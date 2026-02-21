<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->css(array('PPRM/PPRM801LoginEntry'));
echo $this->Html->script(array('PPRM/PPRM801LoginEntry'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM801LoginEntry.btn.disabled,
    .PPRM801LoginEntry.btn[disabled],
    fieldset[disabled] .PPRM801LoginEntry.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM801LoginEntry.ipt.disabled,
    .PPRM801LoginEntry.ipt[disabled],
    fieldset[disabled] .PPRM801LoginEntry.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM801LoginEntry body' id="PPRM801LoginEntry">
    <form>
        <table>
            <tbody>
                <tr>
                    <td>
                        <label class='PPRM801LoginEntry lbl-sky-xM'>
                            ユーザＩＤ
                        </label>
                    </td>
                    <td>
                        <input class='PPRM801LoginEntry ipt Enter Tab LvTextUserID' maxlength="5" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class='PPRM801LoginEntry lbl-sky-xM'>
                            パスワード
                        </label>
                    </td>
                    <td>
                        <input type="password" class='PPRM801LoginEntry ipt Enter Tab LvTextPass' maxlength="10" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class='PPRM801LoginEntry lbl-sky-xM'>
                            パスワード確認
                        </label>
                    </td>
                    <td>
                        <input type="password" class='PPRM801LoginEntry ipt Enter Tab LvTextPassConfirm'
                            maxlength="10" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class='PPRM801LoginEntry lbl-sky-xM'>
                            権限
                        </label>
                    </td>
                    <td>
                        <select class="PPRM801LoginEntry ddlRights Enter Tab" style="width:100%">
                        </select>
                    </td>
                </tr>

            </tbody>
        </table>
    </form>
    <div class="PPRM801LoginEntry buttons">
        <button class='PPRM801LoginEntry Tab btn btnUpdate'>
            登録
        </button>
        <button class='PPRM801LoginEntry Tab btn btnBack'>
            戻る
        </button>
    </div>
</div>