<?php
/**
 * @var Cake\View\View $this
 */
//css start
echo $this->Html->css(array('Main/Main'));
echo $this->Html->css(array('R4/R4/R4'));
//---20170425 li DEL S.
// echo $this -> Html -> css(array('APP/APPM'));
//---20170425 li DEL E.
//css end
echo $this->Html->script(array('Main/Main'));
echo $this->Html->script(array('Login/Relogin'));
?>
<!--20210114 WL INS S -->
<style>
    .LoginedInfo {
        text-decoration: none;
        color: #4169E1;
        font-size: 9pt;
    }

    .LoginedInfo:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    .ten {
        width: 10px;
        height: 21px;
    }

    .labelWidth {
        width: 120px;
    }

    .wholePassword {
        width: 120px;
    }

    .txtUserName {
        color: #000;
        width: 183px;
    }

    .txtUserMail {
        color: #000;
        width: 183px;
    }

    #btn_saveInfo {
        margin-left: 285px;
    }

    #btn_savePS {
        margin-left: 285px;
    }

    .lower {
        width: 550px;
    }

    #txtOldPs {
        width: 183px;
    }

    #txtNewPs {
        width: 183px;
    }

    #txtNewConfirmationPs {
        width: 183px;
    }

    #divNameformat {
        width: 425px;
        margin-left: 5px;
    }

    #divMailformat {
        width: 425px;
        margin-left: 5px;
    }

    .lblusershow {
        height: 220px;
        /* display: none; */
    }

    .lbluserhide {
        height: 195px;
    }

    #div_changePassword {
        height: 260px;
    }

    #emailformatWrong {
        width: 410px;
        margin-left: 7px;
    }

    .userDialog {
        height: 40px;
        display: flex;
        align-items: center;
    }

    #nameformatWrong {
        margin-left: 7px;
    }

    #lblOldPs {
        margin-left: 7px;
    }

    #lblNewPs {
        margin-left: 7px;
    }

    #lblNewConfirmationPs {
        margin-left: 7px;
    }

    #divOldPS {
        margin-left: 5px;
    }

    #divNewPS {
        margin-left: 5px;
    }

    #divNewConfirmationPS {
        margin-left: 5px;
    }

    .usertitle {
        margin-left: 21px;
    }

    .passwordFout {
        color: red;
        font-size: 12px;
        margin-left: 130px;
    }
</style>
<!--20210114 WL INS E -->
<div id="outer-north" class="ui-layout-pane ui-layout-pane-north">
    <table style="white-space:nowrap;">
        <tr>
            <td style="font-size: 28px;">
                （GD）（DZM）社内システム
            </td>
            <td width="100%">
            </td>
            <!--20210114 WL UPD S-->
            <!-- <td valign="bottom">
            <label class='LogineduserID'>
            </label>
            </td>
            <td valign="bottom">
            <label class='LogineduserName'>
            </label>
            </td>  -->
            <td valign="bottom">
                <div class="LoginedInfo"><span class="LogineduserID"></span>&nbsp;<span class="LogineduserName"></span>
                </div>
            </td>
            <!--20210114 WL UPD E-->
            <td valign="bottom">
                <!-- 20220505 WANGYING UPD S -->
                <!-- <button class="logout" style="position: relative;bottom: -5px;"> -->
                <button class="logout" style="position: relative;">
                    ログアウト
                </button>
                <!-- 20220505 WANGYING UPD E -->
            </td>
        </tr>
    </table>
</div>

<div id="outer-south" class=" ui-layout-pane ui-layout-pane-south" style="display: none">
    株式会社（GD）（DZM）　Copyright (GD) (ZM). All Rights Reserved.
</div>

<div id="outer-center"
    class=" ui-layout-pane ui-layout-pane-center ui-layout-container ui-tabs ui-widget ui-widget-content ui-corner-all ui-layout-pane-hover ui-layout-pane-center-hover ui-layout-pane-open-hover ui-layout-pane-center-open-hover">
    <ul id="tabbuttons"
        class=" ui-layout-pane ui-layout-pane-north ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all ui-sortable">
        <?php echo $tab_buttons; ?>
    </ul>
    <div id="tabpanels"
        class="ui-layout-pane ui-layout-pane-center ui-layout-pane-hover ui-layout-pane-center-hover ui-layout-pane-open-hover ui-layout-pane-center-open-hover">
        <?php echo $tab_panels; ?>
    </div>
    <div id="tabbuttons-resizer"
        class="ui-layout-resizer ui-layout-resizer-north ui-layout-resizer-open ui-layout-resizer-north-open">
    </div>
