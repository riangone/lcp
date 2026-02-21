<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmKyuyoInfoTake/FrmKyuyoInfoTake"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmKyuyoInfoTake.txtFile {
        width: 380px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmKyuyoInfoTake'>
    <div class='FrmKyuyoInfoTake JKSYS-content JKSYS-content-fixed-width'>
        <!-- 給与処理月 -->
        <div>
            <label class='FrmKyuyoInfoTake Label4 lbl-sky-L' for=""> 給与処理月 </label>
            <input class='FrmKyuyoInfoTake dtpYM Enter Tab' maxlength="6" tabindex="1" />
        </div>
        <!-- 取込種類 -->
        <div>
            <label class='FrmKyuyoInfoTake Label2 lbl-sky-L' for=""> 取込種類 </label>
            <input class='FrmKyuyoInfoTake chkKyuyo Enter Tab' type="radio" name="FrmKyuyoInfoTake_radio" value="1"
                tabindex="2" checked="true" />
            給与データ
            <input class='FrmKyuyoInfoTake chkSyoyo Enter Tab' type="radio" name="FrmKyuyoInfoTake_radio" value="2"
                tabindex="3" />
            賞与データ
        </div>
        <!-- 取込先 -->
        <div>
            <label class='FrmKyuyoInfoTake Label1 lbl-sky-L' for=""> 取込先 </label>
            <input class="FrmKyuyoInfoTake txtFile Enter Tab" disabled="true" />
            <button class="FrmKyuyoInfoTake btnDialog Enter Tab" tabindex="4">
                ...
            </button>
        </div>
        <!-- 取込ボタン -->
        <div class="FrmKyuyoInfoTake HMS-button-pane">
            <button class='FrmKyuyoInfoTake btnImport HMS-button-set Enter Tab'>
                取込
            </button>
        </div>
        <!-- ファイルダイアログ -->
        <div id="tmpFileUpload"></div>
    </div>
</div>
