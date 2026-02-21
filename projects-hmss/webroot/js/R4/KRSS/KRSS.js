/**
 * KRSS
 * @alias  KRSS
 * @author FCSDL
 */

Namespace.register("KRSS.KRSS");

KRSS.KRSS = function()
{

	var me = new gdmz.base.panel();

	// ==========
	// = 宣言 start =
	// ==========

	// ========== 変数 start ==========

	me.id = "KRSS";
	me.sys_id = "KRSS";

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

		//$(".KRSS.KRSS-loading-icon").show();
		var frmId = "FrmKRSSMainMenu";
		var url = frmId;
		// + "/index";
		url = "KRSS" + "/" + url;
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
				$(".KRSS.KRSS-layout-west").html(result);
				$(".KRSS.KRSS-loading-icon").hide();
				$(".ui-widget-content.KRSS.KRSS-layout-west").css("overflow","scroll");
			}
		});
	};

	// ==========
	// = イベント end =
	// ==========

	// ==========
	// = メソッド start =
	// ==========

	// ==========
	// = メソッド end =
	// ==========

	return me;
};

$(function()
{
	o_KRSS_KRSS = new KRSS.KRSS();
	o_KRSS_KRSS.load();
});
