<!--
/**
* 説明：
*
*
* @author FCS
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20150922           #2162                     BUG                            LI
* --------------------------------------------------------------------------------------------
*/

-->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmChumonCSV/FrmChumonCSV"));
echo $this->fetch('meta');
echo $this->fetch('script');
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmChumonCSV.GroupBox23 {
            height: 252px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmChumonCSV'>
    <div class='FrmChumonCSV content R4-content' style="width: 1113px">
        <!--抽出条件-->
        <div class='FrmChumonCSV GroupBox1'>
            <fieldset>
                <legend>
                    <span style="font-size: 10pt">抽出条件</span>
                </legend>
                <table>
                    <tr>
                        <td>
                            <label class="FrmChumonCSV GroupBox1 Label4" for="">
                                処理年月
                            </label>
                        </td>
                        <td>
                            <!-- 20150922 li UPD S. -->
                            <!-- <input class="FrmChumonCSV GroupBox1 cboUCNO Tab Enter"  style="width: 100px" /> -->
                            <input class="FrmChumonCSV GroupBox1 cboUCNO Tab Enter" style="width: 100px"
                                maxlength="6" />
                            <!-- 20150922 li UPD E. -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmChumonCSV GroupBox1 Label6" for="">
                                更新年月日
                            </label>
                        </td>
                        <td>
                            <input class="FrmChumonCSV GroupBox1 cboDateFrom Tab Enter" style="width: 140px" />
                        </td>
                        <td>
                            <label class="FrmChumonCSV GroupBox1 Label3" for="">
                                ～
                            </label>

                        </td>
                        <td>
                            <input class="FrmChumonCSV GroupBox1 cboDateTo Tab Enter" style="width: 140px" />
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>
                            <label class="FrmChumonCSV GroupBox1 Label1" for="">
                                新中区分
                            </label>
                        </td>
                        <td>
                            <input class="FrmChumonCSV GroupBox1 rdoNew Enter Tab" type="radio"
                                name='FrmChumonCSV_radio' value="0" />
                            新車
                        </td>
                        <td>
                            <input class="FrmChumonCSV GroupBox1 rdoUsed Enter Tab" type="radio"
                                name='FrmChumonCSV_radio' value="1" />
                            中古車
                        </td>
                        <td>
                            <input class="FrmChumonCSV GroupBox1 rdoAll Enter Tab" type="radio"
                                name='FrmChumonCSV_radio' value="2" />
                            全て
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="height: 15px">
        </div>
        <div class='FrmChumonCSV GroupBox23' style='width:99%;margin-left: 0px;'>
            <table width="99%" cellspacing="0" border="0">
                <tr>
                    <td width="45%" valign="top">
                        <div class="FrmChumonCSV GroupBox2">
                            <fieldset>
                                <legend>
                                    <span style="font-size: 10pt">処理件数</span>
                                </legend>
                                <table>
                                    <tr style="height: 15px">
                                    </tr>
                                    <tr>
                                        <td width="8px">

                                        </td>
                                        <td style="width: 220px">
                                            <label class="FrmChumonCSV GroupBox2 lblTitleChu" for="">
                                                新車売上
                                            </label>
                                        </td>
                                        <td align="right" style="width: 80px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntNew" for="">
                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntNewCenter" for="">

                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntNewA" for="">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr style="height: 10px">
                                    </tr>
                                    <tr>
                                        <td width="8px">

                                        </td>
                                        <td style="width: 220px">
                                            <label class="FrmChumonCSV GroupBox2 lblTitleChuDel" for="">
                                                中古車売上
                                            </label>
                                        </td>
                                        <td align="right" style="width: 80px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntUsed" for="">
                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntUsedCenter" for="">

                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntUsedA" for="">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr style="height: 10px">
                                    </tr>
                                    <tr>
                                        <td width="8px">

                                        </td>
                                        <td style="width: 220px">
                                            <label class="FrmChumonCSV GroupBox2 lblTitlePDB" for="">
                                                新車条件変更
                                            </label>
                                        </td>
                                        <td align="right" style="width: 80px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntNewChg" for="">
                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntNewChgCenter" for="">

                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntNewChgA" for="">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr style="height: 10px">
                                    </tr>
                                    <tr>
                                        <td width="8px">

                                        </td>
                                        <td style="width: 220px">
                                            <label class="FrmChumonCSV GroupBox2 Label8" for="">
                                                中古車条件変更
                                            </label>
                                        </td>
                                        <td align="right" style="width: 80px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntUsedChg" for="">
                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntUsedChgCenter" for="">

                                            </label>
                                        </td>
                                        <td align="right" style="width: 30px">
                                            <label class="FrmChumonCSV GroupBox2 lblCntUsedChgA" for="">
                                            </label>
                                        </td>
                                    </tr>
                                    <tr style="height: 70px">
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </td>
                    <td width="2%">
                    </td>
                    <td width="51%">
                        <div class="FrmChumonCSV GroupBox3">
                            <fieldset>
                                <legend>
                                    <span style="font-size: 10pt">原価マスタ未登録ﾃﾞｰﾀ</span>
                                </legend>
                                <table id="FrmChumonCSV_sprList">
                                </table>
                            </fieldset>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="FrmChumonCSV labels">
            <table>
                <tr>
                    <td>
                        <label class="FrmChumonCSV labels lblMSG" for="">

                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="FrmChumonCSV labels lblMSG2" for="">

                        </label>
                    </td>
                </tr>
            </table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmChumonCSV cmdAction Tab Enter'>
                    実行
                </button>
            </div>
        </div>
    </div>
</div>
