<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS700TorihikisakiSearch/HMDPS700TorihikisakiSearch"));
?>
<style type="text/css">
    .HMDPS700TorihikisakiSearch .HMS-button-set {
        margin: auto 16px;
    }
</style>
<div class='HMDPS700TorihikisakiSearch body'>
    <div class='HMDPS700TorihikisakiSearch HMDPS-content'>
        <div>
            <label class="HMDPS700TorihikisakiSearch lbl-sky-L" for="">
                取引先コード
            </label>
            <input class="HMDPS700TorihikisakiSearch txtTorihiki txtTorihikiCode Enter Tab" maxlength="8"
                tabindex="1" />
        </div>

        <div>
            <label class="HMDPS700TorihikisakiSearch lbl-sky-L" for="">
                取引先簡略名
            </label>
            <input class="HMDPS700TorihikisakiSearch txtTorihiki txtTorihikiName Enter Tab" maxlength="30"
                tabindex="2" />
            <span class="HMDPS700TorihikisakiSearch lblItti1 ">(前方一致)</span>
        </div>

        <div>
            <label class="HMDPS700TorihikisakiSearch lbl-sky-L" for="">
                取引先カナ名
            </label>
            <input class="HMDPS700TorihikisakiSearch txtTorihiki txtTorihikiKana Enter Tab" maxlength="38"
                tabindex="3" />
            <span class="HMDPS700TorihikisakiSearch lblItti2 ">(前方一致)</span>
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="HMDPS700TorihikisakiSearch btnView Enter Tab" tabindex="4">
                    表示
                </button>
            </div>
        </div>

        <div>
            <div class='HMDPS700TorihikisakiSearch sprItyp'>
                <table id="HMDPS_HMDPS700TorihikisakiSearch_sprItyp">
                </table>
            </div>
        </div>

        <div class="HMS-button-pane">
            <div class="HMDPS700TorihikisakiSearch HMS-button-set">
                <button class="HMDPS700TorihikisakiSearch btnSelect Enter Tab" tabindex="5">
                    選択
                </button>
                <button class="HMDPS700TorihikisakiSearch btnClose Enter Tab" tabindex="6">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>