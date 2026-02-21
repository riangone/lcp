<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE330HDTSYASYUEntry/HMTVE330HDTSYASYUEntry"));
?>

<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMTVE330HDTSYASYUEntry.CELL_TITLE_ZISE_L {
        width: 99%;
        background-color: #819FF7;
        color: #FFFFFF;
    }

    .HMTVE330HDTSYASYUEntry.LBL_TITLE_STD9 {
        width: 172px;
    }

    .HMTVE330HDTSYASYUEntry.HMTVE-content {
        border: 1px #a6c9e2 solid !important;
    }

    .HMTVE330HDTSYASYUEntry.carTypeName {
        width: 360px;
    }

    .HMTVE330HDTSYASYUEntry.tbOrder {
        text-align: right;
    }
</style>

<div class="HMTVE330HDTSYASYUEntry HMTVE330HDTSYASYUEntryDialog">
    <div class="HMTVE330HDTSYASYUEntry HMTVE-content">
        <label class='HMTVE330HDTSYASYUEntry TableCell2  CELL_TITLE_ZISE_L CELL_BORDER TXT_STD9_L' for=""> 車種マスタ
        </label>
        <div>
            <label class='HMTVE330HDTSYASYUEntry lbl-yellow-L LBL_TITLE_STD9' for=""> 車種コード</label>
            <input type="text" class="HMTVE330HDTSYASYUEntry carTypeCode Enter Tab" maxlength="3" tabindex="1" />
        </div>
        <div>
            <label class='HMTVE330HDTSYASYUEntry lbl-yellow-L LBL_TITLE_STD9' for=""> 車種名 </label>
            <input type="text" class="HMTVE330HDTSYASYUEntry carTypeName Enter Tab" maxlength="40" tabindex="2" />
        </div>
        <div>
            <label class='HMTVE330HDTSYASYUEntry lbl-sky-L LBL_TITLE_STD9' for=""> 車種略称名 </label>
            <input type="text" class="HMTVE330HDTSYASYUEntry carTypeAbbr Enter Tab" maxlength="20" tabindex="3" />
        </div>

        <div>
            <label class='HMTVE330HDTSYASYUEntry lbl-sky-L LBL_TITLE_STD9' for=""> 車種区分 </label>
            <input class="HMTVE330HDTSYASYUEntry r1 LBL_MSG_STD8 Enter Tab" name="rbCarType" type="radio"
                tabindex="4" />
            <label class="HMTVE330HDTSYASYUEntry" for=""> 乗用車 </label>
            <input class="HMTVE330HDTSYASYUEntry r2 LBL_MSG_STD8 Enter Tab" name="rbCarType" type="radio"
                tabindex="5" />
            <label class="HMTVE330HDTSYASYUEntry" for=""> 軽自動車 </label>
            <input class="HMTVE330HDTSYASYUEntry r3 LBL_MSG_STD8 Enter Tab" name="rbCarType" type="radio"
                tabindex="6" />
            <label class="HMTVE330HDTSYASYUEntry" for=""> ポルポ</label>
            <input class="HMTVE330HDTSYASYUEntry r4 LBL_MSG_STD8 Enter Tab" name="rbCarType" type="radio"
                tabindex="7" />
            <label class="HMTVE330HDTSYASYUEntry" for=""> その他 </label>
        </div>
        <div>
            <label class='HMTVE330HDTSYASYUEntry lbl-sky-L LBL_TITLE_STD9' for=""> 速報成約台数内訳出力ﾌﾗｸﾞ </label>
            <label class='HMTVE330HDTSYASYUEntry LBL_MSG_STD8' for=""> 速報のHitNet用Excel出力の対象にする </label>
            <input class="HMTVE330HDTSYASYUEntry cbOutput Enter Tab" type="checkbox" tabindex="8" />
        </div>

        <div>
            <label class='HMTVE330HDTSYASYUEntry lbl-sky-L LBL_TITLE_STD9' for=""> 確報試乗件数内訳出力ﾌﾗｸﾞ </label>
            <label class='HMTVE330HDTSYASYUEntry LBL_MSG_STD8' for=""> 確報のHitNet用Excel出力の対象にする </label>
            <input class="HMTVE330HDTSYASYUEntry cbOutput2 Enter Tab" type="checkbox" tabindex="9" />
        </div>

        <div>
            <label class='HMTVE330HDTSYASYUEntry lbl-sky-L LBL_TITLE_STD9' for=""> 表示順 </label>
            <input type="text" class="HMTVE330HDTSYASYUEntry tbOrder TXT_STD9_R Enter Tab" maxlength="2"
                tabindex="10" />
        </div>
        <div class="HMTVE330HDTSYASYUEntry HMS-button-pane">
            <div class="HMTVE330HDTSYASYUEntry HMS-button-set">
                <button class="HMTVE330HDTSYASYUEntry btnLogin BTN_STD60 button Enter Tab" tabindex="11">
                    登録
                </button>
                <button class="HMTVE330HDTSYASYUEntry btnReturn BTN_STD60 BTN_POP button Enter Tab" tabindex="12">
                    一覧へ
                </button>
            </div>
        </div>
    </div>
</div>