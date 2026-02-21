<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmHknSytKaverRt/FrmHknSytKaverRt"));
?>

<div class='KRSS FrmHknSytKaverRt' id="KRSS_FrmHknSytKaverRt" style="width: 100%;height: 100%">
    <div class='KRSS FrmHknSytKaverRt R4-content'>
        <fieldset>
            <legend>
                出力条件
            </legend>
            <table>
                <tr>
                    <td><label class='KRSS FrmHknSytKaverRt Label1' for="" style="width:82px"> 処理年月 </label></td>
                    <td>
                        <input class='KRSS FrmHknSytKaverRt cboYM Enter Tab' style="width: 80px" maxlength="6">
                        <label class='KRSS FrmHknSytKaverRt Label4' for="" style="width: 100px;text-align:center"> ～
                        </label>
                        <input class='KRSS FrmHknSytKaverRt cboYMTo Enter Tab' style="width: 80px" maxlength="6">
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="KRSS FrmHknSytKaverRt cmdExcel Enter Tab">
                    EXCEL出力
                </button>

                <!-- <button class="KRSS FrmHknSytKaverRt cmdAction Enter Tab">
                印刷
                </button> -->
            </div>
        </div>
    </div>
</div>