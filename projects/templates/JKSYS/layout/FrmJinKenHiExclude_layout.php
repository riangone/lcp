<!-- /**
 * 説明：
 *
 *
 * @author caina
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * --------------------------------------------------------------------------------------------
 */ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("JKSYS/FrmJinKenHiExclude/FrmJinKenHiExclude"));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .FrmJinKenHiExclude .width {
        width: 1100px;
        height: 550;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {

        .FrmJinKenHiExclude .width {
            width: 1044px;
        }
    }
</style>
<!-- 画面個別の内容を表示 -->
<div class='FrmJinKenHiExclude'>
    <div class='FrmJinKenHiExclude JKSYS-content width'>
        <div>
            <table id="JKSYS_FrmJinKenHiExclude_sprList1"></table>
        </div>
        <div class="FrmJinKenHiExclude HMS-button-pane">
            <button class="FrmJinKenHiExclude btnRowAdd Enter Tab" tabindex="1">
                行追加
            </button>
            <button class="FrmJinKenHiExclude btnRowDel Enter Tab" tabindex="2">
                行削除
            </button>
            <div class='FrmJinKenHiExclude HMS-button-set'>
                <button class='FrmJinKenHiExclude btnEnt Enter Tab' tabindex="3">
                    登録
                </button>
            </div>
        </div>
    </div>
</div>