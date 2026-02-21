<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyainSearch/FrmSyainSearch"));
?>
<style type="text/css">
    input.FrmSyainSearch.txtSyainKN {
        width: 150px;
    }

    .FrmSyainSearch .HMS-button-pane,
    .FrmSyainSearch.lbl-content {
        margin-bottom: 0px;
        margin-top: 0px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmSyainSearch">
    <div class="FrmSyainSearch JKSYS-content">
        <div>
            <label class="FrmSyainSearch lbl-sky-L" for=""> 部署コード </label>

            <input class="FrmSyainSearch txtBusyoCD Enter Tab" type='text' maxlength="3" tabindex="0" />
        </div>
        <div>
            <label class="FrmSyainSearch lbl-sky-L" for=""> 社員番号 </label>

            <input class="FrmSyainSearch txtSyainCD Enter Tab" type='text' maxlength="5" tabindex="1" />
        </div>
        <div class="HMS-button-pane">
            <label class="FrmSyainSearch lbl-sky-L" for=""> 社員カナ </label>

            <input class="FrmSyainSearch txtSyainKN Enter Tab" tabindex="2" />
            <label for=""> 前方一致 </label>
            <div class="HMS-button-set">
                <button class="FrmSyainSearch cmdSearch Enter Tab" tabindex="3">
                    検索
                </button>
            </div>
        </div>
        <div class="FrmSyainSearch lbl-content">
            検索条件を指定しない場合は全件検索です
        </div>
        <div class="FrmSyainSearch lbl-content">
            <table id="JKSYS_FrmSyainSearch_sprItyp"></table>
        </div>
        <div class="FrmSyainSearch lbl-content">
            指定したい行をダブルクリックしてください。
        </div>
        <div class="FrmSyainSearch lbl-content">
            又は選択状態で選択ボタンをクリックしてください。
        </div>
        <div class="HMS-button-pane">
            <div class="FrmSyainSearch HMS-button-set">
                <button class="FrmSyainSearch cmdChoice Enter Tab" tabindex="4">
                    選択
                </button>
                <button class="FrmSyainSearch cmdCancel Enter Tab" tabindex="5">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
