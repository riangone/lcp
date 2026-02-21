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
echo $this->Html->script(array("R4/KRSS/FrmHonbuJisseki/FrmHonbuJisseki"));
?>

<div class='KRSS FrmHonbuJisseki' id="KRSS_FrmHonbuJisseki" style="width: 100%;height: 100%">
    <div class='KRSS FrmHonbuJisseki R4-content'>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <label for="" class='KRSS FrmHonbuJisseki Label1 label-snow' style=" width:82px;"> 処理年月 </label>
            <input class='KRSS FrmHonbuJisseki cboYM Enter Tab' style="width: 80px" maxlength="6">
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="KRSS FrmHonbuJisseki cmdAction Enter Tab">
                    印刷
                </button>
            </div>
        </div>
    </div>
</div>
