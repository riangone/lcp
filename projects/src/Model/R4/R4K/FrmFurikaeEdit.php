<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmFurikaeEdit extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    // $this -> ClsComFnc = new ClsComFnc();
    function fncFurikaeSetSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "SELECT FR.DENPY_NO" . "\r\n";
        $strSQL .= ",      FR.TAISK_KB" . "\r\n";
        $strSQL .= ",      FR.BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      FR.KAMOK_CD" . "\r\n";
        $strSQL .= ",      (DECODE(KH.KAMOKUMEI,NULL,M_KMK.KAMOKUMEI,KH.KAMOKUMEI)) KAMOKNM" . "\r\n";
        $strSQL .= ",      FR.HIMOK_CD" . "\r\n";
        $strSQL .= ",      FR.KEIJO_GK" . "\r\n";

        if ($postData['blnTorikomi'] == 'false') {
            $strSQL .= ",      FR.KEIJO_DT" . "\r\n";
            $strSQL .= "FROM   HFURIKAE FR" . "\r\n";
        } else {
            $strSQL .= "FROM   HFURIPTN FR" . "\r\n";
        }

        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, (KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') KH" . "\r\n";
        $strSQL .= "ON        KH.KAMOK_CD = FR.KAMOK_CD" . "\r\n";
        $strSQL .= "AND       KH.KOMOK_CD = FR.HIMOK_CD" . "\r\n";
        $strSQL .= "        LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI" . "\r\n";
        $strSQL .= "                   FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "                   WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "        ) M_KMK" . "\r\n";
        $strSQL .= "ON        M_KMK.KAMOK_CD = FR.KAMOK_CD" . "\r\n";
        $strSQL .= "LEFT  JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON    BUS.BUSYO_CD = FR.BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE FR.DENPY_NO = '@DENPYNO'" . "\r\n";

        if ($postData['blnTorikomi'] == 'false') {
            $strSQL .= "AND   FR.OA_KB = 'A'" . "\r\n";
            $strSQL .= "AND   FR.HASEI_MOTO_KB = 'FR'" . "\r\n";
            $strSQL .= "AND   FR.KEIJO_DT = '@KEIJOBI'" . "\r\n";
        } else {
            $strSQL .= "AND   NVL(FR.OA_KB,' ') <> '1'" . "\r\n";
        }

        if ($postData['blnTorikomi'] == 'false') {
            $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
            $strSQL = str_replace("@DENPYNO", $postData['DENPYNO'], $strSQL);
        } else {
            $strSQL = str_replace("@DENPYNO", $postData['DENPYNO'], $strSQL);
        }
        return $strSQL;

    }

    function fncDataSetJqGridSql()
    {

        $strSQL = "SELECT BUSYO_CD,BUSYO_NM " . "\r\n";

        $strSQL .= "FROM   HBUSYO" . "\r\n";

        return $strSQL;
    }

    function fncDataSetSql()
    {

        $strSQL = "SELECT";

        $strSQL .= "     NVL(BUSYO_CD,'') AS BUSYOCD";

        $strSQL .= "    ,NVL(BUSYO_NM,'') AS BUSYONM";

        $strSQL .= "    ,NVL(KKR_BUSYO_CD,'') as KKRCD";

        $strSQL .= " FROM HBUSYO";

        $strSQL .= " WHERE ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')";

        $strSQL .= " ORDER BY BUSYO_CD";

        return $strSQL;
    }

    function fncDataSetKamokuSql()
    {

        $strSQL = "";

        $strSQL .= "SELECT KAMOK_CD KAMOKUCD, KAMOK_NM KAMOKUNM" . "\r\n";
        $strSQL .= "FROM   M_KAMOKU A" . "\r\n";
        $strSQL .= "WHERE  NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";

        return $strSQL;
    }

    function fncFurikaeMotSetSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "SELECT FR.DENPY_NO" . "\r\n";
        $strSQL .= ",      FR.TAISK_KB" . "\r\n";
        $strSQL .= ",      FR.KAMOK_CD" . "\r\n";
        $strSQL .= ",      (DECODE(KH.KAMOKUMEI,NULL,M_KMK.KAMOKUMEI,KH.KAMOKUMEI)) KAMOKNM" . "\r\n";
        $strSQL .= ",      FR.HIMOK_CD" . "\r\n";
        $strSQL .= ",      FR.BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      FR.KEIJO_GK" . "\r\n";
        if ($postData['blnTorikomi'] == 'false') {
            $strSQL .= "FROM   HFURIKAE FR" . "\r\n";
        } else {
            $strSQL .= "FROM   HFURIPTN FR" . "\r\n";
        }

        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, KOMOK_CD, (KAMOK_NM || ' ' || KOMOK_NM) KAMOKUMEI FROM M_KAMOKU WHERE NVL(TRIM(KOMOK_CD),'00') <> '00') KH" . "\r\n";
        $strSQL .= "ON        KH.KAMOK_CD = FR.KAMOK_CD" . "\r\n";
        $strSQL .= "AND       KH.KOMOK_CD = FR.HIMOK_CD" . "\r\n";
        $strSQL .= "LEFT JOIN (SELECT KAMOK_CD, KAMOK_NM KAMOKUMEI" . "\r\n";
        $strSQL .= "           FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "           WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "        ) M_KMK" . "\r\n";
        $strSQL .= "ON        M_KMK.KAMOK_CD = FR.KAMOK_CD" . "\r\n";
        $strSQL .= "LEFT  JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON    BUS.BUSYO_CD = FR.BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE       FR.OA_KB = '@OAKB'" . "\r\n";
        if ($postData['blnTorikomi'] == 'false') {
            $strSQL .= "AND    FR.HASEI_MOTO_KB = 'FR'" . "\r\n";
            $strSQL .= "AND    FR.KEIJO_DT = '@KEIJYO'" . "\r\n";
        }
        $strSQL .= "AND    FR.DENPY_NO = '@DENPY'" . "\r\n";

        if ($postData['blnTorikomi'] == 'false') {
            $strSQL = str_replace("@KEIJYO", str_replace("/", "", $postData['KEIJYO']), $strSQL);
            $strSQL = str_replace("@DENPY", $postData['DENPY'], $strSQL);
            $strSQL = str_replace("@OAKB", "O", $strSQL);
        } else {
            $strSQL = str_replace("@DENPY", $postData['DENPY'], $strSQL);
            $strSQL = str_replace("@OAKB", "1", $strSQL);
        }

        return $strSQL;
    }

    function fncFurikaeExistSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "SELECT DENPY_NO" . "\r\n";
        $strSQL .= "FROM   HFURIKAE" . "\r\n";
        $strSQL .= "WHERE  DENPY_NO = '@DENPY_NO'" . "\r\n";
        $strSQL .= "AND    KEIJO_DT = '@KEIJOBI'" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@DENPY_NO", $postData['DENPY_NO'], $strSQL);

        return $strSQL;
    }

    function fncDeleteFurikaeSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "DELETE FROM HFURIKAE" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJYO'" . "\r\n";
        $strSQL .= "AND    DENPY_NO = '@DENPYO'" . "\r\n";

        $strSQL = str_replace("@KEIJYO", str_replace("/", "", $postData['KEIJYO']), $strSQL);
        $strSQL = str_replace("@DENPYO", $postData['DENPYO'], $strSQL);

        return $strSQL;
    }

    function fncControlNenChkSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT SYR_YMD" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'" . "\r\n";

        return $strSQL;

    }

    function fncFurikaeExistChkSql($postData = NULL)
    {
        $strSQL = "";

        $strSQL .= "SELECT *" . "\r\n";
        $strSQL .= "FROM   HFURIKAE" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJYO'" . "\r\n";
        $strSQL .= "AND    DENPY_NO = '@DENPY'" . "\r\n";

        $strSQL = str_replace("@KEIJYO", str_replace("/", "", $postData['KEIJYO']), $strSQL);
        $strSQL = str_replace("@DENPY", $postData['DENPY'], $strSQL);
        // echo $strSQL;
        return $strSQL;

    }

    function fncInsertFurikaeSql($postData = NULL, $flg = FALSE)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "FurikaeEdit";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL .= "INSERT INTO HFURIKAE" . "\r\n";
        $strSQL .= "(           KEIJO_DT" . "\r\n";
        $strSQL .= ",           DENPY_NO" . "\r\n";
        $strSQL .= ",           GYO_NO" . "\r\n";
        $strSQL .= ",           TAISK_KB" . "\r\n";
        $strSQL .= ",           BUSYO_CD" . "\r\n";
        $strSQL .= ",           KAMOK_CD" . "\r\n";
        $strSQL .= ",           HIMOK_CD" . "\r\n";
        $strSQL .= ",           KEIJO_GK" . "\r\n";
        $strSQL .= ",           AITE_BUSYO_CD" . "\r\n";
        $strSQL .= ",           AITE_KAMOK_CD" . "\r\n";
        $strSQL .= ",           AITE_HIMOK_CD" . "\r\n";
        $strSQL .= ",           OA_KB" . "\r\n";
        $strSQL .= ",           HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",           CEL_DATE" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES" . "\r\n";
        $strSQL .= "(           '@KEIJYO'" . "\r\n";
        $strSQL .= ",           '@DENPYO'" . "\r\n";
        $strSQL .= ",           NVL((SELECT MAX(GYO_NO)" . "\r\n";
        $strSQL .= "                 FROM   HFURIKAE" . "\r\n";
        $strSQL .= "                 WHERE  KEIJO_DT = '@KEIJYO'" . "\r\n";
        $strSQL .= "                 AND    DENPY_NO = '@DENPYO'),0) + 1" . "\r\n";
        $strSQL .= ",           '@TAISK'" . "\r\n";
        $strSQL .= ",           '@BUSYO'" . "\r\n";
        $strSQL .= ",           '@KAMOK'" . "\r\n";
        $strSQL .= ",           '@HIMOK'" . "\r\n";
        $strSQL .= ",           @GOKEI" . "\r\n";
        $strSQL .= ",           NULL" . "\r\n";
        $strSQL .= ",           NULL" . "\r\n";
        $strSQL .= ",           NULL" . "\r\n";
        $strSQL .= ",           '@OAKB'" . "\r\n";
        $strSQL .= ",           'FR'" . "\r\n";
        $strSQL .= ",           NULL" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           '@UPDUSER'" . "\r\n";
        $strSQL .= ",           '@UPDAPP'" . "\r\n";
        $strSQL .= ",           '@UPDCLT'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@KEIJYO", str_replace("/", "", $postData['KEIJYO']), $strSQL);
        $strSQL = str_replace("@DENPYO", $postData['DENPYO'], $strSQL);
        $strSQL = str_replace("@BUSYO", $postData['BUSYO'], $strSQL);
        $strSQL = str_replace("@KAMOK", $postData['KAMOK'], $strSQL);
        $strSQL = str_replace("@HIMOK", $postData['HIMOK'], $strSQL);
        $strSQL = str_replace("@GOKEI", (float) $postData['GOKEI'], $strSQL);

        if ($flg) {
            $TAISK = ($postData['TAISK'] == '1') ? "2" : "1";
            $strSQL = str_replace("@TAISK", $TAISK, $strSQL);
            $strSQL = str_replace("@OAKB", "O", $strSQL);
        } else {
            $strSQL = str_replace("@TAISK", $postData['TAISK'], $strSQL);
            $strSQL = str_replace("@OAKB", "A", $strSQL);
        }

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);
        return $strSQL;

    }

    public function fncInsertFurikae($postData = NULL, $flg = FALSE)
    {
        $strSql = $this->fncInsertFurikaeSql($postData, $flg);
        return parent::Do_Execute($strSql);
    }

    public function fncFurikaeExistChk($postData = NULL)
    {
        $strSql = $this->fncFurikaeExistChkSql($postData);
        return parent::select($strSql);
    }

    public function fncControlNenChk()
    {
        $strSql = $this->fncControlNenChkSql();
        return parent::select($strSql);
    }

    public function fncDeleteFurikae($postData = NULL)
    {

        $strSql = $this->fncDeleteFurikaeSql($postData);
        return parent::delete($strSql);
    }

    public function fncFurikaeExist($postData = NULL)
    {

        $strSql = $this->fncFurikaeExistSql($postData);
        return parent::select($strSql);
    }

    public function fncDataSetKamoku()
    {

        $strSql = $this->fncDataSetKamokuSql();
        return parent::select($strSql);
    }

    public function fncDataSet()
    {

        $strSql = $this->fncDataSetSql();
        return parent::select($strSql);
    }

    public function fncDataSetJqGrid()
    {

        $strSql = $this->fncDataSetJqGridSql();
        return parent::select($strSql);
    }

    public function fncFurikaeSet($postData = NULL)
    {

        $strSql = $this->fncFurikaeSetSql($postData);
        return parent::select($strSql);
    }

    public function fncFurikaeMotSet($postData = NULL)
    {

        $strSql = $this->fncFurikaeMotSetSql($postData);
        return parent::select($strSql);
    }

    // function FncSqlNv2($objValue, $objReturn = "", $intKind = 1)
    // {
    // if ($objValue === null)
    // {
    // if ($objReturn != "")
    // {
    // return $objReturn;
    // }
    // else
    // {
    // return "''";
    // }
    // }
    // else
    // {
    // if ($objValue == "")
    // {
    // return "Null";
    // }
    // else
    // {
    // if ($intKind == 1)
    // {
    // return "'" . str_replace("'", "''", $objValue) . "'";
    // }
    // else
    // {
    // return str_replace("'", "''", $objValue);
    // }
    // }
    // }
    // }

}