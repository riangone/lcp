<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDSKDScheduleLimit;

//*******************************************
// * sample controller
//*******************************************
class HMAUDSKDScheduleLimitController extends AppController
{
    public $autoLayout = TRUE;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMAUD');
    }

    //    ***********************************************************************
    //    '処 理 名：初期表示
    //    '関 数 名：index
    //    '引    数：無し
    //    '戻 り 値 ：無し
    //    '処理説明 ：
    //    '**********************************************************************

    public function index()
    {
        $this->render('index', 'HMAUDSKDScheduleLimit_layout');
    }

    public function pageload()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $sysCour = '';
                $sysCour = $_POST['request']['cour'];
                $ymDate = $_POST['request']['ymDate'];
                $HMAUDSKDScheduleLimit = new HMAUDSKDScheduleLimit();
                if ($sysCour == '') {
                    //検索条件・クールには 現在のクール数を初期表示
                    $cours = $HMAUDSKDScheduleLimit->getInitializeCour();
                    if (!$cours['result']) {
                        throw new \Exception($cours['data']);
                    }
                    if (count((array) $cours['data']) == 0) {
                        throw new \Exception('W0024');
                    }
                    $result['cour'] = $cours['data'];
                    foreach ((array) $cours['data'] as $value) {
                        if ($value['COURS_NOW'] == 1) {
                            $sysCour = $value['COURS'];
                        }
                    }
                    if ($sysCour == '') {
                        throw new \Exception('W0024NOTNOW');
                    }
                }
                $courres = $HMAUDSKDScheduleLimit->getCours($sysCour);
                if (!$courres['result']) {
                    throw new \Exception($courres['data']);
                }

                $courdata = $courres['data'];
                if (count((array) $courdata) == 0) {
                    throw new \Exception('W0024');
                }
                $courData = array();

                $courData['cour1'] = $courdata[0]['COURS'];
                $courData['cour1_start_dt'] = $courdata[0]['START_DT'];
                $courData['cour1_end_dt'] = $courdata[0]['END_DT'];

                $schedule_limit = $HMAUDSKDScheduleLimit->getSeLimit($ymDate);

                if (!$schedule_limit['result']) {
                    throw new \Exception($schedule_limit['data']);
                }

                $data = $schedule_limit['data'];
                if (count((array) $data) == 0) {
                    throw new \Exception('W0024');
                }

                $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($data);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($data, $totalPage, $page, $tmpCount, $start);
                $result->courData = $courData;
                if (isset($cours)) {
                    $result->cour = $cours['data'];
                }
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    public function updData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );

        try {
            //データの取得
            if (isset($_POST['data'])) {
                $postData = $_POST['data'];
                $insData = $postData['data'];
                $HMAUDSKDScheduleLimit = new HMAUDSKDScheduleLimit();

                $HMAUDSKDScheduleLimit->Do_transaction();
                $blnTran = TRUE;

                for ($i = 0; $i < count($insData); $i++) {
                    if ($insData[$i]['LIMIT_DT'] !== "") {
                        $selectDataRes = $HMAUDSKDScheduleLimit->selectData($postData['cour'], $insData[$i]['SYAIN_NO'], $insData[$i]['LIMIT_DT']);
                        if ($selectDataRes['row'] > 0) {
                            $updDataRes = $HMAUDSKDScheduleLimit->updData($postData['cour'], $selectDataRes['data'][0]['PLAN_DT'], $insData[$i]);
                            if (!$updDataRes['result']) {
                                throw new \Exception($updDataRes['data']);
                            }
                        } else {

                            $insertDataRes = $HMAUDSKDScheduleLimit->insData($postData['cour'], $insData[$i]);
                            if (!$insertDataRes['result']) {
                                throw new \Exception($insertDataRes['data']);
                            }
                        }
                    } else {
                        $selDataRes = $HMAUDSKDScheduleLimit->selectData($postData['cour'], $insData[$i]['SYAIN_NO'], $postData['ym']);
                        if ($selDataRes['row'] > 0) {
                            $delDataRes = $HMAUDSKDScheduleLimit->delData($postData['cour'], $selDataRes['data'][0]['PLAN_DT'], $insData[$i]);
                            if (!$delDataRes['result']) {
                                throw new \Exception($delDataRes['data']);
                            }
                        }
                    }

                }

                $HMAUDSKDScheduleLimit->Do_commit();
                $result['result'] = true;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $HMAUDSKDScheduleLimit->Do_rollback();
            }
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

}