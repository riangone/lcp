<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/KRSS/FrmAuthCtlEdit/FrmAuthCtlEdit'));
?>

<div class="KRSS FrmAuthCtlEdit" id="KRSS_FrmAuthCtlEdit">
    <div class="KRSS FrmAuthCtlEdit  content R4-content">
        <table>
            <tr>
                <td>
                    <label class="KRSS FrmAuthCtlEdit Label5 label-snow" for="" style="width: 96px">
                        部署
                    </label>
                </td>
                <td>
                    <input class="KRSS FrmAuthCtlEdit lblBUSYOCD" style="width: 56px" disabled="disabled" />
                </td>
                <td>
                    <input class="KRSS FrmAuthCtlEdit lblBUSYONM" style="width: 184px" disabled="disabled" />
                </td>
                <td width="10">
                </td>
                <td>
                    <label class="KRSS FrmAuthCtlEdit Label9 label-snow" for="" style="width: 96px">
                        配属期間
                    </label>
                </td>
                <td>
                    <input class="KRSS FrmAuthCtlEdit cboSTARTDATE Enter Tab" style="width: 104px"
                        disabled="disabled" />
                </td>
                <td>
                    <label class="KRSS FrmAuthCtlEdit Label10" for="" style="width: 22px">
                        ～
                    </label>
                </td>
                <td>
                    <input class="KRSS FrmAuthCtlEdit cboENDDATE Enter Tab" style="width: 104px" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class="KRSS FrmAuthCtlEdit Label3 label-snow" for="" style="width: 96px">
                        社員
                    </label>
                </td>
                <td>
                    <input class="KRSS FrmAuthCtlEdit lblSYAINNO" style="width: 56px" disabled="disabled" />
                </td>
                <td>
                    <input class="KRSS FrmAuthCtlEdit lblSYAINNM" style="width: 184px" disabled="disabled" />
                </td>
                <td width="10">
                </td>
                <td colspan="2">
                    <div style="margin-left: 120px">
                        <input class="KRSS FrmAuthCtlEdit cbxAll" type="checkbox" />
                        全て
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <table id="FrmAuthCtlEdit_sprCostList">
                    </table>
                </td>
                <td>
                    <table id="FrmAuthCtlEdit_sprMeisei">
                    </table>
                </td>
            </tr>
        </table>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="KRSS FrmAuthCtlEdit cmdUpdate Tab Enter">
                    登録
                </button>
                <button class="KRSS FrmAuthCtlEdit cmdBack Tab Enter">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>