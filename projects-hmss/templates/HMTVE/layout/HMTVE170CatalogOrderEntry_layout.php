<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE170CatalogOrderEntry/HMTVE170CatalogOrderEntry'));
?>
<style type="text/css">
    .HMTVE170CatalogOrderEntry.lblShopNameShow,
    .HMTVE170CatalogOrderEntry.lblOrderDayShow {
        background-color: #BABEC1;
    }

    .HMTVE170CatalogOrderEntry.lblShopNameShow {
        width: 180px;
    }

    .HMTVE170CatalogOrderEntry.lblOrderTimeShow {
        display: none;
    }

    .HMTVE170CatalogOrderEntry.txtShopCD {
        width: 50px !important;
    }

    .HMTVE170CatalogOrderEntry.lblTd {
        width: 80px;
    }

    /*折行*/
    .HMTVE170CatalogOrderEntry#HMTVE170CatalogOrderEntry_sprList1 .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE170CatalogOrderEntry#HMTVE170CatalogOrderEntry_sprList2 .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE170CatalogOrderEntry#HMTVE170CatalogOrderEntry_sprList3 .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE170CatalogOrderEntry.jqgrid {
        background-color: #ddf4d8;
        width: 470px;
    }

    .HMTVE170CatalogOrderEntry.label {
        width: 80px;
    }

    .HMTVE170CatalogOrderEntry.jqgrid2 {
        background-color: #ddf4d8;
        width: 395px;
    }

    .HMTVE170CatalogOrderEntry.lblCaution1,
    .HMTVE170CatalogOrderEntry.lblCaution2,
    .HMTVE170CatalogOrderEntry.lblCaution3 {
        color: #FF0000;
        padding-left: 50px;
    }

    #HMTVE170CatalogOrderEntry_sprList1 input[type='text'],
    #HMTVE170CatalogOrderEntry_sprList2 input[type='text'],
    #HMTVE170CatalogOrderEntry_sprList3 input[type='text'] {
        width: 90% !important;
    }

    .HMTVE170CatalogOrderEntry.alignTop {
        vertical-align: top;
    }

    .HMTVE170CatalogOrderEntry.fieldset {
        padding: 3px 10px !important;
    }
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE170CatalogOrderEntry.width {
            width: 430px !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE170CatalogOrderEntry body">
    <div class="HMTVE170CatalogOrderEntry HMTVE-content tblMain">
        <fieldset class="HMTVE170CatalogOrderEntry fieldset">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr>
                    <td><label class='HMTVE170CatalogOrderEntry lblShopName lbl-sky-L' for=""> 店舗名 </label></td>
                    <td>
                        <input type="text" class="HMTVE170CatalogOrderEntry txtShopCD Enter Tab" maxlength="3"
                            tabindex="1" />
                    </td>
                    <td><label class="HMTVE170CatalogOrderEntry lblShopNameShow lbl-sky-L" for=""></label></td>
                    <td><label class='HMTVE170CatalogOrderEntry lblTd' for=""></label></td>
                    <td><label class='HMTVE170CatalogOrderEntry lblOrderDay lbl-sky-L' for=""> 注文日</label></td>
                    <td><label class="HMTVE170CatalogOrderEntry lblOrderDayShow lbl-sky-L" for=""> </label></td>
                    <td><label class="HMTVE170CatalogOrderEntry lblOrderTimeShow lbl-sky-L" for=""></label></td>
                    <td class="HMTVE170CatalogOrderEntry HMS-button-pane">
                        <button class="HMTVE170CatalogOrderEntry btnETOrder Enter Tab" tabindex="2">
                            注文
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <!-- jqgrid -->
        <table>
            <tr>
                <td><label class="HMTVE170CatalogOrderEntry jqgrid" for="">本カタログ</label></td>
                <td class="HMTVE170CatalogOrderEntry label"></td>
                <td><label class="HMTVE170CatalogOrderEntry jqgrid width" for="">用品カタログ</label></td>
            </tr>
            <tr>
                <td rowspan="3" class="HMTVE170CatalogOrderEntry alignTop">
                    <table id="HMTVE170CatalogOrderEntry_sprList1"></table>
                </td>
                <td><label class="HMTVE170CatalogOrderEntry label" for=""></label></td>
                <td>
                    <table id="HMTVE170CatalogOrderEntry_sprList2"></table>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><label class="HMTVE170CatalogOrderEntry jqgrid2" for="">用品</label></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <table id="HMTVE170CatalogOrderEntry_sprList3"></table>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="checkbox" class="HMTVE170CatalogOrderEntry chkHaisouKibou" />
                    <span class='HMTVE170CatalogOrderEntry' for=""> 配送を希望しない </span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <span class='HMTVE170CatalogOrderEntry lblCaution1' for=""> ※配送を希望しない場合でも、3日間取りに来られない場合は配送いたします。
                    </span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <span class='HMTVE170CatalogOrderEntry lblCaution2' for=""> ※配送費用は、店舗負担となります</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <span class='HMTVE170CatalogOrderEntry lblCaution3' for=""> ※14時までの注文で翌日の配送となります(土日祝は除く) </span>
                </td>
            </tr>
        </table>
    </div>
</div>
