<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmKaikeiMake/FrmKaikeiMake"));
?>
<div class='FrmKaikeiMake'>
    <div class='FrmKaikeiMake  R4-content'>
        <fieldset class='FrmKaikeiMake frame1' style='margin-bottom:10px;'>
            <legend>
                抽出条件
            </legend>
            <label style='margin-right:20px;' for="">
                計上日
            </label>
            <input type='text' class='FrmKaikeiMake cboDateFrom Enter Tab' tabindex="1" />
            ~
            <input type='text' class='FrmKaikeiMake cboDateTo Enter Tab' tabindex="2" />
        </fieldset>
        <fieldset class='FrmKaikeiMake frame2' style='margin-bottom:10px;width:450px;'>
            <label style='margin-right:100px;' for="">
                出力件数
            </label>
            <label class='FrmKaikeiMake lblCnt' for="">

            </label>
            <label for="">
                件数
            </label>
        </fieldset>
        <fieldset class='FrmKaikeiMake frame3' style='width:300px;height:260px;'>
            <table>
                <tr>
                    <td width=40% valign=top>
                        <table>
                            <tr>
                                <td>
                                    <label style='font-size:12px;color:red;margin-top:10px;' for="">
                                        科目マスタ未登録コードがあります
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label style='font-size:12px;color:red' for="">
                                        科目マスタを確認して下さい
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table id='FrmKaikeiMake_sprErrList'>

                        </table>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmKaikeiMake cmdAction Enter Tab' tabindex="3">
                    実行
                </button>
            </div>
        </div>
        <label class='FrmKaikeiMake lblMsg' style='float:left;width:100%;height:20px;color:blue' for="">

        </label>
        <label class='FrmKaikeiMake lblMsg2 ' style='float:left;width:300px;height:20px;' for="">

        </label>
    </div>
</div>
