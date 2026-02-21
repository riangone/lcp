<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                      FCSDL
* 20250403           機能変更               202504_内部統制_要望.xlsx               CI
* 20251016           機能追加      202510_内部統制システム_仕様変更対応.xlsx         YIN
* 20251224      「副社長」——> 「社長」      202512_内部統制_変更要望.xlsx         YIN
* 20260126     「社長」欄を１つ廃止     202601_内部統制_変更要望.xlsx               YIN
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDReportInputedit/HMAUDReportInputedit'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMAUDReportInputedit.HMAUD-content {
        border: 1px #a6c9e2 solid !important;
    }

    .HMAUDReportInputedit.txtComment {
        width: 431px
    }
</style>

<div class="HMAUDReportInputedit HMAUDReportInputeditDialog">
    <div class="HMAUDReportInputedit HMAUD-content">
        <!-- 20240613 CI INS S -->
        <div class="HMAUDReportInputedit return">

            <div class="HMAUDReportInputedit" style="height:20px"> どこに差し戻しますか？</div>
            <table class="HMAUDReportInputcommentedit 2">
                <tr>
                    <td> <label class="HMAUDReportInputedit lbl-sky-L">
                            ステータス</label></td>
                    <td><input class="HMAUDReportInputedit return_94" type="radio" name="return" value="94"
                            checked="checked">改善取組責任者</input></td>
                </tr>
                <tr>
                    <!-- 20250403 CI UPD S -->
                    <td rowspan="5"> </td>
                    <!-- 20250403 CI UPD E -->
                    <td> <input class="HMAUDReportInputedit return_95" type="radio" name="return"
                            value="95">各領域責任者</input>
                    </td>

                </tr>
                <tr>
                    <td> <input class="HMAUDReportInputedit return_96" type="radio" name="return"
                            value="96">キーマン</input>
                    </td>

                </tr>
                <tr>
                    <td> <input class="HMAUDReportInputedit return_97" type="radio" name="return"
                            value="97">総括責任者</input>
                    </td>

                </tr>
                <!-- 20250403 CI UPD S -->
                <tr class="HMAUDReportInputedit return_98_display">
                    <td> <input class="HMAUDReportInputedit return_98" type="radio" name="return" value="98">取締役</input>
                    </td>

                </tr>
                <tr class="HMAUDReportInputedit return_99_display">
                    <td> <input class="HMAUDReportInputedit return_99" type="radio" name="return" value="99">社長</input>
                    </td>

                </tr>
                <!-- 20250403 CI UPD E -->
            </table>

        </div>
        <!-- 20240613 CI INS E -->
        <div>
            <table class="HMAUDReportInputcommentedit">
                <tr>
                    <td><label class="HMAUDReportInputedit lbl-sky-L">コメント</label></td>
                    <td rowspan="3"> <textarea class="HMAUDReportInputedit txtComment Tab Enter"
                            tabindex="0"></textarea></td>
                </tr>
                <tr style="height:20px"></tr>
                <tr style="height:10px"></tr>
            </table>

        </div>
        <div class="HMAUDReportInputedit HMS-button-pane">
            <button class="HMAUDReportInputedit btnClose HMS-button-set button Enter Tab" tabindex="2">
                キャンセル
            </button>
            <button class="HMAUDReportInputedit btnOK HMS-button-set button Enter Tab" tabindex="1">
                OK
            </button>

        </div>
        <div class="HMAUDReportInputedit hidCrDate"></div>
    </div>
</div>