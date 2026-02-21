<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE300HSYAINMSTList extends ClsComDb
{
    function BindGridViewData($postData)
    {
        $strWhere = "WHERE ";

        $strSQL = "";
        $strSQL .= " SELECT SYA.SYAIN_NO " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_NM " . "\r\n";
        $strSQL .= " ,      SYA.SYAIN_KN " . "\r\n";
        $strSQL .= " ,      BUS.BUSYO_RYKNM " . "\r\n";
        $strSQL .= " ,      BUS.BUSYO_CD " . "\r\n";
        $strSQL .= " ,      SYA.UPD_PRG_ID " . "\r\n";
        $strSQL .= " FROM   HSYAINMST SYA " . "\r\n";
        $strSQL .= " LEFT JOIN HHAIZOKU HAI " . "\r\n";
        $strSQL .= " ON     SYA.SYAIN_NO = HAI.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    NVL(HAI.START_DATE,'00000000') <= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " AND	NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " LEFT JOIN HBUSYO BUS " . "\r\n";
        $strSQL .= " ON	   BUS.BUSYO_CD = HAI.BUSYO_CD " . "\r\n";

        if ($postData['txtDispose'] != '') {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "    HAI.BUSYO_CD = '@DISPOSE' " . "\r\n";
            $strSQL = str_replace("@DISPOSE", $postData['txtDispose'], $strSQL);
            $strWhere = "AND";
        }

        if ($postData['txtNumber'] != '') {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "  SYA.SYAIN_NO = '@NUMBER' " . "\r\n";
            $strSQL = str_replace("@NUMBER", $postData['txtNumber'], $strSQL);
            $strWhere = "AND";
        }

        if ($postData['txtName'] != '') {
            $strSQL .= $strWhere . "\r\n";
            $strSQL .= "    SYA.SYAIN_KN LIKE '@NAME%'   " . "\r\n";
            $strSQL = str_replace("@NAME", $postData['txtName'], $strSQL);
        }

        $strSQL .= "  ORDER BY SYA.SYAIN_NO " . "\r\n";

        return $strSQL;
    }

    public function btnSearch_Click($postData)
    {
        $strSql = $this->BindGridViewData($postData);

        return parent::select($strSql);
    }

}
