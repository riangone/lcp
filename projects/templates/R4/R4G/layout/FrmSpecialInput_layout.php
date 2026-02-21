<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmSpecialInput/FrmSpecialInput'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="FrmSpecialInput R4-content">
    <div class="FrmSpecialInput pad">
        <table>
            <tr>
                <td>
                    <label class="FrmSpecialInput lbl-grey-L">
                        &nbsp;注文書番号&nbsp;
                    </label>
                    <input type="text" class="FrmSpecialInput lblCmnNO" readonly="readonly" tabindex="-1" />
                </td>
                <td width="15px">
                </td>
                <td width="">
                    <button class="FrmSpecialInput cmdInsert Tab Enter">
                        追加
                    </button>
                </td>
                <td width="">
                    <button class="FrmSpecialInput cmdUpdate Tab Enter">
                        修正
                    </button>
                </td>
            </tr>
        </table>
    </div>

    <div style="width:99%;margin-top:5px;">
        <table class='FrmSpecialInput dataInfoArea dataInfoTable' width=100% border=0 cellspacing="0">
            <tr>
                <td height="15px">
                    <label class='FrmSpecialInput dataInfoText1 lbl-sky-L'>
                        &nbsp;契約者
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblKeiyakusya tag-L' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <label class='FrmSpecialInput dataInfoText2 lbl-sky-M'>
                        &nbsp;部署
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblBusyoCD tag-M' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblBusyoNM tag-L' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <label class='FrmSpecialInput dataInfoText6 lbl-sky-L'>
                        &nbsp;問合呼称
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblKosyou tag-L' readonly="readonly" tabindex="-1" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='FrmSpecialInput dataInfoText4 lbl-sky-L'>
                        &nbsp;使用者
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblSiyosya tag-L' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <label class='FrmSpecialInput dataInfoText5 lbl-sky-M'>
                        &nbsp;社員
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblSyainNO tag-M' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblSyainNM tag-L' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <label class='FrmSpecialInput dataInfoText3 lbl-sky-L'>
                        &nbsp;架装番号
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblKasouNO tag-L' readonly="readonly" tabindex="-1" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='FrmSpecialInput dataInfoText7 lbl-sky-L'>
                        &nbsp;使用者カナ
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblSiyosyaKN tag-L' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <label class='FrmSpecialInput dataInfoText8 lbl-sky-M'>
                        &nbsp;販売店
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblHanbaiCD tag-M' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblHanbaiNM tag-L' readonly="readonly" tabindex="-1" />
                </td>
                <td>
                    <label class='FrmSpecialInput dataInfoText9 lbl-sky-L'>
                        &nbsp;消費税
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmSpecialInput lblZei tag-L' readonly="readonly" tabindex="-1" />
                </td>
            </tr>
        </table>

    </div>
    <div style="margin-top:20px;overflow-x: auto;">
        <table id='FrmSpecialInput_sprMeisai'>
        </table>
    </div>

    <div style="width:100%;margin-top:15px;visibility: hidden;display: none;">
        <table>
            <tr>
                <td width="170px" height="25px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmSpecialInput lblSyadaiKata">
                    </label>
                </td>
                <td width="170px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmSpecialInput lblCar_NO">
                    </label>
                </td>
                <td width="170px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmSpecialInput lblHanbaiSyasyu">
                    </label>
                </td>
                <td width="170px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmSpecialInput lblSyasyu_NM">
                    </label>
                </td>
            </tr>
        </table>

    </div>
    <!-- <div align="right">
    <table>
    <tr>
    <td align="right"> -->
    <div class="HMS-button-pane">
        <div class='HMS-button-set'>
            <button class="FrmSpecialInput cmdAction Tab Enter">
                更新
            </button>
            <button class="FrmSpecialInput cmdOption Tab Enter">
                付属品
            </button>
            <button class="FrmSpecialInput cmdBack Tab Enter">
                閉じる
            </button>
        </div>
    </div>

    <!-- </td>
    </tr>
    </table>
    </div> -->
</div>