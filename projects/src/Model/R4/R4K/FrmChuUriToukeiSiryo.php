<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmChuUriToukeiSiryo extends ClsComDb
{
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    public function fncSelect()
    {
        return parent::select($this->selectsql());
    }

    public function fncPrintSelectSQL($cboYMStart)
    {
        $strSQL = "";
        $strSQL .= "SELECT V.MEISYOU_CD" . "\r\n";
        $strSQL .= ",      V.MEISYOU" . "\r\n";
        $strSQL .= ",      SUM(V.DAISU) DAISU" . "\r\n";
        $strSQL .= ",      SUM(V.KAIYAKU) KAIYAKU" . "\r\n";
        $strSQL .= ",      SUM(V.SYARYOU_PRC) SYARYOU_PRC" . "\r\n";
        $strSQL .= ",      SUM(V.SATEI) SATEI" . "\r\n";
        $strSQL .= ",      SUM(V.SAIMITUMORI) SAIMITUMORI" . "\r\n";
        $strSQL .= ",      SUM(V.KASOU_URI) KASOU_URI" . "\r\n";
        $strSQL .= ",      SUM(V.KASOU_PCS) KASOU_PCS" . "\r\n";
        $strSQL .= ",      SUM(V.SONOTA_URI) SONOTA_URI" . "\r\n";
        $strSQL .= ",      SUM(V.SONOTA) SONOTA" . "\r\n";
        $strSQL .= ",      SUM(V.SITASON) SITASON" . "\r\n";
        $strSQL .= ",      SUM(V.SYARYOU_PRC)" . "\r\n";
        $strSQL .= "     + SUM(V.KASOU_URI)" . "\r\n";
        $strSQL .= "     - SUM(V.SATEI)" . "\r\n";
        $strSQL .= "     - SUM(V.SAIMITUMORI)" . "\r\n";
        $strSQL .= "     - SUM(V.KASOU_PCS)" . "\r\n";
        $strSQL .= "     + SUM(V.SONOTA_URI)" . "\r\n";
        $strSQL .= "     - SUM(V.SONOTA)" . "\r\n";
        $strSQL .= "     - SUM(V.SITASON) RIEKI" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",      SUBSTR('@TOUGETU',1,4) || '/' || SUBSTR('@TOUGETU',5,2) TOUGETU" . "\r\n";
        $strSQL .= "FROM  " . "\r\n";
        $strSQL .= "      (SELECT " . "\r\n";
        $strSQL .= " 		    MEI.MEISYOU_CD" . "\r\n";
        $strSQL .= "		,      MEI.MEISYOU" . "\r\n";
        $strSQL .= "		,      COUNT(URI.UC_NO) DAISU" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN URI.CEL_DATE IS NULL THEN 0 ELSE 1 END) KAIYAKU" . "\r\n";
        $strSQL .= "		,      SUM(NVL(URI.SRY_PRC,0)) SYARYOU_PRC" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN URI.CKO_HNB_KB = '7' THEN 0  ELSE NVL(URI.CKO_BAI_SATEI,0) END) SATEI" . "\r\n";
        $strSQL .= "		,      SUM(NVL(URI.CKO_SAI_MITUMORI,0)) SAIMITUMORI" . "\r\n";
        $strSQL .= "		,      SUM(NVL(URI.TKB_KSH_KYK,0)) KASOU_URI" . "\r\n";
        $strSQL .= "		,      SUM(NVL(URI.TKB_KSH_PCS,0)) KASOU_PCS" . "\r\n";
        $strSQL .= "		,      SUM(NVL(URI.KAP_TES_KYK,0)) + SUM(NVL(URI.TOU_SYH_KYK,0)) + SUM(NVL(URI.HOUTEIH_GK,0)) SONOTA_URI" . "\r\n";

        $strSQL .= "		,      SUM(NVL(URI.KAP_TES_KJN,0)) + SUM(NVL(URI.TOU_SYH_KJN,0)) SONOTA" . "\r\n";

        $strSQL .= "		,      SUM(NVL(URI.SHR_JKN_SIT_KIN,0) - NVL(URI.TRA_CAR_PRC_SUM,0) - NVL(URI.TRA_CAR_STI_SUM,0)) SITASON" . "\r\n";
        $strSQL .= "		FROM   HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "		LEFT JOIN HSCURI_VW URI" . "\r\n";
        $strSQL .= "		ON     MEI.MEISYOU_CD = URI.CKO_HNB_KB" . "\r\n";
        $strSQL .= "		" . "\r\n";
        $strSQL .= "		WHERE    MEI.MEISYOU_ID = '17'" . "\r\n";
        $strSQL .= "		AND      URI.KEIJYO_YM = '@TOUGETU'" . "\r\n";
        $strSQL .= "		GROUP BY MEI.MEISYOU_CD, MEI.MEISYOU" . "\r\n";
        $strSQL .= "		UNION ALL" . "\r\n";
        $strSQL .= "		SELECT MEI.MEISYOU_CD" . "\r\n";
        $strSQL .= "        ,      MEI.MEISYOU" . "\r\n";
        $strSQL .= "        ,      COUNT(URI.UC_NO) * -1" . "\r\n";
        $strSQL .= "        ,      0" . "\r\n";
        $strSQL .= "        ,      SUM(NVL(URI.SRY_PRC,0)) * -1 SYARYO_PRC" . "\r\n";
        $strSQL .= "        ,      SUM(CASE WHEN URI.CKO_HNB_KB = '7' THEN 0  ELSE NVL(URI.CKO_BAI_SATEI,0) END) * -1 SATEI" . "\r\n";
        $strSQL .= "        ,      SUM(NVL(URI.CKO_SAI_MITUMORI,0)) * -1 SAIMITUMORI" . "\r\n";
        $strSQL .= "        ,      SUM(NVL(URI.TKB_KSH_KYK,0)) * -1" . "\r\n";
        $strSQL .= "        ,      SUM(NVL(URI.TKB_KSH_PCS,0)) * -1" . "\r\n";
        $strSQL .= "        ,      (SUM(NVL(URI.KAP_TES_KYK,0)) + SUM(NVL(URI.TOU_SYH_KYK,0)) + SUM(NVL(URI.HOUTEIH_GK,0))) * -1 SONOTA_URI" . "\r\n";

        $strSQL .= "        ,      (SUM(NVL(URI.KAP_TES_KJN,0)) + SUM(NVL(URI.TOU_SYH_KJN,0))) * -1 SONOTA" . "\r\n";

        $strSQL .= "        ,      SUM(NVL(URI.SHR_JKN_SIT_KIN,0) - NVL(URI.TRA_CAR_PRC_SUM,0) - NVL(URI.TRA_CAR_STI_SUM,0)) * -1 SITASON" . "\r\n";
        $strSQL .= "		FROM  HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "		LEFT JOIN " . "\r\n";
        $strSQL .= "				(SELECT W_URI.* FROM HSCURI_VW W_URI" . "\r\n";
        $strSQL .= "				INNER JOIN" . "\r\n";
        $strSQL .= "						(SELECT MAX(VW.KEIJYO_YM) KEIJYOBI" . "\r\n";
        $strSQL .= "						,      UC_NO" . "\r\n";
        $strSQL .= "						FROM   HSCURI_VW VW" . "\r\n";
        $strSQL .= "						WHERE  EXISTS (SELECT *" . "\r\n";
        $strSQL .= "										FROM HSCURI_VW JKN" . "\r\n";
        $strSQL .= "										WHERE JKN.UC_NO = VW.UC_NO" . "\r\n";
        $strSQL .= "										AND   JKN.KEIJYO_YM = '@TOUGETU')" . "\r\n";
        $strSQL .= "						AND    VW.KEIJYO_YM < '@TOUGETU'" . "\r\n";
        $strSQL .= "                " . "\r\n";
        $strSQL .= "						GROUP BY UC_NO) JYO" . "\r\n";
        $strSQL .= "				ON    JYO.UC_NO = W_URI.UC_NO" . "\r\n";
        $strSQL .= "				AND   JYO.KEIJYOBI = W_URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "				) URI" . "\r\n";
        $strSQL .= "		ON     MEI.MEISYOU_CD = URI.CKO_HNB_KB" . "\r\n";
        $strSQL .= "	    WHERE  MEI.MEISYOU_ID = '17'" . "\r\n";
        $strSQL .= "		GROUP BY MEI.MEISYOU_CD, MEI.MEISYOU" . "\r\n";
        $strSQL .= ") V" . "\r\n";
        $strSQL .= "GROUP BY V.MEISYOU_CD ,V.MEISYOU" . "\r\n";
        $strSQL .= "ORDER BY V.MEISYOU_CD" . "\r\n";

        $strSQL = str_replace("@TOUGETU", $cboYMStart, $strSQL);
        return $strSQL;

    }

    public function fncPrintSelect($cboYMStart): array
    {
        return parent::select($this->fncPrintSelectSQL($cboYMStart));
    }

}
