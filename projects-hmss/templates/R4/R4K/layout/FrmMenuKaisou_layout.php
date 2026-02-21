<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmMenuKaisou/FrmMenuKaisou'));
?>
<style>
    .FrmMenuKaisou .width.ui-button {
        padding-left: 0 !important
    }
</style>
<div id="FrmMenuKaisou" class='FrmMenuKaisou R4-content'>
    <div class="FrmMenuKaisou searchArea">
        <fieldset class="FrmMenuKaisou searchGroup" style="width: 800px">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <label class='FrmMenuKaisou lbl-blue' style="min-width: 100px" for="">
                所属ＩＤ
            </label>
            <select class="FrmMenuKaisou UcCboStyleID Enter Tab" style="width: 285px">
                <option></option>
            </select>
            <button class='FrmMenuKaisou btnSearch Tab Enter'>
                検索
            </button>
        </fieldset>
    </div>
    <div class='FrmMenuKaisou listArea'>
        <table>
            <tr>
                <td>
                    <table id='FrmMenuKaisou_sprList'>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmMenuKaisou btnLogin Tab Enter'>
                                登録
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="FrmMenuKaisou contextMenu" id="FrmMenuKaisou_columnMenu" style="display:none">
    <ul style="width: 200px">
        <li id="FrmMenuKaisou_MenuInsert">
            <span class="ui-icon ui-icon-plus" style="float:left"></span>
            <span>行挿入</span>
        </li>
        <li id="FrmMenuKaisou_MenuDelete">
            <span class="ui-icon ui-icon-trash" style="float:left"></span>
            <span>行削除</span>
        </li>
    </ul>
</div>
