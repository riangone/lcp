Namespace.register("HMDPS.HMDPS700TorihikisakiSearch");

HMDPS.HMDPS700TorihikisakiSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.hmdps = new HMDPS.HMDPS();
    me.id = 'HMDPS700TorihikisakiSearch';

    // ========== 変数 start ==========

    me.grid_id = "#HMDPS_HMDPS700TorihikisakiSearch_sprItyp";
    me.sys_id = "HMDPS";
    me.g_url = me.sys_id + '/' + me.id + '/' + 'btnHyouji_Click';
    me.option =
    {
        rowNum: 0,
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40
    };

    me.colModel = [
        {
            name: 'ATO_DTRPITCD',
            label: '取引先コード',
            index: 'ATO_DTRPITCD',
            width: 113,
            align: 'left',
            sortable: false
        },
        {
            name: 'ATO_DTRPTBNM',
            label: '取引先名称',
            index: 'ATO_DTRPTBNM',
            width: 237,
            align: 'left',
            sortable: false
        }];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push(
        {
            id: ".HMDPS700TorihikisakiSearch.btnView",
            type: "button",
            handle: ""
        });

    //選択ボタン
    me.controls.push(
        {
            id: ".HMDPS700TorihikisakiSearch.btnSelect",
            type: "button",
            handle: ""
        });

    //戻るボタン
    me.controls.push(
        {
            id: ".HMDPS700TorihikisakiSearch.btnClose",
            type: "button",
            handle: ""
        });

    //ShifキーとTabキーのバインド
    me.hmdps.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.hmdps.TabKeyDown(me.id);

    //Enterキーのバインド
    me.hmdps.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：表示ボタン押下時
    $('.HMDPS700TorihikisakiSearch.btnView').click(function () {
        me.btnView_Click();
    });
    //処理説明：選択ボタン押下時
    $('.HMDPS700TorihikisakiSearch.btnSelect').click(function () {
        me.windowClose();
    });
    //処理説明：戻るボタン押下時
    $('.HMDPS700TorihikisakiSearch.btnClose').click(function () {
        $("#HMDPS700TorihikisakiSearchDialogDiv").dialog("close");
    });

    $(".HMDPS700TorihikisakiSearch.txtTorihiki").on("focus",function() {
        //テキストエリアを全選択する
        $(this).select();
    });

    $(".HMDPS700TorihikisakiSearch.txtTorihiki").blur(function () {
        me.hmdps.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $('.HMDPS700TorihikisakiSearch.txtTorihiki').on('keydown', function (e) {
        var key = e.which;
        if (key == 13 || key == 9) {
            e.preventDefault();

            $('.ui-dialog-buttons').find('.ui-button').trigger("focus");
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.HMDPS700TorihikisakiSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HMDPS700TorihikisakiSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HMDPS700TorihikisakiSearch_load = function () {
        //初期設定処理
        me.SubFirstSet();

        gdmz.common.jqgrid.init(me.grid_id, me.g_url, me.colModel, '', '', me.option);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 422);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 312);
        $('#HMDPS_HMDPS700TorihikisakiSearch_sprItyp_rn').html('№');
        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam",
            {

                ondblClickRow: function (_rowId, _iRow, _iCol, _e) {
                    //選択値の設定
                    if (me.FncSetRtnData() != true) {
                        return;
                    }

                    //閉じる
                    $("#HMDPS700TorihikisakiSearchDialogDiv").dialog("close");
                },
                onSelectRow: function (rowId, _status, _e) {
                    $(me.grid_id + " tr#" + rowId).on('keydown', function (e) {
                        var key = e.which;
                        e.preventDefault();
                        if (key == 9 && e.shiftKey == false) {
                            $(".HMDPS700TorihikisakiSearch.btnSelect").trigger("focus");
                        }
                        else
                            if (key == 9 && e.shiftKey == true) {
                                $(".HMDPS700TorihikisakiSearch.btnView").trigger("focus");
                            }
                    });
                }
            });
        $(me.grid_id).jqGrid('bindKeys',
            {
                onEnter: function (_rowid) {
                    //選択値の設定
                    if (me.FncSetRtnData() != true) {
                        return;
                    }

                    //閉じる
                    $("#HMDPS700TorihikisakiSearchDialogDiv").dialog("close");
                }
            });

        $("#RtnCD").html('-1');
    };

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnView_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：表示ボタンの処理
    //'**********************************************************************
    me.btnView_Click = function () {

        var txtTorihikiCode = $.trim($(".HMDPS700TorihikisakiSearch.txtTorihikiCode").val());
        var txtTorihikiName = $.trim($(".HMDPS700TorihikisakiSearch.txtTorihikiName").val());
        var txtTorihikiKana = $.trim($(".HMDPS700TorihikisakiSearch.txtTorihikiKana").val());

        var data =
        {
            'txtTorihikiCode': txtTorihikiCode,
            'txtTorihikiName': txtTorihikiName,
            'txtTorihikiKana': txtTorihikiKana,
        };

        var complete_fun = function (returnFLG, result) {
            if (result['error']) {
                me.clsComFnc.FncMsgBox("E9999", result['error']);
                return;
            }

            if (returnFLG == "nodata") {
                me.SubFirstSet();
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            }
            else {
                //選択ボタンが表示されます。
                $(".HMDPS700TorihikisakiSearch.btnSelect").show();
                $(".HMDPS700TorihikisakiSearch.txtTorihiki.txtTorihikiCode").trigger("focus");
                $('.HMDPS700TorihikisakiSearch .sprItyp').css('visibility', 'visible');
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);

    };

    //'**********************************************************************
    //'処 理 名：取引先グリッド行選択のイベント
    //'関 数 名：windowClose
    //'戻 り 値：なし
    //'処理説明：取引先グリッド行選択の処理
    //'**********************************************************************
    me.windowClose = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        //閉じる
        $("#HMDPS700TorihikisakiSearchDialogDiv").dialog("close");

    };

    //**********************************************************************
    //処 理 名：選択データの設定
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：True ：正常
    //       　False：異常
    //処理説明：選択したデータを構造体に設定する。
    //**********************************************************************
    me.FncSetRtnData = function () {
        var SelectRow = $(me.grid_id).jqGrid('getGridParam', 'selrow');
        if (SelectRow == null) {
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            $('.HMDPS700TorihikisakiSearch .sprItyp').css('visibility', 'hidden');
            return false;
        }
        else {
            var rowData = $(me.grid_id).jqGrid('getRowData', SelectRow);
            if (rowData && $.trim(rowData['ATO_DTRPITCD']) != '') {
                //リターン値
                $("#RtnCD").html('1');
                //---取引先コード---
                $("#KensakuCD").html($.trim(rowData['ATO_DTRPITCD']));
                //---取引先名---
                $("#KensakuNM").html($.trim(rowData['ATO_DTRPTBNM']));
            }
            else {
                return false;
            }
        }

        return true;
    };

    //**********************************************************************
    //処 理 名：初期設定処理
    //関 数 名：SubFirstSet
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定処理を行う。
    //**********************************************************************
    me.SubFirstSet = function () {
        //フォーカスの設定
        $('.HMDPS700TorihikisakiSearch.txtTorihiki.txtTorihikiCode').trigger("focus");
        //選択ボタンは表示されません。
        $(".HMDPS700TorihikisakiSearch.btnSelect").hide();
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMDPS_HMDPS700TorihikisakiSearch = new HMDPS.HMDPS700TorihikisakiSearch();
    o_HMDPS_HMDPS700TorihikisakiSearch.load();
});
