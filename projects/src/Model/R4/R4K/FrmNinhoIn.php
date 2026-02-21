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

class FrmNinhoIn extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";
    protected $ClsComFnc = "";

    function frmSampleLoadDateSql()
    {

        $strSQL = "SELECT ID" . "\r\n";

        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";

        $strSQL .= "   FROM  HKEIRICTL" . "\r\n";

        $strSQL .= "  WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    function fncTableDeleteSql($postData = NULL)
    {
        $strSQL = "";

        $strSQL .= "DELETE FROM HSTAFFNINHO" . "\r\n";

        $strSQL .= "WHERE KEIJO_DT= '@KEIJOBI' " . "\r\n";

        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);

        return $strSQL;

    }

    function fncGetSqlInsert($value, $postData, $lngHokenryo, $strGetArray = NULL)
    {
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "NinhoIn";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HSTAFFNINHO (";
        $strSQL .= " KEIJO_DT";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", SYAIN_NO";
        $strSQL .= ", SYAIN_NM";
        $strSQL .= ", NINPO_GK";
        $strSQL .= ", BIKO";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ", UPD_SYA_CD";
        $strSQL .= ", UPD_PRG_ID";
        $strSQL .= ", UPD_CLT_NM";

        $strSQL .= ") VALUES (";
        $strSQL .= "  @KEIJO_DT";
        $strSQL .= ", @BUSYO_CD";
        $strSQL .= ", @SYAIN_NO";
        $strSQL .= ", @SYAIN_NM";
        $strSQL .= ", @NINPO_GK";
        $strSQL .= ", @BIKO";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", '@UPDUSER'" . "\r\n";
        $strSQL .= ", '@UPDAPP'" . "\r\n";
        $strSQL .= ", '@UPDCLT'" . "\r\n";
        $strSQL .= ")";

        $strSQL = str_replace("@KEIJO_DT", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv(str_pad(rtrim($value[0]), 3, "0", STR_PAD_LEFT)), $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->ClsComFnc->FncSqlNv(str_pad(rtrim($value[1]), 5, "0", STR_PAD_LEFT)), $strSQL);
        $strSQL = str_replace("@SYAIN_NM", $this->ClsComFnc->FncSqlNv($value[2]), $strSQL);
        $strSQL = str_replace("@NINPO_GK", $lngHokenryo, $strSQL);
        $strSQL = str_replace("@BIKO", $this->ClsComFnc->FncSqlNv(rtrim($strGetArray[16])), $strSQL);
        return $strSQL;

    }

    public function ExcuteFncGetSqlInsert($value, $postData, $lngHokenryo, $strGetArray)
    {

        $strSql = $this->fncGetSqlInsert($value, $postData, $lngHokenryo, $strGetArray);

        return parent::Do_Execute($strSql);
    }

    public function fncTableDelete($postData = NULL)
    {
        $strSql = $this->fncTableDeleteSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function frmSampleLoadDate()
    {
        $strSql = $this->frmSampleLoadDateSql();

        return parent::select($strSql);
    }

}