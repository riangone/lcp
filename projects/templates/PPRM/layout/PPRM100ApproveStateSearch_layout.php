<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM100ApproveStateSearch'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM100ApproveStateSearch.btn.disabled,
    .PPRM100ApproveStateSearch.btn[disabled],
    fieldset[disabled] .PPRM100ApproveStateSearch.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM100ApproveStateSearch.ipt.disabled,
    .PPRM100ApproveStateSearch.ipt[disabled],
    fieldset[disabled] .PPRM100ApproveStateSearch.ipt {
        background-color: #BABEC1 !important
    }

    .PPRM100ApproveStateSearch.btn.btnKinsyuInput {
        float: left;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .PPRM100ApproveStateSearch .trheight {
            height: 15px !important;
        }

        .PPRM100ApproveStateSearch .tdlblSetsumei {
            margin-top: -5px !important;
        }
        .PPRM100ApproveStateSearch .fontSize {
            font-size:6pt !important;
        }
        .PPRM100ApproveStateSearch .lblTitle6 {
            height:39px !important;
            line-height:40px !important;
        }

    }
</style>
<div class='PPRM100ApproveStateSearch' id="PPRM100ApproveStateSearch" style="width: 1000px">
    <div>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table class="PPRM100ApproveStateSearch table1" width="100%">
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td width="100px"><label for="" class='PPRM100ApproveStateSearch lblTitle0 lbl-sky-xM'
                            style="width:100px;"> 対象 </label></td>
                    <td align="left">
                        <input type="radio" value="" name="Taisyo"
                            class='PPRM100ApproveStateSearch ipt rdbTaisyo1 Tab Enter' checked="checked" tabindex="1">
                        事務
                        <input type="radio" value="" name="Taisyo"
                            class='PPRM100ApproveStateSearch ipt rdbTaisyo2 Tab Enter' tabindex="2">
                        整備
                    </td>
                </tr>
            </table>
            <table class="PPRM100ApproveStateSearch table2">
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td><label for="" class='PPRM100ApproveStateSearch lblTitle1 lbl-sky-xM' style="width:100px;"> 店舗
                        </label>
                    </td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt txtFromTenpoCD Tab Enter' style="width:120px"
                            maxlength="3" tabindex="3">
                    </td>
                    <td>
                        <button class='PPRM100ApproveStateSearch btn btnFromTenpoSearch Tab Enter' tabindex="4">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt lblFromTenpo' style="width: 170px"
                            disabled="disabled" />
                    </td>
                    <td></td>
                    <td>～</td>
                    <td></td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt txtToTenpoCD Tab Enter' style="width:120px"
                            maxlength="3" tabindex="5">
                    </td>
                    <td>
                        <button class='PPRM100ApproveStateSearch btn btnToTenpoSearch Tab Enter' tabindex="6">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt lblToTenpo' style="width: 170px"
                            disabled="disabled" />
                    </td>
                </tr>
            </table>
            <table class="PPRM100ApproveStateSearch table3">
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td><label for="" class='PPRM100ApproveStateSearch lblTitle2 lbl-sky-xM' style="width:100px;"> 日締日
                        </label>
                    </td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt txtHJMFromDate Tab Enter' style="width: 100px"
                            maxlength="10" tabindex="7">
                    </td>
                    <td>～</td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt txtHJMToDate Tab Enter' style="width: 100px"
                            maxlength="10" tabindex="8">
                    </td>
                    <td style="width: 150px"></td>
                    <td><label for="" class='PPRM100ApproveStateSearch lblTitle3 lbl-sky-xM' style="width:100px;"> 日締№
                        </label>
                    </td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt txtHJMNo Tab Enter' style="width: 150px"
                            maxlength="12" tabindex="9">
                    </td>
                    <td>
                        <button class='PPRM100ApproveStateSearch btn btnHJMSearch Tab Enter' tabindex="10">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
            <table class="PPRM100ApproveStateSearch table4" width="100%">
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td width="100px"><label for="" class='PPRM100ApproveStateSearch lblTitle4 lbl-sky-xM'
                            style="width:100px;"> 登録状態 </label></td>
                    <td align="left" class="PPRM100ApproveStateSearch rdo1">
                        <input type="radio" value="rdbJyoutai1" name="Jyoutai"
                            class='PPRM100ApproveStateSearch ipt rdbJyoutai1 Tab Enter' checked="checked" tabindex="11">
                        指定なし
                        <input type="radio" value="rdbJyoutai2" name="Jyoutai"
                            class='PPRM100ApproveStateSearch ipt rdbJyoutai2 Tab Enter' tabindex="12">
                        日締データ有り・金種表登録済み
                        <input type="radio" value="rdbJyoutai3" name="Jyoutai"
                            class='PPRM100ApproveStateSearch ipt rdbJyoutai3 Tab Enter' tabindex="13">
                        日締データ有り・金種表未登録
                        <input type="radio" value="rdbJyoutai4" name="Jyoutai"
                            class='PPRM100ApproveStateSearch ipt rdbJyoutai4 Tab Enter' tabindex="14">
                        日締データ無し・金種表登録済み
                    </td>
                </tr>
            </table>
            <table class="PPRM100ApproveStateSearch table5" width="100%">
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td width="100px"><label for="" class='PPRM100ApproveStateSearch lblTitle5 lbl-sky-xM'
                            style="width:100px;"> 確認状況 </label></td>
                    <td class="PPRM100ApproveStateSearch rdo2">
                        <select class="PPRM100ApproveStateSearch ddlKakunin Enter Tab" tabindex="15">
                            <option value="0">経理担当</option>
                            <option value="1">店長</option>
                            <option value="2">課長</option>
                            <option value="3">担当</option>
                        </select>
                        <input type="radio" value="rdbJyokyo1" name="Jyokyo"
                            class='PPRM100ApproveStateSearch ipt rdbJyokyo1 Enter Tab' checked="checked" tabindex="16">
                        未
                        <input type="radio" value="rdbJyokyo2" name="Jyokyo"
                            class='PPRM100ApproveStateSearch ipt rdbJyokyo2 Enter Tab' tabindex="17">
                        済
                        <input type="radio" value="rdbJyokyo3" name="Jyokyo"
                            class='PPRM100ApproveStateSearch ipt rdbJyokyo3 Enter Tab' tabindex="18">
                        指定なし
                    </td>
                </tr>
            </table>
            <table class="PPRM100ApproveStateSearch table6">
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td rowspan="2" width="100px" align="center"><label for=""
                            class='PPRM100ApproveStateSearch lblTitle6 lbl-sky-xM '
                            style="width:100px;height:55px;line-height:55px"> 確認者</label></td>
                    <td align="left" colspan="6" class="PPRM100ApproveStateSearch rdo3">
                        <input type="radio" value="rdbKakunin1" name="Kakunin"
                            class='PPRM100ApproveStateSearch ipt rdbKakunin1 Enter Tab' checked="checked" tabindex="19">
                        経理担当
                        <input type="radio" value="rdbKakunin2" name="Kakunin"
                            class='PPRM100ApproveStateSearch ipt rdbKakunin2 Enter Tab' tabindex="20">
                        店長
                        <input type="radio" value="rdbKakunin3" name="Kakunin"
                            class='PPRM100ApproveStateSearch ipt rdbKakunin3 Enter Tab' tabindex="21">
                        課長
                        <input type="radio" value="rdbKakunin4" name="Kakunin"
                            class='PPRM100ApproveStateSearch ipt rdbKakunin4 Enter Tab' tabindex="22">
                        担当
                    </td>
                </tr>
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt txtFromSyainCD Enter Tab' style="width:120px"
                            maxlength="5" tabindex="23">
                    </td>
                    <td>
                        <button class='PPRM100ApproveStateSearch btn btnFromSyainCDSearch Tab Enter' tabindex="24">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt lblFromSyain' style="width: 170px"
                            disabled="disabled" />
                    </td>
                    <td></td>
                    <td>～</td>
                    <td></td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt txtToSyainCD Enter Tab' style="width:120px"
                            maxlength="5" tabindex="25">
                    </td>
                    <td>
                        <button class='PPRM100ApproveStateSearch btn btnToSyainCDSearch Tab Enter' tabindex="26">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM100ApproveStateSearch ipt lblToSyain' style="width: 170px"
                            disabled="disabled" />
                    </td>
                </tr>
            </table>
            <table width="100%" class="PPRM100ApproveStateSearch table7">
                <tr class="PPRM100ApproveStateSearch trheight" style="height:25px;">
                    <td width="131px">
                        <button class='PPRM100ApproveStateSearch btn btnKinsyuInput Tab Enter' tabindex="27">
                            当日分金種表入力
                        </button>
                    </td>
                    <td align="right">
                        <button class='PPRM100ApproveStateSearch btn btnSearch Tab Enter' tabindex="28">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <table class="PPRM100ApproveStateSearch jqgrid1" style="margin-top: 0px">
            <tr>
                <td rowspan="5">
                    <div class="PPRM100ApproveStateSearch List1">
                        <table id="PPRM100ApproveStateSearch_jqGrid1"
                            class="PPRM100ApproveStateSearch PPRM100ApproveStateSearch_jqGrid1"></table>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="height: 50px"></td>
            </tr>
            <tr>
                <td>
                    <button class='PPRM100ApproveStateSearch btn btnEditOrDelete Tab' style="width: 100px;">
                        編集・削除
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <button class='PPRM100ApproveStateSearch btn btnSyonin Tab' style="width: 100px;margin-top: 15px;">
                        承認を行う
                    </button>
                </td>
            </tr>
            <tr>
                <td style="height: 50px"></td>
            </tr>
        </table>
        <table class="PPRM100ApproveStateSearch jqgrid2" style="margin-top: 2px">
            <tr>
                <td>
                    <div class="PPRM100ApproveStateSearch List2">
                        <table id="PPRM100ApproveStateSearch_jqGrid2"
                            class="PPRM100ApproveStateSearch PPRM100ApproveStateSearch_jqGrid2"></table>
                    </div>
                </td>
                <td>
                    <button class='PPRM100ApproveStateSearch btn btnSyonin1 Tab' style="width: 100px;margin-top: 15px">
                        承認を行う
                    </button>
                </td>
            </tr>
        </table>
        <table id="tdlblSetsumei" class="PPRM100ApproveStateSearch tdlblSetsumei">
            <tr>
                <td rowspan="2" style=" background-color :#ff3300; width:30px; border:solid 1px Black; font-size :8pt;">
                </td>
                <td class="PPRM100ApproveStateSearch fontSize" style="font-size:8pt"> 日締データの前回営業現金残高と今回営業現金残高が </td>
                <td rowspan="2" style="width:3px"></td>
                <td rowspan="2" style=" background-color :#6699ff; width:30px; border:solid 1px Black; font-size :8pt;">
                </td>
                <td class="PPRM100ApproveStateSearch fontSize" rowspan="1" style="font-size:8pt"> 金種表の登録はされているが </td>
                <td rowspan="2" style="width:3px"></td>
                <td rowspan="2" style=" background-color :#ffff00; width:30px; border:solid 1px Black; font-size :8pt;">
                </td>
                <td class="PPRM100ApproveStateSearch fontSize" rowspan="2" style="font-size:8pt"> 日締データと金種表入力の営業現金残高が一致していない </td>
            </tr>
            <tr>
                <td class="PPRM100ApproveStateSearch fontSize" style="font-size:8pt"> 一致しないデータだが、金種表が登録されていない </td>
                <td class="PPRM100ApproveStateSearch fontSize" style="font-size:8pt"> 日締データが存在しない </td>
            </tr>
        </table>
    </div>

    <div id="PPRM100ApproveStateSearch_dialogs" class="PPRM100ApproveStateSearch dialogs" style="display: none;"></div>

</div>
