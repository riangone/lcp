<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE360HMENUPATTERNEntry/HMTVE360HMENUPATTERNEntry")); ?>
<style type="text/css">
    .HMTVE360HMENUPATTERNEntry.txtRightsID {
        width: 40px;
    }

    .HMTVE360HMENUPATTERNEntry.labelright {
        padding-right: 100px;
    }

    .HMTVE360HMENUPATTERNEntry.btnLogin,
    .HMTVE360HMENUPATTERNEntry.btnAdd,
    .HMTVE360HMENUPATTERNEntry.btnDelete {
        min-width: 100px
    }

    .HMTVE360HMENUPATTERNEntry label {
        margin-bottom: 4px;
    }

    .HMTVE360HMENUPATTERNEntry.HMS-button-set button {
        margin-top: 4px;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='HMTVE360HMENUPATTERNEntry'>
    <div class='HMTVE360HMENUPATTERNEntry HMDPS-content'>
        <table class="HMTVE360HMENUPATTERNEntry table">
            <tr>
                <td valign="top">
                    <div class='HMTVE360HMENUPATTERNEntry'>
                        <label class="HMTVE360HMENUPATTERNEntry labelright"
                            for="">既存データを修正する場合は修正するパターン行の選択ボタンを押下してください。</label>
                        <table id="HMTVE360HMENUPATTERNEntry_gvRights"></table>
                        <label for=""> 新規データを追加する場合は下記追加ボタンを押下してください。 </label>
                    </div>
                    <button class='HMTVE360HMENUPATTERNEntry  btnAdd Enter Tab' tabindex="1">
                        追加
                    </button>
                </td>

                <td>
                    <div class='HMTVE360HMENUPATTERNEntry PnlCsvOutTableRow'>
                        <div>
                            <label class='HMTVE360HMENUPATTERNEntry Label1 lbl-sky-L' for=""> 権限ID </label>
                            <input class="HMTVE360HMENUPATTERNEntry txtRightsID Enter Tab" maxlength="3" tabindex="2" />

                        </div>
                        <div>
                            <label class='HMTVE360HMENUPATTERNEntry Label1 lbl-sky-L' for=""> 権限名 </label>
                            <input type="text" class='HMTVE360HMENUPATTERNEntry  txtRightsName  Tab Enter'
                                maxlength="50" tabindex="3" />
                        </div>
                        <div>
                            <table id="HMTVE360HMENUPATTERNEntry_gvProgramInfo"></table>
                        </div>
                        <div class="HMTVE360HMENUPATTERNEntry HMS-button-set">
                            <button class='HMTVE360HMENUPATTERNEntry btnLogin Enter Tab ' tabindex="4">
                                登録
                            </button>
                            <button class='HMTVE360HMENUPATTERNEntry btnDelete Enter Tab ' tabindex="5">
                                削除
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>