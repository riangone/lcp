<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
/**
 * 説明：
 *
 *
 * @author
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20231220           機能修正     202312_人件費データ生成処理修正対応-NO1      caina
 * 20240307           202402_人事給与システム_人件費データexce入出力機能追加l   caina
 * --------------------------------------------------------------------------------------------
 */

//*************************************
// * 処理名	：FrmJinkenhiInfoCreate
// * 関数名	：FrmJinkenhiInfoCreate
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmJinkenhiInfoCreate extends ClsComDb
{
    //処理年月取得SQL
    public function selShoriYMSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT SYORI_YM" . "\r\n";
        $strSQL .= "FROM  JKCONTROLMST" . "\r\n";
        $strSQL .= "WHERE ID = '01'" . "\r\n";

        return parent::select($strSQL);
    }

    //支給データ取得SQL
    public function selShikyuDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(TAISYOU_YM) CNTYM " . "\r\n";
        $strSQL .= " FROM   JKSHIKYU" . "\r\n";
        $strSQL .= " WHERE  TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //事業主データ取得SQL
    public function selJigyonushiDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(TAISYOU_YM) CNTYM " . "\r\n";
        $strSQL .= "FROM   JKJIGYOUNUSHI" . "\r\n";
        $strSQL .= "WHERE  TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //人件費振替比率データ取得SQL
    public function selFurikaehiritsuDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(TAISYOU_YM) CNTYM " . "\r\n";
        $strSQL .= "FROM   JKJINKENHIFURIKAEHIRITU" . "\r\n";
        $strSQL .= "WHERE  TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //人件費データ取得SQL
    public function selJinkenhiDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(TAISYOU_YM) CNTYM " . "\r\n";
        $strSQL .= "FROM   JKJINKENHI" . "\r\n";
        $strSQL .= "WHERE  TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //支給データ_業績奨励金A、B取得SQL
    public function selShikyuSyoreiKinDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT COUNT(TAISYOU_YM) CNTYM " . "\r\n";
        $strSQL .= " FROM   JKSHIKYU" . "\r\n";
        $strSQL .= "WHERE (SHIKYU3 <> 0 " . "\r\n";
        $strSQL .= "  OR   SHIKYU4 <> 0 )" . "\r\n";
        $strSQL .= "  AND  TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //人件費データ削除SQL
    public function delJinkenhiDataSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "DELETE" . "\r\n";
        $strSQL .= "FROM   JKJINKENHI" . "\r\n";
        $strSQL .= "WHERE  TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::delete($strSQL);
    }

    //人件費データ登録SQL
    public function insJinkenhiDataSQL($kbn, $taishoYM)
    {
        $ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "";
        $strSQL .= "INSERT INTO JKJINKENHI" . "\r\n";
        $strSQL .= "    (TAISYOU_YM" . "\r\n";
        $strSQL .= "    ,SYAIN_NO" . "\r\n";
        $strSQL .= "    ,BUSYO_CD" . "\r\n";
        $strSQL .= "    ,KOYOU_KB" . "\r\n";
        $strSQL .= "    ,SYOKUSYU_CD" . "\r\n";
        $strSQL .= "    ,KIHONKYU" . "\r\n";
        $strSQL .= "    ,TEIJIKAN_GESSYU" . "\r\n";
        $strSQL .= "    ,ZANGYOU_TEATE" . "\r\n";
        $strSQL .= "    ,GYOUSEKI_SYOUREI" . "\r\n";
        $strSQL .= "    ,HOKA_GSK_SYOUREI" . "\r\n";
        $strSQL .= "    ,SONOTA_TEATE" . "\r\n";
        $strSQL .= "    ,KENKO_HKN_RYO" . "\r\n";
        $strSQL .= "    ,KAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "    ,KOUSEINENKIN" . "\r\n";
        $strSQL .= "    ,KOYOU_HKN_RYO" . "\r\n";
        $strSQL .= "    ,ROUSAI_HKN_RYO" . "\r\n";
        $strSQL .= "    ,JIDOUTEATE" . "\r\n";
        $strSQL .= "    ,TAISYOKU_KYUFU" . "\r\n";
        $strSQL .= "    ,BNS_MITUMORI" . "\r\n";
        $strSQL .= "    ,BNS_KENKO_HKN_RYO" . "\r\n";
        $strSQL .= "    ,BNS_KAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "    ,BNS_KOUSEI_NENKIN" . "\r\n";
        $strSQL .= "    ,BNS_JIDOU_TEATE" . "\r\n";
        $strSQL .= "    ,JININ_CNT" . "\r\n";
        $strSQL .= "    ,CREATE_DATE" . "\r\n";
        $strSQL .= "    ,CRE_SYA_CD" . "\r\n";
        $strSQL .= "    ,CRE_PRG_ID" . "\r\n";
        $strSQL .= "    ,UPD_DATE" . "\r\n";
        $strSQL .= "    ,UPD_SYA_CD" . "\r\n";
        $strSQL .= "    ,UPD_PRG_ID" . "\r\n";
        $strSQL .= "    ,UPD_CLT_NM)" . "\r\n";
        //役員以外
        if ($kbn == 0) {
            //対象年月
            $strSQL .= "SELECT   '" . $taishoYM . "'" . "\r\n";
            //社員番号
            $strSQL .= "        ,SYA.SYAIN_NO" . "\r\n";
            //部署コード
            $strSQL .= "        ,IDO.BUSYO_CD" . "\r\n";
            //雇用区分
            $strSQL .= "        ,DECODE(KOY.SYAIN_NO, NULL, SYA.KOYOU_KB_CD, KOY.BEF_KOYOU_KB_CD)" . "\r\n";
            //職種コード
            $strSQL .= "        ,IDO.SYOKUSYU_CD" . "\r\n";
            //基本給
            $strSQL .= "        ,CASE TAN.KYUUYO_SYSTEM_KB_CD WHEN '0' THEN ROUND(TAN.KIHONKYU)" . "\r\n";
            $strSQL .= "                                      WHEN '1' THEN ROUND(TAN.KIHONKYU * " . $ClsComFncJKSYS::GMONTHAVGDAYS . ")" . "\r\n";
            $strSQL .= "                                      WHEN '2' THEN ROUND(TAN.KIHONKYU * " . $ClsComFncJKSYS::GMONTHAVGTIMES . ")" . "\r\n";
            $strSQL .= "                                      WHEN '3' THEN ROUND(TAN.KIHONKYU * " . $ClsComFncJKSYS::GMONTHAVGDAYS . ")" . "\r\n";
            $strSQL .= "                                      ELSE 0" . "\r\n";
            $strSQL .= "         END KIHONKYU" . "\r\n";
            //減額金はプラスではなくマイナス
            $strSQL .= "        ,NVL(SHI.SHIKYU1, 0) + NVL(SHI.SHIKYU2, 0) + NVL(SHI.SHIKYU5, 0) - NVL(SHI.SHIKYU17, 0) - NVL(SHI.SHIKYU20, 0)" . "\r\n";
            //定時間月収
            $strSQL .= "        ,NVL(SHI.SHIKYU19, 0)" . "\r\n";
            //残業手当
            $strSQL .= "        ,NVL(SHI.SHIKYU3, 0) + NVL(SHI.SHIKYU4, 0)" . "\r\n";
            //業績奨励金
            $strSQL .= "        ,NVL(SHI.SHIKYU10, 0) + NVL(SHI.SHIKYU11, 0)" . "\r\n";
            //業績奨励金
            //紹介手当が含まれると現行のＣＳＶと差が出たため、含まない
            $strSQL .= "        ,NVL(SHI.SHIKYU6,0)  + NVL(SHI.SHIKYU8,0) + NVL(SHI.SHIKYU9,0) + NVL(SHI.SHIKYU12,0)  + NVL(SHI.SHIKYU13,0) + NVL(SHI.SHIKYU14,0) + NVL(SHI.SHIKYU16,0) + NVL(SHI.SHIKYU18, 0) + NVL(SHI.SHIKYU18_1,0) " . "\r\n";
            //其他手当
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI1, 0)" . "\r\n";
            //健康保険料
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI1_1, 0)" . "\r\n";
            //介護保険料
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI2, 0)" . "\r\n";
            //厚生年金
            $strSQL .= "        ,0" . "\r\n";
            //雇用保険料
            $strSQL .= "        ,0" . "\r\n";
            //労災保険料
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI3, 0)" . "\r\n";
            //児童手当
            //20220118 lujunxia upd s
            //20220117_人事給与システム_人件費データ生成仕様変更.xlsx
            $strSQL .= "        ,NVL(KOU.KINGAKU, 0)" . "\r\n";
            //$strSQL .= "        ,NVL(KOU.KOUJYO7, 0)" . "\r\n";
            //20220118 lujunxia upd e
            //退職給付
            $strSQL .= "        ,0" . "\r\n";
            //賞与見積
            $strSQL .= "        ,0" . "\r\n";
            //賞与健康保険料
            $strSQL .= "        ,0" . "\r\n";
            //賞与介護保険料
            $strSQL .= "        ,0" . "\r\n";
            //賞与厚生年金
            $strSQL .= "        ,0" . "\r\n";
            //賞与児童手当
            $strSQL .= "        ,1" . "\r\n";
            //人員カウント
            $strSQL .= "        ,SYSDATE" . "\r\n";
            //作成日付
            $strSQL .= "        ,'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
            //作成者
            $strSQL .= "        ,'JinkenhiInfoCreate'" . "\r\n";
            //作成APP
            $strSQL .= "        ,SYSDATE" . "\r\n";
            //更新日付
            $strSQL .= "        ,'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
            //更新者
            $strSQL .= "        ,'JinkenhiInfoCreate'" . "\r\n";
            //更新APP
            $strSQL .= "        ,'" . $this->GS_LOGINUSER['strClientNM'] . "'" . "\r\n";
            //更新マシン
        } else {
            //役員
            $strSQL .= "SELECT   '" . $taishoYM . "'" . "\r\n";
            //対象年月
            $strSQL .= "        ,SYA.SYAIN_NO" . "\r\n";
            //社員番号
            $strSQL .= "        ,IDO.BUSYO_CD" . "\r\n";
            //部署コード
            $strSQL .= "        ,DECODE(KOY.SYAIN_NO, NULL, SYA.KOYOU_KB_CD, KOY.BEF_KOYOU_KB_CD)" . "\r\n";
            //雇用区分
            $strSQL .= "        ,IDO.SYOKUSYU_CD" . "\r\n";
            //職種コード
            $strSQL .= "        ,0" . "\r\n";
            //基本給
            $strSQL .= "        ,0" . "\r\n";
            //定時間月収
            $strSQL .= "        ,0" . "\r\n";
            //残業手当
            $strSQL .= "        ,0" . "\r\n";
            //業績奨励金
            $strSQL .= "        ,0" . "\r\n";
            //他業績奨励金
            $strSQL .= "        ,0" . "\r\n";
            //其他手当
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI1, 0)" . "\r\n";
            //健康保険料
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI1_1, 0)" . "\r\n";
            //介護保険料
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI2, 0)" . "\r\n";
            //厚生年金
            $strSQL .= "        ,0" . "\r\n";
            //雇用保険料
            $strSQL .= "        ,0" . "\r\n";
            //労災保険料
            $strSQL .= "        ,NVL(JIG.JIGYOUNUSHI3, 0)" . "\r\n";
            //児童手当
            //20220118 lujunxia upd s
            //20220117_人事給与システム_人件費データ生成仕様変更.xlsx
            $strSQL .= "        ,NVL(KOU.KINGAKU, 0)" . "\r\n";
            //$strSQL .= "        ,NVL(KOU.KOUJYO7, 0)" . "\r\n";
            //20220118 lujunxia upd e
            //退職給付
            $strSQL .= "        ,0" . "\r\n";
            //賞与見積
            $strSQL .= "        ,0" . "\r\n";
            //賞与健康保険料
            $strSQL .= "        ,0" . "\r\n";
            //賞与介護保険料
            $strSQL .= "        ,0" . "\r\n";
            //賞与厚生年金
            $strSQL .= "        ,0" . "\r\n";
            //賞与児童手当
            $strSQL .= "        ,1" . "\r\n";
            //人員カウント
            $strSQL .= "        ,SYSDATE" . "\r\n";
            //作成日付
            $strSQL .= "        ,'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
            //作成者
            $strSQL .= "        ,'JinkenhiInfoCreate'" . "\r\n";
            //作成APP
            $strSQL .= "        ,SYSDATE" . "\r\n";
            //更新日付
            $strSQL .= "        ,'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
            //更新者
            $strSQL .= "        ,'JinkenhiInfoCreate'" . "\r\n";
            //更新APP
            $strSQL .= "        ,'" . $this->GS_LOGINUSER['strClientNM'] . "'" . "\r\n";
            //更新マシン
        }

        //社員マスタ
        $strSQL .= "FROM    JKSYAIN SYA" . "\r\n";

        //その他データ
        $strSQL .= "        LEFT JOIN  JKSONOTA SON" . "\r\n";
        $strSQL .= "            ON    SON.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "            AND   SYA.SYAIN_NO = SON.SYAIN_NO" . "\r\n";
        $strSQL .= "            AND   SON.KS_KB = '1'" . "\r\n";

        //社員マスタ(異動履歴)
        $strSQL .= "        LEFT JOIN (SELECT   IDO2.SYAIN_NO" . "\r\n";
        $strSQL .= "                           ,IDO2.ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                           ,IDO2.BUSYO_CD" . "\r\n";
        $strSQL .= "                           ,IDO2.SYOKUSYU_CD" . "\r\n";
        $strSQL .= "                   FROM    JKIDOURIREKI IDO2" . "\r\n";
        $strSQL .= "                           INNER JOIN  (SELECT   IDO3.SYAIN_NO" . "\r\n";
        $strSQL .= "                                                 ,MAX(IDO3.ANNOUNCE_DT) ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                                         FROM    JKIDOURIREKI IDO3" . "\r\n";
        //20210303 CI UPD S
        $strSQL .= "                                         WHERE  SUBSTR(TO_CHAR(IDO3.ANNOUNCE_DT,'YYYYMM'),1,6) <=  " . $taishoYM . " " . "\r\n";
        //20210303 CI UPD E
        //社員マスタ(異動履歴)
        $strSQL .= "                                         GROUP BY IDO3.SYAIN_NO" . "\r\n";
        $strSQL .= "                                        ) IDO4" . "\r\n";
        $strSQL .= "                               ON    IDO2.SYAIN_NO = IDO4.SYAIN_NO" . "\r\n";
        $strSQL .= "                               AND   IDO2.ANNOUNCE_DT = IDO4.ANNOUNCE_DT" . "\r\n";
        $strSQL .= "                  ) IDO" . "\r\n";
        $strSQL .= "            ON    SYA.SYAIN_NO = IDO.SYAIN_NO" . "\r\n";

        //役員以外のみ
        if ($kbn == 0) {
            //支給データ
            $strSQL .= "        LEFT JOIN  JKSHIKYU SHI" . "\r\n";
            $strSQL .= "            ON    SHI.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
            $strSQL .= "            AND   SYA.SYAIN_NO = SHI.SYAIN_NO" . "\r\n";
            $strSQL .= "            AND   SHI.KS_KB = '1'" . "\r\n";
        }

        //事業主データ
        $strSQL .= "        LEFT JOIN  JKJIGYOUNUSHI JIG" . "\r\n";
        $strSQL .= "            ON    JIG.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "            AND   SYA.SYAIN_NO = JIG.SYAIN_NO" . "\r\n";
        $strSQL .= "            AND   JIG.KS_KB = '1'" . "\r\n";
        //20220118 lujunxia upd s
        //20220117_人事給与システム_人件費データ生成仕様変更.xlsx
        //控除データ
        $strSQL .= "        LEFT JOIN JKTAISYOKUKYUFU KOU" . "\r\n";
        //$strSQL .= "        LEFT JOIN  JKKOUJYO KOU" . "\r\n";
        $strSQL .= "            ON    KOU.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "            AND   SYA.SYAIN_NO = KOU.SYAIN_NO" . "\r\n";
        //$strSQL .= "            AND   KOU.KS_KB = '1'" . "\r\n";
        //20220118 lujunxia upd e
        //役員以外のみ
        if ($kbn == 0) {
            //社員マスタ(単価履歴)
            $strSQL .= "        LEFT JOIN (SELECT   TAN2.SYAIN_NO" . "\r\n";
            $strSQL .= "                           ,TAN2.RIVISION_DT" . "\r\n";
            $strSQL .= "                           ,TAN2.KYUUYO_SYSTEM_KB_CD" . "\r\n";
            $strSQL .= "                           ,TAN2.KIHONKYU" . "\r\n";
            $strSQL .= "                   FROM    JKTANKARIREKI TAN2" . "\r\n";
            $strSQL .= "                           INNER JOIN  (SELECT   TAN3.SYAIN_NO" . "\r\n";
            $strSQL .= "                                                 ,MAX(TAN3.RIVISION_DT) RIVISION_DT" . "\r\n";
            $strSQL .= "                                         FROM    JKTANKARIREKI TAN3" . "\r\n";
            //社員マスタ(異動履歴)
            $strSQL .= "                                                 LEFT JOIN  JKSONOTA SON3" . "\r\n";
            //その他データ
            $strSQL .= "                                                     ON    SON3.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
            $strSQL .= "                                                     AND   TAN3.SYAIN_NO = SON3.SYAIN_NO" . "\r\n";
            $strSQL .= "                                                     AND   SON3.KS_KB = '1'" . "\r\n";
            //20231220 caina upd s
            //$strSQL .= "                                         WHERE   TAN3.RIVISION_DT <= NVL(SON3.SONOTA3,'@KEISANKIKANEND')" . "\r\n";
            $strSQL .= "                                         WHERE   TAN3.RIVISION_DT <= NVL(SON3.SONOTA3,TO_DATE('@KEISANKIKANEND','YYYY-MM-DD'))" . "\r\n";
            //20231220 caina upd e
            $strSQL .= "                                         GROUP BY TAN3.SYAIN_NO" . "\r\n";
            $strSQL .= "                                        ) TAN4" . "\r\n";
            $strSQL .= "                               ON    TAN2.SYAIN_NO = TAN4.SYAIN_NO" . "\r\n";
            $strSQL .= "                               AND   TAN2.RIVISION_DT = TAN4.RIVISION_DT" . "\r\n";
            $strSQL .= "                  ) TAN" . "\r\n";
            $strSQL .= "            ON    SYA.SYAIN_NO = TAN.SYAIN_NO" . "\r\n";
        }
        //雇用履歴データ
        $strSQL .= "        LEFT JOIN  JKKOYOURIREKI KOY" . "\r\n";
        $strSQL .= "            ON    SYA.SYAIN_NO = KOY.SYAIN_NO" . "\r\n";
        //20231220 caina upd s
        //$strSQL .= "            AND   KOY.BEF_NYUSYA_DT <= NVL(SON.SONOTA3,'@KEISANKIKANEND')" . "\r\n";
        //$strSQL .= "            AND   NVL(KOY.BEF_TAISYOKU_DT, TO_DATE('99991231', 'YYYY/MM/DD')) >= NVL(SON.SONOTA3,'@KEISANKIKANEND')" . "\r\n";
        $strSQL .= "            AND   KOY.BEF_NYUSYA_DT <= NVL(SON.SONOTA3,TO_DATE('@KEISANKIKANEND','YYYY-MM-DD'))" . "\r\n";
        $strSQL .= "            AND   NVL(KOY.BEF_TAISYOKU_DT, TO_DATE('99991231', 'YYYY/MM/DD')) >= NVL(SON.SONOTA3,TO_DATE('@KEISANKIKANEND','YYYY-MM-DD'))" . "\r\n";
        //20231220 caina upd e

        //条件
        //役員以外
        if ($kbn == 0) {
            $strSQL .= "WHERE   (KOY.BEF_KOYOU_KB_CD NOT IN ('07','97')" . "\r\n";
            $strSQL .= "         AND     KOY.SYAIN_NO IS NOT NULL" . "\r\n";
            $strSQL .= "         OR      SYA.KOYOU_KB_CD NOT IN ('07','97')" . "\r\n";
            $strSQL .= "         AND     KOY.SYAIN_NO IS NULL)" . "\r\n";
            $strSQL .= " AND    (NVL(SYA.TAISYOKU_DT, TO_DATE('99991231', 'YYYY/MM/DD')) >= ADD_MONTHS(TO_DATE('" . $taishoYM . "01" . "', 'YYYY/MM/DD'), -1)" . "\r\n";
            $strSQL .= "         AND     SHI.TAISYOU_YM IS NULL" . "\r\n";
            $strSQL .= "         OR      SHI.TAISYOU_YM IS NOT NULL)" . "\r\n";
        }
        //役員
        else {
            $strSQL .= "WHERE   (KOY.BEF_KOYOU_KB_CD IN ('07','97')" . "\r\n";
            $strSQL .= "         AND     KOY.SYAIN_NO IS NOT NULL" . "\r\n";
            $strSQL .= "         OR      SYA.KOYOU_KB_CD IN ('07','97')" . "\r\n";
            $strSQL .= "         AND     KOY.SYAIN_NO IS NULL)" . "\r\n";
            $strSQL .= " AND     (NVL(SYA.TAISYOKU_DT, TO_DATE('99991231', 'YYYY/MM/DD')) >= ADD_MONTHS(TO_DATE('" . $taishoYM . "01" . "', 'YYYY/MM/DD'), -1)" . "\r\n";
            $strSQL .= "         AND     JIG.TAISYOU_YM IS NULL" . "\r\n";
            $strSQL .= "         OR      JIG.TAISYOU_YM IS NOT NULL)" . "\r\n";
        }
        //20231220 upd caina s
        // $strSQL .= "AND      SYA.NYUSYA_DT <= '@KEISANKIKANEND'" . "\r\n";
        $strSQL .= "AND      SYA.NYUSYA_DT <= TO_DATE('@KEISANKIKANEND','YYYY-MM-DD')" . "\r\n";
        //20231220 upd caina e
        //20231220 caina ins s
        $strSQL .= "AND   NOT EXISTS" . "\r\n";
        $strSQL .= "       (SELECT EX.SYAIN_NO " . "\r\n";
        $strSQL .= "        FROM   JKJINKENHI_EXCLUDE EX" . "\r\n";
        $strSQL .= "        WHERE  EX.SYAIN_NO = SYA.SYAIN_NO)" . "\r\n";
        //20231220 caina ins e
        $preMon = $taishoYM . "01";
        $preMon = date('Y/m/t', strtotime("$preMon -1 month"));

        $strSQL = str_replace("@KEISANKIKANEND", $preMon, $strSQL);

        return parent::insert($strSQL);
    }

    //人件費データ長期欠勤者更新SQL
    public function updJinkenhiKekkinSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.SYAIN_NO JSYAIN_NO" . "\r\n";
        $strSQL .= "           ,KEK.SYAIN_NO KSYAIN_NO" . "\r\n";
        $strSQL .= "           ,JIN.JININ_CNT" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKJINKENHIKEKKIN KEK" . "\r\n";
        $strSQL .= "                ON      JIN.TAISYOU_YM = KEK.TAISYOU_YM" . "\r\n";
        $strSQL .= "                AND     JIN.SYAIN_NO = KEK.SYAIN_NO" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= "SET     TAB.JININ_CNT = 0" . "\r\n";
        $strSQL .= "WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "AND     TAB.JSYAIN_NO = TAB.KSYAIN_NO" . "\r\n";

        return parent::update($strSQL);
    }

    //人件費データ部署変換更新SQL
    public function updJinkenhiBushoHenkanSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "           ,BUS.AFT_BUSYO_CD" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKBUSYOCNV BUS" . "\r\n";
        $strSQL .= "                ON      JIN.BUSYO_CD = BUS.BEF_BUSYO_CD" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= "SET     TAB.BUSYO_CD = TAB.AFT_BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::update($strSQL);
    }

    //人件費データ出向社員更新SQL
    public function updJinkenhiShukkoSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "UPDATE  JKJINKENHI JIN" . "\r\n";
        $strSQL .= "SET     JININ_CNT = 0" . "\r\n";
        $strSQL .= "WHERE   JIN.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "AND     JIN.BUSYO_CD = '115'" . "\r\n";

        return parent::update($strSQL);
    }

    //人件費データ出向社員更新SQL
    public function updJinkenhiBushoFurikaeSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.SYAIN_NO JSYAIN_NO" . "\r\n";
        $strSQL .= "           ,FRI.SYAIN_NO FSYAIN_NO" . "\r\n";
        $strSQL .= "           ,JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "           ,FRI.FRI_SAKI_BUSYO_CD" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKJINKENHITABUSYOFRI FRI" . "\r\n";
        $strSQL .= "                ON      JIN.TAISYOU_YM = FRI.TAISYOU_YM" . "\r\n";
        $strSQL .= "                AND     JIN.SYAIN_NO = FRI.SYAIN_NO" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= "SET     TAB.BUSYO_CD = TAB.FRI_SAKI_BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "AND     TAB.JSYAIN_NO = TAB.FSYAIN_NO" . "\r\n";

        return parent::update($strSQL);
    }

    //基本給合計取得SQL
    public function selSumKihonkyuSQL($taishoYM, $kbn)
    {
        $strSQL = "";
        $strSQL .= "SELECT NVL(SUM(JIN.KIHONKYU),0) SUMKHK" . "\r\n";
        $strSQL .= " FROM   JKJINKENHI JIN" . "\r\n";
        $strSQL .= "WHERE  JIN.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        //賞与見積、賞与時社会保険料用
        if ($kbn == 0) {
            //正社員、嘱託社員、再雇用
            $strSQL .= "AND    JIN.KOYOU_KB IN ('01','05','3A')" . "\r\n";
        }
        //雇用保険料用
        elseif ($kbn == 1) {
            //正社員、契約社員、嘱託社員、パート社員、再雇用
            $strSQL .= "AND    JIN.KOYOU_KB IN ('01', '03', '05', '08','3A')" . "\r\n";
        }
        //労災保険料用
        elseif ($kbn == 2) {
            //正社員、契約社員、嘱託社員、パート社員、再雇用
            $strSQL .= "AND    JIN.KOYOU_KB IN ('01', '03', '05', '08','3A')" . "\r\n";
            //労災は出向者には配分しない
            $strSQL .= "AND    JIN.BUSYO_CD <> '115'" . "\r\n";
        }
        //契約社員賞与見積、賞与時社会保険料用
        elseif ($kbn == 3) {
            //契約社員
            $strSQL .= "AND    JIN.KOYOU_KB = '03'" . "\r\n";
        }

        return parent::select($strSQL);
    }

    //賞与見積、賞与時社会保険料更新SQL
    public function updShoyoMitsumori_ShahoSQL($taishoYM, $sumKihonkyu)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.KOYOU_KB KOYOU_KB_CD" . "\r\n";
        $strSQL .= "           ,JIN.BNS_MITUMORI JMITUMORI" . "\r\n";
        $strSQL .= "           ,JIN.BNS_KENKO_HKN_RYO JKENKO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,JIN.BNS_KAIGO_HKN_RYO JKAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,JIN.BNS_KOUSEI_NENKIN JKOUSEI_NENKIN" . "\r\n";
        $strSQL .= "           ,JIN.BNS_JIDOU_TEATE JJIDOU_TEATE" . "\r\n";
        $strSQL .= "           ,NVL(FUR.BNS_MITUMORI * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FMITUMORI" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KENKO_HKN_RYO * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FKENKO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KAIGO_HKN_RYO * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FKAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KOUSEINENKIN * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FKOUSEI_NENKIN" . "\r\n";
        $strSQL .= "           ,NVL(FUR.JIDOUTEATE * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FJIDOU_TEATE" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKJINKENHIFURIKAEHIRITU FUR" . "\r\n";
        $strSQL .= "                ON      JIN.TAISYOU_YM = FUR.TAISYOU_YM" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= "SET     TAB.JMITUMORI = ROUND(TAB.FMITUMORI)" . "\r\n";
        $strSQL .= "       ,TAB.JKENKO_HKN_RYO = ROUND(TAB.FKENKO_HKN_RYO)" . "\r\n";
        $strSQL .= "       ,TAB.JKAIGO_HKN_RYO = ROUND(TAB.FKAIGO_HKN_RYO)" . "\r\n";
        $strSQL .= "       ,TAB.JKOUSEI_NENKIN = ROUND(TAB.FKOUSEI_NENKIN)" . "\r\n";
        $strSQL .= "       ,TAB.JJIDOU_TEATE = ROUND(TAB.FJIDOU_TEATE)" . "\r\n";
        $strSQL .= "WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        //正社員、嘱託社員、再雇用
        $strSQL .= "AND     TAB.KOYOU_KB_CD IN ('01','05','3A')" . "\r\n";

        return parent::update($strSQL);
    }

    //人件費振替比率データ契約社員分チェック
    public function selFurikaehiritsuChkSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT NVL(KYK_BNS_MITUMORI,0) + NVL(KYK_KENKO_HKN_RYO,0) + NVL(KYK_KAIGO_HKN_RYO,0) + NVL(KYK_KOUSEINENKIN,0) + NVL(KYK_JIDOUTEATE,0) KYK_BNS_CHK" . "\r\n";
        $strSQL .= " FROM   JKJINKENHIFURIKAEHIRITU" . "\r\n";
        $strSQL .= "WHERE  TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";

        return parent::select($strSQL);
    }

    //契約社員賞与見積、賞与時社会保険料更新SQL
    public function updKYKShoyoMitsumori_ShahoSQL($taishoYM, $sumKihonkyu)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.KOYOU_KB KOYOU_KB_CD" . "\r\n";
        $strSQL .= "           ,JIN.BNS_MITUMORI JMITUMORI" . "\r\n";
        $strSQL .= "           ,JIN.BNS_KENKO_HKN_RYO JKENKO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,JIN.BNS_KAIGO_HKN_RYO JKAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,JIN.BNS_KOUSEI_NENKIN JKOUSEI_NENKIN" . "\r\n";
        $strSQL .= "           ,JIN.BNS_JIDOU_TEATE JJIDOU_TEATE" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KYK_BNS_MITUMORI * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FMITUMORI" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KYK_KENKO_HKN_RYO * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FKENKO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KYK_KAIGO_HKN_RYO * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FKAIGO_HKN_RYO" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KYK_KOUSEINENKIN * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FKOUSEI_NENKIN" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KYK_JIDOUTEATE * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FJIDOU_TEATE" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKJINKENHIFURIKAEHIRITU FUR" . "\r\n";
        $strSQL .= "                ON      JIN.TAISYOU_YM = FUR.TAISYOU_YM" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= " SET     TAB.JMITUMORI = ROUND(TAB.FMITUMORI)" . "\r\n";
        $strSQL .= "       ,TAB.JKENKO_HKN_RYO = ROUND(TAB.FKENKO_HKN_RYO)" . "\r\n";
        $strSQL .= "       ,TAB.JKAIGO_HKN_RYO = ROUND(TAB.FKAIGO_HKN_RYO)" . "\r\n";
        $strSQL .= "       ,TAB.JKOUSEI_NENKIN = ROUND(TAB.FKOUSEI_NENKIN)" . "\r\n";
        $strSQL .= "       ,TAB.JJIDOU_TEATE = ROUND(TAB.FJIDOU_TEATE)" . "\r\n";
        $strSQL .= " WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        //契約社員
        $strSQL .= " AND     TAB.KOYOU_KB_CD = '03'" . "\r\n";

        return parent::update($strSQL);
    }

    //雇用保険料更新SQL
    public function updKoyoHokenSQL($taishoYM, $sumKihonkyu)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.KOYOU_KB KOYOU_KB_CD" . "\r\n";
        $strSQL .= "           ,JIN.KOYOU_HKN_RYO JKOYOU_HKN_RYO" . "\r\n";
        $strSQL .= "           ,NVL(FUR.KOYOU_HKN_RYO * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FKOYOU_HKN_RYO" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKJINKENHIFURIKAEHIRITU FUR" . "\r\n";
        $strSQL .= "                ON      JIN.TAISYOU_YM = FUR.TAISYOU_YM" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= "SET     TAB.JKOYOU_HKN_RYO = ROUND(TAB.FKOYOU_HKN_RYO)" . "\r\n";
        $strSQL .= "WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        //正社員、契約社員、嘱託社員、パート社員、再雇用
        $strSQL .= "AND     TAB.KOYOU_KB_CD IN ('01', '03', '05', '08','3A')" . "\r\n";

        return parent::update($strSQL);
    }

    //労災保険料更新SQL
    public function updRosaiHokenSQL($taishoYM, $sumKihonkyu)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.KOYOU_KB KOYOU_KB_CD" . "\r\n";
        $strSQL .= "           ,JIN.BUSYO_CD" . "\r\n";
        $strSQL .= "           ,JIN.ROUSAI_HKN_RYO JROUSAI_HKN_RYO" . "\r\n";
        $strSQL .= "           ,NVL(FUR.ROUSAI_HKN_RYO * (JIN.KIHONKYU / '" . $sumKihonkyu . "'), 0) FROUSAI_HKN_RYO" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKJINKENHIFURIKAEHIRITU FUR" . "\r\n";
        $strSQL .= "                ON      JIN.TAISYOU_YM = FUR.TAISYOU_YM" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= "SET     TAB.JROUSAI_HKN_RYO = ROUND(TAB.FROUSAI_HKN_RYO)" . "\r\n";
        $strSQL .= "WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        //正社員、契約社員、嘱託社員、パート社員、再雇用
        $strSQL .= "AND     TAB.KOYOU_KB_CD IN ('01', '03', '05', '08','3A')" . "\r\n";
        //労災は出向者には配分しない
        $strSQL .= "AND     TAB.BUSYO_CD <> '115'" . "\r\n";

        return parent::update($strSQL);
    }

    //退職給付の基本給合計取得
    public function selSumKihonkyuTaishokuSQL($taishoYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT SUM(JIN.KIHONKYU) KIHONKYU" . "\r\n";
        $strSQL .= "FROM   JKJINKENHI JIN" . "\r\n";
        $strSQL .= "WHERE  JIN.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "AND    JIN.TAISYOKU_KYUFU <> 0" . "\r\n";

        return parent::select($strSQL);
    }

    //退職手当更新SQL
    public function updTaishokuteateSQL($taishoYM, $sumKihonkyu)
    {
        $strSQL = "";
        $strSQL .= "UPDATE" . "\r\n";
        $strSQL .= "(   SELECT  JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "           ,JIN.TAISYOKU_KYUFU" . "\r\n";
        $strSQL .= "           ,JIN.TAISYOKU_KYUFU JTAISYOKU_KYUFU" . "\r\n";
        $strSQL .= "           ,JIN.TAISYOKU_KYUFU + FUR.TAISYOKUTEATE * (JIN.KIHONKYU / '" . $sumKihonkyu . "') FTAISYOKU_KYUFU" . "\r\n";
        $strSQL .= "    FROM    JKJINKENHI JIN" . "\r\n";
        $strSQL .= "            INNER JOIN  JKJINKENHIFURIKAEHIRITU FUR" . "\r\n";
        $strSQL .= "                ON      JIN.TAISYOU_YM = FUR.TAISYOU_YM" . "\r\n";
        $strSQL .= ") TAB" . "\r\n";
        $strSQL .= "SET     TAB.JTAISYOKU_KYUFU = ROUND(TAB.FTAISYOKU_KYUFU)" . "\r\n";
        $strSQL .= "WHERE   TAB.TAISYOU_YM = '" . $taishoYM . "'" . "\r\n";
        $strSQL .= "AND     TAB.TAISYOKU_KYUFU <> 0" . "\r\n";

        return parent::update($strSQL);
    }

    //20240307 caina ins s
    public function GetJKJINdataSQL($dtpYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT JIN.TAISYOU_YM" . "\r\n";
        $strSQL .= "   ,JIN.SYAIN_NO" . "\r\n";
        $strSQL .= "  ,	JIN.BUSYO_CD	" . "\r\n";
        $strSQL .= "  ,	JIN.KOYOU_KB	" . "\r\n";
        $strSQL .= "  ,	JIN.SYOKUSYU_CD	" . "\r\n";
        $strSQL .= "  ,	JIN.KIHONKYU	" . "\r\n";
        $strSQL .= "  ,	JIN.TEIJIKAN_GESSYU	" . "\r\n";
        $strSQL .= "  ,	JIN.ZANGYOU_TEATE	" . "\r\n";
        $strSQL .= "  ,	JIN.GYOUSEKI_SYOUREI	" . "\r\n";
        $strSQL .= "  ,	JIN.HOKA_GSK_SYOUREI	" . "\r\n";
        $strSQL .= "  ,	JIN.SONOTA_TEATE	" . "\r\n";
        $strSQL .= "  ,	JIN.KENKO_HKN_RYO	" . "\r\n";
        $strSQL .= "  ,	JIN.KAIGO_HKN_RYO	" . "\r\n";
        $strSQL .= "  ,	JIN.KOUSEINENKIN	" . "\r\n";
        $strSQL .= "  ,	JIN.KOYOU_HKN_RYO	" . "\r\n";
        $strSQL .= "  ,	JIN.ROUSAI_HKN_RYO	" . "\r\n";
        $strSQL .= "  ,	JIN.JIDOUTEATE	" . "\r\n";
        $strSQL .= "  ,	JIN.TAISYOKU_KYUFU	" . "\r\n";
        $strSQL .= "  ,	JIN.BNS_MITUMORI	" . "\r\n";
        $strSQL .= "  ,	JIN.BNS_KENKO_HKN_RYO	" . "\r\n";
        $strSQL .= "  ,	JIN.BNS_KAIGO_HKN_RYO	" . "\r\n";
        $strSQL .= "  ,	JIN.BNS_KOUSEI_NENKIN	" . "\r\n";
        $strSQL .= "  ,	JIN.BNS_JIDOU_TEATE	" . "\r\n";
        $strSQL .= "  ,	JIN.JININ_CNT	" . "\r\n";
        $strSQL .= "  ,	TO_CHAR(JIN.CREATE_DATE,'YYYY-MM-DD HH24:MI:SS') AS CREATE_DATE" . "\r\n";
        $strSQL .= "  ,	JIN.CRE_SYA_CD	" . "\r\n";
        $strSQL .= "  ,	JIN.CRE_PRG_ID	" . "\r\n";
        $strSQL .= "  ,	TO_CHAR(JIN.UPD_DATE,'YYYY-MM-DD HH24:MI:SS') AS UPD_DATE" . "\r\n";
        $strSQL .= "  ,	JIN.UPD_SYA_CD	" . "\r\n";
        $strSQL .= "  ,	JIN.UPD_PRG_ID	" . "\r\n";
        $strSQL .= "  ,	JIN.UPD_CLT_NM	" . "\r\n";
        $strSQL .= "FROM   JKJINKENHI JIN" . "\r\n";
        $strSQL .= "WHERE  JIN.TAISYOU_YM = '" . $dtpYM . "'" . "\r\n";

        return parent::select($strSQL);
    }

    function existData($rowdata)
    {
        $strSQL = "";
        $strSQL = "SELECT TAISYOU_YM " . "\r\n";
        $strSQL .= " ,SYAIN_NO " . "\r\n";
        $strSQL .= " FROM ";
        $strSQL .= "    JKJINKENHI";
        $strSQL .= " WHERE TAISYOU_YM =  '" . $rowdata['TAISYOU_YM'] . "'" . "\r\n";
        $strSQL .= " AND SYAIN_NO =  '" . $rowdata['SYAIN_NO'] . "'" . "\r\n";

        return parent::select($strSQL);
    }

    function updateData($rowdata)
    {
        $strSQL = "";
        $strSQL .= " UPDATE JKJINKENHI " . "\r\n";
        $strSQL .= " SET " . "\r\n";
        $strSQL .= " TAISYOU_YM =  '" . $rowdata['TAISYOU_YM'] . "'" . "\r\n";
        $strSQL .= " ,	SYAIN_NO = '" . $rowdata['SYAIN_NO'] . "'" . "\r\n";
        $strSQL .= " ,	BUSYO_CD = '" . $rowdata['BUSYO_CD'] . "'" . "\r\n";
        $strSQL .= " ,	KOYOU_KB = '" . $rowdata['KOYOU_KB'] . "'" . "\r\n";
        $strSQL .= " ,	SYOKUSYU_CD = '" . $rowdata['SYOKUSYU_CD'] . "'" . "\r\n";
        $strSQL .= " ,	KIHONKYU = '" . $rowdata['KIHONKYU'] . "'" . "\r\n";
        $strSQL .= " ,	TEIJIKAN_GESSYU = '" . $rowdata['TEIJIKAN_GESSYU'] . "'" . "\r\n";
        $strSQL .= " ,	ZANGYOU_TEATE = '" . $rowdata['ZANGYOU_TEATE'] . "'" . "\r\n";
        $strSQL .= " ,	GYOUSEKI_SYOUREI = '" . $rowdata['GYOUSEKI_SYOUREI'] . "'" . "\r\n";
        $strSQL .= " ,	HOKA_GSK_SYOUREI = '" . $rowdata['HOKA_GSK_SYOUREI'] . "'" . "\r\n";
        $strSQL .= " ,	SONOTA_TEATE = '" . $rowdata['SONOTA_TEATE'] . "'" . "\r\n";
        $strSQL .= " ,	KENKO_HKN_RYO = '" . $rowdata['KENKO_HKN_RYO'] . "'" . "\r\n";
        $strSQL .= " ,	KAIGO_HKN_RYO = '" . $rowdata['KAIGO_HKN_RYO'] . "'" . "\r\n";
        $strSQL .= " ,	KOUSEINENKIN = '" . $rowdata['KOUSEINENKIN'] . "'" . "\r\n";
        $strSQL .= " ,	KOYOU_HKN_RYO = '" . $rowdata['KOYOU_HKN_RYO'] . "'" . "\r\n";
        $strSQL .= " ,	ROUSAI_HKN_RYO = '" . $rowdata['ROUSAI_HKN_RYO'] . "'" . "\r\n";
        $strSQL .= " ,	JIDOUTEATE = '" . $rowdata['JIDOUTEATE'] . "'" . "\r\n";
        $strSQL .= " ,	TAISYOKU_KYUFU = '" . $rowdata['TAISYOKU_KYUFU'] . "'" . "\r\n";
        $strSQL .= " ,	BNS_MITUMORI = '" . $rowdata['BNS_MITUMORI'] . "'" . "\r\n";
        $strSQL .= " ,	BNS_KENKO_HKN_RYO = '" . $rowdata['BNS_KENKO_HKN_RYO'] . "'" . "\r\n";
        $strSQL .= " ,	BNS_KAIGO_HKN_RYO = '" . $rowdata['BNS_KAIGO_HKN_RYO'] . "'" . "\r\n";
        $strSQL .= " ,	BNS_KOUSEI_NENKIN = '" . $rowdata['BNS_KOUSEI_NENKIN'] . "'" . "\r\n";
        $strSQL .= " ,	BNS_JIDOU_TEATE = '" . $rowdata['BNS_JIDOU_TEATE'] . "'" . "\r\n";
        $strSQL .= " ,	JININ_CNT = '" . $rowdata['JININ_CNT'] . "'" . "\r\n";
        $strSQL .= " ,  CREATE_DATE = TO_DATE('@CREATE_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= " ,	CRE_SYA_CD = '" . $rowdata['CRE_SYA_CD'] . "'" . "\r\n";
        $strSQL .= " ,	CRE_PRG_ID = '" . $rowdata['CRE_PRG_ID'] . "'" . "\r\n";
        $strSQL .= " ,  UPD_DATE = TO_DATE('@UPD_DATE','YYYY-MM-DD HH24:MI:SS')" . "\r\n";
        $strSQL .= " ,	UPD_SYA_CD = '" . $rowdata['UPD_SYA_CD'] . "'" . "\r\n";
        $strSQL .= " ,	UPD_PRG_ID = '" . $rowdata['UPD_PRG_ID'] . "'" . "\r\n";
        $strSQL .= " ,	UPD_CLT_NM = '" . $rowdata['UPD_CLT_NM'] . "'" . "\r\n";
        $strSQL .= " WHERE TAISYOU_YM =  '" . $rowdata['TAISYOU_YM'] . "'" . "\r\n";
        $strSQL .= " AND SYAIN_NO =  '" . $rowdata['SYAIN_NO'] . "'" . "\r\n";

        $strSQL = str_replace("@CREATE_DATE", $rowdata['CREATE_DATE'], $strSQL);
        $strSQL = str_replace("@UPD_DATE", $rowdata['UPD_DATE'], $strSQL);

        return parent::update($strSQL);
    }
    //20240307 caina ins e
}
