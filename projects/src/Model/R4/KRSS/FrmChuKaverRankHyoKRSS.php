<?php
// 共通クラスの読込み
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmChuKaverRankHyoKRSS extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    private $ClsComFnc;

    function fncUriageRankSelSql($postData = NULL)
    {
        $ym = str_replace("/", "", $postData['cboYMEnd']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        $m1 = (int) $m;

        $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $ymd = $y . $m . $d;
        $ymd1 = $y . $m . '01';
        $strSQL = "";
        $strSQL .= "SELECT *" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "SELECT RANK() OVER(ORDER BY V.TOUKI_DAISU DESC) TOUKI_JUNI" . "\r\n";
        $strSQL .= ",      V.SYAIN_NO" . "\r\n";
        $strSQL .= ",      V.SYAIN_NM" . "\r\n";
        $strSQL .= ",      SUBSTR(V.BUSYO_CD,7,3) BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      V.TOUKI_DAISU" . "\r\n";
        $strSQL .= ",      RANK() OVER(ORDER BY V.TOUGETU_DAISU DESC) TOUGETU_JUNI" . "\r\n";
        $strSQL .= ",      V.TOUGETU_DAISU" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "		SELECT SLS.SYAIN_NO" . "\r\n";
        $strSQL .= "     ,      SYA.SYAIN_NM" . "\r\n";

        $strSQL .= "		,      MAX(SLS.KEIJO_DT || SLS.BUSYO_CD) BUSYO_CD" . "\r\n";

        $strSQL .= "		,      SUM(NVL(SLS.CHU_DAISU,0)) TOUKI_DAISU" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SLS.KEIJO_DT = '@TUKI' THEN NVL(SLS.CHU_DAISU,0) ELSE 0 END) TOUGETU_DAISU" . "\r\n";
        $strSQL .= "		FROM   HSLSSTAFF SLS" . "\r\n";
        $strSQL .= "				INNER JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "				ON     SYA.SYAIN_NO = SLS.SYAIN_NO" . "\r\n";

        //2007/04/01 李 insert ST
        $strSQL .= "				INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "				ON     SLS.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "             AND    HAI.START_DATE <='" . $ymd . "'" . "\r\n";
        $strSQL .= "             AND    NVL(HAI.END_DATE,'99999999') >='" . $ymd . "'" . "\r\n";

        $strSQL .= "             AND    SLS.DISP_KB = HAI.DISP_KB" . "\r\n";

        $strSQL .= "		WHERE  SLS.KEIJO_DT >= '@KISYU' " . "\r\n";
        $strSQL .= "		AND    SLS.KEIJO_DT <= '@TUKI'" . "\r\n";
        $strSQL .= "				AND    HAI.DISP_KB = '2'" . "\r\n";
        $strSQL .= "             AND    NVL(SYA.TAISYOKU_DATE,'99999999') >= '" . $ymd1 . "'" . "\r\n";
        $strSQL .= "		GROUP BY SLS.SYAIN_NO" . "\r\n";
        $strSQL .= "		,        SYA.SYAIN_NM" . "\r\n";
        $strSQL .= "       ) V" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = SUBSTR(V.BUSYO_CD,7,3)" . "\r\n";
        $strSQL .= "ORDER BY V.TOUKI_DAISU DESC" . "\r\n";
        $strSQL .= ") RANK_TBL" . "\r\n";

        if ($this->ClsComFnc->FncNz((float) $postData['Rank']) != 0) {
            $strSQL .= "WHERE   TOUKI_JUNI <= @JUNI" . "\r\n";
        }

        if ($postData['radBusyoCheck'] == 'true') {
            $strSQL .= "ORDER BY RANK_TBL.BUSYO_CD, RANK_TBL.SYAIN_NO" . "\r\n";
        }

        $strSQL = str_replace("@KISYU", $postData['cboYMStart'], $strSQL);
        $strSQL = str_replace("@TUKI", substr(str_replace("/", "", $postData['cboYMEnd']), 0, 6), $strSQL);
        $strSQL = str_replace("@JUNI", $postData['Rank'], $strSQL);
        //        $this -> log($strSQL);
        return $strSQL;

    }

    function frmKanrSyukei_LoadSql()
    {

        $strSQL = "";

        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";

        return $strSQL;
    }

    function fncStandardInfoSelSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "       '第@JIKI期中古車営業スタッフ総限界利益・固定費カバー率ランキング表' || '@SUBTITLE' TITLE" . "\r\n";
        //        $strSQL .= ",      SUBSTR(JPDATE('@KISYU' || '01'),2,2) KISYU_Y" . "\r\n";
