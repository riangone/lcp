<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmSyoreiSikyu/FrmSyoreiSikyu"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmSyoreiSikyu.labelHidden {
        visibility: hidden;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmSyoreiSikyu'>
    <div class='FrmSyoreiSikyu JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class='FrmSyoreiSikyu Label18 lbl-sky-L' for=""> 支給年月 </label>
            <input class='FrmSyoreiSikyu DateTimePicker1 Enter Tab' maxlength="6" />
        </div>
        <div>
            <label class='FrmSyoreiSikyu Label2 lbl-sky-L' for=""> 出力対象 </label>
            <input class='FrmSyoreiSikyu rdbGyouseki Enter Tab' type="radio" name="FrmSyoreiSikyu_radio" value="1" />
            <label class="FrmSyoreiSikyu rdbGyousekiDiv" for="">業績奨励手当・全部署一括</label>
        </div>
        <div>
            <label class='FrmSyoreiSikyu Label2 labelHidden lbl-sky-L' for=""></label>
            <input class='FrmSyoreiSikyu rdbGyousekiTenpobetu Enter Tab' type="radio" name="FrmSyoreiSikyu_radio"
                value="2" />
            <label class="FrmSyoreiSikyu rdbGyousekiTenpobetuDiv" for="">業績奨励手当・店舗別</label>
        </div>
        <div>
            <label class='FrmSyoreiSikyu Label2 labelHidden lbl-sky-L' for=""></label>
            <input class="FrmSyoreiSikyu rdbTencyou Enter Tab" type="radio" name="FrmSyoreiSikyu_radio" value="3" />
            <label class="FrmSyoreiSikyu rdbTencyouDiv" for="">店長奨励手当</label>
        </div>
        <div class="FrmKeisuMstMente HMS-button-pane">
            <button class='FrmSyoreiSikyu cmdExcel HMS-button-set Enter Tab'>
                EXCEL出力
            </button>
        </div>
    </div>
</div>
