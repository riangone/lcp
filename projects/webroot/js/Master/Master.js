/**
 * Master
 * @alias  Master
 * @author FCSDL
 */

Namespace.register("HMSS.Master");

HMSS.Master = function()
{

	var me = new gdmz.base.panel();

	// ==========
	// = 宣言 start =
	// ==========

	// ========== 変数 start ==========

	me.id = "Master";
	me.sys_id = "Master";

	me.Main = null;
	me.Login = null;

	me.logined = false;

	// ========== 変数 end ==========

	// ========== コントロール start ==========

	var base_init_control = me.init_control;
	me.init_control = function()
	{
		base_init_control();
	};

	// ========== コントロース end ==========

	// ==========
	// = 宣言 end =
	// ==========

	// ==========
	// = イベント start =
	// ==========

	var base_load = me.load;
	me.load = function()
	{
		base_load();
		url = "";
		$.ajax(
		{
			type : "POST",
			url : url,
			data :
			{
				func : "check_login_state"
			},
			success : function(result)
			{
				if (result == "ng")
				{
					me.logined = false;

					var frmId = "Login";
					var url = "Login" + "/" + frmId;
					$.ajax(
					{
						type : "POST",
						url : url,
						data :
						{
							"url" : url
						},
						success : function(result)
						{
							$("body").html(result);
						}
					});
					return;
				}
				else
				{
					$("body").html(result);
					// $("#outer-center").css('top', '52px');
					// $("#outer-north").css('top', '-5px');
					// $("#outer-south").css('height', '12px');
					// $("#outer-center").css('bottom', '5px');
					// $("#outer-center").css('height', '648px');
					me.logined = true;
				}
			}
		});
	};

	//window.resizeTo('1300', '768');
	window.resizeTo('1366', '768');

	// ==========
	// = イベント end =
	// ==========

	// ==========
	// = メソッド start =
	// ==========

	me.load_Main = function(result)
	{
		$("body").html(result);
		// $("#outer-center").css('top', '52px');
		// $("#outer-north").css('top', '-5px');
		// $("#outer-south").css('height', '12px');
		// $("#outer-center").css('bottom', '5px');
		// $("#outer-center").css('height', '648px');
	};

	// ==========
	// = メソッド end =
	// ==========

	return me;
};

var pageLayoutOptions =
{
	name : 'pageLayout',
	resizeWithWindowDelay : 250,
	resizeWithWindowMaxDelay : 2000,
	resizable : false,
	slidable : false,
	closable : false,
	north__paneSelector : "#outer-north",
	center__paneSelector : "#outer-center",
	south__paneSelector : "#outer-south",
	south__spacing_open : 0,
	north__spacing_open : 0,
	north__size : "7%",
	south__size : "3%",
	center__children :
	{
		name : 'tabsContainerLayout',
		resizable : false,
		slidable : false,
		closable : false,
		north__paneSelector : "#tabbuttons",
		center__paneSelector : "#tabpanels",
		spacing_open : 0,
		center__onresize : $.layout.callbacks.resizeTabLayout // resize ALL visible layouts nested inside
	}
};

var sidebarLayoutOptions =
{
	name : 'sidebarLayout',
	showErrorMessages : false,
	resizeWhileDragging : true,
	north__size : "30%",
	south__size : "30%",
	minSize : 100,
	center__minHeight : 100,
	spacing_open : 10,
	spacing_closed : 10,
	contentSelector : ".ui-widget-content",
	togglerContent_open : '<div class="ui-icon"></div>',
	togglerContent_closed : '<div class="ui-icon"></div>'
};

var tabLayoutOptions =
{
	resizeWithWindow : false,
	resizeWhileDragging : true,
	resizerDragOpacity : 0.5,
	north__resizable : false,
	south__resizable : false,
	north__closable : false,
	south__closable : false,
	west__minSize : 200,  //課題管理表 002 li 20150702
	east__minSize : 200,
	center__minWidth : 400,
	spacing_open : 10,
	spacing_closed : 10,
	contentSelector : ".ui-widget-content",
	togglerContent_open : '<div class="ui-icon"></div>',
	togglerContent_closed : '<div class="ui-icon"></div>',
	triggerEventsOnLoad : true,
	center__onresize : $.layout.callbacks.resizePaneAccordions,
	west__onresize : $.layout.callbacks.resizePaneAccordions,
	west__children : sidebarLayoutOptions,
	east__children : sidebarLayoutOptions
};

//課題管理表 002 li 20150702	S
var tabLayoutOptionsKrss =
{
	resizeWithWindow : false,
	resizeWhileDragging : true,
	resizerDragOpacity : 0.5,
	north__resizable : false,
	south__resizable : false,
	north__closable : false,
	south__closable : false,
	west__minSize : 290,  //課題管理表 002 li 20150702
	east__minSize : 200,
	center__minWidth : 400,
	spacing_open : 10,
	spacing_closed : 10,
	contentSelector : ".ui-widget-content",
	togglerContent_open : '<div class="ui-icon"></div>',
	togglerContent_closed : '<div class="ui-icon"></div>',
	triggerEventsOnLoad : true,
	center__onresize : $.layout.callbacks.resizePaneAccordions,
	west__onresize : $.layout.callbacks.resizePaneAccordions,
	west__children : sidebarLayoutOptions,
	east__children : sidebarLayoutOptions
};
//課題管理表 002 li 20150702  E
//20220914 lujunxia ins s
//内部統制 指摘事項NO55:メニュー開閉の幅を広げる
var tabLayoutOptionsHMAUD =
{
	resizeWithWindow : false,
	resizeWhileDragging : true,
	resizerDragOpacity : 0.5,
	north__resizable : false,
	south__resizable : false,
	north__closable : false,
	south__closable : false,
	west__minSize : 200,
	east__minSize : 200,
	center__minWidth : 400,
	spacing_open : 20,
	spacing_closed : 20,
	contentSelector : ".ui-widget-content",
	togglerContent_open : '<div class="ui-icon" style="margin-left:2px;"></div>',
	togglerContent_closed : '<div class="ui-icon" style="margin-left:2px;"></div>',
	triggerEventsOnLoad : true,
	center__onresize : $.layout.callbacks.resizePaneAccordions,
	west__onresize : $.layout.callbacks.resizePaneAccordions,
	west__children : sidebarLayoutOptions,
	east__children : sidebarLayoutOptions
};
//20220914 lujunxia ins e
$(function()
{
	o_HMSS_Master = new HMSS.Master();
	o_HMSS_Master.load();
});
