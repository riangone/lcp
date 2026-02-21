<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmKaikeiEdit/FrmKaikeiEdit"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmKaikeiEdit" class="FrmKaikeiEdit R4-content">
    <div style="margin-top: 20px;border: 3px groove;padding: 5px">

        <input type="text" class="FrmKaikeiEdit lblToday Enter Tab" style="width: 90px;float: right"
            disabled="disabled">
        <table border="0">
            <tr>
                <td>
                    <label for="">
                        経理日
                    </label>
                </td>
                <td>
                </td>
                <td>
                    <input type="text" class="FrmKaikeiEdit cboKeiriBi Enter Tab" style="width: 110px;" maxlength="10">
                </td>
            </tr>

            <tr>
                <td>
                    <label for="">
                        伝票№
                    </label>
                </td>
                <td>
                </td>
                <td>
                    <input type="text" class="FrmKaikeiEdit txtDenpyoNO Enter Tab" style="width: 130px"
                        maxlength="12" />
                </td>
            </tr>
        </table>

    </div>
    <div style="margin-top: 20px;border: 3px groove">

        <table>
            <tr>
                <td width="480px">
                    <table border="0">
                        <tr>
                            <td align="center" colspan="4" style="background:#63B8FF">
                                借方科目
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                部署
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKriBusyoCD Enter Tab" style="width:  50px"
                                    maxlength="3" />
                            </td>
                            <td>
                                <button class="FrmKaikeiEdit cmdSearchBs">
                                    検索
                                </button>
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit lblKriBusyoNM Enter Tab" style="width: 265px;"
                                    disabled="disabled">
                            </td>
                        </tr>

                        <tr>
                            <td align="right">
                                科目
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKriKamokuCD Enter Tab" style="width:  50px"
                                    maxlength="5" />
                            </td>
                            <td>
                                <button class="FrmKaikeiEdit cmdSearchKmk">
                                    検索
                                </button>
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit lblKriKamokuNM Enter Tab" style="width: 265px;"
                                    disabled="disabled">
                            </td>
                        </tr>

                        <tr>
                            <td align="right">
                                補目
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKriHomoku Enter Tab"
                                    style="width:  50px;text-align:right;" maxlength="3" />
                            </td>
                            <td align="right">
                                費目
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKriHimoku Enter Tab" style="width: 30px;"
                                    maxlength="2">
                            </td>
                        </tr>

                        <tr>
                            <td align="right">
                                B/K
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKriBK Enter Tab" style="width:  30px"
                                    maxlength="2" />
                            </td>
                            <td align="right">
                                UN№
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKriUCNO Enter Tab" style="width: 112px;"
                                    maxlength="12">
                                摘要(社員№)
                                <input type="text" class="FrmKaikeiEdit txtKriSyainNO Enter Tab" style="width: 50px;"
                                    maxlength="5">
                            </td>
                        </tr>

                    </table>
                </td>
                <td width="475px">
                    <table>
                        <tr>
                            <td align="center" colspan="4" style="background:#63B8FF">
                                貸方科目
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                部署
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKasBusyoCD Enter Tab" style="width:  50px"
                                    maxlength="3" />
                            </td>
                            <td align="right">
                                <button class="FrmKaikeiEdit cmdSearchBs2">
                                    検索
                                </button>
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit lblKasBusyoNM Enter Tab" style="width: 265px;"
                                    disabled="disabled">
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                科目
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKasKamokuCD Enter Tab" style="width:  50px"
                                    maxlength="5" />
                            </td>
                            <td>
                                <button class="FrmKaikeiEdit cmdSearchKmk2">
                                    検索
                                </button>
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit lblKasKamokuNM Enter Tab" style="width: 265px;"
                                    disabled="disabled">
                            </td>
                        </tr>

                        <tr>
                            <td align="right">
                                補目
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKasHomoku Enter Tab"
                                    style="width:  50px;text-align:right;" maxlength="3" />
                            </td>
                            <td align="right">
                                費目
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKasHimoku Enter Tab" style="width: 30px;"
                                    maxlength="2">
                            </td>
                        </tr>

                        <tr>
                            <td align="right">
                                B/K
                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKasBK Enter Tab" style="width:  30px"
                                    maxlength="2" />
                            </td>
                            <td align="right">
                                UN№

                            </td>
                            <td>
                                <input type="text" class="FrmKaikeiEdit txtKasUCNO Enter Tab" style="width:112px;"
                                    maxlength="12">
                                摘要(社員№)
                                <input type="text" class="FrmKaikeiEdit txtKasSyainNO Enter Tab" style="width: 50px;"
                                    maxlength="5">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
    <div style="margin-top: 20px;border: 3px groove;padding: 5px">

        <table>
            <tr>
                <td>
                    金額
                </td>
                <td>
                    <input type="text" class="FrmKaikeiEdit txtKingaku numeric Enter Tab"
                        style="text-align: right;width: 170px" maxlength="14">
                </td>
            </tr>
            <tr>
                <td>
                    摘要
                </td>
                <td>
                    <input type="text" class="FrmKaikeiEdit txtTekiyo1 Enter Tab" style="width: 300px;" maxlength="100">
                    <input type="text" class="FrmKaikeiEdit txtTekiyo2 Enter Tab" style="width: 190px;" maxlength="20">
                    <input type="text" class="FrmKaikeiEdit txtTekiyo3 Enter Tab" style="width: 190px;" maxlength="20">
                </td>
            </tr>
            <tr>
                <td>
                    証憑№
                </td>
                <td>
                    <input type="text" class="FrmKaikeiEdit txtSyouhyo Enter Tab" style="width: 190px;" maxlength="20">
                </td>
            </tr>

        </table>

    </div>

    <div class="HMS-button-pane" align="right" style="margin-top: 20px;">
        <button class="FrmKaikeiEdit cmdAction Enter Tab">
            登録(F9)
        </button>
        <button class="FrmKaikeiEdit cmdBack Enter Tab">
            戻る
        </button>
    </div>

</div>
