<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS702BusyoSearch/HMDPS702BusyoSearch"));
?>
<style type="text/css">
    .HMDPS702BusyoSearch .HMS-button-set {
        margin: auto 16px;
    }
</style>
<div class='HMDPS702BusyoSearch body'>
    <div class='HMDPS702BusyoSearch HMDPS-content'>
        <div>
            <label class="HMDPS702BusyoSearch lbl-sky-L" for=""> 部署コード </label>
            <input class="HMDPS702BusyoSearch txtDeploy txtDeployCode Enter Tab" maxlength="8" tabindex="1" />
        </div>

        <div>
            <label class="HMDPS702BusyoSearch lbl-sky-L" for=""> 部署略称名 </label>
            <input class="HMDPS702BusyoSearch txtDeploy txtdeployName Enter Tab" maxlength="30" tabindex="2" />
            <span class="HMDPS702BusyoSearch Label5 ">(前方一致)</span>
        </div>

        <div>
            <label class="HMDPS702BusyoSearch lbl-sky-L" for=""> 部署名ｶﾅ </label>
            <input class="HMDPS702BusyoSearch txtDeploy txtdeployKN Enter Tab" maxlength="30" tabindex="3" />
            <span class="HMDPS702BusyoSearch Label54 ">(前方一致・半角ｶﾅ)</span>
        </div>

        <div class="HMS-button-pane">
            <label class="HMDPS702BusyoSearch lbl-sky-L" for=""> 部署区分 </label>
            <input class='HMDPS702BusyoSearch rdoSin Enter Tab' type="radio" name="HMDPS702BusyoSearch_radio"
                checked="true" value="rdoSin" tabindex="4" />
            新車
            <input class='HMDPS702BusyoSearch rdoTyu Enter Tab' type="radio" name="HMDPS702BusyoSearch_radio"
                value="rdoTyu" tabindex="5" />
            中古車
            <input class='HMDPS702BusyoSearch rdoSno Enter Tab' type="radio" name="HMDPS702BusyoSearch_radio"
                value="rdoSno" tabindex="6" />
            その他
            <div class="HMS-button-set">
                <button class="HMDPS702BusyoSearch btnView Enter Tab" tabindex="7">
                    表示
                </button>
            </div>
        </div>
        <div>
            <div class='HMDPS702BusyoSearch sprItyp'>
                <table id="HMDPS_HMDPS702BusyoSearch_sprItyp"></table>
            </div>
        </div>

        <div class="HMS-button-pane">
            <div class="HMDPS702BusyoSearch HMS-button-set">
                <button class="HMDPS702BusyoSearch btnSelect Enter Tab" tabindex="8">
                    選択
                </button>
                <button class="HMDPS702BusyoSearch btnClose Enter Tab" tabindex="9">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>