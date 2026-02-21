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
use App\Model\PPRM\PPRM803MenuNameMstMnt;
use Cake\Core\Exception\Exception;

//*******************************************
// * sample controller
//*******************************************
class PPRM803MenuNameMstMntController extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFnc');
    }
    public $PPRM803MenuNameMstMnt;
    public $result = array();
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
        $layout = 'PPRM803MenuNameMstMnt_layout';
        $this->render('/PPRM/PPRM803MenuNameMstMnt/index', $layout);
    }

    //'***********************************************************************
    //'処 理 名：プログラムマスタ取得
    //'関 数 fncGetSqlHPROGRAMMST
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：プログラムマスタを取得
    //'***********************************************************************/
    public function fncGetSqlHPROGRAMMST()
    {
        $result = array();
        try {

            if (isset($_POST['request'])) {
                $this->PPRM803MenuNameMstMnt = new PPRM803MenuNameMstMnt();
                $this->Session = $this->request->getSession();
                $sys_kb = $this->Session->read('Sys_KB');
                $this->result = $this->PPRM803MenuNameMstMnt->FncGetSql_HPROGRAMMST($sys_kb);
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

    //'***********************************************************************
    //'処 理 名：プログラムマスタ存在チェック
    //'関 数 fncCheckSQL
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：更新ボタン押下行の存在チェック処理
    //'***********************************************************************/
    public function fncCheckSQL()
    {
        try {
            $lblProNO = $_POST['data']['lblProNO'];

            $this->PPRM803MenuNameMstMnt = new PPRM803MenuNameMstMnt();
            $this->Session = $this->request->getSession();
            $sys_kb = $this->Session->read('Sys_KB');
            $this->result = $this->PPRM803MenuNameMstMnt->FncCheckSQL($lblProNO, $sys_kb);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }
        $this->fncReturn($this->result);
    }

    //'***********************************************************************
    //'処 理 名：プログラムマスタ更新処理
    //'関 数 fncUpdateHPROGRAMMST
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：プログラムマスタを更新する
    //'***********************************************************************/
    public function fncUpdateHPROGRAMMST()
    {
        try {
            $lblProNO = $_POST['data']['lblProNO'];
            $txtProName = $_POST['data']['txtProName'];
            $ddlUserAuthCtlFlg = $_POST['data']['ddlUserAuthCtlFlg'];

            //DB接続
            $this->PPRM803MenuNameMstMnt = new PPRM803MenuNameMstMnt();
            $DB_Conn = $this->PPRM803MenuNameMstMnt->Do_conn();
            if (!$DB_Conn['result']) {
                throw new \Exception($DB_Conn['data']);
            }

            //トランザクション開始
            $this->PPRM803MenuNameMstMnt->Do_transaction();

            $this->PPRM803MenuNameMstMnt = new PPRM803MenuNameMstMnt();
            $this->Session = $this->request->getSession();
            $session['Sys_KB'] = $this->Session->read('Sys_KB');
            $session['login_user'] = $this->Session->read('login_user');
            $session['MachineNM'] = $this->request->clientIp();
            $this->result = $this->PPRM803MenuNameMstMnt->FncUpdate_HPROGRAMMST($lblProNO, $txtProName, $ddlUserAuthCtlFlg, $session);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            //コミット
            $this->PPRM803MenuNameMstMnt->Do_commit();

        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->PPRM803MenuNameMstMnt->Do_rollback();
        }
        if (isset($this->PPRM803MenuNameMstMnt->conn_orl)) {
            $this->PPRM803MenuNameMstMnt->Do_close();
            unset($this->PPRM803MenuNameMstMnt->conn_orl);
        }
        $this->fncReturn($this->result);
    }

    // ==========
    // = イベント end =
    // ==========

}
