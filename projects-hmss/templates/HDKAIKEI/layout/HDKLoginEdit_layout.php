<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKLoginEdit/HDKLoginEdit"));
?>

<style type="text/css">
    .HDKLoginEdit input {
        width: 202px !important;
    }

    .HDKLoginEdit .BusyoCdVal {
        width: 70px !important;
    }

    .HDKLoginEdit .BusyoNmVal {
        width: 120px !important;
    }

    .HDKLoginEdit.HMS-button-pane td button {
        margin-left: unset;
        margin-top: 10px;
    }
</style>

<div class="HDKLoginEdit">
    <div class="HDKLoginEdit HDKAIKEI-content HDKAIKEI-content-fixed-width">
        <table>
            <tr>
                <td><label for="" class='HDKLoginEdit lbl-sky-L'> ユーザーID </label>
                </td>
                <td><input type="text" class="HDKLoginEdit SyainNoVal Enter Tab" maxlength="5" tabindex="1" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="" class='HDKLoginEdit lbl-sky-L'> 所属 </label>
                </td>
                <td>
                    <input type="text" class="HDKLoginEdit BusyoCdVal Enter Tab" maxlength="3" tabindex="2" />
                    <input type="text" class="HDKLoginEdit BusyoNmVal Enter Tab" disabled />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="" class='HDKLoginEdit lbl-sky-L'> 氏名 </label>
                </td>
                <td>
                    <input type="text" class="HDKLoginEdit SyainNmVal Enter Tab" maxlength="20" tabindex="3" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="" class='HDKLoginEdit lbl-sky-L'> パスワード </label>
                </td>
                <td>
                    <form>
                        <input type="password" class="HDKLoginEdit PassWordVal Enter Tab" maxlength="10" tabindex="4" />
                    </form>
                </td>
            </tr>
            <tr class="HDKLoginEdit HMS-button-pane">
                <td>
                    <button class="HDKLoginEdit btnPtnUpdate Enter Tab" tabindex="5">更新</button>
                </td>
                <td>
                    <button class="HDKLoginEdit btnPtnClear Enter Tab" tabindex="6">クリア</button>
                </td>
            </tr>
        </table>
    </div>
</div>