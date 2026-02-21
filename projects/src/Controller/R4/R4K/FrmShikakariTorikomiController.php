<?php
namespace App\Controller\R4\R4K;

/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151012           #2093                        BUG                              Yuanjh
 * 20201020 		  MAPのデータ取込追加			   依頼								YIN
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

use App\Controller\AppController;
use App\Model\R4\R4K\FrmShikakariTorikomi;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FrmShikakariTorikomiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    // //20201020 YIN INS S
    // public $readFomart = "";
    // //20201020 YIN INS E

    // //取込ﾌｧｲﾙの項目番号
    private $E_FILE_COL = array(
        'E_SYORI_DT' => 0,
        //処理日      処理日追加   処理日追加により部品名以降番号1プラス
        'E_BUHIN_MEI' => 1,
        //部品名
        'E_KINGAKU' => 2,
        //金額
        'E_SYADAIKATA' => 3,
        //車台型式
        'E_CAR_NO' => 4 //カー№
    );
    private $FrmShikakariTorikomi;
    private $readFomart;
    private $strErrLogPath;
    private $Session;
    // public $ClsComFnc = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmShikakariTorikomi_layout');
    }

    public function frmSampleLoad()
    {
        $result = array();
        try {
            $this->FrmShikakariTorikomi = new FrmShikakariTorikomi();
            $result = $this->FrmShikakariTorikomi->reselect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
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
            //$pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
            $pathUpLoad = $this->ClsComFnc->FncGetPath('UpLoad');

            if (!file_exists($pathUpLoad)) {
                mkdir($pathUpLoad, 0777, TRUE);
            }
            if ($_FILES["file"]["error"] > 0) {
                $result['result'] = FALSE;
                $result['data'] = "ファイルのアップロードに失敗しました。";
                throw new \Exception($result['data']);
            } else {
                $file_name = $this->changeFileName($_FILES["file"]["name"]);
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $pathUpLoad . $file_name)) {
                    $result['result'] = TRUE;
                } else {
                    $result['result'] = FALSE;
                    $result['data'] = 'ファイルのアップロードに失敗しました。';
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncCheckFileReturn($result);
    }

    public function changeFileName($param)
    {
        $this->FrmShikakariTorikomi = new FrmShikakariTorikomi();
        $strUserID = $this->FrmShikakariTorikomi->GS_LOGINUSER['strUserID'];
        $arr = explode(".", $param);
        $long = count($arr) - 1;
        $file_type = $arr[$long];
        $file_name = '';
        for ($i = 0; $i < $long; $i++) {
            $file_name = $file_name . $arr[$i] . '.';
        }
        $file_name = substr($file_name, 0, strlen($file_name) - 1);
        $file_name = $strUserID . '_' . $file_name . '.' . $file_type;

        return $file_name;
    }

    public function cmdActClick()
    {
        $blnTrn = FALSE;
        $blnConn = FALSE;
        $intHitNum = 0;
        $result = array();
        $Do_conn = array();
        $Do_Execute = array();
        //the file's type.
        $strExt = "";
        //処理年月
        $cboYM = "";
        //the file's name
        $txtFile = "";
        //CSVファイル取込の 戻り配列
        $CsvResult = array();
        //EXCELファイル取込の 戻り配列
        $ExcelResult = array();
        try {
            //20201020 YIN INS S
            $this->readFomart = $_POST['data']['readFomart'];
            if ($this->readFomart == "map") {
                $this->E_FILE_COL = array(
                    'E_SYORI_DT' => 0,
                    //処理日      処理日追加   処理日追加により部品名以降番号1プラス
                    'E_BUHIN_MEI' => 3,
                    //部品名
                    'E_KINGAKU' => 4,
                    //金額
                    'E_SYADAIKATA' => 1,
                    //車台型式
                    'E_CAR_NO' => 2 //カー№
                );
            }
            //20201020 YIN INS E
            $strExt = $_POST['data']['strExt'];
            $cboYM = $_POST['data']['cboYM'];
            $txtFile = $_POST['data']['txtFile'];
            $this->Session = $this->request->getSession();
            $txtFile = $this->Session->read('login_user') . "_" . $txtFile;


            $Path = dirname(dirname(dirname(dirname(__FILE__))));
            //LOG出力ﾊﾟｽを取得する
            $strPath = $this->ClsComFnc->FncGetPath("PprErrLog");

            //CSV出力先のファイル設定
            $intHitNum = strripos($strPath, "/");

            if ($intHitNum == FALSE) {
                $this->strErrLogPath = "mnt/temp";
            } else {
                $this->strErrLogPath = substr($strPath, 0, $intHitNum);
            }
            $this->strErrLogPath = $Path . "/" . $this->strErrLogPath . "/";
            //------------------------------
            //   取込処理
            //------------------------------
            $now = date('Y/m/d H:i:s');
            //ﾛｸﾞﾌｧｲﾙを初期化
            $this->fncOutLog("取込開始:" . $now, FALSE);

            $this->FrmShikakariTorikomi = new FrmShikakariTorikomi();
            //DB接続
            $Do_conn = $this->FrmShikakariTorikomi->Do_conn();
            if (!$Do_conn['result']) {
                throw new \Exception($Do_conn['data']);
            }
            $blnConn = TRUE;
            //トランザクション開始
            $this->FrmShikakariTorikomi->Do_transaction();
            $blnTrn = TRUE;
            switch ($strExt) {
                case "TXT":
                    //取込ﾃｰﾌﾞﾙを初期化
                    $Do_Execute = $this->FrmShikakariTorikomi->fncTableDelete(str_replace("/", "", $cboYM), "0");
                    if (!$Do_Execute['result']) {
                        $this->fncOutLog($Do_Execute['data']);
                        throw new \Exception($Do_Execute['data']);
                    }
                    //CSVファイル取込
                    $CsvResult = $this->fncCsvRead($txtFile, $cboYM);
                    if ($CsvResult['result'] == FALSE) {
                        throw new \Exception($CsvResult['data']);
                    }
                    break;
                case "XLS":
                    //取込ﾃｰﾌﾞﾙを初期化
                    //20201020 YIN INS S
                    if ($this->readFomart == "buhan") {
                        //20201020 YIN INS E
                        $Do_Execute = $this->FrmShikakariTorikomi->fncTableDelete(str_replace("/", "", $cboYM), "1");
                        //20201020 YIN INS S
                    } else {
                        $Do_Execute = $this->FrmShikakariTorikomi->fncTableDelete(str_replace("/", "", $cboYM), "0");
                    }
                    //20201020 YIN INS E
                    if (!$Do_Execute['result']) {
                        $this->fncOutLog($Do_Execute['data']);
                        throw new \Exception($Do_Execute['data']);
                    }

                    //EXCELファイル取込
                    $ExcelResult = $this->fncFileRead($txtFile, $cboYM);
                    if ($ExcelResult['result'] == FALSE) {
                        throw new \Exception($ExcelResult['data']);
                    }
                    break;
            }

            //ｺﾐｯﾄ
            $this->FrmShikakariTorikomi->Do_commit();
            $blnTrn = FALSE;
            //ﾛｸﾞﾌｧｲﾙ出力
            $now = date('Y/m/d H:i:s');
            $this->fncOutLog("正常終了:" . $now);
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            //============ ｴﾗｰ処理 ============
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        //============ 終了処理 ============
        //ﾛｰﾙﾊﾞｯｸ
        if ($blnTrn == TRUE) {
            $this->FrmShikakariTorikomi->Do_rollback();
        }
        //DB接続解除
        if ($blnConn == TRUE) {
            $this->FrmShikakariTorikomi->Do_close();
        }

        $this->fncReturn($result);
    }

    //********************************************************************
    //処理概要：ﾛｸﾞﾌｧｲﾙ出力
    //引　　数：strOutMsg         ﾛｸﾞ出力文字列
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    public function fncOutLog($strOutMsg, $blnAppend = TRUE)
    {
        if (!file_exists($this->strErrLogPath)) {
            mkdir($this->strErrLogPath, 0777, TRUE);
        }
        //$strLogPath = $this -> strErrLogPath . "仕掛データ取込.log";
        $strLogPath = $this->strErrLogPath . "FrmShikakariTorikomi.log";

        if ($blnAppend == TRUE) {
            $sw = fopen($strLogPath, "a+");
            fwrite($sw, "\r\n" . $strOutMsg);
        } else {
            $sw = fopen($strLogPath, "w+");
            fwrite($sw, $strOutMsg);
        }
        fclose($sw);
        //fclose($objSw);
    }

    //********************************************************************
    //処理概要：指定ﾌｧｲﾙの情報をﾃｰﾌﾞﾙに取込む（CSV）
    //引　　数：1.$strFilepath 2.$cboYM
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    public function fncCsvRead($strFilepath, $cboYM)
    {
        //ｽﾄﾘｰﾑﾘｰﾀﾞ  StreamReader
        $sr = "";
        //読込みﾚｺｰﾄﾞ
        $strRecord = "";
        //読込みﾚｺｰﾄﾞ（配列）
        $strRecArr = array();
        //ﾚｺｰﾄﾞｶｳﾝﾄ
        $lngRcCnt = 0;
        //ｴﾗｰﾌﾗｸﾞ
        $blnErr = FALSE;
        $strKoumokuMei = array(
            "エアコン",
            "ステレオ",
            "外装",
            "内装",
            "足廻り",
            "看板",
            "塗装",
            "社外ナビ",
            "帆シート",
            "木工鉄鋼",
            "社外部品",
            "特装"
        );
        $intSeqNo = -1;
        $intCnt = "";
        //SEQNO取得の戻り配列
        $Do_Fill = array();
        //Insertの戻り配列
        $Do_Execute = array();
        //戻り配列
        $result = array(
            'result' => TRUE,
            'data' => ""
        );
        try {
            $Path = dirname(dirname(dirname(dirname(__FILE__))));
            //$strFilepath = $Path . "/" . $this->ClsComFnc->FncGetPath('UpLoad') . $strFilepath;
            $strFilepath = $this->ClsComFnc->FncGetPath('UpLoad') . $strFilepath;
            //open the file.
            $sr = fopen($strFilepath, 'r+');
            do {
                //1ﾚｺｰﾄﾞ読込
                $strRecord = fgets($sr);
                $strRecord = str_replace("\r\n", "", $strRecord);
                //ﾚｺｰﾄﾞ件数をｶｳﾝﾄｱｯﾌﾟ
                $lngRcCnt += 1;
                //終了判定
                //20151012  Yuanjh UPD S.
                if (strlen($strRecord) == 0) {
                    break;
                } elseif (ord($strRecord) == 26) {
                    break;
                }
                /*
                if (ord($strRecord) == 26)
                {
                break;
                }*/
                //20151012  Yuanjh UPD E.
                //固定長273文字
                elseif (strlen($strRecord) != 273) {
                    $this->fncOutLog($lngRcCnt . "行目：フォーマットが違います！(半角273文字固定長)");
                    $blnErr = TRUE;
                    break;
                }
                $strRecord = str_replace("'", " ", $strRecord);
                //配列に取り込んだCSVファイルの値を格納
                //型式
                $strRecArr[0] = rtrim(substr($strRecord, 59, 6));
                //CAR_NO
                $strRecArr[1] = rtrim(substr($strRecord, 65, 8));
                //エアコン
                $strRecArr[2] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 97, 8)));
                //ステレオ
                $strRecArr[3] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 105, 8)));
                //外装
                $strRecArr[4] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 113, 8)));
                //内装
                $strRecArr[5] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 121, 8)));
                //足廻り
                $strRecArr[6] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 129, 8)));
                //看板
                $strRecArr[7] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 137, 8)));
                //塗装
                $strRecArr[8] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 145, 8)));
                //社外ナビ
                $strRecArr[9] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 153, 8)));
                //帆シート
                $strRecArr[10] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 161, 8)));
                //木工鉄鋼
                $strRecArr[11] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 169, 8)));
                //社外部品
                $strRecArr[12] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 177, 8)));
                //特装
                $strRecArr[13] = ltrim($this->ClsComFnc->FncNz(substr($strRecord, 185, 8)));
                //処理日
                $strRecArr[14] = rtrim($this->ClsComFnc->FncNz(substr($strRecord, 42, 4)));
                //項目数が正常な場合のみ、項目のﾁｪｯｸ処理を行う
                if ($blnErr == FALSE) {
                    $this->fncCheckRecordcsv($strRecArr, $lngRcCnt, $strKoumokuMei, $blnErr);
                    //SEQNO取得
                    $Do_Fill = $this->FrmShikakariTorikomi->fncSeqNOSet(str_replace("/", "", $cboYM), rtrim($this->ClsComFnc->FncNv($strRecArr[0])), rtrim($this->ClsComFnc->FncNv($strRecArr[1])), 0);
                    if (!$Do_Fill['result']) {
                        throw new \Exception($Do_Fill['data']);
                    }
                    //条件のデータが存在しない場合
                    if (count((array) $Do_Fill['data']) == 0) {
                        $intSeqNo = 1;
                    }
                    //MAX値＋1
                    else {
                        $intSeqNo = $Do_Fill['data'][0]['MAX(SEQ_NO)+1'];
                    }

                    //正常ﾃﾞｰﾀの場合はDB登録
                    if ($intSeqNo > 0 && $blnErr == FALSE) {
                        $intCnt = 0;
                        //更新項目を設定
                        for ($i = 0; $i <= 11; $i++) {
                            if ($this->ClsComFnc->FncNz($strRecArr[$i + 2]) != 0) {
                                $postData = array(
                                    'cboYM' => str_replace("/", "", $cboYM),
                                    'HASEI_MOTO_KB' => 0,
                                    'SYADAIKATA' => rtrim($this->ClsComFnc->FncNv($strRecArr[0])),
                                    'CAR_NO' => rtrim($this->ClsComFnc->FncNv($strRecArr[1])),
                                    'SYORIDT' => rtrim($this->ClsComFnc->FncNv($strRecArr[14])),
                                    'SEQ_NO' => $intSeqNo + $intCnt,
                                    'BUHIN_MEI' => $strKoumokuMei[$i],
                                    'KINGAKU' => $strRecArr[$i + 2]
                                );
                                //ｸｴﾘ実行
                                $Do_Execute = $this->FrmShikakariTorikomi->fncGetSqlInsert($postData);
                                if (!$Do_Execute['result']) {
                                    throw new \Exception($Do_Execute['data']);
                                }
                                //同一CARNOで実際に実行した件数をカウント
                                $intCnt += 1;
                            }
                        }
                    } else {
                        $blnErr = TRUE;
                    }
                }
            }
            while (feof($sr) == FALSE);
            //fileを閉じる
            fclose($sr);
            //ｴﾗｰが存在した場合は終了
            if ($blnErr == TRUE) {
                $result['result'] = FALSE;
            } else {
                //正常終了
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //============ ｴﾗｰ処理 ============
            $this->fncOutLog($e->getMessage());
            //fileを閉じる
            fclose($sr);
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;

    }

    //********************************************************************
    //処理概要：ﾚｺｰﾄﾞ情報のﾁｪｯｸ処理
    //引　　数：$strRecArr ﾁｪｯｸﾚｺｰﾄﾞ
    //　　　　：$lngRcCnt  行番号
    //　　　　：$strKoumokuMei 項目名
    //　　　　：$blnErr    ｴﾗｰ区分
    //戻 り 値：
    //********************************************************************
    public function fncCheckRecordcsv($strRecArr, $lngRcCnt, $strKoumokuMei, &$blnErr)
    {
        for ($i = 2; $i <= 13; $i++) {
            //数値チェック
            if ($this->ClsComFnc->FncNz(rtrim((string) $strRecArr[$i])) != "0") {
                if (is_numeric(rtrim((string) $strRecArr[$i])) != TRUE) {
                    $this->fncOutLog($lngRcCnt . "行目：" . $strKoumokuMei[$i - 2] . "が数値ではありません。");
                    $blnErr = TRUE;
                }
            }
        }
    }

    //********************************************************************
    //処理概要：指定ﾌｧｲﾙの情報をﾃｰﾌﾞﾙに取込む
    //引　　数：なし
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    public function fncFileRead($strFilepath, $cboYM)
    {
        $Do_Fill = array();
        $Do_Execute = array();
        $strGetArray = array();

        //ﾚｺｰﾄﾞｶｳﾝﾄ
        $lngRcCnt = 0;
        //ｴﾗｰﾌﾗｸﾞ
        $blnErr = FALSE;

        //配列数カウント
        $intArrayCnt = "";

        $intHitNum = 0;

        $intSeqNo = -1;

        $intSubstrNo = "";
        //戻り配列
        $result = array(
            'result' => TRUE,
            'data' => ""
        );
        try {

            //------------------------------------
            //   初期処理
            //------------------------------------
            $Path = dirname(dirname(dirname(dirname(__FILE__))));
//            $strFilepath = $Path . "/" . $this->ClsComFnc->FncGetPath('UpLoad') . $strFilepath;
            $strFilepath = $this->ClsComFnc->FncGetPath('UpLoad') . $strFilepath;
            //EXCELファイルを読み込む

            //20201020 YIN INS S
            if ($this->readFomart == "buhan") {
                //20201020 YIN INS E

                $strGetEXCEL = $this->ExcelRead($strFilepath, 2);
                //20201020 YIN INS S
            } else {
                $strGetEXCEL = $this->ExcelRead($strFilepath, 3);
            }
            //20201020 YIN INS E

            $strGetArray = $strGetEXCEL['data'];

            //配列数を取得
            $intArrayCnt = sizeof((array) $strGetArray);
            while ($lngRcCnt < $intArrayCnt) {
                //項目数が正常な場合のみ、項目のﾁｪｯｸ処理を行う
                if ($blnErr == FALSE) {
                    //20201020 YIN INS S
                    if ($this->readFomart == "buhan") {
                        //20201020 YIN INS E

                        if (rtrim($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYADAIKATA']])) == "") {
                            //20201020 YIN UPD S
                            // $intHitNum = strripos($strGetArray[$lngRcCnt][$this -> E_FILE_COL['E_CAR_NO']], "-");
                            $intHitNum = strpos(trim($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), " ");
                            if ($intHitNum == FALSE) {
                                $intHitNum = strripos($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']], "-");
                            }
                            //20201020 YIN UPD E
                            if ($intHitNum == FALSE) {
                                $intHitNum = 0;
                                $strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYADAIKATA']] = trim(substr(str_pad($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), 20), 0, 6));
                            } else {
                                //20201020 YIN UPD S
                                // $strGetArray[$lngRcCnt][$this -> E_FILE_COL['E_SYADAIKATA']] = rtrim(substr($this -> ClsComFnc -> FncNv($strGetArray[$lngRcCnt][$this -> E_FILE_COL['E_CAR_NO']]), 0, $intHitNum - 1));
                                $strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYADAIKATA']] = rtrim(substr($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), 0, $intHitNum));
                                //20201020 YIN UPD E
                            }
                        }

                        if ($intHitNum == 0) {
                            $strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']] = rtrim(substr(str_pad($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), 20), 6));
                        } else {
                            $strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']] = rtrim(substr($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), $intHitNum + 1));
                        }
                        //20201020 YIN INS S
                    }
                    //20201020 YIN INS E
                    $this->fncCheckRecord($strGetArray[$lngRcCnt], $lngRcCnt + 1, $intArrayCnt - 1, $blnErr);

                    // SEQNO取得
                    //20201020 YIN INS S
                    if ($this->readFomart == "buhan") {
                        //20201020 YIN INS E
                        $Do_Fill = $this->FrmShikakariTorikomi->fncSeqNOSet(str_replace("/", "", $cboYM), rtrim($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYADAIKATA']])), str_pad($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), 8, " ", STR_PAD_LEFT), 1);
                        //20201020 YIN INS S
                    } else {
                        $Do_Fill = $this->FrmShikakariTorikomi->fncSeqNOSet(str_replace("/", "", $cboYM), rtrim($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYADAIKATA']])), str_pad($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), 8, " ", STR_PAD_LEFT), 0);
                    }
                    //20201020 YIN INS E

                    if (!$Do_Fill['result']) {
                        throw new \Exception($Do_Fill['data']);
                    }
                    //条件のデータが存在しない場合
                    if (count((array) $Do_Fill['data']) == 0) {
                        $intSeqNo = 1;
                    } else {
                        //MAX値＋1
                        $intSeqNo = $Do_Fill['data'][0]['MAX(SEQ_NO)+1'];
                    }
                    if ($intSeqNo > 0 && $blnErr == FALSE) {
                        //更新項目を設定
                        $postData = array(
                            'cboYM' => str_replace("/", "", $cboYM),
                            'HASEI_MOTO_KB' => 1,
                            'SYADAIKATA' => rtrim($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYADAIKATA']])),
                            'CAR_NO' => str_pad($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_CAR_NO']]), 8, " ", STR_PAD_LEFT),
                            'SYORIDT' => "",
                            'SEQ_NO' => $intSeqNo,
                            'BUHIN_MEI' => rtrim($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_BUHIN_MEI']])),
                            'KINGAKU' => rtrim($this->ClsComFnc->FncNz($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_KINGAKU']]))
                        );
                        //20201020 YIN INS S
                        if ($this->readFomart == "map") {
                            $postData['HASEI_MOTO_KB'] = 0;
                            $strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYORI_DT']] = str_replace("/", "", $this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYORI_DT']]));
                        }
                        //20201020 YIN INS E
                        $intSubstrNo = strlen($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYORI_DT']])) - 4;
                        if ($intSubstrNo < 0) {
                            $postData['SYORIDT'] = rtrim($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYORI_DT']]));
                        } else {
                            $postData['SYORIDT'] = substr($this->ClsComFnc->FncNv($strGetArray[$lngRcCnt][$this->E_FILE_COL['E_SYORI_DT']]), $intSubstrNo);
                        }

                        // 'ｸｴﾘ実行
                        $Do_Execute = $this->FrmShikakariTorikomi->fncGetSqlInsert($postData);
                        if (!$Do_Execute['result']) {
                            throw new \Exception($Do_Execute['data']);
                        }
                    } else {
                        $blnErr = TRUE;
                    }
                    $intHitNum = 0;
                }
                //ﾚｺｰﾄﾞ件数をｶｳﾝﾄｱｯﾌﾟ
                $lngRcCnt += 1;
            }
            //ｴﾗｰが存在した場合は終了
            if ($blnErr == TRUE) {
                $result['result'] = FALSE;
            }
            //正常終了
            else {
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            //============ ｴﾗｰ処理 ============
            $this->fncOutLog($e->getMessage());
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;

    }

    //********************************************************************
    //処理概要：Excelデータ読み込み
    //引　　数：$path                パス
    //　　　　：$readstartRowNumber  開始行番号
    //戻 り 値：string()()          配列
    //********************************************************************
    public function ExcelRead($path, $readstartRowNumber)
    {
        try {
            $result = array(
                'result' => 'false',
                'data' => 'ErrorInfo'
            );
            $arr = explode(".", $path);
            if (($arr[count($arr) - 1]) == 'xlsx' || ($arr[count($arr) - 1]) == 'XLSX') {
                $objReader = IOFactory::createReader('Xlsx');
            } else {
                $objReader = IOFactory::createReader('Xls');
            }
            $spreadsheet = $objReader->load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            $rowarr = array();
            $arr = array(
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q'
            );

            for ($row = $readstartRowNumber; $row <= $highestRow; $row++) {
                $col = array();
                $rowNothing = TRUE;
                //20201020 YIN UPD S
                // foreach ($arr as $key => $value)
                // {
                // if ($value == 'F' || $value == 'H' || $value == 'L' || $value == 'N' || $value == 'Q')
                // {
                // $val = $worksheet -> getCell($value . $row) -> getCalculatedValue();
                // if ($val != "")
                // {
                // $rowNothing = FALSE;
                // }
                // array_push($col, $this -> ClsComFnc -> FncNv($val));
                // }
                // }
                if ($this->readFomart == "buhan") {
                    foreach ($arr as $value) {
                        if ($value == 'F' || $value == 'H' || $value == 'L' || $value == 'N' || $value == 'O') {
                            $val = $worksheet->getCell($value . $row)->getCalculatedValue();
                            if ($val != "") {
                                $rowNothing = FALSE;
                            }
                            array_push($col, $this->ClsComFnc->FncNv($val));
                        }
                    }
                } else {
                    foreach ($arr as $value) {
                        if ($value == 'I' || $value == 'K' || $value == 'N' || $value == 'P' || $value == 'Q') {
                            if ($value == 'I') {
                                $val = $worksheet->getCell($value . $row)->getFormattedValue();
                            } else {
                                $val = $worksheet->getCell($value . $row)->getCalculatedValue();
                            }
                            if ($val != "") {
                                $rowNothing = FALSE;
                            }
                            array_push($col, $this->ClsComFnc->FncNv($val));
                        }
                    }
                }
                //20201020 YIN UPD E
                if ($rowNothing) {
                    break;
                }
                array_push($rowarr, $col);
            }

            $result = array(
                'result' => TRUE,
                'data' => $rowarr
            );

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //********************************************************************
    //処理概要：ﾚｺｰﾄﾞ情報のﾁｪｯｸ処理
    //引　　数：$strRecArr ﾁｪｯｸﾚｺｰﾄﾞ
    //　　　　：$lngRcCnt  行番号
    //　　　　：$intArrayCnt 配列数
    //　　　　：$blnErr    ｴﾗｰ区分
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    public function fncCheckRecord($strRecArr, $lngRcCnt, $intArrayCnt, &$blnErr)
    {
        $ErrRcCnt = $lngRcCnt;

        //部品名
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[$this->E_FILE_COL['E_BUHIN_MEI']])) != "") {
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[$this->E_FILE_COL['E_BUHIN_MEI']]))) > 40) {
                $this->fncOutLog($ErrRcCnt . "行目：部品名の桁数が不正です。（全角20文字以下）" . $strRecArr[$this->E_FILE_COL['E_BUHIN_MEI']]);
                $blnErr = TRUE;
            }
        }

        //金額
        if ($this->ClsComFnc->FncNz(rtrim($strRecArr[$this->E_FILE_COL['E_KINGAKU']])) != "0") {
            if (is_numeric(rtrim($strRecArr[$this->E_FILE_COL['E_KINGAKU']])) != TRUE) {
                $this->fncOutLog($ErrRcCnt . "行目：金額が数値ではありません。");
                $blnErr = TRUE;
            }
        }

        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(str_replace(",", "", rtrim($strRecArr[$this->E_FILE_COL['E_KINGAKU']])))) > 9) {
            $this->fncOutLog($ErrRcCnt . "行目：金額の桁数が不正です。（999999999以下）" . $strRecArr[$this->E_FILE_COL['E_KINGAKU']]);
            $blnErr = TRUE;
        }

        //車台型式
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[$this->E_FILE_COL['E_SYADAIKATA']])) == "") {
            $this->fncOutLog($ErrRcCnt . "行目：車台型式が未入力です。");
            $blnErr = TRUE;
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[$this->E_FILE_COL['E_SYADAIKATA']]))) > 8) {
            $this->fncOutLog($ErrRcCnt . "行目：車台型式の桁数が不正です。（半角8文字以下）" . $strRecArr[$this->E_FILE_COL['E_SYADAIKATA']]);
            $blnErr = TRUE;
        }
        //CAR_NO
        if ($this->ClsComFnc->FncNz($strRecArr[$this->E_FILE_COL['E_CAR_NO']]) == "") {
            $this->fncOutLog($ErrRcCnt . "行目：CAR_NOが未入力です。");
            $blnErr = TRUE;
        }

        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv($strRecArr[$this->E_FILE_COL['E_CAR_NO']])) > 10) {
            $this->fncOutLog($ErrRcCnt . "行目：CAR_NOの桁数が不正です。（半角10文字以下）" . $strRecArr[$this->E_FILE_COL['E_CAR_NO']]);
            $blnErr = TRUE;
        }
        //正常終了
        return TRUE;
    }

}