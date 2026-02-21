<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE040InputDataS/HMTVE040InputDataS"));
?>
<style type="text/css">
    .HMTVE040InputDataS.btnView {
        float: left;
        min-width: 80px;
        margin: 1px;
    }

    .HMTVE040InputDataS fieldset>div {
        padding: 1px 1px 5px 1px;
    }

    .HMTVE040InputDataS.pnlList {
        width: 176px;
    }

    .CELL_SUM_C input {
        width: 88% !important;
    }

    .HMTVE040InputDataS.pnlList button {
        min-width: 80px;
    }

    .HMTVE040InputDataS fieldset {
        min-width: 485px;
        padding: 0px 2px 0px;
    }

    .HMTVE040InputDataS .ui-jqgrid.ui-widget.ui-widget-content.ui-corner-all {
        padding: 1px 2px 1px;
    }

    .HMTVE040InputDataS .LBL_TITLE_STD9 {
        width: 105px;
    }

    .HMTVE040InputDataS.ddlExhibitDay {
        width: 110px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE040InputDataS">
    <div class="HMTVE040InputDataS HMTVE-content HMTVE-content-fixed-width">
        <div>
            <table class="HMTVE040InputDataS containTable" border="0">
                <tr class="HMTVE040InputDataS">
                    <td valign="top"><!-- 検索条件 -->
                        <fieldset>
                            <legend>
                                <b><span>検索条件</span></b>
                            </legend>
                            <div>
                                <label for="" class='HMTVE040InputDataS LBL_TITLE_STD9 lbl-sky-L'> 展示会開催期間 </label>
                                <input type="text" class="HMTVE040InputDataS lblExhibitTermFrom" readonly="true"
                                    tabindex="0" />
                                <label for=""> ～ </label>
                                <input type="text" class="HMTVE040InputDataS lblExhibitTermTo" readonly="true"
                                    tabindex="1" />
                                <button class="HMTVE040InputDataS btnETSearch button Enter Tab" tabindex="2">
                                    展示会検索
                                </button>
                            </div>
                            <div>
                                <label for="" class='HMTVE040InputDataS LBL_TITLE_STD9 lbl-sky-L'> 展示会開催日 </label>

                                <select class="HMTVE040InputDataS ddlExhibitDay Enter Tab" tabindex="3"></select>
                            </div>
                            <div>
                                <button class="HMTVE040InputDataS btnView button Enter Tab" tabindex="4">
                                    表　示
                                </button>
                            </div>
                        </fieldset>
                    </td>
                    <td class="HMTVE040InputDataS tableTd"><!-- jqgrid -->
                        <table id="HMTVE040InputDataS_tblMain"></table>
                    </td>
                    <td valign="top" class="HMTVE040InputDataS buttonTd">
                        <div class="HMTVE040InputDataS pnlList HMS-button-set">
                            <button class="HMTVE040InputDataS btnUpdate button Enter Tab" tabindex="5">
                                更　新
                            </button>
                            <button class="HMTVE040InputDataS btnDelete button Enter Tab" tabindex="6">
                                削　除
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>