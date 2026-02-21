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
* 20150928           #2179                     BUG                            LI
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmHendoKobetu/FrmHendoKobetu"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmHendoKobetu" class="FrmHendoKobetu R4-content">
    <div>
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmHendoKobetu lbl-sky-xM" for="">
                            計上年月
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <!-- 20150922 Yuanjh UPD S. -->
                        <!--<input  type="text" class="FrmHendoKobetu cboYM  Enter Tab" style="width: 100px;" maxlength="7">-->
                        <input type="text" class="FrmHendoKobetu cboYM Enter Tab" style="width: 100px;" maxlength="6"
                            tabindex="1">
                        <!-- 20150922 Yuanjh UPD E. -->
                    </td>
                    <td>
                    </td>
                    <!-- 20180206 YIN UPD S -->
                    <!-- <td width="190px"> -->
                    <td width="200px">
                        <!-- 20180206 YIN UPD E -->
                    </td>
                </tr>

                <tr>
                    <td>
                        <label class="FrmHendoKobetu lbl-sky-xM" for="">
                            項目番号
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <select class="FrmHendoKobetu cboItemNO Enter Tab" style="width: 200px" tabindex="2">
                        </select>
                    </td>
                    <td>
                        <button class="FrmHendoKobetu cmdSearch1 Enter Tab" tabindex="3">
                            検索
                        </button>
                    </td>
                    <td>

                        <input type="text" class="FrmHendoKobetu txtItemNO Enter Tab" value="100"
                            style="visibility: hidden" maxlength="3" disabled="disabled" />
                        <button class="FrmHendoKobetu cmdTeisyuTrk Enter Tab" disabled="disabled" tabindex="4">
                            定収ファイルより登録
                        </button>
                    </td>

                    <!-- <td>
                    <input type="checkbox" class="FrmHendoKobetu chkSyainNo Enter Tab"/>
                    社員番号入力
                    </td> -->
                </tr>
            </table>
        </fieldset>
    </div>

    <div>
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">入力項目</span></b>
            </legend>
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmHendoKobetu lbl-sky-xM" for="">
                            部署
                        </label>
                    </td>
                    <td>
                        <input type="text" class="FrmHendoKobetu txtBusyoCD Enter Tab numeric" style="width: 60px;"
                            maxlength="3" tabindex="5">
                    </td>
                    <td>
                        <button class="FrmHendoKobetu cmdSearchBs Enter Tab" tabindex="6">
                            検索
                        </button>

                    </td>
                    <td>
                        <input type="text" class="FrmHendoKobetu lblBusyoNM Enter Tab" style="width:300px;"
                            disabled="disabled">
                    </td>
                    <td>
                        <input type="checkbox" class="FrmHendoKobetu chkSyainNo Enter Tab" tabindex="7" />
                        社員番号入力
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <label class="FrmHendoKobetu lbl-sky-xM" for="">
                            社員番号
                        </label>
                    </td>
                    <td>
                        <select class="FrmHendoKobetu cboSyainNO Enter Tab" style="width: 200px" tabindex="8">
                            <option></option>
                        </select>
                        <input type="text" class="FrmHendoKobetu txtSyainNO Enter Tab" style="width: 55px;"
                            maxlength="5" tabindex="9" />
                        <label class="FrmHendoKobetu lbl-sky-xM" style="margin-left: 15px" for="">
                            金額
                        </label>
                        <input type="text" class="FrmHendoKobetu txtKingaku numeric Enter Tab"
                            style="width: 145px;text-align: right" maxlength="14" tabindex="10" />

                    </td>

                </tr>

            </table>
        </fieldset>
    </div>
    <!-- 20150928 LI UPD S. -->
    <!-- <div style="margin-top: 20px;"> -->
    <div style="margin-top: 10px;">
        <!-- 20150928 LI UPD E. -->
        <table class="FrmHendoKobetu sprMeisai" id="FrmHendoKobetu_sprMeisai"></table>
    </div>
    <div align="right" style="margin-top: 10px;width: 760px;">
        <label class="FrmHendoKobetu lbl-sky-xM" for="">
            合計
        </label>
        <input type="text" class="FrmHendoKobetu txtGoukei" style="width: 145px; margin-right: 5px;text-align: right"
            value="0" disabled="disabled" />
    </div>
    <!-- 20150928 LI UPD S. -->
    <!-- <div class="HMS-button-pane" align="right" style="margin-top: 10px;width: 760px;"> -->
    <div class="HMS-button-pane" align="right" style="margin-top: 10px;">
        <!-- 20150928 LI UPD E. -->
        <button class="FrmHendoKobetu cmdCopy Enter Tab" tabindex="11">
            <label for="">
                コピー
            </label>
        </button>
        <button class="FrmHendoKobetu cmdAction Enter Tab" tabindex="12">
            <label for="">
                登録（F9）
            </label>
        </button>
        <button class="FrmHendoKobetu cmdDelete Enter Tab" tabindex="13">
            <label for="">
                削除
            </label>
        </button>
    </div>
</div>
