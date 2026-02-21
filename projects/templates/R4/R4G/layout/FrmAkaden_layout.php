<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmAkaden/FrmAkaden'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmAkaden .ui-jqgrid .ui-jqgrid-pager .ui-paging-pager,
    .ui-jqgrid .ui-jqgrid-toppager .ui-paging-pager {
        height: 0% !important;
    }
</style>
<div class='FrmAkaden '>
    <div class='R4-content'>
        <fieldset class="FrmAkaden center GroupBox1 " style='width: 800px;height:50px;'>
            <legend>
                <b><span class='FrmAkaden_GroupBox1_searchTitle_css'>検索条件</span></b>
            </legend>
            <div class='FrmAkaden searchArea  FrmAkaden_searchArea_css '>
                <table class='FrmAkaden searchArea searchTable FrmAkaden_searchArea_searchTable_css' border=0
                    cellspacing="0">
                    <tr>
                        <td>
                            <div class='FrmAkaden_searchArea_div1_css'>
                                <table>
                                    <tr>
                                        <td>
                                            <label for="">
                                                注文書番号
                                            </label>
                                            <input type='text' name="FrmAkaden_inputＴext_CMNNO"
                                                class='FrmAkaden inputＴext_CMNNO  Enter Tab ' maxlength="10"
                                                tabindex="0" />
                                        </td>
                                        <td>
                                            <p class='FrmAkaden' style='visibility: hidden;display:none;'>
                                                <label style='margin-left:30px;' for="">
                                                    顧客名カナ
                                                </label>
                                                <input type='text' class='FrmAkaden inputText_SiyFgn '
                                                    name="FrmAkaden_inputText_SiyFgn" />
                                                <label style='margin-left:30px;' for="">
                                                    社員番号
                                                </label>
                                                <input type='text' class='FrmAkaden inputText_EmpNO '
                                                    name="FrmAkaden_inputText_EmpNO" />
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </td>
                        <td align='left'>
                            <button class='FrmAkaden button_search Enter Tab ' tabindex="1" style='margin-left:50px;'>
                                検索
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <div class='FrmAkaden listArea R4-content FrmAkaden_listArea_css'>
            <table id="FrmAkaden_sprList" cellspacing="0" border=0 width=100%>
            </table>
            <div id="divFrmAkaden_pager">
            </div>
        </div>
        <!--<div class='FrmAkaden footer FrmAkaden_fosoter'  >-->
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmAkaden button_print Enter Tab' tabindex="2">
                    発行
                </button>
            </div>
        </div>
    </div>
</div>
</div>
