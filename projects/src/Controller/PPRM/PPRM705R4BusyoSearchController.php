<?php
/**
 * 説明：
 *
 *
 * @author CIYUANCHEN
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM705R4BusyoSearch;

//*******************************************
// * sample controller
//*******************************************
class PPRM705R4BusyoSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    // public $ClsComFnc;
    public $result;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM705R4BusyoSearch_layout';
        $this->render('/PPRM/PPRM705R4BusyoSearch/index', $layout);
    }
    // //'**********************************************************************
    // //'処 理 名：表示ボタンクリック
    // //'関 数 名：btnView_Click
    // //'引 数 １：(I)sender イベントソース
    // //'引 数 ２：(I)e      イベントパラメータ
    // //'戻 り 値：なし
    // //'処理説明：画面項目の表示,取得データを部署グリッドにバインドする
    // //'**********************************************************************
    public function btnViewClick()
    {

        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST["request"];
                $PPRM705R4BusyoSearch = new PPRM705R4BusyoSearch();
                $this->result = $PPRM705R4BusyoSearch->getDeployDataSQL($postData);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }

                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);
                $this->result = $tmpJqgrid;
            } else {
                $this->result = $result;
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);

    }

}
