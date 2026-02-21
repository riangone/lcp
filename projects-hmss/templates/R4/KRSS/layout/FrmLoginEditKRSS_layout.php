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
* 日付                   Feature/Bug                 内容                      担当
* YYYYMMDD               #ID                     XXXXXX                      FCSDL
* 20160527			  	#2529						依頼						Yinhuaiyu
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmLoginEditKRSS/FrmLoginEditKRSS"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmLoginEditKRSS" class='FrmLoginEdit KRSS'>
    <div class="FrmLoginEditKRSS searchArea KRSS">
        <label class='FrmLoginEditKRSS lbl-blue KRSS' for="" style="min-width: 100px">
            システム区分
        </label>
        <select class="FrmLoginEditKRSS cboSysKB Enter Tab KRSS" style="width: 168px;margin-left:2px;margin-top:5px;">
        </select>
        <br />
        <label class='FrmLoginEditKRSS lbl-blue KRSS' for="" style="min-width: 100px ;margin-top:5px;">
            ユーザＩＤ
        </label>
        <input id="FrmLoginEditKRSS_UcUserID" class="FrmLoginEditKRSS UcUserID Enter Tab KRSS"
            style='width:55px;margin-top:5px;' />
        <input class="FrmLoginEditKRSS UcUserNM Enter Tab KRSS" style='width:184px;margin-top:5px;' />
        <br />
        <form>
            <label class='FrmLoginEditKRSS lbl-blue KRSS' for="" style="min-width: 100px;margin-top:5px;">
                パスワード
            </label>
            <input type='password' class="FrmLoginEditKRSS password Enter Tab KRSS"
                style='width:104px;margin-top:5px;' />

            <br />
            <label class='FrmLoginEditKRSS lbl-blue KRSS' for="" style="min-width: 100px;margin-top:5px;">
                パスワード確認
            </label>
            <input type='password' class="FrmLoginEditKRSS rePassword Enter Tab KRSS"
                style='width:104px;margin-top:5px;' />
            <br />
        </form>
        <label class='FrmLoginEditKRSS lbl-blue KRSS' for="" style="min-width: 100px;margin-top:5px;">
            所属ＩＤ
        </label>
        <select class="FrmLoginEditKRSS UcComboBox1 Enter Tab KRSS"
            style="width: 168px;margin-left:2px;margin-top:5px;">
            <option></option>
        </select>
        <br />
        <label class='FrmLoginEditKRSS lbl-blue KRSS' for="" style="min-width: 100px;margin-top:5px;">
            パターンＩＤ
        </label>
        <select class="FrmLoginEditKRSS UcComboBox2 Enter Tab KRSS"
            style="width: 168px;margin-left:2px;margin-top:5px;">
            <option></option>
        </select>
        <!--<input type="text" class="FrmLoginEditKRSS label9 Enter Tab KRSS" />-->
    </div>

    <div class='FrmLoginEditKRSS listArea KRSS'>
        <table width=100%>
            <tr>
                <td>
                    <table id='FrmLoginEditKRSS_sprList'>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="HMS-button-pane KRSS">
                        <div class='HMS-button-set KRSS'>
                            <button class='FrmLoginEditKRSS Button3 Tab Enter'>
                                登録
                            </button>
                            <button class='FrmLoginEditKRSS Button2 Tab Enter'>
                                戻る
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>