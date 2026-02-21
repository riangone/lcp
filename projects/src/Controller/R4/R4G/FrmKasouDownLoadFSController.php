<?php

namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmKasouDownLoadFS;

class FrmKasouDownLoadFSController extends AppController
{
    // public $autoRender = false;

    public $fncControlCheck = "";

    public $FrmKasouDownLoadFS = "";
    public $startDate = "";
    public $startTime = "";
    public $client = "";
    public $blnTran = "";
    public $blnFTSUpd = "";
    public $strMessage = "";
    public $message_array;
    public $lable = TRUE;
    public $Do_Excute;
    public function index()
    {
        $this->render('index', 'FrmKasouDownLoadFS_layout');
    }
    // public $ClsComFnc = '';
    // public $ClsComControl = '';
    // public $ClsFileObserver = '';
    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
        $this->loadComponent('ClsComControl');
        $this->loadComponent('ClsFileObserver');
        $this->loadComponent('ClsComFnc');
    }

    public function buttonclick()
    {
        $lockstate = "";
        $sysdate = "";
        $directory = "";
        $strFileNM = "";
        $this->blnTran = False;
        $this->blnFTSUpd = False;
        $chumon_no = "";
        // register_shutdown_function(
        //     array(
        //         $this,
        //         "finally"
        //     )
        // );
        try {
            //-----取込処理が終了しているかﾁｪｯｸする----
            $this->FrmKasouDownLoadFS = new FrmKasouDownLoadFS();
            $result = $this->FrmKasouDownLoadFS->select_sql();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if ($result['data'][0]['BEF_GET_DT'] != null) {
                $this->lable = FALSE;
                $this->message_array = array(
                    "msg" => array(
                        "error_code" => "E9999",
                        "message" => "取込が終了していません! 取込を行ってからダウンロードを実行してください。"
                    ),
                    "flag" => true
                );
            } else {
                //-----排他制御-----
                // ロックの状態を確かめる
                $this->lable = FALSE;
                $lockstate = $_POST['data']['status'];
                $this->fncControlCheck = $this->ClsComControl->fncControlCheck($lockstate);
                if ($this->fncControlCheck == FALSE) {
                    $this->message_array = array(
                        "msg" => array(
                            "error_code" => "E9999",
                            "message" => "別ユーザが実行中です"
                        ),
                        "flag" => true
                    );
                } else {
                    //日付を取得
                    $sysdate = $this->ClsComFnc->FncGetSysDate("Ymd His");
                    $this->startDate = substr($sysdate, 0, 8);
                    $this->startTime = substr($sysdate, 9);
                    //コンピュータ名取得
                    $this->client = $this->FrmKasouDownLoadFS->fncgetclient();
                    if (strlen($this->client) > 20) {
                        $this->client = substr($this->client, 0, 19);
                    }
                    //トランザクション処理開始
                    $chumon_no = $_POST['data']['Chumon_NO1'];
                    $result = $this->FrmKasouDownLoadFS->Do_conn();
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $this->FrmKasouDownLoadFS->Do_transaction();
                    $this->blnTran = TRUE;
                    //SQL操作共通関数を呼び出し  fncInsHFTS_TRANSFER_LIST
                    $fncInsHFTSTRANSFERLISTSql = $this->ClsFileObserver->fncInsHFTSTRANSFERLIST("001", $this->startDate, $this->startTime, $this->client, "注文書関連データダウンロード", $chumon_no, "", "");
                    $this->Do_Excute = $this->FrmKasouDownLoadFS->fncInsHFTSTRANSFERLIST($fncInsHFTSTRANSFERLISTSql);
                    if (!$this->Do_Excute['result']) {
                        throw new \Exception($this->Do_Excute['data']);
                    }
                    $this->blnFTSUpd = TRUE;
                    //外部パス取得の共通関数を呼び出し
                    $directory = $this->ClsComFnc->FncGetPath("PathFrom");
                    $strPath = dirname(dirname(dirname(dirname(__FILE__))));
                    //ﾌｧｲﾙ名を設定
                    //$strFileNM = $strPath . "/" . $directory . "/001" . $this -> startDate . $this -> startTime . $this -> client . ".txt";
                    $strFileNM = $strPath . "/" . $directory . "001" . $this->startDate . $this->startTime . $this->client . ".txt";
                    //ファイル作成
                    //mkdir($directory);
                    $objSw = fopen($strFileNM, "w");
                    //ファイル閉じる
                    fclose($objSw);
                    //トランザクションをコミットする
                    //ダウンロード実行画面を呼び出しために、状態を設定
                    $this->message_array = array(
                        "msg" => true,
                        "flag" => true
                    );
                    $this->FrmKasouDownLoadFS->Do_commit();
                    $this->blnTran = FALSE;
                }
            }
        } catch (\Exception $e) {
            $this->message_array['flag'] = FALSE;
            $this->message_array['msg'] = array(
                "error_code" => "E9999",
                "message" => $e->getMessage()
            );
            $this->strMessage = $e->getMessage();
        }
        $this->finally();
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    function finally()
    {
        if ($this->blnTran) {
            $this->FrmKasouDownLoadFS->Do_rollback();
        } else {
            //ｴﾗｰが発生した場合、ファイル転送ﾘｽﾄにｴﾗｰ内容を更新する
            if ($this->blnFTSUpd == TRUE && $this->strMessage != "") {
                $clsFos = $this->ClsFileObserver->fncUpdTrnTbl("001", $this->startDate, $this->startTime, $this->client, 0, $this->ClsFileObserver->enmState["ErrIrregular"], $this->strMessage, TRUE);
                if ($clsFos == FALSE) {
                    $this->message_array['flag'] = FALSE;
                    $this->message_array['msg'] = array(
                        "error_code" => "E9999",
                        "message" => $this->strMessage
                    );
                }
            }
        }
        $this->fncReturn($this->message_array);
        if ($this->lable == TRUE) {
            $this->FrmKasouDownLoadFS->Do_close();
        }
    }

}
