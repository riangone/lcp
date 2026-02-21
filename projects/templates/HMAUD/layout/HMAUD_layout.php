<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUD'));
echo $this->Html->css(array('HMAUD/HMAUD'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .HMAUD.HMAUD-layout-center div.FrmMainContainer {
        margin: 5px;
    }

    .HMAUD.HMAUD-layout-center .FrmMainContainer.lblErrMsg {
        color: red;
    }
</style>
<div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
    style="position: absolute; margin: 0px; left: 210px; right: 210px; top: 34px; bottom: 34px; height: 470px; width: 1460px; z-index: 0; display: block; visibility: visible;">
    <div class="ui-widget-header ui-corner-top HMAUD-ContentBar" id="mainTtl_HMAUD">
        <?php echo $app_name; ?>
    </div>
    <div class="ui-widget-content HMAUD HMAUD-layout-center"></div>
</div>
<div class="ui-layout-west ui-layout-pane ui-layout-pane-west ui-layout-container">
    <div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
        style="position: absolute; margin: 0px; top: 0px; bottom: auto; left: 0px; right: 0px; width: auto; z-index: 0; height: 100%; display: block; visibility: visible;">
        <div class="ui-widget-header ui-corner-top HMAUD-MenuBar">
            メニュー
        </div>
        <div class="ui-layout-center ui-layout-pane ui-layout-pane-center">
            <div class="HMAUD HMAUD-loading-icon"></div>
        </div>
        <div class="ui-widget-content HMAUD HMAUD-layout-west">

        </div>
    </div>
</div>