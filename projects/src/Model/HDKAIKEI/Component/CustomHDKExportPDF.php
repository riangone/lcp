<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                    担当
 * YYYYMMDD           #ID                                     XXXXXX                                 FCSDL
 * 20240227           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」        YIN
 * 20240228           20240213_機能改善要望対応 NO5    支払伝票印刷時、科目名の表示が無いので追加してほしい  caina
 * 20240325           変更イメージです・明細を１行分追加・科目、補助科目は 上段にコード、下段に名称を表示・税率を追加  caina
 * 20240411           本番保守.xlsx  NO14        社員を追加し、No11で追加した社員番号＆社員名を出力したい           caina
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
// 共通クラスの読込み
namespace App\Model\HDKAIKEI\Component;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;
use Cake\Routing\Router;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComFnc
// * 処理説明：共通関数
//*************************************

class CustomHDKExportPDF extends ClsComDb
{
    public $SessionComponent;
    public function FncGetKamokuMstValueSQL($strCode, $strKomoku, $strMstFormat)
    {
        //** ＳＱＬ作成
        $strSql = "";
        if ($strKomoku == "999999") {
            //科目名で検索(項目は科目でグルーピングした最初の項目)
            $strSql .= "SELECT KAMOK_NM" . "\r\n";
            $strSql .= "FROM  M_KAMOKU A" . "\r\n";
            $strSql .= "WHERE NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";
            $strSql .= "AND   A.KAMOK_CD = '@KAMOKUCD'" . "\r\n";
        } else {
            //科目・項目名で検索
            $strSql .= "SELECT (KAMOK_NM || ' ' || KOMOK_NM) KAMOK_NM" . "\r\n";
            $strSql .= "FROM   M_KAMOKU" . "\r\n";
            $strSql .= "WHERE  KAMOK_CD = '@KAMOKUCD'" . "\r\n";

            if ($strMstFormat == "") {
                $strSql .= "AND  NVL(TRIM(KOMOK_CD),'00') = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
            } else {
                $strSql .= "AND  (CASE WHEN LENGTH(TRIM(KOMOK_CD)) > 2 THEN TRIM(KOMOK_CD) ELSE NVL(LPAD(TRIM(KOMOK_CD),2,'@MSTFORMAT'),'00') END) = NVL(TRIM('@KOMOKU'),'00')" . "\r\n";
            }
        }

        $strSql = str_replace("@KAMOKUCD", $strCode, $strSql);
        $strSql = str_replace("@KOMOKU", $strKomoku, $strSql);
        $strSql = str_replace("@MSTFORMAT", $strMstFormat, $strSql);
        return $strSql;
    }

