<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：KoukaType
// * 関数名	：KoukaType
// * 処理説明	：共通クラスの読込み
//*************************************
class KoukaType extends ClsComDb
{
    public function SetComboBoxSql()
    {
        //--- 初期化 ---
        $strSQL = "";
        //--- SELECT ---
        $strSQL .= " SELECT DISTINCT KOUKATYPE_CD" . "\r\n";
        $strSQL .= "               , KOUKATYPE_NM" . "\r\n";
        $strSQL .= "               , 1 SKEY" . "\r\n";
        $strSQL .= "   FROM JKKOUKA_TYPE_MST" . "\r\n";

        //ブランク用
        $strSQL .= " UNION ALL " . "\r\n";
        $strSQL .= " SELECT '' as KOUKATYPE_CD" . "\r\n";
        $strSQL .= "      , '' as  KOUKATYPE_NM" . "\r\n";
        $strSQL .= "      , 0 SKEY" . "\r\n";
        $strSQL .= "   FROM DUAL" . "\r\n";

        $strSQL .= "   ORDER BY SKEY" . "\r\n";
        $strSQL .= "        ,KOUKATYPE_CD" . "\r\n";

        return parent::select($strSQL);
    }

}
