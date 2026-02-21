<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmJinkenhiEnt/FrmJinkenhiEnt")); ?>
<style type="text/css">
    #JKSYS_FrmJinkenhiEnt_sprList {
        height: 1px;
    }

    .FrmJinkenhiEnt.set-inline {
        display: inline;
    }

    .FrmJinkenhiEnt.set-width {
        width: 190px;
    }

    .FrmJinkenhiEnt.txtBusyoCd,
    .FrmJinkenhiEnt.txtSyainNo {
        width: 100px;
    }

    .FrmJinkenhiEnt.lblBusyoNm,
    .FrmJinkenhiEnt.lblSyainNm {
        width: 150px;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmJinkenhiEnt.txtBusyoCd,
        .FrmJinkenhiEnt.txtSyainNo {
            width: 90px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class="FrmJinkenhiEnt">
    <div class="FrmJinkenhiEnt JKSYS-content">
        <fieldset class="FrmJinkenhiEnt grpSearch">
            <legend>
                <b> <span>検索条件</span> </b>
            </legend>
            <table>
                <tr class="FrmJinkenhiEnt HMS-button-pane">
                    <td><label class="FrmJinkenhiEnt lbl-sky-L" for=""> 対象年月 </label></td>
                    <td colspan="3">
                        <input type="text" class="FrmJinkenhiEnt dtpYM Enter Tab" maxlength="6" />
                    </td>
                    <td><label class="FrmJinkenhiEnt lbl-sky-L" for=""> 雇用区分 </label></td>
                    <td colspan="3"><select class="FrmJinkenhiEnt set-width ddlKoyouKbn Enter Tab"></select></td>
                </tr>
                <tr>
                    <td><label class="FrmJinkenhiEnt lbl-sky-L" for=""> 部署 </label></td>
                    <td>
                        <input type="text" class="FrmJinkenhiEnt txtBusyoCd Enter Tab" />
                    </td>
                    <td>
                        <div class="FrmJinkenhiEnt HMS-button-pane set-inline">
                            <button class="FrmJinkenhiEnt btnSearchBusyo Enter Tab">
                                検索
                            </button>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="FrmJinkenhiEnt set-width lblBusyoNm Enter Tab" disabled="disabled" />
                    </td>
                    <td><label class="FrmJinkenhiEnt lbl-sky-L" for=""> 社員 </label></td>
                    <td>
                        <input type="text" class="FrmJinkenhiEnt txtSyainNo Enter Tab" />
                    </td>
                    <td>
                        <div class="FrmJinkenhiEnt HMS-button-pane set-inline">
                            <button class="FrmJinkenhiEnt btnSearchSyain Enter Tab">
                                検索
                            </button>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="FrmJinkenhiEnt set-width lblSyainNm Enter Tab" disabled="disabled" />
                    </td>
                    <td class="FrmJinkenhiEnt HMS-button-pane">
                        <button class="FrmJinkenhiEnt btnSearch Enter Tab">
                            検索
                        </button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div>
            <table class="FrmJinkenhiEnt sprList Enter Tab" id="JKSYS_FrmJinkenhiEnt_sprList"></table>
        </div>
        <div class="FrmJinkenhiEnt HMS-button-pane">
            <button class="FrmJinkenhiEnt btnAddRow Enter Tab">
                行追加
            </button>
            <button class="FrmJinkenhiEnt btnDelRow Enter Tab">
                行削除
            </button>
        </div>
        <div class="FrmJinkenhiEnt HMS-button-pane">
            <button class="FrmJinkenhiEnt btnModify Enter Tab">
                条件変更
            </button>
            <button class="FrmJinkenhiEnt HMS-button-set btnUpdate Enter Tab">
                登録
            </button>
        </div>
    </div>
</div>