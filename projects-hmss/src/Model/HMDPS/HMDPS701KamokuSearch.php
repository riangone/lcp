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
class HMDPS701KamokuSearch extends ClsComDb
{
    public $ClsComFncHMDPS;

    function FncGetSql_KAMOKU($postData)
    {
        $this->ClsComFncHMDPS = new ClsComFncHMDPS();

        // 10:伝票検索画面から値が伝わってきたのです。
        if ($postData['str'] == '10') {
            $strSQL = " SELECT DISTINCT ";
            $strSQL .= "        KAMOK_CD";
            $strSQL .= ",      'k' as KOUMK_CD";
            $strSQL .= ",       KAMOK_SSK_NM as KMK_KUM_NM";
            $strSQL .= " FROM ";
            $strSQL .= "        M29FZ6 ";
            $strSQL .= " WHERE ";
            $strSQL .= "        1=1 ";
        } else {
            $strSQL = " SELECT ";
            $strSQL .= "        KAMOK_CD";
            $strSQL .= ",       KOUMK_CD";
            $strSQL .= ",       case when trim(nvl(KOUMK_CD,'')) IS NULL then KAMOK_SSK_NM else  KMK_KUM_NM end as KMK_KUM_NM";
            $strSQL .= " FROM ";
            $strSQL .= "        M29FZ6 ";
            $strSQL .= " WHERE ";
            $strSQL .= "        1=1 ";
        }
        if (trim($postData['txtKamokuCode']) != '') {
            $strSQL .= " AND   KAMOK_CD      LIKE '@KAMOKUCODE%'";
            $strSQL = str_replace("@KAMOKUCODE", $this->ClsComFncHMDPS->FncNv($postData['txtKamokuCode']), $strSQL);
        }

        if (trim($postData['txtKoumokuCode']) != '') {
            $strSQL .= " AND   KOUMK_CD      = '@KOUMOKUCODE' ";
            $strSQL = str_replace("@KOUMOKUCODE", $this->ClsComFncHMDPS->FncNv($postData['txtKoumokuCode']), $strSQL);
        }

        if (trim($postData['txtKamokuName']) != '') {
            // 10:伝票検索画面から値が伝わってきたのです。
            if ($postData['str'] == '10') {
                $strSQL .= " AND   KAMOK_SSK_NM    LIKE '%@KAMOKUNAME%'";
            } else {
                $strSQL .= " AND   KMK_KUM_NM      LIKE '%@KAMOKUNAME%'";

            }
            $strSQL = str_replace("@KAMOKUNAME", $this->ClsComFncHMDPS->FncNv($postData['txtKamokuName']), $strSQL);
        }
        $strSQL .= " ORDER BY ";
        $strSQL .= "        KAMOK_CD,KOUMK_CD ";

        return $strSQL;

    }

    //データを取得
    public function btnHyouji_Click($postData)
    {
        $strSql = $this->FncGetSql_KAMOKU($postData);

        return parent::select($strSql);
    }

}
