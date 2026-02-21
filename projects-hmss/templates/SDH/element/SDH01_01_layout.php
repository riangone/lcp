<!-- /**
* 説明：
*
*
* @author zhenghuiyun
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
* 日付                Feature/Bug               内容                           担当
* YYYYMMDD           #ID                       XXXXXX                         FCSDL
* 20150526           ---                       新規                           FCSDL
* 20150609           ---                       判定文コピー機能追加           FCSDL
* 20150610           ---                       判定文コピー文言変更           HM
* --------------------------------------------------------------------------------------------
*/ -->
<?php
// echo $this -> Html -> script(array('SDH/SDH01_01'));
?>
<div class="<?php echo $hantei_class; ?>" style="width:100%;height: 100%">
    <!-- 20180129 lqs UPD S -->
    <!-- <table style="width:100%;height: 100%"> -->
    <table style="width:100%;">
        <!-- 20180129 lqs UPD E -->
        <tr style="width:100%;height: 100%">
            <td style="width:90%;height: 100%">
                <!-- ---20150610 upd s.#1911-2 --><!-- ---20150609 fanzhengzhou upd s.#1911-2 -->
                <!-- 20180408 YIN UPD S -->
                <!-- <div class="<?php echo $hanteinengetu_btn_class; ?>" style="width:10%;height: 100%;float: left;width: 20px;text-align: center;cursor: pointer;font-weight: normal;color: #ff4422;font-size:6px;"  title="前月の活動内容をコピー"> -->
                <div class="<?php echo $hanteinengetu_btn_class; ?>"
                    style="width:10%;height: 100%;float: left;width: 20px;text-align: center;cursor: pointer;font-weight: normal;color: #ff4422;font-size:10px;"
                    title="前月の活動内容をコピー">
                    <!-- 20180408 YIN UPD E -->
                    (写)
                </div>
                <div class="<?php echo $hanteinengetu_class; ?>"
                    style="width:80%;height: 100%;text-align: center;float: left;vertical-align: center">
                    <?php echo $hanteinengetu; ?>
                </div>
            </td>
            <!-- ---20150609 fanzhengzhou upd e.#1911-2 -->
            <!-- ---20150610 upd e.#1911-2 -->
            <td><a href="#" class="<?php echo $rireki_class; ?>"
                    style="width:100%;height: 100%;text-align: center;vertical-align: center"> </a></td>
        </tr>
        <tr style="width:100%;height: 100%">
            <td style="width:100%;height: 100%" colspan="2">
                <!-- 20160304 YIN UPS S -->
                <!-- <div style="width:100%;height: 100%;text-align: center"> -->
                <div class="<?php echo $result_class; ?>" style="width:100%;height: 100%;text-align: center">
                    <!-- 20160304 YIN UPS E -->
                    <input type="text" class="<?php echo $result_select_class; ?>" value="" style="width:100%" />
                    <!-- 20171123 lqs UPD S -->
                    <!-- <textarea class="<?php echo $result_text_class; ?>" style="width:100%" > -->
                    <textarea class="<?php echo $result_text_class; ?>" style="width:100%;overflow: auto">
                    <?php echo $result_text; ?>
                </textarea>
                    <!-- 20171123 lqs UPD E -->
                    <ul class="<?php echo $result_menu_class; ?>">
                        <?php
                        /**
                         * @var Cake\View\View $this
                         */
                        // App::uses('SDH01', 'Model/SDH');
                        use App\Model\SDH\SDH01;

                        $m_SDH01 = new SDH01();
                        $result = $m_SDH01->m_select_menu_top('');

                        $arrTop = array();
                        $arr2 = array();
                        //$arr3 = array();
                        
                        foreach ((array) $result['data'] as $key => $value) {
                            if ('0' == $value['MENU_TYPE']) {
                                $arrTop[$value['TEIKEI_CD']] = $value['ITEMNAME1'];
                                //$arr3[$value['TEIKEI_CD']] = $value['TOOLTIPTEXT'];
                            }

                            if ('1' == $value['MENU_TYPE']) {
                                $arr2[$value['TEIKEI_CD']] = $value['ITEMNAME2'];
                            }
                        }

                        $str = '';
                        foreach ($arrTop as $key => $value) {
                            $mark = false;
                            $str .= '<li rel="' . $key . '">';
                            //$str .= '<li rel="' . $key . '" title="' . $arr3[$key]   .  '">';
                            $str .= $value;
                            foreach ($arr2 as $key2 => $value2) {
                                if (substr($key, 0, 2) == substr($key2, 0, 2)) {
                                    $mark = true;
                                    break;
                                }
                            }
                            if ($mark) {
                                $str .= '<ul>';
                                foreach ($arr2 as $key2 => $value2) {
                                    if (substr($key, 0, 2) == substr($key2, 0, 2)) {
                                        $str .= '<li rel="' . $key2 . '">';
                                        $str .= $value2;
                                        $str .= '</li>';
                                    }
                                }
                                $str .= '</ul>';
                            }
                            $str .= '</li>';
                        }

                        echo $str;
                        // パス取得
                        // $strPath = dirname(dirname(dirname(dirname(__FILE__))));
                        // $filename = $strPath . "/Model/SDH/" . 'menu1.xml';
                        // $menu1 = fopen($filename, "r");
                        // $menu1Content = fread($menu1, filesize($filename));
                        // echo $menu1Content;
                        ?>
                    </ul>
                </div>
            </td>
        </tr>
    </table>
</div>