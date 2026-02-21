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
echo $this->Html->script(array("R4/R4K/FrmHendoBubetu/FrmHendoBubetu"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmHendoBubetu" class="FrmHendoBubetu R4-content">
    <div>
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmHendoBubetu lbl-sky-xM" for="">
                            計上年月
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <!-- 20150922 Yuanjh UPD S. -->
                        <!--<input  type="text" class="FrmHendoBubetu cboYM  Enter Tab" style="width: 100px;" maxlength="7">-->
                        <input type="text" class="FrmHendoBubetu cboYM Enter Tab" style="width: 100px;" maxlength="6">
                        <!-- 20150922 Yuanjh UPD E. -->
                    </td>
                    <td>
                    </td>
                    <td width="190px">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label class="FrmHendoBubetu lbl-sky-xM" for="">
                            項目番号
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <select class="FrmHendoBubetu cboItemNO Enter Tab" style="width: 200px">
                        </select>
                    </td>
                    <td>
                        <button class="FrmHendoBubetu cmdSearch1 Enter Tab">
                            検索
                        </button>
                    </td>
                    <td>

                        <input type="text" class="FrmHendoBubetu txtItemNO" value="100" style="visibility: hidden"
                            maxlength="3" disabled="disabled" />

                    </td>

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
                        <label class="FrmHendoBubetu lbl-sky-xM" for="">
                            部署
                        </label>
                    </td>
                    <td>
                        <input type="text" class="FrmHendoBubetu txtBusyoCD Enter Tab" style="width: 60px;"
                            maxlength="3">
                    </td>
                    <td>
                        <button class="FrmHendoBubetu cmdSearchBs Enter Tab">
                            検索
                        </button>

                    </td>
                    <td>
                        <input type="text" class="FrmHendoBubetu lblBusyoNM Enter Tab" style="width:300px;"
                            disabled="disabled">
                    </td>
                    <td>
                        <label class="FrmHendoBubetu lbl-sky-xM" style="margin-left: 15px" for="">
                            金額
                        </label>
                        <input type="text" class="FrmHendoBubetu txtKingaku numeric Tab"
                            style="width: 145px;text-align: right" maxlength="14" />

                    </td>
                </tr>
            </table>

        </fieldset>
    </div>

    <!-- 20150928 LI UPD S. -->
    <!-- <div style="margin-top: 20px;"> -->
    <div style="margin-top: 10px;">
        <!-- 20150928 LI UPD E. -->
        <table class="FrmHendoBubetu  sprMeisai" id="FrmHendoBubetu_sprMeisai">
        </table>

    </div>
    <div align="right" style="margin-top: 10px;width: 560px;">
        <label class="FrmHendoBubetu lbl-sky-xM" for="">
            合計
        </label>
        <input type="text" class="FrmHendoBubetu txtGoukei" style="width: 195px; margin-right: 5px;text-align: right"
            value="0" disabled="disabled" />
    </div>
    <!-- 20150928 LI UPD S. -->
    <!-- <div class="HMS-button-pane" align="right" style="margin-top: 10px;width: 560px;"> -->
    <div class="HMS-button-pane" align="right" style="margin-top: 10px;">
        <!-- 20150928 LI UPD E. -->
        <button class="FrmHendoBubetu cmdCopy Enter Tab">
            <label for="">
                コピー
            </label>
        </button>
        <button class="FrmHendoBubetu cmdAction Enter Tab">
            <label for="">
                登録（F9）
            </label>
        </button>
        <button class="FrmHendoBubetu cmdDelete Enter Tab">
            <label for="">
                削除
            </label>
        </button>
    </div>
</div>
</div>
