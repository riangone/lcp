<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmReOutReportEdit/FrmReOutReportEdit"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmReOutReportEdit'>
    <div class='FrmReOutReportEdit content R4-content' style="width: 895px">
        <div style="margin-top: 10px">
            <table>
                <tr>
                    <td>
                        <label class="FrmReOutReportEdit Label8" for="">
                            完了日
                        </label>
                    </td>
                    <td width="20">
                    </td>
                    <td>
                        <input class="FrmReOutReportEdit cboInpDate Enter Tab" style="width: 100px" maxlength="10" />
                    </td>
                    <td width="100">
                    </td>
                    <td>
                        <button class="FrmReOutReportEdit cmdUpdate Enter Tab">
                            登録(F9)
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div style="height: 10px">
        </div>
        <div>
            <table id="FrmReOutReportEdit_sprList">
            </table>
        </div>
        <div style="height: 10px">
        </div>
        <div>
            <table>
                <tr height="24">
                    <td width="240">
                    </td>
                    <td>
                        <label class="FrmReOutReportEdit Label1"
                            style="width: 120px;height: 21px;background-color: #16b1e9;line-height:21px" for="">
                            当日合計
                        </label>
                    </td>
                    <td>
                        <!-- 20180126 YIN UPD S -->
                        <!-- <div style="border: inset;width: 115px;height: 15px;"> -->
                        <div style="border: solid 1px black;display:block;width: 115px;height: 15px;">
                            <!-- 20180126 YIN UPD E -->
                            <label class="FrmReOutReportEdit lblBuhinGk" style="float: right;display: block" for="">
                            </label>
                        </div>
                    </td>
                    <td>
                        <!-- 20180126 YIN UPD S -->
                        <!-- <div style="border: inset;width: 115px;height: 15px;"> -->
                        <div style="border: solid 1px black;display:block;width: 115px;height: 15px;">
                            <!-- 20180126 YIN UPD E -->
                            <label class="FrmReOutReportEdit lblGaichuGk" style="float: right;display: block" for="">
                            </label>
                        </div>
                    </td>
                    <td>
                        <!-- 20180126 YIN UPD S -->
                        <!-- <div style="border: inset;width: 115px;height: 15px;"> -->
                        <div style="border: solid 1px black;display:block;width: 115px;height: 15px;">
                            <!-- 20180126 YIN UPD E -->
                            <label class="FrmReOutReportEdit lblKouchinGk" style="float: right;display: block" for="">
                            </label>
                        </div>
                    </td>
                    <td>
                        <!-- 20180126 YIN UPD S -->
                        <!-- <div style="border: inset;width: 115px;height: 15px;"> -->
                        <div style="border: solid 1px black;display:block;width: 115px;height: 15px;">
                            <!-- 20180126 YIN UPD E -->
                            <label class="FrmReOutReportEdit lblSouGoukei" style="float: right;display: block" for="">
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
