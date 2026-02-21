<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmBusyoMst/FrmBusyoMst"));
?>
<style type="text/css">
    .FrmBusyoMst .ui-jqgrid .ui-jqgrid-pager .ui-paging-pager,
    .ui-jqgrid .ui-jqgrid-toppager .ui-paging-pager {
        height: 0% !important;
    }
</style>
<div class='FrmBusyoMst'>
    <div class='FrmBusyoMst  R4-content'>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table width=100% border=0>
                <tr>
                    <td width=50%>
                        <table border=0>
                            <tr>
                                <td>
                                    <label style='border:solid 1px;background-color:#B0E2FF' for="">
                                        部署コード
                                    </label>
                                </td>
                                <td>
                                    <input type='text' class='FrmBusyoMst txtBusyoCD Enter Tab' tabindex="10"
                                        style='width:30px;margin-left:5px;' maxlength="6" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label style='border:solid 1px;background-color:#B0E2FF' for="">
                                        部署名カナ
                                    </label>
                                </td>
                                <td>
                                    <input type='text' class='FrmBusyoMst txtBusyoKN Enter Tab' tabindex="8"
                                        style='width:200px;margin-left:5px;' maxlength="6" />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" width=50%>
                        <button style='margin-right:20px;' class='FrmBusyoMst cmdSearch Enter Tab' tabindex="3">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <table id="FrmBusyoMst_sprList" class='FrmBusyoMst FrmBusyoMst_sprList Enter Tab'>
        </table>
        <div id='FrmBusyoMst_sprList_pager'>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmBusyoMst cmdInsert Enter Tab' tabindex="7">
                    新規登録
                </button>
                <button class='FrmBusyoMst cmdUpdate Enter Tab' tabindex="5">
                    修正
                </button>
                <button class='FrmBusyoMst cmdDelete Enter Tab' tabindex="1">
                    削除
                </button>
            </div>
        </div>
    </div>
</div>
<div id="FrmBusyoMst_subFormDialog">
</div>
