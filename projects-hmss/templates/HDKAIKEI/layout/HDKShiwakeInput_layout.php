<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKShiwakeInput/HDKShiwakeInput"));
?>
<style type="text/css">
    .HDKShiwakeInput input[type='text'] {
        width: 120px;
    }

    .HDKShiwakeInput a {
        text-decoration: underline;
        color: blue;
    }

    .HDKShiwakeInput.HDKShiwakeInput_sprList {
        margin: 2px 5px;
    }

    .HDKShiwakeInput .borderSpace {
        border-spacing: 2px 0px;
    }

    .HDKShiwakeInput textarea {
        height: 42px;
    }

    .HDKShiwakeInput.ddlPatternSel {
        width: 150px;
    }

    .HDKShiwakeInput.txtKeiriSyoriDT {
        width: 130px !important;
    }

    .HDKShiwakeInput.lblMemo {
        height: 73px;
        width: 290px;
    }

    .HDKShiwakeInput.lblZeink_GK,
    .HDKShiwakeInput.lblSyohizei {
        height: 21px;
        padding: 0px 2px;
        line-height: 21px;
        width: 145px !important;
        text-align: right;
    }

    .HDKShiwakeInput.txtPatternBusyo {
        width: 70px !important;
    }

    .HDKShiwakeInput.txtPatternNM {
        width: 157px;
        height: 19.2px;
    }

    .HDKShiwakeInput.txtTekyo {
        width: 700px;
    }

    .HDKShiwakeInput#Label10,
    .HDKShiwakeInput#Label11 {
        text-align: center;
        width: 547px;
        height: 19.2px;
        padding: 0px 2px;
    }

    .HDKShiwakeInput.txtLKamokuCD,
    .HDKShiwakeInput.txtLKomokuCD,
    .HDKShiwakeInput.txtRKamokuCD,
    .HDKShiwakeInput.txtRKomokuCD {
        width: 62px !important;
    }

    .HDKShiwakeInput.txtLBusyoCD,
    .HDKShiwakeInput.txtRbusyoCD,
    .HDKShiwakeInput.lblKensakuCD {
        width: 134px !important;
    }

    .HDKShiwakeInput.lblRbusyoNM,
    .HDKShiwakeInput.lblLbusyoNM,
    .HDKShiwakeInput.lblKensakuNM {
        width: 251px !important;
    }

    .HDKShiwakeInput.lblLKamokuNM,
    .HDKShiwakeInput.lblRKamokuNM {
        width: 92px !important;
    }

    .HDKShiwakeInput.lblLKoumkNM,
    .HDKShiwakeInput.lblRKoumkNM {
        width: 148px !important;
    }

    .HDKShiwakeInput.clearLabel {
        height: 21.6px;
        display: block;
    }

    .HDKShiwakeInput.ddlLSyohizeiKbn,
    .HDKShiwakeInput.ddlRSyohizeiKbn {
        width: 188px;
    }

    .HDKShiwakeInput.lblZeikm_GK_NM {
        width: 105px !important;
    }

    .HDKShiwakeInput.lblSyohy_no,
    .HDKShiwakeInput.txtZeikm_GK {
        width: 155px !important;
    }

    .HDKShiwakeInput.Tekiyo {
        height: 45px;
        line-height: 45px;
        padding-top: 2px;
    }

    .HDKShiwakeInput.lbl-grey-L {
        width: 115px;
    }

    .HDKShiwakeInput.lbl-sky-L {
        width: 105px;
    }

    .HDKShiwakeInput.radPatternKyotu_label {
        height: 45px;
        line-height: 45px;
    }

    .HDKShiwakeInput input[readonly='true'] {
        background-color: #BABEC1 !important;
    }

    /*灰色背景*/
    .HDKShiwakeInput.CELL_TITLE_GLAY_L {
        background-color: #BABEC1;
        border: solid 1px black;
        padding: 0px 2px;
    }

    .HDKShiwakeInput.CELL_TITLE_GLAY_L_width {
        width: 115px;
    }

    .HDKShiwakeInput.btnSyainSearch {
        min-width: 50px;
        width: 92px !important;
    }

    /*修正前表示,最新表示*/
    .HDKShiwakeInput.btnSaishinDisp,
    .HDKShiwakeInput.btnSyuseiMaeDisp {
        float: right;
    }

    /*蓝色背景*/
    .HDKShiwakeInput.CELL_TITLE_HISU_BLUE_L {
        background-color: #B0E0E6;
        border: solid 1px black;
        padding: 0px 2px;
        width: 92px;
    }

    /*按钮不换行*/
    .HDKShiwakeInput.nowrap {
        white-space: nowrap;
    }

    /*右寄せ*/
    .HDKShiwakeInput.TXT_RIGHT,
    .HDKShiwakeInput.lblKensu,
    .HDKShiwakeInput.lblZeikomiGoukei,
    .HDKShiwakeInput.lblSyohizeiGoukei {
        text-align: right;
    }

    /*label*/
    .HDKShiwakeInput.clearLabel {
        width: 92px;
    }

    .HDKShiwakeInput.GOUKEITBL {
        margin: 0px 5px 3px 5px;
    }

    .HDKShiwakeInput div.HMS-button-pane {
        margin: 0px !important;
        min-height: 25px;
    }

    /*改行*/
    .HDKShiwakeInput_sprList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HDKShiwakeInput.lblSyohizei {
        width: 145px !important;
    }

    .HDKShiwakeInput_sprList .ui-jqgrid-bdiv {
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    .HDKShiwakeInput .ui-jqgrid .ui-jqgrid-htable th,
    .HDKShiwakeInput .btnHeight {
        height: 22px;
    }

    .HDKShiwakeInput.ddlLSyouhiKbn,
    .HDKShiwakeInput.ddlRSyouhiKbn {
        width: 155px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HDKShiwakeInput.lbl-sky-L {
            width: 85px;
        }

        .HDKShiwakeInput.txtTekyo {
            width: 640px;
        }

        .HDKShiwakeInput.lblSyohy_no,
        .HDKShiwakeInput.txtZeikm_GK {
            width: 135px !important;
        }

        .HDKShiwakeInput.lblZeikm_GK_NM {
            width: 85px !important;
        }

        .HDKShiwakeInput.lblZeink_GK,
        .HDKShiwakeInput.lblSyohizei {
            height: 15px;
            line-height: 15px;
        }

        .HDKShiwakeInput.txtPatternNM {
            height: 13.2px;
        }

        .HDKShiwakeInput#Label10,
        .HDKShiwakeInput#Label11 {
            width: 507px;
        }

        .HDKShiwakeInput.lblLKamokuNM,
        .HDKShiwakeInput.lblRKamokuNM {
            width: 79px !important;
        }

        .HDKShiwakeInput.lblRbusyoNM,
        .HDKShiwakeInput.lblLbusyoNM,
        .HDKShiwakeInput.lblKensakuNM {
            width: 231px !important;
        }

        .HDKShiwakeInput.lblLKoumkNM,
        .HDKShiwakeInput.lblRKoumkNM {
            width: 139px !important;
        }

        .HDKShiwakeInput.ddlLSyohizeiKbn,
        .HDKShiwakeInput.ddlRSyohizeiKbn {
            width: 182px;
        }

        .HDKShiwakeInput.ddlLSyouhiKbn,
        .HDKShiwakeInput.ddlRSyouhiKbn {
            width: 147px;
        }

        .HDKShiwakeInput.txtLKamokuCD,
        .HDKShiwakeInput.txtLKomokuCD,
        .HDKShiwakeInput.txtRKamokuCD,
        .HDKShiwakeInput.txtRKomokuCD {
            width: 62px !important;
        }

        .HDKShiwakeInput.txtLBusyoCD,
        .HDKShiwakeInput.txtRbusyoCD,
        .HDKShiwakeInput.lblKensakuCD {
            width: 136px !important;
        }

        .HDKShiwakeInput.CELL_TITLE_HISU_BLUE_L {
            width: 79px;
        }

        .HDKShiwakeInput .ui-jqgrid .ui-jqgrid-htable th,
        .HDKShiwakeInput .btnHeight {
            height: 18px;
        }

        .HDKShiwakeInput.lblMemo {
            height: 68px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HDKShiwakeInput body">
    <div class="HDKShiwakeInput HDKAIKEI-content">
        <table class="borderSpace">
            <tr class="HDKShiwakeInput HMS-button-pane KeyTableRow">
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 lblSyohy_no_NM lbl-sky-L "> 証憑№ </label></td>
                <td>
                    <input class="HDKShiwakeInput lbl-grey-L lblSyohy_no Enter Tab" type="text" readonly="true" />
                </td>
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 lblKeiriSyoriDT_NM lbl-sky-L"> 経理処理日 </label>
                </td>
                <td>
                    <input class="HDKShiwakeInput LBL_MSG_STD10 txtKeiriSyoriDT Enter Tab" type="text" />
                </td>
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 lblPatternSel_NM lbl-sky-L"> パターン選択 </label>
                </td>
                <td><select class="HDKShiwakeInput LBL_MSG_STD9 ddlPatternSel Enter Tab"></select></td>
                <td colspan="3">
                    <button class="HDKShiwakeInput BTN_STD80 btnSyuseiMaeDisp HDKShiwakeInputBtn Enter Tab">
                        修正前表示
                    </button>
                    <button class="HDKShiwakeInput BTN_STD80 btnSaishinDisp HDKShiwakeInputBtn Enter Tab">
                        最新表示
                    </button>
                </td>
            </tr>
            <tr>
                <td><label for=""
                        class="HDKShiwakeInput KingakuRow LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L lblZeikm_GK_NM"> 税込金額
                    </label></td>
                <td>
                    <input class="HDKShiwakeInput LBL_MSG_STD10 TXT_RIGHT KingakuRow txtZeikm_GK Enter Tab" type="text"
                        tabindex="1" maxlength="17" />
                </td>
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 lblZeink_GK_NM KingakuRow lbl-sky-L"> 税抜金額
                    </label>
                </td>
                <td><label for="" type="text" class="HDKShiwakeInput lbl-grey-L lblZeink_GK KingakuRow Enter Tab"
                        type="text" disabled="disabled"></label></td>
                <td><label for="" class="HDKShiwakeInput lblSyohizei_NM KingakuRow lbl-sky-L"> 消費税金額 </label></td>
                <td><label for="" class="HDKShiwakeInput lbl-grey-L lblSyohizei KingakuRow Enter Tab"
                        disabled="disabled"></label></td>
                <!-- パターン対象部署№ -->
                <td rowspan="2" colspan="3">
                    <div class="HDKShiwakeInput pnlTenpo">
                        <label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_GLAY_L lblMemo"> </label>
                    </div>
                    <table class="HDKShiwakeInput pnlHonbu borderSpace">
                        <tr>
                            <td rowspan="2"><label for=""
                                    class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_GLAY_L CELL_TITLE_GLAY_L_width radPatternKyotu_label">
                                    パターン対象部署 </label></td>
                            <td colspan="2">
                                <input class="HDKShiwakeInput LBL_MSG_STD10 radPatternKyotu Enter Tab"
                                    name="HDKShiwakeInput_radio" type="radio" tabindex="59" />
                                <label for="" class="HDKShiwakeInput">共通</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="HDKShiwakeInput LBL_MSG_STD10 radPatternBusyo Enter Tab"
                                    name="HDKShiwakeInput_radio" type="radio" tabindex="60" />
                                <label for="" class="HDKShiwakeInput">部署指定</label>
                                <input class="HDKShiwakeInput LBL_MSG_STD10 txtPatternBusyo Enter Tab" maxlength="16" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for=""
                                    class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_GLAY_L CELL_TITLE_GLAY_L_width patternLabel">
                                    パターン名 </label></td>
                            <td>
                                <input class="HDKShiwakeInput LBL_MSG_STD10 txtPatternNM Enter Tab" tabindex="61" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <!-- 摘要 -->
                <td rowspan="2"><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 Tekiyo lbl-sky-L"> 摘要 </label></td>
                <td colspan="5" rowspan="2">
                    <textarea class="HDKShiwakeInput LBL_MSG_STD10 txtTekyo Enter Tab" tabindex="2"
                        placeholder="【例】（取引先）〇〇(株)　（取引日）10/1　（内容）本社ビル清掃料" />
                </td>
            </tr>
        </table>
        <div class="borderSpace">
            <tr>
                <td colspan="6">
                    <button class="HDKShiwakeInput btn fileSelect Enter Tab" disabled="disabled" tabindex="84">添付ファイル：
                        <span class="HDKShiwakeInput hasFileFlg">なし</span>
                    </button>
                </td>
            </tr>
        </div>
        <table class="borderSpace">
            <tr>
                <td colspan="6"><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 lbl-yellow-L" id="Label10"> 借方
                    </label>
                </td>
                <td width="5px"></td>
                <td colspan="7"><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 lbl-yellow-L" id="Label11"> 貸方
                    </label>
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 科目 </label></td>
                <td>
                    <input class="HDKShiwakeInput LBL_MSG_STD10 txtLKamokuCD KamokuCD  Enter Tab" tabindex="3"
                        maxlength="5" type="text" />
                </td>
                <td>
                    <input class="HDKShiwakeInput LBL_MSG_STD10 txtLKomokuCD KamokuCD Enter Tab" tabindex="4"
                        maxlength="5" type="text" />
                </td>
                <td>
                    <button
                        class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 nowrap btnLKamokuSearch btnHeight Enter Tab"
                        tabindex="5">
                        検索
                    </button>
                </td>
                <td colspan="1">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblLKamokuNM Enter Tab" readonly="true" type="text" />
                </td>
                <td colspan="1">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblLKoumkNM Enter Tab" readonly="true" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 科目 </label></td>
                <td>
                    <input class="HDKShiwakeInput LBL_MSG_STD10 txtRKamokuCD KamokuCD Enter Tab" tabindex="25"
                        maxlength="5" type="text" />
                </td>
                <td>
                    <input class="HDKShiwakeInput LBL_MSG_STD10 txtRKomokuCD KamokuCD Enter Tab" tabindex="26"
                        maxlength="5" type="text" />
                </td>
                <td>
                    <button
                        class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 nowrap btnRKamokuSearch btnHeight Enter Tab"
                        tabindex="27">
                        検索
                    </button>
                </td>
                <td colspan="1">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblRKamokuNM Enter Tab" readonly="true" type="text" />
                </td>
                <td colspan="1">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblRKoumkNM Enter Tab" readonly="true" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 発生部署 </label></td>
                <td colspan="2">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 txtLBusyoCD Enter Tab" tabindex="6" maxlength="16"
                        type="text" />
                </td>
                <td>
                    <button
                        class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD40 BTN_POP nowrap btnLBusyoSearch btnHeight Enter Tab"
                        tabindex="7">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblLbusyoNM Enter Tab" readonly="true" type="text" />
                </td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 発生部署 </label></td>
                <td colspan="2">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 txtRbusyoCD Enter Tab" tabindex="28" maxlength="16"
                        type="text" />
                </td>
                <td>
                    <button
                        class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD40 BTN_POP nowrap btnRBusyoSearch btnHeight Enter Tab"
                        tabindex="29">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblRbusyoNM Enter Tab" readonly="true" type="text" />
                </td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 消費税区分 </label></td>
                <td colspan="3"><select class="HDKShiwakeInput LBL_MSG_STD10 ddlLSyohizeiKbn Enter Tab"
                        tabindex="8"></select>
                </td>
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 消費税率 </label></td>
                <td><select class="HDKShiwakeInput LBL_MSG_STD10 ddlLSyouhiKbn Enter Tab" tabindex="9"></select></td>
                <td width="5px"></td>
                <!-- 貸方 -->
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 消費税区分 </label></td>
                <td colspan="3"><select class="HDKShiwakeInput LBL_MSG_STD10 ddlRSyohizeiKbn Enter Tab"
                        tabindex="30"></select>
                </td>
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 消費税率 </label></td>
                <td><select class="HDKShiwakeInput LBL_MSG_STD10 ddlRSyouhiKbn Enter Tab" tabindex="31"></select></td>
            </tr>
            <tr>
                <!-- 借方 -->
                <td><label for="" class="HDKShiwakeInput LBL_TITLE_STD9 CELL_TITLE_HISU_BLUE_L"> 取引先 </label></td>
                <td colspan="2">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblKensakuCD Enter Tab" type="text" maxlength="10"
                        tabindex="32" />
                </td>
                <td>
                    <button
                        class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD40 BTN_POP nowrap btnTorihikiSearch btnHeight Enter Tab"
                        tabindex="33">
                        検索
                    </button>
                </td>
                <td colspan="2">
                    <input class="HDKShiwakeInput LBL_MSG_STD10 lblKensakuNM Enter Tab" readonly="true" type="text" />
                </td>
                <td width="5px"></td>
            </tr>
        </table>
        <!-- jqgrid -->
        <div class="HDKShiwakeInput HDKShiwakeInput_sprList">
            <table class="HDKShiwakeInput sprList Enter Tab" id="HDKShiwakeInput_sprList"></table>
        </div>
        <!-- 件 -->
        <div id="GOUKEITBL" class="HDKShiwakeInput GOUKEITBL">
            <input class="HDKShiwakeInput LBL_MSG_STD10 lblKensu" readonly="true" />
            <label for="" class="HDKShiwakeInput LBL_MSG_STD10 lblKen"> 件 </label>
            <input class="HDKShiwakeInput LBL_MSG_STD10 lblZeikomiGoukei" readonly="true">
            <input class="HDKShiwakeInput LBL_MSG_STD10 lblSyohizeiGoukei" readonly="true" />
        </div>
        <div id="BOTTONTBL" class="HMS-button-pane">
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnAdd Enter Tab" tabindex="48">
                行追加
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnUpdate Enter Tab" tabindex="49">
                行変更
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnDelete Enter Tab" tabindex="50">
                行削除
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnClear Enter Tab" tabindex="51">
                クリア
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD260 btnPatternTrk Enter Tab" tabindex="52">
                表示されている仕訳をパターンとして登録
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnKakutei Enter Tab" tabindex="53">
                全確定
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnPtnInsert Enter Tab" tabindex="54">
                登録
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnAllDelete Enter Tab" tabindex="55">
                全削除
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnPtnUpdate Enter Tab" tabindex="56">
                更新
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnPtnDelete Enter Tab" tabindex="57">
                削除
            </button>
            <button class="HDKShiwakeInput HDKShiwakeInputBtn BTN_STD100 btnClose Enter Tab" tabindex="58">
                閉じる
            </button>
        </div>
    </div>
</div>