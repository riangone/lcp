<!-- /**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240328      全銀協連携データ出力       画面構成を OBC取込データ出力 と同じ構成                 lujunxia
 * -------------------------------------------------------------------------------------------------------
 */ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKOut4ZenGin/HDKOut4ZenGin"));
?>

<style type="text/css">
    .HDKOut4ZenGin.labelColor1 {
        color: #000080;
    }

    .HDKOut4ZenGin.LBL_TITLE_STD9 {
        background-color: #191970;
        border: solid 1px black;
        padding: 0px 2px;
        color: white;
    }

    .HDKOut4ZenGin input[maxlength="10"] {
        width: 100px;
    }

    .HDKOut4ZenGin input[maxlength="5"] {
        width: 50px;
    }

    .HDKOut4ZenGin input[maxlength="16"] {
        width: 149px;
    }

    .HDKOut4ZenGin.LBL_TITLE_WIDTH2 {
        width: 116px;
    }

    .HDKOut4ZenGin.TXT_STD9_XL {
        width: 200px;
    }

    .HDKOut4ZenGin.CELL_BORDER {
        border: solid 1px #808080;
        margin-bottom: 10px;
        padding-top: 2px;
        padding-left: 2px;
    }

    .HDKOut4ZenGin.margin0 {
        margin-top: 0px;
        margin-bottom: 2px;
    }

    /*blur時 高さが変化する問題を防ぐ*/
    .HDKOut4ZenGin.lKamokuLabel,
    .HDKOut4ZenGin.rKamokuLabel,
    .HDKOut4ZenGin.busyoLabel,
    .HDKOut4ZenGin.tanntousyaLabel {
        height: auto;
    }

    .HDKOut4ZenGin.textAlign {
        text-align: right;
    }

    .HDKOut4ZenGin.marginBottom {
        margin-bottom: 5px;
    }

    .HDKOut4ZenGin.containTable {
        width: 100%;
    }

    /*jggrid改行表示*/
    .HDKOut4ZenGin .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HDKOut4ZenGin input[type='text'][readonly='readonly'] {
        background-color: #BABEC1
    }

    .HDKOut4ZenGin .lvTxtCount {
        width: 80px !important;
    }

    .HDKOut4ZenGin .lvTxtKingakuSum {
        width: 150px !important;
    }

    .HDKOut4ZenGin.searchClass button {
        margin-left: 0px !important;
    }

    .HDKOut4ZenGin.searchClass {
        margin-top: 4px;
    }

    /* 20240328 lujunxia upd s */
    #gview_HDKOut4ZenGin_table .ui-jqgrid-bdiv,
    #gview_HDKOut4ZenGin_table_selected .ui-jqgrid-bdiv {
        overflow-x: hidden;
        scrollbar-width: thin;
    }

    .HDKOut4ZenGin.clearBtn {
        float: right;
    }

    .HDKOut4ZenGin.noselectBtn {
        margin: 0px 20%;
    }

    .HDKOut4ZenGin.button-pane-middle {
        margin-top: 1ex;
    }

    /* 20240328 lujunxia upd e */
    .HDKOut4ZenGin .ui-jqgrid .ui-jqgrid-htable th,
    .HDKOut4ZenGin .btnBusyoSearch,
    .HDKOut4ZenGin .btnTanntousyaSearch,
    .HDKOut4ZenGin .btnLKamokuSearch,
    .HDKOut4ZenGin .btnRKamokuSearch {
        height: 22px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HDKOut4ZenGin .ui-jqgrid .ui-jqgrid-htable th,
        .HDKOut4ZenGin .btnBusyoSearch,
        .HDKOut4ZenGin .btnTanntousyaSearch,
        .HDKOut4ZenGin .btnLKamokuSearch,
        .HDKOut4ZenGin .btnRKamokuSearch {
            height: 18px;
        }
    }
</style>

