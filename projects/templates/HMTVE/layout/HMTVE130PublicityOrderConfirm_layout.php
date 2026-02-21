<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE130PublicityOrderConfirm/HMTVE130PublicityOrderConfirm'));
?>
<style type="text/css">
    .HMTVE130PublicityOrderConfirm.lbl-sky-L {
        width: 102px;
    }

    .HMTVE130PublicityOrderConfirm.HMS-button-pane {
        margin-top: 20px;
    }

    .HMTVE130PublicityOrderConfirm.lblSum {
        text-align: right;
    }

    .HMTVE130PublicityOrderConfirm.lblDay,
    .HMTVE130PublicityOrderConfirm.lblSum {
        width: 10em;
        border: solid 1px;
        padding-left: 2px
    }

    .HMTVE130PublicityOrderConfirm.lblShop {
        width: 15em;
        border: solid 1px;
        padding-left: 2px
    }

    .HMTVE130PublicityOrderConfirm.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE130PublicityOrderConfirm.HMS-button-pane {
        text-align: right;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE130PublicityOrderConfirm body">
    <div class="HMTVE130PublicityOrderConfirm HMTVE-content">
        <div>
            <span>下記内容でよろしければ注文確定ボタンを、修正する場合は入力画面に戻るボタンを押下してください</span>
        </div>
        <div>
            <label class='HMTVE130PublicityOrderConfirm Label1 lbl-sky-L' for=""> 店舗名 </label>
            <label class='HMTVE130PublicityOrderConfirm lblShop' for=""> &nbsp; </label>
        </div>
        <div>
            <label class='HMTVE130PublicityOrderConfirm Label2 lbl-sky-L' for=""> 展示会開催年月 </label>
            <label class='HMTVE130PublicityOrderConfirm lblDay' for=""> &nbsp; </label>
        </div>
        <div>
            <label class='HMTVE130PublicityOrderConfirm lblSum1 lbl-sky-L' for=""> 注文金額合計 </label>
            <label class='HMTVE130PublicityOrderConfirm lblSum' for=""> &nbsp; </label>
        </div>
        <div class="HMTVE130PublicityOrderConfirm pnlList">
            <table id="HMTVE130PublicityOrderConfirm_talGrd"></table>
            <div class="HMTVE130PublicityOrderConfirm HMS-button-pane">
                <button class="HMTVE130PublicityOrderConfirm btnReturn button Enter Tab" tabindex="1">
                    入力画面に戻る
                </button>
                <button class="HMTVE130PublicityOrderConfirm btnValidate button Enter Tab" tabindex="2">
                    注文を確認
                </button>
            </div>
        </div>
    </div>
</div>