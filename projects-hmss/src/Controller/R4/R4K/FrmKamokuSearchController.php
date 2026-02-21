<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKamokuSearch;

//*******************************************
// * sample controller
//*******************************************
class FrmKamokuSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/FrmBusyoSearch_layout.ctpを参照)

        $this->render('index', 'FrmKamokuSearch_layout');
    }

    public function fncDataSet()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $FrmKanrChkList = new FrmKamokuSearch();

                $result = $FrmKanrChkList->fncDataSet($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;
            } else {

            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}