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
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmTypeGen/FrmTypeGen"));
?>

<style type="text/css">
    .FrmTypeGen.dtpTaisyouKE {
        width: 100px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmTypeGen.dtpTaisyouKE {
            width: 80px;
        }
    }
</style>

<div class='FrmTypeGen'>
    <div class='FrmTypeGen JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmTypeGen lbl-sky-L' for=""> 評価最終年月 </label>
            <input type="text" class='FrmTypeGen dtpTaisyouKE Tab Enter' maxlength="6" tabindex="0"
                id="FrmTypeGen_dtpTaisyouKE" />
        </div>
        <div>
            <label class='FrmTypeGen lbl-sky-L' for=""> 支給予定日 </label>
            <input type="text" class='FrmTypeGen dtpShikyuYD Tab Enter' maxlength="10" tabindex="1"
                id="FrmTypeGen_dtpShikyuYD" />
        </div>
        <div class="FrmTypeGen HMS-button-pane">
            <div class='FrmTypeGen HMS-button-set'>
                <button class="FrmTypeGen cmdApply Enter Tab" tabindex="2">
                    生成
                </button>
            </div>
        </div>
    </div>
</div>