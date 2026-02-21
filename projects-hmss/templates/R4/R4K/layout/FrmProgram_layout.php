<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmProgram/FrmProgram"));
?>

<!--包含子画面时，须定义id=‘FrmProgram’-->
<div class='FrmProgram R4-content'>
    <div class='FrmProgram  R4-content'>
        <!-- jqGrid 定义-->
        <table id='FrmProgram_sprMeisai'></table>
        <!-- jqGrid pager定义 按照各自需求-->
        <div id='FrmProgram_pager'></div>
        <!-- 功能button 定义 -->
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmProgram cmdInsert Enter Tab'>
                    追加
                </button>
                <button class='FrmProgram cmdUpdate Enter Tab'>
                    登録
                </button>
            </div>
        </div>
    </div>
</div>