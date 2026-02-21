<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmShihyoMst;

class FrmShihyoMstController extends AppController
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
    public $FrmShihyoMst;

    public function index()
    {
        $this->render('index', 'FrmShihyoMst_layout');
    }

    public function frmGetYearMonth()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmShihyoMst = new FrmShihyoMst();
            $result = $this->FrmShihyoMst->frmGetYearMonth();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncShihyoSelect()
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
                $this->FrmShihyoMst = new FrmShihyoMst();
                $result = $this->FrmShihyoMst->fncShihyoSelect($postData['BUSYOCD'], $postData['KI']);

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

    public function frmHSHIHYODeleteRow()
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
                $this->FrmShihyoMst = new FrmShihyoMst();
                $result = $this->FrmShihyoMst->frmHSHIHYODeleteRow($postData['BUSYOCD'], $postData['KI'], $postData['LINENO']);

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
                "frmShihyoMstFinally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = json_decode($_POST['data'], true);
            }

            if (!$postData == "") {
                $this->FrmShihyoMst = new FrmShihyoMst();
                $result = $this->FrmShihyoMst->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //指標ﾏｽﾀに登録開始
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmShihyoMst->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->blnTranFlg = TRUE;

                //指標ﾏｽﾀを削除する
                $result = $this->FrmShihyoMst->fncDelDataMst($postData['BUSYOCD'], $postData['KI']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //指標ﾏｽﾀに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['inputData']); $i++) {
                        if ($postData['inputData'][$i]['Check_FLAG'] == "1") {
                            $result = $this->FrmShihyoMst->fncUpdDataMst($postData['BUSYOCD'], $postData['KI'], $postData['KKRBUSYO'], $postData['inputData'][$i], $i);

                            if (!$result['result']) {
                                throw new \Exception($result['data']);
                            }
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmShihyoMst->Do_commit();

                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->blnTranFlg = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function frmShihyoMstFinally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmShihyoMst->Do_rollback();
        }

        //DB接続解除
        $this->FrmShihyoMst->Do_close();
    }

}