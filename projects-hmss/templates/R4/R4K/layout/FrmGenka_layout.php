<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmGenka/FrmGenka"));
?>
<style type="text/css">
    .FrmHRAKUOutput .ui-jqgrid .ui-state-focus {
        z-index: 2 !important;
    }
</style>
<div class='FrmGenka'>
    <div class='FrmGenka  R4-content'>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table width=500 border=0>
                <tr>
                    <td>
                        <label style='border:solid 1px;background-color:#B0E2FF' for="">
                            問合呼称
                        </label>
                        <input type='text' class='FrmGenka txtTOA_NAME Enter Tab' tabindex="10"
                            style='width:150px;margin-left:5px;' maxlength="6" />
                        <button style='' class='FrmGenka cmdSearch Enter Tab' tabindex="3">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <table id='FrmGenka_sprMeisai'>

        </table>
        <div id='FrmGenka_pager'>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmGenka cmdCopy Enter Tab' tabindex="1">
                    コピー
                </button>
                <button class='FrmGenka cmdInsert Enter Tab' tabindex="1">
                    新規追加
                </button>
                <button class='FrmGenka cmdUpdate Enter Tab' tabindex="2">
                    更新
                </button>
                <button class='FrmGenka cmdCancel Enter Tab' tabindex="3">
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>
