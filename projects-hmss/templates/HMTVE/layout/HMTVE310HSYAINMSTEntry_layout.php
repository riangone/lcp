<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE310HSYAINMSTEntry/HMTVE310HSYAINMSTEntry")); ?>
<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMTVE310HSYAINMSTEntry.lableTitle {
        width: 100%;
        background-color: #819FF7;
        color: #FFFFFF;
    }

    .HMTVE310HSYAINMSTEntry.lableMargin {
        padding-left: 80px;
    }

    .HMTVE310HSYAINMSTEntry.widthlable {
        width: 105px;
    }

    .HMTVE310HSYAINMSTEntry.addBorder {
        width: 350px;
        border: 1px #222222 solid;
    }

    .HMTVE310HSYAINMSTEntry.classificationRight {
        float: right;
    }

    .HMTVE310HSYAINMSTEntry.classificationLeft {
        float: left;
    }

    .HMTVE310HSYAINMSTEntry.addBorders {
        width: 200px;
        border: 1px #222222 solid;
    }

    .HMTVE310HSYAINMSTEntry.paddingAdd {
        padding-left: 25px;
    }

    .HMTVE310HSYAINMSTEntry.paddingAddWider {
        padding-left: 128px;
    }

    .HMTVE310HSYAINMSTEntry.jqgridDiv {
        padding-top: 85px;
    }

    .HMTVE310HSYAINMSTEntry #HMTVE310HSYAINMSTEntry_gvBusyo input[type='text'] {
        width: 88% !important;
    }

    .HMTVE310HSYAINMSTEntry.HMS-button-pane {
        margin-top: 5px;
    }

    .HMTVE310HSYAINMSTEntry.btnAdd,
    .HMTVE310HSYAINMSTEntry.btnClose {
        float: right;
    }

    .HMTVE310HSYAINMSTEntry.HMTVE-content {
        border: 1px #a6c9e2 solid !important;
    }

    .HMTVE310HSYAINMSTEntry.txtEmployeeSpell {
        width: 190px;
    }

    .HMTVE310HSYAINMSTEntry.DivLeft {
        margin-left: -7px;
        display: inline-block;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .HMTVE310HSYAINMSTEntry.jqgridDiv {
            padding-top: 60px;
        }

        .HMTVE310HSYAINMSTEntry.lableTitle {
            width: 99%;
        }

        .HMTVE310HSYAINMSTEntry.txtEmployeeSpell {
            width: 165px;
        }

        .HMTVE310HSYAINMSTEntry.paddingAddWider {
            padding-left: 105px;
        }
    }
</style>

<div class="HMTVE310HSYAINMSTEntry">
    <div class="HMTVE310HSYAINMSTEntry HMTVE-content">
        <label class='HMTVE310HSYAINMSTEntry lableTitle' for=""> 社員情報 </label>
        <div>
            <label class='HMTVE310HSYAINMSTEntry lbl-yellow-L' for=""> 社員№ </label>
            <input type="text" class="HMTVE310HSYAINMSTEntry txtEmployeeNO Enter Tab" maxlength="5" tabindex="1" />
        </div>
        <div>
            <label class='HMTVE310HSYAINMSTEntry lbl-yellow-L' for=""> 社員名 </label>
            <input type="text" class="HMTVE310HSYAINMSTEntry txtEmployeeName Enter Tab" maxlength="20" tabindex="2" />
        </div>
        <div>
            <label class='HMTVE310HSYAINMSTEntry lbl-sky-L' for=""> 社員名カナ </label>
            <input type="text" class="HMTVE310HSYAINMSTEntry txtEmployeeSpell Enter Tab" maxlength="40" tabindex="3" />
        </div>
        <div>
            <label class='HMTVE310HSYAINMSTEntry lbl-sky-L' for=""> 資格コード </label>
            <input type="text" class="HMTVE310HSYAINMSTEntry txtCapacity Enter Tab" maxlength="2" tabindex="4" />
            <label class='HMTVE310HSYAINMSTEntry lable' for=""> 99：研修生 </label>
            <label class='HMTVE310HSYAINMSTEntry widthlable lbl-sky-L' for=""> 営業スタッフ区分 </label>
            <input type="text" class="HMTVE310HSYAINMSTEntry txtBusiness Enter Tab" maxlength="2" tabindex="5" />
            <label class='HMTVE310HSYAINMSTEntry lableMargin' for="">1：営業スタッフ ３：管理者 9：その他</label>
        </div>
        <div>
            <label class='HMTVE310HSYAINMSTEntry lbl-sky-L' for=""> 退職日 </label>
            <input type="text" class="HMTVE310HSYAINMSTEntry txtResignation Enter Tab" maxlength="10" tabindex="6" />
        </div>
        <label class='HMTVE310HSYAINMSTEntry lableTitle' for=""> 配属先情報 </label>
        <div>
            <div>
                <label class='HMTVE310HSYAINMSTEntry' for="">固定費カバー率用項目説明 </label>
            </div>
            <div class="HMTVE310HSYAINMSTEntry classificationLeft">
                <div class="HMTVE310HSYAINMSTEntry DivLeft">
                    <label class='HMTVE310HSYAINMSTEntry' for="">【表示区分】</label>
                </div>
                <div class="HMTVE310HSYAINMSTEntry addBorder">
                    <div>
                        <label class='HMTVE310HSYAINMSTEntry' for="">1．新車ランキング表に表示</label>
                        <label class='HMTVE310HSYAINMSTEntry paddingAdd' for=""> 9： 対象外・退職者 </label>
                    </div>
                    <div>
                        <label class='HMTVE310HSYAINMSTEntry' for=""> 2．中古ランキング表に表示 </label>
                        <label class='HMTVE310HSYAINMSTEntry paddingAdd' for=""> 空白： 対象外 </label>
                    </div>
                    <div>
                        <label class='HMTVE310HSYAINMSTEntry' for="">3：管理者</label>
                        <label class='HMTVE310HSYAINMSTEntry paddingAddWider' for="">3：管理者</label>
                    </div>
                </div>
            </div>
            <div class="HMTVE310HSYAINMSTEntry classificationRight">
                <div class="HMTVE310HSYAINMSTEntry DivLeft">
                    <label class='HMTVE310HSYAINMSTEntry' for="">【台数表示区分】</label>
                </div>
                <div class="HMTVE310HSYAINMSTEntry addBorders">
                    <div>
                        <label class='HMTVE310HSYAINMSTEntry' for="">売上台数ランキング表に</label>
                    </div>
                    <div>
                        <label class='HMTVE310HSYAINMSTEntry' for="">1：非表示</label>
                    </div>
                    <div>
                        <label class='HMTVE310HSYAINMSTEntry' for="">空白：表示</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="HMTVE310HSYAINMSTEntry jqgridDiv">
            <div class="HMTVE310HSYAINMSTEntry HMS-button-pane">
                <button class="HMTVE310HSYAINMSTEntry btnAddRow button Enter Tab" tabindex="7">
                    行追加
                </button>
            </div>
            <table id="HMTVE310HSYAINMSTEntry_gvBusyo"></table>
            <div class="HMTVE310HSYAINMSTEntry pnlList HMS-button-pane">
                <button class="HMTVE310HSYAINMSTEntry btnClose button Enter Tab" tabindex="9">
                    一覧へ
                </button>
                <button class="HMTVE310HSYAINMSTEntry btnAdd  button Enter Tab" tabindex="8">
                    登録
                </button>
            </div>
        </div>
    </div>
</div>