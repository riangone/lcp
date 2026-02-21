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
echo $this->Html->script(array("R4/KRSS/FrmGENRILISTKRSS/FrmGENRILISTKRSS"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div id="KRSS_FrmGENRILISTKRSS" class='KRSS FrmGENRILISTKRSS' style="width: 100%;height: 100%">
    <div class='KRSS FrmGENRILISTKRSS content R4-content'>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <table>
                <tr>
                    <td><label for="" class='KRSS FrmGENRILISTKRSS Label1 label-snow' style="width: 82px"> 処理年月 </label>
                    </td>
                    <td>
                        <input class='KRSS FrmGENRILISTKRSS cboYM Enter Tab' style="width: 80px" maxlength="6" />
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><label for="" class="KRSS FrmGENRILISTKRSS Label10 label-snow" style="width: 82px;"> 部署 </label>
                    </td>
                    <td>
                        <input class="KRSS FrmGENRILISTKRSS txtBusyoCDFrom Enter Tab" style="width: 59px"
                            maxlength="3" />
                    </td>
                    <td>
                        <input class="KRSS FrmGENRILISTKRSS lblBusyoNMFrom" style="width:308px" disabled="true" />
                    </td>
                    <td><label for="" class="KRSS FrmGENRILISTKRSS Label6"> ～ </label></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="KRSS FrmGENRILISTKRSS txtBusyoCDTo Enter Tab" style="width: 59px" maxlength="3" />
                    </td>
                    <td>
                        <input class="KRSS FrmGENRILISTKRSS lblBusyoNMTo" style="width:308px" disabled="true" />
                    </td>
                    <td></td>
                </tr>
            </table>
            <div>
                <label for="" class="KRSS FrmGENRILISTKRSS lblMessage"> 権限が設定されている部署が対象です。全社が設定されている場合は全ての部署が対象です。
                </label>
            </div>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='KRSS FrmGENRILISTKRSS cmd003 checklist Enter Tab'>
                    チェックリスト
                </button>
                <button class='KRSS FrmGENRILISTKRSS cmd003 ExcelOut Enter Tab'>
                    一覧表
                </button>
                <button class='KRSS FrmGENRILISTKRSS cancel Enter Tab'>
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>