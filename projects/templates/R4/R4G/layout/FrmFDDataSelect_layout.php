<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('R4/R4G/FrmFDDataSelect/FrmFDDataSelect'));
echo $this->Html->css('R4/R4G/FrmFDDataSelect/FrmFDDataSelect');
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="FrmFDDataSelect">
    <div class="FrmFDDataSelect R4-content">
        <div class="FrmFDDataSelect center">
            <fieldset class="FrmFDDataSelect center GroupBox1" style="height: 13.7vh;width: 800px">
                <legend>
                    <b><span style="font-size: 10pt">検索条件</span></b>
                </legend>
                <table style="margin-top: 5px">
                    <tr height="40px">
                        <td>
                            <label class="FrmFDDataSelect Label1" for="">
                                登録予定日
                            </label>
                        </td>
                        <td>
                            <input class="FrmFDDataSelect cboTourokuFrom Enter Tab"
                                name="FrmFDDataSelect_cboTourokuFrom" />
                        </td>
                        <td>
                            <label class="FrmFDDataSelect Label3" for="">
                                ～
                            </label>
                        </td>
                        <td>
                            <input class="FrmFDDataSelect cboTourokuTo Enter Tab" name="FrmFDDataSelect_cboTourokuTo" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmFDDataSelect Label4" for="">
                                FD未作成データのみ抽出
                            </label>
                        </td>
                        <td>
                            <input class="FrmFDDataSelect chkMisakusei Tab" type="checkbox"
                                name="FrmFDDataSelect_chkMisakusei" />
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <button class="FrmFDDataSelect cmdSearch Tab">
                                検索
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div class="FrmFDDataSelect center2" style="margin-top: 5px">
            <table id="FrmFDDataSelect_sprList">
            </table>
            <div id="divFrmFDDataSelect_pager">
            </div>
        </div>
        <!-- <div class='FrmFDDataSelect footer'  style='float:right'> -->
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmFDDataSelect cmdAction'>
                    更新
                </button>
            </div>
        </div>
        <!-- </div> -->
    </div>
</div>
