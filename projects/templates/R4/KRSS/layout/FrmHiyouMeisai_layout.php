<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmHiyouMeisai/FrmHiyouMeisai"));
?>

<!-- 画面個別の内容を表示 -->
<div class='KRSS FrmHiyouMeisai'>
    <div id="FrmHiyouMeisai" class='KRSS FrmHiyouMeisai content R4-content'>
        <fieldset>
            <legend>
                出力対象
            </legend>
            <div>
                <table border=0>
                    <tr>
                        <td>
                            <div class='label-snow' style='width:80px;'>
                                処理年月
                            </div>
                        </td>
                        <td>
                            <input type="text" class='KRSS FrmHiyouMeisai cboYM Enter Tab' style='width:80px;'
                                maxlength="6" />
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" valign="top">
                            <div class='label-snow' style='width:80px;'>
                                部署
                            </div>
                        </td>
                        <td>
                            <input type='text' style='width:55px' class='KRSS FrmHiyouMeisai txtBusyoCDFrom Enter Tab'
                                maxlength="3" />
                            <input type='text' disabled='disabled' style='width:224px;'
                                class='KRSS FrmHiyouMeisai lblBusyoCDFrom' />
                            ~
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' style='width:55px' class='KRSS FrmHiyouMeisai txtBusyoCDTo Enter Tab'
                                maxlength="3" />
                            <input type='text' disabled='disabled' style='width:224px;'
                                class='KRSS FrmHiyouMeisai lblBusyoCDTo'>
                        </td>
                    </tr>

                    <tr>
                        <td rowspan="2" valign="top">
                            <div class='label-snow' style='width:80px;'>
                                科目コード
                            </div>
                        </td>
                        <td>
                            <input type='text' style='width:55px' class='KRSS FrmHiyouMeisai txtKamokuCDFrom Enter Tab'
                                maxlength="5" />
                            <input type='text' disabled='disabled' style='width:224px;'
                                class='KRSS FrmHiyouMeisai lblKamokuCDFrom' />
                            ~
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type='text' style='width:55px' class='KRSS FrmHiyouMeisai txtKamokuCDTo Enter Tab'
                                maxlength="5" />
                            <input type='text' style='width:224px;' disabled='disabled'
                                class='KRSS FrmHiyouMeisai lblKamokuCDTo' />
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>

        <fieldset class='KRSS FrmHiyouMeisai frameTime ' style='margin-top:20px'>
            <table>
                <tr>
                    <td>
                        <div style='margin-right:30px'>
                            <label for="">終了予定時刻</label>
                        </div>
                    </td>

                    <td>
                        <div style='margin-right:30px'>
                            <input type='text' class='KRSS FrmHiyouMeisai txtStartTime '
                                style='width:93px;background-color:white' />
                        </div>
                    </td>

                    <td>
                        <div style='margin-right:30px'>
                            →&nbsp;<label for="" class="KRSS FrmHiyouMeisai finishTime"></label>
                        </div>
                    </td>

                    <td>
                        <div>
                            <input type='text' class='KRSS FrmHiyouMeisai txtEndTime '
                                style='width:93px;background-color:white' />
                        </div>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class='KRSS FrmHiyouMeisai lblMSG' style='margin-top:10px;color:blue'>

        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='KRSS FrmHiyouMeisai cmd004 Enter Tab'>
                    Excel出力
                </button>
                <!-- <button class='KRSS FrmHiyouMeisai cmd002 Enter Tab'>
                印刷
                </button> -->
            </div>
        </div>
    </div>
</div>
