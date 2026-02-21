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
<?php /**
  * @var Cake\View\View $this
  */
echo $this->Html->script(array("JKSYS/FrmJigyousyoZei/FrmJigyousyoZei")); ?>
<style type="text/css">
    .FrmJigyousyoZei.txtOld {
        text-align: right
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmJigyousyoZei">
    <div class='FrmJigyousyoZei JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmJigyousyoZei Label18 lbl-sky-L' for=""> 対象期間 </label>
            <input type="text" class='FrmJigyousyoZei dtpTaisyouYM_F  Tab Enter' maxlength="10" tabindex="1"
                id="FrmJigyousyoZei_dtpTaisyouYM_F" />
            ～
            <input type="text" class='FrmJigyousyoZei dtpTaisyouYM_T  Tab Enter' maxlength="10" tabindex="2"
                id="FrmJigyousyoZei_dtpTaisyouYM_T" />
        </div>
        <div>
            <label class='FrmJigyousyoZei Label1 lbl-sky-L' for=""> 老人年齢 </label>
            <input type="text" class='FrmJigyousyoZei txtOld Tab Enter' tabindex="3" 　 />
            <label for="">歳以上を老人としてカウントする</label>
        </div>
        <div class="FrmJigyousyoZei HMS-button-pane">
            <div class='FrmJigyousyoZei HMS-button-set'>
                <button class="FrmJigyousyoZei btnExcel Enter Tab" tabindex="4">
                    Excel出力
                </button>
            </div>
        </div>
    </div>
</div>
