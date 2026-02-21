<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmDLStateCheck/FrmDLStateCheck"));
?>
<style type="text/css">
    .FrmDLStateCheck.lblLogPath {
        width: 300px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmDLStateCheck">
    <div class="FrmDLStateCheck JKSYS-content">
        <div>
            <table id="JKSYS_FrmDLStateCheck_sprList"></table>
        </div>
        <div>
            <label class="FrmDLStateCheck Label1 lbl-sky-L" for=""> ログ出力先 </label>
            <input type="text" class="FrmDLStateCheck lblLogPath" readonly="readonly" />
        </div>
        <div class="HMS-button-pane">
            <button class="FrmDLStateCheck cmdDisp Enter Tab">
                再表示(F5)
            </button>
            <button class="FrmDLStateCheck HMS-button-set cmdUpdate Enter Tab">
                更新
            </button>
        </div>
    </div>
</div>
