<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDSKDList;

//*******************************************
// * sample controller
//*******************************************
class HMAUDSKDListController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }


    public function index()
    {
        $this->render('index', 'HMAUDSKDList_layout');
    }

    public function getListData()
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
                $HMAUDSKDList = new HMAUDSKDList();
                if ($sysCour == '') {
                    //検索条件・クールには 現在のクール数を初期表示
                    $cours = $HMAUDSKDList->getInitializeCour();
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
                $courres = $HMAUDSKDList->getCours($sysCour);
                if (!$courres['result']) {
                    throw new \Exception($courres['data']);
                }

                $courdata = $courres['data'];
                if (count((array) $courdata) == 0) {
                    throw new \Exception('W0024');
                }
                $courData = array();
                $headerData = array();
                $monthArr = array();

                $courData['cour1'] = $courdata[0]['COURS'];
                $courData['cour1_start_dt'] = $courdata[0]['START_DT'];
                $courData['cour1_end_dt'] = $courdata[0]['END_DT'];
                $headerData['COUR1_MONTH1'] = $this->getheadermonth($courdata[0]['MONTH1']);
                $headerData['COUR1_MONTH2'] = $this->getheadermonth($courdata[0]['MONTH2']);
                $headerData['COUR1_MONTH3'] = $this->getheadermonth($courdata[0]['MONTH3']);
                $headerData['COUR1_MONTH4'] = $this->getheadermonth($courdata[0]['MONTH4']);
                $headerData['COUR1_MONTH5'] = $this->getheadermonth($courdata[0]['MONTH5']);
                $headerData['COUR1_MONTH6'] = $this->getheadermonth($courdata[0]['MONTH6']);
                $monthArr[$courdata[0]['MONTH1']] = 'COUR1_MONTH1';
                $monthArr[$courdata[0]['MONTH2']] = 'COUR1_MONTH2';
                $monthArr[$courdata[0]['MONTH3']] = 'COUR1_MONTH3';
                $monthArr[$courdata[0]['MONTH4']] = 'COUR1_MONTH4';
                $monthArr[$courdata[0]['MONTH5']] = 'COUR1_MONTH5';
                $monthArr[$courdata[0]['MONTH6']] = 'COUR1_MONTH6';
                $cour1month = array(
                    $courdata[0]['MONTH1'],
                    $courdata[0]['MONTH2'],
                    $courdata[0]['MONTH3'],
                    $courdata[0]['MONTH4'],
                    $courdata[0]['MONTH5'],
                    $courdata[0]['MONTH6']
                );

                $coursStr = $courData['cour1'];

                $auditRes = $HMAUDSKDList->getAudit($coursStr);
                if (!$auditRes['result']) {
                    throw new \Exception($auditRes['data']);
                }
                if (count((array) $auditRes['data']) == 0) {
                    throw new \Exception('W0024');
                }

                $data = array();
                $kyotencd = '';
                $territory_ktn = '';
                $dataTmpArr = array(
                    'CHECK_DATETIME1' => "",
                    'CHECK_TIME1' => "",
                    'CHECK_DATETIME2' => "",
                    'CHECK_TIME2' => "",
                );
                $dataArr = $dataTmpArr;
                foreach ((array) $auditRes['data'] as $value) {
                    if ($kyotencd !== '' && $territory_ktn !== '') {
                        if ($kyotencd !== $value['KYOTEN_CD'] || $territory_ktn !== $value['TERRITORY_KTN']) {
                            array_push($data, $dataArr);
                            $dataArr = $dataTmpArr;
                        }
                    }
                    $kyotencd = $value['KYOTEN_CD'];
                    $territory_ktn = $value['TERRITORY_KTN'];
                    $dataArr['KYOTEN_CD'] = $value['KYOTEN_CD'];
                    $dataArr['KYOTEN_NAME'] = $value['KYOTEN_NAME'];
                    $dataArr['TERRITORY_KTN'] = $value['TERRITORY_KTN'];
                    $dataArr['TERRITORY_NM'] = $value['TERRITORY_NM'];
                    if ($value['COURS'] == $courData['cour1']) {
                        $dataArr['COURS1'] = $value['COURS'];
                        if ($value['MEMBERS'] !== null) {
                            $dataArr['CHECK_MEMBER1'] = $value['MEMBERS'];
                        }
                        if ($value['TERRITORY_KTN'] == $value['TERRITORY_MAIN']) {
                            $dataArr['CHECK_DATETIME1'] = $value['CHECK_DT'] ?: "";
                            $dataArr['CHECK_TIME1'] = $value['CHECK_TIME'] ?: "";
                            if ($value['PLAN_DT'] !== null && in_array($value['PLAN_DT'], $cour1month)) {
                                $dataArr[$monthArr[$value['PLAN_DT']]] = 'PLAN_DT';
                            }
                            if ($value['REPORT_LIMIT'] !== null && in_array($value['REPORT_LIMIT'], $cour1month)) {
                                $dataArr[$monthArr[$value['REPORT_LIMIT']]] = 'REPORT_LIMIT';
                            }
                            if ($value['KEY_PERSON_LIMIT'] !== null && in_array($value['KEY_PERSON_LIMIT'], $cour1month)) {
                                $dataArr[$monthArr[$value['KEY_PERSON_LIMIT']]] = 'KEY_PERSON_LIMIT';
                            }
                            if ($value['AUDIT_MEET_DT'] !== null && in_array($value['AUDIT_MEET_DT'], $cour1month)) {
                                $dataArr[$monthArr[$value['AUDIT_MEET_DT']]] = 'AUDIT_MEET_DT';
                            }
                        }

                    }
                }
                array_push($data, $dataArr);
                $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($data);
                // $sortstr = $tmpJqgridShow['sortStr'];
                $start = $tmpJqgridShow['start'];
                // $limit = $tmpJqgridShow['limit'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMAUD->FncCreateJqGridDataReload($data, $totalPage, $page, $tmpCount, $start);
                $result->headerData = $headerData;
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

    public function getheadermonth($value)
    {
        return substr($value, 0, 4) . '年' . substr($value, 5, 2) . '月';
    }

    public function getRiyouListData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $y = $_POST['request']['y'];
                $m = $_POST['request']['m'];
                $ym = $y . $m;
                $HMAUDSKDList = new HMAUDSKDList();

                $auditors = $HMAUDSKDList->fncSelectMSTdata();
                if (!$auditors['result']) {
                    throw new \Exception($auditors['data']);
                }
                if (count((array) $auditors['data']) == 0) {
                    throw new \Exception('W0024');
                }

                $resRiyouAm = $HMAUDSKDList->getRiyouAmList($ym, '1');
                if (!$resRiyouAm['result']) {
                    throw new \Exception($resRiyouAm['data']);
                }
                $resRiyouAmData = $resRiyouAm['data'];
                $resRiyouPm = $HMAUDSKDList->getRiyouAmList($ym, '2');
                if (!$resRiyouPm['result']) {
                    throw new \Exception($resRiyouPm['data']);
                }
                $resRiyouPmData = $resRiyouPm['data'];


                $data = $this->makeRiyouData($auditors['data'], $resRiyouAmData, $resRiyouPmData, $y, $m);

                $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($data);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMAUD->FncCreateJqGridDataReload($data, $totalPage, $page, $tmpCount, $start);
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function makeRiyouData($auditors, $resRiyouAmData, $resRiyouPmData, $y, $m)
    {
        $data = array();
        $amArr = array_column($resRiyouAmData, 'ENABLED', 'SYAINDT');
        $pmArr = array_column($resRiyouPmData, 'ENABLED', 'SYAINDT');
        $date = new \DateTime("$y-$m-01");
        $days = [];
        while ($date->format("m") == $m) {
            $days[] = $date->format("Y-m-d");
            $date->modify("+1 day");
        }
        foreach ((array) $auditors as $auditor) {
            $dataTmpArr = array(
                'AUDITOR_NAME' => $auditor['SYAIN_NAME'],
                'AMPM' => "AM",
            );
            foreach ($days as $day) {
                $dataTmpArr[$day] = isset($amArr[$auditor['SYAIN_NO'] . $day]) ? $amArr[$auditor['SYAIN_NO'] . $day] == '1' ? '〇' : '×' : '';
            }
            array_push($data, $dataTmpArr);
            $dataTmpArr = array(
                'AUDITOR_NAME' => "",
                'AMPM' => "PM",
            );
            foreach ($days as $day) {
                $dataTmpArr[$day] = isset($pmArr[$auditor['SYAIN_NO'] . $day]) ? $pmArr[$auditor['SYAIN_NO'] . $day] == '1' ? '〇' : '×' : '';
            }
            array_push($data, $dataTmpArr);
        }
        return $data;
    }

}