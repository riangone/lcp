<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmSyainMstList/FrmSyainMstList"));
?>
<style type="text/css">
    .FrmSyainMstList .ui-jqgrid .ui-jqgrid-pager .ui-paging-pager,
    .ui-jqgrid .ui-jqgrid-toppager .ui-paging-pager {
        height: 0% !important;
    }

    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmSyainMstList .cmd_SearchBs {
            height: 18px !important;
            line-height: 15px !important;
        }
    }
</style>
<div class='FrmSyainMstList' id="FrmSyainMstList">
    <div class='FrmSyainMstList  R4-content'>
        <div style="">
            <fieldset>
                <legend>
                    検索条件
                </legend>
                <div style='width:100%'>
                    <div class='FrmSyainMstList group1' style='float:left;width:100%'>
                        <label
                            style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:100px;;'
                            for="">
                            部署コード
                        </label>
                        <input type='text' class='FrmSyainMstList txtBusyoCD Enter Tab' style='width:30px;float:left;'
                            maxlength="3" tabindex="1" />
                        <button class='FrmSyainMstList cmd_SearchBs '
                            style="min-width:20px;height:21px;float:left;line-height:18px;">
                            検索
                        </button>
                        <label class='FrmSyainMstList lblBusyoNM'
                            style='width:380px;height:20px;; border:inset 1px;float:left;background-color:#C7C7C7'
                            for="">

                        </label>
                    </div>
                    <div class='FrmSyainMstList group2' style='float:left;width:100%'>
                        <label
                            style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:100px;'
                            for="">
                            社員No.
                        </label>
                        <input type='text' class='FrmSyainMstList txtSyainNO Enter Tab' maxlength="5"
                            style='width:50px;float:left ' tabindex="2" />
                    </div>
                    <div class='FrmSyainMstList group3' style='float:left;width:100%'>
                        <label
                            style='float:left;background-color:#87CEFF;border:solid 1px;padding:1px,1px,1px,1px;margin-top:3px;margin-right:5px;width:100px;'
                            for="">
                            社員名カナ
                        </label>
                        <input type='text' class='FrmSyainMstList txtSyainNM Enter Tab' style='width:500px;float:left'
                            tabindex="3" maxlength="40" />
                        <label style='margin-top:1px' for="">
                            (前方一致)
                        </label>
                    </div>
                    <div class='FrmSyainMstList group4' style='float:left;width:100%'>
                        <input type='checkbox' class='FrmSyainMstList chkTaisyoku Enter Tab' style='margin-top:5px;'
                            tabindex="4" />
                        退職者を除く
                        <button class='FrmSyainMstList cmdSearch Enter Tab' style='float:right' tabindex="5">
                            検索
                        </button>
                    </div>

                </div>
            </fieldset>
            <div class='FrmSyainMstList table' style="margin-top:2px;">
                <table id="FrmSyainMstList_sprMeisai" class='Enter Tab' tabindex="6">

                </table>
                <div id="FrmSyainMstList_pager">

                </div>
            </div>
            <div class="HMS-button-pane">
                <div class='HMS-button-set'>
                    <button class='FrmSyainMstList cmdInsert Enter Tab' tabindex="7">
                        新規登録
                    </button>
                    <button class='FrmSyainMstList cmdUpdate Enter Tab' tabindex="8">
                        修正
                    </button>
                    <button class='FrmSyainMstList cmdDelete Enter Tab' tabindex="9">
                        削除
                    </button>
                </div>
            </div>
        </div>
        <div id='FrmSyainMstList_sub_dialog' style='height:500px;'>

        </div>
    </div>
</div>
