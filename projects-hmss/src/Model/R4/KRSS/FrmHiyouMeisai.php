<?php
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFncKRSS;
class FrmHiyouMeisai extends ClsComDb
{
    //---execute---
    public function fncHKEIRICTL()
    {
        $sqlstr = $this->fncHKEIRICTL_sql();
        return parent::select($sqlstr);
    }

    public function fncAuthCheck()
    {
        $sqlstr = $this->fncAuthCheck_sql();
        return parent::select($sqlstr);
    }

    public function FncGetBusyoMstValue()
    {
        $sqlstr = $this->FncGetBusyoMstValue_sql();
        return parent::select($sqlstr);
    }

    public function FncGetKamokuMstValue()
    {
        $sqlstr = $this->FncGetKamokuMstValue_sql();
        return parent::select($sqlstr);
    }

    public function fncHiyoumeisaiSel($data)
    {
        $sqlstr = $this->fncHiyoumeisaiSel_sql($data);
        return parent::select($sqlstr);
    }

    public function fncGetLoginUserBusyonCD($data)
    {
        $sqlstr = $this->fncGetLoginUserBusyonCD_sql($data);
        return parent::select($sqlstr);
    }

    //---sql---
    public function fncHKEIRICTL_sql()
    {

        $sqlstr = "SELECT ID , (SUBSTR(SYR_YMD,1,4)  || SUBSTR(SYR_YMD,5,2)) TOUGETU FROM HKEIRICTL WHERE ID = '01'";

        return $sqlstr;
    }

    public function fncAuthCheck_sql()
    {
        $sqlstr = "SELECT SYAIN_NO 		";
        $sqlstr .= ",		BUSYO_CD  		";
        $sqlstr .= "FROM HAUTHORITY_CTL 	";
        $sqlstr .= "WHERE ";
        $sqlstr .= "		SYAIN_NO = '@SYAIN_NO' ";
        $sqlstr .= "AND   SYS_KB = '@SYS_KB'	";
        $sqlstr .= " GROUP BY SYAIN_NO ";
        $sqlstr .= ",		BUSYO_CD";
        $sqlstr = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@SYS_KB", ClsComFncKRSS::GSYSTEM_KB, $sqlstr);


        return $sqlstr;
    }

    public function FncGetBusyoMstValue_sql()
    {
        $strSql = "";
        //** ＳＱＬ作成
        $strSql .= "SELECT BUSYO_NM" . "\r\n";
        $strSql .= ",      KKR_BUSYO_CD " . "\r\n";
        $strSql .= ",      BUSYO_CD " . "\r\n";

        $strSql .= "FROM   HBUSYO " . "\r\n";
        $strSql .= " where ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')" . "\r\n";
        return $strSql;
    }

    public function FncGetKamokuMstValue_sql()
    {
        $strSql = "";
        $strSql .= "SELECT KAMOK_NM" . "\r\n";
        $strSql .= ",KAMOK_CD\r\n";
        $strSql .= "FROM  M_KAMOKU A" . "\r\n";
        $strSql .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        return $strSql;
    }

