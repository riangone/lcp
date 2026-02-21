<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyukkouSeikyuInfoEnt/FrmSyukkouSeikyuInfoEnt"));

echo $this->fetch("meta");
echo $this->fetch("css");
echo $this->fetch("script");
?>
<style type="text/css">
    #FrmSyukkouSeikyuInfoEnt_sprList {
        height: 1px;
    }

    .FrmSyukkouSeikyuInfoEnt.Label1 {
        margin-left: 50px;
    }

    .FrmSyukkouSeikyuInfoEnt.comSyukkou {
        width: 120px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmSyukkouSeikyuInfoEnt'>
    <div class="FrmSyukkouSeikyuInfoEnt JKSYS-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend class="FrmFurikaeHiritsuEnt lblSearchTitle">
                検索条件
            </legend>
            <div class="FrmSyukkouSeikyuInfoEnt HMS-button-pane">
                <label class="FrmSyukkouSeikyuInfoEnt Label18 lbl-sky-L" for=""> 対象年月
                </label>
                <input class="FrmSyukkouSeikyuInfoEnt dtpYM Enter Tab" maxlength="6" tabindex="53" />
                <label class="FrmSyukkouSeikyuInfoEnt Label1 lbl-sky-L" for=""> 出向先
                </label>
                <select class="FrmSyukkouSeikyuInfoEnt comSyukkou Enter Tab"></select>
                <button class="FrmSyukkouSeikyuInfoEnt cmdSearch Enter Tab">
                    検索
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div>
            <table class="FrmSyukkouSeikyuInfoEnt sprList Enter Tab" id="FrmSyukkouSeikyuInfoEnt_sprList"></table>
        </div>
        <!-- 条件変更·登録 -->
        <div class="FrmSyukkouSeikyuInfoEnt HMS-button-pane">
            <button class="FrmSyukkouSeikyuInfoEnt cmdChange Enter Tab">
                条件変更
            </button>
            <button class="FrmSyukkouSeikyuInfoEnt cmdEntry HMS-button-set Enter Tab">
                登録
            </button>
        </div>
    </div>
</div>
