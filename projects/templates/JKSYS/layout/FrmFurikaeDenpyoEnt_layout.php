<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmFurikaeDenpyoEnt/FrmFurikaeDenpyoEnt"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmFurikaeDenpyoEnt.dtpTaisyouYMDiv {
        display: inline-block;
    }

    .FrmFurikaeDenpyoEnt.lblState {
        text-align: center;
        height: 21px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmFurikaeDenpyoEnt.lblState {
            height: 16px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmFurikaeDenpyoEnt'>
    <div class='FrmFurikaeDenpyoEnt JKSYS-content JKSYS-content-fixed-width'>
        <fieldset>
            <legend>
                <b> <span>検索条件</span> </b>
            </legend>
            <div class="FrmFurikaeDenpyoEnt HMS-button-pane">
                <label class="FrmFurikaeDenpyoEnt lbl-sky-L" for="">対象年月</label>
                <input type="text" class="FrmFurikaeDenpyoEnt dtpTaisyouYM Enter Tab" maxlength="6" tabindex="1" />
                <button class="FrmFurikaeDenpyoEnt HMS-button-set btnKensaku Enter Tab" tabindex="2">
                    検索
                </button>
            </div>
        </fieldset>
        <div>
            <label class="FrmFurikaeDenpyoEnt lbl-yellow-M lblState" for=""></label>
        </div>
        <div>
            <div class="FrmFurikaeDenpyoEnt Label1 Label" for=""> <strong>他部署振替者氏名および振替先</strong>
            </div>
        </div>
        <div>
            <table id="JKSYS_FrmFurikaeDenpyoEnt_sprList1"></table>
        </div>
        <div class="FrmFurikaeDenpyoEnt HMS-button-pane">
            <button class="FrmFurikaeDenpyoEnt btnRowAdd Enter Tab" tabindex="3">
                行追加
            </button>
            <button class="FrmFurikaeDenpyoEnt btnRowDel Enter Tab" tabindex="4">
                行削除
            </button>
        </div>
        <div>
            <label class="FrmFurikaeDenpyoEnt Label2" for=""> <strong>長期欠勤連絡</strong> </label>
        </div>
        <div>
            <table id="JKSYS_FrmFurikaeDenpyoEnt_sprList2"></table>
        </div>
        <div class="FrmFurikaeDenpyoEnt HMS-button-pane">
            <button class='FrmFurikaeDenpyoEnt btnChange Enter Tab' tabindex="8">
                条件変更
            </button>
            <div class='FrmFurikaeDenpyoEnt HMS-button-set'>
                <button class='FrmFurikaeDenpyoEnt btnEnt Enter Tab' tabindex="5">
                    登録
                </button>
                <button class='FrmFurikaeDenpyoEnt btnDelete Enter Tab' tabindex="6">
                    削除
                </button>
                <button class='FrmFurikaeDenpyoEnt btnExcel Enter Tab' tabindex="7">
                    Excel出力
                </button>
            </div>
        </div>
    </div>
</div>