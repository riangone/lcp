<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/R4K/FrmBusyoMstEdit/FrmBusyoMstEdit"));
?>
<div class='FrmBusyoMst'>
    <div class='FrmBusyoMst  R4-content'>
        <label style='margin-top:15px;margin-left:10px;' for="">
            部署コード
        </label>
        <input type='text' class='FrmBusyoMstEdit txtBusyoCD Enter Tab' style='width:20' maxlength="3" />
        <fieldset style='margin-top:10px;'>
            <div style='; float:left;width:500px;'>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        部署名
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtBusyoNM Enter Tab' style='width:330px;'
                        maxlength="40" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        部署名カナ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtBusyoKN Enter Tab' style='width:260px;'
                        maxlength="30" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        部署略称名
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtBusyoRK Enter Tab' style='width:100px;'
                        maxlength='10' />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        括り部署
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtKkrBusyoCD Enter Tab' style='width:50px;'
                        maxlength="3" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        変換部署
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtCnvBusyoCD Enter Tab' style='width:50px;'
                        maxlength="3" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        店舗コード
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtTenpoCD Enter Tab' style='width:50px;' maxlength="3" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        集計部署区分
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtSyukeiKB Enter Tab' style='width:20px;'
                        maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        取込部署区分
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtTorikomiBusyoKB Enter Tab' style='width:20px;'
                        maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        管理者社員コード
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtManeger_CD Enter Tab' style='width:80px;'
                        maxlength="8" />

                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        設立日
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtStartDate Enter Tab' style='width:110px;'
                        maxlength="10" />

                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:120px' for="">
                        閉鎖日
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtEndDate Enter Tab' style='width:110px;'
                        maxlength="10" />
                </div>
            </div>
            <!-- == -->
            <div style='; width:300px;float:right;margin-left:10px;'>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        表示順位
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtDsp_SeqNO Enter Tab' style='width:40px;'
                        maxlength="3" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        新車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtPRN_KB1 Enter Tab' style='width:20px;' maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        中古車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtPRN_KB2 Enter Tab' style='width:20px;' maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        整備ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtPRN_KB3 Enter Tab' style='width:20px;' maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        損益科目明細出力ﾌﾗｸﾞ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtPRN_KB4 Enter Tab' style='width:20px;' maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        経営成果対象ﾌﾗｸﾞ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtPRN_KB5 Enter Tab' style='width:20px;' maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        本部別実績対象ﾌﾗｸﾞ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtPRN_KB6 Enter Tab' style='width:20px;' maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        固定費カバー率用表示ﾌﾗｸﾞ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtHknSytDspKB Enter Tab' style='width:20px;'
                        maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        実績集計表出力フラグ
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtJissekiOutFlg Enter Tab' style='width:20px;'
                        maxlength="1" />
                </div>
                <div style='; width:100%'>
                    <label style='border:solid 1px; background-color:#C1CDCD;width:175px' for="">
                        部署区分
                    </label>
                    <input type='text' class='FrmBusyoMstEdit txtBusyoKB Enter Tab' style='width:40px;' maxlength="1" />
                </div>
            </div>
        </fieldset>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmBusyoMstEdit cmdAction Enter Tab' tabindex="7">
                    登録
                </button>
                <button class='FrmBusyoMstEdit cmdBack Enter Tab' tabindex="8">
                    戻る
                </button>

            </div>
        </div>
    </div>
</div>
