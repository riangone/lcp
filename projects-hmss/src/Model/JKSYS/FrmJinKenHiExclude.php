<?php
/**
 * 説明：
 *
 *
 * @author caina
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                          FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：FrmJinKenHiExclude
// * 関数名	：FrmJinKenHiExclude
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmJinKenHiExclude extends ClsComDb
{
    // * 処理名	：fncSelectDAT
    // * 関数名	：fncSelectDAT
    // * 処理説明	：人件費集計対象外データ取得
    public function fncSelectDAT()
    {
        $strSql = $this->fncSelectDATSQL();
        return parent::select($strSql);
    }
    public function fncSelectDATSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT " . "\r\n";
        $strSQL .= " JKEX.SYAIN_NO" . "\r\n";
        $strSQL .= ",JKEX.REMARKS" . "\r\n";
        $strSQL .= ",JKEX.CREATE_DATE" . "\r\n";
        $strSQL .= ",JKEX.CRE_SYA_CD" . "\r\n";
        $strSQL .= ",JKEX.UPD_DATE" . "\r\n";
        $strSQL .= ",JKEX.UPD_SYA_CD" . "\r\n";
        $strSQL .= ",JKEX.UPD_CLT_NM" . "\r\n";
        $strSQL .= ",JKS.SYAIN_NM" . "\r\n";
        $strSQL .= " FROM JKJINKENHI_EXCLUDE JKEX" . "\r\n";
        $strSQL .= " LEFT JOIN JKSYAIN JKS" . "\r\n";
        $strSQL .= " ON JKEX.SYAIN_NO = JKS.SYAIN_NO" . "\r\n";
        return $strSQL;
    }
    // * 処理名	：fncGetJKCMST
    // * 関数名	：fncGetJKCMST
    // * 処理説明	：人事コントロールマスタ
    public function fncGetJKCMST()
    {
        $strSql = $this->fncGetJKCMSTSQL();
        return parent::select($strSql);
    }

    public function fncGetJKCMSTSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT SYORI_YM" . "\r\n";
        $strSQL .= " FROM JKCONTROLMST JKC " . "\r\n";
        $strSQL .= " WHERE JKC.ID = '01' " . "\r\n";
        return $strSQL;
    }

    // * 処理名	fncDelJKTFDAT
    // * 関数名	fncDelJKTFDAT
    // * 処理説明	：人件費集計対象外リストデータ(Delete)
    public function fncDelJKTFDAT()
    {
        $strSql = $this->fncDelJKTFDATSQL();
        return parent::delete($strSql);
    }

    public function fncDelJKTFDATSQL()
    {
        $strSQL = "";
        $strSQL = " DELETE FROM JKJINKENHI_EXCLUDE" . "\r\n";

        return $strSQL;
    }

    // * 処理名	：FncInsJKTFDAT
    // * 関数名	：FncInsJKTFDAT
    // * 処理説明	：人件費集計対象外リストデータ(Insert)
    public function fncInsJKTFDAT($strSyainNo, $strBiko)
    {
        $strSql = $this->fncInsJKTFDATSQL($strSyainNo, $strBiko);
        return parent::insert($strSql);
    }

    public function fncInsJKTFDATSQL($strSyainNo, $strBiko)
    {
        $strSQL = "";
        $strSQL = "INSERT INTO JKJINKENHI_EXCLUDE(" . "\r\n";
        $strSQL .= " SYAIN_NO" . "\r\n";
        $strSQL .= ",REMARKS" . "\r\n";
        $strSQL .= ",CREATE_DATE" . "\r\n";
        $strSQL .= ",CRE_SYA_CD" . "\r\n";
        $strSQL .= ",CRE_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_DATE" . "\r\n";
        $strSQL .= ",UPD_SYA_CD" . "\r\n";
        $strSQL .= ",UPD_PRG_ID" . "\r\n";
        $strSQL .= ",UPD_CLT_NM" . "\r\n";
        $strSQL .= ")VALUES(" . "\r\n";
        $strSQL .= " '@SYAIN_NO'" . "\r\n";
        $strSQL .= ",'@REMARKS'" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        $strSQL .= ",'FrmJinKenHiExclude'" . "\r\n";
        $strSQL .= ",SYSDATE" . "\r\n";
        $strSQL .= ",'" . $this->GS_LOGINUSER['strUserID'] . "'" . "\r\n";
        $strSQL .= ",'FrmJinKenHiExclude'" . "\r\n";
        $strSQL .= ",'" . $this->GS_LOGINUSER['strClientNM'] . "')" . "\r\n";
        //条件を設定
        $strSQL = str_replace("@SYAIN_NO", $strSyainNo, $strSQL);
        $strSQL = str_replace("@REMARKS", $strBiko, $strSQL);
        return $strSQL;
    }


    // * 処理名	：fncGetSyainMstValue
    // * 関数名	：fncGetSyainMstValue
    // * 処理説明	：社員署名取得
    public function fncGetSyainMstValue()
    {
        $strSql = $this->fncGetSyainMstValueSQL();
        return parent::select($strSql);
    }

    public function fncGetSyainMstValueSQL()
    {
        $strSQL = "";
        $strSQL = "SELECT SYAIN_NO, SYAIN_NM FROM   JKSYAIN" . "\r\n";
        return $strSQL;
    }
}
