<?php
namespace App\Controller\JKSYS;

use App\Controller\AppController;
use App\Model\JKSYS\FrmShimeProc;

//*******************************************
// * sample controller
//*******************************************
class FrmShimeProcController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
    }

    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'FrmShimeProc_layout');
    }

    //処理年月を表示する
    public function subDispSyoriYM()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            $FrmShimeProc = new FrmShimeProc();
            $result = $FrmShimeProc->subDispSyoriYM();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function btnUpdateClick()
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );

        try {
            $FrmShimeProc = new FrmShimeProc();
            $result = $FrmShimeProc->btnUpdate_Click();
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $result['data'] = "";
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
