<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmHknSytKmkLineMst;

class FrmHknSytKmkLineMstController extends AppController
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
    public $FrmHknSytKmkLineMst;

    public function index()
    {
        $this->render('index', 'FrmHknSytKmkLineMst_layout');
    }

    public function fncKmkLineSelectLine()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmHknSytKmkLineMst = new FrmHknSytKmkLineMst();
            $result = $this->FrmHknSytKmkLineMst->fncSelectLine();

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
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }
        $this->fncReturn($result);
    }

    public function fncKmkLineMstSelect()
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

            $this->FrmHknSytKmkLineMst = new FrmHknSytKmkLineMst();
            $result = $this->FrmHknSytKmkLineMst->fncSelectKamoku($postData["LINE_NO"]);

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
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }
        $this->fncReturn($result);
    }

    public function frmKmkDeleteRow()
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
                $this->FrmHknSytKmkLineMst = new FrmHknSytKmkLineMst();
                $result = $this->FrmHknSytKmkLineMst->fncDeleteRow($postData['KAMOK_CD'], $postData['HIMOK_CD'], $postData['LINE_NO']);

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

    public function fncKmkLineDelUpd()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmHknSytKmkLineMstDealFinally"
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
                $this->FrmHknSytKmkLineMst = new FrmHknSytKmkLineMst();
                $result = $this->FrmHknSytKmkLineMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmHknSytKmkLineMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //科目ラインマスタのデータを削除する
                $result = $this->FrmHknSytKmkLineMst->fncDeleteKmkLineMst($postData['LINE_NO']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //科目ラインマスタに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['inputData']); $i++) {
                        $result = $this->FrmHknSytKmkLineMst->fncInsertKmkLineMst($postData['inputData'][$i], $postData['LINE_NO']);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        } else {
                            $result['result'] = TRUE;
                            $result['data'] = "";
                        }
                    }
                }

                //コミット
                $this->FrmHknSytKmkLineMst->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmHknSytKmkLineMstDealFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmHknSytKmkLineMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmHknSytKmkLineMst->Do_close();
    }

}