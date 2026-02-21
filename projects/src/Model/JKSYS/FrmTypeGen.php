<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
//*************************************
// * 処理名    ：FrmTypeGen
// * 関数名    ：FrmTypeGen
// * 処理説明    ：共通クラスの読込み
//*************************************
class FrmTypeGen extends ClsComDb
{
    public $ClsComFncJKSYS;
    //人事コントロールマスタ選択SQL
    public function Sel_JKCONTROLMST_SQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID" . "\r\n";
        $strSQL .= "    ,SYORI_YM" . "\r\n";
        $strSQL .= "    ,KAKI_BONUS_MONTH " . "\r\n";
        $strSQL .= "    ,KAKI_BONUS_START_MT " . "\r\n";
        $strSQL .= "    ,KAKI_BONUS_END_MT " . "\r\n";
        $strSQL .= "    ,KAKI_HYOUKA_START_MT " . "\r\n";
        $strSQL .= "    ,KAKI_HYOUKA_END_MT " . "\r\n";
        $strSQL .= "    ,TOUKI_BONUS_MONTH " . "\r\n";
        $strSQL .= "    ,TOUKI_BONUS_START_MT " . "\r\n";
        $strSQL .= "    ,TOUKI_BONUS_END_MT " . "\r\n";
        $strSQL .= "    ,TOUKI_HYOUKA_START_MT " . "\r\n";
        $strSQL .= "    ,TOUKI_HYOUKA_END_MT " . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKCONTROLMST " . "\r\n";
        $strSQL .= "WHERE" . "\r\n";
        $strSQL .= "    ID = '01'" . "\r\n";

