<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDKansaJinMente/HMAUDKansaJinMente'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .HMAUDKansaJinMente.btnSearch {
        margin-left: 350px;
    }

    #HMAUDKansaJinMente_tblMain tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    .HMAUDKansaJinMente button[disabled] {
        background-color: #C3C3C3 !important;
    }
</style>
<div class="HMAUDKansaJinMente">
    <div class="HMAUDKansaJinMente HMAUD-content">
        <!-- 検索条件 -->
        <div class="HMAUDKansaJinMente HMS-button-pane">
            <button class="HMAUDKansaJinMente btnRowAdd button Enter Tab" tabindex="1">
                行追加
            </button>
            <button class="HMAUDKansaJinMente btnUpdata button Enter Tab" tabindex="2">
                更新
            </button>
            <button class="HMAUDKansaJinMente btnSearch button Enter Tab" tabindex="3">
                最新情報を表示
            </button>
        </div>

        <div class="HMAUDKansaJinMente pnlList">
            <table id="HMAUDKansaJinMente_tblMain"></table>
        </div>
    </div>
</div>