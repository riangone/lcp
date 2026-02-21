<title><?php // echo $title_for_layout ?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<?php
/**
 * @var Cake\View\View $this
 */
// 固定情報表示

echo $this->Html->script(array('R4/R4G/FrmFDCreate/FrmFDCreate'));
echo $this->Html->css(array('R4/R4G/FrmFDCreate/FrmFDCreate'));
// IE用JSONオブジェクト設定
// 可変情報表示
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class='FrmFDCreate'>
    <div class='FrmFDCreate R4-content'>
        <fieldset class="FrmFDCreate center GroupBox1 " style='width: 800px;height:100px;'>
            <legend>
                <b><span class='FrmFDCreate_GroupBox1_searchTitle_css'>検索条件</span></b>
            </legend>
            <div class='FrmFDCreate search rmFDCreate_searchArea_css'>
                <table width=100% border=0 cellspacing="0" class='FrmFDCreate search_table'>
                    <tr>
                        <td>
                            <div class='FrmFDCreate_search_touroku_div_css '>
                                <label class='FrmFDCreate search_touroku_text ' for="">登録予定日</label>
                                <input type='text' style='margin-left:90px;'
                                    class='FrmFDCreate search_touroku_inputText FrmFDCreate_search_touroku_inputText_css Enter Tab'
                                    name="FrmFDCreate_search_touroku_inputText" />
                            </div>
                        </td>
                        <td align=left>

                        </td>
                    </tr>
                    <tr>
                        <td width=50%>
                            <table width=100% border=0 cellspacing="0">
                                <tr>
                                    <td>
                                        <label class='FrmFDCreate search_misakusei_text ' for="">FD未作成データのみ抽出</label>
                                        <input type='checkbox' class='FrmFDCreate search_misakusei_inputCheck Enter Tab'
                                            name="FrmFDCreate_search_misakusei_text" />
                                    </td>
                                    <td align='right'>
                                        <button class='FrmFDCreate button_search Enter Tab'>
                                            検索
                                        </button>
                                    </td>
                                </tr>
                            </table>


                        </td>
                        <td align='left'>

                        </td>
                    </tr>

                </table>
            </div>
        </fieldset>
        <div class='FrmFDCreate listArea' style='margin-top:15px;'>
            <table cellspacing="0" width=100% id='FrmFDCreate_sprList'>
            </table>
            <div id='divFrmFDCreate_pager'></div>
        </div>

        <!--<div class='FrmFDCreate_footer_css' >-->
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmFDCreate button_action Enter Tab'>
                    作成
                </button>
            </div>
        </div>
        <!--</div>-->
    </div>
</div>
