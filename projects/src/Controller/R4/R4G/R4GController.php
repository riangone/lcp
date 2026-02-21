<?php

namespace App\Controller\R4\R4G;

//20141028 fan upd.
use App\Controller\AppController;

//*******************************************
// * sample controller
//*******************************************
class R4GController extends AppController
{
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // $this -> layout = 'R4G_layout';
        $r4_name = '車両業務システム';
        $this->set('r4_name', $r4_name);
        // Viewファイル呼出し
        $this->render('/R4/R4G/index', 'R4G_layout');
    }
}
