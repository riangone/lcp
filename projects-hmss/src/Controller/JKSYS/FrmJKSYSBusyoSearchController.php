<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJKSYSBusyoSearch;
//*******************************************
// * sample controller
//*******************************************
class FrmJKSYSBusyoSearchController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncJKSYS');
    }

    public $autoLayout = TRUE;
    // public $autoRender = FALSE;

    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmBusyoSearch_layout.ctpを参照)
        $this->render('index', 'FrmBusyoSearch_layout');
    }

    public function fncDataSet()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $frmJKSYSBusyoSearch = new FrmJKSYSBusyoSearch();
                $result = $frmJKSYSBusyoSearch->fncDataSet($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncJKSYS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncJKSYS->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
