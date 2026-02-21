<?php
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKLoginEdit;
use App\Controller\HDKAIKEI\HDKAIKEIController;
//*******************************************
// * sample controller
//*******************************************
class HDKLoginEditController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    public $HDKLoginEdit = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HDKLoginEdit_layout');
    }
    public function fncGetBusyoMstValue()
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //部署名取得
            $objDs = $this->ClsComFncHDKAIKEI->FncGetCreatBusyoMstValue();
            if (!$objDs['result']) {
                throw new \Exception($objDs['data']);
            }
            $res['data'] = $objDs['data'];
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function fncSyainNoChanged()
    {
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $postData = $_POST['data'];
            $postData['SYS_KB'] = HDKAIKEIController::SYS_KB;
            //部署名取得
            $this->HDKLoginEdit = new HDKLoginEdit();
            $res = $this->HDKLoginEdit->FncGetSyainMstValue($postData);
            if (!$res['result']) {
                throw new \Exception($res['data']);
            }
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
    public function fncLoginBtnClick()
    {
        $this->HDKLoginEdit = new HDKLoginEdit();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $res['data']['tranStartFlg'] = FALSE;
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $postData = $_POST['data'];

            //トランザクション開始
            $this->HDKLoginEdit->Do_transaction();
            $res['data']['tranStartFlg'] = TRUE;

            $postData['SYS_KB'] = HDKAIKEIController::SYS_KB;
            $postData['STYLE_ID'] = '001';
            $postData['PATTERN_ID'] = '000';
            $postData['SYSDATE'] = date('Y-m-d H:i:s');
            $postData['DATEYMD'] = date_format(date_create($postData['SYSDATE']), 'Ymd');
            $postData['END_DATE'] = date_format(date_create(date('Y-m-d', strtotime('-1 day'))), 'Ymd');

            //下記３テーブルにデータを登録
            $LoginSel = $this->HDKLoginEdit->mLoginSel($postData);
            if (!$LoginSel['result']) {
                throw new \Exception($LoginSel['data']);
            }
            if ($LoginSel['row'] > 0) {
                $LoginUpd = $this->HDKLoginEdit->mLoginUpd($postData);
                if (!$LoginUpd['result']) {
                    throw new \Exception($LoginUpd['data']);
                }
            } else {
                $LoginIns = $this->HDKLoginEdit->mLoginIns($postData);
                if (!$LoginIns['result']) {
                    throw new \Exception($LoginIns['data']);
                }
            }
            $hhaizokuSel = $this->HDKLoginEdit->hhaizokuSel($postData);
            if (!$hhaizokuSel['result']) {
                throw new \Exception($hhaizokuSel['data']);
            }
            if ($hhaizokuSel['row'] == 0) {
                $hhaizokuUpd = $this->HDKLoginEdit->hhaizokuUpd($postData);
                if (!$hhaizokuUpd['result']) {
                    throw new \Exception($hhaizokuUpd['data']);
                }
                $hhaizokuIns = $this->HDKLoginEdit->hhaizokuIns($postData);
                if (!$hhaizokuIns['result']) {
                    throw new \Exception($hhaizokuIns['data']);
                }
            }
            $hsyainMstSel = $this->HDKLoginEdit->hsyainMstSel($postData);
            if (!$hsyainMstSel['result']) {
                throw new \Exception($hsyainMstSel['data']);
            }
            if ($hsyainMstSel['row'] > 0) {
                $hsyainMstUpd = $this->HDKLoginEdit->hsyainMstUpd($postData);
                if (!$hsyainMstUpd['result']) {
                    throw new \Exception($hsyainMstUpd['data']);
                }
            } else {
                $hsyainMstIns = $this->HDKLoginEdit->hsyainMstIns($postData);
                if (!$hsyainMstIns['result']) {
                    throw new \Exception($hsyainMstIns['data']);
                }
            }
            //コミット
            $this->HDKLoginEdit->Do_commit();
            $res['data']['tranStartFlg'] = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($res['data']['tranStartFlg']) {
                $this->HDKLoginEdit->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
}
