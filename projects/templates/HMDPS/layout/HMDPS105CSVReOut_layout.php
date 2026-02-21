<!-- /**
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付					Feature/Bug						     内容											担当
 * YYYYMMDD				#ID									XXXXXX											GSDL
 * 20240426		    バーコード読取・CSV出力		   グリッドの高さ・幅が ウインドウのサイズに追従する		   lujunxia
 * -------------------------------------------------------------------------------------------------------------------------------------
 */ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS105CSVReOut/HMDPS105CSVReOut")); ?>
<style type="text/css">
    .HMDPS105CSVReOut.txtGroupName {
        width: 159px;
    }

    .HMDPS105CSVReOut.KensakuDiv {
        padding: 30px 0px 0px 0px;
    }

    .HMDPS105CSVReOut.Kensaku {
        min-width: 100px;
    }

    .HMDPS105CSVReOut.tableSearch {
        display: inline;
    }

    .HMDPS105CSVReOut.grdGroupList tr.jqgrow td,
    .HMDPS105CSVReOut.pnlCsvOut tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    input[type='text'][maxlength='3'],
    .HMDPS105CSVReOut.lvTxtCount {
        width: 50px;
    }

    .HMDPS105CSVReOut fieldset {
        padding: 0px 5px 0px;
    }

    .HMDPS105CSVReOut.lbl-sky-xL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 2px;
        width: 120px;
    }

    .HMDPS105CSVReOut .ui-jqgrid.ui-widget.ui-widget-content.ui-corner-all {
        padding: 2px 0px 2px;
    }

    .HMDPS105CSVReOut.lvTxtKingakuSum {
        text-align: right;
        width: 160px;
    }

    .HMDPS105CSVReOut.lvTxtCount {
        text-align: right;
    }

    .HMDPS105CSVReOut input[type='text'][readonly='readonly'] {
        background-color: #BABEC1;
    }

    .HMDPS105CSVReOut.tableSearch button {
        min-width: 60px;
    }

    /* 20240426 lujunxia ins s */
    #gview_HMDPS105CSVReOut_grdGroupList .ui-jqgrid-bdiv {
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    /* 20240426 lujunxia ins e */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMDPS105CSVReOut.lbl-sky-xL {
            width: 87px;
        }

        .HMDPS105CSVReOut.lvTxtKingakuSum {
            width: 125px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='HMDPS105CSVReOut body'>
    <div class='HMDPS105CSVReOut HMDPS-content'>
        <fieldset>
            <legend class="HMDPS105CSVReOut lblSearchTitle">
                <b><span>検索条件</span></b>
            </legend>
            <table class='HMDPS105CSVReOut tableSearch'>
                <tr class="HMDPS105CSVReOut">
                    <td><label for="" class='HMDPS105CSVReOut Label1 lbl-sky-L'> グループ名 </label></td>
                    <td colspan="2">
                        <input class="HMDPS105CSVReOut txtGroupName Enter Tab" maxlength="40" tabindex="1" />
                    </td>
                    <td><label for="" class='HMDPS105CSVReOut Label2 lbl-sky-L'> 部署コード </label></td>
                    <td>
                        <input type="text" class='HMDPS105CSVReOut ltxtBusyoCD  Tab Enter' maxlength="3" tabindex="4" />
                    </td>
                    <td>
                        <button class='HMDPS105CSVReOut btnBusyo Enter Tab ' tabindex="5">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="HMDPS105CSVReOut BusyoNM Enter Tab" disabled="true" />
                    </td>
                </tr>
                <tr class="HMDPS105CSVReOut">
                    <td><label for="" class='HMDPS105CSVReOut Label2 lbl-sky-L'> CSV出力日 </label></td>
                    <td>
                        <input type="text" class='HMDPS105CSVReOut CSVStart  Tab Enter' maxlength="10" tabindex="2" />
                    </td>
                    <td> ～
                        <input type="text" class='HMDPS105CSVReOut CSVEnd  Tab Enter' maxlength="10" tabindex="3" />
                    </td>
                    <td><label for="" class='HMDPS105CSVReOut Label2 lbl-sky-L'> 担当者コード </label></td>
                    <td>
                        <input type="text" class='HMDPS105CSVReOut ltxtTantouCD Tab Enter' maxlength="5" tabindex="6" />
                    </td>
                    <td>
                        <button class='HMDPS105CSVReOut btnTantou Enter Tab ' tabindex="7">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="HMDPS105CSVReOut TantouNM Enter Tab" disabled="true" />
                    </td>
                </tr>
            </table>
            <div class='HMDPS105CSVReOut KensakuDiv HMS-button-set'>
                <button class='HMDPS105CSVReOut Kensaku Enter Tab' tabindex="8">
                    検索
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <table>
            <tr>
                <td valign="top">
                    <div class='HMDPS105CSVReOut grdGroupListTableRow'>
                        <table class="HMDPS105CSVReOut grdGroupList Enter Tab" id="HMDPS105CSVReOut_grdGroupList">
                        </table>
                        <div id="HMDPS105CSVReOut_pager"></div>
                    </div>
                </td>

                <td>
                    <div class='HMDPS105CSVReOut PnlCsvOutTableRow'>
                        <div>
                            <label for="" class='HMDPS105CSVReOut Label1 lbl-sky-L'> グループ名 </label>
                            <input class="HMDPS105CSVReOut txtGroupNO Enter Tab" hidden />
                            <input class="HMDPS105CSVReOut txtInputGroupNM Enter Tab" maxlength="40" tabindex="1" />

                        </div>
                        <div>
                            <label for="" class='HMDPS105CSVReOut Label1 lbl-sky-L'> 経理処理日 </label>
                            <input type="text" class='HMDPS105CSVReOut  txtInputKeiriDt  Tab Enter' maxlength="10"
                                tabindex="1" />
                        </div>
                        <div>
                            <table class="HMDPS105CSVReOut pnlCsvOut Enter Tab" id="HMDPS105CSVReOut_pnlCsvOut"></table>
                        </div>
                        <div class="HMDPS105CSVReOut HMS-button-pane">
                            <label for="" class='HMDPS105CSVReOut Label1 lbl-sky-L'> 出力対象件数 </label>
                            <input type="text" class='HMDPS105CSVReOut lvTxtCount Tab Enter' readonly="readonly" />
                            <label for="" class='HMDPS105CSVReOut Label1 lbl-sky-xL'>出力対象金額合計</label>
                            <input type="text" class='HMDPS105CSVReOut lvTxtKingakuSum  Tab Enter'
                                readonly="readonly" />
                            <button class='HMDPS105CSVReOut btnCsvOut Enter Tab HMS-button-set'>
                                CSV出力
                            </button>
                        </div>
                        <div class="HMDPS105CSVReOut lbl-content">
                            ※背景色がオレンジ色の行は前回のＣＳＶ出力後にデータの修正が行われた証憑です。
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>