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
echo $this->Html->script(array("HMAUD/HMAUDSKDList/HMAUDSKDList"));
?>
<style type="text/css">
    .HMAUDSKDList.HMAUD-content {
        overflow-y: hidden;
    }

    /*折行*/
    .HMAUDSKDList.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMAUDSKDList .ui-jqgrid .ui-jqgrid-sortable {
        cursor: auto;
    }

    .HMAUDSKDList .CHECK_MEMBER_COLUMN {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .HMAUDSKDList.coursSearchInput {
        width: 108px;
    }

    .HMAUDSKDList.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 175px; */
        width: 200px;
        /* 20250409 lujunxia upd e */
        height: 0px;
        margin-left: 5px;
    }

    .HMAUDSKDList .containerDiv {
        display: flex;
        justify-content: space-between;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDSKDList">
    <div class="HMAUDSKDList HMAUD-content">

        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMAUDSKDList LBL_TITLE_STD9 lbl-sky-L'> クール </label>
                <select class="HMAUDSKDList coursSearchInput Enter Tab" tabindex="1" />
                <label for="" class="HMAUDSKDList courPeriod"></label>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMAUDSKDList pnlList containerDiv">
            <div>
                <table id="HMAUDSKDList_tblMain"></table>
                <div id="HMAUDSKDList_pager"></div>
            </div>
            <div>
                <table id="HMAUDSKDList_tblRiyou"></table>
                <div id="HMAUDSKDList_tblRiyou_pager"></div>
            </div>
        </div>
    </div>
</div>