<?php
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
use App\Model\R4\Component\ClsComFncKRSS;

class FrmAuthCtlEdit extends ClsComDb
{
    public function fncGetBusyosql($flag)
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD ";
        $strSQL .= ", BUSYO_NM ";
        $strSQL .= "  FROM ";
        $strSQL .= "  HBUSYO ";
        if ($flag == FALSE) {
            $strSQL .= "  WHERE ";
            $strSQL .= "  SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1' ";
        }
        return $strSQL;
    }

    public function fncGetBusyo($flag)
    {
        return parent::select($this->fncGetBusyosql($flag));
    }

    public function fncSQL1($postArr)
    {
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= " SELECT DISTINCT" . "\r\n";
        $strSQL .= "      BU.BUSYO_CD " . "\r\n";
        $strSQL .= "     ,BU.BUSYO_NM " . "\r\n";
        $strSQL .= " FROM HAUTHORITY_CTL ACTL " . "\r\n";
        $strSQL .= "     ,HBUSYO         BU " . "\r\n";
        $strSQL .= " WHERE ACTL.BUSYO_CD=BU.BUSYO_CD " . "\r\n";
        $strSQL .= "   AND ACTL.SYS_KB = '@SYS_KB'" . "\r\n";
        $strSQL .= "     AND ACTL.SYAIN_NO = '@SYAIN_NO' " . "\r\n";
        $strSQL .= " ORDER BY BU.BUSYO_CD " . "\r\n";
        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $postArr['SYAIN_NO'], $strSQL);
        return $strSQL;
    }

    public function fncDoSQL1($postArr)
    {
        return parent::select($this->fncSQL1($postArr));
    }

    public function fncSQL2($postArr)
    {
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= "     NVL2(ACTL.HAUTH_ID,1,0) " . "\r\n";
        $strSQL .= "     ,AUTH.HAUTH_ID " . "\r\n";
        $strSQL .= "     ,AUTH.HAUTH_NM " . "\r\n";
        $strSQL .= "     ,AUTH.MEMO " . "\r\n";
        $strSQL .= "     ,NVL2(ACTL.HAUTH_ID,1,0) " . "\r\n";
        $strSQL .= "     ,ACTL.CREATE_DATE " . "\r\n";
        $strSQL .= " FROM HAUTHORITY_CTL ACTL " . "\r\n";
        $strSQL .= "     ,HAUTHORITY AUTH " . "\r\n";
        $strSQL .= " WHERE ACTL.SYAIN_NO(+) = '@SYAIN_NO'" . "\r\n";
        $strSQL .= "     AND ACTL.BUSYO_CD(+)='@BUSYO_CD' " . "\r\n";
        $strSQL .= "     AND ACTL.HAUTH_ID(+)=AUTH.HAUTH_ID " . "\r\n";
        $strSQL .= "     AND ACTL.SYS_KB(+) = AUTH.SYS_KB" . "\r\n";
        $strSQL .= "     AND AUTH.SYS_KB = '@SYS_KB'" . "\r\n";
        $strSQL .= " ORDER BY AUTH.HAUTH_ID " . "\r\n";
        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postArr['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $postArr['SYAIN_NO'], $strSQL);
        return $strSQL;
    }

    public function fncDoSQL2($postArr)
    {
        return parent::select($this->fncSQL2($postArr));
    }

    public function fncSQL3($postArr)
    {
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= " DELETE HAUTHORITY_CTL " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO='@SYAIN_NO' " . "\r\n";
        $strSQL .= "       AND BUSYO_CD='@BUSYO_CD' " . "\r\n";
        $strSQL .= "       AND SYS_KB = '@SYS_KB'" . "\r\n";
        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postArr['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $postArr['SYAIN_NO'], $strSQL);
        return $strSQL;
    }

    public function fncDoSQL3($postArr)
    {
        return parent::Do_Execute($this->fncSQL3($postArr));
    }

    public function fncSQL4($postArr, $CREATE_DATE, $HAUTH_ID)
    {
        $ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "frmAuthCtlEdit";
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= " INSERT INTO HAUTHORITY_CTL " . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "       SYAIN_NO" . "\r\n";
        $strSQL .= "      ,BUSYO_CD" . "\r\n";
        $strSQL .= "      ,HAUTH_ID" . "\r\n";
        $strSQL .= "      ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,CREATE_DATE" . "\r\n";
        $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "      ,UPD_CLT_NM" . "\r\n";
        $strSQL .= "      ,SYS_KB" . "\r\n";
        $strSQL .= "      ,MENU_LIST_NO" . "\r\n";
        $strSQL .= "      ) VALUES (" . "\r\n";
        $strSQL .= "       '@SYAIN_NO'" . "\r\n";
        $strSQL .= "      ,'@BUSYO_CD'" . "\r\n";
        $strSQL .= "      ,'@HAUTH_ID'" . "\r\n";
        $strSQL .= "     ,SYSDATE " . "\r\n";
        $strSQL .= "      ,'@CREATE_DATE'" . "\r\n";
        $strSQL .= "     ," . $ClsComFnc->FncSqlNv($UPDUSER) . "\r\n";
        $strSQL .= "     ," . $ClsComFnc->FncSqlNv($UPDAPP) . "\r\n";
        $strSQL .= "     ," . $ClsComFnc->FncSqlNv($UPDCLTNM) . "\r\n";
        $strSQL .= "     ,'@SYS_KB'" . "\r\n";
        $strSQL .= "     ,'1'" . "\r\n";

        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postArr['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@HAUTH_ID", $HAUTH_ID, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $postArr['SYAIN_NO'], $strSQL);
        if ($CREATE_DATE == "" && $CREATE_DATE == null) {
            $strSQL = str_replace("'@CREATE_DATE'", "SYSDATE", $strSQL);
        } else {
            $strSQL = str_replace("@CREATE_DATE", $CREATE_DATE, $strSQL);
        }
        return $strSQL;
    }

    public function fncDoSQL4($postArr, $CREATE_DATE, $HAUTH_ID)
    {
        return parent::Do_Execute($this->fncSQL4($postArr, $CREATE_DATE, $HAUTH_ID));
    }

}
