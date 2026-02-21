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
echo $this->Html->script(array("R4/R4K/FrmNinhoIn/FrmNinhoIn"));
?>

<!-- 画面個別の内容を表示 -->

<div class="FrmNinhoIn">
    <div class="FrmNinhoIn content R4-content" style="width: 1113px">
        <table border="0">
            <tr>
                <td><label class="FrmNinhoIn Label1" for=""> 年月 </label></td>
                <td></td>
                <td>
                    <!-- 20150922 Yuanjh UPD S. -->
                    <!--<input class="FrmNinhoIn cboYM Enter Tab" style="width: 100px;" maxlength="7"　value="2006/04/05">-->
                    <input class="FrmNinhoIn cboYM Enter Tab" style="width: 100px;" maxlength="6">
                    <!-- 20150922 Yuanjh UPD E. -->

                </td>


                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><label class="FrmNinhoIn Label1" for=""> 取込先 </label></td>
                <td></td>
                <td>
                    <!-- <input class="FrmNinhoIn  txtFile Enter Tab" style="width: 400px;"> -->
                    <input class="FrmNinhoIn  txtFile Enter Tab" style="width: 400px;" disabled="disabled">
                </td>
                <td>
                    <button class="FrmNinhoIn cmdOpen Enter Tab">
                        参照
                    </button>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td align="right">
                    <button class="FrmNinhoIn cmdAct Enter Tab">
                        取込実行
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
<div id="tmpFileUpload"></div>
