<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmMeisyoMst/FrmMeisyoMst"));
?>
<div class='FrmMeisyoMst'>
    <div class='FrmMeisyoMst  R4-content'>
        <table width=100% border=0>
            <tr>
                <td width=50%>
                    <table border=0>
                        <tr>
                            <td valign="top">
                                <label style='border:solid 1px;background-color:#B0E2FF' for="">
                                    名称ID
                                </label>
                                <input type='text' class='FrmMeisyoMst txtID Enter Tab' tabindex="2"
                                    style='width:30px;margin-left:5px;' maxlength="2" />
                            </td>
                            <td valign=top>

                                <button style='margin-right:20px;' class='FrmMeisyoMst cmdSearch Enter Tab'
                                    tabindex="3">
                                    検索
                                </button>
                            </td>
                            <td>
                                <table cellspacing="5"
                                    style='width:500px;border:solid 1px #4F94CD;background-color:#B2DFEE;'>
                                    <tr>
                                        <td width="150">
                                            <label for="">
                                                10：色名称
                                            </label>
                                        </td>
                                        <td>
                                            <label for="">
                                                16：所有権
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="150">
                                            <label for="">
                                                11：課税区分
                                            </label>
                                        </td>
                                        <td>
                                            <label for="">
                                                17：中古販売区分
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="150">
                                            <label for="">
                                                12：ｸﾚｼﾞｯﾄ会社
                                            </label>
                                        </td>
                                        <td>
                                            <label for="">
                                                18：中古仕入区分
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="150">
                                            <label for="">
                                                13：用途区分
                                            </label>
                                        </td>
                                        <td>
                                            <label for="">
                                                19：中古業名義
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="150" for="">
                                            <label>
                                                14：DM送付
                                            </label>
                                        </td>
                                        <td>
                                            <label for="">
                                                20：職制
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="150">
                                            <label for="">
                                                15：入庫約束
                                            </label>
                                        </td>
                                        <td>
                                            <label for="">

                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
        <table id="FrmMeisyoMst_sprMeisai" class='FrmMeisyoMst FrmMeisyoMst_sprMeisai Enter Tab'>
        </table>
        <div id='FrmMeisyoMst_sprList_pager'>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmMeisyoMst cmdUpdate Enter Tab' tabindex="5">
                    更新
                </button>
            </div>
        </div>
    </div>
</div>
