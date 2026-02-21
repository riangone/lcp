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
* 20150922           #2162                     BUG                            Yuanjh
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmYosanMst/FrmYosanMst'));
?>

<div id="FrmYosanMst" class="FrmYosanMst R4-content">
    <div class="FrmYosanMst searchArea">
        <fieldset class="FrmYosanMst center GroupBox1" style="width:1000px">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0">
                <tr>
                    <!-- 2018/02/05 ciyuanchen UPD S. -->
                    <!--<td><label class="FrmYosanMst lbl-blue"> 年度　　　　</label></td>-->
                    <td><label class="FrmYosanMst lbl-blue" style="width: 80px;" for=""> 年度</label></td>
                    <!-- 2018/02/05 ciyuanchen UPD E. -->
                    <td>
                        <!-- 20150922 Yuanjh UPD S. -->
                        <!--<input class="FrmYosanMst cboYM Enter Tab"  style="width: 75px"/>-->
                        <input class="FrmYosanMst cboYM Enter Tab" style="width: 75px" maxlength="6" />
                        <!-- 20150922 Yuanjh UPD E. -->
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <!-- 2018/02/05 ciyuanchen UPD S. -->
                    <!--<td><label class="FrmYosanMst lbl-blue"> 部署　　　　</label></td>-->
                    <td><label class="FrmYosanMst lbl-blue" style="width: 80px;" for="">
                            部署</label></td>
                    <!-- 2018/02/05 ciyuanchen UPD E. -->
                    <td>
                        <input class="FrmYosanMst txtBusyoCD Enter Tab" type='text' maxlength="3" style="width: 90px" />
                    </td>
                    <td>
                        <button class="FrmYosanMst cmdSearchBs" style="min-width:10px">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="FrmYosanMst lblBusyoNM" type='text' readonly="readonly" style="width: 200px" />
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <!-- 2018/02/05 ciyuanchen UPD S. -->
                    <!--<td><label class="FrmYosanMst lbl-blue"> 括り部署　　</label></td>-->
                    <td><label class="FrmYosanMst lbl-blue" style="width: 80px;" for=""> 括り部署</label>
                    </td>
                    <!-- 2018/02/05 ciyuanchen UPD E. -->
                    <td>
                        <input class="FrmYosanMst lblKKR" type='text' readonly="readonly" style="width: 90px;" />
                    </td>
                    <td width="500"></td>
                    <td>
                        <button class="FrmYosanMst cmdSearch Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class='FrmYosanMst listArea' style="margin-top: 10px">
        <table>
            <tr>
                <td>
                    <table id='FrmYosanMst_sprList' class='FrmYosanMst'></table>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmYosanMst cmdAction Tab Enter'>
                                更新
                            </button>
                            <button class='FrmYosanMst cmdClear Tab Enter'>
                                クリア
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