</div>
<input id='tabnum' type="text" value='<?php echo $tabnamestring; ?>' style="visibility:hidden" />
<input id='r4name' type="text" value='<?php echo $r4_name; ?>' style="visibility:hidden" />
<!-- ---20161229 li INS S. -->
<input id='appname' type="text" value='<?php echo $app_name; ?>' style="visibility:hidden" />
<!-- ---20161229 li INS E. -->
<!--20190221 YIN INS start chrome 72 version-->
<input id='padname' style="width:0px;height: 0px;padding: 0px;border: 0px" />
<!--20190221 YIN INS end chrome 72 version-->
<div id="sessionoutdate" scroll="no" class="session_outdate" style="display: none">
    <div align="center">

        <label>
            <b>セッションがタイムアウトしました。もう一度ログインしてください。</b>
        </label>
    </div>
    <br />
    <div align="center">
        <table>
            <tr>
            </tr>
            <tr>
                <td align="right">
                    ユーザー ID :
                </td>
                <td>
                    <input id="sessionoutuser" type="text" maxlength="20" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td align="right">
                    パスワード :
                </td>
                <td>
                    <form onsubmit="return false;">
                        <input id="sessionoutpassword" maxlength="20" type="password"
                            onfocusin="this.style.backgroundColor='#F5DEB3' "
                            onfocusout="this.style.backgroundColor='#FFFFFF'" autocomplete="on" />
                    </form>
                </td>
            </tr>
        </table>
        <div style="height: 5px">
        </div>
        <div id="reloginErrMessage" align="center" class="ui-state-error HMS-circle-conner"
            style="display: block;border: 0px;">
        </div>
        <div align="center">
            <a href="">ここをクリックして、ユーザーIDを切り替えてください。</a>
        </div>
        <div style="height: 3px">
        </div>

    </div>
</div>
<!--20141024  fan upd s.-->
<!-- <div id="indexLoading" style="display: none">
<table align='center'>
<tr>
<td><img src='img/loading_index.gif' />
</td>
</tr>
</table>
</div> -->
<!--20141204 fanzhengzhou ins s.-->
<div id="SDH_session_outdate" style="display: none">
    <table align='center'>
        <tr>
            <td>
                セッションがタイムアウトしました。もう一度ログインしてください。
            </td>
        </tr>
    </table>
</div>
<!--20141204 fanzhengzhou ins e.-->

<!--20210114 WL INS S-->
<div style="display:none;">
    <div class="div_personinfo">
        <div id="div_userInformation">
            <h2 class="lbluserInformation"><span class='usertitle'>ユーザー情報設定<span></h2>
            <div class='lblusershow'>
                <div class='userDialog'>

                    <label class="ten">
                        ●
                    </label>
                    <label class='labelWidth'>
                        社員番号:
                    </label>
                    <label class="lbluserID">
                    </label>
                </div>
                <div class='userDialog'>
                    <label class="ten">
                        ●
                    </label>
                    <label class='labelWidth'>
                        社員氏名:
                    </label>
                    <input class="txtUserName" />
                    <font class='ui-state-error ui-corner-all' id="divNameformat">
                        <label id="nameformatWrong">
                        </label>
                    </font>
                </div>

                <div class='userDialog'>
                    <label class="ten">
                        ●
                    </label>
                    <label class='labelWidth'>
                        イーメール:
                    </label>
                    <input class='txtUserMail' value="" />

                    <font class='ui-state-error ui-corner-all' id="divMailformat">
                        <label id="emailformatWrong">
                        </label>
                    </font>
                </div>

                <div class='userDialog'>
                    <label class="ten">
                        ●
                    </label>
                    <label class='labelWidth'>
                        所属名:
                    </label>
                    <label class="txtUserPosition">
                    </label>
                </div>
                <div>
                    <button class="ui-state-default ui-corner-all" id="btn_saveInfo">
                        保存
                    </button>
                    <label class="lower">
                    </label>
                </div>

            </div>
        </div>

        <div id="div_changePassword">
            <h2 class="lbluserPassword"><span class='usertitle'>パスワード変更</span></h2>
            <div class='lbluserhide'>

                <form>
                    <div class='userDialog'>
                        <label class="ten">
                            ●
                        </label>
                        <label class='wholePassword'>
                            古いパスワード:
                        </label>
                        <input type='password' id='txtOldPs' autocomplete="on" />
                        <font class='ui-state-error ui-corner-all' id="divOldPS">
                            <label id="lblOldPs">
                            </label>
                        </font>

                    </div>
                    <div class='passwordFout'>
                        パスワードはアルファベットまたは数字で6文字以上8文字以内で設定してください
                    </div>

                    <div class='userDialog'>
                        <label class="ten">
                            ●
                        </label>
                        <label class='wholePassword'>
                            新しいパスワード:
                        </label>
                        <input type='password' id='txtNewPs' autocomplete="on" />
                        <font class='ui-state-error ui-corner-all' id="divNewPS">
                            <label id="lblNewPs">
                            </label>
                        </font>
                    </div>
                    <div class='userDialog'>
                        <label class="ten">
                            ●
                        </label>
                        <label class='wholePassword'>
                            パスワードの確認:
                        </label>
                        <input type='password' id='txtNewConfirmationPs' autocomplete="on" />

                        <font class='ui-state-error ui-corner-all' id="divNewConfirmationPS">
                            <label id="lblNewConfirmationPs">
                            </label>
                        </font>
                    </div>
                </form>
                <div>
                    <button class="ui-state-default ui-corner-all" id="btn_savePS">
                        保存
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<!--20210114 WL INS E-->