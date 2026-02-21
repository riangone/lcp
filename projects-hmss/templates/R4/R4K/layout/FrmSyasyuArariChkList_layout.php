<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyasyuArariChkList/FrmSyasyuArariChkList"));
?>
<div class='FrmSyasyuArariChkList'>
    <div class='FrmSyasyuArariChkList  R4-content'>
        <fieldset>
            <legend>
                帳票選択
            </legend>
            <div>
                <br />
                <input type='radio' name='radio_frmSyasyuArariChkList'
                    class='FrmSyasyuArariChkList radChkList Tab Enter' />
                新車車種別粗利益チェックリスト
                <br />
                <br />
                <input type='radio' name='radio_frmSyasyuArariChkList'
                    class='FrmSyasyuArariChkList radMeisai Tab Enter' />
                新車車種別粗利益表
                <br />
                <br />
                <input type='radio' name='radio_frmSyasyuArariChkList'
                    class='FrmSyasyuArariChkList radDouble Tab Enter' />
                両方
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
            <table>
                <tr>
                    <td class="FrmSyasyuUriageList Label1">
                        処理年月
                    </td>
                    <td width="30">
                    </td>
                    <td>
                        <div class="FrmSyasyuArariChkList cboYMStartdiv" style="float: left">
                            <!-- 20150922 yin upd S	 -->
                            <!-- <input  class="FrmSyasyuUriageList cboYMFrom Tab Enter" style="width: 80px" maxlength="7"/> -->
                            <input class="FrmSyasyuArariChkList cboYMStart Tab Enter" style="width: 80px"
                                maxlength="6" />
                            <!-- 20150922 yin upd S	 -->
                        </div>
                    </td>
                    <td width="15">
                    </td>
                    <td>
                        <label class="FrmSyasyuArariChkList Label3" for="">
                            ～
                        </label>
                    </td>
                    <td width="15">
                    </td>
                    <td>
                        <!-- 20150922 yin upd S -->
                        <!-- <input  class="FrmSyasyuUriageList cboYMTo Tab Enter" style="width: 80px" maxlength="7"/> -->
                        <input class="FrmSyasyuArariChkList cboYMEnd Tab Enter" style="width: 80px" maxlength="6" />
                        <!-- 20150922 yin upd S -->
                    </td>

                </tr>
            </table>

        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSyasyuArariChkList button_action Tab Enter'>
                    実 行
                </button>
            </div>
        </div>
    </div>
</div>
