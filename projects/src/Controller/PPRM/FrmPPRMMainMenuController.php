<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20250423           BUG    セッションが期限切れの状態でシステムを切り替えた場合に  caina
 *                                         情報が混在する不具合の修正
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\PPRM;

use App\Controller\AppController;
use App\Model\Main\Main;
use App\Model\PPRM\FrmPPRMMainMenu;
use mysqli;

//*******************************************
// * sample controller
//*******************************************
class FrmPPRMMainMenuController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
// public $autoRender = false;
    // var $components = array('RequestHandler');

    private $Session;

    public $FrmMainMenu;

    private $Main;

    //　デフォルトで最初に実行される機能
    public function index()
    {
        // レイアウトファイルの指定
        $layout = 'FrmPPRMMainMenu_layout';
        $this->set('title_for_layout', 'PPRMペーパレス化支援システム：メニュー画面');
        $this->render('/PPRM/FrmPPRMMainMenu/index', $layout);
    }

    public function menuPPRM()
    {
        // 変数の初期化
        $result = array();

        // 呼出クラスのインスタンス作成
        $this->FrmMainMenu = new FrmPPRMMainMenu();
        $this->Session = $this->request->getSession();
        $login_user = $this->Session->read('login_user');
        $STYLE_ID = "";
        $PATTERN_ID = "";
        //20170920 YIN UPD S
        // $SYS_KB = "16";
        $SYS_KB = "";
        // MySQL DBへ接続
        $this->Main = new Main();
        $connresult = $this->Main->connMysql();
        if ($connresult['result']) {
            $mysqli = $connresult['data'];
            if ($mysqli instanceof mysqli) {
                $mysqli->set_charset("utf8");
            }
            $sysdata = $this->FrmMainMenu->getSysKb();
            $syskbdata = mysqli_fetch_assoc($sysdata);
            if ($syskbdata) {
                $SYS_KB = $syskbdata['sys_cd'];
                $SYS_KB = (int) $SYS_KB;
            }
        }
        //20170920 YIN UPD E

        $roledata = $this->FrmMainMenu->getmenulist15($login_user, $SYS_KB);
        if (count((array) $roledata['data']) > 0) {
            if ($roledata['data'][0]['STYLE_ID'] != null && $roledata['data'][0]['PATTERN_ID'] != null) {
                $STYLE_ID = $roledata['data'][0]['STYLE_ID'];
                $PATTERN_ID = $roledata['data'][0]['PATTERN_ID'];

                $BUSYO_CD = $roledata['data'][0]['BUSYO_CD'];
                $SYAIN_NM = $roledata['data'][0]['SYAIN_NM'];
                $ip = $this->request->clientIp();
                //部署コード
                $this->Session->write('BusyoCD', $BUSYO_CD);
                //USER名前
                $this->Session->write('SyainNM', $SYAIN_NM);
                //PC is Ip
                $this->Session->write('MachineNM', $ip);
                //20170214 YIN INS S
                $this->Session->write('PatternID', $PATTERN_ID);
                //20170214 YIN INS E
                //Sysコード
                $this->Session->write('Sys_KB', $SYS_KB);
                //
                //部署のチェック
                $strRet0 = "";
                $strRet1 = "";
                $FrmPPRMMainMenu = new FrmPPRMMainMenu();
                $rolePprdata = $FrmPPRMMainMenu->fncBusyoKB($BUSYO_CD);
                if (count((array) $rolePprdata['data']) > 0) {
                    if ($rolePprdata['data'][0]['SVKYOTN_CD'] == $BUSYO_CD) {
                        $strRet0 = "F";
                        $strRet1 = trim($rolePprdata['data'][0]['TENPO_CD']);
                    } else {
                        $strRet0 = "S";
                        $strRet1 = trim($rolePprdata['data'][0]['TENPO_CD']);
                    }
                }
                if ($strRet0 != "") {
                    //BusyoKB
                    $this->Session->write('BusyoKB', $strRet0);
                    //TenpoCD
                    $this->Session->write('TenpoCD', $strRet1);
                } else {
                    //BusyoKB
                    $this->Session->write('BusyoKB', "S");
                    //TenpoCD
                    $this->Session->write('TenpoCD', "");
                }
            }
        }

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
            $idIndex = 0;
            foreach ($parentArr as $key => $value) {
                $item1 = array();
                if (!is_numeric($key)) {
                    $item1['id'] = $key;
                } else {
                    $item1["id"] = $idIndex++;
                }
                $item1["text"] = $value;
                $item1["children"] = array();

                foreach ($childArr1[$value] as $key1 => $value1) {
                    $item2 = array();
                    if (!is_numeric($key1)) {
                        $item2['id'] = $key1;
                    } else {
                        $item2["id"] = $idIndex++;
                    }
                    $item2["text"] = $value1;
                    $item2["children"] = array();

                    foreach ($childArr2[$value . $value1] as $key2 => $value2) {
                        $item3 = array();
                        if (!is_numeric($key2)) {
                            $item3['id'] = $key2;
                        } else {
                            $item3["id"] = $idIndex++;
                        }
                        $item3["text"] = $value2;
                        $item3["children"] = array();

                        foreach ($childArr3[$value . $value1 . $value2] as $key3 => $value3) {
                            $item4 = array();
                            if (!is_numeric($key3)) {
                                $item4['id'] = $key3;
                            } else {
                                $item4["id"] = $idIndex++;
                            }
                            $item4["text"] = $value3;

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

    // 20170301 LQS INS S
    public function getSession()
    {
        $result = array(
            'result' => false,
            'data' => ''
        );
        try {
            // 20250423 caina upd s
            // $data = array(
            //     'UserId' => $this->Session->read('login_user'),
            //     'BusyoCD' => $this->Session->read('BusyoCD'),
            //     'BusyoKB' => $this->Session->read('BusyoKB'),
            //     'TenpoCD' => $this->Session->read('TenpoCD'),
            //     'SyainNM' => $this->Session->read('SyainNM'),
            //     'MachineNM' => $this->Session->read('MachineNM'),
            //     'PatternID' => $this->Session->read('PatternID'),
            //     'Sys_KB' => $this->Session->read('Sys_KB')
            // );
            $this->FrmMainMenu = new FrmPPRMMainMenu();
            $this->Session = $this->request->getSession();
            $login_user = $this->Session->read('login_user');

            $SYS_KB = "";
            // MySQL DBへ接続
            $this->Main = new Main();
            $connresult = $this->Main->connMysql();
            if ($connresult['data'] instanceof mysqli) {
                mysqli_query($connresult['data'], 'set names utf8');
                mysqli_set_charset($connresult['data'], 'utf8');
            }
            if ($connresult) {
                $sysdata = $this->FrmMainMenu->getSysKb();
                $syskbdata = mysqli_fetch_assoc($sysdata);
                if ($syskbdata) {
                    $SYS_KB = $syskbdata['sys_cd'];
                    $SYS_KB = (int) $SYS_KB;
                    $this->Session->write('Sys_KB', $SYS_KB);
                }
            }
            $ip = $this->request->clientIp();
            $roledata = $this->FrmMainMenu->getmenulist15($login_user, $SYS_KB);
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
                if (count((array) $roledata['data']) > 0) {
                    if ($roledata['data'][0]['STYLE_ID'] != null && $roledata['data'][0]['PATTERN_ID'] != null) {
                        $BUSYO_CD = $roledata['data'][0]['BUSYO_CD'];
                        $strRet0 = "";
                        $strRet1 = "";
                        $FrmPPRMMainMenu = new FrmPPRMMainMenu();
                        $rolePprdata = $FrmPPRMMainMenu->fncBusyoKB($BUSYO_CD);
                        if (count((array) $rolePprdata['data']) > 0) {
                            if ($rolePprdata['data'][0]['SVKYOTN_CD'] == $BUSYO_CD) {
                                $strRet0 = "F";
                                $strRet1 = trim($rolePprdata['data'][0]['TENPO_CD']);
                            } else {
                                $strRet0 = "S";
                                $strRet1 = trim($rolePprdata['data'][0]['TENPO_CD']);
                            }
                        }
                        if ($strRet0 != "") {
                            $BusyoKB = $strRet0;
                            $TenpoCD = $strRet1;
                        } else {
                            $BusyoKB = "S";
                            $TenpoCD = "";
                        }
                    }
                }
                $data = array(
                    'UserId' => $login_user,
                    'BusyoCD' => $roledata['data'][0]['BUSYO_CD'],
                    'BusyoKB' => $BusyoKB,
                    'TenpoCD' => $TenpoCD,
                    'SyainNM' => $roledata['data'][0]['SYAIN_NM'],
                    'MachineNM' => $ip,
                    'PatternID' => $roledata['data'][0]['PATTERN_ID'],
                    'Sys_KB' => $SYS_KB
                );
            } else {
                $data = array(
                    'UserId' => $login_user,
                    'BusyoCD' => '',
                    'BusyoKB' => '',
                    'TenpoCD' => '',
                    'SyainNM' => '',
                    'MachineNM' => $ip,
                    'PatternID' => '',
                    'Sys_KB' => $SYS_KB
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

    // 20170301 LQS INS E

}