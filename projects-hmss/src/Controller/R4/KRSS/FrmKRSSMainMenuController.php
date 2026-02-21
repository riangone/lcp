<?php

namespace App\Controller\R4\KRSS;

use App\Controller\AppController;
use App\Model\R4\FrmMainMenu;

//*******************************************
// * sample controller
//*******************************************
class FrmKRSSMainMenuController extends AppController
{
    public $FrmMainMenu;
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    // var $components = array('RequestHandler');

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // レイアウトファイルの指定(app/View/Layouts/smpkame_layout.ctpを参照)
        // $this->layout = 'FrmKRSSMainMenu_layout';

        $this->set('title_for_layout', 'KRSS連携サブシステム：メニュー画面');
        // $this->render('index');
        $this->render('index', 'FrmKRSSMainMenu_layout');
    }

    public function menuKRSS()
    {
        // 変数の初期化
        // $postData = "";
        $result = array();

        // 呼出クラスのインスタンス作成
        $this->FrmMainMenu = new FrmMainMenu();
        $session = $this->request->getSession();
        $login_user = $session->read('login_user');
        // $login_user = $this->Session->read('login_user');
        $STYLE_ID = "";
        $PATTERN_ID = "";
        $roledata = $this->FrmMainMenu->getmenulist1($login_user);
        if (count((array) $roledata['data']) > 0) {
            if ($roledata['data'][0]['STYLE_ID'] != null && $roledata['data'][0]['PATTERN_ID'] != null) {
                $STYLE_ID = $roledata['data'][0]['STYLE_ID'];
                $PATTERN_ID = $roledata['data'][0]['PATTERN_ID'];
            }
        }

        $SYS_KB = "11";

        $parentArr = array();

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // 処理の実行
            $menudata = $this->FrmMainMenu->menu($STYLE_ID, $PATTERN_ID, $SYS_KB);

            $data = $menudata["data"];

            //print_r($data);
            $index = 0;
            foreach ((array) $data as $key => $value) {
                //print_r($value);
                if ($value['KAISOU_ID1'] != null && $value['KAISOU_ID1'] != '' && $value['KAISOU_ID2'] == 0) {
                    $item1 = $value['KAISOU_NM'] . $value['PRO_NM'];
                    //array_push($parentArr, $item1);
                    if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                        $parentArr[$value['PRO_ID']] = $item1;
                    } else {
                        $parentArr[$index] = $item1;
                        $index++;
                        //array_push($parentArr, $item1);
                    }
                    $childArr1[$item1] = array();
                } elseif ($value['KAISOU_ID2'] != 0) {

                    if ($value['KAISOU_ID3'] == 0) {
                        $item2 = $value['KAISOU_NM'] . $value['PRO_NM'];
                        //array_push($childArr1[$item1], $item2);
                        if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                            $childArr1[$item1][$value['PRO_ID']] = $item2;
                        } else {
                            $childArr1[$item1][$index] = $item2;
                            $index++;
                            //array_push($childArr1[$item1], $item2);
                        }
                        //$childArr1[$item1][$value['PRO_ID']] = $item2;
                        $childArr2[$item1 . $item2] = array();

                    } elseif ($value['KAISOU_ID3'] != 0) {
                        if ($value['KAISOU_ID4'] == 0) {
                            $item3 = $value['KAISOU_NM'] . $value['PRO_NM'];
                            //array_push($childArr2[$item1 . $item2], $item3);
                            if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                                $childArr2[$item1 . $item2][$value['PRO_ID']] = $item3;
                            } else {
                                $childArr2[$item1 . $item2][$index] = $item3;
                                $index++;
                                //array_push($childArr2[$item1 . $item2], $item3);
                            }
                            //$childArr2[$item1 . $item2][$value['PRO_ID']] = $item3;
                            $childArr3[$item1 . $item2 . $item3] = array();

                        } elseif ($value['KAISOU_ID4'] != 0) {

                            if ($value['KAISOU_ID5'] == 0) {
                                $item4 = $value['KAISOU_NM'] . $value['PRO_NM'];
                                //array_push($childArr3[$item1 . $item2 . $item3], $item4);
                                if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                                    $childArr3[$item1 . $item2 . $item3][$value['PRO_ID']] = $item4;
                                } else {
                                    $childArr3[$item1 . $item2 . $item3][$index] = $item4;
                                    $index++;
                                    //array_push($childArr3[$item1 . $item2 . $item3], $item4);
                                }
                                //$childArr3[$item1 . $item2 . $item3][$value['PRO_ID']] = $item4;
                            }
                        }
                    }
                }
            }
            //20240606 caina ins s
            $idIndex = 0;
            //20240606 caina ins e
            foreach ($parentArr as $key => $value) {
                $item1 = array();
                //20240606 caina upd s
                // if (!is_numeric($key)) {
                //     $item1["attr"]["id"] = $key;
                // }
                // $item1["data"] = $value;
                if (!is_numeric($key)) {
                    $item1['id'] = $key;
                } else {
                    $item1["id"] = $idIndex++;
                }
                $item1["text"] = $value;
                //20240606 caina upd e
                //$item1["state"] = "close";
                $item1["children"] = array();

                foreach ($childArr1[$value] as $key1 => $value1) {
                    $item2 = array();
                    //20240606 caina upd s
                    // if (!is_numeric($key1)) {
                    //     $item2["attr"]["id"] = $key1;
                    // }
                    // $item2["data"] = $value1;
                    if (!is_numeric($key1)) {
                        $item2["id"] = $key1;
                    } else {
                        $item2["id"] = $idIndex++;
                    }
                    $item2["text"] = $value1;
                    //20240606 caina upd e
                    $item2["children"] = array();

                    foreach ($childArr2[$value . $value1] as $key2 => $value2) {
                        $item3 = array();
                        //20240606 caina upd s
                        // if (!is_numeric($key2)) {
                        //     $item3["attr"]["id"] = $key2;
                        // }
                        // $item3["data"] = $value2;
                        if (!is_numeric($key2)) {
                            $item3['id'] = $key2;
                        } else {
                            $item3["id"] = $idIndex++;
                        }
                        $item3["text"] = $value2;
                        //20240606 caina upd e
                        $item3["children"] = array();

                        foreach ($childArr3[$value . $value1 . $value2] as $key3 => $value3) {
                            $item4 = array();
                            //20240606 caina upd s
                            // if (!is_numeric($key3)) {
                            //     $item4["attr"]["id"] = $key3;
                            // }
                            // $item4["data"] = $value3;
                            if (!is_numeric($key3)) {
                                $item4['id'] = $key3;
                            } else {
                                $item4["id"] = $idIndex++;
                            }
                            $item4["text"] = $value3;
                            //20240606 caina upd e

                            array_push($item3["children"], $item4);
                        }
                        array_push($item2["children"], $item3);
                    }
                    array_push($item1["children"], $item2);

                }
                array_push($result, $item1);
            }

        } else {
            // エラー処理
            $result = array('result' => 'false', 'data' => 'no ajax request');
        }

        // 取得した値をView画面で使用する
        $this->set('result', $result);
        // render('ajaxUpdate')とした場合はajax_Updateに自動変換される
        // $this->render('menukrss');
        $this->render('/R4/KRSS/FrmKRSSMainMenu/menukrss');
    }

}
