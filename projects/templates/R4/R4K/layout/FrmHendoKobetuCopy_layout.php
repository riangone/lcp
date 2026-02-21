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
* 20150928           #2179                     BUG                            LI
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmHendoKobetuCopy/FrmHendoKobetuCopy"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmHendoKobetuCopy" class="FrmHendoKobetuCopy R4-content">
    <div>
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">コピー元(前月分)</span></b>
            </legend>

            <label class="FrmHendoKobetuCopy lbl-sky-GF" for="">
                項目番号
            </label>
            <select class="FrmHendoKobetuCopy cboItemNO Enter Tab" style="width: 200px">

            </select>
            <input type="text" class="FrmHendoKobetuCopy txtItemNO" 　 style="width:50px;visibility: hidden" />

        </fieldset>
    </div>
    <div style="margin-top: 20px">
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">コピー先</span></b>
            </legend>

            <table border="0">
                <tr>
                    <td>
                        <label class="FrmHendoKobetuCopy lbl-sky-GF" for="">
                            年月
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <!-- 20150928 LI UPD S. -->
                        <!-- <input  type="text" class="FrmHendoKobetuCopy cboYMEnd Enter Tab" style="width: 100px;" maxlength="7"> -->
                        <input type="text" class="FrmHendoKobetuCopy cboYMEnd Enter Tab" style="width: 100px;"
                            maxlength="6">
                        <!-- 20150928 LI UPD E. -->
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label class="FrmHendoKobetuCopy lbl-sky-GF" for="">
                            項目番号
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input type="text" class="FrmHendoKobetuCopy lblItemNM" style="width: 200px;padding-left: 1px"
                            readonly="readonly">
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>

        </fieldset>
    </div>
    <div class="HMS-button-pane" style="margin-top: 20px">
        <div class="HMS-button-set">
            <button class="FrmHendoKobetuCopy cmdAction Enter Tab">コピー</button>
            <button class="FrmHendoKobetuCopy cmdEnd Enter Tab">戻る</button>
        </div>
    </div>

</div>