    public function fncHDKPrintSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO || SWK.EDA_NO SYOHY_NO" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(SWK.KEIRI_DT,'YYYYMMDD'),'YYYY/MM/DD') KEIRI_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') PRINT_DATE" . "\r\n";
        //20240228 caina ins s
        //20240325 caina del s
        // if ($postData == "2") {
        //20240325 caina del e
        $strSQL .= ",      SWK.L_KAMOK_CD AS L_KAMCD" . "\r\n";
        $strSQL .= ",      SWK.L_KOUMK_CD AS L_KOUCD" . "\r\n";
        $strSQL .= ",      LKMK.KAMOK_NAME AS L_KAM_NAME" . "\r\n";
        $strSQL .= ",      LKMK.SUB_KAMOK_NAME AS L_SUBKAM_NAME" . "\r\n";
        //20240325 caina del s
        // } else {
        // 	//20240228 caina ins e
        // 	// $strSQL .= ",      (CASE WHEN SWK.L_KOUMK_CD IS NULL THEN SWK.L_KAMOK_CD ELSE SWK.L_KAMOK_CD || '-' || SWK.L_KOUMK_CD END) L_KAMOKU_CD" . "\r\n";
        // 	// $strSQL .= ",      (CASE WHEN LKMK.SUB_KAMOK_NAME IS NULL THEN LKMK.KAMOK_NAME ELSE LKMK.SUB_KAMOK_NAME END) L_KAMOKU" . "\r\n";
        // 	//20240228 caina ins s
        // }
        // if ($postData == "2") {
        //20240325 caina del e
        $strSQL .= ",      SWK.R_KAMOK_CD AS R_KAMCD" . "\r\n";
        $strSQL .= ",      LPAD(SWK.R_KOUMK_CD,3,'0') AS R_KOUCD" . "\r\n";
        //20240325 caina del s
        // } else {
        // 	//20240228 caina ins e
        // 	$strSQL .= ",      (CASE WHEN SWK.R_KOUMK_CD IS NULL THEN SWK.R_KAMOK_CD ELSE SWK.R_KAMOK_CD || '-' || LPAD(SWK.R_KOUMK_CD,3,'0') END) R_KAMOKU_CD" . "\r\n";
        // }
        //20240325 caina del e
        // 貸方科目名
        if ($postData == "2") {
            //20240228 caina upd s
            $strSQL .= ",	  RKMK.MEISYOU AS R_KAMOKU" . "\r\n";
            $strSQL .= ",	  RKMK.MOJI1 AS R_SUB_KAMOKU" . "\r\n";
            // $strSQL .= ",	  (CASE WHEN RKMK.MOJI1 IS NULL THEN RKMK.MEISYOU ELSE RKMK.MOJI1 END) R_KAMOKU" . "\r\n";
            //20240228 caina ups e
        } else {
            //20240325 caina upd s
            // $strSQL .= ",      (CASE WHEN RKMK.SUB_KAMOK_NAME IS NULL THEN RKMK.KAMOK_NAME ELSE RKMK.SUB_KAMOK_NAME END) R_KAMOKU" . "\r\n";
            $strSQL .= ",	  RKMK.KAMOK_NAME AS R_KAMOKU" . "\r\n";
            $strSQL .= ",	  RKMK.SUB_KAMOK_NAME AS R_SUB_KAMOKU" . "\r\n";
            //20240325 caina upd e
        }
        $strSQL .= ",      SWK.L_HASEI_KYOTN_CD L_BUSYO_CD" . "\r\n";
        $strSQL .= ",      LBS.BUSYO_NM L_BUSYO" . "\r\n";
        $strSQL .= ",      SWK.R_HASEI_KYOTN_CD R_BUSYO_CD" . "\r\n";
        $strSQL .= ",      RBS.BUSYO_NM R_BUSYO" . "\r\n";
        if ($postData == "2") {
            $strSQL .= ",      SWK.TORIHIKISAKI_CD" . "\r\n";
            $strSQL .= ",      SWK.TORIHIKISAKI_NAME" . "\r\n";
            $strSQL .= ",      (CASE WHEN SWK.JIKI = '1' THEN '即日支払'" . "\r\n";
            $strSQL .= "             WHEN SWK.JIKI = '2' THEN '日付指定'" . "\r\n";
            $strSQL .= "             WHEN SWK.JIKI = '3' THEN '1ヵ月後締切後支払' ELSE '' END) SHIHARAI" . "\r\n";
            $strSQL .= ",	  DECODE(SWK.SHIHARAI_DT,NULL,'',SUBSTR(SWK.SHIHARAI_DT,1,4) || '/' || SUBSTR(SWK.SHIHARAI_DT,5,2) || '/' || SUBSTR(SWK.SHIHARAI_DT,7,2)) SHIHARAI_DT" . "\r\n";
            $strSQL .= ",      (CASE WHEN SWK.GINKO_KB = '1' THEN '（GD）銀行'" . "\r\n";
            $strSQL .= "             WHEN SWK.GINKO_KB = '2' THEN 'もみじ銀行'" . "\r\n";
            $strSQL .= "             WHEN SWK.GINKO_KB = '3' THEN '（GD）信用金庫'" . "\r\n";
            $strSQL .= "             ELSE SWK.GINKO_NM END) GINKOMEI" . "\r\n";
            $strSQL .= ",      SWK.SHITEN_NM" . "\r\n";
            $strSQL .= ",      (CASE WHEN SWK.YOKIN_SYUBETU = '1' THEN '普通'" . "\r\n";
            $strSQL .= "             WHEN SWK.YOKIN_SYUBETU = '2' THEN '当座'" . "\r\n";
            $strSQL .= "             WHEN SWK.YOKIN_SYUBETU = '9' THEN 'その他' ELSE '' END) SYUBETU" . "\r\n";
            $strSQL .= ",      SWK.KOUZA_NO" . "\r\n";
            $strSQL .= ",      SWK.KOUZA_KN" . "\r\n";
        }
        $strSQL .= ",      SWK.ZEIKM_GK" . "\r\n";
        // 消費税区分
        $strSQL .= ",      (CASE WHEN LSHZKBN.NICKNAME IS NULL THEN LSHZKBN.TAX_KBN_NAME ELSE LSHZKBN.NICKNAME END) L_SYOHIZEI" . "\r\n";
        $strSQL .= ",      (CASE WHEN RSHZKBN.NICKNAME IS NULL THEN RSHZKBN.TAX_KBN_NAME ELSE RSHZKBN.NICKNAME END) R_SYOHIZEI" . "\r\n";
        // 税率区分
        $strSQL .= ",      LRT.MEISYOU L_SYOHIZEI_RT" . "\r\n";
        $strSQL .= ",      RRT.MEISYOU R_SYOHIZEI_RT" . "\r\n";
        $strSQL .= ",      TEKYO" . "\r\n";
        $strSQL .= ",      TO_CHAR(SWK.CREATE_DATE,'YYYY/MM/DD') CREATE_DATE" . "\r\n";
        $strSQL .= ",      '' BIKOU" . "\r\n";
        //20240411 caina ins s
        $strSQL .= ",      SWK.TATEKAE_SYA_CD || '　' || SYAN.SYAIN_NM AS SYAIN" . "\r\n";
        //20240411 caina ins e
        $strSQL .= ",      (SELECT SUM(GK.ZEIKM_GK) FROM HDPSHIWAKEDATA GK WHERE SWK.SYOHY_NO = GK.SYOHY_NO AND SWK.EDA_NO = GK.EDA_NO) ZEIKM_GK_GOUKEI" . "\r\n";
        $strSQL .= ",      (SELECT SUM(GK.ZEIKM_GK) FROM HDPSHIWAKEDATA GK WHERE SWK.SYOHY_NO = GK.SYOHY_NO AND SWK.EDA_NO = GK.EDA_NO AND GK.KEIRI_DT IS NOT NULL) ZEIKM_GK_WITH_DATE" . "\r\n";
        $strSQL .= ",      (SELECT SUM(GK.ZEIKM_GK) FROM HDPSHIWAKEDATA GK WHERE SWK.SYOHY_NO = GK.SYOHY_NO AND SWK.EDA_NO = GK.EDA_NO AND GK.KEIRI_DT IS NULL) ZEIKM_GK_WITHOUT_DATE" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "       WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "ON     SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_PRG_ID = '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_CLT_NM = '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_KAMOKU LKMK" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.SUB_KAMOK_CD),'999999'),LKMK.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND LKMK.USE_FLG ='1'" . "\r\n";
        $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.SUB_KAMOK_CD),'999999'),LKMK.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN" . "\r\n";
        if ($postData == "2") {
            $strSQL .= "       HMEISYOUMST RKMK" . "\r\n";
            $strSQL .= "ON	TO_NUMBER(SUBSTR(RKMK.MEISYOU_CD,1,1) || RKMK.SUCHI1) = TO_NUMBER(SWK.SHR_KAMOK_KB || SWK.R_KAMOK_CD) AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(RKMK.SUCHI2),'999999'),RKMK.SUCHI2) = NVL(SWK.R_KOUMK_CD,'999999') AND RKMK.MEISYOU_ID = 'DK'" . "\r\n";
        } else {
            $strSQL .= "       HDK_MST_KAMOKU RKMK" . "\r\n";
            $strSQL .= "ON     RKMK.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(RKMK.SUB_KAMOK_CD),'999999'),RKMK.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        }
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_BUMON LBS" . "\r\n";
        $strSQL .= "ON     LBS.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LBS.USE_FLG = '1'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_BUMON RBS" . "\r\n";
        $strSQL .= "ON     RBS.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RBS.USE_FLG = '1'" . "\r\n";
        // 消費税区分
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_SHZKBN LSHZKBN" . "\r\n";
        $strSQL .= "ON     LSHZKBN.TAX_KBN_CD = SWK.L_KAZEI_KB" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_SHZKBN RSHZKBN" . "\r\n";
        $strSQL .= "ON     RSHZKBN.TAX_KBN_CD = SWK.R_KAZEI_KB" . "\r\n";
        // 消費税率
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HMEISYOUMST LRT" . "\r\n";
        $strSQL .= "ON     LRT.MEISYOU_CD = SWK.L_ZEI_RT_KB AND LRT.MEISYOU_ID = 'DS'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HMEISYOUMST RRT" . "\r\n";
        $strSQL .= "ON     RRT.MEISYOU_CD = SWK.R_ZEI_RT_KB AND RRT.MEISYOU_ID = 'DS'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = SWK.CRE_SYA_CD" . "\r\n";
        //20240411 caina ins s
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYAN" . "\r\n";
        $strSQL .= "ON     SYAN.SYAIN_NO = SWK.TATEKAE_SYA_CD" . "\r\n";
        //20240411 caina ins e
        $strSQL .= "WHERE  SWK.DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "ORDER BY SWK.SYOHY_NO" . "\r\n";
        $strSQL .= ",        SWK.EDA_NO" . "\r\n";
        $strSQL .= ",        SWK.GYO_NO" . "\r\n";
        $strSQL = str_replace("@DENPY_KB", $postData, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        return $strSQL;
    }

    public function fncHDKGroupSQL($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT SWK.SYOHY_NO || SWK.EDA_NO SYOHY_NO" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(SWK.KEIRI_DT,'YYYYMMDD'),'YYYY/MM/DD') KEIRI_DT" . "\r\n";
        $strSQL .= "FROM   HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "INNER JOIN" . "\r\n";
        $strSQL .= "       WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "ON     SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_PRG_ID = '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= "AND    SYOHY.CRE_CLT_NM = '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_KAMOKU LKMK" . "\r\n";
        // 20240227 YIN UPD S
        // $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.SUB_KAMOK_CD),'999999'),LKMK.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') AND LKMK.USE_FLG ='1'" . "\r\n";
        $strSQL .= "ON     LKMK.KAMOK_CD = SWK.L_KAMOK_CD AND DECODE(SWK.L_KOUMK_CD,NULL,NVL(TRIM(LKMK.SUB_KAMOK_CD),'999999'),LKMK.SUB_KAMOK_CD) = NVL(SWK.L_KOUMK_CD,'999999') " . "\r\n";
        // 20240227 YIN UPD E
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_KAMOKU RKMK" . "\r\n";
        $strSQL .= "ON     RKMK.KAMOK_CD = SWK.R_KAMOK_CD AND DECODE(SWK.R_KOUMK_CD,NULL,NVL(TRIM(RKMK.SUB_KAMOK_CD),'999999'),RKMK.SUB_KAMOK_CD) = NVL(SWK.R_KOUMK_CD,'999999')" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_BUMON LBS" . "\r\n";
        $strSQL .= "ON     LBS.BUSYO_CD = SWK.L_HASEI_KYOTN_CD AND LBS.USE_FLG = '1'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HDK_MST_BUMON RBS" . "\r\n";
        $strSQL .= "ON     RBS.BUSYO_CD = SWK.R_HASEI_KYOTN_CD AND RBS.USE_FLG = '1'" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = SWK.CRE_SYA_CD" . "\r\n";
        $strSQL .= "WHERE  SWK.DENPY_KB = '@DENPY_KB'" . "\r\n";
        $strSQL .= "GROUP BY SWK.SYOHY_NO || SWK.EDA_NO,KEIRI_DT" . "\r\n";
        $strSQL .= "ORDER BY SWK.SYOHY_NO || SWK.EDA_NO" . "\r\n";
        $strSQL = str_replace("@DENPY_KB", $postData, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);

        return $strSQL;
    }

    public function fncUpdPrintFlgSQL($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA SWK" . "\r\n";
        $strSQL .= "SET    PRINT_OUT_FLG = '1'" . "\r\n";
        if ($this->SessionComponent->read('PatternID') == $postData['CONST_ADMIN_PTN_NO'] || ($this->SessionComponent->read('PatternID') == $postData['CONST_HONBU_PTN_NO'])) {
            $strSQL .= ",      HONBU_SYORIZUMI_FLG = '1'" . "\r\n";
        }
        $strSQL .= ",      UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= ",      UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID = '@UPD_PRG_ID'" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE  EXISTS " . "\r\n";
        $strSQL .= "       (SELECT SYOHY.SYOHY_NO" . "\r\n";
        $strSQL .= "        FROM   WK_SYOHY_NO SYOHY" . "\r\n";
        $strSQL .= "        WHERE  SYOHY.SYOHY_NO = SWK.SYOHY_NO" . "\r\n";
        $strSQL .= "        AND    SYOHY.EDA_NO = SWK.EDA_NO" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_SYA_CD = '@CRE_SYA_CD'" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_PRG_ID = '@CRE_PRG_ID'" . "\r\n";
        $strSQL .= "        AND    SYOHY.CRE_CLT_NM = '@CRE_CLT_NM'" . "\r\n";
        $strSQL .= "        )" . "\r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "DENPYO_SEARCH_PRINT", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $postData['BusyoCD'], $strSQL);

        return $strSQL;
    }


    public function fncHDKPrint($postData)
    {
        $strSql = $this->fncHDKPrintSQL($postData);

        return parent::select($strSql);
    }

    public function fncHDKGroup($postData)
    {
        $strSql = $this->fncHDKGroupSQL($postData);

        return parent::select($strSql);
    }

    public function fncUpdPrintFlg($postData)
    {
        $strSql = $this->fncUpdPrintFlgSQL($postData);

        return parent::update($strSql);
    }


}
?>