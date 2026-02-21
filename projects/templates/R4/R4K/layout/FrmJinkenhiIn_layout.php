<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmJinkenhiIn/FrmJinkenhiIn"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmJinkenhiIn ">
    <div class="FrmJinkenhiIn  content R4-content" style="width: 1113px">
        <table border="0">
            <tr>
                <td><label class="FrmJinkenhiIn  Label1" for=""> 年月 </label></td>
                <td></td>
                <td>
                    <input class="FrmJinkenhiIn cboYM Enter Tab" style="width: 100px;" maxlength="7">
                </td>


                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><label class="FrmJinkenhiIn Label1" for=""> 取込先 </label></td>
                <td></td>
                <td>
                    <!-- <input class="FrmJinkenhiIn   txtFile Enter Tab" style="width: 400px;"> -->
                    <input class="FrmJinkenhiIn txtFile Enter Tab" style="width: 400px;" disabled="disabled">
                </td>
                <td>
                    <button class="FrmJinkenhiIn cmdOpen Enter Tab">
                        参照
                    </button>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td align="right">
                    <button class="FrmJinkenhiIn cmdAct Enter Tab">
                        取込実行
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
<div id="tmpFileUpload"></div>
