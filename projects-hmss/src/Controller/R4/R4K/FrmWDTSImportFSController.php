<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151116           #2276						   BUG                              li
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmWDTSImportFS;

class FrmWDTSImportFSController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $const_dl_kind = "1";
    private $const_dl_step = "2";
    private $strDBLink = "";
    private $strStartTime = "";
    private $strStartDate = "";
    private $strClientNM = "";
    private $strMessage = "";
    private $blnTran = FALSE;
    private $blnFTSUpd = FALSE;
    private $blnDBconectFlg = FALSE;
    private $FrmWDTSImportFS;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsComControl');
        $this->loadComponent('ClsFileObserver');
    }
    public function index()
    {
        $this->render('index', 'FrmWDTSImportFS_layout');
    }

    public function buttonclick()
    {
        $strMessage = "";
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
            $strFileNM = "";
            $fncInsHFTS_TRANSFER_LISTSQL = "";
            $objSw = "";

            //サーバ名取得
            $strGyoumDbLink = $this->ClsComFnc->FncGetPath("GyoumSvLinkNM");

            //2015-12-25 Update Start
//				switch ($tmpArr[1])
//				{
//					case 'GDMZ' :
//						$this -> strDBLink = "";
//						//$strPara1 = "日次ダウンロード";
//						break;
//
//					default :
//						$this -> strDBLink = $strGyoumDbLink;
//						//$strPara1 = "経理ダウンロード";
//						break;
//				}
            $this->strDBLink = $strGyoumDbLink;
            //2015-12-25 Update End
            $this->FrmWDTSImportFS = new FrmWDTSImportFS();

            //-----ダウンロード対象グループを取得する----
            //$result = $this -> FrmWDTSImportFS -> fncGetDate($this -> strDBLink);
            $result = $this->FrmWDTSImportFS->fncGetTableID($this->strDBLink, $this->const_dl_kind);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count((array) $result['data']) == 0) {
                throw new \Exception("ダウンロード対象グループが設定されていません", 1);
            }
            //取込処理が終了しているか及びロック状況をﾁｪｯｸする
            foreach ((array) $result['data'] as $key => $value) {
                $strTableID = $this->ClsComFnc->FncNv($result['data'][$key]['TABLE_ID']);
                //---20151116 li UPD S.
                // $result = $this -> ClsFileObserver -> fcLockCheckDBLink($strTableID, $this -> const_dl_step, $strMessage, $this -> strDBLink);
                // //print_r($strMessage);
                // if (!$result)
                $resultCls = $this->ClsFileObserver->fcLockCheckDBLink($strTableID, $this->const_dl_step, $strMessage, $this->strDBLink);
                if (!$resultCls)
                //---20151116 li UPD E.
                {
                    if ($strMessage == "") {
                        $strMessage = "error";
                    }
                    throw new \Exception($strMessage, 1);

                }
            }
            $this->strStartDate = $this->ClsComFnc->FncGetSysDate("Ymd His");
            $this->strStartTime = substr($this->strStartDate, 9);
            $this->strStartDate = substr($this->strStartDate, 0, 8);

            //コンピュータ名取得
            $this->strClientNM = $this->FrmWDTSImportFS->fncGetClient();

            if (strlen($this->strClientNM) > 20) {
                $this->strClientNM = substr($this->strClientNM, 0, 19);
            }

            //トランザクション処理開始
            $result = $this->FrmWDTSImportFS->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->blnDBconectFlg = TRUE;

            $this->FrmWDTSImportFS->Do_transaction();
            $this->blnTran = TRUE;

            //HFTS_TARNSFER_LISTにINSERTする
            $fncInsHFTS_TRANSFER_LISTSQL = $this->ClsFileObserver->fncInsHFTSTRANSFERLIST("004", $this->strStartDate, $this->strStartTime, $this->strClientNM, "R4連携集計システム用ダウンロード", "", "", $this->strDBLink);
            $result = $this->FrmWDTSImportFS->fncInsHFTSTRANSFERLIST($fncInsHFTS_TRANSFER_LISTSQL);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $this->FrmWDTSImportFS->Do_commit();

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
            $strFileNM = $strFileNM . "004" . $this->strStartDate . $this->strStartTime . $this->strClientNM . ".txt";
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
            $this->blnTran = FALSE;

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
            $this->FrmWDTSImportFS->Do_rollback();
        } else {
            //ｴﾗｰが発生した場合、ファイル転送ﾘｽﾄにｴﾗｰ内容を更新する
            if ($this->blnFTSUpd == TRUE && $this->strMessage != "") {
                $clsFos = $this->ClsFileObserver->fncUpdTrnTbl("004", $this->strStartDate, $this->strStartTime, $this->strClientNM, 0, $this->ClsFileObserver->enmState["ErrIrregular"], $this->strMessage, TRUE, $this->strDBLink);

                if ($clsFos == FALSE) {
                    // $result['result'] = FALSE;
                    // $result['data'] = $this->strMessage;
                }
            }
        }

        //DB接続解除
        if ($this->blnDBconectFlg) {
            $this->FrmWDTSImportFS->Do_close();
        }
    }

}