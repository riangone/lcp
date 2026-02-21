<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSyainMstList extends ClsComDb
{
    //---execute---
    public function fncFromSyainSelect($postData)
    {
        $strSql = $this->fncFromSyainSelect_sql($postData);
        return parent::select($strSql);
    }

    //--sql---
    public function fncFromSyainSelect_sql($postData)
    {
        $where = "";
        $sqlstr = "";
        $sqlstr .= "SELECT SYA.SYAIN_NO  \n";
        $sqlstr .= ",      SYA.SYAIN_NM  \n";
        $sqlstr .= ",      SYA.SYAIN_KN  \n";
        $sqlstr .= "FROM   HSYAINMST SYA  \n";
        $sqlstr .= "LEFT JOIN  \n";
        $sqlstr .= "       HKEIRICTL KRI  \n";
        $sqlstr .= "ON KRI.ID = '01'  \n";
        $sqlstr .= "LEFT JOIN HHAIZOKU HAI  \n";
        $sqlstr .= "ON     SYA.SYAIN_NO = HAI.SYAIN_NO  \n";
        $sqlstr .= "AND    HAI.START_DATE <= TO_CHAR(LAST_DAY(TO_DATE(KRI.SYR_YMD || '01','YYYYMMDD')),'YYYYMMDD')  \n";
        $sqlstr .= "AND    NVL(HAI.END_DATE,'99999999')>= TO_CHAR(LAST_DAY(TO_DATE(KRI.SYR_YMD || '01','YYYYMMDD')),'YYYYMMDD')  \n";

        if (trim($postData["txtSyainNO"]) != "") {
            $where .= " WHERE SYA.SYAIN_NO LIKE '";
            $where .= $postData["txtSyainNO"];
            $where .= "%'\n";
        }

        if (trim($postData["txtSyainNM"]) != "") {
            if ($where != "") {
                $where .= " AND ";
            } else {
                $where .= " WHERE ";
            }
            $where .= " SYA.SYAIN_KN LIKE '";
            $where .= $postData["txtSyainNM"];
            $where .= "%' \n";
        }

        if (trim($postData["txtBusyoCD"]) != "") {
            if ($where != "") {
                $where .= " AND ";
            } else {
                $where .= " WHERE ";
            }
            $where .= " HAI.BUSYO_CD = '";
            $where .= $postData["txtBusyoCD"];
            $where .= "' \n";
        }

        if ($postData["chkTaisyoku"] == "TRUE" || $postData["chkTaisyoku"] == "true") {
            if ($where != "") {
                $where .= " AND ";
            } else {
                $where .= " WHERE ";
            }
            $where .= "SYA.TAISYOKU_DATE IS NULL";
        }
        $sqlstr .= $where;
        $sqlstr .= " ORDER BY SYAIN_NO ";
        return $sqlstr;
    }

    public function fncTxtBusyoCDValidating()
    {

    }

}