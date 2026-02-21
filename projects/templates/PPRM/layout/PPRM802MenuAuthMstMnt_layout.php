<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->css(array('PPRM/PPRM802MenuAuthMstMnt'));
echo $this->Html->script(array('PPRM/PPRM802MenuAuthMstMnt'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM802MenuAuthMstMnt.btn.disabled,
    .PPRM802MenuAuthMstMnt.btn[disabled],
    fieldset[disabled] .PPRM802MenuAuthMstMnt.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM802MenuAuthMstMnt.ipt.disabled,
    .PPRM802MenuAuthMstMnt.ipt[disabled],
    fieldset[disabled] .PPRM802MenuAuthMstMnt.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM802MenuAuthMstMnt body' id="PPRM802MenuAuthMstMnt">
    <table class="PPRM802MenuAuthMstMnt tblMain" cellpadding="4" cellspacing="0">
        <tbody>
            <tr>
                <td style="width:610px;vertical-align: top">
                    <table class="PPRM802MenuAuthMstMnt tblSubMain" cellpadding="0" cellspacing="0">
                        <tr>
                            <td colspan="2">
                                <label for="" class="PPRM802MenuAuthMstMnt title"
                                    style=" margin-top: 10px;margin-bottom:10px;">
                                    既存データを修正する場合は修正するパターン行の選択ボタンを押下してください。
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:450px;">
                                <table id="PPRM802MenuAuthMstMnt_gvRights" class="PPRM802MenuAuthMstMnt gvRights">
                                </table>
                            </td>
                            <td class="PPRM802MenuAuthMstMnt" style="vertical-align:top;text-align:center;width:100px;">
                                <button class="PPRM802MenuAuthMstMnt btn btnSelect">
                                    選択
                                </button>
                            </td>
                        </tr>
                        <!-- 20170907 ZHANGXIAOLEI INS S -->
                        <tr>
                            <td>
                                <label for="" class="PPRM802MenuAuthMstMnt title"
                                    style=" margin-top: 2px;margin-bottom:5px;">
                                    新規データを追加する場合は下記追加ボタンを押下してください。
                                </label>
                            </td>
                        </tr>
                        <!-- 20170907 ZHANGXIAOLEI INS E -->
                        <tr>
                            <td>
                                <button class="PPRM802MenuAuthMstMnt btn btnAdd">
                                    追加
                                </button>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="padding-top:15px;vertical-align: top;">
                    <table class="PPRM802MenuAuthMstMnt tblThirdMain" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td style="width:115px;">
                                    <label for="" class='PPRM802MenuAuthMstMnt lbl-sky-xM lblRightsID'>
                                        権限ID
                                    </label>
                                </td>
                                <td>
                                    <input class='PPRM802MenuAuthMstMnt ipt Enter Tab txtRightsID' maxlength="3"
                                        style="width:50px;">
                                </td>
                            </tr>
                            <tr>
                                <td style="width:115px;">
                                    <label for="" class='PPRM802MenuAuthMstMnt lbl-sky-xM lblRightsName'>
                                        権限名
                                    </label>
                                </td>
                                <td>
                                    <input class='PPRM802MenuAuthMstMnt ipt Enter Tab txtRightsName' maxlength="50"
                                        style="width:200px;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table id="PPRM802MenuAuthMstMnt_gvProgramInfo"
                                        class="PPRM802MenuAuthMstMnt gvProgramInfo">
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top:15px;text-align: right">
                                    <button class="PPRM802MenuAuthMstMnt btn btnLogin">
                                        登録
                                    </button>
                                    <button class="PPRM802MenuAuthMstMnt btn btnDelete">
                                        削除
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>