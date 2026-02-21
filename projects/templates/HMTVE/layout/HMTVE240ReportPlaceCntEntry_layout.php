<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE240ReportPlaceCntEntry/HMTVE240ReportPlaceCntEntry'));
?>
<style type="text/css">
    .HMTVE240ReportPlaceCntEntry.txtTitle {
        width: 20px;
    }

    .HMTVE240ReportPlaceCntEntry.ddlMonth {
        width: 50px;
    }

    .HMTVE240ReportPlaceCntEntry.labelString {
        background-color: #FFFFFF;
        border-width: 2px;
        font-weight: bold;
        text-align: center;
        height: 21px;
        line-height: 21px;
        margin-left: 5px;
    }

    .HMTVE240ReportPlaceCntEntry.btnSearch,
    .HMTVE240ReportPlaceCntEntry.btnLogin,
    .HMTVE240ReportPlaceCntEntry.btnDelete {
        float: right;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE240ReportPlaceCntEntry.searchGroup {
            width: 91% !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE240ReportPlaceCntEntry">
    <div class="HMTVE240ReportPlaceCntEntry HMTVE-content HMTVE-content-fixed-width">
        <fieldset class="HMTVE240ReportPlaceCntEntry searchGroup">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE240ReportPlaceCntEntry tblMain">
                <table class="HMTVE240ReportPlaceCntEntry tableFieldset">
                    <tr>
                        <td><label class="HMTVE240ReportPlaceCntEntry strKbn" for=""></label>
                            <input class="HMTVE240ReportPlaceCntEntry txtTitle Enter Tab" maxlength="2" tabindex="1"
                                onkeyup="this.value=this.value.replace(/\D/g,'')" />
                            <label for="">年</label><select class="HMTVE240ReportPlaceCntEntry ddlMonth Enter Tab"
                                tabindex="2"></select><label class="HMTVE240ReportPlaceCntEntry labelValue"
                                for="">月分軽自動車の保管場所届出件数 及び 検査等申請件数報告書</label>
                        </td>
                    </tr>
                    <tr>
                        <!--20250623 UPDATE START-->
                        <!--
                        <td rowspan="2" valign="top"><label class="HMTVE240ReportPlaceCntEntry labelString lbl-sky-L"
                                for="">毎月15日迄</label></td>
-->
                        <td rowspan="2" valign="top"><label class="HMTVE240ReportPlaceCntEntry labelString lbl-sky-L"
                                for="">毎月10日迄</label></td>
                        <!--20250623 UPDATE START-->
                        <td><label class='HMTVE240ReportPlaceCntEntry Label1 lbl-sky-L' for=""> 店舗名 </label>
                            <input class="HMTVE240ReportPlaceCntEntry lblShopName Enter Tab" readonly="readonly" />
                        </td>
                    </tr>
                    <tr>
                        <td><label class='HMTVE240ReportPlaceCntEntry Label1 lbl-sky-L' for=""> 報告者名 </label>
                            <input class="HMTVE240ReportPlaceCntEntry lblReporterName Enter Tab" readonly="readonly" />
                        </td>
                    </tr>
                </table>
            </div>
            <div class="HMTVE240ReportPlaceCntEntry HMS-button-pane">
                <label for="">登録対象は<b>（GD）市・福山市・呉市・東（GD）市</b>です。</label>
                <button class="HMTVE240ReportPlaceCntEntry btnSearch  button Enter Tab" tabindex="3">
                    表示
                </button>
            </div>
        </fieldset>
        <div class="HMTVE240ReportPlaceCntEntry PnlCsvOutTableRow">
            <table id="HMTVE240ReportPlaceCntEntry_tblSubMain"></table>
            <div class="HMTVE240ReportPlaceCntEntry HMS-button-pane searchGroup">
                <label for=""> ※登録対象のデータがない場合でも、0として登録を行います。 </label>
                <button class="HMTVE240ReportPlaceCntEntry btnDelete  button Enter Tab" tabindex="5">
                    削除
                </button>
                <button class="HMTVE240ReportPlaceCntEntry btnLogin  button Enter Tab" tabindex="4">
                    登録
                </button>
            </div>
        </div>
    </div>
</div>