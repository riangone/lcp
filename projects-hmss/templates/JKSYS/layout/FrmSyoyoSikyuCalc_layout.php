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
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyoyoSikyuCalc/FrmSyoyoSikyuCalc"));
?>
<style type="text/css">
    .FrmSyoyoSikyuCalc.cmbYM {
        width: 100px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmSyoyoSikyuCalc">
    <div class="FrmSyoyoSikyuCalc JKSYS-content JKSYS-content-fixed-width">
        <div>
            <label class='FrmSyoyoSikyuCalc Label4 lbl-sky-L' for=""> 評価実施年月 </label>
            <select class="FrmSyoyoSikyuCalc cmbYM Tab Enter" maxlength="7" tabindex="1"></select>
        </div>
        <div>
            <label class='FrmSyoyoSikyuCalc Label2 lbl-sky-L' for=""> 評価対象期間 </label>
            <label class='FrmSyoyoSikyuCalc lblKikan' for="">2010/04/01 ～ 2010/09/30</label>
        </div>
        <div class="HMS-button-pane">
            <button class="FrmSyoyoSikyuCalc HMS-button-set cmdCsv Enter Tab" tabindex="2">
                CSV出力
            </button>
        </div>
    </div>
</div>
