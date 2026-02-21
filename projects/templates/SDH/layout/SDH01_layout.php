<!-- /**
* 説明：
*
*
* @author zhenghuiyun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20150609           ---                       判定文コピー機能追加           HM
 * 20150611           ---                       mcdropdownのCSS名変更          HM
 * 20220121           機能追加　　　　　　          N6対応                         Sun
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
//20150610 Update Start
//echo $this -> Html -> css(array("jquery/jquery.mcdropdown"));
echo $this->Html->css(array("jquery/jquery.mcdropdown"));
//20150610 Update End
echo $this->Html->script(array('jquery/jquery.mcdropdown'));
echo $this->Html->script(array('jquery/jquery.bgiframe'));

echo $this->Html->script(array('jquery/jquery.exresize'));
echo $this->Html->script(array('jquery/jquery.exdate'));

echo $this->Html->script(array("common/d3.v3.min"));
echo $this->Html->script(array("common/d3.tip"));
echo $this->Html->css(array("timeline/timeline"));
//----20220121 sun add s
echo $this->Html->css(array("SDH/select2.min"));
echo $this->Html->script(array('common/select2.min'));
//----20220121 sun add e
echo $this->Html->css(array("SDH/SDH"));
//fan update s.
echo $this->Html->script(array("SDH/timeline"));
//fan update e.
echo $this->Html->script(array('SDH/SDH01'));

// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<style></style>

<div class="sdh sdh01 all">
    <div class="sdh sdh01 title">
        <table style="width:100%;height: 100%">
            <tr style="width:100%;height: 100%">
                <td>
                    <div class="sdh sdh01 current_all lbl_count">
                        <span class="sdh sdh01 current_all lbl_count page_no"
                            style="color:#FF0000;vertical-align: top;line-height: 27px;"></span>
                        <span style="vertical-align: top;line-height: 27px">/</span>
                        <span class="sdh sdh01 current_all lbl_count total"
                            style="vertical-align: top;line-height: 27px"></span>
                        <span class="sdh sdh01 current_all lbl_count ken">件</span>
                    </div>
                </td>
                <td>
                    <div>
                        <select class="sdh sdh01 sel_tenpo ListSelect"></select>
                    </div>
                </td>
                <td>
                    <div>
                        <select class="sdh sdh01 sel_nengetu ListSelect">
                    </div>
                </td>
                <td style="width: 100%"></td>
                <td style="width: 120px">
                    <button class="sdh sdh01 btn_sdh_02 OprationButton" title="検索条件変更画面" style="width:100px">
                        検索条件
                    </button>
                </td>
                <td style="width: 120px">
                    <button class="sdh sdh01 btn_sdh_05 OprationButton " title="集計情報を表示" style="width:100px">
                        集計
                    </button>
                </td>
                <!--  20150820  Yuanjh  ADD S.-->
                <td style="width: 120px">
                    <button class="sdh sdh01 btn_sdh_06 OprationButton " title="車種別集計情報を表示" style="width:100px">
                        車種別集計
                    </button>
                </td>
                <!--  20150820  Yuanjh  ADD E.-->
                <td style="width: 120px">
                    <button class="sdh sdh01 btn_sdh_03 OprationButton " title="注文書情報を表示" style="width:100px">
                        注文書
                    </button>
                </td>
                <td style="width: 120px" title="保険・クレジット情報を表示">
                    <button class="sdh sdh01 btn_sdh_04 OprationButton " style="width:100px">
                        保険・クレ
                    </button>
                </td>
                <!-- ----20220121 sun add s -->
                <td style="width: 120px" title="進捗確認">
                    <button class="sdh sdh01 btn_sinchoku OprationButton " style="width:100px;display: none">
                        進捗確認
                    </button>
                </td>
                <!-- ----20220121 sun add e -->
                <td style="width: 120px" title="判定結果を保存">
                    <button class="sdh sdh01 btn_save OprationButton " style="width:100px">
                        保存
                    </button>
                </td>
                <td style="width: 120px" title="前の車両に戻る">
                    <button class="sdh sdh01 btn_prev_syaryou OprationButton " style="width:100px">
                        前の車両
                    </button>
                </td>
                <td style="width: 120px" title="次の車両に進む">
                    <button class="sdh sdh01 btn_next_syaryou OprationButton " style="width:100px">
                        次の車両
                    </button>
                </td>
                <!--20160322 Upd S-->
                <!--
                <td style="width: 120px" title = "保存せず画面を更新">
                <button class="sdh sdh01 btn_reload OprationButton " style="width:100px">
                    画面更新
                </button></td>
-->
                <td style="width: 40px">
                    <button class="sdh sdh01 btn_reload OprationButton " style="width:30px"></button>
                </td>
                <!--
<SCRIPT language="JavaScript">
function popup_modeless(url){
    var newWin = window.open(
        url,    //移動先
        "pop",  //ターゲット名（aタグのtargetと同様）
        "width=700, height=400,top=300,left=700,toolbar=no,location=no,status=no,rezizable=yes,scrollbars=yes"
    );
    newWin.focus();
}
</SCRIPT>
                <td style="width: 40px">
                <button class="sdh sdh01 btn_help OprationButton " style="width:30px"onClick="popup_modeless('files/sdh_help.html')"></button>
                </td>
-->
                <td style="width: 40px">
                    <button class="sdh sdh01 btn_help OprationButton " style="width:30px"></button>
                </td>

                <!--20160322 Upd E-->

            </tr>
        </table>
    </div>

    <div class="sdh sdh01 content" style="width: 100%;height: 100%">
        <div class="sdh sdh01 content left panel">
            <div class="sdh sdh01 content left hantei_list" style="background-color: white">

            </div>
            <div title="リスト表示/非表示" class="sdh sdh01 content left hantei_list_showhide" style="background-color:#C5F3DD">
                <span class="ui-icon ui-icon-circle-triangle-w sdh sdh01 listshow" style="margin-left: 2px"></span>
                <span class="ui-icon ui-icon-newwin sdh sdh01 listpopup" style="margin-left: 2px"></span>
            </div>
        </div>

        <div class="sdh sdh01 content center">
            <table style="width: 100%;height: 100%" cellspacing="0" cellpadding="0" border="1">
                <tr style="width: 100%">
                    <td style="width: 100%">
                        <div style="width: 100%">
                            <table style="width:100%;height: 100%" border="1">
                                <tr style="width:100%;height: 100%">
                                    <?php
                                    for ($i = 1; $i < 8; $i++) {
                                        echo "<td style=\"width: 11.1%\">";
                                        echo $this->element(
                                            'SDH01_01_layout',
                                            array(
                                                "hantei_class" => "sdh sdh01 hantei_0" . $i,
                                                //---20150609 fanzhengzhou add s.#1911-2
                                                "hanteinengetu_btn_class" => "sdh sdh01 hanteinengetu_btn_0" . $i,
                                                //---20150609 fanzhengzhou add e.#1911-2
                                                "hanteinengetu_class" => "sdh sdh01 hanteinengetu_0" . $i,
											// @formatter:off
											"hanteinengetu" => ${"hanteinengetu_0" . $i},
                                                // @formatter:on
                                                //20160304 YIN INS S
                                                "result_class" => "sdh sdh01 result_0" . $i,
                                                //20160304 YIN INS E
                                                "result_select_class" => "sdh sdh01 result_select_0" . $i,
                                                "result_menu_class" => "sdh sdh01 result_menu_0" . $i . " mcdropdown_menu ",
                                                "result_text_class" => "sdh sdh01 result_text_0" . $i,
											// @formatter:off
											"result_text" => ${"result_text_0" . $i},
                                                // @formatter:on
                                                "rireki_class" => "sdh sdh01 btn_rireki_0" . $i . " tooltip yellow-tooltip",
                                            )
                                        );
                                        echo "</td>";
                                    }
                                    ?>
                                    <td style="width: 11.1%"><?php echo $this->element('SDH01_06_layout'); ?></td>
                                    <td style="width: 11.1%"><?php echo $this->element('SDH01_07_layout'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr style="width: 100%;height: 100%;margin-top: 80px">
                    <td style="width: 100%;height: 100%">
                        <div style="width: 100%;height: 100%">
                            <!-- /**fanzhengzhou 20150318 add tableid</-->
                            <table id="centerTable" style="width: 100%;height: 100%;border-collapse: collapse"
                                cellpadding="0" cellspacing="0">
                                <tr style="width: 100%;height: 100%">
                                    <td style="width: 80%;height: 100%">

                                        <table style="width: 100%;height: 100%" cellspacing="1">
                                            <tr style="width: 100%">
                                                <td style="width: 100%">
                                                    <?php echo $this->element('SDH01_02_layout'); ?>
                                                </td>
                                            </tr>
                                            <tr style="width: 100%;height: 100%">
                                                <td style="width: 100%;height:100%;">
                                                    <?php echo $this->element('SDH01_05_layout'); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="width: 20%;height: 100%">

                                        <!-- 20171129 lqs UPD S -->
                                        <!-- <table style="width: 100%;height: 100%" cellspacing="1"> -->
                                        <table class="sdh sdh01 rightpart" style="width: 100%;height: 100%"
                                            cellspacing="1">
                                            <!-- 20171129 lqs UPD E -->
                                            <tr style="width: 100%;height: 20%">
                                                <td style="width: 100%">
                                                    <?php echo $this->element('SDH01_03_layout'); ?>
                                                </td>
                                            </tr>
                                            <tr style="width: 100%;height: 80%">
                                                <td style="width: 100%">
                                                    <?php echo $this->element('SDH01_04_layout'); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="sdh sdh01 dialog_area"></div>
        <div class="sdh sdh01 accumulation_dialog_area" style="visibility:hidden">
            <div>
                <?php echo $this->element('SDH01_09_layout'); ?>
            </div>
        </div>
        <div class="sdh sdh01 syasyu_accumulation_dialog_area" style="visibility:hidden">
            <div>
                <?php echo $this->element('SDH01_10_layout'); ?>
            </div>
        </div>
        <div class="sdh sdh01 help_area" style="visibility:hidden">
            <div>
                <?php echo $this->element('SDH01_11_layout'); ?>
            </div>
        </div>


    </div>
</div>