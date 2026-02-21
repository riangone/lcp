<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmUriBusyoCnv/FrmUriBusyoCnv"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmUriBusyoCnv'>
    <div class='FrmUriBusyoCnv content R4-content' style="width: 1113px">
        <div class="FrmUriBusyoCnv GroupBox1">
            <fieldset>
                <legend>
                    <span style="font-size: 10pt">検索条件 </span>
                </legend>
                <table>
                    <tr>
                        <td>
                            <label class="FrmUriBusyoCnv GroupBox1 Label8" for="">
                                社員番号
                            </label>
                        </td>
                        <td width="400">
                            <input class="FrmUriBusyoCnv txtSYAINNO Tab Enter" maxlength="5" />
                        </td>
                        <td width="450">
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmUriBusyoCnv GroupBox1 Label12" for="">
                                社員名カナ
                            </label>
                        </td>
                        <td width="400">
                            <input class="FrmUriBusyoCnv txtSYAINKN Tab Enter" style="width: 395px" maxlength="40" />
                        </td>
                        <td width="450">
                            <label class="FrmUriBusyoCnv GroupBox1 Label1" for="">
                                (前方一致)
                            </label>
                        </td>
                        <td>
                            <button class="FrmUriBusyoCnv cmdSearch Tab Enter">
                                検索
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="height: 5.5vh">
        </div>
        <div class="FrmUriBusyoCnv GroupsprList">
            <table id="FrmUriBusyoCnv_sprList">
            </table>
        </div>
        <div style="height: 20px">
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmUriBusyoCnv cmdInsert Tab Enter">
                    新規登録
                </button>
                <button class="FrmUriBusyoCnv cmdUpdate Tab Enter">
                    修正
                </button>
                <button class="FrmUriBusyoCnv cmdDelete Tab Enter">
                    削除
                </button>
            </div>
        </div>
        <div id="FrmUriBusyoCnvEdit">
        </div>
        <div id="FrmUriBusyoCnv_pager">
        </div>
    </div>
</div>
