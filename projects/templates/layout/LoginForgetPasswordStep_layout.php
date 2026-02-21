<?php
// echo $this -> Html -> css(array('Login/Login')) . "\n";
?>
<!-- ユーザーID あるいは　メールを入力してください画面  2013/12/12 fuxiaolin add start-->
<div class="LoginForgetPasswordStep">
    <center>
        <div class="LoginForgetPasswordStep divChooseItem HMS-circle-conner">
            <div class="LoginForgetPasswordStep divChooseItemTitle" align="left">
                <div style="margin-top: 2px">
                    <b>ユーザIDまたはメールアドレスを入力してください</b>
                </div>
            </div>

            <div class="LoginForgetPasswordStep divIDtoGetPassword HMS-circle-conner">
                <table border="0">
                    <tr>
                        <td width="115px" align="right">
                            <div class="Label">
                                <b>ユーザーID</b>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="LoginForgetPasswordStep txtId" id="txtId" />
                        </td>
                        <td>
                            <button class="LoginForgetPasswordStep btnSendMailById"
                                style="width: 110px;text-align:left">
                                送信
                            </button>
                        </td>
                    </tr>
                </table>

                <div class="LoginForgetPasswordStep divIdErrInfo">

                    <div class="LoginForgetPasswordStep lblIdErrInfo HMS-circle-conner">
                    </div>

                </div>
            </div>

            <div class="LoginForgetPasswordStep divEmailtoGetPassword HMS-circle-conner">
                <table border="0">
                    <tr>
                        <td width="115px" align="right">
                            <div class="Label">
                                <b>eメールアドレス</b>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="LoginForgetPasswordStep txtEmailAddress"
                                id="txtEmailAddressfgpw" />
                        </td>
                        <td>
                            <button class="LoginForgetPasswordStep btnSendMail" style="width: 110px;text-align:left">
                                送信
                            </button>
                        </td>
                    </tr>

                </table>
                <div class='LoginForgetPasswordStep divEmailFormatErrInfo'>

                    <div class="LoginForgetPasswordStep lblEmailFormatErrInfo HMS-circle-conner">
                    </div>

                </div>
            </div>

            <div class="LoginForgetPasswordStep divBtnPanel" style="float: right">
                <button class="LoginForgetPasswordStep btnCancel" style="margin-top:7px;margin-right: 3px;width: 100px">
                    キャンセル
                </button>
                <button class="LoginForgetPasswordStep btnBack" style="margin-top:7px;margin-right: 8px;width: 100px">
                    戻る
                </button>
            </div>

        </div>
    </center>
</div>
<!-- ユーザーID あるいは　メールを入力してください画面  2013/12/12 fuxiaolin add end-->