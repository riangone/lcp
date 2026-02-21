<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyasyuUriageList/FrmSyasyuUriageList"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<div class='FrmSyasyuUriageList'>
    <div class='FrmSyasyuUriageList content R4-content' style="width: 1113px">
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
                            <input class="FrmSyasyuUriageList radTougetu Tab Enter" type="radio"
                                name="FrmSyasyuUriageList_radio">
                            当月のみ

                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmSyasyuUriageList radTouki Tab Enter" type="radio"
                                name="FrmSyasyuUriageList_radio">
                            当期のみ

                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmSyasyuUriageList radDouble Tab Enter" type="radio"
                                name="FrmSyasyuUriageList_radio">
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
                        <td class="FrmSyasyuUriageList Label1">
                            処理年月
                        </td>
                        <td width="30">
                        </td>
                        <td>
                            <div class="FrmSyasyuUriageList cboYMFromdiv" style="float: left">
                                <!-- 20150922 yin upd S	 -->
                                <!-- <input  class="FrmSyasyuUriageList cboYMFrom Tab Enter" style="width: 80px" maxlength="7"/> -->
                                <input class="FrmSyasyuUriageList cboYMFrom Tab Enter" style="width: 80px"
                                    maxlength="6" />
                                <!-- 20150922 yin upd S	 -->
                            </div>
                        </td>
                        <td width="15">
                        </td>
                        <td>
                            <label class="FrmSyasyuUriageList Label3" for="">
                                ～
                            </label>
                        </td>
                        <td width="15">
                        </td>
                        <td>
                            <!-- 20150922 yin upd S -->
                            <!-- <input  class="FrmSyasyuUriageList cboYMTo Tab Enter" style="width: 80px" maxlength="7"/> -->
                            <input class="FrmSyasyuUriageList cboYMTo Tab Enter" style="width: 80px" maxlength="6" />
                            <!-- 20150922 yin upd S -->
                        </td>
                        <td width="300">
                        </td>
                        <td>
                            <button class="FrmSyasyuUriageList cmdAction Tab Enter">
                                実行
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>

        </div>

    </div>
</div>
