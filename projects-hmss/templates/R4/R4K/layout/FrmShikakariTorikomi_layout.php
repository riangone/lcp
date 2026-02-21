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
* 20201020 		  MAPのデータ取込追加			   依頼								YIN
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmShikakariTorikomi/FrmShikakariTorikomi"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class='FrmShikakariTorikomi'>
    <div class='FrmShikakariTorikomi content R4-content' style="width: 1113px">
        <div style="margin-left: 20px">
            <table>
                <tr>
                    <td>
                        <label class="FrmShikakariTorikomi Label3" style="width: 70px" for="">
                            処理年月
                        </label>
                    </td>
                    <td colspan="2">
                        <!-- 20150923 yin upd S -->
                        <!-- <input class="FrmShikakariTorikomi cboYM Enter Tab" style="width: 100px"/> -->
                        <input class="FrmShikakariTorikomi cboYM Enter Tab" style="width: 100px" maxlength="6" />
                        <!-- 20150923 yin upd E -->
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="FrmShikakariTorikomi Label1" for="">
                            取込先
                        </label>
                    </td>
                    <td>
                        <input class="FrmShikakariTorikomi txtFile Enter Tab" style="width: 500px" disabled="true" />
                    </td>
                    <td>
                        <button class="FrmShikakariTorikomi cmdOpen Enter Tab">
                            参照
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div style="height: 20px">
        </div>
        <div style="margin-left: 20px">
            <fieldset style="width: 605px">
                <table>
                    <tr>
                        <td>
                            <label style="width: 40px" for="">
                            </label>
                        </td>
                        <td>
                            <label class="FrmShikakariTorikomi Label4" for="">
                                開始時刻：
                            </label>
                        </td>
                        <td>
                            <!-- 20180126 YIN UPD S -->
                            <!-- <label class="FrmShikakariTorikomi lblStartTime" style="width: 100px;height:13px;border:inset;display: block"> -->
                            <label class="FrmShikakariTorikomi lblStartTime"
                                style="width: 100px;height:13px;border: solid 1px black;display: block" for="">
                                <!-- 20180126 YIN UPD E -->
                            </label>
                        </td>
                        <td width="60px" align="center">
                            <label class="FrmShikakariTorikomi Label8" for="">
                                →
                            </label>
                        </td>
                        <td>
                            <label class="FrmShikakariTorikomi Label7" for="">
                                終了予定時刻：
                            </label>
                        </td>
                        <td>
                            <!-- 20180126 YIN UPD S -->
                            <!-- <label class="FrmShikakariTorikomi lblEndTime" style="width: 100px;height:13px;border:inset;display: block"> -->
                            <label class="FrmShikakariTorikomi lblEndTime"
                                style="width: 100px;height:13px;border: solid 1px black;display:block" for="">
                                <!-- 20180126 YIN UPD E -->
                            </label>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <!-- 20201020 YIN INS S -->
        <div style="margin-left: 20px;margin-top: 20px">
            <table>
                <tr>
                    <td>
                        <label class="FrmShikakariTorikomi Label9" style="width: 100px" for="">
                            フォーマット
                        </label>
                    </td>
                    <td>
                        <input type='radio' name='radio_frmShikakariTorikomi'
                            class='FrmShikakariTorikomi radMap Tab Enter' />
                        MAP
                    </td>
                    <td>
                        <input type='radio' name='radio_frmShikakariTorikomi'
                            class='FrmShikakariTorikomi radBuhan Tab Enter' />
                        部販
                    </td>
                </tr>
            </table>
        </div>
        <!-- 20201020 YIN INS E -->
        <div style="margin-top: 20px">
            <button class="FrmShikakariTorikomi cmdAct Enter Tab"
                style="min-width: 100px;height: 25px;margin-left: 550px">
                取込実行
            </button>
        </div>
        <div id="tmpFileUpload">
        </div>
    </div>
</div>
