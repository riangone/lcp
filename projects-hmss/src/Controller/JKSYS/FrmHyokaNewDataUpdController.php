<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmHyokaNewDataUpd;

//*******************************************
// * sample controller
//*******************************************
class FrmHyokaNewDataUpdController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $this->render('index', 'FrmHyokaNewDataUpd_layout');
    }

    public function updateAction()
    {
        $result = array(
            'result' => false,
            'data' => 'ErrorInfo',
            'row' => '',
        );

        try {
            $frmHyokaNewDataUpd = new FrmHyokaNewDataUpd();
            $result = $frmHyokaNewDataUpd->fncUpdData();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
