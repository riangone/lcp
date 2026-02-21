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

class FrmHoyuDaisuIn extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $ClsComFnc;

    function frmSampleLoadDateSql()
    {

        $strSQL = "SELECT ID" . "\r\n";

        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";

        $strSQL .= "   FROM  HKEIRICTL" . "\r\n";

        $strSQL .= "  WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    function fncSelHksaibanSql()
    {

        $strSQL = "";

        $strSQL .= "SELECT SEQNO" . "\r\n";

        $strSQL .= "FROM   HKSAIBAN" . "\r\n";

        $strSQL .= " WHERE ID = 'KEIRI'" . "\r\n";

        return $strSQL;

    }

    function fncUpdHksaibanSql($intSu)
    {

        $strSQL = "";

        $strSQL .= "UPDATE HKSAIBAN" . "\r\n";

        $strSQL .= "   SET SEQNO = (SELECT SEQNO + @SU FROM HKSAIBAN WHERE ID = 'KEIRI')" . "\r\n";

        $strSQL .= " WHERE ID = 'KEIRI'" . "\r\n";

        $strSQL = str_replace("@SU", $intSu, $strSQL);

        return $strSQL;

    }

    function fncZandakaDeleteSql($postData = NULL)
    {

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;

        $strSQL = "";

        $strSQL .= "DELETE FROM HKNRZAN" . "\r\n";

        $strSQL .= "WHERE KEIJO_DT= '@KEIJOBI' " . "\r\n";

        $strSQL .= "   AND KAMOK_CD  IN ('00901','00902')" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", $ymd, $strSQL);

        return $strSQL;

    }

    function fncFurikaeDeleteSql($postData = NULL)
    {

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;

        $strSQL = "";

        $strSQL .= "DELETE FROM HFURIKAE" . "\r\n";

        $strSQL .= "WHERE KEIJO_DT= '@KEIJOBI' " . "\r\n";

        $strSQL .= "AND    HASEI_MOTO_KB = 'HY' " . "\r\n";

        $strSQL = str_replace("@KEIJOBI", $ymd, $strSQL);

        return $strSQL;

    }

    function fncGetZandakaInsertSql($value, $postData = NULL)
    {
        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "HoyuDaisuIn";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HKNRZAN (";
        $strSQL .= "  KEIJO_DT";
        $strSQL .= ", DATA_KB";
        $strSQL .= ", TAISK_KB";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", KAMOK_CD";
        $strSQL .= ", ZEN_GK";
        $strSQL .= ", TOU_GK";
        $strSQL .= ", TAISYOU_GK";
        $strSQL .= ", KINRI_GK";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ", UPD_SYA_CD" . "\r\n";
        $strSQL .= ", UPD_PRG_ID" . "\r\n";
        $strSQL .= ", UPD_CLT_NM" . "\r\n";

        $strSQL .= ") VALUES (";
        $strSQL .= "  @KEIJO_DT";
        $strSQL .= ", ' '";
        $strSQL .= ", '1'";
        $strSQL .= ", @BUSYO_CD";
        $strSQL .= ", @KAMOK_CD";
        $strSQL .= ", @ZEN_GK";
        $strSQL .= ", @TOU_GK";
        $strSQL .= ", 0";
        $strSQL .= ", 0";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", '@UPDUSER'" . "\r\n";
        $strSQL .= ", '@UPDAPP'" . "\r\n";
        $strSQL .= ", '@UPDCLT'" . "\r\n";

        $strSQL .= ")";

        $strSQL = str_replace("@KEIJO_DT", $ymd, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($value[1]), $strSQL);
        $strSQL = str_replace("@ZEN_GK", $this->ClsComFnc->FncSqlNz($value[2]), $strSQL);
        $strSQL = str_replace("@TOU_GK", $this->ClsComFnc->FncSqlNz($value[3]), $strSQL);
        $strSQL = str_replace("@KAMOK_CD", $this->ClsComFnc->FncSqlNv($value[5]), $strSQL);

        return $strSQL;

    }

    function fncGetFurikaeInsertSql($value, $lngSeqNO, $intGyoNO, $postData = NULL)
    {

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "HoyuDaisuIn";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HFURIKAE (";
        $strSQL .= "  KEIJO_DT";
        $strSQL .= ", ID";
        $strSQL .= ", DENPY_NO";
        $strSQL .= ", GYO_NO";
        $strSQL .= ", TAISK_KB";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", KAMOK_CD";
        $strSQL .= ", HIMOK_CD";
        $strSQL .= ", KEIJO_GK";
        $strSQL .= ", AITE_BUSYO_CD";
        $strSQL .= ", AITE_KAMOK_CD";
        $strSQL .= ", AITE_HIMOK_CD";
        $strSQL .= ", OA_KB";
        $strSQL .= ", HASEI_MOTO_KB";
        $strSQL .= ", CEL_DATE";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ", UPD_SYA_CD" . "\r\n";
        $strSQL .= ", UPD_PRG_ID" . "\r\n";
        $strSQL .= ", UPD_CLT_NM" . "\r\n";

        $strSQL .= ") VALUES (";
        $strSQL .= "  @KEIJO_DT";
        $strSQL .= ", '01'";
        $strSQL .= ", @DENPY_NO";
        $strSQL .= ", @GYO_NO";
        $strSQL .= ", '1'";
        $strSQL .= ", @BUSYO_CD";
        $strSQL .= ", @KAMOK_CD";
        $strSQL .= ",''";
        $strSQL .= ", @KEIJO_GK";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", 'HY'";
        $strSQL .= ", NULL";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", '@UPDUSER'" . "\r\n";
        $strSQL .= ", '@UPDAPP'" . "\r\n";
        $strSQL .= ", '@UPDCLT'" . "\r\n";

        $strSQL .= ")";

        $strSQL = str_replace("@KEIJO_DT", $ymd, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        $strSQL = str_replace("@DENPY_NO", $this->ClsComFnc->FncSqlNv($lngSeqNO), $strSQL);
        $strSQL = str_replace("@GYO_NO", $this->ClsComFnc->FncSqlNz($intGyoNO), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($value[1]), $strSQL);
        $strSQL = str_replace("@KEIJO_GK", $this->ClsComFnc->FncSqlNz($value[3]), $strSQL);
        $strSQL = str_replace("@KAMOK_CD", $this->ClsComFnc->FncSqlNv($value[5]), $strSQL);

        return $strSQL;

    }

    public function fncGetZandakaInsert($value, $postData)
    {

        $strSql = $this->fncGetZandakaInsertSql($value, $postData);

        return parent::Do_Execute($strSql);
    }

    public function fncGetFurikaeInsert($value, $lngSeqNO, $intGyoNO, $postData)
    {

        $strSql = $this->fncGetFurikaeInsertSql($value, $lngSeqNO, $intGyoNO, $postData);

        return parent::Do_Execute($strSql);
    }

    public function fncFurikaeDelete($postData = NULL)
    {
        $strSql = $this->fncFurikaeDeleteSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncZandakaDelete($postData = NULL)
    {
        $strSql = $this->fncZandakaDeleteSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncUpdHksaiban($intSu)
    {
        $strSql = $this->fncUpdHksaibanSql($intSu);

        return parent::Do_Execute($strSql);
    }

    public function fncSelHksaiban()
    {
        $strSql = $this->fncSelHksaibanSql();

        return parent::Fill($strSql);
    }

    public function frmSampleLoadDate()
    {
        $strSql = $this->frmSampleLoadDateSql();

        return parent::select($strSql);
    }

}