<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmKyuyoInfoTake;

//*******************************************
// * sample controller
//*******************************************
class FrmKyuyoInfoTakeController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    //log情報
    private $eWriteOutLogMode = array(
        'eStart' => 0,
        'eNormal' => 1,
        'eEnd' => 3,
        'eErr' => 9
    );
    private $prvArgNM = "給与情報取込";
    public $uploadfile;
    public $frmKyuyoInfoTake;

    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsLogControl');
        $this->loadComponent('ClsComFncJKSYS');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmKyuyoInfoTake_layout');
    }

    //フォーム初期化
    public function frmKyuyoInfoTakeLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $this->frmKyuyoInfoTake = new FrmKyuyoInfoTake();
            //人事コントロールマスタの処理年月取得
            $strRetYM = $this->frmKyuyoInfoTake->procGetJinjiCtrlMst_YM();
            if (!$strRetYM['result']) {
                throw new \Exception($strRetYM['data']);
            }
            $SYORI_YM = "";
            if ($strRetYM["row"] > 0) {
                $SYORI_YM = $strRetYM['data'][0]['SYORI_YM'];
                //日付形式を確認する
                $date = $SYORI_YM . '01';
                if (date('Ymd', strtotime($date)) != $date) {
                    //年月格式正しくない
                    throw new \Exception("String \"" . $SYORI_YM . "\" から型 'Date' への変換は無効です。");
                }
            } else {
                //年月なし
                throw new \Exception("コントロールマスタが存在しません！");
            }
            $result['data']['SYORI_YM'] = $SYORI_YM;

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //既にデータチェック
    public function btnImportClickCheck()
    {
        $result = array(
            'result' => false,
            'error' => '',
            'data' => ''
        );

        try {
            if (isset($_POST['data'])) {
                $dtpYM = $_POST['data']["dtpYM"];
                $kbn = $_POST['data']["kbn"];
                //データ取得
                $this->frmKyuyoInfoTake = new FrmKyuyoInfoTake();
                $result = $this->frmKyuyoInfoTake->procExistCheckData($dtpYM, $kbn);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if (count((array) $result['data']) > 0) {
                    $result['row'] = $result['data'][0]['CNT'];
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //取込_はいボタンクリック
    public function btnImportClick()
    {
        $this->frmKyuyoInfoTake = new FrmKyuyoInfoTake();
        $csvfile = null;
        $result = array(
            'result' => false,
            'error' => '',
            'data' => ''
        );
        $blnTran = FALSE;
        try {
            //ファイルパス
            $Session = $this->request->getSession();
            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
            if (isset($_POST['data'])) {
                $dtpYM = $_POST['data']["dtpYM"];
                $kbn = $_POST['data']["kbn"];
                $txtFile = $_POST['data']["txtFile"];
                //ファイルの新名称
                $csvfile = $pathUpLoad . $Session->read('login_user') . "_" . $txtFile;
                if (!file_exists($csvfile)) {
                    throw new \Exception($csvfile . '存在していないです。');
                }
                //1.開始ログ出力
                $result = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, $this->eWriteOutLogMode["eStart"]);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                //2.奉行データ取込ラインマスタの取得
                //ファイル名の取得
                $getDataFile = $this->ClsComFncJKSYS->FncGetPath('KyuuyoFileNM');
                if ($kbn == '2') {
                    $getDataFile = $this->ClsComFncJKSYS->FncGetPath('SyouyoFileNM');
                }
                $DT = $this->frmKyuyoInfoTake->procGetTorikomiLineMst($getDataFile);
                if (!$DT['result']) {
                    throw new \Exception($DT['data']);
                }
                //3.INSERT句の生成, キー項目の取得ray();
                $colItemSQL = array();
                $colItemKey = array();
                $colValueIdx = array();
                $result = $this->procCretateInsertItemSQL($DT, $colTableNm, $colItemSQL, $colItemKey, $colValueIdx);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
                //トランザクション開始
                $this->frmKyuyoInfoTake->Do_transaction();
                $blnTran = TRUE;
                //4.CSV⇒各マスタへ登録
                $result = $this->procImportCSVData($colTableNm, $colItemSQL, $colValueIdx, $dtpYM, $kbn, $csvfile);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $blnTran = FALSE;
                //5.終了ログ出力
                $result = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, $this->eWriteOutLogMode["eEnd"]);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $result["result"] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->frmKyuyoInfoTake->Do_rollback();
            }
        }
        //ファイル削除
        if (isset($csvfile) && file_exists($csvfile)) {
            @chmod($csvfile, 0777);
            @unlink($csvfile);
        }
        $this->fncReturn($result);
    }

    //ファイルのアップロード
    public function fncCheckFile()
    {
        $result = array(
            'result' => false,
            'data' => ''
        );
        try {
            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
            //フォルダ不存在場合
            if (!file_exists($pathUpLoad)) {
                $file_path = dirname($pathUpLoad);
                if (!(is_readable($file_path) && is_writable($file_path) && is_executable($file_path))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0777);
            } else {
                if (!(is_readable($pathUpLoad) && is_writable($pathUpLoad) && is_executable($pathUpLoad))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            //アップロード失敗
            if ($_FILES["file"]["error"] > 0) {
                throw new \Exception("ファイルのアップロードに失敗しました。");
            } else {
                $result = $this->changeFileName($_FILES["file"]["name"]);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
                $file_name = $result['data'];
                $this->uploadfile = $pathUpLoad . $file_name;
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                    $result['data'] = $this->uploadfile;
                } else {
                    throw new \Exception("ファイルのアップロードに失敗しました。");
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncCheckFileReturn($result);
    }

    //ファイル名称変更
    public function changeFileName($param)
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $this->frmKyuyoInfoTake = new FrmKyuyoInfoTake();
            $strUserID = $this->frmKyuyoInfoTake->GS_LOGINUSER['strUserID'];

            $result['data'] = $strUserID . '_' . $param;

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //CSV⇒マスタへ取込
    public function procImportCSVData($colTableNm, $colItemSQL, $colValueIdx, $dtpYM, $kbn, $csvfile)
    {
        //戻り配列
        $result = array(
            'result' => false,
            'data' => ""
        );
        try {
            //ﾚｺｰﾄﾞｶｳﾝﾄ
            $intReadCounter = 0;
            //正常読込ﾚｺｰﾄﾞ件
            $intNoProbremCounter = 0;
            //不備読込ﾚｺｰﾄﾞ件
            $intInvalidCounter = 0;
            //各マスタデータの削除
            $result = $this->frmKyuyoInfoTake->procDeleteMstData($colTableNm, $dtpYM, $kbn);
            if (!$result['result']) {
                return $result;
            }

            // 每50件出力一个読込完了log
            $CONST_WRITEOUTLOG_LINE_COUNT = 50;

            $maxString = '';
            foreach ($colValueIdx as $value) {
                $maxString .= ',' . $value;
            }
            $maxColumn = explode(',', $maxString);

            //获取文件中数据总行数
            $fileRowNum = $this->getFileRowsNum($csvfile);
            //读取文件
            $fp = new \SplFileObject($csvfile, 'rb');
            //先頭行はタイトル
            $fp->seek(0);
            $firstIdx = null;
            for ($i = 0; $i <= $fileRowNum; ++$i) {
                //当前行内容
                $content = $fp->current();
                $res = $this->codingExchange($content);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                $content = str_replace(
                    array(
                        "\r\n",
                        "\r",
                        "\n"
                    ),
                    "",
                    $content
                );
                $CsvRow = explode(",", $content);
                if ($content == '')//空行
                {
                    //next row
                    $fp->next();
                    continue;
                }
                if ($firstIdx === null) {
                    $firstIdx = $i;
                }
                if (count($CsvRow) < (max($maxColumn) + 1)) {
                    throw new \Exception('インデックスが配列の境界外です。');
                }
                //タイトル以外
                if ($i > $firstIdx) {
                    $intReadCounter += 1;
                    //正常
                    if ($CsvRow[0] !== '') {
                        $intNoProbremCounter += 1;
                        foreach ($colTableNm as $intIdx => $value1) {
                            //INSERT対象の場合INSERTする
                            $result = $this->frmKyuyoInfoTake->procInsertDataToMst($CsvRow, $colItemSQL[$intIdx], $colValueIdx[$intIdx], $dtpYM, $kbn);
                            if (!$result['result']) {
                                return $result;
                            }
                        }
                    } else//不備
                    {
                        $intInvalidCounter += 1;
                    }
                }
                //行カウンタ
                if ($intReadCounter != 0 && fmod($intReadCounter, $CONST_WRITEOUTLOG_LINE_COUNT) == 0) {
                    $strCntMsg = $intReadCounter . "件 読込完了";
                    $result = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, $this->eWriteOutLogMode["eNormal"], $strCntMsg);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                //next row
                $fp->next();
            }

            //その他データの更新
            $result = $this->frmKyuyoInfoTake->procUpdateSonotaData($dtpYM, $kbn);
            if (!$result['result']) {
                return $result;
            }
            $strCntMsg = "トータル：" . $intReadCounter . "件 読込完了";
            $result = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, $this->eWriteOutLogMode["eNormal"], $strCntMsg);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $strCntMsg = "正常：" . $intNoProbremCounter . "件　　不備：" . $intInvalidCounter . "件";
            $result = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, $this->eWriteOutLogMode["eNormal"], $strCntMsg);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            //コミット
            $this->frmKyuyoInfoTake->Do_commit();
        } catch (\Exception $e) {
            //============ ｴﾗｰ処理 ============
            $res = $this->ClsLogControl->procWriteOutLog($this->prvArgNM, $this->eWriteOutLogMode["eErr"], $e->getMessage());
            if (!$res['result']) {
                $result['data'] = $res['data'];
            } else {
                $result['data'] = "取込中にエラーが発生しました。ログを確認してください。";
            }
            $result['result'] = FALSE;
        }
        $fp = null;
        return $result;
    }

    //INSERT句の生成
    public function procCretateInsertItemSQL($DT, &$colTableNm, &$colItemSQL, &$colItemKey, &$colValueIdx)
    {
        //戻り配列
        $result = array(
            'result' => false,
            'error' => ""
        );
        try {
            $strSQL = "";
            $strKeyIdx = "";
            //TRK_SAKI_TABLE_NM值
            $strTableNM = "";
            $strTrkIdx = "";
            //不同的TRK_SAKI_TABLE_NM对应的追加sql文
            $colItemSQL = array();
            $colTableNm = array();
            $colValueIdx = array();

            for ($intIdx = 0; $intIdx < $DT["row"]; $intIdx++) {
                if ($strTableNM <> $DT["data"][$intIdx]["TRK_SAKI_TABLE_NM"]) {
                    //テーブル名ブレーク時、最終行の場合⇒SQLをCollection オブジェクトに格納
                    if ($strTableNM <> "") {
                        $strSQL .= ",CRE_SYA_CD" . "\r\n";
                        $strSQL .= ",CRE_CLT_NM" . "\r\n";
                        $strSQL .= ",CREATE_DATE" . "\r\n";
                        $strSQL .= ",CRE_PRG_ID" . "\r\n";
                        $strSQL .= ") VALUES (" . "\r\n";

                        $strKeyIdx .= ",";

                        $colItemSQL[$strTableNM] = $strSQL;
                        $colItemKey[$strTableNM] = $strKeyIdx;
                        $colValueIdx[$strTableNM] = $strTrkIdx;
                    }

                    $strTableNM = $DT["data"][$intIdx]["TRK_SAKI_TABLE_NM"];
                    $colTableNm[$strTableNM] = $strTableNM;

                    $strSQL = "";
                    $strSQL = "INSERT INTO " . $strTableNM . "(" . "\r\n";
                    $strSQL .= "  TAISYOU_YM" . "\r\n";
                    $strSQL .= ", KS_KB" . "\r\n";
                    $strSQL .= "," . $DT["data"][$intIdx]["TRK_SAKI_KOUMK_ID"] . "\r\n";

                    $strTrkIdx = "";
                    $strTrkIdx .= $DT["data"][$intIdx]["TRK_MOTO_LINE_NO"];
                } else {
                    $strSQL .= "," . $DT["data"][$intIdx]["TRK_SAKI_KOUMK_ID"] . "\r\n";
                    $strTrkIdx .= "," . $DT["data"][$intIdx]["TRK_MOTO_LINE_NO"];
                }
                if ($DT["data"][$intIdx]["PRIMARY_KEY_FLG"] == "1") {
                    $strKeyIdx .= "," . $DT["data"][$intIdx]["TRK_MOTO_LINE_NO"];
                }
                if ($intIdx == $DT["row"] - 1) {
                    //最終行の場合⇒SQLをCollection オブジェクトに格納
                    $strSQL .= ",CRE_SYA_CD" . "\r\n";
                    $strSQL .= ",CRE_CLT_NM" . "\r\n";
                    $strSQL .= ",CREATE_DATE" . "\r\n";
                    $strSQL .= ",CRE_PRG_ID" . "\r\n";
                    $strSQL .= ") VALUES (" . "\r\n";

                    $strKeyIdx .= ",";

                    $colItemSQL[$strTableNM] = $strSQL;
                    $colItemKey[$strTableNM] = $strKeyIdx;
                    $colValueIdx[$strTableNM] = $strTrkIdx;
                }
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //获取文件内数据行数的方法
    public function getFileRowsNum($csvfile)
    {
        $splFileObject = new \SplFileObject($csvfile, 'rb');
        $filesize = filesize($csvfile);
        $splFileObject->seek($filesize);
        $num = $splFileObject->key();
        $splFileObject = null;
        clearstatcache();
        return $num;
    }

    //文件内数据编码格式转换
    public function codingExchange(&$content)
    {
        $result = array(
            'result' => false,
            'data' => ""
        );
        try {
            $detect_order = mb_list_encodings();
            $detect_order = array_diff(
                $detect_order,
                array(
                    "EUC-JP",
                    "SJIS",
                    "JIS"
                )
            );
            setlocale(LC_ALL, 'ja_JP.UTF-8');

            if (strpos($content, "\xEF\xBB\xBF") === 0) {
                $content = substr($content, 3);
            }
            $encoding = mb_detect_encoding($content, $detect_order, true);

            if (!$encoding) {
                // 文字コードの自動判定に失敗
                throw new \Exception('Character set detection failed');
            }
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

}
