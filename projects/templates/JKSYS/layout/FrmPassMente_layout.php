<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('JKSYS/FrmPassMente/FrmPassMente'));
?>
<style type="text/css">
    .FrmPassMente.lbl-sky-XL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-top: 5px;
        margin-left: 2px;
        width: 150px;
    }

    .FrmPassMente.selectWidth {
        width: 200px;
    }

    input[maxlength='30'] {
        width: 150px;
    }
</style>
<div class="FrmPassMente">
    <div class='FrmPassMente JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmPassMente Label2 lbl-sky-XL' for=""> プログラム名 </label>
            <select class='FrmPassMente Enter Tab cmbPGNM selectWidth' tabindex="1"></select>
        </div>
        <div>
            <label class='FrmPassMente Label1 lbl-sky-XL' for=""> パスワード </label>
            <input maxlength="30" class='FrmPassMente Enter Tab txtPass1' tabindex="2" />
        </div>
        <div>
            <label class='FrmPassMente Label4 lbl-sky-XL' for=""> パスワード（再入力） </label>
            <input maxlength="30" class='FrmPassMente Enter Tab txtPass2' tabindex="3" />
        </div>
        <div class="HMS-button-pane">
            <div class="FrmPassMente HMS-button-set">
                <button class='FrmPassMente btn cmdCan Tab Enter' tabindex="4">
                    キャンセル
                </button>
                <button class='FrmPassMente btn cmdReg Tab Enter' tabindex="5">
                    登録
                </button>
                <button class='FrmPassMente btn cmdDel Tab Enter' tabindex="6">
                    削除
                </button>
            </div>
        </div>
    </div>
</div>
