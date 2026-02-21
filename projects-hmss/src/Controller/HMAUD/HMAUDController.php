<?php
// App::uses('AppController', 'Controller');
namespace App\Controller\HMAUD;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class HMAUDController extends AppController
{
    const SYS_KB = '19';
    const STYLE_ID = '001';
    // public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    // var $components = array('RequestHandler');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // $this->layout = 'HMAUD_layout';
        $app_name = "内部統制システム";
        $this->set('app_name', $app_name);
        // Viewファイル呼出し
        $this->render('/HMAUD/HMAUD/index', 'HMAUD_layout');
    }
}