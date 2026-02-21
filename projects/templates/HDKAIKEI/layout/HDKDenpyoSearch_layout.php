<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKDenpyoSearch/HDKDenpyoSearch"));
?>
<style type="text/css">
    .HDKDenpyoSearch.Label2,
    .HDKDenpyoSearch.Label8 {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-left: 2px;
        width: 100px;
        height: 48px;
        line-height: 48px;
    }

    .HDKDenpyoSearch.lbl-sky-L {
        width: 101px;
    }

    .HDKDenpyoSearch.lblKeyWord {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-left: 2px;
        width: 100px;
    }

    .HDKDenpyoSearch.btnAllSelect {
        margin-right: 20px;
    }

    .HDKDenpyoSearch.btnDenpyPrint {
        float: right;
    }

    .HDKDenpyoSearch.HDKAIKEI-content {
        overflow-y: hidden;
    }

    .HDKDenpyoSearch.txtLKamokuCD,
    .HDKDenpyoSearch.txtRKamokuCD,
    .HDKDenpyoSearch.txtBusyoCD,
    .HDKDenpyoSearch.txtSyainNO {
        width: 135px !important;
    }

    .HDKDenpyoSearch.samllButton {
        min-width: 60px;
    }

    .HDKDenpyoSearch.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HDKDenpyoSearch.btnSearch {
        width: 140px;
    }

    .HDKDenpyoSearch.HMS-bottom {
        margin-top: -6px;
    }

    .HDKDenpyoSearch.HMS-bottom2 {
        margin-top: -3px;
    }

    .HDKDenpyoSearch.disabledNM {
        width: 180px;
    }

    .HDKDenpyoSearch .ui-jqgrid .ui-jqgrid-htable th div {
        margin: 0 !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HDKDenpyoSearch.lbl-sky-L {
            width: 75px;
        }

        .HDKDenpyoSearch.txtLKamokuCD,
        .HDKDenpyoSearch.txtRKamokuCD,
        .HDKDenpyoSearch.txtBusyoCD,
        .HDKDenpyoSearch.txtSyainNO {
            width: 125px !important;
        }

        .HDKDenpyoSearch.disabledNM {
            width: 170px !important;
        }

        .HDKDenpyoSearch.Label2,
        .HDKDenpyoSearch.Label8 {
            height: 40px;
            line-height: 40px;
        }

        .HDKDenpyoSearch .ui-jqgrid .ui-jqgrid-htable th div {
            margin: 5px !important;
        }
    }
</style>

