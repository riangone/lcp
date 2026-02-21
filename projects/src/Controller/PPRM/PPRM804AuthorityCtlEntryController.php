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
 * YYYYMMDD           #ID                          XXXXXX                           GSDL　　　　　　　　
 * * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\PPRM\PPRM804AuthorityCtlEntry;

class PPRM804AuthorityCtlEntryController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM804AuthorityCtlEntry_layout';
        $this->render('/PPRM/PPRM804AuthorityCtlEntry/index', $layout);
    }

    //'**********************************************************************
    //'処 理 名：部署情報取得
    //'関 数 名：fncBusyoInfoSel
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：
    //'**********************************************************************
    public function fncBusyoInfoSel()
    {
        $result = array();
        $postData = $_POST["request"];

        try {
            $PPRM804AuthorityCtlEntry = new PPRM804AuthorityCtlEntry();
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $result = $PPRM804AuthorityCtlEntry->fncBusyoInfoSel($postData, $sys_kb);

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
    //'処 理 名：新規追加
    //'関 数 btnAddClick
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：
    //'**********************************************************************
    public function btnAddClick()
    {
        $result = array();

        try {
            $PPRM804AuthorityCtlEntry = new PPRM804AuthorityCtlEntry();
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $result = $PPRM804AuthorityCtlEntry->btnAdd_click($sys_kb);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：社員別権限管理マスタの登録情報を削除する
    //'関 数 名：fncDeleteSQL
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：
    //'**********************************************************************
    public function fncDeleteSQL()
    {
        $result = array();
        $postData = $_POST["data"]["request"];
        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $PPRM804AuthorityCtlEntry = new PPRM804AuthorityCtlEntry();
            $DB_Conn = $PPRM804AuthorityCtlEntry->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }
            //トランザクション開始
            $PPRM804AuthorityCtlEntry->Do_transaction();

            $result = $PPRM804AuthorityCtlEntry->fncDeleteSQL($postData, $sys_kb);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            //コミット
            $PPRM804AuthorityCtlEntry->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $PPRM804AuthorityCtlEntry->Do_rollback();
        }
        if (isset($PPRM804AuthorityCtlEntry->conn_orl)) {
            $PPRM804AuthorityCtlEntry->Do_close();
            unset($PPRM804AuthorityCtlEntry->conn_orl);
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：部署名取得
    //'関 数 fncGetBusyoNM
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：
    //'**********************************************************************
    public function fncGetBusyoNM()
    {
        $result = array();
        try {
            $PPRM804AuthorityCtlEntry = new PPRM804AuthorityCtlEntry();
            $result = $PPRM804AuthorityCtlEntry->FncGetBusyoNM();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：選択した部署の更新日をセットする
    //'関 数 名：fncMaxCheck
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：
    //'**********************************************************************
    public function fncMaxCheck()
    {
        $result = array();
        $postData = $_POST["data"]["request"];

        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $PPRM804AuthorityCtlEntry = new PPRM804AuthorityCtlEntry();
            $result = $PPRM804AuthorityCtlEntry->fncMaxCheck($postData, $sys_kb);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：メニュー権限管理マスタデータを取得する
    //'関 数 gvRightsSelectedIndexChanged
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：
    //'**********************************************************************
    public function gvRightsSelectedIndexChanged()
    {
        $result = array();
        $postData = $_POST["data"]["request"];

        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $PPRM804AuthorityCtlEntry = new PPRM804AuthorityCtlEntry();
            $result = $PPRM804AuthorityCtlEntry->gvRights_SelectedIndexChanged($postData, $sys_kb);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //'**********************************************************************
    //'処 理 名：登録処理
    //'関 数 btnTourokuClick
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：
    //'**********************************************************************
    public function btnTourokuClick()
    {
        $result = array();
        $postData = $_POST["data"]["request"];
        $deployDataArr = $postData['deployDataArr'];

        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $login_user = $this->Session->read('login_user');
            $MachineNM = $this->request->clientIp();
            $PPRM804AuthorityCtlEntry = new PPRM804AuthorityCtlEntry();
            //DB接続
            $DB_Conn = $PPRM804AuthorityCtlEntry->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $PPRM804AuthorityCtlEntry->Do_transaction();

            $result = $PPRM804AuthorityCtlEntry->fncDeleteSQL($postData, $sys_kb);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            foreach ($deployDataArr as $value) {
                $result = $PPRM804AuthorityCtlEntry->btnTouroku_click($postData, $value, $MachineNM, $sys_kb, $login_user);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }

            //コミット
            $PPRM804AuthorityCtlEntry->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $PPRM804AuthorityCtlEntry->Do_rollback();
        }
        if (isset($PPRM804AuthorityCtlEntry->conn_orl)) {
            $PPRM804AuthorityCtlEntry->Do_close();
            unset($PPRM804AuthorityCtlEntry->conn_orl);
        }
        $this->fncReturn($result);
    }

}
