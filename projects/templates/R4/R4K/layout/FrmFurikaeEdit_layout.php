<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmFurikaeEdit/FrmFurikaeEdit"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmFurikaeEdit R4-content" id="FrmFurikaeEdit">
    <div style="margin-top: 5px">

        <table border="0">
            <tr>
                <!-- 20180204 YIN UPD S -->
                <!-- <td width="70px"> -->
                <td width="71px">
                    <!-- 20180204 YIN UPD E -->
                    <label for="">
                        経理日
                    </label>
                </td>
                <td>
                    <input type="text" class="FrmFurikaeEdit cboKeiriBi Enter Tab" style="width: 100px;" maxlength="10">
                </td>
                <td width="20px">
                </td>
                <td colspan="5">
                </td>
                <!-- <label> -->
                <!-- 取込伝票№ -->
                <!-- </label> -->

                <!-- <td> -->
                <!-- <input  type="text" class="FrmFurikaeEdit txtTorikomiDenpy Enter Tab" style="width: 110px;" maxlength="12"> -->
                <!-- <button class="FrmFurikaeEdit cmdTorikomi Enter Tab">
                取込
                </button> -->
                <!-- </td>
                <td>
                </td>
                <td>

                </td>
                <td>

                </td> -->
            </tr>

            <tr>
                <td>
                    <label for="">
                        伝票№
                    </label>
                </td>
                <td>
                    <input type="text" class="FrmFurikaeEdit txtDenpyoNO Enter Tab" style="width: 110px;"
                        maxlength="12">
                </td>
                <td　width="20px">
                </td>
                <td>

                </td>
                <td>
                    <label for="">
                        貸借区分
                        <input type="text" class="FrmFurikaeEdit txtTaisyakuKbn Enter Tab" style="width: 20px;"
                            maxlength="1">
                        (1:借方　2:貸方)
                    </label>

                </td>
                <td colspan="5">

                </td>
            </tr>
            <tr>
                <td>
                    <label for="">
                        科目コード
                    </label>
                </td>
                <td>
                    <input type="text" class="FrmFurikaeEdit txtKamokuCD Enter Tab" style="width: 60px;" maxlength="5">
                    <button class="FrmFurikaeEdit cmdSearchKmk">
                        検索
                    </button>
                </td>
                <td colspan="5">
                    <input type="text" class="FrmFurikaeEdit lblKamokuNM " style="width:300px;" disabled="disabled" />
                </td>
                <td width="100px" align="right">
                    費目
                    <input type="text" class="FrmFurikaeEdit txtHimokuCD" style="width: 30px;" maxlength="2" />
                </td>
            </tr>
        </table>

    </div>
    <div style="margin-top: 5px">
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">振替元</span></b>
            </legend>
            <table border="0" width="98%">
                <tr>
                    <td>
                        <label for="">
                            科目コード
                        </label>
                    </td>
                    <td>
                        <input type="text" class="FrmFurikaeEdit txtMotKmkCD" style="width: 60px;" maxlength="5">
                        <button class="FrmFurikaeEdit cmdMotKmk_S" style="min-width: 44px">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="FrmFurikaeEdit lblMotKamokuNM " style="width:285px;"
                            disabled="disabled" />
                    </td>

                    <td width="80px" align="right">
                        費目
                        <input type="text" class="FrmFurikaeEdit txtMotHmkCD " style="width: 30px;" maxlength="2" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="">
                            部署コード
                        </label>
                    </td>
                    <td>
                        <input type="text" class="FrmFurikaeEdit txtMotBusyoCD Enter Tab" style="width: 60px;"
                            maxlength="3">
                        <button class="FrmFurikaeEdit cmdMotBs_S" style="min-width: 44px">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="FrmFurikaeEdit lblMotBusyoNM " style="width:285px;"
                            disabled="disabled" />
                    </td>

                    <td width="80px" align="right">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="">
                            金額
                        </label>
                    </td>
                    <td colspan="4">
                        <input type="text" class="FrmFurikaeEdit txtMotKingaku Enter Tab"
                            style="width: 150px;text-align: right" maxlength="18">
                        入力合計
                        <input type="text" class="FrmFurikaeEdit lblInputTotal" style="width: 150px; text-align: right"
                            disabled="disabled" />
                    </td>
                </tr>

            </table>
        </fieldset>
    </div>

    <div style="margin-top: 5px;">
        <table class="FrmFurikaeEdit  sprMeisai" id="FrmFurikaeEdit_sprMeisai">
        </table>

    </div>

    <div class="HMS-button-pane" align="right" style="margin-top: 10px;">

        <button class="FrmFurikaeEdit cmdAction Enter Tab">
            登録（F9）
        </button>

        <button class="FrmFurikaeEdit cmdBack Enter Tab">
            戻る
        </button>
    </div>
</div>
