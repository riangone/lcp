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

class FrmSyasyuTourokuList extends ClsComDb
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

    public function fncPrintTougetutSQL($cboYMTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT  L_SYA.LINE_NO" . "\r\n";
        $strSQL .= ",       L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= ",       MAX(SYA.SS_NAME) SYASYU_NM" . "\r\n";
        $strSQL .= ",       SUM(VW.DAISU) DAISU" . "\r\n";
        $strSQL .= ",       SUM(VW.KAIYAKU) KAIYAKU" . "\r\n";
        $strSQL .= ",       SUM(VW.KAKAKU) KAKAKU" . "\r\n";
        $strSQL .= ",       SUM(VW.GENKA) GENKA" . "\r\n";
        $strSQL .= ",       SUM(VW.NEBIKI) NEBIKI" . "\r\n";
        $strSQL .= ",       SUM(VW.FHZ_KYK) FHZ_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.FHZ_PCS) FHZ_PCS" . "\r\n";
        $strSQL .= ",       SUM(VW.TKB_KYK) TKB_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.TKB_PCS) TKB_PCS" . "\r\n";
        $strSQL .= ",       TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",       (SUBSTR('@TOUGETU',1,4) || '年' || SUBSTR('@TOUGETU',5,2) || '月') TOUGETU " . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "		        SELECT URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "				,      URI.UC_NO" . "\r\n";
        $strSQL .= " 　　　　　　,      URI.KKR_CD" . "\r\n";
        $strSQL .= "				,      URI.SS_CD" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.TOU_DAISU,0) END) DAISU" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.SRY_PRC,0) END) KAKAKU" . "\r\n";
        $strSQL .= "             ,      (CASE WHEN CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE (CASE WHEN URI.KYK_HNS = '17349' THEN 0 ELSE NVL(URI.GNK_HJN_PCS,0) END) END) GENKA" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.SRY_NBK,0) END) NEBIKI" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.FHZ_KYK,0) END) FHZ_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.FHZ_PCS,0) END) FHZ_PCS" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.TKB_KSH_KYK,0) END) TKB_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.TKB_KSH_PCS,0) END) TKB_PCS" . "\r\n";
        $strSQL .= "				FROM   HSCURI_VW URI" . "\r\n";
        //---20151112 Yin UPD S
        //$strSQL .= "                    ,      (SELECT MIN(KEIJYO_YM) KEIJYO_YM" . "\r\n";
        $strSQL .= "                    ,      (SELECT MIN(KEIJYO_YM) KEIJYO_YM" . "\r\n";
        //---20151112 Yin UPD E
        $strSQL .= "				               ,       UC_NO" . "\r\n";
        $strSQL .= "                            FROM   HSCURI_VW" . "\r\n";
        $strSQL .= "				        GROUP BY UC_NO) WK_URI" . "\r\n";
        $strSQL .= "				WHERE  URI.KEIJYO_YM = WK_URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "                         AND    URI.UC_NO = WK_URI.UC_NO" . "\r\n";
        $strSQL .= "                         AND    URI.TOU_HNS = '3634'" . "\r\n";
        $strSQL .= "                         AND    URI.KEIJYO_YM = '@TOUGETU'" . "\r\n";
        $strSQL .= "        ) VW" . "\r\n";
        $strSQL .= "LEFT JOIN HSYASYUMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.UCOYA_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HLISTSYASYUMST L_SYA" . "\r\n";
        $strSQL .= "ON     L_SYA.KUKURI_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "WHERE VW.DAISU <> 0" . "\r\n";
        $strSQL .= "AND   L_SYA.LINE_NO < 48" . "\r\n";
        $strSQL .= "GROUP BY L_SYA.LINE_NO" . "\r\n";
        $strSQL .= ",        L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= "ORDER BY L_SYA.LINE_NO" . "\r\n";
        $strSQL = str_replace("@TOUGETU", $cboYMTo, $strSQL);
        return $strSQL;
    }

    public function fncPrintTougetut($cboYMTo): array
    {
        return parent::select($this->fncPrintTougetutSQL($cboYMTo));
    }

    public function fncPrintToukiSQL($cboYMFrom, $cboYMTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT  L_SYA.LINE_NO" . "\r\n";
        $strSQL .= ",       L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= ",       MAX(SYA.SS_NAME) SYASYU_NM" . "\r\n";
        $strSQL .= ",       SUM(VW.DAISU) DAISU" . "\r\n";

        $strSQL .= ",       SUM(VW.KAIYAKU) KAIYAKU" . "\r\n";

        $strSQL .= ",       SUM(VW.KAKAKU) KAKAKU" . "\r\n";
        $strSQL .= ",       SUM(VW.GENKA) GENKA" . "\r\n";
        $strSQL .= ",       SUM(VW.NEBIKI) NEBIKI" . "\r\n";
        $strSQL .= ",       SUM(VW.FHZ_KYK) FHZ_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.FHZ_PCS) FHZ_PCS" . "\r\n";
        $strSQL .= ",       SUM(VW.TKB_KYK) TKB_KYK" . "\r\n";
        $strSQL .= ",       SUM(VW.TKB_PCS) TKB_PCS" . "\r\n";
        $strSQL .= ",       TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",       (SUBSTR('@KISYU',1,4) || '年' || SUBSTR('@KISYU',5,2) || '月' || ' ～ ' ||" . "\r\n";
        $strSQL .= "         SUBSTR('@TOUGETU',1,4) || '年' || SUBSTR('@TOUGETU',5,2) || '月') TOUGETU " . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "		        SELECT URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "				,      URI.UC_NO" . "\r\n";
        $strSQL .= " 　　　　　　	        ,      URI.KKR_CD" . "\r\n";
        $strSQL .= "				,      URI.SS_CD" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.TOU_DAISU,0) END) DAISU" . "\r\n";

        $strSQL .= "             ,      (CASE WHEN CEL_DATE IS NOT NULL THEN 1 ELSE 0 END) KAIYAKU" . "\r\n";

        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.SRY_PRC,0) END) KAKAKU" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE (CASE WHEN URI.KYK_HNS = '17349' THEN 0 ELSE NVL(URI.GNK_HJN_PCS,0) END) END) GENKA" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.SRY_NBK,0) END) NEBIKI" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.FHZ_KYK,0) END) FHZ_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.FHZ_PCS,0) END) FHZ_PCS" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.TKB_KSH_KYK,0) END) TKB_KYK" . "\r\n";
        $strSQL .= "				,      (CASE WHEN CEL_DATE IS NOT NULL THEN 0 ELSE NVL(URI.TKB_KSH_PCS,0) END) TKB_PCS" . "\r\n";
        $strSQL .= "				FROM   HSCURI_VW URI" . "\r\n";
        //---20151112 Yin UPD S
        //$strSQL .= "                         ,      (SELECT MIN(KEIJYO_YM) KEIJYO_YM" . "\r\n";
        $strSQL .= "                         ,      (SELECT MIN(KEIJYO_YM) KEIJYO_YM" . "\r\n";
        //---20151112 Yin UPD E
        $strSQL .= "				        ,       UC_NO" . "\r\n";
        $strSQL .= "                                 FROM   HSCURI_VW" . "\r\n";
        $strSQL .= "				        GROUP BY UC_NO) WK_URI" . "\r\n";
        $strSQL .= "				WHERE  URI.KEIJYO_YM = WK_URI.KEIJYO_YM" . "\r\n";
        $strSQL .= "                         AND    URI.UC_NO = WK_URI.UC_NO" . "\r\n";
        $strSQL .= "                         AND    URI.TOU_HNS = '3634'" . "\r\n";
        $strSQL .= "                         AND    URI.KEIJYO_YM BETWEEN '@KISYU' AND '@TOUGETU'" . "\r\n";
        $strSQL .= "        ) VW" . "\r\n";
        $strSQL .= "LEFT JOIN HSYASYUMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.UCOYA_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HLISTSYASYUMST L_SYA" . "\r\n";
        $strSQL .= "ON     L_SYA.KUKURI_CD = VW.KKR_CD" . "\r\n";
        $strSQL .= "WHERE VW.DAISU <> 0" . "\r\n";
        $strSQL .= "AND   L_SYA.LINE_NO < 48" . "\r\n";
        $strSQL .= "GROUP BY L_SYA.LINE_NO" . "\r\n";
        $strSQL .= ",        L_SYA.CAR_KBN" . "\r\n";
        $strSQL .= "ORDER BY L_SYA.LINE_NO" . "\r\n";

        $strSQL = str_replace("@KISYU", $cboYMFrom, $strSQL);
        $strSQL = str_replace("@TOUGETU", $cboYMTo, $strSQL);
        return $strSQL;
    }

    public function fncPrintTouki($cboYMFrom, $cboYMTo): array
    {
        return parent::select($this->fncPrintToukiSQL($cboYMFrom, $cboYMTo));
    }

}