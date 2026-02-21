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
use App\Model\PPRM\PPRM804AuthorityCtlList;

class PPRM804AuthorityCtlListController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $Session;
    public $result;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM804AuthorityCtlList_layout';
        $this->render('/PPRM/PPRM804AuthorityCtlList/index', $layout);
    }

    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 名：btnViewClick
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：ログイン情報を表示する
    //'**********************************************************************

    public function btnViewClick()
    {
        $result = array();
        try {

            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
                $sysDate = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");
                $PPRM804AuthorityCtlList = new PPRM804AuthorityCtlList();
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');
                $this->result = $PPRM804AuthorityCtlList->FncGetSql_HSYAINMST($postData, $sysDate, $sys_kb);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);
                $page = $tmpJqgrid['page'];
                $totalPage = $tmpJqgrid['totalPage'];
                $tmpCount = $tmpJqgrid['count'];
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

    //20170908 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部店舗名取得（関数）
    //'関 数 名：fncGetBusyoNM
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
            $PPRM804AuthorityCtlList = new PPRM804AuthorityCtlList();
            $result = $PPRM804AuthorityCtlList->FncGetAllBusyoNM();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //20170908 ZHANGXIAOLEI INS E

    //'**********************************************************************
    //'処 理 名：ログインテーブル存在チェック
    //'関 数 名：fncCheckSQL
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ユーザID取得
    //'**********************************************************************
    public function fncCheckSQL()
    {
        $this->result = array();
        try {
            if (isset($_POST['data'])) {
                $lblUserID = $_POST['data']['lblUserID'];

                $PPRM804AuthorityCtlList = new PPRM804AuthorityCtlList();
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');
                $this->result = $PPRM804AuthorityCtlList->FncCheckSQL($lblUserID, $sys_kb);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }

            } else {
                $this->result['result'] = FALSE;
                $this->result['data'] = 'param error';
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：削除キャンセル
    //'関 数 名：btnDeleteClick
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：社員別権限管理データ削除
    //'**********************************************************************
    public function btnDeleteClick()
    {
        $result = array();
        $postData = $_POST["data"];
        try {
            $PPRM804AuthorityCtlList = new PPRM804AuthorityCtlList();
            $DB_Conn = $PPRM804AuthorityCtlList->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $PPRM804AuthorityCtlList->Do_transaction();
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $result = $PPRM804AuthorityCtlList->FncDelete($postData, $sys_kb);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $PPRM804AuthorityCtlList->Do_commit();

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $PPRM804AuthorityCtlList->Do_rollback();
        }
        if (isset($PPRM804AuthorityCtlList->conn_orl)) {
            $PPRM804AuthorityCtlList->Do_close();
            unset($PPRM804AuthorityCtlList->conn_orl);
        }
        $this->fncReturn($result);
    }

}
