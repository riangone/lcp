<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmOptionInput/FrmOptionInput'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="FrmOptionInput R4-content">
    <!--header start-->
    <div style='width:100%;padding-top:5px;padding-bottom: 5px'>
        <table>
            <tr>
                <td>
                    <label class="lbl-grey-L">
                        &nbsp;注文書番号&nbsp;
                    </label>
                    <input type="text" class="FrmOptionInput lblCmnNO" readonly="readonly">
                </td>
                <td width="15px">
                </td>
                <td width="">
                    <button class="FrmOptionInput cmdInsert Tab Enter">
                        追加
                    </button>
                </td>
                <td width="">
                    <button class="FrmOptionInput cmdUpdate Tab Enter">
                        修正
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <!--header end-->
    <div style="width:99%;margin-top:5px;">
        <table class='FrmOptionInput dataInfoArea dataInfoTable' width=99% border=0 cellspacing="0">
            <tr>
                <td height="12px">
                    <label class='FrmOptionInput dataInfoText1 lbl-sky-L'>
                        &nbsp;契約者
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblKeiyakusya tag-L' readonly="readonly" />
                </td>
                <td>
                    <label class='FrmOptionInput dataInfoText2 lbl-sky-M'>
                        &nbsp;部署
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblBusyoCD tag-M' readonly="readonly" />
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblBusyoNM tag-L' readonly="readonly" />
                </td>
                <td>
                    <label class='FrmOptionInput dataInfoText3 lbl-sky-L'>
                        &nbsp;架装番号
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblKasouNO tag-L' readonly="readonly" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='FrmOptionInput dataInfoText4 lbl-sky-L'>
                        &nbsp;使用者
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblSiyosya tag-L' readonly="readonly" />
                </td>
                <td>
                    <label class='FrmOptionInput dataInfoText5 lbl-sky-M'>
                        &nbsp;社員
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblSyainNO tag-M' readonly="readonly" />
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblSyainNM tag-L' readonly="readonly" />
                </td>
                <td>
                    <label class='FrmOptionInput dataInfoText6 lbl-sky-L'>
                        &nbsp;問合呼称
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblKosyou tag-L' readonly="readonly" />
                </td>
            </tr>
            <tr>
                <td>
                    <label class='FrmOptionInput dataInfoText7 lbl-sky-L'>
                        &nbsp;使用者カナ
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblSiyosyaKN tag-L' readonly="readonly" />
                </td>
                <td>
                    <label class='FrmOptionInput dataInfoText8 lbl-sky-M'>
                        &nbsp;販売店
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblHanbaiCD tag-M' readonly="readonly" />
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblHanbaiNM tag-L' readonly="readonly" />
                </td>
                <td>
                    <label class='FrmOptionInput dataInfoText9 lbl-sky-L'>
                        &nbsp;消費税
                    </label>
                </td>
                <td>
                    <input type='text' class='FrmOptionInput lblZei tag-L' readonly="readonly" />
                </td>
            </tr>
        </table>

    </div>
    <div style="margin-top:20px;overflow-x: auto;">
        <table class="FrmOptionInput sprMeisai" id="FrmOptionInput_sprMeisai">
        </table>
    </div>

    <div style="width:100%;margin-top:10px;visibility: hidden;display: none;">
        <table>
            <tr>
                <td width="170px" height="25px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmOptionInput lblSyadaiKata">
                    </label>
                </td>
                <td width="170px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmOptionInput lblCar_NO">
                    </label>
                </td>
                <td width="170px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmOptionInput lblHanbaiSyasyu">
                    </label>
                </td>
                <td width="170px" style="background-color: #EDEDED;border: 1px solid">
                    <label class="FrmOptionInput lblSyasyu_NM">
                    </label>
                </td>
            </tr>
        </table>

    </div>
    <!-- <div align="right">
    <table>
    <tr>
    <td  align="right"> -->
    <div class="HMS-button-pane">
        <div class='HMS-button-set'>
            <button class="FrmOptionInput cmdAction Tab Enter">
                更新
            </button>
            <button class="FrmOptionInput cmdSpecial Tab Enter">
                特別仕様
            </button>
            <button class="FrmOptionInput cmdBack Tab Enter">
                閉じる
            </button>
        </div>
    </div>
    <!-- </td>
    </tr>
    </table>
    </div> -->
</div>