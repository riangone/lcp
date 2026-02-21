<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE350HBUSYOEntry extends ClsComDb
{
    function fncFormloadSql($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT BUSYO_CD , BUSYO_NM , BUSYO_KANANM " . "\r\n";
        $strSQL .= " ,      BUSYO_RYKNM " . "\r\n";
        $strSQL .= " ,      KKR_BUSYO_CD " . "\r\n";
        $strSQL .= " ,      CNV_BUSYO_CD " . "\r\n";
        $strSQL .= " ,      TENPO_CD " . "\r\n";
        $strSQL .= " ,      SYUKEI_KB " . "\r\n";
        $strSQL .= " ,      MANEGER_CD " . "\r\n";
        $strSQL .= " ,      START_DATE " . "\r\n";
        $strSQL .= " ,      END_DATE " . "\r\n";
        $strSQL .= " ,      DSP_SEQNO " . "\r\n";
        $strSQL .= " ,      BUSYO_KB " . "\r\n";
        $strSQL .= " ,      TORIKOMI_BUSYO_KB " . "\r\n";
        $strSQL .= " ,      PRN_KB1 " . "\r\n";
        $strSQL .= " ,      PRN_KB2 " . "\r\n";
        $strSQL .= " ,      PRN_KB3 " . "\r\n";
        $strSQL .= " ,      PRN_KB4 " . "\r\n";
        $strSQL .= " ,      PRN_KB5 " . "\r\n";
        $strSQL .= " ,      PRN_KB6 " . "\r\n";
        $strSQL .= " ,      HKNSYT_DSP_KB " . "\r\n";
        $strSQL .= " ,      HDT_TENPO_CD " . "\r\n";
        $strSQL .= " ,      IVENT_TENPO_DISP_NO " . "\r\n";
        $strSQL .= " ,      HDT_TENPO_DISP_NO " . "\r\n";
        $strSQL .= " ,      STD_TENPO_DISP_NO " . "\r\n";
        $strSQL .= " ,      CREATE_DATE " . "\r\n";
        $strSQL .= " FROM   HBUSYO  " . "\r\n";
        $strSQL .= " WHERE  BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $postData['PartmentID'], $strSQL);

        return $strSQL;
    }

    public function fncFormload($postData)
    {
        $strSql = $this->fncFormloadSql($postData);

        return parent::select($strSql);
    }

    function HbusyoEntryDataChk($postData)
    {
        $strSQL = "";
        $strSQL .= " SELECT BUSYO_CD " . "\r\n";
        $strSQL .= " FROM HBUSYO" . "\r\n";
        $strSQL .= "  WHERE BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@BUSYOCD", $postData['txtID'], $strSQL);

        return parent::select($strSQL);
    }

    function HbusyoEntryDataUpd($postData)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HBUSYO " . "\r\n";
        $strSQL .= " SET HDT_TENPO_CD = '@HDT_TENPO_CD' " . "\r\n";
        $strSQL .= " , IVENT_TENPO_DISP_NO = '@IVENT_TENPO_DISP_NO'" . "\r\n";
        $strSQL .= " , HDT_TENPO_DISP_NO = '@HDT_TENPO_DISP_NO'" . "\r\n";
        $strSQL .= " , STD_TENPO_DISP_NO = '@STD_TENPO_DISP_NO'" . "\r\n";
        $strSQL .= " , UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= " , UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " , UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= " , UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "  WHERE BUSYO_CD = '@BUSYOCD' " . "\r\n";

        $strSQL = str_replace("@HDT_TENPO_CD", $postData['txtSetShopID'], $strSQL);
        $strSQL = str_replace("@IVENT_TENPO_DISP_NO", $postData['txtSetShowIndex'], $strSQL);
        $strSQL = str_replace("@HDT_TENPO_DISP_NO", $postData['txtTandFShowIndex'], $strSQL);
        $strSQL = str_replace("@STD_TENPO_DISP_NO", $postData['txtShopIndex'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", 'HBUSYOEntry', $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@BUSYOCD", $postData['txtID'], $strSQL);

        return parent::update($strSQL);
    }
}
