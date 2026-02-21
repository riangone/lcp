<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE290IntroduceConfirmList/HMTVE290IntroduceConfirmList"));
?>
<style type="text/css">
    .HMTVE290IntroduceConfirmList.CELL_GLAY_L {
        background-color: #C0C0C0;
    }

    .HMTVE290IntroduceConfirmList.txtPosition {
        width: 72px !important;
    }

    .HMTVE290IntroduceConfirmList.DropDownList {
        width: 80px !important;
    }

    /*折行*/
    .HMTVE290IntroduceConfirmList.tblDetail .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE290IntroduceConfirmList #HMTVE290IntroduceConfirmList_sprList input[type='text'] {
        width: 95% !important;
    }

    .HMTVE290IntroduceConfirmList #HMTVE290IntroduceConfirmList_sprList select {
        width: 100% !important;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE290IntroduceConfirmList">
    <div class="HMTVE290IntroduceConfirmList HMTVE-content HMTVE-content-fixed-width">
        <fieldset class="HMTVE290IntroduceConfirmList">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr>
                    <td><label class='HMTVE290IntroduceConfirmList lblPositionTitle1 lbl-sky-L' for=""> 部署 </label></td>
                    <td>
                        <input type="text" class="HMTVE290IntroduceConfirmList txtPosition Enter Tab" maxlength="3"
                            tabindex="1" />
                    </td>
                    <td colspan="6">
                        <input class='HMTVE290IntroduceConfirmList lblPosition CELL_GLAY_L' disabled="disabled"
                            tabindex="2" />
                    </td>
                    <td colspan="6"></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label class='HMTVE290IntroduceConfirmList lblDate lbl-sky-L' for=""> 提供日 </label></td>
                    <td><select class="HMTVE290IntroduceConfirmList ddlYear DropDownList Enter Tab"
                            tabindex="3"></select></td>
                    <td><label class='HMTVE290IntroduceConfirmList' for=""> 年 </label></td>
                    <td><select class="HMTVE290IntroduceConfirmList ddlMonth DropDownList Enter Tab"
                            tabindex="4"></select></td>
                    <td><label class='HMTVE290IntroduceConfirmList' for=""> 月 </label></td>
                    <td><select class="HMTVE290IntroduceConfirmList ddlDay DropDownList Enter Tab"
                            tabindex="5"></select></td>
                    <td><label class='HMTVE290IntroduceConfirmList' for=""> 日 </label></td>
                    <td><label class='HMTVE290IntroduceConfirmList' for=""> ～ </label></td>
                    <td><select class="HMTVE290IntroduceConfirmList ddlYear2 DropDownList Enter Tab"
                            tabindex="6"></select></td>
                    <td><label class='HMTVE290IntroduceConfirmList' for=""> 年 </label></td>
                    <td><select class="HMTVE290IntroduceConfirmList ddlMonth2 DropDownList Enter Tab"
                            tabindex="7"></select></td>
                    <td><label class='HMTVE290IntroduceConfirmList' for=""> 月 </label></td>
                    <td><select class="HMTVE290IntroduceConfirmList ddlDay2 DropDownList Enter Tab"
                            tabindex="8"></select></td>
                    <td><label class='HMTVE290IntroduceConfirmList' for=""> 日 </label></td>
                    <td></td>
                </tr>
                <tr>
                    <td><label class='HMTVE290IntroduceConfirmList lblObject lbl-sky-L' for=""> 対象 </label></td>
                    <td colspan="9">
                        <input class="HMTVE290IntroduceConfirmList rdoNotConfirm Enter Tab"
                            name="HMTVE290IntroduceConfirmList_radio" type="radio" tabindex="9" />
                        <label class="HMTVE290IntroduceConfirmList" for=""> 未確認 </label>
                        <input class="HMTVE290IntroduceConfirmList rdoConfirm Enter Tab"
                            name="HMTVE290IntroduceConfirmList_radio" type="radio" tabindex="10" />
                        <label class="HMTVE290IntroduceConfirmList" for=""> 確認済み </label>
                        <input class="HMTVE290IntroduceConfirmList rdoTwo Enter Tab"
                            name="HMTVE290IntroduceConfirmList_radio" type="radio" tabindex="11" />
                        <label class="HMTVE290IntroduceConfirmList" for=""> 両方 </label>
                    </td>
                    <td colspan="4"></td>
                    <td class="HMTVE290IntroduceConfirmList HMS-button-pane">
                        <button class="HMTVE290IntroduceConfirmList btnExcel Enter Tab" style="float: right;"
                            tabindex="13">
                            Excel出力
                        </button>
                        <button class="HMTVE290IntroduceConfirmList btnExpression Enter Tab" style="float: right;"
                            tabindex="12">
                            表示
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE290IntroduceConfirmList tblDetail">
            <table id="HMTVE290IntroduceConfirmList_sprList"></table>
        </div>
        <div class="HMTVE290IntroduceConfirmList HMS-button-pane trInfo">
            <button class="HMTVE290IntroduceConfirmList btnConfirm Enter Tab" style="float: right;" tabindex="15">
                確認済みへ
            </button>
            <button class="HMTVE290IntroduceConfirmList btnLogin Enter Tab" style="float: right;" tabindex="14">
                登録
            </button>
        </div>
    </div>
</div>