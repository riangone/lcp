<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmKaikeiEdit extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $ClsComFnc;

    function fncKaikeiSetSql($postData = NULL)
    {

        // $this -> ClsComFnc = new ClsComFnc();
        $strSQL = "";

        $strSQL .= "SELECT KAI.INP_BUSYO" . "\r\n";
        $strSQL .= ",      KAI.KEIJO_DT" . "\r\n";
        $strSQL .= ",      KAI.SYOHY_NO" . "\r\n";
        $strSQL .= ",      KAI.DENPY_NO" . "\r\n";
        $strSQL .= ",      KAI.GYO_NO" . "\r\n";
        $strSQL .= ",      KAI.L_BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS_L.BUSYO_NM L_BUSYO_NM" . "\r\n";
        $strSQL .= ",      KAI.L_KAMOK_CD" . "\r\n";
        $strSQL .= ",      M_KMK_L.KAMOKNM L_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAI.L_KOMOK_CD" . "\r\n";
        $strSQL .= ",      KAI.L_HIMOK_CD" . "\r\n";
        $strSQL .= ",      KAI.L_BK" . "\r\n";
        $strSQL .= ",      KAI.L_UC_NO" . "\r\n";
        $strSQL .= ",      KAI.L_SYAIN_NO" . "\r\n";
        $strSQL .= ",      KAI.R_BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS_R.BUSYO_NM R_BUSYO_NM" . "\r\n";
        $strSQL .= ",      KAI.R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      M_KMK_R.KAMOKNM R_KAMOK_NM" . "\r\n";
        $strSQL .= ",      KAI.R_KOMOK_CD" . "\r\n";
        $strSQL .= ",      KAI.R_HIMOK_CD" . "\r\n";
        $strSQL .= ",      KAI.R_BK" . "\r\n";
        $strSQL .= ",      KAI.R_UC_NO" . "\r\n";
        $strSQL .= ",      KAI.R_SYAIN_NO" . "\r\n";
        $strSQL .= ",      KAI.KEIJO_GK" . "\r\n";
        $strSQL .= ",      KAI.TEKIYO1" . "\r\n";
        $strSQL .= ",      KAI.HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",      KAI.TEKIYO1" . "\r\n";
        $strSQL .= ",      KAI.TEKIYO2" . "\r\n";
        $strSQL .= ",      KAI.TEKIYO3" . "\r\n";
        $strSQL .= ",      KAI.SYOHY_NO" . "\r\n";
        $strSQL .= "FROM   HKAIKEI KAI" . "\r\n";
        $strSQL .= "LEFT  JOIN " . "\r\n";

        $strSQL .= "       (SELECT KAMOK_CD, KAMOK_NM KAMOKNM" . "\r\n";
        $strSQL .= "        FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "        WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "       ) M_KMK_L" . "\r\n";

        $strSQL .= "ON    M_KMK_L.KAMOK_CD = KAI.L_KAMOK_CD" . "\r\n";
        $strSQL .= "LEFT  JOIN " . "\r\n";

        $strSQL .= "       (SELECT KAMOK_CD, KAMOK_NM KAMOKNM" . "\r\n";
        $strSQL .= "        FROM M_KAMOKU A" . "\r\n";
        $strSQL .= "        WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
        $strSQL .= "       ) M_KMK_R" . "\r\n";

        $strSQL .= "ON    M_KMK_R.KAMOK_CD = KAI.R_KAMOK_CD" . "\r\n";
        $strSQL .= "LEFT  JOIN HBUSYO BUS_L" . "\r\n";
        $strSQL .= "ON    BUS_L.BUSYO_CD = KAI.L_BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT  JOIN HBUSYO BUS_R" . "\r\n";
        $strSQL .= "ON    BUS_R.BUSYO_CD = KAI.R_BUSYO_CD" . "\r\n";

        $strSQL .= "WHERE KAI.GYO_NO = '@GYONO'" . "\r\n";
        $strSQL .= "AND   KAI.KEIJO_DT = '@KEIJOBI'" . "\r\n";
        $strSQL .= "AND   KAI.DENPY_NO = '@DENPYNO'" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJYO']), $strSQL);
        $strSQL = str_replace("@DENPYNO", $postData['DENPYO'], $strSQL);
        $strSQL = str_replace("@GYONO", $postData['GYO_NO'], $strSQL);

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

    public function fncControlNenChkSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT SYR_YMD" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    public function fncInsertKaikeiSql($postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "KaikeiEdit";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HKAIKEI" . "\r\n";
        $strSQL .= "(           INP_BUSYO" . "\r\n";
        $strSQL .= ",           KEIJO_DT" . "\r\n";
        $strSQL .= ",           SYOHY_NO" . "\r\n";
        $strSQL .= ",           DENPY_NO" . "\r\n";
        $strSQL .= ",           GYO_NO" . "\r\n";
        $strSQL .= ",           L_BUSYO_CD" . "\r\n";
        $strSQL .= ",           L_KAMOK_CD" . "\r\n";
        $strSQL .= ",           L_KOMOK_CD" . "\r\n";
        $strSQL .= ",           L_HIMOK_CD" . "\r\n";
        $strSQL .= ",           L_BK" . "\r\n";
        $strSQL .= ",           L_UC_NO" . "\r\n";
        $strSQL .= ",           L_SYAIN_NO" . "\r\n";
        $strSQL .= ",           R_BUSYO_CD" . "\r\n";
        $strSQL .= ",           R_KAMOK_CD" . "\r\n";
        $strSQL .= ",           R_KOMOK_CD" . "\r\n";
        $strSQL .= ",           R_HIMOK_CD" . "\r\n";
        $strSQL .= ",           R_BK" . "\r\n";
        $strSQL .= ",           R_UC_NO" . "\r\n";
        $strSQL .= ",           R_SYAIN_NO" . "\r\n";
        $strSQL .= ",           KEIJO_GK" . "\r\n";
        $strSQL .= ",           TEKIYO1" . "\r\n";
        $strSQL .= ",           TEKIYO2" . "\r\n";
        $strSQL .= ",           TEKIYO3" . "\r\n";
        $strSQL .= ",           KAZEI_KB" . "\r\n";
        $strSQL .= ",           ZEI_RT_KB" . "\r\n";
        $strSQL .= ",           HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",           CEL_DATE" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES" . "\r\n";
        $strSQL .= "(           NULL" . "\r\n";
        $strSQL .= ",           '@KEIJO_DT'" . "\r\n";
        $strSQL .= ",           '@SYOHY_NO'" . "\r\n";
        $strSQL .= ",           '@DENPY_NO'" . "\r\n";
        $strSQL .= ",           NVL((SELECT MAX(GYO_NO)" . "\r\n";
        $strSQL .= "                 FROM   HKAIKEI" . "\r\n";
        $strSQL .= "                 WHERE  KEIJO_DT = '@KEIJO_DT'" . "\r\n";
        $strSQL .= "                 AND    DENPY_NO = '@DENPY_NO'),0) + 1" . "\r\n";
        $strSQL .= ",           '@L_BUSYO_CD'" . "\r\n";
        $strSQL .= ",           '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",           '@L_KOMOK_CD'" . "\r\n";
        $strSQL .= ",           '@L_HIMOK_CD'" . "\r\n";
        $strSQL .= ",           '@L_BK'" . "\r\n";
        $strSQL .= ",           '@L_UC_NO'" . "\r\n";
        $strSQL .= ",           '@L_SYAIN_NO'" . "\r\n";
        $strSQL .= ",           '@R_BUSYO_CD'" . "\r\n";
        $strSQL .= ",           '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",           '@R_KOMOK_CD'" . "\r\n";
        $strSQL .= ",           '@R_HIMOK_CD'" . "\r\n";
        $strSQL .= ",           '@R_BK'" . "\r\n";
        $strSQL .= ",           '@R_UC_NO'" . "\r\n";
        $strSQL .= ",           '@R_SYAIN_NO'" . "\r\n";
        $strSQL .= ",           @KEIJO_GK" . "\r\n";
        $strSQL .= ",           '@TEKIYO1'" . "\r\n";
        $strSQL .= ",           '@TEKIYO2'" . "\r\n";
        $strSQL .= ",           '@TEKIYO3'" . "\r\n";
        $strSQL .= ",           NULL" . "\r\n";
        $strSQL .= ",           NULL" . "\r\n";
        $strSQL .= ",           'KA'" . "\r\n";
        $strSQL .= ",           NULL" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           '@UPDUSER'" . "\r\n";
        $strSQL .= ",           '@UPDAPP'" . "\r\n";
        $strSQL .= ",           '@UPDCLT'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@KEIJO_DT", $postData['KEIJO_DT'], $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $postData['SYOHY_NO'], $strSQL);
        $strSQL = str_replace("@DENPY_NO", $postData['DENPY_NO'], $strSQL);
        $strSQL = str_replace("@L_BUSYO_CD", $postData['L_BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@L_KAMOK_CD", $postData['L_KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@L_KOMOK_CD", $postData['L_KOMOK_CD'], $strSQL);
        $strSQL = str_replace("@L_HIMOK_CD", $postData['L_HIMOK_CD'], $strSQL);
        $strSQL = str_replace("@L_BK", $postData['L_BK'], $strSQL);
        $strSQL = str_replace("@L_UC_NO", $postData['L_UC_NO'], $strSQL);
        $strSQL = str_replace("@L_SYAIN_NO", $postData['L_SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@R_BUSYO_CD", $postData['R_BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@R_KAMOK_CD", $postData['R_KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@R_KOMOK_CD", $postData['R_KOMOK_CD'], $strSQL);
        $strSQL = str_replace("@R_HIMOK_CD", $postData['R_HIMOK_CD'], $strSQL);
        $strSQL = str_replace("@R_BK", $postData['R_BK'], $strSQL);
        $strSQL = str_replace("@R_UC_NO", $postData['R_UC_NO'], $strSQL);
        $strSQL = str_replace("@R_SYAIN_NO", $postData['R_SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@KEIJO_GK", $postData['KEIJO_GK'], $strSQL);
        $strSQL = str_replace("@TEKIYO1", $postData['TEKIYO1'], $strSQL);
        $strSQL = str_replace("@TEKIYO2", $postData['TEKIYO2'], $strSQL);
        $strSQL = str_replace("@TEKIYO3", $postData['TEKIYO3'], $strSQL);

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        return $strSQL;
    }

    public function fncUpdateKaikeiSql($postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "KaikeiEdit";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "UPDATE HKAIKEI" . "\r\n";
        $strSQL .= "SET    SYOHY_NO = '@SYOHY_NO'" . "\r\n";
        $strSQL .= ",      L_BUSYO_CD = '@L_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      L_KAMOK_CD = '@L_KAMOK_CD'" . "\r\n";
        $strSQL .= ",      L_KOMOK_CD = '@L_KOMOK_CD'" . "\r\n";
        $strSQL .= ",      L_HIMOK_CD = '@L_HIMOK_CD'" . "\r\n";
        $strSQL .= ",      L_BK = '@L_BK'" . "\r\n";
        $strSQL .= ",      L_UC_NO = '@L_UC_NO'" . "\r\n";
        $strSQL .= ",      L_SYAIN_NO = '@L_SYAIN_NO'" . "\r\n";
        $strSQL .= ",      R_BUSYO_CD = '@R_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      R_KAMOK_CD = '@R_KAMOK_CD'" . "\r\n";
        $strSQL .= ",      R_KOMOK_CD = '@R_KOMOK_CD'" . "\r\n";
        $strSQL .= ",      R_HIMOK_CD = '@R_HIMOK_CD'" . "\r\n";
        $strSQL .= ",      R_BK = '@R_BK'" . "\r\n";
        $strSQL .= ",      R_UC_NO = '@R_UC_NO'" . "\r\n";
        $strSQL .= ",      R_SYAIN_NO = '@R_SYAIN_NO'" . "\r\n";
        $strSQL .= ",      KEIJO_GK = '@KEIJO_GK'" . "\r\n";
        $strSQL .= ",      TEKIYO1 = '@TEKIYO1'" . "\r\n";
        $strSQL .= ",      TEKIYO2 = '@TEKIYO2'" . "\r\n";
        $strSQL .= ",      TEKIYO3 = '@TEKIYO3'" . "\r\n";
        $strSQL .= ",      UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD = '@UPDUSER'" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID = '@UPDAPP'" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM = '@UPDCLT'" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJYO'" . "\r\n";
        $strSQL .= "AND    DENPY_NO = '@DENPYO'" . "\r\n";
        $strSQL .= "AND    GYO_NO = '@GYO_NO'" . "\r\n";

        $strSQL = str_replace("@KEIJYO", $postData['KEIJO_DT'], $strSQL);
        $strSQL = str_replace("@SYOHY_NO", $postData['SYOHY_NO'], $strSQL);
        $strSQL = str_replace("@DENPYO", $postData['DENPY_NO'], $strSQL);
        $strSQL = str_replace("@L_BUSYO_CD", $postData['L_BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@L_KAMOK_CD", $postData['L_KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@L_KOMOK_CD", $postData['L_KOMOK_CD'], $strSQL);
        $strSQL = str_replace("@L_HIMOK_CD", $postData['L_HIMOK_CD'], $strSQL);
        $strSQL = str_replace("@L_BK", $postData['L_BK'], $strSQL);
        $strSQL = str_replace("@L_UC_NO", $postData['L_UC_NO'], $strSQL);
        $strSQL = str_replace("@L_SYAIN_NO", $postData['L_SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@R_BUSYO_CD", $postData['R_BUSYO_CD'], $strSQL);
        $strSQL = str_replace("@R_KAMOK_CD", $postData['R_KAMOK_CD'], $strSQL);
        $strSQL = str_replace("@R_KOMOK_CD", $postData['R_KOMOK_CD'], $strSQL);
        $strSQL = str_replace("@R_HIMOK_CD", $postData['R_HIMOK_CD'], $strSQL);
        $strSQL = str_replace("@R_BK", $postData['R_BK'], $strSQL);
        $strSQL = str_replace("@R_UC_NO", $postData['R_UC_NO'], $strSQL);
        $strSQL = str_replace("@R_SYAIN_NO", $postData['R_SYAIN_NO'], $strSQL);
        $strSQL = str_replace("@KEIJO_GK", $postData['KEIJO_GK'], $strSQL);
        $strSQL = str_replace("@TEKIYO1", $postData['TEKIYO1'], $strSQL);
        $strSQL = str_replace("@TEKIYO2", $postData['TEKIYO2'], $strSQL);
        $strSQL = str_replace("@TEKIYO3", $postData['TEKIYO3'], $strSQL);
        $strSQL = str_replace("@GYO_NO", $postData['GYO_NO'], $strSQL);

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        return $strSQL;
    }

    function fncDataSetKamokuSql()
    {

        $this->ClsComFnc = new ClsComFnc();

        $strSQL = "";

        $strSQL .= "SELECT KAMOK_CD KAMOKUCD, KAMOK_NM KAMOKUNM" . "\r\n";
        $strSQL .= "FROM   M_KAMOKU A" . "\r\n";
        $strSQL .= "WHERE  NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";

        // if (trim($postData['txtNM']) != '') {
        //     $strSQL .= "AND    KAMOK_NM LIKE '@KAMOK%'" . "\r\n";
        // }

        // if (trim($postData['txtCD']) != '') {
        //     $strSQL .= "AND    KAMOK_CD LIKE '@CD%'" . "\r\n";
        // }

        $strSQL .= " ORDER BY KAMOKUCD";

        // $strSQL = str_replace("@KAMOK", $this->ClsComFnc->FncNv($postData['txtNM']), $strSQL);
        // $strSQL = str_replace("@CD", $this->ClsComFnc->FncNv($postData['txtCD']), $strSQL);

        return $strSQL;
    }

    public function fncKaikeiSet($postData = NULL)
    {
        $strSql = $this->fncKaikeiSetSql($postData);
        return parent::select($strSql);
    }

    public function fncDataSet()
    {
        $strSql = $this->fncDataSetSql();
        return parent::select($strSql);
    }

    public function fncDataSetKamoku()
    {
        $strSql = $this->fncDataSetKamokuSql();
        return parent::select($strSql);
    }

    public function fncControlNenChk()
    {
        $strSql = $this->fncControlNenChkSql();
        return parent::select($strSql);
    }

    public function fncInsertKaikei($postData = NULL)
    {
        $strSql = $this->fncInsertKaikeiSql($postData);
        return parent::insert($strSql);
    }

    public function fncUpdateKaikei($postData = NULL)
    {
        $strSql = $this->fncUpdateKaikeiSql($postData);
        return parent::update($strSql);
    }

}