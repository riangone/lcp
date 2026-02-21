<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmMKamokuMnt;

class FrmMKamokuMntController extends AppController
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
    public $FrmMKamokuMnt;

    public function index()
    {
        $this->render('index', 'FrmMKamokuMnt_layout');
    }

    public function fncMKamokuSelect()
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

            $this->FrmMKamokuMnt = new FrmMKamokuMnt();
            $result = $this->FrmMKamokuMnt->fncSelect($postData['kamoku_cd']);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);

                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];
                // $tmpCount -= 1;

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

    public function frmKamokuDeleteRow()
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
                $this->FrmMKamokuMnt = new FrmMKamokuMnt();
                $result = $this->FrmMKamokuMnt->fncDeleteRow($postData['KAMOK_CD'], $postData['KOMOK_CD']);

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

    public function frmCheckExit()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo',
            'rowNO' => "none"
        );

        try {
            if (isset($_POST['data'])) {
                $postData = json_decode($_POST['data'], true);
            }

            if ($postData == "") {
                $result['result'] = FALSE;
                $result['data'] = "ErrorInfo";
                $result['rowNO'] = "none";
            } else {
                $this->FrmMKamokuMnt = new FrmMKamokuMnt();

                foreach ($postData as $value) {
                    if ($value['FLAG'] != "1") {
                        $result = $this->FrmMKamokuMnt->fncKmkChk($value['KAMOK_CD'], $value['KOMOK_CD']);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        } elseif (count((array) $result['data']) > 0) {
                            $result['rowNO'] = $value['rowNO'];
                            break;
                        } else {
                            $result['rowNO'] = "none";
                        }
                    }
                }

                $result['result'] = TRUE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['rowNO'] = "none";
        }

        $this->fncReturn($result);
    }

    public function fncMKamokuDelUpd()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmMKamokuMntDealfinally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = json_decode($_POST['data'], true);
            }

            if ($postData == "") {
                $result = array(
                    'result' => FALSE,
                    'data' => 'ErrorInfo'
                );
            } else {
                $this->FrmMKamokuMnt = new FrmMKamokuMnt();
                $result = $this->FrmMKamokuMnt->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //科目費目マスタに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmMKamokuMnt->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //科目費目マスタのデータを削除する
                $result = $this->FrmMKamokuMnt->fncMKamokuMntDelete($postData['kamoku_cd']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //科目マスタに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['inputData']); $i++) {
                        if ($postData['inputData'][$i]['KAMOK_CD'] != "") {
                            $result = $this->FrmMKamokuMnt->fncMKamokuMntInsert($postData['inputData'][$i]);

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
                $this->FrmMKamokuMnt->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmMKamokuMntDealfinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmMKamokuMnt->Do_rollback();
        }
        //DB接続解除
        $this->FrmMKamokuMnt->Do_close();
    }

}
