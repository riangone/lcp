<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmYosanTTLBusyoMst;

class FrmYosanTTLBusyoMstController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    var $blnTranFlg = FALSE;
    public $FrmYosanTTLBusyoMst;

    public function index()
    {
        $this->render('index', 'FrmYosanTTLBusyoMst_layout');
    }

    public function fncBusyoMstSelect()
    {
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        try {
            $this->FrmYosanTTLBusyoMst = new FrmYosanTTLBusyoMst();
            $result = $this->FrmYosanTTLBusyoMst->fncBusyoMstSelect();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result['data'], $totalPage, $page, $tmpCount);

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

    public function fncYOSANTTLBusyoMstSelect()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];
            }

            $this->FrmYosanTTLBusyoMst = new FrmYosanTTLBusyoMst();
            $result = $this->FrmYosanTTLBusyoMst->fncYOSANTTLBusyoMstSelect($postData['Busyo_CD']);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else {
                $tmpJqgridShow = $this->ClsComFnc->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = (int) $tmpJqgridShow['count'];

                $tmpJqgrid = $this->ClsComFnc->FncCreateJqGridData($result['data'], $totalPage, $page, $tmpCount);

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
                $this->FrmYosanTTLBusyoMst = new FrmYosanTTLBusyoMst();
                $result = $this->FrmYosanTTLBusyoMst->fncBusyoNmSelect($this->ClsComFnc->FncNv($postData['BUSYO_CD']));

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

    public function fncDeleteRow()
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

            if ($postData == "") {
                $result['result'] = FALSE;
                $result['data'] = "ErrorInfo";
            } else {
                $this->FrmYosanTTLBusyoMst = new FrmYosanTTLBusyoMst();
                $result = $this->FrmYosanTTLBusyoMst->fncDeleteRowData($postData['BUSYO_CD'], $postData['TOTAL_BUSYO_CD']);

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

    public function fncBusyoNmCheck()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo",
            "rowNO" => ""
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            if ($postData == "") {
                $result['result'] = FALSE;
                $result['data'] = "ErrorInfo";
                $result['rowNO'] = "";
            } else {
                $this->FrmYosanTTLBusyoMst = new FrmYosanTTLBusyoMst();

                foreach ($postData['busyoData'] as $key => $value) {
                    if ($value['BUSYO_CD'] != "") {
                        $result = $this->FrmYosanTTLBusyoMst->fncBusyoNmSelect($value['BUSYO_CD']);

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        } elseif (count((array) $result['data']) == 0) {
                            $result['rowNO'] = $key;
                            break;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
            $result['rowNO'] = "";
        }

        $this->fncReturn($result);
    }

    public function fncDelUpdYOSANTTLBusyo()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        register_shutdown_function(
            array(
                $this,
                "frmYosanTTLBusyoMstFinally"
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
                $this->FrmYosanTTLBusyoMst = new FrmYosanTTLBusyoMst();
                $result = $this->FrmYosanTTLBusyoMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //予算集計部署マスタに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmYosanTTLBusyoMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->blnTranFlg = TRUE;

                //予算集計部署マスタのデータを削除する
                $result = $this->FrmYosanTTLBusyoMst->fncDeleteYosanTTLBusyo($postData['TOTAL_BUSYO_CD']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //予算集計部署マスタに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['INPUT_DATA']); $i++) {
                        if ($postData['INPUT_DATA'][$i]['BUSYO_CD'] != "") {
                            //予算集計部署マスタ更新処理を実行
                            $result = $this->FrmYosanTTLBusyoMst->fncInsertYOSANTTLBusyo($postData['INPUT_DATA'][$i], $postData['TOTAL_BUSYO_CD']);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }
                }

                $result['result'] = TRUE;
                $result['data'] = "";

                //コミット
                $this->FrmYosanTTLBusyoMst->Do_commit();

                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->blnTranFlg = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmYosanTTLBusyoMstFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmYosanTTLBusyoMst->Do_rollback();
        }

        //DB接続解除
        $this->FrmYosanTTLBusyoMst->Do_close();
    }

}