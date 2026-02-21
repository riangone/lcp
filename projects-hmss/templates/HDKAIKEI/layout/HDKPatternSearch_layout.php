<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HDKAIKEI/HDKPatternSearch/HDKPatternSearch"));
?>
<style type="text/css">
    .HDKPatternSearch.BusyoCD {
        width: 135px;
    }

    .HDKPatternSearch.txtPatternName {
        width: 280px;
    }

    .HDKPatternSearch.textmin {
        min-width: 400px;
    }

    .HDKPatternSearch.jqgridHidden .ui-jqgrid tr.jqgrow td {
        word-wrap: break-word;
        white-space: normal !important;
    }

    .HDKPatternSearch.rediominBig {
        min-width: 90px;
    }

    .HDKPatternSearch.redioSmall {
        min-width: 70px;
    }

    .HDKPatternSearch fieldset {
        padding: 0px 5px 0px;
    }

    .HDKPatternSearch.HMS-button-pane {
        min-height: 15px;
        margin: 1px;
    }

    .HDKPatternSearch.Kensaku {
        min-width: 60px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .HDKPatternSearch.textmin {
            min-width: 381px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='HDKPatternSearch body'>
    <div class='HDKPatternSearch HDKAIKEI-content'>

        <fieldset>
            <legend class="HDKPatternSearch lblSearchTitle">
                <b><span>検索条件</span></b>
            </legend>
            <table>
                <tr class="HDKPatternSearch HMS-button-pane">
                    <td><label for="" class='HDKPatternSearch Label4 lbl-sky-L'> 伝票種類 </label></td>
                    <td class='HDKPatternSearch redioSmall'>
                        <input class='HDKPatternSearch Subete Enter Tab' type="radio"
                            name="HDKPatternSearch_radio_DENPYO" value="3" tabindex="1" checked="true" />
                        全て
                    </td>
                    <td class='HDKPatternSearch rediominBig'>
                        <input class='HDKPatternSearch Shiwake Enter Tab' type="radio"
                            name="HDKPatternSearch_radio_DENPYO" value="1" tabindex="2" />
                        仕訳伝票
                    </td>
                    <td colspan="3" class='HDKPatternSearch rediominBig'>
                        <input class='HDKPatternSearch Shiharai Enter Tab' type="radio"
                            name="HDKPatternSearch_radio_DENPYO" value="2" tabindex="3" />
                        支払伝票
                    </td>
                    <td class='HDKPatternSearch textmin'><label for="" class="HDKPatternSearch lbl-sky-L"> パターン名
                        </label>
                        <input type="text" class="HDKPatternSearch txtPatternName Enter Tab" tabindex="8" />
                    </td>
                </tr>
                <tr class="HDKPatternSearch">
                    <td><label for="" class='HDKPatternSearch Label2 lbl-sky-L'> 対象部署 </label></td>
                    <td class='HDKPatternSearch redioSmall'>
                        <input class='HDKPatternSearch Kyoutuu Enter Tab' type="radio"
                            name="HDKPatternSearch_radio_SYURUI" value="1" tabindex="4" checked="true" />
                        共通
                    </td>
                    <td class='HDKPatternSearch rediominBig'>
                        <input class='HDKPatternSearch Busyo Enter Tab' type="radio"
                            name="HDKPatternSearch_radio_SYURUI" value="2" tabindex="5" />
                        部署指定
                    </td>
                    <td>
                        <input class="HDKPatternSearch BusyoCD Enter Tab" maxLength="16" tabindex="6" />
                    </td>
                    <td>
                        <button class='HDKPatternSearch Kensaku Enter Tab ' tabindex="7">
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='HDKPatternSearch BusyoNM  Enter Tab' disabled="true" />
                    </td>
                    <td class="HDKPatternSearch HMS-button-pane HMS-button-set">
                        <button class='HDKPatternSearch Kensaku2 Enter Tab' tabindex="9">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="HDKPatternSearch HMS-button-pane">
            <button class="HDKPatternSearch Shinki Enter Tab" tabindex="10">
                新規追加
            </button>
        </div>
        <!-- jqgrid -->
        <div class="HDKPatternSearch jqgridHidden">
            <table class="HDKPatternSearch sprList Enter Tab" id="HDKPatternSearch_sprList"></table>
            <div id="HDKPatternSearch_pager"></div>
        </div>
    </div>