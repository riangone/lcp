<?php
namespace App\Controller\HMDPS;

use App\Controller\AppController;
use App\Model\HMDPS\HMDPS105CSVReOut;
//*******************************************
// * sample controller
//*******************************************
class HMDPS105CSVReOutController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    // var $components = array(
    //     'RequestHandler',
    //     'ClsComFncHMDPS'
    // );

    public $HMDPS105CSVReOut;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHMDPS');
    }
    public function index()
    {
        $this->Session = $this->request->getSession();
        $this->Session->delete("HMDPS104_CSV_TYPE_RECHK");
        // Viewファイル呼出し
        $this->render('index', 'HMDPS105CSVReOut_layout');
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
            $strStartDate = $this->ClsComFncHMDPS->FncGetSysDate("Y/m/d H:i:s");
            $result['data']['strStartDate'] = $strStartDate;

            //部署コード
            $GetBusyoMstValue = $this->ClsComFncHMDPS->FncGetBusyoMstValue();

            if (!$GetBusyoMstValue['result']) {
                throw new \Exception($GetBusyoMstValue['data']);
            }
            $result['data']['GetBusyoMstValue'] = $GetBusyoMstValue['data'];

            //社員番号
            $GetSyainMstValue = $this->ClsComFncHMDPS->FncGetSyainMstValue();

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

                $this->HMDPS105CSVReOut = new HMDPS105CSVReOut();

                $result = $this->HMDPS105CSVReOut->Kensaku_Click($postData);

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMDPS->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMDPS->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
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

                $this->HMDPS105CSVReOut = new HMDPS105CSVReOut();
                //グループ名
                $txtGroupName = $this->HMDPS105CSVReOut->Fnc_Fill($postData);

                if (!$txtGroupName['result']) {
                    throw new \Exception($txtGroupName['data']);
                }

                if ($postData['flg'] == '1') {
                    $result = $this->HMDPS105CSVReOut->fncSelSyohyShiwakeData($postData);
                } else {
                    //仕訳データの取得:グループ一覧の選択ボタン押下時の処理
                    $result = $this->HMDPS105CSVReOut->fncSelGroupShiwakeData($postData);
                }

                if (!$result['result']) {
                    throw new \Exception($result['data']);
                }

                $tmpJqgridShow = $this->ClsComFncHMDPS->FncCreateJqGridShow($result['data']);
                $start = $tmpJqgridShow['start'];
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $tmpJqgrid = $this->ClsComFncHMDPS->FncCreateJqGridDataReload($result["data"], $totalPage, $page, $tmpCount, $start);
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
        $this->HMDPS105CSVReOut = new HMDPS105CSVReOut();
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
                $sysDate = $this->ClsComFncHMDPS->FncGetSysDate("Y/m/d H:i:s");
                $groupNo = $postData['strGroup_no'];
                //出力グループ名の重複チェック
                $resultGroupNM = $this->HMDPS105CSVReOut->FncChkExistGroupNM($postData['txtInputGroupNM'], $groupNo);

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
                $this->HMDPS105CSVReOut->Do_transaction();
                $blnTran = TRUE;

                //出力グループの登録
                $resultGroupData = $this->HMDPS105CSVReOut->SubInsertGroupData($postData, $groupNo, $sysDate);

                if (!$resultGroupData['result']) {
                    throw new \Exception($resultGroupData['data']);
                }
                ;

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
                    $resultSyohyoDataCancel = $this->HMDPS105CSVReOut->SubUpdateSyohyoDataCancel($postData, $strSyohyoNo, $sysDate, $patternID);

                    if (!$resultSyohyoDataCancel['result']) {
                        throw new \Exception($resultSyohyoDataCancel['data']);
                    }

                    if ($objLvChkCSV == true) {
                        //出力対象にチェックが入っているデータを対象に、出力フラグ、グループ№をセットする
                        $resultSyohyo = $this->HMDPS105CSVReOut->SubUpdateSyohyoData($postData, $strSyohyoNo, $strEdaNo, $groupNo, $sysDate, $intIdx + 1, $patternID, $BusyoCD);
                        if (!$resultSyohyo['result']) {
                            throw new \Exception($resultSyohyo['data']);
                        }
                    }
                }

                //コミット
                $this->HMDPS105CSVReOut->Do_commit();
                $blnTran = FALSE;

                $sessionArray = array(
                    'HMDPS104_CSVType' => $this->Session->read("HMDPS104_CSV_TYPE_RECHK") == null ? "" : $this->Session->read("HMDPS104_CSV_TYPE_RECHK"),
                    'HMDPS104_GroupNo' => $groupNo,
                    'HMDPS104_GroupNM' => $postData['txtInputGroupNM'],
                    'HMDPS104_sysDate' => $sysDate,
                    'login_user' => $this->Session->read("login_user"),
                );
                $this->Session->delete("HMDPS104_CSV_TYPE_RECHK");
                include_once dirname(__DIR__) . '/HMDPS/HMDPS104BarCodeReadOutController.php';
                $HMDPS104CSVType = new HMDPS104BarCodeReadOutController($this->request);
                $resultCSV = $HMDPS104CSVType->CSVDownload($sessionArray);
                if (!$resultCSV['result']) {
                    throw new \Exception($resultCSV['error']);
                }

                $result['data'] = $resultCSV['data']['url'];
            }

        } catch (\Exception $e) {
            if ($blnTran) {
                $this->HMDPS105CSVReOut->Do_rollback();
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
            'data' => '',
            'error' => ''
        );

        try {
            $this->Session = $this->request->getSession();
            $result = $this->HMDPS105CSVReOut->FncChkAndSetShiwakeInfoSql($strSyohyoNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count((array) $result['data']) == 0) {
                if ($Mode > 0) {
                    throw new \Exception('証憑№:' . $strSyohyoNo . 'に該当するデータは削除されています！再度、検索ボタンを押下して最新データを取得して下さい！');
                }
            } else {
                $retCSVFLG = $result['data'][0]['特別ＣＳＶフラグ'];

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
                    if ($this->Session->read("HMDPS104_CSV_TYPE_RECHK") != NULL) {
                        if ($this->Session->read("HMDPS104_CSV_TYPE_RECHK") != $retCSVFLG) {
                            throw new \Exception('証憑№:' . $strSyohyoNo . 'のデータは１件目と出力フォーマットが違います！');
                        }
                    } else {
                        $this->Session->write("HMDPS104_CSV_TYPE_RECHK", $retCSVFLG);
                    }
                }

                if ($Mode > 0 && $result['data'][0]['ＣＳＶ出力フラグ'] == '0') {
                    throw new \Exception('証憑№:' . $strSyohyoNo . 'は他のユーザによってＣＳＶ出力をキャンセルされています！');
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
            $result = $this->HMDPS105CSVReOut->fncCheckOffGroup($strSyohyoNo);

            if (!$result['result']) {
                throw new \Exception($result['data']);
            }

            if (count((array) $result['data']) == 0) {
                $result['result'] = TRUE;
            }

            if ($this->ClsComFncHMDPS->FncNv($result['data'][0]['グループ番号']) != $groupNo && empty($result['data'][0]['グループ番号']) == false) {
                throw new \Exception('証憑№:' . $strSyohyoNo . 'に該当するデータは他のユーザによって更新されています！再度、検索ボタンを押下して最新データを取得して下さい！');
            }

        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

}
