<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM705R4BusyoSearch'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM705R4BusyoSearch.btn.disabled,
    .PPRM705R4BusyoSearch.btn[disabled],
    fieldset[disabled] .PPRM705R4BusyoSearch.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM705R4BusyoSearch.ipt.disabled,
    .PPRM705R4BusyoSearch.ipt[disabled],
    fieldset[disabled] .PPRM705R4BusyoSearch.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM705R4BusyoSearch body' id="PPRM705R4BusyoSearch">
    <div>
        <fieldset style="width:450px;">
            <legend class="PPRM705R4BusyoSearch lblSearchTitle">
                検索条件
            </legend>
            <table>
                <tr style="height:30px;">
                    <td>
                        <label class='PPRM705R4BusyoSearch lblBusyoCDLabelNM lbl-sky-xM '
                            style="text-align:left;width:100px;">店舗コード</label>
                    </td>
                    <td>
                        <input class='PPRM705R4BusyoSearch ipt txtDeployCode Enter705 Tab' style="width: 180px"
                            maxlength="8" tabindex="1">
                    </td>
                </tr>

                <tr style="height:30px;">
                    <td>
                        <label class='PPRM705R4BusyoSearch lblBusyoRKNLabelNM lbl-sky-xM '
                            style="text-align:left;width:100px;">
                            店舗略称名
                        </label>
                    </td>
                    <td>
                        <input class='PPRM705R4BusyoSearch ipt txtdeployName Enter705 Tab' style="width: 180px"
                            maxlength="30" tabindex="2">
                        &nbsp;<span class="PPRM705R4BusyoSearch Label5 ">(前方一致)</span>
                    </td>
                </tr>

                <tr style="height:30px;">
                    <td>
                        <label class='PPRM705R4BusyoSearch lblBusyoKanaLabelName lbl-sky-xM '
                            style="text-align:left;width:100px;">
                            店舗名ｶﾅ
                        </label>
                    </td>
                    <td>
                        <input class='PPRM705R4BusyoSearch ipt txtdeployKN Enter705 Tab' style="width: 180px"
                            maxlength="30" tabindex="3">
                        &nbsp;<span class="PPRM705R4BusyoSearch lable54 ">(前方一致・半角ｶﾅ)</span>
                    </td>
                </tr>

                <tr style="height:30px;">
                    <td style="width:100px"></td>
                    <td style="padding-left:50px;" align="right">
                        <button style="width:100px" class="PPRM705R4BusyoSearch btn btnView Tab"
                            tabindex="7">表示</button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div>
        <table id="PPRM705R4BusyoSearch_gvInfo5"></table>

    </div>

    <table class="PPRM705R4BusyoSearch table4" style="width:470px;" border="0">
        <tbody>
            <tr>
                <td style="width:30%; text-align:right;">
                    <button style="width:100px" class="PPRM705R4BusyoSearch btn btnSelect Tab " tabindex="8">選択</button>
                    <button style="width:100px" class="PPRM705R4BusyoSearch btn btnClose Tab" tabindex="9">戻る</button>
                </td>
            </tr>
        </tbody>
    </table>
    <input class="PPRM705R4BusyoSearch hidTKB" value="" type="hidden">


</div>