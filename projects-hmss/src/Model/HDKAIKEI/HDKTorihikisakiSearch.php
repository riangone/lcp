<?php
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKTorihikisakiSearch extends ClsComDb
{
    public $ClsComFncHDKAIKEI = null;
    function FncGetSql_TORIHIKI($postData)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = " SELECT ";
        $strSQL .= "        TORIHIKISAKI_CD";
        $strSQL .= ",       TORIHIKISAKI_NAME";
        $strSQL .= ",       TORIHIKISAKI_KANA";
        $strSQL .= " FROM ";
        $strSQL .= "        HDK_MST_TORIHIKISAKI ";
        $strSQL .= " WHERE ";
        $strSQL .= "        1=1 ";

        if ($postData['txtTorihikiCode'] != '') {
            $strSQL .= " AND   TORIHIKISAKI_CD      = '@TORIHIKISAKI_CD' ";
            $strSQL = str_replace("@TORIHIKISAKI_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtTorihikiCode']), $strSQL);
        }

        if ($postData['txtTorihikiName'] != '') {
            $strSQL .= "  AND   TORIHIKISAKI_NAME   LIKE '@ATO_DTRPITNMONE%'";
            $strSQL = str_replace("@ATO_DTRPITNMONE", $this->ClsComFncHDKAIKEI->FncNv($postData['txtTorihikiName']), $strSQL);
        }

        if ($postData['txtTorihikiKana'] != '') {
            $strSQL .= " AND   TORIHIKISAKI_KANA    LIKE '@TORIHIKIKANA%'";
            $strSQL = str_replace("@TORIHIKIKANA", $this->ClsComFncHDKAIKEI->FncNv($postData['txtTorihikiKana']), $strSQL);
        }
        $strSQL .= " ORDER BY ";
        $strSQL .= "        TORIHIKISAKI_CD  ";

        return $strSQL;

    }

    //取引先データを取得
    public function btnHyouji_Click($postData)
    {
        $strSql = $this->FncGetSql_TORIHIKI($postData);

        return parent::select($strSql);
    }

}
