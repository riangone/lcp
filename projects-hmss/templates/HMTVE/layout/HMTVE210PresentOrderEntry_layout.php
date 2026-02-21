<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE210PresentOrderEntry/HMTVE210PresentOrderEntry'));
?>
<style type="text/css">
    .HMTVE210PresentOrderEntry.HMTVE-content {
        overflow-y: hidden;
    }

    .HMTVE210PresentOrderEntry.btnExhibitSearch {
        float: none;
    }

    .HMTVE210PresentOrderEntry.lblShopName {
        margin-left: 50px
    }

    .HMTVE210PresentOrderEntry.lblShopName2 {
        width: 200px
    }

    .HMTVE210PresentOrderEntry.lbl-sky-L {
        width: 102px;
    }

    .HMTVE210PresentOrderEntry.pnlList {
        width: min-content;
    }

    .HMTVE210PresentOrderEntry.btnOrder {
        margin-top: 10px;
        float: right;
        width: 80px;
    }

    .HMTVE210PresentOrderEntry.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE210PresentOrderEntry .numeric.editable {
        width: 94% !important;
        text-align: right;
    }
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE210PresentOrderEntry.searchGroup {
            width: 90% !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE210PresentOrderEntry">
    <div class="HMTVE210PresentOrderEntry HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset class="HMTVE210PresentOrderEntry searchGroup">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE210PresentOrderEntry lblExhibitTitle lbl-sky-L' for=""> 展示会開催期間 </label>
                <input type="text" class="HMTVE210PresentOrderEntry lblExhibitTime1" readonly="true" />
                <label for=""> ～ </label>
                <input type="text" class="HMTVE210PresentOrderEntry lblExhibitTime2" readonly="true" />
                <button class="HMTVE210PresentOrderEntry btnExhibitSearch button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <label class='HMTVE210PresentOrderEntry lblShopName lbl-sky-L' for=""> 店舗名 </label>
                <input type="text" class="HMTVE210PresentOrderEntry lblShopName2 CELL_GLAY_L" readonly="readonly" />
                <button class="HMTVE210PresentOrderEntry btnShow button Enter Tab" tabindex="2">
                    表　示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE210PresentOrderEntry pnlList">
            <table id="HMTVE210PresentOrderEntry_tblMain"></table>
            <button class="HMTVE210PresentOrderEntry btnOrder button Enter Tab" tabindex="3">
                注文
            </button>
        </div>
    </div>
</div>
