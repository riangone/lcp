<?php
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\HMTVE320HDTSYASYUList;
//*******************************************
// * sample controller
//*******************************************
class HMTVE320HDTSYASYUListController extends AppController
{
    public $autoLayout = TRUE;
    private $HMTVE320HDTSYASYUList;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMTVE');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMTVE320HDTSYASYUList_layout');
    }

    //検索ボタンのイベント
    public function btnSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HMTVE320HDTSYASYUList = new HMTVE320HDTSYASYUList();
                $result = $this->HMTVE320HDTSYASYUList->btnSearch_Click($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMTVE->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMTVE->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //データ削除のイベント
    public function deleteDataByCD()
    {
        $result = array(
            'result' => false,
            'error' => ''
        );
        try {
            $this->HMTVE320HDTSYASYUList = new HMTVE320HDTSYASYUList();
            $result = $this->HMTVE320HDTSYASYUList->deleteDataByCD($_POST['data']['syasyuCD']);
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
}
