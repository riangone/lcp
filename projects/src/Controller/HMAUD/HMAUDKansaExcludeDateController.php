<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKansaExcludeDate;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKansaExcludeDateController extends AppController
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
        $this->render('index', 'HMAUDKansaExcludeDate_layout');
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
                $HMAUDKansaExcludeDate = new HMAUDKansaExcludeDate();
                if ($sysCour == '') {
                    //検索条件・クールには 現在のクール数を初期表示
                    $cours = $HMAUDKansaExcludeDate->getInitializeCour();
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
                $courres = $HMAUDKansaExcludeDate->getCours($sysCour);
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

                $exclude_date = $HMAUDKansaExcludeDate->getExclude($courData['cour1_start_dt'], $courData['cour1_end_dt']);
                if (!$exclude_date['result']) {
                    throw new \Exception($exclude_date['data']);
                }

                $data = $exclude_date['data'];
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
                $HMAUDKansaExcludeDate = new HMAUDKansaExcludeDate();

                $HMAUDKansaExcludeDate->Do_transaction();
                $blnTran = TRUE;

                $excludeDelRes = $HMAUDKansaExcludeDate->excludeDel($postData['startDate'], $postData['endDate']);
                if (!$excludeDelRes['result']) {
                    throw new \Exception($excludeDelRes['data']);
                }
                for ($i = 0; $i < count($insData); $i++) {
                    if ($insData[$i]['EXCLUDE_DT'] !== "") {
                        $insertDataRes = $HMAUDKansaExcludeDate->insertData($insData[$i]);
                        if (!$insertDataRes['result']) {
                            throw new \Exception($insertDataRes['data']);
                        }
                    }
                }

                $HMAUDKansaExcludeDate->Do_commit();
                $result['result'] = true;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $HMAUDKansaExcludeDate->Do_rollback();
            }
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }

}