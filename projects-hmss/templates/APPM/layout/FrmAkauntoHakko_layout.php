<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmAkauntoHakko'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmAkauntoHakko' id="FrmAkauntoHakko">
    <div class='FrmAkauntoHakko center FrmAkauntoHakko-content'>
        <table class="FrmAkauntoHakko center tbl" border='0' style="margin:10px 20px;">
            <tr>
                <td style="padding-right:10px;"> お客様No </td>
                <td>
                    <input class="FrmAkauntoHakko Tab Enter txtCusNo" type='text' maxlength="10" />
                </td>
                <td style="padding-left:10px;">
                    <div class="HMS-button-pane" style="margin-top:0px;">
                        <button class="FrmAkauntoHakko Tab Enter btnSearch">
                            検索
                        </button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="FrmAkauntoHakko ui-jqgrid ui-widget ui-widget-content ui-corner-all box confirmBox"
    style="margin-top:50px;">
    <!-- 20171211 lqs UPD S -->
    <!-- <div class="ui-state-default ui-jqgrid-hdiv normalHeader"> -->
    <div class="ui-state-default ui-jqgrid-hdiv normalHeader" style="overflow:hidden">
        <!-- 20171211 lqs UPD E -->
        アカウント発行確認
    </div>
    <p>
        入力した顧客Noのお客様情報は以下の通りです。
        <br />
        ID/PWを発行したいお客様に間違いがないか確認してください。
    </p>

    <div class="innerBox">
        <!-- 20171211 lqs UPD S -->
        <!-- <div class="ui-state-default ui-jqgrid-hdiv normalHeader"> -->
        <div class="ui-state-default ui-jqgrid-hdiv normalHeader" style="overflow:hidden">
            <!-- 20171211 lqs UPD E -->
            お客様情報
        </div>
        <table class="FrmAkauntoHakko table" style="width:98%;margin:5px;" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td style="text-align: left;"> お客様名 </td>
                    <td colspan="3">
                        <input type="text" value="山田 太郎" class="FrmAkauntoHakko CSRNM1" disabled="disabled"
                            style="width:354.5px;">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;"> 自宅TEL </td>
                    <td>
                        <input type="text" value="123-123-123" class="FrmAkauntoHakko HOM_TEL" disabled="disabled">
                    </td>
                    <td style="text-align: left;"> 携帯TEL </td>
                    <td>
                        <input type="text" value="123-123-123" class="FrmAkauntoHakko MOB_TEL" disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;"> 住所 </td>
                    <td colspan="3">
                        <input type="text" value="（GD）県（GD）市南区大州4-10-11" class="FrmAkauntoHakko CSRAD colspan"
                            disabled="disabled" style="width:354.5px;">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="innerBox" style="margin-top:12px;border:none;">
        <table id="FrmAkauntoHakko_jqgrid"></table>
    </div>

    <table style="width:100%">
        <tbody>
            <tr>
                <td align="center">
                    <div class="HMS-button-pane">
                        <button class="FrmAkauntoHakko Tab Enter btnIssue">
                            ID/仮PW発行
                        </button>
                    </div>
                </td>
                <td align="center">
                    <div class="HMS-button-pane">
                        <button class="FrmAkauntoHakko Tab Enter btnCancel">
                            キャンセル
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>