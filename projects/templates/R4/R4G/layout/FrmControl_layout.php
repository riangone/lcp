<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmControl/FrmControl'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmControl'>
    <div class='FrmControl R4-content'>
        <div style="height: 20px">
        </div>
        <label class="FrmControl Label2" style="margin-left: 20px" for="">
            ロックを解除したい処理のチェックボックスを選択して、実行ボタンを押してください。
        </label>
        <div style="height: 20px">
        </div>
        <div style="margin-left: 20px">
            <table id='FrmControl_sprList'>
            </table>
        </div>
        <!-- <div>
        <table>
        <tr>
        <td style="width: 700px">
        </td>
        <td> -->
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmControl cmdAction">
                    実行
                </button>
            </div>
        </div>
        <!-- </td>
        </tr>
        </table>
        </div> -->
    </div>
</div>
