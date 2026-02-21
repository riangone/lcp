<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmOshiraseJokenToroku'));
echo $this->Html->css(array('APPM/FrmOshiraseJokenToroku'));
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmOshiraseJokenToroku body' id="FrmOshiraseJokenToroku">
    <div class="FrmOshiraseJokenToroku header">
        <div class="FrmOshiraseJokenToroku hyojiymddiv">
            <table>
                <tr>

                    <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"> 表示日 </label></td>
                    <td>
                        <input type="text" class="FrmOshiraseJokenToroku hyojiymd" style="width:90px" maxlength="10" />
                    </td>

                    <td style="text-align: right;width: 150px;">
                        <button class='FrmOshiraseJokenToroku btn btnSet Enter Tab'>
                            設定
                        </button>
                    </td>

                </tr>
            </table>
        </div>
        <button class='FrmOshiraseJokenToroku btn btnToroku Enter Tab' style="width: 113px">
            登録
        </button>
        <button class='FrmOshiraseJokenToroku btn btnCancel Enter Tab'>
            キャンセル
        </button>
    </div>
    <div class="FrmOshiraseJokenToroku form">
        <div>
            <div class="FrmOshiraseJokenToroku ui-state-default ui-jqgrid-hdiv normalHeader">
                メッセージ
            </div>
            <table>
                <tbody>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM" style="width: 80px"> メッセージ </label>
                        </td>
                        <td colspan="7">
                            <input type="text" class="FrmOshiraseJokenToroku txtMesseJi" style="width: 380px;" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM" style="width: 80px"> 全件送付 </label>
                        </td>
                        <td colspan="1">
                            <input type="checkbox" class="FrmOshiraseJokenToroku zenkensofu"
                                style="width: 18px;height: 18px;" />
                        </td>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"> 表示時間 </label>
                            <input type="text" class="FrmOshiraseJokenToroku hyojihm" style="width:90px"
                                maxlength="5" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="FrmOshiraseJokenToroku table1">
            <div>
                <div class="FrmOshiraseJokenToroku normalHeader ui-state-default ui-jqgrid-hdiv">
                    個人属性
                </div>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM">性別</label></td>
                        <td><select class="FrmOshiraseJokenToroku seibetsu" style="width: 80px;"></select></td>
                        <td style="padding-left: 150px;"><label for=""
                                class="FrmOshiraseJokenToroku lbl-sky-xM">カテゴリ</label>
                        </td>
                        <td><select class="FrmOshiraseJokenToroku kategori" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM">年代</label></td>
                        <td><select class="FrmOshiraseJokenToroku nendaiFrom" style="width: 80px;"></select></td>
                        <td>～</td>
                        <td><select class="FrmOshiraseJokenToroku nendaiTo" style="width: 80px;"></select></td>
                        <td style="padding-left: 50px;"><label for=""
                                class="FrmOshiraseJokenToroku lbl-sky-xM">誕生月</label>
                        </td>
                        <td>
                            <select class="FrmOshiraseJokenToroku tanjyotuki" style="width: 80px;">
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
                <div class="FrmOshiraseJokenToroku normalHeader ui-state-default ui-jqgrid-hdiv">
                    車両属性
                </div>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM" style="width: 100px;">車種名</label>
                        </td>
                        <td>
                            <input class="FrmOshiraseJokenToroku shashuNm" style="width: 160px;" maxlength="30" />
                        </td>
                        <td style="padding-left: 50px;"><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 80px;">メーカー名</label></td>
                        <td><select class="FrmOshiraseJokenToroku makerNm" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM" style="width: 100px;">固定化区分</label>
                        </td>
                        <td><select class="FrmOshiraseJokenToroku koteikakbn" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM" style="width: 100px;">管理拠点</label>
                        </td>
                        <td><select class="FrmOshiraseJokenToroku kanrichimu" style="width: 80px;"></select></td>
                        <td style="padding-left: 50px;"><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 120px;">サービス管理拠点</label></td>
                        <td><select class="FrmOshiraseJokenToroku sabisuchimu" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM" style="width: 100px;">初度登録年月</label>
                        </td>
                        <td>
                            <input class="FrmOshiraseJokenToroku shonendotorokuym" style="width: 90px;" maxlength="6" />
                        </td>
                        <td style="padding-left: 50px;"><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 100px;">車検満了日</label></td>
                        <td>
                            <input class="FrmOshiraseJokenToroku shakenmanryoFrom" style="width: 90px;"
                                maxlength="10" />
                        </td>
                        <td> ～ </td>
                        <td>
                            <input class="FrmOshiraseJokenToroku shakenmanryoTo" style="width: 90px;" maxlength="10" />
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 200px;">パックdeメンテ現在加入</label>
                        </td>
                        <td><select class="FrmOshiraseJokenToroku pakkudementekanyu" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 200px;">（DZM）延長保証現在加入</label>
                        </td>
                        <td><select class="FrmOshiraseJokenToroku matsudaenchohoshokanyu" style="width: 80px;"></select>
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 200px;">ボディーコーティング現在加入</label></td>
                        <td><select class="FrmOshiraseJokenToroku bodeikoteingukanyu" style="width: 80px;"></select>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <div class="FrmOshiraseJokenToroku normalHeader ui-state-default ui-jqgrid-hdiv">
                    車検・点検属性
                </div>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM">点検</label></td>
                        <td><select class="FrmOshiraseJokenToroku tenken" style="width: 80px;"></select></td>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM">点検年月</label></td>
                        <td>
                            <input class="FrmOshiraseJokenToroku tenkenymd" style="width: 90px;" maxlength="6" />
                        </td>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 100px;">点検ステータス</label></td>
                        <td><select class="FrmOshiraseJokenToroku tenkensutetasu" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM">車検</label></td>
                        <td><select class="FrmOshiraseJokenToroku shaken" style="width: 80px;"></select></td>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM">車検年月</label></td>
                        <td>
                            <input class="FrmOshiraseJokenToroku shakenymd" style="width: 90px;" maxlength="6" />
                        </td>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 100px;">車検ステータス</label></td>
                        <td><select class="FrmOshiraseJokenToroku shakensutetasu" style="width: 80px;"></select></td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 200px;">車点検ＤＭ発信結果日時</label>
                        </td>
                        <td>
                            <input class="FrmOshiraseJokenToroku dmhasshinkekkaDateFrom" style="width: 90px;"
                                maxlength="10" />
                        </td>
                        <td> ～ </td>
                        <td>
                            <input class="FrmOshiraseJokenToroku dmhasshinkekkaDateTo" style="width: 90px;"
                                maxlength="10" />
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td><label for="" class="FrmOshiraseJokenToroku lbl-sky-xM"
                                style="width: 200px;">車点検ＤＭ発信結果タイプ名称</label></td>
                        <td><select class="FrmOshiraseJokenToroku dmhasshinkekkameisho" style="width: 80px;"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="FrmOshiraseJokenToroku footer">
        <button class='FrmOshiraseJokenToroku btn btnToroku Enter Tab' style="width: 113px">
            登録
        </button>
        <button class='FrmOshiraseJokenToroku btn btnCancel Enter Tab'>
            キャンセル
        </button>
    </div>
</div>
