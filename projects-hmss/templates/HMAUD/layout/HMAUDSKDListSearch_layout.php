<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                        FCSDL
* 20250409           機能変更               202504_内部統制_要望.xlsx              lujunxia
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMAUD/HMAUDSKDListSearch/HMAUDSKDListSearch"));
?>
<style type="text/css">
    .HMAUDSKDListSearch.HMAUD-content {
        overflow-y: hidden;
    }

    /*折行*/
    .HMAUDSKDListSearch.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMAUDSKDListSearch .ui-jqgrid .ui-jqgrid-sortable {
        cursor: auto;
    }

    .HMAUDSKDListSearch .CHECK_MEMBER_COLUMN {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .HMAUDSKDListSearch.coursSearchInput {
        width: 108px;
    }

    .HMAUDSKDListSearch.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 175px; */
        width: 200px;
        /* 20250409 lujunxia upd e */
        height: 0px;
        margin-left: 5px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDSKDListSearch">
    <div class="HMAUDSKDListSearch HMAUD-content">

        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMAUDSKDListSearch LBL_TITLE_STD9 lbl-sky-L'> クール </label>
                <select class="HMAUDSKDListSearch coursSearchInput Enter Tab" tabindex="1" />
                <label for="" class="HMAUDSKDListSearch courPeriod"></label>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMAUDSKDListSearch pnlList">
            <table id="HMAUDSKDListSearch_tblMain"></table>
            <div id="HMAUDSKDListSearch_pager"></div>
        </div>
    </div>
</div>