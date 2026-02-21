<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('R4/R4G/FrmOkaiageMst/FrmOkaiageMst'));
echo $this->Html->css(array('R4/R4G/FrmOkaiageMst/FrmOkaiageMst'));
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='frmOkaiageMst widthheight'>
    <!--20171206 CIYUANCHEN UPD S -->
    <!-- <div class='frmOkaiageMst listArea' >-->
    <div class='frmOkaiageMst listArea' style="overflow-x:scroll; overflow-y:hidden">
        <!--20171206 CIYUANCHEN UPD E -->
        <table id='frmOkaiageMst_sprList'>
        </table>
        <div id='divFrmOkaiageMst_pager'>
        </div>
    </div>
    <!-- <div class='FrmOkaiageMst bottombut'> -->
    <div class="HMS-button-pane">
        <div class='HMS-button-set'>
            <button class='FrmOkaiageMst button_action Tab'>
                更新
            </button>
        </div>
    </div>
    <!-- </div> -->
</div>