<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                担当
 * YYYYMMDD           #ID                                     XXXXXX                              FCSDL
 * 20240227       20240213_機能改善要望対応 NO6  科目マスタの使用フラグ、使用フラグ名は撤廃で確定です   LUJUNXIA
 * 20240306       20240213_機能改善要望対応.xlsx NO3  同一振込先の支払伝票が複数ある場合               YIN
 * 																									金額をまとめて１行にして出力してほしい
 * 20240318       本番障害.xlsx NO6               仕訳伝票を表示しない。支払伝票のみを抽出する        lujunxia
 * 20240319       本番障害.xlsx NO7　       全銀協システムに アップロードしたところエラーになりました  lujunxia
 * 20240322       本番障害.xlsx NO8         科目名、補助科目名のいずれかしか表示していない箇所があるが  caina
 * 20240328      全銀協連携データ出力               画面構成を OBC取込データ出力 と同じ構成           lujunxia
 * 20240527      全銀協連携データ出力       預金種目には 4ではなく9をセットしていただけますか           yinhuaiyu
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
class HDKOut4ZenGin extends ClsComDb
{
    //読み取りデータのチェックと読取書類ラベルへのセット
    //Mode=0[ラベルへのセットのみ], Mode=1[チェック＆メッセージ付], Mode=2[CSV出力時の再チェック]
    public function FncChkAndSetShiwakeInfoSql($strSyohyoNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT   A.EDA_NO," . "\r\n";
        $strSQL .= "         DECODE(A.DENPY_KB, '1', '仕訳伝票', '2', '支払伝票', NULL) AS 読取書類," . "\r\n";
        $strSQL .= "         A.CSV_OUT_FLG AS ＣＳＶ出力フラグ," . "\r\n";
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
    public function FncSetDataSql($data)
    {
        $strSQL = "";
        $strSQL .= "SELECT   '1' AS CHK_CSV_STATUS," . "\r\n";
        $strSQL .= "         DECODE(A.DENPY_KB, '1', '仕訳伝票', '2', '支払伝票', NULL) AS SYOHYO_KBN," . "\r\n";
        $strSQL .= "         A.SYOHY_NO || A.EDA_NO AS SYOHYO_NO_VIEW," . "\r\n";
        $strSQL .= "         A.SYOHY_NO AS SYOHYO_NO," . "\r\n";
        $strSQL .= "         A.EDA_NO," . "\r\n";
        //20240322 caina upd s
        // $strSQL .= "       (CASE WHEN A.L_KOUMK_CD IS NULL THEN ML.KAMOK_NAME ELSE ML.SUB_KAMOK_NAME END) KARIKATA," . "\r\n";
        $strSQL .= "         ML.KAMOK_NAME AS L_KAMOKU," . "\r\n";
        $strSQL .= "         ML.SUB_KAMOK_NAME AS L_KOUMKU," . "\r\n";
        //20240322 caina upd e
        $strSQL .= "      CASE A.DENPY_KB  " . "\r\n";
        //20240322 caina upd s
        // $strSQL .= "       WHEN '1' THEN (CASE WHEN A.R_KOUMK_CD IS NULL THEN MR.KAMOK_NAME ELSE MR.SUB_KAMOK_NAME END) " . "\r\n";
        // $strSQL .= "       ELSE (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) END KASHIKATA," . "\r\n";
        $strSQL .= "       WHEN '1' THEN MR.KAMOK_NAME ELSE KAS.MEISYOU END AS R_KAMOKU," . "\r\n";
        $strSQL .= "      CASE A.DENPY_KB  " . "\r\n";
        $strSQL .= "       WHEN '1' THEN MR.SUB_KAMOK_NAME ELSE KAS.MOJI1 END AS R_KOUMKU," . "\r\n";
        //20240322 caina upd e
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
        $strSQL .= "WHERE  A.CSV_OUT_FLG <> '1'" . "\r\n";
        //印刷
        $strSQL .= "AND A.PRINT_OUT_FLG <> '0'" . "\r\n";
        //未削除
        $strSQL .= "AND A.DEL_FLG <> '1'" . "\r\n";
        //20240318 lujunxia ins s
        //仕訳伝票を表示しない。支払伝票のみを抽出する
        $strSQL .= "AND A.DENPY_KB = '2'" . "\r\n";
        //20240318 lujunxia ins e
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
            //20240328 lujunxia ins s
            if (isset($data['selectedNo']) && $data['selectedNo'] != '') {
                //「選択データ一覧」にデータが表示しない
                $strSQL .= "AND A.SYOHY_NO NOT IN('@selectedNo')" . "\r\n";
                $strSQL = str_replace("@selectedNo", $data['selectedNo'], $strSQL);
            }
            //20240328 lujunxia ins e
        }
        $strSQL .= "GROUP BY A.SYOHY_NO," . "\r\n";
        $strSQL .= "         A.EDA_NO," . "\r\n";
        $strSQL .= "         A.DENPY_KB," . "\r\n";
        //20240322 caina upd s
        // $strSQL .= "         (CASE WHEN A.L_KOUMK_CD IS NULL THEN ML.KAMOK_NAME ELSE ML.SUB_KAMOK_NAME END)," . "\r\n";
        $strSQL .= "         ML.KAMOK_NAME," . "\r\n";
        $strSQL .= "         ML.SUB_KAMOK_NAME," . "\r\n";
        // $strSQL .= "        (CASE A.DENPY_KB  " . "\r\n";
        // $strSQL .= "       WHEN '1' THEN (CASE WHEN A.R_KOUMK_CD IS NULL THEN MR.KAMOK_NAME ELSE MR.SUB_KAMOK_NAME END) " . "\r\n";
        // $strSQL .= "       ELSE (CASE WHEN KAS.MOJI1 IS NULL THEN KAS.MEISYOU ELSE KAS.MOJI1 END) END)," . "\r\n";
        $strSQL .= "         CASE A.DENPY_KB WHEN '1' THEN MR.KAMOK_NAME ELSE KAS.MEISYOU END," . "\r\n";
        $strSQL .= "         CASE A.DENPY_KB WHEN '1' THEN MR.SUB_KAMOK_NAME ELSE KAS.MOJI1 END," . "\r\n";
        //20240322 caina upd e
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
        $strSQL .= "FROM     HDPOUTGROUPDATA_ZENGIN A" . "\r\n";
        $strSQL .= "WHERE    A.CSV_GROUP_NM ='@lvTxtGroupName'" . "\r\n";

        $strSQL = str_replace("@lvTxtGroupName", $lvTxtGroupName, $strSQL);
        return parent::select($strSQL);
    }

