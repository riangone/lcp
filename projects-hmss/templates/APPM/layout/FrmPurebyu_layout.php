<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmPurebyu'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmPurebyu body' id="FrmPurebyu" style="width: 700px;text-align: center;">
    <fieldset>
        <table>
            <tr style="height:20px;">
                <td><label for="" class='FrmPurebyu txtShaken Enter Tab Tab3'
                        style="width: 325px; text-align:center"></label>
                </td>
            </tr>
        </table>
        <table>
            <tr style="height:20px;">
                <td><label for="" class='FrmPurebyu txtSharyo Enter Tab Tab3'
                        style="width: 90px;text-align:left"></label></td>
                <td><label for="" class='FrmPurebyu txtTitle Enter Tab Tab3'
                        style="width: 240px; text-align:left"></label>
                </td>
            </tr>
        </table>
        <a class="FrmPurebyu imgLink"><img src="" class="FrmPurebyu lblImg"
                style="text-align: left;width: 320px;height: 230px;" /></a>
        <table>
            <tr style="height:35px;">
                <td><label for="" class='FrmPurebyu txtMessage1 Enter Tab Tab3'
                        style="width: 325px;text-align:left"></label>
                </td>
            </tr>
        </table>
        <table>
            <tr style="height:35px;">
                <td><label for="" class='FrmPurebyu txtMessage2 Enter Tab Tab3'
                        style="width: 325px;text-align:left"></label>
                </td>
            </tr>
        </table>
        <table>
            <tr style="height:35px;">
                <td><label for="" class='FrmPurebyu txtMessage3 Enter Tab Tab3'
                        style="width: 325px;text-align:left"></label>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <button class="FrmPurebyu btnContact" style="width: 325px;height: 35px;display:none;">
                        コンタクト
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <button class="FrmPurebyu btnShich" style="width: 325px;height: 35px;display:none;">
                        試乗予約
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <button class="FrmPurebyu btnRuku" style="width: 325px;height: 35px;display:none;">
                        入庫予約
                    </button>
                </td>
            </tr>
        </table>
        <table style="padding-left: 75px;">
            <tr>
                <td>
                    <button class="FrmPurebyu btnClose" style="width: 180px;height: 35px;">
                        閉じる
                    </button>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<div id="tmpFileUpload"></div>
