<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKBusyoSearch/HDKBusyoSearch"));
?>
<style type="text/css">
    .HDKBusyoSearch .HMS-button-set {
        margin: auto 2px;
    }

    .HDKBusyoSearch .lbl-sky-L {
        width: 100px;
    }

    .ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }
</style>
<div class='HDKBusyoSearch body'>
    <div class='HDKBusyoSearch HDKAIKEI-content'>
        <div>
            <label class="HDKBusyoSearch lbl-sky-L" for=""> 部署コード </label>
            <input class="HDKBusyoSearch txtDeploy txtDeployCode Enter Tab" maxlength="16" tabindex="1" />
        </div>

        <div>
            <label class="HDKBusyoSearch lbl-sky-L" for=""> 部署略称名 </label>
            <input class="HDKBusyoSearch txtDeploy txtdeployName Enter Tab" maxlength="30" tabindex="2" />
            <span class="HDKBusyoSearch Label5 ">(前方一致)</span>
        </div>

        <div>
            <label class="HDKBusyoSearch lbl-sky-L" for=""> 部署名ｶﾅ </label>
            <input class="HDKBusyoSearch txtDeploy txtdeployKN Enter Tab" maxlength="30" tabindex="3" />
            <span class="HDKBusyoSearch Label54 ">(前方一致・半角ｶﾅ)</span>
        </div>

        <div class="HMS-button-pane">
            <label class="HDKBusyoSearch lbl-sky-L busyo-L" for=""> 部署区分 </label>
            <input class='HDKBusyoSearch rdoWho Enter Tab' type="radio" name="HDKBusyoSearch_radio" checked="true"
                value="rdoWho" tabindex="4" />
            すべて
            <input class='HDKBusyoSearch rdoInd Enter Tab' type="radio" name="HDKBusyoSearch_radio" value="rdoInd"
                tabindex="5" />
            間接部門
            <input class='HDKBusyoSearch rdoRen Enter Tab' type="radio" name="HDKBusyoSearch_radio" value="rdoRen"
                tabindex="6" />
            地代家賃取引先
            <input class='HDKBusyoSearch rdoCom Enter Tab' type="radio" name="HDKBusyoSearch_radio" value="rdoCom"
                tabindex="7" />
            本社ビルテナント
            <input class='HDKBusyoSearch rdoOth Enter Tab' type="radio" name="HDKBusyoSearch_radio" value="rdoOth"
                tabindex="8" />
            その他
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="HDKBusyoSearch btnView Enter Tab" tabindex="9">
                    表示
                </button>
            </div>
        </div>
        <div>
            <div class='HDKBusyoSearch sprItyp'>
                <table id="HDKAIKEI_HDKBusyoSearch_sprItyp"></table>
            </div>
        </div>

        <div class="HMS-button-pane">
            <div class="HDKBusyoSearch HMS-button-set">
                <button class="HDKBusyoSearch btnSelect Enter Tab" tabindex="10">
                    選択
                </button>
                <button class="HDKBusyoSearch btnClose Enter Tab" tabindex="11">
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>