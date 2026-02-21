<?php
/**
 * 説明：
 *
 *
 * @author jinmingai
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\SDH;

use App\Controller\AppController;
use App\Model\SDH\SDH03;

/**
 * 注文書ダイアログ
 * SDH03Controller
 */
class SDH03Controller extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $layout;
    private $m_SDH03;
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    public function index()
    {
        $this->layout = 'SDH03_layout';

        $this->m_SDH03 = new SDH03();

        if (isset($_POST['data'])) {
            $cmn_no = $_POST['data']['CMN_NO'];
        }
        $result = $this->m_SDH03->m_select_M41E10($cmn_no);
        $data = $result["data"];
        if ($data != null && count((array) $data) > 0) {
            $TOURK_NO = $data[0]["TOURK_NO"];
            $VCLRGTNO_LAND = substr($TOURK_NO, 0, 5);
            $VCLRGTNO_LAND = trim($VCLRGTNO_LAND);
            $result1 = $this->m_SDH03->m_select_M27M14($VCLRGTNO_LAND);
            $data1 = $result1["data"];
            $data[0]["VCLRGTNO_LAND_NM"] = $data1[0]["VCLRGTNO_LAND_NM"];
            $data[0]["TOURK_NO"] = $data1[0]["VCLRGTNO_LAND_NM"] . mb_convert_kana(substr($TOURK_NO, 5), "Hc");
        }
        $items = array(
            "UC_NO",
            "CMN_NO",
            "TOU_DT",
            "BUY_SHP",
            "ABHOT_KB",
            "HBSS_CD",
            "SDI_KAT",
            "CAR_NO",
            "CARNO",
            "TOURK_NO",
            "VCLNM",
            "KYK_YBN_NO",
            "KYK_CUS_NM1",
            "SIY_YBN_NO",
            "ZYUSYO",
            "SIY_CUS_NM1",
            "SIY_YBN_NO",
            "SIY_CUS_NM1",
            "KYOTN_RKN",
            "DAIRITN_CD",
            "SRY_HTA_PRC_ZKM",
            "SRY_HTA_NBK_GKU_ZKM",
            "FZH_SUM_GKU_ZKM",
            "TKB_KSH_SUM_GKU_ZKM",
            "TKB_KSH_NBK_SUM_GKU_ZKM",
            "KAP_TES",
            "BET_SHR_HYO_SUM_GKU_ZKM",
            "OPT_HOK_KIN",
            "TRA_CAR_RCY_YTK_SUM_GKU",
            "SIY_SMI_CAR_KNR_HYO",
            "SHR_GKN_DPS",
            "KAP_MOT_KIN",
            "KAP_TES",
            "TRA_CAR_PRC_SUM",
            "TRA_CAR_SHZ_SUM",
            "TRA_CAR_ZSI_SUM",
            "YHN_NM",
            "KYK_ADR1",
            "KYK_ADR2",
            "KYK_ADR3",
            "SIY_ADR1",
            "SIY_ADR2",
            "SIY_ADR3",
            "SYAIN_KNJ_SEI",
            "SYAIN_KNJ_MEI",
            "SRY_TET_HWS_KKU",
            "KGK_KKU_KEI",
            "HBA_KKU_GKE",
            "KONYU_ZYK_GOK",
            "KAP_KEI",
            "SIHARAI",
            "SITATORI",
            "RCYL_GK"
        );

        foreach ($items as $key) {
            $this->set($key, "");
        }

        $TOU_DT_YMD = "";
        if ($data != null && count((array) $data) > 0) {
            foreach ($items as $key) {
                if (array_key_exists($key, (array) $data[0])) {
                    $value = $data[0][$key];
                    $this->set($key, $value);
                }

                switch ($key) {
                    case 'TOU_DT':
                        $TOU_DT_YMD = substr($value, 0, 4) . "/" . substr($value, 4, 2) . "/" . substr($value, 6, 2);
                        $this->set($key, $TOU_DT_YMD);
                        $TOU_DT_YMD = $value;
                        break;

                    case 'BUY_SHP':
                        $BUY_SHP_value = $this->m_SDH03->m_select_BUY_KEITAI_sql($value);
                        $this->set("BUY_SHP", $BUY_SHP_value);
                        break;

                    case 'ABHOT_KB':
                        if ($TOU_DT_YMD < "20130401") {
                            $BSALE_KEITAI_BF_value = $this->m_SDH03->m_select_SALE_KEITAI_BF_sql($value);
                            $this->set("ABHOT_KB", $BSALE_KEITAI_BF_value);
                        } else {
                            $BSALE_KEITAI_AF_value = $this->m_SDH03->m_select_SALE_KEITAI_AF_sql($value);
                            $this->set("ABHOT_KB", $BSALE_KEITAI_AF_value);
                        }

                        break;

                    case 'CARNO':
                        if (trim(str_replace("-", "", $value) == "")) {
                            $this->set("CARNO", "");
                        } else {
                            $this->set("CARNO", $value);
                        }

                        break;

                    case 'TKB_KSH_SUM_GKU_ZKM':
                        $value = $value - $data[0]['TKB_KSH_NBK_SUM_GKU_ZKM'];
                        $this->set("TKB_KSH_SUM_GKU_ZKM", $value);
                        break;

                    default:
                        break;
                }
            }
        }
        // $this->render('/SDH/SDH03/index', 'SDH03_layout');

        /**
         * 扱者取得
         */
        $result = $this->m_SDH03->m_select_sdh_08($cmn_no);
        $data = $result["data"];

        if ($data != null && count((array) $data) > 0) {
            $value = $data[0]["KYOTN_RKN"];
            $this->set("KYOTN_RKN", $value);

            $value = $data[0]["SYAIN_KNJ_SEI"];
            $this->set("SYAIN_KNJ_SEI", $value);

            $value = $data[0]["SYAIN_KNJ_MEI"];
            $this->set("SYAIN_KNJ_MEI", $value);

            $value = $data[0]["DAIRITN_NM"];
            $this->set("DAIRITN_CD", $value);

        }

        /**
         * 使用済車引取販売店支払額取得
         */
        $result = $this->m_SDH03->m_select_sdh_09($cmn_no);
        $data = $result["data"];
        if ($data != null) {
            $this->set("SIYOUZUMI", $data[0]["支払額"]);
        } else {
            $this->set("SIYOUZUMI", "0");
        }

        /**
         * 付属品明細１取得
         */
        $result = $this->m_SDH03->m_select_M41E12_1_sql($cmn_no);
        $data = $result["data"];

        $nbk_zkm1_total = 0;
        $TTH_ICD_PRC_ZKM1_total = 0;
        $tableStr = "";
        foreach ((array) $data as $key => $value) {
            $tableStr .= "<tr>";
            $tableStr .= "<td style='width:65%'>";
            $tableStr .= "<div class='sdh sdh03 div_ div sfont'>";
            $tableStr .= "<label for=''>";
            $tableStr .= $value['YHN_NM'];
            $tableStr .= "</label>";
            $tableStr .= "</div>";
            $tableStr .= "</td>";
            $tableStr .= "<td class='sdh sdh03 price'>";
            $tableStr .= "<label for='' class='sdh sdh03 lbl_ value sfont'>";

            $TTH_ICD_PRC_ZKM1_total += $value['TTH_ICD_PRC_ZKM'];
            $value['TTH_ICD_PRC_ZKM'] = $value['TTH_ICD_PRC_ZKM'] == 0 ? "" : number_format($value['TTH_ICD_PRC_ZKM']);
            $tableStr .= $value['TTH_ICD_PRC_ZKM'];

            $tableStr .= "</label>";
            $tableStr .= "</td>";
            $tableStr .= "</tr>";
            $nbk_zkm1_total += $value['NBK_ZKM'];
        }
        $this->set("table_m41e12_1", $tableStr);
        $this->set("NBK_ZKM1", $nbk_zkm1_total);
        $this->set("HUZOKU1", $TTH_ICD_PRC_ZKM1_total);
        $this->set("NBK_ZKM_KAKAKU1", $TTH_ICD_PRC_ZKM1_total - $nbk_zkm1_total);

        /**
         * 付属品明細２取得
         */
        $result = $this->m_SDH03->m_select_M41E12_2_sql($cmn_no);
        $data = $result["data"];

        $nbk_zkm2_total = 0;
        $TTH_ICD_PRC_ZKM2_total = 0;
        $tableStr = "";
        foreach ((array) $data as $key => $value) {
            $tableStr .= "<tr>";
            $tableStr .= "<td style='width:65%'>";
            // $tableStr .= "<div style='background-color:#99FFFF;color: #808080;font-weight: bold;'>";
            $tableStr .= "<div class='sdh sdh03 div_ div sfont'>";
            $tableStr .= "<label for=''>";
            $tableStr .= $value['YHN_NM'];
            $tableStr .= "</label>";
            $tableStr .= "</div>";
            $tableStr .= "</td>";
            $tableStr .= "<td class='sdh sdh03 price'>";
            $tableStr .= "<label for='' class='sdh sdh03 lbl_ value sfont'>";
            $TTH_ICD_PRC_ZKM2_total += $value['TTH_ICD_PRC_ZKM'];
            $value['TTH_ICD_PRC_ZKM'] = $value['TTH_ICD_PRC_ZKM'] == 0 ? "" : number_format($value['TTH_ICD_PRC_ZKM']);
            $tableStr .= $value['TTH_ICD_PRC_ZKM'];
            $tableStr .= "</label>";
            $tableStr .= "</td>";
            $tableStr .= "</tr>";
            $nbk_zkm2_total += $value['NBK_ZKM'];
        }
        $this->set("table_m41e12_2", $tableStr);
        $this->set("NBK_ZKM2", $nbk_zkm2_total);
        $this->set("HUZOKU2", $TTH_ICD_PRC_ZKM2_total);
        $this->set("NBK_ZKM_KAKAKU2", $TTH_ICD_PRC_ZKM2_total - $nbk_zkm2_total);
        $this->render('/SDH/SDH03/index', $this->layout);
    }

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // ==========
    // = メソッド end =
    // ==========

}