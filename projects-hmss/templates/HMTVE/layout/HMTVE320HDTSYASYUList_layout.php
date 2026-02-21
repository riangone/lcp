<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE320HDTSYASYUList/HMTVE320HDTSYASYUList"));
?>
<style type="text/css">
    /*折行*/
    .HMTVE320HDTSYASYUList.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE320HDTSYASYUList fieldset>div {
        padding: 1px 1px 5px 1px;
    }
</style>
<div class="HMTVE320HDTSYASYUList">
    <div class="HMTVE320HDTSYASYUList HMTVE-content HMTVE-content-fixed-width">
        <fieldset class="HMTVE320HDTSYASYUList">
            <!-- 検索条件 -->
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE320HDTSYASYUList lblNumber LBL_TITLE_STD9 lbl-sky-L' for="">車種コード</label>
                <input type="text" class="HMTVE320HDTSYASYUList txtNumber Enter Tab" maxlength="3" tabindex="1" />
            </div>
            <div>
                <label class='HMTVE320HDTSYASYUList lblName LBL_TITLE_STD9 lbl-sky-L' for="">車種名</label>
                <input type="text" class="HMTVE320HDTSYASYUList txtName Enter Tab" maxlength="40" tabindex="2" />
                <div class="HMTVE320HDTSYASYUList HMS-button-set">
                    <button class="HMTVE320HDTSYASYUList BTN_STD100 btnAdd Enter Tab" tabindex="4">
                        追加
                    </button>
                    <button class="HMTVE320HDTSYASYUList BTN_STD100 btnSearch Enter Tab" tabindex="3">
                        検索
                    </button>
                </div>
            </div>
        </fieldset>
        <div class="HMTVE320HDTSYASYUList pnlList">
            <table id="HMTVE320HDTSYASYUListMain"></table>
            <div id="HMTVE320HDTSYASYUList_pager"></div>
        </div>
    </div>
</div>