<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE050TotalS/HMTVE050TotalS"));
?>
<style type="text/css">
    .HMTVE050TotalS.btnETSearch {
        float: none;
    }

    .HMTVE050TotalS.lbl-sky-L {
        width: 102px;
    }

    .HMTVE050TotalS.lblExhibitTitle2 {
        margin-left: 15px
    }

    .HMTVE050TotalS.ddlExhibitDay {
        width: 109px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE050TotalS">
    <div class="HMTVE050TotalS HMTVE-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMTVE050TotalS lblExhibitTitle lbl-sky-L'> 展示会開催期間 </label>
                <input type="text" class="HMTVE050TotalS lblExhibitTerm" readonly="true" />
                <label for=""> ～ </label>
                <input type="text" class="HMTVE050TotalS lblExhibitTerm2" readonly="true" />
                <button class="HMTVE050TotalS btnETSearch button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <label for="" class='HMTVE050TotalS lblExhibitTitle2 lbl-sky-L'> 展示会開催日 </label>
                <select class="HMTVE050TotalS ddlExhibitDay Enter Tab" tabindex="2"></select>
                <button class="HMTVE050TotalS btnUnLock button Enter Tab" tabindex="5">
                    ロック解除
                </button>
                <button class="HMTVE050TotalS btnOutputHITNET button Enter Tab" tabindex="4">
                    HITNET用Excel出力
                </button>
                <button class="HMTVE050TotalS btnView button Enter Tab" tabindex="3">
                    表　示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE050TotalS pnlList">
            <table id="HMTVE050TotalS_tblMain"></table>
        </div>
    </div>
</div>