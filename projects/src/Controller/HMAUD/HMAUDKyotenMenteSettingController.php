<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKyotenMenteSetting;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKyotenMenteSettingController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDKyotenMenteSetting;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }


    public function index()
    {
        $this->render('index', 'HMAUDKyotenMenteSetting_layout');
    }
    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            $postData = $_POST['data'];

            $this->HMAUDKyotenMenteSetting = new HMAUDKyotenMenteSetting();

            $result = $this->HMAUDKyotenMenteSetting->getdata($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $GetSyainMstValue = $this->HMAUDKyotenMenteSetting->FncGetSyainMstValue();

            if (!$GetSyainMstValue['result']) {
                throw new \Exception($GetSyainMstValue['data']);
            }
            $result['data']['GetSyainMstValue'] = $GetSyainMstValue['data'];
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function fncUpdataMst()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            if (isset($_POST['data'])) {
                $postdata = $_POST['data'];
                $this->HMAUDKyotenMenteSetting = new HMAUDKyotenMenteSetting();

                $this->HMAUDKyotenMenteSetting->Do_transaction();
                $blnTran = TRUE;
                $result = $this->HMAUDKyotenMenteSetting->MST_KTNUpd($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                //コミット処理を行う
                $this->HMAUDKyotenMenteSetting->Do_commit();
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMAUDKyotenMenteSetting->Do_rollback();
            }
        }
        $result['data'] = '';
        $this->fncReturn($result);
    }

}
