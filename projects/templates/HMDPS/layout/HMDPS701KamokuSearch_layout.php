<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS701KamokuSearch/HMDPS701KamokuSearch"));
?>
<style type="text/css">
    .HMDPS701KamokuSearch .HMS-button-set {
        margin: auto 16px;
    }
</style>
<div class='HMDPS701KamokuSearch body'>
    <div class='HMDPS701KamokuSearch HMDPS-content'>
        <div>
            <label class="HMDPS701KamokuSearch lbl-sky-L" for="">
                科目コード
            </label>
            <input class="HMDPS701KamokuSearch txtKamoku txtKamokuCode Enter Tab" maxlength="8" tabindex="1" />
            <span class="HMDPS701KamokuSearch Ittti1 ">(前方一致)</span>
        </div>

        <div>
            <label class="HMDPS701KamokuSearch lbl-sky-L" for="">
                項目コード
            </label>
            <input class="HMDPS701KamokuSearch txtKamoku txtKoumokuCode Enter Tab" maxlength="30" tabindex="2" />
        </div>

        <div>
            <label class="HMDPS701KamokuSearch lbl-sky-L" for="">
                科目名
            </label>
            <input class="HMDPS701KamokuSearch txtKamoku txtKamokuName Enter Tab" maxlength="30" tabindex="3" />
            <span class="HMDPS701KamokuSearch Ittti2 ">(部分一致・漢字)</span>
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="HMDPS701KamokuSearch btnView Enter Tab" tabindex="4">
                    表示
                </button>
            </div>
        </div>

        <div>
            <table id="HMDPS_HMDPS701KamokuSearch_sprItyp">
            </table>
        </div>

        <div class="HMS-button-pane">
            <div class="HMDPS701KamokuSearch HMS-button-set">
                <button class="HMDPS701KamokuSearch btnSelect Enter Tab" tabindex="5">
                    選択
                </button>
                <button class="HMDPS701KamokuSearch btnClose Enter Tab" tabindex="6">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>