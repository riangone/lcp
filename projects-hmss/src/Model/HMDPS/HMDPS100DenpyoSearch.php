<?php
// 共通クラスの読込み
namespace App\Model\HMDPS;

use App\Model\Component\ClsComDb;
use Cake\Routing\Router;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMDPS100DenpyoSearch extends ClsComDb
{
    public $SessionComponent;
    function fncInsTaisyoSyohyNOPrintsql($postData)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO WK_SYOHY_NO" . "\r\n";
        $strSQL .= "(      SYOHY_NO" . "\r\n";
        $strSQL .= ",      EDA_NO" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      KENSU" . "\r\n";
        $strSQL .= ",      KINGAKU" . "\r\n";
        $strSQL .= ",      FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",      HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= ",      PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",      CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES (" . "\r\n";
        $strSQL .= "       '@SYOHY_NO'" . "\r\n";
        $strSQL .= ",      '@EDA_NO'" . "\r\n";
        $strSQL .= ",      ' '" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= ",      'DENPYO_SEARCH_PRINT'" . "\r\n";
        $strSQL .= ",      '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL = str_replace("@SYOHY_NO", substr($postData, 0, 15), $strSQL);
        $strSQL = str_replace("@EDA_NO", substr($postData, 15, 2), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    function fncInsTaisyoSyohyNOsql($postData)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO WK_SYOHY_NO" . "\r\n";
        $strSQL .= "(      SYOHY_NO" . "\r\n";
        $strSQL .= ",      EDA_NO" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      KENSU" . "\r\n";
        $strSQL .= ",      KINGAKU" . "\r\n";
        $strSQL .= ",      FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",      HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= ",      PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",      CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      CRE_SYA_CD" . "\r\n";
        $strSQL .= ",      CRE_PRG_ID" . "\r\n";
        $strSQL .= ",      CRE_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",      SWK.EDA_NO" . "\r\n";
        $strSQL .= ",      MIN(SWK.GYO_NO) GYO_NO" . "\r\n";
        $strSQL .= ",      COUNT(SWK.SYOHY_NO) KENSU" . "\r\n";
        $strSQL .= ",      SUM(SWK.ZEIKM_GK) KINGAKU" . "\r\n";
        $strSQL .= ",      MAX(SWK.FUKANZEN_FLG) FUKANZEN" . "\r\n";
        $strSQL .= ",      MAX(SWK.HONBU_SYORIZUMI_FLG) HONBU_SYORIZUMI_FLG" . "\r\n";
        $strSQL .= ",      MAX(SWK.PRINT_OUT_FLG) PRINT" . "\r\n";
        $strSQL .= ",      MAX(SWK.CSV_OUT_FLG) CSVOUT" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      '@SYAINCD'" . "\r\n";
        $strSQL .= ",      '@PRGID'" . "\r\n";
        $strSQL .= ",      '@CLIENTNM'" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= ",      (SELECT SYOHY_NO" . "\r\n";
        $strSQL .= "		,      MAX(EDA_NO) OIBAN" . "\r\n";
        $strSQL .= "		FROM   HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "		WHERE  DEL_FLG = '0'" . "\r\n";
        if ($postData["radAll"] == "false") {
            //仕訳伝票又は支払伝票が選択されている場合
            $strSQL .= "        AND    DENPY_KB = '@DENPY_KB'" . "\r\n";
        }
        if (trim($postData["txtSyohyNO"]) !== "") {
            $strSQL .= "        AND    SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        }
        if (trim($postData["txtDateFrom"]) !== "") {
            $strSQL .= "        AND    TO_CHAR(A.CREATE_DATE,'YYYYMMDD') >= '@SAKUSEIFROM'" . "\r\n";
        }
        if (trim($postData["txtDateTo"]) !== "") {
            $strSQL .= "        AND    TO_CHAR(A.CREATE_DATE,'YYYYMMDD') <= '@SAKUSEITO'" . "\r\n";
        }
        if (trim($postData["txtBusyoCD"]) !== "") {
            $strSQL .= "        AND    A.CRE_BUSYO_CD = '@CRE_BUSYO_CD'" . "\r\n";
        }
        if (trim($postData["txtSyainNO"]) !== "") {
            $strSQL .= "        AND    A.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        }
        $strSQL .= "		GROUP BY SYOHY_NO) V" . "\r\n";
        $strSQL .= "WHERE  SWK.SYOHY_NO = V.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SWK.EDA_NO = V.OIBAN" . "\r\n";
        if (trim($postData["txtShiharaiDTFrom"]) !== "") {
            $strSQL .= "        AND    SWK.SHIHARAI_DT >= '@SHIHARAIFROM'" . "\r\n";
        }
        if (trim($postData["txtShiharaiDTEnd"]) !== "") {
            $strSQL .= "        AND    SWK.SHIHARAI_DT <= '@SHIHARAITO'" . "\r\n";
        }
        if (trim($postData["txtLKamokuCD"]) !== "") {
            $strSQL .= "        AND    SWK.L_KAMOK_CD = '@L_KAMOK_CD'" . "\r\n";
        }
        if (trim($postData["txtRKamokuCD"]) !== "") {
            $strSQL .= "        AND    SWK.R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        }
        if ($postData["txtKeyWord"] !== "") {
            $strSQL .= "        AND " . "\r\n";
            $strSQL .= "        ( " . "\r\n";

            $strSQL .= "         SWK.TEKYO LIKE '%@KEYWORD%' OR " . "\r\n";

            $strSQL .= "         SWK.L_HISSU_TEKYO1 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO2 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO3 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO4 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO5 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO6 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO7 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO8 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO9 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO10 LIKE '%@KEYWORD%' OR " . "\r\n";

            $strSQL .= "         SWK.R_HISSU_TEKYO1 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO2 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO3 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO4 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO5 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO6 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO7 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO8 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO9 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO10 LIKE '%@KEYWORD%' OR " . "\r\n";

            $strSQL .= "         SWK.SHIHARAISAKI_CD LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.SHIHARAISAKI_NM LIKE '%@KEYWORD%' " . "\r\n";
            $strSQL .= "        ) " . "\r\n";
        }
        $strSQL .= "GROUP BY" . "\r\n";
        $strSQL .= "       SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",      SWK.EDA_NO" . "\r\n";
        $strSQL = str_replace("@SYAINCD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRGID", $postData["strPrgNM"], $strSQL);
        $strSQL = str_replace("@CLIENTNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        if ($postData["radShiwake"] == "true") {
            $strSQL = str_replace("@DENPY_KB", "1", $strSQL);
        } else
            if ($postData["radShiharai"] == "true") {
                $strSQL = str_replace("@DENPY_KB", "2", $strSQL);
            }
        if (strlen(trim($postData["txtSyohyNO"])) == 17) {
            $strSQL = str_replace("@SYOHY_NO", substr(trim($postData["txtSyohyNO"]), 0, 15), $strSQL);
        } else {
            $strSQL = str_replace("@SYOHY_NO", trim($postData["txtSyohyNO"]), $strSQL);
        }
        $strSQL = str_replace("@FUKANZEN", "1", $strSQL);
        if ($postData["radCsvSumi"] == "true") {
            $strSQL = str_replace("@CSVFLG", "1", $strSQL);
        } else
            if ($postData["radCsvMi"] == "true") {
                $strSQL = str_replace("@CSVFLG", "", $strSQL);
            }
        if ($postData["radPrintSumi"] == "true") {
            $strSQL = str_replace("@PRINTFLG", "1", $strSQL);
        } else
            if ($postData["radPrintMi"] == "true") {
                $strSQL = str_replace("@PRINTFLG", "0", $strSQL);
            }

        $strSQL = str_replace("@SAKUSEIFROM", trim(str_replace("/", "", $postData["txtDateFrom"])), $strSQL);
        $strSQL = str_replace("@SAKUSEITO", trim(str_replace("/", "", $postData["txtDateTo"])), $strSQL);
        $strSQL = str_replace("@SHIHARAIFROM", trim(str_replace("/", "", $postData["txtShiharaiDTFrom"])), $strSQL);
        $strSQL = str_replace("@SHIHARAITO", trim(str_replace("/", "", $postData["txtShiharaiDTEnd"])), $strSQL);
        $strSQL = str_replace("@L_KAMOK_CD", trim($postData["txtLKamokuCD"]), $strSQL);
        $strSQL = str_replace("@R_KAMOK_CD", trim($postData["txtRKamokuCD"]), $strSQL);
        $strSQL = str_replace("@CRE_BUSYO_CD", trim($postData["txtBusyoCD"]), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", trim($postData["txtSyainNO"]), $strSQL);
        $strSQL = str_replace("@KEYWORD", trim($postData["txtKeyWord"]), $strSQL);
        return $strSQL;
    }

    public function fncSelectShiwakesql($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "SELECT 'FALSE' TAISYO" . "\r\n";
        $strSQL .= ",      SWK.SYOHY_NO || SWK.EDA_NO SYOHY_NO" . "\r\n";
        $strSQL .= ",      SUBSTRB((CASE WHEN SWK.L_KOUMK_CD IS NULL THEN LKMK.KAMOK_SSK_NM ELSE LKMK.KMK_KUM_NM END),1,22) L_KAMOKU" . "\r\n";
        $strSQL .= ",      SUBSTRB((CASE WHEN SWK.R_KOUMK_CD IS NULL THEN RKMK.KAMOK_SSK_NM ELSE RKMK.KMK_KUM_NM END),1,22) R_KAMOKU" . "\r\n";
        // 20240416 YIN UPD S
        // $strSQL .= ",      TO_CHAR(TO_DATE(SWK.SHIHARAI_DT),'YYYY/MM/DD') SHIHARAI_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(SWK.SHIHARAI_DT,'YYYYMMDD'),'YYYY/MM/DD') SHIHARAI_DT" . "\r\n";
        // 20240416 YIN UPD E

        // 20240703 UPD START
        // $strSQL .= ",      SUBSTRB(SWK.SHIHARAISAKI_NM,1,12) SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",      SUBSTRB(SWK.SHIHARAISAKI_NM,1,22) SHIHARAISAKI_NM" . "\r\n";
        // 20240703 UPD END

        $strSQL .= ",      TO_CHAR(SWK.CREATE_DATE,'YYYY/MM/DD') CREATE_DATE" . "\r\n";
        $strSQL .= ",      SWK.CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",      SUBSTRB(SYA.SYAIN_NM,1,12) CRE_SYA_NM" . "\r\n";
        $strSQL .= ",      SYOHY.KENSU" . "\r\n";
        $strSQL .= ",      SYOHY.KINGAKU" . "\r\n";
        $strSQL .= ",      DECODE(SYOHY.FUKANZEN_FLG,'1','TRUE','FALSE') FUKANZEN_FLG" . "\r\n";
        $strSQL .= ",      DECODE(SYOHY.PRINT_OUT_FLG,'1','TRUE','FALSE') PRINT_OUT_FLG" . "\r\n";
        if ($this->SessionComponent->read('PatternID') == $postData['CONST_ADMIN_PTN_NO'] || ($this->SessionComponent->read('PatternID') == $postData['CONST_HONBU_PTN_NO'])) {
            $strSQL .= ",      DECODE(SYOHY.CSV_OUT_FLG,'1','TRUE','FALSE') CSV_OUT_FLG" . "\r\n";
            $strSQL .= ",      (SELECT MAX(CSV_OUT_FLG)" . "\r\n";
            $strSQL .= "        FROM   HDPSHIWAKEDATA A" . "\r\n";
            $strSQL .= "        WHERE  A.SYOHY_NO = SWK.SYOHY_NO) MAX_SYORI_FLG" . "\r\n";
        } else {
            $strSQL .= ",      DECODE(SYOHY.HONBU_SYORIZUMI_FLG,'1','TRUE','FALSE') CSV_OUT_FLG" . "\r\n";
            $strSQL .= ",      DECODE(SYOHY.HONBU_SYORIZUMI_FLG,'1','1','0') MAX_SYORI_FLG" . "\r\n";
        }
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "       WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "ON     SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.GYO_NO = SWK.GYO_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_SYA_CD = '@SYOHY_SYA_CD'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_PRG_ID = '@SYOHY_PRG_ID'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_CLT_NM = '@SYOHY_CLT_NM'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 LKMK" . "\r\n";
        $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.KOUMK_CD),'999999'),LKMK.KOUMK_CD) = NVL(SWK.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 RKMK" . "\r\n";
        $strSQL .= "ON     RKMK.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(RKMK.KOUMK_CD),'999999'),RKMK.KOUMK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= "		WHERE  DEL_FLG = '0'" . "\r\n";
        if ($postData['radAll'] == "false") {
            //仕訳伝票又は支払伝票が選択されている場合
            $strSQL .= "        AND    SWK.DENPY_KB = '@DENPY_KB'" . "\r\n";
        }
        if ($postData['radPrintNoSel'] == "false") {
            $strSQL .= "        AND    SYOHY.PRINT_OUT_FLG = '@PRINTFLG'" . "\r\n";
        }
        if ($postData['radCsvNoSel'] == "false") {
            if ($this->SessionComponent->read('PatternID') == $postData['CONST_ADMIN_PTN_NO'] || ($this->SessionComponent->read('PatternID') == $postData['CONST_HONBU_PTN_NO'])) {
                $strSQL .= "        AND    SYOHY.CSV_OUT_FLG = '@CSVFLG'" . "\r\n";
            } else {
                $strSQL .= "        AND    SYOHY.HONBU_SYORIZUMI_FLG = '@CSVFLG'" . "\r\n";
            }
        }
        if ($postData['chkFukanzen'] == "true") {
            $strSQL .= "        AND    SYOHY.FUKANZEN_FLG = '@FUKANZEN'" . "\r\n";
        }
        if ($postData['txtKeyWord'] !== "") {
            $strSQL .= "        AND " . "\r\n";
            $strSQL .= "        ( " . "\r\n";

            $strSQL .= "         SWK.TEKYO LIKE '%@KEYWORD%' OR " . "\r\n";

            $strSQL .= "         SWK.L_HISSU_TEKYO1 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO2 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO3 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO4 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO5 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO6 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO7 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO8 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO9 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.L_HISSU_TEKYO10 LIKE '%@KEYWORD%' OR " . "\r\n";

            $strSQL .= "         SWK.R_HISSU_TEKYO1 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO2 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO3 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO4 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO5 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO6 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO7 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO8 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO9 LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.R_HISSU_TEKYO10 LIKE '%@KEYWORD%' OR " . "\r\n";

            $strSQL .= "         SWK.SHIHARAISAKI_CD LIKE '%@KEYWORD%' OR " . "\r\n";
            $strSQL .= "         SWK.SHIHARAISAKI_NM LIKE '%@KEYWORD%' " . "\r\n";
            $strSQL .= "        ) " . "\r\n";
        }
        $strSQL .= "ORDER BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",        SWK.EDA_NO" . "\r\n";
        if ($postData["radShiwake"] == "true") {
            $strSQL = str_replace("@DENPY_KB", "1", $strSQL);
        } else
            if ($postData["radShiharai"] == "true") {
                $strSQL = str_replace("@DENPY_KB", "2", $strSQL);
            }
        if (strlen(trim($postData["txtSyohyNO"])) == 17) {
            $strSQL = str_replace("@SYOHY_NO", substr(trim($postData["txtSyohyNO"]), 0, 15), $strSQL);
            $strSQL = str_replace("@EDA_NO", substr(trim($postData["txtSyohyNO"]), 15, 2), $strSQL);
        } else {
            $strSQL = str_replace("@SYOHY_NO", trim($postData["txtSyohyNO"]), $strSQL);
            $strSQL = str_replace("@EDA_NO", "", $strSQL);
        }
        $strSQL = str_replace("@FUKANZEN", "1", $strSQL);
        if ($postData["radCsvSumi"] == "true") {
            $strSQL = str_replace("@CSVFLG", "1", $strSQL);
        } else
            if ($postData["radCsvMi"] == "true") {
                $strSQL = str_replace("@CSVFLG", "0", $strSQL);
            }
        if ($postData["radPrintSumi"] == "true") {
            $strSQL = str_replace("@PRINTFLG", "1", $strSQL);
        } else
            if ($postData["radPrintMi"] == "true") {
                $strSQL = str_replace("@PRINTFLG", "0", $strSQL);
            }

        $strSQL = str_replace("@SAKUSEIFROM", trim(str_replace("/", "", $postData["txtDateFrom"])), $strSQL);
        $strSQL = str_replace("@SAKUSEITO", trim(str_replace("/", "", $postData["txtDateTo"])), $strSQL);
        $strSQL = str_replace("@SHIHARAIFROM", trim(str_replace("/", "", $postData["txtShiharaiDTFrom"])), $strSQL);
        $strSQL = str_replace("@SHIHARAITO", trim(str_replace("/", "", $postData["txtShiharaiDTEnd"])), $strSQL);
        $strSQL = str_replace("@L_KAMOK_CD", trim($postData["txtLKamokuCD"]), $strSQL);
        $strSQL = str_replace("@R_KAMOK_CD", trim($postData["txtRKamokuCD"]), $strSQL);
        $strSQL = str_replace("@CRE_BUSYO_CD", trim($postData["txtBusyoCD"]), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", trim($postData["txtSyainNO"]), $strSQL);
        $strSQL = str_replace("@SYOHY_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@SYOHY_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@SYOHY_PRG_ID", $postData["strPrgID"], $strSQL);
        $strSQL = str_replace("@KEYWORD", trim($postData["txtKeyWord"]), $strSQL);
        return $strSQL;
    }

    public function fncMicheckListsql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO || SWK.EDA_NO SYOHY_NO" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.L_KOUMK_CD IS NULL THEN LKMK.KAMOK_SSK_NM ELSE LKMK.KMK_KUM_NM END) L_KAMOKU" . "\r\n";
        $strSQL .= ",      (CASE WHEN SWK.R_KOUMK_CD IS NULL THEN RKMK.KAMOK_SSK_NM ELSE RKMK.KMK_KUM_NM END) R_KAMOKU" . "\r\n";
        // 20240416 YIN UPD S
        // $strSQL .= ",      TO_CHAR(TO_DATE(SWK.SHIHARAI_DT),'YYYY/MM/DD') SHIHARAI_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(SWK.SHIHARAI_DT,'YYYYMMDD'),'YYYY/MM/DD') SHIHARAI_DT" . "\r\n";
        // 20240416 YIN UPD E
        $strSQL .= ",      SWK.SHIHARAISAKI_NM" . "\r\n";
        $strSQL .= ",      TO_CHAR(SWK.CREATE_DATE,'YYYY/MM/DD') CREATE_DATE" . "\r\n";
        $strSQL .= ",      SWK.CRE_BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_RYKNM" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM CRE_SYA_NM" . "\r\n";
        $strSQL .= ",      SYOHY.KENSU" . "\r\n";
        $strSQL .= ",      SYOHY.KINGAKU" . "\r\n";
        $strSQL .= ",      DECODE(SYOHY.PRINT_OUT_FLG,'1','','○') PRINT_OUT_FLG" . "\r\n";
        $strSQL .= ",      DECODE(SYOHY.CSV_OUT_FLG,'1','','○') CSV_OUT_FLG" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') PRINT_DATE" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "       WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "ON     SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.GYO_NO = SWK.GYO_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_SYA_CD = '@SYOHY_SYA_CD'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_PRG_ID = '@SYOHY_PRG_ID'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_CLT_NM = '@SYOHY_CLT_NM'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 LKMK" . "\r\n";
        $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.KOUMK_CD),'999999'),LKMK.KOUMK_CD) = NVL(SWK.L_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       M29FZ6 RKMK" . "\r\n";
        $strSQL .= "ON     RKMK.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(RKMK.KOUMK_CD),'999999'),RKMK.KOUMK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = SWK.CRE_BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE  (SYOHY.PRINT_OUT_FLG = '0' OR SYOHY.CSV_OUT_FLG = '0')" . "\r\n";
        $strSQL .= "AND    DEL_FLG = '0'" . "\r\n";
        if ($postData['radAll'] == "false") {
            $strSQL .= "        AND    SWK.DENPY_KB = '@DENPY_KB'" . "\r\n";
        }
        $strSQL .= "ORDER BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",        SWK.EDA_NO" . "\r\n";
        if ($postData['radShiwake'] == "true") {
            $strSQL = str_replace("@DENPY_KB", "1", $strSQL);
        } else
            if ($postData['radShiharai'] == "true") {
                $strSQL = str_replace("@DENPY_KB", "2", $strSQL);
            }
        $strSQL = str_replace("@SYOHY_NO", trim($postData["txtSyohyNO"]), $strSQL);
        $strSQL = str_replace("@SYOHY_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@SYOHY_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@SYOHY_PRG_ID", "DENPYO_SEARCH_CHECK", $strSQL);
        return $strSQL;
    }

    public function fncFlgChecksql($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",      SWK.EDA_NO" . "\r\n";
        $strSQL .= ",      MAX(SWK.DEL_FLG) DEL_FLG" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "INNER JOIN WK_SYOHY_NO WKT" . "\r\n";
        $strSQL .= "ON     SWK.SYOHY_NO = WKT.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SWK.EDA_NO = WKT.EDA_NO" . "\r\n";
        $strSQL .= "AND    WKT.CRE_SYA_CD = '@SYOHY_SYA_CD'" . "\r\n";
        $strSQL .= "AND    WKT.CRE_PRG_ID = '@SYOHY_PRG_ID'" . "\r\n";
        $strSQL .= "AND    WKT.CRE_CLT_NM = '@SYOHY_CLT_NM'" . "\r\n";
        $strSQL .= "GROUP BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",        SWK.EDA_NO" . "\r\n";

        $strSQL = str_replace("@SYOHY_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@SYOHY_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@SYOHY_PRG_ID", $postData, $strSQL);
        return $strSQL;
    }

    public function fncSYOHYNOdeletesql()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_SYOHY_NO WHERE CRE_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' AND CRE_PRG_ID = 'DENPYO_SEARCH' AND CRE_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "'" . "\r\n";
        return $strSQL;
    }

    public function fncDenpyPrintdeletesql()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_SYOHY_NO WHERE CRE_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' AND CRE_PRG_ID = 'DENPYO_SEARCH_PRINT' AND CRE_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "'" . "\r\n";
        return $strSQL;
    }

    public function fncMicheckPrintdeletesql()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_SYOHY_NO WHERE CRE_SYA_CD = '" . $this->GS_LOGINUSER['strUserID'] . "' AND CRE_PRG_ID = 'DENPYO_SEARCH_CHECK' AND CRE_CLT_NM = '" . $this->GS_LOGINUSER['strClientNM'] . "'" . "\r\n";
        return $strSQL;
    }

    public function fncUpdPrintFlgSQL($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "SET    PRINT_OUT_FLG = '1'" . "\r\n";
        if ($this->SessionComponent->read('PatternID') == $postData['CONST_ADMIN_PTN_NO'] || ($this->SessionComponent->read('PatternID') == $postData['CONST_HONBU_PTN_NO'])) {
            $strSQL .= ",      HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",      UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",      UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  EXISTS " . "\r\n";
        $strSQL .= "       (SELECT SYOHY.SYOHY_NO" . "\r\n";
        $strSQL .= "        FROM   WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "        WHERE  SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "        AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_PRG_ID = '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_CLT_NM = '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= "        )" . "\r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $postData['BusyoCD'], $strSQL);

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：ワーク証憑№に登録する
    // '関 数 名：fncInsTaisyoSyohyNOPrint
    // '引 数 １：(I)strSyohy_NO :証憑№
    // '戻 り 値：ＳＱＬ
    // '処理説明：ワーク証憑№に対象欄にチェックが入っている伝票を登録する
    // '**********************************************************************
    public function fncInsTaisyoSyohyNOPrint($postData)
    {
        $strSql = $this->fncInsTaisyoSyohyNOPrintsql($postData);

        return parent::insert($strSql);
    }

    // '**********************************************************************
    // '処 理 名：ワーク証憑№に登録する
    // '関 数 名：fncInsTaisyoSyohyNO
    // '引 数 １：(I)strPrgNM :プログラムＩＤ
    // '戻 り 値：ＳＱＬ
    // '処理説明：ワーク証憑№に検索を行った結果の証憑№を登録する
    // '**********************************************************************
    public function fncInsTaisyoSyohyNO($postData)
    {
        $strSql = $this->fncInsTaisyoSyohyNOsql($postData);

        return parent::insert($strSql);
    }

    // '**********************************************************************
    // '処 理 名：検索条件に一致する証憑№を取得する
    // '関 数 名：fncSelectShiwake
    // '引 数 １：(I)strPrgID :プログラムＩＤ
    // '戻 り 値：ＳＱＬ
    // '処理説明：検索条件に一致する証憑№を取得する
    // '**********************************************************************
    public function fncSelectShiwake($postData)
    {
        $strSql = $this->fncSelectShiwakesql($postData);

        return parent::select($strSql);
    }

    // '**********************************************************************
    // '処 理 名：未チェックリスト一覧用のＳＱＬを取得する
    // '関 数 名：fncMicheckList
    // '引 数 １：(I)strPrgID :プログラムＩＤ
    // '戻 り 値：ＳＱＬ
    // '処理説明：未チェックリスト一覧用のＳＱＬを取得する
    // '**********************************************************************
    public function fncMicheckList($postData)
    {
        $strSql = $this->fncMicheckListsql($postData);

        return parent::select($strSql);
    }

    // '**********************************************************************
    // '処 理 名：検索条件に一致する証憑№を取得する
    // '関 数 名：fncSelectShiwake
    // '引 数 １：(I)strPrgID :プログラムＩＤ
    // '戻 り 値：ＳＱＬ
    // '処理説明：検索条件に一致する証憑№を取得する
    // '**********************************************************************
    public function fncFlgCheck($postData)
    {
        $strSql = $this->fncFlgChecksql($postData);

        return parent::select($strSql);
    }

    //ワーク証憑№のデータを全件削除する
    public function fncSYOHYNOdelete()
    {
        $strSql = $this->fncSYOHYNOdeletesql();

        return parent::delete($strSql);
    }

    //ワーク証憑№のデータを全件削除する
    public function fncMicheckPrintdelete()
    {
        $strSql = $this->fncMicheckPrintdeletesql();

        return parent::delete($strSql);
    }

    //ワーク証憑№のデータを全件削除する
    public function fncDenpyPrintdelete()
    {
        $strSql = $this->fncDenpyPrintdeletesql();

        return parent::delete($strSql);
    }

    public function fncUpdPrintFlg($postData)
    {
        $strSql = $this->fncUpdPrintFlgSQL($postData);

        return parent::update($strSql);
    }

}
