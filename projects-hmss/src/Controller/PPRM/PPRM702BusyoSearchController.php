<?php
/**
 * 説明：
 *
 *
 * @author CIYUANCHEN
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM702BusyoSearch;

//*******************************************
// * sample controller
//*******************************************
class PPRM702BusyoSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM702BusyoSearch_layout';
        $this->render('/PPRM/PPRM702BusyoSearch/index', $layout);
    }

    public function btnViewClick()
    {

        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST["request"];
                $PPRM702BusyoSearch = new PPRM702BusyoSearch();
                $this->result = $PPRM702BusyoSearch->getDeployDataSQL($postData);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);
                $this->result = $tmpJqgrid;
            } else {
                $this->result = $result;
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);

    }

}
