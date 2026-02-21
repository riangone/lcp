<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE380MLOGINEntry;
use App\Controller\HMTVE\HMTVEController;
//*******************************************
// * sample controller
//*******************************************
class HMTVE380MLOGINEntryController extends AppController
{
    public $autoLayout = TRUE;
    private $HMTVE380MLOGINEntry;
    public $components = array('RequestHandler');
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE380MLOGINEntry_layout');
    }

    //ページロード
    public function pageLoad()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $this->HMTVE380MLOGINEntry = new HMTVE380MLOGINEntry();
            //権限のコンボリストのデータソースを取得する
            $pattern = $this->HMTVE380MLOGINEntry->ddlSearch(HMTVEController::SYS_KB);
            if (!$pattern['result']) {
                throw new \Exception($pattern['data']);
            }
            $result['data']['pattern'] = $pattern['data'];
            $postdata = $_POST['data'];
            //ﾛｸﾞｲﾝ情報データを取得する　
            $user = $this->HMTVE380MLOGINEntry->userSearch(HMTVEController::SYS_KB, $postdata['USERID']);
            if (!$user['result']) {
                throw new \Exception($user['data']);
            }
            $result['data']['user'] = $user['data'];
            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //登録ボタンのイベント
    public function btnLoginClick()
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
                $this->HMTVE380MLOGINEntry = new HMTVE380MLOGINEntry();
                $this->HMTVE380MLOGINEntry->Do_transaction();
                $blnTran = TRUE;
                $delResult = $this->HMTVE380MLOGINEntry->mLoginDelete(HMTVEController::SYS_KB, $postdata['USERID']);
                if (!$delResult['result']) {
                    throw new \Exception($delResult['data']);
                }
                $result = $this->HMTVE380MLOGINEntry->mLoginInsert(HMTVEController::SYS_KB, $postdata['USERID'], $postdata['PASSWORD'], $postdata['PATTERNID'], $postdata['RECUPDDT']);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                //コミット処理を行う
                $this->HMTVE380MLOGINEntry->Do_commit();
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();

            if ($blnTran) {
                $this->HMTVE380MLOGINEntry->Do_rollback();
            }
        }
        $result['data'] = '';

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

}
