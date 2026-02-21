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
echo $this->Html->script(array("R4/R4K/FrmHendoBubetuCopy/FrmHendoBubetuCopy"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmHendoBubetuCopy" class="FrmHendoBubetuCopy R4-content">
    <div align="center">

        <table>
            <tr>
                <td width="40px">
                    <label for="">
                        <b>前月</b>
                    </label>
                </td>
                <td width="40px">
                    <b>→</b>
                </td>
                <td>
                    <label class="FrmHendoBubetuCopy lbl-sky-GF" for="">
                        コピー先年月
                    </label>

                </td>
                <td>
                    <!-- 20150928 LI UPD S. -->
                    <!-- <input  type="text" class="FrmHendoBubetuCopy cboYMEnd Enter Tab" style="width: 100px;" maxlength="7"> -->
                    <input type="text" class="FrmHendoBubetuCopy cboYMEnd Enter Tab" style="width: 100px;"
                        maxlength="6">
                    <!-- 20150928 LI UPD E. -->
                </td>
            </tr>
        </table>

    </div>

    <div class="HMS-button-pane" style="margin-top: 20px">
        <div class="HMS-button-set">
            <button class="FrmHendoBubetuCopy cmdAction Enter Tab">
                コピー
            </button>
            <button class="FrmHendoBubetuCopy cmdEnd Enter Tab">
                戻る
            </button>
        </div>
    </div>

</div>
