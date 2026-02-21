<!--
/**
* 説明：
*
*
* @author yushuangji
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20160612			 依赖#2530				   EXCEL出力機能の速度改善			  Yinhuaiyu
* --------------------------------------------------------------------------------------------
*/

-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmBusyoKanriVBA/FrmBusyoKanriVBA"));
?>
<div class="KRSS FrmBusyoKanriVBA" id="KRSS_FrmBusyoKanriVBA" style="width:100%;height:100%">
    <div class="KRSS FrmBusyoKanriVBA R4-content">
        <fieldset>
            <legend>
                出力対象
            </legend>
            <table class="KRSS FrmBusyoKanriVBA table1" border="0" 　style='width:100%;'>

                <tr>
                    <td width=80> 処理年月 </td>
                    <td>
                        <input type="text" class="KRSS FrmBusyoKanriVBA cboYM Enter Tab" style="width:80px" tabindex="1"
                            maxlength="6" />
                        <label class="KRSS FrmBusyoKanriVBA label">
                            <input type="checkbox" class="KRSS FrmBusyoKanriVBA chkMikakudei Enter Tab" tabindex="2"
                                style="margin-left: 80px;" />
                            未確定の集計データを使用する
                        </label>

                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>パターン </td>
                    <td>
                        <table id="KRSS_FrmBusyoKanriVBA_sprList"></table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table width=100% border=0>
                            <tr>
                                <td width="80"> 部署 </td>
                                <td>
                                    <input type="text" class="KRSS FrmBusyoKanriVBA txtBusyoCDFrom Tab Enter"
                                        style="width:60px" maxlength="3" tabindex="3" />
                                    <input type="text" class="KRSS FrmBusyoKanriVBA txtBusyoNMFrom" style="width:260px"
                                        disabled="disabled" tabindex="4" />
                                    ~
                                </td>
                                <td></td>
                            </tr>
                            <tr>

                                <td></td>
                                <td>
                                    <input type="text" class="KRSS FrmBusyoKanriVBA txtBusyoCDTo Tab Enter"
                                        style="width:60px" maxlength="3" tabindex="5" />
                                    <input type="text" class="KRSS FrmBusyoKanriVBA txtBusyoNMTo" style="width:260px"
                                        disabled="disabled" tabindex="6" />
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td align="right">
                        <table border=0>
                            <tr>
                                <td>
                                    <button class="KRSS FrmBusyoKanriVBA cmdAction Tab Enter" tabindex="7">
                                        実行
                                    </button>
                                </td>
                                <td>
                                    <button class="KRSS FrmBusyoKanriVBA cmdCancel Tab Enter" tabindex="8">
                                        キャンセル
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </fieldset>

    </div>
</div>