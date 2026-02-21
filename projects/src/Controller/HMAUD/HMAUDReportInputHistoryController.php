<?php

namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDReportInputHistory;

//*******************************************
// * sample controller
//*******************************************
class HMAUDReportInputHistoryController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDReportInputHistory;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');


    }


    public function index()
    {
        $this->render('index', 'HMAUDReportInputHistory_layout');
    }
    public function pageLoad()
    {
        $this->HMAUDReportInputHistory = new HMAUDReportInputHistory();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['request'])) {
                throw new \Exception('param error');
            }
            $mainData = $this->HMAUDReportInputHistory->getMainData($_POST['request']);
            if (!$mainData['result']) {
                throw new \Exception($mainData['data']);
            }
            $historyData = $this->HMAUDReportInputHistory->getHistoryData($_POST['request']);
            if (!$historyData['result']) {
                throw new \Exception($historyData['data']);
            }
            $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($historyData['data']);
            $page = $tmpJqgridShow['page'];
            $totalPage = $tmpJqgridShow['totalPage'];
            $tmpCount = $tmpJqgridShow['count'];
            $res = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($historyData['data'], $totalPage, $page, $tmpCount);
            $res->mainData = $mainData['data'];

        } catch (\Exception $e) {
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

}
