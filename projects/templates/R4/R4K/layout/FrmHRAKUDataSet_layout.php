<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmHRAKUDataSet/FrmHRAKUDataSet"));
?>
<style type="text/css">
    .FrmHRAKUDataSet.HMS-button-pane {
        display: flex;
        justify-content: center;
    }

    .FrmHRAKUDataSet.btnCancel {
        margin-left: 300px;
    }

    /*折行*/
    #FrmHRAKUDataSet_table tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }
</style>
<div class='FrmHRAKUDataSet body'>
    <div class='FrmHRAKUDataSet content R4-content'>
        <table id='FrmHRAKUDataSet_table'>
        </table>
        <div class="FrmHRAKUDataSet HMS-button-pane">
            <button class='FrmHRAKUDataSet btn btnChoose Enter Tab'>
                選択
            </button>
            <button class='FrmHRAKUDataSet btn btnCancel Enter Tab'>
                キャンセル
            </button>
        </div>
    </div>
</div>