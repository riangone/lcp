<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyokusyuSyukeiMente/FrmSyokusyuSyukeiMente"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<style type="text/css">
    .FrmSyokusyuSyukeiMente .HMS-button-pane {
        margin-top: 0px;
        margin-bottom: 0px;
    }

    .FrmSyokusyuSyukeiMente .margin0 {
        margin-top: 0px;
        margin-bottom: 2px;
    }

    .FrmSyokusyuSyukeiMente input[maxlength="30"] {
        width: 210px;
    }
</style>

<!-- 画面個別の内容を表示 -->
<div class="FrmSyokusyuSyukeiMente">
    <div class="FrmSyokusyuSyukeiMente JKSYS-content JKSYS-content-fixed-width">
        <!-- jqgrid -->
        <div class="margin0">
            <table id="FrmSyokusyuSyukeiMente_spr_List1"></table>
        </div>
        <!-- 選択 -->
        <div class="HMS-button-pane">
            <button class="HMS-button-set FrmSyokusyuSyukeiMente btnSelect Enter Tab" tabindex="2">
                選択
            </button>
        </div>
        <!-- 職種集計区分 -->
        <div class="FrmSyokusyuSyukeiMente margin0">
            <label class="FrmSyokusyuSyukeiMente Label6 lbl-sky-L" for="">
                職種集計区分</label>
            <input type="text" class="FrmSyokusyuSyukeiMente txtKbn Enter Tab" maxlength="3" tabindex="3" />
        </div>
        <!-- 職種区分名 -->
        <div class="FrmSyokusyuSyukeiMente margin0">
            <label class="FrmSyokusyuSyukeiMente Label1 lbl-sky-L" for="">
                職種区分名</label>
            <input type="text" class="FrmSyokusyuSyukeiMente txtKbnNM Enter Tab" maxlength="30" tabindex="4" />
        </div>
        <!-- 出力順 -->
        <div class="FrmSyokusyuSyukeiMente margin0">
            <label class="FrmSyokusyuSyukeiMente Label2 lbl-sky-L" for="">
                出力順</label>
            <input type="text" class="FrmSyokusyuSyukeiMente txtOrder Enter Tab" maxlength="2" tabindex="5" />
        </div>
        <!-- jqgrid -->
        <div>
            <table id="FrmSyokusyuSyukeiMente_spr_List2"></table>
        </div>
        <!-- キャンセル·削除·登録 -->
        <div class="HMS-button-pane">
            <button class="FrmSyokusyuSyukeiMente cmdCan Enter Tab" tabindex="7">
                キャンセル
            </button>
            <button class="FrmSyokusyuSyukeiMente cmdReg Enter Tab" tabindex="8">
                登録
            </button>
            <button class="FrmSyokusyuSyukeiMente cmdDel Enter Tab" tabindex="9">
                削除
            </button>
        </div>
    </div>
</div>
