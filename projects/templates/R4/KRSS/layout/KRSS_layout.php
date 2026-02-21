<?php
	echo $this -> Html -> script(array('R4/KRSS/KRSS'));
	echo $this -> Html -> css(array('R4/KRSS/KRSS'));

	echo $this -> fetch('meta');
	echo $this -> fetch('css');
	echo $this -> fetch('script');
?>

<div class="ui-layout-center ui-layout-pane ui-layout-pane-center" style="position: absolute; margin: 0px; left: 210px; right: 210px; top: 34px; bottom: 34px; height: 470px; width: 1460px; z-index: 0; display: block; visibility: visible;">
	<div class="ui-widget-header ui-corner-top KRSS-ContentBar" id="mainTtl_KRSS">
		<?php  echo $KRSS_name; ?>
	</div>
	<div class="ui-widget-content KRSS KRSS-layout-center">

	</div>
</div>
<div class="ui-layout-west ui-layout-pane ui-layout-pane-west ui-layout-container">
	<div class="ui-layout-center ui-layout-pane ui-layout-pane-center" style="position: absolute; margin: 0px; top: 0px; bottom: auto; left: 0px; right: 0px; width: auto; z-index: 0; height: 100%; display: block; visibility: visible;">
		<div class="ui-widget-header ui-corner-top KRSS-MenuBar">
			メニュー
		</div>
		<div class="ui-layout-center ui-layout-pane ui-layout-pane-center">
			<div class="KRSS KRSS-loading-icon">
			</div>
		</div>
		<div class="ui-widget-content KRSS KRSS-layout-west">

		</div>
	</div>
</div>

