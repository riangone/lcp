<!-- /**
* 説明：
*
*
* @author yinhuaiyu
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150923                  #2162                   BUG                         YIN
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmKaverRankSyukei/FrmKaverRankSyukei"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmKaverRankSyukei">
    <div class="FrmKaverRankSyukei content R4-content" style="width: 1113px">
        <table>
            <tr>
                <td>
                    <label class="FrmKaverRankSyukei Label1" for="">
                        処理年月
                    </label>
                </td>
                <td style="width: 60px">
                </td>
                <td>
                    <!-- 20150923 yin upd S -->
                    <!-- <input class="FrmKaverRankSyukei cboYM Enter Tab" style="width: 120px" maxlength="7"> -->
                    <input class="FrmKaverRankSyukei cboYM Enter Tab" style="width: 120px" maxlength="6">
                    <!-- 20150923 yin upd S -->
                </td>
                <td style="width: 500px">
                </td>
                <td>
                    <button class="FrmKaverRankSyukei cmdAct Enter Tab">
                        実行
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>