    public function fncHiyoumeisaiSel_sql($data)
    {

        $strSql = "";

        ///------

        $strSql .= "SELECT SUBSTR(V.KEIJYOBI,1,4) NEN \r\n";
        $strSql .= ",      SUBSTR(V.KEIJYOBI,5,2) TUKI\r\n";
        $strSql .= ",      SUBSTR(V.KEIJYOBI,7,2) HI\r\n";
        $strSql .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY\r\n";
        $strSql .= ",      V.KAMOKUCD\r\n";
        $strSql .= ",      (DECODE(KH.KAMOK_NM,NULL,KMK.KAMOKUMEI,KH.KAMOK_NM)) KAMOKUMEI\r\n";
        $strSql .= ",     (DECODE(KH.KAMOKUMEI,NULL,KMK.KAMOKUMEI,KH.KAMOKUMEI)) KOMOKUMEI\r\n";
        $strSql .= ",      V.HIMOKUCD\r\n";
        $strSql .= ",      V.BUSYOCD\r\n";
        $strSql .= ",      BUS.BUSYO_NM M_BUSYONM\r\n";
        $strSql .= ",      V.AITEKAMOKU\r\n";
        $strSql .= ",      (DECODE(A_KH.KAMOKUMEI,NULL,A_K.KAMOKUMEI,A_KH.KAMOKUMEI)) A_KAMOKUMEI\r\n";
        $strSql .= ",      V.AITEBUSYO\r\n";
        $strSql .= ",      A_B.BUSYO_NM A_BUSYONM\r\n";
        $strSql .= ",      V.DENPYONO\r\n";
        $strSql .= ",      NVL(V.KARIKIN,0) KARIKIN\r\n";
        $strSql .= ",      NVL(V.KASIKIN,0) KASIKIN\r\n";
        $strSql .= ",      DECODE(V.HASEI_MOTO_KB,'SW','仕訳','FR','社振','JH','給与','KA','会計','SC','基準','ZN','金利','PN','ﾍﾟﾅ',V.HASEI_MOTO_KB) HASEI_MOTO_KB\r\n";
        $strSql .= ",      V.SORTBUSYO\r\n";
        $strSql .= ",      S_B.BUSYO_NM S_BUSYONM\r\n";
        $strSql .= "FROM   (SELECT FR.KEIJO_DT KEIJYOBI\r\n";
        $strSql .= ",      FR.TAISK_KB TAISYAKU\r\n";
        $strSql .= ",      FR.BUSYO_CD BUSYOCD\r\n";
        $strSql .= ",      FR.KAMOK_CD KAMOKUCD\r\n";
        $strSql .= ",      FR.HIMOK_CD HIMOKUCD\r\n";
        $strSql .= ",      (CASE WHEN FR.TAISK_KB = '1' THEN FR.KEIJO_GK ELSE 0 END) KARIKIN\r\n";
        $strSql .= ",      (CASE WHEN FR.TAISK_KB = '2' THEN FR.KEIJO_GK ELSE 0 END) KASIKIN\r\n";
        $strSql .= ",      FR.DENPY_NO DENPYONO\r\n";
        $strSql .= ",      NULL AITEBUSYO\r\n";
        $strSql .= ",      NULL AITEKAMOKU\r\n";
        $strSql .= ",      NULL AITEHIMOKU\r\n";
        $strSql .= ",      FR.HASEI_MOTO_KB\r\n";
        $strSql .= ",      (CASE WHEN SUBSTR(FR.BUSYO_CD,1,1) IN ('3','4','5','6')\r\n";
        //20180206 Update Start
//        $strSql .= "OR SUBSTR(FR.BUSYO_CD,1,2) IN ('18','27','29','22')\r\n";
        $strSql .= "OR SUBSTR(FR.BUSYO_CD,1,2) IN ('18','24','27','29','22')\r\n";
        //20180206 Update End
        $strSql .= "THEN (SUBSTR(FR.BUSYO_CD,1,2) || '0')\r\n";
        $strSql .= "ELSE FR.BUSYO_CD END) SORTBUSYO\r\n";
        $strSql .= "FROM   HFURIKAE FR\r\n";
        $strSql .= "WHERE  SUBSTR(FR.KAMOK_CD,1,1) IN ('4','5','8')\r\n";
        $strSql .= "AND    FR.BUSYO_CD IN (SELECT BUSYO_CD\r\n";
        $strSql .= "FROM   HAUTHORITY_CTL\r\n";
        $strSql .= "WHERE  HAUTH_ID = '@AUTHID'\r\n";
        $strSql .= "AND    SYS_KB = '11'\r\n";
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND  BUSYO_CD >= '@BUSYO_F'\r\n";
        }
        if ($data['txtBusyoCDTo'] != "") {
            $strSql .= "AND    BUSYO_CD <= '@BUSYO_T'\r\n";
        }

        $strSql .= "AND    SYAIN_NO = '@USERID')\r\n";
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    FR.BUSYO_CD >= '@BUSYO_F'\r\n";
        }
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    FR.BUSYO_CD <= '@BUSYO_T'\r\n";
        }

        if ($data['txtKamokuCDFrom'] != "") {
            $strSql .= "AND    FR.KAMOK_CD >= '@KAMOK_F'\r\n";
        }
        if ($data['txtKamokuCDFrom'] != "") {
            $strSql .= "AND    FR.KAMOK_CD <= '@KAMOK_T'\r\n";
        }

        $strSql .= "AND    FR.KEIJO_DT LIKE '@KEIJYO%'\r\n";
        $strSql .= "UNION ALL\r\n";
        $strSql .= "SELECT KRI.KEIJO_DT\r\n";
        $strSql .= ",      '1'\r\n";
        $strSql .= ",      KRI.L_BUSYO_CD\r\n";
        $strSql .= ",      KRI.L_KAMOK_CD\r\n";
        $strSql .= ",      KRI.L_HIMOK_CD\r\n";
        $strSql .= ",      KRI.KEIJO_GK\r\n";
        $strSql .= ",      0\r\n";
        $strSql .= ",      KRI.DENPY_NO\r\n";
        $strSql .= ",      KRI.R_BUSYO_CD\r\n";
        $strSql .= ",      KRI.R_KAMOK_CD\r\n";
        $strSql .= ",      KRI.R_HIMOK_CD\r\n";
        $strSql .= ",      KRI.HASEI_MOTO_KB\r\n";
        $strSql .= ",      (CASE WHEN SUBSTR(KRI.L_BUSYO_CD,1,1) IN ('3','4','5','6')\r\n";
        //20180206 Update Start
