<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmList/FrmList'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmList' id="FrmList" style="width: 1113px;max-width: 100%">
    <div class='FrmList R4-content'>
        <!-- 検索条件-->
        <div class='FrmList searchArea'>
            <fieldset>
                <legend>
                    <b><span style="font-size: 10pt">検索条件</span></b>
                </legend>

                <label class='FrmList searchText2 lbl-grey' for="">
                    &nbsp;注文書番号&nbsp;
                </label>
                <input type='text' class='FrmList txtCMNNO Enter Tab' name="FrmList_txtCMNNO" maxlength="10" />
                <label class='FrmList searchText3 lbl-grey' style="margin-left:20px;" for="">
                    &nbsp;顧客名ｶﾅ&nbsp;
                </label>
                <input type='text' class='FrmList txtSiyFgn Enter Tab' maxlength="10" name="FrmList_txtSiyFgn" />
                <label class='FrmList searchText4 lbl-grey' style="margin-left:20px; " for="">
                    &nbsp;社員番号&nbsp;
                </label>
                <input type='text' class='FrmList txtEmpNO Enter Tab' maxlength="10" name="FrmList_txtEmpNO" />

                <button class='FrmList cmdSearch Enter Tab'>
                    検索
                </button>

            </fieldset>
        </div>

        <!-- コピー-->
        <!-- 2018/03/09 ciyuanchen UPD S. -->
        <!--<div class='FrmList copyArea' style="margin-top:10px">-->
        <div class='FrmList copyArea' style="margin-top:1px">
            <!-- 2018/02/05 ciyuanchen UPD E. -->
            <fieldset>
                <legend>
                    <b><span style="font-size: 10pt">コピー</span></b>
                </legend>

                <label class='FrmList copyText2 lbl-orange-L' for="">
                    &nbsp;コピー元 注文書番号&nbsp;
                </label>
                <input type='text' class='FrmList txtCopyStart Enter Tab' name="FrmList_txtCopyStart" maxlength="10" />

                <label class='FrmList copyText3' style="margin-left:30px;" for="">
                    →
                </label>
                <label class='FrmList copyText4 lbl-orange-L' style="margin-left:30px;" for="">
                    &nbsp;コピー先 注文書番号&nbsp;
                </label>
                <input type='text' class='FrmList txtCopyEnd Enter Tab' name="FrmList_txtCopyEnd" maxlength="10" />

                <button class='FrmList cmdUpdate Enter Tab'>
                    更新
                </button>
            </fieldset>
        </div>

        <!--end -->
        <div class='FrmList dataInfoArea' style="width:99%;margin-top:10px;">
            <table class='FrmList dataInfoArea dataInfoTable' width="99%" border=0 cellspacing="0">
                <tr>
                    <td>
                        <label class='FrmList  lbl-sky-M' for="">
                            &nbsp;契約者&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblKeiyakusya tag-L' name="FrmList_lblKeiyakusya"
                            readonly="readonly" />
                    </td>
                    <td>
                        <label class='FrmList lbl-sky-M' for="">
                            &nbsp;部署&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblBusyoCD tag-M' readonly="readonly"
                            name="FrmList_lblBusyoCD" />
                        <input type='text' class='FrmList lblBusyoNM tag-L' readonly="readonly" />
                    </td>
                    <td>

                        <!-- 2018/03/09 ciyuanchen UPD S. -->
                        <!--<label class='FrmList lbl-sky-M'-->
                        <label class='FrmList lbl-sky-M' style="width: 81px" for="">
                            <!-- 2018/03/09 ciyuanchen UPD E. -->
                            &nbsp;前回発効&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList txtUPD_DAT tag-L' name="FrmList_txtUPD_DAT"
                            readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class='FrmList lbl-sky-M' for="">
                            &nbsp;使用者&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblSiyosya tag-L' name="FrmList_lblSiyosya"
                            readonly="readonly" />
                    </td>
                    <td>
                        <label class='FrmList lbl-sky-M' for="">
                            &nbsp;社員&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblSyainNO tag-M' name="FrmList_lblSyainNO"
                            readonly="readonly" />
                        <input type='text' class='FrmList lblSyainNM tag-L' readonly="readonly" />
                    </td>
                    <td>
                        <label class='FrmList lbl-sky-M' for="">
                            &nbsp;伝票NO&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblKasouNO tag-L' name="FrmList_lblSyainNO"
                            readonly="readonly" value="03" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <!-- 2018/03/09 ciyuanchen UPD S. -->
                        <!--<label class='FrmList lbl-sky-M'-->
                        <label class='FrmList lbl-sky-M' style="width: 81px" for="">
                            <!-- 2018/03/09 ciyuanchen UPD E. -->
                            &nbsp;使用者ｶﾅ&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblSiyosyaKN tag-L' name="FrmList_lblSiyosyaKN"
                            readonly="readonly" />
                    </td>
                    <td>
                        <label class='FrmList lbl-sky-M' for="">
                            &nbsp;販売店&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblHanbaitenNO tag-M' name="FrmList_lblHanbaitenNO"
                            readonly="readonly" />
                        <input type='text' class='FrmList lblHanbaitenNM tag-L' readonly="readonly" />
                    </td>
                    <td>
                        <label class='FrmList lbl-sky-M' for="">
                            &nbsp;問合呼称&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblKosyo tag-L' name="FrmList_lblKosyo" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <label class='FrmList lbl-grey-noborder' for="">
                            &nbsp;車両配送指示&nbsp;
                        </label>
                        <input type='text' class='FrmList txtHaisouSiji Enter Tab' name="FrmList_txtHaisouSiji" />
                        <select class='FrmList cboMemo Enter Tab' style="width:200px; ">
                            <option value=""></option>
                            <option value="174 海田車両センター">174 海田車両センター</option>
                            <option value="351 MS千代田">351 MS千代田</option>
                            <option value="M04 高見自動車">M04 高見自動車</option>
                            <option value="M06 （GD）藤井モータース">M06 （GD）藤井モータース</option>
                            <option value="M08 三次（DZM）">M08 三次（DZM）</option>
                            <option value="M16 津森オート">M16 津森オート</option>
                            <option value="M17 ㈱エリート">M17 ㈱エリート</option>
                            <option value="M22 五日市藤井モータース">M22 五日市藤井モータース</option>
                            <option value="M24 角モータース">M24 角モータース</option>
                            <option value="M25 北村自動車">M25 北村自動車</option>
                            <option value="M45 ニシモト">M45 ニシモト</option>
                            <option value="R11 フォード大洲">R11 フォード大洲</option>
                            <option value="R12 フォード西条">R12 フォード西条</option>
                            <option value="M07 カーエース（GD）">M07 カーエース（GD）</option>
                            <option value="X60 矢野河野ボディー">X60 矢野河野ボディー</option>
                            <option value="X62 大洲中元自動車">X62 大洲中元自動車</option>
                            <option value="X72 アエラクリエート">X72 アエラクリエート</option>
                            <option value="999 行き先未定">999 行き先未定</option>
                        </select>
                    </td>
                    <td>
                        <label class='FrmList lbl-sky-M' for="">
                            &nbsp;消費税&nbsp;
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmList lblZei tag-L' name="FrmList_lblZei" readonly="readonly" />
                    </td>
                </tr>
            </table>
        </div>

        <!-- データ表示-->
        <div class='FrmList listTitle' style='width:98%;border:solid 0px;margin-left: 10px;margin-left: 5px;'>
            <table class='FrmList listTitle titleTable' cellspacing="0" width="98%">
                <tr>
                    <td style="float: left" width="45%">
                        【金額】
                    </td>
                    <td width="48%">
                        【架装依頼先】
                    </td>
                </tr>
            </table>
        </div>
        <div class='FrmList listArea' style='width:99%;margin-left: 0px;'>
            <table width="99%" cellspacing="0" border="0">
                <tr>
                    <td width="45%">
                        <div>
                            <table class='FrmList listArea sprMoneyList' id="FrmList_sprMoneyList">
                            </table>
                        </div>
                    </td>
                    <td width="8%">
                    </td>
                    <td width="45%">
                        <div>
                            <table class='FrmList listArea sprCustomer' id="FrmList_sprCustomer">
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- 合計-->
        <div class='FrmList countArea' style='margin-top:5px;margin-left: 5px; width:98%;height:20px;border:solid 0px;'>
            <table class='FrmList countArea countTable1' cellspacing="0" width="45.5%" style="float:left;" border="0">
                <tr>
                    <td class='List_td_header bottom_td_border' width="30%" align="right">
                    </td>
                    <td class='List_td_header bottom_td_border' width="30%">
                        社内原価合計
                    </td>
                    <td class='List_td_header bottom_td_border' width="30%" align="right">
                        <label class='FrmList lblGenkaGK' for="">
                            999,999,999
                        </label>
                    </td>
                </tr>
            </table>
            <table class='FrmList countArea countTable2' cellspacing="0" width="42%" style="float:right;">
                <tr>
                    <td class='List_td_header bottom_td_border' width="30%" align="right">
                    </td>
                    <td class='List_td_header bottom_td_border' width="30%">
                        外注実原価合計
                    </td>
                    <td class='List_td_header bottom_td_border' width="30%" align="right">
                        <label class='FrmList lblGaiJituGen' for="">
                            999,999,999
                        </label>
                    </td>
                </tr>
            </table>
        </div>

        <!-- -->
        <div class='FrmList showArea' style='margin-top:5px;margin-left: 5px; border:solid 0px;'>

            <div class='FrmList HMS-button-pane'>
                <label class='FrmList showText1 lbl-sky-L' for="">
                    車台-CARNO
                </label>
                <input class='FrmList lblSyadaiCarNO' type='text' name="FrmList_lblSyadaiCarNO"
                    style='background-color:#DFDFDF;border:solid 1px; width:250px;' readonly="readonly" />
                <div class="HMS-button-set">

                    <!-- 20180515 YIN INS S -->
                    <button class='FrmList cmdsave Enter Tab'>
                        保存
                    </button>
                    <!-- 20180515 YIN INS E -->
                    <button class='FrmList cmdPrintKasou Enter Tab'>
                        架装・外注依頼書印刷
                    </button>
                    <button class='FrmList cmdOption Enter Tab'>
                        付属品
                    </button>
                    <button class='FrmList cmdSpecial Enter Tab'>
                        特別仕様
                    </button>
                    <button class='FrmList cmdDelete Enter Tab'>
                        削除
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<div style="display: none">
    <label class='FrmList lblSyadaiKata' for="" style="width: px; padding-left: 10px;visibility: hidden">
        1234567890
    </label>
    <label class='FrmList lblCar_NO' for="" style="width: 100px; padding-left: 10px;visibility: hidden">
        1234567890
    </label>
    <label class='FrmList lblHanbaiSyasyu' for="" style="width: 100px; padding-left: 10px;visibility: hidden">
        1234567890
    </label>
    <label class='FrmList lblSyasyu_NM' for="" style="width: 100px; padding-left: 10px;visibility: hidden">
        1234567890
    </label>
</div>
</div>

<!-- <div id='FrmListOptionDialogDiv'>
</div>
<div id='FrmListSpecialDialogDiv'>
</div> -->
