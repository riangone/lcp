<!-- /**
* 説明：
*
*
* @author yinhuaiyu
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150923                  #2162                   BUG                         YIN
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyanaiLeasePrint/FrmSyanaiLeasePrint"));
?>
<div class='FrmSyanaiLeasePrint'>
    <div class='FrmSyanaiLeasePrint  R4-content'>
        <div style='margin-left:20px;margin-bottom:10px;'>
            処理年月
            <!-- 20150923 yin upd S -->
            <!-- <input class='FrmSyanaiLeasePrint cboYM Enter Tab' style="width: 80px" maxlength="7" tabindex="1"> -->
            <input class='FrmSyanaiLeasePrint cboYM Enter Tab' style="width: 80px" maxlength="6" tabindex="1">
            <!-- 20150923 yin upd E -->
        </div>
        <fieldset>
            <legend>
                印刷条件
            </legend>
            <div style='margin-left:20px;'>
                <table border=0 cellspacing="2" width=700px>
                    <tr>
                        <td width=40px>
                            部署
                        </td>
                        <td width=70px>
                            <input class='FrmSyanaiLeasePrint busyoCDFrom Enter Tab' maxlength="3" tabindex="2"
                                type='text' style='width:50px;' />

                        </td>
                        <td width=300px>
                            <input class='FrmSyanaiLeasePrint busyoNMFrom ' type='text' style='width:400px;'
                                disabled="disabled" />
                        </td>
                    </tr>
                    <tr>
                        <td width=40px>

                        </td>
                        <td width=70px>
                            <input class='FrmSyanaiLeasePrint busyoCDTo Enter Tab' maxlength="3" type='text'
                                style='width:50px;' tabindex="3" />
                        </td>
                        <td width=300px>
                            <input class='FrmSyanaiLeasePrint busyoNMTo' type='text' style='width:400px;'
                                disabled="disabled" />
                        </td>
                    </tr>
                </table>
            </div>
            <div style='margin-left:20px;margin-top:40px;'>
                <fieldset>
                    <legend>
                        オプション選択
                    </legend>
                    <table cellspacing="4">
                        <tr>
                            <td>
                                <input class='FrmSyanaiLeasePrint radioAll Enter Tab' type='radio'
                                    name='FrmSyanaiLeasePrint_radio' tabindex="4" checked="checked" />
                                全て
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class='FrmSyanaiLeasePrint radioOption1 Enter Tab' type='radio'
                                    name='FrmSyanaiLeasePrint_radio' tabindex="5" />
                                4：サービスカー　　　7：器具・備品
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class='FrmSyanaiLeasePrint radioOption2 Enter Tab' type='radio'
                                    name='FrmSyanaiLeasePrint_radio' tabindex="6" />
                                5：機械　　　　　　　6：工具
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSyanaiLeasePrint button_CSV_print Enter Tab' tabindex="7">
                    CSV出力
                </button>
                <button class='FrmSyanaiLeasePrint button_cmdAction Enter Tab' tabindex="8">
                    実 行
                </button>
            </div>
        </div>
    </div>
</div>