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
echo $this->Html->script(array('HMTVE/HMTVE070TotalKHonbu/HMTVE070TotalKHonbu'));
?>
<style type="text/css">
    .HMTVE070TotalKHonbu.HMTVE-content {
        overflow-y: hidden;
    }

    .HMTVE070TotalKHonbu.btnETSearch {
        float: none;
    }

    .HMTVE070TotalKHonbu fieldset {
        padding-bottom: 7.3px
    }

    @media screen and (max-height: 750px) {
        .HMTVE070TotalKHonbu .ui-jqgrid .ui-jqgrid-view .ui-state-default.ui-jqgrid-hdiv {
            font-size: 13px
        }
    }

    .HMTVE070TotalKHonbu.lbl-sky-L {
        width: 102px;
    }

    .HMTVE070TotalKHonbu .ui-jqgrid .ui-jqgrid-sortable {
        cursor: auto;
    }

    /* 20240326 LHB INS S */
    .HMTVE070TotalKHonbu .ui-th-ltr,
    .ui-jqgrid .ui-jqgrid-htable th.ui-th-ltr {
        font-size: 10px;
    }

    /* 20240326 LHB INS E */
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE070TotalKHonbu">
    <div class="HMTVE070TotalKHonbu HMTVE-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE070TotalKHonbu lblExhibitTitle1 lbl-sky-L' for=""> 展示会開催期間 </label>
                <input type="text" class="HMTVE070TotalKHonbu lblExhibitTermStart" readonly="true" />
                <label for=""> ～ </label>
                <input type="text" class="HMTVE070TotalKHonbu lblExhibitTermEnd" readonly="true" />
                <button class="HMTVE070TotalKHonbu btnETSearch button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <button class="HMTVE070TotalKHonbu btnLock button Enter Tab" tabindex="6">
                    ロック解除
                </button>
                <button class="HMTVE070TotalKHonbu btnOutputHITNET button Enter Tab" tabindex="5">
                    HITNET用Excel出力
                </button>
                <button class="HMTVE070TotalKHonbu btnCSVOut button Enter Tab" tabindex="4">
                    CSV出力
                </button>
                <button class="HMTVE070TotalKHonbu btnExcelOut button Enter Tab" tabindex="3">
                    Excel出力
                </button>
                <button class="HMTVE070TotalKHonbu btnView button Enter Tab" tabindex="2">
                    表　示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE070TotalKHonbu pnlList">
            <table id="HMTVE070TotalKHonbu_tblMain"></table>
        </div>
    </div>
</div>