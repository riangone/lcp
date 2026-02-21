<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE390HDTCOMPANYSEARCH/HMTVE390HDTCOMPANYSEARCH"));
?>
<style type="text/css">
    .HMTVE390HDTCOMPANYSEARCH.btnSel,
    .HMTVE390HDTCOMPANYSEARCH.btnClose {
        float: right;
    }

    /*折行*/
    .HMTVE390HDTCOMPANYSEARCH.tblDetail .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE390HDTCOMPANYSEARCH body">
    <div class="HMTVE390HDTCOMPANYSEARCH HMTVE-content">
        <fieldset class="HMTVE390HDTCOMPANYSEARCH fieldset">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE390HDTCOMPANYSEARCH HMS-button-pane">
                <label class='HMTVE390HDTCOMPANYSEARCH lbl-sky-L' for=""> 会社コード </label>
                <input type="text" class="HMTVE390HDTCOMPANYSEARCH txtComCode Enter Tab" maxlength="5" tabindex="1" />
                <label class='HMTVE390HDTCOMPANYSEARCH lbl-sky-L' for=""> 会社名 </label>
                <input type="text" class="HMTVE390HDTCOMPANYSEARCH txtComName Enter Tab" maxlength="100" tabindex="2" />
                <button class="HMTVE390HDTCOMPANYSEARCH btnClose Enter Tab" tabindex="4">
                    閉じる
                </button>
                <button class="HMTVE390HDTCOMPANYSEARCH btnView Enter Tab" tabindex="3">
                    表示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE390HDTCOMPANYSEARCH tblDetail">
            <table id="HMTVE390HDTCOMPANYSEARCH_sprList"></table>
            <div id="HMTVE390HDTCOMPANYSEARCH_pager"></div>
        </div>
        <div class="HMTVE390HDTCOMPANYSEARCH HMS-button-pane btnSelDiv">
            <button class="HMTVE390HDTCOMPANYSEARCH btnSel Enter Tab" tabindex="5">
                選択
            </button>
        </div>
    </div>
</div>