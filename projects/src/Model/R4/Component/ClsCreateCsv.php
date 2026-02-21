<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150917           #2118                   BUG                               LI
 * 20150929           #2122                   BUG                               LI
 * 20151008           20150929以降の修正差異点                                     LI　
 * 20160120           #2367       			  BUG                               LI
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\Component;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：FrmPrintTanto
// * 関数名	：FrmPrintTanto
// * 処理説明	：FrmPrintTanto
//*************************************

class ClsCreateCsv extends ClsComDb
{
    public $ClsComFnc = "";

    function __construct()
    {
        parent::__construct();
        $this->ClsComFnc = new ClsComFnc();
    }

    function fncChkUCNOSQL($strFromDate, $strToDate, $strSCKbn)
    {
        //$strDepend,$strFromDate,$strToDate变量没有意义，应该删除
        $strSQL = "";

        $strSQL .= "SELECT V.CMN_NO" . "\r\n";
        $strSQL .= "      ,V.UC_NO" . "\r\n";
        $strSQL .= "      ,V.JKN_HKD" . "\r\n";
        $strSQL .= "      ,V.HNB_JKN_HKO_RIN_LST_NO" . "\r\n";
        $strSQL .= "      ,V.CEL_DT" . "\r\n";
        $strSQL .= "      ,('  　　UCNOが入力されていません。　車両売上日＝' || V.SRY_URG_DT) ERR_MSG" . "\r\n";
        $strSQL .= "  FROM" . "\r\n";
        $strSQL .= "      (SELECT M_CMN.CMN_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.UC_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.JKN_HKD" . "\r\n";
        $strSQL .= "             ,M_CMN.HNB_JKN_HKO_RIN_LST_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.CEL_DT" . "\r\n";
        $strSQL .= "			 ,M_CMN.SRY_URG_DT" . "\r\n";
        $strSQL .= "         FROM M41E10 M_CMN" . "\r\n";
        $strSQL .= "       WHERE  UC_NO IS NULL" . "\r\n";
        $strSQL .= "         AND  M_CMN.SRY_URG_DT IS NOT NULL" . "\r\n";
        $strSQL .= "      　 AND (TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD') >= '@FROMDT'" . "\r\n";
        $strSQL .= "         AND  TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD') <= '@TODT')" . "\r\n";
        if ($strSCKbn != "") {
            $strSQL .= "          AND  M_CMN.NAU_KB = " . $this->ClsComFnc->FncSqlNv($strSCKbn) . "\r\n";
        }
        //2010/07/26 INS Start   条件変更ではないのにシステム上で更新されてしまい対象になってしまうものを外す
        $strSQL .= "         AND  NOT (M_CMN.REC_UPD_SYA_CD = '00000' AND M_CMN.REC_UPD_PRG_ID = 'JH41E002' AND M_CMN.REC_UPD_CLT_NM = 'MZS892')" . "\r\n";
        //2010/07/26 INS end
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "       SELECT M_RIE.CMN_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.UC_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.JKN_HKD" . "\r\n";
        $strSQL .= "             ,M_CMN.HNB_JKN_HKO_RIN_LST_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.CEL_DT" . "\r\n";
        $strSQL .= "			 ,M_CMN.SRY_URG_DT" . "\r\n";
        $strSQL .= "         FROM M41E30 M_RIE" . "\r\n";
        $strSQL .= "   INNER JOIN M41E10 M_CMN" . "\r\n";
        $strSQL .= "           ON M_CMN.CMN_NO = M_RIE.CMN_NO" . "\r\n";
        $strSQL .= "       WHERE  M_CMN.UC_NO IS NULL" . "\r\n";
        $strSQL .= "         AND  M_CMN.SRY_URG_DT IS NOT NULL" . "\r\n";
        $strSQL .= "         AND  M_RIE.REC_UPD_DT > M_CMN.REC_UPD_DT" . "\r\n";
        $strSQL .= "         AND (TO_CHAR(M_RIE.REC_UPD_DT,'YYYYMMDD') >= '@FROMDT'" . "\r\n";
        $strSQL .= "         AND  TO_CHAR(M_RIE.REC_UPD_DT,'YYYYMMDD') <= '@TODT')" . "\r\n";
        if ($strSCKbn != "") {
            $strSQL .= "          AND  M_CMN.NAU_KB = " . $this->ClsComFnc->FncSqlNv($strSCKbn) . "\r\n";
        }
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "       SELECT M_CMN.CMN_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.UC_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.JKN_HKD" . "\r\n";
        $strSQL .= "             ,M_CMN.HNB_JKN_HKO_RIN_LST_NO" . "\r\n";
        $strSQL .= "             ,M_CMN.CEL_DT" . "\r\n";
        $strSQL .= "             ,M_CMN.SRY_URG_DT" . "\r\n";
        $strSQL .= "         FROM M41B02 M_ZAI" . "\r\n";
        $strSQL .= "             ,M41E11 M_SIT" . "\r\n";
        $strSQL .= "             ,M41E10 M_CMN" . "\r\n";
        $strSQL .= "        WHERE M_CMN.UC_NO IS NULL" . "\r\n";
        $strSQL .= "          AND  M_CMN.SRY_URG_DT IS NOT NULL" . "\r\n";
        $strSQL .= "          AND M_CMN.CMN_NO = M_SIT.CMN_NO" . "\r\n";
        $strSQL .= "          AND M_SIT.TRA_GK <> M_ZAI.SIR_GK" . "\r\n";
        $strSQL .= "          AND M_SIT.CMN_NO = M_ZAI.SEIRI_NO" . "\r\n";
        //2009/10/27 INS Start   Ｒ４の中古車メニューの経過データ削除（在庫/画像データ削除）で一定期間経過した在庫情報を削除すると（容量削減のため）更新日がUPDATEされ、条件変更データとして
        //　　　　　　　　　　　 あがってきてしまうため、このプログラムの更新によって更新日が変更された場合は対象にしないという判断を追加。
        $strSQL .= "          AND M_ZAI.REC_UPD_PRG_ID <> 'VH41B900'" . "\r\n";
        //2009/10/27 INS End
        //2010/07/26 INS Start   条件変更ではないのにシステム上で更新されてしまい対象になってしまうものを外す
        $strSQL .= "          AND  NOT (M_ZAI.REC_UPD_SYA_CD = '00000' AND M_ZAI.REC_UPD_PRG_ID = 'JH41B001' AND M_ZAI.REC_UPD_CLT_NM = 'MZS892')" . "\r\n";
        //2010/07/26 INS end
        $strSQL .= "          AND TO_CHAR(M_ZAI.REC_UPD_DT,'YYYYMMDD') > TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD')" . "\r\n";
        $strSQL .= "          AND (TO_CHAR(M_ZAI.REC_UPD_DT,'YYYYMMDD') >= '@FROMDT'" . "\r\n";
        $strSQL .= "          AND  TO_CHAR(M_ZAI.REC_UPD_DT,'YYYYMMDD') <= '@TODT')" . "\r\n";
        if ($strSCKbn != "") {
            $strSQL .= "          AND  M_CMN.NAU_KB = " . $this->ClsComFnc->FncSqlNv($strSCKbn) . "\r\n";
        }
        $strSQL .= "        ) V" . "\r\n";
        $strSQL .= "        GROUP BY V.CMN_NO" . "\r\n";
        $strSQL .= "                ,V.UC_NO" . "\r\n";
        $strSQL .= "                ,V.JKN_HKD" . "\r\n";
        $strSQL .= "                ,V.HNB_JKN_HKO_RIN_LST_NO" . "\r\n";
        $strSQL .= "                ,V.CEL_DT" . "\r\n";
        $strSQL .= "                ,V.SRY_URG_DT" . "\r\n";
        $strSQL .= "ORDER BY V.SRY_URG_DT" . "\r\n";

        //此处代码没有意义,应该删除
        $strSQL = str_replace("@FROMDT", $strFromDate, $strSQL);
        $strSQL = str_replace("@TODT", $strToDate, $strSQL);
        //此处代码没有意义,应该删除
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：注文書番号ﾃｰﾌﾞﾙを削除する
    //関 数 名：fncDeleteWK_CHMNOSQL
    //引    数：無し
    //戻 り 値：SQL文
    //処理説明：注文書番号ﾃｰﾌﾞﾙを削除する
    //**********************************************************************
    function fncDeleteWK_CHMNOSQL()
    {
        $strSQL = "";
        $strSQL = "DELETE FROM WK_CMNNO";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：UCNO年月指定対象となる注文書番号を注文書番号ﾃｰﾌﾞﾙに格納する
    //関 数 名：fncInsertWK_CHMNO_UCNOSQL
    //引    数：無し
    //戻 り 値：SQL文
    //処理説明：CNO年月指定対象となる注文書番号を注文書番号ﾃｰﾌﾞﾙに格納する
    //**********************************************************************
    function fncInsertWK_CHMNO_UCNOSQL($strUCNO, $strFromDate, $strToDate, $strSCKbn)
    {
        $strSQL = "";

        $strSQL .= " INSERT INTO WK_CMNNO" . "\r\n";
        $strSQL .= "            (CMN_NO" . "\r\n";
        $strSQL .= "            ,GET_DATE)" . "\r\n";
        $strSQL .= "SELECT CMN_NO" . "\r\n";
        $strSQL .= "      ,MAX(REC_UPD_DT)" . "\r\n";
        $strSQL .= "  FROM" . "\r\n";
        //--BTH41E10_注文書データ
        $strSQL .= "      (SELECT M_CMN.CMN_NO,NULL REC_UPD_DT" . "\r\n";
        $strSQL .= "         FROM M41E10 M_CMN" . "\r\n";
        //$strSQL .= "        WHERE (SUBSTR(M_CMN.UC_NO,1,6) = " & clsComFnc.FncSqlNv(strUCNO)."\r\n";
        //$strSQL .= "          OR  (TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD') >= " & clsComFnc.FncSqlNv(strFromDate)."\r\n";
        //$strSQL .= "           AND TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD') <= " & clsComFnc.FncSqlNv(strToDate) & "))"."\r\n";
        $strSQL .= "       WHERE  UC_NO IS NOT NULL" . "\r\n";
        $strSQL .= "      　 AND (TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD') >= " . $this->ClsComFnc->FncSqlNv($strFromDate) . "\r\n";
        $strSQL .= "         AND  TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD') <= " . $this->ClsComFnc->FncSqlNv($strToDate) . ")" . "\r\n";
        if ($strSCKbn != "") {
            $strSQL .= "     AND  M_CMN.NAU_KB = " . $this->ClsComFnc->FncSqlNv($strSCKbn) . "\r\n";
        }
        //2010/07/26 INS Start
        //$strSQL .= "         AND  NOT (M_CMN.REC_UPD_SYA_CD = '00000' AND M_CMN.REC_UPD_PRG_ID = 'JH41E002' AND M_CMN.REC_UPD_CLT_NM = 'MZS892')"."\r\n";
        //2010/07/26 INS End
        //2011/12/08 MOD Start
        $strSQL .= "         AND  ( " . "\r\n";
        $strSQL .= "                NOT (M_CMN.REC_UPD_SYA_CD = '00000' AND M_CMN.REC_UPD_PRG_ID = 'JH41E002' AND M_CMN.REC_UPD_CLT_NM = 'MZS892')" . "\r\n";
        $strSQL .= "                OR  (M_CMN.REC_UPD_SYA_CD = '00000' AND M_CMN.REC_UPD_PRG_ID = 'JH41E002' AND M_CMN.REC_UPD_CLT_NM = 'MZS892'" . "\r\n";
        $strSQL .= "                     AND (SUBSTR(M_CMN.UC_NO,1,6) = " . $this->ClsComFnc->FncSqlNv($strUCNO) . "\r\n";
        $strSQL .= "                          OR SUBSTR(M_CMN.JKN_HKD,1,6) = " . $this->ClsComFnc->FncSqlNv($strUCNO) . "\r\n";
        $strSQL .= "                          OR SUBSTR(M_CMN.CEL_DT,1,6) = " . $this->ClsComFnc->FncSqlNv($strUCNO) . "\r\n";
        $strSQL .= "                         ) " . "\r\n";
        $strSQL .= "                    ) " . "\r\n";
        $strSQL .= "              ) " . "\r\n";
        //2011/12/08 MOD End
        //---BTH41E30_利益計算データ
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "       SELECT M_RIE.CMN_NO,M_RIE.REC_UPD_DT" . "\r\n";
        $strSQL .= "         FROM M41E30 M_RIE" . "\r\n";
        $strSQL .= "   INNER JOIN M41E10 M_CMN" . "\r\n";
        $strSQL .= "           ON M_CMN.CMN_NO = M_RIE.CMN_NO" . "\r\n";
        $strSQL .= "       WHERE  M_CMN.UC_NO IS NOT NULL" . "\r\n";
        $strSQL .= "         AND  M_RIE.REC_UPD_DT > M_CMN.REC_UPD_DT" . "\r\n";
        $strSQL .= "         AND (TO_CHAR(M_RIE.REC_UPD_DT,'YYYYMMDD') >= " . $this->ClsComFnc->FncSqlNv($strFromDate) . "\r\n";
        $strSQL .= "         AND  TO_CHAR(M_RIE.REC_UPD_DT,'YYYYMMDD') <= " . $this->ClsComFnc->FncSqlNv($strToDate) . ")" . "\r\n";
        if ($strSCKbn != "") {
            $strSQL .= "     AND  M_CMN.NAU_KB = " . $this->ClsComFnc->FncSqlNv($strSCKbn) . "\r\n";
        }
        //---BTH41B02_中古車在庫情報
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "       SELECT M_CMN.CMN_NO,M_ZAI.REC_UPD_DT" . "\r\n";
        $strSQL .= "         FROM M41B02 M_ZAI" . "\r\n";
        $strSQL .= "             ,M41E11 M_SIT" . "\r\n";
        $strSQL .= "             ,M41E10 M_CMN" . "\r\n";
        $strSQL .= "        WHERE M_CMN.UC_NO IS NOT NULL" . "\r\n";
        $strSQL .= "          AND M_CMN.CMN_NO = M_SIT.CMN_NO" . "\r\n";
        $strSQL .= "          AND M_SIT.TRA_GK <> M_ZAI.SIR_GK" . "\r\n";
        $strSQL .= "          AND M_SIT.CMN_NO = M_ZAI.SEIRI_NO" . "\r\n";
        //2009/10/27 INS Start   Ｒ４の中古車メニューの経過データ削除（在庫/画像データ削除）で一定期間経過した在庫情報を削除すると（容量削減のため）更新日がUPDATEされ、条件変更データとして
        //　　　　　　　　　　　 あがってきてしまうため、このプログラムの更新によって更新日が変更された場合は対象にしないという判断を追加。
        $strSQL .= "          AND M_ZAI.REC_UPD_PRG_ID <> 'VH41B900'" . "\r\n";
        //2009/10/27 INS End
        //2010/07/26 INS Start
        $strSQL .= "          AND  NOT (M_ZAI.REC_UPD_SYA_CD = '00000' AND M_ZAI.REC_UPD_PRG_ID = 'JH41B001' AND M_ZAI.REC_UPD_CLT_NM = 'MZS892')" . "\r\n";
        //2010/07/26 INS End
        $strSQL .= "          AND TO_CHAR(M_ZAI.REC_UPD_DT,'YYYYMMDD') > TO_CHAR(M_CMN.REC_UPD_DT,'YYYYMMDD')" . "\r\n";
        $strSQL .= "          AND (TO_CHAR(M_ZAI.REC_UPD_DT,'YYYYMMDD') >= " . $this->ClsComFnc->FncSqlNv($strFromDate) . "\r\n";
        $strSQL .= "          AND  TO_CHAR(M_ZAI.REC_UPD_DT,'YYYYMMDD') <= " . $this->ClsComFnc->FncSqlNv($strToDate) . ")" . "\r\n";
        if ($strSCKbn != "") {
            $strSQL .= "     AND  M_CMN.NAU_KB = " . $this->ClsComFnc->FncSqlNv($strSCKbn) . "\r\n";
        }
        ////---BTH41E31_利益計算明細データ
        //$strSQL .= "        UNION ALL"."\r\n";
        //$strSQL .= "       SELECT M_RID.CMN_NO"."\r\n";
        //$strSQL .= "         FROM M41E31 M_RID"."\r\n";
        //$strSQL .= "   INNER JOIN M41E10 M_CMN"."\r\n";
        //$strSQL .= "           ON M_CMN.CMN_NO = M_RID.CMN_NO"."\r\n";
        //$strSQL .= "        WHERE  M_CMN.UC_NO IS NOT NULL"."\r\n";
        //$strSQL .= "          AND (TO_CHAR(M_RID.REC_UPD_DT,'YYYYMMDD') >= " & clsComFnc.FncSqlNv(strFromDate)."\r\n";
        //$strSQL .= "          AND  TO_CHAR(M_RID.REC_UPD_DT,'YYYYMMDD') <= " & clsComFnc.FncSqlNv(strToDate) & ")"."\r\n";
        //If strSCKbn <> "" Then
        //    $strSQL .= "     AND  M_CMN.NAU_KB = " & clsComFnc.FncSqlNv(strSCKbn)."\r\n";
        //End If
        $strSQL .= ") V";
        $strSQL .= "       GROUP BY  V.CMN_NO" . "\r\n";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新車中古車売上データ取得(SQL)
    //関 数 名：fncChuSelectSql
    //引    数：無し
    //戻 り 値：SQL文
    //処理説明：新車中古車売上データ取得(SQL)
    //**********************************************************************
    function fncChuSelectSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT    M_CHU.CMN_NO" . "\r\n";
        //注文書番号
        $strSQL .= "         ,M_CHU.NAU_KB" . "\r\n";
        //新中区分
        $strSQL .= "         ,M_CHU.UC_NO" . "\r\n";
        //UC番号
        $strSQL .= "         ,M_CHU.CKO_CAR_SER_NO" . "\r\n";
        //中古車整理№
        $strSQL .= "         ,M_CHU.CKO_CAR_SER_SEQ" . "\r\n";
        //中古車整理№枝番
        //2007/04/03 UPD START
        //strSQL.Append("         ,M_CHU.HNB_KTN_CD"."\r\n";                                        '販売拠点コード
        $strSQL .= "         ,(CASE WHEN CNV.CMN_NO IS NOT NULL THEN CNV.BUSYO_CD ELSE M_CHU.HNB_KTN_CD END) HNB_KTN_CD" . "\r\n";
        //2007/04/03 UPD END
        $strSQL .= "         ,M_CHU.HNB_TAN_EMP_NO" . "\r\n";
        //販売担当社員番号
        $strSQL .= "         ,DECODE(M_CHU.DAIRITN_CD,'9998','9000','9001','9000',M_CHU.DAIRITN_CD) DAIRITN_CD" . "\r\n";
        //業販店コード
        $strSQL .= "         ,DECODE(M_CHU.SVKYOTN_CD,'999','00',M_CHU.SVKYOTN_CD) SVKYOTN_CD" . "\r\n";
        //サービス拠点コード
        $strSQL .= "         ,M_CHU.NTI_COP_USR_CD" . "\r\n";
        //認定法人ユーザーコード
        $strSQL .= "         ,M_CHU.TOU_DT" . "\r\n";
        //登録日
        $strSQL .= "         ,M_CHU.SRY_URG_DT" . "\r\n";
        //車両売上日
        $strSQL .= "         ,TO_CHAR(M_CHU.URG_SYR_DT,'YYYYMMDD') URG_SYR_DT" . "\r\n";
        //売上処理日
        $strSQL .= "         ,M_CHU.CEL_DT" . "\r\n";
        //解約日
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '2'" . "\r\n";
        $strSQL .= "            THEN M_C_ZAIKO.SYADAI_NO" . "\r\n";
        $strSQL .= "            ELSE M_S_ZAIKO.SDAIKATA_CD END) SDI_KAT" . "\r\n";
        //車台型式
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '2'" . "\r\n";
        $strSQL .= "            THEN M_C_ZAIKO.CAR_NO" . "\r\n";
        $strSQL .= "            ELSE M_S_ZAIKO.CAR_NO END) CAR_NO" . "\r\n";
        //カーNO
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '2'" . "\r\n";
        $strSQL .= "            THEN M_C_ZAIKO.MAKER_CD ELSE '' END) MAKER_CD" . "\r\n";
        //銘柄コード
        $strSQL .= "         ,M_CHU.YSK" . "\r\n";
        //年式(年)
        $strSQL .= "         ,M_C_ZAIKO.SYODO_YM" . "\r\n";
        //初年度登録
        $strSQL .= "         ,M_CHU.NINKATA_CD" . "\r\n";
        //認可型式
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        $strSQL .= "            THEN SUBSTR(M_S_ZAIKO.STKSK_RBTNO,1,4)" . "\r\n";
        $strSQL .= "            ELSE SUBSTR(M_C_ZAIKO.SITEI_NO,1,4) END) SITEI_NO" . "\r\n";
        //指定型式類別番号
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        $strSQL .= "            THEN SUBSTR(M_S_ZAIKO.STKSK_RBTNO,6,4)" . "\r\n";
        $strSQL .= "            ELSE M_C_ZAIKO.RUIBETU_NO END) RUIBETU_NO" . "\r\n";
        //指定型式類別番号
        $strSQL .= "         ,M_CHU.HBSS_CD" . "\r\n";
        //販売車種コード
        $strSQL .= "         ,M_CHU.TOURK_NO" . "\r\n";
        //登録NO
        $strSQL .= "         ,M_CHU.SYAKEN_EXP_DT" . "\r\n";
        //車検満了日
        $strSQL .= "         ,M_CHU.KYK_TOU_HNS" . "\r\n";
        //契約･登録販社
        $strSQL .= "         ,M_S_ZAIKO.AIM_KEISAKI_KB" . "\r\n";
        //AIM契約区分
        $strSQL .= "         ,M_CHU.EC_JUCHU_KB" . "\r\n";
        //EC受注区分
        $strSQL .= "         ,M_CHU.ABHOT_KB" . "\r\n";
        //ABホット区分
        $strSQL .= "         ,DECODE(M_CHU.VCLYOTOKBN,'1','1','2','2','1') VCLYOTOKBN" . "\r\n";
        //用途区分
        $strSQL .= "         ,NVL(M_CHU.CSR_KB,'1') CSR_KB" . "\r\n";
        //管理客区分（顧客区分）
        $strSQL .= "         ,M_CHU.HNB_ARA" . "\r\n";
        //販売地域
        $strSQL .= "         ,M_CHU.KGO_KB" . "\r\n";
        //競合区分
        $strSQL .= "         ,M_CHU.BUY_SHP" . "\r\n";
        //購入形態
        $strSQL .= "         ,M_CHU.LEASE_KB" . "\r\n";
        //リース区分
        $strSQL .= "         ,M_CHU.LES_SHP" . "\r\n";
        //リース形態
        $strSQL .= "         ,M_CHU.TKS_KB" . "\r\n";
        //特装区分
        $strSQL .= "         ,M_CHU.TKS_NYO" . "\r\n";
        //特装内容
        $strSQL .= "         ,M_CHU.HNB_KB" . "\r\n";
        //販売区分
        $strSQL .= "         ,DECODE(M_CHU.SRY_RRK_FKA_KB_DM,'1','0','1') SRY_RRK_FKA_KB_DM" . "\r\n";
        //DM区分
        $strSQL .= "         ,DECODE(M_CHU.SRY_RRK_FKA_KB_DM,'1','1','0') NYK_YAKUSOKU" . "\r\n";
        //入庫約束
        $strSQL .= "         ,M_CHU.CHUKOSYA_NO" . "\r\n";
        //中古車NO
        $strSQL .= "         ,M_CHU.JKN_HKD" . "\r\n";
        //条件変更日
        $strSQL .= "         ,M_CHU.HNB_JKN_HKO_RIN_LST_NO" . "\r\n";
        //条件変更稟議書NO
        $strSQL .= "         ,M_CHU.KYK_FGN" . "\r\n";
        //契約者ｶﾅ氏名
        $strSQL .= "         ,M_CHU.KYK_CSRDOSID" . "\r\n";
        //性別区分
        $strSQL .= "         ,M_OKY.BRTDT" . "\r\n";
        //生年月日(名義人)

        $strSQL .= "         ,(NVL2(M_CHU.KYK_TEL_ACD,M_CHU.KYK_TEL_ACD,'') || ";
        //契約者TEL
        $strSQL .= "           NVL2(M_CHU.KYK_TEL_ACD,'-','') || ";
        $strSQL .= "           NVL2(M_CHU.KYK_TEL_CCD, M_CHU.KYK_TEL_CCD, '') || ";
        $strSQL .= "           NVL2(M_CHU.KYK_TEL_CCD,'-', '') || ";
        $strSQL .= "           NVL2(M_CHU.KYK_TEL_KNY_NO, M_CHU.KYK_TEL_KNY_NO, '')) KYK_TEL";
        $strSQL .= "         ,M_CHU.KYK_YBN_NO" . "\r\n";
        //契約者郵便番号
        $strSQL .= "         ,M_CHU.KYK_ADRSCD" . "\r\n";
        //住所ｺｰﾄﾞ
        $strSQL .= "         ,M_CHU.KYK_ADR1" . "\r\n";
        //住所1
        $strSQL .= "         ,M_CHU.KYK_ADR2" . "\r\n";
        //住所2
        $strSQL .= "         ,M_CHU.KYK_ADR3" . "\r\n";
        //住所3
        $strSQL .= "         ,M_CHU.KYK_CUS_NM1" . "\r\n";
        //契約者お客様名１
        $strSQL .= "         ,M_CHU.KYK_CUS_NM2" . "\r\n";
        //契約者お客様名２
        $strSQL .= "         ,M_CHU.SIY_FGN" . "\r\n";
        //使用者ｶﾅ氏名
        $strSQL .= "         ,DECODE(M_CHU.USR_CSRDOSID,'3','4',M_CHU.USR_CSRDOSID) USR_CSRDOSID" . "\r\n";
        //使用者性別区分
        $strSQL .= "         ,(NVL2(M_CHU.SIY_TEL_ACD,M_CHU.SIY_TEL_ACD,'') || ";
        //使用者TEL
        $strSQL .= "           NVL2(M_CHU.SIY_TEL_ACD,'-','') || ";
        $strSQL .= "           NVL2(M_CHU.SIY_TEL_CCD, M_CHU.SIY_TEL_CCD, '') || ";
        $strSQL .= "           NVL2(M_CHU.SIY_TEL_CCD,'-', '') || ";
        $strSQL .= "           NVL2(M_CHU.SIY_TEL_KNY_NO, M_CHU.SIY_TEL_KNY_NO, '')) SIY_TEL";
        $strSQL .= "         ,M_CHU.SIY_YBN_NO" . "\r\n";
        //使用者郵便番号
        $strSQL .= "         ,M_CHU.USR_ADRSCD" . "\r\n";
        //使用者住所ｺｰﾄﾞ
        $strSQL .= "         ,M_CHU.SIY_ADR1" . "\r\n";
        //使用者住所1
        $strSQL .= "         ,M_CHU.SIY_ADR2" . "\r\n";
        //使用者住所2
        $strSQL .= "         ,M_CHU.SIY_ADR3" . "\r\n";
        //使用者住所3
        $strSQL .= "         ,M_CHU.SIY_CUS_NM1" . "\r\n";
        //使用者お客様名１
        $strSQL .= "         ,M_CHU.SIY_CUS_NM2" . "\r\n";
        //使用者お客様名２
        $strSQL .= "         ,M_CHU.KYK_CUS_NO VCHU_KEYAKSHA" . "\r\n";
        //契約者コード
        //$strSQL .= "         ,M_CHU.CSR_KB" . "\r\n";
        //管理容区分
        $strSQL .= "         ,M_CHU.SRY_HT_PRC_ZEINK" . "\r\n";
        //車両本体価格
        $strSQL .= "         ,M_CHU.SRY_HT_NBK_GKU_ZEINK" . "\r\n";
        //車両値引額
        $strSQL .= "         ,M_CHU.SRY_PCS" . "\r\n";
        //車両原価
        $strSQL .= "         ,M_RIE.SRY_KNR_PCS" . "\r\n";
        //車輌管理原価　
        $strSQL .= "         ,M_CHU.FZH_SUM_GKU_ZEINK" . "\r\n";
        //付属品合計
        $strSQL .= "         ,M_CHU.FZH_NBK_SUM_GKU_ZEINK" . "\r\n";
        //付属品値引
        $strSQL .= "         ,M_CHU.FZH_SHZ_SUM_GKU" . "\r\n";
        //付属品消費税額
        $strSQL .= "         ,M_RIE.FZH_KNR_PCS" . "\r\n";
        //付属品原価　付属品管理原価
        $strSQL .= "         ,M_CHU.TKB_KSH_SUM_GKU_ZEINK" . "\r\n";
        //特装(3％)合計
        $strSQL .= "         ,M_CHU.TKB_KSH_NBK_SUM_GKU_ZEINK" . "\r\n";
        //特装(3％)値引
        $strSQL .= "         ,M_CHU.TKB_KSH_SHZ_SUM_GKU" . "\r\n";
        //特装(3％)消費税額
        $strSQL .= "         ,M_RIE.TKB_KSH_KNR_PCS" . "\r\n";
        //特装(3％)原価　特別架装品管理原価
        $strSQL .= "         ,M_CHU.KAP_TES" . "\r\n";
        //割賦手数料
        $strSQL .= "         ,M_CHU.KAP_TES_NBK" . "\r\n";
        //割賦手数料値引
        $strSQL .= "         ,M_CHU.BET_SHR_HYO_SUM_GKU_ZEINK" . "\r\n";
        //登録諸費用3%　付帯費用
        $strSQL .= "         ,M_CHU.BET_SHR_HYO_SHZ_GKU" . "\r\n";
        //登録諸費用3%消費税　付帯費用
        $strSQL .= "         ,M_CHU.TRA_CAR_ZSI_SUM" . "\r\n";
        //下取車残債
        $strSQL .= "         ,M_CHU.TRA_CAR_PRC_SUM" . "\r\n";
        //下取車額
        $strSQL .= "         ,M_CHU.TRA_CAR_SHZ_SUM" . "\r\n";
        //下取車消費税額
        $strSQL .= "         ,M_CHU.SHR_GKN_DPS" . "\r\n";
        //現金
        $strSQL .= "         ,M_CHU.TGT_MSU" . "\r\n";
        //手形枚数
        $strSQL .= "         ,M_CHU.KAP_MOT_KIN" . "\r\n";
        //割賦元金
        $strSQL .= "         ,M_CHU.KRJ_BUN_KSU" . "\r\n";
        //クレジット回数
        $strSQL .= "         ,M_CHU.KRJ_MOT_KIN" . "\r\n";
        //クレジット元金
        $strSQL .= "         ,M_CHU.CREDITCD" . "\r\n";
        //クレジット会社
        $strSQL .= "         ,M_CHU.CRE_NO" . "\r\n";
        //クレジットNO
        $strSQL .= "         ,M_CHU.CRE_RISOKU_GK" . "\r\n";
        //ｸﾚｼﾞｯﾄ利息額
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        //2007/04/03 UPD START
        //strSQL.Append("            THEN S_JIDOSYA.SCO_GK_ZEINK"."\r\n";
        //strSQL.Append("            ELSE C_JIDOSYA.SCO_GK_ZEINK END) JIDOSYA_ZEI"."\r\n";           //自動車税
        $strSQL .= "            THEN JIDOSYA.SINSYA" . "\r\n";
        $strSQL .= "            ELSE JIDOSYA.CHUKO" . "\r\n";
        $strSQL .= "           END) JIDOSYA_ZEI" . "\r\n";
        //2007/04/03 UPD END
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        $strSQL .= "            THEN S_SYUTOKU.SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "            ELSE C_SYUTOKU.SCO_GK_ZEINK END) SYUTOKU_ZEI" . "\r\n";
        //取得税
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        $strSQL .= "            THEN S_JYUURYO.SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "            ELSE C_JYUURYO.SCO_GK_ZEINK END) JUURYO_ZEI" . "\r\n";
        //重量税
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        $strSQL .= "            THEN S_JIBAIHO.SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "            ELSE C_JIBAIHO.SCO_GK_ZEINK END) JIBAIHO_ZEI" . "\r\n";
        //自賠責
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        $strSQL .= "            THEN S_JIBAIHO.HJO_INF_ATY_SU_ATY" . "\r\n";
        $strSQL .= "            ELSE C_JIBAIHO.HJO_INF_ATY_SU_ATY END) JIBAIHO_TUKISU" . "\r\n";
        //自賠責月数
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '2'" . "\r\n";
        $strSQL .= "            THEN C_MJIDOSYA.SCO_GK_ZEINK" . "\r\n";
        //未経過自動車税
        $strSQL .= "            ELSE 0 END) MJIDOSYA_ZEI" . "\r\n";
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '2'" . "\r\n";
        $strSQL .= "            THEN C_MJIDOSYA.SHZ_GK" . "\r\n";
        //未経過自動車税消費税
        $strSQL .= "            ELSE 0 END) MJIDOSYA_SHZ" . "\r\n";
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '2'" . "\r\n";
        $strSQL .= "            THEN C_MJIBAIHO.SCO_GK_ZEINK" . "\r\n";
        //未経過自賠責
        $strSQL .= "            ELSE 0 END) MJIBAIHO_ZEI" . "\r\n";
        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '2'" . "\r\n";
        $strSQL .= "            THEN C_MJIBAIHO.SHZ_GK" . "\r\n";
        //未経過自賠責消費税
        $strSQL .= "            ELSE 0 END) MJIBAIHO_SHZ" . "\r\n";
        $strSQL .= "         ,M_CHU.OPT_HOK_KIN" . "\r\n";
        //任意保険料
        $strSQL .= "         ,(NVL(DECODE(M_CHU.ABHOT_KB,'28',0,M_CHU.SRY_HTA_SHZ_GKU),0)" . "\r\n";
        $strSQL .= "         + NVL(M_CHU.FZH_SHZ_SUM_GKU,0)" . "\r\n";
        $strSQL .= "         + NVL(M_CHU.TKB_KSH_SHZ_SUM_GKU,0)" . "\r\n";
        //2006/11/02 UPDATE Start BET_SHR_HYO_SHZ_GKUに含まれてはいけない消費税が含まれていたため変更
        $strSQL .= "         + NVL(S_SYOKEIZEI.SHZ_GK,0)" . "\r\n";
        $strSQL .= "         + NVL(C_MJIDOSYA.SHZ_GK,0)" . "\r\n";
        //中古車未経過自動車税消費税
        $strSQL .= " 　　　　+ NVL(C_MJIBAIHO.SHZ_GK,0)) ZEIGOUKEI" . "\r\n";
        //中古車未経過自賠責消費税
        //strSQL.Append("         + NVL(M_CHU.BET_SHR_HYO_SHZ_GKU,0)) ZEIGOUKEI"."\r\n";              //消費税合計
        //2006/11/02 UPDATE End
        //strSQL.Append("         + M_RSIKIN.SHIKIN_KNR_SHZ) ZEIGOUKEI"."\r\n";                      //消費税合計(資金管理料消費税）

