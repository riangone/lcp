<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmAkauntoIchiranSansho'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmAkauntoIchiranSansho' id="FrmAkauntoIchiranSansho">
    <div class='FrmAkauntoIchiranSansho center FrmAkauntoIchiranSansho-content' style="width:1030px;">
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <table class="FrmAkauntoIchiranSansho center tbl" border='0' style="margin:10px 20px;">
                    <tr>
                        <td> 店舗 </td>
                        <td>
                            <select class="FrmAkauntoIchiranSansho Tab Enter txtTenpo" style="width: 100%">
                                <option value=""></option>
                            </select>
                        </td>
                        <td style="padding-left:10px;"> 発行日 </td>
                        <td>
                            <input class="FrmAkauntoIchiranSansho Tab Enter txtDTFrom" type='text' maxlength="10" />
                        </td>
                        <td align="center" width="30px" align="center"><span class="FrmAkauntoIchiranSansho Label1"> ～
                            </span></td>
                        <td>
                            <input class="FrmAkauntoIchiranSansho Tab Enter txtDTTo" type='text' maxlength="10" />
                        </td>
                        <td style="padding-left:10px;">
                            <div class="HMS-button-pane" style="margin-top:0px;">
                                <button class="FrmAkauntoIchiranSansho Tab Enter btnSearch">
                                    検索
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td> お客様名 </td>
                        <td>
                            <input class="FrmAkauntoIchiranSansho Tab Enter txtCusNM" type='text' maxlength="30" />
                        </td>
                        <td style="padding-left:10px;"> お客様No </td>
                        <td>
                            <input class="FrmAkauntoIchiranSansho Tab Enter txtCusNo" type='text' maxlength="10" />
                        </td>
                    </tr>
                </table>
        </fieldset>
        <div class="HMS-button-pane" style="margin-top:10px;">
            <button class="FrmAkauntoIchiranSansho Tab Enter btnIssue">
                新規発行
            </button>
            <button class="FrmAkauntoIchiranSansho Tab Enter btnPDFoutput">
                PDF出力
            </button>
        </div>
        <div style="margin:10px 5px">
            <table id="FrmAkauntoIchiranSansho_jqgrid"></table>
            <div id="divFrmAkauntoIchiranSansho_pager"></div>
        </div>
        <div id="FrmAkauntoIchiranSanshodialog">
        </div>
    </div>
</div>