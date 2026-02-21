<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE110PublicityOrderBase/HMTVE110PublicityOrderBase"));
?>
<style type="text/css">
    .HMTVE110PublicityOrderBase fieldset {
        padding-bottom: 7.3px
    }

    .HMTVE110PublicityOrderBase.lbl-sky-L {
        width: 102px;
    }

    .HMTVE110PublicityOrderBase.headerCell {
        background-color: #ddf4d8;
        height: 20px;
        width: 462px;
        line-height: 20px;
        margin: 8px 0;
        padding-left: 5px;
    }

    .HMTVE110PublicityOrderBase.headerCell1 {
        background-color: #ddf4d8;
        height: 20px;
        width: 559px;
        line-height: 20px;
        margin: 8px 0;
        padding-left: 5px;
    }

    .HMTVE.HMTVE-layout-center {
        -ms-overflow-y: hidden !important;
    }

    .HMTVE110PublicityOrderBase.View1 {
        float: left;
        display: inline;
    }

    .HMTVE110PublicityOrderBase.View2 {
        float: left;
        display: inline;
        margin-left: 40px
    }

    /*展示会開催年月 select*/
    .HMTVE110PublicityOrderBase.ddlYear,
    .HMTVE110PublicityOrderBase.ddlMonth {
        width: 65px;
    }

    .HMTVE110PublicityOrderBase.pnlList1 .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMTVE110PublicityOrderBase.headerCell {
            width: 407px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE110PublicityOrderBase">
    <div class="HMTVE110PublicityOrderBase HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <div class="HMTVE110PublicityOrderBase tblMain">
                    <label for="" class='HMTVE110PublicityOrderBase lblWritten lbl-sky-L'> 展示会開催年月 </label>
                    <select class="HMTVE110PublicityOrderBase ddlYear Enter Tab" tabindex="1"></select>
                    <label for="" class="HMTVE110PublicityOrderBase Label1"> 年 </label>
                    <select class="HMTVE110PublicityOrderBase ddlMonth Enter Tab" tabindex="2"></select>
                    <label for="" class="HMTVE110PublicityOrderBase Label2"> 月 </label>
                    <button class="HMTVE110PublicityOrderBase btnETSearch button Enter Tab" tabindex="3">
                        表示
                    </button>
                </div>
        </fieldset>
        <div class="HMTVE110PublicityOrderBase View1">
            <div class="HMTVE110PublicityOrderBase pnlList1">
                <div class="HMTVE110PublicityOrderBase headerCell1">
                    展示会設定
                </div>
                <table id="HMTVE110PublicityOrderBase_tblMain1"></table>
            </div>
        </div>
        <div class="HMTVE110PublicityOrderBase View2">
            <div class="HMTVE110PublicityOrderBase pnlList">
                <div class="HMTVE110PublicityOrderBase headerCell">
                    品名・単価設定
                </div>
                <table id="HMTVE110PublicityOrderBase_tblMain"></table>
            </div>
            <div class="HMTVE110PublicityOrderBase pnlList">
                <div class="HMTVE110PublicityOrderBase headerCell">
                    期限
                </div>
                <label for="" class='HMTVE110PublicityOrderBase lblExhibitTitle1 lbl-sky-L'> 回収期限 </label>
                <input type="text" class="HMTVE110PublicityOrderBase Enter Tab txtDate" maxLength="10" tabindex="4" />
                まで
            </div>
            <div class="HMTVE110PublicityOrderBase HMS-button-pane">
                <div class='HMTVE110PublicityOrderBase HMS-button-set'>
                    <button class="HMTVE110PublicityOrderBase btnLogin button Enter Tab" tabindex="5">
                        登録
                    </button>
                    <button class="HMTVE110PublicityOrderBase btnDel button Enter Tab" tabindex="6">
                        削除
                    </button>
                </div>
            </div>
            <input type="text" class="HMTVE110PublicityOrderBase txtTime" / hidden>
        </div>
    </div>
</div>