        $strSQL .= "         ,M_CHU.HNB_TES_SKI_RYO" . "\r\n";
        //販売手数料
        $strSQL .= "         ,M_CHU.ETC_NBK" . "\r\n";
        //その他
        $strSQL .= "         ,M_SYOKAI.SKP_CD" . "\r\n";
        //紹介者ｺｰﾄﾞ
        $strSQL .= "         ,M_SYOKAI.SKP_KB" . "\r\n";
        //紹介者区分
        //20190731 UPDATE START
        ////---20150929 li UPD S.
        ////$strSQL .= "       ,DECODE(M_SYOKAI.HNB_SHZ_RT_KB,'4','1','1','1','') HNB_SHZ_RT_KB" . "\r\n";
        //$strSQL .= "         ,DECODE(M_SYOKAI.HNB_SHZ_RT_KB,'4','1','5','1','6','1','1','1','') HNB_SHZ_RT_KB" . "\r\n";
        ////---20150929 li UPD E.
        $strSQL .= "         ,DECODE(M_SYOKAI.HNB_SHZ_RT_KB,'4','1','5','1','6','1','7','1','1','1','') HNB_SHZ_RT_KB" . "\r\n";
        //20190731 UPDATE END
        //紹介者税区分
        $strSQL .= "         ,M_SYOKAI.HNB_SHZ_RT" . "\r\n";
        //紹介者税率　
        $strSQL .= "         ,M_SYOKAI.HNB_SKI_RYO" . "\r\n";
        //紹介料計
        $strSQL .= "         ,M_SYOKAI.HNB_SHZ_GKU" . "\r\n";
        //紹介料消費税
        $strSQL .= "         ,M_HOUTEIHI.SCO_GK_ZEINK HOUTEIHI" . "\r\n";
        //法定費
        $strSQL .= "         ,M_JAF.SCO_GK_ZEINK JAF" . "\r\n";
        //JAF
        $strSQL .= "         ,M_RYOTAKU.SCO_GK_ZEINK RYOTAKU" . "\r\n";
        //ﾘｻｲｸﾙ預託金

        //strSQL.Append("         ,(CASE WHEN M_CHU.HNB_KB = '5'"."\r\n";
        //strSQL.Append("            THEN 0"."\r\n";
        //strSQL.Append("            ELSE M_RYOTAKU.SCO_GK_ZEINK END) RYOTAKU"."\r\n";               //ﾘｻｲｸﾙ預託金

        $strSQL .= "         ,M_CHU.SRY_HTA_SHZ_GKU" . "\r\n";
        //車両消費税
        $strSQL .= "         ,M_CHU.FZH_SHZ_SUM_GKU" . "\r\n";
        //付/特消費税額
        $strSQL .= "         ,M_CHU.TKB_KSH_SHZ_SUM_GKU" . "\r\n";
        //特別仕消費税額
        $strSQL .= "         ,M_CHU.BET_SHR_HYO_SHZ_GKU" . "\r\n";
        //付帯費消費税
        $strSQL .= "         ,M_CHU.SHZ_RT HTA_SHZ_RT" . "\r\n";
        //本体税率
        $strSQL .= "         ,0 FTZEIRIT" . "\r\n";
        //付/特税率
        $strSQL .= "         ,0 TSZEIRIT" . "\r\n";
        //特別仕税率
        $strSQL .= "         ,0 FIZEIRIT" . "\r\n";
        //付帯費税率
        $strSQL .= "         ,M_CHU.SHR_KB" . "\r\n";
        //支払方法
        $strSQL .= "         ,M_CHU.TRA_CAR_PRC_SUM" . "\r\n";
        //下取車額
        $strSQL .= "         ,M_CHU.TRA_CAR_SHZ_SUM" . "\r\n";
        //下取車消費税額

        $strSQL .= "         ,S_SYOKEI.SCO_GK_ZEINK SHOKEI" . "\r\n";
        //登録諸費用小計
        $strSQL .= "         ,S_SYOKEIZEI.SHZ_GK SHOKEI_SHZ" . "\r\n";
        //登録諸費用小計税

        $strSQL .= "         ,SUBSTRB(M_SYOKAI.SKP_NM1,1,20) SKP_NM1" . "\r\n";
        //紹介者名

        $strSQL .= "         ,(CASE WHEN M_CHU.NAU_KB = '1'" . "\r\n";
        //預託区分
        $strSQL .= "                    THEN DECODE(M_CHU.TOUROKU_UM,'0','1','1','2','')" . "\r\n";
        $strSQL .= "                    ELSE " . "\r\n";
        $strSQL .= "                        (CASE WHEN NVL(M_CHU.YOTAK_GK,0)  = 0" . "\r\n";
        $strSQL .= "                          THEN  (CASE WHEN M_CHU.HNB_KB  = '5' OR  M_CHU.HNB_KB  = '9'" . "\r\n";
        $strSQL .= "                                 THEN '2' ELSE '1' END)" . "\r\n";
        $strSQL .= "                          ELSE " . "\r\n";
        $strSQL .= "                           (CASE WHEN NVL(M_RSIKIN.SHIKIN_KNR_RYOKIN,0) = 0" . "\r\n";
        $strSQL .= "                            THEN '2' ELSE '1' END) END) END) TOUROKU_UM" . "\r\n";

        $strSQL .= "         ,DECODE(M_CHU.ATSUKAI_KB,'0','1','1','2','') ATSUKAI_KB" . "\r\n";
        //扱い区分
        $strSQL .= "         ,M_CHU.YOTAK_GK" . "\r\n";
        //売車預託金相当額
        //strSQL.Append("         ,TRUNC(M_CHU.SHIKIN_KNR_RYOKIN"."\r\n";
        //strSQL.Append("            / (1 + M_CHU.SHZ_RT / 100) + 0.9) SHIKIN_KNR_RYOKIN"."\r\n";   //売車資金管理料金
        $strSQL .= "         ,M_RSIKIN.SHIKIN_KNR_RYOKIN" . "\r\n";
        //売車資金管理料金
        $strSQL .= "         ,M_CHU.TRA_CAR_RCY_YTK_SUM_GKU" . "\r\n";
        //下取車リサイクル預託金合計額

        $strSQL .= "         ,M_CHU.SIY_SMI_CAR_KNR_HYO" . "\r\n";
        //使用済車関連費用

        $strSQL .= "         ,M_RIE.JIP_DTL_SUM" . "\r\n";
        //実費明細合計

        //strSQL.Append("         ,M_TAISAKUHI.GK TAISAKUHI"."\r\n";                                //対策費計
        //strSQL.Append("         ,NVL(M_C_ZAIKO.SIR_GK,0) + NVL(M_C_ZAIKO.SIR_SZEI_GK,0) SIR_GK"."\r\n";          //中古車仕入金額+消費税                                   //中古車仕入金額
        //strSQL.Append("         ,NVL(M_C_ZAIKO.SATEI_GK,0) + NVL(M_C_ZAIKO.SATE_SZEI_GK,0) SATEI_GK"."\r\n";     //中古査定金額
        $strSQL .= "         ,NVL(M_C_ZAIKO.SIR_GK,0) SIR_GK" . "\r\n";
        //中古車仕入金額+消費税                                   //中古車仕入金額
        $strSQL .= "         ,NVL(M_C_ZAIKO.SATEI_GK,0) SATEI_GK" . "\r\n";
        //中古査定金額
        $strSQL .= "         ,M_CHU.SAISEI_GK" . "\r\n";
        //再生費

        $strSQL .= "         ,M_TOUROKU_KENSA.GK M_TOUROKU_KENSA" . "\r\n";
        //登録諸費用3検査
        $strSQL .= "         ,M_TOUROKU_SYAKO.GK M_TOUROKU_SYAKO" . "\r\n";
        //登録諸費用3車庫証明3検査
        $strSQL .= "         ,M_TOUROKU_NOUSYA.GK M_TOUROKU_NOUSYA" . "\r\n";
        //登録諸費用3納車費用
        $strSQL .= "         ,M_TOUROKU_SITA.GK M_TOUROKU_SITA" . "\r\n";
        //登録諸費用3下取諸手続
        $strSQL .= "         ,M_TOUROKU_SATEI.GK M_TOUROKU_SATEI" . "\r\n";
        //登録諸費用3査定料
        $strSQL .= "         ,M_TOUROKU_JIKOU.GK M_TOUROKU_JIKOU" . "\r\n";
        //登録諸費用3自光式
        //2007/07/06 UPD Start
        //strSQL.Append("         ,M_TOUROKU_TA.GK M_TOUROKU_TA"."\r\n";                             //登録諸費用3その他
        $strSQL .= "         ,M_TOUROKU_TA.SONOTAGK" . "\r\n";
        //登録諸費用3その他(燃料代、その他)
        $strSQL .= "         ,M_TOUROKU_TA.PACK753" . "\r\n";
        //パックＤＥ753
        $strSQL .= "         ,M_TOUROKU_TA.PACKMENTE" . "\r\n";
        //パックＤＥメンテ
        //2007/07/06 UPD End

        $strSQL .= "         ,M_AZU_KENSA.GK M_AZU_KENSA" . "\r\n";
        //預り法定費用検査
        $strSQL .= "         ,M_AZU_SYAKO.GK M_AZU_SYAKO" . "\r\n";
        //預り法定費用車庫証明
        $strSQL .= "         ,M_AZU_SITA.GK M_AZU_SITA" . "\r\n";
        //預り法定費用下取

        $strSQL .= "         ,M_NINTE.GK M_NINTE" . "\r\n";
        //任意保険収手

        $strSQL .= "         ,M_KB.GK M_KB" . "\r\n";
        //ｷｯｸﾊﾞｯｸ

        $strSQL .= "         ,M_ETCSYOKAI.GK M_ETCSYOKAI" . "\r\n";
        //そのた紹介料

        $strSQL .= "         ,M_RKJ.RIKUJI_NM" . "\r\n";
        //陸事名

        $strSQL .= "         ,M_GENKA.KTN_PCS" . "\r\n";
        //拠点原価
        $strSQL .= "         ,M_GENKA.SIK_PCS" . "\r\n";
        //仕切
        $strSQL .= "         ,M_GENKA.TYK_PCS" . "\r\n";
        //仕切
        $strSQL .= "         ,M_GENKA.F_PCS" . "\r\n";
        //Ｆ号原価
        //strSQL.Append("         ,M_GENKA.UC_OYA"."\r\n";                                          //UC親CD
        //strSQL.Append("         ,M_GENKA.CD_KETA2"."\r\n";                                        //Ｆ号原価

        $strSQL .= "         ,M_SYASYUCD.SS_CD" . "\r\n";
        //車種コード
        $strSQL .= "         ,M_SYASYUCD.SS_NAME" . "\r\n";
        //車種名
        $strSQL .= "         ,M_SYASYUCD.UCOYA_CD" . "\r\n";
        //UC親CD
        $strSQL .= "         ,M_SYASYUCD.ID GENKA_ID" . "\r\n";
        //UC親ｺｰﾄﾞ

        //strSQL.Append("         ,M_GYOSYA.ATO_DTRPITNM1"."\r\n";                                 //業者名
        $strSQL .= "         ,M_C_ZAIKO.HNKANRI_GK" . "\r\n";
        //管理原価（本社用）
        $strSQL .= "         ,M_RIE.SRY_KNR_PCS" . "\r\n";
        //車輌管理原価
        $strSQL .= "         ,M_RIE.MUC_SUM" . "\r\n";
        //車輌管理原価
        $strSQL .= "         ,M_RIE.REB_PCS" . "\r\n";
        //再生原価

        $strSQL .= "         ,M_SYOHIKIJN31.GK SYOHIKIJN31" . "\r\n";
        //登録諸費基準
        //2006/10/16 UPDATE Start 登録諸費用をK543000番の値をもってくるように変更
        //strSQL.Append("         ,M_SYOHIKIJN68.GK SYOHIKIJN68"."\r\n";                            //登録諸費基準
        //                        //登録諸費基準
        //strSQL.Append("         ,(CASE WHEN M_CHU.NAU_KB = '1'"."\r\n";                           //登録諸費基準新車
        //strSQL.Append("            THEN (NVL(M_SYOHIKIJN31.GK,0)-NVL(M_SYOHIKIJN68.GK,0)+NVL(M_RSIKIN.SHIKIN_KNR_RYOKIN,0))"."\r\n";
        //strSQL.Append("            ELSE (NVL(M_SYOHIKIJN31.GK,0)-NVL(M_SYOHIKIJN68.GK,0)+NVL(M_AZU_KENSA.GK,0)+NVL(M_AZU_SYAKO.GK,0)"."\r\n";
        //strSQL.Append("                  +NVL(M_RSIKIN.SHIKIN_KNR_RYOKIN,0)) END) SYOHIKIJN"."\r\n";                   //登録諸費基準chuuko
        $strSQL .= "         ,NVL(M_SYOHIKIJN31.GK,0) SYOHIKIJN" . "\r\n";
        //登録諸費用3%基準
        //2006/10/16 UPDATE End

        //                        '登録諸費基準NEW
        $strSQL .= "         ,NVL(M_SYOHIKIJN31.GK,0) SYOHIKIJN_NEW" . "\r\n";

        //                        '登録諸費利益NEW
        $strSQL .= "         ,NVL(M_SYOHIKIJN31.RIEKI,0) RIEKI_NEW" . "\r\n";

        $strSQL .= "         ,DECODE(M_CHU.NAU_KB,'1',H_KASO.FZK_GNK,FZH_KNR_PCS) FZK_GNK" . "\r\n";
        //付属品原価　　新車-架装明細：部品社内原価　中古車-利益明細：付属品管理原価
        $strSQL .= "         ,DECODE(M_CHU.NAU_KB,'1',H_KASO.TKS_GNK,TKB_KSH_KNR_PCS) TKS_GNK" . "\r\n";
        //特別架装原価　新車-架装明細：外注原価　　　中古車-利益明細：特別架装品管理原価
        $strSQL .= "         ,H_KASO.KASO_CNT" . "\r\n";
        //架装明細：件数

        $strSQL .= "         ,M_RIE.CMN_NO RIE_CMN_NO" . "\r\n";
        //利益計算ﾃﾞｰﾀ注文書№

        $strSQL .= "         ,TESURYO_M.SITO" . "\r\n";
        //                       '割賦手数料
        $strSQL .= "         ,(CASE WHEN TGT_MSU = 1" . "\r\n";
        $strSQL .= "                THEN TRUNC(TRUNC(NVL(M_CHU.KAP_MOT_KIN,0) * 1000 * NVL(TESURYO_M.SITO,0)  / 36500 * NVL(TESURYO_M.NEN_RT,0)) / 1000) + NVL(TESURYO_M.TESURYO,0)" . "\r\n";
        $strSQL .= "                ELSE TRUNC(TRUNC(NVL(M_CHU.KAP_MOT_KIN,0) * 1000 * NVL(TESURYO_M.SYANAI_RT,0) / 100) / 1000) + NVL(TESURYO_M.TORITATERYO,0)" . "\r\n";
        $strSQL .= "                END) KAP_TESURYO_KJN" . "\r\n";
        $strSQL .= "         ,(CASE WHEN M_CHU.TOU_DT < M_CHU.GKN_KAI_YOT_DT THEN  TO_DATE(M_CHU.GKN_KAI_YOT_DT,'YYYYMMDD') -TO_DATE(M_CHU.TOU_DT,'YYYYMMDD') ELSE 0 END) PN_NISU" . "\r\n";
        //                       'ﾍﾟﾅﾙﾃｨ
        $strSQL .= "         ,(CASE WHEN M_CHU.TOU_DT < M_CHU.GKN_KAI_YOT_DT" . "\r\n";
        $strSQL .= "                THEN TRUNC(TRUNC((M_CHU.SHR_GKN_DPS+M_CHU.KRJ_MOT_KIN+M_CHU.KAP_MOT_KIN) * 1000 *" . "\r\n";
        $strSQL .= "                    (TO_DATE(M_CHU.GKN_KAI_YOT_DT,'YYYYMMDD') - TO_DATE(M_CHU.TOU_DT,'YYYYMMDD'))  * TESURYO_M.NEN_RT / 36500) / 1000)" . "\r\n";
        $strSQL .= "               ELSE 0 END)  PENALTY" . "\r\n";

        $strSQL .= "         ,TESURYO_M.NEN_RT " . "\r\n";
        //割賦手数料年利率
        $strSQL .= "         ,TESURYO_M.TESURYO" . "\r\n";
        //手数料
        $strSQL .= "         ,TESURYO_M.SYANAI_RT" . "\r\n";
        //社内率
        $strSQL .= "         ,TESURYO_M.TORITATERYO" . "\r\n";
        //取立料
        $strSQL .= "         ,TESURYO_M.CAL_NISU" . "\r\n";
        //算出日数

        $strSQL .= "         ,TO_CHAR(M_CHU.SSU_EDT_HDK,'YYYYMMDD')　SSU_EDT_HDK" . "\r\n";
        //更新年月日
        //---20150917 li UPD S.
        //$strSQL .= "         ,TO_CHAR(M_CHU.REC_UPD_DT,'YYYY/MM/DD HH24:mm:ss')　REC_UPD_DT" . "\r\n";
        //最終更新処理日
        //$strSQL .= "         ,TO_CHAR(M_CHU.REC_CRE_DT,'YYYY/MM/DD HH24:mm:ss') REC_CRE_DT" . "\r\n";
        $strSQL .= "         ,TO_CHAR(M_CHU.REC_UPD_DT,'YYYY/MM/DD HH24:MI:SS')　REC_UPD_DT" . "\r\n";
        //最終更新処理日
        $strSQL .= "         ,TO_CHAR(M_CHU.REC_CRE_DT,'YYYY/MM/DD HH24:MI:SS') REC_CRE_DT" . "\r\n";
        //---20150917 li UPD E.
        //作成処理日
        //strSQL.Append("         ,TO_CHAR(M_RIE.REC_UPD_DT,'YYYYMMDD')　RIE_REC_UPD_DT"."\r\n"; //利益計算最終更新処理日
        $strSQL .= "         ,TO_CHAR(W_CMN.GET_DATE,'YYYYMMDD')　JHN_REC_UPD_DT" . "\r\n";
        //注文書以外変更日

        //2009/12/21 INS Start
        $strSQL .= "         ,K_OKY.CSRRANK K_CSRRANK" . "\r\n";
        //契約者ｶﾃｺﾞﾘｰﾗﾝｸ
        $strSQL .= "         ,M_OKY.CSRRANK M_CSRRANK" . "\r\n";
        //使用者ｶﾃｺﾞﾘｰﾗﾝｸ
        //2009/12/21 INS End

        //注文書ファイル
        $strSQL .= "FROM      M41E10 M_CHU" . "\r\n";
        $strSQL .= "INNER JOIN WK_CMNNO W_CMN" . "\r\n";
        $strSQL .= "ON         W_CMN.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //2007/04/03 INS START
        $strSQL .= "LEFT JOIN HURIBUSYOCNV CNV" . "\r\n";
        $strSQL .= "ON        CNV.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //2007/04/03 INS END
        //新車在庫情報
        $strSQL .= "LEFT JOIN M27A02 M_S_ZAIKO" . "\r\n";
        $strSQL .= "ON        M_S_ZAIKO.JUCHU_NO = M_CHU.JTU_NO" . "\r\n";
        $strSQL .= "AND       M_S_ZAIKO.DEL_KB IS NULL" . "\r\n";
        //中古車在庫情報
        $strSQL .= "LEFT JOIN M41B02 M_C_ZAIKO" . "\r\n";
        $strSQL .= "ON        M_C_ZAIKO.SEIRI_NO = M_CHU.CKO_CAR_SER_NO" . "\r\n";
        $strSQL .= "AND       M_C_ZAIKO.SEIRI_SEQ = M_CHU.CKO_CAR_SER_SEQ" . "\r\n";
        $strSQL .= "AND       M_C_ZAIKO.JYOTAIKBN <> '9'" . "\r\n";
        //注文書諸費用明細データ(新車の自動車税)
        //2007/04/03 UPD START   結合できるﾃｰﾌﾞﾙの最大値のため、新車と中古車を1つに
        //strSQL.Append("LEFT JOIN M41E68 S_JIDOSYA"."\r\n";
        //strSQL.Append("ON        S_JIDOSYA.CMN_NO = M_CHU.CMN_NO"."\r\n";
        //strSQL.Append("AND       S_JIDOSYA.SCO_ITM_NO = 'K111001'"."\r\n";
        $strSQL .= "LEFT JOIN (SELECT CMN_NO" . "\r\n";
        $strSQL .= "           ,      (CASE WHEN SCO_ITM_NO = 'K111001' THEN NVL(SCO_GK_ZEINK,0) ELSE 0 END) SINSYA" . "\r\n";
        $strSQL .= "           ,      (CASE WHEN SCO_ITM_NO = 'K112001' THEN NVL(SCO_GK_ZEINK,0) ELSE 0 END) CHUKO" . "\r\n";
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "           WHERE  SCO_ITM_NO = 'K112001' OR SCO_ITM_NO = 'K111001'" . "\r\n";
        $strSQL .= "          ) JIDOSYA" . "\r\n";
        $strSQL .= "ON        JIDOSYA.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //2007/04/03 UPD END
        //注文書諸費用明細データ(新車の取得税)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "           FROM   M41E68" . "\r\n";
        //20190731 UPDATE START
//			$strSQL .= "           WHERE  (SCO_ITM_NO = 'K111002'" . "\r\n";
//			$strSQL .= "           OR      SCO_ITM_NO = 'K111006')" . "\r\n";
        $strSQL .= "           WHERE  (SCO_ITM_NO = 'K111000'" . "\r\n";
        $strSQL .= "           OR      SCO_ITM_NO = 'K111002' " . "\r\n";
        $strSQL .= "           OR      SCO_ITM_NO = 'K111006')" . "\r\n";
        //20190731 UPDATE END
        $strSQL .= "           GROUP BY CMN_NO) S_SYUTOKU" . "\r\n";
        $strSQL .= "ON        S_SYUTOKU.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //注文書諸費用明細データ(新車の重量税)
        $strSQL .= "LEFT JOIN M41E68 S_JYUURYO" . "\r\n";
        $strSQL .= "ON        S_JYUURYO.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        $strSQL .= "AND       S_JYUURYO.SCO_ITM_NO = 'K111004'" . "\r\n";
        //注文書諸費用明細データ(新車の自賠責保険料)"."\r\n";
        $strSQL .= "LEFT JOIN M41E68 S_JIBAIHO" . "\r\n";
        $strSQL .= "ON        S_JIBAIHO.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        $strSQL .= "AND       S_JIBAIHO.SCO_ITM_NO = 'K111005'" . "\r\n";

