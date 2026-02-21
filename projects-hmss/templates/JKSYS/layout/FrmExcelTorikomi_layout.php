<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmExcelTorikomi/FrmExcelTorikomi"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmExcelTorikomi.txtPath {
        width: 500px;
    }

    .lbl-sky-xL {
        background-color: #87CEFA;
        border: solid 1px black;
        padding: 0px 3px;
        margin-top: 5px;
        margin-left: 2px;
        width: 100px;
        height: 220px;
        line-height: 205px;

    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmExcelTorikomi .lbl-sky-xL {
            width: 78px;

        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmExcelTorikomi'>
    <div class='FrmExcelTorikomi JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmExcelTorikomi Label18 lbl-sky-L' for="">対象年月</label>
            <input type="text" class='FrmExcelTorikomi dtpYM Enter Tab' maxlength="6" />
        </div>
        <div>
            <label class='FrmExcelTorikomi Label18 lbl-sky-L' for="">取込ファイル</label>

            <input class="FrmExcelTorikomi txtPath Enter Tab" disabled="true" />
            <button class="FrmExcelTorikomi btnDialog Enter Tab">
                ...
            </button>
        </div>
        <table>
            <tr>
                <td rowspan="9">
                    <label class='FrmExcelTorikomi Label18 lbl-sky-xL' for="">種類</label>
                </td>
                <td>
                    <input class='FrmExcelTorikomi rdbMode01 Tab Enter' checked="true" type="radio"
                        name="FrmExcelTorikomi_radio" value="1" />
                    （TMRH）リース
                </td>
            </tr>
            <tr>
                <td>
                    <input class='FrmExcelTorikomi rdbMode02 Tab Enter' type="radio" name="FrmExcelTorikomi_radio"
                        value="2" />
                    JAF件数
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmExcelTorikomi rdbMode03 Tab Enter" type="radio" name="FrmExcelTorikomi_radio"
                        value="3" />
                    人員
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmExcelTorikomi rdbMode04 Tab Enter" type="radio" name="FrmExcelTorikomi_radio"
                        value="4" />
                    管理台数表
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmExcelTorikomi rdbMode05 Tab Enter" type="radio" name="FrmExcelTorikomi_radio"
                        value="5" />
                    任意保険新規
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmExcelTorikomi rdbMode06 Tab Enter" type="radio" name="FrmExcelTorikomi_radio"
                        value="6" />
                    パックDeメンテ
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmExcelTorikomi rdbMode07 Tab Enter" type="radio" name="FrmExcelTorikomi_radio"
                        value="7" />
                    （TMRH）リース_再リース
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmExcelTorikomi rdbMode09 Tab Enter" type="radio" name="FrmExcelTorikomi_radio"
                        value="9" />
                    サービス貢献度
                </td>
            </tr>
            <tr>
                <td>
                    <input class="FrmExcelTorikomi rdbMode10 Tab Enter" type="radio" name="FrmExcelTorikomi_radio"
                        value="10" />
                    営業活動報告書
                </td>
            </tr>
        </table>
        <div class="FrmExcelTorikomi HMS-button-pane">
            <div class='FrmExcelTorikomi HMS-button-set'>
                <button class='FrmExcelTorikomi btnAction Enter Tab'>
                    実行
                </button>
            </div>
        </div>
        <div id="tmpFileUpload"></div>
    </div>
</div>