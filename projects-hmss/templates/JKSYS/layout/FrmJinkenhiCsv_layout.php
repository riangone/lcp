<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmJinkenhiCsv/FrmJinkenhiCsv"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmJinkenhiCsv.txtHombuFutankin,
    .FrmJinkenhiCsv.txtSeibiFutankin {
        text-align: right
    }
</style>
<div class='FrmJinkenhiCsv'>
    <div class='FrmJinkenhiCsv JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmJinkenhiCsv Label1 lbl-sky-L' for="">対象年月 </label>
            <input type="text" class='FrmJinkenhiCsv dtpYM Enter Tab' maxlength="6" tabindex="1" />
        </div>
        <div>
            <label class='FrmJinkenhiCsv Label2 lbl-sky-L' for=""> 本部負担金
            </label>
            <input type="text" class='FrmJinkenhiCsv txtHombuFutankin Enter Tab' tabindex="2" maxlength="7" />
            (1人当り)
        </div>
        <div>
            <label class='FrmJinkenhiCsv Label3 lbl-sky-L' for=""> 整備負担金
            </label>
            <input type="text" class='FrmJinkenhiCsv txtSeibiFutankin Enter Tab' maxlength="7" tabindex="3" />
            (1人当り)
        </div>
        <div class="FrmJinkenhiCsv HMS-button-pane">
            <div class='FrmJinkenhiCsv HMS-button-set'>
                <button class='FrmJinkenhiCsv cmdCsv Enter Tab' tabindex="4">
                    CSV出力
                </button>
            </div>
        </div>
    </div>
</div>
