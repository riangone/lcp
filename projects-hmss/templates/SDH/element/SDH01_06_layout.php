<!-- /**
* 説明：
*
*
* @author jinmingai
* @copyright (GD) (ZM)
* @package default
*
* 履歴：
* --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           ---                       新規                           FCSDL
 * 20160127           #2373                   	依頼                            li
* --------------------------------------------------------------------------------------------
*/ -->
<!-- 20180129 lqs UPD S -->
<!-- <table style="width:100%;height: 100%;background-color: #AAAADD" border="0"> -->
<table style="width:100%;background-color: #AAAADD" border="0">
    <!-- 20180129 lqs UPD E -->
    <tr style="width:100%">
        <td style="width:90%;height: 100%">
            <!-- -- 20160127 li UPD S -->
            <!-- <div style="width:100%;height: 100%;text-align: center"> -->
            <div class="sdh sdh01 titleName_08" style="width:100%;height: 100%;text-align: center">
                <!-- -- 20160127 li UPD E -->
                最終結果：
            </div>
        </td>
        <td>
            <a href="#" class="sdh sdh01 btn_rireki_08 tooltip yellow-tooltip"
                style="width:100%;height: 100%;text-align: center"> </a>
        </td>
    </tr>
    <tr style="width:100%">
        <td style="width:100%;height: 100%" colspan="2">
            <div class="sdh sdh01 result_08" style="width:100%;height: 100%;text-align: center">
                <input type="text" class="sdh sdh01 result_select_08" value="" style="width:100%" />
                <!-- 20180129 lqs UPD S -->
                <!-- <button class="sdh sdh01 result_button_08" style="display: none"> -->
                <button class="sdh sdh01 result_button_08 OprationButton" style="display: none;height:20px">
                    <!-- 20180129 lqs UPD E -->
                    新注文書
                </button>
                <!-- 20171123 lqs UPD S -->
                <!-- <textarea class="sdh sdh01 result_text_08" style="width:100%" ></textarea> -->
                <textarea class="sdh sdh01 result_text_08" style="width:100%;overflow: auto"></textarea>
                <!-- 20171123 lqs UPD E -->
                <ul class="sdh sdh01 result_menu_08  mcdropdown_menu ">
                    <?php
                    // App::uses('SDH01', 'Model/SDH');
                    
                    // $this -> m_SDH01 = new SDH01();
                    //--- 20160127 li INS S
                    // $result = $this -> m_SDH01 -> m_select_menuLast_top();
                    $result = array
                    (
                        'result' => 1,
                        'data' => array
                        (
                            0 => array
                            (
                                'ITEMNAME1' => '代替促進◎',
                                'TEIKEI_CD' => 2400,
                                'MENU_TYPE' => 0,
                                'ITEMNAME2' => null
                            ),

                            1 => array
                            (
                                'ITEMNAME1' => '代替促進◎',
                                'TEIKEI_CD' => 2406,
                                'MENU_TYPE' => 1,
                                'ITEMNAME2' => 'ＣＸ－３'
                            ),

                            2 => array
                            (
                                'ITEMNAME1' => '代替促進◎',
                                'TEIKEI_CD' => 2407,
                                'MENU_TYPE' => 1,
                                'ITEMNAME2' => 'ＣＸ－５'
                            ),

                            3 => array
                            (
                                'ITEMNAME1' => '代替促進◎',
                                'TEIKEI_CD' => 2408,
                                'MENU_TYPE' => 1,
                                'ITEMNAME2' => 'ＣＸ－８'
                            ),

                            4 => array
                            (
                                'ITEMNAME1' => '代替促進◎',
                                'TEIKEI_CD' => 2409,
                                'MENU_TYPE' => 1,
                                'ITEMNAME2' => 'ＣＸ－３０'
                            ),

                            5 => array
                            (
                                'ITEMNAME1' => '代替促進◎',
                                'TEIKEI_CD' => 2410,
                                'MENU_TYPE' => 1,
                                'ITEMNAME2' => 'Ｍａｚｄａ２'
                            )

                        ),

                        'row' => 6
                    );
                    // if ($con4 == "1" or $con4 == "2")
                    // {
                    // $result = $this -> m_SDH01 -> m_select_menuLast_top_sinsya();
                    // }else if ($con4 == "0"){
                    // $result = $this -> m_SDH01 -> m_select_menuLast_top();
                    // }
                    //--- 20160127 li INS E
                    
                    $arrTop = array();
                    $arr2 = array();

                    foreach ($result['data'] as $key => $value) {

                        if ($value['MENU_TYPE'] == '0') {
                            $arrTop[$value['TEIKEI_CD']] = $value['ITEMNAME1'];
                        }

                        if ($value['MENU_TYPE'] == '1') {
                            $arr2[$value['TEIKEI_CD']] = $value['ITEMNAME2'];
                        }
                    }
                    $str = "";

                    foreach ($arrTop as $key => $value) {
                        $mark = FALSE;
                        $str .= '<li rel="' . $key . '">';
                        $str .= $value;
                        foreach ($arr2 as $key2 => $value2) {
                            if (substr($key, 0, 2) == substr($key2, 0, 2)) {
                                $mark = TRUE;
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
                    ?>
                </ul>
            </div>
        </td>
    </tr>
</table>