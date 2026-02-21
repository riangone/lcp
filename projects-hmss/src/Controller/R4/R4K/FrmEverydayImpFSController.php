<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmEverydayImpFS;

class FrmEverydayImpFSController extends AppController
{
    public $autoLayout = TRUE;
    private $FrmEverydayImpFS;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsComControl');
        $this->loadComponent('ClsFileObserver');
    }
    public $strDBLink = "";
    public $strStartTime = "";
    public $strStartDate = "";
    public $strClientNM = "";
    public $strMessage = "";
    public $blnTran = FALSE;
    public $blnFTSUpd = FALSE;
    public $blnDBconectFlg = FALSE;

    public function index()
    {
        $this->render('index', 'FrmEverydayImpFS_layout');
    }

    public function buttonclick()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo',
        );

        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );

        try {
            $strPara1 = "";
            $strFileNM = "";
            $fncInsHFTS_TRANSFER_LISTSQL = "";
            $objSw = "";

            //サーバ名取得
            // $strServer = $this->ClsComFnc->FncGetPath("server");
            $strGyoumDbLink = $this->ClsComFnc->FncGetPath("GyoumSvLinkNM");
            // $tmpArr = explode("/", strtoupper($strServer));

            // 2015-12-25 Update Start
            // switch ($tmpArr[1]) {
            //     case 'GDMZ':
            //         $this->strDBLink = "";
            //         $strPara1 = "日次ダウンロード";
            //         break;

            //     default:
            //         $this->strDBLink = $strGyoumDbLink;
            //         $strPara1 = "経理ダウンロード";
            //         break;
            // }

            $this->strDBLink = $strGyoumDbLink;
            $strPara1 = "経理ダウンロード";
            //2015-12-25 Update End

            $this->FrmEverydayImpFS = new FrmEverydayImpFS();

            //-----取込処理が終了しているかﾁｪｯｸする----
            $result = $this->FrmEverydayImpFS->fncGetDate($this->strDBLink);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if ($this->ClsComFnc->FncNv($result['data'][0]['BEF_GET_DT']) == "") {
                $result['result'] = FALSE;
                $result['data'] = "取込処理は既に終了しています!";
            } else {
                //-----排他制御-----
                //ロックの状態を確かめる
                //---20180223 li UPD S.
                // $flagLogState = $this -> ClsComControl -> fncControlCheck("2", $this -> strDBLink);
                $flagLogState = $this->ClsComControl->FncControlCheck("2", $this->strDBLink);
                //---20180223 li UPD E.

                if ($flagLogState == FALSE) {
                    $result['result'] = FALSE;
                    $result['data'] = "別ユーザが実行中です";
                } else
                    if ($flagLogState == TRUE) {
                        $this->strStartDate = $this->ClsComFnc->FncGetSysDate("Ymd His");
                        $this->strStartTime = substr($this->strStartDate, 9);
                        $this->strStartDate = substr($this->strStartDate, 0, 8);

                        //コンピュータ名取得
                        $this->strClientNM = $this->FrmEverydayImpFS->fncGetClient();

                        if (strlen($this->strClientNM) > 20) {
                            $this->strClientNM = substr($this->strClientNM, 0, 19);
                        }

                        //トランザクション処理開始
                        $result = $this->FrmEverydayImpFS->Do_conn();

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }

                        $this->blnDBconectFlg = TRUE;

                        $this->FrmEverydayImpFS->Do_transaction();
                        $this->blnTran = TRUE;

                        //HFTS_TARNSFER_LISTにINSERTする
                        $fncInsHFTS_TRANSFER_LISTSQL = $this->ClsFileObserver->fncInsHFTSTRANSFERLIST("002", $this->strStartDate, $this->strStartTime, $this->strClientNM, $strPara1, "", "", $this->strDBLink);
                        $result = $this->FrmEverydayImpFS->fncInsHFTSTRANSFERLIST($fncInsHFTS_TRANSFER_LISTSQL);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }

                        //コミット
                        $this->FrmEverydayImpFS->Do_commit();

                        $this->blnTran = FALSE;
                        $this->blnFTSUpd = TRUE;

                        //FROMフォルダを取得する
                        $strPath = dirname(dirname(dirname(dirname(__FILE__))));
                        $strFileNM = $strPath . "/" . $this->ClsComFnc->FncGetPath("PathFrom");

                        if (!file_exists($strFileNM)) {
                            if (!mkdir($strFileNM, 0777, TRUE)) {
                                $this->strMessage = "[" . $strFileNM . "]の保存に失敗しました。";
                                throw new \Exception($this->strMessage);
                            }
                        }

                        //ﾌｧｲﾙ名を設定
                        $strFileNM = $strFileNM . "002" . $this->strStartDate . $this->strStartTime . $this->strClientNM . ".txt";
                        //インスタンス作成
                        $objSw = fopen($strFileNM, "a");

                        if ($objSw == FALSE) {
                            $this->strMessage = "[" . $strFileNM . "]の保存に失敗しました。";
                            throw new \Exception($this->strMessage);
                        }

                        //ファイル閉じる
                        if (!fclose($objSw)) {
                            $this->strMessage = "[" . $strFileNM . "]の保存に失敗しました。";
                            throw new \Exception($this->strMessage);
                        }

                        $result['data'] = "";
                    } else {
                        throw new \Exception($flagLogState);
                    }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    function finally()
    {
        if ($this->blnTran) {
            //ロールバック
            $this->FrmEverydayImpFS->Do_rollback();
        } else {
            //ｴﾗｰが発生した場合、ファイル転送ﾘｽﾄにｴﾗｰ内容を更新する
            if ($this->blnFTSUpd == TRUE && $this->strMessage != "") {
                $clsFos = $this->ClsFileObserver->fncUpdTrnTbl("002", $this->strStartDate, $this->strStartTime, $this->strClientNM, 0, $this->ClsFileObserver->enmState["ErrIrregular"], $this->strMessage, TRUE, $this->strDBLink);

                if ($clsFos == FALSE) {
                    // $result['result'] = FALSE;
                    // $result['data'] = $this->strMessage;
                }
            }
        }

        //DB接続解除
        if ($this->blnDBconectFlg) {
            $this->FrmEverydayImpFS->Do_close();
        }
    }

}