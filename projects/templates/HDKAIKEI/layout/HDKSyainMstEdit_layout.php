<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKSyainMstEdit/HDKSyainMstEdit"));
?>

<style type="text/css">
    .HDKSyainMstEdit input,
    .HDKSyainMstEdit select {
        width: 135px !important;
    }

    .HDKSyainMstEdit.HMS-button-pane td button {
        margin-left: unset;
        margin-top: 10px;
    }
</style>

<div class="HDKSyainMstEdit">
    <div class="HDKSyainMstEdit HDKAIKEI-content HDKAIKEI-content-fixed-width">
        <table>
            <tr>
                <td><label class='HDKSyainMstEdit lbl-sky-L' for=""> 所属 </label>
                </td>
                <td><input type="text" id="BusyoCdVal" class="HDKSyainMstEdit BusyoCdVal Enter Tab" maxlength="3"
                        tabindex="1" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='HDKSyainMstEdit lbl-sky-L' for=""> 社員番号 </label>
                </td>
                <td>
                    <input type="text" id="SyainNoVal" class="HDKSyainMstEdit SyainNoVal Enter Tab" maxlength="5"
                        tabindex="2" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='HDKSyainMstEdit lbl-sky-L' for=""> 社員名 </label>
                </td>
                <td>
                    <input type="text" id="SyainNmVal" class="HDKSyainMstEdit SyainNmVal Enter Tab" maxlength="20"
                        tabindex="3" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='HDKSyainMstEdit lbl-sky-L' for=""> 社員名カナ </label>
                </td>
                <td>
                    <input type="text" id="SyainKnVal" class="HDKSyainMstEdit SyainKnVal Enter Tab" maxlength="40"
                        tabindex="4" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='HDKSyainMstEdit lbl-sky-L' for=""> パスワード </label>
                </td>
                <td>
                    <form> <input type="password" id="PassWordVal" class="HDKSyainMstEdit PassWordVal Enter Tab"
                            maxlength="10" tabindex="5" /></form>
                </td>
            </tr>
            <tr>
                <td>
                    <label class='HDKSyainMstEdit lbl-sky-L' for=""> 区分 </label>
                </td>
                <td>
                    <select id="PatternIdSel" class="HDKSyainMstEdit PatternIdSel Enter Tab" tabindex="6">
                        <option value="000">管理者</option>
                        <option value="002">一般</option>
                    </select>
                </td>
            </tr>
            <tr class="HDKSyainMstEdit HMS-button-pane">
                <td>
                    <button class="HDKSyainMstEdit LoginBtn Enter Tab" tabindex="7">登録</button>
                </td>
            </tr>
        </table>
    </div>
</div>