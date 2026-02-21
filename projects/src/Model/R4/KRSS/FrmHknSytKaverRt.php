<?php
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;

class FrmHknSytKaverRt extends ClsComDb
{
    //====execute====
    public function fncHKEIRICTL()
    {
        $sqlstr = $this->fncHKEIRICTL_sql();
        return parent::select($sqlstr);
    }

    public function fncDeleteWk_HknSytKanr()
    {
        $sqlstr = $this->fncDeleteWk_HknSytKanr_sql();
        return parent::Do_Execute($sqlstr);
    }

    public function fncHknSytBusyoSyukei($postData)
    {
        $sqlstr = $this->fncHknSytBusyoSyukei_sql($postData);
        return parent::Do_Execute($sqlstr);
    }

    public function fncHknSytLineSyukei()
    {
        $sqlstr = $this->fncHknSytLineSyukei_sql();
        return parent::Do_Execute($sqlstr);
    }

    public function fncPrintSelect($postData)
    {
        $sqlstr = $this->fncPrintSelect_sql($postData);
        return parent::select($sqlstr);
    }

    //====sql====
    public function fncHKEIRICTL_sql()
    {
        $sqlstr = "";
        $sqlstr .= "SELECT ID \r\n";
        $sqlstr .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU\r\n";
        $sqlstr .= ",      KISYU_YMD KISYU\r\n";
        $sqlstr .= "FROM   HKEIRICTL\r\n";
        $sqlstr .= "WHERE  ID = '01'";
        return $sqlstr;
    }

