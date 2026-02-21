<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmHendoKobetu extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";

    function frmSampleLoadDateSql()
    {

        $strSQL = "SELECT ID" . "\r\n";

        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";

        $strSQL .= "   FROM  HKEIRICTL" . "\r\n";

        $strSQL .= "  WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    function subComboSet2Sql()
    {
        $strSQL = "";

        $strSQL .= "SELECT (RPAD(MEISYOU_CD,3) || SUBSTRB(MOJI1,1,1)) MEISYOU_CD , MEISYOU ";

        $strSQL .= "FROM   HMEISYOUMST ";

        $strSQL .= " WHERE  MEISYOU_ID = '31'";

        return $strSQL;

    }

    function subComboSetSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "SELECT RTRIM(SYA.SYAIN_NO), SYA.SYAIN_NO, SYA.SYAIN_NM " . "\r\n";
        $strSQL .= " FROM  HSYAINMST SYA" . "\r\n";
        $strSQL .= "  LEFT JOIN HHAIZOKU HAI";
        $strSQL .= "  ON     SYA.SYAIN_NO = HAI.SYAIN_NO";
        $strSQL .= "  AND    HAI.START_DATE <= TO_CHAR(LAST_DAY(TO_DATE('@KJNBI','YYYYMMDD')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "  AND    NVL(HAI.END_DATE,'99999999')>= TO_CHAR(LAST_DAY(TO_DATE('@KJNBI','YYYYMMDD')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "  WHERE  NVL(SYA.TAISYOKU_DATE,'99999999') >= '@KJNBI'";

        if ($postData['strNameID'] != '') {
            $strSQL .= "  AND   HAI.BUSYO_CD = '" . $postData['strNameID'] . "'";

        }

        $tmp = str_replace("/", "", $postData['KJNBI']) . "01";
        $strSQL = str_replace("@KJNBI", $tmp, $strSQL);
        return $strSQL;

    }

    function fncFromHSTAFFSelectSql($postData, $blnCheck)
    {
        $strSQL = "";
        $strSQL .= "SELECT (SUBSTR(STF.KEIJO_DT,1,4) || '/' || SUBSTR(STF.KEIJO_DT,5,2) || '/01') KEIJO_DT" . "\r\n";
        $strSQL .= ",      (RPAD(STF.ITEM_CD,3) || STF.DATA_KB) ITEMNO" . "\r\n";
        $strSQL .= ",      STF.BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      STF.SYAIN_NO" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      NVL(STF.KEIJYO_GK,0) KEIJYO_GK" . "\r\n";
        $strSQL .= "      FROM   HSTAFFKOUMOKU STF" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = STF.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = STF.SYAIN_NO" . "\r\n";
        $strSQL .= "WHERE  STF.KEIJO_DT = '@KEIJOBI'" . "\r\n";
        $strSQL .= "AND    STF.ITEM_CD = '@ITEMCD'" . "\r\n";
        $strSQL .= "AND    STF.SYAIN_NO <> '00000'" . "\r\n";

        if ($blnCheck) {
            $strSQL .= "AND   STF.BUSYO_CD = '@BUSYOCD'" . "\r\n";
            $strSQL .= "AND   STF.SYAIN_NO = '@SYAINNO'" . "\r\n";
        }

        $strSQL .= "ORDER BY STF.BUSYO_CD, STF.SYAIN_NO" . "\r\n";
        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@ITEMCD", rtrim(substr(str_pad($postData['ItemNO'], 3, " ", STR_PAD_RIGHT), 0, 3)), $strSQL);
        $strSQL = str_replace("@BUSYOCD", $postData['strBusyoCD'], $strSQL);
        $strSQL = str_replace("@SYAINNO", $postData['SyainNO'], $strSQL);

        return $strSQL;
    }

    function fncExistCheckSelSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "SELECT A.KEIJO_DT" . "\r\n";
        $strSQL .= ",      A.BUSYO_CD" . "\r\n";
        $strSQL .= ",      A.SYAIN_NO" . "\r\n";
        $strSQL .= ",      A.ITEM_CD" . "\r\n";
        $strSQL .= "FROM   HSTAFFKOUMOKU A" . "\r\n";
        $strSQL .= "WHERE  A.KEIJO_DT = '@TOUGETU'" . "\r\n";
        $strSQL .= "AND    A.SYAIN_NO <> '00000'" . "\r\n";
        $strSQL .= "AND    A.ITEM_CD = '@ITEMCD'" . "\r\n";

        $strSQL = str_replace("@TOUGETU", str_replace("/", "", $postData['TOUGETU']), $strSQL);
        $strSQL = str_replace("@ITEMCD", rtrim(substr(str_pad($postData['ITEMCD'], 3, " ", STR_PAD_RIGHT), 0, 3)), $strSQL);
        return $strSQL;
    }

    function fncFromTeisyuDelSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HSTAFFKOUMOKU" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJO_DT'" . "\r\n";
        $strSQL .= "AND    ITEM_CD = '5'" . "\r\n";
        $strSQL .= "AND    SYAIN_NO <> '00000'" . "\r\n";
        $strSQL = str_replace("@KEIJO_DT", str_replace("/", "", $postData['KEIJO_DT']), $strSQL);
        return $strSQL;
    }

    function fncDeleteHSTAFFKomokuSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "DELETE FROM HSTAFFKOUMOKU" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJOBI'" . "\r\n";
        if ($postData['strBusyoCD'] != "") {
            $strSQL .= "AND    BUSYO_CD = '@BUSYOCD'" . "\r\n";
        }
        $strSQL .= "AND    SYAIN_NO = '@SYAINNO'" . "\r\n";
        $strSQL .= "AND    ITEM_CD = '@ITEMCD'" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@BUSYOCD", $postData['strBusyoCD'], $strSQL);
        $strSQL = str_replace("@SYAINNO", $postData['SyainNO'], $strSQL);
        $strSQL = str_replace("@ITEMCD", rtrim(substr(str_pad($postData['ITEMCD'], 3, " ", STR_PAD_RIGHT), 0, 3)), $strSQL);
        return $strSQL;
    }

    function fncInsertHSTAFFKomokuSql($postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "HendoKobetu";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HSTAFFKOUMOKU" . "\r\n";
        $strSQL .= "(      KEIJO_DT" . "\r\n";
        $strSQL .= ",      BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYAIN_NO" . "\r\n";
        $strSQL .= ",      ITEM_CD" . "\r\n";
        $strSQL .= ",      KEIJYO_GK" . "\r\n";
        $strSQL .= ",      DATA_KB" . "\r\n";
        $strSQL .= ",      DISP_NO" . "\r\n";
        $strSQL .= ",      UPD_DATE" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES  " . "\r\n";
        $strSQL .= "(      '@KEIJO_DT'" . "\r\n";
        $strSQL .= ",      '@BUSYO_CD'" . "\r\n";
        $strSQL .= ",      '@SYAIN_NO'" . "\r\n";
        $strSQL .= ",      '@ITEM_CD'" . "\r\n";
        $strSQL .= ",      @KEIJO_GK" . "\r\n";
        $strSQL .= ",      '@DATA_KB'" . "\r\n";
        $strSQL .= ",      (SELECT NVL(MAX(DISP_NO),0) + 1 FROM HSTAFFKOUMOKU WHERE KEIJO_DT = '@KEIJO_DT' AND ITEM_CD = '@ITEM_CD' AND SYAIN_NO <> '00000')" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@KEIJO_DT", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postData['strBusyoCD'], $strSQL);
        $strSQL = str_replace("@SYAIN_NO", $postData['SyainNO'], $strSQL);
        $strSQL = str_replace("@ITEM_CD", rtrim(substr(str_pad($postData['ITEMCD'], 3, " ", STR_PAD_RIGHT), 0, 3)), $strSQL);
        $strSQL = str_replace("@DATA_KB", rtrim(substr(str_pad($postData['ITEMCD'], 4, " ", STR_PAD_RIGHT), 3, 1)), $strSQL);
        $strSQL = str_replace("@KEIJO_GK", (float) $postData['KEIJO_GK'], $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

        return $strSQL;

    }

    function fncFromTeisyuInsSql($postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "HendoKobetu";
        $UPDCLT = $this->GS_LOGINUSER['strClientNM'];
        $strSQL = "";
        $strSQL .= "INSERT INTO HSTAFFKOUMOKU" . "\r\n";
        $strSQL .= "(      KEIJO_DT" . "\r\n";
        $strSQL .= ",      BUSYO_CD" . "\r\n";
        $strSQL .= ",      SYAIN_NO" . "\r\n";
        $strSQL .= ",      ITEM_CD" . "\r\n";
        $strSQL .= ",      KEIJYO_GK" . "\r\n";
        $strSQL .= ",      DATA_KB" . "\r\n";
        $strSQL .= ",      DISP_NO" . "\r\n";
        $strSQL .= ",      UPD_DATE" . "\r\n";
        $strSQL .= ",      CREATE_DATE" . "\r\n";
        $strSQL .= ",      UPD_SYA_CD" . "\r\n";
        $strSQL .= ",      UPD_PRG_ID" . "\r\n";
        $strSQL .= ",      UPD_CLT_NM" . "\r\n";

        $strSQL .= ") " . "\r\n";
        $strSQL .= "SELECT '@KEIJO_DT'" . "\r\n";
        $strSQL .= ",      HAI.BUSYO_CD" . "\r\n";
        $strSQL .= ",      TEI.SYAIN_NO" . "\r\n";
        $strSQL .= ",      '5'" . "\r\n";
        $strSQL .= ",      TEI.HOYU" . "\r\n";
        $strSQL .= ",      '@DATA_KB'" . "\r\n";
        $strSQL .= ",      ROW_NUMBER() OVER(ORDER BY HAI.BUSYO_CD, TEI.SYAIN_NO)" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      SYSDATE" . "\r\n";
        $strSQL .= ",      '@UPDUSER'" . "\r\n";
        $strSQL .= ",      '@UPDAPP'" . "\r\n";
        $strSQL .= ",      '@UPDCLT'" . "\r\n";
        $strSQL .= "FROM   HTEISYU TEI" . "\r\n";
        $strSQL .= "INNER JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = TEI.SYAIN_NO" . "\r\n";
        $strSQL .= "INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     TEI.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= TO_CHAR(LAST_DAY(TO_DATE('@KJNBI','YYYYMMDD')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999')>= TO_CHAR(LAST_DAY(TO_DATE('@KJNBI','YYYYMMDD')),'YYYYMMDD')" . "\r\n";

        $strSQL .= "WHERE  NVL(SYA.TAISYOKU_DATE,'99999999') >= '@KJNBI'" . "\r\n";
        $strSQL .= "AND    TEI.HOYU IS NOT NULL" . "\r\n";

        $strSQL = str_replace("@KEIJO_DT", str_replace("/", "", $postData['KEIJO_DT']), $strSQL);
        $strSQL = str_replace("@DATA_KB", rtrim(substr(str_pad($postData['DATA_KB'], 4, " ", STR_PAD_RIGHT), 3, 1)), $strSQL);
        $val = str_replace("/", "", $postData['KEIJO_DT']);
        $val = $val . "01";
        $strSQL = str_replace("@KJNBI", $val, $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);
        return $strSQL;
    }

    function fncSyainmstExistSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "SELECT SYA.SYAIN_NO" . "\r\n";
        $strSQL .= ",      HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "FROM   HSYAINMST SYA" . "\r\n";
        $strSQL .= "LEFT JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = HAI.SYAIN_NO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= TO_CHAR(LAST_DAY(TO_DATE('@KJNBI','YYYYMMDD')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999')>= TO_CHAR(LAST_DAY(TO_DATE('@KJNBI','YYYYMMDD')),'YYYYMMDD')" . "\r\n";
        $strSQL .= "WHERE  SYA.SYAIN_NO = '@SYAINNO'" . "\r\n";
        $strSQL .= "AND    NVL(SYOKUSYU_KB,'0') <> '9'" . "\r\n";
        $strSQL .= "AND    NVL(SYA.TAISYOKU_DATE,'99999999') >= '@KJNBI'";

        $val = str_replace("/", "", $postData['KJNBI']);
        $val = $val . "01";
        $strSQL = str_replace("@KJNBI", $val, $strSQL);
        $strSQL = str_replace("@SYAINNO", $postData['SyainNO'], $strSQL);
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
        $strSQL .= "	a.SYAIN_NO" . "\r\n";

        return $strSQL;
    }

    public function fncDataSet()
    {
        $strSql = $this->fncDataSetSql();
        return parent::select($strSql);
    }

    function fncFromHSTAFFSelect($postData, $blnCheck = FALSE)
    {

        $strSql = $this->fncFromHSTAFFSelectSql($postData, $blnCheck);
        return parent::select($strSql);
    }

    public function subComboSet2()
    {
        $strSql = $this->subComboSet2Sql();

        return parent::select($strSql);
    }

    public function subComboSet($postData = NULL)
    {
        $strSql = $this->subComboSetSql($postData);
        return parent::select($strSql);
    }

    public function frmSampleLoadDate()
    {
        $strSql = $this->frmSampleLoadDateSql();
        return parent::select($strSql);
    }

    public function fncExistCheckSel($postData = NULL)
    {
        $strSql = $this->fncExistCheckSelSql($postData);
        return parent::select($strSql);
    }

    public function fncFromTeisyuDel($postData = NULL)
    {
        $strSql = $this->fncFromTeisyuDelSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncFromTeisyuIns($postData = NULL)
    {
        $strSql = $this->fncFromTeisyuInsSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncSyainmstExist($postData = NULL)
    {
        $strSql = $this->fncSyainmstExistSql($postData);
        return parent::select($strSql);
    }

    public function fncDeleteHSTAFFKomoku($postData = NULL)
    {
        $strSql = $this->fncDeleteHSTAFFKomokuSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncInsertHSTAFFKomoku($postData = NULL)
    {
        $strSql = $this->fncInsertHSTAFFKomokuSql($postData);
        return parent::Do_Execute($strSql);
    }

    public function fncDeleteHSTAFFKomokuOnly($postData = NULL)
    {
        $strSql = $this->fncDeleteHSTAFFKomokuSql($postData);
        return parent::delete($strSql);
    }

    public function fncDataSetSyain()
    {
        $strSql = $this->fncDataSetSyainSql();
        return parent::select($strSql);
    }

}