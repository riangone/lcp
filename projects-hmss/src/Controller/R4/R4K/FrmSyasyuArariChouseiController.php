<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmSyasyuArariChousei;

class FrmSyasyuArariChouseiController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $result;
    public $filePathName;
    public $Do_conn;
    public $blnTranFlg;
    public $FrmSyasyuArariChousei;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
        $this->loadComponent('ClsCreateCsv');
    }
    public function index()
    {
        $this->render('index', 'FrmSyasyuArariChousei_layout');
    }

    /*
           '**********************************************************************
           '処理概要：フォームロード
           '**********************************************************************
           */
    public function formLoad()
    {
        try {
            //モデルの仕様するクラスを定義
            $objModel_FrmSyasyuArariChousei = new FrmSyasyuArariChousei();
            //モデルクラスのselect処理を呼出し
            $this->result = $objModel_FrmSyasyuArariChousei->frmSyasyuArariChousei_Load_select();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }

    /*
           '**********************************************************************
           '処 理 名：コンボボックス値を設定
           '関 数 名：subComboSet2
           '引    数：しない
           '戻 り 値：String
           '処理説明：名称マスタから保険区分を取得し、コンボボックスに設定する
           '**********************************************************************
           */
    public function subComboSet2()
    {
        try {
            //モデルの仕様するクラスを定義
            $objModel_FrmSyasyuArariChousei = new FrmSyasyuArariChousei();
            //モデルクラスのselect処理を呼出し
            $this->result = $objModel_FrmSyasyuArariChousei->subComboSet2_select();
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }

    /*
           '**********************************************************************
           '処 理 名：抽出する
           '関 数 名：fncArariSelect
           '引    数：無し
           '戻 り 値：ＳＱＬ文
           '処理説明：抽出する
           '**********************************************************************
           */
    public function fncArariSelect()
    {
        try {
            $tmpCboYM = str_replace("/", "", $_POST['data']['cboYM']);
            $tmpTxtItemNO = $_POST['data']['txtItemNo'];
            //モデルの仕様するクラスを定義
            $objModel_FrmSyasyuArariChousei = new FrmSyasyuArariChousei();
            //モデルクラスのselect処理を呼出し
            $this->result = $objModel_FrmSyasyuArariChousei->fncArariSelect($tmpCboYM, $tmpTxtItemNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }

    /*
           '**********************************************************************
           '処 理 名：追加と削除する
           '関 数 名：fncDeleteInsertArari
           '引    数：無し
           '戻 り 値：ＳＱＬ文
           '処理説明：DBに追加と削除する
           '**********************************************************************
           */
    public function fncDeleteInsertArari()
    {
        try {

            $tmpCboYM = str_replace("/", "", $_POST['data']['cboYM']);
            $tmpTxtItemNO = $_POST['data']['txtItemNo'];
            $tmpTxtUriage = $_POST['data']['txtUriage'];
            $tmpTxtArari = $_POST['data']['txtArari'];
            if ($tmpTxtUriage == "") {
                $tmpTxtUriage = 0;
            }
            if ($tmpTxtArari == "") {
                $tmpTxtArari = 0;
            }
            //モデルの仕様するクラスを定義
            $this->FrmSyasyuArariChousei = new FrmSyasyuArariChousei();
            $this->Do_conn = $this->FrmSyasyuArariChousei->Do_conn();
            if (!$this->Do_conn['result']) {
                throw new \Exception($this->Do_conn['data']);
            }
            //トランザクション開始
            $this->FrmSyasyuArariChousei->Do_transaction();
            //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
            $this->blnTranFlg = True;
            //モデルクラスのdelete処理を呼出し
            $this->result = $this->FrmSyasyuArariChousei->fncDeleteArari($tmpCboYM, $tmpTxtItemNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }

            //モデルクラスのinsert処理を呼出し
            $this->result = $this->FrmSyasyuArariChousei->fncInsertArari($tmpCboYM, $tmpTxtItemNO, $tmpTxtUriage, $tmpTxtArari);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            $this->result['data'] = "success";

            //コミット
            $this->FrmSyasyuArariChousei->Do_commit();
            //ﾄﾗﾝｻﾞｸｼｮﾝ終了
            $this->blnTranFlg = False;
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }
        //finally
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmSyasyuArariChousei->Do_rollback();
        }
        //DB接続解除
        $this->FrmSyasyuArariChousei->Do_close();

        $this->fncReturn($this->result);

    }

    /*
           '**********************************************************************
           '処 理 名：削除する
           '関 数 名：fncDeleteArari
           '引    数：無し
           '戻 り 値：ＳＱＬ文
           '処理説明：DBから削除する
           '**********************************************************************
           */
    public function fncDeleteArari()
    {
        try {
            $tmpCboYM = str_replace("/", "", $_POST['data']['cboYM']);
            $tmpTxtItemNO = $_POST['data']['txtItemNo'];
            //モデルの仕様するクラスを定義
            $objModel_FrmSyasyuArariChousei = new FrmSyasyuArariChousei();
            //モデルクラスのselect処理を呼出し
            $this->result = $objModel_FrmSyasyuArariChousei->fncDeleteArari_delete($tmpCboYM, $tmpTxtItemNO);
            if (!$this->result['result']) {
                throw new \Exception($this->result['data'], 1);
            }
            $this->result['data'] = 'success';
        } catch (\Exception $ex) {
            $this->result['result'] = FALSE;
            $this->result['data'] = $ex->getMessage();
        }

        $this->fncReturn($this->result);
    }

}