<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDKyotenMente/HMAUDKyotenMente'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    /*20230309 CAI UPD S*/
    /*.HMAUDKyotenMente.btnRowAdd*/
    .HMAUDKyotenMente.btnRowAdd {
        margin-left: 80px;
    }

    /*20230309 CAI UPD E*/
    .HMAUDKyotenMente.courPeriod {
        width: 175px;
        height: 0px;
        margin-left: 5px;
    }

    /*折行*/
    #HMAUDKyotenMente_tblMain tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    #HMAUDKyotenMente_tblMain select {
        width: 90%
    }

    #HMAUDKyotenMente_tblMain textarea {
        width: 95%
    }

    .HMAUDKyotenMente.btnSearch {
        float: unset !important;
        margin-top: 9px !important;
    }

    .HMAUDKyotenMente button[disabled] {
        background-color: #C3C3C3 !important;
    }
</style>
<div class="HMAUDKyotenMente">
    <div class="HMAUDKyotenMente HMAUD-content">
        <!-- 検索条件 -->
        <div class="HMAUDKyotenMente HMS-button-pane">
            <!-- 20230308 CAI UPD S -->
            <button class="HMAUDKyotenMente btnUpdata button Enter Tab" tabindex="0">
                更新
            </button>
            <button class="HMAUDKyotenMente btnRetrun button Enter Tab" tabindex="1">
                キャンセル
            </button>
            <button class="HMAUDKyotenMente btnRowAdd button Enter Tab" tabindex="3">
                行追加
            </button>
            <button class="HMAUDKyotenMente btnRowDel button Enter Tab" tabindex="4">
                行削除
            </button>
            <!-- <button class="HMAUDKyotenMente btnUpdata button Enter Tab" tabindex="0">
                更新
            </button>
            <button class="HMAUDKyotenMente btnRetrun button Enter Tab" tabindex="1">
                キャンセル
            </button> -->
            <!-- 20230308 CAI UPD E -->
        </div>

        <div class="HMAUDKyotenMente pnlList">
            <!-- jqgrid -->
            <table id="HMAUDKyotenMente_tblMain"></table>

        </div>
        <div id="HMAUDKyotenMente_dialog" class="HMAUDKyotenMente dialogsHMAUDKyotenMenteSetting"></div>
    </div>
</div>