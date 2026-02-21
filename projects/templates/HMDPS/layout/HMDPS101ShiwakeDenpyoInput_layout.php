<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS101ShiwakeDenpyoInput/HMDPS101ShiwakeDenpyoInput"));
?>
<style type="text/css">
    .HMDPS101ShiwakeDenpyoInput input[type='text'] {
        width: 120px;
    }

    .HMDPS101ShiwakeDenpyoInput.HMDPS101ShiwakeDenpyoInput_sprList {
        margin: 0px 5px;
    }

    .HMDPS101ShiwakeDenpyoInput .borderSpace {
        border-spacing: 2px 0px;
    }

    .HMDPS101ShiwakeDenpyoInput textarea {
        height: 42px;
    }

    .HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm,
    .HMDPS101ShiwakeDenpyoInput.ddlPatternSel {
        width: 150px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT {
        width: 130px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.lblMemo {
        height: 73px;
        width: 290px;
    }

    .HMDPS101ShiwakeDenpyoInput.lblZeink_GK,
    .HMDPS101ShiwakeDenpyoInput.lblSyohizei {
        height: 21px;
        padding: 0px 2px;
        line-height: 21px;
        width: 145px !important;
        text-align: right;
    }

    .HMDPS101ShiwakeDenpyoInput.txtPatternBusyo {
        width: 70px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.txtPatternNM {
        width: 157px;
        height: 19.2px;
    }

    .HMDPS101ShiwakeDenpyoInput.txtTekyo {
        width: calc(100% - 5px);
    }

    .HMDPS101ShiwakeDenpyoInput#Label10,
    .HMDPS101ShiwakeDenpyoInput#Label11 {
        text-align: center;
        width: 547px;
        height: 19.2px;
        padding: 0px 2px;
    }

    .HMDPS101ShiwakeDenpyoInput.txtLKamokuCD,
    .HMDPS101ShiwakeDenpyoInput.txtLKomokuCD,
    .HMDPS101ShiwakeDenpyoInput.txtRKamokuCD,
    .HMDPS101ShiwakeDenpyoInput.txtRKomokuCD {
        width: 52px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.lblRbusyoNM,
    .HMDPS101ShiwakeDenpyoInput.lblLKamokuNM,
    .HMDPS101ShiwakeDenpyoInput.lblLbusyoNM,
    .HMDPS101ShiwakeDenpyoInput.lblRKamokuNM {
        width: 264px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.lblKensakuCD {
        width: 105px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.clearLabel {
        height: 21.6px;
        display: block;
    }

    .HMDPS101ShiwakeDenpyoInput.lblKensakuNM {
        width: 700px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn,
    .HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn,
    .HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn,
    .HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn {
        width: 170px;
    }

    .HMDPS101ShiwakeDenpyoInput.lblZeikm_GK_NM {
        width: 105px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN {
        width: 160px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.lblSyohy_no,
    .HMDPS101ShiwakeDenpyoInput.txtZeikm_GK {
        width: 155px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.lblOkyakuNOTorihikisakiNm {
        width: 257px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.Tekiyo {
        height: 45px;
        line-height: 45px;
        padding-top: 2px;
    }

    .HMDPS101ShiwakeDenpyoInput.lbl-grey-L {
        width: 115px;
    }

    .HMDPS101ShiwakeDenpyoInput.lbl-sky-L {
        width: 105px;
    }

    .HMDPS101ShiwakeDenpyoInput.radPatternKyotu_label {
        height: 45px;
        line-height: 45px;
    }

    .HMDPS101ShiwakeDenpyoInput input[readonly='true'] {
        background-color: #BABEC1 !important;
    }

    /*灰色背景*/
    .HMDPS101ShiwakeDenpyoInput.CELL_TITLE_GLAY_L {
        background-color: #BABEC1;
        border: solid 1px black;
        padding: 0px 2px;
    }

    .HMDPS101ShiwakeDenpyoInput.CELL_TITLE_GLAY_L_width {
        width: 115px;
    }

    .HMDPS101ShiwakeDenpyoInput.btnSyainSearch,
    .HMDPS101ShiwakeDenpyoInput.btnTorihikiSearch {
        min-width: 50px;
        width: 105px !important;
    }

    /*修正前表示,最新表示*/
    .HMDPS101ShiwakeDenpyoInput.btnSaishinDisp,
    .HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp {
        float: right;
    }

    /*蓝色背景*/
    .HMDPS101ShiwakeDenpyoInput.CELL_TITLE_HISU_BLUE_L {
        background-color: #B0E0E6;
        border: solid 1px black;
        padding: 0px 2px;
        width: 92px;
    }

    /*按钮不换行*/
    .HMDPS101ShiwakeDenpyoInput.nowrap {
        white-space: nowrap;
    }

    /*右寄せ*/
    .HMDPS101ShiwakeDenpyoInput.TXT_RIGHT,
    .HMDPS101ShiwakeDenpyoInput.lblKensu,
    .HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei,
    .HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei {
        text-align: right;
    }

    /*label*/
    .HMDPS101ShiwakeDenpyoInput.clearLabel {
        width: 92px;
    }

    /*口座キー*/
    .HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet2,
    .HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet {
        width: 165px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.GOUKEITBL {
        margin: 0px 5px 0px 5px;
    }

    .HMDPS101ShiwakeDenpyoInput div.HMS-button-pane {
        margin: 0px !important;
        min-height: 25px;
    }

    /*改行*/
    .HMDPS101ShiwakeDenpyoInput_sprList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMDPS101ShiwakeDenpyoInput.lblSyohizei {
        width: 145px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.align-bottom {
        vertical-align: bottom !important;
    }

    .HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya {
        width: 430px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo {
        width: 144px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN {
        width: 174px !important;
    }

    .HMDPS101ShiwakeDenpyoInput.lblOkyakusamaNOTorihikisaki {
        font-size: 0.95em;
    }

    .HMDPS101ShiwakeDenpyoInput .ui-jqgrid .ui-jqgrid-htable th {
        height: 22px !important;
    }

    .HMDPS101ShiwakeDenpyoInput #gbox_HMDPS101ShiwakeDenpyoInput_sprList {
        padding: 0.7ex;
    }

    .HMDPS101ShiwakeDenpyoInput.trHeight50 {
        height: 50px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMDPS101ShiwakeDenpyoInput.lblZeink_GK,
        .HMDPS101ShiwakeDenpyoInput.lblSyohizei {
            height: 16px;
            line-height: 16px;
            width: 120px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.radPatternKyotu_label {
            height: 40px;
            line-height: 40px;
        }

        .HMDPS101ShiwakeDenpyoInput textarea {
            height: 34px;
        }

        .HMDPS101ShiwakeDenpyoInput.Tekiyo {
            height: 38px;
            line-height: 38px;
        }

        .HMDPS101ShiwakeDenpyoInput.lbl-sky-L {
            width: 80px;
        }

        .HMDPS101ShiwakeDenpyoInput.lblZeikm_GK_NM {
            width: 80px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.lblSyohy_no,
        .HMDPS101ShiwakeDenpyoInput.txtZeikm_GK {
            width: 130px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN {
            width: 136px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT {
            width: 105px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm,
        .HMDPS101ShiwakeDenpyoInput.ddlPatternSel {
            width: 125px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.lblOkyakuNOTorihikisakiNm {
            width: 207px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.txtPatternNM {
            width: 139px;
            height: 11.2px;
        }

        .HMDPS101ShiwakeDenpyoInput.lblKensakuCD {
            width: 80px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya {
            width: 356px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo {
            width: 119px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.lblKensakuNM {
            width: 579px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.btnSyainSearch,
        .HMDPS101ShiwakeDenpyoInput.btnTorihikiSearch {
            min-width: 50px;
            width: 85px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.CELL_TITLE_GLAY_L_width {
            width: 90px;
        }

        .HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN {
            width: 156px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.trHeight50 {
            height: 41px;
        }

        .HMDPS101ShiwakeDenpyoInput.lblMemo {
            height: 60px;
            width: 244px;
        }

        .HMDPS101ShiwakeDenpyoInput.CELL_TITLE_HISU_BLUE_L {
            width: 80px;
        }

        .HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn,
        .HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn,
        .HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn,
        .HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn {
            width: 136px;
        }

        .HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet2,
        .HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet {
            width: 130px !important;
        }

        .HMDPS101ShiwakeDenpyoInput.txtLKamokuCD,
        .HMDPS101ShiwakeDenpyoInput.txtLKomokuCD,
        .HMDPS101ShiwakeDenpyoInput.txtRKamokuCD,
        .HMDPS101ShiwakeDenpyoInput.txtRKomokuCD {
            width: 40px !important;
        }

        .HMDPS101ShiwakeDenpyoInput input[type='text'] {
            width: 92px;
        }

        .HMDPS101ShiwakeDenpyoInput.lblRbusyoNM,
        .HMDPS101ShiwakeDenpyoInput.lblLKamokuNM,
        .HMDPS101ShiwakeDenpyoInput.lblLbusyoNM,
        .HMDPS101ShiwakeDenpyoInput.lblRKamokuNM {
            width: 218px !important;
        }

        .HMDPS101ShiwakeDenpyoInput#Label10,
        .HMDPS101ShiwakeDenpyoInput#Label11 {
            width: 451px;
            height: 16.2px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMDPS101ShiwakeDenpyoInput body">
    <div class="HMDPS101ShiwakeDenpyoInput HMDPS-content">
        <table class="borderSpace">
            <tr class="HMDPS101ShiwakeDenpyoInput HMS-button-pane KeyTableRow">
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblSyohy_no_NM lbl-sky-L "> 証憑№
                    </label>
                </td>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput lbl-grey-L lblSyohy_no Enter Tab" type="text"
                        readonly="true" />
                </td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblKeiriSyoriDT_NM lbl-sky-L"> 経理処理日
                    </label></td>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtKeiriSyoriDT Enter Tab" type="text" />
                </td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblPatternSel_NM lbl-sky-L"> パターン選択
                    </label>
                </td>
                <td><select class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD9 ddlPatternSel Enter Tab"></select></td>
                <td colspan="3">
                    <button
                        class="HMDPS101ShiwakeDenpyoInput BTN_STD80 btnSyuseiMaeDisp HMDPS101ShiwakeDenpyoInputBtn Enter Tab">
                        修正前表示
                    </button>
                    <button
                        class="HMDPS101ShiwakeDenpyoInput BTN_STD80 btnSaishinDisp HMDPS101ShiwakeDenpyoInputBtn Enter Tab">
                        最新表示
                    </button>
                </td>
            </tr>
            <tr>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput KingakuRow LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L lblZeikm_GK_NM">
                        税込金額 </label></td>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 TXT_RIGHT KingakuRow txtZeikm_GK Enter Tab"
                        type="text" tabindex="1" maxlength="17" />
                </td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblZeink_GK_NM KingakuRow lbl-sky-L">
                        税抜金額
                    </label></td>
                <td><label for="" type="text"
                        class="HMDPS101ShiwakeDenpyoInput lbl-grey-L lblZeink_GK KingakuRow Enter Tab" type="text"
                        disabled="disabled"></label></td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput lblSyohizei_NM KingakuRow lbl-sky-L"> 消費税金額 </label>
                </td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput lbl-grey-L lblSyohizei KingakuRow Enter Tab"
                        disabled="disabled"></label></td>
                <!-- パターン対象部署№ -->
                <td rowspan="2" colspan="3">
                    <div class="HMDPS101ShiwakeDenpyoInput pnlTenpo">
                        <label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_GLAY_L lblMemo">
                        </label>
                    </div>
                    <table class="HMDPS101ShiwakeDenpyoInput pnlHonbu borderSpace">
                        <tr>
                            <td rowspan="2"><label for=""
                                    class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_GLAY_L CELL_TITLE_GLAY_L_width radPatternKyotu_label">
                                    パターン対象部署 </label></td>
                            <td colspan="2">
                                <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 radPatternKyotu Enter Tab"
                                    name="HMDPS101ShiwakeDenpyoInput_radio" type="radio" tabindex="66" />
                                <label for="" class="HMDPS101ShiwakeDenpyoInput">共通</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 radPatternBusyo Enter Tab"
                                    name="HMDPS101ShiwakeDenpyoInput_radio" type="radio" tabindex="67" />
                                <label for="" class="HMDPS101ShiwakeDenpyoInput">部署指定</label>
                                <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtPatternBusyo Enter Tab"
                                    maxlength="3" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for=""
                                    class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_GLAY_L CELL_TITLE_GLAY_L_width patternLabel">
                                    パターン名 </label></td>
                            <td>
                                <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtPatternNM Enter Tab"
                                    tabindex="68" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="HMDPS101ShiwakeDenpyoInput trHeight50">
                <td class="HMDPS101ShiwakeDenpyoInput align-bottom"><label for=""
                        class="HMDPS101ShiwakeDenpyoInput lblAitesaki LBL_TITLE_STD9 lbl-sky-L">
                        相手先区分
                    </label></td>
                <td class="HMDPS101ShiwakeDenpyoInput align-bottom"><select
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 ddlAitesakiKBN Enter Tab" tabindex="4"></select>
                </td>
                <td class="HMDPS101ShiwakeDenpyoInput align-bottom"><label for=""
                        class="HMDPS101ShiwakeDenpyoInput lblOkyakusamaNOTorihikisaki LBL_TITLE_STD9 lbl-sky-L">
                        お客様NO取引先
                    </label></td>
                <td class="HMDPS101ShiwakeDenpyoInput align-bottom">
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtOkyakusamaNOTorihikisakiNm Enter Tab"
                        tabindex="5" maxlength="8" type="text" />
                </td>
                <td colspan="2" class="HMDPS101ShiwakeDenpyoInput align-bottom"><input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblOkyakuNOTorihikisakiNm Enter Tab"
                        readonly="true" type="text" /></td>
            </tr>
        </table>
        <table class="borderSpace">
            <tr>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput lblTorokuNoKazeiMenzei LBL_TITLE_STD9 lbl-sky-L">
                        事業者名
                    </label></td>
                <td><input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtTorokuNoKazeiMenzeiGyosya Enter Tab"
                        tabindex="6" maxlength="100" type="text" /></td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput lblJigyosyoMeiTorokuNo LBL_TITLE_STD9 lbl-sky-L">
                        登録番号
                    </label></td>
                <td><input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtJigyosyoMeiTorokuNo Enter Tab"
                        tabindex="7" maxlength="14" type="text" /></td>
                <td width="1px"></td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput lblTokurei LBL_TITLE_STD9 lbl-sky-L"> 特例区分
                    </label></td>
                <td><select class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 ddlTokureiKBN Enter Tab"
                        tabindex="8"></select></td>
            </tr>
            <tr>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput lbl-grey-L lblKensakuCD Enter Tab" type="text" />
                </td>
                <td colspan="3">
                    <input class="HMDPS101ShiwakeDenpyoInput lbl-grey-L lblKensakuNM Enter Tab" type="text" />
                </td>
                <td width="1px"></td>
                <td>
                    <button
                        class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn btnTorihikiSearch BTN_STD100 Enter Tab">
                        取引先検索
                    </button>
                </td>
                <td>
                    <button
                        class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnSyainSearch Enter Tab">
                        社員検索
                    </button>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <!-- 摘要 -->
                <td rowspan="2">
                    <label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 Tekiyo lbl-sky-L"> 摘要 </label>
                </td>
                <td colspan="6" rowspan="2">
                    <textarea class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtTekyo Enter Tab" tabindex="9" />
                </td>
            </tr>
        </table>
        <table class="borderSpace">
            <tr>
                <td colspan="6"><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lbl-yellow-L"
                        id="Label10"> 借方
                    </label></td>
                <td width="5px"></td>
                <td colspan="7"><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lbl-yellow-L"
                        id="Label11"> 貸方
                    </label></td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 科目 </label>
                </td>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLKamokuCD KamokuCD  Enter Tab"
                        tabindex="10" maxlength="5" type="text" />
                </td>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLKomokuCD KamokuCD Enter Tab"
                        tabindex="11" maxlength="5" type="text" />
                </td>
                <td>
                    <button
                        class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 nowrap btnLKamokuSearch Enter Tab"
                        tabindex="12">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblLKamokuNM Enter Tab" readonly="true"
                        type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 科目 </label>
                </td>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRKamokuCD KamokuCD Enter Tab"
                        tabindex="32" maxlength="5" type="text" />
                </td>
                <td>
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRKomokuCD KamokuCD Enter Tab"
                        tabindex="33" maxlength="5" type="text" />
                </td>
                <td>
                    <button
                        class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 nowrap btnRKamokuSearch Enter Tab"
                        tabindex="34">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblRKamokuNM Enter Tab" readonly="true"
                        type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 発生部署
                    </label></td>
                <td colspan="2">
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLBusyoCD Enter Tab" tabindex="13"
                        maxlength="3" type="text" />
                </td>
                <td>
                    <button
                        class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD40 BTN_POP nowrap btnLBusyoSearch Enter Tab"
                        tabindex="14">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblLbusyoNM Enter Tab" readonly="true"
                        type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 発生部署
                    </label></td>
                <td colspan="2">
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRbusyoCD Enter Tab" tabindex="35"
                        maxlength="3" type="text" />
                </td>
                <td>
                    <button
                        class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD40 BTN_POP nowrap btnRBusyoSearch Enter Tab"
                        tabindex="36">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblRbusyoNM Enter Tab" readonly="true"
                        type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 消費税区分
                    </label></td>
                <td colspan="3"><select class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 ddlLSyohizeiKbn Enter Tab"
                        tabindex="15"></select></td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 取引区分
                    </label></td>
                <td><select class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 ddlLTorihikiKbn Enter Tab"
                        tabindex="16"></select></td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 消費税区分
                    </label></td>
                <td colspan="3"><select class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 ddlRSyohizeiKbn Enter Tab"
                        tabindex="37"></select></td>
                <td><label for="" class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 取引区分
                    </label></td>
                <td><select class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 ddlRTorihikiKbn Enter Tab"
                        tabindex="38"></select></td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLKouzaKey1NM clearLabel lbl-sky-L"> 口座キー1
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLKouzaKey1 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="17" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLKouzaKey2NM clearLabel lbl-sky-L"> 口座キー2
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLKouzaKey2 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="18" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRKouzaKey1NM clearLabel lbl-sky-L"> 口座キー1
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRKouzaKey1 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="39" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRKouzaKey2NM clearLabel lbl-sky-L"> 口座キー2
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRKouzaKey2 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="40" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLKouzaKey3NM clearLabel lbl-sky-L"> 口座キー3
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLKouzaKey3 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="19" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLKouzaKey4NM clearLabel lbl-sky-L"> 口座キー4
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLKouzaKey4 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="20" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRKouzaKey3NM clearLabel lbl-sky-L"> 口座キー3
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRKouzaKey3 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="41" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRKouzaKey4NM clearLabel lbl-sky-L"> 口座キー4
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRKouzaKey4 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="42" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLKouzaKey5NM clearLabel lbl-sky-L"> 口座キー5
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLKouzaKey5 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="21" type="text" />
                </td>
                <td></td>
                <td></td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRKouzaKey5NM clearLabel lbl-sky-L"> 口座キー5
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRKouzaKey5 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="43" type="text" />
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo1 clearLabel lbl-sky-L"> 必須摘要1
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo1 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="22" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo2 clearLabel lbl-sky-L"> 必須摘要2
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo2 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="23" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo1 clearLabel lbl-sky-L"> 必須摘要1
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo1 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="44" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo2 clearLabel lbl-sky-L"> 必須摘要2
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo2 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="45" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo3 clearLabel lbl-sky-L"> 必須摘要3
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo3 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="24" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo4 clearLabel lbl-sky-L"> 必須摘要4
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo4 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="25" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo3 clearLabel lbl-sky-L"> 必須摘要3
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo3 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="46" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo4 clearLabel lbl-sky-L"> 必須摘要4
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo4 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="47" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo5 clearLabel lbl-sky-L"> 必須摘要5
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo5 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="26" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo6 clearLabel lbl-sky-L"> 必須摘要6
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo6 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="27" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo5 clearLabel lbl-sky-L"> 必須摘要5
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo5 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="48" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo6 clearLabel lbl-sky-L"> 必須摘要6
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo6 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="49" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo7 clearLabel lbl-sky-L"> 必須摘要7
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo7 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="28" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo8 clearLabel lbl-sky-L"> 必須摘要8
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo8 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="29" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo7 clearLabel lbl-sky-L"> 必須摘要7
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo7 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="50" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo8 clearLabel lbl-sky-L"> 必須摘要8
                    </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo8 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="51" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo9 clearLabel lbl-sky-L"> 必須摘要9
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo9 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="30" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblLHissuTekyo10 clearLabel lbl-sky-L">
                        必須摘要10 </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtLHissuTekyo10 KouzaHiTekkiEnabledSet Enter Tab"
                        tabindex="31" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo9 clearLabel lbl-sky-L"> 必須摘要9
                    </label></td>
                <td colspan="3">
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo9 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="52" type="text" />
                </td>
                <td><label for=""
                        class="HMDPS101ShiwakeDenpyoInput LBL_TITLE_STD9 lblRHissuTekyo10 clearLabel lbl-sky-L">
                        必須摘要10 </label></td>
                <td>
                    <input
                        class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 txtRHissuTekyo10 KouzaHiTekkiEnabledSet2 Enter Tab"
                        tabindex="53" type="text" />
                </td>
            </tr>
        </table>
        <!-- jqgrid -->
        <div class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInput_sprList">
            <table class="HMDPS101ShiwakeDenpyoInput sprList Enter Tab" id="HMDPS101ShiwakeDenpyoInput_sprList"></table>
        </div>
        <!-- 件 -->
        <div id="GOUKEITBL" class="HMDPS101ShiwakeDenpyoInput GOUKEITBL">
            <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblKensu" readonly="true" />
            <label for="" class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblKen"> 件 </label>
            <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblZeikomiGoukei" readonly="true">
            <input class="HMDPS101ShiwakeDenpyoInput LBL_MSG_STD10 lblSyohizeiGoukei" readonly="true" />
        </div>
        <div id="BOTTONTBL" class="HMS-button-pane">
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnAdd Enter Tab"
                tabindex="55">
                行追加
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnUpdate Enter Tab"
                tabindex="56">
                行変更
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnDelete Enter Tab"
                tabindex="57">
                行削除
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnClear Enter Tab"
                tabindex="58">
                クリア
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD260 btnPatternTrk Enter Tab"
                tabindex="59">
                表示されている仕訳をパターンとして登録
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnKakutei Enter Tab"
                tabindex="60">
                全確定
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnPtnInsert Enter Tab"
                tabindex="61">
                登録
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnAllDelete Enter Tab"
                tabindex="62">
                全削除
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnPtnUpdate Enter Tab"
                tabindex="63">
                更新
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnPtnDelete Enter Tab"
                tabindex="64">
                削除
            </button>
            <button class="HMDPS101ShiwakeDenpyoInput HMDPS101ShiwakeDenpyoInputBtn BTN_STD100 btnClose Enter Tab"
                tabindex="65">
                閉じる
            </button>
        </div>
    </div>
</div>