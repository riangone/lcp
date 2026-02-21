<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmGenkaIn/FrmGenkaIn"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmGenkaIn'>
    <div class='FrmGenkaIn content R4-content' style="width: 1113px">
        <div>
            <table>
                <tr>
                    <td>
                        <label class="FrmGenkaIn Label4" for="">
                            取込CSVﾌｧｲﾙ:
                        </label>
                    </td>
                    <td width="15"></td>
                    <td colspan="4">
                        <input class="FrmGenkaIn txtFile" type="text" style="width: 600px" disabled="true" />
                    </td>

                </tr>
                <tr>
                    <td align="right">
                        <label class="FrmGenkaIn Label1" for="">
                            初期化：
                        </label>
                    </td>
                    <td>
                    </td>
                    <td align="center">
                        <input class="FrmGenkaIn rdoDELON Enter Tab" type="radio" name="rdoDEL" value="0" />
                        行う
                    </td>
                    <td align="center">
                        <input class="FrmGenkaIn rdoDELOFF Enter Tab" type="radio" name="rdoDEL" value="1" />
                        行わない
                    </td>
                    <td width="300px">
                    </td>
                    <td width="80px" align="right">
                        <button class="FrmGenkaIn cmdAct Enter Tab">
                            取込実行
                        </button>
                    </td>
                </tr>
            </table>
            <div style="height: 30px"></div>
            <table>
                <tr>
                    <td width="200">
                    </td>
                    <td>
                        <label class="FrmGenkaIn lblMSG2" for="">
                        </label>
                    </td>
                    <td width="100">
                    </td>
                    <td>
                        <label class="FrmGenkaIn lblKensu" for="">
                        </label>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>
