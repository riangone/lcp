/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20171215 		  #2807						   依頼								YIN
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSinsyaZaikoTorikomi");

R4.FrmSinsyaZaikoTorikomi = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSinsyaZaikoTorikomi";
    me.sys_id = "R4K";
    me.strTougetu = "";
    me.fileMark = 0;

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmSinsyaZaikoTorikomi.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSinsyaZaikoTorikomi.cmdAct",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.frmSample_Load();
        //****************************************D3**************************************************************.START

        // var w = 920;
        // var h = 300;
        // var padding = 80;
        // //the width of cell.
        // var cell = 30;
        // var marginleft = 40;
        // //the info of "■".
        // var dataset = [[1, 7], [3, 8], [6, 8], [12, 8], [18, 8], [24, 8], [6, 7], [12, 7], [11, 6]];
        // //参照线
        // var dataset1 = [0, 1, 3, 6, 12, 18, 24, 30, 36, 42, 48, 54, 60, 66, 72, 78, 84, 90, 96, 102, 108, 114, 120, 126, 132];
        // //label.
        // var dataset2 = [["入庫歴", 8], ["定期点検", 7], ["リコール", 6], ["パックｄｅメンテ", 5], ["延長保証", 4], ["ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ", 3], ["クレジット", 2], ["保険", 1]];
        // //単位:月 label
        // var dataset3 = ["単位:月"];
        // //the info of Rectangular.
        // var dataset4 = [[24, 5, 5, "rgb(9,255,154)", "哈哈哈哈"], [24, 4, 5, "rgb(9,255,154)", "呵呵呵呵"], [24, 3, 5, "rgb(255,255,255)", "我了个去"], [24, 2, 1, "rgb(255,170,255)", "Fcs"], [24, 1, 1, "rgb(255,145,12)", "Mazda"]];
        // //the current month.
        // var dataset5 = [24];
        //
        // //**************************************************************
        // //Create scale functions
        // //**************************************************************
        // var xScale = d3.scale.linear().domain([0, 1]).range([marginleft + padding, marginleft + padding + cell]);
        // var xScale1 = d3.scale.linear().domain([1, 3]).range([marginleft + padding + cell, marginleft + padding + 2 * cell]);
        // var xScale2 = d3.scale.linear().domain([3, 6]).range([marginleft + padding + 2 * cell, marginleft + padding + 3 * cell]);
        // var xScale3 = d3.scale.linear().domain([6, 132]).range([marginleft + padding + 3 * cell, w - padding]);
        // var yScale = d3.scale.linear().domain([0, 8]).range([h - padding, 40]);
        // //设置刻度的格式
        // var format = d3.format("1");
        //
        // //Create SVG element
        // var svg = d3.select("#timeline").append("svg").attr("width", w).attr("height", h);
        // var tip = d3.tip().attr("class", "d3-tip").offset([-10, 0]).html(function(d)
        // {
        // return d[0];
        // });
        // svg.call(tip);
        // //********************************************************************
        // //draw "■"
        // //********************************************************************
        // svg.selectAll("rect").data(dataset).enter().append("rect").attr("x", function(d)
        // {
        // if (d[0] >= 0 && d[0] <= 1)
        // {
        // return xScale(d[0] - 0.13);
        // }
        // else
        // if (d[0] > 1 && d[0] <= 3)
        // {
        // return xScale1(d[0] - 0.26);
        // }
        // else
        // if (d[0] > 3 && d[0] <= 6)
        // {
        // return xScale2(d[0] - 0.39);
        // }
        // else
        // if (d[0] > 6 && d[0] <= 132)
        // {
        // return xScale3(d[0] - 0.78);
        // }
        // //设置原点坐标，以及横轴位移量
        // }).attr("y", function(d)
        // {
        // return yScale(d[1]);
        // }).attr("width", 8).attr('height', 8).attr("class", function(d)
        // {
        // if (d[0] == dataset5[0])
        // {
        // return "current";
        // }
        // else
        // {
        // return "bar";
        // }
        // }).on("mouseover", tip.show).on('mouseout', tip.hide);
        //
        // //**********************************************************************
        // //参照线
        // //***********************************************************************
        // svg.selectAll("rect1").data(dataset1).enter().append("rect").attr("x", function(d, i)
        // {
        // return (w - 2 * padding - marginleft) / 24 * i + padding + 40;
        // }).attr("y", function(d)
        // {
        // return 40;
        // }).attr("width", 0.3).attr("height", 150).attr("fill", function(d)
        // {
        // return "rgb(220,220,220)";
        // });
        //
        // //************************************************************************s
        // //draw Rectangular.
        // //************************************************************************s
        // svg.selectAll("rect2").data(dataset4).enter().append("rect").attr("x", function(d)
        // {
        // if (d[0] >= 0 && d[0] <= 1)
        // {
        // return xScale(d[0]);
        // }
        // else
        // if (d[0] > 1 && d[0] <= 3)
        // {
        // return xScale1(d[0]);
        // }
        // else
        // if (d[0] > 3 && d[0] <= 6)
        // {
        // return xScale2(d[0]);
        // }
        // else
        // if (d[0] > 6 && d[0] <= 132)
        // {
        // return xScale3(d[0]);
        // }
        // //return xScale(d[0]);
        // //设置原点坐标，以及横轴位移量
        // }).attr("y", function(d)
        // {
        // return yScale(d[1]);
        // }).attr("width", function(d)
        // {
        // return (w - 2 * padding - marginleft) / 24 * d[2];
        // }).attr('height', function(d)
        // {
        // return 14;
        // }).attr('fill', function(d)
        // {
        // return d[3];
        // });
        //
        // //**********************************************************************
        // //draw the line of current month.
        // //***********************************************************************
        // svg.selectAll("rect3").data(dataset5).enter().append("rect").attr("x", function(d, i)
        // {
        // if (d >= 0 && d <= 1)
        // {
        // return xScale(d);
        // }
        // else
        // if (d > 1 && d <= 3)
        // {
        // return xScale1(d);
        // }
        // else
        // if (d > 3 && d <= 6)
        // {
        // return xScale2(d);
        // }
        // else
        // if (d > 6 && d <= 132)
        // {
        // return xScale3(d);
        // }
        // }).attr("y", function(d)
        // {
        // return padding - 10;
        // }).attr("width", 0.5).attr("height", 170).attr("fill", function(d)
        // {
        // return "rgb(255,127,0)";
        // });
        //
        // //************************************************************************
        // //draw the left info.
        // //*************************************************************************
        // svg.selectAll("text").data(dataset2).enter().append("text").text(function(d)
        // {
        // return d[0];
        // }).attr("x", function(d)
        // {
        // return 0;
        // }).attr("y", function(d)
        // {
        // return yScale(d[1]) + 10;
        // }).attr("font-family", "sans-serif").attr("font-size", "12px").attr("font-weight", "bold");
        //
        // //**************************************************************************
        // //単位:月
        // //**************************************************************************
        // svg.selectAll("text1").data(dataset3).enter().append("text").text(function(d)
        // {
        // return d;
        // }).attr("x", w - padding + 10).attr("y", 30).attr("font-family", "sans-serif").attr("font-size", "12px").attr('fill', function()
        // {
        // return "rgb(0,0,255)";
        // }).attr("font-weight", "bold");
        //
        // //***********************************************************************s
        // //draw the info of Rectangular.
        // //************************************************************************
        // svg.selectAll("text2").data(dataset4).enter().append("text").text(function(d)
        // {
        // return d[4];
        // }).attr("x", function(d)
        // {
        // if (d[0] >= 0 && d[0] <= 1)
        // {
        // return xScale(d[0]);
        // }
        // else
        // if (d[0] > 1 && d[0] <= 3)
        // {
        // return xScale1(d[0]);
        // }
        // else
        // if (d[0] > 3 && d[0] <= 6)
        // {
        // return xScale2(d[0]);
        // }
        // else
        // if (d[0] > 6 && d[0] <= 132)
        // {
        // return xScale3(d[0]);
        // }
        // }).attr("y", function(d)
        // {
        // return yScale(d[1]) + 10;
        // }).attr("font-family", "sans-serif").attr("font-size", "12px");
        //
        // //Define X axis[0,1]
        // var xAxis = d3.svg.axis().scale(xScale).tickSize(-5).tickPadding(2).tickValues([0, 1]).orient("bottom").tickFormat(format);
        // //Define X1 axis[1,3]
        // var xAxis1 = d3.svg.axis().scale(xScale1).tickSize(-5).tickPadding(2).tickValues([1, 3]).orient("bottom").tickFormat(format);
        // //Define X2 axis[3,6]
        // var xAxis2 = d3.svg.axis().scale(xScale2).tickSize(-5).tickPadding(2).tickValues([3, 6]).orient("bottom").tickFormat(format);
        // //Define X3 axis[6,132]
        // var xAxis3 = d3.svg.axis().scale(xScale3).tickSize(-5).tickPadding(2).tickValues([6, 12, 18, 24, 30, 36, 42, 48, 54, 60, 66, 72, 78, 84, 90, 96, 102, 108, 114, 120, 126, 132]).orient("bottom").tickFormat(format);
        //
        // //Create X axis[0,1]
        // svg.append("g").attr("class", "axis").attr("transform", "translate(0," + (padding - 60) + ")").call(xAxis);
        // //Create X1 axis[1,3]
        // svg.append("g").attr("class", "axis").attr("transform", "translate(0," + (padding - 60) + ")").call(xAxis1);
        // //Create X2 axis[3,6]
        // svg.append("g").attr("class", "axis").attr("transform", "translate(0," + (padding - 60) + ")").call(xAxis2);
        // //Create X3 axis[6,132]
        // svg.append("g").attr("class", "axis").attr("transform", "translate(0," + (padding - 60) + ")").call(xAxis3);
        //****************************************D3**************************************************************.End
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //********************************************************************
    //   [参照]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmSinsyaZaikoTorikomi.cmdOpen").click(function () {
        me.fileMark = 0;
        //参照ボタンcmdOpen_Click
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = "text/plain";
        $("#FrmSinsyaZaikoTorikomiFileUpload").html("");
        $("#FrmSinsyaZaikoTorikomiFileUpload").append(me.file.create());
        //20171215 YIN DEL S
        // me.file.select_file();
        //20171215 YIN DEL E
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[i].size > 2048000) {
                me.clsComFnc.MessageBox(
                    "添付可能なファイルサイズは、最大 2000KB です。",
                    "HMReports",
                    "OK",
                    me.clsComFnc.MessageBoxIcon.Warning
                );
                return;
            }

            if (fileType != "txt") {
                me.clsComFnc.MessageBox(
                    "使用できるファイルは.txtです。",
                    "HMReports",
                    "OK",
                    me.clsComFnc.MessageBoxIcon.Warning
                );
                return;
            }

            $(".FrmSinsyaZaikoTorikomi.txtFile").val(this.files[i].name);
        });
        //20171215 YIN INS S
        me.file.select_file();
        //20171215 YIN INS E
    });
    //********************************************************************
    //   [実行]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmSinsyaZaikoTorikomi.cmdAct").click(function () {
        if (me.fileMark == 0) {
            me.fncCheckFile();
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
            me.clsComFnc.MessageBox(
                "実行します。よろしいですか？",
                "HMReports",
                "YesNo",
                "Question"
            );
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmSample_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmSample_Load = function () {
        //画面項目ｸﾘｱ
        $("FrmSinsyaZaikoTorikomi txtFile").val("");
        var url = me.sys_id + "/" + me.id + "/" + "frmSample_Load";
        me.ajax.receive = function (result) {
            $(".FrmSinsyaZaikoTorikomi.cmdOpen").trigger("focus");
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                } else {
                    //コンボボックスに当月年月を設定
                    me.strTougetu = me.clsComFnc.FncNv(
                        result["data"]["0"]["TOUGETU"]
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 0);
    };
    me.func = function () {
        me.fileMark = 1;
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
        me.clsComFnc.MessageBox(
            "実行します。よろしいですか？",
            "HMReports",
            "YesNo",
            "Question"
        );
    };

    me.cmdAct_ClickNo = function () {
        me.fileMark = 1;
    };

    me.cmdAct_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "cmdAct_Click";
        var data = $(".FrmSinsyaZaikoTorikomi.txtFile").val();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            //クリア処理
            $(".FrmSinsyaZaikoTorikomi.txtFile").val("");
            me.fileMark = 0;
            if (result["result"] == true) {
                me.clsComFnc.MessageBox(
                    "取込処理は正常に終了しました。",
                    me.clsComFnc.GSYSTEM_NAME,
                    "OK",
                    ""
                );
            } else {
                if (result["data"] != "") {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.ShowMessageBox;
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                } else {
                    me.ShowMessageBox();
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.ShowMessageBox = function () {
        me.clsComFnc.MessageBox(
            "取込処理はエラー終了しました。ログファイルを確認してください。",
            me.clsComFnc.GSYSTEM_NAME,
            "OK",
            ""
        );
    };
    //********************************************************************
    //処理概要：ﾌｧｲﾙのﾁｪｯｸ処理
    //引　　数：なし
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    me.fncCheckFile = function () {
        var FileName = $(".FrmSinsyaZaikoTorikomi.txtFile").val();
        //取込ﾌｧｲﾙが未入力の場合はｴﾗｰ
        if (FileName.trimEnd() == "") {
            me.clsComFnc.MessageBox(
                "取込ﾌｧｲﾙを指定してください。",
                "HMReports",
                "OK",
                me.clsComFnc.MessageBoxIcon.Warning
            );
            return;
        }
        me.file.send(me.func);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSinsyaZaikoTorikomi = new R4.FrmSinsyaZaikoTorikomi();
    o_R4_FrmSinsyaZaikoTorikomi.load();
});
