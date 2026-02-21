<!-- /**
* 説明：
*
*
* @author lijun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150911                  #2114                   BUG                         LI
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmGENRILIST/FrmGENRILIST"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmGENRILIST'>
    <div class='FrmGENRILIST content R4-content' style="width: 1113px">
        <table>
            <tr>
                <td>
                    <label class='FrmGENRILIST Label1' for="">
                        処理年月
                    </label>
                </td>
                <td style="width: 60px">
                </td>
                <td>
                    <!-- 20150922 yin upd S -->
                    <!-- <input class='FrmGENRILIST cboYM Enter Tab' style="width: 80px" maxlength="7"> -->
                    <input class='FrmGENRILIST cboYM Enter Tab' style="width: 80px" maxlength="6">
                    <!-- 20150922 yin upd S -->
                </td>
                <td style="width: 500px">
                </td>
                <td>
                    <button class='FrmGENRILIST cmdAct Enter Tab' style="min-width: 80px;height: 23px;">
                        実行
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
