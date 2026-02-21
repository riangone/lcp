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
use App\Model\PPRM\PPRM801LoginEntry;

//*******************************************
// * sample controller
//*******************************************
class PPRM801LoginEntryController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    public $PPRM801LoginEntry;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }
    public $result = array();
    private $Session;
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
        $layout = 'PPRM801LoginEntry_layout';
        $this->render('/PPRM/PPRM801LoginEntry/index', $layout);
    }

    //'***********************************************************************
    //'処 理 名：権限ドロップダウンリスト表示
    //'関 数 名：subComboSet
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：権限ドロップダウンリスト表示
    //'***********************************************************************
    public function subComboSet()
    {
        $this->result = array();
        $this->result['result'] = FALSE;
        $this->result['data'] = '';
        try {
            $this->PPRM801LoginEntry = new PPRM801LoginEntry();
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $this->result = $this->PPRM801LoginEntry->subComboSet($sys_kb);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            $this->result['result'] = true;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }

    //'***********************************************************************
    //'処 理 名：ﾛｸﾞｲﾝ情報データを取得する
    //'関 数 名：subInfoSet
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：ﾛｸﾞｲﾝ情報データを取得する
    //'***********************************************************************
    public function subInfoSet()
    {
        $this->result = array();
        try {
            if (isset($_POST['data'])) {
                $LvTextUserID = $_POST['data']['LvTextUserID'];

                $this->PPRM801LoginEntry = new PPRM801LoginEntry();
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');
                $this->result = $this->PPRM801LoginEntry->subInfoSet($LvTextUserID, $sys_kb);
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
    //'処 理 名：ログイン情報を更新する
    //'関 数 fncUpdateConfirm
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    public function fncUpdateConfirm()
    {
        $this->result = array();
        try {
            if (isset($_POST['data'])) {
                $LvTextUserID = $_POST['data']['LvTextUserID'];
                $LvTextPass = $_POST['data']['LvTextPass'];
                $ddlRights = $_POST['data']['ddlRights'];

                //DB接続
                $this->PPRM801LoginEntry = new PPRM801LoginEntry();
                $DB_Conn = $this->PPRM801LoginEntry->Do_conn();
                if (!$DB_Conn['result']) {
                    throw new \Exception($DB_Conn['data']);
                }

                //トランザクション開始
                $this->PPRM801LoginEntry->Do_transaction();

                $result1 = $this->FncDelete_Login($LvTextUserID);
                if (!$result1['result']) {
                    throw new \Exception($result1['data']);
                }

                if ($result1['result'] == FALSE) {
                    return;
                }

                $result2 = $this->FncInsert_Login($LvTextUserID, $LvTextPass, $ddlRights);
                if (!$result2['result']) {
                    throw new \Exception($result2['data']);
                }

                if ($result2['result'] == FALSE) {
                    return;
                }

                //コミット
                $this->PPRM801LoginEntry->Do_commit();

                $this->result['result'] = TRUE;

            } else {
                $this->result['result'] = FALSE;
                $this->result['data'] = 'param error';
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->PPRM801LoginEntry->Do_rollback();
        }
        if (isset($this->PPRM801LoginEntry->conn_orl)) {
            $this->PPRM801LoginEntry->Do_close();
            unset($this->PPRM801LoginEntry->conn_orl);
        }
        $this->fncReturn($this->result);
    }

    //'**********************************************************************
    //'処 理 名：ログイン情報の削除処理
    //'関 数 名：FncDelete_Login
    //'引 数 １：$LvTextUserID
    //'戻 り 値：result
    //'処理説明：ログイン情報の削除処理
    //'**********************************************************************/
    public function FncDelete_Login($LvTextUserID)
    {
        try {
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $result = $this->PPRM801LoginEntry->fncDeleteLogin($LvTextUserID, $sys_kb);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：ログイン情報の追加処理
    //'関 数 名：FncInsert_Login
    //'引 数 １：$LvTextUserID, $LvTextPass, $ddlRights
    //'戻 り 値：result
    //'処理説明：ログイン情報の追加処理
    //'**********************************************************************/
    public function FncInsert_Login($LvTextUserID, $LvTextPass, $ddlRights)
    {
        try {
            $this->Session = $this->request->getSession();
            $session['Sys_KB'] = $this->Session->read('Sys_KB');
            $session['login_user'] = $this->Session->read('login_user');
            $session['MachineNM'] = $this->request->clientIp();
            $result = $this->PPRM801LoginEntry->fncInsertLogin($LvTextUserID, $LvTextPass, $ddlRights, $session);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    // ==========
    // = メソッド end =
    // ==========
}
