<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmLoginEdit;

class FrmLoginEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = false;
    private $FrmLoginEdit;
    private $blnTranFlg;
    private $fncDelMst;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        $this->render('index', 'FrmLoginEdit_layout');
    }

    public function fncLoadDeal()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            $this->FrmLoginEdit = new FrmLoginEdit();
            $result = $this->FrmLoginEdit->fncHKEIRICTL();

            //コントロールマスタ存在ﾁｪｯｸ
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } else
                if (count((array) $result['data']) == 0) {
                    //コントロールマスタが存在していない場合
                    throw new \Exception("コントロールマスタが存在しません！");
                }

            //コンボボックスに当月年月を設定
            $strTougetu = $this->ClsComFnc->FncNv($result['data'][0]["TOUGETU"]);

            $result = $this->FrmLoginEdit->getComboxListTable();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $arrSTYLEID = $result['data'];

            $result = $this->FrmLoginEdit->getPatternID($postData["UserID"], $strTougetu);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $arrUserInfo = $result['data'];

            // パターンＩＤコンボボックスの項目に設定する
            $result = $this->FrmLoginEdit->SetPatternCombox($arrUserInfo[0]["STYLE_ID"]);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['strTougetu'] = $strTougetu;
            $result['arrSTYLEID'] = array();
            $result['arrSTYLEID'] = $arrSTYLEID;
            $result['arrUserInfo'] = array();
            $result['arrUserInfo'] = $arrUserInfo;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function setPatternCombox()
    {
        $result = array(
            'result' => FALSE,
            'data' => 'ErrorInfo'
        );

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
            }

            $this->FrmLoginEdit = new FrmLoginEdit();
            // パターンＩＤコンボボックスの項目に設定する
            $result = $this->FrmLoginEdit->SetPatternCombox($postData["UserID"]);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteUpdataMst()
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
                $this->FrmLoginEdit = new FrmLoginEdit();
                $result = $this->FrmLoginEdit->Do_conn();

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmLoginEdit->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->blnTranFlg = TRUE;

                //ログインﾏｽﾀを削除する
                $result = $this->FrmLoginEdit->fncDelMst($postData['USER_ID']);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                } else {
                    $UPDCLTNM = $this->request->clientIp();
                    //ログインﾏｽﾀに追加するためのSQLを発行
                    $result = $this->FrmLoginEdit->fncUpdMst($postData['USER_ID'], $postData['PASSWORD'], $postData['REC_CRE_DT'], $postData['STYLE_ID'], $postData['PATTERN_ID'], $UPDCLTNM);

                    if (!$result['result']) {
                        throw new \Exception($result['data']);
                    }

                    $result['result'] = TRUE;
                    $result['data'] = "";
                }

                //コミット
                $this->FrmLoginEdit->Do_commit();

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
            $this->FrmLoginEdit->Do_rollback();
        }

        //DB接続解除
        $this->FrmLoginEdit->Do_close();
    }

}