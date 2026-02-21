<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmHyogoInfoTake;

//*******************************************
// * sample controller
//*******************************************
class FrmHyogoInfoTakeController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    private $prvCsvColumns = array(
        '社員番号',
        '氏名',
        '最終評価－判定値',
        '最終評価－評価',
        "最終評価－評価値"
    );
    public $FrmHyogoInfoTake;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'FrmHyogoInfoTake_layout');
    }

    public function frmSyokusyubetuKamokuMenteLoad()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $this->FrmHyogoInfoTake = new FrmHyogoInfoTake();

            $result['combo'] = $this->FrmHyogoInfoTake->fncSetCombo();
            if (!$result['combo']['result']) {
                throw new \Exception($result['combo']['data']);
            }

            $result['kikan'] = $this->FrmHyogoInfoTake->fncSetKikan();

            if (!$result['kikan']['result']) {
                throw new \Exception($result['kikan']['data']);
            }
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    public function fncSearchSpread()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $this->FrmHyogoInfoTake = new FrmHyogoInfoTake();
            $result = $this->FrmHyogoInfoTake->fncHyoukaTorikomiRirekiData();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
            $start = $tmpJqgridShow['start'];
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = (int) $tmpJqgridShow['count'];

            $tmpJqgrid = $this->ClsComFncJKSYS->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);

            $result = $tmpJqgrid;
        } catch (\Exception $e) {
            $result['result'] = true;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncCheckFile()
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        try {
            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
            if (!file_exists($pathUpLoad)) {
                if (!mkdir($pathUpLoad, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($pathUpLoad, 0777);
            }
            if ($_FILES["file"]["error"] > 0) {
                throw new \Exception("ファイルのアップロードに失敗しました。");
            } else {
                $result = $this->changeFileName($_FILES["file"]["name"]);
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
                $file_name = $result['data'];
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
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

    public function fncDeleteClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $blnTran = FALSE;

        $this->FrmHyogoInfoTake = new FrmHyogoInfoTake();
        try {
            $postData = $_POST["data"];
            //トランザクション開始
            $this->FrmHyogoInfoTake->Do_transaction();
            $blnTran = TRUE;

            $result = $this->FrmHyogoInfoTake->fncDelHyoukaRirekiData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result = $this->FrmHyogoInfoTake->fncDelHyoukaTorikomiRirekiData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmHyogoInfoTake->Do_commit();
            $blnTran = FALSE;
            $result['data'] = '';
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->FrmHyogoInfoTake->Do_rollback();
            }
        }

        $this->fncReturn($result);

    }

    public function fncHyoukaRirekiCheck()
    {
        $postData = $_POST["data"];
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        $this->FrmHyogoInfoTake = new FrmHyogoInfoTake();
        try {
            $result = $this->FrmHyogoInfoTake->fncHyoukaRirekiData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncTorikomi()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'msg' => ''
        );
        $blnTran = FALSE;
        $this->FrmHyogoInfoTake = new FrmHyogoInfoTake();

        try {
            $postData = $_POST["data"];
            //開始ログ出力
            $date = date("Y/m/d H:i:s");
            $result = $this->fncOutLog("評価情報取込み開始:" . $date . "\r\n", FALSE);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }

            //トランザクションを開始する
            $this->FrmHyogoInfoTake->Do_transaction();
            $blnTran = TRUE;

            //評価履歴データを削除する
            $result = $this->FrmHyogoInfoTake->fncDelHyoukaRirekiData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //CSVを取込み、評価履歴データに更新する
            $result = $this->fncCsvRead($postData);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }

            $postData['Kensu'] = $result['data'];

            //評価取込履歴データを削除する
            $result = $this->FrmHyogoInfoTake->fncDelHyoukaTorikomiRirekiData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //評価取込履歴データに登録する
            $result = $this->FrmHyogoInfoTake->fncInsHyoukaTorikomiRirekiData($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmHyogoInfoTake->Do_commit();
            $blnTran = FALSE;

            //終了ログ出力
            $date = date("Y/m/d H:i:s");
            $result = $this->fncOutLog("評価情報取込み終了:" . $date . "\r\n", True);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $result['data'] = "";
            $result['result'] = true;
        } catch (\Exception $e) {
            if ($blnTran) {
                $this->FrmHyogoInfoTake->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //ログファイル出力
    public function fncOutLog($strOutMsg, $blnAppend = TRUE)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'msg' => ''
        );
        try {
            $strPath = dirname(dirname(dirname(__FILE__)));
            $strErrLogPath = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JKImportCsvPath');
            if (!file_exists($strErrLogPath)) {
                if (!mkdir($strErrLogPath, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($strErrLogPath, 0777);
            }
            $strLogPath = $strErrLogPath . "/JKImportCsv.Log";
            if ($blnAppend) {

                $objSw = fopen($strLogPath, "a");
            } else {
                $objSw = fopen($strLogPath, "w");
            }
            fwrite($objSw, $strOutMsg);
            fclose($objSw);
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    //CSV取込
    public function fncCsvRead($postData = NULL)
    {
        $lngRctCnt = 0; // ﾚｺｰﾄﾞｶｳﾝﾄ
        $strRecArr = array(); // 読込みﾚｺｰﾄﾞ（配列）
        $blnErr = FALSE; // ｴﾗｰﾌﾗｸﾞ
        $sqlstr = ""; // 実行ｸｴﾘ
        $blnFmtErr = FALSE;
        $pathUpLoad = null;
        $result = array(
            'result' => FALSE,
            'error' => '',
            'msg' => ''
        );

        try {
            //INSERT文の取得
            $sqlstr_ins = $this->FrmHyogoInfoTake->fncInsHyoukaRirekiDataSQL($postData);

            $result = $this->changeFileName($postData['FileName']);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $filename = $result['data'];
            $strPath = dirname(dirname(dirname(__FILE__)));
            $pathUpLoad = $strPath . "/" . $this->ClsComFncJKSYS->FncGetPath('JksysUpLoad');
            $pathUpLoad = $pathUpLoad . $filename;
            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['data'] = '対象ﾌｧｲﾙが存在していません。';
                throw new \Exception($result['data']);
            }

            $blnFmtErr = TRUE;
            $exitTryBool = FALSE;
            //获取文件中数据总行数
            $fileRowNum = $this->getFileRowsNum($pathUpLoad);
            //读取文件
            $fp = new \SplFileObject($pathUpLoad, 'rb');
            //先頭行はタイトル S
            $fp->seek(0);
            $content = $fp->current();
            $res = $this->codingExchange($content);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $strRecArr = explode(",", $content);
            if (count($this->prvCsvColumns) !== count($strRecArr)) {
                $exitTryBool = TRUE;
            }
            //終了判定
            if ($content == "" || $content == null || str_replace(array("\r\n", "\r", "\n"), '', $content) == '') {
                $exitTryBool = TRUE;
            } else
                if (ord($content) == 26) {
                    $exitTryBool = TRUE;
                } else {
                    for ($i = 0; $i <= count($this->prvCsvColumns) - 1; $i++) {
                        str_replace("\"\"", "", $strRecArr[$i]);
                        if (trim($strRecArr[$i]) !== $this->prvCsvColumns[$i]) {
                            $exitTryBool = TRUE;
                            break;
                        }
                    }
                }
            //先頭行はタイトル E
            if (!$exitTryBool) {
                $blnFmtErr = FALSE;
                $fp->next();
                for ($i = 1; $i < $fileRowNum; ++$i) {
                    //当前行内容
                    $content = $fp->current();
                    $res = $this->codingExchange($content);
                    if (!$res['result']) {
                        throw new \Exception($res['data']);
                    }
                    $content = str_replace(array("\r\n", "\r", "\n"), "", $content);
                    //読込みﾚｺｰﾄﾞをｶﾝﾏで分割
                    $strRecArr = explode(",", $content);
                    //跳过空行
                    if ($content == '') {
                        $fp->next();
                        continue;
                    }
                    //ﾚｺｰﾄﾞ件数をｶｳﾝﾄｱｯﾌﾟ
                    $lngRctCnt += 1;
                    //項目数が正常な場合のみ、項目のﾁｪｯｸ処理を行う
                    if (!$blnFmtErr) {
                        if (count($strRecArr) <= 4) {
                            throw new \Exception("インデックスが配列の境界外です。");
                        }
                        for ($j = 0; $j <= count($strRecArr) - 1; $j++) {
                            str_replace("'", "''", str_replace("\"\"", "", $strRecArr[$j]));
                        }
                        $result = $this->fncCheckRecord($strRecArr, $lngRctCnt, $blnErr);
                        if (!$result['result']) {
                            throw new \Exception($result['error']);
                        }

                        //正常ﾃﾞｰﾀの場合はDB登録
                        if ($blnErr == FALSE) {
                            //実行ｸｴﾘの初期化
                            $sqlstr = $sqlstr_ins;
                            //更新項目を設定
                            $sqlstr = str_replace("@SYAIN_NO", rtrim($strRecArr[0]), $sqlstr);
                            $sqlstr = str_replace("@LAST_HYOUKACHI", rtrim($strRecArr[4]), $sqlstr);
                            $sqlstr = str_replace("@LAST_HANTEI", rtrim($strRecArr[2]), $sqlstr);
                            $sqlstr = str_replace("@LAST_HYOUKA", rtrim($strRecArr[3]), $sqlstr);

                            $result = $this->FrmHyogoInfoTake->fncInsHyoukaRirekiData($sqlstr);
                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }
                    //next row
                    $fp->next();
                }
                $result['result'] = true;
                //取込件数
                $result['data'] = $lngRctCnt;
            }
        } catch (\Exception $e) {
            $res = $this->fncOutLog($e->getMessage() . "\r\n");
            $result['result'] = FALSE;
            if ($res['result']) { // fncOutLog以外的异常，如fncCheckRecord方法的异常，写入log
                $result['error'] = '';
                $result['msg'] = '取込中にエラーが発生しました。ログを確認してください。';
            } else {
                $result['error'] = $res['error'];
            }
        }
        //============ 終了処理 ============
        $fp = null;
        //ファイル削除
        if (isset($pathUpLoad) && file_exists($pathUpLoad)) {
            @chmod($pathUpLoad, 0777);
            @unlink($pathUpLoad);
        }
        if ($blnFmtErr) {
            $result['result'] = FALSE;
            $result['error'] = '';
            $result['msg'] = "フォーマットが違います。取り込もうとしたCSVをチェックしてください";
        }
        if ($blnErr) {
            $result['result'] = FALSE;
            $result['error'] = '';
            $result['msg'] = "取込中にエラーが発生しました。ログを確認してください。";
        }
        return $result;
    }

    //エラーチェック
    public function fncCheckRecord($strRecArr, $lngRctCnt, $blnErr)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'msg' => ''
        );
        try {
            $ErrRcCnt = $lngRctCnt + 1;
            //社員番号
            if ($this->ClsComFncJKSYS->fncNv(rtrim($strRecArr[0])) <> '') {
                if ($this->ClsComFncJKSYS->GetByteCount($this->ClsComFncJKSYS->fncNv(rtrim($strRecArr[0]))) > 5) {
                    $result = $this->fncOutLog($ErrRcCnt . "行目：社員番号の桁数が不正です。（5桁以下）" . $strRecArr[0] . "\r\n");
                    if (!$result['result']) {
                        throw new \Exception($result['error']);
                    }
                    $blnErr = TRUE;
                }
            } else {
                $result = $this->fncOutLog($ErrRcCnt . "行目：社員番号が未入力です。" . "\r\n");
                if (!$result['result']) {
                    throw new \Exception($result['error']);
                }
                $blnErr = TRUE;
            }
            //最終評価－判定値
            if ($this->ClsComFncJKSYS->fncNv(rtrim($strRecArr[2])) <> '') {
                if (is_numeric($this->ClsComFncJKSYS->FncNv(rtrim($strRecArr[2]))) == false) {
                    $result = $this->fncOutLog($ErrRcCnt . "行目：最終評価－判定値が数値以外です。" . $strRecArr[2] . "\r\n");
                    if (!$result['result']) {
                        throw new \Exception($result['error']);
                    }
                    $blnErr = TRUE;
                }
            }
            //最終評価－評価
            if ($this->ClsComFncJKSYS->FncNv(rtrim($strRecArr[3])) <> '') {
                if ($this->ClsComFncJKSYS->GetByteCount($this->ClsComFncJKSYS->FncNv(rtrim($strRecArr[3]))) > 4) {
                    $result = $this->fncOutLog($ErrRcCnt . "行目：最終評価－評価の桁数が不正です。（2桁以下）" . $strRecArr[3] . "\r\n");
                    if (!$result['result']) {
                        throw new \Exception($result['error']);
                    }
                    $blnErr = TRUE;
                }
            }
            //最終評価－評価値
            if ($this->ClsComFncJKSYS->FncNv(rtrim($strRecArr[4])) <> '') {
                if (is_numeric($this->ClsComFncJKSYS->FncNv(rtrim($strRecArr[4]))) == false) {
                    $result = $this->fncOutLog($ErrRcCnt . "行目：最終評価－評価値が数値以外です。" . $strRecArr[4] . "\r\n");
                    if (!$result['result']) {
                        throw new \Exception($result['error']);
                    }
                    $blnErr = TRUE;
                }
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        return $result;
    }

    public function changeFileName($param)
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'msg' => ''
        );
        try {
            $FrmHyogoInfoTake = new FrmHyogoInfoTake();
            $strUserID = $FrmHyogoInfoTake->GS_LOGINUSER['strUserID'];
            $arr = explode(".", $param);
            $long = count($arr) - 1;
            $file_type = $arr[$long];
            $file_name = '';
            for ($i = 0; $i < $long; $i++) {
                $file_name = $file_name . $arr[$i] . '.';
            }
            $file_name = substr($file_name, 0, strlen($file_name) - 1);
            $file_name = $strUserID . '_' . $file_name . '.' . $file_type;
            $result['result'] = true;
            $result['data'] = $file_name;
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
        return $num + 1;
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
                    "SJIS"
                )
            );
            array_unshift($detect_order, 'JIS');
            setlocale(LC_ALL, 'ja_JP.UTF-8');

            if (strpos($content, "\xEF\xBB\xBF") === 0) {
                $content = substr($content, 3);
            }
            $encoding = mb_detect_encoding($content, $detect_order, true);

            if (!$encoding) {
                // 文字コードの自動判定に失敗
                throw new \Exception('Character set detection failed');
            }

            if ($encoding == 'JIS') {
                foreach ($this->prvCsvColumns as $key => $value) {
                    $this->prvCsvColumns[$key] = str_replace("－", "−", $value);
                }
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
