<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmRankingInput extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";

    function fncControlNenChkSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT ID,KI,SYR_YMD" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'" . "\r\n";

        return $strSQL;

    }

    public function fncControlNenChk()
    {
        $strSql = $this->fncControlNenChkSql();
        return parent::select($strSql);
    }

    function fncRankingDataSelSql($strNengetu = "")
    {
        $strSQL = "";

        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= " (SUBSTR(NENGETU,1,4) || '/' || SUBSTR(NENGETU,5,2)) NENGETU" . "\r\n";
        $strSQL .= ",ZENSYA_JININ" . "\r\n";
        $strSQL .= ",HONSYA_JININ" . "\r\n";
        $strSQL .= ",ATHER_JININ" . "\r\n";
        $strSQL .= ",SINSYA_DAISU" . "\r\n";
        $strSQL .= ",CHUKO_DAISU" . "\r\n";
        $strSQL .= ",SEIBI_JININ" . "\r\n";
        $strSQL .= ",TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS') CRE_DT" . "\r\n";
        $strSQL .= "FROM HRANKINGINPUTDATA" . "\r\n";

        if ($strNengetu != "") {
            $strSQL .= "WHERE NENGETU = '@NENGETU'" . "\r\n";
        }
        $strSQL .= "ORDER BY NENGETU DESC" . "\r\n";

        $strSQL = str_replace("@NENGETU", $strNengetu, $strSQL);

        return $strSQL;

    }

    function fncDeleteSql($postData = "")
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HRANKINGINPUTDATA " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "NENGETU='@NENGETU'" . "\r\n";

        $strSQL = str_replace("@NENGETU", str_replace("/", "", $postData['NENGETU']), $strSQL);

        return $strSQL;
    }

    public function fncRankingDataInsSql($postData = "")
    {
        $strSQL = "";

        $strSQL .= "INSERT INTO HRANKINGINPUTDATA" . "\r\n";
        $strSQL .= "(           NENGETU" . "\r\n";
        $strSQL .= ",           HONSYA_JININ" . "\r\n";
        $strSQL .= ",           ZENSYA_JININ" . "\r\n";
        $strSQL .= ",           ATHER_JININ" . "\r\n";
        $strSQL .= ",           SINSYA_DAISU" . "\r\n";
        $strSQL .= ",           CHUKO_DAISU" . "\r\n";
        $strSQL .= ",           SEIBI_JININ" . "\r\n";
        $strSQL .= ",           UPD_DATE" . "\r\n";
        $strSQL .= ",           CREATE_DATE" . "\r\n";
        $strSQL .= ",           UPD_SYA_CD" . "\r\n";
        $strSQL .= ",           UPD_PRG_ID" . "\r\n";
        $strSQL .= ",           UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES" . "\r\n";
        $strSQL .= "(           '@NENGETU'" . "\r\n";
        $strSQL .= ",           '@HONSYA_JININ'" . "\r\n";
        $strSQL .= ",           '@ZENSYA_JININ'" . "\r\n";
        $strSQL .= ",           '@ATHER_JININ'" . "\r\n";
        $strSQL .= ",           '@SINSYA_DAISU'" . "\r\n";
        $strSQL .= ",           '@CHUKO_DAISU'" . "\r\n";
        $strSQL .= ",           '@SEIBI_JININ'" . "\r\n";
        $strSQL .= ",           SYSDATE" . "\r\n";
        $strSQL .= ",           @CREATE_DATE" . "\r\n";
        $strSQL .= ",           '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",           '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",           '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@NENGETU", str_replace("/", "", $postData['NENGETU']), $strSQL);
        $strSQL = str_replace("@HONSYA_JININ", rtrim($postData['HONSYA_JININ']), $strSQL);
        $strSQL = str_replace("@ZENSYA_JININ", rtrim($postData['ZENSYA_JININ']), $strSQL);
        $strSQL = str_replace("@ATHER_JININ", rtrim($postData['ATHER_JININ']), $strSQL);
        $strSQL = str_replace("@SINSYA_DAISU", rtrim($postData['SINSYA_DAISU']), $strSQL);
        $strSQL = str_replace("@CHUKO_DAISU", rtrim($postData['CHUKO_DAISU']), $strSQL);
        $strSQL = str_replace("@SEIBI_JININ", rtrim($postData['SEIBI_JININ']), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "RankingInput", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        if (rtrim($postData['CREATE_DATE']) == "") {
            $strSQL = str_replace("@CREATE_DATE", "SYSDATE", $strSQL);

        } else {
            $strSQL = str_replace("@CREATE_DATE", "TO_DATE('" . rtrim($postData['CREATE_DATE']) . "','YYYY/MM/DD HH24:MI:SS')" . "\r\n", $strSQL);

        }
        return $strSQL;
    }

    public function fncRankingDataSel($postData = "")
    {
        $strSql = $this->fncRankingDataSelSql($postData);
        return parent::select($strSql);
    }

    public function fncDelete($postData = "")
    {
        $strSql = $this->fncDeleteSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncRankingDataIns($postData = "")
    {
        $strSql = $this->fncRankingDataInsSql($postData);
        return parent::Do_Execute($strSql);
    }

}