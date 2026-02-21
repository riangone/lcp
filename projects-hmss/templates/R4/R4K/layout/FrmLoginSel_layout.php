<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmLoginSel/FrmLoginSel'));
?>

<div id="FrmLoginSel" class='FrmLoginSel R4-content'>
    <div class="FrmLoginSel searchArea">
        <!-- 20150818  Yuanjh Modify S.
    <fieldset class="FrmLoginSel searchGroup"  style="width: 800px">
    -->
        <fieldset class="FrmLoginSel searchGroup" style="width: 895px">
            <!-- 20150818  Yuanjh Modify E.-->
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <label class='FrmLoginSel lbl-blue' style="min-width: 100px" for="">
                ユーザＩＤ
            </label>
            <input class="FrmLoginSel UcUserID Enter Tab" />
            <br />
            <label class='FrmLoginSel lbl-blue' style="min-width: 100px" for="">
                所属ＩＤ
            </label>
            <select class="FrmLoginSel UcComboBox1 Enter Tab" style="width: 270px;margin-left:2px">
                <option></option>
            </select>
            <button class='FrmLoginSel Button1 Tab Enter'>
                検索
            </button>
        </fieldset>
    </div>
    <div class='FrmLoginSel listArea'>
        <table>
            <tr>
                <td>
                    <table id='FrmLoginSel_sprList'>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmLoginSel Button3 Tab Enter'>
                                入力
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <!--<div id="FrmLoginEditDialogDiv"></div>-->
</div>
