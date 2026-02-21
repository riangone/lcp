<!-- /**
* 説明：
*
*
* @author yinhuaiyu
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150923                  #2162                   BUG                         YIN
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmChuKaverRankHyo/FrmChuKaverRankHyo"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmChuKaverRankHyo  content R4-content">
    <div>
        <fieldset align="left">
            <legend>
                出力対象
            </legend>
            <div style="margin-left: 35px;margin-top: 15px;margin-bottom: 15px">
                <table>
                    <tr>
                        <td>
                            <label class="FrmChuKaverRankHyo Label1" for="">
                                処理年月
                            </label>
                        </td>
                        <td style="width: 30px">
                        </td>
                        <td>
                            <!-- 20150923 yin upd S -->
                            <!-- <input class="FrmChuKaverRankHyo cboYMStart Enter Tab" style="width: 120px" maxlength="7"> -->
                            <input class="FrmChuKaverRankHyo cboYMStart Enter Tab" style="width: 120px" maxlength="6">
                            <!-- 20150923 yin upd E -->
                        </td>
                        <td style="width: 30px">
                        </td>
                        <td>
                            ～
                        </td>
                        <td style="width: 30px">
                        </td>
                        <td>
                            <!-- 20150923 yin upd S -->
                            <!-- <input class="FrmChuKaverRankHyo cboYMEnd Enter Tab" style="width: 120px" maxlength="7"> -->
                            <input class="FrmChuKaverRankHyo cboYMEnd Enter Tab" style="width: 120px" maxlength="6">
                            <!-- 20150923 yin upd E -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            順位
                        </td>
                        <td style="width: 30px">
                        </td>
                        <td>
                            <input class="FrmChuKaverRankHyo txtRank Enter Tab" style="width: 100px" maxlength="3">
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
                <input type="radio" name="rad" value="ランキング" class="FrmChuKaverRankHyo radRanking Enter Tab"
                    checked="checked" />
                ランキング

                <input type="radio" name="rad" value="ランキング(家賃を除く)" class="FrmChuKaverRankHyo radYachin Enter Tab"
                    style="margin-left: 60px" />
                ランキング(家賃を除く)

                <input type="radio" name="rad" value="部署別" class="FrmChuKaverRankHyo radBusyo Enter Tab"
                    style="margin-left: 60px" />
                部署別
            </div>
        </fieldset>
    </div>

    <div class="HMS-button-pane" style="margin-top: 20px">
        <div class="HMS-button-set">
            <button class="FrmChuKaverRankHyo cmdExcelOut Enter Tab">
                Excel出力
            </button>
            <button class="FrmChuKaverRankHyo cmdAction Enter Tab">
                実行
            </button>
        </div>
    </div>

</div>
