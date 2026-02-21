<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmListSelect/FrmListSelect'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="FrmListSelect" align="center">
    <!--footer start-->
    <!-- <div class='FrmListSelect header R4-title R4-circle-conner'>
                    <label class="FrmControl Label1 R4-title-label">架装明細表示</label>
    </div> -->
    <!--footer end-->
    <div style="width:100%;margin-top:10px;">
        <table class="FrmListSelect sprList" id="FrmListSelect_sprList">

        </table>
        <!-- <div id="FrmListSelect_pager"></div> -->
        <div style="height: 20px"></div>
        <!--footer start-->
        <div align="right" 　style="margin-top: 17px">
            <table>
                <tr>
                    <td align="right">
                        <button class="FrmListSelect cmdCopy Enter Tab">
                            コピー
                        </button>
                        <button class="FrmListSelect cmdSelect Enter Tab">
                            選択
                        </button>
                        <button class="FrmListSelect cmdBack Enter Tab">
                            閉じる
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <!--footer end-->

</div>