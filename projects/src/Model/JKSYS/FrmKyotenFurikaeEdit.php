<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmKyotenFurikaeEdit extends ClsComDb
{
    protected $conn_orl = "";
    protected $Sel_Array = "";

    function fncDeleteFurikaeSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "DELETE FROM HKYOTENFURIKAE" . "\r\n";
        $strSQL .= "WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "AND    CMN_NO = NVL('@CMNNO','9999999999')" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@EDANO'" . "\r\n";

        $strSQL = str_replace("@NENGETU", $postData['cboKeiriBi'], $strSQL);
        $strSQL = str_replace("@EDANO", $postData['txtEdaNO'], $strSQL);
        $strSQL = str_replace("@CMNNO", $postData['txtCMNNO'], $strSQL);

        return $strSQL;
    }

    function fncInsertFurikaeSql($txtSyainCD, $txtFurikaeKin, $MOTOSAKI_KB, $postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "KyotenFurikaeEdit";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";

        $strSQL .= "INSERT INTO HKYOTENFURIKAE" . "\r\n";
        $strSQL .= "(           NENGETU" . "\r\n";
        $strSQL .= ",           SYAIN_CD" . "\r\n";
        $strSQL .= ",           CMN_NO" . "\r\n";
        $strSQL .= ",           EDA_NO" . "\r\n";
        $strSQL .= ",           UC_NO" . "\r\n";
        $strSQL .= ",           DISP_MOJI" . "\r\n";
        $strSQL .= ",           FURIKAE_KIN" . "\r\n";
        $strSQL .= ",           MOTOSAKI_KB" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "VALUES" . "\r\n";
        $strSQL .= "(           '@NENGETU'" . "\r\n";
        $strSQL .= ",           '@SYAIN_CD'" . "\r\n";
        $strSQL .= ",           NVL('@CMN_NO','9999999999')" . "\r\n";
        $strSQL .= ",           @EDA_NO" . "\r\n";
        $strSQL .= ",           '@UC_NO'" . "\r\n";
        $strSQL .= ",           '@DISP_MOJI'" . "\r\n";
        $strSQL .= ",           @FURIKAE_KIN" . "\r\n";
        $strSQL .= ",           '@MOTOSAKI_KB'" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           '@UPDUSER'" . "\r\n";
        $strSQL .= ",           '@UPDAPP'" . "\r\n";
        $strSQL .= ",           '@UPDCLT'" . "\r\n";

        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@NENGETU", $postData['cboKeiriBi'], $strSQL);
        $strSQL = str_replace("@CMN_NO", $postData['txtCMNNO'], $strSQL);
        $strSQL = str_replace("@UC_NO", $postData['lblUCNO'], $strSQL);
        $strSQL = str_replace("@DISP_MOJI", $postData['txtDispMoji'], $strSQL);
        $strSQL = str_replace("@EDA_NO", $postData['txtEdaNO'], $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        $strSQL = str_replace("@SYAIN_CD", $txtSyainCD, $strSQL);
        $strSQL = str_replace("@FURIKAE_KIN", $txtFurikaeKin, $strSQL);
        $strSQL = str_replace("@MOTOSAKI_KB", $MOTOSAKI_KB, $strSQL);
        return $strSQL;

    }

    public function fncGetTougetuSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYORI_YM,1,4) || '/' || SUBSTR(SYORI_YM,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= "FROM JKCONTROLMST" . "\r\n";
        $strSQL .= "WHERE ID = '01'" . "\r\n";

        return $strSQL;

    }

    public function fncGetAllSyainJqGridSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT SYAIN_NM" . "\r\n";
        $strSQL .= ", SYAIN_NO" . "\r\n";
        $strSQL .= "FROM   JKSYAIN" . "\r\n";

        return $strSQL;

    }

    public function fncFurikaeExistSql($postData, $strCmnCnv = "9999999999")
    {
        $strSQL = "";

        $strSQL .= "SELECT FRI.NENGETU" . "\r\n";
        $strSQL .= ",      FRI.EDA_NO" . "\r\n";
        $strSQL .= ",      FRI.MOTOSAKI_KB" . "\r\n";
        $strSQL .= ",      FRI.SYAIN_CD" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      REPLACE(FRI.CMN_NO,'9999999999','') CMN_NO" . "\r\n";
        $strSQL .= ",      FRI.UC_NO" . "\r\n";
        $strSQL .= ",      FRI.DISP_MOJI" . "\r\n";
        $strSQL .= ",      FRI.FURIKAE_KIN" . "\r\n";
        $strSQL .= "FROM   HKYOTENFURIKAE FRI" . "\r\n";
        $strSQL .= "LEFT JOIN JKSYAIN SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = FRI.SYAIN_CD" . "\r\n";
        $strSQL .= "WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@SEQNO'" . "\r\n";
        $strSQL .= "AND    CMN_NO = NVL('@CMN','@CNV')" . "\r\n";
        if ($postData['strMSKb'] != "") {
            $strSQL .= "AND   MOTOSAKI_KB = '@MSKB'" . "\r\n";
        }
        $strSQL = str_replace("@NENGETU", str_replace("/", "", $postData['GetTougetuNew']), $strSQL);
        $strSQL = str_replace("@SEQNO", $postData['txtEdaNO'], $strSQL);
        $strSQL = str_replace("@CMN", $postData['txtCMNNO'], $strSQL);
        $strSQL = str_replace("@CNV", $strCmnCnv, $strSQL);
        $strSQL = str_replace("@MSKB", $postData['strMSKb'], $strSQL);
        return $strSQL;

    }

    public function fncM41E10CheckSql($postData)
    {
        $strSQL = "";

        $strSQL .= "SELECT UC_NO" . "\r\n";
        $strSQL .= "FROM   M41E10@PPR3634" . "\r\n";
        $strSQL .= "WHERE  CMN_NO = '@CMNNO'" . "\r\n";

        $strSQL = str_replace("@CMNNO", $postData['strCMNNO'], $strSQL);

        return $strSQL;

    }

    public function fncSyainMstCheckSql($postData)
    {
        $strSQL = "";

        $strSQL .= "SELECT SYAIN_NM" . "\r\n";
        $strSQL .= "FROM   JKSYAIN" . "\r\n";
        $strSQL .= "WHERE  SYAIN_NO = '@SYAINNO'" . "\r\n";

        $strSQL = str_replace("@SYAINNO", $postData['strSyainNO'], $strSQL);

        return $strSQL;

    }

    public function fncEdaNoSetSql($postData)
    {
        $strSQL = "";

        $strSQL .= "SELECT (NVL(MAX(EDA_NO),0) + 1) NO" . "\r\n";
        $strSQL .= "FROM   HKYOTENFURIKAE" . "\r\n";
        $strSQL .= "WHERE  NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "AND    CMN_NO = NVL('@CMN','9999999999')" . "\r\n";

        $strSQL = str_replace("@NENGETU", str_replace("/", "", $postData['cboKeiriBi']), $strSQL);
        $strSQL = str_replace("@CMN", $postData['txtCMNNO'], $strSQL);

        return $strSQL;

    }

    public function fncGetTougetu()
    {
        $strSql = $this->fncGetTougetuSql();
        return parent::select($strSql);
    }

    public function fncGetAllSyainJqGrid()
    {
        $strSql = $this->fncGetAllSyainJqGridSql();
        return parent::select($strSql);
    }

    public function fncFurikaeExist($postData)
    {
        $strSql = $this->fncFurikaeExistSql($postData);
        return parent::select($strSql);
    }

    public function fncM41E10Check($postData)
    {
        $strSql = $this->fncM41E10CheckSql($postData);
        return parent::select($strSql);
    }

    public function fncSyainMstCheck($postData)
    {
        $strSql = $this->fncSyainMstCheckSql($postData);
        return parent::select($strSql);
    }

    public function fncEdaNoSet($postData)
    {
        $strSql = $this->fncEdaNoSetSql($postData);
        return parent::select($strSql);
    }

    public function fncInsertFurikae($txtSyainCD, $txtFurikaeKin, $MOTOSAKI_KB, $postData)
    {
        $strSql = $this->fncInsertFurikaeSql($txtSyainCD, $txtFurikaeKin, $MOTOSAKI_KB, $postData);
        return parent::insert($strSql);
    }

    public function fncDeleteFurikae($postData = NULL)
    {

        $strSql = $this->fncDeleteFurikaeSql($postData);
        return parent::delete($strSql);
    }


}
