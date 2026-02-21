<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmChuKaverRankHyoKRSS/FrmChuKaverRankHyoKRSS"));
?>

<!-- 画面個別の内容を表示 -->
<div class="KRSS FrmChuKaverRankHyoKRSS  R4-content">
    <div>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <div style="margin-left: 35px;margin-top: 15px;margin-bottom: 15px">
                <table>
                    <tr>
                        <td><label class="KRSS FrmChuKaverRankHyoKRSS label-snow" for="" style="width: 76px"> 処理年月
                            </label>
                        </td>
                        <td></td>
                        <td>
                            <input class="KRSS FrmChuKaverRankHyoKRSS cboYMStart Enter Tab" style="width: 88px"
                                maxlength="6">
                        </td>
                        <td style="width: 30px"></td>
                        <td> ～ </td>
                        <td style="width: 30px"></td>
                        <td>
                            <input class="KRSS FrmChuKaverRankHyoKRSS cboYMEnd Enter Tab" style="width: 88px"
                                maxlength="6">
                        </td>
                    </tr>
                    <tr>
                        <td><label class="KRSS FrmChuKaverRankHyoKRSS label-snow" for="" style="width: 76px"> 順位</label>
                        </td>
                        <td style="width: 30px"></td>
                        <td>
                            <input class="KRSS FrmChuKaverRankHyoKRSS txtRank Enter Tab" style="width: 67px"
                                maxlength="3">
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </div>

    <div style="margin-top: 25px">
        <fieldset align="left">
            <legend>
                帳票種類
            </legend>

            <div style="margin-left: 35px;margin-top: 15px;margin-bottom: 15px">
                <input type="radio" name="rad" value="ランキング" class="KRSS FrmChuKaverRankHyoKRSS radRanking"
                    checked="checked" />
                ランキング

                <input type="radio" name="rad" value="ランキング(家賃を除く)" class="KRSS FrmChuKaverRankHyoKRSS radYachin"
                    style="margin-left: 60px" />
                ランキング(家賃を除く)

                <input type="radio" name="rad" value="部署別" class="KRSS FrmChuKaverRankHyoKRSS radBusyo"
                    style="margin-left: 60px" />
                部署別
            </div>
        </fieldset>
    </div>

    <div class="HMS-button-pane" style="margin-top: 20px">
        <div class="HMS-button-set">
            <button class="FrmChuKaverRankHyoKRSS cmdExcelOut">
                Excel出力
            </button>
            <!-- <button class="FrmChuKaverRankHyoKRSS cmdAction">
                印刷
            </button> -->
        </div>
    </div>

</div>