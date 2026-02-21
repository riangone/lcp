<?php
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151008           20150929以降の修正差異点                                         li　　　　　
 * 20151208           #2227                                                         li
 * 20180205           #2807                        BUG                              YIN
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
use Cake\Log\Log;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmJinjiIn extends ClsComDb
{
    public $ClsComFnc;
    //---yushuangji add start---
    //---execute---
    public function fncTableDelete($strTableName)
    {
        $strSql = $this->fncTableDelete_sql($strTableName);
        return parent::Do_Execute($strSql);
    }

    public function Fnc_ExecuteScalar($sqlstr)
    {
        //return parent::FncExecuteScalar($sqlstr);
        return parent::Do_Execute($sqlstr);

    }

    //科目コード変換更新（（TMrh）ｺｰﾄﾞ-->Rｺｰﾄﾞ）
    public function fncUPDWK_CNVDATA()
    {
        $strSql = $this->fncUPDWK_CNVDATA_sql();
        return parent::Do_Execute($strSql);
    }

    //初期化指定の場合　対象ﾃｰﾌﾞﾙ初期化
    public function fncDELHFURIKAE()
    {
        $strSql = $this->fncDELHFURIKAE_sql();
        return parent::Do_Execute($strSql);
    }

    //初期化指定の場合　対象ﾃｰﾌﾞﾙ初期化
    public function fncSELECTWK_CNVDATA()
    {
        $strSql = $this->fncSELECTWK_CNVDATA_sql();
        return parent::Fill($strSql);
    }

    /*
           '**********************************************************************
           '処 理 名：人事関連データ作成(SQL)
           '関 数 名：fncYosanInsert
           '引    数：strCMNNO:注文書№
           '戻 り 値：SQL文
           '処理説明：ワークより人事関連データを作成する(SQL)
           */
    //--- 20151208 LI UPD S
    // public function fncJinjiInsert($value)
    public function fncJinjiInsert($value, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
//      $tmp = ((int) substr($value['WK002'], 0, 4)) * 100 + 19880001;
        $tmp = substr($value['WK002'], 0, 6);
        $tmpM = substr($tmp, 4, 2);
        $tmpY = substr($tmp, 0, 4);
        // $d = cal_days_in_month(CAL_GREGORIAN, $tmpM, $tmpY);
        $d = date("t", strtotime(substr($tmp, 0, 4) . '-' . substr($tmp, 4, 2)));
        $lastDay_date = $tmpY . $tmpM . $d;
        //--- 20151208 LI UPD S
        // $strSql = $this -> fncJinjiInsert_sql($lastDay_date, $value);
        $strSql = $this->fncJinjiInsert_sql($lastDay_date, $value, $ClsComFnc);
        //--- 20151208 LI UPD E
        return parent::Do_Execute($strSql);
    }

    //初期化指定の場合　営業所人員ﾃｰﾌﾞﾙ初期化
    public function fncDELETEHEIJININ()
    {
        $strSql = $this->fncDELETEHEIJININ_sql();
        return parent::Do_Execute($strSql);
    }

    public function fncEijinInsert()
    {
        $strSql = $this->fncEijinInsert_sql();
        return parent::Do_Execute($strSql);
    }

    //---sql---
    //
    public function fncEijinInsert_sql()
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HEIJININ\n";
        $sqlstr .= "           (\n";
        $sqlstr .= "            ID\n";
        $sqlstr .= "           ,YMD\n";
        $sqlstr .= "           ,BUSYO_CD\n";
        $sqlstr .= "           ,JININ_GK\n";
        $sqlstr .= "           ,JININ1\n";
        $sqlstr .= "           ,JININ2\n";
        $sqlstr .= "           ,JININ3\n";
        $sqlstr .= "           ,JININ4\n";
        $sqlstr .= "           ,JININ5\n";
        $sqlstr .= "           ,JININ6\n";
        $sqlstr .= "           ,JININ7\n";
        $sqlstr .= "           ,JININ8\n";
        $sqlstr .= "           ,JININ9\n";
        $sqlstr .= "           ,SONOTA1\n";
        $sqlstr .= "           ,SONOTA2\n";
        $sqlstr .= "           ,SONOTA3\n";
        $sqlstr .= "           ,UPD_DATE\n";
        $sqlstr .= "           ,CREATE_DATE\n";
        $sqlstr .= "           ,UPD_SYA_CD\n";
        $sqlstr .= "           ,UPD_PRG_ID\n";
        $sqlstr .= "           ,UPD_CLT_NM\n";
        $sqlstr .= "            )\n";
        $sqlstr .= "SELECT 'JH'\n";
        //20180205 YIN UPD S
        // $sqlstr .= "      ,MIN(TO_CHAR(LAST_DAY(TO_DATE(TO_NUMBER(SUBSTR(WK002, 1, 4)) * 100 + 19880001)),'YYYYMMDD'))\n";
//        $sqlstr .= "      ,MIN(TO_CHAR(LAST_DAY(TO_DATE(TO_NUMBER(SUBSTR(WK002, 1, 4)) * 100 + 19880001,'YYYY/MM/DD')),'YYYYMMDD'))\n";
        $sqlstr .= "      ,MIN(TO_CHAR(LAST_DAY(TO_DATE(SUBSTR(WK002, 0, 4) ||'/'|| SUBSTR(WK002, 5, 2) ||'/01')),'YYYYMMDD'))\n";

        //20180205 YIN UPD E
        $sqlstr .= "      ,SUBSTR(WK004,1,2)\n";
        $sqlstr .= "      ,SUM(NVL(WK008,0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'1',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'2',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'3',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'4',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'5',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'6',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'7',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'8',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,SUM(DECODE(SUBSTR(WK004,3,1),'9',NVL(WK008,0),0))\n";
        $sqlstr .= "      ,0\n";
        $sqlstr .= "      ,0\n";
        $sqlstr .= "      ,0\n";
        $sqlstr .= "      ,SYSDATE\n";
        $sqlstr .= "      ,SYSDATE\n";
        $sqlstr .= "      ,'@UPDUSER'\n";
        $sqlstr .= "      ,'@UPDAPP'\n";
        $sqlstr .= "      ,'@UPDCLT'\n";
        $sqlstr .= "  FROM WK_CNVDATA A\n";
        $sqlstr .= " WHERE TO_CHAR(TRIM(A.WK006),'FM00000') = '00800'\n";
        $sqlstr .= "   AND (SUBSTR(A.WK004,1,2) = '18'\n";

        //---20180206 Add Start
        $sqlstr .= "    OR  SUBSTR(A.WK004,1,2) = '24'\n";
        //---20180206 Add End

        //---20151008 li UPD S.
        //$sqlstr .= "    OR (SUBSTR(A.WK004,1,2) >= '29' AND SUBSTR(A.WK004,1,2) < '69'))\n";
        $sqlstr .= "    OR (SUBSTR(A.WK004,1,2) >= '27' AND SUBSTR(A.WK004,1,2) < '69'))\n";
        //---20151008 li UPD E.
        $sqlstr .= " GROUP BY SUBSTR(WK004,1,2)\n";
        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDAPP", 'frmJinjiIn', $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $sqlstr);

        return $sqlstr;
    }

    //初期化指定の場合　営業所人員ﾃｰﾌﾞﾙ初期化
    public function fncDELETEHEIJININ_sql()
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HEIJININ\n";
        $sqlstr .= " WHERE EXISTS\n";
        $sqlstr .= "         (SELECT WK_CNVDATA.WK002\n";
        $sqlstr .= "  FROM WK_CNVDATA\n";
        //20180205 YIN UPD S
        // $sqlstr .= "WHERE TO_CHAR(LAST_DAY(TO_DATE(TO_NUMBER(SUBSTR(WK002, 1, 4)) * 100 + 19880001)),'YYYYMMDD') = HEIJININ.YMD)\n";
