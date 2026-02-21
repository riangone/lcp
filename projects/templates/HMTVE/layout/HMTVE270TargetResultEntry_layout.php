<!--
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug					　　　　内容															   担当
 * YYYYMMDD						#ID							　　　　XXXXXX															  GSDL
 * 20240326        受入検証.xlsx NO4     見出しの高さを全体的に小さくして、データ行ができるだけ多く表示されるようにしてほしい             	  LHB
 * 20240611    		202406_データ集計システム_CX-80追加        		CX-80追加            		 		 								LHB
 * 20240710                      BUG                     内容が完全に表示されるようにサイズ変更                                            YIN
 * 20240712    		CX-80追加判断                           CX-80追加判断            		 	                                        LHB
 * 20251118         202511_データ集計システム_機能追加要望   目標と実績＿改修イメージ                caina
 * -------------------------------------------------------------------------------------------------------------------------------------
 */ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE270TargetResultEntry/HMTVE270TargetResultEntry")); ?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMTVE270TargetResultEntry {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin: 0 !important;
    }

    .HMTVE270TargetResultEntry.HMTVE-content>div {
        margin: 2px !important;
    }

    .HMTVE270TargetResultEntry.SUBTITLE {
        background-color: #DDF4D8;
        padding-left: 10px;
    }

    .HMTVE270TargetResultEntry.CELL_TITLE_GREEN {
        background-color: #006600;
        color: #FFFFFF;
    }

    .HMTVE270TargetResultEntry.CELL_PADDING_LEFT {
        padding-left: 10px;
    }

    .HMTVE270TargetResultEntry.CELL_PADDING_RIGHT {
        padding-right: 10px;
    }

    .HMTVE270TargetResultEntry.TEXT_ALIAGN_CENTER {
        text-align: center;
        width: 60px !important;
    }

    .HMTVE270TargetResultEntry table {
        float: left;
    }

    .HMTVE270TargetResultEntry table input {
        text-align: right;
        width: 65px;
    }

    .HMTVE270TargetResultEntry.CELL_TITLE_GREEN_C {
        background-color: #006600;
        color: #FFFFFF;
        text-align: center;
        width: 50px !important;
    }

    .HMTVE270TargetResultEntry input[readonly='readonly'],
    .HMTVE270TargetResultEntry input[disabled='disabled'] {
        background-color: #C0C0C0 !important;
    }

    .HMTVE270TargetResultEntry.lblShopName {
        width: 300px;
    }

    .HMTVE270TargetResultEntry.btnLogin {
        margin-left: 70px !important;
    }

    .HMTVE270TargetResultEntry.set-height {
        height: 21.6px;
    }

    .HMTVE270TargetResultEntry.margin-right {
        margin-right: 30px !important;
    }

    /* 20240710 YIN INS S */
    .HMTVE270TargetResultEntry .leftTable input {
        height: 14.2px;
    }

    /* 20240710 YIN INS E */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE270TargetResultEntry.set-height {
            height: 14.6px;
        }

        .HMTVE270TargetResultEntry .leftTable input {
            height: 9px;
        }
    }
</style>

