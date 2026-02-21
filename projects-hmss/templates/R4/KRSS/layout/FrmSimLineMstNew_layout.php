<!-- /**
* 説明：
*
*
* @author fanzhengzhou
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
*       日付                   Feature/Bug                    内容                      担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmSimLineMstNew/FrmSimLineMstNew"));
?>
<style type="text/css">
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmSimLineMstNew .btnMargin {
            margin-left: 725px !important;
        }

    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='KRSS FrmSimLineMstNew'>
    <div class='KRSS FrmSimLineMstNew R4-content'>
        <div>
            <div class="FrmSimLineMstNew btnMargin" style="margin-left: 820px;margin-top: 10px">
                <button class='KRSS FrmSimLineMstNew update Enter Tab' style="width: 100px;height: 25px">
                    更新
                </button>
                <button class="KRSS FrmSimLineMstNew cancel Enter Tab" style="width: 100px;height: 25px">
                    キャンセル
                </button>
            </div>
            <table>
                <tr>
                    <td>
                        <div>
                            <label for="">（ライン）</label>
                        </div>
                        <div>
                            <table id="FrmSimLineMstNew_LINE"></table>
                        </div>
                    </td>
                    <td width="20"></td>
                    <td>
                        <div>
                            <label for="">（科目から集計）</label>
                        </div>
                        <div>
                            <table id="FrmSimLineMstNew_KAMOKU"></table>
                        </div>
                        <div style="height: 16px"></div>
                        <div>
                            <label for="">（科目以外から集計）</label>
                        </div>
                        <div>
                            <input class="KRSS FrmSimLineMstNew KAMOKU_OUTSIDE" style="width: 370px" />
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
