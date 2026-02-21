<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSyokaiFurikae extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";

    function fncFromChuSyokaiSelectSql($postData = NULL)
    {
        $ym = $postData['KEIJOBI'];
        $y = substr($ym, 0, 4);
        //20211108 UPD START 
//			$m = substr($ym, 5, 2);
        $m = substr($ym, 4, 2);
        //20211108 UPD END
        // $m1 = (int) $m;
        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;

        $strSQL = "SELECT (SUBSTR(STF.KEIJO_DT,1,4) || '/' || SUBSTR(STF.KEIJO_DT,5,2) || '/01') KEIJO_DT" . "\r\n";
        $strSQL .= ",      STF.DENPY_NO" . "\r\n";
        $strSQL .= ",      STF.GYO_NO" . "\r\n";
        $strSQL .= ",      M_HAI.BUSYO_CD MOT_BUSYO_CD" . "\r\n";
        $strSQL .= ",      STF.MOT_SYAIN_NO" . "\r\n";
        $strSQL .= ",      M_SYA.SYAIN_NM MOT_SYAIN_NM" . "\r\n";
        $strSQL .= ",      S_HAI.BUSYO_CD SAKI_BUSYO_CD" . "\r\n";
        $strSQL .= ",      STF.SAKI_SYAIN_NO" . "\r\n";
        $strSQL .= ",      S_SYA.SYAIN_NM SAKI_SYAIN_NM" . "\r\n";
        $strSQL .= ",      STF.KEIJO_GK" . "\r\n";
        $strSQL .= ",      M_BUS.BUSYO_NM MOT_BUSYO_NM" . "\r\n";
        $strSQL .= ",      S_BUS.BUSYO_NM SAKI_BUSYO_NM" . "\r\n";
        $strSQL .= ",      STF.DISP_NO" . "\r\n";
        $strSQL .= ",      STF.CREATE_DATE" . "\r\n";
        $strSQL .= "FROM   HSTAFFCHUSYOKAI STF" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST M_SYA" . "\r\n";
        $strSQL .= "ON     M_SYA.SYAIN_NO = STF.MOT_SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST S_SYA" . "\r\n";
        $strSQL .= "ON     S_SYA.SYAIN_NO = STF.SAKI_SYAIN_NO" . "\r\n";

        $strSQL .= "LEFT JOIN HHAIZOKU M_HAI" . "\r\n";
        $strSQL .= "ON      M_HAI.SYAIN_NO = STF.MOT_SYAIN_NO" . "\r\n";
        $strSQL .= "AND     M_HAI.START_DATE <='" . $ymd . "'" . "\r\n";
        $strSQL .= "AND     NVL(M_HAI.END_DATE,'99999999') >='" . $ymd . "'" . "\r\n";
        $strSQL .= "LEFT JOIN HHAIZOKU S_HAI" . "\r\n";
        $strSQL .= "ON      S_HAI.SYAIN_NO = STF.SAKI_SYAIN_NO" . "\r\n";
        $strSQL .= "AND     S_HAI.START_DATE <='" . $ymd . "'" . "\r\n";
        $strSQL .= "AND     NVL(S_HAI.END_DATE,'99999999') >='" . $ymd . "'" . "\r\n";

        $strSQL .= "LEFT JOIN HBUSYO M_BUS" . "\r\n";
        $strSQL .= "ON     M_BUS.BUSYO_CD = M_HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO S_BUS" . "\r\n";
        $strSQL .= "ON     S_BUS.BUSYO_CD = S_HAI.BUSYO_CD" . "\r\n";

        $strSQL .= "WHERE  STF.KEIJO_DT = '@KEIJOBI'" . "\r\n";
        $strSQL .= "AND    STF.DENPY_NO = '@DENPYNO'" . "\r\n";
        $strSQL .= "ORDER BY DISP_NO" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@DENPYNO", $postData['DENPYNO'], $strSQL);
        //$this->log($strSQL);
        return $strSQL;

    }

    function frmSampleLoadDateSql()
    {

        $strSQL = "SELECT ID" . "\r\n";

        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";

        $strSQL .= "   FROM  HKEIRICTL" . "\r\n";

        $strSQL .= "  WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    function fncDataSetSql()
    {

        $strSQL = "SELECT";

        $strSQL .= "     NVL(BUSYO_CD,'') AS BUSYOCD";

        $strSQL .= "    ,NVL(BUSYO_NM,'') AS BUSYONM";

        $strSQL .= "    ,NVL(KKR_BUSYO_CD,'') as KKRCD";

        $strSQL .= " FROM HBUSYO";

        $strSQL .= " WHERE ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')";

        $strSQL .= " ORDER BY BUSYO_CD";

        return $strSQL;
    }

    function fncDataSetSyainSql()
    {
        $strSQL = "SELECT";
        $strSQL .= "	a.BUSYO_CD," . "\r\n";
        $strSQL .= "	RTRIM(a.SYAIN_NO) SYAINNO," . "\r\n";
        $strSQL .= "	a.SYAIN_NM," . "\r\n";
        $strSQL .= "	a.START_DATE," . "\r\n";
        $strSQL .= "	a.END_DATE," . "\r\n";
        $strSQL .= "	a.TAISYOKU_DATE" . "\r\n";
        $strSQL .= "	FROM" . "\r\n";
        $strSQL .= "	  (" . "\r\n";
        $strSQL .= "	    SELECT" . "\r\n";
        $strSQL .= "	    HAI.BUSYO_CD," . "\r\n";
        $strSQL .= "	     SYA.SYAIN_NO," . "\r\n";
        $strSQL .= "	    SYA.SYAIN_NM," . "\r\n";
        $strSQL .= "	 HAI.START_DATE," . "\r\n";
        $strSQL .= "	 HAI.END_DATE," . "\r\n";
        $strSQL .= "	 SYA.TAISYOKU_DATE" . "\r\n";
        $strSQL .= "	 FROM" . "\r\n";
        $strSQL .= "	 HSYAINMST SYA" . "\r\n";
        $strSQL .= "	 LEFT JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "	ON" . "\r\n";
        $strSQL .= "	SYA.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "	)" . "\r\n";
        $strSQL .= "	a" . "\r\n";
        $strSQL .= "	LEFT JOIN HBUSYO HBU" . "\r\n";
        $strSQL .= "	ON" . "\r\n";
        $strSQL .= "	a.BUSYO_CD = HBU.BUSYO_CD" . "\r\n";
        $strSQL .= "	WHERE" . "\r\n";
        $strSQL .= "	a.BUSYO_CD IN" . "\r\n";
        $strSQL .= "	 (" . "\r\n";
        $strSQL .= "	SELECT" . "\r\n";
        $strSQL .= "	 HBU.BUSYO_CD" . "\r\n";
        $strSQL .= "	 FROM" . "\r\n";
        $strSQL .= "	 HBUSYO HBU" . "\r\n";
        $strSQL .= "	 WHERE" . "\r\n";
        $strSQL .= "	 (" . "\r\n";
        $strSQL .= "	HBU.SYUKEI_KB  IS NULL" . "\r\n";
        $strSQL .= "	OR HBU.SYUKEI_KB <> '1'" . "\r\n";
        $strSQL .= "	)" . "\r\n";
        $strSQL .= "	)" . "\r\n";
        $strSQL .= "	ORDER BY" . "\r\n";
        $strSQL .= "	a.BUSYO_CD" . "\r\n";

        return $strSQL;
    }

    function fncSyainmstExistSql($postData = NULL)
    {
        $ym = $postData['KJNBI'];
        $y = substr($ym, 0, 4);
        $m = substr($ym, 5, 2);
        // $m1 = (int) $m;
        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;

        $strSQL = "";
        $strSQL .= "SELECT SYA.SYAIN_NO" . "\r\n";
        $strSQL .= "FROM   HSYAINMST SYA" . "\r\n";
        $strSQL .= "LEFT JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <='" . $ymd . "'" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999')>= '" . $ymd . "'" . "\r\n";
        $strSQL .= "WHERE  SYA.SYAIN_NO = '@SYAINNO'" . "\r\n";
        $strSQL .= "AND    NVL(SYA.TAISYOKU_DATE,'99999999') >= '" . $y . $m . '01' . "'";

        $strSQL = str_replace("@SYAINNO", $postData['SyainNO'], $strSQL);
        return $strSQL;
    }

    function fncExistChukoSyokaiSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "SELECT DENPY_NO" . "\r\n";
        $strSQL .= ",      MOT_SYAIN_NO" . "\r\n";
        $strSQL .= ",      DISP_NO" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= "FROM   HSTAFFCHUSYOKAI" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJOBI'" . "\r\n";
        $strSQL .= "AND    DENPY_NO = '@DENPYNO'" . "\r\n";

        if ($postData['intChk'] == 2) {
            $strSQL .= "AND    MOT_SYAIN_NO = '@MOTNO'" . "\r\n";
            $strSQL .= "AND    SAKI_SYAIN_NO = '@SAKINO'" . "\r\n";
        }
        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@DENPYNO", $postData['DENPYNO'], $strSQL);
        $strSQL = str_replace("@MOTNO", $postData['MOTNO'], $strSQL);
        $strSQL = str_replace("@SAKINO", $postData['SAKINO'], $strSQL);

        return $strSQL;
    }

    function fncDeleteChuSyokaiSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HSTAFFCHUSYOKAI" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJOBI'" . "\r\n";
        $strSQL .= "AND    DENPY_NO = '@DENPYNO'" . "\r\n";
        $strSQL .= "AND    MOT_SYAIN_NO = '@MOTNO'" . "\r\n";
        $strSQL .= "AND    SAKI_SYAIN_NO = '@SAKINO'" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", $postData['KEIJOBI'], $strSQL);
        $strSQL = str_replace("@DENPYNO", $postData['DENPYNO'], $strSQL);
        $strSQL = str_replace("@MOTNO", $postData['MOTNO'], $strSQL);
        $strSQL = str_replace("@SAKINO", $postData['SAKINO'], $strSQL);

        return $strSQL;
    }

    function FncSqlNv2($objValue, $objReturn = "", $intKind = 1)
    {
        if ($objValue === null) {
            if ($objReturn != "") {
                return $objReturn;
            } else {
                return "''";
            }
        } else {
            if ($objValue == "") {
                return "Null";
            } else {
                if ($intKind == 1) {
                    return "'" . str_replace("'", "''", $objValue) . "'";
                } else {
                    return str_replace("'", "''", $objValue);
                }
            }
        }
    }

    function fncInsertChuSyokaiSql($postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "SyokaiFurikae";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HSTAFFCHUSYOKAI" . "\r\n";
        $strSQL .= "(      KEIJO_DT" . "\r\n";
        $strSQL .= ",      DENPY_NO" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      MOT_SYAIN_NO" . "\r\n";
        $strSQL .= ",      SAKI_SYAIN_NO" . "\r\n";
        $strSQL .= ",      KEIJO_GK" . "\r\n";
        $strSQL .= ",      DISP_NO" . "\r\n";
        $strSQL .= ",      UPD_DATE" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM" . "\r\n";

        $strSQL .= ") SELECT  " . "\r\n";
        $strSQL .= "       '@KEIJO_DT'" . "\r\n";
        $strSQL .= ",      '@DENPY_NO'" . "\r\n";
        $strSQL .= ",      NVL(MAX(GYO_NO),0) + 1" . "\r\n";
        $strSQL .= ",      '@MOT_SYAIN_NO'" . "\r\n";
        $strSQL .= ",      '@SAKI_SYAIN_NO'" . "\r\n";
        $strSQL .= ",      @KEIJO_GK" . "\r\n";
        $strSQL .= ",      @DISPNO" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      @CRE_DT" . "\r\n";
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";

        $strSQL .= "FROM   HSTAFFCHUSYOKAI" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJO_DT' AND DENPY_NO = '@DENPY_NO'" . "\r\n";

        $strSQL = str_replace("@KEIJO_DT", $postData['KEIJOBI'], $strSQL);
        $strSQL = str_replace("@DENPY_NO", $postData['DENPYNO'], $strSQL);
        $strSQL = str_replace("@GYO_NO", 1, $strSQL);
        $strSQL = str_replace("@MOT_SYAIN_NO", $postData['MOTNO'], $strSQL);
        $strSQL = str_replace("@SAKI_SYAIN_NO", $postData['SAKINO'], $strSQL);
        $strSQL = str_replace("@KEIJO_GK", (float) $postData['KEIJO_GK'], $strSQL);

        $DISPNO = ($postData['DISPNO'] != '') ? $this->FncSqlNv2($postData['DISPNO']) : "NVL(MAX(DISP_NO),0) + 1";
        $strSQL = str_replace("@DISPNO", $DISPNO, $strSQL);

        $CRE_DT = ($postData['CRE_DT'] != '') ? "TO_DATE(" . $this->FncSqlNv2($postData['CRE_DT']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";
        $strSQL = str_replace("@CRE_DT", $CRE_DT, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);
        return $strSQL;

    }

    public function fncInsertChuSyokai($postData = NULL)
    {
        $strSql = $this->fncInsertChuSyokaiSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncDeleteChuSyokaiExcu($postData = NULL)
    {
        $strSql = $this->fncDeleteChuSyokaiSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncDeleteChuSyokai($postData = NULL)
    {
        $strSql = $this->fncDeleteChuSyokaiSql($postData);
        return parent::delete($strSql);
    }

    public function fncExistChukoSyokai($postData = NULL)
    {
        $strSql = $this->fncExistChukoSyokaiSql($postData);
        return parent::select($strSql);
    }

    public function fncDataSetSyain()
    {
        $strSql = $this->fncDataSetSyainSql();
        return parent::select($strSql);
    }

    public function fncDataSet()
    {
        $strSql = $this->fncDataSetSql();
        return parent::select($strSql);
    }

    public function frmSampleLoadDate()
    {
        $strSql = $this->frmSampleLoadDateSql();
        return parent::select($strSql);
    }

    public function fncFromChuSyokaiSelect($postData = NULL)
    {
        $strSql = $this->fncFromChuSyokaiSelectSql($postData);
        return parent::select($strSql);
    }

    public function fncSyainmstExist($postData = NULL)
    {
        $strSql = $this->fncSyainmstExistSql($postData);
        return parent::select($strSql);
    }

}