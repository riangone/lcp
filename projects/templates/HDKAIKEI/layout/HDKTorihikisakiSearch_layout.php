<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKTorihikisakiSearch/HDKTorihikisakiSearch"));
?>
<style type="text/css">
    .HDKTorihikisakiSearch .HMS-button-set {
        margin: auto 2px;
    }
</style>
<div class='HDKTorihikisakiSearch body'>
    <div class='HDKTorihikisakiSearch HDKAIKEI-content'>
        <div>
            <label for="" class="HDKTorihikisakiSearch lbl-sky-L">
                取引先コード
            </label>
            <input class="HDKTorihikisakiSearch txtTorihiki txtTorihikiCode Enter Tab" maxlength="8" tabindex="1" />
        </div>

        <div>
            <label for="" class="HDKTorihikisakiSearch lbl-sky-L">
                取引先簡略名
            </label>
            <input class="HDKTorihikisakiSearch txtTorihiki txtTorihikiName Enter Tab" maxlength="30" tabindex="2" />
            <span class="HDKTorihikisakiSearch lblItti1 ">(前方一致)</span>
        </div>

        <div>
            <label for="" class="HDKTorihikisakiSearch lbl-sky-L">
                取引先カナ名
            </label>
            <input class="HDKTorihikisakiSearch txtTorihiki txtTorihikiKana Enter Tab" maxlength="38" tabindex="3" />
            <span class="HDKTorihikisakiSearch lblItti2 ">(前方一致)</span>
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="HDKTorihikisakiSearch btnView Enter Tab" tabindex="4">
                    表示
                </button>
            </div>
        </div>

        <div>
            <div class='HDKTorihikisakiSearch sprItyp'>
                <table id="HDKAIKEI_HDKTorihikisakiSearch_sprItyp">
                </table>
            </div>
        </div>

        <div class="HMS-button-pane">
            <div class="HDKTorihikisakiSearch HMS-button-set">
                <button class="HDKTorihikisakiSearch btnSelect Enter Tab" tabindex="5">
                    選択
                </button>
                <button class="HDKTorihikisakiSearch btnClose Enter Tab" tabindex="6">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>