//        $strSql .= "OR SUBSTR(KRI.L_BUSYO_CD,1,2) IN ('18','27','29','22')\r\n";
        $strSql .= "OR SUBSTR(KRI.L_BUSYO_CD,1,2) IN ('18','24','27','29','22')\r\n";
        //20180206 Update End
        $strSql .= "THEN (SUBSTR(KRI.L_BUSYO_CD,1,2) || '0')\r\n";
        $strSql .= "ELSE KRI.L_BUSYO_CD END) SORTBUSYO\r\n";
        $strSql .= "FROM   HKAIKEI KRI\r\n";
        $strSql .= "WHERE  SUBSTR(KRI.L_KAMOK_CD,1,1) IN ('4','5','8')\r\n";
        $strSql .= "AND    KRI.L_BUSYO_CD IN (SELECT BUSYO_CD\r\n";
        $strSql .= "FROM   HAUTHORITY_CTL\r\n";
        $strSql .= "WHERE  HAUTH_ID = '@AUTHID'\r\n";
        $strSql .= "AND    SYS_KB = '11'\r\n";
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    BUSYO_CD >= '@BUSYO_F'\r\n";
        }
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    BUSYO_CD <= '@BUSYO_T'\r\n";
        }

        $strSql .= "AND    SYAIN_NO = '@USERID')\r\n";
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    KRI.L_BUSYO_CD >= '@BUSYO_F'\r\n";
        }
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    KRI.L_BUSYO_CD <= '@BUSYO_T'\r\n";
        }
        if ($data['txtKamokuCDFrom'] != "") {
            $strSql .= "AND    KRI.L_KAMOK_CD >= '@KAMOK_F'\r\n";
        }
        if ($data['txtKamokuCDFrom'] != "") {
            $strSql .= "AND    KRI.L_KAMOK_CD <= '@KAMOK_T'\r\n";
        }
        $strSql .= "AND    KRI.KEIJO_DT LIKE '@KEIJYO%'\r\n";
        $strSql .= "UNION ALL\r\n";
        $strSql .= "SELECT KAS.KEIJO_DT\r\n";
        $strSql .= ",      '2'\r\n";
        $strSql .= ",      KAS.R_BUSYO_CD\r\n";
        $strSql .= ",      KAS.R_KAMOK_CD\r\n";
        $strSql .= ",      KAS.R_HIMOK_CD\r\n";
        $strSql .= ",      0\r\n";
        $strSql .= ",      KAS.KEIJO_GK\r\n";
        $strSql .= ",      KAS.DENPY_NO\r\n";
        $strSql .= ",      KAS.L_BUSYO_CD\r\n";
        $strSql .= ",      KAS.L_KAMOK_CD\r\n";
        $strSql .= ",      KAS.L_HIMOK_CD\r\n";
        $strSql .= ",      KAS.HASEI_MOTO_KB\r\n";
        $strSql .= ",      (CASE WHEN SUBSTR(KAS.R_BUSYO_CD,1,1) IN ('3','4','5','6')\r\n";
        //20180206 Update Start
