<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmHRAKUOutput/FrmHRAKUOutput"));
?>
<style type="text/css">
    .FrmHRAKUOutput .HMS-button-set {
        margin: auto 2px;
        float: none
    }

    .FrmHRAKUOutput .HMS-button-pane {
        text-align: center;
    }

    .FrmHRAKUOutput .btnSelect {
        margin-right: 200px;
    }

    .ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }
</style>
<div class='FrmHRAKUOutput body'>
    <div class='FrmHRAKUOutput content R4-content'>
        <div>
            <div class='FrmHRAKUOutput sprItyp'>
                <table id="R4_FrmHRAKUOutput_sprItyp"></table>
            </div>
        </div>

        <div class="HMS-button-pane">
            <div class="FrmHRAKUOutput HMS-button-set">
                <button class="FrmHRAKUOutput btnSelect Enter Tab">
                    選択
                </button>
                <button class="FrmHRAKUOutput btnClose Enter Tab">
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>