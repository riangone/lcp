<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
class FrmJinkenhiBusyoHenkanMente extends ClsComDb
{
    public $ClsComFncJKSYS;
    //人件費部署変換マスタ(存在チェック)
    public function FncGetBUSYOCNV()
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "  BUS.BEF_BUSYO_CD , " . "\r\n";
        $strSQL .= "  JKB1.BUSYO_NM BEFORE_NM , " . "\r\n";
        $strSQL .= "  BUS.AFT_BUSYO_CD , " . "\r\n";
        $strSQL .= "  JKB2.BUSYO_NM AFTER_NM  " . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "  JKBUSYOCNV BUS " . "\r\n";
        $strSQL .= "INNER JOIN JKBUMON JKB1 " . "\r\n";
        $strSQL .= "ON BUS.BEF_BUSYO_CD = JKB1.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN JKBUMON JKB2 " . "\r\n";
        $strSQL .= "ON BUS.AFT_BUSYO_CD = JKB2.BUSYO_CD" . "\r\n";
        $strSQL .= "ORDER BY BEF_BUSYO_CD " . "\r\n";

        return parent::select($strSQL);
    }

    //人件費部署変換マスタ(UPDATE)
    public function FncUpdBUSYOCNV($txtAfter, $txtBefore)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "UPDATE  JKBUSYOCNV" . "\r\n";
        $strSQL .= "SET" . "\r\n";
        $strSQL .= "BEF_BUSYO_CD = @BEFORE," . " \r\n";
        $strSQL .= "AFT_BUSYO_CD = @AFTER," . " \r\n";
        $strSQL .= "UPD_DATE = sysdate," . "\r\n";
        $strSQL .= "UPD_SYA_CD = '@SYA_CD'," . " \r\n";
        $strSQL .= "UPD_PRG_ID = 'JinkenhiBusyoMente', " . "\r\n";
        $strSQL .= "UPD_CLT_NM = '@CLT_NM'" . " \r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "BEF_BUSYO_CD = @BEFORE" . " \r\n";

        $strSQL = str_replace("@BEFORE", $this->ClsComFncJKSYS->FncSqlNv($txtBefore), $strSQL);
        $strSQL = str_replace("@AFTER", $this->ClsComFncJKSYS->FncSqlNv($txtAfter), $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    //人件費部署変換マスタ(INSERT)
    public function FncInsBUSYOCNV($txtAfter, $txtBefore)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " INSERT INTO JKBUSYOCNV " . "\r\n";
        $strSQL .= "      (" . "\r\n";
        $strSQL .= "       BEF_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,AFT_BUSYO_CD" . "\r\n";
        $strSQL .= "      ,CREATE_DATE" . "\r\n";
        $strSQL .= "      ,CRE_SYA_CD" . "\r\n";
        $strSQL .= "      ,CRE_PRG_ID" . "\r\n";
        $strSQL .= "      ,UPD_DATE" . "\r\n";
        $strSQL .= "      ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "      ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "      ,UPD_CLT_NM" . "\r\n";
        $strSQL .= "      ) VALUES (" . "\r\n";
        $strSQL .= "      @BEFORE" . "\r\n";
        $strSQL .= "      ,@AFTER" . "\r\n";
        $strSQL .= "     ,sysdate " . "\r\n";
        $strSQL .= "  ,'@SYA_CD'" . "\r\n";
        $strSQL .= "      ,'JinkenhiBusyoMente'" . "\r\n";
        $strSQL .= "     ,sysdate " . "\r\n";
        $strSQL .= "  ,'@SYA_CD'" . "\r\n";
        $strSQL .= "      ,'JinkenhiBusyoMente'" . "\r\n";
        $strSQL .= "   ,'@CLT_NM')" . "\r\n";

        $strSQL = str_replace("@BEFORE", $this->ClsComFncJKSYS->FncSqlNv($txtBefore), $strSQL);
        $strSQL = str_replace("@AFTER", $this->ClsComFncJKSYS->FncSqlNv($txtAfter), $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //部門マスタ
    public function FncGetBUMON()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_NM" . " \r\n";
        $strSQL .= ", BUSYO_CD" . " \r\n";
        $strSQL .= "FROM    JKBUMON" . " \r\n";

        return parent::select($strSQL);
    }

    //人件費部署変換マスタ(存在チェック)
    public function FncGetBUSYOCNV2($txtBefore)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= " BUS.BEF_BUSYO_CD" . "\r\n";
        $strSQL .= " FROM  JKBUSYOCNV BUS " . "\r\n";
        $strSQL .= " WHERE BUS.BEF_BUSYO_CD = @BEF_BUSYO_CD " . "\r\n";

        $strSQL = str_replace("@BEF_BUSYO_CD", $this->ClsComFncJKSYS->FncSqlNv($txtBefore), $strSQL);

        return parent::select($strSQL);
    }

    //人件費部署変換マスタ(DELETE)
    public function FncDelBUSYOCNV($postdata)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE FROM JKBUSYOCNV BUS" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        $strSQL .= "BUS.BEF_BUSYO_CD = @BEFORE" . "\r\n";

        $strSQL = str_replace("@BEFORE", $this->ClsComFncJKSYS->FncSqlNv($postdata), $strSQL);

        return parent::delete($strSQL);
    }

}
