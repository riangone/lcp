<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                         担当
 * YYYYMMDD           #ID                       XXXXXX                                       FCSDL
 * 20241030           202410_内部統制システム_集計機能改善対応.xlsx                             caina
 * 20250219           20250219_内部統制_改修要望.xlsx                                         caina
 * --------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDJissekiPDFOutput;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as PhpSpreadsheetXlsx;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

//*******************************************
// * sample controller
//*******************************************
class HMAUDJissekiPDFOutputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDJissekiPDFOutput;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HMAUDJissekiPDFOutput_layout');
    }

    public function pageLoad()
    {
        $this->HMAUDJissekiPDFOutput = new HMAUDJissekiPDFOutput();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            //検索条件・クールには 現在のクール数を初期表示
            $cour = $this->HMAUDJissekiPDFOutput->getInitializeCour();
            if (!$cour['result']) {
                throw new \Exception($cour['data']);
            }
            //20230314 LIU INS S
            $viewer = $this->HMAUDJissekiPDFOutput->getViewer();
            if (!$viewer['result']) {
                throw new \Exception($viewer['data']);
            }
            $res['data']['viewer'] = $viewer['data'];
            //20230314 LIU INS E

            $res['data']['cour'] = $cour['data'];

            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    public function courChange()
    {
        $this->HMAUDJissekiPDFOutput = new HMAUDJissekiPDFOutput();
        $res = array(
            'result' => FALSE,
            'data' => array(),
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $cour = $_POST['data']['COUR'];
                //スケジュールに登録されている人
                $member = $this->HMAUDJissekiPDFOutput->getmember($cour);
                if (!$member['result']) {
                    throw new \Exception($member['data']);
                }
                //監査スケジュールに登録されていないが管理者マスタに登録済のユーザが ボタンを操作できるようにしてほしい
                $manager = $this->HMAUDJissekiPDFOutput->getManager();
                if (!$manager['result']) {
                    throw new \Exception($manager['data']);
                }
                $res['data']['member'] = $member['row'] > 0 ? $member['data'] : $manager['data'];
                $res['result'] = true;
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    public function getData($param, $flg)
    {
        $this->HMAUDJissekiPDFOutput = new HMAUDJissekiPDFOutput();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $cour = $param['COUR'];
            $TERRITORYArr = $param['TERRITORYArr'];
            // 20241030 caina ins s
            $summery = $param['SUMMERY'];
            // 20241030 caina ins e
            $pdfDT = array();
            $allData = array();
            $nodata_kyoten = true;
            $nodata_detail = true;
            //領域
            for ($index = 0; $index < count($TERRITORYArr); $index++) {
                $territory = $TERRITORYArr[$index];
                $territory_name = '';
                // 20241030 YIN INS S
                $territory_color = '';
                // 20241030 YIN INS E
                if ($territory == 1) {
                    $territory_name = '営業';
                    // 20241030 YIN INS S
                    $territory_color = '#DDEBF7';
                    // 20241030 YIN INS E
                } else
                    if ($territory == 2) {
                        $territory_name = 'サービス';
                        // 20241030 YIN INS S
                        $territory_color = '#FCE4D6';
                        // 20241030 YIN INS E
                    } else
                        if ($territory == 3) {
                            $territory_name = '管理';
                            // 20241030 YIN INS S
                            $territory_color = '#E2EFDA';
                            // 20241030 YIN INS E
                        } else
                            if ($territory == 4) {
                                $territory_name = '業売';
                            } else
                                if ($territory == 5) {
                                    $territory_name = '業売管理';
                                }
                                // 20250219 caina ins s
                                else
                                    if ($territory == 6) {
                                        $territory_name = 'カーセブン';
                                    }
                // 20250219 caina ins e
                //各領域のデータ
                $each_territory_data = array();
                //拠点
                $kyoten = $this->HMAUDJissekiPDFOutput->getTitleKyoten($territory);
                if (!$kyoten['result']) {
                    throw new \Exception($kyoten['data']);
                }
                if ($kyoten['row'] > 0) {
                    $nodata_kyoten = false;
                }
                //ID、手順書項目、業務内容、監査項目
                $detail = $this->HMAUDJissekiPDFOutput->getDetail($territory, $cour);
                if (!$detail['result']) {
                    throw new \Exception($detail['data']);
                }
                if ($detail['row'] > 0) {
                    $nodata_detail = false;
                }
                // 20241030 caina ins s
                if ($summery === 'consecutive_issue_table' || $summery === 'cumulative_issue_table') {
                    // 20241030 caina ins e
                    if ($kyoten['row'] > 0 && $detail['row'] > 0) {
                        //拠点title
                        $headerKyoten = array();
                        for ($j = 0; $j < count((array) $kyoten['data']); $j++) {
                            array_push($headerKyoten, $kyoten['data'][$j]["KYOTEN_NAME"]);
                        }
                        $excelheaderKyoten = $headerKyoten;
                        array_push($headerKyoten, '合計');
                        array_push($headerKyoten, '実施度合**（全' . $kyoten['row'] . '拠点）');
                        array_push($headerKyoten, 'ランク**（項目）');
                        //detail
                        $footer = array();
                        for ($i = 0; $i < count((array) $detail['data']); $i++) {
                            //合計
                            $total = 0;
                            for ($k = 0; $k < count((array) $kyoten['data']); $k++) {
                                //footer
                                if ($i == 0) {
                                    $footer[$k]['total'] = 0;
                                }
                                //指摘回数
                                $params = array(
                                    'COURS' => $cour,
                                    'TERRITORY' => $territory,
                                    'KYOTEN_CD' => $kyoten['data'][$k]["KYOTEN_CD"],
                                    'ROW_NO' => $detail['data'][$i]["ROW_NO"],
                                    //20241030 caina ins s
                                    'SUMMERY' => $summery,
                                    //20241030 caina ins e
                                );
                                //20241030 caina upd s
                                // $num = $this->HMAUDJissekiPDFOutput->chkrowno($params);
                                $num = $summery === 'consecutive_issue_table'
                                    ? $this->HMAUDJissekiPDFOutput->chkrownoContinuity($params)
                                    : $this->HMAUDJissekiPDFOutput->chkrowno($params);
                                //20241030 caina upd e
                                //20241030 caina ins s
                                if (!$num['result']) {
                                    throw new \Exception($num['data']);
                                }
                                $count = 0;
                                if ($summery === 'consecutive_issue_table' && isset($num['data']) && count((array) $num['data']) >= 2) {
                                    if ($num['data'][0]['COURS'] !== $cour) {
                                        $num['data'][0]['COUNT'] = 0;
                                    } else {
                                        foreach ((array) $num['data'] as $j => $data) {
                                            if ($num['data'][1]['COURS'] != $cour - 1) {
                                                continue;
                                            }
                                            if (
                                                isset($num['data'][$j + 1]) &&
                                                $data['MEMBER'] === $num['data'][$j + 1]['MEMBER'] &&
                                                $data['COURS'] == $num['data'][$j + 1]['COURS'] + 1
                                            ) {
                                                $count++;
                                            }
                                            if ($data['COURS'] == '8') {
                                                break;
                                            }
                                        }
                                        $num['data'][0]['COUNT'] = $count >= 1 ? $count + 1 : 0;
                                        $num['data'][0]['COUNT'] = $num['data'][0]['COUNT'] == 1 ? 0 : $num['data'][0]['COUNT'];
                                    }
                                }
                                //20241030 caina ins e
                                $key = 'check' . $k;
                                // $each_territory_data[$i][$key] = $num['data'][0]['COUNT'];
                                $each_territory_data[$i][$key] = isset($num['data'][0]['COUNT']) ? $num['data'][0]['COUNT'] : NULL;
                                //20241030 caina upd s
                                // if ($num['data'][0]['COUNT'] > 0) {
                                if (isset($num['data'][0]['COUNT']) && $num['data'][0]['COUNT'] > 0) {
                                    // 20241030 caina upd e
                                    $total++;
                                    $footer[$k]['total'] = $footer[$k]['total'] + 1;
                                }
                                //footer
                                if ($i == count((array) $detail['data']) - 1) {
                                    $footer[$k]['rank_cal'] = $detail['row'] - $footer[$k]['total'];
                                    $footer[$k]['percent'] = number_format(($detail['row'] - $footer[$k]['total']) / $detail['row'] * 100, 1) . '%';
                                    $footer[$k]['rank'] = '';
                                }
                            }
                            $each_territory_data[$i]["COUR"] = $cour;
                            $each_territory_data[$i]["TERRITORY"] = $territory_name;
                            //PDF出力結果をできるだけ画面に合わせたいので、画面で連番表示しているIDの値をPDFに出力してもらいたいです
                            //COLUMN1->ROW_NO
                            $each_territory_data[$i]["COLUMN1"] = $detail['data'][$i]['ROW_NO'];
                            $each_territory_data[$i]["COLUMN2"] = $detail['data'][$i]['COLUMN2'];
                            $each_territory_data[$i]["COLUMN4"] = $detail['data'][$i]['COLUMN4'];
                            $each_territory_data[$i]["COLUMN7"] = $detail['data'][$i]['COLUMN7'];
                            if ($i == 0) {
                                $each_territory_data[$i]["header"] = $headerKyoten;
                            }
                            $each_territory_data[$i]['total'] = $total;
                            $each_territory_data[$i]['rank_cal'] = $kyoten['row'] - $total;
                            $each_territory_data[$i]['percent'] = number_format(($kyoten['row'] - $total) / $kyoten['row'] * 100, 1) . '%';
                            $each_territory_data[$i]['rank'] = '';
                            $each_territory_data[$i]['excelheaderKyoten'] = $excelheaderKyoten;
                        }
                        $etdRes = $this->fncRank($each_territory_data, 'rank_cal', 'rank');
                        $each_territory_data = $etdRes['rankdata'];
                        array_push($allData, $each_territory_data);
                        $pdfDT[$index]['color']['kyoten_first'] = $etdRes['first'];
                        $pdfDT[$index]['color']['kyoten_second'] = $etdRes['second'];
                        $pdfDT[$index]['data'] = $each_territory_data;
                        $pdfDT[$index]['COUR'] = $cour;
                        $pdfDT[$index]['TERRITORY'] = $territory_name;
                        $footer = $this->fncRank($footer, 'rank_cal', 'rank');
                        $pdfDT[$index]['footer'] = $footer['rankdata'];
                        $pdfDT[$index]['color']['detail_first'] = $footer['first'];
                        $pdfDT[$index]['color']['detail_second'] = $footer['second'];
                    }
                    // 20241030 caina ins s
                } elseif ($summery === 'issue_ranking') {
                    //③指摘事項数ランキング
                    $indicationArr = $this->HMAUDJissekiPDFOutput->getIndicationCountArr($territory, $cour);
                    if (!$indicationArr['result']) {
                        throw new \Exception($indicationArr['data']);
                    }
                    array_push($allData, $indicationArr);
                    $pdfDT[$index]['data'] = $indicationArr;
                } elseif ($summery === 'cumulative_multiple_issue_ranking') {
                    //④複数回指摘事項数ランキング（累計）
                    $mulIndicationArr = $this->HMAUDJissekiPDFOutput->getMulIndicationCount($territory, $cour);
                    if (!$mulIndicationArr['result']) {
                        throw new \Exception($mulIndicationArr['data']);
                    }
                    array_push($allData, $mulIndicationArr);
                    $pdfDT[$index]['data'] = $mulIndicationArr;
                } elseif ($summery === 'consecutive_multiple_issue_ranking') {
                    //⑤複数回指摘事項数ランキング（連続）
                    $continueMulArr = $this->HMAUDJissekiPDFOutput->getContinueMulCount($territory, $cour, '', '');
                    if (!$continueMulArr['result']) {
                        throw new \Exception($continueMulArr['data']);
                    }
                    array_push($allData, $continueMulArr);
                    $pdfDT[$index]['data'] = $continueMulArr;
                } elseif ($summery === 'issue_ranking_per_territory' || $summery === 'cumulative_multiple_issue_ranking_per_territory') {
                    //⑥各領域ごと指摘項目ランキング（指定されたクールで指摘された拠点数を領域別にカウント）
                    //⑦各領域ごと複数回指摘項目ランキング（※回数は累計）
                    $kyotenCountArr = $this->HMAUDJissekiPDFOutput->getKyotenCountArr($territory, $cour, $summery);
                    if (!$kyotenCountArr['result']) {
                        throw new \Exception($kyotenCountArr['data']);
                    }
                    $kyotenCountArr['territory_name'] = $territory_name;
                    $kyotenCountArr['territory_color'] = $territory_color;
                    array_push($allData, $kyotenCountArr);
                }
                // 20241030 caina ins e
            }
            if ($nodata_detail == true) {
                throw new \Exception('nodetail');
            }
            if ($nodata_kyoten == true) {
                throw new \Exception('nokyoten');
            }
            // 20241030 caina upd s
            // if ($flg == 'pdf') {
            if ($flg == 'pdf' && $summery !== 'issue_ranking_per_territory' && $summery !== 'cumulative_multiple_issue_ranking_per_territory') {
                // 20241030 caina upd e
                $res['data'] = $pdfDT;
            } else {
                $res['data'] = $allData;
            }
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    public function btnDownloadClick()
    {

        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            $flg = $_POST['data']['flg'];
            // 20241030 caina ins s
            $summery = $_POST['data']['SUMMERY'];
            // 20241030 caina ins e

            if ($flg == 'pdf' || $flg == 'jisseki') {
                $pdfDTRes = $this->getData($_POST['data'], 'pdf');
                if (!$pdfDTRes['result']) {
                    throw new \Exception($pdfDTRes['error']);
                }
                $pdfDT = $pdfDTRes['data'];
                // 20241030 caina ins s
                $pdfDTArr = array();
                if ($summery !== 'consecutive_issue_table' && $summery !== 'cumulative_issue_table') {
                    $pdfDTArr = $this->MakePDF($_POST['data'], $pdfDT);
                }
                // 20241030 caina ins e
                $rpxFolder = dirname(__FILE__) . '/Component/tcpdf/';
                if (!(is_readable($rpxFolder) && is_writable($rpxFolder) && is_executable($rpxFolder))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                include_once 'Component/tcpdf/rpx_to_pdf.php';
                include_once 'Component/tcpdf/rptJisseki.inc';
                // 20241030 caina ins s
                include_once 'Component/tcpdf/rptJissekiIssue.inc';
                include_once 'Component/tcpdf/rptJissekiTerritory.inc';
                // 20241030 caina ins e
                $datas = array();
                // 20241030 caina upd s
                // $datas['rptJisseki']['data'] = $pdfDT;
                // $datas['rptJisseki']['mode'] = '1';
                // $datas['rptJisseki']['summery'] = $_POST['data']['SUMMERY'];
                // $rpx_file_names['rptJisseki'] = $data_fields_rptJisseki;
                if ($summery === 'cumulative_issue_table' || $summery === 'consecutive_issue_table') {
                    $datas['rptJisseki']['data'] = $pdfDT;
                    $datas['rptJisseki']['mode'] = '1';
                    //集計種類
                    $datas['rptJisseki']['summery'] = $_POST['data']['SUMMERY'];
                    $rpx_file_names['rptJisseki'] = $data_fields_rptJisseki;
                } elseif ($summery === 'issue_ranking' || $summery === 'cumulative_multiple_issue_ranking' || $summery === 'consecutive_multiple_issue_ranking') {
                    if (!isset($pdfDTArr['data'][0])) {
                        throw new \Exception('nodata');
                    }
                    $datas['rptJissekiIssue']['data'][0] = $pdfDTArr['data'];
                    $datas['rptJissekiIssue']['mode'] = '14';
                    $datas['rptJissekiIssue']['summery'] = $_POST['data']['SUMMERY'];
                    $rpx_file_names['rptJissekiIssue'] = $data_fields_rptJissekiIssue;
                } elseif ($summery === 'issue_ranking_per_territory' || $summery === 'cumulative_multiple_issue_ranking_per_territory') {
                    if (count((array) $pdfDTArr['data']) == 0) {
                        throw new \Exception('nodata');
                    }
                    $datas['rptJissekiTerritory']['data'] = $pdfDTArr['data'];
                    $datas['rptJissekiTerritory']['mode'] = '13';
                    $datas['rptJissekiTerritory']['summery'] = $_POST['data']['SUMMERY'];
                    $rpx_file_names['rptJissekiTerritory'] = $data_fields_rptJisseki;
                }
                // 20241030 caina upd e
                $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                //フォルダのパーミッションチェック
                if (file_exists($obj->REPORTS_TEMP_PATH)) {
                    if (!(is_readable($obj->REPORTS_TEMP_PATH) && is_writable($obj->REPORTS_TEMP_PATH) && is_executable($obj->REPORTS_TEMP_PATH))) {
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                } else {
                    $outFloder = dirname(WWW_ROOT . $obj->REPORTS_TEMP_PATH);
                    if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                    if (!mkdir($obj->REPORTS_TEMP_PATH, 0777, true)) {
                        throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                    }
                }
                $res['report'] = $obj->to_pdf();
                unset($obj);
            } else
                if ($flg == 'xlsx') {
                    $pdfDTRes = $this->getData($_POST['data'], 'xlsx');
                    if (!$pdfDTRes['result']) {
                        throw new \Exception($pdfDTRes['error']);
                    }

                    //20241030 caina upd s
                    // $makeExcel = $this->MakeExcel($pdfDTRes);
                    $makeExcel = $this->MakeExcel($pdfDTRes, $_POST['data']);
                    //20241030 caina upd e
                    if ($makeExcel['result'] == false) {
                        throw new \Exception($makeExcel['error']);
                    }
                    $res = $makeExcel;
                }
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        $this->fncReturn($res);
    }

    //20241030 caina ins s
    public function MakePDF($postData, $pdfDTRes)
    {
        $this->HMAUDJissekiPDFOutput = new HMAUDJissekiPDFOutput();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if ($postData['SUMMERY'] === 'issue_ranking' || $postData['SUMMERY'] === 'cumulative_multiple_issue_ranking' || $postData['SUMMERY'] === 'consecutive_multiple_issue_ranking') {
                $allData = array();
                foreach ($pdfDTRes as $index => $dataEntry) {
                    if ($dataEntry['data']['row'] == 0) {
                        continue;
                    }
                    foreach ($dataEntry['data']['data'] as $key => $value) {
                        //前回
                        $preIndication = ($postData['SUMMERY'] === 'consecutive_multiple_issue_ranking')
                            ? $this->HMAUDJissekiPDFOutput->getContinueMulCount($value['TERRITORY'], $postData['COUR'], $value['KYOTEN_CD'], $postData['SUMMERY'])
                            : $this->HMAUDJissekiPDFOutput->getPreIndicationArr($value['KYOTEN_CD'], $value['TERRITORY'], $postData['COUR'], $postData['SUMMERY']);
                        if (!$preIndication['result']) {
                            throw new \Exception($preIndication['data']);
                        }
                        if ($postData['COUR'] - 1 == 8 && $postData['SUMMERY'] === 'consecutive_multiple_issue_ranking') {
                            $preIndication['row'] = 0;
                        }
                        $pdfDTRes[$index]['data']['data'][$key]['PRECOUNT'] = $preIndication['row'] > 0 ? ($postData['SUMMERY'] === 'consecutive_multiple_issue_ranking' ? $preIndication['data'][0]['CHECK_COUNT'] : $preIndication['data'][0]['PRECOUNT']) : 0;

                        // 合計
                        if (!isset($allData[$value['KYOTEN_NAME']])) {
                            $allData[$value['KYOTEN_NAME']] = array(
                                'CHECK_COUNT' => 0,
                                'PRECOUNT' => 0,
                                'KYOTEN_NAME' => ''

                            );
                        }
                        $allData[$value['KYOTEN_NAME']]['CHECK_COUNT'] += $value['CHECK_COUNT'];
                        $allData[$value['KYOTEN_NAME']]['PRECOUNT'] += $preIndication['row'] > 0 ? ($postData['SUMMERY'] === 'consecutive_multiple_issue_ranking' ? $preIndication['data'][0]['CHECK_COUNT'] : $preIndication['data'][0]['PRECOUNT']) : 0;
                        $allData[$value['KYOTEN_NAME']]['KYOTEN_NAME'] = $value['KYOTEN_NAME'];
                    }
                }
                arsort($allData);
                // データセクションの抽出
                $i = 0;
                $pdfDT = array(
                    'SUM_0' => 0,
                    'SUMPRE_0' => 0,
                    'SUM_SUMPRE_0' => 0,
                    'SUM_1' => 0,
                    'SUMPRE_1' => 0,
                    'SUM_SUMPRE_1' => 0,
                    'SUM_2' => 0,
                    'SUMPRE_2' => 0,
                    'SUM_SUMPRE_2' => 0,
                );

                foreach ($allData as $key => $value) {
                    $pdfDT[$i] = array(
                        'KYOTEN_NAME' => isset($value['KYOTEN_NAME']) ? $value['KYOTEN_NAME'] : '',
                        'CHECK_COUNT' => isset($value['CHECK_COUNT']) ? $value['CHECK_COUNT'] : 0,
                        'PRECOUNT' => isset($value['PRECOUNT']) ? $value['PRECOUNT'] : 0,
                        'COMCOUNT' => isset($value['CHECK_COUNT']) && isset($value['PRECOUNT']) ? $value['CHECK_COUNT'] - $value['PRECOUNT'] : 0,
                    );

                    foreach (range(0, 2) as $index) {
                        if (isset($pdfDTRes[$index]['data']['data'][$i])) {
                            $currentData = $pdfDTRes[$index]['data']['data'][$i];

                            $pdfDT[$i]["KYOTEN_NAME_{$index}"] = isset($currentData['KYOTEN_NAME']) ? $currentData['KYOTEN_NAME'] : '';
                            $pdfDT[$i]["CHECK_COUNT_{$index}"] = isset($currentData['CHECK_COUNT']) ? $currentData['CHECK_COUNT'] : 0;
                            $pdfDT[$i]["PRECOUNT_{$index}"] = isset($currentData['PRECOUNT']) ? $currentData['PRECOUNT'] : 0;

                            if (isset($currentData['CHECK_COUNT']) && isset($currentData['PRECOUNT'])) {
                                $pdfDT[$i]["COMCOUNT_{$index}"] = $currentData['CHECK_COUNT'] - $currentData['PRECOUNT'];
                            }
                            $pdfDT["SUM_{$index}"] += isset($currentData['CHECK_COUNT']) ? $currentData['CHECK_COUNT'] : 0;
                            $pdfDT["SUMPRE_{$index}"] += isset($currentData['PRECOUNT']) ? $currentData['PRECOUNT'] : 0;
                        } else {
                            $pdfDT[$i]["KYOTEN_NAME_{$index}"] = '';
                            $pdfDT[$i]["CHECK_COUNT_{$index}"] = '';
                            $pdfDT[$i]["PRECOUNT_{$index}"] = '';
                            $pdfDT[$i]["COMCOUNT_{$index}"] = '';
                        }
                    }
                    $i++;
                }
                $pdfDT['SUM_SUMPRE_0'] = $pdfDT['SUM_0'] - $pdfDT['SUMPRE_0'];
                $pdfDT['SUM_SUMPRE_1'] = $pdfDT['SUM_1'] - $pdfDT['SUMPRE_1'];
                $pdfDT['SUM_SUMPRE_2'] = $pdfDT['SUM_2'] - $pdfDT['SUMPRE_2'];
                $pdfDT['SUM'] = $pdfDT['SUM_0'] + $pdfDT['SUM_1'] + $pdfDT['SUM_2'];
                $pdfDT['SUMPRE'] = $pdfDT['SUMPRE_0'] + $pdfDT['SUMPRE_1'] + $pdfDT['SUMPRE_2'];
                $pdfDT['SUM_SUMPRE'] = $pdfDT['SUM_SUMPRE_0'] + $pdfDT['SUM_SUMPRE_1'] + $pdfDT['SUM_SUMPRE_2'];
                if ($postData['SUMMERY'] === 'issue_ranking') {
                    $pdfDT['TITLE1'] = '＜合計＞指摘事項数ランキング';
                    $pdfDT['TITLE2'] = '＜各領域ごと＞指摘事項数ランキング';
                    $pdfDT['TITLE3'] = '指摘項目全合計';
                } else {
                    $pdfDT['TITLE1'] = '複数回指摘事項数ランキング';
                    $pdfDT['TITLE2'] = '各領域ごと複数回指摘事項数ランキング';
                    $pdfDT['TITLE3'] = '複数回指摘項目合計';
                }
            }
            // 20241030 YIN INS S
            else if ($postData['SUMMERY'] === 'issue_ranking_per_territory' || $postData['SUMMERY'] === 'cumulative_multiple_issue_ranking_per_territory') {
                $pdfDT = array();
                $has_data = false;
                foreach ($pdfDTRes as &$dataEntry) {
                    if (count($dataEntry['data']) > 0) {
                        $has_data = true;
                    }
                    foreach ($dataEntry['data'] as $index => &$item) {
                        //前回
                        $preKyoten = $this->HMAUDJissekiPDFOutput->getpreKyoten($item['CHECK_LST_ID'], $item['TERRITORY'], $postData);
                        if (!$preKyoten['result']) {
                            throw new \Exception($preKyoten['data']);
                        }
                        $item['PRE_KYOTEN'] = $preKyoten['data'][0]['COUNT'];
                        //前回比
                        $item['ROTIO'] = $item['KYOTEN_COUNT'] - $item['PRE_KYOTEN'];
                    }
                    array_push($pdfDT, $dataEntry);
                }
                if (!$has_data) {
                    $pdfDT = array();
                }
            }
            // 20241030 YIN INS E
            $res['data'] = $pdfDT;
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }

        return $res;
    }
    // 20241030 caina ins e
    //20241030 caina upd s
    // public function MakeExcel($pdfDTRes)
    public function MakeExcel($pdfDTRes, $postData)
    //20241030 caina upd e
    {
        $result = array(
            'result' => FALSE,
            'error' => ''
        );
        try {
            //20241030 caina ins s
            $summery = $postData['SUMMERY'];
            $cour = $postData['COUR'];
            //20241030 caina ins e
            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(__FILE__)));
            $tmpPath2 = "webroot/files/HMAUD/";
            $tmpPath = dirname($tmpPath1) . "/" . $tmpPath2;
            $strTemplatePath1 = $this->ClsComFncHMAUD->FncGetPath("HMAUDExcelLayoutPath");
            // 20241030 caina ins s
            if ($summery === 'consecutive_issue_table' || $summery === 'cumulative_issue_table') {
                $tempFileName = 'HMAUDTEMAPLATE.xlsx';
                $file_name = $summery === 'cumulative_issue_table' ? "指摘事項表（累計）.xlsx" : "指摘事項表（連続）.xlsx";
            } elseif ($summery === 'issue_ranking' || $summery === 'cumulative_multiple_issue_ranking' || $summery === 'consecutive_multiple_issue_ranking') {
                $tempFileName = 'HMAUD_ISSUE_RANKING.xlsx';
                $file_name = $summery === 'issue_ranking' ? "指摘事項数ランキング.xlsx" : ($summery === 'consecutive_multiple_issue_ranking' ? "複数回指摘事項数ランキング（連続）.xlsx" : "複数回指摘事項数ランキング（累計）.xlsx");

            } elseif ($summery === 'issue_ranking_per_territory' || $summery === 'cumulative_multiple_issue_ranking_per_territory') {
                $tempFileName = 'HMAUD_RANKING_PER_TER.xlsx';
                $file_name = $summery === 'issue_ranking_per_territory' ? "各領域ごと指摘項目ランキング.xlsx" : "各領域ごと複数回指摘項目ランキング（累計）.xlsx";
            }
            $fileName = $tmpPath . $file_name;
            // 20241030 caina ins e
            // 20241030 caina upd s
            // $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath1 . "HMAUDTEMAPLATE.xlsx";
            $strTemplatePath = $tmpPath1 . '/' . $strTemplatePath1 . $tempFileName;
            // 20241030 caina upd e
            // 20241030 caina del s
            // $fileName = $tmpPath . "実績集計.xlsx";
            // 20241030 caina del e
            if (!file_exists($strTemplatePath)) {
                $result["data"] = 'W9999';
                throw new \Exception($result["data"]);
            }
            //path is exist
            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            } else {
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                mkdir($tmpPath, 0777, TRUE);
            }

            //***Excel出力処理****
            //エクセルのテンプレートが保存されている場所を取得
            $objReader = new Xlsx();
            $objPHPExcel = $objReader->load($strTemplatePath);
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 20241030 caina ins s
            if ($summery === 'consecutive_issue_table' || $summery === 'cumulative_issue_table') {
                // 20241030 caina ins e
                for ($a = 0; $a < count($pdfDTRes['data']); $a++) {
                    $objPHPExcel->setActiveSheetIndex(0);
                    $sc = $objPHPExcel->getActiveSheet()->copy();
                    $clonedSheet = clone $sc;
                    $temporarySheet = clone $clonedSheet;
                    $excelsheetname = $pdfDTRes['data'][$a][0]['TERRITORY'];
                    $temporarySheet->setTitle($excelsheetname);
                    $objPHPExcel->addSheet($temporarySheet, count($pdfDTRes['data']));
                    $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($temporarySheet));
                    $objActSheet = $objPHPExcel->getActiveSheet();
                    $objActSheet->setCellValue('B1', $pdfDTRes['data'][$a][0]['COUR']);
                    $objActSheet->setCellValue('B2', $excelsheetname);
                    $detail = $pdfDTRes['data'][$a];
                    $headerKyoten = $pdfDTRes['data'][$a][0]['excelheaderKyoten'];
                    $objActSheet->insertNewRowBefore(10, count($detail));
                    $objActSheet->insertNewColumnBefore('G', count($headerKyoten));
                    if (count($detail) > 4) {
                        $objActSheet->removeRow(7 + count($detail) - 3, 3);
                    } else {
                        $objActSheet->removeRow(9, 3);
                    }
                    if (count($headerKyoten) > 4) {
                        $objActSheet->removeColumn(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + count($headerKyoten) - 3), 3);
                    } else {
                        $objActSheet->removeColumn("F", 3);
                    }

                    $row = 7 + count($detail) + 1;
                    $col = 4 + count($headerKyoten);

                    //合計
                    // $sum = $objActSheet->getCell([4, $row])->getValue();
                    //実施度合
                    $duhe = str_ireplace("29", count($detail), $objActSheet->getCell([5, $row + 1])->getValue());
                    //合計
                    $sum_col = $objActSheet->getCell([$col + 1, 8])->getValue();
                    //実施度合
                    $duhe_col = str_ireplace("17", count($headerKyoten), $objActSheet->getCell([$col + 2, 8])->getValue());
                    //合并行用
                    //業務手順書項目
                    $merge_start_column2 = 0;
                    $merge_end_column2 = 0;
                    //業務内容
                    $merge_start_column4 = 0;
                    $merge_end_column4 = 0;
                    //監査項目
                    // $merge_start_column7 = 0;
                    // $merge_end_column7 = 0;
                    $objActSheet->setCellValue('D' . (9 + count($detail)), "実施度合" . "\r\n" . "（全" . count($detail) . '項目）');
                    $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(6 + count($headerKyoten)) . '7', "実施度合" . "\r\n" . "（全" . count($headerKyoten) . '拠点）');
                    for ($j = 0; $j < count($detail); $j++) {
                        $col_before = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2);
                        if (count($headerKyoten) == 1) {
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . (8 + $j), "=COUNTA(E" . (8 + $j) . ":E" . (8 + $j) . ")");
                        } else {
                            $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . (8 + $j), str_ireplace("8", 8 + $j, $sum_col));
                        }

                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2) . (8 + $j), str_ireplace("8)", (8 + $j) . ")", $duhe_col));
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 3) . (8 + $j), '=RANK(' . $col_before . (8 + $j) . ',$' . $col_before . '$8:$' . $col_before . '$' . (7 + count($detail)) . ')');
                        $objActSheet->setCellValue('A' . (8 + $j), $detail[$j]['COLUMN1']);
                        $objActSheet->setCellValue('B' . (8 + $j), $detail[$j]['COLUMN2']);
                        $objActSheet->setCellValue('C' . (8 + $j), $detail[$j]['COLUMN4']);
                        $objActSheet->setCellValue('D' . (8 + $j), $detail[$j]['COLUMN7']);
                        for ($m = 0; $m < count($headerKyoten); $m++) {
                            $comment = $detail[$j]['check' . $m];
                            //20241030 caina upd s
                            // if ($comment == "1") {
                            //     $objActSheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(4 + $m) . (8 + $j), "×");
                            // }
                            if ($comment != "0") {
                                $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $m) . (8 + $j), $comment);
                            }
                            //20241030 caina upd e
                            if ($comment == "2" && $summery == 'consecutive_issue_table') {
                                //20241030 caina del s
                                // $objActSheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(4 + $m) . (8 + $j), "×");
                                //20241030 caina del e
                                $objActSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $m) . (8 + $j))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
                            }
                            if ($comment >= "3" && $summery == 'consecutive_issue_table') {
                                //20241030 caina del s
                                // $objActSheet->setCellValue(\PHPExcel_Cell::stringFromColumnIndex(4 + $m) . (8 + $j), "×");
                                //20241030 caina del e
                                $objActSheet->getStyle(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $m) . (8 + $j))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
                            }

                        }
                        //行のマージ
                        if ($j !== 0 && count($detail) > 1) {
                            //業務手順書項目
                            if ($detail[$j - 1]['COLUMN2'] == $detail[$j]['COLUMN2']) {
                                $merge_end_column2 = $j;
                                if ($j == count($detail) - 1 && $merge_end_column2 - $merge_start_column2 > 0) {
                                    $objActSheet->mergeCells('B' . (8 + $merge_start_column2) . ':B' . (8 + $merge_end_column2));
                                }
                            } else {
                                if ($merge_end_column2 - $merge_start_column2 > 0) {
                                    $objActSheet->mergeCells('B' . (8 + $merge_start_column2) . ':B' . (8 + $merge_end_column2));
                                }
                                $merge_start_column2 = $j;
                            }
                            //業務内容
                            if ($detail[$j - 1]['COLUMN4'] == $detail[$j]['COLUMN4'] && $detail[$j - 1]['COLUMN2'] == $detail[$j]['COLUMN2']) {
                                $merge_end_column4 = $j;
                                if ($j == count($detail) - 1 && $merge_end_column4 - $merge_start_column4 > 0) {
                                    $objActSheet->mergeCells('C' . (8 + $merge_start_column4) . ':C' . (8 + $merge_end_column4));
                                }
                            } else {
                                if ($merge_end_column4 - $merge_start_column4 > 0) {
                                    $objActSheet->mergeCells('C' . (8 + $merge_start_column4) . ':C' . (8 + $merge_end_column4));
                                }
                                $merge_start_column4 = $j;
                            }
                        }
                    }
                    for ($i = 0; $i < count($headerKyoten); $i++) {
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i) . '7', $headerKyoten[$i]);

                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i) . $row, "=COUNTA(" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i) . "8:" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i) . (7 + count($detail)) . ")");

                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i) . ($row + 1), str_ireplace("-E", "-" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i), $duhe));
                        $objActSheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i) . ($row + 2), '=RANK(' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $i) . ($row + 1) . ',$' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + 1) . '$' . ($row + 1) . ':$' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + count($headerKyoten)) . '$' . ($row + 1) . ')');
                    }

                    $LARGEcol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
                    $style_range = "$" . $LARGEcol . "$8:$" . $LARGEcol . "$" . (count($pdfDTRes['data'][$a]) + 7);
                    $objConditional1 = new Conditional();
                    $objConditional1->setConditionType(Conditional::CONDITION_CELLIS);
                    $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition1 = "LARGE(" . $style_range . ",1)";
                    $objConditional1->addCondition("IF(" . $addCondition1 . "=0,\"\"," . $addCondition1 . ")");
                    $objConditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('C0504D');

                    $objConditional2 = new Conditional();
                    $objConditional2->setConditionType(Conditional::CONDITION_CELLIS);
                    $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition2 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . ",LARGE(" . $style_range . ",1))+1)";
                    $objConditional2->addCondition("IF(" . $addCondition2 . "=0,\"\"," . $addCondition2 . ")");
                    $objConditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    $objConditional3 = new Conditional();
                    $objConditional3->setConditionType(Conditional::CONDITION_CELLIS);
                    $objConditional3->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition3 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . "," . $addCondition1 . ")+1 + COUNTIF(" . $style_range . "," . $addCondition2 . "))";
                    $objConditional3->addCondition("IF(" . $addCondition3 . "=0,\"\"," . $addCondition3 . ")");
                    $objConditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    $conditionalStyles = $objActSheet->getStyle([$col + 1, 8, $col + 1, count($pdfDTRes['data'][$a]) + 7])->getConditionalStyles();
                    array_push($conditionalStyles, $objConditional1);
                    array_push($conditionalStyles, $objConditional2);
                    array_push($conditionalStyles, $objConditional3);
                    $objActSheet->getStyle([$col + 1, 8, $col + 1, count($pdfDTRes['data'][$a]) + 7])->setConditionalStyles($conditionalStyles);

                    $objConditional1 = new Conditional();
                    $objConditional1->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $objConditional1->addCondition($LARGEcol . "8=IF(" . $addCondition1 . "=0,\"\"," . $addCondition1 . ")");
                    $objConditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('C0504D');
                    $objConditional1->getStyle()->getNumberFormat()->setFormatCode('0.0%');

                    $objConditional2 = new Conditional();
                    $objConditional2->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $objConditional2->addCondition($LARGEcol . "8=IF(" . $addCondition2 . "=0,\"\"," . $addCondition2 . ")");
                    $objConditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');
                    $objConditional2->getStyle()->getNumberFormat()->setFormatCode('0.0%');

                    $objConditional3 = new Conditional();
                    $objConditional3->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional3->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $objConditional3->addCondition($LARGEcol . "8=IF(" . $addCondition3 . "=0,\"\"," . $addCondition3 . ")");
                    $objConditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');
                    $objConditional3->getStyle()->getNumberFormat()->setFormatCode('0.0%');

                    // $style_range_relation = "$" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2) . "$8:$" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2) . (count($pdfDTRes['data'][$a]) + 7);
                    $style_range_relation = [$col + 2, 8, $col + 2, count($pdfDTRes['data'][$a]) + 7];
                    $conditionalStyles = $objActSheet->getStyle($style_range_relation)->getConditionalStyles();
                    array_push($conditionalStyles, $objConditional1);
                    array_push($conditionalStyles, $objConditional2);
                    array_push($conditionalStyles, $objConditional3);
                    $objActSheet->getStyle($style_range_relation)->setConditionalStyles($conditionalStyles);

                    $objConditional1 = new Conditional();
                    $objConditional1->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $objConditional1->addCondition($LARGEcol . "8=IF(" . $addCondition1 . "=0,\"\"," . $addCondition1 . ")");
                    $objConditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('C0504D');

                    $objConditional2 = new Conditional();
                    $objConditional2->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $objConditional2->addCondition($LARGEcol . "8=IF(" . $addCondition2 . "=0,\"\"," . $addCondition2 . ")");
                    $objConditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    $objConditional3 = new Conditional();
                    $objConditional3->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional3->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $objConditional3->addCondition($LARGEcol . "8=IF(" . $addCondition3 . "=0,\"\"," . $addCondition3 . ")");
                    $objConditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    // $style_range_relation = "\$A$8:\$A$" . (count($pdfDTRes['data'][$a]) + 7);
                    $style_range_relation = [1, 8, 1, count($pdfDTRes['data'][$a]) + 7];
                    $conditionalStyles = $objActSheet->getStyle($style_range_relation)->getConditionalStyles();
                    array_push($conditionalStyles, $objConditional1);
                    array_push($conditionalStyles, $objConditional2);
                    array_push($conditionalStyles, $objConditional3);
                    $objActSheet->getStyle($style_range_relation)->setConditionalStyles($conditionalStyles);

                    $LARGEcol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $LARGErow = count($pdfDTRes['data'][$a]) + 8;
                    $style_range = "\$E$" . $LARGErow . ":$" . $LARGEcol . "$" . $LARGErow;
                    $objConditional1 = new Conditional();
                    $objConditional1->setConditionType(Conditional::CONDITION_CELLIS);
                    $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition1 = "LARGE(" . $style_range . ",1)";
                    $objConditional1->addCondition("IF(" . $addCondition1 . "=0,\"\"," . $addCondition1 . ")");
                    $objConditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('C0504D');

                    $objConditional2 = new Conditional();
                    $objConditional2->setConditionType(Conditional::CONDITION_CELLIS);
                    $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition2 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . ",LARGE(" . $style_range . ",1))+1)";
                    $objConditional2->addCondition("IF(" . $addCondition2 . "=0,\"\"," . $addCondition2 . ")");
                    $objConditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    $objConditional3 = new Conditional();
                    $objConditional3->setConditionType(Conditional::CONDITION_CELLIS);
                    $objConditional3->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition3 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . "," . $addCondition1 . ")+1 + COUNTIF(" . $style_range . "," . $addCondition2 . "))";
                    $objConditional3->addCondition("IF(" . $addCondition3 . "=0,\"\"," . $addCondition3 . ")");
                    $objConditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    $conditionalStyles = $objActSheet->getStyle([5, $LARGErow, $col, $LARGErow])->getConditionalStyles();
                    array_push($conditionalStyles, $objConditional1);
                    array_push($conditionalStyles, $objConditional2);
                    array_push($conditionalStyles, $objConditional3);
                    $objActSheet->getStyle([5, $LARGErow, $col, $LARGErow])->setConditionalStyles($conditionalStyles);

                    $objConditional1 = new Conditional();
                    $objConditional1->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition1 = "LARGE(" . $style_range . ",1)";
                    $objConditional1->addCondition("E" . (count($pdfDTRes['data'][$a]) + 8) . "=IF(" . $addCondition1 . "=0,\"\"," . $addCondition1 . ")");
                    $objConditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('C0504D');
                    $objConditional1->getStyle()->getNumberFormat()->setFormatCode('0.0%');

                    $objConditional2 = new Conditional();
                    $objConditional2->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition2 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . ",LARGE(" . $style_range . ",1))+1)";
                    $objConditional2->addCondition("E" . (count($pdfDTRes['data'][$a]) + 8) . "=IF(" . $addCondition2 . "=0,\"\"," . $addCondition2 . ")");
                    $objConditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');
                    $objConditional2->getStyle()->getNumberFormat()->setFormatCode('0.0%');

                    $objConditional3 = new Conditional();
                    $objConditional3->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional3->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition3 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . "," . $addCondition1 . ")+1 + COUNTIF(" . $style_range . "," . $addCondition2 . "))";
                    $objConditional3->addCondition("E" . (count($pdfDTRes['data'][$a]) + 8) . "=IF(" . $addCondition3 . "=0,\"\"," . $addCondition3 . ")");
                    $objConditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');
                    $objConditional3->getStyle()->getNumberFormat()->setFormatCode('0.0%');

                    // $style_range_relation = "\$E$" . (count($pdfDTRes['data'][$a]) + 9) . ":$" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . "$" . (count($pdfDTRes['data'][$a]) + 9);
                    $style_range_relation = [5, count($pdfDTRes['data'][$a]) + 9, $col, count($pdfDTRes['data'][$a]) + 9];
                    $conditionalStyles = $objActSheet->getStyle($style_range_relation)->getConditionalStyles();
                    array_push($conditionalStyles, $objConditional1);
                    array_push($conditionalStyles, $objConditional2);
                    array_push($conditionalStyles, $objConditional3);
                    $objActSheet->getStyle($style_range_relation)->setConditionalStyles($conditionalStyles);

                    $objConditional1 = new Conditional();
                    $objConditional1->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition1 = "LARGE(" . $style_range . ",1)";
                    $objConditional1->addCondition("E" . (count($pdfDTRes['data'][$a]) + 8) . "=IF(" . $addCondition1 . "=0,\"\"," . $addCondition1 . ")");
                    $objConditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('C0504D');

                    $objConditional2 = new Conditional();
                    $objConditional2->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional2->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition2 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . ",LARGE(" . $style_range . ",1))+1)";
                    $objConditional2->addCondition("E" . (count($pdfDTRes['data'][$a]) + 8) . "=IF(" . $addCondition2 . "=0,\"\"," . $addCondition2 . ")");
                    $objConditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    $objConditional3 = new Conditional();
                    $objConditional3->setConditionType(Conditional::CONDITION_EXPRESSION);
                    $objConditional3->setOperatorType(Conditional::OPERATOR_EQUAL);
                    $addCondition3 = "LARGE(" . $style_range . ",COUNTIF(" . $style_range . "," . $addCondition1 . ")+1 + COUNTIF(" . $style_range . "," . $addCondition2 . "))";
                    $objConditional3->addCondition("E" . (count($pdfDTRes['data'][$a]) + 8) . "=IF(" . $addCondition3 . "=0,\"\"," . $addCondition3 . ")");
                    $objConditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getEndColor()->setARGB('E6B8B7');

                    //$style_range_relation = "\$E$7:$" . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . "$7";
                    $style_range_relation = [5, 7, $col, 7];
                    $conditionalStyles = $objActSheet->getStyle($style_range_relation)->getConditionalStyles();
                    array_push($conditionalStyles, $objConditional1);
                    array_push($conditionalStyles, $objConditional2);
                    array_push($conditionalStyles, $objConditional3);
                    $objActSheet->getStyle($style_range_relation)->setConditionalStyles($conditionalStyles);

                    unset($temporarySheet);
                }
                $objPHPExcel->removeSheetByIndex(0);
                // 20241030 caina ins s
            } elseif ($summery === 'issue_ranking_per_territory' || $summery === 'cumulative_multiple_issue_ranking_per_territory') {
                $noDataflg = true;
                if ($summery === 'cumulative_multiple_issue_ranking_per_territory') {
                    $objActSheet->setCellValue("C1", '各領域ごと複数回指摘項目ランキング');
                    // シート名の設定
                    $objActSheet->setTitle('各領域ごと複数回指摘項目ランキング（累計）');
                }
                $startRow = 5;
                foreach ($pdfDTRes['data'] as $dataEntry) {
                    if ($dataEntry['row'] == 0) {
                        continue;
                    }
                    $noDataflg = false;
                    if (isset($dataEntry['data'][0]['TERRITORY'])) {
                        foreach ($dataEntry['data'] as $index => $item) {
                            $objActSheet->insertNewRowBefore($startRow + 1, 1);
                            $objActSheet->setCellValue("C{$startRow}", $item['CHECK_LST_ID']);
                            $objActSheet->setCellValue("D{$startRow}", $item['KYOTEN_COUNT']);//拠点数
                            //前回
                            $preKyoten = $this->HMAUDJissekiPDFOutput->getpreKyoten($item['CHECK_LST_ID'], $item['TERRITORY'], $postData);
                            if (!$preKyoten['result']) {
                                throw new \Exception($preKyoten['data']);
                            }
                            $objActSheet->setCellValue("E{$startRow}", $preKyoten['data'][0]['COUNT']);
                            //前回比
                            $objActSheet->setCellValue("F{$startRow}", "=SUM(" . "D{$startRow}-E{$startRow}" . ")");
                            $objActSheet->mergeCells("G{$startRow}:" . "V{$startRow}");
                            $objActSheet->setCellValue("G{$startRow}", $item['COLUMN7']);
                            $startRow++;
                            if ($index + 1 == count($dataEntry['data'])) {
                                $objActSheet->removeRow($startRow);
                            }
                        }
                        $startRow += 2;
                    } else {
                        $startRow += 3;
                    }
                }
                if ($noDataflg) {
                    throw new \Exception('nodata');
                }
            } elseif ($summery === 'issue_ranking' || $summery === 'cumulative_multiple_issue_ranking' || $summery === 'consecutive_multiple_issue_ranking') {
                $maxRow = $pdfDTRes['data'][0]['row'];
                $allData = array();
                $isFirstTer = true;
                $noDataflg = true;
                $territoryMaxRows = array();// 各レルムを記録するために使用される最大行数

                if ($summery === 'cumulative_multiple_issue_ranking' || $summery === 'consecutive_multiple_issue_ranking') {
                    $objActSheet->setCellValue("C3", '複数回指摘事項数ランキング');
                    $objActSheet->setCellValue("D5", '複数回指摘項目合計');
                    $objActSheet->setCellValue("H3", '各領域ごと複数回指摘事項数ランキング');
                    // シート名の設定
                    if ($summery === 'cumulative_multiple_issue_ranking') {
                        $objActSheet->setTitle('複数回指摘事項数ランキング（累計）');
                    } elseif ($summery === 'consecutive_multiple_issue_ranking') {
                        $objActSheet->setTitle('複数回指摘事項数ランキング（連続）');
                    }
                }
                foreach ($pdfDTRes['data'] as $dataEntry) {
                    $startRow = 7;
                    if ($dataEntry['row'] == 0) {
                        continue;
                    }

                    $noDataflg = false;
                    $territoryMaxRows[$dataEntry['data'][0]['TERRITORY']] = $dataEntry['row'] + $startRow;
                    foreach ($dataEntry['data'] as $key => $value) {
                        $col = $value['TERRITORY'] == 1 ? 'H' : ($value['TERRITORY'] == 2 ? 'M' : 'R');
                        if ($isFirstTer || $key + 7 >= $maxRow) {
                            $objActSheet->insertNewRowBefore($startRow + 1, 1);
                        }
                        $objActSheet->setCellValue($col . $startRow, $value['KYOTEN_NAME']);
                        $objActSheet->setCellValue(chr(ord($col) + 1) . $startRow, $value['CHECK_COUNT']);

                        //前回
                        $preIndication = ($summery === 'consecutive_multiple_issue_ranking')
                            ? $this->HMAUDJissekiPDFOutput->getContinueMulCount($value['TERRITORY'], $cour, $value['KYOTEN_CD'], $summery)
                            : $this->HMAUDJissekiPDFOutput->getPreIndicationArr($value['KYOTEN_CD'], $value['TERRITORY'], $cour, $summery);

                        if (!$preIndication['result']) {
                            throw new \Exception($preIndication['data']);
                        }
                        if ($cour - 1 == 8 && $postData['SUMMERY'] === 'consecutive_multiple_issue_ranking') {
                            $preIndication['row'] = 0;
                        }
                        $preCount = $preIndication['row'] > 0 ? ($summery === 'consecutive_multiple_issue_ranking' ? $preIndication['data'][0]['CHECK_COUNT'] : $preIndication['data'][0]['PRECOUNT']) : 0;
                        $objActSheet->setCellValue(chr(ord($col) + 2) . $startRow, $preCount);
                        $objActSheet->setCellValue(chr(ord($col) + 3) . $startRow, "=SUM(" . chr(ord($col) + 1) . $startRow . " - " . chr(ord($col) + 2) . $startRow . ")");

                        $startRow++;
                        if (!isset($allData[$value['KYOTEN_NAME']])) {
                            $allData[$value['KYOTEN_NAME']] = array(
                                'CHECK_COUNT' => 0,
                                'PRECOUNT' => 0,
                                'KYOTEN_NAME' => ''

                            );
                        }
                        $allData[$value['KYOTEN_NAME']]['CHECK_COUNT'] += $value['CHECK_COUNT'];
                        $allData[$value['KYOTEN_NAME']]['PRECOUNT'] += $preCount;
                        $allData[$value['KYOTEN_NAME']]['KYOTEN_NAME'] = $value['KYOTEN_NAME'];
                    }
                    //前領域の最大行数が$maxRowより大きい場合は更新$maxRow
                    $maxRow = $startRow > $maxRow ? $startRow : $maxRow;
                    $isFirstTer = false;
                }
                if ($noDataflg) {
                    throw new \Exception('nodata');
                }
                arsort($allData);
                $startRow = 7;
                foreach ($allData as $key => $item) {
                    if ($startRow > $maxRow && $startRow - 6 < count($allData)) {
                        $objActSheet->insertNewRowBefore($startRow, 1);
                    }
                    $objActSheet->setCellValue("C" . $startRow, $item['KYOTEN_NAME']);
                    $objActSheet->setCellValue("D" . $startRow, $item['CHECK_COUNT']);
                    $objActSheet->setCellValue("E" . $startRow, $item['PRECOUNT']);
                    $objActSheet->setCellValue("F" . $startRow, "=SUM(" . "D" . $startRow . "-E" . $startRow . ")");

                    // 最大行数に達していない場合は斜線と固定値を塗りつぶす
                    if (isset($territoryMaxRows[1]) && $startRow >= $territoryMaxRows[1]) {
                        $col = 'H';
                        //画斜线
                        $range = $col . $startRow . ':' . chr(ord($col) + 3) . $startRow;
                        $objPHPExcel->getActiveSheet()->getStyle($range)->getBorders()->setDiagonalDirection(2);
                        $objPHPExcel->getActiveSheet()->getStyle($range)->getBorders()->getDiagonal()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    if (isset($territoryMaxRows[2]) && $startRow >= $territoryMaxRows[2]) {
                        $col = 'M';
                        $range = $col . $startRow . ':' . chr(ord($col) + 3) . $startRow;
                        $objPHPExcel->getActiveSheet()->getStyle($range)->getBorders()->setDiagonalDirection(2);
                        $objPHPExcel->getActiveSheet()->getStyle($range)->getBorders()->getDiagonal()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    if (isset($territoryMaxRows[3]) && $startRow >= $territoryMaxRows[3]) {
                        $col = 'R';
                        $range = $col . $startRow . ':' . chr(ord($col) + 3) . $startRow;
                        $objPHPExcel->getActiveSheet()->getStyle($range)->getBorders()->setDiagonalDirection(2);
                        $objPHPExcel->getActiveSheet()->getStyle($range)->getBorders()->getDiagonal()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    $startRow++;
                }
            }
            // 20241030 caina ins e
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet->setSelectedCell("A1");
            //ブック作成
            $objWriter = new PhpSpreadsheetXlsx($objPHPExcel);
            $objWriter->save($fileName);
            $objPHPExcel->disconnectWorksheets();
            unset($objWriter, $objReader, $objPHPExcel);
            // 20241030 caina upd s
            // $file = "files/HMAUD/" . "実績集計.xlsx";
            $file = "files/HMAUD/" . $file_name;
            // 20241030 caina upd e

            $result['data'] = $file;
            $result['report'] = $file;
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();

        }
        return $result;
    }

    //ランキング=総個数-自分より低い-自分と同じ+1
    public function fncRank($array, $s, $r)
    {
        $resArr = array(
            'first' => array(),
            'second' => array(),
            'rankdata' => array(),
        );
        foreach ($array as $v) {
            $marr[] = $v[$s];
            $bgArr[] = $v['total'];
        }
        //指摘の多かった項目ワースト３にいろつけ
        $del_repeat = array_unique($bgArr);
        rsort($del_repeat);
        foreach ($array as $index => $val) {
            $repeat = $this->get_array_repeats($val[$s], $marr);
            $num = $this->get_array_values($val[$s], $marr);
            $rank[$r] = count($marr) - $num - $repeat + 1;
            $rank2[] = array_merge($val, $rank);

            //指摘がなければ色を塗らない
            if ($val['total'] != 0) {
                //指摘の多かった項目ワースト３にいろつけ
                if ($val['total'] == $del_repeat[0]) {
                    array_push($resArr['first'], $index);
                } else
                    if ((isset($del_repeat[1]) && $val['total'] == $del_repeat[1]) || (isset($del_repeat[2]) && $val['total'] == $del_repeat[2])) {
                        array_push($resArr['second'], $index);
                    }
            }
        }
        $resArr['rankdata'] = $rank2;
        return $resArr;
    }

    public function get_array_values($val, $array)
    {
        $num = 0;
        for ($i = 0; $i < count($array); $i++) {
            if ($val > $array[$i]) {
                $num++;
            }
        }
        return $num;
    }

    public function get_array_repeats($string, $array)
    {
        $count = array_count_values($array);

        foreach ($count as $key => $value) {
            if ($key == $string) {
                return $value;
            }
        }
    }

}
