<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSCUrkIn/FrmSCUrkIn"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmSCUrkIn">
    <div class="FrmSCUrkIn content R4-content">
        <table border="0">
            <tr>
                <td>
                    <label class="FrmSCUrkIn Label1" for="">
                        計上年月:
                    </label>
                </td>

                <td>
                    <input type='text' class="FrmSCUrkIn cboYM Enter Tab" tabindex="1" />
                </td>
                <td>
                </td>

            </tr>
            <tr>
                <td>
                    取込データ種類:
                </td>
                <td>
                    <input type='radio' class="FrmSCUrkIn radSinsya Enter Tab" tabindex="2" name='FrmSCUrkIn_radio' />
                    新車
                    <input type='radio' class="FrmSCUrkIn radChukosya Enter Tab" tabindex="3" name='FrmSCUrkIn_radio' />
                    中古車
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>
                    取込CSVﾌｧｲﾙ名:
                </td>
                <td>
                    <input class="FrmSCUrkIn  txtFileName Enter Tab" style="width: 400px;" disabled="disabled">
                    <button class="FrmSCUrkIn cmdOpen Enter Tab" tabindex="4">
                        参照
                    </button>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>
                    取込件数:
                </td>
                <td>
                    <input type='text' class="FrmSCUrkIn lblJijCnt Enter Tab" disabled='disabled' />
                    件
                </td>
                <td>
                </td>
            </tr>
        </table>

        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSCUrkIn cmdAct Enter Tab'>
                    実行
                </button>
            </div>
        </div>
        <label style='border:solid 0px;float:left;color:#0000CD' class='FrmSCUrkIn lblMsg' for="">
        </label>
    </div>
</div>
<div id="tmpFileUpload">
</div>
