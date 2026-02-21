<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class HMTVEController extends AppController
{
    const SYS_KB = '2';
    const STYLE_ID = '001';
    public $autoLayout = TRUE;
    // public $autoRender = false;
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'HMTVE_layout';
        $app_name = "データ集計システム";
        $this->set('app_name', $app_name);
        // Viewファイル呼出し
        $this->render('/HMTVE/HMTVE/index', $layout);
    }
}