<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmPrintTanto/frmPrintTanto'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<div class="FrmPrintTanto">
    <div class="R4-content">
        <table>
            <tr>
                <td>
                    <label style="margin-left: 10px" for="">
                        担当者姓
                    </label>
                </td>
                <td>
                    <input class="FrmPrintTanto txtTANTO_SEI Enter Tab" name="FrmPrintTanto_txtTANTO_SEI" type="text"
                        maxlength="64" />
                </td>
            </tr>
            <tr>
                <td>
                    <label style="margin-left: 10px " for="">
                        担当者名
                    </label>
                </td>
                <td>
                    <input class="FrmPrintTanto txtTANTO_MEI Enter Tab" name="FrmPrintTanto_txtTANTO_MEI" type="text"
                        maxlength="64" />
                </td>
            </tr>
            <tr>
                <td align="right">
                    <label style="margin-left: 10px" for="">
                        部署名
                    </label>
                </td>
                <td>
                    <input class="FrmPrintTanto txtBUSYO_NM Enter Tab" name="FrmPrintTanto_txtBUSYO_NM" type="text"
                        maxlength="12" />
                </td>
            </tr>
        </table>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmPrintTanto cmdReg Enter Tab">
                    登録
                </button>
            </div>
        </div>
    </div>
</div>
