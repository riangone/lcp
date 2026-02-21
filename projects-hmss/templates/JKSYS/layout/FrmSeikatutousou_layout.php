<!--
/**
* 説明：
*
*
* @author FCS
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* --------------------------------------------------------------------------------------------
*/
-->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSeikatutousou/FrmSeikatutousou"));
?>
<style type="text/css">
    .FrmSeikatutousou.DateTimePicker1 {
        width: 100px
    }

    .FrmSeikatutousou.set-inline {
        display: inline;
    }

    .FrmSeikatutousou.set-inline div:nth-child(2) {
        margin-left: 10px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmSeikatutousou">
    <div class='FrmSeikatutousou JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmSeikatutousou Label18 lbl-sky-L' for=""> 対象年月日 </label>
            <input class='FrmSeikatutousou DateTimePicker1 Tab Enter' maxlength="4" tabindex="1" />
            <label for="">年 12月 31日時点のデータで作成する</label>
        </div>
        <div>
            <label class='FrmSeikatutousou Label1 lbl-sky-L' for=""> 出力帳票 </label>
            <div class="FrmSeikatutousou chkNo set-inline">
                <div class="FrmSeikatutousou set-inline">
                    <input type="checkbox" value="" name="chkNo1" class='FrmSeikatutousou chkNo1 Tab Enter'
                        checked="checked" tabindex="2" />
                    調査票№１
                </div>
                <div class="FrmSeikatutousou set-inline">
                    <input type="checkbox" value="" name="chkNo2" class='FrmSeikatutousou chkNo2 Tab Enter'
                        checked="checked" tabindex="3" />
                    調査票№２
                </div>
            </div>
        </div>
        <div class="FrmSeikatutousou HMS-button-pane">
            <div class='FrmSeikatutousou HMS-button-set'>
                <button class="FrmSeikatutousou cmdExcel Enter Tab" tabindex="4">
                    Excel出力
                </button>
            </div>
        </div>
    </div>
</div>
