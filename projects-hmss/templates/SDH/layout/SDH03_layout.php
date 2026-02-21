<!-- /**
* 説明：
*
*
* @author jinmingai
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20150611           ---                       注文書編集要領変更             HM
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('SDH/SDH03'));
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="sdh sdh03 dialog" style="width: 100%;height: 100%">
    <style type="text/css">
    </style>
    <table style="width: 100%">
        <tr>
            <td colspan="8">
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆管理情報</span></b>
                    </legend>
                    <table style="width:100%;">
                        <td width="10%">
                            <div class="sdh sdh03 div_UC_NO div sfont">
                                <label for="">
                                    ＵＣNo：
                                </label>
                            </div>
                        </td>
                        <td width="15%">
                            <label for="" class="sdh sdh03 lbl_UC_NO value sfont">
                                <?php
                                echo $UC_NO;
                                ?>
                            </label>
                        </td>
                        <td width="10%">
                            <div class="sdh sdh03 div_CMN_NO div sfont">
                                <label for="">
                                    注文書No：
                                </label>
                            </div>
                        </td>
                        <td width="15%">
                            <label for="" class="sdh sdh03 lbl_CMN_NO value sfont">
                                <?php
                                echo $CMN_NO;
                                ?>
                            </label>
                        </td>
                        <td width="10%">
                        </td>
                        <td width="15%">
                        </td>
                        <td width="10%">
                        </td>
                        <td width="15%">
                        </td>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_BUY_SHP div sfont">
                                    <label for="">
                                        購入形態：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_BUY_SHP value sfont">
                                    <?php
                                    echo $BUY_SHP;
                                    ?>
                                </label>
                            </td>
                            <td>
                                <div class="sdh sdh03 div_ABHOT_KB div sfont">
                                    <label for="">
                                        販売形態：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_ABHOT_KB value sfont">
                                    <?php
                                    echo $ABHOT_KB;
                                    ?>
                                </label>
                            </td>
                            <td>
                                <div class="sdh sdh03 div_OU_DT div sfont">
                                    <label for="">
                                        登録日：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_OU_DT value sfont">
                                    <?php
                                    echo $TOU_DT;
                                    ?>
                                </label>
                            </td>
                            <td>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_HBSS_CD div sfont">
                                    <label for="">
                                        ８桁コード：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_HBSS_CD value sfont">
                                    <?php
                                    echo $HBSS_CD;
                                    ?>
                                </label>
                            </td>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        CARNO：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $CARNO
                                        ?>
                                </label>
                            </td>
                            <td>
                                <div class="sdh sdh03 div_TOURK_NO div sfont">
                                    <label for="">
                                        登録NO：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_TOURK_NO value sfont">
                                    <?php
                                    echo $TOURK_NO;
                                    ?>
                                </label>
                            </td>
                            <td>
                                <div class="sdh sdh03 div_VCLNM div sfont">
                                    <label for="">
                                        車名：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_VCLNM value sfont">
                                    <?php
                                    echo $VCLNM;
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td style="width: 50%">
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆契約者</span></b>
                    </legend>
                    <table style="width:100%;">
                        <tr>
                            <td width="25%">
                                <div class="sdh sdh03 div_KYK_YBN_NO div sfont">
                                    <label for="">
                                        郵便NO：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_KYK_YBN_NO value sfont">
                                    <?php
                                    echo $KYK_YBN_NO;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="sdh sdh03 div_ div sfont">
                                <label for="">
                                    住所：
                                </label>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $KYK_ADR1 . $KYK_ADR2 . $KYK_ADR3;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="sdh sdh03 div_KYK_CUS_NM1 div sfont">
                                <label for="">
                                    契約者：
                                </label>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_KYK_CUS_NM1 value sfont">
                                    <?php
                                    echo $KYK_CUS_NM1;
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
            <td>
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆DM送付先</span></b>
                    </legend>
                    <table style="width:100%;">
                        <tr>
                            <td width="25%" class="sdh sdh03 div_SIY_YBN_NO div sfont">
                                <label for="">
                                    郵便NO：
                                </label>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_SIY_YBN_NO value sfont">
                                    <?php
                                    echo $SIY_YBN_NO;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="sdh sdh03 div_ div sfont">
                                <label for="">
                                    住所：
                                </label>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $SIY_ADR1 . $SIY_ADR2 . $SIY_ADR3;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="sdh sdh03 div_SIY_CUS_NM1 div sfont">
                                <label for="">
                                    DM送付先：
                                </label>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_SIY_CUS_NM1 value sfont">
                                    <?php
                                    echo $SIY_CUS_NM1;
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td>
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆名義人</span></b>
                    </legend>
                    <table style="width:100%;">
                        <tr>
                            <td width="25%">
                                <div class="sdh sdh03 div_SIY_YBN_NO div sfont">
                                    <label for="">
                                        郵便NO：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_SIY_YBN_NO value sfont">
                                    <?php
                                    echo $SIY_YBN_NO;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        住所：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $SIY_ADR1 . $SIY_ADR2 . $SIY_ADR3;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_SIY_CUS_NM1 div sfont">
                                    <label for="">
                                        名義人：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_SIY_CUS_NM1 value sfont">
                                    <?php
                                    echo $SIY_CUS_NM1;
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
            <td>
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆扱者</span></b>
                    </legend>
                    <table style="width:100%;">
                        <tr>
                            <td width="25%">
                                <div class="sdh sdh03 div_KYOTN_RKN div sfont">
                                    <label for="">
                                        部署：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_KYOTN_RKN value sfont">
                                    <?php
                                    echo $KYOTN_RKN;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        社員：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $SYAIN_KNJ_SEI . '　' . $SYAIN_KNJ_MEI;
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_DAIRITN_CD div sfont">
                                    <label for="">
                                        業者：
                                    </label>
                                </div>
                            </td>
                            <td>
                                <label for="" class="sdh sdh03 lbl_DAIRITN_CD value sfont">
                                    <?php
                                    echo $DAIRITN_CD;
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td>
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆購入条件</span></b>
                    </legend>
                    <table style="width:100%;">
                        <tr>
                            <td width="65%">
                                <div class="sdh sdh03 div_SRY_HTA_PRC_ZKM div sfont">
                                    <label for="">
                                        車両本体価格
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_SRY_HTA_PRC_ZKM value sfont">
                                    <?php
                                    echo $SRY_HTA_PRC_ZKM = $SRY_HTA_PRC_ZKM == 0 || $SRY_HTA_PRC_ZKM == "" ? "" : number_format($SRY_HTA_PRC_ZKM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_SRY_HTA_NBK_GKU_ZKM div sfont">
                                    <label for="">
                                        車両本体値引額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_SRY_HTA_NBK_GKU_ZKM value sfont">
                                    <?php
                                    echo $SRY_HTA_NBK_GKU_ZKM = $SRY_HTA_NBK_GKU_ZKM == 0 || $SRY_HTA_NBK_GKU_ZKM == "" ? "" : number_format($SRY_HTA_NBK_GKU_ZKM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        車両店頭引渡価格
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $SRY_TET_HWS_KKU = $SRY_TET_HWS_KKU == 0 || $SRY_TET_HWS_KKU == "" ? "" : number_format($SRY_TET_HWS_KKU);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_FZH_SUM_GKU_ZKM div sfont">
                                    <label for="">
                                        付属品店頭引渡価格１
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_FZH_SUM_GKU_ZKM value sfont">
                                    <?php
                                    echo $FZH_SUM_GKU_ZKM = $FZH_SUM_GKU_ZKM == 0 || $FZH_SUM_GKU_ZKM == "" ? "" : number_format($FZH_SUM_GKU_ZKM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_TKB_KSH_SUM_GKU_ZKM div sfont">
                                    <label for="">
                                        付属品店頭引渡価格２
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_TKB_KSH_SUM_GKU_ZKM value sfont">
                                    <?php
                                    echo $TKB_KSH_SUM_GKU_ZKM = $TKB_KSH_SUM_GKU_ZKM == 0 || $TKB_KSH_SUM_GKU_ZKM == "" ? "" : number_format($TKB_KSH_SUM_GKU_ZKM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        現金価格計
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $KGK_KKU_KEI = $KGK_KKU_KEI == 0 || $KGK_KKU_KEI == "" ? "" : number_format($KGK_KKU_KEI);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_KAP_TES div sfont">
                                    <label for="">
                                        割賦手数料
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_KAP_TES value sfont">
                                    <?php
                                    echo $KAP_TES = $KAP_TES == 0 || $KAP_TES == "" ? "" : number_format($KAP_TES);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_BET_SHR_HYO_SUM_GKU_ZKM div sfont">
                                    <label for="">
                                        別途支払費用
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_BET_SHR_HYO_SUM_GKU_ZKM value sfont">
                                    <?php
                                    echo $BET_SHR_HYO_SUM_GKU_ZKM = $BET_SHR_HYO_SUM_GKU_ZKM == 0 || $BET_SHR_HYO_SUM_GKU_ZKM == "" ? "" : number_format($BET_SHR_HYO_SUM_GKU_ZKM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_OPT_HOK_KIN div sfont">
                                    <label for="">
                                        任意保険料
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_OPT_HOK_KIN value sfont">
                                    <?php
                                    echo $OPT_HOK_KIN = $OPT_HOK_KIN == 0 || $OPT_HOK_KIN == "" ? "" : number_format($OPT_HOK_KIN);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        販売価格合計
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $HBA_KKU_GKE = $HBA_KKU_GKE == 0 || $HBA_KKU_GKE == "" ? "" : number_format($HBA_KKU_GKE);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <!--<div class="sdh sdh03 div_TRA_CAR_RCY_YTK_SUM_GKU div sfont">-->
                                <div class="sdh sdh03 div_YOTAK_GK div sfont">
                                    <label for="">
                                        リサイクル預託金相当額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <!--<label for="" class="sdh sdh03 lbl_TRA_CAR_RCY_YTK_SUM_GKU value sfont">-->
                                <label for="" class="sdh sdh03 lbl_YOTAK_GK value sfont">
                                    <?php
                                    //20150611 Update Start
                                    // echo number_format($TRA_CAR_RCY_YTK_SUM_GKU);
                                    echo $TRA_CAR_RCY_YTK_SUM_GKU = $TRA_CAR_RCY_YTK_SUM_GKU == 0 || $TRA_CAR_RCY_YTK_SUM_GKU == "" ? "" : number_format($TRA_CAR_RCY_YTK_SUM_GKU);
                                    //echo $YOTAK_GK = $YOTAK_GK == 0 ? "" : number_format($YOTAK_GK);
                                    //20150611 Update End
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_SIY_SMI_CAR_KNR_HYO div sfont">
                                    <label for="">
                                        使用済車引取お客様支払額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_SIY_SMI_CAR_KNR_HYO value sfont">
                                    <?php
                                    echo $SIY_SMI_CAR_KNR_HYO = $SIY_SMI_CAR_KNR_HYO == 0 || $SIY_SMI_CAR_KNR_HYO == "" ? "" : number_format($SIY_SMI_CAR_KNR_HYO);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        購入条件合計
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $KONYU_ZYK_GOK = $KONYU_ZYK_GOK == 0 || $KONYU_ZYK_GOK == "" ? "" : number_format($KONYU_ZYK_GOK);
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
            <td valign=top>
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆支払条件</span></b>
                    </legend>
                    <table style="width:100%;">
                        <tr>
                            <td width="65%">
                                <div class="sdh sdh03 div_SHR_GKN_DPS div sfont">
                                    <label for="">
                                        現金（含申込金）
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_SHR_GKN_DPS value sfont">
                                    <?php
                                    echo $SHR_GKN_DPS = $SHR_GKN_DPS == 0 || $SHR_GKN_DPS == "" ? "" : number_format($SHR_GKN_DPS);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_KAP_MOT_KIN div sfont">
                                    <label for="">
                                        割賦元金
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_KAP_MOT_KIN value sfont">
                                    <?php
                                    echo $KAP_MOT_KIN = $KAP_MOT_KIN == 0 || $KAP_MOT_KIN == "" ? "" : number_format($KAP_MOT_KIN);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_KAP_TES div sfont">
                                    <label for="">
                                        割賦手数料
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_KAP_TES value sfont">
                                    <?php
                                    echo $KAP_TES = $KAP_TES == 0 || $KAP_TES == "" ? "" : number_format($KAP_TES);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        賦払金計
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $KAP_KEI = $KAP_KEI == 0 || $KAP_KEI == "" ? "" : number_format($KAP_KEI);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        支払金計
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $SIHARAI = $SIHARAI == 0 || $SIHARAI == "" ? "" : number_format($SIHARAI);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_TRA_CAR_PRC_SUM div sfont">
                                    <label for="">
                                        下取車価格
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_TRA_CAR_PRC_SUM value sfont">
                                    <?php
                                    echo $TRA_CAR_PRC_SUM = $TRA_CAR_PRC_SUM == 0 || $TRA_CAR_PRC_SUM == "" ? "" : number_format($TRA_CAR_PRC_SUM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_TRA_CAR_SHZ_SUM div sfont">
                                    <label for="">
                                        下取車消費税額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_TRA_CAR_SHZ_SUM value sfont">
                                    <?php
                                    echo $TRA_CAR_SHZ_SUM = $TRA_CAR_SHZ_SUM == 0 || $TRA_CAR_SHZ_SUM == "" ? "" : number_format($TRA_CAR_SHZ_SUM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_TRA_CAR_ZSI_SUM div sfont">
                                    <label for="">
                                        下取車残債(－)
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_TRA_CAR_ZSI_SUM value sfont">
                                    <?php
                                    echo $TRA_CAR_ZSI_SUM = $TRA_CAR_ZSI_SUM == 0 || $TRA_CAR_ZSI_SUM == "" ? "" : number_format($TRA_CAR_ZSI_SUM);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_TRA_CAR_RCY_YTK_SUM_GKU div sfont">
                                    <label for="">
                                        下取車リサイクル預託金相当額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_TRA_CAR_RCY_YTK_SUM_GKU value sfont">
                                    <?php
                                    //20150611 Update Start
                                    //echo $RCYL_GK;
                                    echo $RCYL_GK = $RCYL_GK == 0 || $RCYL_GK == "" ? "" : number_format($RCYL_GK);
                                    //20150611 Update End
                                    ?>
                                </label>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        使用済車引取販売店支払額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $SIYOUZUMI = $SIYOUZUMI == 0 || $SIYOUZUMI == "" ? "" : number_format($SIYOUZUMI);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        下取/使用済車充当額計
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    //20150611 Update Start
                                    //$SIYOUZUMI = $SIYOUZUMI == "" ? 0 : $SIYOUZUMI;
                                    //$SITATORI = $SITATORI + $SIYOUZUMI;
                                    echo $SITATORI = $SITATORI == 0 || $SITATORI == "" ? "" : number_format($SITATORI);
                                    //20150611 Update End
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        支払条件合計
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    $SIHARAI = str_replace(",", "", $SIHARAI) == "" ? 0 : str_replace(",", "", $SIHARAI);
                                    $SITATORI = str_replace(",", "", $SITATORI) == "" ? 0 : str_replace(",", "", $SITATORI);
                                    //20150611 Update Start
                                    //$GOUKEI = $SIHARAI - $SITATORI;
                                    $GOUKEI = $SIHARAI + $SITATORI;
                                    //20150611 Update End
                                    echo $GOUKEI = $GOUKEI == 0 || $GOUKEI == "" ? "" : number_format($GOUKEI);
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td valign=top>
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆付属品明細１</span></b>
                    </legend>
                    <table style="width: 100%">
                        <?php
                        echo $table_m41e12_1;
                        ?>
                        <tr>
                            <td style='width:65%'>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        付属品計１
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $HUZOKU1 = $HUZOKU1 == 0 || $HUZOKU1 == "" ? "" : number_format($HUZOKU1);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_NBK_ZKM div sfont">
                                    <label for="">
                                        付属品１割引
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_NBK_ZKM value sfont">
                                    <?php
                                    echo $NBK_ZKM1 = $NBK_ZKM1 == 0 || $NBK_ZKM1 == "" ? "" : number_format($NBK_ZKM1);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        付属品店頭引渡価格１
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $NBK_ZKM_KAKAKU1 = $NBK_ZKM_KAKAKU1 == 0 || $NBK_ZKM_KAKAKU1 == "" ? "" : number_format($NBK_ZKM_KAKAKU1);
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
            <td valign=top>
                <fieldset style="border-color: #808080;padding: 3px">
                    <legend>
                        <b><span style="font-size: 10pt">◆付属品明細２</span></b>
                    </legend>
                    <table style="width: 100%">
                        <?php
                        echo $table_m41e12_2;
                        ?>
                        <tr>
                            <td style='width:65%'>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        付属品計２
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $HUZOKU2 = $HUZOKU2 == 0 || $HUZOKU2 == "" ? " " : number_format($HUZOKU2);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        付属品２割引
                                    </label>
                                </div>

                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $NBK_ZKM2 = $NBK_ZKM2 == 0 || $NBK_ZKM2 == "" ? "" : number_format($NBK_ZKM2);
                                    ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="sdh sdh03 div_ div sfont">
                                    <label for="">
                                        付属品店頭引渡価格２
                                    </label>
                                </div>

                            </td>
                            <td class="sdh sdh03 price">
                                <label for="" class="sdh sdh03 lbl_ value sfont">
                                    <?php
                                    echo $NBK_ZKM_KAKAKU2 = $NBK_ZKM_KAKAKU2 == 0 || $NBK_ZKM_KAKAKU2 == "" ? "" : number_format($NBK_ZKM_KAKAKU2);
                                    ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>
</div>