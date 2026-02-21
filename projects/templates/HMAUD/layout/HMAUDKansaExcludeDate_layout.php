<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDKansaExcludeDate/HMAUDKansaExcludeDate'));
?>
<style type="text/css">
    .HMAUDKansaExcludeDate .buttonClass {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .HMAUDKansaExcludeDate button {
        width: 80px;
    }


    .HMAUDKansaExcludeDate .numeric {
        width: 90% !important;
    }

    .HMAUDKansaExcludeDate.coursSearchInput {
        width: 108px;
    }

    .HMAUDKansaExcludeDate .cmdDisp {
        margin-left: 264px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDKansaExcludeDate">
    <div class="HMAUDKansaExcludeDate HMAUD-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMAUDKansaExcludeDate LBL_TITLE_STD9 lbl-sky-L'> クール </label>
                <select class="HMAUDKansaExcludeDate coursSearchInput Enter Tab" tabindex="1" />
                <label for="" class="HMAUDKansaExcludeDate courPeriod"></label>
            </div>
        </fieldset>
        <div class="HMAUDKansaExcludeDate buttonClass">
            <button class="HMAUDKansaExcludeDate btnRowAdd button Enter Tab" tabindex="2">
                行追加
            </button>
            <button class="HMAUDKansaExcludeDate btnLogin button Enter Tab" tabindex="3">
                更新
            </button>
            <button class="HMAUDKansaExcludeDate cmdDisp button Enter Tab" tabindex="4">
                再表示
            </button>
        </div>
        <!-- jqgrid -->
        <div class="HMAUDKansaExcludeDate pnlList">
            <table id="HMAUDKansaExcludeDate_tblMain"></table>
        </div>
    </div>
</div>