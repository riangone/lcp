<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKOBCDataExpImp/HDKOBCDataExpImp"));
?>
<style type="text/css">
    .HDKOBCDataExpImp .HMS-button-set {
        margin: auto 16px;
    }

    .HDKOBCDataExpImp .btn {
        width: 120px;
    }

    .HDKOBCDataExpImp .comSyukkou {
        width: 180px;
    }
</style>
<div class='HDKOBCDataExpImp body'>
    <div class='HDKOBCDataExpImp HDKAIKEI-content'>
        <div>
            <select class="HDKOBCDataExpImp selectTable comSyukkou tabindex Enter Tab" tabindex="1">
                <option selected="selected"></option>
                <option value="HDK_MST_KAMOKU">科目・補助科目</option>
                <option value="HDK_MST_SHZKBN">消費税区分</option>
                <option value="HDK_MST_TORIHIKISAKI">取引先</option>
                <option value="HDK_MST_BUMON">部門</option>
                <option value="HDK_MST_BANK">金融機関</option>
            </select>

            <button class='HDKOBCDataExpImp btnExport tabindex btn Enter Tab' tabindex="2">
                エクスポート
            </button>
            <button class='HDKOBCDataExpImp btnImport tabindex btn Enter Tab' tabindex="3">
                インポート
            </button>
            <div id="tmpFileUpload"></div>
        </div>
    </div>
</div>