<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmSimulationDataTotal/FrmSimulationDataTotal"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmSimulationDataTotal R4-content">
    <div style="width: 680px;margin-top: 10px">
        <div>
            <fieldset>
                <legend>
                    <b><span style="font-size: 10pt">集計条件</span></b>
                </legend>
                <table border="0">
                    <tr>
                        <td><label class="FrmSimulationDataTotal" for=""> 年月 </label></td>
                        <td>
                            <input type="text" class="KRSS FrmSimulationDataTotal cboYM Enter Tab" style="width: 80px;"
                                maxlength="7">
                        </td>

                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
    <div class="HMS-button-pane" align="right" style="margin-top: 10px;width: 680px">
        <button class="KRSS FrmSimulationDataTotal cmdAction Enter Tab">
            実行
        </button>

    </div>

</div>