<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmSyasyuArariChkListKRSS/FrmSyasyuArariChkListKRSS"));
?>
<div class='KRSS FrmSyasyuArariChkListKRSS_KRSS'>
    <div class='KRSS FrmSyasyuArariChkListKRSS_KRSS R4-content'>
        <fieldset>
            <legend>
                帳票選択
            </legend>
            <div>
                <br />
                <input type='radio' name='KRSS_radio_frmSyasyuArariChkList'
                    class='KRSS FrmSyasyuArariChkListKRSS_KRSS radChkList Tab Enter' />
                新車車種別粗利益チェックリスト
                <br />
                <br />
                <input type='radio' name='KRSS_radio_frmSyasyuArariChkList'
                    class='KRSS FrmSyasyuArariChkListKRSS_KRSS radMeisai Tab Enter' />
                新車車種別粗利益表
                <br />
                <br />
                <input type='radio' name='KRSS_radio_frmSyasyuArariChkList'
                    class='KRSS FrmSyasyuArariChkListKRSS_KRSS radBaseh Tab Enter' />
                新車ベースH別粗利益表　
                <br />
                <br />
                <input type='radio' name='KRSS_radio_frmSyasyuArariChkList'
                    class='KRSS FrmSyasyuArariChkListKRSS_KRSS radDouble Tab Enter' />
                すべて
                <br />
                <br />
                <br />
            </div>
        </fieldset>
        <br />
        <fieldset>
            <legend>
                出力対象
            </legend>
            <div>
                <table>
                    <tr>
                        <td>
                            <div class='label-snow' style='width:76px'>
                                処理年月
                            </div>
                        </td>
                        <td>
                            <div class="KRSS FrmSyasyuArariChkListKRSS_KRSS DIVcboYMStart" style="float: left">
                                <input type='text' class='KRSS FrmSyasyuArariChkListKRSS_KRSS cboYMStart Tab Enter'
                                    style="width: 89px" maxlength="6" />
                            </div>
                            ～
                            <input type='text' class='KRSS FrmSyasyuArariChkListKRSS_KRSS cboYMEnd Tab Enter'
                                style="width: 89px" maxlength="6" />
                        </td>
                    </tr>
                </table>

            </div>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>

                <button class='KRSS FrmSyasyuArariChkListKRSS_KRSS cmdExportExcel Tab Enter'>
                    Excel出力
                </button>
            </div>
        </div>
    </div>
</div>