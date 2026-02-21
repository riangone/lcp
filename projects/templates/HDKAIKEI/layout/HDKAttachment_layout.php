<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKAttachment/HDKAttachment"));
?>
<style type="text/css">
    .HDKAttachment .HMS-button-pane {
        padding-top: 20px;
    }

    /*折行*/
    #HDKAIKEI_HDKAttachment_grid tr.jqgrow td {
        word-wrap: break-word;
        white-space: pre-wrap !important;
    }

    .HDKAttachment .btn {
        margin-left: 0px !important;
    }

    .HDKAttachment .txtTorihiki .td {
        width: 100% !important;
    }

    .HDKAttachment .txtTorihiki {
        width: 98.7% !important;
    }

    .HDKAttachment .txtTekyo {
        width: 99%;
    }

    .HDKAttachment.Label1 {
        height: 37px;
        line-height: 37px;
        padding: 2px;
    }

    .HDKAttachment textarea {
        height: 36px;
    }

    .HDKAttachment .Label2 {
        width: 99.5%;
        height: 14px;
        line-height: 14px;
    }

    .HDKAttachment .txtSyohy_no {
        width: 154px;
    }

    .HDKAttachment .sprItyp {
        margin-top: 3px;
    }
</style>
<div class='HDKAttachment body'>
    <div class='HDKAttachment HDKAIKEI-content'>
        <table>
            <tr>
                <td><label for="" class="HDKAttachment lbl-sky-L"> 証憑NO </label></td>
                <td>
                    <input class="HDKAttachment txtSyohy_no lbl-grey-L Enter Tab" readonly="true" />
                </td>

                <td><label for="" class="HDKAttachment lbl-sky-L"> 取引先 </label></td>
                <td class="HDKAttachment txtTorihiki td">
                    <input class="HDKAttachment txtTorihiki lbl-grey-L Enter Tab" readonly="true" />
                </td>
            </tr>
            <tr>
                <td><label for="" class="HDKAttachment Label1 lbl-sky-L"> 摘要 </label></td>
                <td colspan="3">
                    <textarea class="HDKAttachment txtTekyo lbl-grey-L Enter Tab" rows="2" readonly="true"></textarea>
                </td>
            </tr>
            <tr class="HDKPatternSearch HMS-button-pane">
                <td>
                    <button class="HDKAttachment btn btnAdd Enter Tab" tabindex="1">
                        追加
                    </button>
                    <div id="tmpFileUpload"></div>
                </td>
                <td>
                    <button class="HDKAttachment btn btnDelete Enter Tab" tabindex="2">
                        削除
                    </button>
                </td>
                <td>
                </td>
                <td style="text-align:end">
                    <button class="HDKAttachment btnClose Enter Tab" tabindex="3">
                        戻る
                    </button>
                </td>
            </tr>
            <!-- <tr>
                <td colspan="4"><label class="HDKAttachment lbl-yellow-L Label2"> 添付ファイル（最大5MB、形式はpdfのみ可） </label></td>
            </tr> -->
        </table>
        <div class="HDKAttachment pnlList">
            <label for="" class="HDKAttachment lbl-yellow-L Label2"> 添付ファイル（最大5MB、形式はpdfのみ可）
            </label>
            <div class='HDKAttachment sprItyp'>
                <table id="HDKAIKEI_HDKAttachment_grid"></table>
            </div>
        </div>
        <div class="HDKAttachment pnlList">
            <label for="" class="HDKAttachment lbl-yellow-L Label2">プレビュー</label>
        </div>
        <div class="HDKAttachment pnlList">
            <iframe class="HDKAttachment temp" src="" style="width:100%"></iframe>
        </div>
    </div>
</div>