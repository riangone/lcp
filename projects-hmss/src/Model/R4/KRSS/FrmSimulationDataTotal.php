<?php
/**
 * 説明：
 *
 *
 * @author fuxiaolin
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150729         KRSS_受入れ.57               KRSS_受入れ.57                        FANZHENGZHOU
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
use Cake\Log\Log;
class frmSimulationDataTotal extends ClsComDb
{
    public function fncSQLSql($iKBN = "", $postData = null)
    {
        $strSQL = "";

        switch ($iKBN) {
            case 1:
                $strSQL .= "  SELECT ID, " . "\r\n";
                $strSQL .= "     TO_CHAR(ADD_MONTHS(TO_DATE((SIMULATION_YM || '01'),'YYYY/MM/DD'),1),'YYYY/MM/DD') TOUGETU " . "\r\n";
                $strSQL .= "  FROM   HKEIRICTL" . "\r\n";
                $strSQL .= "  WHERE  ID = '01'";

                break;
            case 2:
                $strSQL .= " SELECT KEIJO_DT " . "\r\n";
                $strSQL .= " FROM   HKANRIZ  " . "\r\n";
                $strSQL .= " WHERE  KEIJO_DT = '@KEIJO_DT' " . "\r\n";

                $strSQL = str_replace("@KEIJO_DT", rtrim(str_replace("/", "", $postData['cboYM'])), $strSQL);
                break;
            case 3:
                //$strSQL .= " DELETE FROM HSIMTOTALDATA " . "\r\n";
                //$strSQL .= " DELETE FROM HSIMTOTALDATA_NEW " . "\r\n";
                $strSQL .= " DELETE FROM HSIMTOTALDATA_KRSS " . "\r\n";
                $strSQL .= " WHERE  KEIJO_DT = '@KEIJO_DT' " . "\r\n";
                $strSQL = str_replace("@KEIJO_DT", rtrim(str_replace("/", "", $postData['cboYM'])), $strSQL);
                break;

            case 4:
                //$strSQL .= "INSERT INTO HSIMTOTALDATA " . "\r\n";
                //$strSQL .= "INSERT INTO HSIMTOTALDATA_NEW " . "\r\n";
                $strSQL .= "INSERT INTO HSIMTOTALDATA_KRSS " . "\r\n";
                $strSQL .= " ( " . "\r\n";
                $strSQL .= "     KEIJO_DT " . "\r\n";
                $strSQL .= "     ,BUSYO_CD " . "\r\n";
                $strSQL .= "     ,SIM_LINE_NO " . "\r\n";
                $strSQL .= "     ,TOU_ZAN " . "\r\n";
                $strSQL .= "     ,UPD_DATE " . "\r\n";
                $strSQL .= "     ,CREATE_DATE " . "\r\n";
                $strSQL .= "     ,UPD_SYA_CD " . "\r\n";
                $strSQL .= "     ,UPD_PRG_ID " . "\r\n";
                $strSQL .= "     ,UPD_CLT_NM " . "\r\n";
                $strSQL .= " ) " . "\r\n";
                $strSQL .= " SELECT " . "\r\n";
                $strSQL .= "      WK.KEIJO_DT " . "\r\n";
                $strSQL .= "     ,WK.BUSYO_CD " . "\r\n";
                $strSQL .= "     ,LINE.SIM_LINE_NO " . "\r\n";
                $strSQL .= "     ,SUM(NVL(WK.TOU_ZAN, 0) * NVL(LINE.CAL_KB, 1)) " . "\r\n";
                $strSQL .= "     ,SYSDATE " . "\r\n";
                $strSQL .= "     ,SYSDATE " . "\r\n";
                $strSQL .= "     ,WK.UPD_SYA_CD " . "\r\n";
                $strSQL .= "     ,WK.UPD_PRG_ID " . "\r\n";
                $strSQL .= "     ,WK.UPD_CLT_NM " . "\r\n";
                //$strSQL .= " FROM WK_HSIMKANR WK, HSIMLINEMST LINE" . "\r\n";
                $strSQL .= " FROM WK_HSIMKANR WK, HSIMLINEMST_KEIEISEIKA LINE" . "\r\n";
                $strSQL .= "WHERE WK.LINE_NO = LINE.LINE_NO " . "\r\n";
                $strSQL .= "GROUP BY " . "\r\n";
                $strSQL .= "     WK.KEIJO_DT " . "\r\n";
                $strSQL .= "     ,WK.BUSYO_CD " . "\r\n";
                $strSQL .= "     ,LINE.SIM_LINE_NO " . "\r\n";
                $strSQL .= "     ,WK.UPD_SYA_CD " . "\r\n";
                $strSQL .= "     ,WK.UPD_PRG_ID " . "\r\n";
                $strSQL .= "     ,WK.UPD_CLT_NM " . "\r\n";
                break;
            case 5:
                //---20150729 KRSS_受入れ.57  fanzhengzhou add s.
                $clsComFnc = new ClsComFnc();
                $UPDUSER = $_SESSION['login_user'];
                $UPDAPP = 'SimulationDataTotal';
                $UPDCLTNM = $_SERVER['REMOTE_ADDR'];
                //---20150729 KRSS_受入れ.57  fanzhengzhou add s.
                $strSQL .= "UPDATE HKEIRICTL SET" . "\r\n";
                $strSQL .= "SIMULATION_YM = '@SIMYM'" . "\r\n";
                //---20150729 KRSS_受入れ.57  fanzhengzhou add s.
                $strSQL .= ",        SYR_YMD = TO_CHAR(ADD_MONTHS(TO_DATE(SYR_YMD||'01','YYYYMMDD'),1),'YYYYMM')" . "\r\n";
                $strSQL .= ",       KISYU_YMD = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KISYU_YMD,'YYYYMMDD'),12),'YYYYMMDD') ELSE KISYU_YMD END)" . "\r\n";
                $strSQL .= ",       KIMATU_YMD = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN TO_CHAR(ADD_MONTHS(TO_DATE(KIMATU_YMD,'YYYYMMDD'),12),'YYYYMMDD') ELSE KIMATU_YMD END)" . "\r\n";
                $strSQL .= ",       KI = (CASE WHEN SUBSTR(SYR_YMD,5,2) = '09' THEN KI + 1 ELSE KI END)" . "\r\n";
                $strSQL .= ",       UPD_SYA_CD = " . $clsComFnc->FncSqlNv($UPDUSER);
                $strSQL .= ",       UPD_PRG_ID = " . $clsComFnc->FncSqlNv($UPDAPP);
                $strSQL .= ",       UPD_CLT_NM = " . $clsComFnc->FncSqlNv($UPDCLTNM);
                $strSQL .= ",      UPD_DATE = SYSDATE ";
                //---20150729 KRSS_受入れ.57  fanzhengzhou add e.
                $strSQL .= "WHERE ID = '01'" . "\r\n";
                $strSQL .= "AND   NVL(SIMULATION_YM,'000000') < '@SIMYM'" . "\r\n";
                $strSQL = str_replace("@SIMYM", rtrim(str_replace("/", "", $postData['cboYM'])), $strSQL);
                Log::error($strSQL);
                break;

            case 6:
                //$strSQL .= "DELETE FROM HSIMRUISEKIKANR" . "\r\n";
                //$strSQL .= "DELETE FROM HSIMRUISEKIKANR_NEW " . "\r\n";
                $strSQL .= "DELETE FROM HSIMRUISEKIKANR_KRSS " . "\r\n";
                $strSQL .= "WHERE  KEIJO_DT = '@KEIJO_DT'" . "\r\n";

                $strSQL = str_replace("@KEIJO_DT", rtrim(str_replace("/", "", $postData['cboYM'])), $strSQL);

                break;
            case 7:
                //$strSQL .= "INSERT INTO HSIMRUISEKIKANR" . "\r\n";
                //$strSQL .= "INSERT INTO HSIMRUISEKIKANR_NEW " . "\r\n";
                $strSQL .= "INSERT INTO HSIMRUISEKIKANR_KRSS " . "\r\n";
                $strSQL .= "SELECT * FROM WK_HSIMKANR" . "\r\n";
                $strSQL .= "WHERE  KEIJO_DT = '@KEIJO_DT'" . "\r\n";

                $strSQL = str_replace("@KEIJO_DT", rtrim(str_replace("/", "", $postData['cboYM'])), $strSQL);
                break;
            default:
                break;
        }

        return $strSQL;
    }

    function fncDeleteWkKanrSQL()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_HSIMKANR";
        return $strSQL;
    }

    /**
     * 処 理 名：集計・部署別集計を行う
     * 関 数 名：fncSyukeiToBusyo
     * 引    数：dtlSyoriYM  (I)処理年月
     * 　    　：dtlTougtuYM (I)当月年月
     * 戻 り 値：SQL
     * 処理説明：集計・部署別集計を行い、部署別集計ﾜｰｸに格納
     */
    function fncSyukeiToBusyoSQL($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strSQL = "";

        $strSQL .= "INSERT INTO WK_HSIMKANR" . "\r\n";
        $strSQL .= "(   KEIJO_DT" . "\r\n";
        $strSQL .= ",   BUSYO_CD " . "\r\n";
        $strSQL .= ",   LINE_NO  " . "\r\n";
        $strSQL .= ",   TOU_ZAN  " . "\r\n";
        $strSQL .= ",    UPD_SYA_CD" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "    SELECT  '@TOU_GETU'" . "\r\n";
        $strSQL .= "    ,       V.BUSYO_CD" . "\r\n";
        $strSQL .= "    ,       V.LINE_NO" . "\r\n";
        $strSQL .= "    ,       SUM(V.TOUGETU)" . "\r\n";
        $strSQL .= ",        '@UPDUSER'" . "\r\n";
        $strSQL .= ",        '@UPDAPP'" . "\r\n";
        $strSQL .= ",        '@UPDCLT'" . "\r\n";
        $strSQL .= "    FROM    (" . "\r\n";
        $strSQL .= "            --当月集計" . "\r\n";
        $strSQL .= "            SELECT  DECODE(BUS.CNV_BUSYO_CD,NULL,TOU.BUSYO_CD,BUS.CNV_BUSYO_CD) BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "            ,      SUM(NVL(TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "            FROM   HKANRIZ TOU" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        $strSQL .= "                   HBUSYO BUS" . "\r\n";
        $strSQL .= "            ON     BUS.BUSYO_CD = TOU.BUSYO_CD" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        //$strSQL .= "                   HKMKLINEMST KLINE" . "\r\n";
        $strSQL .= "                   HKMKLINEMST_KEIEISEIKA KLINE" . "\r\n";
        $strSQL .= "            ON     KLINE.KAMOK_CD = TOU.KAMOKU_CD" . "\r\n";
        $strSQL .= "            AND    (KLINE.HIMOK_CD = NVL(TRIM(TOU.HIMOKU_CD),'00')" . "\r\n";
        $strSQL .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(TOU.HIMOKU_CD,1,1),TOU.HIMOKU_CD,1,1)))" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        //$strSQL .= "                   HLINEMST LINE" . "\r\n";
        $strSQL .= "                   HLINEMST_KEIEISEIKA LINE" . "\r\n";
        $strSQL .= "            ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "            WHERE  TOU.KEIJO_DT = '@TOU_GETU'" . "\r\n";
        $strSQL .= "            GROUP BY  DECODE(BUS.CNV_BUSYO_CD,NULL,TOU.BUSYO_CD,BUS.CNV_BUSYO_CD), LINE.LINE_NO" . "\r\n";
        $strSQL .= "            " . "\r\n";
        $strSQL .= "            --当月部署別集計" . "\r\n";
        $strSQL .= "            UNION ALL" . "\r\n";
        $strSQL .= "    " . "\r\n";
        $strSQL .= "            SELECT SBUS.TOTAL_BUSYO_CD" . "\r\n";
        $strSQL .= "            ,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "            ,      SUM(NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "            FROM   HKANRIZ B_TOU" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        $strSQL .= "                   HBUSYO BUS" . "\r\n";
        $strSQL .= "            ON     BUS.BUSYO_CD = B_TOU.BUSYO_CD" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        $strSQL .= "                   HTTLBUSYO SBUS" . "\r\n";
        $strSQL .= "            ON     SBUS.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        //$strSQL .= "                   HKMKLINEMST KLINE" . "\r\n";
        $strSQL .= "                   HKMKLINEMST_KEIEISEIKA KLINE" . "\r\n";
        $strSQL .= "            ON     KLINE.KAMOK_CD = B_TOU.KAMOKU_CD" . "\r\n";
        $strSQL .= "            AND    (KLINE.HIMOK_CD = NVL(TRIM(B_TOU.HIMOKU_CD),'00')" . "\r\n";
        $strSQL .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(B_TOU.HIMOKU_CD,1,1),B_TOU.HIMOKU_CD,1,1)))" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        //$strSQL .= "                   HLINEMST LINE" . "\r\n";
        $strSQL .= "                   HLINEMST_KEIEISEIKA LINE" . "\r\n";
        $strSQL .= "            ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "            WHERE  B_TOU.KEIJO_DT = '@TOU_GETU'" . "\r\n";
        $strSQL .= "            GROUP BY SBUS.TOTAL_BUSYO_CD, LINE.LINE_NO" . "\r\n";
        $strSQL .= "            " . "\r\n";
        $strSQL .= "            --トータル集計(当月)" . "\r\n";
        $strSQL .= "            UNION ALL" . "\r\n";
        $strSQL .= "    " . "\r\n";
        $strSQL .= "            SELECT '000'" . "\r\n";
        $strSQL .= "            ,      LINE.LINE_NO" . "\r\n";
        $strSQL .= "            ,      SUM(NVL(B_TOU.TOU_ZAN,0) * NVL(KLINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "            FROM   HKANRIZ B_TOU" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        $strSQL .= "                   HBUSYO BUS" . "\r\n";
        $strSQL .= "            ON     BUS.BUSYO_CD = B_TOU.BUSYO_CD" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        //$strSQL .= "                   HKMKLINEMST KLINE" . "\r\n";
        $strSQL .= "                   HKMKLINEMST_KEIEISEIKA KLINE" . "\r\n";
        $strSQL .= "            ON     KLINE.KAMOK_CD = B_TOU.KAMOKU_CD" . "\r\n";
        $strSQL .= "            AND    (KLINE.HIMOK_CD = NVL(TRIM(B_TOU.HIMOKU_CD),'00')" . "\r\n";
        $strSQL .= "             OR (DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(KLINE.HIMOK_CD,1,1),KLINE.HIMOK_CD) = DECODE(SUBSTR(KLINE.HIMOK_CD,2,1),'0',SUBSTR(B_TOU.HIMOKU_CD,1,1),B_TOU.HIMOKU_CD,1,1)))" . "\r\n";
        $strSQL .= "            INNER JOIN" . "\r\n";
        //$strSQL .= "                   HLINEMST LINE" . "\r\n";
        $strSQL .= "                   HLINEMST_KEIEISEIKA LINE" . "\r\n";
        $strSQL .= "            ON     LINE.LINE_NO = KLINE.LINE_NO" . "\r\n";
        $strSQL .= "            WHERE  B_TOU.KEIJO_DT = '@TOU_GETU'" . "\r\n";
        $strSQL .= "            GROUP BY LINE.LINE_NO" . "\r\n";
        $strSQL .= "            " . "\r\n";
        $strSQL .= "    ) V" . "\r\n";
        $strSQL .= "    " . "\r\n";
        $strSQL .= "    GROUP BY V.BUSYO_CD, V.LINE_NO" . "\r\n";

        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        $strSQL = str_replace("@TOU_GETU", $dtlSyoriYM, $strSQL);

        return $strSQL;
    }

    /**
     * 処 理 名：ライン集計を行う
     * 関 数 名：fncSyukeiLine
     * 引    数：無し
     * 戻 り 値：SQL
     * 処理説明：ライン集計を行う
     */
    function fncSyukeiLineSQL($strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO WK_HSIMKANR" . "\r\n";
        $strSQL .= "(   KEIJO_DT" . "\r\n";
        $strSQL .= ",   BUSYO_CD " . "\r\n";
        $strSQL .= ",   LINE_NO  " . "\r\n";
        $strSQL .= ",   TOU_ZAN  " . "\r\n";
        $strSQL .= ",    UPD_SYA_CD" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT  SYUKEI.KEIJO_DT" . "\r\n";
        $strSQL .= ",       SYUKEI.BUSYO_CD" . "\r\n";
        $strSQL .= ",       SYUKEI.TOTAL_LINE_NO" . "\r\n";
        $strSQL .= ",       SYUKEI.TOUGETU" . "\r\n";
        $strSQL .= ",       '@UPDUSER'" . "\r\n";
        $strSQL .= ",       '@UPDAPP'" . "\r\n";
        $strSQL .= ",       '@UPDCLT'" . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "        SELECT W_KR.KEIJO_DT" . "\r\n";
        $strSQL .= "        ,      W_KR.BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      S_LINE.TOTAL_LINE_NO" . "\r\n";
        $strSQL .= "        ,      SUM(W_KR.TOU_ZAN * NVL(S_LINE.CAL_KB,1)) TOUGETU" . "\r\n";
        $strSQL .= "" . "\r\n";
        $strSQL .= "        FROM   WK_HSIMKANR W_KR" . "\r\n";
        $strSQL .= "        INNER JOIN" . "\r\n";
        //$strSQL .= "               HTTLLINEMST S_LINE" . "\r\n";
        $strSQL .= "               HTTLLINEMST_KEIEISEIKA S_LINE" . "\r\n";
        $strSQL .= "        ON     S_LINE.LINE_NO = W_KR.LINE_NO" . "\r\n";
        $strSQL .= "        GROUP BY W_KR.KEIJO_DT, W_KR.BUSYO_CD, S_LINE.TOTAL_LINE_NO) SYUKEI" . "\r\n";

        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        return $strSQL;
    }

    /**
     * 処 理 名：経営成果対象でないデータを部署別集計ﾜｰｸから削除する
     * 関 数 名：fncDeleteKanr
     * 引    数：strBusyoFrom  (I)画面：開始部署ｺｰﾄﾞ
     * 　    　：strBusyoTo    (I)画面：終了部署ｺｰﾄﾞ
     * 戻 り 値：SQL
     * 処理説明：経営成果対象でないデータを部署別集計ﾜｰｸから削除する(（GD）（DZM）データ連携サブシステムで使用)
     */
    function fncDeleteKanrSQL()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM WK_HSIMKANR KR" . "\r\n";
        $strSQL .= "WHERE   NOT EXISTS" . "\r\n";
        $strSQL .= "       (SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "        FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "        WHERE  KR.BUSYO_CD = BUS.BUSYO_CD" . "\r\n";
        $strSQL .= "        AND    BUS.PRN_KB5 = 'O'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        return $strSQL;
    }

    public function fncDeleteKanr()
    {
        $strsql = $this->fncDeleteKanrSQL();
        return parent::Do_Execute($strsql);
    }

    public function fncSQL($iKBN = "", $postData = null)
    {
        $str_sql = $this->fncSQLSql($iKBN, $postData);
        Log::error($str_sql);

        if ($iKBN == 1) {
            return parent::select($str_sql);
        } else {
            return parent::Do_Execute($str_sql);
        }

    }



    /**
     * 処 理 名：集計・部署別集計を行う
     * 関 数 名：fncSyukeiToBusyo
     * 引    数：dtlSyoriYM  (I)処理年月
     * 　    　：dtlTougtuYM (I)当月年月
     * 戻 り 値：SQL
     * 処理説明：集計・部署別集計を行い、部署別集計ﾜｰｸに格納
     */
    function fncSyukeiToBusyoSQL_NEW($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strSQL = "";
        $strSQL .= "INSERT INTO WK_HSIMKANR" . "\r\n";
        $strSQL .= "(   KEIJO_DT" . "\r\n";
        $strSQL .= ",   BUSYO_CD " . "\r\n";
        $strSQL .= ",   LINE_NO  " . "\r\n";
        $strSQL .= ",   TOU_ZAN  " . "\r\n";
        $strSQL .= ",    UPD_SYA_CD" . "\r\n";
        $strSQL .= ",    UPD_PRG_ID" . "\r\n";
        $strSQL .= ",    UPD_CLT_NM" . "\r\n";
        $strSQL .= ")" . "\r\n";
        $strSQL .= "SELECT  SYUKEI.KEIJO_DT" . "\r\n";
        $strSQL .= ",       SYUKEI.BUSYO_CD" . "\r\n";
        $strSQL .= ",       SYUKEI.TOTAL_LINE_NO" . "\r\n";
        $strSQL .= ",       SYUKEI.TOUGETU" . "\r\n";
        $strSQL .= ",       '@UPDUSER'" . "\r\n";
        $strSQL .= ",       '@UPDAPP'" . "\r\n";
        $strSQL .= ",       '@UPDCLT'" . "\r\n";
        $strSQL .= "FROM    (" . "\r\n";
        $strSQL .= "        SELECT W_KR.NENGETU KEIJO_DT" . "\r\n";
        $strSQL .= "        ,      W_KR.BUSYO_CD" . "\r\n";
        $strSQL .= "        ,      W_KR.LINE_NO TOTAL_LINE_NO" . "\r\n";
        $strSQL .= "        ,      W_KR.JISSEKI TOUGETU" . "\r\n";
        $strSQL .= "" . "\r\n";
        //        $strSQL .= "        FROM   VW_KEIEISEIKA_TUKI W_KR ) SYUKEI" . "\r\n";
        $strSQL .= "        FROM   VW_KEIEISEIKA_TUKI W_KR " . "\r\n";
        $strSQL .= "        WHERE W_KR.NENGETU ='@SYORIYM' " . "\r\n";
        $strSQL .= "        ) SYUKEI" . "\r\n";
        $strSQL = str_replace("@SYORIYM", $dtlSyoriYM, $strSQL);

        $strSQL = str_replace("@UPDAPP", $strUpdPro, $strSQL);
        $strSQL = str_replace("@UPDCLT", $strUpdClt, $strSQL);
        $strSQL = str_replace("@UPDUSER", $strUpdUser, $strSQL);
        return $strSQL;

    }



    public function fncChkHKANRIZ($iKBN = "", $postData = null)
    {
        $str_sql = $this->fncSQLSql($iKBN, $postData);
        return parent::select($str_sql);
    }

    public function fncDeleteWkKanr()
    {
        $strsql = $this->fncDeleteWkKanrSQL();
        return parent::Do_Execute($strsql);
    }

    public function fncSyukeiToBusyo($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro)
    {
        //        $strsql = $this -> fncSyukeiToBusyoSQL($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro);
        $strsql = $this->fncSyukeiToBusyoSQL_NEW($dtlSyoriYM, $strUpdUser, $strUpdClt, $strUpdPro);
        Log::error($strsql);
        return parent::Do_Execute($strsql);
    }

    public function fncSyukeiLine($strUpdUser, $strUpdClt, $strUpdPro)
    {
        $strsql = $this->fncSyukeiLineSQL($strUpdUser, $strUpdClt, $strUpdPro);
        return parent::Do_Execute($strsql);
    }

}
