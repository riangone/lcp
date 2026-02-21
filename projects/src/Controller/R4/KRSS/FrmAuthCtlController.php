<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\KRSS\FrmAuthCtl;

class FrmAuthCtlController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public $FrmAuthCtl = "";
    //　デフォルトで最初に実行される機能
    public function index()
    {

        $this->render('index', 'FrmAuthCtl_layout');
    }
    //部署データリストの値を設定
    public function fncGetBusyo()
    {
        $result = array();
        try {
            $this->FrmAuthCtl = new FrmAuthCtl();
            $result = $this->FrmAuthCtl->fncGetBusyo();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //データリストの値を設定
    public function subSpreadReShow()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $this->FrmAuthCtl = new FrmAuthCtl();
                $result = $this->FrmAuthCtl->fncListSel($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
                $this->fncReturn($tmpJqgrid);

            } else {
                $this->fncReturn($result);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $this->set('result', $result);
        }
    }

    // '**********************************************************************
    // '処 理 名：削除処理
    // '関 数 名：cmdDelete_Click
    // '引    数：
    // '戻 り 値：
    // '処理説明：削除処理
    // '**********************************************************************
    public function cmdDeleteClick()
    {
        $result = array();
        try {
            //JSの参数データ「lineArr」、こちらで取得する
            $syain_no = $_POST['data'];

            //データ削除
            $this->FrmAuthCtl = new FrmAuthCtl();
            $result1 = $this->FrmAuthCtl->fncDeletData($syain_no);
            //戻るエラー処理
            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            //システムエラーの場合、戻る設定
            $result['result'] = FALSE;
            //システムエラーの場合、エラー情報を表示する
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
