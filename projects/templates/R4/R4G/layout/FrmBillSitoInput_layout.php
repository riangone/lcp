<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('R4/R4G/FrmBillSitoInput/FrmBillSitoInput'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style>
    .FrmBillSitoInput.lbl-static {
        display: block;
        float: left;
        width: 100px;
    }

    .FrmBillSitoInput.footer {
        border-top: 1px solid #a6c9e2;
        width: 100%;
    }

    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .FrmBillSitoInput.txtBillSito {
            width: 90px !important;
        }
    }
</style>
<div class='FrmBillSitoInput'>
    <div class='FrmBillSitoInput R4-content listArea'>
        <div>
            <table border="0" style="margin-left: 15px;margin-top: 20px;">
                <tr>
                    <td>
                        <label class="FrmBillSitoInput lbl-static" style="border: 0px;" for="">
                            注文書番号
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmBillSitoInput Tab Enter txtCMN_NO'
                            name="FrmBillSitoInput_txtCMN_NO" maxlength="10" onfocusin="this.select()" />
                    </td>
                </tr>
            </table>
        </div>
        <div style="border: 1px solid #a6c9e2; width:80%;margin:10px;padding: 15px;" class='HMS-circle-conner'>
            <table cellspacing="0">
                <tr style="height:40px;">
                    <td>
                        <label class="FrmBillSitoInput lbl-static" for="">
                            UCNO
                        </label>
                    </td>
                    <td>
                        <input type='text' class='FrmBillSitoInput txtUCNO' disabled="disabled"
                            name="FrmBillSitoInput_txtUCNO" />
                    </td>
                </tr>
                <tr style="height:25px;">
                    <td>
                        <label class="FrmBillSitoInput lbl-static" for="">
                            契約者
                        </label>
                    </td>
                    <td>
                        <input class='FrmBillSitoInput txtKeiyakusya' name="FrmBillSitoInput_txtKeiyakusya" type='text'
                            disabled="disabled" />
                    </td>
                </tr>
                <tr style="height:25px;">
                    <td>
                        <label class="FrmBillSitoInput lbl-static" style="margin:7px auto;" for="">
                            使用者
                        </label>
                    </td>
                    <td>
                        <input class='FrmBillSitoInput txtSiyosya' type='text' disabled="disabled"
                            name="FrmBillSitoInput_txtSiyosya" />
                    </td>
                </tr>
                <tr style="height:25px;">
                    <td>
                        <label class="FrmBillSitoInput lbl-static" for="">
                            使用者カナ
                        </label>
                    </td>
                    <td>
                        <input class='FrmBillSitoInput txtSiyosyaKN' type='text' disabled="disabled"
                            name="FrmBillSitoInput_txtSiyosyaKN" />
                    </td>
                </tr>
                <tr style="height:40px;">
                    <td>
                        <label class="FrmBillSitoInput lbl-static" for="">
                            割賦元金
                        </label>
                    </td>
                    <td>
                        <input class='FrmBillSitoInput txtKaptes' type='text' disabled="disabled" dir="rtl"
                            name="FrmBillSitoInput_txtKaptes" />
                    </td>
                </tr>
                <tr style="height:40px;">
                    <td>
                        <label class="FrmBillSitoInput lbl-static" for="">
                            手形据置日数
                        </label>
                    </td>
                    <td>
                        <input class='FrmBillSitoInput Tab Enter txtBillSito' type='text' maxlength="5"
                            name="FrmBillSitoInput_txtBillSito" style="text-align: right;width: 120px;" />
                    </td>
                </tr>
            </table>
        </div>
        <div class="HMS-button-pane">
            <div class='HMS-button-set'>
                <button class='FrmBillSitoInput Tab Enter btnAction' id='btnAction'>
                    更新
                </button>
                <button class='FrmBillSitoInput Tab Enter btnDelete'>
                    削除
                </button>
            </div>
        </div>
    </div>
</div>
