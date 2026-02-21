<?php
/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150526           -----                      新規                           FCSDL
 * 20160127           #2373                     依頼                            li
 * 20160402           -----                     新１新６ 最終結果コンボ順序変更                      HM
 * 20190226           #2870                     依頼                            ci
 * 20210219           \99.提供資料\20210217\20210217_SDH_ログイン後の仕様変更.xlsx                       依頼                           CI
 * 20220121           機能追加　　　　　　          N6対応                          Sun
 * 20220218           機能追加　　　　　　          20220212ーN6対応指摘事項(No14)    YIN
 * --------------------------------------------------------------------------------------------
 */

namespace App\Controller\SDH;

use App\Controller\AppController;
use App\Model\SDH\SDH02;
use App\Model\SDH\SDH01;

/**
 * 車検代替判定画面
 * SDH02Controller.
 */
class SDH02Controller extends AppController
{
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    public $autoLayout = true;
    // public $autoRender = false;
    public $layout;
    private $tenpo_cd = '';
    private $m_SDH01;
    private $m_SDH02;
    private $Session;

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    public function index()
    {
        $post_data = null;

        if (isset($_POST['data'])) {
            $post_data = $_POST['data'];
            $this->tenpo_cd = $post_data['tenpo_cd'];
        }

        $this->layout = 'SDH02_layout';
        $this->m_SDH02 = new SDH02();
        //20210224 CI UPD S
        //$busyo_option_list = "";
        $busyo_option_list_result = $this->get_sdh02_busyo_index();
        $busyo_option_list = $busyo_option_list_result['busyo_list'];
        //20210224 CI UPD E
        //本部ユーザ
        if ('' == $this->tenpo_cd || 'honbu' == $this->tenpo_cd) {
            //20210219 CI UPD S
            // $busyo_option_list = $this -> get_sdh02_busyo_index();
            // $this -> set("busyo_option_list", $busyo_option_list);
            // $this -> set("busyo_name", "");
            // $this -> set("busyo_cd", "");

            //ipアドレス取得
            $ip = $this->request->clientIp();
            //test
            //$ip = "192.168.330.2";
            //test
            //店舗コード取得
            //拠点IPアドレスマスタ（HKTNIPTABLES）を検索
            $busyo_name = $this->m_SDH02->gethonbudata($ip);
            $data = $busyo_name['data'];
            //該当データありの場合
            if (count((array) $data) > 0) {
                //店舗取得
                $result = $this->m_SDH02->m_select_busyo($data[0]['IP_ADDRESS']);
                //店舗選択
                if (count((array) $result['data']) > 0) {
                    $busyo_option_list_result = $this->get_sdh02_busyo_index($result['data'][0]['BUSYO_CD']);
                    $busyo_option_list = $busyo_option_list_result['busyo_list'];
                }
            }
            $this->set('busyo_option_list', $busyo_option_list);
            $this->set('sel_busyo_visible', '');
            //$this -> set("lbl_busyo_visible", "display:none");
            $this->set('nengetu02', date('Ym'));
            //20210219 CI UPD E
        }
        //一般ユーザ
        else {
            //20210219 CI UPD S
            // $result = $this -> m_SDH02 -> m_select_busyo($this -> tenpo_cd);
            // $data = $result['data'];
            // $this -> set("busyo_option_list", "");
            // $this -> set("busyo_name", $data[0]["BUSYO_RYKNM"]);
            // $this -> set("busyo_cd", $data[0]["BUSYO_CD"]);

            //ログインユーザID取得
            $this->Session = $this->request->getSession();
            $userid = $this->Session->read('login_user');
            //店舗コード取得
            //ログインユーザーIDを条件に、社員配属マスタ（ HHAIZOKU ）を検索
            $busyo_name = $this->m_SDH02->getyippanndata($userid);
            $data = $busyo_name['data'];
            //該当データありの場合
            if (count((array) $data) > 0) {
                //店舗取得
                $result = $this->m_SDH02->m_select_busyo($data[0]['BUSYO_CD']);
                //店舗選択
                if (count((array) $result['data']) > 0) {
                    $busyo_option_list_result = $this->get_sdh02_busyo_index(substr($result['data'][0]['BUSYO_CD'], 0, 2));
                    $busyo_option_list = $busyo_option_list_result['busyo_list'];
                    if (true == $busyo_option_list_result['flag']) {
                        $this->set('sel_busyo_visible', 'disabled');
                    } else {
                        $this->set('sel_busyo_visible', '');
                    }
                } else {
                    $this->set('sel_busyo_visible', '');
                }
            } else {
                $this->set('sel_busyo_visible', '');
            }
            $this->set('busyo_option_list', $busyo_option_list);
            //	$this -> set("lbl_busyo_visible", "display:block");
            $this->set('nengetu02', date('Ym'));
            //20210219 CI UPD E
        }
        $option_list1 = $this->getActionSitu();
        $this->set('option_list1', $option_list1);

        //----20220121 sun add s
        $option_list1m = $this->getActionSitu(true);
        $this->set('option_list1m', $option_list1m);
        //----20220121 sun add e

        $option_list2 = $this->getLastSitu();
        $this->set('option_list2', $option_list2);

        $user_option_list = $this->get_tenpo_option_hazime_list($this->tenpo_cd);
        $this->set('user_option_list', $user_option_list);
        // Viewファイル呼出し
        $this->render('/SDH/SDH02/index', $this->layout);
    }

