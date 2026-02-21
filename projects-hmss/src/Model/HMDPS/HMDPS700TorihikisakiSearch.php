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
class HMDPS700TorihikisakiSearch extends ClsComDb
{
    public $ClsComFncHMDPS;
    function FncGetSql_TORIHIKI($postData)
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();

        $strSQL = " SELECT ";
        $strSQL .= "        ATO_DTRPITCD";
        $strSQL .= ",       ATO_DTRPITNM1";
        $strSQL .= ",       ATO_DTRPITNM2";
        $strSQL .= ",       ATO_DTRPTBNM";
        $strSQL .= ",       ATO_DTRPIKNM";
        $strSQL .= " FROM ";
        $strSQL .= "        M28M68 ";
        $strSQL .= " WHERE ";
        $strSQL .= "        1=1 ";

        if ($postData['txtTorihikiCode'] != '') {
            $strSQL .= " AND   ATO_DTRPITCD      = '@ATO_DTRPITCD' ";
            $strSQL = str_replace("@ATO_DTRPITCD", $this->ClsComFncHMDPS->FncNv($postData['txtTorihikiCode']), $strSQL);
        }

        if ($postData['txtTorihikiName'] != '') {
            $strSQL .= "  AND   (ATO_DTRPITNM1   LIKE '@ATO_DTRPITNMONE%'";
            $strSQL .= "  OR    ATO_DTRPITNM2   LIKE '@ATO_DTRPITNMTWO%') ";
            $strSQL = str_replace("@ATO_DTRPITNMONE", $this->ClsComFncHMDPS->FncNv($postData['txtTorihikiName']), $strSQL);
            $strSQL = str_replace("@ATO_DTRPITNMTWO", $this->ClsComFncHMDPS->FncNv($postData['txtTorihikiName']), $strSQL);
        }

        if ($postData['txtTorihikiKana'] != '') {
            $strSQL .= " AND   ATO_DTRPIKNM    LIKE '@TORIHIKIKANA%'";
            $strSQL = str_replace("@TORIHIKIKANA", $this->ClsComFncHMDPS->FncNv($postData['txtTorihikiKana']), $strSQL);
        }
        $strSQL .= " ORDER BY ";
        $strSQL .= "        ATO_DTRPITCD  ";

        return $strSQL;

    }

    //取引先データを取得
    public function btnHyouji_Click($postData)
    {
        $strSql = $this->FncGetSql_TORIHIKI($postData);

        return parent::select($strSql);
    }

}
