<?php
/**
 * @var Cake\View\View $this
 */
//20210203 YIN INS S 社員個人記録入力
echo $this->Html->css(array('jquery/jquery.combo.select'));
echo $this->Html->script(array('jquery/jquery.combo.select'));
echo $this->Html->script(array('jquery/jquery.ajaxzip3-source'));
//20210203 YIN INS E 社員個人記録入力
echo $this->Html->script(array('HMHRMS/HMHRMS'));
echo $this->Html->css(array('HMHRMS/HMHRMS'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .ui-layout-center.ui-layout-pane.ui-layout-pane-center {
        position: absolute;
        margin: 0px;
        left: 210px;
        right: 210px;
        top: 34px;
        bottom: 34px;
        height: 470px;
        width: 1460px;
        z-index: 0;
        display: block;
        visibility: visible;
    }

    .HMHRMS.showName {
        font-size: 21px;
    }

    .HMHRMS.selfInfo {
        border: #979797 1px solid;
        padding: 10px;
        margin-bottom: 10px;
    }

    .HMHRMS.toggle:hover {
        background-color: #D5D5D5;
    }

    .HMHRMS.toggle {
        width: 100%;
        font-size: 1.17em;
        font-weight: bold;
        padding: 10px 0px;
        cursor: pointer;
    }

    .HMHRMS.inline {
        display: inline-table;
    }

    .HMHRMS input[type='text'],
    .HMHRMS input[type='search'] {
        width: 297px !important;
    }

    .HMHRMS-layout-center input[type='text'],
    select.commuteMethod,
    select.livingcondition {
        width: 400px !important;
    }

    .HMHRMS.textarea {
        margin: 0 auto;
        overflow: hidden;
        width: 400px;
        font-size: 14px;
        padding: 2px;
        border-radius: 4px;
        height: 15px;
    }

    .HMHRMS.dialogShow,
    .HMHRMS.cancelBtn,
    .HMHRMS.updateBtn {
        display: none;
    }

    .HMHRMS.table-content {
        margin-left: 20px
    }

    .HMHRMS.dialog_Btn {
        padding-right: 20px
    }

    input[type='text'].empAddress,
    input[type='text'].emergencyAddress,
    input[type='text'].emergencyAddress2 {
        width: 800px !important;
    }

    .HMHRMS.notes {
        color: red;
    }

    .HMHRMS.tdRight {
        text-align: right;
        width: 170px
    }

    .HMHRMS-layout-center input[type='search'] {
        width: 408px !important;
    }

    .HMHRMS.empZipCode::-ms-clear {
        display: none;
    }

    .HMHRMS.emergencyZipCode::-ms-clear {
        display: none;
    }

    .HMHRMS.emergencyZipCode2::-ms-clear {
        display: none;
    }

    .HMHRMS.photo {
        width: 120px;
        height: 120px;
    }

    .HMHRMS.photoDiv,
    .HMHRMS.uploadDiv {
        padding: 8px
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMHRMS.textarea {
            font-size: 10px;
        }

        .HMHRMS.showName {
            font-size: 16px;
        }
    }
</style>
<div class="ui-layout-center ui-layout-pane ui-layout-pane-center">
    <div class="ui-widget-content HMHRMS HMHRMS-layout-center">
        <div class="HMHRMS EmpMsg">
            <div>
                <label class='HMHRMS showName' for=""></label>
                <label class='HMHRMS showPhonetic' for=""></label>
            </div>
            <div class="HMHRMS selfInfo ">
                <div class="HMHRMS toggle"><img src="././img/mcdropdown/ico2.gif" class="h4bj">
                    個人情報
                </div>
                <div class="HMHRMS stretch slide">
                    <div class="HMHRMS HMS-button-pane">
                        <label class='HMHRMS notes' for="">入力した内容はすべて変更履歴として記録されますので、誤記がないように注意してください</label>
                        <button class="HMHRMS HMS-button-set btn Tab Enter editBtn" tabindex="3">
                            編集
                        </button>
                        <button class="HMHRMS HMS-button-set btn Tab Enter cancelBtn" tabindex="5">
                            キャンセル
                        </button>
                        <button class="HMHRMS HMS-button-set btn Tab Enter updateBtn" tabindex="4">
                            更新
                        </button>
                    </div>
                    <div class="HMHRMS photoDiv">
                        <img class="HMHRMS photo" src="http://192.168.2.170/hmhrms/face/error.png" />
                    </div>
                    <div class="HMHRMS uploadDiv">
                        <label tabindex="5" for=""> 写真ファイル </label>
                        <input class="HMHRMS txtFile" disabled="true" tabindex="5" readonly="readonly" />
                        <button class="HMHRMS cmdOpen btn Enter Tab" tabindex="5" style="display: none">
                            ...
                        </button>
                        <button class="HMHRMS cmdPDelete btn Enter Tab" tabindex="5" style="display: none">
                            ×
                        </button>
                    </div>
                    <div>
                        <table class="HMHRMS inline" cellspacing="8">
                            <tr>
                                <td>
                                    <h4>住 所</h4>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">郵便番号</td>
                                <td>
                                    <input type="text" class='HMHRMS empZipCode Enter Tab' name="yubin00" tabindex="6"
                                        maxlength="8" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">住　所</td>
                                <td>
                                    <input type="text" class='HMHRMS empAddress Enter Tab ' name="addr00" tabindex="7"
                                        maxlength="255" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">住居の状況</td>
                                <td>
                                    <select class='HMHRMS livingcondition Enter Tab' tabindex="8" id="livingcondition">
                                        <option value="自己所有">自己所有</option>
                                        <option value="親族所有">親族所有</option>
                                        <option value="借家等">借家等</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">ＴＥＬ</td>
                                <td>
                                    <input type="text" class='HMHRMS empTel Enter Tab' tabindex="9" maxlength="13" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">携帯電話</td>
                                <td>
                                    <input type="text" class='HMHRMS empMobile Enter Tab' tabindex="10"
                                        maxlength="13" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">メールアドレス（個人）</td>
                                <td>
                                    <input type="text" class='HMHRMS mail_address_personal Enter Tab' tabindex="11"
                                        maxlength="45" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">メールアドレス（会社）</td>
                                <td>
                                    <input type="text" class='HMHRMS mail_address_company Enter Tab' tabindex="12"
                                        maxlength="45" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>緊急連絡先１</h4>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">氏　名</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyName Enter Tab ' tabindex="13"
                                        maxlength="20" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">フリガナ</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyPhonetic Enter Tab' tabindex="14"
                                        maxlength="45" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">続　柄</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyRelation Enter Tab' tabindex="15"
                                        maxlength="10" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">郵便番号</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyZipCode Enter Tab' name="yubin01"
                                        tabindex="16" maxlength="8" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">住　所</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyAddress Enter Tab' name="addr01"
                                        tabindex="17" maxlength="255" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">ＴＥＬ</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyTel Enter Tab' tabindex="18"
                                        maxlength="13" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">携帯電話</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyMobile Enter Tab' tabindex="19"
                                        maxlength="13" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>緊急連絡先２</h4>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">氏　名</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyName2 Enter Tab ' tabindex="20"
                                        maxlength="20" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">フリガナ</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyPhonetic2 Enter Tab' tabindex="21"
                                        maxlength="45" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">続　柄</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyRelation2 Enter Tab' tabindex="22"
                                        maxlength="10" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">郵便番号</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyZipCode2 Enter Tab' name="yubin02"
                                        tabindex="23" maxlength="8" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">住　所</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyAddress2 Enter Tab' name="addr02"
                                        tabindex="24" maxlength="255" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">ＴＥＬ</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyTel2 Enter Tab' tabindex="25"
                                        maxlength="13" />
                                </td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">携帯電話</td>
                                <td>
                                    <input type="text" class='HMHRMS emergencyMobile2 Enter Tab' tabindex="26"
                                        maxlength="13" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>通 勤</h4>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">通勤方法</td>
                                <td><select class='HMHRMS commuteMethod Enter Tab HMHRMS-selectWidth'
                                        tabindex="27"></select></td>
                            </tr>
                            <tr class="HMHRMS hideControl">
                                <td class="HMHRMS tdRight">通勤距離</td>
                                <td>
                                    <input type="text" class='HMHRMS commuteDistance Enter Tab' tabindex="28"
                                        maxlength="8" />
                                    <label class="HMHRMS distanceKmShow" for=""> Km </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4>フリーコメント</h4>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">特技・趣味</td>
                                <td> <textarea class="HMHRMS textarea hobbies Enter Tab" maxlength="800"
                                        tabindex="29"></textarea></td>
                            </tr>
                            <tr>
                                <td class="HMHRMS tdRight">キャリア希望・特筆事項等</td>
                                <td> <textarea class="HMHRMS textarea freeDescription Enter Tab" maxlength="800"
                                        tabindex="30"></textarea></td>
                            </tr>
                            <tr class="HMHRMS microinformationshow">
                                <td class="HMHRMS tdRight">機微情報</td>
                                <td> <textarea class="HMHRMS textarea microinformation Enter Tab" maxlength="800"
                                        tabindex="31"></textarea></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div id="HMHRMStmpFileUpload"></div>
            <div class="HMHRMS selfInfo">
                <div class="HMHRMS toggle"><img src="././img/mcdropdown/ico2.gif" class="h4bj">
                    個人記録
                </div>
                <div class="HMHRMS HMS-button-pane table-content slide">
                    <div class="HMHRMS familyDialogDiv"></div>
                    <div class="HMHRMS toggle"><img src="././img/mcdropdown/ico2.gif" class="h4bj">
                        家族状況
                    </div>
                    <div class="HMHRMS slide">
                        <button class="HMHRMS btn Tab Enter family_addBtn" tabindex="32">
                            追加
                        </button>
                        <button class="HMHRMS btn Tab Enter family_editBtn" tabindex="33">
                            編集
                        </button>
                        <button class="HMHRMS btn Tab Enter family_delBtn" tabindex="34">
                            削除
                        </button>
                        <table class="HMHRMS jqtable" id="family_jqgridTable" tabindex="35"></table>
                    </div>
                    <div class="HMHRMS educationDialogDiv"></div>
                    <div class="HMHRMS toggle"><img src="././img/mcdropdown/ico2.gif" class="h4bj">
                        学歴
                    </div>
                    <div class="HMHRMS slide">
                        <button class="HMHRMS btn Tab Enter education_addBtn" tabindex="36">
                            追加
                        </button>
                        <button class="HMHRMS btn Tab Enter education_editBtn" tabindex="37">
                            編集
                        </button>
                        <button class="HMHRMS btn Tab Enter education_delBtn" tabindex="38">
                            削除
                        </button>
                        <table class="HMHRMS jqtable" id="education_jqgridTable" tabindex="39"></table>
                    </div>
                    <div class="HMHRMS toggle"><img src="././img/mcdropdown/ico2.gif" class="h4bj">
                        社外職歴
                    </div>
                    <div class="HMHRMS slide">
                        <button class="HMHRMS btn Tab Enter othercompany_addBtn" tabindex="40">
                            追加
                        </button>
                        <button class="HMHRMS btn Tab Enter othercompany_editBtn" tabindex="41">
                            編集
                        </button>
                        <button class="HMHRMS btn Tab Enter othercompany_delBtn" tabindex="42">
                            削除
                        </button>
                        <table class="HMHRMS jqtable" id="othercompany_jqgridTable" tabindex="43"></table>
                    </div>
                    <div class="HMHRMS toggle"><img src="././img/mcdropdown/ico2.gif" class="h4bj">
                        表彰歴
                    </div>
                    <div class="HMHRMS slide">
                        <button class="HMHRMS btn Tab Enter praise_addBtn" tabindex="44">
                            追加
                        </button>
                        <button class="HMHRMS btn Tab Enter praise_editBtn" tabindex="45">
                            編集
                        </button>
                        <button class="HMHRMS btn Tab Enter praise_delBtn" tabindex="46">
                            削除
                        </button>
                        <table class="HMHRMS jqtable" id="praise_jqgridTable" tabindex="47"></table>
                    </div>
                    <div class="HMHRMS toggle"><img src="././img/mcdropdown/ico2.gif" class="h4bj">
                        資格・免許
                    </div>
                    <div class="HMHRMS slide">
                        <button class="HMHRMS btn Tab Enter qualication_addBtn" tabindex="48">
                            追加
                        </button>
                        <button class="HMHRMS btn Tab Enter qualication_editBtn" tabindex="49">
                            編集
                        </button>
                        <button class="HMHRMS btn Tab Enter qualication_delBtn" tabindex="50">
                            削除
                        </button>
                        <table class="HMHRMS jqtable" id="qualication_jqgridTable" tabindex="51"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="HMHRMS dialogShow">
    <!-- 家族状況 -->
    <div class="HMHRMS Family">
        <table>
            <tr>
                <td>氏名</td>
                <td>
                    <input type="text" class='HMHRMS FamilyName Enter Tab' tabindex="52" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td>フリガナ</td>
                <td>
                    <input type="text" class='HMHRMS FamilyNamePhonetic Enter Tab' tabindex="53" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td>続柄</td>
                <td>
                    <input type="text" class='HMHRMS FamilyRelation Enter Tab ' tabindex="54" maxlength="10" />
                </td>
            </tr>
            <tr>
                <td>生年月日</td>
                <td>
                    <input type="text" class='HMHRMS FamilyBirthDate Enter Tab' tabindex="55" />
                </td>
            </tr>
            <tr>
                <td>年 齢</td>
                <td>
                    <input type="text" class='HMHRMS FamilyAge Enter Tab' tabindex="56" disabled="disabled" />
                </td>
            </tr>
            <!-- <tr>
                <td>同居別居</td>
                <td>
                    <input type="radio" class='HMHRMSFamilyStatus Enter Tab' id="tong" value="1" name="together"
                        tabindex="57" />
                    同
                    <input type="radio" class='HMHRMSFamilyStatus Enter Tab' id="bie" value="0" name="together"
                        checked="checked" tabindex="58" />
                    别
                </td> -->
            </tr>
        </table>
        <div class="HMHRMS HMS-button-pane">
            <div class="HMHRMS HMS-button-set">
                <div class="HMHRMS dialog_Btn">
                    <button class="HMHRMS btn Tab Enter family_cancelBtn" tabindex="59">
                        キャンセル
                    </button>
                    <button class="HMHRMS btn Tab Enter family_saveBtn" tabindex="60">
                        保 存
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- 学歴 -->
    <div class="HMHRMS Education">
        <table>
            <tr>
                <td>学校種別</td>
                <td><select class='HMHRMS SchoolType Enter Tab HMHRMS-selectWidth' tabindex="61"></select></td>
            </tr>
            <tr>
                <td>学校名</td>
                <td>
                    <input type="text" class='HMHRMS SchoolName Enter Tab' tabindex="62" maxlength="50" />
                </td>
            </tr>
            <tr>
                <td>学部・学科</td>
                <td>
                    <input type="text" class='HMHRMS Disciplines Enter Tab ' tabindex="63" maxlength="50" />
                </td>
            </tr>
            <tr>
                <td>所在地（国）</td>
                <td>
                    <input type="text" class='HMHRMS AdressCountry Enter Tab' tabindex="64" maxlength="50" />
                </td>
            </tr>
            <tr>
                <td>所在地（都道府県）</td>
                <td>
                    <input type="text" class='HMHRMS AdressPrefecture Enter Tab' tabindex="65" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>所在地（市）</td>
                <td>
                    <input type="text" class='HMHRMS AdressCity Enter Tab' tabindex="66" maxlength="50" />
                </td>
            </tr>
        </table>
        <div class="HMHRMS HMS-button-pane">
            <div class="HMHRMS HMS-button-set">
                <div>
                    <button class="HMHRMS btn Tab Enter Education_cancelBtn" tabindex="67">
                        キャンセル
                    </button>
                    <button class="HMHRMS btn Tab Enter Education_saveBtn" tabindex="68">
                        保 存
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 社外職歴 -->
    <div class="HMHRMS othercompany">
        <table>
            <tr>
            <tr>
                <td>年月Start</td>
                <td>
                    <input type="text" class='HMHRMS company_start Enter Tab' tabindex="69" />
                </td>
            </tr>
            <tr>
                <td>年月End</td>
                <td>
                    <input type="text" class='HMHRMS company_end Enter Tab' tabindex="70" />
                </td>
            </tr>
            <tr>
                <td>勤務地（国）</td>
                <td>
                    <input type="text" class='HMHRMS company_country Enter Tab' tabindex="71" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>勤務地（都道府県）</td>
                <td>
                    <input type="text" class='HMHRMS company_prefecture Enter Tab' tabindex="72" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>勤務地（市）</td>
                <td>
                    <input type="text" class='HMHRMS company_city Enter Tab' tabindex="73" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>社 名</td>
                <td>
                    <input type="text" class='HMHRMS company_name Enter Tab' tabindex="74" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>ポジション</td>
                <td>
                    <input type="text" class='HMHRMS company_position Enter Tab' tabindex="75" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>職務内容</td>
                <td>
                    <input type="text" class='HMHRMS job_content Enter Tab' tabindex="76" maxlength="255" />
                </td>
            </tr>
        </table>
        <div class="HMHRMS HMS-button-pane">
            <div class="HMHRMS HMS-button-set">
                <div class="HMHRMS dialog_Btn">
                    <button class="HMHRMS btn Tab Enter othercompany_cancelBtn" tabindex="77">
                        キャンセル
                    </button>
                    <button class="HMHRMS btn Tab Enter othercompany_saveBtn" tabindex="78">
                        保 存
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 表彰歴 -->
    <div class="HMHRMS praise">
        <table>
            <tr>
                <td>年</td>
                <td>
                    <input type="text" list="praisetimeyear" class='HMHRMS praiseyear Enter Tab' tabindex="79" />
                    <datalist id="praisetimeyear" class="HMHRMS praisedatalist"></datalist>
                </td>
            </tr>
            <tr>
                <td>月</td>
                <td>
                    <input type="text" list="praisetimemonth" class='HMHRMS praisemonth Enter Tab' tabindex="80" />
                    <datalist id="praisetimemonth">
                        <option value="01">
                        <option value="02">
                        <option value="03">
                        <option value="04">
                        <option value="05">
                        <option value="06">
                        <option value="07">
                        <option value="08">
                        <option value="09">
                        <option value="10">
                        <option value="11">
                        <option value="12">
                    </datalist>
                </td>
            </tr>
            <tr>
                <td>表彰内容</td>
                <td>
                    <input type="text" class='HMHRMS praisecontent Enter Tab' tabindex="81" maxlength="255" />
                </td>
            </tr>
        </table>
        <div class="HMHRMS HMS-button-pane">
            <div class="HMHRMS HMS-button-set">
                <div class="HMHRMS dialog_Btn">
                    <button class="HMHRMS btn Tab Enter praise_cancelBtn" tabindex="82">
                        キャンセル
                    </button>
                    <button class="HMHRMS btn Tab Enter praise_saveBtn" tabindex="83">
                        保 存
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 資格・免許 -->
    <div class="HMHRMS qualication">
        <table>
            <tr>
                <td>資格・免許</td>
                <td>
                    <input type="text" class='HMHRMS qualicationlicense Enter Tab' tabindex="84" maxlength="255" />
                </td>
            </tr>
            <tr>
                <td>取得時期(年)</td>
                <td>
                    <input type="text" list="timeyear" class='HMHRMS qualicationyear Enter Tab' tabindex="85" />
                    <datalist id="timeyear" class="HMHRMS qualicationdatalist"></datalist>
                </td>
            </tr>
            <tr>
                <td>取得時期(月)</td>
                <td>
                    <input type="text" list="timemonth" class='HMHRMS qualicationmonth Enter Tab' tabindex="86" />
                    <datalist id="timemonth">
                        <option value="01">
                        <option value="02">
                        <option value="03">
                        <option value="04">
                        <option value="05">
                        <option value="06">
                        <option value="07">
                        <option value="08">
                        <option value="09">
                        <option value="10">
                        <option value="11">
                        <option value="12">
                    </datalist>
                </td>
            </tr>
        </table>
        <div class="HMHRMS HMS-button-pane">
            <div class="HMHRMS HMS-button-set">
                <div class="HMHRMS dialog_Btn">
                    <button class="HMHRMS btn Tab Enter qualication_cancelBtn" tabindex="87">
                        キャンセル
                    </button>
                    <button class="HMHRMS btn Tab Enter qualication_saveBtn" tabindex="88">
                        保 存
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>