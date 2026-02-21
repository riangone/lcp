<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmTotalBusyo/FrmTotalBusyo'));
?>

<div class='FrmTotalBusyo R4-content'>
    <div class='FrmTotalBusyo listArea'>
        <table cellspacing="0" border="0" style="margin-left: 20px">
            <tr>
                <td height="99%" rowspan="2">
                    <div>
                        <label style="margin-bottom: 5px" for="">
                            集計先部署
                        </label>
                        <table id='FrmTotalBusyo_sprLine'>
                        </table>
                    </div>
                </td>
                <td width="10px">
                </td>
                <td>
                    <div>
                        <label style="margin-bottom: 5px" for="">
                            集計元部署
                        </label>
                        <table id='FrmTotalBusyo_sprMeisai'>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <div>
                        <label style="margin-bottom: 5px" for="">
                            中古車部門加算集計元部署
                        </label>
                        <table id='FrmTotalBusyo_sprMeisaiPlus'>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmTotalBusyo cmdCancel Tab Enter'>
                                キャンセル
                            </button>
                            <button class='FrmTotalBusyo cmdAction Tab Enter'>
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
