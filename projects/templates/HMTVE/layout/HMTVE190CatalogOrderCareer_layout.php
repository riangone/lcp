<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE190CatalogOrderCareer/HMTVE190CatalogOrderCareer'));
?>
<style type="text/css">
    .HMTVE190CatalogOrderCareer.paddingSearchdiv {
        margin-top: 7px
    }

    .HMTVE190CatalogOrderCareer.ddlYearStart,
    .HMTVE190CatalogOrderCareer.ddlYearEnd {
        width: 62px;
    }

    .HMTVE190CatalogOrderCareer.ddlMonthStart,
    .HMTVE190CatalogOrderCareer.ddlDayStart,
    .HMTVE190CatalogOrderCareer.ddlMonthEnd,
    .HMTVE190CatalogOrderCareer.ddlDayEnd {
        width: 43px;
    }

    /*折行*/
    .HMTVE190CatalogOrderCareer.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE190CatalogOrderCareer">
    <div class="HMTVE190CatalogOrderCareer HMTVE-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <div>
                    <label class='HMTVE190CatalogOrderCareer lblDate lbl-sky-L' for=""> 注文日 </label>
                    <select class="HMTVE190CatalogOrderCareer ddlYearStart DropDownList Enter Tab"
                        tabindex="1"></select>
                    <label class='HMTVE190CatalogOrderCareer' for=""> 年 </label>
                    <select class="HMTVE190CatalogOrderCareer ddlMonthStart DropDownList Enter Tab"
                        tabindex="2"></select>
                    <label class='HMTVE190CatalogOrderCareer' for=""> 月 </label>
                    <select class="HMTVE190CatalogOrderCareer ddlDayStart DropDownList Enter Tab" tabindex="3"></select>
                    <label class='HMTVE190CatalogOrderCareer' for=""> 日 </label>
                    <label class='HMTVE190CatalogOrderCareer' for=""> ～ </label>
                    <select class="HMTVE190CatalogOrderCareer ddlYearEnd DropDownList Enter Tab" tabindex="4"></select>
                    <label class='HMTVE190CatalogOrderCareer' for=""> 年 </label>
                    <select class="HMTVE190CatalogOrderCareer ddlMonthEnd DropDownList Enter Tab" tabindex="5"></select>
                    <label class='HMTVE190CatalogOrderCareer' for=""> 月 </label>
                    <select class="HMTVE190CatalogOrderCareer ddlDayEnd DropDownList Enter Tab" tabindex="6"></select>
                    <label class='HMTVE190CatalogOrderCareer' for=""> 日 </label>
                    <button class="HMTVE190CatalogOrderCareer btnETSearch button Enter Tab" tabindex="7">
                        表　示
                    </button>
                </div>
                <div class='HMTVE190CatalogOrderCareer paddingSearchdiv'>
                    <label class='HMTVE190CatalogOrderCareer lblPositionTitle1 lbl-sky-L' for=""> 店舗名 </label>
                    <input class='HMTVE190CatalogOrderCareer lblPosition CELL_GLAY_L' disabled="disabled" />
                </div>
            </div>
        </fieldset>
        <!-- jqgrid -->

        <div class="HMTVE190CatalogOrderCareer pnlList">
            <table id="HMTVE190CatalogOrderCareer_tblMain"></table>
            <div id="HMTVE190CatalogOrderCareer_pager"></div>
        </div>

    </div>
</div>