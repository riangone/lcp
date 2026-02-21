<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmKyotenFurikae extends ClsComDb
{
    function fncSearchFurikae($postData)
    {
        $strWhere = "WHERE";
        $strSQL = "";

        $strSQL .= "SELECT MOTO_TBL.NENGETU" . "\r\n";
        $strSQL .= ",      REPLACE(MOTO_TBL.CMN_NO,'9999999999','') CMN_NO" . "\r\n";
        $strSQL .= ",      MOTO_TBL.UC_NO" . "\r\n";
        $strSQL .= ",      MOTO_TBL.EDA_NO" . "\r\n";
        $strSQL .= ",      MOTO_TBL.DISP_MOJI" . "\r\n";
        $strSQL .= ",      MOTO_TBL.SYAIN_CD MOTO_SYAIN_CD" . "\r\n";
        $strSQL .= ",      M_SYA.SYAIN_NM MOTO_SYAIN_NM" . "\r\n";
        $strSQL .= ",      MOTO_TBL.FURIKAE_KIN MOTO_KIN" . "\r\n";
        $strSQL .= ",      SAKI_TBL.SYAIN_CD SAKI_SYAIN_CD" . "\r\n";
        $strSQL .= ",      S_SYA.SYAIN_NM SAKI_SYAIN_NM" . "\r\n";
        $strSQL .= ",      SAKI_TBL.FURIKAE_KIN SAKI_KIN" . "\r\n";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY MOTO_TBL.NENGETU, MOTO_TBL.CMN_NO, MOTO_TBL.EDA_NO, SAKI_TBL.SYAIN_CD) " . "\r\n";
        $strSQL .= "       - RANK() OVER(ORDER BY MOTO_TBL.NENGETU, MOTO_TBL.CMN_NO, MOTO_TBL.EDA_NO) DISP_KB" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "        (SELECT FRI.NENGETU" . "\r\n";
        $strSQL .= "        ,      FRI.SYAIN_CD" . "\r\n";
        $strSQL .= "        ,      FRI.CMN_NO CMN_NO" . "\r\n";
        $strSQL .= "        ,      FRI.UC_NO" . "\r\n";
        $strSQL .= "        ,      FRI.DISP_MOJI" . "\r\n";
        $strSQL .= "        ,      FRI.FURIKAE_KIN" . "\r\n";
        $strSQL .= "        ,      FRI.EDA_NO" . "\r\n";
        $strSQL .= "        FROM   HKYOTENFURIKAE FRI" . "\r\n";
        $strSQL .= "        WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "        AND    FRI.MOTOSAKI_KB = 'M') MOTO_TBL" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "        (SELECT FRI.NENGETU" . "\r\n";
        $strSQL .= "        ,      FRI.SYAIN_CD" . "\r\n";
        $strSQL .= "        ,      FRI.CMN_NO CMN_NO" . "\r\n";
        $strSQL .= "        ,      FRI.UC_NO" . "\r\n";
        $strSQL .= "        ,      FRI.DISP_MOJI" . "\r\n";
        $strSQL .= "        ,      FRI.FURIKAE_KIN" . "\r\n";
        $strSQL .= "        ,      FRI.EDA_NO" . "\r\n";
        $strSQL .= "        FROM   HKYOTENFURIKAE FRI" . "\r\n";
        $strSQL .= "        WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "        AND    FRI.MOTOSAKI_KB = 'S') SAKI_TBL" . "\r\n";
        $strSQL .= "ON    MOTO_TBL.CMN_NO = SAKI_TBL.CMN_NO" . "\r\n";
        $strSQL .= "AND   MOTO_TBL.EDA_NO = SAKI_TBL.EDA_NO" . "\r\n";
        $strSQL .= "LEFT JOIN JKSYAIN M_SYA" . "\r\n";
        $strSQL .= "ON    M_SYA.SYAIN_NO = MOTO_TBL.SYAIN_CD" . "\r\n";
        $strSQL .= "LEFT JOIN JKSYAIN S_SYA" . "\r\n";
        $strSQL .= "ON    S_SYA.SYAIN_NO = SAKI_TBL.SYAIN_CD" . "\r\n";

        if ($postData['SYAIN'] != '') {
            $strSQL .= "WHERE   MOTO_TBL.SYAIN_CD LIKE '@SYAIN%'" . "\r\n";
            $strWhere = " AND ";
        }

        if ($postData['CMNNO'] != '') {
            $strSQL .= $strWhere . "  MOTO_TBL.CMN_NO LIKE '@CMNNO%'" . "\r\n";
        }

        $strSQL .= "ORDER BY MOTO_TBL.CMN_NO" . "\r\n";
        $strSQL .= ",        MOTO_TBL.EDA_NO" . "\r\n";
        $strSQL .= ",        SAKI_TBL.SYAIN_CD" . "\r\n";

        $strSQL = str_replace("@NENGETU", str_replace("/", "", $postData['NENGETU']), $strSQL);
        $strSQL = str_replace("@SYAIN", $postData['SYAIN'], $strSQL);
        $strSQL = str_replace("@CMNNO", $postData['CMNNO'], $strSQL);

        return $strSQL;

    }

    public function fncSelect($postData = NULL)
    {
        $strSql = $this->fncSearchFurikae($postData);
        return parent::select($strSql);
    }

    public function fncGetTougetuSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYORI_YM,1,4) || '/' || SUBSTR(SYORI_YM,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= "FROM JKCONTROLMST WHERE ID = '01'" . "\r\n";

        return $strSQL;

    }

    public function fncGetTougetu()
    {
        $strSql = $this->fncGetTougetuSql();
        return parent::select($strSql);
    }

}
