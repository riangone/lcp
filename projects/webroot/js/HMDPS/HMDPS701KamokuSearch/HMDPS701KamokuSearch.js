/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240329       本番障害.xlsx NO9       ダイアログ上で検索実行後に 検索条件を復元する必要なし      	LHB
 * -------------------------------------------------------------------------------------------------------
 */
Namespace.register("HMDPS.HMDPS701KamokuSearch");

HMDPS.HMDPS701KamokuSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.hmdps = new HMDPS.HMDPS();
    me.id = 'HMDPS701KamokuSearch';
    me.ajax = new gdmz.common.ajax();
    // ========== 変数 start ==========

    me.grid_id = "#HMDPS_HMDPS701KamokuSearch_sprItyp";
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
            name: 'KAMOK_CD',
            label: '科目コード',
            index: 'KAMOK_CD',
            width: 113,
            align: 'left',
            sortable: false
        },
        {
            name: 'KOUMK_CD',
            label: '項目<br/>コード',
            index: 'KOUMK_CD',
            width: 60,
            align: 'left',
            sortable: false,
        },
        {
            name: 'KMK_KUM_NM',
            label: '科目名',
            index: 'KMK_KUM_NM',
            width: 173,
            align: 'left',
            sortable: false
        }];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push(
        {
            id: ".HMDPS701KamokuSearch.btnView",
            type: "button",
            handle: ""
        });

    //選択ボタン
    me.controls.push(
        {
            id: ".HMDPS701KamokuSearch.btnSelect",
            type: "button",
            handle: ""
        });

    //戻るボタン
    me.controls.push(
        {
            id: ".HMDPS701KamokuSearch.btnClose",
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
    $('.HMDPS701KamokuSearch.btnView').click(function () {
        me.btnView_Click();
    });
    //処理説明：選択ボタン押下時
    $('.HMDPS701KamokuSearch.btnSelect').click(function () {
        me.windowClose();
    });
    //処理説明：戻るボタン押下時
    $('.HMDPS701KamokuSearch.btnClose').click(function () {
        $("#HMDPS701KamokuSearchDialogDiv").dialog("close");
    });

    $(".HMDPS701KamokuSearch.txtKamoku").on("focus",function() {
        //テキストエリアを全選択する
        $(this).select();
    });

    $(".HMDPS701KamokuSearch.txtKamoku").blur(function () {
        me.hmdps.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $('.HMDPS701KamokuSearch.txtKamoku').on('keydown', function (e) {
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
        me.HMDPS701KamokuSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HMDPS701KamokuSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HMDPS701KamokuSearch_load = function () {
        //初期設定処理
        me.SubFirstSet();

        gdmz.common.jqgrid.init(me.grid_id, me.g_url, me.colModel, '', '', me.option);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 423);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 312);
        $('#HMDPS_HMDPS701KamokuSearch_sprItyp_rn').html('№');
        // 10:伝票検索画面から値が伝わってきたのです。
        if ($("#koumkuCd").val() == '10') {
            //伝票検索画面から科目コード画面を開きます
            //項目コードの列を表示しない
            $(me.grid_id).hideCol("KOUMK_CD");
            $('#HMDPS_HMDPS701KamokuSearch_sprItyp_KMK_KUM_NM').css('width', '237');
            $('#HMDPS_HMDPS701KamokuSearch_sprItyp td:nth-child(4)').css('width', '237');

        }
        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam",
            {

                ondblClickRow: function (_rowId, _iRow, _iCol, _e) {
                    //選択値の設定
                    if (me.FncSetRtnData() != true) {
                        return;
                    }

                    //閉じる
                    $("#HMDPS701KamokuSearchDialogDiv").dialog("close");
                },
                onSelectRow: function (rowId, _status, _e) {
                    $(me.grid_id + " tr#" + rowId).on('keydown', function (e) {
                        var key = e.which;
                        e.preventDefault();
                        if (key == 9 && e.shiftKey == false) {
                            $(".HMDPS701KamokuSearch.btnSelect").trigger("focus");
                        }
                        else
                            if (key == 9 && e.shiftKey == true) {
                                $(".HMDPS701KamokuSearch.btnView").trigger("focus");
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
                    $("#HMDPS701KamokuSearchDialogDiv").dialog("close");
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
        var txtKamokuCode = $.trim($(".HMDPS701KamokuSearch.txtKamokuCode").val());
        var txtKoumokuCode = $.trim($(".HMDPS701KamokuSearch.txtKoumokuCode").val());
        var txtKamokuName = $.trim($(".HMDPS701KamokuSearch.txtKamokuName").val());
        var str = $.trim($("#koumkuCd").val());

        var data =
        {
            'txtKamokuCode': txtKamokuCode,
            'txtKoumokuCode': txtKoumokuCode,
            'txtKamokuName': txtKamokuName,
            'str': str
        };

        var complete_fun = function (returnFLG, result) {
            if (result['error']) {
                me.clsComFnc.FncMsgBox("E9999", result['error']);
                return;
            }

            if (returnFLG == "nodata") {
                //20240329 LHB UPD S
                me.SubFirstSet(true);
                //20240329 LHB UPD E
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            }
            else {
                //選択ボタンが表示されます。
                $(".HMDPS701KamokuSearch.btnSelect").show();
                $(".HMDPS701KamokuSearch.txtKamoku.txtKamokuCode").trigger("focus");
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);

    };

    //'**********************************************************************
    //'処 理 名：科目グリッド行選択のイベント
    //'関 数 名：windowClose
    //'戻 り 値：なし
    //'処理説明：科目グリッド行選択の処理
    //'**********************************************************************
    me.windowClose = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        //閉じる
        $("#HMDPS701KamokuSearchDialogDiv").dialog("close");
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
            return false;
        }
        else {
            var rowData = $(me.grid_id).jqGrid('getRowData', SelectRow);
            if (rowData && $.trim(rowData['KAMOK_CD']) != '') {
                //リターン値
                $("#RtnCD").html('1');
                //---科目コード---
                $("#KamokuCD").html($.trim(rowData['KAMOK_CD']));
                //---項目コード---
                $("#KoumkuCD").html($.trim(rowData['KOUMK_CD']));
                //---科目名---
                $("#KamokuNM").html($.trim(rowData['KMK_KUM_NM']));
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
    //20240329 LHB UPD S
    // me.SubFirstSet = function () {
    // if ($('#KamokuCD').length > 0)
    me.SubFirstSet = function (flg) {
        if ($('#KamokuCD').length > 0 && !flg) {
            //20240329 LHB UPD E
            var strKamokuCD = $('#KamokuCD').val();
            if (strKamokuCD) {
                $('.HMDPS701KamokuSearch.txtKamoku.txtKamokuCode').val(strKamokuCD);
            }
        }
        //フォーカスの設定
        $('.HMDPS701KamokuSearch.txtKamoku.txtKamokuCode').trigger("focus");
        //選択ボタンは表示されません。-
        $(".HMDPS701KamokuSearch.btnSelect").hide();
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMDPS_HMDPS701KamokuSearch = new HMDPS.HMDPS701KamokuSearch();
    o_HMDPS_HMDPS701KamokuSearch.load();
});