<div class="HDKOut4ZenGin">
    <div class="HDKOut4ZenGin HDKAIKEI-content">
        <table class="HDKOut4ZenGin containTable" border="0">
            <tr>
                <td valign="top">
                    <div class="HDKOut4ZenGin">
                        <!-- 1.データ検索 start -->
                        <label for="" class="HDKOut4ZenGin labelColor1"> 1.データ検索 </label>
                        <div class="HDKOut4ZenGin CELL_BORDER">
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">支払予定日</label>
                                <input class="HDKOut4ZenGin datepickerStyle keiriSyoribiFrom Enter Tab" maxlength="10"
                                    tabindex="1" />
                                ～
                                <input class="HDKOut4ZenGin datepickerStyle keiriSyoribiTo Enter Tab" maxlength="10"
                                    tabindex="2" />
                            </div>
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">部署</label>
                                <input class="HDKOut4ZenGin busyoInput Enter Tab" maxlength="16" tabindex="3" />
                                <button class='HDKOut4ZenGin buttonStyle btnBusyoSearch Enter Tab' tabindex="4">
                                    検索
                                </button>
                                <!-- 40 -->
                                <label for="" class="HDKOut4ZenGin busyoLabel"></label>
                            </div>
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">作成担当者</label>
                                <input class="HDKOut4ZenGin tanntousyaInput Enter Tab" maxlength="5" tabindex="5" />
                                <button class='HDKOut4ZenGin buttonStyle btnTanntousyaSearch Enter Tab' tabindex="6">
                                    検索
                                </button>
                                <!-- 20 -->
                                <label for="" class="HDKOut4ZenGin tanntousyaLabel"></label>
                            </div>
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">借方科目</label>
                                <input class="HDKOut4ZenGin lKamokuInput Enter Tab" maxlength="5" tabindex="7" />
                                <button class='HDKOut4ZenGin buttonStyle btnLKamokuSearch Enter Tab' tabindex="8">
                                    検索
                                </button>
                                <label for="" class="HDKOut4ZenGin lKamokuLabel"></label>
                            </div>
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">貸方科目</label>
                                <input class="HDKOut4ZenGin rKamokuInput Enter Tab" maxlength="5" tabindex="9" />
                                <button class='HDKOut4ZenGin buttonStyle btnRKamokuSearch Enter Tab' tabindex="10">
                                    検索
                                </button>
                                <!-- 60 -->
                                <label for="" class="HDKOut4ZenGin rKamokuLabel"></label>
                            </div>
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">キーワード</label>
                                <input class="HDKOut4ZenGin keywordInput Enter Tab" maxlength="255" tabindex="11" />
                            </div>
                            <div class="HDKOut4ZenGin HMS-button-pane searchClass">
                                <button class='HDKOut4ZenGin buttonStyle btnSearch Enter Tab' tabindex="12">
                                    検索
                                </button>
                                <button class='HDKOut4ZenGin buttonStyle btnCancle Enter Tab' tabindex="13">
                                    キャンセル
                                </button>
                            </div>
                        </div>
                        <!-- 1.データ検索 end -->
                        <!-- 2.全銀協出力 start -->
                        <label for="" class="HDKOut4ZenGin labelColor1"> 2.全銀協出力 </label>
                        <div class="HDKOut4ZenGin CELL_BORDER">
                            <label for=""> 一覧の全銀協欄にチェックが入っている伝票の経理処理日に</label>
                            <br />
                            <label for="" class="HDKOut4ZenGin marginBottom">下記で指定した日付を登録し、全銀協を出力します。 </label>
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">経理処理日</label>
                                <input class="HDKOut4ZenGin datepickerStyle lvTxtKeiriSyoribi Enter Tab" maxlength="10"
                                    tabindex="14" />
                            </div>
                            <div class="HDKOut4ZenGin margin0">
                                <label for="" class="HDKOut4ZenGin lbl-sky-L">出力グループ名</label>
                                <input type="text" class="HDKOut4ZenGin lvTxtGroupName TXT_STD9_XL Enter Tab"
                                    tabindex="15" />
                            </div>
                            <div class="HDKOut4ZenGin HMS-button-pane">
                                <button class='HDKOut4ZenGin buttonStyle btnCsvOut HMS-button-set Enter Tab'
                                    tabindex="16">
                                    全銀協出力
                                </button>
                            </div>
                        </div>
                        <!-- 2.全銀協出力 end -->
                    </div>
                </td>
                <td width="60%">
                    <div class="HDKOut4ZenGin rightList">
                        <label for="" class="HDKOut4ZenGin labelColor1"> 一覧 </label>
                        <table class="HDKOut4ZenGin" id="HDKOut4ZenGin_table"></table>
                        <!-- 20240328 lujunxia ins s -->
                        <div class="HDKOut4ZenGin HMS-button-pane button-pane-middle">
                            <button class='HDKOut4ZenGin Enter Tab buttonStyle noselectBtn' tabindex="17">
                                ↑
                            </button>
                            <button class='HDKOut4ZenGin Enter Tab buttonStyle selectBtn' tabindex="18">
                                ↓
                            </button>
                            <button class='HDKOut4ZenGin Enter Tab buttonStyle clearBtn' tabindex="19">
                                クリア
                            </button>
                        </div>
                        <table class="HDKOut4ZenGin" id="HDKOut4ZenGin_table_selected"></table>
                        <!-- 20240328 lujunxia ins e -->
                    </div>
                </td>
            </tr>
            <tr class="HDKOut4ZenGin rightList">
                <td colspan="2" class="HDKOut4ZenGin textAlign"><label for=""
                        class="HDKOut4ZenGin LBL_TITLE_STD9 LBL_TITLE_WIDTH2">出力対象件数</label>
                    <!-- 20240328 lujunxia upd s -->
                    <input type="text" class="HDKOut4ZenGin lvTxtCount textAlign Enter Tab" tabindex="20"
                        readonly="readonly" />
                    <!-- 20240328 lujunxia upd e -->
                    <label for="" class="HDKOut4ZenGin LBL_TITLE_STD9 LBL_TITLE_WIDTH2">出力対象金額合計</label>
                    <!-- 20240328 lujunxia upd s -->
                    <input type="text" class="HDKOut4ZenGin lvTxtKingakuSum textAlign LBL_TITLE_WIDTH2  Enter Tab"
                        tabindex="21" readonly="readonly" />
                    <!-- 20240328 lujunxia upd e -->
                </td>
            </tr>
        </table>
    </div>
</div>