<!-- /**
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 									担当
 * YYYYMMDD           #ID                                    XXXXXX                               								  FCSDL
 * 20240326    受入検証.xlsx NO4     見出しの高さを全体的に小さくして、データ行ができるだけ多く表示されるようにしてほしい             		LHB
 * -----------------------------------------------------------------------------------------------------------------------------------------
 */ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE060TotalKShop/HMTVE060TotalKShop'));
?>
<style type="text/css">
    .HMTVE060TotalKShop.HMTVE-content {
        overflow-y: hidden;
    }

    .HMTVE060TotalKShop.btnETSearch {
        float: none;
    }

    @media screen and (max-height: 750px) {
        .HMTVE060TotalKShop .ui-jqgrid .ui-jqgrid-view .ui-state-default.ui-jqgrid-hdiv {
            font-size: 13px
        }
    }

    .HMTVE060TotalKShop.lblExhibitTitle2 {
        margin-left: 15px
    }

    .HMTVE060TotalKShop.ddlExhibitDay {
        width: 120px
    }

    .HMTVE060TotalKShop.lblTenpoNM {
        width: 272px
    }

    .HMTVE060TotalKShop.lblTenpoCD {
        display: none
    }

    .HMTVE060TotalKShop.paddingSearchdiv {
        margin-top: 7px
    }

    .HMTVE060TotalKShop.lbl-sky-L {
        width: 102px;
    }

    /* 20240326 LHB INS S */
    .HMTVE060TotalKShop .ui-th-ltr,
    .ui-jqgrid .ui-jqgrid-htable th.ui-th-ltr {
        font-size: 10px;
    }

    /* 20240326 LHB INS E */
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE060TotalKShop">
    <div class="HMTVE060TotalKShop HMTVE-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE060TotalKShop lblExhibitTitle1 lbl-sky-L' for=""> 展示会開催期間 </label>
                <input type="text" class="HMTVE060TotalKShop lblExhibitTermFrom" readonly="true" />
                <label for=""> ～ </label>
                <input type="text" class="HMTVE060TotalKShop lblExhibitTermTo" readonly="true" />
                <button class="HMTVE060TotalKShop btnETSearch button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <label class='HMTVE060TotalKShop lblExhibitTitle2 lbl-sky-L' for=""> 展示会開催日 </label>
                <select class="HMTVE060TotalKShop ddlExhibitDay Tab Enter" tabindex="2"></select>
                <button class="HMTVE060TotalKShop btnReturn button Enter Tab" tabindex="5">
                    戻　る
                </button>
                <button class="HMTVE060TotalKShop btnPrintOut button Enter Tab" tabindex="4">
                    印　刷
                </button>
                <button class="HMTVE060TotalKShop btnView button Enter Tab" tabindex="3">
                    表　示
                </button>
            </div>
            <div class='HMTVE060TotalKShop paddingSearchdiv'>
                <label class='HMTVE060TotalKShop lblExhibitTitle3 lbl-sky-L' for=""> 店舗名 </label>
                <input type="text" class="HMTVE060TotalKShop lblTenpoNM" readonly="true" />
                <input type="text" class="HMTVE060TotalKShop lblTenpoCD" />
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE060TotalKShop pnlList">
            <table id="HMTVE060TotalKShop_tblMain"></table>
        </div>
    </div>
</div>