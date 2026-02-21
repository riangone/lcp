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
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmShikakariPrint/FrmShikakariPrint"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- 画面個別の内容を表示 -->
<div class='FrmShikakariPrint'>
    <div class='FrmShikakariPrint content R4-content' style="width: 1113px">
        <div style="float: left;margin-left: 20px;margin-bottom: 20px;margin-top: 20px">
            <fieldset style="width: 120px">
                <legend>
                    帳票選択
                </legend>
                <table>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmShikakariPrint radNew Tab Enter" type="radio"
                                name="FrmShikakariPrint_radio">
                            新車

                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmShikakariPrint radOld Tab Enter" type="radio"
                                name="FrmShikakariPrint_radio">
                            中古車

                        </td>
                    </tr>
                    <tr height="10">
                    </tr>
                    <tr>
                        <td>
                            <input class="FrmShikakariPrint radDouble Tab Enter" type="radio"
                                name="FrmShikakariPrint_radio">
                            両方

                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div style="float: left;margin-left: 20px;margin-top: 20px">
            <fieldset>
                <legend>
                    出力対象
                </legend>
                <table>
                    <tr>
                        <td class="FrmShikakariPrint Label1">
                            処理年月
                        </td>
                        <td width="30">
                        </td>
                        <td>
                            <!-- 20150923 yin upd S -->
                            <!-- <input  class="FrmShikakariPrint cboYMStart Tab Enter" style="width: 80px" maxlength="7"/> -->
                            <input class="FrmShikakariPrint cboYMStart Tab Enter" style="width: 80px" maxlength="6" />
                            <!-- 20150923 yin upd E -->
                        </td>
                        <td width="300">
                        </td>
                        <td>
                            <button class="FrmShikakariPrint cmdAction Tab Enter">
                                実行
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>

        </div>

    </div>
</div>