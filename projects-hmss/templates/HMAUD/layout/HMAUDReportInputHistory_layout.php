<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDReportInputHistory/HMAUDReportInputHistory'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMAUDReportInputHistory.HMAUD-content {
        border: 1px #a6c9e2 solid !important;
    }

    .HMAUDReportInputHistory.HMS-button-pane {
        text-align: center;
    }

    .HMAUDReportInputHistory .line-height {
        height: 10px;
    }

    .HMAUDReportInputHistory .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: break-spaces !important;
    }

    .HMAUDReportInputHistory .label-td {
        width: 80px;
    }
</style>

<div class="HMAUDReportInputHistory HMAUDReportInputHistoryDialog">
    <div class="HMAUDReportInputHistory HMAUD-content">
        <div class="HMAUDReportInputHistoryList">
            <table>
                <tr>
                    <td class="label-td">クール</td>
                    <td class="firstTd">
                        <input type="text" class="date-input COURS" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td>拠点</td>
                    <td>
                        <input type="text" class="label-input KYOTEN_NAME" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td>領域</td>
                    <td>
                        <input type="text" class="label-input TERRITORY" readonly="readonly" />
                    </td>
                </tr>
                <tr class="line-height"></tr>
                <tr>
                    <td colspan="2">
                        <table id="HMAUDReportInputHistoryTb"></table>
                    </td>
                </tr>
            </table>

        </div>
        <div class="HMAUDReportInputHistory HMS-button-pane">
            <button class="HMAUDReportInputHistory btnClose button Enter Tab" tabindex="2">
                戻る
            </button>
        </div>
        <div class="HMAUDReportInputHistory hidCrDate"></div>
    </div>
</div>