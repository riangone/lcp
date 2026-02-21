<!-- /**
* 説明：
*
*
* @author lijun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           		担当
* YYYYMMDD           #ID                       XXXXXX                         		FCSDL
* 20161008           #2575			      	  車両業務システム_要望対応           	    yangyang
* 20221017           対応		R4との総額不一致チェックを行っているが不一致となった場合   	yinhuaiyu
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmOkaiagePrint/FrmOkaiagePrint'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<?php
// ヘッダー/メニュー情報の読込み
// echo $this->element('header');
// echo $this->element('menu');
?>
<!-- 20221017 YIN INS S -->
<style type="text/css">
    .FrmOkaiagePrint .error-td {
        vertical-align: baseline;
    }

    .FrmOkaiagePrint .error-label {
        width: 80px;
    }

    .FrmOkaiagePrint .error-message {
        line-height: 120%;
    }

    .FrmOkaiagePrint .error-tb {
        margin-top: 5px;
    }

    .FrmOkaiagePrint .error-div {
        width: 570px;
        max-height: 390px;
        overflow-y: auto;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmOkaiagePrint .widthClass150 {
            width: 118px;
        }
    }
</style>
<!-- 20221017 YIN INS E -->
<!-- 画面個別の内容を表示 -->
<div class='FrmOkaiagePrint'>
    <div class='FrmOkaiagePrint center R4-content'>
        <table class="FrmOkaiagePrint center tbl" border='0' style="margin:10px 5px;height:55vh;">
            <tr>
                <td width="120px">
                    <input class="FrmOkaiagePrint Tab Enter radUriage" type="radio" name='radio' checked="checked"
                        value="0" />
                    登録日
                </td>
                <td class="widthClass150" width="150px" align="left">
                    <input class='FrmOkaiagePrint Tab Enter torokuDate cboStartDate' type="text" />
                </td>
                <td align="center" width="30px" align="center"><label class="FrmOkaiagePrint Label1" for=""> ～ </label>
                </td>
                <td class="widthClass150" width="150px" align="center">
                    <input class="FrmOkaiagePrint Tab Enter torokuDate cboEndDate" name="FrmOkaiagePrint_torokuDate"
                        type="text" />
                </td>
                <!-- 20221017 YIN INS S -->
                <td rowspan="11" class="FrmOkaiagePrint error-td">
                    <div class="FrmOkaiagePrint error-div">
                        <table class="FrmOkaiagePrint error-tb">
                            <tr class="FrmOkaiagePrint gross-error-tr">
                                <td colspan="2"> 総額がR4データと一致しないため、以下のデータは出力されませんでした </td>
                            </tr>
                            <tr class="FrmOkaiagePrint gross-error-tr">
                                <td class="FrmOkaiagePrint error-td error-label">
                                    <p>
                                        注文書NO：
                                    </p>
                                </td>
                                <td>
                                    <p class="FrmOkaiagePrint gross error-message"></p>
                                </td>
                            </tr>
                            <tr class="FrmOkaiagePrint recycle-error-tr">
                                <td colspan="2"> リサイクル預託金が発生しているため、以下のデータは出力されませんでした </td>
                            </tr>
                            <tr class="FrmOkaiagePrint recycle-error-tr">
                                <td class="FrmOkaiagePrint error-td error-label">
                                    <p>
                                        注文書NO：
                                    </p>
                                </td>
                                <td>
                                    <p class="FrmOkaiagePrint recycle error-message"></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <!-- 20221017 YIN INS E -->
            </tr>
            <tr>
                <td>
                    <input class="FrmOkaiagePrint Tab Enter radCmnNO" type="radio" name='radio' value="1" />
                    注文書NO
                </td>
                <td align="left">
                    <!--20161008 yangyang upd s --><!-- <input class="FrmOkaiagePrint Tab Enter txtCMN_NO" type='text' maxlength="10" disabled="disabled" onfocusin="this.select();" /> -->
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO1" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                    <!--20161008 yangyang upd e -->
                </td>
                <!--20161008 yangyang add s -->
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO2" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <!--20161008 yangyang add e -->
            </tr>
            <!-- 20161008 yangyang add s -->
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO3" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO4" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO5" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO6" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO7" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO8" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO9" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO10" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO11" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO12" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO13" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO14" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO15" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO16" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO17" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO18" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO19" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
                <td></td>
                <td align="left">
                    <input class="FrmOkaiagePrint Tab Enter txtCMN_NO txtCMN_NO20" type='text' maxlength="10"
                        disabled="disabled" onfocusin="this.select();" />
                </td>
            </tr>
            <!-- 20161008 yangyang add e -->
        </table>
        <!--20141204 fuxiaolin add s  -->
        <div class="FrmOkaiagePrint chk" style="margin-left: 40px;margin-top: 10px">
            <input type="checkbox" class="FrmOkaiagePrint Tab Enter chk1" />
            お買上明細書を印刷する
            <input type="checkbox" class="FrmOkaiagePrint Tab Enter chk2" checked="checked" style="margin-left: 20px" />
            納品請求書を印刷する
        </div>
        <!--20141204 fuxiaolin add e  -->
        <div class="HMS-button-pane" style="margin-top: 10px">

            <button class='FrmOkaiagePrint Tab Enter cmdPreview' style="margin-top: 10px;margin-left: 150px">
                プレビュー
            </button>

        </div>
    </div>
</div>