        return parent::select($strSQL);
    }

    //データ取得SQL
    public function Sel_DATA_SQL($dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= " SELECT " . "\r\n";
        $strSQL .= "   JKSYAIN.SYAIN_NO " . "\r\n";
        $strSQL .= "   , JKKOUKA_TYPE_MST.KOYOU_KB_CD " . "\r\n";
        $strSQL .= "   , JKSYAIN.ZAISEKI_KB_CD " . "\r\n";
        $strSQL .= "   , JKSYAIN.BIRTHDAY " . "\r\n";
        $strSQL .= "   , JKSYAIN.TAISYOKU_PLAN_DT " . "\r\n";
        $strSQL .= "   , JKSYAIN.TAISYOKU_DT " . "\r\n";
        $strSQL .= "   , IDOURIREKI.ANNOUNCE_DT " . "\r\n";
        $strSQL .= "   , IDOURIREKI.BUSYO_CD " . "\r\n";
        $strSQL .= "   , IDOURIREKI.SYOKUSYU_CD " . "\r\n";
        $strSQL .= "   , IDOURIREKI.SHIKAKU_CD  " . "\r\n";
        $strSQL .= "   , IDOURIREKI.ANNOUNCE_DT_MAX " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= "   (SELECT DISTINCT KOYOU_KB_CD FROM JKKOUKA_TYPE_MST)  JKKOUKA_TYPE_MST  " . "\r\n";
        $strSQL .= "   INNER JOIN JKSYAIN  " . "\r\n";
        $strSQL .= "     ON JKKOUKA_TYPE_MST.KOYOU_KB_CD = JKSYAIN.KOYOU_KB_CD  " . "\r\n";
        $strSQL .= "   INNER JOIN " . "\r\n";
        $strSQL .= "       (SELECT A.SYAIN_NO " . "\r\n";
        $strSQL .= "        , A.ANNOUNCE_DT " . "\r\n";
        $strSQL .= "        , A.BUSYO_CD " . "\r\n";
        $strSQL .= "        , A.SYOKUSYU_CD " . "\r\n";
        $strSQL .= "        , A.SHIKAKU_CD " . "\r\n";
        $strSQL .= "        , B.ANNOUNCE_DT_MAX " . "\r\n";
        $strSQL .= "       FROM JKIDOURIREKI A " . "\r\n";
        $strSQL .= "       INNER JOIN  " . "\r\n";
        $strSQL .= "       (SELECT " . "\r\n";
        $strSQL .= "         JKIDOURIREKI.SYAIN_NO AS SYAIN_NO " . "\r\n";
        $strSQL .= "         , Max(JKIDOURIREKI.ANNOUNCE_DT) AS ANNOUNCE_DT_MAX " . "\r\n";
        $strSQL .= "       FROM " . "\r\n";
        $strSQL .= "         JKIDOURIREKI  " . "\r\n";
        $strSQL .= "       WHERE " . "\r\n";
        $strSQL .= "         JKIDOURIREKI.ANNOUNCE_DT <= TO_DATE(@TAISYOKUYM,'yyyy/MM/dd') " . "\r\n";
        $strSQL .= "       GROUP BY " . "\r\n";
        $strSQL .= "         JKIDOURIREKI.SYAIN_NO) B " . "\r\n";
        $strSQL .= "       ON (A.SYAIN_NO = B.SYAIN_NO  " . "\r\n";
        $strSQL .= "           AND A.ANNOUNCE_DT = B.ANNOUNCE_DT_MAX)) IDOURIREKI" . "\r\n";
        $strSQL .= "     ON JKSYAIN.SYAIN_NO = IDOURIREKI.SYAIN_NO  " . "\r\n";
        $strSQL .= " WHERE " . "\r\n";
        $strSQL .= "    JKSYAIN.ZAISEKI_KB_CD = '0'" . "\r\n";
        $strSQL .= "    OR  ( add_months(JKSYAIN.BIRTHDAY, 12 * 60)<= JKSYAIN.TAISYOKU_DT " . "\r\n";
        $strSQL .= "   AND  JKSYAIN.TAISYOKU_DT >= TO_DATE(@TAISYOKUYM,'yyyy/MM/dd') " . "\r\n";
        $strSQL .= "   AND  add_months(JKSYAIN.BIRTHDAY, 12 * 61) > JKSYAIN.TAISYOKU_DT) " . "\r\n";
        $strSQL .= " ORDER BY " . "\r\n";
        $strSQL .= "   JKSYAIN.SYAIN_NO " . "\r\n";
        $strSQL .= "   , IDOURIREKI.ANNOUNCE_DT " . "\r\n";

        $strSQL = str_replace("@TAISYOKUYM", $this->ClsComFncJKSYS->FncSqlNv($this->GetEndDate($dtpYM)), $strSQL);

        return parent::select($strSQL);
    }

    //社員別考課表タイプデータの存在チェックＳＱＬ
    public function CHK_JKKOUKA_SYAIN_TYPE_SQL($dtpYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "    COUNT(*) AS CNT" . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKKOUKA_SYAIN_TYPE " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "    HYOUKA_KIKAN_END = '@dtpTaisyouKE'" . "\r\n";

        $strSQL = str_replace("@dtpTaisyouKE", $dtpYM, $strSQL);
        return parent::select($strSQL);
    }

    //社員別考課表タイプデータの削除ＳＱＬ
    public function DEL_JKKOUKA_SYAIN_TYPE_SQL($dtpYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM " . "\r\n";
        $strSQL .= "    JKKOUKA_SYAIN_TYPE" . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "    HYOUKA_KIKAN_END = '@dtpTaisyouKE'" . "\r\n";

        $strSQL = str_replace("@dtpTaisyouKE", $dtpYM, $strSQL);
        return parent::delete($strSQL);
    }

    //実績集計の削除ＳＱＬ
    public function DEL_JKKOUKA_JISSEKI_SYUKEI_SQL($dtpYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM " . "\r\n";
        $strSQL .= "    JKKOUKA_JISSEKI_SYUKEI" . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "    HYOUKA_KIKAN_END = '@dtpTaisyouKE'" . "\r\n";

        $strSQL = str_replace("@dtpTaisyouKE", $dtpYM, $strSQL);
        return parent::delete($strSQL);
    }

    //周辺利益集計データの削除ＳＱＬ
    public function DEL_JKKOUKA_SYUHEN_RIEKI_SQL($dtpYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM " . "\r\n";
        $strSQL .= "    JKKOUKA_SYUHEN_RIEKI" . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "    HYOUKA_KIKAN_END = '@dtpTaisyouKE'" . "\r\n";

        $strSQL = str_replace("@dtpTaisyouKE", $dtpYM, $strSQL);
        return parent::delete($strSQL);
    }

    //社員別考課表タイプデータを作成する
    public function fncCreateDataBef($resultSelIdx, $dtpYM, $strKOUKATYPE_CD, $strGROUP_CD)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYAIN_TYPE " . "\r\n";
        $strSQL .= "     (HYOUKA_KIKAN_START " . "\r\n";
        $strSQL .= "     , HYOUKA_KIKAN_END " . "\r\n";
        $strSQL .= "     , SYAIN_NO " . "\r\n";
        $strSQL .= "     , KOYOU_KB_CD " . "\r\n";
        $strSQL .= "     , BUSYO_CD " . "\r\n";
        $strSQL .= "     , SYOKUSYU_CODE " . "\r\n";
        $strSQL .= "     , SHIKAKU_CODE " . "\r\n";
        $strSQL .= "     , KOUKATYPE_CD " . "\r\n";
        $strSQL .= "     , GROUP_CD " . "\r\n";
        $strSQL .= "     , CREATE_DATE " . "\r\n";
        $strSQL .= "     , CRE_SYA_CD " . "\r\n";
        $strSQL .= "     , CRE_PRG_ID " . "\r\n";
        $strSQL .= "     , UPD_DATE " . "\r\n";
        $strSQL .= "     , UPD_SYA_CD " . "\r\n";
        $strSQL .= "     , UPD_PRG_ID " . "\r\n";
        $strSQL .= "     , UPD_CLT_NM) " . "\r\n";
        //評価対象期間開始
        $strSQL .= "VALUES('@REP1' " . "\r\n";
        //評価対象期間終了
        $strSQL .= "     , '@REP2' " . "\r\n";
        //社員番号
        $strSQL .= "     , @SYAIN_NO " . "\r\n";
        //雇用区分コード
        $strSQL .= "     , @KOYOU_KB_CD " . "\r\n";
        //部門コード
        $strSQL .= "     , @BUSYO_CD " . "\r\n";
        //職種コード
        $strSQL .= "     , @SYOKUSYU_CD " . "\r\n";
        //資格等級コード
        $strSQL .= "     , @SHIKAKU_CD " . "\r\n";
        //考課表タイプコード
        $strSQL .= "     , @KOUKATYPE_CD " . "\r\n";
        //グループコード
        $strSQL .= "     , @GROUP_CD " . "\r\n";
        //作成日付
        $strSQL .= "     , SYSDATE " . "\r\n";
        //作成者
        $strSQL .= "     , @SYA_CD " . "\r\n";
        //作成ＡＰＰ
        $strSQL .= "     , @PRG_ID " . "\r\n";
        //更新日付
        $strSQL .= "     , SYSDATE " . "\r\n";
        //更新者
        $strSQL .= "     , @SYA_CD " . "\r\n";
        //更新ＡＰＰ
        $strSQL .= "     , @PRG_ID " . "\r\n";
        //更新マシン
        $strSQL .= "     , @CLT_NM " . "\r\n";
        $strSQL .= " )" . " \r\n";

        $strSQL = str_replace("@REP1", $this->getPreMonth($dtpYM), $strSQL);
        $strSQL = str_replace("@REP2", $dtpYM, $strSQL);

        $strSQL = str_replace("@SYAIN_NO", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['SYAIN_NO']), $strSQL);
        $strSQL = str_replace("@KOYOU_KB_CD", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['KOYOU_KB_CD']), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['BUSYO_CD']), $strSQL);
        $strSQL = str_replace("@SYOKUSYU_CD", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['SYOKUSYU_CD']), $strSQL);
        $strSQL = str_replace("@SHIKAKU_CD", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['SHIKAKU_CD']), $strSQL);
        $strSQL = str_replace("@KOUKATYPE_CD", $this->ClsComFncJKSYS->FncSqlNv($strKOUKATYPE_CD), $strSQL);
        $strSQL = str_replace("@GROUP_CD", $this->ClsComFncJKSYS->FncSqlNv($strGROUP_CD), $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@PRG_ID", $this->ClsComFncJKSYS->FncSqlNv("PatternGen"), $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);

        return parent::insert($strSQL);
    }

    public function fncCreateDataAft($resultSelIdx, $dtpYM, $strBUSYO_CD, $strSYOKUSYU_CD, $strKOUKATYPE_CD, $strGROUP_CD)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKKOUKA_SYAIN_TYPE " . "\r\n";
        $strSQL .= "     (HYOUKA_KIKAN_START " . "\r\n";
        $strSQL .= "     , HYOUKA_KIKAN_END " . "\r\n";
        $strSQL .= "     , SYAIN_NO " . "\r\n";
        $strSQL .= "     , KOYOU_KB_CD " . "\r\n";
        $strSQL .= "     , BUSYO_CD " . "\r\n";
        $strSQL .= "     , SYOKUSYU_CODE " . "\r\n";
        $strSQL .= "     , SHIKAKU_CODE " . "\r\n";
        $strSQL .= "     , KOUKATYPE_CD " . "\r\n";
        $strSQL .= "     , GROUP_CD " . "\r\n";
        $strSQL .= "     , CREATE_DATE " . "\r\n";
        $strSQL .= "     , CRE_SYA_CD " . "\r\n";
        $strSQL .= "     , CRE_PRG_ID " . "\r\n";
        $strSQL .= "     , UPD_DATE " . "\r\n";
        $strSQL .= "     , UPD_SYA_CD " . "\r\n";
        $strSQL .= "     , UPD_PRG_ID " . "\r\n";
        $strSQL .= "     , UPD_CLT_NM) " . "\r\n";
        //評価対象期間開始
        $strSQL .= "VALUES('@REP1' " . "\r\n";
        //評価対象期間終了
        $strSQL .= "     , '@REP2' " . "\r\n";
        //社員番号
        $strSQL .= "     , @SYAIN_NO " . "\r\n";
        //雇用区分コード
        $strSQL .= "     , @KOYOU_KB_CD " . "\r\n";
        //部門コード
        $strSQL .= "     , @BUSYO_CD " . "\r\n";
        //職種コード
        $strSQL .= "     , @SYOKUSYU_CD " . "\r\n";
        //資格等級コード
        $strSQL .= "     , @SHIKAKU_CD " . "\r\n";
        //考課表タイプコード
        $strSQL .= "     , @KOUKATYPE_CD " . "\r\n";
        //グループコード
        $strSQL .= "     , @GROUP_CD " . "\r\n";
        //作成日付
        $strSQL .= "     , SYSDATE " . "\r\n";
        //作成者
        $strSQL .= "     , @SYA_CD " . "\r\n";
        //作成ＡＰＰ
        $strSQL .= "     , @PRG_ID " . "\r\n";
        //更新日付
        $strSQL .= "     , SYSDATE " . "\r\n";
        //更新者
        $strSQL .= "     , @SYA_CD " . "\r\n";
        //更新ＡＰＰ
        $strSQL .= "     , @PRG_ID " . "\r\n";
        //更新マシン
        $strSQL .= "     , @CLT_NM " . "\r\n";
        $strSQL .= " )" . " \r\n";

        $strSQL = str_replace("@REP1", $this->getPreMonth($dtpYM), $strSQL);
        $strSQL = str_replace("@REP2", $dtpYM, $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['SYAIN_NO']), $strSQL);
        $strSQL = str_replace("@KOYOU_KB_CD", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['KOYOU_KB_CD']), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFncJKSYS->FncSqlNv($strBUSYO_CD), $strSQL);
        $strSQL = str_replace("@SYOKUSYU_CD", $this->ClsComFncJKSYS->FncSqlNv($strSYOKUSYU_CD), $strSQL);
        $strSQL = str_replace("@SHIKAKU_CD", $this->ClsComFncJKSYS->FncSqlNv($resultSelIdx['SHIKAKU_CD']), $strSQL);
        $strSQL = str_replace("@KOUKATYPE_CD", $this->ClsComFncJKSYS->FncSqlNv($strKOUKATYPE_CD), $strSQL);
        $strSQL = str_replace("@GROUP_CD", $this->ClsComFncJKSYS->FncSqlNv($strGROUP_CD), $strSQL);
        $strSQL = str_replace("@SYA_CD", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strUserID']), $strSQL);
        $strSQL = str_replace("@PRG_ID", $this->ClsComFncJKSYS->FncSqlNv("PatternGen"), $strSQL);
        $strSQL = str_replace("@CLT_NM", $this->ClsComFncJKSYS->FncSqlNv($this->GS_LOGINUSER['strClientNM']), $strSQL);

        return parent::insert($strSQL);
    }

    //考課表タイプ設定マスタ取得
    public function fncGetTYPE_SETTEI($vSKS)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "    KOUKATYPE_CD" . "\r\n";
        $strSQL .= "    ,GROUP_CD " . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKKOUKA_TYPESETTEI_MST " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "    SYOKUSYU_CD = @SYOKUSYU_CD " . "\r\n";

        $strSQL = str_replace("@SYOKUSYU_CD", $this->ClsComFncJKSYS->FncSqlNv($vSKS), $strSQL);
        return parent::select($strSQL);
    }

    //異動履歴データから最長データの情報を取得する
    public function fncGetMaxIdouRireki($vSB, $dtpYM)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "    A.SYAIN_NO AS SYAIN_NO" . "\r\n";
        $strSQL .= "    ,A.ANNOUNCE_DT " . "\r\n";
        $strSQL .= "    ,A.BUSYO_CD " . "\r\n";
        $strSQL .= "    ,A.SYOKUSYU_CD " . "\r\n";
        $strSQL .= "    ,B.KOUKATYPE_CD " . "\r\n";
        $strSQL .= "    ,B.GROUP_CD " . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    JKIDOURIREKI A " . "\r\n";
        $strSQL .= "INNER JOIN " . "\r\n";
        $strSQL .= "    JKKOUKA_TYPESETTEI_MST B " . "\r\n";
        $strSQL .= "ON A.SYOKUSYU_CD = B.SYOKUSYU_CD " . "\r\n";
        $strSQL .= "WHERE " . "\r\n";
        $strSQL .= "    A.SYAIN_NO = @REP1 " . "\r\n";
        $strSQL .= "AND " . "\r\n";
        $strSQL .= "    A.ANNOUNCE_DT <= @REP2 " . "\r\n";
        $strSQL .= "ORDER BY A.ANNOUNCE_DT DESC " . "\r\n";

        $strSQL = str_replace("@REP1", $this->ClsComFncJKSYS->FncSqlNv($vSB), $strSQL);
        $strSQL = str_replace("@REP2", $this->ClsComFncJKSYS->FncSqlNv($this->GetEndDate($dtpYM)), $strSQL);

        return parent::select($strSQL);
    }

    //年月-numか月
    public function getPreMonth($dtpYM)
    {
        $dtpYM = $dtpYM . "01";
        $rtnDate = date('Ym', strtotime($dtpYM . ' -5 month'));

        return $rtnDate;
    }

    //指定年月の末日を取得する
    public function GetEndDate($dtpYM)
    {
        $dtpYM = $dtpYM . "01";
        $lastday = date('Y/m/d', strtotime($dtpYM . ' +1 month -1 day'));

        return $lastday;
    }

    //20220419 lqs ins S
    public function getDiffMonth($start, $end)
    {
        $strSQL = "";
        $strSQL .= "SELECT FLOOR(months_between(date'@END',date'@START')) months" . "\r\n";
        $strSQL .= "FROM " . "\r\n";
        $strSQL .= "    DUAL " . "\r\n";

        $strSQL = str_replace("@START", $start, $strSQL);
        $strSQL = str_replace("@END", $end, $strSQL);

        return parent::select($strSQL);
    }
    //20220419 lqs ins E

}
