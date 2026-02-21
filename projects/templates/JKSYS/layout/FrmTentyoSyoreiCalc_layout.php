<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmTentyoSyoreiCalc/FrmTentyoSyoreiCalc"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmTentyoSyoreiCalc.set-margin {
        display: inline;
        margin-left: 100px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmTentyoSyoreiCalc'>
    <div class='FrmTentyoSyoreiCalc JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmTentyoSyoreiCalc Label18 lbl-sky-L' for=""> 支給年月 </label>
            <input class='FrmTentyoSyoreiCalc dtpYM Enter Tab' maxlength="6" />
            <div class="FrmTentyoSyoreiCalc set-margin  HMS-button-pane">
                <button class='FrmTentyoSyoreiCalc btnAction Enter Tab'>
                    実行
                </button>
            </div>
        </div>
    </div>
</div>
