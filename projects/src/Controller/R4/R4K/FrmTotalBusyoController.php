<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmTotalBusyo;

class FrmTotalBusyoController extends AppController
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
    public $FrmTotalBusyo;

    public function index()
    {
        $this->render('index', 'FrmTotalBusyo_layout');
    }

    public function fncBusyoMstSelect()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmTotalBusyo = new FrmTotalBusyo();
            $result = $this->FrmTotalBusyo->fncBusyoMstSelect();

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

    public function fncTTLBusyoMstSelect()
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

            $this->FrmTotalBusyo = new FrmTotalBusyo();
            $result = $this->FrmTotalBusyo->fncTTLBusyoMstSelect($postData["Busyo_CD"]);

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

    public function fncPlusTTLBusyoMstSelect()
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

            $this->FrmTotalBusyo = new FrmTotalBusyo();
            $result = $this->FrmTotalBusyo->fncPlusTTLBusyoMstSelect($postData["Busyo_CD"]);

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

    public function fncPlusKMKLineMstSelect()
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
                $this->FrmTotalBusyo = new FrmTotalBusyo();
                $result = $this->FrmTotalBusyo->fncPlusKMKLineMstSelect($postData['Busyo_CD']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmMeisaiDeleteRow()
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
                $this->FrmTotalBusyo = new FrmTotalBusyo();
                $result = $this->FrmTotalBusyo->fncDeleteRow($postData['BUSYO_CD'], $postData['TOTAL_BUSYO_CD']);

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

    public function fncBusyoNmSelect()
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
                $this->FrmTotalBusyo = new FrmTotalBusyo();
                $result = $this->FrmTotalBusyo->fncBusyoNmSelect($this->ClsComFnc->FncNv($postData['BUSYO_CD']));

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmMeisaiPlusDeleteRow()
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
                $this->FrmTotalBusyo = new FrmTotalBusyo();
                $result = $this->FrmTotalBusyo->fncDeletePlusRow($postData['BUSYO_CD'], $postData['TOTAL_BUSYO_CD']);

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
            'rowNO' => "none",
            'gridNM' => ""
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            if ($postData == "") {
                $result['result'] = FALSE;
                $result['data'] = "ErrorInfo";
                $result['rowNO'] = "none";
                $result['gridNM'] = "";
            } else {
                $this->FrmTotalBusyo = new FrmTotalBusyo();

                foreach ($postData['MeisaiData'] as $value) {
                    if ($value['BUSYO_CD'] != "") {
                        $result = $this->FrmTotalBusyo->fncBusyoNmSelect($value['BUSYO_CD']);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        } elseif (count((array) $result['data']) == 0) {
                            $result['rowNO'] = $value['rowNO'];
                            $result['gridNM'] = "grid_Meisai";
                            break;
                        } else {
                            $result['rowNO'] = "none";
                        }
                    }
                }

                if ($result['rowNO'] == "none" && $postData['checkPlus'] == "true") {
                    foreach ($postData['MeisaiDataPlus'] as $value) {
                        if ($value['BUSYO_CD'] != "") {
                            $result = $this->FrmTotalBusyo->fncBusyoNmSelect($value['BUSYO_CD']);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            } elseif (count((array) $result['data']) == 0) {
                                $result['rowNO'] = $value['rowNO'];
                                $result['gridNM'] = "grid_MeisaiPlus";
                                break;
                            } else {
                                $result['rowNO'] = "none";
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['rowNO'] = "none";
            $result['gridNM'] = "";
        }

        $this->fncReturn($result);
    }

    public function fncDelUpdTTLBusyo()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmTotalBusyoDealfinally"
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
                $this->FrmTotalBusyo = new FrmTotalBusyo();
                $result = $this->FrmTotalBusyo->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //集計部署マスタに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmTotalBusyo->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //集計部署マスタのデータを削除する
                $result = $this->FrmTotalBusyo->fncDeleteTTLBusyo($postData['TOTAL_BUSYO_CD']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //集計部署マスタに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['MeisaiData']); $i++) {
                        if ($postData['MeisaiData'][$i]['BUSYO_CD'] != "") {
                            //集計部署マスタ更新処理を実行
                            $result = $this->FrmTotalBusyo->fncInsertTTLBusyo($postData['MeisaiData'][$i], $postData["TOTAL_BUSYO_CD"]);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }

                    if ($postData['checkPlus'] == "true") {
                        //中古車部門加算部署マスタのデータを削除する
                        $result = $this->FrmTotalBusyo->fncDeletePlusTTLBusyo($postData['TOTAL_BUSYO_CD']);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        } else {
                            //中古車部門加算部署マスタに追加するためのSQLを発行
                            for ($i = 0; $i < count($postData['MeisaiDataPlus']); $i++) {
                                if ($postData['MeisaiDataPlus'][$i]['BUSYO_CD'] != "") {
                                    //中古車部門加算部署マスタ更新処理を実行
                                    $result = $this->FrmTotalBusyo->fncInsertPlusTTLBusyo($postData['MeisaiDataPlus'][$i], $postData["TOTAL_BUSYO_CD"]);

                                    if (!$result['result']) {
                                        throw new \Exception($result['data']);
                                    }
                                }
                            }
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmTotalBusyo->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmTotalBusyoDealfinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmTotalBusyo->Do_rollback();
        }
        //DB接続解除
        $this->FrmTotalBusyo->Do_close();
    }

}
