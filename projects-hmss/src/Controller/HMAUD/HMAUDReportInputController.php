<?php
/**
 * 履歴：
 * ------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                       担当
 * YYYYMMDD           #ID                       XXXXXX                                    FCSDL
 * 20241030           機能変更　　202410_内部統制システム_集計機能改善対応 指摘回数を細分化         LHB
 * 20251016           bug                  既存バグ修正です                              YIN
 * ------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMAUD;

use App\Controller\AppController;
use App\Model\HMAUD\HMAUDReportInput;

//*******************************************
// * sample controller
//*******************************************
class HMAUDReportInputController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    public $HMAUDReportInput;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMAUD');
    }


    public function index()
    {
        $this->render('index', 'HMAUDReportInput_layout');
    }

    public function fncSearchSpread()
    {

        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $this->HMAUDReportInput = new HMAUDReportInput();
                $admindata = $this->HMAUDReportInput->getadminSql();
                if (!$admindata['result']) {
                    throw new \Exception($admindata['data']);
                }

                if ($admindata['data'][0]['COUNT'] == '0') {
                    $memberdata = $this->HMAUDReportInput->getMemberdataSql();
                    if (!$memberdata['result']) {
                        throw new \Exception($memberdata['data']);
                    }
                    $viewerdata = $this->HMAUDReportInput->getViewerdataSql();
                    if (!$viewerdata['result']) {
                        throw new \Exception($viewerdata['data']);
                    }
                    if ($memberdata['data'][0]['COUNT'] == '0' && $viewerdata['data'][0]['COUNT'] == '0') {
                        throw new \Exception('W0008');
                    }
                }
                $headdata = $this->HMAUDReportInput->getHeadDataSql($_POST['request']);
                if (!$headdata['result']) {
                    throw new \Exception($headdata['data']);
                }
                $persondata = $this->HMAUDReportInput->getPersonDataSql($_POST['request']);
                if (!$persondata['result']) {
                    throw new \Exception($persondata['data']);
                }
                $objdr = $this->HMAUDReportInput->getDataSql($_POST['request']);
                if (!$objdr['result']) {
                    throw new \Exception($objdr['data']);
                }
                if (count((array) $objdr['data']) > 0) {
                    for ($i = 0; $i < count((array) $objdr['data']); $i++) {

                        $chkrowno_1 = $this->HMAUDReportInput->chkrowno($_POST['request'], $objdr['data'][$i]['ROW_NO']);
                        if (!$chkrowno_1['result']) {
                            throw new \Exception($chkrowno_1['data']);
                        }
                        // 20241030 LHB upd s
                        // $objdr['data'][$i]['ROW_NO1'] = $chkrowno_1['data'][0]['COUNT'] < 2 ? "" : $chkrowno_1['data'][0]['COUNT'];
                        $objdr['data'][$i]['ROW_NO1'] = $chkrowno_1['data'][0]['COUNT'] < 2 ? "" : $chkrowno_1['data'][0]['COUNT'] . "回目";
                        // 20241030 LHB upd e
                        // 20241030 LHB ins s
                        $chkrowno_2 = $this->HMAUDReportInput->continuous_chkrowno($_POST['request'], $objdr['data'][$i]['ROW_NO']);
                        if (!$chkrowno_2['result']) {
                            throw new \Exception($chkrowno_1['data']);
                        }
                        $chkrowno_2_count = 1;
                        // 20251016 YIN UPD S
                        // if ($chkrowno_2['data'][0]['COURS'] == $_POST['request']['COURS'] && count((array) $chkrowno_2['data']) > 0) {
                        if (count((array) $chkrowno_2['data']) > 0 && $chkrowno_2['data'][0]['COURS'] == $_POST['request']['COURS']) {
                            // 20251016 YIN UPD E
                            foreach ((array) $chkrowno_2['data'] as $key => $value) {
                                if ($key > 0) {
                                    if ($key > 0 && (int) $value["COURS"] + 1 == $chkrowno_2['data'][$key - 1]['COURS'] && $value["MEMBER"] == $chkrowno_2['data'][0]['MEMBER']) {
                                        $chkrowno_2_count += 1;
                                    } else {
                                        break;
                                    }
                                }
                            }
                        } else {
                            $chkrowno_2_count = 0;
                        }
                        $objdr['data'][$i]['ROW_NO2'] = $chkrowno_2_count < 2 ? "" : $chkrowno_2_count;
                        // 20241030 LHB ins e
                    }
                }
                $tmpJqgridShow = $this->ClsComFncHMAUD->FncCreateJqGridShow($objdr['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncHMAUD->FncCreateJqGridDataIndex($objdr['data'], $totalPage, $page, $tmpCount);
                if (count((array) $headdata['data']) !== 0) {
                    $check_id = $headdata['data'][0]['CHECK_ID'];
                    $getrole = $this->HMAUDReportInput->getUserRoleSql($check_id);
                    if (!$getrole['result']) {
                        throw new \Exception($getrole['data']);
                    }
                    $rolearr = array();
                    for ($i = 0; $i < count((array) $getrole['data']); $i++) {
                        array_push($rolearr, $getrole['data'][$i]['ROLE']);
                    }

                    $res->role = $rolearr;
                }
                $res->headdata = $headdata;
                $res->persondata = $persondata;
                $res->admin = $admindata['data'][0]['COUNT'];
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //拠点マスタのデータを取得
    public function getKyotenData()
    {
        $this->HMAUDReportInput = new HMAUDReportInput();
        $res = array(
            'result' => FALSE,
            'data' => array(
                'kyoten' => '',
                'cour' => '',

            ),
            'error' => ''
        );
        try {
            $kyoten = $this->HMAUDReportInput->getKyotenSql();
            if (!$kyoten['result']) {
                throw new \Exception($kyoten['data']);
            }
            //検索条件・クールには 現在のクール数を初期表示
            $cour = $this->HMAUDReportInput->getInitializeCour();
            if (!$cour['result']) {
                throw new \Exception($cour['data']);
            }
            $res['data']['kyoten'] = $kyoten['data'];
            $res['data']['cour'] = $cour['data'];
            $res['result'] = true;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //保存ボタンクリック
    public function btnSave()
    {
        $tranStartFlg = FALSE;
        $this->HMAUDReportInput = new HMAUDReportInput();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (!isset($_POST['data'])) {
                throw new \Exception('param error');
            }
            $checkdata = $this->HMAUDReportInput->getDataSql($_POST['data']);
            if (!$checkdata['result']) {
                throw new \Exception($checkdata['data']);
            }
            //トランザクション開始
            //更新処理を行う
            $this->HMAUDReportInput->Do_transaction();
            $tranStartFlg = TRUE;
            $data = $_POST['data']['rowdata'];
            for ($i = 0; $i < count($data); $i++) {
                if ($checkdata['data'][$i]['UPD_DATE'] != $data[$i]['UPD_DATE']) {
                    throw new \Exception('W9999');
                }
                $chkRes = $this->HMAUDReportInput->getReportDetailExist($_POST['data']['CHECK_ID'], $data[$i]['REPORT_LIST_ID']);
                if (!$chkRes['result']) {
                    throw new \Exception($chkRes['data']);
                }
                $param = array(
                    'CHECK_ID' => $_POST['data']['CHECK_ID'],
                    'REPORT_LIST_ID' => $data[$i]['REPORT_LIST_ID'],
                    'CHECK_LIST_ID' => $data[$i]['CHECK_LIST_ID'],
                    'ROW_NO' => $data[$i]['ROW_NO'],
                    'ROW_NO1' => $data[$i]['ROW_NO1'],
                    'POINTED' => $data[$i]['POINTED'],
                    'IMPROVE_DETAIL' => $data[$i]['IMPROVE_DETAIL'],
                    'IMPROVE_PLAN_DT' => $data[$i]['IMPROVE_PLAN_DT'],
                    'IMPROVE_DT' => $data[$i]['IMPROVE_DT'],
                    'KEYPERSON_CHECK' => $data[$i]['KEYPERSON_CHECK'],
                    'KEYPERSON_COMMENT' => $data[$i]['KEYPERSON_COMMENT'],
                );
                if ($chkRes['data'][0]['COUNT'] == 0) {
                    $getid = $this->HMAUDReportInput->getMaxid();
                    if (!$getid['result']) {
                        throw new \Exception($getid['data']);
                    }
                    $maxid = $getid['data'][0]['REPORT_LIST_ID'];
                    // 20241030 LHB ins s
                    $count_row_no = $this->HMAUDReportInput->count_row_no($param['CHECK_ID'], $param['CHECK_LIST_ID']);
                    if (!$count_row_no['result']) {
                        throw new \Exception($count_row_no['data']);
                    }
                    $param['ROW_NO1'] = $count_row_no['data']['0']['ROW_NO'];
                    // 20241030 LHB ins e
                    //監査実績データを追加する
                    $insRes = $this->HMAUDReportInput->insReportDetailSql($param, $maxid);
                    if (!$insRes['result']) {
                        throw new \Exception($insRes['data']);
                    }
                } else {
                    //監査実績データを更新する
                    $updRes = $this->HMAUDReportInput->updReportDetailSql($param);
                    if (!$updRes['result']) {
                        throw new \Exception($updRes['data']);
                    }
                }

            }
            //エラーがない場合、コミットする
            $this->HMAUDReportInput->Do_commit();
            $tranStartFlg = FALSE;
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($tranStartFlg) {
                $this->HMAUDReportInput->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

}
