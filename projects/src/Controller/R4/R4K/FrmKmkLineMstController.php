<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKmkLineMst;

class FrmKmkLineMstController extends AppController
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
    public $FrmKmkLineMst;

    public function index()
    {
        $this->render('index', 'FrmKmkLineMst_layout');
    }

    public function fncKmkLineSelectLine()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmKmkLineMst = new FrmKmkLineMst();
            $result = $this->FrmKmkLineMst->fncSelectLine();

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

            $this->FrmKmkLineMst = new FrmKmkLineMst();
            $result = $this->FrmKmkLineMst->fncSelectKamoku($postData["LINE_NO"]);

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
                $this->FrmKmkLineMst = new FrmKmkLineMst();
                $result = $this->FrmKmkLineMst->fncDeleteRow($postData['KAMOK_CD'], $postData['HIMOK_CD'], $postData['LINE_NO']);

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
                "frmKmkLineMstDealfinally"
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
                $this->FrmKmkLineMst = new FrmKmkLineMst();
                $result = $this->FrmKmkLineMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmKmkLineMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //科目費目マスタのデータを削除する
                $result = $this->FrmKmkLineMst->fncDeleteKmkLineMst($postData['LINE_NO']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //科目マスタに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['inputData']); $i++) {
                        if ($postData['inputData'][$i]['KAMOK_CD'] != "") {
                            $result = $this->FrmKmkLineMst->fncInsertKmkLineMst($postData['inputData'][$i], $postData['LINE_NO']);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            } else {
                                $result['result'] = TRUE;
                                $result['data'] = "";
                            }
                        }
                    }
                }

                //コミット
                $this->FrmKmkLineMst->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmKmkLineMstDealfinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmKmkLineMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmKmkLineMst->Do_close();
    }

}