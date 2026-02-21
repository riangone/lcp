<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

class FrmSyokusyubetuKamokuMente extends ClsComDb
{
    //科目名取得
    public function FncGetKamokuMstValue($strCode, $strKomoku)
    {
        $strSQL = "";
        $strSQL .= "SELECT  HKN.KAMOK_CD" . " \r\n";
        $strSQL .= ",       HKN.HIMOK_CD" . " \r\n";
        $strSQL .= ",       KMK.KAMOK_NM" . " \r\n";
        $strSQL .= "FROM   (SELECT  (CASE WHEN  WK.KAMOK_CD IS NULL THEN '@KAMOKUCD' ELSE WK.KAMOK_CD END) KAMOK_CD" . " \r\n";
        $strSQL .= "       ,       '@KOMOKU' HIMOK_CD" . " \r\n";

        $strSQL .= "FROM    DUAL" . " \r\n";
        $strSQL .= "        LEFT JOIN WK_CNVKAMOK WK" . " \r\n";
        $strSQL .= "       ON      WK.GDMZ_CD = '@KAMOKUCD'" . " \r\n";
        $strSQL .= "      ) HKN" . " \r\n";
        $strSQL .= "LEFT JOIN M_KAMOKU KMK" . " \r\n";
        $strSQL .= "ON       HKN.KAMOK_CD = KMK.KAMOK_CD" . " \r\n";
        $strSQL .= "AND      NVL(TRIM(HKN.HIMOK_CD),'00') = NVL(TRIM(KMK.KOMOK_CD),'00')" . " \r\n";

        $strSQL = str_replace("@KAMOKUCD", $strCode, $strSQL);
        $strSQL = str_replace("@KOMOKU", $strKomoku, $strSQL);

        return parent::select($strSQL);
    }

