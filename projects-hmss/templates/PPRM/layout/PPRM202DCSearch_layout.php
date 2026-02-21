<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM202DCSearch'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM202DCSearch.btn.disabled,
    .PPRM202DCSearch.btn[disabled],
    fieldset[disabled] .PPRM202DCSearch.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM202DCSearch.ipt.disabled,
    .PPRM202DCSearch.ipt[disabled],
    fieldset[disabled] .PPRM202DCSearch.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM202DCSearch body' id="PPRM202DCSearch" style="width: 60%">
    <div>
        <fieldset style="width:920px;">
            <legend>
                検索条件
            </legend>
            <table>
                <tr style="height:28px;" class="PPRM202DCSearch tr1">
                    <td><label for="" class='PPRM202DCSearch lblTitle0 lbl-sky-xM' style="width:100px;"> 対象 </label>
                    </td>
                    <td>
                        <input type="radio" value="" class='PPRM202DCSearch ipt rdbTaisyo1 Enter202 Tab' name="object"
                            checked="checked" tabindex="1">
                        事務
                    </td>
                    <td>
                        <input type="radio" value="" class='PPRM202DCSearch ipt rdbTaisyo2 Enter202 Tab' name="object"
                            tabindex="2">
                        整備
                    </td>
                    <td></td>
                </tr>
            </table>
            <table>
                <tr style="height:28px;">
                    <td><label for="" class='PPRM202DCSearch lblTitle1 lbl-sky-xM' style="width:100px;"> 店舗 </label>
                    </td>
                    <td>
                        <input class='PPRM202DCSearch ipt txtFromTenpoCD Enter202 Tab' style="width: 100px"
                            maxlength="3" tabindex="3">
                    </td>
                    <td>
                        <button class='PPRM202DCSearch btn btnFromTenpoSearch Tab' tabindex="4">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM202DCSearch ipt lblFromTenpo' style="width: 150px" disabled="disabled" />
                    </td>
                    <td>～</td>
                    <td>
                        <input class='PPRM202DCSearch ipt txtToTenpoCD Enter202 Tab' style="width: 100px" maxlength="3"
                            tabindex="5">
                    </td>
                    <td>
                        <button class='PPRM202DCSearch btn btnToTenpoSearch Tab' tabindex="6">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM202DCSearch ipt lblTenpo' style="width: 150px" disabled="disabled" />
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:28px;">
                    <td><label for="" class='PPRM202DCSearch lblTitle2 lbl-sky-xM' style="width:100px;"> 日締日 </label>
                    </td>
                    <td>
                        <input class='PPRM202DCSearch ipt txtHJMFromDate Enter202 Tab Tab3' style="width: 105px"
                            maxlength="10" tabindex="7">
                    </td>
                    <td>～</td>
                    <td>
                        <input class='PPRM202DCSearch ipt txtHJMToDate Enter202 Tab Tab3' style="width: 105px"
                            maxlength="10" tabindex="8">
                    </td>
                    <td style="padding-left:98px;"><label for="" class='PPRM202DCSearch tdlblHJM lbl-sky-xM'
                            style="width:100px;"> 日締№ </label></td>
                    <td>
                        <input class='PPRM202DCSearch ipt txtHJMNo Enter202 Tab' style="width: 130px" maxlength="12"
                            tabindex="9">
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr style="height:20px;">
                    <td align="right">
                        <button class='PPRM202DCSearch btn btnSearch Tab' tabindex="10">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <table>
        <tr>
            <td>
                <div class="PPRM202DCSearch spdList">
                    <table id="PPRM202DCSearch_spdList" class="PPRM202DCSearch PPRM202DCSearch_spdList"></table>
                </div>
                <div class="PPRM202DCSearch spdList1">
                    <table id="PPRM202DCSearch_spdList1" class="PPRM202DCSearch PPRM202DCSearch_spdList1"></table>
                </div>
            </td>
            <td>
                <button class='PPRM202DCSearch btn openHijimeOut'
                    style="width: 130px;height:25px;margin-top: 10px;display: none;">
                    日締ﾌﾟﾚﾋﾞｭｰ
                </button>
                <button class='PPRM202DCSearch btn ImgOpenFile '
                    style="width: 130px;height:25px;margin-top: 10px;display: none;">
                    イメージファイル
                </button>
                <button class='PPRM202DCSearch btn openKinsyuIn '
                    style="width: 130px;height:25px;margin-top: 10px;display: none;">
                    金種表参照
                </button>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr style="height:30px;">
            <td align="left" style="padding-left: 730px;">
                <button class='PPRM202DCSearch btn btnSelect  Tab' style="width: 80px;height:25px;" tabindex="11">
                    選択
                </button>
            </td>
            <td align="right" style="padding-right: 16px;">
                <button class='PPRM202DCSearch btn btnClose  Tab' style="width: 80px;height:25px;" tabindex="12">
                    戻る
                </button>
            </td>
        </tr>
    </table>

    <div id="PPRM705R4BusyoSearch_dialog" class="PPRM202DCSearch PPRM705R4BusyoSearch_dialog"></div>
    <div id="PPRM204_DC_Output_dialog" class="PPRM202DCSearch PPRM204_DC_Output_dialog"></div>
    <div id="PPRM203_DC_MonyKindInput_dialog" class="PPRM202DCSearch PPRM203_DC_MonyKindInput_dialog"></div>
    <div id="PPRMjpgView_dialog" class="PPRM202DCSearch PPRMjpgView_dialog"></div>
</div>