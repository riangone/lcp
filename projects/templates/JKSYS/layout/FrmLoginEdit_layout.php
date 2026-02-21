<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('JKSYS/FrmLoginEdit/FrmLoginEdit')); ?>
<style type="text/css">
    input.FrmLoginEdit.Label8 {
        width: 60px;
    }

    .FrmLoginEdit.UcComboBox2 {
        width: 150px;
    }

    input.FrmLoginEdit.Label9 {
        display: none;
    }

    .FrmLoginEdit.labelStyle {
        display: inline-block;
        vertical-align: top;
    }
</style>
<div class='FrmLoginEdit body'>
    <div class='FrmLoginEdit JKSYS-content'>
        <div>
            <label class='FrmLoginEdit lbl-sky-L' for=""> システム区分 </label>
            <select class="FrmLoginEdit cboSysKB Enter Tab"></select>
        </div>
        <div>
            <label class='FrmLoginEdit lbl-sky-L' for=""> ユーザＩＤ </label>
            <input class="FrmLoginEdit Label8" disabled="disabled" />
            <input class="FrmLoginEdit Label7" disabled="disabled" />
        </div>

        <div>
            <label class='FrmLoginEdit lbl-sky-L' for=""> パスワード </label>
            <input class="FrmLoginEdit UcTextBox1 Enter Tab" type="password" tabindex="0" />
        </div>

        <div>
            <label class='FrmLoginEdit lbl-sky-L' for=""> パスワード確認 </label>
            <input class="FrmLoginEdit UcTextBox2 Enter Tab" type="password" tabindex="1" />
        </div>

        <div>
            <label class='FrmLoginEdit labelStyle lbl-sky-L' for=""> パターンＩＤ </label>
            <select class="FrmLoginEdit UcComboBox2 Enter Tab" tabindex="2"></select>
            <input class="FrmLoginEdit Label9" />
        </div>
        <div class="FrmLoginEdit HMS-button-pane">
            <div class='FrmLoginEdit HMS-button-set'>
                <button class='FrmLoginEdit Button3 Tab Enter' tabindex="3">
                    登録
                </button>
                <button class='FrmLoginEdit Button2 Tab Enter' tabindex="4">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
