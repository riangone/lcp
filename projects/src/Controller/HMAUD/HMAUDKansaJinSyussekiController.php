<?php
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDKansaJinSyusseki;

//*******************************************
// * sample controller
//*******************************************
class HMAUDKansaJinSyussekiController extends AppController
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
        $this->render('index', 'HMAUDKansaJinSyusseki_layout');
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
                // $sysCour = '';
                $ymDate = $_POST['request']['ymDate'];
                $HMAUDKansaJinSyusseki = new HMAUDKansaJinSyusseki();

                $selRes = $HMAUDKansaJinSyusseki->getCours($ymDate);
                if (!$selRes['result']) {
                    throw new \Exception($selRes['data']);
                }
                if ($selRes['row'] === 0) {
                    throw new \Exception('指定された年月はクール設定期間外です。');
                }

                $admin = $HMAUDKansaJinSyusseki->getAdmin();
                if (!$admin['result']) {
                    throw new \Exception($admin['data']);
                }

                $schedule_datas = $HMAUDKansaJinSyusseki->getSchedule($ymDate);
                if (!$schedule_datas['result']) {
                    throw new \Exception($schedule_datas['data']);
                }

                $exclude_datas = $HMAUDKansaJinSyusseki->getExcludeDt($ymDate);
                if (!$exclude_datas['result']) {
                    throw new \Exception($exclude_datas['data']);
                }
                $data = $schedule_datas['data'];
                if (count((array) $data) == 0) {
                    throw new \Exception('W0024');
                }

                $formattedData = $this->formatSchedule($schedule_datas['data'], $ymDate);
                $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($formattedData);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $result = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($formattedData, $totalPage, $page, $tmpCount, $start);
                $result->exclude = $exclude_datas['data'];
                $result->admin = $admin['data'];
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }
    public function formatSchedule($scheduleData, $ymDate)
    {
        $year = intval(substr($ymDate, 0, 4));
        $month = intval(substr($ymDate, 4, 2));
        $lastDay = date("t", strtotime("$year-$month-01"));

        $formatted = [];
        $grouped = [];
        foreach ($scheduleData as $row) {
            $grouped[$row['SYAIN_NO']][] = $row;
        }

        foreach ($grouped as $rows) {
            $base = [
                "SYAIN_NO" => $rows[0]["SYAIN_NO"],
                "SYAIN_NAME" => $rows[0]["SYAIN_NAME"],
                "SEQ" => $rows[0]["SEQ"],
                "LIMIT_DT" => $rows[0]["LIMIT_DT"]
                    ? date("Y/m/d", strtotime($rows[0]["LIMIT_DT"]))
                    : null,
            ];

            foreach (["AM", "PM"] as $am_pm) {
                $item = $base;
                $item["AM_PM"] = $am_pm;

                for ($d = 1; $d <= $lastDay; $d++) {
                    $item["d" . $d] = null;
                }

                foreach ($rows as $row) {
                    if ($row["AM_PM"] === $am_pm && !empty($row["PLAN_DT"]) && $row["ENABLED"] !== null) {
                        $day = intval(date("j", strtotime($row["PLAN_DT"])));
                        $item["d" . $day] = $row["ENABLED"] === "1" ? 1 : 0;
                    }
                }

                $formatted[] = $item;
            }
        }

        return $formatted;
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
                $ymDate = $postData['ymDate'];

                $HMAUDKansaJinSyusseki = new HMAUDKansaJinSyusseki();

                $selRes = $HMAUDKansaJinSyusseki->getCours($ymDate);
                if (!$selRes['result']) {
                    throw new \Exception($selRes['data']);
                }
                $cours = $selRes['data'][0]['COURS'];

                $HMAUDKansaJinSyusseki->Do_transaction();
                $blnTran = TRUE;

                $params = [
                    'syainNo' => $postData['syainNo'],
                    'limitDate' => $postData['limitDate'] === '' ? $ymDate : $postData['limitDate'],
                    'ymDate' => $postData['ymDate'],
                    'oldDate' => isset($postData['oldDate']) ? $postData['oldDate'] : null,
                ];
                $selLimitRes = $HMAUDKansaJinSyusseki->selLimit($cours, $params);
                if (!$selLimitRes['result']) {
                    throw new \Exception($selLimitRes['data']);
                }

                if ($selLimitRes['row'] > 0) {
                    if ($postData['limitDate'] == '') {
                        $delLimitRes = $HMAUDKansaJinSyusseki->delLimit($cours, $params);
                        if (!$delLimitRes['result']) {
                            throw new \Exception($delLimitRes['data']);
                        }
                    } else {
                        $updLimitRes = $HMAUDKansaJinSyusseki->updLimit($cours, $params);
                        if (!$updLimitRes['result']) {
                            throw new \Exception($updLimitRes['data']);
                        }
                    }
                } else {
                    $insLimitRes = $HMAUDKansaJinSyusseki->insLimit($cours, $params);
                    if (!$insLimitRes['result']) {
                        throw new \Exception($insLimitRes['data']);
                    }
                }
                $HMAUDKansaJinSyusseki->Do_commit();
                $result['result'] = true;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $HMAUDKansaJinSyusseki->Do_rollback();
            }
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }
    public function updScheduleData()
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
                $ymDate = $postData['ymDate'];

                $HMAUDKansaJinSyusseki = new HMAUDKansaJinSyusseki();

                $selRes = $HMAUDKansaJinSyusseki->getCours($ymDate);
                if (!$selRes['result']) {
                    throw new \Exception($selRes['data']);
                }
                $cours = $selRes['data'][0]['COURS'];

                $HMAUDKansaJinSyusseki->Do_transaction();
                $blnTran = TRUE;

                $params = [
                    'syainNo' => $postData['syainNo'],
                    'planDt' => $postData['planDt'],
                    'am_pm' => $postData['am_pm'],
                    'enabled' => isset($postData['enabled']) ? $postData['enabled'] : null,
                ];
                $selScheduleRes = $HMAUDKansaJinSyusseki->selAuditSchedule($cours, $params);
                if (!$selScheduleRes['result']) {
                    throw new \Exception($selScheduleRes['data']);
                }

                if ($selScheduleRes['row'] > 0) {
                    if (!isset($postData['enabled'])) {
                        $delRes = $HMAUDKansaJinSyusseki->delAuditSchedule($cours, $params);
                        if (!$delRes['result']) {
                            throw new \Exception($delRes['data']);

                        }
                    } else {
                        $updRes = $HMAUDKansaJinSyusseki->updAuditSchedule($cours, $params);
                        if (!$updRes['result']) {
                            throw new \Exception($updRes['data']);
                        }
                    }
                } else {
                    $insRes = $HMAUDKansaJinSyusseki->insAuditSchedule($cours, $params);
                    if (!$insRes['result']) {
                        throw new \Exception($insRes['data']);
                    }
                }
                $HMAUDKansaJinSyusseki->Do_commit();
                $result['result'] = true;
            }

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $HMAUDKansaJinSyusseki->Do_rollback();
            }
        }
        $result['data'] = '';

        $this->fncReturn($result);
    }
}