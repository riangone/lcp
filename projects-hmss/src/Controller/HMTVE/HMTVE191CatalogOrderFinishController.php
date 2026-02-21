<?php
namespace App\Controller\HMTVE;
use App\Controller\AppController;
use App\Model\HMTVE\HMTVE191CatalogOrderFinish;
//*******************************************
// * sample controller
//*******************************************
class HMTVE191CatalogOrderFinishController extends AppController
{
    public $autoLayout = TRUE;
    public $HMTVE191CatalogOrderFinish;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }

    public function index()
    {
        $this->render('index', 'HMTVE191CatalogOrderFinish_layout');
    }
    public function pageload()
    {
        $this->HMTVE191CatalogOrderFinish = new HMTVE191CatalogOrderFinish();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $res = $this->HMTVE191CatalogOrderFinish->fncOrderSql($_POST['data']['OrderNO']);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

}
