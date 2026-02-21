<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;

class FrmShimeProc extends ClsComDb
{
    public $ClsComFncJKSYS;
    //処理年月を表示する
    public function subDispSyoriYM()
    {
        $strSQL = "";

        $strSQL = " SELECT SUBSTR(SYORI_YM,1,4) || '/' || SUBSTR(SYORI_YM,5,2) AS SYORI_YM" . "\r\n";
        $strSQL .= " FROM   JKCONTROLMST" . "\r\n";
        $strSQL .= " WHERE  ID = '01'" . "\r\n";
        return parent::select($strSQL);
    }

    public function btnUpdate_Click()
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        $strSQL .= "UPDATE JKCONTROLMST" . "\r\n";
        $strSQL .= " SET    SYORI_YM = TO_CHAR(ADD_MONTHS(TO_DATE(SYORI_YM || '01','YYYY/MM/DD'),1),'YYYYMM')" . "\r\n";
        $strSQL .= " ,       KISYU_YMD = (CASE WHEN SUBSTR(SYORI_YM,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KISYU_YMD),12),'YYYYMMDD') ELSE KISYU_YMD END)" . " \r\n";
        $strSQL .= " ,       KIMATU_YMD = (CASE WHEN SUBSTR(SYORI_YM,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KIMATU_YMD),12),'YYYYMMDD') ELSE KIMATU_YMD END)" . " \r\n";
        $strSQL .= " ,       KI = (CASE WHEN SUBSTR(SYORI_YM,5,2) = '09' THEN KI + 1 ELSE KI END)" . "\r\n";
        $strSQL .= " ,       UPD_SYA_CD = " . $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']) . " \r\n";
        $strSQL .= " ,       UPD_PRG_ID = " . $this->ClsComFncJKSYS->FncSqlNv('ShimeProc') . "\r\n";
        $strSQL .= " ,       UPD_CLT_NM = " . $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']) . " \r\n";
        $strSQL .= " WHERE  ID = '01'" . "\r\n";
        return parent::update($strSQL);
    }

}
