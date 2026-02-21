<?php 
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS703SyainSearch/HMDPS703SyainSearch")); ?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMDPS703SyainSearch.txtBusyo {
        width: 50px;
    }

    .HMDPS703SyainSearch.lblBusyo {
        width: 150px;
    }

    .HMDPS703SyainSearch .HMS-button-set {
        margin: auto 16px;
    }
</style>

<div class="HMDPS703SyainSearch body">
    <div class="HMDPS703SyainSearch  HMDPS-content">
        <div>
            <label class="HMDPS703SyainSearch lbl-sky-L" for="">社員№</label>
            <input class="HMDPS703SyainSearch txtShainn txtShainnNo Enter Tab" type='text' maxlength="8" tabindex="1" />
        </div>
        <div>
            <label class="HMDPS703SyainSearch lbl-sky-L" for="">社員名</label>
            <input class="HMDPS703SyainSearch txtShainn txtShainnNM Enter Tab" type='text' maxlength="30"
                tabindex="2" />
            <label for=""> (前方一致) </label>
        </div>
        <div>
            <label class="HMDPS703SyainSearch lbl-sky-L" for="">社員名カナ</label>
            <input class="HMDPS703SyainSearch txtShainn txtShainnNM_Kana Enter Tab" type='text' maxlength="30"
                tabindex="3" />
            <label for=""> (前方一致) </label>
        </div>
        <div>
            <label class="HMDPS703SyainSearch lbl-sky-L" for="">部署</label>
            <input class="HMDPS703SyainSearch txtBusyo Enter Tab" maxlength="38" tabindex="4" />
            <button class="HMDPS703SyainSearch btnSearch Enter Tab" tabindex="5">
                検索
            </button>
            <input type="text" class="HMDPS703SyainSearch lblBusyo Enter Tab" disabled="disabled" />
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="HMDPS703SyainSearch btnHyouji Enter Tab" tabindex="6">
                    表示
                </button>
            </div>
        </div>
        <div>
            <div class="HMDPS703SyainSearch tableItyp">
                <table id="HMDPS_HMDPS703SyainSearch_sprItyp"></table>
            </div>
        </div>
        <div class="HMS-button-pane">
            <div class="HMDPS703SyainSearch HMS-button-set">
                <button class="HMDPS703SyainSearch btnSenntaku Enter Tab" tabindex="7">
                    選択
                </button>
                <button class="HMDPS703SyainSearch btnModoru Enter Tab" tabindex="8">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>