<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJKSYSDLStateCheck;

//*******************************************
// * sample controller
//*******************************************
class FrmJKSYSDLStateCheckController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $FrmJKSYSDLStateCheck;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }
    /*
           ***********************************************************************
           '処 理 名：初期表示
           '関 数 名：index
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */

    public function index()
    {
        $this->render('index', 'FrmDLStateCheck_layout');
    }
    /*
           ***********************************************************************
           '処 理 名：データを取得
           '関 数 名：frmDLStateCheck_load
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function frmDLStateCheckLoad()
    {
        $result = array(
            'result' => false,
            'data' => '',
            'error' => '',
        );
        try {
            $this->FrmJKSYSDLStateCheck = new FrmJKSYSDLStateCheck();
            $result = $this->FrmJKSYSDLStateCheck->fncHFTS_TRANSFER_LIST_Sel();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $result = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($result['data'], $totalPage, $page, $tmpCount);
        } catch (\Exception $e) {
            //エラーメッセージのタイトルに問題があるため、ここでの戻り値はtrue
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //ログ出力先取得
    public function fncGetPath()
    {
        $res = array(
            'result' => false,
            'data' => '',
            'error' => ''
        );
        try {
            $path = $this->ClsComFncJKSYS->FncGetPath("JKImportCsvLog");
            $res['data'] = $path;
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = false;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    /*
           ***********************************************************************
           '処 理 名：データを更新
           '関 数 名：fncHFTS_TRANSFER_LIST_Upd
           '引    数：無し
           '戻 り 値 ：無し
           '処理説明 ：
           '**********************************************************************
           */
    public function fncHFTSTRANSFERLISTUpd()
    {
        $this->FrmJKSYSDLStateCheck = new FrmJKSYSDLStateCheck();
        $res = array(
            'result' => false,
            'data' => '',
            'error' => '',
        );
        $blnTran = FALSE;
        try {
            if (isset($_POST['data'])) {
                $data = $_POST['data']['data'];
                //トランザクション開始
                $this->FrmJKSYSDLStateCheck->Do_transaction();
                $blnTran = TRUE;
                //ﾁｪｯｸが入っている行を更新する
                foreach ($data as $value) {
                    $result = $this->FrmJKSYSDLStateCheck->fncHFTS_TRANSFER_LIST_Upd($value['FILE_NM']);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                }
                //コミット
                $this->FrmJKSYSDLStateCheck->Do_commit();
                $blnTran = FALSE;
            }
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = false;
            $res['error'] = $e->getMessage();
            //ロールバック
            if ($blnTran) {
                $this->FrmJKSYSDLStateCheck->Do_rollback();
            }
        }
        $this->fncReturn($res);
    }

}
