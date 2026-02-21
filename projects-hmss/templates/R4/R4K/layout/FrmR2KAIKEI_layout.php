<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmR2KAIKEI/FrmR2KAIKEI'));
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmR2KAIKEI.errorArea table tr td[height="130px"] {
            height: auto !important;
        }

        .FrmR2KAIKEI.errorArea td[align="top"] {
            vertical-align: top !important;
        }
    }
</style>
<div class="FrmR2KAIKEI R4-content">
    <div class="FrmR2KAIKEI searchArea" style="font-size: 8pt ;margin-top: 0px">
        <fieldset class="FrmR2KAIKEI searchGroup" style="width: 800px ;height:80px">
            <legend>
                <b><span style="font-size: 10pt">条件</span></b>
            </legend>
            <div>
                <input class="FrmR2KAIKEI searchArea radOutFlg" name="condition" type="radio" value="CSV未出力のデータを出力" />
                <label>
                    CSV未出力のデータを出力</label>
            </div>
            <!-- <br /> -->
            <div>
                <input class="FrmR2KAIKEI searchArea radHanibi" name="condition" type="radio" value="計上日" />
                計上日
                <input class="FrmR2KAIKEI searchArea cboDateFrom Tab Enter" style="width: 140px;margin-left:50px"
                    maxlength="10" />
                <label for="">
                    ～
                </label>
                <input class="FrmR2KAIKEI searchArea cboDateTo Tab Enter" style="width: 140px" maxlength="10" />
            </div>
            <!-- <br /> -->
            <div>
                <input class="FrmR2KAIKEI searchArea radDate" name="condition" type="radio" value="日付選択" />
                日付選択
            </div>
        </fieldset>
    </div>
    <!-- 20180221 CI UPD S -->
    <!--<div class="FrmR2KAIKEI outputInfo" style="margin-top: 0px;font-size: 8pt"> -->
    <!-- 20180301 lqs UPD S -->
    <!-- <div class="FrmR2KAIKEI outputInfo" style="margin-top: 3px; font-size: 8pt;"> -->
    <div class="FrmR2KAIKEI outputInfo" style="margin-top: 3px;height:33px; font-size: 8pt;">
        <!-- 20180301 lqs UPD E -->
        <!-- 20180221 CI UPD E -->
        <!-- 20180301 lqs UPD S -->
        <!-- <fieldset class="FrmR2KAIKEI outputInfoGroup"  style="width: 800px"> -->
        <fieldset class="FrmR2KAIKEI outputInfoGroup" style="width: 800px;height:18px">
            <!-- 20180301 lqs UPD E -->
            <label for="">
                出力先
            </label>
            <input class="FrmR2KAIKEI outputInfoGroup txtOutput" style="width: 500px;margin-left: 20px"
                disabled="disabled" />
        </fieldset>
    </div>
    <div>
        <table width="410px" margin>
            <tr>
                <td>
                    <div class="FrmR2KAIKEI outputCount" style="margin-top: 0px;font-size: 8pt">
                        <!-- 20180221 CI UPD S -->
                        <!-- <fieldset class="FrmR2KAIKEI outputCountGroup"  style="width: 365px"> -->
                        <fieldset class="FrmR2KAIKEI outputCountGroup"
                            style="width: 365px; height: 20px; margin-left: -1px">
                            <!-- 20180221 CI UPD E -->
                            <label for="">
                                出力件数
                            </label>
                            <label class="FrmR2KAIKEI outputCountGroup lblCnt" for="">
                            </label>
                            <label class="FrmR2KAIKEI outputCountGroup lblAllCnt" for="">
                            </label>
                        </fieldset>
                    </div>
                </td>
                <td rowspan="2">
                    <div class="FrmR2KAIKEI listArea">
                        <fieldset class="FrmR2KAIKEI listAreaGroup" style="width: 400px">
                            <legend>
                                <b><span style="font-size: 10pt">CSV作成履歴</span></b>
                            </legend>
                            <table id="FrmR2KAIKEI_sprList">
                            </table>
                        </fieldset>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="FrmR2KAIKEI errorArea" style="margin-top: 0px">
                        <fieldset class="FrmR2KAIKEI errorAreaGroup" style="width: 365px">
                            <table width="100%">
                                <tr>
                                    <td align="top">
                                        <label style="color: #FF0000" for="">
                                            <br />
                                            科目変換未設定コード
                                            <br />
                                            があります
                                            <br />
                                            科目変換マスタを確認
                                            <br />
                                            して下さい
                                        </label>
                                    </td>
                                    <td align="right" rowspan="2">
                                        <table id="FrmR2KAIKEI_sprErrList">
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="130px">
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="FrmR2KAIKEI lblMSG" style="color: #0000CD" for="">
                    </label>
                </td </tr>
            <tr>
                <td>
                    <label class="FrmR2KAIKEI lblMSG2" style="color: #0000CD" for="">
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <!-- 20180301 lqs UPD S -->
                    <!-- <div class="HMS-button-pane"> -->
                    <div class="HMS-button-pane" style="margin-top:0px;">
                        <!-- 20180301 lqs UPD E -->
                        <div class='HMS-button-set'>
                            <button class='FrmR2KAIKEI cmdAction Tab Enter'>
                                実行
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
