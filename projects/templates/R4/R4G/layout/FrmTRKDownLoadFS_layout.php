<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmTRKDownLoadFS/FrmTRKDownLoadFS'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<div class='FrmTRKDownLoadFS'>
    <div class='FrmTRKDownLoadFS content R4-content' style="width: 1113px">
        <table>
            <tr>
                <td>
                    <label class='FrmTRKDownLoadFS Label1' for="">
                        登録予定日
                    </label>
                </td>
                <td style="width: 60px">
                </td>
                <td>
                    <input class='FrmTRKDownLoadFS cboT_YoteiBi Enter Tab' name="FrmTRKDownLoadFS_cboT_YoteiBi"
                        style="width: 100px" maxlength="10">
                    </select>
                </td>
                <td style="width: 400px">
                </td>
                <td>
                    <button class='FrmTRKDownLoadFS Button1 Enter Tab'>
                        作成
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
