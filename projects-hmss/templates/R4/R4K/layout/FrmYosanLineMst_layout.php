<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmYosanLineMst/FrmYosanLineMst"));
?>

<div class="FrmYosanLineMst R4-content">
    <div class="FrmYosanLineMst searchArea">
        <fieldset class="FrmYosanLineMst center GroupBox1" style="height: 50px;width: 700px">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0" width="80%">
                <tr>
                    <td>
                        <label class="FrmYosanLineMst lbl-blue" style="padding-right: 20px" for="">
                            部署区分
                        </label>
                    </td>
                    <td>
                        <input class="FrmYosanLineMst txtBusyoKB Enter Tab" type="text" maxlength="5"
                            style="width: 75px" />
                    </td>
                    <td>
                        <label class="FrmYosanLineMst lblMessage" for="">
                            新車：S　中古車：C　整備：F
                        </label>
                    </td>
                    <!-- 20171226 YIN UPD S -->
                    <!-- <td width="40%"> -->
                    <td width="30%">
                        <!-- 20171226 YIN UPD E -->
                        <button class="FrmYosanLineMst cmdKensaku Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="FrmYosanLineMst listArea" style="margin-top: 10px">
        <table>
            <tr>
                <td>
                    <table id="FrmYosanLineMst_sprList">
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="HMS-button-pane">
                        <div class="HMS-button-set">
                            <button class="FrmYosanLineMst cmdInsert Tab Enter">
                                新規追加
                            </button>
                            <button class="FrmYosanLineMst cmdAction Tab Enter">
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
