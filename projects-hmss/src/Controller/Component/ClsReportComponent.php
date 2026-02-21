<?php
//App::uses('ClsLogControl', 'Model/Component');
namespace App\Controller\Component;

use Cake\Controller\Component;

class ClsReportComponent extends Component
{

    //*************************************
    // * 公開メソッド
    //*************************************

    public function fncGetReport($reportNM, $data, $count = 1)
    {
        $tunix = time();

        $reportPrintPath = str_replace("Controller/Component", "webroot/reports", dirname(__FILE__));

        $dataReports = array();

        for ($i = 0; $i < $count; $i++) {
            for ($nmkey = 0; $nmkey < count($reportNM); $nmkey++) {
                $tmp_path = str_replace("Controller/Component", "Model/Report", dirname(__FILE__)) . "/" . $reportNM[$nmkey] . ".html";

                $html = file_get_contents($tmp_path, true);

                $reportPrintNM = $reportNM[$nmkey] . "_" . $i . "_" . $tunix . ".html";

                foreach ($data[$i] as $key => $value) {
                    $html = str_replace("[" . $key . "]", $value, $html);
                }

                file_put_contents($reportPrintPath . "/" . $reportPrintNM, $html);

                array_push($dataReports, $reportPrintNM);
            }
        }
        return $dataReports;
    }

    public function fncReportPreview($reports, $previewNM)
    {
        $reportHtml = "";

        $tunix = time();

        $previewNM = $previewNM . "_" . $tunix . ".html";

        $reportPrintPath = str_replace("Controller/Component", "webroot/reports", dirname(__FILE__));

        for ($i = 0; $i < count($reports); $i++) {
            $tmp_path = str_replace("Controller/Component", "webroot/reports", dirname(__FILE__)) . "/" . $reports[$i];

            $html = file_get_contents($tmp_path, true);

            $reportHtml .= $html;
        }

        file_put_contents($reportPrintPath . "/" . $previewNM, $reportHtml);

        $previewNM = "reports/" . $previewNM;

        return $previewNM;

    }

    public function fncGetTblReport($reportNM, $data, $keys, $headerKeys = array())
    {
        try {

            $dataReports = array();
            $strtbl = "";
            $dataAll = count($data);
            $pageCnt = (int) ($dataAll / 48);

            if (($dataAll % 48) > 0) {
                $pageCnt = $pageCnt + 1;
            }

            for ($j = 0; $j < $pageCnt; $j++) {
                $tmp_path = str_replace("Controller/Component", "Model/Report", dirname(__FILE__)) . "/" . $reportNM . ".html";

                $html = file_get_contents($tmp_path, true);

                if (isset($headerKeys) && count($headerKeys) > 0) {
                    foreach ($headerKeys as $headval) {
                        $html = str_replace("[" . $headval . "]", $data[0][$headval], $html);
                    }
                }

                $currentCnt = ($j + 1) * 48;
                if ($currentCnt >= $dataAll) {
                    $currentCnt = $dataAll;
                }
                $strtbl = "";

                for ($in = $j * 48; $in < $currentCnt; $in++) {

                    $strtbl .= "<tr>";

                    foreach ($keys as $key) {
                        $strtbl .= "<td>";

                        $strtbl .= $data[$in][$key];

                        $strtbl .= "</td>";

                    }
                    $strtbl .= "</tr>";

                }

                $html = str_replace("[content]", $strtbl, $html);

                $tunix = time();

                $reportPrintNM = $reportNM . "_" . $j . "_" . $tunix . ".html";

                $reportPrintPath = str_replace("Controller/Component", "webroot/reports", dirname(__FILE__));

                file_put_contents($reportPrintPath . "/" . $reportPrintNM, $html);

                array_push($dataReports, $reportPrintNM);

            }

            return $dataReports;
        } catch (\Exception $e) {

        }
    }

}