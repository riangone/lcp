Namespace.register("gdmz.common.file");

gdmz.common.file = function () {
    var me = new Object();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.ajax = new gdmz.common.ajax();
    me.action = "";
    me.accept =
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel";
    me.formId = "frmUpload";
    me.res = "";
    me.isfiles = false;

    me.select_file = function () {
        $("#file").trigger("click");
    };
    me.create = function () {
        var frm = "<form ";
        frm = frm + "id = '" + me.formId + "' ";
        frm = frm + "action = '" + me.action + "' ";
        frm = frm + "method = 'post' ";
        frm = frm + "style = 'display:none' ";
        frm = frm + "enctype ='multipart/form-data'";
        frm = frm + "target = 'ifrm' ";
        frm = frm + "> ";

        var ipt = "<input ";
        if (me.isfiles) {
            ipt = ipt + "multiple ";
            ipt = ipt + "name = 'file[]' ";
        } else {
            ipt = ipt + "name = 'file' ";
        }
        ipt = ipt + "id = 'file' ";
        ipt = ipt + "type = 'file' ";
        ipt = ipt + "accept = '" + me.accept + "' ";
        ipt = ipt + "> ";

        frm = frm + ipt;

        var ifrm = "<iframe ";
        ifrm = ifrm + "id = 'ifrm' ";
        ifrm = ifrm + "name = 'ifrm' ";
        ifrm = ifrm + "> ";
        ifrm = ifrm + "</iframe>";

        frm = frm + ifrm;

        frm = frm + "</form>";
        return frm;
    };

    me.remove = function () {
        $("#" + me.formId).remove();
    };

    me.send = function (func, efunc) {
        me.ajax.ShowLoading();
        var ifrm = $("#ifrm");
        ifrm.unbind().on("load", function () {
            me.ajax.CloseLoading();
            try {
                var response = ifrm.contents();
                res = response.find("body").text();
                me.remove();
                if (me.res == "HMHRMS") {
                    res = res.substr(res.indexOf('{"result":'));
                }
                result = eval("(" + res + ")");

                if (
                    result["result"] == true &&
                    result["data"] == "session is outdate"
                ) {
                    $("#sessionoutdate").dialog("open");
                    client = $(".LogineduserID").html();
                    $("#sessionoutuser").val(client);
                    $("#sessionoutpassword").focus();
                } else {
                    if (result["result"] == false) {
                        me.clsComFnc.FncMsgBox("E9999", result["data"]);
                        if (
                            me.res == "HMHRMS" ||
                            me.res == "HMAUD" ||
                            me.res == "FrmExcelTorikomiKyufu"
                        ) {
                            efunc();
                        }
                        return;
                    } else {
                        if (me.res == "HMHRMS") {
                            func(result["data"]);
                        } else {
                            func();
                        }
                    }
                }
            } catch (err) {
                func(err);
            }
        });

        var frm = $("#" + me.formId);
        frm.submit();
    };

    return me;
};
