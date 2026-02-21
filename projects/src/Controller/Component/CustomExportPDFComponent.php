<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\HMDPS\Component\CustomExportPDF;

class CustomExportPDFComponent extends Component
{
    public $CustomExportPDF;
    public function FncDenpyoinsatuPrint($flag, $postdata)
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        $rpx_file_names = array();
        $datas = array();
        $blnTran = FALSE;
        $print_flag = 0;
        try {
            $this->CustomExportPDF = new CustomExportPDF();
            if ($flag == "100" || $flag == "101") {
                $result = $this->CustomExportPDF->fncPrint("1");
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                if (count((array) $result['data']) > 0) {
                    $result_GroupS = $this->CustomExportPDF->fncGroup("1");
                    if (!$result_GroupS['result']) {
                        throw new \Exception($result_GroupS['data']);
                    }
                    $strPath = dirname(dirname(__FILE__));

                    include_once $strPath . "/HMDPS/Component/tcpdf/rpx_to_pdf.php";
                    include_once $strPath . "/HMDPS/Component/tcpdf/rptDenpyoinsatu.inc";
                    include_once $strPath . "/HMDPS/Component/tcpdf/rptDenpyoinsatu2.inc";
                    $pdfDT = array();
                    $arr = array();
                    foreach ((array) $result_GroupS['data'] as $key_group => $value_group) {
                        $arr = array();
                        $arr['data'] = array();
                        $first_flag = 1;
                        foreach ((array) $result['data'] as $key => $value) {

                            if ($value['SYOHY_NO'] == $value_group['SYOHY_NO'] && $value['KEIRI_DT'] == $value_group['KEIRI_DT']) {
                                if ($first_flag == 1) {
                                    $arr['SYOHY_NO'] = $value['SYOHY_NO'];
                                    $arr['PRINT_DATE'] = $value['PRINT_DATE'];
                                    $arr['KEIRI_DT'] = $value['KEIRI_DT'];
                                    $arr['BIKOU'] = $value['BIKOU'];
                                    $arr['ZEIKM_GK_GOUKEI'] = $value['KEIRI_DT'] == null ? $value['ZEIKM_GK_WITHOUT_DATE'] : $value['ZEIKM_GK_WITH_DATE'];
                                    $first_flag = 2;
                                }
                                $value['ZEIKM_GK_GOUKEI'] = $arr['ZEIKM_GK_GOUKEI'];
                                array_push($arr['data'], $value);

                            }

                        }
                        array_push($pdfDT, $arr);
                    }
                    $datas['rptDenpyoinsatu']['data'] = $pdfDT;
                    $datas['rptDenpyoinsatu']['mode'] = '1';
                    $rpx_file_names['rptDenpyoinsatu'] = $data_fields_rptDenpyoinsatu;
                    $rpx_file_names['rptDenpyoinsatu2'] = $data_fields_rptDenpyoinsatu2;
                } else {
                    $print_flag++;
                }
            }
            if ($flag == "100" || $flag == "102") {
                $result_Denpyo = $this->CustomExportPDF->fncPrint("2");
                if (!$result_Denpyo['result']) {
                    throw new \Exception($result_Denpyo['data']);
                }
                if (count((array) $result_Denpyo['data']) > 0) {
                    $print_flag = False;
                    $strPath = dirname(dirname(__FILE__));
                    include_once $strPath . "/HMDPS/Component/tcpdf/rpx_to_pdf.php";
                    include_once $strPath . "/HMDPS/Component/tcpdf/rptShiharaiDenpyo.inc";
                    include_once $strPath . "/HMDPS/Component/tcpdf/rptShiharaiDenpyo2.inc";

                    $datas['rptShiharaiDenpyo']['data'] = $result_Denpyo['data'];
                    $datas['rptShiharaiDenpyo']['mode'] = '0';
                    $rpx_file_names['rptShiharaiDenpyo'] = $data_fields_rptShiharaiDenpyo;
                    $rpx_file_names['rptShiharaiDenpyo2'] = $data_fields_rptShiharaiDenpyo2;
                } else {
                    $print_flag++;
                }
            }
            if ($print_flag == 2) {
                throw new \Exception("W0024");
            }
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
            $this->CustomExportPDF->Do_transaction();
            $blnTran = TRUE;
            $result_UpdPrint = $this->CustomExportPDF->fncUpdPrintFlg($postdata);
            if (!$result_UpdPrint['result']) {
                throw new \Exception($result_UpdPrint['data']);
            }
            //コミット処理を行う
            $this->CustomExportPDF->Do_commit();
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
            if ($blnTran) {
                $this->CustomExportPDF->Do_rollback();
            }
        }
        return $result;
    }
}
