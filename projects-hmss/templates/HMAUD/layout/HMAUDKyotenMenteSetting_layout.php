<!-- /**
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD              #ID                     	XXXXXX                        FCSDL
* 20260128             修正          社員番号英字を入力できるように修正               YIN
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("HMAUD/HMAUDKyotenMenteSetting/HMAUDKyotenMenteSetting"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<!-- 画面個別の内容を表示 -->
<style type="text/css">
    input.HMAUDKyotenMenteSetting.Label8 {
        width: 100px;
    }

    .HMAUDKyotenMenteSetting.UcComboBox2 {
        width: 70px;
    }

    .HMAUDKyotenMenteSetting.UcComboBox3 {
        width: 140px;
    }

    input.HMAUDKyotenMenteSetting.Label9 {
        display: none;
    }

    .HMAUDKyotenMenteSetting.labelStyle {
        display: inline-block;
        vertical-align: top;
    }
</style>
<div class='HMAUDKyotenMenteSetting body'>
    <div class='HMAUDKyotenMenteSetting HMAUD-content'>
        <div>
            <label for="" class='HMAUDKyotenMenteSetting lbl-sky-L'> 拠点コード </label>
            <input class="HMAUDKyotenMenteSetting kyoten_cd UcComboBox2" disabled="disabled" />
        </div>
        <div>
            <label for="" class='HMAUDKyotenMenteSetting lbl-sky-L'> 拠点名 </label>
            <input class="HMAUDKyotenMenteSetting kyoten_name UcComboBox3" disabled="disabled" />

        </div>
        <div>
            <label for="" class='HMAUDKyotenMenteSetting lbl-sky-L'> 領域 </label>
            <select class="HMAUDKyotenMenteSetting statusSelect UcComboBox3 Enter Tab" disabled="disabled" />

        </div>

        <div>
            <label for="" class='HMAUDKyotenMenteSetting lbl-sky-L'> 拠点責任者 </label>
            <input class="HMAUDKyotenMenteSetting kyoten_userid UcComboBox2 Enter Tab"
                oninput="value=value.replace(/[^0-9a-zA-Z]/g,'')" maxlength="5" tabindex="0" />
            <input class="HMAUDKyotenMenteSetting kyoten_username Label8" disabled="disabled" />
        </div>

        <div>
            <label for="" class='HMAUDKyotenMenteSetting lbl-sky-L'> 領域責任者 </label>
            <input class="HMAUDKyotenMenteSetting responsible_userid UcComboBox2 Enter Tab"
                oninput="value=value.replace(/[^0-9a-zA-Z]/g,'')" maxlength="5" tabindex="1" />
            <input class="HMAUDKyotenMenteSetting responsible_username Label8" disabled="disabled" />
        </div>

        <div>
            <label for="" class='HMAUDKyotenMenteSetting labelStyle lbl-sky-L'> キーマン </label>
            <input class="HMAUDKyotenMenteSetting keyperson_userid UcComboBox2 Enter Tab"
                oninput="value=value.replace(/[^0-9a-zA-Z]/g,'')" maxlength="5" tabindex="2" />
            <input class="HMAUDKyotenMenteSetting keyperson_username Label8" disabled="disabled" />
        </div>
        <div class="HMAUDKyotenMenteSetting HMS-button-pane">
            <div class='HMAUDKyotenMenteSetting HMS-button-set'>
                <button class='HMAUDKyotenMenteSetting Button3 Tab Enter' tabindex="3">
                    更新
                </button>
                <button class='HMAUDKyotenMenteSetting Button2 Tab Enter' tabindex="4">
                    キャンセル
                </button>
            </div>
        </div>
    </div>
</div>