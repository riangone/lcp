<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                        FCSDL
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMAUD/HMAUDKansaJinSyusseki/HMAUDKansaJinSyusseki"));
?>
<style type="text/css">
    .HMAUDKansaJinSyusseki.HMS-button-pane button {
        margin-left: 80px;
    }

    .HMAUDKansaJinSyusseki.btnSearch {
        float: unset !important;
        margin-top: 9px !important;
        margin-left: 200px
    }
</style>
<div class="HMAUDKansaJinSyusseki">
    <div class="HMAUDKansaJinSyusseki HMAUD-content">
        <!-- 検索条件 -->
        <fieldset>
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label for="" class='HMAUDKansaJinSyusseki LBL_TITLE_STD9 lbl-sky-L'> 年月 </label>
                <input type="text" class="HMAUDKansaJinSyusseki dateInput Enter Tab" maxlength="10" tabindex="5" />
                <button class="HMAUDKansaJinSyusseki btnSearch button Enter Tab" tabindex="4">
                    検索
                </button>
            </div>
        </fieldset>
        <div class="HMAUDKansaJinSyusseki pnlList">
            <!-- jqgrid -->
            <table id="HMAUDKansaJinSyusseki_tblMain"></table>
        </div>
    </div>
</div>