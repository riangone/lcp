<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示
echo $this->Html->script(array('CkChkzaiko/CkChkzaiko'));
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<div class="CkChkzaiko" style="padding-left: 30px">
    <div class="CkChkzaiko CkChkzaiko-content">
        <table>
            <tr>
                <td>
                    &nbsp;
                    <!--<label>
                キーワード
                </label>
                -->
                </td>
                <td>
                    <!--<input type="text" class="CkChkzaiko txt_ck_search_key">-->
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="checkbox" class="CkChkzaiko chk_ck_showPrinted" />
                    印刷済みデータを表示
                </td>
                <td>
                    日付絞込み
                </td>
                <td>
                    <input type="text" class="CkChkzaiko txt_ck_datepickerFrom">
                    <!--disabled="disabled" style="background-color: #E5E5E5"-->
                    から
                    <input type="text" class="CkChkzaiko txt_ck_datepickerTo">
                    <!--disabled="disabled" style="background-color: #E5E5E5"-->
                    まで
                </td>
            </tr>
        </table>
    </div>
    <div style="width:100% ; height: 1px;background-color: #A6C9E2;margin-top: 4px;margin-bottom: 4px">
    </div>
    <div class="CkChkzaiko div_ck_Table">
        <button id="btn_ck_print_top" class="CkChkzaiko comment_demo">
            詳細表示(F1)
        </button>
        <!--<button id="btn_ck_printSVG_top" class="CkChkzaiko comment_demo_SVG">
        詳細表示SVG(F2)
        </button>
        -->
        <br />
        <table id="CkChkzaiko_sprList">
        </table>
        <div id="CkChkzaiko_pager">
        </div>
        <button id="btn_ck_print_bottom" class="CkChkzaiko comment_demo">
            詳細表示(F1)
        </button>
    </div>
    <div class="CkChkzaiko div_ck_PrintDialog_PDF">
        <div class="CkChkzaiko div_ck_Printview_PDF" Align="center">
            <div class="CkChkzaiko div_ck_PrintArea_PDF">
            </div>
        </div>
    </div>
</div>