//        $strSql .= "OR SUBSTR(KAS.R_BUSYO_CD,1,2) IN ('18','27','29','22')\r\n";
        $strSql .= "OR SUBSTR(KAS.R_BUSYO_CD,1,2) IN ('18','24','27','29','22')\r\n";
        //20180206 Update End
        $strSql .= "THEN (SUBSTR(KAS.R_BUSYO_CD,1,2) || '0')\r\n";
        $strSql .= "ELSE KAS.R_BUSYO_CD END) SORTBUSYO\r\n";
        $strSql .= "FROM   HKAIKEI KAS\r\n";
        $strSql .= "WHERE  SUBSTR(KAS.R_KAMOK_CD,1,1) IN ('4','5','8')\r\n";
        $strSql .= "AND    KAS.R_BUSYO_CD IN (SELECT BUSYO_CD\r\n";
        $strSql .= "FROM   HAUTHORITY_CTL\r\n";
        $strSql .= "WHERE  HAUTH_ID = '@AUTHID'\r\n";
        $strSql .= " AND    SYS_KB = '11'\r\n";
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    BUSYO_CD >= '@BUSYO_F'\r\n";
        }
        if ($data['txtBusyoCDTo'] != "") {
            $strSql .= "AND    BUSYO_CD <= '@BUSYO_T'\r\n";
        }

        $strSql .= " AND    SYAIN_NO = '@USERID')\r\n";
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= "AND    KAS.R_BUSYO_CD >= '@BUSYO_F'\r\n";
        }
        if ($data['txtBusyoCDFrom'] != "") {
            $strSql .= " AND    KAS.R_BUSYO_CD <= '@BUSYO_T'\r\n";
        }
        if ($data['txtKamokuCDFrom'] != "") {
            $strSql .= "AND    KAS.R_KAMOK_CD >= '@KAMOK_F'\r\n";
        }
        if ($data['txtKamokuCDFrom'] != "") {
            $strSql .= "AND    KAS.R_KAMOK_CD <= '@KAMOK_T'\r\n";
        }
        $strSql .= "AND    KAS.KEIJO_DT LIKE '@KEIJYO%') V\r\n";
        $strSql .= "LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, KAMOK_NM,(KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') KH\r\n";
        $strSql .= "ON        KH.KAMOK_CD = V.KAMOKUCD\r\n";
        $strSql .= "AND       KH.KOMOK_CD = V.HIMOKUCD\r\n";
        $strSql .= "LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI\r\n";
        $strSql .= "FROM M_KAMOKU A\r\n";
        $strSql .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)\r\n";
        $strSql .= " ) KMK\r\n";
        $strSql .= "ON        KMK.KAMOK_CD = V.KAMOKUCD\r\n";
        $strSql .= "LEFT JOIN HBUSYO BUS\r\n";
        $strSql .= "ON      BUS.BUSYO_CD = V.BUSYOCD\r\n";
        $strSql .= "LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, (KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') A_KH\r\n";
        $strSql .= "ON        A_KH.KAMOK_CD = V.AITEKAMOKU\r\n";
        $strSql .= "AND       A_KH.KOMOK_CD = V.AITEHIMOKU\r\n";
        $strSql .= "LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI\r\n";
        $strSql .= "FROM M_KAMOKU A\r\n";
        $strSql .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)\r\n";
        $strSql .= ") A_K\r\n";
        $strSql .= "ON        A_K.KAMOK_CD = V.AITEKAMOKU\r\n";
        $strSql .= "LEFT JOIN HBUSYO A_B\r\n";
        $strSql .= "ON      A_B.BUSYO_CD = V.AITEBUSYO\r\n";
        $strSql .= "LEFT JOIN HBUSYO S_B\r\n";
        $strSql .= "ON      S_B.BUSYO_CD = V.SORTBUSYO\r\n";
        $strSql .= "ORDER BY V.SORTBUSYO, V.KAMOKUCD, V.HIMOKUCD, V.KEIJYOBI,V.DENPYONO\r\n";

        $strSql = str_replace("@KEIJYO", str_replace("/", "", $data['cboYM']), $strSql);
        $strSql = str_replace("@BUSYO_F", rtrim($data['txtBusyoCDFrom']), $strSql);
        $strSql = str_replace("@BUSYO_T", rtrim($data['txtBusyoCDTo']), $strSql);
        $strSql = str_replace("@KAMOK_F", rtrim($data['txtKamokuCDFrom']), $strSql);
        $strSql = str_replace("@KAMOK_T", rtrim($data['txtKamokuCDTo']), $strSql);
        //'2007/09/07 INS Start
        $strSql = str_replace("@AUTHID", str_replace("cmd", "", $data['cmdButton']), $strSql);
        $strSql = str_replace("@USERID", $this->GS_LOGINUSER['strUserID'], $strSql);
        //        $this->log($strSql);
        return $strSql;
    }

    public function fncGetLoginUserBusyonCD_sql($data)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT BUSYO_CD \r\n";
        $sqlstr .= " FROM  HHAIZOKU \r\n";
        $sqlstr .= " WHERE SYAIN_NO='" . $this->GS_LOGINUSER['strUserID'] . "'\r\n";
        $sqlstr .= " AND START_DATE <='" . $data . "'\r\n";
        $sqlstr .= " AND (END_DATE>= '" . $data . "' OR END_DATE is null)";
        return $sqlstr;
    }

}

