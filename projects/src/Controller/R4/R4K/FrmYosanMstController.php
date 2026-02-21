<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmYosanMst;

class FrmYosanMstController extends AppController
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
    public $FrmYosanMst;

    public function index()
    {
        $this->render('index', 'FrmYosanMst_layout');
    }

    public function frmGetYearMonth()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmYosanMst = new FrmYosanMst();
            $result = $this->FrmYosanMst->frmGetYearMonth();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncYosanSelect()
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
                $this->FrmYosanMst = new FrmYosanMst();
                $result = $this->FrmYosanMst->fncYosanSelect($postData['BUSYOCD'], $postData['KI']);

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

    public function frmHYOSANDeleteRow()
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
                $this->FrmYosanMst = new FrmYosanMst();
                $result = $this->FrmYosanMst->frmHYOSANDeleteRow($postData['BUSYOCD'], $postData['KI'], $postData['LINENO']);

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

    public function fncDelUpdDataMst()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        register_shutdown_function(
            array(
                $this,
                "frmYosanMstFinally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = json_decode($_POST['data'], true);
            }

            if (!$postData == "") {
                $this->FrmYosanMst = new FrmYosanMst();
                $result = $this->FrmYosanMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //予算ﾏｽﾀに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmYosanMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->blnTranFlg = TRUE;

                //予算ﾏｽﾀを削除する
                $result = $this->FrmYosanMst->fncDelDataMst($postData['BUSYOCD'], $postData['KI']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //予算ﾏｽﾀに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['inputData']); $i++) {
                        if ($postData['inputData'][$i]['Check_FLAG'] == "1") {
                            $result = $this->FrmYosanMst->fncUpdDataMst($postData['BUSYOCD'], $postData['KI'], $postData['KKRBUSYO'], $postData['inputData'][$i], $i);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmYosanMst->Do_commit();

                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->blnTranFlg = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmYosanMstFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmYosanMst->Do_rollback();
        }

        //DB接続解除
        $this->FrmYosanMst->Do_close();
    }

}