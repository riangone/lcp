<!-- /**
* 説明：
*
*
* @author lijun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
*       日付                   Feature/Bug                    内容                      担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/KRSS/FrmSimBusyoMst/FrmSimBusyoMst'));
?>
<style>
    .FrmSimBusyoMst .styled-label {
        text-align: left;
        height: 21px;
        line-height: 21px;
        display: inline-block;
    }
</style>
<div class="KRSS FrmSimBusyoMst R4-content">

    <div class="KRSS HMS-button-pane" style="margin-left: 10px;margin-top: 10px">
        <table id='FrmSimBusyoMst_sprList'></table>
    </div>
    <div style="margin-left: 650px;position: absolute;margin-top: -330px">
        <fieldset style="border-color: #808080;width: 130px">
            <table>
                <tr>
                    <td><span class="styled-label">新車の場合</span></td>
                    <td><span class="styled-label">0</span></td>
                </tr>
                <tr>
                    <td><span class="styled-label">中古車の場合</span></td>
                    <td><span class="styled-label">1</span></td>
                </tr>
                <tr>
                    <td><span class="styled-label">整備の場合</span></td>
                    <td><span class="styled-label">2</span></td>
                </tr>
                <tr>
                    <td><span class="styled-label">対象外</span></td>
                    <td><span class="styled-label">空白</span></td>
                </tr>
            </table>

        </fieldset>
    </div>

    <div class="HMS-button-pane">
        <div class='HMS-button-set'>
            <button class='KRSS FrmSimBusyoMst cmdUpdate Enter Tab'>
                登録
            </button>
        </div>
    </div>
</div>