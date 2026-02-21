<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmStaffMemoMnt;

//*******************************************
// * sample controller
//*******************************************
class FrmStaffMemoMntController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmStaffMemoMnt = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmStaffMemoMnt_layout');
    }

    public function fncStaffMemoSelect()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmStaffMemoMnt = new FrmStaffMemoMnt();

            $result = $this->FrmStaffMemoMnt->fncStaffMemoSelect($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
        }

        $this->fncReturn($result);
    }

    public function fncDeleteInsertStaffMemo()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data']['request'];
        try {
            $this->FrmStaffMemoMnt = new FrmStaffMemoMnt();

            $result = $this->FrmStaffMemoMnt->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmStaffMemoMnt->Do_transaction();

            $blnTran = TRUE;

            $result1 = $this->FrmStaffMemoMnt->fncDeleteStaffMemo($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }

            foreach ($postData['jqData'] as $key => $value) {

                $result2 = $this->FrmStaffMemoMnt->fncInsertStaffMemo($postData, $value, $key);

                if (!$result2['result']) {
                    throw new \Exception($result2['data']);
                }
            }

            $this->FrmStaffMemoMnt->Do_commit();

            $blnTran = FALSE;

            $result['result'] = TRUE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTran) {
            $this->FrmStaffMemoMnt->Do_rollback();
        }

        $this->FrmStaffMemoMnt->Do_close();

        $this->fncReturn($result);
    }

}