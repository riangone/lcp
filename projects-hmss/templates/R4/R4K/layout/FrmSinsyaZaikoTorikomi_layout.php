<!DOCTYPE html>
<?php
// echo $this -> Html -> script(array("common/d3.v3.min"));
// echo $this -> Html -> script(array("common/d3.tip"));
// echo $this -> Html -> css(array("timeline/timeline"));
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSinsyaZaikoTorikomi/FrmSinsyaZaikoTorikomi"));

// echo $this -> fetch('meta');
// echo $this -> fetch('css');
// echo $this -> fetch('script');
?>
<!-- <div class='FrmSinsyaZaikoTorikomi'>
<div class='FrmSinsyaZaikoTorikomi content R4-content' style="width: 1113px">
<div id="timeline" style="margin-left: 20px;background-color: #FFAA00">
</div>
</div>
</div> -->
<div class='FrmSinsyaZaikoTorikomi'>
    <div class='FrmSinsyaZaikoTorikomi content R4-content' style="width: 1113px">
        <div style="margin-left: 20px;margin-top: 20px">
            <table>
                <tr>
                    <td width="80" align="left">
                        <label class="FrmSinsyaZaikoTorikomi Label1" for="">
                            取込先
                        </label>
                    </td>
                    <td>
                        <input class="FrmSinsyaZaikoTorikomi txtFile" style="width: 500px" disabled="true" />
                    </td>
                    <td>
                        <button class="FrmSinsyaZaikoTorikomi cmdOpen Tab Enter">
                            参照
                        </button>
                    </td>
                </tr>
            </table>
            <div style="height: 30px">
            </div>
            <button class="FrmSinsyaZaikoTorikomi cmdAct Tab Enter"
                style="min-width: 100px;height: 25px;margin-left: 545px">
                取込実行
            </button>
            <div style="height: 30px">
            </div>
            <div id="FrmSinsyaZaikoTorikomiFileUpload">
            </div>
        </div>
    </div>
</div>
