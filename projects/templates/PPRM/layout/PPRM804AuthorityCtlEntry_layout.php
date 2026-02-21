<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM804AuthorityCtlEntry'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM804AuthorityCtlEntry.btn.disabled,
    .PPRM804AuthorityCtlEntry.btn[disabled],
    fieldset[disabled] .PPRM804AuthorityCtlEntry.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM804AuthorityCtlEntry.ipt.disabled,
    .PPRM804AuthorityCtlEntry.ipt[disabled],
    fieldset[disabled] .PPRM804AuthorityCtlEntry.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM804AuthorityCtlEntry body' id="PPRM804AuthorityCtlEntry">
    <div>
        <fieldset style="width:900px;">
            <legend class="PPRM804AuthorityCtlEntry lblSearchTitle">
                検索条件
            </legend>
            <table>
                <tr style="height:30px;">
                    <td><label class='PPRM804AuthorityCtlEntry LBL_TITLE_STD9 lbl-sky-xM '
                            style="text-align:left;width:100px;">部署</label></td>
                    <td>
                        <input class='PPRM804AuthorityCtlEntry ipt txtDispBusyoCD ' style="width: 100px"
                            disabled="disabled" maxlength="3">
                    </td>
                    <td>
                        <input class='PPRM804AuthorityCtlEntry ipt txtDispBusyoNM ' style="width: 140px"
                            disabled="disabled">
                    </td>
                    <td style="width: 150px"></td>
                    <td><label class='PPRM804AuthorityCtlEntry LBL_TITLE_STD9 lbl-sky-xM '
                            style="text-align:left;width:100px;">配属期間</label></td>
                    <td>
                        <input class='PPRM804AuthorityCtlEntry ipt txtDispStartDate ' style="width: 100px"
                            disabled="disabled" maxlength="10">
                    </td>
                    <td>
                        ～
                    </td>
                    <td>
                        <input class='PPRM804AuthorityCtlEntry ipt txtDispEndDate ' style="width: 100px"
                            disabled="disabled" maxlength="10">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label class='PPRM804AuthorityCtlEntry LBL_TITLE_STD9 lbl-sky-xM'
                            style="text-align:left;width:100px;"> 社員</label></td>
                    <td>
                        <input class='PPRM804AuthorityCtlEntry ipt txtDispSyainNO ' style="width:100px"
                            disabled="disabled" maxlength="10">
                    </td>
                    <td>
                        <input class='PPRM804AuthorityCtlEntry ipt txtDispSyainNM ' style="width:140px"
                            disabled="disabled">
                    </td>
                </tr>
            </table>
        </fieldset>
        <table>
            <tr style="height:5px;">
                <td>
                    <font size="2"><b>登録済み部署</b></font>
                </td>
            </tr>
            <tr style="height:10px;">
                <td>
                    <font size="2">既存データを修正する場合は修正するパターン行の選択ボタンを押下してください。</font>
                </td>
            </tr>
        </table>
    </div>
    <div style="height: 10px;"></div>

    <table>
        <tr>
            <td>
                <table id='PPRM804AuthorityCtlEntry_gvRights'
                    class="PPRM804AuthorityCtlEntry PPRM804AuthorityCtlEntry_gvRights"></table>
            </td>
            <td>
                <button class='PPRM804AuthorityCtlEntry btn btnSelect '
                    style="width: 90px;height:25px;margin-top: 10px;display: block;">
                    選択
                </button>
            </td>
            <td>
                <div class="PPRM804AuthorityCtlEntry tblThirdMain">
                    <table>
                        <tr>
                            <td><label class='PPRM804AuthorityCtlEntry lblInpBusyo lbl-sky-xM'
                                    style="text-align:left;width:100px;"> 部署</label></td>
                            <td>
                                <input class='PPRM804AuthorityCtlEntry ipt txtInpBusyoCD Enter ' style="width:100px"
                                    maxlength="3">
                            </td>
                            <td>
                                <button class="PPRM804AuthorityCtlEntry btn btnSearch Tab" style="width: 100px;"
                                    tabindex="-1">
                                    検索
                                </button>
                            </td>
                            <td>
                                <input class='PPRM804AuthorityCtlEntry ipt textInpBusyoNM  Tab' style="width:100px"
                                    disabled="disabled" tabindex="-1">
                            </td>
                            <td style="height: 10px;"></td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td>
                                <table id='PPRM804AuthorityCtlEntry_gvProgramInfo'
                                    class="PPRM804AuthorityCtlEntry gvProgramInfo"></table>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td class="PPRM804AuthorityCtlEntry checkAll" style="display: none;">
                                <input type="checkbox" class="PPRM804AuthorityCtlEntry ipt chkAll Enter" />
                                全て
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td>
                <font size="2pt">新規データを追加する場合は下記追加ボタンを押下してください。</font>
            </td>
        </tr>
        <tr>
            <td>
                <button class="PPRM804AuthorityCtlEntry btn btnAdd " style="width: 100px;">
                    新規追加
                </button>
            </td>
        </tr>
        <tr style="height:25px;">
            <td align="left" style="padding-left: 655px;">
                <button class='PPRM804AuthorityCtlEntry btn btnTouroku ' style="width: 80px;height:25px;">
                    登録
                </button>
            </td>
            <td>
                <button class='PPRM804AuthorityCtlEntry btn btnDelete ' style="width: 80px;height:25px;">
                    削除
                </button>
            </td>
            <td align="right" style="padding-right: 30px;">
                <button class='PPRM804AuthorityCtlEntry btn btnBack ' style="width: 80px;height:25px;">
                    戻る
                </button>
            </td>
        </tr>
    </table>
    <div id="PPPRM702_BusyoSearch_dialog" class="PPRM804AuthorityCtlEntry PPRM702_BusyoSearch_dialog"></div>
</div>