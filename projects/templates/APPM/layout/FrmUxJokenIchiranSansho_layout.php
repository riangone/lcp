<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmUxJokenIchiranSansho'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmUxJokenIchiranSansho body' id="FrmUxJokenIchiranSansho">
    <div class="FrmUxJokenIchiranSansho header" style="width:1020px;">
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <tr style="height:40px;">
                    <td>
                        <label for="" class='FrmUxJokenIchiranSansho lbl-sky-xM' style="width:80px;text-align: center">
                            表示日
                        </label>
                    </td>
                    <td>
                        <input class='FrmUxJokenIchiranSansho Enter txtHyoJI' style="width:100px;" maxlength="10">
                    </td>
                    <td style="padding-left:30px;">
                        <label for="" class='FrmUxJokenIchiranSansho lbl-sky-xM' style="width:80px;text-align: center">
                            連携区分
                        </label>
                    </td>
                    <td>
                        <select class="FrmUxJokenIchiranSansho ddlRenKeiKbn" style="width:120px;">
                            <option></option>
                        </select>
                    </td>
                    <td style="padding-left:30px;">
                        <label for="" class='FrmUxJokenIchiranSansho lbl-sky-xM' style="width:80px;text-align: center">
                            全件送付
                        </label>
                    </td>
                    <td>
                        <input type="checkbox" class="FrmUxJokenIchiranSansho chkZenkensofuFlg"
                            style="width: 20px;height: 20px;" />
                    </td>
                    <td style="padding-left:30px;">
                        <label for="" class='FrmUxJokenIchiranSansho lbl-sky-xM' style="width:80px;text-align: center">
                            削除表示
                        </label>
                    </td>
                    <td>
                        <select class="FrmUxJokenIchiranSansho ddlDelFlg" style="width:120px;">
                            <option></option>
                        </select>
                    </td>
                </tr>
                <tr style="height:40px;">
                    <td>
                        <label for="" class='FrmUxJokenIchiranSansho lbl-sky-xM' style="width:80px;text-align: center">
                            メッセージ
                        </label>
                    </td>
                    <td colspan="5">
                        <input class='FrmUxJokenIchiranSansho Enter txtMesseJi' style="width:100%">
                    </td>
                    <td></td>
                    <td>
                        <button class='FrmUxJokenIchiranSansho Enter Tab btnSearch'>
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="FrmUxJokenIchiranSansho buttonGroup" style="margin:10px 0px;">
        <table>
            <tr>
                <td>
                    <button class='FrmUxJokenIchiranSansho Enter Tab btnReference'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        参照
                    </button>
                </td>
                <td>
                    <button class='FrmUxJokenIchiranSansho Enter Tab btnSign'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        新規作成
                    </button>
                </td>
                <td>
                    <button class='FrmUxJokenIchiranSansho Enter Tab btnUpdate'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        変更
                    </button>
                </td>
                <td>
                    <button class='FrmUxJokenIchiranSansho Enter Tab btnDel'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        削除
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <div class="FrmUxJokenIchiranSansho listView">
        <table id="FrmUxJokenIchiranSansho_jqGrid" class="FrmUxJokenIchiranSansho jqGrid">
        </table>
        <div id="FrmUxJokenIchiranSansho_pager"></div>
    </div>
    <div id="dialogsToroku" class="FrmUxJokenIchiranSansho dialogsToroku" style="display: none;">
    </div>
</div>
