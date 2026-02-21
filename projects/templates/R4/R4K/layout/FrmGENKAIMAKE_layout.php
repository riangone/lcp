<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmGENKAIMAKE/FrmGENKAIMAKE"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmGENKAIMAKE'>
    <div class='FrmGENKAIMAKE content R4-content' style="width: 1113px">
        <table>
            <tr>
                <td>
                    <label class='FrmGENKAIMAKE Label1' for="">
                        処理年月
                    </label>
                </td>
                <td style="width: 60px">
                </td>
                <td>
                    <!-- 20150922 yin upd S -->
                    <!-- <input class='FrmGENKAIMAKE cboYM Enter Tab' style="width: 80px" maxlength="7"> -->
                    <input class='FrmGENKAIMAKE cboYM Enter Tab' style="width: 80px" maxlength="6">
                    <!-- 20150922 yin upd E -->
                </td>
                <td style="width: 500px">
                </td>
                <td>
                    <button class='FrmGENKAIMAKE cmdAct Enter Tab'>
                        実行
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
