<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151008           20150929以降の修正差異点                                         li　　　　　
 * 20151102           #2245						   BUG                              li  　
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\Component;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsKeiriDataMake
// * 処理説明：共通関数
//*************************************

class ClsKeiriDataMake extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $number_of_rows = "";
    // 20131004 kamei add end

    //**********************************************************************
    //処 理 名：振替データ削除
    //関 数 名：fncFurikaeDeleteSQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：引渡された計上日範囲内の振替ﾃﾞｰﾀを削除する
    //**********************************************************************
    function fncFurikaeDeleteSQL($strKEIJYOBI, $strHaseiKB, $strActmode)
    {
        $strSQL = "";

        $strSQL .= "DELETE " . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "  FROM HFURIKAE_S" . "\r\n";
        } else {
            $strSQL .= "  FROM HFURIKAE" . "\r\n";
        }
        $strSQL .= " WHERE SUBSTR(KEIJO_DT,1,6) = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND HASEI_MOTO_KB = '@HaseiKB'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);
        $strSQL = str_replace("@HaseiKB", $strHaseiKB, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新中売上台数振替ﾃﾞｰﾀ作成(SQL)
    //関 数 名：fncFURIDAISUInsertSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL文
    //処理説明：新中売上ﾃﾞｰﾀより売上台数振替ﾃﾞｰﾀを作成する(SQL)
    //**********************************************************************
    //2006/12/11 UPD 更新ユーザ、更新ﾏｼﾝ、更新プログラムを引数に追加
    function fncFURIDAISUInsertSQL($strSyoriDT, $strUpdPro, $strActmode)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        if ($strActmode == "S") {
            $strSQL .= "INSERT INTO HFURIKAE_S" . "\r\n";
        } else {
            $strSQL .= "INSERT INTO HFURIKAE" . "\r\n";
        }

        $strSQL .= "           (" . "\r\n";
        $strSQL .= "            KEIJO_DT" . "\r\n";
        $strSQL .= "           ,ID" . "\r\n";
        $strSQL .= "           ,DENPY_NO" . "\r\n";
        $strSQL .= "           ,GYO_NO" . "\r\n";
        $strSQL .= "           ,TAISK_KB" . "\r\n";
        $strSQL .= "           ,BUSYO_CD" . "\r\n";
        $strSQL .= "           ,KAMOK_CD" . "\r\n";
        $strSQL .= "           ,HIMOK_CD" . "\r\n";
        $strSQL .= "           ,KEIJO_GK" . "\r\n";
        $strSQL .= "           ,AITE_BUSYO_CD" . "\r\n";
        $strSQL .= "           ,AITE_KAMOK_CD" . "\r\n";
        $strSQL .= "           ,AITE_HIMOK_CD" . "\r\n";
        $strSQL .= "           ,HASEI_MOTO_KB" . "\r\n";
        $strSQL .= "           ,CEL_DATE" . "\r\n";
        $strSQL .= "           ,UPD_DATE" . "\r\n";
        $strSQL .= "           ,CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "            ) " . "\r\n";
        $strSQL .= "--売上台数()" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,1" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,DECODE(A.NAU_KB,'1',A.URK_BUSYO_CD,A.URI_BUSYO_CD)" . "\r\n";
        $strSQL .= "      ,DECODE(A.NAU_KB,'1','00807','00811')" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.NAU_KB = '1' THEN B.HMK_CD" . "\r\n";
        //2010/10/26 UPD Start
        // '''strSQL.Append("            ELSE CASE WHEN A.CKG_KB = '1' THEN '11' " ."\r\n";
        // '''strSQL.Append("            ELSE DECODE(A.CKO_HNB_KB,'5','13','9','13','3','14','6','14','12') END END " ."\r\n";
        $strSQL .= "            ELSE DECODE(A.CKO_HNB_KB,'1','11','2','11','5','13','9','13','3','14','6','14','12') END " . "\r\n";
        //2010/10/26 UPD Start
        $strSQL .= "      ,A.URI_DAISU" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO " . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A " . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A " . "\r\n";
        }
        $strSQL .= "      ,HUCHMKMST B " . "\r\n";

        $strSQL .= " WHERE A.KKR_CD = B.UCOYA_CD(+) " . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "--売上台数(条変前)" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,2" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,DECODE(A.NAU_KB,'1',A.URK_BUSYO_CD,A.URI_BUSYO_CD)" . "\r\n";
        $strSQL .= "      ,DECODE(A.NAU_KB,'1','00807','00811')" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.NAU_KB = '1' THEN B.HMK_CD" . "\r\n";
        //2010/10/26 UPD Start
        // '''strSQL.Append("            ELSE CASE WHEN A.CKG_KB = '1' THEN '11' " & vbCrLf)
        // '''strSQL.Append("            ELSE DECODE(A.CKO_HNB_KB,'5','13','9','13','3','14','6','14','12') END END " & vbCrLf)
        $strSQL .= "            ELSE DECODE(A.CKO_HNB_KB,'1','11','2','11','5','13','9','13','3','14','6','14','12') END " . "\r\n";
        //2010/10/26 UPD End
        $strSQL .= "      ,A.URI_DAISU*-1" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
        }
        $strSQL .= "      ,HUCHMKMST B " . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        $strSQL .= " WHERE A.KKR_CD = B.UCOYA_CD(+)" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "--登録台数(新車)" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,3" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'00830'" . "\r\n";
        $strSQL .= "      ,B.HMK_CD" . "\r\n";
        $strSQL .= "      ,A.TOU_DAISU" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= "      ,HUCHMKMST B " . "\r\n";
        $strSQL .= " WHERE A.KKR_CD = B.UCOYA_CD " . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.TOU_DAISU <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "--登録台数(新車条変前)" . "\r\n";
        $strSQL .= "UNION  ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,4" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'00830'" . "\r\n";
        $strSQL .= "      ,B.HMK_CD" . "\r\n";
        $strSQL .= "      ,A.TOU_DAISU*-1" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO " . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
        }
        $strSQL .= "      ,HUCHMKMST B " . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        $strSQL .= " WHERE A.KKR_CD = B.UCOYA_CD" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND A.TOU_DAISU <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "--業売台数(新車)" . "\r\n";
        $strSQL .= "          UNION ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,5" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'00809'" . "\r\n";
        $strSQL .= "      ,'  '" . "\r\n";
        $strSQL .= "      ,1" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A " . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A " . "\r\n";
        }
        $strSQL .= "      ,HUCHMKMST B " . "\r\n";
        $strSQL .= " WHERE A.KKR_CD = B.UCOYA_CD " . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND TRK_KB IN ('1','3')" . "\r\n";
        $strSQL .= "   AND NVL(URI_GYOSYA,' ')<>' '" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "--業売台数(新車条変前)" . "\r\n";
        $strSQL .= "UNION  ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO " . "\r\n";
        $strSQL .= "      ,6" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,DECODE(A.NAU_KB,'1',A.URK_BUSYO_CD,A.URI_BUSYO_CD)" . "\r\n";
        $strSQL .= "      ,'00809'" . "\r\n";
        $strSQL .= "      ,'  '" . "\r\n";
        $strSQL .= "      ,-1" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO " . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
        }
        $strSQL .= "      ,HUCHMKMST B " . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        $strSQL .= " WHERE A.KKR_CD = B.UCOYA_CD" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND A.TRK_KB IN ('1','3')" . "\r\n";
        $strSQL .= "   AND NVL(A.URI_GYOSYA,' ')<>' '" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "" . "\r\n";
        $strSQL .= "--他チャネル(新車)" . "\r\n";
        $strSQL .= "          UNION ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,7" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'00831'" . "\r\n";
        $strSQL .= "      ,'  '" . "\r\n";
        $strSQL .= "      ,1" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A " . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A " . "\r\n";
        }
        $strSQL .= " WHERE A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.TRK_KB IN ('1','3')" . "\r\n";
        $strSQL .= "   AND A.URI_BUSYO_CD = '168'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--チャネル(新車条変前)" . "\r\n";
        $strSQL .= "UNION  ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO " . "\r\n";
        $strSQL .= "      ,8" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'00831'" . "\r\n";
        $strSQL .= "      ,'  '" . "\r\n";
        $strSQL .= "      ,-1" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO " . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND A.TRK_KB IN ('1','3')" . "\r\n";
        $strSQL .= "   AND A.URI_BUSYO_CD = '168'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "" . "\r\n";
        $strSQL .= "--下取台数(新車)" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,9" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'00810'" . "\r\n";
        $strSQL .= "      ,'  '" . "\r\n";
        $strSQL .= "      ,V.CNT" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
            $strSQL .= "      ,(SELECT CMN_NO,COUNT(*) CNT FROM HSCSIT_S_VW WHERE KEIJYO_YM = '@KEIJYOBI' GROUP BY CMN_NO) V" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
            $strSQL .= "      ,(SELECT CMN_NO,COUNT(*) CNT FROM HSCSIT_VW WHERE KEIJYO_YM = '@KEIJYOBI' GROUP BY CMN_NO) V" . "\r\n";
        }
        $strSQL .= " WHERE V.CMN_NO = A.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--下取台数(条変前新車)" . "\r\n";
        $strSQL .= "UNION  ALL" . "\r\n";
        $strSQL .= "SELECT TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'01'" . "\r\n";
        $strSQL .= "      ,A.CMN_NO " . "\r\n";
        $strSQL .= "      ,10" . "\r\n";
        $strSQL .= "      ,'1'" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'00810'" . "\r\n";
        $strSQL .= "      ,'  '" . "\r\n";
        $strSQL .= "      ,V.CNT*-1" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.URI_TANNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,(SELECT CMN_NO,JKN_HKO_RIRNO,COUNT(*) CNT FROM HJYOUHENSIT_S WHERE KEIJYO_YM < '@KEIJYOBI' GROUP BY CMN_NO,JKN_HKO_RIRNO) V" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,(SELECT CMN_NO,JKN_HKO_RIRNO,COUNT(*) CNT FROM HJYOUHENSIT WHERE KEIJYO_YM < '@KEIJYOBI' GROUP BY CMN_NO,JKN_HKO_RIRNO) V" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";

            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND V.CMN_NO = A.CMN_NO" . "\r\n";
        $strSQL .= "   AND V.JKN_HKO_RIRNO = A.JKN_HKO_RIRNO" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB='1'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strSyoriDT, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：会計データ削除
    //関 数 名：fncKaikeiDelete
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：引渡された計上日範囲内の会計ﾃﾞｰﾀを削除する
    //**********************************************************************
    function fncKaikeiDeleteSQL($strDepend1, $strDepend2, $strHaseiKB, $strActmode = "K")
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "  FROM HKAIKEI_S" . "\r\n";
        } else {
            $strSQL .= "  FROM HKAIKEI" . "\r\n";
        }
        $strSQL .= " WHERE KEIJO_DT >= '@Depend1'" . "\r\n";
        $strSQL .= "   AND KEIJO_DT <= '@Depend2'" . "\r\n";
        $strSQL .= "   AND HASEI_MOTO_KB = '@HaseiKB'" . "\r\n";

        $strSQL = str_replace("@Depend1", $strDepend1, $strSQL);
        $strSQL = str_replace("@Depend2", $strDepend2, $strSQL);
        $strSQL = str_replace("@HaseiKB", $strHaseiKB, $strSQL);
        return $strSQL;
    }

    function fncGetSaibanSelectSQL($strID)
    {
        $strSQL = "";
        $strSQL .= "SELECT SEQNO+1 SEQNO" . "\r\n";
        $strSQL .= "  FROM HKSAIBAN" . "\r\n";
        $strSQL .= " WHERE ID = '" . $strID . "'" . "\r\n";
        return $strSQL;
    }

    function fncGetSaibanUpdateSQL($strID)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HKSAIBAN SET SEQNO=DECODE(SEQNO,99999,0,SEQNO+1)" . "\r\n";
        $strSQL .= " WHERE ID = '" . $strID . "'" . "\r\n";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：基準価格会計データ作成(SQL)
    //関 数 名：fncKijyunKaikeiInsertSQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：当月売上データタを抽出し、会計データを作成する(SQL)
    //**********************************************************************
    //2006/12/11 UPD Start 引数を追加
    function fncKijyunKaikeiInsertSQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        if ($strActmode == "S") {
            $strSQL .= " INSERT INTO HKAIKEI_S (" . "\r\n";
        } else {
            $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        }
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";

        //===========================================================================================
        $strSQL .= "--'当月売上分" . "\r\n";
        //===========================================================================================
        $strSQL .= "--'新車部署別車両原価" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,1" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'42111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //2018/02/26 修正 START
        ////2013/12/20 修正 START
        ////strSQL.Append("      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' THEN '291'" & vbCrLf)
        ////strSQL.Append("            ELSE '174' END" & vbCrLf)
        //$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        //$strSQL .= "            THEN " . "\r\n";
        //$strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        //$strSQL .= "                      THEN '271'" . "\r\n";
        //$strSQL .= "                      ELSE '291' END" . "\r\n";
        //$strSQL .= "            ELSE '174' END" . "\r\n";
        ////2013/12/20 修正 END
        $strSQL .= "      ,CASE " . "\r\n";
        //			$strSQL .= "           WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        $strSQL .= "           WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' OR A.KKR_CD='X90' OR A.KKR_CD='X9C' OR A.KKR_CD='V60' OR A.KKR_CD='V9C' OR A.KKR_CD='V6C' OR A.KKR_CD='XC9' OR A.KKR_CD='XC6' OR A.KKR_CD='XC4' OR A.KKR_CD='X60' OR A.KKR_CD='C40' OR A.KKR_CD='S90' OR A.KKR_CD='S60'   " . "\r\n";
        $strSQL .= "           THEN " . "\r\n";
        $strSQL .= "               CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        $strSQL .= "                    THEN '271'" . "\r\n";
        $strSQL .= "                    ELSE '291' END" . "\r\n";
        $strSQL .= "           WHEN A.KKR_CD='LOT' " . "\r\n";
        $strSQL .= "           THEN '241'" . "\r\n";
        $strSQL .= "           ELSE '174' END" . "\r\n";
        //2018/02/26 修正 END

        $strSQL .= "      ,'42111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.SRY_BUY_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.SRY_BUY_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KYK_HNS <> '17349'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車架装原価" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,2" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //2018/02/26 修正 START
        ////2013/12/20 修正 START
        ////strSQL.Append("      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' THEN '297'" & vbCrLf)
        ////strSQL.Append("          ELSE '174' END R_BUSYO" & vbCrLf)
        //$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        //$strSQL .= "            THEN " . "\r\n";
        ////---20151009 yin UPD S.
        ////$strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '277'" . "\r\n";
        //$strSQL .= "                 CASE WHEN SUBSTR(A.URK_BUSYO_CD,1,2) = '27'" . "\r\n";
        ////---20151009 yin UPD E.
        //$strSQL .= "                      THEN '277'" . "\r\n";
        //$strSQL .= "                      ELSE '297' END" . "\r\n";
        ////---20151009 yin UPD S.
        ////$strSQL .= "            ELSE '174' END" . "\r\n";
        //$strSQL .= "            ELSE '174' END R_BUSYO" . "\r\n";
        ////---20151009 yin UPD E.
        ////2013/12/20 修正 END
//			$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        $strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' OR A.KKR_CD='X90' OR A.KKR_CD='X9C' OR A.KKR_CD='V60' OR A.KKR_CD='V9C' OR A.KKR_CD='V6C' OR A.KKR_CD='XC9'  OR A.KKR_CD='XC6'  OR A.KKR_CD='XC4'  OR A.KKR_CD='X60'  OR A.KKR_CD='C40'  OR A.KKR_CD='S90'  OR A.KKR_CD='S60'   " . "\r\n";

        $strSQL .= "            THEN " . "\r\n";
        $strSQL .= "                 CASE WHEN SUBSTR(A.URK_BUSYO_CD,1,2) = '27'" . "\r\n";
        $strSQL .= "                      THEN '277'" . "\r\n";
        $strSQL .= "                      ELSE '297' END" . "\r\n";
        $strSQL .= "            WHEN A.KKR_CD='LOT'  " . "\r\n";
        $strSQL .= "            THEN '241' " . "\r\n";
        $strSQL .= "            ELSE '174' END R_BUSYO" . "\r\n";
        //2018/02/26 修正 END

        $strSQL .= "      ,'42112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.FHZ_PCS,0)+NVL(A.TKB_KSH_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.FHZ_PCS,0)+NVL(A.TKB_KSH_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車車両収入手数料" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,3" . "\r\n";

        //2018/02/26 修正 START
        ////2013/12/20 修正 START
        ////strSQL.Append("      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' THEN '291'" & vbCrLf)
        ////strSQL.Append("          ELSE '161' END L_BUSYO" & vbCrLf)
        //$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        //$strSQL .= "            THEN " . "\r\n";
        //$strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        //$strSQL .= "                      THEN '271'" . "\r\n";
        //$strSQL .= "                      ELSE '291' END" . "\r\n";
        ////---20151009 yin UPD S.
        ////$strSQL .= "            ELSE '161' END" . "\r\n";
        //$strSQL .= "            ELSE '161' END L_BUSYO" . "\r\n";
        ////---20151009 yin UPD E.
        ////2013/12/20 修正 END
//			$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        $strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' OR A.KKR_CD='X90' OR A.KKR_CD='X9C' OR A.KKR_CD='V60' OR A.KKR_CD='V9C' OR A.KKR_CD='V6C' OR A.KKR_CD='XC9'  OR A.KKR_CD='XC6'  OR A.KKR_CD='XC4'  OR A.KKR_CD='X60'  OR A.KKR_CD='C40'  OR A.KKR_CD='S90'  OR A.KKR_CD='S60'   " . "\r\n";

        $strSQL .= "                THEN " . "\r\n";
        $strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        $strSQL .= "                      THEN '271'" . "\r\n";
        $strSQL .= "                      ELSE '291' END" . "\r\n";
        $strSQL .= "               WHEN A.KKR_CD='LOT' " . "\r\n";
        $strSQL .= "               THEN '241' " . "\r\n";
        $strSQL .= "            ELSE '161' END L_BUSYO" . "\r\n";
        //2018/02/26 修正 END

        $strSQL .= "      ,'41921'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD R_BUSYO" . "\r\n";
        $strSQL .= "      ,'41921'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車月賦手数料データ" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,4" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'82111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,'019'" . "\r\n";
        $strSQL .= "      ,'82111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.KAP_TES_KJN,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.KAP_TES_KJN,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車登録諸費用基準　社内原価　業務課" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,5" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE '174' END " . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //strSQL.Append("      ,KJ_V.HAIBUN_GK1" & vbCrLf)
        //2006/10/16 UPDATE Start
        //strSQL.Append("      ,DECODE(KJ_V.HAIBUN_GK1,NULL,(NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0)),KJ_V.HAIBUN_GK1)" & vbCrLf)
        $strSQL .= "      ,DECODE(KJ_V.HAIBUN_GK1,NULL,NVL(A.TOU_SYH_KJN,0),KJ_V.HAIBUN_GK1)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HSCURI SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        //strSQL.Append(" WHERE KJ_V.HAIBUN_GK1 <> 0" & vbCrLf)
        //strSQL.Append("   AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        //2006/10/16 UPDATE Start
        $strSQL .= " WHERE KJ_V.KJN_GENKA(+) = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //strSQL.Append(" WHERE KJ_V.KJN_GENKA(+) = (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        //2006/10/16 UPDATE End
        $strSQL .= "   AND KJ_V.NAU_KB(+) =  '1'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB =  '1'" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("   AND (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))<>0" & vbCrLf)
        $strSQL .= "   AND NVL(A.TOU_SYH_KJN,0) <> 0" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車登録諸費用基準　社内原価　使用済" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,6" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE '211' END " . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK2" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HSCURI SC" & vbCrLf)
        //2010/01/28 UPD end

        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= " WHERE KJ_V.HAIBUN_GK2 <> 0" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("   AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        $strSQL .= "   AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "   AND KJ_V.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車登録諸費用基準　社内原価　中古本部" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,7" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,'211'" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK3" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HSCURI SC" & vbCrLf)
        //2010/01/28 UPD end
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= " WHERE KJ_V.HAIBUN_GK3 <> 0" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("   AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        $strSQL .= "   AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "   AND KJ_V.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDENPNO, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：基準価格会計データ作成(SQL)
    //関 数 名：fncKijyunKaikeiInsert2SQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：当月売上データタを抽出し、会計データを作成する(SQL)
    //**********************************************************************
    function fncKijyunKaikeiInsert2SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        if ($strActmode == "S") {
            $strSQL .= " INSERT INTO HKAIKEI_S (" . "\r\n";
        } else {
            $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        }
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";

        $strSQL .= "--'中古再生見積" . "\r\n";
        //        $strSQL .= "UNION ALL" & vbCrLf)
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,1" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42221'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //2010/10/26 UPD Start
        //''strSQL.Append("      ,CASE WHEN A.CKG_KB='1'and CKO_MEG_KB='1' THEN SUBSTR(A.URI_BUSYO_CD,1,2) ||'7' " & vbCrLf)
        $strSQL .= "      ,CASE WHEN A.CKO_HNB_KB IN ('1','2') AND CKO_MEG_KB = '1' THEN SUBSTR(A.URI_BUSYO_CD,1,2) ||'7' " . "\r\n";
        //2010/10/26 UPD Start
        $strSQL .= "          ELSE '211' END R_BUSYO" . "\r\n";
        $strSQL .= "      ,'42221'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.CKO_SAI_MITUMORI,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(CKO_SAI_MITUMORI,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        //strSQL.Append("--'中古架装原価" & vbCrLf)      2010/10/20 UPD
        $strSQL .= "--'中古架装原価（特別架装原価）" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,2" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //2010/10/20 UPD Start
        //strSQL.Append("      ,(CASE WHEN A.URI_BUSYO_CD IN ('224','231')  THEN '212' " & vbCrLf)
        //strSQL.Append("            ELSE CASE WHEN A.URI_TANNO = '67112' THEN '427' " & vbCrLf)
        //strSQL.Append("            ELSE CASE WHEN A.URI_TANNO = '64326' THEN '417' " & vbCrLf)
        //strSQL.Append("            ELSE SUBSTR(A.URI_BUSYO_CD,1,2)||'7' END END END) R_BUSYO" & vbCrLf)
        //---20151008 li UPD S.
        $strSQL .= "      ,'212'" . "\r\n";
        // $strSQL .= "      ,(CASE WHEN A.URI_BUSYO_CD IN ('224','231')  THEN '212' " . "\r\n";
        // $strSQL .= "             ELSE CASE WHEN A.URI_TANNO = '67112' THEN '427' " . "\r\n";
        // $strSQL .= "             ELSE CASE WHEN A.URI_TANNO = '64326' THEN '417' " . "\r\n";
        // $strSQL .= "             ELSE SUBSTR(A.URI_BUSYO_CD,1,2)||'7' END END END) R_BUSYO" . "\r\n";
        //---20151008 li UPD E.
        //2010/10/20 UPD End
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.TKB_KSH_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.TKB_KSH_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        //2010/10/20 INS Start
        $strSQL .= "--'中古架装原価（付属品原価）" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        ///*--- 2011/01/13 Upd Start
        //中古架装原価（特別架装原価）とプライマリキーがダブル場合がある為、変更
        //strSQL.Append("      ,2" & vbCrLf)
        $strSQL .= "      ,22" . "\r\n";
        ///*--- 2011/01/13 Upd End
        $strSQL .= "      ,A.URI_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,(CASE WHEN A.URI_BUSYO_CD IN ('224','231','261')  THEN A.URI_BUSYO_CD " . "\r\n";
        $strSQL .= "            ELSE SUBSTR(A.URI_BUSYO_CD,1,2)||'7' END) R_BUSYO" . "\r\n";
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.FHZ_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.FHZ_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        //2010/10/20 INS End

        $strSQL .= "--'中古車両収入手数料" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,3" . "\r\n";
        $strSQL .= "      ,'211' L_BUSYO" . "\r\n";
        $strSQL .= "      ,'41922'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD R_BUSYO" . "\r\n";
        $strSQL .= "      ,'41922'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古月賦手数料データ" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,4" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,'019'" . "\r\n";
        $strSQL .= "      ,'82112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.KAP_TES_KJN,0)*0.6" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= " WHERE NVL(A.KAP_TES_KJN,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古登録諸費用基準　社内原価　業務課" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,5" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE DECODE(KJ_V.HAIBUN_GK1,NULL,'211','174') END " . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //strSQL.Append("      ,KJ_V.HAIBUN_GK1" & vbCrLf)
        //2006/10/16 UPDATE Start
        //strSQL.Append("      ,DECODE(KJ_V.HAIBUN_GK1,NULL,(NVL(A.TOU_SYH_KJN,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0)),KJ_V.HAIBUN_GK1)" & vbCrLf)
        $strSQL .= "      ,DECODE(KJ_V.HAIBUN_GK1,NULL,NVL(A.TOU_SYH_KJN,0),KJ_V.HAIBUN_GK1)" . "\r\n";
        //2006/10/16 UPDATE end
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HSCURI SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("WHERE KJ_V.KJN_GENKA(+) = (NVL(A.TOU_SYH_KJN,0)- NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0) )" & vbCrLf)
        $strSQL .= "WHERE KJ_V.KJN_GENKA(+) = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "  AND KJ_V.NAU_KB(+) =  '2'" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("  AND (NVL(A.TOU_SYH_KJN,0)  - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))<>0" & vbCrLf)
        $strSQL .= "  AND NVL(A.TOU_SYH_KJN,0) <> 0" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "  AND A.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "  AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古登録諸費用基準　社内原価　中古管理" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,6" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE '211' END " . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK2" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HSCURI SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= "WHERE KJ_V.HAIBUN_GK2 <> 0" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("  AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0) )" & vbCrLf)
        $strSQL .= "  AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "  AND KJ_V.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND A.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "  AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古登録諸費用基準　社内原価　中古本部" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,7" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,'211'" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK3" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HSCURI SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HSCURI SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= "WHERE KJ_V.HAIBUN_GK3 <> 0" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("  AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0)- NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        $strSQL .= "  AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "  AND KJ_V.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND A.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "  AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古本部負担金" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,8" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'84111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,'211'" . "\r\n";
        $strSQL .= "      ,'84111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //2010/10/26 UPD Start
        //''strSQL.Append("      ,CASE WHEN A.CKG_KB = '1' THEN 8000 ELSE 5000 END" & vbCrLf)
        ///*--- 2013/11/02 Upd Start
        //strSQL.Append("      ,CASE WHEN A.CKO_HNB_KB IN ('1','2') THEN 8000 ELSE 5000 END" & vbCrLf)
        //2010/10/26 UPD End
        //◆負担金変更　5,000→7,000　8,000→10,000
        $strSQL .= "      ,CASE WHEN A.CKO_HNB_KB IN ('1','2') THEN 10000 ELSE 7000 END" . "\r\n";
        ///*--- 2013/11/02 Upd End
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HSCURI_S_VW A" . "\r\n";
        } else {
            $strSQL .= "  FROM HSCURI_VW A" . "\r\n";
        }
        //2010/10/26 UPD Start
        //''strSQL.Append(" WHERE (A.CKG_KB = '1' OR (A.CKO_HNB_KB <> '5' AND A.CKO_HNB_KB <> '9') )" & vbCrLf)
        $strSQL .= " WHERE (A.CKO_HNB_KB <> '5' AND A.CKO_HNB_KB <> '9')" . "\r\n";
        //2010/10/26 UPD End
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.CEL_DATE IS NULL" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDENPNO, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：基準価格会計データ作成(SQL)
    //関 数 名：fncKijyunKaikeiInsert3SQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：当月売上データタを抽出し、会計データを作成する(SQL)
    //**********************************************************************
    function fncKijyunKaikeiInsert3SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];

        //===========================================================================================
        //strSQL.Append("--'条件変更分" & vbCrLf)
        //===========================================================================================
        $strSQL = "";

        if ($strActmode == "S") {
            $strSQL .= " INSERT INTO HKAIKEI_S (" . "\r\n";
        } else {
            $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        }
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";
        //strSQL.Append("UNION ALL" & vbCrLf)
        $strSQL .= "--'新車部署別車両原価" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,11" . "\r\n";
        //2018/02/26 修正 START
        ////2013/12/20 修正 START
        ////strSQL.Append("      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' THEN '291'" & vbCrLf)
        ////strSQL.Append("               ELSE '174' END" & vbCrLf)
        //$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        //$strSQL .= "            THEN " . "\r\n";
        //$strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        //$strSQL .= "                      THEN '271'" . "\r\n";
        //$strSQL .= "                      ELSE '291' END" . "\r\n";
        //$strSQL .= "            ELSE '174' END" . "\r\n";
        ////2013/12/20 修正 END
