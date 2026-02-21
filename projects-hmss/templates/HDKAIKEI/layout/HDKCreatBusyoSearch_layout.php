<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKCreatBusyoSearch/HDKCreatBusyoSearch"));
?>
<style type="text/css">
    .HDKCreatBusyoSearch .HMS-button-set {
        margin: auto 2px;
    }

    .HDKCreatBusyoSearch .lbl-sky-L {
        width: 100px;
    }

    .ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }
</style>
<div class='HDKCreatBusyoSearch body'>
    <div class='HDKCreatBusyoSearch HDKAIKEI-content'>
        <div>
            <label class="HDKCreatBusyoSearch lbl-sky-L"> 部署コード </label>
            <input class="HDKCreatBusyoSearch txtDeploy txtDeployCode Enter Tab" maxlength="16" tabindex="1" />
        </div>

        <div>
            <label class="HDKCreatBusyoSearch lbl-sky-L"> 部署略称名 </label>
            <input class="HDKCreatBusyoSearch txtDeploy txtdeployName Enter Tab" maxlength="30" tabindex="2" />
            <span class="HDKCreatBusyoSearch Label5 ">(前方一致)</span>
        </div>

        <div>
            <label class="HDKCreatBusyoSearch lbl-sky-L"> 部署名ｶﾅ </label>
            <input class="HDKCreatBusyoSearch txtDeploy txtdeployKN Enter Tab" maxlength="30" tabindex="3" />
            <span class="HDKCreatBusyoSearch Label54 ">(前方一致・半角ｶﾅ)</span>
        </div>

        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="HDKCreatBusyoSearch btnView Enter Tab" tabindex="9">
                    表示
                </button>
            </div>
        </div>
        <div>
            <div class='HDKCreatBusyoSearch sprItyp'>
                <table id="HDKAIKEI_HDKCreatBusyoSearch_sprItyp"></table>
            </div>
        </div>

        <div class="HMS-button-pane">
            <div class="HDKCreatBusyoSearch HMS-button-set">
                <button class="HDKCreatBusyoSearch btnSelect Enter Tab" tabindex="10">
                    選択
                </button>
                <button class="HDKCreatBusyoSearch btnClose Enter Tab" tabindex="11">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>