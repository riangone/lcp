<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmHyogoCheckList/FrmHyogoCheckList"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .FrmHyogoCheckList.cmbJissi {
        width: 100px;
    }
</style>
<div class='FrmHyogoCheckList'>
    <div class='FrmHyogoCheckList JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmHyogoCheckList lbl-sky-L' for=""> 評価実施年月 </label>
            <select class="FrmHyogoCheckList cmbJissi Tab Enter" maxlength="7" tabindex="0"></select>
        </div>
        <div>
            <label class='FrmHyogoCheckList lbl-sky-L' for=""> 評価対象期間 </label>

            <label class='FrmHyogoCheckList lblTaisyou' for="">2010/04/01 ～ 2010/09/30</label>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmHyogoCheckList btnExcel Enter Tab' tabindex="1">
                    EXCEL出力
                </button>
            </div>
        </div>
    </div>
</div>
