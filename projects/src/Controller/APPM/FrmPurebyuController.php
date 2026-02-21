<?php
/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\APPM;

use App\Controller\AppController;
//*******************************************
// * sample controller
//*******************************************
class FrmPurebyuController extends AppController
{
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'FrmPurebyu_layout');
    }

}
