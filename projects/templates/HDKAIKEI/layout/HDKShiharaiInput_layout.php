<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKShiharaiInput/HDKShiharaiInput"));
?>
<style type="text/css">
    .HDKShiharaiInput table {
        border-spacing: 2px 0px;
    }

    .HDKShiharaiInput textarea {
        height: 35px;
    }

    .HDKShiharaiInput input[type='text'] {
        width: 100px;
    }

    .HDKShiharaiInput input[readonly='true'],
    .HDKShiharaiInput.lblKensakuNM {
        background-color: #BABEC1 !important;
    }

    .HDKShiharaiInput.ddlPatternSel {
        width: 137px;
    }

    .HDKShiharaiInput.lblSyohizei {
        width: 137px !important;
    }

    .HDKShiharaiInput.lblZeink_GK {
        width: 142px !important;
    }

    .HDKShiharaiInput.lbl-sky-L {
        width: 105px;
    }

    .HDKShiharaiInput.lbl-grey-L {
        width: 115px;
    }

    .HDKShiharaiInput.Label7.lbl-grey-L,
    .HDKShiharaiInput.Label16,
    .HDKShiharaiInput.Label1,
    .HDKShiharaiInput.Label8 {
        height: 40px;
        line-height: 40px;
        padding: 0px 2px;
    }

    .HDKShiharaiInput.lbl-green-L {
        width: 105px;
        background-color: #B0E0E6;
        border: solid 1px black;
        padding: 0px 2px;
    }

    .HDKShiharaiInput.yellow-table .lbl-green-L,
    .HDKShiharaiInput.yellow-table .lbl-sky-L {
        width: 92px;
    }

    .HDKShiharaiInput.yellow-table .Label18 {
        width: 110px !important;
    }

    .HDKShiharaiInput.txtTekyo {
        width: 695px;
    }

    .HDKShiharaiInput.txtPatternNM {
        width: 168px;
    }

    .HDKShiharaiInput.txtPatternBusyo {
        width: 70px !important;
    }

    .HDKShiharaiInput.txtLKamokuCD,
    .HDKShiharaiInput.txtLKomokuCD {
        width: 43px !important;
    }

    .HDKShiharaiInput.Label10.lbl-yellow-L,
    .HDKShiharaiInput.Label11.lbl-yellow-L {
        text-align: center;
        width: 528px;
        height: 19.2px;
        padding: 0px 2px;
    }

    .HDKShiharaiInput.select2 {
        width: 220px;
    }


    .HDKShiharaiInput.lblLKamokuNM,
    .HDKShiharaiInput.lblLKomokuNM {
        width: 129px !important;
    }

    .HDKShiharaiInput.txtRBusyoCD,
    .HDKShiharaiInput.txtLBusyoCD {
        width: 145px !important;
    }

    .HDKShiharaiInput.lblLbusyoNM,
    .HDKShiharaiInput.lblRbusyoNM {
        width: 222px !important;
    }

    .HDKShiharaiInput.select1 {
        width: 205px;
    }

    .HDKShiharaiInput.select3 {
        width: 165px;
    }

    .HDKShiharaiInput.custom-width {
        width: 155px !important;
    }

    .HDKShiharaiInput.txtKensakuCD {
        width: 102px !important;
    }

    .HDKShiharaiInput.lblKensakuNM,
    .HDKShiharaiInput.lblTatekaeSyaNM {
        width: 260px !important;
    }

    .HDKShiharaiInput.btnSaishinDisp,
    .HDKShiharaiInput.btnSyuseiMaeDisp {
        float: right;
    }

    .HDKShiharaiInput.custom-radio input[type='radio'] {
        margin: 3px 1px 0px 2px !important;
    }

    .HDKShiharaiInput.label-text {
        font-size: 0.9em !important;
    }

    .HDKShiharaiInput.ml-M {
        margin-left: 12px;
    }

    .HDKShiharaiInput.ml-XL {
        margin-left: 31px;
    }

    .HDKShiharaiInput.txtSonotaGinko {
        width: 126px;
    }

    .HDKShiharaiInput.yellow-table .Label23 {
        width: 148px !important;
    }

    .HDKShiharaiInput.txtTorihikiHasseibi {
        width: 138px !important;
    }

    .HDKShiharaiInput.txtKouzaNM {
        width: 365px !important;
    }

    .HDKShiharaiInput div.HMS-button-pane {
        margin: 0px !important;
        min-height: 25px;
        margin-left: -4px !important;
    }

    .HDKShiharaiInput.btnLKamokuSearch,
    .HDKShiharaiInput.btnLBusyoSearch,
    .HDKShiharaiInput.btnRBusyoSearch,
    .HDKShiharaiInput.btnTorihikiSearch {
        width: 45px;
    }

    .HDKShiharaiInput.lblMemo {
        height: 94px;
        width: 250px;
    }

    .HDKShiharaiInput.txtKeiriSyoriDT {
        width: 130px !important;
    }

    .HDKShiharaiInput.lblZeink_GK,
    .HDKShiharaiInput.lblSyohizei {
        height: 21px;
        padding: 0px 2px;
        line-height: 21px;
        text-align: right;
    }

    /*右寄せ*/
    .HDKShiharaiInput.txtZeikm_GK,
    .HDKShiharaiInput.txtCopySyohyNo,
    .HDKShiharaiInput.lblKensu,
    .HDKShiharaiInput.lblZeikomiGoukei,
    .HDKShiharaiInput.lblSyohizeiGoukei {
        text-align: right;
    }

    .HDKShiharaiInput.txtZeikm_GK {
        width: 165px !important;
    }

    .HDKShiharaiInput.txtCopySyohyNo {
        width: 165px !important;
    }

    .HDKShiharaiInput.topBtn {
        width: 260px;
    }

    .HDKShiharaiInput.lblSyohy_no {
        width: 170px !important;
    }

    .HDKShiharaiInput.Label7 {
        line-height: normal !important;
        width: 70px !important;
        padding-top: 10px !important;
        padding-bottom: -10px !important;
    }

    .HDKShiharaiInput.Label8 {
        width: 70px !important;
    }

    .HDKShiharaiInput.fileDialog {
        margin-top: 3px !important;
        margin-bottom: 3px !important;
    }

    /*改行*/
    .HDKShiharaiInput_sprList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HDKShiharaiInput .HDKShiharaiInput_sprList table {
        border-spacing: 0px 0px;
    }

    .HDKShiharaiInput_sprList .ui-jqgrid-bdiv {
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    /* 20240507 LQS INS S */
    .HDKShiharaiInput.btnBankSearch {
        margin-left: 5px;
    }

    /* 20240507 LQS INS E */
    .HDKShiharaiInput .ui-jqgrid .ui-jqgrid-htable th,
    .HDKShiharaiInput .btnHeight {
        height: 22px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HDKShiharaiInput.lblSyohy_no {
            width: 130px !important;
        }

        .HDKShiharaiInput.txtKeiriSyoriDT {
            width: 100px !important;
        }

        .HDKShiharaiInput.ddlPatternSel {
            width: 110px;
        }

        .HDKShiharaiInput.txtCopySyohyNo,
        .HDKShiharaiInput.txtZeikm_GK {
            width: 128px !important;
        }

        .HDKShiharaiInput.lblSyohizei {
            width: 105px !important;
        }

        .HDKShiharaiInput.lblZeink_GK {
            width: 119px !important;
        }

        .HDKShiharaiInput.txtLKamokuCD,
        .HDKShiharaiInput.txtLKomokuCD {
            width: 33px !important;
        }

        .HDKShiharaiInput.btnLKamokuSearch,
        .HDKShiharaiInput.btnLBusyoSearch,
        .HDKShiharaiInput.btnRBusyoSearch,
        .HDKShiharaiInput.btnTorihikiSearch {
            width: 37px;
        }

        .HDKShiharaiInput.select1 {
            width: 175px;
        }

        .HDKShiharaiInput.select2 {
            width: 196px;
        }

        .HDKShiharaiInput.txtRBusyoCD,
        .HDKShiharaiInput.txtLBusyoCD {
            width: 119px !important;
        }

        .HDKShiharaiInput.yellow-table .Label18 {
            width: 90px !important;
        }

        .HDKShiharaiInput.yellow-table .Label23 {
            width: 112px !important;
        }

        .HDKShiharaiInput.lblLbusyoNM,
        .HDKShiharaiInput.lblRbusyoNM {
            width: 197px !important;
        }

        .HDKShiharaiInput.txtTorihikiHasseibi {
            width: 125px !important;
        }

        .HDKShiharaiInput.txtKensakuCD {
            width: 99px !important;
        }

        .HDKShiharaiInput.lbl-sky-L {
            width: 80px;
        }

        .HDKShiharaiInput input[type='text'] {
            width: 90px;
        }

        .HDKShiharaiInput.lbl-green-L {
            width: 80px;
        }

        .HDKShiharaiInput.yellow-table .lbl-green-L,
        .HDKShiharaiInput.yellow-table .lbl-sky-L {
            width: 65px;
        }

        .HDKShiharaiInput.txtTekyo {
            width: 552px;
        }

        .HDKShiharaiInput.Label10.lbl-yellow-L,
        .HDKShiharaiInput.Label11.lbl-yellow-L {
            width: 443px;
        }

        .HDKShiharaiInput.ml-M {
            margin-left: 9px;
        }

        .HDKShiharaiInput.txtKouzaNM {
            width: 325px !important;
        }

        .HDKShiharaiInput.custom-width {
            width: 149px !important;
        }

        .HDKShiharaiInput.lblLKamokuNM,
        .HDKShiharaiInput.lblLKomokuNM {
            width: 114px !important;
        }

        .HDKShiharaiInput.lblKensakuNM,
        .HDKShiharaiInput.lblTatekaeSyaNM {
            width: 217px !important;
        }

        .HDKShiharaiInput.select3 {
            width: 149px;
        }

        .HDKShiharaiInput.ml-XL {
            margin-left: 16px;
        }

        .HDKShiharaiInput.lblZeink_GK,
        .HDKShiharaiInput.lblSyohizei {
            height: 16px !important;
            line-height: 16px !important;
        }

        .HDKShiharaiInput .ui-jqgrid .ui-jqgrid-htable th,
        .HDKShiharaiInput .btnHeight {
            height: 17px;
        }

        .HDKShiharaiInput.Label7.lbl-grey-L,
        .HDKShiharaiInput.Label16,
        .HDKShiharaiInput.Label1,
        .HDKShiharaiInput.Label8 {
            height: 30px;
            padding-top: 0px !important;
        }

        .HDKShiharaiInput textarea {
            height: 25px;
        }

        .HDKShiharaiInput.lblMemo {
            height: 75px;
        }
    }
</style>

<!-- 画面個別の内容を表示 -->
<div class='HDKShiharaiInput body'>
    <div class="HDKShiharaiInput HDKAIKEI-content">
        <table>
            <tr>
                <td><label for="" class="HDKShiharaiInput KeyTableRow lblSyohy_no_NM lbl-sky-L">証憑№</label></td>
                <td>
                    <input type="text" class="HDKShiharaiInput KeyTableRow lblSyohy_no lbl-grey-L" readonly="true" />
                </td>
                <td><label for="" class="HDKShiharaiInput KeyTableRow lblKeiriSyoriDT_NM lbl-sky-L">経理処理日</label></td>
                <td>
                    <div class="HDKShiharaiInput txtKeiriSyoriDT-dateDiv">
                        <input type="text" class="HDKShiharaiInput KeyTableRow Datepicker txtKeiriSyoriDT"
                            maxlength="10" tabindex="2" />
                    </div>
                </td>
                <td><label for="" class="HDKShiharaiInput KeyTableRow lblPatternSel_NM lbl-sky-L">パターン選択</label></td>
                <td><select class="HDKShiharaiInput KeyTableRow ddlPatternSel Tab Enter"></select></td>
                <td class="HDKShiharaiInput topBtn">
                    <div class="HDKShiharaiInput HMS-button-pane first-row-div">
                        <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnSyuseiMaeDisp Enter Tab">
                            修正前表示
                        </button>
                        <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnSaishinDisp Enter Tab">
                            最新表示
                        </button>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td><label for="" class="HDKShiharaiInput CopyMotoRow Label21 lbl-green-L">コピー元証憑№</label></td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput CopyMotoRow txtCopySyohyNo  Enter Tab" tabindex="100" />
                    <button
                        class="HDKShiharaiInput CopyMotoRow HDKShiharaiInputButton btn btnCopySyohy btnHeight Enter Tab"
                        tabindex="101">
                        表示
                    </button>
                </td>
                <td colspan="4"></td>
                <td colspan="3" rowspan="4">
                    <div class="HDKShiharaiInput pnlTenpo">
                        <label for="" class="HDKShiharaiInput lblMemo lbl-grey-L"></label>
                    </div>
                    <table class="HDKShiharaiInput pnlHonbu grpPattern">
                        <tr>
                            <td rowspan="2"><label for="" class="HDKShiharaiInput Label7 lbl-grey-L">パターン
                                    <br>
                                    対象部署</label></td>
                            <td colspan="2">
                                <input type="radio" value="radPatternKyotu" checked="checked"
                                    name="HDKShiharaiInput_grpPattern"
                                    class='HDKShiharaiInput radPatternKyotu Tab Enter' tabindex="90" />
                                <label for="" class="HDKShiharaiInput radPatternKyotuLabel">共通</label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="radio" value="radPatternBusyo" name="HDKShiharaiInput_grpPattern"
                                    class='HDKShiharaiInput radPatternBusyo Tab Enter' tabindex="91" />
                                <label for="" class="HDKShiharaiInput radPatternBusyoLabel">部署指定</label>
                                <input type="text" class="HDKShiharaiInput txtPatternBusyo" maxlength="16"
                                    tabindex="92" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="HDKShiharaiInput Label8 lbl-grey-L">パターン名</label></td>
                            <td colspan="2"> <textarea class="HDKShiharaiInput txtPatternNM Tab Enter" rows="3"
                                    tabindex="93"></textarea></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="HDKShiharaiInput KingakuRow">
                <td><label for="" class="HDKShiharaiInput lblZeikm_GK_NM lbl-green-L">税込金額</label></td>
                <td>
                    <input type="text" class="HDKShiharaiInput txtZeikm_GK  Tab Enter" maxlength="17" tabindex="1" />
                </td>
                <td><label for="" class="HDKShiharaiInput lblZeink_GK_NM lbl-sky-L">税抜金額</label></td>
                <td><label for="" type="text" class="HDKShiharaiInput lblZeink_GK lbl-grey-L"></label></td>
                <td><label for="" class="HDKShiharaiInput lblSyohizei_NM lbl-sky-L">消費税金額</label></td>
                <td><label for="" type="text" class="HDKShiharaiInput lblSyohizei lbl-grey-L"></label></td>
                <td></td>
            </tr>
            <tr>
                <td><label for="" class="HDKShiharaiInput Label1 lbl-sky-L">摘要</label></td>
                <td colspan="6"><textarea class="HDKShiharaiInput txtTekyo Tab Enter" rows="3" tabindex="2"
                        placeholder="【例】（取引先）〇〇㈱　（取引日）10/1　（内容）本社ビル清掃料"></textarea>
                </td>
            </tr>
        </table>
        <table class="HDKShiharaiInput yellow-table">
            <tr>
                <td colspan="14">
                    <button class="HDKShiharaiInput HDKShiharaiInputButton btn fileDialog Enter Tab" disabled="disabled"
                        tabindex="84">添付ファイル：
                        <span class="HDKShiharaiInput hasFileFlg">なし</span>
                    </button>
                </td>
            </tr>
            <tr>
                <td colspan="6"><label for="" class="HDKShiharaiInput Label10 lbl-yellow-L">借方</label></td>
                <td width="7px"></td>
                <td colspan="7"><label for="" class="HDKShiharaiInput Label11 lbl-yellow-L">貸方</label></td>
            </tr>
            <tr>
                <td><label for="" class="HDKShiharaiInput Label2 lbl-green-L">科目</label></td>
                <td>
                    <input type="text" class="HDKShiharaiInput txtLKamokuCD Tab Enter" maxlength="5" tabindex="3" />
                </td>
                <td>
                    <input type="text" class="HDKShiharaiInput txtLKomokuCD Tab Enter" maxlength="5" tabindex="4" />
                </td>
                <td>
                    <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnLKamokuSearch btnHeight Enter Tab"
                        tabindex="5">
                        検索
                    </button>
                </td>
                <td>
                    <input type="text" class="HDKShiharaiInput lblLKamokuNM lbl-grey-L" readonly="true" />
                </td>
                <td>
                    <input type="text" class="HDKShiharaiInput lblLKomokuNM lbl-grey-L" readonly="true" />
                </td>
                <td width="8px"></td>
                <td><label for="" class="HDKShiharaiInput Label3 lbl-green-L">科目</label></td>
                <td colspan="4"><select class="HDKShiharaiInput ddlRKamokuCD Tab Enter select1" tabindex="10"></select>
                </td>
                <td colspan="2"><select class="HDKShiharaiInput ddlRKomokuCD select2 Tab Enter" tabindex="11"></select>
                </td>
            </tr>
        </table>
        <table class="HDKShiharaiInput yellow-table">
            <tr>
                <td><label for="" class="HDKShiharaiInput Label14 lbl-green-L">発生部署</label></td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput txtLBusyoCD Tab Enter" maxlength="16" tabindex="6" />
                </td>
                <td>
                    <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnLBusyoSearch btnHeight Enter Tab"
                        tabindex="7">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput lblLbusyoNM lbl-grey-L" readonly="true" />
                </td>
                <td width="7px"></td>
                <td><label for="" class="HDKShiharaiInput Label15 lbl-green-L">発生部署</label></td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput txtRBusyoCD Tab Enter" maxlength="16" tabindex="14" />
                </td>
                <td>
                    <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnRBusyoSearch btnHeight Enter Tab"
                        tabindex="15">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput lblRbusyoNM lbl-grey-L" readonly="true" />
                </td>
            </tr>
        </table>
        <table class="HDKShiharaiInput yellow-table">
            <tr>
                <td><label for="" class="HDKShiharaiInput Label4 lbl-green-L">消費税区分</label></td>
                <td colspan="3"><select class="HDKShiharaiInput ddlLSyohizeiKbn select3 Tab Enter"
                        tabindex="8"></select></td>
                <td><label for="" class="HDKShiharaiInput Label5 lbl-green-L">消費税率</label></td>
                <td><select class="HDKShiharaiInput ddlLSyohizeiritu select3 Tab Enter" tabindex="9"></select></td>
                <td width="5px"></td>
                <td><label for="" class="HDKShiharaiInput Label6 lbl-green-L">消費税区分</label></td>
                <td colspan="3"><select class="HDKShiharaiInput ddlRSyohizeiKbn select3 Tab Enter"
                        tabindex="16"></select></td>
                <td colspan="2"><label for="" class="HDKShiharaiInput Label9 lbl-green-L">消費税率</label></td>
                <td><select class="HDKShiharaiInput ddlRSyohizeiritu select3 Tab Enter" tabindex="17"></select></td>
            </tr>
            <tr>
                <!-- 20240407 LQS UPD S -->
                <!-- <td colspan="6"></td> -->
                <td><label for="" class="HDKShiharaiInput tatekae-syain Label4 lbl-green-L">社員</label></td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput tatekae-syain txtTatekaeSyaCD Tab Enter" maxlength="10"
                        tabindex="18" />
                </td>
                <td>
                    <button
                        class="HDKShiharaiInput HDKShiharaiInputButton tatekae-syain btn btnTatekaeSyaSearch btnHeight Enter Tab"
                        tabindex="18">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput tatekae-syain lblTatekaeSyaNM lbl-grey-L"
                        readonly="true" />
                </td>
                <!-- 20240407 LQS UPD E -->
                <td width="5px"></td>
                <td colspan="4">
                </td>
                <td colspan="2"><label for="" class="HDKShiharaiInput LiveLabel2 lbl-green-L">取引発生日</label></td>
                <td>
                    <input type="text" class="HDKShiharaiInput txtTorihikiHasseibi Datepicker Tab Enter" maxlength="10"
                        tabindex="20" />
                </td>
            </tr>
            <tr>
                <td colspan="6"></td>
                <td width="5px"></td>
                <td rowspan="2"><label for="" class="HDKShiharaiInput Label13 lbl-sky-L">時期</label></td>
                <td colspan="4" rowspan="2" class="HDKShiharaiInput custom-radio grpJiki">
                    <input type="radio" value="radJikiSokujitu" checked="checked" name="HDKShiharaiInput_grpJiki"
                        class='HDKShiharaiInput grpJiki radJikiSokujitu Tab Enter' tabindex="57" />
                    <label for="" class="HDKShiharaiInput radJikiSokujituLabel label-text">即日支払</label>
                    <input type="radio" value="radJikiHiduke" name="HDKShiharaiInput_grpJiki"
                        class='HDKShiharaiInput radJikiHiduke grpJiki Tab Enter' tabindex="58" />
                    <label for="" class="HDKShiharaiInput radJikiHidukeLabel label-text">日付指定</label>
                    <input type="radio" value="radJikiYokugetu" name="HDKShiharaiInput_grpJiki"
                        class='HDKShiharaiInput radJikiYokugetu grpJiki Tab Enter' tabindex="60" />
                    <label for="" class="HDKShiharaiInput radJikiYokugetuLabel label-text">1ヵ月後</label>
                </td>
                <td colspan="2" rowspan="2">
                    <div class="HDKShiharaiInput ml-M">
                        <label for="" class="HDKShiharaiInput Label12 label-text">支払予定日：</label>
                        <!-- 20240315 LQS UPD S -->
                        <!-- <input type="text" class="HDKShiharaiInput txtJikiDate Datepicker Tab Enter" tabindex="59" /> -->
                        <input type="text" class="HDKShiharaiInput txtJikiDate Datepicker5 Tab Enter" tabindex="59" />
                        <!-- 20240315 LQS UPD E -->
                    </div>
                </td>
            </tr>
            <tr>

                <td><label for="" class="HDKShiharaiInput Label4 lbl-green-L">取引先</label></td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput txtKensakuCD Tab Enter" maxlength="19" tabindex="18" />
                </td>
                <td>
                    <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnTorihikiSearch btnHeight Enter Tab"
                        tabindex="19">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput lblKensakuNM lbl-grey-L" readonly="true" />
                </td>
                <td width="5px"></td>
                <td colspan="7">
                </td>
            </tr>
            <tr>
                <td rowspan="2"><label for="" class="HDKShiharaiInput Label16 lbl-sky-L">振込先銀行</label></td>
                <td colspan="5" class="HDKShiharaiInput custom-radio grpGinko">
                    <input type="radio" value="radHiroGinko" checked="checked" name="HDKShiharaiInput_grpGinko"
                        class='HDKShiharaiInput radHiroGinko Tab Enter' tabindex="61" />
                    <label for="" class="HDKShiharaiInput radHiroGinkoLabel">（GD）銀行</label>
                    <input type="radio" value="radMomijiGinko" name="HDKShiharaiInput_grpGinko"
                        class='HDKShiharaiInput radMomijiGinko Tab Enter' tabindex="62" />
                    <label for="" class="HDKShiharaiInput radMomijiGinkoLabel">もみじ銀行</label>
                    <input type="radio" value="radShinyoKinko" name="HDKShiharaiInput_grpGinko"
                        class='HDKShiharaiInput radShinyoKinko Tab Enter' tabindex="63" />
                    <label for="" class="HDKShiharaiInput radShinyoKinkoLabel">（GD）信用金庫</label>
                    <input type="radio" value="radGinkoSonota" name="HDKShiharaiInput_grpGinko"
                        class='HDKShiharaiInput radGinkoSonota Tab Enter' tabindex="64" />
                    <label for="" class="HDKShiharaiInput radGinkoSonotaLabel">その他</label>
                    <!-- 20240507 LQS INS S -->
                    <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnBankSearch btnHeight Enter Tab"
                        tabindex="64">
                        検索
                    </button>
                    <!-- 20240507 LQS INS E -->
                </td>
                <td width="5px"></td>
                <td colspan="5" class="HDKShiharaiInput custom-radio grpSyubetu"><label for=""
                        class="HDKShiharaiInput Label18 lbl-sky-L">振込先口座番号</label>
                    <input type="radio" value="radSyubetuTouza" checked="checked" name="HDKShiharaiInput_grpSyubetu"
                        class='HDKShiharaiInput radSyubetuTouza Tab Enter' tabindex="67" />
                    <label for="" class="HDKShiharaiInput radSyubetuTouzaLabel">当座</label>
                    <input type="radio" value="radSyubetuFutu" name="HDKShiharaiInput_grpSyubetu"
                        class='HDKShiharaiInput radSyubetuFutu Tab Enter' tabindex="68" />
                    <label for="" class="HDKShiharaiInput radSyubetuFutuLabel">普通</label>
                    <input type="radio" value="radSyubetuSonota" name="HDKShiharaiInput_grpSyubetu"
                        class='HDKShiharaiInput radSyubetuSonota Tab Enter' tabindex="69" />
                    <label for="" class="HDKShiharaiInput radSyubetuSonotaLabel">その他</label>
                </td>
                <td colspan="2">
                    <div class="HDKShiharaiInput ml-XL">
                        <label for="" class="HDKShiharaiInput Label19 label-text">№</label>
                        <input type="text" class="HDKShiharaiInput txtKouzaNO Tab Enter custom-width" maxlength="7"
                            tabindex="70" />
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="text" class="HDKShiharaiInput txtSonotaGinko Tab Enter" tabindex="65" maxlength="19" />
                    <label for="" class="HDKShiharaiInput Label20">銀行</label>
                </td>
                <td colspan="2">
                    <input type="text" class="HDKShiharaiInput txtSonotaShiten Tab Enter custom-width" tabindex="66"
                        maxlength="15" />
                    <label for="" class="HDKShiharaiInput Label22">支店</label>
                </td>
                <td width="5px"></td>
                <td colspan="7"><label for="" class="HDKShiharaiInput Label23 lbl-sky-L">振込先口座名　　カナ</label>
                    <!-- 20240520 YIN UPD S -->
                    <!-- <input type="text" class="HDKShiharaiInput txtKouzaNM Tab Enter" tabindex="71" maxlength="60" /> -->
                    <!-- 20240522 YIN UPD S -->
                    <!-- <input type="text" class="HDKShiharaiInput txtKouzaNM Tab Enter" tabindex="71" maxlength="30" /> -->
                    <input type="text" class="HDKShiharaiInput txtKouzaNM Tab Enter" tabindex="71" maxlength="60" />
                    <!-- 20240522 YIN UPD E -->
                    <!-- 20240520 YIN UPD E -->
                </td>
            </tr>
        </table>
        <!-- jqgrid -->
        <div class="HDKShiharaiInput HDKShiharaiInput_sprList">
            <table class="HDKShiharaiInput sprList Enter Tab" id="HDKShiharaiInput_sprList"></table>
        </div>
        <!-- 件 -->
        <div id="GOUKEITBL" class="HDKShiharaiInput GOUKEITBL">
            <input class="HDKShiharaiInput LBL_MSG_STD10 lblKensu" readonly="true" />
            <label for="" class="HDKShiharaiInput LBL_MSG_STD10 lblKen"> 件 </label>
            <input class="HDKShiharaiInput LBL_MSG_STD10 lblZeikomiGoukei" readonly="true">
            <input class="HDKShiharaiInput LBL_MSG_STD10 lblSyohizeiGoukei" readonly="true" />
        </div>
        <div class="HMS-button-pane">
            <button class="HDKShiharaiInput HDKShiharaiInputButton BTN_STD100 btnAdd Enter Tab" tabindex="73">
                行追加
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton BTN_STD100 btnUpdate Enter Tab" tabindex="74">
                行変更
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton BTN_STD100 btnDelete Enter Tab" tabindex="75">
                行削除
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnPtnInsert Enter Tab" tabindex="76">
                登録
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnKakutei Enter Tab" tabindex="77">
                全確定
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnAllDelete Enter Tab" tabindex="78">
                全削除
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnPtnUpdate Enter Tab" tabindex="79">
                更新
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnClear Enter Tab" tabindex="80">
                クリア
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnPtnDelete Enter Tab" tabindex="81">
                削除
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnPatternTrk Enter Tab" tabindex="82">
                表示されている仕訳をパターンとして登録
            </button>
            <button class="HDKShiharaiInput HDKShiharaiInputButton btn btnClose Enter Tab" tabindex="83">
                閉じる
            </button>
        </div>
    </div>
</div>