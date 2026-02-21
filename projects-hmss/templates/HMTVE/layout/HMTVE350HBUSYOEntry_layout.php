<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE350HBUSYOEntry/HMTVE350HBUSYOEntry"));
?>

<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMTVE350HBUSYOEntry.LBL_TITLE_STD8 {
        width: 108px;
    }

    .HMTVE350HBUSYOEntry.LBL_TITLE_STD9 {
        width: 172px;
        margin-left: 30px;
    }

    .HMTVE350HBUSYOEntry.LBL_TITLE_STD7 {
        width: 172px;
        margin-left: 80px;
    }

    .HMTVE350HBUSYOEntry.LBL_TITLE_STD10 {
        width: 172px;
        margin-left: 240px;
    }

    .HMTVE350HBUSYOEntry.LBL_TITLE_STD11 {
        width: 172px;
        margin-left: 250px;
    }

    .HMTVE350HBUSYOEntry.LBL_TITLE_STD12 {
        width: 172px;
        margin-left: 150px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE350HBUSYOEntry.LBL_TITLE_STD12 {
            margin-left: 180px;
        }
    }

    .HMTVE350HBUSYOEntry.LBL_TITLE_STD14 {
        width: 172px;
        margin-left: 170px;
    }

    .HMTVE350HBUSYOEntry.LBL_TITLE_STD13 {
        width: 172px;
        margin-left: 220px;
    }

    .HMTVE350HBUSYOEntry.txtName {
        width: 240px;
    }

    .HMTVE350HBUSYOEntry.txtNameKa {
        width: 190px;
    }

    .HMTVE350HBUSYOEntry.txtMsgID {
        width: 100px;
    }

    .HMTVE350HBUSYOEntry.HMTVE-content {
        border: 1px #a6c9e2 solid !important;
    }
</style>

