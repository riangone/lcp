<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSCUriageList;

class FrmSCUriageListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmSCUriageList = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmSCUriageList_layout');
    }

    public function subSpreadReShow()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $this->FrmSCUriageList = new FrmSCUriageList();
                $result = $this->FrmSCUriageList->FncSelectHscUri($postData, "", "");
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data'], TRUE);
                    $start = $tmpJqgridShow['start'];
                    $limit = $tmpJqgridShow['limit'];
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $result = $this->FrmSCUriageList->FncSelectHscUri($postData, $start, $limit);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount, $start);
                    $result = $tmpJqgrid;
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}