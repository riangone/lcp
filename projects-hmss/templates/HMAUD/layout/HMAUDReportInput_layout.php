<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                        FCSDL
* 20250409           機能変更               202504_内部統制_要望.xlsx              lujunxia
* 20250508           機能変更               202505_内部統制_要望.xlsx              ciyuanchen
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDReportInput/HMAUDReportInput'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .HMAUDReportInput #HMAUDReportInput_tblMain {
        border-right: 1px solid lightblue !important;
        border-left: 1px solid lightblue !important;
    }

    /*折行*/
    .HMAUDReportInput.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    .HMAUDReportInput.pnlList1 .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    /*.HMAUDReportInput.pnlList1
    {
        margin-top: -21px;
    }*/
    .HMAUDReportInput.btnJisseki {
        float: left;

    }

    .HMAUDReportInput.btnShokai {
        float: left;
    }

    .HMAUDReportInput.btnSave {
        float: left;

    }

    .HMAUDReportInput.btnSearch {
        float: left;
    }

    .HMAUDReportInput.posSearch,
    .HMAUDReportInput.statusSelect,
    .HMAUDReportInput.coursSearchInput {
        width: 180px;
    }

    .HMAUDReportInput.lblBusyoNm {
        width: 150px;
    }

    .HMAUDReportInput.LBL_TITLE_STD10 {
        background-color: white !important;
        height: 20px;
        width: 130px;
    }

    .HMAUDReportInput.LBL_TITLE_STD10 span {
        vertical-align: middle;
    }

    /*指摘事項NO71:ボタン押下不可時の表示:背景色「灰色」*/
    .HMAUDReportInput button[disabled] {
        background-color: #C3C3C3 !important;
    }

    .HMAUDReportInput.btnHistorytd {
        width: 1%;
        vertical-align: bottom;
    }

    .HMAUDReportInput fieldset,
    .HMAUD-content fieldset {
        max-height: 300px;
    }

    .HMAUDReportInput .btnHistoryDiv {
        position: absolute;
        bottom: 0;
        right: -10px;
    }

    .HMAUDReportInput.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 175px; */
        width: 200px;
        /* 20250409 lujunxia upd e */
        margin-left: 5px;
    }

    /* 20250508 CI INS S */
    .HMAUDReportInput-resizable-handle {
        height: 10px;
        background: #ddd;
        cursor: row-resize;
        margin: 2px 0;
        border: 1px solid #aaa;
        text-align: center;
        line-height: 10px;
        color: #666;
    }

    .HMAUDReportInput-resizable-handle::after {
        content: "≡";
        font-size: 12px;
    }

    .HMAUDReportInput fieldset {
        flex: 0 0 auto;
        resize: none;
        overflow: auto;
        min-height: 20px;
    }

    .HMAUDReportInput-resizable-handle {
        flex: 0 0 10px;
        cursor: row-resize;
        z-index: 10;
        transition: background 0.2s;
    }

    .HMAUDReportInput.pnlList {
        flex: 1 1 auto;
        min-height: 100px;
        overflow: auto;
        position: relative;
    }

    .HMAUDReportInput #HMAUDReportInput_tblMain {
        height: 100% !important;
    }

    .HMAUDReportInput.add {
        display: inline-block;
        height: 24px;
        min-width: 30px;
        max-width: 948px;
        transition: width 0.3s ease;
        overflow: hidden;
        text-align: center;
        line-height: 24px;
        padding: 0 8px;
        border: 1px solid #ccc;
        background: #f0f0f0;
        cursor: pointer;
    }

    /* 20250508 CI INS E */

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMAUDReportInput .btnHistoryDiv {
            right: -2px;
        }

        .HMAUDReportInput.posSearch,
        .HMAUDReportInput.statusSelect,
        .HMAUDReportInput.coursSearchInput {
            width: 130px;
        }

        .HMAUDReportInput fieldset,
        .HMAUD-content fieldset {
            max-height: 280px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDReportInput">
    <div class="HMAUDReportInput HMAUD-content">
        <!-- 検索条件 -->
        <fieldset style="overflow-y: auto; max-height: 300px; position: relative;">
            <legend><b><span>検索条件</span></b></legend>
            <div>
                <table class="HMAUDReportInputcommentedit" style="width: 100%;">
                    <tr>
                        <td style="vertical-align: top;">
                            <table>
                                <tr>
                                    <td>
                                        <label class='HMAUDReportInput LBL_TITLE_STD9 lbl-sky-L'>クール</label>
                                        <select class="HMAUDReportInput coursSearchInput Enter Tab" tabindex="0" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="HMAUDReportInput courPeriod"></label>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <!-- <td style="width:25px"></td> -->

                        <td rowspan="6" style="vertical-align: top; position: relative;">
                            <div class="HMAUDReportInput pnlList1">
                                <table id="HMAUDReportInput_tblMain2" style="width: 100%;" />
                            </div>
                            <div class="btnHistoryDiv">
                                <button class="HMAUDReportInput btnHistory button Enter Tab" tabindex="4">
                                    履歴
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <label class='HMAUDReportInput LBL_TITLE_STD9 lbl-sky-L'>拠点</label>
                            <select class="HMAUDReportInput posSearch Enter Tab" tabindex="1" />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <label class='HMAUDReportInput LBL_TITLE_STD9 lbl-sky-L'>領域</label>
                            <select class="HMAUDReportInput statusSelect Enter Tab" tabindex="2" />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding-top: 10px;">
                            <button class="HMAUDReportInput btnSearch button Enter Tab" tabindex="3">
                                検索
                            </button>

                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding-top: 10px;">
                            <button class="HMAUDReportInput btnSave button Enter Tab" tabindex="5"
                                style="margin-right: 10px;">
                                保存
                            </button>
                            <button class="HMAUDReportInput btnJisseki button Enter Tab" tabindex="6">
                                実績入力へ
                            </button>
                            <button class="HMAUDReportInput btnShokai button Enter Tab" tabindex="7"
                                style="margin-left: 10px;">
                                実績照会へ
                            </button>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top: 10px;">
                            <div class='HMAUDReportInput LBL_TITLE_STD10'>
                                <span class='HMAUDReportInput LBL_TITLE_STD11'>指摘件数：０件</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>

        <div class="HMAUDReportInput-resizable-handle"></div>


        <!-- jqgrid -->
        <button class="HMAUDReportInput add button Enter Tab" tabindex="9">
        </button>
        <div class="HMAUDReportInput pnlList">
            <table id="HMAUDReportInput_tblMain"></table>
        </div>
    </div>