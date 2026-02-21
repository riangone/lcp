<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE400HDTCOMPANYMSTEntry/HMTVE400HDTCOMPANYMSTEntry"));
?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMTVE400HDTCOMPANYMSTEntry fieldset button {
        float: none;
    }

    .HMTVE400HDTCOMPANYMSTEntry.divborder {
        border: solid 1px black;
        height: 110px;
        margin-left: 50px;
    }

    .HMTVE400HDTCOMPANYMSTEntry.divstyle {
        margin: 10px;
    }

    .HMTVE400HDTCOMPANYMSTEntry.txtComName2 {
        margin-top: 10px;
        width: 250px;
    }

    .HMTVE400HDTCOMPANYMSTEntry.HMS-button-pane {
        width：auto;
    }

    /*折行*/
    .HMTVE400HDTCOMPANYMSTEntry.grdGroupListTableRow .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMTVE400HDTCOMPANYMSTEntry .HMTVE-content .HMS-button-pane button {
        vertical-align: baseline !important;
    }
</style>
<div class="HMTVE400HDTCOMPANYMSTEntry">
    <div class="HMTVE400HDTCOMPANYMSTEntry HMTVE-content HMTVE-content-fixed-width">
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE400HDTCOMPANYMSTEntry LBL_TITLE_STD9 lbl-sky-L' for=""> 窓口会社コード </label>
                <input type="text" class="HMTVE400HDTCOMPANYMSTEntry txtComCode Enter Tab" maxlength="5" tabindex="0" />
            </div>
            <div class="HMTVE400HDTCOMPANYMSTEntry HMS-button-pane">
                <label class='HMTVE400HDTCOMPANYMSTEntry LBL_TITLE_STD9 lbl-sky-L' for=""> 窓口会社名 </label>
                <input type="text" class="HMTVE400HDTCOMPANYMSTEntry txtComName Enter Tab" maxlength="100"
                    tabindex="1" />
                <button class="HMTVE400HDTCOMPANYMSTEntry btnSearch button Enter Tab" tabindex="2">
                    絞り込む
                </button>
            </div>
        </fieldset>

        <table>
            <tr>
                <td>
                    <div class='HMTVE400HDTCOMPANYMSTEntry grdGroupListTableRow'>
                        <table id="HMTVE400HDTCOMPANYMSTEntry_grdGroupList"></table>
                    </div>
                </td>

                <td valign="top">
                    <div class="HMTVE400HDTCOMPANYMSTEntry divborder">
                        <div class="HMTVE400HDTCOMPANYMSTEntry divstyle">
                            <div>
                                <label class='HMTVE400HDTCOMPANYMSTEntry LBL_TITLE_STD9 lbl-sky-L' for=""> 窓口会社コード
                                </label>
                                <input type="text" class="HMTVE400HDTCOMPANYMSTEntry txtComCode2 Enter Tab"
                                    maxlength="5" tabindex="3" />
                            </div>
                            <div>
                                <label class='HMTVE400HDTCOMPANYMSTEntry LBL_TITLE_STD9 lbl-sky-L' for=""> 窓口会社名
                                </label>
                                <input type="text" class="HMTVE400HDTCOMPANYMSTEntry txtComName2 Enter Tab"
                                    maxlength="100" tabindex="4" />
                            </div>
                            <div class="HMTVE400HDTCOMPANYMSTEntry HMS-button-pane">
                                <button class="HMTVE400HDTCOMPANYMSTEntry btnClear HMS-button-set button Enter Tab"
                                    tabindex="6">
                                    クリア
                                </button>
                                <button class="HMTVE400HDTCOMPANYMSTEntry btnAdd HMS-button-set button Enter Tab"
                                    tabindex="5">
                                    登録
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>