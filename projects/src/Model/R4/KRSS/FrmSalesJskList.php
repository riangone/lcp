<?php
/*
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 		日付        			 Feature/Bug         			内容                 		担当
 * YYYYMMDD   		 		#ID            			XXXXXX               		FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
class FrmSalesJskList extends ClsComDb
{
    /**
     * 注文書情報取得
     * @param {String} $dtlSyoribi
     * @return {String} select文
     */
    public function fncPrintSelectSql($dtlSyoribi)
    {

        $strSQL = "";
        $strSQL .= "SELECT '@TUKI1'" . "\r\n";
        $strSQL .= ",  TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= ",  SYSDATE" . "\r\n";
        $strSQL .= ",  SALES.KKR_CD" . "\r\n";
        $strSQL .= ",  SALES.BUSYO_CD" . "\r\n";
        $strSQL .= ",  BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",  MNG.SYAIN_NM MANAGER" . "\r\n";
        $strSQL .= ",  (CASE WHEN SALES.NSG_KB = '1' THEN 'N'" . "\r\n";
        $strSQL .= "     WHEN SALES.NSG_KB = '2' THEN 'S'" . "\r\n";
        $strSQL .= "     ELSE 'G' END) KBN" . "\r\n";
        $strSQL .= ",  '(' || (CASE WHEN SALES.SYAIN_NO = '99999' THEN RPAD(SALES.GYOSYA_CD,5) ELSE RPAD(SALES.SYAIN_NO,5) END) || ')' CD" . "\r\n";
        $strSQL .= ",  (CASE WHEN SALES.SYAIN_NO = '99999' THEN GSY.GYOSYA_NM ELSE SYA.SYAIN_NM END) NAME" . "\r\n";
        $strSQL .= ",  MEI.MEISYOU" . "\r\n";
        $strSQL .= ",  TSY.HOYU" . "\r\n";
        $strSQL .= ",  TSY.TEISYU" . "\r\n";
        $strSQL .= ",      '@TOUGETU' TOUGETU" . "\r\n";
        $strSQL .= ",  NDAI1" . "\r\n";
        $strSQL .= ",  SDAI1" . "\r\n";
        $strSQL .= ",  GK1" . "\r\n";
        $strSQL .= ",  NDAI2" . "\r\n";
        $strSQL .= ",  SDAI2" . "\r\n";
        $strSQL .= ",  GK2" . "\r\n";
        $strSQL .= ",  NDAI3" . "\r\n";
        $strSQL .= ",  SDAI3" . "\r\n";
        $strSQL .= ",  GK3" . "\r\n";
        $strSQL .= ",  NDAI4" . "\r\n";
        $strSQL .= ",  SDAI4" . "\r\n";
        $strSQL .= ",  GK4" . "\r\n";
        $strSQL .= ",  NDAI5" . "\r\n";
        $strSQL .= ",  SDAI5" . "\r\n";
        $strSQL .= ",  GK5" . "\r\n";
        $strSQL .= ",  NDAI6" . "\r\n";
        $strSQL .= ",  SDAI6" . "\r\n";
        $strSQL .= ",  GK6" . "\r\n";
        $strSQL .= ",  NDAI7" . "\r\n";
        $strSQL .= ",  SDAI7" . "\r\n";
        $strSQL .= ",  GK7" . "\r\n";
        $strSQL .= ",  NDAI8" . "\r\n";
        $strSQL .= ",  SDAI8" . "\r\n";
        $strSQL .= ",  GK8" . "\r\n";
        $strSQL .= ",  NDAI9" . "\r\n";
        $strSQL .= ",  SDAI9" . "\r\n";
        $strSQL .= ",  GK9" . "\r\n";
        $strSQL .= ",  NDAI10" . "\r\n";
        $strSQL .= ",  SDAI10" . "\r\n";
        $strSQL .= ",  GK10" . "\r\n";
        $strSQL .= ",  NDAI11" . "\r\n";
        $strSQL .= ",  SDAI11" . "\r\n";
        $strSQL .= ",  GK11" . "\r\n";
        $strSQL .= ",  NDAI12" . "\r\n";
        $strSQL .= ",  SDAI12" . "\r\n";
        $strSQL .= ",  GK12" . "\r\n";
        $strSQL .= ",  NDAI13" . "\r\n";
        $strSQL .= ",  SDAI13" . "\r\n";
        $strSQL .= ",  GK13" . "\r\n";
        $strSQL .= "FROM   (" . "\r\n";
        $strSQL .= "		SELECT SL.KKR_CD" . "\r\n";
        $strSQL .= "        ,  SL.BUSYO_CD" . "\r\n";
        $strSQL .= "  ,  SL.SYAIN_NO" . "\r\n";
        $strSQL .= "  ,  SL.GYOSYA_CD" . "\r\n";
        $strSQL .= "        ,  SL.NSG_KB" . "\r\n";
        $strSQL .= "	    ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI1' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI1" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI1' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI1" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI1' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK1" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI2' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI2" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI2' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI2" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI2' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK2" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI3' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI3" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI3' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI3" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI3' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK3" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI4' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI4" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI4' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI4" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI4' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK4" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI5' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI5" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI5' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI5" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI5' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK5" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI6' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI6" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI6' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI6" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI6' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK6" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI7' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI7" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI7' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI7" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI7' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK7" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI8' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI8" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI8' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI8" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI8' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK8" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI9' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI9" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI9' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI9" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKI9' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK9" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIA' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI10" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIA' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI10" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIA' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK10" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIB' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI11" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIB' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI11" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIB' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK11" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIC' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI12" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIC' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI12" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKIC' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK12" . "\r\n";
        $strSQL .= "     ,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKID' THEN NVL(SL.NEW_DAISU,0) ELSE 0 END) NDAI13" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKID' THEN NVL(SL.USED_DAISU,0) ELSE 0 END) SDAI13" . "\r\n";
        $strSQL .= "		,      SUM(CASE WHEN SL.KEIJO_DT = '@TUKID' THEN NVL(SL.GENRI_GK,0) ELSE 0 END) GK13" . "\r\n";
        $strSQL .= "   			" . "\r\n";
        $strSQL .= "		FROM   HSGENJ SL" . "\r\n";
        $strSQL .= "		WHERE  SL.KEIJO_DT <= '@TUKI1'" . "\r\n";
        $strSQL .= "		AND    SL.KEIJO_DT >= '@TUKID'" . "\r\n";
        $strSQL .= "		" . "\r\n";
        $strSQL .= "		GROUP BY SL.KKR_CD" . "\r\n";
        $strSQL .= "        ,  SL.BUSYO_CD" . "\r\n";
        $strSQL .= "        ,  SL.SYAIN_NO" . "\r\n";
        $strSQL .= "        ,  SL.GYOSYA_CD" . "\r\n";
        $strSQL .= "        ,  SL.NSG_KB" . "\r\n";
        $strSQL .= ") SALES" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = SALES.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = SALES.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HSYAINMST MNG" . "\r\n";
        $strSQL .= "ON     MNG.SYAIN_NO = BUS.MANEGER_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HTEISYU TSY" . "\r\n";
        $strSQL .= "ON     TSY.SYAIN_NO = SALES.SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HGYOSYAMST GSY" . "\r\n";
        $strSQL .= "ON     GSY.GYOSYA_CD = SALES.GYOSYA_CD" . "\r\n";
        $strSQL .= "LEFT JOIN" . "\r\n";
        $strSQL .= "       HMEISYOUMST MEI" . "\r\n";
        $strSQL .= "ON     MEI.MEISYOU_ID = '20'" . "\r\n";
        $strSQL .= "AND    MEI.MEISYOU_CD = SYA.SIKAKU_CD" . "\r\n";
        $strSQL .= "ORDER BY SALES.KKR_CD, SALES.BUSYO_CD, SYA.SIKAKU_CD, SALES.SYAIN_NO, SALES.GYOSYA_CD" . "\r\n";

        $strSQL = str_replace("@TOUGETU", $dtlSyoribi . "/01", $strSQL);
        $dtlSyoribir = str_replace("/", "", $dtlSyoribi);
        $strSQL = str_replace("@TUKI1", $dtlSyoribir, $strSQL);
        $strSQL = str_replace("@TUKI2", $this->addMonths($dtlSyoribi, -1), $strSQL);
        $strSQL = str_replace("@TUKI3", $this->addMonths($dtlSyoribi, -2), $strSQL);
        $strSQL = str_replace("@TUKI4", $this->addMonths($dtlSyoribi, -3), $strSQL);
        $strSQL = str_replace("@TUKI5", $this->addMonths($dtlSyoribi, -4), $strSQL);
        $strSQL = str_replace("@TUKI6", $this->addMonths($dtlSyoribi, -5), $strSQL);
        $strSQL = str_replace("@TUKI7", $this->addMonths($dtlSyoribi, -6), $strSQL);
        $strSQL = str_replace("@TUKI8", $this->addMonths($dtlSyoribi, -7), $strSQL);
        $strSQL = str_replace("@TUKI9", $this->addMonths($dtlSyoribi, -8), $strSQL);
        $strSQL = str_replace("@TUKIA", $this->addMonths($dtlSyoribi, -9), $strSQL);
        $strSQL = str_replace("@TUKIB", $this->addMonths($dtlSyoribi, -10), $strSQL);
        $strSQL = str_replace("@TUKIC", $this->addMonths($dtlSyoribi, -11), $strSQL);
        $strSQL = str_replace("@TUKID", $this->addMonths($dtlSyoribi, -12), $strSQL);



        return $strSQL;
    }

    public function frmsampleloadSql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || SUBSTR(SYR_YMD,5,2) ) TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";

        return $strSQL;
    }

    public function frmKanrSyukei_Load()
    {
        $str_sql = $this->frmsampleloadSql();

        return parent::select($str_sql);
    }

    public function cmdActionClickSql()
    {
        $strSQL = "DELETE FROM HSGENJ";
        return $strSQL;
    }

    public function cmdAction_Click()
    {
        $str_sql = $this->cmdActionClickSql();

        return parent::delete($str_sql);
    }

    public function fncPrintSelect($dtlSyoribi)
    {
        $str_sql = $this->fncPrintSelectSql($dtlSyoribi);

        return parent::select($str_sql);
    }

    public function addMonths($ym, $val)
    {
        $y = substr($ym, 0, 4);
        $m = substr($ym, 5, 2);
        $tmp = (int) $m + (int) $val;
        if ($tmp < 0) {
            $y = $y - 1;
            $m = $tmp + 12;
            $m = ($m < 10) ? "0" . $m : $m;

        } elseif ($tmp == 0) {
            $y = $y - 1;
            $m = 12;

        } else {
            $m = $tmp;
            $m = ($m < 10) ? "0" . $m : $m;
        }

        $ym = $y . $m;
        return $ym;

    }

}
