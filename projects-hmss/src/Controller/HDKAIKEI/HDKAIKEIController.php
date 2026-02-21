<?php
// App::uses('AppController', 'Controller');
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class HDKAIKEIController extends AppController
{
    public $layout;
    const SYS_KB = '31';
    const STYLE_ID = '001';
    // public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    // var $components = array('RequestHandler');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->layout = 'HDKAIKEI_layout';
        $app_name = "（TMRH）HD伝票集計";
        $this->set('app_name', $app_name);
        // Viewファイル呼出し
        $this->render('/HDKAIKEI/HDKAIKEI/index', 'HDKAIKEI_layout');
    }
}