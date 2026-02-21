<!-- /**
* 説明：
*
*
* @author yinhuaiyu
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug              内容                      担当
* YYYYMMDD               #ID                     XXXXXX                    FCSDL
* 20160511               #2436                   NEW                       YinHuaiyu
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>

<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmKeijouRiekiTree/FrmKeijouRiekiTree"));
?>

<div class='KRSS FrmKeijouRiekiTree' id="KRSS_FrmKeijouRiekiTree" style="width: 100%;height: 100%">
    <div class='KRSS FrmKeijouRiekiTree R4-content'>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <label class='KRSS FrmKeijouRiekiTree Label1 label-snow' for="" style=" width:82px;"> 処理年月 </label>
            <input class='KRSS FrmKeijouRiekiTree cboYM Enter Tab' style="width: 80px" maxlength="6">
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class="KRSS FrmKeijouRiekiTree cmdAction Enter Tab">
                    実行
                </button>
                <button class="KRSS FrmKeijouRiekiTree cmdBack Enter Tab">
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>