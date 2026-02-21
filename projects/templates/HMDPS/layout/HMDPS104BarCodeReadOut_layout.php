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
echo $this->Html->script(array("HMDPS/HMDPS104BarCodeReadOut/HMDPS104BarCodeReadOut"));
?>

<style type="text/css">
    .HMDPS104BarCodeReadOut.labelColor1 {
        color: #000080;
    }

    .HMDPS104BarCodeReadOut.LBL_TITLE_STD10 {
        color: #FF6633;
        font-weight: bold;
        font-size: 10pt;
        margin-bottom: 5px;
    }

    .HMDPS104BarCodeReadOut.LBL_TITLE_STD9 {
        background-color: #191970;
        border: solid 1px black;
        padding: 0px 2px;
        color: white;
    }

    .HMDPS104BarCodeReadOut.LBL_TITLE_WIDTH1 {
        width: 100px;
    }

    .HMDPS104BarCodeReadOut.LBL_TITLE_WIDTH2 {
        width: 116px;
    }

    .HMDPS104BarCodeReadOut.displayNone {
        display: none;
    }

    .HMDPS104BarCodeReadOut.TXT_STD9_XL {
        width: 200px;
    }

    .HMDPS104BarCodeReadOut.CELL_BORDER {
        border: solid 1px #808080;
        margin-bottom: 10px;
        padding-top: 2px;
        padding-left: 2px;
    }

    .HMDPS104BarCodeReadOut.margin0 {
        margin-top: 0px;
        margin-bottom: 2px;
    }

    .HMDPS104BarCodeReadOut.txtSyohyoNo {
        width: 170px;
    }

    .HMDPS104BarCodeReadOut.textAlign {
        text-align: right;
    }

    .HMDPS104BarCodeReadOut.marginBottom {
        margin-bottom: 5px;
    }

    .HMDPS104BarCodeReadOut.containTable {
        width: 100%;
    }

    /*jggrid改行表示*/
    .HMDPS104BarCodeReadOut .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMDPS104BarCodeReadOut input[type='text'][readonly='readonly'] {
        background-color: #BABEC1
    }

    .HMDPS104BarCodeReadOut .lvTxtCount {
        width: 80px !important;
    }

    .HMDPS104BarCodeReadOut .lvTxtKingakuSum {
        width: 150px !important;
    }

    /* 20240426 lujunxia upd s */
    /*初期化されたスクロールバーがない場合は、水平スクロールバーを追加*/
    /* .HMDPS104BarCodeReadOut #HMDPS104BarCodeReadOut_table {
        height: 1px;
    } */
    #gview_HMDPS105CSVReOut_pnlCsvOut .ui-jqgrid-bdiv {
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    /* 20240426 lujunxia upd e */

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMDPS104BarCodeReadOut.LBL_TITLE_STD10 {
            font-size: 8pt;
        }

        .HMDPS104BarCodeReadOut.LBL_TITLE_WIDTH1 {
            width: 80px;
        }
    }
</style>

