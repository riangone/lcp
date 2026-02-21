<?php
// bignumberを使う原因は(0.35).toFixed(1)=0.3, new bigNumber(0.35).toFixed(1)=0.4
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyoreikinSyoriMente/FrmSyoreikinSyoriMente", "JKSYS/bignumber.min.js"));
?>
<style type="text/css">
    .FrmSyoreikinSyoriMente.JKSYS-content {
        overflow-y: hidden;
    }

    .FrmSyoreikinSyoriMente.labelStyle {
        display: inline-block;
        vertical-align: top;
    }

    .FrmSyoreikinSyoriMente.tabsList {
        padding: 2px;
        width: 98%;
        height: 730px
    }

    .FrmSyoreikinSyoriMente.tabsPanel {
        padding: 7px;
        padding-bottom: 0px
    }

    .FrmSyoreikinSyoriMente.tabsPanel>div {
        margin: 2px;
    }

    .FrmSyoreikinSyoriMente.Label11 {
        color: red;
        font-size: 12pt;
        float: right
    }

    .FrmSyoreikinSyoriMente fieldset {
        border-color: white;
    }

    .FrmSyoreikinSyoriMente button {
        float: none;
    }

    .FrmSyoreikinSyoriMente.fieldset-M {
        width: 200px;
    }

    .FrmSyoreikinSyoriMente.fieldset-S {
        width: 340px;
    }

    .FrmSyoreikinSyoriMente.fieldset-L {
        width: 450px;
    }

    .FrmSyoreikinSyoriMente.fieldset-XM {
        width: 620px;
    }

    .FrmSyoreikinSyoriMente.fieldset-XS {
        width: 800px;
    }

    .FrmSyoreikinSyoriMente.fieldset-XL {
        width: 930px;
    }

    .FrmSyoreikinSyoriMente.Label12 {
        width: 80px;
        height: 190px;
        line-height: 190px;
    }

    .FrmSyoreikinSyoriMente.lbl-sky-xL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-top: 5px;
        margin-left: 2px;
        width: 160px;
    }

    .FrmSyoreikinSyoriMente.text-right {
        text-align: right
    }

    .FrmSyoreikinSyoriMente.HMS-button-pane {
        margin-top: 0px;
    }

    .FrmSyoreikinSyoriMente.grpGyoTaisyoRoute>fieldset>div,
    .FrmSyoreikinSyoriMente.grpTenTaisyoRoute>fieldset>div {
        margin-top: 5px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmSyoreikinSyoriMente.tabsList {
            height: 670px
        }

        .FrmSyoreikinSyoriMente .ui-jqgrid-view {
            line-height: 2.17em;
        }

        .FrmSyoreikinSyoriMente.Label12 {
            width: 77px;
        }

        .FrmSyoreikinSyoriMente.fieldset-XM {
            width: 603px;
        }

        .FrmSyoreikinSyoriMente.fieldset-XL {
            width: 912px;
        }

        .FrmSyoreikinSyoriMente.fieldset-XS {
            width: 780px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmSyoreikinSyoriMente">
    <div class="FrmSyoreikinSyoriMente JKSYS-content JKSYS-content-fixed-width">
        <div class="FrmSyoreikinSyoriMente tabsList">
            <ul class="FrmSyoreikinSyoriMente tabsUI">
                <li class="FrmSyoreikinSyoriMente tabsLI_Eigyoukeisu" tabindex="0">
                    <a href="#tabs-1">業績奨励係数管理</a>
                </li>
                <li class="FrmSyoreikinSyoriMente tabsLI_EigyouTen">
                    <a href="#tabs-2">業績奨励支給管理</a>
                </li>
                <li class="FrmSyoreikinSyoriMente tabsLI_Tencyoukeisu">
                    <a href="#tabs-3">店長奨励係数管理</a>
                </li>
                <li class="FrmSyoreikinSyoriMente tabsLI_TencyouTen">
                    <a href="#tabs-4">店長奨励支給管理</a>
                </li>
            </ul>
            <div id="tabs-1" class="FrmSyoreikinSyoriMente tabsPanel">
                <div>
                    <label class="FrmSyoreikinSyoriMente lbl-sky-L" for=""> 処理選択
                    </label>
                    <input type="radio" value="rdoGyoKeisuSyurui" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoGyoKeisuSyurui Tab Enter' tabindex="3" />
                    係数種類
                    <input type="radio" value="rdoGyokeisuKomoku" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoGyokeisuKomoku Tab Enter' tabindex="4" />
                    係数項目
                    <input type="radio" value="rdoGyoTaisyoRoute" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoGyoTaisyoRoute Tab Enter' tabindex="5" />
                    係数種類別対象販売ルート <label class="FrmSyoreikinSyoriMente Label11" for=""> 業績奨励 </label>
                </div>
                <div class="FrmSyoreikinSyoriMente grpGyoKeisuSyurui fieldset-XM">
                    <fieldset>
                        <legend>
                            <b><span>係数種類</span></b>
                        </legend>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprGyoKeisuSyurui"
                                id="FrmSyoreikinSyoriMente_sprGyoKeisuSyurui"></table>
                        </div>
                    </fieldset>
                </div>
                <div class="FrmSyoreikinSyoriMente grpGyokeisuKomoku fieldset-XM">
                    <fieldset>
                        <legend>
                            <b><span>係数項目</span></b>
                        </legend>
                        <div class="FrmSyoreikinSyoriMente HMS-button-pane ">
                            <label class="FrmSyoreikinSyoriMente labelStyle lbl-sky-L" for=""> 係数種類 </label>
                            <select class="FrmSyoreikinSyoriMente cmbGyoKeisuSyurui1 Enter Tab" tabindex="9"></select>
                            <div class="HMS-button-set">
                                <button class="FrmSyoreikinSyoriMente btnAddGyoKomoku  Enter Tab" tabindex="11">
                                    行追加
                                </button>
                                <button class="FrmSyoreikinSyoriMente btnDelGyoKomoku  Enter Tab" tabindex="12">
                                    行削除
                                </button>
                            </div>

                        </div>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprGyokeisuKomoku"
                                id="FrmSyoreikinSyoriMente_sprGyokeisuKomoku"></table>
                        </div>
                    </fieldset>
                </div>
                <div class="FrmSyoreikinSyoriMente grpGyoTaisyoRoute fieldset-L">
                    <fieldset>
                        <legend>
                            <b><span>係数種類別対象販売ルート</span></b>
                        </legend>
                        <div>
                            <label class="FrmSyoreikinSyoriMente labelStyle lbl-sky-L" for=""> 係数種類 </label>
                            <select class="FrmSyoreikinSyoriMente cmbGyoKeisuSyurui2 Enter Tab" tabindex="14"></select>
                        </div>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprGyoTaisyoRoute"
                                id="FrmSyoreikinSyoriMente_sprGyoTaisyoRoute"></table>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div id="tabs-2" class="FrmSyoreikinSyoriMente tabsPanel">
                <div>
                    <label class="FrmSyoreikinSyoriMente lbl-sky-L" for=""> 処理選択
                    </label></td>
                    <input type="radio" value="rdoGyoTaisyo" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoGyoTaisyo Tab Enter' tabindex="3" />
                    支給対象
                    <input type="radio" value="rdoGyoJogen" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoGyoJogen Tab Enter' tabindex="4" />
                    支給上限
                    <input type="radio" value="rdoGyoKakeritu" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoGyoKakeritu Tab Enter' tabindex="5" />
                    算出奨励金掛け率
                    <label class="FrmSyoreikinSyoriMente Label11" for=""> 業績奨励
                    </label>
                </div>
                <div class="FrmSyoreikinSyoriMente grpGyoTaisyo fieldset-XL">
                    <fieldset>
                        <legend>
                            <b><span>支給対象</span></b>
                        </legend>
                        <div class="FrmSyoreikinSyoriMente HMS-button-pane">
                            <div class="HMS-button-set">
                                <button class="FrmSyoreikinSyoriMente btnAddGyoTaisyo  Enter Tab" tabindex="8">
                                    行追加
                                </button>
                                <button class="FrmSyoreikinSyoriMente btnDelGyoTaisyo  Enter Tab" tabindex="9">
                                    行削除
                                </button>
                            </div>
                        </div>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprGyoTaisyo" id="FrmSyoreikinSyoriMente_sprGyoTaisyo">
                            </table>
                        </div>
                    </fieldset>
                </div>
                <div class="FrmSyoreikinSyoriMente grpGyoJogen fieldset-XS">
                    <fieldset>
                        <legend>
                            <b><span>支給上限</span></b>
                        </legend>
                        <div class="FrmSyoreikinSyoriMente HMS-button-pane">
                            <label class="FrmSyoreikinSyoriMente lbl-sky-L" for="">
                                正社員 </label>
                            <input type="text" class="FrmSyoreikinSyoriMente txtGyoJogen Tab Enter text-right"
                                tabindex="11" />
                            <div class="HMS-button-set">
                                <button class="FrmSyoreikinSyoriMente btnAddGyoJogen  Enter Tab" tabindex="13">
                                    行追加
                                </button>
                                <button class="FrmSyoreikinSyoriMente btnDelGyoJogen  Enter Tab" tabindex="14">
                                    行削除
                                </button>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <td><label class="FrmSyoreikinSyoriMente Label12 lbl-sky-L" for=""> 正社員以外 </label></td>
                                <td>
                                    <div>
                                        <table class="FrmSyoreikinSyoriMente sprGyoJogen"
                                            id="FrmSyoreikinSyoriMente_sprGyoJogen"></table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
                <div class="FrmSyoreikinSyoriMente grpGyoKakeritu fieldset-S">
                    <fieldset>
                        <legend>
                            <b><span>算出奨励金掛け率</span></b>
                        </legend>
                        <div>
                            <label class="FrmSyoreikinSyoriMente lbl-sky-xL" for=""> 支給額　＝　計算額　× </label>
                            <input type="text" class="FrmSyoreikinSyoriMente txtGyoKakeritu Tab Enter text-right"
                                tabindex="16" />
                        </div>
                    </fieldset>
                </div>
            </div>
            <div id="tabs-3" class="FrmSyoreikinSyoriMente tabsPanel">
                <div>
                    <label class="FrmSyoreikinSyoriMente lbl-sky-L" for="">
                        処理選択</label>
                    <input type="radio" value="rdoTenKeisuSyurui" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoTenKeisuSyurui Tab Enter' tabindex="3" />
                    係数種類
                    <input type="radio" value="rdoTenkeisuKomoku" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoTenkeisuKomoku Tab Enter' tabindex="4" />
                    係数項目
                    <input type="radio" value="rdoTenTaisyoRoute" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoTenTaisyoRoute Tab Enter' tabindex="5" />
                    係数種類別対象販売ルート </td> <label class="FrmSyoreikinSyoriMente Label11" for=""> 店長奨励 </label>
                </div>
                <div class="FrmSyoreikinSyoriMente grpTenKeisuSyurui fieldset-XM">
                    <fieldset>
                        <legend>
                            <b><span>係数種類</span></b>
                        </legend>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprTenKeisuSyurui"
                                id="FrmSyoreikinSyoriMente_sprTenKeisuSyurui"></table>
                        </div>
                    </fieldset>
                </div>
                <div class="FrmSyoreikinSyoriMente grpTenKeisuKomoku fieldset-XM">
                    <fieldset>
                        <legend>
                            <b><span>係数項目</span></b>
                        </legend>
                        <div class="FrmSyoreikinSyoriMente HMS-button-pane">
                            <label class="FrmSyoreikinSyoriMente labelStyle　lbl-sky-L" for=""> 係数種類 </label>
                            <select class="FrmSyoreikinSyoriMente cmbTenKeisuSyurui1 Enter Tab" tabindex="9"></select>
                            <div class="HMS-button-set">
                                <button class="FrmSyoreikinSyoriMente btnAddTenKomoku  Enter Tab" tabindex="11">
                                    行追加
                                </button>
                                <button class="FrmSyoreikinSyoriMente btnDelTenKomoku  Enter Tab" tabindex="12">
                                    行削除
                                </button>
                            </div>
                        </div>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprTenkeisuKomoku"
                                id="FrmSyoreikinSyoriMente_sprTenkeisuKomoku"></table>
                        </div>
                    </fieldset>
                </div>
                <div class="FrmSyoreikinSyoriMente grpTenTaisyoRoute fieldset-L">
                    <fieldset>
                        <legend>
                            <b><span>係数種類別対象販売ルート</span></b>
                        </legend>
                        <div>
                            <label class="FrmSyoreikinSyoriMente labelStyle　lbl-sky-L" for=""> 係数種類 </label>
                            <select class="FrmSyoreikinSyoriMente cmbTenKeisuSyurui2 Enter Tab" tabindex="14"></select>
                        </div>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprTenTaisyoRoute"
                                id="FrmSyoreikinSyoriMente_sprTenTaisyoRoute"></table>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div id="tabs-4" class="FrmSyoreikinSyoriMente tabsPanel">
                <div>
                    <label class="FrmSyoreikinSyoriMente lbl-sky-L" for=""> 処理選択
                    </label>
                    <input type="radio" value="rdoTenTaisyo" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoTenTaisyo Tab Enter' tabindex="3" />
                    支給対象
                    <input type="radio" value="rdoTenJogen" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoTenJogen Tab Enter' tabindex="4" />
                    支給上限
                    <input type="radio" value="rdoTenKakeritu" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoTenKakeritu Tab Enter' tabindex="5" />
                    総限界利益掛け率
                    <input type="radio" value="rdoTenSyutoku" name="rdo"
                        class='FrmSyoreikinSyoriMente rdoTenSyutoku Tab Enter' tabindex="6" />
                    限界/経常取得部署
                    <label class="FrmSyoreikinSyoriMente Label11" for=""> 店長奨励
                    </label>
                </div>
                <div class="FrmSyoreikinSyoriMente grpTenTaisyo fieldset-XS">
                    <fieldset>
                        <legend>
                            <b><span>支給対象</span></b>
                        </legend>
                        <div class="FrmSyoreikinSyoriMente HMS-button-pane">
                            <div class="HMS-button-set">
                                <button class="FrmSyoreikinSyoriMente btnAddTenTaisyo  Enter Tab" tabindex="9">
                                    行追加
                                </button>
                                <button class="FrmSyoreikinSyoriMente btnDelTenTaisyo  Enter Tab" tabindex="10">
                                    行削除
                                </button>
                            </div>
                        </div>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprTenTaisyo" id="FrmSyoreikinSyoriMente_sprTenTaisyo">
                            </table>
                        </div>
                    </fieldset>
                </div>

                <div class="FrmSyoreikinSyoriMente grpTenJogen fieldset-M labelStyle">
                    <fieldset>
                        <legend>
                            <b><span>支給上限</span></b>
                        </legend>
                        <div>
                            <input type="text" class="FrmSyoreikinSyoriMente txtTenJogen Tab Enter text-right"
                                tabindex="11" />
                        </div>
                    </fieldset>
                </div>
                <div class="FrmSyoreikinSyoriMente grpTenKakeritu fieldset-M labelStyle">
                    <fieldset>
                        <legend>
                            <b><span>総限界利益掛け率</span></b>
                        </legend>
                        <div>
                            <input type="text" class="FrmSyoreikinSyoriMente txtTenKakeritu Tab Enter text-right"
                                tabindex="14" />
                            %
                        </div>
                    </fieldset>
                </div>

                <div class="FrmSyoreikinSyoriMente grpTenSyutoku fieldset-XL">
                    <fieldset>
                        <legend>
                            <b><span>限界/経常取得部署</span></b>
                        </legend>
                        <div class="FrmSyoreikinSyoriMente HMS-button-pane">
                            <div class="HMS-button-set">
                                <button class="FrmSyoreikinSyoriMente btnAddTenSyutoku  Enter Tab" tabindex="17">
                                    行追加
                                </button>
                                <button class="FrmSyoreikinSyoriMente btnDelTenSyutoku  Enter Tab" tabindex="18">
                                    行削除
                                </button>
                            </div>
                        </div>
                        <div>
                            <table class="FrmSyoreikinSyoriMente sprTenSyutoku"
                                id="FrmSyoreikinSyoriMente_sprTenSyutoku"></table>
                        </div>
                    </fieldset>
                </div>

            </div>
        </div>

        <div class="FrmSyoreikinSyoriMente HMS-button-pane">
            <div class="HMS-button-set">
                <button class="FrmSyoreikinSyoriMente btnCancel Enter Tab" tabindex="20">
                    キャンセル
                </button>
                <button class="FrmSyoreikinSyoriMente btnUpdate Enter Tab" tabindex="21">
                    更新
                </button>
            </div>
        </div>
    </div>
</div>