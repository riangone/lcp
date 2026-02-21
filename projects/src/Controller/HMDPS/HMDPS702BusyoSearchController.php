<?php
namespace App\Controller\HMDPS;

use App\Controller\AppController;
use App\Model\HMDPS\HMDPS702BusyoSearch;

//*******************************************
// * sample controller
//*******************************************
class HMDPS702BusyoSearchController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $HMDPS702BusyoSearch;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMDPS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->render('index', 'HMDPS702BusyoSearch_layout');
    }

    public function btnViewClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HMDPS702BusyoSearch = new HMDPS702BusyoSearch();

                $result = $this->HMDPS702BusyoSearch->btnView_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMDPS->FncCreateJqGridShow($result['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMDPS->FncCreateJqGridDataIndex($result["data"], $totalPage, $page, $tmpCount);
                $result = $tmpJqgrid;
            }

        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

}
