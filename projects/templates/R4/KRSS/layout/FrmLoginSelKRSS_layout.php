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
* 日付                   Feature/Bug                    内容                      担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20160527			  	#2529						依頼						Yinhuaiyu
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmLoginSelKRSS/FrmLoginSelKRSS"));
?>

<style>
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmLoginSelKRSS.searchGroup {
            width: 98% !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div id="FrmLoginSelKRSS" class='FrmLoginSelKRSS KRSS'>
    <div class="FrmLoginSelKRSS searchArea KRSS">
        <fieldset class="FrmLoginSelKRSS searchGroup KRSS" style="width: 100%">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0" width="100%">
                <tr>
                    <td>
                        <label class='FrmLoginSelKRSS KRSS' for="" style="min-width: 120px;margin-top:5px;">
                            システム区分
                        </label>
                        <select class="FrmLoginSelKRSS cboSysKB Enter Tab KRSS"
                            style="margin-top:5px;width: 168px;margin-left:2px">
                        </select>
                        <br />
                        <label class='FrmLoginSelKRSS  KRSS' for="" style="margin-top:5px;min-width: 120px">
                            ユーザＩＤ
                        </label>
                        <input class="FrmLoginSelKRSS UcUserID Enter Tab KRSS" style='margin-top:5px;width:84px' />
                        <br />
                        <label class='FrmLoginSelKRSS  KRSS' for="" style="margin-top:5px;min-width: 120px">
                            所属ＩＤ
                        </label>
                        <select class="FrmLoginSelKRSS UcComboBox1 Enter Tab KRSS"
                            style="margin-top:5px;width: 168px;margin-left:2px">
                            <option></option>
                        </select>
                    </td>
                    <td valign="middle" align="right">
                        <button class='KRSS FrmLoginSelKRSS Button1 Tab Enter ' style='margin-right:20px;'>
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class='FrmLoginSelKRSS listArea KRSS'>
        <table style="width: 100%">
            <tr>
                <td>
                    <table id='FrmLoginSelKRSS_sprList'>
                    </table>
                </td>
            </tr>
        </table>
        <td align="right">
            <div class="HMS-button-pane">
                <div class='HMS-button-set'>
                    <button class='FrmLoginSelKRSS Button3 Tab Enter KRSS'>
                        入力
                    </button>
                </div>
            </div>
            </table>
    </div>
    <div id="FrmLoginSelKRSS_SubDialog">
    </div>
</div>