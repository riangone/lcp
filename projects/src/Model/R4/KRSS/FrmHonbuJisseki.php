<?php


namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;

class FrmHonbuJisseki extends ClsComDb
{
    public function frmGetYearMonthSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT ";
        $strSQL .= "  ID, ";
        $strSQL .= "  (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU, ";
        $strSQL .= "  KISYU_YMD KISYU, ";
        $strSQL .= "  KI ";
        $strSQL .= "FROM ";
        $strSQL .= "  HKEIRICTL ";
        $strSQL .= "WHERE ";
        $strSQL .= "  ID = '01' ";

        return $strSQL;
    }

    function fncDeleteKanr_sql()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_HKANRIZ_KEIEISEIKA KR" . "\r\n";
        $strSQL .= " WHERE   NOT EXISTS" . "\r\n";
        $strSQL .= "       (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "        FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "        WHERE  KR.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "        AND    BUS.PRN_KB5 = 'O'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        return $strSQL;
    }

    public function fncWKInsert_sql($NENGTU_cboYM, $UPDAPP, $UPDCLT, $UPDUSER)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO \r";
        $sqlstr .= "    WK_HKANRIZ_KEIEISEIKA \r";
        $sqlstr .= "(";
        $sqlstr .= "KEIJO_DT\r";
        $sqlstr .= ",KAMOKU_CD\r";
        $sqlstr .= ",HIMOKU_CD\r";
        $sqlstr .= ",BUSYO_CD\r";
        $sqlstr .= ",L_GK\r";
        $sqlstr .= ",R_GK\r";
        $sqlstr .= ",TOU_ZAN\r";
        $sqlstr .= ",CREATE_DATE\r";

        $sqlstr .= ",UPD_SYA_CD\r";
        $sqlstr .= ",UPD_PRG_ID\r";
        $sqlstr .= ",UPD_CLT_NM\r";
        $sqlstr .= ",UPD_DATE\r";
        $sqlstr .= ")";

        $sqlstr .= " SELECT \r";
        $sqlstr .= "KEIJO_DT\r";
        $sqlstr .= ",KAMOKU_CD\r";
        $sqlstr .= ",HIMOKU_CD\r";
        $sqlstr .= ",BUSYO_CD\r";
        $sqlstr .= ",L_GK\r";
        $sqlstr .= ",R_GK\r";
        $sqlstr .= ",TOU_ZAN\r";
        $sqlstr .= ",CREATE_DATE\r";

        $sqlstr .= ", '@UPDUSER' \r\n";
        $sqlstr .= ", '@UPDAPP' \r\n";
        $sqlstr .= ", '@UPDCLT' \r\n";
        $sqlstr .= ",SYSDATE \r";
        $sqlstr .= " FROM \r";
        $sqlstr .= "    HKANRIZ\r";
        $sqlstr .= "    WHERE ";

        $tpM = substr($NENGTU_cboYM, 4, 2);
        $tpY = substr($NENGTU_cboYM, 0, 4);
        if ((int) $tpM >= 10) {
            $tpY = (int) $tpY;
            $KIFM = $tpY . "10";
        } else {
            $KIFM = (int) $tpY - 1;
            $KIFM = $KIFM . "10";
        }
        $sqlstr .= " KEIJO_DT >='" . $KIFM . "'";
        $sqlstr .= " AND KEIJO_DT <='" . $NENGTU_cboYM . "'";
        $sqlstr = str_replace("@UPDUSER", $UPDUSER, $sqlstr);
        $sqlstr = str_replace("@UPDAPP", $UPDAPP, $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $UPDCLT, $sqlstr);

        return $sqlstr;
    }

    public function fncWKTRUNCATE_sql()
    {
        $sqlstr = "DELETE FROM WK_HKANRIZ_KEIEISEIKA";
        return $sqlstr;
    }

    public function fncPrintSelectSQL($NENGTU_cboYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT ";
        $strSQL .= " KI ";
        $strSQL .= ",NENGETU ";
        $strSQL .= ",BUSYO_CD ";
        $strSQL .= ",BUSYO_NM ";
        $strSQL .= ",LINE_NO ";
        $strSQL .= ",JISSEKI ";
        $strSQL .= "FROM ";
        //        $strSQL .= " VW_KEIEISEIKA ";
        $strSQL .= " VW_HONBUJISSEKILIST ";
        $strSQL .= "WHERE ";
        $strSQL .= " NENGETU ='" . str_replace("/", "", $NENGTU_cboYM) . "'";

        //        $strSQL .= "ORDER BY ";
//        $strSQL .= "  BUSYO_CD, ";
//        $strSQL .= "  LINE_NO ";
//$this->log($strSQL);
        return $strSQL;
    }

    public function frmGetYearMonth()
    {
        $strsql = $this->frmGetYearMonthSQL();
        return parent::select($strsql);
    }

    public function fncPrintSelect($NENGTU_cboYM)
    {
        $strsql = $this->fncPrintSelectSQL($NENGTU_cboYM);
        return parent::select($strsql);
    }

    public function fncDeleteKanr()
    {
        $sql = $this->fncDeleteKanr_sql();
        return parent::Do_Execute($sql);
    }

    public function fncWKInsert($NENGTU_cboYM, $UPDAPP, $UPDCLTNM, $UPDUSER)
    {
        $sql = $this->fncWKInsert_sql($NENGTU_cboYM, $UPDAPP, $UPDCLTNM, $UPDUSER);
        return parent::Do_Execute($sql);
    }

    public function fncWKTRUNCATE()
    {
        $sql = $this->fncWKTRUNCATE_sql();
        return parent::Do_Execute($sql);
    }



    //20160620 Ins Start
    public function fncWKKIKANTRUNCATE()
    {
        $sql = $this->fncWKKIKANTRUNCATE_sql();
        return parent::Do_Execute($sql);
    }
    public function fncWKKIKANINSERT($Nengetu = "")
    {
        $sql = $this->fncWKKIKANINSERT_sql($Nengetu);
        return parent::Do_Execute($sql);
    }

    //--sql--
    private function fncWKKIKANTRUNCATE_sql()
    {
        $sqlstr = "";
        $sqlstr .= "TRUNCATE TABLE WK_KEIEISEIKA_KIKAN ";
        return $sqlstr;
    }

    private function fncWKKIKANINSERT_sql($Nengetu)
    {
        $sqlstr = "";

        $tpM = substr($Nengetu, 4, 2);
        $tpY = substr($Nengetu, 0, 4);
        if ((int) $tpM >= 10) {
            $tpY = (int) $tpY - 1;
            $KIFM = $tpY . "10";
        } else {
            $KIFM = (int) $tpY - 2;
            $KIFM = $KIFM . "10";
        }

        $sqlstr .= "INSERT INTO WK_KEIEISEIKA_KIKAN VALUES ('" . $KIFM . "','" . $Nengetu . "') ";
        //$this->log($sqlstr);
        return $sqlstr;
    }

    //20160620 Ins End


}
