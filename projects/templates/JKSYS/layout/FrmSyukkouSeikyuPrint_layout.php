<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyukkouSeikyuPrint/FrmSyukkouSeikyuPrint"));

echo $this->fetch("meta");
echo $this->fetch("css");
echo $this->fetch("script");
?>
<style type="text/css">
    .FrmSyukkouSeikyuPrint.cmbSyukko {
        width: 185px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmSyukkouSeikyuPrint.cmbSyukko {
            width: 141px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmSyukkouSeikyuPrint'>
    <div class="FrmSyukkouSeikyuPrint JKSYS-content JKSYS-content-fixed-width">
        <!-- 対象年月 -->
        <div>
            <label class="FrmSyukkouSeikyuPrint Label18 lbl-sky-L" for=""> 対象年月 </label>
            <input class='FrmSyukkouSeikyuPrint DateTimePicker1 Enter Tab' maxlength="6" />
        </div>
        <!-- 出向先 -->
        <div>
            <label class="FrmSyukkouSeikyuPrint Label1 lbl-sky-L" for=""> 出向先
            </label>
            <select class="FrmSyukkouSeikyuPrint cmbSyukko Enter Tab"></select>
        </div>
        <!-- 印刷 -->
        <div class="FrmSyukkouSeikyuPrint HMS-button-pane">
            <button class="FrmSyukkouSeikyuPrint cmdPri HMS-button-set Enter Tab">
                印刷
            </button>
        </div>
    </div>
</div>