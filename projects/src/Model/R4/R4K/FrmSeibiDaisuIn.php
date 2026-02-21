<?php
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL　　　　　　　　
 * 20151021           #2184                        BUG                              Yuanjh
 * 20151104			 #2239							BUG								Yinhuaiyu
 * 20170809           -----                        無償整備台数 00842 追加　　　HM
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSeibiDaisuIn extends ClsComDb
{
    public $ClsComFnc = '';
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";

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

        $strSQL .= "   AND KAMOK_CD = '00903'" . "\r\n";

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

        $strSQL .= "AND    HASEI_MOTO_KB = 'SB' " . "\r\n";

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
        $UPDAPP = "SeibiDaisuIn";
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
        $strSQL .= ", '00903'";
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

        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($value[13]), $strSQL);
        $strSQL = str_replace("@ZEN_GK", $this->ClsComFnc->FncSqlNz($value[9]), $strSQL);
        $strSQL = str_replace("@TOU_GK", $this->ClsComFnc->FncSqlNz($value[10]), $strSQL);

        return $strSQL;

    }

    function fncGetFurikaeInsertSql($value, $lngSeqNO, $intGyoNO, $i, $postData = NULL)
    {
        $strArrayKmk = array(
            array(
                "00841",
                "11"
            ),
            array(
                "00841",
                "21"
            ),
            array(
                "00841",
                "31"
            ),
            array(
                "00841",
                "41"
            ),
            array(
                "",
                ""
            ),
            array(
                "00842",
                ""
            ),
            array(
                "00845",
                ""
            ),
            array(
                "00846",
                ""
            ),
            array(
                "00843",
                ""
            ),
            array(
                "",
                ""
            ),
            array(
                "00903",
                ""
            ),
            array(
                "91411",
                "41"
            ),
            array(
                "91411",
                "42"
            )
        );

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "SeibiDaisuIn";
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
        $strSQL .= ", @HIMOK_CD";
        $strSQL .= ", @KEIJO_GK";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", 'SB'";
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
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($value[13]), $strSQL);
        //20151104 Yin UPD S
        //$strSQL = str_replace("@KEIJO_GK", $this -> ClsComFnc -> FncSqlNz($value[$i]), $strSQL);
        $strSQL = str_replace("@KEIJO_GK", $this->ClsComFnc->FncSqlNz($value[$i - 1]), $strSQL);
        //20151104 Yin UPD E
        //--20151021  Yuanjh UPD S.
        //$strSQL = str_replace("@KAMOK_CD", $this -> ClsComFnc -> FncSqlNv($strArrayKmk[$i][0]), $strSQL);
        //$strSQL = str_replace("@HIMOK_CD", $this -> ClsComFnc -> FncSqlNv($strArrayKmk[$i][1]), $strSQL);
        $strSQL = str_replace("@KAMOK_CD", $this->ClsComFnc->FncSqlNv($strArrayKmk[$i - 1][0]), $strSQL);
        $strSQL = str_replace("@HIMOK_CD", $this->ClsComFnc->FncSqlNv($strArrayKmk[$i - 1][1]), $strSQL);
        //--20151021  Yuanjh UPD E.
        return $strSQL;

    }

    public function fncGetZandakaInsert($value, $postData)
    {
        $strSql = $this->fncGetZandakaInsertSql($value, $postData);
        return parent::Do_Execute($strSql);
    }

    public function fncGetFurikaeInsert($value, $lngSeqNO, $intGyoNO, $i, $postData)
    {
        $strSql = $this->fncGetFurikaeInsertSql($value, $lngSeqNO, $intGyoNO, $i, $postData);
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