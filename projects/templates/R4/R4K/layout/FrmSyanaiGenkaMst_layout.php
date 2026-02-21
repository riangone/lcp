<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyanaiGenkaMst/FrmSyanaiGenkaMst"));
?>
<div class='FrmSyanaiGenkaMst'>
    <div class='FrmSyanaiGenkaMst  R4-content'>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table width=500 border=0>
                <tr>
                    <td>
                        <label style='border:solid 1px;background-color:#B0E2FF' for="">
                            新中区分
                        </label>
                        <input type='text' class='FrmSyanaiGenkaMst txtNauKB Enter Tab' tabindex="10"
                            style='width:150px;margin-left:5px;' maxlength="1" />
                        <button style='' class='FrmSyanaiGenkaMst cmdSearch Enter Tab' tabindex="3">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <table id='FrmSyanaiGenkaMst_sprMeisai'>

        </table>
        <div id='FrmSyanaiGenkaMst_pager'>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSyanaiGenkaMst cmdInsert Enter Tab' tabindex="1">
                    新規追加
                </button>
                <button class='FrmSyanaiGenkaMst cmdUpdate Enter Tab' tabindex="2">
                    更新
                </button>
            </div>
        </div>
    </div>
</div>
