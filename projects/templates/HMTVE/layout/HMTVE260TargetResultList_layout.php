<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE260TargetResultList/HMTVE260TargetResultList"));
?>
<style type="text/css">
    .HMTVE260TargetResultList.HMTVE-content {
        overflow-y: hidden;
    }

    .HMTVE260TargetResultList.btnExChukan,
    .HMTVE260TargetResultList.btnExGessho,
    .HMTVE260TargetResultList.btnToCar,
    .HMTVE260TargetResultList.btnToLogin,
    .HMTVE260TargetResultList.btnMend,
    .HMTVE260TargetResultList.btnSee {
        float: right
    }

    .HMTVE260TargetResultList.txtbDuring,
    .HMTVE260TargetResultList.ddlMonth {
        width: 60px !important
    }

    .HMTVE260TargetResultList.lblSouDaisuu,
    .HMTVE260TargetResultList.lblUchiRenta {
        text-align: right
    }

    .HMTVE260TargetResultList .ui-jqgrid .ui-jqgrid-view {
        font-size: 10px
    }

    .HMTVE260TargetResultList #gview_HMTVE260TargetResultList_tblMain td {
        height: 19px
    }

    .HMTVE260TargetResultList #gview_HMTVE260TargetResultList_tblSyasyu td {
        height: 21px
    }

    .HMTVE260TargetResultList.lblShopname {
        width: 250px
    }

    .HMTVE260TargetResultList.ddlMonthLable {
        width: 100px
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE260TargetResultList .ui-jqgrid-view {
            line-height: 1.08em;
        }

        .HMTVE260TargetResultList #gview_HMTVE260TargetResultList_tblMain td {
            height: 15px
        }

        .HMTVE260TargetResultList #gview_HMTVE260TargetResultList_tblSyasyu td {
            height: 15px
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE260TargetResultList">
    <div class="HMTVE260TargetResultList HMTVE-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMTVE260TargetResultList lbl-sky-L'> 対象年月 </label>
                <input type="text" class="HMTVE260TargetResultList txtbDuring Tab Enter" tabindex="1" />
                <label for=""> 年 </label>
                <select class="HMTVE260TargetResultList ddlMonth Tab Enter" tabindex="2"></select>
                <label for="" class="HMTVE260TargetResultList ddlMonthLable"> 月分 </label>
                <label for="" class='HMTVE260TargetResultList lbl-sky-L'> 店舗名 </label>
                <input type="text" class="HMTVE260TargetResultList lblShopname" readonly="readonly" />
                <button class="HMTVE260TargetResultList btnLogin button Enter Tab" tabindex="4">
                    登録
                </button>
                <button class="HMTVE260TargetResultList btnETSearch button Enter Tab" tabindex="3">
                    表示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE260TargetResultList View1">
            <table id="HMTVE260TargetResultList_tblMain"></table>
            <div class="HMTVE260TargetResultList HMS-button-pane">
                <label for="" class='HMTVE260TargetResultList lbl-sky-L'> 総登録台数 </label>
                <input type="text" class='HMTVE260TargetResultList lblSouDaisuu' readonly="readonly">
                </input>
                <label for="" class='HMTVE260TargetResultList lbl-sky-L'> 内ﾚﾝﾀｶｰ </label>
                <input type="text" class='HMTVE260TargetResultList lblUchiRenta' readonly="readonly">
                </input>
                <button class="HMTVE260TargetResultList btnToLogin button Enter Tab" tabindex="8">
                    変更画面へ
                </button>
                <button class="HMTVE260TargetResultList btnToCar button Enter Tab" tabindex="7">
                    車種内訳画面へ
                </button>
                <button class="HMTVE260TargetResultList btnExGessho button Enter Tab" tabindex="6">
                    月初会議資料出力
                </button>
                <button class="HMTVE260TargetResultList btnExChukan button Enter Tab" tabindex="5">
                    中間会議資料出力
                </button>
            </div>
        </div>
        <div class="HMTVE260TargetResultList View2">
            <table id="HMTVE260TargetResultList_tblSyasyu"></table>
            <div class="HMTVE260TargetResultList HMS-button-pane">
                <button class="HMTVE260TargetResultList btnMend button Enter Tab" tabindex="8">
                    変更画面へ
                </button>
                <button class="HMTVE260TargetResultList btnSee button Enter Tab" tabindex="7">
                    一覧画面へ
                </button>
            </div>
        </div>
    </div>
</div>