        //注文書諸費用明細データ(新車の登録諸費)
        //2006/10/30 UPDATE Start    K150001に限っては消費税も登録諸費用に含める
        //strSQL.Append("LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) SCO_GK_ZEINK"."\r\n";
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(CASE WHEN SCO_ITM_NO = 'K150001' THEN NVL(SCO_GK_ZKM,0) ELSE NVL(SCO_GK_ZEINK,0) END) SCO_GK_ZEINK" . "\r\n";
        //2006/10/30 UPDATE End
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "            WHERE  (SCO_RIE_KSN_KUM_CLF_CD = 'K12'" . "\r\n";
        $strSQL .= "            　　OR  SCO_RIE_KSN_KUM_CLF_CD = 'K15')" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150021'" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K153000'" . "\r\n";
        //2010/07/26 INS Start   //K150032が延長保証になったため（パックＤＥ７５３が延長保証に変わるため）
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150032'" . "\r\n";
        //2010/07/26 INS end
        $strSQL .= "              AND  SCO_ITM_NO <> 'K123204'" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K123200'" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K123201'" . "\r\n";
        //2008/03/03 INS Start   登録諸費用にはパックＤＥメンテ、資金管理費用は含めない
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150031'" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150001'" . "\r\n";
        //2008/03/03 INS End
        $strSQL .= "           GROUP BY CMN_NO) S_SYOKEI" . "\r\n";
        $strSQL .= "ON        S_SYOKEI.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //注文書諸費用明細データ(新車の登録諸費税)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SHZ_GK) SHZ_GK" . "\r\n";
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "            WHERE  (SCO_RIE_KSN_KUM_CLF_CD = 'K12'" . "\r\n";
        $strSQL .= "            　　OR  SCO_RIE_KSN_KUM_CLF_CD = 'K15')" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150021'" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K153000'" . "\r\n";
        //2010/07/26 INS Start   'K150032が延長保証になったため（パックＤＥ７５３が延長保証に変わるため）
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150032'" . "\r\n";
        //2010/07/26 INS end
        $strSQL .= "              AND  SCO_ITM_NO <> 'K123204'" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K123200'" . "\r\n";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K123201'" . "\r\n";
        //2006/10/30 UPDATE Start    'K150001に限っては消費税は登録諸費用に含めない
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150001'" . "\r\n";
        //2008/03/03 INS Start   登録諸費用消費税にはパックＤＥメンテの値を含めない
        $strSQL .= "              AND  SCO_ITM_NO <> 'K150031'" . "\r\n";
        //2008/03/03 INS End
        //2006/10/30 UPDATE End
        $strSQL .= "           GROUP BY CMN_NO) S_SYOKEIZEI" . "\r\n";
        $strSQL .= "ON        S_SYOKEIZEI.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //注文書諸費用明細データ(預かり法定費)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "            WHERE  SCO_RIE_KSN_KUM_CLF_CD = 'K13'";
        $strSQL .= "              AND  SCO_ITM_NO <> 'K130001'";
        $strSQL .= "           GROUP BY CMN_NO) M_HOUTEIHI" . "\r\n";
        $strSQL .= "ON        M_HOUTEIHI.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //注文書諸費用明細データ(JAF)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "           WHERE  SCO_ITM_NO IN" . "\r\n";
        $strSQL .= "                   ('K150021')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_JAF" . "\r\n";
        $strSQL .= "ON        M_JAF.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //注文書諸費用明細データ(ﾘｻｲｸﾙ預託金)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "           WHERE  SCO_ITM_NO IN" . "\r\n";
        $strSQL .= "                  ('K130001')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_RYOTAKU" . "\r\n";
        $strSQL .= "ON        M_RYOTAKU.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //注文書諸費用明細データ(中古車の自動車税)
        //2007/04/03 DEL 移動    結合できるﾃｰﾌﾞﾙの最大値のため、新車と中古を1つに
        //strSQL.Append("LEFT JOIN M41E68 C_JIDOSYA"."\r\n";
        //strSQL.Append("ON        C_JIDOSYA.CMN_NO = M_CHU.CMN_NO"."\r\n";
        //strSQL.Append("AND       C_JIDOSYA.SCO_ITM_NO = 'K112001'"."\r\n";
        //2007/04/03 DEL
        //注文書諸費用明細データ(中古車の取得税)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) SCO_GK_ZEINK" . "\r\n";
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "           WHERE  (SCO_ITM_NO = 'K112002'" . "\r\n";
        //20190731 UPDATE START
//			$strSQL .= "           OR      SCO_ITM_NO = 'K112008' OR SCO_ITM_NO = 'K112009' OR SCO_ITM_NO = 'K_112010')" . "\r\n";
        $strSQL .= "           OR      SCO_ITM_NO = 'K112008' OR SCO_ITM_NO = 'K112009' OR SCO_ITM_NO = 'K_112010'  OR SCO_ITM_NO = 'K_112000'  OR SCO_ITM_NO = 'K_112003')" . "\r\n";
        //20190731 UPDATE END
        $strSQL .= "           GROUP BY CMN_NO) C_SYUTOKU" . "\r\n";
        $strSQL .= "ON        C_SYUTOKU.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //注文書諸費用明細データ(中古車の重量税)
        $strSQL .= "LEFT JOIN M41E68 C_JYUURYO" . "\r\n";
        $strSQL .= "ON        C_JYUURYO.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        $strSQL .= "AND       C_JYUURYO.SCO_ITM_NO = 'K112004'" . "\r\n";
        //注文書諸費用明細データ(中古車の自賠責保険料)
        $strSQL .= "LEFT JOIN M41E68 C_JIBAIHO" . "\r\n";
        $strSQL .= "ON        C_JIBAIHO.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        $strSQL .= "AND       C_JIBAIHO.SCO_ITM_NO = 'K112005'" . "\r\n";
        //注文書諸費用明細データ(中古車の未経過自動車税)
        $strSQL .= "LEFT JOIN M41E68 C_MJIDOSYA" . "\r\n";
        $strSQL .= "ON        C_MJIDOSYA.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        $strSQL .= "AND       C_MJIDOSYA.SCO_ITM_NO = 'K112007'" . "\r\n";
        //注文書諸費用明細データ(中古車の未経過自賠責)
        $strSQL .= "LEFT JOIN M41E68 C_MJIBAIHO" . "\r\n";
        $strSQL .= "ON        C_MJIBAIHO.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        $strSQL .= "AND       C_MJIBAIHO.SCO_ITM_NO = 'K112006'" . "\r\n";
        //利益計算データ
        $strSQL .= "LEFT JOIN M41E30 M_RIE" . "\r\n";
        $strSQL .= "ON        M_RIE.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(登録諸費用3検査)27
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE (M_MEI.SCO_ITM_NO = 'K123000'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123001'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123500'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123501'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K124000'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K124001'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K124002')" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123060'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_TOUROKU_KENSA" . "\r\n";
        $strSQL .= "ON        M_TOUROKU_KENSA.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(登録諸費用3車庫証明)29
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE (M_MEI.SCO_ITM_NO = 'K123010'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123011'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123510'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123511'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K124010'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K124011')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_TOUROKU_SYAKO" . "\r\n";
        $strSQL .= "ON        M_TOUROKU_SYAKO.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(登録諸費用3納車費用)30
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE (M_MEI.SCO_ITM_NO = 'K123020'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123203'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123520'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K124020')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_TOUROKU_NOUSYA" . "\r\n";
        $strSQL .= "ON        M_TOUROKU_NOUSYA.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細明細データ(登録諸費用3下取諸手続)31
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE  M_MEI.SCO_ITM_NO = 'K123030'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123530'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_TOUROKU_SITA" . "\r\n";
        $strSQL .= "ON        M_TOUROKU_SITA.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(登録諸費用3査定料)32
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE (M_MEI.SCO_ITM_NO = 'K123050'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123051'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123550')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_TOUROKU_SATEI" . "\r\n";
        $strSQL .= "ON        M_TOUROKU_SATEI.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(自光式)33
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE  M_MEI.SCO_ITM_NO = 'K123080'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_TOUROKU_JIKOU" . "\r\n";
        $strSQL .= "ON         M_TOUROKU_JIKOU.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(登録諸費用その他)34
        //2007/07/06 UPD Start   パックDEメンテ追加により、パックDE753は別項目として保持し、
        //パックＤＥメンテもその他に含めるのではなく別項目とする(売上データ参照時はパックＤＥ753・メンテを含めてその他で表示)
        //strSQL.Append("LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK"."\r\n";
        //2010/07/25 UPD Start
        //''strSQL.Append("LEFT JOIN (SELECT CMN_NO, SUM(CASE WHEN SCO_ITM_NO = 'K153000' THEN SCO_GK_ZEINK ELSE 0 END) PACK753"."\r\n";
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(CASE WHEN SCO_ITM_NO IN ('K153000','K150032') THEN SCO_GK_ZEINK ELSE 0 END) PACK753" . "\r\n";
        //2010/07/25 UPD End
        $strSQL .= "           ,      SUM(CASE WHEN SCO_ITM_NO = 'K150031' THEN SCO_GK_ZEINK ELSE 0 END) PACKMENTE" . "\r\n";
        $strSQL .= "           ,      SUM(CASE WHEN SCO_ITM_NO IN ('K123070','K123110') THEN SCO_GK_ZEINK ELSE 0 END) SONOTAGK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE  M_MEI.SCO_ITM_NO = 'K123070'" . "\r\n";
        //2007/07/06 UPD Start   パックＤＥ７５３のアイテム№がK123090→K153000に変更
        //strSQL.Append("              OR  M_MEI.SCO_ITM_NO = 'K123090'"."\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K153000'" . "\r\n";
        //パックＤＥ753
        //2007/07/06 UPD End
        //2010/07/26 INS Start   K150032が延長保証になったため（パックＤＥ７５３が延長保証に変わるため）
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K150032'" . "\r\n";
        //延長保証
        //2010/07/26 INS end
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K150031'" . "\r\n";
        //2007/07/06 INS パックＤＥメンテ
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K123110'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_TOUROKU_TA" . "\r\n";
        $strSQL .= "ON         M_TOUROKU_TA.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //2007/07/06 UPD End

        //諸費用明細データ(預り法定費用検査)35
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE (M_MEI.SCO_ITM_NO = 'K133000'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133001'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133031'" . "\r\n";
        //---20151008 li INS S.
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133542'" . "\r\n";
        //---20151008 li INS E.
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133500'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133501'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133502'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133503')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_AZU_KENSA" . "\r\n";
        $strSQL .= "ON        M_AZU_KENSA.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(預り法定費用車庫証明)37
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE (M_MEI.SCO_ITM_NO = 'K133010'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133011'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133510'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133511')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_AZU_SYAKO" . "\r\n";
        $strSQL .= "ON        M_AZU_SYAKO.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(預り法定費用下取)38
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E68   M_MEI" . "\r\n";
        $strSQL .= "           WHERE (M_MEI.SCO_ITM_NO = 'K133020'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133050'" . "\r\n";
        $strSQL .= "              OR  M_MEI.SCO_ITM_NO = 'K133520')" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_AZU_SITA" . "\r\n";
        $strSQL .= "ON        M_AZU_SITA.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(ｷｯｸﾊﾞｯｸ)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(GK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E31   M_MEI" . "\r\n";
        $strSQL .= "           WHERE  M_MEI.SCO_ITM_NO = 'K123200'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_KB" . "\r\n";
        $strSQL .= "ON         M_KB.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //諸費用明細データ(そのた紹介料)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(GK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E31   M_MEI" . "\r\n";
        $strSQL .= "           WHERE  M_MEI.SCO_ITM_NO = 'K543007'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_ETCSYOKAI" . "\r\n";
        $strSQL .= "ON         M_ETCSYOKAI.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //注文書諸費用明細データ(資金管理料)
        //2006/10/16 UPDATE Start    消費税抜きから消費税込みに変更
        //strSQL.Append("LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZEINK) SHIKIN_KNR_RYOKIN,SUM(SHZ_GK) SHIKIN_KNR_SHZ"."\r\n";
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(SCO_GK_ZKM) SHIKIN_KNR_RYOKIN,SUM(SHZ_GK) SHIKIN_KNR_SHZ" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "           FROM   M41E68" . "\r\n";
        $strSQL .= "           WHERE  SCO_ITM_NO = 'K150001'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_RSIKIN" . "\r\n";
        $strSQL .= "ON        M_RSIKIN.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //注文書諸費用明細データ(任保収手)
        $strSQL .= "LEFT JOIN (SELECT CMN_NO, SUM(GK) GK" . "\r\n";
        $strSQL .= "           FROM   M41E31" . "\r\n";
        $strSQL .= "           WHERE  SCO_ITM_NO = 'K533002'" . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) M_NINTE" . "\r\n";
        $strSQL .= "ON        M_NINTE.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //注文書諸費用明細データ(登録諸費基準)
        //2006/10/16 DELETE Start    '登録諸費基準はK543000番からのみ抽出するように変更のため
        //strSQL.Append("LEFT JOIN  (SELECT CMN_NO, SUM(SCO_GK_ZEINK) GK"."\r\n";
        //strSQL.Append("                    FROM M41E68"."\r\n";
        //strSQL.Append("                   WHERE SUBSTR(SCO_ITM_NO,1,3) = 'K13'"."\r\n";
        //strSQL.Append("                     AND SCO_ITM_NO <> 'K130001'"."\r\n";
        //strSQL.Append("                     AND SCO_ITM_NO <> 'K133031'"."\r\n";
        //strSQL.Append("                     AND SCO_ITM_NO <> 'K133050'"."\r\n";
        //strSQL.Append("                     AND SCO_ITM_NO <> 'K133520'"."\r\n";
        //strSQL.Append("                   GROUP BY CMN_NO) M_SYOHIKIJN68"."\r\n";
        //strSQL.Append("ON   M_SYOHIKIJN68.CMN_NO = M_CHU.CMN_NO"."\r\n";
        //2006/10/16 DELETE End

        $strSQL .= "LEFT JOIN (SELECT CMN_NO" . "\r\n";
        //2006/10/16 UPDATE Start
        //strSQL.Append("           ,SUM(CASE WHEN  SUBSTR(SCO_ITM_NO,1,4) = 'K543' AND SCO_ITM_NO <> 'K543005' THEN NVL(GK,0) ELSE 0 END) GK"."\r\n";
        $strSQL .= "           ,SUM(CASE WHEN SCO_ITM_NO = 'K543000' THEN NVL(GK,0) ELSE 0 END) GK" . "\r\n";
        //2006/10/16 UPDATE End
        $strSQL .= "           ,SUM(CASE WHEN  RIE_KSN_TBL_DSP_PSI = '3' AND SCO_ITM_NO <> 'K123200' THEN NVL(GK,0) ELSE 0 END) RIEKI" . "\r\n";
        $strSQL .= "           FROM(M41E31)" . "\r\n";
        $strSQL .= "          GROUP BY CMN_NO ) M_SYOHIKIJN31" . "\r\n";
        $strSQL .= "ON   M_SYOHIKIJN31.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //利益計算データ明細データ(対策費)
        //strSQL.Append("LEFT JOIN (SELECT CMN_NO, SUM(GK) GK"."\r\n";
        //strSQL.Append("           FROM   M41E31   M_MEI"."\r\n";
        //strSQL.Append("           WHERE  SUBSTR(M_MEI.SCO_ITM_NO,1,3) = 'K51'"."\r\n";
        //strSQL.Append("           GROUP BY CMN_NO) M_TAISAKUHI"."\r\n";
        //strSQL.Append("ON        M_TAISAKUHI.CMN_NO = M_CHU.CMN_NO"."\r\n";

        //注文書紹介者データ
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "          (SELECT CMN_NO" . "\r\n";
        $strSQL .= "                 ,MIN(SKP_NM1) SKP_NM1" . "\r\n";
        $strSQL .= "                 ,MIN(SHZ_RT_KB) HNB_SHZ_RT_KB" . "\r\n";
        $strSQL .= "                 ,MIN(SHZ_RT) HNB_SHZ_RT" . "\r\n";
        $strSQL .= "                 ,MIN(SKP_CD) SKP_CD" . "\r\n";
        $strSQL .= "                 ,MIN(SKP_KB) SKP_KB" . "\r\n";
        $strSQL .= "                 ,SUM(SKI_RYO) HNB_SKI_RYO" . "\r\n";
        $strSQL .= "                 ,SUM(SHZ_GKU) HNB_SHZ_GKU" . "\r\n";
        $strSQL .= "             FROM M41E13 " . "\r\n";
        $strSQL .= "            GROUP BY  CMN_NO" . "\r\n";
        $strSQL .= "          ) M_SYOKAI" . "\r\n";
        $strSQL .= "ON        M_SYOKAI.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //新車原価マスタ
        $strSQL .= " LEFT JOIN ";
        $strSQL .= "          (SELECT TOA_NAME,HTA_PRC,KTN_PCS,SIK_PCS,TYK_PCS,F_PCS FROM HGENKAMST M_GENKA) M_GENKA" . "\r\n";
        $strSQL .= " ON        M_GENKA.TOA_NAME = SUBSTR(M_CHU.HBSS_CD,1,5)||SUBSTR(M_CHU.HBSS_CD,8,1)" . "\r\n";
        $strSQL .= " AND       M_GENKA.HTA_PRC = M_CHU.SRY_HT_PRC_ZEINK" . "\r\n";

        //車種マスタ
        $strSQL .= " LEFT JOIN ";
        $strSQL .= "          (SELECT DISTINCT M_GENKA.TOA_NAME,M_GENKA.ID,M_SYASYU.SS_CD,M_SYASYU.SS_NAME,M_SYASYU.UCOYA_CD FROM HGENKAMST M_GENKA" . "\r\n";
        $strSQL .= "           LEFT JOIN ";
        $strSQL .= "                  (SELECT SS_CD,SS_NAME,UCOYA_CD  FROM HSYASYUMST) M_SYASYU" . "\r\n";
        $strSQL .= "                  ON M_SYASYU.UCOYA_CD = M_GENKA.ID) M_SYASYUCD" . "\r\n";
        $strSQL .= " ON        M_SYASYUCD.TOA_NAME = SUBSTR(M_CHU.HBSS_CD,1,5)||SUBSTR(M_CHU.HBSS_CD,8,1)" . "\r\n";

        //新車原価マスタUC親取得
        //strSQL.Append(" LEFT JOIN ")
        //strSQL.Append("          (SELECT DISTINCT TOA_NAME,ID FROM HGENKAMST) M_GENKAUC"."\r\n";
        //strSQL.Append(" ON        M_GENKAUC.TOA_NAME = SUBSTR(M_CHU.HBSS_CD,1,5)||SUBSTR(M_CHU.HBSS_CD,8,1)"."\r\n";

        //架装明細テーブル
        $strSQL .= "LEFT JOIN (SELECT CMN_NO,COUNT(*) KASO_CNT,MAX(KASOUNO) KASOUNO, SUM(DECODE(FUZOKUHINKBN,'0',NVL(BUHIN_SYANAI_GEN,0)+NVL(GAICYU_GEN,0),0)) FZK_GNK" . "\r\n";
        $strSQL .= ",SUM(DECODE(FUZOKUHINKBN,'1',NVL(BUHIN_SYANAI_GEN,0)+NVL(GAICYU_GEN,0),0)) TKS_GNK" . "\r\n";
        $strSQL .= "           FROM   HKASOUMEISAI " . "\r\n";
        $strSQL .= "           GROUP BY CMN_NO) H_KASO" . "\r\n";
        $strSQL .= "ON        H_KASO.CMN_NO = M_CHU.CMN_NO" . "\r\n";

        //車種マスタ
        //strSQL.Append(" LEFT JOIN ")
        //strSQL.Append("          (SELECT *  FROM HSYASYUMST) M_SYASYU"."\r\n";
        //strSQL.Append(" ON        M_SYASYU.UC = SUBSTR(M_CHU.HBSS_CD,1,5)||SUBSTR(M_CHU.HBSS_CD,8,1)"."\r\n";

        //手形据置日数ﾃﾞｰﾀ
        //strSQL.Append("LEFT JOIN HBILLSITOD BILL_D"."\r\n";
        //strSQL.Append("ON        BILL_D.CMN_NO = M_CHU.CMN_NO"."\r\n";

        //お客様データ
        $strSQL .= "LEFT JOIN M41C01 M_OKY" . "\r\n";
        //2009/12/10 UPD Start   '名義人誕生日に契約者の誕生日を取得するようになっていたので変更
        $strSQL .= "ON        M_OKY.DLRCSRNO = M_CHU.SIY_CUS_NO" . "\r\n";
        //strSQL.Append("ON       M_OKY.DLRCSRNO = M_CHU.KYK_CUS_NO"."\r\n";
        //2009/12/10 UPD End
        //陸事マスタ
        $strSQL .= "LEFT JOIN HRIKUJI M_RKJ" . "\r\n";
        $strSQL .= "ON       M_RKJ.RIKUJI_CD = SUBSTR(M_CHU.TOURK_NO,1,4)" . "\r\n";
        //業者マスタ
        //strSQL.Append("LEFT JOIN M27M08 M_GYOSYA"."\r\n";
        //strSQL.Append("ON       M_GYOSYA.DAIRITN_CD = DECODE(NVL(M_CHU.DAIRITN_CD,''),'',NVL(M_CHU.DAIRITN_CD,''),"."\r\n";

        //手数料マスタ
        //strSQL.Append(",(SELECT TESU_M.* FROM HTESURYO TESU_M"."\r\n";
        //strSQL.Append("   INNER Join"."\r\n";
        //strSQL.Append("         (SELECT MAX(KIJYUN_DT) KIJYUN_DT"."\r\n";
        //strSQL.Append("            FROM   HTESURYO TEST_V,M41E10 M_CHU"."\r\n";
        //strSQL.Append("           WHERE DECODE(M_CHU.JKN_HKD,NULL,M_CHU.TOU_DT,M_CHU.JKN_HKD) >= TEST_V.KIJYUN_DT ) V"."\r\n";
        //strSQL.Append("      ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT) TESURYO_M"."\r\n";

        $strSQL .= "LEFT JOIN (SELECT TESU_M.*,V.CMN_NO,V.SITO FROM HTESURYO TESU_M" . "\r\n";
        $strSQL .= "        INNER Join" . "\r\n";
        $strSQL .= "         (SELECT MAX(KIJYUN_DT) KIJYUN_DT,M_CHU.CMN_NO ,MAX(BILL_D.SITO) SITO" . "\r\n";
        $strSQL .= "            FROM   HTESURYO TEST_V,M41E10 M_CHU,HBILLSITOD BILL_D" . "\r\n";
        $strSQL .= "--           WHERE DECODE(M_CHU.JKN_HKD,NULL,M_CHU.TOU_DT,M_CHU.JKN_HKD) >= TEST_V.KIJYUN_DT ) V" . "\r\n";
        $strSQL .= "        WHERE(M_CHU.TOU_DT >= TEST_V.KIJYUN_DT)" . "\r\n";
        //strSQL.Append("        WHERE(M_CHU.SRY_URG_DT >= TEST_V.KIJYUN_DT)"."\r\n";
        $strSQL .= "             AND M_CHU.CMN_NO = BILL_D.CMN_NO(+)" . "\r\n";
        $strSQL .= "           GROUP BY M_CHU.CMN_NO ) V" . "\r\n";
        $strSQL .= "      ON  TESU_M.KIJYUN_DT = V.KIJYUN_DT" . "\r\n";
        $strSQL .= "    ) TESURYO_M" . "\r\n";
        $strSQL .= "ON  TESURYO_M.CMN_NO = M_CHU.CMN_NO" . "\r\n";
        //2009/12/21 INS Start
        $strSQL .= "         LEFT JOIN M41C01 K_OKY" . "\r\n";
        $strSQL .= "         ON       K_OKY.DLRCSRNO = M_CHU.KYK_CUS_NO" . "\r\n";
        //2009/12/21 INS End
        // $strSQL .= "         where M_CHU.CMN_NO='361N103131' " . "\r\n";
        $strSQL .= "ORDER BY UC_NO" . "\r\n";
        //$this->log($strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新車中古車下取データ取得(SQL)
    //関 数 名：fncSelectSql
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL文
    //処理説明：新車中古車下取データ取得SQL)
    //**********************************************************************
    function fncSitSelectSQL($strCmnNo)
    {
        $strSQL = "";
        $strSQL .= " SELECT RPAD(M_SIT.CMN_NO,10)" . "\r\n";
        $strSQL .= "          || M_SIT.TRA_CAR_SEQ_NO SEIRI_NO" . "\r\n";
        //下取車整理№
        $strSQL .= "       ,M_SIT.TRA_CAR_SEQ_NO" . "\r\n";
        //下取車整理№連番
        $strSQL .= "       ,M_SIT.BRD_CD" . "\r\n";
        //銘柄コード
        $strSQL .= "       ,SUBSTR(M_SIT.SYD_TOU_YM,1,4)" . "\r\n";
        //初年度登録の年
        $strSQL .= "       ,M_SIT.SDI_KAT" . "\r\n";
        //車台型式
        $strSQL .= "       ,M_SIT.CAR_NO" . "\r\n";
        //カー№
        $strSQL .= "       ,M_SIT.VCLNM" . "\r\n";
        //通称車名
        $strSQL .= "       ,M_SIT.SITEI_NO" . "\r\n";
        //指定番号
        $strSQL .= "       ,M_SIT.RUIBETU_NO" . "\r\n";
        //類別番号
        $strSQL .= "       ,M_SIT.SYD_TOU_YM" . "\r\n";
        //初年度登録
        $strSQL .= "       ,M_SIT.SYAKEN_EXP_DT" . "\r\n";
        //車検満了日
        $strSQL .= "       ,M_SIT.TOU_NO_RKJ_CD" . "\r\n";
        //登録ＮＯ（陸事コード）
        $strSQL .= "       ,M_RKJ.RIKUJI_NM" . "\r\n";
        //陸事名
        $strSQL .= "       ,M_SIT.VCLRGTNO_SYU" . "\r\n";
        //登録No－種別
        $strSQL .= "       ,M_SIT.TOU_NO_KNA" . "\r\n";
        //登録ＮＯ（かな）
        $strSQL .= "       ,M_SIT.TOU_NO_RBN" . "\r\n";
        //登録ＮＯ（連番）
        $strSQL .= "       ,M_SIT.TRA_GK" . "\r\n";
        //下取金額

        //2006/07/15 UPDATE START
        //strSQL.Append("       ,M_SIT.SATEI_GK" & vbCrLf)                                  //査定金額
        //strSQL.Append("       ,DECODE(M_ZAIKO.SIR_GK,NULL,M_SIT.TRA_GK,NVL(M_ZAIKO.SIR_GK,0)+NVL(M_ZAIKO.SIR_SZEI_GK,0)) SATEI_GK" & vbCrLf)                                  //査定金額
        //  2006/09/08 UPDATE START //奨励金で仕入額が消費税抜きになっているため、引取先区分が="0"(個人)の場合は消費税をプラスするよう変更
        //    2006/09/29 UPDATE Start //R4で受入入力を行ったのち削除を行うと、NULLではなく0で仕入額が更新されてしまうため変更
        //strSQL.Append("       ,(CASE WHEN M_ZAIKO.SIR_GK IS NULL" & vbCrLf)
        $strSQL .= "       ,(CASE WHEN NVL(M_ZAIKO.SIR_GK,0) = 0" . "\r\n";
        //    2006/09/29 UPDATE End
        $strSQL .= "              THEN NVL(M_SIT.TRA_GK,0)" . "\r\n";
        $strSQL .= "              ELSE (CASE WHEN NVL(M_ZAIKO.HIKI_KBN,' ') = '0'" . "\r\n";
        //    2006/09/29 UPDATE Start
        //strSQL.Append("                         THEN NVL(M_ZAIKO.SIR_GK,0) + NVL(M_ZAIKO.SYARYO_SZEI_GK,0)" & vbCrLf)
        $strSQL .= "                         THEN NVL(M_ZAIKO.SIR_GK,0) + NVL(M_ZAIKO.SIR_SZEI_GK,0)" . "\r\n";
        //    2006/09/29 UPDATE End
        $strSQL .= "                         ELSE NVL(M_ZAIKO.SIR_GK,0)" . "\r\n";
        $strSQL .= "                    END)" . "\r\n";
        $strSQL .= "         END) SATEI_GK" . "\r\n";
        //strSQL.Append("       ,DECODE(M_ZAIKO.SIR_GK,NULL,M_SIT.TRA_GK,NVL(M_ZAIKO.SIR_GK,0)) SATEI_GK" & vbCrLf)                                  //査定金額
        //  2006/09/08 UPDATE END
        //2006/07/15 UPDATE END

        $strSQL .= "       ,M_SIT.SHZ_RT" . "\r\n";
        //消費税率
        $strSQL .= "       ,DECODE(M_SIT.KAZEI_KB,'0',0,M_SIT.SHZ_GKU) SHZ_GKU" . "\r\n";
        //消費税額
        $strSQL .= "       ,M_SIT.RCYL_GK" . "\r\n";
        //リサイクル料金合計額
        $strSQL .= "       ,M_SIT.YOTAK_GK" . "\r\n";
        //リサイクル預託料金
        $strSQL .= "       ,M_SIT.SHIKIN_KNR_RYOKIN" . "\r\n";
        //資金管理料金
        $strSQL .= "       ,DECODE(M_SIT.TOUROKU_UM,'0','1','1','2','') TOUROKU_UM" . "\r\n";
        //登録有無
        $strSQL .= "       ,DECODE(M_SIT.ATSUKAI_KB,'0','1','1','2','') ATSUKAI_KB" . "\r\n";
        //扱い区分
        $strSQL .= "       ,M_SIT.YOTAK_UM" . "\r\n";
        //追加預託有無

        $strSQL .= "       ,M_SIT.MSY_TOU_TTK_DAIKO_HYO" . "\r\n";
        //抹消登録手続き代行費用
        $strSQL .= "       ,M_SIT.MSY_TOU_AZK_HTE_HYO" . "\r\n";
        //抹消登録預り法定費用
        $strSQL .= "       ,M_SIT.SIY_SMI_CAR_SYR_HYO" . "\r\n";
        //使用済自動車処理費用
        //---20151008 li UPD S.
        // $strSQL .= "       ,TRUNC(M_SIT.MSY_TOU_TTK_DAIKO_HYO" . "\r\n";
        // $strSQL .= "            / (1 + 5 / 100) *  (5 / 100)) MSY_TOU_TTK_DAIKO_HYO_SHZ" . "\r\n";
        // //抹消登録手続き代行費用
//
        // $strSQL .= "       ,TRUNC(M_SIT.MSY_TOU_AZK_HTE_HYO" . "\r\n";
        // $strSQL .= "            / (1 + 5 / 100) *  (5 / 100)) MSY_TOU_AZK_HTE_HYO_SHZ" . "\r\n";
        // //抹消登録預り法定費用
//
        // $strSQL .= "       ,TRUNC(M_SIT.SIY_SMI_CAR_SYR_HYO" . "\r\n";
        // $strSQL .= "            / (1 + 5 / 100) *  (5 / 100)) SIY_SMI_CAR_SYR_HYO_SHZ" . "\r\n";
        //抹消登録手続き代行費用
        $strSQL .= "       ,CASE WHEN M_SIT.SHZ_KB = '4' THEN TRUNC(M_SIT.MSY_TOU_TTK_DAIKO_HYO / (1 + 5 / 100) *  (5 / 100)) " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '5' THEN TRUNC(M_SIT.MSY_TOU_TTK_DAIKO_HYO / (1 + 8 / 100) *  (8 / 100)) " . "\r\n";
        //201907/31 UPDATE START
//			$strSQL .= "             WHEN M_SIT.SHZ_KB = '6' THEN TRUNC(M_SIT.MSY_TOU_TTK_DAIKO_HYO / (1 + 10 / 100) *  (10 / 100)) " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '6' THEN TRUNC(M_SIT.MSY_TOU_TTK_DAIKO_HYO / (1 + 8 / 100) *  (8 / 100)) " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '7' THEN TRUNC(M_SIT.MSY_TOU_TTK_DAIKO_HYO / (1 + 10 / 100) *  (10 / 100)) " . "\r\n";
        //201907/31 UPDATE END

        $strSQL .= "             ELSE M_SIT.MSY_TOU_TTK_DAIKO_HYO END MSY_TOU_TTK_DAIKO_HYO_SHZ " . "\r\n";

        //抹消登録預り法定費用
        $strSQL .= "       ,CASE WHEN M_SIT.SHZ_KB = '4' THEN TRUNC(M_SIT.MSY_TOU_AZK_HTE_HYO / (1 + 5 / 100) *  (5 / 100))  " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '5' THEN TRUNC(M_SIT.MSY_TOU_AZK_HTE_HYO / (1 + 8 / 100) *  (8 / 100)) " . "\r\n";
        //20190731 UPDATE START
//			$strSQL .= "             WHEN M_SIT.SHZ_KB = '6' THEN TRUNC(M_SIT.MSY_TOU_AZK_HTE_HYO / (1 + 10 / 100) *  (10 / 100)) " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '6' THEN TRUNC(M_SIT.MSY_TOU_AZK_HTE_HYO / (1 + 8 / 100) *  (8 / 100)) " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '7' THEN TRUNC(M_SIT.MSY_TOU_AZK_HTE_HYO / (1 + 10 / 100) *  (10 / 100)) " . "\r\n";
        //20190731 UPDATE END
        $strSQL .= "             ELSE M_SIT.MSY_TOU_AZK_HTE_HYO END MSY_TOU_AZK_HTE_HYO_SHZ " . "\r\n";

        //使用済自動車処理費用
        $strSQL .= "       ,CASE WHEN M_SIT.SHZ_KB = '4' THEN TRUNC(M_SIT.SIY_SMI_CAR_SYR_HYO / (1 + 5 / 100) *  (5 / 100))  " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '5' THEN TRUNC(M_SIT.SIY_SMI_CAR_SYR_HYO / (1 + 8 / 100) *  (8 / 100)) " . "\r\n";
        //20190731 UPDATE START
//			$strSQL .= "             WHEN M_SIT.SHZ_KB = '6' THEN TRUNC(M_SIT.SIY_SMI_CAR_SYR_HYO / (1 + 10 / 100) *  (10 / 100)) " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '6' THEN TRUNC(M_SIT.SIY_SMI_CAR_SYR_HYO / (1 + 8 / 100) *  (8 / 100)) " . "\r\n";
        $strSQL .= "             WHEN M_SIT.SHZ_KB = '7' THEN TRUNC(M_SIT.SIY_SMI_CAR_SYR_HYO / (1 + 10 / 100) *  (10 / 100)) " . "\r\n";
        //20190731 UPDATE END
        $strSQL .= "             ELSE M_SIT.SIY_SMI_CAR_SYR_HYO END SIY_SMI_CAR_SYR_HYO_SHZ " . "\r\n";
        //---20151008 li UPD E.
        //使用済自動車処理費用
        $strSQL .= "  FROM  M41E11 M_SIT" . "\r\n";
        $strSQL .= "       ,HRIKUJI M_RKJ" . "\r\n";
        $strSQL .= "       ,M41B02 M_ZAIKO" . "\r\n";
        $strSQL .= " WHERE  M_RKJ.RIKUJI_CD(+) = M_SIT.TOU_NO_RKJ_CD" . "\r\n";
        //中古車在庫情報
        $strSQL .= "   AND  M_ZAIKO.SEIRI_NO(+) = M_SIT.CMN_NO" . "\r\n";
        $strSQL .= "   AND  M_ZAIKO.SEIRI_SEQ(+) = M_SIT.TRA_CAR_SEQ_NO" . "\r\n";
        $strSQL .= "   AND  M_ZAIKO.JYOTAIKBN(+) <> '9'" . "\r\n";
        $strSQL .= "   AND  M_SIT.CMN_NO = " . $this->ClsComFnc->FncSqlNv($strCmnNo) . "\r\n";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：業者名取得
    //関 数 名：fncGYOSYASelectSQL
    //引    数：無し
    //戻 り 値：SQL
    //処理説明：注文書情報を取得
    //**********************************************************************
    function fncGYOSYASelectSQL($strGyosya_CD)
    {
        $strSQL = "";
        $strSQL .= "SELECT DAIRITN_NM " . "\r\n";
        $strSQL .= "FROM   M27M08" . "\r\n";
        $strSQL .= "WHERE  DAIRITN_CD = '@GYOCD'" . "\r\n";

        $strSQL = str_replace("@GYOCD", $strGyosya_CD, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：ｴﾗｰチェックを行う
    //関 数 名：fncErrChkUriSQL
    //引    数：無し
    //戻 り 値：SQL文
    //処理説明：ｴﾗｰチェックを行う
    //**********************************************************************
    function fncErrChkUriSQL($CMN_NO, $UC_NO, $URIBUSYO, $URKBUSYO, $TANNO, $KRIDATE, $NAU_KB)
    {
        $strSQL = "";
        //SQL文

        $strSQL .= "";
        $strSQL .= "		SELECT '@CMN_NO' CMN_NO" . "\r\n";
        $strSQL .= "        ,      '@UC_NO' UC_NO" . "\r\n";
        $strSQL .= "        ,      '@URIBUSYO' ERR_MSG1" . "\r\n";
        $strSQL .= "        ,      NULL ERR_MSG2" . "\r\n";
        $strSQL .= "        ,      NULL ERR_MSG3" . "\r\n";
        $strSQL .= "        ,      '1' ERR_NO" . "\r\n";
        $strSQL .= "        ,      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "        FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "        WHERE  BUS.BUSYO_CD = '@URIBUSYO'" . "\r\n";
        $strSQL .= "        AND    BUS.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "        SELECT '@CMN_NO'" . "\r\n";
        $strSQL .= "        ,      '@UC_NO'" . "\r\n";
        $strSQL .= "        ,      '@URKBUSYO'" . "\r\n";
        $strSQL .= "        ,      NULL" . "\r\n";
        $strSQL .= "        ,      NULL" . "\r\n";
        $strSQL .= "        ,      '2' ERR_NO" . "\r\n";
        $strSQL .= "        ,      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "        FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "        WHERE  BUS.BUSYO_CD = '@URKBUSYO'" . "\r\n";
        $strSQL .= "        AND    BUS.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "        SELECT '@CMN_NO'" . "\r\n";
        $strSQL .= "        ,      '@UC_NO'" . "\r\n";
        $strSQL .= "        ,      '@TANNO'" . "\r\n";
        $strSQL .= "        ,      NULL" . "\r\n";
        $strSQL .= "        ,      NULL" . "\r\n";
        $strSQL .= "        ,      '3' ERR_NO" . "\r\n";
        $strSQL .= "        ,      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "        FROM   HSYAINMST SYA" . "\r\n";
        $strSQL .= "        WHERE  SYA.SYAIN_NO = '@TANNO'" . "\r\n";
        $strSQL .= "        AND    SYA.SYAIN_NO IS NULL" . "\r\n";
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "        SELECT '@CMN_NO'" . "\r\n";
        $strSQL .= "        ,      '@UC_NO'" . "\r\n";
        $strSQL .= "        ,      '@TANNO'" . "\r\n";
        $strSQL .= "        ,      '@URKBUSYO'" . "\r\n";
        $strSQL .= "        ,      HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      '4' ERR_NO" . "\r\n";
        $strSQL .= "        ,      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "        FROM   HHAIZOKU HAI" . "\r\n";
        $strSQL .= "        WHERE  HAI.SYAIN_NO = '@TANNO'" . "\r\n";
        $strSQL .= "        AND    HAI.START_DATE <= '@KRIDATE'" . "\r\n";
        $strSQL .= "        AND    NVL(HAI.END_DATE,'99999999') >= '@KRIDATE'" . "\r\n";
        $strSQL .= "        AND    (HAI.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "                OR     '@URKBUSYO' <> HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "                OR     '@URKBUSYO' <> HAI.SYUKEI_BUSYO_CD )" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "        SELECT '@CMN_NO'" . "\r\n";
        $strSQL .= "        ,      '@UC_NO'" . "\r\n";
        $strSQL .= "        ,      HAI.SYOKUSYU_KB" . "\r\n";
        $strSQL .= "        ,      '@NAU_KB'" . "\r\n";
        $strSQL .= "        ,      NULL" . "\r\n";
        $strSQL .= "        ,      '5' ERR_NO" . "\r\n";
        $strSQL .= "        ,      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "        FROM   HHAIZOKU HAI" . "\r\n";
        $strSQL .= "        INNER JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "        ON     SYA.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "        WHERE  HAI.SYAIN_NO = '@TANNO'" . "\r\n";
        $strSQL .= "        AND    HAI.START_DATE <= '@KRIDATE'" . "\r\n";
        $strSQL .= "        AND    NVL(HAI.END_DATE,'99999999') >= '@KRIDATE'" . "\r\n";
        $strSQL .= "        AND    HAI.SYOKUSYU_KB NOT IN ('1','2') " . "\r\n";
        $strSQL .= "        AND    NVL(SYA.TAISYOKU_DATE,'99999999') >= '@KRIDATE'" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "        UNION ALL" . "\r\n";
        $strSQL .= "        SELECT '@CMN_NO'" . "\r\n";
        $strSQL .= "        ,      '@UC_NO'" . "\r\n";
        $strSQL .= "        ,      '@TANNO'" . "\r\n";
        $strSQL .= "        ,      '@URKBUSYO'" . "\r\n";
        $strSQL .= "        ,      CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= "        ,      '6' ERR_NO" . "\r\n";
        $strSQL .= "        ,      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "        FROM   HURIBUSYOCNV CNV" . "\r\n";
        $strSQL .= "        INNER JOIN M41E10 CMN" . "\r\n";
        $strSQL .= "        ON    CMN.CMN_NO = CNV.CMN_NO" . "\r\n";
        $strSQL .= "        WHERE  CNV.CMN_NO = '@CMN_NO'" . "\r\n";

        $strSQL = str_replace("@CMN_NO", $CMN_NO, $strSQL);
        $strSQL = str_replace("@UC_NO", $UC_NO, $strSQL);
        $strSQL = str_replace("@URIBUSYO", $URIBUSYO, $strSQL);
        $strSQL = str_replace("@URKBUSYO", $URKBUSYO, $strSQL);
        $strSQL = str_replace("@TANNO", $TANNO, $strSQL);
        $strSQL = str_replace("@KRIDATE", $KRIDATE, $strSQL);
        $strSQL = str_replace("@NAU_KB", $NAU_KB, $strSQL);

        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新中売上データ存在チェック
    //関 数 名：fncEXISTSSCURI
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：新中売上データの存在チェック
    //**********************************************************************
    public function fncEXISTSSCURISQL($strCMNNO)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO,KEIJYO_YM" . "\r\n";
        $strSQL .= "FROM   HSCURI" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：条件変更履歴データ存在チェック
    //関 数 名：fncEXISTSJYOUHENSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：条件変更履歴データの存在チェック
    //**********************************************************************
    public function fncEXISTSJYOUHENSQL($strCMNNO, $strUPD_Date)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO,KEIJYO_YM" . "\r\n";
        $strSQL .= "FROM   HJYOUHEN" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "AND    KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        $strSQL = str_replace("@KEIJYOBI", $strUPD_Date, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：条件変更データ登録(SQL)
    //関 数 名：fncJOHENInsertSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL文
    //処理説明：条件変更データ登録(SQL)
    //**********************************************************************
    //2006/12/11 UPD 引数追加
    public function fncJOHENInsertSQL($strCMNNO, $strUpdPro)
    {
        $strSQL = "";
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL .= " INSERT INTO HJYOUHEN" . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "       KEIJYO_YM" . "\r\n";
        $strSQL .= "      ,NAU_KB" . "\r\n";
        $strSQL .= "      ,CMN_NO" . "\r\n";
        $strSQL .= "      ,JKN_HKO_RIRNO" . "\r\n";
        $strSQL .= "      ,DATA_KB" . "\r\n";
        $strSQL .= "      ,UC_NO" . "\r\n";
        $strSQL .= "      ,URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,URI_TANNO" . "\r\n";
        $strSQL .= "      ,URI_GYOSYA" . "\r\n";
        $strSQL .= "      ,SAV_KTNCD" . "\r\n";
        $strSQL .= "      ,URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,KYK_HNS" . "\r\n";
        $strSQL .= "      ,TOU_HNS" . "\r\n";
        $strSQL .= "      ,NTI_USR_CD" . "\r\n";
        $strSQL .= "      ,TOU_DATE" . "\r\n";
        $strSQL .= "      ,URG_DATE" . "\r\n";
        $strSQL .= "      ,KRI_DATE" . "\r\n";
        $strSQL .= "      ,CEL_DATE" . "\r\n";
        $strSQL .= "      ,SYADAI" . "\r\n";
        $strSQL .= "      ,CARNO" . "\r\n";
        $strSQL .= "      ,MAKER_CD" . "\r\n";
        $strSQL .= "      ,NENSIKI" . "\r\n";
        $strSQL .= "      ,SITEI_NO" . "\r\n";
        $strSQL .= "      ,RUIBETU_NO" . "\r\n";
        $strSQL .= "      ,SS_CD" . "\r\n";
        $strSQL .= "      ,TOA_NAME" . "\r\n";
        $strSQL .= "      ,HBSS_CD" . "\r\n";
        $strSQL .= "      ,KASOUNO" . "\r\n";
        $strSQL .= "      ,YOHIN_A" . "\r\n";
        $strSQL .= "      ,YOHIN_C" . "\r\n";
        $strSQL .= "      ,YOHIN_H" . "\r\n";
        $strSQL .= "      ,YOHIN_S" . "\r\n";
        $strSQL .= "      ,RIKUJI_CD" . "\r\n";
        $strSQL .= "      ,TOURK_NO1" . "\r\n";
        $strSQL .= "      ,TOURK_NO2" . "\r\n";
        $strSQL .= "      ,TOURK_NO3" . "\r\n";
        $strSQL .= "      ,H59" . "\r\n";
        $strSQL .= "      ,SYAKEN_EXP_DT" . "\r\n";
        $strSQL .= "      ,KKR_CD" . "\r\n";
        $strSQL .= "      ,NINKATA_CD" . "\r\n";
        $strSQL .= "      ,SYANAI_KOSYO" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_SYD_YM" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_NYK_YM" . "\r\n";
        $strSQL .= "      ,CHUMON_KB" . "\r\n";
        $strSQL .= "      ,KASOU_KB" . "\r\n";
        $strSQL .= "      ,AIM_FLG" . "\r\n";
        $strSQL .= "      ,AIM_KBN" . "\r\n";
        $strSQL .= "      ,KYK_KB" . "\r\n";
        $strSQL .= "      ,ZERO_KB" . "\r\n";
        $strSQL .= "      ,SYOYUKEN_KB" . "\r\n";
        $strSQL .= "      ,ITEN_KB" . "\r\n";
        $strSQL .= "      ,YOUTO_KB" . "\r\n";
        $strSQL .= "      ,KANRI_KB" . "\r\n";
        $strSQL .= "      ,HNBCHK_KB" . "\r\n";
        $strSQL .= "      ,KGO_KB" . "\r\n";
        $strSQL .= "      ,BUY_SHP" . "\r\n";
        $strSQL .= "      ,MZD_SIY" . "\r\n";
        $strSQL .= "      ,LEASE_KB" . "\r\n";
        $strSQL .= "      ,LEASE_KB2" . "\r\n";
        $strSQL .= "      ,LES_SHP1" . "\r\n";
        $strSQL .= "      ,LES_SHP2" . "\r\n";
        $strSQL .= "      ,KAP_KB" . "\r\n";
        $strSQL .= "      ,HNB_KB" . "\r\n";
        $strSQL .= "      ,OPT_HOK_KNY_KB" . "\r\n";
        $strSQL .= "      ,TRK_KB" . "\r\n";
        $strSQL .= "      ,ZAIKO_KB" . "\r\n";
        $strSQL .= "      ,KSO_KENSA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NAIYO" . "\r\n";
        $strSQL .= "      ,KSO_KB" . "\r\n";
        $strSQL .= "      ,KAZEI_KB" . "\r\n";
        $strSQL .= "      ,DAINO_FLG" . "\r\n";
        $strSQL .= "      ,JIBAI_FLG" . "\r\n";
        $strSQL .= "      ,SIH_SIT_KB" . "\r\n";
        $strSQL .= "      ,PAY_OFF_FLG" . "\r\n";
        $strSQL .= "      ,SWK_SUM_FLG" . "\r\n";
        $strSQL .= "      ,CKG_KB" . "\r\n";
        $strSQL .= "      ,CKO_HNB_KB" . "\r\n";
        $strSQL .= "      ,CKO_SS_KB" . "\r\n";
        $strSQL .= "      ,CKO_SIR_KB" . "\r\n";
        $strSQL .= "      ,CKO_SEB_KB" . "\r\n";
        $strSQL .= "      ,CKO_MEG_KB" . "\r\n";
        $strSQL .= "      ,CKO_MHN_KB" . "\r\n";
        $strSQL .= "      ,CKO_UCNO" . "\r\n";
        $strSQL .= "      ,CKO_CAR_SER_NO" . "\r\n";
        $strSQL .= "      ,JKN_HKD_AK" . "\r\n";
        $strSQL .= "      ,JKN_HK_NAIYO" . "\r\n";
        $strSQL .= "      ,JKN_HKD" . "\r\n";
        $strSQL .= "      ,JKN_NO" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO1" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO2" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO3" . "\r\n";
        $strSQL .= "      ,GYO_NAME" . "\r\n";
        $strSQL .= "      ,MEG_NAME" . "\r\n";
        $strSQL .= "      ,UC_OYA2" . "\r\n";
        $strSQL .= "      ,TGT_SIT" . "\r\n";
        $strSQL .= "      ,SRY_PRC" . "\r\n";
        $strSQL .= "      ,SRY_NBK" . "\r\n";
        $strSQL .= "      ,SRY_CMN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_KTN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_BUY_PCS" . "\r\n";
        $strSQL .= "      ,SRY_SHZ_RT" . "\r\n";
        $strSQL .= "      ,SRY_SHZ" . "\r\n";
        $strSQL .= "      ,FHZ_TEIKA" . "\r\n";
        $strSQL .= "      ,FHZ_NBK" . "\r\n";
        $strSQL .= "      ,FHZ_KYK" . "\r\n";
        $strSQL .= "      ,FHZ_PCS" . "\r\n";
        $strSQL .= "      ,FHZ_SHZ" . "\r\n";
        $strSQL .= "      ,TKB_KSH_TEIKA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NBK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_KYK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_PCS" . "\r\n";
        $strSQL .= "      ,TKB_KSH_SHZ" . "\r\n";
        $strSQL .= "      ,KAP_TES_KYK" . "\r\n";
        $strSQL .= "      ,KAP_TES_KJN" . "\r\n";
        $strSQL .= "      ,KAP_TES_RT" . "\r\n";
        $strSQL .= "      ,KAP_TES_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KYK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KJN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SHZ" . "\r\n";
        $strSQL .= "      ,HOUTEIH_GK" . "\r\n";
        $strSQL .= "      ,HKN_GK" . "\r\n";
        $strSQL .= "      ,TRA_CAR_ZSI_SUM" . "\r\n";
        $strSQL .= "      ,SHR_GK_SUM" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_SHZ" . "\r\n";
        $strSQL .= "      ,SHR_JKN_ATM_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TRK_SYH" . "\r\n";
        $strSQL .= "      ,SHR_JKN_CKO_FTK" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KIN" . "\r\n";
        $strSQL .= "      ,KUREJITGAISYA" . "\r\n";
        $strSQL .= "      ,KUREJIT_NO" . "\r\n";
        $strSQL .= "      ,TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSQL .= "      ,KAP_GKN" . "\r\n";
        $strSQL .= "      ,TRA_CAR_PRC_SUM" . "\r\n";
        $strSQL .= "      ,TRA_CAR_STI_SUM" . "\r\n";
        $strSQL .= "      ,JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= "      ,SYARYOU_ZEI" . "\r\n";
        $strSQL .= "      ,EAKON_ZEI" . "\r\n";
        $strSQL .= "      ,SUTEREO_ZEI" . "\r\n";
        $strSQL .= "      ,JYURYO_ZEI" . "\r\n";
        $strSQL .= "      ,SHZ_KEI" . "\r\n";
        $strSQL .= "      ,JIBAI_SITEI" . "\r\n";
        $strSQL .= "      ,JIBAI_KAISYA" . "\r\n";
        $strSQL .= "      ,JIBAI_CAR_KND" . "\r\n";
        $strSQL .= "      ,JIBAI_ICOL_CD" . "\r\n";
        $strSQL .= "      ,JIBAI_TUKI_SU" . "\r\n";
        $strSQL .= "      ,JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= "      ,OPTHOK_RYO" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_KZI_KBN" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_SHR_CD" . "\r\n";
        $strSQL .= "      ,HNB_TES_GKU" . "\r\n";
        $strSQL .= "      ,HNB_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,TOU_SYH_NOUSYA" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SIT_TTK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SATEI" . "\r\n";
        $strSQL .= "      ,TOU_SYH_JIKOU" . "\r\n";
        $strSQL .= "      ,TOU_SYH_ETC" . "\r\n";
        $strSQL .= "      ,HOUTEIH_KEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SIT" . "\r\n";
        $strSQL .= "      ,HONBU_FTK" . "\r\n";
        $strSQL .= "      ,UKM_SNY_TES" . "\r\n";
        $strSQL .= "      ,UKM_SINSEI_SYR" . "\r\n";
        $strSQL .= "      ,KAP_TES_SGK" . "\r\n";
        $strSQL .= "      ,ETC_SKI_RYO" . "\r\n";
        $strSQL .= "      ,SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= "      ,PENALTY" . "\r\n";
        $strSQL .= "      ,EGO_GAI_SYUEKI" . "\r\n";
        $strSQL .= "      ,SAI_SONEKI" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_RUI_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_SYR" . "\r\n";
        $strSQL .= "      ,TOK_KEI_TOK_KIN" . "\r\n";
        $strSQL .= "      ,GNK_HJN_PCS" . "\r\n";
        $strSQL .= "      ,GNK_SIT_URI_SKI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SIT_KIN" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI" . "\r\n";
        $strSQL .= "      ,CKO_SAI_MITUMORI" . "\r\n";
        $strSQL .= "      ,CKO_SYOGAKARI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI_SOKAI" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_SHZ" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        $strSQL .= "      ,NYK_KB" . "\r\n";
        $strSQL .= "      ,DM_KB" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KYK" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_SKI" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KOKEN" . "\r\n";
        $strSQL .= "      ,NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KIJUN_NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KOUSEI_SYOSYO" . "\r\n";
        $strSQL .= "      ,JAF" . "\r\n";
        $strSQL .= "      ,KICK_BACK" . "\r\n";
        $strSQL .= "      ,YOTAK_KB" . "\r\n";
        $strSQL .= "      ,RCY_YOT_KIN" . "\r\n";
        $strSQL .= "      ,RCY_SKN_KAN_HI" . "\r\n";
        $strSQL .= "      ,URI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_DAISU" . "\r\n";
        $strSQL .= "      ,KYK_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,KYK_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,KYK_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,KYK_KEY_TEL" . "\r\n";
        $strSQL .= "      ,KYK_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_MEI" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,KYK_ADR_KN" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,MGN_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,MGN_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,MGN_KEY_TEL" . "\r\n";
        $strSQL .= "      ,MGN_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_MEI" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,MGN_ADR_KN" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_SEX" . "\r\n";
        $strSQL .= "      ,MGN_BRTDT" . "\r\n";
        $strSQL .= "      ,OLD_CMN_NO" . "\r\n";
        $strSQL .= "      ,DEL_DATE" . "\r\n";
        $strSQL .= "      ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,CREATE_DATE" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KJN_GK" . "\r\n";
        $strSQL .= "      ,SYUEKI_SYOKEI" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "      ,UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End
        //2007/07/06 INS Start   パックDE753/パックDEメンテ
        $strSQL .= "      ,PACK_DE_753" . "\r\n";
        $strSQL .= "      ,PACK_DE_MENTE" . "\r\n";
        //2007/07/06 INS End
        //2009/12/21 INS Start
        $strSQL .= "      ,KYK_CSRRANK" . "\r\n";
        $strSQL .= "      ,SIY_CSRRANK" . "\r\n";
        //'''strSQL.Append("      ,UC_KENSU_FLG" & vbCrLf)
        // '''strSQL.Append("      ,MI_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TOU_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TA_KYK_JI_TRK_FLG" & vbCrLf)
        // '''strSQL.Append("      ,JI_KYK_TA_TRK_FLG" & vbCrLf)
        // '''strSQL.Append("      ,MAKER_FLG" & vbCrLf)
        // '''strSQL.Append("      ,FUKUSHI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SYAMEI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,URI_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,LEASE_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SEAVICE_CAR_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SAIBAI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,KARUTE_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_URI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_TOU_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_SONTA_FLG" & vbCrLf)
        $strSQL .= "      ,UC_KENSU" . "\r\n";
        $strSQL .= "      ,MI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TA_KYK_JI_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,JI_KYK_TA_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,MAKER_DAISU" . "\r\n";
        $strSQL .= "      ,FUKUSHI_DAISU" . "\r\n";
        $strSQL .= "      ,SYAMEI_DAISU" . "\r\n";
        $strSQL .= "      ,URI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,LEASE_DAISU" . "\r\n";
        $strSQL .= "      ,SEAVICE_CAR_DAISU" . "\r\n";
        $strSQL .= "      ,SAIBAI_DAISU" . "\r\n";
        $strSQL .= "      ,KARUTE_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_URI_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_TOU_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_SONTA_DAISU" . "\r\n";
        $strSQL .= "      ,KAIYAKU_DAISU" . "\r\n";

        //2009/12/21 INS end
        $strSQL .= "      )" . "\r\n";
        $strSQL .= " SELECT" . "\r\n";
        //strSQL.Append(clsComFnc.FncSqlNv(OrderInfo.OrderInfo1.経理日.Substring(0, 6)) & vbCrLf)
        $strSQL .= "       KEIJYO_YM" . "\r\n";
        $strSQL .= "      ,NAU_KB" . "\r\n";
        $strSQL .= "      ,CMN_NO" . "\r\n";
        $strSQL .= "      ,(SELECT NVL(MAX(JKN_HKO_RIRNO),0)+1 FROM HJYOUHEN WHERE CMN_NO = '@CMNNO')" . "\r\n";
        $strSQL .= "      ,DECODE(SUBSTR(DATA_KB,1,1),'1','1X','2X')" . "\r\n";
        $strSQL .= "      ,UC_NO" . "\r\n";
        $strSQL .= "      ,URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,URI_TANNO" . "\r\n";
        $strSQL .= "      ,URI_GYOSYA" . "\r\n";
        $strSQL .= "      ,SAV_KTNCD" . "\r\n";
        $strSQL .= "      ,URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,KYK_HNS" . "\r\n";
        $strSQL .= "      ,TOU_HNS" . "\r\n";
        $strSQL .= "      ,NTI_USR_CD" . "\r\n";
        $strSQL .= "      ,TOU_DATE" . "\r\n";
        $strSQL .= "      ,URG_DATE" . "\r\n";
        $strSQL .= "      ,KRI_DATE" . "\r\n";
        $strSQL .= "      ,CEL_DATE" . "\r\n";
        $strSQL .= "      ,SYADAI" . "\r\n";
        $strSQL .= "      ,CARNO" . "\r\n";
        $strSQL .= "      ,MAKER_CD" . "\r\n";
        $strSQL .= "      ,NENSIKI" . "\r\n";
        $strSQL .= "      ,SITEI_NO" . "\r\n";
        $strSQL .= "      ,RUIBETU_NO" . "\r\n";
        $strSQL .= "      ,SS_CD" . "\r\n";
        $strSQL .= "      ,TOA_NAME" . "\r\n";
        $strSQL .= "      ,HBSS_CD" . "\r\n";
        $strSQL .= "      ,KASOUNO" . "\r\n";
        $strSQL .= "      ,YOHIN_A" . "\r\n";
        $strSQL .= "      ,YOHIN_C" . "\r\n";
        $strSQL .= "      ,YOHIN_H" . "\r\n";
        $strSQL .= "      ,YOHIN_S" . "\r\n";
        $strSQL .= "      ,RIKUJI_CD" . "\r\n";
        $strSQL .= "      ,TOURK_NO1" . "\r\n";
        $strSQL .= "      ,TOURK_NO2" . "\r\n";
        $strSQL .= "      ,TOURK_NO3" . "\r\n";
        $strSQL .= "      ,H59" . "\r\n";
        $strSQL .= "      ,SYAKEN_EXP_DT" . "\r\n";
        $strSQL .= "      ,KKR_CD" . "\r\n";
        $strSQL .= "      ,NINKATA_CD" . "\r\n";
        $strSQL .= "      ,SYANAI_KOSYO" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_SYD_YM" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_NYK_YM" . "\r\n";
        $strSQL .= "      ,CHUMON_KB" . "\r\n";
        $strSQL .= "      ,KASOU_KB" . "\r\n";
        $strSQL .= "      ,AIM_FLG" . "\r\n";
        $strSQL .= "      ,AIM_KBN" . "\r\n";
        $strSQL .= "      ,KYK_KB" . "\r\n";
        $strSQL .= "      ,ZERO_KB" . "\r\n";
        $strSQL .= "      ,SYOYUKEN_KB" . "\r\n";
        $strSQL .= "      ,ITEN_KB" . "\r\n";
        $strSQL .= "      ,YOUTO_KB" . "\r\n";
        $strSQL .= "      ,KANRI_KB" . "\r\n";
        $strSQL .= "      ,HNBCHK_KB" . "\r\n";
        $strSQL .= "      ,KGO_KB" . "\r\n";
        $strSQL .= "      ,BUY_SHP" . "\r\n";
        $strSQL .= "      ,MZD_SIY" . "\r\n";
        $strSQL .= "      ,LEASE_KB" . "\r\n";
        $strSQL .= "      ,LEASE_KB2" . "\r\n";
        $strSQL .= "      ,LES_SHP1" . "\r\n";
        $strSQL .= "      ,LES_SHP2" . "\r\n";
        $strSQL .= "      ,KAP_KB" . "\r\n";
        $strSQL .= "      ,HNB_KB" . "\r\n";
        $strSQL .= "      ,OPT_HOK_KNY_KB" . "\r\n";
        $strSQL .= "      ,TRK_KB" . "\r\n";
        $strSQL .= "      ,ZAIKO_KB" . "\r\n";
        $strSQL .= "      ,KSO_KENSA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NAIYO" . "\r\n";
        $strSQL .= "      ,KSO_KB" . "\r\n";
        $strSQL .= "      ,KAZEI_KB" . "\r\n";
        $strSQL .= "      ,DAINO_FLG" . "\r\n";
        $strSQL .= "      ,JIBAI_FLG" . "\r\n";
        $strSQL .= "      ,SIH_SIT_KB" . "\r\n";
        $strSQL .= "      ,PAY_OFF_FLG" . "\r\n";
        $strSQL .= "      ,SWK_SUM_FLG" . "\r\n";
        $strSQL .= "      ,CKG_KB" . "\r\n";
        $strSQL .= "      ,CKO_HNB_KB" . "\r\n";
        $strSQL .= "      ,CKO_SS_KB" . "\r\n";
        $strSQL .= "      ,CKO_SIR_KB" . "\r\n";
        $strSQL .= "      ,CKO_SEB_KB" . "\r\n";
        $strSQL .= "      ,CKO_MEG_KB" . "\r\n";
        $strSQL .= "      ,CKO_MHN_KB" . "\r\n";
        $strSQL .= "      ,CKO_UCNO" . "\r\n";
        $strSQL .= "      ,CKO_CAR_SER_NO" . "\r\n";
        $strSQL .= "      ,JKN_HKD_AK" . "\r\n";
        $strSQL .= "      ,JKN_HK_NAIYO" . "\r\n";
        $strSQL .= "      ,JKN_HKD" . "\r\n";
        $strSQL .= "      ,JKN_NO" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO1" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO2" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO3" . "\r\n";
        $strSQL .= "      ,GYO_NAME" . "\r\n";
        $strSQL .= "      ,MEG_NAME" . "\r\n";
        $strSQL .= "      ,UC_OYA2" . "\r\n";
        $strSQL .= "      ,TGT_SIT" . "\r\n";
        $strSQL .= "      ,SRY_PRC" . "\r\n";
        $strSQL .= "      ,SRY_NBK" . "\r\n";
        $strSQL .= "      ,SRY_CMN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_KTN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_BUY_PCS" . "\r\n";
        $strSQL .= "      ,SRY_SHZ_RT" . "\r\n";
        $strSQL .= "      ,SRY_SHZ" . "\r\n";
        $strSQL .= "      ,FHZ_TEIKA" . "\r\n";
        $strSQL .= "      ,FHZ_NBK" . "\r\n";
        $strSQL .= "      ,FHZ_KYK" . "\r\n";
        $strSQL .= "      ,FHZ_PCS" . "\r\n";
        $strSQL .= "      ,FHZ_SHZ" . "\r\n";
        $strSQL .= "      ,TKB_KSH_TEIKA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NBK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_KYK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_PCS" . "\r\n";
        $strSQL .= "      ,TKB_KSH_SHZ" . "\r\n";
        $strSQL .= "      ,KAP_TES_KYK" . "\r\n";
        $strSQL .= "      ,KAP_TES_KJN" . "\r\n";
        $strSQL .= "      ,KAP_TES_RT" . "\r\n";
        $strSQL .= "      ,KAP_TES_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KYK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KJN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SHZ" . "\r\n";
        $strSQL .= "      ,HOUTEIH_GK" . "\r\n";
        $strSQL .= "      ,HKN_GK" . "\r\n";
        $strSQL .= "      ,TRA_CAR_ZSI_SUM" . "\r\n";
        $strSQL .= "      ,SHR_GK_SUM" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_SHZ" . "\r\n";
        $strSQL .= "      ,SHR_JKN_ATM_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TRK_SYH" . "\r\n";
        $strSQL .= "      ,SHR_JKN_CKO_FTK" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KIN" . "\r\n";
        $strSQL .= "      ,KUREJITGAISYA" . "\r\n";
        $strSQL .= "      ,KUREJIT_NO" . "\r\n";
        $strSQL .= "      ,TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSQL .= "      ,KAP_GKN" . "\r\n";
        $strSQL .= "      ,TRA_CAR_PRC_SUM" . "\r\n";
        $strSQL .= "      ,TRA_CAR_STI_SUM" . "\r\n";
        $strSQL .= "      ,JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= "      ,SYARYOU_ZEI" . "\r\n";
        $strSQL .= "      ,EAKON_ZEI" . "\r\n";
        $strSQL .= "      ,SUTEREO_ZEI" . "\r\n";
        $strSQL .= "      ,JYURYO_ZEI" . "\r\n";
        $strSQL .= "      ,SHZ_KEI" . "\r\n";
        $strSQL .= "      ,JIBAI_SITEI" . "\r\n";
        $strSQL .= "      ,JIBAI_KAISYA" . "\r\n";
        $strSQL .= "      ,JIBAI_CAR_KND" . "\r\n";
        $strSQL .= "      ,JIBAI_ICOL_CD" . "\r\n";
        $strSQL .= "      ,JIBAI_TUKI_SU" . "\r\n";
        $strSQL .= "      ,JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= "      ,OPTHOK_RYO" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_KZI_KBN" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_SHR_CD" . "\r\n";
        $strSQL .= "      ,HNB_TES_GKU" . "\r\n";
        $strSQL .= "      ,HNB_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,TOU_SYH_NOUSYA" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SIT_TTK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SATEI" . "\r\n";
        $strSQL .= "      ,TOU_SYH_JIKOU" . "\r\n";
        $strSQL .= "      ,TOU_SYH_ETC" . "\r\n";
        $strSQL .= "      ,HOUTEIH_KEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SIT" . "\r\n";
        $strSQL .= "      ,HONBU_FTK" . "\r\n";
        $strSQL .= "      ,UKM_SNY_TES" . "\r\n";
        $strSQL .= "      ,UKM_SINSEI_SYR" . "\r\n";
        $strSQL .= "      ,KAP_TES_SGK" . "\r\n";
        $strSQL .= "      ,ETC_SKI_RYO" . "\r\n";
        $strSQL .= "      ,SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= "      ,PENALTY" . "\r\n";
        $strSQL .= "      ,EGO_GAI_SYUEKI" . "\r\n";
        $strSQL .= "      ,SAI_SONEKI" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_RUI_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_SYR" . "\r\n";
        $strSQL .= "      ,TOK_KEI_TOK_KIN" . "\r\n";
        $strSQL .= "      ,GNK_HJN_PCS" . "\r\n";
        $strSQL .= "      ,GNK_SIT_URI_SKI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SIT_KIN" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI" . "\r\n";
        $strSQL .= "      ,CKO_SAI_MITUMORI" . "\r\n";
        $strSQL .= "      ,CKO_SYOGAKARI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI_SOKAI" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_SHZ" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        $strSQL .= "      ,NYK_KB" . "\r\n";
        $strSQL .= "      ,DM_KB" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KYK" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_SKI" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KOKEN" . "\r\n";
        $strSQL .= "      ,NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KIJUN_NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KOUSEI_SYOSYO" . "\r\n";
        $strSQL .= "      ,JAF" . "\r\n";
        $strSQL .= "      ,KICK_BACK" . "\r\n";
        $strSQL .= "      ,YOTAK_KB" . "\r\n";
        $strSQL .= "      ,RCY_YOT_KIN" . "\r\n";
        $strSQL .= "      ,RCY_SKN_KAN_HI" . "\r\n";
        $strSQL .= "      ,URI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_DAISU" . "\r\n";
        $strSQL .= "      ,KYK_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,KYK_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,KYK_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,KYK_KEY_TEL" . "\r\n";
        $strSQL .= "      ,KYK_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_MEI" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,KYK_ADR_KN" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,MGN_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,MGN_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,MGN_KEY_TEL" . "\r\n";
        $strSQL .= "      ,MGN_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_MEI" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,MGN_ADR_KN" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_SEX" . "\r\n";
        $strSQL .= "      ,MGN_BRTDT" . "\r\n";
        $strSQL .= "      ,OLD_CMN_NO" . "\r\n";
        $strSQL .= "      ,DEL_DATE" . "\r\n";
        $strSQL .= "      ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,CREATE_DATE" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KJN_GK" . "\r\n";
        $strSQL .= "      ,SYUEKI_SYOKEI" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= "      ,'@UPDUSER'" . "\r\n";
        $strSQL .= "      ,'@UPDAPP'" . "\r\n";
        $strSQL .= "      ,'@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2007/07/06 INS Start   パックDE753/パックDEメンテ
        $strSQL .= "      ,PACK_DE_753" . "\r\n";
        $strSQL .= "      ,PACK_DE_MENTE" . "\r\n";
        //2007/07/06 INS End
        //2009/12/21 INS Start
        $strSQL .= "      ,KYK_CSRRANK" . "\r\n";
        $strSQL .= "      ,SIY_CSRRANK" . "\r\n";
        // '''strSQL.Append("      ,UC_KENSU_FLG" & vbCrLf)
        // '''strSQL.Append("      ,MI_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TOU_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TA_KYK_JI_TRK_FLG" & vbCrLf)
        // '''strSQL.Append("      ,JI_KYK_TA_TRK_FLG" & vbCrLf)
        // '''strSQL.Append("      ,MAKER_FLG" & vbCrLf)
        // '''strSQL.Append("      ,FUKUSHI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SYAMEI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,URI_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,LEASE_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SEAVICE_CAR_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SAIBAI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,KARUTE_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_URI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_TOU_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_SONTA_FLG" & vbCrLf)
        $strSQL .= "      ,UC_KENSU" . "\r\n";
        $strSQL .= "      ,MI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TA_KYK_JI_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,JI_KYK_TA_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,MAKER_DAISU" . "\r\n";
        $strSQL .= "      ,FUKUSHI_DAISU" . "\r\n";
        $strSQL .= "      ,SYAMEI_DAISU" . "\r\n";
        $strSQL .= "      ,URI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,LEASE_DAISU" . "\r\n";
        $strSQL .= "      ,SEAVICE_CAR_DAISU" . "\r\n";
        $strSQL .= "      ,SAIBAI_DAISU" . "\r\n";
        $strSQL .= "      ,KARUTE_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_URI_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_TOU_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_SONTA_DAISU" . "\r\n";
        $strSQL .= "      ,KAIYAKU_DAISU" . "\r\n";
        //2009/12/21 INS end
        $strSQL .= " FROM  HSCURI" . "\r\n";
        $strSQL .= " WHERE CMN_NO = '@CMNNO'" . "\r\n";
        //---20160120 li UPD S.
        // $strSQL = str_replace("@CMNNO", $strCMNNO, $subject, $strSQL);
        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        //---20160120 li UPD E.
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

        return $strSQL;
    }

    public function fncGetMAXNOSQL($strNO)
    {
        $strSQL = "";
        $strSQL = "SELECT NVL(MAX(JKN_HKO_RIRNO),0) NO FROM HJYOUHEN WHERE CMN_NO = '@CMNNO'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strNO, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：条件変更データ登録(SQL)
    //関 数 名：fncJOHENInsertSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL文
    //処理説明：条件変更データ登録(SQL)
    //**********************************************************************
    //2006/12/11 UPD 引数追加
    public function fncJOHENSITInsertSQL($strCMNNO, $strUpdPro)
    {
        $strSQL = "";
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        //---20160120 li UPD S.
        // $strSQL .= " INSERT INTO HJYOUHEN" . "\r\n";
        // $strSQL .= "      (" . "\r\n";
        // $strSQL .= "       KEIJYO_YM" . "\r\n";
        // $strSQL .= "      ,NAU_KB" . "\r\n";
        // $strSQL .= "      ,CMN_NO" . "\r\n";
        // $strSQL .= "      ,JKN_HKO_RIRNO" . "\r\n";
        // $strSQL .= "      ,DATA_KB" . "\r\n";
        // $strSQL .= "      ,UC_NO" . "\r\n";
        // $strSQL .= "      ,URI_BUSYO_CD" . "\r\n";
        // $strSQL .= "      ,URI_TANNO" . "\r\n";
        // $strSQL .= "      ,URI_GYOSYA" . "\r\n";
        // $strSQL .= "      ,SAV_KTNCD" . "\r\n";
        // $strSQL .= "      ,URK_BUSYO_CD" . "\r\n";
        // $strSQL .= "      ,KYK_HNS" . "\r\n";
        // $strSQL .= "      ,TOU_HNS" . "\r\n";
        // $strSQL .= "      ,NTI_USR_CD" . "\r\n";
        // $strSQL .= "      ,TOU_DATE" . "\r\n";
        // $strSQL .= "      ,URG_DATE" . "\r\n";
        // $strSQL .= "      ,KRI_DATE" . "\r\n";
        // $strSQL .= "      ,CEL_DATE" . "\r\n";
        // $strSQL .= "      ,SYADAI" . "\r\n";
        // $strSQL .= "      ,CARNO" . "\r\n";
        // $strSQL .= "      ,MAKER_CD" . "\r\n";
        // $strSQL .= "      ,NENSIKI" . "\r\n";
        // $strSQL .= "      ,SITEI_NO" . "\r\n";
        // $strSQL .= "      ,RUIBETU_NO" . "\r\n";
        // $strSQL .= "      ,SS_CD" . "\r\n";
        // $strSQL .= "      ,TOA_NAME" . "\r\n";
        // $strSQL .= "      ,HBSS_CD" . "\r\n";
        // $strSQL .= "      ,KASOUNO" . "\r\n";
        // $strSQL .= "      ,YOHIN_A" . "\r\n";
        // $strSQL .= "      ,YOHIN_C" . "\r\n";
        // $strSQL .= "      ,YOHIN_H" . "\r\n";
        // $strSQL .= "      ,YOHIN_S" . "\r\n";
        // $strSQL .= "      ,RIKUJI_CD" . "\r\n";
        // $strSQL .= "      ,TOURK_NO1" . "\r\n";
        // $strSQL .= "      ,TOURK_NO2" . "\r\n";
        // $strSQL .= "      ,TOURK_NO3" . "\r\n";
        // $strSQL .= "      ,H59" . "\r\n";
        // $strSQL .= "      ,SYAKEN_EXP_DT" . "\r\n";
        // $strSQL .= "      ,KKR_CD" . "\r\n";
        // $strSQL .= "      ,NINKATA_CD" . "\r\n";
        // $strSQL .= "      ,SYANAI_KOSYO" . "\r\n";
        // $strSQL .= "      ,CHUKOSYA_SYD_YM" . "\r\n";
        // $strSQL .= "      ,CHUKOSYA_NYK_YM" . "\r\n";
        // $strSQL .= "      ,CHUMON_KB" . "\r\n";
        // $strSQL .= "      ,KASOU_KB" . "\r\n";
        // $strSQL .= "      ,AIM_FLG" . "\r\n";
        // $strSQL .= "      ,AIM_KBN" . "\r\n";
        // $strSQL .= "      ,KYK_KB" . "\r\n";
        // $strSQL .= "      ,ZERO_KB" . "\r\n";
        // $strSQL .= "      ,SYOYUKEN_KB" . "\r\n";
        // $strSQL .= "      ,ITEN_KB" . "\r\n";
        // $strSQL .= "      ,YOUTO_KB" . "\r\n";
        // $strSQL .= "      ,KANRI_KB" . "\r\n";
        // $strSQL .= "      ,HNBCHK_KB" . "\r\n";
        // $strSQL .= "      ,KGO_KB" . "\r\n";
        // $strSQL .= "      ,BUY_SHP" . "\r\n";
        // $strSQL .= "      ,MZD_SIY" . "\r\n";
        // $strSQL .= "      ,LEASE_KB" . "\r\n";
        // $strSQL .= "      ,LEASE_KB2" . "\r\n";
        // $strSQL .= "      ,LES_SHP1" . "\r\n";
        // $strSQL .= "      ,LES_SHP2" . "\r\n";
        // $strSQL .= "      ,KAP_KB" . "\r\n";
        // $strSQL .= "      ,HNB_KB" . "\r\n";
        // $strSQL .= "      ,OPT_HOK_KNY_KB" . "\r\n";
        // $strSQL .= "      ,TRK_KB" . "\r\n";
        // $strSQL .= "      ,ZAIKO_KB" . "\r\n";
        // $strSQL .= "      ,KSO_KENSA" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_NAIYO" . "\r\n";
        // $strSQL .= "      ,KSO_KB" . "\r\n";
        // $strSQL .= "      ,KAZEI_KB" . "\r\n";
        // $strSQL .= "      ,DAINO_FLG" . "\r\n";
        // $strSQL .= "      ,JIBAI_FLG" . "\r\n";
        // $strSQL .= "      ,SIH_SIT_KB" . "\r\n";
        // $strSQL .= "      ,PAY_OFF_FLG" . "\r\n";
        // $strSQL .= "      ,SWK_SUM_FLG" . "\r\n";
        // $strSQL .= "      ,CKG_KB" . "\r\n";
        // $strSQL .= "      ,CKO_HNB_KB" . "\r\n";
        // $strSQL .= "      ,CKO_SS_KB" . "\r\n";
        // $strSQL .= "      ,CKO_SIR_KB" . "\r\n";
        // $strSQL .= "      ,CKO_SEB_KB" . "\r\n";
        // $strSQL .= "      ,CKO_MEG_KB" . "\r\n";
        // $strSQL .= "      ,CKO_MHN_KB" . "\r\n";
        // $strSQL .= "      ,CKO_UCNO" . "\r\n";
        // $strSQL .= "      ,CKO_CAR_SER_NO" . "\r\n";
        // $strSQL .= "      ,JKN_HKD_AK" . "\r\n";
        // $strSQL .= "      ,JKN_HK_NAIYO" . "\r\n";
        // $strSQL .= "      ,JKN_HKD" . "\r\n";
        // $strSQL .= "      ,JKN_NO" . "\r\n";
        // $strSQL .= "      ,SIT_SEIRINO1" . "\r\n";
        // $strSQL .= "      ,SIT_SEIRINO2" . "\r\n";
        // $strSQL .= "      ,SIT_SEIRINO3" . "\r\n";
        // $strSQL .= "      ,GYO_NAME" . "\r\n";
        // $strSQL .= "      ,MEG_NAME" . "\r\n";
        // $strSQL .= "      ,UC_OYA2" . "\r\n";
        // $strSQL .= "      ,TGT_SIT" . "\r\n";
        // $strSQL .= "      ,SRY_PRC" . "\r\n";
        // $strSQL .= "      ,SRY_NBK" . "\r\n";
        // $strSQL .= "      ,SRY_CMN_PCS" . "\r\n";
        // $strSQL .= "      ,SRY_KTN_PCS" . "\r\n";
        // $strSQL .= "      ,SRY_BUY_PCS" . "\r\n";
        // $strSQL .= "      ,SRY_SHZ_RT" . "\r\n";
        // $strSQL .= "      ,SRY_SHZ" . "\r\n";
        // $strSQL .= "      ,FHZ_TEIKA" . "\r\n";
        // $strSQL .= "      ,FHZ_NBK" . "\r\n";
        // $strSQL .= "      ,FHZ_KYK" . "\r\n";
        // $strSQL .= "      ,FHZ_PCS" . "\r\n";
        // $strSQL .= "      ,FHZ_SHZ" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_TEIKA" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_NBK" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_KYK" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_PCS" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_SHZ" . "\r\n";
        // $strSQL .= "      ,KAP_TES_KYK" . "\r\n";
        // $strSQL .= "      ,KAP_TES_KJN" . "\r\n";
        // $strSQL .= "      ,KAP_TES_RT" . "\r\n";
        // $strSQL .= "      ,KAP_TES_SHZ" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KYK" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KJN" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SHZ" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_GK" . "\r\n";
        // $strSQL .= "      ,HKN_GK" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_ZSI_SUM" . "\r\n";
        // $strSQL .= "      ,SHR_GK_SUM" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_SIT_KIN" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_SIT_SHZ" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_ATM_KIN" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_TRK_SYH" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_CKO_FTK" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_TGT_KAI" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_TGT_KIN" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_KRJ_KAI" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_KRJ_KIN" . "\r\n";
        // $strSQL .= "      ,KUREJITGAISYA" . "\r\n";
        // $strSQL .= "      ,KUREJIT_NO" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_RCYYTK_SUM" . "\r\n";
        // $strSQL .= "      ,KAP_GKN" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_PRC_SUM" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_STI_SUM" . "\r\n";
        // $strSQL .= "      ,JIDOUSYA_ZEI" . "\r\n";
        // $strSQL .= "      ,SYARYOU_ZEI" . "\r\n";
        // $strSQL .= "      ,EAKON_ZEI" . "\r\n";
        // $strSQL .= "      ,SUTEREO_ZEI" . "\r\n";
        // $strSQL .= "      ,JYURYO_ZEI" . "\r\n";
        // $strSQL .= "      ,SHZ_KEI" . "\r\n";
        // $strSQL .= "      ,JIBAI_SITEI" . "\r\n";
        // $strSQL .= "      ,JIBAI_KAISYA" . "\r\n";
        // $strSQL .= "      ,JIBAI_CAR_KND" . "\r\n";
        // $strSQL .= "      ,JIBAI_ICOL_CD" . "\r\n";
        // $strSQL .= "      ,JIBAI_TUKI_SU" . "\r\n";
        // $strSQL .= "      ,JIBAI_HOK_RYO" . "\r\n";
        // $strSQL .= "      ,OPTHOK_RYO" . "\r\n";
        // $strSQL .= "      ,HNB_TES_RYO_KZI_KBN" . "\r\n";
        // $strSQL .= "      ,HNB_TES_RYO_SHR_CD" . "\r\n";
        // $strSQL .= "      ,HNB_TES_GKU" . "\r\n";
        // $strSQL .= "      ,HNB_SHZ" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KEN" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SYAKEN" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SYAKO_SYO" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_NOUSYA" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SIT_TTK" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SATEI" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_JIKOU" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_ETC" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_KEN" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_SYAKEN" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_SYAKO_SYO" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_SIT" . "\r\n";
        // $strSQL .= "      ,HONBU_FTK" . "\r\n";
        // $strSQL .= "      ,UKM_SNY_TES" . "\r\n";
        // $strSQL .= "      ,UKM_SINSEI_SYR" . "\r\n";
        // $strSQL .= "      ,KAP_TES_SGK" . "\r\n";
        // $strSQL .= "      ,ETC_SKI_RYO" . "\r\n";
        // $strSQL .= "      ,SRY_GENKAI_RIE" . "\r\n";
        // $strSQL .= "      ,PENALTY" . "\r\n";
        // $strSQL .= "      ,EGO_GAI_SYUEKI" . "\r\n";
        // $strSQL .= "      ,SAI_SONEKI" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_KHN_MGN" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_RUI_MGN" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_KHN_SYR" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_TOK_KIN" . "\r\n";
        // $strSQL .= "      ,GNK_HJN_PCS" . "\r\n";
        // $strSQL .= "      ,GNK_SIT_URI_SKI" . "\r\n";
        // $strSQL .= "      ,CKO_BAI_SIT_KIN" . "\r\n";
        // $strSQL .= "      ,CKO_BAI_SATEI" . "\r\n";
        // $strSQL .= "      ,CKO_SAI_MITUMORI" . "\r\n";
        // $strSQL .= "      ,CKO_SYOGAKARI" . "\r\n";
        // $strSQL .= "      ,CKO_BAI_SATEI_SOKAI" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JDO_KIN" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JDO_SHZ" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JIBAI_KIN" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        // $strSQL .= "      ,NYK_KB" . "\r\n";
        // $strSQL .= "      ,DM_KB" . "\r\n";
        // $strSQL .= "      ,KYOUSINKAI_KYK" . "\r\n";
        // $strSQL .= "      ,KYOUSINKAI_SKI" . "\r\n";
        // $strSQL .= "      ,KYOUSINKAI_KOKEN" . "\r\n";
        // $strSQL .= "      ,NEBIKI_RT" . "\r\n";
        // $strSQL .= "      ,KIJUN_NEBIKI_RT" . "\r\n";
        // $strSQL .= "      ,KOUSEI_SYOSYO" . "\r\n";
        // $strSQL .= "      ,JAF" . "\r\n";
        // $strSQL .= "      ,KICK_BACK" . "\r\n";
        // $strSQL .= "      ,YOTAK_KB" . "\r\n";
        // $strSQL .= "      ,RCY_YOT_KIN" . "\r\n";
        // $strSQL .= "      ,RCY_SKN_KAN_HI" . "\r\n";
        // $strSQL .= "      ,URI_DAISU" . "\r\n";
        // $strSQL .= "      ,TOU_DAISU" . "\r\n";
        // $strSQL .= "      ,KYK_YUBIN_NO" . "\r\n";
        // $strSQL .= "      ,KYK_KEY_MEI_YOSE" . "\r\n";
        // $strSQL .= "      ,KYK_KEY_CKU_CD" . "\r\n";
        // $strSQL .= "      ,KYK_KEY_TEL" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_NOKI_KNJ" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_TUSYO_KNJ" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_MEI" . "\r\n";
        // $strSQL .= "      ,KYK_MEI_KNJ1" . "\r\n";
        // $strSQL .= "      ,KYK_MEI_KNJ2" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_KN" . "\r\n";
        // $strSQL .= "      ,KYK_MEI_KN" . "\r\n";
        // $strSQL .= "      ,MGN_YUBIN_NO" . "\r\n";
        // $strSQL .= "      ,MGN_KEY_MEI_YOSE" . "\r\n";
        // $strSQL .= "      ,MGN_KEY_CKU_CD" . "\r\n";
        // $strSQL .= "      ,MGN_KEY_TEL" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_NOKI_KNJ" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_TUSYO_KNJ" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_MEI" . "\r\n";
        // $strSQL .= "      ,MGN_MEI_KNJ1" . "\r\n";
        // $strSQL .= "      ,MGN_MEI_KNJ2" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_KN" . "\r\n";
        // $strSQL .= "      ,MGN_MEI_KN" . "\r\n";
        // $strSQL .= "      ,MGN_SEX" . "\r\n";
        // $strSQL .= "      ,MGN_BRTDT" . "\r\n";
        // $strSQL .= "      ,OLD_CMN_NO" . "\r\n";
        // $strSQL .= "      ,DEL_DATE" . "\r\n";
        // $strSQL .= "      ,UPD_DATE" . "\r\n";
        // $strSQL .= "      ,CREATE_DATE" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KJN_GK" . "\r\n";
        // $strSQL .= "      ,SYUEKI_SYOKEI" . "\r\n";
        // //TODO 2006/12/08 UPD Start
        // $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        // $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        // $strSQL .= "      ,UPD_CLT_NM" . "\r\n";
        // //2006/12/08 UPD End
        // //2007/07/06 INS Start   パックDE753/パックDEメンテ
        // $strSQL .= "      ,PACK_DE_753" . "\r\n";
        // $strSQL .= "      ,PACK_DE_MENTE" . "\r\n";
        // //2007/07/06 INS End
        // //2009/12/21 INS Start
        // $strSQL .= "      ,KYK_CSRRANK" . "\r\n";
        // $strSQL .= "      ,SIY_CSRRANK" . "\r\n";
        // // '''strSQL.Append("      ,UC_KENSU_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,MI_JISSEKI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TOU_JISSEKI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TA_KYK_JI_TRK_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,JI_KYK_TA_TRK_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,MAKER_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,FUKUSHI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,SYAMEI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,URI_JISSEKI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,LEASE_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,SEAVICE_CAR_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,SAIBAI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,KARUTE_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TRKKB_URI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TRKKB_TOU_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TRKKB_SONTA_FLG" & vbCrLf)
        // $strSQL .= "      ,UC_KENSU" . "\r\n";
        // $strSQL .= "      ,MI_JISSEKI_DAISU" . "\r\n";
        // $strSQL .= "      ,TOU_JISSEKI_DAISU" . "\r\n";
        // $strSQL .= "      ,TA_KYK_JI_TRK_DAISU" . "\r\n";
        // $strSQL .= "      ,JI_KYK_TA_TRK_DAISU" . "\r\n";
        // $strSQL .= "      ,MAKER_DAISU" . "\r\n";
        // $strSQL .= "      ,FUKUSHI_DAISU" . "\r\n";
        // $strSQL .= "      ,SYAMEI_DAISU" . "\r\n";
        // $strSQL .= "      ,URI_JISSEKI_DAISU" . "\r\n";
        // $strSQL .= "      ,LEASE_DAISU" . "\r\n";
        // $strSQL .= "      ,SEAVICE_CAR_DAISU" . "\r\n";
        // $strSQL .= "      ,SAIBAI_DAISU" . "\r\n";
        // $strSQL .= "      ,KARUTE_DAISU" . "\r\n";
        // $strSQL .= "      ,TRKKB_URI_DAISU" . "\r\n";
        // $strSQL .= "      ,TRKKB_TOU_DAISU" . "\r\n";
        // $strSQL .= "      ,TRKKB_SONTA_DAISU" . "\r\n";
        // $strSQL .= "      ,KAIYAKU_DAISU" . "\r\n";
//
        // //2009/12/21 INS end
        // $strSQL .= "      )" . "\r\n";
        // $strSQL .= " SELECT" . "\r\n";
        // //strSQL.Append(clsComFnc.FncSqlNv(OrderInfo.OrderInfo1.経理日.Substring(0, 6)) & vbCrLf)
        // $strSQL .= "       KEIJYO_YM" . "\r\n";
        // $strSQL .= "      ,NAU_KB" . "\r\n";
        // $strSQL .= "      ,CMN_NO" . "\r\n";
        // $strSQL .= "      ,(SELECT NVL(MAX(JKN_HKO_RIRNO),0)+1 FROM HJYOUHEN WHERE CMN_NO = '@CMNNO')" . "\r\n";
        // $strSQL .= "      ,DECODE(SUBSTR(DATA_KB,1,1),'1','1X','2X')" . "\r\n";
        // $strSQL .= "      ,UC_NO" . "\r\n";
        // $strSQL .= "      ,URI_BUSYO_CD" . "\r\n";
        // $strSQL .= "      ,URI_TANNO" . "\r\n";
        // $strSQL .= "      ,URI_GYOSYA" . "\r\n";
        // $strSQL .= "      ,SAV_KTNCD" . "\r\n";
        // $strSQL .= "      ,URK_BUSYO_CD" . "\r\n";
        // $strSQL .= "      ,KYK_HNS" . "\r\n";
        // $strSQL .= "      ,TOU_HNS" . "\r\n";
        // $strSQL .= "      ,NTI_USR_CD" . "\r\n";
        // $strSQL .= "      ,TOU_DATE" . "\r\n";
        // $strSQL .= "      ,URG_DATE" . "\r\n";
        // $strSQL .= "      ,KRI_DATE" . "\r\n";
        // $strSQL .= "      ,CEL_DATE" . "\r\n";
        // $strSQL .= "      ,SYADAI" . "\r\n";
        // $strSQL .= "      ,CARNO" . "\r\n";
        // $strSQL .= "      ,MAKER_CD" . "\r\n";
        // $strSQL .= "      ,NENSIKI" . "\r\n";
        // $strSQL .= "      ,SITEI_NO" . "\r\n";
        // $strSQL .= "      ,RUIBETU_NO" . "\r\n";
        // $strSQL .= "      ,SS_CD" . "\r\n";
        // $strSQL .= "      ,TOA_NAME" . "\r\n";
        // $strSQL .= "      ,HBSS_CD" . "\r\n";
        // $strSQL .= "      ,KASOUNO" . "\r\n";
        // $strSQL .= "      ,YOHIN_A" . "\r\n";
        // $strSQL .= "      ,YOHIN_C" . "\r\n";
        // $strSQL .= "      ,YOHIN_H" . "\r\n";
        // $strSQL .= "      ,YOHIN_S" . "\r\n";
        // $strSQL .= "      ,RIKUJI_CD" . "\r\n";
        // $strSQL .= "      ,TOURK_NO1" . "\r\n";
        // $strSQL .= "      ,TOURK_NO2" . "\r\n";
        // $strSQL .= "      ,TOURK_NO3" . "\r\n";
        // $strSQL .= "      ,H59" . "\r\n";
        // $strSQL .= "      ,SYAKEN_EXP_DT" . "\r\n";
        // $strSQL .= "      ,KKR_CD" . "\r\n";
        // $strSQL .= "      ,NINKATA_CD" . "\r\n";
        // $strSQL .= "      ,SYANAI_KOSYO" . "\r\n";
        // $strSQL .= "      ,CHUKOSYA_SYD_YM" . "\r\n";
        // $strSQL .= "      ,CHUKOSYA_NYK_YM" . "\r\n";
        // $strSQL .= "      ,CHUMON_KB" . "\r\n";
        // $strSQL .= "      ,KASOU_KB" . "\r\n";
        // $strSQL .= "      ,AIM_FLG" . "\r\n";
        // $strSQL .= "      ,AIM_KBN" . "\r\n";
        // $strSQL .= "      ,KYK_KB" . "\r\n";
        // $strSQL .= "      ,ZERO_KB" . "\r\n";
        // $strSQL .= "      ,SYOYUKEN_KB" . "\r\n";
        // $strSQL .= "      ,ITEN_KB" . "\r\n";
        // $strSQL .= "      ,YOUTO_KB" . "\r\n";
        // $strSQL .= "      ,KANRI_KB" . "\r\n";
        // $strSQL .= "      ,HNBCHK_KB" . "\r\n";
        // $strSQL .= "      ,KGO_KB" . "\r\n";
        // $strSQL .= "      ,BUY_SHP" . "\r\n";
        // $strSQL .= "      ,MZD_SIY" . "\r\n";
        // $strSQL .= "      ,LEASE_KB" . "\r\n";
        // $strSQL .= "      ,LEASE_KB2" . "\r\n";
        // $strSQL .= "      ,LES_SHP1" . "\r\n";
        // $strSQL .= "      ,LES_SHP2" . "\r\n";
        // $strSQL .= "      ,KAP_KB" . "\r\n";
        // $strSQL .= "      ,HNB_KB" . "\r\n";
        // $strSQL .= "      ,OPT_HOK_KNY_KB" . "\r\n";
        // $strSQL .= "      ,TRK_KB" . "\r\n";
        // $strSQL .= "      ,ZAIKO_KB" . "\r\n";
        // $strSQL .= "      ,KSO_KENSA" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_NAIYO" . "\r\n";
        // $strSQL .= "      ,KSO_KB" . "\r\n";
        // $strSQL .= "      ,KAZEI_KB" . "\r\n";
        // $strSQL .= "      ,DAINO_FLG" . "\r\n";
        // $strSQL .= "      ,JIBAI_FLG" . "\r\n";
        // $strSQL .= "      ,SIH_SIT_KB" . "\r\n";
        // $strSQL .= "      ,PAY_OFF_FLG" . "\r\n";
        // $strSQL .= "      ,SWK_SUM_FLG" . "\r\n";
        // $strSQL .= "      ,CKG_KB" . "\r\n";
        // $strSQL .= "      ,CKO_HNB_KB" . "\r\n";
        // $strSQL .= "      ,CKO_SS_KB" . "\r\n";
        // $strSQL .= "      ,CKO_SIR_KB" . "\r\n";
        // $strSQL .= "      ,CKO_SEB_KB" . "\r\n";
        // $strSQL .= "      ,CKO_MEG_KB" . "\r\n";
        // $strSQL .= "      ,CKO_MHN_KB" . "\r\n";
        // $strSQL .= "      ,CKO_UCNO" . "\r\n";
        // $strSQL .= "      ,CKO_CAR_SER_NO" . "\r\n";
        // $strSQL .= "      ,JKN_HKD_AK" . "\r\n";
        // $strSQL .= "      ,JKN_HK_NAIYO" . "\r\n";
        // $strSQL .= "      ,JKN_HKD" . "\r\n";
        // $strSQL .= "      ,JKN_NO" . "\r\n";
        // $strSQL .= "      ,SIT_SEIRINO1" . "\r\n";
        // $strSQL .= "      ,SIT_SEIRINO2" . "\r\n";
        // $strSQL .= "      ,SIT_SEIRINO3" . "\r\n";
        // $strSQL .= "      ,GYO_NAME" . "\r\n";
        // $strSQL .= "      ,MEG_NAME" . "\r\n";
        // $strSQL .= "      ,UC_OYA2" . "\r\n";
        // $strSQL .= "      ,TGT_SIT" . "\r\n";
        // $strSQL .= "      ,SRY_PRC" . "\r\n";
        // $strSQL .= "      ,SRY_NBK" . "\r\n";
        // $strSQL .= "      ,SRY_CMN_PCS" . "\r\n";
        // $strSQL .= "      ,SRY_KTN_PCS" . "\r\n";
        // $strSQL .= "      ,SRY_BUY_PCS" . "\r\n";
        // $strSQL .= "      ,SRY_SHZ_RT" . "\r\n";
        // $strSQL .= "      ,SRY_SHZ" . "\r\n";
        // $strSQL .= "      ,FHZ_TEIKA" . "\r\n";
        // $strSQL .= "      ,FHZ_NBK" . "\r\n";
        // $strSQL .= "      ,FHZ_KYK" . "\r\n";
        // $strSQL .= "      ,FHZ_PCS" . "\r\n";
        // $strSQL .= "      ,FHZ_SHZ" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_TEIKA" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_NBK" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_KYK" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_PCS" . "\r\n";
        // $strSQL .= "      ,TKB_KSH_SHZ" . "\r\n";
        // $strSQL .= "      ,KAP_TES_KYK" . "\r\n";
        // $strSQL .= "      ,KAP_TES_KJN" . "\r\n";
        // $strSQL .= "      ,KAP_TES_RT" . "\r\n";
        // $strSQL .= "      ,KAP_TES_SHZ" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KYK" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KJN" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SHZ" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_GK" . "\r\n";
        // $strSQL .= "      ,HKN_GK" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_ZSI_SUM" . "\r\n";
        // $strSQL .= "      ,SHR_GK_SUM" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_SIT_KIN" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_SIT_SHZ" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_ATM_KIN" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_TRK_SYH" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_CKO_FTK" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_TGT_KAI" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_TGT_KIN" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_KRJ_KAI" . "\r\n";
        // $strSQL .= "      ,SHR_JKN_KRJ_KIN" . "\r\n";
        // $strSQL .= "      ,KUREJITGAISYA" . "\r\n";
        // $strSQL .= "      ,KUREJIT_NO" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_RCYYTK_SUM" . "\r\n";
        // $strSQL .= "      ,KAP_GKN" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_PRC_SUM" . "\r\n";
        // $strSQL .= "      ,TRA_CAR_STI_SUM" . "\r\n";
        // $strSQL .= "      ,JIDOUSYA_ZEI" . "\r\n";
        // $strSQL .= "      ,SYARYOU_ZEI" . "\r\n";
        // $strSQL .= "      ,EAKON_ZEI" . "\r\n";
        // $strSQL .= "      ,SUTEREO_ZEI" . "\r\n";
        // $strSQL .= "      ,JYURYO_ZEI" . "\r\n";
        // $strSQL .= "      ,SHZ_KEI" . "\r\n";
        // $strSQL .= "      ,JIBAI_SITEI" . "\r\n";
        // $strSQL .= "      ,JIBAI_KAISYA" . "\r\n";
        // $strSQL .= "      ,JIBAI_CAR_KND" . "\r\n";
        // $strSQL .= "      ,JIBAI_ICOL_CD" . "\r\n";
        // $strSQL .= "      ,JIBAI_TUKI_SU" . "\r\n";
        // $strSQL .= "      ,JIBAI_HOK_RYO" . "\r\n";
        // $strSQL .= "      ,OPTHOK_RYO" . "\r\n";
        // $strSQL .= "      ,HNB_TES_RYO_KZI_KBN" . "\r\n";
        // $strSQL .= "      ,HNB_TES_RYO_SHR_CD" . "\r\n";
        // $strSQL .= "      ,HNB_TES_GKU" . "\r\n";
        // $strSQL .= "      ,HNB_SHZ" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KEN" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SYAKEN" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SYAKO_SYO" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_NOUSYA" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SIT_TTK" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_SATEI" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_JIKOU" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_ETC" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_KEN" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_SYAKEN" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_SYAKO_SYO" . "\r\n";
        // $strSQL .= "      ,HOUTEIH_SIT" . "\r\n";
        // $strSQL .= "      ,HONBU_FTK" . "\r\n";
        // $strSQL .= "      ,UKM_SNY_TES" . "\r\n";
        // $strSQL .= "      ,UKM_SINSEI_SYR" . "\r\n";
        // $strSQL .= "      ,KAP_TES_SGK" . "\r\n";
        // $strSQL .= "      ,ETC_SKI_RYO" . "\r\n";
        // $strSQL .= "      ,SRY_GENKAI_RIE" . "\r\n";
        // $strSQL .= "      ,PENALTY" . "\r\n";
        // $strSQL .= "      ,EGO_GAI_SYUEKI" . "\r\n";
        // $strSQL .= "      ,SAI_SONEKI" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_KHN_MGN" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_RUI_MGN" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_KHN_SYR" . "\r\n";
        // $strSQL .= "      ,TOK_KEI_TOK_KIN" . "\r\n";
        // $strSQL .= "      ,GNK_HJN_PCS" . "\r\n";
        // $strSQL .= "      ,GNK_SIT_URI_SKI" . "\r\n";
        // $strSQL .= "      ,CKO_BAI_SIT_KIN" . "\r\n";
        // $strSQL .= "      ,CKO_BAI_SATEI" . "\r\n";
        // $strSQL .= "      ,CKO_SAI_MITUMORI" . "\r\n";
        // $strSQL .= "      ,CKO_SYOGAKARI" . "\r\n";
        // $strSQL .= "      ,CKO_BAI_SATEI_SOKAI" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JDO_KIN" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JDO_SHZ" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JIBAI_KIN" . "\r\n";
        // $strSQL .= "      ,CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        // $strSQL .= "      ,NYK_KB" . "\r\n";
        // $strSQL .= "      ,DM_KB" . "\r\n";
        // $strSQL .= "      ,KYOUSINKAI_KYK" . "\r\n";
        // $strSQL .= "      ,KYOUSINKAI_SKI" . "\r\n";
        // $strSQL .= "      ,KYOUSINKAI_KOKEN" . "\r\n";
        // $strSQL .= "      ,NEBIKI_RT" . "\r\n";
        // $strSQL .= "      ,KIJUN_NEBIKI_RT" . "\r\n";
        // $strSQL .= "      ,KOUSEI_SYOSYO" . "\r\n";
        // $strSQL .= "      ,JAF" . "\r\n";
        // $strSQL .= "      ,KICK_BACK" . "\r\n";
        // $strSQL .= "      ,YOTAK_KB" . "\r\n";
        // $strSQL .= "      ,RCY_YOT_KIN" . "\r\n";
        // $strSQL .= "      ,RCY_SKN_KAN_HI" . "\r\n";
        // $strSQL .= "      ,URI_DAISU" . "\r\n";
        // $strSQL .= "      ,TOU_DAISU" . "\r\n";
        // $strSQL .= "      ,KYK_YUBIN_NO" . "\r\n";
        // $strSQL .= "      ,KYK_KEY_MEI_YOSE" . "\r\n";
        // $strSQL .= "      ,KYK_KEY_CKU_CD" . "\r\n";
        // $strSQL .= "      ,KYK_KEY_TEL" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_NOKI_KNJ" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_TUSYO_KNJ" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_MEI" . "\r\n";
        // $strSQL .= "      ,KYK_MEI_KNJ1" . "\r\n";
        // $strSQL .= "      ,KYK_MEI_KNJ2" . "\r\n";
        // $strSQL .= "      ,KYK_ADR_KN" . "\r\n";
        // $strSQL .= "      ,KYK_MEI_KN" . "\r\n";
        // $strSQL .= "      ,MGN_YUBIN_NO" . "\r\n";
        // $strSQL .= "      ,MGN_KEY_MEI_YOSE" . "\r\n";
        // $strSQL .= "      ,MGN_KEY_CKU_CD" . "\r\n";
        // $strSQL .= "      ,MGN_KEY_TEL" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_NOKI_KNJ" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_TUSYO_KNJ" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_MEI" . "\r\n";
        // $strSQL .= "      ,MGN_MEI_KNJ1" . "\r\n";
        // $strSQL .= "      ,MGN_MEI_KNJ2" . "\r\n";
        // $strSQL .= "      ,MGN_ADR_KN" . "\r\n";
        // $strSQL .= "      ,MGN_MEI_KN" . "\r\n";
        // $strSQL .= "      ,MGN_SEX" . "\r\n";
        // $strSQL .= "      ,MGN_BRTDT" . "\r\n";
        // $strSQL .= "      ,OLD_CMN_NO" . "\r\n";
        // $strSQL .= "      ,DEL_DATE" . "\r\n";
        // $strSQL .= "      ,UPD_DATE" . "\r\n";
        // $strSQL .= "      ,CREATE_DATE" . "\r\n";
        // $strSQL .= "      ,TOU_SYH_KJN_GK" . "\r\n";
        // $strSQL .= "      ,SYUEKI_SYOKEI" . "\r\n";
        // //TODO 2006/12/08 UPD Start
        // $strSQL .= "      ,'@UPDUSER'" . "\r\n";
        // $strSQL .= "      ,'@UPDAPP'" . "\r\n";
        // $strSQL .= "      ,'@UPDCLT'" . "\r\n";
        // //2006/12/08 UPD End
        // //2007/07/06 INS Start   パックDE753/パックDEメンテ
        // $strSQL .= "      ,PACK_DE_753" . "\r\n";
        // $strSQL .= "      ,PACK_DE_MENTE" . "\r\n";
        // //2007/07/06 INS End
        // //2009/12/21 INS Start
        // $strSQL .= "      ,KYK_CSRRANK" . "\r\n";
        // $strSQL .= "      ,SIY_CSRRANK" . "\r\n";
        // // '''strSQL.Append("      ,UC_KENSU_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,MI_JISSEKI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TOU_JISSEKI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TA_KYK_JI_TRK_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,JI_KYK_TA_TRK_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,MAKER_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,FUKUSHI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,SYAMEI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,URI_JISSEKI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,LEASE_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,SEAVICE_CAR_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,SAIBAI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,KARUTE_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TRKKB_URI_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TRKKB_TOU_FLG" & vbCrLf)
        // // '''strSQL.Append("      ,TRKKB_SONTA_FLG" & vbCrLf)
        // $strSQL .= "      ,UC_KENSU" . "\r\n";
        // $strSQL .= "      ,MI_JISSEKI_DAISU" . "\r\n";
        // $strSQL .= "      ,TOU_JISSEKI_DAISU" . "\r\n";
        // $strSQL .= "      ,TA_KYK_JI_TRK_DAISU" . "\r\n";
        // $strSQL .= "      ,JI_KYK_TA_TRK_DAISU" . "\r\n";
        // $strSQL .= "      ,MAKER_DAISU" . "\r\n";
        // $strSQL .= "      ,FUKUSHI_DAISU" . "\r\n";
        // $strSQL .= "      ,SYAMEI_DAISU" . "\r\n";
        // $strSQL .= "      ,URI_JISSEKI_DAISU" . "\r\n";
        // $strSQL .= "      ,LEASE_DAISU" . "\r\n";
        // $strSQL .= "      ,SEAVICE_CAR_DAISU" . "\r\n";
        // $strSQL .= "      ,SAIBAI_DAISU" . "\r\n";
        // $strSQL .= "      ,KARUTE_DAISU" . "\r\n";
        // $strSQL .= "      ,TRKKB_URI_DAISU" . "\r\n";
        // $strSQL .= "      ,TRKKB_TOU_DAISU" . "\r\n";
        // $strSQL .= "      ,TRKKB_SONTA_DAISU" . "\r\n";
        // $strSQL .= "      ,KAIYAKU_DAISU" . "\r\n";
        // //2009/12/21 INS end
        // $strSQL .= " FROM  HSCURI" . "\r\n";
        // $strSQL .= " WHERE CMN_NO = '@CMNNO'" . "\r\n";

        $strSQL .= " INSERT INTO HJYOUHENSIT" . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "        KEIJYO_YM" . "\r\n";
        $strSQL .= "       ,CMN_NO" . "\r\n";
        $strSQL .= "       ,JKN_HKO_RIRNO" . "\r\n";
        $strSQL .= "       ,TRA_CARSEQ_NO" . "\r\n";
        $strSQL .= "       ,OYA_CMN_NO" . "\r\n";
        $strSQL .= "       ,URI_CMN_NO" . "\r\n";
        $strSQL .= "       ,SIT_SW" . "\r\n";
        $strSQL .= "       ,KAI_RIYU" . "\r\n";
        $strSQL .= "       ,GEN_SIK" . "\r\n";
        $strSQL .= "       ,MEIGARA" . "\r\n";
        $strSQL .= "       ,SEIREKI_NEN" . "\r\n";
        $strSQL .= "       ,SYAKEN_KAT" . "\r\n";
        $strSQL .= "       ,CARNO" . "\r\n";
        $strSQL .= "       ,SYAMEI" . "\r\n";
        $strSQL .= "       ,KATASIKI" . "\r\n";
        $strSQL .= "       ,RUIIBETU" . "\r\n";
        $strSQL .= "       ,TOU_Y_DT" . "\r\n";
        $strSQL .= "       ,RIKUJI" . "\r\n";
        $strSQL .= "       ,TOU_NO1" . "\r\n";
        $strSQL .= "       ,TOU_NO2" . "\r\n";
        $strSQL .= "       ,TOU_NO3" . "\r\n";
        $strSQL .= "       ,H59" . "\r\n";
        $strSQL .= "       ,SIT_KIN" . "\r\n";
        $strSQL .= "       ,SAT_KIN" . "\r\n";
        $strSQL .= "       ,JITU_SAT_KIN" . "\r\n";
        $strSQL .= "       ,SHZ_RT" . "\r\n";
        $strSQL .= "       ,SHZ_GK" . "\r\n";
        $strSQL .= "       ,YOTAK_GK" . "\r\n";
        $strSQL .= "       ,SHIKIN_KNR_RYOKIN" . "\r\n";
        $strSQL .= "       ,YOTAK_KB" . "\r\n";
        $strSQL .= "       ,TEBANASHI_KB" . "\r\n";
        $strSQL .= "       ,UPD_DATE" . "\r\n";
        $strSQL .= "       ,CREATE_DATE" . "\r\n";
        // 'TODO 2006/12/08 UPD Start
        $strSQL .= "       ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "       ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "       ,UPD_CLT_NM" . "\r\n";
        // '2006/12/08 UPD End
        $strSQL .= "      )" . "\r\n";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "        KEIJYO_YM" . "\r\n";
        // 'strSQL.Append(clsComFnc.FncSqlNv(orderinfo.OrderInfo1.経理日.Substring(0, 6)) . "\r\n";
        $strSQL .= "       ,CMN_NO" . "\r\n";
        $strSQL .= "       ,(SELECT NVL(MAX(JKN_HKO_RIRNO),0) FROM HJYOUHEN WHERE CMN_NO = '@CMNNO')" . "\r\n";
        $strSQL .= "       ,TRA_CARSEQ_NO" . "\r\n";
        $strSQL .= "       ,OYA_CMN_NO" . "\r\n";
        $strSQL .= "       ,URI_CMN_NO" . "\r\n";
        $strSQL .= "       ,SIT_SW" . "\r\n";
        $strSQL .= "       ,KAI_RIYU" . "\r\n";
        $strSQL .= "       ,GEN_SIK" . "\r\n";
        $strSQL .= "       ,MEIGARA" . "\r\n";
        $strSQL .= "       ,SEIREKI_NEN" . "\r\n";
        $strSQL .= "       ,SYAKEN_KAT" . "\r\n";
        $strSQL .= "       ,CARNO" . "\r\n";
        $strSQL .= "       ,SYAMEI" . "\r\n";
        $strSQL .= "       ,KATASIKI" . "\r\n";
        $strSQL .= "       ,RUIIBETU" . "\r\n";
        $strSQL .= "       ,TOU_Y_DT" . "\r\n";
        $strSQL .= "       ,RIKUJI" . "\r\n";
        $strSQL .= "       ,TOU_NO1" . "\r\n";
        $strSQL .= "       ,TOU_NO2" . "\r\n";
        $strSQL .= "       ,TOU_NO3" . "\r\n";
        $strSQL .= "       ,H59" . "\r\n";
        $strSQL .= "       ,SIT_KIN" . "\r\n";
        $strSQL .= "       ,SAT_KIN" . "\r\n";
        $strSQL .= "       ,JITU_SAT_KIN" . "\r\n";
        $strSQL .= "       ,SHZ_RT" . "\r\n";
        $strSQL .= "       ,SHZ_GK" . "\r\n";
        $strSQL .= "       ,YOTAK_GK" . "\r\n";
        $strSQL .= "       ,SHIKIN_KNR_RYOKIN" . "\r\n";
        $strSQL .= "       ,YOTAK_KB" . "\r\n";
        $strSQL .= "       ,TEBANASHI_KB" . "\r\n";
        $strSQL .= "       ,UPD_DATE" . "\r\n";
        $strSQL .= "       ,CREATE_DATE" . "\r\n";
        // 'TODO 2006/12/08 UPD Start
        $strSQL .= "       ,'@UPDUSER'" . "\r\n";
        $strSQL .= "       ,'@UPDAPP'" . "\r\n";
        $strSQL .= "       ,'@UPDCLT'" . "\r\n";
        // '2006/12/08 UPD End
        $strSQL .= " FROM  HSCSIT" . "\r\n";
        $strSQL .= " WHERE CMN_NO = '@CMNNO'" . "\r\n";
        //---20160120 li UPD E.

        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：条件変更履歴データ削除処理
    //関 数 名：fncJyuhenDeleteSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：新中売上データの削除処理を行う
    //**********************************************************************
    public function fncJyuhenDeleteSQL($strCMNNO, $strKeijyoYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        $strSQL .= "FROM   HJYOUHEN" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "AND    KEIJYO_YM = '@KEIJYOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", $strKeijyoYM, $strSQL);
        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：下取データ削除処理
    //関 数 名：fncJYOHENSITDeleteSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：下取データの削除処理を行う
    //**********************************************************************
    public function fncJYOHENSITDeleteSQL($strCMNNO, $strKEIJYOYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        $strSQL .= "FROM   HJYOUHENSIT" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "AND    KEIJYO_YM = '@KEIJYOYM'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        $strSQL = str_replace("@KEIJYOYM", $strKEIJYOYM, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：前回取得日取得
    //関 数 名：fncGetBEFGETDTSQL
    //引    数：strTableId：ﾃｰﾌﾞﾙID　
    //戻 り 値：SQL
    //処理説明：ﾃﾞｰﾀ受信ﾃｰﾌﾞﾙより前回CSV作成日付を取得する
    //**********************************************************************
    public function fncGetBEFGETDTSQL($strTableId)
    {
        $strSQL = "";
        $strSQL .= "SELECT BEF_CSVPUT_DT" . "\r\n";
        $strSQL .= "  FROM M_DATARECEP" . "\r\n";
        $strSQL .= " WHERE TABLE_ID = '" . $strTableId . "'" . "\r\n";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新中売上データ削除処理
    //関 数 名：fncSCURIDeleteSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：新中売上データの削除処理を行う
    //**********************************************************************
    function fncSCURIDeleteSQL($strCMNNO)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        $strSQL .= "FROM   HSCURI" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新中売上データ登録(SQL)
    //関 数 名：fncSCURIInsertSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL文
    //処理説明：新中売上データ登録(SQL)
    //**********************************************************************
    //2006/12/11 UPD 引数追加
    function fncSCURIInsertSQL($orderinfo, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL .= " INSERT INTO HSCURI" . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "       KEIJYO_YM" . "\r\n";
        $strSQL .= "      ,NAU_KB" . "\r\n";
        $strSQL .= "      ,CMN_NO" . "\r\n";
        $strSQL .= "      ,DATA_KB" . "\r\n";
        $strSQL .= "      ,UC_NO" . "\r\n";
        $strSQL .= "      ,URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,URI_TANNO" . "\r\n";
        $strSQL .= "      ,URI_GYOSYA" . "\r\n";
        $strSQL .= "      ,SAV_KTNCD" . "\r\n";
        $strSQL .= "      ,URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,KYK_HNS" . "\r\n";
        $strSQL .= "      ,TOU_HNS" . "\r\n";
        $strSQL .= "      ,NTI_USR_CD" . "\r\n";
        $strSQL .= "      ,TOU_DATE" . "\r\n";
        $strSQL .= "      ,URG_DATE" . "\r\n";
        $strSQL .= "      ,KRI_DATE" . "\r\n";
        $strSQL .= "      ,CEL_DATE" . "\r\n";
        $strSQL .= "      ,SYADAI" . "\r\n";
        $strSQL .= "      ,CARNO" . "\r\n";
        $strSQL .= "      ,MAKER_CD" . "\r\n";
        $strSQL .= "      ,NENSIKI" . "\r\n";
        $strSQL .= "      ,SITEI_NO" . "\r\n";
        $strSQL .= "      ,RUIBETU_NO" . "\r\n";
        $strSQL .= "      ,SS_CD" . "\r\n";
        $strSQL .= "      ,TOA_NAME" . "\r\n";
        $strSQL .= "      ,HBSS_CD" . "\r\n";
        $strSQL .= "      ,KASOUNO" . "\r\n";
        $strSQL .= "      ,YOHIN_A" . "\r\n";
        $strSQL .= "      ,YOHIN_C" . "\r\n";
        $strSQL .= "      ,YOHIN_H" . "\r\n";
        $strSQL .= "      ,YOHIN_S" . "\r\n";
        $strSQL .= "      ,RIKUJI_CD" . "\r\n";
        $strSQL .= "      ,TOURK_NO1" . "\r\n";
        $strSQL .= "      ,TOURK_NO2" . "\r\n";
        $strSQL .= "      ,TOURK_NO3" . "\r\n";
        $strSQL .= "      ,H59" . "\r\n";
        $strSQL .= "      ,SYAKEN_EXP_DT" . "\r\n";
        $strSQL .= "      ,KKR_CD" . "\r\n";
        $strSQL .= "      ,NINKATA_CD" . "\r\n";
        $strSQL .= "      ,SYANAI_KOSYO" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_SYD_YM" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_NYK_YM" . "\r\n";
        $strSQL .= "      ,CHUMON_KB" . "\r\n";
        $strSQL .= "      ,KASOU_KB" . "\r\n";
        $strSQL .= "      ,AIM_FLG" . "\r\n";
        $strSQL .= "      ,AIM_KBN" . "\r\n";
        $strSQL .= "      ,KYK_KB" . "\r\n";
        $strSQL .= "      ,ZERO_KB" . "\r\n";
        $strSQL .= "      ,SYOYUKEN_KB" . "\r\n";
        $strSQL .= "      ,ITEN_KB" . "\r\n";
        $strSQL .= "      ,YOUTO_KB" . "\r\n";
        $strSQL .= "      ,KANRI_KB" . "\r\n";
        $strSQL .= "      ,HNBCHK_KB" . "\r\n";
        $strSQL .= "      ,KGO_KB" . "\r\n";
        $strSQL .= "      ,BUY_SHP" . "\r\n";
        $strSQL .= "      ,MZD_SIY" . "\r\n";
        $strSQL .= "      ,LEASE_KB" . "\r\n";
        $strSQL .= "      ,LEASE_KB2" . "\r\n";
        $strSQL .= "      ,LES_SHP1" . "\r\n";
        $strSQL .= "      ,LES_SHP2" . "\r\n";
        $strSQL .= "      ,KAP_KB" . "\r\n";
        $strSQL .= "      ,HNB_KB" . "\r\n";
        $strSQL .= "      ,OPT_HOK_KNY_KB" . "\r\n";
        $strSQL .= "      ,TRK_KB" . "\r\n";
        $strSQL .= "      ,ZAIKO_KB" . "\r\n";
        $strSQL .= "      ,KSO_KENSA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NAIYO" . "\r\n";
        $strSQL .= "      ,KSO_KB" . "\r\n";
        $strSQL .= "      ,KAZEI_KB" . "\r\n";
        $strSQL .= "      ,DAINO_FLG" . "\r\n";
        $strSQL .= "      ,JIBAI_FLG" . "\r\n";
        $strSQL .= "      ,SIH_SIT_KB" . "\r\n";
        $strSQL .= "      ,PAY_OFF_FLG" . "\r\n";
        $strSQL .= "      ,SWK_SUM_FLG" . "\r\n";
        $strSQL .= "      ,CKG_KB" . "\r\n";
        $strSQL .= "      ,CKO_HNB_KB" . "\r\n";
        $strSQL .= "      ,CKO_SS_KB" . "\r\n";
        $strSQL .= "      ,CKO_SIR_KB" . "\r\n";
        $strSQL .= "      ,CKO_SEB_KB" . "\r\n";
        $strSQL .= "      ,CKO_MEG_KB" . "\r\n";
        $strSQL .= "      ,CKO_MHN_KB" . "\r\n";
        $strSQL .= "      ,CKO_UCNO" . "\r\n";
        $strSQL .= "      ,CKO_CAR_SER_NO" . "\r\n";
        $strSQL .= "      ,JKN_HKD_AK" . "\r\n";
        $strSQL .= "      ,JKN_HK_NAIYO" . "\r\n";
        $strSQL .= "      ,JKN_HKD" . "\r\n";
        $strSQL .= "      ,JKN_NO" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO1" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO2" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO3" . "\r\n";
        $strSQL .= "      ,GYO_NAME" . "\r\n";
        $strSQL .= "      ,MEG_NAME" . "\r\n";
        $strSQL .= "      ,UC_OYA2" . "\r\n";
        $strSQL .= "      ,TGT_SIT" . "\r\n";
        $strSQL .= "      ,SRY_PRC" . "\r\n";
        $strSQL .= "      ,SRY_NBK" . "\r\n";
        $strSQL .= "      ,SRY_CMN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_KTN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_BUY_PCS" . "\r\n";
        $strSQL .= "      ,SRY_SHZ_RT" . "\r\n";
        $strSQL .= "      ,SRY_SHZ" . "\r\n";
        $strSQL .= "      ,FHZ_TEIKA" . "\r\n";
        $strSQL .= "      ,FHZ_NBK" . "\r\n";
        $strSQL .= "      ,FHZ_KYK" . "\r\n";
        $strSQL .= "      ,FHZ_PCS" . "\r\n";
        $strSQL .= "      ,FHZ_SHZ" . "\r\n";
        $strSQL .= "      ,TKB_KSH_TEIKA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NBK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_KYK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_PCS" . "\r\n";
        $strSQL .= "      ,TKB_KSH_SHZ" . "\r\n";
        $strSQL .= "      ,KAP_TES_KYK" . "\r\n";
        $strSQL .= "      ,KAP_TES_KJN" . "\r\n";
        $strSQL .= "      ,KAP_TES_RT" . "\r\n";
        $strSQL .= "      ,KAP_TES_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KYK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KJN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SHZ" . "\r\n";
        $strSQL .= "      ,HOUTEIH_GK" . "\r\n";
        $strSQL .= "      ,HKN_GK" . "\r\n";
        $strSQL .= "      ,TRA_CAR_ZSI_SUM" . "\r\n";
        $strSQL .= "      ,SHR_GK_SUM" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_SHZ" . "\r\n";
        $strSQL .= "      ,SHR_JKN_ATM_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TRK_SYH" . "\r\n";
        $strSQL .= "      ,SHR_JKN_CKO_FTK" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KIN" . "\r\n";
        $strSQL .= "      ,KUREJITGAISYA" . "\r\n";
        $strSQL .= "      ,KUREJIT_NO" . "\r\n";
        $strSQL .= "      ,TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSQL .= "      ,KAP_GKN" . "\r\n";
        $strSQL .= "      ,TRA_CAR_PRC_SUM" . "\r\n";
        $strSQL .= "      ,TRA_CAR_STI_SUM" . "\r\n";
        $strSQL .= "      ,JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= "      ,SYARYOU_ZEI" . "\r\n";
        $strSQL .= "      ,EAKON_ZEI" . "\r\n";
        $strSQL .= "      ,SUTEREO_ZEI" . "\r\n";
        $strSQL .= "      ,JYURYO_ZEI" . "\r\n";
        $strSQL .= "      ,SHZ_KEI" . "\r\n";
        $strSQL .= "      ,JIBAI_SITEI" . "\r\n";
        $strSQL .= "      ,JIBAI_KAISYA" . "\r\n";
        $strSQL .= "      ,JIBAI_CAR_KND" . "\r\n";
        $strSQL .= "      ,JIBAI_ICOL_CD" . "\r\n";
        $strSQL .= "      ,JIBAI_TUKI_SU" . "\r\n";
        $strSQL .= "      ,JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= "      ,OPTHOK_RYO" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_KZI_KBN" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_SHR_CD" . "\r\n";
        $strSQL .= "      ,HNB_TES_GKU" . "\r\n";
        $strSQL .= "      ,HNB_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,TOU_SYH_NOUSYA" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SIT_TTK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SATEI" . "\r\n";
        $strSQL .= "      ,TOU_SYH_JIKOU" . "\r\n";
        $strSQL .= "      ,TOU_SYH_ETC" . "\r\n";
        $strSQL .= "      ,HOUTEIH_KEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SIT" . "\r\n";
        $strSQL .= "      ,HONBU_FTK" . "\r\n";
        $strSQL .= "      ,UKM_SNY_TES" . "\r\n";
        $strSQL .= "      ,UKM_SINSEI_SYR" . "\r\n";
        $strSQL .= "      ,KAP_TES_SGK" . "\r\n";
        $strSQL .= "      ,ETC_SKI_RYO" . "\r\n";
        $strSQL .= "      ,SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= "      ,PENALTY" . "\r\n";
        $strSQL .= "      ,EGO_GAI_SYUEKI" . "\r\n";
        $strSQL .= "      ,SAI_SONEKI" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_RUI_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_SYR" . "\r\n";
        $strSQL .= "      ,TOK_KEI_TOK_KIN" . "\r\n";
        $strSQL .= "      ,GNK_HJN_PCS" . "\r\n";
        $strSQL .= "      ,GNK_SIT_URI_SKI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SIT_KIN" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI" . "\r\n";
        $strSQL .= "      ,CKO_SAI_MITUMORI" . "\r\n";
        $strSQL .= "      ,CKO_SYOGAKARI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI_SOKAI" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_SHZ" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        $strSQL .= "      ,NYK_KB" . "\r\n";
        $strSQL .= "      ,DM_KB" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KYK" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_SKI" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KOKEN" . "\r\n";
        $strSQL .= "      ,NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KIJUN_NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KOUSEI_SYOSYO" . "\r\n";
        $strSQL .= "      ,JAF" . "\r\n";
        $strSQL .= "      ,KICK_BACK" . "\r\n";
        $strSQL .= "      ,YOTAK_KB" . "\r\n";
        $strSQL .= "      ,RCY_YOT_KIN" . "\r\n";
        $strSQL .= "      ,RCY_SKN_KAN_HI" . "\r\n";
        $strSQL .= "      ,URI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_DAISU" . "\r\n";
        $strSQL .= "      ,KYK_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,KYK_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,KYK_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,KYK_KEY_TEL" . "\r\n";
        $strSQL .= "      ,KYK_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_MEI" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,KYK_ADR_KN" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,MGN_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,MGN_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,MGN_KEY_TEL" . "\r\n";
        $strSQL .= "      ,MGN_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_MEI" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,MGN_ADR_KN" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_SEX" . "\r\n";
        $strSQL .= "      ,MGN_BRTDT" . "\r\n";
        $strSQL .= "      ,OLD_CMN_NO" . "\r\n";
        $strSQL .= "      ,DEL_DATE" . "\r\n";
        $strSQL .= "      ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,CREATE_DATE" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KJN_GK" . "\r\n";
        $strSQL .= "      ,SYUEKI_SYOKEI" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "      ,UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End
        //2007/07/06 INS Start   PACK_DE_753を登録諸費用その他から分離　PACK_DE_MENTEを追加
        $strSQL .= "      ,PACK_DE_753" . "\r\n";
        $strSQL .= "      ,PACK_DE_MENTE" . "\r\n";
        //2007/07/06 INS End
        //2009/12/21 INS Start
        $strSQL .= "      ,KYK_CSRRANK" . "\r\n";
        $strSQL .= "      ,SIY_CSRRANK" . "\r\n";
        // '''strSQL.Append("      ,UC_KENSU_FLG" & vbCrLf)
        // '''strSQL.Append("      ,MI_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TOU_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TA_KYK_JI_TRK_FLG" & vbCrLf)
        // '''strSQL.Append("      ,JI_KYK_TA_TRK_FLG" & vbCrLf)
        // '''strSQL.Append("      ,MAKER_FLG" & vbCrLf)
        // '''strSQL.Append("      ,FUKUSHI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SYAMEI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,URI_JISSEKI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,LEASE_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SEAVICE_CAR_FLG" & vbCrLf)
        // '''strSQL.Append("      ,SAIBAI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,KARUTE_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_URI_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_TOU_FLG" & vbCrLf)
        // '''strSQL.Append("      ,TRKKB_SONTA_FLG" & vbCrLf)
        $strSQL .= "      ,UC_KENSU" . "\r\n";
        $strSQL .= "      ,MI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,TA_KYK_JI_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,JI_KYK_TA_TRK_DAISU" . "\r\n";
        $strSQL .= "      ,MAKER_DAISU" . "\r\n";
        $strSQL .= "      ,FUKUSHI_DAISU" . "\r\n";
        $strSQL .= "      ,SYAMEI_DAISU" . "\r\n";
        $strSQL .= "      ,URI_JISSEKI_DAISU" . "\r\n";
        $strSQL .= "      ,LEASE_DAISU" . "\r\n";
        $strSQL .= "      ,SEAVICE_CAR_DAISU" . "\r\n";
        $strSQL .= "      ,SAIBAI_DAISU" . "\r\n";
        $strSQL .= "      ,KARUTE_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_URI_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_TOU_DAISU" . "\r\n";
        $strSQL .= "      ,TRKKB_SONTA_DAISU" . "\r\n";
        $strSQL .= "      ,KAIYAKU_DAISU" . "\r\n";

        //2009/12/21 INS end

        $strSQL .= "      ) VALUES (" . "\r\n";

        $strSQL .= $this->ClsComFnc->FncSqlNv(mb_substr($orderinfo['OrderInfo1']['経理日'], 0, 6)) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['新中区分']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv((mb_substr($orderinfo['OrderInfo6']['データ区分'], 0, 1) == "1") ? "11" : "21") . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['UCNO']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上部署']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上セールス']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上業者']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['サービス']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売掛部署']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['契約店']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録店']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['認定特需ユーザーコード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['経理日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['解約日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['車台']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['CARNO']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['銘柄']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['年製']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['指定類別型式指定']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['指定類別区分']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['車種コード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['問合呼称']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['桁８コード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['新車架装整理NO']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品A']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品C']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品H']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品S']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['陸事']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録NO1']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録NO2']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録NO3']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['H59']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['車検年']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['くくりコード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['認可型式']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['社内呼称']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['中古車初度年月']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['中古車入荷年月']) . "\r\n";

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分01']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分02']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分03']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分04']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分05']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分06']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分07']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分08']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分09']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分10']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分11']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分12']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分13']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分14']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分15']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分16']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分17']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分18']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分19']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分20']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分21']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分22']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分23']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分24']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分25']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分26']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分27']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分28']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分29']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分30']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分31']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分32']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分33']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分34']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分35']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分36']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分37']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分38']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分39']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['中古車売上親UCNO']) . "\r\n";
        //--(10)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['中古車売車整理NO']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更赤黒']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更内容']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更年月日']) . "\r\n";
        //--(8)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更NO']) . "\r\n";
        //--(7)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['下取車整理NO1']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['下取車整理NO2']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['下取車整理NO3']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['業者名']) . "\r\n";
        //--(20)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.契約者名称カナ) & vbCrLf)              '--(27)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人区分) & vbCrLf)                  '--(1)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人誕生日) & vbCrLf)                '--(8)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人TEL) & vbCrLf)                   '--(12)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人地区CD) & vbCrLf)                '--(13)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人軒番カナ) & vbCrLf)              '--(20)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo3']['名義人名称']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo3']['親2桁コード']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['手形据置日数']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両値引']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両注文書原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両拠点原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両新車車両部署別用原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両消費税率']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両消費税額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品定価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品値引']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3定価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3値引']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様6原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料基準']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料消費税率']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料消費税額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3基準']) . "\r\n";
        //--(9)
        //strSQL.Append("," & clsComFnc.FncSqlNz(orderinfo.OrderInfo3.登録諸費用3基準NEW) & vbCrLf)              '--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['預り法廷費用']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['税金保険料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['残債']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払金合計']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件下取価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件下取車消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件頭金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件登録諸費用']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件中古車負担金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件手形回数']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件手形金額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ回数']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ金額']) . "\r\n";
        //--(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ会社']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ承認NO']) . "\r\n";
        //--(20)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['割賦元金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['下取者買取価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['下取者査定価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金自動車税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金車両取得税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金ｴｱｺﾝ取得税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金ｽﾃﾚｵ取得税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金重量税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責指定']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責会社']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責自動車種類']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責色コード']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['自賠責月数']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['自賠責保険料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['任意保険料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['販売手数料課税非課税']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['販売手数料支払先コード']) . "\r\n";
        //--(5)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['販売手数料額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['販売消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3検査']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3持込車検']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3車庫証明']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3納車費用']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3下取諸手続']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3査定料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3字光式']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3その他']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用検査']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用持込車検']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用車庫証明']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用下取']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['本部負担金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['打込金収入手数料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['打込金申請奨励金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['割賦手数料差額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['その他紹介料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['車両F号限界利益']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['ﾍﾟﾅﾙﾃｨ']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['営業外収益']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['最終損益']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約基本ﾏｰｼﾞﾝ']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約累進ﾏｰｼﾞﾝ']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約拡販奨励金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約特別価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['原価標準原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['原価下取車売上仕切']) . "\r\n";
        //--(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車売車下取価格']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車売車査定']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車再生見積']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車諸掛']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車売車査定ソカイ']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自動車税金額']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自動車税消費税']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自賠責金額']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自賠責消費税']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['入庫約束']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['DM送付']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['キョウシンカイ顧客']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['キョウシンカイ紹介']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['キョウシンカイコウケン']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['値引率']) . "\r\n";
        //--9(2.2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['基準値引率']) . "\r\n";
        //--9(2.2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['公正証書']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['JAF']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['KB']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['預託区分']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['ﾘｻｲｸﾙ預託金']) . "\r\n";
        //--S9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['ﾘｻｲｸﾙ資金管理費']) . "\r\n";
        //--S9(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['売上台数']) . "\r\n";
        //--S9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['登録台数']) . "\r\n";
        //--S9(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者郵便番号']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者キー名寄せ']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者キー地区コード']) . "\r\n";
        //--X(13)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者キーTEL']) . "\r\n";
        //--X(12)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所１']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所２']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所３']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者名称1漢字']) . "\r\n";
        //--X(40)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者名称2漢字']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所カナ']) . "\r\n";
        //--X(20)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者名称カナ']) . "\r\n";
        //--X(40)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人郵便番号']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人キー名寄せ']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人キー地区コード']) . "\r\n";
        //--X(13)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人キーTEL']) . "\r\n";
        //--X(12)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所１']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所２']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所３']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人名称1漢字']) . "\r\n";
        //--X(40)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人名称2漢字']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所カナ']) . "\r\n";
        //--X(20)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人名称カナ']) . "\r\n";
        //--X(40)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['名義人区分']) . "\r\n";
        //--X(40)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['名義人誕生日']) . "\r\n";
        //--X(40)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['注文書NO2']) . "\r\n";
        //--X(40)

        $strSQL .= ",Null" . "\r\n";
        //--S9(9)
        $strSQL .= ",TO_DATE('" . $orderinfo['OrderInfo6']['更新日'] . "','YYYY/MM/DD HH24:MI:SS')" . "\r\n";
        //--S9(9)
        $strSQL .= ",SYSDATE" . "\r\n";
        //--S9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3基準NEW']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3契約NEW']) . "\r\n";
        //--(9)
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",'@UPDUSER'" . "\r\n";
        $strSQL .= ",'@UPDAPP'" . "\r\n";
        $strSQL .= ",'@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End
        //2007/07/06 INS Start   パックDE753・パックDEメンテ
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['パックDE753']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['パックDEメンテ']) . "\r\n";
        //--(9)
        //2007/07/06 INS End
        //2009/12/21 INS Start
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者カテゴリーランク']) . "\r\n";
        //契約者カテゴリーランク
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人カテゴリーランク']) . "\r\n";
        //名義人カテゴリーランク
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.UC件数FLG) & vbCrLf)    'UC件数FLG
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.未実績FLG) & vbCrLf)    '未実績フラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.登録実績FLG) & vbCrLf)  '登録実績フラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.他契自登FLG) & vbCrLf)  '他契自登フラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.自契他登FLG) & vbCrLf)  '自契他登フラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.メーカーFLG) & vbCrLf)  'メーカーフラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.福祉FLG) & vbCrLf)      '福祉フラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.社名FLG) & vbCrLf)      '社名フラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.売上実績FLG) & vbCrLf)  '売上実績フラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.ﾘｰｽFLG) & vbCrLf)  'ﾘｰｽFLG
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.サービスカーFLG) & vbCrLf)  'サービスカーフラグ
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.再売FLG) & vbCrLf)      '再売FLG
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.カルテFLG) & vbCrLf)    'カルテFLG
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.売上_登録区分_FLG) & vbCrLf)    '売上_登録区分_FLG
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.登録_登録区分_FLG) & vbCrLf)    '登録_登録区分_FLG
        // '''strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo6.その他_登録区分_FLG) & vbCrLf)    'その他_登録区分_FLG"
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['UC件数']) . "\r\n";
        //UC件数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['未実績台数']) . "\r\n";
        //未実績台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['登録実績台数']) . "\r\n";
        //登録実績台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['他契自登台数']) . "\r\n";
        //他契自登台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['自契他登台数']) . "\r\n";
        //自契他登台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['メーカー台数']) . "\r\n";
        //メーカー台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['福祉台数']) . "\r\n";
        //福祉台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['社名台数']) . "\r\n";
        //社名台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['売上実績台数']) . "\r\n";
        //売上実績台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['ﾘｰス台数']) . "\r\n";
        //ﾘｰｽ台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['サービスカー台数']) . "\r\n";
        //サービスカー台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['再売台数']) . "\r\n";
        //再売台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['カルテ台数']) . "\r\n";
        //カルテ台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['売上_登録区分_台数']) . "\r\n";
        //売上_登録区分_台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['登録_登録区分_台数']) . "\r\n";
        //登録_登録区分_台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['その他_登録区分_台数']) . "\r\n";
        //その他_登録区分_台数
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['解約台数']) . "\r\n";
        //解約台数

        //2009/12/21 INS End
        $strSQL .= ")" . "\r\n";
        //--S9(9)

        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //$this->log($strSQL);

        //2006/12/08 UPD End
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：下取データ削除処理
    //関 数 名：fncSCSITDeleteSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：下取データの削除処理を行う
    //**********************************************************************
    function fncSCSITDeleteSQL($strCMNNO)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        $strSQL .= "FROM   HSCSIT" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：下取データ登録(SQL)
    //関 数 名：fncSCSITInsertSQL
    //引    数：strCMNNO:注文書№
    //          INDEX   :下取SEQNO
    //戻 り 値：SQL文
    //処理説明：下取データ登録(SQL)
    //**********************************************************************
    //2006/12/11 UPD 引数追加
    function fncSCSITInsertSQL($orderinfo, $INDEX, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= " INSERT INTO HSCSIT" . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "        KEIJYO_YM" . "\r\n";
        $strSQL .= "       ,CMN_NO" . "\r\n";
        $strSQL .= "       ,TRA_CARSEQ_NO" . "\r\n";
        $strSQL .= "       ,OYA_CMN_NO" . "\r\n";
        $strSQL .= "       ,URI_CMN_NO" . "\r\n";
        $strSQL .= "       ,SIT_SW" . "\r\n";
        $strSQL .= "       ,KAI_RIYU" . "\r\n";
        $strSQL .= "       ,GEN_SIK" . "\r\n";
        $strSQL .= "       ,MEIGARA" . "\r\n";
        $strSQL .= "       ,SEIREKI_NEN" . "\r\n";
        $strSQL .= "       ,SYAKEN_KAT" . "\r\n";
        $strSQL .= "       ,CARNO" . "\r\n";
        $strSQL .= "       ,SYAMEI" . "\r\n";
        $strSQL .= "       ,KATASIKI" . "\r\n";
        $strSQL .= "       ,RUIIBETU" . "\r\n";
        $strSQL .= "       ,TOU_Y_DT" . "\r\n";
        $strSQL .= "       ,RIKUJI" . "\r\n";
        $strSQL .= "       ,TOU_NO1" . "\r\n";
        $strSQL .= "       ,TOU_NO2" . "\r\n";
        $strSQL .= "       ,TOU_NO3" . "\r\n";
        $strSQL .= "       ,H59" . "\r\n";
        $strSQL .= "       ,SIT_KIN" . "\r\n";
        $strSQL .= "       ,SAT_KIN" . "\r\n";
        $strSQL .= "       ,JITU_SAT_KIN" . "\r\n";
        $strSQL .= "       ,SHZ_RT" . "\r\n";
        $strSQL .= "       ,SHZ_GK" . "\r\n";
        $strSQL .= "       ,YOTAK_GK" . "\r\n";
        $strSQL .= "       ,SHIKIN_KNR_RYOKIN" . "\r\n";
        $strSQL .= "       ,YOTAK_KB" . "\r\n";
        $strSQL .= "       ,TEBANASHI_KB" . "\r\n";
        $strSQL .= "       ,UPD_DATE" . "\r\n";
        $strSQL .= "       ,CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= "       ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "       ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "       ,UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "      ) VALUES (" . "\r\n";
        $strSQL .= $this->ClsComFnc->FncSqlNv(substr($orderinfo['OrderInfo1']['経理日'], 0, 6)) . "\r\n";
        switch ($INDEX) {
            case 1:
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
                //--X(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($INDEX) . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目親車注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目売上注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目下取SW']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目買下理由']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目現地仕切']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目銘柄']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目西暦年制']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目車検証型式']) . "\r\n";
                //--X(15)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目CARNO']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目車名']) . "\r\n";
                //--X(12)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目型式指定']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目類別区分']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録年月日']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目陸事名称']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録NO1']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録NO2']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録NO3']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目H59']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目下取価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目実査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目消費税率']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目消費税額']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ預託金']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ資金管理料']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目預託区分']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目手放区分']) . "\r\n";
                //--X(1)
                break;
            case 2:
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
                //--X(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($INDEX) . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目親車注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目売上注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目下取SW']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目買下理由']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目現地仕切']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目銘柄']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目西暦年制']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目車検証型式']) . "\r\n";
                //--X(15)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目CARNO']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目車名']) . "\r\n";
                //--X(12)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目型式指定']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目類別区分']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録年月日']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目陸事名称']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録NO1']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録NO2']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録NO3']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目H59']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目下取価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目実査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目消費税率']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目消費税額']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ預託金']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ資金管理料']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目預託区分']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目手放区分']) . "\r\n";
                //--X(1)
                break;
            case 3:
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
                //--X(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($INDEX) . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目親車注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目売上注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目下取SW']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目買下理由']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目現地仕切']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目銘柄']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目西暦年制']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目車検証型式']) . "\r\n";
                //--X(15)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目CARNO']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目車名']) . "\r\n";
                //--X(12)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目型式指定']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目類別区分']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録年月日']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目陸事名称']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録NO1']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録NO2']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録NO3']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目H59']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目下取価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目実査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目消費税率']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目消費税額']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ預託金']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ資金管理料']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目預託区分']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目手放区分']) . "\r\n";
                //--X(1)
                break;
        }
        $strSQL .= ",SYSDATE" . "\r\n";
        //--S9(9)
        $strSQL .= ",SYSDATE" . "\r\n";
        //--S9(9)
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",'@UPDUSER'" . "\r\n";
        $strSQL .= ",'@UPDAPP'" . "\r\n";
        $strSQL .= ",'@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= ")" . "\r\n";
        //--S9(9)

        //TODO 2009/09/10 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2009/09/10 UPD End
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：条変前新中売上データ存在チェック
    //関 数 名：fncEXISTSJYOHENSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：新中売上データの存在チェック
    //**********************************************************************
    function fncEXISTSJYOHENSQL($strCMNNO, $strDate)
    {
        $strSQL = "";
        $strSQL .= "SELECT CMN_NO,KEIJYO_YM" . "\r\n";
        $strSQL .= "FROM   HSCURI" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "  AND  KEIJYO_YM = '@KEIJYOYM'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        $strSQL = str_replace("@KEIJYOYM", $strDate, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：新中売上データ削除処理
    //関 数 名：fncJYOHENDeleteSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL
    //処理説明：新中売上データの削除処理を行う
    //**********************************************************************
    function fncJYOHENDeleteSQL($strCMNNO, $strKEIJYOYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE " . "\r\n";
        $strSQL .= "FROM   HJYOUHEN" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";
        $strSQL .= "  AND  KEIJYO_YM = '@KEIJYOYM'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $strCMNNO, $strSQL);
        $strSQL = str_replace("@KEIJYOYM", $strKEIJYOYM, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：条変売上データ登録(SQL)
    //関 数 名：fncJYOHENCNVInsertSQL
    //引    数：strCMNNO:注文書№
    //戻 り 値：SQL文
    //処理説明：新中売上データ登録(SQL)
    //**********************************************************************
    function fncJYOHENCNVInsertSQL($orderinfo, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL .= " INSERT INTO HJYOUHEN" . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "       KEIJYO_YM" . "\r\n";
        $strSQL .= "      ,NAU_KB" . "\r\n";
        $strSQL .= "      ,CMN_NO" . "\r\n";
        $strSQL .= "      ,JKN_HKO_RIRNO" . "\r\n";
        $strSQL .= "      ,DATA_KB" . "\r\n";
        $strSQL .= "      ,UC_NO" . "\r\n";
        $strSQL .= "      ,URI_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,URI_TANNO" . "\r\n";
        $strSQL .= "      ,URI_GYOSYA" . "\r\n";
        $strSQL .= "      ,SAV_KTNCD" . "\r\n";
        $strSQL .= "      ,URK_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,KYK_HNS" . "\r\n";
        $strSQL .= "      ,TOU_HNS" . "\r\n";
        $strSQL .= "      ,NTI_USR_CD" . "\r\n";
        $strSQL .= "      ,TOU_DATE" . "\r\n";
        $strSQL .= "      ,URG_DATE" . "\r\n";
        $strSQL .= "      ,KRI_DATE" . "\r\n";
        $strSQL .= "      ,CEL_DATE" . "\r\n";
        $strSQL .= "      ,SYADAI" . "\r\n";
        $strSQL .= "      ,CARNO" . "\r\n";
        $strSQL .= "      ,MAKER_CD" . "\r\n";
        $strSQL .= "      ,NENSIKI" . "\r\n";
        $strSQL .= "      ,SITEI_NO" . "\r\n";
        $strSQL .= "      ,RUIBETU_NO" . "\r\n";
        $strSQL .= "      ,SS_CD" . "\r\n";
        $strSQL .= "      ,TOA_NAME" . "\r\n";
        $strSQL .= "      ,HBSS_CD" . "\r\n";
        $strSQL .= "      ,KASOUNO" . "\r\n";
        $strSQL .= "      ,YOHIN_A" . "\r\n";
        $strSQL .= "      ,YOHIN_C" . "\r\n";
        $strSQL .= "      ,YOHIN_H" . "\r\n";
        $strSQL .= "      ,YOHIN_S" . "\r\n";
        $strSQL .= "      ,RIKUJI_CD" . "\r\n";
        $strSQL .= "      ,TOURK_NO1" . "\r\n";
        $strSQL .= "      ,TOURK_NO2" . "\r\n";
        $strSQL .= "      ,TOURK_NO3" . "\r\n";
        $strSQL .= "      ,H59" . "\r\n";
        $strSQL .= "      ,SYAKEN_EXP_DT" . "\r\n";
        $strSQL .= "      ,KKR_CD" . "\r\n";
        $strSQL .= "      ,NINKATA_CD" . "\r\n";
        $strSQL .= "      ,SYANAI_KOSYO" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_SYD_YM" . "\r\n";
        $strSQL .= "      ,CHUKOSYA_NYK_YM" . "\r\n";
        $strSQL .= "      ,CHUMON_KB" . "\r\n";
        $strSQL .= "      ,KASOU_KB" . "\r\n";
        $strSQL .= "      ,AIM_FLG" . "\r\n";
        $strSQL .= "      ,AIM_KBN" . "\r\n";
        $strSQL .= "      ,KYK_KB" . "\r\n";
        $strSQL .= "      ,ZERO_KB" . "\r\n";
        $strSQL .= "      ,SYOYUKEN_KB" . "\r\n";
        $strSQL .= "      ,ITEN_KB" . "\r\n";
        $strSQL .= "      ,YOUTO_KB" . "\r\n";
        $strSQL .= "      ,KANRI_KB" . "\r\n";
        $strSQL .= "      ,HNBCHK_KB" . "\r\n";
        $strSQL .= "      ,KGO_KB" . "\r\n";
        $strSQL .= "      ,BUY_SHP" . "\r\n";
        $strSQL .= "      ,MZD_SIY" . "\r\n";
        $strSQL .= "      ,LEASE_KB" . "\r\n";
        $strSQL .= "      ,LEASE_KB2" . "\r\n";
        $strSQL .= "      ,LES_SHP1" . "\r\n";
        $strSQL .= "      ,LES_SHP2" . "\r\n";
        $strSQL .= "      ,KAP_KB" . "\r\n";
        $strSQL .= "      ,HNB_KB" . "\r\n";
        $strSQL .= "      ,OPT_HOK_KNY_KB" . "\r\n";
        $strSQL .= "      ,TRK_KB" . "\r\n";
        $strSQL .= "      ,ZAIKO_KB" . "\r\n";
        $strSQL .= "      ,KSO_KENSA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NAIYO" . "\r\n";
        $strSQL .= "      ,KSO_KB" . "\r\n";
        $strSQL .= "      ,KAZEI_KB" . "\r\n";
        $strSQL .= "      ,DAINO_FLG" . "\r\n";
        $strSQL .= "      ,JIBAI_FLG" . "\r\n";
        $strSQL .= "      ,SIH_SIT_KB" . "\r\n";
        $strSQL .= "      ,PAY_OFF_FLG" . "\r\n";
        $strSQL .= "      ,SWK_SUM_FLG" . "\r\n";
        $strSQL .= "      ,CKG_KB" . "\r\n";
        $strSQL .= "      ,CKO_HNB_KB" . "\r\n";
        $strSQL .= "      ,CKO_SS_KB" . "\r\n";
        $strSQL .= "      ,CKO_SIR_KB" . "\r\n";
        $strSQL .= "      ,CKO_SEB_KB" . "\r\n";
        $strSQL .= "      ,CKO_MEG_KB" . "\r\n";
        $strSQL .= "      ,CKO_MHN_KB" . "\r\n";
        $strSQL .= "      ,CKO_UCNO" . "\r\n";
        $strSQL .= "      ,CKO_CAR_SER_NO" . "\r\n";
        $strSQL .= "      ,JKN_HKD_AK" . "\r\n";
        $strSQL .= "      ,JKN_HK_NAIYO" . "\r\n";
        $strSQL .= "      ,JKN_HKD" . "\r\n";
        $strSQL .= "      ,JKN_NO" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO1" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO2" . "\r\n";
        $strSQL .= "      ,SIT_SEIRINO3" . "\r\n";
        $strSQL .= "      ,GYO_NAME" . "\r\n";
        $strSQL .= "      ,MEG_NAME" . "\r\n";
        $strSQL .= "      ,UC_OYA2" . "\r\n";
        $strSQL .= "      ,TGT_SIT" . "\r\n";
        $strSQL .= "      ,SRY_PRC" . "\r\n";
        $strSQL .= "      ,SRY_NBK" . "\r\n";
        $strSQL .= "      ,SRY_CMN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_KTN_PCS" . "\r\n";
        $strSQL .= "      ,SRY_BUY_PCS" . "\r\n";
        $strSQL .= "      ,SRY_SHZ_RT" . "\r\n";
        $strSQL .= "      ,SRY_SHZ" . "\r\n";
        $strSQL .= "      ,FHZ_TEIKA" . "\r\n";
        $strSQL .= "      ,FHZ_NBK" . "\r\n";
        $strSQL .= "      ,FHZ_KYK" . "\r\n";
        $strSQL .= "      ,FHZ_PCS" . "\r\n";
        $strSQL .= "      ,FHZ_SHZ" . "\r\n";
        $strSQL .= "      ,TKB_KSH_TEIKA" . "\r\n";
        $strSQL .= "      ,TKB_KSH_NBK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_KYK" . "\r\n";
        $strSQL .= "      ,TKB_KSH_PCS" . "\r\n";
        $strSQL .= "      ,TKB_KSH_SHZ" . "\r\n";
        $strSQL .= "      ,KAP_TES_KYK" . "\r\n";
        $strSQL .= "      ,KAP_TES_KJN" . "\r\n";
        $strSQL .= "      ,KAP_TES_RT" . "\r\n";
        $strSQL .= "      ,KAP_TES_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KYK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KJN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SHZ" . "\r\n";
        $strSQL .= "      ,HOUTEIH_GK" . "\r\n";
        $strSQL .= "      ,HKN_GK" . "\r\n";
        $strSQL .= "      ,TRA_CAR_ZSI_SUM" . "\r\n";
        $strSQL .= "      ,SHR_GK_SUM" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_SIT_SHZ" . "\r\n";
        $strSQL .= "      ,SHR_JKN_ATM_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TRK_SYH" . "\r\n";
        $strSQL .= "      ,SHR_JKN_CKO_FTK" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_TGT_KIN" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KAI" . "\r\n";
        $strSQL .= "      ,SHR_JKN_KRJ_KIN" . "\r\n";
        $strSQL .= "      ,KUREJITGAISYA" . "\r\n";
        $strSQL .= "      ,KUREJIT_NO" . "\r\n";
        $strSQL .= "      ,TRA_CAR_RCYYTK_SUM" . "\r\n";
        $strSQL .= "      ,KAP_GKN" . "\r\n";
        $strSQL .= "      ,TRA_CAR_PRC_SUM" . "\r\n";
        $strSQL .= "      ,TRA_CAR_STI_SUM" . "\r\n";
        $strSQL .= "      ,JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= "      ,SYARYOU_ZEI" . "\r\n";
        $strSQL .= "      ,EAKON_ZEI" . "\r\n";
        $strSQL .= "      ,SUTEREO_ZEI" . "\r\n";
        $strSQL .= "      ,JYURYO_ZEI" . "\r\n";
        $strSQL .= "      ,SHZ_KEI" . "\r\n";
        $strSQL .= "      ,JIBAI_SITEI" . "\r\n";
        $strSQL .= "      ,JIBAI_KAISYA" . "\r\n";
        $strSQL .= "      ,JIBAI_CAR_KND" . "\r\n";
        $strSQL .= "      ,JIBAI_ICOL_CD" . "\r\n";
        $strSQL .= "      ,JIBAI_TUKI_SU" . "\r\n";
        $strSQL .= "      ,JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= "      ,OPTHOK_RYO" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_KZI_KBN" . "\r\n";
        $strSQL .= "      ,HNB_TES_RYO_SHR_CD" . "\r\n";
        $strSQL .= "      ,HNB_TES_GKU" . "\r\n";
        $strSQL .= "      ,HNB_SHZ" . "\r\n";
        $strSQL .= "      ,TOU_SYH_KEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKEN" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,TOU_SYH_NOUSYA" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SIT_TTK" . "\r\n";
        $strSQL .= "      ,TOU_SYH_SATEI" . "\r\n";
        $strSQL .= "      ,TOU_SYH_JIKOU" . "\r\n";
        $strSQL .= "      ,TOU_SYH_ETC" . "\r\n";
        $strSQL .= "      ,HOUTEIH_KEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKEN" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SYAKO_SYO" . "\r\n";
        $strSQL .= "      ,HOUTEIH_SIT" . "\r\n";
        $strSQL .= "      ,HONBU_FTK" . "\r\n";
        $strSQL .= "      ,UKM_SNY_TES" . "\r\n";
        $strSQL .= "      ,UKM_SINSEI_SYR" . "\r\n";
        $strSQL .= "      ,KAP_TES_SGK" . "\r\n";
        $strSQL .= "      ,ETC_SKI_RYO" . "\r\n";
        $strSQL .= "      ,SRY_GENKAI_RIE" . "\r\n";
        $strSQL .= "      ,PENALTY" . "\r\n";
        $strSQL .= "      ,EGO_GAI_SYUEKI" . "\r\n";
        $strSQL .= "      ,SAI_SONEKI" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_RUI_MGN" . "\r\n";
        $strSQL .= "      ,TOK_KEI_KHN_SYR" . "\r\n";
        $strSQL .= "      ,TOK_KEI_TOK_KIN" . "\r\n";
        $strSQL .= "      ,GNK_HJN_PCS" . "\r\n";
        $strSQL .= "      ,GNK_SIT_URI_SKI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SIT_KIN" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI" . "\r\n";
        $strSQL .= "      ,CKO_SAI_MITUMORI" . "\r\n";
        $strSQL .= "      ,CKO_SYOGAKARI" . "\r\n";
        $strSQL .= "      ,CKO_BAI_SATEI_SOKAI" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JDO_SHZ" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_KIN" . "\r\n";
        $strSQL .= "      ,CKO_MIKEI_JIBAI_SHZ" . "\r\n";
        $strSQL .= "      ,NYK_KB" . "\r\n";
        $strSQL .= "      ,DM_KB" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KYK" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_SKI" . "\r\n";
        $strSQL .= "      ,KYOUSINKAI_KOKEN" . "\r\n";
        $strSQL .= "      ,NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KIJUN_NEBIKI_RT" . "\r\n";
        $strSQL .= "      ,KOUSEI_SYOSYO" . "\r\n";
        $strSQL .= "      ,JAF" . "\r\n";
        $strSQL .= "      ,KICK_BACK" . "\r\n";
        $strSQL .= "      ,YOTAK_KB" . "\r\n";
        $strSQL .= "      ,RCY_YOT_KIN" . "\r\n";
        $strSQL .= "      ,RCY_SKN_KAN_HI" . "\r\n";
        $strSQL .= "      ,URI_DAISU" . "\r\n";
        $strSQL .= "      ,TOU_DAISU" . "\r\n";
        $strSQL .= "      ,KYK_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,KYK_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,KYK_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,KYK_KEY_TEL" . "\r\n";
        $strSQL .= "      ,KYK_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,KYK_ADR_MEI" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,KYK_ADR_KN" . "\r\n";
        $strSQL .= "      ,KYK_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_YUBIN_NO" . "\r\n";
        $strSQL .= "      ,MGN_KEY_MEI_YOSE" . "\r\n";
        $strSQL .= "      ,MGN_KEY_CKU_CD" . "\r\n";
        $strSQL .= "      ,MGN_KEY_TEL" . "\r\n";
        $strSQL .= "      ,MGN_ADR_NOKI_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_TUSYO_KNJ" . "\r\n";
        $strSQL .= "      ,MGN_ADR_MEI" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KNJ2" . "\r\n";
        $strSQL .= "      ,MGN_ADR_KN" . "\r\n";
        $strSQL .= "      ,MGN_MEI_KN" . "\r\n";
        $strSQL .= "      ,MGN_SEX" . "\r\n";
        $strSQL .= "      ,MGN_BRTDT" . "\r\n";
        $strSQL .= "      ,OLD_CMN_NO" . "\r\n";
        $strSQL .= "      ,DEL_DATE" . "\r\n";
        $strSQL .= "      ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "      ,UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "      ) VALUES (" . "\r\n";

        $strSQL .= $this->ClsComFnc->FncSqlNv(substr($orderinfo['OrderInfo1']['経理日'], 0, 6)) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['新中区分']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
        $strSQL .= "      ,(SELECT NVL(MAX(JKN_HKO_RIRNO),0)+1 FROM HJYOUHEN WHERE CMN_NO = '@CMNNO')" . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv((substr($orderinfo['OrderInfo6']['データ区分'], 0, 1) == "1") ? "1X" : "2X") . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['UCNO']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上部署']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上セールス']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上業者']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['サービス']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売掛部署']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['契約店']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録店']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['認定特需ユーザーコード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['売上日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['経理日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['解約日']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['車台']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['CARNO']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['銘柄']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['年製']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['指定類別型式指定']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['指定類別区分']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['車種コード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['問合呼称']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['桁８コード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['新車架装整理NO']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品A']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品C']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品H']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['用品S']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['陸事']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録NO1']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録NO2']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['登録NO3']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['H59']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['車検年']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['くくりコード']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['認可型式']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['社内呼称']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['中古車初度年月']) . "\r\n";
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['中古車入荷年月']) . "\r\n";

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分01']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分02']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分03']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分04']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分05']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分06']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分07']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分08']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分09']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分10']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分11']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分12']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分13']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分14']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分15']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分16']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分17']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分18']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分19']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分20']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分21']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分22']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分23']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分24']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分25']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分26']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分27']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分28']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分29']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分30']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分31']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分32']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分33']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分34']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分35']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分36']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分37']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分38']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['区分39']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['中古車売上親UCNO']) . "\r\n";
        //--(10)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['中古車売車整理NO']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更赤黒']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更内容']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更年月日']) . "\r\n";
        //--(8)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['条件変更NO']) . "\r\n";
        //--(7)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['下取車整理NO1']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['下取車整理NO2']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['下取車整理NO3']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['業者名']) . "\r\n";
        //--(20)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.契約者名称カナ) & vbCrLf)              '--(27)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人区分) & vbCrLf)                  '--(1)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人誕生日) & vbCrLf)                '--(8)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人TEL) & vbCrLf)                   '--(12)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人地区CD) & vbCrLf)                '--(13)
        //        strSQL.Append("," & clsComFnc.FncSqlNv(orderinfo.OrderInfo2.名義人軒番カナ) & vbCrLf)              '--(20)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo3']['名義人名称']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo3']['親2桁コード']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['手形据置日数']) . "\r\n";
        //--(3)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両値引']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両注文書原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両拠点原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両新車車両部署別用原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両消費税率']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['車両消費税額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品定価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品値引']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['添付品消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3定価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3値引']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様6原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['特別仕様3消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料基準']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料消費税率']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['割賦手数料消費税額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3契約']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3基準']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['登録諸費用3消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['預り法廷費用']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['税金保険料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['残債']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払金合計']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件下取価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件下取車消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件頭金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件登録諸費用']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件中古車負担金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件手形回数']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件手形金額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ回数']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ金額']) . "\r\n";
        //--(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ会社']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ承認NO']) . "\r\n";
        //--(20)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['割賦元金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['下取者買取価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['下取者査定価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金自動車税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金車両取得税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金ｴｱｺﾝ取得税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金ｽﾃﾚｵ取得税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金重量税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['税金消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責指定']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責会社']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責自動車種類']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['自賠責色コード']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['自賠責月数']) . "\r\n";
        //--(2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['自賠責保険料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['任意保険料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['販売手数料課税非課税']) . "\r\n";
        //--(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo4']['販売手数料支払先コード']) . "\r\n";
        //--(5)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['販売手数料額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['販売消費税']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3検査']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3持込車検']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3車庫証明']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3納車費用']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3下取諸手続']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3査定料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3字光式']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['登録諸費用3その他']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用検査']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用持込車検']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用車庫証明']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['預り法定費用下取']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['本部負担金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['打込金収入手数料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['打込金申請奨励金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['割賦手数料差額']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['その他紹介料']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['車両F号限界利益']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['ﾍﾟﾅﾙﾃｨ']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['営業外収益']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['最終損益']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約基本ﾏｰｼﾞﾝ']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約累進ﾏｰｼﾞﾝ']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約拡販奨励金']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['特約店契約特別価格']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['原価標準原価']) . "\r\n";
        //--(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo4']['原価下取車売上仕切']) . "\r\n";
        //--(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車売車下取価格']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車売車査定']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車再生見積']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車諸掛']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車売車査定ソカイ']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自動車税金額']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自動車税消費税']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自賠責金額']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['中古車未経過自賠責消費税']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['入庫約束']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['DM送付']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['キョウシンカイ顧客']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['キョウシンカイ紹介']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['キョウシンカイコウケン']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['値引率']) . "\r\n";
        //--9(2.2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['基準値引率']) . "\r\n";
        //--9(2.2)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['公正証書']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['JAF']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['KB']) . "\r\n";
        //--9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['預託区分']) . "\r\n";
        //--X(1)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['ﾘｻｲｸﾙ預託金']) . "\r\n";
        //--S9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo5']['ﾘｻｲｸﾙ資金管理費']) . "\r\n";
        //--S9(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['売上台数']) . "\r\n";
        //--S9(9)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfo6']['登録台数']) . "\r\n";
        //--S9(9)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者郵便番号']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者キー名寄せ']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者キー地区コード']) . "\r\n";
        //--X(13)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者キーTEL']) . "\r\n";
        //--X(12)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所１']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所２']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所３']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者名称1漢字']) . "\r\n";
        //--X(40)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者名称2漢字']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者住所カナ']) . "\r\n";
        //--X(20)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoD']['契約者名称カナ']) . "\r\n";
        //--X(40)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人郵便番号']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人キー名寄せ']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人キー地区コード']) . "\r\n";
        //--X(13)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人キーTEL']) . "\r\n";
        //--X(12)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所１']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所２']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所３']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人名称1漢字']) . "\r\n";
        //--X(40)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人名称2漢字']) . "\r\n";
        //--X(30)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人住所カナ']) . "\r\n";
        //--X(20)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoE']['名義人名称カナ']) . "\r\n";
        //--X(40)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['名義人区分']) . "\r\n";
        //--X(40)
        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo2']['名義人誕生日']) . "\r\n";
        //--X(40)

        $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo1']['注文書NO2']) . "\r\n";
        //--X(40)

        $strSQL .= ",Null" . "\r\n";
        //--S9(9)
        $strSQL .= ",SYSDATE" . "\r\n";
        //--S9(9)
        $strSQL .= ",SYSDATE" . "\r\n";
        //--S9(9)
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",'@UPDUSER'" . "\r\n";
        $strSQL .= ",'@UPDAPP'" . "\r\n";
        $strSQL .= ",'@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= ")" . "\r\n";
        //--S9(9)

        $strSQL = str_replace("@CMNNO", $orderinfo['OrderInfo6']['注文書NO'], $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End
    }

    //**********************************************************************
    //処 理 名：下取データ登録(SQL)
    //関 数 名：fncSCURIInsert
    //引    数：strCMNNO:注文書№
    //          INDEX   :下取SEQNO
    //戻 り 値：SQL文
    //処理説明：下取データ登録(SQL)
    //**********************************************************************
    function fncJYOUHENSITCNVInsertSQL($orderinfo, $INDEX, $strUpdPro)
    {
        $strUpdUser = $this->GS_LOGINUSER['strUserID'];
        $strUpdClt = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= " INSERT INTO HJYOUHENSIT" . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "        KEIJYO_YM" . "\r\n";
        $strSQL .= "       ,CMN_NO" . "\r\n";
        $strSQL .= "       ,JKN_HKO_RIRNO" . "\r\n";
        $strSQL .= "       ,TRA_CARSEQ_NO" . "\r\n";
        $strSQL .= "       ,OYA_CMN_NO" . "\r\n";
        $strSQL .= "       ,URI_CMN_NO" . "\r\n";
        $strSQL .= "       ,SIT_SW" . "\r\n";
        $strSQL .= "       ,KAI_RIYU" . "\r\n";
        $strSQL .= "       ,GEN_SIK" . "\r\n";
        $strSQL .= "       ,MEIGARA" . "\r\n";
        $strSQL .= "       ,SEIREKI_NEN" . "\r\n";
        $strSQL .= "       ,SYAKEN_KAT" . "\r\n";
        $strSQL .= "       ,CARNO" . "\r\n";
        $strSQL .= "       ,SYAMEI" . "\r\n";
        $strSQL .= "       ,KATASIKI" . "\r\n";
        $strSQL .= "       ,RUIIBETU" . "\r\n";
        $strSQL .= "       ,TOU_Y_DT" . "\r\n";
        $strSQL .= "       ,RIKUJI" . "\r\n";
        $strSQL .= "       ,TOU_NO1" . "\r\n";
        $strSQL .= "       ,TOU_NO2" . "\r\n";
        $strSQL .= "       ,TOU_NO3" . "\r\n";
        $strSQL .= "       ,H59" . "\r\n";
        $strSQL .= "       ,SIT_KIN" . "\r\n";
        $strSQL .= "       ,SAT_KIN" . "\r\n";
        $strSQL .= "       ,JITU_SAT_KIN" . "\r\n";
        $strSQL .= "       ,SHZ_RT" . "\r\n";
        $strSQL .= "       ,SHZ_GK" . "\r\n";
        $strSQL .= "       ,YOTAK_GK" . "\r\n";
        $strSQL .= "       ,SHIKIN_KNR_RYOKIN" . "\r\n";
        $strSQL .= "       ,YOTAK_KB" . "\r\n";
        $strSQL .= "       ,TEBANASHI_KB" . "\r\n";
        $strSQL .= "       ,UPD_DATE" . "\r\n";
        $strSQL .= "       ,CREATE_DATE" . "\r\n";
        //TODO 2006/12/08 UPD Start
        $strSQL .= "       ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "       ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "       ,UPD_CLT_NM" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= "      ) VALUES (" . "\r\n";
        $strSQL .= $this->ClsComFnc->FncSqlNv(substr($orderinfo['OrderInfo1']['経理日'], 0, 6)) . "\r\n";

        switch ($INDEX) {
            case 1:
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
                //--X(9)
                $strSQL .= "      ,(SELECT NVL(MAX(JKN_HKO_RIRNO),0) FROM HJYOUHEN WHERE CMN_NO = '@CMNNO')" . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($INDEX) . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目親車注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目売上注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目下取SW']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目買下理由']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目現地仕切']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目銘柄']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目西暦年制']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目車検証型式']) . "\r\n";
                //--X(15)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目CARNO']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目車名']) . "\r\n";
                //--X(12)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目型式指定']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目類別区分']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録年月日']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目陸事名称']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録NO1']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録NO2']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目登録NO3']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目H59']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目下取価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目実査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目消費税率']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目消費税額']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ預託金']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ資金管理料']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目預託区分']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoA']['下取車１台目手放区分']) . "\r\n";
                //--X(1)
                break;
            case 2:
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
                //--X(9)
                $strSQL .= "      ,(SELECT NVL(MAX(JKN_HKO_RIRNO),0) FROM HJYOUHEN WHERE CMN_NO = '@CMNNO')" . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($INDEX) . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目親車注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目売上注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目下取SW']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目買下理由']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目現地仕切']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目銘柄']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目西暦年制']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目車検証型式']) . "\r\n";
                //--X(15)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目CARNO']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目車名']) . "\r\n";
                //--X(12)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目型式指定']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目類別区分']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録年月日']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目陸事名称']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録NO1']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録NO2']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目登録NO3']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目H59']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目下取価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目実査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目消費税率']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目消費税額']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ預託金']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ資金管理料']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目預託区分']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoB']['下取車２台目手放区分']) . "\r\n";
                //--X(1)
                break;
            case 3:
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfo6']['注文書NO']) . "\r\n";
                //--X(9)
                $strSQL .= "      ,(SELECT NVL(MAX(JKN_HKO_RIRNO),0) FROM HJYOUHEN WHERE CMN_NO = '@CMNNO')" . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($INDEX) . "\r\n";
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目親車注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目売上注文書NO']) . "\r\n";
                //--X(7)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目下取SW']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目買下理由']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目現地仕切']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目銘柄']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目西暦年制']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目車検証型式']) . "\r\n";
                //--X(15)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目CARNO']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目車名']) . "\r\n";
                //--X(12)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目型式指定']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目類別区分']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録年月日']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目陸事名称']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録NO1']) . "\r\n";
                //--X(8)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録NO2']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目登録NO3']) . "\r\n";
                //--X(4)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目H59']) . "\r\n";
                //--X(3)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目下取価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目実査定価格']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目消費税率']) . "\r\n";
                //--X(2)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目消費税額']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ預託金']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNz($orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ資金管理料']) . "\r\n";
                //--S9(9)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目預託区分']) . "\r\n";
                //--X(1)
                $strSQL .= "," . $this->ClsComFnc->FncSqlNv($orderinfo['OrderInfoC']['下取車３台目手放区分']) . "\r\n";
                //--X(1)
                break;
        }
        $strSQL .= ",SYSDATE" . "\r\n";
        //--S9(9)
        $strSQL .= ",SYSDATE" . "\r\n";
        //--S9(9)
        //TODO 2006/12/08 UPD Start
        $strSQL .= ",'@UPDUSER'" . "\r\n";
        $strSQL .= ",'@UPDAPP'" . "\r\n";
        $strSQL .= ",'@UPDCLT'" . "\r\n";
        //2006/12/08 UPD End

        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@CMNNO", $orderinfo['OrderInfo6']['注文書NO'], $strSQL);
        //TODO 2006/12/08 UPD Start
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        //2006/12/08 UPD End

    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function fncChkUCNO($strFromDate, $strToDate, $strSCKbn)
    {
        return parent::Fill($this->fncChkUCNOSQL($strFromDate, $strToDate, $strSCKbn));
    }

    public function fncDeleteWK_CHMNO()
    {
        return parent::Do_Execute($this->fncDeleteWK_CHMNOSQL());
    }

    public function fncInsertWK_CHMNO_UCNO($strUCNO, $strFromDate, $strToDate, $strSCKbn)
    {
        return parent::Do_Execute($this->fncInsertWK_CHMNO_UCNOSQL($strUCNO, $strFromDate, $strToDate, $strSCKbn));
    }

    public function fncChuSelect()
    {
        return parent::Fill($this->fncChuSelectSql());
    }

    public function fncSitSelect($strCmnNo)
    {
        return parent::Fill($this->fncSitSelectSQL($strCmnNo));
    }

    public function fncGYOSYASelect($strGyosya_CD)
    {
        return parent::Fill($this->fncGYOSYASelectSQL($strGyosya_CD));
    }

    public function fncErrChkUri($CMN_NO, $UC_NO, $URIBUSYO, $URKBUSYO, $TANNO, $KRIDATE, $NAU_KB)
    {
        return parent::Fill($this->fncErrChkUriSQL($CMN_NO, $UC_NO, $URIBUSYO, $URKBUSYO, $TANNO, $KRIDATE, $NAU_KB));
    }

    public function fncEXISTSSCURI($strCMNNO)
    {
        return parent::Fill($this->fncEXISTSSCURISQL($strCMNNO));
    }

    public function fncEXISTSJYOUHEN($strCMNNO, $strUPD_Date)
    {
        return parent::Fill($this->fncEXISTSJYOUHENSQL($strCMNNO, $strUPD_Date));
    }

    public function fncJOHENInsert($strCMNNO, $strUpdPro)
    {
        //20240909 caina upd s
        // return parent::Fill($this->fncJOHENInsertSQL($strCMNNO, $orderinfo, $strUpdPro));
        return parent::insert($this->fncJOHENInsertSQL($strCMNNO, $strUpdPro));
        //20240909 caina upd e
    }

    public function fncGetMAXNO($strNO)
    {
        return parent::Fill($this->fncGetMAXNOSQL($strNO));
    }

    public function fncJOHENSITInsert($strCMNNO, $strUpdPro)
    {
        return parent::Do_Execute($this->fncJOHENSITInsertSQL($strCMNNO, $strUpdPro));
    }

    public function fncJyuhenDelete($strCMNNO, $strKeijyoYM)
    {
        return parent::Do_Execute($this->fncJyuhenDeleteSQL($strCMNNO, $strKeijyoYM));
    }

    public function fncJYOHENSITDelete($strCMNNO, $strKeijyoYM)
    {
        return parent::Do_Execute($this->fncJYOHENSITDeleteSQL($strCMNNO, $strKeijyoYM));
    }

    public function fncGetBEFGETDT($strTableId)
    {
        return parent::Fill($this->fncGetBEFGETDTSQL($strTableId));
    }

    public function fncSCURIDelete($strCMNNO)
    {
        return parent::Do_Execute($this->fncSCURIDeleteSQL($strCMNNO));
    }

    public function fncSCURIInsert($orderinfo, $strUpdPro)
    {
        return parent::Do_Execute($this->fncSCURIInsertSQL($orderinfo, $strUpdPro));
    }

    public function fncSCSITDelete($strCMNNO)
    {
        return parent::Do_Execute($this->fncSCSITDeleteSQL($strCMNNO));
    }

    public function fncSCSITInsert($orderinfo, $INDEX, $strUpdPro)
    {
        return parent::Do_Execute($this->fncSCSITInsertSQL($orderinfo, $INDEX, $strUpdPro));
    }

    public function fncEXISTSJYOHEN($strCMNNO, $strDate)
    {
        return parent::Fill($this->fncEXISTSJYOHENSQL($strCMNNO, $strDate));
    }

    public function fncJYOHENDelete($strCMNNO, $strKEIJYOYM)
    {
        return parent::Do_Execute($this->fncJYOHENDeleteSQL($strCMNNO, $strKEIJYOYM));
    }

    public function fncJYOHENCNVInsert($orderinfo, $strUpdPro)
    {
        return parent::Do_Execute($this->fncJYOHENCNVInsertSQL($orderinfo, $strUpdPro));
    }

    public function fncJYOUHENSITCNVInsert($orderinfo, $INDEX, $strUpdPro)
    {
        return parent::Do_Execute($this->fncJYOUHENSITCNVInsertSQL($orderinfo, $INDEX, $strUpdPro));
    }

}
