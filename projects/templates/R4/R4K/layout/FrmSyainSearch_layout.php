<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyainSearch/FrmSyainSearch"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmSyainSearch">
    <div class="FrmSyainSearch  R4-content">
        <div style="margin-left: 15px">
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmSyainSearch Label1 lbl-sky-MF" for="">
                            部署コード
                        </label>

                    </td>
                    <td>
                        <input class="FrmSyainSearch txtBusyoCD Enter Tab" style="width: 50px;" maxlength="3">
                    </td>

                </tr>
                <tr>
                    <td>
                        <label class="FrmSyainSearch Label2 lbl-sky-MF" for="">
                            社員番号
                        </label>
                    </td>
                    <td>
                        <input class="FrmSyainSearch txtSyainCD Enter Tab" style="width: 50px;" maxlength="5">

                    </td>

                </tr>
                <tr>
                    <td>
                        <label class="FrmSyainSearch Label3 lbl-sky-MF" for="">
                            社員カナ
                        </label>

                    </td>
                    <td>
                        <input class="FrmSyainSearch txtSyainKN Enter Tab" style="width: 300px;">
                        <label for="">
                            前方一致
                        </label>
                    </td>
                    <!-- 前方一致 -->
                    <td align="right" width="200PX">
                        <button class="FrmSyainSearch cmdSearch Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            検索条件を指定しない場合は全件検索です
        </div>
        <div>
            <table id="FrmSyainSearch_sprMeisai">
            </table>
        </div>
        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            指定したい行をダブルクリックしてください。
        </div>
        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
            又は選択状態で選択ボタンをクリックしてください。
        </div>
        <div class="HMS-button-pane" 　align="right">
            <div class="HMS-button-set">
                <button class="FrmSyainSearch cmdChoice Enter Tab">
                    選択
                </button>
                <button class="FrmSyainSearch cmdCancel Enter Tab">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
