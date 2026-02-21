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
use App\Model\SDH\SDH07;

/**
 * 車検代替判定画面
 * SDHController
 */
class SDH07Controller extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    public $autoLayout = TRUE;
    // public $autoRender = false;
    public $layout;
    private $m_SDH07;

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
     * デフォルトで最初に実行される機能
     */
    public function index()
    {
        $this->layout = 'SDH07_layout';

        $this->m_SDH07 = new SDH07();

        // test
        $urgdt = $_POST['data']['URG_DT'];
        $nyukokbnmei = $_POST['data']['NYUKOKBNMEI'];
        $syainknjmei = $_POST['data']['SYAIN_KNJ_MEI'];
        $nyukokbn = $_POST['data']['NYUKOKBN'];
        $vinrbn = $_POST['data']['VIN_RBN'];
        $vinsdikat = $_POST['data']['VIN_SDI_KAT'];

        $sebnouno = $_POST['data']['SEB_NOU_NO'];


        $this->set("sdh07_urgdt", $urgdt);
        $this->set("sdh07_nyukokbn", $nyukokbnmei);
        $this->set("sdh07_knj", $syainknjmei);

        //一覧を取得する
        $arrayData['URG_DT'] = str_replace("/", "", $urgdt);
        $arrayData['NYUKOKBN'] = $nyukokbn;
        $arrayData['VIN_RBN'] = $vinrbn;
        $arrayData['VIN_SDI_KAT'] = $vinsdikat;
        $arrayData['SEB_NOU_NO'] = $sebnouno;

        $result = $this->m_SDH07->m_select_Sdh07_JQG($arrayData);

        $data = $result["data"];

        $tableStr = '';

        foreach ((array) $data as $value) {
            if (empty($value['URG_GKU']) || $value['URG_GKU'] == '0') {
                $value['URG_GKU'] = '';
            } else {
                $value['URG_GKU'] = number_format($value['URG_GKU']);
            }
            if (empty($value['ZKM_TGK']) || $value['ZKM_TGK'] == '0') {
                $value['ZKM_TGK'] = '';
            } else {
                $value['ZKM_TGK'] = number_format($value['ZKM_TGK']);
            }
            $tableStr .= "<tr>";
            $tableStr .= "<td style='width:40px'>";
            $tableStr .= $value['DSP_YOU_SEQ'];
            $tableStr .= "</td>";
            $tableStr .= "<td style='width:110px'>";
            $tableStr .= $value['NYUKOKBNNGKBN'];
            $tableStr .= "</td>";
            $tableStr .= "<td style='width:350px'>";
            $tableStr .= $value['SAG_NM'];
            $tableStr .= "</td>";
            $tableStr .= "<td style='width:105px' align='right'>";
            $tableStr .= $value['URG_GKU'];
            $tableStr .= "</td>";
            $tableStr .= "<td style='width:90px' align='right'>";
            $tableStr .= $value['ZKM_TGK'];
            $tableStr .= "</td>";
            $tableStr .= "</tr>";
        }

        $this->set("sdh07_table", $tableStr);


        $this->render('/SDH/SDH07/index', $this->layout);
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