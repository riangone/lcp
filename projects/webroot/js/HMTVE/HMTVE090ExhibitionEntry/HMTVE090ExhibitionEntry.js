/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE090ExhibitionEntry");

HMTVE.HMTVE090ExhibitionEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE090ExhibitionEntry";
    me.hmtve = new HMTVE.HMTVE();
    me.selected = null;

    me.CREATE_DATE = null;
    me.data = [];
    me.dateDt = [];
    var calendarEl = document.getElementById("calendar");
    me.calendar = new FullCalendar.Calendar(calendarEl, {
        locale: "ja",
        initialView: "dayGridMonth",
        events: me.data,
        defaultAllDay: true,
        displayEventTime: false,
        selectable: true,
        unselectAuto: false,
        nextDayThreshold: "00:00:00",
        selectAllow: function (info) {
            var titleMon = me.calendar.view.title
                .substring(5)
                .replace("月", "");
            if (
                info.startStr.substring(5, 7) ==
                (titleMon.length == 2 ? titleMon : "0" + titleMon)
            ) {
                return true;
            }
        },
        select: function (info) {
            me.selected = info;
            me.Calendar_SelectionChanged(me.selected.startStr);
        },
        eventClick: function (info) {
            // var titleMon = info.view.title.substring(5).replace('月', '');
            // if (info.event.startStr.substring(5, 7) != (titleMon.length == 2 ? titleMon : '0' + titleMon))
            // {
            // return;
            // }
            me.calendar.select(info.event.startStr);
        },
        dayMaxEvents: true, // allow "more" link when too many events
    });

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE090ExhibitionEntry.Button",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMTVE090ExhibitionEntry.Datepicker",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmtve.TabKeyDown();

    //Enterキーのバインド
    me.hmtve.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // 編集
    $(".HMTVE090ExhibitionEntry.btnUpdate").click(function () {
        me.BtnUpdate_OnClick();
    });
    // 削除
    $(".HMTVE090ExhibitionEntry.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.BtnDelete_OnClick();
        };

        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会データを削除します。よろしいですか？"
        );
    });
    // キャンセル
    $(".HMTVE090ExhibitionEntry.btnCancel").click(function () {
        me.BtnCancel_OnClick();
    });
    // 更新
    $(".HMTVE090ExhibitionEntry.btnEdit").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            // 入力チェック
            if (!me.inputCheck()) {
                return;
            }
            me.BtnEdit_OnClick();
        };

        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会データを更新します。よろしいですか？"
        );
    });
    // 追加
    $(".HMTVE090ExhibitionEntry.btnInsert").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            // 入力チェック
            if (!me.inputCheck()) {
                return;
            }
            me.BtnInsert_OnClick();
        };

        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会データを追加します。よろしいですか？"
        );
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

        me.calendar.render();
        $(".fc-daygrid-body.fc-daygrid-body-unbalanced").width("0px");
        $(".HMTVE090ExhibitionEntry .fc-col-header").css(
            "width",
            $(".HMTVE090ExhibitionEntry.calendar").css("width")
        );
        $(".HMTVE090ExhibitionEntry .fc-scrollgrid-sync-table").css(
            "width",
            $(".HMTVE090ExhibitionEntry.calendar").css("width")
        );
        // var calendarBodyH = $('.HMTVE090ExhibitionEntry.calendar .fc-scrollgrid.fc-scrollgrid-liquid').css('height').replace('px', '');
        // var weekHeadH = $('.HMTVE090ExhibitionEntry.calendar .fc-col-header ').css('height').replace('px', '');
        // $('.HMTVE090ExhibitionEntry .fc-scrollgrid-sync-table').css('height', (calendarBodyH - weekHeadH - 3) + 'px');
        $(".HMTVE090ExhibitionEntry .fc-scrollgrid-sync-table").css(
            "height",
            "497px"
        );
        me.setEventWidth();

        //fullcalendar 前月クリック
        $(".fc-prev-button").click(function () {
            me.Calendar_DayRender();
        });
        //fullcalendar 来月クリック
        $(".fc-next-button").click(function () {
            me.Calendar_DayRender();
        });
        //fullcalendar Todayクリック
        $(".fc-today-button").click(function () {
            me.Calendar_DayRender();
        });
        //プロシージャ:画面初期化
        me.Page_Load();
    };

    me.Page_Load = function () {
        // 画面初期化
        $(".HMTVE090ExhibitionEntry.dtv").hide();

        //データバインドする
        var url = me.sys_id + "/" + me.id + "/GetSysDate";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.calendar.firstDay = result["data"];
                me.Calendar_DayRender();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, "", 0);
    };

    me.kijunbiSet = function () {
        // 現在の基準日をセットする
        // 基準日を取得する
        var objdr = me.dateDt;
        // '基準日をセットする
        // '展示会開催期間に展示会データの基準日ﾌﾗｸﾞに1がたっている展示会データが存在しない
        if (objdr.length == 0) {
            $(".HMTVE090ExhibitionEntry.lblExibitTerm").val("");
        } else {
            if (objdr[0]["START_DATE"] == "" || objdr[0]["END_DATE"] == "") {
                $(".HMTVE090ExhibitionEntry.lblExibitTerm").val("");
            } else {
                // 現在の基準日に基準日ﾌﾗｸﾞが1の展示会開催期間が表示されている
                var start =
                    objdr[0]["START_DATE"].substring(0, 4) +
                    "/" +
                    objdr[0]["START_DATE"].substring(4, 6) +
                    "/" +
                    objdr[0]["START_DATE"].substring(6);
                var end =
                    objdr[0]["END_DATE"].substring(0, 4) +
                    "/" +
                    objdr[0]["END_DATE"].substring(4, 6) +
                    "/" +
                    objdr[0]["END_DATE"].substring(6);
                $(".HMTVE090ExhibitionEntry.lblExibitTerm").val(
                    start + "～" + end
                );
            }
        }
    };

    // '**********************************************************************
    // '処 理 名：カレンダー内の日付セル作成
    //
    // '関 数 名：Calendar_DayRender
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    //
    // '処理説明：カレンダー内の日付セル作成
    // '**********************************************************************
    me.Calendar_DayRender = function () {
        //データバインドする
        var url = me.sys_id + "/" + me.id + "/calendarDayRender";
        var data = me.getCalDate();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.data = result["data"];
                me.calendar.removeAllEvents();
                me.calendar.addEventSource(me.data);
                me.calendar.refetchEvents();

                me.dateDt = result["dataFlg"];
                me.kijunbiSet();
            } else {
                $(".fc-prev-button").button().button("disable");
                $(".fc-next-button").button().button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：カレンダー内の日付セルクリックの場合
    // '関 数 名：Calendar_SelectionChanged
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：カレンダー内の日付セルクリック時,展示会データを取得する
    // '**********************************************************************
    me.Calendar_SelectionChanged = function (dateStr) {
        //データバインドする
        var url = me.sys_id + "/" + me.id + "/calendarDayRender";
        var data = me.getCalDate();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.data = result["data"];
                me.calendar.removeAllEvents();
                me.calendar.addEventSource(me.data);
                me.calendar.refetchEvents();

                me.dateDt = result["dataFlg"];
                me.kijunbiSet();
                // 展示会データを取得する
                var url = me.sys_id + "/" + me.id + "/calendarSelectionChanged";
                var data = {
                    STDT: dateStr.replaceAll("-", ""),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"]) {
                        if (result["data"].length > 0) {
                            // 抽出データ＝1件の場合
                            // データバインド
                            // ItemTemplateが表示された状態
                            me.ChangeMode("ReadOnly", result["data"][0]);
                            me.CREATE_DATE = result["data"][0]["CREATE_DATE"];
                            // 編集ボタンにフォーカスがある
                            $(".HMTVE090ExhibitionEntry.btnUpdate").trigger(
                                "focus"
                            );
                        } else {
                            // 抽出データが0件の場合
                            me.ChangeMode("Insert", {
                                start: dateStr,
                            });
                            // 展示会開催期間(FROM)にフォーカスがある
                            $(
                                ".HMTVE090ExhibitionEntry.TxtExhibitStartDate"
                            ).trigger("focus");
                        }
                    } else {
                        $(".HMTVE090ExhibitionEntry.dtv").hide();
                        me.calendar.unselect();
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                };
                me.ajax.send(url, data, 0);
            } else {
                $(".fc-prev-button").button().button("disable");
                $(".fc-next-button").button().button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.ChangeMode = function (flag, date) {
        if (flag == "ReadOnly") {
            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val(
                new Date(date["start"]).Format("yyyy/MM/dd")
            );
            if (date["start"] >= date["end"]) {
                $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val("");
            } else {
                $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val(
                    new Date(date["end"].substring(0, 10)).Format("yyyy/MM/dd")
                );
            }

            $(".HMTVE090ExhibitionEntry.LblEventMei").val(date["title"]);
            $(".HMTVE090ExhibitionEntry.chkBaseflag").get(0).checked =
                date["BASE_FLG"];

            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").attr(
                "readonly",
                true
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").datepicker(
                "disable"
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").attr(
                "readonly",
                true
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").datepicker(
                "disable"
            );
            $(".HMTVE090ExhibitionEntry.LblEventMei").attr("readonly", true);
            $(".HMTVE090ExhibitionEntry.LblEventMei").attr("disabled", true);
            $(".HMTVE090ExhibitionEntry.chkBaseflag").attr("disabled", true);
            $(".HMTVE090ExhibitionEntry.btnEdit").hide();
            $(".HMTVE090ExhibitionEntry.btnCancel").hide();
            $(".HMTVE090ExhibitionEntry.btnInsert").hide();
            $(".HMTVE090ExhibitionEntry.btnUpdate").show();
            $(".HMTVE090ExhibitionEntry.btnDelete").show();
        } else if (flag == "Insert") {
            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val(
                new Date(date["start"]).Format("yyyy/MM/dd")
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val("");
            $(".HMTVE090ExhibitionEntry.LblEventMei").val("");
            $(".HMTVE090ExhibitionEntry.chkBaseflag").get(0).checked =
                date["BASE_FLG"];

            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").attr(
                "readonly",
                false
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").datepicker(
                "enable"
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").attr(
                "readonly",
                false
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").datepicker(
                "enable"
            );
            $(".HMTVE090ExhibitionEntry.LblEventMei").attr("readonly", false);
            $(".HMTVE090ExhibitionEntry.LblEventMei").attr("disabled", false);
            $(".HMTVE090ExhibitionEntry.chkBaseflag").attr("disabled", false);
            $(".HMTVE090ExhibitionEntry.btnEdit").hide();
            $(".HMTVE090ExhibitionEntry.btnCancel").hide();
            $(".HMTVE090ExhibitionEntry.btnInsert").show();
            $(".HMTVE090ExhibitionEntry.btnUpdate").hide();
            $(".HMTVE090ExhibitionEntry.btnDelete").hide();
        } else if (flag == "Edit") {
            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val(
                new Date(date["start"]).Format("yyyy/MM/dd")
            );
            if (date["start"] >= date["end"]) {
                $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val("");
            } else {
                $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val(
                    new Date(date["end"].substring(0, 10)).Format("yyyy/MM/dd")
                );
            }

            $(".HMTVE090ExhibitionEntry.LblEventMei").val(date["title"]);
            $(".HMTVE090ExhibitionEntry.chkBaseflag").get(0).checked =
                date["BASE_FLG"];

            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").attr(
                "readonly",
                true
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").datepicker(
                "disable"
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").attr(
                "readonly",
                false
            );
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").datepicker(
                "enable"
            );
            $(".HMTVE090ExhibitionEntry.LblEventMei").attr("readonly", false);
            $(".HMTVE090ExhibitionEntry.LblEventMei").attr("disabled", false);
            $(".HMTVE090ExhibitionEntry.chkBaseflag").attr("disabled", false);
            $(".HMTVE090ExhibitionEntry.btnEdit").show();
            $(".HMTVE090ExhibitionEntry.btnCancel").show();
            $(".HMTVE090ExhibitionEntry.btnInsert").hide();
            $(".HMTVE090ExhibitionEntry.btnUpdate").hide();
            $(".HMTVE090ExhibitionEntry.btnDelete").hide();
        }
        $(".HMTVE090ExhibitionEntry.dtv").show();
    };
    // '**********************************************************************
    // '処 理 名：編集ボタンクリック
    // '関 数 名：BtnUpdate_OnClick
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：編集ボタンクリックの場合,EditItemTemplateが表示される
    // '**********************************************************************
    me.BtnUpdate_OnClick = function () {
        me.BindDetailView("Edit");
    };
    // '**********************************************************************
    // '処 理 名：キャンセルボタンクリック
    // '関 数 名：BtnCancel_OnClick
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：キャンセルボタンクリックの場合,ItemTemplateが表示される
    // '**********************************************************************
    me.BtnCancel_OnClick = function () {
        me.BindDetailView("ReadOnly");
    };
    // '**********************************************************************
    // '処 理 名：更新ボタンクリック
    // '関 数 名：BtnEdit_OnClick
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：更新ボタンクリックの場合,更新処理を行う
    // '**********************************************************************
    me.BtnEdit_OnClick = function () {
        var url = me.sys_id + "/" + me.id + "/btnEditOnClick";
        var data = {
            IVENT_NM: $(".HMTVE090ExhibitionEntry.LblEventMei").val(),
            STDT: new Date(
                $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val()
            ).Format("yyyyMMdd"),
            END_DATE: new Date(
                $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val()
            ).Format("yyyyMMdd"),
            BASE_FLG: $(".HMTVE090ExhibitionEntry.chkBaseflag:checked").val()
                ? 1
                : 0,
            CREATE_DATE: me.CREATE_DATE,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //現在の基準日の再表示
                me.Calendar_DayRender();
                me.setEventWidth();
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    // 画面項目NO7．展示会データ詳細(DetailsView)は非表示
                    $(".HMTVE090ExhibitionEntry.dtv").hide();

                    // Calendarの選択された日付クリアする
                    me.calendar.unselect();
                    me.CREATE_DATE = null;
                    me.selected = null;
                };
                // 完了メッセージの表示
                me.clsComFnc.FncMsgBox("I0016");
            } else {
                me.clsComFnc.FncMsgBox("E9999", "更新処理できません！");
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：追加ボタンクリック
    // '関 数 名：BtnInsert_OnClick
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：追加ボタンクリックの場合,追加処理を行う
    // '**********************************************************************
    me.BtnInsert_OnClick = function () {
        var url = me.sys_id + "/" + me.id + "/btnInsertOnClick";
        var data = {
            IVENT_NM: $(".HMTVE090ExhibitionEntry.LblEventMei").val(),
            STDT: new Date(
                $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val()
            ).Format("yyyyMMdd"),
            END_DATE: new Date(
                $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val()
            ).Format("yyyyMMdd"),
            BASE_FLG: $(".HMTVE090ExhibitionEntry.chkBaseflag:checked").val()
                ? 1
                : 0,
            CREATE_DATE: me.CREATE_DATE,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //現在の基準日の再表示
                me.Calendar_DayRender();
                me.setEventWidth();

                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    // 画面項目NO7．展示会データ詳細(DetailsView)は非表示
                    $(".HMTVE090ExhibitionEntry.dtv").hide();

                    // Calendarの選択された日付クリアする
                    me.calendar.unselect();

                    me.selected = null;
                };
                // 完了メッセージの表示
                me.clsComFnc.FncMsgBox("I0016");
            } else {
                if (result["error"] == "E9999") {
                    //エラー項目にフォーカス移動
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE090ExhibitionEntry.txtExhibitStartDate"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "追加データは既に存在します！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", "追加処理できません！");
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：削除ボタンクリック
    // '関 数 名：BtnDelete_OnClick
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：削除ボタンクリックの場合,削除処理を行う
    // '**********************************************************************
    me.BtnDelete_OnClick = function () {
        var url = me.sys_id + "/" + me.id + "/btnDeleteOnClick";
        var data = {
            STDT: new Date(
                $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val()
            ).Format("yyyyMMdd"),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //現在の基準日の再表示
                me.Calendar_DayRender();
                me.setEventWidth();

                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    // 画面項目NO7．展示会データ詳細(DetailsView)は非表示
                    $(".HMTVE090ExhibitionEntry.dtv").hide();

                    // Calendarの選択された日付クリアする
                    me.calendar.unselect();

                    me.selected = null;
                };
                // 完了メッセージの表示
                me.clsComFnc.FncMsgBox("I0017");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：DetailsViewのデータソース設定
    //
    // '関 数 名：BindDetailView
    // '戻 り 値：なし
    //
    // '処理説明：DetailsViewのデータソース設定
    //
    // '**********************************************************************
    me.BindDetailView = function (mode) {
        // 展示会データを取得する
        var url = me.sys_id + "/" + me.id + "/bindDetailView";
        var data = {
            STDT: me.selected.startStr.replaceAll("-", ""),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["data"].length > 0) {
                    // 抽出データ＝1件の場合
                    // データバインド
                    me.ChangeMode(mode, result["data"][0]);

                    $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").trigger(
                        "focus"
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.inputCheck = function () {
        // 画面項目No8が未入力の場合
        if (
            $.trim($(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val()) ==
            ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE090ExhibitionEntry.TxtExhibitStartDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を入力してください。"
            );
            return false;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val())
            ) > 10
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE090ExhibitionEntry.TxtExhibitStartDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)は指定されている桁数をオーバーしています。"
            );
            return false;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val())
            ) < 10
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE090ExhibitionEntry.TxtExhibitStartDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "'YYYY/MM/DD'書式のようにご入力ください。"
            );
            return false;
        } else {
            if (
                !me.clsComFnc.CheckDate(
                    $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate")
                )
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE090ExhibitionEntry.TxtExhibitStartDate"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "展示会開催期間(範囲開始)の入力値が不正です！"
                );
                return false;
            }
            if (
                $.trim(
                    $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val()
                ).substring(4, 5) != "/" ||
                $.trim(
                    $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val()
                ).substring(7, 8) != "/"
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE090ExhibitionEntry.TxtExhibitStartDate"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "'YYYY/MM/DD'書式のようにご入力ください。"
                );
                return false;
            }
        }
        //画面項目NO9が未入力の場合
        if (
            $.trim($(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val()) == ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE090ExhibitionEntry.TxtExhibitEndDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を入力してください。"
            );
            return false;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val())
            ) > 10
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE090ExhibitionEntry.TxtExhibitEndDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)は指定されている桁数をオーバーしています。"
            );
            return false;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val())
            ) < 10
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE090ExhibitionEntry.TxtExhibitEndDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "'YYYY/MM/DD'書式のようにご入力ください。"
            );
            return false;
        } else {
            if (
                !me.clsComFnc.CheckDate(
                    $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate")
                )
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE090ExhibitionEntry.TxtExhibitEndDate"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "展示会開催期間(範囲終了)の入力値が不正です！"
                );
                return false;
            }
            if (
                $.trim(
                    $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val()
                ).substring(4, 5) != "/" ||
                $.trim(
                    $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val()
                ).substring(7, 8) != "/"
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE090ExhibitionEntry.TxtExhibitEndDate"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "'YYYY/MM/DD'書式のようにご入力ください。"
                );
                return false;
            }
        }

        // 時間間隔数を取得する
        if (
            $(".HMTVE090ExhibitionEntry.TxtExhibitEndDate").val() <
            $(".HMTVE090ExhibitionEntry.TxtExhibitStartDate").val()
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE090ExhibitionEntry.TxtExhibitEndDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間の大小関係が不正です！"
            );
            return false;
        }

        // 画面項目NO10が未入力の場合
        if ($.trim($(".HMTVE090ExhibitionEntry.LblEventMei").val()) == "") {
            me.clsComFnc.ObjFocus = $(".HMTVE090ExhibitionEntry.LblEventMei");
            me.clsComFnc.FncMsgBox("W9999", "イベント名を入力してください。");
            return false;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE090ExhibitionEntry.LblEventMei").val())
            ) > 100
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE090ExhibitionEntry.LblEventMei");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "イベント名は指定されている桁数をオーバーしています。"
            );
            return false;
        }
        return true;
    };
    me.setEventWidth = function () {
        var eles1 = $(".HMTVE090ExhibitionEntry .fc-event-title.fc-sticky");
        var eles2 = $(
            ".HMTVE090ExhibitionEntry .fc-daygrid-event.fc-daygrid-block-event"
        );
        for (var i = 0; i < eles1.length; i++) {
            var ele = eles1[i];
            ele.style.width = eles2[i].offsetWidth + "px";
        }
    };

    me.getCalDate = function () {
        var curDate = me.calendar.getDate();
        var STDT =
            curDate.getFullYear().toString() +
            me.pad(curDate.getMonth() + 1, 2).toString() +
            "01";
        var ED = new Date(curDate.getFullYear(), curDate.getMonth() + 1, 0);
        var EDDT = ED.toLocaleDateString("en-CA").replaceAll("-", "");
        var date = {
            STDT: STDT,
            EDDT: EDDT,
        };
        return date;
    };

    me.pad = function (num, size) {
        num = num.toString();
        while (num.length < size) num = "0" + num;
        return num;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE090ExhibitionEntry = new HMTVE.HMTVE090ExhibitionEntry();
    o_HMTVE_HMTVE090ExhibitionEntry.load();
});
