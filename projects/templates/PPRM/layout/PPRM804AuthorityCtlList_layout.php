<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM804AuthorityCtlList'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 20170908 ZHANGXIAOLEI INS (tabindex) -->
<style type="text/css">
    .PPRM804AuthorityCtlList.btn.disabled,
    .PPRM804AuthorityCtlList.btn[disabled],
    fieldset[disabled] .PPRM804AuthorityCtlList.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM804AuthorityCtlList.ipt.disabled,
    .PPRM804AuthorityCtlList.ipt[disabled],
    fieldset[disabled] .PPRM804AuthorityCtlList.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM804AuthorityCtlList' id="PPRM804AuthorityCtlList">
    <div>
        <fieldset style="width:900px;">
            <legend class="PPRM804AuthorityCtlList lblSearchTitle">
                検索条件
            </legend>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class='PPRM804AuthorityCtlList lbl-sky-xM '
                            style="text-align:left;width:100px;">ユーザＩＤ</label></td>
                    <td>
                        <input class='PPRM804AuthorityCtlList ipt LvTextUserID Enter Tab' style="width: 100px"
                            maxlength="5" tabindex="51">
                    </td>
                    <td style="width: 240px"></td>
                    <td><label for="" class='PPRM804AuthorityCtlList lbl-sky-xM '
                            style="text-align:left;width:100px;">社員名</label></td>
                    <td>
                        <input class='PPRM804AuthorityCtlList ipt LvTextUserNM  Enter Tab' style="width: 150px"
                            maxlength="200" tabindex="52">
                    </td>
                </tr>
            </table>

            <table>
                <tr style="height:30px;">
                    <td><label for="" class='PPRM804AuthorityCtlList lbl-sky-xM' style="text-align:left;width:100px;">
                            部署コード</label></td>
                    <td>
                        <input class='PPRM804AuthorityCtlList ipt LvTextBusyoCD Enter Tab' style="width:100px"
                            maxlength="3" tabindex="53">
                    </td>
                    <td>
                        <button class='PPRM804AuthorityCtlList btn btnSearch Tab' tabindex="-1">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM804AuthorityCtlList ipt LvTextBusyoNM Tab' style="width: 150px"
                            disabled="disabled" tabindex="-1" />
                    </td>
                    <td><label for="" class='PPRM804AuthorityCtlList lbl-sky-xM ' style="text-align:left;width:100px;">
                            権限登録状態
                        </label></td>
                    <td class="PPRM804AuthorityCtlList rdo">
                        <input class="PPRM804AuthorityCtlList ipt rdoPATTERN_0 Enter" name="radio1" value="1"
                            type="radio" checked="checked" />
                        <label for="">指定なし</label>
                        <input class="PPRM804AuthorityCtlList ipt rdoPATTERN_1 Enter" name="radio1" value="2"
                            type="radio" />
                        <label for="">未設定</label>
                        <input class="PPRM804AuthorityCtlList ipt rdoPATTERN_2 Enter" name="radio1" value="3"
                            type="radio" />
                        <label for="">設定済み</label>
                    </td>
                </tr>
            </table>

            <table>
                <tr style="height:30px;">
                    <td colspan="4"><span class="PPRM804AuthorityCtlList LBL_MSG_STD10">
                            <input class="PPRM804AuthorityCtlList ipt chkTaisyoku Enter " checked="checked"
                                type="checkbox">
                            <label for="">退職した社員を除く</label></span></td>
                    <button class="PPRM804AuthorityCtlList btn btnView Tab" tabindex="54">
                        検索
                    </button>
                    </td>
                </tr>
            </table>

        </fieldset>
    </div>
    <div style="height: 10px;"></div>

    <table>
        <tr>
            <td>
                <div class="PPRM804AuthorityCtlList pnlLoginList">
                    <table id='PPRM804AuthorityCtlList_gvInfo'></table>
                </div>
            </td>
            <td>
                <button class='PPRM804AuthorityCtlList btn btnEdit '
                    style="width: 100px;height:25px;margin-top: 10px;display: block">
                    修正
                </button>
                <button class='PPRM804AuthorityCtlList btn btnDelete '
                    style="width: 100px;height:25px;margin-top: 10px;display: block">
                    削除
                </button>
            </td>
        </tr>
    </table>

</div>
<div id="PPRM804AuthorityCtlList_dialogs" class="PPRM804AuthorityCtlList dialogs" style="display: none;"></div>