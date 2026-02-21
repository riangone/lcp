<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmMessejiIchiranSansho'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 20170505 WANG UPD S -->
<!-- <div class='FrmMessejiIchiranSansho body' id="FrmMessejiIchiranSansho" style="width: 1000px;"> -->
<div class='FrmMessejiIchiranSansho body' id="FrmMessejiIchiranSansho" style="width: 1020px;">
    <!-- 20170505 WANG UPD E -->
    <fieldset>
        <legend>
            検索条件
        </legend>
        <table>
            <tr style="height:40px;" class="FrmMessejiIchiranSansho tr1">
                <td><label for="" class='FrmMessejiIchiranSansho lblTitle2 lbl-sky-xM'
                        style="text-align:center;width:100px;">
                        利用日 </label></td>
                <td>
                    <input class='FrmMessejiIchiranSansho txtDate Enter Tab Tab3' style="width: 105px">
                </td>
                <td><label for="" class="FrmMessejiIchiranSansho content lbl-sky-xM"
                        style="text-align: center;width: 100px;">内容区分 </label></td>
                <td>
                    <select style="width: 110px;" class="FrmMessejiIchiranSansho selContent">
                        <option></option>
                    </select>
                </td>
                <td><label for="" class="FrmMessejiIchiranSansho lianxie lbl-sky-xM"
                        style="text-align: center;width: 100px;">連携区分 </label></td>
                <td>
                    <select style="width: 110px;" class="FrmMessejiIchiranSansho sellianxie">
                        <option></option>
                    </select>
                </td>
                <td><label for="" class="FrmMessejiIchiranSansho delete lbl-sky-xM"
                        style="text-align: center;width: 100px;">削除表示 </label></td>
                <td>
                    <select style="width: 110px;" class="FrmMessejiIchiranSansho txtDelete">
                        <option></option>
                    </select>
                </td>
            </tr>
        </table>
        <table>
            <tr style="height: 5px;"></tr>
            <tr>
                <td><label for="" class='FrmMessejiIchiranSansho lblTarget lbl-sky-xM'
                        style="text-align:center;width:100px;">メッセージ </label></td>
                <td>
                    <input class="FrmMessejiIchiranSansho tags" style="width: 350px;" />
                </td>
                <td style="padding-left: 363px;">
                    <button class="FrmMessejiIchiranSansho msgSearch Enter Tab" style="width: 80px;">
                        検索
                    </button>
                </td>
            </tr>
            <tr style="height: 5px;"></tr>
        </table>
    </fieldset>
    <div class="FrmMessejiIchiranSansho buttonGroup" style="margin:10px 0px;">
        <table>
            <tr>
                <td>
                    <button class="FrmMessejiIchiranSansho btnCan Enter Tab"
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        参照
                    </button>
                </td>
                <td>
                    <button class="FrmMessejiIchiranSansho btnNew Enter Tab"
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        新規作成
                    </button>
                </td>
                <td>
                    <button class="FrmMessejiIchiranSansho btnEdit Enter Tab"
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        変更
                    </button>
                </td>
                <td>
                    <button class="FrmMessejiIchiranSansho btnDelete Enter Tab"
                        style="height:24px;line-height:24px;padding:0 1.2em;">
                        削除
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="FrmMessejiIchiranSansho spdList">
    <table id="FrmMessejiIchiranSansho_spdList" class="FrmMessejiIchiranSansho FrmMessejiIchiranSansho_spdList"></table>
    <div id="FrmMessejiIchiranSansho_pager"></div>
</div>

<div id="FrmMessejiIchiranSansho_FrmMessejiToroku" class="FrmMToroku_dialog"></div>
