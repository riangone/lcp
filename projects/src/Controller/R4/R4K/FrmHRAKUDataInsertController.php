<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHRAKUDataInsert;

class FrmHRAKUDataInsertController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmHRAKUDataInsert = "";
    public $strErrLogPath = "";
    private $prvCsvColumns = array(
        '仕訳No.',
        '仕訳区分',
        '仕訳データ生成日',
        '仕訳データ生成時刻',
        '備考',
        '事業者登録番号',
        '仕訳日',
        '借方：勘定科目コード',
        '借方：勘定科目名',
        '借方：勘定科目：会計連携項目',
        '借方：補助科目コード',
        '借方：補助科目名',
        '借方：補助科目：会計連携項目',
        '借方：補助科目：会計連携補助項目１',
        '借方：補助科目：会計連携補助項目２',
        '借方：補助科目：会計連携補助項目３',
        '借方：補助科目：会計連携補助項目４',
        '借方：負担部門コード',
        '借方：負担部門名',
        '借方：負担部門：会計連携項目',
        '借方：税区分コード',
        '借方：税区分名',
        '借方：税区分：会計連携項目',
        '借方：税計算区分',
        '借方：税率',
        '借方：端数処理',
        '借方：プロジェクトコード',
        '借方：プロジェクト名',
        '借方：プロジェクト：会計連携項目',
        '借方：金額',
        '借方：税額',
        '借方：税抜き額',
        '貸方：勘定科目コード',
        '貸方：勘定科目名',
        '貸方：勘定科目：会計連携項目',
        '貸方：補助科目コード',
        '貸方：補助科目名',
        '貸方：補助科目：会計連携項目',
        '貸方：補助科目：会計連携補助項目１',
        '貸方：補助科目：会計連携補助項目２',
        '貸方：補助科目：会計連携補助項目３',
        '貸方：補助科目：会計連携補助項目４',
        '貸方：負担部門コード',
        '貸方：負担部門名',
        '貸方：負担部門：会計連携項目',
        '貸方：税区分コード',
        '貸方：税区分名',
        '貸方：税区分：会計連携項目',
        '貸方：税計算区分',
        '貸方：税率',
        '貸方：端数処理',
        '貸方：プロジェクトコード',
        '貸方：プロジェクト名',
        '貸方：プロジェクト：会計連携項目',
        '貸方：金額',
        '貸方：税額',
        '貸方：税抜き額',
        '摘要',
        'フリー１',
        'フリー２',
        'フリー３',
        'フリー４',
        'フリー５',
        'フリー６',
        'フリー７',
        'フリー８',
        '伝票種別',
        '申請メニュー名',
        '伝票No.',
        '伝票明細No.',
        '所属部門CD',
        '所属部門名',
        '申請者CD/支払先CD',
        '申請者名/支払先名',
        '申請日',
        '合計',
        '備考',
        'フリー１(ヘッダ)',
        'フリー２(ヘッダ)',
        'フリー１(明細)',
        'フリー２(明細)',
        '換算前額',
        '単位',
        'レート',
        '支払方法',
        '支払先CD',
        '支払先名',
        '出張エリア(ヘッダ)',
        '出張区分(ヘッダ)',
        '未払費用計上(ヘッダ)',
        '支払元（貸方）(ヘッダ)',
        '相手先区分(ヘッダ)',
        '扱者（社員番号）(明細)',
        '注文書No(明細)',
        '紹介者名(明細)',
        '口座発生No(明細)',
        '口座名称(明細)',
        '車台型式(明細)',
        'カーNo(明細)',
        '中古車No(明細)',
        '顧客No(明細)',
        'ﾛｰﾝ･ｸﾚ会社ｺｰﾄﾞ(明細)',
        '損保ロ(明細)',
        '保険会社(明細)',
        '取引区分(明細)',
        '特例区分(明細)',
        '相手先区分(明細)',
    );
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmHRAKUDataInsert_layout');
    }

    public function frmSampleLoad()
    {
        $result = array();
        try {
            $this->FrmHRAKUDataInsert = new FrmHRAKUDataInsert();

            $result1 = $this->FrmHRAKUDataInsert->fncDataSet();

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            $result2 = $this->FrmHRAKUDataInsert->getSyainMstAllData();

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }
            $session = $this->request->getSession();
            $result['BusyoCD'] = $session->read('BusyoCD');
            $result['data']['busyo'] = $result1['data'];
            $result['data']['syain'] = $result2['data'];
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncCheckFile()
    {
        $result = array();
        try {
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            if (!file_exists($pathUpLoad)) {
                //フォルダ権限の判断
                $outFloder = dirname($pathUpLoad);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                mkdir($pathUpLoad, 0777, TRUE);
            } else {
                if (!(is_readable($pathUpLoad) && is_writable($pathUpLoad) && is_executable($pathUpLoad))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            if ($_FILES["file"]["error"] > 0) {
                throw new \Exception("ファイルのアップロードに失敗しました。");
            } else {
                $file_name = $_FILES["file"]["name"];
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


    public function btnImportClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => '',
            'msg' => ''
        );
        $blnTran = FALSE;
        $this->FrmHRAKUDataInsert = new FrmHRAKUDataInsert();
        try {
            $postData = $_POST["data"];

            //開始ログ出力
            $date = date("Y/m/d H:i:s");
            $result = $this->fncOutLog("データ取込み開始:" . $date . "\r\n", FALSE);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }

            //トランザクションを開始する
            $this->FrmHRAKUDataInsert->Do_transaction();
            $blnTran = TRUE;

            $result = $this->fncCsvRead($postData);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $postData['Kensu'] = $result['data'];

            $this->FrmHRAKUDataInsert->Do_commit();
            $blnTran = FALSE;

            //終了ログ出力
            $date = date("Y/m/d H:i:s");
            $result = $this->fncOutLog("データ取込み終了:" . $date . "\r\n", True);
            if (!$result['result']) {
                throw new \Exception($result['error']);
            }
            $result['data'] = "";
            $result['result'] = true;
        } catch (\Exception $e) {
            if ($blnTran) {
                $this->FrmHRAKUDataInsert->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //CSV取込
    public function fncCsvRead($postData)
    {
        $lngRctCnt = 0; // ﾚｺｰﾄﾞｶｳﾝﾄ
        $strRecArr = array(); // 読込みﾚｺｰﾄﾞ（配列）
        $blnErr = FALSE; // ｴﾗｰﾌﾗｸﾞ
        $blnFmtErr = FALSE;
        $pathUpLoad = null;
        $result = array(
            'result' => FALSE,
            'error' => '',
            'msg' => ''
        );
        try {
            $filename = $postData['txtFile']; //入力データ.csv
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $pathUpLoad . $filename;
            if (!file_exists($pathUpLoad)) {
                //文件处理异常
                $result['data'] = '対象ファイルが存在していません。';
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
                $systemTime = date('Y-m-d H:i:s');
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
                        for ($j = 0; $j <= count($strRecArr) - 1; $j++) {
                            str_replace("'", "''", str_replace("\"\"", "", $strRecArr[$j]));
                        }

                        //正常ﾃﾞｰﾀの場合はDB登録
                        if ($blnErr == FALSE) {
                            //更新項目を設定
                            $rowdata["SHIWAKE_NO"] = $strRecArr[0];
                            $rowdata["SHIWAKE_KBN"] = $strRecArr[1];
                            $rowdata["SHIWAKE_CRE_DATE"] = $strRecArr[2];
                            $rowdata["SHIWAKE_CRE_TIME"] = $strRecArr[3];
                            $rowdata["BIKO1"] = $strRecArr[4];
                            $rowdata["BUSI_REGIST_NUM"] = $strRecArr[5];
                            $rowdata["SHIWAKE_DATE"] = $strRecArr[6];
                            $rowdata["L_KANJYOU_CD"] = $strRecArr[7];
                            $rowdata["L_KANJYOU_NM"] = $strRecArr[8];
                            $rowdata["L_KANJYOU_KAIKEI"] = $strRecArr[9];
                            $rowdata["L_HOJYO_CD"] = $strRecArr[10];
                            $rowdata["L_HOJYO_NM"] = $strRecArr[11];
                            $rowdata["L_HOJYO_KAIKEI"] = $strRecArr[12];
                            $rowdata["L_HOJYO_KAIKEI_HOJYO1"] = $strRecArr[13];
                            $rowdata["L_HOJYO_KAIKEI_HOJYO2"] = $strRecArr[14];
                            $rowdata["L_HOJYO_KAIKEI_HOJYO3"] = $strRecArr[15];
                            $rowdata["L_HOJYO_KAIKEI_HOJYO4"] = $strRecArr[16];
                            $rowdata["L_FUTAN_BUMON_CD"] = str_pad($strRecArr[17], 3, '0', STR_PAD_RIGHT);
                            $rowdata["L_FUTAN_BUMON_NM"] = $strRecArr[18];
                            $rowdata["L_FUTAN_BUMON_KAIKEI"] = $strRecArr[19];
                            $rowdata["L_TAX_KBN_CD"] = $strRecArr[20];
                            $rowdata["L_TAX_KBN_NM"] = $strRecArr[21];
                            $rowdata["L_TAX_KBN_KAIKEI"] = $strRecArr[22];
                            $rowdata["L_TAX_CALC_KBN"] = $strRecArr[23];
                            $rowdata["L_TAX"] = $strRecArr[24];
                            $rowdata["L_ODD"] = $strRecArr[25];
                            $rowdata["L_PRO_CD"] = $strRecArr[26];
                            $rowdata["L_PRO_NM"] = $strRecArr[27];
                            $rowdata["L_PRO_KAIKEI"] = $strRecArr[28];
                            $rowdata["L_AMOUNT"] = $strRecArr[29];
                            $rowdata["L_TAX_AMOUNT"] = $strRecArr[30];
                            $rowdata["L_NOTAX_AMOUNT"] = $strRecArr[31];
                            $rowdata["R_KANJYOU_CD"] = $strRecArr[32];
                            $rowdata["R_KANJYOU_NM"] = $strRecArr[33];
                            $rowdata["R_KANJYOU_KAIKEI"] = $strRecArr[34];
                            $rowdata["R_HOJYO_CD"] = $strRecArr[35];
                            $rowdata["R_HOJYO_NM"] = $strRecArr[36];
                            $rowdata["R_HOJYO_KAIKEI"] = $strRecArr[37];
                            $rowdata["R_HOJYO_KAIKEI_HOJYO1"] = $strRecArr[38];
                            $rowdata["R_HOJYO_KAIKEI_HOJYO2"] = $strRecArr[39];
                            $rowdata["R_HOJYO_KAIKEI_HOJYO3"] = $strRecArr[40];
                            $rowdata["R_HOJYO_KAIKEI_HOJYO4"] = $strRecArr[41];
                            $rowdata["R_FUTAN_BUMON_CD"] = str_pad($strRecArr[42], 3, '0', STR_PAD_RIGHT);
                            $rowdata["R_FUTAN_BUMON_NM"] = $strRecArr[43];
                            $rowdata["R_FUTAN_BUMON_KAIKEI"] = $strRecArr[44];
                            $rowdata["R_TAX_KBN_CD"] = $strRecArr[45];
                            $rowdata["R_TAX_KBN_NM"] = $strRecArr[46];
                            $rowdata["R_TAX_KBN_KAIKEI"] = $strRecArr[47];
                            $rowdata["R_TAX_CALC_KBN"] = $strRecArr[48];
                            $rowdata["R_TAX"] = $strRecArr[49];
                            $rowdata["R_ODD"] = $strRecArr[50];
                            $rowdata["R_PRO_CD"] = $strRecArr[51];
                            $rowdata["R_PRO_NM"] = $strRecArr[52];
                            $rowdata["R_PRO_KAIKEI"] = $strRecArr[53];
                            $rowdata["R_AMOUNT"] = $strRecArr[54];
                            $rowdata["R_TAX_AMOUNT"] = $strRecArr[55];
                            $rowdata["R_NOTAX_AMOUNT"] = $strRecArr[56];
                            $rowdata["TEKYO"] = $strRecArr[57];
                            $rowdata["FREE1"] = $strRecArr[58];
                            $rowdata["FREE2"] = $strRecArr[59];
                            $rowdata["FREE3"] = $strRecArr[60];
                            $rowdata["FREE4"] = $strRecArr[61];
                            $rowdata["FREE5"] = $strRecArr[62];
                            $rowdata["FREE6"] = $strRecArr[63];
                            $rowdata["FREE7"] = $strRecArr[64];
                            $rowdata["FREE8"] = $strRecArr[65];
                            $rowdata["DENPYOU_TYPE"] = $strRecArr[66];
                            $rowdata["REQU_MENU_NM"] = $strRecArr[67];
                            $rowdata["DENPYOU_NO"] = $strRecArr[68];
                            $rowdata["DENPYOU_DETAIL_NO"] = $strRecArr[69];
                            $rowdata["BUMON_CD"] = $strRecArr[70];
                            $rowdata["BUMON_MN"] = $strRecArr[71];
                            $rowdata["REQU_USER_CD"] = $strRecArr[72];
                            $rowdata["REQU_USER_NM"] = $strRecArr[73];
                            $rowdata["REQU_DATE"] = $strRecArr[74];
                            $rowdata["TOTAL"] = $strRecArr[75];
                            $rowdata["BIKO2"] = $strRecArr[76];
                            $rowdata["FREE1_HEADER"] = $strRecArr[77];
                            $rowdata["FREE2_HEADER"] = $strRecArr[78];
                            $rowdata["FREE1_DETAIL"] = $strRecArr[79];
                            $rowdata["FREE2_DETAIL"] = $strRecArr[80];
                            $rowdata["CALC_MAE_AMOUNT"] = $strRecArr[81];
                            $rowdata["UNIT"] = $strRecArr[82];
                            $rowdata["RATE"] = $strRecArr[83];
                            $rowdata["SHIHARA_HOUHOU"] = $strRecArr[84];
                            $rowdata["SHIHARASAKI_CD"] = $strRecArr[85];
                            $rowdata["SHIHARASAKI_NM"] = $strRecArr[86];
                            $rowdata["SYUTTYO_AREA_HEADER"] = $strRecArr[87];
                            $rowdata["SYUTTYO_KBN_HEADER"] = $strRecArr[88];
                            $rowdata["UNPAID_EXPENSES_HEADER"] = $strRecArr[89];
                            $rowdata["PAYER_HEADER"] = $strRecArr[90];
                            $rowdata["AITE_KBN_HEADER"] = $strRecArr[91];
                            $rowdata["KOSYA_DETAIL"] = $strRecArr[92];
                            $rowdata["TYUMONSYO_NO_DETAIL"] = $strRecArr[93];
                            $rowdata["SYAOKAISYA_NM_DETAIL"] = $strRecArr[94];
                            $rowdata["KOUZA_HSSEI_NO_DETAIL"] = $strRecArr[95];
                            $rowdata["KOUZA_NM_DETAIL"] = $strRecArr[96];
                            $rowdata["CAR_TYPE_DETAIL"] = $strRecArr[97];
                            $rowdata["CAR_NO_DETAIL"] = $strRecArr[98];
                            $rowdata["TYUKOSYA_NO_DETAIL"] = $strRecArr[99];
                            $rowdata["CUSTOMER_NO_DETAIL"] = $strRecArr[100];
                            $rowdata["LOAN_CRE_CD_DETAIL"] = $strRecArr[101];
                            $rowdata["ZONBO_DETAIL"] = $strRecArr[102];
                            $rowdata["HOKENGAISYA_DETAIL"] = $strRecArr[103];
                            $rowdata["TORIHIKI_KBN_DETAIL"] = $strRecArr[104];
                            $rowdata["TOKUREI_KBN"] = $strRecArr[105];
                            $rowdata["AITE_KBN"] = $strRecArr[106];

                            $result = $this->FrmHRAKUDataInsert->fncInsRAKUDataSQL($rowdata, $filename, $systemTime);
                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }
                    //next row
                    $fp->next();
                }
                $result['result'] = true;
            }
        } catch (\Exception $e) {
            $res = $this->fncOutLog($e->getMessage() . "\r\n");
            $result['result'] = FALSE;
            if ($res['result']) {
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
        return $result;
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
            $strPath = dirname(dirname(dirname(dirname(__FILE__))));
            $strErrLogPath = $strPath . "/" . $this->ClsComFnc->FncGetPath('PprErrLog');
            if (!file_exists($strErrLogPath)) {
                if (!mkdir($strErrLogPath, 0777, TRUE)) {
                    throw new \Exception("フォルダー作成失敗しました。");
                }
                chmod($strErrLogPath, 0777);
            }
            $strLogPath = $strErrLogPath . "楽楽からR4へデータ取込.log";
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
    //ファイル内のデータ行数を取得する方法
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
    //========== 設定関連 start ==========
    //重複チェック
    public function repeatCheck()
    {
        $this->FrmHRAKUDataInsert = new FrmHRAKUDataInsert();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception('no param');
            }
            //重複チェック
            $repeat = $this->FrmHRAKUDataInsert->repeatCheck($_POST['data']['grNm']);
            if (!$repeat['result']) {
                throw new \Exception($repeat['data']);
            }
            if ($repeat['row'] > 0) {
                throw new \Exception('グループ名がすでに使用されています');
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
    //========== 設定関連 end ==========
}
