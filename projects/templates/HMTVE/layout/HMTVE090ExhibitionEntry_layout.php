<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('jquery/fullcalendar-6.1.15/index.global.min.js'));
echo $this->Html->script(array('jquery/fullcalendar-6.1.15/locales/ja.global.min.js'));
echo $this->Html->script(array("HMTVE/HMTVE090ExhibitionEntry/HMTVE090ExhibitionEntry"));
?>
<style type="text/css">
    /*現在の基準日*/
    .HMTVE090ExhibitionEntry.lblExibitTerm.lbl-grey-L {
        width: 200px;
    }

    /*sky-label*/
    .HMTVE090ExhibitionEntry.lbl-sky-L {
        width: 105px;
    }

    .HMTVE090ExhibitionEntry.calendar-container {
        position: relative;
        z-index: 1;
    }

    .HMTVE090ExhibitionEntry.calendar {
        width: 640px;
    }

    .HMTVE090ExhibitionEntry .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 0px;
        justify-content: space-between;
    }

    /* 画面にcalendarのボタンhover時にbackgroundがある問題のために */
    .HMTVE090ExhibitionEntry.calendar button:hover {
        background: #1e2b37;
    }

    .HMTVE090ExhibitionEntry.calendar button:disabled:hover {
        background-color: var(--fc-button-bg-color) !important;
    }

    .HMTVE090ExhibitionEntry.fc .fc-scrollgrid-liquid {
        height: 95%;

    }

    .HMTVE090ExhibitionEntry .fc-scrollgrid-sync-table {
        height: 424px !important;
    }

    /*展示会データ詳細*/
    .HMTVE090ExhibitionEntry.LBL_TITLE_STD11 {
        background: #16b1e9 url(css/jquery/images/ui-bg_gloss-wave_75_16b1e9_500x100.png) 50% 50% repeat-x;
        width: 375px;
        height: 24px;
        line-height: 24px;
        font-size: 1.1em;
        color: #FFFFFF;
        padding: 0px 5px;
    }

    /*イベント名*/
    .HMTVE090ExhibitionEntry.lbl-event {
        height: 88px;
        line-height: 88px;
        float: left;
    }

    /*イベント名 textarea*/
    .HMTVE090ExhibitionEntry.LblEventMei {
        margin-left: 4px;
        height: 85px;
        width: 262px;
        display: inline-block;
    }

    .HMTVE090ExhibitionEntry textarea[readonly='readonly'] {
        background-color: #DFDFDF;
    }

    .HMTVE090ExhibitionEntry td.fc-daygrid-day.fc-day {
        cursor: pointer;
    }

    .HMTVE090ExhibitionEntry td.fc-daygrid-day.fc-day.fc-day-other

    /*, .HMTVE090ExhibitionEntry td.fc-daygrid-day.fc-day.fc-day-other .fc-daygrid-event*/
        {
        cursor: auto;
    }

    /*.HMTVE090ExhibitionEntry td.fc-daygrid-day.fc-day.fc-day-other .fc-daygrid-event.fc-daygrid-block-event
    {
        background-color: #96b9dc;
        border: #96b9dc;
    }*/
    .HMTVE090ExhibitionEntry .fc-event-title.fc-sticky {
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .HMTVE090ExhibitionEntry.row {
        margin-top: 5px;
    }

    /*展示会開催期間*/
    .HMTVE090ExhibitionEntry input[readonly='readonly'] {
        width: 102px !important;
    }

    .HMTVE090ExhibitionEntry.Datepicker {
        width: 100px !important;
    }

    .HMTVE090ExhibitionEntry .HMS-button-pane button {
        margin-left: 0px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMTVE090ExhibitionEntry .fc-scrollgrid-sync-table {
            height: 324px !important;
        }

        .HMTVE090ExhibitionEntry.calendar {
            width: 490px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE090ExhibitionEntry">
    <div class="HMTVE090ExhibitionEntry HMTVE-content">
        <div>
            <label for="" class='HMTVE090ExhibitionEntry lblNowDay lbl-sky-L'> 現在の基準日： </label>
            <input class="HMTVE090ExhibitionEntry lblExibitTerm lbl-grey-L" readonly="true" />
        </div>
        <table>
            <tr>
                <td rowspan="3">
                    <div id="calendar-container" class="HMTVE090ExhibitionEntry calendar-container">
                        <div id="calendar" class='HMTVE090ExhibitionEntry calendar'></div>
                    </div>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td width="20"></td>
                <td>
                    <div class="HMTVE090ExhibitionEntry EditDiv dtv">
                        <div class="HMTVE090ExhibitionEntry row LBL_TITLE_STD11">
                            <b>展示会データ詳細</b>
                        </div>
                        <div class="HMTVE090ExhibitionEntry row">
                            <label for="" class='HMTVE090ExhibitionEntry lbl-sky-L'>展示会開催期間</label>
                            <input type="text" class='HMTVE090ExhibitionEntry TxtExhibitStartDate Datepicker Enter Tab'
                                maxlength="10" tabindex="1" />
                            ～
                            <input type="text" class='HMTVE090ExhibitionEntry TxtExhibitEndDate Datepicker Enter Tab'
                                maxlength="10" tabindex="2" />
                        </div>
                        <div class="HMTVE090ExhibitionEntry row">
                            <label for="" class='HMTVE090ExhibitionEntry lbl-event lbl-sky-L'>イベント名</label>
                            <textarea class='HMTVE090ExhibitionEntry LblEventMei Enter Tab' rows="4"
                                tabindex="3"></textarea>
                        </div>
                        <div class="HMTVE090ExhibitionEntry row">
                            <label for="" class='HMTVE090ExhibitionEntry lbl-sky-L'>基準日フラグ</label>
                            <input type="checkbox" class='HMTVE090ExhibitionEntry chkBaseflag Enter Tab' tabindex="4" />
                            <label for="" class='HMTVE090ExhibitionEntry lbl-chkBaseflag'>基準日に指定</label>
                        </div>
                        <div class="HMTVE090ExhibitionEntry row HMS-button-pane">
                            <button class="HMTVE090ExhibitionEntry Button btn btnEdit Enter Tab" tabindex="5">
                                更新
                            </button>
                            <button class="HMTVE090ExhibitionEntry Button btn btnCancel Enter Tab" tabindex="6">
                                キャンセル
                            </button>
                            <button class="HMTVE090ExhibitionEntry Button btn btnInsert Enter Tab" tabindex="7">
                                追加
                            </button>
                            <button class="HMTVE090ExhibitionEntry Button btn btnUpdate Enter Tab" tabindex="8">
                                編集
                            </button>
                            <button class="HMTVE090ExhibitionEntry Button btn btnDelete Enter Tab" tabindex="9">
                                削除
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>