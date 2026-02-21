<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmArariSyukeiMst/FrmArariSyukeiMst"));
?>
<div class='FrmArariSyukeiMst'>
    <div class='FrmArariSyukeiMst  R4-content'>
        <table>
            <tr>
                <td>
                    <table id='FrmArariSyukeiMst_sprMeisai'>

                    </table>
                </td>
                <td valign="top">
                    <div style='width:170px;top:0px; margin-left:20px;border:solid 1px #a6c9e2;text-align:left '>
                        ※車種集計コードを廃止する場合は出力順を空白にしてください。順位が入力してある場合のみ車種別粗利益表に出力されます。空白の場合は出力されません。<br />※一度使用した集計コードを再利用することは出来ません。
                    </div>
                </td>
            </tr>

        </table>

        <div id='FrmArariSyukeiMst_pager'>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmArariSyukeiMst cmdInsert Enter Tab' tabindex="1">
                    新規追加
                </button>
                <button class='FrmArariSyukeiMst cmdUpdate Enter Tab' tabindex="2">
                    更新
                </button>
            </div>
        </div>
    </div>
</div>