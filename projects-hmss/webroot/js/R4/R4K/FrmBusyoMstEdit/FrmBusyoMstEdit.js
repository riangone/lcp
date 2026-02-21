/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150728           #2002  　　　           閉鎖日が「999999」が設定されているとき、更新ができない  FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmBusyoMstEdit");

R4.FrmBusyoMstEdit = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmBusyoMstEdit";
    me.sys_id = "R4K";
    me.fatherActionFlg = "";
    me.fatherData = new Array();
    me.validatingArr = {
        current: "",
        before: "",
    };
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    me.controls.push({
        id: ".FrmBusyoMstEdit.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmBusyoMstEdit.cmdBack",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmBusyoMstEdit.cmdAction").click(function () {
        me.fncCmdAction();
    });
    $(".FrmBusyoMstEdit.cmdBack").click(function () {
        me.fncCmdBack();
    });
    $("input.FrmBusyoMstEdit").on("focus", function () {
        me.fncFocusFrmBusyoMstEdit();
    });

    //me.packageBlurEvent();
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmBusyoMstEdit_load();
    };

    me.FrmBusyoMstEdit_load = function () {
        me.fatherActionFlg = me.o_R4_FrmBusyoMst.actionFlg;
        if (me.fatherActionFlg == "UPD") {
            $(".FrmBusyoMstEdit.txtBusyoCD").attr("readonly", "readonly");
            $(".FrmBusyoMstEdit.txtBusyoCD").css("border", "inset 1px ");
            me.fncBusyoSet();
        }
    };

    //--click event functions--
    me.fncCmdAction = function () {
        switch (me.fatherActionFlg) {
            case "INS":
                //--insert--
                //入力ﾁｪｯｸ
                if (!me.fncInputChk()) {
                    return;
                }

                //存在ﾁｪｯｸ
                var tmp_url =
                    me.sys_id + "/" + "FrmBusyoMstEdit/fncExistsCheck";
                data1 = {
                    busyoCd: $(".FrmBusyoMstEdit.txtBusyoCD")
                        .val()
                        .toString()
                        .trimEnd(),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"]) {
                        if (result["row"] > 0) {
                            $(".FrmBusyoMstEdit.txtBusyoCD").trigger("focus");
                            clsComFnc.FncMsgBox("W0013", "部署コード");
                            return;
                        }
                        //確認ﾒｯｾｰｼﾞ表示
                        clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
                        clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
                        clsComFnc.FncMsgBox("QY010");
                    }
                };
                me.ajax.send(tmp_url, data1, 1);
                break;
            case "UPD":
                //入力ﾁｪｯｸ
                if (!me.fncInputChk()) {
                    return;
                }

                //確認ﾒｯｾｰｼﾞ表示
                clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
                clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
                clsComFnc.FncMsgBox("QY010");
                break;
        }
    };
    me.fncCmdBack = function () {
        me.o_R4_FrmBusyoMst.closeSubFormDialog_fnc();
    };

    //--focus event functions--
    me.fncFocusFrmBusyoMstEdit = function () {
        var tmpA = document.activeElement;
        me.validatingArr["before"] = me.validatingArr["current"];
        me.validatingArr["current"] = tmpA;
        if (me.validatingArr["before"] && me.validatingArr["current"]) {
            if (
                me.validatingArr["before"].className !=
                me.validatingArr["current"].className
            ) {
                me.fncPackageBlurEvent();
            }
        }
    };

    //--blur event functions--
    me.fncPackageBlurEvent = function () {
        $(".FrmBusyoMstEdit.txtBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtBusyoNM").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtBusyoKN").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtBusyoRK").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtKkrBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtCnvBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtTenpoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtSyukeiKB").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtTorikomiBusyoKB").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtManeger_CD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtStartDate").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtEndDate").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtDsp_SeqNO").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtPRN_KB1").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtPRN_KB2").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtPRN_KB3").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtPRN_KB4").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtPRN_KB5").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtPRN_KB6").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtHknSytDspKB").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtJissekiOutFlg").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmBusyoMstEdit.txtBusyoKB").css(clsComFnc.GC_COLOR_NORMAL);
    };

    //--functions--
    me.fncInputChk = function () {
        var intRtn = 0;
        //部署コード
        if (me.fatherActionFlg == "INS") {
            intRtn = clsComFnc.FncTextCheck(
                $(".FrmBusyoMstEdit.txtBusyoCD"),
                1,
                clsComFnc.INPUTTYPE.CHAR2
            );
            if (intRtn < 0) {
                $(".FrmBusyoMstEdit.txtBusyoCD").trigger("focus");
                $(".FrmBusyoMstEdit.txtBusyoCD").css(clsComFnc.GC_COLOR_ERROR);
                me.subMsgOutput(
                    intRtn,
                    "部署コード",
                    $(".FrmBusyoMstEdit.txtBusyoCD")
                );
                return false;
            }
        }
        //部署名
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtBusyoNM"),
            1,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtBusyoNM").trigger("focus");
            $(".FrmBusyoMstEdit.txtBusyoNM").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(intRtn, "部署名", $(".FrmBusyoMstEdit.txtBusyoNM"));
            return false;
        }
        //部署カナ名
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtBusyoKN"),
            1,
            clsComFnc.INPUTTYPE.CHAR5
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtBusyoKN").trigger("focus");
            $(".FrmBusyoMstEdit.txtBusyoKN").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "部署カナ名",
                $(".FrmBusyoMstEdit.txtBusyoKN")
            );
            return false;
        }
        //部署略称名
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtBusyoRK"),
            1,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtBusyoRK").trigger("focus");
            $(".FrmBusyoMstEdit.txtBusyoRK").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "部署略称名",
                $(".FrmBusyoMstEdit.txtBusyoRK")
            );
            return false;
        }

        //括り部署
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtKkrBusyoCD"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtKkrBusyoCD").trigger("focus");
            $(".FrmBusyoMstEdit.txtKkrBusyoCD").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "括り部署",
                $(".FrmBusyoMstEdit.txtKkrBusyoCD")
            );
            return false;
        }
        //変換部署
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtCnvBusyoCD"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtCnvBusyoCD").trigger("focus");
            $(".FrmBusyoMstEdit.txtCnvBusyoCD").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "変換部署",
                $(".FrmBusyoMstEdit.txtCnvBusyoCD")
            );
            return false;
        }
        //店舗コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtTenpoCD"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtTenpoCD").trigger("focus");
            $(".FrmBusyoMstEdit.txtTenpoCD").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "店舗コード",
                $(".FrmBusyoMstEdit.txtTenpoCD")
            );
            return false;
        }
        //集計部署区分
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtSyukeiKB"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtSyukeiKB").trigger("focus");
            $(".FrmBusyoMstEdit.txtSyukeiKB").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "集計部署区分",
                $(".FrmBusyoMstEdit.txtSyukeiKB")
            );
            return false;
        }
        //取込部署区分
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtTorikomiBusyoKB"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtTorikomiBusyoKB").trigger("focus");
            $(".FrmBusyoMstEdit.txtTorikomiBusyoKB").css(
                clsComFnc.GC_COLOR_ERROR
            );
            me.subMsgOutput(
                intRtn,
                "取込部署区分",
                $(".FrmBusyoMstEdit.txtTorikomiBusyoKB")
            );
            return false;
        }
        //管理者社員コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtManeger_CD"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtManeger_CD").trigger("focus");
            $(".FrmBusyoMstEdit.txtManeger_CD").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "管理者社員コード",
                $(".FrmBusyoMstEdit.txtManeger_CD")
            );
            return false;
        }

        //設立日
        if ($(".FrmBusyoMstEdit.txtStartDate").val() != "") {
            if ($(".FrmBusyoMstEdit.txtStartDate").val().indexOf("/") < 0) {
                var tmpDate = $(".FrmBusyoMstEdit.txtStartDate")
                    .val()
                    .substr(0, 8);
                var tmpYear = tmpDate.substr(0, 4);
                var tmpMonth = tmpDate.substr(4, 2);
                var tmpDay = tmpDate.substr(6, 2);
                $(".FrmBusyoMstEdit.txtStartDate").val(
                    tmpYear + "/" + tmpMonth + "/" + tmpDay
                );
            }

            intRtn = clsComFnc.FncTextCheck(
                $(".FrmBusyoMstEdit.txtStartDate"),
                0,
                clsComFnc.INPUTTYPE.DATE1
            );
            var tf = me.isDate($(".FrmBusyoMstEdit.txtStartDate").val());
            if (intRtn < 0 || !tf) {
                $(".FrmBusyoMstEdit.txtStartDate").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                me.subMsgOutput(
                    -2,
                    "設立日",
                    $(".FrmBusyoMstEdit.txtStartDate")
                );
                return false;
            }
        }

        //閉鎖日
        if (
            $(".FrmBusyoMstEdit.txtEndDate").val() != "" &&
            $(".FrmBusyoMstEdit.txtEndDate").val() != "99999999"
        ) {
            if ($(".FrmBusyoMstEdit.txtEndDate").val().indexOf("/") < 0) {
                var tmpDate = $(".FrmBusyoMstEdit.txtEndDate")
                    .val()
                    .substr(0, 8);
                var tmpYear = tmpDate.substr(0, 4);
                var tmpMonth = tmpDate.substr(4, 2);
                var tmpDay = tmpDate.substr(6, 2);
                $(".FrmBusyoMstEdit.txtEndDate").val(
                    tmpYear + "/" + tmpMonth + "/" + tmpDay
                );
            }

            intRtn = clsComFnc.FncTextCheck(
                $(".FrmBusyoMstEdit.txtEndDate"),
                0,
                clsComFnc.INPUTTYPE.DATE1
            );
            var tf = me.isDate($(".FrmBusyoMstEdit.txtEndDate").val());
            if (intRtn < 0 || !tf) {
                $(".FrmBusyoMstEdit.txtEndDate").trigger("focus");
                $(".FrmBusyoMstEdit.txtEndDate").css(clsComFnc.GC_COLOR_ERROR);
                me.subMsgOutput(-2, "閉鎖日", $(".FrmBusyoMstEdit.txtEndDate"));
                return false;
            }

            //compare
            if (
                $(".FrmBusyoMstEdit.txtEndDate").val() <
                $(".FrmBusyoMstEdit.txtStartDate").val()
            ) {
                $(".FrmBusyoMstEdit.txtEndDate").css(clsComFnc.GC_COLOR_ERROR);
                $(".FrmBusyoMstEdit.txtStartDate").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                clsComFnc.FncMsgBox("W0006", "設立日/閉鎖日");
                return false;
            }
        }

        //表示順位
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtDsp_SeqNO"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtDsp_SeqNO").trigger("focus");
            $(".FrmBusyoMstEdit.txtDsp_SeqNO").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "表示順位",
                $(".FrmBusyoMstEdit.txtDsp_SeqNO")
            );
            return false;
        }

        //新車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtPRN_KB1"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtPRN_KB1").trigger("focus");
            $(".FrmBusyoMstEdit.txtPRN_KB1").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "新車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ",
                $(".FrmBusyoMstEdit.txtPRN_KB1")
            );
            return false;
        }

        //中古車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtPRN_KB2"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtPRN_KB2").trigger("focus");
            $(".FrmBusyoMstEdit.txtPRN_KB2").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "中古車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ",
                $(".FrmBusyoMstEdit.txtPRN_KB2")
            );
            return false;
        }

        //整備ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtPRN_KB3"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtPRN_KB3").trigger("focus");
            $(".FrmBusyoMstEdit.txtPRN_KB3").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "整備ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ",
                $(".FrmBusyoMstEdit.txtPRN_KB3")
            );
            return false;
        }

        //損益科目明細出力ﾌﾗｸﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtPRN_KB4"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtPRN_KB4").trigger("focus");
            $(".FrmBusyoMstEdit.txtPRN_KB4").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "損益科目明細出力ﾌﾗｸﾞ",
                $(".FrmBusyoMstEdit.txtPRN_KB4")
            );
            return false;
        }

        //経営成果対象ﾌﾗｸﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtPRN_KB5"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtPRN_KB5").trigger("focus");
            $(".FrmBusyoMstEdit.txtPRN_KB5").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "経営成果対象ﾌﾗｸﾞ",
                $(".FrmBusyoMstEdit.txtPRN_KB5")
            );
            return false;
        }
        //本部別実績対象ﾌﾗｸﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtPRN_KB6"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtPRN_KB6").trigger("focus");
            $(".FrmBusyoMstEdit.txtPRN_KB6").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "本部別実績対象ﾌﾗｸﾞ",
                $(".FrmBusyoMstEdit.txtPRN_KB6")
            );
            return false;
        }

        //保険収手固定費カバー率用表示ﾌﾗｸﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtHknSytDspKB"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtHknSytDspKB").trigger("focus");
            $(".FrmBusyoMstEdit.txtHknSytDspKB").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "保険収手固定費カバー率用表示ﾌﾗｸﾞ",
                $(".FrmBusyoMstEdit.txtHknSytDspKB")
            );
            return false;
        }

        //実績集計表出力フラグ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtJissekiOutFlg"),
            0,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtJissekiOutFlg").trigger("focus");
            $(".FrmBusyoMstEdit.txtJissekiOutFlg").css(
                clsComFnc.GC_COLOR_ERROR
            );
            me.subMsgOutput(
                intRtn,
                "実績集計表出力フラグ",
                $(".FrmBusyoMstEdit.txtJissekiOutFlg")
            );
            return false;
        }

        //部署区分
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmBusyoMstEdit.txtBusyoKB"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmBusyoMstEdit.txtBusyoKB").trigger("focus");
            $(".FrmBusyoMstEdit.txtBusyoKB").css(clsComFnc.GC_COLOR_ERROR);
            me.subMsgOutput(
                intRtn,
                "部署区分",
                $(".FrmBusyoMstEdit.txtBusyoKB")
            );
            return false;
        }
        return true;
    };
    me.subMsgOutput = function (intErrMsgNo, strErrMsg, formObj) {
        switch (intErrMsgNo) {
            case -1:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0001", strErrMsg);

                break;
            case -2:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0002", strErrMsg);
                break;
            case -3:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0003", strErrMsg);
                break;
            case -6:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0006", strErrMsg);
                break;
            case -7:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0007", strErrMsg);
                break;
            case -8:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0008", strErrMsg);
                break;
            case -9:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W9999", strErrMsg);
                break;
            case -15:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W00015", strErrMsg);
                break;
        }
    };
    me.isDate = function (date) {
        var ttdate = date.split("/");
        switch (ttdate[1]) {
            case "01":
            case "03":
            case "05":
            case "07":
            case "08":
            case "10":
            case "12":
                if (ttdate[2] > 31) {
                    return false;
                }
                break;
            case "04":
            case "06":
            case "09":
            case "11":
                if (ttdate[2] > 30) {
                    return false;
                }
                break;
            case "02":
                if (ttdate[2] > 29) {
                    return false;
                }
        }
        return true;
    };

    me.YesActionFnc = function () {
        //SQLを発行
        switch (me.fatherActionFlg) {
            case "INS":
                me.fncInsertBusyo();
                break;
            case "UPD":
                me.fncUpdateBusyo();
                break;
        }
    };
    me.NoActionFnc = function () {
        return;
    };

    me.fncUpdateBusyo = function () {
        //--update--
        var tmp_url = me.sys_id + "/" + "FrmBusyoMstEdit/fncUpdateBusyo";
        var data1 = me.getUpdateData();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.o_R4_FrmBusyoMst.closeSubFormDialog_fnc();
                me.o_R4_FrmBusyoMst.fncBusyoSearchButtonClick();
            } else {
                clsComFnc.FncMsgBox("E0007");
                clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(tmp_url, data1, 1);
    };
    me.fncInsertBusyo = function () {
        //--insert--
        var url = me.sys_id + "/" + "FrmBusyoMstEdit/fncInsertBusyo";
        var tArr = {};
        for (var i = 0; i < 22; i++) {
            var tmpName = $("input.FrmBusyoMstEdit")[i].classList[1];
            tArr[tmpName] = $("input.FrmBusyoMstEdit")[i].value;
        }

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.clearForm();
                me.o_R4_FrmBusyoMst.fncBusyoSearchButtonClick();
            } else {
                clsComFnc.FncMsgBox("E0007");
                clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };

        me.ajax.send(url, tArr, 1);
    };
    me.fncBusyoSet = function () {
        //--select--
        //表示初期値設定
        var busyoCd = me.o_R4_FrmBusyoMst.busyoCd;
        var tmp_url = me.sys_id + "/" + "FrmBusyoMstEdit/fncBusyoSet";
        data1 = {
            busyoCd: busyoCd,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.setInputData(result["data"]);
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                //clsComFnc.FncMsgBox("I0001", "", "");
            }
        };
        me.ajax.send(tmp_url, data1, 1);
    };

    me.clearForm = function () {
        $(".FrmBusyoMstEdit.txtBusyoCD").val("");
        $(".FrmBusyoMstEdit.txtBusyoNM").val("");
        $(".FrmBusyoMstEdit.txtBusyoKN").val("");
        $(".FrmBusyoMstEdit.txtBusyoRK").val("");
        $(".FrmBusyoMstEdit.txtKkrBusyoCD").val("");
        $(".FrmBusyoMstEdit.txtCnvBusyoCD").val("");
        $(".FrmBusyoMstEdit.txtTenpoCD").val("");
        $(".FrmBusyoMstEdit.txtSyukeiKB").val("");
        $(".FrmBusyoMstEdit.txtTorikomiBusyoKB").val("");
        $(".FrmBusyoMstEdit.txtManeger_CD").val("");
        $(".FrmBusyoMstEdit.txtStartDate").val("");
        $(".FrmBusyoMstEdit.txtEndDate").val("");
        $(".FrmBusyoMstEdit.txtDsp_SeqNO").val("");
        $(".FrmBusyoMstEdit.txtPRN_KB1").val("");
        $(".FrmBusyoMstEdit.txtPRN_KB2").val("");
        $(".FrmBusyoMstEdit.txtPRN_KB3").val("");
        $(".FrmBusyoMstEdit.txtPRN_KB4").val("");
        $(".FrmBusyoMstEdit.txtPRN_KB5").val("");
        $(".FrmBusyoMstEdit.txtPRN_KB6").val("");
        $(".FrmBusyoMstEdit.txtHknSytDspKB").val("");
        $(".FrmBusyoMstEdit.txtJissekiOutFlg").val("");
        $(".FrmBusyoMstEdit.txtBusyoKB").val("");
    };
    me.setInputData = function (data) {
        var tmpStartDate = "";
        var tmpEndDate = "";
        if (!(data[0]["START_DATE"] === null)) {
            tmpStartDate =
                data[0]["START_DATE"].substr(0, 4) +
                "/" +
                data[0]["START_DATE"].substr(4, 2) +
                "/" +
                data[0]["START_DATE"].substr(6, 2);
        }
        if (!(data[0]["END_DATE"] === null)) {
            //20150728 #2002 fanzhengzhou upd s.
            if (data[0]["END_DATE"] == "999999") {
                tmpEndDate = null;
            } else {
                tmpEndDate =
                    data[0]["END_DATE"].substr(0, 4) +
                    "/" +
                    data[0]["END_DATE"].substr(4, 2) +
                    "/" +
                    data[0]["END_DATE"].substr(6, 2);
            }
            //20150728 #2002 fanzhengzhou upd e.
        }

        $(".FrmBusyoMstEdit.txtBusyoCD").val(data[0]["BUSYO_CD"]);
        $(".FrmBusyoMstEdit.txtBusyoNM").val(data[0]["BUSYO_NM"]);
        $(".FrmBusyoMstEdit.txtBusyoKN").val(data[0]["BUSYO_KANANM"]);
        $(".FrmBusyoMstEdit.txtBusyoRK").val(data[0]["BUSYO_RYKNM"]);
        $(".FrmBusyoMstEdit.txtKkrBusyoCD").val(data[0]["KKR_BUSYO_CD"]);
        $(".FrmBusyoMstEdit.txtCnvBusyoCD").val(data[0]["CNV_BUSYO_CD"]);
        $(".FrmBusyoMstEdit.txtTenpoCD").val(data[0]["TENPO_CD"]);
        $(".FrmBusyoMstEdit.txtSyukeiKB").val(data[0]["SYUKEI_KB"]);
        $(".FrmBusyoMstEdit.txtTorikomiBusyoKB").val(
            data[0]["TORIKOMI_BUSYO_KB"]
        );
        $(".FrmBusyoMstEdit.txtManeger_CD").val(data[0]["MANEGER_CD"]);
        $(".FrmBusyoMstEdit.txtStartDate").val(tmpStartDate);
        //data[0]['START_DATE']);
        $(".FrmBusyoMstEdit.txtEndDate").val(tmpEndDate);
        //data[0]['END_DATE']);
        $(".FrmBusyoMstEdit.txtDsp_SeqNO").val(data[0]["DSP_SEQNO"]);
        $(".FrmBusyoMstEdit.txtPRN_KB1").val(data[0]["PRN_KB1"]);
        $(".FrmBusyoMstEdit.txtPRN_KB2").val(data[0]["PRN_KB2"]);
        $(".FrmBusyoMstEdit.txtPRN_KB3").val(data[0]["PRN_KB3"]);
        $(".FrmBusyoMstEdit.txtPRN_KB4").val(data[0]["PRN_KB4"]);
        $(".FrmBusyoMstEdit.txtPRN_KB5").val(data[0]["PRN_KB5"]);
        $(".FrmBusyoMstEdit.txtPRN_KB6").val(data[0]["PRN_KB6"]);
        $(".FrmBusyoMstEdit.txtHknSytDspKB").val(data[0]["HKNSYT_DSP_KB"]);
        $(".FrmBusyoMstEdit.txtJissekiOutFlg").val(data[0]["JISSEKITTL_KB"]);
        $(".FrmBusyoMstEdit.txtBusyoKB").val(data[0]["BUSYO_KB"]);
    };
    me.getUpdateData = function () {
        var data_tmp = {};
        data_tmp["BUSYO_CD"] = $(".FrmBusyoMstEdit.txtBusyoCD").val();
        data_tmp["BUSYO_NM"] = $(".FrmBusyoMstEdit.txtBusyoNM").val();
        data_tmp["BUSYO_KANANM"] = $(".FrmBusyoMstEdit.txtBusyoKN").val();
        data_tmp["BUSYO_RYKNM"] = $(".FrmBusyoMstEdit.txtBusyoRK").val();
        data_tmp["KKR_BUSYO_CD"] = $(".FrmBusyoMstEdit.txtKkrBusyoCD").val();
        data_tmp["CNV_BUSYO_CD"] = $(".FrmBusyoMstEdit.txtCnvBusyoCD").val();
        data_tmp["TENPO_CD"] = $(".FrmBusyoMstEdit.txtTenpoCD").val();
        data_tmp["SYUKEI_KB"] = $(".FrmBusyoMstEdit.txtSyukeiKB").val();
        data_tmp["TORIKOMI_BUSYO_KB"] = $(
            ".FrmBusyoMstEdit.txtTorikomiBusyoKB"
        ).val();
        data_tmp["MANEGER_CD"] = $(".FrmBusyoMstEdit.txtManeger_CD").val();
        data_tmp["START_DATE"] = $(".FrmBusyoMstEdit.txtStartDate").val();
        data_tmp["END_DATE"] = $(".FrmBusyoMstEdit.txtEndDate").val();
        data_tmp["DSP_SEQNO"] = $(".FrmBusyoMstEdit.txtDsp_SeqNO").val();
        data_tmp["PRN_KB1"] = $(".FrmBusyoMstEdit.txtPRN_KB1").val();
        data_tmp["PRN_KB2"] = $(".FrmBusyoMstEdit.txtPRN_KB2").val();
        data_tmp["PRN_KB3"] = $(".FrmBusyoMstEdit.txtPRN_KB3").val();
        data_tmp["PRN_KB4"] = $(".FrmBusyoMstEdit.txtPRN_KB4").val();
        data_tmp["PRN_KB5"] = $(".FrmBusyoMstEdit.txtPRN_KB5").val();
        data_tmp["PRN_KB6"] = $(".FrmBusyoMstEdit.txtPRN_KB6").val();
        data_tmp["HKNSYT_DSP_KB"] = $(".FrmBusyoMstEdit.txtHknSytDspKB").val();
        data_tmp["JISSEKITTL_KB"] = $(
            ".FrmBusyoMstEdit.txtJissekiOutFlg"
        ).val();
        data_tmp["BUSYO_KB"] = $(".FrmBusyoMstEdit.txtBusyoKB").val();
        return data_tmp;
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmBusyoMstEdit = new R4.FrmBusyoMstEdit();

    o_R4K_R4K.FrmBusyoMst.o_R4_FrmBusyoMstEdit = o_R4_FrmBusyoMstEdit;
    o_R4_FrmBusyoMstEdit.o_R4_FrmBusyoMst = o_R4K_R4K.FrmBusyoMst;

    o_R4_FrmBusyoMstEdit.load();
});
