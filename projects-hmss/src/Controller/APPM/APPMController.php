<?php
// App::uses('AppController', 'Controller');
namespace App\Controller\APPM;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class APPMController extends AppController
{
    // public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    // var $components = array('RequestHandler');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // $this->layout = 'APPM_layout';
        $app_name = "広アプシステム";
        $this->set('app_name', $app_name);
        // Viewファイル呼出し
        $this->render('/APPM/APPM/index', 'APPM_layout');
    }

}