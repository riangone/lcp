<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('PPRM/PPRM101ApproveAct'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>
<style type="text/css">
    .PPRM101ApproveAct.btn.disabled,
    .PPRM101ApproveAct.btn[disabled],
    fieldset[disabled] .PPRM101ApproveAct.btn {
        background-image: none !important;
        opacity: 0.35 !important
    }

    .PPRM101ApproveAct.ipt.disabled,
    .PPRM101ApproveAct.ipt[disabled],
    fieldset[disabled] .PPRM101ApproveAct.ipt {
        background-color: #BABEC1 !important
    }
    @media (-webkit-min-device-pixel-ratio: 1.5),
    (min-resolution: 1.5dppx) {
        .PPRM101ApproveAct .temp {
            height: 420px !important;
        }
    }

</style>
<div class='PPRM101ApproveAct body' id="PPRM101ApproveAct" style="width: 90%">
    <div>
        <table>
            <tr>
                <td style="width:5px"></td>
                <td style="width:100px"></td>
                <td style="width:120px"></td>
                <td style="width:50px"></td>
                <td style="width:100px"></td>
                <td style="width:200px"></td>
                <td style="width:50px"></td>
                <td style="width:100px"></td>
                <td style="width:120px"></td>
            </tr>
            <tr>
                <td></td>
                <td><label class='PPRM101ApproveAct lblTitle1 lbl-sky-xM' style="width: 100px"> 店舗 </label></td>
                <td>
                    <input class='PPRM101ApproveAct ipt lblTenpo  Tab' style="width: 120px" disabled="disabled" />
                </td>
                <td></td>
                <td><label class='PPRM101ApproveAct lblTitle2 lbl-sky-xM' style="width: 100px"> 日締日時 </label></td>
                <td>
                    <input class='PPRM101ApproveAct ipt lblHJMDate' style="width: 200px" disabled="disabled" />
                </td>
                <td></td>
                <td class='PPRM101ApproveAct tdlblTitle3' style="float:left">
                    <label class='PPRM101ApproveAct lblTitle3 lbl-sky-xM' style="width: 100px"> 日締№ </label>
                </td>
                <td class='PPRM101ApproveAct tdlblHJMNo'>
                    <input class='PPRM101ApproveAct ipt lblHJMNo  Tab' style="width: 119px" disabled="disabled" />
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width:5px"></td>
                <td style="width:150px"></td>
                <td style="width:150px"></td>
                <td style="width:150px"></td>
                <td style="width:150px"></td>
                <td style="width:30px"></td>
                <td style="width:230px"></td>
                <td style="width:100px"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class='PPRM101ApproveAct btn btnKeiri  Tab' style="width: 150px;height:25px;">
                        経理担当承認
                    </button>
                </td>
                <td>
                    <button class='PPRM101ApproveAct btn btnTencho  Tab' style="width: 150px;height:25px;">
                        店長承認
                    </button>
                </td>
                <td>
                    <button class='PPRM101ApproveAct btn btnKacho  Tab' style="width: 150px;height:25px;">
                        課長承認
                    </button>
                </td>
                <td>
                    <button class='PPRM101ApproveAct btn btnTantou  Tab' style="width: 150px;height:25px;">
                        担当承認
                    </button>
                </td>
                <td></td>
                <td>
                    <!-- 20180105 lqs UPD S -->
                    <!-- <button class='PPRM101ApproveAct btn btnKanren  Tab' style="width: 230px;height:25px;"> -->
                    <button class='PPRM101ApproveAct btn btnKanren  Tab' style="width: 230px;height:25px;display:none;">
                        <!-- 20180105 lqs UPD E -->
                        関連付いているイメージを確認する
                    </button>
                </td>
                <td>
                    <button class='PPRM101ApproveAct btn btnClose  Tab' style="width: 100px;height:25px;">
                        閉じる
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <iframe class="PPRM101ApproveAct temp" src="" style="width:100%;height:520px"></iframe>
    <div class="PPRM101ApproveAct_dialog"></div>

</div>
