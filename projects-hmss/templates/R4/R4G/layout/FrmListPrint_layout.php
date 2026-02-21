<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmListPrint/FrmListPrint'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmListPrint' id="FrmListPrint">
    <div class='FrmListPrint center R4-content'>
        <table class="FrmListPrint center tbl" border='0' style="margin:10px 5px;height:128px;">
            <tr>
                <td width="120px">
                    <input class="FrmListPrint Tab Enter radUriage" type="radio" name='radio' checked="checked"
                        value="0" />
                    入力日
                </td>
                <td width="150px" align="left">
                    <input class='FrmListPrint Tab Enter torokuDate cboStartDate' type="text" />
                </td>
                <td align="center" width="30px" align="center"><label class="FrmListPrint Label1" for=""> ～ </label>
                </td>
                <td width="150px" align="center">
                    <input class="FrmListPrint Tab Enter torokuDate cboEndDate" name="FrmListPrint_cboEndDate"
                        type="text" />
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmListPrint Tab Enter radCmnNO" type="radio" name='radio' value="1" />
                    注文書NO
                </td>
                <td align="left">
                    <input class="FrmListPrint Tab Enter txtCMN_NO" type='text' maxlength="10" disabled="disabled"
                        onfocusin="this.select();" />
                </td>
            </tr>
        </table>
        <div class="HMS-button-pane" style="margin-top: 10px">

            <button class='FrmListPrint Tab Enter cmdPreview' style="margin-top: 10px;margin-left: 150px">
                プレビュー
            </button>
            <!-- 20180601 YIN INS S -->
            <button class='FrmListPrint Tab Enter cmdPreviewagain' style="margin-top: 10px;margin-left: 50px">
                再印刷
            </button>
            <!-- 20180601 YIN INS E -->
        </div>
    </div>

    <!-- <input type="hidden" class="FrmListPrint lblKeiyakusya" placeholder="契約者 KEIYAKUSYA" value="">
         <input type="hidden" class="FrmListPrint lblBusyoCD" placeholder="部署1 KYOTN_CD" value="">
         <input type="hidden" class="FrmListPrint lblBusyoNM" placeholder="部署2 BUSYOMEI" value="">
         <input type="hidden" class="FrmListPrint lblSyainNM" placeholder="社員2 SYAIN" value="">
         <input type="hidden" class="FrmListPrint lblKasouNO" placeholder="伝票NO KASOU_NO" value="">
         <input type="hidden" class="FrmListPrint lblKosyo" placeholder="問合呼称 HANBAISYASYU" value="">
         <input type="hidden" class="FrmListPrint lblHanbaiSyasyu" placeholder="HANBAISYASYU" value="">
         <input type="hidden" class="FrmListPrint lblSyadaiKata" placeholder="SDI_KAT" value="">
         <input type="hidden" class="FrmListPrint lblCar_NO" placeholder="CAR_NO" value="">
         <input type="hidden" class="FrmListPrint lblSyasyu_NM" placeholder="BASEH_KN" value="">
    <input type="hidden" class="FrmListPrint txtHaisouSiji" placeholder="車両配送指示"  value="">
    <input type="hidden" class="FrmListPrint lblGenkaGK" placeholder="社内原価合計" value=""> -->

</div>
