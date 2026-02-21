<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmKyotenFurikae/FrmKyotenFurikae"));
?>

<style type="text/css">
    .FrmKyotenFurikae.cboKeiriBiRight {
        float: left;
        margin-right: 50px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmKyotenFurikae">
    <div class="FrmKyotenFurikae JKSYS-content">
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="FrmKyotenFurikae cboKeiriBiRight">
                <label class="FrmKyotenFurikae lbl-sky-L" for=""> 年月 </label>
                <input type="text" maxlength="6" class="FrmKyotenFurikae cboKeiriBi Enter Tab" tabindex="0" />
            </div>
            <div>
                <label class="FrmKyotenFurikae lbl-sky-L" for=""> 注文書番号 </label>
                <input type="text" maxlength="12" class="FrmKyotenFurikae txtCmnNO Enter Tab" tabindex="1" />
            </div>
            <div class="HMS-button-pane">
                <label class="FrmKyotenFurikae lbl-sky-L" for=""> 社員番号 </label>
                <input type="text" maxlength="12" class="FrmKyotenFurikae txtSyainNO Enter Tab" tabindex="2" />
                <button class="FrmKyotenFurikae cmdSearch Enter Tab" tabindex="6">
                    検索
                </button>
            </div>
        </fieldset>
        <div>
            <table class="FrmKyotenFurikae sprList Enter Tab" id="JKSYS_FrmKyotenFurikae_sprList" tabindex="3"></table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmKyotenFurikae cmdInsert Enter Tab" tabindex="4">
                    新規登録
                </button>
                <button class="FrmKyotenFurikae cmdUpdate Enter Tab" tabindex="5">
                    修正
                </button>
                <button class="FrmKyotenFurikae cmdDelete Enter Tab" tabindex="6">
                    削除
                </button>
            </div>
        </div>
        <div class="FrmKyotenFurikae dialogsFrmKyotenFurikaeEdit"></div>
    </div>
</div>