//        $strSQL .= ",      SUBSTR(JPDATE('@KISYU' || '01'),4,2) KISYU_M" . "\r\n";
//        $strSQL .= ",      SUBSTR(JPDATE('@TUKI'),2,2) TUKI_Y" . "\r\n";
//        $strSQL .= ",      SUBSTR(JPDATE('@TUKI'),4,2) TUKI_M" . "\r\n";
        $strSQL .= ",      SUBSTR( '@KISYU' || '01' ,0,4) KISYU_Y" . "\r\n";
        $strSQL .= ",      SUBSTR( '@KISYU' || '01' ,5,2) KISYU_M" . "\r\n";
        $strSQL .= ",      SUBSTR( '@TUKI' ,0,4 ) TUKI_Y" . "\r\n";
        $strSQL .= ",      SUBSTR( '@TUKI' ,5,2) TUKI_M" . "\r\n";

        $strSQL .= "FROM   DUAL" . "\r\n";

        $strSQL = str_replace("@JIKI", (int) (substr($postData['cboYMStart'], 0, 4) - 1917), $strSQL);
        $strSQL = str_replace("@KISYU", $postData['cboYMStart'], $strSQL);
        $strSQL = str_replace("@TUKI", str_replace("/", "", $postData['cboYMEnd']), $strSQL);
        $tmp = ($postData['radYachinCheck'] == 'true') ? "(家賃を除く)" : "";
        $strSQL = str_replace("@SUBTITLE", $tmp, $strSQL);
        //        $this -> log($strSQL);
        return $strSQL;
    }

    function fncKaverRankSelSql($postData = NULL)
    {
        $this->ClsComFnc = new ClsComFnc();
        $ym = str_replace("/", "", $postData['cboYMEnd']);

        $y = substr($ym, 0, 4);
        $m = substr($ym, 4, 2);
        $m1 = (int) $m;

        $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $ymd = $y . $m . $d;
        $ymd1 = $y . $m . '01';
        $strSQL = "";
        $strSQL .= "SELECT RANK_TBL.TOUKI_JUN" . "\r\n";
        $strSQL .= ",      RANK_TBL.SYAIN_NO" . "\r\n";
        $strSQL .= ",      RANK_TBL.SYAIN_NM" . "\r\n";
        $strSQL .= ",      RANK_TBL.BUSYO_CD" . "\r\n";
        $strSQL .= ",      RANK_TBL.BUSYO_NM" . "\r\n";
        $strSQL .= ",      RANK_TBL.KOTEI_KAVER_RT" . "\r\n";
        $strSQL .= ",      RANK_TBL.TOUKI_GENRI" . "\r\n";
        $strSQL .= ",      RANK_TBL.KOTEIHI" . "\r\n";
        $strSQL .= ",      RANK_TBL.WORK_BUNPAI_RT" . "\r\n";
        $strSQL .= ",      RANK_TBL.Y_MINUS_KOTEI" . "\r\n";
        $strSQL .= ",      RANK_TBL.Y_MINUS_KAVER_RT" . "\r\n";
        $strSQL .= ",      RANK_TBL.SANKO_JUN" . "\r\n";
        //add start
        $strSQL .= ",      RANK_TBL.KANRI_DAISU" . "\r\n";
        $strSQL .= ",      RANK_TBL.KEIKEN_NENSU" . "\r\n";
        //add end
        if ($postData['radBusyoCheck'] == 'true') {
            $strSQL .= ",      DENSE_RANK() OVER(ORDER BY RANK_TBL.BUSYO_CD) COLOR_NO" . "\r\n";
        }

        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "SELECT RANK() OVER(ORDER BY RT_TBL.KOTEI_KAVER_RT DESC) TOUKI_JUN" . "\r\n";
        $strSQL .= ",      RT_TBL.SYAIN_NO" . "\r\n";
        $strSQL .= ",      RT_TBL.SYAIN_NM" . "\r\n";
        $strSQL .= ",      RT_TBL.BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      RT_TBL.KOTEI_KAVER_RT" . "\r\n";
        $strSQL .= ",      RT_TBL.TOUKI_GENRI" . "\r\n";
        $strSQL .= ",      RT_TBL.KOTEIHI" . "\r\n";
        $strSQL .= ",      RT_TBL.WORK_BUNPAI_RT" . "\r\n";
        $strSQL .= ",      RT_TBL.Y_MINUS_KOTEI" . "\r\n";
        $strSQL .= ",      RT_TBL.Y_MINUS_KAVER_RT" . "\r\n";
        $strSQL .= ",      RANK() OVER(ORDER BY RT_TBL.Y_MINUS_KAVER_RT DESC) SANKO_JUN" . "\r\n";
        //add start
        $strSQL .= ",      RT_TBL.KANRI_DAISU" . "\r\n";
        $strSQL .= ",     RT_TBL.KEIKEN_NENSU" . "\r\n";
        //add end
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "		SELECT V.SYAIN_NO" . "\r\n";
        $strSQL .= "		,      V.SYAIN_NM" . "\r\n";
        $strSQL .= "		,      SUBSTR(V.BUSYO_CD,7,3) BUSYO_CD" . "\r\n";
        $strSQL .= "		,      DECODE(V.KOTEIHI,0,0,ROUND(V.TOUKI_GENRI / V.KOTEIHI * 100,1)) KOTEI_KAVER_RT" . "\r\n";
        $strSQL .= "		,      ROUND(V.TOUKI_GENRI/1000,0) TOUKI_GENRI" . "\r\n";
        $strSQL .= "		,      ROUND(V.KOTEIHI/1000,0) KOTEIHI" . "\r\n";
        $strSQL .= "		,      DECODE(V.TOUKI_GENRI,0,0,ROUND(V.JINKENHI / V.TOUKI_GENRI * 100,1)) WORK_BUNPAI_RT" . "\r\n";
        $strSQL .= "     ,      ROUND(V.Y_MINUS_KOTEI/1000,0) Y_MINUS_KOTEI" . "\r\n";
        $strSQL .= "		,      DECODE(V.Y_MINUS_KOTEI,0,0,ROUND(V.TOUKI_GENRI / V.Y_MINUS_KOTEI * 100,1)) Y_MINUS_KAVER_RT" . "\r\n";
        //add start
        $strSQL .= ",      V.KANRI_DAISU" . "\r\n";
        $strSQL .= ",      V.KEIKEN_NENSU" . "\r\n";
        //add end
        $strSQL .= "		FROM   (" . "\r\n";
        $strSQL .= "				SELECT SLS.SYAIN_NO" . "\r\n";
        $strSQL .= "				,      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= "				,      MAX(SLS.KEIJO_DT || SLS.BUSYO_CD) BUSYO_CD" . "\r\n";
        $strSQL .= "				,      SUM(NVL(SLS.SOU_GENRI,0)) TOUKI_GENRI" . "\r\n";
        $strSQL .= "				,      SUM(NVL(SLS.KOTEIHI_YACHIN,0)) KOTEIHI" . "\r\n";
        $strSQL .= "				,      SUM(NVL(SLS.SOU_JINKEN,0)) JINKENHI" . "\r\n";
        $strSQL .= "				,      SUM(NVL(SLS.KOTEIHIKEI,0)) Y_MINUS_KOTEI" . "\r\n";
        //add start
        $strSQL .= ",      MAX(" . "\r\n";
        $strSQL .= "         CASE" . "\r\n";
        $strSQL .= "          WHEN SLS.KEIJO_DT >= '@KISYU' AND SLS.KEIJO_DT <= '@TUKI'" . "\r\n";
        $strSQL .= "           THEN NVL(SLS.KNR_DAISU,0)" . "\r\n";
        $strSQL .= "          ELSE 0" . "\r\n";
        $strSQL .= "        END) KANRI_DAISU ," . "\r\n";
        $strSQL .= "        MAX(" . "\r\n";
        $strSQL .= "       CASE" . "\r\n";
        $strSQL .= "         WHEN SLS.KEIJO_DT >= '@KISYU' AND SLS.KEIJO_DT <= '@TUKI'" . "\r\n";
        $strSQL .= "        THEN NVL(SLS.KEI_NEN_SU,0)" . "\r\n";
        $strSQL .= "        ELSE 0" . "\r\n";
        $strSQL .= "       END) KEIKEN_NENSU" . "\r\n";
        //add end
        $strSQL .= "				FROM   HSLSSTAFF SLS" . "\r\n";
        $strSQL .= "				INNER JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "				ON     SLS.SYAIN_NO = SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "				INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "				ON     SLS.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "             AND    HAI.START_DATE <='" . $ymd . "'" . "\r\n";
        $strSQL .= "             AND    NVL(HAI.END_DATE,'99999999') >='" . $ymd . "'" . "\r\n";
        $strSQL .= "             AND    SLS.DISP_KB = HAI.DISP_KB" . "\r\n";
        $strSQL .= "				WHERE  SLS.KEIJO_DT >= '@KISYU' " . "\r\n";
        $strSQL .= "				AND    SLS.KEIJO_DT <= '@TUKI'" . "\r\n";
        $strSQL .= "				AND    HAI.DISP_KB = '2'" . "\r\n";
        $strSQL .= "             AND    NVL(SYA.TAISYOKU_DATE,'99999999') >= '" . $ymd1 . "'" . "\r\n";
        $strSQL .= "				GROUP BY SLS.SYAIN_NO, SYA.SYAIN_NM" . "\r\n";
        $strSQL .= "		       ) V" . "\r\n";
        $strSQL .= "        ) RT_TBL" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        BUS.BUSYO_CD = RT_TBL.BUSYO_CD" . "\r\n";

        if ($postData['radBusyoCheck'] == 'true' || $postData['radRankingCheck'] == 'true') {
            $strSQL .= "ORDER BY RT_TBL.KOTEI_KAVER_RT DESC" . "\r\n";
        } else {
            $strSQL .= "ORDER BY RT_TBL.Y_MINUS_KAVER_RT DESC" . "\r\n";
        }

        $strSQL .= ") RANK_TBL" . "\r\n";

        if ($this->ClsComFnc->FncNz((float) $postData['Rank']) != 0) {
            if ($postData['radRankingCheck'] == 'true') {
                $strSQL .= "WHERE    SANKO_JUN <= @JUNI" . "\r\n";
            } else {
                $strSQL .= "WHERE    TOUKI_JUN <= @JUNI" . "\r\n";
            }
        }

        if ($postData['radBusyoCheck'] == 'true') {
            $strSQL .= "ORDER BY RANK_TBL.BUSYO_CD, RANK_TBL.SYAIN_NO" . "\r\n";
        }

        $strSQL = str_replace("@JIKI", (int) (substr($postData['cboYMStart'], 0, 4) - 1917), $strSQL);
        $strSQL = str_replace("@KISYU", $postData['cboYMStart'], $strSQL);
        $strSQL = str_replace("@TUKI", substr(str_replace("/", "", $postData['cboYMEnd']), 0, 6), $strSQL);
        $strSQL = str_replace("@JUNI", $postData['Rank'], $strSQL);
        return $strSQL;
    }

    function fncMemoSelSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT MEMO" . "\r\n";
        $strSQL .= ",      FONT_SIZE" . "\r\n";
        $strSQL .= ",      FONT_TYPE" . "\r\n";
        $strSQL .= "FROM   HSTAFFMEMO" . "\r\n";
        $strSQL .= "WHERE  ID = '01'" . "\r\n";
        $strSQL .= "AND    NAU_KB = '2'" . "\r\n";
        $strSQL .= "ORDER BY GYO_NO" . "\r\n";
        // echo $strSQL;
//        $this -> log($strSQL);
        return $strSQL;
    }

    public function frmKanrSyukei_Load()
    {
        $strSql = $this->frmKanrSyukei_LoadSql();
        return parent::select($strSql);
    }

    public function fncStandardInfoSel($postData = NULL)
    {
        $strSql = $this->fncStandardInfoSelSql($postData);
        return parent::select($strSql);
    }

    public function fncKaverRankSel($postData = NULL)
    {
        $strSql = $this->fncKaverRankSelSql($postData);
        return parent::select($strSql);
    }

    public function fncUriageRankSel($postData = NULL)
    {
        $strSql = $this->fncUriageRankSelSql($postData);

        return parent::select($strSql);
    }

    public function fncMemoSel()
    {
        $strSql = $this->fncMemoSelSql();
        return parent::select($strSql);
    }

}
