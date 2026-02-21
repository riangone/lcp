<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240227    20240213_機能改善要望対応 NO6    科目マスタの使用フラグ、使用フラグ名は撤廃で確定です    LUJUNXIA
 * 20240305    20240213_機能改善要望対応 NO2     検索を再実行しても、一覧の選択状態が消えない           LUJUNXIA
 * 20240322       本番障害.xlsx NO8                 科目名、補助科目名は両方表示してほしい            LUJUNXIA
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKOut4OBC extends ClsComDb
{
    //読み取りデータのチェックと読取書類ラベルへのセット
    public function FncChkAndSetShiwakeInfoSql($strSyohyoNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT   A.EDA_NO," . "\r\n";
        $strSQL .= "         DECODE(A.DENPY_KB, '1', '仕訳伝票', '2', '支払伝票', NULL) AS 読取書類," . "\r\n";
        $strSQL .= "         A.XLSX_OUT_FLG AS ＣＳＶ出力フラグ," . "\r\n";
        $strSQL .= "         A.DEL_FLG AS 削除フラグ," . "\r\n";
        $strSQL .= "         NVL(A.PRINT_OUT_FLG, '0') AS 印刷フラグ" . "\r\n";
        $strSQL .= "FROM     HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "         INNER JOIN (SELECT   BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BA.EDA_NO," . "\r\n";
        $strSQL .= "                              BB.XLSX_OUT_FLG," . "\r\n";
        $strSQL .= "                              MIN(BA.GYO_NO) AS GYO_NO" . "\r\n";
        $strSQL .= "                     FROM     HDPSHIWAKEDATA BA" . "\r\n";
        $strSQL .= "                              INNER JOIN (SELECT   SYOHY_NO," . "\r\n";
        $strSQL .= "                                                   MAX(XLSX_OUT_FLG) AS XLSX_OUT_FLG," . "\r\n";
        $strSQL .= "                                                   MAX(EDA_NO) AS EDA_NO" . "\r\n";
        $strSQL .= "                                          FROM     HDPSHIWAKEDATA" . "\r\n";
        $strSQL .= "                                          WHERE    SYOHY_NO = '@strSyohyoNo'" . "\r\n";
        $strSQL .= "                                          GROUP BY SYOHY_NO) BB" . "\r\n";
        $strSQL .= "                                  ON BA.SYOHY_NO = BB.SYOHY_NO" . "\r\n";
        $strSQL .= "                                 AND BA.EDA_NO = BB.EDA_NO" . "\r\n";
        $strSQL .= "                     GROUP BY BA.SYOHY_NO," . "\r\n";
        $strSQL .= "                              BB.XLSX_OUT_FLG," . "\r\n";
        $strSQL .= "                              BA.EDA_NO) B" . "\r\n";
        $strSQL .= "             ON A.SYOHY_NO = B.SYOHY_NO" . "\r\n";
        $strSQL .= "            AND A.EDA_NO = B.EDA_NO" . "\r\n";
        $strSQL .= "            AND A.GYO_NO = B.GYO_NO" . "\r\n";
        $strSQL .= "WHERE    A.SYOHY_NO = '@strSyohyoNo'" . "\r\n";

        $strSQL = str_replace("@strSyohyoNo", substr($strSyohyoNo, 0, 15), $strSQL);
        return parent::select($strSQL);
    }

    public function FncSetDataSql($data)
    {
        $strSQL = "";
        $strSQL .= "SELECT   '1' AS CHK_XLSX_STATUS," . "\r\n";
        $strSQL .= "         DECODE(A.DENPY_KB, '1', '仕訳伝票', '2', '支払伝票', NULL) AS SYOHYO_KBN," . "\r\n";
        $strSQL .= "         A.SYOHY_NO || A.EDA_NO AS SYOHYO_NO_VIEW," . "\r\n";
        $strSQL .= "         A.SYOHY_NO AS SYOHYO_NO," . "\r\n";
        $strSQL .= "         A.EDA_NO," . "\r\n";
        //20240322 lujunxia upd s
        //$strSQL .= "       (CASE WHEN A.L_KOUMK_CD IS NULL THEN ML.KAMOK_NAME ELSE ML.SUB_KAMOK_NAME END) KARIKATA," . "\r\n";
        //借方科目
        $strSQL .= "         ML.KAMOK_NAME L_KAMOKU,ML.SUB_KAMOK_NAME L_KOUMKU," . "\r\n";
        //貸方科目
        $strSQL .= "      CASE A.DENPY_KB  " . "\r\n";
        //$strSQL .= "       WHEN '1' THEN (CASE WHEN A.R_KOUMK_CD IS NULL THEN MR.KAMOK_NAME ELSE MR.SUB_KAMOK_NAME END) " . "\r\n";
        //$strSQL .= "       ELSE (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) END KASHIKATA," . "\r\n";
        $strSQL .= "       WHEN '1' THEN MR.KAMOK_NAME" . "\r\n";
        $strSQL .= "       ELSE KAS.MEISYOU END R_KAMOKU," . "\r\n";
        $strSQL .= "      CASE A.DENPY_KB  " . "\r\n";
        $strSQL .= "       WHEN '1' THEN MR.SUB_KAMOK_NAME " . "\r\n";
        $strSQL .= "       ELSE KAS.MOJI1 END R_KOUMKU," . "\r\n";
        //20240322 lujunxia upd e
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
        $strSQL .= "         LEFT JOIN HDK_MST_KAMOKU ML" . "\r\n";
        $strSQL .= "             ON A.L_KAMOK_CD = ML.KAMOK_CD" . "\r\n";
        //20240227 LUJUNXIA UPD S
        //$strSQL .= "            AND NVL(A.L_KOUMK_CD,'999999') = DECODE(A.L_KOUMK_CD,NULL,NVL(TRIM(ML.SUB_KAMOK_CD),'999999'),ML.SUB_KAMOK_CD) AND ML.USE_FLG='1'" . "\r\n";
        $strSQL .= "            AND NVL(A.L_KOUMK_CD,'999999') = DECODE(A.L_KOUMK_CD,NULL,NVL(TRIM(ML.SUB_KAMOK_CD),'999999'),ML.SUB_KAMOK_CD)" . "\r\n";
        //20240227 LUJUNXIA UPD E
        $strSQL .= "         LEFT JOIN HDK_MST_KAMOKU MR" . "\r\n";
        $strSQL .= "             ON A.R_KAMOK_CD = MR.KAMOK_CD" . "\r\n";
        //20240227 LUJUNXIA UPD S
        //$strSQL .= "            AND NVL(A.R_KOUMK_CD,'999999') = DECODE(A.R_KOUMK_CD,NULL,NVL(TRIM(MR.SUB_KAMOK_CD),'999999'),MR.SUB_KAMOK_CD) AND MR.USE_FLG='1'" . "\r\n";
        $strSQL .= "            AND NVL(A.R_KOUMK_CD,'999999') = DECODE(A.R_KOUMK_CD,NULL,NVL(TRIM(MR.SUB_KAMOK_CD),'999999'),MR.SUB_KAMOK_CD)" . "\r\n";
        //20240227 LUJUNXIA UPD E
        $strSQL .= "LEFT JOIN HMEISYOUMST KAS" . "\r\n";
        $strSQL .= "ON	TO_NUMBER(SUBSTR(KAS.MEISYOU_CD,1,1) || KAS.SUCHI1) = TO_NUMBER(A.SHR_KAMOK_KB || A.R_KAMOK_CD) AND DECODE(A.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUCHI2),'999999'),KAS.SUCHI2) = NVL(A.R_KOUMK_CD,'999999') AND KAS.MEISYOU_ID = 'DK'" . "\r\n";

        //未出力
        $strSQL .= "WHERE  A.XLSX_OUT_FLG <> '1'" . "\r\n";
        //印刷
        $strSQL .= "AND A.PRINT_OUT_FLG <> '0'" . "\r\n";
        //未削除
        $strSQL .= "AND A.DEL_FLG <> '1'" . "\r\n";
        //search key
        if ($data != '') {
            if (isset($data['keiriSyoribiFrom']) && $data['keiriSyoribiFrom'] != '') {
                //支払予定日from
                $strSQL .= "AND A.SHIHARAI_DT >= '@KEIRI_DT_F'" . "\r\n";
                $strSQL = str_replace("@KEIRI_DT_F", $data['keiriSyoribiFrom'], $strSQL);
            }
            if (isset($data['keiriSyoribiTo']) && $data['keiriSyoribiTo'] != '') {
                //支払予定日to
                $strSQL .= "AND A.SHIHARAI_DT <= '@KEIRI_DT_T'" . "\r\n";
                $strSQL = str_replace("@KEIRI_DT_T", $data['keiriSyoribiTo'], $strSQL);
            }
            if (isset($data['busyo']) && $data['busyo'] != '') {
                //部署
                $strSQL .= "AND A.UPD_BUSYO_CD = '@busyo'" . "\r\n";
                $strSQL = str_replace("@busyo", $data['busyo'], $strSQL);
            }
            if (isset($data['tanntousya']) && $data['tanntousya'] != '') {
                //作成担当者
                $strSQL .= "AND A.UPD_SYA_CD = '@tanntousya'" . "\r\n";
                $strSQL = str_replace("@tanntousya", $data['tanntousya'], $strSQL);
            }
            if (isset($data['kamoku1']) && $data['kamoku1'] != '') {
                //借方科目コード
                $strSQL .= "AND A.L_KAMOK_CD = '@kamoku1'" . "\r\n";
                $strSQL = str_replace("@kamoku1", $data['kamoku1'], $strSQL);
            }
            if (isset($data['kamoku2']) && $data['kamoku2'] != '') {
                //貸方科目コード
                $strSQL .= "AND A.R_KAMOK_CD = '@kamoku2'" . "\r\n";
                $strSQL = str_replace("@kamoku2", $data['kamoku2'], $strSQL);
            }
            if (isset($data['keyword']) && $data['keyword'] != '') {
                //キーワード(摘要)
                $strSQL .= "        AND " . "\r\n";
                $strSQL .= "        ( " . "\r\n";

                $strSQL .= "         A.TEKYO LIKE '%@KEYWORD%' OR " . "\r\n";

                $strSQL .= "         A.TORIHIKISAKI_CD LIKE '%@KEYWORD%' OR " . "\r\n";
                $strSQL .= "         A.TORIHIKISAKI_NAME LIKE '%@KEYWORD%' OR " . "\r\n";
                $strSQL .= "         ML.KAMOK_NAME LIKE '%@KEYWORD%' OR " . "\r\n";
                $strSQL .= "         ML.SUB_KAMOK_NAME LIKE '%@KEYWORD%' OR " . "\r\n";
                $strSQL .= "         (MR.KAMOK_NAME LIKE '%@KEYWORD%' AND A.DENPY_KB='1') OR " . "\r\n";
                $strSQL .= "         (MR.SUB_KAMOK_NAME LIKE '%@KEYWORD%' AND A.DENPY_KB='1') OR" . "\r\n";
                $strSQL .= "         (KAS.MEISYOU LIKE '%@KEYWORD%' AND A.DENPY_KB='2') OR " . "\r\n";
                $strSQL .= "         (KAS.MOJI1 LIKE '%@KEYWORD%' AND A.DENPY_KB='2') " . "\r\n";
                $strSQL .= "        ) " . "\r\n";
                $strSQL = str_replace("@KEYWORD", $data['keyword'], $strSQL);
            }
            //20240305 lujunxia ins s
            if (isset($data['selectedNo']) && $data['selectedNo'] != '') {
                //「選択データ一覧」にデータが表示しない
                $strSQL .= "AND A.SYOHY_NO NOT IN('@selectedNo')" . "\r\n";
                $strSQL = str_replace("@selectedNo", $data['selectedNo'], $strSQL);
            }
            //20240305 lujunxia ins e
        }
        $strSQL .= "GROUP BY A.SYOHY_NO," . "\r\n";
        $strSQL .= "         A.EDA_NO," . "\r\n";
        $strSQL .= "         A.DENPY_KB," . "\r\n";
        //20240322 lujunxia upd s
        //$strSQL .= "         (CASE WHEN A.L_KOUMK_CD IS NULL THEN ML.KAMOK_NAME ELSE ML.SUB_KAMOK_NAME END)," . "\r\n";
        $strSQL .= "         ML.KAMOK_NAME,ML.SUB_KAMOK_NAME," . "\r\n";
        $strSQL .= "        (CASE A.DENPY_KB  " . "\r\n";
        //$strSQL .= "       WHEN '1' THEN (CASE WHEN A.R_KOUMK_CD IS NULL THEN MR.KAMOK_NAME ELSE MR.SUB_KAMOK_NAME END) " . "\r\n";
        //$strSQL .= "       ELSE (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) END)," . "\r\n";
        $strSQL .= "       WHEN '1' THEN MR.KAMOK_NAME " . "\r\n";
        $strSQL .= "       ELSE KAS.MEISYOU END)," . "\r\n";
        $strSQL .= "        (CASE A.DENPY_KB  " . "\r\n";
        $strSQL .= "       WHEN '1' THEN MR.SUB_KAMOK_NAME " . "\r\n";
        $strSQL .= "       ELSE KAS.MOJI1 END)," . "\r\n";
        //20240322 lujunxia upd e
        $strSQL .= "         B.M_FUKANZEN_FLG," . "\r\n";
        $strSQL .= "         B.SUM_ZEIKM_GK,0," . "\r\n";
        $strSQL .= "         A.UPD_DATE" . "\r\n";
        $strSQL .= "  ORDER BY A.UPD_DATE DESC" . "\r\n";

        return parent::select($strSQL);
    }

    //出力グループ名の重複チェック
    public function FncChkExistGroupNMSql($lvTxtGroupName)
    {
        $strSQL = "";
        $strSQL .= "SELECT   COUNT(*)" . "\r\n";
        $strSQL .= "FROM     HDPOUTGROUPDATA_OBC A" . "\r\n";
        $strSQL .= "WHERE    A.CSV_GROUP_NM ='@lvTxtGroupName'" . "\r\n";

        $strSQL = str_replace("@lvTxtGroupName", $lvTxtGroupName, $strSQL);
        return parent::select($strSQL);
    }

    //グループ№の最新取得
    public function FncGetGroupNoSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT   NVL(MAX(A.CSV_GROUP_NO), 0) + 1" . "\r\n";
        $strSQL .= "FROM     HDPOUTGROUPDATA_OBC A" . "\r\n";

        return parent::select($strSQL);
    }

    //出力グループの登録
    public function SubInsertGroupDataSql($params)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDPOUTGROUPDATA_OBC (" . "\r\n";
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
        $strSQL .= "'HDKOut4OBC'," . "\r\n";
        $strSQL .= "'@MachineNM'," . "\r\n";
        $strSQL .= "TO_DATE('@sysDate', 'yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "'@LoginID'," . "\r\n";
        $strSQL .= "'HDKOut4OBC'," . "\r\n";
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
        $strSQL .= "XLSX_OUT_FLG = '1'," . "\r\n";
        //パターンＩＤが管理者又は本部かで分ける
        if ($params['PatternID'] == $params['CONST_ADMIN_PTN_NO'] || $params['PatternID'] == $params['CONST_HONBU_PTN_NO']) {
            $strSQL .= "HONBU_SYORIZUMI_FLG = '1'," . "\r\n";
        }
        //FLG追加のため
        $strSQL .= "XLSX_GROUP_NO = '@groupNo'," . "\r\n";
        $strSQL .= "XLSX_OUT_ORDER = '@intXlsxOutOrd'," . "\r\n";
        $strSQL .= "UPD_DATE = TO_DATE('@sysDate', 'yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "UPD_BUSYO_CD = '@UPD_BUSYO_CD'," . "\r\n";
        $strSQL .= "UPD_SYA_CD = '@LoginID'," . "\r\n";
        $strSQL .= "UPD_PRG_ID = 'HDKOut4OBC'," . "\r\n";
        $strSQL .= "UPD_CLT_NM = '@MachineNM'" . "\r\n";
        $strSQL .= "WHERE  SYOHY_NO = '@strSyohyoNo'" . "\r\n";
        $strSQL .= "AND    EDA_NO = '@strEdaNo'" . "\r\n";

        $strSQL = str_replace("@lvTxtKeiriSyoribi", $params['lvTxtKeiriSyoribi'], $strSQL);
        $strSQL = str_replace("@groupNo", $params['groupNo'], $strSQL);
        $strSQL = str_replace("@strSyohyoNo", $datas['strSyohyoNo'], $strSQL);
        $strSQL = str_replace("@strEdaNo", $datas['strEdaNo'], $strSQL);
        $strSQL = str_replace("@intXlsxOutOrd", $params['intXlsxOutOrd'], $strSQL);
        $strSQL = str_replace("@sysDate", $params['sysDate'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $params['BusyoCD'], $strSQL);
        $strSQL = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }

    //Excel出力処理(実行)
    public function XLSXDownloadSql($GroupNo, $type)
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        //日付
        $strSQL .= "  TO_DATE(A.KEIRI_DT,'yyyy-MM-dd') AS KEIRI_DT," . "\r\n";
        //借方部門コード
        $strSQL .= "  LB.BUSYO_CD                      AS L_BUSYO_CD," . "\r\n";
        //借方部門名
        $strSQL .= "  LB.BUSYO_NM                      AS L_BUSYO_NM," . "\r\n";
        //借方勘定科目コード
        $strSQL .= "  LK.KAMOK_CD                    AS L_KAMOK_CD," . "\r\n";
        //借方勘定科目名
        $strSQL .= "  LK.KAMOK_NAME                    AS L_KAMOK_NAME," . "\r\n";
        //借方補助科目コード
        $strSQL .= "  LK.SUB_KAMOK_CD                AS L_SUB_KAMOK_CD," . "\r\n";
        //借方補助科目名
        $strSQL .= "  LK.SUB_KAMOK_NAME                AS L_SUB_KAMUK_NAME," . "\r\n";
        //借方消費税区分名
        $strSQL .= "  LTAX.TAX_KBN_NAME                AS L_TAX_KBN_NAME," . "\r\n";
        //借方消費税率
        $strSQL .= "  LM.MEISYOU                       AS L_MEISYOU," . "\r\n";
        //取引先コード(借方、貸方の両方に同じ内容)
        $strSQL .= "  HMT.TORIHIKISAKI_CD," . "\r\n";
        //借方取引先名(借方、貸方の両方に同じ内容)
        $strSQL .= "  HMT.TORIHIKISAKI_NAME," . "\r\n";
        //貸方部門コード
        $strSQL .= "  RB.BUSYO_CD       AS R_BUSYO_CD," . "\r\n";
        //貸方部門名
        $strSQL .= "  RB.BUSYO_NM       AS R_BUSYO_NM," . "\r\n";
        //貸方勘定科目コード
        $strSQL .= "  A.R_KAMOK_CD      AS R_KAMOK_CD," . "\r\n";
        //貸方補助科目コード
        $strSQL .= "  A.R_KOUMK_CD      AS R_SUB_KAMOK_CD," . "\r\n";
        //本体金額->税込み金額
        $strSQL .= "  A.ZEIKM_GK     AS L_ZEIKM_GK," . "\r\n";
        $strSQL .= "  A.ZEIKM_GK     AS R_ZEIKM_GK," . "\r\n";
        if ($type == '0') {
            //仕訳データ
            //貸方/借方本体金額(税込金額)
            //$strSQL .= "  A.ZEIKM_GK AS L_ZEIKM_GK," . "\r\n";
            //$strSQL .= "  A.ZEIKM_GK AS R_ZEIKM_GK," . "\r\n";
            //貸方勘定科目名
            $strSQL .= "  RK.KAMOK_NAME     AS R_KAMOK_NAME," . "\r\n";
            //貸方補助科目名
            $strSQL .= "  RK.SUB_KAMOK_NAME AS R_SUB_KAMOK_NAME," . "\r\n";
        } else {
            //支払データ
            //借方本体金額(税込金額)
            //$strSQL .= "  A.L_ZEIKM_GK," . "\r\n";
            //貸方本体金額(税込金額)
            //$strSQL .= "  A.R_ZEIKM_GK," . "\r\n";
            //貸方勘定科目名
            $strSQL .= "  KAS.MEISYOU     AS R_KAMOK_NAME," . "\r\n";
            //貸方補助科目名
            $strSQL .= "  KAS.MOJI1 AS R_SUB_KAMOK_NAME," . "\r\n";
        }
        //貸方消費税区分名
        $strSQL .= "  RTAX.TAX_KBN_NAME AS R_TAX_KBN_NAME," . "\r\n";
        //貸方消費税率
        $strSQL .= "  RM.MEISYOU        AS R_MEISYOU," . "\r\n";
        //摘要
        $strSQL .= "  A.TEKYO" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "  HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON LB" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  A.L_HASEI_KYOTN_CD = LB.BUSYO_CD AND LB.USE_FLG='1'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_KAMOKU LK" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  LK.KAMOK_CD      =A.L_KAMOK_CD" . "\r\n";
        //20240227 LUJUNXIA UPD S
        //$strSQL .= "AND LK.SUB_KAMOK_CD=A.L_KOUMK_CD  AND LK.USE_FLG='1'" . "\r\n";
        $strSQL .= "AND LK.SUB_KAMOK_CD=A.L_KOUMK_CD" . "\r\n";
        //20240227 LUJUNXIA UPD E
        $strSQL .= "LEFT JOIN HDK_MST_SHZKBN LTAX" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  LTAX.TAX_KBN_CD = A.L_KAZEI_KB" . "\r\n";
        $strSQL .= "LEFT JOIN HMEISYOUMST LM" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        //HMEISYOUMSTから消費税率名を取得する箇所
        $strSQL .= "  LM.MEISYOU_CD= A.L_ZEI_RT_KB  AND LM.MEISYOU_ID= 'DS'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BUMON RB" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  A.R_HASEI_KYOTN_CD = RB.BUSYO_CD AND RB.USE_FLG='1'" . "\r\n";
        if ($type == '0') {
            //仕訳データ
            $strSQL .= "LEFT JOIN HDK_MST_KAMOKU RK" . "\r\n";
            $strSQL .= "ON" . "\r\n";
            $strSQL .= "  RK.KAMOK_CD      =A.R_KAMOK_CD" . "\r\n";
            //20240227 LUJUNXIA UPD S
            //$strSQL .= "AND RK.SUB_KAMOK_CD=A.R_KOUMK_CD  AND RK.USE_FLG='1'" . "\r\n";
            $strSQL .= "AND RK.SUB_KAMOK_CD=A.R_KOUMK_CD" . "\r\n";
            //20240227 LUJUNXIA UPD E
        } else {
            //支払データ
            $strSQL .= "LEFT JOIN HMEISYOUMST KAS" . "\r\n";
            $strSQL .= "ON	TO_NUMBER(SUBSTR(KAS.MEISYOU_CD,1,1) || KAS.SUCHI1) = TO_NUMBER(A.SHR_KAMOK_KB || A.R_KAMOK_CD) AND DECODE(A.R_KOUMK_CD,NULL,NVL(TRIM(KAS.SUCHI2),'999999'),KAS.SUCHI2) = NVL(A.R_KOUMK_CD,'999999') AND KAS.MEISYOU_ID = 'DK'" . "\r\n";
        }
        $strSQL .= "LEFT JOIN HDK_MST_SHZKBN RTAX" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  RTAX.TAX_KBN_CD = A.R_KAZEI_KB" . "\r\n";
        $strSQL .= "LEFT JOIN HMEISYOUMST RM" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        //HMEISYOUMSTから消費税率名を取得する箇所
        $strSQL .= "  RM.MEISYOU_CD= A.R_ZEI_RT_KB AND RM.MEISYOU_ID= 'DS'" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_TORIHIKISAKI HMT" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  A.TORIHIKISAKI_CD=HMT.TORIHIKISAKI_CD" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "  A.XLSX_GROUP_NO=@GroupNo" . "\r\n";
        $strSQL = str_replace("@GroupNo", $GroupNo, $strSQL);

        return parent::select($strSQL);
    }

}
