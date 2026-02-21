<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM703SyainSearch'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM703SyainSearch.btn.disabled,
    .PPRM703SyainSearch.btn[disabled],
    fieldset[disabled] .PPRM703SyainSearch.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM703SyainSearch.ipt.disabled,
    .PPRM703SyainSearch.ipt[disabled],
    fieldset[disabled] .PPRM703SyainSearch.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM703SyainSearch body' id="PPRM703SyainSearch">
    <div>
        <fieldset style="width:450px;">
            <legend class="PPRM703SyainSearch lblSearchTitle">
                検索条件
            </legend>
            <table>
                <tr style="height:30px;">
                    <td>
                        <label class='PPRM703SyainSearch lbl-sky-xM ' style="text-align:left;width:100px;">社員№</label>
                    </td>
                    <td>
                        <input class='PPRM703SyainSearch ipt txtShainnNo Enter Tab' style="width: 180px" maxlength="8"
                            tabindex="1">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td>
                        <label class='PPRM703SyainSearch lbl-sky-xM ' style="text-align:left;width:100px;">
                            社員名
                        </label>
                    </td>
                    <td>
                        <input class='PPRM703SyainSearch ipt txtShainnNM Enter Tab' style="width: 180px" maxlength="30"
                            tabindex="2">
                        &nbsp;<span class="PPRM703SyainSearch Label5 ">(前方一致)</span>
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td>
                        <label class='PPRM703SyainSearch lblBusyoKanaLabelName lbl-sky-xM '
                            style="text-align:left;width:100px;">
                            社員名カナ
                        </label>
                    </td>
                    <td>
                        <input class='PPRM703SyainSearch ipt txtShainnNM_Kana Enter Tab' style="width: 180px"
                            maxlength="30" tabindex="3">
                        &nbsp;<span class="PPRM703SyainSearch lable54 ">(前方一致)</span>
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label class='PPRM703SyainSearch lbl-sky-xM' style="text-align:left;width:100px;"> 部署 </label>
                    </td>
                    <td>
                        <input class='PPRM703SyainSearch ipt txtBusyo Enter Tab' style="width:50px" maxlength="38"
                            tabindex="4">
                        <button class='PPRM703SyainSearch btn btnSearch Tab' tabindex="5">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM703SyainSearch ipt lblBusyo Enter Tab' style="width: 150px"
                            disabled="disabled" tabindex="6" />
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td style="width:280px"></td>
                    <td style="padding-left:50px;" align="right">
                        <button style="width:100px" class="PPRM703SyainSearch btn btnHyouji Enter Tab"
                            tabindex="7">表示</button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div>
        <table id="PPRM703SyainSearch_gdvShainnBetuKG"></table>

    </div>

    <table class="PPRM703SyainSearch table4" style="width:470px;" border="0">
        <tbody>
            <tr>
                <td style="width:30%; text-align:right;">
                    <button style="width:100px" class="PPRM703SyainSearch btn btnSenntaku Enter Tab "
                        tabindex="8">選択</button>
                    <button style="width:100px" class="PPRM703SyainSearch btn btnModoru Enter Tab"
                        tabindex="9">戻る</button>
                </td>
            </tr>
        </tbody>
    </table>


</div>
<div id="PPRM703SyainSearch_dialogs" class="PPRM703SyainSearch dialogs" style="display: none;"></div>