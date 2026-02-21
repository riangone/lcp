<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSinsyaZaikoTorikomi;

class FrmSinsyaZaikoTorikomiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    private $FrmSinsyaZaikoTorikomi;
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
        $this->render('index', 'FrmSinsyaZaikoTorikomi_layout');
    }

    public function frmSampleLoad()
    {
        $result = array();
        try {
            $this->FrmSinsyaZaikoTorikomi = new FrmSinsyaZaikoTorikomi();
            $result = $this->FrmSinsyaZaikoTorikomi->reselect();
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
//            $pathUpLoad = $strPath . "/" . $this->ClsComFnc->FncGetPath('UpLoad');
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
        $this->FrmSinsyaZaikoTorikomi = new FrmSinsyaZaikoTorikomi();
        $strUserID = $this->FrmSinsyaZaikoTorikomi->GS_LOGINUSER['strUserID'];
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
        $result = array();
        $Do_conn = array();
        $Do_Execute = array();
        //CSVファイル取込の 戻り配列
        $CsvResult = array();
        //取込先
        $txtFile = "";

        try {
            $txtFile = $_POST['data'];
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
            $this->FrmSinsyaZaikoTorikomi = new FrmSinsyaZaikoTorikomi();
            //DB接続
            $Do_conn = $this->FrmSinsyaZaikoTorikomi->Do_conn();
            if (!$Do_conn['result']) {
                throw new \Exception($Do_conn['data']);
            }
            $blnConn = TRUE;
            //トランザクション開始
            $this->FrmSinsyaZaikoTorikomi->Do_transaction();
            $blnTrn = TRUE;
            //取込ﾃｰﾌﾞﾙを初期化
            $Do_Execute = $this->FrmSinsyaZaikoTorikomi->fncDelete();
            if (!$Do_Execute['result']) {
                $this->fncOutLog($Do_Execute['data']);
                throw new \Exception($Do_Execute['data']);
            }

            //CSVファイル取込
            $CsvResult = $this->fncCsvRead($txtFile);
            if ($CsvResult['result'] == FALSE) {
                throw new \Exception($CsvResult['data']);
            }
            //ｺﾐｯﾄ
            $this->FrmSinsyaZaikoTorikomi->Do_commit();
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
            $this->FrmSinsyaZaikoTorikomi->Do_rollback();
        }
        //DB接続解除
        if ($blnConn == TRUE) {
            $this->FrmSinsyaZaikoTorikomi->Do_close();
        }
        $this->fncReturn($result);
    }

    //********************************************************************
    //処理概要：指定ﾌｧｲﾙの情報をﾃｰﾌﾞﾙに取込む（CSV）
    //引　　数：$strFilepath
    //戻 り 値：配列
    //********************************************************************
    public function fncCsvRead($strFilepath)
    {
        //ｽﾄﾘｰﾑﾘｰﾀﾞ
        $sr = "";
        //読込みﾚｺｰﾄﾞ
        $strRecord = "";
        //読込みﾚｺｰﾄﾞ（配列）
        $strRecArr = array();
        //ﾚｺｰﾄﾞｶｳﾝﾄ
        $lngRcCnt = 0;
        //ｴﾗｰﾌﾗｸﾞ
        $blnErr = FALSE;
        //Insertの戻り配列
        $Do_Execute = array();
        //戻り配列
        $result = array(
            'result' => TRUE,
            'data' => ""
        );
        try {
            $Path = dirname(dirname(dirname(dirname(__FILE__))));
//            $strFilepath = $Path . "/" . $this->ClsComFnc->FncGetPath('UpLoad') . $strFilepath;
            $strFilepath = $this->ClsComFnc->FncGetPath('UpLoad') . $strFilepath;
            //open the file.
            $sr = fopen($strFilepath, 'r+');
            // ------------------------------------
            //  取込ﾁｪｯｸ処理
            //------------------------------------
            do {
                //1ﾚｺｰﾄﾞ読込
                $strRecord = fgets($sr);
                $strRecord = str_replace("\r\n", "", $strRecord);
                //ﾚｺｰﾄﾞ件数をｶｳﾝﾄｱｯﾌﾟ
                $lngRcCnt += 1;
                //終了判定
                if (ord($strRecord) == 26) {
                    break;
                }
                $strRecArr = explode("	", $strRecord);
                if (isset($strRecArr[3]) ? $strRecArr[3] == 'B2' : false) {
                    //項目数が正常な場合のみ、項目のﾁｪｯｸ処理を行う
                    if ($blnErr == FALSE) {
                        //新車  "SJIS"->"UTF-8".
                        $strRecArr[6] = mb_convert_encoding($strRecArr[6], "UTF-8", "SJIS");
                        $this->fncCheckRecord($strRecArr, $lngRcCnt, $blnErr);
                        // 正常ﾃﾞｰﾀの場合はDB登録
                        if ($blnErr == FALSE) {
                            //更新項目を設定
                            $post = array(
                                "KAMOK_CD" => $this->ClsComFnc->FncNv(rtrim($strRecArr[4])),
                                "KOMOK_CD" => $this->ClsComFnc->FncNv(rtrim($strRecArr[5])),
                                "KOMOK_NM" => $this->ClsComFnc->FncNv(rtrim($strRecArr[6])),
                                "NENGAPPI" => $this->ClsComFnc->FncNv(str_replace("/", "", rtrim($strRecArr[7]))),
                                "HISSU_TEKYO2" => $this->ClsComFnc->FncNv(rtrim($strRecArr[8])),
                                "HISSU_TEKYO3" => $this->ClsComFnc->FncNv(rtrim($strRecArr[10]))
                            );
                            $Do_Execute = $this->FrmSinsyaZaikoTorikomi->fncInsert($post);
                            if (!$Do_Execute['result']) {
                                throw new \Exception($Do_Execute['data']);
                            }
                        }
                    }
                }
            }
            while (feof($sr) == FALSE);
            //fileを閉じる
            fclose($sr);
            if ($blnErr == TRUE) {
                //ｴﾗｰが存在した場合は終了
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
    //　　　　：$blnErr    ｴﾗｰ区分
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    public function fncCheckRecord($strRecArr, $lngRcCnt, &$blnErr)
    {
        $ErrRcCnt = "";
        $ErrRcCnt = $lngRcCnt;
        //科目ｺｰﾄﾞ
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[4])) != "") {
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[4]))) > 5) {
                $this->fncOutLog($ErrRcCnt . "行目：勘定科目ｺｰﾄﾞの桁数が不正です。（半角5文字以下）" . $strRecArr[4]);
                $blnErr = TRUE;
            }
        }
        //項目ｺｰﾄﾞ
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[5])) != "") {
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[5]))) > 2) {
                $this->fncOutLog($ErrRcCnt . "行目：勘定項目ｺｰﾄﾞの桁数が不正です。（半角2文字以下）" . $strRecArr[5]);
                $blnErr = TRUE;
            }
        }
        //項目名
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[6])) != "") {
            if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[6]))) > 20) {
                $this->fncOutLog($ErrRcCnt . "行目：勘定科目項目名称の桁数が不正です。（半角20文字以下）" . $strRecArr[6]);
                $blnErr = TRUE;
            }
        }
        //年月日
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[7])) != "") {
            if ($this->ClsComFnc->IsDate($this->ClsComFnc->FncNv(rtrim($strRecArr[7]))) == FALSE) {
                $this->fncOutLog($ErrRcCnt . "行目：年月日のフォーマットが不正です。(YYYY/MM/DD) " . $strRecArr[7]);
                $blnErr = TRUE;
            }
        }

        //車台型式
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[8])) == "") {
            $this->fncOutLog($ErrRcCnt . "行目：口座1/必須摘要1が未入力です。");
            $blnErr = TRUE;
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[8]))) > 3) {
            $this->fncOutLog($ErrRcCnt . "行目：口座1/必須摘要1の桁数が不正です。（半角3文字以下）" . $strRecArr[8]);
            $blnErr = TRUE;
        }
        //CAR_NO
        if ($this->ClsComFnc->FncNv(rtrim($strRecArr[10])) == "") {
            $this->fncOutLog($ErrRcCnt . "行目：CAR_NOが未入力です。");
            $blnErr = TRUE;
        }
        if ($this->ClsComFnc->GetByteCount($this->ClsComFnc->FncNv(rtrim($strRecArr[10]))) > 10) {
            $this->fncOutLog($ErrRcCnt . "行目：CAR_NOの桁数が不正です。（半角10文字以下）" . $strRecArr[10]);
            $blnErr = TRUE;
        }
        //正常終了
        return TRUE;
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
        $strLogPath = $this->strErrLogPath . "新車在庫データ取込.log";
        if ($blnAppend == TRUE) {
            $sw = fopen($strLogPath, "a+");
            fwrite($sw, "\r\n" . $strOutMsg);
        } else {
            $sw = fopen($strLogPath, "w+");
            fwrite($sw, $strOutMsg);
        }
        fclose($sw);
    }

}
