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
echo $this->Html->script(array("HMAUD/HMAUDKansaJissekiInput/HMAUDKansaJissekiInput"));
?>
<style type="text/css">
    .HMAUDKansaJissekiInput.HMS-button-pane button {
        margin-left: 80px;
    }

    .HMAUDKansaJissekiInput.territorySelect {
        width: 150px;
    }

    .HMAUDKansaJissekiInput.posSearchInput {
        width: 182px;
    }

    .HMAUDKansaJissekiInput.coursSearchInput {
        width: 108px;
    }

    .HMAUDKansaJissekiInput.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 175px; */
        width: 200px;
        /* 20250409 lujunxia upd e */
        height: 0px;
        margin-left: 5px;
    }

    /*折行*/
    #HMAUDKansaJissekiInput_tblMain tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    #HMAUDKansaJissekiInput_tblMain select {
        width: 90%
    }

    #HMAUDKansaJissekiInput_tblMain textarea {
        width: 95%
    }

    .HMAUDKansaJissekiInput.btnSearch {
        float: unset !important;
        margin-top: 9px !important;
    }

    /*指摘事項NO71:ボタン押下不可時の表示:背景色「灰色」*/
    .HMAUDKansaJissekiInput button[disabled] {
        background-color: #C3C3C3 !important;
    }
</style>
<div class="HMAUDKansaJissekiInput">
    <div class="HMAUDKansaJissekiInput HMAUD-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMAUDKansaJissekiInput LBL_TITLE_STD9 lbl-sky-L'> クール </label>
                <select class="HMAUDKansaJissekiInput coursSearchInput Enter Tab" tabindex="1"></select>
                <label for="" class="HMAUDKansaJissekiInput courPeriod"></label>
                <label for="" class='HMAUDKansaJissekiInput LBL_TITLE_STD9 lbl-sky-L'> 拠点 </label>
                <select class="HMAUDKansaJissekiInput posSearchInput Enter Tab" tabindex="2"></select>
                <label for="" class='HMAUDKansaJissekiInput LBL_TITLE_STD9 lbl-sky-L'> 領域 </label>
                <select class="HMAUDKansaJissekiInput territorySelect Enter Tab" tabindex="3"></select>
            </div>
            <button class="HMAUDKansaJissekiInput btnSearch button Enter Tab" tabindex="4">
                検索
            </button>
        </fieldset>
        <div class="HMAUDKansaJissekiInput buttonClass">
            <div class="HMAUDKansaJissekiInput HMS-button-pane">
                <label for="" class='HMAUDKansaJissekiInput LBL_TITLE_STD9 lbl-sky-L'> 実施日 </label>
                <input type="text" class="HMAUDKansaJissekiInput dateInput Enter Tab" maxlength="10" tabindex="5" />
                <button class="HMAUDKansaJissekiInput btnSave button Enter Tab" tabindex="6">
                    保存
                </button>
                <button class="HMAUDKansaJissekiInput btnConfirm button Enter Tab" tabindex="7">
                    確定
                </button>
                <button class="HMAUDKansaJissekiInput btnReport button Enter Tab" tabindex="8">
                    改善報告書へ
                </button>
                <button class="HMAUDKansaJissekiInput btnShokai button Enter Tab" tabindex="9">
                    実績照会へ
                </button>
            </div>
        </div>
        <div class="HMAUDKansaJissekiInput pnlList">
            <!-- jqgrid -->
            <table id="HMAUDKansaJissekiInput_tblMain"></table>
        </div>
    </div>
</div>