<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                    担当
 * YYYYMMDD           #ID                                     XXXXXX                                 FCSDL
 * 20240416      /04.コード/ASP.NET/HMDPS_20210902         20240327：VBコード変更の対応                 LJX
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HMDPS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMDPS104BarCodeReadOut extends ClsComDb
{
    //読み取りデータのチェックと読取書類ラベルへのセット
    //Mode=0[ラベルへのセットのみ], Mode=1[チェック＆メッセージ付], Mode=2[CSV出力時の再チェック]
    public function FncChkAndSetShiwakeInfoSql($strSyohyoNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT   A.EDA_NO," . "\r\n";
        $strSQL .= "         DECODE(A.DENPY_KB, '1', '仕訳伝票', '2', '支払伝票', NULL) AS 読取書類," . "\r\n";
        $strSQL .= "         CASE" . "\r\n";
        $strSQL .= "             WHEN A.DENPY_KB = '2' AND" . "\r\n";
        $strSQL .= "                  A.R_KAMOK_CD = '21152' AND" . "\r\n";
        $strSQL .= "                  A.R_KOUMK_CD = '9' AND" . "\r\n";
        //「店舗支払」：99999
        $strSQL .= "                  A.SHIHARAISAKI_CD <> '99999' THEN '1'" . "\r\n";
        $strSQL .= "             ELSE '0'" . "\r\n";
        $strSQL .= "         END AS 特別ＣＳＶフラグ," . "\r\n";
        $strSQL .= "         B.CSV_OUT_FLG AS ＣＳＶ出力フラグ," . "\r\n";
        $strSQL .= "         A.DEL_FLG AS 削除フラグ," . "\r\n";
        $strSQL .= "         NVL(A.PRINT_OUT_FLG, '0') AS 印刷フラグ" . "\r\n";
        $strSQL .= "FROM     HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "         INNER JOIN (SELECT   BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BA.EDA_NO," . "\r\n";
        $strSQL .= "                              BB.CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                              MIN(BA.GYO_NO) AS GYO_NO" . "\r\n";
        $strSQL .= "                     FROM     HDPSHIWAKEDATA BA" . "\r\n";
        $strSQL .= "                              INNER JOIN (SELECT   SYOHY_NO," . "\r\n";
        $strSQL .= "                                                   MAX(CSV_OUT_FLG) AS CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                                                   MAX(EDA_NO) AS EDA_NO" . "\r\n";
        $strSQL .= "                                          FROM     HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "                                          WHERE    SYOHY_NO = '@strSyohyoNo'" . "\r\n";
        $strSQL .= "                                          GROUP BY SYOHY_NO) BB" . "\r\n";
        $strSQL .= "                                  ON BA.SYOHY_NO = BB.SYOHY_NO" . "\r\n";
        $strSQL .= "                                 AND BA.EDA_NO = BB.EDA_NO" . "\r\n";
        $strSQL .= "                     GROUP BY BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BB.CSV_OUT_FLG," . "\r\n";
        $strSQL .= "                              BA.EDA_NO) B" . "\r\n";
        $strSQL .= "             ON A.SYOHY_NO = B.SYOHY_NO" . "\r\n";
        $strSQL .= "            AND A.EDA_NO = B.EDA_NO" . "\r\n";
        $strSQL .= "            AND A.GYO_NO = B.GYO_NO" . "\r\n";
        $strSQL .= "WHERE    A.SYOHY_NO = '@strSyohyoNo'" . "\r\n";

        $strSQL = str_replace("@strSyohyoNo", substr($strSyohyoNo, 0, 15), $strSQL);
        return parent::select($strSQL);
    }

    //Gridへのデータセット
    public function FncSetDataSql($strSyohyoNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT   '1' AS CHK_CSV_STATUS," . "\r\n";
        $strSQL .= "         DECODE(A.DENPY_KB, '1', '仕訳伝票', '2', '支払伝票', NULL) AS SYOHYO_KBN," . "\r\n";
        $strSQL .= "         A.SYOHY_NO || A.EDA_NO AS SYOHYO_NO_VIEW," . "\r\n";
        $strSQL .= "         A.SYOHY_NO AS SYOHYO_NO," . "\r\n";
        $strSQL .= "         A.EDA_NO," . "\r\n";
        $strSQL .= "       (CASE WHEN A.L_KOUMK_CD IS NULL THEN ML.KAMOK_SSK_NM ELSE ML.KMK_KUM_NM END) KARIKATA," . "\r\n";
        $strSQL .= "       (CASE WHEN A.R_KOUMK_CD IS NULL THEN MR.KAMOK_SSK_NM ELSE MR.KMK_KUM_NM END) KASHIKATA," . "\r\n";
        $strSQL .= "        NVL(B.SUM_ZEIKM_GK,0) AS KINGAKU," . "\r\n";
        $strSQL .= "         B.M_FUKANZEN_FLG AS CHK_HUKANZEN_STATUS," . "\r\n";
        $strSQL .= "         A.UPD_DATE" . "\r\n";
        $strSQL .= "FROM     HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "         INNER JOIN (SELECT   BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BA.EDA_NO," . "\r\n";
        $strSQL .= "                              MAX(BA.FUKANZEN_FLG) AS M_FUKANZEN_FLG," . "\r\n";
        $strSQL .= "                              SUM(BA.ZEIKM_GK) AS SUM_ZEIKM_GK," . "\r\n";
        $strSQL .= "                              MIN(BA.GYO_NO) AS GYO_NO" . "\r\n";
        $strSQL .= "                     FROM     HDPSHIWAKEDATA BA" . "\r\n";
        $strSQL .= "                              INNER JOIN (SELECT   SYOHY_NO," . "\r\n";
        $strSQL .= "                                                   MAX(EDA_NO) AS EDA_NO" . "\r\n";
        $strSQL .= "                                          FROM     HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "                                          GROUP BY SYOHY_NO) BB" . "\r\n";
        $strSQL .= "                                  ON BA.SYOHY_NO = BB.SYOHY_NO" . "\r\n";
        $strSQL .= "                                 AND BA.EDA_NO = BB.EDA_NO" . "\r\n";
        $strSQL .= "                     GROUP BY BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BA.EDA_NO) B" . "\r\n";
        $strSQL .= "             ON A.SYOHY_NO = B.SYOHY_NO" . "\r\n";
        $strSQL .= "            AND A.EDA_NO = B.EDA_NO" . "\r\n";
        $strSQL .= "            AND A.GYO_NO = B.GYO_NO" . "\r\n";
        $strSQL .= "         INNER JOIN M29FZ6 ML" . "\r\n";
        $strSQL .= "             ON A.L_KAMOK_CD = ML.KAMOK_CD" . "\r\n";
        $strSQL .= "            AND NVL(A.L_KOUMK_CD,'999999') = DECODE(A.L_KOUMK_CD,NULL,NVL(TRIM(ML.KOUMK_CD),'999999'),ML.KOUMK_CD)" . "\r\n";
        $strSQL .= "         INNER JOIN M29FZ6 MR" . "\r\n";
        $strSQL .= "             ON A.R_KAMOK_CD = MR.KAMOK_CD" . "\r\n";
        $strSQL .= "            AND NVL(A.R_KOUMK_CD,'999999') = DECODE(A.R_KOUMK_CD,NULL,NVL(TRIM(MR.KOUMK_CD),'999999'),MR.KOUMK_CD)" . "\r\n";
        $strSQL .= "WHERE    A.SYOHY_NO = '@strSyohyoNo'" . "\r\n";
        $strSQL .= "GROUP BY A.SYOHY_NO," . "\r\n";
        $strSQL .= "         A.EDA_NO," . "\r\n";
        $strSQL .= "         A.DENPY_KB," . "\r\n";
        $strSQL .= "         (CASE WHEN A.L_KOUMK_CD IS NULL THEN ML.KAMOK_SSK_NM ELSE ML.KMK_KUM_NM END)," . "\r\n";
        $strSQL .= "         (CASE WHEN A.R_KOUMK_CD IS NULL THEN MR.KAMOK_SSK_NM ELSE MR.KMK_KUM_NM END)," . "\r\n";
        $strSQL .= "         B.M_FUKANZEN_FLG," . "\r\n";
        $strSQL .= "         B.SUM_ZEIKM_GK,0," . "\r\n";
        $strSQL .= "         A.UPD_DATE" . "\r\n";

        $strSQL = str_replace("@strSyohyoNo", substr($strSyohyoNo, 0, 15), $strSQL);
        return parent::select($strSQL);
    }

    //出力グループ名の重複チェック
    public function FncChkExistGroupNMSql($lvTxtGroupName)
    {
        $strSQL = "";
        $strSQL .= "SELECT   COUNT(*)" . "\r\n";
        $strSQL .= "FROM     HDPOUTGROUPDATA A" . "\r\n";
        $strSQL .= "WHERE    A.CSV_GROUP_NM ='@lvTxtGroupName'" . "\r\n";

        $strSQL = str_replace("@lvTxtGroupName", $lvTxtGroupName, $strSQL);
        return parent::select($strSQL);
    }

    //グループ№の最新取得
    public function FncGetGroupNoSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT   NVL(MAX(A.CSV_GROUP_NO), 0) + 1" . "\r\n";
        $strSQL .= "FROM     HDPOUTGROUPDATA A" . "\r\n";

        return parent::select($strSQL);
    }

    //出力グループの登録
    public function SubInsertGroupDataSql($params)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDPOUTGROUPDATA (" . "\r\n";
        $strSQL .= "CSV_GROUP_NO," . "\r\n";
        $strSQL .= "CSV_GROUP_NM," . "\r\n";
        $strSQL .= "CSV_OUT_DT," . "\r\n";
        $strSQL .= "KEIRI_DT," . "\r\n";
        $strSQL .= "CREATE_DATE," . "\r\n";
        $strSQL .= "CRE_SYA_CD," . "\r\n";
        $strSQL .= "CRE_PRG_ID," . "\r\n";
        $strSQL .= "CRE_CLT_NM," . "\r\n";
        $strSQL .= "UPD_DATE," . "\r\n";
        $strSQL .= "UPD_SYA_CD," . "\r\n";
        $strSQL .= "UPD_PRG_ID," . "\r\n";
        $strSQL .= "UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES (" . "\r\n";
        $strSQL .= "'@groupNo'," . "\r\n";
        $strSQL .= "'@lvTxtGroupName'," . "\r\n";
        $strSQL .= "TO_DATE('@sysDate', 'yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "'@lvTxtKeiriSyoribi'," . "\r\n";
        $strSQL .= "TO_DATE('@sysDate', 'yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "'@LoginID'," . "\r\n";
        $strSQL .= "'BarCodeReadOut'," . "\r\n";
        $strSQL .= "'@MachineNM'," . "\r\n";
        $strSQL .= "TO_DATE('@sysDate', 'yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "'@LoginID'," . "\r\n";
        $strSQL .= "'BarCodeReadOut'," . "\r\n";
        $strSQL .= "'@MachineNM'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@groupNo", $params['groupNo'], $strSQL);
        $strSQL = str_replace("@lvTxtGroupName", $params['lvTxtGroupName'], $strSQL);
        $strSQL = str_replace("@sysDate", $params['sysDate'], $strSQL);
        $strSQL = str_replace("@lvTxtKeiriSyoribi", $params['lvTxtKeiriSyoribi'], $strSQL);
        $strSQL = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

    //証憑データの更新
    public function SubUpdateSyohyoDataSql($datas, $params)
    {
        $strSQL = "";
        $strSQL .= "UPDATE HDPSHIWAKEDATA SET" . "\r\n";
        $strSQL .= "KEIRI_DT = '@lvTxtKeiriSyoribi'," . "\r\n";
        $strSQL .= "CSV_OUT_FLG = '1'," . "\r\n";
        //パターンＩＤが管理者又は本部かで分ける
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO']) {
            $strSQL .= "HONBU_SYORIZUMI_FLG = '1'," . "\r\n";
        }
        //FLG追加のため
        $strSQL .= "CSV_GROUP_NO = '@groupNo'," . "\r\n";
        $strSQL .= "CSV_OUT_ORDER = '@intCsvOutOrd'," . "\r\n";
        $strSQL .= "UPD_DATE = TO_DATE('@sysDate', 'yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "UPD_BUSYO_CD = '@UPD_BUSYO_CD'," . "\r\n";
        $strSQL .= "UPD_SYA_CD = '@LoginID'," . "\r\n";
        $strSQL .= "UPD_PRG_ID = 'BarCodeReadOut'," . "\r\n";
        $strSQL .= "UPD_CLT_NM = '@MachineNM'" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@strSyohyoNo'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@strEdaNo'" . "\r\n";

        $strSQL = str_replace("@lvTxtKeiriSyoribi", $params['lvTxtKeiriSyoribi'], $strSQL);
        $strSQL = str_replace("@groupNo", $params['groupNo'], $strSQL);
        $strSQL = str_replace("@strSyohyoNo", $datas['strSyohyoNo'], $strSQL);
        $strSQL = str_replace("@strEdaNo", $datas['strEdaNo'], $strSQL);
        $strSQL = str_replace("@intCsvOutOrd", $params['intCsvOutOrd'], $strSQL);
        $strSQL = str_replace("@sysDate", $params['sysDate'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $params['BusyoCD'], $strSQL);
        $strSQL = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    //CSV出力処理(実行)
    public function CSVDownloadSql($CSVType, $GroupNo)
    {
        $strSQL = "";
        if ($CSVType == "0") {
            //外部仕訳データ
            $strSQL .= "SELECT   '3634' || '	' || " . "\r\n";
            $strSQL .= "         'B' || '	' || " . "\r\n";
            $strSQL .= "         CSV_GROUP_NO || '	' || " . "\r\n";
            $strSQL .= "         ROWNUM || '	' || " . "\r\n";
            $strSQL .= "         KEIRI_DT || '	' || " . "\r\n";
            $strSQL .= "         '1' || '	' || " . "\r\n";
            $strSQL .= "         SYOHYO_NO || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         ZEINK_GK || '	' || " . "\r\n";
            $strSQL .= "         ZEIKM_GK || '	' || " . "\r\n";
            $strSQL .= "         SHZEI_GK || '	' || " . "\r\n";
            //20240416 LJX UPD S
            //$strSQL .= "         SUBSTRB(DECODE(SHIHARAISAKI_NM,NULL,TEKYO,SHIHARAISAKI_NM || '　' || TEKYO),1,100) || '	' || " . "\r\n";
            $strSQL .= "         TEKYO || '	' || " . "\r\n";
            //20240416 LJX UPD E
            $strSQL .= "         L_KAMOK_CD || '	' || " . "\r\n";
            $strSQL .= "         L_KOUMK_CD || '	' || " . "\r\n";
            $strSQL .= "         L_KAZEI_KB || '	' || " . "\r\n";
            $strSQL .= "         L_TORHK_KB || '	' || " . "\r\n";
            $strSQL .= "         L_ZEI_RT_KB || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY1 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY2 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY3 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY4 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY5 || '	' || " . "\r\n";
            $strSQL .= "         L_HASEI_KYOTN_CD || '	' || " . "\r\n";
            $strSQL .= "         KEIRI_DT || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO1 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO2 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO3 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO4 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO5 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO6 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO7 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO8 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO9 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO10 || '	' || " . "\r\n";
            $strSQL .= "         R_KAMOK_CD || '	' || " . "\r\n";
            $strSQL .= "         R_KOUMK_CD || '	' || " . "\r\n";
            $strSQL .= "         R_KAZEI_KB || '	' || " . "\r\n";
            $strSQL .= "         R_TORHK_KB || '	' || " . "\r\n";
            $strSQL .= "         R_ZEI_RT_KB || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY1 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY2 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY3 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY4 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY5 || '	' || " . "\r\n";
            $strSQL .= "         R_HASEI_KYOTN_CD || '	' || " . "\r\n";
            $strSQL .= "         KEIRI_DT || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO1 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO2 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO3 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO4 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO5 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO6 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO7 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO8 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO9 || '	' || " . "\r\n";
            //20240416 LJX UPD S
            //$strSQL .= "         R_HISSU_TEKYO10 || '		end' AS STRCSV" . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO10 || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         AITESAKI_KB || '	' || " . "\r\n";
            $strSQL .= "         OKYAKU_TORIHIKI_NO || '	' || " . "\r\n";
            $strSQL .= "         JIGYOSYA_NM || '	' || " . "\r\n";
            $strSQL .= "         INVOICE_ENTRYNO || '	' || " . "\r\n";
            $strSQL .= "         TOKUREI_KB || '		end' AS STRCSV" . "\r\n";
            //20240416 LJX UPD E
            $strSQL .= "FROM     (" . "\r\n";
            $strSQL .= "SELECT   A.CSV_GROUP_NO," . "\r\n";
            $strSQL .= "         A.SYOHY_NO || A.EDA_NO AS SYOHYO_NO," . "\r\n";
            $strSQL .= "         A.KEIRI_DT," . "\r\n";
            $strSQL .= "         A.ZEINK_GK," . "\r\n";
            $strSQL .= "         A.ZEIKM_GK," . "\r\n";
            $strSQL .= "         A.SHZEI_GK," . "\r\n";
            $strSQL .= "         REPLACE(REPLACE(REPLACE(A.TEKYO,CHR(13)||CHR(10),'　'),CHR(13),'　'),CHR(10),'　') TEKYO," . "\r\n";
            $strSQL .= "         A.L_KAMOK_CD," . "\r\n";
            $strSQL .= "         A.L_KOUMK_CD," . "\r\n";
            $strSQL .= "         A.L_HASEI_KYOTN_CD," . "\r\n";
            $strSQL .= "         A.L_KAZEI_KB," . "\r\n";
            $strSQL .= "         A.L_ZEI_RT_KB," . "\r\n";
            $strSQL .= "         A.L_TORHK_KB," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY1," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY2," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY3," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY4," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY5," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO1," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO2," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO3," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO4," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO5," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO6," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO7," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO8," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO9," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO10," . "\r\n";
            $strSQL .= "         A.R_KAMOK_CD," . "\r\n";
            $strSQL .= "         A.R_KOUMK_CD," . "\r\n";
            $strSQL .= "         A.R_HASEI_KYOTN_CD," . "\r\n";
            $strSQL .= "         A.R_KAZEI_KB," . "\r\n";
            $strSQL .= "         A.R_ZEI_RT_KB," . "\r\n";
            $strSQL .= "         A.R_TORHK_KB," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY1," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY2," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY3," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY4," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY5," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO1," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO2," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO3," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO4," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO5," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO6," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO7," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO8," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO9," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO10," . "\r\n";
            $strSQL .= "         A.SHIHARAISAKI_NM" . "\r\n";
            //20240416 LJX INS S
            $strSQL .= "         ,A.AITESAKI_KB" . "\r\n";
            $strSQL .= "         ,A.OKYAKU_TORIHIKI_NO" . "\r\n";
            $strSQL .= "         ,A.JIGYOSYA_NM" . "\r\n";
            $strSQL .= "         ,A.INVOICE_ENTRYNO" . "\r\n";
            $strSQL .= "         ,A.TOKUREI_KB " . "\r\n";
            //20240416 LJX INS E
            $strSQL .= "FROM     HDPSHIWAKEDATA A" . "\r\n";
            $strSQL .= "WHERE    A.CSV_GROUP_NO = @GroupNo" . "\r\n";
            $strSQL .= "ORDER BY A.CSV_OUT_ORDER" . "\r\n";
            $strSQL .= ",        A.SYOHY_NO" . "\r\n";
            $strSQL .= ",         A.EDA_NO" . "\r\n";
            $strSQL .= ",         A.GYO_NO" . "\r\n";
            $strSQL .= ")" . "\r\n";
        } else {
            //外部支払データ
            $strSQL .= "SELECT   '3634' || '	' || " . "\r\n";
            $strSQL .= "         'B' || '	' || " . "\r\n";
            $strSQL .= "         CSV_GROUP_NO || '	' || " . "\r\n";
            $strSQL .= "         ROWNUM || '	' || " . "\r\n";
            $strSQL .= "         SHIHARAISAKI_CD || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         '' || '	' || " . "\r\n";
            $strSQL .= "         SYOHYO_NO || '	' || " . "\r\n";
            $strSQL .= "         TORIHIKI_DT || '	' || " . "\r\n";
            $strSQL .= "         SHIHARAI_DT || '	' || " . "\r\n";
            $strSQL .= "         KEIRI_DT || '	' || " . "\r\n";
            $strSQL .= "         L_KAMOK_CD || '	' || " . "\r\n";
            $strSQL .= "         L_KOUMK_CD || '	' || " . "\r\n";
            $strSQL .= "         L_KAZEI_KB || '	' || " . "\r\n";
            $strSQL .= "         L_TORHK_KB || '	' || " . "\r\n";
            $strSQL .= "         L_ZEI_RT_KB || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY1 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY2 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY3 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY4 || '	' || " . "\r\n";
            $strSQL .= "         L_KOUZA_KEY5 || '	' || " . "\r\n";
            $strSQL .= "         L_HASEI_KYOTN_CD || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO1 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO2 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO3 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO4 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO5 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO6 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO7 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO8 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO9 || '	' || " . "\r\n";
            $strSQL .= "         L_HISSU_TEKYO10 || '	' || " . "\r\n";
            $strSQL .= "         R_KAMOK_CD || '	' || " . "\r\n";
            $strSQL .= "         R_KOUMK_CD || '	' || " . "\r\n";
            $strSQL .= "         R_KAZEI_KB || '	' || " . "\r\n";
            $strSQL .= "         R_TORHK_KB || '	' || " . "\r\n";
            $strSQL .= "         R_ZEI_RT_KB || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY1 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY2 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY3 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY4 || '	' || " . "\r\n";
            $strSQL .= "         R_KOUZA_KEY5 || '	' || " . "\r\n";
            $strSQL .= "         R_HASEI_KYOTN_CD || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO1 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO2 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO3 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO4 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO5 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO6 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO7 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO8 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO9 || '	' || " . "\r\n";
            $strSQL .= "         R_HISSU_TEKYO10 || '	' || " . "\r\n";
            $strSQL .= "         ZEIKM_GK || '	' || " . "\r\n";
            $strSQL .= "         ZEINK_GK || '	' || " . "\r\n";
            $strSQL .= "         SHZEI_GK || '	' || " . "\r\n";
            //20240416 LJX UPD S
            //$strSQL .= "         SUBSTRB(TEKYO,1,100) AS STRCSV" . "\r\n";
            $strSQL .= "         SUBSTRB(TEKYO,1,100) || '	' || " . "\r\n";
            $strSQL .= "         AITESAKI_KB || '	' || " . "\r\n";
            $strSQL .= "         OKYAKU_TORIHIKI_NO || '	' || " . "\r\n";
            $strSQL .= "         JIGYOSYA_NM || '	' || " . "\r\n";
            $strSQL .= "         INVOICE_ENTRYNO || '	' || " . "\r\n";
            $strSQL .= "         TOKUREI_KB AS STRCSV" . "\r\n";
            //20240416 LJX UPD E
            $strSQL .= "FROM     (" . "\r\n";
            $strSQL .= "SELECT   A.CSV_GROUP_NO," . "\r\n";
            $strSQL .= "         A.SEIKYUSYO_NO AS SYOHYO_NO," . "\r\n";
            $strSQL .= "         A.KEIRI_DT," . "\r\n";
            $strSQL .= "         A.TORIHIKI_DT," . "\r\n";
            $strSQL .= "         A.SHIHARAI_DT," . "\r\n";
            $strSQL .= "         A.ZEINK_GK," . "\r\n";
            $strSQL .= "         A.ZEIKM_GK," . "\r\n";
            $strSQL .= "         A.SHZEI_GK," . "\r\n";
            $strSQL .= "         REPLACE(REPLACE(REPLACE(A.TEKYO,CHR(13)||CHR(10),'　'),CHR(13),'　'),CHR(10),'　') TEKYO," . "\r\n";
            $strSQL .= "         A.L_KAMOK_CD," . "\r\n";
            $strSQL .= "         A.L_KOUMK_CD," . "\r\n";
            $strSQL .= "         A.L_HASEI_KYOTN_CD," . "\r\n";
            $strSQL .= "         A.L_KAZEI_KB," . "\r\n";
            $strSQL .= "         A.L_ZEI_RT_KB," . "\r\n";
            $strSQL .= "         A.L_TORHK_KB," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY1," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY2," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY3," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY4," . "\r\n";
            $strSQL .= "         A.L_KOUZA_KEY5," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO1," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO2," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO3," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO4," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO5," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO6," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO7," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO8," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO9," . "\r\n";
            $strSQL .= "         A.L_HISSU_TEKYO10," . "\r\n";
            $strSQL .= "         A.R_KAMOK_CD," . "\r\n";
            $strSQL .= "         A.R_KOUMK_CD," . "\r\n";
            $strSQL .= "         A.R_HASEI_KYOTN_CD," . "\r\n";
            $strSQL .= "         A.R_KAZEI_KB," . "\r\n";
            $strSQL .= "         A.R_ZEI_RT_KB," . "\r\n";
            $strSQL .= "         A.R_TORHK_KB," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY1," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY2," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY3," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY4," . "\r\n";
            $strSQL .= "         A.R_KOUZA_KEY5," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO1," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO2," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO3," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO4," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO5," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO6," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO7," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO8," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO9," . "\r\n";
            $strSQL .= "         A.R_HISSU_TEKYO10," . "\r\n";
            $strSQL .= "         A.SHIHARAISAKI_CD," . "\r\n";
            $strSQL .= "         A.SHIHARAISAKI_NM" . "\r\n";
            //20240416 LJX INS S
            $strSQL .= "         ,A.AITESAKI_KB " . "\r\n";
            $strSQL .= "         ,A.OKYAKU_TORIHIKI_NO " . "\r\n";
            $strSQL .= "         ,A.JIGYOSYA_NM " . "\r\n";
            $strSQL .= "         ,A.INVOICE_ENTRYNO " . "\r\n";
            $strSQL .= "         ,A.TOKUREI_KB " . "\r\n";
            //20240416 LJX INS E
            $strSQL .= "FROM     HDPSHIWAKEDATA A" . "\r\n";
            $strSQL .= "WHERE    A.CSV_GROUP_NO = '@GroupNo'" . "\r\n";
            $strSQL .= "ORDER BY A.CSV_OUT_ORDER" . "\r\n";
            $strSQL .= ",        A.SYOHY_NO" . "\r\n";
            $strSQL .= ",         A.EDA_NO" . "\r\n";
            $strSQL .= ",         A.GYO_NO" . "\r\n";
            $strSQL .= ")" . "\r\n";
        }
        $strSQL = str_replace("@GroupNo", $GroupNo, $strSQL);

        return parent::select($strSQL);
    }

}
