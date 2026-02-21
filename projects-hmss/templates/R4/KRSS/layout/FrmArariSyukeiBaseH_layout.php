<!DOCTYPE html>
<!--
/**
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* 20201117            bug                      ボタンのレイアウトが間違っています  ZhangBoWen
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/KRSS/FrmArariSyukeiBaseH/FrmArariSyukeiBaseH'));
?>
<style>
    .my-div {
        float: float;
        margin-left: 820px;
        margin-top: 10px;
    }

    /* 当屏幕缩放 ≥ 150% 时，调整 margin-left */
    @media (resolution: 1.5dppx),
    (-webkit-device-pixel-ratio: 1.5),
    (device-pixel-ratio: 1.5) {
        .my-div {
            float: float !important;
            margin-left: 720px !important;
            margin-top: 10px !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->

<div class='KRSS FrmArariSyukeiBaseH R4-content'>
    <div style="float: left;margin-left: 5px;margin-top: 5px;margin-bottom: 5px">
        <table id='FrmArariSyukeiBaseH_sprList'></table>
    </div>
    <!-- 20201117 zhangbowen upd S -->
    <!-- <div style="float:float;margin-left: 840px;margin-top: 10px"> -->
    <div class="my-div">
        <table>
            <tr>
                <td>
                    <!-- <button class="KRSS FrmArariSyukeiBaseH cmdUpdate Enter Tab" style="width: 85px;height: 25px"> -->
                    <button class="KRSS FrmArariSyukeiBaseH cmdUpdate Enter Tab" style="width: 90px;height: 25px">
                        更新
                    </button>
                </td>
                <td>
                    <!-- <button class="KRSS FrmArariSyukeiBaseH cmdback Enter Tab" style="width: 85px;height: 25px"> -->
                    <button class="KRSS FrmArariSyukeiBaseH cmdback Enter Tab" style="width: 90px;height: 25px">
                        キャンセル
                    </button>
                </td>
                <!-- 20201117 zhangbowen upd E -->
            </tr>
        </table>
    </div>
</div>