<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmJinkenhiCsv extends ClsComDb
{
    //処理年月取得SQL
    private function selShoriYMSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT SYORI_YM " . "\r\n";
        $strSQL .= " FROM   JKCONTROLMST " . "\r\n";
        $strSQL .= " WHERE  ID = '01' " . "\r\n";

        return $strSQL;
    }

    //人件費負担金取得SQL
    private function selJinkenhiFutankinSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= " SELECT TO_CHAR(HONBU_FUTANKIN, 'FM999,999') HONBU_FUTANKIN " . "\r\n";
        $strSQL .= "       ,TO_CHAR(SEIBI_FUTANKIN, 'FM999,999') SEIBI_FUTANKIN " . "\r\n";
        $strSQL .= " FROM   JKJINKENHIFUTANKIN " . "\r\n";
        $strSQL .= " WHERE  TAISYOU_YM = '" . $taishoYM . "' " . "\r\n";

        return $strSQL;
    }

    //負担金データ削除SQL
    private function delFutankinDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= " DELETE " . "\r\n";
        $strSQL .= " FROM   JKJINKENHIFUTANKIN " . "\r\n";
        $strSQL .= " WHERE  TAISYOU_YM = '" . $taishoYM . "' " . "\r\n";
        return $strSQL;
    }

    //負担金データ登録SQL
    private function insFutankinDataSQL($taishoYM, $HombuFutankin, $SeibiFutankin)
    {

        $strSQL = "";
        $strSQL .= " INSERT INTO JKJINKENHIFUTANKIN " . "\r\n";
        $strSQL .= "      (TAISYOU_YM " . "\r\n";
        $strSQL .= "      ,HONBU_FUTANKIN " . "\r\n";
        $strSQL .= "      ,SEIBI_FUTANKIN " . "\r\n";
        $strSQL .= "      ,CREATE_DATE " . "\r\n";
        $strSQL .= "      ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "      ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "      ,CRE_CLT_NM) " . "\r\n";
        $strSQL .= " VALUES ('" . $taishoYM . "' " . "\r\n";
        $strSQL .= " , " . str_replace(",", "", $HombuFutankin) . "\r\n";
        $strSQL .= " , " . str_replace(",", "", $SeibiFutankin) . "\r\n";
        $strSQL .= "      ,SYSDATE " . "\r\n";
        $strSQL .= " ,'" . $this->GS_LOGINUSER["strUserID"] . "' " . "\r\n";
        $strSQL .= "      ,'JinkenhiCsv' " . "\r\n";
        $strSQL .= " ,'" . $this->GS_LOGINUSER["strClientNM"] . "') " . "\r\n";

        return $strSQL;
    }

    //人件費科目変換データ削除SQL
    private function delKamokuHenkanDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= " DELETE " . "\r\n";
        $strSQL .= " FROM   JKJINKENHIKMKCNVDATA " . "\r\n";
        $strSQL .= " WHERE  TAISYOU_YM = '" . $taishoYM . "' " . "\r\n";

        return $strSQL;
    }

    //人件費科目変換データ(借方データ)登録SQL
    private function insLKamokuHenkanDataSQL($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, $KoumkNo)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO JKJINKENHIKMKCNVDATA " . "\r\n";
        $strSQL .= "     (TAISYOU_YM " . "\r\n";
        $strSQL .= "     ,TAISK_KB " . "\r\n";
        $strSQL .= "     ,BUSYO_CD " . "\r\n";
        $strSQL .= "     ,KAMOK_CD " . "\r\n";
        $strSQL .= "     ,HIMOK_CD " . "\r\n";
        $strSQL .= "     ,KINGAKU " . "\r\n";
        $strSQL .= "     ,CREATE_DATE " . "\r\n";
        $strSQL .= "     ,CRE_SYA_CD " . "\r\n";
        $strSQL .= "     ,CRE_PRG_ID " . "\r\n";
        $strSQL .= "     ,CRE_CLT_NM) " . "\r\n";
        //対象年月
        $strSQL .= " SELECT   '" . $TAISYOU_YM . "' " . "\r\n";
        //貸借区分
        $strSQL .= "      ,'1' " . "\r\n";
        //部署コード
        $strSQL .= "     ,JIN.BUSYO_CD " . "\r\n";
        //科目コード
        $strSQL .= "     ,SYO.KAMOK_CD " . "\r\n";
        //費目コード
        $strSQL .= "     ,SYO.HIMOK_CD " . "\r\n";
        //金額
        switch ($KoumkNo) {
            //定時間月収
            case '1':
                //給与（定時間）
                $strSQL .= "      ,SUM(JIN.TEIJIKAN_GESSYU) " . "\r\n";
                break;
            //其他手当
            case '2':
                //給与（諸手当）
                $strSQL .= "      ,SUM(JIN.SONOTA_TEATE) " . "\r\n";
                break;
            //残業手当
            case '3':
                //給与（残業手当）
                $strSQL .= "      ,SUM(JIN.ZANGYOU_TEATE) " . "\r\n";
                break;
            //業績奨励金＋他業績奨励金
            case '4':
                //給与（奨励金）
                $strSQL .= "      ,SUM(JIN.GYOUSEKI_SYOUREI + JIN.HOKA_GSK_SYOUREI) " . "\r\n";
                break;
            //健康保険料＋介護保険料＋厚生年金＋雇用保険料＋労災保険＋児童手当
            case '5':
                //福利厚生費
                $strSQL .= "      ,SUM(JIN.KENKO_HKN_RYO + JIN.KAIGO_HKN_RYO + JIN.KOUSEINENKIN + JIN.KOYOU_HKN_RYO + JIN.ROUSAI_HKN_RYO + JIN.JIDOUTEATE " . "\r\n";
                $strSQL .= "      + JIN.BNS_KENKO_HKN_RYO + JIN.BNS_KAIGO_HKN_RYO + JIN.BNS_KOUSEI_NENKIN + JIN.BNS_JIDOU_TEATE) " . "\r\n";
                break;
            //退職給付
            case '6':
                //退職手当
                $strSQL .= "      ,SUM(JIN.TAISYOKU_KYUFU) " . "\r\n";
                break;
            //賞与見積
            case '7':
                //賞与
                $strSQL .= "      ,SUM(JIN.BNS_MITUMORI) " . "\r\n";
                break;
            //人員カウント
            case '8':
                //総人員
                $strSQL .= "     ,SUM(JIN.JININ_CNT) " . "\r\n";
                break;
            //人員カウント
            case '9':
                //人員データ
                $strSQL .= "     ,SUM(JIN.JININ_CNT) " . "\r\n";
                break;
            //人員カウント×画面.本部負担金
            case '10':
                //本部負担金データ
                $strSQL .= "     ,SUM(JIN.JININ_CNT * " . str_replace(",", "", $HombuFutankin) . ") " . "\r\n";
                break;
            //人員カウント×画面.整備負担金
            case '11':
                //整備負担金データ
                $strSQL .= "      ,SUM(JIN.JININ_CNT * " . str_replace(",", "", $SeibiFutankin) . ") " . "\r\n";
                break;
        }
        $strSQL .= "        ,SYSDATE" . "\r\n";
        $strSQL .= "        ,'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        $strSQL .= "        ,'JinkenhiCsv'" . "\r\n";
        $strSQL .= "        ,'" . $this->GS_LOGINUSER["strClientNM"] . "'" . "\r\n";
        $strSQL .= " FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "        ,JKSYOKUSYUKAMOKCNV SYO" . "\r\n";
        $strSQL .= " WHERE   JIN.TAISYOU_YM = '" . $TAISYOU_YM . "'" . "\r\n";
        $strSQL .= " AND     DECODE(SYO.SYOKUSYU_CD,'ZZZ','1',NVL(JIN.SYOKUSYU_CD,'000')) = DECODE(SYO.SYOKUSYU_CD,'ZZZ','1',SYO.SYOKUSYU_CD)" . "\r\n";
        $strSQL .= " AND     SYO.KOUMK_NO = " . $KoumkNo . "\r\n";
        if ($KoumkNo == 11) {
            $strSQL .= " AND     JIN.BUSYO_CD <> '251'" . "\r\n";
        }
        $strSQL .= " GROUP BY JIN.BUSYO_CD, SYO.KAMOK_CD, SYO.HIMOK_CD " . "\r\n";

        return $strSQL;
    }

    //人件費科目変換データ(貸方データ)登録SQL
    private function insRKamokuHenkanDataSQL($TAISYOU_YM, $BusyoCd, $KamokCd, $HimokCD = null)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO JKJINKENHIKMKCNVDATA" . "\r\n";
        $strSQL .= "    (TAISYOU_YM" . "\r\n";
        $strSQL .= "    ,TAISK_KB" . "\r\n";
        $strSQL .= "    ,BUSYO_CD" . "\r\n";
        $strSQL .= "    ,KAMOK_CD" . "\r\n";
        $strSQL .= "    ,HIMOK_CD" . "\r\n";
        $strSQL .= "    ,KINGAKU" . "\r\n";
        $strSQL .= "    ,CREATE_DATE" . "\r\n";
        $strSQL .= "    ,CRE_SYA_CD" . "\r\n";
        $strSQL .= "    ,CRE_PRG_ID" . "\r\n";
        $strSQL .= "    ,CRE_CLT_NM)" . "\r\n";
        //対象年月
        $strSQL .= "SELECT   '" . $TAISYOU_YM . "'" . "\r\n";
        //貸借区分
        $strSQL .= "        ,'2'" . "\r\n";
        //部署コード
        $strSQL .= "        ,'" . $BusyoCd . "' BUSYO_CD" . "\r\n";
        //科目コード
        $strSQL .= "        ,'" . $KamokCd . "' KAMOK_CD" . "\r\n";

        if ($HimokCD == null) {
            $strSQL .= "        ,'     ' HIMOK_CD" . "\r\n";
        } else {
            $strSQL .= "        ,'" . $HimokCD . "' HIMOK_CD" . "\r\n";
        }
        //金額
        $strSQL .= "        ,SUM(KMK.KINGAKU)" . "\r\n";
        //作成日付
        $strSQL .= "        ,SYSDATE" . "\r\n";
        //作成者
        $strSQL .= "        ,'" . $this->GS_LOGINUSER["strUserID"] . "'" . "\r\n";
        //作成APP
        $strSQL .= "        ,'JinkenhiCsv'" . "\r\n";
        //作成マシン
        $strSQL .= "        ,'" . $this->GS_LOGINUSER["strClientNM"] . "'" . "\r\n";
        $strSQL .= " FROM    JKJINKENHIKMKCNVDATA KMK " . "\r\n";
        $strSQL .= " WHERE   KMK.TAISYOU_YM = '" . $TAISYOU_YM . "'" . "\r\n";
        $strSQL .= " AND     KMK.KAMOK_CD = '" . $KamokCd . "'" . "\r\n";
        $strSQL .= " GROUP BY KMK.KAMOK_CD " . "\r\n";

        return $strSQL;
    }

    //人件費科目変換データ取得SQL
    private function selKamokuHenkanDataSQL($TAISYOU_YM)
    {
        $strSQL = "";
        //20210303 CI UPD S
        //$strSQL .= "SELECT SUBSTR(JPDATE(TAISYOU_YM || '01', '2'), 1, 4) TAISYOU_YM " . "\r\n";
        $strSQL .= "SELECT TAISYOU_YM" . "\r\n";
        //20210303 CI UPD E
        $strSQL .= "      ,TAISK_KB " . "\r\n";
        $strSQL .= "      ,BUSYO_CD " . "\r\n";
        $strSQL .= "      ,KAMOK_CD " . "\r\n";
        $strSQL .= "      ,KINGAKU " . "\r\n";
        $strSQL .= "      ,HIMOK_CD " . "\r\n";
        $strSQL .= " FROM   JKJINKENHIKMKCNVDATA " . "\r\n";
        $strSQL .= " WHERE  TAISYOU_YM = '" . $TAISYOU_YM . "' " . "\r\n";
        $strSQL .= " ORDER BY TAISK_KB, BUSYO_CD, KAMOK_CD, HIMOK_CD " . "\r\n";

        return $strSQL;
    }

    // 人件費科目変換データ取得SQL
    private function selJinkenhiMeisaiDataSQL($TAISYOU_YM)
    {
        $strSQL = "";
        $strSQL .= "SELECT JIN.SYAIN_NO" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      JIN.BUSYO_CD" . "\r\n";
        //20251125 UPDATE START
//        $strSQL .= ",      JIN.TEIJIKAN_GESSYU + JIN.ZANGYOU_TEATE + JIN.GYOUSEKI_SYOUREI + JIN.HOKA_GSK_SYOUREI + JIN.SONOTA_TEATE AS KYUYOKEI " . "\r\n";
//        $strSQL .= ",      JIN.KENKO_HKN_RYO + JIN.KAIGO_HKN_RYO + JIN.KOUSEINENKIN + JIN.KOYOU_HKN_RYO + JIN.ROUSAI_HKN_RYO + JIN.JIDOUTEATE " . "\r\n";
//        $strSQL .= "       + JIN.TAISYOKU_KYUFU + JIN.BNS_KENKO_HKN_RYO + JIN.BNS_KAIGO_HKN_RYO + JIN.BNS_KOUSEI_NENKIN + JIN.BNS_JIDOU_TEATE AS SYAHOKEI " . "\r\n";
//        $strSQL .= ",      JIN.BNS_MITUMORI" . "\r\n";
//        $strSQL .= ",      JIN.TEIJIKAN_GESSYU + JIN.ZANGYOU_TEATE + JIN.GYOUSEKI_SYOUREI + JIN.HOKA_GSK_SYOUREI + JIN.SONOTA_TEATE" . "\r\n";
//        $strSQL .= "       + JIN.KENKO_HKN_RYO + JIN.KAIGO_HKN_RYO + JIN.KOUSEINENKIN + JIN.KOYOU_HKN_RYO + JIN.ROUSAI_HKN_RYO + JIN.JIDOUTEATE" . "\r\n";
//        $strSQL .= "       + JIN.TAISYOKU_KYUFU + JIN.BNS_KENKO_HKN_RYO + JIN.BNS_KAIGO_HKN_RYO + JIN.BNS_KOUSEI_NENKIN + JIN.BNS_JIDOU_TEATE" . "\r\n";
//        $strSQL .= "       + JIN.BNS_MITUMORI AS JINKENHIKEI " . "\r\n";
//        $strSQL .= ",      TEIJIKAN_GESSYU " . "\r\n";
        $strSQL .= ",      NVL(JIN.TEIJIKAN_GESSYU,0) + NVL(JIN.ZANGYOU_TEATE,0) + NVL(JIN.GYOUSEKI_SYOUREI,0) + NVL(JIN.HOKA_GSK_SYOUREI,0) + NVL(JIN.SONOTA_TEATE,0) AS KYUYOKEI " . "\r\n";
        $strSQL .= ",      NVL(JIN.KENKO_HKN_RYO,0) + NVL(JIN.KAIGO_HKN_RYO,0) + NVL(JIN.KOUSEINENKIN,0) + NVL(JIN.KOYOU_HKN_RYO,0) + NVL(JIN.ROUSAI_HKN_RYO,0) + NVL(JIN.JIDOUTEATE,0) " . "\r\n";
        $strSQL .= "       + NVL(JIN.TAISYOKU_KYUFU,0) + NVL(JIN.BNS_KENKO_HKN_RYO,0) + NVL(JIN.BNS_KAIGO_HKN_RYO,0) + NVL(JIN.BNS_KOUSEI_NENKIN,0) + NVL(JIN.BNS_JIDOU_TEATE,0) AS SYAHOKEI " . "\r\n";
        $strSQL .= ",      NVL(JIN.BNS_MITUMORI,0) AS BNS_MITUMORI" . "\r\n";
        $strSQL .= ",      NVL(JIN.TEIJIKAN_GESSYU,0) + NVL(JIN.ZANGYOU_TEATE,0) + NVL(JIN.GYOUSEKI_SYOUREI,0) + NVL(JIN.HOKA_GSK_SYOUREI,0) + NVL(JIN.SONOTA_TEATE,0)" . "\r\n";
        $strSQL .= "       + NVL(JIN.KENKO_HKN_RYO,0) + NVL(JIN.KAIGO_HKN_RYO,0) + NVL(JIN.KOUSEINENKIN,0) + NVL(JIN.KOYOU_HKN_RYO,0) + NVL(JIN.ROUSAI_HKN_RYO,0) + NVL(JIN.JIDOUTEATE,0)" . "\r\n";
        $strSQL .= "       + NVL(JIN.TAISYOKU_KYUFU,0) + NVL(JIN.BNS_KENKO_HKN_RYO,0) + NVL(JIN.BNS_KAIGO_HKN_RYO,0) + NVL(JIN.BNS_KOUSEI_NENKIN,0) + NVL(JIN.BNS_JIDOU_TEATE,0)" . "\r\n";
        $strSQL .= "       + NVL(JIN.BNS_MITUMORI,0) AS JINKENHIKEI " . "\r\n";
        $strSQL .= ",      TEIJIKAN_GESSYU " . "\r\n";
        //20251125 UPDATE END

        $strSQL .= " FROM  JKJINKENHI JIN " . "\r\n";
        $strSQL .= " LEFT JOIN JKSYAIN SYA " . "\r\n";
        $strSQL .= " ON    SYA.SYAIN_NO = JIN.SYAIN_NO " . "\r\n";
        $strSQL .= " WHERE JIN.TAISYOU_YM = '@TAISYOU_YM' " . "\r\n";
        $strSQL .= " ORDER BY JIN.BUSYO_CD, JIN.SYAIN_NO " . "\r\n";

        $strSQL = str_replace("@TAISYOU_YM", $TAISYOU_YM, $strSQL);

        return $strSQL;
    }

    //人件費データ存在チェック
    private function procJinkenhiExistsSQL($dtpYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT MAX(CASE WHEN JIN.BUSYO_CD IS NULL THEN 1 ELSE 0 END) BUSYO_ERR" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JIN.SYOKUSYU_CD IS NULL AND JIN.KOYOU_KB NOT IN ('07','97') THEN 1 ELSE 0 END) SYOKUSYU_ERR" . "\r\n";
        $strSQL .= ",      MAX(CASE WHEN JIN.BUSYO_CD = '175' AND JIN.SYOKUSYU_CD IN (SELECT SYOKUSYU_CD FROM JKSYOKUSYUKAMOKCNV WHERE KAMOK_CD = '00805') THEN 1 ELSE 0 END) SYOKUSYU_ERR2" . "\r\n";
        $strSQL .= "FROM   JKJINKENHI JIN" . "\r\n";
        $strSQL .= "WHERE  JIN.TAISYOU_YM = '@TAISYOU_YM'" . "\r\n";

        $strSQL = str_replace("@TAISYOU_YM", $dtpYM, $strSQL);

        return $strSQL;
    }

    //システム日付（和暦）取得
    private function Fnc_GetSysDateWarekiSQL($dtpYM)
    {
        $strSQL = "";
        //20210303 CI UPD S
        //$strSQL .= "SELECT JPDATE('@TODAY','1') TODAY_VAL FROM DUAL";
        $strSQL .= "SELECT '@TODAY' TODAY_VAL FROM DUAL";
        //20210303 CI UPD E
        $strSQL = str_replace("@TODAY", $dtpYM . "01", $strSQL);

        return $strSQL;
    }

    //処理年月取得
    public function selShoriYM()
    {
        $strSql = $this->selShoriYMSQL();

        return parent::select($strSql);
    }

    //本部負担金、整備負担金設定
    public function selJinkenhiFutankin($taishoYM)
    {
        $strSql = $this->selJinkenhiFutankinSQL($taishoYM);

        return parent::select($strSql);
    }

    //人件費明細（営業スタッフランキング用）
    public function Fnc_GetSysDateWareki($dtpYM)
    {
        $strSql = $this->Fnc_GetSysDateWarekiSQL($dtpYM);

        return parent::select($strSql);
    }

    //負担金データの削除
    public function delFutankinData($taishoYM)
    {
        $strSql = $this->delFutankinDataSQL($taishoYM);

        return parent::delete($strSql);
    }

    //負担金データの登録
    public function insFutankinData($taishoYM, $HombuFutankin, $SeibiFutankin)
    {
        $strSql = $this->insFutankinDataSQL($taishoYM, $HombuFutankin, $SeibiFutankin);

        return parent::insert($strSql);
    }

    //人件費科目変換データの削除
    public function delKamokuHenkanData($taishoYM)
    {
        $strSql = $this->delKamokuHenkanDataSQL($taishoYM);

        return parent::delete($strSql);
    }

    //★借方データの生成★
    public function insLKamokuHenkanData($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, $KoumkNo)
    {
        $strSql = $this->insLKamokuHenkanDataSQL($TAISYOU_YM, $HombuFutankin, $SeibiFutankin, $KoumkNo);

        return parent::insert($strSql);
    }

    //★貸方データの生成★
    public function insRKamokuHenkanData($TAISYOU_YM, $BusyoCd, $KamokCd, $HimokCD = null)
    {
        $strSql = $this->insRKamokuHenkanDataSQL($TAISYOU_YM, $BusyoCd, $KamokCd, $HimokCD);

        return parent::insert($strSql);
    }

    //人件費科目変換データ取得
    public function selKamokuHenkanData($TAISYOU_YM)
    {
        $strSql = $this->selKamokuHenkanDataSQL($TAISYOU_YM);

        return parent::select($strSql);
    }

    //人件費データ存在チェック
    public function procJinkenhiExists($dtpYM)
    {
        $strSql = $this->procJinkenhiExistsSQL($dtpYM);

        return parent::select($strSql);
    }

    //人件費科目変換データ取得
    public function selJinkenhiMeisaiData($TAISYOU_YM)
    {
        $strSql = $this->selJinkenhiMeisaiDataSQL($TAISYOU_YM);

        return parent::select($strSql);
    }

}
