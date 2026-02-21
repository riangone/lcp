<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                           担当
* YYYYMMDD              #ID                     	XXXXXX                        FCSDL
* 20230103           	機能追加　　　　　　        20221226_内部統制_仕様変更      YIN
* 20240313                 画面上の表記「常務」を「取締役」に変更お願いします       caina
* 20250403              機能追加　　　　　　 202504_内部統制_要望.xlsx               YIN
* 20251016              機能追加      202510_内部統制システム_仕様変更対応.xlsx      YIN
* 20251224     「副社長」——> 「社長」      202512_内部統制_変更要望.xlsx            YIN
* 20260126     「社長」欄を１つ廃止     202601_内部統制_変更要望.xlsx               YIN
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMAUD/HMAUDSKDToroku/HMAUDSKDToroku"));
?>
<style type="text/css">
    .HMAUDSKDToroku.lbl-sky-L {
        width: 102px;
    }

    .HMAUDSKDToroku.HMS-button-pane {
        margin-top: 5px;
    }

    .HMAUDSKDToroku.pnlList {
        margin-top: 5px;
    }

    .HMAUDSKDToroku.HMAUD-content {
        /* 20250403 YIN UPD S */
        /* height: 686px; */
        height: 518px;
        /* 20250403 YIN UPD E */
    }

    .HMAUDSKDToroku .line-height {
        height: 5px;
    }

    .HMAUDSKDToroku .date-input {
        width: 100px;
        text-align: right;
    }

    .HMAUDSKDToroku .label-input {
        width: 120px;
    }

    .HMAUDSKDToroku .LABEL-GREEN {
        background: #81cd81;
    }

    .HMAUDSKDToroku .LABEL-ORANGE {
        background: #ffa26c;
    }

    .HMAUDSKDToroku .LABEL-PURPLE {
        background: #ffb8ff;
    }

    .HMAUDSKDToroku .LABEL-BLUE {
        background: #a4c3fb;
    }

    .HMAUDSKDToroku .grid-label {
        vertical-align: top;
        padding-top: 5px;
    }

    .HMAUDSKDToroku .grid-btn {
        width: 80px;
    }

    .HMAUDSKDToroku .btnGrid {
        width: 80px;
        margin-bottom: 10px;
    }

    /* 20250403 YIN UPD S */
    /* .HMAUDSKDToroku .table-class {
        width: 100%;
    } */
    .HMAUDSKDToroku .left-table-class {
        width: 58%;
        float: left;
    }

    .HMAUDSKDToroku .right-table-class {
        width: 42%;
    }

    /* 20250403 YIN UPD E */

    .HMAUDSKDToroku .firstTd {
        padding-right: 17px;
    }

    #HMAUDSKDTorokutblMain input {
        width: 91% !important;
    }

    /* 20250403 YIN INS S */
    .HMAUDSKDToroku .top-height {
        height: 115px;
    }

    .HMAUDSKDToroku .div-height {
        height: 472px;
    }

    .HMAUDSKDToroku .label-width {
        width: 180px;
    }

    .HMAUDSKDToroku .mr {
        margin-right: 15px;
    }

    /* 20250403 YIN INS E */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMAUDSKDToroku.HMAUD-content {
            height: 490px;
        }

        .HMAUDSKDToroku .div-height {
            height: 450px;
        }

        .HMAUDSKDToroku .right-table-class {
            width: 38%;
        }

        .HMAUDSKDToroku .left-table-class {
            width: 50%;
        }

        .HMAUDSKDToroku .top-height {
            height: 97px;
        }

    }
