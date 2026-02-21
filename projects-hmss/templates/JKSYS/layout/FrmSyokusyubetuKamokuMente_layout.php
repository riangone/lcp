<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('JKSYS/FrmSyokusyubetuKamokuMente/FrmSyokusyubetuKamokuMente'));
?>
<style type="text/css">
    select {
        width: 200px
    }

    .FrmSyokusyubetuKamokuMente.lblKaNm {
        width: 180px;
    }

    .input-hidden {
        visibility: hidden;
    }

    .FrmSyokusyubetuKamokuMente.labelStyle {
        display: inline-block;
        vertical-align: top;
    }

    input.FrmSyokusyubetuKamokuMente[maxlength="5"] {
        text-align: right;
    }
</style>
<div class='FrmSyokusyubetuKamokuMente'>
    <div class='FrmSyokusyubetuKamokuMente JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <table id="FrmSyokusyubetuKamokuMente_sprList"></table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmSyokusyubetuKamokuMente btn Tab cmdSel" tabindex="1">
                    選択
                </button>
            </div>
        </div>
        <div>
            <label class='FrmSyokusyubetuKamokuMente labelStyle Label6 lbl-sky-L' for=""> 項目 </label>
            <select class="FrmSyokusyubetuKamokuMente cmbItem Enter Tab" tabindex="2"></select>
        </div>
        <div>
            <label class='FrmSyokusyubetuKamokuMente Label1 lbl-sky-L' for=""> 科目コード </label>
            <input type="text" class='FrmSyokusyubetuKamokuMente Enter Tab txtKaCd' maxlength="5" tabindex="3" />
            <input type="text" class='FrmSyokusyubetuKamokuMente Enter Tab txtHiCd' maxlength="5" tabindex="4" />
            <input type="text" class='FrmSyokusyubetuKamokuMente lblKaNm' />
            <input type="text" class='FrmSyokusyubetuKamokuMente lblCreD input-hidden' />
            <input type="text" class='FrmSyokusyubetuKamokuMente lblCreM input-hidden' />
            <input type="text" class='FrmSyokusyubetuKamokuMente lblCreA input-hidden' />
        </div>
        <div>
            <label class='FrmSyokusyubetuKamokuMente labelStyle Label5 lbl-sky-L' for=""> 職種コード </label>
            <select class="FrmSyokusyubetuKamokuMente cmbSyCd Tab Enter" tabindex="5"></select>
        </div>
        <div class="HMS-button-pane">
            <div class='FrmSyokusyubetuKamokuMente HMS-button-set'>
                <button class='FrmSyokusyubetuKamokuMente btn cmdCan Tab Enter' tabindex="6">
                    キャンセル
                </button>
                <button class='FrmSyokusyubetuKamokuMente btn cmdReg Tab Enter' tabindex="7">
                    登録
                </button>
                <button class='FrmSyokusyubetuKamokuMente btn cmdDel Tab Enter' tabindex="8">
                    削除
                </button>
            </div>
        </div>
    </div>
</div>
