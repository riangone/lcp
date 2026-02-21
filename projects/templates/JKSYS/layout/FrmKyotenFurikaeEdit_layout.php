<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmKyotenFurikaeEdit/FrmKyotenFurikaeEdit"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmKyotenFurikaeEdit.txtFurikaeKin,
    .FrmKyotenFurikaeEdit.lblInputTotal {
        text-align: right
    }

    .FrmKyotenFurikaeEdit.txtEdaNO {
        display: none
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmKyotenFurikaeEdit body">
    <div class='FrmKyotenFurikaeEdit JKSYS-content'>
        <div>
            <label class='FrmKyotenFurikaeEdit lbl-sky-L' for=""> 経理日 </label>
            <input type="text" class="FrmKyotenFurikaeEdit cboKeiriBi Enter Tab" maxlength="10" tabindex="1"
                value="200604" 　 />
        </div>
        <div>
            <fieldset>
                <legend>
                    <b><span>振替元</span></b>
                </legend>
                <div>
                    <label class='FrmKyotenFurikaeEdit lbl-sky-L' for=""> 注文書番号 </label>
                    <input type="text" class="FrmKyotenFurikaeEdit txtCMNNO Enter Tab" maxlength="10" tabindex="2" />
                    <label class='FrmKyotenFurikaeEdit lbl-sky-L' for=""> UCNO </label>
                    <input type="text" class="FrmKyotenFurikaeEdit lblUCNO Enter Tab" disabled="disabled" tabindex="3"
                        value="社員番号" />
                </div>
                <div>
                    <label class='FrmKyotenFurikaeEdit lbl-sky-L' for=""> 社員番号 </label>
                    <input type="text" class="FrmKyotenFurikaeEdit txtSyainCD Enter Tab" maxlength="5" tabindex="4"
                        value="12345" />
                    <input type="text" class="FrmKyotenFurikaeEdit lblSyainNM " disabled="disabled" tabindex="6"
                        value="社員番号" />
                </div>
                <div>
                    <label class='FrmKyotenFurikaeEdit lbl-sky-L' for=""> 表示文字入力
                    </label>
                    <input type="text" class="FrmKyotenFurikaeEdit txtDispMoji Enter Tab" maxlength="12" tabindex="8" />
                </div>
                <div>
                    <label class='FrmKyotenFurikaeEdit lbl-sky-L' for=""> 金額 </label>
                    <input type="text" class="FrmKyotenFurikaeEdit txtFurikaeKin Enter Tab" maxlength="256"
                        tabindex="12" />
                </div>
            </fieldset>
        </div>
        <div>
            <label class='FrmKyotenFurikaeEdit lbl-sky-L' style="width: 101px;" for=""> 振替先金額合計</label>
            <input type="text" class="FrmKyotenFurikaeEdit lblInputTotal Enter Tab" disabled="disabled"
                maxlength="256" />
            <input type="text" class="FrmKyotenFurikaeEdit txtEdaNO" maxlength="3" tabindex="5" />
        </div>

        <div>
            <table class="FrmKyotenFurikaeEdit sprList" id="FrmKyotenFurikaeEdit_sprList"></table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmKyotenFurikaeEdit cmdAction Enter Tab" tabindex="7">
                    登録（F9）
                </button>

                <button class="FrmKyotenFurikaeEdit cmdBack Enter Tab" tabindex="8">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