<div class="HMTVE270TargetResultEntry">
    <div class="HMTVE270TargetResultEntry  HMTVE-content">
        <div>
            <label class='HMTVE270TargetResultEntry lbl-sky-L' for=""> 対象年月 </label>
            <input type="text" class="HMTVE270TargetResultEntry txtbDuring" disabled="disabled" />
            <label for=""> 年 </label>
            <select class="HMTVE270TargetResultEntry ddlMonth" disabled="disabled"></select>
            <label for=""> 月分 </label>
        </div>
        <div>
            <label class='HMTVE270TargetResultEntry lbl-sky-L' for=""> 店舗名 </label>
            <input type="text" class="HMTVE270TargetResultEntry lblShopName" disabled="disabled" />
            <button class="HMTVE270TargetResultEntry btnLogin lbl-blue-M Enter Tab" tabindex="81">
                登録
            </button>
            <button class="HMTVE270TargetResultEntry btnDel lbl-blue-M Enter Tab" tabindex="82">
                削除
            </button>
            <button class="HMTVE270TargetResultEntry btnClose lbl-blue-M Enter Tab" tabindex="83">
                閉じる
            </button>
        </div>
        <div>
            <!-- 20240710 YIN INS S -->
            <!-- <table border="0" class="HMTVE270TargetResultEntry margin-right"></table> -->
            <table border="0" class="HMTVE270TargetResultEntry leftTable margin-right">
                <!-- 20240710 YIN INS E -->
                <!--総限界利益(単位：千円) start-->
                <tr>
                    <td colspan="5" class="HMTVE270TargetResultEntry SUBTITLE"> 総限界利益(単位：千円) </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry set-height">
                    <td colspan="2"></td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">中間会議</td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">増減</td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">最終予想</td>
                </tr>
                <tr>
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 目標 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtGoal Enter Tab" lblname="総限界利益_中間会議" maxlength="6"
                            tabindex="1" />
                    </td>
                    <td colspan="2"></td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 月末予想 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtYosou Enter Tab lostFocusInput"
                            lblname="総限界利益_月末予想_中間会議" maxlength="6" tabindex="2" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSabun Enter Tab lostFocusInput"
                            lblname="総限界利益_月末予想_増減" maxlength="6" tabindex="3" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtJisski" maxlength="6" readonly="readonly" />
                    </td>
                </tr>
                <!--総限界利益(単位：千円) end-->
                <!--利益売上台数予想 start-->
                <tr>
                    <td colspan="5" class="HMTVE270TargetResultEntry SUBTITLE"> 利益売上台数予想 </td>
                </tr>
                <tr>
                    <td colspan="5" class="HMTVE270TargetResultEntry CELL_PADDING_LEFT"> (自契自登＋自契他登) </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry set-height">
                    <td colspan="2"></td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        中間会議 </td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        増減 </td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        最終予想 </td>
                </tr>
                <tr>
                    <td rowspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN_C"> 当月
                        <br />
                        目標
                    </td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> メイン権 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtMain Enter Tab" lblname="利益売上台数予想_当月目標_ﾒｲﾝ権"
                            maxlength="5" tabindex="4" />
                    </td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 他チャネル </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtTaChanel Enter Tab" lblname="利益売上台数予想_当月目標_他ﾁｬﾈﾙ"
                            maxlength="5" tabindex="5" />
                    </td>
                    <td colspan="2"></td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td rowspan="4" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN_C"> 月末
                        <br />
                        予想
                    </td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> メイン権 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtMainY Enter Tab lostFocusInput selfSellNum1"
                            lblname="利益売上台数予想_月末予想_ﾒｲﾝ権_中間会議" maxlength="5" tabindex="6" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtMainS Enter Tab lostFocusInput selfSellNum2"
                            lblname="利益売上台数予想_月末予想_ﾒｲﾝ権_増減" maxlength="5" tabindex="7" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtMainSY selfSellNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 軽自動車 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKeiY Enter Tab lostFocusInput selfSellNum1"
                            lblname="利益売上台数予想_月末予想_軽自動車_中間会議" maxlength="5" tabindex="8" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKeiS Enter Tab lostFocusInput selfSellNum2"
                            lblname="利益売上台数予想_月末予想_軽自動車_増減" maxlength="5" tabindex="9" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKeiSY selfSellNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ボルボ </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtVolvoY Enter Tab lostFocusInput selfSellNum1"
                            lblname="利益売上台数予想_月末予想_ボルボ_中間会議" maxlength="5" tabindex="10" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtVolvoS Enter Tab lostFocusInput selfSellNum2"
                            lblname="利益売上台数予想_月末予想_ボルボ_増減" maxlength="5" tabindex="11" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtVolvoSY selfSellNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> その他 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSonotaY Enter Tab lostFocusInput selfSellNum1"
                            lblname="利益売上台数予想_月末予想_その他_中間会議" maxlength="5" tabindex="12" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSonotaS Enter Tab lostFocusInput selfSellNum2"
                            lblname="利益売上台数予想_月末予想_その他_増減" maxlength="5" tabindex="13" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSonotaSY selfSellNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 売上台数計 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtYGk sellNum1" lblname="利益売上台数予想_売上台数計_中間会議"
                            maxlength="6" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSGk sellNum2" lblname="利益売上台数予想_売上台数計_増減"
                            maxlength="6" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSYGk sellNum3" maxlength="6" readonly="readonly" />
                    </td>
                </tr>
                <!--利益売上台数予想 end-->
                <!--登録台数予想 start-->
                <tr>
                    <td colspan="5" class="HMTVE270TargetResultEntry SUBTITLE"> 登録台数予想 </td>
                </tr>
                <tr>
                    <td colspan="5" class="HMTVE270TargetResultEntry CELL_PADDING_LEFT"> (メイン権＋福祉＋他契自登－自契他登) </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry set-height">
                    <td colspan="2"></td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        中間会議 </td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        増減 </td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        最終予想 </td>
                </tr>
                <tr>
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> メイン権 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtJijiY selfLoginNum1" lblname="登録台数予想_自自_中間会議"
                            maxlength="5" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtJijiS selfLoginNum2" lblname="登録台数予想_自自_増減"
                            maxlength="5" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtJijiSY selfLoginNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 福祉 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtFukushiY Enter Tab lostFocusInput selfLoginNum1"
                            lblname="登録台数予想_福祉_中間会議" maxlength="5" tabindex="14" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtFukushiS Enter Tab lostFocusInput selfLoginNum2"
                            lblname="登録台数予想_福祉_増減" maxlength="5" tabindex="15" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtFukushiSY selfLoginNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 他自 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtTajiY Enter Tab lostFocusInput selfLoginNum1"
                            lblname="登録台数予想_他自_中間会議" maxlength="5" tabindex="16" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtTajiS Enter Tab lostFocusInput selfLoginNum2"
                            lblname="登録台数予想_他自_増減" maxlength="5" tabindex="17" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtTajiSY selfLoginNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 自他 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtJitaY Enter Tab lostFocusInput selfLoginNum_1"
                            lblname="登録台数予想_自他_中間会議" maxlength="5" tabindex="18" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtJitaS Enter Tab lostFocusInput selfLoginNum_2"
                            lblname="登録台数予想_自他_増減" maxlength="5" tabindex="19" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtJitaSY selfLoginNum_3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 登録台数計 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtTYGk loginNum1" lblname="登録台数予想_登録台数計_中間会議"
                            maxlength="6" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtTSGk loginNum2" lblname="登録台数予想_登録台数計_増減"
                            maxlength="6" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtTSYGk loginNum3" maxlength="6" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 軽自動車 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKJijiY selfLoginNum4" lblname="登録台数予想_軽自自_中間会議"
                            maxlength="5" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKJijiS selfLoginNum5" lblname="登録台数予想_軽自自_増減"
                            maxlength="5" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKJijiSY selfLoginNum6" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 軽：他自 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKTajiY Enter Tab lostFocusInput selfLoginNum4"
                            lblname="登録台数予想_軽他自_中間会議" maxlength="5" tabindex="20" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKTajiS Enter Tab lostFocusInput selfLoginNum5"
                            lblname="登録台数予想_軽他自_増減" maxlength="5" tabindex="21" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKTajiSY selfLoginNum6" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 軽：自他 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKJitaY Enter Tab lostFocusInput selfLoginNum_4"
                            lblname="登録台数予想_軽自他_中間会議" maxlength="5" tabindex="22" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKJitaS Enter Tab lostFocusInput selfLoginNum_5"
                            lblname="登録台数予想_軽自他_増減" maxlength="5" tabindex="23" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKJitaSY selfLoginNum_6" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 軽：福祉 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKFukushiY Enter Tab lostFocusInput selfLoginNum4"
                            maxlength="5" tabindex="24" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKFukushiS Enter Tab lostFocusInput selfLoginNum5"
                            maxlength="5" tabindex="25" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKFukushiSY selfLoginNum6" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 軽自動車登録台数計
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKTDaisuY loginNum4" lblname="登録台数予想_軽自動車登録台数計_中間会議"
                            maxlength="5" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKTDaisuS loginNum5" lblname="登録台数予想_軽自動車登録台数計_増減"
                            maxlength="5" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtKTDaisuSS loginNum6" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 内ﾚﾝﾀｶｰ登録 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtRentaY Enter Tab lostFocusInput"
                            lblname="登録台数予想_内レンタカー_中間会議" maxlength="5" tabindex="26" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtRentaS Enter Tab lostFocusInput"
                            lblname="登録台数予想_内レンタカー_増減" maxlength="5" tabindex="27" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtRentaSY" maxlength="6" readonly="readonly" />
                    </td>
                </tr>
                <!--登録台数予想 end-->
            </table>
            <!--登録台数車種内訳 start-->
            <table border="0" class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE margin-right">
                <tr>
                    <td colspan="4" class="HMTVE270TargetResultEntry SUBTITLE"> 登録台数車種内訳 </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry set-height">
                    <td></td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER"> 中間会議 </td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER"> 増減 </td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER"> 最終予想 </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> デミオ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtTDaisuY Enter Tab lostFocusInput"
                            lblname="デミオ登録台数_中間会議" maxlength="3" tabindex="28" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtTDaisuS Enter Tab lostFocusInput"
                            lblname="デミオ登録台数_増減" maxlength="3" tabindex="29" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtTDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> (ZM)2 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM2GDaisuY Enter Tab lostFocusInput"
                            lblname="(ZM)2 登録台数_中間会議" maxlength="3" tabindex="30" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM2GDaisuS Enter Tab lostFocusInput"
                            lblname="(ZM)2 登録台数_増減" maxlength="3" tabindex="31" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM2GDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> CX-3 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX3DaisuY Enter Tab lostFocusInput"
                            lblname="CX-3登録台数_中間会議" maxlength="3" tabindex="32" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX3DaisuS Enter Tab lostFocusInput"
                            lblname="CX-3登録台数_増減" maxlength="3" tabindex="33" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX3DaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> CX-5 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX5Y Enter Tab lostFocusInput"
                            lblname="ＣＸ－５登録台数_中間会議" maxlength="3" tabindex="34" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX5S Enter Tab lostFocusInput" lblname="ＣＸ－５登録台数_増減"
                            maxlength="3" tabindex="35" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX5SS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> CX-8 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX8Y Enter Tab lostFocusInput"
                            lblname="ＣＸ－８登録台数_中間会議" maxlength="3" tabindex="36" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX8S Enter Tab lostFocusInput" lblname="ＣＸ－８登録台数_増減"
                            maxlength="3" tabindex="37" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX8SS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> CX-30 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX30Y Enter Tab lostFocusInput"
                            lblname="ＣＸ－３０登録台数_中間会議" maxlength="3" tabindex="38" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX30S Enter Tab lostFocusInput"
                            lblname="ＣＸ－３０登録台数_増減" maxlength="3" tabindex="39" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX30SS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> MX-30 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtMX30Y Enter Tab lostFocusInput"
                            lblname="ＭＸ－３０登録台数_中間会議" maxlength="3" tabindex="40" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtMX30S Enter Tab lostFocusInput"
                            lblname="ＭＸ－３０登録台数_増減" maxlength="3" tabindex="41" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtMX30SS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <!-- 20240326 LHB INS S -->
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> CX-60 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX60Y Enter Tab lostFocusInput"
                            lblname="ＣＸ－６０登録台数_中間会議" maxlength="3" tabindex="42" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX60S Enter Tab lostFocusInput"
                            lblname="ＣＸ－６０登録台数_増減" maxlength="3" tabindex="43" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX60SS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <!-- 20240326 LHB INS E -->
                <!-- 20240611 LHB INS S -->
                <tr class="HMTVE270TargetResultEntry CX80_TRK_DAISU">
                    <!-- 20240712 LHB UPD S -->
                    <!-- <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> CX-80 </td> -->
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT isexit" disabled="disabled">
                        CX-80 </td>
                    <!-- 20240712 LHB UPD S -->
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX80Y Enter Tab lostFocusInput"
                            lblname="ＣＸ－８０登録台数_中間会議" maxlength="3" tabindex="44" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX80S Enter Tab lostFocusInput"
                            lblname="ＣＸ－８０登録台数_増減" maxlength="3" tabindex="45" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtCX80SS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <!-- 20240611 LHB INS E -->
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> (ZM)3 SDN </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM3SDaisuY Enter Tab lostFocusInput"
                            lblname="Mazda3 SDN登録台数_中間会議" maxlength="3" tabindex="46" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM3SDaisuS Enter Tab lostFocusInput"
                            lblname="Mazda3 SDN登録台数_増減" maxlength="3" tabindex="47" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM3SDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> (ZM)3 FB </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM3HDaisuY Enter Tab lostFocusInput"
                            lblname="Mazda3 SDN登録台数_中間会議" maxlength="3" tabindex="48" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM3HDaisuS Enter Tab lostFocusInput"
                            lblname="Mazda3 SDN登録台数_増減" maxlength="3" tabindex="49" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM3HDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> (ZM)6 SDN </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM6SDaisuY Enter Tab lostFocusInput"
                            lblname="Mazda6 SDN登録台数_中間会議" maxlength="3" tabindex="50" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM6SDaisuS Enter Tab lostFocusInput"
                            lblname="Mazda6 SDN登録台数_増減" maxlength="3" tabindex="51" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM6SDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> (ZM)6 WGN </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM6WDaisuY Enter Tab lostFocusInput"
                            lblname="Mazda6 WGN登録台数_中間会議" maxlength="3" tabindex="52" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM6WDaisuS Enter Tab lostFocusInput"
                            lblname="Mazda6 WGN登録台数_増減" maxlength="3" tabindex="53" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtM6WDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> アテンザ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtATDaiSuY Enter Tab lostFocusInput"
                            lblname="アテンザ登録台数_中間会議" maxlength="3" tabindex="54" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtATDaiSuS Enter Tab lostFocusInput"
                            lblname="アテンザ登録台数_増減" maxlength="3" tabindex="55" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtATDaiSuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> アクセラ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtAXTDaisuY Enter Tab lostFocusInput"
                            lblname="アクセラ登録台数_中間会議" maxlength="3" tabindex="56" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtAXTDaisuS Enter Tab lostFocusInput"
                            lblname="アクセラ登録台数_増減" maxlength="3" tabindex="57" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtAXTDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> プレマシー </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtPTDaisuY Enter Tab lostFocusInput"
                            lblname="プレマシー登録台数_中間会議" maxlength="3" tabindex="58" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtPTDaisuS Enter Tab lostFocusInput"
                            lblname="プレマシー登録台数_増減" maxlength="3" tabindex="59" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtPTDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ビアンテ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtBianteY Enter Tab lostFocusInput"
                            lblname="ビアンテ登録台数_中間会議" maxlength="3" tabindex="60" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtBianteS Enter Tab lostFocusInput"
                            lblname="ビアンテ登録台数_増減" maxlength="3" tabindex="61" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtBianteSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> MPV </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtMTDaisuY Enter Tab lostFocusInput"
                            lblname="ＭＰＶ登録台数_中間会議" maxlength="3" tabindex="62" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtMTDaisuS Enter Tab lostFocusInput"
                            lblname="ＭＰＶ登録台数_増減" maxlength="3" tabindex="63" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtMTDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ロードスター </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtLTDaisuY Enter Tab lostFocusInput"
                            lblname="ロードスター登録台数_中間会議" maxlength="3" tabindex="64" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtLTDaisuS Enter Tab lostFocusInput"
                            lblname="ロードスター登録台数_増減" maxlength="3" tabindex="65" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtLTDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ファミリアバン </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtSTDaisuY Enter Tab lostFocusInput"
                            lblname="ファミリアバン登録台数_中間会議" maxlength="3" tabindex="66" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtSTDaisuS Enter Tab lostFocusInput"
                            lblname="ファミリアバン登録台数_増減" maxlength="3" tabindex="67" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtSTDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ボンゴ/ブローニィ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtBTaisuY Enter Tab lostFocusInput"
                            lblname="ボンゴ／ブローニィ登録台数_中間会議" maxlength="3" tabindex="68" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtBTaisuS Enter Tab lostFocusInput"
                            lblname="ボンゴ／ブローニィ登録台数_増減" maxlength="3" tabindex="69" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtBTaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> タイタン </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtTTTDaisuY Enter Tab lostFocusInput"
                            lblname="タイタン登録台数_中間会議" maxlength="3" tabindex="70" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtTTTDaisuS Enter Tab lostFocusInput"
                            lblname="タイタン登録台数_増減" maxlength="3" tabindex="71" />
                    </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtTTTDaisuSS" maxlength="4" readonly="readonly" />
                    </td>
                </tr>
            </table>
            <!--登録台数車種内訳 end-->

            <!--中古売上台数 start-->
            <table border="0" class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE margin-right">
                <tr>
                    <td colspan="5" class="HMTVE270TargetResultEntry SUBTITLE"> 中古売上台数 </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry set-height">
                    <td colspan="2"></td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        中間会議 </td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        増減 </td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        最終予想 </td>
                </tr>
                <tr>
                    <td rowspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN_C"> 当月
                        <br />
                        目標
                    </td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT CELL_PADDING_RIGHT"> 直売
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtChoku Enter Tab" lblname="中古売上台数_当月目標_直売"
                            maxlength="5" tabindex="4" />
                    </td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 業売 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtGyobai Enter Tab" lblname="中古売上台数_当月目標_業売"
                            maxlength="5" tabindex="5" />
                    </td>
                    <td colspan="2"></td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td rowspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN_C"> 月末
                        <br />
                        予想
                    </td>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 直売 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtChokuY Enter Tab lostFocusInput selfSellCNum1"
                            lblname="中古売上台数_月末予想_直売_中間会議" maxlength="5" tabindex="6" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtChokuS Enter Tab lostFocusInput selfSellCNum2"
                            lblname="中古売上台数_月末予想_直売_増減" maxlength="5" tabindex="7" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtChokuSY selfSellCNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry LOGIN_NUM_CALTYPE">
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 業売 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtGyobaiY Enter Tab lostFocusInput selfSellCNum1"
                            lblname="中古売上台数_月末予想_業売_中間会議" maxlength="5" tabindex="8" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtGyobaiS Enter Tab lostFocusInput selfSellCNum2"
                            lblname="中古売上台数_月末予想_業売_増減" maxlength="5" tabindex="9" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtGyobaiSY selfSellCNum3" maxlength="6"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 売上台数計 </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtYCk sellCNum1" lblname="中古売上台数_売上台数計_中間会議"
                            maxlength="6" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSCf sellCNum2" lblname="中古売上台数_売上台数計_増減"
                            maxlength="6" readonly="readonly" />
                    </td>
                    <td>
                        <input class="HMTVE270TargetResultEntry txtSYCk sellCNum3" maxlength="6" readonly="readonly" />
                    </td>
                </tr>
            </table>
            <!--中古売上台数 end-->
            <!--周辺利益 start-->
            <table border="0" class="HMTVE270TargetResultEntry margin-right">
                <tr>
                    <td colspan="3" class="HMTVE270TargetResultEntry SUBTITLE"> 周辺利益 </td>
                </tr>
                <tr class="HMTVE270TargetResultEntry set-height">
                    <td></td>
                    <td class="HMTVE270TargetResultEntry HMTVE270TargetResultEntry CELL_TITLE_GREEN TEXT_ALIAGN_CENTER">
                        月末予想 </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 自動車保険料 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtHoken Enter Tab" lblname="周辺利益_自動車保険" maxlength="5"
                            tabindex="72" />
                    </td>
                    <td> (千円) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 再リース </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtLease Enter Tab" lblname="周辺利益_再リース" maxlength="5"
                            tabindex="73" />
                    </td>
                    <td> (千円) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ローンＫＢ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtLoan Enter Tab" lblname="周辺利益_ローンＫＢ" maxlength="5"
                            tabindex="74" />
                    </td>
                    <td> (千円) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 希望Ｎｏ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtKibou Enter Tab" lblname="周辺利益_希望Ｎｏ" maxlength="3"
                            tabindex="75" />
                    </td>
                    <td> (件) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> 延長保証 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtP753 Enter Tab" lblname="周辺利益_延長保証" maxlength="3"
                            tabindex="76" />
                    </td>
                    <td> (件) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> Ｐメンテ </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtPMente Enter Tab" lblname="周辺利益_Ｐメンテ" maxlength="3"
                            tabindex="77" />
                    </td>
                    <td> (件) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ボディーコ－ト </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtBodycoat Enter Tab" lblname="周辺利益_ボディコ－ト"
                            maxlength="3" tabindex="78" />
                    </td>
                    <td> (件) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ＪＡＦ加入 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtJaf Enter Tab" lblname="周辺利益_ＪＡＦ加入" maxlength="3"
                            tabindex="79" />
                    </td>
                    <td> (件) </td>
                </tr>
                <tr>
                    <td class="HMTVE270TargetResultEntry CELL_TITLE_GREEN CELL_PADDING_LEFT"> ＯＳＳ加入 </td>
                    <td class="HMTVE270TargetResultEntry TEXT_ALIAGN_CENTER">
                        <input class="HMTVE270TargetResultEntry txtOss Enter Tab" lblname="周辺利益_ＯＳＳ" maxlength="3"
                            tabindex="80" />
                    </td>
                    <td> (件) </td>
                </tr>
            </table>
            <!--周辺利益 end-->
        </div>
    </div>
</div>