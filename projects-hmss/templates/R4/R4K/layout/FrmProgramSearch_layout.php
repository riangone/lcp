<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmProgramSearch/FrmProgramSearch"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmProgramSearch">
    <div class="FrmProgramSearch R4-content">
        <div style="margin-left: 10px;margin-top: 15px">
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmProgramSearch Label1 lbl-sky-MF" style="width: 100px" for="">
                            プログラム名称
                        </label>
                    </td>
                    <td>
                        <input class="FrmProgramSearch txtProgramNM Enter Tab" style="width: 200px;" maxlength="32767">
                        <button class="FrmProgramSearch cmdSearch Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            前方一致検索です。
        </div>
        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            検索条件を指定しない場合は全件検索です。
        </div>
        <div style="margin-left: 10px">
            <table id="FrmProgramSearch_sprItyp">
            </table>
        </div>
        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            指定したい行をダブルクリックして下さい
        </div>
        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            又は選択状態で選択をクリックして下さい
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="FrmProgramSearch cmdChoice Enter Tab">
                    選択
                </button>
                <button class="FrmProgramSearch cmdCancel Enter Tab">
                    ｷｬﾝｾﾙ
                </button>
            </div>
        </div>
    </div>
</div>
