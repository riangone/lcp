<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmYosanTorikomiMst;

class FrmYosanTorikomiMstController extends AppController
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
    public $FrmYosanTorikomiMst;

    public function index()
    {
        $this->render('index', 'FrmYosanTorikomiMst_layout');
    }

    public function fncYosanTorikomiMstSel()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->FrmYosanTorikomiMst = new FrmYosanTorikomiMst();
                $result = $this->FrmYosanTorikomiMst->fncYosanTorikomiMstSel($postData["BUSYO_KB"]);

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
                $result['result'] = TRUE;
                $result['data'] = "";

            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();

            unset($_POST['request']);
        }

        $this->fncReturn($result);
    }

    public function fncDeleteRowData()
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

            $this->FrmYosanTorikomiMst = new FrmYosanTorikomiMst();
            $result = $this->FrmYosanTorikomiMst->fncDeleteRowData($postData['BUSYO_KB'], $postData['LINE_NO'], $postData['EXCEL_LINE_NO']);

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

            $this->FrmYosanTorikomiMst = new FrmYosanTorikomiMst();

            $l = $postData["intSaveRowCnt"];

            if ($l == count($postData["inputDatas"])) {
                $result['result'] = TRUE;
                $result['data'] = "";
            } else {
                while ($l < count($postData["inputDatas"])) {
                    $result = $this->FrmYosanTorikomiMst->checkExistData($postData["inputDatas"][$l]);

                    if (!$result["result"]) {
                        throw new \Exception($result['data']);
                    } else
                        if (count((array) $result["data"]) != 0) {
                            $result["rowNo"] = $l;
                            break;
                        }

                    $l += 1;
                }
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    function fncDelUpdHYOSANTORIKOMIMST()
    {
        $postData = "";
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        register_shutdown_function(
            array(
                $this,
                "frmYosanTorikomiMstFinally"
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
                $this->FrmYosanTorikomiMst = new FrmYosanTorikomiMst();
                $result = $this->FrmYosanTorikomiMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //ﾏｽﾀに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmYosanTorikomiMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->errorFlag = TRUE;

                //ﾏｽﾀを削除する
                $result = $this->FrmYosanTorikomiMst->fncDeleteYosanTorikomiMst($postData['BUSYO_KB']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //INSERT発行
                    for ($i = 0; $i < count($postData['inputDatas']); $i++) {
                        if ($postData['inputDatas'][$i]['BUSYO_KB'] != "") {
                            //更新処理を実行
                            $result = $this->FrmYosanTorikomiMst->fncInsertYosanTorikomiMst($postData['inputDatas'][$i]);
                        }

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmYosanTorikomiMst->Do_commit();
                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->errorFlag = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmYosanTorikomiMstFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->errorFlag) {
            $this->FrmYosanTorikomiMst->Do_rollback();
        }
        //DB接続解除
        $this->FrmYosanTorikomiMst->Do_close();
    }

}