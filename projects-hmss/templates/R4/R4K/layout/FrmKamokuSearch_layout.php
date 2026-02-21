<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmKamokuSearch/FrmKamokuSearch"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmKamokuSearch">
    <div class="FrmKamokuSearch R4-content">
        <div style="margin-left: 15px">
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmKamokuSearch Label1 lbl-sky-MF" for="">
                            科目コード
                        </label>

                    </td>
                    <td>
                        <input class="FrmKamokuSearch txtKamokCD Enter Tab" style="width: 55px;" maxlength="5">
                    </td>

                </tr>
                <tr>
                    <td>
                        <label class="FrmKamokuSearch Label2 lbl-sky-MF" for="">
                            科目名
                        </label>
                    </td>
                    <td>
                        <input class="FrmKamokuSearch txtKamokuNM Enter Tab" style="width: 180px;">
                        <button class="FrmKamokuSearch cmdSearch Enter Tab">
                            検索
                        </button>
                    </td>

                </tr>
            </table>
        </div>

        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            検索条件を指定しない場合は全件検索です。
        </div>
        <div>
            <table id="FrmKamokuSearch_sprMeisai">
            </table>
        </div>
        <!-- 20180201 YIN UPD S -->
        <!-- <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px"> -->
        <div style="margin-top: 1px;margin-left: 15px;margin-bottom: 1px">
            <!-- 20180201 YIN UPD E -->
            指定したい行をダブルクリックして下さい
        </div>
        <!-- 20180201 YIN UPD S -->
        <!-- <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px"> -->
        <div style="margin-top: 1px;margin-left: 15px;margin-bottom: 1px">
            <!-- 20180201 YIN UPD E -->
            又は選択状態で選択をクリックして下さい
        </div>
        <div class="HMS-button-pane" 　align="right">
            <div class="HMS-button-set">
                <button class="FrmKamokuSearch cmdChoice Enter Tab">
                    選択
                </button>
                <button class="FrmKamokuSearch cmdCancel Enter Tab">
                    ｷｬﾝｾﾙ
                </button>
            </div>
        </div>
    </div>
</div>
