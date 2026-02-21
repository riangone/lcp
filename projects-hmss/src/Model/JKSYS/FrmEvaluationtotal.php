<?php
/**
 * 説明：
 *
 *
 * @author yin
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                      内容                         担当
 * YYYYMMDD                  #ID                          XXXXXX                      FCSDL
 * 20240722                仕様変更         202407_人事考課表作成ツール_再生係仕様変更      YIN
 * 20240902                総限界利益＋サービス員給与の合算値が変更         総限界利益＋サービス員給与の合算値が変更      lhb
 * 20250418                仕様変更       202504_人事考課表作成ツール_集計仕様変更.xlsx     lujunxia
 * 20250429                仕様変更     202504_人事考課表作成ツール_集計仕様変更.xlsx-仕様変更20250428      lujunxia
 * 20250507                  BUG              20250428_人事考課集計エラー.xlsx           caina
 * 20250508                仕様変更     部署181を441に変換している箇所がありますが、すべて変換しないようにする      lujunxia
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
//*************************************
// * 処理名	：FrmEvaluationtotal
// * 関数名	：FrmEvaluationtotal
// * 処理説明	：共通クラスの読込み
//*************************************

class FrmEvaluationtotal extends ClsComDb
{
    private $ClsComFncJKSYS;
    // 人事コントロールマスタ取得SQL
    public function SelJKCONTROLMSE_SQL($strID)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $SQL = "";
        $SQL .= "SELECT KAKI_HYOUKA_END_MT" . "\r\n";
        $SQL .= "      ,TOUKI_HYOUKA_END_MT" . "\r\n";
        $SQL .= "      ,KISYU_YMD" . "\r\n";
        $SQL .= "      ,KIMATU_YMD" . "\r\n";
        $SQL .= "FROM  JKCONTROLMST" . "\r\n";
        $SQL .= "WHERE ID = @ID" . "\r\n";

        // '-- ﾊﾟﾗﾒｰﾀ --
        $SQL = str_replace("@ID", $this->ClsComFncJKSYS->FncSqlNv($strID), $SQL);

        return parent::select($SQL);
    }

    // 社員別考課表タイプデータ取得SQL
    public function SelJKKOUKA_SYAIN_TYPE_SQL($strYm = "")
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $SQL = "";
        if ($strYm == "") {
            $SQL .= "SELECT MAX(HYOUKA_KIKAN_END) HYOUKA_KIKAN_END" . "\r\n";
            $SQL .= "FROM JKKOUKA_SYAIN_TYPE" . "\r\n";
        } else {
            $SQL .= "SELECT HYOUKA_KIKAN_END" . "\r\n";
            $SQL .= "FROM JKKOUKA_SYAIN_TYPE" . "\r\n";
            $SQL .= "WHERE HYOUKA_KIKAN_END = @KIKANEND" . "\r\n";

            // '-- ﾊﾟﾗﾒｰﾀ --
            $SQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($strYm), $SQL);
        }

        return parent::select($SQL);
    }

    // 実績集計データの存在チェック
    public function CHK_JISSEKI_SYUKEI_SQL($cboKoukaType, $rdoBoth, $rdo6Months, $rdo1year, $dtpTaisyouKE)
    {

        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";

        $strSQL .= "SELECT COUNT(JS.SYAIN_NO) KENSU" . "\r\n";
        $strSQL .= "FROM JKKOUKA_JISSEKI_SYUKEI JS" . "\r\n";

        // 考課表タイプが選択された場合
        if ($cboKoukaType <> "") {
            $strSQL .= "　  ,JKKOUKA_SYAIN_TYPE ST " . "\r\n";
        }

        // 評価対象期間終了
        $strSQL .= "WHERE JS.HYOUKA_KIKAN_END = @REP_ED " . "\r\n";

        // 評価期間（両方以外が選択された場合）
        if ($rdoBoth == 'false') {
            // 評価対象期間開始
            $strSQL .= "  AND JS.HYOUKA_KIKAN_START = @REP_ST " . "\r\n";

            // '-- ﾊﾟﾗﾒｰﾀ --
            if ($rdo6Months == 'true') {
                // 評価期間＝６ヶ月の場合
                $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $strSQL);
            } elseif ($rdo1year == 'true') {
                // 評価期間＝１年の場合
                $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -11)), $strSQL);
            }
        }

        // 考課表タイプが選択された場合
        if ($cboKoukaType <> "") {
            // 考課表タイプコード
            $strSQL .= "  AND ST.KOUKATYPE_CD = @KOUKA_TYPE " . "\r\n";
            // 社員番号
            $strSQL .= "  AND ST.SYAIN_NO = JS.SYAIN_NO " . "\r\n";
            // 評価対象期間終了
            $strSQL .= "  AND ST.HYOUKA_KIKAN_END = JS.HYOUKA_KIKAN_END  " . "\r\n";
            // 評価対象期間開始
            $strSQL .= "  AND ST.HYOUKA_KIKAN_START = JS.HYOUKA_KIKAN_START  " . "\r\n";

            // '-- ﾊﾟﾗﾒｰﾀ --
            $strSQL = str_replace("@KOUKA_TYPE", $this->ClsComFncJKSYS->FncSqlNv($cboKoukaType), $strSQL);

        }

        // '-- ﾊﾟﾗﾒｰﾀ --
        $strSQL = str_replace("@REP_ED", $this->ClsComFncJKSYS->FncSqlNv(substr(str_replace("/", "", $dtpTaisyouKE), 0, 6)), $strSQL);

        return parent::select($strSQL);
    }

    // 実績集計データ削除ＳＱＬ
    public function DEL_JISSEKI_SYUKEI_SQL($strKomokuKbn, $dtpTaisyouKE, $rdoBoth, $rdo1year, $rdo6Months, $cboKoukaType)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";

        $strSQL .= "DELETE FROM JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        // 評価対象期間終了
        $strSQL .= "WHERE HYOUKA_KIKAN_END = @REP_ED " . "\r\n";
        // 項目区分
        $strSQL .= "  AND KOMOKU_KBN >= @REP_KOUMOKU " . "\r\n";

        // 評価期間（両方以外が選択された場合）
        if ($rdoBoth == 'false') {
            // 評価対象期間開始
            $strSQL .= "  AND HYOUKA_KIKAN_START = @REP_ST " . "\r\n";
            //'-- ﾊﾟﾗﾒｰﾀ --
            if ($rdo6Months == 'true') {
                // 評価期間＝６ヶ月の場合
                $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $strSQL);
            } elseif ($rdo1year == 'true') {
                // 評価期間＝１年の場合
                $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -11)), $strSQL);
            }

        }

        // 考課表タイプが選択された場合
        if ($cboKoukaType <> "") {
            $strSQL .= "  AND (HYOUKA_KIKAN_START,HYOUKA_KIKAN_END,SYAIN_NO) IN (SELECT HYOUKA_KIKAN_START" . "\r\n";
            $strSQL .= "                                                                ,HYOUKA_KIKAN_END  " . "\r\n";
            $strSQL .= "                                                                ,SYAIN_NO  " . "\r\n";
            $strSQL .= "　                                                        FROM  JKKOUKA_SYAIN_TYPE " . "\r\n";
            $strSQL .= "                                                          WHERE KOUKATYPE_CD = @KOUKA_TYPE " . "\r\n";
            // 評価対象期間終了
            $strSQL .= "                                                            AND HYOUKA_KIKAN_END = @REP_ED  " . "\r\n";

            // 評価期間（両方以外が選択された場合）
            if ($rdoBoth == 'false') {
                // 評価対象期間開始
                $strSQL .= "                                                            AND HYOUKA_KIKAN_START = @REP_ST " . "\r\n";

                //  '-- ﾊﾟﾗﾒｰﾀ --
                if ($rdo6Months == 'true') {
                    // 評価期間＝６ヶ月の場合
                    $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $strSQL);
                } elseif ($rdo1year == 'true') {
                    // 評価期間＝１年の場合
                    $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -11)), $strSQL);
                }
            }

            $strSQL .= "                                                             )  " . "\r\n";

            //'-- ﾊﾟﾗﾒｰﾀ --
            $strSQL = str_replace("@KOUKA_TYPE", $this->ClsComFncJKSYS->FncSqlNv($cboKoukaType), $strSQL);
        }
        //-- ﾊﾟﾗﾒｰﾀ --
        $strSQL = str_replace("@REP_ED", $this->ClsComFncJKSYS->FncSqlNv(substr(str_replace("/", "", $dtpTaisyouKE), 0, 6)), $strSQL);
        $strSQL = str_replace("@REP_KOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strKomokuKbn), $strSQL);

        return parent::delete($strSQL);

    }

    // 周辺利益集計データ削除ＳＱＬ
    public function DEL_SYUHEN_RIEKI_SQL($strKomokuKbn, $cboKoukaType, $dtpTaisyouKE, $rdoBoth, $rdo6Months, $rdo1year)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";

        $strSQL .= "DELETE FROM JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        // 評価対象期間終了
        $strSQL .= "WHERE HYOUKA_KIKAN_END = @REP_ED " . "\r\n";
        // 項目区分
        $strSQL .= "  AND KOMOKU_KBN >= @REP_KOUMOKU " . "\r\n";

        // 評価期間（両方以外が選択された場合）
        if ($rdoBoth == 'false') {
            // 評価対象期間開始
            $strSQL .= "  AND HYOUKA_KIKAN_START = @REP_ST " . "\r\n";

            // -- ﾊﾟﾗﾒｰﾀ --
            if ($rdo6Months == 'true') {
                // 評価期間＝６ヶ月の場合
                $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $strSQL);
            } elseif ($rdo1year == 'true') {
                // 評価期間＝１年の場合
                $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -11)), $strSQL);
            }
        }

        // 考課表タイプが選択された場合
        if ($cboKoukaType <> "") {
            $strSQL .= "  AND (HYOUKA_KIKAN_START,HYOUKA_KIKAN_END,SYAIN_NO) IN (SELECT HYOUKA_KIKAN_START" . "\r\n";
            $strSQL .= "                                                                ,HYOUKA_KIKAN_END  " . "\r\n";
            $strSQL .= "                                                                ,SYAIN_NO  " . "\r\n";
            $strSQL .= "　                                                        FROM  JKKOUKA_SYAIN_TYPE " . "\r\n";
            $strSQL .= "                                                          WHERE KOUKATYPE_CD = @KOUKA_TYPE " . "\r\n";
            // 評価対象期間終了
            $strSQL .= "                                                            AND HYOUKA_KIKAN_END = @REP_ED  " . "\r\n";

            // 評価期間（両方以外が選択された場合）
            if ($rdoBoth == 'false') {
                // 評価対象期間開始
                $strSQL .= "                                                            AND HYOUKA_KIKAN_START = @REP_ST " . "\r\n";

                //  -- ﾊﾟﾗﾒｰﾀ --
                if ($rdo6Months == 'true') {
                    // 評価期間＝６ヶ月の場合
                    $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $strSQL);
                } elseif ($rdo1year == 'true') {
                    // 評価期間＝１年の場合
                    $strSQL = str_replace("@REP_ST", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -11)), $strSQL);
                }
            }
            $strSQL .= "                                                             )  " . "\r\n";

            // '-- ﾊﾟﾗﾒｰﾀ --
            $strSQL = str_replace("@KOUKA_TYPE", $this->ClsComFncJKSYS->FncSqlNv($cboKoukaType), $strSQL);
        }

        // '-- ﾊﾟﾗﾒｰﾀ --
        $strSQL = str_replace("@REP_ED", $this->ClsComFncJKSYS->FncSqlNv(substr(str_replace("/", "", $dtpTaisyouKE), 0, 6)), $strSQL);
        $strSQL = str_replace("@REP_KOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strKomokuKbn), $strSQL);

        return parent::delete($strSQL);
    }

    // 集計対象データ取得SQL
    public function SelJKKOUKA_SYAIN_SQL($cboKoukaType, $dtpTaisyouKE)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $SQL = "";
        // 社員番号
        $SQL .= " SELECT SYAIN_NO " . "\r\n";
        $SQL .= " FROM  JKKOUKA_SYAIN_TYPE" . "\r\n";
        $SQL .= " WHERE HYOUKA_KIKAN_END = @ENDYM" . "\r\n";
        $SQL .= "   AND HYOUKA_KIKAN_START = @STARTYM" . "\r\n";
        if ($cboKoukaType <> "") {
            $SQL .= "   AND KOUKATYPE_CD = @KOUKATYPE" . "\r\n";
        }

        // '-- ﾊﾟﾗﾒｰﾀ --
        $SQL = str_replace("@ENDYM", $this->ClsComFncJKSYS->FncSqlNv(substr(str_replace("/", "", $dtpTaisyouKE), 0, 6)), $SQL);

        $SQL = str_replace("@STARTYM", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $SQL);

        if ($cboKoukaType <> "") {
            $SQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($cboKoukaType), $SQL);
        }
        return parent::select($SQL);

    }

    // HYOSAN_VWより予算情報取得
    public function fnc_InsYosan($strType, $strKaisiYm, $intKikanKbn, $strSyukeiKomoku, $intLineNo, $blnMarume, $prvEndYm, $prvKisyuYM, $strBusyoCd = "")
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        // '--- Insert --
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        // 評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        // 評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        // 社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        // 集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        // 項目区分
        $strSQL .= "      ,'01'" . "\r\n";

        if ($intKikanKbn == 0) {
            // 6ヶ月
            if ($strKaisiYm == $prvKisyuYM) {
                if ($blnMarume) {
                    $strSQL .= "      ,ROUND(SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)) / 1000,0) " . "\r\n";
                } else {
                    $strSQL .= "      ,SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)) " . "\r\n";
                }
            } else {
                if ($blnMarume) {
                    $strSQL .= "      ,ROUND(SUM(NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) / 1000,0) " . "\r\n";
                } else {
                    $strSQL .= "      ,SUM(NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) " . "\r\n";
                }
            }
        } else {
            // 1年
            if ($blnMarume) {
                $strSQL .= "      ,ROUND(SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) / 1000,0)" . "\r\n";
            } else {
                $strSQL .= "      ,SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0) + NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0))" . "\r\n";
            }
        }
        // 20250418 lujunxia del s
        // 20240722 YIN INS S
        // switch ($strType) {
        //     case CNS_15:
        //         $strSQL .= "      + SUM(NVL(SIM.TOU_ZAN,0)) " . "\r\n";
        //         break;
        //     default:
        // }
        // 20240722 YIN INS E
        // 20250418 lujunxia del e

        // '共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        $strSQL .= "FROM (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,YSN_GK10" . "\r\n";
        $strSQL .= "            ,YSN_GK11" . "\r\n";
        $strSQL .= "            ,YSN_GK12" . "\r\n";
        $strSQL .= "            ,YSN_GK1" . "\r\n";
        $strSQL .= "            ,YSN_GK2" . "\r\n";
        $strSQL .= "            ,YSN_GK3" . "\r\n";
        $strSQL .= "            ,YSN_GK4" . "\r\n";
        $strSQL .= "            ,YSN_GK5" . "\r\n";
        $strSQL .= "            ,YSN_GK6" . "\r\n";
        $strSQL .= "            ,YSN_GK7" . "\r\n";
        $strSQL .= "            ,YSN_GK8" . "\r\n";
        $strSQL .= "            ,YSN_GK9" . "\r\n";
        //20210302 CI UPD S
        //$strSQL .= "      FROM   HYOSAN_NEW YSN" . "\r\n";
        switch ($strType) {
            case CNS_01:
                // 店長
                $strSQL .= "      FROM   (SELECT " . "\r\n";
                // 20250508 lujunxia upd s
                // $strSQL .= "              CASE WHEN Y.BUSYO_CD='181' THEN '441' ELSE Y.BUSYO_CD END BUSYO_CD" . "\r\n";
                $strSQL .= "              Y.BUSYO_CD" . "\r\n";
                // 20250508 lujunxia upd e
                $strSQL .= "             ,Y.YOSAN_YMD" . "\r\n";
                $strSQL .= "             ,Y.LINE_NO" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK10) YSN_GK10" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK11) YSN_GK11" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK12) YSN_GK12" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK1) YSN_GK1" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK2) YSN_GK2" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK3) YSN_GK3" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK4) YSN_GK4" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK5) YSN_GK5" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK6) YSN_GK6" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK7) YSN_GK7" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK8) YSN_GK8" . "\r\n";
                $strSQL .= "             ,SUM(Y.YSN_GK9) YSN_GK9" . "\r\n";
                $strSQL .= "              FROM HYOSAN_NEW Y " . "\r\n";
                $strSQL .= "              WHERE Y.LINE_NO = @LINE_NO" . "\r\n";
                $strSQL .= "                AND Y.YOSAN_YMD = @KISYUYM " . "\r\n";
                $strSQL .= "              GROUP BY " . "\r\n";
                // 20250508 lujunxia upd s
                // $strSQL .= "              CASE WHEN Y.BUSYO_CD='181' THEN '441' ELSE Y.BUSYO_CD END " . "\r\n";
                $strSQL .= "              Y.BUSYO_CD " . "\r\n";
                // 20250508 lujunxia upd s
                $strSQL .= "             ,Y.YOSAN_YMD" . "\r\n";
                $strSQL .= "             ,Y.LINE_NO" . "\r\n";
                $strSQL .= "              ) " . "\r\n";
                $strSQL .= "              ) YSN" . "\r\n";
                break;
            default:
                // 以外
                $strSQL .= "      FROM   HYOSAN_NEW YSN" . "\r\n";
        }
        ;
        //20210302 CI UPD E
        switch ($strType) {
            case CNS_01:
                // 店長
                //20210302 CI DEL S
                //$strSQL .= "      WHERE LINE_NO = @LINE_NO" . "\r\n";
                //$strSQL .= "        AND YOSAN_YMD = @KISYUYM ) YSN " . "\r\n";
                //20210302 CI DEL E
                // 整備限界利益は店舗コード下1桁='7'より取得
                if ($strSyukeiKomoku == "11") {
                    $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
                    $strSQL .= "WHERE SUBSTR(STYPE.BUSYO_CD,1,2) || '7' = YSN.BUSYO_CD(+) " . "\r\n";
                } else {
                    $strSQL .= "    , JKKOUKA_KANRI_MST  KANRI" . "\r\n";
                    $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
                    $strSQL .= "WHERE KANRI.MOJI_1 = YSN.BUSYO_CD(+) " . "\r\n";
                    // 20250507 caina upd s
                    // $strSQL .= "  AND STYPE.BUSYO_CD = KANRI.CODE " . "\r\n";
                    $strSQL .= "  AND TO_NUMBER(STYPE.BUSYO_CD) = TO_NUMBER(KANRI.CODE) " . "\r\n";
                    // 20250507 caina upd e
                    $strSQL .= "  AND KANRI.SYUBETU_CD = 'TENPO'  " . "\r\n";
                }
                break;
            case CNS_13:
                // 'ｻｰﾋﾞｽ・ｱﾄﾞﾊﾞｲｻﾞ
                if ($strBusyoCd == "212") {
                    $strSQL .= "      WHERE (LINE_NO = @LINE_NO" . "\r\n";
                    $strSQL .= "         OR  LINE_NO = 45)" . "\r\n";
                    $strSQL .= "        AND  BUSYO_CD = '212' " . "\r\n";
                } else {
                    $strSQL .= "      WHERE  LINE_NO = @LINE_NO" . "\r\n";
                    $strSQL .= "        AND  BUSYO_CD <> '212' " . "\r\n";
                }
                $strSQL .= "        AND YOSAN_YMD = @KISYUYM ) YSN " . "\r\n";
                $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
                $strSQL .= "WHERE STYPE.BUSYO_CD = YSN.BUSYO_CD(+) " . "\r\n";

                if ($strBusyoCd == "212") {
                    $strSQL .= "  AND STYPE.BUSYO_CD = '212' " . "\r\n";
                } else {
                    $strSQL .= "  AND STYPE.BUSYO_CD <> '212' " . "\r\n";
                }
                break;
            // 20250418 lujunxia del s
            // 20240722 YIN INS S
            // case CNS_15:
            //     $strSQL .= "      WHERE LINE_NO = @LINE_NO" . "\r\n";
            //     $strSQL .= "        AND YOSAN_YMD = @KISYUYM ) YSN " . "\r\n";
            //     $strSQL .= "     ,(SELECT BUSYO_CD" . "\r\n";
            //     $strSQL .= "            ,TOU_ZAN" . "\r\n";
            //     $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
            //     $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            //     $strSQL .= "        AND LINE_NO = 74 ) SIM" . "\r\n";
            //     $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
            //     $strSQL .= "WHERE STYPE.BUSYO_CD = YSN.BUSYO_CD(+) " . "\r\n";
            //     $strSQL .= "AND STYPE.BUSYO_CD = SIM.BUSYO_CD(+) " . "\r\n";
            //     break;
            // 20240722 YIN INS E
            // 20250418 lujunxia del e
            default:
                // 以外
                $strSQL .= "      WHERE LINE_NO = @LINE_NO" . "\r\n";
                $strSQL .= "        AND YOSAN_YMD = @KISYUYM ) YSN " . "\r\n";
                $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
                $strSQL .= "WHERE STYPE.BUSYO_CD = YSN.BUSYO_CD(+) " . "\r\n";
        }
        ;

        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";

        // '-- ﾊﾟﾗﾒｰﾀ --
        // '考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        // '期首年月
        $strSQL = str_replace("@KISYUYM", $this->ClsComFncJKSYS->FncSqlNv($prvKisyuYM), $strSQL);
        // '評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // '評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 20240722 YIN INS S
        // '集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // '集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 20240722 YIN INS E
        // '集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        //    'ﾗｲﾝNO
        $strSQL = str_replace("@LINE_NO", $this->ClsComFncJKSYS->FncSqlNz($intLineNo), $strSQL);
        return parent::insert($strSQL);
    }
    // 20250429 lujunxia ins s
    // サービス拠点数
    public function getServiceBaseStr()
    {
        $strSQL = "(SELECT DISTINCT(BUSYO_CD) FROM JKKOUKA_SYAIN_TYPE WHERE BUSYO_CD IN(" . SERVICE_BASE . ")" . "\r\n";
        $strSQL .= "  AND KOUKATYPE_CD = @KOUKATYPE AND HYOUKA_KIKAN_END = @KIKANEND)" . "\r\n";
        return $strSQL;
    }
    //HYOSAN_VWより予算情報取得3版（全サービス拠点）
    public function fnc_InsYosan3($strType, $strKaisiYm, $strSyukeiKomoku, $intLineNo, $prvEndYm, $prvKisyuYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        // '--- Insert --
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        // 評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        // 評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        // 社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        // 集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        // 項目区分
        $strSQL .= "      ,'01'" . "\r\n";
        //値
        $strSQL .= "      ,SERVICEBUSYO.AVERAGEVAL" . "\r\n";
        // '共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        switch ($strType . $strSyukeiKomoku) {
            // サービス・アドバイザ-総入庫台数-基準
            case CNS_12 . '21':
                // 総入庫台数:(予算表.有償入庫台数（10行目）+予算表.整備・無償台数（70行目）)÷予算表.総人員（144行目）÷サービス拠点数
                $strSQL .= "FROM (SELECT ROUND(DECODE(NVL(L144, 0)*SERVICEBASE.BASENUM,0,0,NVL(LTOTAL, 0) / NVL(L144, 0)/SERVICEBASE.BASENUM)) AS AVERAGEVAL" . "\r\n";
                break;
            default:
                // サービス総員当りサービス総限界利益:予算表．整備・総限界利益（87行目）÷ 予算表．総人員（144行目）÷ サービス拠点数（千円）
                $strSQL .= "FROM (SELECT ROUND(DECODE(NVL(L144, 0)*SERVICEBASE.BASENUM,0,0,NVL(LTOTAL, 0) / NVL(L144, 0)/SERVICEBASE.BASENUM/1000)) AS AVERAGEVAL" . "\r\n";
        }
        $strSQL .= "FROM (SELECT " . "\r\n";
        if ($strKaisiYm == $prvKisyuYM) {
            $strSQL .= "SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)) " . "\r\n";
        } else {
            $strSQL .= "SUM(NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0)) " . "\r\n";
        }
        $strSQL .= "  AS LTOTAL " . "\r\n";
        $strSQL .= "FROM (SELECT HYOSAN_NEW.BUSYO_CD" . "\r\n";
        $strSQL .= "            ,YSN_GK10" . "\r\n";
        $strSQL .= "            ,YSN_GK11" . "\r\n";
        $strSQL .= "            ,YSN_GK12" . "\r\n";
        $strSQL .= "            ,YSN_GK1" . "\r\n";
        $strSQL .= "            ,YSN_GK2" . "\r\n";
        $strSQL .= "            ,YSN_GK3" . "\r\n";
        $strSQL .= "            ,YSN_GK4" . "\r\n";
        $strSQL .= "            ,YSN_GK5" . "\r\n";
        $strSQL .= "            ,YSN_GK6" . "\r\n";
        $strSQL .= "            ,YSN_GK7" . "\r\n";
        $strSQL .= "            ,YSN_GK8" . "\r\n";
        $strSQL .= "            ,YSN_GK9" . "\r\n";
        $strSQL .= "      FROM   HYOSAN_NEW " . "\r\n";
        $strSQL .= "      INNER JOIN " . "\r\n";
        $strSQL .= $this->getServiceBaseStr() . "\r\n";
        $strSQL .= "      CALCBASE ON HYOSAN_NEW.BUSYO_CD = CALCBASE.BUSYO_CD" . "\r\n";
        switch ($strType . $strSyukeiKomoku) {
            // サービス・アドバイザ-総入庫台数-基準
            case CNS_12 . '21':
                // 総入庫台数
                $strSQL .= "      WHERE (HYOSAN_NEW.LINE_NO = @LINE_NO OR HYOSAN_NEW.LINE_NO = 10)" . "\r\n";
                break;
            default:
                // サービス総員当りサービス総限界利益
                $strSQL .= "      WHERE HYOSAN_NEW.LINE_NO = @LINE_NO" . "\r\n";
        }
        $strSQL .= "        AND YOSAN_YMD = @KISYUYM ) YSN )" . "\r\n";
        $strSQL .= "     , (SELECT " . "\r\n";
        if ($strKaisiYm == $prvKisyuYM) {
            $strSQL .= "SUM(NVL(YSN144.YSN_GK10,0) + NVL(YSN144.YSN_GK11,0) + NVL(YSN144.YSN_GK12,0) + NVL(YSN144.YSN_GK1,0) + NVL(YSN144.YSN_GK2,0) + NVL(YSN144.YSN_GK3,0)) " . "\r\n";
        } else {
            $strSQL .= "SUM(NVL(YSN144.YSN_GK4,0) + NVL(YSN144.YSN_GK5,0) + NVL(YSN144.YSN_GK6,0) + NVL(YSN144.YSN_GK7,0) + NVL(YSN144.YSN_GK8,0) + NVL(YSN144.YSN_GK9,0)) " . "\r\n";
        }
        $strSQL .= "  AS L144 " . "\r\n";
        $strSQL .= "  FROM (SELECT HYOSAN_NEW.BUSYO_CD" . "\r\n";
        $strSQL .= "            ,YSN_GK10" . "\r\n";
        $strSQL .= "            ,YSN_GK11" . "\r\n";
        $strSQL .= "            ,YSN_GK12" . "\r\n";
        $strSQL .= "            ,YSN_GK1" . "\r\n";
        $strSQL .= "            ,YSN_GK2" . "\r\n";
        $strSQL .= "            ,YSN_GK3" . "\r\n";
        $strSQL .= "            ,YSN_GK4" . "\r\n";
        $strSQL .= "            ,YSN_GK5" . "\r\n";
        $strSQL .= "            ,YSN_GK6" . "\r\n";
        $strSQL .= "            ,YSN_GK7" . "\r\n";
        $strSQL .= "            ,YSN_GK8" . "\r\n";
        $strSQL .= "            ,YSN_GK9" . "\r\n";
        $strSQL .= "      FROM  HYOSAN_NEW " . "\r\n";
        $strSQL .= "      INNER JOIN " . "\r\n";
        $strSQL .= $this->getServiceBaseStr() . "\r\n";
        $strSQL .= "      CALCBASE2 ON HYOSAN_NEW.BUSYO_CD = CALCBASE2.BUSYO_CD" . "\r\n";
        $strSQL .= "      WHERE YOSAN_YMD = @KISYUYM   " . "\r\n";
        $strSQL .= "        AND LINE_NO = 144 ) YSN144)" . "\r\n";
        // サービス拠点数
        $strSQL .= "    ,(SELECT COUNT(*) AS BASENUM FROM " . "\r\n";
        $strSQL .= $this->getServiceBaseStr() . ") SERVICEBASE) SERVICEBUSYO" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        $strSQL .= "  WHERE STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        //サービス拠点
        $strSQL .= "AND STYPE.BUSYO_CD IN(" . SERVICE_BASE . ")" . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO,SERVICEBUSYO.AVERAGEVAL" . "\r\n";

        // '-- ﾊﾟﾗﾒｰﾀ --
        // '考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        // '期首年月
        $strSQL = str_replace("@KISYUYM", $this->ClsComFncJKSYS->FncSqlNv($prvKisyuYM), $strSQL);
        // '評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // '評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 20240722 YIN INS S
        // '集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // '集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 20240722 YIN INS E
        // '集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        //    'ﾗｲﾝNO
        $strSQL = str_replace("@LINE_NO", $this->ClsComFncJKSYS->FncSqlNz($intLineNo), $strSQL);

        return parent::insert($strSQL);
    }
    // 20250429 lujunxia ins e
    // 20250418 lujunxia ins s
    // HYOSAN_VWより予算情報取得2版（6ヶ月だけ）
    public function fnc_InsYosan2($strType, $strKaisiYm, $strSyukeiKomoku, $intLineNo, $prvEndYm, $prvKisyuYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        // '--- Insert --
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        // 評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        // 評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        // 社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        // 集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        // 項目区分
        $strSQL .= "      ,'01'" . "\r\n";
        // 6ヶ月
        switch ($strType . $strSyukeiKomoku) {
            // サービス・アドバイザ-総入庫台数-基準
            case CNS_12 . '21':
            // BPアドバイザ-総入庫台数-基準
            case CNS_15 . '21':
                // 総入庫台数:(予算表.有償入庫台数（10行目）+予算表.整備・無償台数（70行目）)÷予算表.総人員（144行目）
                if ($strKaisiYm == $prvKisyuYM) {
                    $strSQL .= ",ROUND(DECODE(SUM(NVL(YSN144.YSN_GK10,0) + NVL(YSN144.YSN_GK11,0) + NVL(YSN144.YSN_GK12,0) + NVL(YSN144.YSN_GK1,0) + NVL(YSN144.YSN_GK2,0) + NVL(YSN144.YSN_GK3,0)), 0, 0," . "\r\n";
                    $strSQL .= "(SUM(NVL(YSN10.YSN_GK10,0) + NVL(YSN10.YSN_GK11,0) + NVL(YSN10.YSN_GK12,0) + NVL(YSN10.YSN_GK1,0) + NVL(YSN10.YSN_GK2,0) + NVL(YSN10.YSN_GK3,0)) " . "\r\n";
                    $strSQL .= "+SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0)))" . "\r\n";
                    $strSQL .= "/SUM(NVL(YSN144.YSN_GK10,0) + NVL(YSN144.YSN_GK11,0) + NVL(YSN144.YSN_GK12,0) + NVL(YSN144.YSN_GK1,0) + NVL(YSN144.YSN_GK2,0) + NVL(YSN144.YSN_GK3,0))),0)" . "\r\n";
                } else {
                    $strSQL .= ",ROUND(DECODE(SUM(NVL(YSN144.YSN_GK4,0) + NVL(YSN144.YSN_GK5,0) + NVL(YSN144.YSN_GK6,0) + NVL(YSN144.YSN_GK7,0) + NVL(YSN144.YSN_GK8,0) + NVL(YSN144.YSN_GK9,0)), 0, 0," . "\r\n";
                    $strSQL .= "(SUM(NVL(YSN10.YSN_GK4,0) + NVL(YSN10.YSN_GK5,0) + NVL(YSN10.YSN_GK6,0) + NVL(YSN10.YSN_GK7,0) + NVL(YSN10.YSN_GK8,0) + NVL(YSN10.YSN_GK9,0)) " . "\r\n";
                    $strSQL .= "+ SUM(NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0))) " . "\r\n";
                    $strSQL .= "/SUM(NVL(YSN144.YSN_GK4,0) + NVL(YSN144.YSN_GK5,0) + NVL(YSN144.YSN_GK6,0) + NVL(YSN144.YSN_GK7,0) + NVL(YSN144.YSN_GK8,0) + NVL(YSN144.YSN_GK9,0))),0)" . "\r\n";
                }
                break;
            default:
                // サービス総員当りサービス総限界利益:予算表．整備・総限界利益（87行目）÷ 予算表．総人員（144行目）
                if ($strKaisiYm == $prvKisyuYM) {
                    $strSQL .= ",DECODE(SUM(NVL(YSN144.YSN_GK10,0) + NVL(YSN144.YSN_GK11,0) + NVL(YSN144.YSN_GK12,0) + NVL(YSN144.YSN_GK1,0) + NVL(YSN144.YSN_GK2,0) + NVL(YSN144.YSN_GK3,0)), 0, 0," . "\r\n";
                    $strSQL .= "ROUND(SUM(NVL(YSN.YSN_GK10,0) + NVL(YSN.YSN_GK11,0) + NVL(YSN.YSN_GK12,0) + NVL(YSN.YSN_GK1,0) + NVL(YSN.YSN_GK2,0) + NVL(YSN.YSN_GK3,0))/SUM(NVL(YSN144.YSN_GK10,0) + NVL(YSN144.YSN_GK11,0) + NVL(YSN144.YSN_GK12,0) + NVL(YSN144.YSN_GK1,0) + NVL(YSN144.YSN_GK2,0) + NVL(YSN144.YSN_GK3,0)) / 1000,0)) " . "\r\n";
                } else {
                    $strSQL .= ",DECODE(SUM(NVL(YSN144.YSN_GK4,0) + NVL(YSN144.YSN_GK5,0) + NVL(YSN144.YSN_GK6,0) + NVL(YSN144.YSN_GK7,0) + NVL(YSN144.YSN_GK8,0) + NVL(YSN144.YSN_GK9,0)), 0, 0," . "\r\n";
                    $strSQL .= "ROUND(SUM(NVL(YSN.YSN_GK4,0) + NVL(YSN.YSN_GK5,0) + NVL(YSN.YSN_GK6,0) + NVL(YSN.YSN_GK7,0) + NVL(YSN.YSN_GK8,0) + NVL(YSN.YSN_GK9,0))/SUM(NVL(YSN144.YSN_GK4,0) + NVL(YSN144.YSN_GK5,0) + NVL(YSN144.YSN_GK6,0) + NVL(YSN144.YSN_GK7,0) + NVL(YSN144.YSN_GK8,0) + NVL(YSN144.YSN_GK9,0)) / 1000,0)) " . "\r\n";
                }
        }
        // '共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        $strSQL .= "FROM (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,YSN_GK10" . "\r\n";
        $strSQL .= "            ,YSN_GK11" . "\r\n";
        $strSQL .= "            ,YSN_GK12" . "\r\n";
        $strSQL .= "            ,YSN_GK1" . "\r\n";
        $strSQL .= "            ,YSN_GK2" . "\r\n";
        $strSQL .= "            ,YSN_GK3" . "\r\n";
        $strSQL .= "            ,YSN_GK4" . "\r\n";
        $strSQL .= "            ,YSN_GK5" . "\r\n";
        $strSQL .= "            ,YSN_GK6" . "\r\n";
        $strSQL .= "            ,YSN_GK7" . "\r\n";
        $strSQL .= "            ,YSN_GK8" . "\r\n";
        $strSQL .= "            ,YSN_GK9" . "\r\n";
        $strSQL .= "      FROM   HYOSAN_NEW YSN" . "\r\n";
        $strSQL .= "      WHERE LINE_NO = @LINE_NO" . "\r\n";
        $strSQL .= "        AND YOSAN_YMD = @KISYUYM ) YSN " . "\r\n";
        $strSQL .= "     ,(SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,YSN_GK10" . "\r\n";
        $strSQL .= "            ,YSN_GK11" . "\r\n";
        $strSQL .= "            ,YSN_GK12" . "\r\n";
        $strSQL .= "            ,YSN_GK1" . "\r\n";
        $strSQL .= "            ,YSN_GK2" . "\r\n";
        $strSQL .= "            ,YSN_GK3" . "\r\n";
        $strSQL .= "            ,YSN_GK4" . "\r\n";
        $strSQL .= "            ,YSN_GK5" . "\r\n";
        $strSQL .= "            ,YSN_GK6" . "\r\n";
        $strSQL .= "            ,YSN_GK7" . "\r\n";
        $strSQL .= "            ,YSN_GK8" . "\r\n";
        $strSQL .= "            ,YSN_GK9" . "\r\n";
        $strSQL .= "      FROM  HYOSAN_NEW " . "\r\n";
        $strSQL .= "      WHERE YOSAN_YMD = @KISYUYM   " . "\r\n";
        $strSQL .= "        AND LINE_NO = 144 ) YSN144" . "\r\n";
        $strSQL .= "     ,(SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,YSN_GK10" . "\r\n";
        $strSQL .= "            ,YSN_GK11" . "\r\n";
        $strSQL .= "            ,YSN_GK12" . "\r\n";
        $strSQL .= "            ,YSN_GK1" . "\r\n";
        $strSQL .= "            ,YSN_GK2" . "\r\n";
        $strSQL .= "            ,YSN_GK3" . "\r\n";
        $strSQL .= "            ,YSN_GK4" . "\r\n";
        $strSQL .= "            ,YSN_GK5" . "\r\n";
        $strSQL .= "            ,YSN_GK6" . "\r\n";
        $strSQL .= "            ,YSN_GK7" . "\r\n";
        $strSQL .= "            ,YSN_GK8" . "\r\n";
        $strSQL .= "            ,YSN_GK9" . "\r\n";
        $strSQL .= "      FROM  HYOSAN_NEW " . "\r\n";
        $strSQL .= "      WHERE YOSAN_YMD = @KISYUYM   " . "\r\n";
        $strSQL .= "        AND LINE_NO = 10 ) YSN10" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        $strSQL .= "WHERE STYPE.BUSYO_CD = YSN.BUSYO_CD(+) " . "\r\n";
        $strSQL .= "AND STYPE.BUSYO_CD = YSN144.BUSYO_CD(+) " . "\r\n";
        $strSQL .= "AND STYPE.BUSYO_CD = YSN10.BUSYO_CD(+) " . "\r\n";

        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";

        // '-- ﾊﾟﾗﾒｰﾀ --
        // '考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        // '期首年月
        $strSQL = str_replace("@KISYUYM", $this->ClsComFncJKSYS->FncSqlNv($prvKisyuYM), $strSQL);
        // '評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // '評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 20240722 YIN INS S
        // '集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // '集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 20240722 YIN INS E
        // '集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        //    'ﾗｲﾝNO
        $strSQL = str_replace("@LINE_NO", $this->ClsComFncJKSYS->FncSqlNz($intLineNo), $strSQL);

        return parent::insert($strSQL);
    }
    // HSIMRUISEKIKANRより実績情報取得2版
    public function fnc_InsJisseki2($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        // --- Insert --
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        // 評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        // 評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        // 社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        // 集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        // 項目区分
        $strSQL .= "      ,@KOUMOKU" . "\r\n";
        switch ($strType . $strSyukeiKomoku) {
            // サービスアドバイザ-総入庫台数-実績
            case CNS_12 . '21':
            // BPアドバイザ-総入庫台数-実績
            case CNS_15 . '21':
                //総入庫台数:(経営成果管理表.有償入庫台数（10行目）+経営成果管理表.整備・無償台数（70行目）)÷経営成果管理表.総人員（144行目）
                $strSQL .= ",ROUND(DECODE(SUM(NVL(SIM144.TOU_ZAN,0)),0, 0," . "\r\n";
                $strSQL .= "(SUM(NVL(SIM10.TOU_ZAN,0))+SUM(NVL(SIM.TOU_ZAN,0)))/SUM(NVL(SIM144.TOU_ZAN,0))),0) " . "\r\n";
                break;
            // 20250429 lujunxia ins s
            // サービス管理職-サービス総限界利益前年比-実績/基準
            case CNS_06 . '20':
            // サービスアドバイザ-サービス総限界利益前年比-実績/基準
            case CNS_12 . '20':
                //サービス総限界利益前年比:整備・総限界利益（87行目）
                $strSQL .= "      ,ROUND((SUM(NVL(SIM.TOU_ZAN,0))/1000),0) " . "\r\n";
                break;
            // 20250429 lujunxia ins e
            default:
                // サービス総員当りサービス総限界利益:経営成果管理表．整備・総限界利益（87行目）÷経営成果管理表．総人員（144行目）
                $strSQL .= ",DECODE(SUM(NVL(SIM144.TOU_ZAN,0)),0, 0," . "\r\n";
                $strSQL .= "ROUND(SUM(NVL(SIM.TOU_ZAN,0))/SUM(NVL(SIM144.TOU_ZAN,0))/1000,0)) " . "\r\n";
        }

        // 共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        $strSQL .= "FROM (SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,TOU_ZAN" . "\r\n";
        $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
        $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";

        $strSQL .= "        AND LINE_NO = @LINE_NO ) SIM" . "\r\n";
        $strSQL .= "     ,(SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,TOU_ZAN" . "\r\n";
        $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
        $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
        $strSQL .= "        AND LINE_NO = 144) SIM144" . "\r\n";
        $strSQL .= "     ,(SELECT BUSYO_CD" . "\r\n";
        $strSQL .= "            ,TOU_ZAN" . "\r\n";
        $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
        $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
        $strSQL .= "        AND LINE_NO = 10) SIM10" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        $strSQL .= "WHERE STYPE.BUSYO_CD = SIM.BUSYO_CD(+) " . "\r\n";
        $strSQL .= "AND STYPE.BUSYO_CD = SIM144.BUSYO_CD(+) " . "\r\n";
        $strSQL .= "AND STYPE.BUSYO_CD = SIM10.BUSYO_CD(+) " . "\r\n";

        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        // 20250429 lujunxia ins s
        if ($strType == CNS_06 || $strType == CNS_12 || $strType == CNS_13) {
            //サービス拠点
            $strSQL .= "AND STYPE.BUSYO_CD IN(" . SERVICE_BASE . ")" . "\r\n";
        }
        // 20250429 lujunxia ins e
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";
        // '-- ﾊﾟﾗﾒｰﾀ --
        // 考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        // 評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // 評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        // 集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        // 集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        // 項目
        $strSQL = str_replace("@KOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strKomoku), $strSQL);
        //  ﾗｲﾝNO
        $strSQL = str_replace("@LINE_NO", $this->ClsComFncJKSYS->FncSqlNz($intLineNo), $strSQL);

        return parent::insert($strSQL);
    }
    // 20250418 lujunxia ins e
    // HSIMRUISEKIKANRより実績情報取得
    public function fnc_InsJisseki($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $intLineNo, $blnMarume, $prvEndYm, $strBusyoCd = "")
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "";
        // --- Insert --
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        // 評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        // 評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        // 社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        // 集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        // 項目区分
        $strSQL .= "      ,@KOUMOKU" . "\r\n";

        if ($blnMarume) {
            // 値
            $strSQL .= "      ,ROUND((SUM(NVL(SIM.TOU_ZAN,0))/1000),0) " . "\r\n";
        } else {
            // 値
            $strSQL .= "      ,SUM(NVL(SIM.TOU_ZAN,0)) " . "\r\n";
        }
        // 20250418 lujunxia del s
        // 20240722 YIN INS S
        // switch ($strType) {
        //     case CNS_15:
        //         //20240902 lhb upd s
        //         // if ($this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku) == '04' && $this->ClsComFncJKSYS->FncSqlNv($strKomoku) == '01') {
        //         // 	$strSQL .= "      ,ROUND((SUM(NVL(SIM74.TOU_ZAN,0))/1000),0) " . "\r\n";
        //         // } else {
        //         // 	$strSQL .= "      + SUM(NVL(SIM74.TOU_ZAN,0)) " . "\r\n";
        //         // }
        //         $strSQL .= "      + ROUND((SUM(NVL(SIM74.TOU_ZAN,0))/1000),0) " . "\r\n";
        //         //20240902 lhb upd e
        //         break;
        //     default:
        // }
        // 20240722 YIN INS E
        // 20250418 lujunxia del e
        // 共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        //20210302 CI UPD S
        // $strSQL .= "FROM (SELECT BUSYO_CD" . "\r\n";
        // $strSQL .= "            ,TOU_ZAN" . "\r\n";
        // $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
        // $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
        switch ($strType) {
            case CNS_01:
                // 20250508 lujunxia upd s
                // $strSQL .= "FROM (SELECT CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END BUSYO_CD" . "\r\n";
                $strSQL .= "FROM (SELECT BUSYO_CD" . "\r\n";
                // 20250508 lujunxia upd e
                $strSQL .= "            ,SUM(TOU_ZAN) TOU_ZAN" . "\r\n";
                $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
                $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
                $strSQL .= "        AND LINE_NO = @LINE_NO " . "\r\n";
                // 20250508 lujunxia upd s
                // $strSQL .= "      GROUP BY  CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END) SIM" . "\r\n";
                $strSQL .= "      GROUP BY  BUSYO_CD) SIM" . "\r\n";
                // 20250508 lujunxia upd e
                break;
            // 20250418 lujunxia del s
            //20240902 lhb ins s
            // case CNS_15:
            //     $strSQL .= "FROM (SELECT BUSYO_CD" . "\r\n";
            //     $strSQL .= "            ,SUM(NVL(TOU_ZAN,0)) as TOU_ZAN" . "\r\n";
            //     $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
            //     $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            //     break;
            //20240902 lhb ins e
            // 20250418 lujunxia del e
            default:
                // 以外
                $strSQL .= "FROM (SELECT BUSYO_CD" . "\r\n";
                $strSQL .= "            ,TOU_ZAN" . "\r\n";
                $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
                $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
        }
        switch ($strType) {
            case CNS_01:
                //20210302 CI DEL S
                //$strSQL .= "        AND LINE_NO = @LINE_NO ) SIM" . "\r\n";
                //20210302 CI DEL E
                // 店長
                // 整備限界利益は店舗コード下1桁='7'より取得
                if ($strSyukeiKomoku == "11") {
                    $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
                    $strSQL .= "WHERE SUBSTR(STYPE.BUSYO_CD,1,2) || '7' = SIM.BUSYO_CD(+) " . "\r\n";
                } else {
                    $strSQL .= "    , JKKOUKA_KANRI_MST  KANRI" . "\r\n";
                    $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

                    $strSQL .= "WHERE KANRI.MOJI_1 = SIM.BUSYO_CD(+) " . "\r\n";
                    // 20250507 caina upd s
                    // $strSQL .= "  AND STYPE.BUSYO_CD = KANRI.CODE " . "\r\n";
                    $strSQL .= "  AND TO_NUMBER(STYPE.BUSYO_CD) = TO_NUMBER(KANRI.CODE) " . "\r\n";
                    // 20250507 caina upd e
                    $strSQL .= "  AND KANRI.SYUBETU_CD = 'TENPO'  " . "\r\n";
                }
                break;
            case CNS_13:
            // 20240722 YIN INS S
            case CNS_16:
            case CNS_18:
                // 20240722 YIN INS E
                //  ｻｰﾋﾞｽ・ｱﾄﾞﾊﾞｲｻﾞ
                if ($strBusyoCd == "212") {
                    $strSQL .= "  AND (LINE_NO = @LINE_NO" . "\r\n";
                    $strSQL .= "   OR  LINE_NO = 45)" . "\r\n";
                    $strSQL .= "  AND  BUSYO_CD = '212' ) SIM" . "\r\n";
                } else {
                    $strSQL .= "  AND LINE_NO = @LINE_NO" . "\r\n";
                    $strSQL .= "  AND BUSYO_CD <> '212' ) SIM" . "\r\n";
                }
                $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
                $strSQL .= "WHERE STYPE.BUSYO_CD = SIM.BUSYO_CD(+) " . "\r\n";

                if ($strBusyoCd == "212") {
                    $strSQL .= "  AND  STYPE.BUSYO_CD = '212' " . "\r\n";
                } else {
                    $strSQL .= "  AND  STYPE.BUSYO_CD <> '212' " . "\r\n";
                }
                break;
            // 20250418 lujunxia del s
            // 20240722 YIN INS S
            // case CNS_15:
            //     //20240902 lhb upd s
            //     // $strSQL .= "      AND LINE_NO = @LINE_NO ) SIM" . "\r\n";
            //     $strSQL .= "      AND LINE_NO = @LINE_NO GROUP BY BUSYO_CD) SIM" . "\r\n";
            //     $strSQL .= "     ,(SELECT BUSYO_CD" . "\r\n";
            //     // $strSQL .= "            ,TOU_ZAN" . "\r\n";
            //     $strSQL .= "            ,SUM(NVL(TOU_ZAN,0)) as TOU_ZAN" . "\r\n";
            //     $strSQL .= "      FROM  HSIMRUISEKIKANR_KRSS " . "\r\n";
            //     $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            //     // $strSQL .= "        AND LINE_NO = 74 ) SIM74" . "\r\n";
            //     $strSQL .= "        AND LINE_NO = 74 GROUP BY BUSYO_CD) SIM74" . "\r\n";
            //     //20240902 lhb upd e
            //     $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
            //     $strSQL .= "WHERE STYPE.BUSYO_CD = SIM.BUSYO_CD(+) " . "\r\n";
            //     $strSQL .= "AND STYPE.BUSYO_CD = SIM74.BUSYO_CD(+) " . "\r\n";
            //     break;
            // 20240722 YIN INS E
            // 20250418 lujunxia del e
            default:
                // 以外
                $strSQL .= "        AND LINE_NO = @LINE_NO ) SIM" . "\r\n";
                $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

                $strSQL .= "WHERE STYPE.BUSYO_CD = SIM.BUSYO_CD(+) " . "\r\n";
        }
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";
        // '-- ﾊﾟﾗﾒｰﾀ --
        // 考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        // 評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        // 評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        // 集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        // 集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        // 集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        // 項目
        $strSQL = str_replace("@KOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strKomoku), $strSQL);
        //  ﾗｲﾝNO
        $strSQL = str_replace("@LINE_NO", $this->ClsComFncJKSYS->FncSqlNz($intLineNo), $strSQL);

        return parent::insert($strSQL);

    }

    //HSTAFFNINHO_VWより保険実績情報取得
    public function fnc_InsHoken($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //Insert
        $strSQL = "";
        $strSQL .= " INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        //'評価対象開始期間
        $strSQL .= " SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "       ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "       ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "       ,@SYUKEIKOUMOKU" . "\r\n";
        //項目区分
        $strSQL .= "       ,@KOUMOKU" . "\r\n";
        //値
        $strSQL .= "       ,ROUND(SUM(NVL(NIN.NINPO_GK,0))/1000,0) " . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        //20210302 CI UPD S
        switch ($strType) {
            //店長
            case CNS_01:
                // 20250508 lujunxia upd s
                $strSQL .= " FROM (SELECT SUBSTR(BUSYO_CD,1,2) BUSYO_CD" . "\r\n";
                // $strSQL .= "FROM (SELECT SUBSTR(CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END,1,2) BUSYO_CD" . "\r\n";
                // 20250508 lujunxia upd e
                $strSQL .= "           ,SUM(NINPO_GK) NINPO_GK " . "\r\n";
                $strSQL .= "      FROM  HSTAFFNINHO_VW " . "\r\n";
                $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
                // 20250508 lujunxia upd s
                // $strSQL .= "      GROUP BY SUBSTR(CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END,1,2) ) NIN" . "\r\n";
                $strSQL .= "      GROUP BY SUBSTR(BUSYO_CD,1,2) ) NIN" . "\r\n";
                // 20250508 lujunxia upd e
                break;
            //販売課長
            case CNS_02:
                $strSQL .= " FROM (SELECT BUSYO_CD" . "\r\n";
                $strSQL .= "           ,NINPO_GK " . "\r\n";
                $strSQL .= "      FROM  HSTAFFNINHO_VW " . "\r\n";
                $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND ) NIN" . "\r\n";
                break;
            //以外
            default:
                $strSQL .= " FROM (SELECT SYAIN_NO" . "\r\n";
                $strSQL .= "           ,NINPO_GK " . "\r\n";
                $strSQL .= "      FROM  HSTAFFNINHO_VW " . "\r\n";
                $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND ) NIN" . "\r\n";
        }
        // $strSQL .= "            ,NINPO_GK " . "\r\n";
        // $strSQL .= "       FROM  HSTAFFNINHO_VW " . "\r\n";
        // $strSQL .= "       WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND ) NIN" . "\r\n";
        //20210302 CI UPD E
        $strSQL .= "     , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        switch ($strType) {
            //店長
            case CNS_01:
                $strSQL .= " WHERE SUBSTR(STYPE.BUSYO_CD,1,2) = NIN.BUSYO_CD(+)" . "\r\n";
                break;
            //販売課長
            case CNS_02:
                $strSQL .= " WHERE STYPE.BUSYO_CD = NIN.BUSYO_CD(+)" . "\r\n";
                break;
            //以外
            default:
                $strSQL .= " WHERE STYPE.SYAIN_NO = NIN.SYAIN_NO(+)" . "\r\n";
        }
        $strSQL .= "   AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "   AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= " GROUP BY STYPE.SYAIN_NO " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        //項目
        $strSQL = str_replace("@KOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strKomoku), $strSQL);

        return parent::insert($strSQL);
    }

    //HSTAFFNINHO_VW,HGENRI_VWより保険＆クレジット実績情報取得
    public function fnc_InsHokenCredit($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        //項目区分
        $strSQL .= "      ,@KOUMOKU" . "\r\n";
        //値
        $strSQL .= "      ,ROUND((NVL(NIN.NINPO_GK,0) + NVL(GEN.KB,0))/1000,0) " . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        // 20250508 lujunxia upd s
        //20210302 CI UPD S
        $strSQL .= "FROM (SELECT BUSYO_CD " . "\r\n";
        $strSQL .= "            ,SUM(NVL(NINPO_GK,0)) NINPO_GK" . "\r\n";
        $strSQL .= "      FROM  HSTAFFNINHO_VW" . "\r\n";
        $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
        $strSQL .= "      GROUP BY BUSYO_CD ) NIN" . "\r\n";
        $strSQL .= "    ,(SELECT ATUKAI_BUSYO " . "\r\n";
        $strSQL .= "            ,SUM(NVL(KB,0)) KB" . "\r\n";
        $strSQL .= "      FROM  HGENRI_VW" . "\r\n";
        $strSQL .= "      WHERE NENGETU BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
        $strSQL .= "      GROUP BY ATUKAI_BUSYO ) GEN" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        $strSQL .= "WHERE STYPE.BUSYO_CD = NIN.BUSYO_CD(+)" . "\r\n";
        $strSQL .= "  AND STYPE.BUSYO_CD = GEN.ATUKAI_BUSYO(+)" . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        // switch ($strType) {
        // 	case CNS_01:
        // 		$strSQL .= "FROM (SELECT CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END " . "\r\n";
        // 		$strSQL .= "            ,SUM(NVL(NINPO_GK,0)) NINPO_GK" . "\r\n";
        // 		$strSQL .= "      FROM  HSTAFFNINHO_VW" . "\r\n";
        // 		$strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
        // 		$strSQL .= "      GROUP BY CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END ) NIN" . "\r\n";
        // 		$strSQL .= "    ,(SELECT CASE WHEN ATUKAI_BUSYO='181' THEN '441' ELSE ATUKAI_BUSYO END ATUKAI_BUSYO  " . "\r\n";
        // 		$strSQL .= "            ,SUM(NVL(KB,0)) KB" . "\r\n";
        // 		$strSQL .= "      FROM  HGENRI_VW" . "\r\n";
        // 		$strSQL .= "      WHERE NENGETU BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
        // 		$strSQL .= "      GROUP BY CASE WHEN ATUKAI_BUSYO='181' THEN '441' ELSE ATUKAI_BUSYO END ) GEN" . "\r\n";
        // 		$strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        // 		$strSQL .= "WHERE STYPE.BUSYO_CD = NIN.BUSYO_CD(+)" . "\r\n";
        // 		$strSQL .= "  AND STYPE.BUSYO_CD = GEN.ATUKAI_BUSYO(+)" . "\r\n";
        // 		$strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        // 		$strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        // 		break;
        // 	default:
        // 		$strSQL .= "FROM (SELECT BUSYO_CD " . "\r\n";
        // 		$strSQL .= "            ,SUM(NVL(NINPO_GK,0)) NINPO_GK" . "\r\n";
        // 		$strSQL .= "      FROM  HSTAFFNINHO_VW" . "\r\n";
        // 		$strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
        // 		$strSQL .= "      GROUP BY BUSYO_CD ) NIN" . "\r\n";
        // 		$strSQL .= "    ,(SELECT ATUKAI_BUSYO " . "\r\n";
        // 		$strSQL .= "            ,SUM(NVL(KB,0)) KB" . "\r\n";
        // 		$strSQL .= "      FROM  HGENRI_VW" . "\r\n";
        // 		$strSQL .= "      WHERE NENGETU BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
        // 		$strSQL .= "      GROUP BY ATUKAI_BUSYO ) GEN" . "\r\n";
        // 		$strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        // 		$strSQL .= "WHERE STYPE.BUSYO_CD = NIN.BUSYO_CD(+)" . "\r\n";
        // 		$strSQL .= "  AND STYPE.BUSYO_CD = GEN.ATUKAI_BUSYO(+)" . "\r\n";
        // 		$strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        // 		$strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        // }
        //20210302 CI UPD E
        // 20250508 lujunxia upd e
        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        //項目
        $strSQL = str_replace("@KOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strKomoku), $strSQL);

        return parent::insert($strSQL);
    }

    //EXSVCKOUKEN0001より入庫情報取得
    public function fnc_InsNyuko($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $strKomoku, $strNyukoKbn, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        //項目区分
        $strSQL .= "      ,@KOUMOKU" . "\r\n";
        //値
        $strSQL .= "      ,SUM(NVL(SVC.VALUE8,0)) " . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM (SELECT VALUE4" . "\r\n";
        $strSQL .= "            ,VALUE8" . "\r\n";
        if ($strType == CNS_06) {
            //ｻｰﾋﾞｽ管理職
            $strSQL .= "            ,VALUE18" . "\r\n";
        } else {
            //以外
            $strSQL .= "            ,VALUE2" . "\r\n";
        }

        $strSQL .= "      FROM  EXSVCKOUKEN0001 " . "\r\n";
        if ($strType == CNS_06) {
            $strSQL .= "      WHERE VALUE4 IN (@NYUKOKBN)" . "\r\n";
        } else {
            $strSQL .= "      WHERE VALUE4 <= @NYUKOKBN" . "\r\n";
        }

        $strSQL .= "        AND VALUE1 BETWEEN @SUMSTART AND @SUMEND ) SVC" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        if ($strType == CNS_06) {
            //ｻｰﾋﾞｽ管理職
            $strSQL .= "WHERE STYPE.BUSYO_CD = SVC.VALUE18(+) " . "\r\n";
        } else {
            $strSQL .= "WHERE STYPE.SYAIN_NO = SVC.VALUE2(+) " . "\r\n";
        }
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        //項目
        $strSQL = str_replace("@KOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strKomoku), $strSQL);
        //入庫区分
        $strSQL = str_replace("@NYUKOKBN", $this->ClsComFncJKSYS->FncSqlNz($strNyukoKbn), $strSQL);

        return parent::insert($strSQL);
    }

    //JKKOUKA_KOJINMOKUHYOより情報取得
    public function fnc_InsMokuhyo($strType, $strKaisiYm, $strSyukeiKomoku, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        //項目区分
        $strSQL .= "      ,'01'" . "\r\n";
        if ($strSyukeiKomoku == "03") {
            //値
            $strSQL .= "      ,SUM(NVL(MOK.SOUGENKAI,0)) " . "\r\n";
        } else {
            //値
            $strSQL .= "      ,SUM(NVL(MOK.DAISU,0)) " . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        //EXURIDAISU00001《売上達成率》より取得
        $strSQL .= "FROM (SELECT VALUE4 SYAIN_NO" . "\r\n";
        $strSQL .= "            ,SUM(NVL(VALUE6,0)) SOUGENKAI" . "\r\n";
        $strSQL .= "            ,SUM(NVL(VALUE9,0) + NVL(VALUE10,0)) DAISU" . "\r\n";
        $strSQL .= "      FROM  EXURIDAISU00001" . "\r\n";
        $strSQL .= "      WHERE VALUE1 BETWEEN @KIKANSTART AND @KIKANEND " . "\r\n";
        $strSQL .= "      GROUP BY VALUE4 ) MOK" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        $strSQL .= "WHERE STYPE.SYAIN_NO = MOK.SYAIN_NO(+)" . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);

        return parent::insert($strSQL);
    }

    //HSLSSTAFF_VWより実績情報取得
    public function fnc_InsStaff($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";

        switch ($strSyukeiKomoku) {
            case "03":
                //限界利益
                //値
                $strSQL .= "      ,ROUND((SUM(NVL(STF.SOU_GENRI,0))/1000),0) " . "\r\n";
                break;
            case "05":
                //新車台数
                //値
                $strSQL .= "      ,SUM(NVL(STF.SIN_DAISU,0)) " . "\r\n";
                break;
            case "07":
                //中古車台数
                //値
                $strSQL .= "      ,SUM(NVL(STF.CHU_DAISU,0)) " . "\r\n";
                break;
            case "17":
                //固定費ｶﾊﾞｰ率
                $strSQL .= "      ,CASE WHEN SUM(NVL(STF.SOU_GENRI,0)) = 0 OR SUM(NVL(STF.KOTEIHIKEI,0)) = 0 " . "\r\n";
                $strSQL .= "                 THEN 0 " . "\r\n";
                //値
                $strSQL .= "            ELSE ROUND((SUM(STF.SOU_GENRI)/SUM(STF.KOTEIHIKEI)*100),1) " . "\r\n";
                $strSQL .= "       END " . "\r\n";
                break;
            case "18":
                //労働分配率
                $strSQL .= "      ,CASE WHEN SUM(NVL(STF.SOU_JINKEN,0)) = 0 OR SUM(NVL(STF.SOU_GENRI,0)) = 0 " . "\r\n";
                $strSQL .= "                 THEN 0 " . "\r\n";
                //値
                $strSQL .= "            ELSE ROUND((SUM(STF.SOU_JINKEN)/SUM(STF.SOU_GENRI)*100),1) " . "\r\n";
                $strSQL .= "       END " . "\r\n";
                break;
        }

        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM (SELECT SYAIN_NO" . "\r\n";
        $strSQL .= "            ,SOU_GENRI" . "\r\n";
        $strSQL .= "            ,SIN_DAISU" . "\r\n";
        $strSQL .= "            ,CHU_DAISU" . "\r\n";
        $strSQL .= "            ,SOU_JINKEN" . "\r\n";
        $strSQL .= "            ,KOTEIHIKEI" . "\r\n";
        $strSQL .= "      FROM  HSLSSTAFF_VW " . "\r\n";
        $strSQL .= "      WHERE KEIJO_DT BETWEEN @SUMSTART AND @SUMEND ) STF" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        $strSQL .= "WHERE STYPE.SYAIN_NO = STF.SYAIN_NO(+)" . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);

        return parent::insert($strSQL);
    }
    // 20250418 lujunxia del s
    //HSIMRUISEKIKANRより労働分配率取得(ｻｰﾋﾞｽ・ｱﾄﾞﾊﾞｲｻﾞ)
    // public function fnc_InsBunpai($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $strSyukeiKomoku, $prvEndYm)
    // {
    //     $this->ClsComFncJKSYS = new ClsComFncJKSYS();

    //     //--- Insert ---
    //     $strSQL = "";
    //     $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
    //     $strSQL .= $this->GetInssql() . "\r\n";

    //     //評価対象開始期間
    //     $strSQL .= "SELECT @KIKANSTART" . "\r\n";
    //     //評価対象終了期間
    //     $strSQL .= "      ,@KIKANEND" . "\r\n";
    //     //社員番号
    //     $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
    //     //集計項目区分
    //     $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
    //     //項目区分
    //     $strSQL .= "      ,'02'" . "\r\n";
    //     //値
    //     // 20240722 YIN UPD S
    //     // $strSQL .= "      ,CASE WHEN NVL(JINKENHI.TOU_ZAN,0) = 0 OR NVL(SOGENKAI.TOU_ZAN,0) = 0" . "\r\n";
    //     // $strSQL .= "                 THEN 0 " . "\r\n";
    //     // $strSQL .= "            ELSE ROUND(JINKENHI.TOU_ZAN / SOGENKAI.TOU_ZAN * 100,1) " . "\r\n";
    //     // $strSQL .= "       END " . "\r\n";
    //     switch ($strType) {
    //         case CNS_15:
    //             $strSQL .= "      ,CASE WHEN NVL(JINKENHI.TOU_ZAN,0) + NVL(SABISU.TOU_ZAN,0) = 0 OR NVL(SOGENKAI.TOU_ZAN,0) + NVL(SABISU.TOU_ZAN,0) = 0" . "\r\n";
    //             $strSQL .= "                 THEN 0 " . "\r\n";
    //             $strSQL .= "            ELSE ROUND((NVL(JINKENHI.TOU_ZAN,0) + NVL(SABISU.TOU_ZAN,0)) / (NVL(SOGENKAI.TOU_ZAN,0) + NVL(SABISU.TOU_ZAN,0)) * 100,1) " . "\r\n";
    //             $strSQL .= "       END " . "\r\n";
    //             break;

    //         default:
    //             $strSQL .= "      ,CASE WHEN NVL(JINKENHI.TOU_ZAN,0) = 0 OR NVL(SOGENKAI.TOU_ZAN,0) = 0" . "\r\n";
    //             $strSQL .= "                 THEN 0 " . "\r\n";
    //             $strSQL .= "            ELSE ROUND(JINKENHI.TOU_ZAN / SOGENKAI.TOU_ZAN * 100,1) " . "\r\n";
    //             $strSQL .= "       END " . "\r\n";
    //             break;
    //     }
    //     // 20240722 YIN UPD E

    //     //共通部分
    //     $strSQL .= $this->Getsql() . "\r\n";

    //     $strSQL .= "FROM (SELECT BUSYO_CD " . "\r\n";
    //     $strSQL .= "            ,SUM(NVL(TOU_ZAN,0)) TOU_ZAN " . "\r\n";
    //     $strSQL .= "　　　FROM   HSIMRUISEKIKANR_KRSS " . "\r\n";
    //     $strSQL .= "      WHERE  LINE_NO = 93" . "\r\n";
    //     $strSQL .= "        AND  KEIJO_DT BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
    //     $strSQL .= "      GROUP BY BUSYO_CD ) JINKENHI" . "\r\n";
    //     $strSQL .= "    ,(SELECT BUSYO_CD " . "\r\n";
    //     $strSQL .= "            ,SUM(NVL(TOU_ZAN,0)) TOU_ZAN " . "\r\n";
    //     $strSQL .= "　　　FROM   HSIMRUISEKIKANR_KRSS" . "\r\n";
    //     $strSQL .= "      WHERE  LINE_NO = 87" . "\r\n";

    //     $strSQL .= "        AND  KEIJO_DT BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
    //     $strSQL .= "      GROUP BY BUSYO_CD ) SOGENKAI " . "\r\n";
    //     // 20240722 YIN INS S
    //     switch ($strType) {
    //         case CNS_15:
    //             $strSQL .= "    ,(SELECT BUSYO_CD " . "\r\n";
    //             $strSQL .= "            ,SUM(NVL(TOU_ZAN,0)) TOU_ZAN " . "\r\n";
    //             $strSQL .= "　　　FROM   HSIMRUISEKIKANR_KRSS " . "\r\n";
    //             $strSQL .= "      WHERE  LINE_NO = 74" . "\r\n";
    //             $strSQL .= "        AND  KEIJO_DT BETWEEN @SUMSTART AND @SUMEND" . "\r\n";
    //             $strSQL .= "      GROUP BY BUSYO_CD ) SABISU" . "\r\n";
    //             break;
    //         default:
    //             break;
    //     }
    //     // 20240722 YIN INS E
    //     $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

    //     $strSQL .= "WHERE STYPE.BUSYO_CD = JINKENHI.BUSYO_CD(+)" . "\r\n";
    //     $strSQL .= "  AND STYPE.BUSYO_CD = SOGENKAI.BUSYO_CD(+) " . "\r\n";
    //     // 20240722 YIN INS S
    //     switch ($strType) {
    //         case CNS_15:
    //             $strSQL .= "  AND STYPE.BUSYO_CD = SABISU.BUSYO_CD(+) " . "\r\n";
    //             break;
    //         default:
    //             break;
    //     }
    //     // 20240722 YIN INS E
    //     $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
    //     $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

    //     //-- ﾊﾟﾗﾒｰﾀ --
    //     //考課表タイプ
    //     $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
    //     //評価対象期間開始
    //     $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
    //     //評価対象期間終了
    //     $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
    //     //集計開始年月
    //     $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
    //     //集計終了年月
    //     $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
    //     //集計項目
    //     $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);

    //     return parent::insert($strSQL);
    // }
    // 20250418 lujunxia del e
    //HSIMRUISEKIKANRより延べ人員取得
    public function fnc_InsJinin($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";

        $strSQL .= $this->GetInssql() . "\r\n";
        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'00'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        //値
        $strSQL .= "      ,SUM(SIM.TOU_ZAN) " . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        //20210302 CI UPD S
        if ($strType == CNS_01) {
            //店長
            $strSQL .= "FROM  (" . "\r\n";
            $strSQL .= "       SELECT " . "\r\n";
            $strSQL .= "        LINE_NO," . "\r\n";
            // 20250508 lujunxia upd s
            // $strSQL .= "        CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END BUSYO_CD," . "\r\n";
            $strSQL .= "        BUSYO_CD," . "\r\n";
            // 20250508 lujunxia upd e
            $strSQL .= "        SUM(TOU_ZAN) TOU_ZAN " . "\r\n";
            $strSQL .= "       FROM " . "\r\n";
            $strSQL .= "        HSIMRUISEKIKANR_KRSS " . "\r\n";
            $strSQL .= "       WHERE " . "\r\n";
            $strSQL .= "        LINE_NO IN (1,5) " . "\r\n";
            $strSQL .= "        AND KEIJO_DT BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
            $strSQL .= "       GROUP BY " . "\r\n";
            $strSQL .= "        LINE_NO," . "\r\n";
            // 20250508 lujunxia upd s
            // $strSQL .= "        CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END ) SIM" . "\r\n";
            $strSQL .= "        BUSYO_CD ) SIM" . "\r\n";
            // 20250508 lujunxia upd e
            //$strSQL .= "FROM  HSIMRUISEKIKANR_KRSS SIM" . "\r\n";
            $strSQL .= "    , JKKOUKA_KANRI_MST  KANRI" . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
            $strSQL .= "WHERE KANRI.MOJI_1 = SIM.BUSYO_CD " . "\r\n";
            // $strSQL .= "WHERE SIM.LINE_NO IN (1,5)" . "\r\n";
            // $strSQL .= "  AND KANRI.MOJI_1 = SIM.BUSYO_CD " . "\r\n";
            // $strSQL .= "  AND SIM.KEIJO_DT BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "  AND STYPE.BUSYO_CD = KANRI.CODE " . "\r\n";
            $strSQL .= "  AND TO_NUMBER(STYPE.BUSYO_CD) = TO_NUMBER(KANRI.CODE) " . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "  AND KANRI.SYUBETU_CD = 'TENPO'  " . "\r\n";
        } else {
            //店長以外
            $strSQL .= "FROM  HSIMRUISEKIKANR_KRSS SIM" . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE SIM.LINE_NO IN (1,5)" . "\r\n";
            $strSQL .= "  AND STYPE.BUSYO_CD = SIM.BUSYO_CD " . "\r\n";
            $strSQL .= "  AND SIM.KEIJO_DT BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
        }
        //20210302 CI UPD E
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);

        return parent::insert($strSQL);
    }

    //JKKOUKA_BODYCOATINGよりﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ件数取得
    public function fnc_InsBodyCoat($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $intKikan, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'01'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        if ($strType < CNS_07) {
            //管理職
            $strSQL .= "      ,CASE WHEN NVL(BDC.KENSU,0) = 0 OR NVL(JIN.ATAI,0) = 0 " . "\r\n";
            $strSQL .= "                 THEN 0 " . "\r\n";
            //値
            $strSQL .= "            ELSE ROUND(NVL(BDC.KENSU,0) / NVL(JIN.ATAI,0) * @KIKAN,1)" . "\r\n";
            $strSQL .= "       END" . "\r\n";
        } else {
            //営業
            //値
            $strSQL .= "      ,NVL(BDC.KENSU,0) " . "\r\n";
        }

        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        if ($strType < CNS_07) {
            //管理職
            $strSQL .= "FROM (SELECT SYAIN_NO " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,ATAI " . "\r\n";
            $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "      FROM  JKKOUKA_SYUHEN_RIEKI" . "\r\n";
            $strSQL .= "      WHERE KOMOKU_KBN = '02'  " . "\r\n";
            $strSQL .= "        AND SYUKEI_KOMOKU_KBN = '00' " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JIN" . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "    ,(SELECT SUBSTR(KYOTN_CD,1,2) KYOTN_CD" . "\r\n";
                //$strSQL .= "    ,(SELECT SUBSTR(CASE WHEN KYOTN_CD='181' THEN '441' ELSE KYOTN_CD END ,1,2) KYOTN_CD" . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "    ,(SELECT KYOTN_CD " . "\r\n";
            }

            $strSQL .= "            ,COUNT(CSRNO) KENSU " . "\r\n";
            $strSQL .= "      FROM  JKKOUKA_BODYCOATING" . "\r\n";
            $strSQL .= "      WHERE TO_CHAR(HANBAIBI,'YYYYMM') BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "      GROUP BY SUBSTR(KYOTN_CD,1,2) ) BDC " . "\r\n";
                // $strSQL .= "      GROUP BY SUBSTR(CASE WHEN KYOTN_CD='181' THEN '441' ELSE KYOTN_CD END,1,2) ) BDC " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "      GROUP BY KYOTN_CD ) BDC " . "\r\n";
            }

            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
            if ($strType == CNS_01) {
                //店長
                $strSQL .= "  AND SUBSTR(STYPE.BUSYO_CD,1,2) = BDC.KYOTN_CD(+) " . "\r\n";
            } else {
                //店長以外
                $strSQL .= "  AND STYPE.BUSYO_CD = BDC.KYOTN_CD(+) " . "\r\n";
            }
        } else {
            $strSQL .= "FROM (SELECT TANTOUSYA_CD " . "\r\n";
            $strSQL .= "            ,COUNT(CSRNO) KENSU " . "\r\n";
            $strSQL .= "      FROM  JKKOUKA_BODYCOATING" . "\r\n";
            $strSQL .= "      WHERE TO_CHAR(HANBAIBI,'YYYYMM') BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            $strSQL .= "      GROUP BY TANTOUSYA_CD ) BDC " . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = BDC.TANTOUSYA_CD(+) " . "\r\n";
        }

        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計期間
        $strSQL = str_replace("@KIKAN", $this->ClsComFncJKSYS->FncSqlNz($intKikan), $strSQL);

        return parent::insert($strSQL);
    }

    //HGENRI_VWよりｸﾚｼﾞｯﾄKB金額取得
    public function fnc_InsCredit($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $intKikan, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        if ($strType < CNS_07) {
            //管理職
            $strSQL .= "      ,CASE WHEN NVL(GENRI.TOTAL,0) = 0 OR NVL(JIN.ATAI,0) = 0 " . "\r\n";
            $strSQL .= "                 THEN 0 " . "\r\n";
            $strSQL .= "            ELSE ROUND(NVL(GENRI.TOTAL,0) / NVL(JIN.ATAI,0) * @KIKAN,1)" . "\r\n";
            //値
            $strSQL .= "       END" . "\r\n";
        } else {
            //営業
            //値
            $strSQL .= "      ,NVL(GENRI.TOTAL,0) " . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        if ($strType < CNS_07) {
            //管理職
            $strSQL .= "FROM (SELECT SYAIN_NO " . "\r\n";
            // 20250507 caina upd s
            //  $strSQL .= "            ,ATAI " . "\r\n";
            $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "      FROM  JKKOUKA_SYUHEN_RIEKI" . "\r\n";
            $strSQL .= "      WHERE KOMOKU_KBN = '02'  " . "\r\n";
            $strSQL .= "        AND SYUKEI_KOMOKU_KBN = '00' " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JIN" . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "    ,(SELECT SUBSTR(ATUKAI_BUSYO,1,2) ATUKAI_BUSYO " . "\r\n";
                // $strSQL .= "    ,(SELECT SUBSTR(CASE WHEN ATUKAI_BUSYO='181' THEN '441' ELSE ATUKAI_BUSYO END,1,2) ATUKAI_BUSYO " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "    ,(SELECT ATUKAI_BUSYO " . "\r\n";
            }
            $strSQL .= "            ,SUM(KB) TOTAL" . "\r\n";
            $strSQL .= "      FROM  HGENRI_VW" . "\r\n";
            $strSQL .= "      WHERE NENGETU BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "      GROUP BY SUBSTR(ATUKAI_BUSYO,1,2) ) GENRI " . "\r\n";
                // $strSQL .= "      GROUP BY SUBSTR(CASE WHEN ATUKAI_BUSYO='181' THEN '441' ELSE ATUKAI_BUSYO END,1,2) ) GENRI " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "      GROUP BY ATUKAI_BUSYO ) GENRI " . "\r\n";
            }
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
            if ($strType == CNS_01) {
                //店長
                $strSQL .= "  AND SUBSTR(STYPE.BUSYO_CD,1,2) = GENRI.ATUKAI_BUSYO(+) " . "\r\n";
            } else {
                //店長以外
                $strSQL .= "  AND STYPE.BUSYO_CD = GENRI.ATUKAI_BUSYO(+) " . "\r\n";
            }
        } else {
            $strSQL .= "FROM (SELECT ATUKAI_SYAIN " . "\r\n";
            $strSQL .= "            ,SUM(KB) TOTAL " . "\r\n";
            $strSQL .= "      FROM  HGENRI_VW" . "\r\n";
            $strSQL .= "      WHERE NENGETU BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            $strSQL .= "      GROUP BY ATUKAI_SYAIN ) GENRI " . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = GENRI.ATUKAI_SYAIN(+)" . "\r\n";
        }

        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計期間
        $strSQL = str_replace("@KIKAN", $this->ClsComFncJKSYS->FncSqlNz($intKikan), $strSQL);
        return parent::insert($strSQL);
    }

    //EXSAILEASE00001より再ﾘｰｽ金額取得
    public function fnc_InsSaiLease($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $intKikan, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'03'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        if ($strType < CNS_07) {
            //管理職
            $strSQL .= "      ,CASE WHEN NVL(SAIL.TOTAL,0) = 0 OR NVL(JIN.ATAI,0) = 0 " . "\r\n";
            $strSQL .= "                 THEN 0 " . "\r\n";
            //値
            $strSQL .= "            ELSE ROUND(NVL(SAIL.TOTAL,0) / NVL(JIN.ATAI,0) * @KIKAN,1)" . "\r\n";
            $strSQL .= "       END " . "\r\n";
        } else {
            //営業
            //値
            $strSQL .= "      ,NVL(SAIL.TOTAL,0) " . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";
        if ($strType < CNS_07) {
            //管理職
            $strSQL .= "FROM (SELECT SYAIN_NO " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,ATAI " . "\r\n";
            $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "      FROM  JKKOUKA_SYUHEN_RIEKI" . "\r\n";
            $strSQL .= "      WHERE KOMOKU_KBN = '02'  " . "\r\n";
            $strSQL .= "        AND SYUKEI_KOMOKU_KBN = '00' " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JIN" . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "    ,(SELECT SUBSTR(VALUE2,1,2) VALUE2 " . "\r\n";
                // $strSQL .= "    ,(SELECT SUBSTR(CASE WHEN VALUE2='181' THEN '441' ELSE VALUE2 END,1,2) VALUE2 " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "    ,(SELECT VALUE2 " . "\r\n";
            }
            $strSQL .= "            ,SUM(NVL(VALUE6,0)) TOTAL " . "\r\n";
            $strSQL .= "      FROM  EXSAILEASE00001" . "\r\n";
            $strSQL .= "      WHERE VALUE1 BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "      GROUP BY SUBSTR(VALUE2,1,2) ) SAIL " . "\r\n";
                // $strSQL .= "      GROUP BY SUBSTR(CASE WHEN VALUE2='181' THEN '441' ELSE VALUE2 END,1,2) ) SAIL " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "      GROUP BY VALUE2 ) SAIL " . "\r\n";
            }
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
            if ($strType == CNS_01) {
                //店長
                $strSQL .= "  AND SUBSTR(STYPE.BUSYO_CD,1,2) = SAIL.VALUE2(+) " . "\r\n";
            } else {
                //店長以外
                $strSQL .= "  AND STYPE.BUSYO_CD = SAIL.VALUE2(+) " . "\r\n";
            }
        } else {
            $strSQL .= "FROM (SELECT VALUE4 " . "\r\n";
            $strSQL .= "            ,SUM(NVL(VALUE6,0)) TOTAL " . "\r\n";
            $strSQL .= "      FROM  EXSAILEASE00001" . "\r\n";
            $strSQL .= "      WHERE VALUE1 BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
            $strSQL .= "      GROUP BY VALUE4 ) SAIL " . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = SAIL.VALUE4(+)" . "\r\n";
        }
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計期間
        $strSQL = str_replace("@KIKAN", $this->ClsComFncJKSYS->FncSqlNz($intKikan), $strSQL);

        return parent::insert($strSQL);
    }

    // EXPACKDEMENTE01よりﾊﾟｯｸdeﾒﾝﾃ件数取得
    public function fnc_InsPackdeMente($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $intKikan, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'04'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        if ($strType < CNS_07 || $strType == CNS_12) {
            //管理職
            $strSQL .= "      ,CASE WHEN NVL(MENTE.KENSU,0) = 0 OR NVL(JIN.ATAI,0) = 0 " . "\r\n";
            $strSQL .= "                 THEN 0 " . "\r\n";
            //値
            $strSQL .= "            ELSE ROUND(NVL(MENTE.KENSU,0) / NVL(JIN.ATAI,0) * @KIKAN,1)" . "\r\n";

            $strSQL .= "       END " . "\r\n";
        } else {
            //営業
            //値
            $strSQL .= "      ,NVL(MENTE.KENSU,0) " . "\r\n";
        }

        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        if ($strType < CNS_07 || $strType == CNS_12) {
            //管理職
            $strSQL .= "FROM (SELECT SYAIN_NO " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,ATAI " . "\r\n";
            $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "      FROM  JKKOUKA_SYUHEN_RIEKI" . "\r\n";
            $strSQL .= "      WHERE KOMOKU_KBN = '02'  " . "\r\n";
            $strSQL .= "        AND SYUKEI_KOMOKU_KBN = '00' " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JIN" . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "    ,(SELECT SUBSTR(VALUE2,1,2) VALUE2" . "\r\n";
                // $strSQL .= "    ,(SELECT SUBSTR(CASE WHEN VALUE2='181' THEN '441' ELSE VALUE2 END ,1,2) VALUE2" . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "    ,(SELECT VALUE2 " . "\r\n";
            }
            $strSQL .= "            ,SUM(NVL(VALUE11,0)) KENSU " . "\r\n";
            $strSQL .= "      FROM  EXPACKDEMENTE01" . "\r\n";
            $strSQL .= "      WHERE VALUE1 BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "      GROUP BY SUBSTR(VALUE2,1,2) ) MENTE " . "\r\n";
                // $strSQL .= "      GROUP BY SUBSTR(CASE WHEN VALUE2='181' THEN '441' ELSE VALUE2 END,1,2) ) MENTE " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "      GROUP BY VALUE2 ) MENTE " . "\r\n";
            }
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
            if ($strType == CNS_01) {
                //店長
                $strSQL .= "  AND SUBSTR(STYPE.BUSYO_CD,1,2) = MENTE.VALUE2(+) " . "\r\n";
            } else {
                //店長以外
                $strSQL .= "  AND STYPE.BUSYO_CD = MENTE.VALUE2(+) " . "\r\n";
            }
        } else {
            $strSQL .= "FROM (SELECT VALUE4 " . "\r\n";
            $strSQL .= "            ,SUM(NVL(VALUE11,0)) KENSU " . "\r\n";
            $strSQL .= "      FROM  EXPACKDEMENTE01" . "\r\n";
            $strSQL .= "      WHERE VALUE1 BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            $strSQL .= "      GROUP BY VALUE4 ) MENTE " . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
            $strSQL .= "WHERE STYPE.SYAIN_NO = MENTE.VALUE4(+)" . "\r\n";
        }
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計期間
        $strSQL = str_replace("@KIKAN", $this->ClsComFncJKSYS->FncSqlNz($intKikan), $strSQL);

        return parent::insert($strSQL);
    }

    //JKKOUKA_ENCHOHOSYOよりﾊﾟｯｸde753件数取得
    public function fnc_InsPackde753($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $intKikan, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'05'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        if ($strType < CNS_07 || $strType == CNS_12) {
            //管理職
            $strSQL .= "      ,CASE WHEN NVL(ENCHO.KENSU,0) = 0 OR NVL(JIN.ATAI,0) = 0 " . "\r\n";
            $strSQL .= "                 THEN 0 " . "\r\n";
            $strSQL .= "            ELSE ROUND(NVL(ENCHO.KENSU,0) / NVL(JIN.ATAI,0) * @KIKAN,1)" . "\r\n";
            //値
            $strSQL .= "       END " . "\r\n";
        } else {
            //営業
            //値
            $strSQL .= "      ,NVL(ENCHO.KENSU,0) " . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        if ($strType < CNS_07 || $strType == CNS_12) {
            //管理職
            $strSQL .= "FROM (SELECT SYAIN_NO " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,ATAI " . "\r\n";
            $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "      FROM  JKKOUKA_SYUHEN_RIEKI" . "\r\n";
            $strSQL .= "      WHERE KOMOKU_KBN = '02'  " . "\r\n";
            $strSQL .= "        AND SYUKEI_KOMOKU_KBN = '00' " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JIN" . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "    ,(SELECT SUBSTR(BUSYO_CD,1,2) BUSYO_CD " . "\r\n";
                // $strSQL .= "    ,(SELECT SUBSTR(CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END,1,2) BUSYO_CD " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "    ,(SELECT BUSYO_CD " . "\r\n";
            }
            $strSQL .= "            ,COUNT(ID) KENSU " . "\r\n";
            $strSQL .= "      FROM  JKKOUKA_ENCHOHOSYO" . "\r\n";
            $strSQL .= "      WHERE TO_CHAR(KANYU_YMD,'YYYYMM') BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "      GROUP BY SUBSTR(BUSYO_CD,1,2) ) ENCHO " . "\r\n";
                // $strSQL .= "      GROUP BY SUBSTR(CASE WHEN BUSYO_CD='181' THEN '441' ELSE BUSYO_CD END,1,2) ) ENCHO " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "      GROUP BY BUSYO_CD ) ENCHO " . "\r\n";
            }
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
            if ($strType == CNS_01) {
                //店長
                $strSQL .= "  AND SUBSTR(STYPE.BUSYO_CD,1,2) = ENCHO.BUSYO_CD(+) " . "\r\n";
            } else {
                //店長以外
                $strSQL .= "  AND STYPE.BUSYO_CD = ENCHO.BUSYO_CD(+) " . "\r\n";
            }
        } else {
            $strSQL .= "FROM (SELECT TANTOUSYA_CD " . "\r\n";
            $strSQL .= "            ,COUNT(ID) KENSU " . "\r\n";
            $strSQL .= "      FROM  JKKOUKA_ENCHOHOSYO" . "\r\n";
            $strSQL .= "      WHERE TO_CHAR(KANYU_YMD,'YYYYMM') BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            $strSQL .= "      GROUP BY TANTOUSYA_CD ) ENCHO " . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = ENCHO.TANTOUSYA_CD(+)" . "\r\n";
        }

        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計期間
        $strSQL = str_replace("@KIKAN", $this->ClsComFncJKSYS->FncSqlNz($intKikan), $strSQL);

        return parent::insert($strSQL);
    }

    //  EXJAF0000000001よりJAF件数取得
    public function fnc_InsJAF($strType, $strKaisiYm, $strSumStartYm, $strSumEndYm, $intKikan, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'06'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'02'" . "\r\n";
        if ($strType < CNS_07 || $strType == CNS_12) {
            //管理職
            $strSQL .= "      ,CASE WHEN NVL(JAF.KENSU,0) = 0 OR NVL(JIN.ATAI,0) = 0 " . "\r\n";
            $strSQL .= "                 THEN 0 " . "\r\n";
            //値
            $strSQL .= "            ELSE ROUND(NVL(JAF.KENSU,0) / NVL(JIN.ATAI,0) * @KIKAN,1)" . "\r\n";
            $strSQL .= "       END" . "\r\n";
        } else {
            //営業
            //値
            $strSQL .= "      ,NVL(JAF.KENSU,0) " . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        if ($strType < CNS_07 || $strType == CNS_12) {
            //管理職
            $strSQL .= "FROM (SELECT SYAIN_NO " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,ATAI " . "\r\n";
            $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "      FROM  JKKOUKA_SYUHEN_RIEKI" . "\r\n";
            $strSQL .= "      WHERE KOMOKU_KBN = '02'  " . "\r\n";
            $strSQL .= "        AND SYUKEI_KOMOKU_KBN = '00' " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JIN" . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "    ,(SELECT  SUBSTR(VALUE2,1,2) VALUE2 " . "\r\n";
                //$strSQL .= "    ,(SELECT  SUBSTR(CASE WHEN VALUE2='181' THEN '441' ELSE VALUE2 END,1,2) VALUE2 " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "    ,(SELECT VALUE2 " . "\r\n";
            }
            $strSQL .= "            ,SUM(NVL(VALUE6,0)) KENSU " . "\r\n";
            $strSQL .= "      FROM  EXJAF0000000001" . "\r\n";
            $strSQL .= "      WHERE VALUE1 BETWEEN @SUMSTART AND @SUMEND " . "\r\n";
            if ($strType == CNS_01) {
                // 20250508 lujunxia upd s
                //20210302 CI UPD S
                $strSQL .= "      GROUP BY SUBSTR(VALUE2,1,2) ) JAF " . "\r\n";
                // $strSQL .= "      GROUP BY SUBSTR(CASE WHEN VALUE2='181' THEN '441' ELSE VALUE2 END,1,2) ) JAF " . "\r\n";
                //20210302 CI UPD E
                // 20250508 lujunxia upd e
            } else {
                $strSQL .= "      GROUP BY VALUE2 ) JAF " . "\r\n";
            }
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

            $strSQL .= "WHERE STYPE.SYAIN_NO = JIN.SYAIN_NO" . "\r\n";
            if ($strType == CNS_01) {
                //店長
                $strSQL .= "  AND SUBSTR(STYPE.BUSYO_CD,1,2) = JAF.VALUE2(+) " . "\r\n";
            } else {
                //店長以外
                $strSQL .= "  AND STYPE.BUSYO_CD = JAF.VALUE2(+) " . "\r\n";
            }
        } else {
            $strSQL .= "FROM (SELECT VALUE4 " . "\r\n";
            $strSQL .= "            ,SUM(NVL(VALUE6,0)) KENSU " . "\r\n";
            $strSQL .= "      FROM  EXJAF0000000001" . "\r\n";
            $strSQL .= "      WHERE VALUE1 BETWEEN @SUMSTART AND @SUMEND  " . "\r\n";
            $strSQL .= "      GROUP BY VALUE4 ) JAF " . "\r\n";
            $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
            $strSQL .= "WHERE STYPE.SYAIN_NO = JAF.VALUE4(+)" . "\r\n";
        }

        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計開始年月
        $strSQL = str_replace("@SUMSTART", $this->ClsComFncJKSYS->FncSqlNv($strSumStartYm), $strSQL);
        //集計終了年月
        $strSQL = str_replace("@SUMEND", $this->ClsComFncJKSYS->FncSqlNv($strSumEndYm), $strSQL);
        //集計期間
        $strSQL = str_replace("@KIKAN", $this->ClsComFncJKSYS->FncSqlNz($intKikan), $strSQL);

        return parent::insert($strSQL);
    }

    //達成率取得
    public function fnc_InsTassei_Ritu($strType, $strKaisiYm, $strSyukeiKomoku, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        //項目区分
        $strSQL .= "      ,'03'" . "\r\n";
        //値
        $strSQL .= "      ,CASE WHEN NVL(KIJUN.ATAI,0) = 0 OR NVL(JISSEKI.ATAI,0) = 0 " . "\r\n";

        $strSQL .= "                 THEN 0 " . "\r\n";
        $strSQL .= "            WHEN KIJUN.ATAI > 0 AND JISSEKI.ATAI > 0" . "\r\n";
        $strSQL .= "                 THEN ROUND(JISSEKI.ATAI / KIJUN.ATAI * 100,1) " . "\r\n";
        $strSQL .= "            WHEN KIJUN.ATAI < JISSEKI.ATAI  " . "\r\n";
        $strSQL .= "                 THEN ROUND((JISSEKI.ATAI - KIJUN.ATAI) / ABS(KIJUN.ATAI) * 100,1) " . "\r\n";
        $strSQL .= "            WHEN KIJUN.ATAI > JISSEKI.ATAI  " . "\r\n";
        $strSQL .= "                 THEN ROUND((KIJUN.ATAI - JISSEKI.ATAI) / ABS(KIJUN.ATAI) * -100,1) " . "\r\n";
        //20210302 CI INS S
        $strSQL .= "            WHEN KIJUN.ATAI = JISSEKI.ATAI THEN 100 " . "\r\n";
        //20210302 CI INS E
        $strSQL .= "       END " . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM (SELECT SYAIN_NO  " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "            ,ATAI " . "\r\n";
        $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "      FROM JKKOUKA_JISSEKI_SYUKEI" . "\r\n";
        $strSQL .= "      WHERE KOMOKU_KBN = '01'" . "\r\n";
        $strSQL .= "        AND SYUKEI_KOMOKU_KBN = @SYUKEIKOUMOKU" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) KIJUN" . "\r\n";
        $strSQL .= "    ,(SELECT SYAIN_NO  " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "            ,ATAI " . "\r\n";
        $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "      FROM JKKOUKA_JISSEKI_SYUKEI" . "\r\n";
        $strSQL .= "      WHERE KOMOKU_KBN = '02'" . "\r\n";
        $strSQL .= "        AND SYUKEI_KOMOKU_KBN = @SYUKEIKOUMOKU" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JISSEKI" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        $strSQL .= "WHERE JISSEKI.SYAIN_NO = KIJUN.SYAIN_NO " . "\r\n";
        $strSQL .= "  AND STYPE.SYAIN_NO = JISSEKI.SYAIN_NO " . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);
        return parent::insert($strSQL);
    }

    //実績_総数設定
    public function fnc_InsTotal($strType, $strKaisiYm, $strSyukeiKomoku, $prvEndYm, $rdoExct_Grop)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        //--- Insert ---
        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,@SYUKEIKOUMOKU" . "\r\n";
        //項目区分
        $strSQL .= "      ,'07'" . "\r\n";
        //値
        $strSQL .= "      ,TOTAL.KENSU" . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM  JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";
        $strSQL .= "    ,(SELECT COUNT(SYAIN_NO) KENSU" . "\r\n";
        if ($rdoExct_Grop == 'true') {
            $strSQL .= "            ,GROUP_CD  " . "\r\n";
        }
        $strSQL .= "       FROM  JKKOUKA_SYAIN_TYPE " . "\r\n";
        $strSQL .= "       WHERE KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        if ($rdoExct_Grop == 'true') {
            $strSQL .= "         AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "       GROUP BY GROUP_CD ) TOTAL " . "\r\n";
        } else {
            $strSQL .= "         AND HYOUKA_KIKAN_END = @KIKANEND ) TOTAL " . "\r\n";

        }

        $strSQL .= "WHERE STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        if ($rdoExct_Grop == 'true') {
            $strSQL .= "  AND STYPE.GROUP_CD =  TOTAL.GROUP_CD " . "\r\n";
        }

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //集計項目
        $strSQL = str_replace("@SYUKEIKOUMOKU", $this->ClsComFncJKSYS->FncSqlNv($strSyukeiKomoku), $strSQL);

        return parent::insert($strSQL);
    }

    //実績_順位設定
    public function fnc_InsRank($strType, $strKaisiYm, $prvEndYm, $rdoExct_Type, $blnJisseki = false)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,JISSEKI.SYUKEI_KOMOKU_KBN" . "\r\n";
        //項目区分
        $strSQL .= "      ,'05'" . "\r\n";
        //--- 労働分配率：昇順　以外：降順 ---
        if ($rdoExct_Type == 'true') {
            $strSQL .= "      ,CASE WHEN JISSEKI.SYUKEI_KOMOKU_KBN = '18'" . "\r\n";
            //値
            // 20250507 caina upd s
            // $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY JISSEKI.ATAI)" . "\r\n";
            $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(JISSEKI.ATAI))" . "\r\n";
            //値
            // $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY JISSEKI.ATAI DESC)" . "\r\n";
            $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(JISSEKI.ATAI) DESC)" . "\r\n";
            $strSQL .= "       END" . "\r\n";
        } else {
            $strSQL .= "      ,CASE WHEN JISSEKI.SYUKEI_KOMOKU_KBN = '18'" . "\r\n";
            // $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.GROUP_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY JISSEKI.ATAI)" . "\r\n";
            $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.GROUP_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(JISSEKI.ATAI))" . "\r\n";
            // $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.GROUP_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY JISSEKI.ATAI DESC)" . "\r\n";
            $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.GROUP_CD,JISSEKI.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(JISSEKI.ATAI) DESC)" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "       END" . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM  JKKOUKA_JISSEKI_SYUKEI JISSEKI " . "\r\n";
        $strSQL .= "     ,JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        if ($blnJisseki) {
            $strSQL .= "WHERE JISSEKI.KOMOKU_KBN = '02'  " . "\r\n";
        } else {
            $strSQL .= "WHERE JISSEKI.KOMOKU_KBN = '03'  " . "\r\n";
        }
        $strSQL .= "  AND STYPE.SYAIN_NO = JISSEKI.SYAIN_NO  " . "\r\n";
        if ($strType == CNS_12) {
            if ($blnJisseki) {
                $strSQL .= "  AND JISSEKI.SYUKEI_KOMOKU_KBN = '18'  " . "\r\n";
            } else {
                $strSQL .= "  AND JISSEKI.SYUKEI_KOMOKU_KBN <> '18'  " . "\r\n";
            }
        } else {
            $strSQL .= "  AND JISSEKI.SYUKEI_KOMOKU_KBN <> '18'  " . "\r\n";
        }
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = JISSEKI.HYOUKA_KIKAN_END  " . "\r\n";
        $strSQL .= "  AND JISSEKI.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);

        return parent::insert($strSQL);
    }

    //実績_達成度設定
    public function fnc_InsTasseido($strType, $strKaisiYm, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,JISSEKI.SYUKEI_KOMOKU_KBN" . "\r\n";
        //項目区分
        $strSQL .= "      ,'06'" . "\r\n";
        //値
        $strSQL .= "      ,MAX(TASSEI.CODE)" . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM (SELECT TO_NUMBER(MST.CODE) CODE " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "            ,MST.SUUTI_2 * TAISYO.ATAI JUNNI" . "\r\n";
        $strSQL .= "            ,TO_NUMBER(MST.SUUTI_2) * TO_NUMBER(TAISYO.ATAI) JUNNI" . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "            ,TAISYO.SYAIN_NO " . "\r\n";
        $strSQL .= "      FROM   JKKOUKA_KANRI_MST MST " . "\r\n";
        $strSQL .= "           ,(SELECT MIN(TO_NUMBER(KANRI.SYUBETU_CD)) SYUBETU_CD " . "\r\n";
        $strSQL .= "                   ,JISSEKI.SYAIN_NO" . "\r\n";
        $strSQL .= "                   ,JISSEKI.ATAI" . "\r\n";
        $strSQL .= "             FROM JKKOUKA_KANRI_MST KANRI" . "\r\n";
        $strSQL .= "                 ,JKKOUKA_JISSEKI_SYUKEI JISSEKI " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "             WHERE JISSEKI.ATAI <= TO_NUMBER(KANRI.SYUBETU_CD) " . "\r\n";
        $strSQL .= "             WHERE TO_NUMBER(JISSEKI.ATAI) <= TO_NUMBER(KANRI.SYUBETU_CD) " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "               AND KANRI.SYUBETU_CD BETWEEN '1' AND '999' " . "\r\n";
        $strSQL .= "               AND JISSEKI.KOMOKU_KBN = '07' " . "\r\n";
        $strSQL .= "               AND JISSEKI.SYUKEI_KOMOKU_KBN = '17' " . "\r\n";
        $strSQL .= "               AND JISSEKI.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "               AND JISSEKI.HYOUKA_KIKAN_START = @KIKANSTART " . "\r\n";
        $strSQL .= "             GROUP BY JISSEKI.SYAIN_NO " . "\r\n";
        $strSQL .= "                     ,JISSEKI.ATAI) TAISYO  " . "\r\n";
        $strSQL .= "      WHERE TAISYO.SYUBETU_CD = TO_NUMBER(MST.SYUBETU_CD) " . "\r\n";
        $strSQL .= "        AND MST.SYUBETU_CD BETWEEN '1' AND '999' ) TASSEI" . "\r\n";
        $strSQL .= "     ,JKKOUKA_JISSEKI_SYUKEI JISSEKI " . "\r\n";
        $strSQL .= "     ,JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        // 20250507 caina upd s
        // $strSQL .= "WHERE JISSEKI.ATAI <= TASSEI.JUNNI " . "\r\n";
        $strSQL .= "WHERE TO_NUMBER(JISSEKI.ATAI) <= TO_NUMBER(TASSEI.JUNNI) " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "  AND JISSEKI.SYAIN_NO = TASSEI.SYAIN_NO " . "\r\n";
        $strSQL .= "  AND JISSEKI.KOMOKU_KBN = '05' " . "\r\n";
        $strSQL .= "  AND STYPE.SYAIN_NO = JISSEKI.SYAIN_NO  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = JISSEKI.HYOUKA_KIKAN_END  " . "\r\n";
        $strSQL .= "  AND JISSEKI.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";
        $strSQL .= "        ,JISSEKI.SYUKEI_KOMOKU_KBN " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);

        return parent::insert($strSQL);
    }

    //周辺利益_実績総数設定
    public function fnc_InsTotal_Syuhen($strType, $strKaisiYm, $prvEndYm, $rdoExct_Grop)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,SYUHEN.SYUKEI_KOMOKU_KBN" . "\r\n";
        //項目区分
        $strSQL .= "      ,'07'" . "\r\n";
        //値
        $strSQL .= "      ,TOTAL.KENSU" . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM  JKKOUKA_SYUHEN_RIEKI SYUHEN " . "\r\n";
        $strSQL .= "     ,(SELECT COUNT(SYAIN_NO) KENSU" . "\r\n";
        if ($rdoExct_Grop == 'true') {
            $strSQL .= "            ,GROUP_CD  " . "\r\n";
        }
        $strSQL .= "       FROM  JKKOUKA_SYAIN_TYPE " . "\r\n";
        $strSQL .= "       WHERE KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        if ($rdoExct_Grop == 'true') {
            $strSQL .= "         AND HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "       GROUP BY GROUP_CD ) TOTAL " . "\r\n";
        } else {
            $strSQL .= "         AND HYOUKA_KIKAN_END = @KIKANEND ) TOTAL " . "\r\n";
        }
        $strSQL .= "     ,JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        $strSQL .= "WHERE SYUHEN.SYUKEI_KOMOKU_KBN > '00'  " . "\r\n";
        $strSQL .= "  AND SYUHEN.KOMOKU_KBN = '02'  " . "\r\n";
        $strSQL .= "  AND STYPE.SYAIN_NO = SYUHEN.SYAIN_NO  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = SYUHEN.HYOUKA_KIKAN_END  " . "\r\n";
        if ($rdoExct_Grop == 'true') {
            $strSQL .= "  AND STYPE.GROUP_CD =  TOTAL.GROUP_CD " . "\r\n";
        }
        $strSQL .= "  AND SYUHEN.HYOUKA_KIKAN_START =  @KIKANSTART " . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);

        return parent::insert($strSQL);
    }

    //周辺利益_順位設定
    public function fnc_InsRank_Syuhen($strType, $strKaisiYm, $prvEndYm, $rdoExct_Type, $blnSyuhenRieki = false)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,SYUHEN.SYUKEI_KOMOKU_KBN" . "\r\n";
        //項目区分
        $strSQL .= "      ,'05'" . "\r\n";
        //--- 周辺利益指数：昇順　以外：降順 ---
        if ($rdoExct_Type == 'true') {
            $strSQL .= "      ,CASE WHEN SYUHEN.SYUKEI_KOMOKU_KBN = '99' " . "\r\n";
            //値
            // 20250507 caina upd s
            // $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY SYUHEN.ATAI)" . "\r\n";
            $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(SYUHEN.ATAI))" . "\r\n";
            //値
            // $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY SYUHEN.ATAI DESC)" . "\r\n";
            $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.KOUKATYPE_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(SYUHEN.ATAI) DESC)" . "\r\n";
            $strSQL .= "       END" . "\r\n";
        } else {
            $strSQL .= "      ,CASE WHEN SYUHEN.SYUKEI_KOMOKU_KBN = '99'  " . "\r\n";
            // $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.GROUP_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY SYUHEN.ATAI)" . "\r\n";
            $strSQL .= "                 THEN RANK() OVER(PARTITION BY STYPE.GROUP_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(SYUHEN.ATAI))" . "\r\n";
            // $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.GROUP_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY SYUHEN.ATAI DESC)" . "\r\n";
            $strSQL .= "            ELSE RANK() OVER(PARTITION BY STYPE.GROUP_CD,SYUHEN.SYUKEI_KOMOKU_KBN ORDER BY TO_NUMBER(SYUHEN.ATAI) DESC)" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "       END" . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM  JKKOUKA_SYUHEN_RIEKI SYUHEN " . "\r\n";
        $strSQL .= "     ,JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        if ($blnSyuhenRieki) {
            $strSQL .= "WHERE SYUHEN.KOMOKU_KBN = '04'  " . "\r\n";
        } else {
            $strSQL .= "WHERE SYUHEN.KOMOKU_KBN = '02'  " . "\r\n";
        }
        $strSQL .= "  AND SYUHEN.SYUKEI_KOMOKU_KBN > '00'  " . "\r\n";
        $strSQL .= "  AND STYPE.SYAIN_NO = SYUHEN.SYAIN_NO  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = SYUHEN.HYOUKA_KIKAN_END  " . "\r\n";
        $strSQL .= "  AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);

        return parent::insert($strSQL);
    }

    //周辺利益_指数設定
    public function fnc_InsShisu_Syuhen($strType, $strKaisiYm, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";

        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,'99'" . "\r\n";
        //項目区分
        $strSQL .= "      ,'04'" . "\r\n";

        if ($strType == CNS_01 || $strType == CNS_02 || $strType == CNS_08) {
            //値
            $strSQL .= "      ,ROUND(BDC.ATAI/BDC.JUNNI + CREDIT.ATAI/CREDIT.JUNNI + SLEASE.ATAI/SLEASE.JUNNI + MENTE.ATAI/MENTE.JUNNI + HOSYO.ATAI/HOSYO.JUNNI + JAF.ATAI/JAF.JUNNI,5)" . "\r\n";
        }
        if ($strType == CNS_06 || $strType == CNS_12) {
            //値
            $strSQL .= "      ,ROUND(MENTE.ATAI/MENTE.JUNNI + HOSYO.ATAI/HOSYO.JUNNI + JAF.ATAI/JAF.JUNNI,5)" . "\r\n";
        }
        if ($strType == CNS_10) {
            //値
            $strSQL .= "      ,ROUND(CREDIT.ATAI/CREDIT.JUNNI + MENTE.ATAI/MENTE.JUNNI + JAF.ATAI/JAF.JUNNI,5)" . "\r\n";
        }
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        //JAF順位
        $strSQL .= "FROM (SELECT SYUHEN.SYAIN_NO  " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "            ,SYUHEN.ATAI" . "\r\n";
        $strSQL .= "            ,TO_NUMBER(SYUHEN.ATAI) AS ATAI" . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "            ,SAIDAI.JUNNI" . "\r\n";
        $strSQL .= "      FROM (SELECT SH.SYUKEI_KOMOKU_KBN" . "\r\n";
        $strSQL .= "                  ,SH.KOMOKU_KBN" . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "                  ,MAX(SH.ATAI) JUNNI" . "\r\n";
        $strSQL .= "                  ,TO_NUMBER(MAX(SH.ATAI)) JUNNI" . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "            FROM  JKKOUKA_SYUHEN_RIEKI SH" . "\r\n";
        $strSQL .= "                 ,JKKOUKA_SYAIN_TYPE ST " . "\r\n";
        $strSQL .= "            WHERE SH.KOMOKU_KBN = '05' " . "\r\n";
        $strSQL .= "              AND SH.SYUKEI_KOMOKU_KBN = '06' " . "\r\n";
        $strSQL .= "              AND ST.SYAIN_NO = SH.SYAIN_NO  " . "\r\n";
        $strSQL .= "              AND ST.HYOUKA_KIKAN_END = SH.HYOUKA_KIKAN_END  " . "\r\n";
        $strSQL .= "              AND SH.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
        $strSQL .= "              AND ST.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "              AND ST.HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
        $strSQL .= "            GROUP BY SH.SYUKEI_KOMOKU_KBN" . "\r\n";
        $strSQL .= "                    ,SH.KOMOKU_KBN ) SAIDAI" . "\r\n";
        $strSQL .= "           ,JKKOUKA_SYUHEN_RIEKI SYUHEN" . "\r\n";
        $strSQL .= "      WHERE SYUHEN.KOMOKU_KBN = '05' " . "\r\n";
        $strSQL .= "        AND SYUHEN.SYUKEI_KOMOKU_KBN = '06' " . "\r\n";
        $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART ) JAF" . "\r\n";

        //ﾊﾟｯｸdeﾒﾝﾃ順位
        $strSQL .= "    ,(SELECT SYUHEN.SYAIN_NO  " . "\r\n";
        // 20250507 caina upd s
        //  $strSQL .= "            ,SYUHEN.ATAI" . "\r\n";
        $strSQL .= "            ,TO_NUMBER(SYUHEN.ATAI) AS ATAI" . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "            ,SAIDAI.JUNNI" . "\r\n";
        $strSQL .= "      FROM (SELECT SH.SYUKEI_KOMOKU_KBN" . "\r\n";
        $strSQL .= "                  ,SH.KOMOKU_KBN" . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "                  ,MAX(SH.ATAI) JUNNI" . "\r\n";
        $strSQL .= "                  ,TO_NUMBER(MAX(SH.ATAI)) JUNNI" . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "            FROM  JKKOUKA_SYUHEN_RIEKI SH" . "\r\n";
        $strSQL .= "                 ,JKKOUKA_SYAIN_TYPE ST " . "\r\n";
        $strSQL .= "            WHERE SH.KOMOKU_KBN = '05' " . "\r\n";
        $strSQL .= "              AND SH.SYUKEI_KOMOKU_KBN = '04' " . "\r\n";
        $strSQL .= "              AND ST.SYAIN_NO = SH.SYAIN_NO  " . "\r\n";
        $strSQL .= "              AND ST.HYOUKA_KIKAN_END = SH.HYOUKA_KIKAN_END  " . "\r\n";
        $strSQL .= "              AND SH.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
        $strSQL .= "              AND ST.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "              AND ST.HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
        $strSQL .= "            GROUP BY SH.SYUKEI_KOMOKU_KBN" . "\r\n";
        $strSQL .= "                    ,SH.KOMOKU_KBN ) SAIDAI" . "\r\n";
        $strSQL .= "           ,JKKOUKA_SYUHEN_RIEKI SYUHEN" . "\r\n";
        $strSQL .= "      WHERE SYUHEN.KOMOKU_KBN = '05' " . "\r\n";
        $strSQL .= "        AND SYUHEN.SYUKEI_KOMOKU_KBN = '04' " . "\r\n";
        $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART ) MENTE" . "\r\n";

        if ($strType == CNS_01 || $strType == CNS_02 || $strType == CNS_08) {
            //ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ順位
            $strSQL .= "    ,(SELECT SYUHEN.SYAIN_NO  " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,SYUHEN.ATAI" . "\r\n";
            $strSQL .= "            ,TO_NUMBER(SYUHEN.ATAI) AS ATAI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            ,SAIDAI.JUNNI" . "\r\n";
            $strSQL .= "      FROM (SELECT SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                  ,SH.KOMOKU_KBN" . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "                  ,MAX(SH.ATAI) JUNNI" . "\r\n";
            $strSQL .= "                  ,TO_NUMBER(MAX(SH.ATAI)) JUNNI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            FROM  JKKOUKA_SYUHEN_RIEKI SH" . "\r\n";
            $strSQL .= "                 ,JKKOUKA_SYAIN_TYPE ST " . "\r\n";
            $strSQL .= "            WHERE SH.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "              AND SH.SYUKEI_KOMOKU_KBN = '01' " . "\r\n";
            $strSQL .= "              AND ST.SYAIN_NO = SH.SYAIN_NO  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = SH.HYOUKA_KIKAN_END  " . "\r\n";
            $strSQL .= "              AND SH.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
            $strSQL .= "              AND ST.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "            GROUP BY SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                    ,SH.KOMOKU_KBN ) SAIDAI" . "\r\n";
            $strSQL .= "           ,JKKOUKA_SYUHEN_RIEKI SYUHEN" . "\r\n";
            $strSQL .= "      WHERE SYUHEN.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "        AND SYUHEN.SYUKEI_KOMOKU_KBN = '01' " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART ) BDC" . "\r\n";
        }
        if ($strType == CNS_01 || $strType == CNS_02 || $strType == CNS_08 || $strType == CNS_10) {
            //ｸﾚｼﾞｯﾄKB順位
            $strSQL .= "    ,(SELECT SYUHEN.SYAIN_NO  " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,SYUHEN.ATAI" . "\r\n";
            $strSQL .= "            ,TO_NUMBER(SYUHEN.ATAI) AS ATAI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            ,SAIDAI.JUNNI" . "\r\n";
            $strSQL .= "      FROM (SELECT SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                  ,SH.KOMOKU_KBN" . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "                  ,MAX(SH.ATAI) JUNNI" . "\r\n";
            $strSQL .= "                  ,TO_NUMBER(MAX(SH.ATAI)) JUNNI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            FROM  JKKOUKA_SYUHEN_RIEKI SH" . "\r\n";
            $strSQL .= "                 ,JKKOUKA_SYAIN_TYPE ST " . "\r\n";
            $strSQL .= "            WHERE SH.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "              AND SH.SYUKEI_KOMOKU_KBN = '02' " . "\r\n";
            $strSQL .= "              AND ST.SYAIN_NO = SH.SYAIN_NO  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = SH.HYOUKA_KIKAN_END  " . "\r\n";
            $strSQL .= "              AND SH.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
            $strSQL .= "              AND ST.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "            GROUP BY SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                    ,SH.KOMOKU_KBN ) SAIDAI" . "\r\n";
            $strSQL .= "           ,JKKOUKA_SYUHEN_RIEKI SYUHEN" . "\r\n";
            $strSQL .= "      WHERE SYUHEN.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "        AND SYUHEN.SYUKEI_KOMOKU_KBN = '02' " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART ) CREDIT" . "\r\n";
        }
        if ($strType == CNS_01 || $strType == CNS_02 || $strType == CNS_08) {
            //再ﾘｰｽ順位
            $strSQL .= "    ,(SELECT SYUHEN.SYAIN_NO  " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,SYUHEN.ATAI" . "\r\n";
            $strSQL .= "            ,TO_NUMBER(SYUHEN.ATAI) AS ATAI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            ,SAIDAI.JUNNI" . "\r\n";
            $strSQL .= "      FROM (SELECT SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                  ,SH.KOMOKU_KBN" . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "                  ,MAX(SH.ATAI) JUNNI" . "\r\n";
            $strSQL .= "                  ,TO_NUMBER(MAX(SH.ATAI)) JUNNI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            FROM  JKKOUKA_SYUHEN_RIEKI SH" . "\r\n";
            $strSQL .= "                 ,JKKOUKA_SYAIN_TYPE ST " . "\r\n";
            $strSQL .= "            WHERE SH.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "              AND SH.SYUKEI_KOMOKU_KBN = '03' " . "\r\n";
            $strSQL .= "              AND ST.SYAIN_NO = SH.SYAIN_NO  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = SH.HYOUKA_KIKAN_END  " . "\r\n";
            $strSQL .= "              AND SH.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
            $strSQL .= "              AND ST.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "            GROUP BY SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                    ,SH.KOMOKU_KBN ) SAIDAI" . "\r\n";
            $strSQL .= "           ,JKKOUKA_SYUHEN_RIEKI SYUHEN" . "\r\n";
            $strSQL .= "      WHERE SYUHEN.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "        AND SYUHEN.SYUKEI_KOMOKU_KBN = '03' " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART ) SLEASE" . "\r\n";
        }

        if ($strType == CNS_01 || $strType == CNS_02 || $strType == CNS_06 || $strType == CNS_08 || $strType == CNS_12) {
            //ﾊﾟｯｸde753順位
            $strSQL .= "    ,(SELECT SYUHEN.SYAIN_NO  " . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "            ,SYUHEN.ATAI" . "\r\n";
            $strSQL .= "            ,TO_NUMBER(SYUHEN.ATAI) AS ATAI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            ,SAIDAI.JUNNI" . "\r\n";
            $strSQL .= "      FROM (SELECT SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                  ,SH.KOMOKU_KBN" . "\r\n";
            // 20250507 caina upd s
            // $strSQL .= "                  ,MAX(SH.ATAI) JUNNI" . "\r\n";
            $strSQL .= "                  ,TO_NUMBER(MAX(SH.ATAI)) JUNNI" . "\r\n";
            // 20250507 caina upd e
            $strSQL .= "            FROM  JKKOUKA_SYUHEN_RIEKI SH" . "\r\n";
            $strSQL .= "                 ,JKKOUKA_SYAIN_TYPE ST " . "\r\n";
            $strSQL .= "            WHERE SH.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "              AND SH.SYUKEI_KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "              AND ST.SYAIN_NO = SH.SYAIN_NO  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = SH.HYOUKA_KIKAN_END  " . "\r\n";
            $strSQL .= "              AND SH.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
            $strSQL .= "              AND ST.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
            $strSQL .= "              AND ST.HYOUKA_KIKAN_END = @KIKANEND " . "\r\n";
            $strSQL .= "            GROUP BY SH.SYUKEI_KOMOKU_KBN" . "\r\n";
            $strSQL .= "                    ,SH.KOMOKU_KBN ) SAIDAI" . "\r\n";
            $strSQL .= "           ,JKKOUKA_SYUHEN_RIEKI SYUHEN" . "\r\n";
            $strSQL .= "      WHERE SYUHEN.KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "        AND SYUHEN.SYUKEI_KOMOKU_KBN = '05' " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
            $strSQL .= "        AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART ) HOSYO" . "\r\n";
        }
        $strSQL .= "     ,JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        //JAF
        $strSQL .= "WHERE MENTE.SYAIN_NO = JAF.SYAIN_NO  " . "\r\n";

        if ($strType == CNS_01 || $strType == CNS_02 || $strType == CNS_08) {
            //ﾊﾟｯｸdeﾒﾝﾃ
            $strSQL .= "  AND BDC.SYAIN_NO = MENTE.SYAIN_NO  " . "\r\n";
            //ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ
            $strSQL .= "  AND CREDIT.SYAIN_NO = BDC.SYAIN_NO  " . "\r\n";
            //ｸﾚｼﾞｯﾄKB
            $strSQL .= "  AND SLEASE.SYAIN_NO = CREDIT.SYAIN_NO  " . "\r\n";
            //再ﾘｰｽ
            $strSQL .= "  AND HOSYO.SYAIN_NO = SLEASE.SYAIN_NO  " . "\r\n";
            //ﾊﾟｯｸde753
            $strSQL .= "  AND STYPE.SYAIN_NO = HOSYO.SYAIN_NO  " . "\r\n";
        }

        if ($strType == CNS_06 || $strType == CNS_12) {
            //ﾊﾟｯｸdeﾒﾝﾃ
            $strSQL .= "  AND HOSYO.SYAIN_NO = MENTE.SYAIN_NO  " . "\r\n";
            //ﾊﾟｯｸde753
            $strSQL .= "  AND STYPE.SYAIN_NO = HOSYO.SYAIN_NO  " . "\r\n";
        }

        if ($strType == CNS_10) {
            //ﾊﾟｯｸdeﾒﾝﾃ
            $strSQL .= "  AND CREDIT.SYAIN_NO = MENTE.SYAIN_NO  " . "\r\n";
            //ｸﾚｼﾞｯﾄKB
            $strSQL .= "  AND STYPE.SYAIN_NO = CREDIT.SYAIN_NO  " . "\r\n";
        }
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);

        return parent::insert($strSQL);
    }

    //周辺利益_達成度設定
    public function fnc_InsTasseido_Syuhen($strType, $strKaisiYm, $prvEndYm)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYUHEN_RIEKI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,SYUHEN.SYUKEI_KOMOKU_KBN" . "\r\n";
        //項目区分
        $strSQL .= "      ,'06'" . "\r\n";
        //値
        $strSQL .= "      ,MAX(TASSEI.CODE)" . "\r\n";
        //共通部分
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM (SELECT TO_NUMBER(MST.CODE) CODE " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "            ,MST.SUUTI_2 * TAISYO.ATAI JUNNI" . "\r\n";
        $strSQL .= "            ,TO_NUMBER(MST.SUUTI_2) * TO_NUMBER(TAISYO.ATAI) JUNNI" . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "            ,TAISYO.SYAIN_NO " . "\r\n";
        $strSQL .= "      FROM   JKKOUKA_KANRI_MST MST " . "\r\n";
        $strSQL .= "           ,(SELECT MIN(TO_NUMBER(KANRI.SYUBETU_CD)) SYUBETU_CD " . "\r\n";
        $strSQL .= "                   ,RIEKI.SYAIN_NO" . "\r\n";
        $strSQL .= "                   ,RIEKI.ATAI" . "\r\n";
        $strSQL .= "             FROM JKKOUKA_KANRI_MST KANRI" . "\r\n";
        $strSQL .= "                 ,JKKOUKA_SYUHEN_RIEKI RIEKI " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "             WHERE RIEKI.ATAI <= TO_NUMBER(KANRI.SYUBETU_CD) " . "\r\n";
        $strSQL .= "             WHERE TO_NUMBER(RIEKI.ATAI) <= TO_NUMBER(KANRI.SYUBETU_CD) " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "               AND KANRI.SYUBETU_CD BETWEEN '1' AND '999' " . "\r\n";
        $strSQL .= "               AND RIEKI.KOMOKU_KBN = '07' " . "\r\n";
        $strSQL .= "               AND RIEKI.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";
        $strSQL .= "               AND RIEKI.HYOUKA_KIKAN_START = @KIKANSTART " . "\r\n";
        $strSQL .= "             GROUP BY RIEKI.SYAIN_NO " . "\r\n";
        $strSQL .= "                     ,RIEKI.ATAI) TAISYO  " . "\r\n";
        $strSQL .= "      WHERE TAISYO.SYUBETU_CD = TO_NUMBER(MST.SYUBETU_CD) " . "\r\n";
        $strSQL .= "        AND MST.SYUBETU_CD BETWEEN '1' AND '999' ) TASSEI" . "\r\n";
        $strSQL .= "     ,JKKOUKA_SYUHEN_RIEKI SYUHEN " . "\r\n";
        $strSQL .= "     ,JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        // 20250507 caina upd s
        // $strSQL .= "WHERE SYUHEN.ATAI <= TASSEI.JUNNI " . "\r\n";
        $strSQL .= "WHERE TO_NUMBER(SYUHEN.ATAI) <= TO_NUMBER(TASSEI.JUNNI) " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "  AND SYUHEN.SYAIN_NO = TASSEI.SYAIN_NO " . "\r\n";
        $strSQL .= "  AND SYUHEN.KOMOKU_KBN = '05' " . "\r\n";
        $strSQL .= "  AND STYPE.SYAIN_NO = SYUHEN.SYAIN_NO  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = SYUHEN.HYOUKA_KIKAN_END  " . "\r\n";
        $strSQL .= "  AND SYUHEN.HYOUKA_KIKAN_START = @KIKANSTART  " . "\r\n";
        $strSQL .= "  AND STYPE.KOUKATYPE_CD = @KOUKATYPE  " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  " . "\r\n";

        $strSQL .= "GROUP BY STYPE.SYAIN_NO " . "\r\n";
        $strSQL .= "        ,SYUHEN.SYUKEI_KOMOKU_KBN ";

        //-- ﾊﾟﾗﾒｰﾀ --
        //考課表タイプ
        $strSQL = str_replace("@KOUKATYPE", $this->ClsComFncJKSYS->FncSqlNv($strType), $strSQL);
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($strKaisiYm), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($prvEndYm), $strSQL);

        return parent::insert($strSQL);
    }

    //Insert 共通部分
    public function GetInssql()
    {
        $strSQL = "";
        //評価対象期間開始
        $strSQL .= " ( HYOUKA_KIKAN_START " . "\r\n";
        //評価対象期間終了
        $strSQL .= "  ,HYOUKA_KIKAN_END " . "\r\n";
        //社員番号
        $strSQL .= "  ,SYAIN_NO " . "\r\n";
        //集計項目区分
        $strSQL .= "  ,SYUKEI_KOMOKU_KBN " . "\r\n";
        //項目区分
        $strSQL .= "  ,KOMOKU_KBN" . "\r\n";
        //値
        $strSQL .= "  ,ATAI" . "\r\n";
        //作成日付
        $strSQL .= "  ,CREATE_DATE" . "\r\n";
        //作成者
        $strSQL .= "  ,CRE_SYA_CD " . "\r\n";
        //作成APP
        $strSQL .= "  ,CRE_PRG_ID " . "\r\n";
        //更新日付
        $strSQL .= "  ,UPD_DATE " . "\r\n";
        //更新者
        $strSQL .= "  ,UPD_SYA_CD " . "\r\n";
        //更新APP
        $strSQL .= "  ,UPD_PRG_ID " . "\r\n";
        //更新マシン
        $strSQL .= "  ,UPD_CLT_NM )";
        return $strSQL;
    }

    public function Getsql()
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strsql = "";
        //作成日付
        $strsql .= ", SYSDATE " . "\r\n";
        //作成者
        $strsql .= "," . $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']) . "\r\n";
        //作成ＡＰＰ
        $strsql .= "," . $this->ClsComFncJKSYS->FncSqlNv("Evaluationtotal") . "\r\n";
        //更新日付
        $strsql .= ", SYSDATE " . "\r\n";
        //更新者
        $strsql .= "," . $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']) . "\r\n";
        //更新APP
        $strsql .= "," . $this->ClsComFncJKSYS->FncSqlNv("Evaluationtotal") . "\r\n";
        //更新マシン
        $strsql .= "," . $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']);

        return $strsql;
    }

    //達成率のみ更新__DELETE
    public function FncDelSYUKEI($dtpTaisyouKE)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "DELETE JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= "WHERE HYOUKA_KIKAN_START = @KIKANSTART" . "\r\n";
        $strSQL .= " AND  HYOUKA_KIKAN_END = @KIKANEND" . "\r\n";
        $strSQL .= " AND  KOMOKU_KBN ='03'";

        //-- ﾊﾟﾗﾒｰﾀ --
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($dtpTaisyouKE), $strSQL);

        return parent::delete($strSQL);
    }

    //達成率のみ更新__INSERT
    public function FncInsSYUKEI($dtpTaisyouKE)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_JISSEKI_SYUKEI " . "\r\n";
        $strSQL .= $this->GetInssql() . "\r\n";
        //評価対象開始期間
        $strSQL .= "SELECT @KIKANSTART" . "\r\n";
        //評価対象終了期間
        $strSQL .= "      ,@KIKANEND" . "\r\n";
        //社員番号
        $strSQL .= "      ,STYPE.SYAIN_NO" . "\r\n";
        //集計項目区分
        $strSQL .= "      ,JISSEKI.SYUKEI_KOMOKU_KBN" . "\r\n";
        //項目区分
        $strSQL .= "      ,'03'" . "\r\n";
        //値
        $strSQL .= "      ,CASE WHEN NVL(KIJUN.ATAI,0) = 0 OR NVL(JISSEKI.ATAI,0) = 0 " . "\r\n";
        $strSQL .= "                 THEN 0 " . "\r\n";
        $strSQL .= "            WHEN KIJUN.ATAI > 0 AND JISSEKI.ATAI > 0" . "\r\n";
        $strSQL .= "                 THEN ROUND(JISSEKI.ATAI / KIJUN.ATAI * 100,1) " . "\r\n";
        $strSQL .= "            WHEN KIJUN.ATAI < JISSEKI.ATAI  " . "\r\n";
        $strSQL .= "                 THEN ROUND((JISSEKI.ATAI - KIJUN.ATAI) / ABS(KIJUN.ATAI) * 100,1) " . "\r\n";
        $strSQL .= "            WHEN KIJUN.ATAI > JISSEKI.ATAI  " . "\r\n";
        $strSQL .= "                 THEN ROUND((KIJUN.ATAI - JISSEKI.ATAI) / ABS(KIJUN.ATAI) * -100,1) " . "\r\n";
        //20210302 CI INS S
        $strSQL .= "            WHEN KIJUN.ATAI = JISSEKI.ATAI THEN 100 " . "\r\n";
        //20210302 CI INS E
        $strSQL .= "       END " . "\r\n";
        $strSQL .= $this->Getsql() . "\r\n";

        $strSQL .= "FROM (SELECT SYAIN_NO  " . "\r\n";
        $strSQL .= "            ,SYUKEI_KOMOKU_KBN " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "            ,ATAI " . "\r\n";
        $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "      FROM JKKOUKA_JISSEKI_SYUKEI" . "\r\n";
        $strSQL .= "      WHERE KOMOKU_KBN = '01'" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) KIJUN" . "\r\n";
        $strSQL .= "    ,(SELECT SYAIN_NO  " . "\r\n";
        $strSQL .= "            ,SYUKEI_KOMOKU_KBN " . "\r\n";
        // 20250507 caina upd s
        // $strSQL .= "            ,ATAI " . "\r\n";
        $strSQL .= "            ,TO_NUMBER(ATAI) AS ATAI " . "\r\n";
        // 20250507 caina upd e
        $strSQL .= "      FROM JKKOUKA_JISSEKI_SYUKEI" . "\r\n";
        $strSQL .= "      WHERE KOMOKU_KBN = '02'" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_END = @KIKANEND" . "\r\n";
        $strSQL .= "        AND HYOUKA_KIKAN_START = @KIKANSTART ) JISSEKI" . "\r\n";
        $strSQL .= "    , JKKOUKA_SYAIN_TYPE STYPE " . "\r\n";

        $strSQL .= "WHERE JISSEKI.SYAIN_NO = KIJUN.SYAIN_NO " . "\r\n";
        $strSQL .= "  AND STYPE.SYAIN_NO = JISSEKI.SYAIN_NO " . "\r\n";
        $strSQL .= "  AND JISSEKI.SYUKEI_KOMOKU_KBN = KIJUN.SYUKEI_KOMOKU_KBN " . "\r\n";
        $strSQL .= "  AND STYPE.HYOUKA_KIKAN_END = @KIKANEND  ";

        //- ﾊﾟﾗﾒｰﾀ --
        //評価対象期間開始
        $strSQL = str_replace("@KIKANSTART", $this->ClsComFncJKSYS->FncSqlNv($this->AddMonths($dtpTaisyouKE, -5)), $strSQL);
        //評価対象期間終了
        $strSQL = str_replace("@KIKANEND", $this->ClsComFncJKSYS->FncSqlNv($dtpTaisyouKE), $strSQL);

        return parent::insert($strSQL);
    }

    //年月-numか月
    public function AddMonths($dtpYM, $num)
    {
        $dtpYM = $dtpYM . "01";
        $rtnDate = date('Ym', strtotime("$dtpYM $num month"));

        return $rtnDate;
    }

}
