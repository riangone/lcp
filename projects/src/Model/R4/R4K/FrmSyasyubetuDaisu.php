<?php
//
// * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
// * @alias  panel
// * @author FCSDL
// *
// * 履歴：
// * --------------------------------------------------------------------------------------------
// * 日付                Feature/Bug                  内容                             担当
// * YYYYMMDD           #ID                          XXXXXX                           FCSDL
// * 20151008           20150929以降の修正差異点                                         li
// *　20151112           20151112以降の修正差異点                                         Yin　　　　　　
// * --------------------------------------------------------------------------------------------
// *
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmSyasyubetuDaisu extends ClsComDb
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

    public function fncPrintTougetutSQL($cboYMFrom)
    {
        $strSQL = "";
        $strSQL .= "SELECT  L_SYA.LINE_NO" . "\r\n";
        $strSQL .= ",       L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= ",       MAX(CASE WHEN L_SYA.CAR_KBN = '2' THEN 'その他' ELSE SYA.SS_NAME END) SYASYUMEI" . "\r\n";
        $strSQL .= ",       SUM(VW.UC_KENSU) UC_KENSU" . "\r\n";
        $strSQL .= ",       SUM(VW.MIJISSEKI) MIJISSEKI" . "\r\n";
        $strSQL .= ",       SUM(VW.TOU_JISSEKI) TOU_JISSEKI" . "\r\n";
        $strSQL .= ",       SUM(VW.TAKYK_JITRK) TAKYK_JITRK" . "\r\n";
        $strSQL .= ",       SUM(VW.FUKUSHI) FUKUSHI" . "\r\n";
        $strSQL .= ",       SUM(VW.MEKER) MEKER" . "\r\n";
        $strSQL .= ",       SUM(VW.SYAMEI) SYAMEI" . "\r\n";
        $strSQL .= ",       SUM(VW.KAIYAKU) KAIYAKU" . "\r\n";
        $strSQL .= ",       SUM(VW.URI_JISSEKI) URI_JISSEKI" . "\r\n";
        $strSQL .= ",       SUM(VW.JIKYK_TATRK) JIKYK_TATRK" . "\r\n";
        $strSQL .= ",       SUM(VW.URI_JISSEKI) - SUM(VW.JIKYK_TATRK) IPPAN" . "\r\n";
        $strSQL .= ",       SUM(VW.LEASE) LEASE" . "\r\n";
        $strSQL .= ",       SUM(VW.SERVICE_CAR) SERVICE_CAR" . "\r\n";
        $strSQL .= ",       SUM(VW.SAIBAI) SAIBAI" . "\r\n";
        $strSQL .= ",       SUM(VW.KB_TOUROKU) KB_TOUROKU" . "\r\n";
        $strSQL .= ",       SUM(VW.KB_URIAGE) KB_URIAGE" . "\r\n";
        $strSQL .= ",       SUM(VW.KB_SONOTA) KB_SONOTA" . "\r\n";
        $strSQL .= ",       SUM(VW.KARUTE) KARUTE" . "\r\n";
        $strSQL .= ",       TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",       (SUBSTR('@TOUGETU',1,4) || '年' || SUBSTR('@TOUGETU',5,2) || '月') TOUGETU " . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "		SELECT  V.KKR_CD" . "\r\n";
        $strSQL .= "		,       V.UC_NO" . "\r\n";
        $strSQL .= "        ,       NVL(V.UC_KENSU,0) - NVL(JHN.UC_KENSU,0) UC_KENSU" . "\r\n";
        $strSQL .= "        ,       NVL(V.MIJISSEKI,0) - NVL(JHN.MIJISSEKI,0) MIJISSEKI" . "\r\n";
        $strSQL .= "        ,       NVL(V.TOU_JISSEKI,0) - NVL(JHN.TOU_JISSEKI,0) TOU_JISSEKI" . "\r\n";
        $strSQL .= "        ,       NVL(V.TAKYK_JITRK,0) - NVL(JHN.TAKYK_JITRK,0) TAKYK_JITRK" . "\r\n";
        $strSQL .= "        ,       NVL(V.FUKUSHI,0) - NVL(JHN.FUKUSHI,0) FUKUSHI" . "\r\n";
        $strSQL .= "        ,       NVL(V.MEKER,0) - NVL(JHN.MEKER,0) MEKER" . "\r\n";
        $strSQL .= "        ,       NVL(V.SYAMEI,0) - NVL(JHN.SYAMEI,0) SYAMEI" . "\r\n";
        $strSQL .= "        ,       NVL(V.KAIYAKU,0) - NVL(JHN.KAIYAKU,0) KAIYAKU" . "\r\n";
        $strSQL .= "        ,       NVL(V.URI_JISSEKI,0) - NVL(JHN.URI_JISSEKI,0) URI_JISSEKI" . "\r\n";
        $strSQL .= "        ,       NVL(V.JIKYK_TATRK,0) - NVL(JHN.JIKYK_TATRK,0) JIKYK_TATRK" . "\r\n";
        $strSQL .= "        ,       NVL(V.LEASE,0) - NVL(JHN.LEASE,0) LEASE" . "\r\n";
        $strSQL .= "        ,       NVL(V.SERVICE_CAR,0) - NVL(JHN.SERVICE_CAR,0) SERVICE_CAR" . "\r\n";
        $strSQL .= "        ,       NVL(V.SAIBAI,0) - NVL(JHN.SAIBAI,0) SAIBAI" . "\r\n";
        $strSQL .= "        ,       NVL(V.KB_TOUROKU,0) - NVL(JHN.KB_TOUROKU,0) KB_TOUROKU" . "\r\n";
        $strSQL .= "        ,       NVL(V.KB_URIAGE,0) - NVL(JHN.KB_URIAGE,0) KB_URIAGE" . "\r\n";
        $strSQL .= "        ,       NVL(V.KB_SONOTA,0) - NVL(JHN.KB_SONOTA,0) KB_SONOTA" . "\r\n";
        $strSQL .= "        ,       NVL(V.KARUTE,0) - NVL(JHN.KARUTE,0) KARUTE" . "\r\n";
        $strSQL .= "	    FROM    (" . "\r\n";
        $strSQL .= "				SELECT URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "				,      URI.UC_NO" . "\r\n";
        $strSQL .= "				,      URI.KKR_CD" . "\r\n";
        $strSQL .= "				,      URI.SS_CD" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.UC_NO IS NOT NULL THEN 1 ELSE 0 END) UC_KENSU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN (URI.KYK_HNS = '17349' AND SUBSTR(URI.UC_NO,10,1) > '9') " . "\r\n";
        $strSQL .= "                                   OR (URI.KYK_HNS IN ('00000','3634') AND SUBSTR(URI.UC_NO,10,1) > '9' AND URI.SRY_PRC = 0)" . "\r\n";
        $strSQL .= "                                   OR (URI.KYK_HNS = '3734' AND URI.TOU_HNS <> '3634' AND SUBSTR(URI.UC_NO,10,1) > '9')" . "\r\n";
        $strSQL .= "                                   OR (NVL(URI.KYK_HNS,'3634') <> '3634' AND NVL(URI.TOU_HNS,'3634') <> '3634') THEN 1 ELSE 0 END) MIJISSEKI" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL" . "\r\n";

        $strSQL .= "                                     THEN (CASE WHEN (SUBSTR(URI.UC_NO,10,1) BETWEEN '0' AND '9' AND SUBSTR(URI.UC_NO,7,3) <> 'ZZZ') OR SUBSTR(URI.UC_NO,7,3) = 'TAT' THEN 1 ELSE 0 END)" . "\r\n";
        $strSQL .= "                                     ELSE (CASE WHEN (SUBSTR(URI.UC_NO,10,1) BETWEEN '0' AND '9' AND SUBSTR(URI.UC_NO,7,3) <> 'ZZZ') AND SUBSTR(URI.UC_NO,7,3) <> 'TAT' THEN 1 END)" . "\r\n";

        $strSQL .= "                        END) TOU_JISSEKI" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.KYK_HNS NOT IN ('17349','00000','3634') AND URI.SRY_PRC = 0 AND URI.TOU_HNS = '3634' THEN 1 ELSE 0 END) TAKYK_JITRK" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 1 ELSE 0 END) FUKUSHI" . "\r\n";
        $strSQL .= "			    ,      (CASE WHEN URI.KYK_HNS = '00000' THEN 1 ELSE 0 END) MEKER" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN NVL(URI.KYK_HNS,'3634') = '3634' AND URI.SRY_PRC = 0 THEN 1 ELSE 0 END) END) SYAMEI" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,1) END) END) URI_JISSEKI" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE (CASE WHEN SUBSTR(URI.UC_NO,7,3) = 'TAT' THEN 1 ELSE 0 END)" . "\r\n";
        $strSQL .= "                                        END) END) JIKYK_TATRK" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.LEASE_KB = '1' THEN 1 ELSE 0 END) LEASE" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 " . "\r\n";
        $strSQL .= "                                        ELSE (CASE WHEN SUBSTR(URI.UC_NO,7,3) <> 'TAT' AND URI.HNB_KB = 'A' THEN 1 ELSE 0 END)" . "\r\n";
        $strSQL .= "                                        END) END) SERVICE_CAR" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN (URI.KYK_HNS = '17349' AND SUBSTR(URI.UC_NO,10,1) > '9') " . "\r\n";
        $strSQL .= "                                   OR (URI.KYK_HNS IN ('00000','3634') AND SUBSTR(URI.UC_NO,10,1) > '9' AND URI.SRY_PRC = 0)" . "\r\n";
        $strSQL .= "                                   OR (URI.KYK_HNS = '3734' AND URI.TOU_HNS <> '3634' AND SUBSTR(URI.UC_NO,10,1) > '9')" . "\r\n";
        $strSQL .= "                                   OR (URI.SRY_PRC > 0 AND SUBSTR(URI.UC_NO,7,3) <> 'TAT' AND SUBSTR(URI.UC_NO,10,1) > '9') THEN 1 ELSE 0 END) SAIBAI" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.TRK_KB IN ('1','2') THEN 1 ELSE 0 END) KB_TOUROKU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.TRK_KB IN ('1','3') THEN 1 ELSE 0 END) KB_URIAGE" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.TRK_KB = '4' THEN 1 ELSE 0 END) KB_SONOTA" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.SAV_KTNCD <> '00' THEN 1 ELSE 0 END) KARUTE" . "\r\n";
        $strSQL .= "               	FROM   HSCURI_VW URI" . "\r\n";
        $strSQL .= "				WHERE    URI.KEIJYO_YM = '@TOUGETU'" . "\r\n";
        $strSQL .= "              ) V" . "\r\n";
        $strSQL .= "        " . "\r\n";
        $strSQL .= "		LEFT JOIN" . "\r\n";
        $strSQL .= "		       (SELECT J_HN.UC_NO" . "\r\n";
        $strSQL .= "               ,      J_HN.KKR_CD" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.UC_NO IS NOT NULL THEN 1 ELSE 0 END) UC_KENSU" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN (J_HN.KYK_HNS = '17349' AND SUBSTR(J_HN.UC_NO,10,1) > '9') " . "\r\n";
        $strSQL .= "                                  OR (J_HN.KYK_HNS IN ('00000','3634') AND SUBSTR(J_HN.UC_NO,10,1) > '9' AND J_HN.SRY_PRC = 0)" . "\r\n";
        $strSQL .= "                                  OR (J_HN.KYK_HNS = '3734' AND J_HN.TOU_HNS <> '3634' AND SUBSTR(J_HN.UC_NO,10,1) > '9')" . "\r\n";
        $strSQL .= "                                  OR (NVL(J_HN.KYK_HNS,'3634') <> '3634' AND NVL(J_HN.TOU_HNS,'3634') <> '3634') THEN 1 ELSE 0 END) MIJISSEKI" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL" . "\r\n";

        $strSQL .= "                                     THEN (CASE WHEN (SUBSTR(J_HN.UC_NO,10,1) BETWEEN '0' AND '9' AND SUBSTR(J_HN.UC_NO,7,3) <> 'ZZZ') OR SUBSTR(J_HN.UC_NO,7,3) = 'TAT' THEN 1 ELSE 0 END)" . "\r\n";
        $strSQL .= "                                     ELSE (CASE WHEN (SUBSTR(J_HN.UC_NO,10,1) BETWEEN '0' AND '9' AND SUBSTR(J_HN.UC_NO,7,3) <> 'ZZZ') AND SUBSTR(J_HN.UC_NO,7,3) <> 'TAT' THEN 1 END)" . "\r\n";

        $strSQL .= "                        END) TOU_JISSEKI" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.KYK_HNS NOT IN ('17349','00000','3634') AND J_HN.SRY_PRC = 0 AND J_HN.TOU_HNS = '3634' THEN 1 ELSE 0 END) TAKYK_JITRK" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 1 ELSE 0 END) FUKUSHI" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.KYK_HNS = '00000' THEN 1 ELSE 0 END) MEKER" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                            ELSE (CASE WHEN NVL(J_HN.KYK_HNS,'3634') = '3634' AND J_HN.SRY_PRC = 0 THEN 1 ELSE 0 END) END)SYAMEI" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                            ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,1) END) END) URI_JISSEKI" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                            ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE (CASE WHEN SUBSTR(J_HN.UC_NO,7,3) = 'TAT' THEN 1 ELSE 0 END)" . "\r\n";
        $strSQL .= "                                       END) END) JIKYK_TATRK" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.LEASE_KB = '1' THEN 1 ELSE 0 END) LEASE" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                            ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 " . "\r\n";
        $strSQL .= "                                       ELSE (CASE WHEN SUBSTR(J_HN.UC_NO,7,3) <> 'TAT' AND J_HN.HNB_KB = 'A' THEN 1 ELSE 0 END)" . "\r\n";
        $strSQL .= "                                       END) END) SERVICE_CAR" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN (J_HN.KYK_HNS = '17349' AND SUBSTR(J_HN.UC_NO,10,1) > '9') " . "\r\n";
        $strSQL .= "                                  OR (J_HN.KYK_HNS IN ('00000','3634') AND SUBSTR(J_HN.UC_NO,10,1) > '9' AND J_HN.SRY_PRC = 0)" . "\r\n";
        $strSQL .= "                                  OR (J_HN.KYK_HNS = '3734' AND J_HN.TOU_HNS <> '3634' AND SUBSTR(J_HN.UC_NO,10,1) > '9')" . "\r\n";
        $strSQL .= "                                  OR (J_HN.SRY_PRC > 0 AND SUBSTR(J_HN.UC_NO,7,3) <> 'TAT' AND SUBSTR(J_HN.UC_NO,10,1) > '9') THEN 1 ELSE 0 END) SAIBAI" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.TRK_KB IN ('1','2') THEN 1 ELSE 0 END) KB_TOUROKU" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.TRK_KB IN ('1','3') THEN 1 ELSE 0 END) KB_URIAGE" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.TRK_KB = '4' THEN 1 ELSE 0 END) KB_SONOTA" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.SAV_KTNCD <> '00' THEN 1 ELSE 0 END) KARUTE" . "\r\n";
        $strSQL .= "		        FROM   HJYOUHEN J_HN" . "\r\n";
        //---20151008 li UPD S.
        // $strSQL .= "			,      (SELECT MAX(KEIJYO_YM) KEIJYO_YM" . "\r\n";
        // $strSQL .= "				,      UC_NO" . "\r\n";
        // $strSQL .= "                         ,      MAX(JKN_HKO_RIRNO) MAX_RIRNO" . "\r\n";
        // $strSQL .= "				FROM   HJYOUHEN" . "\r\n";
        // $strSQL .= "				WHERE  KEIJYO_YM < '@TOUGETU'" . "\r\n";
        // $strSQL .= " 			GROUP BY UC_NO) M_JHN" . "\r\n";
        // $strSQL .= "		       WHERE J_HN.KEIJYO_YM = M_JHN.KEIJYO_YM" . "\r\n";
        // $strSQL .= "               AND   J_HN.UC_NO = M_JHN.UC_NO" . "\r\n";
        // $strSQL .= "               AND   J_HN.JKN_HKO_RIRNO = M_JHN.MAX_RIRNO" . "\r\n";  '@TOUGETU'
        //---20151112 Yin DEL S
        //$strSQL .= "               WHERE   J_HN.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN D WHERE D.UC_NO = J_HN.UC_NO AND D.KEIJYO_YM < '@TOUGETU')" . "\r\n";
        //---20151112 Yin DEL E
        //---20151008 li UPD E.
        //---20151112 Yin INS S
        $strSQL .= "			,      (SELECT MAX(KEIJYO_YM) KEIJYO_MAX" . "\r\n";
        $strSQL .= "				         ,      UC_NO" . "\r\n";
        //        $strSQL .= "                      ,      MAX(JKN_HKO_RIRNO) MAX_RIRNO" . "\r\n";
        $strSQL .= "				FROM   HJYOUHEN" . "\r\n";
        //$strSQL .= "				WHERE  KEIJYO_YM < '@TOUGETU'" . "\r\n";
        $strSQL .= "				WHERE  KEIJYO_YM < '@TOUGETU'" . "\r\n";
        $strSQL .= " 			GROUP BY UC_NO) M_JHN" . "\r\n";
        $strSQL .= "		       WHERE J_HN.KEIJYO_YM = M_JHN.KEIJYO_MAX" . "\r\n";
        $strSQL .= "               AND   J_HN.UC_NO = M_JHN.UC_NO" . "\r\n";
        //        $strSQL .= "               AND   J_HN.JKN_HKO_RIRNO = M_JHN.MAX_RIRNO" . "\r\n";
        //---20151112 Yin INS E
        $strSQL .= "               ) JHN" . "\r\n";
        $strSQL .= "		ON JHN.UC_NO = V.UC_NO" . "\r\n";
        $strSQL .= "      	) VW" . "\r\n";
        $strSQL .= "INNER JOIN HSYASYUMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.UCOYA_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HLISTSYASYUMST L_SYA" . "\r\n";
        $strSQL .= "ON     L_SYA.KUKURI_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "GROUP BY L_SYA.LINE_NO, L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= "ORDER BY L_SYA.LINE_NO" . "\r\n";

        $strSQL = str_replace("@TOUGETU", $cboYMFrom, $strSQL);
        return $strSQL;
    }

    public function fncPrintTougetut($cboYMFrom): array
    {
        return parent::select($this->fncPrintTougetutSQL($cboYMFrom));
    }

}