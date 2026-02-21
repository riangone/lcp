<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE180CatalogOrderConfirm/HMTVE180CatalogOrderConfirm"));
?>
<style type="text/css">
    .HMTVE180CatalogOrderConfirm.lblShopCD,
    .HMTVE180CatalogOrderConfirm.lblShopNameShow,
    .HMTVE180CatalogOrderConfirm.lblOrderDayShow {
        background-color: #BABEC1;
    }

    .HMTVE180CatalogOrderConfirm.lblShopNameShow {
        width: 220px !important;
    }

    .HMTVE180CatalogOrderConfirm.lblOrderTimeShow {
        display: none
    }

    .HMTVE180CatalogOrderConfirm.btnConfirm,
    .HMTVE180CatalogOrderConfirm.btnToInput {
        float: right;
    }

    .HMTVE180CatalogOrderConfirm.CatalogLabel {
        background-color: #ddf4d8;
        width: 100%;
    }

    .HMTVE180CatalogOrderConfirm.CatalogLabel1 {
        background-color: #ddf4d8;
        width: 100px;
    }

    .HMTVE180CatalogOrderConfirm.CatalogText {
        color: #FF0000;
        padding-left: 50px;
    }

    .HMTVE180CatalogOrderConfirm.lblTd {
        width: 100px;
    }

    /*折行*/
    .HMTVE180CatalogOrderConfirm .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
        height: auto;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE180CatalogOrderConfirm body">
    <div class="HMTVE180CatalogOrderConfirm HMTVE-content tbMain">
        <fieldset class="HMTVE180CatalogOrderConfirm">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr>
                    <td colspan="8"><label for="" class='HMTVE180CatalogOrderConfirm'>
                            下記内容でよろしければ注文確定ボタンを、修正する場合は入力画面に戻るボタンを押下してください </label></td>
                </tr>
                <tr>
                    <td><label for="" class='HMTVE180CatalogOrderConfirm lblShopName lbl-sky-L'> 店舗名 </label></td>
                    <td><label for="" class='HMTVE180CatalogOrderConfirm lblShopCD lbl-sky-L'></label></td>
                    <td><label for="" class='HMTVE180CatalogOrderConfirm lblShopNameShow lbl-sky-L'></label></td>
                    <td><label for="" class='HMTVE180CatalogOrderConfirm lblTd'></label></td>
                    <td><label for="" class='HMTVE180CatalogOrderConfirm lblOrderDay lbl-sky-L'>注文日 </label></td>
                    <td><label for="" class='HMTVE180CatalogOrderConfirm lblOrderDayShow lbl-sky-L'></label></td>
                    <td><label for="" class='HMTVE180CatalogOrderConfirm lblOrderTimeShow lbl-sky-L'></label></td>
                    <td class="HMTVE180CatalogOrderConfirm HMS-button-pane">
                        <button class="HMTVE180CatalogOrderConfirm btnConfirm Enter Tab" tabindex="2">
                            注文を確認
                        </button>
                        <button class="HMTVE180CatalogOrderConfirm btnToInput Enter Tab" tabindex="1">
                            入力画面に戻る
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <!-- jqgrid -->
        <table>
            <tr>
                <td><label for="" class="HMTVE180CatalogOrderConfirm CatalogLabel">本カタログ</label></td>
                <td width="50px"></td>
                <td><label for="" class="HMTVE180CatalogOrderConfirm CatalogLabel">用品カタログ</label></td>
            </tr>
            <tr>
                <td rowspan="3">
                    <table id="HMTVE180CatalogOrderConfirm_sprList1"></table>
                </td>
                <td></td>
                <td>
                    <table id="HMTVE180CatalogOrderConfirm_sprList2"></table>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><label for="" class="HMTVE180CatalogOrderConfirm CatalogLabel1">用品</label></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table id="HMTVE180CatalogOrderConfirm_sprList3"></table>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="checkbox" class="HMTVE180CatalogOrderConfirm chkHaisouKibou" />
                    <label for="" class='HMTVE180CatalogOrderConfirm lblCaution'> 配送希望 </label><label for=""
                        class='HMTVE180CatalogOrderConfirm CatalogText'> ※配送希望の場合、配送費用は店舗負担となります </label>
                </td>
            </tr>
        </table>
    </div>
</div>