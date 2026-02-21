<!-- /**
* 説明：
*
*
* @author FCSDL
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* ----------------------------------------------------------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                         担当
* YYYYMMDD                  #ID                     XXXXXX                      FCSDL
* 20201117                  bug                     コピーボタンをクリックすると、jqGridとボタンのレイアウトが少し変えられるようになっています。WANGYING
* ----------------------------------------------------------------------------------------------------------------------------------------------
*/ -->
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4K/FrmKeieiSeikaPatternMst/FrmKeieiSeikaPatternMst'));
?>
<style>
    /* 暂时修正 150% */
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmKeieiSeikaPatternMst.tabsList,
        .FrmKeieiSeikaPatternMst.tabsList ul {
            height: 21px !important;
        }

        .FrmKeieiSeikaPatternMst.lbl-blue[style*="padding-right: 16px"] {
            padding-right: 12px !important;
        }
    }
</style>
<div class="FrmKeieiSeikaPatternMst R4-content">
    <div class="FrmKeieiSeikaPatternMst listArea">
        <table border="0">
            <tr>
                <td>
                    <div>
                        <table id='FrmKeieiSeikaPatternMst_sprPatarn'>
                        </table>
                    </div>
                </td>
                <td rowspan="3" width="500px">
                    <div class='FrmKeieiSeikaPatternMst listArea'>
                        <table id='FrmKeieiSeikaPatternMst_sprList'>
                        </table>
                    </div>
                    <div class="FrmKeieiSeikaPatternMst tabScroll" style="width: 520px;overflow: auto">
                        <!-- 20201117 wangying upd S -->
                        <!-- <div class="FrmKeieiSeikaPatternMst tabsList" style="padding: 2px;width: 10000px"> -->
                        <div class="FrmKeieiSeikaPatternMst tabsList" style="padding: 2px;width: 10000px;height: 27px">
                            <!-- 20201117 wangying upd E -->
                            <ul style="padding: 0px;height: 27px" class="FrmKeieiSeikaPatternMst tabsUI">
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set' style="float: center;padding-left: 15px">
                            <button class='FrmKeieiSeikaPatternMst cmdInsert Tab'>
                                追加
                            </button>
                            <button class='FrmKeieiSeikaPatternMst cmdCopy Tab'>
                                コピー
                            </button>
                            <button class='FrmKeieiSeikaPatternMst cmdDelete Tab'>
                                削除
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <fieldset class="FrmKeieiSeikaPatternMst GroupBox1"
                        style="height: 60px;width: 390px;border:solid 1px #A6C9E2">
                        <table>
                            <tr>
                                <td>
                                    <label class="FrmKeieiSeikaPatternMst lbl-blue" style="padding-right: 16px" for="">
                                        部署　　　　　
                                    </label>
                                    <input class="FrmKeieiSeikaPatternMst txtBusyoCD Enter Tab" type='text'
                                        maxlength="3" style="width: 50px" />
                                    <input class="FrmKeieiSeikaPatternMst lblBusyoNM" type='text' readonly="readonly"
                                        style="width: 180px" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="FrmKeieiSeikaPatternMst lbl-blue" for="">
                                        印刷順　　　　　
                                    </label>
                                    <input class="FrmKeieiSeikaPatternMst txtPrintOrder numeric Enter Tab" type='text'
                                        maxlength="3" style="width: 50px;text-align: right" />
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                    <div class="HMS-button-pane">
                        <div class='HMS-button-set'>
                            <button class='FrmKeieiSeikaPatternMst cmdAction Enter Tab'>
                                更新
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
