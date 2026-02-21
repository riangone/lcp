<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmTRKDownLoadFS;

class FrmTRKDownLoadFSController extends AppController
{
    // public $autoRender = false;
    public $FrmTRKDownLoadFS = "";
    public $startdate = "";
    public $starttime = "";
    public $client = "";
    public $blnTran = "";
    public $blnFTSUpd = "";
    public $strMessage = "";
    public $message_array;
    public $label = TRUE;
    public $fncControlCheck;
    public $Do_conn;
    public $Do_Excute;
    public function index()
    {
        $this->render('index', 'FrmTRKDownLoadFS_layout');
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

        //ロック状態値
        $status = "";
        //システム日付値
        $sysdate = "";
        //予定日
        $schedudate = "";
        //フォルダパス
        $path = "";
        //ファイル名
        $filename = "";
        $this->blnTran = False;
        $this->blnFTSUpd = False;
        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );
        try {
            $this->FrmTRKDownLoadFS = new FrmTRKDownLoadFS();
            //取込処理が終了しているかﾁｪｯｸする
            $result = $this->FrmTRKDownLoadFS->select_data();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if ($result['data'][0]['BEF_GET_DT'] != null) {
                $this->label = FALSE;
                $this->message_array = array(
                    "msg" => array(
                        "error_code" => "E9999",
                        "message" => "取込が終了していません! 取込を行ってからダウンロードを実行してください。"
                    ),
                    "flag" => true
                );
            } else {
                //-----排他制御-----
                $this->label = FALSE;
                $status = $_POST['data']['status'];

                $this->fncControlCheck = $this->ClsComControl->fncControlCheck($status);

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
                    $this->startdate = substr($sysdate, 0, 8);
                    $this->starttime = substr($sysdate, 9);
                    //コンピュータ名取得
                    $this->client = $this->FrmTRKDownLoadFS->fncgetclient();
                    if (strlen($this->client) > 20) {
                        $this->client = substr($this->client, 0, 19);
                    }

                    //トランザクション処理開始
                    $schedudate = $_POST['data']['cboT_YoteiBi'];
                    $this->Do_conn = $this->FrmTRKDownLoadFS->Do_conn();
                    if (!$this->Do_conn['result']) {
                        throw new \Exception($this->Do_conn['data']);
                    }
                    $this->FrmTRKDownLoadFS->Do_transaction();
                    $this->blnTran = TRUE;

                    //SQL操作共通関数を呼び出し  fncInsHFTS_TRANSFER_LIST
                    $fncInsHFTSTRANSFERLISTSql = $this->ClsFileObserver->fncInsHFTSTRANSFERLIST("001", $this->startdate, $this->starttime, $this->client, "登録予定ダウンロード", str_replace('/', '', rtrim($schedudate)), "", "");
                    $this->Do_Excute = $this->FrmTRKDownLoadFS->fncInsHFTSTRANSFERLIST($fncInsHFTSTRANSFERLISTSql);
                    if (!$this->Do_Excute['result']) {
                        throw new \Exception($this->Do_Excute['data']);
                    }
                    $this->blnFTSUpd = TRUE;
                    //外部パス取得の共通関数を呼び出し
                    $path = $this->ClsComFnc->FncGetPath("PathFrom");
                    $strPath = dirname(dirname(dirname(dirname(__FILE__))));
                    //ファイル名を設定
                    //$filename = $strPath . "/" . $path . "/001" . $this -> startdate . $this -> starttime . $this -> client . ".txt";
                    $filename = $strPath . "/" . $path . "001" . $this->startdate . $this->starttime . $this->client . ".txt";


                    //ファイル作成
                    //mkdir($path);
                    $objSw = fopen($filename, "w");
                    //ファイル閉じる
                    fclose($objSw);
                    //トランザクションをコミットする
                    //ダウンロード実行画面を呼び出しために、状態を設定
                    $this->message_array = array(
                        "msg" => true,
                        "flag" => true
                    );
                    $this->FrmTRKDownLoadFS->Do_commit();
                    $this->blnTran = FALSE;
                }
            }
        } catch (\Exception $e) {
            $this->message_array['flag'] = FALSE;
            $this->message_array['msg'] = array(
                "error_code" => "E9999",
                "message" => $e->getMessage()
            );
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
            $this->FrmTRKDownLoadFS->Do_rollback();
        } else {
            //ｴﾗｰが発生した場合、ファイル転送ﾘｽﾄにｴﾗｰ内容を更新する
            if ($this->blnFTSUpd == TRUE && $this->strMessage != "") {
                $clsFos = $this->ClsFileObserver->fncUpdTrnTbl("001", $this->startdate, $this->starttime, $this->client, 0, $this->ClsFileObserver->enmState["ErrIrregular"], $this->strMessage, TRUE);
                if ($clsFos == FALSE) {
                    $this->message_array['flag'] = FALSE;
                    $this->message_array['msg'] = array(
                        "error_code" => "E9999",
                        "message" => $this->strMessage
                    );
                }
            }
        }
        if ($this->label == TRUE) {
            $this->FrmTRKDownLoadFS->Do_close();
        }

        $this->fncReturn($this->message_array);

    }

}
