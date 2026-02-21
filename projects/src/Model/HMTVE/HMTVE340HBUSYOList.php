<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE340HBUSYOList extends ClsComDb
{
    function btnSearchClickSql($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT BUSYO_CD , BUSYO_NM , BUSYO_KANANM FROM HBUSYO WHERE 1=1 " . "\r\n";

        if ($postData['txtID'] != '') {
            $strSQL .= " AND  BUSYO_CD LIKE '@BUSYOCD%'" . "\r\n";
        }

        if ($postData['txtName'] != '') {
            $strSQL .= "  AND    BUSYO_KANANM LIKE '@BUSYOKN%'" . "\r\n";
        }

        $strSQL .= "  ORDER BY BUSYO_CD " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $postData['txtID'], $strSQL);
        $strSQL = str_replace("@BUSYOKN", $postData['txtName'], $strSQL);

        return $strSQL;
    }

    public function btnSearch_Click($postData)
    {
        $strSql = $this->btnSearchClickSql($postData);

        return parent::select($strSql);
    }

}
