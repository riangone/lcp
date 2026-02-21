<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE340HBUSYOList/HMTVE340HBUSYOList"));
?>
<style type="text/css">
    /*折行*/
    .HMTVE340HBUSYOList.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE340HBUSYOList fieldset>div {
        padding: 1px 1px 5px 1px;
    }
</style>
<div class="HMTVE340HBUSYOList">
    <div class="HMTVE340HBUSYOList HMTVE-content HMTVE-content-fixed-width">
        <fieldset class="HMTVE340HBUSYOList">
            <!-- 検索条件 -->
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE340HBUSYOList lblCode LBL_TITLE_STD9 lbl-sky-L' for="">部署コード</label>
                <input type="text" class="HMTVE340HBUSYOList txtID Enter Tab" maxlength="3" tabindex="1" />
            </div>
            <div>
                <label class='HMTVE340HBUSYOList lblName LBL_TITLE_STD9 lbl-sky-L' for="">部署名カナ</label>
                <input type="text" class="HMTVE340HBUSYOList txtName Enter Tab" maxlength="30" tabindex="2" />
                <div class="HMTVE340HBUSYOList HMS-button-set">
                    <button class="HMTVE340HBUSYOList BTN_STD100 btnSearch Enter Tab" tabindex="3">
                        検索
                    </button>
                </div>
            </div>
        </fieldset>
        <div class="HMTVE340HBUSYOList pnlList">
            <table id="HMTVE340HBUSYOListMain"></table>
            <div id="HMTVE340HBUSYOList_pager"></div>
        </div>
    </div>
</div>