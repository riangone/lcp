/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240326    		受入検証.xlsx NO2     					車種を追加してください             		 LHB
 * 20240611    		202406_データ集計システム_CX-80追加        CX-80追加            		 		 LHB
 * 20240712    		CX-80追加判断                           CX-80追加判断            		 	     LHB
 * 20240806         20240806_HMTVE(PHP)グリッド高さ調整.xlsx                         caina
 * 20251118         202511_データ集計システム_機能追加要望   目標と実績＿改修イメージ                YIN
 * -------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE260TargetResultList");

HMTVE.HMTVE260TargetResultList = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.HMTVE = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE260TargetResultList";
    me.ActiveViewIndex = 0;
    me.Excelbtn = "";
    // 20240712 LHB INS S
    me.hidden = false;
    // 20240712 LHB INS S

    // jqgrid
    me.grid_id = "#HMTVE260TargetResultList_tblMain";
    me.s_grid_id = "#HMTVE260TargetResultList_tblSyasyu";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.option = {
        rownumbers: true,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };
    me.colModel = [
        {
            name: "BUSYO_RYKNM",
            classes: "BUSYO260_CELL_TITLE_GREEN_C",
            label: "部署",
            index: "BUSYO_RYKNM",
            width: 90,
            align: "center",
            sortable: false,
            frozen: true,
        },
        {
            name: "GENRI_MOKUHYO",
            label: "目<br />標",
            classes: "CELL_TITLE_GREEN_C",
            index: "GENRI_MOKUHYO",
            width: 45,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "GENRI_YOSOU",
            label: "月<br />末<br />予<br />想",
            index: "GENRI_YOSOU",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "GENRI_SABUN",
            label: "月<br />末<br />予<br />想",
            index: "GENRI_SABUN",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "GENRI_JISSEKI",
            label: "月<br />末<br />予<br />想",
            index: "GENRI_JISSEKI",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "URIMOKU_MAIN",
            label: "メ<br />イ<br />ン<br />権",
            index: "URIMOKU_MAIN",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
        },
        {
            name: "URIMOKU_TACHANEL",
            label: "他<br />チ<br />ャ<br />ネ<br />ル",
            index: "URIMOKU_TACHANEL",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
        },
        {
            name: "URIYOSOU_MAIN_Y",
            label: "メ<br />イ<br />ン<br />権",
            index: "URIYOSOU_MAIN_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "URIYOSOU_MAIN_S",
            label: "メ<br />イ<br />ン<br />権",
            index: "URIYOSOU_MAIN_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "URIYOSOU_KEI_Y",
            label: "軽<br />自<br />動<br />車",
            index: "URIYOSOU_KEI_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "URIYOSOU_KEI_S",
            label: "軽<br />自<br />動<br />車",
            index: "URIYOSOU_KEI_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "URIYOSOU_VOLVO_SONOTA_Y",
            label: "ボ<br />ル<br />ボ<br />他",
            index: "URIYOSOU_VOLVO_SONOTA_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "URIYOSOU_VOLVO_SONOTA_S",
            label: "ボ<br />ル<br />ボ<br />他",
            index: "URIYOSOU_VOLVO_SONOTA_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "URI_GK_Y",
            label: "売<br />上<br />台<br />数<br />計",
            index: "URI_GK_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "URI_GK_S",
            label: "売<br />上<br />台<br />数<br />計",
            index: "URI_GK_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "TRKDAISU_FUKUSHI_Y",
            label: "福<br />　<br />祉",
            index: "TRKDAISU_FUKUSHI_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "TRKDAISU_FUKUSHI_S",
            label: "福<br />　<br />祉",
            index: "TRKDAISU_FUKUSHI_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "TRKDAISU_TAJI_Y",
            label: "他<br />　<br />自",
            index: "TRKDAISU_TAJI_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "TRKDAISU_TAJI_S",
            label: "他<br />　<br />自",
            index: "TRKDAISU_TAJI_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "TRKDAISU_JITA_Y",
            label: "自<br />　<br />他",
            index: "TRKDAISU_JITA_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "TRKDAISU_JITA_S",
            label: "自<br />　<br />他",
            index: "TRKDAISU_JITA_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "TRK_GK_Y",
            label: "登<br />録<br />台<br />数<br />計",
            index: "TRK_GK_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "TRK_GK_S",
            label: "登<br />録<br />台<br />数<br />計",
            index: "TRK_GK_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "KEI_TRK_DAISU_Y",
            label: "登<br />録<br />台<br />数<br />計",
            index: "KEI_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "KEI_TRK_DAISU_S",
            label: "登<br />録<br />台<br />数<br />計",
            index: "KEI_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "TRKDAISU_KEI_TAJI",
            label: "他<br />　<br />自",
            index: "TRKDAISU_KEI_TAJI",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "TRKDAISU_KEI_JITA",
            label: "自<br />　<br />他",
            index: "TRKDAISU_KEI_JITA",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "TRKDAISU_KEI_FUKUSHI",
            label: "福<br />　<br />祉",
            index: "TRKDAISU_KEI_FUKUSHI",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "URIYOSOU_CHUKO_CHOKU_Y",
            label: "直<br />　<br />売",
            index: "URIYOSOU_CHUKO_CHOKU_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "URIYOSOU_CHUKO_CHOKU_S",
            label: "直<br />　<br />売",
            index: "URIYOSOU_CHUKO_CHOKU_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "URIYOSOU_CHUKO_GYOBAI_Y",
            label: "業<br />　<br />売",
            index: "URIYOSOU_CHUKO_GYOBAI_Y",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "URIYOSOU_CHUKO_GYOBAI_S",
            label: "業<br />　<br />売",
            index: "URIYOSOU_CHUKO_GYOBAI_S",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "SHURI_HOKEN",
            label: "自<br />動<br />車<br />保<br />険<br />料",
            index: "SHURI_HOKEN",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "SHURI_LEASE",
            label: "再<br />リ<br />｜<br />ス",
            index: "SHURI_LEASE",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "SHURI_LOAN",
            label: "ロ<br />｜<br />ン<br />Ｋ<br />Ｂ",
            index: "SHURI_LOAN",
            classes: "CELL_TITLE_GREEN_C",
            width: 45,
            align: "right",
            sortable: false,
        },
        {
            name: "SHURI_KIBOU",
            label: "希<br />望<br />No",
            index: "SHURI_KIBOU",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "SHURI_P753",
            label: "延<br />長<br />保<br />証",
            index: "SHURI_P753",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "SHURI_PMENTE",
            label: "Ｐ<br />メ<br />ン<br />テ",
            index: "SHURI_PMENTE",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "SHURI_BODYCOAT",
            label: "ボ<br />デ<br />ィ<br />｜<br />コ<br />｜<br />ト",
            index: "SHURI_BODYCOAT",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "SHURI_JAF",
            label: "Ｊ<br />Ａ<br />Ｆ<br />加<br />入",
            index: "SHURI_JAF",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "SHURI_OSS",
            label: "Ｏ<br />Ｓ<br />Ｓ",
            index: "SHURI_OSS",
            classes: "CELL_TITLE_GREEN_C",
            width: 35,
            align: "right",
            sortable: false,
        },
    ];

    me.s_colModel = [
        {
            name: "BUSYO_RYKNM",
            classes: "BUSYO260_CELL_TITLE_GREEN_C",
            label: "部署",
            index: "BUSYO_RYKNM",
            width: 90,
            align: "center",
            sortable: false,
            frozen: true,
        },
        {
            name: "DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "登<br/>録<br/>台<br/>数<br/>計",
            index: "DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "登<br/>録<br/>台<br/>数<br/>計",
            index: "DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "DEMIO_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "デ<br/>ミ<br/>オ",
            index: "DEMIO_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "DEMIO_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "デ<br/>ミ<br/>オ",
            index: "DEMIO_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "M2G_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>２<br/>",
            index: "M2G_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "M2G_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>２<br/>",
            index: "M2G_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "CX3_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "C<br/>X<br/>|<br/>3",
            index: "CX3_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "CX3_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "C<br/>X<br/>|<br/>3",
            index: "CX3_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "CX5_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "C<br/>X<br/>｜<br/>5",
            index: "CX5_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "CX5_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "C<br/>X<br/>｜<br/>5",
            index: "CX5_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "CX8_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｃ<br/>Ｘ<br/>｜<br/>８",
            index: "CX8_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "CX8_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｃ<br/>Ｘ<br/>｜<br/>８",
            index: "CX8_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "CX30_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｃ<br/>Ｘ<br/>｜<br/>３<br/>０<br/>",
            index: "CX30_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "CX30_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｃ<br/>Ｘ<br/>｜<br/>３<br/>０<br/>",
            index: "CX30_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "MX30_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ｘ<br/>｜<br/>３<br/>０<br/>",
            index: "MX30_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "MX30_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ｘ<br/>｜<br/>３<br/>０<br/>",
            index: "MX30_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        // 20240326 LHB INS S
        {
            name: "CX60_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｃ<br/>Ｘ<br/>｜<br/>６<br/>０<br/>",
            index: "CX60_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "CX60_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｃ<br/>Ｘ<br/>｜<br/>６<br/>０<br/>",
            index: "CX60_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB DEL S
        // {
        // 	name: 'CX80_TRK_DAISU_Y',
        // 	classes: 'CELL_TITLE_GREEN_C',
        // 	label: 'Ｃ<br/>Ｘ<br/>｜<br/>８<br/>０<br/>',
        // 	index: 'CX80_TRK_DAISU_Y',
        // 	width: 35,
        // 	align: 'right',
        // 	sortable: false,
        // },
        // {
        // 	name: 'CX80_TRK_DAISU_S',
        // 	classes: 'CELL_TITLE_GREEN_C',
        // 	label: 'Ｃ<br/>Ｘ<br/>｜<br/>８<br/>０<br/>',
        // 	index: 'CX80_TRK_DAISU_S',
        // 	width: 35,
        // 	align: 'right',
        // 	sortable: false,
        // 	cellattr: function (rowId, value, rowObject, colModel, arrData) {
        // 		return ' style=\"color:blue\"';
        // 	},
        // },
        // 20240712 LHB DEL E
        // 20240611 LHB INS E
        {
            name: "M3S_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>３<br/>・<br/>Ｓ<br/>",
            index: "M3S_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "M3S_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>３<br/>・<br/>Ｓ<br/>",
            index: "M3S_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "M3H_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>３<br/>・<br/>Ｆ<br/>",
            index: "M3H_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "M3H_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>３<br/>・<br/>Ｆ<br/>",
            index: "M3H_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "M6S_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>６<br/>・<br/>Ｓ<br/>",
            index: "M6S_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "M6S_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>６<br/>・<br/>Ｓ<br/>",
            index: "M6S_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "M6W_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>６<br/>・<br/>Ｗ<br/>",
            index: "M6W_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "M6W_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "Ｍ<br/>Ａ<br/>Ｚ<br/>Ｄ<br/>Ａ<br/>６<br/>・<br/>Ｗ<br/>",
            index: "M6W_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "ATENZA_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "ア<br/>テ<br/>ン<br/>ザ",
            index: "ATENZA_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "ATENZA_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "ア<br/>テ<br/>ン<br/>ザ",
            index: "ATENZA_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "AXS_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "ア<br/>ク<br/>セ<br/>ラ",
            index: "AXS_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "AXS_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "ア<br/>ク<br/>セ<br/>ラ",
            index: "AXS_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "LDSTAR_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "ロ<br/>｜<br/>ド<br/>ス<br/>タ<br/>｜",
            index: "LDSTAR_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "LDSTAR_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "ロ<br/>｜<br/>ド<br/>ス<br/>タ<br/>｜",
            index: "LDSTAR_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "FMV_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "フ<br/>ァ<br/>ミ<br/>リ<br/>ア<br/>バ<br/>ン",
            index: "FMV_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "FMV_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "フ<br/>ァ<br/>ミ<br/>リ<br/>ア<br/>バ<br/>ン",
            index: "FMV_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "BONGO_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "ボ<br/>ン<br/>ゴ<br/>／<br/>ブ<br/>ロ<br/>｜<br/>ニ<br/>ィ",
            index: "BONGO_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "BONGO_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "ボ<br/>ン<br/>ゴ<br/>／<br/>ブ<br/>ロ<br/>｜<br/>ニ<br/>ィ",
            index: "BONGO_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "TT_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "タ<br/>イ<br/>タ<br/>ン",
            index: "TT_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "TT_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "タ<br/>イ<br/>タ<br/>ン",
            index: "TT_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
        {
            name: "KEI_TRK_DAISU_Y",
            classes: "CELL_TITLE_GREEN_C",
            label: "軽<br/>自<br/>動<br/>車",
            index: "KEI_TRK_DAISU_Y",
            width: 35,
            align: "right",
            sortable: false,
        },
        {
            name: "KEI_TRK_DAISU_S",
            classes: "CELL_TITLE_GREEN_C",
            label: "軽<br/>自<br/>動<br/>車",
            index: "KEI_TRK_DAISU_S",
            width: 35,
            align: "right",
            sortable: false,
            cellattr: function () {
                return ' style="color:blue"';
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE260TargetResultList.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMTVE.TabKeyDown();

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //表示ボタンクリック
    $(".HMTVE260TargetResultList.btnETSearch").click(function () {
        if (me.checkNull() == false) {
            return;
        }
        me.btnETSearch_Click();
    });
    //登録ボタンクリック
    $(".HMTVE260TargetResultList.btnLogin").click(function () {
        me.openPageExbSearch();
    });
    //登録ボタンクリック
    $(".HMTVE260TargetResultList.btnToLogin").click(function () {
        me.openPageExbSearch();
    });
    //登録ボタンクリック
    $(".HMTVE260TargetResultList.btnMend").click(function () {
        me.openPageExbSearch();
    });
    //車種内訳画面へボタンクリック
    $(".HMTVE260TargetResultList.btnToCar").click(function () {
        me.btnToCar_Click();
    });
    //車種内訳画面へボタンクリック
    $(".HMTVE260TargetResultList.btnSee").click(function () {
        me.btnSee_Click();
    });
    $(".HMTVE260TargetResultList.txtbDuring").numeric({
        decimal: false,
        negative: false,
    });
    //中間会議資料出力ボタンクリック
    $(".HMTVE260TargetResultList.btnExChukan").click(function () {
        me.Excelbtn = "btnExChukan";
        me.MesExcel();
    });
    //月初会議資料出力ボタンクリック
    $(".HMTVE260TargetResultList.btnExGessho").click(function () {
        me.Excelbtn = "btnExGessho";
        me.MesExcel();
    });
    // $('.ui-layout-toggler-closed.ui-layout-toggler-west-closed').click(function()
    // {
    // setTimeout(function()
    // {
    // gdmz.common.jqgrid.set_grid_width(me.grid_id, $(".HMTVE260TargetResultList fieldset").width());
    // }, 500);
    // });
    $(".ui-layout-toggler-open.ui-layout-toggler-west-open").click(function () {
        setTimeout(function () {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMTVE260TargetResultList fieldset").width()
            );
            $("th[colspan]").each(function () {
                $(this)
                    .addClass(
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C"
                    )
                    .css("background", "#006600")
                    .css("color", "#FFFFFF");
            });
            me.gridStyleReset();
            gdmz.common.jqgrid.set_grid_width(
                me.s_grid_id,
                $(".HMTVE260TargetResultList fieldset").width()
            );
            $(
                ".HMTVE260TargetResultList .View2 .ui-state-default.ui-th-column-header.ui-th-ltr"
            )
                .css("background", "#006600")
                .css("color", "#FFFFFF");
            me.sgridStyleReset();
        }, 500);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：init_control
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };

    // **********************************************************************
    // 処 理 名：ページロード
    // 関 数 名：Page_Load
    // 戻 り 値：なし
    // 処理説明：ページ初期化
    // **********************************************************************
    me.Page_Load = function () {
        if (
            gdmz.SessionPatternID !== me.HMTVE.CONST_ADMIN_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_HONBU_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_MANAGER_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_TESTER_PTN_NO
        ) {
            $(".HMTVE260TargetResultList.btnETSearch").hide();
        }
        var url = me.sys_id + "/" + me.id + "/fncPageLoad";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                $(
                    ".HMTVE260TargetResultList.btnLogin , .HMTVE260TargetResultList.btnETSearch"
                ).button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            $(
                ".HMTVE260TargetResultList.btnLogin , .HMTVE260TargetResultList.btnETSearch"
            ).button("enable");
            var data = result["data"];
            var sysDate = data["sysDate"];
            var sysDates = sysDate.split("-");
            var sysYear = parseInt(sysDates[0]);
            var sysMonth = parseInt(sysDates[1]);
            // 20240712 LHB INS S
            me.hidden = result["isExit"] == "0" ? true : false;
            // 20240712 LHB INS E
            $(".HMTVE260TargetResultList.txtbDuring").val(sysYear);
            $(".HMTVE260TargetResultList.lblShopname").val(data["lblShopname"]);
            for (var i = 1; i <= 12; i++) {
                $("<option></option>")
                    .val(i)
                    .text(i.toString().padLeft(2, "0"))
                    .appendTo(".HMTVE260TargetResultList.ddlMonth");
            }
            $(".HMTVE260TargetResultList.ddlMonth").val(sysMonth);
            me.gridSyasyuInit();
        };
        me.ajax.send(url, "", 0);
        me.gridInit();
        me.subSearchButtonEnable(false);
        $(".HMTVE260TargetResultList.txtbDuring").trigger("focus");
    };
    //目標と実績分類grid初期化
    me.gridInit = function () {
        $(me.grid_id).jqGrid({
            datatype: "local",
            emptyRecordRow: false,
            caption: "",
            rownumbers: false,
            loadui: "disable",
            footerrow: true,
            shrinkToFit: false,
            autoScroll: true,
            colModel: me.colModel,
        });
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMTVE260TargetResultList fieldset").width()
        );
        //20240806 caina upd s
        var ch = $(".ui-widget-content.HMTVE.HMTVE-layout-center").height();
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 210);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, ch - 353);
        //20240806 caina upd e
        $(me.grid_id).jqGrid("bindKeys");
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C HMTVE260TargetResultList_tblMain_pandding",
                    startColumnName: "GENRI_MOKUHYO",
                    numberOfColumns: 4,
                    titleText: "総限界利益<br />(単位：千円)",
                },
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C",
                    startColumnName: "URIMOKU_MAIN",
                    numberOfColumns: 10,
                    titleText: "利益売上台数予想<br />(自契自登＋自契他登)",
                },
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C",
                    startColumnName: "TRKDAISU_FUKUSHI_Y",
                    numberOfColumns: 13,
                    titleText:
                        "登録台数予想<br />(メイン権＋福祉＋他契自登－自契他登＝登録台数予想)",
                },
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C",
                    startColumnName: "URIYOSOU_CHUKO_CHOKU_Y",
                    numberOfColumns: 4,
                    titleText: "中古車",
                },
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C",
                    startColumnName: "SHURI_HOKEN",
                    numberOfColumns: 9,
                    titleText: "周辺利益",
                },
            ],
        });
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            numberOfRowSpan: 3,
            groupHeaders: [
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C",
                    startColumnName: "URIMOKU_MAIN",
                    numberOfColumns: 2,
                    titleText: "当月目標",
                },
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C",
                    startColumnName: "URIYOSOU_MAIN_Y",
                    numberOfColumns: 6,
                    titleText: "月末予想",
                },
                {
                    addclass:
                        "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C",
                    startColumnName: "KEI_TRK_DAISU_Y",
                    numberOfColumns: 5,
                    titleText: "軽自動車",
                },
            ],
        });
        $(me.grid_id).jqGrid("setFrozenColumns");
        $(".HMTVE260TargetResultList .ui-th-column.ui-th-ltr.ui-state-default")
            .css("background", "#006600")
            .css("color", "#FFFFFF");
        $(me.grid_id).jqGrid("setGridParam", {
            gridComplete: function () {
                $("th[colspan]").each(function () {
                    $(this)
                        .addClass(
                            "HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C"
                        )
                        .css("background", "#006600")
                        .css("color", "#FFFFFF");
                });
                setTimeout(function () {
                    $(
                        ".HMTVE260TargetResultList .frozen-div.ui-jqgrid-hdiv"
                    ).height(
                        $(
                            ".HMTVE260TargetResultList .ui-th-column.ui-th-ltr.ui-state-default"
                        ).height()
                    );
                    $(
                        ".HMTVE260TargetResultList .frozen-div.ui-jqgrid-hdiv .HMTVE260TargetResultList_tblMain_BUSYO260_CELL_TITLE_GREEN_C"
                    ).height($(".frozen-div.ui-jqgrid-hdiv").height());
                    me.gridStyleReset();
                    $(
                        ".HMTVE260TargetResultList .View1 div.ui-jqgrid-hdiv.ui-state-default.ui-corner-top table tr:nth-of-type(2) .HMTVE260TargetResultList_tblMain_CELL_TITLE_GREEN_C"
                    ).css({
                        "padding-top": "5px",
                        "padding-bottom": "5px",
                    });

                    $(
                        ".HMTVE260TargetResultList .frozen-bdiv.ui-jqgrid-bdiv"
                    ).height(
                        $(
                            "#gview_HMTVE260TargetResultList_tblMain > div:nth-of-type(3)"
                        ).height() - 15
                    );
                    $(
                        ".HMTVE260TargetResultList .frozen-bdiv.ui-jqgrid-bdiv"
                    ).css(
                        "top",
                        $(
                            ".HMTVE260TargetResultList .frozen-div.ui-jqgrid-hdiv"
                        ).height() + 1
                    );
                    if (me.ratio === 1.5) {
                        $(".HMTVE260TargetResultList .ui-jqgrid-labels th").css(
                            "height",
                            "20px"
                        );
                        $(".HMTVE260TargetResultList .ui-jqgrid-resize").css(
                            "height",
                            "20px"
                        );
                        $(".HMTVE260TargetResultList .ui-th-div").css(
                            "top",
                            "0px"
                        );
                    }
                }, 0);
            },
        });
        $(
            ".HMTVE260TargetResultList .frozen-div.ui-state-default.ui-jqgrid-hdiv"
        ).css("overflow-y", "hidden");
    };
    me.gridStyleReset = function () {
        $("#HMTVE260TargetResultList_tblMain_GENRI_SABUN").remove();
        $("#HMTVE260TargetResultList_tblMain_GENRI_JISSEKI").remove();
        $("#HMTVE260TargetResultList_tblMain_GENRI_YOSOU").prop("colspan", "3");
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_MAIN_S").remove();
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_MAIN_Y").prop(
            "colspan",
            "2"
        );
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_KEI_S").remove();
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_KEI_Y").prop(
            "colspan",
            "2"
        );
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_VOLVO_SONOTA_S").remove();
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_VOLVO_SONOTA_Y").prop(
            "colspan",
            "2"
        );
        $("#HMTVE260TargetResultList_tblMain_URI_GK_S").remove();
        $("#HMTVE260TargetResultList_tblMain_URI_GK_Y").prop("colspan", "2");
        $("#HMTVE260TargetResultList_tblMain_TRKDAISU_FUKUSHI_S").remove();
        $("#HMTVE260TargetResultList_tblMain_TRKDAISU_FUKUSHI_Y").prop(
            "colspan",
            "2"
        );
        $("#HMTVE260TargetResultList_tblMain_TRKDAISU_TAJI_S").remove();
        $("#HMTVE260TargetResultList_tblMain_TRKDAISU_TAJI_Y").prop(
            "colspan",
            "2"
        );
        $("#HMTVE260TargetResultList_tblMain_TRKDAISU_JITA_S").remove();
        $("#HMTVE260TargetResultList_tblMain_TRKDAISU_JITA_Y").prop(
            "colspan",
            "2"
        );
        $("#HMTVE260TargetResultList_tblMain_TRK_GK_S").remove();
        $("#HMTVE260TargetResultList_tblMain_TRK_GK_Y").prop("colspan", "2");
        $("#HMTVE260TargetResultList_tblMain_KEI_TRK_DAISU_S").remove();
        $("#HMTVE260TargetResultList_tblMain_KEI_TRK_DAISU_Y").prop(
            "colspan",
            "2"
        );

        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_CHUKO_CHOKU_S").remove();
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_CHUKO_CHOKU_Y").prop(
            "colspan",
            "2"
        );
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_CHUKO_GYOBAI_S").remove();
        $("#HMTVE260TargetResultList_tblMain_URIYOSOU_CHUKO_GYOBAI_Y").prop(
            "colspan",
            "2"
        );
    };
    //車種内訳grid初期化
    me.gridSyasyuInit = function () {
        // 20240712 LHB INS S
        var lenght = 40;
        if (!me.hidden) {
            for (var i = 0; i < me.s_colModel.length; i++) {
                if (me.s_colModel[i]["name"] === "CX60_TRK_DAISU_S") {
                    me.s_colModel.splice(i + 1, 0, {
                        name: "CX80_TRK_DAISU_Y",
                        classes: "CELL_TITLE_GREEN_C",
                        label: "Ｃ<br/>Ｘ<br/>｜<br/>８<br/>０<br/>",
                        index: "CX80_TRK_DAISU_Y",
                        width: 35,
                        align: "right",
                        sortable: false,
                    });
                    me.s_colModel.splice(i + 2, 0, {
                        name: "CX80_TRK_DAISU_S",
                        classes: "CELL_TITLE_GREEN_C",
                        label: "Ｃ<br/>Ｘ<br/>｜<br/>８<br/>０<br/>",
                        index: "CX80_TRK_DAISU_S",
                        width: 35,
                        align: "right",
                        sortable: false,
                        cellattr: function () {
                            return ' style="color:blue"';
                        },
                    });
                    break;
                }
            }
            lenght += 2;
        }
        // 20240712 LHB INS E
        $(me.s_grid_id).jqGrid({
            datatype: "local",
            emptyRecordRow: false,
            caption: "",
            rownumbers: false,
            loadui: "disable",
            footerrow: true,
            shrinkToFit: false,
            autoScroll: true,
            shrinkToFit: false,
            colModel: me.s_colModel,
            gridComplete: function () {
                $(
                    ".HMTVE260TargetResultList tr.ui-jqgrid-labels.jqg-second-row-header th"
                )
                    .css("background", "#006600")
                    .css("color", "#FFFFFF");
                $(
                    ".HMTVE260TargetResultList_tblSyasyu_BUSYO260_CELL_TITLE_GREEN_C"
                )
                    .css("background", "#006600")
                    .css("color", "#FFFFFF");
                $(
                    ".HMTVE260TargetResultList .ui-jqgrid-hdiv .ui-jqgrid-hbox"
                ).css("background", "#006600");
                $(
                    ".HMTVE260TargetResultList .frozen-div.ui-state-default.ui-jqgrid-hdiv"
                ).css("overflow-y", "hidden");
                // 20240712 LHB INS S
                $(".HMTVE260TargetResultList .frozen-bdiv.ui-jqgrid-bdiv").css(
                    "top",
                    $(".frozen-div.ui-jqgrid-hdiv").height() + 1
                );
                // 20240712 LHB INS S
                me.sgridStyleReset();
                $(
                    ".HMTVE260TargetResultList .View2 .frozen-div .ui-jqgrid-htable .HMTVE260TargetResultList_tblSyasyu_BUSYO260_CELL_TITLE_GREEN_C"
                ).css("padding-bottom", "4px");
                setTimeout(function () {
                    $(
                        ".HMTVE260TargetResultList .frozen-div.ui-jqgrid-hdiv"
                    ).css("height:126px");
                    $(
                        "#gview_HMTVE260TargetResultList_tblSyasyu .frozen-bdiv.ui-jqgrid-bdiv"
                    ).height(
                        $(
                            "#gview_HMTVE260TargetResultList_tblSyasyu > div:nth-of-type(3)"
                        ).height() - 12
                    );
                    $(
                        "#gview_HMTVE260TargetResultList_tblSyasyu .frozen-div.ui-jqgrid-hdiv.ui-state-default"
                    ).height(
                        $(
                            "#HMTVE260TargetResultList_tblSyasyu_BUSYO_RYKNM"
                        ).height()
                    );
                    $(
                        ".HMTVE260TargetResultList.View2 .frozen-bdiv.ui-jqgrid-bdiv"
                    ).css(
                        "top",
                        $(
                            "#HMTVE260TargetResultList_tblSyasyu_BUSYO_RYKNM"
                        ).height() + 1
                    );
                }, 0);
            },
        });
        gdmz.common.jqgrid.set_grid_width(
            me.s_grid_id,
            $(".HMTVE260TargetResultList fieldset").width()
        );
        gdmz.common.jqgrid.set_grid_height(me.s_grid_id, 190);
        $(me.s_grid_id).jqGrid("bindKeys");
        $(me.s_grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    addclass:
                        "HMTVE260TargetResultList_tblSyasyu_CELL_TITLE_GREEN_C",
                    startColumnName: "DAISU_Y",
                    // 20240712 LHB UPD S
                    // numberOfColumns: 38,
                    numberOfColumns: lenght,
                    // 20240712 LHB UPD E
                    titleText: "登録台数車種内訳'(台数＝最低バー)",
                },
            ],
        });
        $(me.s_grid_id).jqGrid("setFrozenColumns");
    };
    me.sgridStyleReset = function () {
        for (var i = 1; i < me.s_colModel.length; i++) {
            $(
                "#HMTVE260TargetResultList_tblSyasyu_" +
                    me.s_colModel[i + 1]["name"]
            ).remove();
            $(
                "#HMTVE260TargetResultList_tblSyasyu_" +
                    me.s_colModel[i]["name"]
            ).prop("colspan", "2");
            i++;
        }
    };
    //合計明細ﾃｰﾌﾞﾙに値をセットする
    me.gridComplete = function (objdr1, objdr2, objdr3) {
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find(".sumtr")
            .remove();
        objdr1["BUSYO_RYKNM"] = "小計";
        for (var i = 1; i < me.colModel.length; i++) {
            if (
                me.colModel[i]["name"] != "BUSYO_RYKNM" &&
                me.colModel[i]["name"] != "BUSYO_CD"
            ) {
                objdr1[me.colModel[i]["name"]] = Number(
                    objdr1[me.colModel[i]["name"]]
                );
            }
        }
        $(me.grid_id).jqGrid("footerData", "set", objdr1);
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_GENRI_SABUN"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_URIYOSOU_MAIN_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_URIYOSOU_KEI_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_URIYOSOU_VOLVO_SONOTA_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_URI_GK_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_TRKDAISU_FUKUSHI_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_TRKDAISU_TAJI_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_TRKDAISU_JITA_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_TRK_GK_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_KEI_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_URIYOSOU_CHUKO_CHOKU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_URIYOSOU_CHUKO_GYOBAI_S"]'
            )
            .css("color", "blue");
        var $sumtrSonota = $(
            ".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        var $sumtrGouki = $(
            ".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        var $sumtrZennentuki = $(
            ".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        var $sumtrTaizennenhi = $(
            ".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        for (var j = 0; j < me.colModel.length; j++) {
            if (
                me.colModel[j]["name"] != "BUSYO_RYKNM" &&
                me.colModel[j]["name"] != "BUSYO_CD"
            ) {
                if (!objdr1[me.colModel[j]["name"]]) {
                    objdr1[me.colModel[j]["name"]] = "0";
                }
                if (!objdr2[me.colModel[j]["name"]]) {
                    objdr2[me.colModel[j]["name"]] = "0";
                }
                if (!objdr3[me.colModel[j]["name"]]) {
                    objdr3[me.colModel[j]["name"]] = "0";
                }
                switch (me.colModel[j]["name"]) {
                    case "GENRI_MOKUHYO":
                    case "GENRI_YOSOU":
                    case "GENRI_SABUN":
                    case "GENRI_JISSEKI":
                    case "TRKDAISU_KEI_FUKUSHI":
                    case "SHURI_HOKEN":
                    case "SHURI_LEASE":
                        $sumtrSonota
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(
                                Number(objdr2[me.colModel[j]["name"]])
                                    .toString()
                                    .numFormat()
                            );
                        $sumtrGouki
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(
                                (
                                    Number(objdr1[me.colModel[j]["name"]]) +
                                    Number(objdr2[me.colModel[j]["name"]])
                                )
                                    .toString()
                                    .numFormat()
                            );
                        $sumtrZennentuki
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(
                                Number(objdr3[me.colModel[j]["name"]])
                                    .toString()
                                    .numFormat()
                            );
                        $sumtrTaizennenhi
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(
                                (
                                    Number(objdr1[me.colModel[j]["name"]]) +
                                    Number(objdr2[me.colModel[j]["name"]]) -
                                    Number(objdr3[me.colModel[j]["name"]])
                                )
                                    .toString()
                                    .numFormat()
                            );
                        break;
                    default:
                        $sumtrSonota
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(Number(objdr2[me.colModel[j]["name"]]));
                        $sumtrGouki
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(
                                Number(objdr1[me.colModel[j]["name"]]) +
                                    Number(objdr2[me.colModel[j]["name"]])
                            );
                        $sumtrZennentuki
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(Number(objdr3[me.colModel[j]["name"]]));
                        $sumtrTaizennenhi
                            .find(
                                '[aria-describedby="HMTVE260TargetResultList_tblMain_' +
                                    me.colModel[j]["name"] +
                                    '"]'
                            )
                            .text(
                                Number(objdr1[me.colModel[j]["name"]]) +
                                    Number(objdr2[me.colModel[j]["name"]]) -
                                    Number(objdr3[me.colModel[j]["name"]])
                            );
                        break;
                }
            }
        }
        $sumtrSonota
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_BUSYO_RYKNM"]'
            )
            .text("その他");
        $sumtrGouki
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_BUSYO_RYKNM"]'
            )
            .text("合計");
        $sumtrZennentuki
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_BUSYO_RYKNM"]'
            )
            .text("前年同月");
        $sumtrTaizennenhi
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblMain_BUSYO_RYKNM"]'
            )
            .text("対前年比");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tr")
            .css("background", "#CCFFCC");
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tbody")
            .append($sumtrSonota.css("background", "none").addClass("sumtr"));
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tbody")
            .append($sumtrGouki.css("background", "#CCFFCC").addClass("sumtr"));
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tbody")
            .append(
                $sumtrZennentuki.css("background", "none").addClass("sumtr")
            );
        $(".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv")
            .find("tbody")
            .append(
                $sumtrTaizennenhi.css("background", "#CCFFCC").addClass("sumtr")
            );
        $(".HMTVE260TargetResultList .View1 .BUSYO260_CELL_TITLE_GREEN_C")
            .css("background", "#006600")
            .css("color", "#FFFFFF");
        var lblSouDaisuu =
            Number(objdr1["TRK_GK_Y"]) +
            Number(objdr2["TRK_GK_Y"]) +
            (Number(objdr1["TRK_GK_S"]) + Number(objdr2["TRK_GK_S"])) +
            (Number(objdr1["KEI_TRK_DAISU_Y"]) +
                Number(objdr2["KEI_TRK_DAISU_Y"])) +
            (Number(objdr1["KEI_TRK_DAISU_S"]) +
                Number(objdr2["KEI_TRK_DAISU_S"]));
        $(".HMTVE260TargetResultList.lblSouDaisuu").val(
            lblSouDaisuu.toString().numFormat()
        );
        $(".HMTVE260TargetResultList.lblUchiRenta").val(
            Number(objdr1["SHURI_OSS"]) + Number(objdr2["SHURI_OSS"])
        );
        $(".HMTVE260TargetResultList .ui-jqgrid tr.footrow td").css(
            "font-weight",
            "normal"
        );
    };
    //合計明細ﾃｰﾌﾞﾙに値をセットする
    me.syasyugridComplete = function (objdr1, objdr2, objdr3) {
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find(".sumtr")
            .remove();
        objdr1["BUSYO_RYKNM"] = "小計";
        for (var i = 1; i < me.s_colModel.length; i++) {
            if (
                me.s_colModel[i]["name"] != "BUSYO_RYKNM" &&
                me.s_colModel[i]["name"] != "BUSYO_CD"
            ) {
                objdr1[me.s_colModel[i]["name"]] = Number(
                    objdr1[me.s_colModel[i]["name"]]
                );
            }
        }
        $(me.s_grid_id).jqGrid("footerData", "set", objdr1);
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .css("background", "#CCFFCC");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_DEMIO_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_M2G_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_CX3_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_CX5_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_CX8_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_CX30_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_MX30_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        // 20240326 LHB INS S
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_CX60_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB INS S
        // $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv").find("tr").find('[aria-describedby="HMTVE260TargetResultList_tblSyasyu_CX80_TRK_DAISU_S"]').css("color", 'blue');
        if (me.hidden == false) {
            $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
                .find("tr")
                .find(
                    '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_CX80_TRK_DAISU_S"]'
                )
                .css("color", "blue");
        }
        // 20240712 LHB INS E
        // 20240611 LHB INS E
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_M3S_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_M3H_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_M6S_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_M6W_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_ATENZA_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_AXS_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_LDSTAR_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_FMV_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_BONGO_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_TT_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tr")
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_KEI_TRK_DAISU_S"]'
            )
            .css("color", "blue");
        var $sumtrSonota = $(
            ".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        var $sumtrGouki = $(
            ".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        var $sumtrZennentuki = $(
            ".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        var $sumtrTaizennenhi = $(
            ".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv:first"
        )
            .find("tr")
            .clone();
        for (var j = 0; j < me.s_colModel.length; j++) {
            if (
                me.s_colModel[j]["name"] != "BUSYO_RYKNM" &&
                me.s_colModel[j]["name"] != "BUSYO_CD"
            ) {
                $sumtrSonota
                    .find(
                        '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_' +
                            me.s_colModel[j]["name"] +
                            '"]'
                    )
                    .text(
                        Number(
                            objdr2[me.s_colModel[j]["name"]]
                                ? objdr2[me.s_colModel[j]["name"]]
                                : 0
                        )
                    );
                $sumtrGouki
                    .find(
                        '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_' +
                            me.s_colModel[j]["name"] +
                            '"]'
                    )
                    .text(
                        Number(
                            objdr1[me.s_colModel[j]["name"]]
                                ? objdr1[me.s_colModel[j]["name"]]
                                : 0
                        ) +
                            Number(
                                objdr2[me.s_colModel[j]["name"]]
                                    ? objdr2[me.s_colModel[j]["name"]]
                                    : 0
                            )
                    );
                $sumtrZennentuki
                    .find(
                        '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_' +
                            me.s_colModel[j]["name"] +
                            '"]'
                    )
                    .text(
                        Number(
                            objdr3[me.s_colModel[j]["name"]]
                                ? objdr3[me.s_colModel[j]["name"]]
                                : 0
                        )
                    );
                $sumtrTaizennenhi
                    .find(
                        '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_' +
                            me.s_colModel[j]["name"] +
                            '"]'
                    )
                    .text(
                        Number(
                            objdr1[me.s_colModel[j]["name"]]
                                ? objdr1[me.s_colModel[j]["name"]]
                                : 0
                        ) +
                            Number(
                                objdr2[me.s_colModel[j]["name"]]
                                    ? objdr2[me.s_colModel[j]["name"]]
                                    : 0
                            ) -
                            Number(
                                objdr3[me.s_colModel[j]["name"]]
                                    ? objdr3[me.s_colModel[j]["name"]]
                                    : 0
                            )
                    );
            }
        }
        $sumtrSonota
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_BUSYO_RYKNM"]'
            )
            .text("その他");
        $sumtrGouki
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_BUSYO_RYKNM"]'
            )
            .text("合計");
        $sumtrZennentuki
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_BUSYO_RYKNM"]'
            )
            .text("前年同月");
        $sumtrTaizennenhi
            .find(
                '[aria-describedby="HMTVE260TargetResultList_tblSyasyu_BUSYO_RYKNM"]'
            )
            .text("対前年比");
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tbody")
            .append($sumtrSonota.addClass("sumtr"));
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tbody")
            .append($sumtrGouki.addClass("sumtr"));
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tbody")
            .append($sumtrZennentuki.addClass("sumtr"));
        $(".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv")
            .find("tbody")
            .append($sumtrTaizennenhi.addClass("sumtr"));
        $(".HMTVE260TargetResultList .View2 .BUSYO260_CELL_TITLE_GREEN_C")
            .css("background", "#006600")
            .css("color", "#FFFFFF");
    };
    // **********************************************************************
    // 処 理 名：目標と実績表示ボタンのイベント
    // 関 数 名：btnETSearch_Click
    // 戻 り 値：なし
    // 処理説明：目標と実績画面の戻り値を画面項目にセットする
    // **********************************************************************
    me.btnETSearch_Click = function () {
        if (
            gdmz.SessionPatternID !== me.HMTVE.CONST_ADMIN_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_HONBU_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_MANAGER_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_TESTER_PTN_NO
        ) {
            return;
        }
        var data = {
            ActiveViewIndex: me.ActiveViewIndex,
            txtbDuring: $(".HMTVE260TargetResultList.txtbDuring").val(),
            ddlMonth: $(".HMTVE260TargetResultList.ddlMonth").val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnETSearch_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.subSearchButtonEnable(false);
                setTimeout(me.clsComFnc.FncMsgBox("E9999", result["error"]), 0);
                return;
            }
            var data = result["data"];
            if (me.ActiveViewIndex == 0) {
                if (data["dataSet1"].length > 0) {
                    //20240806 caina upd s
                    var ch = $(
                        ".ui-widget-content.HMTVE.HMTVE-layout-center"
                    ).height();
                    // gdmz.common.jqgrid.set_grid_height(me.grid_id, 210);
                    gdmz.common.jqgrid.set_grid_height(
                        me.grid_id,
                        ch - (me.ratio === 1.5 ? 290 : 353)
                    );
                    //20240806 caina upd e
                } else {
                    $(me.grid_id).css("height", "1px");
                }
                $(me.grid_id).jqGrid("clearGridData");
                for (var i = 0; i < data["dataSet1"].length; i++) {
                    $(me.grid_id).addRowData(i, data["dataSet1"][i]);
                }
                $(me.grid_id).jqGrid("setSelection", 0);
                me.gridComplete(
                    data["objdr2"][0],
                    data["objdr3"][0],
                    data["objdr4"][0]
                );
                me.getSumFrozen();
            } else if (me.ActiveViewIndex == 1) {
                if (data["dataSet2"].length > 0) {
                    gdmz.common.jqgrid.set_grid_height(
                        me.s_grid_id,
                        me.ratio === 1.5 ? 130 : 170
                    );
                } else {
                    $(me.s_grid_id).css("height", "1px");
                }
                $(me.s_grid_id).jqGrid("clearGridData");
                // 20240712 LHB INS S
                me.hidden = data["isExit"] == "0" ? true : false;
                // 20240712 LHB INS E
                for (var i = 0; i < data["dataSet2"].length; i++) {
                    $(me.s_grid_id).addRowData(i, data["dataSet2"][i]);
                }
                $(me.s_grid_id).jqGrid("setSelection", 0);

                me.syasyugridComplete(
                    data["objdr5"][0],
                    data["objdr6"][0],
                    data["objdr7"][0]
                );
                me.getSyasyuSumFrozen();
            }
            me.subSearchButtonEnable(true);
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：車種内訳画面へボタンのイベント
    // 関 数 名：btnToCar_Click
    // 戻 り 値：なし
    // 処理説明：車種内訳画面へ行く
    // **********************************************************************
    me.btnToCar_Click = function () {
        if (me.checkNull() == false) {
            return;
        }
        me.ActiveViewIndex = 1;
        me.btnETSearch_Click();
    };
    // **********************************************************************
    // 処 理 名：一覧画面へボタンのイベント
    // 関 数 名：btnLogin_Click
    // 戻 り 値：なし
    // 処理説明：一覧画面へ行く
    // **********************************************************************
    me.btnSee_Click = function () {
        if (me.checkNull() == false) {
            return;
        }
        me.ActiveViewIndex = 0;
        me.btnETSearch_Click();
    };

    me.subSearchButtonEnable = function (blnHantei) {
        if (blnHantei == true) {
            if (me.ActiveViewIndex == 0) {
                $(".HMTVE260TargetResultList.View2").hide();
                $(".HMTVE260TargetResultList.View1").show();
            } else {
                $(".HMTVE260TargetResultList.View1").hide();
                $(".HMTVE260TargetResultList.View2").show();
            }
        } else {
            $(".HMTVE260TargetResultList.View1").hide();
            $(".HMTVE260TargetResultList.View2").hide();
        }
    };
    //目標と実績grid列を凍結する
    me.getSumFrozen = function () {
        $(".HMTVE260TargetResultList .View1 .frozen-sdiv").remove();
        var $sumdiv = $(
            ".HMTVE260TargetResultList .View1 .ui-jqgrid-sdiv .ui-jqgrid-hbox"
        ).clone();
        $sumdiv.width("");
        $sumdiv.find("table").width("");
        $sumdiv.find("tr").each(function (_i, value) {
            $(value).find(".CELL_TITLE_GREEN_C").remove();
        });

        $sumFrozenDiv = $(
            '<div style="position:absolute;left:0px;bottom:0px;" class="frozen-sdiv"></div>'
        );
        $sumFrozenDiv.append($sumdiv);
        $sumFrozenDiv.insertAfter(
            $(".HMTVE260TargetResultList .View1 .frozen-bdiv")
        );
    };
    //車種内訳grid列を凍結する
    me.getSyasyuSumFrozen = function () {
        $(".HMTVE260TargetResultList .View2 .frozen-sdiv").remove();
        var $sumdiv = $(
            ".HMTVE260TargetResultList .View2 .ui-jqgrid-sdiv .ui-jqgrid-hbox"
        ).clone();
        $sumdiv.width("");
        $sumdiv.find("table").width("");
        $sumdiv.find("tr").each(function (i, value) {
            $(value).find(".CELL_TITLE_GREEN_C").remove();
        });

        $sumFrozenDiv = $(
            '<div style="position:absolute;left:0px;bottom:0px;" class="frozen-sdiv"></div>'
        );
        $sumFrozenDiv.append($sumdiv);
        $sumFrozenDiv.insertAfter(
            $(".HMTVE260TargetResultList .View2 .frozen-bdiv")
        );
    };
    // 目標と実績登録画面ページを開く
    me.openPageExbSearch = function () {
        var frmId = "HMTVE270TargetResultEntry";
        var dialogdiv = "HMTVE260TargetResultListDialogDiv";
        var $rootDiv = $(".HMTVE260TargetResultList.HMTVE-content");
        if (me.checkNull() == false) {
            return;
        }

        $("<div style='display:none;'></div>")
            .prop("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "txtbDuring")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "ddlMonth")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "hidStart")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "hidEnd")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "temp")
            .insertAfter($rootDiv);
        // 20240712 INS S
        if (me.hidden === true) {
            $("<div style='display:none;'></div>")
                .prop("id", "isexit")
                .insertAfter($rootDiv);
            var $hide = $rootDiv.parent().find("#isexit");
            $hide.html("true");
        }
        // 20240712 INS E

        var $txtbDuring = $rootDiv.parent().find("#txtbDuring");
        var $ddlMonth = $rootDiv.parent().find("#ddlMonth");
        var $temp = $rootDiv.parent().find("#temp");

        $txtbDuring.html($(".HMTVE260TargetResultList.txtbDuring").val());
        $ddlMonth.html($(".HMTVE260TargetResultList.ddlMonth").val());
        $temp.html(Math.random());

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                var $hidStart = $rootDiv.parent().find("#hidStart");
                var $hidEnd = $rootDiv.parent().find("#hidEnd");
                if ($hidStart.html() !== "" && $hidEnd.html() !== "") {
                    $(".HMTVE260TargetResultList.txtbDuring").val(
                        $hidStart.html()
                    );
                    $(".HMTVE260TargetResultList.ddlMonth").val($hidEnd.html());
                    me.btnETSearch_Click();
                }
                $hidStart.remove();
                $hidEnd.remove();
                $txtbDuring.remove();
                $ddlMonth.remove();
                $temp.remove();
                // 20240712 INS S
                if (me.hidden === true) {
                    $hide.remove();
                }
                // 20240712 INS E
                $("#" + dialogdiv).remove();
            }

            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE260TargetResultList.HMTVE270TargetResultEntry.before_close =
                before_close;
        };
    };
    //Excel出力前の確認
    me.MesExcel = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnExcel_Click;
        //出力ボタンの確認メッセージの表示
        me.clsComFnc.FncMsgBox(
            "QY999",
            "Excelデータを出力します。よろしいですか？"
        );
    };
    // **********************************************************************
    // 処 理 名：会議資料(Excel)出力ボタンのイベント
    // 関 数 名：btnExcel_Click
    // 戻 り 値：なし
    // 処理説明：エクセル出力する
    // **********************************************************************
    me.btnExcel_Click = function () {
        if (me.checkNull() == false) {
            return;
        }
        if (me.Excelbtn == "btnExChukan") {
            me.MakeChukanExcel();
        } else {
            me.MakeGesshoExcel();
        }
    };

    // **********************************************************************
    // 処 理 名：中間会議資料_Excelファイル
    // 関 数 名：MakeChukanExcel
    // 戻 り 値：なし
    // 処理説明：Exceファイル生成処理
    // **********************************************************************
    me.MakeChukanExcel = function () {
        var data = {
            txtbDuring: $(".HMTVE260TargetResultList.txtbDuring").val(),
            ddlMonth: $(".HMTVE260TargetResultList.ddlMonth").val(),
        };
        var url = me.sys_id + "/" + me.id + "/MakeChukanExcel";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "テンプレートファイルが存在しません。") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：月初会議資料_Excelファイル
    // 関 数 名：MakeGesshoExcel
    // 戻 り 値：なし
    // 処理説明：Exceファイル生成処理
    // **********************************************************************
    me.MakeGesshoExcel = function () {
        var data = {
            txtbDuring: $(".HMTVE260TargetResultList.txtbDuring").val(),
            ddlMonth: $(".HMTVE260TargetResultList.ddlMonth").val(),
        };
        var url = me.sys_id + "/" + me.id + "/MakeGesshoExcel";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "テンプレートファイルが存在しません。") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：日付チェク
    // 関 数 名：checkDate1
    // 引 数 １：なし
    // 戻 り 値：なし
    // 処理説明：日付をチェクする
    // **********************************************************************
    me.checkDate1 = function (txtDate) {
        if (txtDate.val() == "0000") {
            return false;
        }
        var strDate = new Date($.trim(txtDate.val()), 1, 1);
        if (strDate == "Invalid Date") {
            return false;
        }
        return true;
    };
    // 対象年月(年)をチェックする
    me.checkNull = function () {
        if ($.trim($(".HMTVE260TargetResultList.ddlMonth").val()).length == 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE260TargetResultList.ddlMonth");
            me.clsComFnc.FncMsgBox("W9999", "対象年月(月)を入力してください。");
            return false;
        }
        var $txtbDuring = $(".HMTVE260TargetResultList.txtbDuring");
        if (me.clsComFnc.GetByteCount($.trim($txtbDuring.val())) == 4) {
            if (me.checkDate1($txtbDuring) == false) {
                me.clsComFnc.ObjFocus = $txtbDuring;
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "対象年月(年)は不正な値が入力されています。"
                );
                return false;
            }
        } else if (
            me.clsComFnc.GetByteCount($txtbDuring.val().trimEnd()) == 0
        ) {
            me.clsComFnc.ObjFocus = $txtbDuring;
            me.clsComFnc.FncMsgBox("W9999", "対象年月(年)を入力してください。");
            return false;
        } else {
            me.clsComFnc.ObjFocus = $txtbDuring;
            me.clsComFnc.FncMsgBox(
                "W9999",
                "対象年月(年)は不正な値が入力されています。"
            );
            return false;
        }
        return true;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE260TargetResultList = new HMTVE.HMTVE260TargetResultList();
    o_HMTVE_HMTVE260TargetResultList.load();
    o_HMTVE_HMTVE.HMTVE260TargetResultList = o_HMTVE_HMTVE260TargetResultList;
});
