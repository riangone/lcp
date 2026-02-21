<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('JKSYS/FrmJinkenhiBusyoHenkanMente/FrmJinkenhiBusyoHenkanMente')); ?>
<style type="text/css">
    .FrmJinkenhiBusyoHenkanMente.lbl-sky-XL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-top: 5px;
        margin-left: 2px;
        width: 120px;
    }

    input.FrmJinkenhiBusyoHenkanMente.inputStyle {
        width: 70px;
    }

    .FrmJinkenhiBusyoHenkanMente.footer {
        width: 710px
    }

    .FrmJinkenhiBusyoHenkanMente.readOnly {
        background-color: #BABEC1;
    }
</style>
<div class="FrmJinkenhiBusyoHenkanMente">
    <div class='FrmJinkenhiBusyoHenkanMente JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <table id="JKSYS_FrmJinkenhiBusyoHenkanMente_sprList"></table>
        </div>
        <div class="FrmJinkenhiBusyoHenkanMente footer">
            <div class="FrmJinkenhiBusyoHenkanMente HMS-button-pane">
                <div class='FrmJinkenhiBusyoHenkanMente HMS-button-set'>
                    <button class="FrmJinkenhiBusyoHenkanMente btn Tab btnSelect" tabindex="0">
                        選択
                    </button>
                </div>
            </div>
        </div>
        <div class="FrmJinkenhiBusyoHenkanMente footer">
            <div class="FrmJinkenhiBusyoHenkanMente">
                <label class='FrmJinkenhiBusyoHenkanMente lbl-sky-XL' for="">
                    変換前部署コード </label>
                <input class='FrmJinkenhiBusyoHenkanMente Enter Tab txtBefore inputStyle' maxlength="3" tabindex="1" />
                <input class='FrmJinkenhiBusyoHenkanMente Tab lblBefore readOnly' disabled="disabled" />
            </div>
            <div class="FrmJinkenhiBusyoHenkanMente">
                <label class='FrmJinkenhiBusyoHenkanMente lbl-sky-XL' for="">
                    変換後部署コード </label>
                <input class='FrmJinkenhiBusyoHenkanMente Enter Tab txtAfter inputStyle' maxlength="3" tabindex="2" />
                <input class='FrmJinkenhiBusyoHenkanMente Tab lblAfter readOnly' disabled="disabled" />
            </div>
            <div class="FrmJinkenhiBusyoHenkanMente HMS-button-pane">
                <div class="FrmJinkenhiBusyoHenkanMente HMS-button-set">
                    <button class='FrmJinkenhiBusyoHenkanMente btn cmdCan Tab Enter' tabindex="3">
                        キャンセル
                    </button>
                    <button class='FrmJinkenhiBusyoHenkanMente btn cmdReg Tab Enter' tabindex="4">
                        登録
                    </button>
                    <button class='FrmJinkenhiBusyoHenkanMente btn cmdDel Tab Enter' tabindex="5">
                        削除
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
