<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->css(array('Login/Login')) . "\n";
echo $this->Html->script(array('Login/Login'));
?>

<div class="Login Wrap">
    <!-- 2013-12-05 qiuqiu modify start -->
    <div class="Login LoginForm">
        <table style="font-size: 1.2em;" align="center" width="500" cellspacing="0" border=0>
            <tr>
                <td colspan="2" align="left">
                    <div class="LoginFormTitlePanel HMS-circle-conner">
                        <div class="Login LoginFormTitle HMS-circle-conner">
                            <span class="Login" aria-hidden="true"><b>ログイン</b></span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    <div class="Login outsideBorder">
                        <form>
                            <table class="Login LoginFormContentTbl" cellspacing="2" cellpadding="2">
                                <tr height='25px'>
                                    <td width="40%" align="right"> ユーザーID : </td>
                                    <td align="left">
                                        <input type="text" name="username" class="Login txtUser inputStyle Tab Enter"
                                            maxlength="20" onfocusin="this.style.backgroundColor='#F5DEB3' "
                                            onfocusout="this.style.backgroundColor='#FFFFFF'" autocomplete="username">
                                    </td>

                                </tr>
                                <tr height='25px'>
                                    <td align="right"> パスワード : </td>
                                    <td align="left">
                                        <input type="password" name="password"
                                            class="Login txtPassword inputStyle Tab Enter" maxlength="20"
                                            onfocusin="this.style.backgroundColor='#F5DEB3' "
                                            onfocusout="this.style.backgroundColor='#FFFFFF'" autocomplete="on">
                                    </td>
                                </tr>
                                <tr height='10px'>
                                    <td colspan="2" align="center">
                                        <div class="Login topBorder"></div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <table class="Login LoginFormContentTbl1" cellspacing="2" cellpadding="2">
                            <tr height='20px'>
                                <td colspan="6" align="center">
                                    <button class="Login btnLogin Tab Enter">
                                        ログイン
                                    </button>
                                    <button class="Login btnReset Tab Enter" style="margin-left:75px ">
                                        リセット
                                    </button>
                                </td>

                            </tr>
                            <!-- 		ID、パスワード忘れた機能  2013/12/11 fuxiaolin add end -->
                            <tr style="height: 15px">
                                <td colspan="6" align="center"><span
                                        class="Login lblForgetAll"><b>ユーザーID、パスワードを忘れた?</b></span></td>
                            </tr>


                            <tr>
                                <td colspan="6" align="center">
                                    <div class="Login ErrorInfo ui-state-error HMS-circle-conner"></div>
                                </td>
                            </tr>
                            <!-- 		ID、パスワード忘れた機能  2013/12/11 fuxiaolin add end -->
                        </table>
                    </div>
                </td>
            </tr>
            <!--20250513 ADD START-->
            <!--             <tr>
                <td colspan="6">
                <div class="divInformation"  id="info" >
                <div>【システム管理者よりお知らせ】</div>
                <div>ログイン後に何も表示されない場合は</div>
                <div>ブラウザの閲覧履歴を削除して</div>
                <div>再度ログインしてください</div>
                <div><a href="files/cacheclear.pdf" target="_blank">手順はこちら</a></div>								</div></td>
            </tr>
-->
            <!--20250513 ADD END-->

        </table>
        <footer>
            <div class="Login footer" style="position: absolute;bottom:10px;right: 5px;">
                株式会社（GD）（DZM）　Copyright (GD) (ZM). All Rights Reserved.
            </div>
        </footer>
    </div>
</div>

<!-- login画面  20121212 fuxiaolin edit end-->
<div id="LoginForgetAllStep_dialog"></div>
<div id="LoginForgetIdStep_dialog"></div>
<div id="LoginForgetPasswordStep_dialog"></div>
<div id="LoginSendMailSuc_dialog"></div>
<div id="indexLoading" align="center">
    <table align='center' align="center">
        <tr height="10px">

        </tr>
        <tr>
            <td><img src='img/sendMailLoading.gif' /></td>
        </tr>
        <tr>
            <td align="center"><b>送信中... ...</b></td>
        </tr>
    </table>
</div>

