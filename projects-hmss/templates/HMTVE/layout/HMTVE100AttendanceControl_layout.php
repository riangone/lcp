<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE100AttendanceControl/HMTVE100AttendanceControl'));
?>
<style type="text/css">
    .HMTVE100AttendanceControl.btnETSearch {
        float: none;
    }

    .HMTVE100AttendanceControl.lblExhibitTitle2 {
        margin-left: 15px
    }

    .HMTVE100AttendanceControl.ddlExhibitDay {
        width: 120px
    }

    .HMTVE100AttendanceControl.lblTenpoNM {
        width: 272px
    }

    .HMTVE100AttendanceControl.lblTenpoCd {
        display: none
    }

    .HMTVE100AttendanceControl.paddingSearchdiv {
        margin-top: 7px
    }

    .HMTVE100AttendanceControl.lbl-sky-L {
        width: 102px;
    }

    .HMTVE.HMTVE-layout-center {
        -ms-overflow-y: hidden !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMTVE100AttendanceControl.lblTenpoNM {
            width: 205px
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE100AttendanceControl">
    <div class="HMTVE100AttendanceControl HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE100AttendanceControl lblExhibitTitle1 lbl-sky-L' for=""> 展示会開催期間 </label>
                <input type="text" class="HMTVE100AttendanceControl lblExhibitTermStart" readonly="true" />
                <label for=""> ～ </label>
                <input type="text" class="HMTVE100AttendanceControl lblExhibitTermEnd" readonly="true" />
                <button class="HMTVE100AttendanceControl btnETSearch button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <label class='HMTVE100AttendanceControl lblExhibitTitle2 lbl-sky-L' for=""> 展示会開催日 </label>
                <select class="HMTVE100AttendanceControl ddlExhibitDay Tab Enter" tabindex="2"></select>
                <button class="HMTVE100AttendanceControl btnPrintOut button Enter Tab" tabindex="3">
                    表　示
                </button>
            </div>
            <div class='HMTVE100AttendanceControl paddingSearchdiv'>
                <label class='HMTVE100AttendanceControl lblTenpo lbl-sky-L' for=""> 店舗名 </label>
                <input type="text" class="HMTVE100AttendanceControl lblTenpoCd" readonly="true" />
                <input type="text" class="HMTVE100AttendanceControl lblTenpoNM" readonly="true" />
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE100AttendanceControl pnlList">
            <table id="HMTVE100AttendanceControl_tblMain"></table>
        </div>
        <div class="HMTVE100AttendanceControl HMS-button-pane">
            <button class="HMTVE100AttendanceControl btnReg button Enter Tab" tabindex="4">
                登録
            </button>
            <button class="HMTVE100AttendanceControl btnDel button Enter Tab" tabindex="5">
                削除
            </button>
        </div>
    </div>
</div>