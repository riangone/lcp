﻿<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM204DCOutput'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM204DCOutput.btn.disabled,
    .PPRM204DCOutput.btn[disabled],
    fieldset[disabled] .PPRM204DCOutput.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM204DCOutput.ipt.disabled,
    .PPRM204DCOutput.ipt[disabled],
    fieldset[disabled] .PPRM204DCOutput.ipt {
        background-color: #BABEC1 !important
    }
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .PPRM204DCOutput .temp {
            height: 295px !important;
        }
    }
</style>
<div class='PPRM204DCOutput body' id="PPRM204DCOutput" style="width: 90%">
    <div>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <tr style="height:28px;" class="PPRM204DCOutput tr1">
                    <td><label class='PPRM204DCOutput lblTarget lbl-sky-xM' style="width:100px;"> 対象 </label></td>
                    <td>
                        <input type="radio" value="" class='PPRM204DCOutput ipt radJimu  Tab' name="object"
                            checked="checked">
                        事務
                    </td>
                    <td>
                        <input type="radio" value="" class='PPRM204DCOutput ipt radSeibi  Tab' name="object">
                        整備
                    </td>
                    <td></td>
                </tr>
            </table>
            <div class="PPRM204DCOutput pnlJimu">
                <table>
                    <tr style="height:28px;">
                        <td><label class='PPRM204DCOutput lblJHjmNO lbl-sky-xM' style="width:100px;"> 日締№ </label></td>
                        <td>
                            <input class='PPRM204DCOutput ipt txtJHjmNO  Tab' style="width: 120px" maxlength="12">
                        </td>
                        <td>
                            <button class='PPRM204DCOutput btn btnHjmSearch  Tab'>
                                検索
                            </button>
                        </td>
                        <td><label class='PPRM204DCOutput lblJTenpo lbl-sky-xM' style="width:100px;"> 店舗 </label></td>
                        <td>
                            <input class='PPRM204DCOutput ipt lblJTenpoNM  Tab' style="width: 120px"
                                disabled="disabled">
                        </td>
                        <td><label class='PPRM204DCOutput lblJHjmDTLabel lbl-sky-xM' style="width:100px;"> 日締日時 </label>
                        </td>
                        <td>
                            <input class='PPRM204DCOutput ipt lblJHjmDT' style="width: 150px" disabled="disabled" />
                        </td>
                        <td></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td rowspan="3"><label class='PPRM204DCOutput lblJPrintPdf lbl-sky-xM'
                                style="width: 100px;height:60px;line-height:60px;"> 出力帳票 </label></td>
                        <td>
                            <input type="radio" value="" class='PPRM204DCOutput ipt radJPrintAll  Tab' name="object1"
                                checked="checked">
                            全て
                            <input type="radio" value="" class='PPRM204DCOutput ipt radJPrintSelect  Tab'
                                name="object1">
                            限定
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkSuitoEigKsy  Tab' />
                            現金出納帳（営業）金種表
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkSuitoEig  Tab' />
                            現金出納帳（営業）
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkCardMei  Tab' />
                            カード伝票明細一覧表
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkShiireMei  Tab' />
                            仕入伝票明細一覧表
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkFurikaeMei  Tab' />
                            振替伝票明細一覧表
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkSonotaMei  Tab' />
                            その他伝票明細一覧表
                        </td>
                    </tr>
                </table>
            </div>
            <div class="PPRM204DCOutput pnlSeibi" style="display: none;">
                <div class="PPRM204DCOutput blockDate">
                    <table>
                        <tr style="height:28px;">
                            <td><label class='PPRM204DCOutput lblSTenpo lbl-sky-xM' style="width: 100px;"> 店舗 </label>
                            </td>
                            <td>
                                <input class='PPRM204DCOutput ipt txtSTenpoCD  Tab' style="width: 120px" maxlength="3">
                            </td>
                            <td>
                                <button class='PPRM204DCOutput btn btnTenpoSearch  Tab'>
                                    検索
                                </button>
                            </td>
                            <td>
                                <input class='PPRM204DCOutput ipt lblSTenpoNM  Tab' style="width: 120px"
                                    disabled="disabled">
                            </td>
                            <td><label class='PPRM204DCOutput lblSUriageDate lbl-sky-xM' style="width:100px;"> 売上日
                                </label></td>
                            <td>
                                <input class='PPRM204DCOutput ipt txtSUriageDate  Tab' style="width: 150px"
                                    maxlength="10">
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <table>
                    <tr>
                        <td rowspan="2"><label class='PPRM204DCOutput lblSPrintPdf lbl-sky-xM'
                                style="height:60px;line-height:60px;width: 100px;"> 出力帳票 </label></td>
                        <td>
                            <input type="radio" value="" class='PPRM204DCOutput ipt radSPrintAll  Tab' name="object2"
                                checked="checked">
                            全て
                            <input type="radio" value="" class='PPRM204DCOutput ipt radSPrintSelect  Tab'
                                name="object2">
                            限定
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkSeibiNik  Tab' />
                            整備日報（日計）
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkSeibiGek  Tab' />
                            整備日報（月計）
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkUriMei  Tab' />
                            売上明細一覧表
                            <input type="checkbox" value="" class='PPRM204DCOutput ipt chkGaichu  Tab' />
                            外注検収一覧表
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </div>

    <div class="PPRM204DCOutput PPRM202"></div>
    <div class="PPRM204DCOutput PPRM705"></div>

    <table>
        <tr style="height:25px;">
            <td></td>
            <td style='padding-left:800px;'>
                <button class='PPRM204DCOutput btn btnPdf  Tab' style="width: 100px;height:25px;">
                    プレビュー
                </button>
            </td>
            <td>
                <button class='PPRM204DCOutput btn btnClose  Tab' style="width: 100px;height:25px;">
                    閉じる
                </button>
            </td>
        </tr>
    </table>
    <iframe class="PPRM204DCOutput temp" src="" style="width:100%;height:385px"></iframe>
</div>
