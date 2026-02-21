<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmR2KAIKEI;

class FrmR2KAIKEIController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmChumonCSV = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsComControl');
    }
    var $blnDBconectFlg = FALSE;
    var $blnLockFlg = FALSE;
    var $strOutFileNM = FALSE;
    public $FrmR2KAIKEI;

    public function index()
    {
        $this->render('index', 'FrmR2KAIKEI_layout');
    }

    public function fncRirekiDateSelect()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $this->FrmR2KAIKEI = new FrmR2KAIKEI();
                $result = $this->FrmR2KAIKEI->fncRirekiDateSelect();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);

                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                }
            } else {
                $result['result'] = TRUE;
                $result['data'] = "";

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    public function fncGetPath()
    {
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        try {
            $strPath = "";
            $strCsvPath = "";
            $strLogPath = "";
            $strBackUpPath = "";

            $strPath = dirname(dirname(dirname(dirname(__FILE__))));

            //CSV出力ﾌｧｲﾙﾊﾟｽを取得する
            $strCsvPath = $this->ClsComFnc->FncGetPath("KAIcsvpath");
            //LOG出力ﾊﾟｽを取得する
            $strLogPath = $this->ClsComFnc->FncGetPath("pprlogpath");
            //ﾊﾞｯｸｱｯﾌﾟﾊﾟｽを取得する
            $strBackUpPath = $this->ClsComFnc->FncGetPath("pprbackuppath");

            if ($strCsvPath == "") {
                //CSV出力ﾊﾟｽをﾃｷｽﾄに初期表示する
                $strCsvPath = $strPath . "/mnt/CSV/KEIRI/";
            } else {
                //デフォルトのCSV出力先を表示する
                $strCsvPath = $strPath . "/" . $strCsvPath;
            }

            if (file_exists($strCsvPath) == FALSE) {
                if (!mkdir($strCsvPath, 0777, TRUE)) {
                    $this->strMessage = "[" . $strCsvPath . "]の新規に失敗しました。";
                    throw new \Exception($this->strMessage);
                }
            }

            if ($strLogPath == "") {
                $strLogPath = $strPath . "/mnt/temp/log/LOG.Log";
            } else {
                $strLogPath = $strPath . "/mnt/temp/" . $strLogPath;
            }

            $dirpath = dirname($strLogPath);
            if (file_exists($dirpath) == FALSE) {
                if (!mkdir($dirpath, 0777, TRUE)) {
                    $this->strMessage = "[" . $dirpath . "]の新規に失敗しました。";
                    throw new \Exception($this->strMessage);
                }
            }

            if ($strBackUpPath == "") {
                $strBackUpPath = $strPath . "/mnt/temp/BACK0908/";
            } else {
                $strBackUpPath = $strPath . "/" . $strBackUpPath;
            }

            if (file_exists($strBackUpPath) == FALSE) {
                if (!mkdir($strBackUpPath, 0777, TRUE)) {
                    $this->strMessage = "[" . $strBackUpPath . "]の新規に失敗しました。";
                    throw new \Exception($this->strMessage);
                }
            }

            $result['result'] = TRUE;
            $result['data'] = "";
            $result['strCsvPath'] = $strCsvPath;
            $result['strLogPath'] = $strLogPath;
            $result['strBackUpPath'] = $strBackUpPath;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncLogUpdate()
    {
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        try {
            $this->FrmR2KAIKEI = new FrmR2KAIKEI();
            $result = $this->FrmR2KAIKEI->fncLogUpdate();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $result['result'] = TRUE;
                $result['data'] = "";
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    function fncCheckState()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo',
            'blnLockFlg' => FALSE
        );
        $flagLogState = FALSE;
        $blnLockFlg = FALSE;

        try {
            // if (isset($_POST['data'])) {
            //     $postData = $_POST['data'];
            // }

            //-----排他制御-----
            //ロックの状態を確かめる
            //---20180223 li UPD S.
            // $flagLogState = $this -> ClsComControl -> fncControlCheck("7");
            $flagLogState = $this->ClsComControl->FncControlCheck("7");
            //---20180223 li UPD E.

            if ($flagLogState == FALSE) {
                $result['result'] = FALSE;
                $result['data'] = "別ユーザが実行中です";
                $result['blnLockFlg'] = FALSE;
            } else {
                //ロックをかける
                $this->FrmR2KAIKEI = new FrmR2KAIKEI();
                $result = $this->FrmR2KAIKEI->fncUpdControl();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $this->blnLockFlg = TRUE;

                $result['result'] = TRUE;
                $result['data'] = "";
                $result['blnLockFlg'] = $this->blnLockFlg;

                //出力先ﾁｪｯｸ
                // strRtn = clsOutPut.fncOutChk(clsOutPut.strLogPath, strErrMsg)
                // If strRtn <> "" Then
                // clsFnction.FncMsgBox("E9999", "ﾛｸﾞﾌｧｲﾙ出力パスが存在しません" & vbCrLf & clsOutPut.strLogPath)
                // Return
                // End If
                // strRtn = clsOutPut.fncOutChk(Me.txtOutput.Text, strErrMsg)
                // If strRtn <> "" Then
                // clsFnction.FncMsgBox(strRtn, strErrMsg)
                // Return
                // End If
                // strRtn = clsOutPut.fncOutChk(clsOutPut.strBackUpPath, strErrMsg)
                // If strRtn <> "" Then
                // clsFnction.FncMsgBox("E9999", "ﾊﾞｯｸｱｯﾌﾟ先パスが存在しません" & vbCrLf & clsOutPut.strBackUpPath)
                // Return
                // End If
            }

            // $result = $this -> FrmR2KAIKEI -> Do_conn();
            //
            // if (!$result['result'])
            // {
            // throw new Exception($result['data']);
            // }
            //
            // $this -> blnDBconectFlg = true;
            // //ﾏｽﾀに登録開始
            // //ﾄﾗﾝｻﾞｸｼｮﾝ開始
            // $this -> FrmR2KAIKEI -> Do_transaction();
            //
            // //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            // $this -> errorFlag = TRUE;
            //
            // //ﾏｽﾀを削除する
            // $result = $this -> FrmYosanTorikomiMst -> fncDeleteYosanTorikomiMst($postData['BUSYO_KB']);
            //
            // if (!$result['result'])
            // {
            // throw new Exception($result['data']);
            // }
            // else
            // {
            // //INSERT発行
            // for ($i = 0; $i < count($postData['inputDatas']); $i++)
            // {
            // if ($postData['inputDatas'][$i]['BUSYO_KB'] != "")
            // {
            // //更新処理を実行
            // $result = $this -> FrmYosanTorikomiMst -> fncInsertYosanTorikomiMst($postData['inputDatas'][$i]);
            // }
            //
            // if (!$result['result'])
            // {
            // throw new Exception($result['data']);
            // }
            // }
            //
            // $result['result'] = TRUE;
            // $result['data'] = "";
            // }
            //
            // //コミット
            // $this -> FrmYosanTorikomiMst -> Do_commit();
            // //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            // $this -> errorFlag = FALSE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['blnLockFlg'] = $this->$blnLockFlg;
        }

        $this->fncReturn($result);
    }

}