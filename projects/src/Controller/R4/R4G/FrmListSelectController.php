<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmListSelect;

//*******************************************
// * sample controller
//*******************************************
class FrmListSelectController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmListSelect;
    public function index()
    {
        $this->render('index', 'frmListSelect_layout');
    }

    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public function fncFrmListSelect()
    {
        $postData = '';
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $postData = $_POST['request'];
            if (isset($postData)) {
                $NENGETU = Date("Ym");
                $this->FrmListSelect = new FrmListSelect();
                $result = $this->FrmListSelect->fncFrmListSelect($postData, $NENGETU);
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);

                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $result = $this->FrmListSelect->fncFrmListSelect($postData, $NENGETU);
                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                unset($_POST['request']);

                $this->fncReturn($tmpJqgrid);

            } else {
                return;
            }
        } else {
            return;
        }
    }

}
