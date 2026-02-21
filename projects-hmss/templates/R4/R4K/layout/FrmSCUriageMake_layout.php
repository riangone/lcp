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
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150911                  #2114                   BUG                         LI
* 20180122                  #2807                   依頼                         YIN
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSCUriageMake/FrmSCUriageMake"));
echo $this->fetch('meta');
echo $this->fetch('script');
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmSCUriageMake .HMS-button-pane {
            margin-top: 0 !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmSCUriageMake'>
    <div class='FrmSCUriageMake content R4-content' style="width: 1113px">
        <!--前回抽出条件-->
        <div class='FrmSCUriageMake GroupBox4'>
            <fieldset style="background-color: #EBEBEB">
                <legend>
                    <span style="font-size: 10pt">前回抽出条件</span>
                </legend>
                <table>
                    <tr>
                        <td align="right">
                            <label class="FrmSCUriageMake GroupBox4 Label12" for="">
                                処理年月
                            </label>
                        </td>
                        <td>
                            <label class="FrmSCUriageMake GroupBox4 lblUPSYRYM"
                                style="border: solid #777777 1px;width: 100px;height: 19px" for="">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <label class="FrmSCUriageMake GroupBox4 Label10" for="">
                                更新年月日
                            </label>
                        </td>
                        <td>
                            <label class="FrmSCUriageMake GroupBox4 lblUPFromDT"
                                style="border: solid #777777 1px;width: 120px;height: 19px" for="">
                            </label>
                        </td>
                        <td>
                            <label class="FrmSCUriageMake GroupBox4 Label11" for="">
                                ～
                            </label>

                        </td>
                        <td>
                            <label class="FrmSCUriageMake GroupBox4 lblUPToDT"
                                style="border: solid #777777 1px;width: 120px;height: 19px" for="">
                            </label>
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td>
                            <label class="FrmSCUriageMake GroupBox4 Label7" style="padding-left: 13px" for="">
                                新中区分
                            </label>
                        </td>
                        <td>
                            <input class="FrmSCUriageMake GroupBox4 rdoUPNew Enter Tab" type="radio" name='UPradio'
                                value="0" disabled="true" />
                            新車
                        </td>
                        <td>
                            <input class="FrmSCUriageMake GroupBox4 rdoUPUsed Enter Tab" type="radio" name='UPradio'
                                value="1" disabled="true" />
                            中古車
                        </td>
                        <td>
                            <input class="FrmSCUriageMake GroupBox4 rdoUPAll Enter Tab" type="radio" name='UPradio'
                                value="2" disabled="true" />
                            全て
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="height: 15px">
            <!--抽出条件-->
            <div class='FrmSCUriageMake GroupBox1'>
                <fieldset>
                    <legend>
                        <span style="font-size: 10pt">抽出条件</span>
                    </legend>
                    <table>
                        <tr>
                            <td align="right">
                                <label class="FrmSCUriageMake GroupBox1 Label4" for="">
                                    処理年月
                                </label>
                            </td>
                            <td>
                                <!-- 20150922 yin upd S-->
                                <!-- <input class="FrmSCUriageMake GroupBox1 cboUCNO Tab Enter" style="width: 100px" /> -->
                                <input class="FrmSCUriageMake GroupBox1 cboUCNO Tab Enter" style="width: 100px"
                                    maxlength="6" />
                                <!-- 20150922 yin upd E -->
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label class="FrmSCUriageMake GroupBox1 Label6" for="">
                                    更新年月日
                                </label>
                            </td>
                            <td>
                                <input class="FrmSCUriageMake GroupBox1 cboDateFrom Tab Enter" style="width: 140px" />
                            </td>
                            <td>
                                <label class="FrmSCUriageMake GroupBox1 Label3" for="">
                                    ～
                                </label>

                            </td>
                            <td>
                                <input class="FrmSCUriageMake GroupBox1 cboDateTo Tab Enter" style="width: 140px" />
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td>
                                <label class="FrmSCUriageMake GroupBox1 Label1" style="padding-left: 13px" for="">
                                    新中区分
                                </label>
                            </td>
                            <td>
                                <input class="FrmSCUriageMake GroupBox1 rdoNew Enter Tab" type="radio" name='radio'
                                    value="0" />
                                新車
                            </td>
                            <td>
                                <input class="FrmSCUriageMake GroupBox1 rdoUsed Enter Tab" type="radio" name='radio'
                                    value="1" />
                                中古車
                            </td>
                            <td>
                                <input class="FrmSCUriageMake GroupBox1 rdoAll Enter Tab" type="radio" name='radio'
                                    value="2" />
                                全て
                            </td>
                        </tr>
                        <!--20150911 li DEL S. -->
                        <!-- <tr style="height: 15px"> -->
                        <!--20150911 li DEL E. -->
                        </tr>
                    </table>
                </fieldset>
            </div>
            <!--20150911 li DEL S. -->
            <!-- <div style="height: 15px">
            </div> -->
            <!--20150911 li DEL E. -->
            <div class='FrmSCUriageMake GroupBox23' style='width:99%;margin-left: 0px;'>
                <table width="99%" cellspacing="0" border="0">
                    <tr>
                        <td width="45%" valign="top">
                            <div class="FrmSCUriageMake GroupBox2">
                                <fieldset>
                                    <legend>
                                        <span style="font-size: 10pt">処理件数</span>
                                    </legend>
                                    <table>
                                        <tr style="height: 15px">
                                        </tr>
                                        <tr>
                                            <td width="8px">

                                            </td>
                                            <td style="width: 220px">
                                                <label class="FrmSCUriageMake GroupBox2 lblTitleChu" for="">
                                                    新車売上
                                                </label>
                                            </td>
                                            <td align="right" style="width: 80px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntNew" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntNewCenter" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntNewA" for="">

                                                </label>
                                            </td>
                                        </tr>
                                        <tr style="height: 10px">
                                        </tr>
                                        <tr>
                                            <td width="8px">

                                            </td>
                                            <td style="width: 220px">
                                                <label class="FrmSCUriageMake GroupBox2 lblTitleChuDel" for="">
                                                    中古車売上
                                                </label>
                                            </td>
                                            <td align="right" style="width: 80px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntUsed" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntUsedCenter" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntUsedA" for="">

                                                </label>
                                            </td>
                                        </tr>
                                        <tr style="height: 10px">
                                        </tr>
                                        <tr>
                                            <td width="8px">

                                            </td>
                                            <td style="width: 220px">
                                                <label class="FrmSCUriageMake GroupBox2 lblTitlePDB" for="">
                                                    新車条件変更
                                                </label>
                                            </td>
                                            <td align="right" style="width: 80px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntNewChg" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntNewChgCenter" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntNewChgA" for="">

                                                </label>
                                            </td>
                                        </tr>
                                        <tr style="height: 10px">
                                        </tr>
                                        <tr>
                                            <td width="8px">

                                            </td>
                                            <td style="width: 220px">
                                                <label class="FrmSCUriageMake GroupBox2 Label8" for="">
                                                    中古車条件変更
                                                </label>
                                            </td>
                                            <td align="right" style="width: 80px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntUsedChg" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntUsedChgCenter" for="">

                                                </label>
                                            </td>
                                            <td align="right" style="width: 30px">
                                                <label class="FrmSCUriageMake GroupBox2 lblCntUsedChgA" for="">

                                                </label>
                                            </td>
                                        </tr>
                                        <!--20150911 li UPD S. -->
                                        <!-- <tr style="height: 80px"> -->
                                        <!-- 20180122 YIN DEL S -->
                                        <!-- <tr style="height: 30px"> -->
                                        <!-- 20180122 YIN DEL E -->
                                        <!--20150911 li UPD E. -->
                    </tr>
                </table>
                </fieldset>
            </div>
            </td>
            <td width="2%">
            </td>
            <td width="51%">
                <div class="FrmSCUriageMake GroupBox3">
                    <fieldset>
                        <legend>
                            <span style="font-size: 10pt">原価マスタ未登録ﾃﾞｰﾀ</span>
                        </legend>
                        <table id="FrmSCUriageMake_sprList">
                        </table>
                    </fieldset>
                </div>
            </td>
            </tr>
            </table>
        </div>
        <div class="FrmSCUriageMake labels">
            <table>
                <tr>
                    <td>
                        <label class="FrmSCUriageMake labels lblMSG" for="">

                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="FrmSCUriageMake labels lblMSG2" for="">

                        </label>
                    </td>
                </tr>
                <!--20150911 li DEL S. -->
                <!-- <tr style="height: 10px">

                    </tr> -->
                <!--20150911 li DEL E. -->
            </table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSCUriageMake cmdAction Tab Enter'>
                    実行
                </button>
            </div>
        </div>
    </div>
</div>