//        $sqlstr .= "WHERE TO_CHAR(LAST_DAY(TO_DATE(TO_NUMBER(SUBSTR(WK002, 1, 4)) * 100 + 19880001,'YYYY/MM/DD')),'YYYYMMDD') = HEIJININ.YMD)\n";
        $sqlstr .= "WHERE TO_CHAR(LAST_DAY(TO_DATE(SUBSTR(WK002, 0, 4) ||'/'|| SUBSTR(WK002, 5, 2) ||'/01')),'YYYYMMDD') = HEIJININ.YMD)\n";
        //20180205 YIN UPD E
        return $sqlstr;
    }

    /*
           '**********************************************************************
           '処 理 名：人事関連データ作成(SQL)
           '関 数 名：fncYosanInsert
           '引    数：strCMNNO:注文書№
           '戻 り 値：SQL文
           '処理説明：ワークより人事関連データを作成する(SQL)
           */
    //--- 20151208 LI UPD S
    // public function fncJinjiInsert_sql($lastDay_date, $value)
    public function fncJinjiInsert_sql($lastDay_date, $value, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
        //--- 20151208 LI UPD S
        // $this -> ClsComFnc = new ClsComFnc();
        $this->ClsComFnc = $ClsComFnc;
        //--- 20151208 LI UPD E
        $sqlstr = "";
        $sqlstr .= "INSERT INTO HFURIKAE\n";
        $sqlstr .= "           (\n";
        $sqlstr .= "            KEIJO_DT\n";
        $sqlstr .= "           ,ID\n";
        $sqlstr .= "           ,DENPY_NO\n";
        $sqlstr .= "           ,GYO_NO\n";
        $sqlstr .= "           ,TAISK_KB\n";
        $sqlstr .= "           ,BUSYO_CD\n";
        $sqlstr .= "           ,KAMOK_CD\n";
        $sqlstr .= "           ,HIMOK_CD\n";
        $sqlstr .= "           ,KEIJO_GK\n";
        $sqlstr .= "           ,AITE_BUSYO_CD\n";
        $sqlstr .= "           ,AITE_KAMOK_CD\n";
        $sqlstr .= "           ,AITE_HIMOK_CD\n";
        $sqlstr .= "           ,HASEI_MOTO_KB \n";
        $sqlstr .= "           ,CEL_DATE\n";
        $sqlstr .= "           ,UPD_DATE\n";
        $sqlstr .= "           ,CREATE_DATE \n";
        $sqlstr .= "           ,UPD_SYA_CD\n";
        $sqlstr .= "           ,UPD_PRG_ID\n";
        $sqlstr .= "           ,UPD_CLT_NM\n";

        $sqlstr .= "           )\n";
        $sqlstr .= " VALUES(" . $this->ClsComFnc->FncSqlNv($lastDay_date) . "\n";
        $sqlstr .= "," . $this->ClsComFnc->FncSqlNv($value['WK001']) . "\n";
        $ttp = $this->ClsComFnc->FncSqlNv($value['WK007']);
        $ttp = substr($ttp, 1, count((array) $this->ClsComFnc->FncSqlNv($value['WK007'])) - 2);
        $ttp = str_pad($ttp, 12, ' ', STR_PAD_RIGHT);
        $sqlstr .= ",'" . $ttp . "'\n";
        $sqlstr .= ",(SELECT NVL(MAX(GYO_NO),0)+1 FROM HFURIKAE WHERE KEIJO_DT = '@KEIJOBI' AND DENPY_NO = '@DENPNO')\n";
        $sqlstr .= "," . $this->ClsComFnc->FncSqlNv(rtrim($value['WK003'])) . "\n";
        $sqlstr .= ",'" . str_pad($this->ClsComFnc->FncNz($value['WK004']), 3, '0', STR_PAD_LEFT) . "'\n";
        $sqlstr .= ",'" . str_pad($this->ClsComFnc->FncNz($value['WK006']), 5, '0', STR_PAD_LEFT) . "'\n";
        if (trim($this->ClsComFnc->FncNv($value['WK004'])) == '115' && trim($this->ClsComFnc->FncNv($value['WK006'])) == '43220') {
            $sqlstr .= ", ''\n";
        } else {
            $sqlstr .= ", '" . trim($this->ClsComFnc->FncNv($value['WK010'])) . "'\n";
        }
        $ttp = $this->ClsComFnc->FncSqlNv($value['WK008']);
        $ttp = substr($ttp, 1, count((array) $this->ClsComFnc->FncSqlNv($value['WK008'])) - 2);

        //$sqlstr .= ", " . trim($this -> ClsComFnc -> FncSqlNz($value['WK008'])) . "\n";
        $sqlstr .= ", " . $ttp . "\n";

        $sqlstr .= ",''\n";
        $sqlstr .= ",''\n";
        $sqlstr .= ",''\n";
        $sqlstr .= ",'JH'\n";
        $sqlstr .= ",NULL\n";
        $sqlstr .= ",SYSDATE\n";
        $sqlstr .= ",SYSDATE\n";
        $sqlstr .= ",'@UPDUSER'\n";
        $sqlstr .= ",'@UPDAPP'\n";
        $sqlstr .= ",'@UPDCLT'\n";
        $sqlstr .= "		)\n";
        $sqlstr = str_replace("@KEIJOBI", $lastDay_date, $sqlstr);
        $sqlstr = str_replace("@DENPNO", str_pad($value['WK007'] ?? '', 12, ' ', STR_PAD_RIGHT), $sqlstr);
        $sqlstr = str_replace("@UPDUSER", $this->GS_LOGINUSER['strUserID'], $sqlstr);
        $sqlstr = str_replace("@UPDAPP", 'frmJinjiIn', $sqlstr);
        $sqlstr = str_replace("@UPDCLT", $this->GS_LOGINUSER['strClientNM'], $sqlstr);

        return $sqlstr;

    }

    //初期化指定の場合　対象ﾃｰﾌﾞﾙ初期化
    public function fncSELECTWK_CNVDATA_sql()
    {
        $sqlstr = "";
        $sqlstr .= "select * from WK_CNVDATA";
        return $sqlstr;
    }

    //初期化指定の場合　対象ﾃｰﾌﾞﾙ初期化
    public function fncDELHFURIKAE_sql()
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HFURIKAE\n";
        $sqlstr .= " WHERE EXISTS\n";
        $sqlstr .= "         (SELECT WK_CNVDATA.WK008\n";
        $sqlstr .= "  FROM WK_CNVDATA\n";
        //20180205 YIN UPD S
        // $sqlstr .= "WHERE TO_CHAR(LAST_DAY(TO_DATE(TO_NUMBER(SUBSTR(WK002, 1, 4)) * 100 + 19880001)),'YYYYMMDD') = HFURIKAE.KEIJO_DT\n";
