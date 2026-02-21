<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmJKSYSBusyoSearch extends ClsComDb
{
    public $ClsComFncJKSYS;
    function fncDataSetSql($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "SELECT";
        $strSQL .= "     NVL(BUSYO_CD,'') AS BUSYOCD";
        $strSQL .= "    ,NVL(BUSYO_NM,'') AS BUSYONM";
        $strSQL .= " FROM JKBUMON";
        $strSQL .= " WHERE 1 = 1";

        //---部署名---
        if (trim($postData['txtBusyoNM']) != '') {
            $strSQL .= "   AND BUSYO_NM LIKE '@BUSYO%'";
        }
        if (trim($postData['txtBusyoCD']) != '') {
            $strSQL .= "   AND BUSYO_CD LIKE '@CD%'";
        }

        $strSQL .= " ORDER BY BUSYO_CD";

        //---検索条件---
        $strSQL = str_replace("@BUSYO", $this->ClsComFncJKSYS->FncNv($postData['txtBusyoNM']), $strSQL);
        $strSQL = str_replace("@CD", $this->ClsComFncJKSYS->FncNv($postData['txtBusyoCD']), $strSQL);

        return $strSQL;
    }

    public function fncDataSet($postData)
    {
        $strSql = $this->fncDataSetSql($postData);
        //---SQL発行---
        return parent::select($strSql);
    }

}