</style>
<div class="HMAUDSKDToroku body">
    <div class="HMAUDSKDToroku HMAUD-content">
        <!-- 20250403 YIN UPD S -->
        <!-- <div>
            <table class="table-class"> -->
        <div class="div-height">
            <table class="left-table-class">
                <!-- 20250403 YIN UPD E -->
                <tr>
                    <td>クール</td>
                    <td class="firstTd">
                        <input type="text" class="date-input COUR" readonly="readonly" />
                    </td>
                    <td colspan="2">
                        <p class="COUR_DATE"></p>
                    </td>
                </tr>
                <tr>
                    <td>拠点</td>
                    <td>
                        <input type="text" class="date-input KYOTEN_CD" readonly="readonly" />
                    </td>
                    <td>
                        <input type="text" class="label-input KYOTEN_NAME" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td>監査予定日時</td>
                    <td>
                        <input class="date-input PLAN_DT Datepicker" readonly="readonly" />
                    </td>
                    <td>
                        <input class="label-input PLAN_TIME Enter Tab" tabindex="1" />
                    </td>
                </tr>
                <tr>
                    <td>監査日程調整期限</td>
                    <td>
                        <input class="date-input PLAN_LIMIT Datepicker" readonly="true" />
                    </td>
                    <td>（予定日の前日）</td>
                </tr>
                <tr class="line-height"></tr>
                <tr>
                    <td class="LABEL-GREEN">監査人が指摘事項を提示</td>
                    <td>
                        <input class="date-input AUDIT_PRESENT Datepicker" readonly="true" />
                    </td>
                    <td>予定日と同月</td>
                </tr>
                <tr>
                    <td class="LABEL-GREEN">各領域の改善報告書担当の承認</td>
                    <td>
                        <input class="date-input REPORT_TERRITORY_LIMIT Datepicker" readonly="true" />
                    </td>
                    <td>予定日と同月</td>
                </tr>
                <tr>
                    <td class="LABEL-ORANGE">店舗が改善結果入力期限</td>
                    <td>
                        <input class="date-input REPORT_LIMIT Datepicker" readonly="true" />
                    </td>
                    <td>予定日の翌月</td>
                </tr>
                <tr>
                    <td class="LABEL-ORANGE">領域責任者の確認</td>
                    <td>
                        <input class="date-input RESPONSIBLE_TERRITORY_LIMIT Datepicker" readonly="true" />
                    </td>
                    <td>予定日の翌月</td>
                </tr>
                <tr>
                    <td class="LABEL-PURPLE">キーマン確認期限</td>
                    <td>
                        <input class="date-input KEY_PERSON_LIMIT Datepicker" readonly="true" />
                    </td>
                    <td>予定日の翌々月</td>
                </tr>
                <tr>
                    <td class="LABEL-BLUE">監査人Mtg</td>
                    <td>
                        <input class="date-input AUDIT_MEET_DT Datepicker" readonly="true" />
                    </td>
                    <td>（クール最終年月）</td>
                </tr>
                <tr class="line-height"></tr>
                <tr>
                    <td class="grid-label">監査人</td>
                    <td colspan="2">
                        <table id="HMAUDSKDTorokutblMain"></table>
                    </td>
                    <!-- 20250403 YIN INS S -->
                </tr>
                <tr>
                    <td></td>
                    <!-- 20250403 YIN INS E -->
                    <!-- 20250403 YIN UPD S -->
                    <!-- <td class="grid-btn">
                        <button class="HMAUDSKDToroku btnAdd btnGrid Enter Tab" tabindex="2"> -->
                    <td class="grid-btn" colspan="2">
                        <button class="HMAUDSKDToroku btnAdd btnGrid Enter Tab mr" tabindex="2">
                            <!-- 20250403 YIN UPD E -->
                            行追加
                        </button>
                        <button class="HMAUDSKDToroku btnDel btnGrid Enter Tab" tabindex="3">
                            行削除
                        </button>
                    </td>
                </tr>
                <!-- 20250403 YIN INS S -->
            </table>
            <table class="right-table-class">
                <!-- 20250403 YIN INS E -->
                <tr class="top-height"></tr>
                <tr>
                    <td class="label-width">改善報告書担当</td>
                    <td>
                        <input type="text" class="date-input IMPROVEMENT_REPORT Enter Tab" tabindex="4" />
                    </td>
                    <td>
                        <input type="text" class="label-input IMPROVEMENT_REPORT_NAME" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="label-width">改善取組責任者</td>
                    <td>
                        <input type="text" class="date-input RESPONSIBLE_KYOTEN Enter Tab" tabindex="5" />
                    </td>
                    <td>
                        <input type="text" class="label-input RESPONSIBLE_KYOTEN_NAME" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="label-width">各領域責任者</td>
                    <td>
                        <input type="text" class="date-input RESPONSIBLE_TERRITORY Enter Tab" tabindex="6" />
                    </td>
                    <td>
                        <input type="text" class="label-input RESPONSIBLE_TERRITORY_NAME" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="label-width">キーマン</td>
                    <td>
                        <input type="text" class="date-input KEY_PERSON Enter Tab" tabindex="7" />
                    </td>
                    <td>
                        <input type="text" class="label-input KEY_PERSON_NAME" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="label-width">総括責任者</td>
                    <td>
                        <input type="text" class="date-input DIRECTOR_GENERAL Enter Tab" tabindex="8" />
                    </td>
                    <td>
                        <input type="text" class="label-input DIRECTOR_GENERAL_NAME" readonly="readonly" />
                    </td>
                </tr>
                <!-- 20230103 YIN INS S -->
                <tr class="executive-display">
                    <!-- 20240313 caina upd s -->
                    <!-- <td>常務</td> -->
                    <td class="label-width">取締役</td>
                    <!-- 20240313 caina upd e -->
                    <td>
                        <input type="text" class="date-input EXECUTIVE Enter Tab" tabindex="9" />
                    </td>
                    <td>
                        <input type="text" class="label-input EXECUTIVE_NAME" readonly="readonly" />
                    </td>
                </tr>
                <!-- 20230103 YIN INS E -->
                <!-- 20250403 YIN INS S -->
                <tr>
                    <td class="label-width">社長</td>
                    <td>
                        <input type="text" class="date-input VICE_PRESIDENT Enter Tab" tabindex="9" />
                    </td>
                    <td>
                        <input type="text" class="label-input VICE_PRESIDENT_NAME" readonly="readonly" />
                    </td>
                </tr>
                <!-- 20250403 YIN INS S -->
                <tr class="PRESIDENT-DISPLAY">
                    <td class="label-width">社長</td>
                    <td>
                        <input type="text" class="date-input PRESIDENT Enter Tab" tabindex="10" />
                    </td>
                    <td>
                        <input type="text" class="label-input PRESIDENT_NAME" readonly="readonly" />
                    </td>
                </tr>
            </table>
        </div>

        <div class="HMAUDSKDToroku HMS-button-pane">
            <div class="HMAUDSKDToroku HMS-button-set">
                <button class="HMAUDSKDToroku btnUpd Enter Tab" tabindex="10">
                    更新
                </button>
                <button class="HMAUDSKDToroku btnClose Enter Tab" tabindex="11">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>