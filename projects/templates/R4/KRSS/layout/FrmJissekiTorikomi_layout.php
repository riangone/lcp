<!-- /**
* 説明：
*
*
* @author lijun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                   Feature/Bug                 内容                                担当
* YYYYMMDD                  #ID                     XXXXXX                             FCSDL
* 20160511                  #2437                   実績取込機能改修                      Sun
* --------------------------------------------------------------------------------------------
*/ -->
<!DOCTYPE html>
<?php
/**
 * @var Cake\View\View $this
 */
echo $this->Html->script(array("R4/KRSS/FrmJissekiTorikomi/FrmJissekiTorikomi"));
?>
<div id="FrmJissekiTorikomi" class="KRSS FrmJissekiTorikomi R4-content">
    <div style="margin-top: 10px">
        <table>
            <tr>
                <td>
                    <input type="radio" class="KRSS FrmJissekiTorikomi comment  Enter Tab" name="rad" checked="checked">
                    コメント
                </td>
                <td>
                    <!--
                <input  type="radio" class="KRSS FrmJissekiTorikomi service  Enter Tab" name="rad"  disabled="disabled">
                サービス実績 -->
                </td>
                <td>
                    <input type="radio" class="KRSS FrmJissekiTorikomi hoken  Enter Tab" name="rad">
                    保険実績
                </td>
                <!-- 20160511 Sun Add Start -->
                <td>
                    <input type="radio" class="KRSS FrmJissekiTorikomi tougetueigyo  Enter Tab" name="rad">
                    台数（営業）
                </td>
                <td>
                    <input type="radio" class="KRSS FrmJissekiTorikomi tougetusabisu  Enter Tab" name="rad">
                    台数（サービス）
                </td>
                <!-- 20160511 Sun Add End -->
                <!-- 20161012 Sun Add Start -->
                <td>
                    <input type="radio" class="KRSS FrmJissekiTorikomi tougetuchuko  Enter Tab" name="rad">
                    台数（中古）
                </td>
                <!-- 20161012 Sun Add End -->
                <td>
                    <input type="radio" class="KRSS FrmJissekiTorikomi other  Enter Tab" name="rad">
                    その他
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top: 10px">
        <table>
            <tr>
                <td>取込ファイル</td>
                <td>
                    <input type="text" class="KRSS FrmJissekiTorikomi txtFile Enter Tab" style="width: 400px;"
                        disabled="disabled" />
                    <button class="KRSS FrmJissekiTorikomi fileopen Enter Tab">
                        参照
                    </button>
                </td>

            </tr>
        </table>
    </div>
    <div class="HMS-button-pane" style="margin-top: 20px">
        <div class="HMS-button-set">
            <button class="KRSS FrmJissekiTorikomi cmdAction">
                登録
            </button>
            <button class="KRSS FrmJissekiTorikomi cancel">
                キャンセル
            </button>
        </div>
    </div>
</div>
<div id="tmpFileUpload"></div>