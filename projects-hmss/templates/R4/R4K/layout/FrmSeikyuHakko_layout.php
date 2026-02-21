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
echo $this->Html->script(array("R4/R4K/FrmSeikyuHakko/FrmSeikyuHakko"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmSeikyuHakko'>
    <div class='FrmSeikyuHakko content R4-content' style="width: 1113px">
        <fieldset style='margin-left:150px;margin-right:200px;margin-top:30px;margin-bottom:30px;'>
            <legend>
                出力条件
            </legend>
            <table>
                <tr>
                    <td>
                        <label class='FrmSeikyuHakko Label1' for="">
                            処理年月
                        </label>
                    </td>
                    <td>
                        <!-- 20150922 yin upd S -->
                        <!-- <input class='FrmSeikyuHakko cboYM Enter Tab' style="width: 80px" maxlength="7"> -->
                        <input class='FrmSeikyuHakko cboYM Enter Tab' style="width: 80px" maxlength="6">
                        <!-- 20150922 yin upd E -->
                    </td>
                    <td width="320">
                    </td>
                    <td>
                        <button class="FrmSeikyuHakko Button1 Enter Tab">
                            Excel出力
                        </button>
                    </td>
                    <td>
                        <button class="FrmSeikyuHakko cmdAction Enter Tab">
                            印刷
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
