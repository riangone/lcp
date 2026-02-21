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
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>

<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('SDH/SDH04'));
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="sdh sdh04 dialog" style="width: 100%;height: 100%">
    <table style="width: 100%">
        <tr>
            <td width="45%" valign="top">
                <fieldset style="width: 95%">
                    <legend>
                        <span style="font-size: 12pt">◆任意保険情報</span>
                    </legend>
                    <table style="width: 100%">
                        <tr>
                            <td width="26%">
                                <div class="sdh sdh04 tabl1 div">
                                    <label for="">
                                        保険会社
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 KAISYAMEI value" />
                                <?php
                                echo $KAISYAMEI;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="26%">
                                <div class="sdh sdh04 SYOKENNO div">
                                    <label for="">
                                        証券番号
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 SYOKENNO value" />
                                <?php
                                echo $SYOKENNO;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="26%">
                                <div class="sdh sdh04 KEIYAKUNAME div">
                                    <label for="">
                                        契約者名
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 KEIYAKUNAME value" />
                                <?php
                                echo $KEIYAKUNAME;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="26%">
                                <div class="sdh sdh04 SYURUIMEI div">
                                    <label for="">
                                        保険種類
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 SYURUIMEI value" />
                                <?php
                                echo $SYURUIMEI;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="26%">
                                <div class="sdh sdh04 HARAIKOMIMEI div">
                                    <label for="">
                                        支払方法
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 HARAIKOMIMEI value" />
                                <?php
                                echo $HARAIKOMIMEI;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="26%">
                                <div class="sdh sdh04 HOKENSIKI div">
                                    <label for="">
                                        始期～終期
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 HOKENSIKI value" />
                                <?php
                                echo $HOKENSIKI;
                                ?>
                                <label for="" class="sdh sdh04 COUNTDATA1 value" />
                                <?php
                                echo $COUNTDATA1;
                                ?>
                                <label for="" class="sdh sdh04 HOKENSYUKI value" />
                                <?php
                                echo $HOKENSYUKI;
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>

            <td width="55%" valign="top">
                <fieldset style="width: 90%">
                    <legend>
                        <span style="font-size: 12pt">◆クレジット詳細</span>
                    </legend>
                    <table style="width: 100%">
                        <tr>
                            <td bgcolor="#99FFFF" color="#808080" font-weight="bold" rowspan="11" width="20%">
                                <div class="sdh sdh04 tabl2 div">
                                    <label for="">
                                        契約内容
                                    </label>
                                </div>
                            </td>
                            <td width="15%">
                                <div class="sdh sdh04 SHR_GKN_DPS div">
                                    <label for="">
                                        現金（頭金）
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 SHR_GKN_DPS value" />
                                <?php
                                echo $SHR_GKN_DPS;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 ZAN_SET_GKU div">
                                    <label for="">
                                        据置額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 ZAN_SET_GKU value" />
                                <?php
                                echo $ZAN_SET_GKU;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 KRJ_MOT_KIN div">
                                    <label for="">
                                        クレジット現金
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 KRJ_MOT_KIN value" />
                                <?php
                                echo $KRJ_MOT_KIN;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 SCD_NM div">
                                    <label for="">
                                        クレジット会社
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 SCD_NM value" />
                                <?php
                                echo $SCD_NM;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 ROI div">
                                    <label for="">
                                        金利
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 ROI value" />
                                <?php
                                echo $ROI;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 KRJ_BUN_KSU div">
                                    <label for="">
                                        分割回数
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 KRJ_BUN_KSU value" />
                                <?php
                                echo $KRJ_BUN_KSU;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 KRJ_SHR_KKN_FRO div">
                                    <label for="">
                                        支払い期間
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" style="width: 60" class="sdh sdh04 KRJ_SHR_KKN_FRO value" />
                                <?php
                                echo $KRJ_SHR_KKN_FRO;
                                ?>
                                <label for="" class="sdh sdh04 COUNTDATA2 value" />
                                <?php
                                echo $COUNTDATA2;
                                ?>
                                <label for="" style="width: 60" class="sdh sdh04 KRJ_SHR_KKN_TO value" />
                                <?php
                                echo $KRJ_SHR_KKN_TO;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">
                                <div class="sdh sdh04 SHR_DT div">
                                    <label for="">
                                        支払日
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 SHR_DT value" />
                                <?php
                                echo $SHR_DT;
                                if ($SHR_DT != "" && $SHR_DT != NULL) {
                                    echo "日";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 BNS_ADD_SHR_GKU div">
                                    <label for="">
                                        ボーナス加算額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 BNS_ADD_SHR_GKU value" />
                                <?php
                                echo $BNS_ADD_SHR_GKU;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">
                                <div class="sdh sdh04 PASENNTO div">
                                    <label for="">
                                        ボーナス比率
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" class="sdh sdh04 PASENNTO value" />
                                <?php
                                echo $PASENNTO;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 BNS_SHR_MM1 div">
                                    <label for="">
                                        ボーナス支払月
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" style="width: 30" class="sdh sdh04 BNS_SHR_MM1 value" />
                                <?php
                                echo $BNS_SHR_MM1;
                                ?>
                                <label for="" class="sdh sdh04 COUNTDATA3 value" />
                                <?php
                                echo $COUNTDATA3;
                                ?>
                                <label for="" style="width: 30" class="sdh sdh04 BNS_SHR_MM2 value" />
                                <?php
                                echo $BNS_SHR_MM2;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#99FFFF" color="#808080" font-weight="bold" rowspan="4">
                                <div class="sdh sdh04 table3 div">
                                    <label for="">
                                        支払内容
                                    </label>
                                </div>
                            </td>
                            <td width="15%">
                                <div class="sdh sdh04 table3 div">
                                    <label for="">
                                        ボーナス加算額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" style="width: 100" class="sdh sdh04 BNS_ADD_SHR_GKU value" />
                                <?php
                                echo $BNS_ADD_SHR_GKU;
                                ?>
                                <label for="" style="width: 30" class="sdh sdh04 COUNTDATA4 value" />
                                <?php
                                echo $COUNTDATA4;
                                ?>
                                <label for="" style="width: 100" class="sdh sdh04 BNS_KSU value" />
                                <?php
                                echo $BNS_KSU;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 FIR_FNL_SHR_GKU div">
                                    <label for="">
                                        初回
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" style="width: 100" class="sdh sdh04 FIR_FNL_SHR_GKU value" />
                                <?php
                                echo $FIR_FNL_SHR_GKU;
                                ?>
                                <label for="" class="sdh sdh04 COUNTDATA5 value" />
                                <?php
                                echo $COUNTDATA5;
                                ?>
                                <label for="" style="width: 100" class="sdh sdh04 FIR_FNL_SHR_KSU value" />
                                <?php
                                echo $FIR_FNL_SHR_KSU;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 MM_SHR_GKU div">
                                    <label for="">
                                        毎月
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" style="width: 100" class="sdh sdh04 MM_SHR_GKU value" />
                                <?php
                                echo $MM_SHR_GKU;
                                ?>
                                <label for="" class="sdh sdh04 COUNTDATA6 value" />
                                <?php
                                echo $COUNTDATA6;
                                ?>
                                <label for="" style="width: 100" class="sdh sdh04 KRJ_BUN_KSU_VAL value" />
                                <?php
                                echo $KRJ_BUN_KSU_VAL;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="15%">
                                <div class="sdh sdh04 SUM div">
                                    <label for="">
                                        総支払額
                                    </label>
                                </div>
                            </td>
                            <td class="sdh sdh04 valuealign">
                                <label for="" style="width: 100" class="sdh sdh04 SUM value" />
                                <?php
                                echo $SUM;
                                ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>

    </table>
</div>