<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmExcelTorikomiKyufu/FrmExcelTorikomiKyufu"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmExcelTorikomiKyufu.txtPath {
        width: 500px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmExcelTorikomiKyufu'>
    <div class='FrmExcelTorikomiKyufu JKSYS-content'>
        <div>
            <label class='FrmExcelTorikomiKyufu Label18 lbl-sky-L'>対象年月</label>
            <input type="text" class='FrmExcelTorikomiKyufu dtpYM Enter Tab' maxlength="6" />
        </div>
        <div>
            <label class='FrmExcelTorikomiKyufu Label18 lbl-sky-L'>取込ファイル</label>

            <input class="FrmExcelTorikomiKyufu txtPath Enter Tab" disabled="true" />
            <button class="FrmExcelTorikomiKyufu btnDialog Enter Tab">
                参照
            </button>
        </div>
        <div class="FrmExcelTorikomiKyufu HMS-button-pane">
            <div class='FrmExcelTorikomiKyufu HMS-button-set'>
                <button class='FrmExcelTorikomiKyufu btnAction Enter Tab'>
                    取込
                </button>
                <button class='FrmExcelTorikomiKyufu btnClose Enter Tab'>
                    戻る
                </button>
            </div>
        </div>
        <div id="tmpFileUpload"></div>
    </div>
</div>