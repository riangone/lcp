<!--
/**
* 説明：
*
*
* @author FCS
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20150928           #2179                     BUG                            LI
* --------------------------------------------------------------------------------------------
*/
-->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmHMMainDataCreate/FrmHMMainDataCreate"));
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmHMMainDataCreate div[style*="font-size:12px"] {
            font-size: 10px !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmHMMainDataCreate">
    <div class="FrmHMMainDataCreate content R4-content">
        <table>
            <tr>
                <td colspan="2">
                    作成対象
                </td>
            </tr>
            <tr>
                <td width="50">

                </td>
                <td>
                    <input type="radio" class='FrmHMMainDataCreate radAll Enter Tab' name='FrmHMMainDataCreate_radio' />
                    全ての速報データを作成する
                    <div>
                        <label style='font-size:12px;margin-left:50px;' for="">
                            独自速報データ作成対象一覧内の全てのデータを作成します。
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="50">

                </td>
                <td>
                    <input type="radio" class='FrmHMMainDataCreate radKobetu Enter Tab' name='FrmHMMainDataCreate_radio'
                        style='margin-top:15px;' />
                    個別に速報データを作成する
                    <div>
                        <label style='font-size:12px;margin-left:50px;' for="">
                            データを作成する対象を下記から選択して下さい。
                        </label>
                    </div>
                    <fieldset style='width:300px;margin-top:10px;margin-bottom:10px;margin-left:50px;'>
                        <legend>
                            独自速報データ作成対象一覧
                        </legend>
                        <table>
                            <tr>
                                <td>
                                    <input type="checkbox" class='FrmHMMainDataCreate chkUriSoku Enter Tab'
                                        style='margin-bottom:5px;' />
                                    売上速報データ作成
                                    <br />
                                    <input type="checkbox" class='FrmHMMainDataCreate chkGenriSoku Enter Tab'
                                        style='margin-bottom:5px;' />
                                    限界利益速報データ作成
                                    <br />
                                    <input type="checkbox" class='FrmHMMainDataCreate chkKijyunSoku Enter Tab'
                                        style='margin-bottom:5px;' />
                                    基準会計速報データ作成
                                    <br />
                                    <input type="checkbox" class='FrmHMMainDataCreate chkKaikeiSoku Enter Tab'
                                        style='margin-bottom:5px;' />
                                    会計速報データ作成
                                    <br />
                                    <input type="checkbox" class='FrmHMMainDataCreate chkUriMain Enter Tab'
                                        style='margin-bottom:5px;' />
                                    売上メインデータ作成
                                    <br />
                                    <input type="checkbox" class='FrmHMMainDataCreate chkKaikeiMain Enter Tab'
                                        style='margin-bottom:5px;' />
                                    会計メインデータ作成
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    対象更新年月日

                    <input type='text' class='FrmHMMainDataCreate cboStartDate Enter Tab' />
                    ~
                    <input type='text' class='FrmHMMainDataCreate cboEndDate Enter Tab' />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class='FrmHMMainDataCreate div cboSyoriYM'>
                        <label class="FrmHMMainDataCreate lblSyoriYM" for="">
                            対象年月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </label>
                        <!-- 20150922 Yuanjh UPD S. -->
                        <!-- <input type='text' class='FrmHMMainDataCreate cboSyoriYM'/> -->
                        <input type='text' class='FrmHMMainDataCreate cboSyoriYM Enter Tab' maxlength="6" />
                        <!-- 20150922 Yuanjh UPD E. -->
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label style='margin-top:10px;' for="">
                        ※サーバーに負荷がかかりますので、この画面は夜間バッチが起動しなかった場合のみ使用してください。
                    </label>
                </td>
            </tr>
        </table>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmHMMainDataCreate cmdAct Enter Tab'>
                    実行
                </button>
            </div>
        </div>
        <label style='border:solid 0px;float:left;color:#0000CD' class='FrmHMMainDataCreate lblMsg' for="">
        </label>
    </div>
</div>
<div id="tmpDealMsgDialog">
</div>
