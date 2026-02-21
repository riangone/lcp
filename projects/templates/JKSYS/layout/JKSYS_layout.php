<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('JKSYS/JKSYS'));
echo $this->Html->css(array('JKSYS/JKSYS'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .JKSYS.JKSYS-layout-center div.FrmMainContainer {
        margin: 5px;
    }

    .JKSYS.JKSYS-layout-center .FrmMainContainer.lblErrMsg {
        color: red;
    }
</style>
<div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
    style="position: absolute; margin: 0px; left: 210px; right: 210px; top: 34px; bottom: 34px; height: 470px; width: 1460px; z-index: 0; display: block; visibility: visible;">
    <div class="ui-widget-header ui-corner-top JKSYS-ContentBar" id="mainTtl_JKSYS">
        <?php echo $app_name; ?>
    </div>
    <div class="ui-widget-content JKSYS JKSYS-layout-center">
        <fieldset>
            <legend>
                <b> <span>奉行データ取込状況</span> </b>
            </legend>
            <div class="FrmMainContainer">
                <label for="" class="FrmMainContainer lbl-sky-L">社員情報</label>
                <label for="" class="FrmMainContainer lblSyainDate">2010/07/01 10:10:10</label>
            </div>
            <div class="FrmMainContainer">
                <label for="" class="FrmMainContainer lbl-sky-L">給与・賞与情報</label>
                <label for="" class="FrmMainContainer lblKyuyoDate">2010/07月</label>
            </div>
            <div class="FrmMainContainer">
                <label for="" class="FrmMainContainer lbl-sky-L">評価情報</label>
                <label for="" class="FrmMainContainer lblHyoukaDate">2010/12月</label>
            </div>
        </fieldset>
        <div class="FrmMainContainer">
            <label for="" class="FrmMainContainer lblErrMsg"></label>
        </div>
    </div>
</div>
<div class="ui-layout-west ui-layout-pane ui-layout-pane-west ui-layout-container">
    <div class="ui-layout-center ui-layout-pane ui-layout-pane-center"
        style="position: absolute; margin: 0px; top: 0px; bottom: auto; left: 0px; right: 0px; width: auto; z-index: 0; height: 100%; display: block; visibility: visible;">
        <div class="ui-widget-header ui-corner-top JKSYS-MenuBar">
            メニュー
        </div>
        <div class="ui-layout-center ui-layout-pane ui-layout-pane-center">
            <div class="JKSYS JKSYS-loading-icon"></div>
        </div>
        <div class="ui-widget-content JKSYS JKSYS-layout-west">

        </div>
    </div>
</div>