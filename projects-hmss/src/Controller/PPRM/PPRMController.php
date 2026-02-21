<?php
// App::uses('AppController', 'Controller');
namespace App\Controller\PPRM;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class PPRMController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    // var $components = array('RequestHandler');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM_layout';
        $PPRM_name = "ペーパレス化支援システム";
        $this->set('PPRM_name', $PPRM_name);
        // Viewファイル呼出し
        $this->render('/PPRM/PPRM/index', $layout);
    }

}