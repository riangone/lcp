<!-- /**
* 説明：
*
*
* @author FCSDL
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* ----------------------------------------------------------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20201117                  bug                     DIVのHeightが間違っています            WANGYING
* ----------------------------------------------------------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyainMstEdit/FrmSyainMstEdit"));
?>

<div class='FrmSyainMstEdit' id="FrmSyainMstList">
    <div class='FrmSyainMstEdit  R4-content'>
        <div style='width:100%;float:left'>
            <label
                style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:120px;;'
                for="">
                社員No. </label>
            <input type='text' class='FrmSyainMstEdit txtSyainNO Tab Enter' style='width:50px;float:left'
                maxlength="5" />
        </div>
        <div style='width:100%;float:left'>
            <label
                style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:120px;;'
                for="">
                社員名 </label>
            <input type='text' class='FrmSyainMstEdit txtSyainNM Tab Enter' style='float:left' maxlength="20" />
        </div>
        <div style='width:100%;float:left;'>
            <label
                style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:120px;;'
                for="">
                社員名カナ </label>
            <input type='text' class='FrmSyainMstEdit txtSyainKN Tab Enter' style='width:400px;float:left'
                maxlength="40" />
        </div>

        <div style='width:100%;float:left'>
            <label
                style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:120px;;'
                for="">
                資格コード </label>
            <input type='text' class='FrmSyainMstEdit txtSikakuCD Tab Enter' style='width:20px;float:left'
                maxlength="2" />
        </div>
        <div style='width:100%;float:left'>
            <label
                style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:120px;;'
                for="">
                営業スタッフ区分 </label>
            <input type='text' class='FrmSyainMstEdit txtKBN Tab Enter' style='width:20px;float:left' maxlength="2" />
            <label for=""> 1:営業スタッフ　3：管理者　9：その他 </label>
        </div>
        <div style='width:100%;float:left'>
            <label
                style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:120px;;'
                for="">
                退職日 </label>
            <input type='checkbox' class='FrmSyainMstEdit chkTaisyokuYMD Tab Enter' style='float:left' />
            <input type='text' class='FrmSyainMstEdit cboTaisyokuYMD Tab Enter' style='width:120px;float:left'
                maxlength="10" />
            <input type='text ' class='FrmSyainMstEdit txtCreateDate'
                style='width:200px;float:left;visibility: hidden;display:none' />
        </div>
        <!-- 20150810 li UPD S -->
        <!-- <label style='width:100%;float:left;margin-top:8px;'>
        固定費カバー率用項目説明
        </label>
        <fieldset style='width:300px;height:70px;float:left;margin-top:5px;'>
        <legend>
        【表示区分】
        </legend>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;'>
        1：新車ﾗﾝｷﾝｸﾞ表に表示
        </label>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;margin-left:20px;'>
        9　：対象外・退職者
        </label>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;'>
        2：中古ﾗﾝｷﾝｸﾞ表に表示
        </label>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;margin-left:20px;'>
        空白：対象外
        </label>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;'>
        3：管理者
        </label>
        </fieldset>
        <fieldset style='width:210px;height:70px;float:left;margin-left:15px;margin-top:5px;margin-bottom: 5px;'>
        <legend>
        【台数表示区分】
        </legend>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;'>
        売上台数ﾗﾝｷﾝｸﾞ表に
        </label>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;'>
        1　：非表示
        </label>
        <label style='width:140px;float:left;font-size:8px;margin-bottom:5px;'>
        空白：表示
        </label>
        </fieldset>-->

        <label style='width:100%;float:left;margin-top:3px;' for=""> 固定費カバー率用項目説明 </label>

        <!-- 20201117 wangying upd S -->
        <!-- <fieldset style="width:300px;height:65px;float:left;margin-top:1px;"> -->
        <fieldset style="width:300px;height:auto;float:left;margin-top:1px;">
            <!-- 20201117 wangying upd E -->
            <legend>
                【表示区分】
            </legend>
            <div style="width:50%; float:left">
                <label style="float:left;margin-bottom:1px;" for=""> 1：新車ﾗﾝｷﾝｸﾞ表に表示 </label>
                <label style="float:left;margin-bottom:1px;" for=""> 2：中古ﾗﾝｷﾝｸﾞ表に表示 </label>
                <label style="float:left;margin-bottom:1px;" for=""> 3：管理者 </label>
            </div>
            <div style="width:50%;float:right">
                <label style="float:left;margin-bottom:1px;margin-left:10px;" for=""> 9　：対象外・退職者
                </label>
                <label style="float:left;margin-bottom:1px;margin-left:10px;" for=""> 空白：対象外
                </label>
            </div>
        </fieldset>
        <!-- 20201117 wangying upd S -->
        <!-- <fieldset style="width:220px;height:65px;float:left;margin-top:1px;"> -->
        <fieldset style="width:220px;height:auto;float:left;margin-top:1px;">
            <!-- 20201117 wangying upd E -->
            <legend>
                【台数表示区分】
            </legend>
            <div style="width:100%;">
                <label style='float:left;margin-bottom:1px;width:100%;' for=""> 売上台数ﾗﾝｷﾝｸﾞ表に
                </label>
                <label style='float:left;margin-bottom:1px;width:100%;' for=""> 1　：非表示 </label>
                <label style='float:left;margin-bottom:1px;width:100%;' for=""> 空白：表示 </label>
            </div>
        </fieldset>
        <!-- 20150810 li UPD E -->

        <table id='FrmSyainMstEdit_sprMeisai'>

        </table>
        <!-- 20150810 li DEL S -->
        <!-- <div id='FrmSyainMstEdit_pager'>

        </div> -->
        <!-- 20150810 li DEL E -->
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmSyainMstEdit cmdAction Tab Enter'>
                    更新
                </button>
                <button class='FrmSyainMstEdit cmdBack Tab Enter'>
                    戻る
                </button>
            </div>
        </div>
    </div>
</div>