<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDSKDScheduleLimit/HMAUDSKDScheduleLimit'));
?>
<style type="text/css">
    .HMAUDSKDScheduleLimit .buttonClass {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .HMAUDSKDScheduleLimit .btnLogin {
        width: 80px;
    }

    .HMAUDSKDScheduleLimit .cmdDisp {
        width: 120px;
        margin-left: 329px;
    }

    .HMAUDSKDScheduleLimit .numeric {
        width: 90% !important;
    }

    .HMAUDSKDScheduleLimit.coursSearchInput,
    .HMAUDSKDScheduleLimit.ymSearchInput {
        width: 108px;
    }

    .HMAUDSKDScheduleLimit.ym-row {
        padding: 3px 0;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDSKDScheduleLimit">
    <div class="HMAUDSKDScheduleLimit HMAUD-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMAUDSKDScheduleLimit LBL_TITLE_STD9 lbl-sky-L'> クール </label>
                <select class="HMAUDSKDScheduleLimit coursSearchInput Enter Tab" tabindex="1" />
                <label for="" class="HMAUDSKDScheduleLimit courPeriod"></label>
            </div>
            <div class="HMAUDSKDScheduleLimit ym-row">
                <label for="" class='HMAUDSKDScheduleLimit LBL_TITLE_STD9 lbl-sky-L'> 年月 </label>
                <select class="HMAUDSKDScheduleLimit ymSearchInput Enter Tab" tabindex="2" />
            </div>
        </fieldset>
        <div class="HMAUDSKDScheduleLimit buttonClass">
            <button class="HMAUDSKDScheduleLimit btnLogin button Enter Tab" tabindex="3">
                更新
            </button>
            <button class="HMAUDSKDScheduleLimit cmdDisp button Enter Tab" tabindex="4">
                最新情報を表示
            </button>
        </div>
        <!-- jqgrid -->
        <div class="HMAUDSKDScheduleLimit pnlList">
            <table id="HMAUDSKDScheduleLimit_tblMain"></table>
        </div>
    </div>
</div>