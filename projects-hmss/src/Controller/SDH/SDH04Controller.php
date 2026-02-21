<?php
/**
 * 説明：
 *
 *
 * @author lijun
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
use App\Model\SDH\SDH04;

/**
 * 任意保険＆クレジット情報ダイアログ画面
 * SDH04Controller
 */
class SDH04Controller extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $layout;

    private $tenpo_cd = "";
    private $tenpo_nm = "";
    private $m_SDH04;

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    /**
     * 注文書ダイアログ
     */
    public function index()
    {
        $this->layout = 'SDH04_layout';

        $this->m_SDH04 = new SDH04();
        try {
            $items = array(
                "KAISYAMEI",
                //
                "SYOKENNO",
                //
                "KEIYAKUNAME",
                //
                "SYURUIMEI",
                //
                "HARAIKOMIMEI",
                //
                "HOKENSIKI",
                //
                "HOKENSYUKI",
                //
                "COUNTDATA1",
                //
                "SHR_GKN_DPS",
                //
                "ZAN_SET_GKU",
                //
                "KRJ_MOT_KIN",
                //
                "SCD_NM",
                //
                "ROI",
                //
                "COUNTDATA2",
                //
                "KRJ_BUN_KSU",
                //
                "KRJ_SHR_KKN_FRO",
                //
                "KRJ_SHR_KKN_TO",
                //
                "SHR_DT",
                //
                "BNS_ADD_SHR_GKU",
                //
                "PASENNTO",
                //
                "BNS_SHR_MM1",
                //
                "BNS_SHR_MM2",
                //
                "COUNTDATA3",
                //
                "BNS_KSU",
                //
                "COUNTDATA4",
                //
                "FIR_FNL_SHR_GKU",
                //
                "FIR_FNL_SHR_KSU",
                //
                "COUNTDATA5",
                //
                "MM_SHR_GKU",
                //
                "KRJ_BUN_KSU_VAL",
                //
                "COUNTDATA6",
                //
                "SUM"
            );
            foreach ($items as $key) {
                $this->set($key, '');
            }
            //test
            if (isset($_POST['data'])) {
                $cmn_no = $_POST['data']['CMN_NO'];
            }
            $result = $this->m_SDH04->m_select_Sdh04_M41E10($cmn_no);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $data = $result["data"];
            if ($data != null && count((array) $data) > 0) {

                if ($data[0]['KRJ_MOT_KIN'] == 0 || $data[0]['KRJ_MOT_KIN'] == null) {
                    $data[0]['PASENNTO'] = '';
                } else {
                    //ボーナス加算額＊ボーナス回数/クレジット元金*100%  単位：1％
                    $data[0]['PASENNTO'] = number_format(intval($data[0]['BNS_ADD_SHR_GKU']) * $data[0]['BNS_KSU'] / $data[0]['KRJ_MOT_KIN'] * 100, 0);
                    if ($data[0]['PASENNTO'] == 0) {
                        $data[0]['PASENNTO'] = '';
                    } else {
                        $data[0]['PASENNTO'] = $data[0]['PASENNTO'] . '%';
                    }
                }
                $data[0]['FIR_FNL_SHR_KSU'] = '1';
                // 据置額
                if ($data[0]['ZAN_SET_GKU'] == 0 || $data[0]['ZAN_SET_GKU'] == '0') {
                    $data[0]['ZAN_SET_GKU'] = '';
                }
                // 分割回数
                if ($data[0]['KRJ_BUN_KSU'] == 0 || $data[0]['KRJ_BUN_KSU'] == null) {
                    $data[0]['KRJ_BUN_KSU'] = '';
                } else {
                    $data[0]['KRJ_BUN_KSU'] = $data[0]['KRJ_BUN_KSU'] . '回';
                }
                // 据置額(ZAN_SET_GKU) > 0 の場合に「据置額有」、0 または null の場合「据置額無」となります。
                if ($data[0]['ZAN_SET_GKU'] > 0) {
                    $data[0]['KRJ_BUN_KSU_VAL'] = number_format(intval(str_replace('回', '', $data[0]['KRJ_BUN_KSU']))) - 2;
                }
                if ($data[0]['ZAN_SET_GKU'] == 0 || $data[0]['ZAN_SET_GKU'] == null) {
                    $data[0]['KRJ_BUN_KSU_VAL'] = number_format(intval(str_replace('回', '', $data[0]['KRJ_BUN_KSU']))) - 1;
                }
                //金利 単位：0.01％
                if ($data[0]['ROI'] == '' || $data[0]['ROI'] == null || $data[0]['ROI'] == '0' || $data[0]['ROI'] == 0) {
                    $data[0]['ROI'] = '';
                } else {
                    $data[0]['ROI'] = number_format($data[0]['ROI'], 2) . '%';
                }
                //支払い期間 開始月
                if ($data[0]['KRJ_SHR_KKN_FRO'] != "") {
                    $data[0]['KRJ_SHR_KKN_FRO'] = substr($data[0]['KRJ_SHR_KKN_FRO'], 0, 4) . '/' . substr($data[0]['KRJ_SHR_KKN_FRO'], 4, 2);
                }
                //支払い期間 終了月
                if ($data[0]['KRJ_SHR_KKN_TO'] != "") {
                    $data[0]['KRJ_SHR_KKN_TO'] = substr($data[0]['KRJ_SHR_KKN_TO'], 0, 4) . '/' . substr($data[0]['KRJ_SHR_KKN_TO'], 4, 2);
                }
                //
                if ($data[0]['KRJ_SHR_KKN_FRO'] == '' && $data[0]['KRJ_SHR_KKN_TO'] == '') {
                    $data[0]['COUNTDATA2'] = '';
                } else {
                    $data[0]['COUNTDATA2'] = '～';
                }
                // //@formatter:on
                //据置額＋初回支払額＋ボーナス加算額＊回数＋毎月支払い額＊回数
                //@formatter:off
                $data[0]['SUM'] = number_format((int) $data[0]['ZAN_SET_GKU'] + (int) $data[0]['FIR_FNL_SHR_GKU'] + (int) $data[0]['BNS_ADD_SHR_GKU'] * (int) $data[0]['BNS_KSU'] + (int) $data[0]['MM_SHR_GKU'] * (int) $data[0]['KRJ_BUN_KSU_VAL']);
                //@formatter:on

                if ($data[0]['SUM'] == 0) {
                    $data[0]['SUM'] = '';
                }

                //現金（頭金）
                $data[0]['SHR_GKN_DPS'] = number_format(intval($data[0]['SHR_GKN_DPS']));
                if ($data[0]['SHR_GKN_DPS'] == 0) {
                    $data[0]['SHR_GKN_DPS'] = '';
                }

                //クレジット元金
                $data[0]['KRJ_MOT_KIN'] = number_format(intval($data[0]['KRJ_MOT_KIN']));
                if ($data[0]['KRJ_MOT_KIN'] == 0) {
                    $data[0]['KRJ_MOT_KIN'] = '';
                }

                //ボーナス支払月
                if ($data[0]['BNS_SHR_MM1'] == '' && $data[0]['BNS_SHR_MM2'] == '') {
                    $data[0]['COUNTDATA3'] = '';
                } else {
                    $data[0]['COUNTDATA3'] = '～';
                }

                //ボーナス加算額
                $data[0]['BNS_ADD_SHR_GKU'] = number_format(intval($data[0]['BNS_ADD_SHR_GKU']));
                if ($data[0]['BNS_ADD_SHR_GKU'] == 0) {
                    $data[0]['BNS_ADD_SHR_GKU'] = '';
                }
                if ($data[0]['BNS_KSU'] == 0) {
                    $data[0]['BNS_KSU'] = '';
                }
                if ($data[0]['BNS_ADD_SHR_GKU'] == '' && $data[0]['BNS_KSU'] == '') {
                    $data[0]['COUNTDATA4'] = '';
                } else {
                    $data[0]['COUNTDATA4'] = '　×　';
                }
                if (number_format(intval($data[0]['BNS_ADD_SHR_GKU'])) * number_format(intval($data[0]['BNS_KSU'])) == 0) {
                    $data[0]['BNS_ADD_SHR_GKU'] = '';
                    $data[0]['BNS_KSU'] = '';
                    $data[0]['COUNTDATA4'] = '';
                }

                //初回支払い金額
                $data[0]['FIR_FNL_SHR_GKU'] = number_format(intval($data[0]['FIR_FNL_SHR_GKU']));
                if ($data[0]['FIR_FNL_SHR_GKU'] == 0) {
                    $data[0]['FIR_FNL_SHR_GKU'] = '';
                }
                $data[0]['FIR_FNL_SHR_KSU'] = number_format(intval($data[0]['FIR_FNL_SHR_KSU']));
                if ($data[0]['FIR_FNL_SHR_KSU'] == 0) {
                    $data[0]['FIR_FNL_SHR_KSU'] = '';
                }
                if ($data[0]['FIR_FNL_SHR_GKU'] == '' && $data[0]['FIR_FNL_SHR_KSU'] == '') {
                    $data[0]['COUNTDATA5'] = '';
                } else {
                    $data[0]['COUNTDATA5'] = '　×　';
                }
                if (number_format(intval($data[0]['FIR_FNL_SHR_GKU'])) * number_format(intval($data[0]['FIR_FNL_SHR_KSU'])) == 0) {
                    $data[0]['FIR_FNL_SHR_GKU'] = '';
                    $data[0]['FIR_FNL_SHR_KSU'] = '';
                    $data[0]['COUNTDATA5'] = '';
                }

                //毎月金額
                $data[0]['MM_SHR_GKU'] = number_format(intval($data[0]['MM_SHR_GKU']));
                $data[0]['KRJ_BUN_KSU_VAL'] = number_format(intval($data[0]['KRJ_BUN_KSU_VAL']));
                if ($data[0]['MM_SHR_GKU'] == 0 && $data[0]['KRJ_BUN_KSU_VAL'] == 0) {
                    $data[0]['COUNTDATA6'] = '';
                } else {
                    $data[0]['COUNTDATA6'] = '　×　';
                }
                if (number_format(intval($data[0]['MM_SHR_GKU'])) * number_format(intval($data[0]['KRJ_BUN_KSU_VAL'])) == 0) {
                    $data[0]['MM_SHR_GKU'] = '';
                    $data[0]['KRJ_BUN_KSU_VAL'] = '';
                    $data[0]['COUNTDATA6'] = '';
                }
                foreach ((array) $data[0] as $key => $value) {
                    $this->set($key, $value);
                }

                $syadaino = trim($data[0]['SDI_KAT']) . '-' . trim($data[0]['CAR_NO']);
                $TOU_DT = trim($data[0]['TOU_DT']);

                $result = $this->m_SDH04->m_select_Sdh04_MCI_0170($syadaino, $TOU_DT);
                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }
                $data1 = $result["data"];
                if ($data1 != null && count((array) $data1) > 0) {
                    //始期
                    if ($data1[0]['HOKENSIKI'] != null) {
                        if ($data1[0]['HOKENSIKI'] != "") {
                            //@formatter:off
                            $data1[0]['HOKENSIKI'] = substr($data1[0]['HOKENSIKI'], 0, 4) . '/' . substr($data1[0]['HOKENSIKI'], 4, 2) . '/' . substr($data1[0]['HOKENSIKI'], 6, 2);
                            //@formatter:on
                        }
                    }
                    //終期
                    if ($data1[0]['HOKENSYUKI'] != null) {
                        if ($data1[0]['HOKENSYUKI'] != "") {
                            //@formatter:off
                            $data1[0]['HOKENSYUKI'] = substr($data1[0]['HOKENSYUKI'], 0, 4) . '/' . substr($data1[0]['HOKENSYUKI'], 4, 2) . '/' . substr($data1[0]['HOKENSYUKI'], 6, 2);
                            //@formatter:off
                        }
                    }
                    if ($data1[0]['HOKENSIKI'] != '' && $data1[0]['HOKENSIKI'] != null && $data1[0]['HOKENSYUKI'] != '' && $data1[0]['HOKENSYUKI'] != null){
                        $data1[0]['COUNTDATA1'] = '～';
                    } else{
                        $data1[0]['COUNTDATA1'] = '';
                    }
                    if ($data1[0]['SYARYO'] == '99'){
                        $data1[0]['SYURUIMEI'] = $data1[0]['SYURUIMEI'] . "＋車両特約付";
                    }
                    foreach ((array)$data1[0] as $key => $value){
                        $this->set($key, $value);
                    }
                }
            }
        } catch (\Exception $e){
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->render('/SDH/SDH04/index', $this->layout);
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