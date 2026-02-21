<?php
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKSyainMstEdit extends ClsComDb
{
    public $ClsComFncHDKAIKEI = null;
    public function userDataDel($tableName, $userId)
    {
        $strSQL = $this->userDataDelSql($tableName, $userId);
        return parent::delete($strSQL);
    }
    public function mLoginIns($params)
    {
        $strSQL = $this->mLoginInsSql($params);
        return parent::insert($strSQL);
    }
    public function hsyainmstIns($params)
    {
        $strSQL = $this->hsyainmstInsSql($params);
        return parent::insert($strSQL);
    }
    public function hhaizokuIns($params)
    {
        $strSQL = $this->hhaizokuInsSql($params);
        return parent::insert($strSQL);
    }
    public function userDataDelSql($tableName, $userId)
    {
        if ($tableName === 'M_LOGIN') {
            $strSQL = "DELETE FROM @table WHERE USER_ID = '@USER_ID'";
        } else {
            $strSQL = "DELETE FROM @table WHERE SYAIN_NO = '@USER_ID'";
        }
        $strSQL = str_replace("@table", $tableName, $strSQL);
        $strSQL = str_replace("@USER_ID", $userId, $strSQL);
        return $strSQL;
    }
    public function mLoginInsSql($params)
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
        $strSQL .= ",     'SyainMstEdit'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYS_KB", $params['SYS_KB'], $strSQL);
        $strSQL = str_replace("@USER_ID", $params['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@PASSWORD", $params['PASSWORD'], $strSQL);
        $strSQL = str_replace("@STYLE_ID", $params['STYLE_ID'], $strSQL);
        $strSQL = str_replace("@PATTERN_ID", $params['PATTERN_ID'], $strSQL);
        $strSQL = str_replace("@REC_UPD_DT", $params['SYSDATE'], $strSQL);
        $strSQL = str_replace("@REC_CRE_DT", $params['SYSDATE'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return $strSQL;
    }
    public function hsyainmstInsSql($params)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HSYAINMST(" . "\r\n";
        $strSQL .= "      SYAIN_NO" . "\r\n";
        $strSQL .= ",     SYAIN_NM" . "\r\n";
        $strSQL .= ",     SYAIN_KN" . "\r\n";
        $strSQL .= ",     SIKAKU_CD" . "\r\n";
        $strSQL .= ",     SLSSUTAFF_KB" . "\r\n";
        $strSQL .= ",     UPD_DATE" . "\r\n";
        $strSQL .= ",     CREATE_DATE" . "\r\n";
        $strSQL .= ",     UPD_SYA_CD" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "      '@SYAIN_NO'" . "\r\n";
        $strSQL .= ",     '@SYAIN_NM'" . "\r\n";
        $strSQL .= ",     '@SYAIN_KN'" . "\r\n";
        $strSQL .= ",     '07'" . "\r\n";
        $strSQL .= ",     '9'" . "\r\n";
        $strSQL .= ",     TO_DATE('@UPD_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     TO_DATE('@CREATE_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $params['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@SYAIN_NM", $params['SYAIN_NM'], $strSQL);
        $strSQL = str_replace("@SYAIN_KN", $params['SYAIN_KN'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", $params['SYSDATE'], $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $params['SYSDATE'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return $strSQL;
    }
    public function hhaizokuInsSql($params)
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
        $strSQL .= ",     '1'" . "\r\n";
        $strSQL .= ",     '@BUSYO_CD'" . "\r\n";
        $strSQL .= ",     '@BUSYO_CD'" . "\r\n";
        $strSQL .= ",     '@START_DATE'" . "\r\n";
        $strSQL .= ",     '7'" . "\r\n";
        $strSQL .= ",     '1'" . "\r\n";
        $strSQL .= ",     TO_DATE('@UPD_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     TO_DATE('@CREATE_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= ",     '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",     'SyainMstEdit'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $params['SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $params['BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@START_DATE", $params['DATEYMD'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", $params['SYSDATE'], $strSQL);
        $strSQL = str_replace("@CREATE_DATE", $params['SYSDATE'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return $strSQL;
    }
}
