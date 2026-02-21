<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmReOutReport/FrmReOutReport"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmReOutReport'>
    <div class='FrmReOutReport content R4-content' style="width: 1113px">
        <div>
            <fieldset style="width: 60%">
                <legend>
                    検索条件
                </legend>
                <table>
                    <tr>
                        <td>
                            <label class='FrmReOutReport Label8' for="">
                                完了日
                            </label>
                        </td>
                        <td width="30">
                        </td>
                        <td>
                            <input class='FrmReOutReport cboDateFrom Enter Tab' style="width: 100px" maxlength="10">
                        </td>
                        <td>
                            <label class="FrmReOutReport Label4" for="">
                                ～
                            </label>
                        </td>
                        <td>
                            <input class='FrmReOutReport cboDateTo Enter Tab' style="width: 100px" maxlength="10" />
                        </td>
                        <td width="40">
                        </td>
                        <td>
                            <button class='FrmReOutReport cmdSearch Enter Tab'>
                                検索
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="height: 10px">
        </div>
        <div class="FrmReOutReport botType" id="botType" style="display: none;">
        </div>
        <div>
            <table id="FrmReOutReport_sprList">
            </table>
        </div>
        <div style="height: 10px">
        </div>
        <div>
            <table>
                <tr>
                    <td width="380">
                    </td>
                    <td>
                        <button class="FrmReOutReport cmdInsert Enter Tab" style="min-width: 100px;height: 25px">
                            新規登録
                        </button>
                    </td>
                    <td>
                        <button class="FrmReOutReport cmdUpdate Enter Tab" style="min-width: 100px;height: 25px">
                            修正
                        </button>
                    </td>
                    <td>
                        <button class="FrmReOutReport cmdDelete Enter Tab" style="min-width: 100px;height: 25px">
                            削除
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div id="FrmReOutReportEdit">
        </div>
    </div>
</div>
