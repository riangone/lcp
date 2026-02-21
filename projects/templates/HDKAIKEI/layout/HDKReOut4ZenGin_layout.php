<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKReOut4ZenGin/HDKReOut4ZenGin"));
?>
<style type="text/css">
    .HDKReOut4ZenGin.txtGroupName {
        width: 159px;
    }

    .HDKReOut4ZenGin.KensakuDiv {
        padding: 30px 0px 0px 0px;
    }

    .HDKReOut4ZenGin.Kensaku {
        min-width: 100px;
    }

    .HDKReOut4ZenGin.tableSearch {
        display: inline;
    }

    .HDKReOut4ZenGin.grdGroupList tr.jqgrow td,
    .HDKReOut4ZenGin.pnlCsvOut tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HDKReOut4ZenGin.lvTxtCount {
        width: 50px;
    }

    .HDKReOut4ZenGin fieldset {
        padding: 0px 5px 0px;
    }

    .HDKReOut4ZenGin.lbl-sky-xL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 2px;
        width: 120px;
    }

    .HDKReOut4ZenGin .ui-jqgrid.ui-widget.ui-widget-content.ui-corner-all {
        padding: 2px 0px 2px;
    }

    .HDKReOut4ZenGin.lvTxtKingakuSum {
        text-align: right;
        width: 160px;
    }

    .HDKReOut4ZenGin.lvTxtCount {
        text-align: right;
    }

    .HDKReOut4ZenGin input[type='text'][readonly='readonly'] {
        background-color: #BABEC1;
    }

    .HDKReOut4ZenGin.tableSearch button {
        min-width: 60px;
    }

    .HDKReOut4ZenGin.ltxtBusyoCD,
    .HDKReOut4ZenGin.ltxtTantouCD {
        width: 145px !important;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='HDKReOut4ZenGin body'>
    <div class='HDKReOut4ZenGin HDKAIKEI-content'>
        <fieldset>
            <legend class="HDKReOut4ZenGin lblSearchTitle">
                <b><span>検索条件</span></b>
            </legend>
            <table class='HDKReOut4ZenGin tableSearch'>
                <tr class="HDKReOut4ZenGin">
                    <td><label for="" class='HDKReOut4ZenGin Label1 lbl-sky-L'> グループ名 </label></td>
                    <td colspan="2">
                        <input class="HDKReOut4ZenGin txtGroupName Enter Tab" maxlength="40" tabindex="1" />
                    </td>
                    <td><label for="" class='HDKReOut4ZenGin Label2 lbl-sky-L'> 部署コード </label></td>
                    <td>
                        <input type="text" class='HDKReOut4ZenGin ltxtBusyoCD  Tab Enter' maxlength="16" tabindex="4" />
                    </td>
                    <td>
                        <button class='HDKReOut4ZenGin btnBusyo Enter Tab ' tabindex="5">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="HDKReOut4ZenGin BusyoNM Enter Tab" disabled="true" />
                    </td>
                </tr>
                <tr class="HDKReOut4ZenGin">
                    <td><label for="" class='HDKReOut4ZenGin Label2 lbl-sky-L'> 全銀協出力日 </label></td>
                    <td>
                        <input type="text" class='HDKReOut4ZenGin CSVStart  Tab Enter' maxlength="10" tabindex="2" />
                    </td>
                    <td> ～
                        <input type="text" class='HDKReOut4ZenGin CSVEnd  Tab Enter' maxlength="10" tabindex="3" />
                    </td>
                    <td><label for="" class='HDKReOut4ZenGin Label2 lbl-sky-L'> 担当者コード </label></td>
                    <td>
                        <input type="text" class='HDKReOut4ZenGin ltxtTantouCD Tab Enter' maxlength="5" tabindex="6" />
                    </td>
                    <td>
                        <button class='HDKReOut4ZenGin btnTantou Enter Tab ' tabindex="7">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="HDKReOut4ZenGin TantouNM Enter Tab" disabled="true" />
                    </td>
                </tr>
            </table>
            <div class='HDKReOut4ZenGin KensakuDiv HMS-button-set'>
                <button class='HDKReOut4ZenGin Kensaku Enter Tab' tabindex="8">
                    検索
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <table>
            <tr>
                <td valign="top">
                    <div class='HDKReOut4ZenGin grdGroupListTableRow'>
                        <table class="HDKReOut4ZenGin grdGroupList Enter Tab" id="HDKReOut4ZenGin_grdGroupList"></table>
                        <div id="HDKReOut4ZenGin_pager"></div>
                    </div>
                </td>

                <td>
                    <div class='HDKReOut4ZenGin PnlCsvOutTableRow'>
                        <div>
                            <label for="" class='HDKReOut4ZenGin Label1 lbl-sky-L'> グループ名 </label>
                            <input class="HDKReOut4ZenGin txtGroupNO Enter Tab" hidden />
                            <input class="HDKReOut4ZenGin txtInputGroupNM Enter Tab" maxlength="40" tabindex="1" />

                        </div>
                        <div>
                            <label for="" class='HDKReOut4ZenGin Label1 lbl-sky-L'> 経理処理日 </label>
                            <input type="text" class='HDKReOut4ZenGin  txtInputKeiriDt  Tab Enter' maxlength="10"
                                tabindex="1" />
                        </div>
                        <div>
                            <table class="HDKReOut4ZenGin pnlCsvOut Enter Tab" id="HDKReOut4ZenGin_pnlCsvOut"></table>
                        </div>
                        <div class="HDKReOut4ZenGin HMS-button-pane">
                            <label for="" class='HDKReOut4ZenGin Label1 lbl-sky-L'> 出力対象件数 </label>
                            <input type="text" class='HDKReOut4ZenGin lvTxtCount Tab Enter' readonly="readonly" />
                            <label for="" class='HDKReOut4ZenGin Label1 lbl-sky-xL'>出力対象金額合計</label>
                            <input type="text" class='HDKReOut4ZenGin lvTxtKingakuSum  Tab Enter' readonly="readonly" />
                            <button class='HDKReOut4ZenGin btnCsvOut Enter Tab HMS-button-set'>
                                全銀協出力
                            </button>
                        </div>
                        <div class="HDKReOut4ZenGin lbl-content">
                            ※背景色がオレンジ色の行は前回の全銀協出力後にデータの修正が行われた証憑です。
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>