<!-- 画面個別の内容を表示 -->
<div class='HDKDenpyoSearch'>
    <div class="HDKDenpyoSearch HDKAIKEI-content">
        <fieldset class="HDKDenpyoSearch fieldset">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr>
                    <td><label for="" class="HDKDenpyoSearch Label1 lbl-sky-L"> 伝票種類 </label>
                        <input type="radio" value="radAll" name="HDKDENPYOKIND" checked="checked"
                            class='HDKDenpyoSearch radAll Tab Enter' tabindex="1" />
                        <label for="" class="HDKDenpyoSearch radAllDiv">全て</label>
                        <input type="radio" value="radShiharai" name="HDKDENPYOKIND"
                            class='HDKDenpyoSearch radShiharai Tab Enter' tabindex="2" />
                        <label for="" class="HDKDenpyoSearch radShiharaiDiv">支払伝票</label>
                        <input type="radio" value="radShiwake" name="HDKDENPYOKIND"
                            class='HDKDenpyoSearch radShiwake Tab Enter' tabindex="3" />
                        <label for="" class="HDKDenpyoSearch radShiwakeDiv">仕訳伝票</label>
                    </td>
                    <td width="5px"></td>
                    <td rowspan="2"><label for="" class="HDKDenpyoSearch Label2"> 作成日 </label></td>
                    <td>
                        <input type="text" maxlength="10" class="HDKDenpyoSearch txtDateFrom Datepicker Enter Tab"
                            tabindex="14" />
                    </td>
                    <td>～</td>
                    <td width="10px"></td>
                    <td><label for="" class="HDKDenpyoSearch Label3 lbl-sky-L">借方科目コード</label></td>
                    <td>
                        <input type="text" maxlength="5" class="HDKDenpyoSearch txtLKamokuCD  Enter Tab"
                            tabindex="19" />
                    </td>
                    <td>
                        <button class="HDKDenpyoSearch btnLKSearch HDKDenpyoSearchButton samllButton Enter Tab"
                            tabindex="20">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HDKDenpyoSearch lblLkamokuNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td><label for="" class="HDKDenpyoSearch Label5 lbl-sky-L">証憑№</label>
                        <input type="text" maxlength="20" class="HDKDenpyoSearch txtSyohyNO  Enter Tab" tabindex="4" />
                    </td>
                    <td></td>
                    <td>
                        <input type="text" maxlength="10" class="HDKDenpyoSearch txtDateTo Datepicker Enter Tab"
                            tabindex="15" />
                    </td>
                    <td></td>
                    <td></td>
                    <td><label for="" class="HDKDenpyoSearch Label6 lbl-sky-L">貸方科目コード</label></td>
                    <td>
                        <input type="text" maxlength="5" class="HDKDenpyoSearch txtRKamokuCD Enter Tab" tabindex="21" />
                    </td>
                    <td>
                        <button class="HDKDenpyoSearch btnRKSearch HDKDenpyoSearchButton samllButton Enter Tab"
                            tabindex="22">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HDKDenpyoSearch lblRkamokuNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td><label for="" class="HDKDenpyoSearch Label13 lbl-sky-L"> 印刷状態 </label>
                        <input type="radio" value="radPrintNoSel" name="HDKPRINTKIND" checked="checked"
                            class='HDKDenpyoSearch radPrintNoSel Tab Enter' tabindex="5" />
                        <label for="" class="HDKDenpyoSearch radPrintNoSelDiv">指定しない</label>
                        <input type="radio" value="radPrintMi" name="HDKPRINTKIND"
                            class='HDKDenpyoSearch radPrintMi Tab Enter' tabindex="6" />
                        <label for="" class="HDKDenpyoSearch radPrintMiDiv">未</label>
                        <input type="radio" value="radPrintSumi" name="HDKPRINTKIND"
                            class='HDKDenpyoSearch radPrintSumi Tab Enter' tabindex="7" />
                        <label for="" class="HDKDenpyoSearch radPrintSumiDiv">済</label>
                    </td>
                    <td></td>
                    <td rowspan="2"><label for="" class="HDKDenpyoSearch Label8"> 支払予定日 </label></td>
                    <td>
                        <input type="text" maxlength="10" class="HDKDenpyoSearch txtShiharaiDTFrom Datepicker Enter Tab"
                            tabindex="16" />
                    </td>
                    <td>～</td>
                    <td></td>
                    <td><label for="" class="HDKDenpyoSearch Label9 lbl-sky-L">作成部署</label></td>
                    <td>
                        <input type="text" maxlength="16" class="HDKDenpyoSearch txtBusyoCD Enter Tab" tabindex="23" />
                    </td>
                    <td>
                        <button class="HDKDenpyoSearch btnBusyoSearch HDKDenpyoSearchButton samllButton Enter Tab"
                            tabindex="24">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HDKDenpyoSearch lblBusyoNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td><label for="" class="HDKDenpyoSearch lblCsvName lbl-sky-L">経理課処理済</label>
                        <input type="radio" value="radCsvNoSel" name="HDKCSVKIND" checked="checked"
                            class='HDKDenpyoSearch radCsvNoSel Tab Enter' tabindex="8" />
                        <label for="" class="HDKDenpyoSearch radCsvNoSelDiv">指定しない</label>
                        <input type="radio" value="radCsvMi" name="HDKCSVKIND"
                            class='HDKDenpyoSearch radCsvMi Tab Enter' tabindex="9" />
                        <label for="" class="HDKDenpyoSearch radCsvMiDiv">未</label>
                        <input type="radio" value="radCsvSumi" name="HDKCSVKIND"
                            class='HDKDenpyoSearch radCsvSumi Tab Enter' tabindex="10" />
                        <label for="" class="HDKDenpyoSearch radCsvSumiDiv">済</label>
                    </td>
                    <td></td>
                    <td>
                        <input type="text" maxlength="10" class="HDKDenpyoSearch txtShiharaiDTEnd Datepicker Enter Tab"
                            tabindex="17" />
                    </td>
                    <td></td>
                    <td></td>
                    <td><label for="" class="HDKDenpyoSearch Label11 lbl-sky-L">作成者</label></td>
                    <td>
                        <input type="text" maxlength="5" class="HDKDenpyoSearch txtSyainNO Enter Tab" tabindex="25" />
                    </td>
                    <td>
                        <button class="HDKDenpyoSearch btnSyainSearch HDKDenpyoSearchButton samllButton Enter Tab"
                            tabindex="26">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HDKDenpyoSearch lblSyainNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="HDKDenpyoSearch xlsxDiv"><label for=""
                                class="HDKDenpyoSearch lblXlsxName lbl-sky-L">OBC出力状態</label>
                            <input type="radio" value="radXlsxNoSel" name="HDKXLSXKIND" checked="checked"
                                class='HDKDenpyoSearch radXlsxNoSel Tab Enter' tabindex="11" />
                            <label for="" class="HDKDenpyoSearch radXlsxNoSelDiv">指定しない</label>
                            <input type="radio" value="radXlsxMi" name="HDKXLSXKIND"
                                class='HDKDenpyoSearch radXlsxMi Tab Enter' tabindex="12" />
                            <label for="" class="HDKDenpyoSearch radXlsxMiDiv">未</label>
                            <input type="radio" value="radXlsxSumi" name="HDKXLSXKIND"
                                class='HDKDenpyoSearch radXlsxSumi Tab Enter' tabindex="13" />
                            <label for="" class="HDKDenpyoSearch radXlsxSumiDiv">済</label>
                        </div>
                    </td>
                    <td></td>
                    <td><label for="" class="HDKDenpyoSearch lblKeyWord">キーワード</label>
                    </td>
                    <td colspan="8">
                        <input type="text" maxlength="20" class="HDKDenpyoSearch txtKeyWord Enter Tab" tabindex="18" />
                        <button class="HDKDenpyoSearch btnMicheckPrint HDKDenpyoSearchButton Enter Tab" tabindex="28">
                            未チェック一覧印刷
                        </button>
                        <button class="HDKDenpyoSearch btnSearch HDKDenpyoSearchButton Enter Tab" tabindex="27">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HDKDenpyoSearch HMS-button-pane">
            <button class="HDKDenpyoSearch btnNew HDKDenpyoSearchButton Enter Tab" tabindex="29">
                新規作成
            </button>
        </div>
        <div class="HDKDenpyoSearch pnlList HMS-bottom">
            <table class="HDKDenpyoSearch grdList " id="HDKDenpyoSearch_grdList"></table>
            <div id="HDKDenpyoSearch_pager"></div>
        </div>
        <div class="HDKDenpyoSearch pnlallbutton HMS-button-pane HMS-bottom2">
            <button class="HDKDenpyoSearch btnAllSelect HDKDenpyoSearchButton Enter Tab" tabindex="30">
                全て選択
            </button>
            <button class="HDKDenpyoSearch btnAllKaijyo HDKDenpyoSearchButton Enter Tab" tabindex="31">
                選択解除
            </button>
            <button class="HDKDenpyoSearch btnDenpyPrint HDKDenpyoSearchButton Enter Tab" tabindex="32">
                伝票印刷
            </button>
        </div>
    </div>
</div>