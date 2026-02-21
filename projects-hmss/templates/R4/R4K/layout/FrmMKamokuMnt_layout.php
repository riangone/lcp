<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmMKamokuMnt/FrmMKamokuMnt'));
?>
<!-- 20210708 YIN INS S -->
<style type="text/css">
    .FrmMKamokuMnt.cmdChange {
        float: left;
        left: -1%;
    }

    .FrmMKamokuMnt .ui-jqgrid .ui-jqgrid-pager .ui-paging-pager,
    .ui-jqgrid .ui-jqgrid-toppager .ui-paging-pager {
        height: 0% !important;
    }
</style>
<!-- 20210708 YIN INS E -->
<div class='FrmMKamokuMnt R4-content'>
    <div style="margin: 10px">
        <label class='FrmMKamokuMnt lbl-blue' for="">
            科目コード
        </label>
        <input class='FrmMKamokuMnt txtKamokuCD Tab Enter' type='text' maxlength="5" />
        <button class='FrmMKamokuMnt cmdSearch Tab Enter'>
            検索
        </button>
    </div>
    <div class='FrmMKamokuMnt listArea'>
        <table>
            <tr>
                <td>
                    <table id='FrmMKamokuMnt_sprList' class='FrmMKamokuMnt Enter'>
                    </table>
                    <div id='FrmMKamokuMnt_pager'>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="HMS-button-pane">
                        <!-- 20210708 YIN INS S -->
                        <button class='FrmMKamokuMnt cmdChange Tab Enter'>
                            条件変更
                        </button>
                        <!-- 20210708 YIN INS E -->
                        <div class='HMS-button-set'>
                            <button class='FrmMKamokuMnt cmdAdd Tab Enter'>
                                新規追加
                            </button>
                            <button class='FrmMKamokuMnt cmdAction Tab Enter'>
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
