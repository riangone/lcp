<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmFurikae extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";


    public function ControlCheckSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT ID FROM HKEIRICTL WHERE ID = '01'" . "\r\n";

        return $strSQL;

    }

    function fncSearchFurikaeSql($postData = NULL)
    {
        $strWHERE = " WHERE ";
        $strSQL = "";
        $strSQL .= "SELECT V.KEIJO_DT" . "\r\n";
        $strSQL .= ",      V.DENPY_NO" . "\r\n";
        $strSQL .= ",      MAX(V.KAMOK_CD) KAMOK_CD" . "\r\n";
        $strSQL .= ",      MAX(V.KAMOKNM) KAMOKNM" . "\r\n";
        $strSQL .= ",      MAX(V.AITE_KMK_CD) AITE_KMK_CD" . "\r\n";
        $strSQL .= ",      MAX(V.AITE_KMK_NM) AITE_KMK_NM" . "\r\n";
        $strSQL .= ",      MAX(V.KEIJO_GK) KEIJO_GK" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "        SELECT FR.KEIJO_DT" . "\r\n";
        $strSQL .= "        ,      FR.DENPY_NO" . "\r\n";
        $strSQL .= "        ,      FR.KAMOK_CD" . "\r\n";
        $strSQL .= "        ,      (DECODE(KH.KAMOKUMEI,NULL,M_KMK.KAMOKUMEI,KH.KAMOKUMEI)) KAMOKNM" . "\r\n";
        $strSQL .= "        ,      NULL AITE_KMK_CD" . "\r\n";
        $strSQL .= "        ,      NULL AITE_KMK_NM" . "\r\n";
        $strSQL .= "        ,      FR.KEIJO_GK" . "\r\n";
        $strSQL .= "        FROM  HFURIKAE FR" . "\r\n";
        $strSQL .= "        LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, (KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') KH" . "\r\n";
        $strSQL .= "        ON        KH.KAMOK_CD = FR.KAMOK_CD" . "\r\n";
        $strSQL .= "        AND       KH.KOMOK_CD = FR.HIMOK_CD" . "\r\n";
        $strSQL .= "        LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI" . "\r\n";
        $strSQL .= "                   FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "                   WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "                   ) M_KMK" . "\r\n";

        $strSQL .= "        ON        M_KMK.KAMOK_CD = FR.KAMOK_CD" . "\r\n";
        $strSQL .= "        WHERE FR.HASEI_MOTO_KB = 'FR'" . "\r\n";
        $strSQL .= "        AND   FR.TAISK_KB = '1'" . "\r\n";

        $strSQL .= "        UNION ALL" . "\r\n";

        $strSQL .= "        SELECT FR.KEIJO_DT" . "\r\n";
        $strSQL .= "        ,      FR.DENPY_NO" . "\r\n";
        $strSQL .= "        ,      NULL" . "\r\n";
        $strSQL .= "        ,      NULL" . "\r\n";
        $strSQL .= "        ,      FR.KAMOK_CD" . "\r\n";
        $strSQL .= "        ,      (DECODE(KH.KAMOKUMEI,NULL,M_KMK.KAMOKUMEI,KH.KAMOKUMEI)) KAMOKNM" . "\r\n";
        $strSQL .= "        ,      FR.KEIJO_GK" . "\r\n";
        $strSQL .= "        FROM  HFURIKAE FR" . "\r\n";
        $strSQL .= "        LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, (KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') KH" . "\r\n";
        $strSQL .= "        ON        KH.KAMOK_CD = FR.KAMOK_CD" . "\r\n";
        $strSQL .= "        AND       KH.KOMOK_CD = FR.HIMOK_CD" . "\r\n";
        $strSQL .= "        LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI" . "\r\n";
        $strSQL .= "                   FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "                   WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "        ) M_KMK" . "\r\n";

        $strSQL .= "        ON        M_KMK.KAMOK_CD = FR.KAMOK_CD" . "\r\n";

        $strSQL .= "        WHERE FR.HASEI_MOTO_KB = 'FR'" . "\r\n";
        $strSQL .= "        AND   FR.TAISK_KB = '2'" . "\r\n";
        $strSQL .= ") V" . "\r\n";

        if (rtrim($postData['KEIJYOBI']) != '') {
            $strSQL .= $strWHERE;
            $strSQL .= "   SUBSTR(V.KEIJO_DT,1,6) = '@KEIJYOBI'" . "\r\n";
            $strWHERE = "AND";
        }
        if (rtrim($postData['DENPYOF']) != '') {
            $strSQL .= $strWHERE;
            $strSQL .= "   V.DENPY_NO >= '@DENPYOF'" . "\r\n";
            $strWHERE = "AND";
        }
        if (rtrim($postData['DENPYOT']) != '') {
            $strSQL .= $strWHERE;
            $strSQL .= "   V.DENPY_NO <= '@DENPYOT'" . "\r\n";
            $strWHERE = "AND";
        }

        $strSQL .= "GROUP BY V.KEIJO_DT, V.DENPY_NO" . "\r\n";

        $strSQL .= "ORDER BY V.KEIJO_DT, V.DENPY_NO" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", str_replace("/", "", $postData['KEIJYOBI']), $strSQL);
        $strSQL = str_replace("@DENPYOF", $postData['DENPYOF'], $strSQL);
        $strSQL = str_replace("@DENPYOT", $postData['DENPYOT'], $strSQL);
        return $strSQL;
    }



    public function ControlCheck()
    {
        $strSql = $this->ControlCheckSql();
        return parent::select($strSql);
    }

    public function fncSearchFurikae($postData = NULL)
    {
        $strSql = $this->fncSearchFurikaeSql($postData);
        return parent::select($strSql);
    }

}