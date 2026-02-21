<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE120PublicityOrderEntry/HMTVE120PublicityOrderEntry"));
?>
<style type="text/css">
    /*展示会開催年月*/
    .HMTVE120PublicityOrderEntry.label1 {
        width: 105px;
    }

    /*展示会開催年月 select*/
    .HMTVE120PublicityOrderEntry.DdlYear,
    .HMTVE120PublicityOrderEntry.DdlMonth {
        width: 65px;
    }

    /*label*/
    .HMTVE120PublicityOrderEntry.lblDate {
        background-color: #FFFFFF;
        text-align: center;
        border: solid 1px black;
        width: 185px;
    }

    /*注文内容確認画面へ*/
    .HMTVE120PublicityOrderEntry.btnCheck button {
        float: right;
    }

    /*店舗名 input*/
    .HMTVE120PublicityOrderEntry.lblStore {
        width: 150px;
    }

    /*折行*/
    .HMTVE120PublicityOrderEntry.tblMain1 .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE120PublicityOrderEntry">
    <div class="HMTVE120PublicityOrderEntry HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE120PublicityOrderEntry tblMain">
                <label for="" class='HMTVE120PublicityOrderEntry label1 lbl-sky-L'> 展示会開催年月 </label>
                <select class="HMTVE120PublicityOrderEntry DdlYear Enter Tab" tabindex="1"></select>
                <label for="" class="HMTVE120PublicityOrderEntry lblYear"> 年 </label>
                <select class="HMTVE120PublicityOrderEntry DdlMonth Enter Tab" tabindex="2"></select>
                <label for="" class="HMTVE120PublicityOrderEntry lblMonth"> 月 </label>
                <label for="" class='HMTVE120PublicityOrderEntry lblSname lbl-sky-L'> 店舗名 </label>
                <input type="text" class='HMTVE120PublicityOrderEntry lblStore lbl-grey-L' readonly="true" />
                <button class="HMTVE120PublicityOrderEntry btnView Button Enter Tab" tabindex="3">
                    表示
                </button>
            </div>
        </fieldset>
        <div class="HMTVE120PublicityOrderEntry TabCellDate">
            <label for="" class='HMTVE120PublicityOrderEntry lblDate'> </label>
        </div>
        <!-- jqgrid -->
        <div class="HMTVE120PublicityOrderEntry tblMain1">
            <table id="HMTVE120PublicityOrderEntry_tblSubMain"></table>
        </div>
        <div class="HMTVE120PublicityOrderEntry btnCheck HMS-button-pane">
            <button class="HMTVE120PublicityOrderEntry btnCheck Button Enter Tab" tabindex="4">
                注文内容確認画面へ
            </button>
        </div>
    </div>
</div>