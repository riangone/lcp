<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                        FCSDL
* 20250409           機能変更               202504_内部統制_要望.xlsx              lujunxia
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMAUD/HMAUDGijirokuULDL/HMAUDGijirokuULDL"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .HMAUDGijirokuULDL.txtFile {
        width: 380px;
    }

    .HMAUDGijirokuULDL.field {
        width: 180px;
    }

    .HMAUDGijirokuULDL.cours {
        width: 180px;
    }

    /*折行*/
    .HMAUDGijirokuULDL.pnlList .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMAUDGijirokuULDL.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 175px; */
        width: 200px;
        /* 20250409 lujunxia upd e */
        margin-left: 5px;
    }

    .HMAUDGijirokuULDL.btnDownload,
    .HMAUDGijirokuULDL.btnUpload {
        margin-top: 5px;
        margin-left: -1px;
    }

    .HMAUDGijirokuULDL .grid-button {
        margin-top: 10px;
    }

    .HMAUDGijirokuULDL .btnDelete {
        margin-left: 258px;
    }

    .HMAUDGijirokuULDL .Label-upload {
        margin-top: 10px;
        ;
        background-color: #DAEEF3;
        width: 60%
    }

    .HMAUDGijirokuULDL .Label-download {
        margin-top: 20px;
        ;
        background-color: #DAEEF3;
        width: 60%
    }
</style>
<div class='HMAUDGijirokuULDL'>
    <div class='HMAUDGijirokuULDL HMAUD-content HMAUD-content-fixed-width'>
        <div>
            <label for="" class='HMAUDGijirokuULDL Label4 lbl-sky-L'> クール </label>
            <select class="HMAUDGijirokuULDL cours Enter Tab" tabindex="0" />
            <label for="" class="HMAUDGijirokuULDL courPeriod"></label>
        </div>
        <div>
            <label for="" class='HMAUDGijirokuULDL Label-upload'> アップロード </label>
        </div>
        <div>
            <label for="" class='HMAUDGijirokuULDL Label1 lbl-sky-L'> ファイル選択 </label>
            <input class="HMAUDGijirokuULDL txtFile Enter Tab" disabled="true" />
            <button class="HMAUDGijirokuULDL btnDialog btn Enter Tab" tabindex="1">
                参照
            </button>
        </div>
        <div>
            <label for="" class='HMAUDGijirokuULDL Label1 lbl-sky-L'> 検索キーワード </label>
            <input class="HMAUDGijirokuULDL searchKeyword Enter Tab" maxlength="100" tabindex="2" />
        </div>
        <div class="HMAUDGijirokuULDL HMS-button-pane">
            <button class='HMAUDGijirokuULDL btnUpload btn Enter Tab' tabindex="3">
                アップロード
            </button>
        </div>
        <div id="tmpFileUpload"></div>
        <div>
            <label for="" class='HMAUDGijirokuULDL Label-download'> ダウンロード </label>
        </div>
        <div>
            <label for="" class='HMAUDGijirokuULDL Label1 lbl-sky-L'> キーワード </label>
            <input class="HMAUDGijirokuULDL keyword Tab" maxlength="100" tabindex="4" />
        </div>

        <div class="HMAUDGijirokuULDL HMS-button-pane grid-button">
            <button class='HMAUDGijirokuULDL btnDownload btn Enter Tab' tabindex="5">
                ダウンロード
            </button>
            <button class='HMAUDGijirokuULDL btnDelete btn Enter Tab' tabindex="6">
                削除
            </button>
        </div>
        <!-- jqgrid -->
        <div class="HMAUDGijirokuULDL pnlList">
            <table id="HMAUDGijirokuULDL_tblMain"></table>
        </div>
    </div>
</div>