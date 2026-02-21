<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151023           #2228						   BUG                              li  　　
 * --------------------------------------------------------------------------------------------
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

class FrmSCUrkIn extends ClsComDb
{
    public $ClsComFnc;
    //---yushuangji add start---
    //---execute---
    //--
    public function fncTableDelete_HSCURKZAN($cboYM, $radioChk)
    {
        $strSql = $this->fncTableDelete_HSCURKZAN_sql($cboYM, $radioChk);
        return parent::Do_Execute($strSql);
    }

    //--
    public function fncSCUrkZanInsert($strKeijyoDT, $strRecArr, $intNauKb)
    {
        $strSql = $this->fncSCUrkZanInsert_sql($strKeijyoDT, $strRecArr, $intNauKb);
        return parent::Do_Execute($strSql);
    }

    //---sql---
    //

    //-
    public function fncSCUrkZanInsert_sql($strKeijyoDT, $strRecArr, $intNauKb)
    {
        $clscomfnc = new ClsComFnc();
        $sqlstr = "";
        //---20151023 li UPD S.
        //$sqlstr .= "INSERT INTO HSCURKZAN1\n";
        $sqlstr .= "INSERT INTO HSCURKZAN\n";
        //---20151023 li UPD E.
        $sqlstr .= "           (\n";
        $sqlstr .= "            KEIJO_DT\n";
        $sqlstr .= "           ,HNS_CD\n";
        $sqlstr .= "           ,SEQ_NO\n";
        $sqlstr .= "           ,ID\n";
        $sqlstr .= "           ,BUSYO_CD\n";
        $sqlstr .= "           ,NAU_KB_NM\n";
        $sqlstr .= "           ,TANTO_CD\n";
        $sqlstr .= "           ,KAISYU_TANTO_NM\n";
        $sqlstr .= "           ,URI_DATE\n";
        $sqlstr .= "           ,KYK_CUS_NO\n";
        $sqlstr .= "           ,KYK_CUS_NM\n";
        $sqlstr .= "           ,KYK_CUS_KANA\n";
        $sqlstr .= "           ,SIY_CUS_NO\n";
        $sqlstr .= "           ,SIY_CUS_NM\n";
        $sqlstr .= "           ,SIY_CUS_KANA\n";
        $sqlstr .= "           ,CMN_NO\n";
        $sqlstr .= "           ,KAMOK_CD\n";
        $sqlstr .= "           ,ZAN_GK\n";
        $sqlstr .= "           ,GEN_GK\n";
        $sqlstr .= "           ,TEG_GK\n";
        $sqlstr .= "           ,CLE_GK\n";
        $sqlstr .= "           ,SIT_GK\n";
        $sqlstr .= "           ,ZAN_GK_1M\n";
        $sqlstr .= "           ,ZAN_GK_2M\n";
        $sqlstr .= "           ,ZAN_GK_3M\n";
        $sqlstr .= "           ,ZAN_GK_4M\n";
        $sqlstr .= "           ,BIKOU\n";
        $sqlstr .= "           ,NISU\n";
        $sqlstr .= "           ,UPD_DATE\n";
        $sqlstr .= "           ,CREATE_DATE\n";
        $sqlstr .= "           ,UPD_SYA_CD\n";
        $sqlstr .= "           ,UPD_PRG_ID\n";
        $sqlstr .= "           ,UPD_CLT_NM\n";
        $sqlstr .= "           ,NAU_KB\n";

        $sqlstr .= "            )\n";
        $sqlstr .= "VALUES (\n";
        $sqlstr .= "       '@KEIJOBI'\n";
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[0]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[2]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[3]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[8]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim(mb_convert_encoding(mb_substr(str_pad($strRecArr[10], 20, ' ', STR_PAD_RIGHT), 0, 20, "SJIS"), 'UTF-8', 'SJIS')));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[11]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim(mb_convert_encoding(mb_substr(str_pad($strRecArr[12], 30, ' ', STR_PAD_RIGHT), 0, 30, "SJIS"), 'UTF-8', 'SJIS')));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[13]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[14]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim(mb_convert_encoding(mb_substr(str_pad($strRecArr[15], 60, ' ', STR_PAD_RIGHT), 0, 60, "SJIS"), 'UTF-8', 'SJIS')));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim(mb_convert_encoding(mb_substr(str_pad($strRecArr[16], 40, ' ', STR_PAD_RIGHT), 0, 40, "SJIS"), 'UTF-8', 'SJIS')));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[17]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim(mb_convert_encoding(mb_substr(str_pad($strRecArr[18], 60, ' ', STR_PAD_RIGHT), 0, 60, "SJIS"), 'UTF-8', 'SJIS')));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim(mb_convert_encoding(mb_substr(str_pad($strRecArr[19], 40, ' ', STR_PAD_RIGHT), 0, 40, "SJIS"), 'UTF-8', 'SJIS')));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[20]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[21]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[22]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[23]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[24]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[25]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim($strRecArr[26]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[27]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[28]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[29]));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[30]));
        $sqlstr .= "      ," . $clscomfnc->FncSqlNv(rtrim(mb_convert_encoding(mb_substr(str_pad($strRecArr[31], 50, ' ', STR_PAD_RIGHT), 0, 50, "SJIS"), 'UTF-8', 'SJIS')));
        $sqlstr .= "      ," . $this->FncSqlNz2(rtrim($strRecArr[32]));
        $sqlstr .= "      ,SYSDATE\n";
        $sqlstr .= "      ,SYSDATE\n";
        $sqlstr .= "      ,'@UPDUSER'\n";
        $sqlstr .= "      ,'@UPDAPP'\n";
        $sqlstr .= "      ,'@UPDCLT'\n";
        $sqlstr .= "      ,'@NAUKB'\n";

        $sqlstr .= "            )\n";

        $sqlstr = str_replace("@KEIJOBI", $strKeijyoDT, $sqlstr);
        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDAPP", "frmSCUrkIn", $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $sqlstr);
        $sqlstr = str_replace("@NAUKB", $intNauKb, $sqlstr);
        return $sqlstr;
    }

    //-
    public function fncTableDelete_HSCURKZAN_sql($strKeijyoDT, $intNauKb)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HSCURKZAN\n";
        $sqlstr .= " WHERE KEIJO_DT = '" . $strKeijyoDT . "'\n";
        $sqlstr .= "   AND NAU_KB = '" . $intNauKb . "'\n";
        return $sqlstr;
    }

    //-
    public function FncSqlNz2($objValue, $objReturn = "")
    {
        //---NULLの場合---
        if ($objValue == null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "Null";
            }
        }
        //---以外の場合---
        else {
            if ($objValue == "") {
                return "Null";
            } else {
                return $objValue;
            }
        }
    }

    //---yushuangji add end---
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";

    function frmSampleLoadDateSql()
    {

        $strSQL = "";
        $strSQL .= "SELECT ID\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU\n";
        $strSQL .= ",      KISYU_YMD KISYU\n";
        $strSQL .= ",      KI\n";
        $strSQL .= "FROM   HKEIRICTL\n";
        $strSQL .= "WHERE  ID = '01'";

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
        $m1 = (int) $m;

        $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
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
        $m1 = (int) $m;

        $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
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
        $m1 = (int) $m;

        $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
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
        $m1 = (int) $m;

        $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
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