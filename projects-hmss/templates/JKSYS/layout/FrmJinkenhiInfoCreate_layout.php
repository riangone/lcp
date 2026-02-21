<!-- /**
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20240307   202402_人事給与システム_人件費データexce入出力機能追加l             caina
 * --------------------------------------------------------------------------------------------
 */ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmJinkenhiInfoCreate/FrmJinkenhiInfoCreate"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmJinkenhiInfoCreate.set-margin {
        display: inline;
        margin-left: 100px;
    }

    .FrmJinkenhiInfoCreate.btnWidth {
        width: 209px;
    }

    .FrmJinkenhiInfoCreate.cmdXlsxDiv {
        margin-top: 23px
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmJinkenhiInfoCreate.btnWidth {
            width: 166px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmJinkenhiInfoCreate'>
    <div class='FrmJinkenhiInfoCreate JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmJinkenhiInfoCreate Label1 lbl-sky-L' for=""> 対象年月
            </label>
            <input class='FrmJinkenhiInfoCreate dtpYM Enter Tab' tabindex="1" maxlength="6" />
            <div class="FrmJinkenhiInfoCreate set-margin  HMS-button-pane">
                <button class='FrmJinkenhiInfoCreate cmdExecute Enter Tab' tabindex="2">
                    実行
                </button>
            </div>
        </div>
    </div>
    <div class='FrmJinkenhiInfoCreate JKSYS-content JKSYS-content-fixed-width cmdXlsxDiv'>
        <div class="FrmJinkenhiInfoCreate HMS-button-pane">
            <button class='FrmJinkenhiInfoCreate cmdXlsxOut btnWidth Enter Tab' tabindex="3">
                EXCELに出力
            </button>
            <button class='FrmJinkenhiInfoCreate cmdXlsxIn btnWidth set-margin Enter Tab' tabindex="4">
                EXCELを取込
            </button>
        </div>
        <!-- ファイルダイアログ -->
        <div id="tmpFileUpload"></div>
    </div>
</div>