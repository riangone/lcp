<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20250423           BUG    セッションが期限切れの状態でシステムを切り替えた場合に      caina
 *                                         情報が混在する不具合の修正
 * -------------------------------------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMTVE;

use App\Controller\AppController;
use App\Model\HMTVE\FrmHMTVEMainMenu;
use App\Controller\HMTVE\HMTVEController;

//*******************************************
// * sample controller
//*******************************************
class FrmHMTVEMainMenuController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutoRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    private $FrmHMTVEMainMenu;
    private $Session;

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // レイアウトファイルの指定
        $layout = 'FrmHMTVEMainMenu_layout';
        $this->set('title_for_layout', 'データ集計システム：メニュー画面');
        $this->render('/HMTVE/FrmHMTVEMainMenu/index', $layout);
    }

    public function menuHMTVE()
    {
        // 変数の初期化
        $PATTERN_ID = "";
        $result = array();

        // 呼出クラスのインスタンス作成
        $this->FrmHMTVEMainMenu = new FrmHMTVEMainMenu();

        $this->Session = $this->request->getSession();
        $login_user = $this->Session->read('login_user');
        $roledata = $this->FrmHMTVEMainMenu->getmenulist($login_user, HMTVEController::SYS_KB);
        $role = $roledata["data"];
        if ($role) {
            if ($role[0]['PATTERN_ID'] != null) {
                $PATTERN_ID = $role[0]['PATTERN_ID'];
                $this->Session->write('PatternID', $PATTERN_ID);
            }
            //20220115 lujunxia ins s
            else {
                $this->Session->delete('PatternID');
            }
            //20220115 lujunxia ins e
            //20211104 ZHANGBOWEN INS S
            if ($role[0]['BUSYO_CD'] != null) {
                //部署コード
                $this->Session->write('BusyoCD', $role[0]['BUSYO_CD']);

            }
            //20220115 lujunxia ins s
            else {
                $this->Session->delete('BusyoCD');
            }
            //20220115 lujunxia ins e
            if ($role[0]['SYAIN_NM'] != null) {
                $this->Session->write('SyainNM', $role[0]['SYAIN_NM']);
            }
            //20211104 ZHANGBOWEN INS E
            //20220115 lujunxia ins s
            else {
                $this->Session->delete('SyainNM');
            }
            //20220115 lujunxia ins e
        }

        $parentArr = array();

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // 処理の実行
            $menudata = $this->FrmHMTVEMainMenu->menu(HMTVEController::STYLE_ID, $PATTERN_ID, HMTVEController::SYS_KB);

            $data = $menudata["data"];

            $index = 0;
            foreach ((array) $data as $key => $value) {
                if ($value['KAISOU_ID1'] != null && $value['KAISOU_ID1'] != '' && $value['KAISOU_ID2'] == 0) {
                    $item1 = $value['KAISOU_NM'] . $value['PRO_NM'];
                    if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                        $parentArr[$value['PRO_ID']] = $item1;
                    } else {
                        $parentArr[$index] = $item1;
                        $index++;
                    }
                    $childArr1[$item1] = array();
                } elseif ($value['KAISOU_ID2'] != 0) {

                    if ($value['KAISOU_ID3'] == 0) {
                        $item2 = $value['KAISOU_NM'] . $value['PRO_NM'];
                        if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                            $childArr1[$item1][$value['PRO_ID']] = $item2;
                        } else {
                            $childArr1[$item1][$index] = $item2;
                            $index++;
                        }
                        $childArr2[$item1 . $item2] = array();

                    } elseif ($value['KAISOU_ID3'] != 0) {
                        if ($value['KAISOU_ID4'] == 0) {
                            $item3 = $value['KAISOU_NM'] . $value['PRO_NM'];
                            if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                                $childArr2[$item1 . $item2][$value['PRO_ID']] = $item3;
                            } else {
                                $childArr2[$item1 . $item2][$index] = $item3;
                                $index++;
                            }
                            $childArr3[$item1 . $item2 . $item3] = array();

                        } elseif ($value['KAISOU_ID4'] != 0) {

                            if ($value['KAISOU_ID5'] == 0) {
                                $item4 = $value['KAISOU_NM'] . $value['PRO_NM'];
                                if ($value['PRO_ID'] != "" && $value['PRO_ID'] != null) {
                                    $childArr3[$item1 . $item2 . $item3][$value['PRO_ID']] = $item4;
                                } else {
                                    $childArr3[$item1 . $item2 . $item3][$index] = $item4;
                                    $index++;
                                }
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
            $result = array(
                'result' => 'false',
                'data' => 'no ajax request'
            );
        }

        $this->fncReturn($result);
    }

    public function getSession()
    {
        $result = array(
            'result' => false,
            'data' => ''
        );
        try {
            // 20250423 caina upd s
            // $data = array(
            //     'PatternID' => $this->Session->read('PatternID'),
            //     'UserId' => $this->Session->read('login_user'),
            // );
            $this->FrmHMTVEMainMenu = new FrmHMTVEMainMenu();
            $this->Session = $this->request->getSession();
            $login_user = $this->Session->read('login_user');
            $roledata = $this->FrmHMTVEMainMenu->getmenulist($login_user, HMTVEController::SYS_KB);
            if (!$roledata['result']) {
                throw new \Exception($roledata['data']);
            }
            if ($roledata['data']) {
                if ($roledata['data'][0]['PATTERN_ID'] != null) {
                    $this->Session->write('PatternID', $roledata['data'][0]['PATTERN_ID']);
                }
                if ($roledata['data'][0]['BUSYO_CD'] != null) {
                    $this->Session->write('BusyoCD', $roledata['data'][0]['BUSYO_CD']);
                }
                if ($roledata['data'][0]['SYAIN_NM'] != null) {
                    $this->Session->write('SyainNM', $roledata['data'][0]['SYAIN_NM']);
                }
                $data = array(
                    'UserId' => $login_user,
                    'PatternID' => $roledata['data'][0]['PATTERN_ID'],
                    'SyainNM' => $roledata['data'][0]['SYAIN_NM'],
                    'BusyoCD' => $roledata['data'][0]['BUSYO_CD'],
                );
            } else {
                $data = array(
                    'UserId' => $login_user,
                    'PatternID' => '',
                    'SyainNM' => '',
                    'BusyoCD' => '',
                );
            }

            // 20250423 caina upd e
            $result["data"] = $data;
            $result["result"] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        $this->fncReturn($result);
    }

}