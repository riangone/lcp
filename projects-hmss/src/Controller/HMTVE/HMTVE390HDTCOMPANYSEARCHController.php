<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE390HDTCOMPANYSEARCH;
//*******************************************
// * sample controller
//*******************************************
class HMTVE390HDTCOMPANYSEARCHController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    private $HMTVE390HDTCOMPANYSEARCH;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE390HDTCOMPANYSEARCH_layout');
    }
    public function fncSearchSpread()
    {
        $result = array(
            'result' => TRUE,
            'error' => ''
        );

        try {
            $this->HMTVE390HDTCOMPANYSEARCH = new HMTVE390HDTCOMPANYSEARCH();
            $data_result = $this->HMTVE390HDTCOMPANYSEARCH->DataGetSQL(isset($_POST['request']['txtComCode']) ? $_POST['request']['txtComCode'] : "", isset($_POST['request']['txtComName']) ? $_POST['request']['txtComName'] : "");
            if (!$data_result['result']) {
                throw new \Exception($data_result['data']);
            }

            $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($data_result['data']);
            $start = $tmpJqgridShow['start'];
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($data_result["data"], $totalPage, $page, $tmpCount, $start);

            $this->fncReturn($tmpJqgrid);
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();

            $this->fncReturn($result);
        }
    }

}
