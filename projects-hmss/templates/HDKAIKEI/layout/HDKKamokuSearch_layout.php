<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKKamokuSearch/HDKKamokuSearch"));
?>
<style type="text/css">
    .HDKKamokuSearch .HMS-button-set {
        margin: auto 2px;
    }

    .HDKKamokuSearch .ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }

    /* 最後の行の線を表示のために、旧システムと同じで修正する */
    .HDKKamokuSearch .ui-jqgrid .ui-jqgrid-htable th {
        height: 22px !important;
    }

    /* ツリーのiconの色のために（CSSに黒い色を変更したことがあるので、この画面はここで修正する） */
    .HDKKamokuSearch .ui-widget-content .ui-icon {
        background-image: url(css/jquery/images/ui-icons_0078ae_256x240.png);
    }
</style>
<div class='HDKKamokuSearch body'>
    <div class='HDKKamokuSearch HDKAIKEI-content'>
        <div>
            <label for="" class="HDKKamokuSearch lbl-sky-L">
                科目コード
            </label>
            <input class="HDKKamokuSearch txtKamoku txtKamokuCode Enter Tab" maxlength="8" tabindex="1" />
            <span class="HDKKamokuSearch Ittti1 ">(前方一致)</span>
        </div>
        <div>
            <label for="" class="HDKKamokuSearch lbl-sky-L">
                科目名
            </label>
            <input class="HDKKamokuSearch txtKamoku txtKamokuName Enter Tab" maxlength="30" tabindex="2" />
            <span class="HDKKamokuSearch Ittti2 ">(部分一致・漢字)</span>
        </div>
        <div>
            <label for="" class="HDKKamokuSearch lbl-sky-L">
                補助科目コード
            </label>
            <input class="HDKKamokuSearch txtKamoku txtSubkoumokuCode Enter Tab" maxlength="30" tabindex="3" />
            <span class="HDKKamokuSearch Ittti3 ">(前方一致)</span>
        </div>
        <div>
            <label for="" class="HDKKamokuSearch lbl-sky-L">
                補助科目名
            </label>
            <input class="HDKKamokuSearch txtKamoku txtSubkoumokuName Enter Tab" maxlength="30" tabindex="4" />
            <span class="HDKKamokuSearch Ittti4 ">(部分一致・漢字)</span>
        </div>
        <div class="HMS-button-pane grpGinko">
            <label for="" class="HDKKamokuSearch lbl-sky-L busyo-L"> 表示方法 </label>
            <input class='HDKKamokuSearch rdonotree Enter Tab' type="radio" name="HDKKamokuSearch_radio" checked="true"
                value="rdonotree" tabindex="4" />
            標準
            <input class='HDKKamokuSearch rdotree Enter Tab' type="radio" name="HDKKamokuSearch_radio" value="rdotree"
                tabindex="4" />
            ツリー
            <div class="HMS-button-set">
                <button class="HDKKamokuSearch btnView Enter Tab" tabindex="5">
                    表示
                </button>
            </div>
        </div>

        <div class="HDKKamokuSearch kam">
            <table id="HDKAIKEI_HDKKamokuSearch_sprItyp">
            </table>
        </div>
        <div class="HDKKamokuSearch treekam">
            <table id="HDKAIKEI_HDKKamokuSearch_treeprItyp">
            </table>
        </div>

        <div class="HMS-button-pane">
            <div class="HDKKamokuSearch HMS-button-set">
                <button class="HDKKamokuSearch btnSelect Enter Tab" tabindex="6">
                    選択
                </button>
                <button class="HDKKamokuSearch btnClose Enter Tab" tabindex="7">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
