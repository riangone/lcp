<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE370MLOGINList;
use App\Controller\HMTVE\HMTVEController;
//*******************************************
// * sample controller
//*******************************************
class HMTVE370MLOGINListController extends AppController
{
    public $autoLayout = TRUE;
    private $HMTVE370MLOGINList;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE370MLOGINList_layout');
    }

    //検索ボタンのイベント
    public function btnSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE370MLOGINList = new HMTVE370MLOGINList();
                $result = $this->HMTVE370MLOGINList->btnSearch_Click(HMTVEController::SYS_KB, $postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //データ削除のイベント
    public function deleteDataByCD()
    {
        $result = array(
            'result' => false,
            'error' => '',
            'data' => ''
        );
        try {
            $this->HMTVE370MLOGINList = new HMTVE370MLOGINList();
            $resultDel = $this->HMTVE370MLOGINList->deleteDataByCD(HMTVEController::SYS_KB, $_POST['data']['SYAINCD']);
            if (!$resultDel['result']) {
                throw new \Exception($resultDel['data']);
            }
            if ($resultDel['number_of_rows'] == '0') {
                $result['data'] = 'W0024';
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        // Viewファイル呼出し
        $this->fncReturn($result);
    }
}
