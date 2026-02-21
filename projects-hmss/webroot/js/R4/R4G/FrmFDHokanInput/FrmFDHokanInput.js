/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                       担当
 * YYYYMMDD            #ID                          XXXXXX                                   GSSDL
 * 20201117            bug                             AJAX.SEND パラメータ数                     lqs
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmFDHokanInput");

R4.FrmFDHokanInput = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4G/FrmFDHokanInput";
    me.sys_id = "R4G";

    me.class_id = ".FrmFDHokanInput.body";
    me.parent_class_id = ".R4.R4-layout-center";
    me.FrmFDHokanSelect = null;
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmFDHokanInput.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmFDHokanInput.cmdBack",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    clsComFnc.TabKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //**********************************************************************
    //処 理 名：戻るボタン押下
    //関 数 名：cmdBack_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：フォームを閉じる
    //				 親画面を表示する
    //**********************************************************************
    $(".FrmFDHokanInput.cmdBack").click(function () {
        $(".FrmFDHokanSelect.subDialog").dialog("close");
    });

    //**********************************************************************
    //処 理 名：DBに内容を登録、修正、削除
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：パラメータがINS(追加)の場合は入力チェック、存在チェックの後、DBに登録
    //			     UPD(修正)の場合は名称の入力チェック後、DBを修正
    //				 DEL(削除)の場合はDBから削除
    //**********************************************************************
    $(".FrmFDHokanInput.cmdAction").click(function () {
        me.fnccmdActionClick();
    });

    //年、月、日欄が正の整数の入力チェック
    $(".FrmFDHokanInput.changeCOL.integer").numeric(
        {
            decimal: false,
            negative: false,
        },
        function () {
            this.value = "";
            // this.focus();
        }
    );

    //年、月、日欄の入力値が2桁未満、ゼロを前に埋める
    $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_Y").on(
        "blur",
        function () {
            $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_Y").val(
                $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_Y")
                    .val()
                    .padLeft(2, "0")
            );
        }
    );
    $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_M").on(
        "blur",
        function () {
            $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_M").val(
                $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_M")
                    .val()
                    .padLeft(2, "0")
            );
        }
    );
    $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_D").on(
        "blur",
        function () {
            $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_D").val(
                $(".FrmFDHokanInput.changeCOL.integer.txtSEISAKU_D")
                    .val()
                    .padLeft(2, "0")
            );
        }
    );

    //**********************************************************************
    //処 理 名：使用者と同じチェックボックス選択時
    //関 数 名：chkSYOYU_NM_SIYO_CheckedChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：使用者と同じチェックボックス選択時
    //**********************************************************************
    $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").change(function () {
        subSYOYUNMSIYOClick();
    });
    $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").change(function () {
        subSYOYUADDRSIYO();
    });
    $(".FrmFDHokanInput.chkHONKYO_ADDR_SIYO").change(function () {
        subHONKYOADDRSIYO();
    });

    //**********************************************************************
    //処 理 名：画面.所有者コードフォーカスを失った時
    //関 数 名：txtSYOYU_CD_onBlur
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面.所有者コードフォーカスを失った時
    //				  所有者コード入力後フォーカス移動時、入力の必要のない項目を非表示にする
    //**********************************************************************
    $(".FrmFDHokanInput.txtSYOYU_CD").on("blur", function () {
        if ($(".FrmFDHokanInput.txtSYOYU_CD").val().trimEnd() != "") {
            $(".FrmFDHokanInput.txtSYOYU_NM").val("");
            $(".FrmFDHokanInput.txtSYOYU_NM").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_NM").css("backgroundColor", "#DCDCDC");

            var tmpFlag = $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop(
                "checked"
            );
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").removeAttr("checked");
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("disabled", "disabled");
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").css(
                "backgroundColor",
                "#DCDCDC"
            );

            if (
                tmpFlag !=
                $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked")
            ) {
                subSYOYUNMSIYOClick();
            }

            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").removeAttr("checked");
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").css(
                "backgroundColor",
                "#DCDCDC"
            );

            var tmpFlag = $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop(
                "checked"
            );
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").removeAttr("checked");
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").css(
                "backgroundColor",
                "#DCDCDC"
            );

            if (
                tmpFlag !=
                $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop("checked")
            ) {
                subSYOYUADDRSIYO();
            }

            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").val("");
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").val("");
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").css(
                "backgroundColor",
                "#DCDCDC"
            );
        } else {
            $(".FrmFDHokanInput.txtSYOYU_NM").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_NM").css(clsComFnc.GC_COLOR_NORMAL);
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").removeAttr("disabled");
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            // $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").removeAttr("checked");
            // $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").css(clsComFnc.GC_COLOR_NORMAL);
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").removeAttr("disabled");
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").css(
                clsComFnc.GC_COLOR_NORMAL
            );
        }
    });

    //**********************************************************************
    //処 理 名：フォーカスセット
    //関 数 名：SubSetFocus
    //引    数：  objCtrl						(I) フォーカスをセットしたいコントロール
    //戻 り 値：  無し
    //処理説明：フォーカスをセットする。
    //**********************************************************************
    $(".FrmFDHokanInput.changeCOL").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        var objClass = $(this).prop("class");
        objClass = objClass.replace(/ /g, ".");
        if ($("." + objClass).prop("disabled") == false) {
            $("." + objClass).css(clsComFnc.GC_COLOR_NORMAL);
        }
    });

    //**********************************************************************
    //処 理 名：画面.使用者.氏名又は名称、画面.所有者.氏名又は名称、画面.申請者.使用者.氏名又は名称、画面.申請者.使用者.住所のフォーカス移動時
    //関 数 名：txtSHIYOU_NM_onblur
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面.使用者.氏名又は名称、画面.所有者.氏名又は名称、画面.申請者.使用者.氏名又は名称、画面.申請者.使用者.住所のフォーカス移動時
    //				  使用者名・所有者名変更時、申請者欄も変更する
    //**********************************************************************
    $(".FrmFDHokanInput.validating").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        var objClass = $(this).prop("class");
        objClass = objClass.replace(/ /g, ".");
        if ($("." + objClass).prop("disabled") == false) {
            $("." + objClass).css(clsComFnc.GC_COLOR_NORMAL);
        }

        switch (objClass) {
            case "FrmFDHokanInput.validating.txtSHIYOU_NM.Tab.Enter":
                $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").val(
                    $(".FrmFDHokanInput.txtSHIYOU_NM").val()
                );

                if (
                    $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked") ==
                    true
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").val(
                        $(".FrmFDHokanInput.txtSHIYOU_NM").val()
                    );
                }
                break;
            case "FrmFDHokanInput.validating.txtSYOYU_NM.Tab.Enter":
                if (
                    $(".FrmFDHokanInput.txtSYOYU_NM").prop("disabled") == false
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").val(
                        $(".FrmFDHokanInput.txtSYOYU_NM").val()
                    );
                }
                break;
            case "FrmFDHokanInput.validating.txtSINSEI_SIYO_NM.Tab.Enter":
                if (
                    $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked") ==
                    true
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").val(
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").val()
                    );
                }
                break;
            case "FrmFDHokanInput.validating.txtSINSEI_SIYO_ADDR.Tab.Enter":
                if (
                    $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop("checked") ==
                    true
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").val(
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_ADDR").val()
                    );
                }
                break;
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_load = me.load;
    me.load = function () {
        base_load();

        //ﾌｫｰｶｽ設定
        $(".FrmFDHokanInput.txtTesuryo").trigger("focus");

        var chumn_no = me.FrmFDHokanSelect.objArr["objfrm_PrpChumn_NO"];
        //表示初期値設定
        var funcName = "fncKeijiReport";
        var url = me.id + "/" + funcName;
        var arrayVal = {
            CHUMN_NO: chumn_no,
        };

        me.data = {
            request: arrayVal,
        };

        //when load form,the third parameter = 1 ; else parameter = 0.
        //when only one ajax,the forth parameter is blank.

        ajax.receive = function (result) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");

            if (jsonResult.json[0]["result"] == true) {
                subDataSet(jsonResult.json[0]["data"][0]);
            } else if (jsonResult.json[0]["result"] == "noData") {
                clsComFnc.FncMsgBox("I0001");
                return;
            } else if (jsonResult.json[0]["result"] == false) {
                //エラーの場合
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
        };
        // 20201117 lqs upd S
        // ajax.send(url, me.data, 1, '');
        ajax.send(url, me.data, 1);
        // 20201117 lqs upd E
        // $.ajax(
        // {
        // type : "POST",
        // url : url,
        // data : me.data,
        //
        // success : function(result)
        // {
        // var jsonResult =
        // {
        // };
        // var txtResult = '{ "json" : [' + result + ']}';
        // jsonResult = eval("(" + txtResult + ")");
        //
        // if (jsonResult.json[0]['result'] == true)
        // {
        // subDataSet(jsonResult.json[0]['data'][0]);
        // }
        // else
        // if (jsonResult.json[0]['result'] == 'noData')
        // {
        // clsComFnc.FncMsgBox("I0001");
        // return;
        // }
        // else
        // if (jsonResult.json[0]['result'] == false)
        // {
        // //エラーの場合
        // clsComFnc.FncMsgBox("E9999", jsonResult.json[0]['data']);
        // return;
        // }
        // }
        // });
    };

    //**********************************************************************
    //処 理 名：画面項目をDB値にセット
    //関 数 名：subDataSet
    //引    数： $SQL実行結果
    //戻 り 値： $array検索結果
    //処理説明：画面項目をDB値にセット
    //**********************************************************************
    function subDataSet(objDr) {
        $(".FrmFDHokanInput.lblCHUMN_NO").val(
            clsComFnc.FncNv(objDr["CHUMN_NO"])
        );
        $(".FrmFDHokanInput.lblTOU_Y_DT").val(
            clsComFnc.FncNv(objDr["TOU_Y_DT"])
        );
        $(".FrmFDHokanInput.txtTesuryo").val(clsComFnc.FncNv(objDr["TESURYO"]));

        var tmpId =
            ".FrmFDHokanInput.cboBAN_SIJI_YOT_2 option[index='" +
            clsComFnc.FncNz(objDr["BAN_SIJI_YOT_2"]) +
            "']";
        $(tmpId).prop("selected", true);

        var tmpId =
            ".FrmFDHokanInput.cboBAN_SIJI_HBN_1 option[index='" +
            clsComFnc.FncNz(objDr["BAN_SIJI_HBN_1"]) +
            "']";
        $(tmpId).prop("selected", true);

        $(".FrmFDHokanInput.txtKIBO_SRY_BUNRUI").val(
            clsComFnc.FncNv(objDr["KIBO_SRY_BUNRUI"])
        );
        $(".FrmFDHokanInput.txtKIBO_SRY_KANA").val(
            clsComFnc.FncNv(objDr["KIBO_SRY_KANA"])
        );
        $(".FrmFDHokanInput.txtKIBO_SRY_KIBO").val(
            clsComFnc.FncNv(objDr["KIBO_SRY_KIBO"])
        );
        $(".FrmFDHokanInput.txtSRY_BAN_MOJI").val(
            clsComFnc.FncNv(objDr["SRY_BAN_MOJI"])
        );
        $(".FrmFDHokanInput.txtSRY_BAN_BUNRUI").val(
            clsComFnc.FncNv(objDr["SRY_BAN_BUNRUI"])
        );
        $(".FrmFDHokanInput.txtSRY_BAN_KANA").val(
            clsComFnc.FncNv(objDr["SRY_BAN_KANA"])
        );
        $(".FrmFDHokanInput.txtSRY_BAN_SITEI").val(
            clsComFnc.FncNv(objDr["SRY_BAN_SITEI"])
        );
        $(".FrmFDHokanInput.txtSRY_BAN_SYOUBAN").val(
            clsComFnc.FncNv(objDr["SRY_BAN_SYOUBAN"])
        );
        $(".FrmFDHokanInput.txtSYADAI_NO").val(
            clsComFnc.FncNv(objDr["SYADAI_NO"])
        );
        $(".FrmFDHokanInput.txtSYOYU_CD").val(
            clsComFnc.FncNv(objDr["SYOYU_CD"])
        );
        $(".FrmFDHokanInput.txtSYOYU_SIYO").val(
            clsComFnc.FncNv(objDr["SYOYU_SIYO"])
        );

        var strShiyouNm = "";
        strShiyouNm = String(clsComFnc.FncNv(objDr["SHIYOU_NM"]));
        strShiyouNm = strShiyouNm.replace("㈱", "株式会社");
        strShiyouNm = strShiyouNm.replace("㈲", "有限会社");

        if (strShiyouNm.indexOf("株式会社") == 0) {
            if (strShiyouNm.indexOf("株式会社　") == -1) {
                //20160531 Upd Start
                strShiyouNm =
                    strShiyouNm.substring(0, 4) +
                    "　" +
                    strShiyouNm.substring(4);
                //				strShiyouNm = strShiyouNm.substr(0, 4) + strShiyouNm.substr(4);
                //20160531 Upd End
            }
        } else if (strShiyouNm.indexOf("株式会社") > 0) {
            if (strShiyouNm.indexOf("　株式会社") == -1) {
                //20160531 Upd Start
                strShiyouNm =
                    strShiyouNm.substring(0, strShiyouNm.indexOf("株式会社")) +
                    "　" +
                    strShiyouNm.substring(strShiyouNm.indexOf("株式会社"));
                //				strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("株式会社")) + strShiyouNm.substr(strShiyouNm.indexOf("株式会社"));
                //20160531 Upd End
            }
        } else if (strShiyouNm.indexOf("有限会社") == 0) {
            if (strShiyouNm.indexOf("有限会社　") == -1) {
                //20160531 Upd Start
                strShiyouNm =
                    strShiyouNm.substring(0, 4) +
                    "　" +
                    strShiyouNm.substring(4);
                //				strShiyouNm = strShiyouNm.substr(0, 4) + strShiyouNm.substr(4);
                //20160531 Upd End
            }
        } else if (strShiyouNm.indexOf("有限会社") > 0) {
            if (strShiyouNm.indexOf("　有限会社") == -1) {
                //20160531 Upd Start
                strShiyouNm =
                    strShiyouNm.substring(0, strShiyouNm.indexOf("有限会社")) +
                    "　" +
                    strShiyouNm.substring(strShiyouNm.indexOf("有限会社"));
                //				strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("有限会社")) + strShiyouNm.substr(strShiyouNm.indexOf("有限会社"));
                //20160531 Upd End
            }
        }

        //20160608 Upd Start
        //		strShiyouNm = strShiyouNm.replace("　", "");
        //		strShiyouNm = strShiyouNm.replace(" ", "");
        strShiyouNm = strShiyouNm.trimEnd();
        //20160608 Upd End

        $(".FrmFDHokanInput.txtSHIYOU_NM").val(strShiyouNm);

        if (
            String(clsComFnc.FncNv(objDr["SHIYOU_ADDR_CD"]))
                .padRight(12)
                .substring(9, 12)
                .trimEnd() == "000"
        ) {
            $(".FrmFDHokanInput.txtSHIYOU_ADDR_CD").val(
                String(clsComFnc.FncNv(objDr["SHIYOU_ADDR_CD"]))
                    .padRight(12)
                    .substring(0, 9)
                    .trimEnd()
            );
        } else {
            $(".FrmFDHokanInput.txtSHIYOU_ADDR_CD").val(
                String(clsComFnc.FncNv(objDr["SHIYOU_ADDR_CD"]))
                    .padRight(12)
                    .trimEnd()
            );
        }

        $(".FrmFDHokanInput.txtSHIYOU_ADDR_1").val(
            clsComFnc.FncNv(objDr["SHIYOU_ADDR_1"])
        );
        $(".FrmFDHokanInput.txtSHIYOU_ADDR_2").val(
            clsComFnc.FncNv(objDr["SHIYOU_ADDR_2"])
        );

        if (clsComFnc.FncNv(objDr["SYOYU_NM_SIYO"]) == "1") {
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked", "checked");
            subSYOYUNMSIYOClick();
        } else {
            var strSyoyuNm = "";
            strSyoyuNm = String(clsComFnc.FncNv(objDr["SYOYU_NM"]));
            strSyoyuNm = strSyoyuNm.replace("㈱", "株式会社");
            strSyoyuNm = strSyoyuNm.replace("㈲", "有限会社");

            if (strSyoyuNm.indexOf("株式会社") == 0) {
                if (strSyoyuNm.indexOf("株式会社　") == -1) {
                    //20160531 Upd Start
                    strSyoyuNm =
                        strSyoyuNm.substring(0, 4) +
                        "　" +
                        strSyoyuNm.substring(4);
                    //					strSyoyuNm = strSyoyuNm.substr(0, 4) + strSyoyuNm.substr(4);
                    //20160531 Upd End
                }
            } else if (strSyoyuNm.indexOf("株式会社") > 0) {
                if (strSyoyuNm.indexOf("　株式会社") == -1) {
                    //20160531 Upd Start
                    strSyoyuNm =
                        strSyoyuNm.substring(
                            0,
                            strSyoyuNm.indexOf("株式会社")
                        ) +
                        "　" +
                        strSyoyuNm.substring(strSyoyuNm.indexOf("株式会社"));
                    //					strSyoyuNm = strSyoyuNm.substr(0, strSyoyuNm.indexOf("株式会社")) + strSyoyuNm.substr(strSyoyuNm.indexOf("株式会社"));
                    //20160531 Upd End
                }
            } else if (strSyoyuNm.indexOf("有限会社") == 0) {
                if (strSyoyuNm.indexOf("有限会社　") == -1) {
                    //20160531 Upd Start
                    strSyoyuNm =
                        strSyoyuNm.substring(0, 4) +
                        "　" +
                        strSyoyuNm.substring(4);
                    //					strSyoyuNm = strSyoyuNm.substr(0, 4) + strSyoyuNm.substr(4);
                    //20160531 Upd End
                }
            } else if (strSyoyuNm.indexOf("有限会社") > 0) {
                if (strSyoyuNm.indexOf("　有限会社") == -1) {
                    //20160531 Upd Start
                    strSyoyuNm =
                        strSyoyuNm.substring(
                            0,
                            strSyoyuNm.indexOf("有限会社")
                        ) +
                        "　" +
                        strSyoyuNm.substring(strSyoyuNm.indexOf("有限会社"));
                    //					strSyoyuNm = strSyoyuNm.substr(0, strSyoyuNm.indexOf("有限会社")) + strSyoyuNm.substr(strSyoyuNm.indexOf("有限会社"));
                    //20160531 Upd End
                }
            }

            //20160608 Upd Start
            //		strSyoyuNm = strSyoyuNm.replace("　", "");
            //		strSyoyuNm= strSyoyuNm.replace(" ", "");
            strSyoyuNm = strSyoyuNm.trimEnd();
            //20160608 Upd End

            if (strShiyouNm == strSyoyuNm) {
                $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop(
                    "checked",
                    "checked"
                );
                subSYOYUNMSIYOClick();
            } else {
                $(".FrmFDHokanInput.txtSYOYU_NM").val(strSyoyuNm);
            }
        }

        if (clsComFnc.FncNv(objDr["SYOYU_ADDR_SIYO"]) == "1") {
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop("checked", "checked");
            subSYOYUADDRSIYO();
        } else {
            if (
                String(clsComFnc.FncNv(objDr["SHIYOU_ADDR_CD"])) ==
                    String(clsComFnc.FncNv(objDr["SYOYU_ADDR_CD"])) &&
                String(clsComFnc.FncNv(objDr["SHIYOU_ADDR_1"])) ==
                    String(clsComFnc.FncNv(objDr["SYOYU_ADDR_1"])) &&
                String(clsComFnc.FncNv(objDr["SHIYOU_ADDR_2"])) ==
                    String(clsComFnc.FncNv(objDr["SYOYU_ADDR_2"]))
            ) {
                $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop(
                    "checked",
                    "checked"
                );
                subSYOYUADDRSIYO();
            } else {
                if (
                    String(clsComFnc.FncNv(objDr["SYOYU_ADDR_CD"]))
                        .padRight(12)
                        .substring(9, 12)
                        .trimEnd() == "000"
                ) {
                    $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").val(
                        String(clsComFnc.FncNv(objDr["SYOYU_ADDR_CD"]))
                            .padRight(12)
                            .substring(0, 9)
                            .trimEnd()
                    );
                } else {
                    $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").val(
                        String(clsComFnc.FncNv(objDr["SYOYU_ADDR_CD"]))
                            .padRight(12)
                            .trimEnd()
                    );
                }

                $(".FrmFDHokanInput.txtSYOYU_ADDR_1").val(
                    clsComFnc.FncNv(objDr["SYOYU_ADDR_1"])
                );
                $(".FrmFDHokanInput.txtSYOYU_ADDR_2").val(
                    clsComFnc.FncNv(objDr["SYOYU_ADDR_2"])
                );
            }
        }

        if (clsComFnc.FncNv(objDr["HONKYO_ADDR_SIYO"]) == "1") {
            $(".FrmFDHokanInput.chkHONKYO_ADDR_SIYO").prop(
                "checked",
                "checked"
            );
            subHONKYOADDRSIYO();
        } else {
            if (
                String(clsComFnc.FncNv(objDr["HONKYO_ADDR_CD"])) == "" &&
                String(clsComFnc.FncNv(objDr["HONKYO_ADDR_1"])) == "" &&
                String(clsComFnc.FncNv(objDr["HONKYO_ADDR_2"])) == "" &&
                String(clsComFnc.FncNv(objDr["HONKYO_ADDR_NM"])) == ""
            ) {
                $(".FrmFDHokanInput.chkHONKYO_ADDR_SIYO").prop(
                    "checked",
                    "checked"
                );
                subHONKYOADDRSIYO();
            } else {
                if (
                    String(clsComFnc.FncNv(objDr["HONKYO_ADDR_CD"]))
                        .padRight(12)
                        .substring(9, 12)
                        .trimEnd() == "000"
                ) {
                    $(".FrmFDHokanInput.txtHONKYO_ADDR_CD").val(
                        String(clsComFnc.FncNv(objDr["HONKYO_ADDR_CD"]))
                            .padRight(12)
                            .substring(0, 9)
                            .trimEnd()
                    );
                } else {
                    $(".FrmFDHokanInput.txtHONKYO_ADDR_CD").val(
                        String(clsComFnc.FncNv(objDr["HONKYO_ADDR_CD"]))
                            .padRight(12)
                            .trimEnd()
                    );
                }

                $(".FrmFDHokanInput.txtHONKYO_ADDR_1").val(
                    clsComFnc.FncNv(objDr["HONKYO_ADDR_1"])
                );
                $(".FrmFDHokanInput.txtHONKYO_ADDR_2").val(
                    clsComFnc.FncNv(objDr["HONKYO_ADDR_2"])
                );
                $(".FrmFDHokanInput.txtHONKYO_ADDR_NM").val(
                    clsComFnc.FncNv(objDr["HONKYO_ADDR_NM"])
                );
            }
        }

        $(".FrmFDHokanInput.txtKATASIKI").val(
            clsComFnc.FncNv(objDr["KATASIKI_RUIBETU"])
        );

        var tmpId =
            ".FrmFDHokanInput.cboIRO_CD option[index='" +
            clsComFnc.FncNz(objDr["IRO_CD"]) +
            "']";
        $(tmpId).prop("selected", true);

        $(".FrmFDHokanInput.txtSEISAKU_GENGO").val(
            clsComFnc.FncNv(objDr["SEISAKU_GENGO"])
        );
        $(".FrmFDHokanInput.txtSEISAKU_Y").val(
            clsComFnc.FncNv(objDr["SEISAKU_Y"])
        );
        $(".FrmFDHokanInput.txtSEISAKU_M").val(
            clsComFnc.FncNv(objDr["SEISAKU_M"])
        );
        $(".FrmFDHokanInput.txtSEISAKU_D").val(
            clsComFnc.FncNv(objDr["SEISAKU_D"])
        );
        $(".FrmFDHokanInput.txtHNB_CD").val(clsComFnc.FncNv(objDr["HNB_CD"]));
        $(".FrmFDHokanInput.txtSYOMEI_SIJI").val(
            clsComFnc.FncNv(objDr["SYOMEI_SIJI"])
        );
        //20170104 Ins Start
        $(".FrmFDHokanInput.txtSYOMEI_SIJI2").val(
            clsComFnc.FncNv(objDr["SYOMEI_SIJI2"])
        );
        //20170104 Ins End

        var strSinseiSiyoNm = "";
        strSinseiSiyoNm = String(clsComFnc.FncNv(objDr["SINSEI_SIYO_NM"]));
        strSinseiSiyoNm = strSinseiSiyoNm.replace("㈱", "株式会社");
        strSinseiSiyoNm = strSinseiSiyoNm.replace("㈲", "有限会社");

        if (strSinseiSiyoNm.indexOf("株式会社") == 0) {
            if (strSinseiSiyoNm.indexOf("株式会社　") == -1) {
                //20160531 Upd Start
                strSinseiSiyoNm =
                    strSinseiSiyoNm.substring(0, 4) +
                    "　" +
                    strSinseiSiyoNm.substring(4);
                //				strSinseiSiyoNm = strSinseiSiyoNm.substr(0, 4) + strSinseiSiyoNm.substr(4);
                //20160531 Upd End
            }
        } else if (strSinseiSiyoNm.indexOf("株式会社") > 0) {
            if (strSinseiSiyoNm.indexOf("　株式会社") == -1) {
                //20160531 Upd Start
                strSinseiSiyoNm =
                    strSinseiSiyoNm.substring(
                        0,
                        strSinseiSiyoNm.indexOf("株式会社")
                    ) +
                    "　" +
                    strSinseiSiyoNm.substring(
                        strSinseiSiyoNm.indexOf("株式会社")
                    );
                //				strSinseiSiyoNm = strSinseiSiyoNm.substr(0, strSinseiSiyoNm.indexOf("株式会社")) + strSinseiSiyoNm.substr(strSinseiSiyoNm.indexOf("株式会社"));
                //20160531 Upd End
            }
        } else if (strSinseiSiyoNm.indexOf("有限会社") == 0) {
            if (strSinseiSiyoNm.indexOf("有限会社　") == -1) {
                //20160531 Upd Start
                strSinseiSiyoNm =
                    strSinseiSiyoNm.substring(0, 4) +
                    "　" +
                    strSinseiSiyoNm.substring(4);
                //				strSinseiSiyoNm = strSinseiSiyoNm.substr(0, 4) + strSinseiSiyoNm.substr(4);
                //20160531 Upd End
            }
        } else if (strSinseiSiyoNm.indexOf("有限会社") > 0) {
            if (strSinseiSiyoNm.indexOf("　有限会社") == -1) {
                //20160531 Upd Start
                strSinseiSiyoNm =
                    strSinseiSiyoNm.substring(
                        0,
                        strSinseiSiyoNm.indexOf("有限会社")
                    ) +
                    "　" +
                    strSinseiSiyoNm.substring(
                        strSinseiSiyoNm.indexOf("有限会社")
                    );
                //				strSinseiSiyoNm = strSinseiSiyoNm.substr(0, strSinseiSiyoNm.indexOf("有限会社")) + strSinseiSiyoNm.substr(strSinseiSiyoNm.indexOf("有限会社"));
                //20160531 Upd End
            }
        }

        //20160608 Upd Start
        //		strSinseiSiyoNm= strSinseiSiyoNm.replace("　", "");
        //		strSinseiSiyoNm= strSinseiSiyoNm.replace(" ", "");
        strSinseiSiyoNm = strSinseiSiyoNm.trimEnd();
        //20160608 Upd End

        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").val(strSinseiSiyoNm);
        $(".FrmFDHokanInput.txtSINSEI_SIYO_ADDR").val(
            clsComFnc.FncNv(objDr["SINSEI_SIYO_ADDR"])
        );

        var strSinseiSyoyuNm = "";
        strSinseiSyoyuNm = String(clsComFnc.FncNv(objDr["SINSEI_SYOYU_NM"]));
        strSinseiSyoyuNm = strSinseiSyoyuNm.replace("㈱", "株式会社");
        strSinseiSyoyuNm = strSinseiSyoyuNm.replace("㈲", "有限会社");

        if (strSinseiSyoyuNm.indexOf("株式会社") == 0) {
            if (strSinseiSyoyuNm.indexOf("株式会社　") == -1) {
                //20160601 Upd Start
                strSinseiSyoyuNm =
                    strSinseiSyoyuNm.substring(0, 4) +
                    "　" +
                    strSinseiSyoyuNm.substring(4);
                //				strSinseiSyoyuNm = strSinseiSyoyuNm.substr(0, 4) + strSinseiSyoyuNm.substr(4);
                //20160601 Upd End
            }
        } else if (strSinseiSyoyuNm.indexOf("株式会社") > 0) {
            if (strSinseiSyoyuNm.indexOf("　株式会社") == -1) {
                //20160601 Upd Start
                strSinseiSyoyuNm =
                    strSinseiSyoyuNm.substring(
                        0,
                        strSinseiSyoyuNm.indexOf("株式会社")
                    ) +
                    "　" +
                    strSinseiSyoyuNm.substring(
                        strSinseiSyoyuNm.indexOf("株式会社")
                    );
                //				strSinseiSyoyuNm = strSinseiSyoyuNm.substr(0, strSinseiSyoyuNm.indexOf("株式会社")) + strSinseiSyoyuNm.substr(strSinseiSyoyuNm.indexOf("株式会社"));
                //20160601 Upd End
            }
        } else if (strSinseiSyoyuNm.indexOf("有限会社") == 0) {
            if (strSinseiSyoyuNm.indexOf("有限会社　") == -1) {
                //20160601 Upd Start
                strSinseiSyoyuNm =
                    strSinseiSyoyuNm.substring(0, 4) +
                    "　" +
                    strSinseiSyoyuNm.substring(4);
                //				strSinseiSyoyuNm = strSinseiSyoyuNm.substr(0, 4) + strSinseiSyoyuNm.substr(4);
                //20160601 Upd End
            }
        } else if (strSinseiSyoyuNm.indexOf("有限会社") > 0) {
            if (strSinseiSyoyuNm.indexOf("　有限会社") == -1) {
                //20160601 Upd Start
                strSinseiSyoyuNm =
                    strSinseiSyoyuNm.substring(
                        0,
                        strSinseiSyoyuNm.indexOf("有限会社")
                    ) +
                    "　" +
                    strSinseiSyoyuNm.substring(
                        strSinseiSyoyuNm.indexOf("有限会社")
                    );
                //				strSinseiSyoyuNm = strSinseiSyoyuNm.substr(0, strSinseiSyoyuNm.indexOf("有限会社")) + strSinseiSyoyuNm.substr(strSinseiSyoyuNm.indexOf("有限会社"));
                //20160601 Upd End
            }
        }

        //20160608 Upd Start
        //		strSinseiSyoyuNm= strSinseiSyoyuNm.replace("　", "");
        //		strSinseiSyoyuNm= strSinseiSyoyuNm.replace(" ", "");
        strSinseiSyoyuNm = strSinseiSyoyuNm.trimEnd();
        //20160608 Upd End

        $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").val(strSinseiSyoyuNm);
        $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").val(
            clsComFnc.FncNv(objDr["SINSEI_SYOYU_ADDR"])
        );

        if (clsComFnc.FncNz(objDr["INP_FLG"]) == "0") {
            var tmpId = ".FrmFDHokanInput.cboBAN_SIJI_YOT_2 option[index='0']";
            $(tmpId).prop("selected", true);

            var tmpId = ".FrmFDHokanInput.cboIRO_CD option[index='8']";
            $(tmpId).prop("selected", true);
        }

        if ($(".FrmFDHokanInput.txtSYOYU_CD").val().trimEnd() != "") {
            $(".FrmFDHokanInput.txtSYOYU_NM").val("");
            $(".FrmFDHokanInput.txtSYOYU_NM").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_NM").css("backgroundColor", "#DCDCDC");

            var tmpFlag = $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop(
                "checked"
            );
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").removeAttr("checked");
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("disabled", "disabled");
            $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").css(
                "backgroundColor",
                "#DCDCDC"
            );

            if (
                tmpFlag !=
                $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked")
            ) {
                subSYOYUNMSIYOClick();
            }

            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").css(
                "backgroundColor",
                "#DCDCDC"
            );

            var tmpFlag = $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop(
                "checked"
            );
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").removeAttr("checked");
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").css(
                "backgroundColor",
                "#DCDCDC"
            );

            if (
                tmpFlag !=
                $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop("checked")
            ) {
                subSYOYUADDRSIYO();
            }

            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").val("");
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").val("");
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").css(
                "backgroundColor",
                "#DCDCDC"
            );
        }
    }

    function subSYOYUNMSIYOClick() {
        if ($(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked") == true) {
            $(".FrmFDHokanInput.txtSYOYU_NM").val("");
            $(".FrmFDHokanInput.txtSYOYU_NM").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_NM").css("backgroundColor", "#DCDCDC");
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").val(
                $(".FrmFDHokanInput.txtSHIYOU_NM").val()
            );
        } else {
            $(".FrmFDHokanInput.txtSYOYU_NM").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_NM").css(clsComFnc.GC_COLOR_NORMAL);
        }
    }

    function subSYOYUADDRSIYO() {
        if ($(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop("checked") == true) {
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").val("");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR").val(
                $(".FrmFDHokanInput.txtSINSEI_SIYO_ADDR").val()
            );
        } else {
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_CD").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_1").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").removeAttr("disabled");
            $(".FrmFDHokanInput.txtSYOYU_ADDR_2").css(
                clsComFnc.GC_COLOR_NORMAL
            );
        }
    }

    function subHONKYOADDRSIYO() {
        if ($(".FrmFDHokanInput.chkHONKYO_ADDR_SIYO").prop("checked") == true) {
            $(".FrmFDHokanInput.txtHONKYO_ADDR_CD").val("");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_CD").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_CD").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_1").val("");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_1").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_1").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_2").val("");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_2").prop("disabled", "disabled");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_2").css(
                "backgroundColor",
                "#DCDCDC"
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_NM").val("");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_NM").prop(
                "disabled",
                "disabled"
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_NM").css(
                "backgroundColor",
                "#DCDCDC"
            );
        } else {
            $(".FrmFDHokanInput.txtHONKYO_ADDR_CD").removeAttr("disabled");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_CD").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_1").removeAttr("disabled");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_1").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_2").removeAttr("disabled");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_2").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmFDHokanInput.txtHONKYO_ADDR_NM").removeAttr("disabled");
            $(".FrmFDHokanInput.txtHONKYO_ADDR_NM").css(
                clsComFnc.GC_COLOR_NORMAL
            );
        }
    }

    me.fnccmdActionClick = function () {
        //入力ﾁｪｯｸ
        if (!fncCheck()) {
            return;
        }

        //確認ﾒｯｾｰｼﾞ表示
        clsComFnc.MsgBoxBtnFnc.Yes = UpdateDeal;
        clsComFnc.FncMsgBox("QY010");
    };
    function fncCheck() {
        if (!fncInputCheck()) {
            return false;
        }

        return true;
    }

    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：fncInputCheck
    //引    数：無し
    //戻 り 値：True:正常終了 False:異常終了
    //処理説明：入力チェック
    //**********************************************************************
    function fncInputCheck() {
        NomalColorChg();

        //手数料(1以外は不可)
        if ($(".FrmFDHokanInput.txtTesuryo").val().trimEnd() != "") {
            if ($(".FrmFDHokanInput.txtTesuryo").val().trimEnd() != "1") {
                $(".FrmFDHokanInput.txtTesuryo").css(clsComFnc.GC_COLOR_ERROR);
                subMsgOutput(-2, "手数料", $(".FrmFDHokanInput.txtTesuryo"));
                return false;
            }
        }

        //希望車両－分類番号
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtKIBO_SRY_BUNRUI"),
            0,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "希望車両(分類番号)",
                $(".FrmFDHokanInput.txtKIBO_SRY_BUNRUI")
            );
            return false;
        }

        //希望車両－かな文字
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtKIBO_SRY_KANA"),
            0,
            clsComFnc.INPUTTYPE.CHAR4
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "希望車両(かな文字)",
                $(".FrmFDHokanInput.txtKIBO_SRY_KANA")
            );
            return false;
        }

        //希望車両－一連希望番号
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtKIBO_SRY_KIBO"),
            0,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "希望車両(一連希望番号)",
                $(".FrmFDHokanInput.txtKIBO_SRY_KIBO")
            );
            return false;
        }

        //車両文字
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSRY_BAN_MOJI"),
            0,
            clsComFnc.INPUTTYPE.CHAR4
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "車両番号(運輸管理部又は運輸支局等を表示する文字)",
                $(".FrmFDHokanInput.txtSRY_BAN_MOJI")
            );
            return false;
        }

        //車両分類番号
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSRY_BAN_BUNRUI"),
            0,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "車両番号(分類番号)",
                $(".FrmFDHokanInput.txtSRY_BAN_BUNRUI")
            );
            return false;
        }

        //車両かな文字
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSRY_BAN_KANA"),
            0,
            clsComFnc.INPUTTYPE.CHAR4
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "車両番号(かな文字)",
                $(".FrmFDHokanInput.txtSRY_BAN_KANA")
            );
            return false;
        }

        //車両一連指定番号
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSRY_BAN_SITEI"),
            0,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "車両番号(一連指定番号)",
                $(".FrmFDHokanInput.txtSRY_BAN_SITEI")
            );
            return false;
        }

        //車両小判
        if ($(".FrmFDHokanInput.txtSRY_BAN_SYOUBAN").val().trimEnd() != "") {
            if (
                $(".FrmFDHokanInput.txtSRY_BAN_SYOUBAN").val().trimEnd() != "1"
            ) {
                $(".FrmFDHokanInput.txtSRY_BAN_SYOUBAN").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -2,
                    "小判",
                    $(".FrmFDHokanInput.txtSRY_BAN_SYOUBAN")
                );
                return false;
            }
        }

        //車台番号
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSYADAI_NO"),
            1,
            clsComFnc.INPUTTYPE.CHAR1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "車台番号",
                $(".FrmFDHokanInput.txtSYADAI_NO")
            );
            return false;
        }

        //所有者コード
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSYOYU_CD"),
            0,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "所有者コード",
                $(".FrmFDHokanInput.txtSYOYU_CD")
            );
            return false;
        }

        //所有者コード(使用者)
        if ($(".FrmFDHokanInput.txtSYOYU_SIYO").val().trimEnd() != "") {
            if ($(".FrmFDHokanInput.txtSYOYU_SIYO").val().trimEnd() != "1") {
                $(".FrmFDHokanInput.txtSYOYU_SIYO").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -2,
                    "所有者コード",
                    $(".FrmFDHokanInput.txtSYOYU_SIYO")
                );
                return false;
            }
        }

        //使用者氏名
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSHIYOU_NM"),
            1,
            clsComFnc.INPUTTYPE.CHAR4
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "使用者氏名",
                $(".FrmFDHokanInput.txtSHIYOU_NM")
            );
            return false;
        }

        //株式会社チェック
        if (
            $(".FrmFDHokanInput.txtSHIYOU_NM").val().trimEnd().indexOf("㈱") >
            -1
        ) {
            $(".FrmFDHokanInput.txtSHIYOU_NM").css(clsComFnc.GC_COLOR_ERROR);
            subMsgOutput(
                -9,
                "㈱は株式会社に変更してください！",
                $(".FrmFDHokanInput.txtSHIYOU_NM")
            );
            return false;
        }

        var intShiyouNm = $(".FrmFDHokanInput.txtSHIYOU_NM")
            .val()
            .trimEnd()
            .indexOf("株式会社");
        //20160601 Del Start
        if (intShiyouNm > -1) {
            if (intShiyouNm == 0) {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                            .val()
                            .trimEnd()
                            .substring(4, 5)
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSHIYOU_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "株式会社の後に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                    );
                    return false;
                }
            } else {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                            .val()
                            .trimEnd()
                            .substring(intShiyouNm - 1, intShiyouNm)
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSHIYOU_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "株式会社の前に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                    );
                    return false;
                }
            }
        }
        //20160601 Del End

        //有限会社チェック
        if (
            $(".FrmFDHokanInput.txtSHIYOU_NM").val().trimEnd().indexOf("㈲") >
            -1
        ) {
            $(".FrmFDHokanInput.txtSHIYOU_NM").css(clsComFnc.GC_COLOR_ERROR);
            subMsgOutput(
                -9,
                "㈲は有限会社に変更してください！",
                $(".FrmFDHokanInput.txtSHIYOU_NM")
            );
            return false;
        }

        var intYugenShiyoNm = $(".FrmFDHokanInput.txtSHIYOU_NM")
            .val()
            .trimEnd()
            .indexOf("有限会社");
        //20160601 Del Start
        if (intYugenShiyoNm > -1) {
            if (intYugenShiyoNm == 0) {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                            .val()
                            .trimEnd()
                            .substring(4, 5)
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSHIYOU_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "有限会社の後に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                    );
                    return false;
                }
            } else {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                            .val()
                            .trimEnd()
                            .substring(intYugenShiyoNm - 1, intYugenShiyoNm)
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSHIYOU_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "有限会社の前に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSHIYOU_NM")
                    );
                    return false;
                }
            }
        }
        //20160601 DEL End

        //使用者住所コード
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSHIYOU_ADDR_CD"),
            1,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "使用者住所(住所コード)",
                $(".FrmFDHokanInput.txtSHIYOU_ADDR_CD")
            );
            return false;
        }

        //使用者(丁目)
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSHIYOU_ADDR_1"),
            0,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "使用者住所(丁目)",
                $(".FrmFDHokanInput.txtSHIYOU_ADDR_1")
            );
            return false;
        }

        //使用者(番地)
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSHIYOU_ADDR_2"),
            0,
            clsComFnc.INPUTTYPE.NUMBER3
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "使用者住所(番地)",
                $(".FrmFDHokanInput.txtSHIYOU_ADDR_2")
            );
            return false;
        }

        //使用者と同じ以外は
        if ($(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked") == false) {
            //所有者コードが入力された場合は、所有者の欄の入力は必要なし
            if ($(".FrmFDHokanInput.txtSYOYU_CD").val().trimEnd() == "") {
                //所有者氏名
                var intRtn = clsComFnc.FncTextCheck(
                    $(".FrmFDHokanInput.txtSYOYU_NM"),
                    1,
                    clsComFnc.INPUTTYPE.CHAR4
                );
                if (intRtn < 0) {
                    subMsgOutput(
                        intRtn,
                        "所有者氏名",
                        $(".FrmFDHokanInput.txtSYOYU_NM")
                    );
                    return false;
                }

                //株式会社チェック
                if (
                    $(".FrmFDHokanInput.txtSYOYU_NM")
                        .val()
                        .trimEnd()
                        .indexOf("㈱") > -1
                ) {
                    $(".FrmFDHokanInput.txtSYOYU_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "㈱は株式会社に変更してください！",
                        $(".FrmFDHokanInput.txtSYOYU_NM")
                    );
                    return false;
                }

                var intSyoyuNm = $(".FrmFDHokanInput.txtSYOYU_NM")
                    .val()
                    .trimEnd()
                    .indexOf("株式会社");
                //20160601 DEL Start
                if (intSyoyuNm > -1) {
                    if (intSyoyuNm == 0) {
                        if (
                            String(
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                                    .val()
                                    .trimEnd()
                                    .substring(4, 5)
                            ) != "　"
                        ) {
                            $(".FrmFDHokanInput.txtSYOYU_NM").css(
                                clsComFnc.GC_COLOR_ERROR
                            );
                            subMsgOutput(
                                -9,
                                "株式会社の後に空白を入れてください！",
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                            );
                            return false;
                        }
                    } else {
                        if (
                            String(
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                                    .val()
                                    .trimEnd()
                                    .substring(intSyoyuNm - 1, intSyoyuNm)
                            ) != "　"
                        ) {
                            $(".FrmFDHokanInput.txtSYOYU_NM").css(
                                clsComFnc.GC_COLOR_ERROR
                            );
                            subMsgOutput(
                                -9,
                                "株式会社の前に空白を入れてください！",
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                            );
                            return false;
                        }
                    }
                }
                //20160601 DEL End

                //有限会社チェック
                if (
                    $(".FrmFDHokanInput.txtSYOYU_NM")
                        .val()
                        .trimEnd()
                        .indexOf("㈲") > -1
                ) {
                    $(".FrmFDHokanInput.txtSYOYU_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "㈲は有限会社に変更してください！",
                        $(".FrmFDHokanInput.txtSYOYU_NM")
                    );
                    return false;
                }

                var intYugenSyoyuNm = $(".FrmFDHokanInput.txtSYOYU_NM")
                    .val()
                    .trimEnd()
                    .indexOf("有限会社");
                //20160601 DEL Start
                if (intYugenSyoyuNm > -1) {
                    if (intYugenSyoyuNm == 0) {
                        if (
                            String(
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                                    .val()
                                    .trimEnd()
                                    .substring(4, 5)
                            ) != "　"
                        ) {
                            $(".FrmFDHokanInput.txtSYOYU_NM").css(
                                clsComFnc.GC_COLOR_ERROR
                            );
                            subMsgOutput(
                                -9,
                                "有限会社の後に空白を入れてください！",
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                            );
                            return false;
                        }
                    } else {
                        if (
                            String(
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                                    .val()
                                    .trimEnd()
                                    .substring(
                                        intYugenSyoyuNm - 1,
                                        intYugenSyoyuNm
                                    )
                            ) != "　"
                        ) {
                            $(".FrmFDHokanInput.txtSYOYU_NM").css(
                                clsComFnc.GC_COLOR_ERROR
                            );
                            subMsgOutput(
                                -9,
                                "有限会社の前に空白を入れてください！",
                                $(".FrmFDHokanInput.txtSYOYU_NM")
                            );
                            return false;
                        }
                    }
                }
            }
        }
        //20160601 DEL End

        //使用者と同じ以外は
        if ($(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop("checked") == false) {
            //所有者コードが入力された場合は、所有者の欄の入力は必要なし
            if ($(".FrmFDHokanInput.txtSYOYU_CD").val().trimEnd() == "") {
                //所有者住所コード
                var intRtn = clsComFnc.FncTextCheck(
                    $(".FrmFDHokanInput.txtSYOYU_ADDR_CD"),
                    1,
                    clsComFnc.INPUTTYPE.NUMBER1
                );
                if (intRtn < 0) {
                    subMsgOutput(
                        intRtn,
                        "所有者住所(住所コード)",
                        $(".FrmFDHokanInput.txtSYOYU_ADDR_CD")
                    );
                    return false;
                }

                //所有者住所(丁目)
                var intRtn = clsComFnc.FncTextCheck(
                    $(".FrmFDHokanInput.txtSYOYU_ADDR_1"),
                    0,
                    clsComFnc.INPUTTYPE.NUMBER1
                );
                if (intRtn < 0) {
                    subMsgOutput(
                        intRtn,
                        "所有者住所(丁目)",
                        $(".FrmFDHokanInput.txtSYOYU_ADDR_1")
                    );
                    return false;
                }

                //所有者住所(番地)
                var intRtn = clsComFnc.FncTextCheck(
                    $(".FrmFDHokanInput.txtSYOYU_ADDR_2"),
                    0,
                    clsComFnc.INPUTTYPE.NUMBER3
                );
                if (intRtn < 0) {
                    subMsgOutput(
                        intRtn,
                        "所有者住所(番地)",
                        $(".FrmFDHokanInput.txtSYOYU_ADDR_2")
                    );
                    return false;
                }
            }
        }

        //使用者と同じ以外は
        if (
            $(".FrmFDHokanInput.chkHONKYO_ADDR_SIYO").prop("checked") == false
        ) {
            //使用の本拠の位置(住所コード)
            var intRtn = clsComFnc.FncTextCheck(
                $(".FrmFDHokanInput.txtHONKYO_ADDR_CD"),
                1,
                clsComFnc.INPUTTYPE.NUMBER1
            );
            if (intRtn < 0) {
                subMsgOutput(
                    intRtn,
                    "使用の本拠の位置(住所コード)",
                    $(".FrmFDHokanInput.txtHONKYO_ADDR_CD")
                );
                return false;
            }

            //使用の本拠の位置(丁目)
            var intRtn = clsComFnc.FncTextCheck(
                $(".FrmFDHokanInput.txtHONKYO_ADDR_1"),
                0,
                clsComFnc.INPUTTYPE.NUMBER1
            );
            if (intRtn < 0) {
                subMsgOutput(
                    intRtn,
                    "使用の本拠の位置(丁目)",
                    $(".FrmFDHokanInput.txtHONKYO_ADDR_1")
                );
                return false;
            }

            //使用の本拠の位置(番地)
            var intRtn = clsComFnc.FncTextCheck(
                $(".FrmFDHokanInput.txtHONKYO_ADDR_2"),
                0,
                clsComFnc.INPUTTYPE.NUMBER3
            );
            if (intRtn < 0) {
                subMsgOutput(
                    intRtn,
                    "使用の本拠の位置(番地)",
                    $(".FrmFDHokanInput.txtHONKYO_ADDR_2")
                );
                return false;
            }

            //使用の本拠の位置(住所)
            var intRtn = clsComFnc.FncTextCheck(
                $(".FrmFDHokanInput.txtHONKYO_ADDR_NM"),
                1,
                clsComFnc.INPUTTYPE.CHAR4
            );
            if (intRtn < 0) {
                subMsgOutput(
                    intRtn,
                    "使用の本拠の位置(住所)",
                    $(".FrmFDHokanInput.txtHONKYO_ADDR_NM")
                );
                return false;
            }
        }

        //型式類別
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtKATASIKI"),
            1,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            subMsgOutput(intRtn, "型式類別", $(".FrmFDHokanInput.txtKATASIKI"));
            return false;
        }

        //車体の塗色
        if ($(".FrmFDHokanInput.cboIRO_CD").val() == 0) {
            $(".FrmFDHokanInput.cboIRO_CD").css(clsComFnc.GC_COLOR_ERROR);
            subMsgOutput(-1, "車体の塗色", $(".FrmFDHokanInput.cboIRO_CD"));
            return false;
        }

        //製作年月
        if ($(".FrmFDHokanInput.txtSEISAKU_GENGO").val().trimEnd() != "") {
            if ($(".FrmFDHokanInput.txtSEISAKU_GENGO").val().trimEnd() != "1") {
                $(".FrmFDHokanInput.txtSEISAKU_GENGO").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -2,
                    "製作年月(元号)",
                    $(".FrmFDHokanInput.txtSEISAKU_GENGO")
                );
                return false;
            }
        }

        var intNengetuChk = 0;

        //製作年月(年)
        if ($(".FrmFDHokanInput.txtSEISAKU_Y").val().trimEnd() != "") {
            intNengetuChk += 1;
        }

        //製作年月(月)
        if ($(".FrmFDHokanInput.txtSEISAKU_M").val().trimEnd() != "") {
            if (
                $(".FrmFDHokanInput.txtSEISAKU_M").val().trimEnd() < "01" ||
                $(".FrmFDHokanInput.txtSEISAKU_M").val().trimEnd() > "12"
            ) {
                $(".FrmFDHokanInput.txtSEISAKU_M").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -2,
                    "製作年月(月)",
                    $(".FrmFDHokanInput.txtSEISAKU_M")
                );
                return false;
            }

            intNengetuChk += 1;
        }

        //製作年月(日)
        if ($(".FrmFDHokanInput.txtSEISAKU_D").val().trimEnd() != "") {
            if (
                $(".FrmFDHokanInput.txtSEISAKU_D").val().trimEnd() < "01" ||
                $(".FrmFDHokanInput.txtSEISAKU_D").val().trimEnd() > "31"
            ) {
                $(".FrmFDHokanInput.txtSEISAKU_D").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -2,
                    "製作年月(日)",
                    $(".FrmFDHokanInput.txtSEISAKU_D")
                );
                return false;
            }

            intNengetuChk += 1;
        }

        //製作年月日のいずれかに入力した場合は必須
        if (intNengetuChk > 0 && intNengetuChk < 3) {
            subMsgOutput(
                -2,
                "製作年月日",
                $(".FrmFDHokanInput.txtSEISAKU_GENGO")
            );
            return false;
        }

        if (
            $(".FrmFDHokanInput.txtSEISAKU_GENGO").val().trimEnd() != "" &&
            intNengetuChk == 0
        ) {
            $(".FrmFDHokanInput.txtSEISAKU_Y").css(clsComFnc.GC_COLOR_ERROR);
            $(".FrmFDHokanInput.txtSEISAKU_M").css(clsComFnc.GC_COLOR_ERROR);
            $(".FrmFDHokanInput.txtSEISAKU_D").css(clsComFnc.GC_COLOR_ERROR);
            subMsgOutput(
                -9,
                "製作元号を入力した場合は年月日は必須です！",
                $(".FrmFDHokanInput.txtSEISAKU_Y")
            );
            return false;
        }

        //証明書指示
        if ($(".FrmFDHokanInput.txtSYOMEI_SIJI").val().trimEnd() != "") {
            if (
                $(".FrmFDHokanInput.txtSYOMEI_SIJI").val().trimEnd() != "1" &&
                $(".FrmFDHokanInput.txtSYOMEI_SIJI").val().trimEnd() != "2"
            ) {
                $(".FrmFDHokanInput.txtSYOMEI_SIJI").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -2,
                    "証明書指示",
                    $(".FrmFDHokanInput.txtSYOMEI_SIJI")
                );
                return false;
            }
        }

        if (
            $(".FrmFDHokanInput.txtSYOMEI_SIJI").val().trimEnd() == "" &&
            intNengetuChk > 0
        ) {
            $(".FrmFDHokanInput.txtSYOMEI_SIJI").css(clsComFnc.GC_COLOR_ERROR);
            subMsgOutput(
                -9,
                "製作年月日を入力した場合は証明書指示は必須です",
                $(".FrmFDHokanInput.txtSYOMEI_SIJI")
            );
            return false;
        }

        if (
            $(".FrmFDHokanInput.txtSYOMEI_SIJI").val().trimEnd() != "" &&
            intNengetuChk == 0
        ) {
            $(".FrmFDHokanInput.txtSEISAKU_GENGO").css(
                clsComFnc.GC_COLOR_ERROR
            );
            subMsgOutput(
                -9,
                "証明書指示を入力した場合は製作年月日は必須です",
                $(".FrmFDHokanInput.txtSEISAKU_GENGO")
            );
            return false;
        }

        //20170104 Ins Start
        //証明書指示2
        if ($(".FrmFDHokanInput.txtSYOMEI_SIJI2").val().trimEnd() != "") {
            if (
                $(".FrmFDHokanInput.txtSYOMEI_SIJI2").val().trimEnd() != "1" &&
                $(".FrmFDHokanInput.txtSYOMEI_SIJI2").val().trimEnd() != "2" &&
                $(".FrmFDHokanInput.txtSYOMEI_SIJI2").val().trimEnd() != "3"
            ) {
                $(".FrmFDHokanInput.txtSYOMEI_SIJI2").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -2,
                    "証明書指示２",
                    $(".FrmFDHokanInput.txtSYOMEI_SIJI2")
                );
                return false;
            }
        }
        //20170104 Ins End

        //申請者(使用者)氏名
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSINSEI_SIYO_NM"),
            1,
            clsComFnc.INPUTTYPE.CHAR4
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "申請者(使用者氏名又は名称)",
                $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
            );
            return false;
        }

        //株式会社チェック
        if (
            $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                .val()
                .trimEnd()
                .indexOf("㈱") > -1
        ) {
            $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").css(
                clsComFnc.GC_COLOR_ERROR
            );
            subMsgOutput(
                -9,
                "㈱は株式会社に変更してください！",
                $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
            );
            return false;
        }

        var intSinseiShiyoNm = $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
            .val()
            .trimEnd()
            .indexOf("株式会社");
        //20160601 DEL Start
        if (intSinseiShiyoNm > -1) {
            if (intSinseiShiyoNm == 0) {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                            .val()
                            .trimEnd()
                            .substring(4, 5)
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "株式会社の後に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                    );
                    return false;
                }
            } else {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                            .val()
                            .trimEnd()
                            .substring(intSinseiShiyoNm - 1, intSinseiShiyoNm)
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "株式会社の前に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                    );
                    return false;
                }
            }
        }
        //20160601 DEL End

        //有限会社チェック
        if (
            $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                .val()
                .trimEnd()
                .indexOf("㈲") > -1
        ) {
            $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").css(
                clsComFnc.GC_COLOR_ERROR
            );
            subMsgOutput(
                -9,
                "㈲は有限会社に変更してください！",
                $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
            );
            return false;
        }

        var intYugenSinseiShiyoNm = $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
            .val()
            .trimEnd()
            .indexOf("有限会社");
        //20160601 DEL Start
        if (intYugenSinseiShiyoNm > -1) {
            if (intYugenSinseiShiyoNm == 0) {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                            .val()
                            .trimEnd()
                            .substring(4, 5)
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "有限会社の後に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                    );
                    return false;
                }
            } else {
                if (
                    String(
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                            .val()
                            .trimEnd()
                            .substring(
                                intYugenSinseiShiyoNm - 1,
                                intYugenSinseiShiyoNm
                            )
                    ) != "　"
                ) {
                    $(".FrmFDHokanInput.txtSINSEI_SIYO_NM").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    subMsgOutput(
                        -9,
                        "有限会社の前に空白を入れてください！",
                        $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                    );
                    return false;
                }
            }
        }
        //20160601 DEL End

        //申請者(使用者)住所
        var intRtn = clsComFnc.FncTextCheck(
            $(".FrmFDHokanInput.txtSINSEI_SIYO_ADDR"),
            1,
            clsComFnc.INPUTTYPE.CHAR4
        );
        if (intRtn < 0) {
            subMsgOutput(
                intRtn,
                "申請者(使用者住所)",
                $(".FrmFDHokanInput.txtSINSEI_SIYO_ADDR")
            );
            return false;
        }

        //所有者コードが入力された場合は、所有者の欄の入力は必要なし
        if ($(".FrmFDHokanInput.txtSYOYU_CD").val().trimEnd() == "") {
            //申請者(使用者)住所
            var intRtn = clsComFnc.FncTextCheck(
                $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM"),
                1,
                clsComFnc.INPUTTYPE.CHAR4
            );
            if (intRtn < 0) {
                subMsgOutput(
                    intRtn,
                    "申請者(所有者氏名又は名称)",
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                );
                return false;
            }

            //株式会社チェック
            if (
                $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                    .val()
                    .trimEnd()
                    .indexOf("㈱") > -1
            ) {
                $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -9,
                    "㈱は株式会社に変更してください！",
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                );
                return false;
            }

            var intSinseiSyoyuNm = $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                .val()
                .trimEnd()
                .indexOf("株式会社");
            //20160601 DEL Start
            if (intSinseiSyoyuNm > -1) {
                if (intSinseiSyoyuNm == 0) {
                    if (
                        String(
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                                .val()
                                .trimEnd()
                                .substring(4, 5)
                        ) != "　"
                    ) {
                        $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                            clsComFnc.GC_COLOR_ERROR
                        );
                        subMsgOutput(
                            -9,
                            "株式会社の後に空白を入れてください！",
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                        );
                        return false;
                    }
                } else {
                    if (
                        String(
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                                .val()
                                .trimEnd()
                                .substring(
                                    intSinseiSyoyuNm - 1,
                                    intSinseiSyoyuNm
                                )
                        ) != "　"
                    ) {
                        $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                            clsComFnc.GC_COLOR_ERROR
                        );
                        subMsgOutput(
                            -9,
                            "株式会社の前に空白を入れてください！",
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                        );
                        return false;
                    }
                }
            }
            //20160601 DEL End

            //有限会社チェック
            if (
                $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                    .val()
                    .trimEnd()
                    .indexOf("㈲") > -1
            ) {
                $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                subMsgOutput(
                    -9,
                    "㈲は有限会社に変更してください！",
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                );
                return false;
            }

            var intYugenSinseiSyoyuNm = $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                .val()
                .trimEnd()
                .indexOf("有限会社");
            //20160601 DEL Start
            if (intYugenSinseiSyoyuNm > -1) {
                if (intYugenSinseiSyoyuNm == 0) {
                    if (
                        String(
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                                .val()
                                .trimEnd()
                                .substring(4, 5)
                        ) != "　"
                    ) {
                        $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                            clsComFnc.GC_COLOR_ERROR
                        );
                        subMsgOutput(
                            -9,
                            "有限会社の後に空白を入れてください！",
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                        );
                        return false;
                    }
                } else {
                    if (
                        String(
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                                .val()
                                .trimEnd()
                                .substring(
                                    intYugenSinseiSyoyuNm - 1,
                                    intYugenSinseiSyoyuNm
                                )
                        ) != "　"
                    ) {
                        $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM").css(
                            clsComFnc.GC_COLOR_ERROR
                        );
                        subMsgOutput(
                            -9,
                            "有限会社の前に空白を入れてください！",
                            $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                        );
                        return false;
                    }
                }
            }
            //20160601 DEL End

            //申請者(所有者)住所
            var intRtn = clsComFnc.FncTextCheck(
                $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR"),
                1,
                clsComFnc.INPUTTYPE.CHAR4
            );
            if (intRtn < 0) {
                subMsgOutput(
                    intRtn,
                    "申請者(所有者住所)",
                    $(".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR")
                );
                return false;
            }
        }

        return true;
    }

    function NomalColorChg() {
        $(".FrmFDHokanInput.txtTesuryo").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFDHokanInput.cboBAN_SIJI_HBN_1").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFDHokanInput.cboBAN_SIJI_YOT_2").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFDHokanInput.cboIRO_CD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFDHokanInput.txtSRY_BAN_SYOUBAN").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFDHokanInput.txtSEISAKU_GENGO").css(clsComFnc.GC_COLOR_NORMAL);
    }

    function subMsgOutput(intErrMsgno, strerrmsg, objerr) {
        switch (intErrMsgno) {
            case -1:
                //'必須ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W0001", strerrmsg);
                break;
            case -2:
                //入力値ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W0002", strerrmsg);
                break;
            case -3:
                //桁数ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W0003", strerrmsg);
                break;
            case -6:
                //範囲ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W0006", strerrmsg);
                break;
            case -7:
                //存在ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W0007", strerrmsg);
                break;
            case -8:
                //存在ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W0008", strerrmsg);
                break;
            case -9:
                //その他ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W9999", strerrmsg);
                break;
            case -15:
                //フォルダ存在ｴﾗｰ
                clsComFnc.ObjSelect = objerr;
                clsComFnc.FncMsgBox("W0015", strerrmsg);
                break;
        }
    }

    function UpdateDeal() {
        var returnFlag = false;

        //SQLを発行
        var strMenteFlg = me.FrmFDHokanSelect.objArr["objfrm_PrpMenteFlg"];

        switch (strMenteFlg) {
            case "UPD":
                //SQL実行
                //表示初期値設定
                var funcName = "fncUPDATE";
                var url = me.id + "/" + funcName;

                var chkSYOYUNM = "";
                if (
                    $(".FrmFDHokanInput.chkSYOYU_NM_SIYO").prop("checked") ==
                    true
                ) {
                    chkSYOYUNM = "1";
                } else {
                    chkSYOYUNM = "";
                }

                var chkSYOYUADDR = "";
                if (
                    $(".FrmFDHokanInput.chkSYOYU_ADDR_SIYO").prop("checked") ==
                    true
                ) {
                    chkSYOYUADDR = "1";
                } else {
                    chkSYOYUADDR = "";
                }

                var chkHONKYOADDR = "";
                if (
                    $(".FrmFDHokanInput.chkHONKYO_ADDR_SIYO").prop("checked") ==
                    true
                ) {
                    chkHONKYOADDR = "1";
                } else {
                    chkHONKYOADDR = "";
                }

                var arrayVal = {
                    TESURYO: $(".FrmFDHokanInput.txtTesuryo").val().trimEnd(),
                    //"BAN_SIJI_YOT_2" : $(".FrmFDHokanInput.cboBAN_SIJI_YOT_2").val() - 1,
                    BAN_SIJI_YOT_2: $(
                        ".FrmFDHokanInput.cboBAN_SIJI_YOT_2"
                    ).val(),
                    BAN_SIJI_HBN_1: $(
                        ".FrmFDHokanInput.cboBAN_SIJI_HBN_1"
                    ).val(),
                    KIBO_SRY_BUNRUI: $(".FrmFDHokanInput.txtKIBO_SRY_BUNRUI")
                        .val()
                        .trimEnd(),
                    KIBO_SRY_KANA: $(".FrmFDHokanInput.txtKIBO_SRY_KANA")
                        .val()
                        .trimEnd(),
                    KIBO_SRY_KIBO: $(".FrmFDHokanInput.txtKIBO_SRY_KIBO")
                        .val()
                        .trimEnd(),
                    SRY_BAN_MOJI: $(".FrmFDHokanInput.txtSRY_BAN_MOJI")
                        .val()
                        .trimEnd(),
                    SRY_BAN_BUNRUI: $(".FrmFDHokanInput.txtSRY_BAN_BUNRUI")
                        .val()
                        .trimEnd(),
                    SRY_BAN_KANA: $(".FrmFDHokanInput.txtSRY_BAN_KANA")
                        .val()
                        .trimEnd(),
                    SRY_BAN_SITEI: $(".FrmFDHokanInput.txtSRY_BAN_SITEI")
                        .val()
                        .trimEnd(),
                    SRY_BAN_SYOUBAN: $(".FrmFDHokanInput.txtSRY_BAN_SYOUBAN")
                        .val()
                        .trimEnd(),
                    SYADAI_NO: $(".FrmFDHokanInput.txtSYADAI_NO")
                        .val()
                        .trimEnd(),
                    SYOYU_CD: $(".FrmFDHokanInput.txtSYOYU_CD").val().trimEnd(),
                    SYOYU_SIYO: $(".FrmFDHokanInput.txtSYOYU_SIYO")
                        .val()
                        .trimEnd(),
                    SHIYOU_NM: $(".FrmFDHokanInput.txtSHIYOU_NM")
                        .val()
                        .trimEnd(),
                    SHIYOU_ADDR_CD: $(".FrmFDHokanInput.txtSHIYOU_ADDR_CD")
                        .val()
                        .trimEnd(),
                    SHIYOU_ADDR_1: $(".FrmFDHokanInput.txtSHIYOU_ADDR_1")
                        .val()
                        .trimEnd(),
                    SHIYOU_ADDR_2: $(".FrmFDHokanInput.txtSHIYOU_ADDR_2")
                        .val()
                        .trimEnd(),
                    SYOYU_NM_SIYO: chkSYOYUNM,
                    SYOYU_NM: $(".FrmFDHokanInput.txtSYOYU_NM").val().trimEnd(),
                    SYOYU_ADDR_SIYO: chkSYOYUADDR,
                    SYOYU_ADDR_CD: $(".FrmFDHokanInput.txtSYOYU_ADDR_CD")
                        .val()
                        .trimEnd(),
                    SYOYU_ADDR_1: $(".FrmFDHokanInput.txtSYOYU_ADDR_1")
                        .val()
                        .trimEnd(),
                    SYOYU_ADDR_2: $(".FrmFDHokanInput.txtSYOYU_ADDR_2")
                        .val()
                        .trimEnd(),
                    HONKYO_ADDR_SIYO: chkHONKYOADDR,
                    HONKYO_ADDR_CD: $(".FrmFDHokanInput.txtHONKYO_ADDR_CD")
                        .val()
                        .trimEnd(),
                    HONKYO_ADDR_1: $(".FrmFDHokanInput.txtHONKYO_ADDR_1")
                        .val()
                        .trimEnd(),
                    HONKYO_ADDR_2: $(".FrmFDHokanInput.txtHONKYO_ADDR_2")
                        .val()
                        .trimEnd(),
                    HONKYO_ADDR_NM: $(".FrmFDHokanInput.txtHONKYO_ADDR_NM")
                        .val()
                        .trimEnd(),
                    KATASIKI_RUIBETU: $(".FrmFDHokanInput.txtKATASIKI")
                        .val()
                        .trimEnd(),
                    IRO_CD: $(".FrmFDHokanInput.cboIRO_CD").val(),
                    SEISAKU_GENGO: $(".FrmFDHokanInput.txtSEISAKU_GENGO")
                        .val()
                        .trimEnd(),
                    SEISAKU_YMD:
                        $(".FrmFDHokanInput.txtSEISAKU_Y").val().trimEnd() +
                        $(".FrmFDHokanInput.txtSEISAKU_M").val().trimEnd() +
                        $(".FrmFDHokanInput.txtSEISAKU_D").val().trimEnd(),
                    SYOMEI_SIJI: $(".FrmFDHokanInput.txtSYOMEI_SIJI")
                        .val()
                        .trimEnd(),
                    //20170104 Ins Start
                    SYOMEI_SIJI2: $(".FrmFDHokanInput.txtSYOMEI_SIJI2")
                        .val()
                        .trimEnd(),
                    //20170104 Ins End
                    // "HNB_CD" : $(".FrmFDHokanInput.txtHNB_CD").val().trimEnd(),
                    SINSEI_SIYO_NM: $(".FrmFDHokanInput.txtSINSEI_SIYO_NM")
                        .val()
                        .trimEnd(),
                    SINSEI_SIYO_ADDR: $(".FrmFDHokanInput.txtSINSEI_SIYO_ADDR")
                        .val()
                        .trimEnd(),
                    SINSEI_SYOYU_NM: $(".FrmFDHokanInput.txtSINSEI_SYOYU_NM")
                        .val()
                        .trimEnd(),
                    SINSEI_SYOYU_ADDR: $(
                        ".FrmFDHokanInput.txtSINSEI_SYOYU_ADDR"
                    )
                        .val()
                        .trimEnd(),
                    CHUMNNO: me.FrmFDHokanSelect.objArr["objfrm_PrpChumn_NO"],
                };

                me.data = {
                    request: arrayVal,
                };

                ajax.receive = function (result) {
                    var jsonResult = {};
                    var txtResult = '{ "json" : [' + result + "]}";
                    jsonResult = eval("(" + txtResult + ")");

                    if (jsonResult.json[0]["result"] == "noData") {
                        clsComFnc.FncMsgBox("E0003");
                        return;
                    } else if (jsonResult.json[0]["result"] == false) {
                        //エラーの場合
                        clsComFnc.FncMsgBox("E0003");
                        return;
                    }

                    //後処理
                    switch (strMenteFlg) {
                        case "UPD":
                            returnFlag = true;
                            me.FrmFDHokanSelect.subExeFnc(returnFlag);
                            break;
                    }
                };
                // 20201117 lqs upd S
                // ajax.send(url, me.data, 0, '');
                ajax.send(url, me.data, 0);
                // 20201117 lqs upd E
                // $.ajax(
                // {
                // type : "POST",
                // url : url,
                // data : me.data,
                //
                // success : function(result)
                // {
                // console.log(result);
                // var jsonResult =
                // {
                // };
                // var txtResult = '{ "json" : [' + result + ']}';
                // jsonResult = eval("(" + txtResult + ")");
                //
                // if (jsonResult.json[0]['result'] == 'noData')
                // {
                // clsComFnc.FncMsgBox("E0003");
                // return;
                // }
                // else
                // if (jsonResult.json[0]['result'] == false)
                // {
                // //エラーの場合
                // clsComFnc.FncMsgBox("E0003");
                // return;
                // }
                //
                // //後処理
                // switch(strMenteFlg)
                // {
                // case "UPD":
                // returnFlag = true;
                // me.FrmFDHokanSelect.subExeFnc(returnFlag);
                // break;
                // }
                // }
                // });
                break;
        }
    }

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var FrmFDHokanInput = new R4.FrmFDHokanInput();

    o_R4_R4.FrmFDHokanSelect.FrmFDHokanInput = FrmFDHokanInput;
    FrmFDHokanInput.FrmFDHokanSelect = o_R4_R4.FrmFDHokanSelect;

    FrmFDHokanInput.load();
});