    public function fncDeleteWk_HknSytKanr_sql()
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM WK_HKNSYTKANR";
        return $sqlstr;
    }

    public function fncHknSytBusyoSyukei_sql($postData)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_HKNSYTKANR\r\n";
        $sqlstr .= "(   KEIJO_DT\r\n";
        $sqlstr .= ",   BUSYO_CD \r\n";
        $sqlstr .= ",	LINE_NO  \r\n";
        $sqlstr .= ",	TOU_ZAN  \r\n";
        $sqlstr .= ",	TKI_ZAN\r\n";
        $sqlstr .= ",    UPD_SYA_CD\r\n";
        $sqlstr .= ",    UPD_PRG_ID\r\n";
        $sqlstr .= ",    UPD_CLT_NM\r\n";
        $sqlstr .= ")  \r\n";

        $sqlstr .= "	SELECT  '@TOUGETU'\r\n";
        $sqlstr .= "	,       V.BUSYO_CD\r\n";
        $sqlstr .= "	,       V.LINE_NO\r\n";
        $sqlstr .= "	,       SUM(V.TOUGETU)\r\n";
        $sqlstr .= "	,       SUM(V.TOUKI)\r\n";
        $sqlstr .= ",        '@UPDUSER'\r\n";
        $sqlstr .= ",        '@UPDAPP'\r\n";
        $sqlstr .= ",        '@UPDCLT'\r\n";

        $sqlstr .= "	FROM    (\r\n";
        $sqlstr .= "			--当月集計\r\n";
        $sqlstr .= "	        SELECT  DECODE(BUS.CNV_BUSYO_CD,NULL,TOU.BUSYO_CD,BUS.CNV_BUSYO_CD) BUSYO_CD\r\n";
        $sqlstr .= "			,      LINE.LINE_NO\r\n";
        $sqlstr .= "			,      SUM(CASE WHEN TOU.KEIJO_DT = '@TOUGETU' THEN NVL(TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1) ELSE 0 END) TOUGETU\r\n";
        $sqlstr .= "			,      SUM(NVL(TOU.TOU_ZAN,0)* NVL(KLINE.CAL_KB,1)) TOUKI\r\n";
        $sqlstr .= "		   	FROM   HKANRIZ TOU\r\n";
        $sqlstr .= "	        INNER JOIN\r\n";
        $sqlstr .= "			       HBUSYO BUS\r\n";
        $sqlstr .= "			ON     BUS.BUSYO_CD = TOU.BUSYO_CD\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        //$sqlstr .= "			       HHKNSYTKMKLMST KLINE\r\n";
        $sqlstr .= "			       HHKNSYTKMKLMST_NEW KLINE\r\n";
        $sqlstr .= "			ON     KLINE.KAMOK_CD = TOU.KAMOKU_CD\r\n";
        $sqlstr .= "	        AND    (KLINE.HIMOK_CD = NVL(TRIM(TOU.HIMOKU_CD),'00')\r\n";
        $sqlstr .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(TOU.HIMOKU_CD,1,1),TOU.HIMOKU_CD,1,1)))\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        //$sqlstr .= "			       HLINEMST LINE\r\n";
        $sqlstr .= "			       HLINEMST_KEIEISEIKA LINE\r\n";
        $sqlstr .= "			ON     LINE.LINE_NO = KLINE.LINE_NO\r\n";
        $sqlstr .= "			WHERE  TOU.KEIJO_DT >= '@KISYU'\r\n";
        $sqlstr .= "			AND    TOU.KEIJO_DT <= '@TOUGETU'\r\n";
        $sqlstr .= "			GROUP BY  DECODE(BUS.CNV_BUSYO_CD,NULL,TOU.BUSYO_CD,BUS.CNV_BUSYO_CD), LINE.LINE_NO\r\n";
        $sqlstr .= "			--当月部署別集計\r\n";
        $sqlstr .= "			UNION ALL\r\n";
        $sqlstr .= "			SELECT SBUS.TOTAL_BUSYO_CD\r\n";
        $sqlstr .= "			,      LINE.LINE_NO\r\n";
        $sqlstr .= "	     	,      SUM(CASE WHEN B_TOU.KEIJO_DT = '@TOUGETU' THEN NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1) ELSE 0 END) TOUGETU\r\n";
        $sqlstr .= "			,      SUM(NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUKI\r\n";
        $sqlstr .= "		   	FROM   HKANRIZ B_TOU\r\n";
        $sqlstr .= "	        INNER JOIN\r\n";
        $sqlstr .= "			       HBUSYO BUS\r\n";
        $sqlstr .= "			ON     BUS.BUSYO_CD = B_TOU.BUSYO_CD\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        $sqlstr .= "			       HTTLBUSYO SBUS\r\n";
        $sqlstr .= "			ON     SBUS.BUSYO_CD = BUS.BUSYO_CD\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        //$sqlstr .= "			       HHKNSYTKMKLMST KLINE\r\n";
        $sqlstr .= "			       HHKNSYTKMKLMST_NEW KLINE\r\n";
        $sqlstr .= "			ON     KLINE.KAMOK_CD = B_TOU.KAMOKU_CD\r\n";
        $sqlstr .= "	        AND    (KLINE.HIMOK_CD = NVL(TRIM(B_TOU.HIMOKU_CD),'00')\r\n";
        $sqlstr .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(B_TOU.HIMOKU_CD,1,1),B_TOU.HIMOKU_CD,1,1)))\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        //$sqlstr .= "			       HLINEMST LINE\r\n";
        $sqlstr .= "			       HLINEMST_KEIEISEIKA LINE\r\n";
        $sqlstr .= "			ON     LINE.LINE_NO = KLINE.LINE_NO\r\n";
        $sqlstr .= "			WHERE  B_TOU.KEIJO_DT >= '@KISYU'\r\n";
        $sqlstr .= "			AND    B_TOU.KEIJO_DT <= '@TOUGETU'\r\n";
        $sqlstr .= "			GROUP BY SBUS.TOTAL_BUSYO_CD, LINE.LINE_NO\r\n";
        $sqlstr .= "	      	--トータル集計(当月)\r\n";
        $sqlstr .= "			UNION ALL\r\n";
        $sqlstr .= "			SELECT '000'\r\n";
        $sqlstr .= "			,      LINE.LINE_NO\r\n";
        $sqlstr .= "	     	,      SUM(CASE WHEN B_TOU.KEIJO_DT = '@TOUGETU' THEN NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1) ELSE 0 END) TOUGETU\r\n";
        $sqlstr .= "			,      SUM(NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUKI\r\n";
        $sqlstr .= "		   	FROM   HKANRIZ B_TOU\r\n";
        $sqlstr .= "	        INNER JOIN\r\n";
        $sqlstr .= "			       HBUSYO BUS\r\n";
        $sqlstr .= "			ON     BUS.BUSYO_CD = B_TOU.BUSYO_CD\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        //$sqlstr .= "			       HHKNSYTKMKLMST KLINE\r\n";
        $sqlstr .= "			       HHKNSYTKMKLMST_NEW KLINE\r\n";
        $sqlstr .= "			ON     KLINE.KAMOK_CD = B_TOU.KAMOKU_CD\r\n";
        $sqlstr .= "	        AND    (KLINE.HIMOK_CD = NVL(TRIM(B_TOU.HIMOKU_CD),'00')\r\n";
        $sqlstr .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(B_TOU.HIMOKU_CD,1,1),B_TOU.HIMOKU_CD,1,1)))\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        //$sqlstr .= "			       HLINEMST LINE\r\n";
        $sqlstr .= "			       HLINEMST_KEIEISEIKA LINE\r\n";
        $sqlstr .= "			ON     LINE.LINE_NO = KLINE.LINE_NO\r\n";
        $sqlstr .= "			WHERE  B_TOU.KEIJO_DT >= '@KISYU'\r\n";
        $sqlstr .= "			AND    B_TOU.KEIJO_DT <= '@TOUGETU'\r\n";
        $sqlstr .= "			GROUP BY LINE.LINE_NO\r\n";
        $sqlstr .= "	) V\r\n";
        $sqlstr .= "	\r\n";
        $sqlstr .= "	GROUP BY V.BUSYO_CD, V.LINE_NO\r\n";
        $sqlstr = str_replace("@TOUGETU", str_replace("/", "", $postData['cboYMTo']), $sqlstr);
        $sqlstr = str_replace("@KISYU", str_replace("/", "", $postData['cboYM']), $sqlstr);
        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "HknSytKaverRt", $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $sqlstr);

        return $sqlstr;
    }

    public function fncHknSytLineSyukei_sql()
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO WK_HKNSYTKANR\r\n";
        $sqlstr .= "(   KEIJO_DT\r\n";
        $sqlstr .= ",   BUSYO_CD \r\n";
        $sqlstr .= ",	LINE_NO  \r\n";
        $sqlstr .= ",	TOU_ZAN  \r\n";
        $sqlstr .= ",	TKI_ZAN  \r\n";
        $sqlstr .= ",    UPD_SYA_CD\r\n";
        $sqlstr .= ",    UPD_PRG_ID\r\n";
        $sqlstr .= ",    UPD_CLT_NM\r\n";

        $sqlstr .= ")\r\n";
        $sqlstr .= "SELECT  SYUKEI.KEIJO_DT\r\n";
        $sqlstr .= ",       SYUKEI.BUSYO_CD\r\n";
        $sqlstr .= ",       SYUKEI.TOTAL_LINE_NO\r\n";
        $sqlstr .= ",       SYUKEI.TOUGETU\r\n";
        $sqlstr .= ",       SYUKEI.TOUKI\r\n";
        $sqlstr .= ",       '@UPDUSER'\r\n";
        $sqlstr .= ",       '@UPDAPP'\r\n";
        $sqlstr .= ",       '@UPDCLT'\r\n";

        $sqlstr .= "FROM    (\r\n";
        $sqlstr .= "		SELECT W_KR.KEIJO_DT\r\n";
        $sqlstr .= "        ,      W_KR.BUSYO_CD\r\n";
        $sqlstr .= "		,      S_LINE.TOTAL_LINE_NO\r\n";
        $sqlstr .= "		,      SUM(W_KR.TOU_ZAN * NVL(S_LINE.CAL_KB,1)) TOUGETU\r\n";
        $sqlstr .= "		,      SUM(W_KR.TKI_ZAN * NVL(S_LINE.CAL_KB,1)) TOUKI\r\n";
        $sqlstr .= "      	FROM   WK_HKNSYTKANR W_KR\r\n";
        $sqlstr .= "       	INNER JOIN\r\n";
        //$sqlstr .= "               HTTLLINEMST S_LINE\r\n";
        $sqlstr .= "               HTTLLINEMST_KEIEISEIKA S_LINE\r\n";
        $sqlstr .= "        ON     S_LINE.LINE_NO = W_KR.LINE_NO\r\n";
        $sqlstr .= "        GROUP BY W_KR.KEIJO_DT, W_KR.BUSYO_CD, S_LINE.TOTAL_LINE_NO) SYUKEI\r\n";

        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "HknSytKaverRt", $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $sqlstr);
        return $sqlstr;
    }

    public function fncPrintSelect_sql($postData)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT TBL.NEN\r\n";
        $sqlstr .= ",      TBL.TUKI\r\n";
        $sqlstr .= ",      TBL.BUSYO_CD\r\n";
        $sqlstr .= ",      TBL.BUSYO_NM\r\n";
        $sqlstr .= ",      (CASE WHEN TBL.BUSYO_CD = '000' THEN NULL ELSE TBL.TOU_JUNI END) TOU_JUNI\r\n";
        $sqlstr .= ",      TBL.TOU_HKNSYT\r\n";
        $sqlstr .= ",      TBL.TOU_KOTEI\r\n";
        $sqlstr .= ",      TBL.TOU_KAVER_RT\r\n";
        $sqlstr .= ",      TBL.TKI_HKNSYT\r\n";
        $sqlstr .= ",      TBL.TKI_KOTEI\r\n";
        $sqlstr .= ",      TBL.TKI_KAVER_RT\r\n";
        $sqlstr .= ",      (CASE WHEN TBL.BUSYO_CD = '000' THEN NULL ELSE TBL.TKI_JUNI END) TKI_JUNI\r\n";
        $sqlstr .= "FROM   (\r\n";
        $sqlstr .= "		SELECT V.NEN\r\n";
        $sqlstr .= "		,      V.TUKI\r\n";
        $sqlstr .= "		,      V.BUSYO_CD\r\n";
        $sqlstr .= "		,      V.BUSYO_NM\r\n";
        $sqlstr .= "		,      ROW_NUMBER() OVER(ORDER BY (CASE WHEN V.BUSYO_CD = '000' THEN 1 ELSE 0 END), V.TOU_KAVER_RT DESC, V.BUSYO_CD) TOU_JUNI\r\n";
        $sqlstr .= "		,      V.TOU_HKNSYT\r\n";
        $sqlstr .= "		,      V.TOU_KOTEI\r\n";
        $sqlstr .= "		,      V.TOU_KAVER_RT\r\n";
        $sqlstr .= "		,      V.TKI_HKNSYT\r\n";
        $sqlstr .= "		,      V.TKI_KOTEI\r\n";
        $sqlstr .= "		,      V.TKI_KAVER_RT\r\n";
        $sqlstr .= "		,      ROW_NUMBER() OVER(ORDER BY (CASE WHEN V.BUSYO_CD = '000' THEN 1 ELSE 0 END), V.TKI_KAVER_RT DESC, V.BUSYO_CD) TKI_JUNI\r\n";
        $sqlstr .= "		FROM\r\n";
        $sqlstr .= "			(SELECT \r\n";
        //20160915 Upd Start 和暦変換
        //$sqlstr .= "			       SUBSTR(JPDATE('@TOUGETU'),2,2) NEN\r\n";
        //$sqlstr .= "			,      SUBSTR(JPDATE('@TOUGETU'),4,2) TUKI\r\n";
        $sqlstr .= "			       SUBSTR( '@TOUGETU' ,0,4) NEN\r\n";
        $sqlstr .= "			,      SUBSTR( '@TOUGETU' ,5,2) TUKI\r\n";
        //20160915 Upd End 和暦変換

        $sqlstr .= "			,      WK.BUSYO_CD\r\n";
        $sqlstr .= "			,      BUS.BUSYO_NM\r\n";
        $sqlstr .= "			,      WK.TOU_HKNSYT\r\n";
        $sqlstr .= "			,      WK.TOU_KOTEI\r\n";
        $sqlstr .= "			,      ROUND(DECODE(WK.TOU_KOTEI,0,0,WK.TOU_HKNSYT * 100 / WK.TOU_KOTEI),1) TOU_KAVER_RT\r\n";
        $sqlstr .= "			,      WK.TKI_HKNSYT\r\n";
        $sqlstr .= "			,      WK.TKI_KOTEI\r\n";
        $sqlstr .= "			,      ROUND(DECODE(WK.TKI_KOTEI,0,0,WK.TKI_HKNSYT * 100 / WK.TKI_KOTEI),1) TKI_KAVER_RT\r\n";
        $sqlstr .= "			FROM   (SELECT BUSYO_CD\r\n";
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 34 THEN TOU_ZAN ELSE 0 END) TOU_HKNSYT\r\n";
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 72 THEN TOU_ZAN ELSE 0 END) TOU_KOTEI\r\n";
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 34 THEN TKI_ZAN ELSE 0 END) TKI_HKNSYT\r\n";
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 72 THEN TKI_ZAN ELSE 0 END) TKI_KOTEI\r\n";
//20180821 Upd Start
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 82 THEN TOU_ZAN ELSE 0 END) TOU_HKNSYT\r\n";
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 107  THEN TOU_ZAN ELSE 0 END) TOU_KOTEI\r\n";
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 82 THEN TKI_ZAN ELSE 0 END) TKI_HKNSYT\r\n";
        //$sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 107 THEN TKI_ZAN ELSE 0 END) TKI_KOTEI\r\n";
        $sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 84 THEN TOU_ZAN ELSE 0 END) TOU_HKNSYT\r\n";
        $sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 109  THEN TOU_ZAN ELSE 0 END) TOU_KOTEI\r\n";
        $sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 84 THEN TKI_ZAN ELSE 0 END) TKI_HKNSYT\r\n";
        $sqlstr .= "			        ,      SUM(CASE WHEN LINE_NO = 109 THEN TKI_ZAN ELSE 0 END) TKI_KOTEI\r\n";
        //20180821 Upd End

        $sqlstr .= "			        FROM WK_HKNSYTKANR\r\n";
        //$sqlstr .= "			        WHERE LINE_NO IN (34,72)\r\n";
//20180821 Upd Start
        //$sqlstr .= "			        WHERE LINE_NO IN (82,107)\r\n";
        $sqlstr .= "			        WHERE LINE_NO IN (84,109)\r\n";
        //20180821 Upd End
        $sqlstr .= "			        GROUP BY BUSYO_CD) WK\r\n";
        $sqlstr .= "			INNER JOIN\r\n";
        $sqlstr .= "			       HBUSYO BUS\r\n";
        $sqlstr .= "			ON     BUS.BUSYO_CD = WK.BUSYO_CD\r\n";
        $sqlstr .= "			\r\n";
        //			$sqlstr .= "			WHERE   BUS.HKNSYT_DSP_KB = 'O'\r\n";
        $sqlstr .= "			WHERE   BUS.HKNSYT_DSP_KB is not null \r\n";
        $sqlstr .= "			) V\r\n";
        $sqlstr .= "		) TBL\r\n";
        $sqlstr .= "ORDER BY TBL.TOU_JUNI, TBL.BUSYO_CD\r\n";
        $sqlstr = str_replace("@TOUGETU", str_replace("/", "", $postData['cboYMTo']), $sqlstr);
        return $sqlstr;
    }
}
