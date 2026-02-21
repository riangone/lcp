<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmGENKAIMAKE;

class FrmGENKAIMAKEController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmGENKAIMAKE = "";
    public $blnTranFlg = "";
    public $Do_conn = "";
    public $glb_result = "";
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmGENKAIMAKE_layout');
    }

    public function frmGenkaiMakeLoad()
    {
        $result = [];
        try {
            $this->FrmGENKAIMAKE = new FrmGENKAIMAKE();
            $result = $this->FrmGENKAIMAKE->fncSelect();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function cmdActClick()
    {
        $blnTranFlg = FALSE;
        $cboYM = "";
        $result = [];
        $label = TRUE;
        try {
            if (isset($_POST['data'])) {
                $cboYM = $_POST['data'];
            }
            if ($cboYM == '') {
                $result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $this->FrmGENKAIMAKE = new FrmGENKAIMAKE();
                //存在ﾁｪｯｸ
                $result = $this->FrmGENKAIMAKE->fncHscUriExistCheck($cboYM);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if ($result['result'] == TRUE) {
                    if (count((array) $result['data']) == 0) {
                        $result['result'] = TRUE;
                        $result['data'] = "I0001";
                    } else {
                        //トランザクション開始
                        $this->Do_conn = $this->FrmGENKAIMAKE->Do_conn();
                        if (!$this->Do_conn['result']) {
                            throw new \Exception($this->Do_conn['data']);
                        }

                        $this->FrmGENKAIMAKE->Do_transaction();
                        $blnTranFlg = TRUE;
                        $label = FALSE;
                        //当月限界利益データを削除する
                        $result = $this->FrmGENKAIMAKE->fncDeleteGenri($cboYM);
                        if ($result['result'] == FALSE) {
                            throw new \Exception($result['data']);
                        }

                        //条件変更履歴データに同一注文書番号が存在しないデータをINSERTする
                        $result = $this->FrmGENKAIMAKE->fncInsertNoExist($cboYM);
                        if ($result['result'] == FALSE) {
                            throw new \Exception($result['data']);
                        }
                        //??
                        //条件変更履歴データに同一注文書番号、売上部署、売上セールが存在しているデータをINSERTする
                        $result = $this->FrmGENKAIMAKE->fncInsertUriageSagaku($cboYM);
                        if ($result['result'] == FALSE) {
                            throw new \Exception($result['data']);
                        }

                        //条件変更履歴データに同一注文書番号が存在しているが、売上部署又は売上セールスが一致しないデータをINSERTする
                        //①条件変更履歴データよりマイナスデータを作成する
                        $result = $this->FrmGENKAIMAKE->fncInsertAkaJyohen($cboYM);
                        if ($result['result'] == FALSE) {
                            throw new \Exception($result['data']);
                        }
                        //②売上データより限界利益データを作成する

                        $result = $this->FrmGENKAIMAKE->fncInsertForExist($cboYM);
                        if ($result['result'] == FALSE) {
                            throw new \Exception($result['data']);
                        }

                        $this->FrmGENKAIMAKE->Do_commit();
                        $blnTranFlg = FALSE;
                        $result['result'] = TRUE;
                        $result['data'] = "I0005";
                    }
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTranFlg == TRUE) {
            $this->FrmGENKAIMAKE->Do_rollback();
        }
        if ($label == FALSE) {
            $this->FrmGENKAIMAKE->Do_close();
        }
        $this->fncReturn($result);

    }

}