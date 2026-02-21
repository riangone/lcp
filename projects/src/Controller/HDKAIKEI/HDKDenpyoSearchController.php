<?php

namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKDenpyoSearch;

//*******************************************
// * sample controller
//*******************************************
class HDKDenpyoSearchController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKDenpyoSearch;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
        $this->loadComponent('CustomHDKExportPDF');
    }
    public function index()
    {
        // 画面表示内容の設定
        $this->render('index', 'HDKDenpyoSearch_layout');
    }

    public function fncpageload()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(
                'strStartDate' => "",
                'GetBusyoMstValue' => "",
                'GetSyainMstValue' => ""
            ),
            'error' => ''
        );
        try {
            //時間取得
            $strStartDate = $this->ClsComFncHDKAIKEI->FncGetSysDate();
            //20250106 lujunxia del s
            // if (!$strStartDate['result']) {
            //     throw new \Exception($strStartDate['data']);
            // }
            //20250106 lujunxia del e
            $result['data']['sysdate'] = $strStartDate;

            //部署コード
            $GetBusyoMstValue = $this->ClsComFncHDKAIKEI->FncGetCreatBusyoMstValue();

            if (!$GetBusyoMstValue['result']) {
                throw new \Exception($GetBusyoMstValue['data']);
            }
            $result['data']['GetBusyoMstValue'] = $GetBusyoMstValue['data'];

            //社員番号
            $GetSyainMstValue = $this->ClsComFncHDKAIKEI->FncGetSyainMstValue();

            if (!$GetSyainMstValue['result']) {
                throw new \Exception($GetSyainMstValue['data']);
            }
            $result['data']['GetSyainMstValue'] = $GetSyainMstValue['data'];
            // //科目番号
            $GetKamokuMstValue = $this->ClsComFncHDKAIKEI->FncGetKamokuMstValue();

            if (!$GetKamokuMstValue['result']) {
                throw new \Exception($GetKamokuMstValue['error']);
            }
            $result['data']['GetKamokuMstValue'] = $GetKamokuMstValue['data'];
            $Session = $this->request->getSession();
            $result['data']['BusyoCD'] = $Session->read('BusyoCD');
            if (!isset($result['data']['BusyoCD'])) {
                throw new \Exception("W9999");
            }
            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

    public function btnSearchClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            if (isset($_POST['request'])) {
                $postdata = $_POST['request'];
                $this->HDKDenpyoSearch = new HDKDenpyoSearch();

                $this->HDKDenpyoSearch->Do_transaction();
                $blnTran = TRUE;
                $result_del = $this->HDKDenpyoSearch->fncSYOHYNOdelete();
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $result_ins = $this->HDKDenpyoSearch->fncInsTaisyoSyohyNO($postdata);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
                //コミット処理を行う
                $this->HDKDenpyoSearch->Do_commit();
                $blnTran = FALSE;

                $result = $this->HDKDenpyoSearch->fncSelectShiwake($postdata);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                for ($i = 0; $i < count((array) $result['data']); $i++) {
                    $check = $this->HDKDenpyoSearch->fncchkattachment(substr($result['data'][$i]['SYOHY_NO'], 0, 15), substr($result['data'][$i]['SYOHY_NO'], 15, 2));
                    if (!$check['data']) {
                        throw new \Exception($check['data']);
                    }
                    if ($check['data'][0]['COUNT'] > 0) {
                        $result['data'][$i]['FILE'] = "あり";
                    }
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            } else {
                throw new \Exception('値が受信されません');
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->HDKDenpyoSearch->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

    public function btnDenpyPrintClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            if (isset($_POST['data'])) {
                $postdata = $_POST['data'];
                $this->HDKDenpyoSearch = new HDKDenpyoSearch();

                $this->HDKDenpyoSearch->Do_transaction();
                $blnTran = TRUE;
                $result_del = $this->HDKDenpyoSearch->fncDenpyPrintdelete();
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                foreach ($postdata['arr'] as $value) {
                    $result_ins = $this->HDKDenpyoSearch->fncInsTaisyoSyohyNOPrint($value['SYOHY_NO']);
                    if (!$result_ins['result']) {
                        throw new \Exception($result_ins['data']);
                    }
                }

                //コミット処理を行う
                $this->HDKDenpyoSearch->Do_commit();
                $blnTran = FALSE;
                $result_check = $this->HDKDenpyoSearch->fncFlgCheck("DENPYO_SEARCH_PRINT");
                if (!$result_check['result']) {
                    throw new \Exception($result_check['data']);
                }

                foreach ((array) $result_check['data'] as $value) {
                    if ($value['DEL_FLG'] == "1") {
                        throw new \Exception('W9999証憑№：' . $this->ClsComFncHDKAIKEI->FncNv($value['SYOHY_NO']) . $this->ClsComFncHDKAIKEI->FncNv($value['EDA_NO']) . 'は削除されていますので印刷出来ません。印刷対象から外して下さい！');
                    }
                }
                $arr = array();
                $arr['CONST_ADMIN_PTN_NO'] = $postdata['CONST_ADMIN_PTN_NO'];
                $arr['CONST_HONBU_PTN_NO'] = $postdata['CONST_HONBU_PTN_NO'];
                $Session = $this->request->getSession();
                $arr['BusyoCD'] = $Session->read('BusyoCD');
                $printPDF = $this->CustomHDKExportPDF->FncDenpyoinsatuPrint("100", $arr);
                if (!$printPDF['result']) {
                    throw new \Exception($printPDF['error']);
                }
                $result['report'] = $printPDF['report'];
                $result['result'] = true;
            }
        } catch (\Exception $e) {
            $result['result'] = False;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->HDKDenpyoSearch->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }

    public function btnMicheckPrintClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $blnTran = FALSE;
        try {
            if (isset($_POST['data'])) {
                $postdata = $_POST['data'];
                $this->HDKDenpyoSearch = new HDKDenpyoSearch();

                $this->HDKDenpyoSearch->Do_transaction();
                $blnTran = TRUE;
                $result_del = $this->HDKDenpyoSearch->fncMicheckPrintdelete();
                if (!$result_del['result']) {
                    throw new \Exception($result_del['data']);
                }
                $result_ins = $this->HDKDenpyoSearch->fncInsTaisyoSyohyNO($postdata);
                if (!$result_ins['result']) {
                    throw new \Exception($result_ins['data']);
                }
                //コミット処理を行う
                $this->HDKDenpyoSearch->Do_commit();
                $blnTran = FALSE;
                $result_list = $this->HDKDenpyoSearch->fncMicheckList($postdata);
                if (!$result_list['result']) {
                    throw new \Exception($result_list['data']);
                }
                if (count((array) $result_list['data']) == 0) {
                    throw new \Exception('W0024');
                } else {
                    include_once "Component/tcpdf/rpx_to_pdf.php";
                    include_once "Component/tcpdf/rptMicheckIchiran.inc";
                    $pdfDT[0]['data'] = $result_list['data'];
                    $pdfDT[0]['PRINT_DATE'] = $result_list['data'][0]['PRINT_DATE'];
                    $datas = array();
                    $datas['rptMicheckIchiran']['data'] = $pdfDT;

                    $datas['rptMicheckIchiran']['mode'] = '1';
                    $rpx_file_names['rptMicheckIchiran'] = $data_fields_rptMicheckIchiran;
                    $obj = new \rpx_to_pdf($rpx_file_names, $datas);
                    //フォルダのパーミッションチェック

                    if (file_exists($obj->REPORTS_TEMP_PATH)) {
                        if (!(is_readable($obj->REPORTS_TEMP_PATH) && is_writable($obj->REPORTS_TEMP_PATH) && is_executable($obj->REPORTS_TEMP_PATH))) {
                            throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                        }

                    } else {
                        $outFloder = dirname(WWW_ROOT . $obj->REPORTS_TEMP_PATH);
                        if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                            throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                        }
                        if (!mkdir($obj->REPORTS_TEMP_PATH, 0777, TRUE)) {
                            throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                        }
                    }
                    $result['report'] = $obj->to_pdf();
                    unset($obj);
                    $result['result'] = true;
                }

            }
        } catch (\Exception $e) {
            $result['result'] = false;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->HDKDenpyoSearch->Do_rollback();
            }
        }
        $this->fncReturn($result);
    }
}
