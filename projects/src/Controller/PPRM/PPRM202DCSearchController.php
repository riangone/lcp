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
 * * ------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM202DCSearch;
use App\Model\PPRM\Component\ClsProc;
use App\Model\PPRM\Component\ClsComFncPprm;

class PPRM202DCSearchController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $Session;
    // public $ClsComFnc;
    public $ClsComFncPprm;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM202DCSearch_layout';
        $this->render('/PPRM/PPRM202DCSearch/index', $layout);
    }

    //'**********************************************************************
    //'処 理 名：権限取得＆自動設定
    //'関 数 pprm202DCSearchLoad
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：権限設定（初期値）
    //'**********************************************************************
    public function pprm202DCSearchLoad()
    {
        $ClsProc = new ClsProc();
        $this->Session = $this->request->getSession();
        $btnEnabled = $ClsProc->SubSetEnabled_OnPageLoad($this->Session->read('Sys_KB'), "PPRM202DCSearch", $this->Session->read('login_user'));
        $result['result'] = true;
        $result['data'] = $btnEnabled;
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：店舗名取得（関数）
    //'関 数 名：FncGetBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に店舗名を取得する
    //'**********************************************************************
    // public function FncGetBusyoNM()
    // {
    // 	$result = array(
    // 		'result' => 'false',
    // 		'data' => 'ErrorInfo'
    // 	);
    // 	$postData = $_POST["data"]["request"];
    // 	try {
    // 		$this->ClsComFncPprm = new ClsComFncPprm();
    // 		$result = $this->ClsComFncPprm->FncGetBusyoMstValue_ppr($postData["txtTenpoCD"], TRUE);
    // 		if ($result['result']) {
    // 			$result['data'] = $this->ClsComFnc->FncNv($result['data'][0]);
    // 		}
    // 	} catch (Exception $e) {
    // 		$result['result'] = FALSE;
    // 		$result['data'] = $e->getMessage();
    // 	}
    // echo json_encode($result);
    // }

    //20170905 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部店舗名取得（関数）
    //'関 数 fncGetALLBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に店舗名を取得する
    //'**********************************************************************
    public function fncGetALLBusyoNM()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $this->ClsComFncPprm = new ClsComFncPprm();
            $result = $this->ClsComFncPprm->FncGetALLBusyoMstPPR();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }
    //20170905 ZHANGXIAOLEI INS E

    //'*******************************************************************
    //'処 理 名：日締データを検索
    //'関 数 名：fncSelectHJM
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：対象が事務の場合（日締データ）
    //'**********************************************************************
    public function fncSelectHJM()
    {
        $result = array();
        $postData = $_POST["request"];

        try {
            $this->Session = $this->request->getSession();
            $postData['Sys_KB'] = $this->Session->read('Sys_KB');
            $postData['login_user'] = $this->Session->read('login_user');
            $postData['BusyoCd'] = $this->Session->read('BusyoCd');
            $PPRM202DCSearch = new PPRM202DCSearch();
            $result = $PPRM202DCSearch->fncSelectHJM($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];

            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
            $result = $tmpJqgrid;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：売上データを検索
    //'関 数 名：fncSelectURI
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：対象が整備の場合（売上データ）
    //'**********************************************************************
    public function fncSelectURI()
    {
        $result = array();
        $postData = $_POST["request"];
        try {
            $this->Session = $this->request->getSession();
            $postData['Sys_KB'] = $this->Session->read('Sys_KB');
            $postData['login_user'] = $this->Session->read('login_user');
            $postData['BusyoCd'] = $this->Session->read('BusyoCd');
            $PPRM202DCSearch = new PPRM202DCSearch();
            $result = $PPRM202DCSearch->fncSelectURI($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];

            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
            $result = $tmpJqgrid;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
