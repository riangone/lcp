<?php

/**
 * 説明：
 *
 *
 * @author yinhuaiyu
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
use App\Model\PPRM\PPRM100ApproveStateSearch;
use App\Model\PPRM\Component\ClsProc;
use App\Model\PPRM\Component\ClsComFncPprm;

//*******************************************
// * sample controller
//*******************************************
class PPRM100ApproveStateSearchController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $Session;
    public $ClsComFncPprm;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM100ApproveStateSearch_layout';
        $this->render('/PPRM/PPRM100ApproveStateSearch/index', $layout);
    }

    // '**********************************************************************
    // '処 理 名：権限取得＆自動設定
    // '関 数 名：pprm100ApproveStateSearch_load
    // '引 数 　：なし
    // '戻 り 値：なし
    // '処理説明：権限取得＆自動設定（Page_Load時の初期設定用）
    // '**********************************************************************
    public function pprm100ApproveStateSearchLoad()
    {
        try {
            $ClsProc = new ClsProc();
            $this->Session = $this->request->getSession();
            $btnEnabled = $ClsProc->SubSetEnabled_OnPageLoad($this->Session->read('Sys_KB'), "PPRM100ApproveStateSearch", $this->Session->read('login_user'));
            $result['result'] = true;
            $result['data'] = $btnEnabled;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：検索条件に一致する値を取得する
    // '関 数 名：fncSearch1
    // '引 数 　：なし
    // '戻 り 値：なし
    // '処理説明：対象が事務の場合（日締データ）
    // '**********************************************************************
    public function fncSearch1()
    {
        $result = array();
        $postData = $_POST["request"];
        try {
            $PPRM100ApproveStateSearch = new PPRM100ApproveStateSearch();
            $this->Session = $this->request->getSession();
            $postData["request"]['Sys_KB'] = $this->Session->read('Sys_KB');
            $postData["request"]['login_user'] = $this->Session->read('login_user');
            $postData["request"]['BusyoCD'] = $this->Session->read('BusyoCD');
            $result = $PPRM100ApproveStateSearch->fncSelectSearch1($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
            $start = $tmpJqgridShow['start'];
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];

            //20170905 YIN UPD S
            // $tmpJqgrid = $this -> ClsComFnc -> FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount, $start);
            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
            //20170905 YIN UPD E
            $result = $tmpJqgrid;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：検索条件に一致する値を取得する
    //'関 数 名：fncSearch2
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：対象が整備の場合（売上データ）
    //'**********************************************************************
    public function fncSearch2()
    {
        $result = array();
        $postData = $_POST["request"];
        try {
            $PPRM100ApproveStateSearch = new PPRM100ApproveStateSearch();
            $this->Session = $this->request->getSession();
            $postData["request"]['Sys_KB'] = $this->Session->read('Sys_KB');
            $postData["request"]['login_user'] = $this->Session->read('login_user');
            $postData["request"]['BusyoCD'] = $this->Session->read('BusyoCD');

            $result = $PPRM100ApproveStateSearch->fncSelectSearch2($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];

            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);
            $result = $tmpJqgrid;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：店舗名取得（関数）
    // '関 数 名：fncGetBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    // '処理説明：店舗名を取得する
    // '**********************************************************************
    public function fncGetBusyoNM()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST["data"]["request"];
        try {
            $this->ClsComFncPprm = new ClsComFncPprm();
            $result = $this->ClsComFncPprm->FncGetBusyoMstValue_ppr($postData["txtTenpoCD"], TRUE);
            if ($result['result']) {
                $result['data'] = $this->ClsComFnc->FncNv($result['data'][0]);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    // '**********************************************************************
    // '処 理 名：社員名取得（関数）
    // '関 数 名：fncGetSyainNM
    //'引 数 　：なし
    //'戻 り 値：なし
    // '処理説明：社員名を取得する
    // '**********************************************************************
    public function fncGetSyainNM()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        $postData = $_POST["data"]["request"];
        try {
            $this->ClsComFncPprm = new ClsComFncPprm();
            $result = $this->ClsComFncPprm->FncGetSyainMstValue_ppr($postData["txtTenpoCD"]);
            if ($result['result']) {
                $result['data'] = $this->ClsComFnc->FncNv($result['data'][0]['SYAIN_NM']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20170905 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部店舗名,社員名取得（関数）
    //'関 数 fncGetALLBusyoAndSyain
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全部店舗名,社員名取得（関数）
    //'**********************************************************************
    public function fncGetALLBusyoAndSyain()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo'
        );
        try {
            $result['data'] = array(
                'BusyoData' => array(),
                'SyainData' => array()
            );
            $this->ClsComFncPprm = new ClsComFncPprm();
            $selResult = $this->ClsComFncPprm->FncGetALLBusyoMstPPR();
            if (!$selResult['result']) {
                throw new \Exception($selResult['data']);
            }
            $result['data']['BusyoData'] = $selResult['data'];

            $selResult = $this->ClsComFncPprm->FncGetALLSyainMstPPR();
            if (!$selResult['result']) {
                throw new \Exception($selResult['data']);
            }
            $result['data']['SyainData'] = $selResult['data'];
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20170905 ZHANGXIAOLEI INS E

    // '**********************************************************************
    // '処 理 名：初期設定
    // '関 数 名：setInt
    //'引 数 　：なし
    //'戻 り 値：なし
    // '処理説明：リストの初期設定を行う
    // '**********************************************************************
    public function setInt()
    {
        $result = array();
        $postData = $_POST["data"]["request"];
        try {
            $PPRM100ApproveStateSearch = new PPRM100ApproveStateSearch();
            $result = $PPRM100ApproveStateSearch->SetInt($postData);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
