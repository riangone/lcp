<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmKeisuMstMente/FrmKeisuMstMente"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmKeisuMstMente.selectWidth {
        width: 188px;
    }

    .FrmKeisuMstMente>fieldset>div {
        margin: 5px;
    }

    .FrmKeisuMstMente.labelStyle {
        display: inline-block;
        vertical-align: top;
    }

    .FrmKeisuMstMente.txtHaniS,
    .FrmKeisuMstMente.txtHaniE,
    .FrmKeisuMstMente.txtKeisu {
        text-align: right;
    }
</style>

<!-- 画面個別の内容を表示 -->
<div class='FrmKeisuMstMente'>
    <div class="FrmKeisuMstMente JKSYS-content JKSYS-content-fixed-width">
        <fieldset class="FrmKeisuMstMente">
            <legend>
                <b><span>検索条件</span></b>
            </legend>
            <div>
                <label class="FrmKeisuMstMente Label18 lbl-sky-L" for=""> 奨励金区分 </label>

                <input type="radio" value="rdbEigyouSch" name="kinKbnSch"
                    class='FrmKeisuMstMente rdbEigyouSch Tab Enter' tabindex="2" />
                <label class="FrmKeisuMstMente rdbEigyouSchDiv" for="">営業業績</label>

                <input type="radio" value="rdbTencyouSch" name="kinKbnSch"
                    class='FrmKeisuMstMente rdbTencyouSch Tab Enter' tabindex="3" />
                <label class="FrmKeisuMstMente rdbTencyouSchDiv" for="">店長</label>
            </div>
            <div class="HMS-button-pane">
                <label class="FrmKeisuMstMente Label2 labelStyle lbl-sky-L" for=""> 係数種類
                </label>
                <select class="FrmKeisuMstMente cmbKeisuSch selectWidth Enter Tab" tabindex="4"></select>
                <button class="FrmKeisuMstMente cmdSearch Enter Tab" tabindex="5">
                    検索
                </button>
            </div>
        </fieldset>

        <div>
            <table class="FrmKeisuMstMente FpSpread1" id="FrmKeisuMstMente_FpSpread1"></table>
        </div>
        <div class="FrmKeisuMstMente HMS-button-pane">
            <button class="FrmKeisuMstMente cmdSelect HMS-button-set Enter Tab" tabindex="7">
                選択
            </button>
        </div>

        <div>
            <label class="FrmKeisuMstMente Label1 lbl-sky-L" for=""> 奨励金区分 </label>
            <input type="radio" value="rdbEigyou" name="kinKbn" class='FrmKeisuMstMente rdbEigyou Tab Enter'
                tabindex="9" />
            <label class="FrmKeisuMstMente rdbEigyouDiv" for="">営業業績</label>
            <input type="radio" value="rdbTencyou" name="kinKbn" class='FrmKeisuMstMente rdbTencyou Tab Enter'
                tabindex="10" />
            <label class="FrmKeisuMstMente rdbTencyouDiv" for="">店長</label>
        </div>
        <div>
            <label class="FrmKeisuMstMente  labelStyle lbl-sky-L" for=""> 係数種類 </label>
            <select class="FrmKeisuMstMente cmbKeisu selectWidth Enter Tab" tabindex="11"></select>
        </div>
        <div>
            <label class="FrmKeisuMstMente labelStyle lbl-sky-L" for=""> 項目 </label>
            <select class="FrmKeisuMstMente cmbKomok selectWidth Enter Tab" tabindex="12"></select>
        </div>
        <div>
            <label class="FrmKeisuMstMente lbl-sky-L" for=""> 範囲 </label>
            <input class="FrmKeisuMstMente txtHaniS Enter Tab" tabindex="13" />
            <label class="FrmKeisuMstMente Label7" for=""> ～ </label>
            <input class="FrmKeisuMstMente txtHaniE Enter Tab" tabindex="14" />
        </div>
        <div>
            <label class="FrmKeisuMstMente lbl-sky-L" for=""> 係数 </label>
            <input class="FrmKeisuMstMente txtKeisu Enter Tab" tabindex="15" />
        </div>
        <div class="FrmKeisuMstMente HMS-button-pane">
            <div class="FrmKeisuMstMente HMS-button-set">
                <button class="FrmKeisuMstMente cmdCancel Enter Tab" tabindex="16">
                    キャンセル
                </button>
                <button class="FrmKeisuMstMente cmdRegist Enter Tab" tabindex="17">
                    登録
                </button>
                <button class="FrmKeisuMstMente cmdDelete Enter Tab" tabindex="18">
                    削除
                </button>
            </div>
        </div>
    </div>
</div>