//			$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        $strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' OR A.KKR_CD='X90' OR A.KKR_CD='X9C' OR A.KKR_CD='V60' OR A.KKR_CD='V9C' OR A.KKR_CD='V6C' OR A.KKR_CD='XC9'  OR A.KKR_CD='XC6'  OR A.KKR_CD='XC4'  OR A.KKR_CD='X60'  OR A.KKR_CD='C40'  OR A.KKR_CD='S90'  OR A.KKR_CD='S60'   " . "\r\n";

        $strSQL .= "            THEN " . "\r\n";
        $strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        $strSQL .= "                      THEN '271'" . "\r\n";
        $strSQL .= "                      ELSE '291' END" . "\r\n";
        $strSQL .= "               WHEN A.KKR_CD='LOT' " . "\r\n";
        $strSQL .= "            THEN '241' " . "\r\n";
        $strSQL .= "            ELSE '174' END" . "\r\n";
        //2018/02/26 修正 END

        $strSQL .= "      ,'42111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'42111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.SRY_BUY_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.SRY_BUY_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.KYK_HNS <> '17349'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車架装原価" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,12" . "\r\n";
        //2018/02/26 修正 START

        ////2013/12/20 修正 START
        ////strSQL.Append("      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' THEN '297'" & vbCrLf)
        ////strSQL.Append("          ELSE '174' END R_BUSYO" & vbCrLf)
        //$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        //$strSQL .= "            THEN " . "\r\n";
        ////---20151009 yin UPD S.
        ////$strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '277'" . "\r\n";
        //$strSQL .= "                 CASE WHEN SUBSTR(A.URK_BUSYO_CD,1,2) = '27'" . "\r\n";
        ////---20151009 yin UPD E.
        //$strSQL .= "                      THEN '277'" . "\r\n";
        //$strSQL .= "                      ELSE '297' END" . "\r\n";
        ////---20151009 yin UPD S.
        ////$strSQL .= "            ELSE '174' END" . "\r\n";
        //$strSQL .= "            ELSE '174' END R_BUSYO" . "\r\n";
        ////---20151009 yin UPD E.
        ////2013/12/20 修正 END
//			$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        $strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' OR A.KKR_CD='X90' OR A.KKR_CD='X9C' OR A.KKR_CD='V60' OR A.KKR_CD='V9C' OR A.KKR_CD='V6C' OR A.KKR_CD='XC9'  OR A.KKR_CD='XC6'  OR A.KKR_CD='XC4'  OR A.KKR_CD='X60'  OR A.KKR_CD='C40'  OR A.KKR_CD='S90'  OR A.KKR_CD='S60'   " . "\r\n";

        $strSQL .= "            THEN " . "\r\n";
        $strSQL .= "                 CASE WHEN SUBSTR(A.URK_BUSYO_CD,1,2) = '27'" . "\r\n";
        $strSQL .= "                      THEN '277'" . "\r\n";
        $strSQL .= "                      ELSE '297' END" . "\r\n";
        $strSQL .= "               WHEN A.KKR_CD='LOT' " . "\r\n";
        $strSQL .= "               THEN '247' " . "\r\n";
        $strSQL .= "            ELSE '174' END R_BUSYO" . "\r\n";
        //2018/02/26 修正 END

        $strSQL .= "      ,'42112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.FHZ_PCS,0)+NVL(A.TKB_KSH_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.FHZ_PCS,0)+NVL(A.TKB_KSH_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車車両収入手数料" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,13" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD R_BUSYO" . "\r\n";
        $strSQL .= "      ,'41921'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //2018/02/26 修正 START
        ////2013/12/20 修正 START
        ////strSQL.Append("      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' THEN '291'" & vbCrLf)
        ////strSQL.Append("          ELSE '161' END L_BUSYO" & vbCrLf)
        //$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        //$strSQL .= "            THEN " . "\r\n";
        //$strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        //$strSQL .= "                      THEN '271'" . "\r\n";
        //$strSQL .= "                      ELSE '291' END" . "\r\n";
        ////---20151009 yin UPD S.
        ////$strSQL .= "            ELSE '161' END" . "\r\n";
        //$strSQL .= "            ELSE '161' END L_BUSYO" . "\r\n";
        ////---20151009 yin UPD E.
        ////2013/12/20 修正 END
//			$strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' " . "\r\n";
        $strSQL .= "      ,CASE WHEN A.KKR_CD='VLS' OR A.KKR_CD='VLW' OR A.KKR_CD='VLC' OR A.KKR_CD='X90' OR A.KKR_CD='X9C' OR A.KKR_CD='V60' OR A.KKR_CD='V9C' OR A.KKR_CD='V6C' OR A.KKR_CD='XC9'  OR A.KKR_CD='XC6'  OR A.KKR_CD='XC4'  OR A.KKR_CD='X60'  OR A.KKR_CD='C40'  OR A.KKR_CD='S90'  OR A.KKR_CD='S60'   " . "\r\n";

        $strSQL .= "            THEN " . "\r\n";
        $strSQL .= "                 CASE WHEN A.URK_BUSYO_CD = '271'" . "\r\n";
        $strSQL .= "                      THEN '271'" . "\r\n";
        $strSQL .= "                      ELSE '291' END" . "\r\n";
        $strSQL .= "            WHEN A.KKR_CD='LOT' " . "\r\n";
        $strSQL .= "            THEN '241' " . "\r\n";
        $strSQL .= "            ELSE '161' END L_BUSYO" . "\r\n";
        //2018/02/26 修正 END
        $strSQL .= "      ,'41921'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車月賦手数料データ" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,14" . "\r\n";
        $strSQL .= "      ,'019'" . "\r\n";
        $strSQL .= "      ,'82111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'82111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.KAP_TES_KJN,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.KAP_TES_KJN,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車登録諸費用基準　社内原価　業務課" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "        ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,15" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE '174' END " . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "    --,KJ_V.KJN_GENKA" . "\r\n";
        $strSQL .= "    --,(NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" . "\r\n";
        $strSQL .= "    --,KJ_V.HAIBUN_GK1" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("      ,DECODE(KJ_V.HAIBUN_GK1,NULL,(NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0)),KJ_V.HAIBUN_GK1)" & vbCrLf)
        $strSQL .= "      ,DECODE(KJ_V.HAIBUN_GK1,NULL,NVL(A.TOU_SYH_KJN,0),KJ_V.HAIBUN_GK1)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "     ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "        INNER Join" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        //strSQL.Append("-- WHERE KJ_V.HAIBUN_GK1 <> 0" & vbCrLf)
        //2006/10/16 UPDATE Start
        //strSQL.Append(" WHERE KJ_V.KJN_GENKA(+) = (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        $strSQL .= " WHERE KJ_V.KJN_GENKA(+) = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "   AND KJ_V.NAU_KB(+) =  '1'" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("   AND (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))<>0" & vbCrLf)
        $strSQL .= "   AND NVL(A.TOU_SYH_KJN,0) <> 0" . "\r\n";
        //2006/10/16 UPDATE End
        if ($strActmode == "S") {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車登録諸費用基準　社内原価　使用済" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,16" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE '211' END " . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK2" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" . "\r\n";
        }

        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= " WHERE KJ_V.HAIBUN_GK2 <> 0" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("   AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        $strSQL .= "   AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "   AND KJ_V.NAU_KB =  '1'" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'新車登録諸費用基準　社内原価　中古本部" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,17" . "\r\n";
        $strSQL .= "      ,'211'" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82121'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK3" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" . "\r\n";
        }

        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= " WHERE KJ_V.HAIBUN_GK3 <> 0" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("   AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0) + NVL(A.HOUTEIH_GK,0) - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))" & vbCrLf)
        $strSQL .= "   AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "   AND KJ_V.NAU_KB =  '1'" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND A.NAU_KB =  '1'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDENPNO, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：基準価格会計データ作成(SQL)
    //関 数 名：fncKijyunKaikeiInsert4SQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：当月売上データタを抽出し、会計データを作成する(SQL)
    //**********************************************************************
    function fncKijyunKaikeiInsert4SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        if ($strActmode == "S") {
            $strSQL .= " INSERT INTO HKAIKEI_S (" . "\r\n";
        } else {
            $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        }
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";
        $strSQL .= "--'中古再生見積" . "\r\n";
        //strSQL.Append("UNION ALL" & vbCrLf)
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,11" . "\r\n";
        //2010/10/26 UPD Start
        //''strSQL.Append("      ,CASE WHEN A.CKG_KB='1'and A.CKO_MEG_KB='1' THEN SUBSTR(A.URI_BUSYO_CD,1,2) ||'7' " & vbCrLf)
        $strSQL .= "      ,CASE WHEN A.CKO_HNB_KB IN ('1','2') AND A.CKO_MEG_KB = '1' THEN SUBSTR(A.URI_BUSYO_CD,1,2) ||'7' " . "\r\n";
        //2010/10/26 UPD End
        $strSQL .= "          ELSE '211' END R_BUSYO" . "\r\n";
        $strSQL .= "      ,'42221'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42221'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.CKO_SAI_MITUMORI,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        //''strSQL.Append(" WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.CKO_SAI_MITUMORI,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        //strSQL.Append("--'中古架装原価" & vbCrLf)      2010/10/20 UPD
        $strSQL .= "--'中古架装原価（特別架装原価）" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,12" . "\r\n";
        //2010/10/20 UPD Start
        //strSQL.Append("      ,(CASE WHEN A.URI_BUSYO_CD IN ('224','231')  THEN '212' " & vbCrLf)
        //strSQL.Append("            ELSE CASE WHEN A.URI_TANNO = '67112' THEN '427' " & vbCrLf)
        //strSQL.Append("            ELSE CASE WHEN A.URI_TANNO = '64326' THEN '417' " & vbCrLf)
        //strSQL.Append("            ELSE SUBSTR(A.URI_BUSYO_CD,1,2)||'7' END END END) R_BUSYO" & vbCrLf)
        $strSQL .= "      ,'212'" . "\r\n";
        //2010/10/20 UPD End
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.TKB_KSH_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        //''strSQL.Append(" WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.TKB_KSH_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        //2010/10/20 INS Start
        $strSQL .= "--'中古架装原価（付属品原価）" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        ///*--- 2011/01/13 Upd Start
        //中古架装原価（特別架装原価）とプライマリキーがダブル場合がある為、変更
        //strSQL.Append("      ,12" & vbCrLf)
        $strSQL .= "      ,32" . "\r\n";
        ///*--- 2011/01/13 Upd End
        $strSQL .= "      ,(CASE WHEN A.URI_BUSYO_CD IN ('224','231','261')  THEN A.URI_BUSYO_CD " . "\r\n";
        $strSQL .= "            ELSE SUBSTR(A.URI_BUSYO_CD,1,2)||'7' END) R_BUSYO" . "\r\n";
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD L_BUSYO" . "\r\n";
        $strSQL .= "      ,'42215'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.FHZ_PCS,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        if ($strActmode == "S") {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.FHZ_PCS,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        //2010/10/20 INS End

        $strSQL .= "--'中古車両収入手数料" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,13" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD R_BUSYO" . "\r\n";
        $strSQL .= "      ,'41922'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,'211' L_BUSYO" . "\r\n";
        $strSQL .= "      ,'41922'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0)" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE   " . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        //''strSQL.Append(" WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.UKM_SNY_TES,0)+NVL(A.UKM_SINSEI_SYR,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古月賦手数料データ" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,14" . "\r\n";
        $strSQL .= "      ,'019'" . "\r\n";
        $strSQL .= "      ,'82112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82112'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,NVL(A.KAP_TES_KJN,0)*0.6" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= " WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        //''strSQL.Append(" WHERE A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND NVL(A.KAP_TES_KJN,0) <> 0" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古登録諸費用基準　社内原価　業務課" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,15" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE DECODE(KJ_V.HAIBUN_GK1,NULL,'211','174') END " . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //strSQL.Append("      ,KJ_V.HAIBUN_GK1" & vbCrLf)
        //2006/10/16 UPDATE start
        //strSQL.Append("      ,DECODE(KJ_V.HAIBUN_GK1,NULL,(NVL(A.TOU_SYH_KJN,0)- NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0)),KJ_V.HAIBUN_GK1)" & vbCrLf)
        $strSQL .= "      ,DECODE(KJ_V.HAIBUN_GK1,NULL,NVL(A.TOU_SYH_KJN,0),KJ_V.HAIBUN_GK1)" . "\r\n";
        //2006/10/16 UPDATE end
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" . "\r\n";
        }

        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("WHERE KJ_V.KJN_GENKA(+) = (NVL(A.TOU_SYH_KJN,0)- NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0) )" & vbCrLf)
        $strSQL .= "WHERE KJ_V.KJN_GENKA(+) = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE end
        $strSQL .= "  AND KJ_V.NAU_KB(+) =  '2'" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("  AND (NVL(A.TOU_SYH_KJN,0)  - NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0))<>0" & vbCrLf)
        $strSQL .= "  AND NVL(A.TOU_SYH_KJN,0) <> 0" . "\r\n";
        //2006/10/16 UPDATE end
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        //''strSQL.Append("  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End

        $strSQL .= "  AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "  AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "  AND A.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古登録諸費用基準　社内原価　中古管理" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,16" . "\r\n";
        $strSQL .= "      ,CASE WHEN A.RIKUJI_CD='福山' THEN '667' ELSE '211' END " . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK2" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= "WHERE KJ_V.HAIBUN_GK2 <> 0" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("  AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0)- NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0) )" & vbCrLf)
        $strSQL .= "  AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE end
        $strSQL .= "  AND KJ_V.NAU_KB =  '2'" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        //'''strSQL.Append("  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "  AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "  AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "  AND A.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古登録諸費用基準　社内原価　中古本部" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,17" . "\r\n";
        $strSQL .= "      ,'211'" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'82122'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,KJ_V.HAIBUN_GK3" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "     ,(SELECT KJ.* FROM HKIJUNGENKATBL KJ" . "\r\n";
        $strSQL .= "           INNER JOIN" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN_S SC" . "\r\n";
        } else {
            $strSQL .= "                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" . "\r\n";
        }
        //''strSQL.Append("                    FROM HKIJUNGENKATBL KJ,HJYOUHEN SC" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   WHERE TO_CHAR(LAST_DAY(TO_DATE(SC.KEIJYO_YM||'01')),'YYYYMMDD') >= KJ.KIJYUN_DT" . "\r\n";
        $strSQL .= "                     AND SC.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "                     AND SC.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 ) V" . "\r\n";
        $strSQL .= "              ON  KJ.KIJYUN_DT = V.KIJYUN_DT) KJ_V" . "\r\n";
        $strSQL .= "WHERE KJ_V.HAIBUN_GK3 <> 0" . "\r\n";
        //2006/10/16 UPDATE start
        //strSQL.Append("  AND KJ_V.KJN_GENKA = (NVL(A.TOU_SYH_KJN,0)- NVL(A.RCY_YOT_KIN,0) - NVL(A.RCY_SKN_KAN_HI,0) )" & vbCrLf)
        $strSQL .= "  AND KJ_V.KJN_GENKA = NVL(A.TOU_SYH_KJN,0)" . "\r\n";
        //2006/10/16 UPDATE end
        $strSQL .= "  AND KJ_V.NAU_KB =  '2'" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        //''strSQL.Append("  AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "  AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "  AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "  AND A.NAU_KB =  '2'" . "\r\n";
        $strSQL .= "  AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL .= "--'中古本部負担金" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "       ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE(C.KEIJYO_YM||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'2J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CMN_NO" . "\r\n";
        $strSQL .= "      ,18" . "\r\n";
        $strSQL .= "      ,'211'" . "\r\n";
        $strSQL .= "      ,'84111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        $strSQL .= "      ,A.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,'84111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.UC_NO" . "\r\n";
        //2010/10/26 UPD Start
        //''strSQL.Append("      ,CASE WHEN A.CKG_KB = '1' THEN 8000 ELSE 5000 END" & vbCrLf)
        ///*--- 2013/11/02 Upd Start
        //strSQL.Append("      ,CASE WHEN A.CKO_HNB_KB IN ('1','2') THEN 8000 ELSE 5000 END" & vbCrLf)
        //2010/10/26 UPD End
        //◆負担金変更　5,000→7,000　8,000→10,000
        $strSQL .= "      ,CASE WHEN A.CKO_HNB_KB IN ('1','2') THEN 10000 ELSE 7000 END" . "\r\n";
        ///*--- 2013/11/02 Upd End
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,A.CARNO" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "      ,HSCURI_VW C" . "\r\n";
        }
        //''strSQL.Append("  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        //2010/10/26 UPD Start
        //''strSQL.Append(" WHERE (A.CKG_KB = '1' OR (A.CKO_HNB_KB <> '5' AND A.CKO_HNB_KB <> '9') )" & vbCrLf)
        $strSQL .= " WHERE (A.CKO_HNB_KB <> '5' AND A.CKO_HNB_KB <> '9')" . "\r\n";
        //2010/10/26 UPD End
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }

        //''strSQL.Append("   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "   AND A.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "   AND C.KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        //===========================================================================================
        //ペナルティ
        //===========================================================================================
        //strSQL.Append("--'新車ペナルティ" & vbCrLf)
        //strSQL.Append("UNION ALL" & vbCrLf)
        //strSQL.Append("SELECT" & vbCrLf)
        //strSQL.Append("       '122'" & vbCrLf)
        //strSQL.Append("      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" & vbCrLf)
        //strSQL.Append("      ,'1J'" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.CMN_NO" & vbCrLf)
        //strSQL.Append("      ,20" & vbCrLf)
        //strSQL.Append("      ,A.URK_BUSYO_CD" & vbCrLf)
        //strSQL.Append("      ,'85114'" & vbCrLf)
        //strSQL.Append("      ,A.URI_TANNO" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.UC_NO" & vbCrLf)
        //strSQL.Append("      ,'019'" & vbCrLf)
        //strSQL.Append("      ,'85114'" & vbCrLf)
        //strSQL.Append("      ,A.URI_TANNO" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.UC_NO" & vbCrLf)
        //'strSQL.Append("      ,ROUND(NVL(A.PENALTY,0) * 1200 / TESURYO_M.SYANAI_RT)" & vbCrLf)       '手数料
        //strSQL.Append("      ,NVL(A.PENALTY,0)" & vbCrLf)       'ペナルティ
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.CARNO" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,'SC'" & vbCrLf)
        //strSQL.Append("      ,NULL" & vbCrLf)
        //strSQL.Append("      ,SYSDATE" & vbCrLf)
        //strSQL.Append("      ,SYSDATE " & vbCrLf)
        //strSQL.Append("  FROM HSCURI A" & vbCrLf)
        //''手数料マスタ
        //strSQL.Append(",(SELECT TESU_M.* FROM HTESURYO TESU_M" & vbCrLf)
        //strSQL.Append("   INNER Join" & vbCrLf)
        //strSQL.Append("         (SELECT MAX(KIJYUN_DT) KIJYUN_DT" & vbCrLf)
        //strSQL.Append("            FROM   HTESURYO TEST_V,HSCURI A" & vbCrLf)
        //strSQL.Append("           WHERE A.KEIJYO_YM||'31' >= TEST_V.KIJYUN_DT ) V" & vbCrLf)
        //strSQL.Append("      ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TESURYO_M" & vbCrLf)
        //strSQL.Append(" WHERE NVL(A.PENALTY,0) <> 0" & vbCrLf)
        //strSQL.Append("   AND A.NAU_KB = '1'" & vbCrLf)
        //strSQL.Append("   AND A.KEIJYO_YM = '@KEIJYOBI'" & vbCrLf)

        //strSQL.Append("--'中古ペナルティ" & vbCrLf)
        //strSQL.Append("UNION ALL" & vbCrLf)
        //strSQL.Append("SELECT" & vbCrLf)
        //strSQL.Append("       '122'" & vbCrLf)
        //strSQL.Append("      ,TO_CHAR(LAST_DAY(TO_DATE(A.KEIJYO_YM||'01')),'YYYYMMDD')" & vbCrLf)
        //strSQL.Append("      ,'1J'" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.CMN_NO" & vbCrLf)
        //strSQL.Append("      ,20" & vbCrLf)
        //strSQL.Append("      ,A.URK_BUSYO_CD" & vbCrLf)
        //strSQL.Append("      ,'85115'" & vbCrLf)
        //strSQL.Append("      ,A.URI_TANNO" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.UC_NO" & vbCrLf)
        //strSQL.Append("      ,'019'" & vbCrLf)
        //strSQL.Append("      ,'85115'" & vbCrLf)
        //strSQL.Append("      ,A.URI_TANNO" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.UC_NO" & vbCrLf)
        //strSQL.Append("      ,ROUND(NVL(A.PENALTY,0) * 1200 / TESURYO_M.NEN_RT)" & vbCrLf)       '手数料
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,A.CARNO" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,''" & vbCrLf)
        //strSQL.Append("      ,'SC'" & vbCrLf)
        //strSQL.Append("      ,NULL" & vbCrLf)
        //strSQL.Append("      ,SYSDATE" & vbCrLf)
        //strSQL.Append("      ,SYSDATE " & vbCrLf)
        //strSQL.Append("  FROM HSCURI A" & vbCrLf)
        //''手数料マスタ
        //strSQL.Append(",(SELECT TESU_M.* FROM HTESURYO TESU_M" & vbCrLf)
        //strSQL.Append("   INNER Join" & vbCrLf)
        //strSQL.Append("         (SELECT MAX(KIJYUN_DT) KIJYUN_DT" & vbCrLf)
        //strSQL.Append("            FROM   HTESURYO TEST_V,HSCURI A" & vbCrLf)
        //strSQL.Append("           WHERE A.KEIJYO_YM||'31' >= TEST_V.KIJYUN_DT ) V" & vbCrLf)
        //strSQL.Append("      ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TESURYO_M" & vbCrLf)
        //strSQL.Append(" WHERE NVL(A.PENALTY,0) <> 0" & vbCrLf)
        //strSQL.Append("   AND A.NAU_KB = '2'" & vbCrLf)
        //strSQL.Append("   AND A.KEIJYO_YM = '@KEIJYOBI'" & vbCrLf)

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDENPNO, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：基準価格会計データ作成(SQL)
    //関 数 名：fncKijyunKaikeiInsert5SQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：当月売上データタを抽出し、会計データを作成する(SQL)
    //**********************************************************************
    function fncKijyunKaikeiInsert5SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        if ($strActmode == "S") {
            $strSQL .= " INSERT INTO HKAIKEI_S (" . "\r\n";
        } else {
            $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        }
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";

        //'総括データ
        $strSQL .= "--'総括" . "\r\n";
        //strSQL.Append("UNION ALL" & vbCrLf)
        $strSQL .= "SELECT ''" . "\r\n";
        $strSQL .= "      ,TO_CHAR(LAST_DAY(TO_DATE('@KEIJYOBI'||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "      ,'1J'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'@DENPNO'" . "\r\n";
        $strSQL .= "      ,1" . "\r\n";
        $strSQL .= "      ,'174'" . "\r\n";
        $strSQL .= "      ,'42111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'251'" . "\r\n";
        $strSQL .= "      ,'42111'" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,(VV.CNT) * 5000" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,'SC'" . "\r\n";
        $strSQL .= "      ,NULL" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",     '@UPDUSER'" . "\r\n";
        $strSQL .= ",     '@UPDAPP'" . "\r\n";
        $strSQL .= ",     '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "  FROM (SELECT KEIJYO_YM" . "\r\n";
        $strSQL .= "              ,SUM(V.CNT) CNT" . "\r\n";
        $strSQL .= "          FROM (SELECT KEIJYO_YM" . "\r\n";
        $strSQL .= "                      ,1 CNT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                  FROM HSCURI_S_VW" . "\r\n";
        } else {
            $strSQL .= "                  FROM HSCURI_VW" . "\r\n";
        }
        //''strSQL.Append("                  FROM HSCURI_VW" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                 WHERE (TRK_KB IN ('1','2')" . "\r\n";
        //2018-02-26 修正 START
        //$strSQL .= "                    OR  URI_BUSYO_CD='168' AND KKR_CD NOT IN ('VLS','VLW','VLC'))" . "\r\n";
