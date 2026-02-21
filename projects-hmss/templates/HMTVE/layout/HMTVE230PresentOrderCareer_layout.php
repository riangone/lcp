<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE230PresentOrderCareer/HMTVE230PresentOrderCareer"));
?>
<style type="text/css">
    .HMTVE230PresentOrderCareer.paddingSearchdiv {
        margin-top: 7px
    }

    /*折行*/
    .HMTVE230PresentOrderCareer.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE230PresentOrderCareer.ddlYearB,
    .HMTVE230PresentOrderCareer.ddlYearE {
        width: 62px;
    }

    .HMTVE230PresentOrderCareer.ddlMonthB,
    .HMTVE230PresentOrderCareer.ddlMonthE,
    .HMTVE230PresentOrderCareer.ddlDayB,
    .HMTVE230PresentOrderCareer.ddlDayE {
        width: 43px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE230PresentOrderCareer.searchGroup {
            width: 91% !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE230PresentOrderCareer">
    <div class="HMTVE230PresentOrderCareer HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset class="HMTVE230PresentOrderCareer searchGroup">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <div>
                    <label for="" class='HMTVE230PresentOrderCareer lblExhibitTime lbl-sky-L'> 展示会開催日 </label>
                    <select class="HMTVE230PresentOrderCareer ddlYearB DropDownList Enter Tab" tabindex="1"></select>
                    <label for="" class='HMTVE230PresentOrderCareer'> 年 </label>
                    <select class="HMTVE230PresentOrderCareer ddlMonthB DropDownList Enter Tab" tabindex="2"></select>
                    <label for="" class='HMTVE230PresentOrderCareer'> 月 </label>
                    <select class="HMTVE230PresentOrderCareer ddlDayB DropDownList Enter Tab" tabindex="3"></select>
                    <label for="" class='HMTVE230PresentOrderCareer'> 日 </label>
                    <label for="" class='HMTVE230PresentOrderCareer'> ～ </label>
                    <select class="HMTVE230PresentOrderCareer ddlYearE DropDownList Enter Tab" tabindex="4"></select>
                    <label for="" class='HMTVE230PresentOrderCareer'> 年 </label>
                    <select class="HMTVE230PresentOrderCareer ddlMonthE DropDownList Enter Tab" tabindex="5"></select>
                    <label for="" class='HMTVE230PresentOrderCareer'> 月 </label>
                    <select class="HMTVE230PresentOrderCareer ddlDayE DropDownList Enter Tab" tabindex="6"></select>
                    <label for="" class='HMTVE230PresentOrderCareer'> 日 </label>
                </div>
                <div class='HMTVE230PresentOrderCareer paddingSearchdiv'>
                    <label for="" class='HMTVE230PresentOrderCareer lblShop lbl-sky-L'> 店舗名 </label>
                    <input class='HMTVE230PresentOrderCareer lblShopMei CELL_GLAY_L' disabled="disabled" />
                    <button class="HMTVE230PresentOrderCareer btn Enter Tab" tabindex="7">
                        表　示
                    </button>
                </div>
            </div>
        </fieldset>
        <!-- jqgrid -->

        <div class="HMTVE230PresentOrderCareer pnlList">
            <table id="HMTVE230PresentOrderCareer_tblMain"></table>
            <div id="HMTVE230PresentOrderCareer_pager"></div>
        </div>

    </div>
</div>
