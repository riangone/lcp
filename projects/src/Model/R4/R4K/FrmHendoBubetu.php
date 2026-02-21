<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmHendoBubetu extends ClsComDb
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

        $strSQL .= " WHERE  MEISYOU_ID = '30'";

        return $strSQL;

    }

    function fncFromHSTAFFSelectSql($postData, $blnCheck)
    {
        $strSQL = "";
        $strSQL .= "SELECT (SUBSTR(STF.KEIJO_DT,1,4) || '/' || SUBSTR(STF.KEIJO_DT,5,2) || '/01') KEIJO_DT" . "\r\n";
        $strSQL .= ",      (RPAD(STF.ITEM_CD,3) || STF.DATA_KB) ITEMNO" . "\r\n";
        $strSQL .= ",      STF.BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      NVL(STF.KEIJYO_GK,0) KEIJYO_GK" . "\r\n";
        $strSQL .= "      FROM   HSTAFFKOUMOKU STF" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = STF.BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = STF.SYAIN_NO" . "\r\n";
        $strSQL .= "WHERE  STF.KEIJO_DT = '@KEIJOBI'" . "\r\n";
        $strSQL .= "AND    STF.ITEM_CD = '@ITEMCD'" . "\r\n";
        $strSQL .= "AND    STF.SYAIN_NO = '00000'" . "\r\n";

        if ($blnCheck) {
            $strSQL .= "AND   STF.BUSYO_CD = '@BUSYOCD'" . "\r\n";
        }

        $strSQL .= "ORDER BY STF.BUSYO_CD" . "\r\n";
        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@ITEMCD", rtrim(substr(str_pad($postData['ItemNO'], 3, " ", STR_PAD_RIGHT), 0, 3)), $strSQL);
        $strSQL = str_replace("@BUSYOCD", $postData['strBusyoCD'], $strSQL);

        return $strSQL;
    }

    function fncDeleteHSTAFFKomokuSql($postData = NULL)
    {

        $strSQL = "";
        $strSQL .= "DELETE FROM HSTAFFKOUMOKU" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJOBI'" . "\r\n";
        $strSQL .= "AND    BUSYO_CD = '@BUSYOCD'" . "\r\n";
        $strSQL .= "AND    SYAIN_NO = '@SYAINNO'" . "\r\n";
        $strSQL .= "AND    ITEM_CD = '@ITEMCD'" . "\r\n";

        $strSQL = str_replace("@KEIJOBI", str_replace("/", "", $postData['KEIJOBI']), $strSQL);
        $strSQL = str_replace("@BUSYOCD", rtrim($postData['strBusyoCD']), $strSQL);
        $strSQL = str_replace("@SYAINNO", '00000', $strSQL);
        $strSQL = str_replace("@ITEMCD", rtrim(substr(str_pad($postData['ITEMCD'], 3, " ", STR_PAD_RIGHT), 0, 3)), $strSQL);
        return $strSQL;
    }

    function fncInsertHSTAFFKomokuSql($postData = NULL)
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDAPP = "HendoBubetu";
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
        $strSQL = str_replace("@BUSYO_CD", rtrim($postData['strBusyoCD']), $strSQL);
        $strSQL = str_replace("@SYAIN_NO", '00000', $strSQL);
        $strSQL = str_replace("@ITEM_CD", rtrim(substr(str_pad($postData['ITEMCD'], 3, " ", STR_PAD_RIGHT), 0, 3)), $strSQL);
        $strSQL = str_replace("@DATA_KB", rtrim(substr(str_pad($postData['ITEMCD'], 4, " ", STR_PAD_RIGHT), 3, 1)), $strSQL);
        $strSQL = str_replace("@KEIJO_GK", (float) $postData['KEIJO_GK'], $strSQL);
        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLT, $strSQL);

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


    public function frmSampleLoadDate()
    {
        $strSql = $this->frmSampleLoadDateSql();
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

}
