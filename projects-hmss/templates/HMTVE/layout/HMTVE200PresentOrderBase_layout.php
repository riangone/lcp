<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE200PresentOrderBase/HMTVE200PresentOrderBase'));
?>
<style type="text/css">
    .HMTVE200PresentOrderBase.btnETSearch {
        float: none;
    }

    .HMTVE200PresentOrderBase fieldset {
        padding-bottom: 7.3px
    }

    .HMTVE200PresentOrderBase.lbl-sky-L {
        width: 102px;
    }

    .HMTVE200PresentOrderBase.headerCell {
        background-color: #ddf4d8;
        height: 20px;
        width: 462px;
        line-height: 20px;
        margin: 8px 0;
        padding-left: 5px;
    }

    .HMTVE200PresentOrderBase.btnReg {
        margin-left: 255px;
    }

    .HMTVE.HMTVE-layout-center {
        -ms-overflow-y: hidden !important;
    }

    .HMTVE200PresentOrderBase .align_right.editable {
        width: 93% !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE200PresentOrderBase.searchGroup {
            width: 91% !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE200PresentOrderBase">
    <div class="HMTVE200PresentOrderBase HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset class="HMTVE200PresentOrderBase searchGroup">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE200PresentOrderBase lblExhibitTitle1 lbl-sky-L' for=""> 展示会開催期間 </label>
                <input type="text" class="HMTVE200PresentOrderBase lblExhibitTermStart" readonly="true" />
                <label for=""> ～ </label>
                <input type="text" class="HMTVE200PresentOrderBase lblExhibitTermEnd" readonly="true" />
                <button class="HMTVE200PresentOrderBase btnETSearch button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <button class="HMTVE200PresentOrderBase btnPrintOut button Enter Tab" tabindex="2">
                    表　示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE200PresentOrderBase pnlList">
            <div class="HMTVE200PresentOrderBase headerCell">
                品名設定
            </div>
            <table id="HMTVE200PresentOrderBase_tblMain"></table>
        </div>
        <div class="HMTVE200PresentOrderBase HMS-button-pane">
            <button class="HMTVE200PresentOrderBase btnReg button Enter Tab" tabindex="3">
                登録
            </button>
            <button class="HMTVE200PresentOrderBase btnDel button Enter Tab" tabindex="4">
                削除
            </button>
        </div>
    </div>
</div>
