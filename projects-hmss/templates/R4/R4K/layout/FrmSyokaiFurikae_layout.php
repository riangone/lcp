<!-- /**
* 説明：
*
*
* @author FCS
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
*   日付                   Feature/Bug               内容                        担当
* YYYYMMDD                 #ID                      XXXXXX                      FCSDL
* 20150918                 #2161                    BUG                         LI
* 20150928                 #2179                    BUG                         LI
* 20151124           	   BUG对应                    BUG                        Yin
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyokaiFurikae/FrmSyokaiFurikae"));
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        #FrmSyokaiFurikae div span[style*="font-size: 18pt"] {
            font-size: 13pt !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmSyokaiFurikae R4-content" id="FrmSyokaiFurikae">
    <div style="margin-top: 5px">

        <table border="0">
            <tr>
                <td style="width: 10px;">
                </td>
                <td>
                    <label class="FrmSyokaiFurikae lbl-sky-xM" for="">
                        計上年月
                    </label>
                </td>
                <td>
                    <!-- 20150928 li UPD S. -->
                    <!-- <input  type="text" class="FrmSyokaiFurikae cboYM Enter Tab" style="width: 100px;" maxlength="7"> -->
                    <!-- 20151124 Yin UPD S. -->
                    <div class="FrmSyokaiFurikae cboYMFromdiv" style="float: left">
                        <input type="text" class="FrmSyokaiFurikae cboYM Enter Tab" style="width: 100px;" maxlength="6">

                    </div>
                    <!-- 20151124 Yin UPD E. -->
                    <!-- 20150928 li UPD E. -->
                </td>
                <td>
                </td>
                <td>
                    <label class="FrmSyokaiFurikae lbl-sky-xM" for="">
                        伝票№
                    </label>
                </td>
                <td>
                    <input type="text" class="FrmSyokaiFurikae txtDenpyoNOFrom Enter Tab" style="width: 110px;"
                        maxlength="12">
                </td>
                <td>
                </td>
                <td>
                    <!-- <button class="FrmSyokaiFurikae cmdSearch Enter Tab" style="width: 80px;height: 23px">
                検索
                </button> -->
                </td>
                <td>
                    <label for="">
                        ※F3押下時、伝票№にﾌｫｰｶｽを移動します。
                    </label>
                </td>
            </tr>
        </table>

    </div>
    <div style="margin-top: 5px">
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">振替元</span></b>
            </legend>
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmSyokaiFurikae lbl-sky-xM" for="">
                            部署
                        </label>
                    </td>
                    <td>
                        <input type="text" class="FrmSyokaiFurikae txtMotoBusyoCD Enter Tab" style="width: 60px;"
                            maxlength="3">
                    </td>
                    <td>
                        <button class="FrmSyokaiFurikae cmdSearchBs_M">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="FrmSyokaiFurikae lblMotoBusyoNM " style="width:300px;"
                            disabled="disabled" />
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <label class="FrmSyokaiFurikae lbl-sky-xM" for="">
                            社員番号
                        </label>
                    </td>
                    <td>
                        <select class="FrmSyokaiFurikae cboMotoSyain Enter Tab" style="width: 200px">

                        </select>
                        <input type="text" class="FrmSyokaiFurikae txtMotoSyainNO Enter Tab" style="width: 55px;"
                            maxlength="5" />
                        <label class="FrmSyokaiFurikae lbl-sky-xM" style="margin-left: 15px" for="">
                            金額
                        </label>
                        <input type="text" class="FrmSyokaiFurikae txtMotoKingaku numeric Enter Tab"
                            style="width: 145px;text-align: right" maxlength="14" />
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div align="center" style="margin-top: 2px">
        <span style="font-size: 18pt"><b>↓</b></span>
    </div>
    <div style="margin-top: 2px">
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">振替先</span></b>
            </legend>
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmSyokaiFurikae lbl-sky-xM" for="">
                            部署
                        </label>
                    </td>
                    <td>
                        <input type="text" class="FrmSyokaiFurikae txtSakiBusyoCD Enter Tab" style="width: 60px;"
                            maxlength="3">
                    </td>
                    <td>
                        <button class="FrmSyokaiFurikae cmdSearchBs_S">
                            検索
                        </button>
                    </td>
                    <td>
                        <input type="text" class="FrmSyokaiFurikae lblSakiBusyoNM Enter Tab" style="width:300px;"
                            disabled="disabled" />
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <label class="FrmSyokaiFurikae lbl-sky-xM" for="">
                            社員番号
                        </label>
                    </td>
                    <td>
                        <select class="FrmSyokaiFurikae cboSakiSyain Enter Tab" style="width: 200px">
                        </select>
                        <input type="text" class="FrmSyokaiFurikae txtSakiSyainNO Enter Tab" style="width: 55px;"
                            maxlength="5" />

                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div style="margin-top: 5px;">
        <table class="FrmSyokaiFurikae  sprMeisai" id="FrmSyokaiFurikae_sprMeisai">
        </table>

    </div>
    <div align="right" style="margin-top: 5px;">
        <label class="FrmSyokaiFurikae lbl-sky-xM" for="">
            合計
        </label>
        <input type="text" class="FrmSyokaiFurikae txtGoukei" style="width: 145px; margin-right:15px;text-align: right"
            value="0" disabled="disabled" />
    </div>


    <div class="HMS-button-pane" align="right" style="margin-top: 1.38vh;">
        <!-- 20150918 li UPD S. -->
        <!--<button class="FrmSyokaiFurikae cmdBack Enter Tab">
            戻る
        </button>

        <button class="FrmSyokaiFurikae cmdDelete Enter Tab">
            削除
        </button>

        <button class="FrmSyokaiFurikae cmdAction Enter Tab">
            登録（F9）
        </button> -->
        <td width="200px"></td>
        <button class="FrmSyokaiFurikae cmdAction Enter Tab">
            登録（F9）
        </button>

        <button class="FrmSyokaiFurikae cmdDelete Enter Tab">
            削除
        </button>

        <button class="FrmSyokaiFurikae cmdBack Enter Tab">
            戻る
        </button>
        <!-- 20150918 li UPD E. -->
    </div>
    <input type="text" class="FrmSyokaiFurikae lblCreateDt" style="visibility: hidden;display: none" />
    <input type="text" class="FrmSyokaiFurikae lblDispNo" style="visibility: hidden;display: none" />
</div>
