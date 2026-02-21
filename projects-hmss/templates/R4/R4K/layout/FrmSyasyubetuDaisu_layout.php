<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyasyubetuDaisu/FrmSyasyubetuDaisu"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmSyasyubetuDaisu'>
    <div class='FrmSyasyubetuDaisu content R4-content' style="width: 1113px">
        <fieldset style='margin-left:200px;margin-right:200px;margin-top:50px;margin-bottom:50px;'>
            <legend>
                出力条件
            </legend>
            <table>
                <tr>
                    <td>
                        <label class='FrmSyasyubetuDaisu Label1' for="">
                            処理年月
                        </label>
                    </td>
                    <td style="width: 60px">
                    </td>
                    <td>
                        <!-- 20150922 yin upd S -->
                        <!-- <input class='FrmSyasyubetuDaisu cboYMFrom Enter Tab' style="width: 80px" maxlength="7"> -->
                        <input class='FrmSyasyubetuDaisu cboYMFrom Enter Tab' style="width: 80px" maxlength="6">
                        <!-- 20150922 yin upd E -->
                    </td>
                    <td style="width: 260px">
                    </td>
                    <td>
                        <button class='FrmSyasyubetuDaisu cmdAction Enter Tab'>
                            実行
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