//			$strSQL .= "                    OR  URI_BUSYO_CD='168' AND KKR_CD NOT IN ('VLS','VLW','VLC','LOT'))" . "\r\n";
        $strSQL .= "                    OR  URI_BUSYO_CD='168' AND KKR_CD NOT IN ('VLS','VLW','VLC','LOT','X90','X9C','V60','V9C','V6C','XC9','XC6','XC4','X60','C40' ,'S90','S60'  ))" . "\r\n";


        //2018-02-26 修正 END
        $strSQL .= "                   AND KEIJYO_YM = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                 UNION ALL" . "\r\n";
        $strSQL .= "                SELECT C.KEIJYO_YM" . "\r\n";
        $strSQL .= "                      ,-1 CNT" . "\r\n";
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                  FROM HJYOUHEN_S A" . "\r\n";
            $strSQL .= "                      ,HSCURI_S_VW C" . "\r\n";
        } else {
            $strSQL .= "                  FROM HJYOUHEN A" . "\r\n";
            $strSQL .= "                      ,HSCURI_VW C" . "\r\n";
        }

        //''strSQL.Append("                  FROM HJYOUHEN A" & vbCrLf)
        //''strSQL.Append("                      ,HSCURI_VW C" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                 WHERE (A.TRK_KB IN ('1','2')" . "\r\n";
        //2018-02-26 修正 START
        //$strSQL .= "                    OR  A.URI_BUSYO_CD='168' AND A.KKR_CD NOT IN ('VLS','VLW','VLC'))" . "\r\n";
