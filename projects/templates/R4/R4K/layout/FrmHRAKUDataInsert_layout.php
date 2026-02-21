<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmHRAKUDataInsert/FrmHRAKUDataInsert"));
?>
<style type="text/css">
    .FrmHRAKUDataInsert.btnwidth {
        height: 25px;
    }

    .FrmHRAKUDataInsert .containClass {
        margin-top: 20px;
    }

    .FrmHRAKUDataInsert .containClass table {
        margin-left: 20px;
    }

    .FrmHRAKUDataInsert .titleClass {
        margin-bottom: 10px;
    }

    .FrmHRAKUDataInsert .buttonMarginLeft {
        margin-left: 20px;
    }

    .FrmHRAKUDataInsert .sbtn {
        min-width: 31px;
        margin-left: 15px;
        margin-right: 15px;
    }

    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmHRAKUDataInsert.btnwidth {
            height: 21px;
        }
    }
</style>
<div class='FrmHRAKUDataInsert'>
    <div class='FrmHRAKUDataInsert content R4-content' style="width: 1113px">
        <div class="containClass">
            <div class="titleClass"><b>１、楽楽精算のデータを取り込む</b></div>
            <table>
                <tr>
                    <td width="80" align="left">
                        <label class="FrmHRAKUDataInsert Label1" for="">
                            取込先
                        </label>
                    </td>
                    <td>
                        <input class="FrmHRAKUDataInsert txtFile" style="width: 500px" disabled="true" />
                    </td>
                    <td>
                        <button class="FrmHRAKUDataInsert cmdOpen Tab Enter btnwidth" tabindex="1">
                            参照
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="FrmHRAKUDataInsert cmdAct Tab Enter btnwidth"
                            style="width: 150px; margin-top: 15px; margin-bottom:25px" tabindex="2">
                            楽楽精算データ読込
                        </button>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
            <div class="FrmHRAKUDataInsert titleClass"><b>２、グループを設定する</b></div>
            <table>
                <tr>
                    <td width="80" align="left">
                        <label class="FrmHRAKUDataInsert Label1" for="">
                            グループ名
                        </label>
                    </td>
                    <td>
                        <input class="FrmHRAKUDataInsert groupName Tab Enter" style="width: 500px" maxlength="25"
                            tabindex="3" />
                    </td>
                    <td>
                        <label class="FrmHRAKUDataInsert Label1" for="">
                            （最大全角25文字）
                        </label>
                    </td>
                </tr>
                <tr>
                    <td width="80" align="left">
                        <label class="FrmHRAKUDataInsert Label1" for="">
                            経理処理日
                        </label>
                    </td>
                    <td>
                        <input class='FrmHRAKUDataInsert cboYM Enter Tab' style="width: 100px" maxlength="10"
                            tabindex="4">
                        <label class="FrmHRAKUDataInsert Label1" for="">
                            （yyyy/mm/dd）
                        </label>
                    </td>
                </tr>
            </table>
            <div class='HMS-button-pane'>
                <button class="FrmHRAKUDataInsert cmdSet Tab Enter btnwidth buttonMarginLeft" style="margin-bottom:35px"
                    tabindex="5">
                    設定
                </button>
            </div>
            <div><b>３、ファイルを作成する</b></div>
            <div class='HMS-button-pane'>
                <table>
                    <tr>
                        <td width="80" align="left">
                            <label class="FrmHRAKUDataInsert Label1" for="">
                                担当者
                            </label>
                        </td>
                        <td>
                            <input type="text" class="FrmHRAKUDataInsert txtSYAIN_NO Enter Tab" style="width: 52px;"
                                maxlength="5" tabindex="6">
                        </td>
                        <td>
                            <button class="FrmHRAKUDataInsert syainSearch Tab Enter sbtn" tabindex="7">
                                検索
                            </button>
                        </td>
                        <td>
                            <label class="FrmHRAKUDataInsert lblSYAIN_NM" for="">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td width="80" align="left">
                            <label class="FrmHRAKUDataInsert Label1" for="">
                                入力拠点
                            </label>
                        </td>
                        <td>
                            <input type="text" class="FrmHRAKUDataInsert txtBUSYO_CD Enter Tab" style="width: 52px;"
                                maxlength="3" tabindex="8">
                        </td>
                        <td>
                            <button class="FrmHRAKUDataInsert busyoSearch Tab Enter sbtn" tabindex="9">
                                検索
                            </button>
                        </td>
                        <td>
                            <label class="FrmHRAKUDataInsert lblBUSYO_NM" for="">
                            </label>
                        </td>
                    </tr>
                </table>
                <div class='HMS-button-pane'>
                    <button class="FrmHRAKUDataInsert cmdComplete Tab Enter btnwidth buttonMarginLeft" tabindex="10">
                        作成
                    </button>
                </div>
                <div style="height: 20px">
                </div>
                <div id="FrmHRAKUDataInsertFileUpload">
                </div>
            </div>
        </div>
    </div>
