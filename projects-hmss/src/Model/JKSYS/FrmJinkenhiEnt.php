<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmJinkenhiEnt extends ClsComDb
{
    public $ClsComFncJKSYS;
    public function procGetJinjiCtrlMst_YMSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    SYORI_YM," . "\r\n";
        $strSQL .= "    KAKI_BONUS_MONTH," . "\r\n";
        $strSQL .= "    KAKI_BONUS_START_MT," . "\r\n";
        $strSQL .= "    KAKI_BONUS_END_MT," . "\r\n";
        $strSQL .= "    TOUKI_BONUS_MONTH," . "\r\n";
        $strSQL .= "    TOUKI_BONUS_START_MT," . "\r\n";
        $strSQL .= "    TOUKI_BONUS_END_MT" . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKCONTROLMST " . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    ID = '01'" . "\r\n";

        return $strSQL;
    }

    public function procGetKoyouKbnDataSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    KM.KUBUN_CD," . "\r\n";
        $strSQL .= "    KM.KUBUN_NM" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKKUBUNMST KM" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    KM.USE_STATE_CD = '1'" . "\r\n";
        $strSQL .= "AND" . "\r\n";
        $strSQL .= "    KM.KUBUN_ID = 'KOYOU'" . "\r\n";
        $strSQL .= "ORDER BY" . "\r\n";
        $strSQL .= "    KM.KUBUN_CD" . "\r\n";

        return $strSQL;
    }

    public function procGetSyokusyuDataSQL($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    KM.CODE," . "\r\n";
        $strSQL .= "    KM.MEISYOU" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKCODEMST KM" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    KM.ID = 'SYOKUSYU'" . "\r\n";
        $strSQL .= "AND" . "\r\n";
        $strSQL .= "    (KM.START_DT IS NULL OR" . "\r\n";
        $strSQL .= "     (KM.START_DT IS NOT NULL AND" . "\r\n";
        $strSQL .= "      KM.START_DT <= @REP1))" . "\r\n";
        $strSQL .= "AND" . "\r\n";
        $strSQL .= "    (KM.END_DT IS NULL OR" . "\r\n";
        $strSQL .= "     (KM.END_DT IS NOT NULL AND" . "\r\n";
        $strSQL .= "      KM.END_DT >= @REP1))" . "\r\n";
        $strSQL .= "ORDER BY" . "\r\n";
        $strSQL .= "    KM.CODE" . "\r\n";

        $dtpYM = $dtpYM . '/01';
        $strWkDate = date('Y/m/d', strtotime("$dtpYM +1 month -1 day"));

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlDate($strWkDate . " 00:00:00"), $strSQL);

        return $strSQL;
    }

    public function procGetJinkenhiDataSQL($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    JKJ.BUSYO_CD," . "\r\n";
        $strSQL .= "    BM.BUSYO_NM," . "\r\n";
        $strSQL .= "    JKJ.SYAIN_NO," . "\r\n";
        $strSQL .= "    SM.SYAIN_NM," . "\r\n";
        $strSQL .= "    DECODE(CM.CODE, NULL, NULL, JKJ.SYOKUSYU_CD) AS SYOKUSYU_CD," . "\r\n";
        $strSQL .= "    DECODE(CM.CODE, NULL, NULL, JKJ.SYOKUSYU_CD) AS SYOKUSYU_CODE," . "\r\n";
        $strSQL .= "    DECODE(KOY.KUBUN_CD, NULL, NULL, JKJ.KOYOU_KB) AS KOYOU_KB," . "\r\n";
        $strSQL .= "    JKJ.KIHONKYU," . "\r\n";
        $strSQL .= "    JKJ.TEIJIKAN_GESSYU," . "\r\n";
        $strSQL .= "    JKJ.ZANGYOU_TEATE," . "\r\n";
        $strSQL .= "    JKJ.GYOUSEKI_SYOUREI," . "\r\n";
        $strSQL .= "    JKJ.HOKA_GSK_SYOUREI," . "\r\n";
        $strSQL .= "    JKJ.SONOTA_TEATE," . "\r\n";
        $strSQL .= "    JKJ.KENKO_HKN_RYO," . "\r\n";
        $strSQL .= "    JKJ.KAIGO_HKN_RYO," . "\r\n";
        $strSQL .= "    JKJ.KOUSEINENKIN," . "\r\n";
        $strSQL .= "    JKJ.KOYOU_HKN_RYO," . "\r\n";
        $strSQL .= "    JKJ.ROUSAI_HKN_RYO," . "\r\n";
        $strSQL .= "    JKJ.JIDOUTEATE," . "\r\n";
        $strSQL .= "    JKJ.TAISYOKU_KYUFU," . "\r\n";
        $strSQL .= "    JKJ.BNS_MITUMORI," . "\r\n";
        $strSQL .= "    JKJ.BNS_KENKO_HKN_RYO," . "\r\n";
        $strSQL .= "    JKJ.BNS_KAIGO_HKN_RYO," . "\r\n";
        $strSQL .= "    JKJ.BNS_KOUSEI_NENKIN," . "\r\n";
        $strSQL .= "    JKJ.BNS_JIDOU_TEATE," . "\r\n";
        $strSQL .= "    JKJ.JININ_CNT," . "\r\n";
        $strSQL .= "    TO_CHAR(JKJ.CREATE_DATE,'YYYY/MM/DD HH24:MI:SS') CREATE_DATE," . "\r\n";
        $strSQL .= "    JKJ.CRE_SYA_CD," . "\r\n";
        $strSQL .= "    JKJ.CRE_PRG_ID" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKJINKENHI JKJ" . "\r\n";
        $strSQL .= "    LEFT JOIN JKSYAIN SM" . "\r\n";
        $strSQL .= "        ON JKJ.SYAIN_NO = SM.SYAIN_NO" . "\r\n";
        $strSQL .= "    LEFT JOIN JKBUMON BM" . "\r\n";
        $strSQL .= "        ON JKJ.BUSYO_CD = BM.BUSYO_CD" . "\r\n";

        $strSQL .= "    LEFT JOIN (" . "\r\n";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    KM.CODE," . "\r\n";
        $strSQL .= "    KM.MEISYOU" . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKCODEMST KM" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    KM.ID = 'SYOKUSYU'" . "\r\n";
        $strSQL .= "AND" . "\r\n";
        $strSQL .= "    (KM.START_DT IS NULL OR" . "\r\n";
        $strSQL .= "     (KM.START_DT IS NOT NULL AND" . "\r\n";
        $strSQL .= "      KM.START_DT <= @REP1))" . "\r\n";
        $strSQL .= "AND" . "\r\n";
        $strSQL .= "    (KM.END_DT IS NULL OR" . "\r\n";
        $strSQL .= "     (KM.END_DT IS NOT NULL AND" . "\r\n";
        $strSQL .= "      KM.END_DT >= @REP1))" . "\r\n";
        $strSQL .= "    ) CM" . "\r\n";
        $strSQL .= "        ON JKJ.SYOKUSYU_CD = CM.CODE" . "\r\n";
        $strSQL .= "LEFT JOIN JKKUBUNMST KOY" . "\r\n";
        $strSQL .= "ON        KOY.KUBUN_CD = JKJ.KOYOU_KB" . "\r\n";
        $strSQL .= "AND       KOY.KUBUN_ID = 'KOYOU'" . "\r\n";

        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    JKJ.TAISYOU_YM = @REP2" . "\r\n";

        if ($postData['ddlKoyouKbn'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    JKJ.KOYOU_KB = @REP3" . "\r\n";
        }

        if ($postData['txtBusyoCd'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    JKJ.BUSYO_CD = @REP4" . "\r\n";
        }

        if ($postData['txtSyainNo'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    JKJ.SYAIN_NO = @REP5" . "\r\n";
        }

        $strSQL .= "ORDER BY" . "\r\n";
        $strSQL .= "    JKJ.BUSYO_CD," . "\r\n";
        $strSQL .= "    JKJ.SYAIN_NO" . "\r\n";

        $dtpYM = $postData['dtpYM'] . "/01";
        $strWkDate = date('Y/m/d', strtotime("$dtpYM +1 month -1 day"));

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlDate($strWkDate . " 00:00:00"), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv(str_replace("/", "", $postData['dtpYM'])), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($postData['ddlKoyouKbn']), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($postData['txtBusyoCd']), $strSQL);
        $strSQL = str_replace("@REP5", $this->ClsComFncJKSYS->FncSqlNv($postData['txtSyainNo']), $strSQL);

        return $strSQL;
    }

    public function procGetJinkenhiDataUpdateDateSQL($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "    TO_CHAR(MAX(JKJ.UPD_DATE),'YYYY/MM/DD HH24:MI:SS') AS UPD_DATE " . "\r\n";
        $strSQL .= "FROM" . "\r\n";
        $strSQL .= "    JKJINKENHI JKJ" . "\r\n";

        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    JKJ.TAISYOU_YM = @REP2" . "\r\n";

        if ($postData['ddlKoyouKbn'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    JKJ.KOYOU_KB = @REP3" . "\r\n";
        }

        if ($postData['txtBusyoCd'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    JKJ.BUSYO_CD = @REP4" . "\r\n";
        }

        if ($postData['txtSyainNo'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    JKJ.SYAIN_NO = @REP5" . "\r\n";
        }

        $strSQL .= "ORDER BY" . "\r\n";
        $strSQL .= "    JKJ.BUSYO_CD," . "\r\n";
        $strSQL .= "    JKJ.SYAIN_NO" . "\r\n";

        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv(str_replace("/", "", $postData['dtpYM'])), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($postData['ddlKoyouKbn']), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($postData['txtBusyoCd']), $strSQL);
        $strSQL = str_replace("@REP5", $this->ClsComFncJKSYS->FncSqlNv($postData['txtSyainNo']), $strSQL);

        return $strSQL;
    }

    public function procJinkenhiDataChkSQL($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT SYAIN_NO FROM JKJINKENHI" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    TAISYOU_YM = @REP1" . "\r\n";

        if ($postData['ddlKoyouKbn'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= " NOT (" . "\r\n";
            $strSQL .= "    NVL(KOYOU_KB,'AAA') = @REP2" . "\r\n";
        }

        if ($postData['txtBusyoCd'] != "") {
            if (strpos($strSQL, "AND") != false) {
                $strSQL .= "AND" . "\r\n";
                $strSQL .= "    NVL(BUSYO_CD,'AAAA') = @REP3" . "\r\n";
            } else {
                $strSQL .= "AND" . "\r\n";
                $strSQL .= " NOT (" . "\r\n";
                $strSQL .= "    NVL(BUSYO_CD,'AAAA') = @REP3" . "\r\n";
            }
        }

        if ($postData['txtSyainNo'] != "") {
            if (strpos($strSQL, "AND") != false) {
                $strSQL .= "AND" . "\r\n";
                $strSQL .= "    NVL(SYAIN_NO,'AAAAAA') = @REP4" . "\r\n";
            } else {
                $strSQL .= "AND" . "\r\n";
                $strSQL .= " NOT (" . "\r\n";
                $strSQL .= "    NVL(SYAIN_NO,'AAAAAA') = @REP4" . "\r\n";
            }
        }
        if (strpos($strSQL, "AND") != false) {
            $strSQL .= " )" . "\r\n";
        }

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv(str_replace("/", "", $postData['dtpYM'])), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($postData['ddlKoyouKbn']), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($postData['txtBusyoCd']), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($postData['txtSyainNo']), $strSQL);

        foreach ($postData['Syainarr'] as $key => $value) {
            if ($key == 0) {
                $strSQL .= "AND   SYAIN_NO IN (" . "\r\n";
            } else {
                $strSQL .= "," . "\r\n";
            }

            $strSQL .= $value . "\r\n";
        }
        $strSQL .= ")" . "\r\n";

        return $strSQL;
    }

    public function procDeleteJinkenhiDataSQL($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE FROM JKJINKENHI" . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    TAISYOU_YM = @REP1" . "\r\n";

        if ($postData['ddlKoyouKbn'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    KOYOU_KB = @REP2" . "\r\n";
        }

        if ($postData['txtBusyoCd'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    BUSYO_CD = @REP3" . "\r\n";
        }

        if ($postData['txtSyainNo'] != "") {
            $strSQL .= "AND" . "\r\n";
            $strSQL .= "    SYAIN_NO = @REP4" . "\r\n";
        }

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($postData['dtpYM']), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($postData['ddlKoyouKbn']), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($postData['txtBusyoCd']), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($postData['txtSyainNo']), $strSQL);

        return $strSQL;
    }

    public function procCreateJinkenhiDataSQL($value, $dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKJINKENHI (" . "\r\n";
        $strSQL .= "TAISYOU_YM," . "\r\n";
        $strSQL .= "SYAIN_NO," . "\r\n";
        $strSQL .= "BUSYO_CD," . "\r\n";
        $strSQL .= "SYOKUSYU_CD," . "\r\n";
        $strSQL .= "KOYOU_KB," . "\r\n";
        $strSQL .= "KIHONKYU," . "\r\n";
        $strSQL .= "TEIJIKAN_GESSYU," . "\r\n";
        $strSQL .= "ZANGYOU_TEATE," . "\r\n";
        $strSQL .= "GYOUSEKI_SYOUREI," . "\r\n";
        $strSQL .= "HOKA_GSK_SYOUREI," . "\r\n";
        $strSQL .= "SONOTA_TEATE," . "\r\n";
        $strSQL .= "KENKO_HKN_RYO," . "\r\n";
        $strSQL .= "KAIGO_HKN_RYO," . "\r\n";
        $strSQL .= "KOUSEINENKIN," . "\r\n";
        $strSQL .= "KOYOU_HKN_RYO," . "\r\n";
        $strSQL .= "ROUSAI_HKN_RYO," . "\r\n";
        $strSQL .= "JIDOUTEATE," . "\r\n";
        $strSQL .= "TAISYOKU_KYUFU," . "\r\n";
        $strSQL .= "BNS_MITUMORI," . "\r\n";
        $strSQL .= "BNS_KENKO_HKN_RYO," . "\r\n";
        $strSQL .= "BNS_KAIGO_HKN_RYO," . "\r\n";
        $strSQL .= "BNS_KOUSEI_NENKIN," . "\r\n";
        $strSQL .= "BNS_JIDOU_TEATE," . "\r\n";
        $strSQL .= "JININ_CNT," . "\r\n";
        $strSQL .= "CREATE_DATE," . "\r\n";
        $strSQL .= "CRE_SYA_CD," . "\r\n";
        $strSQL .= "CRE_PRG_ID," . "\r\n";
        $strSQL .= "UPD_DATE," . "\r\n";
        $strSQL .= "UPD_SYA_CD," . "\r\n";
        $strSQL .= "UPD_PRG_ID," . "\r\n";
        $strSQL .= "UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES (" . "\r\n";

        $strSQL .= " @REP01" . "\r\n";
        $strSQL .= ",@REP02" . "\r\n";
        $strSQL .= ",@REP03" . "\r\n";
        $strSQL .= ",@REP04" . "\r\n";
        $strSQL .= ",@REPADD05" . "\r\n";
        $strSQL .= ",@REP05" . "\r\n";
        $strSQL .= ",@REP06" . "\r\n";
        $strSQL .= ",@REP07" . "\r\n";
        $strSQL .= ",@REP08" . "\r\n";
        $strSQL .= ",@REP09" . "\r\n";
        $strSQL .= ",@REP10" . "\r\n";
        $strSQL .= ",@REP11" . "\r\n";
        $strSQL .= ",@REP12" . "\r\n";
        $strSQL .= ",@REP13" . "\r\n";
        $strSQL .= ",@REP14" . "\r\n";
        $strSQL .= ",@REP15" . "\r\n";
        $strSQL .= ",@REP16" . "\r\n";
        $strSQL .= ",@REP17" . "\r\n";
        $strSQL .= ",@REP18" . "\r\n";
        $strSQL .= ",@REP19" . "\r\n";
        $strSQL .= ",@REP20" . "\r\n";
        $strSQL .= ",@REP21" . "\r\n";
        $strSQL .= ",@REP22" . "\r\n";
        $strSQL .= ",@REP23" . "\r\n";
        $strSQL .= ",@REP24" . "\r\n";
        $strSQL .= ",@REP25" . "\r\n";
        $strSQL .= ",@REP26" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",@REP27" . "\r\n";
        $strSQL .= ",'JinkenhiEnt'" . "\r\n";
        $strSQL .= ",@REP28" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@REP01", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace("@REP02", $this->ClsComFncJKSYS->FncSqlNv($value['SYAIN_NO']), $strSQL);
        $strSQL = str_replace("@REP03", $this->ClsComFncJKSYS->FncSqlNv($value['BUSYO_CD']), $strSQL);
        $strSQL = str_replace("@REP04", $this->ClsComFncJKSYS->FncSqlNv($value['SYOKUSYU_CD']), $strSQL);
        $strSQL = str_replace("@REPADD05", $this->ClsComFncJKSYS->FncSqlNv($value['KOYOU_KB']), $strSQL);
        $strSQL = str_replace("@REP05", $this->ClsComFncJKSYS->FncNz($value['KIHONKYU']), $strSQL);
        $strSQL = str_replace("@REP06", $this->ClsComFncJKSYS->FncNz($value['TEIJIKAN_GESSYU']), $strSQL);
        $strSQL = str_replace("@REP07", $this->ClsComFncJKSYS->FncNz($value['ZANGYOU_TEATE']), $strSQL);
        $strSQL = str_replace("@REP08", $this->ClsComFncJKSYS->FncNz($value['GYOUSEKI_SYOUREI']), $strSQL);
        $strSQL = str_replace("@REP09", $this->ClsComFncJKSYS->FncNz($value['HOKA_GSK_SYOUREI']), $strSQL);
        $strSQL = str_replace("@REP10", $this->ClsComFncJKSYS->FncNz($value['SONOTA_TEATE']), $strSQL);
        $strSQL = str_replace("@REP11", $this->ClsComFncJKSYS->FncNz($value['KENKO_HKN_RYO']), $strSQL);
        $strSQL = str_replace("@REP12", $this->ClsComFncJKSYS->FncNz($value['KAIGO_HKN_RYO']), $strSQL);
        $strSQL = str_replace("@REP13", $this->ClsComFncJKSYS->FncNz($value['KOUSEINENKIN']), $strSQL);
        $strSQL = str_replace("@REP14", $this->ClsComFncJKSYS->FncNz($value['KOYOU_HKN_RYO']), $strSQL);
        $strSQL = str_replace("@REP15", $this->ClsComFncJKSYS->FncNz($value['ROUSAI_HKN_RYO']), $strSQL);
        $strSQL = str_replace("@REP16", $this->ClsComFncJKSYS->FncNz($value['JIDOUTEATE']), $strSQL);
        $strSQL = str_replace("@REP17", $this->ClsComFncJKSYS->FncNz($value['TAISYOKU_KYUFU']), $strSQL);
        $strSQL = str_replace("@REP18", $this->ClsComFncJKSYS->FncNz($value['BNS_MITUMORI']), $strSQL);
        $strSQL = str_replace("@REP19", $this->ClsComFncJKSYS->FncNz($value['BNS_KENKO_HKN_RYO']), $strSQL);
        $strSQL = str_replace("@REP20", $this->ClsComFncJKSYS->FncNz($value['BNS_KAIGO_HKN_RYO']), $strSQL);
        $strSQL = str_replace("@REP21", $this->ClsComFncJKSYS->FncNz($value['BNS_KOUSEI_NENKIN']), $strSQL);
        $strSQL = str_replace("@REP22", $this->ClsComFncJKSYS->FncNz($value['BNS_JIDOU_TEATE']), $strSQL);
        $strSQL = str_replace("@REP23", $this->ClsComFncJKSYS->FncNz($value['JININ_CNT']), $strSQL);
        if ($value['CREATE_DATE'] == "") {
            $strSQL = str_replace("@REP24", "SYSDATE", $strSQL);
            $strSQL = str_replace("@REP25", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
            $strSQL = str_replace("@REP26", "'JinkenhiEnt'", $strSQL);
        } else {
            $strSQL = str_replace("@REP24", $this->ClsComFncJKSYS->FncSqlDate($value['CREATE_DATE']), $strSQL);
            $strSQL = str_replace("@REP25", $this->ClsComFncJKSYS->FncSqlNv($value['CRE_SYA_CD']), $strSQL);
            $strSQL = str_replace("@REP26", $this->ClsComFncJKSYS->FncSqlNv($value['CRE_PRG_ID']), $strSQL);
        }
        $strSQL = str_replace("@REP27", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@REP28", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);

        return $strSQL;
    }

    public function FncGetBusyoMstValue()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_NM" . "\r\n";
        $strSQL .= ",      BUSYO_CD" . "\r\n";
        $strSQL .= "FROM   JKBUMON" . "\r\n";

        return parent::select($strSQL);
    }

    public function FncGetSyainMstValue()
    {
        $strSQL = "";

        $strSQL .= "SELECT SYAIN_NM" . "\r\n";
        $strSQL .= ",      SYAIN_NO" . "\r\n";
        $strSQL .= "FROM   JKSYAIN" . "\r\n";

        return parent::select($strSQL);
    }

    //人事コントロールマスタの処理年月取得
    public function procGetJinjiCtrlMst_YM()
    {
        $strSql = $this->procGetJinjiCtrlMst_YMSQL();
        return parent::select($strSql);
    }

    //雇用区分ComboBoxのデータ取得
    public function procGetKoyouKbnData()
    {
        $strSql = $this->procGetKoyouKbnDataSQL();
        return parent::select($strSql);
    }

    //職種ComboBoxのデータ取得
    public function procGetSyokusyuData($dtpYM)
    {
        $strSql = $this->procGetSyokusyuDataSQL($dtpYM);
        return parent::select($strSql);
    }

    //出向者請求明細データの取得
    public function procGetJinkenhiData($postData)
    {
        $strSql = $this->procGetJinkenhiDataSQL($postData);
        return parent::select($strSql);
    }

    //更新日付の取得
    public function procGetJinkenhiDataUpdateDate($postData)
    {
        $strSql = $this->procGetJinkenhiDataUpdateDateSQL($postData);
        return parent::select($strSql);
    }

    //人件費データの存在チェック
    public function procJinkenhiDataChk($postData)
    {
        $strSql = $this->procJinkenhiDataChkSQL($postData);
        return parent::select($strSql);
    }

    //人件費データの削除
    public function procDeleteJinkenhiData($postData)
    {
        $strSql = $this->procDeleteJinkenhiDataSQL($postData);
        return parent::delete($strSql);
    }

    //人件費データの登録
    public function procCreateJinkenhiData($value, $dtpYM)
    {
        $strSql = $this->procCreateJinkenhiDataSQL($value, $dtpYM);
        return parent::insert($strSql);
    }

}
