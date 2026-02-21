<?php
// echo $this -> Html -> css(array('Login/Login')) . "\n";
// echo $this -> Html -> script(array('Login/Login'));
?>
<!-- ログインできない場合のお手続き画面  2013/12/12 fuxiaolin add start-->
<div class="LoginForgetAllStep">
    <center>
        <div class="LoginForgetAllStep divChooseItem HMS-circle-conner">
            <div class="LoginForgetAllStep divChooseItemTitle" align="left">
                <div>
                    <b>お忘れになった項目を選択してください。</b>
                </div>
            </div>

            <div class="LoginForgetAllStep divChooseForgetID HMS-circle-conner">
                <div>
                    ユーザーIDを忘れた
                </div>
            </div>

            <div class="LoginForgetAllStep divChooseForgetPassword HMS-circle-conner">
                <div>
                    パスワードを忘れた
                </div>
            </div>

            <button class="LoginForgetAllStep btnCancel"
                style="float: right;margin-top:7px;margin-right: 8px;width: 100px">
                キャンセル
            </button>
        </div>
    </center>
</div>
<!-- ログインできない場合のお手続き  2013/12/12  fuxiaolin add end-->