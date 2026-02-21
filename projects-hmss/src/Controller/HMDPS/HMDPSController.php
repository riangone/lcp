<?php
// App::uses('AppController', 'Controller');
namespace App\Controller\HMDPS;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class HMDPSController extends AppController
{
    const SYS_KB = '3';
    const STYLE_ID = '001';
    public $autoLayout = TRUE;
    // public $autoRender = false;
    // var $components = array('RequestHandler');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'HMDPS_layout';
        $app_name = "伝票集計システム";
        $this->set('app_name', $app_name);
        // Viewファイル呼出し
        $this->render('/HMDPS/HMDPS/index', $layout);
    }
}