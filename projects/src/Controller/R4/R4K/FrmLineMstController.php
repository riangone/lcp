<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmLineMst;

class FrmLineMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;

    var $errorFlag = FALSE;
    public$FrmLineMst;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        $this->render('index', 'FrmLineMst_layout');
    }

    // **********************************************************************
    // 処 理 名：フォームロード
    // 関 数 名：frmLineMstSelect
    // 引    数：	無し
    // 戻 り 値：	配列.$result
    // 処理説明：	画面読み込み処理
    // **********************************************************************
    public function fncLineMstSelect()
    {
        try {
            $this->FrmLineMst = new FrmLineMst();
            $result = $this->FrmLineMst->fncSelectLineMst();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    // **********************************************************************
    // 処 理 名：更新ボタンクリック
    // 関 数 名：fncDeleteUpdateLineMst
    // 引    数：	$入力データ
    // 戻 り 値：	配列.$result
    // 処理説明：ラインマスタのデータを作成する
    // **********************************************************************
    public function fncDeleteUpdateLineMst()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmLineMstDealfinally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'ErrorInfo'
                );
            } else {
                $this->FrmLineMst = new FrmLineMst();
                $result = $this->FrmLineMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $this->FrmLineMst->Do_transaction();

                $this->errorFlag = TRUE;

                //ラインマスタのデータを削除する
                $result = $this->FrmLineMst->fncLineMstDelete();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    for ($i = 0; $i < count($postData); $i++) {
                        if ($postData[$i]['LINE_NO'] != "") {
                            $result = $this->FrmLineMst->fncLineMstInsert($postData[$i]);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            } else {
                                $result['result'] = TRUE;
                                $result['data'] = "";
                            }
                        }
                    }
                }

                $this->FrmLineMst->Do_commit();
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    // **********************************************************************
    // 処 理 名：Finally処理
    // 関 数 名：frmLineMstDealfinally
    // 引    数：	無し
    // 戻 り 値：	無し
    // 処理説明：Finally処理
    // **********************************************************************
    public function frmLineMstDealfinally()
    {
        if ($this->errorFlag) {
            $this->FrmLineMst->Do_rollback();
        }

        $this->FrmLineMst->Do_close();
    }

    // **********************************************************************
    // 処 理 名：選択行を削除処理
    // 関 数 名：frmDeleteSelectRow
    // 引    数：	$選択行データ
    // 戻 り 値：	配列.$result
    // 処理説明：選択行データを削除処理
    // **********************************************************************
    public function frmDeleteSelectRow()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            if ($postData == "") {
                $result['result'] = FALSE;
                $result['data'] = "ErrorInfo";
            } else {
                $this->FrmLineMst = new FrmLineMst();
                $result = $this->FrmLineMst->fncDeleteRow($postData['LINE_NO']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $result['result'] = TRUE;
                    $result['data'] = "";
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}