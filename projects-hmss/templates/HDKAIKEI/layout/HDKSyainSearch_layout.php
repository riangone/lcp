<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKSyainSearch/HDKSyainSearch"));
?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HDKSyainSearch.txtShainn,
    .HDKSyainSearch.txtBusyo {
        width: 135px;
    }

    .HDKSyainSearch.lblBusyo {
        width: 340px;
    }

    .HDKSyainSearch .HMS-button-set {
        margin: auto 16px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HDKSyainSearch.lblBusyo {
            width: 364px;
        }
    }
</style>

<div class="HDKSyainSearch body">
    <div class="HDKSyainSearch  HDKAIKEI-content">
        <div>
            <label class="HDKSyainSearch lbl-sky-L" for="">社員№</label>
            <input class="HDKSyainSearch txtShainn txtShainnNo Enter Tab" type='text' maxlength="8" tabindex="1" />
        </div>
        <div>
            <label class="HDKSyainSearch lbl-sky-L" for="">社員名</label>
            <input class="HDKSyainSearch txtShainn txtShainnNM Enter Tab" type='text' maxlength="30" tabindex="2" />
            <label for=""> (前方一致) </label>
        </div>
        <div>
            <label class="HDKSyainSearch lbl-sky-L" for="">社員名カナ</label>
            <input class="HDKSyainSearch txtShainn txtShainnNM_Kana Enter Tab" type='text' maxlength="30"
                tabindex="3" />
            <label for=""> (前方一致) </label>
        </div>
        <div>
            <label class="HDKSyainSearch lbl-sky-L" for="">部署</label>
            <input class="HDKSyainSearch txtBusyo Enter Tab" maxlength="38" tabindex="4" />
            <button class="HDKSyainSearch btnSearch Enter Tab" tabindex="5">
                検索
            </button>
            <input type="text" class="HDKSyainSearch lblBusyo Enter Tab" disabled="disabled" />
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="HDKSyainSearch btnHyouji Enter Tab" tabindex="6">
                    表示
                </button>
            </div>
        </div>
        <div>
            <div class="HDKSyainSearch tableItyp">
                <table id="HDKAIKEI_HDKSyainSearch_sprItyp"></table>
            </div>
        </div>
        <div class="HMS-button-pane">
            <div class="HDKSyainSearch HMS-button-set">
                <button class="HDKSyainSearch btnSenntaku Enter Tab" tabindex="7">
                    選択
                </button>
                <button class="HDKSyainSearch btnModoru Enter Tab" tabindex="8">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>