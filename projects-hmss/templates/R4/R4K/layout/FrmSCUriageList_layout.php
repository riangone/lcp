<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSCUriageList/FrmSCUriageList"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 20240509 lujunxia PHP8 ins s -->
<style type="text/css">
    .FrmSCUriageList #FrmSCUriageList_pager_center table {
        height: 0 !important;
    }
</style>
<!-- 20240509 lujunxia PHP8 ins e -->
<!-- 画面個別の内容を表示 -->
<div class='FrmSCUriageList'>
    <div class='FrmSCUriageList content R4-content' style="width: 1113px">
        <div class="FrmSCUriageList GroupBox1">
            <fieldset>
                <legend>
                    <span style="font-size: 10pt">検索条件</span>
                </legend>
                <table>
                    <tr>
                        <td align="right">
                            <label class="FrmSCUriageList Label6" for="">
                                注文書№
                            </label>
                        </td>
                        <td colspan="2">
                            <input class="FrmSCUriageList txtCMNNO Enter Tab" style="width: 220px" maxlength="10" />
                        </td>
                        <td>
                            <label class="FrmSCUriageList Label11" for="">
                                (先頭一致)
                            </label>
                        </td>
                        <td width="80">
                        </td>
                        <td width="50">
                        </td>
                        <td align="right">
                            <label class="FrmSCUriageList Label4" for="">
                                部署ｺｰﾄﾞ
                            </label>
                        </td>
                        <td>
                            <input class="FrmSCUriageList txtBusyoCD Enter Tab" style="width: 96px" / maxlength="3">
                        </td>
                        <td>
                            <label for="">
                                (=)
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label class="FrmSCUriageList Label1" for="">
                                ＵＣＮＯ
                            </label>
                        </td>
                        <td colspan="2">
                            <input class="FrmSCUriageList txtUCNO Enter Tab" style="width: 220px" maxlength="12" />
                        </td>
                        <td>
                            <label class="FrmSCUriageList Label12" for="">
                                (先頭一致)
                            </label>
                        </td>
                        <td>
                        </td>
                        <td width="50">
                        </td>
                        <td align="right">
                            <label class="FrmSCUriageList Label5" for="">
                                社員番号
                            </label>
                        </td>
                        <td>
                            <input class="FrmSCUriageList txtEmpNO Enter Tab" style="width: 127px" maxlength="5" />
                        </td>
                        <td>
                            <label class="FrmSCUriageList Label10" for="">
                                (=)
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label class="FrmSCUriageList Label3" for="">
                                契約者名ｶﾅ
                            </label>
                        </td>
                        <td colspan="3">
                            <input class="FrmSCUriageList txtKana Enter Tab" style="width: 315px" maxlength="6" />
                        </td>
                        <td>
                            <label class="FrmSCUriageList Label13" for="">
                                (先頭一致)
                            </label>
                        </td>
                        <td width="50">
                        </td>
                        <td align="right">
                            <label class="FrmSCUriageList Label14" for="">
                                CAR_NO
                            </label>
                        </td>
                        <td>
                            <input class="FrmSCUriageList txtCarNO Enter Tab" style="width: 127px" maxlength="10" />
                        </td>
                        <td>
                            <label class="FrmSCUriageList Label15" for="">
                                (部分一致)
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label class="FrmSCUriageList Label2" for="">
                                登録№下4桁
                            </label>
                        </td>
                        <td width="120">
                            <input class="FrmSCUriageList txtTourokuNO Enter Tab" style="width: 112px" maxlength="4" />
                        </td>
                        <td>
                            <label class="FrmSCUriageList Label8" for="">
                                (=)
                            </label>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td width="50">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <button class="FrmSCUriageList cmdSearch Enter Tab">
                                検索
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="height: 20px">
        </div>
        <div>
            <table id="FrmSCUriageList_sprList">
            </table>
            <div id="FrmSCUriageList_pager">
            </div>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="FrmSCUriageList cmdAction Tab Enter">
                    表示
                </button>
            </div>
        </div>
        <div id="FrmSCUriageMeisai">
        </div>
    </div>
</div>
