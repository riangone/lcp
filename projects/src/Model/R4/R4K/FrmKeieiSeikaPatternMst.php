<?php
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD         #ID                          XXXXXX                      FCSDL
 * 20151112           #2079                        BUG                          Yuanjh
 * 20151120           #2273                        BUG                          Yuanjh
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmKeieiSeikaPatternMst extends ClsComDb
{
    protected $ClsComFnc = "";
    function fncBusyoListSelSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT 'NO' ADD_FLAG";
        $strSQL .= ",''   PRINT_NO";
        $strSQL .= ",      BUSYO_CD";
        $strSQL .= ",      BUSYO_NM";
        $strSQL .= ",''   CREATE_DATE";
        $strSQL .= "   FROM   HBUSYO BUS";
        $strSQL .= "   ORDER BY BUSYO_CD";
        return $strSQL;
    }

    function fncPatternNMSelSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT";
        $strSQL .= "    PATTERN_NM";
        $strSQL .= ",   to_char(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HKSPATTERNNAMEMST";
        $strSQL .= "  ORDER BY PATTERN_NO";
        return $strSQL;
    }

    function fncPatternListSelSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT PATTERN_NO";
        $strSQL .= ",      BUSYO_CD";
        $strSQL .= ",      PRINT_ORDER";
        $strSQL .= ",   to_char(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS')  AS CREATE_DATE";
        $strSQL .= "  FROM   HKSPATTERNLISTMST";
        //---20151112  Yuanjh ADD S.
        $strSQL .= "  WHERE BUSYO_CD in (SELECT BUSYO_CD from HBUSYO)";
        //---20151112  Yuanjh ADD E.
        $strSQL .= "  ORDER BY PATTERN_NO, BUSYO_CD";
        return $strSQL;
    }

    //--20151120  Yuanjh   ADD S.
    function fncDelTblHKSPATTERNLISTMSTSQL($strPNO)
    {
        $strSQL = "";
        $strSQL = " DELETE FROM HKSPATTERNLISTMST  WHERE  PATTERN_NO = @PNO";
        $strSQL = str_replace("@PNO", $strPNO, $strSQL);

        return $strSQL;
    }
    //--20151120  Yuanjh   ADD E.

    function fncDelTblSQL($strTableNM)
    {
        $strSQL = "";
        $strSQL = "DELETE FROM @TABLENM";
        $strSQL = str_replace("@TABLENM", $strTableNM, $strSQL);
        return $strSQL;
    }

    function fncInsertPatternMstSQL($intPtnRow, $inputData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";

        $strSQL = "INSERT INTO HKSPATTERNNAMEMST";
        $strSQL .= "(           PATTERN_NO";
        $strSQL .= ",           PATTERN_NM";
        $strSQL .= ",           UPD_DATE";
        $strSQL .= ",           CREATE_DATE";
        $strSQL .= ",           UPD_SYA_CD";
        $strSQL .= ",           UPD_PRG_ID";
        $strSQL .= ",           UPD_CLT_NM";

        $strSQL .= ") VALUES";
        $strSQL .= "(           @PATTERNNO";
        $strSQL .= ",           '@PATTERNNM'";
        $strSQL .= ",           SYSDATE";
        $strSQL .= ",           (CASE WHEN '@SAKUSEIBI' IS NULL THEN SYSDATE ELSE TO_DATE('@SAKUSEIBI','YYYY/MM/DD HH24:MI:SS') END)";
        $strSQL .= ",           '@UPDUSER'";
        $strSQL .= ",           '@UPDAPP'";
        $strSQL .= ",           '@UPDCLT'";
        $strSQL .= ")";

        $this->ClsComFnc = new ClsComFnc();

        $strSQL = str_replace("@PATTERNNO", $intPtnRow + 1, $strSQL);
        $strSQL = str_replace("@PATTERNNM", rtrim($this->ClsComFnc->FncNv($inputData["PATTERN_NM"])), $strSQL);
        $strSQL = str_replace("@SAKUSEIBI", rtrim($this->ClsComFnc->FncNv($inputData["CREATE_DATE"])), $strSQL);

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", "KeieiSeikaPatternMst", $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);
        return $strSQL;
    }

    function fncInsPatternListSQL($intPtnRow, $inputData)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";

        $strSQL .= "INSERT INTO";
        $strSQL .= "       HKSPATTERNLISTMST";
        $strSQL .= "(      PATTERN_NO";
        $strSQL .= ",      BUSYO_CD";
        $strSQL .= ",      PRINT_ORDER";
        $strSQL .= ",      UPD_DATE";
        $strSQL .= ",      CREATE_DATE";
        $strSQL .= ",      UPD_SYA_CD";
        $strSQL .= ",      UPD_PRG_ID";
        $strSQL .= ",      UPD_CLT_NM";
        $strSQL .= ") VALUES";
        $strSQL .= "(      @PATTERNNO";
        $strSQL .= ",      '@BUSYOCD'";
        $strSQL .= ",      NVL(TRIM('@PRINTORDER'),'999')";
        $strSQL .= ",      SYSDATE";
        $strSQL .= ",      (CASE WHEN '@SAKUSEIBI' IS NULL THEN SYSDATE ELSE TO_DATE('@SAKUSEIBI','YYYY/MM/DD HH24:MI:SS') END)";
        $strSQL .= ",      '@UPDUSER'";
        $strSQL .= ",      '@UPDAPP'";
        $strSQL .= ",      '@UPDCLT'";

        $strSQL .= ")";

        $this->ClsComFnc = new ClsComFnc();

        $strSQL = str_replace("@PATTERNNO", $intPtnRow + 1, $strSQL);
        $strSQL = str_replace("@BUSYOCD", rtrim($this->ClsComFnc->FncNv($inputData["BUSYO_CD"])), $strSQL);
        $strSQL = str_replace("@PRINTORDER", rtrim($this->ClsComFnc->FncNv($inputData["PRINT_ORDER"])), $strSQL);
        $strSQL = str_replace("@SAKUSEIBI", rtrim($this->ClsComFnc->FncNv($inputData["CREATE_DATE"])), $strSQL);

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", "KeieiSeikaPatternMst", $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);

        return $strSQL;
    }

    public function fncBusyoListSel()
    {
        $strsql = $this->fncBusyoListSelSQL();
        return parent::select($strsql);
    }

    public function fncPatternNMSel()
    {
        $strsql = $this->fncPatternNMSelSQL();
        return parent::select($strsql);
    }

    public function fncPatternListSel()
    {
        $strsql = $this->fncPatternListSelSQL();
        return parent::select($strsql);
    }


    public function fncDelTbl($strTableNM)
    {
        $strSql = $this->fncDelTblSQL($strTableNM);
        return parent::Do_Execute($strSql);
    }

    public function fncInsertPatternMst($intPtnRow, $inputData)
    {
        $strsql = $this->fncInsertPatternMstSQL($intPtnRow, $inputData);
        return parent::Do_Execute($strsql);
    }

    public function fncInsPatternList($intPtnRow, $inputData)
    {
        $strsql = $this->fncInsPatternListSQL($intPtnRow, $inputData);

        return parent::Do_Execute($strsql);
    }
    //---20151120   Yuanjh  ADD  S.
    public function fncDelTblhkspatternlistmst($strPNO)
    {
        $strSql = $this->fncDelTblHKSPATTERNLISTMSTSQL($strPNO);
        return parent::Do_Execute($strSql);
    }
    //---20151120   Yuanjh  ADD  E.

}