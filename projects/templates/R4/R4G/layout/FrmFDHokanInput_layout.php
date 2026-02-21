<!-- /**
* 説明：
*
* @author GSDL
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                               担当
* YYYYMMDD                  #ID                     XXXXXX                             GSDL
* 20201117                  bug                     DIVのHeightが間違っています。        LQS
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('R4/R4G/FrmFDHokanInput/FrmFDHokanInput'));
// echo $this -> Html -> css('R4/FrmFDHokanInput/FrmFDHokanInput') . "\n";
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style>
    .FrmFDHokanInput.cmdAction,
    .FrmFDHokanInput.cmdBack {
        font-size: 1em !important;
    }
</style>
<div class='FrmFDHokanInput'>
    <div class='FrmFDHokanInput center' style='font-size:12px'>
        <table width=98% cellspacing="0" border="0">
            <tr>
                <td>
                    <label class='FrmFDHokanInput lbl-blue-L' for="">
                        希望車両番号
                    </label>
                </td>
                <td>
                    <input class='FrmFDHokanInput changeCOL txtKIBO_SRY_BUNRUI Tab Enter' type='text' maxlength="3">
                    -
                    <input class='FrmFDHokanInput changeCOL txtKIBO_SRY_KANA Tab Enter' type='text' maxlength="2">
                    -
                    <input class='FrmFDHokanInput changeCOL txtKIBO_SRY_KIBO Tab Enter' type='text' maxlength="4">
                </td>
                <td>
                    <label class="chrome-font-s" for="">
                        この欄には希望番号予約済証に記載されている希望車両番号
                    </label>
                    <br />
                    <label class="chrome-font-s" for="">
                        の支局等を表示する文字を除いたものを記入してください
                    </label>
                </td>
                <td align="right">
                    <label class='FrmFDHokanInput lbl-grey-M' for="">
                        注文書番号
                    </label>
                </td>
                <td>
                    <input class='FrmFDHokanInput lblCHUMN_NO Tab Enter' type='text' disabled="disabled" />
                </td>
            </tr>
            <!-- <tr>
            <td>
            </td>
            <td>
            </td>
            <td valign='top'>

            </td>
            <td>
            </td>
            <td>
            </td>
            </tr> -->
            <tr>
                <td>
                    <label class='FrmFDHokanInput lbl-blue-L' for="">
                        車両番号
                    </label>
                </td>
                <td colspan="2">
                    <input class='FrmFDHokanInput txtSRY_BAN_MOJI Tab Enter' type='text' disabled="disabled"
                        maxlength="8" />
                    <label for="">
                        -
                    </label>
                    <input class='FrmFDHokanInput txtSRY_BAN_BUNRUI Tab Enter' type='text' disabled="disabled"
                        maxlength="3" />
                    <label for="">
                        -
                    </label>
                    <input class='FrmFDHokanInput txtSRY_BAN_KANA Tab Enter' type='text' disabled="disabled"
                        maxlength="2" />
                    <label for="">
                        -
                    </label>
                    <input class='FrmFDHokanInput txtSRY_BAN_SITEI Tab Enter' type='text' disabled="disabled"
                        maxlength="4" />

                    <!--20171206 CIYUANCHEN UPD S -->
                    <!--<label class='FrmFDHokanInput lbl-blue-XS' style="margin-left: 12px;"> -->
                    <label class='FrmFDHokanInput lbl-blue-XS' style="margin-left: 7px;" for="">
                        <!--20171206 CIYUANCHEN UPD E -->
                        小判
                    </label>
                    <input class='FrmFDHokanInput txtSRY_BAN_SYOUBAN Tab Enter' type='text' disabled="disabled"
                        maxlength="1" />
                    <label class='FrmFDHokanInput lbl-grey-S' for="">
                        1 小判
                    </label>
                    <label class='FrmFDHokanInput lbl-blue-S' for="">
                        手数料
                    </label>
                    <input class='FrmFDHokanInput changeCOL txtTesuryo Tab Enter' type='text' maxlength="1">
                    <label class='FrmFDHokanInput lbl-grey-S' for="">
                        1 無料
                    </label>
                </td>
                <td align="right">
                    <label class='FrmFDHokanInput lbl-grey-M' for="">
                        登録予定日
                    </label>
                </td>
                <td>
                    <input class='FrmFDHokanInput lblTOU_Y_DT Tab Enter' type='text' disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='FrmFDHokanInput lbl-yellow-L' for="">
                        車台番号
                    </label>
                </td>
                <td>
                    <input class='FrmFDHokanInput changeCOL txtSYADAI_NO Tab Enter' type='text' maxlength="20">
                </td>
                <td>
                    <label class='FrmFDHokanInput lbl-blue' style="margin-left: 63px;" for="">
                        所有者コード
                    </label>
                    <input class='FrmFDHokanInput changeCOL txtSYOYU_CD Tab Enter' type='text' maxlength="5" />
                    <input class='FrmFDHokanInput changeCOL txtSYOYU_SIYO Tab Enter' type='text' maxlength="1" />
                    <label class='FrmFDHokanInput lbl-grey' for="">
                        1 使用者
                    </label>
                </td>
                <td align="right">
                    <label class='FrmFDHokanInput lbl-yellow-S' for="">
                        番号指示
                    </label>
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <select class='FrmFDHokanInput changeCOL cboBAN_SIJI_YOT_2 Tab Enter'>
                                    <option value="1" index="0">自家用</option>
                                    <option value="2" index="1">事業用</option>
                                    <option value="3" index="2">貸渡</option>
                                    <option value="4" index="3">AB</option>
                                </select>
                            </td>
                            <td>
                                <select class='FrmFDHokanInput changeCOL cboBAN_SIJI_HBN_1 Tab Enter'>
                                    <option value="0" index="0"></option>
                                    <option value="1" index="1">字光</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr valign="top">
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td align="right">
                    <label class="chrome-font-s" for="">
                        ※用途2のみ必須
                    </label>
                </td>
                <td>
                    <label class="chrome-font-s" style="margin-left: 15px;" for="">
                        (用途2)
                    </label>
                    <label class="chrome-font-s" style="margin-left: 15px;" for="">
                        (標板1)
                    </label>
                </td>
            </tr>
        </table>
        <table width=98% border="0" cellspacing="0">
            <tr>
                <td width=50% valign='top'>
                    <!-- 使用者-->
                    <!-- 20201117 lqs upd S -->
                    <!-- <table class="miniTbl" cellspacing="0"> -->
                    <table class="miniTbl" style="height:110px" cellspacing="0">
                        <!-- 20201117 lqs upd E -->
                        <tr>
                            <td colspan='2' style="background-color:#00BFFF;border-bottom:solid 1px; ">
                                <label for=""><b>使用者</b></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-yellow-M' for="">
                                    氏名又は名称
                                </label>
                            </td>
                            <td>
                                <input class='FrmFDHokanInput validating txtSHIYOU_NM Tab Enter' type='text'
                                    maxlength="34" />
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:5px;">
                                <label class='FrmFDHokanInput lbl-yellow-M' for="">
                                    住所
                                </label>
                            </td>
                            <td style="padding-top:5px;">
                                <input class='FrmFDHokanInput changeCOL txtSHIYOU_ADDR_CD Tab Enter' type='text'
                                    maxlength="12" />
                                <label for="">
                                    -
                                </label>
                                <input class='FrmFDHokanInput changeCOL txtSHIYOU_ADDR_1 Tab Enter' type='text'
                                    maxlength="2" />
                                <label for="">
                                    丁目-
                                </label>
                                <input class='FrmFDHokanInput changeCOL txtSHIYOU_ADDR_2 Tab Enter' type='text'
                                    maxlength="12" />
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
                                <label style="margin-right: 120px" for="">
                                    (住所コード)
                                </label>
                            </td>
                        </tr>
                    </table>
                    <!-- 所有者-->
                    <!-- 20201117 lqs upd S -->
                    <!-- <table class="miniTbl" cellspacing="0"> -->
                    <table class="miniTbl" style="height:125px" cellspacing="0">
                        <!-- 20201117 lqs upd E -->
                        <tr>
                            <td colspan='2' style="background-color:#00BFFF;border-bottom:solid 1px;">
                                <label for=""><b>所有者</b></label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-yellow-M' for="">
                                    氏名又は名称
                                </label>
                            </td>
                            <td>
                                <input class='FrmFDHokanInput validating txtSYOYU_NM Tab Enter' type='text'
                                    maxlength="32" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" colspan="2">
                                <input class='FrmFDHokanInput chkSYOYU_NM_SIYO Tab Enter' type='checkbox' />
                                <label for="">
                                    使用者と同じ
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-yellow-M' for="">
                                    住所
                                </label>
                            </td>
                            <td>
                                <input class='FrmFDHokanInput changeCOL txtSYOYU_ADDR_CD Tab Enter' type='text'
                                    maxlength="12" />
                                <label for="">
                                    -
                                </label>
                                <input class='FrmFDHokanInput changeCOL txtSYOYU_ADDR_1 Tab Enter' type='text'
                                    maxlength="2" />
                                <label for="">
                                    丁目-
                                </label>
                                <input class='FrmFDHokanInput changeCOL txtSYOYU_ADDR_2 Tab Enter' type='text'
                                    maxlength="12" />
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" colspan="2">
                                <input class='FrmFDHokanInput chkSYOYU_ADDR_SIYO Tab Enter' type='checkbox' />
                                <label for="">
                                    使用者と同じ
                                </label>
                                <label style="margin-left: 30px" for="">
                                    (住所コード)
                                </label>
                            </td>
                        </tr>
                    </table>
                    <!-- 使用の本拠の位置-->
                    <!-- 20201117 lqs upd S -->
                    <!-- <table class="miniTbl" cellspacing="0"> -->
                    <table class="miniTbl" style="height:155px" cellspacing="0">
                        <!-- 20201117 lqs upd E -->
                        <tr>
                            <td colspan='4' style="background-color:#00BFFF;border-bottom:solid 1px;">
                                <label for=""><b>使用の本拠の位置</b></label>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" colspan=2>
                                <input class='FrmFDHokanInput chkHONKYO_ADDR_SIYO Tab Enter' type='checkbox' />
                                <label for="">
                                    使用者と同じ
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-yellow-M' for="">
                                    住所
                                </label>
                            </td>
                            <td>
                                <input style='width: 120px'
                                    class='FrmFDHokanInput changeCOL txtHONKYO_ADDR_CD Tab Enter' type='text'
                                    maxlength="12" />
                                <label for="">
                                    -
                                </label>
                                <input class='FrmFDHokanInput changeCOL txtHONKYO_ADDR_1 Tab Enter' type='text'
                                    maxlength="2" />
                                <label for="">
                                    丁目-
                                </label>
                                <input class='FrmFDHokanInput changeCOL txtHONKYO_ADDR_2 Tab Enter' type='text'
                                    maxlength="12" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                                <label style="margin-left: 30px;" for="">
                                    (住所コード)
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4'>
                                <!--20171206 CIYUANCHEN UPD S -->
                                <!--<textarea  class='FrmFDHokanInput changeCOL txtHONKYO_ADDR_NM Tab Enter' maxlength="160" >-->
                                <textarea style="overflow:auto;width: 407px;"
                                    class='FrmFDHokanInput changeCOL txtHONKYO_ADDR_NM Tab Enter' maxlength="160">
                        <!--20171206 CIYUANCHEN UPD E -->
                        </textarea>
                            </td>
                        </tr>
                    </table>
                    <table style="margin-top: 10px;">
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-yellow-M' for="">
                                    型式類別
                                </label>
                            </td>
                            <td>
                                <input class='FrmFDHokanInput changeCOL txtKATASIKI Tab Enter' maxlength="9"
                                    type='text' />
                            </td>
                            <td>
                                <label class='FrmFDHokanInput lbl-yellow-M' for="">
                                    車体の塗色
                                </label>
                            </td>
                            <td>
                                <select class='FrmFDHokanInput changeCOL cboIRO_CD Tab Enter'>
                                    <option value="0" index="0"></option>
                                    <option value="1" index="1">赤</option>
                                    <option value="2" index="2">橙</option>
                                    <option value="3" index="3">茶</option>
                                    <option value="4" index="4">黄</option>
                                    <option value="5" index="5">緑</option>
                                    <option value="6" index="6">青</option>
                                    <option value="7" index="7">紫</option>
                                    <option value="8" index="8">白</option>
                                    <option value="9" index="9">灰</option>
                                    <option value="0" index="10">黒</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-blue-M' for="">
                                    製作年月日
                                </label>
                            </td>
                            <td colspan="3">
                                <input class='FrmFDHokanInput changeCOL txtSEISAKU_GENGO Tab Enter' type='text'
                                    maxlength="1" />
                                <label for="">
                                    1 昭和
                                </label>
                                <input class='FrmFDHokanInput changeCOL integer txtSEISAKU_Y Tab Enter' type='text'
                                    maxlength="2" />
                                <label for="">
                                    年
                                </label>
                                <input class='FrmFDHokanInput changeCOL integer txtSEISAKU_M Tab Enter' type='text'
                                    maxlength="2" />
                                <label for="">
                                    月
                                </label>
                                <input class='FrmFDHokanInput changeCOL integer txtSEISAKU_D Tab Enter' type='text'
                                    maxlength="2" />
                                <label for="">
                                    日
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-blue-M' for="">
                                    証明書指示
                                </label>
                            </td>
                            <td>
                                <table cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <input class='FrmFDHokanInput changeCOL txtSYOMEI_SIJI Tab Enter'
                                                type='text' maxlength="1" />
                                        </td>
                                        <td>
                                            <!-- 20201117 lqs upd S -->
                                            <!-- <label class='chrome-font-s'> -->
                                            <label class='chrome-font-s' style="margin-left:2px" for="">
                                                <!-- 20201117 lqs upd E -->
                                                1 書面提出
                                            </label>
                                            <br />
                                            <!-- 20201117 lqs upd S -->
                                            <!-- <label  class='chrome-font-s'> -->
                                            <label class='chrome-font-s' style="margin-left:2px" for="">
                                                <!-- 20201117 lqs upd E -->
                                                2 預託金解除
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td>
                                <label class='FrmFDHokanInput lbl-blue-M' for="">
                                    証明書指示２
                                </label>
                            </td>
                            <td>
                                <table cellspacing="0" border="0">
                                    <tr>
                                        <td>
                                            <input class='FrmFDHokanInput changeCOL txtSYOMEI_SIJI2 Tab Enter'
                                                type='text' maxlength="1" />
                                        </td>
                                        <td>
                                            <!-- 20201117 lqs upd S -->
                                            <!-- <label class='chrome-font-s'> -->
                                            <label class='chrome-font-s' style="margin-left:2px" for="">
                                                <!-- 20201117 lqs upd E -->
                                                1 保・自提出
                                            </label>
                                            <br />
                                            <!-- 20201117 lqs upd S -->
                                            <!-- <label  class='chrome-font-s'> -->
                                            <label class='chrome-font-s' style="margin-left:2px" for="">
                                                <!-- 20201117 lqs upd E -->
                                                2 保適証提出
                                            </label>
                                            <br />
                                            <!-- 20201117 lqs upd S -->
                                            <!-- <label  class='chrome-font-s'> -->
                                            <label class='chrome-font-s' style="margin-left:2px" for="">
                                                <!-- 20201117 lqs upd E -->
                                                3 自賠責提出
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
                <td width=50% valign='top'>
                    <!-- 申請者-->
                    <!-- 20201117 lqs upd S -->
                    <!-- <table width=100% style="border:solid 1px;margin-top:5px" cellspacing="0"> -->
                    <table width=100% style="border:solid 1px;margin-top:5px;height:400px" cellspacing="0">
                        <!-- 20201117 lqs upd E -->
                        <tr>
                            <td colspan='2' style="background-color:#00BFFF;border-bottom:solid 1px">
                                <label for="">
                                    申請者
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style='margin-left:5px;'>
                                    <!--使用者　氏名又は名称-->
                                    <label class='FrmFDHokanInput lbl-yellow-XL' for="">
                                        使用者　氏名又は名称
                                    </label>
                                    <!--20171206 CIYUANCHEN UPD S -->
                                    <!--<textarea class='FrmFDHokanInput validating txtSINSEI_SIYO_NM Tab Enter' maxlength="160" ></textarea>-->
                                    <!-- 20201117 lqs upd S -->
                                    <!-- <textarea style="overflow:auto;" class='FrmFDHokanInput validating txtSINSEI_SIYO_NM Tab Enter' maxlength="160" ></textarea> -->
                                    <textarea style="overflow:auto;height:56px"
                                        class='FrmFDHokanInput validating txtSINSEI_SIYO_NM Tab Enter'
                                        maxlength="160"></textarea>
                                    <!-- 20201117 lqs upd E -->
                                    <!--20171206 CIYUANCHEN UPD E -->
                                    <!--使用者　住所-->
                                    <label class='FrmFDHokanInput lbl-yellow-XL' for="">
                                        使用者　住所
                                    </label>
                                    <!--20171206 CIYUANCHEN UPD S -->
                                    <!--<textarea class='FrmFDHokanInput validating txtSINSEI_SIYO_ADDR Tab Enter' maxlength="160" ></textarea>-->
                                    <!-- 20201117 lqs upd S -->
                                    <!-- <textarea style="overflow:auto;" class='FrmFDHokanInput validating txtSINSEI_SIYO_ADDR Tab Enter' maxlength="160" ></textarea> -->
                                    <textarea style="overflow:auto;height:56px"
                                        class='FrmFDHokanInput validating txtSINSEI_SIYO_ADDR Tab Enter'
                                        maxlength="160"></textarea>
                                    <!-- 20201117 lqs upd E -->
                                    <!--20171206 CIYUANCHEN UPD E -->
                                    <!--所有者　氏名又は名称-->
                                    <label class='FrmFDHokanInput lbl-yellow-XL' for="">
                                        所有者　氏名又は名称
                                    </label>
                                    <!--20171206 CIYUANCHEN UPD S -->
                                    <!--<textarea style="overflow:auto;" class='FrmFDHokanInput changeCOL txtSINSEI_SYOYU_NM Tab Enter' maxlength="160" ></textarea>-->
                                    <!-- 20201117 lqs upd S -->
                                    <!-- <textarea style="overflow:auto;" class='FrmFDHokanInput changeCOL txtSINSEI_SYOYU_NM Tab Enter' maxlength="160" ></textarea> -->
                                    <textarea style="overflow:auto;height:56px"
                                        class='FrmFDHokanInput changeCOL txtSINSEI_SYOYU_NM Tab Enter'
                                        maxlength="160"></textarea>
                                    <!-- 20201117 lqs upd E -->
                                    <!--20171206 CIYUANCHEN UPD E -->
                                    <!--所有者　住所-->
                                    <label class='FrmFDHokanInput lbl-yellow-XL' for="">
                                        所有者　住所
                                    </label>
                                    <!--20171206 CIYUANCHEN UPD S -->
                                    <!--<textarea style="overflow:auto;" class='FrmFDHokanInput changeCOL txtSINSEI_SYOYU_ADDR Tab Enter' maxlength="160" ></textarea>-->
                                    <!-- 20201117 lqs upd S -->
                                    <!-- <textarea style="overflow:auto;" class='FrmFDHokanInput changeCOL txtSINSEI_SYOYU_ADDR Tab Enter' maxlength="160" ></textarea> -->
                                    <textarea style="overflow:auto;height:56px"
                                        class='FrmFDHokanInput changeCOL txtSINSEI_SYOYU_ADDR Tab Enter'
                                        maxlength="160"></textarea>
                                    <!-- 20201117 lqs upd E -->
                                    <!--20171206 CIYUANCHEN UPD E -->
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table width=100% style="border:none;margin-top:40px;" cellspacing="0">
                        <tr>
                            <td>
                                <label class='FrmFDHokanInput lbl-grey-L' for="">
                                    販売店コード
                                </label>
                                <input class='FrmFDHokanInput txtHNB_CD Tab Enter' type='text' disabled="disabled" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="HMS-button-pane">
                                    <div class='HMS-button-set'>
                                        <button class='FrmFDHokanInput cmdAction Tab Enter'>
                                            更新
                                        </button>
                                        <button class='FrmFDHokanInput cmdBack Tab Enter'>
                                            戻る
                                        </button>
                                    </div>
                                </div>
                        </tr>
                    </table>
                </td>

            </tr>
        </table>
    </div>
</div>
