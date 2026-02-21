<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmJinkenhiMeisai/FrmJinkenhiMeisai"));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmJinkenhiMeisai.txtFromBusyoCD,
    .FrmJinkenhiMeisai.txtToBusyoCD {
        width: 60px;
    }

    .FrmJinkenhiMeisai.lblFromBusyoNM,
    .FrmJinkenhiMeisai.lblToBusyoNM {
        width: 170px;
    }

    .FrmJinkenhiMeisai.set-inline {
        display: inline;
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmJinkenhiMeisai'>
    <div class='FrmJinkenhiMeisai JKSYS-content JKSYS-content-fixed-width'>
        <div>
            <label class="FrmJinkenhiMeisai lbl-sky-L" for="" tabindex="0"> 対象年月
            </label>
            <input type="text" class='FrmJinkenhiMeisai dtpTaisyouYM Enter Tab' maxlength="6" tabindex="1" />
        </div>
        <div>
            <label class="FrmJinkenhiMeisai lbl-sky-L" for="" tabindex="0"> 部
                署 </label>
            <input class='FrmJinkenhiMeisai txtFromBusyoCD Enter Tab' tabindex="4" />
            <div class="FrmJinkenhiMeisai HMS-button-pane set-inline">
                <button class='FrmJinkenhiMeisai btnFromSearch Enter Tab' tabindex="5">
                    検索
                </button>
            </div>
            <input class='FrmJinkenhiMeisai lblFromBusyoNM Enter Tab' disabled="disabled" />
            ～
            <input class='FrmJinkenhiMeisai txtToBusyoCD Enter Tab' tabindex="4" />
            <div class="FrmJinkenhiMeisai HMS-button-pane set-inline">
                <button class='FrmJinkenhiMeisai btnToSearch Enter Tab' tabindex="5">
                    検索
                </button>
            </div>

            <input class='FrmJinkenhiMeisai lblToBusyoNM Enter Tab' disabled="disabled" />
        </div>
        <div class="FrmJinkenhiMeisai HMS-button-pane">
            <div class='FrmJinkenhiMeisai HMS-button-set'>
                <button class='FrmJinkenhiMeisai btnExcel Enter Tab'>
                    EXCEL出力
                </button>
            </div>
        </div>
    </div>

</div>
