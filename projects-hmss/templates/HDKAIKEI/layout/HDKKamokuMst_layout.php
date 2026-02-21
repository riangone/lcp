<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKKamokuMst/HDKKamokuMst"));
?>

<style type="text/css">
    .HDKKamokuMst.txtRelationName {
        width: 159px;
    }

    .HDKKamokuMst.KensakuDiv {
        padding: 30px 0px 0px 0px;
    }

    .HDKKamokuMst.Kensaku {
        min-width: 100px;
    }

    .HDKKamokuMst.tableSearch {
        display: inline;
    }

    .HDKKamokuMst.grdGroupList tr.jqgrow td,
    .HDKKamokuMst.pnlKamokuList tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HDKKamokuMst fieldset {
        padding: 0px 5px 0px;
    }

    .HDKKamokuMst.lbl-sky-xL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 2px;
        width: 120px;
    }

    .HDKKamokuMst .ui-jqgrid.ui-widget.ui-widget-content.ui-corner-all {
        padding: 2px 0px 2px;
    }

    .HDKKamokuMst input[type='text'][readonly='readonly'] {
        background-color: #BABEC1;
    }

    .HDKKamokuMst.tableSearch button {
        min-width: 60px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='HDKKamokuMst body'>
    <div class='HDKKamokuMst HDKAIKEI-content'>
        <fieldset>
            <legend class="HDKKamokuMst lblSearchTitle">
                <b><span>検索条件</span></b>
            </legend>
            <table class='HDKKamokuMst tableSearch'>
                <tr class="HDKKamokuMst">
                    <td><label class='HDKKamokuMst Label1 lbl-sky-L' for=""> 関係名 </label></td>
                    <td colspan="2">
                        <input class="HDKKamokuMst txtRelationName Enter Tab" maxlength="40" tabindex="1" />
                    </td>
                </tr>
                <tr class="HDKKamokuMst">
                    <td><label class='HDKKamokuMst Label2 lbl-sky-L' for=""> 科目コード </label></td>
                    <td>
                        <input type="text" class='HDKKamokuMst txtKamokuCD  Tab Enter' maxlength="10" tabindex="2" />
                    </td>
                    <td>
                        <button class='HDKKamokuMst btnKamoku btn Enter Tab ' tabindex="3">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="HDKKamokuMst lblkamokuNM Enter Tab" disabled="true" />
                    </td>
                </tr>

            </table>
            <div class='HDKKamokuMst KensakuDiv HMS-button-set'>
                <button class='HDKKamokuMst Kensaku btn Enter Tab' tabindex="4">
                    検索
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <table>
            <tr>
                <td valign="top">
                    <div class='HDKKamokuMst grdGroupListTableRow'>
                        <table class="HDKKamokuMst grdGroupList Enter Tab" id="HDKKamokuMst_grdGroupList"></table>
                        <div class="HDKKamokuMst HMS-button-pane">
                            <button class='HDKKamokuMst btnAdd btn Enter Tab HMS-button-set'>
                                新規
                            </button>
                        </div>
                    </div>
                </td>

                <td style="padding-left:50px">
                    <div class='HDKKamokuMst pnlKamokuListTableRow'>
                        <div>
                            <label class='HDKKamokuMst Label1 lbl-sky-L' for=""> 関係名 </label>
                            <input class="HDKKamokuMst txtRelationNameS Enter Tab" maxlength="20" tabindex="5" />

                        </div>
                        <div>
                            <table class="HDKKamokuMst pnlKamokuList Enter Tab" id="HDKKamokuMst_kamokuList"></table>
                        </div>
                        <div class="HDKKamokuMst HMS-button-pane">
                            <button class='HDKKamokuMst btnSelectAll btn Enter Tab'>
                                全て選択
                            </button>
                            <button class='HDKKamokuMst btnUnSelectAll btn Enter Tab'>
                                選択解除
                            </button>
                            <button class='HDKKamokuMst btnSave btn Enter Tab HMS-button-set'>
                                保存
                            </button>
                            <button class='HDKKamokuMst btnDelete btn Enter Tab HMS-button-set'>
                                削除
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>