<?php
/**
 * 説明：
 *
 *
 * @author YANGYANG
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
use App\Model\PPRM\PPRM800LoginList;
use App\Model\PPRM\PPRM804AuthorityCtlList;

//*******************************************
// * sample controller
//*******************************************
class PPRM800LoginListController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }
    public $result = array();
    private $Session;
    public $PPRM800LoginList;
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $layout = 'PPRM800LoginList_layout';
        $this->render('/PPRM/PPRM800LoginList/index', $layout);
    }


    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 btnViewClick
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ログイン情報を表示する
    //'**********************************************************************
    public function btnViewClick()
    {
        $result = array();
        try {
            if (isset($_POST['request'])) {
                $LvTextUserID = $_POST['request']['LvTextUserID'];
                $LvTextUserNM = $_POST['request']['LvTextUserNM'];
                $LvTextBusyoCD = $_POST['request']['LvTextBusyoCD'];
                $lvTaisyoku = $_POST['request']['lvTaisyoku'];

                $this->PPRM800LoginList = new PPRM800LoginList();
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');
                $this->result = $this->PPRM800LoginList->fncGetSqlHSYAINMST($LvTextUserID, $LvTextUserNM, $LvTextBusyoCD, $lvTaisyoku, $sys_kb);
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

    //'**********************************************************************
    //'処 理 名：ログインテーブル存在チェック
    //'関 数 fncCheckSQL
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

                $this->PPRM800LoginList = new PPRM800LoginList();
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');
                $this->result = $this->PPRM800LoginList->FncCheckSQL($lblUserID, $sys_kb);
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
    //'処 理 名：ログインテーブル削除処理
    //'関 数 fncDeleteLogin
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ログインテーブルを削除する
    //'**********************************************************************
    public function fncDeleteLogin()
    {
        $this->result = array();
        try {
            if (isset($_POST['data'])) {
                $lblUserID = $_POST['data']['lblUserID'];

                //DB接続
                $this->PPRM800LoginList = new PPRM800LoginList();
                $DB_Conn = $this->PPRM800LoginList->Do_conn();
                if (!$DB_Conn['result']) {
                    throw new \Exception($DB_Conn['data']);
                }

                //トランザクション開始
                $this->PPRM800LoginList->Do_transaction();

                $this->PPRM800LoginList = new PPRM800LoginList();
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');
                $this->result = $this->PPRM800LoginList->FncDeleteLOGIN($lblUserID, $sys_kb);
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }

                //コミット
                $this->PPRM800LoginList->Do_commit();

            } else {
                $this->result['result'] = FALSE;
                $this->result['data'] = 'param error';
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->PPRM800LoginList->Do_rollback();
        }
        if (isset($this->PPRM800LoginList->conn_orl)) {
            $this->PPRM800LoginList->Do_close();
            unset($this->PPRM800LoginList->conn_orl);
        }
        $this->fncReturn($this->result);
    }

    // //'**********************************************************************
    // //'処 理 名：部署名取得（関数）
    // //'関 数 FncGetBusyoNM
    // //'引 数 　：strTCD(部署コード)
    // //'戻 り 値：result
    // //'処理説明：値変更時に部署名を取得する
    // //'**********************************************************************
    // public function FncGetBusyoNM($strTCD)
    // {
    // 	try {
    // 		$this->ClsComFncPprm = new ClsComFncPprm();
    // 		$result = $this->ClsComFncPprm->FncGetBusyoMstValue_ppr($strTCD, TRUE);
    // 		if ($result['result']) {
    // 			$result['data'] = $this->ClsComFnc->FncNv($result['data'][0]);
    // 		}

    // 	} catch (Exception $e) {
    // 		$result['result'] = FALSE;
    // 		$result['data'] = $e->getMessage();
    // 	}

    // 	return $result;
    // }

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
            $PPRM804AuthorityCtlList = new PPRM804AuthorityCtlList();
            $result = $PPRM804AuthorityCtlList->FncGetAllBusyoNM();
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}
