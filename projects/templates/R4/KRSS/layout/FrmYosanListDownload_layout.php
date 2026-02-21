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
* --------------------------------------------------------------------------------------------
*/

-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmYosanListDownload/FrmYosanListDownload"));
?>
<div class="KRSS FrmYosanListDownload" id="KRSS_FrmYosanListDownload" style="width:100%;height:100%">
    <div class="KRSS FrmBusyoKanr R4-content">
        <fieldset>
            <legend>
                出力対象
            </legend>
            <table class="KRSS FrmYosanListDownload table1" border="0" 　style='width:100%;'>

                <tr>
                    <td width=80> 処理年月 </td>
                    <td>
                        <input type="text" class="KRSS FrmYosanListDownload cboYM Enter Tab" style="width:80px"
                            tabindex="1" maxlength="6" />
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
                        <table id="KRSS_FrmYosanListDownload_sprList"></table>
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
                                    <input type="text" class="KRSS FrmYosanListDownload txtBusyoCDFrom Tab Enter"
                                        style="width:60px" maxlength="3" tabindex="2" />
                                    <input type="text" class="KRSS FrmYosanListDownload txtBusyoNMFrom"
                                        style="width:260px" disabled="disabled" tabindex="3" />
                                    ~
                                </td>
                                <td></td>
                            </tr>
                            <tr>

                                <td></td>
                                <td>
                                    <input type="text" class="KRSS FrmYosanListDownload txtBusyoCDTo Tab Enter"
                                        style="width:60px" maxlength="3" tabindex="4" />
                                    <input type="text" class="KRSS FrmYosanListDownload txtBusyoNMTo"
                                        style="width:260px" disabled="disabled" tabindex="5" />
                                </td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td align="right">
                        <table border=0>
                            <tr>
                                <td>
                                    <button class="KRSS FrmYosanListDownload cmdYosan Tab Enter" tabindex="6">
                                        予算表ダウンロード
                                    </button>
                                </td>
                                <td>

                                <td>
                                    <button class="KRSS FrmYosanListDownload cmdCancel Tab Enter" tabindex="8">
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