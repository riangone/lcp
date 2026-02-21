<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class JKSYSController extends AppController
{
    // public $autoLayout = TRUE;
    // public $autoRender = FALSE;
    // var $components = array('RequestHandler', 'ClsComFnc', 'ClsCreateCsv', 'ClsLogControl');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // $this -> layout = 'JKSYS_layout';
        $app_name = "人事給与システム";
        $this->set('app_name', $app_name);
        // Viewファイル呼出し
        $this->render('/JKSYS/JKSYS/index', 'JKSYS_layout');
    }
}