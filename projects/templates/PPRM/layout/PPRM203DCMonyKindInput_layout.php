<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->css(array('PPRM/PPRM203DCMonyKindInput'));
echo $this->Html->script(array('PPRM/PPRM203DCMonyKindInput'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM203DCMonyKindInput.btn.disabled,
    .PPRM203DCMonyKindInput.btn[disabled],
    fieldset[disabled] .PPRM203DCMonyKindInput.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM203DCMonyKindInput.ipt.disabled,
    .PPRM203DCMonyKindInput.ipt[disabled],
    fieldset[disabled] .PPRM203DCMonyKindInput.ipt {
        background-color: #BABEC1 !important
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .PPRM203DCMonyKindInput .footer {
            margin-top: -33px !important;
        }

        .PPRM203DCMonyKindInput .trheight {
            height: 25px !important;
        }

    }
</style>
<!-- 20170908 lqs ups 画面上金额及文字居左居右属性 -->
<div class='PPRM203DCMonyKindInput body' id="PPRM203DCMonyKindInput">
    <div class="PPRM203DCMonyKindInput header">
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <tr class="PPRM203DCMonyKindInput trheight" style="height:40px;">
                    <td>
                        <label for="" class='PPRM203DCMonyKindInput lbl-sky-xM'>
                            店舗
                        </label>
                    </td>
                    <td>
                        <input class='PPRM203DCMonyKindInput ipt txtTenpoCD Tab Enter' maxlength="3" tabindex="1">
                    </td>
                    <td>
                        <button class='PPRM203DCMonyKindInput btn Tab btnTenpoSearch' tabindex="2">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM203DCMonyKindInput ipt lblTenpo' style="background:#BABEC1"
                            readonly="readonly" tabindex="-1" />
                    </td>
                    <!-- 20170907 lqs UPD S -->
                    <!-- <td style="padding-left:50px;"> -->
                    <td style="padding-left:15px;">
                        <!-- 20170907 lqs UPD E -->
                        <label for="" class='PPRM203DCMonyKindInput lbl-sky-xM'>
                            日締№
                        </label>
                    </td>
                    <td>
                        <input class='PPRM203DCMonyKindInput ipt Enter Tab txtHJMNo' maxlength="12" tabindex="3">
                    </td>
                    <td>
                        <button class='PPRM203DCMonyKindInput btn Tab btnHJMSearch' tabindex="4">
                            検索
                        </button>
                    </td>
                    <td width="150"></td>
                </tr>
                <tr class="PPRM203DCMonyKindInput trheight" style="height:40px;">
                    <td>
                        <label for="" class='PPRM203DCMonyKindInput lbl-sky-xM'>
                            日締日
                        </label>
                    </td>
                    <td class="PPRM203DCMonyKindInput block">
                        <input class='PPRM203DCMonyKindInput ipt Tab Enter txtHJMDate' maxlength="10" tabindex="5">
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <!-- 20170907 lqs UPD S -->
                    <!-- <td style="padding-left:50px;"> -->
                    <td style="padding-left:15px;">
                        <!-- 20170907 lqs UPD E -->
                        <label for="" class='PPRM203DCMonyKindInput lbl-sky-xM'>
                            入力種類
                        </label>
                    </td>
                    <td>
                        <!-- 20170907 lqs UPD S -->
                        <!-- <input type="radio" class='PPRM203DCMonyKindInput  Tab rdbSyurui' checked="checked" tabindex="-1"> -->
                        <input type="radio" class='PPRM203DCMonyKindInput Tab Enter rdbSyurui' checked="checked"
                            tabindex="6">
                        <!-- 20170907 lqs UPD E -->
                        営業
                    </td>
                    <!-- 20170907 lqs UPD S -->
                    <!-- <td style="padding-left:50px;"> -->
                    <td colspan="2">
                        <!-- 20170907 lqs UPD E -->
                        <button class='PPRM203DCMonyKindInput btn Tab btnDisp' tabindex="6">
                            表示
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div class="PPRM203DCMonyKindInput pnlInput">
        <!-- 20170908 lqs UPD S -->
        <!-- <div class="PPRM203DCMonyKindInput panel clearfix"> -->
        <div class="PPRM203DCMonyKindInput panel clearfix" style=" margin-top: 5px">
            <!-- 20170908 lqs UPD E -->
            <div class="PPRM203DCMonyKindInput left" style=" margin-right: 15px;width:635px">
                <div class="PPRM203DCMonyKindInput title">
                    <span><b>金種別残高</b></span>
                </div>
                <!-- 20170907 lqs UPD S -->
                <!-- <div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all box1"> -->
                <div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all box1" style=" margin-left: 5px;">
                    <!-- 20170907 lqs UPD E -->
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <td style="width:52px">
                                    <label for=""
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        金種
                                    </label>
                                </td>
                                <td style="width:87px;">
                                    <label for=""
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        枚数
                                    </label>
                                </td>
                                <td style="width:175px;">
                                    <label for="" style="width:175px;"
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        残高
                                    </label>
                                </td>
                                <td style="width:35px;">
                                    <label for=""
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        金種
                                    </label>
                                </td>
                                <td style="width:87px;">
                                    <label for=""
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        枚数
                                    </label>
                                </td>
                                <td style="width:165px;">
                                    <label for="" style="width:165px;"
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        残高
                                    </label>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: right; width:52px">
                                    10,000
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_10000 numberInput'
                                        style=" text-align: right; width:45px;" maxlength="9" tabindex="90">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_10000'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                                <td style="text-align: right; width:35px;">
                                    500
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_500 numberInput'
                                        style="text-align: right; width:45px;" maxlength="9" tabindex="94">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_500'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width:52px">
                                    5,000
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_5000'
                                        style="text-align: right; width:45px;" maxlength="9" tabindex="91">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_5000'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                                <td style="text-align: right;">
                                    100
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_100'
                                        style="text-align: right; width:45px;" maxlength="9" tabindex="95">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_100'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width:52px">
                                    2,000
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_2000'
                                        style="text-align: right; width:45px;" maxlength="9" tabindex="92">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_2000'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                                <td style="text-align: right;">
                                    50
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_50'
                                        style="text-align: right;width:45px;" maxlength="9" tabindex="96">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_50'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: right; width:52px">
                                    1,000
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_1000'
                                        style="text-align: right;width:45px;" maxlength="9" tabindex="93">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_1000'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                                <td style="text-align: right;">
                                    10
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_10'
                                        style="text-align: right;width:45px;" maxlength="9" tabindex="97">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_10'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td style="text-align: right;">
                                    5
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_5'
                                        style="text-align: right;width:45px;" maxlength="9" tabindex="98">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_5'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td style="text-align: right;">
                                    1
                                </td>
                                <td>
                                    <input class='PPRM203DCMonyKindInput ipt Enter TabKey txtMaisu_1'
                                        style="text-align: right;width:45px;" maxlength="9" tabindex="99">
                                    <span>枚</span>
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKin_1'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                            </tr>
                            <tr style="background-color:#87CEFA">
                                <td>
                                </td>
                                <td style="text-align:left">
                                    小計①
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblShiheiGoukei'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                                <td>
                                </td>
                                <td style="text-align:left">
                                    小計②
                                </td>
                                <td style="text-align:right">
                                    <label for="" class='PPRM203DCMonyKindInput lblKoukaGoukei'>
                                        0
                                    </label>
                                    <span>円</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="PPRM203DCMonyKindInput right">
                <div class="PPRM203DCMonyKindInput title">
                    <span><b>小切手</b></span>
                    <button class='PPRM203DCMonyKindInput Tab btn btnRowAdd' tabindex="17">
                        行追加
                    </button>
                    <button class='PPRM203DCMonyKindInput Tab btn btnRowDel' tabindex="18">
                        行削除
                    </button>
                </div>
                <div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all box2" style="">
                    <table class="PPRM203DCMonyKindInput Kogite" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <!-- 20171009 lqs UPD S -->
                                <!-- <td style="width:155px;"> -->
                                <td style="width:204px;">
                                    <!-- 20171009 lqs UPD E -->
                                    <label for=""
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        小切手№
                                    </label>
                                </td>
                                <!-- 20171009 lqs UPD S -->
                                <!-- <td style="width:168px;"> -->
                                <td style="width:170px;">
                                    <!-- 20171009 lqs UPD E -->
                                    <label for=""
                                        class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                        金額
                                    </label>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <table class="PPRM203DCMonyKindInput KogiteGoukei" cellspacing="0" cellpadding="0">
                        <tr style="background-color:#87CEFA">
                            <td style="width:120px;text-align: left">
                                小計③
                            </td>
                            <td style="text-align:right">
                                <label for="" class='PPRM203DCMonyKindInput lblKogiteGoukei'>
                                    0
                                </label>
                                <span>円</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="PPRM203DCMonyKindInput footer clearfix">
            <!-- 20170907 lqs UPD S -->
            <!-- <div class="PPRM203DCMonyKindInput left" > -->
            <div class="PPRM203DCMonyKindInput left" style=" margin-right: 50px">
                <!-- 20170907 lqs UPD E -->
                <label for="" class='PPRM203DCMonyKindInput lbl-sky-xM pnlLabel_Riyu'>
                    帳簿上の残高と実際の残高の不一致の理由
                </label>
                <label for="" class='PPRM203DCMonyKindInput lbl-sky-xM pnlDummyLabel' style="display:none;border:none">
                </label>
                <input class='PPRM203DCMonyKindInput ipt Enter Tab pnlText_Riyu txtRiyu'>
                <label for="" class='PPRM203DCMonyKindInput lbl-sky-xM pnlDummyText' style="display:none;border:none">
                </label>
            </div>
            <div class="PPRM203DCMonyKindInput right">
                <table cellpadding="0" cellspacing="0" style="width:500px;">
                    <tr>
                        <td style="width:33%">
                        </td>
                        <td style="width:30%">
                            <label for="" class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                帳簿上の残高
                            </label>
                        </td>
                        <td style="width:37%">
                            <label for="" class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                実際の残高
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <!-- 20171207 lqs UPD S -->
                        <!-- <td style="width:33%"> -->
                        <td style="width:34%">
                            <!-- 20171207 lqs UPD E -->
                            <label for="" class='PPRM203DCMonyKindInput ui-state-default ui-jqgrid-hdiv normalHeader'>
                                営業現金残高 ①＋②＋③
                            </label>
                        </td>
                        <td style="text-align:right">
                            <label for="" class='PPRM203DCMonyKindInput lblTyouboGoukei'>
                                0
                            </label>
                            <span>円</span>
                        </td>
                        <td style="text-align:right">
                            <label for="" class='PPRM203DCMonyKindInput lblJissaiGoukei'>
                                0
                            </label>
                            <span>円</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- 20170908 lqs UPD S -->
    <!-- <div class="PPRM203DCMonyKindInput footerButton"> -->
    <div class="PPRM203DCMonyKindInput footerButton" style=" margin-top: 5px">
        <!-- 20170908 lqs UPD E -->
        <button class='PPRM203DCMonyKindInput Tab btn btnTouroku' tabindex="19">
            登録
        </button>
        <button class='PPRM203DCMonyKindInput Tab btn btnDelete' tabindex="20">
            削除
        </button>
        <button class='PPRM203DCMonyKindInput Tab btn btnClose' tabindex="21">
            閉じる
        </button>
    </div>
    <div id="PPRM203DCMonyKindInput_dialogs" class="PPRM203DCMonyKindInput dialogs705" style="display: none;">
    </div>
    <div id="PPRM203DCMonyKindInput_dialogs" class="PPRM203DCMonyKindInput dialogs202" style="display: none;">
    </div>
</div>
