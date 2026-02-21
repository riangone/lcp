<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmOshiraseJokenIchiranSansho'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmOshiraseJokenIchiranSansho body' id="FrmOshiraseJokenIchiranSansho">
    <div class="FrmOshiraseJokenIchiranSansho header" style="width:1030px;">
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <tr style="height:40px;">
                    <td><label for="" class='FrmOshiraseJokenIchiranSansho lbl-sky-xM'
                            style="width:80px;text-align: center">
                            表示日 </label></td>
                    <td>
                        <input class='FrmOshiraseJokenIchiranSansho Enter txtHyoJiFrom' style="width:100px;"
                            maxlength="10" />
                    </td>
                    <td align="center" width="30px" align="center"><label for=""
                            class="FrmOshiraseJokenIchiranSansho Label1">
                            ～ </label></td>
                    <td>
                        <input class="FrmOshiraseJokenIchiranSansho Tab Enter txtHyoJiTo" style="width:100px;"
                            maxlength="10" />
                    </td>
                    <td style="padding-left:30px;"><label for="" class='FrmOshiraseJokenIchiranSansho lbl-sky-xM'
                            style="width:80px;text-align: center"> 連携区分 </label></td>
                    <td>
                        <select class="FrmOshiraseJokenIchiranSansho ddlRenKeiKbn" style="width:80px;">
                            <option></option>
                        </select>
                    </td>
                    <td style="padding-left:30px;"><label for="" class='FrmOshiraseJokenIchiranSansho lbl-sky-xM'
                            style="width:80px;text-align: center"> 全件送付 </label></td>
                    <td>
                        <input type="checkbox" class="FrmOshiraseJokenIchiranSansho chkZenkensofuFlg"
                            style="width: 20px;height: 20px;" />
                    </td>
                    <td style="padding-left:30px;"><label for="" class='FrmOshiraseJokenIchiranSansho lbl-sky-xM'
                            style="width:80px;text-align: center"> 削除表示 </label></td>
                    <td>
                        <select class="FrmOshiraseJokenIchiranSansho ddlDelFlg" style="width:80px;">
                            <option></option>
                        </select>
                    </td>
                </tr>
                <tr style="height:40px;">
                    <td><label for="" class='FrmOshiraseJokenIchiranSansho lbl-sky-xM'
                            style="width:80px;text-align: center">
                            メッセージ </label></td>
                    <td colspan="5">
                        <input class='FrmOshiraseJokenIchiranSansho Enter txtMesseJi' style="width:100%" maxlength="50">
                    </td>
                    <td colspan="3"></td>
                    <td>
                        <button class='FrmOshiraseJokenIchiranSansho Enter Tab btnSearch'>
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="FrmOshiraseJokenIchiranSansho buttonGroup" style="margin:10px 0px;">
        <table>
            <tr>
                <td>
                    <button class='FrmOshiraseJokenIchiranSansho Enter Tab btnReference'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        参照
                    </button>
                </td>
                <td>
                    <button class='FrmOshiraseJokenIchiranSansho Enter Tab btnSign'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        新規作成
                    </button>
                </td>
                <td>
                    <button class='FrmOshiraseJokenIchiranSansho Enter Tab btnUpdate'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        変更
                    </button>
                </td>
                <td>
                    <button class='FrmOshiraseJokenIchiranSansho Enter Tab btnDel'
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        削除
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <div class="FrmOshiraseJokenIchiranSansho listView">
        <table id="FrmOshiraseJokenIchiranSansho_jqGrid" class="FrmOshiraseJokenIchiranSansho jqGrid"></table>
        <div id="FrmOshiraseJokenIchiranSansho_pager"></div>
    </div>
    <div id="FrmOshiraseJokenIchiranSanshodialogs"></div>
</div>
