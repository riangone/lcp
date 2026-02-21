<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmExcelTorikomiKouka/FrmExcelTorikomiKouka"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmExcelTorikomiKouka.txtPath {
        width: 500px;
    }

    .FrmExcelTorikomiKouka.lbl-sky-xL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-left: 2px;
        width: 100px;
        height: 50px;
        line-height: 50px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmExcelTorikomiKouka.lbl-sky-xL {
            width: 78px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmExcelTorikomiKouka'>
    <div class='FrmExcelTorikomiKouka JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmExcelTorikomiKouka Label18 lbl-sky-L' for="">対象年月</label>
            <input type="text" class='FrmExcelTorikomiKouka dtpYMFROM Enter Tab' maxlength="7" tabindex="1" />
            ～
            <input type="text" class='FrmExcelTorikomiKouka dtpYMTO Enter Tab' maxlength="7" tabindex="2" />
        </div>
        <div>
            <label class='FrmExcelTorikomiKouka Label18 lbl-sky-L' for="">取込ファイル</label>

            <input class="FrmExcelTorikomiKouka txtPath Enter Tab" maxlength="100" disabled="true" />
            <button class="FrmExcelTorikomiKouka btnDialog Enter Tab" tabindex="3">
                ...
            </button>
        </div>
        <table>
            <tr>
                <td rowspan="2"><label class='FrmExcelTorikomiKouka Label18 lbl-sky-xL' for="">種類</label></td>
                <td>
                    <input class='FrmExcelTorikomiKouka rdbMode01 Tab Enter' type="radio"
                        name="FrmExcelTorikomiKouka_radio" value="1" tabindex="4" />
                    考課表_ボディコーティング
                </td>
            </tr>
            <tr>
                <td>
                    <input class='FrmExcelTorikomiKouka rdbMode02 Tab Enter' type="radio"
                        name="FrmExcelTorikomiKouka_radio" value="2" tabindex="5" />
                    考課表_延長保証
                </td>
            </tr>
        </table>
        <div class="FrmExcelTorikomiKouka HMS-button-pane">
            <div class='FrmExcelTorikomiKouka HMS-button-set'>
                <button class='FrmExcelTorikomiKouka btnAction Enter Tab' tabindex="6">
                    実行
                </button>
            </div>
        </div>
        <div id="tmpFileUpload_Kouka"></div>
    </div>
</div>