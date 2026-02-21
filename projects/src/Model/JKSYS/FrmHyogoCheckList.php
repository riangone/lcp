<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmHyogoCheckList extends ClsComDb
{
    // * 処理名	：fncGetJHTRDATSQL
    // * 関数名	：fncGetJHTRDATSQL
    // * 処理説明	：評価取込履歴データ(評価実施期間)
    public function FncGetJHTRDATSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT SUBSTR(MAX(JISSHI_YM),1,4) || '/' || SUBSTR(MAX(JISSHI_YM),5,6) JISSHI_YM" . "\r\n";
        $strSQL .= " FROM JKHYOUKATRKRIREKI JHTR " . "\r\n";

        return $strSQL;
    }

    // * 処理名	：fncGetJHTRDAT2SQL
    // * 関数名	：fncGetJHTRDAT2SQL
    // * 処理説明	：評価取込履歴データ(評価対象期間)
    public function FncGetJHTRDAT2SQL($strJisshi)
    {
        $strSQL = "";
        $strSQL .= "  SELECT to_char(HYOUKA_KIKAN_START, 'YYYY/MM/DD') || ' ～ ' || to_char(HYOUKA_KIKAN_END, 'YYYY/MM/DD') AS KIKAN " . " \r\n";
        $strSQL .= "       , to_char(HYOUKA_KIKAN_START, 'YYYY/MM/DD') AS HYOUKA_KIKAN_START " . " \r\n";
        $strSQL .= "       , to_char(HYOUKA_KIKAN_END, 'YYYY/MM/DD') AS HYOUKA_KIKAN_END " . " \r\n";
        $strSQL .= "       ,JISSHI_YM" . " \r\n";
        $strSQL .= " FROM   JKHYOUKATRKRIREKI JHTR " . " \r\n";
        if ($strJisshi == "") {
            $strSQL .= " WHERE JHTR.JISSHI_YM = (SELECT MAX(JISSHI_YM) FROM JKHYOUKATRKRIREKI) " . " \r\n";
        } else {
            $strSQL .= " WHERE JHTR.JISSHI_YM = SUBSTR('@JISSHI_YM',1,4) || SUBSTR('@JISSHI_YM',6,7) " . " \r\n";
        }

        $strSQL = str_replace("@JISSHI_YM", $strJisshi, $strSQL);

        return $strSQL;
    }

    // * 処理名	：fncGetJHTRDAT3SQL
    // * 関数名	：fncGetJHTRDAT3SQL
    // * 処理説明	：評価取込履歴データ(前回分と前々回分の評価実施月)
    public function FncGetJHTRDAT3SQL($strJisshi, $strSyokoukyuMonth)
    {
        $strSQL = "";
        $strSQL = " SELECT JISSHI_YM" . "\r\n";
        $strSQL .= " FROM JKHYOUKATRKRIREKI JHTR " . "\r\n";
        $strSQL .= " WHERE JHTR.JISSHI_YM < SUBSTR('@JISSHI_YM',1,4) || SUBSTR('@JISSHI_YM',6,7) " . "\r\n";
        $strSQL .= " AND" . "\r\n";
        $strSQL .= " SUBSTR(JHTR.JISSHI_YM,5,2) <> '" . $strSyokoukyuMonth . "'" . "\r\n";
        $strSQL .= " ORDER BY JISSHI_YM DESC" . "\r\n";

        //条件を設定
        $strSQL = str_replace("@JISSHI_YM", $strJisshi, $strSQL);

        return $strSQL;
    }

    // * 処理名	：fncGetJHRDATSQL
    // * 関数名	：fncGetJHRDATSQL
    // * 処理説明	：評価履歴データ
    public function FncGetJHRDATSQL($strJisshi)
    {
        $strSQL = "";
        $strSQL .= " SELECT *" . "\r\n";
        $strSQL .= " FROM JKHYOUKARIREKI JHR " . "\r\n";
        $strSQL .= " WHERE JHR.JISSHI_YM = SUBSTR('@JISSHI_YM',1,4) || SUBSTR('@JISSHI_YM',6,7) " . "\r\n";

        //条件を設定
        $strSQL = str_replace("@JISSHI_YM", $strJisshi, $strSQL);

        return $strSQL;
    }

    // * 処理名	：fncGetBonusSQL
    // * 関数名	：fncGetBonusSQL
    // * 処理説明	：人事コントロールマスタ(ボーナス月のチェック)
    public function FncGetBonusSQL()
    {
        $strSQL = "";
        $strSQL = " SELECT " . "\r\n";
        $strSQL .= " JKC.KAKI_BONUS_MONTH" . "\r\n";
        $strSQL .= ",JKC.TOUKI_BONUS_MONTH" . "\r\n";
        $strSQL .= " FROM JKCONTROLMST JKC " . "\r\n";
        $strSQL .= " WHERE JKC.ID = '01' " . "\r\n";

        return $strSQL;
    }

    // * 処理名	：FncGetTeikiSyokyuMonthSQL
    // * 関数名	：FncGetTeikiSyokyuMonthSQL
    // * 処理説明	：評価実施年月設定
    public function FncGetTeikiSyokyuMonthSQL()
    {
        $strSQL = "";
        $strSQL = " SELECT  KAKI_BONUS_MONTH" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " JKCONTROLMST" . "\r\n";
        $strSQL .= " WHERE  ID = '02'" . "\r\n";

        return $strSQL;
    }

    // * 処理名	：SetHyoukaComboxSQL
    // * 関数名	：SetHyoukaComboxSQL
    // * 処理説明	：評価実施年月設定
    public function SetHyoukaComboxSQL($_strSyokoukyuMonth)
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= " SUBSTR(JISSHI_YM,1,4) || '/' || SUBSTR(JISSHI_YM,5,6) JISSHI_YM,JISSHI_YM AS JISSHI_YM_ORIGINAL" . "\r\n";
        $strSQL .= " FROM" . "\r\n";
        $strSQL .= " JKHYOUKATRKRIREKI" . "\r\n";
        $strSQL .= " WHERE" . "\r\n";
        $strSQL .= " SUBSTR(JISSHI_YM,5,2) <> '" . $_strSyokoukyuMonth . "'" . "\r\n";
        $strSQL .= " ORDER BY JISSHI_YM DESC" . "\r\n";

        return $strSQL;
    }

    // * 処理名	：fncGetHCRDATSQL
    // * 関数名	：fncGetHCRDATSQL
    // * 処理説明	：評価チェックリストデータ
    public function FncGetHCRDATSQL($strZenKai, $strZenZenKai, $strJisshi, $strFrom, $strTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT NVL(SKS.SYOKUSYU_TTL_KB,'999') AS 職種集計区分" . "\r\n";
        //職種集計区分
        $strSQL .= "      ,NVL(SKS.SYOKUSYU_TTL_KB_NM,'職種集計区分名不明') AS 職種集計区分名" . "\r\n";
        $strSQL .= "      ,SKK.SHIKAKU_CD AS 資格コード" . "\r\n";
        //資格等級コード
        $strSQL .= "      ,SKKNM.MEISYOU AS 資格名" . "\r\n";
        //名称 資格名
        $strSQL .= "      ,HYOKA.LAST_HANTEI AS 点数" . "\r\n";
        //最終評価－判定値
        $strSQL .= "      ,HYOKA.SYAIN_NO" . "\r\n";
        //社員番号
        $strSQL .= "      ,SYA.SYAIN_NM AS 氏名" . "\r\n";
        //氏名
        $strSQL .= "      ,SKS.BUSYO_CD" . "\r\n";
        //所属コード
        $strSQL .= "      ,BMN.BUSYO_NM AS 部門名" . "\r\n";
        //部門名
        $strSQL .= "      ,ZENZENKAI.LAST_HYOUKA AS 前々回最終評価" . "\r\n";
        //最終評価－評価
        $strSQL .= "      ,ZENKAI.LAST_HYOUKA AS 前回最終評価" . "\r\n";
        //最終評価－評価
        $strSQL .= "      ,HYOKA.LAST_HYOUKA AS 今回評価" . "\r\n";
        //最終評価－評価
        //評価履歴データ
        $strSQL .= "FROM JKHYOUKARIREKI HYOKA" . "\r\n";
        //社員マスタ
        $strSQL .= "LEFT JOIN JKSYAIN SYA" . "\r\n";
        $strSQL .= "ON SYA.SYAIN_NO = HYOKA.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "(" . "\r\n";
        $strSQL .= "	    SELECT MSKS.SYAIN_NO,MSKS.SYOKUSYU_CD,BUS.BUSYO_CD,TTL.SYOKUSYU_TTL_KB,TTLNM.SYOKUSYU_TTL_KB_NM,TTLNM.ORDER_NO" . "\r\n";
        $strSQL .= "	    FROM (" . "\r\n";
        $strSQL .= "			    SELECT WK.SYAIN_NO" . "\r\n";
        $strSQL .= "			          ,ROW_NUMBER() OVER(ORDER BY WK.SYAIN_NO, SUM(MONTHS_BETWEEN(WK.ET, WK.ST)) desc, MAX(WK.ET)) - RANK() OVER(ORDER BY WK.SYAIN_NO) RNK" . "\r\n";
        $strSQL .= "			          ,SUM(MONTHS_BETWEEN(WK.ET, WK.ST))" . "\r\n";
        $strSQL .= "			          ,WK.SYOKUSYU_CD" . "\r\n";
        $strSQL .= "			          ,MAX(WK.ET) SKSENDDAY	" . "\r\n";
        $strSQL .= "			    FROM (" . "\r\n";
        $strSQL .= "					    SELECT V.SYAIN_NO" . "\r\n";
        $strSQL .= "					          ,(CASE WHEN V.STARTDT < TO_DATE('@FROM_YM','YYYY/MM/DD') THEN TO_DATE('@FROM_YM','YYYY/MM/DD') ELSE V.STARTDT END) ST" . "\r\n";
        $strSQL .= "					          ,(CASE WHEN NVL(V.ENDDT,'9999/12/31') > TO_DATE('@TO_YM','YYYY/MM/DD') THEN TO_DATE('@TO_YM','YYYY/MM/DD') ELSE V.ENDDT END) ET" . "\r\n";
        $strSQL .= "					          ,V.SYOKUSYU_CD" . "\r\n";
        $strSQL .= "		 			    FROM (" . "\r\n";
        $strSQL .= "							    SELECT HAI.SYAIN_NO, HAI.SYOKUSYU_CD, TO_DATE(TO_CHAR(HAI.ANNOUNCE_DT,'YYYY/MM/DD')) STARTDT, LEAD((TO_DATE(TO_CHAR(HAI.ANNOUNCE_DT,'YYYY/MM/DD'))-1)) OVER(PARTITION BY HAI.SYAIN_NO ORDER BY HAI.SYAIN_NO, HAI.ANNOUNCE_DT) ENDDT" . "\r\n";
        //異動履歴データ
        $strSQL .= "							    FROM   JKIDOURIREKI HAI" . "\r\n";
        $strSQL .= "						     ) V" . "\r\n";
        $strSQL .= "					    WHERE NVL(V.ENDDT,'9999/12/31') >= TO_DATE('@FROM_YM','YYYY/MM/DD')" . "\r\n";
        $strSQL .= "					    AND   V.STARTDT <= TO_DATE('@TO_YM','YYYY/MM/DD')" . "\r\n";
        $strSQL .= "		             ) WK" . "\r\n";
        $strSQL .= "             GROUP BY WK.SYAIN_NO" . "\r\n";
        $strSQL .= "	                 ,WK.SYOKUSYU_CD" . "\r\n";
        $strSQL .= "	         ) MSKS" . "\r\n";
        $strSQL .= "	    INNER JOIN (" . "\r\n";
        $strSQL .= "				SELECT V.SYAIN_NO" . "\r\n";
        $strSQL .= "					  ,(CASE WHEN V.STARTDT < TO_DATE('@FROM_YM','YYYY/MM/DD') THEN TO_DATE('@FROM_YM','YYYY/MM/DD') ELSE V.STARTDT END) ST" . "\r\n";
        $strSQL .= "					  ,(CASE WHEN NVL(V.ENDDT,'9999/12/31') > TO_DATE('@TO_YM','YYYY/MM/DD') THEN TO_DATE('@TO_YM','YYYY/MM/DD') ELSE V.ENDDT END) ET" . "\r\n";
        $strSQL .= "					  ,V.BUSYO_CD" . "\r\n";
        $strSQL .= "		 		FROM (" . "\r\n";
        $strSQL .= "						SELECT HAI.SYAIN_NO, HAI.BUSYO_CD, TO_DATE(TO_CHAR(HAI.ANNOUNCE_DT,'YYYY/MM/DD')) STARTDT, LEAD((TO_DATE(TO_CHAR(HAI.ANNOUNCE_DT,'YYYY/MM/DD'))-1)) OVER(PARTITION BY HAI.SYAIN_NO ORDER BY HAI.SYAIN_NO, HAI.ANNOUNCE_DT) ENDDT" . "\r\n";
        //異動履歴データ
        $strSQL .= "						FROM   JKIDOURIREKI HAI" . "\r\n";
        $strSQL .= "				     ) V" . "\r\n";
        $strSQL .= "				WHERE NVL(V.ENDDT,'9999/12/31') >= TO_DATE('@FROM_YM','YYYY/MM/DD')" . "\r\n";
        $strSQL .= "				AND   V.STARTDT <= TO_DATE('@TO_YM','YYYY/MM/DD')" . "\r\n";
        $strSQL .= "	    ) BUS" . "\r\n";
        $strSQL .= "	    ON BUS.SYAIN_NO = MSKS.SYAIN_NO" . "\r\n";
        $strSQL .= "	    AND BUS.ET = MSKS.SKSENDDAY" . "\r\n";
        //評語職種集計マスタ
        $strSQL .= "	    INNER JOIN JKHYOUGOSKSTTLMST TTL" . "\r\n";
        $strSQL .= "	    ON    TTL.SYOKUSYU_CD = MSKS.SYOKUSYU_CD" . "\r\n";
        //評語職種集計区分マスタ
        $strSQL .= "	    INNER JOIN  JKHYOUGOSKSTTLKBNMST TTLNM" . "\r\n";
        $strSQL .= "	    ON    TTLNM.SYOKUSYU_TTL_KB = TTL.SYOKUSYU_TTL_KB" . "\r\n";
        $strSQL .= "	    WHERE MSKS.RNK = 0" . "\r\n";
        $strSQL .= "     ) SKS" . "\r\n";
        $strSQL .= "ON SKS.SYAIN_NO = HYOKA.SYAIN_NO" . "\r\n";

        //部門マスタ
        $strSQL .= "LEFT JOIN JKBUMON BMN" . "\r\n";
        $strSQL .= "ON   BMN.BUSYO_CD = SKS.BUSYO_CD" . "\r\n";

        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "(" . "\r\n";
        $strSQL .= "	    SELECT MSKK.SYAIN_NO, MSKK.SHIKAKU_CD" . "\r\n";
        $strSQL .= "	    FROM (" . "\r\n";
        $strSQL .= "			    SELECT WK.SYAIN_NO" . "\r\n";
        $strSQL .= "			          ,ROW_NUMBER() OVER(ORDER BY WK.SYAIN_NO, SUM(MONTHS_BETWEEN(WK.ET, WK.ST)) desc, MAX(WK.ET)) - RANK() OVER(ORDER BY WK.SYAIN_NO) RNK" . "\r\n";
        $strSQL .= "			          ,SUM(MONTHS_BETWEEN(WK.ET, WK.ST))" . "\r\n";
        $strSQL .= "			          ,WK.SHIKAKU_CD" . "\r\n";
        $strSQL .= "			    FROM (" . "\r\n";
        $strSQL .= "					    SELECT V.SYAIN_NO" . "\r\n";
        $strSQL .= "					          ,(CASE WHEN V.STARTDT < TO_DATE('@FROM_YM','YYYY/MM/DD') THEN TO_DATE('@FROM_YM','YYYY/MM/DD') ELSE V.STARTDT END) ST" . "\r\n";
        $strSQL .= "					          ,(CASE WHEN NVL(V.ENDDT,'9999/12/31') > TO_DATE('@TO_YM','YYYY/MM/DD') THEN TO_DATE('@TO_YM','YYYY/MM/DD') ELSE V.ENDDT END) ET" . "\r\n";

        $strSQL .= "					          ,V.SHIKAKU_CD" . "\r\n";
        $strSQL .= "		 			    FROM (" . "\r\n";
        $strSQL .= "							    SELECT HAI.SYAIN_NO, HAI.SHIKAKU_CD, TO_DATE(TO_CHAR(HAI.ANNOUNCE_DT,'YYYY/MM/DD')) STARTDT, LEAD((TO_DATE(TO_CHAR(HAI.ANNOUNCE_DT,'YYYY/MM/DD'))-1)) OVER(PARTITION BY HAI.SYAIN_NO ORDER BY HAI.SYAIN_NO, HAI.ANNOUNCE_DT) ENDDT" . "\r\n";
        //異動履歴データ
        $strSQL .= "							    FROM   JKIDOURIREKI HAI" . "\r\n";
        $strSQL .= "						      ) V" . "\r\n";
        $strSQL .= "					    WHERE NVL(V.ENDDT,'9999/12/31') >= TO_DATE('@FROM_YM','YYYY/MM/DD')" . "\r\n";
        $strSQL .= "					    AND   V.STARTDT <= TO_DATE('@TO_YM','YYYY/MM/DD')" . "\r\n";
        $strSQL .= "		             ) WK" . "\r\n";
        $strSQL .= "     GROUP BY WK.SYAIN_NO" . "\r\n";
        $strSQL .= "	         ,WK.SHIKAKU_CD" . "\r\n";
        $strSQL .= "	    ) MSKK" . "\r\n";
        $strSQL .= "	    WHERE MSKK.RNK = 0" . "\r\n";
        $strSQL .= ") SKK" . "\r\n";
        $strSQL .= "ON SKK.SYAIN_NO = HYOKA.SYAIN_NO" . "\r\n";

        //コードマスタ
        $strSQL .= "LEFT JOIN JKCODEMST SKKNM" . "\r\n";
        $strSQL .= "ON   SKKNM.CODE = SKK.SHIKAKU_CD" . "\r\n";
        $strSQL .= "AND  SKKNM.ID = 'SHIKAKU'" . "\r\n";

        //評価履歴データ(前回)
        $strSQL .= "LEFT JOIN JKHYOUKARIREKI ZENKAI" . "\r\n";
        $strSQL .= "ON   ZENKAI.SYAIN_NO = HYOKA.SYAIN_NO" . "\r\n";
        $strSQL .= "AND  ZENKAI.JISSHI_YM = '@ZENKAI'" . "\r\n";

        //評価履歴データ(前々回)
        $strSQL .= "LEFT JOIN JKHYOUKARIREKI ZENZENKAI" . "\r\n";
        $strSQL .= "ON   ZENZENKAI.SYAIN_NO = HYOKA.SYAIN_NO" . "\r\n";
        $strSQL .= "AND  ZENZENKAI.JISSHI_YM = '@ZENZENKAI'" . "\r\n";

        $strSQL .= "WHERE HYOKA.JISSHI_YM = SUBSTR('@JISSHI_YM',1,4) || SUBSTR('@JISSHI_YM',6,7) " . "\r\n";
        $strSQL .= "ORDER BY SKS.ORDER_NO" . "\r\n";
        $strSQL .= "        ,NVL(SKS.SYOKUSYU_TTL_KB,'999')" . "\r\n";
        $strSQL .= "        ,NVL(SKK.SHIKAKU_CD,'000') DESC" . "\r\n";
        $strSQL .= "        ,HYOKA.LAST_HANTEI desc" . "\r\n";
        $strSQL .= "        ,HYOKA.SYAIN_NO" . "\r\n";

        //条件を設定
        //画面．実施年月
        $strSQL = str_replace("@JISSHI_YM", $strJisshi, $strSQL);
        //画面．評価対象期間開始
        $strSQL = str_replace("@FROM_YM", $strFrom, $strSQL);
        //画面．評価対象期間終了
        $strSQL = str_replace("@TO_YM", $strTo, $strSQL);
        //前回データ
        $strSQL = str_replace("@ZENKAI", $strZenKai, $strSQL);
        //前々回データ
        $strSQL = str_replace("@ZENZENKAI", $strZenZenKai, $strSQL);

        return $strSQL;
    }

    public function FncGetHCRDAT($strZenKai, $strZenZenKai, $strJisshi, $strFrom, $strTo)
    {
        $strSql = $this->FncGetHCRDATSQL($strZenKai, $strZenZenKai, $strJisshi, $strFrom, $strTo);
        return parent::select($strSql);
    }

    public function FncGetTeikiSyokyuMonth()
    {
        $strSql = $this->FncGetTeikiSyokyuMonthSQL();
        return parent::select($strSql);
    }

    public function SetHyoukaCombox($_strSyokoukyuMonth)
    {
        $strSql = $this->SetHyoukaComboxSQL($_strSyokoukyuMonth);
        return parent::select($strSql);
    }

    public function FncGetBonus()
    {
        $strSql = $this->FncGetBonusSQL();
        return parent::select($strSql);
    }

    public function FncGetJHTRDAT3($strJisshi, $strSyokoukyuMonth)
    {
        $strSql = $this->FncGetJHTRDAT3SQL($strJisshi, $strSyokoukyuMonth);
        return parent::select($strSql);
    }

    public function FncGetJHRDAT($strJisshi)
    {
        $strSql = $this->FncGetJHRDATSQL($strJisshi);
        return parent::select($strSql);
    }

    public function FncGetJHTRDAT2($strJisshi)
    {
        $strSql = $this->FncGetJHTRDAT2SQL($strJisshi);
        return parent::select($strSql);
    }

    public function FncGetJHTRDAT()
    {
        $strSql = $this->FncGetJHTRDATSQL();
        return parent::select($strSql);
    }

}
