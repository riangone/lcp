<?php
namespace App\Controller\HDKAIKEI;
use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKSyainMstEdit;
//*******************************************
// * sample controller
//*******************************************
class HDKSyainMstEditController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    public $HDKSyainMstEdit = null;
    public $ClsComFncHDKAIKEI = null;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        // Viewファイル呼出し
        $this->render('index', 'HDKSyainMstEdit_layout');
    }
    public function fncLoginBtnClick()
    {
        $this->HDKSyainMstEdit = new HDKSyainMstEdit();
        $res = [
            'result' => false,
            'data' => [
                'tranStartFlg' => false
            ],
            'error' => ''
        ];
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $postData = $_POST['data'];

            //トランザクション開始
            $this->HDKSyainMstEdit->Do_transaction();
            $res['data']['tranStartFlg'] = TRUE;

            //下記３テーブルにデータを登録
            $del1 = $this->HDKSyainMstEdit->userDataDel('M_LOGIN', $postData['SYAIN_NO']);
            if (!$del1['result']) {
                throw new \Exception($del1['data']);
            }
            $del2 = $this->HDKSyainMstEdit->userDataDel('HSYAINMST', $postData['SYAIN_NO']);
            if (!$del2['result']) {
                throw new \Exception($del2['data']);
            }
            $del3 = $this->HDKSyainMstEdit->userDataDel('HHAIZOKU', $postData['SYAIN_NO']);
            if (!$del3['result']) {
                throw new \Exception($del3['data']);
            }
            $params = $postData;
            $params['SYS_KB'] = HDKAIKEIController::SYS_KB;
            $params['STYLE_ID'] = HDKAIKEIController::STYLE_ID;
            $params['SYSDATE'] = date('Y-m-d H:i:s');
            $ins1 = $this->HDKSyainMstEdit->mLoginIns($params);
            if (!$ins1['result']) {
                throw new \Exception($ins1['data']);
            }
            $ins2 = $this->HDKSyainMstEdit->hsyainmstIns($params);
            if (!$ins2['result']) {
                throw new \Exception($ins2['data']);
            }
            $params['DATEYMD'] = date_format(date_create($params['SYSDATE']), 'Ymd');
            $ins3 = $this->HDKSyainMstEdit->hhaizokuIns($params);
            if (!$ins3['result']) {
                throw new \Exception($ins3['data']);
            }
            //コミット
            $this->HDKSyainMstEdit->Do_commit();
            $res['data']['tranStartFlg'] = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($res['data']['tranStartFlg']) {
                $this->HDKSyainMstEdit->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
}
