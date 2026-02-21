<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmJinjiIn/FrmJinjiIn"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmJinjiIn">
    <div class="FrmJinjiIn content R4-content">
        <table border="0">
            <tr>
                <td>
                    <label class="FrmJinjiIn Label1" for="">
                        取込CSVﾌｧｲﾙ名:
                    </label>
                </td>

                <td>
                    <!-- <input class="FrmJinjiIn  txtFile Enter Tab" style="width: 400px;"> -->
                    <input class="FrmJinjiIn  txtJinjiName Enter Tab" style="width: 400px;" disabled="disabled">
                </td>
                <td>
                    <button class="FrmJinjiIn cmdOpen Enter Tab">
                        参照
                    </button>
                </td>

            </tr>
            <tr>
                <td>
                    再取込み:
                </td>
                <td>
                    <input type='checkbox' class="FrmJinjiIn chkRtraiJin Enter Tab" />
                    <lable>
                        （取込年月のﾃﾞｰﾀが存在する場合上書きします）
                    </lable>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>
                    取込件数:
                </td>
                <td>
                    <input type='text' class="FrmJinjiIn lblJijCnt Enter Tab" disabled='disabled' />件
                </td>
                <td>
                </td>
            </tr>
        </table>

        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmJinjiIn cmdAct Enter Tab'>
                    実行
                </button>
            </div>
        </div>
        <label style='border:solid 0px;float:left;color:#0000CD' class='FrmJinjiIn lblMsg' for=""></label>
    </div>
</div>
<div id="tmpFileUpload">
</div>
