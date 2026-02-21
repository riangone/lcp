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
echo $this->Html->script(array("R4/R4K/FrmFurikae/FrmFurikae"));
?>

<!-- 画面個別の内容を表示 -->
<div id="FrmFurikae" class="R4-content">
    <div style="width: 980px">
        <div>
            <fieldset>
                <legend>
                    <b><span style="font-size: 10pt">検索条件</span></b>
                </legend>
                <table border="0">
                    <tr>
                        <td>
                            <label class="FrmFurikae" for="">
                                経理年月
                            </label>
                        </td>
                        <td>
                            <!-- 20150923 yin upd S -->
                            <!-- <input  type="text" class="FrmFurikae cboKeiriBi Enter Tab" style="width: 100px;" maxlength="7"> -->
                            <input type="text" class="FrmFurikae cboKeiriBi Enter Tab" style="width: 100px;"
                                maxlength="6">
                            <!-- 20150923 yin upd S -->
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
                            <label class="FrmFurikae" for="">
                                伝票№
                            </label>
                        </td>
                        <td>
                            <input type="text" class="FrmFurikae txtDenpyoNOFrom Enter Tab" style="width: 120px;"
                                maxlength="12">
                        </td>
                        <td>
                            ～
                        </td>
                        <td>
                            <input type="text" class="FrmFurikae txtDenpyoNOTo Enter Tab" style="width: 120px;"
                                maxlength="12">
                        </td>
                        <td>
                            <button class="FrmFurikae cmdSearch Enter Tab">
                                検索
                            </button>
                        </td>
                    </tr>
                </table>
            </fieldset>

        </div>

        <div style="margin-top: 20px;margin-left: 10px">
            <table class="FrmFurikae  sprMeisai" id="FrmFurikae_sprMeisai">
            </table>

        </div>
        <div class="HMS-button-pane" align="right" style="margin-top: 10px;">
            <!-- <button class="FrmFurikae cmdCsvOut Enter Tab">
            CSV出力
            </button> -->
            <button class="FrmFurikae cmdInsert Enter Tab">
                新規登録
            </button>
            <button class="FrmFurikae cmdUpdate Enter Tab">
                修正
            </button>
            <button class="FrmFurikae cmdDelete Enter Tab">
                削除
            </button>
        </div>
    </div>
</div>
