<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE300HSYAINMSTList/HMTVE300HSYAINMSTList"));
?>
<style type="text/css">
    /*灰色背景*/
    .HMTVE300HSYAINMSTList.CELL_BORDER {
        background-color: #BABEC1;
        border: solid 1px black;
    }

    .HMTVE300HSYAINMSTList.lblDispose2 {
        width: 232px !important;
    }

    /*折行*/
    .HMTVE300HSYAINMSTList.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE300HSYAINMSTList fieldset>div {
        padding: 1px 1px 5px 1px;
    }
</style>
<div class="HMTVE300HSYAINMSTList">
    <div class="HMTVE300HSYAINMSTList HMTVE-content HMTVE-content-fixed-width">
        <fieldset class="HMTVE300HSYAINMSTList">
            <!-- 検索条件 -->
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE300HSYAINMSTList lblDispose  LBL_TITLE_STD9 lbl-sky-L' for=""> 部署</label>
                <input type="text" class="HMTVE300HSYAINMSTList txtDispose Enter Tab" maxlength="3" tabindex="1" />
                <input type="text" class="HMTVE300HSYAINMSTList lblDispose2 CELL_BORDER LBL_MSG_STD10  Enter Tab"
                    disabled="disabled" />
            </div>
            <div>
                <label class='HMTVE300HSYAINMSTList lblNumber LBL_TITLE_STD9 lbl-sky-L' for=""> 社員№</label>
                <input id="txtNumber" type="text" class="HMTVE300HSYAINMSTList txtNumber Enter Tab" maxlength="5"
                    tabindex="2" />
            </div>
            <div>
                <label class='HMTVE300HSYAINMSTList lblName LBL_TITLE_STD9 lbl-sky-L' for=""> 社員名カナ</label>
                <input id="txtName" type="text" class="HMTVE300HSYAINMSTList txtName Enter Tab" maxlength="40"
                    tabindex="3" />
                <div class="HMTVE300HSYAINMSTList HMS-button-set">
                    <button class="HMTVE300HSYAINMSTList BTN_STD100 btnAdd Enter Tab" tabindex="5">
                        追加
                    </button>
                    <button class="HMTVE300HSYAINMSTList  BTN_STD100 btnSearch Enter Tab" tabindex="4">
                        検索
                    </button>
                </div>
            </div>
        </fieldset>
        <div class="HMTVE300HSYAINMSTList pnlList">
            <table id="HMTVE300HSYAINMSTListMain"></table>
            <div id="HMTVE300HSYAINMSTList_pager"></div>
        </div>
    </div>
</div>