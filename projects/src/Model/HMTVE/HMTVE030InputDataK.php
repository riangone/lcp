<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use App\Model\HMTVE\Component\ClsComFncHMTVE;
use Cake\Routing\Router;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE030InputDataK extends ClsComDb
{
    public $ClsComFncHMTVE;
    public $SessionComponent;
    public function setExhibitTermDateSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT  START_DATE " . "\r\n";
        $strSQL .= ",       END_DATE " . "\r\n";
        $strSQL .= "FROM    HDTIVENTDATA " . "\r\n";
        $strSQL .= "WHERE   BASE_FLG = '1' " . "\r\n";

        return parent::select($strSQL);
    }

    public function getCarItemSQL($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.SYASYU_CD " . "\r\n";
        $strSQL .= ",      SYA.SYASYU_NM " . "\r\n";
        $strSQL .= ",      KS.SEIYAKU_DAISU_P " . "\r\n";
        $strSQL .= ",      KS.SEIYAKU_DAISU_D " . "\r\n";
        $strSQL .= ",      KS.SIJYO_DAISU " . "\r\n";
        $strSQL .= ",      KS.RAIJYO_DAISU " . "\r\n";
        $strSQL .= ",    TO_CHAR(KS.CREATE_DATE,'yyyy/mm/dd hh24:mi:ss') AS CREATE_DATE " . "\r\n";
        $strSQL .= "FROM   HDTSYASYU SYA " . "\r\n";
        $strSQL .= "LEFT JOIN HDTKAKUHOUSYASYU KS " . "\r\n";
        $strSQL .= "ON     SYA.SYASYU_CD = KS.SYASYU_CD " . "\r\n";
        $strSQL .= "AND    KS.SYAIN_NO = '@SYAIN' " . "\r\n";
        $strSQL .= "AND    KS.IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "ORDER BY SYA.DISP_NO " . "\r\n";
        $strSQL = str_replace("@SYAIN", $this->GS_LOGINUSER['strUserID'], $strSQL);

        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        return parent::select($strSQL);
    }

    public function btnViewClickSQL($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= "SELECT HAI.SYAIN_NO" . "\r\n";
        $strSQL .= ",      HAI.IVENT_TARGET_FLG" . "\r\n";
        $strSQL .= ",      WK.WORK_STATE" . "\r\n";
        $strSQL .= "FROM   HHAIZOKU HAI" . "\r\n";
        $strSQL .= "LEFT JOIN HDTWORKMANAGE WK" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = WK.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    WK.IVENT_DATE = '@IVENTDT'" . "\r\n";
        $strSQL .= "WHERE  HAI.START_DATE <= '@IVENTDT'" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= '@IVENTDT'" . "\r\n";
        $strSQL .= "AND    HAI.SYAIN_NO = '@SYAIN'" . "\r\n";
        $strSQL .= "AND    ((HAI.IVENT_TARGET_FLG = '0' AND (WK.START_DATE IS NULL OR WK.WORK_STATE = '2'))" . "\r\n";
        $strSQL .= "       OR" . "\r\n";
        $strSQL .= "       (HAI.IVENT_TARGET_FLG = '1' AND WK.WORK_STATE = '2'))" . "\r\n";
        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@SYAIN", $this->GS_LOGINUSER['strUserID'], $strSQL);

        return parent::select($strSQL);
    }

    public function getKakuhouItemSQL($ddlExhibitDay, $num)
    {
        $strSQL = "";
        $strSQL .= "SELECT START_DATE " . "\r\n";
        $strSQL .= ",      BUSYO_CD " . "\r\n";
        $strSQL .= ",      SYAIN_NO " . "\r\n";
        $strSQL .= ",      IVENT_DATE" . "\r\n";
        $strSQL .= ",      DATA_KB " . "\r\n";
        $strSQL .= ",      RAIJYO_KUMI_AB_KOKYAKU " . "\r\n";
        $strSQL .= ",      RAIJYO_KUMI_AB_SINTA " . "\r\n";
        $strSQL .= ",      RAIJYO_KUMI_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= ",      RAIJYO_KUMI_NONAB_SINTA " . "\r\n";
        $strSQL .= ",      RAIJYO_KUMI_NONAB_FREE " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_YOBIKOMI " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_KAKUYAKU " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_KOUKOKU " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_MEDIA " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_CHIRASHI " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_TORIGAKARI " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_SYOKAI " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_WEB " . "\r\n";
        $strSQL .= ",      RAIJYO_BUNSEKI_SONOTA " . "\r\n";
        $strSQL .= ",      JIZEN_JYUNBI_DM " . "\r\n";
        $strSQL .= ",      JIZEN_JYUNBI_DH " . "\r\n";
        $strSQL .= ",      JIZEN_JYUNBI_POSTING " . "\r\n";
        $strSQL .= ",      JIZEN_JYUNBI_TEL " . "\r\n";
        $strSQL .= ",      JIZEN_JYUNBI_KAKUYAKU " . "\r\n";
        $strSQL .= ",      ENQUETE_KAISYU " . "\r\n";
        $strSQL .= ",      ABHOT_KOKYAKU " . "\r\n";
        $strSQL .= ",      ABHOT_SINTA " . "\r\n";
        $strSQL .= ",      ABHOT_ZAN " . "\r\n";
        $strSQL .= ",      SATEI_KOKYAKU " . "\r\n";
        $strSQL .= ",      SATEI_KOKYAKU_TA " . "\r\n";
        $strSQL .= ",      SATEI_SINTA " . "\r\n";
        $strSQL .= ",      SATEI_SINTA_TA " . "\r\n";
        $strSQL .= ",      DEMO_KENSU " . "\r\n";
        $strSQL .= ",      RUNCOST_KENSU " . "\r\n";
        $strSQL .= ",      SKYPLAN_KENSU " . "\r\n";
        $strSQL .= ",      NVL(RUNCOST_SEIYAKU_KENSU,0) RUNCOST_SEIYAKU_KENSU " . "\r\n";
        $strSQL .= ",      NVL(SKYPLAN_KEIYAKU_KENSU,0) SKYPLAN_KEIYAKU_KENSU " . "\r\n";
        $strSQL .= ",      SEIYAKU_AB_KOKYAKU " . "\r\n";
        $strSQL .= ",      SEIYAKU_AB_SINTA " . "\r\n";
        $strSQL .= ",      SEIYAKU_NONAB_KOKYAKU " . "\r\n";
        $strSQL .= ",      SEIYAKU_NONAB_SINTA " . "\r\n";
        $strSQL .= ",      SEIYAKU_NONAB_FREE " . "\r\n";
        $strSQL .= ",      KAKUTEI_FLG " . "\r\n";
        $strSQL .= ",      OUT_FLG " . "\r\n";
        $strSQL .= "FROM   HDTKAKUHOUDATA " . "\r\n";
        $strSQL .= "WHERE  SYAIN_NO = '@SYAIN' " . "\r\n";
        $strSQL .= "AND    IVENT_DATE = '@IVENTDT' " . "\r\n";
        $strSQL .= "AND    DATA_KB = '@NUM' " . "\r\n";
        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@SYAIN", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@NUM", $num, $strSQL);

        return parent::select($strSQL);
    }

    public function checkExhibitTermDate($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= " SELECT KAKUTEI_FLG " . "\r\n";
        $strSQL .= " FROM   HDTKAKUHOUKAKUTEI " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@IVENTDT' " . "\r\n";

        $strSQL = str_replace("@IVENTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        return parent::select($strSQL);
    }

    public function IVENTDATESQL($ddlExhibitDay, $textid)
    {
        $strSQL = "";
        $strSQL .= "SELECT IVENT_DATE " . "\r\n";
        $strSQL .= "FROM   HDTKAKUHOUDATA " . "\r\n";
        $strSQL .= "WHERE  IVENT_DATE = '@STARTDT' " . "\r\n";
        $strSQL .= "AND    SYAIN_NO = '@SYAIN_NO'" . "\r\n";
        $strSQL .= "AND    DATA_KB = '@ID' " . "\r\n";

        $strSQL = str_replace("@ID", $textid, $strSQL);
        $strSQL = str_replace("@STARTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::select($strSQL);
    }

    public function textNumInsSQL($textData, $ddlExhibitDay, $lblExhibitTermFrom, $textid)
    {
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "INSERT INTO HDTKAKUHOUDATA(" . "\r\n";
        $strSQL .= "START_DATE" . "\r\n";
        $strSQL .= ",BUSYO_CD" . "\r\n";
        $strSQL .= ",SYAIN_NO" . "\r\n";
        $strSQL .= ",IVENT_DATE" . "\r\n";
        $strSQL .= ",DATA_KB" . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_AB_KOKYAKU" . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_AB_SINTA" . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_NONAB_KOKYAKU" . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_NONAB_SINTA" . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_NONAB_FREE" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_YOBIKOMI" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_KAKUYAKU" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_KOUKOKU" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_MEDIA" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_CHIRASHI" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_TORIGAKARI" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_SYOKAI" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_WEB" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_SONOTA" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_DM" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_DH" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_POSTING" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_TEL" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_KAKUYAKU" . "\r\n";
        $strSQL .= ",ENQUETE_KAISYU" . "\r\n";
        $strSQL .= ",ABHOT_KOKYAKU" . "\r\n";
        $strSQL .= ",ABHOT_SINTA" . "\r\n";
        $strSQL .= ",ABHOT_ZAN" . "\r\n";
        $strSQL .= ",SATEI_KOKYAKU" . "\r\n";
        $strSQL .= ",SATEI_SINTA" . "\r\n";
        $strSQL .= ",SATEI_KOKYAKU_TA" . "\r\n";
        $strSQL .= ",SATEI_SINTA_TA" . "\r\n";
        $strSQL .= ",DEMO_KENSU" . "\r\n";
        $strSQL .= ",RUNCOST_KENSU" . "\r\n";
        $strSQL .= ",SKYPLAN_KENSU" . "\r\n";
        $strSQL .= ",RUNCOST_SEIYAKU_KENSU" . "\r\n";
        $strSQL .= ",SKYPLAN_KEIYAKU_KENSU" . "\r\n";
        $strSQL .= ",SEIYAKU_AB_KOKYAKU" . "\r\n";
        $strSQL .= ",SEIYAKU_AB_SINTA" . "\r\n";
        $strSQL .= ",SEIYAKU_NONAB_KOKYAKU" . "\r\n";
        $strSQL .= ",SEIYAKU_NONAB_SINTA" . "\r\n";
        $strSQL .= ",SEIYAKU_NONAB_FREE" . "\r\n";
        $strSQL .= ",KAKUTEI_FLG" . "\r\n";
        $strSQL .= ",OUT_FLG" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES(" . "\r\n";
        $strSQL .= " '@START_DATE'" . "\r\n";
        $strSQL .= ",'@BUSYO_CD'" . "\r\n";
        $strSQL .= ",'@SYAIN_NO'" . "\r\n";
        $strSQL .= ",'@IVENT_DATE'" . "\r\n";
        $strSQL .= ",'@DATA_KB'" . "\r\n";
        $strSQL .= ",@RAIJYO_KUMI_AB_KOKYAKU" . "\r\n";
        $strSQL .= ",@RAIJYO_KUMI_AB_SINTA" . "\r\n";
        $strSQL .= ",@RAIJYO_KUMI_NONAB_KOKYAKU" . "\r\n";
        $strSQL .= ",@RAIJYO_KUMI_NONAB_SINTA" . "\r\n";
        $strSQL .= ",@RAIJYO_KUMI_NONAB_FREE" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_YOBIKOMI" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_KAKUYAKU" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_KOUKOKU" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_MEDIA" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_CHIRASHI" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_TORIGAKARI" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_SYOKAI" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_WEB" . "\r\n";
        $strSQL .= ",@RAIJYO_BUNSEKI_SONOTA" . "\r\n";
        $strSQL .= ",@JIZEN_JYUNBI_DM" . "\r\n";
        $strSQL .= ",@JIZEN_JYUNBI_DH" . "\r\n";
        $strSQL .= ",@JIZEN_JYUNBI_POSTING" . "\r\n";
        $strSQL .= ",@JIZEN_JYUNBI_TEL" . "\r\n";
        $strSQL .= ",@JIZEN_JYUNBI_KAKUYAKU" . "\r\n";
        $strSQL .= ",@ENQUETE_KAISYU" . "\r\n";
        $strSQL .= ",@ABHOT_KOKYAKU" . "\r\n";
        $strSQL .= ",@ABHOT_SINTA" . "\r\n";
        $strSQL .= ",@ABHOT_ZAN" . "\r\n";
        $strSQL .= ",@SATEI_KOKYAKU" . "\r\n";
        $strSQL .= ",@SATEI_SINTA" . "\r\n";
        $strSQL .= ",@SATEI_KOKYAKU_TA" . "\r\n";
        $strSQL .= ",@SATEI_SINTA_TA" . "\r\n";
        $strSQL .= ",@DEMO_KENSU" . "\r\n";
        $strSQL .= ",@RUNCOST_KENSU" . "\r\n";
        $strSQL .= ",@SKYPLAN_KENSU" . "\r\n";
        $strSQL .= ",@RUNCOST_SEIYAKU_KENSU" . "\r\n";
        $strSQL .= ",@SKYPLAN_KEIYAKU_KENSU" . "\r\n";
        $strSQL .= ",@SEIYAKU_AB_KOKYAKU" . "\r\n";
        $strSQL .= ",@SEIYAKU_AB_SINTA" . "\r\n";
        $strSQL .= ",@SEIYAKU_NONAB_KOKYAKU" . "\r\n";
        $strSQL .= ",@SEIYAKU_NONAB_SINTA" . "\r\n";
        $strSQL .= ",@SEIYAKU_NONAB_FREE" . "\r\n";
        $strSQL .= ",'1'" . "\r\n";
        $strSQL .= ",'0'" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",'InputData_K'" . "\r\n";
        $strSQL .= ",'@UPD_CLT_NM')" . "\r\n";

        $strSQL = str_replace("@START_DATE", str_replace("/", '', $lblExhibitTermFrom), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@IVENT_DATE", str_replace("/", '', $ddlExhibitDay), $strSQL);
        if ($textid == 0) {

            $strSQL = str_replace("@DATA_KB", '0', $strSQL);
        } else
            if ($textid == 1) {

                $strSQL = str_replace("@DATA_KB", '1', $strSQL);
            }

        $strSQL = str_replace("@RAIJYO_KUMI_AB_KOKYAKU", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_AB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@RAIJYO_KUMI_AB_SINTA", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_AB_SINTA']), $strSQL);
        $strSQL = str_replace("@RAIJYO_KUMI_NONAB_KOKYAKU", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_NONAB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@RAIJYO_KUMI_NONAB_SINTA", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_NONAB_SINTA']), $strSQL);
        if ($textid == 0) {
            $strSQL = str_replace("@RAIJYO_KUMI_NONAB_FREE", '0', $strSQL);
        } else
            if ($textid == 1) {
                $strSQL = str_replace("@RAIJYO_KUMI_NONAB_FREE", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_NONAB_FREE']), $strSQL);
            }

        $strSQL = str_replace("@RAIJYO_BUNSEKI_YOBIKOMI", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_YOBIKOMI']), $strSQL);
        if ($textid == 0) {
            $strSQL = str_replace("@RAIJYO_BUNSEKI_KAKUYAKU", '0', $strSQL);
        } else
            if ($textid == 1) {
                $strSQL = str_replace("@RAIJYO_BUNSEKI_KAKUYAKU", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_KAKUYAKU']), $strSQL);
            }
        $strSQL = str_replace("@RAIJYO_BUNSEKI_KOUKOKU", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_KOUKOKU']), $strSQL);
        $strSQL = str_replace("@RAIJYO_BUNSEKI_MEDIA", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_MEDIA']), $strSQL);
        $strSQL = str_replace("@RAIJYO_BUNSEKI_CHIRASHI", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_CHIRASHI']), $strSQL);
        $strSQL = str_replace("@RAIJYO_BUNSEKI_TORIGAKARI", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_TORIGAKARI']), $strSQL);
        $strSQL = str_replace("@RAIJYO_BUNSEKI_SYOKAI", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_SYOKAI']), $strSQL);
        $strSQL = str_replace("@RAIJYO_BUNSEKI_WEB", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_WEB']), $strSQL);
        $strSQL = str_replace("@RAIJYO_BUNSEKI_SONOTA", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_SONOTA']), $strSQL);
        $strSQL = str_replace("@JIZEN_JYUNBI_DM", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_DM']), $strSQL);
        $strSQL = str_replace("@JIZEN_JYUNBI_DH", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_DH']), $strSQL);
        $strSQL = str_replace("@JIZEN_JYUNBI_POSTING", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_POSTING']), $strSQL);
        $strSQL = str_replace("@JIZEN_JYUNBI_TEL", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_TEL']), $strSQL);
        $strSQL = str_replace("@JIZEN_JYUNBI_KAKUYAKU", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_KAKUYAKU']), $strSQL);
        $strSQL = str_replace("@ENQUETE_KAISYU", $this->ClsComFncHMTVE->FncNz($textData['ENQUETE_KAISYU']), $strSQL);
        $strSQL = str_replace("@ABHOT_KOKYAKU", $this->ClsComFncHMTVE->FncNz($textData['ABHOT_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@ABHOT_SINTA", $this->ClsComFncHMTVE->FncNz($textData['ABHOT_SINTA']), $strSQL);
        $strSQL = str_replace("@ABHOT_ZAN", $this->ClsComFncHMTVE->FncNz($textData['ABHOT_ZAN']), $strSQL);
        $strSQL = str_replace("@SATEI_KOKYAKU_TA", $this->ClsComFncHMTVE->FncNz($textData['SATEI_KOKYAKU_TA']), $strSQL);
        $strSQL = str_replace("@SATEI_SINTA_TA", $this->ClsComFncHMTVE->FncNz($textData['SATEI_SINTA_TA']), $strSQL);
        $strSQL = str_replace("@SATEI_KOKYAKU", $this->ClsComFncHMTVE->FncNz($textData['SATEI_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@SATEI_SINTA", $this->ClsComFncHMTVE->FncNz($textData['SATEI_SINTA']), $strSQL);
        $strSQL = str_replace("@DEMO_KENSU", $this->ClsComFncHMTVE->FncNz($textData['DEMO_KENSU']), $strSQL);
        $strSQL = str_replace("@RUNCOST_KENSU", $this->ClsComFncHMTVE->FncNz($textData['RUNCOST_KENSU']), $strSQL);
        $strSQL = str_replace("@SKYPLAN_KENSU", $this->ClsComFncHMTVE->FncNz($textData['SKYPLAN_KENSU']), $strSQL);
        $strSQL = str_replace("@RUNCOST_SEIYAKU_KENSU", $this->ClsComFncHMTVE->FncNz($textData['RUNCOST_SEIYAKU_KENSU']), $strSQL);
        $strSQL = str_replace("@SKYPLAN_KEIYAKU_KENSU", $this->ClsComFncHMTVE->FncNz($textData['SKYPLAN_KEIYAKU_KENSU']), $strSQL);
        $strSQL = str_replace("@SEIYAKU_AB_KOKYAKU", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_AB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@SEIYAKU_AB_SINTA", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_AB_SINTA']), $strSQL);
        $strSQL = str_replace("@SEIYAKU_NONAB_KOKYAKU", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_NONAB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@SEIYAKU_NONAB_SINTA", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_NONAB_SINTA']), $strSQL);
        if ($textid == 0) {
            $strSQL = str_replace("@SEIYAKU_NONAB_FREE", '0', $strSQL);
        } else
            if ($textid == 1) {
                $strSQL = str_replace("@SEIYAKU_NONAB_FREE", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_NONAB_FREE']), $strSQL);
            }
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

    public function textNumUpdSQL($textData, $ddlExhibitDay, $textid)
    {
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = "";
        $strSQL .= "UPDATE HDTKAKUHOUDATA SET " . "\r\n";
        $strSQL .= "RAIJYO_KUMI_AB_KOKYAKU = @RKAK " . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_AB_SINTA = @RKAS" . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_NONAB_KOKYAKU = @RKNK" . "\r\n";
        $strSQL .= ",RAIJYO_KUMI_NONAB_SINTA = @RKNS" . "\r\n";
        if ($textid == 0) {
            $strSQL .= ",RAIJYO_KUMI_NONAB_FREE = NULL" . "\r\n";
        } else
            if ($textid == 1) {
                $strSQL .= ",RAIJYO_KUMI_NONAB_FREE = @RAIJYO_KUMI_NONAB_FREE" . "\r\n";
            }
        $strSQL .= ",RAIJYO_BUNSEKI_YOBIKOMI = @RBY" . "\r\n";
        if ($textid == 0) {
            $strSQL .= ",RAIJYO_BUNSEKI_KAKUYAKU = NULL" . "\r\n";
        } else
            if ($textid == 1) {
                $strSQL .= ",RAIJYO_BUNSEKI_KAKUYAKU = @RAIJYO_BUNSEKI_KAKUYAKU" . "\r\n";
            }
        $strSQL .= ",RAIJYO_BUNSEKI_KOUKOKU = @RBK" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_MEDIA = @RBM" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_CHIRASHI = @RBC" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_TORIGAKARI = @RBT" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_SYOKAI = @RBSI" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_WEB = @RBW" . "\r\n";
        $strSQL .= ",RAIJYO_BUNSEKI_SONOTA = @RBSA" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_DM = @JJDM" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_DH = @JJDH" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_POSTING = @JJPG" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_TEL = @JJTL" . "\r\n";
        $strSQL .= ",JIZEN_JYUNBI_KAKUYAKU = @JJKU" . "\r\n";
        $strSQL .= ",ENQUETE_KAISYU = @EEKU" . "\r\n";
        $strSQL .= ",ABHOT_KOKYAKU = @ATKU" . "\r\n";
        $strSQL .= ",ABHOT_SINTA = @ATSA" . "\r\n";
        $strSQL .= ",ABHOT_ZAN = @ATZN" . "\r\n";
        $strSQL .= ",SATEI_KOKYAKU = @SIKU" . "\r\n";
        $strSQL .= ",SATEI_SINTA = @SISA" . "\r\n";
        $strSQL .= ",SATEI_KOKYAKU_TA = @SATEI_KOKYAKU_TA" . "\r\n";
        $strSQL .= ",SATEI_SINTA_TA = @SATEI_SINTA_TA" . "\r\n";
        $strSQL .= ",DEMO_KENSU = @DOKU" . "\r\n";
        $strSQL .= ",RUNCOST_KENSU = @RUNCOST_SU" . "\r\n";
        $strSQL .= ",SKYPLAN_KENSU = @SKYPLAN_SU" . "\r\n";
        $strSQL .= ",RUNCOST_SEIYAKU_KENSU = @RUNCOST_SEIYAKU_SU" . "\r\n";
        $strSQL .= ",SKYPLAN_KEIYAKU_KENSU = @SKYPLAN_KEIYAKU_SU" . "\r\n";
        $strSQL .= ",SEIYAKU_AB_KOKYAKU = @SAKU" . "\r\n";
        $strSQL .= ",SEIYAKU_AB_SINTA = @SASA" . "\r\n";
        $strSQL .= ",SEIYAKU_NONAB_KOKYAKU = @SNKU" . "\r\n";
        $strSQL .= ",SEIYAKU_NONAB_SINTA = @SNSA" . "\r\n";
        if ($textid == 0) {
            $strSQL .= ",SEIYAKU_NONAB_FREE = NULL" . "\r\n";
        } else
            if ($textid == 1) {
                $strSQL .= ",SEIYAKU_NONAB_FREE = @SEIYAKU_NONAB_FREE" . "\r\n";
            }
        $strSQL .= ",KAKUTEI_FLG = '1'" . "\r\n";
        $strSQL .= ",OUT_FLG = '0'" . "\r\n";
        $strSQL .= ",UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD = '@USCD'" . "\r\n";
        $strSQL .= ",UPD_PRG_ID = 'InputData_K'" . "\r\n";
        $strSQL .= ",UPD_CLT_NM = '@UTNM' " . "\r\n";
        $strSQL .= "WHERE SYAIN_NO = '@SNNO' " . "\r\n";
        $strSQL .= "AND IVENT_DATE = '@ITDE' " . "\r\n";
        if ($textid == 0) {
            $strSQL .= "AND DATA_KB = '0'" . "\r\n";
        } else
            if ($textid == 1) {
                $strSQL .= "AND DATA_KB = '1'" . "\r\n";
            }

        $strSQL = str_replace("@RKAK", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_AB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@RKAS", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_AB_SINTA']), $strSQL);
        $strSQL = str_replace("@RKNK", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_NONAB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@RKNS", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_NONAB_SINTA']), $strSQL);
        if ($textid == 1) {
            $strSQL = str_replace("@RAIJYO_KUMI_NONAB_FREE", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_KUMI_NONAB_FREE']), $strSQL);
        }
        $strSQL = str_replace("@RBY", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_YOBIKOMI']), $strSQL);
        if ($textid == 1) {
            $strSQL = str_replace("@RAIJYO_BUNSEKI_KAKUYAKU", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_KAKUYAKU']), $strSQL);
        }
        $strSQL = str_replace("@RBK", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_KOUKOKU']), $strSQL);
        $strSQL = str_replace("@RBM", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_MEDIA']), $strSQL);
        $strSQL = str_replace("@RBC", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_CHIRASHI']), $strSQL);
        $strSQL = str_replace("@RBT", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_TORIGAKARI']), $strSQL);
        $strSQL = str_replace("@RBSI", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_SYOKAI']), $strSQL);
        $strSQL = str_replace("@RBW", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_WEB']), $strSQL);
        $strSQL = str_replace("@RBSA", $this->ClsComFncHMTVE->FncNz($textData['RAIJYO_BUNSEKI_SONOTA']), $strSQL);
        $strSQL = str_replace("@JJDM", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_DM']), $strSQL);
        $strSQL = str_replace("@JJDH", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_DH']), $strSQL);
        $strSQL = str_replace("@JJPG", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_POSTING']), $strSQL);
        $strSQL = str_replace("@JJTL", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_TEL']), $strSQL);
        $strSQL = str_replace("@JJKU", $this->ClsComFncHMTVE->FncNz($textData['JIZEN_JYUNBI_KAKUYAKU']), $strSQL);
        $strSQL = str_replace("@EEKU", $this->ClsComFncHMTVE->FncNz($textData['ENQUETE_KAISYU']), $strSQL);
        $strSQL = str_replace("@ATKU", $this->ClsComFncHMTVE->FncNz($textData['ABHOT_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@ATSA", $this->ClsComFncHMTVE->FncNz($textData['ABHOT_SINTA']), $strSQL);
        $strSQL = str_replace("@ATZN", $this->ClsComFncHMTVE->FncNz($textData['ABHOT_ZAN']), $strSQL);
        $strSQL = str_replace("@SIKU", $this->ClsComFncHMTVE->FncNz($textData['SATEI_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@SISA", $this->ClsComFncHMTVE->FncNz($textData['SATEI_SINTA']), $strSQL);
        $strSQL = str_replace("@SATEI_KOKYAKU_TA", $this->ClsComFncHMTVE->FncNz($textData['SATEI_KOKYAKU_TA']), $strSQL);
        $strSQL = str_replace("@SATEI_SINTA_TA", $this->ClsComFncHMTVE->FncNz($textData['SATEI_SINTA_TA']), $strSQL);
        $strSQL = str_replace("@DOKU", $this->ClsComFncHMTVE->FncNz($textData['DEMO_KENSU']), $strSQL);
        $strSQL = str_replace("@RUNCOST_SU", $this->ClsComFncHMTVE->FncNz($textData['RUNCOST_KENSU']), $strSQL);
        $strSQL = str_replace("@SKYPLAN_SU", $this->ClsComFncHMTVE->FncNz($textData['SKYPLAN_KENSU']), $strSQL);
        $strSQL = str_replace("@RUNCOST_SEIYAKU_SU", $this->ClsComFncHMTVE->FncNz($textData['RUNCOST_SEIYAKU_KENSU']), $strSQL);
        $strSQL = str_replace("@SKYPLAN_KEIYAKU_SU", $this->ClsComFncHMTVE->FncNz($textData['SKYPLAN_KEIYAKU_KENSU']), $strSQL);
        $strSQL = str_replace("@SAKU", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_AB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@SASA", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_AB_SINTA']), $strSQL);
        $strSQL = str_replace("@SNKU", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_NONAB_KOKYAKU']), $strSQL);
        $strSQL = str_replace("@SNSA", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_NONAB_SINTA']), $strSQL);
        if ($textid == 1) {
            $strSQL = str_replace("@SEIYAKU_NONAB_FREE", $this->ClsComFncHMTVE->FncNz($textData['SEIYAKU_NONAB_FREE']), $strSQL);
        }
        $strSQL = str_replace("@USCD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@SNNO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@ITDE", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@UTNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::update($strSQL);
    }

    public function textNumDelSQL($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HDTKAKUHOUSYASYU" . "\r\n";
        $strSQL .= "WHERE  IVENT_DATE = '@ITDE' " . "\r\n";
        $strSQL .= "AND    SYAIN_NO = '@SNNO'" . "\r\n";

        $strSQL = str_replace("@ITDE", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@SNNO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::delete($strSQL);
    }

    public function btnDeleteclickSql($ddlExhibitDay)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HDTKAKUHOUDATA " . "\r\n";
        $strSQL .= "WHERE  IVENT_DATE = '@STARTDT'" . "\r\n";
        $strSQL .= "AND    SYAIN_NO = '@SYAIN_NO'" . "\r\n";

        $strSQL = str_replace("@STARTDT", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return parent::delete($strSQL);
    }

    public function insertSql($tableDate, $ddlExhibitDay, $lblExhibitTermFrom)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $this->ClsComFncHMTVE = new ClsComFncHMTVE();
        $strSQL = "";
        $strSQL .= "INSERT INTO HDTKAKUHOUSYASYU" . "\r\n";
        $strSQL .= "(START_DATE" . "\r\n";
        $strSQL .= ",BUSYO_CD" . "\r\n";
        $strSQL .= ",SYAIN_NO" . "\r\n";
        $strSQL .= ",IVENT_DATE" . "\r\n";
        $strSQL .= ",SYASYU_CD" . "\r\n";
        $strSQL .= ",SEIYAKU_DAISU_P" . "\r\n";
        $strSQL .= ",SEIYAKU_DAISU_D" . "\r\n";
        $strSQL .= ",SIJYO_DAISU" . "\r\n";
        $strSQL .= ",RAIJYO_DAISU" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM)" . "\r\n";
        $strSQL .= "VALUES(" . "\r\n";
        $strSQL .= "'@START_DATE'" . "\r\n";
        $strSQL .= ",'@BUSYO_CD'" . "\r\n";
        $strSQL .= ",'@SYAIN_NO'" . "\r\n";
        $strSQL .= ",'@IVENT_DATE'" . "\r\n";
        $strSQL .= ",'@SYASYU_CD'" . "\r\n";
        $strSQL .= ",@SEIYAKU_DAISU_P" . "\r\n";
        $strSQL .= ",@SEIYAKU_DAISU_D" . "\r\n";
        $strSQL .= ",@SIJYO_DAISU" . "\r\n";
        $strSQL .= ",@RAIJYO_DAISU" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",@CREATE_DATE" . "\r\n";
        $strSQL .= ",'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",'InputData_K'" . "\r\n";
        $strSQL .= ",'@UPD_CLT_NM')" . "\r\n";

        $strSQL = str_replace("@START_DATE", str_replace("/", '', $lblExhibitTermFrom), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@IVENT_DATE", str_replace("/", '', $ddlExhibitDay), $strSQL);
        $strSQL = str_replace("@SYASYU_CD", $this->ClsComFncHMTVE->FncNz($tableDate['SYASYU_CD']), $strSQL);
        $strSQL = str_replace("@SEIYAKU_DAISU_P", $this->ClsComFncHMTVE->FncNz($tableDate['SEIYAKU_DAISU_P']), $strSQL);
        $strSQL = str_replace("@SEIYAKU_DAISU_D", $this->ClsComFncHMTVE->FncNz($tableDate['SEIYAKU_DAISU_D']), $strSQL);
        $strSQL = str_replace("@SIJYO_DAISU", $this->ClsComFncHMTVE->FncNz($tableDate['SIJYO_DAISU']), $strSQL);
        $strSQL = str_replace("@RAIJYO_DAISU", $this->ClsComFncHMTVE->FncNz($tableDate['RAIJYO_DAISU']), $strSQL);
        if ($tableDate['CREATE_DATE'] !== null && trim($tableDate['CREATE_DATE']) !== "") {
            $strSQL = str_replace("@CREATE_DATE", " TO_DATE('" . $tableDate['CREATE_DATE'] . "', 'yyyy/mm/dd hh24:mi:ss')", $strSQL);
        } else {
            $strSQL = str_replace("@CREATE_DATE", "SYSDATE", $strSQL);
        }
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

}
