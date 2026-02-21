<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE330HDTSYASYUEntry;
//*******************************************
// * sample controller
//*******************************************
class HMTVE330HDTSYASYUEntryController extends AppController
{
    public $autoLayout = TRUE;
    private $HMTVE330HDTSYASYUEntry;

    public function initialize(): void
    {
        parent::initialize();
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE330HDTSYASYUEntry_layout');
    }
    //登録ボタンのイベント
    public function btnLoginClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->HMTVE330HDTSYASYUEntry = new HMTVE330HDTSYASYUEntry();
            $postdata = $_POST['data'];
            $result = $this->HMTVE330HDTSYASYUEntry->getSqlCheck($postdata['SYASYU_CD']);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            } elseif ($postdata['MODE'] == '2' && count((array) $result['data']) == 0) {
                //他のユーザーにより更新されています。最新の情報を確認してください。
                throw new \Exception("W0025");
            } elseif (($postdata['MODE'] == '1' || $postdata['MODE'] == '') && count((array) $result['data']) > 0) {
                //既に登録されています。
                throw new \Exception("E0016");
            }
            //車種マスタの更新処理を行う
            $postdata['UPD_PRG_ID'] = 'HDTSYASYUEntry';
            if (count((array) $result['data']) == 0) {
                $result = $this->HMTVE330HDTSYASYUEntry->insertHDTSYASYU($postdata);
            } else {
                $result = $this->HMTVE330HDTSYASYUEntry->updateHDTSYASYU($postdata);
            }
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }
        $result['data'] = '';

        // Viewファイル呼出し
        $this->fncReturn($result);
    }

    //画面初期化データ取得
    public function updateData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $this->HMTVE330HDTSYASYUEntry = new HMTVE330HDTSYASYUEntry();
            $SYASYU_CD = $_POST['data']['SYASYU_CD'];
            $result = $this->HMTVE330HDTSYASYUEntry->updateData($SYASYU_CD);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
}
