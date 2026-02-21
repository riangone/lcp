<?php
namespace App\Controller\R4\R4K;

use App\Controller\AppController;
use App\Model\R4\R4K\FrmKaverRankSyukei;

//*******************************************
// * sample controller
//*******************************************
class FrmKaverRankSyukeiController extends AppController
{

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $FrmKaverRankSyukei = '';
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFnc');
    }
    public function index()
    {
        // 画面表示内容の設定
        // レイアウトファイルの指定(app/View/Layouts/frmListSelect_layout.ctpを参照)

        $this->render('index', 'FrmKaverRankSyukei_layout');
    }

    public function frmGenkaiMakeLoad()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        try {

            $this->FrmKaverRankSyukei = new FrmKaverRankSyukei();

            $result = $this->FrmKaverRankSyukei->frmSampleLoadDate();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

        } catch (\Exception $e) {

            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function fncExistsCheck()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data'];
        try {
            $this->FrmKaverRankSyukei = new FrmKaverRankSyukei();

            $result = $this->FrmKaverRankSyukei->fncExistsJinkenhi($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $count = count((array) $result["data"]);

            if ($count == 0) {
                $result['data'] = "E0001";
                throw new \Exception($result['data']);
            }

            $result = $this->FrmKaverRankSyukei->fncExistsJibaiseki($postData);

            if (!$result['result']) {

                throw new \Exception($result['data']);
            }
            $count = count((array) $result["data"]);

            if ($count == 0) {
                $result['data'] = "E0002";
                throw new \Exception($result['data']);
            }

            $result = $this->FrmKaverRankSyukei->fncExistsNinho($postData);

            if (!$result['result']) {

                throw new \Exception($result['data']);
            }
            $count = count((array) $result["data"]);

            if ($count == 0) {
                $result['data'] = "E0003";
                throw new \Exception($result['data']);
            }

            $result = $this->FrmKaverRankSyukei->fncExistsNensu($postData);

            if (!$result['result']) {

                throw new \Exception($result['data']);
            }

            $count = count((array) $result["data"]);

            if ($count == 0) {
                $result['data'] = "E0004";
                throw new \Exception($result['data']);
            }

            $result['data'] = "";
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
        }

        $this->fncReturn($result);
    }

    public function cmdActClick()
    {
        $result = array(
            'result' => 'false',
            'data' => 'ErrorInfo',
            'row' => '',
        );
        $postData = $_POST['data'];

        try {
            $this->FrmKaverRankSyukei = new FrmKaverRankSyukei();

            $result = $this->FrmKaverRankSyukei->Do_conn();

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $this->FrmKaverRankSyukei->Do_transaction();

            $blnTranFlg = TRUE;

            //*****総限界利益固定費カバー率ランキング集計*****
            // 営業ｽﾀｯﾌデータ削除
            $result1 = $this->FrmKaverRankSyukei->fncDeleteSalse($postData);

            if (!$result1['result']) {
                throw new \Exception($result1['data']);
            }
            //営業ｽﾀｯﾌﾃﾞｰﾀ作成
            $result2 = $this->FrmKaverRankSyukei->fncInsKaverRankTotal($postData);

            if (!$result2['result']) {
                throw new \Exception($result2['data']);
            }

            //営業ｽﾀｯﾌﾃﾞｰﾀ人件費差分
            $result3 = $this->FrmKaverRankSyukei->fncUpdJinkenSagakuWari($postData);

            if (!$result3['result']) {
                throw new \Exception($result3['data']);
            }

            //営業ｽﾀｯﾌﾃﾞｰﾀUPDATE１
            $result4 = $this->FrmKaverRankSyukei->fncUpdBolboZeroSet($postData);

            if (!$result4['result']) {
                throw new \Exception($result4['data']);
            }

            //営業ｽﾀｯﾌﾃﾞｰﾀUPDATE2
            $result5 = $this->FrmKaverRankSyukei->fncUpdBubetuMinusKobetu($postData);

            if (!$result5['result']) {
                throw new \Exception($result5['data']);
            }

            //営業ｽﾀｯﾌUPDATE3
            $result6 = $this->FrmKaverRankSyukei->fncAtamaWariIppan($postData);

            if (!$result6['result']) {
                throw new \Exception($result6['data']);
            }

            //営業ｽﾀｯﾌUPDATE4
            $result7 = $this->FrmKaverRankSyukei->fncAtamaWariManeger($postData);

            if (!$result7['result']) {
                throw new \Exception($result7['data']);
            }

            //営業ｽﾀｯﾌUPDATE5
            $result8 = $this->FrmKaverRankSyukei->fncUpdRankTotalFirst($postData);

            if (!$result8['result']) {
                throw new \Exception($result8['data']);
            }

            //営業ｽﾀｯﾌUPDATE6
            $result9 = $this->FrmKaverRankSyukei->fncUpdRankTotalSecound($postData);

            if (!$result9['result']) {
                throw new \Exception($result9['data']);
            }

            $this->FrmKaverRankSyukei->Do_commit();

            $blnTranFlg = FALSE;

            $result['result'] = TRUE;
            $result['data'] = '';
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        if ($blnTranFlg) {
            $this->FrmKaverRankSyukei->Do_rollback();
        }

        $this->FrmKaverRankSyukei->Do_close();

        $this->fncReturn($result);
    }

}