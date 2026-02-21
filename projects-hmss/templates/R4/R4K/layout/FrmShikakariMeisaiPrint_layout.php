<!-- /**
* 説明：
*
*
* @author yinhuaiyu
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150923                  #2162                   BUG                         YIN
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmShikakariMeisaiPrint/FrmShikakariMeisaiPrint"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmShikakariMeisaiPrint'>
    <div class='FrmShikakariMeisaiPrint content R4-content' style="width: 1113px">
        <fieldset style='margin-left:200px;margin-right:200px;margin-top:50px;margin-bottom:50px;'>
            <legend>
                出力対象
            </legend>
            <table>
                <tr>
                    <td>
                        <label class='FrmShikakariMeisaiPrint Label1' for="">
                            処理年月
                        </label>
                    </td>
                    <td style="width: 60px">
                    </td>
                    <td>
                        <!-- 20150923 yin upd S -->
                        <!-- <input class='FrmShikakariMeisaiPrint cboYMStart Enter Tab' style="width: 80px" maxlength="7"> -->
                        <input class='FrmShikakariMeisaiPrint cboYMStart Enter Tab' style="width: 80px" maxlength="6">
                        <!-- 20150923 yin upd E -->
                    </td>
                    <td style="width: 260px">
                    </td>
                    <td>
                        <button class='FrmShikakariMeisaiPrint cmdAction Enter Tab'>
                            実行
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
