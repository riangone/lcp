<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmYosanLineMst;


class FrmYosanLineMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmYosanLineMst;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    var $errorFlag = FALSE;

    public function index()
    {
        $this->render('index', 'FrmYosanLineMst_layout');
    }

    public function fncYosanLineMstSel()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->FrmYosanLineMst = new FrmYosanLineMst();
                $result = $this->FrmYosanLineMst->fncYosanLineMstSel($postData["BUSYO_KB"]);

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
                $result['data'] = "";

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    public function fncHYOSANLINEMSTDeleteRow()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            $this->FrmYosanLineMst = new FrmYosanLineMst();
            $result = $this->FrmYosanLineMst->fncHYOSANLINEMSTDeleteRow($postData['BUSYO_KB'], $postData['LINE_NO']);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $result['result'] = TRUE;
                $result['data'] = "";
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncCheckExist()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            $this->FrmYosanLineMst = new FrmYosanLineMst();
            $result = $this->FrmYosanLineMst->checkExistData($postData["inputDatas"], $postData["intSaveRowCnt"]);

            if (!$result["result"]) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDelUpdYosanLineMst()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmYosanLineMstFinally"
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
                $this->FrmYosanLineMst = new FrmYosanLineMst();
                $result = $this->FrmYosanLineMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //ﾏｽﾀに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmYosanLineMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //ﾏｽﾀを削除する
                $result = $this->FrmYosanLineMst->fncDeleteYosanLineMst($postData['BUSYO_KB']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //INSERT発行
                    for ($i = 0; $i < count($postData['inputDatas']); $i++) {
                        if ($postData['inputDatas'][$i]['BUSYO_KB'] != "") {
                            //更新処理を実行
                            $result = $this->FrmYosanLineMst->fncInsertYosanLineMst($postData['inputDatas'][$i]);
                        }

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmYosanLineMst->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmYosanLineMstFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmYosanLineMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmYosanLineMst->Do_close();
    }

}