<?php
/**
 *
 * R4連携集計システムダウンロード
 *
 * @alias FrmGenka
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                   XXXXXX                        FCSDL
 * 20151105        BUG#2249                                                  Yuanjh
 * 20151109        BUG#2264                                                  Yuanjh
 *
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use App\Model\R4\Component\ClsFileObserver;

// App::uses('ClsFileObserver', 'Model/R4/Component');
class ClsFileObserverComponent extends Component
{
    protected $Flash;
    protected $Auth;
    public $ClsComFnc;
    public $ClsFileObserver;

    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);
        $this->ClsComFnc = $registry->load('ClsComFnc');
    }
    // var $components = array('ClsComFnc');
    public $enmState = array(
        "Normal" => 0,
        "NormalEnd" => 1,
        "ErrIrregular" => 8,
        "ErrApp" => 9
    );

    //**********************************************************************
    //処 理 名：FTS_TRANSFER_LIST_TBLの更新
    //関 数 名：fncUpdTrnTbl
    //引     数：objDB
    //　    　   ：strID
    //　    　   ：strDate
    //　    　   ：strTime
    //　    　   ：strSyain
    //戻り値　：Integer   （-1:ｼｽﾃﾑｴﾗｰ / 0:該当ﾃﾞｰﾀ無し / 1:正常終了）
    //処理説明：
    //**********************************************************************
    public function fncUpdTrnTbl($strId, $strStartDate, $strStartTime, $strClientNM, $intStep, $intState, &$strMessage, $blnUpd = true, $strDBLink = "")
    {
        try {
            $this->ClsFileObserver = new ClsFileObserver();
            $result = $this->ClsFileObserver->fncUpdTrnTbl($strId, $strStartDate, $strStartTime, $strClientNM, $intStep, $intState, $strMessage, $blnUpd, $strDBLink);

            if ($result["result"] == false) {
                throw new \Exception($result["data"]);
            }

            return true;
        } catch (\Exception $e) {
            $strMessage = "ERR:HFTS_TRANSFER_LISTﾃｰﾌﾞﾙの更新に失敗しました(ｴﾗｰ内容：" . $e->getMessage() . ")";
            return False;
        }
    }

    //**********************************************************************
    //処 理 名：排他制御
    //関 数 名：fncControlCheck
    //引    数：MyControl　(I)ｺﾝﾄﾛｰﾙ番号
    //                      1.ﾀﾞｳﾝﾛｰﾄﾞ 2.取込処理 3.注文書系CSV 4.登録予定CSV
    //                      5.新車納品書CSV 6.売掛CSV 7.会計CSV
    //戻 り 値：True:実行可能　False:実行中断
    //処理説明：排他制御を行う
    //**********************************************************************
    public function fncControlCheck($strMyControl)
    {

        try {
            $this->ClsFileObserver = new ClsFileObserver();
            $result = $this->ClsFileObserver->fncControlCheck();
            if ($result["result"] == false) {
                throw new \Exception($result["data"]);
            }
            if (count((array) $result["data"]) < 0) {
                return true;
            }
            //ﾀﾞｳﾝﾛｰﾄﾞ
            if ($result["data"][0]['LOCK_ID_1'] == "1") {
                if ($strMyControl == "1" || $strMyControl == "2" || $strMyControl == "8" || $strMyControl == "9") {
                    return false;
                }
            }
            //取込処理
            if ($result["data"][0]['LOCK_ID_2'] == "1") {
                return false;
            }
            //注文書系CSV
            if ($result["data"][0]['LOCK_ID_3'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "3" || $strMyControl == "8") {
                    return false;
                }
            }
            //登録予定CSV
            if ($result["data"][0]['LOCK_ID_4'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "4" || $strMyControl == "9") {
                    return false;
                }
            }
            //新車納品書CSV
            if ($result["data"][0]['LOCK_ID_5'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "5") {
                    return false;
                }
            }
            //売掛CSV
            if ($result["data"][0]['LOCK_ID_6'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "6") {
                    return false;
                }
            }
            //会計CSV
            if ($result["data"][0]['LOCK_ID_7'] == "1") {
                if ($strMyControl == "2" || $strMyControl == "7") {
                    return false;
                }
            }
            //注文書個別ﾀﾞｳﾝﾛｰﾄﾞ
            if ($result["data"][0]['LOCK_ID_8'] == "1") {
                if ($strMyControl == "1" || $strMyControl == "2" || $strMyControl == "3" || $strMyControl == "8") {
                    return false;
                }
            }
            //登録予定個別ﾀﾞｳﾝﾛｰﾄﾞ
            if ($result["data"][0]['LOCK_ID_9'] == "1") {
                if ($strMyControl == "1" || $strMyControl == "2" || $strMyControl == "9" || $strMyControl == "4") {
                    return false;
                }
            }
            return True;
        } catch (\Exception $e) {
        }
    }

    public function FncInsHFTSTRANSFERLIST($strID, $strStartDate, $strStartTime, $strClientNM, $strPara1, $strPara2, $strPara3, $strDBLink)
    {
        $strSQL = "";
        // $result = "";

        //SQL取得
        $strSQL .= $this->fncInsFTSSql();
        //パラメータに値をセット
        $strSQL = str_replace("@ID", $strID, $strSQL);
        $strSQL = str_replace("@START_DATE", $strStartDate, $strSQL);
        $strSQL = str_replace("@START_TIME", $strStartTime, $strSQL);
        $strSQL = str_replace("@CLIENT_NAME", $strClientNM, $strSQL);
        $strSQL = str_replace("@STATE", $this->enmState['Normal'], $strSQL);
        $strSQL = str_replace("@PARA1", $strPara1, $strSQL);
        $strSQL = str_replace("@PARA2", $strPara2, $strSQL);
        $strSQL = str_replace("@PARA3", $strPara3, $strSQL);
        $strSQL = str_replace("@DBLINK", $strDBLink, $strSQL);

        return $strSQL;
    }

    public function fncInsFTSSql()
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HFTS_TRANSFER_LIST@DBLINK" . "\r\n";
        $strSQL .= "(      ID" . "\r\n";
        $strSQL .= ",      START_DATE" . "\r\n";
        $strSQL .= ",      START_TIME" . "\r\n";
        $strSQL .= ",      CLIENT_NAME" . "\r\n";
        $strSQL .= ",      END_DATE" . "\r\n";
        $strSQL .= ",      END_TIME" . "\r\n";
        $strSQL .= ",      STEP" . "\r\n";
        $strSQL .= ",      STATE" . "\r\n";
        $strSQL .= ",      KAKUNIN" . "\r\n";
        $strSQL .= ",      MESSAGE" . "\r\n";
        $strSQL .= ",      PARA1" . "\r\n";
        $strSQL .= ",      PARA2" . "\r\n";
        $strSQL .= ",      PARA3" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= " VALUES" . "\r\n";
        $strSQL .= "(      '@ID'" . "\r\n";
        $strSQL .= ",      '@START_DATE'" . "\r\n";
        $strSQL .= ",      '@START_TIME'" . "\r\n";
        $strSQL .= ",      '@CLIENT_NAME'" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      0" . "\r\n";
        $strSQL .= ",      '@STATE'" . "\r\n";
        $strSQL .= ",      0" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      '@PARA1'" . "\r\n";
        $strSQL .= ",      '@PARA2'" . "\r\n";
        $strSQL .= ",      '@PARA3'" . "\r\n";
        $strSQL .= ")" . "\r\n";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：システム日付取得
    //関 数 名：FncGetSysDate
    //引    数：strFormat  (I)ﾌｫｰﾏｯﾄ
    //戻 り 値：日付(エラーの場合はNothing)
    //処理説明：サーバーのシステム日付を取得する。
    //**********************************************************************
    public function Fnc_GetSysDate(&$strMessage, $strFormat = 'Y-m-d')
    {
        try {
            $this->ClsFileObserver = new ClsFileObserver();
            $result = $this->ClsFileObserver->Fnc_GetSysDate();
            if ($result["result"] == false) {
                throw new \Exception($result["data"]);
            }
            $strDate = strtotime($result["data"][0]['SYS_DATE']);
            $strDate = date($strFormat, $strDate);
            return $strDate;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return NULL;
        }
    }

    public function fncParaSet($strId, $strStartDate, $strStartTime, $strClientNM, &$strPara1, &$strPara2, &$strPara3, &$strMessage)
    {
        $objDr = "";
        try {
            $this->ClsFileObserver = new ClsFileObserver();
            $result = $this->ClsFileObserver->fncParaSet($strId, $strStartDate, $strStartTime, $strClientNM);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objDr = $result['data'];
            //ﾃﾞｰﾀが存在しない場合はｴﾗｰ
            if (count((array) $objDr) <= 0) {
                $strMessage = "ERR:HFTS_TRANSFER_LISTに登録されていません";
                return FALSE;
            }

            //ダウンロード条件を変数に格納
            $strPara1 = rtrim($this->ClsComFnc->FncNv($objDr["PARA1"]));
            $strPara2 = rtrim($this->ClsComFnc->FncNv($objDr["PARA2"]));
            $strPara3 = rtrim($this->ClsComFnc->FncNv($objDr["PARA3"]));

            //正常終了
            return TRUE;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }
    }

    //2009/11/19 INS Start
    //**********************************************************************
    //処 理 名：ロック制御（R4連携集計システムダウンロード用)
    //関 数 名：fncLockStateUpd
    //引    数：strTableID　(I)テーブルID
    //　　　　：strState    (I)状態　（0:停止中　1：ダウンロード実行中　2：インポート実行中）
    //　　　　：strMessage  (I)エラーメッセージ
    //戻 り 値：True:実行可能　False:実行中断
    //処理説明：排他制御を行う
    //**********************************************************************
    public function fncLockStateUpd($strDLKind, $strState, &$strMessage)
    {
        try {
            $this->ClsFileObserver = new ClsFileObserver();
            $result = $this->ClsFileObserver->fncLockStateUpd($strDLKind, $strState);
            if (!$result['result']) {
                $strMessage = $result['data'];
                return FALSE;
            }
            return TRUE;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }
    }

    //**********************************************************************
    //処 理 名：ロック状況チェック（R4連携集計システムダウンロード用)
    //関 数 名：FncLockCheck
    //引    数：strTableID　(I)テーブルID
    //　　　　：strStep     (I)ステップ　(1:ダウンロード　2:インポート)
    //　　　　：strMessage  (I)エラーメッセージ
    //戻 り 値：True:実行可能　False:実行中断
    //処理説明：排他制御を行う
    //**********************************************************************
    public function fcLockCheck($strTableID, $strStep, &$strMessage)
    {
        $objdr = "";
        try {
            $this->ClsFileObserver = new ClsFileObserver();
            $result = $this->ClsFileObserver->fcLockCheck($strTableID);
            if ($result['result']) {
                $strMessage = $result['data'];
                return FALSE;
            }
            $objdr = $result['data'];
            if (count((array) $objdr) <= 0) {
                $strMessage = "R4連携集計システムのデータ受信テーブルの設定が行われていません。管理者にお問合せ下さい。";
                return FALSE;
            }
            if ($strStep == "1") {
                //ダウンロードの場合、実行日が""以外であればまだ取込が行われていないのでエラー
                if ($this->ClsComFnc->FncNv($objdr["ACT_DT"]) != "") {
                    $strMessage = "R4連携集計システム用の取込が終了していません！取込を行ってからダウンロードを実行してください。";
                    return FALSE;
                }
            } elseif ($strStep == "2") {
                //インポートの場合、実行日が空白の場合はダウンロードが行われていないということになるので、エラー
                echo "testing --start";
                echo $this->ClsComFnc->FncNv($objdr["ACT_DT"]);
                echo "testing --end";
                if ($this->ClsComFnc->FncNv($objdr["ACT_DT"]) == "") {
                    $strMessage = "R4連携集計システム用のダウンロードが行われていません！インポートはダウンロード後に行います。";
                    return FALSE;
                }
                //2009/12/15 INS Start
            } elseif ($strStep == "3") {
                //R4データ結合テーブル作成の場合、実行日が""以外であればまだ取込が行われていないのでエラー
                if ($this->ClsComFnc->FncNv($objdr["ACT_DT"]) != "") {
                    $strMessage = "R4連携集計システム用の取込が終了していません！取込を行ってからダウンロードを実行してください。";
                    return FALSE;
                }
                //2009/12/15 INS End
            }

            //排他制御
            switch ($this->ClsComFnc->FncNv($objdr["ACT_STATE"])) {
                case "0":
                    //停止中
                    //ダウンロード可能
                    break;
                case "1":
                    //ダウンロード実行中
                    $strMessage = "他のユーザがR4連携集計システム用のダウンロード中です。少し時間を置いて再度実行してください。";
                    return FALSE;
                case "2":
                    //インポート実行中
                    $strMessage = "他のユーザがR4連携集計システム用のインポート（取込）中です。少し時間を置いて再度実行してください。";
                    return FALSE;
            }

            return TRUE;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }
    }

    //**********************************************************************
    //処 理 名：ロック状況チェック（R4連携集計システムダウンロード用)
    //関 数 名：FncLockCheck
    //引    数：strTableID　(I)テーブルID
    //　　　　：strStep     (I)ステップ　(1:ダウンロード　2:インポート)
    //　　　　：strMessage  (I)エラーメッセージ
    //戻 り 値：True:実行可能　False:実行中断
    //処理説明：排他制御を行う
    //**********************************************************************
    public function fcLockCheckDBLink($strTableID, $strStep, &$strMessage, $strDBLink = "")
    {


        $objdr = "";
        try {
            $this->ClsFileObserver = new ClsFileObserver();
            $result = $this->ClsFileObserver->fcLockCheckDBLink($strTableID, $strDBLink);
            //20140901 ysj edit start
            //--if ($result['result'])
            if (!$result['result'])
            //20140901 ysj edit end
            {
                $strMessage = $result['data'];
                return FALSE;
            }
            $objdr = $result['data'];


            if (count((array) $objdr) <= 0) {
                $strMessage = "R4連携集計システムのデータ受信テーブルの設定が行われていません。管理者にお問合せ下さい。";
                return FALSE;
            }
            if ($strStep == "1") {

                //ダウンロードの場合、実行日が""以外であればまだ取込が行われていないのでエラー
                //20151105  Yuanjh ADD S.
                //if ($this -> ClsComFnc -> FncNv($objdr["ACT_DT"]) != "")
                if ($this->ClsComFnc->FncNv($objdr[0]["ACT_DT"]) != "")
                //20151105  Yuanjh ADD E.
                {
                    $strMessage = "R4連携集計システム用の取込が終了していません！取込を行ってからダウンロードを実行してください。";
                    return FALSE;
                }
            } elseif ($strStep == "2") {

                //インポートの場合、実行日が空白の場合はダウンロードが行われていないということになるので、エラー
                if ($this->ClsComFnc->FncNv($objdr[0]["ACT_DT"]) == "") {
                    $strMessage = "R4連携集計システム用のダウンロードが行われていません！インポートはダウンロード後に行います。";
                    return FALSE;
                }
                //2009/12/15 INS Start
            } elseif ($strStep == "3") {
                //R4データ結合テーブル作成の場合、実行日が""以外であればまだ取込が行われていないのでエラー
                if ($this->ClsComFnc->FncNv($objdr[0]["ACT_DT"]) != "") {
                    $strMessage = "R4連携集計システム用の取込が終了していません！取込を行ってからダウンロードを実行してください。";
                    return FALSE;
                }
                //2009/12/15 INS End
            }

            //排他制御
            //--20151110 Yuanjh  UPD  S.
            //switch ($this->ClsComFnc->FncNv($objdr["ACT_STATE"]))
            //--20151110 Yuanjh  UPD  E.
            switch ($this->ClsComFnc->FncNv($objdr[0]["ACT_STATE"])) {
                case "0":
                    //停止中
                    //ダウンロード可能
                    break;
                case "1":
                    //ダウンロード実行中
                    $strMessage = "他のユーザがR4連携集計システム用のダウンロード中です。少し時間を置いて再度実行してください。";
                    return FALSE;
                case "2":
                    //インポート実行中
                    $strMessage = "他のユーザがR4連携集計システム用のインポート（取込）中です。少し時間を置いて再度実行してください。";
                    return FALSE;
            }
            return TRUE;

        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }
    }

}
