<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE140PublicityOrderTotal/HMTVE140PublicityOrderTotal'));
?>
<style type="text/css">
    /*展示会開催年月*/
    .HMTVE140PublicityOrderTotal.lblExhibitTitle1 {
        width: 105px;
    }

    /*展示会開催年月 select*/
    .HMTVE140PublicityOrderTotal.ddlYear,
    .HMTVE140PublicityOrderTotal.ddlMonth {
        width: 65px;
    }

    /*件数*/
    .HMTVE140PublicityOrderTotal.lblItemnum {
        background-color: #FFFFFF;
        text-align: right;
        height: 21px;
        line-height: 21px;
        margin-left: 5px;
    }

    .HMTVE140PublicityOrderTotal.lblItem {
        float: left;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMTVE140PublicityOrderTotal.lblItemnum {
            height: 16px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE140PublicityOrderTotal">
    <div class="HMTVE140PublicityOrderTotal HMTVE-content HMTVE-content-fixed-width">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE140PublicityOrderTotal tblMain">
                <label class='HMTVE140PublicityOrderTotal lblExhibitTitle1 lbl-sky-L' for=""> 展示会開催年月 </label>
                <select class="HMTVE140PublicityOrderTotal ddlYear Enter Tab" tabindex="1"></select>
                <label class="HMTVE140PublicityOrderTotal Label1" for=""> 年 </label>
                <select class="HMTVE140PublicityOrderTotal ddlMonth Enter Tab" tabindex="2"></select>
                <label class="HMTVE140PublicityOrderTotal Label2" for=""> 月分 </label>
                <button class="HMTVE140PublicityOrderTotal btnETSearch Button Enter Tab" tabindex="3">
                    表示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE140PublicityOrderTotal tblSubMain">
            <table id="HMTVE140PublicityOrderTotal_tblSubMain"></table>
        </div>
        <div>
            <label class='HMTVE140PublicityOrderTotal lblItemnum lbl-sky-L' for=""> </label>
            <label class='HMTVE140PublicityOrderTotal lblItem lbl-sky-L' for=""> 件数 </label>
        </div>
        <div class="HMTVE140PublicityOrderTotal HMS-button-pane">
            <button class="HMTVE140PublicityOrderTotal btnExcelOut Button Enter Tab" tabindex="4">
                Excel出力
            </button>
            <button class="HMTVE140PublicityOrderTotal btnLock Button Enter Tab" tabindex="5">
                ロック解除
            </button>
        </div>
    </div>
</div>