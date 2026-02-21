<?php
// echo $this -> Html -> css(array('Login/Login')) . "\n";
// echo $this -> Html -> script(array('Login/Login'));
?>
<!-- メールアドレスを入力してください画面  2013/12/12 fuxiaolin add start-->
<div class="LoginForgetIdStep">
    <center>
        <div class="LoginForgetIdStep divChooseItem HMS-circle-conner">
            <div class="LoginForgetIdStep divChooseItemTitle" align="left">
                <div style="height: 5px">
                </div>
                <div>
                    <b>eメールアドレスを入力してください。</b>
                </div>
            </div>

            <div class="LoginForgetIdStep divEmailgetID HMS-circle-conner">
                <div class="Label">
                    <b>eメールアドレス</b>
                </div>
                <input type="text" class="LoginForgetIdStep txtEmailAddress" id="txtEmailAddressfgid" />
                <button class="LoginForgetIdStep btnSendMail" style="width: 110px;margin-top: 3px;text-align:left">
                    送信
                </button>
                <div class="LoginForgetIdStep divEmailFormatErrInfo">

                    <div class="LoginForgetIdStep lblEmailFormatErrInfo HMS-circle-conner">

                    </div>

                </div>
            </div>
            <div class="LoginForgetIdStep divBtnPanel" style="float: right">

                <button class="LoginForgetIdStep btnCancel" style="margin-top:7px;margin-right: 3px;width: 100px">
                    キャンセル
                </button>
                <button class="LoginForgetIdStep btnBack" style="margin-top:7px;margin-right: 8px;width: 100px">
                    戻る
                </button>
            </div>
        </div>
    </center>
</div>
<!-- メールアドレスを入力してください画面  2013/12/12 fuxiaolin add end -->