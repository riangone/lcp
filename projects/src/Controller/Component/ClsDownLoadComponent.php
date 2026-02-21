<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\R4\Component\ClsDownLoad;
use App\Model\R4\Component\ClsComDLSql;
use Cake\Controller\ComponentRegistry;

class ClsDownLoadComponent extends Component
{
    public $ClsFncLog;
    public $ClsComFnc;
    public $ClsComDLSql;
    public $objLog;
    public $ClsDownLoad;
    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);
        $this->ClsFncLog = $registry->load('ClsFncLog');
        $this->ClsComFnc = $registry->load('ClsComFnc');
        $this->ClsComDLSql = new ClsComDLSql();
        $this->objLog = $this->ClsFncLog->GS_OUTPUTLOG;
        $this->ClsDownLoad = new ClsDownLoad();
    }
    //ダウンロードログ出力パス
    public $strDownLoadPath = "";
    //開始日付
    public $strgetdate = "";
    //終了日付
    public $strEndDate = "";
    //Log出力名
    public $strLogName = "";
    //ダウンロードのﾀｲﾌﾟ
    public $intDownLoadType = "";
    public $lngCntTbl = array(
        0,
        0,
        0,
        0,
        0
    );
    //テーブルID
    public $intTableID = "";
    //オーバーフロー時のｴﾗｰﾛｸﾞﾊﾟｽ
    public $strErrLogPath = "";
    public $dtlGetDate = "";
    //グループID　"G01":日次ダウンロード　"G02":個別ダウンロード　"G03":登録予定
    public $strGroupID = "";
    public $strMessage = "";
    // public $objLog = "";
    // public $ClsDownLoad = "";

    // public function initialize($config): void
    // {
    //     $this->objLog = $this->ClsFncLog->GS_OUTPUTLOG;
    //     $this->ClsDownLoad = new ClsDownLoad();
    // }


    //**********************************************************************
    //処 理 名：開始ログ出力
    //関 数 名：fncStartLog
    //引    数：$strID
    //戻 り 値：True:正常終了　False:異常終了
    //処理説明：開始ログ出力
    //**********************************************************************
    public function fncStartLog($strID, &$strMessage, $strIndiviCMN = "9999999999")
    {
        // $strErrMsg = "";
        $strErrNO = "";
        $tmpPath = dirname(dirname(dirname(__FILE__))) . "/tmp";
        // $result = "";
        try {
            //開始日付を取得
            $this->strgetdate = $this->ClsComFnc->FncGetSysDate("y/m/d H:i:s");

            //ダウンロードログﾊﾟｽ取得
            $this->strDownLoadPath = $this->ClsComFnc->FncGetPath("downloadpath");

            //ﾀﾞｳﾝﾛｰﾄﾞ時、注文書の特別仮装品合計額のオーバーフロー時ログ出力用ﾊﾟｽ
            $this->strErrLogPath = $this->ClsComFnc->FncGetPath("downloadcheckpath");

            //フォルダﾁｪｯｸ
            $strErrNO = $this->ClsFncLog->fncOutChk($this->strDownLoadPath);

            if ($strErrNO != "") {
                $this->strDownLoadPath = "logs/DownLoad.log";
            }

            //デフォルト
            if ($this->strDownLoadPath == "") {
                $this->strDownLoadPath = "logs/DownLoad.log";
            }

            $this->strDownLoadPath = $tmpPath . "/" . $this->strDownLoadPath;

            $this->strErrLogPath = $tmpPath . "/" . $this->strErrLogPath;

            switch ($strID) {
                case "日次ダウンロード":
                    $this->strGroupID = "G01";
                    break;
                case "個別ダウンロード":
                    $this->strGroupID = "G02";
                    break;
                case "注文書関連データダウンロード":
                    $this->strGroupID = "G02";
                    break;
                case "登録予定ダウンロード":
                    $this->strGroupID = "G03";
                case "経理ダウンロード":
                    if ($strIndiviCMN == "") {
                        $this->strGroupID = "G04";
                    } else {
                        $this->strGroupID = "G05";
                    }
            }

            //構造体に格納(LOG)
            if ($strID == "注文書関連データダウンロード") {
                $this->ClsFncLog->GS_OUTPUTLOG['strID'] = $strID . "(注文書番号＝" . $strIndiviCMN . ")";
            } elseif ($strID == "登録予定ダウンロード") {
                $this->ClsFncLog->GS_OUTPUTLOG['strID'] = $strID . "(登録予定日＝" . $strIndiviCMN . ")";
            } else {
                $this->ClsFncLog->GS_OUTPUTLOG['strID'] = $strID;
            }

            $this->ClsFncLog->GS_OUTPUTLOG['strStartDate'] = $this->strgetdate;

            //開始LOG出力
            $this->ClsFncLog->fncDownLoadLog($this->strDownLoadPath, $this->ClsFncLog->GS_OUTPUTLOG, 0);

            //ログの状態をOKに設定
            $this->ClsFncLog->GS_OUTPUTLOG['strState'] = "OK";
            return TRUE;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }

    }

    //**********************************************************************
    //処 理 名：データ受信テーブルの更新
    //関 数 名：fncUpdateDataRecep
    //引    数：無し
    //戻 り 値：SQL文
    //処理説明：データ受信テーブルの更新(ID='5'で更新)
    //**********************************************************************
    public function FncUpdateDataRecep()
    {
        $strSQL = "";
        $strSQL .= " UPDATE M_DATARECEP";
        $strSQL .= " SET    BEF_GET_DT = TO_DATE('" . $this->strgetdate . "','YYYY-MM-DD HH24:MI:SS')";
        $strSQL .= " WHERE  TABLE_ID = '5'";
        return $strSQL;
    }

    public function subLogOut($strDataNM)
    {
        $this->objLog['strDataNM'] = $strDataNM;
        $this->ClsFncLog->fncDownLoadLog($this->strDownLoadPath, $this->objLog, 2);
    }

    public function fncR4Copy(&$strMessage, $strKobetu = "", $strKobetu2 = "")
    {
        // $lngCnt = "";
        //処理件数
        // $strGetDATE = "";
        //前回取得日付
        // $dtlGetDate = "";

        try {
            //objLog.strDataNM = "R4から（TMrh）へ"
            //ダウンロード処理を実行
            if ($this->fncAutoSQLDownLoad($this->strGroupID, $strMessage, $strKobetu, $strKobetu2) == FALSE) {
                //2006/04/07 UPDATE strkobetu2追加
                return FALSE;
            }
            return TRUE;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }
    }

    public function fncAutoSQLDownLoad($strGroupID, &$strMessage, $strKobetu = "", $strkobetu2 = "")
    {
        $objTblNmDs = "";
        //DL対象ﾃｰﾌﾞﾙ名を格納
        $intRowCnt = "";
        //INSERT文用 行ｶｳﾝﾄ
        // $intDelRowCnt = "";
        //DELETE文用 行ｶｳﾝﾄ
        // $intValueCnt = "";
        $lngResultCnt = "";
        $strBefdate = array(
            "",
            "",
            "",
            ""
        );

        try {
            //対象のﾃｰﾌﾞﾙ名をデータセットに格納
            $result = $this->ClsDownLoad->select($this->ClsComDLSql->fncTableNameGet($strGroupID));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objTblNmDs = $result['data'];
            for ($intRowCnt = 0; $intRowCnt <= count((array) $objTblNmDs) - 1; $intRowCnt++) {
                //構造体にﾛｸﾞをはくためのデータを格納
                $this->objLog['strDataNM'] = "BTH" . $objTblNmDs[$intRowCnt]["TABLE_NM"];
                //ダウンロード処理を実行
                //2006/04/07 UPDATE 引数にstrkobetu2追加
                $lngResultCnt = $this->fncAutoDLSqlAction($this->ClsComFnc->FncNv($objTblNmDs[$intRowCnt]["TABLE_NM"]), $strGroupID, $this->ClsComFnc->FncNz($objTblNmDs[$intRowCnt]["TABLE_ID"]), $strBefdate, $strMessage, $strKobetu, $strkobetu2, $this->ClsComFnc->FncNv($objTblNmDs[$intRowCnt]["KEY"]));

                if ($lngResultCnt < 0) {
                    $this->objLog['strState'] = "NG";
                    return FALSE;
                }
                $this->objLog['lngCount'] = $lngResultCnt;
                $this->ClsFncLog->fncDownLoadLog($this->strDownLoadPath, $this->objLog, $strMessage);
            }
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }
    }

    //2006/04/07 UPDATE 引数にstrkobetu2追加
    public function fncAutoDLSqlAction($strTableNM, $strGroupID, $intTableID, &$strBefdate, &$strMessage, $strKobetu = "", $strkobetu2 = "", $strKey = "")
    {
        $strCreateSQL = "";
        $strDeleteSQL = "";
        $strSelectSQL = "";
        $strCreateValue = "";
        $strTotalSQL = "";
        $strResult = "";
        // $intResultCnt = "";
        $intValueCnt = "";
        $intTypeCnt = "";
        $objValueDs = "";
        //VALUE句以降の列名を格納
        $objTypeDs = "";
        //対象ﾃｰﾌﾞﾙの列のﾀｲﾌﾟを格納

        //Dim intTableID As Integer

        $result = "";

        try {
            $strDeleteSQL = " DELETE FROM BTH" . $strTableNM;
            //削除
            $result = $this->ClsDownLoad->delete($strDeleteSQL);
            if (!$result['result']) {
                $strMessage = $result['data'];
                return -1;
            }

            $strSelectSQL .= " SELECT " . "\r\n";
            //SQL自動生成(列名を列挙)
            $strSelectSQL .= $this->ClsComDLSql->fncAutoCreateSQL("BTH" . $strTableNM);
            //strSelectSQL.Append(" FROM BTH" & strTableNM & vbCrLf)
            switch ($strGroupID) {
                case "G01":
                case "G04":
                    //日次ダウンロード
                    $strSelectSQL .= " FROM BTH" . $strTableNM . "\r\n";

                    //Select Case strTableNM
                    //    Case "28T13", "28T14"   '登録予定
                    //        intTableID = "2"
                    //    Case "29F01"            '会計
                    //        intTableID = "3"
                    //    Case "27A04"            '新車納品書
                    //        intTableID = "4"
                    //    Case Else               '注文書系
                    //        intTableID = "1"
                    //End Select
                    //2006/07/14 UPDATE Start    '行№がマッチしないため、どれが削除されたか分からないので注文書番号単位でＤＥＬ/ＩＮＳ
                    switch ($strTableNM) {
                        case '41E12':
                            $strSelectSQL .= " A WHERE EXISTS (SELECT CMN_NO FROM BTH41E10 B" . "\r\n";
                            break;
                    }
                    //2006/07/14 UPDATE End

                    if ($this->ClsComFnc->FncNv($strBefdate[$intTableID - 1]) == "") {
                        $strBefdate[$intTableID - 1] = $this->fncGetBEFGETDT($intTableID);
                    }
                    switch ($strTableNM) {
                        case "41E11":
                            //nothing
                            break;
                        case "27A02":
                            //2010/10/20 INSERT
                            //nothing
                            break;
                        default:
                            $strSelectSQL .= " WHERE TO_CHAR(" . $strKey . ",'YYYY/MM/DD HH24:MI:SS') > " . $this->ClsComFnc->FncSqlNv($strBefdate[$intTableID - 1], "NULL");
                            break;
                    }

                    //2006/07/14 UPDATE Start
                    switch ($strTableNM) {
                        case "41E12":
                            $strSelectSQL .= " AND A.CMN_NO = B.CMN_NO)" . "\r\n";
                            break;
                    }

                    //2006/07/14 UPDATE End
                    break;

                case "G02":
                    //個別ダウンロード

                    switch ($strTableNM) {
                        case "41C01":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";

                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         BTH41C01.DLRCSRNO = E10.KYK_CUS_NO" . "\r\n";
                            $strSelectSQL .= " WHERE E10.CMN_NO = '" . $strKobetu . "') a";
                            $strSelectSQL .= " UNION ";
                            $strSelectSQL .= " SELECT " . "\r\n";
                            $strSelectSQL .= $this->ClsComDLSql->fncAutoCreateSQL("BTH" . $strTableNM);
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         BTH41C01.DLRCSRNO = E10.SIY_CUS_NO" . "\r\n";
                            $strSelectSQL .= " WHERE E10.CMN_NO = '" . $strKobetu . "') b";
                            break;
                        case "27A01":
                        case "27A02":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10";
                            $strSelectSQL .= " ON         E10.JTU_NO = " . "BTH" . $strTableNM . ".JUCHU_NO";
                            $strSelectSQL .= " WHERE E10.CMN_NO = '" . $strKobetu . "') a";
                            break;
                        case "41B02":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         E10.CKO_CAR_SER_NO = BTH41B02.SEIRI_NO";
                            $strSelectSQL .= " AND        E10.CKO_CAR_SER_SEQ = BTH41B02.SEIRI_SEQ";
                            $strSelectSQL .= " WHERE E10.CMN_NO = '" . $strKobetu . "') a";
                            break;
                        case "41E12":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         E10.CMN_NO = BTH41E12.CMN_NO" . "\r\n";
                            $strSelectSQL .= " WHERE E10.CMN_NO = '" . $strKobetu . "') a";
                            break;
                        case "27AM1":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         E10.MOD_CD = BTH27AM1.BASEH_CD" . "\r\n";
                            $strSelectSQL .= " WHERE E10.CMN_NO = '" . $strKobetu . "') a";
                            break;
                        case "29MA4":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         E10.HNB_TAN_EMP_NO = BTH29MA4.SYAIN_NO" . "\r\n";
                            $strSelectSQL .= " AND        BTH29MA4.HANSH_CD = '3634'" . "\r\n";
                            $strSelectSQL .= " WHERE      E10.CMN_NO = '" . $strKobetu . "') a";
                            break;
                        case "28M71":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E11 E11" . "\r\n";
                            $strSelectSQL .= " ON         E11.BRD_CD = BTH28M71.MEIGARA_CODE" . "\r\n";
                            $strSelectSQL .= " WHERE      E11.CMN_NO = '" . $strKobetu . "') a";
                            break;
                        case "27M01":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         E10.KYOTN_CD = BTH27M01.KYOTN_CD" . "\r\n";
                            $strSelectSQL .= " AND        BTH27M01.HANSH_CD = '3634'" . "\r\n";
                            $strSelectSQL .= " WHERE      E10.CMN_NO = '" . $strKobetu . "') a";
                            //2007/05/07 INS Start
                            break;
                        case "27U01":
                        case "27U02":
                            $strSelectSQL .= " FROM (SELECT BTH" . $strTableNM . ".* FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH41E10 E10" . "\r\n";
                            $strSelectSQL .= " ON         E10.CMN_NO = BTH" . $strTableNM . ".CHUMN_NO" . "\r\n";
                            $strSelectSQL .= " WHERE      E10.CMN_NO = '" . $strKobetu . "') a";
                            //2007/05/07 INS End
                            break;
                        default:
                            $strSelectSQL .= " FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " WHERE CMN_NO = '" . $strKobetu . "'";
                            break;
                    }
                    break;
                case "G03":
                    //登録予定ダウンロード
                    switch ($strTableNM) {
                        case "28T13":
                            $strSelectSQL .= " FROM BTH" . $strTableNM . "\r\n";
                            $strSelectSQL .= " WHERE TOU_Y_DT = '" . $strKobetu . "'";
                            break;
                        case "28T14":
                            $strSelectSQL .= " FROM " . "\r\n";
                            $strSelectSQL .= " (SELECT BTH28T14.* FROM BTH28T14 " . "\r\n";
                            $strSelectSQL .= " INNER JOIN BTH28T13 T13" . "\r\n";
                            $strSelectSQL .= " ON         T13.CHUMN_NO = BTH28T14.CHUMN_NO";
                            $strSelectSQL .= " WHERE T13.TOU_Y_DT = '" . $strKobetu . "') a";
                            break;
                    }
                    break;
                //2006/04/07 ADD Start
                case "G05":
                    //経理ダウンロード(計上日指定)
                    $strSelectSQL .= " FROM BTH" . $strTableNM . "\r\n";

                    if ($this->ClsComFnc->FncNv($strBefdate[$intTableID - 1]) == "") {
                        $strBefdate[$intTableID - 1] = $this->fncGetBEFGETDT($intTableID);
                    }

                    $strSelectSQL .= " WHERE KEIJO_DT >= '" . $strKobetu . "' AND KEIJO_DT <= '" . $strkobetu2 . "'" . "\r\n";
                    break;
            }
            //Value句で設定する項目をﾃﾞｰﾀｾｯﾄに格納
            $result = $this->ClsDownLoad->select($strSelectSQL);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objValueDs = $result['data'];
            if (count((array) $objValueDs) == 0) {
                return 0;
            }

            //INSERT文作成開始
            $strCreateSQL .= "INSERT INTO BTH" . $strTableNM . " (";
            //列名を自動生成
            $strResult = $this->ClsComDLSql->fncAutoCreateSQL("BTH" . $strTableNM);
            if ($strResult == "") {
                return -1;
            } else {
                $strCreateSQL .= $strResult;
                $strCreateSQL .= ")" . "\r\n";
                $strCreateSQL .= " VALUES( " . "\r\n";
            }
            //Value句の値にデータ型によって、シングルコーテーションをつける
            $result = $this->ClsDownLoad->select($this->ClsComDLSql->fncTableNameGet("BTH" . $strTableNM));
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objTypeDs = $this->ClsComFnc->FncNv($result['data']);

            for ($intValueCnt = 0; $intValueCnt <= count((array) $objValueDs) - 1; $intValueCnt++) {
                $strCreateValue .= $this->ClsComDLSql->fncType($this->ClsComFnc->FncNv($objTypeDs[0]["DATA_TYPE"]), $this->ClsComFnc->FncNv($objValueDs[$intValueCnt][0])) . "\r\n";
                for ($intTypeCnt = 0; $intTypeCnt <= count($objTypeDs) - 1; $intTypeCnt++) {
                    $strCreateValue .= " ,";
                    $strCreateValue .= $this->ClsComDLSql->fncType($this->ClsComFnc->FncNv($objTypeDs[$intTypeCnt]["DATA_TYPE"]), $objValueDs[$intValueCnt][$intTypeCnt]) . "\r\n";
                }
                $strTotalSQL .= $strCreateSQL . $strCreateValue . "\r\n";
                $strTotalSQL .= ")";
                $result = $this->ClsDownLoad->insert($strTotalSQL);
                if (!$result['result']) {
                    $strMessage = $result['data'];
                    return -1;
                }
                $strCreateValue = "";
                $strTotalSQL = "";
                switch ($strTableNM) {
                    case '41E10':
                        if ($this->ClsComFnc->FncNz($objValueDs[$intValueCnt]["TKB_KSH_SUM_GKU_ZEINK"]) > 9999999) {
                            if ($this->objLog['strErrFlg'] == "0") {
                                //ｴﾗｰﾛｸﾞ出力開始
                                $this->ClsFncLog->fncDownLoadLog($this->strErrLogPath, $this->objLog, 0);
                                $this->objLog['strErrFlg'] = "1";
                            }
                            $this->objLog['strErrNO'] = $objValueDs[$intValueCnt]["CMN_NO"];
                            $this->ClsFncLog->fncDownLoadLog($this->strErrLogPath, $this->objLog, 4);
                        }
                        break;
                }
            }

            //strCreateSQL.Append(clsComDLSql_Lib.fncAutoValueSQL(objValueDs, objTypeDs))

            //INSERT文作成終了

            return $intValueCnt;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return -1;
        }

    }

    //**********************************************************************
    //処 理 名：データグリッドの再表示
    //関 数 名：fncGetBEFGETDT
    //引    数：objDr (I) オブジェクト
    //戻 り 値：無し
    //処理説明：データグリッドを再表示する
    //**********************************************************************
    public function fncGetBEFGETDT($strTableId)
    {
        $objDr = "";
        //ﾃﾞｰﾀﾘｰﾀﾞ(注文データ）
        $result = "";
        try {
            $result = $this->ClsDownLoad->fncGetBEFGETDT($strTableId);
            if (!$result['result']) {
                throw new \Exception($result['data']);
            }
            $objDr = $result['data'];
            //該当データなし
            if (count((array) $objDr) <= 0) {
                //clsComFnc.FncMsgBox("I0001")      '該当するデータは存在しません。
                return "";
            }
            return $objDr['BEF_GET_DT'];
        } catch (\Exception $e) {
            // $strMsg = "R4COPY " . "\r\n" . "fncGetBEFGETDT " . "\r\n" . $e->getMessage();
            //MessageBox.Show(strMsg, _
            //    clsComFnc.GSYSTEM_NAME, _
            //    MessageBoxButtons.OK, _
            //    MessageBoxIcon.Error, _
            //    MessageBoxDefaultButton.Button1)
            //Console.WriteLine(strMsg)
        }
    }

    //**********************************************************************
    //処 理 名：データ受信テーブルの更新
    //関 数 名：fncUpdateSetGetDate
    //引    数：無し
    //戻 り 値：True:正常終了　False:異常終了
    //処理説明：データ受信テーブルの更新(ID='5'で更新)
    //**********************************************************************
    public function fncUpdateSetGetDate(&$strMessage)
    {
        $result = "";
        try {
            $this->objLog['strState'] = " ";
            $result = $this->ClsDownLoad->fncUpdateDataRecep($this->strgetdate);
            $lngCnt = $result['number_of_rows'];
            if (!$result['result']) {
                $strMessage = $result['data'];
                $this->objLog['strState'] = "NG";
                //ログ出力
                $this->ClsFncLog->fncDownLoadLog($this->strDownLoadPath, $this->objLog, $lngCnt);
                return FALSE;
            }
            $this->objLog['lngCount'] = $lngCnt;
            $this->ClsFncLog->fncDownLoadLog($this->strDownLoadPath, $this->objLog, $strMessage);
            return TRUE;
        } catch (\Exception $e) {
            $strMessage = $e->getMessage();
            return FALSE;
        }
    }

    public function subEndLog($strMessage)
    {
        $this->strEndDate = $this->ClsFileObserver->Fnc_GetSysDate($strMessage, "Y/m/d H:i:s");
        $this->objLog['strEndDate'] = $this->strEndDate;
        $this->ClsFncLog->fncDownLoadLog($this->strDownLoadPath, $this->objLog, $strMessage);
        if ($this->objLog['strErrFlg'] == "1") {
            //ｴﾗｰﾛｸﾞ出力完了
            $this->ClsFncLog->fncDownLoadLog($this->strErrLogPath, $this->objLog, $strMessage);
        }
    }

}
