<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE340HBUSYOList;
//*******************************************
// * sample controller
//*******************************************
class HMTVE340HBUSYOListController extends AppController
{
    public $autoLayout = TRUE;
    private $HMTVE340HBUSYOList;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE340HBUSYOList_layout');
    }

    public function btnSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HMTVE340HBUSYOList = new HMTVE340HBUSYOList();

                $result = $this->HMTVE340HBUSYOList->btnSearch_Click($postData);

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
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
}
