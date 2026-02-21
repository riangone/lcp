<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->css(array('PPRM/PPRM803MenuNameMstMnt'));
echo $this->Html->script(array('PPRM/PPRM803MenuNameMstMnt'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM803MenuNameMstMnt.btn.disabled,
    .PPRM803MenuNameMstMnt.btn[disabled],
    fieldset[disabled] .PPRM803MenuNameMstMnt.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM803MenuNameMstMnt.ipt.disabled,
    .PPRM803MenuNameMstMnt.ipt[disabled],
    fieldset[disabled] .PPRM803MenuNameMstMnt.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM803MenuNameMstMnt body' id="PPRM803MenuNameMstMnt">
    <label for="" class="PPRM803MenuNameMstMnt title" style="  margin-top: 3px;margin-bottom:3px;">
        メニュー名の変更及びメニューが部署や操作(ボタン)単位に権限管理するメニューかどうかを設定します
    </label>

    <table id="PPRM803MenuNameMstMnt_jqGrid" class="PPRM803MenuNameMstMnt jqGrid">
    </table>

    <button class="PPRM803MenuNameMstMnt btn btnEdit" style=" margin-right: 15px;">
        修正
    </button>

    <div class="PPRM803MenuNameMstMnt footer" style="display: none;width:83%">
        <fieldset>
            <legend>
                操作
            </legend>
            <table>
                <tr style="height:20px;">
                    <td>
                        <label for="" class='PPRM803MenuNameMstMnt lbl-sky-xM' style="width:100px">
                            メニュー名
                        </label>
                    </td>
                    <td>
                        <input class='PPRM803MenuNameMstMnt ipt Enter Tab txtProName' maxlength="50">
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td style="display:none">
                        <label for="" class='PPRM803MenuNameMstMnt lbl-sky-xM lblProNO'>
                            PRO_NO
                        </label>
                    </td>
                </tr>
                <tr style="height:20px;">
                    <td>
                        <label for="" class='PPRM803MenuNameMstMnt lbl-sky-xM' style="width:100px">
                            権限を管理する
                        </label>
                    </td>
                    <td>
                        <select class="PPRM803MenuNameMstMnt ddlUserAuthCtlFlg Enter" value="">
                            <option value="">選択してください</option>
                            <option value="0">管理しない</option>
                            <option value="1">管理する</option>
                        </select>
                    </td>
                    <td style="padding-left:170px;">
                        <button class='PPRM803MenuNameMstMnt btn Tab btnUpd'>
                            更新
                        </button>
                    </td>
                    <td>
                        <button class='PPRM803MenuNameMstMnt btn Tab btnCan'>
                            ｷｬﾝｾﾙ
                        </button>
                    </td>
                    <td style="display: none">
                        <label for="" class='PPRM803MenuNameMstMnt lbl-sky-xM lblUpdDate'>
                            更新日
                        </label>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div>
        <table>
            <tr>
                <td width="5px"></td>
                <td><label for=""><strong>部署やボタンごとに権限管理するについて</strong></label></td>
            </tr>
            <tr>
                <td></td>
                <td><label for="">&nbsp;&nbsp;部署を限定したり、操作可能なボタンを限定したい場合は「管理する」を、</label></td>
            </tr>
            <tr>
                <td></td>
                <td><label for="">&nbsp;&nbsp;自由に部署や操作を行うことが可能であれば「管理しない」を選択します。</label></td>
            </tr>
        </table>
    </div>
</div>