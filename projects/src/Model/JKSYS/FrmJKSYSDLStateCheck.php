<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmJKSYSDLStateCheck extends ClsComDb
{
    public function fncHFTS_TRANSFER_LIST_Sel()
    {
        $strSQL = "";
        $strSQL .= " SELECT PARA2, (ID || START_DATE || START_TIME || CLIENT_NAME) FILE_NM" . "\r\n";
        $strSQL .= ",       SUBSTR(START_DATE,1,4) || '/' || SUBSTR(START_DATE,5,2) || '/' || SUBSTR(START_DATE,7,2) ||  ' ' || SUBSTR(START_TIME,1,2) || ':' || SUBSTR(START_TIME,3,2) || ':' || SUBSTR(START_TIME,5,2) DT" . "\r\n";
        $strSQL .= ",       STEP" . "\r\n";
        $strSQL .= ",       (CASE WHEN STATE = '0' THEN '実行中'" . "\r\n";
        $strSQL .= "              WHEN STATE = '1' THEN '終了'" . "\r\n";
        $strSQL .= "              WHEN STATE = '3' THEN '不備データあり'" . "\r\n";
        $strSQL .= "              WHEN STATE = '8' THEN 'エラー' END) STATE" . "\r\n";
        //20201113 YIN UPD S
        // $strSQL .= ",       MESSAGE" . "\r\n";
        $strSQL .= ",       REPLACE(MESSAGE,chr(10),'') AS MESSAGE" . "\r\n";
        //20201113 YIN UPD E
        $strSQL .= " FROM   HFTS_TRANSFER_LIST" . "\r\n";
        $strSQL .= " WHERE  CLIENT_NAME = '@CLIENTNM'" . "\r\n";
        $strSQL .= " AND    KAKUNIN = '0'" . "\r\n";
        $strSQL .= " ORDER BY START_DATE DESC, START_TIME DESC" . "\r\n";

        $strSQL = str_replace("@CLIENTNM", "00000", $strSQL);

        return parent::select($strSQL);
    }

    public function fncHFTS_TRANSFER_LIST_Upd($value)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HFTS_TRANSFER_LIST" . "\r\n";
        $strSQL .= "SET    KAKUNIN = '1'" . "\r\n";
        $strSQL .= "WHERE  ID = '@ID'" . "\r\n";
        $strSQL .= "AND    START_DATE = '@STARTDATE'" . "\r\n";
        $strSQL .= "AND    START_TIME = '@STARTTIME'" . "\r\n";
        $strSQL .= "AND    CLIENT_NAME = '@CLIENTNM'" . "\r\n";

        $strSQL = str_replace("@ID", substr($value, 0, 3), $strSQL);
        $strSQL = str_replace("@STARTDATE", substr($value, 3, 8), $strSQL);
        $strSQL = str_replace("@STARTTIME", substr($value, 11, 6), $strSQL);
        $strSQL = str_replace("@CLIENTNM", substr($value, 17), $strSQL);

        return parent::update($strSQL);
    }

}
