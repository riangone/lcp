<?php
namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\R4G\FrmPrintTanto;

class FrmPrintTantoController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    public function initialize(): void
    {
        parent::initialize();
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setClassName('Ajax');
        }
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
        $this->loadComponent('ClsDownLoad');
        $this->loadComponent('ClsFileObserver');
    }

    protected $FrmPrintTanto = "";
    protected $postData = "";
    protected $result = "";
    protected $Do_Excute = "";
    protected $DB_Conn = "";

    public function index()
    {
        $this->render('index', 'FrmPrintTanto_layout');
    }

    public function fncPrintTantoSelect()
    {
        // ajax呼出処理チェック
        // 関数を直接呼び出してテストする場合はこの判定をコメント
        try {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $this->FrmPrintTanto = new FrmPrintTanto();
                // 処理の呼出
                $this->result = $this->FrmPrintTanto->select_data();
                if (!$this->result['result']) {
                    throw new \Exception($this->result['data']);
                }
            } else {
                // エラー処理
                $this->result = array(
                    'result' => FALSE,
                    'data' => 'no ajax request'
                );
            }
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
        }

        $this->fncReturn($this->result);
    }

    public function fncDeleteUpdataPrintTanto()
    {
        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );
        $this->result = array(
            'result' => FALSE,
            'data' => 'param error'
        );
        try {
            if (isset($_POST['data']['request'])) {
                $this->postData = $_POST['data']['request'];
            }
            $this->FrmPrintTanto = new FrmPrintTanto();
            $this->DB_Conn = $this->FrmPrintTanto->Do_conn();

            //$this -> FrmPrintTanto -> GS_LOGINUSER['strUserID'] = $this -> Session -> read('login_user');
            if (!$this->DB_Conn['result']) {
                throw new \Exception($this->DB_Conn['data']);
            }
            $this->FrmPrintTanto->Do_transaction();

            $this->Do_Excute = $this->FrmPrintTanto->delete_data();

            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data']);
            }
            $this->Do_Excute = $this->FrmPrintTanto->insert($this->postData);

            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data']);
            }
            $this->FrmPrintTanto->Do_commit();
            $this->result['result'] = TRUE;
        } catch (\Exception $e) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $e->getMessage();
            $this->FrmPrintTanto->Do_rollback();
        }

        // render('ajaxSelect')とした場合はajax_Selectに自動変換される
        $this->fncReturn($this->result);
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    function finally()
    {
        if (isset($this->FrmPrintTanto)) {
            $this->FrmPrintTanto->Do_close();
            unset($this->FrmPrintTanto);
        }
        if (isset($this->postData)) {
            unset($this->postData);
        }
        if (isset($this->result)) {
            unset($this->result);
        }
        if (isset($this->DB_Conn)) {
            unset($this->DB_Conn);
        }
        if (isset($this->Do_Excute)) {
            unset($this->Do_Excute);
        }
    }

}
