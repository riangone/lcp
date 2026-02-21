<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmUriBusyoCnvEdit;

class FrmUriBusyoCnvEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmUriBusyoCnvEdit = "";
    public $Do_Excute = [];
    public $Do_conn;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmUriBusyoCnvEdit_layout');
    }

    //画面のデータの値を取得
    public function fncDataSel()
    {
        $result = array();
        $strCMNNO = "";
        try {
            $strCMNNO = $_POST['data'];
            $this->FrmUriBusyoCnvEdit = new FrmUriBusyoCnvEdit();
            $result = $this->FrmUriBusyoCnvEdit->fncDataSel($strCMNNO);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //**********************************************************************
    //処 理 名：注文書番号存在チェック
    //関 数 名：fncCheckCMNNO
    //引    数：
    //戻 り 値：0:正常 1:売上データ存在しない   2:変換テーブル違い
    //処理説明：注文書番号存在チェック
    //**********************************************************************
    public function fncCheckCMNNO()
    {
        $result = array();
        $CMNNO = "";
        try {
            if (isset($_POST['data'])) {
                $CMNNO = $_POST['data'];
            }
            if ($CMNNO == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmUriBusyoCnvEdit = new FrmUriBusyoCnvEdit();
                $result = $this->FrmUriBusyoCnvEdit->fncCheckCMNNO1($CMNNO);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['row'] == 0) {

                    $result['data'] = 1;
                } else {
                    $result = $this->FrmUriBusyoCnvEdit->fncCheckCMNNO2($CMNNO);
                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }
                    if ($result['row'] > 0) {
                        $result['data'] = 2;
                    } else {
                        $result['data'] = 0;
                    }
                }
                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    //部署名取得
    public function fncGetBusyoMstValue()
    {
        $result = array();
        $txtCMNNO2 = "";
        $txtCMNNO2 = $_POST['data'];
        $result = $this->ClsComFnc->FncGetBusyoMstValue($txtCMNNO2, $this->ClsComFnc->GS_BUSYOMST);
        if ($result['result'] == TRUE) {
            $result['data'] = $this->ClsComFnc->GS_BUSYOMST;
        }

        $this->fncReturn($result);
    }

    //売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙから削除を行う
    //売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙに追加処理を行う
    public function fncDeleteInsertHuri()
    {
        $result = array();
        $txtCMNNO = "";
        $txtCMNNO2 = "";
        $lblCreateDate = "";
        $blnTranFlg = FALSE;
        try {
            $txtCMNNO = $_POST['data']['txtCMNNO'];
            $txtCMNNO2 = $_POST['data']['txtCMNNO2'];
            $lblCreateDate = $_POST['data']['lblCreateDate'];
            $this->FrmUriBusyoCnvEdit = new FrmUriBusyoCnvEdit();
            //トランザクション処理
            $this->Do_conn = $this->FrmUriBusyoCnvEdit->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            $this->FrmUriBusyoCnvEdit->Do_transaction();
            $blnTranFlg = TRUE;
            //売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙから削除を行う
            $this->Do_Excute = $this->FrmUriBusyoCnvEdit->fncDeleteHuri($txtCMNNO);
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data']);
            }
            //売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙに追加処理を行う
            if ($this->Do_Excute['result']) {
                $this->Do_Excute = $this->FrmUriBusyoCnvEdit->fncInsertHuri($txtCMNNO, $txtCMNNO2, $lblCreateDate);
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data']);
                }
            }
            //コミット
            $this->FrmUriBusyoCnvEdit->Do_commit();
            $blnTranFlg = FALSE;
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTranFlg == TRUE) {
            $this->FrmUriBusyoCnvEdit->Do_rollback();
        }
        $this->FrmUriBusyoCnvEdit->Do_close();
        $this->fncReturn($result);
    }

}
