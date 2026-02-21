<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKBankSearch/HDKBankSearch"));
?>
<style type="text/css">
    .HDKBankSearch .HMS-button-set {
        margin: auto 2px;
    }

    .ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }

    .HDKBankSearch.btnView {
        width: 98px;
    }
</style>
<div class='HDKBankSearch body'>
    <div class='HDKBankSearch HDKAIKEI-content'>
        <div>
            <label for="" class="HDKBankSearch lbl-sky-L">
                金融機関コード
            </label>
            <input class="HDKBankSearch txtBank txtBankCode Enter Tab" maxlength="8" tabindex="1" />
            <span class="HDKBankSearch Ittti1 ">(前方一致)</span>
        </div>
        <div>
            <label for="" class="HDKBankSearch lbl-sky-L">
                金融機関名
            </label>
            <input class="HDKBankSearch txtBank txtBankName Enter Tab" maxlength="30" tabindex="2" />
            <span class="HDKBankSearch Ittti2 ">(部分一致・漢字)</span>
        </div>
        <div>
            <label for="" class="HDKBankSearch lbl-sky-L">
                支店コード
            </label>
            <input class="HDKBankSearch txtBank txtBranchCode Enter Tab" maxlength="30" tabindex="3" />
            <span class="HDKBankSearch Ittti3 ">(前方一致)</span>
        </div>
        <div>
            <label for="" class="HDKBankSearch lbl-sky-L">
                支店名
            </label>
            <input class="HDKBankSearch txtBank txtBranchName Enter Tab" maxlength="30" tabindex="4" />
            <span class="HDKBankSearch Ittti4 ">(部分一致・漢字)</span>
            <div class="HMS-button-set ">
                <button class="HDKBankSearch btnView Enter Tab" tabindex="5">
                    表示
                </button>
            </div>
        </div>

        <div class="HDKBankSearch kam">
            <table id="HDKAIKEI_HDKBankSearch_sprItyp">
            </table>
        </div>

        <div class="HMS-button-pane">
            <div class="HDKBankSearch HMS-button-set">
                <button class="HDKBankSearch btnSelect Enter Tab" tabindex="6">
                    選択
                </button>
                <button class="HDKBankSearch btnClose Enter Tab" tabindex="7">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
