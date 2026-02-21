<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmGyosekiSyoreiCalc/FrmGyosekiSyoreiCalc"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmGyosekiSyoreiCalc.set-margin {
        display: inline;
        margin-left: 100px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmGyosekiSyoreiCalc'>
    <div class='FrmGyosekiSyoreiCalc JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmGyosekiSyoreiCalc Label18 lbl-sky-L' for=""> 支給年月 </label>
            <input class='FrmGyosekiSyoreiCalc dtpYM Enter Tab' maxlength="6" />
            <div class="FrmGyosekiSyoreiCalc set-margin  HMS-button-pane">
                <button class="FrmGyosekiSyoreiCalc btnAction Enter Tab">
                    実行
                </button>
            </div>
        </div>
    </div>
</div>
