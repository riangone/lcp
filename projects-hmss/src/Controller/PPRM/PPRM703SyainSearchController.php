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
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM703SyainSearch;

//*******************************************
// * sample controller
//*******************************************
class PPRM703SyainSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM703SyainSearch_layout';
        $this->render('/PPRM/PPRM703SyainSearch/index', $layout);
    }

    // //'**********************************************************************
    // //'処 理 名：部署名取得
    // //'関 数 名：FncGetBusyoNM
    // //'引    数：無し
    // //'戻 り 値：無し
    // //'処理説明：
    // //'**********************************************************************

    // public function FncGetBusyoNM()
    // {
    // 	$result = array();
    // 	$postData = $_POST["data"]["request"];
    // 	try {
    // 		$PPRM703SyainSearch = new PPRM703SyainSearch();
    // 		$result = $PPRM703SyainSearch->FncGetBusyoNM($postData);

    // 		$tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
    // 		$page = $tmpJqgridShow['page'];
    // 		$totalPage = $tmpJqgridShow['totalPage'];
    // 		$tmpCount = $tmpJqgridShow['count'];

    // 		$tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
    // 		$result = $tmpJqgrid;
    // 	} catch (Exception $e) {
    // 		$result['result'] = FALSE;
    // 		$result['data'] = $e->getMessage();
    // 	}
    // 	echo json_encode($result);
    // }

    //20170908 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部部署名取得（関数）
    //'関 数 名：FncGetALLBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に部署名を取得する
    //'**********************************************************************
    public function fncGetALLBusyoNM()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $PPRM703SyainSearch = new PPRM703SyainSearch();
            $result = $PPRM703SyainSearch->FncGetALLBusyoNM();
            if (!$result['result']) {
                throw new \Exception($this->result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20170908 ZHANGXIAOLEI INS E

    // //'**********************************************************************
    // //'処 理 名：表示ボタンクリックのイベント
    // //'関 数 名：btnHyoujiClick
    // //'引 数 １：(I)sender イベントソース
    // //'引 数 ２：(I)e      イベントパラメータ
    // //'戻 り 値：なし
    // //'処理説明：表示ボタンの処理
    // //'**********************************************************************
    public function btnHyoujiClick()
    {

        $result = array();
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $PPRM703SyainSearch = new PPRM703SyainSearch();
                $this->result = $PPRM703SyainSearch->FncGetSqlSYAYIN($postData);

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
