<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                      FCSDL
* 20250219           機能変更               20250219_内部統制_改修要望.xlsx                    LHB
* 20250403           機能変更               202504_内部統制_要望.xlsx               lujunxia
* 20250409           機能変更               202504_内部統制_要望.xlsx               lujunxia
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDKansaJissekiShokai/HMAUDKansaJissekiShokai'));
?>
<style type="text/css">
    .HMAUDKansaJissekiShokai .ui-jqgrid tr.ui-row-ltr td {
        border-right-style: none !important;
    }

    .HMAUDKansaJissekiShokai #HMAUDKansaJissekiShokai_tblMain {
        border-right: 1px solid lightblue !important;
        border-left: 1px solid lightblue !important;
        table-layout: auto;
    }

    .HMAUDKansaJissekiShokai.btnSearch {
        float: left;
    }

    .HMAUDKansaJissekiShokai.statusSelect {
        width: 165px;
    }

    .HMAUDKansaJissekiShokai.posSearchSelect {
        width: 182px;
    }

    .HMAUDKansaJissekiShokai fieldset>div {
        padding: 3px 0px;
    }

    .HMAUDKansaJissekiShokai.coursSearchInput {
        width: 108px;
    }

    /*折行*/
    #HMAUDKansaJissekiShokai_tblMain tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    /*指摘事項NO71:ボタン押下不可時の表示:背景色「灰色」*/
    .HMAUDKansaJissekiShokai button[disabled] {
        background-color: #C3C3C3 !important;
    }

    .HMAUDKansaJissekiShokai.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 170px; */
        width: 200px;
        height: 0px;
        /* 20250219 LHB INS S */
        /* margin-right: 47px; */
        /* margin-right: 139px; */
        /* 20250219 LHB INS E */
        margin-right: 109px;
        /* 20250409 lujunxia upd e */
        margin-left: 5px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMAUDKansaJissekiShokai.courPeriod {
            margin-right: 46px;
        }
    }

    /* 20250403 lujunxia ins s */
    .HMAUDKansaJissekiShokai .ui-jqgrid-htable {
        font-size: 91%;
    }

    /* 20250403 lujunxia ins e */

    .HMAUDKansaJissekiShokai.statusSelectLabel {
        margin-left: 14px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDKansaJissekiShokai">
    <div class="HMAUDKansaJissekiShokai HMAUD-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMAUDKansaJissekiShokai LBL_TITLE_STD9 lbl-sky-L'> クール </label>
                <select class="HMAUDKansaJissekiShokai coursSearchInput Enter Tab" tabindex="1" />
                <label for="" class="HMAUDKansaJissekiShokai courPeriod"></label>
                <label for="" class='HMAUDKansaJissekiShokai LBL_TITLE_STD9 lbl-sky-L'> 拠点 </label>
                <select class="HMAUDKansaJissekiShokai posSearchSelect Enter Tab" tabindex="2" />
                <div class='HMAUDKansaJissekiShokai' style="padding: 3px 0px;">
                    <label for="" class='HMAUDKansaJissekiShokai LBL_TITLE_STD9 lbl-sky-L'> 領域 </label>
                    <input type="checkbox" class="HMAUDKansaJissekiShokai territoryChbox tradeChbox Enter Tab"
                        tabindex="3" checked="true" value="1" />
                    <label for="" class='HMAUDKansaJissekiShokai'> 営業 </label>
                    <input type="checkbox" class="HMAUDKansaJissekiShokai territoryChbox serviceChbox Enter Tab"
                        tabindex="4" checked="true" value="2" />
                    <label for="" class='HMAUDKansaJissekiShokai'> サービス </label>
                    <input type="checkbox" class="HMAUDKansaJissekiShokai territoryChbox manageChbox Enter Tab"
                        tabindex="5" checked="true" value="3" />
                    <label for="" class='HMAUDKansaJissekiShokai'> 管理 </label>
                    <input type="checkbox" class="HMAUDKansaJissekiShokai territoryChbox busiChbox Enter Tab"
                        tabindex="6" checked="true" value="4" />
                    <label for="" class='HMAUDKansaJissekiShokai'> 業売 </label>
                    <input type="checkbox" class="HMAUDKansaJissekiShokai territoryChbox busiManageChbox Enter Tab"
                        tabindex="7" checked="true" value="5" />
                    <label for="" class='HMAUDKansaJissekiShokai'> 業売管理</label>
                    <!-- 20250219 LHB INS S -->
                    <input type="checkbox" class="HMAUDKansaJissekiShokai territoryChbox carSevenChbox Enter Tab"
                        tabindex="8" checked="true" value="6" style="visibility:hidden" />
                    <label for="" class='HMAUDKansaJissekiShokai' style="visibility:hidden"> カーセブン</label>
                    <!-- 20250219 LHB INS E -->
                    <label for="" class='HMAUDKansaJissekiShokai LBL_TITLE_STD9 lbl-sky-L statusSelectLabel'> ステータス
                    </label>
                    <select class="HMAUDKansaJissekiShokai statusSelect Enter Tab" tabindex="9" />
                </div>
            </div>
            <div class="HMAUDKansaJissekiShokai">
                <button class="HMAUDKansaJissekiShokai btnSearch button Enter Tab" tabindex="10">
                    検索
                </button>
            </div>
        </fieldset>
        <div class="HMAUDKansaJissekiShokai buttonClass">
            <button class="HMAUDKansaJissekiShokai btnJisseki button Enter Tab" tabindex="11">
                監査実績へ
            </button>
            <button class="HMAUDKansaJissekiShokai btnReport button Enter Tab" tabindex="12">
                改善報告書へ
            </button>
        </div>
        <!-- jqgrid -->
        <div class="HMAUDKansaJissekiShokai pnlList">
            <table id="HMAUDKansaJissekiShokai_tblMain"></table>
        </div>
    </div>
</div>