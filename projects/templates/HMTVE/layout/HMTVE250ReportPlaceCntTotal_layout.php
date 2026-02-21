<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE250ReportPlaceCntTotal/HMTVE250ReportPlaceCntTotal'));
?>
<style type="text/css">
    /*展示会開催年月 select*/
    .HMTVE250ReportPlaceCntTotal.ddlYear,
    .HMTVE250ReportPlaceCntTotal.ddlMonth {
        width: 65px;
    }

    /*件数*/
    .HMTVE250ReportPlaceCntTotal.lblItemnum {
        background-color: #FFFFFF;
        text-align: right;
        height: 21px;
        line-height: 21px;
        margin-left: 5px;
    }

    .HMTVE250ReportPlaceCntTotal.lblItem {
        float: left;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE250ReportPlaceCntTotal.searchGroup {
            width: 91% !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE250ReportPlaceCntTotal">
    <div class="HMTVE250ReportPlaceCntTotal HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset class="HMTVE250ReportPlaceCntTotal searchGroup">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE250ReportPlaceCntTotal tblMain">
                <label class='HMTVE250ReportPlaceCntTotal lblExhibitTitle1 lbl-sky-L' for=""> 対象年月 </label>
                <select class="HMTVE250ReportPlaceCntTotal ddlYear Enter Tab" tabindex="1"></select>
                <label class="HMTVE250ReportPlaceCntTotal Label1" for=""> 年 </label>
                <select class="HMTVE250ReportPlaceCntTotal ddlMonth Enter Tab" tabindex="2"></select>
                <label class="HMTVE250ReportPlaceCntTotal Label2" for=""> 月分 </label>
                <button class="HMTVE250ReportPlaceCntTotal btnExpress Button Enter Tab" tabindex="3">
                    表示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE250ReportPlaceCntTotal tblSubMain">
            <table id="HMTVE250ReportPlaceCntTotal_tblSubMain"></table>
        </div>
        <div class="HMTVE250ReportPlaceCntTotal tblItem">
            <label class='HMTVE250ReportPlaceCntTotal lblItemnum lbl-sky-L' for=""> </label>
            <label class='HMTVE250ReportPlaceCntTotal lblItem lbl-sky-L' for=""> 件数 </label>
        </div>
        <div class="HMTVE250ReportPlaceCntTotal HMS-button-pane">
            <button class="HMTVE250ReportPlaceCntTotal btnAll Button Enter Tab" tabindex="4">
                合計出力
            </button>
            <button class="HMTVE250ReportPlaceCntTotal btnView Button Enter Tab" tabindex="5">
                明細出力
            </button>
            <button class="HMTVE250ReportPlaceCntTotal btnRemove Button Enter Tab" tabindex="6">
                ロック解除
            </button>
        </div>
    </div>
</div>
