<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmDLStateCheck/FrmDLStateCheck'));
?>

<div class="FrmDLStateCheck R4-content">
    <div class="FrmDLStateCheck listArea">
        <table border="0">
            <tr>
                <td colspan="2">
                    <table id="FrmDLStateCheck_sprList">
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="HMS-button-pane">
                        <div class="HMS-button-set" style="float: left;">
                            <button class="FrmDLStateCheck cmdDisp Tab Enter" style="height: 40px">
                                再表示
                                <BR />
                                (F5)
                            </button>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="HMS-button-pane">
                        <div class="HMS-button-set">
                            <button class="FrmDLStateCheck cmdUpdate Tab Enter" style="height: 40px">
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>