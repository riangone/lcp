<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE220PresentOrderTotal/HMTVE220PresentOrderTotal"));
?>
<style type="text/css">
    /*展示会開催年月*/
    .HMTVE220PresentOrderTotal.lblExhibitTitle1 {
        width: 105px;
    }

    .HMTVE220PresentOrderTotal.lblNumberNum {
        background-color: #FFFFFF;
        height: 21px;
        line-height: 21px;
    }

    .HMTVE220PresentOrderTotal.lblNumberNum {
        text-align: right;
        margin-left: 5px;
    }

    .HMTVE220PresentOrderTotal.lblNumber {
        float: left;
    }

    .HMTVE220PresentOrderTotal.btnExhibitSearch {
        float: none;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE220PresentOrderTotal.searchGroup {
            width: 91% !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE220PresentOrderTotal">
    <div class="HMTVE220PresentOrderTotal HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset class="HMTVE220PresentOrderTotal searchGroup">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE220PresentOrderTotal tblMain">
                <label class="HMTVE220PresentOrderTotal lblExhibitTitle1 lbl-sky-L" for=""> 展示会開催期間 </label>
                <input type="text" class="HMTVE220PresentOrderTotal txtExhibitTimeStart" readonly="true" />
                <label class="HMTVE220PresentOrderTotal" for=""> ～ </label>
                <input type="text" class="HMTVE220PresentOrderTotal txtExhibitTimeEnd" readonly="true" />
                <button class="HMTVE220PresentOrderTotal btnExhibitSearch Button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <button class="HMTVE220PresentOrderTotal btnShow Button Enter Tab" tabindex="2">
                    表示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE220PresentOrderTotal tblView">
            <table id="HMTVE220PresentOrderTotal_tblSubMain"></table>
        </div>
        <div>
            <label class="HMTVE220PresentOrderTotal lblNumberNum lbl-sky-L" for=""> </label>
            <label class="HMTVE220PresentOrderTotal lblNumber lbl-sky-L" for=""> 件数 </label>
        </div>
        <div class="HMTVE220PresentOrderTotal HMS-button-pane bottom-btn">
            <button class="HMTVE220PresentOrderTotal btnPutout Button Enter Tab" tabindex="3">
                Excel出力
            </button>
            <button class="HMTVE220PresentOrderTotal btnRemove Button Enter Tab" tabindex="4">
                ロック解除
            </button>
        </div>
    </div>
</div>
