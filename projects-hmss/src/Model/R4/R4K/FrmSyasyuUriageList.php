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
// *　20151112           20151112以降の修正差異点                                         Yin　　　　　　
// * --------------------------------------------------------------------------------------------
// *
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmSyasyuUriageList extends ClsComDb
{
    public function selectSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(KISYU_YMD,1,4) || '/' || SUBSTR(KISYU_YMD,5,2) || '/01') KISYU_YMD" . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    public function fncSelect()
    {
        return parent::select($this->selectSQL());
    }

    public function fncPrintTougetuSQL($cboYMTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT  L_SYA.LINE_NO" . "\r\n";
        $strSQL .= ",       L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= ",       MAX(SYA.SS_CD) SS_CD" . "\r\n";
        $strSQL .= ",       MAX(SYA.SS_NAME) SS_NAME" . "\r\n";
        $strSQL .= ",       SUM(VW.KAIYAKU) * -1 KAIYAKU" . "\r\n";
        $strSQL .= ",       SUM(VW.URI_DAISU) + SUM(VW.KAIYAKU) URI_DAISU" . "\r\n";
        $strSQL .= ",       SUM(VW.SRY_PRC) SRY_PRC" . "\r\n";
        $strSQL .= ",       SUM(VW.HJN_PCS) HJN_PCS" . "\r\n";
        $strSQL .= ",       SUM(VW.NEBIKI) NEBIKI" . "\r\n";
        $strSQL .= ",       SUM(VW.TENPU_KYK) TENPU_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.TENPU_PCS) TENPU_PCS" . "\r\n";
        $strSQL .= ",       SUM(VW.TOKUBETU_KYK) TOKUBETU_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.TOKUBETU_PCS) TOKUBETU_PCS" . "\r\n";
        $strSQL .= ",       TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",       (SUBSTR('@TOUGETU',1,4) || '年' || SUBSTR('@TOUGETU',5,2) || '月') TOUGETU " . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "		SELECT  V.KKR_CD" . "\r\n";
        $strSQL .= "		,       V.UC_NO" . "\r\n";
        $strSQL .= "        ,       NVL(V.KAIYAKU,0) - NVL(JHN.KAIYAKU,0) KAIYAKU" . "\r\n";
        $strSQL .= "        ,       NVL(V.URI_DAISU,0) - NVL(JHN.URI_DAISU,0) URI_DAISU" . "\r\n";
        $strSQL .= "        ,       NVL(V.SRY_PRC,0) - NVL(JHN.SRY_PRC,0) SRY_PRC" . "\r\n";
        $strSQL .= "        ,       NVL(V.HJN_PCS,0) - NVL(JHN.HJN_PCS,0) HJN_PCS" . "\r\n";
        $strSQL .= "        ,       NVL(V.NEBIKI,0) - NVL(JHN.NEBIKI,0) NEBIKI" . "\r\n";
        $strSQL .= "        ,       NVL(V.TENPU_KYK,0) - NVL(JHN.TENPU_KYK,0) TENPU_KYK" . "\r\n";
        $strSQL .= "        ,       NVL(V.TENPU_PCS,0) - NVL(JHN.TENPU_PCS,0) TENPU_PCS" . "\r\n";
        $strSQL .= "        ,       NVL(V.TOKUBETU_KYK,0) - NVL(JHN.TOKUBETU_KYK,0) TOKUBETU_KYK" . "\r\n";
        $strSQL .= "        ,       NVL(V.TOKUBETU_PCS,0) - NVL(JHN.TOKUBETU_PCS,0) TOKUBETU_PCS" . "\r\n";
        $strSQL .= "	    FROM    (" . "\r\n";
        $strSQL .= "				SELECT URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "				,      URI.UC_NO" . "\r\n";
        $strSQL .= "				,      URI.KKR_CD" . "\r\n";
        $strSQL .= "				,      URI.SS_CD" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,1) END) END) URI_DAISU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.SRY_PRC) END)" . "\r\n";
        $strSQL .= "                                        END) SRY_PRC" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.GNK_HJN_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) HJN_PCS" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.SRY_NBK) END)" . "\r\n";
        $strSQL .= "                                        END) NEBIKI" . "\r\n";
        $strSQL .= " 				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.FHZ_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.FHZ_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_PCS" . "\r\n";
        $strSQL .= "				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.TKB_KSH_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.TKB_KSH_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_PCS" . "\r\n";
        $strSQL .= "               	FROM   HSCURI_VW URI" . "\r\n";
        $strSQL .= "				WHERE    URI.KEIJYO_YM = '@TOUGETU'" . "\r\n";
        $strSQL .= "              ) V" . "\r\n";
        $strSQL .= "        " . "\r\n";
        $strSQL .= "		LEFT JOIN" . "\r\n";
        $strSQL .= "		       (SELECT J_HN.UC_NO" . "\r\n";
        $strSQL .= "               ,      J_HN.KKR_CD" . "\r\n";
        $strSQL .= "               ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,1) END) END) URI_DAISU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.SRY_PRC) END)" . "\r\n";
        $strSQL .= "                                        END) SRY_PRC" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.GNK_HJN_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) HJN_PCS" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.SRY_NBK) END)" . "\r\n";
        $strSQL .= "                                        END) NEBIKI" . "\r\n";
        $strSQL .= " 				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.FHZ_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.FHZ_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_PCS" . "\r\n";
        $strSQL .= "				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.TKB_KSH_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.TKB_KSH_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_PCS" . "\r\n";
        $strSQL .= "		        FROM   HJYOUHEN J_HN" . "\r\n";
        $strSQL .= "				,      (SELECT MAX(KEIJYO_YM) KEIJYO_YM" . "\r\n";
        $strSQL .= "				,      UC_NO" . "\r\n";
        //---20151112 Yin DEL S
        //$strSQL .= "                ,      MAX(JKN_HKO_RIRNO) MAX_RIRNO" . "\r\n";
        //---20151112 Yin DEL E
        $strSQL .= "				FROM   HJYOUHEN" . "\r\n";
        $strSQL .= "				WHERE  KEIJYO_YM < '@TOUGETU'" . "\r\n";
        $strSQL .= " 				GROUP BY UC_NO) M_JHN" . "\r\n";
        $strSQL .= "		       WHERE J_HN.KEIJYO_YM = M_JHN.KEIJYO_YM" . "\r\n";
        $strSQL .= "               AND   J_HN.UC_NO = M_JHN.UC_NO" . "\r\n";
        //---20151112 Yin DEL S
        //$strSQL .= "               AND   J_HN.JKN_HKO_RIRNO = M_JHN.MAX_RIRNO" . "\r\n";
        //---20151112 Yin DEL E
        $strSQL .= "               ) JHN" . "\r\n";
        $strSQL .= "		ON JHN.UC_NO = V.UC_NO" . "\r\n";
        $strSQL .= "      	) VW" . "\r\n";
        $strSQL .= "INNER JOIN HSYASYUMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.UCOYA_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HLISTSYASYUMST L_SYA" . "\r\n";
        $strSQL .= "ON     L_SYA.KUKURI_CD = VW.KKR_CD" . "\r\n";

        //---20151112 Yin INS S
        //	 $strSQL .= "WHERE VW.URI_DAISU <> 0" . "\r\n";
        //   $strSQL .= "AND   L_SYA.LINE_NO < 48" . "\r\n";
        //---20151112 Yin INS E

        $strSQL .= "GROUP BY L_SYA.LINE_NO, L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= "ORDER BY L_SYA.LINE_NO" . "\r\n";

        $strSQL = str_replace("@TOUGETU", $cboYMTo, $strSQL);
        return $strSQL;
    }

    public function fncPrintTougetu($cboYMTo): array
    {
        return parent::select($this->fncPrintTougetuSQL($cboYMTo));
    }

    public function fncPrintToukiSQL($cboYMFrom, $cboYMTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT  *" . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "SELECT  L_SYA.LINE_NO" . "\r\n";
        $strSQL .= ",       L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= ",       MAX(SYA.SS_CD) SS_CD" . "\r\n";
        $strSQL .= ",       MAX(SYA.SS_NAME) SS_NAME" . "\r\n";
        $strSQL .= ",       SUM(VW.URI_DAISU) URI_DAISU" . "\r\n";
        $strSQL .= ",       SUM(VW.SRY_PRC) SRY_PRC" . "\r\n";
        $strSQL .= ",       SUM(VW.HJN_PCS) HJN_PCS" . "\r\n";
        $strSQL .= ",       SUM(VW.NEBIKI) NEBIKI" . "\r\n";
        $strSQL .= ",       SUM(VW.TENPU_KYK) TENPU_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.TENPU_PCS) TENPU_PCS" . "\r\n";
        $strSQL .= ",       SUM(VW.TOKUBETU_KYK) TOKUBETU_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.TOKUBETU_PCS) TOKUBETU_PCS" . "\r\n";
        $strSQL .= ",      (SUBSTR('@KISYU',1,4) || '年' || SUBSTR('@KISYU',5,2) || '月' || ' ～ ' ||" . "\r\n";
        $strSQL .= "        SUBSTR('@TOUGETU',1,4) || '年' || SUBSTR('@TOUGETU',5,2) || '月') TOUGETU " . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "		SELECT  V.KKR_CD" . "\r\n";
        $strSQL .= "		,       V.UC_NO" . "\r\n";
        $strSQL .= "        ,       NVL(V.KAIYAKU,0) - NVL(JHN.KAIYAKU,0) KAIYAKU" . "\r\n";
        $strSQL .= "        ,       NVL(V.URI_DAISU,0) - NVL(JHN.URI_DAISU,0) URI_DAISU" . "\r\n";
        $strSQL .= "        ,       NVL(V.SRY_PRC,0) - NVL(JHN.SRY_PRC,0) SRY_PRC" . "\r\n";
        $strSQL .= "        ,       NVL(V.HJN_PCS,0) - NVL(JHN.HJN_PCS,0) HJN_PCS" . "\r\n";
        $strSQL .= "        ,       NVL(V.NEBIKI,0) - NVL(JHN.NEBIKI,0) NEBIKI" . "\r\n";
        $strSQL .= "        ,       NVL(V.TENPU_KYK,0) - NVL(JHN.TENPU_KYK,0) TENPU_KYK" . "\r\n";
        $strSQL .= "        ,       NVL(V.TENPU_PCS,0) - NVL(JHN.TENPU_PCS,0) TENPU_PCS" . "\r\n";
        $strSQL .= "        ,       NVL(V.TOKUBETU_KYK,0) - NVL(JHN.TOKUBETU_KYK,0) TOKUBETU_KYK" . "\r\n";
        $strSQL .= "        ,       NVL(V.TOKUBETU_PCS,0) - NVL(JHN.TOKUBETU_PCS,0) TOKUBETU_PCS" . "\r\n";
        $strSQL .= "	    FROM    (" . "\r\n";
        $strSQL .= "				SELECT URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "				,      URI.UC_NO" . "\r\n";
        $strSQL .= "				,      URI.KKR_CD" . "\r\n";
        $strSQL .= "				,      URI.SS_CD" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,1) END) END) URI_DAISU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.SRY_PRC) END)" . "\r\n";
        $strSQL .= "                                        END) SRY_PRC" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.GNK_HJN_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) HJN_PCS" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.SRY_NBK) END)" . "\r\n";
        $strSQL .= "                                        END) NEBIKI" . "\r\n";
        $strSQL .= " 				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.FHZ_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.FHZ_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_PCS" . "\r\n";
        $strSQL .= "				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.TKB_KSH_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN URI.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN URI.KYK_HNS = '17349' OR URI.HNB_KB = '28' THEN 0 ELSE DECODE(URI.SRY_PRC,0,0,URI.TKB_KSH_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_PCS" . "\r\n";
        $strSQL .= "               	FROM   HSCURI_VW URI" . "\r\n";
        $strSQL .= "				WHERE    URI.KEIJYO_YM BETWEEN '@KISYU' AND '@TOUGETU'" . "\r\n";
        $strSQL .= "              ) V" . "\r\n";
        $strSQL .= "        " . "\r\n";
        $strSQL .= "		LEFT JOIN" . "\r\n";
        $strSQL .= "		       (SELECT J_HN.UC_NO" . "\r\n";
        $strSQL .= "				,      J_HN.KEIJYO_YM" . "\r\n";
        $strSQL .= "               	,      J_HN.KKR_CD" . "\r\n";
        $strSQL .= "				,      M_JHN.UVKEIJYO URI_YM" . "\r\n";
        $strSQL .= "               	,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,1) END) END) URI_DAISU" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.SRY_PRC) END)" . "\r\n";
        $strSQL .= "                                        END) SRY_PRC" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.GNK_HJN_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) HJN_PCS" . "\r\n";
        $strSQL .= "                ,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.SRY_NBK) END)" . "\r\n";
        $strSQL .= "                                        END) NEBIKI" . "\r\n";
        $strSQL .= " 				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.FHZ_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.FHZ_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TENPU_PCS" . "\r\n";
        $strSQL .= "				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.TKB_KSH_KYK) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN J_HN.CEL_DATE IS NOT NULL THEN 0" . "\r\n";
        $strSQL .= "                             ELSE (CASE WHEN J_HN.KYK_HNS = '17349' OR J_HN.HNB_KB = '28' THEN 0 ELSE DECODE(J_HN.SRY_PRC,0,0,J_HN.TKB_KSH_PCS) END)" . "\r\n";
        $strSQL .= "                                        END) TOKUBETU_PCS" . "\r\n";
        $strSQL .= "		        FROM   HJYOUHEN J_HN" . "\r\n";
        $strSQL .= "				,      (SELECT MAX(JH.KEIJYO_YM) KEIJYO_YM" . "\r\n";
        $strSQL .= "						,      UV.KEIJYO_YM UVKEIJYO" . "\r\n";
        $strSQL .= "						,      JH.UC_NO" . "\r\n";
        $strSQL .= "						FROM   HJYOUHEN JH" . "\r\n";
        $strSQL .= "						,      HSCURI_VW UV" . "\r\n";
        $strSQL .= "						WHERE  UV.UC_NO = JH.UC_NO" . "\r\n";
        $strSQL .= "						AND    JH.KEIJYO_YM < UV.KEIJYO_YM" . "\r\n";
        $strSQL .= "						AND    UV.KEIJYO_YM BETWEEN '@KISYU' AND '@TOUGETU'" . "\r\n";
        $strSQL .= "						GROUP BY UV.KEIJYO_YM" . "\r\n";
        $strSQL .= "						,        JH.UC_NO) M_JHN" . "\r\n";
        $strSQL .= "		       WHERE J_HN.KEIJYO_YM = M_JHN.KEIJYO_YM" . "\r\n";
        $strSQL .= "               AND   J_HN.UC_NO = M_JHN.UC_NO" . "\r\n";
        $strSQL .= "               ) JHN" . "\r\n";
        $strSQL .= "		ON JHN.UC_NO = V.UC_NO" . "\r\n";
        $strSQL .= "        AND JHN.URI_YM = V.KEIJYO_YM" . "\r\n";
        $strSQL .= "      	) VW" . "\r\n";
        $strSQL .= "INNER JOIN HSYASYUMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.UCOYA_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HLISTSYASYUMST L_SYA" . "\r\n";
        $strSQL .= "ON     L_SYA.KUKURI_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "GROUP BY L_SYA.LINE_NO, L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= "       ) V" . "\r\n";
        $strSQL .= "WHERE  V.SRY_PRC <> 0 OR V.NEBIKI <> 0 OR V.HJN_PCS <> 0 OR V.TENPU_KYK <> 0 OR V.TENPU_PCS <> 0 OR V.TOKUBETU_KYK <> 0 OR V.TOKUBETU_PCS <> 0 OR V.URI_DAISU <> 0" . "\r\n";
        $strSQL .= "ORDER BY V.LINE_NO" . "\r\n";
        $strSQL = str_replace("@KISYU", $cboYMFrom, $strSQL);
        $strSQL = str_replace("@TOUGETU", $cboYMTo, $strSQL);
        return $strSQL;
    }

    public function fncPrintTouki($cboYMFrom, $cboYMTo): array
    {
        return parent::select($this->fncPrintToukiSQL($cboYMFrom, $cboYMTo));
    }

}