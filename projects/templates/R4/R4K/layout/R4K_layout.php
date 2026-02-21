<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/R4K'));
echo $this->Html->css(array('R4/R4K/R4K'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
    style="position: absolute; margin: 0px; left: 210px; right: 210px; top: 34px; bottom: 34px; height: 470px; width: 1460px; z-index: 0; display: block; visibility: visible;">
    <div class="ui-widget-header ui-corner-top R4K-ContentBar" id="mainTtl_R4K">
        <?php
        echo $r4_name;
        ?>
    </div>
    <div class="ui-widget-content R4K R4K-layout-center">

    </div>
</div>
<div class="ui-layout-west ui-layout-pane ui-layout-pane-west ui-layout-container">
    <div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
        style="position: absolute; margin: 0px; top: 0px; bottom: auto; left: 0px; right: 0px; width: auto; z-index: 0; height: 100%; display: block; visibility: visible;">
        <div class="ui-widget-header ui-corner-top R4K-MenuBar">
            メニュー
        </div>
        <div class="ui-layout-center ui-layout-pane ui-layout-pane-center">
            <div class="R4K R4K-loading-icon">
            </div>
        </div>
        <div class="ui-widget-content R4K R4K-layout-west">

        </div>
    </div>
</div>