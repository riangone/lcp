<!-- /**
* 説明：
*
*
* @author fanzhengzhou
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
*       日付                   Feature/Bug                    内容                      担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmSonekiMeisai/FrmSonekiMeisai"));
?>

<!-- 画面個別の内容を表示 -->
<div class='KRSS FrmSonekiMeisai' id="KRSS_FrmSonekiMeisai" style="width: 100%;height: 100%">
    <div class='KRSS FrmSonekiMeisai R4-content'>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <table>
                <tr>
                    <td><label for="" class='KRSS FrmSonekiMeisai Label3 label-snow' style="width: 98px;"> 期 </label>
                    </td>
                    <td>
                        <input class='KRSS FrmSonekiMeisai lblKi' style="width: 57px;text-align: right"
                            disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <td><label for="" class='KRSS FrmSonekiMeisai Label1 label-snow' style="width: 98px"> 処理年月 </label>
                    </td>
                    <td>
                        <div class="KRSS FrmSonekiMeisai DIVcboKisyu" style="float: left">
                            <input class='KRSS FrmSonekiMeisai cboKisyu' style="width: 80px" maxlength="6">
                        </div>
                        <div style="float: left">
                            <label for="" class='KRSS FrmSonekiMeisai Label4' style="width: 50px;text-align:center"> ～
                            </label>
                        </div style="float: left">
                        <div>
                            <input class='KRSS FrmSonekiMeisai cboYM Enter Tab' style="width: 80px" maxlength="6">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><label for="" class="KRSS FrmSonekiMeisai Label10 label-snow" style="width: 98px"> 部署 </label>
                    </td>
                    <td>
                        <input class="KRSS FrmSonekiMeisai txtBusyoCDFrom Enter Tab" style="width: 58px;"
                            maxlength="3" />
                        <input class="KRSS FrmSonekiMeisai lblBusyoNMFrom" style="width: 332px" disabled="disabled" />
                        <label for="" class="KRSS FrmSonekiMeisai Label6" style="width: 40px;text-align: center"> ～
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="KRSS FrmSonekiMeisai txtBusyoCDTo Enter Tab" style="width: 58px" maxlength="3" />
                        <input class="KRSS FrmSonekiMeisai lblBusyoNMTo" style="width: 332px" disabled="disabled" />
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='KRSS FrmSonekiMeisai cmd003 Enter Tab'>
                    印刷
                </button>
            </div>
        </div>
    </div>
</div>