//        $sqlstr .= "WHERE TO_CHAR(LAST_DAY(TO_DATE(TO_NUMBER(SUBSTR(WK002, 1, 4)) * 100 + 19880001,'YYYY/MM/DD')),'YYYYMMDD') = HFURIKAE.KEIJO_DT\n";
        $sqlstr .= "WHERE TO_CHAR(LAST_DAY(TO_DATE(SUBSTR(WK002, 0, 4) ||'/'|| SUBSTR(WK002, 5, 2) ||'/01')),'YYYYMMDD') = HFURIKAE.KEIJO_DT\n";
        //20180205 YIN UPD E
        $sqlstr .= "         AND HFURIKAE.HASEI_MOTO_KB =  'JH')\n";
        return $sqlstr;
    }

    //科目コード変換更新（（TMrh）ｺｰﾄﾞ-->Rｺｰﾄﾞ）
    public function fncUPDWK_CNVDATA_sql()
    {
        $sqlstr = "";
        $sqlstr .= "UPDATE WK_CNVDATA\n";
        $sqlstr .= "   SET WK006 = (SELECT MIN(WK_CNVKAMOK.KAMOK_CD)\n";
        $sqlstr .= "                  FROM WK_CNVKAMOK\n";
        $sqlstr .= "                 WHERE WK_CNVDATA.WK006 = WK_CNVKAMOK.GDMZ_CD)\n";
        $sqlstr .= " WHERE EXISTS\n";
        $sqlstr .= "       (SELECT WK_CNVKAMOK.KAMOK_CD\n";
        $sqlstr .= "          FROM WK_CNVKAMOK\n";
        $sqlstr .= "         WHERE WK_CNVDATA.WK006 = WK_CNVKAMOK.GDMZ_CD )\n";
        return $sqlstr;
    }

    public function fncTableDelete_sql($strTableName)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM @TABLE_NAME";
        $sqlstr = str_replace("@TABLE_NAME", $strTableName, $sqlstr);
        return $sqlstr;
    }

    /*
           '********************************************************************
           '処理概要：INSER文を返す
           '引　　数：なし
           '戻 り 値：String            INSERT文
           '********************************************************************
           */
    public function fncGetSqlInsert_sql($strTableName, $lngItemNum)
    {
        $sqlstr = "";
        $sqlstr .= "INSERT INTO @TABLE_NAME (";
        for ($i = 1; $i <= $lngItemNum - 1; $i++) {
            if ($i > 1) {
                $sqlstr .= " ,";
            }
            $sqlstr .= " WK" . str_pad($i, 3, '0', STR_PAD_LEFT);
        }
        $sqlstr .= ")";
        //ﾃｰﾌﾞﾙ名を設定
        $sqlstr = str_replace("@TABLE_NAME", $strTableName, $sqlstr);
        return $sqlstr;
    }

    //---yushuangji add end---
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";

    function frmSampleLoadDateSql()
    {

        $strSQL = "SELECT ID" . "\r\n";

        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";

        $strSQL .= "   FROM  HKEIRICTL" . "\r\n";

        $strSQL .= "  WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    function fncSelHksaibanSql()
    {

        $strSQL = "";

        $strSQL .= "SELECT SEQNO" . "\r\n";

        $strSQL .= "FROM   HKSAIBAN" . "\r\n";

        $strSQL .= " WHERE ID = 'KEIRI'" . "\r\n";

        return $strSQL;

    }

    function fncUpdHksaibanSql($intSu)
    {

        $strSQL = "";

        $strSQL .= "UPDATE HKSAIBAN" . "\r\n";

        $strSQL .= "   SET SEQNO = (SELECT SEQNO + @SU FROM HKSAIBAN WHERE ID = 'KEIRI')" . "\r\n";

        $strSQL .= " WHERE ID = 'KEIRI'" . "\r\n";

        $strSQL = str_replace("@SU", $intSu, $strSQL);

        return $strSQL;

    }

    function fncZandakaDeleteSql($postData = NULL)
    {

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime(substr($ym, 0, 4) . '-' . substr($ym, 4, 2)));

        $ymd = $y . $m . $d;

        $strSQL = "";

        $strSQL .= "DELETE FROM HKNRZAN" . "\r\n";

        $strSQL .= "WHERE KEIJO_DT= '@KEIJOBI' " . "\r\n";

        $strSQL .= "   AND KAMOK_CD  IN ('00901','00902')" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", $ymd, $strSQL);

        return $strSQL;

    }

    function fncFurikaeDeleteSql($postData = NULL)
    {

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime(substr($ym, 0, 4) . '-' . substr($ym, 4, 2)));
        $ymd = $y . $m . $d;

        $strSQL = "";

        $strSQL .= "DELETE FROM HFURIKAE" . "\r\n";

        $strSQL .= "WHERE KEIJO_DT= '@KEIJOBI' " . "\r\n";

        $strSQL .= "AND    HASEI_MOTO_KB = 'HY' " . "\r\n";

        $strSQL = str_replace("@KEIJOBI", $ymd, $strSQL);

        return $strSQL;

    }

    function fncGetZandakaInsertSql($value, $postData = NULL)
    {
        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime(substr($ym, 0, 4) . '-' . substr($ym, 4, 2)));
        $ymd = $y . $m . $d;
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "HoyuDaisuIn";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HKNRZAN (";
        $strSQL .= "  KEIJO_DT";
        $strSQL .= ", DATA_KB";
        $strSQL .= ", TAISK_KB";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", KAMOK_CD";
        $strSQL .= ", ZEN_GK";
        $strSQL .= ", TOU_GK";
        $strSQL .= ", TAISYOU_GK";
        $strSQL .= ", KINRI_GK";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ", UPD_SYA_CD" . "\r\n";
        $strSQL .= ", UPD_PRG_ID" . "\r\n";
        $strSQL .= ", UPD_CLT_NM" . "\r\n";

        $strSQL .= ") VALUES (";
        $strSQL .= "  @KEIJO_DT";
        $strSQL .= ", ' '";
        $strSQL .= ", '1'";
        $strSQL .= ", @BUSYO_CD";
        $strSQL .= ", @KAMOK_CD";
        $strSQL .= ", @ZEN_GK";
        $strSQL .= ", @TOU_GK";
        $strSQL .= ", 0";
        $strSQL .= ", 0";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", '@UPDUSER'" . "\r\n";
        $strSQL .= ", '@UPDAPP'" . "\r\n";
        $strSQL .= ", '@UPDCLT'" . "\r\n";

        $strSQL .= ")";

        $strSQL = str_replace("@KEIJO_DT", $ymd, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($value[1]), $strSQL);
        $strSQL = str_replace("@ZEN_GK", $this->ClsComFnc->FncSqlNz($value[2]), $strSQL);
        $strSQL = str_replace("@TOU_GK", $this->ClsComFnc->FncSqlNz($value[3]), $strSQL);
        $strSQL = str_replace("@KAMOK_CD", $this->ClsComFnc->FncSqlNv($value[5]), $strSQL);

        return $strSQL;

    }

    function fncGetFurikaeInsertSql($value, $lngSeqNO, $intGyoNO, $postData = NULL)
    {

        $ym = str_replace("/", "", $postData['KEIJOBI']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        // $m1 = (int) $m;

        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime(substr($ym, 0, 4) . '-' . substr($ym, 4, 2)));
        $ymd = $y . $m . $d;
        $this->ClsComFnc = new ClsComFnc();
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "HoyuDaisuIn";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HFURIKAE (";
        $strSQL .= "  KEIJO_DT";
        $strSQL .= ", ID";
        $strSQL .= ", DENPY_NO";
        $strSQL .= ", GYO_NO";
        $strSQL .= ", TAISK_KB";
        $strSQL .= ", BUSYO_CD";
        $strSQL .= ", KAMOK_CD";
        $strSQL .= ", HIMOK_CD";
        $strSQL .= ", KEIJO_GK";
        $strSQL .= ", AITE_BUSYO_CD";
        $strSQL .= ", AITE_KAMOK_CD";
        $strSQL .= ", AITE_HIMOK_CD";
        $strSQL .= ", OA_KB";
        $strSQL .= ", HASEI_MOTO_KB";
        $strSQL .= ", CEL_DATE";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ", UPD_SYA_CD" . "\r\n";
        $strSQL .= ", UPD_PRG_ID" . "\r\n";
        $strSQL .= ", UPD_CLT_NM" . "\r\n";

        $strSQL .= ") VALUES (";
        $strSQL .= "  @KEIJO_DT";
        $strSQL .= ", '01'";
        $strSQL .= ", @DENPY_NO";
        $strSQL .= ", @GYO_NO";
        $strSQL .= ", '1'";
        $strSQL .= ", @BUSYO_CD";
        $strSQL .= ", @KAMOK_CD";
        $strSQL .= ",''";
        $strSQL .= ", @KEIJO_GK";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", NULL";
        $strSQL .= ", 'HY'";
        $strSQL .= ", NULL";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", '@UPDUSER'" . "\r\n";
        $strSQL .= ", '@UPDAPP'" . "\r\n";
        $strSQL .= ", '@UPDCLT'" . "\r\n";

        $strSQL .= ")";

        $strSQL = str_replace("@KEIJO_DT", $ymd, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        $strSQL = str_replace("@DENPY_NO", $this->ClsComFnc->FncSqlNv($lngSeqNO), $strSQL);
        $strSQL = str_replace("@GYO_NO", $this->ClsComFnc->FncSqlNz($intGyoNO), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $this->ClsComFnc->FncSqlNv($value[1]), $strSQL);
        $strSQL = str_replace("@KEIJO_GK", $this->ClsComFnc->FncSqlNz($value[3]), $strSQL);
        $strSQL = str_replace("@KAMOK_CD", $this->ClsComFnc->FncSqlNv($value[5]), $strSQL);

        return $strSQL;

    }

    public function fncGetZandakaInsert($value, $postData)
    {

        $strSql = $this->fncGetZandakaInsertSql($value, $postData);

        return parent::Do_Execute($strSql);
    }

    public function fncGetFurikaeInsert($value, $lngSeqNO, $intGyoNO, $postData)
    {

        $strSql = $this->fncGetFurikaeInsertSql($value, $lngSeqNO, $intGyoNO, $postData);

        return parent::Do_Execute($strSql);
    }

    public function fncFurikaeDelete($postData = NULL)
    {
        $strSql = $this->fncFurikaeDeleteSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncZandakaDelete($postData = NULL)
    {
        $strSql = $this->fncZandakaDeleteSql($postData);

        return parent::Do_Execute($strSql);
    }

    public function fncUpdHksaiban($intSu)
    {
        $strSql = $this->fncUpdHksaibanSql($intSu);

        return parent::Do_Execute($strSql);
    }

    public function fncSelHksaiban()
    {
        $strSql = $this->fncSelHksaibanSql();

        return parent::Fill($strSql);
    }

    public function frmSampleLoadDate()
    {
        $strSql = $this->frmSampleLoadDateSql();

        return parent::select($strSql);
    }

}