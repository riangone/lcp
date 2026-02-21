<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmAuthCtl/FrmAuthCtl"));
?>
<div class="KRSS FrmAuthCtl" id="KRSS_FrmAuthCtl">
    <div class="KRSS FrmAuthCtl content R4-content">
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0" width="99%">
                <tr>
                    <td style="width:96px"><label class="KRSS FrmAuthCtl Label5 label-snow" for=""
                            style="width: 100%;padding-top: 2px"> 社員番号 </label></td>
                    <td style="width:10px">
                    <td colspan="4" style="width:48px">
                        <input class="KRSS FrmAuthCtl txtSyainCDFrom Enter Tab" type="text" maxlength="5" />
                    </td>

                </tr>
                <tr>
                    <td style="width:96px"><label class="KRSS FrmAuthCtl Label3 label-snow" for=""
                            style="width: 100%;padding-top: 2px"> 社員番号カナ </label></td>
                    <td style="width:10px">
                    <td colspan="4" align="left">
                        <input class="KRSS FrmAuthCtl txtSyainKana Enter Tab" style="width:254px" maxlength="40" />
                    </td>
                </tr>
                <tr>
                    <td style="width:96px"><label class="KRSS FrmAuthCtl Label4 label-snow" for=""
                            style="width: 100%;padding-top: 2px"> 部署コード </label></td>
                    <td style="width:10px">
                    <td style="width:30px">
                        <input class="KRSS FrmAuthCtl txtBusyouCD Enter Tab" type='text' maxlength="3" />
                    </td>
                    <td style="width:31px">
                        <button class="KRSS FrmAuthCtl cmdBS1 Enter Tab">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="KRSS FrmAuthCtl txtBusyouNM" type='text' disabled="disabled"
                            style="width: 304px" />
                    </td>
                    <td align="right">
                        <button class="KRSS FrmAuthCtl cmdSearch Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div style="float: left;margin-top: 10px">
            <div>
                <table id='FrmAuthCtl_sprList'></table>
            </div>
            <div id="FrmAuthCtl_pager"></div>
        </div>
        <div style="float: left;margin-top: 20px;margin-left: 5px">
            <button class="KRSS FrmAuthCtl cmdUpdate Tab Enter" style="width: 85px;height: 24px">
                登録
            </button>
            <button class="KRSS FrmAuthCtl cmdDelete Tab Enter" style="width: 85px;height: 24px">
                削除
            </button>
        </div>
    </div>
</div>
<!--子画面-->
<div id="FrmAuthCtlEdit"></div>
