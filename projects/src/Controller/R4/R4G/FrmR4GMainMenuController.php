<?php

// App::uses('AppController', 'Controller');
// App::uses('FrmMainMenu', 'Model/R4');

namespace App\Controller\R4\R4G;

use App\Controller\AppController;
use App\Model\R4\FrmMainMenu;

//*******************************************
// * sample controller
//*******************************************
class FrmR4GMainMenuController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = true;
    public $FrmMainMenu;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = false;
    // public $components = array('RequestHandler');

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // レイアウトファイルの指定(app/View/Layouts/smpkame_layout.ctpを参照)
        // $this->layout = 'FrmR4GMainMenu_layout';

        $this->set('title_for_layout', 'R4G連携サブシステム：メニュー画面');

        // $this->FrmMainMenu = new FrmMainMenu();
        // // ユーザーID存在チェック,パスワード存在チェック,ユーザーID、パスワード存在チェック
        // $result = $this -> FrmMainMenu -> select();
        // // ログイン情報の取得に失敗した場合
        // if ($result['result'] == "FALSE") {
        // throw new Exception($result['data']);
        // }
        //
        // $this -> set('result', $result);

        // Viewファイル呼出し
        $this->render('index', 'FrmR4GMainMenu_layout');
    }

    public function menuR4G()
    {
        // 変数の初期化
        $result = array();

        /*業務*/
        // $STYLE_ID = "003";
        // $PATTERN_ID = "001";

        /*経理*/
        // $STYLE_ID = "001";
        // $PATTERN_ID = "002";

        /*2014-01-26 qiuqiu start*/

        // 呼出クラスのインスタンス作成
        $this->FrmMainMenu = new FrmMainMenu();
        $session = $this->request->getSession();
        $login_user = $session->read('login_user');
        $roledata = $this->FrmMainMenu->getmenulist($login_user);
        if (!$roledata['result']) {
            // エラー処理
            $result = array(
                'result' => 'false',
                'data' => 'no ajax request',
            );
            $this->fncReturn($result);
        }
        if (0 !== $roledata['row']) {
            $role = $roledata['data'][0];
            if ('005' == $role['STYLE_ID']) {
                $STYLE_ID = $role['STYLE_ID'];
                $PATTERN_ID = $role['PATTERN_ID'];
            } else {
                $STYLE_ID = '003';
                $PATTERN_ID = '001';
            }
        } else {
            $STYLE_ID = '003';
            $PATTERN_ID = '001';
        }

        $session->write('STYLE_ID', $STYLE_ID);
        $SYS_KB = '0';

        $parentArr = array();

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            // 処理の実行
            $menudata = $this->FrmMainMenu->menu($STYLE_ID, $PATTERN_ID, $SYS_KB);
            $data = $menudata['data'];

            //print_r($data);
            $index = 0;
            foreach ((array) $data as $key => $value) {
                //print_r($value);
                if (null != $value['KAISOU_ID1'] && '' != $value['KAISOU_ID1'] && 0 == $value['KAISOU_ID2']) {
                    $item1 = $value['KAISOU_NM'] . $value['PRO_NM'];
                    //array_push($parentArr, $item1);
                    if ('' != $value['PRO_ID'] && null != $value['PRO_ID']) {
                        $parentArr[$value['PRO_ID']] = $item1;
                    } else {
                        $parentArr[$index] = $item1;
                        ++$index;
                        //array_push($parentArr, $item1);
                    }
                    $childArr1[$item1] = array();
                } elseif (0 != $value['KAISOU_ID2']) {
                    if (0 == $value['KAISOU_ID3']) {
                        $item2 = $value['KAISOU_NM'] . $value['PRO_NM'];
                        //array_push($childArr1[$item1], $item2);
                        if ('' != $value['PRO_ID'] && null != $value['PRO_ID']) {
                            $childArr1[$item1][$value['PRO_ID']] = $item2;
                        } else {
                            $childArr1[$item1][$index] = $item2;
                            ++$index;
                            //array_push($childArr1[$item1], $item2);
                        }
                        //$childArr1[$item1][$value['PRO_ID']] = $item2;
                        $childArr2[$item1 . $item2] = array();
                    } elseif (0 != $value['KAISOU_ID3']) {
                        if (0 == $value['KAISOU_ID4']) {
                            $item3 = $value['KAISOU_NM'] . $value['PRO_NM'];
                            //array_push($childArr2[$item1 . $item2], $item3);
                            if ('' != $value['PRO_ID'] && null != $value['PRO_ID']) {
                                $childArr2[$item1 . $item2][$value['PRO_ID']] = $item3;
                            } else {
                                $childArr2[$item1 . $item2][$index] = $item3;
                                ++$index;
                                //array_push($childArr2[$item1 . $item2], $item3);
                            }
                            //$childArr2[$item1 . $item2][$value['PRO_ID']] = $item3;
                            $childArr3[$item1 . $item2 . $item3] = array();
                        } elseif (0 != $value['KAISOU_ID4']) {
                            if (0 == $value['KAISOU_ID5']) {
                                $item4 = $value['KAISOU_NM'] . $value['PRO_NM'];
                                //array_push($childArr3[$item1 . $item2 . $item3], $item4);
                                if ('' != $value['PRO_ID'] && null != $value['PRO_ID']) {
                                    $childArr3[$item1 . $item2 . $item3][$value['PRO_ID']] = $item4;
                                } else {
                                    $childArr3[$item1 . $item2 . $item3][$index] = $item4;
                                    ++$index;
                                    //array_push($childArr3[$item1 . $item2 . $item3], $item4);
                                }
                                //$childArr3[$item1 . $item2 . $item3][$value['PRO_ID']] = $item4;
                            }
                        }
                    }
                }
            }

            //20240605 zhangxiaolei add s
            $idIndex = 0;
            //20240605 zhangxiaolei add e

            foreach ($parentArr as $key => $value) {
                $item1 = array();

                //20240605 zhangxiaolei upd s
                if (!is_numeric($key)) {
                    $item1['id'] = $key;
                } else {
                    $item1["id"] = $idIndex++;
                }
                $item1["text"] = $value;
                //20240605 zhangxiaolei upd e

                //$item1["state"] = "close";
                $item1['children'] = array();

                foreach ($childArr1[$value] as $key1 => $value1) {
                    $item2 = array();

                    //20240605 zhangxiaolei upd s
                    if (!is_numeric($key1)) {
                        $item2['id'] = $key1;
                    } else {
                        $item2["id"] = $idIndex++;
                    }
                    $item2["text"] = $value1;
                    //20240605 zhangxiaolei upd e

                    $item2['children'] = array();

                    foreach ($childArr2[$value . $value1] as $key2 => $value2) {
                        $item3 = array();

                        //20240605 zhangxiaolei upd s
                        if (!is_numeric($key2)) {
                            $item3['id'] = $key2;
                        } else {
                            $item3["id"] = $idIndex++;
                        }
                        $item3["text"] = $value2;
                        //20240605 zhangxiaolei upd e

                        $item3['children'] = array();

                        foreach ($childArr3[$value . $value1 . $value2] as $key3 => $value3) {
                            $item4 = array();

                            //20240605 zhangxiaolei upd s
                            if (!is_numeric($key3)) {
                                $item4['id'] = $key3;
                            } else {
                                $item4["id"] = $idIndex++;
                            }
                            $item4["text"] = $value3;
                            //20240605 zhangxiaolei upd e

                            array_push($item3['children'], $item4);
                        }
                        array_push($item2['children'], $item3);
                    }
                    array_push($item1['children'], $item2);
                }
                array_push($result, $item1);
            }
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
