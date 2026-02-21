<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMTVE/HMTVE380MLOGINEntry/HMTVE380MLOGINEntry"));
?>

<!-- 画面個別の内容を表示 -->
<style type="text/css">
    .HMTVE380MLOGINEntry.CELL_TITLE_ZISE_L {
        width: 100%;
        background-color: #7E8ABC;
        color: #FFFFFF;
        padding-left: 2px;
    }

    .HMTVE380MLOGINEntry.HMTVE-content {
        border: 1px #a6c9e2 solid !important;
    }

    .HMTVE380MLOGINEntry.ddlRights,
    .HMTVE380MLOGINEntry.txtPassword,
    .HMTVE380MLOGINEntry.txtPasswordAgain {
        width: 124px;
    }

    .HMTVE380MLOGINEntry.hidCrDate {
        display: none;
    }
</style>

<div class="HMTVE380MLOGINEntry HMTVE380MLOGINEntryDialog">
    <div class="HMTVE380MLOGINEntry HMTVE-content">
        <div>
            <label class="HMTVE380MLOGINEntry CELL_TITLE_ZISE_L" for=""> ログイン情報 </label>
        </div>
        <div>
            <label class="HMTVE380MLOGINEntry lblUseID lbl-yellow-L" for=""> ユーザID</label>
            <input type="text" class="HMTVE380MLOGINEntry txtUserID" maxlength="5" disabled="disabled" />
        </div>
        <div>
            <form>
                <label class="HMTVE380MLOGINEntry lblPassword lbl-yellow-L" for=""> パスワード </label>
                <input type="password" class="HMTVE380MLOGINEntry txtPassword Enter Tab" maxlength="10" tabindex="1" />
            </form>
        </div>
        <div>
            <form>
                <label class="HMTVE380MLOGINEntry lblPasswordAgain lbl-yellow-L" for=""> パスワード確認 </label>
                <input type="password" class="HMTVE380MLOGINEntry txtPasswordAgain Enter Tab" maxlength="10"
                    tabindex="2" />
            </form>
        </div>

        <div>
            <label class="HMTVE380MLOGINEntry lblRights lbl-yellow-L" for=""> 権限 </label>
            <select class="HMTVE380MLOGINEntry ddlRights Enter Tab" tabindex="3"></select>
        </div>
        <div class="HMTVE380MLOGINEntry HMS-button-pane">
            <button class="HMTVE380MLOGINEntry btnAll HMS-button-set button Enter Tab" tabindex="5">
                一覧へ
            </button>
            <button class="HMTVE380MLOGINEntry btnLogin HMS-button-set button Enter Tab" tabindex="4">
                登録
            </button>
        </div>
        <div class="HMTVE380MLOGINEntry hidCrDate"></div>
    </div>
</div>