//			$strSQL .= "                    OR  A.URI_BUSYO_CD='168' AND A.KKR_CD NOT IN ('VLS','VLW','VLC','LOT'))" . "\r\n";
        $strSQL .= "                    OR  A.URI_BUSYO_CD='168' AND A.KKR_CD NOT IN ('VLS','VLW','VLC','LOT','X90','X9C','V60','V9C','V6C','XC9','XC6','XC4','X60','C40' ,'S90','S60'  ))" . "\r\n";

        //2018-02-26 修正 END
        //2010/01/28 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "                   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN_S D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        } else {
            $strSQL .= "                   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" . "\r\n";
        }
        //''strSQL.Append("                   AND A.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.CMN_NO = C.CMN_NO AND D.KEIJYO_YM < '@KEIJYOBI')" & vbCrLf)
        //2010/01/28 UPD End
        $strSQL .= "                   AND A.CMN_NO = C.CMN_NO" . "\r\n";
        $strSQL .= "                   AND A.KEIJYO_YM < '@KEIJYOBI'" . "\r\n";
        $strSQL .= "                   AND C.KEIJYO_YM = '@KEIJYOBI') V" . "\r\n";
        $strSQL .= "         GROUP BY KEIJYO_YM) VV" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDENPNO, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：残高ﾃﾞｰﾀ削除
    //関 数 名：fncKNRZANDeleteSQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：引渡された計上日範囲内の残高ﾃﾞｰﾀを削除する
    //**********************************************************************
    function fncKNRZANDeleteSQL($strKEIJYOBI)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        $strSQL .= "  FROM HKNRZAN" . "\r\n";
        $strSQL .= " WHERE SUBSTR(KEIJO_DT,1,6) = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "	  AND KAMOK_CD IN ('11210','11220','11230','11329','11161', '11162')" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：当月資産残高金利データ作成(SQL)
    //関 数 名：fncSSKNRZANInsertSQL
    //引    数：strDepend　　　(I)画面：処理年月
    //戻 り 値：SQL文
    //処理説明：当月資産残高金利データ作成する(SQL)
    //**********************************************************************
    function fncSSKNRZANInsertSQL($strDepend, $strUpdPro)
    {
        $strZenDT = "";
        $strMatuDT = "";
        $strDT = "";
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL .= "INSERT INTO HKNRZAN" . "\r\n";
        $strSQL .= "(" . "\r\n";
        $strSQL .= "   KEIJO_DT" . "\r\n";
        $strSQL .= " , DATA_KB" . "\r\n";
        $strSQL .= " , TAISK_KB" . "\r\n";
        $strSQL .= " , BUSYO_CD" . "\r\n";
        $strSQL .= " , KAMOK_CD" . "\r\n";
        $strSQL .= " , ZEN_GK" . "\r\n";
        $strSQL .= " , TOU_GK" . "\r\n";
        $strSQL .= " , TAISYOU_GK" . "\r\n";
        $strSQL .= " , KINRI_GK" . "\r\n";
        $strSQL .= " , UPD_DATE" . "\r\n";
        $strSQL .= " , CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",  UPD_SYA_CD" . "\r\n";
        $strSQL .= ",  UPD_PRG_ID" . "\r\n";
        $strSQL .= ",  UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT  '@MATUJITU'" . "\r\n";
        $strSQL .= ",       ' '" . "\r\n";
        $strSQL .= ",       '1'" . "\r\n";
        $strSQL .= ",       V_ZAN.BUSYO_CD" . "\r\n";
        $strSQL .= ",       V_ZAN.KAMOK_CD" . "\r\n";
        $strSQL .= ",       V_ZAN.TOU_ZAN" . "\r\n";
        $strSQL .= ",       (CASE WHEN V_ZAN.KAMOK_CD IN ('11210','11220') THEN V_ZAN.HASSEI" . "\r\n";
        $strSQL .= "              WHEN V_ZAN.KAMOK_CD IN ('11230','11329') THEN 0 END) HASSEIGAKU" . "\r\n";
        $strSQL .= ",       (CASE WHEN V_ZAN.KAMOK_CD IN ('11210','11230','11329') THEN 0 " . "\r\n";
        $strSQL .= "              WHEN V_ZAN.KAMOK_CD = '11220' THEN V_ZAN.TOU_ZAN END) KINRITAISYOU" . "\r\n";
        $strSQL .= ",       (CASE WHEN V_ZAN.KAMOK_CD IN ('11210','11230','11329') THEN 0" . "\r\n";
        $strSQL .= "              WHEN V_ZAN.KAMOK_CD = '11220' THEN ROUND(ROUND(V_ZAN.TOU_ZAN * TESURYO_M.NEN_RT*10 / 12) / 1000) END) KINRI" . "\r\n";
        $strSQL .= ",       SYSDATE" . "\r\n";
        $strSQL .= ",       SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       '@UPDUSER'" . "\r\n";
        $strSQL .= ",       '@UPDAPP'" . "\r\n";
        $strSQL .= ",       '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        $strSQL .= "         " . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "		SELECT  ZANDAKA.BUSYO_CD" . "\r\n";
        $strSQL .= "		,       ZANDAKA.KAMOK_CD" . "\r\n";
        $strSQL .= "		,       NVL(WK_KAIKEI.KARI_KEI,0) HASSEI" . "\r\n";
        $strSQL .= "		,       ZANDAKA.ZEN_GK + NVL(WK_KAIKEI.KARI_KEI,0) - NVL(WK_KAIKEI.KASI_KEI,0) TOU_ZAN" . "\r\n";
        $strSQL .= "		FROM    (SELECT ZAN.BUSYO_CD" . "\r\n";
        $strSQL .= "		         ,      ZAN.KAMOK_CD" . "\r\n";
        $strSQL .= "		         ,      ZAN.ZEN_GK" . "\r\n";
        $strSQL .= "		         FROM   HKNRZAN ZAN" . "\r\n";
        $strSQL .= "		         WHERE  ZAN.KAMOK_CD IN ('11210','11220','11230','11329')" . "\r\n";
        $strSQL .= "		         AND    SUBSTR(ZAN.KEIJO_DT,1,6) = '@ZENYM'" . "\r\n";
        $strSQL .= "		        ) ZANDAKA" . "\r\n";
        $strSQL .= "		LEFT JOIN" . "\r\n";
        $strSQL .= "			" . "\r\n";
        $strSQL .= "			    (SELECT  V.BUSYO_CD" . "\r\n";
        $strSQL .= "				,       V.KAMOK_CD" . "\r\n";
        $strSQL .= "				,       SUM(NVL(V.KARI_KEIJO_GK,0)) KARI_KEI" . "\r\n";
        $strSQL .= "				,       SUM(NVL(V.KASI_KEIJO_GK,0)) KASI_KEI" . "\r\n";
        $strSQL .= "                  FROM    (SELECT KAI.L_BUSYO_CD BUSYO_CD" . "\r\n";
        $strSQL .= "                               ,(CASE WHEN KAI.L_KAMOK_CD IN ('11210', '11211','11212') THEN '11210'" . "\r\n";
        $strSQL .= "                                      WHEN KAI.L_KAMOK_CD IN ('11220', '11221', '11222') THEN '11220'" . "\r\n";
        $strSQL .= "                                      WHEN KAI.L_KAMOK_CD IN ('11230', '11231') THEN '11230'" . "\r\n";
        $strSQL .= "                                      WHEN KAI.L_KAMOK_CD IN ('11323', '11344') OR (KAI.L_KAMOK_CD='11346' AND TRIM(KAI.L_KOMOK_CD)='5') THEN '11329' END) KAMOK_CD" . "\r\n";
        $strSQL .= "                               ,KAI.KEIJO_GK KARI_KEIJO_GK" . "\r\n";
        $strSQL .= "                               ,0 KASI_KEIJO_GK" . "\r\n";
        $strSQL .= "                           FROM HKAIKEI KAI" . "\r\n";
        $strSQL .= "                          WHERE (KAI.L_KAMOK_CD IN" . "\r\n";
        $strSQL .= "                                               ('11210','11211','11212','11220','11221'," . "\r\n";
        $strSQL .= "                                                '11222','11230','11231','11323','11344')" . "\r\n";
        $strSQL .= "                              OR (KAI.L_KAMOK_CD='11346' AND TRIM(KAI.L_KOMOK_CD)='5'))" . "\r\n";
        $strSQL .= "                            AND SUBSTR(KAI.KEIJO_DT,1,6) = '@SYORIYM'" . "\r\n";
        $strSQL .= "                          UNION ALL" . "\r\n";
        $strSQL .= "                          SELECT KAI.R_BUSYO_CD" . "\r\n";
        $strSQL .= "                                ,(CASE WHEN KAI.R_KAMOK_CD IN ('11210', '11211','11212') THEN '11210'" . "\r\n";
        $strSQL .= "                                       WHEN KAI.R_KAMOK_CD IN ('11220', '11221', '11222') THEN '11220'" . "\r\n";
        $strSQL .= "                                       WHEN KAI.R_KAMOK_CD IN ('11230', '11231') THEN '11230'" . "\r\n";
        $strSQL .= "                                       WHEN KAI.R_KAMOK_CD IN ('11323', '11344') OR (KAI.R_KAMOK_CD='11346' AND TRIM(KAI.R_KOMOK_CD)='5')  THEN '11329' END) KAMOK_CD" . "\r\n";
        $strSQL .= "                                ,NULL KARI_KEIJO_GK" . "\r\n";
        $strSQL .= "                                ,KAI.KEIJO_GK KASI_KEIJO_GK" . "\r\n";
        $strSQL .= "                            FROM HKAIKEI KAI" . "\r\n";
        $strSQL .= "                           WHERE (KAI.R_KAMOK_CD IN" . "\r\n";
        $strSQL .= "                                                ('11210','11211','11212','11220','11221'," . "\r\n";
        $strSQL .= "                                                 '11222','11230','11231','11323','11344')" . "\r\n";
        $strSQL .= "                              OR (KAI.R_KAMOK_CD='11346' AND TRIM(KAI.R_KOMOK_CD)='5'))" . "\r\n";
        $strSQL .= "                             AND SUBSTR(KAI.KEIJO_DT,1,6) = '@SYORIYM'" . "\r\n";
        $strSQL .= "                                ) V" . "\r\n";
        $strSQL .= "		" . "\r\n";
        $strSQL .= "				GROUP BY V.BUSYO_CD" . "\r\n";
        $strSQL .= "				,        V.KAMOK_CD" . "\r\n";
        $strSQL .= "				" . "\r\n";
        $strSQL .= "				) WK_KAIKEI" . "\r\n";
        $strSQL .= "		ON  ZANDAKA.BUSYO_CD = WK_KAIKEI.BUSYO_CD" . "\r\n";
        $strSQL .= "		AND ZANDAKA.KAMOK_CD = WK_KAIKEI.KAMOK_CD" . "\r\n";
        $strSQL .= "		) V_ZAN" . "\r\n";
        //'手数料マスタ
        $strSQL .= ",(SELECT TESU_M.* FROM HTESURYO TESU_M" . "\r\n";
        $strSQL .= "   INNER Join" . "\r\n";
        $strSQL .= "         (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        $strSQL .= "            FROM   HTESURYO TEST_V,HSCURI A" . "\r\n";
        $strSQL .= "           WHERE '@MATUJITU' >= TEST_V.KIJYUN_DT ) V" . "\r\n";
        $strSQL .= "      ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TESURYO_M" . "\r\n";

        $strDT = $strDepend . "01";

        //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应
        $strZenDT = date('Ym', strtotime("-1 month", date('Y/m/d', $strDT)));
        $strMatuDT = date("Ymd", strtotime("-1 day", strtotime("+1 month", date("Y/m/d", $strDT))));
        //20140221 luchao 这个地方有可能出现问题，开发的时候请仔细对应

        $strSQL = str_replace("@ZENYM", $strZenDT, $strSQL);
        $strSQL = str_replace("@SYORIYM", $strDepend, $strSQL);
        $strSQL = str_replace("@MATUJITU", $strMatuDT, $strSQL);

        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：当月資産残高金利会計データ作成(SQL)
    //関 数 名：fncSSKNRKaikeiInsertSQL
    //引    数：strdepend     (I)画面：処理年月
    //　　　　：strdenpno     (I)伝票番号
    //戻 り 値：SQL文
    //処理説明：当月資産残高金利会計データを作成する(SQL)
    //**********************************************************************
    function fncSSKNRKaikeiInsertSQL($strdepend, $strdenpno, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL .= "INSERT INTO HKAIKEI" . "\r\n";
        $strSQL .= "(" . "\r\n";
        $strSQL .= "  INP_BUSYO" . "\r\n";
        $strSQL .= ", KEIJO_DT" . "\r\n";
        $strSQL .= ", SYOHY_NO" . "\r\n";
        $strSQL .= ", DENPY_NO" . "\r\n";
        $strSQL .= ", GYO_NO" . "\r\n";
        $strSQL .= ", L_BUSYO_CD" . "\r\n";
        $strSQL .= ", L_KAMOK_CD" . "\r\n";
        $strSQL .= ", L_KOMOK_CD" . "\r\n";
        $strSQL .= ", L_HIMOK_CD" . "\r\n";
        $strSQL .= ", L_BK" . "\r\n";
        $strSQL .= ", L_UC_NO" . "\r\n";
        $strSQL .= ", R_BUSYO_CD" . "\r\n";
        $strSQL .= ", R_KAMOK_CD" . "\r\n";
        $strSQL .= ", R_KOMOK_CD" . "\r\n";
        $strSQL .= ", R_HIMOK_CD" . "\r\n";
        $strSQL .= ", R_BK" . "\r\n";
        $strSQL .= ", R_UC_NO" . "\r\n";
        $strSQL .= ", KEIJO_GK" . "\r\n";
        $strSQL .= ", TEKIYO1" . "\r\n";
        $strSQL .= ", TEKIYO2" . "\r\n";
        $strSQL .= ", TEKIYO3" . "\r\n";
        $strSQL .= ", KAZEI_KB" . "\r\n";
        $strSQL .= ", ZEI_RT_KB" . "\r\n";
        $strSQL .= ", HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ", CEL_DATE" . "\r\n";
        $strSQL .= ", UPD_DATE" . "\r\n";
        $strSQL .= ", CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ", UPD_SYA_CD" . "\r\n";
        $strSQL .= ", UPD_PRG_ID" . "\r\n";
        $strSQL .= ", UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT ' '" . "\r\n";
        $strSQL .= ",      ZAN.KEIJO_DT" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      '@DENPNO'" . "\r\n";
        $strSQL .= ",      ROWNUM" . "\r\n";
        $strSQL .= ",      '211'" . "\r\n";
        $strSQL .= ",      '85122'" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      '019'" . "\r\n";
        $strSQL .= ",      '85122'" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ZAN.KINRI_GK" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      'ZN'" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "FROM   HKNRZAN ZAN" . "\r\n";
        $strSQL .= "WHERE  ZAN.KAMOK_CD IN ('11210','11220','11230','11323')" . "\r\n";
        $strSQL .= "AND    SUBSTR(ZAN.KEIJO_DT,1,6) = '@KEIJYOBI'" . "\r\n";
        $strSQL .= "AND    ZAN.KINRI_GK > 0" . "\r\n";
        $strSQL .= "AND    ZAN.BUSYO_CD = '211'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strdepend, $strSQL);
        $strSQL = str_replace("@DENPNO", $strdenpno, $strSQL);

        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：一般売掛金残高データ作成(SQL)
    //関 数 名：fncIPKNRZANInsert
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：当月一般売掛金残高データを作成する(SQL)
    //**********************************************************************
    function fncIPKNRZANInsertSQL($strDepend, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strFromDT = "";
        $strTODT = "";

        $strSQL .= "INSERT INTO HKNRZAN" . "\r\n";
        $strSQL .= "           (" . "\r\n";
        $strSQL .= "            KEIJO_DT" . "\r\n";
        $strSQL .= "           ,DATA_KB" . "\r\n";
        $strSQL .= "           ,TAISK_KB" . "\r\n";
        $strSQL .= "           ,BUSYO_CD" . "\r\n";
        $strSQL .= "           ,KAMOK_CD" . "\r\n";
        $strSQL .= "           ,ZEN_GK" . "\r\n";
        $strSQL .= "           ,TOU_GK" . "\r\n";
        $strSQL .= "           ,TAISYOU_GK " . "\r\n";
        $strSQL .= "           ,KINRI_GK" . "\r\n";
        $strSQL .= "           ,UPD_DATE" . "\r\n";
        $strSQL .= "           ,CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "           )" . "\r\n";
        $strSQL .= "SELECT '@KEIJYOBI'" . "\r\n";
        $strSQL .= "       ,' '" . "\r\n";
        $strSQL .= "       ,'1'" . "\r\n";
        $strSQL .= "       ,Z.BUSYO_CD" . "\r\n";
        $strSQL .= "       ,DECODE(Z.BUSYO_CD,'258','11161','11162')" . "\r\n";
        $strSQL .= "       ,NVL(Z.ZEN_GK,0) + NVL(VV.KEIJO_GK,0)" . "\r\n";
        //--当月残
        $strSQL .= "       ,VV.HASSEI_GK" . "\r\n";
        //--当月発生額
        $strSQL .= "       ,CASE WHEN (Z.BUSYO_CD IN ('122','080') OR (NVL(Z.ZEN_GK,0) + NVL(VV.KEIJO_GK,0)) - ROUND(NVL(VV.HASSEI_GK,0)/3)<0)" . "\r\n";
        $strSQL .= "            THEN 0 ELSE (NVL(Z.ZEN_GK,0) + NVL(VV.KEIJO_GK,0)) - ROUND(NVL(VV.HASSEI_GK,0)/3) END" . "\r\n";
        $strSQL .= "       ,CASE WHEN (Z.BUSYO_CD IN ('122','080') OR (NVL(Z.ZEN_GK,0) + NVL(VV.KEIJO_GK,0)) - ROUND(NVL(VV.HASSEI_GK,0)/3)<0)" . "\r\n";
        $strSQL .= "            THEN 0 ELSE " . "\r\n";
        $strSQL .= "                 ROUND(ROUND((NVL(Z.ZEN_GK,0) + NVL(VV.KEIJO_GK,0) - ROUND(NVL(VV.HASSEI_GK,0)/3)) * TESURYO_M.NEN_RT*10 /12)/1000) END" . "\r\n";
        //strSQL.Append("       ,(NVL(Z.ZEN_GK,0) + NVL(VV.KEIJO_GK,0)) - ROUND(NVL(VV.HASSEI_GK,0)/3)" & vbCrLf)                                 '--当月金利対象
        $strSQL .= "       ,SYSDATE" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "  FROM  HKNRZAN Z" . "\r\n";
        $strSQL .= "       ,(SELECT V.BUSYO_CD" . "\r\n";
        $strSQL .= "               ,SUM(V.KEIJO_GK)  KEIJO_GK" . "\r\n";
        $strSQL .= "               ,SUM(V.HASSEI_GK) HASSEI_GK" . "\r\n";
        $strSQL .= "           FROM" . "\r\n";
        $strSQL .= "                --借方集計" . "\r\n";
        $strSQL .= "               (SELECT L_BUSYO_CD  BUSYO_CD" . "\r\n";
        $strSQL .= "                      ,SUM(KEIJO_GK)  KEIJO_GK" . "\r\n";
        $strSQL .= "                      ,SUM((CASE WHEN R_KAMOK_CD NOT IN ('11111','11122','11141','11142','11144','11143')" . "\r\n";
        $strSQL .= "                                 THEN KEIJO_GK ELSE 0 END )) HASSEI_GK" . "\r\n";
        $strSQL .= "                  FROM HKAIKEI" . "\r\n";
        $strSQL .= "                 WHERE L_KAMOK_CD IN ('11144','11143','11144','11143')" . "\r\n";
        $strSQL .= "                   AND KEIJO_DT >= '@KEIJYOFROM' AND KEIJO_DT <= '@KEIJYOTO'" . "\r\n";
        $strSQL .= "                 GROUP BY L_BUSYO_CD" . "\r\n";
        $strSQL .= "               --貸方集計" . "\r\n";
        $strSQL .= "                 UNION ALL" . "\r\n";
        $strSQL .= "                SELECT R_BUSYO_CD BUSYO_CD" . "\r\n";
        $strSQL .= "                      ,SUM(KEIJO_GK)*-1 KEIJO_GK" . "\r\n";
        $strSQL .= "                      ,SUM((CASE WHEN L_KAMOK_CD IN ('41414','41415','41416','41417','41418','21271','11323','11344') OR (L_KAMOK_CD = '21182' AND L_KOMOK_CD = '8')" . "\r\n";
        $strSQL .= "                                 THEN KEIJO_GK ELSE 0 END )) * -1 HASSEI_GK" . "\r\n";
        $strSQL .= "                  FROM HKAIKEI" . "\r\n";
        $strSQL .= "                 WHERE R_KAMOK_CD IN ('11144','11143')" . "\r\n";
        $strSQL .= "                   AND KEIJO_DT >= '@KEIJYOFROM' AND KEIJO_DT <= '@KEIJYOTO'" . "\r\n";
        $strSQL .= "                 GROUP BY R_BUSYO_CD ) V" . "\r\n";
        $strSQL .= " GROUP BY V.BUSYO_CD ) VV" . "\r\n";
        //'手数料マスタ
        $strSQL .= ",(SELECT TESU_M.* FROM HTESURYO TESU_M" . "\r\n";
        $strSQL .= "   INNER Join" . "\r\n";
        $strSQL .= "         (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        $strSQL .= "            FROM   HTESURYO TEST_V,HSCURI A" . "\r\n";
        $strSQL .= "           WHERE '@KEIJYOBI' >= TEST_V.KIJYUN_DT ) V" . "\r\n";
        $strSQL .= "      ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TESURYO_M" . "\r\n";
        $strSQL .= " WHERE Z.BUSYO_CD = VV.BUSYO_CD(+)" . "\r\n";
        $strSQL .= "   AND Z.KAMOK_CD IN ('11161', '11162')" . "\r\n";
        $strSQL .= "   AND Z.KEIJO_DT = '@ZKEIJYOBI'" . "\r\n";

        $strSQL .= " --会計データのみ発生" . "\r\n";
        $strSQL .= " UNION ALL" . "\r\n";
        $strSQL .= " SELECT '@KEIJYOBI'" . "\r\n";
        $strSQL .= "       ,' '" . "\r\n";
        $strSQL .= "       ,'1'" . "\r\n";
        $strSQL .= "       ,BUSYO_CD" . "\r\n";
        $strSQL .= "       ,DECODE(BUSYO_CD,'258','11161','11162')" . "\r\n";
        $strSQL .= "       ,NVL(VV.KEIJO_GK,0)" . "\r\n";
        //--当月残高
        $strSQL .= "       ,VV.HASSEI_GK" . "\r\n";
        //--当月発生額
        $strSQL .= "       ,CASE WHEN (BUSYO_CD IN ('122','080') OR (NVL(VV.KEIJO_GK,0) - ROUND(NVL(VV.HASSEI_GK,0)/3))<0)" . "\r\n";
        $strSQL .= "             THEN 0 ELSE (NVL(VV.KEIJO_GK,0)) - ROUND(NVL(VV.HASSEI_GK,0)/3) END " . "\r\n";
        $strSQL .= "       ,CASE WHEN (BUSYO_CD IN ('122','080') OR (NVL(VV.KEIJO_GK,0) - ROUND(NVL(VV.HASSEI_GK,0)/3))<0)" . "\r\n";
        $strSQL .= "             THEN 0 ELSE " . "\r\n";
        $strSQL .= "                 ROUND(ROUND((NVL(VV.KEIJO_GK,0) - ROUND(NVL(VV.HASSEI_GK,0)/3)) * TESURYO_M.NEN_RT*10 /12)/1000) END" . "\r\n";
        //strSQL.Append("       ,(NVL(VV.KEIJO_GK,0)) - ROUND(NVL(VV.HASSEI_GK,0)/3)" & vbCrLf)                                 '--当月金利対象
        //strSQL.Append("       ,ROUND(ROUND(( + NVL(VV.KEIJO_GK,0) - ROUND(NVL(VV.HASSEI_GK,0)/3)) * 39 /12)/1000)" & vbCrLf)  '--当月金利
        $strSQL .= "       ,SYSDATE" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        $strSQL .= "   FROM (SELECT V.BUSYO_CD" . "\r\n";
        $strSQL .= "               ,SUM(V.KEIJO_GK)  KEIJO_GK" . "\r\n";
        $strSQL .= "               ,SUM(V.HASSEI_GK) HASSEI_GK" . "\r\n";
        $strSQL .= "           FROM" . "\r\n";
        $strSQL .= "               (SELECT L_BUSYO_CD  BUSYO_CD" . "\r\n";
        $strSQL .= "                      ,SUM(KEIJO_GK)  KEIJO_GK" . "\r\n";
        $strSQL .= "                      ,SUM((CASE WHEN R_KAMOK_CD NOT IN ('11111','11122','11141','11142','11144','11143')" . "\r\n";
        $strSQL .= "                                 THEN KEIJO_GK ELSE 0 END )) HASSEI_GK" . "\r\n";
        $strSQL .= "                  FROM HKAIKEI" . "\r\n";
        $strSQL .= "                 WHERE L_KAMOK_CD IN ('11144','11143','11144','11143')" . "\r\n";
        $strSQL .= "                   AND KEIJO_DT >= '@KEIJYOFROM' AND KEIJO_DT <= '@KEIJYOTO'" . "\r\n";
        $strSQL .= "                 GROUP BY L_BUSYO_CD" . "\r\n";
        $strSQL .= "                 UNION ALL" . "\r\n";
        $strSQL .= "                SELECT R_BUSYO_CD BUSYO_CD" . "\r\n";
        $strSQL .= "                      ,SUM(KEIJO_GK)*-1 KEIJO_GK" . "\r\n";
        $strSQL .= "                      ,SUM((CASE WHEN L_KAMOK_CD IN ('41414','41415','41416','41417','41418','21271','11323','11344') OR (L_KAMOK_CD = '21182' AND L_KOMOK_CD = '8')" . "\r\n";
        $strSQL .= "                                 THEN KEIJO_GK ELSE 0 END )) * -1 HASSEI_GK" . "\r\n";
        $strSQL .= "                  FROM HKAIKEI" . "\r\n";
        $strSQL .= "                 WHERE R_KAMOK_CD IN ('11144','11143')" . "\r\n";
        $strSQL .= "                   AND KEIJO_DT >= '@KEIJYOFROM' AND KEIJO_DT <= '@KEIJYOTO'" . "\r\n";
        $strSQL .= "                 GROUP BY R_BUSYO_CD ) V" . "\r\n";
        $strSQL .= "          GROUP BY V.BUSYO_CD ) VV" . "\r\n";
        //'手数料マスタ
        $strSQL .= ",(SELECT TESU_M.* FROM HTESURYO TESU_M" . "\r\n";
        $strSQL .= "   INNER Join" . "\r\n";
        $strSQL .= "         (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        $strSQL .= "            FROM   HTESURYO TEST_V,HSCURI A" . "\r\n";
        $strSQL .= "           WHERE '@KEIJYOBI' >= TEST_V.KIJYUN_DT ) V" . "\r\n";
        $strSQL .= "      ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TESURYO_M" . "\r\n";
        $strSQL .= " WHERE  NOT EXISTS (SELECT BUSYO_CD FROM HKNRZAN Z" . "\r\n";
        $strSQL .= "                     WHERE Z.BUSYO_CD = VV.BUSYO_CD" . "\r\n";
        $strSQL .= "                       AND Z.KAMOK_CD IN ('11161', '11162')" . "\r\n";
        $strSQL .= "                          AND Z.KEIJO_DT = '@ZKEIJYOBI')" . "\r\n";

        $strFromDT = $strDepend . "01";

        $strTODT = date("Ymd", strtotime("-1 day", strtotime("+1 month", date("Y/m/d", $strFromDT))));
        $strSQL = str_replace("@KEIJYOBI", $strTODT, $strSQL);
        $strSQL = str_replace("@KEIJYOFROM", $strFromDT, $strSQL);
        $strSQL = str_replace("@KEIJYOTO", $strTODT, $strSQL);
        $strSQL = str_replace("@ZKEIJYOBI", strtotime("-1 day", date('Y/m/d', $strFromDT)), $strSQL);

        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;

    }

    //**********************************************************************
    //処 理 名：一般売掛金残高金利会計データ作成(SQL)
    //関 数 名：fncIPKNRKaikeiInsertSQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：当月一般売掛金残高金利会計データを作成する(SQL)
    //**********************************************************************
    function fncIPKNRKaikeiInsertSQL($strDepend, $strDenpNo, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        // $strFromDT = "";
        // $strTODT = "";

        $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";
        $strSQL .= "SELECT ''" . "\r\n";
        $strSQL .= "       ,KEIJO_DT" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,@DENPNO" . "\r\n";
        $strSQL .= "       ,ROW_NUMBER()  OVER (ORDER BY BUSYO_CD)" . "\r\n";
        $strSQL .= "       ,BUSYO_CD" . "\r\n";
        $strSQL .= "       ,DECODE(BUSYO_CD,'258','85116','85117')" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,'019'" . "\r\n";
        $strSQL .= "       ,DECODE(BUSYO_CD,'258','85116','85117')" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,KINRI_GK" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,'ZN'" . "\r\n";
        $strSQL .= "       ,NULL" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "  FROM  HKNRZAN" . "\r\n";
        $strSQL .= " WHERE  KAMOK_CD IN ('11161', '11162')" . "\r\n";
        $strSQL .= "   AND KINRI_GK > 0" . "\r\n";
        $strSQL .= "   AND SUBSTR(KEIJO_DT,1,6) = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strDepend, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDenpNo, $strSQL);

        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：ペナルティ会計データ(新車)作成(SQL)
    //関 数 名：fncPenaKaikeiInsertSQL
    //引    数：strDepend:計上日
    //　　　　：strDenpNo:伝票№
    //戻 り 値：SQL文
    //処理説明：当月ペナルティ会計データを作成する(SQL)
    //**********************************************************************
    function fncPenaKaikeiInsertSQL($strDepend, $strDenpNo, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        // $strFromDT = "";
        // $strTODT = "";

        $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";
        $strSQL .= "SELECT '122'" . "\r\n";
        $strSQL .= "       ,TO_CHAR(LAST_DAY(TO_DATE(UK.KEIJO_DT||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,UK.ID" . "\r\n";
        $strSQL .= "       ,'@DENPNO'" . "\r\n";
        $strSQL .= "       ,UK.SEQ_NO" . "\r\n";
        $strSQL .= "       ,UK.BUSYO_CD" . "\r\n";
        $strSQL .= "       ,'85114'" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,SC.UC_NO" . "\r\n";
        $strSQL .= "       ,'019'" . "\r\n";
        $strSQL .= "       ,'85114'" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,SC.UC_NO" . "\r\n";
        //2007/02/23 UPDATE START
        //strSQL.Append("       ,TRUNC(TRUNC(UK.ZAN_GK * 1000 * UK.NISU * TE.NEN_RT / 36500) / 1000)  PENALTY" & vbCrLf)
        $strSQL .= "       ,TRUNC(TRUNC(UK.ZAN_GK * 1000" . "\r\n";
        $strSQL .= "                    * (CASE WHEN UK.NISU > TO_CHAR(LAST_DAY(TO_DATE(UK.KEIJO_DT||'01')),'DD')" . "\r\n";
        $strSQL .= "                            THEN TO_NUMBER(TO_CHAR(LAST_DAY(TO_DATE(UK.KEIJO_DT||'01')),'DD'))" . "\r\n";
        $strSQL .= "                            ELSE UK.NISU END)" . "\r\n";
        $strSQL .= "                    * TE.NEN_RT / 36500) / 1000)  PENALTY" . "\r\n";
        //2007/02/23 UPDATE END
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,UK.TANTO_CD" . "\r\n";
        //担当者
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,'PN'" . "\r\n";
        $strSQL .= "       ,NULL" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "  FROM  HSCURKZAN  UK" . "\r\n";
        $strSQL .= "       ,HSCURI     SC" . "\r\n";
        $strSQL .= "       ,(SELECT TESU_M.* FROM HTESURYO TESU_M" . "\r\n";
        $strSQL .= "           INNER Join" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        $strSQL .= "                    FROM   HTESURYO TEST_V,HSCURKZAN UK" . "\r\n";
        $strSQL .= "                   WHERE UK.KEIJO_DT||'31' >= TEST_V.KIJYUN_DT ) V" . "\r\n";
        $strSQL .= "              ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TE" . "\r\n";
        $strSQL .= " WHERE  UK.CMN_NO = SC.CMN_NO(+)" . "\r\n";
        $strSQL .= "   AND  UK.NAU_KB = '1'" . "\r\n";
        $strSQL .= "   AND  UK.KEIJO_DT = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strDepend, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDenpNo, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End
        return $strSQL;
    }

    //2007/02/23 INSERT START
    //**********************************************************************
    //処 理 名：ペナルティ会計データ(中古車)作成(SQL)
    //関 数 名：fncPenaKaikeiChukoInsertSQL
    //引    数：strDepend:計上日
    //　　　　：strDenpNo:伝票№
    //戻 り 値：SQL文
    //処理説明：当月ペナルティ会計データを作成する(SQL)
    //**********************************************************************
    function fncPenaKaikeiChukoInsertSQL($strDepend, $strDenpNo, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        // $strFromDT = "";
        // $strTODT = "";

        $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "        )" . "\r\n";
        $strSQL .= "SELECT '122'" . "\r\n";
        $strSQL .= "       ,TO_CHAR(LAST_DAY(TO_DATE(UK.KEIJO_DT||'01')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,UK.ID" . "\r\n";
        $strSQL .= "       ,'@DENPNO'" . "\r\n";
        $strSQL .= "       ,UK.SEQ_NO" . "\r\n";
        $strSQL .= "       ,UK.BUSYO_CD" . "\r\n";
        $strSQL .= "       ,'85115'" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,SC.UC_NO" . "\r\n";
        $strSQL .= "       ,'019'" . "\r\n";
        $strSQL .= "       ,'85115'" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,SC.UC_NO" . "\r\n";
        //2007/02/23 UPDATE START
        //strSQL.Append("       ,TRUNC(TRUNC(UK.ZAN_GK * 1000 * UK.NISU * TE.NEN_RT / 36500) / 1000)  PENALTY" & vbCrLf)
        $strSQL .= "       ,TRUNC(TRUNC(UK.ZAN_GK * 1000" . "\r\n";
        $strSQL .= "                    * (CASE WHEN UK.NISU > TO_CHAR(LAST_DAY(TO_DATE(UK.KEIJO_DT||'01')),'DD')" . "\r\n";
        $strSQL .= "                            THEN TO_NUMBER(TO_CHAR(LAST_DAY(TO_DATE(UK.KEIJO_DT||'01')),'DD'))" . "\r\n";
        $strSQL .= "                            ELSE UK.NISU END)" . "\r\n";
        $strSQL .= "                    * TE.NEN_RT / 36500) / 1000)  PENALTY" . "\r\n";
        //2007/02/23 UPDATE END
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,UK.TANTO_CD" . "\r\n";
        //担当者
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,''" . "\r\n";
        $strSQL .= "       ,'PN'" . "\r\n";
        $strSQL .= "       ,NULL" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        $strSQL .= "       ,SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "  FROM  HSCURKZAN  UK" . "\r\n";
        $strSQL .= "       ,HSCURI     SC" . "\r\n";
        $strSQL .= "       ,(SELECT TESU_M.* FROM HTESURYO TESU_M" . "\r\n";
        $strSQL .= "           INNER Join" . "\r\n";
        $strSQL .= "                 (SELECT MAX(KIJYUN_DT) KIJYUN_DT" . "\r\n";
        $strSQL .= "                    FROM   HTESURYO TEST_V,HSCURKZAN UK" . "\r\n";
        $strSQL .= "                   WHERE UK.KEIJO_DT||'31' >= TEST_V.KIJYUN_DT ) V" . "\r\n";
        $strSQL .= "              ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TE" . "\r\n";
        $strSQL .= " WHERE  UK.CMN_NO = SC.CMN_NO(+)" . "\r\n";
        $strSQL .= "   AND  UK.NAU_KB = '2'" . "\r\n";
        $strSQL .= "   AND  UK.KEIJO_DT = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strDepend, $strSQL);
        $strSQL = str_replace("@DENPNO", $strDenpNo, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：会計データ作成(SQL)
    //関 数 名：fncKaikeiInsertSQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：R4仕訳累積より引渡された計上日範囲内のデータを抽出し、会計データを作成する(SQL)
    //**********************************************************************
    //2006/12/11 UPD 引数に更新ユーザ、更新ﾏｼﾝ、更新プログラムを追加
    //---20151102 li UPD S.
    // public function fncKaikeiInsert($strDepend1, $strDepend2, $strUpdUser, $strUpdClt, $strUpdPro, $strActmode = "K")
    public function fncKaikeiInsertSQL($strDepend1, $strDepend2, $strUpdUser, $strUpdClt, $strUpdPro, $strActmode)
    //---20151102 li UPD E.
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        //2009/12/23 UPD Start
        if ($strActmode == "S") {
            $strSQL .= " INSERT INTO HKAIKEI_S (" . "\r\n";
        } else {
            $strSQL .= " INSERT INTO HKAIKEI (" . "\r\n";
        }
        //''strSQL.Append(" INSERT INTO HKAIKEI (" & vbCrLf)
        //2009/12/23 UPD End
        $strSQL .= "        INP_BUSYO" . "\r\n";
        $strSQL .= ",       KEIJO_DT" . "\r\n";
        $strSQL .= ",       SYOHY_NO" . "\r\n";
        $strSQL .= ",       ID" . "\r\n";
        $strSQL .= ",       DENPY_NO" . "\r\n";
        $strSQL .= ",       GYO_NO" . "\r\n";
        $strSQL .= ",       L_BUSYO_CD" . "\r\n";
        $strSQL .= ",       L_KAMOK_CD" . "\r\n";
        $strSQL .= ",       L_KOMOK_CD" . "\r\n";
        $strSQL .= ",       L_HIMOK_CD" . "\r\n";
        $strSQL .= ",       L_BK" . "\r\n";
        $strSQL .= ",       L_UC_NO" . "\r\n";
        $strSQL .= ",       L_SYAIN_NO" . "\r\n";
        $strSQL .= ",       R_BUSYO_CD" . "\r\n";
        $strSQL .= ",       R_KAMOK_CD" . "\r\n";
        $strSQL .= ",       R_KOMOK_CD" . "\r\n";
        $strSQL .= ",       R_HIMOK_CD" . "\r\n";
        $strSQL .= ",       R_BK" . "\r\n";
        $strSQL .= ",       R_UC_NO" . "\r\n";
        $strSQL .= ",       R_SYAIN_NO" . "\r\n";
        $strSQL .= ",       KEIJO_GK" . "\r\n";
        $strSQL .= ",       TEKIYO1" . "\r\n";
        $strSQL .= ",       TEKIYO2" . "\r\n";
        $strSQL .= ",       TEKIYO3" . "\r\n";
        $strSQL .= ",       KAZEI_KB" . "\r\n";
        $strSQL .= ",       ZEI_RT_KB" . "\r\n";
        $strSQL .= ",       HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",       CEL_DATE" . "\r\n";
        $strSQL .= ",       UPD_DATE" . "\r\n";
        $strSQL .= ",       CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",       UPD_SYA_CD" . "\r\n";
        $strSQL .= ",       UPD_PRG_ID" . "\r\n";
        $strSQL .= ",       UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End
        //2009/12/19 INS Start
        $strSQL .= "       ,L_TORHK_KB" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY1" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY2" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY3" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY4" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY5" . "\r\n";
        $strSQL .= "       ,L_HASEI_DT" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY1_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY1_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY2_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY3_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY4_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY5_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY6_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY7_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY8_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY9_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY10_NM" . "\r\n";
        $strSQL .= "       ,R_TORHK_KB" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY1" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY2" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY3" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY4" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY5" . "\r\n";
        $strSQL .= "       ,R_HASEI_DT" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY1_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY1_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY2_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY3_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY4_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY5_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY6_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY7_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY8_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY9_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY10_NM" . "\r\n";
        //2009/12/19 INS End
        $strSQL .= "        )" . "\r\n";

        $strSQL .= "        SELECT INP_KYOTN_CD" . "\r\n";
        //作成部署
        $strSQL .= "              ,KEIJO_DT" . "\r\n";
        //経理年月日
        $strSQL .= "              ,SYOHY_NO SYOHY_NO" . "\r\n";
        //経理スタンプ№
        $strSQL .= "              ,'11'" . "\r\n";
        //区分
        $strSQL .= "              ,SIWAK_NO SIWAK_NO" . "\r\n";
        //伝票№
        $strSQL .= "              ,1" . "\r\n";
        //区分
        $strSQL .= "              ,TRIM(CASE WHEN (L_KAMOK_CD = '11344' OR (L_KAMOK_CD = '21222' AND L_KOUMK_CD = '1')) THEN '080'" . "\r\n";
        //借方部署
        $strSQL .= "               ELSE CASE WHEN L_KAMOK_CD = '43461' AND (R_KAMOK_CD = '11141' OR R_KAMOK_CD = '11142' OR R_KAMOK_CD = '11143') THEN R_KYOTN_CD" . "\r\n";
        //20060622 ADD　　科目41000～43174の場合　拠点(3:1)='9'で'019'で無い場合　拠点(3:1)='7'とする
        $strSQL .= "               ELSE CASE WHEN L_KAMOK_CD >= '41000' AND L_KAMOK_CD <= '43174' AND SUBSTR(L_KYOTN_CD,3,1) = '9' AND L_KYOTN_CD<>'019' THEN SUBSTR(L_KYOTN_CD,1,2)||'7'" . "\r\n";
        //20060222 ADD END
        //20111209 ADD    科目が'11231'の場合のみKOUZA_KEY1とする
        $strSQL .= "               ELSE CASE WHEN L_KAMOK_CD = '11231' THEN L_KOUZA_KEY1" . "\r\n";
        //20111209 ADD END
        $strSQL .= "               ELSE CASE WHEN TL.SV_KYOTEN_CD IS NOT Null THEN TL.SV_KYOTEN_CD" . "\r\n";
        $strSQL .= "               ELSE CASE WHEN KL.KYOTN_KB = '1' THEN KL.KYOTN_CD" . "\r\n";
        $strSQL .= "               ELSE  L_KYOTN_CD END END END END END END) L_KYOTN_CD" . "\r\n";
        //strSQL.Append("              ,CASE WHEN L_KAMOK_CD = '11344' THEN KR.GDMZ_CD" & vbCrLf)                                                    '借方科目
        //strSQL.Append("               ELSE KL.GDMZ_CD END L_HMKAMOK_CD" & vbCrLf)
        //2007/05/11 UPD START   N5200に渡すために科目コードを変換していたが、渡す必要がなくなったため、R4上の科目をそのまま使用する
        //strSQL.Append("              ,CASE WHEN L_KAMOK_CD = '11344' THEN KR.KAMOK_CD" & vbCrLf)                                                    '借方科目
        //strSQL.Append("               ELSE KL.KAMOK_CD END L_KAMOK_CD" & vbCrLf)
        $strSQL .= "              ,KL.KAMOK_CD L_KAMOK_CD" . "\r\n";
        //2007/05/11 UPD END
        $strSQL .= "              ,TRIM(L_KOUMK_CD)" . "\r\n";
        //借方補目
        $strSQL .= "              ,L_HIMOKU_CD" . "\r\n";
        //借方費目目
        $strSQL .= "              ,L_BK" . "\r\n";
        //借方BK
        $strSQL .= "              ,L_UC" . "\r\n";
        //借方UCNO
        $strSQL .= "              ,L_SYAIN_NO" . "\r\n";
        //摘要
        $strSQL .= "              ,TRIM(CASE WHEN (R_KAMOK_CD = '11344' OR (R_KAMOK_CD = '21222' AND R_KOUMK_CD = '1')) THEN '080'" . "\r\n";
        //借方部署
        $strSQL .= "               ELSE CASE WHEN R_KAMOK_CD = '43461' AND (L_KAMOK_CD = '11141' OR L_KAMOK_CD = '11142' OR L_KAMOK_CD = '11143') THEN L_KYOTN_CD" . "\r\n";
        //20060622 ADD　　科目41000～43174の場合　拠点(3:1)='9'で'019'で無い場合　拠点(3:1)='7'とする
        $strSQL .= "               ELSE CASE WHEN R_KAMOK_CD >= '41000' AND R_KAMOK_CD <= '43174' AND SUBSTR(R_KYOTN_CD,3,1) = '9' AND R_KYOTN_CD<>'019' THEN SUBSTR(R_KYOTN_CD,1,2)||'7'" . "\r\n";
        //20060222 ADD END
        //20111209 ADD    科目が'11231'の場合のみKOUZA_KEY1とする
        $strSQL .= "               ELSE CASE WHEN R_KAMOK_CD = '11231' THEN R_KOUZA_KEY1" . "\r\n";
        //20111209 ADD END
        $strSQL .= "               ELSE CASE WHEN TR.SV_KYOTEN_CD IS NOT Null THEN TR.SV_KYOTEN_CD" . "\r\n";
        $strSQL .= "               ELSE CASE WHEN KR.KYOTN_KB = '1' THEN KR.KYOTN_CD" . "\r\n";
        $strSQL .= "               ELSE  R_KYOTN_CD END END END END END END) R_KYOTN_CD" . "\r\n";

        //strSQL.Append("              ,CASE WHEN R_KAMOK_CD = '11344' THEN  KL.GDMZ_CD" & vbCrLf)                                             '貸方科目
        //strSQL.Append("               ELSE KR.GDMZ_CD END R_HMKAMOK_CD" & vbCrLf)
        //2007/05/11 UPD Start　　N5200に渡すために科目コードを変換していたが、渡す必要がなくなったため、R4上の科目をそのまま使用する
        //strSQL.Append("              ,CASE WHEN R_KAMOK_CD = '11344' THEN  KL.KAMOK_CD" & vbCrLf)                                             '貸方科目
        //strSQL.Append("               ELSE KR.KAMOK_CD END R_KAMOK_CD" & vbCrLf)
        $strSQL .= "              ,KR.KAMOK_CD R_KAMOK_CD" . "\r\n";
        //2007/05/11 UPD End
        $strSQL .= "              ,TRIM(R_KOUMK_CD)" . "\r\n";
        //貸方補目
        $strSQL .= "              ,R_HIMOKU_CD" . "\r\n";
        //貸方費目
        $strSQL .= "              ,R_BK" . "\r\n";
        //貸方科目
        $strSQL .= "              ,R_UC" . "\r\n";
        //貸方UCNO
        $strSQL .= "              ,R_SYAIN_NO" . "\r\n";
        //摘要
        $strSQL .= "              ,NVL(KEIJO_GK,0) KEIJO_GK" . "\r\n";
        //金額
        $strSQL .= "              ,TEKYO" . "\r\n";
        //摘要
        $strSQL .= "              ,''" . "\r\n";
        //摘要
        $strSQL .= "              ,''" . "\r\n";
        //摘要

        $strSQL .= "              ,KAZEI_KB" . "\r\n";
        //消費税区分
        $strSQL .= "              ,ZEI_RT_KB" . "\r\n";
        //消費税区分
        $strSQL .= "              ,'SW'" . "\r\n";
        //展開区分
        $strSQL .= "              ,NULL" . "\r\n";
        //展開区分
        $strSQL .= "              ,REC_UPD_DT" . "\r\n";
        //展開区分
        $strSQL .= "              ,REC_CRE_DT" . "\r\n";
        //展開区分
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",             '@UPDUSER'" . "\r\n";
        $strSQL .= ",             '@UPDAPP'" . "\r\n";
        $strSQL .= ",             '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        //2007/04/02 潘 DEL ST
        //strSQL.Append("          FROM M_KAMOKU KL" & vbCrLf)
        //strSQL.Append("              ,M_KAMOKU KR" & vbCrLf)
        //strSQL.Append("              ,M_TENPOLINK TL" & vbCrLf)
        //strSQL.Append("              ,M_TENPOLINK TR" & vbCrLf)
        //strSQL.Append("	 		   ,(SELECT '11' ID" & vbCrLf)
        //2007/04/02 潘 DEL ED

        //2009/12/19 INS Start
        $strSQL .= "       ,L_TORHK_KB" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY1" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY2" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY3" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY4" . "\r\n";
        $strSQL .= "       ,L_KOUZA_KEY5" . "\r\n";
        $strSQL .= "       ,L_HASEI_DT" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= "       ,L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY1_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "       ,L_KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY1_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY2_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY3_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY4_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY5_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY6_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY7_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY8_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY9_NM" . "\r\n";
        $strSQL .= "       ,L_HIS_TKY10_NM" . "\r\n";
        $strSQL .= "       ,R_TORHK_KB" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY1" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY2" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY3" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY4" . "\r\n";
        $strSQL .= "       ,R_KOUZA_KEY5" . "\r\n";
        $strSQL .= "       ,R_HASEI_DT" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= "       ,R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY1_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "       ,R_KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY1_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY2_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY3_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY4_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY5_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY6_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY7_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY8_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY9_NM" . "\r\n";
        $strSQL .= "       ,R_HIS_TKY10_NM" . "\r\n";
        //2009/12/19 INS End

        //2007/04/02 潘 ADD ST
        $strSQL .= "          FROM " . "\r\n";
        $strSQL .= "	 		   (SELECT '11' ID" . "\r\n";
        //2007/04/02 潘 ADD ED

        $strSQL .= "			         ,SUBSTRB(KARI.SSU_INPUT_DT,1,8) INPUT_DT" . "\r\n";
        $strSQL .= "			         ,SUBSTRB(KARI.SSU_INPUT_DT,9,4) INPUT_TIME" . "\r\n";
        $strSQL .= "			         ,KARI.SSU_INP_KYOTN_CD INP_KYOTN_CD" . "\r\n";
        $strSQL .= "			         ,KARI.KEIJO_DT KEIJO_DT" . "\r\n";
        //2007/04/14 UPDATE Start    '3月分までは証憑№が切れて出力されていたが、ｱﾝﾏｯﾁﾘｽﾄ出力のため、注文書番号で結合できるように注文書番号が全部出力されるように変更
        //strSQL.Append("			         ,SUBSTRB(KARI.SYOHY_NO,1,8) SYOHY_NO" & vbCrLf)
        $strSQL .= "			         ,SUBSTRB(KARI.SYOHY_NO,1,20) SYOHY_NO" . "\r\n";
        //2007/04/14 UPDATE End
        $strSQL .= "			         ,KARI.SIWAK_NO SIWAK_NO" . "\r\n";
        $strSQL .= "			         ,'1' KBN" . "\r\n";
        $strSQL .= "			         ,KARI.HASEI_KYOTN_CD L_KYOTN_CD" . "\r\n";
        $strSQL .= "			         ,KARI.KAMOK_CD L_KAMOK_CD" . "\r\n";
        $strSQL .= "			         ,KARI.KOUMK_CD L_KOUMK_CD" . "\r\n";
        $strSQL .= "			         ,'' L_HIMOKU_CD" . "\r\n";
        $strSQL .= "			         ,'' L_BK" . "\r\n";
        $strSQL .= "			         ,SUBSTR(KARI.KOUZA_KEY1,1,12) L_UC" . "\r\n";
        $strSQL .= "			         ,SUBSTR(KARI.HISSU_TEKYO10,1,5) L_SYAIN_NO" . "\r\n";
        $strSQL .= "			         ,KASI.HASEI_KYOTN_CD R_KYOTN_CD" . "\r\n";
        $strSQL .= "			         ,KASI.KAMOK_CD R_KAMOK_CD" . "\r\n";
        $strSQL .= "			         ,KASI.KOUMK_CD R_KOUMK_CD" . "\r\n";
        $strSQL .= "			         ,'' R_HIMOKU_CD" . "\r\n";
        $strSQL .= "			         ,'' R_BK" . "\r\n";
        $strSQL .= "			         ,SUBSTR(KASI.KOUZA_KEY1,1,12) R_UC" . "\r\n";
        $strSQL .= "			         ,SUBSTR(KASI.HISSU_TEKYO10,1,5) R_SYAIN_NO" . "\r\n";
        $strSQL .= "			         ,KARI.KEIJO_GK KEIJO_GK" . "\r\n";
        $strSQL .= "			         ,SUBSTRB(KARI.TEKYO,1,20) TEKYO" . "\r\n";
        $strSQL .= "			         ,'' SIKINGURI_CD" . "\r\n";
        //2007/03/20 潘 DEL ST
        //strSQL.Append("			         ,''" & vbCrLf)
        //2007/03/20 潘 DEL ED
        $strSQL .= "			         ,CASE WHEN (KARI.KAZEI_KB <> '9' AND NVL(KARI.ZEI_RT_KB,'0') = '0') OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB = '0') THEN '0' " . "\r\n";
        //---20151009 yin UPD S.
        //$strSQL .= "			            ELSE CASE WHEN (KARI.KAZEI_KB <> '9' AND KARI.ZEI_RT_KB = '4') OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB = '4') THEN '5' ELSE ' ' END  END KAZEI_KB" . "\r\n";
//20190731 UPDATE START
//			$strSQL .= "			            ELSE CASE WHEN (KARI.KAZEI_KB <> '9' AND KARI.ZEI_RT_KB in ('4','5','6')) OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB in ('4','5','6')) THEN '5' ELSE ' ' END  END KAZEI_KB" . "\r\n";
        $strSQL .= "			            ELSE CASE WHEN (KARI.KAZEI_KB <> '9' AND KARI.ZEI_RT_KB in ('4','5','6','7')) OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB in ('4','5','6','7')) THEN '5' ELSE ' ' END  END KAZEI_KB" . "\r\n";
        //20190731 UPDATE END
        //---20151009 yin UPD E.
        $strSQL .= "			         ,CASE WHEN NVL(KARI.ZEI_RT_KB,'0') <> '0' THEN NVL(KARI.ZEI_RT_KB,'0') ELSE CASE WHEN NVL(KASI.ZEI_RT_KB,'0') <> '0' THEN NVL(KASI.ZEI_RT_KB,'0') ELSE '0' END END ZEI_RT_KB" . "\r\n";
        $strSQL .= "			         ,''" . "\r\n";
        $strSQL .= "			         ,KARI.REC_UPD_DT" . "\r\n";
        $strSQL .= "			         ,KARI.REC_CRE_DT" . "\r\n";
        //2009/12/19 INS Start
        $strSQL .= "			         ,KARI.TORHK_KB L_TORHK_KB" . "\r\n";
        $strSQL .= "			         ,KARI.KOUZA_KEY1 L_KOUZA_KEY1" . "\r\n";
        $strSQL .= "			         ,KARI.KOUZA_KEY2 L_KOUZA_KEY2" . "\r\n";
        $strSQL .= "			         ,KARI.KOUZA_KEY3 L_KOUZA_KEY3" . "\r\n";
        $strSQL .= "			         ,KARI.KOUZA_KEY4 L_KOUZA_KEY4" . "\r\n";
        $strSQL .= "			         ,KARI.KOUZA_KEY5 L_KOUZA_KEY5" . "\r\n";
        $strSQL .= "			         ,KARI.HASEI_DT L_HASEI_DT" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO1 L_HISSU_TEKYO1" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO2 L_HISSU_TEKYO2" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO3 L_HISSU_TEKYO3" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO4 L_HISSU_TEKYO4" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO5 L_HISSU_TEKYO5" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO6 L_HISSU_TEKYO6" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO7 L_HISSU_TEKYO7" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO8 L_HISSU_TEKYO8" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO9 L_HISSU_TEKYO9" . "\r\n";
        $strSQL .= "			         ,KARI.HISSU_TEKYO10 L_HISSU_TEKYO10" . "\r\n";
        $strSQL .= "			         ,KARI.KOZ_KEY1_NM L_KOZ_KEY1_NM" . "\r\n";
        $strSQL .= " 			     ,KARI.KOZ_KEY2_NM L_KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "			         ,KARI.KOZ_KEY3_NM L_KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "			         ,KARI.KOZ_KEY4_NM L_KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "			         ,KARI.KOZ_KEY5_NM L_KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY1_NM L_HIS_TKY1_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY2_NM L_HIS_TKY2_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY3_NM L_HIS_TKY3_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY4_NM L_HIS_TKY4_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY5_NM L_HIS_TKY5_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY6_NM L_HIS_TKY6_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY7_NM L_HIS_TKY7_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY8_NM L_HIS_TKY8_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY9_NM L_HIS_TKY9_NM" . "\r\n";
        $strSQL .= "			         ,KARI.HIS_TKY10_NM L_HIS_TKY10_NM" . "\r\n";
        $strSQL .= "			         ,KASI.TORHK_KB R_TORHK_KB" . "\r\n";
        $strSQL .= "			         ,KASI.KOUZA_KEY1 R_KOUZA_KEY1" . "\r\n";
        $strSQL .= "			         ,KASI.KOUZA_KEY2 R_KOUZA_KEY2" . "\r\n";
        $strSQL .= "			         ,KASI.KOUZA_KEY3 R_KOUZA_KEY3" . "\r\n";
        $strSQL .= "			         ,KASI.KOUZA_KEY4 R_KOUZA_KEY4" . "\r\n";
        $strSQL .= "			         ,KASI.KOUZA_KEY5 R_KOUZA_KEY5" . "\r\n";
        $strSQL .= "			         ,KASI.HASEI_DT R_HASEI_DT" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO1 R_HISSU_TEKYO1" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO2 R_HISSU_TEKYO2" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO3 R_HISSU_TEKYO3" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO4 R_HISSU_TEKYO4" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO5 R_HISSU_TEKYO5" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO6 R_HISSU_TEKYO6" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO7 R_HISSU_TEKYO7" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO8 R_HISSU_TEKYO8" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO9 R_HISSU_TEKYO9" . "\r\n";
        $strSQL .= "			         ,KASI.HISSU_TEKYO10 R_HISSU_TEKYO10" . "\r\n";
        $strSQL .= "			         ,KASI.KOZ_KEY1_NM R_KOZ_KEY1_NM" . "\r\n";
        $strSQL .= " 			     ,KASI.KOZ_KEY2_NM R_KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "			         ,KASI.KOZ_KEY3_NM R_KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "			         ,KASI.KOZ_KEY4_NM R_KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "			         ,KASI.KOZ_KEY5_NM R_KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY1_NM R_HIS_TKY1_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY2_NM R_HIS_TKY2_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY3_NM R_HIS_TKY3_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY4_NM R_HIS_TKY4_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY5_NM R_HIS_TKY5_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY6_NM R_HIS_TKY6_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY7_NM R_HIS_TKY7_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY8_NM R_HIS_TKY8_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY9_NM R_HIS_TKY9_NM" . "\r\n";
        $strSQL .= "			         ,KASI.HIS_TKY10_NM R_HIS_TKY10_NM" . "\r\n";
        //2009/12/19 INS End
        $strSQL .= "		 	    FROM" . "\r\n";

        //借方発生科目
        $strSQL .= "			        (SELECT A.SSU_INPUT_DT" . "\r\n";
        $strSQL .= "			                ,A.SSU_INP_KYOTN_CD" . "\r\n";
        $strSQL .= "			                ,A.KEIJO_DT" . "\r\n";
        $strSQL .= "			                ,A.SYOHY_NO" . "\r\n";
        $strSQL .= "			                ,A.SIWAK_NO" . "\r\n";

        //2007/04/04 潘 UPD ST
        //2007/02/02 UPDATE Start    月の途中で部署が変更になり、新部署ではなく前部署でデータを発生させるために処理を追加(注文書№：443N100774)
        //strSQL.Append("			                ,A.HASEI_KYOTN_CD" & vbCrLf)
        //strSQL.Append("                         ,(CASE WHEN A.SYOHY_NO = '443N100774' THEN '441' " & vbCrLf)
        //strSQL.Append("                                WHEN A.SYOHY_NO = '441N100598' THEN '443' " & vbCrLf)    '2007/03/06 INSERT
        //strSQL.Append("                                ELSE A.HASEI_KYOTN_CD END) HASEI_KYOTN_CD" & vbCrLf)
        //注文書データ：注文書番号≠NULL AND 配属先ﾏｽﾀ：社員№≠NULL
        $strSQL .= "			                ,(CASE WHEN NOT HURIBUSYOCNV.CMN_NO IS NULL " . "\r\n";
        $strSQL .= "			                　　　THEN HURIBUSYOCNV.BUSYO_CD" . "\r\n";
        $strSQL .= "			                ELSE " . "\r\n";
        $strSQL .= "			                　　　A.HASEI_KYOTN_CD END ) AS HASEI_KYOTN_CD" . "\r\n";
        //2007/02/02 UPDATE End

        $strSQL .= "			                ,A.KAMOK_CD" . "\r\n";
        //2007/02/02 UPDATE Start    月の途中で部署が変更になり、新部署ではなく前部署でデータを発生させるために処理を追加(注文書№：443N100774)
        //strSQL.Append("			                ,A.KOUMK_CD" & vbCrLf)

        //strSQL.Append("			                ,(CASE WHEN A.SYOHY_NO = '443N100774'" & vbCrLf)
        //strSQL.Append("                                     THEN (CASE WHEN A.KAMOK_CD IN ('11141','11142') THEN '441' ELSE A.KOUMK_CD END)" & vbCrLf)
        //2007/03/06 UPDATE Start
        //strSQL.Append("                                WHEN A.SYOHY_NO = '441N100598'" & vbCrLf)
        //strSQL.Append("                                     THEN (CASE WHEN A.KAMOK_CD IN ('11141','11142') THEN '443' ELSE A.KOUMK_CD END)" & vbCrLf)
        //2007/03/06 UPDATE End
        //strSQL.Append("                                ELSE A.KOUMK_CD END) KOUMK_CD" & vbCrLf)
        //2007/02/02 UPDATE End
        $strSQL .= "			                ,(CASE WHEN NOT HURIBUSYOCNV.CMN_NO IS NULL THEN " . "\r\n";
        $strSQL .= "			                      (CASE WHEN KAMOK_CD IN ('11141','11142') THEN HURIBUSYOCNV.BUSYO_CD" . "\r\n";
        $strSQL .= "			                          ELSE A.KOUMK_CD END )" . "\r\n";
        $strSQL .= "			                      ELSE A.KOUMK_CD END ) AS KOUMK_CD" . "\r\n";

        //2007/04/04 潘 UPD ED

        $strSQL .= "			                ,A.KOUZA_KEY1" . "\r\n";
        $strSQL .= "			                ,A.KEIJO_GK" . "\r\n";
        $strSQL .= "			                ,A.TEKYO" . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO10" . "\r\n";
        $strSQL .= "			                ,A.KAZEI_KB" . "\r\n";
        $strSQL .= "			                ,A.ZEI_RT_KB" . "\r\n";
        $strSQL .= "			                ,A.UPD_DATE as REC_UPD_DT" . "\r\n";
        $strSQL .= "			                ,A.CREATE_DATE as REC_CRE_DT" . "\r\n";
        //strSQL.Append("                         ,LINE.KAMOK_CD LKAMOK_CD" & vbCrLf)
        //2009/12/19 INS Start
        $strSQL .= "			                ,A.TORHK_KB " . "\r\n";
        //'strSQL.Append("			                ,A.KOUZA_KEY1 " & vbCrLf)
        $strSQL .= "			                ,A.KOUZA_KEY2 " . "\r\n";
        $strSQL .= "			                ,A.KOUZA_KEY3 " . "\r\n";
        $strSQL .= "			                ,A.KOUZA_KEY4 " . "\r\n";
        $strSQL .= "			                ,A.KOUZA_KEY5 " . "\r\n";
        $strSQL .= "			                ,A.HASEI_DT " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO1 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO2 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO3 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO4 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO5 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO6 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO7 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO8 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO9 " . "\r\n";
        //''strSQL.Append("			                ,A.HISSU_TEKYO10 " & vbCrLf)
        $strSQL .= "			                ,A.KOZ_KEY1_NM " . "\r\n";
        $strSQL .= " 			            ,A.KOZ_KEY2_NM " . "\r\n";
        $strSQL .= "			                ,A.KOZ_KEY3_NM " . "\r\n";
        $strSQL .= "			                ,A.KOZ_KEY4_NM " . "\r\n";
        $strSQL .= "			                ,A.KOZ_KEY5_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY1_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY2_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY3_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY4_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY5_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY6_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY7_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY8_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY9_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY10_NM " . "\r\n";
        //2009/12/19 INS End
        //2007/04/04 潘 UPD ST
        //strSQL.Append("			           FROM M29F01 A" & vbCrLf)
        $strSQL .= "			               FROM " . "\r\n";

        //2009/12/23 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "			                      (WK_HKAIKEI_S A LEFT JOIN HURIBUSYOCNV ON A.SYOHY_NO = HURIBUSYOCNV.CMN_NO)" . "\r\n";
            //ﾜｰｸﾃｰﾌﾞﾙ：証憑№＝売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙ：注文書番号(+)
        } else {
            $strSQL .= "			                      (WK_HKAIKEI A LEFT JOIN HURIBUSYOCNV ON A.SYOHY_NO = HURIBUSYOCNV.CMN_NO)" . "\r\n";
            //ﾜｰｸﾃｰﾌﾞﾙ：証憑№＝売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙ：注文書番号(+)
        }
        //''strSQL.Append("			                      (WK_HKAIKEI A LEFT JOIN HURIBUSYOCNV ON A.SYOHY_NO = HURIBUSYOCNV.CMN_NO)" & vbCrLf) 'ﾜｰｸﾃｰﾌﾞﾙ：証憑№＝売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙ：注文書番号(+)
        //strSQL.Append("			                          LEFT JOIN HHAIZOKU ON HURIBUSYOCNV.HNB_TAN_EMP_NO = HHAIZOKU.SYAIN_NO" & vbCrLf)  '注文書データ：販売担当社員番号＝配属先マスタ：社員№(＋)
        //2009/12/23 UPD End

        //2007/04/04 潘 UPD ED

        //'科目ライン設定マスタ
        //strSQL.Append("			      ,(SELECT DISTINCT KAMOK_CD" & vbCrLf)
        //strSQL.Append("			           FROM HKMKLINEMST ) LINE" & vbCrLf)
        $strSQL .= "  			          WHERE A.TAISK_KB = '1'" . "\r\n";
        //2007/04/04 潘 DEL ST
        //strSQL.Append("                         AND A.KEIJO_DT >=  '@Depend1'" & vbCrLf)
        //strSQL.Append("                         AND A.KEIJO_DT <=  '@Depend2'" & vbCrLf)
        //2007/04/04 潘 UPD ED

        //strSQL.Append("				   AND A.KAMOK_CD = LINE.KAMOK_CD(+)" & vbCrLf)
        $strSQL .= "	 	        ) KARI" . "\r\n";
        //貸方発生科目
        $strSQL .= "			       ,(SELECT A.SIWAK_NO" . "\r\n";

        //2007/04/04 潘 UPD ST
        //2007/02/02 UPDATE Start    月の途中で部署が変更になり、新部署ではなく前部署でデータを発生させるために処理を追加(注文書№：443N100774)
        //strSQL.Append("			               ,A.HASEI_KYOTN_CD" & vbCrLf)
        //strSQL.Append("			               ,(CASE WHEN A.SYOHY_NO = '443N100774' THEN '441' " & vbCrLf)
        //strSQL.Append("                               WHEN A.SYOHY_NO = '441N100598' THEN '443' " & vbCrLf)     '2007/03/06 INSERT
        //strSQL.Append("                               ELSE A.HASEI_KYOTN_CD END) HASEI_KYOTN_CD" & vbCrLf)

        $strSQL .= "			                ,(CASE WHEN NOT HURIBUSYOCNV.CMN_NO IS NULL " . "\r\n";
        $strSQL .= "			                　　　THEN HURIBUSYOCNV.BUSYO_CD" . "\r\n";
        $strSQL .= "			                ELSE " . "\r\n";
        $strSQL .= "			                　　　A.HASEI_KYOTN_CD END ) AS HASEI_KYOTN_CD" . "\r\n";

        //2007/02/02 UPDATE End
        $strSQL .= "			               ,A.KAMOK_CD" . "\r\n";
        //2007/02/02 UPDATE Start    月の途中で部署が変更になり、新部署ではなく前部署でデータを発生させるために処理を追加(注文書№：443N100774)
        //strSQL.Append("			               ,A.KOUMK_CD" & vbCrLf)
        //strSQL.Append("			               ,(CASE WHEN A.SYOHY_NO = '443N100774' " & vbCrLf)
        //strSQL.Append("                                    THEN (CASE WHEN A.KAMOK_CD IN ('11141','11142') THEN '441' ELSE A.KOUMK_CD END)" & vbCrLf)
        //2007/03/06 INSERT Start
        //strSQL.Append("                               WHEN A.SYOHY_NO = '441N100598' " & vbCrLf)
        //strSQL.Append("                                    THEN (CASE WHEN A.KAMOK_CD IN ('11141','11142') THEN '443' ELSE A.KOUMK_CD END)" & vbCrLf)
        //2007/03/06 INSERT End
        //strSQL.Append("                               ELSE A.KOUMK_CD END) KOUMK_CD" & vbCrLf)
        //2007/02/02 UPDATE End
        $strSQL .= "			                ,(CASE WHEN NOT HURIBUSYOCNV.CMN_NO IS NULL THEN" . "\r\n";
        $strSQL .= "			                      (CASE WHEN KAMOK_CD IN ('11141','11142') THEN HURIBUSYOCNV.BUSYO_CD" . "\r\n";
        $strSQL .= "			                          ELSE a.KOUMK_CD END )" . "\r\n";
        $strSQL .= "			                      ELSE a.KOUMK_CD END ) AS KOUMK_CD" . "\r\n";

        //2007/04/04 潘 UPD ED
        $strSQL .= "			               ,A.KOUZA_KEY1" . "\r\n";
        $strSQL .= "			               ,A.KEIJO_GK" . "\r\n";
        $strSQL .= "			               ,A.TEKYO" . "\r\n";
        $strSQL .= "			               ,A.HISSU_TEKYO10" . "\r\n";
        $strSQL .= "			               ,A.KAZEI_KB" . "\r\n";
        $strSQL .= "			               ,A.ZEI_RT_KB" . "\r\n";
        //strSQL.Append("                        ,LINE.KAMOK_CD LKAMOK_CD" & vbCrLf)
        //2009/12/19 INS Start
        $strSQL .= "			                ,A.TORHK_KB " . "\r\n";
        $strSQL .= "			                ,A.KOUZA_KEY2 " . "\r\n";
        $strSQL .= "			                ,A.KOUZA_KEY3 " . "\r\n";
        $strSQL .= "			                ,A.KOUZA_KEY4 " . "\r\n";
        $strSQL .= "			                ,A.KOUZA_KEY5 " . "\r\n";
        $strSQL .= "			                ,A.HASEI_DT " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO1 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO2 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO3 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO4 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO5 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO6 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO7 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO8 " . "\r\n";
        $strSQL .= "			                ,A.HISSU_TEKYO9 " . "\r\n";
        $strSQL .= "			                ,A.KOZ_KEY1_NM " . "\r\n";
        $strSQL .= " 			            ,A.KOZ_KEY2_NM " . "\r\n";
        $strSQL .= "			                ,A.KOZ_KEY3_NM " . "\r\n";
        $strSQL .= "			                ,A.KOZ_KEY4_NM " . "\r\n";
        $strSQL .= "			                ,A.KOZ_KEY5_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY1_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY2_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY3_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY4_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY5_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY6_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY7_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY8_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY9_NM " . "\r\n";
        $strSQL .= "			                ,A.HIS_TKY10_NM " . "\r\n";

        //2009/12/19 INS End
        //2007/04/04 潘 UPD ST
        //strSQL.Append("			           FROM M29F01 A" & vbCrLf)
        $strSQL .= "			           FROM " . "\r\n";

        //2009/12/23 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "			                      (WK_HKAIKEI_S A LEFT JOIN HURIBUSYOCNV ON A.SYOHY_NO = HURIBUSYOCNV.CMN_NO)" . "\r\n";
            //ﾜｰｸﾃｰﾌﾞﾙ：証憑№＝売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙ：注文書番号(+)
        } else {
            $strSQL .= "			                      (WK_HKAIKEI A LEFT JOIN HURIBUSYOCNV ON A.SYOHY_NO = HURIBUSYOCNV.CMN_NO)" . "\r\n";
            //ﾜｰｸﾃｰﾌﾞﾙ：証憑№＝売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙ：注文書番号(+)
        }

        //''strSQL.Append("			                      (WK_HKAIKEI A LEFT JOIN HURIBUSYOCNV ON A.SYOHY_NO = HURIBUSYOCNV.CMN_NO)" & vbCrLf) 'ﾜｰｸﾃｰﾌﾞﾙ：証憑№＝売上ﾃﾞｰﾀ部署変換ﾃｰﾌﾞﾙ：注文書番号(+)
        //'''strSQL.Append("			                          LEFT JOIN HHAIZOKU ON M41E10.HNB_TAN_EMP_NO = HHAIZOKU.SYAIN_NO" & vbCrLf)  '注文書データ：販売担当社員番号＝配属先マスタ：社員№(＋)
        //'''2007/04/04 潘 UPD ED
        //2009/12/23 UPD End

        //'科目ライン設定マスタ
        //strSQL.Append("			      ,(SELECT DISTINCT KAMOK_CD" & vbCrLf)
        //strSQL.Append("			           FROM HKMKLINEMST ) LINE" & vbCrLf)
        $strSQL .= "			          WHERE A.TAISK_KB = '2'" . "\r\n";

        //2007/04/04 潘 DEL ST
        //strSQL.Append("                     AND A.KEIJO_DT >=  '@Depend1'" & vbCrLf)
        //strSQL.Append("                     AND A.KEIJO_DT <=  '@Depend2'" & vbCrLf)
        //2007/04/04 潘 DEL ED
        //strSQL.Append("			            AND A.KAMOK_CD = LINE.KAMOK_CD(+)" & vbCrLf)
        $strSQL .= "		  	        ) KASI" . "\r\n";

        $strSQL .= "			 WHERE KARI.SIWAK_NO = KASI.SIWAK_NO" . "\r\n";
        //strSQL.Append("               AND (KARI.LKAMOK_CD  IS NOT NULL OR KASI.LKAMOK_CD  IS NOT NULL )" & vbCrLf)
        $strSQL .= "             ) SW" . "\r\n";
        //2007/04/04 潘 DEL ST
        //借方科目変換
        //strSQL.Append("         WHERE NVL(TRIM(SW.L_KOUMK_CD),' ') = NVL(TRIM(KL.KOMOK_CD(+)),' ')" & vbCrLf)
        //strSQL.Append("           AND TRIM(SW.L_KAMOK_CD) = TRIM(KL.KAMOK_CD(+))" & vbCrLf)
        //貸方科目変換
        //strSQL.Append("           AND NVL(TRIM(SW.R_KOUMK_CD),' ') = NVL(TRIM(KR.KOMOK_CD(+)),' ')" & vbCrLf)
        //strSQL.Append("           AND TRIM(SW.R_KAMOK_CD) = TRIM(KR.KAMOK_CD(+))" & vbCrLf)
        //借方店舗リンクマスタ
        //strSQL.Append("           AND NVL(TRIM(SW.L_KYOTN_CD),' ') = NVL(TRIM(TL.JI_KYOTEN_CD(+)),' ')" & vbCrLf)
        //strSQL.Append("           AND TRIM(SW.R_KAMOK_CD) = TRIM(TL.AIT_KAMOK_CD(+))" & vbCrLf)
        //strSQL.Append("           AND NVL(TRIM(SW.L_KOUMK_CD),' ') = NVL(TRIM(TL.JI_KOMOK_CD(+)),' ')" & vbCrLf)
        //strSQL.Append("           AND TRIM(SW.L_KAMOK_CD) = TRIM(TL.JI_KAMOK_CD(+))" & vbCrLf)
        //貸方店舗リンクマスタ
        //strSQL.Append("           AND NVL(TRIM(SW.R_KYOTN_CD),' ') = NVL(TRIM(TR.JI_KYOTEN_CD(+)),' ')" & vbCrLf)
        //strSQL.Append("           AND TRIM(SW.L_KAMOK_CD) = TRIM(TR.AIT_KAMOK_CD(+))" & vbCrLf)
        //strSQL.Append("           AND NVL(TRIM(SW.R_KOUMK_CD),' ') = NVL(TRIM(TR.JI_KOMOK_CD(+)),' ')" & vbCrLf)
        //strSQL.Append("           AND TRIM(SW.R_KAMOK_CD) = TRIM(TR.JI_KAMOK_CD(+))" & vbCrLf)
        //2007/04/04 潘 DEL ED

        //2007/04/04 潘 ADD ST
        $strSQL .= "			 LEFT JOIN M_KAMOKU KL ON" . "\r\n";
        $strSQL .= "			 NVL(TRIM(SW.L_KOUMK_CD),' ')  = NVL(TRIM(KL.KOMOK_CD),' ') " . "\r\n";
        $strSQL .= "			 AND TRIM(SW.L_KAMOK_CD)  = TRIM(KL.KAMOK_CD) " . "\r\n";

        $strSQL .= "			 LEFT JOIN M_KAMOKU KR ON" . "\r\n";
        $strSQL .= "			 NVL(TRIM(SW.R_KOUMK_CD),' ') = NVL(TRIM(KR.KOMOK_CD),' ')" . "\r\n";
        $strSQL .= "			 AND TRIM(SW.R_KAMOK_CD) = TRIM(KR.KAMOK_CD)" . "\r\n";

        $strSQL .= "			 LEFT JOIN M_TENPOLINK TL ON" . "\r\n";
        $strSQL .= "			 NVL(TRIM(SW.L_KYOTN_CD),' ') = NVL(TRIM(TL.JI_KYOTEN_CD),' ')" . "\r\n";
        $strSQL .= "			 AND TRIM(SW.R_KAMOK_CD) = TRIM(TL.AIT_KAMOK_CD)" . "\r\n";
        $strSQL .= "			 AND NVL(TRIM(SW.L_KOUMK_CD),' ') = NVL(TRIM(TL.JI_KOMOK_CD),' ')" . "\r\n";
        $strSQL .= "			 AND TRIM(SW.L_KAMOK_CD) = TRIM(TL.JI_KAMOK_CD)" . "\r\n";

        $strSQL .= "			 LEFT JOIN M_TENPOLINK TR ON" . "\r\n";
        $strSQL .= "			 NVL(TRIM(SW.R_KYOTN_CD),' ') = NVL(TRIM(TR.JI_KYOTEN_CD),' ')" . "\r\n";
        $strSQL .= "			 AND TRIM(SW.L_KAMOK_CD) = TRIM(TR.AIT_KAMOK_CD)" . "\r\n";
        $strSQL .= "			 AND NVL(TRIM(SW.R_KOUMK_CD),' ') = NVL(TRIM(TR.JI_KOMOK_CD),' ')" . "\r\n";
        $strSQL .= "			 AND TRIM(SW.R_KAMOK_CD) = TRIM(TR.JI_KAMOK_CD)" . "\r\n";
        //2007/04/04 潘 ADD ED

        $strSQL .= "           ORDER BY SW.SIWAK_NO" . "\r\n";

        $strSQL = str_replace("@Depend1", $strDepend1, $strSQL);
        $strSQL = str_replace("@Depend2", $strDepend2, $strSQL);

        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：会計データチェックSQL(SQL)
    //関 数 名：fncKaikeiCHKSQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：会計データチェック(SQL)
    //**********************************************************************
    //---20151102 li UPD S.
    // public function fncKaikeiCHK($strDepend1, $strDepend2, $strActmode = "K")
    public function fncKaikeiCHKSQL($strDepend1, $strDepend2, $strActmode)
    //---20151102 li UPD E.
    {
        $strSQL = "";
        $strSQL .= "SELECT '11' ID" . "\r\n";
        $strSQL .= "      ,SUBSTRB(KARI.SSU_INPUT_DT,1,8)   INPUT_DT" . "\r\n";
        $strSQL .= "      ,SUBSTRB(KARI.SSU_INPUT_DT,9,4)   INPUT_TIME" . "\r\n";
        $strSQL .= "      ,KARI.SSU_INP_KYOTN_CD            INP_KYOTN_CD" . "\r\n";
        $strSQL .= "      ,KARI.KEIJO_DT                    KEIJO_DT" . "\r\n";
        $strSQL .= "      ,SUBSTRB(KARI.SYOHY_NO,1,8)       SYOHY_NO" . "\r\n";
        $strSQL .= "      ,KARI.SIWAK_NO                    SIWAK_NO" . "\r\n";
        $strSQL .= "      ,'1'                              KBN" . "\r\n";
        $strSQL .= "      ,KARI.HASEI_KYOTN_CD              L_KYOTN_CD" . "\r\n";
        $strSQL .= "      ,KARI.KAMOK_CD                    L_KAMOK_CD" . "\r\n";
        $strSQL .= "      ,KARI.KOUMK_CD                    L_KOUMK_CD" . "\r\n";
        $strSQL .= "      ,''                               L_HIMOKU_CD" . "\r\n";
        $strSQL .= "      ,''                               L_BK" . "\r\n";
        $strSQL .= "      ,SUBSTR(KARI.KOUZA_KEY1,1,10)     L_UC" . "\r\n";
        $strSQL .= "      ,KASI.HASEI_KYOTN_CD              R_KYOTN_CD" . "\r\n";
        $strSQL .= "      ,KASI.KAMOK_CD                    R_KAMOK_CD" . "\r\n";
        $strSQL .= "      ,KASI.KOUMK_CD                    R_KOUMK_CD" . "\r\n";
        $strSQL .= "      ,''                               R_HIMOKU_CD" . "\r\n";
        $strSQL .= "      ,''                               R_BK" . "\r\n";
        $strSQL .= "      ,SUBSTR(KASI.KOUZA_KEY1,1,10)     R_UC" . "\r\n";
        $strSQL .= "      ,KARI.KEIJO_GK                    KEIJO_GK" . "\r\n";
        $strSQL .= "      ,SUBSTRB(KARI.TEKYO,1,20)         TEKYO" . "\r\n";
        $strSQL .= "      ,''                               SIKINGURI_CD" . "\r\n";
        $strSQL .= "      ,''" . "\r\n";
        $strSQL .= "      ,CASE WHEN (KARI.KAZEI_KB <> '9' AND NVL(KARI.ZEI_RT_KB,'0') = '0') OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB = '0') THEN '0'" . "\r\n";
        //---20151009 yin UPD S.
        //$strSQL .= "      ELSE CASE WHEN (KARI.KAZEI_KB <> '9' AND KARI.ZEI_RT_KB = '4') OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB = '4') THEN '5' ELSE ' ' END  END KAZEI_KB" . "\r\n";
//20190731 UPDATE START
//			$strSQL .= "      ELSE CASE WHEN (KARI.KAZEI_KB <> '9' AND KARI.ZEI_RT_KB in ('4','5','6')) OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB in ('4','5','6')) THEN '5' ELSE ' ' END  END KAZEI_KB" . "\r\n";
        $strSQL .= "      ELSE CASE WHEN (KARI.KAZEI_KB <> '9' AND KARI.ZEI_RT_KB in ('4','5','6','7')) OR (KASI.KAZEI_KB <> '9' AND KASI.ZEI_RT_KB in ('4','5','6','7')) THEN '5' ELSE ' ' END  END KAZEI_KB" . "\r\n";
        //20190731 UPDATE END
        //---20151009 yin UPD E.
        $strSQL .= "      ,''" . "\r\n";
        //2009/12/19 DEL Start
        //''strSQL.Append("      ,KARI.REC_UPD_DT" & vbCrLf)
        //2009/12/19 DEL End
        //2007/05/11 UPD START   N5200に渡すために科目を変換していたが、廃止
        //2007/01/18 INSERT Start
        //strSQL.Append("      ,(CASE WHEN KARI.KAMOK_CD = '11344' THEN KR.KAMOK_CD" & vbCrLf)                                                    '借方科目
        //strSQL.Append("             ELSE KL.KAMOK_CD END) KL_KAMOK_CD" & vbCrLf)
        //strSQL.Append("      ,(CASE WHEN KASI.KAMOK_CD = '11344' THEN KL.KAMOK_CD" & vbCrLf)
        //strSQL.Append("             ELSE KR.KAMOK_CD END) KR_KAMOK_CD" & vbCrLf)
        $strSQL .= "      ,KL.KAMOK_CD KL_KAMOK_CD" . "\r\n";
        $strSQL .= "      ,KR.KAMOK_CD KR_KAMOK_CD" . "\r\n";
        //2007/01/18 INSERT End
        //2007/05/11 UPD END
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "      (SELECT A.SSU_INPUT_DT" . "\r\n";
        $strSQL .= "             ,A.SSU_INP_KYOTN_CD" . "\r\n";
        $strSQL .= "             ,A.KEIJO_DT" . "\r\n";
        $strSQL .= "             ,A.SYOHY_NO" . "\r\n";
        $strSQL .= "             ,A.SIWAK_NO" . "\r\n";
        $strSQL .= "             ,A.HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "             ,A.KAMOK_CD" . "\r\n";
        $strSQL .= "             ,A.KOUMK_CD" . "\r\n";
        $strSQL .= "             ,A.KOUZA_KEY1" . "\r\n";
        $strSQL .= "             ,A.KEIJO_GK" . "\r\n";
        $strSQL .= "             ,A.TEKYO" . "\r\n";
        $strSQL .= "             ,A.KAZEI_KB" . "\r\n";
        $strSQL .= "             ,A.ZEI_RT_KB" . "\r\n";
        //2009/12/19 DEL Start
        //''strSQL.Append("             ,A.REC_UPD_DT" & vbCrLf)
        //2009/12/19 DEL End
        //strSQL.Append("             ,LINE.KAMOK_CD LKAMOK_CD" & vbCrLf)            '2007/01/18 DELETE
        //2009/12/19 UPD Start
        //''strSQL.Append("         FROM M29F01 A" & vbCrLf)
        //2009/12/23 UPD Start
        //''strSQL.Append("         FROM WK_HKAIKEI A" & vbCrLf)
        if ($strActmode == "S") {
            $strSQL .= "         FROM WK_HKAIKEI_S A" . "\r\n";
        } else {
            $strSQL .= "         FROM WK_HKAIKEI A" . "\r\n";
        }
        //2009/12/23 UPD End
        //2009/12/19 UPD End
        //strSQL.Append("            ,(SELECT DISTINCT KAMOK_CD" & vbCrLf)           '2007/01/18 DELETE
        //strSQL.Append("                FROM HKMKLINEMST ) LINE" & vbCrLf)          '2007/01/18 DELETE
        $strSQL .= "        WHERE A.TAISK_KB = '1'" . "\r\n";
        //2009/12/19 DEL Start
        //''strSQL.Append("          AND A.KEIJO_DT >=  '@Depend1'" & vbCrLf)
        //''strSQL.Append("          AND A.KEIJO_DT <=  '@Depend2'" & vbCrLf)
        //2009/12/19 DEL End
        //strSQL.Append("          AND A.KAMOK_CD = LINE.KAMOK_CD(+)" & vbCrLf)      '2007/01/18 DELETE
        $strSQL .= "              ) KARI" . "\r\n";
        $strSQL .= "     ,(SELECT A.SIWAK_NO" . "\r\n";
        $strSQL .= "             ,A.HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "             ,A.KAMOK_CD" . "\r\n";
        $strSQL .= "             ,A.KOUMK_CD" . "\r\n";
        $strSQL .= "             ,A.KOUZA_KEY1" . "\r\n";
        $strSQL .= "             ,A.KEIJO_GK" . "\r\n";
        $strSQL .= "             ,A.TEKYO" . "\r\n";
        $strSQL .= "             ,A.KAZEI_KB" . "\r\n";
        $strSQL .= "             ,A.ZEI_RT_KB" . "\r\n";
        //strSQL.Append("             ,LINE.KAMOK_CD LKAMOK_CD" & vbCrLf)            '2007/01/18 DELETE
        //2009/12/19 UPD Start
        //''strSQL.Append("         FROM M29F01 A" & vbCrLf)
        //2009/12/23 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "         FROM WK_HKAIKEI_S A" . "\r\n";
        } else {
            $strSQL .= "         FROM WK_HKAIKEI A" . "\r\n";
        }
        //2009/12/23 UPD End
        //2009/12/19 UPD End
        //strSQL.Append("            ,(SELECT DISTINCT KAMOK_CD" & vbCrLf)           '2007/01/18 DELETE
        //strSQL.Append("                FROM HKMKLINEMST ) LINE" & vbCrLf)          '2007/01/18 DELETE
        $strSQL .= "        WHERE A.TAISK_KB = '2'" . "\r\n";
        //2009/12/19 DEL Start
        //''strSQL.Append("          AND A.KEIJO_DT >=  '@Depend1'" & vbCrLf)
        //''strSQL.Append("          AND A.KEIJO_DT <=  '@Depend2'" & vbCrLf)
        //2009/12/19 DEL End
        //strSQL.Append("          AND A.KAMOK_CD = LINE.KAMOK_CD(+)" & vbCrLf)      '2007/01/18 DELETE
        $strSQL .= "              ) KASI" . "\r\n";
        $strSQL .= "     ,M_KAMOKU KL" . "\r\n";
        $strSQL .= "     ,M_KAMOKU KR" . "\r\n";
        //借方科目変換
        $strSQL .= " WHERE NVL(TRIM(KARI.KOUMK_CD),' ') = NVL(TRIM(KL.KOMOK_CD(+)),' ')" . "\r\n";
        $strSQL .= "   AND TRIM(KARI.KAMOK_CD) = TRIM(KL.KAMOK_CD(+))" . "\r\n";
        //貸方科目変換
        $strSQL .= "   AND NVL(TRIM(KASI.KOUMK_CD),' ') = NVL(TRIM(KR.KOMOK_CD(+)),' ')" . "\r\n";
        $strSQL .= "  AND TRIM(KASI.KAMOK_CD) = TRIM(KR.KAMOK_CD(+))" . "\r\n";
        $strSQL .= "   AND KARI.SIWAK_NO = KASI.SIWAK_NO" . "\r\n";
        //strSQL.Append("   AND (KARI.LKAMOK_CD  IS NOT NULL OR KASI.LKAMOK_CD  IS NOT NULL )" & vbCrLf)     '2007/01/18 DELETE
        //2007/01/27 UPDATE Start
        //strSQL.Append("   AND (KL.KAMOK_CD IS  NULL OR KR.KAMOK_CD  IS  NULL )" & vbCrLf)
        //2007/05/11 UPD START
        //strSQL.Append("  AND ((CASE WHEN KARI.KAMOK_CD = '11344' THEN KR.KAMOK_CD" & vbCrLf)
        //strSQL.Append("              ELSE KL.KAMOK_CD END) IS NULL" & vbCrLf)
        //strSQL.Append("         OR " & vbCrLf)
        //strSQL.Append("        (CASE WHEN KASI.KAMOK_CD = '11344' THEN KL.KAMOK_CD" & vbCrLf)
        //strSQL.Append("              ELSE KR.KAMOK_CD END) IS NULL" & vbCrLf)
        //strSQL.Append("       )" & vbCrLf)
        $strSQL .= " AND (KL.KAMOK_CD IS NULL" . "\r\n";
        $strSQL .= "         OR " . "\r\n";
        $strSQL .= "       KR.KAMOK_CD IS NULL" . "\r\n";
        $strSQL .= "       )" . "\r\n";
        //2007/05/11 UPD END
        //2007/01/27 UPDATE End

        $strSQL = str_replace("@Depend1", $strDepend1, $strSQL);
        $strSQL = str_replace("@Depend2", $strDepend2, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：ﾜｰｸﾃｰﾌﾞﾙを全件削除
    //関 数 名：fncKaikeiWKDelete
    //引    数：なし
    //戻 り 値：SQL文
    //処理説明：ﾜｰｸﾃｰﾌﾞﾙを全件削除
    //**********************************************************************
    //---20151102 li UPD S.
    // public function fncKaikeiWKDelete($strActmode = "K")
    public function fncKaikeiWKDeleteSQL($strActmode)
    //---20151102 li UPD E.
    {
        $strSQL = "";
        //''strSQL.Append("DELETE " & vbCrLf)
        //''strSQL.Append("  FROM WK_HKAIKEI" & vbCrLf)
        //---20151102 li UPD S.
        // $strSQL .= "TRUNCATE TABLE " . "\r\n";
        $strSQL .= "DELETE FROM " . "\r\n";
        //---20151102 li UPD E.
        if ($strActmode == "S") {
            $strSQL .= "   WK_HKAIKEI_S" . "\r\n";
        } else {
            $strSQL .= "   WK_HKAIKEI" . "\r\n";
        }
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：ﾜｰｸﾃｰﾌﾞﾙ作成(SQL)
    //関 数 名：fncKaikeiWKInsertSQL
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：仕訳累積ﾌｧｲﾙより引渡された計上日範囲内のデータを抽出し、ﾜｰｸﾃｰﾌﾞﾙを作成する(SQL)
    //**********************************************************************
    //---20151102 li UPD S.
    // public function fncKaikeiWKInsert($strDepend1, $strDepend2, $strActmode = "K")
    public function fncKaikeiWKInsertSQL($strDepend1, $strDepend2, $strActmode)
    //---20151102 li UPD E.
    {
        $strSQL = "";

        //2009/12/23 UPD Start
        if ($strActmode == "S") {
            $strSQL .= "INSERT INTO  WK_HKAIKEI_S(" . "\r\n";
        } else {
            $strSQL .= "INSERT INTO  WK_HKAIKEI(" . "\r\n";
        }
        //''strSQL.Append("INSERT INTO  WK_HKAIKEI(" & vbCrLf)
        //2009/12/23 UPD End

        $strSQL .= "       SIWAK_NO	 " . "\r\n";
        $strSQL .= "       ,TAISK_KB	 " . "\r\n";
        $strSQL .= "       ,KEIJO_DT	 " . "\r\n";
        $strSQL .= "       ,SYOHY_NO	 " . "\r\n";
        $strSQL .= "       ,SSU_INPUT_DT	 " . "\r\n";
        $strSQL .= "       ,SSU_INP_KYOTN_CD" . "\r\n";
        $strSQL .= "       ,HASEI_KYOTN_CD	 " . "\r\n";
        $strSQL .= "       ,KAMOK_CD	 " . "\r\n";
        $strSQL .= "       ,KOUMK_CD	 " . "\r\n";
        $strSQL .= "       ,KOUZA_KEY1	 " . "\r\n";
        $strSQL .= "       ,KEIJO_GK	 " . "\r\n";
        $strSQL .= "       ,TEKYO	         " . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO10	 " . "\r\n";
        $strSQL .= "       ,KAZEI_KB	 " . "\r\n";
        $strSQL .= "       ,ZEI_RT_KB	 " . "\r\n";
        $strSQL .= "       ,UPD_DATE	 " . "\r\n";
        //2009/12/19 UPD STart
        //''strSQL.Append("       ,CREATE_DATE)" & vbCrLf)
        $strSQL .= "       ,CREATE_DATE" . "\r\n";
        //2009/12/19 UPD End
        //2009/12/19 INS Start
        $strSQL .= "       ,TORHK_KB" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY2" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY3" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY4" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY5" . "\r\n";
        $strSQL .= "       ,HASEI_DT" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO1" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO2" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO3" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO4" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO5" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO6" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO7" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO8" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO9" . "\r\n";
        $strSQL .= "       ,KOZ_KEY1_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY1_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY2_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY3_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY4_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY5_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY6_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY7_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY8_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY9_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY10_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        //2009/12/19 INS End
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "       SIWAK_NO	 " . "\r\n";
        $strSQL .= "       ,TAISK_KB	 " . "\r\n";
        $strSQL .= "       ,KEIJO_DT	 " . "\r\n";
        $strSQL .= "       ,SYOHY_NO	 " . "\r\n";
        $strSQL .= "       ,SSU_INPUT_DT	 " . "\r\n";
        $strSQL .= "       ,SSU_INP_KYOTN_CD" . "\r\n";
        $strSQL .= "       ,HASEI_KYOTN_CD	 " . "\r\n";
        $strSQL .= "       ,KAMOK_CD	 " . "\r\n";
        $strSQL .= "       ,KOUMK_CD	 " . "\r\n";
        $strSQL .= "       ,KOUZA_KEY1	 " . "\r\n";
        $strSQL .= "       ,KEIJO_GK	 " . "\r\n";
        $strSQL .= "       ,TEKYO	         " . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO10	 " . "\r\n";
        $strSQL .= "       ,KAZEI_KB	 " . "\r\n";
        $strSQL .= "       ,ZEI_RT_KB	 " . "\r\n";
        $strSQL .= "       ,REC_UPD_DT" . "\r\n";
        $strSQL .= "       ,REC_CRE_DT" . "\r\n";
        //2009/12/19 INS Start
        $strSQL .= "       ,TORHK_KB" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY2" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY3" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY4" . "\r\n";
        $strSQL .= "       ,KOUZA_KEY5" . "\r\n";
        $strSQL .= "       ,HASEI_DT" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO1" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO2" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO3" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO4" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO5" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO6" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO7" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO8" . "\r\n";
        $strSQL .= "       ,HISSU_TEKYO9" . "\r\n";
        $strSQL .= "       ,KOZ_KEY1_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY2_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY3_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY4_NM" . "\r\n";
        $strSQL .= "       ,KOZ_KEY5_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY1_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY2_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY3_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY4_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY5_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY6_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY7_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY8_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY9_NM" . "\r\n";
        $strSQL .= "       ,HIS_TKY10_NM" . "\r\n";
        //2009/12/19 INS End
        $strSQL .= "    FROM " . "\r\n";
        $strSQL .= "       M29F01" . "\r\n";
        $strSQL .= "    WHERE" . "\r\n";
        $strSQL .= "        KEIJO_DT>='@Depend1'" . "\r\n";
        $strSQL .= "        AND KEIJO_DT<='@Depend2'" . "\r\n";

        $strSQL = str_replace("@Depend1", $strDepend1, $strSQL);
        $strSQL = str_replace("@Depend2", $strDepend2, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：部署ｺｰﾄｱﾝﾏｯﾁﾘｽﾄ出力SQL)
    //関 数 名：fncGetAnmattiDate
    //引    数：なし
    //戻 り 値：SQL文
    //処理説明：部署ｺｰﾄｱﾝﾏｯﾁﾘｽﾄ出力(SQL)
    //**********************************************************************
    //---20151102 li UPD S.
    // public function fncGetAnmattiData($strStartDt, $strEndDt, $strActmode = "K")
    public function fncGetAnmattiDataSQL($strStartDt, $strEndDt, $strActmode)
    //---20151102 li UPD E.
    {
        $strSQL = "";

        //2007/04/20 UPD START
        $strSQL .= "SELECT V.SYOHY_NO CMN_NO, V.HNB_TAN_EMP_NO, V.ERR_MSG, SYA.SYAIN_NM ,V.HK_BUSYO_CD, V.HH_BUSYO_CD" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "		SELECT '配属先マスタと部署が不一致' ERR_MSG" . "\r\n";
        $strSQL .= "        ,      ('会計部署＝' || KAI.L_BUSYO_CD) HK_BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      ('配属部署＝' || HAI.BUSYO_CD) HH_BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "        ,      KAI.SYOHY_NO" . "\r\n";
        $strSQL .= "        ,      CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= "        ,      KAI.KEIJO_DT" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "		FROM   HKAIKEI_S KAI" . "\r\n";
        } else {
            $strSQL .= "		FROM   HKAIKEI KAI" . "\r\n";
        }
        if ($strActmode == "S") {
            $strSQL .= "		INNER JOIN WK_HKAIKEI_S WK" . "\r\n";
        } else {
            $strSQL .= "		INNER JOIN WK_HKAIKEI WK" . "\r\n";
        }
        $strSQL .= "		ON     WK.SIWAK_NO = KAI.DENPY_NO" . "\r\n";
        $strSQL .= "　　　　AND    WK.HASEI_KYOTN_CD = KAI.L_BUSYO_CD" . "\r\n";
        $strSQL .= "		AND    WK.TAISK_KB = '1'" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       M41E10 CMN" . "\r\n";
        $strSQL .= "		ON     KAI.SYOHY_NO = CMN.CMN_NO" . "\r\n";
        $strSQL .= "     	LEFT  JOIN" . "\r\n";
        $strSQL .= "		       HHAIZOKU HAI" . "\r\n";
        $strSQL .= "		ON     HAI.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "        AND    HAI.START_DATE <= KAI.KEIJO_DT" . "\r\n";
        $strSQL .= "        AND    NVL(HAI.END_DATE,'99999999') >= KAI.KEIJO_DT" . "\r\n";
        $strSQL .= "		LEFT  JOIN" . "\r\n";
        $strSQL .= "               HBUSYO BUS" . "\r\n";
        $strSQL .= "        ON     BUS.BUSYO_CD = CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= "		WHERE  ((CASE WHEN BUS.CNV_BUSYO_CD IS NOT NULL THEN BUS.CNV_BUSYO_CD ELSE NVL(CMN.HNB_KTN_CD,'A') END) <> NVL(HAI.BUSYO_CD,'A') OR HAI.BUSYO_CD IS NULL)--AND WK.HASEI_KYOTN_CD <> HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "		AND    KAI.KEIJO_DT >= '@STARTBI'" . "\r\n";
        $strSQL .= "		AND    KAI.KEIJO_DT <= '@ENDBI'" . "\r\n";
        $strSQL .= "		UNION ALL" . "\r\n";
        $strSQL .= "		SELECT '配属先マスタと部署が不一致'" . "\r\n";
        $strSQL .= "        ,      '会計部署＝' || KAI.R_BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      '配属部署＝' || HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "        ,      KAI.SYOHY_NO" . "\r\n";
        $strSQL .= "        ,      CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= "        ,      KAI.KEIJO_DT" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "		FROM   HKAIKEI_S KAI" . "\r\n";
        } else {
            $strSQL .= "		FROM   HKAIKEI KAI" . "\r\n";
        }
        if ($strActmode == "S") {
            $strSQL .= "		INNER JOIN WK_HKAIKEI_S WK" . "\r\n";
        } else {
            $strSQL .= "		INNER JOIN WK_HKAIKEI WK" . "\r\n";
        }
        //''strSQL.Append("		FROM   HKAIKEI KAI" & vbCrLf)
        //''strSQL.Append("		INNER JOIN WK_HKAIKEI WK" & vbCrLf)
        $strSQL .= "		ON     WK.SIWAK_NO = KAI.DENPY_NO" . "\r\n";
        $strSQL .= "        AND    WK.HASEI_KYOTN_CD = KAI.R_BUSYO_CD" . "\r\n";
        $strSQL .= "		AND    WK.TAISK_KB = '2'" . "\r\n";
        $strSQL .= "		INNER JOIN" . "\r\n";
        $strSQL .= "		       M41E10 CMN" . "\r\n";
        $strSQL .= "		ON     KAI.SYOHY_NO = CMN.CMN_NO" . "\r\n";
        $strSQL .= "        LEFT  JOIN" . "\r\n";
        $strSQL .= "		       HHAIZOKU HAI" . "\r\n";
        $strSQL .= "		ON     HAI.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "        AND    HAI.START_DATE <= KAI.KEIJO_DT" . "\r\n";
        $strSQL .= "        AND    NVL(HAI.END_DATE,'99999999') >= KAI.KEIJO_DT" . "\r\n";
        $strSQL .= "     	LEFT  JOIN" . "\r\n";
        $strSQL .= "               HBUSYO BUS" . "\r\n";
        $strSQL .= "        ON     BUS.BUSYO_CD = CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= "        WHERE  ((CASE WHEN BUS.CNV_BUSYO_CD IS NOT NULL THEN BUS.CNV_BUSYO_CD ELSE NVL(CMN.HNB_KTN_CD,'A') END) <> NVL(HAI.BUSYO_CD,'A') OR HAI.BUSYO_CD IS NULL) --AND WK.HASEI_KYOTN_CD <> HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "		AND    KAI.KEIJO_DT >= '@STARTBI'" . "\r\n";
        $strSQL .= "		AND    KAI.KEIJO_DT <= '@ENDBI'" . "\r\n";
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "        SELECT '売上部署変換マスタにより部署変換'" . "\r\n";
        $strSQL .= "        ,      '発生拠点＝' || WK.HASEI_KYOTN_CD" . "\r\n";
        $strSQL .= "        ,      '変換部署＝' || CNV.BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      CMN.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "　　　　,      WK.SYOHY_NO" . "\r\n";
        $strSQL .= "        ,      CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= "        ,      WK.KEIJO_DT" . "\r\n";
        if ($strActmode == "S") {
            $strSQL .= "        FROM   WK_HKAIKEI_S WK" . "\r\n";
        } else {
            $strSQL .= "        FROM   WK_HKAIKEI WK" . "\r\n";
        }
        $strSQL .= "        INNER JOIN M41E10 CMN" . "\r\n";
        $strSQL .= "        ON     CMN.CMN_NO = WK.SYOHY_NO" . "\r\n";
        $strSQL .= "        INNER JOIN" . "\r\n";
        $strSQL .= "               HURIBUSYOCNV CNV" . "\r\n";
        $strSQL .= "        ON     CNV.CMN_NO = WK.SYOHY_NO" . "\r\n";
        $strSQL .= "        ) V" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON   SYA.SYAIN_NO = V.HNB_TAN_EMP_NO" . "\r\n";
        $strSQL .= "GROUP BY V.SYOHY_NO , V.HNB_TAN_EMP_NO, V.ERR_MSG, SYA.SYAIN_NM ,V.HK_BUSYO_CD, V.HH_BUSYO_CD" . "\r\n";

        $strSQL = str_replace("@STARTBI", $strStartDt, $strSQL);
        $strSQL = str_replace("@ENDBI", $strEndDt, $strSQL);

        //'2007/04/17 UPD Start

        //strSQL.Append("SELECT V.SYOHY_NO CMN_NO, V.HNB_TAN_EMP_NO, SYA.SYAIN_NM ,V.L_BUSYO_CD HK_BUSYO_CD, V.BUSYO_CD HH_BUSYO_CD, V.HNB_KTN_CD" & vbCrLf)
        //strSQL.Append("FROM   (" & vbCrLf)
        //strSQL.Append("		SELECT KAI.L_BUSYO_CD, HAI.BUSYO_CD, CMN.HNB_TAN_EMP_NO, KAI.SYOHY_NO, CMN.HNB_KTN_CD, KAI.KEIJO_DT" & vbCrLf)
        //strSQL.Append("		FROM   HKAIKEI KAI" & vbCrLf)
        //strSQL.Append("		INNER JOIN WK_HKAIKEI WK" & vbCrLf)
        //strSQL.Append("		ON     WK.SIWAK_NO = KAI.DENPY_NO" & vbCrLf)
        //strSQL.Append("　　　　AND    WK.HASEI_KYOTN_CD = KAI.L_BUSYO_CD" & vbCrLf)
        //strSQL.Append("		AND    WK.TAISK_KB = '1'" & vbCrLf)
        //strSQL.Append("		INNER JOIN" & vbCrLf)
        //strSQL.Append("		       M41E10 CMN" & vbCrLf)
        //strSQL.Append("		ON     KAI.SYOHY_NO = CMN.CMN_NO" & vbCrLf)
        //'2007/04/18 UPDATE Start
        //'strSQL.Append("		INNER JOIN" & vbCrLf)
        //strSQL.Append("     LEFT  JOIN" & vbCrLf)
        //'2007/04/18 UPDATE End
        //strSQL.Append("		       HHAIZOKU HAI" & vbCrLf)
        //strSQL.Append("		ON     HAI.SYAIN_NO = CMN.HNB_TAN_EMP_NO" & vbCrLf)
        //strSQL.Append("        AND    HAI.START_DATE <= KAI.KEIJO_DT" & vbCrLf)
        //strSQL.Append("        AND    NVL(HAI.END_DATE,'99999999') >= KAI.KEIJO_DT" & vbCrLf)
        //strSQL.Append("		WHERE  (NVL(CMN.HNB_KTN_CD,'A') <> NVL(HAI.BUSYO_CD,'A') OR HAI.BUSYO_CD IS NULL)--AND WK.HASEI_KYOTN_CD <> HAI.BUSYO_CD" & vbCrLf)
        //strSQL.Append("		AND    KAI.KEIJO_DT >= '@STARTBI'" & vbCrLf)
        //strSQL.Append("		AND    KAI.KEIJO_DT <= '@ENDBI'" & vbCrLf)
        //strSQL.Append("		UNION ALL" & vbCrLf)
        //strSQL.Append("		SELECT KAI.R_BUSYO_CD, HAI.BUSYO_CD, CMN.HNB_TAN_EMP_NO, KAI.SYOHY_NO, CMN.HNB_KTN_CD, KAI.KEIJO_DT" & vbCrLf)
        //strSQL.Append("		FROM   HKAIKEI KAI" & vbCrLf)
        //strSQL.Append("		INNER JOIN WK_HKAIKEI WK" & vbCrLf)
        //strSQL.Append("		ON     WK.SIWAK_NO = KAI.DENPY_NO" & vbCrLf)
        //strSQL.Append("        AND    WK.HASEI_KYOTN_CD = KAI.R_BUSYO_CD" & vbCrLf)
        //strSQL.Append("		AND    WK.TAISK_KB = '2'" & vbCrLf)
        //strSQL.Append("		INNER JOIN" & vbCrLf)
        //strSQL.Append("		       M41E10 CMN" & vbCrLf)
        //strSQL.Append("		ON     KAI.SYOHY_NO = CMN.CMN_NO" & vbCrLf)
        //'2007/04/18 UPDATE Start
        //'strSQL.Append("		INNER JOIN" & vbCrLf)
        //strSQL.Append("     LEFT  JOIN" & vbCrLf)
        //'2007/04/18 UPDATE End
        //strSQL.Append("		       HHAIZOKU HAI" & vbCrLf)
        //strSQL.Append("		ON     HAI.SYAIN_NO = CMN.HNB_TAN_EMP_NO" & vbCrLf)
        //strSQL.Append("        AND    HAI.START_DATE <= KAI.KEIJO_DT" & vbCrLf)
        //strSQL.Append("        AND    NVL(HAI.END_DATE,'99999999') >= KAI.KEIJO_DT" & vbCrLf)
        //strSQL.Append("		WHERE  (NVL(CMN.HNB_KTN_CD,'A') <> NVL(HAI.BUSYO_CD,'A') OR HAI.BUSYO_CD IS NULL) --AND WK.HASEI_KYOTN_CD <> HAI.BUSYO_CD" & vbCrLf)
        //strSQL.Append("		AND    KAI.KEIJO_DT >= '@STARTBI'" & vbCrLf)
        //strSQL.Append("		AND    KAI.KEIJO_DT <= '@ENDBI'" & vbCrLf)
        //strSQL.Append(") V" & vbCrLf)
        //strSQL.Append("LEFT JOIN HSYAINMST SYA" & vbCrLf)
        //strSQL.Append("ON   SYA.SYAIN_NO = V.HNB_TAN_EMP_NO" & vbCrLf)
        //strSQL.Append("GROUP BY V.SYOHY_NO, V.HNB_TAN_EMP_NO, SYA.SYAIN_NM, V.L_BUSYO_CD, V.BUSYO_CD ,V.HNB_KTN_CD" & vbCrLf)

        //strSQL.Replace("@STARTBI", strStartDt)
        //strSQL.Replace("@ENDBI", strEndDt)

        //'strSQL.Append("SELECT " & vbLf)
        //''2007/04/11 潘 DEL ST
        //''strSQL.Append("      SIWAK_NO, " & vbLf)
        //''strSQL.Append("        TAISK_KB, " & vbLf)
        //''2007/04/11 潘 DEL ED
        //'strSQL.Append("        CMN_NO, " & vbLf)
        //'strSQL.Append("        HNB_TAN_EMP_NO, " & vbLf)
        //'strSQL.Append("        SYAIN_NM, " & vbLf)
        //'strSQL.Append("       HK_BUSYO_CD, " & vbLf)
        //'strSQL.Append("       HH_BUSYO_CD" & vbLf)
        //'strSQL.Append("  FROM " & vbLf)
        //'strSQL.Append("  (  " & vbLf)
        //'strSQL.Append("      SELECT " & vbLf)
        //'strSQL.Append("            A.SYOHY_NO," & vbLf)
        //'strSQL.Append("            A.HK_BUSYO_CD," & vbLf)
        //'strSQL.Append("            A.SYOHY_NO,    " & vbLf)
        //''strSQL.Append("            A.SIWAK_NO,    " & vbLf)'2007/04/11 潘 DEL
        //'strSQL.Append("            A.KEIJO_DT,    " & vbLf)
        //''strSQL.Append("            A.TAISK_KB,  " & vbLf)'2007/04/11 潘 DEL
        //'strSQL.Append("            CMN_NO, " & vbLf)
        //'strSQL.Append("            HNB_TAN_EMP_NO, " & vbLf)
        //'strSQL.Append("            SYAIN_NM," & vbLf)
        //'strSQL.Append("            (CASE" & vbLf)
        //'strSQL.Append("                WHEN HHAIZOKU.BUSYO_CD IS NULL" & vbLf)
        //'strSQL.Append("                   THEN '配属先ﾏｽﾀに設定されていません。'" & vbLf)
        //'strSQL.Append("                ELSE HHAIZOKU.BUSYO_CD" & vbLf)
        //'strSQL.Append("             END) AS HH_BUSYO_CD" & vbLf)
        //'strSQL.Append("          FROM " & vbLf)
        //'strSQL.Append("          " & vbLf)
        //'strSQL.Append("          (SELECT * FROM (SELECT SYOHY_NO," & vbLf)
        //'strSQL.Append("                  (CASE" & vbLf)
        //'strSQL.Append("                   WHEN NOT HURIBUSYOCNV.BUSYO_CD IS NULL" & vbLf)
        //'strSQL.Append("                      THEN HURIBUSYOCNV.BUSYO_CD" & vbLf)
        //'strSQL.Append("                   ELSE HASEI_KYOTN_CD" & vbLf)
        //'strSQL.Append("                      END" & vbLf)
        //'strSQL.Append("                   ) AS HK_BUSYO_CD,                       " & vbLf)

        //'' strSQL.Append("                   SIWAK_NO, " & vbLf)'2007/04/11 潘 DEL
        //'strSQL.Append("                   KEIJO_DT " & vbLf)
        //''strSQL.Append("                   TAISK_KB" & vbLf)'2007/04/11 潘 DEL

        //'strSQL.Append("              FROM " & vbLf)
        //'strSQL.Append("                  WK_HKAIKEI LEFT JOIN HURIBUSYOCNV ON" & vbLf)
        //'strSQL.Append("                  WK_HKAIKEI.SYOHY_NO=HURIBUSYOCNV.CMN_NO) A" & vbLf)
        //'strSQL.Append("                  INNER JOIN M41E10 ON A.SYOHY_NO = M41E10.CMN_NO) A" & vbLf)
        //'strSQL.Append("                            " & vbLf)
        //'strSQL.Append("                            LEFT JOIN HHAIZOKU ON" & vbLf)
        //'strSQL.Append("                            A.HNB_TAN_EMP_NO=HHAIZOKU.SYAIN_NO" & vbLf)
        //'strSQL.Append("                            AND HHAIZOKU.START_DATE<=A.KEIJO_DT" & vbLf)
        //'strSQL.Append("                            AND NVL(HHAIZOKU.END_DATE,'99999999')>=A.KEIJO_DT" & vbLf)
        //'strSQL.Append("                            LEFT JOIN HSYAINMST ON" & vbLf)
        //'strSQL.Append("                            A.HNB_TAN_EMP_NO=HSYAINMST.SYAIN_NO" & vbLf)
        //'strSQL.Append("                 WHERE" & vbLf)
        //'strSQL.Append("                 HHAIZOKU.BUSYO_CD<>A.HK_BUSYO_CD OR HHAIZOKU.BUSYO_CD IS NULL" & vbLf)
        //'strSQL.Append("                 )" & vbLf)
        //'strSQL.Append("                 group by" & vbLf)
        //'strSQL.Append("                 CMN_NO, " & vbLf)
        //'strSQL.Append("                 HNB_TAN_EMP_NO, " & vbLf)
        //'strSQL.Append("                 SYAIN_NM, " & vbLf)
        //'strSQL.Append("                 HK_BUSYO_CD, " & vbLf)
        //'strSQL.Append("                 HH_BUSYO_CD" & vbLf)
        //'2007/04/17 UPDATE End
        //2007/04/20 UPDATE END

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：セールス実績表作成(SQL)
    //関 数 名：fncSGENJInsert
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：ｾｰﾙｽ実績ﾃﾞｰﾀより引渡された計上日範囲内のデータを抽出し、セールス実績表ﾃﾞｰﾀを作成する(SQL)
    //**********************************************************************
    public function fncSGENJInsertSql($strDepend1, $strDepend2, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        //2007/04/02 馬 ADD ST
        $temDate = "";
        //2007/04/02 馬 ADD END

        $strSQL .= "INSERT INTO HSGENJ (" . "\r\n";
        $strSQL .= "            KEIJO_DT " . "\r\n";
        $strSQL .= "           ,KKR_CD   " . "\r\n";
        $strSQL .= "           ,BUSYO_CD " . "\r\n";
        $strSQL .= "           ,SYAIN_NO " . "\r\n";
        $strSQL .= "           ,GYOSYA_CD" . "\r\n";
        $strSQL .= "           ,NSG_KB   " . "\r\n";
        $strSQL .= "           ,NEW_DAISU " . "\r\n";
        $strSQL .= "           ,USED_DAISU " . "\r\n";
        $strSQL .= "           ,GENRI_GK   " . "\r\n";
        $strSQL .= "           ,OLD_NEW_DAISU " . "\r\n";
        $strSQL .= "           ,OLD_USED_DAISU" . "\r\n";
        $strSQL .= "           ,OLD_GENRI_GK  " . "\r\n";
        $strSQL .= "           ,UPD_DATE      " . "\r\n";
        $strSQL .= "           ,CREATE_DATE   " . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= "           ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "           ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "           ,UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End
        $strSQL .= "           )" . "\r\n";
        $strSQL .= "     SELECT V.KEIJO_DT" . "\r\n";
        $strSQL .= "           ,NVL(V.KKR_BUSYO_CD,'  ')" . "\r\n";
        $strSQL .= "           ,NVL(V.SYUKEI_BUSYO_CD,'   ')" . "\r\n";
        $strSQL .= "           ,V.SYAIN_NO" . "\r\n";
        $strSQL .= "           ,V.GYOSYA_CD" . "\r\n";
        $strSQL .= "           ,V.SYOKUSYU_KB" . "\r\n";
        $strSQL .= "           ,V.NEW_DAISU" . "\r\n";
        $strSQL .= "           ,V.USED_DAISU" . "\r\n";
        $strSQL .= "           ,V.GENRI_GK" . "\r\n";
        $strSQL .= "           ,V.OLD_NEW_DAISU" . "\r\n";
        $strSQL .= "           ,V.OLD_USER_DAISU" . "\r\n";
        $strSQL .= "           ,V.OLD_GENRI_GK" . "\r\n";
        $strSQL .= "           ,SYSDATE" . "\r\n";
        $strSQL .= "           ,SYSDATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",          '@UPDUSER'" . "\r\n";
        $strSQL .= ",          '@UPDAPP'" . "\r\n";
        $strSQL .= ",          '@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "       FROM" . "\r\n";
        $strSQL .= "           (SELECT A.KEIJO_DT" . "\r\n";
        $strSQL .= "                  ,C.KKR_BUSYO_CD" . "\r\n";
        $strSQL .= "                  ,B.SYUKEI_BUSYO_CD" . "\r\n";
        $strSQL .= "                  ,A.SYAIN_NO" . "\r\n";
        $strSQL .= "                  ,'     ' GYOSYA_CD" . "\r\n";
        $strSQL .= "                  ,MAX(B.SYOKUSYU_KB) SYOKUSYU_KB" . "\r\n";
        $strSQL .= "                  ,SUM(A.NEW_DAISU) NEW_DAISU" . "\r\n";
        $strSQL .= "                  ,SUM(A.USED_DAISU) USED_DAISU" . "\r\n";
        $strSQL .= "                  ,SUM(A.GENRI_GK) GENRI_GK" . "\r\n";
        $strSQL .= "                  ,SUM(CASE WHEN A.BUSYO_CD<>B.SYUKEI_BUSYO_CD THEN A.NEW_DAISU ELSE 0 END) OLD_NEW_DAISU" . "\r\n";
        $strSQL .= "                  ,SUM(CASE WHEN A.BUSYO_CD<>B.SYUKEI_BUSYO_CD THEN A.USED_DAISU ELSE 0 END) OLD_USER_DAISU" . "\r\n";
        $strSQL .= "                  ,SUM(CASE WHEN A.BUSYO_CD<>B.SYUKEI_BUSYO_CD THEN A.GENRI_GK ELSE 0 END) OLD_GENRI_GK" . "\r\n";
        $strSQL .= "              FROM HSCGENJ A" . "\r\n";
        //2007/04/02 馬 UPD ST
        $strSQL .= "                  ,HSYAINMST D" . "\r\n";
        //2007/04/24 INS
        $strSQL .= "                  ,HHAIZOKU B" . "\r\n";
        //2007/04/02 馬 UPD END
        $strSQL .= "                  ,HBUSYO C" . "\r\n";
        $strSQL .= "             WHERE A.SYAIN_NO = D.SYAIN_NO" . "\r\n";
        //2007/04/24 INS
        $strSQL .= "               AND A.SYAIN_NO = B.SYAIN_NO" . "\r\n";
        //2007/04/02 馬 ADD ST
        $temDate = substr($strDepend2, 0, 4) . "/" . substr($strDepend2, 4, 2) . "/01";
        $y = substr($temDate, 0, 4);
        $m = substr($temDate, 5, 2);
        $m1 = (int) $m;
        $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $ymd = $y . $m . $d;
        //$strSQL .= "               AND B.START_DATE <= '" . date('Ymd', strtotime("-1 day", strtotime("+1 month", $temDate))) . "'" . "\r\n";
        $strSQL .= "               AND B.START_DATE <= '" . $ymd . "'" . "\r\n";
        //$strSQL .= "               AND NVL(B.END_DATE, '99999999') >= '" . date('Ymd', strtotime("-1 day", strtotime("+1 month", $temDate))) . "'" . "\r\n";
        $strSQL .= "               AND NVL(B.END_DATE, '99999999') >= '" . $ymd . "'" . "\r\n";
        //2007/04/02 馬 ADD END
        $strSQL .= "               AND B.SYUKEI_BUSYO_CD = C.BUSYO_CD" . "\r\n";
        $strSQL .= "               AND B.SYOKUSYU_KB IN ('1','2')" . "\r\n";
        //2007/04/24 INS Start
        //$strSQL .= "               AND NVL(D.TAISYOKU_DATE,'99999999') >= '" . date("Ymd", $temDate) . "'" . "\r\n";
        $ymd = str_replace("/", "", $temDate);
        $strSQL .= "               AND NVL(D.TAISYOKU_DATE,'99999999') >= '" . $ymd . "'" . "\r\n";
        //2007/04/24 INS End
        $strSQL .= "             GROUP BY A.KEIJO_DT" . "\r\n";
        $strSQL .= "                     ,C.KKR_BUSYO_CD" . "\r\n";
        $strSQL .= "                     ,B.SYUKEI_BUSYO_CD" . "\r\n";
        $strSQL .= "                     ,A.SYAIN_NO" . "\r\n";
        $strSQL .= "     " . "\r\n";
        $strSQL .= "            UNION  ALL" . "\r\n";
        $strSQL .= "            SELECT A.KEIJO_DT" . "\r\n";
        $strSQL .= "                  ,C.KKR_BUSYO_CD" . "\r\n";
        $strSQL .= "                  ,D.SYUKEI_BUSYO_CD" . "\r\n";
        $strSQL .= "                  ,'99999' SYAIN_NO" . "\r\n";
        $strSQL .= "                  ,A.GYOSYA_CD" . "\r\n";
        $strSQL .= "                  ,'3' SYOKUSYU_KB" . "\r\n";
        $strSQL .= "                  ,SUM(A.NEW_DAISU) NEW_DAISU" . "\r\n";
        $strSQL .= "                  ,SUM(A.USED_DAISU) USED_DAISU" . "\r\n";
        $strSQL .= "                  ,SUM(A.GENRI_GK) GENRI_GK" . "\r\n";
        $strSQL .= "                  ,0" . "\r\n";
        $strSQL .= "                  ,0" . "\r\n";
        $strSQL .= "                  ,0" . "\r\n";
        $strSQL .= "             FROM HSCGENJ A" . "\r\n";
        $strSQL .= "                 ,HSYAINMST B" . "\r\n";
        $strSQL .= "                 ,HGYOSYAMST D" . "\r\n";
        $strSQL .= "                 ,HBUSYO C" . "\r\n";
        $strSQL .= "            WHERE A.SYAIN_NO = B.SYAIN_NO" . "\r\n";
        $strSQL .= "              AND D.SYUKEI_BUSYO_CD = C.BUSYO_CD" . "\r\n";
        $strSQL .= "              AND D.JISSEKI_KB ='0'" . "\r\n";
        $strSQL .= "              AND A.GYOSYA_CD = D.GYOSYA_CD" . "\r\n";
        $strSQL .= "              AND DECODE(SUBSTR(A.UC_NO,1,2),'20',SUBSTR(A.UC_NO,12,1),SUBSTR(A.UC_NO,10,1)) <> ' '" . "\r\n";
        $strSQL .= "              AND A.GYOSYA_CD IS NOT NULL" . "\r\n";
        $strSQL .= "            GROUP BY A.KEIJO_DT" . "\r\n";
        $strSQL .= "                    ,C.KKR_BUSYO_CD" . "\r\n";
        $strSQL .= "                    ,D.SYUKEI_BUSYO_CD" . "\r\n";
        $strSQL .= "                    ,A.GYOSYA_CD) V" . "\r\n";

        $strSQL .= "     WHERE V.KEIJO_DT >=  '@Depend1'" . "\r\n";
        $strSQL .= "       AND V.KEIJO_DT <=  '@Depend2'" . "\r\n";

        $strSQL = str_replace("@Depend1", $strDepend1, $strSQL);
        $strSQL = str_replace("@Depend2", $strDepend2, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：セールス実績ﾌｧｲﾙ作成(SQL)
    //関 数 名：fncSLSJSKInsert
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：限界利益ﾃﾞｰﾀより引渡された計上日範囲内のデータを抽出し、セールス実績ﾌｧｲﾙを作成する(SQL)
    //**********************************************************************
    public function fncSLSJSKInsertSql($strDepend1)
    {
        $strSQL = "";

        $strSQL .= "INSERT INTO HSCGENJ (" . "\r\n";
        $strSQL .= "            KEIJO_DT" . "\r\n";
        $strSQL .= "           ,KKR_CD" . "\r\n";
        $strSQL .= "           ,BUSYO_CD" . "\r\n";
        $strSQL .= "           ,SYAIN_NO" . "\r\n";
        $strSQL .= "           ,GYOSYA_CD" . "\r\n";
        $strSQL .= "           ,NSG_KB" . "\r\n";
        $strSQL .= "           ,NEW_DAISU" . "\r\n";
        $strSQL .= "           ,USED_DAISU" . "\r\n";
        $strSQL .= "           ,GENRI_GK" . "\r\n";
        $strSQL .= "           ,UC_NO" . "\r\n";
        $strSQL .= "           ,UPD_DATE" . "\r\n";
        $strSQL .= "           ,CREATE_DATE" . "\r\n";
        $strSQL .= "           )" . "\r\n";
        $strSQL .= "SELECT V.NENGETU" . "\r\n";
        $strSQL .= "      ,V.KKR_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,V.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "      ,V.ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "      ,V.ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= "      ,V.DATA_KB" . "\r\n";
        $strSQL .= "      ,V.NEW_DAISU" . "\r\n";
        $strSQL .= "      ,V.USED_DAISU" . "\r\n";
        $strSQL .= "      ,V.SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= "      ,V.UC_NO" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "      ,SYSDATE" . "\r\n";
        $strSQL .= "  FROM" . "\r\n";
        $strSQL .= "      (SELECT A.NENGETU" . "\r\n";
        $strSQL .= "             ,NVL(B.KKR_BUSYO_CD,'  ') KKR_BUSYO_CD" . "\r\n";
        $strSQL .= "             ,A.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "                ,NVL(A.ATUKAI_SYAIN,'     ') ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "                ,NVL(A.ATUKAI_GYOSYA,'     ') ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= "             ,MAX(A.DATA_KB) DATA_KB" . "\r\n";
        $strSQL .= "             ,SUM(DECODE(A.DATA_KB,'2',0,DAISU)) NEW_DAISU" . "\r\n";
        $strSQL .= "             ,SUM(DECODE(A.DATA_KB,'2',DAISU,0)) USED_DAISU" . "\r\n";
        $strSQL .= "             ,SUM(TOUGETU_GENRI) SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= "             ,A.UC_NO" . "\r\n";
        $strSQL .= "         FROM HGENRI_VW A" . "\r\n";
        $strSQL .= "             ,HBUSYO B" . "\r\n";
        $strSQL .= "           WHERE A.ATUKAI_BUSYO = B.BUSYO_CD" . "\r\n";
        //strSQL.Append("--           AND A.TOUGETU_GENRI <> 0" & vbCrLf)
        $strSQL .= "        GROUP BY A.NENGETU" . "\r\n";
        $strSQL .= "                ,KKR_BUSYO_CD" . "\r\n";
        $strSQL .= "                ,A.ATUKAI_BUSYO" . "\r\n";
        $strSQL .= "                ,ATUKAI_SYAIN" . "\r\n";
        $strSQL .= "                ,ATUKAI_GYOSYA" . "\r\n";
        $strSQL .= "                ,A.UC_NO) V" . "\r\n";
        $strSQL .= "     WHERE V.NENGETU = '@Depend1'" . "\r\n";

        $strSQL = str_replace("@Depend1", $strDepend1, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：ｾｰﾙｽ実績ﾃﾞｰﾀ削除
    //関 数 名：fncKaikeiDelete
    //引    数：strDepend1:開始計上日
    //　　　　：strDepend2:終了計上日
    //戻 り 値：SQL文
    //処理説明：引渡された計上日範囲内のｾｰﾙｽ実績ﾃﾞｰﾀを削除する
    //**********************************************************************
    public function fncSLSJSKDeleteSql($strKEIJYOBI)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        $strSQL .= "  FROM HSCGENJ" . "\r\n";
        $strSQL .= " WHERE KEIJO_DT = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKEIJYOBI, $strSQL);

        return $strSQL;
    }

    //*************************************
    // * 公開メソッド
    //*************************************
    public function fncSGENJInsert($strDepend1, $strDepend2, $strUpdPro)
    {
        return parent::insert($this->fncSGENJInsertSql($strDepend1, $strDepend2, $strUpdPro));
    }

    public function fncSLSJSKInsert($strDepend1)
    {
        return parent::insert($this->fncSLSJSKInsertSql($strDepend1));
    }

    public function fncSLSJSKDelete($strKEIJYOBI)
    {
        return parent::delete($this->fncSLSJSKDeleteSql($strKEIJYOBI));
    }

    public function fncFurikaeDelete($strKEIJYOBI, $strHaseiKB, $strActmode = "K")
    {
        return parent::delete($this->fncFurikaeDeleteSQL($strKEIJYOBI, $strHaseiKB, $strActmode));
    }

    public function fncFURIDAISUInsert($strKEIJYOBI, $strUpdPro, $strActmode = "K")
    {
        return parent::insert($this->fncFURIDAISUInsertSQL($strKEIJYOBI, $strUpdPro, $strActmode));
    }

    public function fncKaikeiDelete($strDepend1, $strDepend2, $strHaseiKB, $strActmode = "K")
    {
        return parent::delete($this->fncKaikeiDeleteSQL($strDepend1, $strDepend2, $strHaseiKB, $strActmode));
    }

    public function fncGetSaibanSelect($strID)
    {
        return parent::select($this->fncGetSaibanSelectSQL($strID));
    }

    public function fncGetSaibanUpdate($strID)
    {
        return parent::update($this->fncGetSaibanUpdateSQL($strID));
    }

    public function fncKijyunKaikeiInsert($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode = 'K')
    {
        return parent::insert($this->fncKijyunKaikeiInsertSQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode));
    }

    public function fncKijyunKaikeiInsert2($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode = 'K')
    {
        return parent::insert($this->fncKijyunKaikeiInsert2SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode));
    }

    public function fncKijyunKaikeiInsert3($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode = 'K')
    {
        return parent::insert($this->fncKijyunKaikeiInsert3SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode));
    }

    public function fncKijyunKaikeiInsert4($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode = 'K')
    {
        return parent::insert($this->fncKijyunKaikeiInsert4SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode));
    }

    public function fncKijyunKaikeiInsert5($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode = 'K')
    {
        return parent::insert($this->fncKijyunKaikeiInsert5SQL($strKEIJYOBI, $strDENPNO, $strUpdPro, $strActmode));
    }

    public function fncKNRZANDelete($strKEIJYOBI)
    {
        return parent::delete($this->fncKNRZANDeleteSQL($strKEIJYOBI));
    }

    public function fncSSKNRZANInsert($strDepend, $strUpdPro)
    {
        return parent::insert($this->fncSSKNRZANInsertSQL($strDepend, $strUpdPro));
    }

    public function fncSSKNRKaikeiInsert($strdepend, $strdenpno, $strUpdPro)
    {
        return parent::insert($this->fncSSKNRKaikeiInsertSQL($strdepend, $strdenpno, $strUpdPro));
    }

    public function fncIPKNRZANInsert($strDepend, $strUpdPro)
    {
        return parent::insert($this->fncIPKNRZANInsertSQL($strDepend, $strUpdPro));
    }

    public function fncIPKNRKaikeiInsert($strDepend, $strDenpNo, $strUpdPro)
    {
        return parent::insert($this->fncIPKNRKaikeiInsertSQL($strDepend, $strDenpNo, $strUpdPro));
    }

    public function fncPenaKaikeiInsert($strDepend, $strDenpNo, $strUpdPro)
    {
        return parent::insert($this->fncPenaKaikeiInsertSQL($strDepend, $strDenpNo, $strUpdPro));
    }

    public function fncPenaKaikeiChukoInsert($strDepend, $strDenpNo, $strUpdPro)
    {
        return parent::insert($this->fncPenaKaikeiChukoInsertSQL($strDepend, $strDenpNo, $strUpdPro));
    }

    //---20151102 li INS S.
    public function fncKaikeiCHK($strDepend1, $strDepend2, $strActmode = "K")
    {
        return parent::select($this->fncKaikeiCHKSQL($strDepend1, $strDepend2, $strActmode));
    }

    public function fncKaikeiWKInsert($strDepend1, $strDepend2, $strActmode = "K")
    {
        return parent::insert($this->fncKaikeiWKInsertSQL($strDepend1, $strDepend2, $strActmode));
    }

    public function fncKaikeiInsert($strDepend1, $strDepend2, $strUpdUser, $strUpdClt, $strUpdPro, $strActmode = "K")
    {
        return parent::insert($this->fncKaikeiInsertSQL($strDepend1, $strDepend2, $strUpdUser, $strUpdClt, $strUpdPro, $strActmode));
    }

    public function fncKaikeiWKDelete($strActmode = "K")
    {
        return parent::delete($this->fncKaikeiWKDeleteSQL($strActmode));
    }

    public function fncGetAnmattiData($strStartDt, $strEndDt, $strActmode = "K")
    {
        return parent::select($this->fncGetAnmattiDataSQL($strStartDt, $strEndDt, $strActmode));
    }
    //---20151102 li INS E.
}
