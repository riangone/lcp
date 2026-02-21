<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

// App::uses('ClsLogControl', 'Model/R4/Component');
// App::uses('ClsLogControlJksys', 'Model/JKSYS/Component');
use App\Model\R4\Component\ClsLogControl;
use App\Model\JKSYS\Component\ClsLogControlJksys;
use Cake\Controller\ComponentRegistry;

class ClsLogControlComponent extends Component
{
    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);
        $this->ClsComFnc = $registry->load('ClsComFnc');
        $this->ClsComFncJKSYS = $registry->load('ClsComFncJKSYS');
    }
    public $ClsLogControl = '';
    public $ClsComFnc;
    public $ClsComFncJKSYS;
    public $ClsLogControlJksys;
    //*************************************
    // * 公開メソッド
    //*************************************

    /**********************************************************************
    '処 理 名：ログ情報登録
    '関 数 名：fncLogEntry
    '引    数：strID     (I)ID(帳票："001")
    '　    　：strProID  (I)プログラムＩＤ
    '　    　：intState  (I)状態フラグ
    '        ：intoutCnt (I)出力件数
    '        ：strItem1～10 (I)出力条件
    '戻 り 値：ハッシュテーブル
    '処理説明：ログ情報登録
    **********************************************************************/

    public function fncLogEntry($strProID, $intState, $lngOutCnt, $strItem1 = "", $strItem2 = "", $strItem3 = "", $strItem4 = "", $strItem5 = "", $strItem6 = "", $strItem7 = "", $strItem8 = "", $strItem9 = "", $strItem10 = "", $strItem11 = "", $strItem12 = "", $strItem13 = "", $strItem14 = "", $strItem15 = "", $strItem16 = "", $strItem17 = "", $strItem18 = "", $strItem19 = "", $strItem20 = "", $strRecCnt = 1)
    {
        // $strsql = "";

        $this->ClsLogControl = new ClsLogControl();

        try {
            $tblArray = array();
            $tblArray["ID"] = "001";
            $tblArray["SYS_KB"] = "R4";
            //clsComFnc.GSYSTEM_KB
            $tblArray["OUT_USER_ID"] = $this->ClsLogControl->GS_LOGINUSER['strUserID'];
            //clsComFnc.gsLoginUser.strUserID
            $tblArray["OUT_CLT_NM"] = $this->ClsLogControl->GS_LOGINUSER['strClientNM'];
            //clsComFnc.gsLoginUser.strClientNM

            $tblArray["OUT_DATE"] = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");

            $tblArray["OUT_PRG_ID"] = $strProID;
            $tblArray["STATE_FLG"] = $intState;
            $tblArray["OUT_CNT"] = $lngOutCnt;

            $tblArray["ITEM01"] = $strItem1;
            $tblArray["ITEM02"] = $strItem2;
            $tblArray["ITEM03"] = $strItem3;
            $tblArray["ITEM04"] = $strItem4;
            $tblArray["ITEM05"] = $strItem5;
            $tblArray["ITEM06"] = $strItem6;
            $tblArray["ITEM07"] = $strItem7;
            $tblArray["ITEM08"] = $strItem8;
            $tblArray["ITEM09"] = $strItem9;
            $tblArray["ITEM10"] = $strItem10;

            $tblArray["ITEM11"] = $strItem11;
            $tblArray["ITEM12"] = $strItem12;
            $tblArray["ITEM13"] = $strItem13;
            $tblArray["ITEM14"] = $strItem14;
            $tblArray["ITEM15"] = $strItem15;
            $tblArray["ITEM16"] = $strItem16;
            $tblArray["ITEM17"] = $strItem17;
            $tblArray["ITEM18"] = $strItem18;
            $tblArray["ITEM19"] = $strItem19;
            $tblArray["ITEM20"] = $strItem20;
            $tblArray["REC_CNT"] = $strRecCnt;

            $result = $this->ClsLogControl->fncEntrySql($tblArray);

            if (!$result['result']) {
                throw new \Exception('ログテーブルの登録に失敗しました');
            }
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['MsgID'] = 'E9999';
            $result['Msg'] = $e->getMessage();
            //$this -> ClsComFnc -> FncMsgBox("E9999", "暗号化に失敗しました");
        }
        return $result;
    }

    public function fncLogEntryJksys($strProID, $intState, $lngOutCnt, $strItem1 = "", $strItem2 = "", $strItem3 = "", $strItem4 = "", $strItem5 = "", $strItem6 = "", $strItem7 = "", $strItem8 = "", $strItem9 = "", $strItem10 = "", $strItem11 = "", $strItem12 = "", $strItem13 = "", $strItem14 = "", $strItem15 = "", $strItem16 = "", $strItem17 = "", $strItem18 = "", $strItem19 = "", $strItem20 = "", $strRecCnt = 1)
    {
        // $strsql = "";

        $this->ClsLogControlJksys = new ClsLogControlJksys();

        try {
            $tblArray = array();
            $tblArray["ID"] = "001";
            $tblArray["SYS_KB"] = "JKSYS";
            //clsComFnc.GSYSTEM_KB
            $tblArray["OUT_USER_ID"] = $this->ClsLogControlJksys->GS_LOGINUSER['strUserID'];
            //clsComFnc.gsLoginUser.strUserID
            $tblArray["OUT_CLT_NM"] = $this->ClsLogControlJksys->GS_LOGINUSER['strClientNM'];
            //clsComFnc.gsLoginUser.strClientNM

            $tblArray["OUT_DATE"] = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");

            $tblArray["OUT_PRG_ID"] = $strProID;
            $tblArray["STATE_FLG"] = $intState;
            $tblArray["OUT_CNT"] = $lngOutCnt;

            $tblArray["ITEM01"] = $strItem1;
            $tblArray["ITEM02"] = $strItem2;
            $tblArray["ITEM03"] = $strItem3;
            $tblArray["ITEM04"] = $strItem4;
            $tblArray["ITEM05"] = $strItem5;
            $tblArray["ITEM06"] = $strItem6;
            $tblArray["ITEM07"] = $strItem7;
            $tblArray["ITEM08"] = $strItem8;
            $tblArray["ITEM09"] = $strItem9;
            $tblArray["ITEM10"] = $strItem10;

            $tblArray["ITEM11"] = $strItem11;
            $tblArray["ITEM12"] = $strItem12;
            $tblArray["ITEM13"] = $strItem13;
            $tblArray["ITEM14"] = $strItem14;
            $tblArray["ITEM15"] = $strItem15;
            $tblArray["ITEM16"] = $strItem16;
            $tblArray["ITEM17"] = $strItem17;
            $tblArray["ITEM18"] = $strItem18;
            $tblArray["ITEM19"] = $strItem19;
            $tblArray["ITEM20"] = $strItem20;
            $tblArray["REC_CNT"] = $strRecCnt;

            $result = $this->ClsLogControlJksys->fncEntrySql($tblArray);

            if (!$result['result']) {
                throw new \Exception('ログテーブルの登録に失敗しました');
            }
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['MsgID'] = 'E9999';
            $result['Msg'] = $e->getMessage();
            //$this -> ClsComFnc -> FncMsgBox("E9999", "暗号化に失敗しました");
        }
        return $result;
    }

    public function fncLogEntryHMTVE($strProID, $intState, $lngOutCnt, $strItem1 = "", $strItem2 = "", $strItem3 = "", $strItem4 = "", $strItem5 = "", $strItem6 = "", $strItem7 = "", $strItem8 = "", $strItem9 = "", $strItem10 = "", $strItem11 = "", $strItem12 = "", $strItem13 = "", $strItem14 = "", $strItem15 = "", $strItem16 = "", $strItem17 = "", $strItem18 = "", $strItem19 = "", $strItem20 = "", $strRecCnt = 1)
    {
        // $strsql = "";

        $this->ClsLogControlJksys = new ClsLogControlJksys();

        try {
            $tblArray = array();
            $tblArray["ID"] = "001";
            $tblArray["SYS_KB"] = "HMTVE";
            //clsComFnc.GSYSTEM_KB
            $tblArray["OUT_USER_ID"] = $this->ClsLogControlJksys->GS_LOGINUSER['strUserID'];
            //clsComFnc.gsLoginUser.strUserID
            $tblArray["OUT_CLT_NM"] = $this->ClsLogControlJksys->GS_LOGINUSER['strClientNM'];
            //clsComFnc.gsLoginUser.strClientNM

            $tblArray["OUT_DATE"] = $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");

            $tblArray["OUT_PRG_ID"] = $strProID;
            $tblArray["STATE_FLG"] = $intState;
            $tblArray["OUT_CNT"] = $lngOutCnt;

            $tblArray["ITEM01"] = $strItem1;
            $tblArray["ITEM02"] = $strItem2;
            $tblArray["ITEM03"] = $strItem3;
            $tblArray["ITEM04"] = $strItem4;
            $tblArray["ITEM05"] = $strItem5;
            $tblArray["ITEM06"] = $strItem6;
            $tblArray["ITEM07"] = $strItem7;
            $tblArray["ITEM08"] = $strItem8;
            $tblArray["ITEM09"] = $strItem9;
            $tblArray["ITEM10"] = $strItem10;

            $tblArray["ITEM11"] = $strItem11;
            $tblArray["ITEM12"] = $strItem12;
            $tblArray["ITEM13"] = $strItem13;
            $tblArray["ITEM14"] = $strItem14;
            $tblArray["ITEM15"] = $strItem15;
            $tblArray["ITEM16"] = $strItem16;
            $tblArray["ITEM17"] = $strItem17;
            $tblArray["ITEM18"] = $strItem18;
            $tblArray["ITEM19"] = $strItem19;
            $tblArray["ITEM20"] = $strItem20;
            $tblArray["REC_CNT"] = $strRecCnt;

            $result = $this->ClsLogControlJksys->fncEntrySql($tblArray);

            if (!$result['result']) {
                throw new \Exception('ログテーブルの登録に失敗しました');
            }
            $result['result'] = TRUE;

        } catch (\Exception $e) {
            $result['MsgID'] = 'E9999';
            $result['Msg'] = $e->getMessage();
            //$this -> ClsComFnc -> FncMsgBox("E9999", "暗号化に失敗しました");
        }
        return $result;
    }

    public function fncEncrypt($plaintext)
    {
        // $key = pack('H*', "abc");

        // $key_size = strlen($key);
        // echo "Key size: " . $key_size . "\n";
        //
        // $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        // $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        //
        // $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
        //
        // $ciphertext = $iv . $ciphertext;
        // $ciphertext_base64 = base64_encode($ciphertext);
        //
        // return $ciphertext_base64;
        return $plaintext;
    }

    // public function fncDecrypt($ciphertext_base64)
    // {
    //     $key = pack('H*', "abc");
    //     $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

    //     $ciphertext_dec = base64_decode($ciphertext_base64);

    //     # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
    //     $iv_dec = substr($ciphertext_dec, 0, $iv_size);

    //     # retrieves the cipher text (everything except the $iv_size in the front)
    //     $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    //     # may remove 00h valued characters from end of plain text
    //     $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

    //     return $plaintext_dec;
    // }

    //**********************************************************************
    //処 理 名：ﾛｸﾌｧｲﾙ出力
    //関 数 名：procWriteOutLog
    //引    数：$valWriteOutLogMode:0,3,1,9
    //　    　　  $strOutMsg:错误信息
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************
    public function procWriteOutLog($prvArgNM, $valWriteOutLogMode, $strOutMsg = "")
    {
        $result = array(
            'result' => FALSE,
            'data' => ''
        );
        try {
            $strLogFilePath = dirname(dirname(dirname(__FILE__)));
            $strLogPath = $strLogFilePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JKImportCsvPath");
            //パスチェック
            if (!file_exists($strLogPath)) {
                $file_path = dirname($strLogPath);
                if (!(is_readable($file_path) && is_writable($file_path) && is_executable($file_path))) {
                    throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
                }
                mkdir($strLogPath, 0777, TRUE);
            }
            if (!(is_readable($strLogPath) && is_writable($strLogPath) && is_executable($strLogPath))) {
                throw new \Exception('フォルダのパーミッションはエラーが発生しました。');
            }
            $prvPath_CSVLogFile = $strLogFilePath . "/" . $this->ClsComFncJKSYS->FncGetPath("JKImportCsvLog");
            $sw = fopen($prvPath_CSVLogFile, "a+");
            //log内容
            $strOut = "";
            switch ($valWriteOutLogMode) {
                case 0:
                    //開始ログ
                    $strOut = $prvArgNM . " ";
                    $strOut .= "開始: ";
                    $strOut .= date("Y/m/d H:i:s");
                    break;
                case 3:
                    //終了ログ
                    $strOut = $prvArgNM . " ";
                    $strOut .= "終了: ";
                    $strOut .= date("Y/m/d H:i:s");
                    break;
                case 1:
                    //eNormal
                    $strOut = "    ";
                    $strOut .= $strOutMsg;
                    break;
                case 9:
                    //ｴﾗｰログ
                    $strOut = "    ";
                    $strOut .= "ERR:" . $strOutMsg;
                    break;
            }
            //ファイル出力
            fwrite($sw, "\r\n" . $strOut);
            //終了ログ出力後に改行
            if ($valWriteOutLogMode == 3) {
                fwrite($sw, "\r\n");
            }

            //終了
            fclose($sw);

            $result['result'] = true;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }

        return $result;
    }

}