<!-- /**
* 説明：
*
*
* @author lijun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20150526           ---                       新規                           FCSDL
* 20160127           #2373                     依頼                           li
* 20190226           #2870                     依頼                           ci
* 20220121           機能追加　　　　　　          N6対応                         Sun
* 20220218           機能追加　　　　　　      　　　20220212ーN6対応指摘事項(No14)    YIN
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('SDH/SDH02'));
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<!-- ----20220121 sun add s -->
<script type="text/javascript">
    var option_list1m = '<?php echo $option_list1m; ?>';
    var option_list1 = '<?php echo $option_list1; ?>';
    // 20220218 YIN INS S
    var list1m_options = '<?php echo $list1m_options; ?>';
    // 20220218 YIN INS E
</script>
<!-- ----20220121 sun add e -->
<div class="sdh sdh02 dialog" style="width: 100%;height: 100%;">
    <table cellspacing="10" width="99%">
        <!-- 20160127 li ADD S -->
        <tr>
            <td align="right"><label for=""> モード選択： </label></td>
            <td>
                <!-- 20220209 YIN UPD S -->
                <!-- <select name="conditions4" id="selectconditions4" class="sdh sdh02 selectconditions4"> -->
                <select name="conditions4" id="selectconditions4" class="sdh sdh02 selectconditions4"
                    style="width: 150px;">
                    <!-- 20220209 YIN UPD E -->
                    <option value="0">車検代替判定</option>
                    <option value="1">新車１ヶ月点検判定</option>
                    <option value="2">新車６ヶ月点検判定</option>
                    <!-- 20190221 CI ADD S -->
                    <option value="3">中古１ヶ月点検判定</option>
                    <!-- 20190221 CI ADD E -->
                    <!-- 20211224 SUN ADD S -->
                    <option value="4">代替・入庫見込</option>
                    <!-- 20211224 SUN ADD E -->
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <div style="border-bottom: 1px #87cefa solid "></div>
            </td>
        </tr>

        <!-- 20160127 li ADD E -->
        <tr>
            <td align="right"><label for=""> 店舗： </label></td>
            <td>
                <!-- 20220209 YIN UPD S -->
                <!-- <select name="cars" id="busyoData" class="sdh sdh02 sel_busyo" <?php echo $sel_busyo_visible; ?>> -->
                <select name="cars" id="busyoData" class="sdh sdh02 sel_busyo" <?php echo $sel_busyo_visible; ?>
                    style="width: 250px;">
                    <!-- 20220209 YIN UPD E -->
                    <!-- <select name="cars" id="busyoData" class="sdh sdh02 sel_busyo" style="<?php echo $sel_busyo_visible; ?>"> -->
                    <?php
                    echo $busyo_option_list;
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right"><label for=""> 対象年月： </label></td>
            <td>
                <input id="testData" type="text" name="firstname" value="<?php echo $nengetu02; ?>" maxlength="6"
                    style="font-size: 12pt" class="sdh sdh02 input_data value" />
            </td>
        </tr>
        <tr>
            <td align="right"><label for=""> リスト種類： </label></td>
            <td>
                <!-- 20220209 YIN UPD S -->
                <!-- <select name="cars" id="selectData" class="sdh sdh02 sel_user"> -->
                <select name="cars" id="selectData" class="sdh sdh02 sel_user" style="width: 300px;">
                    <!-- 20220209 YIN UPD E -->
                    <?php
                    echo $user_option_list;
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right"><label for=""> 要注意リスト抽出用： </label></td>
            <td>
                <!-- 20220209 YIN UPD S -->
                <!-- <select name="conditions" id="selectconditions" class="sdh sdh02 selectconditions"> -->
                <select name="conditions" id="selectconditions" class="sdh sdh02 selectconditions"
                    style="width: 200px;">
                    <!-- 20220209 YIN UPD E -->
                    <option value="0">指定なし</option>
                    <option value="1">担当者が変更されている</option>
                    <option value="2">担当者が異動している</option>
                    <option value="3">担当者が退職している</option>
                </select>
            </td>
        </tr>

        <tr>
            <td align="right"><label for=""> 活動状況： </label></td>
            <td>
                <!-- ----20220121 sun add s -->
                <select name="conditions1m" id="selectconditions1m" class="sdh sdh02 selectconditions1m"
                    multiple="multiple" style="width: 300px;">
                    <?php
                    echo $option_list1;
                    ?>
                </select>
                <!-- ----20220121 sun add e -->
                <!-- 20220209 YIN DEL S -->
                <!-- <select name="conditions1" id="selectconditions1" class="sdh sdh02 selectconditions1" style="display: none">
                <?php
                echo $option_list1;
                ?>
            </select> -->
                <!-- 20220209 YIN DEL E -->
            </td>
        </tr>
        <tr>
            <td align="right"><label for=""> 最終結果： </label></td>
            <td>
                <!-- 20220209 YIN UPD S -->
                <!-- <select name="conditions2" id="selectconditions2" class="sdh sdh02 selectconditions2"> -->
                <select name="conditions2" id="selectconditions2" class="sdh sdh02 selectconditions2"
                    style="width: 300px">
                    <!-- 20220209 YIN UPD E -->
                    <?php
                    echo $option_list2;
                    ?>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <div style="border-bottom: 1px #87cefa solid "></div>
            </td>
        </tr>

        <tr>
            <td align="right"><label for=""> 並び順指定： </label></td>
            <td>
                <!-- 20220209 YIN UPD S -->
                <!-- <select name="conditions3" id="selectconditions3" class="sdh sdh02 selectconditions3"> -->
                <select name="conditions3" id="selectconditions3" class="sdh sdh02 selectconditions3"
                    style="width: 130px">
                    <!-- 20220209 YIN UPD E -->
                    <option value="0">車両区分順</option>
                    <option value="1">活動状況順</option>
                    <option value="2">最終結果順</option>
                </select>
            </td>
        </tr>

    </table>

</div>