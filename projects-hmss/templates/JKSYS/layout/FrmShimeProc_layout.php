<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmShimeProc/FrmShimeProc"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmShimeProc.set-margin {
        display: inline;
        margin-left: 100px;
    }

    input.FrmShimeProc.lblSyoriYM {
        text-align: right
    }

    .FrmShimeProc.lbl-sky-L {
        width: 101px;
    }
</style>
<div class='FrmShimeProc'>
    <div class='FrmShimeProc JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmShimeProc lbl-sky-L' for=""> 現在の処理年月 </label>
            <input class='FrmShimeProc lblSyoriYM' type="text" disabled="true" />
            <div class='FrmShimeProc set-margin HMS-button-pane'>
                <button class='FrmShimeProc btnUpdate  Enter Tab'>
                    実行
                </button>
            </div>
        </div>
    </div>
</div>
