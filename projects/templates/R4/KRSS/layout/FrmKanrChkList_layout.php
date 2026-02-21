<!DOCTYPE html>
<!-- /**
* 説明：
*
*
* @author yushuangji
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
*       日付                   Feature/Bug                    内容                      担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmKanrChkList/FrmKanrChkList"));
?>

<!-- 画面個別の内容を表示 -->
<div class='KRSS FrmKanrChkList' id="KRSS_FrmKanrChkList_KRSS" style="width:100%;height:100%">
    <div class='KRSS FrmKanrChkList R4-content'>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <table>
                <tr>
                    <td><label for="" class='KRSS FrmKanrChkList Label3 label-snow' style="width: 85px;"> 期 </label>
                    </td>
                    <td>
                        <input class='KRSS FrmKanrChkList lblKi' style="width: 60px;text-align: right"
                            disabled="disabled">
                    </td>
                </tr>
                <tr>
                    <td><label for="" class='KRSS FrmKanrChkList Label1 label-snow' style="width: 85px"> 処理年月 </label>
                    </td>
                    <td>
                        <div style="float:left" class="KRSS FrmKanrChkList cboKisyu_block">
                            <input class='KRSS FrmKanrChkList cboKisyu Enter Tab' style="width: 80px" maxlength="6">
                        </div>
                        <div style="float:left">
                            <label for="" class='KRSS FrmKanrChkList Label4' style="width: 50px;text-align:center"> ～
                            </label>
                        </div>
                        <div style="float:left">
                            <input class='KRSS FrmKanrChkList cboYM Enter Tab' style="width: 80px" maxlength="6">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><label for="" class="KRSS FrmKanrChkList Label10 label-snow" style="width: 85px"> 科目コード </label>
                    </td>
                    <td>
                        <input class="KRSS FrmKanrChkList txtKamokuCDFrom Enter Tab" style="width: 59px;"
                            maxlength="5" />
                        <input class="KRSS FrmKanrChkList lblKamokuNMFrom" style="width: 333px" disabled="disabled" />
                        <label for="" class="KRSS FrmKanrChkList Label6" style="width: 40px;text-align: center"> ～
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input class="KRSS FrmKanrChkList txtKamokuCDTo Enter Tab" style="width: 59px" maxlength="5" />
                        <input class="KRSS FrmKanrChkList lblKamokuNMTo" style="width: 333px" disabled="disabled" />
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="KRSS FrmKanrChkList Excel Enter Tab">
                    Excel出力
                </button>
            </div>
        </div>
    </div>
</div>