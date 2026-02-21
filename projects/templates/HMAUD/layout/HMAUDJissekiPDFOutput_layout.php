<!DOCTYPE html>
<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                      FCSDL
* 20241030           202410_内部統制システム_集計機能改善対応.xlsx                   caina
* 20250219           20250219_内部統制_改修要望.xlsx                               caina
* 20250409           機能変更               202504_内部統制_要望.xlsx              lujunxia
* --------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('HMAUD/HMAUDJissekiPDFOutput/HMAUDJissekiPDFOutput'));
?>
<style type="text/css">
    .HMAUDJissekiPDFOutput.cours {
        width: 180px;
    }

    .HMAUDJissekiPDFOutput.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 175px; */
        width: 200px;
        /* 20250409 lujunxia upd e */
        margin-left: 5px;
        height: auto;
    }

    .HMAUDJissekiPDFOutput .HMS-button-pane {
        min-height: unset;
    }

    .HMAUDJissekiPDFOutput .HMS-button-pane button {
        margin: unset;
    }

    .HMAUDJissekiPDFOutput .pnlList {
        padding-top: 5px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="HMAUDJissekiPDFOutput">
    <div class='HMAUDJissekiPDFOutput HMAUD-content'>
        <div class='HMAUDJissekiPDFOutput search-panel1'>
            <label for="" class='HMAUDJissekiPDFOutput Label4 lbl-sky-L'> クール </label>
            <select class="HMAUDJissekiPDFOutput cours Enter Tab" tabindex="1" />
            <label for="" class="HMAUDJissekiPDFOutput courPeriod"></label>
        </div>
        <div class='HMAUDJissekiPDFOutput search-panel2'>
            <label for="" class='HMAUDJissekiPDFOutput LBL_TITLE_STD9 lbl-sky-L'> 領域 </label>
            <input type="checkbox" class="HMAUDJissekiPDFOutput territoryChbox tradeChbox Enter Tab" tabindex="2"
                checked="true" value="1" />
            <label for="" class='HMAUDJissekiPDFOutput'> 営業 </label>
            <input type="checkbox" class="HMAUDJissekiPDFOutput territoryChbox serviceChbox Enter Tab" tabindex="3"
                checked="true" value="2" />
            <label for="" class='HMAUDJissekiPDFOutput'> サービス </label>
            <input type="checkbox" class="HMAUDJissekiPDFOutput territoryChbox manageChbox Enter Tab" tabindex="4"
                checked="true" value="3" />
            <label for="" class='HMAUDJissekiPDFOutput'> 管理 </label>
            <input type="checkbox" class="HMAUDJissekiPDFOutput territoryChbox busiChbox Enter Tab" tabindex="5"
                checked="true" value="4" />
            <label for="" class='HMAUDJissekiPDFOutput'> 業売 </label>
            <input type="checkbox" class="HMAUDJissekiPDFOutput territoryChbox busiManageChbox Enter Tab" tabindex="6"
                checked="true" value="5" />
            <label for="" class='HMAUDJissekiPDFOutput'> 業売管理</label>
            <!-- 20250219 caina ins s -->
            <input type="checkbox" class="HMAUDJissekiPDFOutput territoryChbox carSevenChbox Enter Tab" tabindex="7"
                checked="true" value="6" hidden />
            <label for="" class='HMAUDJissekiPDFOutput carSevenlbl' hidden> カーセブン</label>
            <!-- 20250219 caina ins e -->
        </div>
        <!-- 20241030 caina ins s -->
        <div class='HMAUDJissekiPDFOutput search-panel3'>
            <label for="" class='HMAUDJissekiPDFOutput Label4 lbl-sky-L'> 集計種類 </label>
            <select class="HMAUDJissekiPDFOutput summery Enter Tab" tabindex="8">
                <option value="cumulative_issue_table">指摘事項表（累計）</option>
                <option value="consecutive_issue_table">指摘事項表（連続）</option>
                <option value="issue_ranking">指摘事項数ランキング</option>
                <option value="cumulative_multiple_issue_ranking">複数回指摘事項数ランキング（累計）</option>
                <option value="consecutive_multiple_issue_ranking">複数回指摘事項数ランキング（連続）</option>
                <option value="issue_ranking_per_territory">各領域ごと指摘項目ランキング</option>
                <option value="cumulative_multiple_issue_ranking_per_territory">各領域ごと複数回指摘項目ランキング（累計）</option>
            </select>
            <label for="" class="HMAUDJissekiPDFOutput summeryTypes"></label>
        </div>
        <!-- 20241030 caina ins e -->
        <div class="HMAUDJissekiPDFOutput HMS-button-pane">
            <button class='HMAUDJissekiPDFOutput btnJisseki button Enter Tab' tabindex="9">
                集計
            </button>
            <button class='HMAUDJissekiPDFOutput pdfDownload button Enter Tab' tabindex="10">
                PDFダウンロード
            </button>
            <button class='HMAUDJissekiPDFOutput xlsxDownload button Enter Tab' tabindex="11">
                XLSXダウンロード
            </button>
        </div>
        <div class="HMAUDJissekiPDFOutput pnlList">
            <iframe class="HMAUDJissekiPDFOutput temp" src="" style="width:100%"></iframe>
        </div>
    </div>
</div>