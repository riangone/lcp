<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmTeisyu;

class FrmTeisyuController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    var $errorFlag = FALSE;
    public $FrmTeisyu;

    public function index()
    {
        $this->render('index', 'FrmTeisyu_layout');
    }

    public function fncFromSyainSelect()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
            }

            if ($postData != "load") {
                $this->FrmTeisyu = new FrmTeisyu();
                $result = $this->FrmTeisyu->fncFromSyainSelect($postData["Busyo_CD"]);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                    $page = $tmpJqgridShow['page'];
                    $totalPage = $tmpJqgridShow['totalPage'];
                    $tmpCount = (int) $tmpJqgridShow['count'];

                    $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result["data"], $totalPage, $page, $tmpCount);

                    unset($_POST['request']);
                    $result = $tmpJqgrid;
                }
            } else {
                $result['result'] = true;
                $result['data'] = "load";

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    public function frmHTEISYUDeleteRow()
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
                $this->FrmTeisyu = new FrmTeisyu();
                $result = $this->FrmTeisyu->frmHTEISYUDeleteRow($postData['SYAIN_NO']);

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

    public function fncGetBusyoMstValue()
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
                // $this -> FrmTeisyu = new FrmTeisyu();
                $result = $this->ClsComFnc->FncGetBusyoMstValue($postData['Busyo_CD'], $this->ClsComFnc->GS_BUSYOMST);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $result['result'] = TRUE;
                    $result['data'] = $this->ClsComFnc->GS_BUSYOMST;
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteUpdataTeisyuMst()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmTeisyuDealFinally"
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
                $this->FrmTeisyu = new FrmTeisyu();
                $result = $this->FrmTeisyu->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //定収ファイルに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmTeisyu->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //定収ファイルデータを削除する
                $result = $this->FrmTeisyu->fncDeleteTeisyuMst($postData['Busyo_CD']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //定収ファイルに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['inputData']); $i++) {
                        //定収ファイルに追加する
                        $result = $this->FrmTeisyu->fncInsertTeisyu($postData['inputData'][$i]);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmTeisyu->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmTeisyuDealFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmTeisyu->Do_rollback();
        }
        //DB接続解除
        $this->FrmTeisyu->Do_close();
    }

}