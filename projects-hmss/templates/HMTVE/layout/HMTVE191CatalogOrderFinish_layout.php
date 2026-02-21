<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE191CatalogOrderFinish/HMTVE191CatalogOrderFinish'));
?>
<style type="text/css">
    .HMTVE191CatalogOrderFinish.bl-gray-L {
        background-color: #C0C0C0;
    }

    .HMTVE191CatalogOrderFinish.HMS-button-pane {
        margin-top: 20px;
    }

    .HMTVE191CatalogOrderFinish.lblBusyoNM {
        width: 300px !important;
    }

    .HMTVE191CatalogOrderFinish.lblOrderNum {
        text-align: right;
    }

    .HMTVE191CatalogOrderFinish.lblOrderDate,
    .HMTVE191CatalogOrderFinish.lblOrderNO,
    .HMTVE191CatalogOrderFinish.lblOrderNum,
    {
    width: 180px !important;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE191CatalogOrderFinish body">
    <div class="HMTVE191CatalogOrderFinish HMTVE-content">
        <div>
            <span>下記内容で注文を受け付けました</span>
        </div>
        <div>
            <label class='HMTVE191CatalogOrderFinish Label2 lbl-yellow-L' for=""> 店舗名 </label>
            <label class='HMTVE191CatalogOrderFinish lblBusyoNM lbl-yellow-L bl-gray-L' for=""> &nbsp; </label>
        </div>
        <div>
            <label class='HMTVE191CatalogOrderFinish Label4 lbl-yellow-L' for=""> 注文日 </label>
            <label class='HMTVE191CatalogOrderFinish lblOrderDate lbl-yellow-L bl-gray-L' for=""> &nbsp; </label>
        </div>
        <div>
            <label class='HMTVE191CatalogOrderFinish Label6 lbl-yellow-L' for=""> 注文番号 </label>
            <label class='HMTVE191CatalogOrderFinish lblOrderNO lbl-yellow-L bl-gray-L' for=""> &nbsp; </label>
        </div>
        <div>
            <label class='HMTVE191CatalogOrderFinish Label7 lbl-yellow-L' for=""> 注文金額合計 </label>
            <label class='HMTVE191CatalogOrderFinish lblOrderNum lbl-yellow-L bl-gray-L' for=""> &nbsp;</label>
        </div>
        <div class="HMTVE191CatalogOrderFinish HMS-button-pane">
            <button class="HMTVE191CatalogOrderFinish btnRirekiKakunin Enter Tab" tabindex="1">
                注文履歴確認画面へ
            </button>
        </div>
    </div>
</div>