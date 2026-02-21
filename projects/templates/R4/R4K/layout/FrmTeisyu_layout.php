<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmTeisyu/FrmTeisyu'));
?>
<!-- 20210708 YIN INS S -->
<style type="text/css">
    .FrmTeisyu.cmdChange {
        float: left;
        left: -1%;
    }
</style>
<!-- 20210708 YIN INS E -->
<div id="FrmTeisyu" class="FrmTeisyu R4-content">
    <div class="FrmTeisyu searchArea">
        <fieldset class="FrmTeisyu center GroupBox1" style="height: 50px;width: 800px">
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table>
                <tr>
                    <td>
                        <!-- 2018/02/05 ciyuanchen UPD S. -->
                        <!--	<label class="FrmTeisyu lbl-blue">
                        部署　　　　　
                    </label>-->
                        <label class="FrmTeisyu lbl-blue" style="width: 80px;" for="">
                            部署　　
                        </label>
                        <!-- 2018/02/05 ciyuanchen UPD E. -->
                    </td>
                    <td>
                        <input class="FrmTeisyu txtBusyoCD Enter Tab" type='text' maxlength="3" style="width: 75px" />
                    </td>
                    <td>
                        <button class="FrmTeisyu cmdSearchBs" style="min-width:10px">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class="FrmTeisyu lblBusyoNM" type='text' readonly="readonly" style="width: 200px" />
                    </td>
                    <td style="width:10%">
                    </td>
                    <td>
                        <button class="FrmTeisyu cmdSearch Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class='FrmTeisyu listArea' style="margin-top: 10px">
        <table>
            <tr>
                <td>
                    <table id='FrmTeisyu_sprList' class='FrmTeisyu'>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="HMS-button-pane">
                        <!-- 20210708 YIN INS S -->
                        <button class='FrmTeisyu cmdChange Tab Enter'>
                            条件変更
                        </button>
                        <!-- 20210708 YIN INS E -->
                        <div class='HMS-button-set'>
                            <button class='FrmTeisyu cmdAction Tab Enter'>
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
