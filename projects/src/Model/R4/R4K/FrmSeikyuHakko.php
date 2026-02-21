<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmSeikyuHakko extends ClsComDb
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

    public function fncPrintSelectSQL($cboYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT  TRUNC((ROW_NUMBER() OVER(ORDER BY V.TOURK_NO1) - 1) / 20) CNT " . "\r\n";
        $strSQL .= ",       V.TOURK_NO1" . "\r\n";
        //
        $strSQL .= ",       V.RIKUJI_CD" . "\r\n";
        //
        $strSQL .= ",       V.TOURK_NO23" . "\r\n";
        $strSQL .= ",       V.SYADAI" . "\r\n";
        $strSQL .= ",       LTRIM(V.CARNO) CARNO" . "\r\n";
        //---20150807 #1964 fanzhengzhou upd s.
        //$strSQL .= ",       (SUBSTR(V.TOU_DATE,5,2) || '月' || SUBSTR(V.TOU_DATE,7,2)) TOU_DATE" . "\r\n";
        $strSQL .= ",       (SUBSTR(V.TOU_DATE,5,2) || '月' || SUBSTR(V.TOU_DATE,7,2) || '日') TOU_DATE" . "\r\n";
        //---20150807 #1964 fanzhengzhou upd e.
        $strSQL .= ",       V.SYARYO_DAI" . "\r\n";

        $strSQL .= ",       V.SYARYO_SHZ" . "\r\n";

        $strSQL .= ",       V.SYARYO_SHZ_RT" . "\r\n";

        $strSQL .= ",       V.TOUROKURYO" . "\r\n";
        $strSQL .= ",       V.SYARYOU_ZEI" . "\r\n";
        $strSQL .= ",       V.RCYL_GK" . "\r\n";
        $strSQL .= ",       V.JYURYO_ZEI" . "\r\n";
        $strSQL .= ",       V.JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= ",       V.JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= ",       V.KASOUHI" . "\r\n";
        $strSQL .= ",       V.KASOUZEI" . "\r\n";
        $strSQL .= ",       V.SITADORI" . "\r\n";
        $strSQL .= ",       V.MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= ",       NVL(V.SYARYO_DAI,0) + NVL(V.SYARYO_SHZ,0) + NVL(V.TOUROKURYO,0)" . "\r\n";
        $strSQL .= "        + NVL(V.SYARYOU_ZEI,0) + NVL(V.RCYL_GK,0) + NVL(V.JYURYO_ZEI,0) " . "\r\n";
        $strSQL .= "        + NVL(V.JIDOUSYA_ZEI,0) + NVL(V.JIBAI_HOK_RYO,0) + NVL(V.KASOUHI,0)" . "\r\n";
        $strSQL .= "        + NVL(V.KASOUZEI,0) - NVL(V.SITADORI,0) SYOUKEI" . "\r\n";
        //- NVL(V.SITADORI,0)"を追加 小計額から下取りが引かれていなかったため
        $strSQL .= ",       ('平成' || SUBSTR(V.W_TODAY,2,2) || '年' || SUBSTR(V.W_TODAY,4,2) || '月' || SUBSTR(V.W_TODAY,6,2) || '日') TODAY" . "\r\n";
        $strSQL .= ",       V.TOUGETU" . "\r\n";
        $strSQL .= ",       GK_TBL.KINGAKU" . "\r\n";
        $strSQL .= ",       GK_TBL.SYOUHIZEI" . "\r\n";
        $strSQL .= ",       V.CMN_NO" . "\r\n";
        $strSQL .= ",       V.SYAIN_NM" . "\r\n";
        $strSQL .= "FROM    (        " . "\r\n";
        $strSQL .= "        SELECT (URI.RIKUJI_CD || TRIM(SUBSTR(URI.TOURK_NO1,5,4))) TOURK_NO1" . "\r\n";
        //
        $strSQL .= "        ,      URI.RIKUJI_CD" . "\r\n";
        //
        $strSQL .= "        ,      (URI.TOURK_NO2 || URI.TOURK_NO3) TOURK_NO23" . "\r\n";
        $strSQL .= "        ,      (RTRIM(URI.SYADAI) || '-') SYADAI" . "\r\n";
        $strSQL .= "        ,      URI.CARNO" . "\r\n";
        $strSQL .= "        ,      URI.TOU_DATE" . "\r\n";
        $strSQL .= "        ,      NVL(URI.SRY_PRC,0) - NVL(URI.SRY_NBK,0) SYARYO_DAI" . "\r\n";
        $strSQL .= "        ,      NVL(URI.SRY_SHZ,0) SYARYO_SHZ" . "\r\n";

        $strSQL .= "        ,      NVL(URI.SRY_SHZ_RT,0) / 100 SYARYO_SHZ_RT" . "\r\n";

        $strSQL .= "        ,      NVL(URI.TOU_SYH_KYK,0) + NVL(URI.TOU_SYH_SHZ,0) + NVL(URI.HOUTEIH_GK,0) - NVL(CMN.RCYL_GK,0) + NVL(URI.PACK_DE_MENTE,0) + NVL(URI.RCY_SKN_KAN_HI,0) + NVL(URI.JAF,0) + NVL(URI.RCY_YOT_KIN,0) + NVL(URI.PACK_DE_753,0) TOUROKURYO" . "\r\n";

        $strSQL .= "        ,      URI.SYARYOU_ZEI" . "\r\n";
        $strSQL .= "        ,      CMN.RCYL_GK" . "\r\n";
        $strSQL .= "        ,      URI.JYURYO_ZEI" . "\r\n";
        $strSQL .= "        ,      URI.JIDOUSYA_ZEI" . "\r\n";
        $strSQL .= "        ,      URI.JIBAI_HOK_RYO" . "\r\n";
        $strSQL .= "        ,      NVL(URI.TKB_KSH_KYK,0) + NVL(URI.FHZ_KYK,0) KASOUHI" . "\r\n";
        $strSQL .= "        ,      NVL(URI.TKB_KSH_SHZ,0) + NVL(URI.FHZ_SHZ,0) KASOUZEI" . "\r\n";
        $strSQL .= "        ,      NVL(URI.SHR_JKN_SIT_KIN,0) + NVL(URI.SHR_JKN_SIT_SHZ,0) SITADORI" . "\r\n";
        $strSQL .= "        ,      URI.MGN_MEI_KNJ1" . "\r\n";
        $strSQL .= "        ,      JPDATE(TO_CHAR(SYSDATE,'YYYYMMDD')) W_TODAY" . "\r\n";
        $strSQL .= "        ,      ('平成' || SUBSTR(JPDATE('@NENGETU'),2,2) || '年' || SUBSTR(JPDATE('@NENGETU'),4,2) || '月度') TOUGETU" . "\r\n";
        $strSQL .= "        ,      URI.CMN_NO" . "\r\n";
        $strSQL .= "        ,      SYA_HNB.SYAIN_NM" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "        FROM   HSCURI URI" . "\r\n";
        $strSQL .= "        LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "        ON     SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "        LEFT JOIN M41E10 CMN" . "\r\n";
        $strSQL .= "        ON     CMN.UC_NO = URI.UC_NO" . "\r\n";

        $strSQL .= "        LEFT JOIN HSYAINMST SYA_HNB" . "\r\n";
        $strSQL .= "        ON     SYA_HNB.SYAIN_NO = CMN.HNB_TAN_EMP_NO" . "\r\n";

        $strSQL .= "        LEFT JOIN M41C01 CUS" . "\r\n";
        $strSQL .= "        ON     CMN.KYK_CUS_NO = CUS.DLRCSRNO" . "\r\n";
        $strSQL .= "        WHERE     URI.UC_NO LIKE '@NENGETU%'" . "\r\n";

        $strSQL .= "        AND    (CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵ-ﾄﾘ-ｽ%'" . "\r\n";
        $strSQL .= "        OR      CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵｰﾄﾘｰｽ%'" . "\r\n";
        $strSQL .= "        OR      CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵｰﾄﾘ-ｽ%'" . "\r\n";
        $strSQL .= "        OR      CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵ-ﾄﾘｰｽ%')" . "\r\n";

        $strSQL .= "		   AND    (NVL(URI.SRY_PRC,0) - NVL(URI.SRY_NBK,0)) <> 0" . "\r\n";
        $strSQL .= "        AND    URI.TOU_DATE LIKE '@NENGETU%'" . "\r\n";

        $strSQL .= "        --ORDER BY   保留" . "\r\n";
        $strSQL .= "       ) V" . "\r\n";
        $strSQL .= ",      (SELECT " . "\r\n";
        $strSQL .= "               SUM(NVL(URI.SRY_PRC,0) - NVL(URI.SRY_NBK,0)" . "\r\n";
        $strSQL .= "                    + NVL(URI.SRY_SHZ,0) + NVL(URI.TOU_SYH_KYK,0)" . "\r\n";

        $strSQL .= "                    + NVL(URI.PACK_DE_MENTE,0) + NVL(URI.RCY_SKN_KAN_HI,0)" . "\r\n";
        $strSQL .= "                    + NVL(URI.JAF,0) + NVL(URI.RCY_YOT_KIN,0)" . "\r\n";

        $strSQL .= "                    + NVL(URI.PACK_DE_753,0)" . "\r\n";

        $strSQL .= "                    + NVL(URI.TOU_SYH_SHZ,0) + NVL(URI.HOUTEIH_GK,0)" . "\r\n";
        $strSQL .= "                    - NVL(CMN.RCYL_GK,0) + NVL(URI.SYARYOU_ZEI,0)" . "\r\n";

        $strSQL .= "                    + NVL(CMN.RCYL_GK,0)" . "\r\n";

        $strSQL .= "                    + NVL(URI.JYURYO_ZEI,0) + NVL(URI.JIDOUSYA_ZEI,0)" . "\r\n";
        $strSQL .= "                    + NVL(URI.JIBAI_HOK_RYO,0) + NVL(URI.TKB_KSH_KYK,0)" . "\r\n";
        $strSQL .= "                    + NVL(URI.FHZ_KYK,0) + NVL(URI.TKB_KSH_SHZ,0)" . "\r\n";

        $strSQL .= "                    + NVL(URI.FHZ_SHZ,0)" . "\r\n";
        $strSQL .= "                    - (NVL(URI.SHR_JKN_SIT_KIN,0) + NVL(URI.SHR_JKN_SIT_SHZ,0))) KINGAKU" . "\r\n";
        $strSQL .= "        ,      SUM(NVL(URI.SRY_SHZ,0) + NVL(URI.TKB_KSH_SHZ,0) + NVL(URI.FHZ_SHZ,0)) SYOUHIZEI" . "\r\n";
        $strSQL .= "        FROM   HSCURI URI" . "\r\n";
        $strSQL .= "        LEFT JOIN  M41E10 CMN" . "\r\n";
        $strSQL .= "        ON     CMN.UC_NO = URI.UC_NO" . "\r\n";
        $strSQL .= "        LEFT JOIN M41C01 CUS" . "\r\n";
        $strSQL .= "        ON     CMN.KYK_CUS_NO = CUS.DLRCSRNO" . "\r\n";
        $strSQL .= "        " . "\r\n";
        $strSQL .= "        WHERE  URI.UC_NO LIKE '@NENGETU%'" . "\r\n";
        $strSQL .= "        AND    (CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵ-ﾄﾘ-ｽ%'" . "\r\n";
        $strSQL .= "        OR      CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵｰﾄﾘｰｽ%'" . "\r\n";
        $strSQL .= "        OR      CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵｰﾄﾘ-ｽ%'" . "\r\n";
        $strSQL .= "        OR      CUS.CSRKNANM LIKE 'ﾏﾂﾀﾞｵ-ﾄﾘｰｽ%')" . "\r\n";
        $strSQL .= "		   AND    (NVL(URI.SRY_PRC,0) - NVL(URI.SRY_NBK,0)) <> 0" . "\r\n";
        $strSQL .= "        AND    URI.TOU_DATE LIKE '@NENGETU%') GK_TBL" . "\r\n";
        $strSQL = str_replace("@NENGETU", $cboYM, $strSQL);
        $strSQL = str_replace("@NEN", substr($cboYM, 0, 4), $strSQL);
        $strSQL = str_replace("@TUKI", substr($cboYM, 4, 2), $strSQL);
        return $strSQL;
    }

    public function fncPrintSelect($cboYM): array
    {
        return parent::select($this->fncPrintSelectSQL($cboYM));
    }

}