<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmUxJokenToroku'));
echo $this->Html->css(array('APPM/FrmUxJokenToroku'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmUxJokenToroku body' id="FrmUxJokenToroku">
    <div class="FrmUxJokenToroku header">
        <table class="FrmUxJokenToroku showDateBlock">
            <tr>
                <td><label for="" class="FrmUxJokenToroku lbl-sky-xM" style=" width: 100px;text-align:center"> 表示期間
                    </label></td>
                <td>
                    <input type="text" class="FrmUxJokenToroku displayDateFrom" style="width:95px" maxlength="10" />
                </td>
                <td> ～ </td>
                <td>
                    <input type="text" class="FrmUxJokenToroku displayDateTo" style="width:95px" maxlength="10" />
                </td>
                <td>
                    <button class='FrmUxJokenToroku btn btnSet Enter Tab' style=" margin-left: 15px">
                        設定
                    </button>
                </td>
            </tr>
            <!-- <tr style=" height: 5px"></tr> -->
        </table>
        <table style="padding-left:400px">
            <tr>
                <td>
                    <button class='FrmUxJokenToroku btn btnToroku Enter Tab' style=" width: 130px">
                        登録
                    </button>
                </td>
                <td>
                    <button class='FrmUxJokenToroku btn btnCancel Enter Tab' style=" width: 130px">
                        キャンセル
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <div class="FrmUxJokenToroku form">
        <div class="FrmUxJokenToroku DIVBLOCK1">
            <div class="FrmUxJokenToroku ui-state-default ui-jqgrid-hdiv normalHeader">
                メッセージ
            </div>
            <table>
                <tbody>
                    <tr>
                        <td><label for="" style=" width: 100px;text-align:center" class="FrmUxJokenToroku lbl-sky-xM">
                                メッセージ </label></td>
                        <td colspan="7">
                            <input type="text" class="FrmUxJokenToroku txtMesseJi" style="width: 450px;" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM" style=" width: 100px;text-align:center">
                                全件送付 </label></td>
                        <td>
                            <input type="checkbox" class="FrmUxJokenToroku allExpress"
                                style="width: 18px;height: 18px;" />
                        </td>
                        <td style="width:100px"></td>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM" style=" width: 70px;text-align:center;">
                                表示時間 </label></td>
                        <td>
                            <input type="text" class="FrmUxJokenToroku displayTimeFrom" style="width:80px"
                                maxlength="5" />
                        </td>
                        <td> ～ </td>
                        <td>
                            <input type="text" class="FrmUxJokenToroku displayTimeTo" style="width:80px"
                                maxlength="5" />
                        </td>
                        <td style=" width: 100px"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="FrmUxJokenToroku DIVBLOCK">
            <div>
                <div class="FrmUxJokenToroku normalHeader ui-state-default ui-jqgrid-hdiv">
                    個人属性
                </div>
                <table>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 100px;text-align:center">性別</label></td>
                        <td><select class="FrmUxJokenToroku gender" style="width: 80px;"></select></td>
                        <td style="padding-left: 150px;"><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 90px;text-align:center">カテゴリ</label></td>
                        <td><select class="FrmUxJokenToroku category" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 100px;text-align:center">年代</label></td>
                        <td><select class="FrmUxJokenToroku eraFrom" style="width: 80px;"></select></td>
                        <td>～</td>
                        <td><select class="FrmUxJokenToroku eraTo" style="width: 80px;"></select></td>
                        <td style="padding-left: 50px;"><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 90px;text-align:center">誕生月</label></td>
                        <td>
                            <select class="FrmUxJokenToroku birthday" style="width: 80px;">
                                <option></option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <div class="FrmUxJokenToroku normalHeader ui-state-default ui-jqgrid-hdiv">
                    車両属性
                </div>
                <table>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 100px;text-align:center">車種名</label></td>
                        <td>
                            <input class="FrmUxJokenToroku carName" style="width: 90px;" / maxlength="30">
                        </td>
                        <td style="padding-left: 50px;"><label for="" style=" width: 100px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">メーカー名</label></td>
                        <td><select class="FrmUxJokenToroku manufacture" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" style=" width: 100px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">固定化区分</label></td>
                        <td><select class="FrmUxJokenToroku classification" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" style=" width: 100px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">管理拠点</label></td>
                        <td><select class="FrmUxJokenToroku management" style="width: 80px;"></select></td>
                        <td style="padding-left: 50px;"><label for="" style=" width: 130px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">サービス管理拠点</label></td>
                        <td><select class="FrmUxJokenToroku serviceManagement" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" style=" width: 100px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">初度登録年月</label></td>
                        <td>
                            <input class="FrmUxJokenToroku loginYear" style="width: 90px;" maxlength="6" />
                        </td>
                        <td style="padding-left: 50px;"><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 100px;text-align:center">車検満了日</label></td>
                        <td>
                            <input class="FrmUxJokenToroku expirationDateFrom" style="width: 90px;" maxlength="10" />
                        </td>
                        <td> ～ </td>
                        <td>
                            <input class="FrmUxJokenToroku expirationDateTo" style="width: 90px;" maxlength="10" />
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" style=" width: 200px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">パックdeメンテ現在加入</label></td>
                        <td><select class="FrmUxJokenToroku packageMaintenance" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" style=" width: 200px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">（DZM）延長保証現在加入</label></td>
                        <td><select class="FrmUxJokenToroku masterMaintenance" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" style=" width: 200px;text-align:center"
                                class="FrmUxJokenToroku lbl-sky-xM">ボディーコーティング現在加入</label></td>
                        <td><select class="FrmUxJokenToroku bodyCoating" style="width: 80px;"></select></td>
                    </tr>
                </table>
            </div>
            <div>
                <div class="FrmUxJokenToroku normalHeader ui-state-default ui-jqgrid-hdiv">
                    車検・点検属性
                </div>
                <table>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 80px;text-align:center">点検</label></td>
                        <td>
                            <select class="FrmUxJokenToroku inspection" style="width: 80px;">
                                <option value=""></option>
                            </select>
                        </td>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 90px;text-align:center">点検年月</label></td>
                        <td>
                            <input class="FrmUxJokenToroku inspectionDate" style="width: 90px;" maxlength="6" />
                        </td>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 100px;text-align:center">点検ステータス</label></td>
                        <td><select class="FrmUxJokenToroku inspectionStatus" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 80px;text-align:center">車検</label></td>
                        <td>
                            <select class="FrmUxJokenToroku vehicleInspection" style="width: 80px;">
                                <option value=""></option>
                            </select>
                        </td>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 90px;text-align:center">車検年月</label></td>
                        <td>
                            <input class="FrmUxJokenToroku vehicleInspectionDate" style="width: 90px;" maxlength="6" />
                        </td>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 100px;text-align:center">車検ステータス</label></td>
                        <td><select class="FrmUxJokenToroku vehicleInspectionStatus" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 200px;text-align:center">車点検ＤＭ発信結果日時</label></td>
                        <td>
                            <input class="FrmUxJokenToroku vehicleInspectionResultDateFrom" style="width: 90px;"
                                maxlength="10" />
                        </td>
                        <td> ～ </td>
                        <td>
                            <input class="FrmUxJokenToroku vehicleInspectionResultDateTo" style="width: 90px;"
                                maxlength="10" />
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmUxJokenToroku lbl-sky-xM"
                                style=" width: 200px;text-align:center">車点検ＤＭ発信結果タイプ名称</label></td>
                        <td><select class="FrmUxJokenToroku vehicleInspectionName" style="width: 80px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="FrmUxJokenToroku footer">
        <table style="padding-left:400px" <tr>
            <td>
                <button class='FrmUxJokenToroku btn btnToroku Enter Tab' style=" width: 130px">
                    登録
                </button>
            </td>
            <td>
                <button class='FrmUxJokenToroku btn btnCancel Enter Tab' style=" width: 130px">
                    キャンセル
                </button>
            </td>
            </tr>
        </table>
    </div>
</div>
