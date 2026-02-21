<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyukkouSeikyuMeisaiCreate/FrmSyukkouSeikyuMeisaiCreate"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmSyukkouSeikyuMeisaiCreate.lblState {
        float: right;
        text-align: center;
        margin-right: 387px;
    }

    .FrmSyukkouSeikyuMeisaiCreate.lblAll {
        text-align: center;
        margin-bottom: 5px;
        width: 58px;
    }

    .FrmSyukkouSeikyuMeisaiCreate.chkAllDelete {
        margin-left: 18px;
    }

    .FrmSyukkouSeikyuMeisaiCreate fieldset {
        min-height: 0px;
    }

    .FrmSyukkouSeikyuMeisaiCreate fieldset .HMS-button-pane {
        margin-top: 0px;
    }

    .FrmSyukkouSeikyuMeisaiCreate.JKSYS-content>div {
        margin: 3px 0 0 5px;
    }

    .FrmSyukkouSeikyuMeisaiCreate .HMS-button-pane {
        min-height: 25px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmSyukkouSeikyuMeisaiCreate.lblState {
            margin-right: 345px;
        }

        .FrmSyukkouSeikyuMeisaiCreate.lblAll {
            width: 43px;
        }

        .FrmSyukkouSeikyuMeisaiCreate.chkAllDelete {
            margin-left: 15px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmSyukkouSeikyuMeisaiCreate">
    <div class="FrmSyukkouSeikyuMeisaiCreate JKSYS-content JKSYS-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMS-button-pane">
                <label class='FrmSyukkouSeikyuMeisaiCreate Label18 lbl-sky-L' for="">
                    対象年月 </label>
                <input type="text" class="FrmSyukkouSeikyuMeisaiCreate dtpYM Enter Tab" maxlength="6" tabindex="1" />
                <button class="FrmSyukkouSeikyuMeisaiCreate btnSearch Enter Tab" tabindex="2">
                    検索
                </button>
            </div>
        </fieldset>
        <!-- 全て -->
        <div>
            <label class="FrmSyukkouSeikyuMeisaiCreate lblAll" for="">
                全て</label>
            <input type="checkbox" class="FrmSyukkouSeikyuMeisaiCreate chkAllUpdate" tabindex="3" />
            <input type="checkbox" class="FrmSyukkouSeikyuMeisaiCreate chkAllDelete" tabindex="4" />
            <label class="FrmSyukkouSeikyuMeisaiCreate lblState lbl-yellow-S" for="">再</label>
        </div>
        <!-- jqgrid -->
        <div>
            <table id="FrmSyukkouSeikyuMeisaiCreate_sprList"></table>
        </div>
        <!-- 行追加·行削除 -->
        <div class="HMS-button-pane">
            <button class="FrmSyukkouSeikyuMeisaiCreate btnAddRow Enter Tab" tabindex="6">
                行追加
            </button>
            <button class="FrmSyukkouSeikyuMeisaiCreate btnDelRow Enter Tab" tabindex="578">
                行削除
            </button>
        </div>
        <!-- 条件変更·実行 -->
        <div class="HMS-button-pane">
            <button class="FrmSyukkouSeikyuMeisaiCreate btnModify Enter Tab" tabindex="7">
                条件変更
            </button>
            <button class="FrmSyukkouSeikyuMeisaiCreate btnAction HMS-button-set Enter Tab" tabindex="8">
                実行
            </button>
        </div>
    </div>
</div>