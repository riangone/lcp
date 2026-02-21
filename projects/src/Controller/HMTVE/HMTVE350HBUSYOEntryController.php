<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE350HBUSYOEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE350HBUSYOEntryController extends AppController
{
    public $autoLayout = TRUE;
    private $HMTVE350HBUSYOEntry;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE350HBUSYOEntry_layout');
    }

    //フォーム初期化
    public function fncFormload()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            $postData = $_POST['data'];

            $this->HMTVE350HBUSYOEntry = new HMTVE350HBUSYOEntry();

            $result = $this->HMTVE350HBUSYOEntry->fncFormload($postData);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //登録
    public function btnLoginClick()
    {
        $this->HMTVE350HBUSYOEntry = new HMTVE350HBUSYOEntry();
        $result = array(
            'result' => false,
            'data' => '',
            'error' => ''
        );
        try {
            $postData = $_POST['data'];

            //存在チェック
            $resultDataChk = $this->HMTVE350HBUSYOEntry->HbusyoEntryDataChk($postData);

            if (!$resultDataChk['result']) {
                throw new \Exception($resultDataChk['data']);
            }

            if ($resultDataChk['row'] <= 0) {
                throw new \Exception('W0025');
            }

            $resultDataUpd = $this->HMTVE350HBUSYOEntry->HbusyoEntryDataUpd($postData);

            if (!$resultDataUpd['result']) {
                throw new \Exception($resultDataUpd['data']);
            }

            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
}
