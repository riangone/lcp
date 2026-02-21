<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/APPM'));
echo $this->Html->css(array('APPM/APPM'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
    style="position: absolute; margin: 0px; left: 210px; right: 210px; top: 34px; bottom: 34px; height: 470px; width: 1460px; z-index: 0; display: block; visibility: visible;">
    <div class="ui-widget-header ui-corner-top APPM-ContentBar" id="mainTtl_APPM">
        <?php echo $app_name; ?>
    </div>
    <div class="ui-widget-content APPM APPM-layout-center">

    </div>
</div>
<div class="ui-layout-west ui-layout-pane ui-layout-pane-west ui-layout-container">
    <div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
        style="position: absolute; margin: 0px; top: 0px; bottom: auto; left: 0px; right: 0px; width: auto; z-index: 0; height: 100%; display: block; visibility: visible;">
        <div class="ui-widget-header ui-corner-top APPM-MenuBar">
            メニュー
        </div>
        <div class="ui-layout-center ui-layout-pane ui-layout-pane-center">
            <div class="APPM APPM-loading-icon">
            </div>
        </div>
        <div class="ui-widget-content APPM APPM-layout-west">

        </div>
    </div>
</div>