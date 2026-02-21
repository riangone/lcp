<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMAUD/HMAUDAdminMente/HMAUDAdminMente"));
?>
<style type="text/css">
    .HMAUDAdminMente.btnRowAdd {
        margin-left: 80px;
    }

    /*折行*/
    #HMAUDAdminMente_tblMain tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    #HMAUDAdminMente_tblMain select {
        width: 90%
    }

    #HMAUDAdminMente_tblMain textarea {
        width: 95%
    }

    .HMAUDAdminMente.btnSearch {
        float: unset !important;
        margin-top: 9px !important;
    }
</style>
<div class="HMAUDAdminMente">
    <div class="HMAUDAdminMente HMAUD-content">
        <!-- 検索条件 -->

        <div class="HMAUDAdminMente HMS-button-pane">
            <button class="HMAUDAdminMente btnUpdata button Enter Tab" tabindex="0">
                更新
            </button>
            <button class="HMAUDAdminMente btnRetrun button Enter Tab" tabindex="1">
                キャンセル
            </button>
            <button class="HMAUDAdminMente btnRowAdd button Enter Tab" tabindex="3">
                行追加
            </button>
            <button class="HMAUDAdminMente btnRowDel button Enter Tab" tabindex="4">
                行削除
            </button>
        </div>

        <div class="HMAUDAdminMente pnlList">
            <!-- jqgrid -->
            <table id="HMAUDAdminMente_tblMain"></table>
        </div>
    </div>
</div>