<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmUriBusyoCnvEdit/FrmUriBusyoCnvEdit"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmUriBusyoCnvEdit'>
    <div class='FrmUriBusyoCnvEdit content R4-content' style="width: 520px">
        <div>
            <table>
                <tr>
                    <td>
                        <label class="FrmUriBusyoCnvEdit Label13 lbl-blue-M" for="">
                            注文書番号
                        </label>
                    </td>
                    <td>
                        <input class="FrmUriBusyoCnvEdit txtCMNNO1 Enter" style="width: 95px" maxlength="10" />
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <fieldset>
                <legend>
                    売上データ
                </legend>
                <table>
                    <tr>
                        <td>
                            <label class="FrmUriBusyoCnvEdit Label1 lbl-grey-M" for="">
                                社員番号
                            </label>
                        </td>
                        <td>
                            <label class="FrmUriBusyoCnvEdit lblSYAINNO"
                                style="width: 56px;height: 21px;border: solid 1px;margin-top: 3px" for="">
                            </label>
                        </td>
                        <td>
                            <label class="FrmUriBusyoCnvEdit lblSYAINNM"
                                style="width: 226px;height: 21px;border: solid 1px;margin-top: 3px" for="">
                            </label>
                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmUriBusyoCnvEdit Label4 lbl-grey-M" for="">
                                契約者名称
                            </label>
                        </td>
                        <td colspan="2">
                            <label class="FrmUriBusyoCnvEdit lblKEIYAKUNM"
                                style="width: 285px;height: 21px;border: solid 1px ;margin-top: 3px" for="">
                            </label>
                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmUriBusyoCnvEdit Label6 lbl-grey-M" for="">
                                部署
                            </label>
                        </td>
                        <td>
                            <label class="FrmUriBusyoCnvEdit lblBUSYONO"
                                style="width: 56px;height: 21px;border: solid 1px;margin-top: 3px" for="">
                            </label>
                        </td>
                        <td>
                            <label class="FrmUriBusyoCnvEdit lblBUSYONM"
                                style="width: 226px;height: 21px;border: solid 1px;margin-top: 3px" for="">
                            </label>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="height: 8px">
        </div>
        <div>
            <table>
                <tr>
                    <td>
                        <label class="FrmUriBusyoCnvEdit Label9 lbl-blue-M" for="">
                            変更後部署
                        </label>

                    </td>
                    <td>
                        <input class="FrmUriBusyoCnvEdit txtCMNNO2 Enter" style="width: 40px" maxlength="3" />
                    </td>
                    <td>
                        <label class="FrmUriBusyoCnvEdit lblCMNO2NM"
                            style="width: 226px;height: 21px;border: solid 1px;margin-top: 3px" for="">
                        </label>
                    </td>
                    <td>
                        <label class="FrmUriBusyoCnvEdit lblCreateDate"
                            style="width: 100px;height: 21px;visibility: hidden" for="">
                        </label>
                    </td>
                </tr>
            </table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmUriBusyoCnvEdit cmdUpdate Enter">
                    更新
                </button>
                <button class="FrmUriBusyoCnvEdit cmdBack Enter">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
