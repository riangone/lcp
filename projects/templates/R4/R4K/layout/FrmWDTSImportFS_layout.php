<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmWDTSImportFS/FrmWDTSImportFS'));
?>

<div class="FrmWDTSImportFS R4-content">
    <table>
        <tr>
            <td>
                <label for="">
                    夜間バッチで起動するR4連携集計システム用データのインポートを手動で実行します。インポートはダウンロード後に行いますので、この処理はR4連携集計システム用ダウンロードが行われていないと実行できません。
                </label>
            </td>
        </tr>
        <tr>
            <td align='right'>
                <button class='FrmWDTSImportFS cmdAction'
                    style="float:right;margin-left: 10px;min-width: 100px;height: 25px">
                    実行
                </button>
            </td>
        </tr>
    </table>
</div>
