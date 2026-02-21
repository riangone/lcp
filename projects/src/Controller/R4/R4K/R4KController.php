<?php
//20141028 fan upd.
// App::uses('AppController', 'Controller');
namespace App\Controller\R4\R4K;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class R4KController extends AppController
{
    // public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    // var $components = array('RequestHandler');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $r4_name = "管理会計システム";
        $this->set('r4_name', $r4_name);
        // Viewファイル呼出し
        // $this->render('index');
        $this->render('/R4/R4K/index', 'R4K_layout');
    }
}