Namespace.register("gdmz.common.jqgrid");

gdmz.common.jqgrid = {
    option: {
        mtype: "POST",
        datatype: "json",
        rowNum: 5,
        rowList: [5, 10, 20, 30],
        viewrecords: true,
        sortorder: "asc",
        shrinkToFit: false,
        multiselect: true,
        pagerpos: "center",
        recordpos: "center",
        caption: "caption",
        rownumbers: true,
        autowidth: false,
        rownumWidth: 40,
        recordtext: 2,
        //fuxiaolin add start 20140730
        gridview: false,
        loadui: "disable",
        scroll: false,
        //fuxiaolin add end 20140730
        //20211109 ZHANGBOWEN INS S
        footerrow: false,
        //20211109 ZHANGBOWEN INS E
        //20230601 lujunxia ins s
        //データがなし場合、テーブルに何も表示しない
        emptyRecordRow: false,
        //20230601 lujunxia ins e
    },
    get_selected_row_id: function (grid_id) {
        return $(grid_id).jqGrid("getGridParam", "selrow");
    },
    get_selected_row_data: function (grid_id, id) {
        return $(grid_id).jqGrid("getRowData", id);
    },
    //fuxl add start 20131023
    show: function (
        grid_id,
        url,
        colModel,
        pager,
        sortname,
        option,
        data,
        completeFnc
    ) {
        //20211229 ZHANGBOWEN INS S
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        //20211229 ZHANGBOWEN INS E
        var clsComFnc = new gdmz.common.clsComFnc();
        var ajax = new gdmz.common.ajax();
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }
        if (data != "undefined" || data != "" || data != NULL) {
            postData = {
                request: data,
                is_first_ajax: "",
            };
        }
        ajax.ShowLoading();
        $(grid_id)
            .jqGrid({
                url: url,
                pager: $(pager),
                colModel: colModel,
                sortname: sortname,
                //postData
                postData: postData,
                //option
                mtype: this.option.mtype,
                datatype: this.option.datatype,
                rowNum: this.option.rowNum,
                //rowList : this.option.rowList,
                viewrecords: this.option.viewrecords,
                sortorder: this.option.sortorder,
                shrinkToFit: this.option.shrinkToFit,
                multiselect: this.option.multiselect,
                //pagerpos : this.option.pagerpos,
                recordpos: this.option.recordpos,
                caption: this.option.caption,
                rownumbers: this.option.rownumbers,
                rownumWidth: this.option.rownumWidth,
                multiselectWidth: this.option.multiselectWidth,
                //fuxiaolin add start 20140730
                loadui: this.option.loadui,
                scroll: this.option.scroll,
                //fuxiaolin add end 20140730
                recordtext: "検索結果 {2}件を表示しました",
                //20230601 lujunxia ins s
                emptyRecordRow: this.option.emptyRecordRow,
                //20230601 lujunxia ins e
                loadComplete: function (data) {
                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }

                    ajax.CloseLoading();
                    if (data["data"] != "session is outdate") {
                        if (
                            completeFnc != null &&
                            completeFnc != "" &&
                            completeFnc != "undefined"
                        ) {
                            completeFnc();
                        }
                    } else {
                        if (data["data"] == "session is outdate") {
                            $("#sessionoutdate").dialog("open");
                            client = $(".LogineduserID").html();
                            $("#sessionoutuser").val(client);
                        }
                    }
                },

                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");

        this.set_grid_width(grid_id, 200);
        //20211229 ZHANGBOWEN INS S
        this.option = optionIndex;
        //20211229 ZHANGBOWEN INS E
    },
    //20200109 LUJUNXIA INS S
    showGridOptions: function (
        grid_id,
        url,
        colModel,
        pager,
        sortname,
        option,
        data,
        completeFnc
    ) {
        //20211229 ZHANGBOWEN INS S
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        //20211229 ZHANGBOWEN INS E
        var clsComFnc = new gdmz.common.clsComFnc();
        var ajax = new gdmz.common.ajax();
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }
        if (data != "undefined" || data != "" || data != NULL) {
            postData = {
                request: data,
                is_first_ajax: "",
            };
        }
        ajax.ShowLoading();
        $(grid_id)
            .jqGrid({
                url: url,
                pager: $(pager),
                colModel: colModel,
                sortname: sortname,
                //postData
                postData: postData,
                //option
                mtype: this.option.mtype,
                datatype: this.option.datatype,
                rowNum: this.option.rowNum,
                //rowList : this.option.rowList,
                viewrecords: this.option.viewrecords,
                sortorder: this.option.sortorder,
                shrinkToFit: this.option.shrinkToFit,
                multiselect: this.option.multiselect,
                //pagerpos : this.option.pagerpos,
                recordpos: this.option.recordpos,
                caption: this.option.caption,
                rownumbers: this.option.rownumbers,
                rownumWidth: this.option.rownumWidth,
                multiselectWidth: this.option.multiselectWidth,
                //fuxiaolin add start 20140730
                loadui: this.option.loadui,
                scroll: this.option.scroll,
                //fuxiaolin add end 20140730
                recordtext: "検索結果 {2}件を表示しました",
                //20230601 lujunxia ins s
                emptyRecordRow: this.option.emptyRecordRow,
                //20230601 lujunxia ins e
                ajaxGridOptions: {
                    //20200811 lujunxia upd s
                    complete: function (obj) {
                        //戻り値を受け取る
                        if (completeFnc) {
                            completeFnc(obj);
                        }
                    },
                    //20200811 lujunxia upd e
                },
                loadComplete: function (data) {
                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }

                    ajax.CloseLoading();
                    if (data["data"] == "session is outdate") {
                        $("#sessionoutdate").dialog("open");
                        client = $(".LogineduserID").html();
                        $("#sessionoutuser").val(client);
                    }
                },
                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");

        this.set_grid_width(grid_id, 200);
        //20211229 ZHANGBOWEN INS S
        this.option = optionIndex;
        //20211229 ZHANGBOWEN INS E
    },
    //20200109 LUJUNXIA INS E
    //fuxl add end 20131023
    //fuxl add start 2014/03/02
    showWithMesg: function (
        grid_id,
        url,
        colModel,
        pager,
        sortname,
        option,
        data,
        completeFnc
    ) {
        //20211229 ZHANGBOWEN INS S
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        //20211229 ZHANGBOWEN INS E
        var clsComFnc = new gdmz.common.clsComFnc();
        var ajax = new gdmz.common.ajax();
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }
        if (data != "undefined" || data != "" || data != NULL) {
            postData = {
                request: data,
                is_first_ajax: "",
            };
        }
        ajax.ShowLoading();
        $(grid_id)
            .jqGrid({
                url: url,
                pager: $(pager),
                colModel: colModel,
                sortname: sortname,
                //postData
                postData: postData,
                //option
                mtype: this.option.mtype,
                datatype: this.option.datatype,
                rowNum: this.option.rowNum,
                //rowList : this.option.rowList,
                viewrecords: this.option.viewrecords,
                sortorder: this.option.sortorder,
                shrinkToFit: this.option.shrinkToFit,
                multiselect: this.option.multiselect,
                //pagerpos : this.option.pagerpos,
                recordpos: this.option.recordpos,
                caption: this.option.caption,
                rownumbers: this.option.rownumbers,
                rownumWidth: this.option.rownumWidth,
                multiselectWidth: this.option.multiselectWidth,
                recordtext: "検索結果 {2}件を表示しました",
                //fuxiaolin add start 20140730
                loadui: this.option.loadui,
                scroll: this.option.scroll,
                //fuxiaolin add end 20140730
                //20230601 lujunxia ins s
                emptyRecordRow: this.option.emptyRecordRow,
                //20230601 lujunxia ins e
                loadComplete: function (data) {
                    // console.log(data);
                    var bErrorFlag = "normal";

                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            bErrorFlag = "error";
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }

                    ajax.CloseLoading();
                    if (data["data"] != "session is outdate") {
                        if (
                            completeFnc != null &&
                            completeFnc != "" &&
                            completeFnc != "undefined"
                        ) {
                            if (data["records"] == 0) {
                                bErrorFlag = "nodata";
                            }

                            completeFnc(bErrorFlag, data);
                        }
                    } else {
                        if (data["data"] == "session is outdate") {
                            $("#sessionoutdate").dialog("open");
                            client = $(".LogineduserID").html();
                            $("#sessionoutuser").val(client);
                        }
                    }
                    $(this).find("input[type='checkbox']").each(function () {
                        if (!$(this).attr("id")) {
                            $(this).attr("id", "input_" + Math.random().toString(36).substring(2, 9));
                        }
                    });
                },

                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");

        this.set_grid_width(grid_id, 200);
        //20211229 ZHANGBOWEN INS S
        this.option = optionIndex;
        //20211229 ZHANGBOWEN INS E
    },

    //fuxl add start 2014/07/30
    showWithMesgScroll: function (
        grid_id,
        url,
        colModel,
        pager,
        sortname,
        option,
        data,
        completeFnc
    ) {
        //20211229 ZHANGBOWEN INS S
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        //20211229 ZHANGBOWEN INS E
        var clsComFnc = new gdmz.common.clsComFnc();
        var ajax = new gdmz.common.ajax();
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }
        if (data != "undefined" || data != "" || data != NULL) {
            postData = {
                request: data,
                is_first_ajax: "",
            };
        }
        ajax.ShowLoading();
        $(grid_id)
            .jqGrid({
                url: url,
                pager: $(pager),
                colModel: colModel,
                sortname: sortname,
                //postData
                postData: postData,
                //option
                mtype: this.option.mtype,
                datatype: this.option.datatype,
                rowNum: this.option.rowNum,
                rowList: this.option.rowList,
                viewrecords: this.option.viewrecords,
                sortorder: this.option.sortorder,
                shrinkToFit: this.option.shrinkToFit,
                multiselect: this.option.multiselect,
                pagerpos: this.option.pagerpos,
                recordpos: this.option.recordpos,
                caption: this.option.caption,
                rownumbers: this.option.rownumbers,
                rownumWidth: this.option.rownumWidth,
                multiselectWidth: this.option.multiselectWidth,
                //fuxiaolin add start 20140730
                gridview: this.option.gridview,
                loadui: this.option.loadui,
                scroll: this.option.scroll,
                //fuxiaolin add end 20140730
                //20230601 lujunxia ins s
                emptyRecordRow: this.option.emptyRecordRow,
                //20230601 lujunxia ins e
                loadComplete: function (data) {
                    //console.log(data);
                    var bErrorFlag = "normal";

                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            bErrorFlag = "error";
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }

                    ajax.CloseLoading();
                    if (data["data"] != "session is outdate") {
                        if (
                            completeFnc != null &&
                            completeFnc != "" &&
                            completeFnc != "undefined"
                        ) {
                            if (data["records"] == 0) {
                                bErrorFlag = "nodata";
                            }
                            //20220506 WANGYING UPD S
                            //completeFnc(bErrorFlag);
                            completeFnc(bErrorFlag, data);
                            //20220506 WANGYING UPD E
                        }
                    } else {
                        if (data["data"] == "session is outdate") {
                            $("#sessionoutdate").dialog("open");
                            client = $(".LogineduserID").html();
                            $("#sessionoutuser").val(client);
                        }
                    }
                },

                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");

        this.set_grid_width(grid_id, 200);
        //20211229 ZHANGBOWEN INS S
        this.option = optionIndex;
        //20211229 ZHANGBOWEN INS E
    },

    show_2: function (
        grid_id,
        url,
        colModel,
        pager,
        sortname,
        option,
        data,
        completeFnc
    ) {
        //20211229 ZHANGBOWEN INS S
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        //20211229 ZHANGBOWEN INS E
        var clsComFnc = new gdmz.common.clsComFnc();
        var ajax = new gdmz.common.ajax();
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }
        if (data != "undefined" || data != "" || data != NULL) {
            postData = {
                request: data,
                is_first_ajax: "",
            };
        }
        ajax.ShowLoading();
        $(grid_id)
            .jqGrid({
                url: url,
                pager: $(pager),
                colModel: colModel,
                sortname: sortname,
                //postData
                postData: postData,
                //option
                //20160822 YIN INS S
                height: this.option.height,
                autowidth: this.option.autowidth,
                //20160822 YIN INS E
                mtype: this.option.mtype,
                datatype: this.option.datatype,
                rowNum: this.option.rowNum,
                rowList: this.option.rowList,
                viewrecords: this.option.viewrecords,
                sortorder: this.option.sortorder,
                shrinkToFit: this.option.shrinkToFit,
                multiselect: this.option.multiselect,
                pagerpos: this.option.pagerpos,
                recordpos: this.option.recordpos,
                caption: this.option.caption,
                rownumbers: this.option.rownumbers,
                rownumWidth: this.option.rownumWidth,
                multiselectWidth: this.option.multiselectWidth,
                // loadui : this.option.loadui, //"disable",
                //fuxiaolin add start 20140730
                loadui: this.option.loadui,
                scroll: this.option.scroll,
                //fuxiaolin add end 20140730
                //20230601 lujunxia ins s
                emptyRecordRow: this.option.emptyRecordRow,
                //20230601 lujunxia ins e
                loadComplete: function (data) {
                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }
                    ajax.CloseLoading();
                    if (data["data"] != "session is outdate") {
                        if (
                            completeFnc != null &&
                            completeFnc != "" &&
                            completeFnc != "undefined"
                        ) {
                            completeFnc();
                        }
                    } else {
                        if (data["data"] == "session is outdate") {
                            $("#sessionoutdate").dialog("open");
                            client = $(".LogineduserID").html();
                            $("#sessionoutuser").val(client);
                        }
                    }

                    // $(grid_id).jqGrid('setSelection', "0");
                },

                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");

        this.set_grid_width(grid_id, 200);

        //20211229 ZHANGBOWEN INS S
        this.option = optionIndex;
        //20211229 ZHANGBOWEN INS E
    },
    init: function (grid_id, url, colModel, pager, sortname, option) {
        //20211229 ZHANGBOWEN INS S
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        //20211229 ZHANGBOWEN INS E
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }

        $(grid_id).jqGrid({
            url: url,
            pager: $(pager),
            colModel: colModel,
            sortname: sortname,
            //option
            mtype: this.option.mtype,
            datatype: this.option.datatype,
            rowNum: this.option.rowNum,
            //rowList : this.option.rowList,
            viewrecords: this.option.viewrecords,
            sortorder: this.option.sortorder,
            shrinkToFit: this.option.shrinkToFit,
            multiselect: this.option.multiselect,
            //pagerpos : this.option.pagerpos,
            recordpos: this.option.recordpos,
            rownumbers: this.option.rownumbers,
            rownumWidth: this.option.rownumWidth,
            caption: this.option.caption,
            multiselectWidth: this.option.multiselectWidth,
            recordtext: "検索結果 {2}件を表示しました",
            //fuxiaolin add start 20140730
            loadui: this.option.loadui,
            scroll: this.option.scroll,
            //fuxiaolin add end 20140730
            //20211109 ZHANGBOWEN INS S
            footerrow: this.option.footerrow,
            //20211109 ZHANGBOWEN INS E
            //20230601 lujunxia ins s
            emptyRecordRow: this.option.emptyRecordRow,
            //20230601 lujunxia ins e
        });

        this.set_grid_width(grid_id, 200);
        //20211229 ZHANGBOWEN INS S
        this.option = optionIndex;
        //20211229 ZHANGBOWEN INS E
    },
    //20170614 YIN INS S
    init2: function (grid_id, url, colModel, pager, sortname, option) {
        //20211229 ZHANGBOWEN INS S
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        //20211229 ZHANGBOWEN INS E
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }

        $(grid_id).jqGrid({
            url: url,
            pager: $(pager),
            colModel: colModel,
            sortname: sortname,
            //option
            mtype: this.option.mtype,
            datatype: this.option.datatype,
            rowNum: this.option.rowNum,
            rowList: this.option.rowList,
            viewrecords: this.option.viewrecords,
            sortorder: this.option.sortorder,
            shrinkToFit: this.option.shrinkToFit,
            multiselect: this.option.multiselect,
            //pagerpos : this.option.pagerpos,
            recordpos: this.option.recordpos,
            rownumbers: this.option.rownumbers,
            rownumWidth: this.option.rownumWidth,
            caption: this.option.caption,
            multiselectWidth: this.option.multiselectWidth,
            //fuxiaolin add start 20140730
            loadui: this.option.loadui,
            scroll: this.option.scroll,
            //fuxiaolin add end 20140730
            //20230601 lujunxia ins s
            emptyRecordRow: this.option.emptyRecordRow,
            //20230601 lujunxia ins e
        });

        this.set_grid_width(grid_id, 200);

        //20211229 ZHANGBOWEN INS S
        this.option = optionIndex;
        //20211229 ZHANGBOWEN INS E
    },
    //20170614 YIN INS E
    //20230811 caina ins s
    init_tree: function (grid_id, url, colModel, pager, sortname, option) {
        var optionIndex = JSON.parse(JSON.stringify(this.option));
        if (option != "undefined") {
            for (key in option) {
                this.option[key] = option[key];
            }
        }
        $(grid_id).jqGrid({
            url: url,
            colModel: colModel,
            sortname: sortname,
            //option
            mtype: this.option.mtype,
            datatype: this.option.datatype,
            viewrecords: this.option.viewrecords,
            sortorder: this.option.sortorder,
            shrinkToFit: this.option.shrinkToFit,
            multiselect: this.option.multiselect,
            recordpos: this.option.recordpos,
            rownumWidth: this.option.rownumWidth,
            caption: this.option.caption,
            multiselectWidth: this.option.multiselectWidth,
            recordtext: "検索結果 {2}件を表示しました",
            loadui: this.option.loadui,
            scroll: this.option.scroll,
            footerrow: this.option.footerrow,
            treeGrid: this.option.treeGrid,
            treeGridModel: this.option.treeGridModel,
            ExpandColumn: this.option.ExpandColumn,
            ExpandColClick: this.option.ExpandColClick,
            treeReader: this.option.treeReader,
            //20250108 lujunxia ins s
            emptyRecordRow: this.option.emptyRecordRow,
            //20250108 lujunxia ins e
        });
        this.set_grid_width(grid_id, 200);
        this.option = optionIndex;
    },
    //20230811 caina ins e
    reload: function (grid_id, data, completeFnc) {
        var clsComFnc = new gdmz.common.clsComFnc();
        postData = {
            request: data,
            is_first_ajax: "",
        };
        $(grid_id)
            .jqGrid("setGridParam", {
                postData: postData,
                loadComplete: function (data) {
                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }
                    if (data["data"] != "session is outdate") {
                        if (
                            completeFnc != null &&
                            completeFnc != "" &&
                            completeFnc != "undefined"
                        ) {
                            completeFnc();
                        }
                    } else {
                        if (data["data"] == "session is outdate") {
                            $("#sessionoutdate").dialog("open");
                            client = $(".LogineduserID").html();
                            $("#sessionoutuser").val(client);
                        }
                    }
                },
                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");
    },
    //20200109 LUJUNXIA INS S
    reloadGridOptions: function (grid_id, data, completeFnc) {
        var clsComFnc = new gdmz.common.clsComFnc();
        postData = {
            request: data,
            is_first_ajax: "",
        };
        $(grid_id)
            .jqGrid("setGridParam", {
                postData: postData,
                ajaxGridOptions: {
                    //20200811 lujunxia upd s
                    complete: function (obj) {
                        //戻り値を受け取る
                        if (completeFnc) {
                            completeFnc(obj);
                        }
                    },
                    //20200811 lujunxia upd e
                },
                loadComplete: function (data) {
                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }
                    if (data["data"] == "session is outdate") {
                        $("#sessionoutdate").dialog("open");
                        client = $(".LogineduserID").html();
                        $("#sessionoutuser").val(client);
                    }
                },
                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");
    },
    //20200109 LUJUNXIA INS E
    //20220425 lujunxia upd s
    //reloadMessage : function(grid_id, data, completeFnc)
    // 20230731 YIN UPD S
    // reloadMessage: function (grid_id, data, completeFnc, showPageNum) {
    reloadMessage: function (
        grid_id,
        data,
        completeFnc,
        showPageNum,
        keepScrolling
    ) {
        // 20230731 YIN UPD E
        var clsComFnc = new gdmz.common.clsComFnc();
        var ajax = new gdmz.common.ajax();
        // 20230731 YIN INS S
        var scrollPosition = $(grid_id).closest(".ui-jqgrid-bdiv").scrollTop();
        // 20230731 YIN INS E
        postData = {
            request: data,
            is_first_ajax: "",
        };
        ajax.ShowLoading();
        $(grid_id)
            .jqGrid("setGridParam", {
                postData: postData,
                page:
                    showPageNum != null && showPageNum != ""
                        ? showPageNum
                        : undefined,
                //20220425 lujunxia upd e
                loadComplete: function (data) {
                    var bErrorFlag = "normal";

                    if (typeof data["result"] != "undefined") {
                        if (!data["result"]) {
                            bErrorFlag = "error";
                            clsComFnc.FncMsgBox("E9999", data["data"]);
                        }
                    }
                    ajax.CloseLoading();
                    if (data["data"] != "session is outdate") {
                        if (
                            completeFnc != null &&
                            completeFnc != "" &&
                            completeFnc != "undefined"
                        ) {
                            if (data["records"] == 0) {
                                bErrorFlag = "nodata";
                            }

                            completeFnc(bErrorFlag, data);
                            // 20230731 YIN INS S
                            if (keepScrolling) {
                                $(grid_id)
                                    .closest(".ui-jqgrid-bdiv")
                                    .scrollTop(scrollPosition);
                            }
                            // 20230731 YIN INS E
                        }
                    } else {
                        if (data["data"] == "session is outdate") {
                            $("#sessionoutdate").dialog("open");
                            client = $(".LogineduserID").html();
                            $("#sessionoutuser").val(client);
                        }
                    }
                    $(this).find("input[type='checkbox']").each(function () {
                        if (!$(this).attr("id")) {
                            $(this).attr("id", "input_" + Math.random().toString(36).substring(2, 9));
                        }
                    });
                },
                loadError: function (xhr, status, error) {
                    console.log("ERROR INFO:" + status + error);
                },
            })
            .trigger("reloadGrid");
    },
    set_grid_width: function (grid_id, w) {
        $(grid_id).jqGrid("setGridWidth", w);
    },
    set_grid_height: function (grid_id, h) {
        $(grid_id).jqGrid("setGridHeight", h);
    },
    set_grid_setSelection: function (grid_id, no) {
        $(grid_id).jqGrid("setSelection", no);
    },
    /**
     * 20190911 zhangXL INS
     * 设置键盘事件（enter，shift+tab，tab）
     * @param {*} grid_id jqGrid的ID
     * @param {*} element 当前选择对象
     * @param {*} lastsel 当前选择行ID
     * @param {*} fncComplete 可选参数,自定义方法
     */
    setKeybordEvents: function (
        grid_id,
        element,
        lastsel,
        fncComplete,
        grid_name
    ) {
        //选择行时设置前行后行的行号
        var upsel, nextsel;
        var rowids = $(grid_id).jqGrid("getDataIDs");
        for (var i = 0, len = rowids.length; i < len; i++) {
            if (rowids[i] == lastsel) {
                upsel = rowids[i - 1];
                nextsel = rowids[i + 1];
                break;
            }
        }

        //获取第一列和最后一列的列名
        var colAttr = $(grid_id).jqGrid("getGridParam", "colModel");
        var findAttr = colAttr.filter(function (item) {
            return !item.hidden && item.editable;
        });
        //20220111 WANGYING UPD S
        //var firstColNM = findAttr ? findAttr[0].name : undefined;
        var firstColNM = findAttr && findAttr[0] ? findAttr[0].name : undefined;
        //var lastColNM = findAttr ? findAttr[findAttr.length - 1].name : undefined;
        var lastColNM =
            findAttr && findAttr[findAttr.length - 1]
                ? findAttr[findAttr.length - 1].name
                : undefined;
        //20220111 WANGYING UPD E

        //选中行时，默认将整个单元格内容选中
        if (element && element.target) {
            var selNextId;
            if (element.target.tagName !== "TD") {
                selNextId = "#" + lastsel + "_" + element.target.name;
                $(selNextId).trigger("focus");
                $(selNextId).select();
            } else {
                if ($(element.target).children(".editable").length !== 0) {
                    $(element.target).children(".editable").select();
                } else {
                    selNextId = "#" + lastsel + "_" + firstColNM;
                    $(selNextId).select();
                }
            }
        } else {
            if (grid_name) {
                var selNextId =
                    "#" + lastsel + "_" + firstColNM + "_" + grid_name;
            } else {
                var selNextId = "#" + lastsel + "_" + firstColNM;
            }
            $(selNextId).trigger("focus");
            $(selNextId).select();
        }

        //设置所有可编辑单元格的事件
        $(grid_id)
            .find("td .editable")
            .on("focus", function (e) {
                $(e.target).select();
            })
            .on("keydown", function (e) {
                var key = e.charCode || e.keyCode;
                //20220729 lujunxia upd s
                //enter or tab
                //if (key == 13 || (key == 9 && !e.shiftKey))
                //textarea : require a line break when pressing enter
                if (
                    (key == 13 && e.target.type != "textarea") ||
                    (key == 9 && !e.shiftKey)
                ) {
                    //20220729 lujunxia upd e
                    if (!nextsel && e.target.name === lastColNM) {
                        return false;
                    }

                    var nextCtrl = $(e.target)
                        .parent()
                        .nextAll()
                        .find(".editable:visible")
                        .first();
                    if (nextCtrl && nextCtrl.length > 0) {
                        nextCtrl.trigger("focus");
                        nextCtrl.select();
                    } else {
                        // 20230607 lujunxia upd s
                        // clientArray:the data is not posted to the server but rather is saved only to the grid
                        // $(grid_id).jqGrid('saveRow', lastsel);
                        $(grid_id).jqGrid(
                            "saveRow",
                            lastsel,
                            null,
                            "clientArray"
                        );
                        // 20230607 lujunxia upd e
                        $(grid_id).jqGrid("setSelection", nextsel, true);
                    }
                    return false;
                } //
                //shift+tab
                else if (e.shiftKey && key == 9) {
                    if (!upsel && e.target.name === firstColNM) {
                        return false;
                    }

                    var prevCtrl = $(e.target)
                        .parent()
                        .prevAll()
                        .find(".editable:visible")
                        .last();
                    if (prevCtrl && prevCtrl.length > 0) {
                        prevCtrl.trigger("focus");
                        prevCtrl.select();
                    } else {
                        // 20230607 lujunxia upd s
                        // clientArray:the data is not posted to the server but rather is saved only to the grid
                        // $(grid_id).jqGrid('saveRow', lastsel);
                        $(grid_id).jqGrid(
                            "saveRow",
                            lastsel,
                            null,
                            "clientArray"
                        );
                        // 20230607 lujunxia upd e
                        $(grid_id).jqGrid("setSelection", upsel, true);
                        if (grid_name) {
                            var selNextId =
                                "#" + upsel + "_" + lastColNM + "_" + grid_name;
                        } else {
                            var selNextId = "#" + upsel + "_" + lastColNM;
                        }
                        //shift+tab
                        $(selNextId).trigger("focus");
                        $(selNextId).select();
                    }
                    return false;
                }
            });

        if (fncComplete) fncComplete();

        return [upsel, nextsel];
    },
};
