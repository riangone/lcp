<!DOCTYPE html>
<!--
* 説明：
*
* @author yinhuaiyu
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20240710                 BUG         内容が完全に表示されるようにサイズ変更        YIN
* 20240806         20240806_HMTVE(PHP)グリッド高さ調整.xlsx                         caina
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('HMTVE/HMTVE030InputDataK/HMTVE030InputDataK'));
?>
<style type="text/css">
    .HMTVE030InputDataK.btnETSearch {
        float: none;
    }

    .HMTVE030InputDataK.lbl-sky-m-ji {
        background-color: #87CEFA;
        padding: 0px 3px;
        margin-top: 1px;
        width: 42px;
        height: 21px;
        border-top: 2px solid;
        border-left: 2px solid;
    }

    .HMTVE030InputDataK.lbl-sky-m-shi {
        background-color: #87CEFA;
        padding: 0px 3px;
        margin-top: 1px;
        width: 42px;
        height: 21px;
        border-top: 2px solid;
        border-left: 2px solid;
        border-right: 2px solid
    }

    .HMTVE030InputDataK.lbl-sky-M1 {
        background-color: #87CEFA;
        padding: 0px 3px;
        /* 20240710 YIN UPD S */
        /* height: 118px; */
        /* 20240806 caina upd s */
        /* height: 108px; */
        height: 102px;
        /* 20240806 caina upd e */
        /* 20240710 YIN UPD E */
        width: 120px;
    }

    .HMTVE030InputDataK.center {
        text-align: center;
    }

    .HMTVE030InputDataK.lbl-yellow-M1 {
        background-color: #FFF68F;
        padding: 2px;
        height: 66px;
        width: 62px;
    }

    .HMTVE030InputDataK.lbl-yellow-M2 {
        background-color: #FFF68F;
        padding: 2px;
        height: 90px;
        width: 62px;
    }

    .HMTVE030InputDataK.lbl-yellow-M3 {
        background-color: #FFF68F;
        padding: 2px;
        /*height: 62px;*/
        width: 62px;
    }

    .HMTVE030InputDataK.lbl-yellow-M4 {
        background-color: #FFF68F;
        padding: 2px;
        /* 20240710 YIN UPD S */
        /* height: 42px; */
        /* 20240806 caina upd s */
        /* height: 39px; */
        height: 35px;
        /* 20240806 caina upd e */
        /* 20240710 YIN UPD E */
        width: 62px;
    }

    .HMTVE030InputDataK.lbl-yellow-M5 {
        background-color: #FFF68F;
        padding: 2px;
        /* 20240710 YIN UPD S */
        /* height: 66px; */
        /* 20240806 caina upd s */
        /* height: 60px; */
        height: 64px;
        /* 20240806 caina upd e */
        /* 20240710 YIN UPD E */
        width: 62px;
    }

    .HMTVE030InputDataK.lbl-sky-LFF {
        background-color: white;
        padding: 0px 2px;
        width: 150px;
        height: 22px;
    }

    .HMTVE030InputDataK.pink {
        background-color: #FF73B3;
    }

    .HMTVE030InputDataK.lbl-sky-pink {
        background-color: #FF73B3;
        width: 150px;
        height: 22px;
    }

    .HMTVE030InputDataK.lbl-sky-xMM {
        background-color: #87CEFA;
        padding: 0px 3px;
        width: 36px;
        height: 166px;
    }

    .HMTVE030InputDataK.lbl-sky-xS {
        background-color: #87CEFA;
        padding: 0px 3px;
        margin-top: 3px;
        width: 25px;
        height: 100px;
    }

    input[maxlength='3'] {
        width: 40px;
        text-align: right;
        height: 15px;
    }

    /* 20240710 YIN YIN S */
    .HMTVE030InputDataK .tblCenter input[maxlength='3'] {
        height: 13px;
    }

    .HMTVE030InputDataK .tblCenter .lbl-sky-LFF {
        height: 19px;
    }

    /* 20240710 YIN YIN E */

    .HMTVE030InputDataK.heightlai {
        /*height: 237px;*/
    }

    .HMTVE030InputDataK.height {
        /*height: 66px;*/
    }

    .HMTVE030InputDataK.inputheight {
        /*height: 38px;*/
    }

    .HMTVE030InputDataK.width {
        width: 218px;
    }

    .HMTVE030InputDataK.center {
        text-align: center;
    }

    .HMTVE030InputDataK.lbl-sky-L {
        width: 102px;
    }

    .HMTVE030InputDataK.questionnaire {
        background-color: #87CEFA;
        padding: 0px 2px;
        height: 19px;
        width: 278px;
    }

    .HMTVE030InputDataK.AB {
        height: 46px;
    }

    .HMTVE030InputDataK.TITLE_STD9 {
        /* 20240710 YIN UPD S */
        /* height: 119px; */
        height: 110px;
        /* 20240710 YIN UPD E */
        width: 52px;
    }

    .HMTVE030InputDataK.text {
        display: block;
        width: 127px;
        height: 19px;
        float: right;
        margin-right: -3px;
        /*margin-top: 22px;*/
        padding-top: 7px;
        border-top: 2px solid;
        border-left: 2px solid
    }

    .HMTVE030InputDataK.bottom {
        border-bottom: 2px solid width: 127px;
    }

    .HMTVE030InputDataK.myttbb {
        border-spacing: 30px;
    }

    .HMTVE030InputDataK.ddlExhibitDay {
        width: 109px;
    }

    #HMTVE030InputDataK_tblMain input {
        width: 88% !important;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        input[maxlength='3'] {
            height: 10px;
            width: 30px;
        }

        .HMTVE030InputDataK.lbl-sky-LFF {
            height: 18px;
            width: 107px;
        }

        .HMTVE030InputDataK.lbl-sky-m-ji {
            height: 16px;
            width: 31px;
        }

        .HMTVE030InputDataK.lbl-sky-m-shi {
            height: 16px;
            width: 31px;
        }

        .HMTVE030InputDataK.lbl-yellow-M1 {
            height: 52px;
        }

        .HMTVE030InputDataK.lbl-sky-xMM {
            height: 134px;
            width: 30px;
        }

        .HMTVE030InputDataK.lbl-yellow-M2 {
            height: 72px;
            width: 51px;
        }

        .HMTVE030InputDataK .tblCenter input[maxlength='3'] {
            height: 8px;
        }

        .HMTVE030InputDataK .tblCenter .lbl-sky-LFF {
            height: 14px;
        }

        .HMTVE030InputDataK.questionnaire {
            height: 14px;
            width: 203px;
        }

        .HMTVE030InputDataK.lbl-sky-M1 {
            height: 79px;
            width: 90px;
        }

        .HMTVE030InputDataK.AB {
            height: 32px;
        }

        .HMTVE030InputDataK.lbl-yellow-M4 {
            height: 28px;
            width: 51px;
        }

        .HMTVE030InputDataK.TITLE_STD9 {
            height: 65px !important;
            width: 33px;
        }

        .HMTVE030InputDataK.lbl-yellow-M5 {
            height: 47px;
            width: 51px;
        }

        .HMTVE030InputDataK .width150 {
            width: 99px !important;
        }

        .HMTVE030InputDataK .width156 {
            width: 82px !important;
        }

        .HMTVE030InputDataK.width {
            width: 163px;
        }

        .HMTVE030InputDataK.redfont {
            font-size: 8px !important;
        }

        .HMTVE030InputDataK.text {
            width: 95px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMTVE030InputDataK">
    <div class="HMTVE030InputDataK HMTVE-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class='HMTVE030InputDataK lblExhibitTitle1 lbl-sky-L' for=""> 展示会開催期間 </label>
                <input type="text" class="HMTVE030InputDataK lblExhibitTermFrom" readonly="true" />
                <label for=""> ～ </label>
                <input type="text" class="HMTVE030InputDataK lblExhibitTermTo" readonly="true" />
                <button class="HMTVE030InputDataK btnETSearch button Enter Tab" tabindex="1">
                    展示会検索
                </button>
                <label class='HMTVE030InputDataK lblExhibitTitle2 lbl-sky-L' for=""> 展示会開催日 </label>
                <select class="HMTVE030InputDataK ddlExhibitDay Enter Tab" tabindex="2"></select>
                <button class="HMTVE030InputDataK btnView button Enter Tab" tabindex="3">
                    表　示
                </button>
            </div>
        </fieldset>
        <!-- jqgrid -->
        <div class="HMTVE030InputDataK tblLeft" style=" float:left; display:inline">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class='HMTVE030InputDataK bottom' style="width:48px"></td>
                    <td class='HMTVE030InputDataK bottom' style="width:70px"></td>
                    <td class='HMTVE030InputDataK bottom width150' style="width:150px"></td>
                    <td class='HMTVE030InputDataK Label10 lbl-sky-m-ji center'>計画 </td>
                    <td class='HMTVE030InputDataK Label11 lbl-sky-m-shi center'> 実績 </td>
                </tr>
            </table>
            <table border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td rowspan='7' class='HMTVE030InputDataK Label1 lbl-sky-xMM center'>
                        <br />
                        来場
                        <br />
                        組数
                    </td>
                    <td rowspan='3' class='HMTVE030InputDataK Label1 lbl-yellow-M1 center'>
                        <br />
                        AB
                    </td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;顧客 </td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L1 DropDownList Enter Tab' maxlength="3"
                            tabindex="4" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults1_L DropDownList Enter Tab' maxlength="3"
                            tabindex="5" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;新他ストック</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForeCast_L2 DropDownList Enter Tab' maxlength="3"
                            tabindex="6" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L2 DropDownList Enter Tab' maxlength="3"
                            tabindex="7" />
                    </td>

                </tr>
                <tr class="HMTVE030InputDataK pink">
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF pink'>&nbsp;合計</td>
                    <td class='HMTVE030InputDataK txtForeCastSum_L1' style="text-align: right">0</td>
                    <td class='HMTVE030InputDataK txtResultsSum_L1' style="text-align: right">0</td>

                </tr>
                <tr>
                    <td rowspan='4' class='HMTVE030InputDataK Label1 lbl-yellow-M2 center'>
                        <br />
                        NON
                        <br />
                        AB
                    </td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;顧客</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L3 DropDownList Enter Tab' maxlength="3"
                            tabindex="8" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L3 DropDownList Enter Tab' maxlength="3"
                            tabindex="9" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;新他ストック</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L4 DropDownList Enter Tab' maxlength="3"
                            tabindex="10" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L4 DropDownList Enter Tab' maxlength="3"
                            tabindex="11" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;(内フリー)</td>
                    <td style="background: #D0D0D0"></td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L5 DropDownList Enter Tab' maxlength="3"
                            tabindex="12" />
                    </td>

                </tr>
                <tr class="HMTVE030InputDataK pink">
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF pink'>&nbsp;合計</td>
                    <td class='HMTVE030InputDataK txtForeCastSum_L2' style="text-align: right">0</td>
                    <td class='HMTVE030InputDataK txtResultsSum_L2' style="text-align: right">0</td>

                </tr>
                <tr>
                    <td rowspan='10' class='HMTVE030InputDataK Label1 lbl-sky-xMM heightlai center'>
                        <br />
                        来場
                        <br />
                        分析
                    </td>
                    <td rowspan='2' class='HMTVE030InputDataK Label1 lbl-yellow-M3 center'>呼込み
                        <br />
                        活動
                        <br />
                        来店
                    </td>
                    <td rowspan='2' class='HMTVE030InputDataK Label1 lbl-sky-LFF height'> DM / DH
                        <br />
                        ﾎﾟｽﾃｨﾝｸﾞ / TELｺｰﾙ
                        <br />
                        <span class="HMTVE030InputDataK text"> (内)確約来店実数</span>
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L11 DropDownList Enter Tab inputheight'
                            maxlength="3" tabindex="13" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L11 DropDownList Enter Tab inputheight'
                            maxlength="3" tabindex="14" />
                    </td>
                </tr>
                <tr>
                    <td style="background: #D0D0D0"></td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L12 Enter Tab' maxlength="3" tabindex="15" />
                    </td>

                </tr>
                <tr>
                    <td colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-LFF width'>&nbsp;新聞広告</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L13 DropDownList Enter Tab' maxlength="3"
                            tabindex="16" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L13 DropDownList Enter Tab' maxlength="3"
                            tabindex="17" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-LFF width'>&nbsp;ラジオ・テレビ</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L14 DropDownList Enter Tab' maxlength="3"
                            tabindex="18" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L14 DropDownList Enter Tab' maxlength="3"
                            tabindex="19" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-LFF width'>&nbsp;折込チラシ</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L15 DropDownList Enter Tab' maxlength="3"
                            tabindex="20" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L15 DropDownList Enter Tab' maxlength="3"
                            tabindex="21" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-LFF width'>&nbsp;通りがかり</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L16 DropDownList Enter Tab' maxlength="3"
                            tabindex="22" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L16 DropDownList Enter Tab' maxlength="3"
                            tabindex="23" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-LFF width'>&nbsp;紹介</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L17 DropDownList Enter Tab' maxlength="3"
                            tabindex="24" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L17 DropDownList Enter Tab' maxlength="3"
                            tabindex="25" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-LFF width'>&nbsp;ＷＥＢ</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L18 DropDownList Enter Tab' maxlength="3"
                            tabindex="26" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L18 DropDownList Enter Tab' maxlength="3"
                            tabindex="27" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-LFF width'>&nbsp;その他</td>
                    <td class='HMTVE030InputDataK txtForecast_L19' style="text-align: right;background: #D0D0D0">0</td>
                    <td class='HMTVE030InputDataK txtResults_L19' style="text-align: right;background: #D0D0D0">0</td>
                </tr>
            </table>

        </div>
        <div class="HMTVE030InputDataK tblCenter" style="float:left; display:inline;">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td class='HMTVE030InputDataK bottom' style="width:48px"></td>
                    <td class='HMTVE030InputDataK bottom' style="width:80px"></td>
                    <td class='HMTVE030InputDataK bottom width156' style="width:156px"></td>
                    <td class='HMTVE030InputDataK Label10 lbl-sky-m-ji center'>計画 </td>
                    <td class='HMTVE030InputDataK Label11 lbl-sky-m-shi center'> 実績 </td>
                </tr>
            </table>
            <table border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td rowspan='5' colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-M1 center'>
                        <br />
                        事前
                        <br />
                        準備
                    </td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;DM配信枚数</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L6 Enter Tab' maxlength="3" tabindex="28" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L6 Enter Tab' maxlength="3" tabindex="29" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;DH配布枚数</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L7 Enter Tab' maxlength="3" tabindex="30" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L7 Enter Tab' maxlength="3" tabindex="31" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;ポスティング配布枚数</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L8 Enter Tab' maxlength="3" tabindex="32" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L8 Enter Tab' maxlength="3" tabindex="33" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;TELコール数</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L9 Enter Tab' maxlength="3" tabindex="34" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L9 Enter Tab' maxlength="3" tabindex="35" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;来店確約数</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_L10 Enter Tab' maxlength="3" tabindex="36" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_L10 Enter Tab' maxlength="3" tabindex="37" />
                    </td>

                </tr>
                <tr>
                    <td colspan="3" class='HMTVE030InputDataK Label1 questionnaire'>アンケート回収</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C1 Enter Tab' maxlength="3" tabindex="38" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C1 Enter Tab' maxlength="3" tabindex="39" />
                    </td>

                </tr>
                <tr>
                    <td rowspan='2' colspan="2" class='HMTVE030InputDataK Label1 lbl-sky-M1 center AB'> ABホット
                        <br />
                        発生
                    </td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;顧客</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C2 Enter Tab' maxlength="3" tabindex="40" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C2 Enter Tab' maxlength="3" tabindex="41" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;新他ストック</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C3 Enter Tab' maxlength="3" tabindex="42" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C3 Enter Tab' maxlength="3" tabindex="43" />
                    </td>

                </tr>
                <tr>
                    <td colspan="3" class='HMTVE030InputDataK Label1 questionnaire'>&nbsp;ABホット残</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C4 Enter Tab' maxlength="3" tabindex="44" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C4 Enter Tab' maxlength="3" tabindex="45" />
                    </td>

                </tr>
                <tr>
                    <!-- 20240710 YIN UPD S -->
                    <!-- <td rowspan='4' class='HMTVE030InputDataK Label1 lbl-sky-M1 center TITLE_STD9'
                        style="height: 94px;"></td> -->
                    <!-- 20240806 caina upd s -->
                    <!-- <td rowspan='4' class='HMTVE030InputDataK Label1 lbl-sky-M1 center TITLE_STD9' style="height: 86px;"> -->
                    <td rowspan='4' class='HMTVE030InputDataK Label1 lbl-sky-M1 center TITLE_STD9'
                        style="height: 84px;">
                        <!-- 20240806 caina upd e -->
                        <!-- 20240710 YIN UPD E -->
                        <br />
                        査定
                    </td>
                    <td rowspan='2' class='HMTVE030InputDataK Label1 lbl-yellow-M4 center ' style="background: white">
                        <span style="position:relative;bottom:-2px;">&nbsp;顧客</span>
                    </td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;自銘柄</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C5 Enter Tab' maxlength="3" tabindex="46" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C5 Enter Tab' maxlength="3" tabindex="47" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;他銘柄</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C5T Enter Tab' maxlength="3" tabindex="48" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C5T Enter Tab' maxlength="3" tabindex="49" />
                    </td>

                </tr>

                <tr>
                    <td rowspan='2' class='HMTVE030InputDataK Label1 lbl-yellow-M4 center' style="background: white">
                        &nbsp;新他ストック
                    </td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;自銘柄</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C6 Enter Tab' maxlength="3" tabindex="50" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C6 Enter Tab' maxlength="3" tabindex="51" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;他銘柄</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C6T Enter Tab' maxlength="3" tabindex="52" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C6T Enter Tab' maxlength="3" tabindex="53" />
                    </td>

                </tr>
                <tr>
                    <td colspan="3" class='HMTVE030InputDataK Label1 questionnaire'>&nbsp;デモ件数</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C7 Enter Tab' maxlength="3" tabindex="54" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C7 Enter Tab' maxlength="3" tabindex="55" />
                    </td>

                </tr>
                <tr>
                    <td colspan="3" class='HMTVE030InputDataK Label1 questionnaire'>&nbsp;ランコス提案<span
                            class="HMTVE030InputDataK redfont" style="color: red;font-size: 12px;float:right">(計画)提案数
                            (実績)成約数</span></td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C13 Enter Tab' maxlength="3" tabindex="56" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C13 Enter Tab' maxlength="3" tabindex="57" />
                    </td>

                </tr>
                <tr>
                    <td colspan="3" class='HMTVE030InputDataK Label1 questionnaire'>&nbsp;ＳＫＹプラン提案<span
                            class="HMTVE030InputDataK redfont" style="color: red;font-size: 12px;float:right">(計画)提案数
                            (実績)成約数</span></td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C14 Enter Tab' maxlength="3" tabindex="58" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C14 Enter Tab' maxlength="3" tabindex="59" />
                    </td>

                </tr>
                <tr>
                    <td rowspan='6' class='HMTVE030InputDataK Label1 lbl-sky-xMM center TITLE_STD9'>
                        <br />
                        成約
                        <br />
                        内訳
                    </td>
                    <td rowspan='2' border="1" class='HMTVE030InputDataK Label1 lbl-yellow-M4 center'><span
                            style="position:relative;bottom:-2px;"> AB </span></td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;顧客</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C8 Enter Tab' maxlength="3" tabindex="60" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C8 Enter Tab' maxlength="3" tabindex="61" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;新他</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C9 Enter Tab' maxlength="3" tabindex="62" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C9 Enter Tab' maxlength="3" tabindex="63" />
                    </td>

                </tr>

                <tr>
                    <td rowspan='3' class='HMTVE030InputDataK Label1 lbl-yellow-M5 center'><span
                            style="position:relative;bottom:-2px;">NON
                            <br />
                            AB </span></td>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;顧客</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C10 Enter Tab' maxlength="3" tabindex="64" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C10 Enter Tab' maxlength="3" tabindex="65" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;新他</td>
                    <td>
                        <input class='HMTVE030InputDataK txtForecast_C11 Enter Tab' maxlength="3" tabindex="66" />
                    </td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C11 Enter Tab' maxlength="3" tabindex="67" />
                    </td>

                </tr>
                <tr>
                    <td class='HMTVE030InputDataK Label1 lbl-sky-LFF'>&nbsp;(内フリー)</td>
                    <td style="background: #D0D0D0"></td>
                    <td>
                        <input class='HMTVE030InputDataK txtResults_C12 Enter Tab' maxlength="3" tabindex="68" />
                    </td>

                </tr>
            </table>
        </div>
        <div class="HMTVE030InputDataK tblRight" style="float:left; display:inline">
            <table id="HMTVE030InputDataK_tblMain"></table>
            <div class="HMTVE030InputDataK HMS-button-pane">
                <div class='HMTVE030InputDataK HMS-button-set'>
                    <button class="HMTVE030InputDataK button btnDecide Enter Tab" tabindex="69">
                        確　定
                    </button>
                    <button class="HMTVE030InputDataK button btnDelete Enter Tab" tabindex="70">
                        削　除
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>