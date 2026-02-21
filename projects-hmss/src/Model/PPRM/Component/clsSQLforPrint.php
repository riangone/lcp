<?php

/**
 * 説明：
 *
 * システム名　　：ペーパーレスシステム
 * プログラム名　：金種表入力
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package $filename
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */

//'******************************************************************************
//' システム名　　：ペーパーレスシステム
//' プログラム名　：帳票SQL生成関数
//'******************************************************************************
//' VERSION   DATE       BY           CHANGE/COMMENT
//'------------------------------------------------------------------------------
//' V1.0      10-05-12   Noda       　Create
//'******************************************************************************

namespace App\Model\PPRM\Component;

use App\Model\Component\ClsComDb;

class clsSQLforPrint extends ClsComDb
{
    private $strPID = "";
    private $strMNM = "";
    private $strSYD = "";
    private $strLID = "";
    private $strLNM = "";
    private $strHNO = "";
    private $strTCD = "";
    private $strUDT = "";

    private $tof;

    //'**********************************************************************
    //'処 理 名：事務帳票出力初期処理
    //'関 数 名：subJimuInit
    //'引    数：ProgramID ：プログラムID
    //'        ：MachinNM  ：マシン名 (Session("MachinNM"))
    //'        ：SystemDT  ：プレビューボタン押下時の日時 (yyyyMMddhh24mmss)
    //'        ：LoginID   ：ログインID (Session("LoginID"))
    //'        ：SyainNM   ：ログインユーザ名 (Session("SyainNM"))
    //'        ：HijimeNO  ：日締№
    //'戻 り 値：なし
    //'処理説明：初期処理
    //'**********************************************************************

