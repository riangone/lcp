<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmKaikei/FrmKaikei"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmKaikei" class="FrmKaikei R4-content">
    <div>
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <input type="text" class="FrmKaikei lblToday Enter Tab" style="width: 90px;float: right"
                disabled="disabled">
            <table border="0">
                <tr>
                    <td>
                        <label for="">
                            経理日
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input type="text" class="FrmKaikei cboKeiriBi Enter Tab" style="width: 110px;" maxlength="10">
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="">
                            伝票№
                        </label>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input type="text" class="FrmKaikei txtDenpyoNOFrom Enter Tab" style="width: 130px"
                            maxlength="12" />
                    </td>
                    <td>
                        ～
                    </td>
                    <td>
                        <input type="text" class="FrmKaikei txtDenpyoNOTo Enter Tab" style="width: 130px"
                            maxlength="12" />
                    </td>
                    <td>
                    </td>
                    <td align="right">
                        <button class="FrmKaikei cmdSearch Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div style="margin-top: 20px;">
        <table class="FrmKaikei  sprMeisai" id="FrmKaikei_sprMeisai">
        </table>
        <!-- <div id="FrmKaikei_pager">
        </div> -->
    </div>
    <div class="HMS-button-pane" align="right" style="margin-top: 10px;">
        <button class="FrmKaikei cmdInsert Enter Tab">
            <label for="">
                新規登録
            </label>
        </button>
        <button class="FrmKaikei cmdUpdate Enter Tab">
            <label for="">
                修正
            </label>
        </button>
        <button class="FrmKaikei cmdDelete Enter Tab">
            <label for="">
                削除
            </label>
        </button>
    </div>

</div>
