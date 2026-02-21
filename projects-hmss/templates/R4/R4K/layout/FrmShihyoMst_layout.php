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
* 20151124           BUG对应                    BUG                       	  Yin
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmShihyoMst/FrmShihyoMst'));
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmShihyoMst.GroupBox1 {
            max-width: 98% !important;
        }
    }
</style>
<!--//20150819  Yuanjh DEL S.-->
<!--
<div id="FrmShihyoMst" class="FrmShihyoMst R4-content">
    <div class="FrmShihyoMst searchArea">
        <fieldset class="FrmShihyoMst center GroupBox1" style="height: 115px;width: 800px">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0" width="99%">
                <tr>
                    <td>
                    <label class="FrmShihyoMst lbl-blue">
                        年度　　　　　
                    </label>
                    </td>
                    <td>
                    <input class="FrmShihyoMst cboYM Enter Tab"  style="width: 100px"/>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label class="FrmShihyoMst lbl-blue">
                        部署　　　　　
                    </label>
                    </td>
                    <td>
                    <input class="FrmShihyoMst txtBusyoCD Enter Tab"  type='text' maxlength="3" style="width: 75px"/>
                    </td>
                    <td>
                    <button class="FrmShihyoMst cmdSearchBs" style="min-width:10px">
                        検索
                    </button>
                    </td>
                    <td>
                    <input class="FrmShihyoMst lblBusyoNM" type='text' readonly="readonly" style="width: 200px"/>
                    </td>
                </tr>
                <tr>
                    <td>
                    <label class="FrmShihyoMst lbl-blue" style="width:73px;height: 18px;margin-top: 7px">
                        括り部署　　　　　
                    </label>
                    </td>
                    <td>
                    <input class="FrmShihyoMst lblKKR"  type='text' readonly="readonly" style="width: 75px;margin-bottom: 7px"/>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td width=40%>
                    <button class="FrmShihyoMst cmdSearch Enter Tab">
                        検索
                    </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
-->
<!--//20150819  Yuanjh DEL E.-->
<!--//20150819  Yuanjh ADD S.-->
<div id="FrmShihyoMst" class="FrmShihyoMst R4-content">
    <div class="FrmShihyoMst searchArea">
        <fieldset class="FrmShihyoMst center GroupBox1" style="width: 1080px">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0">
                <tr>
                    <!-- 20151124 YIN UPD S -->
                    <!-- <td><label class="FrmYosanMst lbl-blue"> 年度　　　　</label></td> -->
                    <td><label class="FrmShihyoMst lbl-blue" style="width: 80px" for=""> 年度</label>
                    </td>
                    <!-- 20151124 YIN UPD E -->
                    <td>
                        <!-- 20150922 Yuanjh UPD S. -->
                        <!--<input class="FrmShihyoMst cboYM Enter Tab"  style="width: 75px"/>-->
                        <div class="FrmShihyoMst cboYMFromdiv" style="float: left">
                            <input class="FrmShihyoMst cboYM Enter Tab" style="width: 75px" maxlength="6" />
                            <!-- 20150922 Yuanjh UPD E. -->
                    </td>
                </tr>
                <tr>
                    <!-- 20151124 YIN UPD S -->
                    <!-- <td><label class="FrmYosanMst lbl-blue"> 部署　　　　</label></td> -->
                    <td><label class="FrmShihyoMst lbl-blue" style="width: 80px" for="">
                            部署</label></td>
                    <!-- 20151124 YIN UPD E -->
                    <td>
                        <input class="FrmShihyoMst txtBusyoCD Enter Tab" type='text' maxlength="3"
                            style="width: 90px" />
                    </td>
                    <td>
                        <!-- 20151124 YIN UPD S -->
                        <!-- <button class="FrmShihyoMst cmdSearch Enter Tab" style="min-width:10px"> -->
                        <button class="FrmShihyoMst cmdSearchBs Enter Tab" style="min-width:10px">

                            <!-- 20151124 YIN UPD E -->
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="FrmShihyoMst lblBusyoNM" type='text' readonly="readonly" style="width: 200px" />
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <!-- 20151124 YIN UPD S -->
                    <!-- <td><label class="FrmYosanMst lbl-blue"> 括り部署　　</label></td> -->
                    <td><label class="FrmShihyoMst lbl-blue" style="width: 80px" for=""> 括り部署</label>
                    </td>
                    <!-- 20151124 YIN UPD E -->
                    <td>
                        <input class="FrmShihyoMst lblKKR" type='text' readonly="readonly" style="width: 90px;" />
                    </td>
                    <td width="500"></td>
                    <td>
                        <!-- 20151124 YIN UPD S -->
                        <!-- <button class="FrmYosanMst cmdSearch Enter Tab"> -->
                        <button class="FrmShihyoMst cmdSearch Enter Tab">
                            <!-- 20151124 YIN UPD E -->
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <!--//20150819  Yuanjh ADD E.-->

    <div class='FrmShihyoMst listArea' style="margin-top: 10px">
        <table>
            <tr>
                <td>
                    <table id='FrmShihyoMst_sprList' class='FrmShihyoMst'>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmShihyoMst cmdAction Tab Enter'>
                                更新
                            </button>
                            <button class='FrmShihyoMst cmdClear Tab Enter'>
                                クリア
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
