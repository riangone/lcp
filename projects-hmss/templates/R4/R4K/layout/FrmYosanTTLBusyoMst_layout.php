<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmYosanTTLBusyoMst/FrmYosanTTLBusyoMst'));
?>

<div class='FrmYosanTTLBusyoMst R4-content'>
    <div class='FrmYosanTTLBusyoMst listArea'>
        <table border="0">
            <tr>
                <td>
                    <div>
                        <label for="">
                            集計先部署
                        </label>
                        <table id='FrmYosanTTLBusyoMst_sprLine'>
                        </table>
                    </div>
                </td>
                <td width="5%">
                </td>
                <td>
                    <div>
                        <label for="">
                            集計元部署
                        </label>
                        <table id="FrmYosanTTLBusyoMst_sprMeisai">
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                </td>
                <td align="right">
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmYosanTTLBusyoMst cmdCancel Tab Enter'>
                                キャンセル
                            </button>
                            <button class='FrmYosanTTLBusyoMst cmdAction Tab Enter'>
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
