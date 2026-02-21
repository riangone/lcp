<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMDPS/HMDPS103PatternSearch/HMDPS103PatternSearch")); ?>
<style type="text/css">
    .HMDPS103PatternSearch.BusyoCD {
        width: 80px;
    }

    .HMDPS103PatternSearch.set-inline {
        display: inline;
    }

    .HMDPS103PatternSearch.txtPatternName {
        width: 280px;
    }

    .HMDPS103PatternSearch.textmin {
        min-width: 400px;
    }

    .HMDPS103PatternSearch.jqgridHidden .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HMDPS103PatternSearch.rediominBig {
        min-width: 90px;
    }

    .HMDPS103PatternSearch.redioSmall {
        min-width: 70px;
    }

    .HMDPS103PatternSearch fieldset {
        padding: 0px 5px 0px;
    }

    .HMDPS103PatternSearch.HMS-button-pane {
        min-height: 15px;
        margin: 1px;
    }

    .HMDPS103PatternSearch.Kensaku {
        min-width: 60px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HMDPS103PatternSearch.textmin {
            min-width: 381px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='HMDPS103PatternSearch body'>
    <div class='HMDPS103PatternSearch HMDPS-content'>
        <!-- 20240426 YIN UPD S -->
        <!-- <fieldset> -->
        <fieldset class='HMDPS103PatternSearch fieldset'>
            <!-- 20240426 YIN UPD E -->
            <legend class="HMDPS103PatternSearch lblSearchTitle">
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr class="HMDPS103PatternSearch HMS-button-pane">
                    <td><label for="" class='HMDPS103PatternSearch Label4 lbl-sky-L'> 伝票種類 </label></td>
                    <td class='HMDPS103PatternSearch redioSmall'>
                        <input class='HMDPS103PatternSearch Subete Enter Tab' type="radio"
                            name="PatternSearch_radio_DENPYO" value="3" tabindex="1" checked="true" />
                        全て
                    </td>
                    <td class='HMDPS103PatternSearch rediominBig'>
                        <input class='HMDPS103PatternSearch Shiwake Enter Tab' type="radio"
                            name="PatternSearch_radio_DENPYO" value="1" tabindex="2" />
                        仕訳伝票
                    </td>
                    <td colspan="3" class='HMDPS103PatternSearch rediominBig'>
                        <input class='HMDPS103PatternSearch Shiharai Enter Tab' type="radio"
                            name="PatternSearch_radio_DENPYO" value="2" tabindex="3" />
                        支払伝票
                    </td>
                    <td class='HMDPS103PatternSearch textmin'><label for="" class="HMDPS103PatternSearch lbl-sky-L">
                            パターン名
                        </label>
                        <input type="text" class="HMDPS103PatternSearch txtPatternName Enter Tab" tabindex="8" />
                    </td>
                </tr>
                <tr class="HMDPS103PatternSearch">
                    <td><label for="" class='HMDPS103PatternSearch Label2 lbl-sky-L'> 対象部署 </label></td>
                    <td class='HMDPS103PatternSearch redioSmall'>
                        <input class='HMDPS103PatternSearch Kyoutuu Enter Tab' type="radio"
                            name="PatternSearch_radio_SYURUI" value="1" tabindex="4" checked="true" />
                        共通
                    </td>
                    <td class='HMDPS103PatternSearch rediominBig'>
                        <input class='HMDPS103PatternSearch Busyo Enter Tab' type="radio"
                            name="PatternSearch_radio_SYURUI" value="2" tabindex="5" />
                        部署指定
                    </td>
                    <td>
                        <input class="HMDPS103PatternSearch BusyoCD Enter Tab" maxLength="3" tabindex="6" />
                    </td>
                    <td>
                        <button class='HMDPS103PatternSearch Kensaku Enter Tab ' tabindex="7">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='HMDPS103PatternSearch BusyoNM  Enter Tab' disabled="true" />
                    </td>
                    <td class="HMDPS103PatternSearch HMS-button-pane HMS-button-set">
                        <button class='HMDPS103PatternSearch Kensaku2 Enter Tab' tabindex="9">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HMDPS103PatternSearch HMS-button-pane">
            <button class="HMDPS103PatternSearch Shinki Enter Tab" tabindex="10">
                新規追加
            </button>
        </div>
        <!-- jqgrid -->
        <div class="HMDPS103PatternSearch jqgridHidden">
            <table class="HMDPS103PatternSearch sprList Enter Tab" id="HMDPS103PatternSearch_sprList"></table>
            <div id="HMDPS103PatternSearch_pager"></div>
        </div>
    </div>