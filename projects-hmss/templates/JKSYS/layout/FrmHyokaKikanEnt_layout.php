<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmHyokaKikanEnt/FrmHyokaKikanEnt"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<div class="FrmHyokaKikanEnt">
    <div class="FrmHyokaKikanEnt JKSYS-content JKSYS-content-fixed-width">
        <!-- 評価実施考課 -->
        <div class="FrmHyokaKikanEnt">
            <label class="FrmHyokaKikanEnt Label1 lbl-sky-L" for=""> 評価実施考課 </label>
            <input type="radio" value="" name="Taisyo" class="FrmHyokaKikanEnt rdbBonus Enter Tab" checked="checked"
                tabindex="0" />
            夏季・冬季
            <input type="radio" value="" name="Taisyo" class="FrmHyokaKikanEnt rdbSyokoukyu Enter Tab" tabindex="0" />
            年間
        </div>
        <!-- 評価実施年月 -->
        <div class="FrmHyokaKikanEnt">
            <label class="FrmHyokaKikanEnt Label4 lbl-sky-L" for=""> 評価実施年月 </label>
            <input class="FrmHyokaKikanEnt dtpJisshiYM Enter Tab" maxlength="6" tabindex="1"
                id="FrmHyokaKikanEnt_dtpJisshiYM" />
        </div>
        <!-- 評価対象期間 -->
        <div class="FrmHyokaKikanEnt">
            <label class="FrmHyokaKikanEnt Label2 lbl-sky-L" for=""> 評価対象期間 </label>
            <input class="FrmHyokaKikanEnt dtpTaisyouKS Enter Tab" maxlength="10" tabindex="3"
                id="FrmHyokaKikanEnt_dtpTaisyouKS" />
            <label class="FrmHyokaKikanEnt" for=""> ～ </label>
            <input class="FrmHyokaKikanEnt dtpTaisyouKE Enter Tab" maxlength="10" tabindex="5"
                id="FrmHyokaKikanEnt_dtpTaisyouKE" />
        </div>
        <!-- キャンセル·削除·登録 -->
        <div class="FrmHyokaKikanEnt HMS-button-pane">
            <div class="FrmHyokaKikanEnt HMS-button-set">
                <button class="FrmHyokaKikanEnt cmdCancel Enter Tab" tabindex="6">
                    キャンセル
                </button>
                <button class="FrmHyokaKikanEnt cmdDelete Enter Tab" tabindex="7">
                    削除
                </button>
                <button class="FrmHyokaKikanEnt cmdUpdate Enter Tab" tabindex="8">
                    登録
                </button>
            </div>
        </div>
        <!-- jqgrid -->
        <div class="FrmHyokaKikanEnt">
            <table id="FrmHyokaKikanEnt_sprList"></table>
        </div>
        <!-- 選択 -->
        <div class="FrmHyokaKikanEnt HMS-button-pane">
            <button class="FrmHyokaKikanEnt btnSelect Enter Tab">
                選択
            </button>
        </div>
    </div>
</div>
