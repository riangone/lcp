<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyasyu/FrmSyasyu"));
?>
<div class='FrmSyasyu'>
    <div class='FrmSyasyu  R4-content'>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table width=500 border=0>
                <tr>
                    <td>
                        <label style='border:solid 1px;background-color:#B0E2FF' for="">
                            ＵＣ親コード
                        </label>
                        <input type='text' class='FrmSyasyu txtKANA Enter Tab' tabindex="10"
                            style='width:150px;margin-left:5px;' maxlength="3" />
                        <button style='' class='FrmSyasyu cmdSearch Enter Tab' tabindex="3">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <table id='FrmSyasyu_sprMeisai'>

        </table>
        <div id='FrmSyasyu_pager'>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSyasyu cmdInsert Enter Tab' tabindex="1">
                    新規追加
                </button>
                <button class='FrmSyasyu cmdUpdate Enter Tab' tabindex="2">
                    更新
                </button>
                <button class='FrmSyasyu cmdCancel Enter Tab' tabindex="3">
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>
