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
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20150922                  #2164                   BUG                         LI
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmStaffMemoMnt/FrmStaffMemoMnt"));
?>
<style>
    .optionWidth1 {
        width: 95px
    }

    .optionWidth2 {
        width: 95px
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmStaffMemoMnt">
    <div class="FrmStaffMemoMnt  R4-content" align="center">
        <div style="margin-top: 5px">
            <table border="0" width="99%">
                <!--20150922 li UPD S. -->
                <!-- <tr height="70px"> -->
                <tr height="50px">
                    <!--20150922 li UPD E. -->
                    <td width="35px">
                    </td>
                    <!--20150922 li UPD S. -->
                    <!-- <td width="290px"> -->
                    <td width="200px">
                        <!--20150922 li UPD E. -->
                        <div>
                            <!--20150922 li UPD S. -->
                            <!-- <fieldset style="height: 61px;margin-top: 6px"> -->
                            <fieldset style="height: 44px;margin-top: 6px">
                                <!--20150922 li UPD E. -->
                                <!--20150922 li UPD S. -->
                                <!-- <div align="center" style="margin-top: 25px"> -->
                                <div align="center" style="margin-top: 15px">
                                    <!--20150922 li UPD E. -->
                                    <input type="radio" name="rad" value="新車" class="FrmStaffMemoMnt radSinsya Tab"
                                        checked="checked" />
                                    新車

                                    <input type="radio" name="rad" value="中古車" class="FrmStaffMemoMnt radChuko Tab"
                                        style="margin-left: 60px" />
                                    中古車
                                </div>

                            </fieldset>
                        </div>
                    </td>
                    <td width="35px">
                    </td>
                    <!--20150922 li UPD S. -->
                    <!-- <td width="290px"> -->
                    <td width="200px">
                        <!--20150922 li UPD E. -->
                        <div>
                            <!--20150922 li UPD S. -->
                            <!-- <fieldset style="height: 68px"> -->
                            <fieldset style="height: 50px">
                                <!--20150922 li UPD E. -->
                                <legend>
                                    <b><span style="font-size: 10pt">フォント見本</span></b>
                                </legend>
                                <!--20150922 li UPD S. -->
                                <!-- <div style="margin-top: 15px" align="center"> -->
                                <div style="margin-top: 5px" align="center">
                                    <!--20150922 li UPD E. -->
                                    <label class="FrmStaffMemoMnt lblMihon" style="font-size: 8.25pt" for="">
                                        あ
                                    </label>
                                    <select class="FrmStaffMemoMnt cboFontSize Tab"
                                        style="width: 60px;margin-left: 40px">
                                        <option value="0" style="width: 60px"></option>
                                        <option value="1" style="width: 60px">7pt</option>
                                        <option value="2" style="width: 60px">9pt</option>
                                    </select>
                                    <select class="FrmStaffMemoMnt cboFontType Tab"
                                        style="width: 60px;margin-left: 20px">
                                        <option value="0" style="width: 60px"></option>
                                        <option value="1" style="width: 60px"> 太字</option>
                                    </select>
                                </div>

                            </fieldset>
                        </div>

                    </td>
                    <!--20150922 li UPD S. -->
                    <!-- <td width="35px"> -->
                    <!-- </td> -->
                    <td width="30px">
                    </td>
                    <!--20150922 li UPD E. -->
                    <!--20150922 li UPD S. -->
                    <!-- <td width="290px"> -->
                    <td width="180px">
                        <!--20150922 li UPD E. -->
                        <div style="margin-top: 5px;margin-left: 15px;margin-bottom: 5px">
                            <label class="FrmStaffMemoMnt label1" for="">
                                サイズを指定しない場合は、8ptで表示されます。
                            </label>
                            <label class="FrmStaffMemoMnt label2" for="">
                                サイズを指定しない場合は、9ptで表示されます。
                            </label>
                        </div>
                    </td>
                    <td width="35px">
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 10px">
            <table id="FrmStaffMemoMnt_sprMeisai">
            </table>
        </div>
        <div class="HMS-button-pane" style="margin-top: 20px">
            <div class="HMS-button-set">
                <button class="FrmStaffMemoMnt cmdAction Enter Tab">
                    登録
                </button>
            </div>
        </div>

    </div>
</div>
