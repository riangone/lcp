<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmkeisuMstMente extends ClsComDb
{
    //係数マスタ削除SQL
    function fncKeisuMstDelSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " DELETE FROM JKKEISUMST " . "\r\n";
        $strSQL .= " WHERE  KOUMOKU_NO = '" . $postData['cmbKomok'] . "' " . "\r\n";
        $strSQL .= " AND    KEISU_SYURUI = '" . $postData['cmbKeisu'] . "' " . "\r\n";
        if ($postData['kinKbn'] == "rdbEigyou") {
            $strSQL .= " AND    SYOREIKIN_KB = '1'" . "\r\n";
        } else {
            $strSQL .= " AND    SYOREIKIN_KB = '2'" . "\r\n";
        }

        return $strSQL;
    }

    //係数マスタ登録SQL
    function fncKeisuMstInsSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " INSERT INTO JKKEISUMST( " . "\r\n";
        $strSQL .= "        SYOREIKIN_KB " . "\r\n";
        $strSQL .= "      , KEISU_SYURUI " . "\r\n";
        $strSQL .= "      , KOUMOKU_NO " . "\r\n";
        $strSQL .= "      , RANGE_FROM " . "\r\n";
        $strSQL .= "      , RANGE_TO " . "\r\n";
        $strSQL .= "      , KEISU " . "\r\n";
        $strSQL .= "      , CREATE_DATE " . "\r\n";
        $strSQL .= "      , CRE_SYA_CD " . "\r\n";
        $strSQL .= "      , CRE_PRG_ID " . "\r\n";
        $strSQL .= "      , UPD_DATE " . "\r\n";
        $strSQL .= "      , UPD_SYA_CD " . "\r\n";
        $strSQL .= "      , UPD_PRG_ID " . "\r\n";
        $strSQL .= "      , UPD_CLT_NM " . "\r\n";
        $strSQL .= " )VALUES( " . "\r\n";
        if ($postData['kinKbn'] == "rdbEigyou") {
            $strSQL .= "    '1' " . "\r\n";
        } else {
            $strSQL .= "    '2' " . "\r\n";
        }
        $strSQL .= "      , '@KEISU_SYURUI' " . "\r\n";
        $strSQL .= "      , '@KOUMOKU_NO' " . "\r\n";
        $strSQL .= "      , '@RANGE_FROM' " . "\r\n";
        $strSQL .= "      , '@RANGE_TO' " . "\r\n";
        $strSQL .= "      , '@KEISU' " . "\r\n";
        $strSQL .= "      , SYSDATE " . "\r\n";
        $strSQL .= "      , '@SYA_CD' " . "\r\n";
        $strSQL .= "      , '@PRG_ID' " . "\r\n";
        $strSQL .= "      , SYSDATE " . "\r\n";
        $strSQL .= "      , '@SYA_CD' " . "\r\n";
        $strSQL .= "      , '@PRG_ID' " . "\r\n";
        $strSQL .= "      , '@CLT_NM' " . "\r\n";
        $strSQL .= " ) ";

        $strSQL = str_replace("@KEISU_SYURUI", $postData['cmbKeisu'], $strSQL);
        $strSQL = str_replace("@KOUMOKU_NO", $postData['cmbKomok'], $strSQL);
        $strSQL = str_replace("@RANGE_FROM", $postData['txtHaniS'], $strSQL);
        $strSQL = str_replace("@RANGE_TO", $postData['txtHaniE'], $strSQL);
        $strSQL = str_replace("@KEISU", $postData['txtKeisu'], $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", "KeisuMstMente", $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    //係数マスタ更新SQL
    function fncKeisuMstUpdSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " UPDATE JKKEISUMST" . "\r\n";
        $strSQL .= " SET    RANGE_FROM = '@RANGE_FROM' " . "\r\n";
        $strSQL .= "      , RANGE_TO = '@RANGE_TO' " . "\r\n";
        $strSQL .= "      , KEISU = '@KEISU' " . "\r\n";
        $strSQL .= "      , UPD_DATE = SYSDATE " . "\r\n";
        $strSQL .= "      , UPD_SYA_CD = '@SYA_CD' " . "\r\n";
        $strSQL .= "      , UPD_PRG_ID = '@PRG_ID' " . "\r\n";
        $strSQL .= "      , UPD_CLT_NM = '@CLT_NM' " . "\r\n";
        if ($postData['kinKbn'] == "rdbEigyou") {
            $strSQL .= " WHERE SYOREIKIN_KB = '1'" . "\r\n";
        } else {
            $strSQL .= " WHERE SYOREIKIN_KB = '2'" . "\r\n";
        }

        $strSQL .= " AND    KEISU_SYURUI = '@KEISU_SYURUI' " . "\r\n";
        $strSQL .= " AND    KOUMOKU_NO = '@KOUMOKU_NO' " . "\r\n";

        $strSQL = str_replace("@KEISU_SYURUI", $postData['cmbKeisu'], $strSQL);
        $strSQL = str_replace("@KOUMOKU_NO", $postData['cmbKomok'], $strSQL);
        $strSQL = str_replace("@RANGE_FROM", $postData['txtHaniS'], $strSQL);
        $strSQL = str_replace("@RANGE_TO", $postData['txtHaniE'], $strSQL);
        $strSQL = str_replace("@KEISU", $postData['txtKeisu'], $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@PRG_ID", "KeisuMstMente", $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return $strSQL;
    }

    //係数マスタチェックSQL
    public function fncKeisuMstChkSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " SELECT   KOUMOKU_NO " . "\r\n";
        $strSQL .= " FROM     JKKEISUMST  " . "\r\n";
        $strSQL .= " WHERE  ( RANGE_TO >= '" . $postData['txtHaniE'] . "' " . "\r\n";
        $strSQL .= " 　AND    RANGE_FROM <= '" . $postData['txtHaniE'] . "' " . "\r\n";
        $strSQL .= " 　OR     RANGE_TO >= '" . $postData['txtHaniS'] . "' " . "\r\n";
        $strSQL .= " 　AND    RANGE_FROM <= '" . $postData['txtHaniS'] . "' " . "\r\n";
        $strSQL .= " 　) " . "\r\n";
        $strSQL .= "   AND    KOUMOKU_NO != '" . $postData['cmbKomok'] . "' " . "\r\n";
        $strSQL .= " 　AND    KEISU_SYURUI = '" . $postData['cmbKeisu'] . "' " . "\r\n";
        if ($postData['kinKbn'] == "rdbEigyou") {
            $strSQL .= " AND      SYOREIKIN_KB = '1' " . "\r\n";
        } else {
            $strSQL .= " AND      SYOREIKIN_KB = '2' " . "\r\n";
        }

        return $strSQL;
    }

    //係数マスタ取得SQL
    public function fncKeisuMstSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " SELECT   KEISU_SYURUI " . "\r\n";
        $strSQL .= " FROM     JKKEISUMST  " . "\r\n";
        $strSQL .= " WHERE    KOUMOKU_NO = '" . $postData['cmbKomok'] . "' " . "\r\n";
        $strSQL .= " AND      KEISU_SYURUI = '" . $postData['cmbKeisu'] . "' " . "\r\n";
        if ($postData['kinKbn'] == "rdbEigyou") {
            $strSQL .= " AND      SYOREIKIN_KB = '1' " . "\r\n";
        } else {
            $strSQL .= " AND      SYOREIKIN_KB = '2' " . "\r\n";
        }

        return $strSQL;
    }

    //奨励金処理マスタ店長項目取得SQL
    public function fncSyoureikinMstTencyouSQL()
    {
        $strSQL = "";

        $strSQL .= " SELECT   CODE " . "\r\n";
        $strSQL .= "        , MEISYO " . "\r\n";
        $strSQL .= "        , ATAI_1 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '20000' " . "\r\n";
        $strSQL .= " ORDER BY HYOJI_JUN " . "\r\n";

        return $strSQL;
    }

    //奨励金処理マスタ営業業績項目取得SQL
    public function fncSyoureikinMstEigyouSQL()
    {
        $strSQL = "";

        $strSQL .= " SELECT   CODE " . "\r\n";
        $strSQL .= "        , MEISYO " . "\r\n";
        $strSQL .= "        , ATAI_1 " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '10000' " . "\r\n";
        $strSQL .= " ORDER BY HYOJI_JUN " . "\r\n";

        return $strSQL;
    }

    //奨励金処理マスタ営業業績（項目）項目取得SQL
    public function fncSyoureikinMstEigyouKmkSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " SELECT   CODE " . "\r\n";
        $strSQL .= "        , MEISYO " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '100' || '" . $postData['cmbKeisu'] . "' " . "\r\n";
        $strSQL .= " ORDER BY HYOJI_JUN " . "\r\n";

        return $strSQL;
    }

    //奨励金処理マスタ店長（項目）項目取得SQL
    public function fncSyoureikinMstTencyouKmkSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " SELECT   CODE " . "\r\n";
        $strSQL .= "        , MEISYO " . "\r\n";
        $strSQL .= " FROM     JKSYOREIKINMST  " . "\r\n";
        $strSQL .= " WHERE    SYUBETU_CD = '200' || '" . $postData['cmbKeisu'] . "' " . "\r\n";
        $strSQL .= " ORDER BY HYOJI_JUN " . "\r\n";

        return $strSQL;
    }

    //検索項目取得SQL
    public function fncSearchSQL($postData)
    {
        $strSQL = "";

        $strSQL .= " SELECT   ke.KEISU_SYURUI AS KEISU_SYURUI_CD " . "\r\n";
        $strSQL .= "        , sy.MEISYO AS KEISU_SYURUI_NM " . "\r\n";
        $strSQL .= "        , ke.KOUMOKU_NO " . "\r\n";
        if ($postData['kinKbnSch'] == 'rdbEigyouSch') {
            $strSQL .= "    , (SELECT MEISYO FROM JKSYOREIKINMST WHERE SYUBETU_CD = '100' || NVL(ke.KEISU_SYURUI, '00') AND CODE = ke.KOUMOKU_NO) AS KOUMOKU_NM " . "\r\n";
        } else {
            $strSQL .= "    , (SELECT MEISYO FROM JKSYOREIKINMST WHERE SYUBETU_CD = '200' || NVL(ke.KEISU_SYURUI, '00') AND CODE = ke.KOUMOKU_NO) AS KOUMOKU_NM " . "\r\n";
        }

        $strSQL .= "        , ke.RANGE_FROM " . "\r\n";
        $strSQL .= "        , CASE WHEN instr(TO_CHAR(ke.RANGE_TO), '.') > 0 THEN TO_CHAR(ke.RANGE_TO,'fm9999990.00') ELSE TO_CHAR(ke.RANGE_TO) END AS RANGE_TO " . "\r\n";
        $strSQL .= "        , CASE WHEN instr(TO_CHAR(ke.KEISU), '.') > 0 THEN TO_CHAR(ke.KEISU,'fm9999990.00') ELSE TO_CHAR(ke.KEISU) END AS KEISU " . "\r\n";
        $strSQL .= "        , sy.ATAI_1 " . "\r\n";
        if ($postData['kinKbnSch'] == 'rdbEigyouSch') {
            $strSQL .= " FROM  (SELECT CODE ,MEISYO ,ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '10000') sy, JKKEISUMST ke  " . "\r\n";
        } else {
            $strSQL .= " FROM  (SELECT CODE ,MEISYO ,ATAI_1 FROM JKSYOREIKINMST WHERE SYUBETU_CD = '20000') sy, JKKEISUMST ke  " . "\r\n";
        }
        $strSQL .= " WHERE    sy.CODE = ke.KEISU_SYURUI  " . "\r\n";
        if ($postData['kinKbnSch'] == 'rdbEigyouSch') {
            $strSQL .= " AND   ke.SYOREIKIN_KB = '1' " . "\r\n";
        } else {
            $strSQL .= " AND   ke.SYOREIKIN_KB = '2' " . "\r\n";
        }
        if ($postData['cmbKeisuSch'] != '999999') {
            $strSQL .= " AND   ke.KEISU_SYURUI = '" . $postData['cmbKeisuSch'] . "' " . "\r\n";
        }
        $strSQL .= " ORDER BY  ke.KEISU_SYURUI " . "\r\n";
        $strSQL .= "          ,ke.KOUMOKU_NO " . "\r\n";

        return $strSQL;
    }

    public function fncSearch($postData)
    {
        $strSql = $this->fncSearchSQL($postData);
        return parent::select($strSql);
    }

    public function fncSyoureikinMstEigyou()
    {
        $strSql = $this->fncSyoureikinMstEigyouSQL();
        return parent::select($strSql);
    }

    public function fncSyoureikinMstTencyou()
    {
        $strSql = $this->fncSyoureikinMstTencyouSQL();
        return parent::select($strSql);
    }

    public function fncSyoureikinMstKmk($postData)
    {
        if ($postData['kinKbn'] == 'rdbEigyou') {
            $strSql = $this->fncSyoureikinMstEigyouKmkSQL($postData);
        } else {
            $strSql = $this->fncSyoureikinMstTencyouKmkSQL($postData);
        }
        return parent::select($strSql);
    }

    public function fncKeisuMst($postData)
    {
        $strSql = $this->fncKeisuMstSQL($postData);
        return parent::select($strSql);
    }

    public function fncKeisuMstChk($postData)
    {
        $strSql = $this->fncKeisuMstChkSQL($postData);
        return parent::select($strSql);
    }

    public function fncKeisuMstIns($postData)
    {
        $strSql = $this->fncKeisuMstInsSQL($postData);
        return parent::insert($strSql);
    }

    public function fncKeisuMstUpd($postData)
    {
        $strSql = $this->fncKeisuMstUpdSQL($postData);
        return parent::update($strSql);
    }

    public function fncKeisuMstDel($postData)
    {
        $strSql = $this->fncKeisuMstDelSQL($postData);
        return parent::delete($strSql);
    }

}
