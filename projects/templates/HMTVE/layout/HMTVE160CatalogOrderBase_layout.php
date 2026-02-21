<!DOCTYPE html>
<!--
* 説明：
*
* @author yinhuaiyu
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20240806         20240806_HMTVE(PHP)グリッド高さ調整.xlsx                         caina
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE160CatalogOrderBase/HMTVE160CatalogOrderBase"));
?>
<style type="text/css">
    .HMTVE160CatalogOrderBase.lbl-sky-green {
        width: 569px;
        background: #ddf4d8;
        margin: 3px;
    }

    .HMTVE160CatalogOrderBase.lbl-sky-green2 {
        width: 473px;
        background: #ddf4d8;
        margin: 3px;
    }

    .HMTVE160CatalogOrderBase.lbl-sky-green3 {
        width: 359px;
        background: #ddf4d8;
        margin: 3px;
    }

    .HMTVE160CatalogOrderBase.lbl-sky-green4 {
        width: 579px;
        background: #7E8ABC;
        margin: 3px;
    }

    .HMTVE160CatalogOrderBase.View1 {
        float: left;
        display: inline;
    }

    .HMTVE160CatalogOrderBase.View2 {
        float: left;
        display: inline;
        margin-left: 40px
    }

    .HMTVE160CatalogOrderBase.HMS-button-set {
        /* 20240806 caina upd s */
        /* margin-top: 116px; */
        margin-top: 76px;
        /* 20240806 caina upd e */
    }

    .CELL_SUM_C input {
        width: 92% !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE160CatalogOrderBase.lbl-sky-green {
            width: 472px !important;
        }
        .HMTVE160CatalogOrderBase.lbl-sky-green2 {
            width: 380px !important;
        }
        .HMTVE160CatalogOrderBase.lbl-sky-green3 {
            width: 280px !important;
        }
        .HMTVE160CatalogOrderBase.lbl-sky-green4 {
            width: 483px !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE160CatalogOrderBase">
    <div class="HMTVE160CatalogOrderBase HMTVE-content">
        <!-- 検索条件 -->
        <!-- jqgrid -->
        <div class="HMTVE160CatalogOrderBase View1">
            <div class="HMTVE160CatalogOrderBase pnlList_grdHonCatalog">
                <label for="" class='HMTVE160CatalogOrderBase Label1 lbl-sky-green'> 本カタログ </label>
                <table id="HMTVE160CatalogOrderBase_tblMain_grdHonCatalog"></table>
                <div class="HMTVE160CatalogOrderBase HMS-button-pane">
                    <button class="HMTVE160CatalogOrderBase btnRowAdd_grdHon button Enter Tab" tabindex="0">
                        行追加
                    </button>
                    <button class="HMTVE160CatalogOrderBase btnRowDel_grdHon button Enter Tab" tabindex="1">
                        行削除
                    </button>
                </div>
            </div>
            <div class="HMTVE160CatalogOrderBase pnlList_grdMail">
                <label for="" class='HMTVE160CatalogOrderBase Label1 lbl-sky-green4'> メール設定 </label>
                <table id="HMTVE160CatalogOrderBase_tblMain_grdMail"></table>
                <div class="HMTVE160CatalogOrderBase HMS-button-pane">
                    <button class="HMTVE160CatalogOrderBase btnRowAdd_grdMail button Enter Tab" tabindex="2">
                        行追加
                    </button>
                    <button class="HMTVE160CatalogOrderBase btnRowDel_grdMail button Enter Tab" tabindex="3">
                        行削除
                    </button>
                </div>
            </div>
        </div>
        <div class="HMTVE160CatalogOrderBase View2">
            <div class="HMTVE160CatalogOrderBase pnlList_grdYou">
                <label for="" class='HMTVE160CatalogOrderBase Label1 lbl-sky-green2'> 用品カタログ </label>
                <table id="HMTVE160CatalogOrderBase_tblMain_grdYou"></table>
                <div class="HMTVE160CatalogOrderBase HMS-button-pane">
                    <button class="HMTVE160CatalogOrderBase btnRowAdd_grdYou button Enter Tab" tabindex="4">
                        行追加
                    </button>
                    <button class="HMTVE160CatalogOrderBase btnRowDel_grdYou button Enter Tab" tabindex="5">
                        行削除
                    </button>
                </div>
            </div>
            <div class="HMTVE160CatalogOrderBase pnlList_grdCata">
                <label for="" class='HMTVE160CatalogOrderBase Label1 lbl-sky-green3'> 用品 </label>
                <table id="HMTVE160CatalogOrderBase_tblMain_grdCata"></table>
                <div class="HMTVE160CatalogOrderBase HMS-button-pane">
                    <button class="HMTVE160CatalogOrderBase btnRowAdd_grdCata button Enter Tab" tabindex="6">
                        行追加
                    </button>
                    <button class="HMTVE160CatalogOrderBase btnRowDel_grdCata button Enter Tab" tabindex="7">
                        行削除
                    </button>
                </div>
            </div>
            <div class="HMTVE160CatalogOrderBase HMS-button-pane">
                <div class='HMTVE160CatalogOrderBase HMS-button-set'>
                    <button class="HMTVE160CatalogOrderBase button btnLogin Enter Tab" tabindex="8">
                        登　録
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
