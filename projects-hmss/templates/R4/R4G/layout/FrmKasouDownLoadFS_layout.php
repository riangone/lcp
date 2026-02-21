<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmKasouDownLoadFS/FrmKasouDownLoadFS'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<div class='FrmKasouDownLoadFS'>
    <div class='FrmKasouDownLoadFS content R4-content' style="width: 1113px">
        <table>
            <tr>
                <td>
                    <label class='FrmKasouDownLoadFS Label1' for="">
                        注文書番号
                    </label>
                </td>
                <td style="width: 60px">
                </td>
                <td>
                    <input class='FrmKasouDownLoadFS txtCmnNo Enter Tab' name="FrmKasouDownLoadFS_txtCmnNo"
                        style="width: 100px" maxlength="10">
                </td>
                <td style="width: 400px">
                </td>
                <td>
                    <button class='FrmKasouDownLoadFS Button1 Enter Tab'>
                        実行
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
