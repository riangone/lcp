<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJKSYSLoginSel;
class FrmJKSYSLoginSelController extends AppController
{
    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $FrmJKSYSLoginSel;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }
    public function index()
    {
        $this->render('index', 'FrmLoginSel_layout');
    }

    public function fncGetLoginInfo()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $this->FrmJKSYSLoginSel = new FrmJKSYSLoginSel();
                $result = $this->FrmJKSYSLoginSel->getCarry($postData["KJNBI"], $postData["SYAIN_NO"], $postData["cboSysKB"]);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];
                $result = $this->ClsComFncJKSYS->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncLoadDeal()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $this->FrmJKSYSLoginSel = new FrmJKSYSLoginSel();

            $result = $this->FrmJKSYSLoginSel->frmLoginSel_Load();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            if (count((array) $result['data']) == 0) {
                throw new \Exception("コントロールマスタが存在しません！");
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
