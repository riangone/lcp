<!-- /**
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
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('SDH/SDH06'));
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="sdh sdh06 dialog" style="width: 100%;height: 100%;" align="center">
    <!-- <style type="text/css">
    .sdh.label {
    float: right
    }
    </style> -->
    <table>
        <tr>
            <td style="width: 38%;font-weight:normal;color:#000000 " align="right">
                <label for="">
                    前管理担当：
                </label>
            </td>
            <td align="left" style="width: 32%">
                <label for="" class="sdh sdh06 lbl_B1_KATANNM value" style="font-weight:normal;color:#000000">
                </label>
            </td>
            <td align="right">
                <label for="" class="sdh sdh06 lbl_B1_KATACHGDAY value" style="font-weight:normal;color:#000000">
                </label>
            </td>
        </tr>
        <tr>
            <td style="font-weight:normal;color:#000000" align="right">
                <label for="">
                    前々管理担当：
                </label>
            </td>
            <td align="left">
                <label for="" class="sdh sdh06 lbl_B2_KATANNM value" style="font-weight:normal;color:#000000">
                </label>
            </td>
            <td align="right">
                <label for="" class="sdh sdh06 lbl_B2_KATACHGDAY value" style="font-weight:normal;color:#000000">
                </label>
            </td>
        </tr>
        <tr>
            <td style="font-weight:normal;color:#000000" align="right">
                <!-- 20171212 lqs UPD S -->
                <!-- <label for=""> -->
                <label for="" style="width:101%">
                    <!-- 20171212 lqs UPD E -->
                    前々前管理担当：
                </label>
            </td>
            <td align="left">
                <label for="" class="sdh sdh06 lbl_B3_KATANNM value" style="font-weight:normal;color:#000000">
                </label>
            </td>
            <td align="right">
                <label for="" class="sdh sdh06 lbl_B3_KATACHGDAY value" style="font-weight:normal;color:#000000">
                </label>
            </td>
        </tr>
        <tr>
            <td style="font-weight:normal;color:#000000" align="right">
                <label for="">
                    販売担当：
                </label>
            </td>
            <td align="left">
                <label for="" class="sdh sdh06 lbl_HAN_BUSMANCD value" style="font-weight:normal;color:#000000">
                </label>
            </td>
            <td></td>
        </tr>
    </table>
</div>