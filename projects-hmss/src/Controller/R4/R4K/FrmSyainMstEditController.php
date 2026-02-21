<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyainMstEdit;

class FrmSyainMstEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $Do_conn;
    public $FrmSyainMstEdit;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsFncLog');
    }
    public $blnTranFlg = false;
    public function index()
    {
        $this->render('index', 'FrmSyainMstEdit_layout');
    }

    public function fncFromSyainSelect()
    {
        try {
            if (!isset($_POST['data']['txtSyainNO'])) {
                $this->fncReturn($this->result);
                return;
            }
            $syainNO = $_POST['data']['txtSyainNO'];
            $this->FrmSyainMstEdit = new FrmSyainMstEdit();
            $this->result = $this->FrmSyainMstEdit->getFormValue($syainNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncGetGridValue()
    {
        try {
            if (!isset($_POST['request'])) {
                $this->fncReturn($this->result);
                return;
            }
            $syainNO = $_POST['request']['txtSyainNO'];
            $this->FrmSyainMstEdit = new FrmSyainMstEdit();
            $this->result = $this->FrmSyainMstEdit->getGridValue($syainNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($this->result['data']);

            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            for ($ii = 0; $ii < count((array) $this->result['data']); $ii++) {
                foreach ((array) $this->result['data'][$ii] as $key => $value) {
                    $this->result['data'][$ii][$key] = trim($this->ClsComFnc->fncNv($value));
                }
            }
            $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($this->result["data"], $totalPage, $page, $tmpCount);
            $this->result = $tmpJqgrid;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncGetBusyoMstValue()
    {
        try {
            $objBusyoMst = $this->ClsComFnc->GS_BUSYOMST;
            $tf = $this->ClsComFnc->FncGetBusyoMstValue(trim($_POST['data']['busyoCD']), $objBusyoMst);
            if ($tf['result']) {
                $this->result['result'] = TRUE;
                $this->result['data'] = $objBusyoMst['intRtnCD'];
            } else {
                throw new \Exception($tf['data']);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncGetSyainNo()
    {
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }
            $syainNO = $_POST['data']['txtSyainNO'];
            $this->FrmSyainMstEdit = new FrmSyainMstEdit();
            $this->result = $this->FrmSyainMstEdit->FncGetSyain_no($syainNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        $this->fncReturn($this->result);
    }

    public function fncUpdate()
    {
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['data'];
            $this->FrmSyainMstEdit = new FrmSyainMstEdit();
            $this->Do_conn = $this->FrmSyainMstEdit->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmSyainMstEdit->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;

            //----------------
            $txtSyainNO = $postData['form']['txtSyainNO'];
            //社員ﾏｽﾀと配属先の削除処理を行う
            //--form--
            $this->result = $this->FrmSyainMstEdit->fncDelete_HSYAINMST($txtSyainNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //--grid--
            $this->result = $this->FrmSyainMstEdit->fncDelete_HHAIZOKU($txtSyainNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }

            //社員ﾏｽﾀと配属先の登録処理を行う
            //--form--
            $this->result = $this->FrmSyainMstEdit->fncInsert_HSYAINMST($postData['form']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //--grid--
            $tmpCnt = 0;
            foreach ($postData['grid'] as $key => $value) {
                if (trim($value['BUSYO_CD']) != "") {

                    $this->result = $this->FrmSyainMstEdit->fncInsert_HHAIZOKU($value, $txtSyainNO, $tmpCnt);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
                $tmpCnt++;
            }

            //----------------

            $this->result['data'] = "success";
            //コミット
            $this->FrmSyainMstEdit->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmSyainMstEdit->Do_rollback();
        }
        //DB接続解除
        $this->FrmSyainMstEdit->Do_close();
        $this->fncReturn($this->result);
    }

    public function fncDelGridData()
    {
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['data'];
            $this->FrmSyainMstEdit = new FrmSyainMstEdit();
            $this->Do_conn = $this->FrmSyainMstEdit->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmSyainMstEdit->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;

            $this->result = $this->FrmSyainMstEdit->FncDelGridData($postData);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            $this->result['data'] = "success";
            //コミット
            $this->FrmSyainMstEdit->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmSyainMstEdit->Do_rollback();
        }
        //DB接続解除
        $this->FrmSyainMstEdit->Do_close();
        $this->fncReturn($this->result);
    }

    public function fncInsert()
    {
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['data'];
            $this->FrmSyainMstEdit = new FrmSyainMstEdit();
            $this->Do_conn = $this->FrmSyainMstEdit->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmSyainMstEdit->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;

            //----------------
            $txtSyainNO = $postData['form']['txtSyainNO'];
            //社員ﾏｽﾀと配属先の登録処理を行う
            //--form--
            $this->result = $this->FrmSyainMstEdit->fncInsert_HSYAINMST($postData['form']);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //--grid--
            $tmpCnt = 0;
            foreach ($postData['grid'] as $value) {
                if (trim($value['BUSYO_CD']) != "") {
                    $this->result = $this->FrmSyainMstEdit->fncInsert_HHAIZOKU($value, $txtSyainNO, $tmpCnt);
                    if (!$this->result['result']) {
                        throw new \Exception($this->result['data']);
                    }
                }
                $tmpCnt++;
            }
            //----------------

            $this->result['data'] = "success";
            //コミット
            $this->FrmSyainMstEdit->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmSyainMstEdit->Do_rollback();
        }
        //DB接続解除
        $this->FrmSyainMstEdit->Do_close();
        $this->fncReturn($this->result);
    }

    public function fncDelete()
    {
        try {
            if (!isset($_POST['data'])) {
                $this->fncReturn($this->result);
                return;
            }
            $postData = $_POST['data'];
            $this->FrmSyainMstEdit = new FrmSyainMstEdit();
            $this->Do_conn = $this->FrmSyainMstEdit->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmSyainMstEdit->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;

            //----------------
            $txtSyainNO = $postData['form']['txtSyainNO'];
            //社員ﾏｽﾀと配属先の削除処理を行う
            //--form--
            $this->result = $this->FrmSyainMstEdit->fncDelete_HSYAINMST($txtSyainNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //--grid--
            $this->result = $this->FrmSyainMstEdit->fncDelete_HHAIZOKU($txtSyainNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data']);
            }
            //----------------

            $this->result['data'] = "success";
            //コミット
            $this->FrmSyainMstEdit->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;

        } catch (\Exception $ex) {
            $this->result['result'] = False;
            $this->result['data'] = $ex->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmSyainMstEdit->Do_rollback();
        }
        //DB接続解除
        $this->FrmSyainMstEdit->Do_close();
        $this->fncReturn($this->result);
    }
}
