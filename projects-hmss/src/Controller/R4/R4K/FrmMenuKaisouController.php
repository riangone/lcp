<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\Component\ClsComFnc;
use App\Model\R4\R4K\FrmMenuKaisou;

class FrmMenuKaisouController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmMenuKaisou;
    private $blnTranFlg;
    // public $ClsComFnc = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }

    public function index()
    {
        $this->render('index', 'FrmMenuKaisou_layout');
    }

    public function fncHMENUSTYLESelect()
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

            if ($postData['STYLE_ID'] != 'load') {
                $this->FrmMenuKaisou = new FrmMenuKaisou();
                $result = $this->FrmMenuKaisou->fncHMENUSTYLESelect($postData["STYLE_ID"]);

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

    public function fncGetSysNM()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            $this->FrmMenuKaisou = new FrmMenuKaisou();
            $result = $this->FrmMenuKaisou->fncGetSysNM();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncGetProNM()
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
                $this->FrmMenuKaisou = new FrmMenuKaisou();
                $result = $this->FrmMenuKaisou->fncGetProNM($postData['PRO_NO']);

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

    public function fncDelUpdData()
    {
        $postData = "";
        $result = array(
            "result" => FALSE,
            "data" => "ErrorInfo"
        );

        register_shutdown_function(
            array(
                $this,
                "finally"
            )
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            if (!$postData == "") {
                $this->FrmMenuKaisou = new FrmMenuKaisou();
                $result = $this->FrmMenuKaisou->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //経理ｼｽﾃﾑ・ｼﾐｭﾚｰｼｮﾝのログインを切り分けるためｼｽﾃﾑ区分を追加したことによる変更
                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmMenuKaisou->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->blnTranFlg = TRUE;

                //--- 20151208 LI INS S
                $ClsComFnc = new ClsComFnc();
                //--- 20151208 LI INS E

                //階層ﾏｽﾀを削除する
                $result = $this->FrmMenuKaisou->fncDelKaisouMst($postData['style_id']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    //予算ﾏｽﾀに追加するためのSQLを発行
                    for ($i = 0; $i < count($postData['inputData']); $i++) {
                        //--- 20151208 LI UPD S
                        // $result = $this -> FrmMenuKaisou -> fncUpdKaisouMst($postData['style_id'], $postData['inputData'][$i]);
                        $result = $this->FrmMenuKaisou->fncUpdKaisouMst($postData['style_id'], $postData['inputData'][$i], $ClsComFnc);
                        //--- 20151208 LI UPD E

                        if (!$result['result']) {
                            throw new \Exception($result['data']);
                        }
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmMenuKaisou->Do_commit();

                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->blnTranFlg = FALSE;
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function finally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmMenuKaisou->Do_rollback();
        }

        //DB接続解除
        $this->FrmMenuKaisou->Do_close();
    }

}
