<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmSalesJskList/FrmSalesJskList"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmSalesJskList" class='FrmSalesJskList KRSS'>
    <div class="FrmSalesJskList searchArea KRSS">
        <fieldset>
            <legend>
                出力対象
            </legend>
            <label for="" class='KRSS FrmSalesJskList Label1' style=" width:82px;">
                処理年月
            </label>
            <input class='KRSS FrmSalesJskList cboYM Enter Tab' style="width: 80px" maxlength="6">
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="KRSS FrmSalesJskList cmdAction Enter Tab">
                    印刷
                </button>
            </div>
        </div>
    </div>
</div>
