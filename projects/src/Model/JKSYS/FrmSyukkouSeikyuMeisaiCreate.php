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
class FrmSyukkouSeikyuMeisaiCreate extends ClsComDb
{
    public $ClsComFncJKSYS;
    //人事コントロールマスタの処理年月取得
    public function procGetJinjiCtrlMst_YM()
    {
        $strSQL = "SELECT" . "\r\n";
        $strSQL .= "     SYORI_YM" . "\r\n";
        $strSQL .= "    ,KAKI_BONUS_MONTH" . "\r\n";
        $strSQL .= "    ,KAKI_BONUS_START_MT" . "\r\n";
        $strSQL .= "    ,KAKI_BONUS_END_MT" . "\r\n";
        $strSQL .= "    ,TOUKI_BONUS_MONTH" . "\r\n";
        $strSQL .= "    ,TOUKI_BONUS_START_MT" . "\r\n";
        $strSQL .= "    ,TOUKI_BONUS_END_MT" . "\r\n";
        $strSQL .= " FROM JKCONTROLMST" . "\r\n";
        $strSQL .= "     JKCONTROLMST" . "\r\n";
        $strSQL .= " WHERE ID = '01'";

        return parent::select($strSQL);
    }

    //出向先ComboBoxのデータ取得
    public function procGetSyukkousakiData()
    {
        $strSQL = "SELECT" . "\r\n" . "\r\n";
        $strSQL .= "      KM.KUBUN_CD" . "\r\n";
        $strSQL .= "     ,BM.BUSYO_NM" . "\r\n";
        $strSQL .= " FROM JKKUBUNMST KM" . "\r\n";
        $strSQL .= "     ,JKBUMON BM" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "      KM.KUBUN_CD = BM.BUSYO_CD" . "\r\n";
        $strSQL .= "   AND KM.KUBUN_ID = 'JKSKOBSY'" . "\r\n";
        $strSQL .= " ORDER BY KM.KUBUN_CD";

        return parent::select($strSQL);
    }

    //出向者請求明細データの取得
    public function procGetSeikyuMeisaiData($MstYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "SELECT" . "\r\n";
        $strSQL .= "     0 AS chkUpdate" . "\r\n";
        $strSQL .= "    ,0 AS chkDelete" . "\r\n";
        $strSQL .= "    ,SSM.SYAIN_NO" . "\r\n";
        $strSQL .= "    ,SM.SYAIN_NM" . "\r\n";
        $strSQL .= "    ,'' AS btnSyainSearch" . "\r\n";
        $strSQL .= "    ,SSM.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,SSM.SYUKKIN_NISSU" . "\r\n";
        $strSQL .= "    ,SSM.SYUGYOU_NISSU" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "     JKSKOSEIKYUMEISAI SSM" . "\r\n";
        $strSQL .= " LEFT JOIN JKSYAIN SM" . "\r\n";
        $strSQL .= " ON    SSM.SYAIN_NO = SM.SYAIN_NO" . "\r\n";
        $strSQL .= " LEFT JOIN JKBUMON BM" . "\r\n";
        $strSQL .= " ON    SSM.BUSYO_CD = BM.BUSYO_CD " . "\r\n";
        $strSQL .= " WHERE 1 = 1" . "\r\n";
        $strSQL .= "   AND SSM.TAISYOU_YM = @REP" . "\r\n";
        $strSQL .= " ORDER BY SSM.BUSYO_CD,SSM.SYAIN_NO";

        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($MstYM), $strSQL);

