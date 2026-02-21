<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmHanteChkList/FrmHanteChkList"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmHanteChkList'>
    <div class='FrmHanteChkList content R4-content' style="width: 1113px">
        <fieldset style='margin-left:200px;margin-right:200px;margin-top:50px;margin-bottom:50px;'>
            <legend>
                出力対象
            </legend>
            <table>
                <tr>
                    <td>
                        <label class='FrmHanteChkList Label1' for="">
                            処理年月
                        </label>
                    </td>
                    <td style="width: 60px">
                    </td>
                    <td>
                        <input class='FrmHanteChkList cboYMStart Enter Tab' style="width: 100px" maxlength="10">
                    </td>
                    <td>
                        <label class="FrmHanteChkList Label3" for="">
                            ～
                        </label>
                    </td>
                    <td>
                        <input class='FrmHanteChkList cboYMEnd Enter Tab' style="width: 100px" maxlength="10">
                    </td>
                    <td style="width: 150px">

                    </td>
                    <td>
                        <button class='FrmHanteChkList cmdAction Enter Tab'>
                            実行
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
