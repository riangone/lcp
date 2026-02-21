<!-- /**
* 説明：
*
*
* @author fanzhengzhou
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
*       日付                   Feature/Bug                    内容                      担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* --------------------------------------------------------------------------------------------
*/ -->

<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmKanrRank/FrmKanrRank"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='KRSS FrmKanrRank'>
    <div class='KRSS FrmKanrRank content R4-content'>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <tr>
                    <td><label for="" class='KRSS FrmKanrRank Label1 label-snow' style="width: 82px"> 処理年月 </label></td>
                    <td>
                        <input class='KRSS FrmKanrRank cboYM Enter Tab' style="width: 80px" maxlength="6" />
                    </td>
                </tr>
                <tr height="15"></tr>
                <tr>
                    <td><label for="" class='KRSS FrmKanrRank Label2 label-snow' style="width: 82px"> 種類 </label></td>
                    <td>
                        <input class='KRSS FrmKanrRank NEW Enter Tab' type="radio" name="ranking" checked="true"
                            value="1" />
                        新車
                    </td>
                </tr>
                <tr>
                    <td><label for="" class='KRSS FrmKanrRank Label3' style="width: 82px"> </label></td>
                    <td>
                        <input class='KRSS FrmKanrRank USED Enter Tab' type="radio" name="ranking" value="2" />
                        中古車
                    </td>
                </tr>
                <tr>
                    <td><label for="" class='KRSS FrmKanrRank Label4' style="width: 82px"></label></td>
                    <td>
                        <input class='KRSS FrmKanrRank MENTE Enter Tab' type="radio" name="ranking" value="3" />
                        整備
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='KRSS FrmKanrRank cmdAction Enter Tab'>
                    実行
                </button>
                <button class='KRSS FrmKanrRank cancel Enter Tab'>
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>