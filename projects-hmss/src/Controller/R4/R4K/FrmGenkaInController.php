<?php
/**
 * 説明：
 *
 *
 * @author zhenghuiyun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL 
 * 20150914           ---                       BUG#2112                       Yuanjh
 * 20150918           ---                       BUG#2112                       Yuanjh 
 * 20151013           ---                       BUG#2193                       Yuanjh
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmGenkaIn;

class FrmGenkaInController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmGenkaIn = "";
    public $strCsvOutPath = "";
    public $strErrLogPath = "";
    public $result = "";

    //取込ﾌｧｲﾙの項目番号
    public $E_FILE_COL = array(
        "E_ID" => 0,
        "E_TOA_NAME" => 1, //問合呼称
        "E_HTA_PRC" => 2, //本体原価
        "E_TNP_PRC" => 3, //店頭価格
        "E_FZK_PRC" => 4, //添付価格
        "E_SOU_HABA" => 5, //利巾
        "E_SYA_PCS" => 6, //社内原価
        "E_SIK_PCS" => 7, //仕切
        "E_FZK_PCS" => 8, //添付社内
        "E_FZK_RIE" => 9, //添付利益
        "E_KTN_PCS" => 10, //拠点原価
        "E_KTN_HABA" => 11, //拠点巾
        "E_TYK_PCS" => 12, //特約店原価
        "E_TYK_HABA" => 13, //特約店巾
        "E_F_PCS" => 14, //Ｆ号原価
        "E_F_HABA" => 15, //Ｆ号巾
        "E_UC_OYA" => 16, //UC親
    );
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmGenkaIn_layout');
    }

    public function frmSampleLoad()
    {
        $result = array();
        $strGenkaPath = '';
        $intHitNum = '';
        $strPath = '';

        $Path = dirname(dirname(dirname(dirname(__FILE__))));
        //CSV取込パスを取得する
        $strGenkaPath = $this->ClsComFnc->FncGetPath("GenkaMstPath");
        if ($strGenkaPath == "") {
            $strGenkaPath = "mnt/temp/GenkaMst/GenkaMst.csv";
        }
        $strGenkaPath = $Path . "/" . $strGenkaPath;
        //CSV出力先のファイル設定
        $intHitNum = strripos($strGenkaPath, "/");
        $this->strCsvOutPath = substr($strGenkaPath, 0, $intHitNum);
        $this->strCsvOutPath = $this->strCsvOutPath . "/N5200GenkaMst.csv";

        $intHitNum = 0;
        //ErrLog出力ﾊﾟｽを取得する
        $strPath = $this->ClsComFnc->FncGetPath("PprErrLog");
        //CSV出力先のファイル設定
        $intHitNum = strripos($strPath, "/");
        if ($intHitNum == FALSE) {
            $this->strErrLogPath = "mnt/temp/";
        } else {
            $this->strErrLogPath = substr($strPath, 0, $intHitNum);
        }
        $this->strErrLogPath = $Path . "/" . $this->strErrLogPath;
        $result['result'] = TRUE;
        $result['data']['strGenkaPath'] = $strGenkaPath;
        $result['data']['strCsvOutPath'] = $this->strCsvOutPath;
        $result['data']['strErrLogPath'] = $this->strErrLogPath;
        $this->fncReturn($result);
    }

    public function fncCheckFile()
    {
        $result = array();
        $strGenkaPath = "";
        $strGenkaPath = $_POST['data'];
        //ﾌｧｲﾙが存在していない場合はｴﾗｰ
        if (file_exists($strGenkaPath)) {
            $result['result'] = TRUE;
            $result['data'] = "";
        } else {
            $result['result'] = FALSE;
            $result['data'] = "指定されたﾌｧｲﾙは存在しません。";
        }
        $this->fncReturn($result);
    }

    public function cmdActClick()
    {
        $result = array();
        $strGenkaPath = '';
        $radiosel = '';
        $rtn = array();
        try {
            $strGenkaPath = $_POST['data']['txtFile'];
            $this->strCsvOutPath = $_POST['data']['strCsvOutPath'];
            $this->strErrLogPath = $_POST['data']['strErrLogPath'];
            $radiosel = $_POST['data']['radiosel'];

            //ﾛｸﾞﾌｧｲﾙを初期化
            $this->fncOutLog("取込開始:" . date('Y/m/d H:i:s', time()), FALSE);
            $this->FrmGenkaIn = new FrmGenkaIn();
            //
            $DB_conn = $this->FrmGenkaIn->Do_conn();
            if (!$DB_conn['result']) {
                throw new \Exception($DB_conn['data']);
            }
            //トランザクション開始
            $this->FrmGenkaIn->Do_transaction();
            //取込ﾃｰﾌﾞﾙを初期化
            if ($radiosel == 1) {
                $DelResult = $this->fncTableDelete();
                if ($DelResult['result'] == FALSE) {

                    throw new \Exception($DelResult['data']);
                }
            }
            //指定ﾌｧｲﾙの情報をﾃｰﾌﾞﾙへ取り込む
            $rtn = $this->fncFileRead($strGenkaPath);
            if ($rtn['result'] == FALSE) {
                //--20151013  Yuanjh ADD S.
                //ﾛｸﾞﾌｧｲﾙ出力
                $this->fncOutLog($rtn['data'], TRUE);
                //--20151013  Yuanjh ADD E.
                throw new \Exception($rtn['data']);
            }
            //ｺﾐｯﾄ
            $this->FrmGenkaIn->Do_commit();
            //ﾛｸﾞﾌｧｲﾙ出力
            $this->fncOutLog("正常終了:" . date('Y/m/d H:i:s', time()));
            $result['result'] = TRUE;
            $result['data'] = $rtn['data'];
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            //20150918 Yuanjh  ADD S.
            $result['cnt'] = $rtn['cnt'];
            //20150918 Yuanjh ADD E.
            $this->FrmGenkaIn->Do_rollback();
        }
        $this->FrmGenkaIn->Do_close();
        $this->fncReturn($result);
    }

    public function fncTableDelete()
    {
        $result = array();
        try {
            $result = $this->FrmGenkaIn->fncTableDelete();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
            $result['data'] = "";
        } catch (\Exception $e) {
            $this->fncOutLog($e->getMessage());
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    public function fncFileRead($GenkaPath)
    {
        $strFilepath = "";
        //取込ﾌｧｲﾙﾊﾟｽ
        $sr = "";
        //ｽﾄﾘｰﾑﾘｰﾀﾞ
        $strRecord = "";
        //読込みﾚｺｰﾄﾞ
        $strRecArr = "";
        //読込みﾚｺｰﾄﾞ（配列）
        $lngRcCnt = 0;
        //ﾚｺｰﾄﾞｶｳﾝﾄ
        $blnErr = FALSE;
        //ｴﾗｰﾌﾗｸﾞ
        $blnCsvErr = FALSE;
        //DELETE文
        //$strSQL = "";
        //実行ｸｴﾘ
        //$strSQL_D = "";
        //実行ｸｴﾘ(DELETE)
        $objSw = "";
        //ストリームライター
        $strOut = "";
        //ストリングビルダー
        $result = array();
        try {
            //ﾌｧｲﾙの存在ﾁｪｯｸ
            $strFilepath = $GenkaPath;
            if (file_exists($strFilepath) == FALSE) {
                $this->fncOutLog("対象ﾌｧｲﾙが存在していません。");
                $result['result'] = FALSE;
                $result['data'] = "対象ﾌｧｲﾙが存在していません。";
                throw new \Exception($result['data']);
            }
            $blnCsvErr = TRUE;
            $sr = fopen($strFilepath, 'r+');
            //20240516 lujunxia PHP8 ins s
            if (!$sr) {
                $rtn['cnt'] = "0";
                throw new \Exception('CSV取込み中にエラーが発生しました。');
            }
            //20240516 lujunxia PHP8 ins e
            $objSw = fopen($this->strCsvOutPath, 'w+');
            do {
                //1ﾚｺｰﾄﾞ読込
                //$strRecArr = fgetcsv($sr);
                //$strRecArr = $this -> __fgetcsv($sr);
                //*********************
                $strRecord = fgets($sr);
                $strRecord = str_replace("\r\n", "", $strRecord);
                //""を取り除く
                $strRecord = str_replace('"', "", $strRecord);
                $strRecArr = explode(",", $strRecord);
                //***********************
                $aa = count($strRecArr);

                //20150914 Yuanjh  Add  S,
                if (strlen($strRecord) == 0) {
                    break;
                }
                //20150914 Yuanjh  Add  E,
                if ($aa == 1 && ($strRecArr[0] == "" || $strRecArr[0] == null)) {
                    if ($blnErr == TRUE) {
                        break;
                    }
                    throw new \Exception("Length of argument 'String' must be greater than zero.");
                }
                if ($aa < 16) {
                    throw new \Exception("Index was outside the bounds of the array.");
                }
                if (ord($strRecArr[0]) == 26) {
                    break;
                }
                //ﾚｺｰﾄﾞ件数をｶｳﾝﾄｱｯﾌﾟ
                $lngRcCnt += 1;
                //""を取り除く
                // foreach ($strRecArr as $value)
                // {
                // $value = str_replace('"', "", $value);
                // }
                //項目数が正常な場合のみ、項目のﾁｪｯｸ処理を行う
                if ($blnErr != TRUE) {
                    $this->fncCheckRecord($strRecArr, $lngRcCnt, $blnErr);
                    //正常ﾃﾞｰﾀの場合はDB登録
                    if ($blnErr != TRUE) {
                        $TOA_NAME = rtrim($strRecArr[$this->E_FILE_COL["E_TOA_NAME"]]);
                        $HTA_PRC = $strRecArr[$this->E_FILE_COL["E_HTA_PRC"]];
                        $result = $this->FrmGenkaIn->fncDelete($TOA_NAME, $HTA_PRC);
                        //print_r($result);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                        //更新項目を設定
                        $postArr = array(
                            "ID" => rtrim($strRecArr[$this->E_FILE_COL["E_ID"]]),
                            "TOA_NAME" => rtrim($strRecArr[$this->E_FILE_COL["E_TOA_NAME"]]),
                            "HTA_PRC" => $strRecArr[$this->E_FILE_COL["E_HTA_PRC"]],
                            "TNP_PRC" => $strRecArr[$this->E_FILE_COL["E_TNP_PRC"]],
                            "FZK_PRC" => $strRecArr[$this->E_FILE_COL["E_FZK_PRC"]],
                            "SOU_HABA" => $strRecArr[$this->E_FILE_COL["E_SOU_HABA"]],
                            "SYA_PCS" => $strRecArr[$this->E_FILE_COL["E_SYA_PCS"]],
                            "SIK_PCS" => $strRecArr[$this->E_FILE_COL["E_SIK_PCS"]],
                            "FZK_PCS" => $strRecArr[$this->E_FILE_COL["E_FZK_PCS"]],
                            "FZK_RIE" => $strRecArr[$this->E_FILE_COL["E_FZK_RIE"]],
                            "KTN_PCS" => $strRecArr[$this->E_FILE_COL["E_KTN_PCS"]],
                            "KTN_HABA" => $strRecArr[$this->E_FILE_COL["E_KTN_HABA"]],
                            "TYK_PCS" => $strRecArr[$this->E_FILE_COL["E_TYK_PCS"]],
                            "TYK_HABA" => $strRecArr[$this->E_FILE_COL["E_TYK_HABA"]],
                            "F_PCS" => $strRecArr[$this->E_FILE_COL["E_F_PCS"]],
                            "F_HABA" => $strRecArr[$this->E_FILE_COL["E_F_HABA"]]
                            //"UC_OYA" => "",
                        );
                        //ｸｴﾘ実行
                        $result = $this->FrmGenkaIn->fncInsert($postArr);
                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                        //CSV出力
                        $this->fncOutput($strOut, $strRecArr);
                        //ファイル出力
                        fwrite($objSw, $strOut);
                    }
                }
            }
            while (feof($sr) == FALSE);
            $blnCsvErr = FALSE;
            //ｽﾄﾘｰﾑﾘｰﾀﾞを閉じる
            fclose($sr);



            //ｴﾗｰが存在した場合は終了
            //--20151013  Yuanjh UPD S.
            //if ($blnErr)
            if ($blnErr || number_format($lngRcCnt) == 0)
            //--20151013  Yuanjh UPD E.
            {
                //20150918 Yuanjh  ADD S.
                $rtn['cnt'] = number_format($lngRcCnt);
                //20150918 Yuanjh  ADD E.
                $rtn['result'] = FALSE;
                $rtn['data'] = 'CSV取込み中にエラーが発生しました。';
            } else {
                $rtn['result'] = TRUE;
                $rtn['data'] = number_format($lngRcCnt);
            }
        } catch (\Exception $e) {
            //エラー時出力ファイル削除
            if ($this->ClsComFnc->FncFileExists($this->strCsvOutPath)) {
                if ($objSw != "") {
                    fclose($objSw);
                }
                unlink($this->strCsvOutPath);
                $blnErr = FALSE;
            }
            $this->fncOutLog($e->getMessage());
            $rtn['result'] = FALSE;
            $rtn['data'] = $e->getMessage();
        }

        if ($blnCsvErr == TRUE) {
            //エラー時出力ファイル削除
            if ($this->ClsComFnc->FncFileExists($this->strCsvOutPath)) {
                if ($objSw != "") {
                    fclose($objSw);
                }
                unlink($this->strCsvOutPath);
            }
            //ストリーム閉じる
            //20240516 lujunxia PHP8 del s
            // if ($objSw != "") {
            //     fclose($objSw);
            // }
            //20240516 lujunxia PHP8 del e
            if ($sr != "") {
                fclose($sr);
            }
        }
        return $rtn;

    }

    public function fncOutput(&$strOut, $strGenkaArray)
    {
        //明細部出力
        $strOut = "";
        //ID
        $strOut .= "\"";
        $strOut .= substr(str_pad($this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_ID"]]), 3), 0, 1);
        $strOut .= substr(str_pad($this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_ID"]]), 3), 2, 1);
        $strOut .= "\",";
        //問合せ呼称
        $strOut .= "\"";
        $strOut .= rtrim($this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_TOA_NAME"]]));
        $strOut .= "\",";
        //本体価格
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_HTA_PRC"]]);
        $strOut .= ",";
        //店頭価格
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_TNP_PRC"]]);
        $strOut .= ",";
        //添付価格
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_FZK_PRC"]]);
        $strOut .= ",";
        //利巾
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_SOU_HABA"]]);
        $strOut .= ",";
        //社内原価
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_SYA_PCS"]]);
        $strOut .= ",";
        //仕切
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_SIK_PCS"]]);
        $strOut .= ",";
        //添付社内
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_FZK_PCS"]]);
        $strOut .= ",";
        //添付利益
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_FZK_RIE"]]);
        $strOut .= ",";
        //拠点原価
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_KTN_PCS"]]);
        $strOut .= ",";
        //拠点巾
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_KTN_HABA"]]);
        $strOut .= ",";
        //特約店原価
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_TYK_PCS"]]);
        $strOut .= ",";
        //特約店巾
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_TYK_HABA"]]);
        $strOut .= ",";
        //F号原価
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_F_PCS"]]);
        $strOut .= ",";
        //F号巾
        $strOut .= $this->ClsComFnc->FncNv($strGenkaArray[$this->E_FILE_COL["E_F_HABA"]]);
        //回车    fan add
        //when not use fgetcsv,to make the N5200GenkaMst.csv change line.
        $strOut .= "\r\n";
    }

    public function fncCheckRecord($strRecArr, $lngRcCnt, &$blnErr)
    {
        $dataArr = array(
            "name" => array(
                'ID',
                '問合呼称',
                '本体原価',
                '店頭価格',
                '添付価格',
                '利巾',
                '社内原価',
                '仕切',
                '添付社内',
                '添付利益',
                '拠点原価',
                '拠点巾',
                '特約店原価',
                '特約店巾',
                'Ｆ号原価',
                'Ｆ号巾'
            ),
            "byteNum" => array(
                3,
                6,
                9,
                9,
                7,
                7,
                9,
                9,
                7,
                7,
                9,
                7,
                9,
                7,
                9,
                7
            )
        );
        for ($i = 0; $i < 16; $i++) {
            $byte = "";
            if ($i == $this->E_FILE_COL["E_ID"]) {
                $byte = $dataArr["byteNum"][$i] . "ﾊﾞｲﾄ";
            } else {
                if ($i == $this->E_FILE_COL["E_TOA_NAME"]) {
                    if (rtrim($strRecArr[$i]) == "" || rtrim($strRecArr[$i]) == null) {
                        $this->fncOutLog($lngRcCnt . "行目：" . $dataArr["name"][$i] . "が未入力です");
                        $blnErr = TRUE;
                    }
                    $byte = $dataArr["byteNum"][$i] . "ﾊﾞｲﾄ";
                } else {
                    if (rtrim($strRecArr[$i]) != "" || rtrim($strRecArr[$i]) != null) {
                        if (is_numeric(rtrim($strRecArr[$i])) != TRUE) {
                            $this->fncOutLog($lngRcCnt . "行目：" . $dataArr["name"][$i] . "が数値ではありません。");
                            $blnErr = TRUE;
                        }
                    }
                    $byte = str_pad($byte, $dataArr["byteNum"][$i], "9", STR_PAD_LEFT);

                }

            }
            $length = strlen(rtrim($strRecArr[$i]));
            if ($length > $dataArr["byteNum"][$i]) {
                $strRecArr[$i] = mb_convert_encoding($strRecArr[$i], "UTF-8", "SJIS");
                $this->fncOutLog($lngRcCnt . "行目：" . $dataArr["name"][$i] . "の桁数が不正です。（" . $byte . "以下）" . $strRecArr[$i]);
                $blnErr = TRUE;
            }
        }

        return TRUE;
    }

    public function fncOutLog($strOutMsg, $blnAppend = TRUE)
    {
        //ﾛｸﾞ出力先ﾌｫﾙﾀﾞの設定
        if (file_exists($this->strErrLogPath) == FALSE) {
            mkdir($this->strErrLogPath);
        }
        //$strLogPath = $this -> strErrLogPath . "/原価マスタ取込.log";
        $strLogPath = $this->strErrLogPath . "/GenkaIn.log";

        if ($blnAppend == TRUE) {
            $sw = fopen($strLogPath, "a+");
            fwrite($sw, "\r\n" . $strOutMsg);
        } else {

            $sw = fopen($strLogPath, "w+");
            fwrite($sw, $strOutMsg);
        }
        fclose($sw);
        return TRUE;
    }

    //********************fan add start**************************************
    //fgetcsv can get the field	of Chinese or Japanese.So defined this method.
    //***********************************************************************
    // function __fgetcsv(&$handle, $length = null, $d = ',', $e = '"')
    // {
    // $d = preg_quote($d);
    // $e = preg_quote($e);
    // $_line = "";
    // $eof = false;
    // while ($eof != true)
    // {
    // $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
    // $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
    // if ($itemcnt % 2 == 0)
    // $eof = true;
    // }
    // $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
    // $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
    // preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
    // $_csv_data = $_csv_matches[1];
    // for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++)
    // {
    // $_csv_data[$_csv_i] = preg_replace('/^' . $e . '(.*)' . $e . '$/s', '$1', $_csv_data[$_csv_i]);
    // $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
    // }
    // return empty($_line) ? false : $_csv_data;
    // }

    //********************fan add end**************

}