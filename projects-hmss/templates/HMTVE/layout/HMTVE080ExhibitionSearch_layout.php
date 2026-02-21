<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE080ExhibitionSearch/HMTVE080ExhibitionSearch"));
?>
<style type="text/css">
    .HMTVE080ExhibitionSearch.lbl-sky-L {
        width: 102px;
    }

    .HMTVE080ExhibitionSearch.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE080ExhibitionSearch.HMS-button-pane {
        margin-top: 10px;
    }

    .HMTVE080ExhibitionSearch.pnlList {
        margin-top: 5px;
    }

    .HMTVE080ExhibitionSearch.HMTVE-content {
        height: 420px;
    }

    .HMTVE080ExhibitionSearch.ddlExhibitDay {
        width: 60px;
    }

    .HMTVE080ExhibitionSearch.mmExhibitDay {
        width: 43px;
    }

    .HMTVE080ExhibitionSearch .custom-header {
        font-size: 14px !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMTVE080ExhibitionSearch.HMTVE-content {
            height: 400px;
        }

        .HMTVE080ExhibitionSearch .custom-header {
            font-size: 10px !important;
        }
    }
</style>
<div class="HMTVE080ExhibitionSearch body">
    <div class="HMTVE080ExhibitionSearch HMTVE-content">
        <div class="HMTVE080ExhibitionSearch HMS-button-pane">
            <label for="" class='HMTVE080ExhibitionSearch lblExhibitTitle lbl-sky-L'>展示会開催年月</label>
            <select class="HMTVE080ExhibitionSearch ddlExhibitDay Enter Tab" tabindex="1"></select>
            <label for="" class='HMTVE080ExhibitionSearch LBL_TITLE_STD9'> 年 </label>
            <select class="HMTVE080ExhibitionSearch mmExhibitDay Enter Tab" tabindex="2"></select>
            <label for="" class='HMTVE080ExhibitionSearch LBL_TITLE_STD9'> 月</label>

            <div class="HMTVE080ExhibitionSearch HMS-button-set">
                <button class="HMTVE080ExhibitionSearch btnView Enter Tab" tabindex="3">
                    表示
                </button>
                <button class="HMTVE080ExhibitionSearch btnClose Enter Tab" tabindex="4">
                    閉じる
                </button>
            </div>
        </div>

        <div class="HMTVE080ExhibitionSearch pnlList">
            <table id="HMTVE080ExhibitionSearchtblMain"></table>
            <div id="HMTVE080ExhibitionSearch_pager"></div>
        </div>

        <div class="HMTVE080ExhibitionSearch HMS-button-pane">
            <div class="HMTVE080ExhibitionSearch HMS-button-set">
                <button class="HMTVE080ExhibitionSearch btnSel Enter Tab" tabindex="5">
                    選択
                </button>
            </div>
        </div>
    </div>
</div>