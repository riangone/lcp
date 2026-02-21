<!DOCTYPE html>

<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmFDHokanSelect/FrmFDHokanSelect'));
echo $this->Html->css(array('R4/R4G/FrmFDHokanSelect/FrmFDHokanSelect'));
//echo '<script type="application/xml" src="/gdmz/cake/js/SmpkameJs.js"></script>';
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<div class='frmFDHokanSelect'>
    <div class='R4-content'>
        <div class='frmFDHokanSelect searchArea'>
            <fieldset class="frmFDHokanSelect center GroupBox1 " style='width: 800px;height:100px;'>
                <legend>
                    <b><span class='frmFDHokanSelect_GroupBox1_searchTitle_css'>検索条件</span></b>
                </legend>
                <table>
                    <tr height="40px">
                        <td>
                            <label class="frmFDHokanSelect Label1" for="">
                                登録予定日
                            </label>
                        </td>
                        <td>
                            <input class="frmFDHokanSelect TourokuFrom_input Enter Tab"
                                name="frmFDHokanSelect_TourokuFrom_input" />
                        </td>
                        <td>
                            <label class="frmFDHokanSelect Label3 " for="">
                                ～
                            </label>
                        </td>
                        <td>
                            <input class="frmFDHokanSelect TourokuTo_input Enter Tab"
                                name="frmFDHokanSelect_TourokuTo_input" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="frmFDHokanSelect Label4" for="">
                                FD未作成データのみ抽出
                                <input class="frmFDHokanSelect Misakusei_inputCheck Tab"
                                    name="frmFDHokanSelect_Misakusei_inputCheck" type="checkbox" />
                            </label>
                        </td>
                        <td>

                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <button class="frmFDHokanSelect searchButton Tab">
                                検索
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div class='frmFDHokanSelect listArea frmFDHokanSelect_listArea_css'>
            <table id='FrmFDHokanSelect_sprList' class='frmFDHokanSelect listArea listTable ' border="0">
            </table>
            <div id='divFrmFDHokanSelect_pager'>
            </div>
        </div>

        <!--<div class='frmFDHokanSelect_footer_css' >-->
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='frmFDHokanSelect button_action Tab'>
                    修正
                </button>
            </div>
        </div>
        <!--</div>-->
    </div>
</div>
<div class='FrmFDHokanSelect subDialog' id='FrmFDHokanSelect_subDialog'>
</div>
