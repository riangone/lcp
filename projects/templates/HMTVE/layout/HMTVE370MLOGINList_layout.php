<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE370MLOGINList/HMTVE370MLOGINList"));
?>
<style type="text/css">
    /*折行*/
    .HMTVE370MLOGINList.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE370MLOGINList input[maxlength='40'] {
        width: 255px;
    }

    .HMTVE370MLOGINList fieldset>div {
        padding: 1px 1px 5px 1px;
    }
</style>
<div class="HMTVE370MLOGINList">
    <div class="HMTVE370MLOGINList HMTVE-content HMTVE-content-fixed-width">
        <fieldset class="HMTVE370MLOGINList">
            <!-- 検索条件 -->
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE370MLOGINList lblUseID lbl-sky-L' for="">ユーザID</label>
                <input type="text" class="HMTVE370MLOGINList txtUserID Enter Tab" maxlength="5" tabindex="1" />
            </div>
            <div>
                <label class='HMTVE370MLOGINList lblSyaYin lbl-sky-L' for="">社員名</label>
                <input type="text" class="HMTVE370MLOGINList txtSyaYin Enter Tab" maxlength="40" tabindex="2" />
                <button class="HMTVE370MLOGINList btnSearch Enter Tab" tabindex="3">
                    検索
                </button>
            </div>
        </fieldset>
        <div class="HMTVE370MLOGINList pnlList">
            <table id="HMTVE370MLOGINList_tblMain"></table>
            <div id="HMTVE370MLOGINList_pager"></div>
        </div>
    </div>
</div>