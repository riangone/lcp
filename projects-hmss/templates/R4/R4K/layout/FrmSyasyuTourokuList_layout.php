<!-- /**
* 説明：
*
*
* @author lijun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150911                  #2114                   BUG                         LI
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyasyuTourokuList/FrmSyasyuTourokuList"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<div class='FrmSyasyuTourokuList'>
    <div class='FrmSyasyuTourokuList content R4-content' style="width: 1113px">
        <div style="float: left;margin-left: 20px;margin-bottom: 20px;margin-top: 20px">
            <fieldset style="width: 120px">
                <legend>
                    帳票選択
                </legend>
                <table>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmSyasyuTourokuList radTougetu Tab Enter" type="radio"
                                name="FrmSyasyuTourokuList_radio">
                            当月のみ

                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmSyasyuTourokuList radTouki Tab Enter" type="radio"
                                name="FrmSyasyuTourokuList_radio">
                            当期のみ

                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmSyasyuTourokuList radDouble Tab Enter" type="radio"
                                name="FrmSyasyuTourokuList_radio">
                            両方

                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="float: left;margin-left: 20px;margin-top: 20px">
            <fieldset>
                <legend>
                    出力条件
                </legend>
                <table>
                    <tr>
                        <td class="FrmSyasyuTourokuList Label1">
                            処理年月
                        </td>
                        <td width="30">
                        </td>

                        <td>
                            <div class="FrmSyasyuTourokuList cboYMFromdiv" style="float: left">
                                <!-- 20150922 yin upd S -->
                                <!-- <input  class="FrmSyasyuTourokuList cboYMFrom Tab Enter" style="width: 80px" maxlength="7"/> -->
                                <input class="FrmSyasyuTourokuList cboYMFrom Tab Enter" style="width: 80px"
                                    maxlength="6" />
                                <!-- 20150922 yin upd E -->
                            </div>
                        </td>

                        <td width="15">
                        </td>
                        <td>
                            <label class="FrmSyasyuTourokuList Label3" for="">
                                ～
                            </label>
                        </td>
                        <td width="15">
                        </td>
                        <td>
                            <!-- 20150922 yin upd S -->
                            <!-- <input  class="FrmSyasyuTourokuList cboYMTo Tab Enter" style="width: 80px" maxlength="7"/> -->
                            <input class="FrmSyasyuTourokuList cboYMTo Tab Enter" style="width: 80px" maxlength="6" />
                            <!-- 20150922 yin upd E -->
                        </td>
                        <td width="300">
                        </td>
                        <td>
                            <button class="FrmSyasyuTourokuList cmdAction Tab Enter">
                                実行
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>

        </div>

    </div>
</div>
