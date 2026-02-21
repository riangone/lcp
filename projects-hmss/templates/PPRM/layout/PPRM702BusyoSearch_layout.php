<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM702BusyoSearch'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM702BusyoSearch.btn.disabled,
    .PPRM702BusyoSearch.btn[disabled],
    fieldset[disabled] .PPRM702BusyoSearch.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM702BusyoSearch.ipt.disabled,
    .PPRM702BusyoSearch.ipt[disabled],
    fieldset[disabled] .PPRM702BusyoSearch.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM702BusyoSearch body' id="PPRM702BusyoSearch">
    <div>
        <fieldset style="width:450px;">
            <legend class="PPRM702BusyoSearch lblSearchTitle">
                検索条件
            </legend>
            <table>
                <tr style="height:30px;">
                    <td>
                        <label class='PPRM702BusyoSearch lbl-sky-xM ' style="text-align:left;width:100px;">部署コード</label>
                    </td>
                    <td>
                        <input class='PPRM702BusyoSearch ipt txtDeployCode Enter702 Tab' style="width: 180px"
                            maxlength="8" tabindex="1">
                    </td>
                </tr>

                <tr style="height:30px;">
                    <td>
                        <label class='PPRM702BusyoSearch lbl-sky-xM ' style="text-align:left;width:100px;">
                            部署略称名
                        </label>
                    </td>
                    <td>
                        <input class='PPRM702BusyoSearch ipt txtdeployName Enter702 Tab' style="width: 180px"
                            maxlength="30" tabindex="2">
                        &nbsp;<span class="PPRM702BusyoSearch Label5 ">(前方一致)</span>
                    </td>
                </tr>

                <tr style="height:30px;">
                    <td>
                        <label class='PPRM702BusyoSearch lbl-sky-xM ' style="text-align:left;width:100px;">
                            部署名ｶﾅ
                        </label>
                    </td>
                    <td>
                        <input class='PPRM702BusyoSearch ipt txtdeployKN Enter702 Tab' style="width: 180px"
                            maxlength="30" tabindex="3">
                        &nbsp;<span class="PPRM702BusyoSearch lable54 ">(前方一致・半角ｶﾅ)</span>
                    </td>
                </tr>

                <tr style="height:30px;">
                    <td>
                        <label class='PPRM702BusyoSearch lbl-sky-xM ' style="text-align:left;width:100px;">
                            部署区分
                        </label>
                    </td>
                    <td class="PPRM702BusyoSearch rdo">
                        <input class="PPRM702BusyoSearch ipt rdoSin Enter702 Tab" name="radio" value="rdoSin"
                            type="radio" checked="checked" tabindex="4" />
                        <label>新車</label>
                        <input class="PPRM702BusyoSearch ipt rdoTyu Enter702 Tab" name="radio" value="rdoTyu"
                            type="radio" tabindex="5" />
                        <label>中古車</label>
                        <input class="PPRM702BusyoSearch ipt rdoSno Enter702 Tab" name="radio" value="rdoSno"
                            type="radio" tabindex="6" />
                        <label>その他</label>
                    </td>
                </tr>

                <tr style="height:30px;">
                    <td style="width:100px"></td>
                    <td style="padding-left:50px;" align="right">
                        <button style="width:100px" class="PPRM702BusyoSearch btn btnView Tab" tabindex="7">表示</button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div>
        <table id="PPRM702BusyoSearch_gvInfo5"></table>

    </div>

    <table class="PPRM702BusyoSearch table4" style="width:470px;" border="0">
        <tbody>
            <tr>
                <td style="width:30%; text-align:right;">
                    <button style="width:100px" class="PPRM702BusyoSearch btn btnSelect Tab " tabindex="8">選択</button>
                    <button style="width:100px" class="PPRM702BusyoSearch btn btnClose Tab" tabindex="9">戻る</button>
                </td>
            </tr>
        </tbody>
    </table>



</div>