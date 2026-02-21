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
* 20151027                  #2241                   BUG                         LI
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmKanrSyukei/FrmKanrSyukei"));
?>

<!-- 画面個別の内容を表示 -->
<div class="FrmKanrSyukei R4-content">
    <div style="width: 680px;margin-top: 10px">
        <div>
            <fieldset>
                <legend>
                    <b><span style="font-size: 10pt">検索条件</span></b>
                </legend>
                <table border="0">
                    <tr>
                        <td>
                            <label class="FrmKanrSyukei" for="">
                                処理年月
                            </label>
                        </td>
                        <td>
                            <!-- 20150923 yin upd S -->
                            <!-- <input  type="text" class="FrmKanrSyukei cboYM Enter Tab" style="width: 100px;" maxlength="7"> -->
                            <input type="text" class="FrmKanrSyukei cboYM Enter Tab" style="width: 100px;"
                                maxlength="6">
                            <!-- 20150923 yin upd E -->
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>

    <div style="width: 680px;margin-top: 10px">

        <div>
            <fieldset>
                <legend>
                    <b><span style="font-size: 10pt">集計結果</span></b>
                </legend>
                <table border="0">
                    <tr>
                        <td>
                            <label class="FrmKanrSyukei" for="">
                                振替データ読込み件数
                            </label>
                        </td>
                        <td>
                            <!--  20151027 li UPD S. -->
                            <!-- <input  type="text" class="FrmKanrSyukei lblFurikaeReadCnt Enter Tab" style="width: 300px;" disabled="disabled"> -->
                            <input type="text" class="FrmKanrSyukei lblFurikaeReadCnt Enter Tab"
                                style="width: 300px; text-align: right;" disabled="disabled">
                            <!--  20151027 li UPD E. -->
                        </td>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmKanrSyukei" for="">
                                会計データ読込み件数
                            </label>
                        </td>
                        <td>
                            <!--  20151027 li UPD S. -->
                            <!-- <input  type="text" class="FrmKanrSyukei lblKaikeiReadCnt Enter Tab" style="width: 300px;" disabled="disabled"> -->
                            <input type="text" class="FrmKanrSyukei lblKaikeiReadCnt Enter Tab"
                                style="width: 300px; text-align: right;" disabled="disabled">
                            <!--  20151027 li UPD E. -->
                        </td>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmKanrSyukei" for="">
                                借方金額合計
                            </label>
                        </td>
                        <td>
                            <!--  20151027 li UPD S. -->
                            <!-- <input  type="text" class="FrmKanrSyukei lblKariGKSum Enter Tab" style="width: 300px;" disabled="disabled"> -->
                            <input type="text" class="FrmKanrSyukei lblKariGKSum Enter Tab"
                                style="width: 300px; text-align: right;" disabled="disabled">
                            <!--  20151027 li UPD E. -->
                        </td>
                        <td>
                    </tr>
                    <tr>
                        <td>
                            <label class="FrmKanrSyukei" for="">
                                貸方金額合計
                            </label>
                        </td>
                        <td>
                            <!--  20151027 li UPD S. -->
                            <!-- <input  type="text" class="FrmKanrSyukei lblKasiGKSum Enter Tab" style="width: 300px;" disabled="disabled"> -->
                            <input type="text" class="FrmKanrSyukei lblKasiGKSum Enter Tab"
                                style="width: 300px; text-align: right;" disabled="disabled">
                            <!--  20151027 li UPD E. -->
                        </td>
                        <td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
    <div style="margin-top: 10px">
        <input type="checkbox" class="FrmKanrSyukei chkPrint" />
        作表のみ
    </div>
    <div>
        <label class="FrmKanrSyukei lblMSG" for="">
            <!-- aa -->
        </label>
    </div>
    <div class="HMS-button-pane" align="right" style="margin-top: 10px;width: 680px">
        <button class="FrmKanrSyukei cmdAction Enter Tab">
            実行
        </button>

    </div>

</div>
