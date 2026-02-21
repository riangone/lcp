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

class FrmJinkenhiIn extends ClsComDb
{
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

        $strSQL .= "DELETE FROM HSTAFFJINKEN" . "\r\n";

        $strSQL .= "WHERE KEIJO_DT= '@KEIJOBI' " . "\r\n";

        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);

        return $strSQL;

    }

    function fncGetSqlInsert($value, $postData = NULL)
    {
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "JinkenhiIn";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HSTAFFJINKEN (";
        $strSQL .= " KEIJO_DT";
        $strSQL .= ", SYAIN_NO";
        $strSQL .= ", SYAIN_NM";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", KYUYO_GK";
        $strSQL .= ", SYAHO_GK";
        $strSQL .= ", SYOYO_GK";
        $strSQL .= ", JINKENHI_GK";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ", UPD_SYA_CD";
        $strSQL .= ", UPD_PRG_ID";
        $strSQL .= ", UPD_CLT_NM";

        $strSQL .= ") VALUES (";
        $strSQL .= "  @KEIJO_DT";
        $strSQL .= ", @SYAIN_NO";
        $strSQL .= ", @SYAIN_NM";
        $strSQL .= ", @BUSYO_CD";
        $strSQL .= ", @KYUYO_GK";
        $strSQL .= ", @SYAHO_GK";
        $strSQL .= ", @SYOYO_GK";
        $strSQL .= ", @JINKENHI_GK";
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

        $strSQL = str_replace("@SYAIN_NO", $this->ClsComFnc->FncSqlNv(str_pad(rtrim($value[0]), 5, "0", STR_PAD_LEFT)), $strSQL);
        $strSQL = str_replace("@SYAIN_NM", $this->ClsComFnc->FncSqlNv($value[1]), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv(str_pad(rtrim($value[2]), 3, "0", STR_PAD_LEFT)), $strSQL);
        $strSQL = str_replace("@KYUYO_GK", $this->ClsComFnc->FncSqlNz($value[3]), $strSQL);
        $strSQL = str_replace("@SYAHO_GK", $this->ClsComFnc->FncSqlNz($value[4]), $strSQL);
        $strSQL = str_replace("@SYOYO_GK", $this->ClsComFnc->FncSqlNz($value[5]), $strSQL);
        $strSQL = str_replace("@JINKENHI_GK", $this->ClsComFnc->FncSqlNz($value[6]), $strSQL);
        return $strSQL;

    }

    public function fncUnmachiListSelSql($postData)
    {
        $ym = $postData['KEIJOBI'];
        $y = substr($ym, 0, 4);
        $m = substr($ym, 5, 2);
        $d = date('t', mktime(0, 0, 0, $m, 1, $y));
        $ymd = $y . $m . $d;

        $strSQL = "";
        $strSQL .= "SELECT JIN.SYAIN_NO" . "\r\n";
        $strSQL .= ",      JIN.SYAIN_NM" . "\r\n";
        $strSQL .= ",      JIN.BUSYO_CD" . "\r\n";

        $strSQL .= ",      CASE WHEN HAI.BUSYO_CD IS NULL THEN '配属先ﾏｽﾀに設定されていません' ELSE HAI.BUSYO_CD END BUSYO_CD_SYA" . "\r\n";

        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",      '@NENGETU' NENGETU" . "\r\n";
        $strSQL .= "FROM   HSTAFFJINKEN JIN" . "\r\n";
        $strSQL .= "INNER JOIN HSYAINMST SYA" . "\r\n";

        $strSQL .= "ON     SYA.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";

        $strSQL .= "LEFT  JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <='" . $ymd . "'" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >='" . $ymd . "'" . "\r\n";
        $strSQL .= "WHERE  (HAI.BUSYO_CD <> JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "OR  HAI.BUSYO_CD is NULL)" . "\r\n";
        $strSQL .= "AND    JIN.KEIJO_DT = '@KEIJOBI'" . "\r\n";

        $strSQL = str_replace("@NENGETU", $y . '年' . $m . '月', $strSQL);
        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        return $strSQL;
    }

    public function ExcuteFncGetSqlInsert($value, $postData)
    {

        $strSql = $this->fncGetSqlInsert($value, $postData);

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

    public function fncUnmachiListSel($postData = NULL)
    {
        $strSql = $this->fncUnmachiListSelSql($postData);

        return parent::select($strSql);
    }

}
