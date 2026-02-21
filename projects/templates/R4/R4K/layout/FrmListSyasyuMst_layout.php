<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmListSyasyuMst/FrmListSyasyuMst"));
?>
<div class='FrmListSyasyuMst'>
    <div class='FrmListSyasyuMst  R4-content'>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table width=100% border=0>
                <tr>
                    <td width=50%>
                        <table border=0>
                            <tr>
                                <td>
                                    <label style='border:solid 1px;background-color:#B0E2FF' for="">
                                        区分
                                    </label>
                                </td>
                                <td>
                                    <input type='text' class='FrmListSyasyuMst txtCarKbn Enter Tab' tabindex="2"
                                        style='width:30px;margin-left:5px;' maxlength="2" />
                                    <label for="">
                                        商用車：0　乗用車：1　他チャネル：2
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label style='border:solid 1px;background-color:#B0E2FF' for="">
                                        括りコード
                                    </label>
                                </td>
                                <td>
                                    <input type='text' class='FrmListSyasyuMst txtKkrCD Enter Tab' tabindex="8"
                                        style='width:200px;margin-left:5px;' maxlength="3" />
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" width=50%>
                        <button style='margin-right:20px;' class='FrmListSyasyuMst cmdSearch Enter Tab' tabindex="3">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <table id="FrmListSyasyuMst_sprMeisai" class='FrmListSyasyuMst FrmListSyasyuMst_sprMeisai Enter Tab'>
        </table>
        <div id='FrmListSyasyuMst_sprList_pager'>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmListSyasyuMst cmdInsert Enter Tab' tabindex="7">
                    新規登録
                </button>
                <button class='FrmListSyasyuMst cmdUpdate Enter Tab' tabindex="5">
                    更新
                </button>
            </div>
        </div>
    </div>
</div>
