<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array('APPM/FrmMessejiToroku'));

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
?>

<div class='FrmMessejiToroku body' id="FrmMessejiToroku" style="width: 1000px;">
    <!-- 20170519 LQS INS S -->
    <div class="FrmMessejiToroku block">
        <!-- 20170519 LQS INS E -->
        <div class="FrmMessejiToroku msgDateBlock">
            <table style=" margin-top: 10px">
                <tr style="height:20px;">
                    <td><label for="" class="FrmMessejiToroku lblMKikan lbl-sky-xM"
                            style="text-align: center;width: 150px;">メッセージ利用期間 </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtMFromKikan Enter Tab Tab3' style="width: 150px;"
                            maxlength="10">
                    </td>
                    <td>～</td>
                    <td>
                        <input class='FrmMessejiToroku txtMToMKikan Enter Tab Tab3' style="width: 150px;"
                            maxlength="10">
                    </td>
                    <td style="width: 50px"></td>
                    <td>
                        <button class="FrmMessejiToroku btnSearch" style="width: 70px">
                            設定
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div class="FrmMessejiToroku detailBlock" style="margin-top: 10px">
            <div class="FrmMessejiToroku ui-state-default ui-jqgrid-hdiv normalHeader"
                style="height: 25px;line-height:25px">
                メッセージ情報
            </div>
            <table style="margin-top: 10px">
                <tr style="height:30px;" class="FrmMessejiToroku tr1">
                    <td><label for="" class="FrmMessejiToroku lblContent lbl-sky-xM"
                            style="text-align: center;width: 100px;">内容区分 </label></td>
                    <td><select style="width: 130px;" class="FrmMessejiToroku txtContent"></select></td>
                    <td style="width:15px;"></td>
                    <td><label for="" class="FrmMessejiToroku lblCode lbl-sky-xM"
                            style="text-align: center;width: 120px;">メッセージコード </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtCode Enter Tab Tab3' style="width: 130px;text-align: center;">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblKidoku lbl-sky-xM"
                            style="text-align: center;width: 100px;">既読
                        </label></td>
                    <td><select style="width: 130px;" class="FrmMessejiToroku txtKidoku"></select></td>
                    <td style="width:15px;"></td>
                    <td><label for="" class="FrmMessejiToroku lblMogiri lbl-sky-xM"
                            style="text-align: center;width: 120px;">もぎり </label></td>
                    <td><select style="width: 135px;" class="FrmMessejiToroku txtMogiri"></select></td>
                </tr>
            </table>

            <table>
                <tr style="height:30px;">
                    <td><!-- 20170508 WANG UPD S <label for="" class="FrmMessejiToroku lblKKikan lbl-sky-xM" style="text-align: center;width: 150px;">クーポン期限 </label> 20170508 WANG UPD E --><label
                            for="" class="FrmMessejiToroku lblKKikan lbl-sky-xM"
                            style="text-align: center;width: 150px;">クーポン期間 </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtKFromKikan Enter Tab Tab3' style="width: 150px;"
                            maxlength="10">
                    </td>
                    <td>～</td>
                    <td>
                        <input class='FrmMessejiToroku txtKToKikan Enter Tab Tab3' style="width: 150px;" maxlength="10">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblShaken lbl-sky-xM"
                            style="text-align: center;width: 150px;">車検点検情報区分 </label></td>
                    <td>
                        <select style="width: 300px;" class="FrmMessejiToroku txtShaken">
                        </select>
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblSharyo lbl-sky-xM"
                            style="text-align: center;width: 150px;">車両情報表示 </label></td>
                    <td><select style="width: 200px;" class="FrmMessejiToroku txtSharyo"></select></td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblTitle lbl-sky-xM"
                            style="text-align: center;width: 150px;">タイトル </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtTitle Enter Tab Tab3' style="width: 450px;" maxlength="100">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblImg lbl-sky-xM"
                            style="text-align: center;width: 150px;">メイン画像
                        </label></td>
                    <td><!-- 20170505 WANG UPD S
                    <input class='FrmMessejiToroku txtImg Enter Tab Tab3' style="width: 280px;">	 -->
                        <input class='FrmMessejiToroku txtImg Enter Tab Tab3' style="width: 400px;" disabled="disabled">
                        <!-- 20170505 WANG UPD E -->
                    <td>
                        <button class="FrmMessejiToroku btnSansho">
                            参照
                        </button>
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblImgUrl lbl-sky-xM"
                            style="text-align: center;width: 150px;">メイン画像URL </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtImgUrl Enter Tab Tab3' style="width: 450px;" maxlength="300">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblMessage1 lbl-sky-xM"
                            style="text-align: center;width: 150px;">メッセージ内容1 </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtMessage1 Enter Tab Tab3' style="width: 450px;"
                            maxlength="200">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblMessage2 lbl-sky-xM"
                            style="text-align: center;width: 150px;">メッセージ内容2 </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtMessage2 Enter Tab Tab3' style="width: 450px;"
                            maxlength="200">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblMessage3 lbl-sky-xM"
                            style="text-align: center;width: 150px;">メッセージ内容3 </label></td>
                    <td>
                        <input class='FrmMessejiToroku txtMessage3 Enter Tab Tab3' style="width: 450px;"
                            maxlength="200">
                    </td>
                </tr>
            </table>
            <table>
                <tr style="height:30px;">
                    <td><label for="" class="FrmMessejiToroku lblRinku lbl-sky-xM"
                            style="text-align: center;width: 150px;">コンタクト　リンク </label></td>
                    <td><select style="width: 70px;" class="FrmMessejiToroku txtRinku"></select></td>
                    <td><label for="" class="FrmMessejiToroku lblShi lbl-sky-xM"
                            style="text-align: center;width: 100px;">試乗予約ボタン </label></td>
                    <td><select style="width: 70px;" class="FrmMessejiToroku txtShi"></select></td>
                    <td><label for="" class="FrmMessejiToroku lblRu lbl-sky-xM"
                            style="text-align: center;width: 100px;">入庫予約ボタン </label></td>
                    <td><select style="width: 70px;" class="FrmMessejiToroku txtRu"></select></td>
                </tr>
            </table>
        </div>

        <!-- 20170519 LQS INS S -->
    </div>
    <!-- 20170519 LQS INS E -->

    <table style="height:30px;padding-left: 350px;">
        <tr style="height: 20px;"></tr>
        <tr>
            <td>
                <button class="FrmMessejiToroku btnRebu" style=" width: 100px">
                    プレビュー
                </button>
            </td>
            <td style="width: 10px;"></td>
            <td>
                <button class="FrmMessejiToroku btnTouroku" style=" width: 100px">
                    登録
                </button>
            </td>
            <td style="width: 10px;"></td>
            <td>
                <button class="FrmMessejiToroku btnCancel" style=" width: 100px">
                    キャンセル
                </button>
            </td>
        </tr>
    </table>
</div>
<div id="tmpFileUpload"></div>
<div id="FrmMToroku_dialog" class="FrmMessejiToroku FrmPurebyu_dialog"></div>
