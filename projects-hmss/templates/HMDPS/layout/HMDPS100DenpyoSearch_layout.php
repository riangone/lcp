<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS100DenpyoSearch/HMDPS100DenpyoSearch"));
?>
<style type="text/css">
    .HMDPS100DenpyoSearch.Label2,
    .HMDPS100DenpyoSearch.Label8 {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-left: 2px;
        width: 100px;
        height: 48px;
        line-height: 48px;
    }

    .HMDPS100DenpyoSearch.lblKeyWord {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-left: 2px;
        width: 100px;
    }

    .HMDPS100DenpyoSearch.btnAllSelect {
        margin-right: 20px;
    }

    .HMDPS100DenpyoSearch.btnDenpyPrint {
        float: right;
    }

    .HMDPS100DenpyoSearch.HMDPS-content {
        overflow-y: hidden;
    }

    .HMDPS100DenpyoSearch.txtBusyoCD {
        width: 50px !important;
    }

    .HMDPS100DenpyoSearch.samllButton {
        min-width: 60px;
    }

    .HMDPS100DenpyoSearch.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMDPS100DenpyoSearch.btnSearch {
        width: 140px;
    }

    .HMDPS100DenpyoSearch.HMS-bottom {
        margin-top: -6px;
    }

    .HMDPS100DenpyoSearch.HMS-bottom2 {
        margin-top: -3px;
    }

    .HMDPS100DenpyoSearch.disabledNM {
        width: 180px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMDPS100DenpyoSearch.disabledNM {
            width: 175px;
        }

        .HMDPS100DenpyoSearch.Label2,
        .HMDPS100DenpyoSearch.Label8 {
            height: 42px;
            line-height: 42px;
        }
    }
</style>

