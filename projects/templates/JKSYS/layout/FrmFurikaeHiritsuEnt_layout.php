<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('JKSYS/FrmFurikaeHiritsuEnt/FrmFurikaeHiritsuEnt'));
?>
<style type="text/css">
    .lbl-sky-xL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-top: 5px;
        width: 135px;
    }

    .center {
        text-align: center;
    }

    .lbl-yellow-M {
        height: 21px;
    }

    .lbl-sky-L {
        width: 101px;
    }

    .lbl-sky-xM {
        width: 25px;
        height: 100px;
    }

    input[maxlength='13'] {
        width: 140px;
        text-align: right;
    }

    td[rowspan='4'] {
        text-align: center;
        width: 25px;
    }

    .FrmFurikaeHiritsuEnt.set-inline {
        display: inline;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {


        .FrmFurikaeHiritsuEnt .lbl-sky-xM {
            height: 82px;
        }

        .lbl-yellow-M {
            height: 16px;
        }

        input[maxlength='13'] {
            width: 135px;
        }
    }
</style>

<div class='FrmFurikaeHiritsuEnt'>
    <div class='FrmFurikaeHiritsuEnt JKSYS-content JKSYS-content-fixed-width'>
        <fieldset>
            <legend class="FrmFurikaeHiritsuEnt lblSearchTitle">
                <b><span>検索条件</span></b>
            </legend>
            <div class="FrmFurikaeDenpyoEnt HMS-button-pane">
                <label class='FrmFurikaeHiritsuEnt Label18 lbl-sky-L' for="">対象年月</label>
                <input type="text" class='FrmFurikaeHiritsuEnt dtpTaisyouYM Enter Tab' tabindex="1" maxlength="6" />
                <button class="FrmFurikaeHiritsuEnt HMS-button-set  btn Tab Enter btnKensaku" tabindex="2">
                    検索
                </button>
            </div>
        </fieldset>
        <div>
            <table>
                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt lbl-yellow-M center lblState' for="">新規</label></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td></td>
                    <td><label class='FrmFurikaeHiritsuEnt Label10 lbl-sky-xL center' for=""> 正社員 </label></td>
                    <td><label class='FrmFurikaeHiritsuEnt Label11 lbl-sky-xL center' for=""> 契約社員 </label></td>
                </tr>

                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt Label1 lbl-sky-xL' for=""> 賞与見積
                        </label></td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtSyouyo Enter Tab' maxlength="13" tabindex="3" />
                    </td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKYKSyouyo Enter Tab' maxlength="13" tabindex="8" />
                    </td>

                </tr>
            </table>
            <table>
                <tr>
                    <td rowspan="4"><label class='FrmFurikaeHiritsuEnt Label9 lbl-sky-xM center' for="">
                            <br />
                            賞
                            <br />
                            与
                            <br />
                            分 </label></td>

                    <td><label class='FrmFurikaeHiritsuEnt Label2 lbl-sky-L' for=""> 健康保険料
                        </label></td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKenkou Enter Tab' maxlength="13" tabindex="4" />
                    </td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKYKKenkou Enter Tab' maxlength="13" tabindex="9" />
                    </td>
                </tr>

                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt Label3 lbl-sky-L' for=""> 介護保険料
                        </label></td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKaigo Enter Tab' maxlength="13" tabindex="5" />
                    </td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKYKKaigo Enter Tab' maxlength="13" tabindex="10" />
                    </td>
                </tr>
                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt Label4 lbl-sky-L' for="">
                            厚生年金保険料 </label></td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKouseiNenkin Enter Tab' maxlength="13" tabindex="6" />
                    </td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKYKKouseiNenkin Enter Tab' maxlength="13" tabindex="11" />
                    </td>
                </tr>
                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt Label7 lbl-sky-L' for="">
                            児童手当 </label></td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtJidouTeate Enter Tab' maxlength="13" tabindex="7" />
                    </td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKYKJidouTeate Enter Tab' maxlength="13" tabindex="12" />
                    </td>
                </tr>

            </table>
            <table>
                <tr>
                    <td></td>
                    <td><label class='FrmFurikaeHiritsuEnt Label12 lbl-sky-xL center' for=""> 対象者全て </label></td>
                </tr>

                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt Label6 lbl-sky-xL' for=""> 雇用保険料
                        </label></td>

                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtKoyou Enter Tab' maxlength="13" tabindex="13" />
                    </td>
                </tr>
                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt Label6 lbl-sky-xL' for="">
                            労災保険料 </label></td>

                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtRousai Enter Tab' maxlength="13" tabindex="14" />
                    </td>
                </tr>
                <tr>
                    <td><label class='FrmFurikaeHiritsuEnt Label8 lbl-sky-xL' for="">
                            退職手当 </label></td>
                    <td>
                        <input class='FrmFurikaeHiritsuEnt txtTaisyoku Enter Tab' maxlength="13" tabindex="15" />
                    </td>
                </tr>
            </table>
        </div>
        <div class="FrmFurikaeHiritsuEnt HMS-button-pane set-inline">
            <button class='FrmFurikaeHiritsuEnt btn btnChange Tab Enter' tabindex="19">
                条件変更
            </button>
            <div class="FrmFurikaeHiritsuEnt HMS-button-set">
                <button class='FrmFurikaeHiritsuEnt btn btnImport Tab Enter' tabindex="16">
                    退職金EXCEL取込
                </button>
                <button class='FrmFurikaeHiritsuEnt btn btnEnt Tab Enter' tabindex="17">
                    登録
                </button>
                <button class='FrmFurikaeHiritsuEnt btn btnDelete Tab Enter' tabindex="18">
                    削除
                </button>
                <button class='FrmFurikaeHiritsuEnt btn btnExcel Tab Enter' tabindex="19">
                    Excel出力
                </button>
            </div>
        </div>

    </div>
</div>