        return parent::select($strSQL);
    }

    //社員マスタデータの取得
    public function procGetSyainMstData($MstYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "SELECT" . "\r\n";
        $strSQL .= "     0 AS chkUpdate," . "\r\n";
        $strSQL .= "     0 AS chkDelete," . "\r\n";
        $strSQL .= "     SM.SYAIN_NO," . "\r\n";
        $strSQL .= "     SM.SYAIN_NM," . "\r\n";
        $strSQL .= "     '' AS btnSyainSearch," . "\r\n";
        $strSQL .= "     IR.BUSYO_CD," . "\r\n";
        $strSQL .= "     NULL AS SYUKKIN_NISSU," . "\r\n";
        $strSQL .= "     NULL SYUGYOU_NISSU" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "     JKSYAIN SM" . "\r\n";
        $strSQL .= " INNER JOIN JKIDOURIREKI IR" . "\r\n";
        $strSQL .= " ON SM.SYAIN_NO = IR.SYAIN_NO" . "\r\n";
        $strSQL .= " INNER JOIN (SELECT" . "\r\n";
        $strSQL .= " SYAIN_NO," . "\r\n";
        $strSQL .= " MAX(ANNOUNCE_DT) AS ANNOUNCE_DT" . "\r\n";
        $strSQL .= "   FROM" . "\r\n";
        $strSQL .= " JKIDOURIREKI" . "\r\n";
        $strSQL .= "   WHERE" . "\r\n";
        $strSQL .= "   ANNOUNCE_DT <= @REP" . "\r\n";
        $strSQL .= "   GROUP BY" . "\r\n";
        $strSQL .= "   SYAIN_NO) IRM" . "\r\n";
        $strSQL .= "   ON IR.SYAIN_NO = IRM.SYAIN_NO" . "\r\n";
        $strSQL .= "   AND IR.ANNOUNCE_DT = IRM.ANNOUNCE_DT" . "\r\n";
        $strSQL .= "   LEFT JOIN JKBUMON BM" . "\r\n";
        $strSQL .= "   ON IR.BUSYO_CD = BM.BUSYO_CD" . "\r\n";
        $strSQL .= "   INNER JOIN JKKUBUNMST KM" . "\r\n";
        $strSQL .= "   ON IR.BUSYO_CD = KM.KUBUN_CD" . "\r\n";
        $strSQL .= "   AND KM.KUBUN_ID = 'JKSKOBSY'" . "\r\n";
        $strSQL .= "   WHERE  NVL(SM.TAISYOKU_DT,'9999/12/31') >= '@TAISYOKUDT'" . "\r\n";
        $strSQL .= "   AND    NVL(SM.NYUSYA_DT,'0001/01/01') < '@NYUSYA_DT'" . "\r\n";
        $strSQL .= "   ORDER BY" . "\r\n";
        $strSQL .= "   IR.BUSYO_CD," . "\r\n";
        $strSQL .= "   SM.SYAIN_NO";

        $MstYM = $MstYM . '01 12:00:00';
        $strWkDate = date('Y/m/d h:i:s', strtotime("$MstYM +1 month -1 day"));
        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlDate($strWkDate), $strSQL);

        $strWkDate = date('Y/m/d', strtotime("$MstYM -1 month"));
        $strSQL = str_replace("@TAISYOKUDT", $strWkDate, $strSQL);
        $strSQL = str_replace("@NYUSYA_DT", date('Y/m/d', strtotime("$MstYM")), $strSQL);

        return parent::select($strSQL);
    }

    //データの存在チェックSql
    function procCheckDataLogicSQL($flag, $data)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "  SELECT" . "\r\n";
        $strSQL .= "     COUNT(*) AS cnt" . "\r\n";
        $strSQL .= " FROM" . "\r\n";

        switch ($flag) {
            //支給データに画面．対象年月のデータ
            case 1:
                $strSQL .= "     JKSHIKYU" . "\r\n";
                $strSQL .= " WHERE" . "\r\n";
                $strSQL .= "     TAISYOU_YM = @REP" . "\r\n";
                break;
            //事業主データに画面．対象年月のデータ
            case 2:
                $strSQL .= "     JKJIGYOUNUSHI" . "\r\n";
                $strSQL .= " WHERE" . "\r\n";
                $strSQL .= "     TAISYOU_YM = @REP" . "\r\n";
                break;
            //画面．対象年月（月）＝変数．夏季ボーナス月の場合,
            case 3:
                //支給データ．対象年月＝画面．対象年月　かつ　支給データ．給与・賞与区分＝"2"　（賞与）のデータ
                $strSQL .= "     JKSHIKYU" . "\r\n";
                $strSQL .= " WHERE" . "\r\n";
                $strSQL .= "     TAISYOU_YM = @REP" . "\r\n";
                $strSQL .= " AND" . "\r\n";
                $strSQL .= "     KS_KB = '2'" . "\r\n";
                break;
            //画面．対象年月（月）＝変数．冬季ボーナス月の場合
            case 4:
                //支給データ．対象年月＝画面．対象年月　かつ　支給データ．給与・賞与区分＝"2"　（賞与）のデータ
                $strSQL .= "     JKSHIKYU" . "\r\n";
                $strSQL .= " WHERE" . "\r\n";
                $strSQL .= "     TAISYOU_YM = @REP" . "\r\n";
                $strSQL .= " AND" . "\r\n";
                $strSQL .= "     KS_KB = '2'" . "\r\n";
                break;
            default:
                $strSQL .= "" . "\r\n";
                break;
        }

        $strSQL = str_replace("@REP", $this->ClsComFncJKSYS->FncSqlNv($data['dtpYM']), $strSQL);
        return $strSQL;
    }

    //データの存在チェック（ロジック）
    public function procCheckDataLogic($intMode, $data)
    {
        $strSql = $this->procCheckDataLogicSQL($intMode, $data);
        return parent::select($strSql);
    }

    //ワーク出向社員請求明細対象者データの削除
    public function procCreateSeikyuMeisai()
    {
        //５－１．ワーク出向社員請求明細対象者データを削除します。
        $strSQL = "DELETE FROM WK_JKSKOSEIKYUTAISYOU";

        return parent::delete($strSQL);
    }

    //出向社員請求明細対象者データの削除
    public function procDeleteSeikyuMeisaiData($dtpYM, $strSyainNo)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        //５－２．入力領域スプレッド件数分繰り返す
        $strSQL = "DELETE FROM JKSKOSEIKYUMEISAI" . "\r\n";
        $strSQL .= " WHERE TAISYOU_YM = @REP1" . "\r\n";
        $strSQL .= " AND   SYAIN_NO = @REP2";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($strSyainNo), $strSQL);

        return parent::delete($strSQL);
    }

    //ワーク出向社員請求明細対象者データの追加
    public function procInsertWorkData($dtpYM, $value)
    {
        //       対象年月＝画面．対象年月
        //       社員番号＝画面（入力領域）．社員番号
        //       出向先部署コード＝画面（入力領域）．出向先.value
        //       出勤日数＝画面（入力領域）．日割日数(出勤）
        //       就業日数＝画面（入力領域）．日割日数(月）
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = "INSERT INTO WK_JKSKOSEIKYUTAISYOU (" . "\r\n";
        $strSQL .= " TAISYOU_YM,SYAIN_NO,BUSYO_CD,SYUKKIN_NISSU,SYUGYOU_NISSU" . "\r\n";
        $strSQL .= " ) VALUES (" . "\r\n";
        $strSQL .= " @REP1,@REP2,@REP3,@REP4,@REP5)";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($value['SYAIN_NO']), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($value['BUSYO_CD']), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($value['SYUKKIN_NISSU']), $strSQL);
        $strSQL = str_replace("@REP5", $this->ClsComFncJKSYS->FncSqlNv($value['SYUGYOU_NISSU']), $strSQL);

        return parent::insert($strSQL);
    }

    //出向社員請求明細データ追加(給与データから)
    public function procInsertSeikyuMeisaiDataFromKyuyo($dtpYM)
    {
        //   ※「テーブル編集仕様書①」を参照
        //		使用テーブル
        //			ワーク出向社員請求明細対象者データ
        //			支給データ							（外部結合）
        //			事業主データ							（外部結合）
        //			控除データ							（外部結合）
        //		結合条件
        //			ワーク出向社員請求明細対象者データ．対象年月＝支給データ．対象年月
        //			ワーク出向社員請求明細対象者データ．社員番号＝支給データ．社員番号
        //			支給データ．給与・賞与区分＝"1"
        //			支給データ．社員番号＝事業主データ．社員番号
        //			支給データ．対象年月＝事業主データ．対象年月
        //			支給データ．給与・賞与区分＝事業主データ．給与・賞与区分
        //			支給データ．社員番号＝控除データ．社員番号
        //			支給データ．対象年月＝控除データ．対象年月
        //			支給データ．給与・賞与区分＝事業主データ．給与・賞与区分
        //		抽出条件
        //			ワーク出向社員請求明細対象者データ．対象年月＝画面．対象年月
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();
        $strSQL = " INSERT INTO JKSKOSEIKYUMEISAI (" . "\r\n";
        $strSQL .= " TAISYOU_YM" . "\r\n";
        $strSQL .= ",SYAIN_NO" . "\r\n";
        $strSQL .= ",BUSYO_CD" . "\r\n";
        $strSQL .= ",KIHONKYU" . "\r\n";
        $strSQL .= ",CHOUSEIKYU" . "\r\n";
        $strSQL .= ",SYOKUMU_TEATE" . "\r\n";
        $strSQL .= ",KAZOKU_TEATE" . "\r\n";
        $strSQL .= ",TUKIN_TEATE" . "\r\n";
        $strSQL .= ",SYARYOU_TEATE" . "\r\n";
        $strSQL .= ",SYOUREIKIN" . "\r\n";
        $strSQL .= ",ZANGYOU_TEATE" . "\r\n";
        $strSQL .= ",SYUKKOU_TEATE" . "\r\n";
        $strSQL .= ",JIKANSA_TEATE" . "\r\n";
        $strSQL .= ",KENKO_HKN_RYO" . "\r\n";
        $strSQL .= ",KAIGO_HKN_RYO" . "\r\n";
        $strSQL .= ",KOUSEINENKIN" . "\r\n";
        $strSQL .= ",JIDOU_TEATE" . "\r\n";
        $strSQL .= ",KOYOU_HKN_RYO" . "\r\n";
        $strSQL .= ",TAISYOKU_NENKIN" . "\r\n";
        $strSQL .= ",ROUSAI_UWA_HKN_RYO" . "\r\n";
        $strSQL .= ",BNS_GK" . "\r\n";
        $strSQL .= ",BNS_GK_MT" . "\r\n";
        $strSQL .= ",BNS_KENKO_HKN_RYO" . "\r\n";
        $strSQL .= ",BNS_KENKO_HKN_RYO_MT" . "\r\n";
        $strSQL .= ",BNS_KAIGO_HKN_RYO" . "\r\n";
        $strSQL .= ",BNS_KAIGO_HKN_RYO_MT" . "\r\n";
        $strSQL .= ",BNS_KOUSEI_NENKIN" . "\r\n";
        $strSQL .= ",BNS_KOUSEI_NENKIN_MT" . "\r\n";
        $strSQL .= ",BNS_JIDOU_TEATE" . "\r\n";
        $strSQL .= ",BNS_JIDOU_TEATE_MT" . "\r\n";
        $strSQL .= ",BNS_KOYOU_HOKEN" . "\r\n";
        $strSQL .= ",BNS_KOYOU_HOKEN_MT" . "\r\n";
        $strSQL .= ",SYUKKIN_NISSU" . "\r\n";
        $strSQL .= ",SYUGYOU_NISSU" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",CRE_SYA_CD" . "\r\n";
        $strSQL .= ",CRE_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= " SELECT" . "\r\n";
        $strSQL .= "    WK.TAISYOU_YM," . "\r\n";
        $strSQL .= "    WK.SYAIN_NO," . "\r\n";
        $strSQL .= "    WK.BUSYO_CD," . "\r\n";
        $strSQL .= "    TRUNC(NVL(JKS.SHIKYU1, 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(NVL(JKS.SHIKYU9, 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(NVL(JKS.SHIKYU2, 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(NVL(JKS.SHIKYU5, 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC((NVL(JKS.SHIKYU18, 0) + NVL(JKS.SHIKYU18_1, 0)) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(NVL(JKS.SHIKYU8, 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC((NVL(JKS.SHIKYU3, 0) + NVL(JKS.SHIKYU4, 0)) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(NVL(JKS.SHIKYU19, 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    TRUNC(TRUNC(NVL(JKJ.JIGYOUNUSHI1, 0), 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(TRUNC(NVL(JKJ.JIGYOUNUSHI1_1, 0), 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(TRUNC(NVL(JKJ.JIGYOUNUSHI2, 0), 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(TRUNC(NVL(JKJ.JIGYOUNUSHI3, 0), 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(TRUNC(NVL(JKJ.JIGYOUNUSHI6, 0), 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    TRUNC(NVL(JKK.KOUJYO7, 0) * (NVL(WK.SYUKKIN_NISSU, 1) / NVL(WK.SYUGYOU_NISSU, 1)), 0)," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    0," . "\r\n";
        $strSQL .= "    WK.SYUKKIN_NISSU," . "\r\n";
        $strSQL .= "    WK.SYUGYOU_NISSU," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REP2," . "\r\n";
        $strSQL .= "    'SyukkouSeikyuMeisiCr'," . "\r\n";
        $strSQL .= "    SYSDATE," . "\r\n";
        $strSQL .= "    @REP2," . "\r\n";
        $strSQL .= "    'SyukkouSeikyuMeisiCr'," . "\r\n";
        $strSQL .= "    @REP3" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "    WK_JKSKOSEIKYUTAISYOU WK" . "\r\n";
        $strSQL .= "    LEFT JOIN JKSHIKYU JKS" . "\r\n";
        $strSQL .= "        ON WK.TAISYOU_YM = JKS.TAISYOU_YM" . "\r\n";
        $strSQL .= "       AND WK.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND JKS.KS_KB = '1'" . "\r\n";
        $strSQL .= "    LEFT JOIN JKJIGYOUNUSHI JKJ" . "\r\n";
        $strSQL .= "        ON JKS.TAISYOU_YM = JKJ.TAISYOU_YM" . "\r\n";
        $strSQL .= "       AND JKS.SYAIN_NO = JKJ.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND JKS.KS_KB = JKJ.KS_KB" . "\r\n";
        $strSQL .= "    LEFT JOIN JKKOUJYO JKK" . "\r\n";
        $strSQL .= "        ON JKS.TAISYOU_YM = JKK.TAISYOU_YM" . "\r\n";
        $strSQL .= "       AND JKS.SYAIN_NO = JKK.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND JKS.KS_KB = JKK.KS_KB" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "    WK.TAISYOU_YM = @REP1" . "\r\n";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);

        return parent::insert($strSQL);
    }

    //出向社員請求明細データ更新(賞与データから) SELECT
    public function procUpdateSeikyuMeisaiDataFromSyoyo($dtpYM)
    {
        //       ※「テーブル編集仕様書②」を参照
        //       使用テーブル
        //           支給データ
        //           事業主データ
        //           ワーク出向社員請求明細対象者データ
        //       結合条件
        //           支給データ．社員番号＝事業主データ．社員番号
        //           支給データ．対象年月＝事業主データ．対象年月
        //           支給データ．給与・賞与区分＝事業主データ．給与・賞与区分
        //           支給データ．対象年月＝ワーク出向社員請求明細対象者データ．対象年月
        //           支給データ．社員番号＝ワーク出向社員請求明細対象者データ．社員番号
        //       抽出条件
        //           支給データ．給与・賞与区分＝"2"
        //           ワーク出向社員請求明細対象者データ．対象年月＝画面．対象年月
        //       更新条件
        //           出向社員請求明細データ．対象年月＝画面．対象年月
        //           出向社員請求明細データ．社員番号＝支給データ．社員番号
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = " SELECT" . "\r\n";
        $strSQL .= "     WK.TAISYOU_YM," . "\r\n";
        $strSQL .= "     WK.SYAIN_NO," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKS.SHIKYU1 / 6), -2) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_GK," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKS.SHIKYU1 / 6), -2) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_GK_MT," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KENKO_HKN_RYO," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KENKO_HKN_RYO_MT," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI1_1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KAIGO_HKN_RYO	," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI1_1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KAIGO_HKN_RYO_MT," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI2 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOUSEI_NENKIN	," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI2 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOUSEI_NENKIN_MT," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI3 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_JIDOU_TEATE," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI3 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_JIDOU_TEATE_MT," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI6 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOYOU_HOKEN," . "\r\n";
        $strSQL .= "     TRUNC(ROUND((JKJ.JIGYOUNUSHI6 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOYOU_HOKEN_MT" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= "     JKSHIKYU JKS" . "\r\n";
        $strSQL .= "     INNER JOIN JKJIGYOUNUSHI JKJ" . "\r\n";
        $strSQL .= "       ON JKS.TAISYOU_YM = JKJ.TAISYOU_YM" . "\r\n";
        $strSQL .= "       AND JKS.SYAIN_NO = JKJ.SYAIN_NO" . "\r\n";
        $strSQL .= "       AND JKS.KS_KB = JKJ.KS_KB" . "\r\n";
        $strSQL .= "     INNER JOIN WK_JKSKOSEIKYUTAISYOU WK" . "\r\n";
        $strSQL .= "       ON JKS.TAISYOU_YM = WK.TAISYOU_YM" . "\r\n";
        $strSQL .= "       AND JKS.SYAIN_NO = WK.SYAIN_NO" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= "     JKS.KS_KB = '2'" . "\r\n";
        $strSQL .= " AND" . "\r\n";
        $strSQL .= "     WK.TAISYOU_YM = @REP1";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);

        return parent::select($strSQL);
    }

    //出向社員請求明細データ更新(賞与データから) UPDATE
    public function procUpdateSeikyuMeisaiDataFromSyoyo2($value)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "UPDATE JKSKOSEIKYUMEISAI SET" . "\r\n";
        $strSQL .= " BNS_GK = @REP1" . "\r\n";
        $strSQL .= " ,BNS_GK_MT = @REP2" . "\r\n";
        $strSQL .= " ,BNS_KENKO_HKN_RYO = @REP3" . "\r\n";
        $strSQL .= " ,BNS_KENKO_HKN_RYO_MT = @REP4" . "\r\n";
        $strSQL .= " ,BNS_KAIGO_HKN_RYO = @REP5" . "\r\n";
        $strSQL .= " ,BNS_KAIGO_HKN_RYO_MT = @REP6" . "\r\n";
        $strSQL .= " ,BNS_KOUSEI_NENKIN = @REP7" . "\r\n";
        $strSQL .= " ,BNS_KOUSEI_NENKIN_MT = @REP8" . "\r\n";
        $strSQL .= " ,BNS_JIDOU_TEATE = @REP9" . "\r\n";
        $strSQL .= " ,BNS_JIDOU_TEATE_MT = @REPA" . "\r\n";
        $strSQL .= " ,BNS_KOYOU_HOKEN = @REPB" . "\r\n";
        $strSQL .= " ,BNS_KOYOU_HOKEN_MT = @REPC" . "\r\n";
        $strSQL .= " WHERE TAISYOU_YM = @REPD" . "\r\n";
        $strSQL .= " AND   SYAIN_NO = @REPE";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_GK'])), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_GK_MT'])), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KENKO_HKN_RYO'])), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KENKO_HKN_RYO_MT'])), $strSQL);
        $strSQL = str_replace("@REP5", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KAIGO_HKN_RYO'])), $strSQL);
        $strSQL = str_replace("@REP6", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KAIGO_HKN_RYO_MT'])), $strSQL);
        $strSQL = str_replace("@REP7", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOUSEI_NENKIN'])), $strSQL);
        $strSQL = str_replace("@REP8", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOUSEI_NENKIN_MT'])), $strSQL);
        $strSQL = str_replace("@REP9", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_JIDOU_TEATE'])), $strSQL);
        $strSQL = str_replace("@REPA", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_JIDOU_TEATE_MT'])), $strSQL);
        $strSQL = str_replace("@REPB", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOYOU_HOKEN'])), $strSQL);
        $strSQL = str_replace("@REPC", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOYOU_HOKEN_MT'])), $strSQL);
        $strSQL = str_replace("@REPD", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNv($value['TAISYOU_YM'])), $strSQL);
        $strSQL = str_replace("@REPE", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNv($value['SYAIN_NO'])), $strSQL);

        return parent::update($strSQL);
    }

    //出向社員請求明細データ更新(差額調整) SELECT
    public function procUpdateSeikyuMeisaiDataTyousei($data)
    {
        //実際の賞与
        //   取得項目
        //       ワーク出向社員請求明細対象者データ．社員番号
        //       支給データ．支給１
        //       事業主データ．事業主１
        //       事業主データ．事業主１_１
        //       事業主データ．事業主２
        //       事業主データ．事業主３
        //       事業主データ．事業主６
        //   使用テーブル
        //       ワーク出向社員請求明細対象者データ
        //       支給データ
        //       事業主データ
        //   結合条件
        //       ワーク出向社員請求明細対象者データ．対象年月＝支給データ．対象年月
        //       ワーク出向社員請求明細対象者データ．社員番号＝支給データ．社員番号
        //       支給データ．給与・賞与区分＝"2"
        //       支給データ．社員番号＝事業主データ．社員番号
        //       支給データ．対象年月＝事業主データ．対象年月
        //       支給データ．給与・賞与区分＝事業主データ．給与・賞与区分
        //   抽出条件
        //       ワーク出向社員請求明細対象者データ．対象年月＝画面．対象年月

        //見積の賞与
        //   取得項目
        //       ワーク出向社員請求明細対象者データ．社員番号
        //       SUM(出向社員請求明細データ．賞与）
        //       SUM(出向社員請求明細データ．賞与健康保険料）
        //       SUM(出向社員請求明細データ．賞与介護保険料）
        //       SUM(出向社員請求明細データ．賞与厚生年金）
        //       SUM(出向社員請求明細データ．賞与児童手当）
        //       SUM(出向社員請求明細データ．賞与雇用保険料）
        //   使用テーブル
        //       ワーク出向社員請求明細対象者データ
        //       出向社員請求明細データ
        //   結合条件
        //       ワーク出向社員請求明細対象者データ．社員番号＝出向社員請求明細データ．社員番号
        //       出向社員請求明細データ．対象年月>='@STARTKIKAN'
        //       出向社員請求明細データ．対象年月<='@ENDKIKAN'
        //   抽出条件
        //       ワーク出向社員請求明細対象者データ．対象年月＝画面．対象年月
        //
        //       @STARTKIKAN = 変数．ボーナス評価期間開始
        //       @ENDKIKAN = 変数．ボーナス評価期終了

        //*************実際の賞与と見積との差額を求める*************
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = " SELECT" . "\r\n";
        $strSQL .= "  X.SYAIN_NO," . "\r\n";
        $strSQL .= "  NVL(X.SHIKYU1, 0) - NVL(Y.BNS_GK, 0) AS BNS_GK," . "\r\n";
        $strSQL .= "  NVL(X.JIGYOUNUSHI1, 0) - NVL(Y.BNS_KENKO_HKN_RYO, 0) AS BNS_KENKO_HKN_RYO," . "\r\n";
        $strSQL .= "  NVL(X.JIGYOUNUSHI1_1, 0) - NVL(Y.BNS_KAIGO_HKN_RYO, 0) AS BNS_KAIGO_HKN_RYO," . "\r\n";
        $strSQL .= "  NVL(X.JIGYOUNUSHI2, 0) - NVL(Y.BNS_KOUSEI_NENKIN, 0) AS BNS_KOUSEI_NENKIN," . "\r\n";
        $strSQL .= "  NVL(X.JIGYOUNUSHI3, 0) - NVL(Y.BNS_JIDOU_TEATE, 0) AS BNS_JIDOU_TEATE," . "\r\n";
        $strSQL .= "  NVL(X.JIGYOUNUSHI6, 0) - NVL(Y.BNS_KOYOU_HOKEN, 0) AS BNS_KOYOU_HOKEN" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " (SELECT" . "\r\n";
        $strSQL .= " WK.SYAIN_NO," . "\r\n";
        $strSQL .= " JKS.SHIKYU1," . "\r\n";
        $strSQL .= " TRUNC(JKJ.JIGYOUNUSHI1, 0) JIGYOUNUSHI1," . "\r\n";
        $strSQL .= " TRUNC(JKJ.JIGYOUNUSHI1_1, 0) JIGYOUNUSHI1_1," . "\r\n";
        $strSQL .= " TRUNC(JKJ.JIGYOUNUSHI2, 0) JIGYOUNUSHI2," . "\r\n";
        $strSQL .= " TRUNC(JKJ.JIGYOUNUSHI3, 0) JIGYOUNUSHI3," . "\r\n";
        $strSQL .= " TRUNC(JKJ.JIGYOUNUSHI6, 0) JIGYOUNUSHI6" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " WK_JKSKOSEIKYUTAISYOU WK" . "\r\n";
        $strSQL .= " LEFT JOIN JKSHIKYU JKS" . "\r\n";
        $strSQL .= " ON WK.TAISYOU_YM = JKS.TAISYOU_YM" . "\r\n";
        $strSQL .= " AND WK.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQL .= " AND JKS.KS_KB = '2'" . "\r\n";
        $strSQL .= " LEFT JOIN JKJIGYOUNUSHI JKJ" . "\r\n";
        $strSQL .= " ON JKS.TAISYOU_YM = JKJ.TAISYOU_YM" . "\r\n";
        $strSQL .= " AND JKS.SYAIN_NO = JKJ.SYAIN_NO" . "\r\n";
        $strSQL .= " AND JKS.KS_KB = JKJ.KS_KB" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= " WK.TAISYOU_YM = @REP1" . "\r\n";
        $strSQL .= " ) X" . "\r\n";
        $strSQL .= " LEFT JOIN" . "\r\n";
        $strSQL .= " (SELECT" . "\r\n";
        $strSQL .= " WK.SYAIN_NO," . "\r\n";
        $strSQL .= " NVL(SUM(JKS.BNS_GK_MT), 0) AS BNS_GK," . "\r\n";
        $strSQL .= " NVL(SUM(JKS.BNS_KENKO_HKN_RYO_MT), 0) AS BNS_KENKO_HKN_RYO," . "\r\n";
        $strSQL .= " NVL(SUM(JKS.BNS_KAIGO_HKN_RYO_MT), 0) AS BNS_KAIGO_HKN_RYO," . "\r\n";
        $strSQL .= " NVL(SUM(JKS.BNS_KOUSEI_NENKIN_MT), 0) AS BNS_KOUSEI_NENKIN," . "\r\n";
        $strSQL .= " NVL(SUM(JKS.BNS_JIDOU_TEATE_MT), 0) AS BNS_JIDOU_TEATE," . "\r\n";
        $strSQL .= " NVL(SUM(JKS.BNS_KOYOU_HOKEN_MT), 0) AS BNS_KOYOU_HOKEN" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " WK_JKSKOSEIKYUTAISYOU WK" . "\r\n";
        $strSQL .= " LEFT JOIN JKSKOSEIKYUMEISAI JKS" . "\r\n";
        $strSQL .= " ON WK.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        $strSQL .= " AND JKS.TAISYOU_YM >= @REP1" . "\r\n";
        $strSQL .= " AND JKS.TAISYOU_YM <= @REP2" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= " WK.TAISYOU_YM = @REP3" . "\r\n";
        $strSQL .= " GROUP BY" . "\r\n";
        $strSQL .= " WK.SYAIN_NO";
        $strSQL .= " ) Y";
        $strSQL .= " ON X.SYAIN_NO = Y.SYAIN_NO";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($data['strJudgeTermFrom']), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($data['strJudgeTermTo']), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($data['dtpYM']), $strSQL);

        return parent::select($strSQL);
    }

    //出向社員請求明細データ更新(差額調整) UPDATE
    public function procUpdateSeikyuMeisaiDataTyousei2($dtpYM, $value)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = " UPDATE JKSKOSEIKYUMEISAI SET" . "\r\n";
        $strSQL .= "  BNS_GK = BNS_GK + @REP1" . "\r\n";
        $strSQL .= " ,BNS_KENKO_HKN_RYO = BNS_KENKO_HKN_RYO + @REP2" . "\r\n";
        $strSQL .= " ,BNS_KAIGO_HKN_RYO = BNS_KAIGO_HKN_RYO + @REP3" . "\r\n";
        $strSQL .= " ,BNS_KOUSEI_NENKIN = BNS_KOUSEI_NENKIN + @REP4" . "\r\n";
        $strSQL .= " ,BNS_JIDOU_TEATE = BNS_JIDOU_TEATE + @REP5" . "\r\n";
        $strSQL .= " ,BNS_KOYOU_HOKEN = BNS_KOYOU_HOKEN + @REP6" . "\r\n";
        $strSQL .= " WHERE TAISYOU_YM = @REP7" . "\r\n";
        $strSQL .= " AND   SYAIN_NO = @REP8";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_GK'])), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KENKO_HKN_RYO'])), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KAIGO_HKN_RYO'])), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOUSEI_NENKIN'])), $strSQL);
        $strSQL = str_replace("@REP5", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_JIDOU_TEATE'])), $strSQL);
        $strSQL = str_replace("@REP6", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOYOU_HOKEN'])), $strSQL);
        $strSQL = str_replace("@REP7", $this->ClsComFncJKSYS->FncSqlNv($dtpYM), $strSQL);
        $strSQL = str_replace("@REP8", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNv($value['SYAIN_NO'])), $strSQL);

        return parent::update($strSQL);
    }

    //出向社員請求明細データ更新(前月分賞与から今月分の賞与見積額を算出) SELECT
    public function procUpdateSeikyuMeisaiDataKurikoshi($data)
    {
        //       ※「テーブル編集仕様書②」を参照
        //       使用テーブル
        //           支給データ
        //           事業主データ
        //           ワーク出向社員請求明細対象者データ
        //       結合条件
        //           支給データ．社員番号＝事業主データ．社員番号
        //           支給データ．対象年月＝事業主データ．対象年月
        //           支給データ．給与・賞与区分＝事業主データ．給与・賞与区分
        //           支給データ．対象年月＝ワーク出向社員請求明細対象者データ．対象年月
        //           支給データ．社員番号＝ワーク出向社員請求明細対象者データ．社員番号
        //       抽出条件
        //           支給データ．給与・賞与区分＝"2"
        //           ワーク出向社員請求明細対象者データ．対象年月＝画面．対象年月
        //       更新条件
        //           出向社員請求明細データ．対象年月＝画面．対象年月
        //           出向社員請求明細データ．社員番号＝支給データ．社員番号
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = " SELECT" . "\r\n";
        $strSQL .= " WK.TAISYOU_YM," . "\r\n";
        $strSQL .= " WK.SYAIN_NO," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKS.SHIKYU1 / 6), -2) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_GK," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKS.SHIKYU1 / 6), -2) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_GK_MT," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KENKO_HKN_RYO," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KENKO_HKN_RYO_MT," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI1_1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KAIGO_HKN_RYO	," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI1_1 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KAIGO_HKN_RYO_MT," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI2 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOUSEI_NENKIN	," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI2 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOUSEI_NENKIN_MT," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI3 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_JIDOU_TEATE," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI3 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_JIDOU_TEATE_MT," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI6 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOYOU_HOKEN," . "\r\n";
        $strSQL .= " TRUNC(ROUND((JKJ.JIGYOUNUSHI6 / 6), 0) * (NVL(WK.SYUKKIN_NISSU,1) / NVL(WK.SYUGYOU_NISSU,1)), 0) AS BNS_KOYOU_HOKEN_MT" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " JKSHIKYU JKS" . "\r\n";
        $strSQL .= " INNER JOIN JKJIGYOUNUSHI JKJ" . "\r\n";
        $strSQL .= " ON JKS.TAISYOU_YM = JKJ.TAISYOU_YM" . "\r\n";
        $strSQL .= " AND JKS.SYAIN_NO = JKJ.SYAIN_NO" . "\r\n";
        $strSQL .= " AND JKS.KS_KB = JKJ.KS_KB" . "\r\n";
        $strSQL .= " INNER JOIN WK_JKSKOSEIKYUTAISYOU WK" . "\r\n";
        $strSQL .= " ON WK.TAISYOU_YM = @REP2" . "\r\n";
        $strSQL .= " AND JKS.SYAIN_NO = WK.SYAIN_NO" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= " JKS.KS_KB = '2'" . "\r\n";
        $strSQL .= " AND" . "\r\n";
        $strSQL .= " JKS.TAISYOU_YM = @REP1";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($data['strJudgeTermFrom']), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($data['dtpYM']), $strSQL);

        return parent::select($strSQL);
    }

    //出向社員請求明細データ更新(前月分賞与から今月分の賞与見積額を算出) UPDATE
    public function procUpdateSeikyuMeisaiDataKurikoshi2($value)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = " UPDATE JKSKOSEIKYUMEISAI SET" . "\r\n";
        $strSQL .= " BNS_GK = @REP1" . "\r\n";
        $strSQL .= " ,BNS_GK_MT = @REP2" . "\r\n";
        $strSQL .= " ,BNS_KENKO_HKN_RYO = @REP3" . "\r\n";
        $strSQL .= " ,BNS_KENKO_HKN_RYO_MT = @REP4" . "\r\n";
        $strSQL .= " ,BNS_KAIGO_HKN_RYO = @REP5" . "\r\n";
        $strSQL .= " ,BNS_KAIGO_HKN_RYO_MT = @REP6" . "\r\n";
        $strSQL .= " ,BNS_KOUSEI_NENKIN = @REP7" . "\r\n";
        $strSQL .= " ,BNS_KOUSEI_NENKIN_MT = @REP8" . "\r\n";
        $strSQL .= " ,BNS_JIDOU_TEATE = @REP9" . "\r\n";
        $strSQL .= " ,BNS_JIDOU_TEATE_MT = @REPA" . "\r\n";
        $strSQL .= " ,BNS_KOYOU_HOKEN = @REPB" . "\r\n";
        $strSQL .= " ,BNS_KOYOU_HOKEN_MT = @REPC" . "\r\n";
        $strSQL .= " WHERE TAISYOU_YM = @REPD" . "\r\n";
        $strSQL .= " AND   SYAIN_NO = @REPE";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_GK'])), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_GK_MT'])), $strSQL);
        $strSQL = str_replace("@REP3", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KENKO_HKN_RYO'])), $strSQL);
        $strSQL = str_replace("@REP4", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KENKO_HKN_RYO_MT'])), $strSQL);
        $strSQL = str_replace("@REP5", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KAIGO_HKN_RYO'])), $strSQL);
        $strSQL = str_replace("@REP6", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KAIGO_HKN_RYO_MT'])), $strSQL);
        $strSQL = str_replace("@REP7", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOUSEI_NENKIN'])), $strSQL);
        $strSQL = str_replace("@REP8", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOUSEI_NENKIN_MT'])), $strSQL);
        $strSQL = str_replace("@REP9", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_JIDOU_TEATE'])), $strSQL);
        $strSQL = str_replace("@REPA", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_JIDOU_TEATE_MT'])), $strSQL);
        $strSQL = str_replace("@REPB", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOYOU_HOKEN'])), $strSQL);
        $strSQL = str_replace("@REPC", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNz($value['BNS_KOYOU_HOKEN_MT'])), $strSQL);
        $strSQL = str_replace("@REPD", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNv($value['TAISYOU_YM'])), $strSQL);
        $strSQL = str_replace("@REPE", $this->ClsComFncJKSYS->FncSqlNv($this->ClsComFncJKSYS->FncNv($value['SYAIN_NO'])), $strSQL);

        return parent::update($strSQL);
    }

    public function fncGetName()
    {
        $strSQL = 'SELECT SYAIN_NO,SYAIN_NM FROM JKSYAIN';

        return parent::select($strSQL);
    }

}
