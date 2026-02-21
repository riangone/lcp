<?php
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
// 共通クラスの読込み
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKCreatBusyoSearch extends ClsComDb
{
    public $ClsComFncHDKAIKEI = null;
    function getDeployDataSQL($postData)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = "SELECT";
        $strSQL .= "     BUS.BUSYO_CD";
        $strSQL .= ",    BUS.BUSYO_NM";
        $strSQL .= " FROM ";
        $strSQL .= "      HBUSYO BUS";
        $strSQL .= "      WHERE 1=1";

        if ($postData['txtDeployCode'] != '') {
            $strSQL .= " AND   BUS.BUSYO_CD      = '@BUSYO_CD' ";
            $strSQL = str_replace("@BUSYO_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtDeployCode']), $strSQL);
        }
        if ($postData['txtdeployName'] != '') {
            $strSQL .= "  AND   BUS.BUSYO_NM   LIKE '@BUSYO_NM%'";
            $strSQL = str_replace("@BUSYO_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtdeployName']), $strSQL);
        }
        if ($postData['txtdeployKN'] != '') {
            $strSQL .= "   AND   BUS.BUSYO_KANANM LIKE '@BUSYO_KANA%'";
            $strSQL = str_replace("@BUSYO_KANA", $this->ClsComFncHDKAIKEI->FncNv($postData['txtdeployKN']), $strSQL);
        }

        $strSQL .= "  ORDER BY ";
        $strSQL .= "       BUS.BUSYO_CD ";

        return $strSQL;
    }

    //部署データを取得
    public function btnView_Click($postData)
    {
        $strSql = $this->getDeployDataSQL($postData);

        return parent::select($strSql);
    }

}
