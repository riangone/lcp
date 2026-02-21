<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmKmkLineMst/FrmKmkLineMst'));
?>

<div class='FrmKmkLineMst R4-content'>
    <div class='FrmKmkLineMst listArea' style='width:99%;margin-left: 0px;'>
        <table cellspacing="0" border="0" style="margin-left: 10px">
            <tr>
                <td width="14%" height="99%">
                    <div>
                        <table id='FrmKmkLineMst_sprLine'>
                        </table>
                        <div id='FrmKmkLineMst_pager1'>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <table id='FrmKmkLineMst_sprMeisai'>
                        </table>
                        <div id='FrmKmkLineMst_pager2'>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="right">
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmKmkLineMst cmdCancel Tab Enter'>
                                キャンセル
                            </button>
                            <button class='FrmKmkLineMst cmdAction Tab Enter'>
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>