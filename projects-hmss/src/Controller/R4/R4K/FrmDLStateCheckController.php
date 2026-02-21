<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmDLStateCheck;

class FrmDLStateCheckController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmDLStateCheck;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    var $blnTran = FALSE;

    public function index()
    {
        $this->render('index', 'FrmDLStateCheck_layout');
    }

    public function fncHFTSTRANSFERLISTSel()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmDLStateCheck = new FrmDLStateCheck();

            $strDBLink = $this->fncGetDBLink();
            $result = $this->FrmDLStateCheck->fncHFTS_TRANSFER_LIST_Sel($strDBLink);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;
                unset($_POST['request']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    function fncGetDBLink()
    {
        //サーバ名取得
        $strDBLink = "";
        //業務サーバへのDBリンク名取得
        $strGyoumDbLink = $this->ClsComFnc->FncGetPath("GyoumSvLinkNM");
        //2015-12-25 Update start
//			switch ($tmpArr[1])
//			{
//				case 'GDMZ' :
//					$strDBLink = "";
//					break;
//
//				default :
//					$strDBLink = $strGyoumDbLink;
//					break;
//			}
        $strDBLink = $strGyoumDbLink;
        //2015-12-25 Update END
        return $strDBLink;
    }

    public function fncStateDelUpd()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            $strDBLink = $this->fncGetDBLink();

            $this->FrmDLStateCheck = new FrmDLStateCheck();
            $result = $this->FrmDLStateCheck->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //トランザクション処理
            $this->FrmDLStateCheck->Do_transaction();

            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTran = TRUE;

            //ﾊﾟﾀｰﾝﾏｽﾀ更新処理
            for ($i = 0; $i < count($postData['inputData']); $i++) {
                $rowData = $postData['inputData'][$i];

                $result = $this->FrmDLStateCheck->fncHFTS_TRANSFER_LIST_Upd($rowData, $strDBLink);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }

            $result['result'] = TRUE;
            $result['data'] = "";

            //コミット
            $this->FrmDLStateCheck->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTran = FALSE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function finally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->blnTran) {
            $this->FrmDLStateCheck->Do_rollback();
        }
        //DB接続解除
        $this->FrmDLStateCheck->Do_close();
    }

}