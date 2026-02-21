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
echo $this->Html->script(array("R4/R4K/FrmSyasyuArariChousei/FrmSyasyuArariChousei"));
?>
<div class='FrmSyasyuArariChousei'>
    <div class='FrmSyasyuArariChousei  R4-content'>
        <div style='width:600px;'>
            <table border=0 width=100% style='margin-left:20px;'>
                <tr>
                    <td width=70>
                        <div
                            style='height:19px;padding-left:5px;padding-top:1px;color:white;border:solid #000000 1px;background-color:#5CACEE'>
                            計上年月
                        </div>

                    </td>
                    <td>
                        <!-- 20150923 yin upd S -->
                        <!-- <input class='FrmSyasyuArariChousei cboYM Enter Tab' type="text" style="width: 80px" tabindex='1'/> -->
                        <input class='FrmSyasyuArariChousei cboYM Enter Tab' type="text" style="width: 80px"
                            tabindex='1' maxlength="6" />
                        <!-- 20150923 yin upd E -->
                    </td>
                </tr>
                <tr>
                    <td width=70>
                        <div
                            style='height:19px;padding-left:5px;padding-top:1px;color:white;border:solid #000000 1px;background-color:#5CACEE'>
                            車&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;種
                        </div>
                    </td>
                    <td>
                        <select style="width: 350px" class='FrmSyasyuArariChousei cboSyasyu Enter Tab' tabindex=2>
                            <option></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width=70>
                        <div
                            style='height:19px;padding-left:5px;padding-top:1px;color:white;border:solid #000000 1px;background-color:#5CACEE'>
                            売&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;上
                        </div>
                    </td>
                    <td>
                        <!--
                       //20150915 Yuanjh UPD S.
                           <input type='text'  class='FrmSyasyuArariChousei txtUriage Enter Tab' style="width: 90px;" tabindex=3/>
                       //20150915 Yuanjh UPD E.
                       -->
                        <input type='text' class='FrmSyasyuArariChousei txtUriage Enter Tab'
                            style="width: 90px;text-align:right" tabindex=3 />
                    </td>
                </tr>
                <tr>
                    <td width=70>
                        <div
                            style='height:19px;padding-left:5px;padding-top:1px;color:white;border:solid #000000 1px;background-color:#5CACEE'>
                            粗&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;利
                        </div>
                    </td>
                    <td>
                        <input type='text' class='FrmSyasyuArariChousei txtArari Enter Tab'
                            style="width: 90px;text-align:right" tabindex=4 />
                    </td>
                </tr>
            </table>
            <input type="text" class='FrmSyasyuArariChousei txtItemNO' style='visibility:hidden' />
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSyasyuArariChousei button_update Enter Tab' tabindex="5">
                    更新(F9)
                </button>
                <button class='FrmSyasyuArariChousei button_delete Enter Tab' tabindex="6">
                    削除
                </button>
            </div>
        </div>
    </div>
</div>