    public function subJimuInit($ProgramID, $MachinNM, $SystemDT, $LoginID, $SyainNM, $HijimeNO)
    {
        $this->strPID = $ProgramID;
        $this->strMNM = $MachinNM;
        $this->strSYD = $SystemDT;
        $this->strLID = $LoginID;
        $this->strLNM = $SyainNM;
        $this->strHNO = $HijimeNO;
        $strSQL = $this->delWkTenpoDenpyo();
        return parent::delete($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：事務帳票出力終了処理
    //'関 数 名：subJimuFinal
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：終了処理
    //'**********************************************************************

    public function subJimuFinal()
    {
        $strSQL = $this->delWkTenpoDenpyo();
        return parent::delete($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：整備帳票出力初期処理
    //'関 数 名：subSeibiInit
    //'引    数：LoginID   ：ログインID (Session("LoginID"))
    //'        ：SyainNM   ：ログインユーザ名 (Session("SyainNM"))
    //'        ：TenpoCD   ：店舗コード
    //'        ：UriageDT  ：売上日
    //'戻 り 値：なし
    //'処理説明：初期処理
    //'**********************************************************************
    public function subSeibiInit($LoginID, $SyainNM, $TenpoCD, $UriageDT)
    {
        $this->strLID = $LoginID;
        $this->strLNM = $SyainNM;
        $this->strTCD = $TenpoCD;
        $this->strUDT = $UriageDT;
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理（全て）登録
    //'関 数 名：insWkAll
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：全ての帳票が出力対象の場合
    //'**********************************************************************
    public function insWkAll()
    {
        $result = $this->insWkF01();
        if (!$result['result']) {
            return $result;
        }
        $result = $this->insWkF03();
        if (!$result['result']) {
            return $result;
        }
        $result = $this->insWkF05();
        if (!$result['result']) {
            return $result;
        }
        $result = $this->insWkF07();
        if (!$result['result']) {
            return $result;
        }
        $result = $this->updWkTenpoDenpyo();
        return $result;
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理（F01,F02）登録
    //'関 数 名：insWkF01
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：現金出納帳、カード伝票、その他伝票が出力対象の場合
    //'**********************************************************************
    public function insWkF01()
    {
        $strSQL = $this->insWkTenpoDenpyo(0, "M41F01", "M41F02");
        return parent::insert($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理（F03,F04）登録
    //'関 数 名：insWkF03
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：現金出納帳、その他伝票が出力対象の場合
    //'**********************************************************************
    public function insWkF03()
    {

        $strSQL = $this->insWkTenpoDenpyo(0, "M41F03", "M41F04");
        return parent::insert($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理（F05,F06）登録
    //'関 数 名：insWkF05
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：仕入伝票が出力対象の場合
    //'**********************************************************************
    public function insWkF05()
    {
        $strSQL = $this->insWkTenpoDenpyo(0, "M41F05", "M41F06");
        return parent::insert($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理（F07,F08）登録
    //'関 数 名：insWkF07
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：振替伝票が出力対象の場合
    //'**********************************************************************
    public function insWkF07()
    {
        $strSQL = $this->insWkTenpoDenpyo(1, "M41F07", "M41F08");
        $result = parent::insert($strSQL);
        if (!$result['result']) {
            return $result;
        }
        $strSQL = $this->insWkTenpoDenpyo(2, "M41F07", "M41F08");
        return parent::insert($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理削除
    //'関 数 名：delWkTenpoDenpyo
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：ワーク伝票管理の削除を行う
    //'**********************************************************************
    public function delWkTenpoDenpyo()
    {
        $strSQL = $this->fncDeleteWkTenpoDenpyoSQL();
        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理登録
    //'関 数 名：insWkTenpoDenpyo
    //'引    数：kbn      ：0（共通）、1（借方）、2（貸方）
    //'        ：         　※振替伝票以外は 0（共通）
    //'        ：strTNM1  ：テーブル名１
    //'        ：strTNM2  ：テーブル名２
    //'戻 り 値：なし
    //'処理説明：ワーク伝票管理の登録を行う
    //'**********************************************************************
    public function insWkTenpoDenpyo($kbn, $strTNM1, $strTNM2)
    {
        $strSQL = $this->fncInsertWkTenpoDenpyoSQL($kbn);
        $strSQL = str_replace("@TABLE_NAME1", $strTNM1, $strSQL);
        $strSQL = str_replace("@TABLE_NAME2", $strTNM2, $strSQL);
        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理更新（限定出力の場合のみ呼び出すこと）
    //'関 数 名：updWkTenpoDenpyo
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：ワーク伝票管理の更新を行う
    //'        ：※全ての帳票を出力する場合は呼ばないでください！！
    //'        ：　（insWkAll()で更新処理を行っています。）
    //'**********************************************************************
    public function updWkTenpoDenpyo()
    {
        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT SCD.SCD_NM FROM M27M14 SCD WHERE WK.SYO_CD_SYS_RIK_KB = SCD.SCD_SYSID AND WK.SYO_CD_GTNI_KUM_ID = SCD.SCD_ID AND WK.KOZ_HSU_VALUE = SCD.SCD_VAL)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    EXISTS (SELECT SCD.SCD_NM FROM M27M14 SCD WHERE WK.SYO_CD_SYS_RIK_KB = SCD.SCD_SYSID AND WK.SYO_CD_GTNI_KUM_ID = SCD.SCD_ID AND WK.KOZ_HSU_VALUE = SCD.SCD_VAL)" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);

        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }

        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT KTN.KYOTN_RKN FROM M27M01 KTN WHERE KTN.HANSH_CD = '3634' AND KTN.ES_KB = 'E' AND KTN.KYOTN_CD = WK.KOZ_HSU_VALUE)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN = '010'" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);

        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }

        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT SYA.SYAIN_KNJ_SEI || ' ' || SYA.SYAIN_KNJ_MEI FROM M29MA4 SYA WHERE SYA.HANSH_CD = '3634' AND SYA.SYAIN_NO = WK.KOZ_HSU_VALUE)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN IN ('360','700','720')" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);

        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }

        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT KYK.INP_SIM1 || KYK.INP_SIM2 FROM M41C01 KYK WHERE KYK.DLRCSRNO = WK.KOZ_HSU_VALUE)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN IN ('320')" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }
        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT SYA.UCOYA_KNM FROM M27M12 SYA WHERE SYA.UCOYA_CD = WK.KOZ_HSU_VALUE)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN IN ('331')" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }
        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT TRI.ATO_DTRPTBNM FROM M28M68 TRI WHERE TRI.ATO_DTRPITCD = WK.KOZ_HSU_VALUE)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN IN ('530')" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }
        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    WK.KOZ_HSU_VALUE = (" . " \r\n";
        $strSQL .= "                            SELECT KMK.KMK_KUM_RKN" . " \r\n";
        $strSQL .= "                            FROM   (" . " \r\n";
        $strSQL .= "                                    SELECT V.TENPO_CD, V.INP_DENPY_NO, V.GYO_NO" . " \r\n";
        $strSQL .= "                                           , MAX(CASE WHEN V.KOBAN = '005' THEN V.KOZ_HSU_VALUE END) KAMOKCD" . " \r\n";
        $strSQL .= "                                           , MAX(CASE WHEN V.KOBAN = '006' THEN V.KOZ_HSU_VALUE END) KOMOKCD" . " \r\n";
        $strSQL .= "                                    FROM   (" . " \r\n";
        $strSQL .= "                                            SELECT DPY.TENPO_CD, DPY.INP_DENPY_NO, DPY.GYO_NO, DPY.KOBAN, DPY.KOZ_HSU_VALUE" . " \r\n";
        $strSQL .= "                                            FROM   WK_TENPO_DENPY DPY" . " \r\n";
        $strSQL .= "                                            WHERE  1 = 1" . " \r\n";
        $strSQL .= "                                            AND    DPY.KOBAN IN ('005')" . " \r\n";
        $strSQL .= "                                            AND    DPY.CRE_SYA_CD = '99999'" . " \r\n";
        $strSQL .= "                                            AND    DPY.CRE_PRG_ID = 'PRG1'" . " \r\n";
        $strSQL .= "                                            AND    DPY.CRE_CLT_NM = 'LBH010'" . " \r\n";
        $strSQL .= "                                            AND    TO_CHAR(DPY.CRE_DATE,'YYYYMMDD') = '20100519'" . " \r\n";
        $strSQL .= "                                            UNION ALL" . " \r\n";
        $strSQL .= "                                            SELECT DPY.TENPO_CD, DPY.INP_DENPY_NO, DPY.GYO_NO, DPY.KOBAN, DPY.KOZ_HSU_VALUE" . " \r\n";
        $strSQL .= "                                            FROM   WK_TENPO_DENPY DPY" . " \r\n";
        $strSQL .= "                                            WHERE  1 = 1" . " \r\n";
        $strSQL .= "                                            AND    DPY.KOBAN IN ('006')" . " \r\n";
        $strSQL .= "                                            AND    DPY.CRE_SYA_CD = '99999'" . " \r\n";
        $strSQL .= "                                            AND    DPY.CRE_PRG_ID = 'PRG1'" . " \r\n";
        $strSQL .= "                                            AND    DPY.CRE_CLT_NM = 'LBH010'" . " \r\n";
        $strSQL .= "                                            AND    TO_CHAR(DPY.CRE_DATE,'YYYYMMDD') = '20100519'" . " \r\n";
        $strSQL .= "                                           ) V" . " \r\n";
        $strSQL .= "                                    GROUP BY V.TENPO_CD, V.INP_DENPY_NO, V.GYO_NO" . " \r\n";
        $strSQL .= "                                     ) INP" . " \r\n";
        $strSQL .= "                            LEFT JOIN M29FZ6 KMK" . " \r\n";
        $strSQL .= "                            ON      KMK.KAMOK_CD = INP.KAMOKCD AND NVL(TRIM(KMK.KOUMK_CD),' ') = NVL(TRIM(INP.KOMOKCD),' ')" . " \r\n";
        $strSQL .= "                            WHERE  INP.INP_DENPY_NO = WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "                            AND    INP.TENPO_CD = WK.TENPO_CD" . " \r\n";
        $strSQL .= "                            AND    INP.TENPO_CD = WK.TENPO_CD" . " \r\n";
        $strSQL .= "                            )" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN IN ('005')" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }

        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT CARD.SCD_NM FROM M28M66 CARD WHERE CARD.SCD_ID = 'CARD' AND LPAD(CARD.SCD_VAL,2,'0') = WK.KOZ_HSU_VALUE)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN IN ('R04')" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }

        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = (SELECT GKO.HNS_BNK_NM || ' ' || GKO.HNS_STN_NM FROM M29FZK GKO WHERE (GKO.BANK_CD || GKO.SITEN_CD) = WK.KOZ_HSU_VALUE)" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.KOBAN IN ('R02')" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $result = parent::update($strSQL);
        if (!$result["result"]) {
            return $result;
        }
        $strSQL = "";
        $strSQL .= "UPDATE WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "SET    KOZ_HSU_NM = '上様'" . " \r\n";
        $strSQL .= "WHERE  1 = 1" . " \r\n";
        $strSQL .= "AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "AND    WK.KOZ_HSU_VALUE = '9999999999'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        return parent::update($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理削除のSQL生成
    //'関 数 名：fncDeleteWkTenpoDenpyoSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：ワーク伝票管理削除のSQLを生成する
    //'**********************************************************************

    public function fncDeleteWkTenpoDenpyoSQL()
    {

        $strSQL = "";
        $strSQL .= "DELETE FROM WK_TENPO_DENPY" . " \r\n";
        $strSQL .= "WHERE CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "AND   CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "AND   CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "AND   CRE_DATE = TO_DATE('@CRE_DATE', 'YYYYMMDDHH24MISS')" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", $this->strSYD, $strSQL);

        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：ワーク伝票管理登録のSQL生成
    //'関 数 名：fncInsertWkTenpoDenpyoSQL
    //'引    数：kbn     ：0（共通）、1（借方）、2（貸方）
    //'戻 り 値：SQL
    //'処理説明：ワーク伝票管理登録のSQLを生成する
    //'**********************************************************************

    public function fncInsertWkTenpoDenpyoSQL($kbn)
    {

        $strSQL = "";
        $strSQL .= "INSERT INTO WK_TENPO_DENPY" . " \r\n";
        $strSQL .= "(      CRE_SYA_CD" . " \r\n";
        $strSQL .= ",      CRE_PRG_ID" . " \r\n";
        $strSQL .= ",      CRE_CLT_NM" . " \r\n";
        $strSQL .= ",      CRE_DATE" . " \r\n";
        $strSQL .= ",      TENPO_CD" . " \r\n";
        $strSQL .= ",      INP_DENPY_NO" . " \r\n";
        $strSQL .= ",      GYO_NO" . " \r\n";
        $strSQL .= ",      TAISK_KB" . " \r\n";
        $strSQL .= ",      KOZ_HSU_KB" . " \r\n";
        $strSQL .= ",      KOZ_HSU_NO" . " \r\n";
        $strSQL .= ",      KAMOK_CD" . " \r\n";
        $strSQL .= ",      KOUMK_CD" . " \r\n";
        $strSQL .= ",      KOBAN" . " \r\n";
        $strSQL .= ",      SYO_CD_SYS_RIK_KB" . " \r\n";
        $strSQL .= ",      SYO_CD_GTNI_KUM_ID" . " \r\n";
        $strSQL .= ",      KOZ_HSU_VALUE" . " \r\n";
        $strSQL .= ",      KOZ_HSU_NM" . " \r\n";
        $strSQL .= ")" . " \r\n";

        for ($i = 1; $i < 6; $i++) {

            $strSQL .= "SELECT '@CRE_SYA_CD'" . " \r\n";
            $strSQL .= ",      '@CRE_PRG_ID'" . " \r\n";
            $strSQL .= ",      '@CRE_CLT_NM'" . " \r\n";
            $strSQL .= ",      TO_DATE('@CRE_DATE', 'YYYYMMDDHH24MISS')" . " \r\n";
            $strSQL .= ",      A.TENPO_CD" . " \r\n";
            $strSQL .= ",      A.INP_DENPY_NO" . " \r\n";
            $strSQL .= ",      B.GYO_NO" . " \r\n";
            if ($kbn == 0) {
                $strSQL .= ",      '0'" . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= ",      '1'" . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= ",      '2'" . " \r\n";
            }
            $strSQL .= ",      '0'" . " \r\n";
            $strSQL .= ",      " . $i . " \r\n";

            if ($kbn == 0) {
                $strSQL .= ",      B.DTL_KMK_CD" . " \r\n";
                $strSQL .= ",      B.DTL_KUM_CD" . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= ",      B.KAR_KMKCD" . " \r\n";
                $strSQL .= ",      B.KAR_KUMCD" . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= ",      B.KAS_KMKCD" . " \r\n";
                $strSQL .= ",      B.KAS_KUMCD" . " \r\n";
            }
            $strSQL .= ",      C.KOZ_KEY" . $i . "_KOBAN" . " \r\n";
            $strSQL .= ",      D.SYO_CD_SYS_RIK_KB" . " \r\n";
            $strSQL .= ",      D.SYO_CD_GTNI_KUM_ID" . " \r\n";

            if ($kbn == 0) {
                $strSQL .= ",      B.DTL_KOZ_KEY" . $i . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= ",      B.KAR_KOZ_KEY" . $i . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= ",      B.KAS_KOZ_KEY" . $i . " \r\n";
            }
            $strSQL .= ",      NULL" . " \r\n";
            $strSQL .= "FROM @TABLE_NAME1 A" . " \r\n";
            $strSQL .= "INNER JOIN @TABLE_NAME2 B ON A.TENPO_CD = B.TENPO_CD AND A.INP_DENPY_NO = B.INP_DENPY_NO" . " \r\n";
            if ($kbn == 0) {
                $strSQL .= "LEFT JOIN M29FZ6 C ON B.DTL_KMK_CD = C.KAMOK_CD AND NVL(TRIM(B.DTL_KUM_CD),' ') = NVL(TRIM(C.KOUMK_CD),' ')" . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= "LEFT JOIN M29FZ6 C ON B.KAR_KMKCD = C.KAMOK_CD AND NVL(TRIM(B.KAR_KUMCD),' ') = NVL(TRIM(C.KOUMK_CD),' ')" . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= "LEFT JOIN M29FZ6 C ON B.KAS_KMKCD = C.KAMOK_CD AND NVL(TRIM(B.KAS_KUMCD),' ') = NVL(TRIM(C.KOUMK_CD),' ')" . " \r\n";
            }
            $strSQL .= "LEFT JOIN M29FZ7 D ON C.KOZ_KEY" . $i . "_KOBAN = D.KOBAN" . " \r\n";
            $strSQL .= "WHERE A.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";
            $strSQL .= "UNION ALL" . " \r\n";

        }

        for ($i = 1; $i < 11; $i++) {

            $strSQL .= "SELECT '@CRE_SYA_CD'" . " \r\n";
            $strSQL .= ",      '@CRE_PRG_ID'" . " \r\n";
            $strSQL .= ",      '@CRE_CLT_NM'" . " \r\n";
            $strSQL .= ",      TO_DATE('@CRE_DATE', 'YYYYMMDDHH24MISS')" . " \r\n";
            $strSQL .= ",      A.TENPO_CD" . " \r\n";
            $strSQL .= ",      A.INP_DENPY_NO" . " \r\n";
            $strSQL .= ",      B.GYO_NO" . " \r\n";

            if ($kbn == 0) {
                $strSQL .= ",      '0'" . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= ",      '1'" . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= ",      '2'" . " \r\n";
            }
            $strSQL .= ",      '1'" . " \r\n";
            $strSQL .= ",      " . $i . " \r\n";
            if ($kbn == 0) {
                $strSQL .= ",      B.DTL_KMK_CD" . " \r\n";
                $strSQL .= ",      B.DTL_KUM_CD" . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= ",      B.KAR_KMKCD" . " \r\n";
                $strSQL .= ",      B.KAR_KUMCD" . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= ",      B.KAS_KMKCD" . " \r\n";
                $strSQL .= ",      B.KAS_KUMCD" . " \r\n";
            }
            $strSQL .= ",      C.HIS_TKY" . $i . "_KOBAN" . " \r\n";
            $strSQL .= ",      D.SYO_CD_SYS_RIK_KB" . " \r\n";
            $strSQL .= ",      D.SYO_CD_GTNI_KUM_ID" . " \r\n";

            if ($kbn == 0) {
                $strSQL .= ",      B.DTL_HSS_OLN" . $i . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= ",      B.KAR_HIS_TKY" . $i . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= ",      B.KAS_HIS_TKY" . $i . " \r\n";
            }
            $strSQL .= ",      NULL" . " \r\n";
            $strSQL .= "FROM @TABLE_NAME1 A" . " \r\n";
            $strSQL .= "INNER JOIN @TABLE_NAME2 B ON A.TENPO_CD = B.TENPO_CD AND A.INP_DENPY_NO = B.INP_DENPY_NO" . " \r\n";
            if ($kbn == 0) {
                $strSQL .= "LEFT JOIN M29FZ6 C ON B.DTL_KMK_CD = C.KAMOK_CD AND NVL(TRIM(B.DTL_KUM_CD),' ') = NVL(TRIM(C.KOUMK_CD),' ')" . " \r\n";
            } elseif ($kbn == 1) {
                $strSQL .= "LEFT JOIN M29FZ6 C ON B.KAR_KMKCD = C.KAMOK_CD AND NVL(TRIM(B.KAR_KUMCD),' ') = NVL(TRIM(C.KOUMK_CD),' ')" . " \r\n";
            } elseif ($kbn == 2) {
                $strSQL .= "LEFT JOIN M29FZ6 C ON B.KAS_KMKCD = C.KAMOK_CD AND NVL(TRIM(B.KAS_KUMCD),' ') = NVL(TRIM(C.KOUMK_CD),' ')" . " \r\n";
            }
            $strSQL .= "LEFT JOIN M29FZ7 D ON C.HIS_TKY" . $i . "_KOBAN = D.KOBAN" . " \r\n";
            $strSQL .= "WHERE A.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";
            if ($i != 10) {
                $strSQL .= "UNION ALL" . " \r\n";

            }

        }
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", $this->strSYD, $strSQL);
        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return $strSQL;
    }

    //'**********************************************************************
    //'処 理 名：日締出力帳票一覧のSQL生成
    //'関 数 名：fncCreatHijimeIchiranSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：日締出力帳票一覧取得SQLを生成する
    //'**********************************************************************
    public function fncCreatHijimeIchiranSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT HJM.TENPO_CD" . " \r\n";
        $strSQL .= ",      TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ",      HJM.TEN_HJM_NO" . " \r\n";
        $strSQL .= ", CASE HJM.HJM_SYR_DTM WHEN NULL THEN '' ELSE SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒' END AS HJM_SYR_DTM " . " \r\n";
        $strSQL .= ",      HJM.EGK_KEJ_KENSU" . " \r\n";
        $strSQL .= ",      HJM.EGK_STA_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.EGK_END_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.KMY_KEJ_KENSU" . " \r\n";
        $strSQL .= ",      HJM.KMY_STA_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.KMY_END_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.CRD_DEN_DTL_KEJ_KENSU" . " \r\n";
        $strSQL .= ",      HJM.CRD_DEN_DTL_STA_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.CRD_DEN_DTL_END_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.SIR_DEN_DTL_KEJ_KENSU" . " \r\n";
        $strSQL .= ",      HJM.SIR_DEN_DTL_STA_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.SIR_DEN_DTL_END_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.FRK_DEN_DTL_KEJ_KENSU" . " \r\n";
        $strSQL .= ",      HJM.FRK_DEN_DTL_STA_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.FRK_DEN_DTL_END_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.ETC_DEN_DTL_KEJ_KENSU" . " \r\n";
        $strSQL .= ",      HJM.ETC_DEN_DTL_STA_DEN_NO" . " \r\n";
        $strSQL .= ",      HJM.ETC_DEN_DTL_END_DEN_NO" . " \r\n";
        $strSQL .= "FROM  M41F11 HJM" . " \r\n";
        $strSQL .= "LEFT  JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT  JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "WHERE HJM.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";

        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_SYA_NM", $this->strLNM, $strSQL);

        return parent::select($strSQL);
    }

    //'**********************************************************************
    //'処 理 名：現金出納帳(営業)金種表のDataSet生成
    //'関 数 名：fncCreatEigyoKinshuDataSet
    //'引    数：clsDB   ：DBコネクタ
    //'戻 り 値：DataSet
    //'処理説明：現金出納帳(営業)金種表DataSetを生成する
    //'**********************************************************************
    public function fncCreatEigyoKinshuDataSet()
    {
        $objds = array();
        $objdt = array();
        $headerds = "";
        $headerdt = array();
        $detailds = array();
        $detaildt = array();

        $dtRowAdd = $this->subSetEigyoKinshuDataTable();

        $detailds = $this->fncCreateigyokinshuDtlSQL();
        if (!$detailds['result']) {
            return $detailds;
        }
        $detaildt = $detailds["data"];
        $dtRow = "";
        if (count((array) $detaildt) > 0) {

            $dtRow = $dtRowAdd;
            $dtRow["KINSYU_SHIHEI"] = "10000";
            $dtRow["KINSYU_KOUKA"] = "500";
            array_push($objdt, $dtRow);
            $dtRow = $dtRowAdd;
            $dtRow["KINSYU_SHIHEI"] = "5000";
            $dtRow["KINSYU_KOUKA"] = "100";
            array_push($objdt, $dtRow);
            $dtRow = $dtRowAdd;
            $dtRow["KINSYU_SHIHEI"] = "2000";
            $dtRow["KINSYU_KOUKA"] = "50";
            array_push($objdt, $dtRow);
            $dtRow = $dtRowAdd;
            $dtRow["KINSYU_SHIHEI"] = "1000";
            $dtRow["KINSYU_KOUKA"] = "10";
            array_push($objdt, $dtRow);
            $dtRow = $dtRowAdd;
            $dtRow["KINSYU_KOUKA"] = "5";
            array_push($objdt, $dtRow);
            $dtRow = $dtRowAdd;
            $dtRow["KINSYU_KOUKA"] = "1";
            array_push($objdt, $dtRow);
            for ($i = 0; $i < count((array) $detaildt); $i++) {
                if ($detaildt[$i]["MNY_KIND"] == "0") {
                    $dtRow = $this->fncGetDataRow($i, $objdt, $detaildt, $dtRowAdd);
                    $dtRow["KINSYU_SHIHEI"] = $detaildt[$i]["KINSYU"];
                    $dtRow["MAISU_SHIHEI"] = $detaildt[$i]["MAISU"];
                    $dtRow["ZANDAKA_SHIHEI"] = $detaildt[$i]["ZANDAKA"];
                    if ($this->tof == true) {
                        $objdt[$detaildt[$i]["MEISAI_NO"] - 1] = $dtRow;
                    } else {
                        array_push($objdt, $dtRow);
                    }
                } elseif ($detaildt[$i]["MNY_KIND"] == "1") {
                    $dtRow = $this->fncGetDataRow($i, $objdt, $detaildt, $dtRowAdd);
                    $dtRow["KINSYU_KOUKA"] = $detaildt[$i]["KINSYU"];
                    $dtRow["MAISU_KOUKA"] = $detaildt[$i]["MAISU"];
                    $dtRow["ZANDAKA_KOUKA"] = $detaildt[$i]["ZANDAKA"];
                    if ($this->tof == true) {
                        $objdt[$detaildt[$i]["MEISAI_NO"] - 1] = $dtRow;
                    } else {
                        array_push($objdt, $dtRow);
                    }
                } elseif ($detaildt[$i]["MNY_KIND"] == "2") {
                    $dtRow = $this->fncGetDataRow($i, $objdt, $detaildt, $dtRowAdd);

                    $dtRow["KINSYU_KOGITTE"] = $detaildt[$i]["KINSYU"];
                    $dtRow["ZANDAKA_KOGITTE"] = $detaildt[$i]["ZANDAKA"];
                    if ($this->tof == true) {
                        $objdt[$detaildt[$i]["MEISAI_NO"] - 1] = $dtRow;
                    } else {
                        array_push($objdt, $dtRow);
                    }

                }

            }

            $headerds = $this->fncCreateigyokinshuHdrSQL();
            $headerdt = $headerds["data"];
            for ($i = 0; $i < count($objdt); $i++) {
                $dtRow = $objdt[$i];
                $dtRow["TENPO_CD"] = isset($headerdt[0]) ? $headerdt[0]["TENPO_CD"] : '';
                $dtRow["TENPO_NM"] = isset($headerdt[0]) ? $headerdt[0]["TENPO_NM"] : '';

                $dtRow["TEN_HJM_NO"] = isset($headerdt[0]) ? $headerdt[0]["TEN_HJM_NO"] : '';
                $dtRow["HJM_SYR_DTM"] = isset($headerdt[0]) ? $headerdt[0]["HJM_SYR_DTM"] : '';
                $dtRow["KON_HJM_EGK_KKS_GK"] = isset($headerdt[0]) ? $headerdt[0]["KON_HJM_EGK_KKS_GK"] : '';
                $objdt[$i] = $dtRow;
            }

        }

        $objds['result'] = true;
        $objds["data"] = $objdt;
        return $objds;
    }

    //**********************************************************************
    //処 理 名：整備日報_諸費用のDataSet生成
    //関 数 名：fncCreatSeibiSyohiyoDataSet
    //戻 り 値：DataSet
    //処理説明：DataSetを生成する
    //**********************************************************************
    public function fncCreatSeibiSyohiyoDataSet($tenpocd, $updstr, $updend)
    {

        $objds = array();
        $objdt = array();
        $dtRow = array();
        $sqlds = array();
        $sqldt = array();

        $dtRowAdd = $this->subSetSeibiSyohiyoDataTable();

        $sqlds = $this->fncCreatSeibiSyohiyoSQL($tenpocd, $updstr, $updend);
        if (!$sqlds['result']) {
            return $sqlds;
        }

        $sqldt = $sqlds["data"];

        if (count((array) $sqldt) > 0) {
            $dtRow = $dtRowAdd;
            $denpyoTtl = 0;
            $kingakuTtl = 0;
            for ($i = 0; $i < count((array) $sqldt); $i++) {

                $denpyoTtl = $denpyoTtl + $sqldt[$i]["DENPYOSU"];
                $kingakuTtl = $kingakuTtl + $sqldt[$i]["KINGAKU"];

                $dtRow["HIYOUMEI" . $i] = $sqldt[$i]["HIYOUMEI"];
                $dtRow["DENPYOSU" . $i] = $sqldt[$i]["DENPYOSU"];
                $dtRow["KINGAKU" . $i] = $sqldt[$i]["KINGAKU"];

            }
            $dtRow["DENPYOSU_TOTAL"] = $denpyoTtl;
            $dtRow["KINGAKU_TOTAL"] = $kingakuTtl;
            array_push($objdt, $dtRow);
        }

        $objds['result'] = true;
        $objds["data"] = $objdt;
        $objds['row'] = $sqlds["row"];

        return $objds;

    }

    //'**********************************************************************
    //'処 理 名：現金出納帳(営業)のSQL生成
    //'関 数 名：fncCreatEigyoGenkinSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：現金出納帳(営業)取得SQLを生成する
    //'**********************************************************************

    public function fncCreatEigyoGenkinSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT TRUNC((ROWNUM - 1) / 8) AS COUNT, A.*" . " \r\n";
        $strSQL .= "FROM" . " \r\n";
        $strSQL .= "(SELECT HJM.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", HJM.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";
        $strSQL .= ", CASE HJM.HJM_SYR_DTM WHEN NULL THEN '' ELSE SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒' END AS HJM_SYR_DTM " . " \r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE, 'YYYY/MM/DD HH24:MI:SS') AS CRE_DTM" . " \r\n";
        $strSQL .= ",      '@CRE_SYA_CD' || '　' || '@CRE_SYA_NM' AS CRE_NM" . " \r\n";
        $strSQL .= ", F01.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F02.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F02.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F02.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F01.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", SUBSTR(F01.KEIJO_DT,1,4) || '/' || SUBSTR(F01.KEIJO_DT,5,2) || '/' || SUBSTR(F01.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", F01.KSM_KOZ_NO AS KSM_KOZ_NO" . " \r\n";
        $strSQL .= ", F02.DTL_HAS_KTN_CD || '　' || KTN.KYOTN_RKN AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ", F01.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F01.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F01.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F01.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F01.KEIJO_KB || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";
        $strSQL .= ", F02.DTL_KMK_CD || '　' || F02.DTL_KUM_CD AS KUM_CD" . " \r\n";
        $strSQL .= ", F02.DTL_KOZ_KEY1 || ' ' || F02.DTL_KOZ_KEY2 || ' ' || F02.DTL_KOZ_KEY3 || ' ' || F02.DTL_KOZ_KEY4 || ' ' || F02.DTL_KOZ_KEY5 AS KOZ_KEY" . " \r\n";
        $strSQL .= ", F02.DTL_HSS_OLN1 || ' ' || F02.DTL_HSS_OLN2 || ' ' || F02.DTL_HSS_OLN3 || ' ' || F02.DTL_HSS_OLN4 || ' ' || F02.DTL_HSS_OLN5 || ' ' || " . " \r\n";
        $strSQL .= "  F02.DTL_HSS_OLN6 || ' ' || F02.DTL_HSS_OLN7 || ' ' || F02.DTL_HSS_OLN8 || ' ' || F02.DTL_HSS_OLN9 || ' ' || F02.DTL_HSS_OLN10 AS HSS_OLN" . " \r\n";
        $strSQL .= ", F02.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", F01.NYUKIN_KB || '　' || NSK.SCD_NM AS NYU_SKN_KB" . " \r\n";
        $strSQL .= ", FZ6.KMK_KUM_RKN AS KMK_KUM_RKN" . " \r\n";
        $strSQL .= ", KH.KOZ_KEY_NM AS KOZ_NM" . " \r\n";
        $strSQL .= ", KH.HIS_OLN_NM AS HIS_NM" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.SYOHY_NO IS NULL THEN F01.RCP_NO ELSE F01.SYOHY_NO END) AS SYOHY_NO" . " \r\n";
        $strSQL .= ", F01.KGT_NO AS KGT_NO" . " \r\n";
        $strSQL .= ", F02.DTL_KAZ_KB AS DTL_KAZ_KB" . " \r\n";
        $strSQL .= ", KZI.SCD_NM AS DTL_KAZ_NM" . " \r\n";
        $strSQL .= ", F02.DTL_TOR_KB AS DTL_TOR_KB" . " \r\n";
        $strSQL .= ", TRI.SCD_NM AS DTL_TOR_NM" . " \r\n";
        $strSQL .= ", F02.DTL_ZRT_KB AS DTL_ZRT_KB" . " \r\n";
        $strSQL .= ", ZRT.SZEI_RT AS DTL_ZRT_NM" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '1' THEN F02.ZEIKM_GK ELSE 0 END) AS KARIKATA" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '9' THEN F02.ZEIKM_GK * -1 ELSE 0 END) AS KASHIKATA" . " \r\n";
        //20170901 lqs UPD S
        // $strSQL .= ", DECODE(F02.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.SHZEI_GK), '999G999'),9) || ')') AS SHZEI_GK" . " \r\n";
        $strSQL .= ", DECODE(F02.SHZEI_GK,0,NULL,NULL,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.SHZEI_GK), '999G999'),9) || ')') AS SHZEI_GK" . " \r\n";
        // $strSQL .= ", DECODE(F02.TES,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.TES), '999G999'),9) || ')') AS TES" . " \r\n";
        $strSQL .= ", DECODE(F02.TES,0,NULL,NULL,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.TES), '999G999'),9) || ')') AS TES" . " \r\n";
        //20170901 lqs UPD E
        $strSQL .= ", HJM.ZEN_HJM_EGK_KKS_GK AS ZEN_HJM_EGK_KKS_GK" . " \r\n";
        $strSQL .= ", HJM.KON_HJM_EGK_KKS_GK AS KON_HJM_EGK_KKS_GK" . " \r\n";
        $strSQL .= "FROM M41F01 F01" . " \r\n";
        $strSQL .= "INNER JOIN M41F02 F02 ON F01.TENPO_CD = F02.TENPO_CD AND F01.INP_DENPY_NO = F02.INP_DENPY_NO" . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F01.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 FZ6 ON F02.DTL_KMK_CD = FZ6.KAMOK_CD AND NVL(TRIM(F02.DTL_KUM_CD),' ') = NVL(TRIM(FZ6.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = F02.DTL_HAS_KTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F01.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F01.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 NSK ON NSK.SCD_VAL = F01.NYUKIN_KB AND NSK.SCD_ID = 'NSKKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KZI ON KZI.SCD_VAL = F02.DTL_KAZ_KB AND KZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 TRI ON TRI.SCD_VAL = F02.DTL_TOR_KB AND TRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 ZRT ON ZRT.SZEI_RT_KB = F02.DTL_ZRT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO" . " \r\n";
        $strSQL .= ") KH ON KH.TENPO_CD = F01.TENPO_CD AND KH.INP_DENPY_NO = F01.INP_DENPY_NO AND KH.GYO_NO = F02.GYO_NO" . " \r\n";
        $strSQL .= "WHERE F01.TEN_HJM_NO = '@TEN_HJM_NO' AND F01.KAMOK_CD = '11111'" . " \r\n";
        $strSQL .= "UNION ALL" . " \r\n";
        $strSQL .= "SELECT HJM.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", HJM.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";
        $strSQL .= ", SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒'AS HJM_SYR_DTM " . " \r\n";

        $strSQL .= ",      TO_CHAR(SYSDATE, 'YYYY/MM/DD HH24:MI:SS') AS CRE_DTM" . " \r\n";
        $strSQL .= ",      '@CRE_SYA_CD' || '　' || '@CRE_SYA_NM' AS CRE_NM" . " \r\n";
        $strSQL .= ", F03.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F04.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F04.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F04.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F03.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", SUBSTR(F03.KEIJO_DT,1,4) || '/' || SUBSTR(F03.KEIJO_DT,5,2) || '/' || SUBSTR(F03.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", NULL AS KSM_KOZ_NO" . " \r\n";
        $strSQL .= ", F04.DTL_HAS_KTN_CD || '　' || KTN.KYOTN_RKN AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ", F03.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F03.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F03.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F03.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F03.KEIJO_KB  || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";
        $strSQL .= ", F04.DTL_KMK_CD || '　' || F04.DTL_KUM_CD AS KUM_CD" . " \r\n";
        $strSQL .= ", F04.DTL_KOZ_KEY1 || ' ' || F04.DTL_KOZ_KEY2 || ' ' || F04.DTL_KOZ_KEY3 || ' ' || F04.DTL_KOZ_KEY4 || ' ' || F04.DTL_KOZ_KEY5 AS KOZ_KEY" . " \r\n";
        $strSQL .= ", F04.DTL_HSS_OLN1 || ' ' || F04.DTL_HSS_OLN2 || ' ' || F04.DTL_HSS_OLN3 || ' ' || F04.DTL_HSS_OLN4 || ' ' || F04.DTL_HSS_OLN5 || ' ' || " . " \r\n";
        $strSQL .= "  F04.DTL_HSS_OLN6 || ' ' || F04.DTL_HSS_OLN7 || ' ' || F04.DTL_HSS_OLN8 || ' ' || F04.DTL_HSS_OLN9 || ' ' || F04.DTL_HSS_OLN10 AS HSS_OLN" . " \r\n";
        $strSQL .= ", F04.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", F03.SKN_KB || '　' || NSK.SCD_NM AS NYU_SKN_KB" . " \r\n";
        $strSQL .= ", FZ6.KMK_KUM_RKN AS KMK_KUM_RKN" . " \r\n";
        $strSQL .= ", KH.KOZ_KEY_NM AS KOZ_NM" . " \r\n";
        $strSQL .= ", KH.HIS_OLN_NM AS HIS_NM" . " \r\n";
        $strSQL .= ", F04.SYOHY_NO AS SYOHY_NO" . " \r\n";
        $strSQL .= ", NULL AS KGT_NO" . " \r\n";
        $strSQL .= ", F04.DTL_KAZ_KB AS DTL_KAZ_KB" . " \r\n";
        $strSQL .= ", KZI.SCD_NM AS DTL_KAZ_NM" . " \r\n";
        $strSQL .= ", F04.DTL_TOR_KB AS DTL_TOR_KB" . " \r\n";
        $strSQL .= ", TRI.SCD_NM AS DTL_TOR_NM" . " \r\n";
        $strSQL .= ", F04.DTL_ZRT_KB AS DTL_ZRT_KB" . " \r\n";
        $strSQL .= ", ZRT.SZEI_RT AS DTL_ZRT_NM" . " \r\n";
        $strSQL .= ", (CASE WHEN F03.KEIJO_KB = '9' THEN F04.ZEIKM_GK * -1 ELSE 0 END) AS KARIKATA" . " \r\n";
        $strSQL .= ", (CASE WHEN F03.KEIJO_KB = '1' THEN F04.ZEIKM_GK ELSE 0 END) AS KASHIKATA" . " \r\n";
        $strSQL .= ", DECODE(F04.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F03.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F04.SHZEI_GK), '999G999'),9) || ')') AS SHZEI_GK" . " \r\n";
        $strSQL .= ", NULL AS TES" . " \r\n";
        $strSQL .= ", HJM.ZEN_HJM_EGK_KKS_GK AS ZEN_HJM_EGK_KKS_GK" . " \r\n";
        $strSQL .= ", HJM.KON_HJM_EGK_KKS_GK AS KON_HJM_EGK_KKS_GK" . " \r\n";
        $strSQL .= "FROM M41F03 F03" . " \r\n";
        $strSQL .= "INNER JOIN M41F04 F04 ON F03.TENPO_CD = F04.TENPO_CD AND F03.INP_DENPY_NO = F04.INP_DENPY_NO " . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F03.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 FZ6 ON F04.DTL_KMK_CD = FZ6.KAMOK_CD AND NVL(TRIM(F04.DTL_KUM_CD),' ') = NVL(TRIM(FZ6.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = F04.DTL_HAS_KTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F03.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F03.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 NSK ON NSK.SCD_VAL = F03.SKN_KB AND NSK.SCD_ID = 'NSKKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KZI ON KZI.SCD_VAL = F04.DTL_KAZ_KB AND KZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 TRI ON TRI.SCD_VAL = F04.DTL_TOR_KB AND TRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 ZRT ON ZRT.SZEI_RT_KB = F04.DTL_ZRT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO" . " \r\n";
        $strSQL .= ") KH ON KH.TENPO_CD = F03.TENPO_CD AND KH.INP_DENPY_NO = F03.INP_DENPY_NO AND KH.GYO_NO = F04.GYO_NO" . " \r\n";
        $strSQL .= "WHERE F03.TEN_HJM_NO = '@TEN_HJM_NO' AND F03.KAMOK_CD = '11111'" . " \r\n";
        $strSQL .= "ORDER BY KEIJO_DT, INP_DENPY_NO, GYO_NO) A" . " \r\n";
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_SYA_NM", $this->strLNM, $strSQL);
        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：カード伝票明細一覧表のSQL生成（カード入金）
    //'関 数 名：fncCreatCardMeisaiNyuSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：カード伝票明細一覧表（カード入金）取得SQLを生成する
    //'**********************************************************************
    public function fncCreatCardMeisaiNyuSQL()
    {
        $strSQL = "";

        $strSQL .= "SELECT TRUNC((ROW_NUMBER() OVER(PARTITION BY F01.CRD_CMP_CD ORDER BY F01.CRD_CMP_CD, F01.KEIJO_DT, F01.INP_DENPY_NO, F02.GYO_NO) - 1) / 8) AS COUNT" . " \r\n";
        $strSQL .= ", HJM.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", HJM.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";
        $strSQL .= ", CASE HJM.HJM_SYR_DTM WHEN NULL THEN '' ELSE SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒' END AS HJM_SYR_DTM " . " \r\n";
        $strSQL .= ", F01.CRD_CMP_CD AS CRD_CMP_CD" . " \r\n";
        $strSQL .= ", '9999' AS BANK_CD" . " \r\n";
        $strSQL .= ", '【 カード入金　' || F01.CRD_CMP_CD || ' ' || CRD.SCD_NM || ' 】' AS BANK_NM_HD" . " \r\n";
        $strSQL .= ", '【 カード入金　' || F01.CRD_CMP_CD || ' ' || CRD.SCD_NM || '　合計 】' AS BANK_NM_FT" . " \r\n";
        $strSQL .= ", F01.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F02.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F02.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F02.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F01.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", F02.DTL_HAS_KTN_CD || '　' || KTN.KYOTN_RKN AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ", F01.SYOHY_NO AS SYOHY_NO" . " \r\n";
        $strSQL .= ", F01.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F01.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F01.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F01.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F01.KEIJO_KB || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";
        $strSQL .= ", SUBSTR(F01.KEIJO_DT,1,4) || '/' || SUBSTR(F01.KEIJO_DT,5,2) || '/' || SUBSTR(F01.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", F02.DTL_KMK_CD || '　' || F02.DTL_KUM_CD AS KUM_CD" . " \r\n";
        $strSQL .= ", FZ6.KMK_KUM_RKN AS KMK_KUM_RKN" . " \r\n";
        $strSQL .= ", (CASE WHEN TRIM(F02.DTL_KOZ_KEY1 || F02.DTL_KOZ_KEY2 || F02.DTL_KOZ_KEY3 || F02.DTL_KOZ_KEY4 || F02.DTL_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN (F02.DTL_HSS_OLN1 || ' ' || F02.DTL_HSS_OLN2 || ' ' || F02.DTL_HSS_OLN3 || ' ' || F02.DTL_HSS_OLN4 || ' ' || F02.DTL_HSS_OLN5 || ' ' || " . " \r\n";
        $strSQL .= "              F02.DTL_HSS_OLN6 || ' ' || F02.DTL_HSS_OLN7 || ' ' || F02.DTL_HSS_OLN8 || ' ' || F02.DTL_HSS_OLN9 || ' ' || F02.DTL_HSS_OLN10)" . " \r\n";
        $strSQL .= "        ELSE (F02.DTL_KOZ_KEY1 || ' ' || F02.DTL_KOZ_KEY2 || ' ' || F02.DTL_KOZ_KEY3 || ' ' || F02.DTL_KOZ_KEY4 || ' ' || F02.DTL_KOZ_KEY5) END) AS KOZ_KEY" . " \r\n";
        $strSQL .= ", (CASE WHEN TRIM(F02.DTL_KOZ_KEY1 || F02.DTL_KOZ_KEY2 || F02.DTL_KOZ_KEY3 || F02.DTL_KOZ_KEY4 || F02.DTL_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN KH.HIS_OLN_NM ELSE KH.KOZ_KEY_NM END) AS KOZ_NM" . " \r\n";
        $strSQL .= ", F02.CRD_SNN_NO AS CRD_SNN_NO" . " \r\n";
        $strSQL .= ", F02.DTL_KAZ_KB AS DTL_KAZ_KB" . " \r\n";
        $strSQL .= ", KZI.SCD_NM AS DTL_KAZ_NM" . " \r\n";
        $strSQL .= ", F02.DTL_TOR_KB AS DTL_TOR_KB" . " \r\n";
        $strSQL .= ", TRI.SCD_NM AS DTL_TOR_NM" . " \r\n";
        $strSQL .= ", F02.DTL_ZRT_KB AS DTL_ZRT_KB" . " \r\n";
        $strSQL .= ", ZRT.SZEI_RT AS DTL_ZRT_NM" . " \r\n";
        $strSQL .= ", SHH.SCD_NM AS CRD_SHH" . " \r\n";
        $strSQL .= ", F02.SKS AS KAISU" . " \r\n";
        $strSQL .= ", F02.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", F02.KOKYK_NO || '　' || OKK.INP_SIM1 || OKK.INP_SIM2 AS OKYAKU" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '1' THEN F02.ZEIKM_GK ELSE 0 END) AS KARIKATA" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '9' THEN F02.ZEIKM_GK * -1 ELSE 0 END) AS KASHIKATA" . " \r\n";
        //20170901 lqs UPD S
        //$strSQL .= ", DECODE(F02.TES,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.TES), '999G999'),9) || ')') AS TES" . " \r\n";
        $strSQL .= ", DECODE(F02.TES,0,NULL,NULL,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.TES), '999G999'),9) || ')') AS TES" . " \r\n";
        //20170901 lqs UPD E
        $strSQL .= "FROM M41F01 F01" . " \r\n";
        $strSQL .= "INNER JOIN M41F02 F02 ON F01.TENPO_CD = F02.TENPO_CD AND F01.INP_DENPY_NO = F02.INP_DENPY_NO" . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F01.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 FZ6 ON F02.DTL_KMK_CD = FZ6.KAMOK_CD AND NVL(TRIM(F02.DTL_KUM_CD),' ') = NVL(TRIM(FZ6.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = F02.DTL_HAS_KTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F01.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M41C01 OKK ON OKK.DLRCSRNO = F02.KOKYK_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M28M66 CRD ON F01.CRD_CMP_CD = LPAD(CRD.SCD_VAL,2,'0') AND CRD.SCD_ID = 'CARD'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F01.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 SHH ON SHH.SCD_VAL = F02.CRD_SHH AND SHH.SCD_ID = 'CARDSHRKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KZI ON KZI.SCD_VAL = F02.DTL_KAZ_KB AND KZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 TRI ON TRI.SCD_VAL = F02.DTL_TOR_KB AND TRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 ZRT ON ZRT.SZEI_RT_KB = F02.DTL_ZRT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO" . " \r\n";
        $strSQL .= ") KH ON KH.TENPO_CD = F01.TENPO_CD AND KH.INP_DENPY_NO = F01.INP_DENPY_NO AND KH.GYO_NO = F02.GYO_NO" . " \r\n";
        $strSQL .= "WHERE F01.TEN_HJM_NO = '@TEN_HJM_NO' AND F01.KAMOK_CD = '11323'" . " \r\n";
        $strSQL .= "ORDER BY CRD_CMP_CD, BANK_CD, KEIJO_DT, INP_DENPY_NO, GYO_NO" . " \r\n";
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：カード伝票明細一覧表のSQL生成（カード振替）
    //'関 数 名：fncCreatCardMeisaiFriSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：カード伝票明細一覧表（カード振替）取得SQLを生成する
    //'**********************************************************************
    public function fncCreatCardMeisaiFriSQL()
    {
        $strSQL = "";

        $strSQL .= "SELECT TRUNC((ROW_NUMBER() OVER(PARTITION BY M67.BANK_CD ORDER BY M67.BANK_CD, F01.KEIJO_DT, F01.INP_DENPY_NO, F02.GYO_NO) - 1) / 8) AS COUNT" . " \r\n";
        $strSQL .= ", HJM.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", HJM.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";
        $strSQL .= ", CASE HJM.HJM_SYR_DTM WHEN NULL THEN '' ELSE SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒' END AS HJM_SYR_DTM " . " \r\n";

        $strSQL .= ", '99' AS CRD_CMP_CD" . " \r\n";
        $strSQL .= ", M67.BANK_CD AS BANK_CD" . " \r\n";
        $strSQL .= ", '【 カード振替　' || M67.BANK_CD || ' ' || M67.SITEN_CD || ' ' || YKS.SCD_NM || ' ' || M67.KOUZA_NO || ' ' || M67.BNK_STN_NM || ' 】' AS BANK_NM_HD" . " \r\n";
        $strSQL .= ", '【 カード振替　' || M67.BANK_CD || ' ' || M67.SITEN_CD || ' ' || YKS.SCD_NM || ' ' || M67.KOUZA_NO || ' ' || M67.BNK_STN_NM || '　合計 】' AS BANK_NM_FT" . " \r\n";
        $strSQL .= ", F01.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F02.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F02.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F02.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F01.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", F02.DTL_HAS_KTN_CD || '　' || KTN.KYOTN_RKN AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ", F01.SYOHY_NO AS SYOHY_NO" . " \r\n";
        $strSQL .= ", F01.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F01.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F01.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F01.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F01.KEIJO_KB || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";
        $strSQL .= ", SUBSTR(F01.KEIJO_DT,1,4) || '/' || SUBSTR(F01.KEIJO_DT,5,2) || '/' || SUBSTR(F01.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", F02.DTL_KMK_CD || '　' || F02.DTL_KUM_CD AS KUM_CD" . " \r\n";
        $strSQL .= ", FZ6.KMK_KUM_RKN AS KMK_KUM_RKN" . " \r\n";
        $strSQL .= ", (CASE WHEN TRIM(F02.DTL_KOZ_KEY1 || F02.DTL_KOZ_KEY2 || F02.DTL_KOZ_KEY3 || F02.DTL_KOZ_KEY4 || F02.DTL_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN (F02.DTL_HSS_OLN1 || ' ' || F02.DTL_HSS_OLN2 || ' ' || F02.DTL_HSS_OLN3 || ' ' || F02.DTL_HSS_OLN4 || ' ' || F02.DTL_HSS_OLN5 || ' ' || " . " \r\n";
        $strSQL .= "              F02.DTL_HSS_OLN6 || ' ' || F02.DTL_HSS_OLN7 || ' ' || F02.DTL_HSS_OLN8 || ' ' || F02.DTL_HSS_OLN9 || ' ' || F02.DTL_HSS_OLN10)" . " \r\n";
        $strSQL .= "        ELSE (F02.DTL_KOZ_KEY1 || ' ' || F02.DTL_KOZ_KEY2 || ' ' || F02.DTL_KOZ_KEY3 || ' ' || F02.DTL_KOZ_KEY4 || ' ' || F02.DTL_KOZ_KEY5) END) AS KOZ_KEY" . " \r\n";
        $strSQL .= ", (CASE WHEN TRIM(F02.DTL_KOZ_KEY1 || F02.DTL_KOZ_KEY2 || F02.DTL_KOZ_KEY3 || F02.DTL_KOZ_KEY4 || F02.DTL_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN KH.HIS_OLN_NM ELSE KH.KOZ_KEY_NM END) AS KOZ_NM" . " \r\n";
        $strSQL .= ", F02.CRD_SNN_NO AS CRD_SNN_NO" . " \r\n";
        $strSQL .= ", F01.CRD_CMP_CD || ' ' || CRD.SCD_NM AS BANK_NM" . " \r\n";
        $strSQL .= ", SHH.SCD_NM AS CRD_SHH" . " \r\n";
        $strSQL .= ", F02.SKS AS KAISU" . " \r\n";
        $strSQL .= ", F02.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", TES.KMK_KUM_RKN AS OKYAKU" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '9' THEN F02.ZEIKM_GK * -1 ELSE 0 END) AS KARIKATA" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '1' THEN F02.ZEIKM_GK ELSE 0 END) AS KASHIKATA" . " \r\n";
        //20170901 lqs UPD S
        //$strSQL .= ", DECODE(F02.TES,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.TES), '999G999'),9) || ')') AS TES" . " \r\n";
        $strSQL .= ", DECODE(F02.TES,0,NULL,NULL,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.TES), '999G999'),9) || ')') AS TES" . " \r\n";
        //20170901 lqs UPD E
        $strSQL .= "FROM M41F01 F01" . " \r\n";
        $strSQL .= "INNER JOIN M41F02 F02 ON F01.TENPO_CD = F02.TENPO_CD AND F01.INP_DENPY_NO = F02.INP_DENPY_NO" . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F01.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 FZ6 ON F02.DTL_KMK_CD = FZ6.KAMOK_CD AND NVL(TRIM(F02.DTL_KUM_CD),' ') = NVL(TRIM(FZ6.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 TES ON F02.TESU_KEI_KAMOKU_CD = TES.KAMOK_CD AND NVL(TRIM(F02.TESU_KEI_KOUMOKU),' ') = NVL(TRIM(TES.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = F02.DTL_HAS_KTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F01.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M41C01 OKK ON OKK.DLRCSRNO = F02.KOKYK_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M28M67 M67 ON M67.NIB_BNK_CD = F01.NIB_BNK_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M28M66 CRD ON F01.CRD_CMP_CD = LPAD(CRD.SCD_VAL,2,'0') AND CRD.SCD_ID = 'CARD'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F01.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 SHH ON SHH.SCD_VAL = F02.CRD_SHH AND SHH.SCD_ID = 'CARDSHRKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 YKS ON YKS.SCD_VAL = M67.YOKIN_SYUMK AND YKS.SCD_ID = 'YOKINSYU'" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO" . " \r\n";
        $strSQL .= ") KH ON KH.TENPO_CD = F01.TENPO_CD AND KH.INP_DENPY_NO = F01.INP_DENPY_NO AND KH.GYO_NO = F02.GYO_NO" . " \r\n";
        $strSQL .= "WHERE F01.TEN_HJM_NO = '@TEN_HJM_NO' AND F02.DTL_KMK_CD = '11323' AND F01.KAMOK_CD = '11122'" . " \r\n";
        $strSQL .= "ORDER BY CRD_CMP_CD, BANK_CD, KEIJO_DT, INP_DENPY_NO, GYO_NO" . " \r\n";
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);

        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：仕入伝票明細一覧表のSQL生成
    //'関 数 名：fncCreatShiireMeisaiSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：仕入伝票明細一覧表取得SQLを生成する
    //'**********************************************************************

    public function fncCreatShiireMeisaiSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT TRUNC((ROW_NUMBER() OVER(ORDER BY F05.INP_DENPY_NO, F06.GYO_NO) - 1) / 8) AS COUNT" . " \r\n";
        $strSQL .= ", F05.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", F05.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";
        $strSQL .= ", CASE HJM.HJM_SYR_DTM WHEN NULL THEN '' ELSE SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒' END AS HJM_SYR_DTM " . " \r\n";

        $strSQL .= ", F05.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F06.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F06.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F06.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F05.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", F06.DTL_HAS_KTN_CD || '　' || KTN.KYOTN_RKN AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ", F05.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F05.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F05.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F05.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F05.KEIJO_KB || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";
        $strSQL .= ", SUBSTR(F05.KEIJO_DT,1,4) || '/' || SUBSTR(F05.KEIJO_DT,5,2) || '/' || SUBSTR(F05.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", F05.TORHK_CD AS TORHK_CD" . " \r\n";
        $strSQL .= ", F06.DTL_KMK_CD || '　' || F06.DTL_KUM_CD AS KUM_CD" . " \r\n";
        $strSQL .= ", F06.DTL_KOZ_KEY1 || ' ' || F06.DTL_KOZ_KEY2 || ' ' || F06.DTL_KOZ_KEY3 || ' ' || F06.DTL_KOZ_KEY4 || ' ' || F06.DTL_KOZ_KEY5 AS KOZ_KEY" . " \r\n";
        $strSQL .= ", F06.DTL_HSS_OLN1 || ' ' || F06.DTL_HSS_OLN2 || ' ' || F06.DTL_HSS_OLN3 || ' ' || F06.DTL_HSS_OLN4 || ' ' || F06.DTL_HSS_OLN5 || ' ' || " . " \r\n";
        $strSQL .= "  F06.DTL_HSS_OLN6 || ' ' || F06.DTL_HSS_OLN7 || ' ' || F06.DTL_HSS_OLN8 || ' ' || F06.DTL_HSS_OLN9 || ' ' || F06.DTL_HSS_OLN10 AS HSS_OLN" . " \r\n";
        $strSQL .= ", F06.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", M68.ATO_DTRPTBNM AS TORHK_NM" . " \r\n";
        $strSQL .= ", FZ6.KMK_KUM_RKN AS KMK_KUM_RKN" . " \r\n";
        $strSQL .= ", KH.KOZ_KEY_NM AS KOZ_NM" . " \r\n";
        $strSQL .= ", KH.HIS_OLN_NM AS HIS_NM" . " \r\n";
        $strSQL .= ", F06.AIT_NOSYO_NO AS NOHIN_NO" . " \r\n";
        $strSQL .= ", SUBSTR(F06.DTL_HAS_DT,3,2) || '/' || SUBSTR(F06.DTL_HAS_DT,5,2) || '/' || SUBSTR(F06.DTL_HAS_DT,7,2) AS SHIIRE_DT" . " \r\n";
        $strSQL .= ", F06.DTL_KAZ_KB AS DTL_KAZ_KB" . " \r\n";
        $strSQL .= ", KZI.SCD_NM AS DTL_KAZ_NM" . " \r\n";
        $strSQL .= ", F06.DTL_TOR_KB AS DTL_TOR_KB" . " \r\n";
        $strSQL .= ", TRI.SCD_NM AS DTL_TOR_NM" . " \r\n";
        $strSQL .= ", F06.DTL_ZRT_KB AS DTL_ZRT_KB" . " \r\n";
        $strSQL .= ", ZRT.SZEI_RT AS DTL_ZRT_NM" . " \r\n";

        $strSQL .= ", (CASE WHEN F06.ZEIKM_GK < 0 THEN F06.ZEIKM_GK * -1 ELSE 0 END) AS KARIKATA" . " \r\n";
        $strSQL .= ", (CASE WHEN F06.ZEIKM_GK > 0 THEN F06.ZEIKM_GK ELSE 0 END) AS KASHIKATA" . " \r\n";
        $strSQL .= ", DECODE(F06.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F06.SHZEI_GK < 0 THEN -1 ELSE 1 END) * F06.SHZEI_GK), '999G999'),9) || ')') AS SHZEI_GK" . " \r\n";
        $strSQL .= "FROM M41F05 F05 " . " \r\n";
        $strSQL .= "INNER JOIN M41F06 F06 ON F05.TENPO_CD = F06.TENPO_CD AND F05.INP_DENPY_NO = F06.INP_DENPY_NO" . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F05.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M28M68 M68 ON M68.ATO_DTRPITCD = F05.TORHK_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 FZ6 ON F06.DTL_KMK_CD = FZ6.KAMOK_CD AND NVL(TRIM(F06.DTL_KUM_CD),' ') = NVL(TRIM(FZ6.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = F05.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = F06.DTL_HAS_KTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F05.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F05.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KZI ON KZI.SCD_VAL = F06.DTL_KAZ_KB AND KZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 TRI ON TRI.SCD_VAL = F06.DTL_TOR_KB AND TRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 ZRT ON ZRT.SZEI_RT_KB = F06.DTL_ZRT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";

        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";

        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO" . " \r\n";
        $strSQL .= ") KH ON KH.TENPO_CD = F05.TENPO_CD AND KH.INP_DENPY_NO = F05.INP_DENPY_NO AND KH.GYO_NO = F06.GYO_NO" . " \r\n";
        $strSQL .= "WHERE F05.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";
        $strSQL .= "ORDER BY F05.INP_DENPY_NO, F06.GYO_NO" . " \r\n";
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);

        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：振替伝票明細一覧表のSQL生成
    //'関 数 名：fncCreatFurikaeMeisaiSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：振替伝票明細一覧表取得SQLを生成する
    //'**********************************************************************

    public function fncCreatFurikaeMeisaiSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT TRUNC((ROW_NUMBER() OVER(ORDER BY F07.INP_DENPY_NO, F08.GYO_NO) - 1) / 6) AS COUNT" . " \r\n";

        $strSQL .= ", F07.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", F07.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";

        $strSQL .= ", CASE HJM.HJM_SYR_DTM WHEN NULL THEN '' ELSE SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒' END AS HJM_SYR_DTM " . " \r\n";

        $strSQL .= ",      TO_CHAR(SYSDATE, 'YYYY/MM/DD HH24:MI:SS') AS CRE_DTM" . " \r\n";
        $strSQL .= ",      '@CRE_SYA_CD' || '　' || '@CRE_SYA_NM' AS CRE_NM" . " \r\n";

        $strSQL .= ", F07.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F08.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F08.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F08.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F07.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", F07.KEIJO_KB || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";

        $strSQL .= ", SUBSTR(F07.KEIJO_DT,1,4) || '/' || SUBSTR(F07.KEIJO_DT,5,2) || '/' || SUBSTR(F07.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", F07.FRK_NYO_KB || '　' || FRK.MEISYOU AS FRKAE_NY" . " \r\n";
        $strSQL .= ", F08.KAR_KMKCD || '　' || F08.KAR_KUMCD || '　' || KAR.KMK_KUM_RKN AS KAR_KUM_CD" . " \r\n";
        $strSQL .= ", (CASE WHEN TRIM(F08.KAR_KOZ_KEY1 || F08.KAR_KOZ_KEY2 || F08.KAR_KOZ_KEY3 || F08.KAR_KOZ_KEY4 || F08.KAR_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN (F08.KAR_HIS_TKY1 || ' ' || F08.KAR_HIS_TKY2 || ' ' || F08.KAR_HIS_TKY3 || ' ' || F08.KAR_HIS_TKY4 || ' ' || F08.KAR_HIS_TKY5 || ' ' || " . " \r\n";
        $strSQL .= "              F08.KAR_HIS_TKY6 || ' ' || F08.KAR_HIS_TKY7 || ' ' || F08.KAR_HIS_TKY8 || ' ' || F08.KAR_HIS_TKY9 || ' ' || F08.KAR_HIS_TKY10)" . " \r\n";
        $strSQL .= "        ELSE (F08.KAR_KOZ_KEY1 || ' ' || F08.KAR_KOZ_KEY2 || ' ' || F08.KAR_KOZ_KEY3 || ' ' || F08.KAR_KOZ_KEY4 || ' ' || F08.KAR_KOZ_KEY5) END) AS KAR_KOZ_KEY" . " \r\n";
        $strSQL .= ", (CASE WHEN TRIM(F08.KAR_KOZ_KEY1 || F08.KAR_KOZ_KEY2 || F08.KAR_KOZ_KEY3 || F08.KAR_KOZ_KEY4 || F08.KAR_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN LKH.HIS_OLN_NM ELSE LKH.KOZ_KEY_NM END) AS KAR_KOZ_NM" . " \r\n";
        $strSQL .= ", F08.KAR_HAS_KTN_CD || '　' || LKTN.KYOTN_RKN AS KAR_HAS_KTN" . " \r\n";
        $strSQL .= ", F08.KAR_KAZ_KB AS KAR_KAZ_KB" . " \r\n";
        $strSQL .= ", LKZI.SCD_NM AS KAR_KAZ_NM" . " \r\n";
        $strSQL .= ", F08.KAR_TOR_KB AS KAR_TOR_KB" . " \r\n";
        $strSQL .= ", LTRI.SCD_NM AS KAR_TOR_NM" . " \r\n";
        $strSQL .= ", F08.KAR_ZEI_RT_KB AS KAR_ZRT_KB" . " \r\n";
        $strSQL .= ", LZRT.SZEI_RT AS KAR_ZRT_NM" . " \r\n";
        $strSQL .= ", F08.ZEIKM_GK AS KAR_ZEIKM_GK" . " \r\n";

        $strSQL .= ", DECODE(F08.KAR_ZEI_RT_KB,NULL,NULL,DECODE(F08.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(F08.SHZEI_GK, '999G999'),9) || ')')) AS KAR_SHZEI_GK" . " \r\n";
        $strSQL .= ", F08.KAS_KMKCD || '　' || F08.KAS_KUMCD || '　' || KAS.KMK_KUM_RKN AS KAS_KUM_CD" . " \r\n";

        $strSQL .= ", (CASE WHEN TRIM(F08.KAS_KOZ_KEY1 || F08.KAS_KOZ_KEY2 || F08.KAS_KOZ_KEY3 || F08.KAS_KOZ_KEY4 || F08.KAS_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN (F08.KAS_HIS_TKY1 || ' ' || F08.KAS_HIS_TKY2 || ' ' || F08.KAS_HIS_TKY3 || ' ' || F08.KAS_HIS_TKY4 || ' ' || F08.KAS_HIS_TKY5 || ' ' || " . " \r\n";
        $strSQL .= "              F08.KAS_HIS_TKY6 || ' ' || F08.KAS_HIS_TKY7 || ' ' || F08.KAS_HIS_TKY8 || ' ' || F08.KAS_HIS_TKY9 || ' ' || F08.KAS_HIS_TKY10)" . " \r\n";
        $strSQL .= "        ELSE (F08.KAS_KOZ_KEY1 || ' ' || F08.KAS_KOZ_KEY2 || ' ' || F08.KAS_KOZ_KEY3 || ' ' || F08.KAS_KOZ_KEY4 || ' ' || F08.KAS_KOZ_KEY5) END) AS KAS_KOZ_KEY" . " \r\n";
        $strSQL .= ", (CASE WHEN TRIM(F08.KAS_KOZ_KEY1 || F08.KAS_KOZ_KEY2 || F08.KAS_KOZ_KEY3 || F08.KAS_KOZ_KEY4 || F08.KAS_KOZ_KEY5) IS NULL" . " \r\n";
        $strSQL .= "        THEN RKH.HIS_OLN_NM ELSE RKH.KOZ_KEY_NM END) AS KAS_KOZ_NM" . " \r\n";
        $strSQL .= ", F08.KAS_HAS_KTN_CD || '　' || RKTN.KYOTN_RKN AS KAS_HAS_KTN" . " \r\n";
        $strSQL .= ", F08.KAS_KAZ_KB AS KAS_KAZ_KB" . " \r\n";
        $strSQL .= ", RKZI.SCD_NM AS KAS_KAZ_NM" . " \r\n";
        $strSQL .= ", F08.KAS_TOR_KB AS KAS_TOR_KB" . " \r\n";
        $strSQL .= ", RTRI.SCD_NM AS KAS_TOR_NM" . " \r\n";
        $strSQL .= ", F08.KAS_ZEI_RT_KB AS KAS_ZRT_KB" . " \r\n";
        $strSQL .= ", RZRT.SZEI_RT AS KAS_ZRT_NM" . " \r\n";
        $strSQL .= ", F08.ZEIKM_GK AS KAS_ZEIKM_GK" . " \r\n";
        $strSQL .= ", DECODE(F08.KAS_ZEI_RT_KB,NULL,NULL,DECODE(F08.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(F08.SHZEI_GK, '999G999'),9) || ')')) AS KAS_SHZEI_GK" . " \r\n";
        $strSQL .= ", F07.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", F07.SYOHY_NO AS SYOHY_NO" . " \r\n";
        $strSQL .= ", F07.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F07.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F07.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F07.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F08.ZEIKM_GK AS KEIJO_GK" . " \r\n";
        $strSQL .= ", DECODE(F08.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(F08.SHZEI_GK, '999G999'),9) || ')') AS SHZEI_GK" . " \r\n";
        $strSQL .= "FROM M41F07 F07 " . " \r\n";
        $strSQL .= "INNER JOIN M41F08 F08 ON F07.TENPO_CD = F08.TENPO_CD AND F07.INP_DENPY_NO = F08.INP_DENPY_NO" . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F07.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAR ON F08.KAR_KMKCD = KAR.KAMOK_CD AND NVL(TRIM(F08.KAR_KUMCD),' ') = NVL(TRIM(KAR.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 KAS ON F08.KAS_KMKCD = KAS.KAMOK_CD AND NVL(TRIM(F08.KAS_KUMCD),' ') = NVL(TRIM(KAS.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = F07.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F07.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN HMEISYOUMST FRK ON FRK.MEISYOU_CD = F07.FRK_NYO_KB AND FRK.MEISYOU_ID = 'FN'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 LKTN ON LKTN.HANSH_CD = '3634' AND LKTN.KYOTN_CD = F08.KAR_HAS_KTN_CD AND LKTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 LKZI ON LKZI.SCD_VAL = F08.KAR_KAZ_KB AND LKZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 LTRI ON LTRI.SCD_VAL = F08.KAR_TOR_KB AND LTRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 LZRT ON LZRT.SZEI_RT_KB = F08.KAR_ZEI_RT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 RKTN ON RKTN.HANSH_CD = '3634' AND RKTN.KYOTN_CD = F08.KAS_HAS_KTN_CD AND RKTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 RKZI ON RKZI.SCD_VAL = F08.KAS_KAZ_KB AND RKZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 RTRI ON RTRI.SCD_VAL = F08.KAS_TOR_KB AND RTRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 RZRT ON RZRT.SZEI_RT_KB = F08.KAS_ZEI_RT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F07.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      WK.TAISK_KB" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO, WK.TAISK_KB" . " \r\n";
        $strSQL .= ") LKH ON LKH.TENPO_CD = F07.TENPO_CD AND LKH.INP_DENPY_NO = F07.INP_DENPY_NO AND LKH.GYO_NO = F08.GYO_NO AND LKH.TAISK_KB = '1'" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      WK.TAISK_KB" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO, WK.TAISK_KB" . " \r\n";
        $strSQL .= ") RKH ON RKH.TENPO_CD = F07.TENPO_CD AND RKH.INP_DENPY_NO = F07.INP_DENPY_NO AND RKH.GYO_NO = F08.GYO_NO AND RKH.TAISK_KB = '2'" . " \r\n";
        $strSQL .= "WHERE F07.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";
        $strSQL .= "ORDER BY F07.INP_DENPY_NO, F08.GYO_NO" . " \r\n";
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);

        $strSQL = str_replace("@CRE_SYA_NM", $this->strLNM, $strSQL);
        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：その他伝票明細一覧表のSQL生成
    //'関 数 名：fncCreatSonotaMeisaiSQL
    //'引    数：なし
    //'戻 り 値：SQL
    //'処理説明：その他伝票明細一覧表取得SQLを生成する
    //'**********************************************************************
    public function fncCreatSonotaMeisaiSQL()
    {

        $strSQL = "";
        $strSQL .= "SELECT HJM.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", HJM.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";
        $strSQL .= ", CASE HJM.HJM_SYR_DTM WHEN NULL THEN '' ELSE SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒' END AS HJM_SYR_DTM " . " \r\n";

        $strSQL .= ",      TO_CHAR(SYSDATE, 'YYYY/MM/DD HH24:MI:SS') AS CRE_DTM" . " \r\n";
        $strSQL .= ",      '@CRE_SYA_CD' || '　' || '@CRE_SYA_NM' AS CRE_NM" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KAMOK_CD = '21222' THEN 1 ELSE 0 END) AS SORT_KEY" . " \r\n";

        $strSQL .= ", (CASE WHEN F01.KAMOK_CD = '21222' THEN '【 仮受金消込 】' ELSE '【 振込　' || BNK.BANK_CD || ' ' || BNK.SITEN_CD || ' ' || YKS.SCD_NM || ' ' || BNK.KOUZA_NO || ' ' || BNK.BNK_STN_NM || ' 】' END) AS BANK_HD" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KAMOK_CD = '21222' THEN '【 仮受金消込　合計 】' ELSE '【 振込　' || BNK.BANK_CD || ' ' || BNK.SITEN_CD || ' ' || YKS.SCD_NM || ' ' || BNK.KOUZA_NO || ' ' || BNK.BNK_STN_NM || '　合計 】' END) AS BANK_FT" . " \r\n";

        $strSQL .= ", F01.KAMOK_CD" . " \r\n";
        $strSQL .= ", F01.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F02.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F02.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F02.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F01.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", F01.KEIJO_KB || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";
        $strSQL .= ", SUBSTR(F01.KEIJO_DT,1,4) || '/' || SUBSTR(F01.KEIJO_DT,5,2) || '/' || SUBSTR(F01.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", F01.NYUKIN_KB || '　' || NSK.SCD_NM AS NYU_SKN_KB" . " \r\n";
        $strSQL .= ", F02.DTL_HAS_KTN_CD || '　' || KTN.KYOTN_RKN AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ", F01.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F01.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F01.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F01.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F02.DTL_KMK_CD || '　' || F02.DTL_KUM_CD AS KUM_CD" . " \r\n";
        $strSQL .= ", FZ6.KMK_KUM_RKN AS KMK_KUM_RKN" . " \r\n";
        $strSQL .= ", F02.DTL_KOZ_KEY1 || ' ' || F02.DTL_KOZ_KEY2 || ' ' || F02.DTL_KOZ_KEY3 || ' ' || F02.DTL_KOZ_KEY4 || ' ' || F02.DTL_KOZ_KEY5 AS KOZ_KEY" . " \r\n";
        $strSQL .= ", F02.DTL_HSS_OLN1 || ' ' || F02.DTL_HSS_OLN2 || ' ' || F02.DTL_HSS_OLN3 || ' ' || F02.DTL_HSS_OLN4 || ' ' || F02.DTL_HSS_OLN5 || ' ' || " . " \r\n";
        $strSQL .= "  F02.DTL_HSS_OLN6 || ' ' || F02.DTL_HSS_OLN7 || ' ' || F02.DTL_HSS_OLN8 || ' ' || F02.DTL_HSS_OLN9 || ' ' || F02.DTL_HSS_OLN10 AS HSS_OLN" . " \r\n";
        $strSQL .= ", KH.KOZ_KEY_NM AS KOZ_NM" . " \r\n";
        $strSQL .= ", KH.HIS_OLN_NM AS HIS_NM" . " \r\n";
        $strSQL .= ", F02.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", F01.TEGAT_NO AS TEGAT_NO" . " \r\n";
        $strSQL .= ", F01.SYOHY_NO AS SYOHY_NO" . " \r\n";
        $strSQL .= ", F01.KGT_NO AS KGT_NO" . " \r\n";
        $strSQL .= ", F01.KSM_KOZ_NO AS KSM_KOZ_NO" . " \r\n";
        $strSQL .= ", F02.DTL_KAZ_KB AS DTL_KAZ_KB" . " \r\n";
        $strSQL .= ", KZI.SCD_NM AS DTL_KAZ_NM" . " \r\n";
        $strSQL .= ", F02.DTL_TOR_KB AS DTL_TOR_KB" . " \r\n";
        $strSQL .= ", TRI.SCD_NM AS DTL_TOR_NM" . " \r\n";
        $strSQL .= ", F02.DTL_ZRT_KB AS DTL_ZRT_KB" . " \r\n";
        $strSQL .= ", ZRT.SZEI_RT AS DTL_ZRT_NM" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '1' THEN F02.ZEIKM_GK ELSE 0 END) AS KARIKATA" . " \r\n";
        $strSQL .= ", (CASE WHEN F01.KEIJO_KB = '9' THEN F02.ZEIKM_GK * -1 ELSE 0 END) AS KASHIKATA" . " \r\n";
        $strSQL .= ", DECODE(F02.TES,0,NULL,F02.TES) AS TES" . " \r\n";
        $strSQL .= ", DECODE(F02.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F01.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F02.SHZEI_GK), '999G999'),9) || ')') AS SHZEI_GK" . " \r\n";
        $strSQL .= "FROM M41F01 F01 " . " \r\n";
        $strSQL .= "INNER JOIN M41F02 F02 ON F01.TENPO_CD = F02.TENPO_CD AND F01.INP_DENPY_NO = F02.INP_DENPY_NO" . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F01.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 FZ6 ON F02.DTL_KMK_CD = FZ6.KAMOK_CD AND NVL(TRIM(F02.DTL_KUM_CD),' ') = NVL(TRIM(FZ6.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = F02.DTL_HAS_KTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F01.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M28M67 BNK ON BNK.NIB_BNK_CD = F01.NIB_BNK_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F01.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 NSK ON NSK.SCD_VAL = F01.NYUKIN_KB AND NSK.SCD_ID = 'NSKKB'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KZI ON KZI.SCD_VAL = F02.DTL_KAZ_KB AND KZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 TRI ON TRI.SCD_VAL = F02.DTL_TOR_KB AND TRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 ZRT ON ZRT.SZEI_RT_KB = F02.DTL_ZRT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 YKS ON YKS.SCD_VAL = BNK.YOKIN_SYUMK AND YKS.SCD_ID = 'YOKINSYU'" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO" . " \r\n";
        $strSQL .= ") KH ON KH.TENPO_CD = F01.TENPO_CD AND KH.INP_DENPY_NO = F01.INP_DENPY_NO AND KH.GYO_NO = F02.GYO_NO" . " \r\n";
        //20170925 lqs UPD S
        //$strSQL .= "WHERE TEN_HJM_NO = '@TEN_HJM_NO' AND F01.KAMOK_CD NOT IN ('11111', '11112', '11323')" . " \r\n";
        $strSQL .= "WHERE HJM.TEN_HJM_NO = '@TEN_HJM_NO' AND F01.KAMOK_CD NOT IN ('11111', '11112', '11323')" . " \r\n";
        //20170925 lqs UPD E
        $strSQL .= "AND NOT (F02.DTL_KMK_CD = '11323' AND F01.KAMOK_CD = '11122')" . " \r\n";
        $strSQL .= "UNION ALL" . " \r\n";
        $strSQL .= "SELECT HJM.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", HJM.TEN_HJM_NO AS TEN_HJM_NO" . " \r\n";
        $strSQL .= ", SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),1,4)||'年'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),6,2)||'月'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),9,2)||'日  '||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),12,2)||'時'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),15,2)||'分'||SUBSTR(TO_CHAR(HJM.HJM_SYR_DTM, 'YYYY/MM/DD HH24:MI:SS'),18,2)||'秒'AS HJM_SYR_DTM " . " \r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE, 'YYYY/MM/DD HH24:MI:SS') AS CRE_DTM" . " \r\n";
        $strSQL .= ",      '@CRE_SYA_CD' || '　' || '@CRE_SYA_NM' AS CRE_NM" . " \r\n";
        $strSQL .= ", 0 AS SORT_KEY" . " \r\n";
        $strSQL .= ", '【 振込　' || BNK.BANK_CD || ' ' || BNK.SITEN_CD || ' ' || YKS.SCD_NM || ' ' || BNK.KOUZA_NO || ' ' || BNK.BNK_STN_NM || ' 】' AS BANK_HD" . " \r\n";
        $strSQL .= ", '【 振込　' || BNK.BANK_CD || ' ' || BNK.SITEN_CD || ' ' || YKS.SCD_NM || ' ' || BNK.KOUZA_NO || ' ' || BNK.BNK_STN_NM || '　合計 】' AS BANK_FT" . " \r\n";
        $strSQL .= ", F03.KAMOK_CD AS KAMOK_CD" . " \r\n";
        $strSQL .= ", F03.INP_DENPY_NO AS INP_DENPY_NO" . " \r\n";
        $strSQL .= ", F04.GYO_NO AS GYO_NO" . " \r\n";
        $strSQL .= ", DECODE(F04.TME_INP_DEN_NO,NULL,NULL,'(' || LPAD(F04.TME_INP_DEN_NO,12) || ')') AS TME_INP_DEN_NO" . " \r\n";
        $strSQL .= ", F03.KAIRK_NO AS KAIRK_NO" . " \r\n";
        $strSQL .= ", F03.KEIJO_KB || '　' || KEI.SCD_NM AS KEIJO_KB" . " \r\n";
        $strSQL .= ", SUBSTR(F03.KEIJO_DT,1,4) || '/' || SUBSTR(F03.KEIJO_DT,5,2) || '/' || SUBSTR(F03.KEIJO_DT,7,2) AS KEIJO_DT" . " \r\n";
        $strSQL .= ", F03.SKN_KB || '　' || NSK.MEISYOU AS NYU_SKN_KB" . " \r\n";
        $strSQL .= ", F04.DTL_HAS_KTN_CD || '　' || KTN.KYOTN_RKN AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ", F03.SSU_INP_TANTO_CD || '　' || SYA.SYAIN_NM AS SSU_INP_TANTO" . " \r\n";
        $strSQL .= ", SUBSTR(F03.SSU_INPUT_DT,1,4) || '年' || SUBSTR(F03.SSU_INPUT_DT,5,2) || '月' || SUBSTR(F03.SSU_INPUT_DT,7,2) || '日' AS SSU_INPUT_DT" . " \r\n";
        $strSQL .= ", F04.DTL_KMK_CD || '　' || F04.DTL_KUM_CD AS KUM_CD" . " \r\n";
        $strSQL .= ", FZ6.KMK_KUM_RKN AS KMK_KUM_RKN" . " \r\n";
        $strSQL .= ", F04.DTL_KOZ_KEY1 || ' ' || F04.DTL_KOZ_KEY2 || ' ' || F04.DTL_KOZ_KEY3 || ' ' || F04.DTL_KOZ_KEY4 || ' ' || F04.DTL_KOZ_KEY5 AS KOZ_KEY" . " \r\n";
        $strSQL .= ", F04.DTL_HSS_OLN1 || ' ' || F04.DTL_HSS_OLN2 || ' ' || F04.DTL_HSS_OLN3 || ' ' || F04.DTL_HSS_OLN4 || ' ' || F04.DTL_HSS_OLN5 || ' ' || " . " \r\n";
        $strSQL .= "  F04.DTL_HSS_OLN6 || ' ' || F04.DTL_HSS_OLN7 || ' ' || F04.DTL_HSS_OLN8 || ' ' || F04.DTL_HSS_OLN9 || ' ' || F04.DTL_HSS_OLN10 AS HSS_OLN" . " \r\n";
        $strSQL .= ", KH.KOZ_KEY_NM AS KOZ_NM" . " \r\n";
        $strSQL .= ", KH.HIS_OLN_NM AS HIS_NM" . " \r\n";
        $strSQL .= ", F04.TEKYO AS TEKYO" . " \r\n";
        $strSQL .= ", NULL AS TEGAT_NO" . " \r\n";
        $strSQL .= ", F04.SYOHY_NO AS SYOHY_NO" . " \r\n";
        $strSQL .= ", NULL AS KGT_NO" . " \r\n";
        $strSQL .= ", NULL AS KSM_KOZ_NO" . " \r\n";
        $strSQL .= ", F04.DTL_KAZ_KB AS DTL_KAZ_KB" . " \r\n";
        $strSQL .= ", KZI.SCD_NM AS DTL_KAZ_NM" . " \r\n";
        $strSQL .= ", F04.DTL_TOR_KB AS DTL_TOR_KB" . " \r\n";
        $strSQL .= ", TRI.SCD_NM AS DTL_TOR_NM" . " \r\n";
        $strSQL .= ", F04.DTL_ZRT_KB AS DTL_ZRT_KB" . " \r\n";
        $strSQL .= ", ZRT.SZEI_RT AS DTL_ZRT_NM" . " \r\n";
        $strSQL .= ", (CASE WHEN F03.KEIJO_KB = '9' THEN F04.ZEIKM_GK * -1 ELSE 0 END) AS KARIKATA" . " \r\n";
        $strSQL .= ", (CASE WHEN F03.KEIJO_KB = '1' THEN F04.ZEIKM_GK ELSE 0 END) AS KASHIKATA" . " \r\n";
        $strSQL .= ", NULL AS TES" . " \r\n";
        $strSQL .= ", DECODE(F04.SHZEI_GK,0,NULL,'(' || LPAD(TO_CHAR(((CASE WHEN F03.KEIJO_KB = '9' THEN -1 ELSE 1 END) * F04.SHZEI_GK), '999G999'),9) || ')') AS SHZEI_GK" . " \r\n";
        $strSQL .= "FROM M41F03 F03" . " \r\n";
        $strSQL .= "INNER JOIN M41F04 F04 ON F03.TENPO_CD = F04.TENPO_CD AND F03.INP_DENPY_NO = F04.INP_DENPY_NO" . " \r\n";
        $strSQL .= "INNER JOIN M41F11 HJM ON HJM.TEN_HJM_NO = F03.TEN_HJM_NO" . " \r\n";
        $strSQL .= "LEFT JOIN M29FZ6 FZ6 ON F04.DTL_KMK_CD = FZ6.KAMOK_CD AND NVL(TRIM(F04.DTL_KUM_CD),' ') = NVL(TRIM(FZ6.KOUMK_CD),' ')" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = F04.DTL_HAS_KTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA ON SYA.SYAIN_NO = F03.SSU_INP_TANTO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M28M67 BNK ON BNK.NIB_BNK_CD = F03.NIB_BNK_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KEI ON KEI.SCD_VAL = F03.KEIJO_KB AND KEI.SCD_ID = 'KEIJOKB'" . " \r\n";
        $strSQL .= "LEFT JOIN HMEISYOUMST NSK ON NSK.MEISYOU_CD = F03.SKN_KB AND NSK.MEISYOU_ID = 'SK'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 KZI ON KZI.SCD_VAL = F04.DTL_KAZ_KB AND KZI.SCD_ID = 'KAZEIKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 TRI ON TRI.SCD_VAL = F04.DTL_TOR_KB AND TRI.SCD_ID = 'TORIHKBRNM'" . " \r\n";
        $strSQL .= "LEFT JOIN M27F09 ZRT ON ZRT.SZEI_RT_KB = F04.DTL_ZRT_KB" . " \r\n";
        $strSQL .= "LEFT JOIN M27M14 YKS ON YKS.SCD_VAL = BNK.YOKIN_SYUMK AND YKS.SCD_ID = 'YOKINSYU'" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT WK.TENPO_CD" . " \r\n";
        $strSQL .= "           ,      WK.INP_DENPY_NO" . " \r\n";
        $strSQL .= "           ,      WK.GYO_NO" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '0' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) KOZ_KEY_NM" . " \r\n";
        $strSQL .= "           ,      MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 1 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 2 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 3 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 4 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 5 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 6 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 7 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 8 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 9 THEN WK.KOZ_HSU_NM END) || ' ' ||" . " \r\n";
        $strSQL .= "                  MAX(CASE WHEN WK.KOZ_HSU_KB = '1' AND WK.KOZ_HSU_NO = 10 THEN WK.KOZ_HSU_NM END) HIS_OLN_NM" . " \r\n";
        $strSQL .= "           FROM   WK_TENPO_DENPY WK" . " \r\n";
        $strSQL .= "           WHERE  1 = 1" . " \r\n";
        $strSQL .= "           AND    WK.CRE_SYA_CD = '@CRE_SYA_CD'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_PRG_ID = '@CRE_PRG_ID'" . " \r\n";
        $strSQL .= "           AND    WK.CRE_CLT_NM = '@CRE_CLT_NM'" . " \r\n";
        $strSQL .= "           AND    TO_CHAR(WK.CRE_DATE,'YYYYMMDD') = '@CRE_DATE'" . " \r\n";
        $strSQL .= "           GROUP BY WK.TENPO_CD, WK.INP_DENPY_NO, WK.GYO_NO" . " \r\n";
        $strSQL .= ") KH ON KH.TENPO_CD = F03.TENPO_CD AND KH.INP_DENPY_NO = F03.INP_DENPY_NO AND KH.GYO_NO = F04.GYO_NO" . " \r\n";
        //20170925 lqs UPD S
        //$strSQL .= "WHERE TEN_HJM_NO = '@TEN_HJM_NO' AND F03.KAMOK_CD NOT IN ('11111', '11112', '11323')" . " \r\n";
        $strSQL .= "WHERE HJM.TEN_HJM_NO = '@TEN_HJM_NO' AND F03.KAMOK_CD NOT IN ('11111', '11112', '11323')" . " \r\n";
        //20170925 lqs UPD E
        $strSQL = str_replace("@CRE_SYA_NM", $this->strLNM, $strSQL);
        $strSQL .= "ORDER BY SORT_KEY, KEIJO_DT, KAMOK_CD, INP_DENPY_NO, GYO_NO" . " \r\n";
        $strSQL = str_replace("@CRE_PRG_ID", $this->strPID, $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->strMNM, $strSQL);
        $strSQL = str_replace("@CRE_DATE", substr($this->strSYD, 0, 8), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：整備日報のSQL生成
    //'関 数 名：fncCreatSeibiNippoSQL
    //'引    数：kbn     ：0（日計）、1（月計）
    //'戻 り 値：String
    //'処理説明：整備日報取得SQLを生成する
    //'**********************************************************************

    public function fncCreatSeibiNippoSQL($kbn)
    {

        $strSQL = "";
        $strSQL .= "SELECT S41.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ", TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ", S41.TEAMCD || '　' || KTN.KYOTN_NM AS DTL_HAS_KTN" . " \r\n";
        //20170809 lqs UPD S
        //$strSQL .= ", SYSDATE AS CRE_DTM" . " \r\n";
        $strSQL .= ", TO_CHAR(SYSDATE, 'YYYY/MM/DD HH24:MI:SS') AS CRE_DTM" . " \r\n";
        //20170809 lqs UPD E
        $strSQL .= ", '@CRE_SYA_CD' || '　' || '@CRE_SYA_NM' AS CRE_NM" . " \r\n";

        if ($kbn == 0) {
            $strSQL .= ", '整備日報（日計）' AS TITLE" . " \r\n";
            $strSQL .= ", '売上日　：　' || TO_CHAR(TO_DATE('@URIAGEDT_END', 'YYYYMMDD'), 'YYYY/MM/DD') AS URI_DTM" . " \r\n";
        } else {
            $strSQL .= ", '整備日報（月計）' AS TITLE" . " \r\n";
            $strSQL .= ", '売上日　：　' || TO_CHAR(TO_DATE('@URIAGEDT_STA', 'YYYYMMDD'), 'YYYY/MM/DD') || '　～　' || TO_CHAR(TO_DATE('@URIAGEDT_END', 'YYYYMMDD'), 'YYYY/MM/DD') AS URI_DTM" . " \r\n";
        }

        $strSQL .= ", '@URIAGEDT_STA' AS URIAGEDT_STA" . " \r\n";
        $strSQL .= ", '@URIAGEDT_END' AS URIAGEDT_END" . " \r\n";
        $strSQL .= "FROM M41S41 S41" . " \r\n";
        $strSQL .= "INNER JOIN M41S30 S30 ON S30.SEB_NOU_NO = S41.SEB_NOU_NO AND S30.DENPYOKB = S41.DENPYOKB" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = S41.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = S41.TEAMCD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "AND   S41.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "AND ROWNUM <= 1" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_SYA_NM", $this->strLNM, $strSQL);

        if ($kbn == 0) {
            $strSQL = str_replace("@URIAGEDT_STA", str_replace("/", "", $this->strUDT), $strSQL);
            $strSQL = str_replace("@URIAGEDT_END", str_replace("/", "", $this->strUDT), $strSQL);
        } else {
            $strSQL = str_replace("@URIAGEDT_STA", substr(str_replace("/", "", $this->strUDT), 0, 6) . "01", $strSQL);
            $strSQL = str_replace("@URIAGEDT_END", str_replace("/", "", $this->strUDT), $strSQL);
        }
        $strSQL = str_replace("@TENPO_CD", $this->strTCD, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：現金出納帳(営業)金種表のDataTable設定
    //'関 数 名：subSetEigyoKinshuDataTable
    //'引 数 　：objdt  ：DataTable
    //'戻 り 値：なし
    //'処理説明：現金出納帳(営業)金種表DataTableを設定する
    //'**********************************************************************

    public function subSetEigyoKinshuDataTable()
    {
        $objdt = array();

        $objdt["KINSYU_SHIHEI"] = "";
        $objdt["MAISU_SHIHEI"] = "";
        $objdt["ZANDAKA_SHIHEI"] = "";
        $objdt["KINSYU_KOUKA"] = "";
        $objdt["MAISU_KOUKA"] = "";
        $objdt["ZANDAKA_KOUKA"] = "";
        $objdt["KINSYU_KOGITTE"] = "";
        $objdt["ZANDAKA_KOGITTE"] = "";
        $objdt["TENPO_CD"] = "";
        $objdt["TENPO_NM"] = "";
        $objdt["TEN_HJM_NO"] = "";
        $objdt["HJM_SYR_DTM"] = "";
        $objdt["KON_HJM_EGK_KKS_GK"] = "";

        return $objdt;
    }

    //'**********************************************************************
    //'処 理 名：整備日報_諸費用のDataTable設定
    //'関 数 名：subSetSeibiSyohiyoDataTable
    //'戻 り 値：なし
    //'処理説明：整備日報_諸費用のDataTable項目を設定する
    //'**********************************************************************

    public function subSetSeibiSyohiyoDataTable()
    {

        $objdt = array();

        $objdt["HIYOUMEI0"] = "";
        $objdt["HIYOUMEI1"] = "";
        $objdt["HIYOUMEI2"] = "";
        $objdt["HIYOUMEI3"] = "";
        $objdt["HIYOUMEI4"] = "";
        $objdt["HIYOUMEI5"] = "";
        $objdt["HIYOUMEI6"] = "";
        $objdt["HIYOUMEI7"] = "";
        $objdt["HIYOUMEI8"] = "";

        $objdt["DENPYOSU0"] = "";
        $objdt["DENPYOSU1"] = "";
        $objdt["DENPYOSU2"] = "";
        $objdt["DENPYOSU3"] = "";
        $objdt["DENPYOSU4"] = "";
        $objdt["DENPYOSU5"] = "";
        $objdt["DENPYOSU6"] = "";
        $objdt["DENPYOSU7"] = "";
        $objdt["DENPYOSU8"] = "";

        $objdt["KINGAKU0"] = "";
        $objdt["KINGAKU1"] = "";
        $objdt["KINGAKU2"] = "";
        $objdt["KINGAKU3"] = "";
        $objdt["KINGAKU4"] = "";
        $objdt["KINGAKU5"] = "";
        $objdt["KINGAKU6"] = "";
        $objdt["KINGAKU7"] = "";
        $objdt["KINGAKU8"] = "";

        $objdt["DENPYOSU_TOTAL"] = "";
        $objdt["KINGAKU_TOTAL"] = "";

        return $objdt;
    }

    //'**********************************************************************
    //'処 理 名：現金出納帳(営業)金種表のヘッダ取得SQL生成
    //'関 数 名：fncCreateigyokinshuHdrSQL
    //'引    数：なし
    //'戻 り 値：String
    //'処理説明：日締NoをもとにSQLを生成する
    //'**********************************************************************

    public function fncCreateigyokinshuHdrSQL()
    {

        $strSQL = "";
        $strSQL .= "SELECT HJM.TENPO_CD" . " \r\n";
        $strSQL .= ",      TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ",      HJM.TEN_HJM_NO" . " \r\n";
        $strSQL .= ",     TO_CHAR(HJM.HJM_SYR_DTM,'YYYY/MM/DD HH24:MI:SS') AS HJM_SYR_DTM" . " \r\n";
        $strSQL .= ",      HJM.KON_HJM_EGK_KKS_GK" . " \r\n";
        $strSQL .= "FROM  M41F11 HJM" . " \r\n";
        $strSQL .= "LEFT  JOIN HBUSYO BUS ON BUS.BUSYO_CD = HJM.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT  JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "WHERE HJM.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_SYA_NM", $this->strLNM, $strSQL);
        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：現金出納帳(営業)金種表のデータ取得SQL生成
    //'関 数 名：fncCreateigyokinshuDtlSQL
    //'引    数：なし
    //'戻 り 値：String
    //'処理説明：日締NoをもとにSQLを生成する
    //'**********************************************************************

    public function fncCreateigyokinshuDtlSQL()
    {

        $strSQL = "";
        $strSQL .= "SELECT DTL.MNY_KIND" . " \r\n";
        $strSQL .= ",      DTL.MEISAI_NO" . " \r\n";
        $strSQL .= ",      DTL.KINSYU" . " \r\n";
        $strSQL .= ",      DTL.MAISU" . " \r\n";
        $strSQL .= ",      DTL.ZANDAKA" . " \r\n";
        $strSQL .= "FROM  PPRHJMMONEYKINDDETAIL DTL" . " \r\n";
        $strSQL .= "WHERE DTL.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";
        $strSQL .= "AND    DTL.EGK_KMY_KBN = '0'" . " \r\n";
        $strSQL .= "ORDER BY DTL.MNY_KIND, DTL.MEISAI_NO" . " \r\n";
        $strSQL = str_replace("@TEN_HJM_NO", $this->strHNO, $strSQL);

        return parent::select($strSQL);

    }

    //'**********************************************************************
    //'処 理 名：現金出納帳(営業)金種表のDataRow取得
    //'関 数 名：fncGetDataRow
    //'戻 り 値：DataRow
    //'処理説明：設定するDataRowを取得します
    //'**********************************************************************

    public function fncGetDataRow($i, $objdt, $detaildt, $dtRowAdd)
    {

        $dtRow = array();

        if (count($objdt) >= $detaildt[$i]["MEISAI_NO"]) {

            $dtRow = $objdt[$detaildt[$i]["MEISAI_NO"] - 1];
            $this->tof = true;

        } else {

            $dtRow = $dtRowAdd;
            $this->tof = false;

        }

        return $dtRow;

    }

    //'2010/06/30 INS START
    //'**********************************************************************
    //'処 理 名：外注検収一覧表
    //'関 数 名：fncGaichuKensyuIchiran
    //'戻 り 値：string
    //'処理説明：外注検収一覧表用のSQLを返す
    //'**********************************************************************

    public function fncGaichuKensyuIchiran()
    {

        $strSQL = "";
        $strSQL .= "SELECT S50.GTU_KEU_NO" . " \r\n";
        $strSQL .= ",      DECODE(S50.KEU_YMD,NULL,NULL,SUBSTR(S50.KEU_YMD,1,4) || '/' || SUBSTR(S50.KEU_YMD,5,2) || '/' || SUBSTR(S50.KEU_YMD,7,2)) KENSYUBI" . " \r\n";
        $strSQL .= ",      S50.HTU_SAI_CD" . " \r\n";
        $strSQL .= ",      M68.ATO_DTRPTBNM" . " \r\n";
        $strSQL .= ",      S50.DAINYKKB" . " \r\n";
        $strSQL .= ",      S02.NYUKOKBNMEI" . " \r\n";
        $strSQL .= ",      M14.SCD_NM RIKUJI_NM" . " \r\n";
        $strSQL .= ",      S50.VCLRGTNO_SYU" . " \r\n";
        $strSQL .= ",      S50.VCLRGTNO_KANA" . " \r\n";
        $strSQL .= ",      S50.VCLRGTNO_REN" . " \r\n";
        $strSQL .= ",      S50.CUS_SIM" . " \r\n";
        $strSQL .= ",      S50.UKK_NO" . " \r\n";
        $strSQL .= ",      DECODE(S50.IRI_YMD,NULL,NULL,SUBSTR(S50.IRI_YMD,1,4) || '/' || SUBSTR(S50.IRI_YMD,5,2) || '/' || SUBSTR(S50.IRI_YMD,7,2)) HACHUBI" . " \r\n";
        $strSQL .= ",      S50.GTU_NOU_NO" . " \r\n";
        $strSQL .= ",      S50.GTU_IRI_SYA_CD" . " \r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . " \r\n";
        $strSQL .= ",      S50.URG_GK_SUM" . " \r\n";
        $strSQL .= ",      S50.HTU_GK_SUM" . " \r\n";
        $strSQL .= ",      S50.KEU_GK_SUM" . " \r\n";
        $strSQL .= ",      S50.GTU_SHZ_GKU" . " \r\n";
        $strSQL .= ",      S50.TOT_SHR_GKU" . " \r\n";
        $strSQL .= ",      DECODE(ROW_NUMBER() OVER(ORDER BY UKK_NO,  S50.GTU_KEU_NO DESC, S50.DENPYOKB ) - RANK() OVER(ORDER BY UKK_NO),0,'*','') MARK" . " \r\n";
        $strSQL .= ",      S50.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ",      TEM.BUSYO_NM AS TENPO_NM" . " \r\n";
        $strSQL .= ",      S50.KYOTN_CD || '　' || KTN.KYOTN_NM AS DTL_HAS_KTN" . " \r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE, 'YYYY/MM/DD HH24:MI:SS') AS CRE_DTM" . " \r\n";
        $strSQL .= ",      '@CRE_SYA_CD' || '　' || '@CRE_SYA_NM' AS CRE_NM" . " \r\n";
        $strSQL .= ", '日締日　:　' || TO_CHAR(TO_DATE('@HJMDT', 'YYYYMMDD'), 'YYYY/MM/DD') AS URI_DTM" . " \r\n";
        $strSQL .= "FROM   M41S50 S50" . " \r\n";
        $strSQL .= "LEFT  JOIN M28S02 S02 ON S50.DAINYKKB = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "LEFT  JOIN M27M14 M14" . " \r\n";
        $strSQL .= "ON    TRIM(M14.SCD_VAL) = TRIM(S50.VCLRGTNO_LAND)" . " \r\n";
        $strSQL .= "AND   M14.SCD_SYSID = 'Z'" . " \r\n";
        $strSQL .= "AND   M14.SCD_ID = 'RIKUJI'" . " \r\n";
        $strSQL .= "LEFT  JOIN M28M68 M68 ON M68.ATO_DTRPITCD = S50.HTU_SAI_CD" . " \r\n";
        $strSQL .= "LEFT  JOIN HSYAINMST SYA ON SYA.SYAIN_NO = S50.GTU_IRI_SYA_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS ON BUS.BUSYO_CD = S50.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN HBUSYO TEM ON TEM.BUSYO_CD = BUS.TENPO_CD" . " \r\n";
        $strSQL .= "LEFT JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = S50.KYOTN_CD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "WHERE  S50.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "AND    S50.HJM_DT = '@HJMDT'" . " \r\n";
        $strSQL .= "ORDER BY S50.UKK_NO,  S50.GTU_KEU_NO, S50.DENPYOKB DESC" . " \r\n";
        $strSQL = str_replace("@TENPO_CD", $this->strTCD, $strSQL);
        $strSQL = str_replace("@HJMDT", str_replace("/", "", $this->strUDT), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->strLID, $strSQL);
        $strSQL = str_replace("@CRE_SYA_NM", $this->strLNM, $strSQL);

        return parent::select($strSQL);

    }

    //'2017/08/07 INS START
    //'**********************************************************************
    //'処 理 名：売上明細のSQL生成++
    //'関 数 名：fncCreatUriMeisaiSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************
    public function fncCreatUriMeisaiSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT V.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ",      V.YUMUKBN AS YUMUKBN" . " \r\n";
        $strSQL .= ",      V.整備納品書NO || V.伝票区分 AS SEB_NOU_NO_KEY" . " \r\n";
        $strSQL .= ",      V.整備納品書NO AS SEB_NOU_NO" . " \r\n";
        $strSQL .= ",      V.旧整備納品書NO AS OLD_SEB_NOU_NO" . " \r\n";
        $strSQL .= ",      V.受付NO AS UKK_NO" . " \r\n";
        $strSQL .= ",      V.売上日 AS URIAGEDT" . " \r\n";
        $strSQL .= ",      V.請求先CD AS SEIKYU_CD" . " \r\n";
        $strSQL .= ",      V.請求先名 AS SEIKYU_NM" . " \r\n";
        $strSQL .= ",      V.RIKUJI_NM AS RIKUJI_NM" . " \r\n";
        $strSQL .= ",      V.VCLRGTNO_SYU AS VCLRGTNO_SYU" . " \r\n";
        $strSQL .= ",      V.VCLRGTNO_KANA AS VCLRGTNO_KANA" . " \r\n";
        $strSQL .= ",      V.VCLRGTNO_REN AS VCLRGTNO_REN" . " \r\n";
        $strSQL .= ",      V.NYUKOKBN AS NYUKOKBN" . " \r\n";
        $strSQL .= ",      V.NYUKOKBNMEI AS NYUKOKBNMEI" . " \r\n";
        $strSQL .= ",      V.台数 AS DAISU" . " \r\n";
        $strSQL .= ",      V.工賃売上 AS KOC_URI" . " \r\n";
        $strSQL .= ",      V.部品売上 AS BUH_URI" . " \r\n";
        $strSQL .= ",      V.部品整備合計 AS BUH_SEB_TTL" . " \r\n";
        $strSQL .= ",      V.外注売上 AS GAC_URI" . " \r\n";
        $strSQL .= ",      V.売上合計 AS URI_TTL" . " \r\n";
        $strSQL .= ",      V.工賃原価 AS KOC_GEN" . " \r\n";
        $strSQL .= ",      V.部品原価 AS BUH_GEN" . " \r\n";
        $strSQL .= ",      V.諸費用計 AS SYH_TTL" . " \r\n";
        $strSQL .= ",      V.外注原価 AS GAC_GEN" . " \r\n";
        $strSQL .= ",      V.合計金額 AS TTL_GKU" . " \r\n";
        $strSQL .= ",      V.内消費税額 AS SHZ_GKU" . " \r\n";
        $strSQL .= ",      V.原価合計 AS GEN_TTL" . " \r\n";
        $strSQL .= ",      (V.売上合計 - V.原価合計) AS ARARI_TTL" . " \r\n";
        $strSQL .= ",      V.値引合計 AS NEB_TTL" . " \r\n";
        $strSQL .= ",      DECODE(V.部品売上,0,0,ROUND(((V.部品売上 - V.部品原価) / V.部品売上) * 100,1)) AS BUH_PRF" . " \r\n";
        $strSQL .= ",      DECODE(V.外注売上,0,0,ROUND(((V.外注売上 - V.外注原価) / V.外注売上) * 100,1)) AS GAC_PRF" . " \r\n";
        $strSQL .= ",       (CASE WHEN V.OLD_KEJ_DT IS NOT NULL THEN (CASE WHEN SUBSTR(V.受付NO,4,3) = SUBSTR(V.CHKURIAGE,4,3) THEN 0 ELSE -1 END) ELSE 1 END) HENKOUKB" . " \r\n";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY V.整備納品書NO, V.伝票区分) - RANK() OVER(ORDER BY V.整備納品書NO, V.伝票区分) DENPYONO" . " \r\n";
        $strSQL .= "        ,      DPYTBL.DENPYOSU" . " \r\n";
        $strSQL .= "FROM   (" . " \r\n";
        $strSQL .= "        SELECT S41.TENPO_CD" . " \r\n";
        $strSQL .= "        ,    S02.YUMUKBN" . " \r\n";
        $strSQL .= "        ,    S30.SEB_NOU_NO 整備納品書NO" . " \r\n";
        $strSQL .= "        ,    S30.DENPYOKB 伝票区分" . " \r\n";
        $strSQL .= "        ,    S30.ADN_OLD_SEB_NOU_NO 旧整備納品書NO" . " \r\n";
        $strSQL .= "        ,    S30.UKK_NO 受付NO" . " \r\n";
        $strSQL .= "        ,    SUBSTR(S30.URIAGEDT,1,4) || '/' || SUBSTR(S30.URIAGEDT,5,2) || '/' || SUBSTR(S30.URIAGEDT,7,2) 売上日" . " \r\n";
        $strSQL .= "		   ,    S30.URIAGEDT CHKURIAGE" . " \r\n";
        $strSQL .= "        ,    S30.VCLOCRNO 請求先CD" . " \r\n";
        $strSQL .= "        ,    S30.CLM_NM1 || '　' || S30.CLM_NM2 請求先名" . " \r\n";
        $strSQL .= "        ,    M14.SCD_NM RIKUJI_NM" . " \r\n";
        $strSQL .= "        ,    S30.VCLRGTNO_SYU" . " \r\n";
        $strSQL .= "        ,    S30.VCLRGTNO_KANA" . " \r\n";
        $strSQL .= "        ,    S30.VCLRGTNO_REN" . " \r\n";
        $strSQL .= "        ,    S41.NYUKOKBN" . " \r\n";
        $strSQL .= "        ,    S02.NYUKOKBNMEI" . " \r\n";
        $strSQL .= "        ,    S41.DSU_CNT 台数" . " \r\n";
        $strSQL .= "        ,    S41.NAI_GJT_RYO_URG + S41.IRI_GJT_RYO_URG + S41.UKO_GJT_RYO_URG 工賃売上" . " \r\n";
        $strSQL .= "        ,    S41.NAI_PAR_URG + S41.IRI_PAR_URG + S41.UKO_PAR_URG 部品売上" . " \r\n";
        $strSQL .= "        ,    S40.PAR_SEB_URG_SUM_GKU 部品整備合計" . " \r\n";
        $strSQL .= "        ,    S41.GTU_GJT_RYO_URG + S41.GTU_PAR_URG + S41.GTU_IRI_GJT_RYO_URG + S41.GTU_IRI_PAR_URG + S41.GTU_UKO_GJT_RYO_URG + S41.GTU_UKO_PAR_URG 外注売上" . " \r\n";
        $strSQL .= "        ,    S41.NAI_GJT_RYO_URG + S41.NAI_PAR_URG + S41.IRI_GJT_RYO_URG + S41.IRI_PAR_URG + S41.UKO_GJT_RYO_URG + S41.UKO_PAR_URG + S41.GTU_GJT_RYO_URG + S41.GTU_PAR_URG + S41.GTU_IRI_GJT_RYO_URG + S41.GTU_IRI_PAR_URG + S41.GTU_UKO_GJT_RYO_URG + S41.GTU_UKO_PAR_URG 売上合計" . " \r\n";
        $strSQL .= "        ,    S41.NAI_GJT_RYO_PCS + S41.IRI_GJT_RYO_PCS + S41.UKO_GJT_RYO_PCS 工賃原価" . " \r\n";
        $strSQL .= "        ,    S41.NAI_PAR_PCS + S41.IRI_PAR_PCS + S41.UKO_PAR_PCS 部品原価" . " \r\n";
        $strSQL .= "        ,    S40.SCO_SUM_GKU 諸費用計" . " \r\n";
        $strSQL .= "        ,    S41.GTU_GJT_RYO_PCS + S41.GE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_PCS + S41.GTU_IRI_PAR_PCS + S41.GTU_UKO_GJT_RYO_PCS + S41.GTU_UKO_PAR_PCS 外注原価" . " \r\n";
        $strSQL .= "        ,    S40.TGK_SUM 合計金額" . " \r\n";
        $strSQL .= "        ,    S40.SUM_SHZ 内消費税額" . " \r\n";
        $strSQL .= "        ,    S41.NAI_GJT_RYO_PCS + S41.NAI_PAR_PCS + S41.IRI_GJT_RYO_PCS + S41.IRI_PAR_PCS + S41.UKO_GJT_RYO_PCS + S41.UKO_PAR_PCS + S41.GTU_GJT_RYO_PCS + S41.GE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_PCS + S41.GTU_IRI_PAR_PCS + S41.GTU_UKO_GJT_RYO_PCS + S41.GTU_UKO_PAR_PCS 原価合計" . " \r\n";
        $strSQL .= "        ,    S41.NAI_GJT_RYO_NBK_GKU + S41.NAI_PAR_NBK_GKU + S41.IRI_GJT_RYO_NBK_GKU + S41.IRI_PAR_NBK_GKU + S41.UKO_GJT_RYO_NBK_GKU + S41.UKO_PAR_NBK_GKU + S41.GTU_GJT_RYO_NBK_GKU + S41.NE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_NBK_GKU + S41.GTU_IRI_PAR_NBK_GKU + S41.GTU_UKO_GJT_RYO_NBK_GKU + S41.GTU_UKO_PAR_NBK_GKU 値引合計" . " \r\n";

        $strSQL .= "        ,    S30.OLD_KEJ_DT" . " \r\n";
        $strSQL .= "        ,    (CASE WHEN S30.DAINYKKB = S41.NYUKOKBN THEN '1' ELSE '2' END) SORTNYUKO" . " \r\n";
        $strSQL .= "        FROM  M41S30 S30" . " \r\n";
        $strSQL .= "        INNER JOIN M41S40 S40 ON S40.SEB_NOU_NO = S30.SEB_NOU_NO AND S40.DENPYOKB = S30.DENPYOKB" . " \r\n";
        $strSQL .= "        INNER JOIN M41S41 S41 ON S30.SEB_NOU_NO = S41.SEB_NOU_NO AND S30.DENPYOKB = S41.DENPYOKB" . " \r\n";

        $strSQL .= "        LEFT JOIN M41S44 S44 ON S44.UKK_NO = S30.UKK_NO AND S44.S_TANTOCD = S30.S_TANTOCD" . " \r\n";

        $strSQL .= "        LEFT  JOIN M28S02 S02 ON S41.NYUKOKBN = S02.NYUKOKBN" . " \r\n";

        $strSQL .= "        LEFT  JOIN M27M14 M14 ON TRIM(M14.SCD_VAL) = TRIM(S30.VCLRGTNO_LAND) AND SCD_SYSID = 'Z' AND SCD_ID = 'RIKUJI'" . " \r\n";

        $strSQL .= "        LEFT  JOIN M27M01 KTN ON KTN.HANSH_CD = '3634' AND KTN.KYOTN_CD = S41.TEAMCD AND KTN.ES_KB = 'E'" . " \r\n";
        $strSQL .= "        WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "        AND   S41.TENPO_CD = '@TENPO_CD'" . " \r\n";

        $strSQL .= "               ) V" . " \r\n";

        $strSQL .= "		INNER JOIN" . " \r\n";
        $strSQL .= "			(SELECT SUM(CASE WHEN S30.DENPYOKB = '1' THEN 1 WHEN S30.DENPYOKB = '2' THEN -1 WHEN S30.DENPYOKB = '3' THEN 1 END) DENPYOSU " . " \r\n";
        $strSQL .= "			 FROM M41S30 S30" . " \r\n";
        $strSQL .= "          INNER JOIN M41S40 S40 ON S40.SEB_NOU_NO = S30.SEB_NOU_NO AND S40.DENPYOKB = S30.DENPYOKB" . " \r\n";
        $strSQL .= "          WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END' AND S30.TENPO_CD = '@TENPO_CD') DPYTBL" . " \r\n";
        $strSQL .= "		ON 1 = 1" . " \r\n";

        $strSQL .= "        ORDER BY V.整備納品書NO,V.伝票区分,V.SORTNYUKO,V.NYUKOKBN" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);

    }

    //'2017/08/07 INS START
    // '**********************************************************************
    //'処 理 名：諸費用明細のSQL生成
    //'関 数 名：fncCreatUriSyohiyoSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************

    public function fncCreatUriSyohiyoSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT BASE.TENPO_CD" . " \r\n";
        $strSQL .= ",      BASE.SEB_NOU_NO" . " \r\n";
        $strSQL .= ",      BASE.OLD_SEB_NOU_NO" . " \r\n";
        $strSQL .= ",      BASE.UKK_NO" . " \r\n";
        $strSQL .= ",      BASE.URIAGEDT" . " \r\n";
        $strSQL .= ",      BASE.SEIKYU_CD" . " \r\n";
        $strSQL .= ",      BASE.SEIKYU_NM" . " \r\n";
        $strSQL .= ",      BASE.RIKUJI_NM" . " \r\n";
        $strSQL .= ",      BASE.VCLRGTNO_SYU" . " \r\n";
        $strSQL .= ",      BASE.VCLRGTNO_KANA" . " \r\n";
        $strSQL .= ",      BASE.VCLRGTNO_REN" . " \r\n";
        $strSQL .= ",      BASE.HIYOUGK" . " \r\n";
        $strSQL .= ",      BASE.JIBAI" . " \r\n";
        $strSQL .= ",      BASE.JURYO" . " \r\n";
        $strSQL .= ",      BASE.INSHI" . " \r\n";
        $strSQL .= ",      BASE.DAIKO" . " \r\n";

        $strSQL .= ",      BASE.YUMUKBN" . " \r\n";
        $strSQL .= ",      (CASE WHEN BASE.YUMUKBN = '0' THEN (CASE WHEN S30.DENPYOKB = '1' THEN 1 WHEN S30.DENPYOKB = '2' THEN -1 WHEN S30.DENPYOKB = '3' THEN 1 END)END) YUDENPYOSU" . " \r\n";
        $strSQL .= ",      (CASE WHEN BASE.YUMUKBN = '1' THEN (CASE WHEN S30.DENPYOKB = '1' THEN 1 WHEN S30.DENPYOKB = '2' THEN -1 WHEN S30.DENPYOKB = '3' THEN 1 END)END) MUDENPYOSU" . " \r\n";
        $strSQL .= ",      (CASE WHEN S30.DENPYOKB = '1' THEN 1 WHEN S30.DENPYOKB = '2' THEN -1 WHEN S30.DENPYOKB = '3' THEN 1 END) DENPYOSU" . " \r\n";
        $strSQL .= "" . " \r\n";

        $strSQL .= "FROM" . " \r\n";
        $strSQL .= "(" . " \r\n";
        $strSQL .= "	SELECT S42.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= "	,      S02.YUMUKBN AS YUMUKBN" . " \r\n";
        $strSQL .= "	,      S30.SEB_NOU_NO AS SEB_NOU_NO" . " \r\n";
        $strSQL .= "	,      S30.ADN_OLD_SEB_NOU_NO AS OLD_SEB_NOU_NO" . " \r\n";
        $strSQL .= "	,      S30.UKK_NO AS UKK_NO" . " \r\n";
        $strSQL .= "	,      SUBSTR(S30.URIAGEDT,1,4) || '/' || SUBSTR(S30.URIAGEDT,5,2) || '/' || SUBSTR(S30.URIAGEDT,7,2) AS URIAGEDT" . " \r\n";
        $strSQL .= " ,      S30.URIAGEDT CHKURIAGE" . " \r\n";
        $strSQL .= "	,      S30.VCLOCRNO AS SEIKYU_CD" . " \r\n";
        $strSQL .= "	,      S30.CLM_NM1 || '　' || S30.CLM_NM2 AS SEIKYU_NM" . " \r\n";

        $strSQL .= "	,      M14.SCD_NM AS RIKUJI_NM" . " \r\n";

        $strSQL .= "	,      S30.VCLRGTNO_SYU AS VCLRGTNO_SYU" . " \r\n";
        $strSQL .= "	,      S30.VCLRGTNO_KANA AS VCLRGTNO_KANA" . " \r\n";
        $strSQL .= "	,      S30.VCLRGTNO_REN AS VCLRGTNO_REN" . " \r\n";
        $strSQL .= "	,      SUM(S42.HIYOUGK) AS HIYOUGK" . " \r\n";
        $strSQL .= "	,      SUM(CASE WHEN S42.HIYOUCD = '01' THEN S42.HIYOUGK END) AS JIBAI" . " \r\n";
        $strSQL .= "	,      SUM(CASE WHEN S42.HIYOUCD = '02' THEN S42.HIYOUGK END) AS JURYO" . " \r\n";
        $strSQL .= "	,      SUM(CASE WHEN S42.HIYOUCD = '03' THEN S42.HIYOUGK END) AS INSHI" . " \r\n";
        $strSQL .= "	,      SUM(CASE WHEN S42.HIYOUCD = '04' THEN S42.HIYOUGK END) AS DAIKO" . " \r\n";
        $strSQL .= "	,      S30.DENPYOKB" . " \r\n";

        $strSQL .= "	FROM   M41S42 S42" . " \r\n";
        $strSQL .= "	INNER JOIN M41S30 S30 ON S30.SEB_NOU_NO = S42.SEB_NOU_NO AND S30.DENPYOKB = S42.DENPYOKB" . " \r\n";
        $strSQL .= "	--INNER JOIN M41S40 S40 ON S40.SEB_NOU_NO = S30.SEB_NOU_NO AND S40.DENPYOKB = S30.DENPYOKB" . " \r\n";
        $strSQL .= "	--INNER JOIN M41S41 S41 ON S30.SEB_NOU_NO = S41.SEB_NOU_NO AND S30.DENPYOKB = S41.DENPYOKB" . " \r\n";
        $strSQL .= "	LEFT JOIN (SELECT HIYOUCD, MAX(HIYOUMEI) HIYOUMEI FROM M28S08 GROUP BY HIYOUCD) S08 ON S08.HIYOUCD = S42.HIYOUCD" . " \r\n";

        $strSQL .= "	LEFT JOIN M27M14 M14 ON TRIM(M14.SCD_VAL) = TRIM(S30.VCLRGTNO_LAND) AND SCD_SYSID = 'Z' AND SCD_ID = 'RIKUJI'" . " \r\n";

        $strSQL .= "	--LEFT JOIN M28S02 S02 ON S41.NYUKOKBN = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "LEFT JOIN M28S02 S02 ON S30.DAINYKKB = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "	WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "	AND   S42.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "	GROUP BY S42.TENPO_CD" . " \r\n";
        $strSQL .= "	,      S02.YUMUKBN" . " \r\n";
        $strSQL .= "	,      S30.SEB_NOU_NO" . " \r\n";
        $strSQL .= "	,      S30.ADN_OLD_SEB_NOU_NO" . " \r\n";
        $strSQL .= "	,      S30.UKK_NO" . " \r\n";
        $strSQL .= "	,      S30.URIAGEDT" . " \r\n";
        $strSQL .= "	,      S30.VCLOCRNO" . " \r\n";
        $strSQL .= "	,      S30.CLM_NM1 || '　' || S30.CLM_NM2" . " \r\n";

        $strSQL .= "	,      M14.SCD_NM" . " \r\n";

        $strSQL .= "	,      S30.VCLRGTNO_SYU" . " \r\n";
        $strSQL .= "	,      S30.VCLRGTNO_KANA" . " \r\n";
        $strSQL .= "	,      S30.VCLRGTNO_REN" . " \r\n";
        $strSQL .= "	, S30.VCLRGTNO_LAND" . " \r\n";
        $strSQL .= "	, S30.DENPYOKB" . " \r\n";
        $strSQL .= ") BASE" . " \r\n";

        $strSQL .= "INNER JOIN M41S30 S30 ON BASE.SEB_NOU_NO = S30.SEB_NOU_NO AND BASE.DENPYOKB = S30.DENPYOKB" . " \r\n";
        $strSQL .= "AND  S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "AND  S30.TENPO_CD = '@TENPO_CD'" . " \r\n";

        $strSQL .= "ORDER BY BASE.SEB_NOU_NO" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);

    }

    //'2017/08/07 INS START
    // '**********************************************************************
    //'処 理 名：パック金額のSQL生成++
    //'関 数 名：fncCreatUriPackSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************
    public function fncCreatUriPackSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT V.SEB_NOU_NO" . " \r\n";
        $strSQL .= ",      V.URIAGEDT" . " \r\n";
        $strSQL .= ",      V.SEIKYU_CD" . " \r\n";
        $strSQL .= ",      V.SEIKYU_NM" . " \r\n";
        $strSQL .= ",      V.PACKKIN" . " \r\n";

        $strSQL .= ",      (CASE WHEN V.DENPYOKB = '1' THEN 1 WHEN V.DENPYOKB = '2' THEN -1 WHEN V.DENPYOKB = '3' THEN 1 END) DENPYOSU" . " \r\n";
        $strSQL .= "FROM   (" . " \r\n";

        $strSQL .= "SELECT S30.SEB_NOU_NO AS SEB_NOU_NO" . " \r\n";
        $strSQL .= ",      SUBSTR(S30.URIAGEDT,1,4) || '/' || SUBSTR(S30.URIAGEDT,5,2) || '/' || SUBSTR(S30.URIAGEDT,7,2) AS URIAGEDT" . " \r\n";
        $strSQL .= ",      S30.VCLOCRNO AS SEIKYU_CD" . " \r\n";
        $strSQL .= ",      S30.CLM_NM1 || '　' || S30.CLM_NM2 AS SEIKYU_NM" . " \r\n";
        $strSQL .= ",      S40.PAK_URG_GKU AS PACKKIN" . " \r\n";

        $strSQL .= ",      S30.DENPYOKB" . " \r\n";
        $strSQL .= "FROM   M41S30 S30" . " \r\n";
        $strSQL .= "INNER JOIN M41S40 S40 ON S30.SEB_NOU_NO = S40.SEB_NOU_NO AND S30.DENPYOKB = S40.DENPYOKB" . " \r\n";
        $strSQL .= "LEFT JOIN M28S02 S02 ON S30.DAINYKKB = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "WHERE  S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "AND    S30.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "AND    S40.PAK_URG_GKU <> 0" . " \r\n";

        $strSQL .= ") V" . " \r\n";

        $strSQL .= "ORDER BY V.SEB_NOU_NO" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);
    }

    //'2017/08/07 INS START
    // '**********************************************************************
    //'処 理 名：整備日報_有償売上分のSQL生成
    //'関 数 名：fncCreatSeibiYushoSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************
    public function fncCreatSeibiYushoSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT V.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ",      V.NYUKOKBN AS NYUKOKBN" . " \r\n";
        $strSQL .= ",      V.NYUKOKBNMEI AS NYUKOKBNMEI" . " \r\n";
        $strSQL .= ",      DECODE(V.RESULTKBN,NULL,'99',V.RESULTKBN) AS RESULTKBN" . " \r\n";
        $strSQL .= ",      V.台数 AS DAISU" . " \r\n";
        $strSQL .= ",      V.工賃売上 AS KOC_URI" . " \r\n";
        $strSQL .= ",      V.部品売上 AS BUH_URI" . " \r\n";
        $strSQL .= ",      V.売上小計 AS URI_SUBTTL" . " \r\n";
        $strSQL .= ",      V.外注売上 AS GAC_URI" . " \r\n";
        $strSQL .= ",      V.売上合計 AS URI_TTL" . " \r\n";
        $strSQL .= ",      V.工賃原価 AS KOC_GEN" . " \r\n";
        $strSQL .= ",      V.部品原価 AS BUH_GEN" . " \r\n";
        $strSQL .= ",      V.原価小計 AS GEN_SUBTTL" . " \r\n";
        $strSQL .= ",      V.外注原価 AS GAC_GEN" . " \r\n";
        $strSQL .= ",      V.原価合計 AS GEN_TTL" . " \r\n";
        $strSQL .= ",      DECODE(SUM(V.売上合計) OVER(PARTITION BY V.TENPO_CD),0,0,ROUND((V.売上合計 / SUM(V.売上合計) OVER(PARTITION BY V.TENPO_CD)) * 100,1)) AS KOSEIHI" . " \r\n";
        $strSQL .= ",      (V.売上合計 - V.原価合計) AS ARARI_TTL" . " \r\n";
        $strSQL .= ",      DECODE(V.売上合計,0,0,ROUND(((V.売上合計 - V.原価合計) / V.売上合計) * 100,1)) AS ARARI_PRF" . " \r\n";
        $strSQL .= ",      DECODE(V.部品売上,0,0,ROUND(((V.部品売上 - V.部品原価) / V.部品売上) * 100,1)) AS BUH_PRF" . " \r\n";
        $strSQL .= ",      DECODE(V.外注売上,0,0,ROUND(((V.外注売上 - V.外注原価) / V.外注売上) * 100,1)) AS GAC_PRF" . " \r\n";
        $strSQL .= ",      V.値引合計 AS NEB_TTL" . " \r\n";
        $strSQL .= ",      DECODE(V.売上合計,0,0,ROUND((V.値引合計 / V.売上合計) * 100,1)) AS NEB_PRF" . " \r\n";
        $strSQL .= ",      DECODE(V.台数,0,0,ROUND(V.売上合計 / V.台数,0)) AS URI_PER_DAI" . " \r\n";
        $strSQL .= ",      DECODE(V.台数,0,0,ROUND(V.原価合計 / V.台数,0)) AS GEN_PER_DAI" . " \r\n";
        $strSQL .= ",      DECODE(V.台数,0,0,ROUND((V.売上合計 - V.原価合計) / V.台数,0)) AS ARARI_PER_DAI" . " \r\n";
        $strSQL .= ",      DECODE(V.台数,0,0,ROUND(V.値引合計 / V.台数,0)) AS NEB_PER_DAI" . " \r\n";
        $strSQL .= ",      SUM(V.売上合計) OVER(PARTITION BY V.TENPO_CD) AS TTL_URI" . " \r\n";
        $strSQL .= "FROM   (" . " \r\n";
        $strSQL .= "        SELECT S41.TENPO_CD" . " \r\n";
        $strSQL .= "        ,    S41.NYUKOKBN" . " \r\n";
        $strSQL .= "        ,    S02.NYUKOKBNMEI" . " \r\n";
        $strSQL .= "        ,    S02.RESULTKBN" . " \r\n";
        $strSQL .= "        ,    SUM(S41.DSU_CNT) 台数" . " \r\n";

        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_URG + S41.IRI_GJT_RYO_URG + S41.UKO_GJT_RYO_URG) 工賃売上" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_PAR_URG + S41.IRI_PAR_URG + S41.UKO_PAR_URG) 部品売上" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_URG + S41.NAI_PAR_URG + S41.IRI_GJT_RYO_URG + S41.IRI_PAR_URG + S41.UKO_GJT_RYO_URG + S41.UKO_PAR_URG) 売上小計" . " \r\n";
        $strSQL .= "        ,    SUM(S41.GTU_GJT_RYO_URG + S41.GTU_PAR_URG + S41.GTU_IRI_GJT_RYO_URG + S41.GTU_IRI_PAR_URG + S41.GTU_UKO_GJT_RYO_URG + S41.GTU_UKO_PAR_URG) 外注売上" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_URG + S41.NAI_PAR_URG + S41.IRI_GJT_RYO_URG + S41.IRI_PAR_URG + S41.UKO_GJT_RYO_URG + S41.UKO_PAR_URG + S41.GTU_GJT_RYO_URG + S41.GTU_PAR_URG + S41.GTU_IRI_GJT_RYO_URG + S41.GTU_IRI_PAR_URG + S41.GTU_UKO_GJT_RYO_URG + S41.GTU_UKO_PAR_URG) 売上合計" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_PCS + S41.IRI_GJT_RYO_PCS + S41.UKO_GJT_RYO_PCS) 工賃原価" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_PAR_PCS + S41.IRI_PAR_PCS + S41.UKO_PAR_PCS) 部品原価" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_PCS + S41.NAI_PAR_PCS + S41.IRI_GJT_RYO_PCS + S41.IRI_PAR_PCS + S41.UKO_GJT_RYO_PCS + S41.UKO_PAR_PCS) 原価小計" . " \r\n";
        $strSQL .= "        ,    SUM(S41.GTU_GJT_RYO_PCS + S41.GE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_PCS + S41.GTU_IRI_PAR_PCS + S41.GTU_UKO_GJT_RYO_PCS + S41.GTU_UKO_PAR_PCS) 外注原価" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_PCS + S41.NAI_PAR_PCS + S41.IRI_GJT_RYO_PCS + S41.IRI_PAR_PCS + S41.UKO_GJT_RYO_PCS + S41.UKO_PAR_PCS + S41.GTU_GJT_RYO_PCS + S41.GE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_PCS + S41.GTU_IRI_PAR_PCS + S41.GTU_UKO_GJT_RYO_PCS + S41.GTU_UKO_PAR_PCS) 原価合計" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_NBK_GKU + S41.NAI_PAR_NBK_GKU + S41.IRI_GJT_RYO_NBK_GKU + S41.IRI_PAR_NBK_GKU + S41.UKO_GJT_RYO_NBK_GKU + S41.UKO_PAR_NBK_GKU + S41.GTU_GJT_RYO_NBK_GKU + S41.NE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_NBK_GKU + S41.GTU_IRI_PAR_NBK_GKU + S41.GTU_UKO_GJT_RYO_NBK_GKU + S41.GTU_UKO_PAR_NBK_GKU) 値引合計" . " \r\n";

        $strSQL .= "        FROM  M41S41 S41" . " \r\n";
        $strSQL .= "        INNER JOIN M41S30 S30 ON S30.SEB_NOU_NO = S41.SEB_NOU_NO AND S30.DENPYOKB = S41.DENPYOKB" . " \r\n";

        $strSQL .= "        INNER JOIN M41S40 S40 ON S40.SEB_NOU_NO = S30.SEB_NOU_NO AND S40.DENPYOKB = S30.DENPYOKB" . " \r\n";

        $strSQL .= "        LEFT JOIN M41S44 S44 ON S44.UKK_NO = S30.UKK_NO AND S44.S_TANTOCD = S30.S_TANTOCD" . " \r\n";

        $strSQL .= "        LEFT JOIN M28S02 S02 ON S41.NYUKOKBN = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "        WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "        AND   S41.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "        AND   S02.YUMUKBN = '0'" . " \r\n";
        $strSQL .= "        GROUP BY S41.TENPO_CD" . " \r\n";
        $strSQL .= "        ,    S41.NYUKOKBN" . " \r\n";
        $strSQL .= "        ,    S02.NYUKOKBNMEI" . " \r\n";
        $strSQL .= "        ,    S02.RESULTKBN" . " \r\n";
        $strSQL .= "               ) V" . " \r\n";
        $strSQL .= "ORDER BY  V.TENPO_CD" . " \r\n";
        $strSQL .= ",    V.RESULTKBN" . " \r\n";
        $strSQL .= ",    V.NYUKOKBN" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);

    }

    //'2017/08/07 INS START
    // '**********************************************************************
    //'処 理 名：整備日報_無償売上分のSQL生成
    //'関 数 名：fncCreatSeibiMushoSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************
    public function fncCreatSeibiMushoSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT V.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ",      V.NYUKOKBN AS NYUKOKBN" . " \r\n";
        $strSQL .= ",      V.NYUKOKBNMEI AS NYUKOKBNMEI" . " \r\n";
        $strSQL .= ",      V.RESULTKBN AS RESULTKBN" . " \r\n";
        $strSQL .= ",      V.台数 AS DAISU" . " \r\n";
        $strSQL .= ",      V.工賃売上 AS KOC_URI" . " \r\n";
        $strSQL .= ",      V.工賃原価 AS KOC_GEN" . " \r\n";
        $strSQL .= ",      V.部品原価 AS BUH_GEN" . " \r\n";
        $strSQL .= ",      V.外注原価 AS GAC_GEN" . " \r\n";
        $strSQL .= ",      V.原価合計 AS GEN_TTL" . " \r\n";
        $strSQL .= ",      DECODE(V.台数,0,0,ROUND(V.売上合計 / V.台数,0)) AS URI_PER_DAI" . " \r\n";
        $strSQL .= ",      DECODE(V.台数,0,0,ROUND(V.原価合計 / V.台数,0)) AS GEN_PER_DAI" . " \r\n";
        $strSQL .= ",      SUM(V.台数) OVER(PARTITION BY V.TENPO_CD) AS TTL_DAISU" . " \r\n";
        $strSQL .= ",      SUM(V.売上合計) OVER(PARTITION BY V.TENPO_CD) AS TTL_URI" . " \r\n";
        $strSQL .= ",      SUM(V.原価合計) OVER(PARTITION BY V.TENPO_CD) AS TTL_GEN" . " \r\n";
        $strSQL .= "FROM   (" . " \r\n";
        $strSQL .= "        SELECT S41.TENPO_CD" . " \r\n";
        $strSQL .= "        ,    S41.NYUKOKBN" . " \r\n";
        $strSQL .= "        ,    S02.NYUKOKBNMEI" . " \r\n";
        $strSQL .= "        ,    S02.RESULTKBN" . " \r\n";
        $strSQL .= "        ,    SUM(S41.DSU_CNT) 台数" . " \r\n";

        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_URG + S41.IRI_GJT_RYO_URG + S41.UKO_GJT_RYO_URG) 工賃売上" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_PAR_URG + S41.IRI_PAR_URG + S41.UKO_PAR_URG) 部品売上" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_URG + S41.NAI_PAR_URG + S41.IRI_GJT_RYO_URG + S41.IRI_PAR_URG + S41.UKO_GJT_RYO_URG + S41.UKO_PAR_URG) 売上小計" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_URG + S41.NAI_PAR_URG + S41.IRI_GJT_RYO_URG + S41.IRI_PAR_URG + S41.UKO_GJT_RYO_URG + S41.UKO_PAR_URG + S41.GTU_GJT_RYO_URG + S41.GTU_PAR_URG + S41.GTU_IRI_GJT_RYO_URG + S41.GTU_IRI_PAR_URG + S41.GTU_UKO_GJT_RYO_URG + S41.GTU_UKO_PAR_URG) 売上合計" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_PCS + S41.IRI_GJT_RYO_PCS + S41.UKO_GJT_RYO_PCS) 工賃原価" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_PAR_PCS + S41.IRI_PAR_PCS + S41.UKO_PAR_PCS) 部品原価" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_PCS + S41.NAI_PAR_PCS + S41.IRI_GJT_RYO_PCS + S41.IRI_PAR_PCS + S41.UKO_GJT_RYO_PCS + S41.UKO_PAR_PCS) 原価小計" . " \r\n";
        $strSQL .= "        ,    SUM(S41.GTU_GJT_RYO_PCS + S41.GE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_PCS + S41.GTU_IRI_PAR_PCS + S41.GTU_UKO_GJT_RYO_PCS + S41.GTU_UKO_PAR_PCS) 外注原価" . " \r\n";
        $strSQL .= "        ,    SUM(S41.NAI_GJT_RYO_PCS + S41.NAI_PAR_PCS + S41.IRI_GJT_RYO_PCS + S41.IRI_PAR_PCS + S41.UKO_GJT_RYO_PCS + S41.UKO_PAR_PCS + S41.GTU_GJT_RYO_PCS + S41.GE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_PCS + S41.GTU_IRI_PAR_PCS + S41.GTU_UKO_GJT_RYO_PCS + S41.GTU_UKO_PAR_PCS) 原価合計" . " \r\n";

        $strSQL .= "        FROM  M41S41 S41" . " \r\n";
        $strSQL .= "        INNER JOIN M41S30 S30 ON S30.SEB_NOU_NO = S41.SEB_NOU_NO AND S30.DENPYOKB = S41.DENPYOKB" . " \r\n";

        $strSQL .= "        INNER JOIN M41S40 S40 ON S40.SEB_NOU_NO = S30.SEB_NOU_NO AND S40.DENPYOKB = S30.DENPYOKB" . " \r\n";

        $strSQL .= "        LEFT JOIN M41S44 S44 ON S44.UKK_NO = S30.UKK_NO AND S44.S_TANTOCD = S30.S_TANTOCD" . " \r\n";

        $strSQL .= "        LEFT JOIN M28S02 S02 ON S41.NYUKOKBN = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "        WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "        AND   S41.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "        AND   S02.YUMUKBN = '1'" . " \r\n";
        $strSQL .= "        GROUP BY S41.TENPO_CD" . " \r\n";
        $strSQL .= "        ,    S41.NYUKOKBN" . " \r\n";
        $strSQL .= "        ,    S02.NYUKOKBNMEI" . " \r\n";
        $strSQL .= "        ,    S02.RESULTKBN" . " \r\n";
        $strSQL .= "               ) V" . " \r\n";
        $strSQL .= "ORDER BY  V.TENPO_CD" . " \r\n";
        $strSQL .= ",    V.NYUKOKBN" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);

    }

    //'2017/08/07 INS START
    // '**********************************************************************
    //'処 理 名：整備日報_総計のSQL生成
    //'関 数 名：fncCreatSeibiSokeiSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************
    public function fncCreatSeibiSokeiSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT V.TENPO_CD AS TENPO_CD" . " \r\n";
        $strSQL .= ",      V.YUMUKBN AS YUMUKBN" . " \r\n";
        $strSQL .= ",      DECODE(V.YUMUKBN,'0','有償','無償') AS YUMUKBN_NM" . " \r\n";
        $strSQL .= ",      V.台数 AS DAISU" . " \r\n";
        $strSQL .= ",      V.売上合計 AS URI_TTL" . " \r\n";
        $strSQL .= ",      V.原価合計 AS GEN_TTL" . " \r\n";
        $strSQL .= ",      (CASE WHEN V.YUMUKBN = '0' THEN V.売上合計 - V.原価合計 ELSE NULL END) AS ARARI_TTL" . " \r\n";
        $strSQL .= "FROM   (" . " \r\n";
        $strSQL .= "        SELECT S41.TENPO_CD" . " \r\n";
        $strSQL .= "        ,      S02.YUMUKBN" . " \r\n";
        $strSQL .= "        ,      SUM(S41.DSU_CNT) 台数" . " \r\n";

        $strSQL .= "        ,      SUM(S41.NAI_GJT_RYO_URG + S41.NAI_PAR_URG + S41.IRI_GJT_RYO_URG + S41.IRI_PAR_URG + S41.UKO_GJT_RYO_URG + S41.UKO_PAR_URG + S41.GTU_GJT_RYO_URG + S41.GTU_PAR_URG + S41.GTU_IRI_GJT_RYO_URG + S41.GTU_IRI_PAR_URG + S41.GTU_UKO_GJT_RYO_URG + S41.GTU_UKO_PAR_URG) 売上合計" . " \r\n";
        $strSQL .= "        ,      SUM(S41.NAI_GJT_RYO_PCS + S41.NAI_PAR_PCS + S41.IRI_GJT_RYO_PCS + S41.IRI_PAR_PCS + S41.UKO_GJT_RYO_PCS + S41.UKO_PAR_PCS + S41.GTU_GJT_RYO_PCS + S41.GE_GI_BUHINGK + S41.GTU_IRI_GJT_RYO_PCS + S41.GTU_IRI_PAR_PCS + S41.GTU_UKO_GJT_RYO_PCS + S41.GTU_UKO_PAR_PCS) 原価合計" . " \r\n";

        $strSQL .= "        FROM  M41S41 S41" . " \r\n";
        $strSQL .= "        INNER JOIN M41S30 S30 ON S30.SEB_NOU_NO = S41.SEB_NOU_NO AND S30.DENPYOKB = S41.DENPYOKB" . " \r\n";

        $strSQL .= "        INNER JOIN M41S40 S40 ON S40.SEB_NOU_NO = S30.SEB_NOU_NO AND S40.DENPYOKB = S30.DENPYOKB" . " \r\n";

        $strSQL .= "        LEFT JOIN M41S44 S44 ON S44.UKK_NO = S30.UKK_NO AND S44.S_TANTOCD = S30.S_TANTOCD" . " \r\n";

        $strSQL .= "        LEFT JOIN M28S02 S02 ON S41.NYUKOKBN = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "        WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "        AND   S41.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "        GROUP BY S41.TENPO_CD" . " \r\n";
        $strSQL .= "        ,     S02.YUMUKBN" . " \r\n";
        $strSQL .= "               ) V" . " \r\n";

        $strSQL .= "ORDER BY V.YUMUKBN" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);

    }

    //'2017/08/07 INS START
    // '**********************************************************************
    //'処 理 名：整備日報_諸費用のSQL生成
    //'関 数 名：fncCreatSeibiSyohiyoSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************
    public function fncCreatSeibiSyohiyoSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT S42.HIYOUCD AS HIYOUCD" . " \r\n";
        $strSQL .= ",      S08.HIYOUMEI AS HIYOUMEI" . " \r\n";
        $strSQL .= ",      SUM(CASE WHEN S42.HIYOUGK > 0 THEN 1 ELSE -1 END) AS DENPYOSU" . " \r\n";
        $strSQL .= ",      SUM(S42.HIYOUGK) AS KINGAKU" . " \r\n";
        $strSQL .= "FROM   M41S42 S42" . " \r\n";
        $strSQL .= "INNER JOIN M41S30 S30 ON S30.SEB_NOU_NO = S42.SEB_NOU_NO AND S30.DENPYOKB = S42.DENPYOKB" . " \r\n";
        $strSQL .= "LEFT JOIN (SELECT HIYOUCD, MAX(HIYOUMEI) HIYOUMEI FROM M28S08 GROUP BY HIYOUCD) S08 ON S08.HIYOUCD = S42.HIYOUCD" . " \r\n";
        $strSQL .= "WHERE S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "AND   S42.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "GROUP BY S42.HIYOUCD, S08.HIYOUMEI" . " \r\n";
        $strSQL .= "ORDER BY S42.HIYOUCD" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);

    }

    //'2017/08/07 INS START
    // '**********************************************************************
    //'処 理 名：整備日報_前受金のSQL生成
    //'関 数 名：fncCreatSeibiMaeukeSQL
    //'戻 り 値：SQL
    //'処理説明：SQLを生成する
    //'**********************************************************************
    public function fncCreatSeibiMaeukeSQL($tenpocd, $updstr, $updend)
    {

        $strSQL = "";
        $strSQL .= "SELECT S30.DAINYKKB AS DAINYKKB" . " \r\n";
        $strSQL .= ",      S02.NYUKOKBNMEI AS NYUKOKBNMEI" . " \r\n";
        $strSQL .= ",      SUM(CASE WHEN S40.MAEUKGAKU > 0 THEN 1 ELSE (CASE WHEN S40.MAEUKGAKU = 0 THEN 0 ELSE -1 END) END) AS MAEUKEDENPY" . " \r\n";
        $strSQL .= ",      SUM(S40.MAEUKGAKU) AS MAEUKEKIN" . " \r\n";
        $strSQL .= ",      SUM(CASE WHEN S40.PAK_URG_GKU > 0 THEN 1 ELSE (CASE WHEN S40.PAK_URG_GKU = 0 THEN 0 ELSE -1 END)  END) AS PACKDENPY" . " \r\n";
        $strSQL .= ",      SUM(S40.PAK_URG_GKU) AS PACKKIN" . " \r\n";
        $strSQL .= "FROM   M41S30 S30" . " \r\n";
        $strSQL .= "INNER JOIN M41S40 S40 ON S30.SEB_NOU_NO = S40.SEB_NOU_NO AND S30.DENPYOKB = S40.DENPYOKB" . " \r\n";
        $strSQL .= "LEFT JOIN M28S02 S02 ON S30.DAINYKKB = S02.NYUKOKBN" . " \r\n";
        $strSQL .= "WHERE  S30.URIAGEDT >= '@URIAGEDT_STA' AND S30.URIAGEDT <= '@URIAGEDT_END'" . " \r\n";
        $strSQL .= "AND    S30.TENPO_CD = '@TENPO_CD'" . " \r\n";
        $strSQL .= "GROUP BY S30.DAINYKKB" . " \r\n";
        $strSQL .= ",        S02.NYUKOKBNMEI" . " \r\n";
        $strSQL .= "ORDER BY S30.DAINYKKB" . " \r\n";

        $strSQL = str_replace("@URIAGEDT_STA", $updstr, $strSQL);
        $strSQL = str_replace("@URIAGEDT_END", $updend, $strSQL);
        $strSQL = str_replace("@TENPO_CD", $tenpocd, $strSQL);

        return parent::select($strSQL);

    }

}
