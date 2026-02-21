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
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmHyogoInfoTake/FrmHyogoInfoTake"));
?>
<style type="text/css">
    .FrmHyogoInfoTake.txtFile {
        width: 380px;
    }

    .FrmHyogoInfoTake.cboJisshiYM {
        width: 130px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmHyogoInfoTake">
    <div class='FrmHyogoInfoTake JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class="FrmHyogoInfoTake lbl-sky-L" tabindex="0" for=""> 評価実施年月 </label>

            <input type="search" list="selectconditions" class="FrmHyogoInfoTake cboJisshiYM Enter Tab" tabindex="1" />
        </div>
        <div>
            <label class="FrmHyogoInfoTake lbl-sky-L" tabindex="2" for=""> 評価対象期間 </label>
            <label class='FrmHyogoInfoTake TaisyouKikanFrom' for=""></label>
            <label class="FrmHyogoInfoTake Label" for=""> ～ </label>
            <label class='FrmHyogoInfoTake TaisyouKikanTo' for=""></label>
        </div>
        <div>
            <label class="FrmHyogoInfoTake lbl-sky-L" tabindex="4" for=""> 取込先 </label>
            <input class="FrmHyogoInfoTake txtFile" disabled="true" tabindex="5" />
            <button class="FrmHyogoInfoTake cmdOpen Enter Tab" tabindex="6">
                ...
            </button>
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="FrmHyogoInfoTake cmdCancel Enter Tab" tabindex="7">
                    キャンセル
                </button>
                <button class="FrmHyogoInfoTake cmdDelete Enter Tab" tabindex="8">
                    削除
                </button>
                <button class="FrmHyogoInfoTake cmdTorikomi Enter Tab" tabindex="9">
                    再取込
                </button>
            </div>
        </div>

        <div id="tmpFileUpload"></div>
        <div>
            <label class="FrmHyogoInfoTake" for="">評価データ取込履歴</label>
        </div>
        <div>
            <table class="FrmHyogoInfoTake DataTable" id="FrmHyogoInfoTake_DataTable"></table>
        </div>
        <div class="HMS-button-pane">
            <div class="HMS-button-set">
                <button class="FrmHyogoInfoTake cmdSelect Enter Tab" tabindex="10">
                    選択
                </button>
            </div>
        </div>

    </div>
</div>