    //グループ№の最新取得
    public function FncGetGroupNoSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT   NVL(MAX(A.CSV_GROUP_NO), 0) + 1" . "\r\n";
        $strSQL .= "FROM     HDPOUTGROUPDATA_ZENGIN A" . "\r\n";

        return parent::select($strSQL);
    }

    //出力グループの登録
    public function SubInsertGroupDataSql($params)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO HDPOUTGROUPDATA_ZENGIN (" . "\r\n";
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
        $strSQL .= "'HDKOut4ZenGin'," . "\r\n";
        $strSQL .= "'@MachineNM'," . "\r\n";
        $strSQL .= "TO_DATE('@sysDate', 'yyyy/MM/dd HH24:MI:SS')," . "\r\n";
        $strSQL .= "'@LoginID'," . "\r\n";
        $strSQL .= "'HDKOut4ZenGin'," . "\r\n";
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
        $strSQL .= "UPD_PRG_ID = 'HDKOut4ZenGin'," . "\r\n";
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
    public function CSVDownloadSql($GroupNo)
    {
        $strSQL = "";
        $strSQL .= "SELECT DISTINCT B.SYOHY_NO,B.GYO_NO," . "\r\n";
        // 20240306 YIN UPD S
        //20240319 lujunxia upd s
        $strSQL .= "B.BANK_CD," . "\r\n";
        $strSQL .= "B.BANK_KANA," . "\r\n";
        $strSQL .= "B.BRANCH_CD," . "\r\n";
        $strSQL .= "B.BRANCH_KANA," . "\r\n";
        //20240319 lujunxia upd e
        $strSQL .= "B.GINKO_NM," . "\r\n";
        $strSQL .= "B.SHITEN_NM," . "\r\n";
        // 20240306 YIN UPD E
        $strSQL .= "B.YOKIN_SYUBETU," . "\r\n";
        $strSQL .= "B.KOUZA_NO," . "\r\n";
        $strSQL .= "B.KOUZA_KN," . "\r\n";
        $strSQL .= "B.ZEIKM_GK" . "\r\n";
        $strSQL .= "FROM (" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        // 20240306 YIN UPD S
        //20240319 lujunxia upd s
        $strSQL .= "  NVL(HMB.BANK_CD,'')      AS BANK_CD," . "\r\n";
        $strSQL .= "  NVL(HMB.BANK_KANA,'')    AS BANK_KANA," . "\r\n";
        $strSQL .= "  NVL(HMBCH.BRANCH_CD,'')  AS BRANCH_CD," . "\r\n";
        $strSQL .= "  NVL(HMBCH.BRANCH_KANA,'')AS BRANCH_KANA," . "\r\n";
        //20240319 lujunxia upd e
        $strSQL .= " CASE " . "\r\n";
        $strSQL .= " WHEN A.GINKO_KB = '1' THEN '（GD）' " . "\r\n";
        $strSQL .= " WHEN A.GINKO_KB = '2' THEN 'もみじ' " . "\r\n";
        $strSQL .= " WHEN A.GINKO_KB = '3' THEN '（GD）信金' " . "\r\n";
        $strSQL .= " ELSE A.GINKO_NM " . "\r\n";
        $strSQL .= " END AS GINKO_NM, " . "\r\n";
        $strSQL .= " A.SHITEN_NM, " . "\r\n";
        // 20240306 YIN UPD E
        //預金種目:1普通 2当座 9その他->4 で出力す
        // 20240527 YIN UPD S
        // $strSQL .= "  DECODE(YOKIN_SYUBETU,9,4,NVL(YOKIN_SYUBETU,'')) AS YOKIN_SYUBETU," . "\r\n";
        $strSQL .= "  NVL(YOKIN_SYUBETU,'') AS YOKIN_SYUBETU," . "\r\n";
        // 20240527 YIN UPD E
        $strSQL .= "  NVL(A.KOUZA_NO,'')       AS KOUZA_NO," . "\r\n";
        $strSQL .= "  NVL(A.KOUZA_KN,'')       AS KOUZA_KN," . "\r\n";
        $strSQL .= "  NVL(A.ZEIKM_GK,'0')       AS ZEIKM_GK," . "\r\n";
        $strSQL .= "  A.SYOHY_NO,A.GYO_NO" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "  HDPSHIWAKEDATA A" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BANK HMB" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  (" . "\r\n";
        $strSQL .= "    CASE" . "\r\n";
        $strSQL .= "      WHEN A.GINKO_KB = '1'" . "\r\n";
        $strSQL .= "      THEN '（GD）'" . "\r\n";
        $strSQL .= "      WHEN A.GINKO_KB = '2'" . "\r\n";
        $strSQL .= "      THEN 'もみじ'" . "\r\n";
        $strSQL .= "      WHEN A.GINKO_KB = '3'" . "\r\n";
        $strSQL .= "      THEN '（GD）信金'" . "\r\n";
        $strSQL .= "      ELSE A.GINKO_NM" . "\r\n";
        $strSQL .= "    END" . "\r\n";
        $strSQL .= "  )" . "\r\n";
        $strSQL .= "  = HMB.BANK_NM" . "\r\n";
        $strSQL .= "LEFT JOIN HDK_MST_BANK HMBCH" . "\r\n";
        $strSQL .= "ON" . "\r\n";
        $strSQL .= "  (" . "\r\n";
        $strSQL .= "    CASE" . "\r\n";
        $strSQL .= "      WHEN A.GINKO_KB = '1'" . "\r\n";
        $strSQL .= "      THEN '（GD）'" . "\r\n";
        $strSQL .= "      WHEN A.GINKO_KB = '2'" . "\r\n";
        $strSQL .= "      THEN 'もみじ'" . "\r\n";
        $strSQL .= "      WHEN A.GINKO_KB = '3'" . "\r\n";
        $strSQL .= "      THEN '（GD）信金'" . "\r\n";
        $strSQL .= "      ELSE A.GINKO_NM" . "\r\n";
        $strSQL .= "    END" . "\r\n";
        $strSQL .= "  )" . "\r\n";
        $strSQL .= "                = HMBCH.BANK_NM" . "\r\n";
        $strSQL .= "AND A.SHITEN_NM = HMBCH.BRANCH_NM" . "\r\n";
        $strSQL .= "WHERE    A.CSV_GROUP_NO = '@GroupNo'" . "\r\n";
        // 20240306 YIN INS S
        $strSQL .= "AND    A.DENPY_KB = '@DENPY_KB'" . "\r\n";
        // 20240306 YIN INS E
        $strSQL .= "ORDER BY A.CSV_OUT_ORDER" . "\r\n";
        $strSQL .= ",        A.SYOHY_NO" . "\r\n";
        $strSQL .= ",         A.EDA_NO" . "\r\n";
        $strSQL .= ",         A.GYO_NO) B" . "\r\n";
        $strSQL = str_replace("@GroupNo", $GroupNo, $strSQL);

        // 20240306 YIN INS S
        $strSQL_UNION = "";
        $strSQL_UNION .= "SELECT " . "\r\n";
        $strSQL_UNION .= "    '' AS SYOHY_NO," . "\r\n";
        $strSQL_UNION .= "    0 AS GYO_NO," . "\r\n";
        $strSQL_UNION .= "    T.GINKO_NM," . "\r\n";
        $strSQL_UNION .= "    T.SHITEN_NM," . "\r\n";
        $strSQL_UNION .= "    T.YOKIN_SYUBETU," . "\r\n";
        $strSQL_UNION .= "    T.KOUZA_NO," . "\r\n";
        $strSQL_UNION .= "    T.KOUZA_KN," . "\r\n";
        //20240319 lujunxia ins s
        $strSQL_UNION .= "    T.BANK_CD," . "\r\n";
        $strSQL_UNION .= "    T.BANK_KANA," . "\r\n";
        $strSQL_UNION .= "    T.BRANCH_CD," . "\r\n";
        $strSQL_UNION .= "    T.BRANCH_KANA," . "\r\n";
        //20240319 lujunxia ins e
        $strSQL_UNION .= "    SUM(T.ZEIKM_GK) AS ZEIKM_GK" . "\r\n";
        $strSQL_UNION .= "FROM" . "\r\n";
        $strSQL_UNION .= "    (" . "\r\n";
        $strSQL_UNION .= str_replace("@DENPY_KB", '2', $strSQL);
        $strSQL_UNION .= "    ) T " . "\r\n";
        $strSQL_UNION .= "GROUP BY" . "\r\n";
        $strSQL_UNION .= "    T.GINKO_NM," . "\r\n";
        $strSQL_UNION .= "    T.SHITEN_NM," . "\r\n";
        $strSQL_UNION .= "    T.YOKIN_SYUBETU," . "\r\n";
        $strSQL_UNION .= "    T.KOUZA_NO," . "\r\n";
        //20240319 lujunxia ins s
        $strSQL_UNION .= "    T.BANK_CD," . "\r\n";
        $strSQL_UNION .= "    T.BANK_KANA," . "\r\n";
        $strSQL_UNION .= "    T.BRANCH_CD," . "\r\n";
        $strSQL_UNION .= "    T.BRANCH_KANA," . "\r\n";
        //20240319 lujunxia ins e
        $strSQL_UNION .= "    T.KOUZA_KN" . "\r\n";
        //20240318 lujunxia del s
        //仕訳伝票を表示しない。支払伝票のみを抽出する
        // $strSQL_UNION .= "UNION" . "\r\n";
        // $strSQL_UNION .= str_replace("@DENPY_KB", '1', $strSQL);
        //20240318 lujunxia del e
        // 20240306 YIN INS E

        // 20240306 YIN UPD S
        // return parent::select($strSQL);
        return parent::select($strSQL_UNION);
        // 20240306 YIN UPD E
    }

}
