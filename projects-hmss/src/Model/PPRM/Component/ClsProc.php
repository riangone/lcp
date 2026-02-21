<?php
// 共通クラスの読込み
namespace App\Model\PPRM\Component;

use App\Model\Component\ClsComDb;

class ClsProc extends ClsComDb
{

    public function SubSetEnabled_OnPageLoad($strGSYSTEM_KB_PPRM, $oPage, $strSyainNO, $strBusyoCD = "")
    {
        $strSql = "";

        $strSql .= "SELECT   PMST.PRO_NO" . " \r\n";
        $strSql .= ",        PCTL.HAUTH_ID" . " \r\n";
        $strSql .= ",        ANAM.HAUTH_NM" . " \r\n";
        $strSql .= ",        ANAM.MEMO" . " \r\n";
        $strSql .= ",        NVL(PMST.SYSTEM_AUTH_CTL_FLG, '0')  AS SYSTEM_AUTH_CTL_FLG" . " \r\n";
        $strSql .= ",        NVL(PMST.USER_AUTH_CTL_FLG, '0')         AS PRG_AUTH_CTL_FLG" . " \r\n";
        $strSql .= ",        DECODE(NVL(ACTL.CNT, 0), 0, '0', '1') AS AUTH_CTL" . " \r\n";
        $strSql .= "FROM     HPROGRAMMST PMST" . " \r\n";
        $strSql .= "         INNER JOIN RCTBASEBTNDISPTBL PCTL" . " \r\n";
        $strSql .= "             ON PMST.PRO_NO = PCTL.MENU_LIST_NO" . " \r\n";
        $strSql .= "            AND PCTL.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSql .= "            AND PCTL.MENU_LIST_KB = '@MENU_LIST_KB'" . " \r\n";
        $strSql .= "         INNER JOIN HAUTHORITY ANAM" . " \r\n";
        $strSql .= "             ON PCTL.HAUTH_ID = ANAM.HAUTH_ID" . " \r\n";
        $strSql .= "            AND PCTL.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSql .= "         LEFT JOIN (SELECT   MENU_LIST_NO," . " \r\n";
        $strSql .= "                             HAUTH_ID," . " \r\n";
        $strSql .= "                             COUNT(*) AS CNT" . " \r\n";
        $strSql .= "                    FROM     HAUTHORITY_CTL" . " \r\n";
        $strSql .= "                    WHERE    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
        if ($strBusyoCD != "") {
            $strSql .= "                    AND      BUSYO_CD IN ('ZZZ', '@BUSYO_CD')" . " \r\n";
        }
        $strSql .= "                    GROUP BY MENU_LIST_NO, HAUTH_ID) ACTL" . " \r\n";
        $strSql .= "             ON PMST.PRO_NO = ACTL.MENU_LIST_NO" . " \r\n";
        $strSql .= "            AND ANAM.HAUTH_ID = ACTL.HAUTH_ID" . " \r\n";
        $strSql .= "WHERE    PMST.PRO_ID = '@PRO_ID'" . " \r\n";
        $strSql .= "AND      PMST.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSql .= "ORDER BY PCTL.HAUTH_ID" . " \r\n";

        $strSql = str_replace("@PRO_ID", $oPage, $strSql);
        $strSql = str_replace("@SYS_KB", $strGSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYAIN_NO", $strSyainNO, $strSql);
        $strSql = str_replace("@BUSYO_CD", $strBusyoCD, $strSql);
        $strSql = str_replace("@MENU_LIST_KB", "0", $strSql);

        $result = parent::select($strSql);
        $btnEnabled = array();

        $objReader = $result['data'];
        if (count((array) $objReader) > 0) {
            $result['data'][0] = "";
            foreach ((array) $objReader as $value) {
                $btnEnabled[$value['MEMO']] = true;
                if ($value['SYSTEM_AUTH_CTL_FLG'] == "0") {
                    $btnEnabled[$value['MEMO']] = false;
                }
                if ($value['PRG_AUTH_CTL_FLG'] == "0") {
                    $btnEnabled[$value['MEMO']] = false;
                }
                if ($value['AUTH_CTL'] != "0") {
                    $btnEnabled[$value['MEMO']] = false;
                }

            }

        }

        return $btnEnabled;

    }

    public function SubSetEnabled_Control($strGSYSTEM_KB_PPRM, $oPage, $strSyainNO, $strBusyoCD, $objControl)
    {

        $strSql = "";
        $intRetVal = 0;
        $strRet = array(
            "",
            ""
        );
        $strProgramID = $oPage;

        $strSql .= "SELECT   PMST.PRO_NO" . " \r\n";
        $strSql .= ",        PCTL.HAUTH_ID" . " \r\n";
        $strSql .= ",        ANAM.HAUTH_NM" . " \r\n";
        $strSql .= ",        ANAM.MEMO" . " \r\n";
        $strSql .= ",        NVL(PMST.SYSTEM_AUTH_CTL_FLG, '0')  AS SYSTEM_AUTH_CTL_FLG" . " \r\n";
        $strSql .= ",        NVL(PMST.USER_AUTH_CTL_FLG, '0')         AS PRG_AUTH_CTL_FLG" . " \r\n";
        $strSql .= ",        DECODE(NVL(ACTL.CNT, 0), 0, '0', '1') AS AUTH_CTL" . " \r\n";
        $strSql .= "FROM     HPROGRAMMST PMST" . " \r\n";
        $strSql .= "         INNER JOIN RCTBASEBTNDISPTBL PCTL" . " \r\n";
        $strSql .= "             ON PMST.PRO_NO = PCTL.MENU_LIST_NO" . " \r\n";
        $strSql .= "            AND PCTL.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSql .= "            AND PCTL.MENU_LIST_KB = '@MENU_LIST_KB'" . " \r\n";
        $strSql .= "         INNER JOIN HAUTHORITY ANAM" . " \r\n";
        $strSql .= "             ON PCTL.HAUTH_ID = ANAM.HAUTH_ID" . " \r\n";
        $strSql .= "            AND ANAM.MEMO     = '@MEMO'" . " \r\n";
        $strSql .= "         LEFT JOIN (SELECT   MENU_LIST_NO," . " \r\n";
        $strSql .= "                             HAUTH_ID," . " \r\n";
        $strSql .= "                             COUNT(*) AS CNT" . " \r\n";
        $strSql .= "                    FROM     HAUTHORITY_CTL" . " \r\n";
        $strSql .= "                    WHERE    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
        $strSql .= "                    AND      BUSYO_CD IN ('ZZZ', '@BUSYO_CD')" . " \r\n";
        $strSql .= "                    GROUP BY MENU_LIST_NO, HAUTH_ID) ACTL" . " \r\n";
        $strSql .= "             ON PMST.PRO_NO = ACTL.MENU_LIST_NO" . " \r\n";
        $strSql .= "            AND ANAM.HAUTH_ID = ACTL.HAUTH_ID" . " \r\n";
        $strSql .= "WHERE    PMST.PRO_ID = '@PRO_ID'" . " \r\n";
        $strSql .= "AND      PMST.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSql .= "ORDER BY PCTL.HAUTH_ID" . " \r\n";
        $strSql = str_replace("@PRO_ID", $strProgramID, $strSql);
        $strSql = str_replace("@SYS_KB", $strGSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYAIN_NO", $strSyainNO, $strSql);
        $strSql = str_replace("@BUSYO_CD", $strBusyoCD ? $strBusyoCD : '', $strSql);
        $strSql = str_replace("@MEMO", $objControl, $strSql);
        $strSql = str_replace("@MENU_LIST_KB", "0", $strSql);

        $result = parent::select($strSql);

        $objReader = $result['data'];
        if (count((array) $objReader) > 0) {
            foreach ((array) $objReader as $value) {
                if ($value['SYSTEM_AUTH_CTL_FLG'] == "0") {
                    $blnEnabled = True;
                    $strRet[1] = "1";
                    $intRetVal = 1;
                }
                if ($value['PRG_AUTH_CTL_FLG'] == "0") {
                    $blnEnabled = True;
                    $strRet[1] = "1";
                    $intRetVal = 1;
                }
                if ($value['AUTH_CTL'] != "0") {
                    $blnEnabled = True;
                }
                if ($intRetVal == 0) {
                    $strRet = $this->FncGetAuthInfo($strGSYSTEM_KB_PPRM, $oPage, $objControl, $strSyainNO);
                }
                if (isset($blnEnabled)) {

                }
            }

        }

        return $strRet;

    }

    public function FncCheckEnabled_Control($strGSYSTEM_KB_PPRM, $oPage, $strSyainNO, $strBusyoCD, $objControl)
    {
        $strSql = "";
        $intRetVal = 0;
        $blnEnabled = false;
        $strProgramID = $oPage;

        $strSql .= "SELECT   PMST.PRO_NO" . " \r\n";
        $strSql .= ",        PCTL.HAUTH_ID" . " \r\n";
        $strSql .= ",        ANAM.HAUTH_NM" . " \r\n";
        $strSql .= ",        ANAM.MEMO" . " \r\n";
        $strSql .= ",        NVL(PMST.SYSTEM_AUTH_CTL_FLG, '0')  AS SYSTEM_AUTH_CTL_FLG" . " \r\n";
        $strSql .= ",        NVL(PMST.USER_AUTH_CTL_FLG, '0')         AS PRG_AUTH_CTL_FLG" . " \r\n";
        $strSql .= ",        DECODE(NVL(ACTL.CNT, 0), 0, '0', '1') AS AUTH_CTL" . " \r\n";
        $strSql .= "FROM     HPROGRAMMST PMST" . " \r\n";
        $strSql .= "         INNER JOIN RCTBASEBTNDISPTBL PCTL" . " \r\n";
        $strSql .= "             ON PMST.PRO_NO = PCTL.MENU_LIST_NO" . " \r\n";
        $strSql .= "            AND PCTL.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSql .= "            AND PCTL.MENU_LIST_KB = '@MENU_LIST_KB'" . " \r\n";
        $strSql .= "         INNER JOIN HAUTHORITY ANAM" . " \r\n";
        $strSql .= "             ON PCTL.HAUTH_ID = ANAM.HAUTH_ID" . " \r\n";
        $strSql .= "            AND ANAM.MEMO     = '@MEMO'" . " \r\n";
        $strSql .= "         LEFT JOIN (SELECT   MENU_LIST_NO," . " \r\n";
        $strSql .= "                             HAUTH_ID," . " \r\n";
        $strSql .= "                             COUNT(*) AS CNT" . " \r\n";
        $strSql .= "                    FROM     HAUTHORITY_CTL" . " \r\n";
        $strSql .= "                    WHERE    SYAIN_NO = '@SYAIN_NO'" . " \r\n";
        $strSql .= "                    AND      BUSYO_CD IN ('ZZZ', '@BUSYO_CD')" . " \r\n";
        $strSql .= "                    GROUP BY MENU_LIST_NO, HAUTH_ID) ACTL" . " \r\n";
        $strSql .= "             ON PMST.PRO_NO = ACTL.MENU_LIST_NO" . " \r\n";
        $strSql .= "            AND ANAM.HAUTH_ID = ACTL.HAUTH_ID" . " \r\n";
        $strSql .= "WHERE    PMST.PRO_ID = '@PRO_ID'" . " \r\n";
        $strSql .= "AND      PMST.SYS_KB = '@SYS_KB'" . " \r\n";
        $strSql .= "ORDER BY PCTL.HAUTH_ID" . " \r\n";
        $strSql = str_replace("@PRO_ID", $strProgramID, $strSql);
        $strSql = str_replace("@SYS_KB", $strGSYSTEM_KB_PPRM, $strSql);
        $strSql = str_replace("@SYAIN_NO", $strSyainNO, $strSql);
        $strSql = str_replace("@BUSYO_CD", $strBusyoCD, $strSql);
        $strSql = str_replace("@MEMO", $objControl, $strSql);
        $strSql = str_replace("@MENU_LIST_KB", "0", $strSql);

        $result = parent::select($strSql);

        $objReader = $result['data'];
        if (count((array) $objReader) > 0) {
            foreach ((array) $objReader as $value) {
                if ($value['SYSTEM_AUTH_CTL_FLG'] == "0") {
                    $blnEnabled = True;
                    $intRetVal = 1;
                }
                if ($value['PRG_AUTH_CTL_FLG'] == "0") {
                    $blnEnabled = True;
                    $intRetVal = 1;
                }
                if ($value['AUTH_CTL'] != "0") {
                    $blnEnabled = True;
                    $intRetVal = 1;
                }
                if ($intRetVal == 0) {
                    $blnEnabled = False;
                    $intRetVal = 2;
                }
            }

        }

        return $blnEnabled;

    }

    public function FncGetAuthInfo($strGSYSTEM_KB_PPRM, $oPage, $strCtlNM, $strSyainNO)
    {
        $strSql = "";
        $strRet = array(
            "",
            ""
        );

        $strSql .= "SELECT   ANAM.HAUTH_ID" . " \r\n";
        $strSql .= ",        DECODE(NVL(ACTL.CNT, 0), 0, '0', '1') AS BUSYO_CTL_FLG2" . " \r\n";
        $strSql .= "FROM     HAUTHORITY ANAM" . " \r\n";
        $strSql .= "         INNER JOIN RCTBASEBTNDISPTBL PCTL" . " \r\n";
        $strSql .= "             ON ANAM.HAUTH_ID = PCTL.HAUTH_ID" . " \r\n";
        $strSql .= "            AND PCTL.MENU_LIST_NO   = '@PRO_NO'" . " \r\n";
        $strSql .= "         LEFT JOIN (SELECT   HAUTH_ID," . " \r\n";
        $strSql .= "                             COUNT(*) AS CNT" . " \r\n";
        $strSql .= "                    FROM     HAUTHORITY_CTL" . " \r\n";
        $strSql .= "                    WHERE    MENU_LIST_NO = '@PRO_NO'" . " \r\n";
        $strSql .= "                    AND      SYAIN_NO = '@SYAIN_NO'" . " \r\n";
        $strSql .= "                    AND      BUSYO_CD = 'ZZZ'" . " \r\n";
        $strSql .= "                    GROUP BY HAUTH_ID) ACTL" . " \r\n";
        $strSql .= "             ON ANAM.HAUTH_ID = ACTL.HAUTH_ID" . " \r\n";
        $strSql .= "WHERE    ANAM.MEMO = '@MEMO'" . " \r\n";
        $strSql = str_replace("@MEMO", $strCtlNM, $strSql);
        $strSql = str_replace("@PRO_NO", $this->FncGetProgramNO($strGSYSTEM_KB_PPRM, $oPage), $strSql);
        $strSql = str_replace("@SYAIN_NO", $strSyainNO, $strSql);

        $result = parent::select($strSql);

        $objReader = $result['data'];
        if (count((array) $objReader) > 0) {
            $strRet[0] = $objReader[0]['HAUTH_ID'];
            $strRet[1] = $objReader[0]['BUSYO_CTL_FLG2'];
        }

        return $strRet;
    }

    //**********************************************************************
    //処 理 名：ProgramNOの取得
    //関 数 名：FncGetProgramNO_ppr
    //引    数：パス
    //戻 り 値：SQL文
    //処理説明：ProgramNOの取得
    //**********************************************************************
    public function FncGetProgramNO($strGSYSTEM_KB_PPRM, $oPage)
    {
        $strSql = "";

        $strSql .= "SELECT   PMST.PRO_NO" . "\r\n";
        $strSql .= "FROM     HPROGRAMMST PMST" . "\r\n";
        $strSql .= "WHERE    PMST.PRO_ID = '@PRO_ID'" . "\r\n";
        $strSql .= "AND      PMST.SYS_KB = '@SYS_KB'";

        $strSql = str_replace("@PRO_ID", $oPage, $strSql);
        $strSql = str_replace("@SYS_KB", $strGSYSTEM_KB_PPRM, $strSql);

        $result = parent::select($strSql);

        $objReader = $result['data'];
        if (count((array) $objReader) > 0) {
            return $objReader[0]['PRO_NO'];
        } else {
            return "ProgramIDの取得に失敗しました。";
        }

    }

}
