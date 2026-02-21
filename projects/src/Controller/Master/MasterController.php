<?php

namespace App\Controller\Master;

use App\Controller\AppController;
use App\Model\Main\Main;
use mysqli;

//*******************************************
// * sample controller
//*******************************************
class MasterController extends AppController
{
    // public $autoRender = TRUE;
    public $Main;
    public $app_name;
    public $r4_name;
    //　デフォルトで最初に実行される機能
    public function index()
    {
        if (true == isset($_POST['func'])) {
            if ('check_login_state' == $_POST['func']) {
                $session = $this->request->getSession();
                $login_user = $session->read('login_user');
                if (null == $login_user) {
                    $this->render_login();
                } else {
                    $this->render_logined();
                }

                return;
            }
        }

        $this->set('title_for_layout', '（GD）（DZM）社内システム');

        // Viewファイル呼出し
        $this->render('/Master/index', 'Master_layout');
    }

    public function render_login()
    {
        $result = 'ng';
        $this->set('result', $result);
        $this->render('/Master/render_login');
    }

    public function render_logined()
    {
        // $this->set('login_visible', $hidden);

        $this->set('title_for_layout', '（GD）（DZM）社内システム');

        $tab_buttons = '';
        $tab_panels = '';

        $arr_cd = array();
        $session = $this->request->getSession();
        $user_id = $session->read('login_user');

        // MySQL DBへ接続
        $this->Main = new Main();
        $result = $this->Main->connMysql();
        $con = $result['data'];
        //mysql_query("set names utf8");
        if ($con instanceof mysqli)
            mysqli_set_charset($con, 'utf8');

        if ($result['result']) {
            //ユーザー権限FLG取得
            $arr_flg = array();
            $result = $this->Main->mainSys($user_id);
            $row = mysqli_fetch_assoc($result);
            //20180426 YIN UPD S
            // for ($i = 1; $i <= 10; $i++)
            for ($i = 1; $i <= 20; ++$i) {
                //20180426 YIN UPD E
                $flg_key = 'SYS' . $i . '_FLG';
                $arr_flg[$flg_key] = $row[$flg_key];
            }

            //ユーザーのシステムコード取得
            $arr_cd = array();
            //20180426 YIN UPD S
            // for ($i = 1; $i <= 10; $i++)
            for ($i = 1; $i <= 20; ++$i) {
                //20180426 YIN UPD E
                //権限FLGが"1"の場合、有効の場合
                $flg_key = 'SYS' . $i . '_FLG';
                if ('1' == $arr_flg[$flg_key]) {
                    //システムコードを取得
                    $cd_key = 'SYS' . $i . '_CD';
                    $arr_cd[$cd_key] = $row[$cd_key];

                    /*2014-01-26 qiuqiu add start*/
                    /*
                     if ($arr_cd[$cd_key] == '003') {
                     $this -> Session -> write('style_id', $row['STYLE_ID']);
                     $this -> Session -> write('pattern_id', $row['PATTERN_ID']);
                     }
                     * */

                    /*2014-01-26 qiuqiu add end*/
                }
            }
        }

        $cnt = count($arr_cd);
        $tabnamestring = '';
        $this->app_name = '';
        for ($i = 1; $i < $cnt + 1; ++$i) {
            $cd_key = 'SYS' . $i . '_CD';
            $sys_cd = $arr_cd[$cd_key];
            // 20140922 zhenghuiyun update start
            // switch($sys_cd)
            // {
            // case "000" :
            // break;
            // case "001" :
            // case "003" :
            // case "006" :
            // //20140814 zhenghuiyun add start
            // case "007" :
            // //20140814 zhenghuiyun add end
            // // case "902" :
            // $stmt = $this -> Main -> select_by_cd($sys_cd);
            // $row = mysqli_fetch_assoc($stmt);
            // $sys_name = $row['sys_name'];
            // $sys_key = $row['sys_key'];
            // $tabnamestring .= $sys_key . ",";
            // $tab_buttons .= '<li>';
            // $tab_buttons .= '<a href="#tabs_' . $sys_key . '">' . $sys_name . '</a>';
            // $tab_buttons .= '</li>';
            // $tab_panels .= '<div id="tabs_' . $sys_key . '"' . 'class="Main Main-' . $sys_key . ' tab-panel ui-tabs-panel ui-widget-content ui-corner-bottom ui-layout-container" style="overflow: hidden; position: relative; display: block;">';
            // $tab_panels .= '<span style="visibility:hidden">' . $sys_key . '</span>';
            // $tab_panels .= '</div>';
            //
            // if ($sys_cd == '003' || $sys_cd == '006')
            // {
            // $this -> r4_name = $sys_name;
            // }
            // else
            // {
            // $this -> r4_name = "";
            // }
            //
            // $this -> Session -> write('r4_name', $this -> r4_name);
            //
            // //var_dump($sys_name);
            // break;
            //
            // default :
            // // $stmt = $this -> Main ->select_by_cd($sys_cd);
            // // $row = mysqli_fetch_assoc($stmt);
            // // $sys_name = $row['sys_name'];
            // // $sys_key = $row['sys_key'];
            // //
            // // $tab_buttons .= '<li>';
            // // $tab_buttons .= '<a href="#tabs_' . $sys_key . '">' . $sys_name . '</a>';
            // // $tab_buttons .= '</li>';
            // // $tab_panels .= '<div id="tabs_' . $sys_key . '"' . 'class="Main Main-' . $sys_key . ' tab-panel ui-tabs-panel ui-widget-content ui-corner-bottom ui-layout-container" style="overflow: hidden; position: relative; display: block;">';
            // // $tab_panels .= '<span style="visibility:hidden">' . $sys_key . '</span>';
            // // $tab_panels .= '</div>';
            // // //var_dump($sys_name);
            // break;
            // }
            switch ($sys_cd) {
                case '000':
                    break;
                case '001':
                case '003':
                case '006':
                case '007':
                //---20161128 li INS S.
                // case '009':
                //---20161128 li INS E.
                //---20161229 li INS S.
                //---20170426 li DEL S.
                // case "013" :
                // case "014" :
                //---20170426 li DEL E.
                //---20161229 li INS E.
                case '008':
                    $stmt = $this->Main->select_by_cd($sys_cd);
                    $row = mysqli_fetch_assoc($stmt);
                    $sys_name = $row['sys_name'];
                    $sys_key = $row['sys_key'];
                    $tabnamestring .= $sys_key . ',';
                    $tab_buttons .= '<li>';
                    //20210310 WY UPD S
                    //$tab_buttons .= '<a href="#tabs_' . $sys_key . '">' . $sys_name . '</a>';
                    $tab_buttons .= '<a href="#tabs_' . $sys_key . '" class="Main tabClick">' . $sys_name . '</a>';
                    //20210310 WY UPD E
                    $tab_buttons .= '</li>';
                    $tab_panels .= '<div id="tabs_' . $sys_key . '"' . 'class="Main Main-' . $sys_key . ' tab-panel ui-tabs-panel ui-widget-content ui-corner-bottom ui-layout-container" style="overflow: hidden; position: relative; display: block;">';
                    $tab_panels .= '<span style="visibility:hidden">' . $sys_key . '</span>';
                    $tab_panels .= '</div>';
                    break;
                // case '010':
                //---20170426 li INS S.
                // case '013':
                //---20170426 li INS E.
                //---20170710 li INS S.
                //20210112 YIN INS S
                case '027':
                case '017':
                case '018':
                // 20220617 YIN INS S
                case '019':
                // 20220617 YIN INS E
                // 20230626 YIN INS S
                case "031":
                // 20230626 YIN INS E
                case '015':
                    //---20170710 li INS E.
                    $stmt = $this->Main->select_by_cd($sys_cd);
                    $row = mysqli_fetch_assoc($stmt);
                    $sys_name = $row['sys_name'];
                    $sys_key = $row['sys_key'];
                    $tabnamestring .= $sys_key . ',';
                    $tab_buttons .= '<li>';
                    //20210310 WY UPD S
                    //$tab_buttons .= '<a href="#tabs_' . $sys_key . '">' . $sys_name . '</a>';
                    $tab_buttons .= '<a href="#tabs_' . $sys_key . '" class="Main tabClick">' . $sys_name . '</a>';
                    //20210310 WY UPD E
                    $tab_buttons .= '</li>';
                    $tab_panels .= '<div id="tabs_' . $sys_key . '"' . 'class="Main Main-' . $sys_key . ' tab-panel ui-tabs-panel ui-widget-content ui-corner-bottom ui-layout-container" style="overflow: hidden; position: relative; display: block;">';
                    $tab_panels .= '<span style="visibility:hidden">' . $sys_key . '</span>';
                    $tab_panels .= '</div>';
                    break;
                //---20190418 yuan INS S.
                case '016':
                    $stmt = $this->Main->select_by_cd($sys_cd);
                    $row = mysqli_fetch_assoc($stmt);
                    $sys_name = $row['sys_name'];
                    $sys_key = $row['sys_key'];
                    $tabnamestring .= $sys_key . ',';
                    $tab_buttons .= '<li>';
                    //20210310 WY UPD S
                    //$tab_buttons .= '<a href="#tabs_' . $sys_key . '">' . $sys_name . '</a>';
                    $tab_buttons .= '<a href="#tabs_' . $sys_key . '" class="Main tabClick">' . $sys_name . '</a>';
                    //20210310 WY UPD E
                    $tab_buttons .= '</li>';
                    $tab_panels .= '<div id="tabs_' . $sys_key . '"' . 'class="Main Main-' . $sys_key . ' tab-panel ui-tabs-panel ui-widget-content ui-corner-bottom ui-layout-container" style="overflow: hidden; position: relative; display: block;">';
                    $tab_panels .= '<span style="visibility:hidden">' . $sys_key . '</span>';
                    $tab_panels .= '</div>';
                    break;
                //---20190418 yuan INS E.
                default:
                    break;
            }
            switch ($sys_cd) {
                case '000':
                    break;
                case '003':
                case '006':
                    //---20161128 li INS S.
                    // case '009':
                    //---20161128 li INS E.
                    $this->r4_name = $sys_name;
                    $session->write('r4_name', $this->r4_name);
                    break;
                default:
                    break;
            }
            //---20161229 li INS S.
            switch ($sys_cd) {
                case '000':
                    break;
                // case '013':
                //---20170425 li INS S.
                // case '014':
                //---20170425 li INS E.
                //---20170710 li INS S.
                case '015':
                    //---20170710 li INS E.
                    $this->app_name = $sys_name;
                    $session->write('app_name', $this->app_name);
                    break;
                default:
                    break;
            }
            //---20161229 li INS E.
            // 20140922 zhenghuiyun update end
        }
        $this->set('tab_buttons', $tab_buttons);
        $this->set('tab_panels', $tab_panels);
        $this->set('tabnamestring', $tabnamestring);
        $this->set('r4_name', $this->r4_name);
        //---20161229 li INS S.
        $this->set('app_name', $this->app_name);
        //---20161229 li INS E.
        // Viewファイル呼出し
        $this->render('/Master/index', 'Main_layout');
    }

    public function getXml()
    {
        $this->viewBuilder()->setLayout(false);
        $result = '';
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            // パス取得
            $strPath = dirname(dirname(__FILE__));
            $filename = $strPath . '/R4/Component/' . 'HMMsg.xml';
            // 値取得
            $xml = simplexml_load_file($filename);
            // XMLの取得
            $result = $xml;
        } else {
            // エラー処理
            $result = array(
                'result' => 'false',
                'data' => 'no ajax request',
            );
        }
        $this->fncReturn($result);
    }
}