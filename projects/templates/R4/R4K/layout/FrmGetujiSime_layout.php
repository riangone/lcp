<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmGetujiSime/FrmGetujiSime"));
?>
<div class='FrmGetujiSime'>
    <div class='FrmGetujiSime  R4-content'>
        <fieldset style='margin-left:200px;margin-right:200px;margin-top:50px;margin-bottom:50px;'>
            <table border=0 style='width:500px'>
                <tr>
                    <td width=100>
                        <label for="">
                            処理年月
                        </label>
                    </td>
                    <td width=200>
                        <input class='FrmGetujiSime cboYM Enter Tab' style='width: 80px' type="text" tabindex="1"
                            maxlength="7" />
                    </td>
                    <td width=200 align='left'>
                        <button class='FrmGetujiSime cmdAction'>
                            実行
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