    /**
     * 活動状況の追加.
     */
    //----20220121 sun upd s
    //public function getActionSitu()
    public function getActionSitu($daitai = false)
    {
        // App::uses('SDH01', 'Model/SDH');
        $this->m_SDH01 = new SDH01();
        //$result = $this -> m_SDH01 -> m_select_menu_top("");
        if ($daitai) {
            $result = $this->m_SDH01->m_select_menu_topdaitai('');
        } else {
            $result = $this->m_SDH01->m_select_menu_top('');
        }

        $option_list = '';
        $option_tmp = '<option value ="{#val}">{#txt}</option>';
        $find = array(
            '{#val}',
            '{#txt}',
        );
        $replace = array(
            '000',
            '指定なし',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;
        //20220218 YIN INS S
        $optionsArr = array();
        //20220218 YIN INS E
        foreach ((array) $result['data'] as $value) {
            if ('0' == $value['MENU_TYPE']) {
                //20220218 YIN INS S
                array_push($optionsArr, $value);
                //20220218 YIN INS E
                $val = $value['TEIKEI_CD'];
                $txt = $value['ITEMNAME1'];

                $replace = array(
                    $val,
                    $txt,
                );
                $option = str_replace($find, $replace, $option_tmp);
                $option_list .= $option;
            }
        }
        // 20220218 YIN INS S
        $this->set('list1m_options', json_encode($optionsArr));
        // 20220218 YIN INS E
        return $option_list;
    }

    //----20220121 sun upd e

    /**
     * 最終結果の追加.
     */
    public function getLastSitu()
    {
        // App::uses('SDH01', 'Model/SDH');
        $this->m_SDH01 = new SDH01();
        $result = $this->m_SDH01->m_select_menuLast_top();
        $option_list = '';
        $option_tmp = '<option value ="{#val}">{#txt}</option>';
        $find = array(
            '{#val}',
            '{#txt}',
        );
        $replace = array(
            '000',
            '指定なし',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;
        foreach ((array) $result['data'] as $value) {
            if ('0' == $value['MENU_TYPE']) {
                $val = $value['TEIKEI_CD'];
                $txt = $value['ITEMNAME1'];
                $replace = array(
                    $val,
                    $txt,
                );
                $option = str_replace($find, $replace, $option_tmp);
                $option_list .= $option;
            }
        }

        return $option_list;
    }

    /**
     * 検索条件変更_店舗.
     */
    public function sDH02()
    {
        $post_data = $_POST['data'];
        $kyotn_cd = $post_data['tenpo_cd'];
        $this->m_SDH02 = new SDH02();
        //拠点コードを取得 e
        $user_option_list = '';
        $user_option_list = $this->get_tenpo_option_list($kyotn_cd);

        $this->set('user_option_list', $user_option_list);
        $this->render('/SDH/SDH02/sdh02selbusyo');
    }

    //--- 20160127 li INS S

    /**
     * 最終結果の追加.
     */
    public function get_tenpo_option_list2($item_type)
    {
        // App::uses('SDH01', 'Model/SDH');
        $this->m_SDH01 = new SDH01();

        $result = $this->m_SDH01->m_select_option_list2($item_type);
        $option_list = '';
        $option_tmp = '<option value ="{#val}">{#txt}</option>';
        $find = array(
            '{#val}',
            '{#txt}',
        );
        //$replace = array(
        //	"998",
        //	"未入庫のみ"
        //);
        //$option = str_replace($find, $replace, $option_tmp);
        //$option_list .= $option;
        //20160402 Del S
        //			$replace = array(
        //				"999",
        //				"全て"
        //			);
        //			$option = str_replace($find, $replace, $option_tmp);
        //			$option_list .= $option;
        //20160402 Del E
        foreach ((array) $result['data'] as $value) {
            if ('0' == $value['MENU_TYPE']) {
                $val = $value['TEIKEI_CD'];
                $txt = $value['ITEMNAME1'];
                $replace = array(
                    $val,
                    $txt,
                );
                $option = str_replace($find, $replace, $option_tmp);
                $option_list .= $option;
            }
        }

        //20160402 Upd S
        $replace = array(
            '999',
            '全て',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;
        //20160402 Del E
        return $option_list;
    }

    /**
     * 検索条件変更_最終結果.
     */
    public function sDH0202()
    {
        $post_data = $_POST['data'];
        $type_cd = $post_data['type_cd'];
        $this->m_SDH02 = new SDH02();
        //最終結果を取得
        $option_list2 = '';
        //新車１ヶ月点検　最終結果の追加
        $item_type = '';
        //----20220121 sun upd s
        //if ($type_cd == "0")
        if ('0' == $type_cd || '4' == $type_cd) {
            //----20220121 sun upd e
            $option_list2 = $this->getLastSitu();
            $this->set('option_list2', $option_list2);
        }
        if ('1' == $type_cd) {
            $item_type = '3';
            $option_list2 = $this->get_tenpo_option_list2($item_type);
        }
        //新車６ヶ月点検　最終結果の追加
        if ('2' == $type_cd) {
            $item_type = '4';
            $option_list2 = $this->get_tenpo_option_list2($item_type);
        }
        //--- 20190221 ci INS S
        if ('3' == $type_cd) {
            $item_type = '5';
            $option_list2 = $this->get_tenpo_option_list2($item_type);
        }
        //--- 20190221 ci INS E

        $this->set('result', $option_list2);
        $this->render('/SDH/SDH02/sdh02');
    }

    //--- 20160127 li INS E

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /**
     * 担当者リストを取得SDH02_LOAD_SEL.
     */
    public function get_tenpo_option_list($kyotn_cd = null)
    {
        //社員リストを取得 s
        $result = $this->m_SDH02->m_select_syain($kyotn_cd);
        //社員リストを取得 e

        $option_list = '';
        $option_tmp = '<option value ="{#val}">{#txt}</option>';
        $option_tmp1 = '<option value ="{#val}" selected = "selected">{#txt}</option>';
        $yakusyok_cd = '';
        $es_kb = '';
        // $userid = $this->Session->read('login_user');
        $session = $this->request->getSession();
        $userid = $session->read('login_user');
        $tempresult = $this->m_SDH02->m_select_yakusyokcd_eskb($userid);
        if ($tempresult['row'] > 0) {
            $yakusyok_cd = $tempresult['data'][0]['YAKUSYOK_CD'];
            $es_kb = $tempresult['data'][0]['ES_KB'];
        }
        $find = array(
            '{#val}',
            '{#txt}',
        );

        //店舗全員
        $replace = array(
            '000',
            '店舗全員',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;

        //営業全員
        $replace = array(
            '001',
            '営業全員',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;

        $data = $result['data'];
        foreach ((array) $data as $value) {
            $val = $value['SYAIN_NO'];
            $txt = $value['SYAIN_NM'];
            $replace = array(
                $val,
                $txt,
            );
            if ($val == $userid && '' != $yakusyok_cd && $yakusyok_cd > '64') {
                $option = str_replace($find, $replace, $option_tmp1);
            } else {
                $option = str_replace($find, $replace, $option_tmp);
            }
            $option_list .= $option;
        }

        //サービス
        $replace = array(
            '002',
            'サービス',
        );
        if ('' != $es_kb && 'S' == $es_kb) {
            $option = str_replace($find, $replace, $option_tmp1);
        } else {
            $option = str_replace($find, $replace, $option_tmp);
        }
        $option_list .= $option;

        return $option_list;
    }

    /**
     * SDH02_layout画面のindex
     * $busyoType　本部=0 ,「本部」以外=1
     * $tenpo_cd1　部署コード1
     * $tenpo_cd2　部署コード2.
     */
    public function get_sdh02_busyo_index($selectiondata = '', $busyoType = null, $tenpo_cd1 = null, $tenpo_cd2 = null)
    {
        $result = array(
            'busyo_list' => '',
            'flag' => '',
        );
        $busyoType = '0';
        //20210219 CI UPD S
        //$busyo_list = "";
        $busyo_list = '<option value =""></option>';
        //20210219 CI UPD E
        $busyo_tmp = '<option value ="{#val}">{#txt}</option>';
        $find = array(
            '{#val}',
            '{#txt}',
        );
        //ログインユーザが　「本部」のとき
        if ('0' == $busyoType) {
            $tenpo_cd1 = '220';
            $tenpo_cd2 = "180', '220','261";
            //全部店舗リストを取得 s
            $result1 = $this->m_SDH02->m_select_busyo_all($tenpo_cd1, $tenpo_cd2);

            $data1 = $result1['data'];
            foreach ((array) $data1 as $value) {
                //店舗名を取得 e
                $val = $value['BUSYO_CD'];
                $txt = $value['BUSYO_RYKNM'];

                if ('' == $this->tenpo_cd) {
                    $this->tenpo_cd = $val;
                    //リスト種類の検索の条件を取得する。
                }

                $replace = array(
                    $val,
                    $txt,
                );
                //20210219 CI UPD S
                //$busyo = str_replace($find, $replace, $busyo_tmp);
                //20210225 CI UPD S
                //「本部ユーザ」の場合
                if (2 == strlen($selectiondata)) {
                    //所属店舗あり→選択状態にする
                    if (substr($val, 0, 2) == $selectiondata) {
                        $busyo_tmp1 = '<option value ="{#val}" selected>{#txt}</option>';
                        $busyo = str_replace($find, $replace, $busyo_tmp1);
                        $result['flag'] = true;
                    }
                    //所属店舗ありません→店舗を未選択状態にする
                    else {
                        $busyo = str_replace($find, $replace, $busyo_tmp);
                    }
                }
                //「一般ユーザ」の場合
                elseif (3 == strlen($selectiondata)) {
                    //所属店舗あり→選択状態にする
                    if ($val == $selectiondata) {
                        $busyo_tmp1 = '<option value ="{#val}" selected>{#txt}</option>';
                        $busyo = str_replace($find, $replace, $busyo_tmp1);
                        $result['flag'] = true;
                    }
                    //所属店舗ありません→店舗を未選択状態にする
                    else {
                        $busyo = str_replace($find, $replace, $busyo_tmp);
                    }
                }
                // if ($val == $selectiondata)
                // {
                // $busyo_tmp1 = "<option value =\"{#val}\" selected>{#txt}</option>";
                // $busyo = str_replace($find, $replace, $busyo_tmp1);
                // $result['flag'] = true;
                // }
                else {
                    $busyo = str_replace($find, $replace, $busyo_tmp);
                }
                //20210225 CI UPD E

                //20210219 CI UPD E
                $busyo_list .= $busyo;
            }
        }
        //ログインユーザが「本部」以外
        if ('1' == $busyoType) {
            $val = $tenpo_cd1;
            $txt = $tenpo_cd2;
            $replace = array(
                $val,
                $txt,
            );
            $busyo = str_replace($find, $replace, $busyo_tmp);
            $busyo_list .= $busyo;
        }
        $result['busyo_list'] = $busyo_list;

        return $result;
    }

    /**
     * 担当者リストを取得SDH02_LOAD_SEL.
     */
    public function get_tenpo_option_hazime_list($kyotn_cd)
    {
        //社員リストを取得 s
        $result = $this->m_SDH02->m_select_syain($kyotn_cd);

        $option_list = '';
        $option_tmp = '<option value ="{#val}">{#txt}</option>';
        $find = array(
            '{#val}',
            '{#txt}',
        );

        //店舗全員
        $replace = array(
            '000',
            '店舗全員',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;

        //営業全員
        $replace = array(
            '001',
            '営業全員',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;

        $data = $result['data'];
        foreach ((array) $data as $value) {
            $val = $value['SYAIN_NO'];
            $txt = $value['SYAIN_NM'];
            $replace = array(
                $val,
                $txt,
            );
            $option = str_replace($find, $replace, $option_tmp);
            $option_list .= $option;
        }

        //サービス
        $replace = array(
            '002',
            'サービス',
        );
        $option = str_replace($find, $replace, $option_tmp);
        $option_list .= $option;

        return $option_list;
    }

    // ==========
// = メソッド end =
// ==========
}