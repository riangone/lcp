<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmProgramSearch;

class FrmProgramSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    private $FrmProgramSearch;
    // public $ClsComFnc = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }


    public function index()
    {
        $this->render('index', 'FrmProgramSearch_layout');
    }

    public function fncHPROGRAMMSTSel()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->FrmProgramSearch = new FrmProgramSearch();
                $result = $this->FrmProgramSearch->fncHPROGRAMMSTSel($postData["PRO_NM"]);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                }
            } else {
                $result['result'] = TRUE;
                $result['data'] = "";

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

}