<?php
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKReOut4OBC;

//*******************************************
// * sample controller
//*******************************************
class HDKReOut4OBCController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKReOut4OBC = null;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    public function index()
    {
        $this->Session = $this->request->getSession();
        $this->Session->delete("HDKOut4OBC_XLSX_TYPE_RECHK");
        // Viewファイル呼出し
        $this->render('index', 'HDKReOut4OBC_layout');
    }

    //フォーム初期化
    public function fncFormload()
    {
        $result = array(
            'result' => FALSE,
            'data' => array(
                'strStartDate' => "",
                'GetBusyoMstValue' => "",
                'GetSyainMstValue' => ""
            ),
            'error' => ''
        );
        try {
            //時間取得
            $strStartDate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d H:i:s");
            $result['data']['strStartDate'] = $strStartDate;

            //部署コード
            $GetBusyoMstValue = $this->ClsComFncHDKAIKEI->FncGetBusyoMstValue();

            if (!$GetBusyoMstValue['result']) {
                throw new \Exception($GetBusyoMstValue['data']);
            }
            $result['data']['GetBusyoMstValue'] = $GetBusyoMstValue['data'];

            //社員番号
            $GetSyainMstValue = $this->ClsComFncHDKAIKEI->FncGetSyainMstValue();

            if (!$GetSyainMstValue['result']) {
                throw new \Exception($GetSyainMstValue['data']);
            }
            $result['data']['GetSyainMstValue'] = $GetSyainMstValue['data'];

            $result['result'] = true;

        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    //検索
    public function kensakuClick()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //データの取得
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HDKReOut4OBC = new HDKReOut4OBC();

                $result = $this->HDKReOut4OBC->Kensaku_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
            }

        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);

    }

    //仕訳データの取得:グループ一覧の選択ボタン押下時の処理
    public function fncSelGroupAndSyohyShiwakeData()
    {
        $result = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $postData = $_POST['request'];

                $this->HDKReOut4OBC = new HDKReOut4OBC();
                //グループ名
                $txtGroupName = $this->HDKReOut4OBC->Fnc_Fill($postData);

                if (!$txtGroupName['result']) {
                    throw new \Exception($txtGroupName['data']);
                }

                if ($postData['flg'] == '1') {
                    $result = $this->HDKReOut4OBC->fncSelSyohyShiwakeData($postData);
                } else {
                    //仕訳データの取得:グループ一覧の選択ボタン押下時の処理
                    $result = $this->HDKReOut4OBC->fncSelGroupShiwakeData($postData);
                }

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
                $result = $tmpJqgrid;
                $result->txtGroupName = $txtGroupName['data'];
            }
        } catch (\Exception $e) {
            $result['result'] = TRUE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    // CSV出力
    public function btnCsvOutClick()
    {
        $blnTran = FALSE;
        $this->HDKReOut4OBC = new HDKReOut4OBC();
        $result = array(
            'result' => TRUE,
            'data' => array(
                'chgColor' => "",
                'rowNum' => "",
            ),
            'msg' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $this->Session = $this->request->getSession();
                $postData = $_POST['data'];
                $resCheck = $this->inputCheck($postData);
                if (!$resCheck['result']) {
                    $result['data'] = $resCheck['data'];
                    $result['html'] = $resCheck['html'];
                    throw new \Exception('W0034');
                }
                $sysDate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d H:i:s");
                $groupNo = $postData['strGroup_no'];
                //出力グループ名の重複チェック
                $resultGroupNM = $this->HDKReOut4OBC->FncChkExistGroupNM($postData['txtInputGroupNM'], $groupNo);

                if (!$resultGroupNM['result']) {
                    throw new \Exception($resultGroupNM['data']);
                }

                if ($resultGroupNM['data'][0]["COUNT(*)"] != 0) {
                    throw new \Exception("repeatErr");
                }

                // 証憑№のチェック
                for ($intIdx = 0; $intIdx < count($postData['data']); $intIdx++) {
                    $strSyohyoNo = $postData['data'][$intIdx]['SYOHYO_NO_VIEW'];
                    $objLvChkCSV = $postData['data'][$intIdx]['CHK_CSV_FLG'];
                    $strUpdDate = $postData['data'][$intIdx]['UPD_DATE'];

                    if ($objLvChkCSV == "1") {
                        $res = $this->FncChkAndSetShiwakeInfo($strSyohyoNo, $strUpdDate, 2, '');

                        if ($res['result'] == false) {
                            $result['data']['chgColor'] = $res['data'];
                            $result['data']['rowNum'] = $intIdx;
                            $result['msg'] = $res['error'];
                            $result['result'] = FALSE;
                            throw new \Exception('W9999');
                        }
                    } else {
                        //グループのチェック(違うグループになっているデータがある場合、未出力に戻すことはできない)
                        $res = $this->fncCheckOffGroup($strSyohyoNo, $groupNo);

                        if ($res['result'] == false) {
                            $result['msg'] = $res['error'];
                            $result['result'] = FALSE;
                            throw new \Exception('W9999');
                        }
                    }
                }

                //トランザクション開始
                $this->HDKReOut4OBC->Do_transaction();
                $blnTran = TRUE;

                //出力グループの登録
                $resultGroupData = $this->HDKReOut4OBC->SubInsertGroupData($postData, $groupNo, $sysDate);

                if (!$resultGroupData['result']) {
                    throw new \Exception($resultGroupData['data']);
                }

                $patternID = $this->Session->read('PatternID');
                $BusyoCD = $this->Session->read('BusyoCD');
                if (!isset($BusyoCD)) {
                    $result['msg'] = '表示できる部署が存在しません。管理者にお問い合わせください。';
                    throw new \Exception('W9999');
                }

                //仕訳データの更新
                for ($intIdx = 0; $intIdx < count($postData['data']); $intIdx++) {
                    $strSyohyoNo = $postData['data'][$intIdx]['SYOHYO_NO'];
                    $strEdaNo = $postData['data'][$intIdx]['EDA_NO'];
                    $objLvChkCSV = $postData['data'][$intIdx]['CHK_CSV_FLG'];

                    //仕訳データのＣＳＶ出力フラグ、グループをキャンセルする(証憑№単位で)
                    $resultSyohyoDataCancel = $this->HDKReOut4OBC->SubUpdateSyohyoDataCancel($postData, $strSyohyoNo, $sysDate, $patternID);

                    if (!$resultSyohyoDataCancel['result']) {
                        throw new \Exception($resultSyohyoDataCancel['data']);
                    }

                    if ($objLvChkCSV == true) {
                        //出力対象にチェックが入っているデータを対象に、出力フラグ、グループ№をセットする
                        $resultSyohyo = $this->HDKReOut4OBC->SubUpdateSyohyoData($postData, $strSyohyoNo, $strEdaNo, $groupNo, $sysDate, $intIdx + 1, $patternID, $BusyoCD);
                        if (!$resultSyohyo['result']) {
                            throw new \Exception($resultSyohyo['data']);
                        }
                    }
                }

                //コミット
                $this->HDKReOut4OBC->Do_commit();
                $blnTran = FALSE;

                $sessionArray = array(
                    'HDKOut4OBC_XLSXType' => $this->Session->read("HDKOut4OBC_XLSX_TYPE_RECHK") == '仕訳伝票' ? "0" : "1",
                    'HDKOut4OBC_GroupNo' => $groupNo,
                    'HDKOut4OBC_GroupNM' => $postData['txtInputGroupNM'],
                    'HDKOut4OBC_sysDate' => $sysDate,
                    'login_user' => $this->Session->read("login_user"),
                );
                $this->Session->delete("HDKOut4OBC_XLSX_TYPE_RECHK");
                include_once dirname(__DIR__) . '/HDKAIKEI/HDKOut4OBCController.php';
                $strTemplatePath1 = $this->ClsComFncHDKAIKEI->FncGetPath("HDKAIKEIExcelLayoutPath");
                $HDKReOut4OBCXLSXType = new HDKOut4OBCController($this->request);
                $resultXLSX = $HDKReOut4OBCXLSXType->CSVDownload($sessionArray, $strTemplatePath1);
                if (!$resultXLSX['result']) {
                    throw new \Exception($resultXLSX['error']);
                }

                $result['data'] = $resultXLSX['data']['url'];
            }

        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HDKReOut4OBC->Do_rollback();
            }
            $result['result'] = FALSE;
            $result['error'] = $e->getMessage();
        }

        $this->fncReturn($result);
    }

    // 読み取りデータのチェックと読取書類ラベルへのセット
    // Mode=0[ラベルへのセットのみ], Mode=1[チェック＆メッセージ付], Mode=2[CSV出力時の再チェック]
    public function FncChkAndSetShiwakeInfo($strSyohyoNo, $strUpdDate, $Mode, $retCSVFLG)
    {
        $res = array(
            'result' => true,
            'data' => [],
            'error' => ''
        );

        try {
            $result = $this->HDKReOut4OBC->FncChkAndSetShiwakeInfoSql($strSyohyoNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count((array) $result['data']) == 0) {
                if ($Mode > 0) {
                    throw new \Exception('証憑№:' . $strSyohyoNo . 'に該当するデータは削除されています！再度、検索ボタンを押下して最新データを取得して下さい！');
                }
            } else {
                $retCSVFLG = $result['data'][0]['読取書類'];

                if ($Mode > 0) {
                    if (substr($strSyohyoNo, 15, 2) < $result['data'][0]['EDA_NO']) {
                        if ($Mode == '2') {
                            $res['data'] = '1';
                        }
                        throw new \Exception('証憑№:' . $strSyohyoNo . 'のデータは最新ではありません！再度、検索ボタンを押下して最新データを取得して下さい！');
                    } else
                        if (substr($strSyohyoNo, 15, 2) > $result['data'][0]['EDA_NO']) {
                            throw new \Exception('証憑№:' . $strSyohyoNo . 'に該当するデータは削除されています！再度、検索ボタンを押下して最新データを取得して下さい！');
                        }
                }

                if ($Mode == 2) {
                    $this->Session = $this->request->getSession();
                    if ($this->Session->read("HDKOut4OBC_XLSX_TYPE_RECHK") != NULL) {
                        if ($this->Session->read("HDKOut4OBC_XLSX_TYPE_RECHK") != $retCSVFLG) {
                            throw new \Exception('証憑№:' . $strSyohyoNo . 'のデータは１件目と出力フォーマットが違います！');
                        }
                    } else {
                        $this->Session->write("HDKOut4OBC_XLSX_TYPE_RECHK", $retCSVFLG);
                    }
                }

                if ($Mode > 0 && $result['data'][0]['XLSX出力フラグ'] == '0') {
                    throw new \Exception('証憑№:' . $strSyohyoNo . 'は他のユーザによってＯＢＣ出力をキャンセルされています！');
                } else
                    if ($Mode > 0 && $result['data'][0]['削除フラグ'] == '1') {
                        throw new \Exception('証憑№:' . $strSyohyoNo . 'のデータは既に削除されています！再度、検索ボタンを押下して最新データを取得して下さい。');
                    } else
                        if ($Mode > 0 && $result['data'][0]['印刷フラグ'] == '0') {
                            throw new \Exception('証憑№:' . $strSyohyoNo . 'のデータは伝票印刷が行われていません！');
                        } else
                            if ($result['data'][0]['UPD_DATE'] != $strUpdDate) {
                                throw new \Exception('証憑№:' . $strSyohyoNo . 'は他のユーザによって更新されています！再度、検索ボタンを押下して最新データを取得して下さい。');
                            }
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    //グループのチェック(違うグループになっているデータがある場合、未出力に戻すことはできない)
    public function fncCheckOffGroup($strSyohyoNo, $groupNo)
    {
        $res = array(
            'result' => TRUE,
            'error' => ''
        );

        try {
            $result = $this->HDKReOut4OBC->fncCheckOffGroup($strSyohyoNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count((array) $result['data']) == 0) {
                $result['result'] = TRUE;
            }

            if ($this->ClsComFncHDKAIKEI->FncNv($result['data'][0]['グループ番号']) != $groupNo && empty($result['data'][0]['グループ番号']) == false) {
                throw new \Exception('証憑№:' . $strSyohyoNo . 'に該当するデータは他のユーザによって更新されています！再度、検索ボタンを押下して最新データを取得して下さい！');
            }

        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }
    public function inputCheck($postData)
    {
        $result = array(
            'result' => true,
            'html' => '',
            'data' => ''
        );
        try {
            if (!$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['txtInputGroupNM'])) {
                $result['html'] = 'txtInputGroupNM';
                throw new \Exception('グループ名');
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }
}