<div class="HMTVE350HBUSYOEntry body">
    <div class="HMTVE350HBUSYOEntry HMTVE-content">
        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblID lbl-yellow-L LBL_TITLE_STD8' for=""> 部署コード </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtID Enter Tab" maxLength="5" tabindex="1" />
            <label class='HMTVE350HBUSYOEntry lblShowIndex lbl-sky-L  LBL_TITLE_STD13' for=""> 表示順位</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtShowIndex Enter Tab" maxLength="3" tabindex="2" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblName lbl-yellow-L  LBL_TITLE_STD8' for=""> 部署名 </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtName Enter Tab" maxLength="40" tabindex="3" />
            <label class='HMTVE350HBUSYOEntry lblNewCar lbl-sky-L  LBL_TITLE_STD9' for=""> 新車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtNewCar Enter Tab" maxLength="1" tabindex="4" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblNameKa lbl-sky-L  LBL_TITLE_STD8' for=""> 部署名カナ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtNameKa Enter Tab" maxLength="30" tabindex="5" />
            <label class='HMTVE350HBUSYOEntry lblOldCar lbl-sky-L  LBL_TITLE_STD7' for=""> 中古車ﾗﾝｷﾝｸﾞ出力ﾌﾗｸﾞ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtOldCar Enter Tab" maxLength="1" tabindex="6" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblSName lbl-sky-L  LBL_TITLE_STD8' for=""> 部署略称名</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtSName Enter Tab" maxLength="10" tabindex="7" />
            <label class='HMTVE350HBUSYOEntry lblMeanwhile lbl-sky-L  LBL_TITLE_STD12' for=""> 整備ﾗﾝｷﾝｸﾞ出力ﾌﾗｸ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtMeanwhile Enter Tab" maxLength="1" tabindex="8" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblPartment lbl-sky-L LBL_TITLE_STD8' for=""> 括り部署 </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtPartment Enter Tab" maxLength="3" tabindex="9" />
            <label class='HMTVE350HBUSYOEntry lblPandLS lbl-sky-L  LBL_TITLE_STD10' for=""> 損益科目明細出力ﾌﾗｸﾞ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtPandLS Enter Tab" maxLength="1" tabindex="10" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblPartChange lbl-sky-L  LBL_TITLE_STD8' for=""> 変換部署 </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtPartChange Enter Tab" maxLength="3" tabindex="11" />
            <label class='HMTVE350HBUSYOEntry lblObjRes lbl-sky-L  LBL_TITLE_STD10' for=""> 経営成果対象ﾌﾗｸﾞ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtObjRes Enter Tab" maxLength="1" tabindex="12" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblShopID lbl-sky-L  LBL_TITLE_STD8' for=""> 店舗コード</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtShopID Enter Tab" maxLength="3" tabindex="13" />
            <label class='HMTVE350HBUSYOEntry lblFactObj lbl-sky-L  LBL_TITLE_STD10' for=""> 本部別実績対象ﾌﾗｸﾞ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtFactObj Enter Tab" maxLength="1" tabindex="14" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblSetDistinction lbl-sky-L LBL_TITLE_STD8' for=""> 集計区分 </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtSetDistinction Enter Tab" maxLength="1" tabindex="15" />
            <label class='HMTVE350HBUSYOEntry lblRate lbl-sky-L  LBL_TITLE_STD11' for=""> 固定費ｶﾊﾞｰ率用表示ﾌﾗｸﾞ</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtRate Enter Tab" maxLength="1" tabindex="16" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblPartDistinction lbl-sky-L LBL_TITLE_STD8' for=""> 部署区分 </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtPartDistinction Enter Tab" maxLength="1" tabindex="17" />
            <label class='HMTVE350HBUSYOEntry lblSetShopID lbl-sky-L  LBL_TITLE_STD11' for=""> イベント集計店舗コード</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtSetShopID Enter Tab" maxLength="3" tabindex="18" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblToPartDictinction lbl-sky-L LBL_TITLE_STD8' for=""> 取込部署区分 </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtToPartDistinction Enter Tab" maxLength="1" tabindex="19">
            <label class='HMTVE350HBUSYOEntry lblSetShowIndex lbl-sky-L  LBL_TITLE_STD11' for=""> イベント集計用表示順位</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtSetShowIndex Enter Tab" maxLength="2" tabindex="20" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblMsgID lbl-sky-L LBL_TITLE_STD8' for=""> 管理者社員コード </label>
            <input type="text" class="HMTVE350HBUSYOEntry txtMsgID Enter Tab" maxLength="8" tabindex="21" />
            <label class='HMTVE350HBUSYOEntry lblTandFShowIndex lbl-sky-L  LBL_TITLE_STD14' for=""> 目標と実績用表示順位</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtTandFShowIndex Enter Tab" maxLength="2" tabindex="22" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblSetDay lbl-sky-L  LBL_TITLE_STD8' for=""> 設立日</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtSetDay Enter Tab" maxLength="10" tabindex="23" />
            <label class='HMTVE350HBUSYOEntry lblShopIndex lbl-sky-L  LBL_TITLE_STD12' for=""> 店舗表示順位</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtShopIndex Enter Tab" maxLength="2" tabindex="24" />
        </div>

        <div class='HMTVE350HBUSYOEntry CELL_TITLE_YELLOW_L CELL_BORDER'>
            <label class='HMTVE350HBUSYOEntry lblCloseDay lbl-sky-L  LBL_TITLE_STD8' for=""> 閉鎖日</label>
            <input type="text" class="HMTVE350HBUSYOEntry txtCloseDay Enter Tab" maxLength="10" tabindex="25" />
        </div>
        <div class="HMTVE350HBUSYOEntry HMS-button-pane">
            <div class="HMTVE350HBUSYOEntry HMS-button-set">
                <button class="HMTVE350HBUSYOEntry btnLogin BTN_STD100 BTN_POP button Enter Tab" tabindex="26">
                    登録
                </button>
                <button class="HMTVE350HBUSYOEntry btnShowAll BTN_STD100 button Enter Tab" tabindex="27">
                    一覧へ
                </button>
            </div>
        </div>
    </div>
</div>