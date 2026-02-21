<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20251222         機能追加要望                    2026年1月から、法人営業が２Gr体制となります      YIN
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use App\Model\HMTVE\Component\ClsComFncHMTVE;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE050TotalS extends ClsComDb
{
    public $ClsComFncHMTVE;
    public function getCreateSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SUBSTR(START_DATE,1,4) || '/' || SUBSTR(START_DATE,5,2)" . "\r\n";
        $strSQL .= "|| '/' || SUBSTR(START_DATE,7,2) FROM_DATE" . "\r\n";
        $strSQL .= ",      SUBSTR(END_DATE,1,4) || '/' || SUBSTR(END_DATE,5,2)" . "\r\n";
        $strSQL .= "|| '/' || SUBSTR(END_DATE,7,2) TO_DATE" . "\r\n";
        $strSQL .= ",      END_DATE" . "\r\n";
        $strSQL .= ",      IVENT_NM" . "\r\n";
        $strSQL .= ",      'DATE:' || TO_CHAR(SYSDATE,'YYYY/MM/DD') SYS" . "\r\n";
        $strSQL .= ",      (SELECT SUM(S_D.SEIYAKU_DAISU) GK FROM HDTSOKUHOUDATA S_D" . "\r\n";
        $strSQL .= "INNER JOIN HDTSYASYU G_S" . "\r\n";
        $strSQL .= "ON         G_S.SYASYU_CD = S_D.SYASYU_CD" . "\r\n";
        $strSQL .= "WHERE G_S.SYASYU_KB = '1'" . "\r\n";
        $strSQL .= "AND   S_D.START_DATE = '@STARTDT') GK" . "\r\n";
        $strSQL .= "FROM   HDTIVENTDATA" . "\r\n";
        $strSQL .= "WHERE  START_DATE = '@STARTDT'" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        return $strSQL;
    }

    public function getCreateSql2($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT VW.CD" . "\r\n";
        $strSQL .= ",      VW.RYAKUSYOU" . "\r\n";
        $strSQL .= ",      SUM(VW.DAISU_GK) AS DAISU" . "\r\n";
        $strSQL .= "FROM(" . "\r\n";
        $strSQL .= "SELECT (CASE WHEN SYA.SYASYU_KB = '0' THEN '9999'" . "\r\n";
        $strSQL .= "WHEN SYA.SYASYU_KB = '1' THEN SYA.SYASYU_CD END) CD" . "\r\n";
        $strSQL .= ",      (CASE WHEN SYA.SYASYU_KB = '0' THEN '軽自動車' " . "\r\n";
        $strSQL .= "WHEN SYA.SYASYU_KB = '1' THEN SYA.SYASYU_RYKNM END) RYAKUSYOU" . "\r\n";
        $strSQL .= ",      (CASE WHEN SYA.SYASYU_KB = '0' THEN 99" . "\r\n";
        $strSQL .= "WHEN SYA.SYASYU_KB = '1' THEN SYA.DISP_NO END) JUNI" . "\r\n";
        $strSQL .= ",      NVL(SOKU.SEIYAKU_DAISU,0) DAISU_GK" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYA" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";

        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= ",      HDT_TENPO_CD V_TENPO " . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "LEFT JOIN HDTSOKUHOUDATA SOKU" . "\r\n";
        $strSQL .= "ON     SOKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    SOKU.SYASYU_CD = SYA.SYASYU_CD" . "\r\n";
        $strSQL .= "AND    SOKU.START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "WHERE  NVL(SYA.SOKU_SEIYAKU_OUT_FLG,'0') = '1'" . "\r\n";
        if (str_replace("/", "", $postData["lblExhibitTerm"]) >= "20141001") {
            $strSQL .= " AND MST.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["lblExhibitTerm"]), 'MST');

        $strSQL .= ") VW" . "\r\n";
        $strSQL .= "GROUP BY VW.CD" . "\r\n";
        $strSQL .= ",        VW.RYAKUSYOU" . "\r\n";
        $strSQL .= ",        VW.JUNI" . "\r\n";
        $strSQL .= "ORDER BY VW.JUNI" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        return $strSQL;
    }

    public function getDateSql($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT START_DATE " . "\r\n";
        $strSQL .= "  FROM   HDTSOKUHOUDATA SOKU" . "\r\n";
        $strSQL .= "WHERE  SOKU.IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        return $strSQL;
    }

    public function getCarTypeDataSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT   (ROWNUM - 1) AS NUM," . "\r\n";
        $strSQL .= "          A.SYASYU_RYKNM," . "\r\n";
        $strSQL .= "           A .SYASYU_CD" . "\r\n";
        $strSQL .= "FROM (" . "\r\n";
        $strSQL .= "SELECT SYA.SYASYU_RYKNM," . "\r\n";
        $strSQL .= "SYA.SYASYU_CD" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYA" . "\r\n";
        $strSQL .= "ORDER BY SYA.DISP_NO" . "\r\n";
        $strSQL .= " ,       SYA.SYASYU_CD" . "\r\n";
        $strSQL .= "         ) A" . "\r\n";
        return $strSQL;
    }

    public function getShopDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",      (CASE WHEN MAX(SOKU.BUSYO_CD) IS NOT NULL" . "\r\n";
        $strSQL .= "             THEN '済'" . "\r\n";
        $strSQL .= "             ELSE (CASE WHEN SUM(NINZU.KAZU) = SUM(WKNIN.KAZU) THEN '済' ELSE '未' END) END) CHK_FLG" . "\r\n";
        $strSQL .= ",      SUM(NVL(SOKU.SEIYAKU_DAISU,0)) DAISU_GK" . "\r\n";
        $strSQL .= "FROM   HBUSYO MST" . "\r\n";
        $strSQL .= "INNER JOIN (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= ",      HDT_TENPO_CD V_TENPO" . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        if (str_replace("/", "", $postData["ddlExhibitDay"]) >= "20141001") {
            $strSQL .= " AND MST.BUSYO_CD NOT IN ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["ddlExhibitDay"]), 'MST');

        $strSQL .= "LEFT JOIN HDTSOKUHOUDATA SOKU" . "\r\n";
        $strSQL .= "ON     SOKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        if (str_replace("/", "", $postData["ddlExhibitDay"]) >= "20141001") {
            $strSQL .= " AND SOKU.BUSYO_CD NOT IN ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["ddlExhibitDay"]), 'SOKU');

        $strSQL = str_replace("SOKU.BUSYO_RYKNM", "MST.BUSYO_RYKNM", $strSQL);

        $strSQL .= "AND    SOKU.IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL .= "AND    EXISTS (SELECT SYU.SYASYU_CD FROM HDTSYASYU SYU WHERE SOKU.SYASYU_CD = SYU.SYASYU_CD)" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT HAI.BUSYO_CD, COUNT(HAI.BUSYO_CD) KAZU" . "\r\n";
        $strSQL .= "           FROM   HHAIZOKU HAI" . "\r\n";
        $strSQL .= "           INNER JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "           ON     SYA.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "           INNER JOIN HBUSYO TENPO" . "\r\n";
        $strSQL .= "           ON     TENPO.BUSYO_CD = HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "           WHERE  HAI.START_DATE <= '@IVENTDT'" . "\r\n";
        $strSQL .= "           AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT'" . "\r\n";
        $strSQL .= "           AND    (NVL(HAI.SYOKUSYU_KB,' ') <> '9' " . "\r\n";
        $strSQL .= "           AND     NVL(SYA.TAISYOKU_DATE,'99999999') > '@IVENTDT')" . "\r\n";
        if (str_replace("/", "", $postData["ddlExhibitDay"]) >= "20141001") {
            $strSQL .= " AND HAI.BUSYO_CD NOT IN ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["ddlExhibitDay"]), 'HAI');

        $strSQL = str_replace("HAI.BUSYO_RYKNM", "TENPO.BUSYO_RYKNM", $strSQL);

        $strSQL .= "           GROUP BY HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "           ) NINZU" . "\r\n";
        $strSQL .= "ON     NINZU.BUSYO_CD = BUS.BUSYO_CD " . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT HAI.BUSYO_CD, COUNT(HAI.SYAIN_NO) KAZU" . "\r\n";
        $strSQL .= "           FROM   HSYAINMST SYA" . "\r\n";
        $strSQL .= "           INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "           ON     SYA.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "           AND    HAI.START_DATE <= '@IVENTDT'" . "\r\n";
        $strSQL .= "           AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT'" . "\r\n";
        $strSQL .= "           INNER JOIN HBUSYO HBUS" . "\r\n";
        $strSQL .= "           ON     HBUS.BUSYO_CD = HAI.BUSYO_CD" . "\r\n";

        $strSQL .= "		   LEFT  JOIN HDTWORKMANAGE WK" . "\r\n";
        $strSQL .= "           ON     WK.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "           AND    WK.IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL .= "           WHERE  (NVL(HAI.SYOKUSYU_KB,' ') <> '9' " . "\r\n";
        $strSQL .= "           AND     NVL(SYA.TAISYOKU_DATE,'99999999') > '@IVENTDT')" . "\r\n";
        $strSQL .= "           AND    ((HAI.IVENT_TARGET_FLG = '1' AND WK.WORK_STATE = '2')" . "\r\n";
        $strSQL .= "                   OR" . "\r\n";
        $strSQL .= "                   (HAI.IVENT_TARGET_FLG = '0' AND (WK.SYAIN_NO IS NULL OR WK.WORK_STATE = '2')))" . "\r\n";
        if (str_replace("/", "", $postData["ddlExhibitDay"]) >= "20141001") {
            $strSQL .= " AND HAI.BUSYO_CD NOT IN ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["ddlExhibitDay"]), 'HAI');

        $strSQL = str_replace("HAI.BUSYO_RYKNM", "HBUS.BUSYO_RYKNM", $strSQL);

        $strSQL .= "           GROUP BY HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "          ) WKNIN" . "\r\n";
        $strSQL .= "ON     WKNIN.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE  MST.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "GROUP BY MST.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",        MST.IVENT_TENPO_DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY MST.IVENT_TENPO_DISP_NO" . "\r\n";
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["ddlExhibitDay"]), $strSQL);
        return $strSQL;
    }

    public function getDetailDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT MST.BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYA.SYASYU_CD" . "\r\n";
        $strSQL .= ",      SUM(NVL(SOKU.SEIYAKU_DAISU,0)) DAISU_GK" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYA" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";

        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= " ,      HDT_TENPO_CD V_TENPO" . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "LEFT JOIN HDTSOKUHOUDATA SOKU" . "\r\n";
        $strSQL .= "ON     SOKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    SOKU.SYASYU_CD = SYA.SYASYU_CD" . "\r\n";
        $strSQL .= "AND    SOKU.IVENT_DATE = '@IVENTDT'" . "\r\n";
        if (str_replace("/", "", $postData["ddlExhibitDay"]) >= "20141001") {
            $strSQL .= " WHERE MST.BUSYO_CD NOT IN ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["ddlExhibitDay"]), 'MST');

        $strSQL .= "GROUP BY MST.BUSYO_CD" . "\r\n";
        $strSQL .= ",      MST.IVENT_TENPO_DISP_NO" . "\r\n";
        $strSQL .= ",      SYA.SYASYU_CD" . "\r\n";
        $strSQL .= ",      SYA.DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY MST.IVENT_TENPO_DISP_NO" . "\r\n";
        $strSQL .= ",      SYA.DISP_NO" . "\r\n";
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["ddlExhibitDay"]), $strSQL);
        return $strSQL;
    }

    public function setAllSumSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SUM(NVL(SOKU.SEIYAKU_DAISU,0)) DAISU_GK" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYA" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= ",      HDT_TENPO_CD V_TENPO" . "\r\n";
        $strSQL .= " FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "LEFT JOIN HDTSOKUHOUDATA SOKU" . "\r\n";
        $strSQL .= "ON     SOKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    SOKU.SYASYU_CD = SYA.SYASYU_CD" . "\r\n";
        $strSQL .= "AND    SOKU.IVENT_DATE = '@IVENTDT'" . "\r\n";
        if (str_replace("/", "", $postData["ddlExhibitDay"]) >= "20141001") {
            $strSQL .= " WHERE MST.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["ddlExhibitDay"]), 'MST');

        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["ddlExhibitDay"]), $strSQL);
        return $strSQL;
    }

    public function getSumDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.SYASYU_CD" . "\r\n";
        $strSQL .= ",      SUM(NVL(SOKU.SEIYAKU_DAISU,0)) DAISU_GK" . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYA" . "\r\n";
        $strSQL .= "INNER JOIN HBUSYO MST" . "\r\n";
        $strSQL .= "ON     MST.IVENT_TENPO_DISP_NO IS NOT NULL" . "\r\n";
        $strSQL .= "INNER JOIN  (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "  ,      HDT_TENPO_CD V_TENPO " . "\r\n";
        $strSQL .= "FROM HBUSYO) BUS" . "\r\n";
        $strSQL .= "ON     MST.BUSYO_CD = BUS.V_TENPO" . "\r\n";
        $strSQL .= "LEFT JOIN HDTSOKUHOUDATA SOKU" . "\r\n";
        $strSQL .= "ON     SOKU.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "AND    SOKU.SYASYU_CD = SYA.SYASYU_CD" . "\r\n";
        $strSQL .= "AND    SOKU.IVENT_DATE = '@IVENTDT'" . "\r\n";
        if (str_replace("/", "", $postData["ddlExhibitDay"]) >= "20141001") {
            $strSQL .= " WHERE MST.BUSYO_CD not in ('443','463') " . "\r\n";
        }

        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = $this->ClsComFncHMTVE->appendBusyoCondition($strSQL, str_replace("/", "", $postData["ddlExhibitDay"]), 'MST');

        $strSQL .= "GROUP BY SYA.SYASYU_CD" . "\r\n";
        $strSQL .= ",        SYA.DISP_NO" . "\r\n";
        $strSQL .= "ORDER BY SYA.DISP_NO" . "\r\n";
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["ddlExhibitDay"]), $strSQL);
        return $strSQL;
    }

    public function getStartDateSql($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT START_DATE " . "\r\n";
        $strSQL .= "  FROM   HDTSOKUHOUKAKUTEI" . "\r\n";
        $strSQL .= "WHERE IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["IVENTDT"]), $strSQL);
        return $strSQL;
    }

    public function fncInsertSql($postData)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDTSOKUHOUKAKUTEI" . "\r\n";
        $strSQL .= "(      START_DATE" . "\r\n";
        $strSQL .= ",      IVENT_DATE" . "\r\n";
        $strSQL .= ",      KAKUTEI_FLG" . "\r\n";
        $strSQL .= ",      UPD_DATE" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES ('@STARTDT'" . "\r\n";
        $strSQL .= ",       '@IVENTDT'" . "\r\n";
        $strSQL .= ",       '1'" . "\r\n";
        $strSQL .= ",       SYSDATE" . "\r\n";
        $strSQL .= ",       SYSDATE" . "\r\n";
        $strSQL .= ",       '@LOGINID'" . "\r\n";
        $strSQL .= ",       'Total_S'" . "\r\n";
        $strSQL .= ",       '@MACHINENM'" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["IVENTDT"]), $strSQL);
        $strSQL = str_replace("@LOGINID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MACHINENM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return $strSQL;
    }

    public function fncUpdateSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSOKUHOUKAKUTEI" . "\r\n";
        $strSQL .= "SET    KAKUTEI_FLG = '1'" . "\r\n";
        $strSQL .= "WHERE  START_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["IVENTDT"]), $strSQL);
        return $strSQL;
    }
    public function fncDatabackSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSOKUHOUKAKUTEI" . "\r\n";
        $strSQL .= "SET    KAKUTEI_FLG = '0'" . "\r\n";
        $strSQL .= "WHERE  START_DATE >= '@STARTDT'" . "\r\n";
        $strSQL .= "AND    IVENT_DATE <= '@IVENTDT'" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        $strSQL = str_replace("@IVENTDT", str_replace("/", "", $postData["ddlExhibitDay"]), $strSQL);
        return $strSQL;
    }
    public function RenewQucikReportPutoutDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSOKUHOUDATA" . "\r\n";
        $strSQL .= "SET    OUT_FLG = '1'" . "\r\n";
        $strSQL .= "WHERE  IVENT_DATE >= '@STARTDT'" . "\r\n";
        $strSQL .= "AND    IVENT_DATE <= '@ENDDT'" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        $strSQL = str_replace("@ENDDT", str_replace("/", "", $postData["ddlExhibitDay"]), $strSQL);
        return $strSQL;
    }

    public function CheckNoDataSql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(START_DATE) CNT" . "\r\n";
        $strSQL .= "FROM   HDTSOKUHOUDATA" . "\r\n";
        $strSQL .= "WHERE  OUT_FLG = '0'" . "\r\n";
        $strSQL .= "AND    IVENT_DATE >= '@STARTDT'" . "\r\n";
        $strSQL .= "AND    IVENT_DATE <= '@ENDDT'" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        $strSQL = str_replace("@ENDDT", str_replace("/", "", $postData["ddlExhibitDay"]), $strSQL);
        return $strSQL;
    }

    public function setExhibitTermDateSql()
    {
        $strSQL = "";
        $strSQL .= " SELECT START_DATE" . "\r\n";
        $strSQL .= ",END_DATE " . "\r\n";
        $strSQL .= "FROM   HDTIVENTDATA" . "\r\n";
        $strSQL .= "WHERE  BASE_FLG = '1' " . "\r\n";
        return $strSQL;
    }

    public function unLocksql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDTSOKUHOUKAKUTEI" . "\r\n";
        $strSQL .= "SET    KAKUTEI_FLG = 0" . "\r\n";
        $strSQL .= "WHERE  KAKUTEI_FLG = 1" . "\r\n";
        $strSQL .= "AND    START_DATE = '@STARTDT'" . "\r\n";
        $strSQL = str_replace("@STARTDT", str_replace("/", "", $postData["lblExhibitTerm"]), $strSQL);
        return $strSQL;
    }

    //展示会開催期間に初期値をセットする
    public function setExhibitTermDate()
    {
        $strSql = $this->setExhibitTermDateSql();
        return parent::select($strSql);
    }

    //データを取得する
    public function getDate($postData)
    {
        $strSql = $this->getDateSql($postData);
        return parent::select($strSql);
    }

    //車種データを取得する
    public function getCarTypeData()
    {
        $strSql = $this->getCarTypeDataSql();
        return parent::select($strSql);
    }

    //店舗データを取得する
    public function getShopData($postData)
    {
        $strSql = $this->getShopDataSql($postData);
        return parent::select($strSql);
    }

    //内訳テーブルの生成
    //内訳データを取得する
    public function getDetailData($postData)
    {
        $strSql = $this->getDetailDataSql($postData);
        return parent::select($strSql);
    }

    //総合計をセットする
    public function setAllSum($postData)
    {
        $strSql = $this->setAllSumSql($postData);
        return parent::select($strSql);
    }

    //総合計_内訳テーブルの生成
    //合計データを取得する
    public function getSumData($postData)
    {
        $strSql = $this->getSumDataSql($postData);
        return parent::select($strSql);
    }

    public function getStartDate($postData)
    {
        $strSql = $this->getStartDateSql($postData);
        return parent::select($strSql);
    }

    public function fncInsert($postData)
    {
        $strSql = $this->fncInsertSql($postData);
        return parent::insert($strSql);
    }

    public function fncUpdate($postData)
    {
        $strSql = $this->fncUpdateSql($postData);
        return parent::update($strSql);
    }
    public function fncDataback($postData)
    {
        $strSql = $this->fncDatabackSql($postData);
        return parent::update($strSql);
    }

    public function RenewQucikReportPutoutData($postData)
    {
        $strSql = $this->RenewQucikReportPutoutDataSql($postData);
        return parent::update($strSql);
    }

    public function getCreate($postData)
    {
        $strSql = $this->getCreateSql($postData);
        return parent::select($strSql);
    }

    public function getCreate2($postData)
    {
        $strSql = $this->getCreateSql2($postData);
        return parent::select($strSql);
    }

    public function unLock($postData)
    {
        $strSql = $this->unLocksql($postData);
        return parent::update($strSql);
    }

}
