<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE190CatalogOrderCareer;
//*******************************************
// * sample controller
//*******************************************
class HMTVE190CatalogOrderCareerController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public $Session;
    public $HMTVE190CatalogOrderCareer = "";

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    public function index()
    {
        $this->render('index', 'HMTVE190CatalogOrderCareer_layout');
    }

    public function pageload()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            //時間取得
            //対象期間を取得する
            $this->HMTVE190CatalogOrderCareer = new HMTVE190CatalogOrderCareer();
            $result_getTerm = $this->HMTVE190CatalogOrderCareer->getTerm();
            if (!$result_getTerm['result']) {
                throw new \Exception($result_getTerm['data']);
            }
            $result['data']['getTerm'] = $result_getTerm['data'];
            $this->HMTVE190CatalogOrderCareer = new HMTVE190CatalogOrderCareer();
            $result_getShop = $this->HMTVE190CatalogOrderCareer->getShop();
            if (!$result_getShop['result']) {
                throw new \Exception($result_getShop['data']);
            }
            $result['data']['getShop'] = $result_getShop['data'];
            $result['data']['sysDate'] = $this->ClsComFncHMTVE->FncGetSysDate('Y/m/d');
            $this->Session = $this->request->getSession();
            $result['data']['BusyoCD'] = $this->Session->read('BusyoCD');
            if (!isset($result['data']['BusyoCD'])) {
                throw new \Exception("W9999");
            }
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnETSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE190CatalogOrderCareer = new HMTVE190CatalogOrderCareer();
                $result = $this->HMTVE190CatalogOrderCareer->getThisDirectory($postdata);
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

}