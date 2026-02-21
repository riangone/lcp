<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM201DCImageRelation'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM201DCImageRelation.btn.disabled,
    .PPRM201DCImageRelation.btn[disabled],
    fieldset[disabled] .PPRM201DCImageRelation.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM201DCImageRelation.ipt.disabled,
    .PPRM201DCImageRelation.ipt[disabled],
    fieldset[disabled] .PPRM201DCImageRelation.ipt {
        background-color: #BABEC1 !important
    }
</style>
<div class='PPRM201DCImageRelation' id="PPRM201DCImageRelation" style="width: 1050px">
    <div>
        <fieldset>
            <legend>
                検索条件
            </legend>
            <table>
                <tr style="height:20px;">
                    <td><label for="" class='PPRM201DCImageRelation lblTitle1 lbl-sky-xM' style="width:100px;"> 店舗
                        </label>
                    </td>
                    <td>
                        <input class='PPRM201DCImageRelation ipt txtFromTenpoCD Enter Tab' style="width:120px"
                            maxlength="3">
                    </td>
                    <td>
                        <button class='PPRM201DCImageRelation btn btnFromTenpoSearch Tab'>
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM201DCImageRelation ipt lblFromTenpo' style="width: 170px"
                            disabled="disabled" />
                    </td>
                    <td></td>
                    <td>～</td>
                    <td></td>
                    <td>
                        <input class='PPRM201DCImageRelation ipt txtToTenpoCD Enter Tab' style="width:120px"
                            maxlength="3">
                    </td>
                    <td>
                        <button class='PPRM201DCImageRelation btn btnToTenpoSearch Tab'>
                            検索
                        </button>
                    </td>
                    <td>
                        <input class='PPRM201DCImageRelation ipt lblToTenpo' style="width: 170px" disabled="disabled" />
                    </td>

                </tr>
            </table>
            <table>
                <tr style="height:20px;">
                    <td><label for="" class='PPRM201DCImageRelation lblTitle2 lbl-sky-xM' style="width:100px;"> 日締日
                        </label>
                    </td>
                    <td>
                        <input class='PPRM201DCImageRelation ipt txtHJMFromDate Enter Tab' style="width: 100px"
                            maxlength="10">
                    </td>
                    <td>～</td>
                    <td>
                        <input class='PPRM201DCImageRelation ipt txtHJMToDate Enter Tab' style="width: 100px"
                            maxlength="10">
                    </td>
                    <td style="width: 150px"></td>
                    <td><label for="" class='PPRM201DCImageRelation lblTitle3 lbl-sky-xM' style="width:100px;"> 日締№
                        </label>
                    </td>
                    <td>
                        <input class='PPRM201DCImageRelation ipt txtHJMNo Enter Tab' style="width: 150px"
                            maxlength="12">
                    </td>
                    <td>
                        <button class='PPRM201DCImageRelation btn btnHJMSearch Tab'>
                            検索
                        </button>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr style="height:20px;">
                    <td width="100px"><label for="" class='PPRM201DCImageRelation lblTitle4 lbl-sky-xM'
                            style="width:100px;">
                            ｲﾒｰｼﾞﾌｧｲﾙ </label></td>
                    <td align="left" class='PPRM201DCImageRelation rdbImage'>
                        <input type="radio" value="1" name="rdbImage"
                            class='PPRM201DCImageRelation ipt rdbImage1 Enter Tab'>
                        イメージファイル割当無し
                        <input type="radio" value="2" name="rdbImage"
                            class='PPRM201DCImageRelation ipt rdbImage2 Enter Tab'>
                        イメージファイル割当有り
                        <input type="radio" value="3" name="rdbImage"
                            class='PPRM201DCImageRelation ipt rdbImage3 Enter Tab' checked="checked">
                        指定無し
                    </td>
                    <td style='padding-right:0px;'>
                        <button class='PPRM201DCImageRelation btn btnSearch Tab'>
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <!-- 20170915 lqs UPD S -->
    <!-- <div class="PPRM201DCImageRelation List" style="margin-top:10px;"> -->
    <div class="PPRM201DCImageRelation List" style="margin-top:5px;">
        <!-- 20170915 lqs UPD E -->
        <table>
            <tr>
                <td style="width:670px; vertical-align: top;">
                    <div class="PPRM201DCImageRelation Ruri">
                        <div style="float: left; margin-right: 15px;">
                            <table id="PPRM201DCImageRelation_spdList1"></table>
                        </div>
                        <div style="float: left;">
                            <button class='PPRM201DCImageRelation btn btnopenHijimeOut'
                                style="width: 170px;height:25px;margin-top: 10px;display: block;" tabindex="-1">
                                印刷ﾌﾟﾚﾋﾞｭｰ
                            </button>
                            <button class='PPRM201DCImageRelation btn btnImgFileAdd'
                                style="width: 170px;height:25px;margin-top: 10px;display: block;" tabindex="-1">
                                イメージファイル追加
                            </button>
                            <button class='PPRM201DCImageRelation btn btnopendetails'
                                style="width: 170px;height:25px;margin-top: 10px;display: block;" tabindex="-1">
                                明細表示
                            </button>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <!-- 20170915 lqs UPD S -->
                    <!-- <div class="PPRM201DCImageRelation tblNyuryoku" style="margin-top:10px;display: none"> -->
                    <div class="PPRM201DCImageRelation tblNyuryoku" style="margin-top:3px;display: none">
                        <!-- 20170915 lqs UPD E -->
                        <table>
                            <tr>
                                <td><label for="" class='PPRM201DCImageRelation Label1 lbl-sky-xM' style="width:100px;">
                                        店舗
                                    </label></td>
                                <td>
                                    <input class='PPRM201DCImageRelation ipt lblTenpoCD ' style="width:50px"
                                        disabled="disabled">
                                </td>
                                <td>
                                    <input class='PPRM201DCImageRelation ipt lblTenpo' style="width: 170px"
                                        disabled="disabled" />
                                </td>
                                <td style="width:37px"></td>
                                <td><label for="" class='PPRM201DCImageRelation Label4 lbl-sky-xM' style="width:100px;">
                                        日締№
                                    </label></td>
                                <td>
                                    <input class='PPRM201DCImageRelation ipt lblHJMNo ' style="width: 150px"
                                        disabled="disabled">
                                </td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td><label for="" class='PPRM201DCImageRelation Label6 lbl-sky-xM' style="width:100px;">
                                        日締日時
                                    </label></td>
                                <td>
                                    <input class='PPRM201DCImageRelation ipt lblHJMDate ' style="width:170px"
                                        disabled="disabled">
                                </td>
                            </tr>
                        </table>
                        <table>
                            <!-- 20170915 lqs UPD S -->
                            <!-- <tr >
                            <td style="vertical-align: top;width: 100px"><label for="" class='PPRM201DCImageRelation Label8 lbl-sky-xM' style="width:100px;"> ｲﾒｰｼﾞﾌｧｲﾙ </label></td>
                            <td>
                            <div class="PPRM201DCImageRelation imgPath">
                            </div><div class="PPRM201DCImageRelation treeList" style="width: 300px; height: 130px; overflow-y: scroll;"></div>
                            <img class="PPRM201DCImageRelation imgView body" src="" style="width: 100%;height: 100%" hidden /></td>
                        </tr> -->
                            <tr>
                                <td rowspan="2" style="vertical-align: top;width: 100px;"><label for=""
                                        class='PPRM201DCImageRelation Label8 lbl-sky-xM'
                                        style="width:100px;height:140px;line-height:140px"> ｲﾒｰｼﾞﾌｧｲﾙ </label></td>
                                <td>
                                    <div class="PPRM201DCImageRelation imgPath">
                                </td>
                            </tr>
                            <tr>
                                <!-- 20170919 lqs UPD S -->
                                <!-- <td> -->
                                <td class="PPRM201DCImageRelation imgDialog">
                                    <!-- 20170919 lqs UPD E -->
                                    <div class="PPRM201DCImageRelation treeList"
                                        style="width: 385px; height: 110px; overflow-y: scroll;"></div>
                                    <!-- 20170919 lqs Del S -->
                                    <!-- <img class="PPRM201DCImageRelation imgView body" src="" style="width: 100%;height: 100%" hidden /> -->
                                    <!-- 20170919 lqs Del E -->
                                </td>
                            </tr>
                            <!-- 20170915 lqs UPD E -->
                        </table>
                        <table>
                            <div style="text-align: right;">
                                <button class='PPRM201DCImageRelation btn btnBack Tab'
                                    style="width: 80px;height:25px;margin-top: 3px; ">
                                    戻る
                                </button>
                                <button class='PPRM201DCImageRelation btn btnUpdate Tab'
                                    style="width: 80px;height:25px;margin-top: 3px;">
                                    登録
                                </button>
                                <button class='PPRM201DCImageRelation btn btnCancel Tab'
                                    style="width: 80px;height:25px;margin-top: 3px; margin-right:14px;">
                                    ｷｬﾝｾﾙ
                                </button>
                            </div>
                        </table>
                    </div>
                </td>
                <td style="vertical-align: top;">
                    <div class="PPRM201DCImageRelation tblMeisai" style="display: none">
                        <table id="PPRM201DCImageRelation_spdList2"></table>
                        <!-- 20170915 lqs UPD S -->
                        <!-- <div style="text-align: right;">
                        <button class='PPRM201DCImageRelation btnImgOpenFile  Tab' style="width: 50px;height:25px;margin-top: 10px; ">
                            表示
                        </button>
                        <button class='PPRM201DCImageRelation btnImgDelFile  Tab' style="width: 50px;height:25px;margin-top: 10px;">
                            削除
                        </button>
                    </div> -->
                        <table width="100%">
                            <tr>
                                <td width="50%"><label for="">登録件数：<span
                                            class="PPRM201DCImageRelation detailNum">0</span>件</label></td>
                                <td width="50%">
                                    <div style="text-align: right;">
                                        <button class='PPRM201DCImageRelation btn btnImgOpenFile Tab'
                                            style="width: 50px;height:25px;margin-top: 5px; ">
                                            表示
                                        </button>
                                        <button class='PPRM201DCImageRelation btn btnImgDelFile Tab'
                                            style="width: 50px;height:25px;margin-top: 5px;">
                                            削除
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <!-- 20170915 lqs UPD E -->

                    </div>
                </td>
            </tr>
        </table>

    </div>

    <div id="PPRM201DCImageRelation_dialogs" class="PPRM201DCImageRelation dialogs" style="display: none;"></div>
    <div id="PPRM204_DC_Output_dialog" class="PPRM201DCImageRelation PPRM204_DC_Output_dialog"></div>

</div>