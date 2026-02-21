<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMAUD/HMAUDViewerMente/HMAUDViewerMente"));
?>
<style type="text/css">
    .HMAUDViewerMente.btnRowAdd {
        margin-left: 80px;
    }

    /*折行*/
    #HMAUDViewerMente_tblMain tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    #HMAUDViewerMente_tblMain select {
        width: 90%
    }

    #HMAUDViewerMente_tblMain textarea {
        width: 95%
    }

    .HMAUDViewerMente.btnSearch {
        float: unset !important;
        margin-top: 9px !important;
    }
</style>
<div class="HMAUDViewerMente">
    <div class="HMAUDViewerMente HMAUD-content">
        <!-- 検索条件 -->

        <div class="HMAUDViewerMente HMS-button-pane">
            <button class="HMAUDViewerMente btnUpdata button Enter Tab" tabindex="0">
                更新
            </button>
            <button class="HMAUDViewerMente btnRetrun button Enter Tab" tabindex="1">
                キャンセル
            </button>
            <button class="HMAUDViewerMente btnRowAdd button Enter Tab" tabindex="3">
                行追加
            </button>
            <button class="HMAUDViewerMente btnRowDel button Enter Tab" tabindex="4">
                行削除
            </button>
        </div>

        <div class="HMAUDViewerMente pnlList">
            <!-- jqgrid -->
            <table id="HMAUDViewerMente_tblMain"></table>
        </div>
    </div>
</div>