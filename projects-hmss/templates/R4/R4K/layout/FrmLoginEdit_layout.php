<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmLoginEdit/FrmLoginEdit'));
?>

<div id="FrmLoginEdit" class='FrmLoginEdit R4-content'>
    <label class='FrmLoginEdit lbl-blue' style="min-width: 100px" for="">
        ユーザＩＤ
    </label>
    &nbsp;
    <input class="FrmLoginEdit Label8" disabled="disabled" style="width: 60px" />
    <input class="FrmLoginEdit Label7" disabled="disabled" style="width: 190px" />
    <br />
    <input type="hidden" class="FrmLoginEdit UcTextBox1 Enter" />
    <!--
  <label class='FrmLoginEdit lbl-blue' style="min-width: 100px">
    パスワード
  </label>
  <input class="FrmLoginEdit UcTextBox1 Enter Tab" type="password" />
  <br />
  <label class='FrmLoginEdit lbl-blue' style="min-width: 100px">
    パスワード確認
  </label>
  <input class="FrmLoginEdit UcTextBox2 Enter Tab" type="password" />
  -->


    <label class='FrmLoginEdit lbl-blue' style="min-width: 100px" for="">
        所属ＩＤ
    </label>
    <select class="FrmLoginEdit UcComboBox1 Enter Tab" style="width: 270px;margin-left:2px">
        <option></option>
    </select>
    <br />
    <label class='FrmLoginEdit lbl-blue' style="min-width: 100px" for="">
        パターンＩＤ
    </label>
    <select class="FrmLoginEdit UcComboBox2 Enter Tab" style="width: 270px;margin-left:2px">
        <option></option>
    </select>
    <br />
    <div class="HMS-button-pane">
        <div class='HMS-button-set'>
            <button class='FrmLoginEdit Button3 Enter Tab'>
                登録
            </button>
        </div>
    </div>
</div>
