<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmJKSYSLoginEdit;

class FrmJKSYSLoginEditController extends AppController
{
    public $autoLayout = TRUE;
    // public $autoRender = FALSE;

    public $blnTranFlg = FALSE;
    public $FrmJKSYSLoginEdit;
    // public $components = array(
    //     'RequestHandler',
    //     'ClsComFncJKSYS'
    // );
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncJKSYS');
    }

    public function index()
    {
        $this->render('index', 'FrmLoginEdit_layout');
    }

    public function fncLoadDeal()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $this->FrmJKSYSLoginEdit = new FrmJKSYSLoginEdit();
            $tougetu = $this->FrmJKSYSLoginEdit->fncHKEIRICTL();

            //コントロールマスタ存在ﾁｪｯｸ
            if (!$tougetu['result']) {
                throw new \Exception($tougetu['data']);
            }
            if (count((array) $tougetu['data']) == 0) {
                //コントロールマスタが存在していない場合
                throw new \Exception("コントロールマスタが存在しません！");
            }

            //コンボボックスに当月年月を設定
            $strTougetu = $this->ClsComFncJKSYS->FncNv($tougetu['data'][0]["TOUGETU"]);
            $result['data']['strTougetu'] = $strTougetu;

            if (isset($_POST['data'])) {
                $postData = $_POST['data'];

                // パターンＩＤコンボボックスの項目に設定する
                $arrCombox = $this->FrmJKSYSLoginEdit->SetPatternCombox("001", $postData["cboSysKB"]);
                if (!$arrCombox['result']) {
                    throw new \Exception($arrCombox['data']);
                }
                //データセットの取得
                if ($arrCombox['row'] > 0) {
                    $result['data']['arrCombox'] = $arrCombox['data'];
                }

                //初期化
                $pattern = $this->FrmJKSYSLoginEdit->getPatternID($postData["UserID"], $strTougetu, $postData["cboSysKB"]);
                if (!$pattern['result']) {
                    throw new \Exception($pattern['data']);
                }
                if ($pattern['row'] == 0) {
                    throw new \Exception('I0001');
                }

                $result['data']['pattern'] = $pattern['data'][0];
            }

            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncDeleteUpdataMst()
    {
        $this->blnTranFlg = FALSE;
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        register_shutdown_function(array(
            $this,
            "finally"
        ));

        try {
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
                $this->FrmJKSYSLoginEdit = new FrmJKSYSLoginEdit();

                //ﾄﾗﾝｻﾞｸｼｮﾝ開始
                $this->FrmJKSYSLoginEdit->Do_transaction();

                //ﾄﾗﾝｻﾞｸｼｮﾝをかけた時点でﾌﾗｸﾞをTrueに設定
                $this->blnTranFlg = TRUE;

                //ログインﾏｽﾀを削除する
                $result = $this->FrmJKSYSLoginEdit->fncDelMst($postData['USER_ID'], $postData['cboSysKB']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //ログインﾏｽﾀに追加するためのSQLを発行
                $result = $this->FrmJKSYSLoginEdit->getLogin($postData);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //コミット
                $this->FrmJKSYSLoginEdit->Do_commit();

                //ﾄﾗﾝｻﾞｸｼｮﾝ終了
                $this->blnTranFlg = FALSE;

                $result['result'] = TRUE;
                $result['data'] = "";
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function finally()
    {
        //トランザクションがかかったままの場合はロールバックする
        if ($this->blnTranFlg) {
            //ロールバック
            $this->FrmJKSYSLoginEdit->Do_rollback();
        }
    }

}
