<?php
// 共通クラスの読込み
namespace App\Model\HMDPS;

use App\Model\Component\ClsComDb;
use App\Model\HMDPS\Component\ClsComFncHMDPS;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMDPS702BusyoSearch extends ClsComDb
{
    public $ClsComFncHMDPS;
    function getDeployDataSQL($postData)
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();

        $strSQL = "SELECT";
        $strSQL .= "     BUS.BUSYO_CD";
        $strSQL .= ",    BUS.BUSYO_NM";
        $strSQL .= " FROM ";
        $strSQL .= "      HBUSYO BUS";
        $strSQL .= " WHERE ";
        $strSQL .= "       1=1 ";
        if ($postData['txtDeployCode'] != '') {
            $strSQL .= " AND   BUS.BUSYO_CD      = '@BUSYO_CD' ";
            $strSQL = str_replace("@BUSYO_CD", $this->ClsComFncHMDPS->FncNv($postData['txtDeployCode']), $strSQL);
        }
        if ($postData['txtdeployName'] != '') {
            $strSQL .= "  AND   BUS.BUSYO_NM   LIKE '@BUSYO_NM%'";
            $strSQL = str_replace("@BUSYO_NM", $this->ClsComFncHMDPS->FncNv($postData['txtdeployName']), $strSQL);
        }
        if ($postData['txtdeployKN'] != '') {
            $strSQL .= "   AND   BUS.BUSYO_KANANM LIKE '@BUSYO_KANA%'";
            $strSQL = str_replace("@BUSYO_KANA", $this->ClsComFncHMDPS->FncNv($postData['txtdeployKN']), $strSQL);
        }

        if ($postData["rdo"] == "rdoSin") {
            $strSQL .= "   AND   BUS.BUSYO_KB = 'S'";
        } else
            if ($postData["rdo"] == 'rdoTyu') {
                $strSQL .= "   AND   BUS.BUSYO_KB = 'C' ";
            } else {
                $strSQL .= "   AND   NVL(BUS.BUSYO_KB,'F') = 'F' ";
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
