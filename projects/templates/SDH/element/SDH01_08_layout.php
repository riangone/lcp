<!-- /**
* 説明：
*
*
* @author zhenghuiyun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
* --------------------------------------------------------------------------------------------
*/ -->
<table style="width:100%">
    <?php

    //車両区分 s
    $arr_syaryo_kubun = array(
        0 => "00：自新直",
        1 => "01：自新業",
        2 => "02：自新特",
        3 => "03：他社新",
        5 => "05：自中直",
        4 => "04：自新リ",
    );
    //車両区分 e
    
    $syaryo_kubun = -1;
    $cnt = count($arr_result_shd01_08);
    for ($i = 0; $i < $cnt; $i++) {
        $data = $arr_result_shd01_08[$i];

        //車両区分
        if ($syaryo_kubun !== $data["XH10CAID"]) {
            $syaryo_kubun = $data["XH10CAID"];

            echo "<tr style=\"width:100%\">";
            echo "<td style=\"width:100%\">";
            echo "<div class=\"sdh sdh01 sdh01_08 title carlistTitle\" style=\"width:100%\">";
            echo $arr_syaryo_kubun[$syaryo_kubun];
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        }

        echo "<tr style=\"width:100%\">";
        echo "<td style=\"width:100%\">";

        echo "<div class=\"sdh sdh01 sdh01_08 item ALL carlist\" style=\"width: 100%;height: 100%;cursor: pointer\">";

        // 注文書NO s
        echo "<div class=\"sdh sdh01 sdh01_08 item ORDERNO\" style=\"display: none\">";
        echo $data["ORDERNO"];
        echo "</div>";

        // 車台番号 s
        echo "<div class=\"sdh sdh01 sdh01_08 item VIN_WMIVDS\" style=\"display: none\">";
        echo $data["VIN_WMIVDS"];
        echo "</div>";

        // カーNo s
        echo "<div class=\"sdh sdh01 sdh01_08 item VIN_VIS\" style=\"display: none\">";
        echo $data["VIN_VIS"];
        echo "</div>";

        echo "<table style=\"width:100%\">";
        echo "<tr style=\"width:100%\">";
        echo "<td style=\"width:100%\" colspan=\"2\">";
        echo "<div class=\"sdh sdh01 sdh01_08 item CSRNM1\" style=\"width:100%;text-align: left\">";

        // お客様名 s
        echo $data["CSRNM1"];

        echo "</div>";
        echo "</td>";
        echo "</tr>";
        echo "<tr style=\"width:100%\">";
        echo "<td style=\"width:50%\">";
        echo "<div class=\"sdh sdh01 sdh01_08 item VCLIPEDT\" style=\"width:100%;text-align: left\">";

        // 車検年月 s
        echo $data["VCLIPEDT"];

        echo "</div>";
        echo "</td>";
        echo "<td style=\"width:50%\">";
        echo "<div class=\"sdh sdh01 sdh01_08 item VCLNM\" style=\"width:100%;text-align: left\">";

        // 車名 s
        echo $data["VCLNM"];

        echo "</div>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";

        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>