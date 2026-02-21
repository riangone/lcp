<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                      担当
 * YYYYMMDD           #ID                       XXXXXX                                    FCSDL
 * 20231107           機能修正　    明細の相手先金融機関名以降の項目が空白で出力される         caina
 * 20240319      本番障害.xlsx NO7　全銀協システムに アップロードしたところエラーになりました  lujunxia
 * 20240412           bug　          本来30バイト出力さえる箇所が欠落している               lujunxia
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\HDKAIKEI;

use App\Controller\AppController;
use App\Model\HDKAIKEI\HDKOut4ZenGin;

//*******************************************
// * sample controller
//*******************************************
class HDKOut4ZenGinController extends AppController
{
    // cakephp用の設定
    // cakephpがlayout変数に格納された名称のファイルを自動読込みする様に設定
    public $autoLayout = TRUE;
    public $HDKOut4ZenGin = null;
    public $Session;
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ClsComFncHDKAIKEI');
    }
    //　デフォルトで最初に実行される機能
    public function index()
    {
        $this->Session = $this->request->getSession();
        $this->Session->delete("HDKOut4ZenGin_CSV_TYPE_RECHK");
        // Viewファイル呼出し
        $this->render('index', 'HDKOut4ZenGin_layout');
    }
    //出力グループ名の重複チェック
    public function fncChkExistGroupNM()
    {
        $this->HDKOut4ZenGin = new HDKOut4ZenGin();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['data'])) {
                $resCheck = $this->inputCheck($_POST['data']);
                if (!$resCheck['result']) {
                    $res['data'] = $resCheck['data'];
                    $res['html'] = $resCheck['html'];
                    throw new \Exception('W0034');
                }
                $res = $this->HDKOut4ZenGin->FncChkExistGroupNMSql($_POST['data']['lvTxtGroupName']);
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

    public function getShiwakeData($strSyohyoNo, $Mode, $retCSVFLG, $chgColor, $HDKOut4ZenGin_CSV_TYPE = "")
    {
        $this->HDKOut4ZenGin = new HDKOut4ZenGin();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $DT = $this->HDKOut4ZenGin->FncChkAndSetShiwakeInfoSql($strSyohyoNo);
            if (!$DT['result']) {
                throw new \Exception($DT['data']);
            }
            if ($DT['row'] == 0) {
                if ($Mode > 0) {
                    $res['data']['errorMsg'] = "該当するデータは登録されていません！";

                }
                return $res;
            } else {
                //$res['data']['retCSVFLG'] = $DT['data'][0]['特別ＣＳＶフラグ'];
                if ($Mode > 0) {
                    if (substr($strSyohyoNo, 15, 2) < $DT['data'][0]['EDA_NO']) {
                        $res['data']['errorMsg'] = "notnew";
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
                if ($Mode > 0 && $DT['data'][0]['ＣＳＶ出力フラグ'] == "1") {
                    $res['data']['errorMsg'] = "証憑№:" . $strSyohyoNo . "のデータは既に全銀協出力されています！";
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
        $this->HDKOut4ZenGin = new HDKOut4ZenGin();
        $res = array(
            'result' => FALSE,
            'data' => '',
            'error' => ''
        );
        try {
            if (isset($_POST['request'])) {
                $data = $_POST['request'];
                //Mode=0[ラベルへのセットのみ], Mode=1[チェック＆メッセージ付], Mode=2[CSV出力時の再チェック]
                $DT = $this->HDKOut4ZenGin->FncSetDataSql($data);
                if (!$DT['result']) {
                    throw new \Exception($DT['data']);
                }
                $tmpJqgridShow = $this->ClsComFncHDKAIKEI->FncCreateJqGridShow($DT['data']);
                $page = $tmpJqgridShow['page'];
                $totalPage = $tmpJqgridShow['totalPage'];
                $tmpCount = $tmpJqgridShow['count'];
                $res = $this->ClsComFncHDKAIKEI->FncCreateJqGridDataIndex($DT['data'], $totalPage, $page, $tmpCount);
            }
        } catch (\Exception $e) {
            //前端jqgrid共通，显示error信息的title有问题，所以这里返回值为true
            $res['result'] = TRUE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //CSV出力ボタンクリック
    public function btnCsvOutClick()
    {
        $this->HDKOut4ZenGin = new HDKOut4ZenGin();
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        $res['data']['tranStartFlg'] = FALSE;
        try {
            $this->Session = $this->request->getSession();
            //証憑№のチェック
            $res['data']['type'] = "FncChkAndSetShiwakeInfo";
            if (isset($_POST['data']['lvGvList']) && count($_POST['data']['lvGvList']) > 0) {
                $lvGvList = $_POST['data']['lvGvList'];
                //読取書類
                $this->Session->write('HDKOut4ZenGin_CSV_TYPE_RECHK', $lvGvList[0]['SYOHYO_KBN']);
                $chooseDataArr = array();
                for ($i = 0; $i < count($lvGvList); $i++) {
                    $strSyohyoNo = $lvGvList[$i]['SYOHYO_NO_VIEW'];
                    $chgColor = "0";
                    $FncChkAndSetShiwakeInfo = $this->getShiwakeData($strSyohyoNo, 2, "", $chgColor);
                    if (!$FncChkAndSetShiwakeInfo['result']) {
                        if ($FncChkAndSetShiwakeInfo['data']['errorMsg']) {

                            if ($FncChkAndSetShiwakeInfo['data']['errorMsg'] == 'notnew') {
                                $resData = array(
                                    'chgColor' => isset($FncChkAndSetShiwakeInfo['data']['chgColor']) ? $FncChkAndSetShiwakeInfo['data']['chgColor'] : $chgColor,
                                    'rowNum' => $lvGvList[$i]['rowId'],
                                    'no' => $strSyohyoNo
                                );
                                array_push($chooseDataArr, $resData);
                            } else {
                                $res['data']['errorMsg'] = $FncChkAndSetShiwakeInfo['data']['errorMsg'];
                                throw new \Exception("W9999");
                            }
                        } else {
                            throw new \Exception($FncChkAndSetShiwakeInfo['error']);
                        }

                    }

                }
                if (count($chooseDataArr) > 0) {
                    $msg = '';
                    for ($c = 0; $c < count($chooseDataArr); $c++) {
                        $msg .= "証憑№:" . $chooseDataArr[$c]['no'] . "<br/>";
                    }
                    $res['data']['errorMsg'] = $msg . "データは最新ではありません！";
                    $res['data']['chooseData'] = $chooseDataArr;
                    throw new \Exception("W9999");
                }
            }
            $res['data']['type'] = "CsvOut";
            //グループ№の最新取得
            $groupNoRes = $this->HDKOut4ZenGin->FncGetGroupNoSql();
            if (!$groupNoRes['result']) {
                throw new \Exception($groupNoRes['data']);
            }
            if (!isset($_POST['data'])) {
                throw new \Exception("param error");
            }

            $sysDate = $this->ClsComFncHDKAIKEI->FncGetSysDate("Y/m/d H:i:s");
            $groupNo = $groupNoRes['row'] > 0 ? $groupNoRes['data'][0]["NVL(MAX(A.CSV_GROUP_NO),0)+1"] : "1";
            $lvTxtKeiriSyoribi = date_format(date_create($_POST['data']['lvTxtKeiriSyoribi']), "Ymd");

            //トランザクション開始
            $this->HDKOut4ZenGin->Do_transaction();
            $res['data']['tranStartFlg'] = TRUE;

            $params = array(
                //出力グループ名
                'lvTxtGroupName' => $_POST['data']['lvTxtGroupName'],
                // 経理処理日
                'lvTxtKeiriSyoribi' => $lvTxtKeiriSyoribi,
                'groupNo' => $groupNo,
                'sysDate' => $sysDate
            );
            //出力グループの登録
            $insRes = $this->HDKOut4ZenGin->SubInsertGroupDataSql($params);
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
                    $params['intCsvOutOrd'] = count($lvGvList) - $i;
                    $upd = $this->HDKOut4ZenGin->SubUpdateSyohyoDataSql($lvGvList[$i], $params);
                    if (!$upd['result']) {
                        throw new \Exception($upd['data']);
                    }
                }
            }
            //コミット
            $this->HDKOut4ZenGin->Do_commit();
            $res['data']['tranStartFlg'] = FALSE;

            $sessionArray = array(
                'HDKOut4ZenGin_CSVType' => $this->Session->read("HDKOut4ZenGin_CSV_TYPE_RECHK") == '仕訳伝票' ? "0" : "1",
                'HDKOut4ZenGin_GroupNo' => $groupNo,
                'HDKOut4ZenGin_GroupNM' => $_POST['data']['lvTxtGroupName'],
                'HDKOut4ZenGin_sysDate' => $sysDate,
                'login_user' => $this->Session->read("login_user"),
                // 経理処理日
                'lvTxtKeiriSyoribi' => date_format(date_create($_POST['data']['lvTxtKeiriSyoribi']), "md"),
                //選択したデータの合計金額
                'lvTxtKingakuSum' => $_POST['data']['lvTxtKingakuSum'],
                //選択した件数
                'count' => count($lvGvList)
            );
            $this->Session->delete("HDKOut4ZenGin_CSV_TYPE_RECHK");
            //CSV出力
            $download = $this->CSVDownload($sessionArray);
            if (!$download['result']) {
                throw new \Exception($download['error']);
            }
            $res['data']['url'] = $download['data']['url'];
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            if ($res['data']['tranStartFlg']) {
                $this->HDKOut4ZenGin->Do_rollback();
            }
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }

    //CSV出力処理(実行)
    public function CSVDownload($sessionArray)
    {
        $this->HDKOut4ZenGin = new HDKOut4ZenGin();
        $myfile = null;
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            $dt = $this->HDKOut4ZenGin->CSVDownloadSql($sessionArray['HDKOut4ZenGin_GroupNo']);
            if (!$dt['result']) {
                throw new \Exception($dt['data']);
            }
            //path is exist
            //出力先パス
            $tmpPath1 = dirname(dirname(dirname(dirname(__FILE__))));
            $tmpPath2 = "webroot/files/HDKAIKEI/";
            $tmpPath = $tmpPath1 . "/" . $tmpPath2;

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
                $outFloder = dirname($tmpPath);
                if (!(is_readable($outFloder) && is_writable($outFloder) && is_executable($outFloder))) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
                if (!mkdir($tmpPath, 0777, TRUE)) {
                    throw new \Exception("フォルダのパーミッションはエラーが発生しました。");
                }
            }
            //***CSV出力処理****

            $date = date_format(date_create($sessionArray['HDKOut4ZenGin_sysDate']), "YmdHis");
            $filename = "全銀協連携データ_" . $date . ".csv";

            $filefullpath = $tmpPath . $filename;
            if (file_exists($filefullpath)) {
                unlink($filefullpath);
            }
            $myfile = fopen($filefullpath, 'w');

            //'ヘッダ行
            $strOut = '';
            //データ区分,種別コード,コード区分,依頼人コード,依頼人名:固定
            $strOut .= '121 0000068078';
            //20240315 UPD START
            //$word = 'ﾋｶﾞｼｵﾉﾐﾁｼｮｳｷﾞｮｳｶｲﾊﾂｶﾌﾞｼｷｶﾞｲｼｬ';
            //$strOut .= str_pad($word, 40 + mb_strlen($word) * 2, ' ', STR_PAD_RIGHT);
            $word = 'ﾋﾛﾏﾂﾎｰﾙﾃﾞｲﾝｸﾞｽ(ｶ';
            $strOut .= str_pad($word, 38 + mb_strlen($word) * 2, ' ', STR_PAD_RIGHT);
            //20240315 UPD END

            //画面.経理処理日
            $strOut .= $sessionArray['lvTxtKeiriSyoribi'];
            //仕向金融機関番号,仕向金融機関名:固定
            $strOut .= '0169';
            $word = 'ﾋﾛｼﾏｷﾞﾝｺｳ';
            $strOut .= str_pad($word, 15 + mb_strlen($word) * 2, ' ', STR_PAD_RIGHT);
            //仕向支店番号,仕向支店名:固定
            $strOut .= '102';
            $word = 'ﾋｶﾞｼｵﾉﾐﾁｼﾃﾝ';
            $strOut .= str_pad($word, 15 + mb_strlen($word) * 2, ' ', STR_PAD_RIGHT);
            //預金種目（依頼人）,口座番号（依頼人）:固定
            $strOut .= '10904716';
            //17文字半角スペース
            $strOut .= $this->getSpace(17);
            //明細行
            for ($intIdx = 0; $intIdx < count((array) $dt['data']); $intIdx++) {
                $csvData = $dt['data'][$intIdx];
                //データ区分
                $strOut .= "2";
                //20240319 lujunxia upd s
                //20231107 caina upd s
                //被仕向金融機関番号
                $strOut .= $csvData['BANK_CD'] == '' ? $this->getSpace(4) : str_pad($csvData['BANK_CD'], 4, ' ', STR_PAD_LEFT);
                //$strOut .= $this->getSpace(4);
                //被仕向金融機関名
                $strOut .= str_pad($csvData['BANK_KANA'] == '' ? $this->getSpace(15) : $csvData['BANK_KANA'], 15 + strlen($csvData['BANK_KANA']) - mb_strlen($csvData['BANK_KANA']), ' ', STR_PAD_RIGHT);
                //$strOut .= $this->getSpace(15);
                //被仕向支店番号
                $strOut .= str_pad($csvData['BRANCH_CD'] == '' ? $this->getSpace(3) : $csvData['BRANCH_CD'], 3, ' ', STR_PAD_LEFT);
                //$strOut .= $this->getSpace(3);
                //被仕向支店名
                //20250123 lujunxia upd s
                if ($csvData['BRANCH_KANA'] == '' || is_null($csvData['BRANCH_KANA'])) {
                    $strOut .= $this->getSpace(15);
                } else {
                    $strOut .= str_pad($csvData['BRANCH_KANA'], 15 + strlen($csvData['BRANCH_KANA']) - mb_strlen($csvData['BRANCH_KANA']), ' ', STR_PAD_RIGHT);
                }
                //20250123 lujunxia upd e
                //$strOut .= $this->getSpace(15);
                //20240319 lujunxia upd e
                //手形交換所番号
                $strOut .= $this->getSpace(4);
                //20231107 caina upd e
                //預金種目:1普通 2当座 9その他->4 で出力す
                $strOut .= $csvData['YOKIN_SYUBETU'] == '' ? $this->getSpace(1) : $csvData['YOKIN_SYUBETU'];
                //口座番号
                $strOut .= str_pad($csvData['KOUZA_NO'] == '' ? $this->getSpace(7) : $csvData['KOUZA_NO'], 7, '0', STR_PAD_LEFT);
                //受取人名:先頭30バイトを出力
                //20240412 lujunxia upd s
                //$csvData['KOUZA_KN'] = mb_strimwidth($csvData['KOUZA_KN'], 0, 10);
                //20250123 lujunxia upd s
                $csvData['KOUZA_KN'] = is_null($csvData['KOUZA_KN']) ? '' : mb_strimwidth($csvData['KOUZA_KN'], 0, 30);
                //20250123 lujunxia upd e
                //20240412 lujunxia upd e
                $strOut .= str_pad($csvData['KOUZA_KN'] == '' ? $this->getSpace(30) : $csvData['KOUZA_KN'], 30 + strlen($csvData['KOUZA_KN']) - mb_strlen($csvData['KOUZA_KN']), ' ', STR_PAD_RIGHT);
                //振込金額:大連で20231107問題の検出:金額最大13ビットーskypeに返信:上位10ビットを取る
                $csvData['ZEIKM_GK'] = substr($csvData['ZEIKM_GK'], 0, 10);
                $strOut .= str_pad($csvData['ZEIKM_GK'] == '' ? $this->getSpace(10) : $csvData['ZEIKM_GK'], 10, '0', STR_PAD_LEFT);
                //新規コード,顧客コード1,2
                $strOut .= $this->getSpace(21);
                //振込指定区分
                $strOut .= "7";
                //識別表示,空きエリア
                $strOut .= $this->getSpace(8);
            }
            //(3) トレーラ・レコード  -- [8,選択した件数,選択したデータの合計金額,101文字半角スペース]
            $strOut .= '8';
            $strOut .= str_pad($sessionArray['count'], 6, '0', STR_PAD_LEFT);
            $lvTxtKingakuSum = substr($sessionArray['lvTxtKingakuSum'], 0, 12);
            $strOut .= str_pad($lvTxtKingakuSum, 12, '0', STR_PAD_LEFT);
            $strOut .= $this->getSpace(101);
            //(4) エンド・レコード  -- [9,119文字半角スペース]
            $strOut .= '9';
            $strOut .= $this->getSpace(119);
            //convert encoding
            $strOut = mb_convert_encoding($strOut, "SJIS");
            fwrite($myfile, $strOut);
            @fclose($myfile);
            $res['result'] = TRUE;
            $res['data']['url'] = "files/HDKAIKEI/" . $filename;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();

            if (is_resource($myfile)) {
                @fclose($myfile);
            }
            if ($myfile != null) {
                unset($myfile);
            }
        }
        return $res;
    }

    public function fncGetMaster()
    {
        $res = array(
            'result' => FALSE,
            'data' => [],
            'error' => ''
        );
        try {
            //科目
            $KamokuMst = $this->ClsComFncHDKAIKEI->FncGetKamokuMstValue();
            if (!$KamokuMst['result']) {
                throw new \Exception($KamokuMst['error']);
            }
            $res['data']['kamoku'] = $KamokuMst['data'];
            //部署
            $BusyoMst = $this->ClsComFncHDKAIKEI->FncGetCreatBusyoMstValue();
            if (!$BusyoMst['result']) {
                throw new \Exception($BusyoMst['data']);
            }
            $res['data']['busyo'] = $BusyoMst['data'];
            //作成担当者
            $SyainMst = $this->ClsComFncHDKAIKEI->FncGetSyainMstValue();
            if (!$SyainMst['result']) {
                throw new \Exception($SyainMst['data']);
            }
            $res['data']['tanntousya'] = $SyainMst['data'];
            $res['result'] = TRUE;
        } catch (\Exception $e) {
            $res['result'] = FALSE;
            $res['error'] = $e->getMessage();
        }
        $this->fncReturn($res);
    }
    public function inputCheck($postData)
    {
        $result = array(
            'result' => true,
            'html' => '',
            'data' => ''
        );
        try {
            if (isset($postData['lvTxtGroupName']) && !$this->ClsComFncHDKAIKEI->FncEncodeCheck($postData['lvTxtGroupName'])) {
                $result['html'] = 'lvTxtGroupName';
                throw new \Exception('出力グループ名');
            }
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }
    //半角スペース
    public function getSpace($num)
    {
        $str = '';
        for ($i = 0; $i < $num; $i++) {
            $str .= ' ';
        }
        return $str;
    }
}
