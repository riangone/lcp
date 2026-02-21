<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            GSDL
 * -------------------------------------------------------------------------------------------------------
 */
namespace App\Model\HDKAIKEI;
// 共通クラスの読込み
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKBankSearch extends ClsComDb
{

    public $ClsComFncHDKAIKEI = null;
    function FncGetSql_BAKN($postData)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = " SELECT DISTINCT ";
        $strSQL .= "        BANK_CD";
        $strSQL .= ",       BRANCH_CD";
        $strSQL .= ",       BANK_NM";
        $strSQL .= ",       BRANCH_NM";
        $strSQL .= " FROM ";
        $strSQL .= "        HDK_MST_BANK ";
        $strSQL .= " WHERE ";
        $strSQL .= "        DEL_DATE IS NULL ";

        if (isset($postData['txtBankCode']) && trim($postData['txtBankCode']) != '') {
            $strSQL .= " AND   BANK_CD      LIKE '@BANK_CD%'";
            $strSQL = str_replace("@BANK_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtBankCode']), $strSQL);
        }

        if (isset($postData['txtBranchCode']) && trim($postData['txtBranchCode']) != '') {
            $strSQL .= " AND   BRANCH_CD      LIKE '@BRANCH_CD%' ";
            $strSQL = str_replace("@BRANCH_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtBranchCode']), $strSQL);
        }

        if (isset($postData['txtBankName']) && trim($postData['txtBankName']) != '') {
            $strSQL .= " AND   BANK_NM    LIKE '%@BANK_NM%'";
            $strSQL = str_replace("@BANK_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtBankName']), $strSQL);
        }
        if (isset($postData['txtBranchName']) && trim($postData['txtBranchName']) != '') {
            $strSQL .= " AND   BRANCH_NM      LIKE '%@BRANCH_NM%' ";
            $strSQL = str_replace("@BRANCH_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtBranchName']), $strSQL);
        }
        $strSQL .= " ORDER BY ";
        $strSQL .= "        BANK_CD,BRANCH_CD";

        return $strSQL;
    }
    //データを取得
    public function btnHyouji_Click($postData = array())
    {
        $strSql = $this->FncGetSql_BAKN($postData);

        return parent::select($strSql);
    }
}