<!-- 画面個別の内容を表示 -->
<div class='HMDPS100DenpyoSearch'>
    <div class="HMDPS100DenpyoSearch HMDPS-content">
        <!-- 20240426 YIN UPD S -->
        <!-- <fieldset class="HMDPS100DenpyoSearch"> -->
        <fieldset class="HMDPS100DenpyoSearch fieldset">
            <!-- 20240426 YIN UPD E -->
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr>
                    <td><label class="HMDPS100DenpyoSearch Label1 lbl-sky-L" for=""> 伝票種類 </label>
                        <input type="radio" value="radAll" name="DENPYOKIND" checked="checked"
                            class='HMDPS100DenpyoSearch radAll Tab Enter' tabindex="1" />
                        <label class="HMDPS100DenpyoSearch radAllDiv" for="">全て</label>
                        <input type="radio" value="radShiharai" name="DENPYOKIND"
                            class='HMDPS100DenpyoSearch radShiharai Tab Enter' tabindex="2" />
                        <label class="HMDPS100DenpyoSearch radShiharaiDiv" for="">支払伝票</label>
                        <input type="radio" value="radShiwake" name="DENPYOKIND"
                            class='HMDPS100DenpyoSearch radShiwake Tab Enter' tabindex="3" />
                        <label class="HMDPS100DenpyoSearch radShiwakeDiv" for="">仕訳伝票</label>
                    </td>
                    <td width="5px"></td>
                    <td rowspan="2"><label class="HMDPS100DenpyoSearch Label2" for=""> 作成日 </label></td>
                    <td>
                        <input type="text" maxlength="10" class="HMDPS100DenpyoSearch txtDateFrom Datepicker Enter Tab"
                            tabindex="12" />
                    </td>
                    <td>～</td>
                    <td width="10px"></td>
                    <td><label class="HMDPS100DenpyoSearch Label3 lbl-sky-L" for="">借方科目コード</label></td>
                    <td>
                        <input type="text" maxlength="5" class="HMDPS100DenpyoSearch txtLKamokuCD  Enter Tab"
                            tabindex="17" />
                    </td>
                    <td>
                        <button
                            class="HMDPS100DenpyoSearch btnLKSearch HMDPS100DenpyoSearchButton samllButton Enter Tab"
                            tabindex="18">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HMDPS100DenpyoSearch lblLkamokuNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td><label class="HMDPS100DenpyoSearch Label5 lbl-sky-L" for="">証憑№</label>
                        <input type="text" maxlength="20" class="HMDPS100DenpyoSearch txtSyohyNO  Enter Tab"
                            tabindex="4" />
                    </td>
                    <td></td>
                    <td>
                        <input type="text" maxlength="10" class="HMDPS100DenpyoSearch txtDateTo Datepicker Enter Tab"
                            tabindex="13" />
                    </td>
                    <td></td>
                    <td></td>
                    <td><label class="HMDPS100DenpyoSearch Label6 lbl-sky-L" for="">貸方科目コード</label></td>
                    <td>
                        <input type="text" maxlength="5" class="HMDPS100DenpyoSearch txtRKamokuCD Enter Tab"
                            tabindex="19" />
                    </td>
                    <td>
                        <button
                            class="HMDPS100DenpyoSearch btnRKSearch HMDPS100DenpyoSearchButton samllButton Enter Tab"
                            tabindex="20">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HMDPS100DenpyoSearch lblRkamokuNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td><label class="HMDPS100DenpyoSearch Label13 lbl-sky-L" for=""> 印刷状態 </label>
                        <input type="radio" value="radPrintNoSel" name="PRINTKIND" checked="checked"
                            class='HMDPS100DenpyoSearch radPrintNoSel Tab Enter' tabindex="5" />
                        <label class="HMDPS100DenpyoSearch radPrintNoSelDiv" for="">指定しない</label>
                        <input type="radio" value="radPrintMi" name="PRINTKIND"
                            class='HMDPS100DenpyoSearch radPrintMi Tab Enter' tabindex="6" />
                        <label class="HMDPS100DenpyoSearch radPrintMiDiv" for="">未</label>
                        <input type="radio" value="radPrintSumi" name="PRINTKIND"
                            class='HMDPS100DenpyoSearch radPrintSumi Tab Enter' tabindex="7" />
                        <label class="HMDPS100DenpyoSearch radPrintSumiDiv" for="">済</label>
                    </td>
                    <td></td>
                    <td rowspan="2"><label class="HMDPS100DenpyoSearch Label8" for=""> 支払予定日 </label></td>
                    <td>
                        <input type="text" maxlength="10"
                            class="HMDPS100DenpyoSearch txtShiharaiDTFrom Datepicker Enter Tab" tabindex="14" />
                    </td>
                    <td>～</td>
                    <td></td>
                    <td><label class="HMDPS100DenpyoSearch Label9 lbl-sky-L" for="">作成部署</label></td>
                    <td>
                        <input type="text" maxlength="3" class="HMDPS100DenpyoSearch txtBusyoCD Enter Tab"
                            tabindex="21" />
                    </td>
                    <td>
                        <button
                            class="HMDPS100DenpyoSearch btnBusyoSearch HMDPS100DenpyoSearchButton samllButton Enter Tab"
                            tabindex="22">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HMDPS100DenpyoSearch lblBusyoNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td><label class="HMDPS100DenpyoSearch lblCsvName lbl-sky-L" for="">経理課処理済</label>
                        <input type="radio" value="radCsvNoSel" name="CSVKIND" checked="checked"
                            class='HMDPS100DenpyoSearch radCsvNoSel Tab Enter' tabindex="8" />
                        <label class="HMDPS100DenpyoSearch radCsvNoSelDiv" for="">指定しない</label>
                        <input type="radio" value="radCsvMi" name="CSVKIND"
                            class='HMDPS100DenpyoSearch radCsvMi Tab Enter' tabindex="9" />
                        <label class="HMDPS100DenpyoSearch radCsvMiDiv" for="">未</label>
                        <input type="radio" value="radCsvSumi" name="CSVKIND"
                            class='HMDPS100DenpyoSearch radCsvSumi Tab Enter' tabindex="10" />
                        <label class="HMDPS100DenpyoSearch radCsvSumiDiv" for="">済</label>
                    </td>
                    <td></td>
                    <td>
                        <input type="text" maxlength="10"
                            class="HMDPS100DenpyoSearch txtShiharaiDTEnd Datepicker Enter Tab" tabindex="15" />
                    </td>
                    <td></td>
                    <td></td>
                    <td><label class="HMDPS100DenpyoSearch Label11 lbl-sky-L" for="">作成者</label></td>
                    <td>
                        <input type="text" maxlength="5" class="HMDPS100DenpyoSearch txtSyainNO Enter Tab"
                            tabindex="23" />
                    </td>
                    <td>
                        <button
                            class="HMDPS100DenpyoSearch btnSyainSearch HMDPS100DenpyoSearchButton samllButton Enter Tab"
                            tabindex="24">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="HMDPS100DenpyoSearch lblSyainNM disabledNM" disabled="disabled" />
                    </td>
                </tr>
                <tr>
                    <td><label class="HMDPS100DenpyoSearch lblFukanzen lbl-sky-L" for="">不完全</label>
                        <input type="checkbox" class="HMDPS100DenpyoSearch chkFukanzen  Enter Tab" tabindex="11" />
                        <label class="HMDPS100DenpyoSearch lblFukanzen" for="">不完全</label>
                    </td>
                    <td></td>
                    <td><label class="HMDPS100DenpyoSearch lblKeyWord" for="">キーワード</label></td>
                    <td colspan="8">
                        <input type="text" maxlength="20" class="HMDPS100DenpyoSearch txtKeyWord Enter Tab"
                            tabindex="16" />
                        <button class="HMDPS100DenpyoSearch btnMicheckPrint HMDPS100DenpyoSearchButton Enter Tab"
                            tabindex="26">
                            未チェック一覧印刷
                        </button>
                        <button class="HMDPS100DenpyoSearch btnSearch HMDPS100DenpyoSearchButton Enter Tab"
                            tabindex="25">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HMDPS100DenpyoSearch HMS-button-pane">
            <button class="HMDPS100DenpyoSearch btnNew HMDPS100DenpyoSearchButton Enter Tab" tabindex="27">
                新規作成
            </button>
        </div>
        <div class="HMDPS100DenpyoSearch pnlList HMS-bottom">
            <table class="HMDPS100DenpyoSearch grdList " id="HMDPS100DenpyoSearch_grdList"></table>
            <div id="HMDPS100DenpyoSearch_pager"></div>
        </div>
        <div class="HMDPS100DenpyoSearch pnlallbutton HMS-button-pane HMS-bottom2">
            <button class="HMDPS100DenpyoSearch btnAllSelect HMDPS100DenpyoSearchButton Enter Tab" tabindex="28">
                全て選択
            </button>
            <button class="HMDPS100DenpyoSearch btnAllKaijyo HMDPS100DenpyoSearchButton Enter Tab" tabindex="29">
                選択解除
            </button>
            <button class="HMDPS100DenpyoSearch btnDenpyPrint HMDPS100DenpyoSearchButton Enter Tab" tabindex="30">
                伝票印刷
            </button>
        </div>
    </div>
</div>