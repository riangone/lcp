<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmYosanTorikomiMst/FrmYosanTorikomiMst"));
?>

<div class="FrmYosanTorikomiMst R4-content">
    <div class="FrmYosanTorikomiMst searchArea">
        <fieldset class="FrmYosanTorikomiMst center GroupBox1" style="height: 50px;width: 700px">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0" width="80%">
                <tr>
                    <td>
                        <!-- 20150819   Yuanjh  modify S.-->
                        <!--<label class="FrmYosanTorikomiMst lbl-blue" style="padding-right: 20px;">-->
                        <label class="FrmYosanTorikomiMst lbl-blue" style="padding-right: 20px;width: 60px" for="">
                            <!-- 20150819   Yuanjh  modify E.-->
                            部署区分
                        </label>
                    </td>
                    <td>
                        <input class="FrmYosanTorikomiMst txtBusyoKB Enter Tab" type="text" maxlength="5"
                            style="width: 75px" />
                    </td>
                    <td>
                        <!-- 20150819   Yuanjh  modify S.-->
                        <!--<label class="FrmYosanTorikomiMst lblMessage" >-->
                        <label class="FrmYosanTorikomiMst lblMessage" style="width: 200px;" for="">
                            <!-- 20150819   Yuanjh  modify E.-->
                            新車：S　中古車：C　整備：F
                        </label>
                    </td>
                    <td width="40%">
                        <button class="FrmYosanTorikomiMst cmdKensaku Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="FrmYosanTorikomiMst listArea" style="margin-top: 10px">
        <table>
            <tr>
                <td>
                    <table id="FrmYosanTorikomiMst_sprList">
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="HMS-button-pane">
                        <div class="HMS-button-set">
                            <button class="FrmYosanTorikomiMst cmdInsert Tab Enter">
                                新規追加
                            </button>
                            <button class="FrmYosanTorikomiMst cmdAction Tab Enter">
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
