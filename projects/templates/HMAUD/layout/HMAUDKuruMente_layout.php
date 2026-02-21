<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDKuruMente/HMAUDKuruMente'));
?>
<style type="text/css">
    .HMAUDKuruMente .buttonClass {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .HMAUDKuruMente .btnLogin {
        margin-right: 20px;
        width: 80px;
    }

    .HMAUDKuruMente .numeric {
        width: 90% !important;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDKuruMente">
    <div class="HMAUDKuruMente HMAUD-content">
        <div class="HMAUDKuruMente buttonClass">
            <button class="HMAUDKuruMente btnLogin button Enter Tab" tabindex="1">
                更新
            </button>
            <button class="HMAUDKuruMente btnCancel button Enter Tab" tabindex="2">
                キャンセル
            </button>
        </div>
        <!-- jqgrid -->
        <div class="HMAUDKuruMente pnlList">
            <table id="HMAUDKuruMente_tblMain"></table>
        </div>
    </div>
</div>