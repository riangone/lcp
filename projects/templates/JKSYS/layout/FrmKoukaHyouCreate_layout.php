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
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmKoukaHyouCreate/FrmKoukaHyouCreate")); ?>

<style type="text/css">
    .FrmKoukaHyouCreate.cboKoukaType {
        width: 222px;
    }

    .FrmKoukaHyouCreate.txtSyainNo {
        width: 90px;
    }

    .FrmKoukaHyouCreate.dtpKikanEnd {
        width: 100px;
    }

    .FrmKoukaHyouCreate .pnlOutput {
        display: inline-block;
    }
</style>

<div class="FrmKoukaHyouCreate">
    <div class="FrmKoukaHyouCreate JKSYS-content JKSYS-content-fixed-width">
        <div>
            <label class="FrmKoukaHyouCreate lbl-sky-L" for=""> 作成者 </label>
            <input id="FrmKoukaHyouCreate_txtSyainNo" class="FrmKoukaHyouCreate txtSyainNo Enter Tab" maxlength="5"
                tabindex="1" />
            <span class="FrmKoukaHyouCreate lbl_SyainName"></span>
        </div>
        <div>
            <label class="FrmKoukaHyouCreate lbl-sky-L" for=""> 評価期間 </label>
            <input id="FrmKoukaHyouCreate_dtpKikanEnd" class="FrmKoukaHyouCreate dtpKikanEnd Enter Tab" maxlength="6"
                tabindex="3" />
            <div class="FrmKoukaHyouCreate pnlOutput">
                <input class="FrmKoukaHyouCreate rdo6kagetu Enter Tab" type="radio" name="pnlOutput" value="2"
                    tabindex="6" checked="true" disabled="true" />
                6ヶ月
            </div>
        </div>
        <div>
            <label class="FrmKoukaHyouCreate lbl-sky-L" for=""> 考課表タイプ </label>
            <!-- 20201120 YIN INS S -->
            <div class="FrmKoukaHyouCreate divcboKoukaType pnlOutput">
                <!-- 20201120 YIN INS E -->
                <select id="FrmKoukaHyouCreate_cboKoukaType" class="FrmKoukaHyouCreate cboKoukaType Enter Tab"
                    tabindex="10"></select>
            </div>
        </div>
        <div class="FrmKoukaHyouCreate HMS-button-pane">
            <button class="FrmKoukaHyouCreate cmdExcel HMS-button-set Enter Tab" tabindex="2">
                Excel出力
            </button>
        </div>
    </div>
</div>
