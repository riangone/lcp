<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE280IntroduceConfirmEntry/HMTVE280IntroduceConfirmEntry"));
?>
<style type="text/css">
    .HMTVE280IntroduceConfirmEntry.CELL_GLAY_L {
        background-color: #C0C0C0;
    }

    .HMTVE280IntroduceConfirmEntry.DropDownList {
        width: 80px !important;
    }

    /*折行*/
    .HMTVE280IntroduceConfirmEntry.tblDetail .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE280IntroduceConfirmEntry.txtExhibitTitle1 {
        width: 70px !important;
    }

    .HMTVE280IntroduceConfirmEntry.lblExhibitTitle1 {
        width: 240px !important;
    }

    .HMTVE280IntroduceConfirmEntry.txtJyuriNo {
        width: 100px !important;
    }

    .HMTVE280IntroduceConfirmEntry.btnETSearch,
    .HMTVE280IntroduceConfirmEntry.btnDelete,
    .HMTVE280IntroduceConfirmEntry.btnLand,
    .HMTVE280IntroduceConfirmEntry.btnClear {
        float: right;
    }

    .HMTVE280IntroduceConfirmEntry.txtAcceptNo {
        width: 180px !important;
    }

    .HMTVE280IntroduceConfirmEntry.lblClient,
    .HMTVE280IntroduceConfirmEntry.lblIntroPeople,
    .HMTVE280IntroduceConfirmEntry.lblBargain {
        width: 120px !important;
    }

    .HMTVE280IntroduceConfirmEntry.txtClient,
    .HMTVE280IntroduceConfirmEntry.txtIntroPeople {
        width: 220px !important;
    }

    .HMTVE280IntroduceConfirmEntry.txtPost {
        width: 60px !important;
    }

    .HMTVE280IntroduceConfirmEntry.lblPost1 {
        width: 250px !important;
    }

    .HMTVE280IntroduceConfirmEntry.ddlDirector {
        width: 150px !important;
    }

    .HMTVE280IntroduceConfirmEntry.btnCopy,
    .HMTVE280IntroduceConfirmEntry.btnSearch {
        width: 60px;
    }

    .HMTVE280IntroduceConfirmEntry.lbl-gray-L {
        background-color: #ABBFD5;
    }

    .HMTVE280IntroduceConfirmEntry.fieldset {
        margin-bottom: 15px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE280IntroduceConfirmEntry body">
    <div class="HMTVE280IntroduceConfirmEntry HMTVE-content HMTVE-content-fixed-width">
        <fieldset class="HMTVE280IntroduceConfirmEntry fieldset">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry lbl-sky-L'> 部署 </label></td>
                    <td>
                        <input type="text" class="HMTVE280IntroduceConfirmEntry txtExhibitTitle1 Enter Tab"
                            maxlength="3" tabindex="1" />
                    </td>
                    <td colspan="8"><input class="HMTVE280IntroduceConfirmEntry lblExhibitTitle1 CELL_GLAY_L"
                            disabled="disabled" /></td>
                    <td colspan="2"><label for="" class='HMTVE280IntroduceConfirmEntry lbl-sky-L'> 受理No. </label></td>
                    <td colspan="2">
                        <input type="text" class="HMTVE280IntroduceConfirmEntry txtJyuriNo Enter Tab" maxlength="10"
                            tabindex="21" />
                    </td>
                    <td colspan="2"><label for="" class='HMTVE280IntroduceConfirmEntry lbl-sky-L'> 確認/未確認 </label></td>
                    <td>
                        <input class="HMTVE280IntroduceConfirmEntry rdoKaku Enter Tab" name="Kaku1" type="radio"
                            tabindex="22" />
                        <label for="" class="HMTVE280IntroduceConfirmEntry"> 確認 </label>
                        <input class="HMTVE280IntroduceConfirmEntry rdoMikaku Enter Tab" name="Kaku1" type="radio"
                            tabindex="23" />
                        <label for="" class="HMTVE280IntroduceConfirmEntry"> 未確認 </label>
                    </td>
                </tr>
                <tr>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry lblDate lbl-sky-L'> 提供日 </label></td>
                    <td><select class="HMTVE280IntroduceConfirmEntry ddlYear DropDownList Enter Tab"
                            tabindex="2"></select></td>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry Label3'> 年 </label></td>
                    <td><select class="HMTVE280IntroduceConfirmEntry ddlMonth DropDownList Enter Tab"
                            tabindex="3"></select></td>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry Label4'> 月 </label></td>
                    <td><select class="HMTVE280IntroduceConfirmEntry ddlDay DropDownList Enter Tab"
                            tabindex="4"></select></td>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry Label5'> 日 </label></td>
                    <td></td>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry'> ～ </label></td>
                    <td></td>
                    <td><select class="HMTVE280IntroduceConfirmEntry ddlYear2 DropDownList Enter Tab"
                            tabindex="5"></select></td>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry Label6'> 年 </label></td>
                    <td><select class="HMTVE280IntroduceConfirmEntry ddlMonth2 DropDownList Enter Tab"
                            tabindex="6"></select></td>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry Label7'> 月 </label></td>
                    <td><select class="HMTVE280IntroduceConfirmEntry ddlDay2 DropDownList Enter Tab"
                            tabindex="7"></select></td>
                    <td><label for="" class='HMTVE280IntroduceConfirmEntry Label8'> 日 </label></td>
                    <td class="HMTVE280IntroduceConfirmEntry HMS-button-pane">
                        <button class="HMTVE280IntroduceConfirmEntry btnETSearch Enter Tab" tabindex="8">
                            表示
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE280IntroduceConfirmEntry tblDetail">
            <table id="HMTVE280IntroduceConfirmEntry_sprList"></table>
        </div>
        <table>
            <tr>
                <td><label for="" class='HMTVE280IntroduceConfirmEntry lblAcceptNo lbl-sky-L lbl-gray-L'> 受理No. </label>
                </td>
                <td colspan="2">
                    <input type="text" class="HMTVE280IntroduceConfirmEntry txtAcceptNo Enter Tab" maxlength="10"
                        tabindex="10" />
                </td>
                <td><label for="" class='HMTVE280IntroduceConfirmEntry lblClient lbl-sky-L lbl-gray-L'> お客様 </label>
                </td>
                <td colspan="2">
                    <input type="text" class="HMTVE280IntroduceConfirmEntry txtClient Enter Tab" tabindex="14"
                        maxlength="40" />
                    <button class="HMTVE280IntroduceConfirmEntry btnCopy Enter Tab" tabindex="15">
                        コピー
                    </button>
                </td>
            </tr>
            <tr>
                <td><label for="" class='HMTVE280IntroduceConfirmEntry lblAcceptDate lbl-sky-L lbl-gray-L'> 提供日 </label>
                </td>
                <td colspan="2">
                    <input type="text" class="HMTVE280IntroduceConfirmEntry txtAcceptDate Enter Tab" tabindex="11"
                        maxlength="10" />
                </td>
                <td><label for="" class='HMTVE280IntroduceConfirmEntry lblIntroPeople lbl-sky-L lbl-gray-L'> 紹介者・窓口会社
                    </label>
                </td>
                <td colspan="2">
                    <input type="text" class="HMTVE280IntroduceConfirmEntry txtIntroPeople Enter Tab" tabindex="16"
                        maxlength="40" />
                    <button class="HMTVE280IntroduceConfirmEntry btnSearch Enter Tab" tabindex="24">
                        検索
                    </button>
                </td>
            </tr>
            <tr>
                <td><label for="" class='HMTVE280IntroduceConfirmEntry lblPost lbl-sky-L lbl-gray-L'> 部署 </label></td>
                <td>
                    <input type="text" class="HMTVE280IntroduceConfirmEntry txtPost Enter Tab" tabindex="12"
                        maxlength="3" />
                </td>
                <td><input class="HMTVE280IntroduceConfirmEntry lblPost1 CELL_GLAY_L" disabled="disabled" /></td>
                <td><label for="" class='HMTVE280IntroduceConfirmEntry lblBargain lbl-sky-L lbl-gray-L'> 商談フラグ </label>
                </td>
                <td>
                    <input type="checkbox" class="HMTVE280IntroduceConfirmEntry chkBargain Enter Tab" tabindex="17" />
                    <label for="" class='HMTVE280IntroduceConfirmEntry'> 未商談 </label>
                </td>
            </tr>
            <tr>
                <td><label for="" class='HMTVE280IntroduceConfirmEntry lblDirector lbl-sky-L lbl-gray-L'> 担当者 </label>
                </td>
                <td colspan="3"><select class="HMTVE280IntroduceConfirmEntry ddlDirector Enter Tab"
                        tabindex="13"></select></td>
                <td colspan="2" class="HMTVE280IntroduceConfirmEntry HMS-button-pane">
                    <button class="HMTVE280IntroduceConfirmEntry btnDelete Enter Tab" tabindex="20">
                        削除
                    </button>
                    <button class="HMTVE280IntroduceConfirmEntry btnLand Enter Tab" tabindex="19">
                        登録
                    </button>
                    <button class="HMTVE280IntroduceConfirmEntry btnClear Enter Tab" tabindex="18">
                        クリア
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>