<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM'));
//20170208 YIN INS S
echo $this->Html->script(array('PPRM/ODR_JScript'));
//20170208 YIN INS E
echo $this->Html->css(array('PPRM/PPRM'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
    style="position: absolute; margin: 0px; left: 210px; right: 210px; top: 34px; bottom: 34px; height: 470px; width: 1460px; z-index: 0; display: block; visibility: visible;">
    <div class="ui-widget-header ui-corner-top PPRM-ContentBar" id="mainTtl_PPRM">
        <?php echo $PPRM_name; ?>
    </div>
    <div class="ui-widget-content PPRM PPRM-layout-center">

    </div>
</div>
<div class="ui-layout-west ui-layout-pane ui-layout-pane-west ui-layout-container">
    <div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
        style="position: absolute; margin: 0px; top: 0px; bottom: auto; left: 0px; right: 0px; width: auto; z-index: 0; height: 100%; display: block; visibility: visible;">
        <div class="ui-widget-header ui-corner-top PPRM-MenuBar">
            メニュー
        </div>
        <div class="ui-layout-center ui-layout-pane ui-layout-pane-center">
            <div class="PPRM PPRM-loading-icon">
            </div>
        </div>
        <div class="ui-widget-content PPRM PPRM-layout-west">

        </div>
    </div>
</div>