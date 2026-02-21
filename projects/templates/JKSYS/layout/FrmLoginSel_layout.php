<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('JKSYS/FrmLoginSel/FrmLoginSel')); ?>
<style type="text/css">
    .FrmLoginSel.cboSysKB {
        width: 200px;
    }
</style>
<div class="FrmLoginSel">
    <div class="FrmLoginSel JKSYS-content JKSYS-content-fixed-width">
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='FrmLoginSel lbl-sky-L' for=""> システム区分 </label>
                <select class="FrmLoginSel cboSysKB Enter Tab" tabindex="0"></select>
            </div>
            <div class="HMS-button-pane">
                <label class='FrmLoginSel lbl-sky-L' for=""> ユーザＩＤ </label>
                <input class="FrmLoginSel UcUserID Enter Tab" type="text" tabindex="1" />
                <button class='FrmLoginSel Button1 Tab Enter' tabindex="2">
                    検索
                </button>
            </div>
        </fieldset>
        <div>
            <table id='FrmJKSYSLoginSel_sprList'></table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmLoginSel Button3 Tab Enter' tabindex="3">
                    入力
                </button>
            </div>
        </div>
        <div id="FrmLoginSel_dialog" class="FrmLoginSel dialogsFrmLoginEdit"></div>
    </div>
</div>
