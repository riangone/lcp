<?php
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class KRSSController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    // var $components = array('RequestHandler');
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // $this->layout = 'KRSS_layout';
        $KRSS_name = "経常利益シミュレーションシステム";
        $this->set('KRSS_name', $KRSS_name);
        // Viewファイル呼出し
        // $this->render('index');
        $this->render('/R4/KRSS/KRSS/index', 'KRSS_layout');
    }

}