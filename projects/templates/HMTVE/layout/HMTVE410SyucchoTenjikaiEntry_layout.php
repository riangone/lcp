<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE410SyucchoTenjikaiEntry/HMTVE410SyucchoTenjikaiEntry"));
?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMTVE410SyucchoTenjikaiEntry.CELL_L {
        width: 100px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.CELL_XL {
        width: 370px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.ddlDirector {
        width: 150px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.CELL_S {
        width: 50px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.set-width {
        width: 20%;
    }

    .HMTVE410SyucchoTenjikaiEntry.lblEnqueteSu {
        width: 108px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.set-margin {
        margin-top: 23px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry select {
        width: 65px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.margin-bottom {
        margin-bottom: 5px;
    }

    .HMTVE410SyucchoTenjikaiEntry.CELL_GLAY_L {
        background-color: #C0C0C0 !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1 {
        width: 70px !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.lblPost1 {
        height: auto !important;
    }

    .HMTVE410SyucchoTenjikaiEntry.txtAlighRight {
        text-align: right !important;
    }

    /*折行*/
    .HMTVE410SyucchoTenjikaiEntry.tblDetail .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMTVE410SyucchoTenjikaiEntry.set-margin {
            margin-top: 13px !important;
        }
    }
</style>
<div class="HMTVE410SyucchoTenjikaiEntry">
    <div class="HMTVE410SyucchoTenjikaiEntry HMTVE-content HMTVE-content-fixed-width">
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div class="HMTVE410SyucchoTenjikaiEntry margin-bottom">
                <label for="" class="HMTVE410SyucchoTenjikaiEntry lbl-sky-L">部署</label>
                <input type="text" class="HMTVE410SyucchoTenjikaiEntry txtExhibitTitle1 Enter Tab" maxlength="3"
                    tabindex="1" />
                <input type="text" class="HMTVE410SyucchoTenjikaiEntry lblExhibitTitle1 CELL_GLAY_L"
                    readonly="readonly" />
            </div>
            <div>
                <label for="" class="HMTVE410SyucchoTenjikaiEntry Label2 lbl-sky-L">開催日</label>
                <select class="HMTVE410SyucchoTenjikaiEntry ddlYear Enter Tab" tabindex="2"></select>
                <label for=""> 年 </label>
                <select class="HMTVE410SyucchoTenjikaiEntry ddlMonth Enter Tab" tabindex="3"></select>
                <label for=""> 月 </label>
                <select class="HMTVE410SyucchoTenjikaiEntry ddlDay Enter Tab" tabindex="4"></select>
                <label for=""> 日 </label>
                <label for=""> ～ </label>
                <select class="HMTVE410SyucchoTenjikaiEntry ddlYear2 Enter Tab" tabindex="5"></select>
                <label for=""> 年 </label>
                <select class="HMTVE410SyucchoTenjikaiEntry ddlMonth2 Enter Tab" tabindex="6"></select>
                <label for=""> 月 </label>
                <select class="HMTVE410SyucchoTenjikaiEntry ddlDay2 Enter Tab" tabindex="7"></select>
                <label for=""> 日 </label>
                <button class="HMTVE410SyucchoTenjikaiEntry btnTopClear Button Enter Tab" tabindex="10">
                    クリア
                </button>
                <button class="HMTVE410SyucchoTenjikaiEntry btnExcel Button Enter Tab" tabindex="9">
                    EXCEL出力
                </button>
                <button class="HMTVE410SyucchoTenjikaiEntry btnETSearch Button Enter Tab" tabindex="8">
                    表示
                </button>
            </div>
        </fieldset>
        <div class="HMTVE410SyucchoTenjikaiEntry tblDetail">
            <table id="HMTVE410SyucchoTenjikaiEntry_sprList"></table>
        </div>
        <div class="HMTVE410SyucchoTenjikaiEntry HMS-button-pane set-margin">
            <table border="0" cellspacing="1" cellpadding="0">
                <tr>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblAcceptNo lbl-sky-L">No.</td>
                    <td>
                        <input type="text" class="HMTVE410SyucchoTenjikaiEntry txtAcceptNo CELL_L CELL_GLAY_L"
                            readonly="readonly" maxlength="10" />
                    </td>
                    <td colspan="7"></td>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblRaijo lbl-sky-L">来場者数</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry txtRaijoSu CELL_S txtAlighRight Enter Tab"
                            maxlength="3" tabindex="18" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblAcceptDate lbl-sky-L">開催日</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry txtAcceptDate CELL_L Enter Tab" maxlength="10"
                            tabindex="11" />
                    </td>
                    <td></td>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblStartTime lbl-sky-L">開始時刻</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry txtStartTime CELL_S Enter Tab" maxlength="5"
                            tabindex="12" />
                    </td>
                    <td></td>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblEndTime lbl-sky-L">終了時刻</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry txtEndTime CELL_S Enter Tab" maxlength="5"
                            tabindex="13" />
                    </td>
                    <td class="HMTVE410SyucchoTenjikaiEntry set-width"></td>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblEnqueteSu lbl-sky-L">アンケート回収数</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry CELL_S txtEnqueteSu txtAlighRight Enter Tab"
                            maxlength="3" tabindex="19" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblPlace lbl-sky-L">開催場所</td>
                    <td colspan="5">
                        <input class="HMTVE410SyucchoTenjikaiEntry txtPlace CELL_XL Enter Tab" maxlength="40"
                            tabindex="14" />
                    </td>
                    <td colspan="3"></td>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblABHot lbl-sky-L">ABホット数</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry txtABHotSu CELL_S txtAlighRight Enter Tab"
                            maxlength="3" tabindex="20" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblDemoCars lbl-sky-L">使用デモカー</td>
                    <td colspan="5">
                        <input class="HMTVE410SyucchoTenjikaiEntry txtDemoCars CELL_XL Enter Tab" maxlength="40"
                            tabindex="15" />
                    </td>
                    <td colspan="3"></td>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblMitumori lbl-sky-L">見積数</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry txtMitumoriSu CELL_S txtAlighRight Enter Tab"
                            maxlength="3" tabindex="21" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblPost lbl-sky-L">部署</td>
                    <td colspan="8">
                        <input class="HMTVE410SyucchoTenjikaiEntry txtPost CELL_L Enter Tab" maxlength="3"
                            tabindex="16" />
                        <label for="" class="HMTVE410SyucchoTenjikaiEntry lblPost1"></label>
                    </td>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblSeiyaku lbl-sky-L">成約数</td>
                    <td>
                        <input class="HMTVE410SyucchoTenjikaiEntry txtSeiyakuSu CELL_S txtAlighRight Enter Tab"
                            maxlength="3" tabindex="22" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE410SyucchoTenjikaiEntry lblDirector lbl-sky-L">担当者</td>
                    <td colspan="2"><select class="HMTVE410SyucchoTenjikaiEntry ddlDirector Enter Tab"
                            tabindex="17"></select></td>
                    <td colspan="8"></td>
                </tr>
            </table>
            <button class="HMTVE410SyucchoTenjikaiEntry btnCancel HMS-button-set Button Enter Tab" tabindex="25">
                キャンセル
            </button>
            <button class="HMTVE410SyucchoTenjikaiEntry btnDelete HMS-button-set Button Enter Tab" tabindex="24">
                削除
            </button>
            <button class="HMTVE410SyucchoTenjikaiEntry btnLand HMS-button-set Button Enter Tab" tabindex="23">
                登録
            </button>
        </div>
    </div>
</div>