<?php
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKLoginEdit extends ClsComDb
{
    public function FncGetSyainMstValue($postData)
    {
        $strSQL = $this->FncGetSyainMstValueSql($postData);
        return parent::select($strSQL);
    }
    public function mLoginSel($postData)
    {
        $strSQL = $this->mLoginSelSql($postData);
        return parent::select($strSQL);
    }
    public function mLoginUpd($postData)
    {
        $strSQL = $this->mLoginUpdSql($postData);
        return parent::update($strSQL);
    }
    public function mLoginIns($postData)
    {
        $strSQL = $this->mLoginInsSql($postData);
        return parent::insert($strSQL);
    }
    public function hhaizokuSel($postData)
    {
        $strSQL = $this->hhaizokuSelSql($postData);
        return parent::select($strSQL);
    }
    public function hhaizokuUpd($postData)
    {
        $strSQL = $this->hhaizokuUpdSql($postData);
        return parent::update($strSQL);
    }
    public function hhaizokuIns($postData)
    {
        $strSQL = $this->hhaizokuInsSql($postData);
        return parent::insert($strSQL);
    }
    public function hsyainMstSel($postData)
    {
        $strSQL = $this->hsyainMstSelSql($postData);
        return parent::select($strSQL);
    }
    public function hsyainMstUpd($postData)
    {
        $strSQL = $this->hsyainMstUpdSql($postData);
        return parent::update($strSQL);
    }

    public function hsyainMstIns($postData)
    {
        $strSQL = $this->hsyainMstInsSql($postData);
        return parent::insert($strSQL);
    }
    public function FncGetSyainMstValueSql($postData)
    {
        $strSQL = "SELECT";
        $strSQL .= "     SYA.SYAIN_NO" . "\r\n";
        $strSQL .= ",     LOG.USER_ID" . "\r\n";
        $strSQL .= ",    SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",    HAI.BUSYO_CD" . "\r\n";
        $strSQL .= ",    LOG.PASSWORD" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "      HSYAINMST SYA" . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "      HHAIZOKU HAI" . "\r\n";
        $strSQL .= " ON " . "\r\n";
        $strSQL .= "      HAI.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD')" . "\r\n";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD')" . "\r\n";
        $strSQL .= " LEFT JOIN " . "\r\n";
        $strSQL .= "      M_LOGIN LOG" . "\r\n";
        $strSQL .= " ON " . "\r\n";
        $strSQL .= "      LOG.USER_ID = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      LOG.SYS_KB = '@SYS_KB' " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "      SYA.TAISYOKU_DATE IS NULL" . "\r\n";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      SYA.SYAIN_NO = '@SYAIN_NO' " . "\r\n";

        $strSQL = str_replace("@SYS_KB", $postData['SYS_KB'], $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $postData['SYAIN_NO'], $strSQL);

        return $strSQL;
    }

    public function mLoginSelSql($postData)
    {
        $strSQL = "SELECT";
        $strSQL .= "     *";
        $strSQL .= " FROM";
        $strSQL .= "      M_LOGIN";
        $strSQL .= " WHERE ";
        $strSQL .= "      SYS_KB = '@SYS_KB' ";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      USER_ID = '@USER_ID' " . "\r\n";

        $strSQL = str_replace("@SYS_KB", $postData['SYS_KB'], $strSQL);
        $strSQL = str_replace("@USER_ID", $postData['SYAIN_NO'], $strSQL);

        return $strSQL;
    }

    public function mLoginUpdSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE M_LOGIN " . "\r\n";
        $strSQL .= "SET " . "\r\n";
        $strSQL .= " PASSWORD = '@PASSWORD' " . "\r\n";
        $strSQL .= ",STYLE_ID = '@STYLE_ID' " . "\r\n";
        $strSQL .= ",PATTERN_ID = '@PATTERN_ID' " . "\r\n";
        $strSQL .= ",REC_UPD_DT = TO_DATE('@UPD_DATE','YYYY-MM-DD HH24:MI:SS') " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " SYS_KB = '@SYS_KB' " . "\r\n";
        $strSQL .= " AND USER_ID = '@USER_ID' " . "\r\n";


        $strSQL = str_replace("@SYS_KB", $postData['SYS_KB'], $strSQL);
        $strSQL = str_replace("@USER_ID", $postData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@PASSWORD", $postData['PASSWORD'], $strSQL);
        $strSQL = str_replace("@STYLE_ID", $postData['STYLE_ID'], $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $postData['PATTERN_ID'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", $postData['SYSDATE'], $strSQL);

        return $strSQL;
    }

    public function mLoginInsSql($postData)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO M_LOGIN(" . "\r\n";
        $strSQL .= "      SYS_KB" . "\r\n";
        $strSQL .= ",     USER_ID" . "\r\n";
        $strSQL .= ",     PASSWORD" . "\r\n";
        $strSQL .= ",     STYLE_ID" . "\r\n";
        $strSQL .= ",     PATTERN_ID" . "\r\n";
        $strSQL .= ",     REC_UPD_DT" . "\r\n";
        $strSQL .= ",     REC_CRE_DT" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "      '@SYS_KB'" . "\r\n";
        $strSQL .= ",     '@USER_ID'" . "\r\n";
        $strSQL .= ",     '@PASSWORD'" . "\r\n";
        $strSQL .= ",     '@STYLE_ID'" . "\r\n";
        $strSQL .= ",     '@PATTERN_ID'" . "\r\n";
        $strSQL .= ",     TO_DATE('@REC_UPD_DT','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     TO_DATE('@REC_CRE_DT','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     'HDKLoginEdit'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYS_KB", $postData['SYS_KB'], $strSQL);
        $strSQL = str_replace("@USER_ID", $postData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@PASSWORD", $postData['PASSWORD'], $strSQL);
        $strSQL = str_replace("@STYLE_ID", $postData['STYLE_ID'], $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $postData['PATTERN_ID'], $strSQL);
        $strSQL = str_replace("@REC_UPD_DT", $postData['SYSDATE'], $strSQL);
        $strSQL = str_replace("@REC_CRE_DT", $postData['SYSDATE'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return $strSQL;
    }
    public function hhaizokuSelSql($postData)
    {
        $strSQL = "SELECT";
        $strSQL .= "     *";
        $strSQL .= " FROM";
        $strSQL .= "      HHAIZOKU";
        $strSQL .= " WHERE ";
        $strSQL .= "      SYAIN_NO = '@SYAIN_NO' ";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      BUSYO_CD = '@BUSYO_CD' " . "\r\n";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      END_DATE IS NULL " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $postData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postData['BUSYO_CD'], $strSQL);

        return $strSQL;
    }

    public function hhaizokuUpdSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HHAIZOKU " . "\r\n";
        $strSQL .= "SET " . "\r\n";
        $strSQL .= " END_DATE = '@END_DATE' " . "\r\n";
        $strSQL .= " WHERE ";
        $strSQL .= "      SYAIN_NO = '@SYAIN_NO' ";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      BUSYO_CD <> '@BUSYO_CD' " . "\r\n";
        $strSQL .= " AND " . "\r\n";
        $strSQL .= "      END_DATE IS NULL " . "\r\n";


        $strSQL = str_replace("@SYAIN_NO", $postData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postData['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@END_DATE", $postData['END_DATE'], $strSQL);

        return $strSQL;
    }
    public function hhaizokuInsSql($postData)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HHAIZOKU(" . "\r\n";
        $strSQL .= "      SYAIN_NO" . "\r\n";
        $strSQL .= ",     RIR_NO" . "\r\n";
        $strSQL .= ",     BUSYO_CD" . "\r\n";
        $strSQL .= ",     SYUKEI_BUSYO_CD" . "\r\n";
        $strSQL .= ",     START_DATE" . "\r\n";
        $strSQL .= ",     SYOKUSYU_KB" . "\r\n";
        $strSQL .= ",     IVENT_TARGET_FLG" . "\r\n";
        $strSQL .= ",     UPD_DATE" . "\r\n";
        $strSQL .= ",     CREATE_DATE" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ",     UPD_PRG_ID" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "      '@SYAIN_NO'" . "\r\n";
        $strSQL .= ",     (SELECT NVL(MAX(RIR_NO), -1) + 1 FROM HHAIZOKU WHERE SYAIN_NO = '@SYAIN_NO')" . "\r\n";
        $strSQL .= ",     '@BUSYO_CD'" . "\r\n";
        $strSQL .= ",     '@BUSYO_CD'" . "\r\n";
        $strSQL .= ",     '@START_DATE'" . "\r\n";
        $strSQL .= ",     '9'" . "\r\n";
        $strSQL .= ",     '1'" . "\r\n";
        $strSQL .= ",     TO_DATE('@UPD_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     TO_DATE('@CREATE_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     'HDKLoginEdit'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $postData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postData['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@START_DATE", $postData['DATEYMD'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", $postData['SYSDATE'], $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $postData['SYSDATE'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return $strSQL;
    }

    public function hsyainMstSelSql($postData)
    {
        $strSQL = "SELECT";
        $strSQL .= "     *";
        $strSQL .= " FROM";
        $strSQL .= "      HSYAINMST";
        $strSQL .= " WHERE ";
        $strSQL .= "      SYAIN_NO = '@SYAIN_NO' ";

        $strSQL = str_replace("@SYAIN_NO", $postData['SYAIN_NO'], $strSQL);

        return $strSQL;
    }

    public function hsyainMstUpdSql($postData)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HSYAINMST " . "\r\n";
        $strSQL .= "SET " . "\r\n";
        $strSQL .= " SYAIN_NM = '@SYAIN_NM' " . "\r\n";
        $strSQL .= ",SYAIN_KN = NULL " . "\r\n";
        $strSQL .= ",SIKAKU_CD = '07' " . "\r\n";
        $strSQL .= ",SLSSUTAFF_KB = '9' " . "\r\n";
        $strSQL .= ",UPD_DATE = TO_DATE('@UPD_DATE','YYYY-MM-DD HH24:MI:SS') " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= " SYAIN_NO = '@SYAIN_NO' " . "\r\n";


        $strSQL = str_replace("@SYAIN_NO", $postData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@SYAIN_NM", $postData['SYAIN_NM'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", $postData['SYSDATE'], $strSQL);

        return $strSQL;
    }
    public function hsyainMstInsSql($postData)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HSYAINMST(" . "\r\n";
        $strSQL .= "      SYAIN_NO" . "\r\n";
        $strSQL .= ",     SYAIN_NM" . "\r\n";
        $strSQL .= ",     SIKAKU_CD" . "\r\n";
        $strSQL .= ",     SLSSUTAFF_KB" . "\r\n";
        $strSQL .= ",     UPD_DATE" . "\r\n";
        $strSQL .= ",     CREATE_DATE" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "      '@SYAIN_NO'" . "\r\n";
        $strSQL .= ",     '@SYAIN_NM'" . "\r\n";
        $strSQL .= ",     '07'" . "\r\n";
        $strSQL .= ",     '9'" . "\r\n";
        $strSQL .= ",     TO_DATE('@UPD_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     TO_DATE('@CREATE_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $postData['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@SYAIN_NM", $postData['SYAIN_NM'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", $postData['SYSDATE'], $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $postData['SYSDATE'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return $strSQL;
    }
}
