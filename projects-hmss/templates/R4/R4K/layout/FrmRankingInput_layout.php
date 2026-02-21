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
echo $this->Html->script(array("R4/R4K/FrmRankingInput/FrmRankingInput"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmRankingInput R4-content">
    <div style="width: 780px">
        <div style="margin-left: 10px">
            <label for="">
                当月年月
            </label>
            <!-- 20150923 yin upd S -->
            <!-- <input  type="text" class="FrmRankingInput cboYM Enter Tab" style="width: 70px;" maxlength="7"> -->
            <input type="text" class="FrmRankingInput cboYM Enter Tab" style="width: 70px;" maxlength="6">
            <!-- 20150923 yin upd E -->
        </div>
        <div style="margin-left: 10px;margin-top: 10px">
            <table>
                <tr>
                    <td>
                        <fieldset>
                            <legend>
                                <b><span style="font-size: 10pt">本社除く人員</span></b>
                            </legend>
                            <table>
                                <tr>
                                    <td>
                                        全社
                                    </td>
                                    <td width="20px">

                                    </td>
                                    <td>
                                        <input type="text" class="FrmRankingInput txtZensya numeric Enter Tab"
                                            style="text-align: right" maxlength="6" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        本社
                                    </td>
                                    <td width="20px">

                                    </td>
                                    <td>
                                        <input type="text" class="FrmRankingInput txtHonsya numeric Enter Tab"
                                            style="text-align: right" maxlength="6" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        本社除く
                                    </td>
                                    <td width="20px">

                                    </td>
                                    <td>
                                        <input type="text" class="FrmRankingInput lblNozoku numeric Enter Tab"
                                            disabled="disabled" style="text-align: right" />
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                    <td width="30px">
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    新車売上台数
                                </td>
                                <td width="20px">

                                </td>
                                <td>
                                    <input type="text" class="FrmRankingInput txtSinUriDaisu numeric Enter Tab"
                                        style="text-align: right" maxlength="6" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    中古車売上台数
                                </td>
                                <td width="20px">

                                </td>
                                <td>
                                    <input type="text" class="FrmRankingInput txtChuUriDaisu numeric Enter Tab"
                                        style="text-align: right" maxlength="6" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    整備人員
                                </td>
                                <td width="20px">

                                </td>
                                <td>
                                    <input type="text" class="FrmRankingInput txtSeibiJinin numeric Enter Tab"
                                        style="text-align: right" maxlength="6" />
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td width="20px">

                                </td>
                                <td>
                                    <input type="text" class="FrmRankingInput txtCreDt"
                                        style="text-align: right;visibility: hidden" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
        <div style="margin-left: 10px;margin-top: 10px">
            <table id="FrmRankingInput_sprList">
            </table>

        </div>
        <div class="HMS-button-pane" align="right" style="margin-top: 20px;">

            <button class="FrmRankingInput cmdAction Enter Tab">
                登録
            </button>

            <button class="FrmRankingInput cmdDelete Enter Tab">
                削除
            </button>
        </div>
    </div>
</div>
