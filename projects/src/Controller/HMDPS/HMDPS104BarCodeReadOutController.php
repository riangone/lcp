<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240425      バーコード読取・CSV出力	「№」が文字化しない問題。環境依存文字のUTF-8への変換      lujunxia
 * 20240507		 バーコード読取・CSV出力		   検索されたデータをgridに追加されるの変更		   　lujunxia
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Controller\HMDPS;

use App\Controller\AppController;
use App\Model\HMDPS\HMDPS104BarCodeReadOut;

//*******************************************
// * sample controller
//*******************************************
class HMDPS104BarCodeReadOutController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    //　$aoutRender = TUREの場合は、全ての関数実行後にのViewファイルの出力を試みる
    // public $autoRender = FALSE;
    public $Session;
    public $HMDPS104BarCodeReadOut;
    public function initialize(): void
    {
        parent::initialize();
        // $this->loadComponent('RequestHandler');
        $this->loadComponent('ClsComFncHMDPS');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->Session = $this->request->getSession();
        $this->Session->delete("HMDPS104_CSV_TYPE_RECHK");
        $this->render('index', 'HMDPS104BarCodeReadOut_layout');
    }

    //出力グループ名の重複チェック
    public function fncChkExistGroupNM()
    {
        $this->HMDPS104BarCodeReadOut = new HMDPS104BarCodeReadOut();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $res = $this->HMDPS104BarCodeReadOut->FncChkExistGroupNMSql($_POST['data']['lvTxtGroupName']);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                if ($res['row'] != 0) {
                    if ($res['data'][0]["COUNT(*)"] != 0) {
                        throw new \Exception("repeatErr");
                    }
                }
            }
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //読み取りデータのチェックと読取書類ラベルへのセット
    public function fncChkAndSetShiwakeInfo()
    {
        $this->HMDPS104BarCodeReadOut = new HMDPS104BarCodeReadOut();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $strSyohyoNo = $_POST['data']['strSyohyoNo'];
                //Mode=0[ラベルへのセットのみ], Mode=1[チェック＆メッセージ付], Mode=2[CSV出力時の再チェック]
                $Mode = $_POST['data']['Mode'];
                $HMDPS104_CSV_TYPE = $_POST['data']['HMDPS104_CSV_TYPE'];
                $res = $this->getShiwakeData($strSyohyoNo, $Mode, $HMDPS104_CSV_TYPE);
                if (!$res['result']) {
                    throw new \Exception($res['error']);
                }
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    public function getShiwakeData($strSyohyoNo, $Mode, $HMDPS104_CSV_TYPE = "")
    {
        $this->HMDPS104BarCodeReadOut = new HMDPS104BarCodeReadOut();
        $this->Session = $this->request->getSession();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $DT = $this->HMDPS104BarCodeReadOut->FncChkAndSetShiwakeInfoSql($strSyohyoNo);
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            if ($DT['row'] == 0) {
                if ($Mode > 0) {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "に該当するデータは登録されていません！";

                }
                $res['data']['lvTxtYomitoriSyorui'] = "";
                return $res;
            } else {
                $res['data']['lvTxtYomitoriSyorui'] = $DT['data'][0]['読取書類'];
                $res['data']['retCSVFLG'] = $DT['data'][0]['特別ＣＳＶフラグ'];
                if ($Mode > 0) {
                    if (substr($strSyohyoNo, 15, 2) < $DT['data'][0]['EDA_NO']) {
                        $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは最新ではありません！";
                        if ($Mode == 2) {
                            $res['data']['chgColor'] = "1";
                        }
                        return $res;
                    } else
                        if (substr($strSyohyoNo, 15, 2) > $DT['data'][0]['EDA_NO']) {
                            $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "に該当するデータは登録されていません！";
                            return $res;
                        }
                }
                if ($HMDPS104_CSV_TYPE != "") {
                    if ($Mode == 1) {
                        if ($HMDPS104_CSV_TYPE != $DT['data'][0]['特別ＣＳＶフラグ']) {
                            $res['data']['errorMsg'] = "ＣＳＶ出力のフォーマットが違う伝票が読み込まれました！";
                            return $res;
                        }
                    }
                }
                if ($Mode == 2) {
                    if ($this->Session->read("HMDPS104_CSV_TYPE_RECHK") != null) {
                        if ($this->Session->read("HMDPS104_CSV_TYPE_RECHK") != $DT['data'][0]['特別ＣＳＶフラグ']) {
                            $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは１件目と出力フォーマットが違います！";
                            return $res;
                        }
                    } else {
                        $this->Session->write("HMDPS104_CSV_TYPE_RECHK", $DT['data'][0]['特別ＣＳＶフラグ']);
                    }
                }
                if ($Mode > 0 && $DT['data'][0]['ＣＳＶ出力フラグ'] == "1") {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは既にＣＳＶ出力されています！";
                    return $res;
                }
                if ($Mode > 0 && $DT['data'][0]['削除フラグ'] == "1") {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは既に削除されています！";
                    return $res;
                }
                if ($Mode > 0 && $DT['data'][0]['印刷フラグ'] == "0") {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは伝票印刷が行われていません！";
                    return $res;
                }
            }
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        return $res;
    }

    //Gridへのデータセット
    public function fncSetData()
    {
        $this->HMDPS104BarCodeReadOut = new HMDPS104BarCodeReadOut();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            //20240507 lujunxia upd s
            // if (isset($_POST['request'])) {
            if (isset($_POST['data'])) {
                //$strSyohyoNo = $_POST['request']['strSyohyoNo'];
                $strSyohyoNo = $_POST['data']['strSyohyoNo'];
                //Mode=0[ラベルへのセットのみ], Mode=1[チェック＆メッセージ付], Mode=2[CSV出力時の再チェック]
                $res = $this->HMDPS104BarCodeReadOut->FncSetDataSql($strSyohyoNo);
                if (!$res['result']) {
                    throw new \Exception($res['data']);
                }
                // $tmpJqgridShow = $this->ClsComFncHMDPS->FncCreateJqGridShow($DT['data']);
                // $sortstr = $tmpJqgridShow['sortStr'];
                // $start = $tmpJqgridShow['start'];
                // $limit = $tmpJqgridShow['limit'];
                // $page = $tmpJqgridShow['page'];
                // $totalPage = $tmpJqgridShow['totalPage'];
                // $tmpCount = $tmpJqgridShow['count'];
                // $res = $this->ClsComFncHMDPS->FncCreateJqGridDataIndex($DT['data'], $totalPage, $page, $tmpCount);
                //20240507 lujunxia upd e
            }
        } catch (\Exception $e) {
            //20240507 lujunxia upd s
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            //$res['result'] = TRUE;
            $res['result'] = FALSE;
            //20240507 lujunxia upd e
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //CSV出力ボタンクリック
    public function btnCsvOutClick()
    {
        $this->HMDPS104BarCodeReadOut = new HMDPS104BarCodeReadOut();
        $this->Session = $this->request->getSession();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $res['data']['tranStartFlg'] = FALSE;
        try {
            //証憑№のチェック
            $res['data']['type'] = "FncChkAndSetShiwakeInfo";
            if (isset($_POST['data']['lvGvList']) && count($_POST['data']['lvGvList']) > 0) {
                $lvGvList = $_POST['data']['lvGvList'];
                for ($i = 0; $i < count($lvGvList); $i++) {
                    if ($lvGvList[$i]['strIsCSVOut'] == "1") {
                        $strSyohyoNo = $lvGvList[$i]['SYOHYO_NO_VIEW'];
                        $chgColor = "0";
                        $FncChkAndSetShiwakeInfo = $this->getShiwakeData($strSyohyoNo, 2);
                        //読取書類
                        $res['data']['lvTxtYomitoriSyorui'] = isset($FncChkAndSetShiwakeInfo['data']['lvTxtYomitoriSyorui']) ? $FncChkAndSetShiwakeInfo['data']['lvTxtYomitoriSyorui'] : "";
                        if (!$FncChkAndSetShiwakeInfo['result']) {
                            $res['data']['chgColor'] = isset($FncChkAndSetShiwakeInfo['data']['chgColor']) ? $FncChkAndSetShiwakeInfo['data']['chgColor'] : $chgColor;
                            $res['data']['rowNum'] = $i;
                            if ($FncChkAndSetShiwakeInfo['data']['errorMsg']) {
                                $res['data']['errorMsg'] = $FncChkAndSetShiwakeInfo['data']['errorMsg'];
                                throw new \Exception("W9999");
                            } else {
                                throw new \Exception($FncChkAndSetShiwakeInfo['error']);
                            }

                        }
                    }
                }
            }
            $res['data']['type'] = "CsvOut";
            //グループ№の最新取得
            $groupNoRes = $this->HMDPS104BarCodeReadOut->FncGetGroupNoSql();
            if (!$groupNoRes['result']) {
                throw new \Exception($groupNoRes['data']);
            }
            $sysDate = $this->ClsComFncHMDPS->FncGetSysDate("Y/m/d H:i:s");
            $groupNo = $groupNoRes['row'] > 0 ? $groupNoRes['data'][0]["NVL(MAX(A.CSV_GROUP_NO),0)+1"] : "1";
            //トランザクション開始
            $this->HMDPS104BarCodeReadOut->Do_transaction();
            $res['data']['tranStartFlg'] = TRUE;
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }
            $lvTxtKeiriSyoribi = date_format(date_create($_POST['data']['lvTxtKeiriSyoribi']), "Ymd");
            $params = array(
                //出力グループ名
                'lvTxtGroupName' => $_POST['data']['lvTxtGroupName'],
                // 経理処理日
                'lvTxtKeiriSyoribi' => $lvTxtKeiriSyoribi,
                'groupNo' => $groupNo,
                'sysDate' => $sysDate
            );
            //出力グループの登録
            $insRes = $this->HMDPS104BarCodeReadOut->SubInsertGroupDataSql($params);
            if (!$insRes['result']) {
                throw new \Exception($insRes['data']);
            }
            $BusyoCD = $this->Session->read('BusyoCD');
            if (isset($BusyoCD) == FALSE) {
                $res['data']['msg'] = 'W9999';
                throw new \Exception('表示できる部署が存在しません。管理者にお問い合わせください。');
            }
            //仕訳データの更新
            if (isset($_POST['data']['lvGvList']) && count($_POST['data']['lvGvList']) > 0) {
                $params['PatternID'] = $this->Session->read('PatternID');
                $params['BusyoCD'] = $BusyoCD;
                $params['CONST_ADMIN_PTN_NO'] = $_POST['data']['CONST_ADMIN_PTN_NO'];
                $params['CONST_HONBU_PTN_NO'] = $_POST['data']['CONST_HONBU_PTN_NO'];
                $lvGvList = $_POST['data']['lvGvList'];
                for ($i = 0; $i < count($lvGvList); $i++) {
                    if ($lvGvList[$i]['strIsCSVOut'] == "1") {
                        $params['intCsvOutOrd'] = count($lvGvList) - $i;
                        $upd = $this->HMDPS104BarCodeReadOut->SubUpdateSyohyoDataSql($lvGvList[$i], $params);
                        if (!$upd['result']) {
                            throw new \Exception($upd['data']);
                        }
                    }
                }
            }
            //コミット
            $this->HMDPS104BarCodeReadOut->Do_commit();
            $res['data']['tranStartFlg'] = FALSE;

            $sessionArray = array(
                'HMDPS104_CSVType' => $this->Session->read("HMDPS104_CSV_TYPE_RECHK") == null ? "" : $this->Session->read("HMDPS104_CSV_TYPE_RECHK"),
                'HMDPS104_GroupNo' => $groupNo,
                'HMDPS104_GroupNM' => $_POST['data']['lvTxtGroupName'],
                'HMDPS104_sysDate' => $sysDate,
                'login_user' => $this->Session->read("login_user")
            );
            $this->Session->delete("HMDPS104_CSV_TYPE_RECHK");
            //CSV出力
            $download = $this->CSVDownload($sessionArray);
            if (!$download['result']) {
                throw new \Exception($download['error']);
            }
            $res['data']['url'] = $download['data']['url'];
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($res['data']['tranStartFlg']) {
                $this->HMDPS104BarCodeReadOut->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //CSV出力処理(実行)
    public function CSVDownload($sessionArray)
    {
        $this->HMDPS104BarCodeReadOut = new HMDPS104BarCodeReadOut();
        $myfile = null;
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $dt = $this->HMDPS104BarCodeReadOut->CSVDownloadSql($sessionArray['HMDPS104_CSVType'], $sessionArray['HMDPS104_GroupNo']);
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            //path is exist
            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            //            $tmpPath2 = "webroot/files/HMDPS/";
//            $tmpPath = $tmpPath1 . "/" . $tmpPath2;
            $tmpPath = "files/HMDPS/";

            if (file_exists($tmpPath)) {
                if (!(is_readable($tmpPath) && is_writable($tmpPath) && is_executable($tmpPath))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                //フォルダ削除
                $dh = opendir($tmpPath);
                while ($file = readdir($dh)) {
                    if ($file != "." && $file != ".." && (strpos($file, "外部仕訳データ") !== false || strpos($file, "外部支払データ") !== false)) {
                        $fullpath = $tmpPath . "/" . $file;
                        if (!is_dir($fullpath)) {
                            unlink($fullpath);
                        } else {
                            rmdir($tmpPath);
                        }
                    }
                }
            } else {
                //                $outFloder = $tmpPath1 . "/webroot/files/";
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            //***CSV出力処理****
            $filename = "";
            $date = date_format(date_create($sessionArray['HMDPS104_sysDate']), "YmdHis");
            if ($sessionArray['HMDPS104_CSVType'] == "0") {
                $filename = "外部仕訳データ_" . $date . ".txt";
            } else {
                $filename = "外部支払データ_" . $date . ".txt";
            }

            $filefullpath = $tmpPath . $filename;
            if (file_exists($filefullpath)) {
                unlink($filefullpath);
            }
            $myfile = fopen($filefullpath, 'w');

            //'ヘッダ行  -- [3634,A,グループ№,取込件数,作成者,3634,000,ＣＳＶ出力日,1,グループ名]
            //[\0:スペース]
            $strOut = '';
            //3634,A,
            $strOut .= '3634' . "\t" . 'A';
            $strOut .= "\t";
            //グループ№,
            $strOut .= $sessionArray['HMDPS104_GroupNo'];
            $strOut .= "\t";
            //取込件数
            $strOut .= $dt['row'];
            $strOut .= "\t";
            //作成者
            $strOut .= $sessionArray['login_user'];
            $strOut .= "\t";
            //3634,000,
            $strOut .= '3634' . "\t" . '000';
            $strOut .= "\t";
            //ＣＳＶ出力日
            $strOut .= date_format(date_create($sessionArray['HMDPS104_sysDate']), "Ymd");
            $strOut .= "\t";
            // 1
            $strOut .= '1';
            $strOut .= "\t";
            //グループ名
            $strOut .= $sessionArray['HMDPS104_GroupNM'];
            $strOut .= "\r\n";

            //明細行
            for ($intIdx = 0; $intIdx < count((array) $dt['data']); $intIdx++) {
                $strOut .= str_replace("〜", "～", $dt['data'][$intIdx]['STRCSV']);
                $strOut .= "\r\n";
            }

            //フッタ行  -- [3634,C,グループ№]
            $strOut .= '3634' . "\t" . 'C';
            $strOut .= "\t";
            //グループ№
            $strOut .= $sessionArray['HMDPS104_GroupNo'];
            //convert encoding
            //20240425 lujunxia upd s
            //$strOut = mb_convert_encoding($strOut, "SJIS");
            $strOut = mb_convert_encoding($strOut, "SJIS-win");
            //20240425 lujunxia upd e
            fwrite($myfile, $strOut);
            @fclose($myfile);
            $res['result'] = TRUE;
            $res['data']['url'] = "files/HMDPS/" . $filename;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();

            if (is_resource($myfile)) {
                fclose($myfile);
            }
            if ($myfile != null) {
                unset($myfile);
            }
        }
        return $res;
    }

}
