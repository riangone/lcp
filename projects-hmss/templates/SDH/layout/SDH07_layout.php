<!-- /**
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
*/ -->
<!DOCTYPE html>

<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('SDH/SDH07'));
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class="sdh sdh07 dialog" style="width: 100%;height: 100%;overflow-y:inherit " align="center">
    <div style="width: 100%; float:left; border:1px solid #000000">
        <table cellspacing="2" border=0 style='border-color: #FFFFFF' width=100%>
            <tr>
                <td class="sdh sdh07 URG_DT div" width="33%">
                    <label for="">
                        入庫日</label>
                </td>
                <td class="sdh sdh07 NYUKOKBN div" width="33%">
                    <label for="">
                        入庫区分</label>
                </td>
                <td class="sdh sdh07 USER_ID div" width="34%">
                    <label for="">
                        受付担当</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="" class="sdh sdh07 URG_DT value">
                        <?php
                        echo $sdh07_urgdt;
                        ?>
                    </label>
                </td>
                <td>
                    <label for="" class="sdh sdh07 NYUKOKBN value">
                        <?php
                        echo $sdh07_nyukokbn;
                        ?>
                    </label>
                </td>
                <td>
                    <label for="" class="sdh sdh07 USER_ID value">
                        <?php
                        echo $sdh07_knj;
                        ?>
                </td>
            </tr>
        </table>
    </div>
    <div class='sdh sdh07 listArea'
        style='margin-top:15px;   width:100%;  height: 80%; overflow-Y: auto; float:left; border:1px solid #000000'
        align="center">
        <!-- 20180408 YIN UPD S -->
        <!-- <table cellspacing="2" border=0 rules="cols" width="100%" style='border-color: #FFFFFF'> -->
        <table cellspacing="2" border=0 width="100%" style='border-color: #FFFFFF'>
            <!-- 20180408 YIN UPD E -->
            <tr>
                <td class="sdh sdh07 SEQ div" width="40px">
                    <label for="">
                        SEQ</label>
                </td>
                <td class="sdh sdh07 NYUKOKBNNGKBN div" width="110px">
                    <label for="">
                        入庫区分</label>
                </td>
                <td class="sdh sdh07 SAG_NM div" width="350px">
                    <label for="">
                        受付担当</label>
                </td>
                <td class="sdh sdh07 URG_GKU div" width="105px">
                    <label for="">
                        税抜価格</label>
                </td>
                <td class="sdh sdh07 ZKM_TGK div" width="105px">
                    <label for="">
                        総額</label>
                </td>
            </tr>
            <?php
            echo $sdh07_table;
            ?>
        </table>
    </div>
</div>