    //区分マスタデータ取得SQL
    public function FncSelKubunMstSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT KUBUN_CD" . " \r\n";
        $strSQL .= " , KUBUN_NM" . " \r\n";
        $strSQL .= "FROM   JKKUBUNMST" . " \r\n";
        $strSQL .= "WHERE  KUBUN_ID = 'JKKOUMK'" . " \r\n";
        $strSQL .= "ORDER BY KUBUN_CD" . " \r\n";
        return parent::select($strSQL);
    }

    //コードマスタデータ取得SQL
    public function FncSelCodeMstSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT CODE" . " \r\n";
        $strSQL .= " , MEISYOU" . " \r\n";
        $strSQL .= "FROM   JKCODEMST" . " \r\n";
        $strSQL .= " WHERE  ID = 'SYOKUSYU'" . " \r\n";
        $strSQL .= " UNION ALL" . " \r\n";
        $strSQL .= " SELECT 'ZZZ' CODE" . " \r\n";
        $strSQL .= ", '全職種' MEISYOU" . " \r\n";
        $strSQL .= " FROM   DUAL" . " \r\n";
        $strSQL .= " ORDER BY CODE" . " \r\n";
        return parent::select($strSQL);
    }

    //職種別科目変換マスタデータ取得SQL
    public function fncSelSyokusyuKamokCnvSQL()
    {
        $strSql = "";
        $strSql .= "SELECT skcm.KOUMK_NO" . "\r\n";
        $strSql .= "  , kbm.KUBUN_NM " . "\r\n";
        $strSql .= "  , skcm.KAMOK_CD  " . "\r\n";
        $strSql .= "  , skcm.HIMOK_CD  " . "\r\n";
        $strSql .= "  , kmm.KAMOK_NM  " . "\r\n";
        $strSql .= "  , skcm.SYOKUSYU_CD " . "\r\n";
        $strSql .= "  , cdm.MEISYOU  " . "\r\n";
        $strSql .= "  , skcm.CREATE_DATE  " . "\r\n";
        $strSql .= "  , skcm.CRE_SYA_CD " . "\r\n";
        $strSql .= "  , skcm.CRE_PRG_ID  " . "\r\n";
        $strSql .= "FROM  (SELECT  (CASE WHEN WK.KAMOK_CD IS NULL THEN SKC.KAMOK_CD ELSE WK.KAMOK_CD END) KAMOK_CD" . "\r\n";
        $strSql .= "  ,       SKC.HIMOK_CD  " . "\r\n";
        $strSql .= "  ,       SKC.KOUMK_NO  " . "\r\n";
        $strSql .= "  ,       SKC.SYOKUSYU_CD  " . "\r\n";
        $strSql .= "  ,       SKC.CREATE_DATE  " . "\r\n";
        $strSql .= "  ,       SKC.CRE_PRG_ID " . "\r\n";
        $strSql .= "  ,       SKC.CRE_SYA_CD " . "\r\n";

        $strSql .= " FROM JKSYOKUSYUKAMOKCNV SKC  " . "\r\n";
        $strSql .= " LEFT JOIN WK_CNVKAMOK WK  " . "\r\n";
        $strSql .= " ON      WK.GDMZ_CD = SKC.KAMOK_CD) skcm" . "\r\n";
        $strSql .= "  LEFT JOIN M_KAMOKU kmm " . "\r\n";
        $strSql .= "  ON   skcm.KAMOK_CD = kmm.KAMOK_CD  " . "\r\n";
        $strSql .= " AND   NVL(TRIM(skcm.HIMOK_CD),'00') = NVL(TRIM(kmm.KOMOK_CD),'00') " . "\r\n";

        $strSql .= "  LEFT JOIN JKCODEMST cdm " . "\r\n";
        $strSql .= " ON    skcm.SYOKUSYU_CD = cdm.CODE  " . "\r\n";
        $strSql .= " AND   cdm.ID = 'SYOKUSYU'" . "\r\n";
        $strSql .= "  LEFT JOIN JKKUBUNMST kbm" . "\r\n";
        $strSql .= "  ON    skcm.KOUMK_NO = kbm.KUBUN_CD " . "\r\n";
        $strSql .= " AND   kbm.KUBUN_ID = 'JKKOUMK' " . "\r\n";
        $strSql .= "  ORDER BY  skcm.KOUMK_NO, kbm.KUBUN_NM, skcm.KAMOK_CD, skcm.HIMOK_CD, skcm.SYOKUSYU_CD " . "\r\n";
        return parent::select($strSql);
    }

    //職種別科目変換マスタデータ削除SQL
    public function fncDelSyokusyuKamokCnvSQL($txtKaCd, $txtHiCd, $cmbItem, $cmbSyCd)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM JKSYOKUSYUKAMOKCNV" . " \r\n";
        $strSQL .= "WHERE" . " \r\n";
        $strSQL .= "KAMOK_CD = '@KAMOK_CD'" . " \r\n";
        $strSQL .= "AND    HIMOK_CD = '@HIMOK_CD'" . " \r\n";
        $strSQL .= "AND    KOUMK_NO  = '@KOUMK_NO'" . " \r\n";
        $strSQL .= "AND    SYOKUSYU_CD = '@SYOKUSYU_CD'" . " \r\n";

        $strSQL = str_replace("@KAMOK_CD", $txtKaCd, $strSQL);
        $strSQL = str_replace("@HIMOK_CD", $txtHiCd, $strSQL);
        $strSQL = str_replace("@KOUMK_NO", $cmbItem, $strSQL);
        $strSQL = str_replace("@SYOKUSYU_CD", $cmbSyCd, $strSQL);

        return parent::delete($strSQL);
    }

    //職種別科目変換マスタデータ登録SQL
    public function fncRegSyokusyuKamokCnvSQL($cmbItem, $txtKaCd, $txtHiCd, $cmbSyCd, $lblCreD, $lblCreM, $lblCreA)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO JKSYOKUSYUKAMOKCNV( " . "\r\n";
        $strSQL .= "       KAMOK_CD" . "\r\n";
        $strSQL .= "      , HIMOK_CD" . "\r\n";
        $strSQL .= "      , SYOKUSYU_CD" . "\r\n";
        $strSQL .= "      , KOUMK_NO" . "\r\n";
        $strSQL .= "      , CREATE_DATE" . "\r\n";
        $strSQL .= "      , CRE_SYA_CD" . "\r\n";
        $strSQL .= "      , CRE_PRG_ID" . "\r\n";
        $strSQL .= "      , UPD_DATE" . "\r\n";
        $strSQL .= "      , UPD_SYA_CD" . "\r\n";
        $strSQL .= "      , UPD_PRG_ID" . "\r\n";
        $strSQL .= "      , UPD_CLT_NM" . "\r\n";
        $strSQL .= " ) VALUES (" . "\r\n";
        $strSQL .= "        '@KAMOK_CD'" . "\r\n";
        $strSQL .= "      , '@HIMOK_CD'" . "\r\n";
        $strSQL .= "      , '@SYOKUSYU_CD'" . "\r\n";
        $strSQL .= "      , '@KOUMK_NO'" . "\r\n";

        if ($lblCreD == "") {
            $strSQL .= "     , SYSDATE" . "\r\n";
        } else {
            $strSQL .= "     , '" . $lblCreD . "' " . "\r\n";
        }

        if ($lblCreM == "") {
            $strSQL .= "     , '@SYA_CD'" . "\r\n";
        } else {
            $strSQL .= "     , '" . $lblCreM . "' " . "\r\n";
        }

        if ($lblCreA == "") {
            $strSQL .= "     , '@PRG_ID'" . "\r\n";
        } else {
            $strSQL .= "     , '" . $lblCreA . "' " . "\r\n";
        }

        $strSQL .= "      , SYSDATE" . "\r\n";
        $strSQL .= "      , '@SYA_CD'" . "\r\n";
        $strSQL .= "      , '@PRG_ID'" . "\r\n";
        $strSQL .= "      , '@CLT_NM'" . "\r\n";
        $strSQL .= " )" . "\r\n";

        $strSQL = str_replace("@KAMOK_CD", $txtKaCd, $strSQL);

        if ($txtHiCd == "") {
            $strSQL = str_replace("@HIMOK_CD", "     ", $strSQL);
        } else {
            $strSQL = str_replace("@HIMOK_CD", $txtHiCd, $strSQL);
        }

        $strSQL = str_replace("@SYOKUSYU_CD", $cmbSyCd, $strSQL);
        $strSQL = str_replace("@KOUMK_NO", $cmbItem, $strSQL);

        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", "SyokusyubetuKamoku", $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

}
