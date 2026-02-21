<!--
/**
* 説明：
*
*
* @author FCS
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20150922           #2162                     BUG                            Yuanjh
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyokaiFurikaeList/FrmSyokaiFurikaeList"));
?>
<style type="text/css">
    .FrmSyokaiFurikaeList.lbl-sky-L {
        width: 101px !important;
    }

    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        #FrmSyokaiFurikaeList fieldset legend b span {
            font-size: 11px !important;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div id="FrmSyokaiFurikaeList">
    <div>
        <fieldset>
            <legend>
                <b><span style="font-size: 10pt">検索条件</span></b>
            </legend>
            <table border="0">
                <tr>
                    <td>
                        <label class="FrmSyokaiFurikaeList lbl-sky-L" for="">
                            年月
                        </label>
                    </td>
                    <td>
                        <!-- 20150922 Yuanjh UPD S. -->
                        <!--<input  type="text" class="FrmSyokaiFurikaeList cboKeiriBi Enter Tab" style="width: 100px;" maxlength="7">-->
                        <input type="text" class="FrmSyokaiFurikaeList cboKeiriBi Enter Tab" style="width: 100px;"
                            maxlength="6">
                        <!-- 20150922 Yuanjh UPD E. -->
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="FrmSyokaiFurikaeList lbl-sky-L" for="">
                            伝票番号
                        </label>
                    </td>
                    <td>
                        <input type="text" class="FrmSyokaiFurikaeList txtDenpyoNOFrom Enter Tab" style="width: 120px;"
                            maxlength="12">
                    </td>
                    <td>
                        ～
                    </td>
                    <td>
                        <input type="text" class="FrmSyokaiFurikaeList txtDenpyoNOTo Enter Tab" style="width: 120px;"
                            maxlength="12">
                    </td>
                    <td>
                    </td>
                </tr>
                <td>
                    <label class="FrmSyokaiFurikaeList lbl-sky-L" for="">
                        振替元社員番号
                    </label>
                </td>
                <td>
                    <input type="text" class="FrmSyokaiFurikaeList txtSyainNO Enter Tab" style="width: 100px;"
                        maxlength="5">
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <button class="FrmSyokaiFurikaeList cmdSearch Enter Tab">
                        検索
                    </button>
                </td>
            </table>
        </fieldset>

    </div>

    <div style="margin-top: 2.75vh;">
        <table class="FrmSyokaiFurikaeList  sprMeisai" id="FrmSyokaiFurikaeList_sprMeisai">
        </table>

    </div>
    <div class="HMS-button-pane" align="right" style="margin-top: 10px;">
        <button class="FrmSyokaiFurikaeList cmdInsert Enter Tab">
            <label for="">
                新規登録
            </label>
        </button>
        <button class="FrmSyokaiFurikaeList cmdUpdate Enter Tab">
            <label for="">
                修正
            </label>
        </button>
        <button class="FrmSyokaiFurikaeList cmdDelete Enter Tab">
            <label for="">
                削除
            </label>
        </button>
    </div>
</div>
