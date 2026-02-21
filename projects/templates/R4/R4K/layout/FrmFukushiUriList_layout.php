<!--
/**
* 説明：
*
*
* @author FCS
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20150922           #2162                     BUG                            yin
* --------------------------------------------------------------------------------------------
*/

-->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmFukushiUriList/FrmFukushiUriList"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmFukushiUriList'>
    <div class='FrmFukushiUriList content R4-content' style="width: 1113px">
        <fieldset style='margin-left:200px;margin-right:200px;margin-top:50px;margin-bottom:50px;'>
            <legend>
                出力条件
            </legend>
            <table>
                <tr>
                    <td>
                        <label class='FrmFukushiUriList Label1' for="">
                            処理年月
                        </label>
                    </td>
                    <td style="width: 60px">
                    </td>
                    <td>
                        <!-- 20150922 yin upd S -->
                        <!-- <input class='FrmFukushiUriList cboYM Enter Tab' style="width: 80px" maxlength="7"> -->
                        <input class='FrmFukushiUriList cboYM Enter Tab' style="width: 80px" maxlength="6">
                        <!-- 20150922 yin upd S -->
                    </td>
                    <td style="width: 260px">
                    </td>
                    <td>
                        <button class='FrmFukushiUriList cmdAction Enter Tab'>
                            実行
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
