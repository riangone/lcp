<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmBusyoSearch/FrmBusyoSearch"));
?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    input.FrmBusyoSearch.txtBusyoNM {
        width: 150px;
    }
</style>

<div class="FrmBusyoSearch">
    <div class="FrmBusyoSearch  JKSYS-content ">
        <div>
            <label class="FrmBusyoSearch lbl-sky-L" for=""> 部署コード </label>
            <input class="FrmBusyoSearch txtBusyoCD Enter Tab" type='text' maxlength="3" tabindex="0" />
        </div>
        <div class="HMS-button-pane">
            <label class="FrmBusyoSearch lbl-sky-L" for=""> 部署名 </label>
            <input class="FrmBusyoSearch txtBusyoNM Enter Tab" tabindex="1" />

            <div class="HMS-button-set">
                <button class="FrmBusyoSearch cmdSearch Enter Tab" tabindex="2">
                    検索
                </button>
            </div>
        </div>
        <div>
            検索条件を指定しない場合は全件検索です
        </div>
        <div>
            <table id="JKSYS_FrmBusyoSearch_sprItyp"></table>
        </div>
        <div>
            指定したい行をダブルクリックしてください。
        </div>
        <div>
            又は選択状態で選択ボタンをクリックしてください。
        </div>
        <div class="HMS-button-pane">
            <div class="FrmBusyoSearch HMS-button-set">
                <button class="FrmBusyoSearch cmdChoice Enter Tab" tabindex="3">
                    選択
                </button>
                <button class="FrmBusyoSearch cmdCancel Enter Tab" tabindex="4">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
