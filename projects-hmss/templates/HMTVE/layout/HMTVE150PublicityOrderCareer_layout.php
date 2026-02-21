<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE150PublicityOrderCareer/HMTVE150PublicityOrderCareer"));
?>
<style type="text/css">
    .HMTVE150PublicityOrderCareer.lblExhibitTitle1 {
        width: 105px;
    }

    .HMTVE150PublicityOrderCareer.tblMain select {

        width: 70px;
    }

    #gbox_HMTVE150PublicityOrderCareer_grdExView tr.jqgrow td,
    .HMDPS105CSVReOut.pnlCsvOut tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE150PublicityOrderCareer.paddingSearchdiv {
        margin-top: 7px
    }
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE150PublicityOrderCareer.searchGroup {
            width: 90% !important;
        }
    }

</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE150PublicityOrderCareer">
    <div class="HMTVE150PublicityOrderCareer HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset class="HMTVE150PublicityOrderCareer searchGroup">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE150PublicityOrderCareer tblMain">
                <label for="" class='HMTVE150PublicityOrderCareer  lblExhibitTitle1 lbl-sky-L'> 展示会開催年月 </label>
                <select class="HMTVE150PublicityOrderCareer ddlYear Enter Tab" tabindex="1"></select>
                <label for=""> 年 </label>
                <select class="HMTVE150PublicityOrderCareer ddlMonth Enter Tab" tabindex="2"></select>
                <label for=""> 月 ～</label>
                <select class="HMTVE150PublicityOrderCareer ddlYear2 Enter Tab" tabindex="3"></select>
                <label for=""> 年 </label>
                <select class="HMTVE150PublicityOrderCareer ddlMonth2 Enter Tab" tabindex="4"></select>
                <label for=""> 月 </label>
            </div>
            <div class="HMTVE150PublicityOrderCareer paddingSearchdiv">
                <label for="" class='HMTVE150PublicityOrderCareer lblExhibitTitle1 Label1 lbl-sky-L'> 店舗名 </label>
                <input class="HMTVE150PublicityOrderCareer lblShopNa Enter Tab" disabled="disabled" />
                <button class='HMTVE150PublicityOrderCareer btnShow Enter Tab HMS-button-set' tabindex="5">
                    表示
                </button>
            </div>
        </fieldset>
        <div class="HMTVE150PublicityOrderCareer grdExView">
            <table id="HMTVE150PublicityOrderCareer_grdExView"></table>
            <div id="HMTVE150PublicityOrderCareer_pager"></div>
        </div>
    </div>
</div>
