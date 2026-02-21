<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmSinKaverRankHyoKRSS/FrmSinKaverRankHyoKRSS"));
?>

<!-- 画面個別の内容を表示 -->
<div class="KRSS FrmSinKaverRankHyoKRSS  R4-content">
    <div>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <div style="margin-left: 35px;margin-top: 15px;margin-bottom: 15px">
                <table>
                    <tr>
                        <td><label for="" class="KRSS FrmSinKaverRankHyoKRSS label-snow" style="min-width: 76px"> 処理年月
                            </label>
                        </td>
                        <td></td>
                        <td>
                            <input class="KRSS FrmSinKaverRankHyoKRSS cboYMStart Enter Tab" style="width: 94px"
                                maxlength="6">
                        </td>
                        <td style="width: 30px"></td>
                        <td> ～ </td>
                        <td style="width: 30px"></td>
                        <td>
                            <input class="KRSS FrmSinKaverRankHyoKRSS cboYMEnd Enter Tab" style="width: 94px"
                                maxlength="6">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="KRSS FrmSinKaverRankHyoKRSS label-snow"
                                style="min-width: 76px">順位</label>
                        </td>
                        <td></td>
                        <td>
                            <input class="KRSS FrmSinKaverRankHyoKRSS txtRank Enter Tab" style="width: 67px"
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
                <input type="radio" name="rad" value="ランキング" class="KRSS FrmSinKaverRankHyoKRSS radRanking"
                    checked="checked" />
                ランキング

                <input type="radio" name="rad" value="ランキング(家賃を除く)" class="KRSS FrmSinKaverRankHyoKRSS radYachin"
                    style="margin-left: 60px" />
                ランキング(家賃を除く)

                <input type="radio" name="rad" value="部署別" class="KRSS FrmSinKaverRankHyoKRSS radBusyo"
                    style="margin-left: 60px" />
                部署別
            </div>
        </fieldset>
    </div>

    <div class="HMS-button-pane" style="margin-top: 20px">
        <div class="HMS-button-set">
            <button class="KRSS FrmSinKaverRankHyoKRSS cmdExcelOut">
                Excel出力
            </button>
        </div>
    </div>

</div>
