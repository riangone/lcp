<!--
/**
* 説明：
*
*
* @author FCS
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20150930           #2028                     BUG                            LI
* 20201118           bug         右jqgridに追加されるシートはブランク部分があります         WANGYING
* --------------------------------------------------------------------------------------------
*/
-->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmPattern/FrmPattern'));
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmPattern.tabsList,
        .FrmPattern.tabsList ul {
            height: 21px !important;
        }

        .FrmPattern.GroupBox2 {
            height: auto !important;
        }
    }
</style>
<div class="FrmPattern R4-content">
    <div class="FrmPattern listArea">
        <table border="0">
            <tr>
                <td>
                    <fieldset class="FrmPattern GroupBox1" style="height: 20px;width: 410px;border:solid 1px #A6C9E2">
                        <label class='FrmPattern lbl-blue' style="min-width: 100px" for="">
                            所属
                        </label>
                        <select class="FrmPattern UcComboBox1 Enter Tab" style="width: 270px;margin-left: 10px">
                            <option></option>
                        </select>
                    </fieldset>
                </td>
                <td rowspan="3">
                    <!-- 20150930 LI UPD S. -->
                    <!-- <fieldset class="FrmPattern GroupBox2"	style="height: 480px;width: 460px;border:solid 1px #A6C9E2"> -->
                    <fieldset class="FrmPattern GroupBox2" style="height: 460px;width: 460px;border:solid 1px #A6C9E2">
                        <!-- 20150930 LI UPD S. -->
                        <div>
                            <table id='FrmPattern_sprProgramList'>
                            </table>
                        </div>
                        <div class="FrmPattern tabScroll" style="width: 460px;overflow: auto">
                            <!-- 20201118 wangying upd S -->
                            <!-- <div class="FrmPattern tabsList" style="padding: 2px;width: 10000px"> -->
                            <div class="FrmPattern tabsList" style="padding: 2px;width: 10000px;height: 27px">
                                <!-- 20201118 wangying upd E -->
                                <ul style="padding: 0px;height: 27px" class="FrmPattern tabsUI">
                                </ul>
                            </div>
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <table id='FrmPattern_sprPatarn'>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <fieldset class="FrmPattern GroupBox3" style="height: 35px;width: 410px;border:solid 1px #A6C9E2">
                        <div class="HMS-button-pane">
                            <div class='HMS-button-set' style="float: left;">
                                <button class='FrmPattern cmdDelete Tab'>
                                    削除
                                </button>
                                <button class='FrmPattern cmdCopy Tab'>
                                    コピー
                                </button>
                                <button class='FrmPattern cmdInsert Tab'>
                                    追加
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmPattern cmdInput Enter Tab'>
                                登録
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
