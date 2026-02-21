<!-- /**
* 説明：
*
*
* @author yinhuaiyu
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150923                  #2162                   BUG                         YIN
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSeibiDaisuIn/FrmSeibiDaisuIn"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmSeibiDaisuIn">
    <div class="FrmSeibiDaisuIn content R4-content" style="width: 1113px">
        <table border="0">
            <tr>
                <td><label class="FrmSeibiDaisuIn Label1" for=""> 年月 </label></td>
                <td></td>
                <td>
                    <!-- 20150923 yin upd S -->
                    <!-- <input class="FrmSeibiDaisuIn cboYM Enter Tab" style="width: 100px;" maxlength="7"　value="2006/04/05"> -->
                    <input class="FrmSeibiDaisuIn cboYM Enter Tab" style="width: 100px;" maxlength="6"
                        value="2006/04/05">
                    <!-- 20150923 yin upd E -->
                </td>


                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><label class="FrmSeibiDaisuIn Label1" for=""> 取込先 </label></td>
                <td></td>
                <td>
                    <!-- <input class="FrmSeibiDaisuIn  txtFile Enter Tab" style="width: 400px;"> -->
                    <input class="FrmSeibiDaisuIn  txtFile Enter Tab" style="width: 400px;" disabled="disabled">
                </td>
                <td>
                    <button class="FrmSeibiDaisuIn cmdOpen Enter Tab">
                        参照
                    </button>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td align="right">
                    <button class="FrmSeibiDaisuIn cmdAct Enter Tab">
                        取込実行
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
<div id="tmpFileUpload"></div>
