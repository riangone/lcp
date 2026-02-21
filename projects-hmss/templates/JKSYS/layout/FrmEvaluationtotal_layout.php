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
* 20201120           bug                       IE下考課表タイプ 无法选择问题修正    YIN
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmEvaluationtotal/FrmEvaluationtotal")); ?>
<style type="text/css">
    .FrmEvaluationtotal.cboKoukaType {
        width: 222px;
    }

    .FrmEvaluationtotal .divcboKoukaType {
        display: inline-block;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmEvaluationtotal.cboKoukaType {
            width: 172px;
        }
    }
</style>
<div class="FrmEvaluationtotal">
    <div class="FrmEvaluationtotal JKSYS-content JKSYS-content-fixed-width">
        <div>
            <label class="FrmEvaluationtotal lbl-sky-L" for="">評価期間</label>
            <input type="text" class='FrmEvaluationtotal dtpTaisyouKE Tab Enter' maxlength="6" tabindex="0" />
            <input type="radio" value="1" name="pnlKIKAN" class="FrmEvaluationtotal rdoBoth Enter Tab" hidden />
            <input type="radio" value="2" name="pnlKIKAN" class="FrmEvaluationtotal rdo6Months Enter Tab"
                checked="checked" disabled="true" tabindex="2" />
            6ヶ月
            <input type="radio" value="3" name="pnlKIKAN" class="FrmEvaluationtotal rdo1year Enter Tab" hidden />
        </div>
        <div>
            <label class="FrmEvaluationtotal lbl-sky-L" for="">考課表タイプ</label>
            <!-- 20201120 YIN INS S -->
            <div class="FrmEvaluationtotal divcboKoukaType">
                <!-- 20201120 YIN INS E -->
                <select class="FrmEvaluationtotal cboKoukaType Tab Enter" tabindex="20"></select>
            </div>
        </div>
        <div>
            <label class="FrmEvaluationtotal lbl-sky-L" for="">順位設定単位</label>
            <input type="radio" value="1" name="Panel2" class="FrmEvaluationtotal rdoExct_Type Enter Tab"
                checked="checked" tabindex="1" />
            考課表タイプ別
            <input type="radio" value="2" name="Panel2" class="FrmEvaluationtotal rdoExct_Grop Enter Tab"
                tabindex="1" />
            グループ別
        </div>
        <div class="FrmEvaluationtotal HMS-button-pane">
            <div class="FrmEvaluationtotal HMS-button-set">
                <button class="FrmEvaluationtotal cmdApply Enter Tab" tabindex="13">
                    集計
                </button>
                <button class="FrmEvaluationtotal Button1 Enter Tab" tabindex="14">
                    達成率更新
                </button>
                <button class="FrmEvaluationtotal cmdReApply Enter Tab" tabindex="15">
                    順位再設定
                </button>
            </div>
        </div>
    </div>
</div>