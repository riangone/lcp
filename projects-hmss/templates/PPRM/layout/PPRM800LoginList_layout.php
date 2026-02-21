<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->css(array('PPRM/PPRM800LoginList'));
echo $this->Html->script(array('PPRM/PPRM800LoginList'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM800LoginList.btn.disabled,
    .PPRM800LoginList.btn[disabled],
    fieldset[disabled] .PPRM800LoginList.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM800LoginList.ipt.disabled,
    .PPRM800LoginList.ipt[disabled],
    fieldset[disabled] .PPRM800LoginList.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM800LoginList body' id="PPRM800LoginList">
    <div class="PPRM800LoginList header" style="width:95%">
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <label for="" class='PPRM800LoginList lbl-sky-xM'>
                                ユーザＩＤ
                            </label>
                        </td>
                        <td colspan="5">
                            <input class='PPRM800LoginList ipt Enter Tab LvTextUserID' style="width:50px;" maxlength="5"
                                tabindex="51" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="" class='PPRM800LoginList lbl-sky-xM'>
                                社員名
                            </label>
                        </td>
                        <td colspan="5">
                            <input class='PPRM800LoginList ipt Enter Tab LvTextUserNM' style="width:250px;"
                                maxlength="200" tabindex="52" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="" class='PPRM800LoginList lbl-sky-xM'>
                                部署コード
                            </label>
                        </td>
                        <td>
                            <input class='PPRM800LoginList ipt Enter Tab LvTextBusyoCD' style="width:70px;"
                                maxlength="3" tabindex="53" />
                        </td>
                        <td>
                            <button class='PPRM800LoginList btn Tab btnSearch' tabindex="-1">
                                検索
                            </button>
                        </td>
                        <td>
                            <input class='PPRM800LoginList ipt Tab LvTextBusyoNM'
                                style="width:250px;background-color:#BABEC1;" readonly="readonly" tabindex="-1" />
                        </td>
                        <td style="width:250px;">
                        </td>
                        <td>
                            <button class='PPRM800LoginList btn Tab btnView' tabindex="54">
                                検索
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <input type="checkbox" id="chk" class='PPRM800LoginList ipt lvTaisyoku' checked="checked" />
                            <label for="" class='PPRM800LoginList lblTaisyoku Enter'
                                style="width:150px;text-align:left;" for="chk">
                                退職した社員を除く
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="PPRM800LoginList pnlLoginList" style="margin-top:30px;">
        <table>
            <tbody>
                <tr>
                    <td>
                        <table id="PPRM800LoginList_gvLoginList" class="PPRM800LoginList gvLoginList">
                        </table>
                    </td>
                    <td style="vertical-align: top;padding-left:15px;">
                        <button class='PPRM800LoginList btn btnEdit Tab' style="display: block;margin-bottom:13px;">
                            修正
                        </button>
                        <button class='PPRM800LoginList btn btnDelete Tab' style="display: block">
                            削除
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="PPRM800LoginList_dialogs" class="PPRM800LoginList dialogs702" style="display: none;">
    </div>
    <div id="PPRM800LoginList_dialogs" class="PPRM800LoginList dialogs801" style="display: none;">
    </div>
</div>