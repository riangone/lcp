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
echo $this->Html->script(array('HMAUD/HMAUDKansaItemTeigi/HMAUDKansaItemTeigi'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .HMAUDKansaItemTeigi.txtFile {
        width: 380px;
    }

    .HMAUDKansaItemTeigi.field {
        width: 180px;
    }

    .HMAUDKansaItemTeigi.cours {
        width: 180px;
    }

    /*指摘事項NO71:ボタン押下不可時の表示:背景色「灰色」*/
    .HMAUDKansaItemTeigi button[disabled] {
        background-color: #C3C3C3 !important;
    }

    .HMAUDKansaItemTeigi.courPeriod {
        /* 20250409 lujunxia upd s */
        /* width: 175px; */
        width: 200px;
        /* 20250409 lujunxia upd e */
        margin-left: 5px;
    }
</style>
<div class='HMAUDKansaItemTeigi'>
    <div class='HMAUDKansaItemTeigi HMAUD-content HMAUD-content-fixed-width'>
        <div>
            <label for="" class='HMAUDKansaItemTeigi Label4 lbl-sky-L'> クール </label>
            <select class="HMAUDKansaItemTeigi cours Enter Tab" tabindex="0" />
            <label for="" class="HMAUDKansaItemTeigi courPeriod"></label>
        </div>
        <div>
            <label for="" class='HMAUDKansaItemTeigi Label2 lbl-sky-L'> 領域 </label>
            <select class="HMAUDKansaItemTeigi field Enter Tab" tabindex="1"></select>
        </div>
        <div>
            <label for="" class='HMAUDKansaItemTeigi Label1 lbl-sky-L'> ファイル </label>
            <input class="HMAUDKansaItemTeigi txtFile Enter Tab" disabled="true" />
            <button class="HMAUDKansaItemTeigi btnDialog Enter Tab" tabindex="2">
                参照
            </button>
        </div>
        <div class="HMAUDKansaItemTeigi HMS-button-pane">
            <button class='HMAUDKansaItemTeigi btnReturn HMS-button-set Enter Tab' tabindex="4">
                戻る
            </button>
            <button class='HMAUDKansaItemTeigi btnUpload HMS-button-set Enter Tab' tabindex="3">
                アップロード
            </button>

        </div>
        <div id="tmpFileUpload"></div>
    </div>
</div>