<div class="HMDPS104BarCodeReadOut">
    <div class="HMDPS104BarCodeReadOut HMDPS-content">
        <table class="HMDPS104BarCodeReadOut containTable" border="0">
            <tr>
                <td valign="top">
                    <div class="HMDPS104BarCodeReadOut">
                        <!-- 1.バーコード読取 start -->
                        <label class="HMDPS104BarCodeReadOut labelColor1" for=""> 1.バーコード読取 </label>
                        <div class="HMDPS104BarCodeReadOut CELL_BORDER">
                            <label class="HMDPS104BarCodeReadOut" for=""> 証憑№が選択されている状態でバーコード読取を行ってください。
                                <br />
                                読み取った伝票が一覧に表示されます。
                                <br />
                            </label>
                            <label class="HMDPS104BarCodeReadOut LBL_TITLE_STD10" for="">
                                ※手入力の場合は証憑№入力後、Enterキーを一度押してください。
                            </label>
                            <div class="HMDPS104BarCodeReadOut margin0">
                                <label class="HMDPS104BarCodeReadOut lbl-sky-L" for="">証憑№</label>
                                <input type="text" class="HMDPS104BarCodeReadOut txtSyohyoNo Enter Tab" maxlength="17"
                                    tabindex="1" />
                                <input class="HMDPS104BarCodeReadOut lvTxtSyohyoNo displayNone" maxlength="17" />
                                <button class="HMDPS104BarCodeReadOut cmdEventKeyUp displayNone"></button>
                                <button class="HMDPS104BarCodeReadOut cmdEventEnter displayNone"></button>
                            </div>
                            <div class="HMDPS104BarCodeReadOut margin0">
                                <label class="HMDPS104BarCodeReadOut LBL_TITLE_STD9 LBL_TITLE_WIDTH1"
                                    for="">読取書類</label>
                                <input type="text" class="HMDPS104BarCodeReadOut lvTxtYomitoriSyorui TXT_STD9_XL"
                                    readonly="readonly" />
                            </div>
                        </div>
                        <!-- 1.バーコード読取 end -->
                        <!-- 2.CSV出力 start -->
                        <label class="HMDPS104BarCodeReadOut labelColor1" for=""> 2.CSV出力 </label>
                        <div class="HMDPS104BarCodeReadOut CELL_BORDER">
                            <label for=""> 一覧のCSV欄にチェックが入っている伝票の経理処理日に</label>
                            <br />
                            <label class="HMDPS104BarCodeReadOut marginBottom" for="">下記で指定した日付を登録し、CSVを出力します。 </label>
                            <div class="HMDPS104BarCodeReadOut margin0">
                                <label class="HMDPS104BarCodeReadOut lbl-sky-L" for="">経理処理日</label>
                                <input class="HMDPS104BarCodeReadOut lvTxtKeiriSyoribi LBL_TITLE_WIDTH1 Enter Tab"
                                    maxlength="10" tabindex="2" />
                            </div>
                            <div class="HMDPS104BarCodeReadOut margin0">
                                <label class="HMDPS104BarCodeReadOut lbl-sky-L" for="">出力グループ名</label>
                                <input type="text" class="HMDPS104BarCodeReadOut lvTxtGroupName TXT_STD9_XL Enter Tab"
                                    tabindex="3" />
                            </div>
                            <div class="HMDPS104BarCodeReadOut HMS-button-pane">
                                <button class='HMDPS104BarCodeReadOut btnCsvOut HMS-button-set Enter Tab' tabindex="4">
                                    CSV出力
                                </button>
                            </div>
                        </div>
                        <!-- 2.CSV出力 end -->
                    </div>
                </td>
                <td>
                    <div class="HMDPS104BarCodeReadOut">
                        <label class="HMDPS104BarCodeReadOut labelColor1" for=""> 一覧 </label>
                        <table class="HMDPS104BarCodeReadOut" id="HMDPS104BarCodeReadOut_table"></table>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="HMDPS104BarCodeReadOut textAlign"><label
                        class="HMDPS104BarCodeReadOut LBL_TITLE_STD9 LBL_TITLE_WIDTH2" for="">出力対象件数</label>
                    <input type="text" class="HMDPS104BarCodeReadOut lvTxtCount textAlign LBL_TITLE_WIDTH1 Enter Tab"
                        tabindex="5" readonly="readonly" />
                    <label class="HMDPS104BarCodeReadOut LBL_TITLE_STD9 LBL_TITLE_WIDTH2" for="">出力対象金額合計</label>
                    <input type="text"
                        class="HMDPS104BarCodeReadOut lvTxtKingakuSum textAlign LBL_TITLE_WIDTH2  Enter Tab"
                        tabindex="6" readonly="readonly" />
                </td>
            </tr>
        </table>
    </div>
</div>