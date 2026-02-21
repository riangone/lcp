<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE200PresentOrderBase extends ClsComDb
{
    //取得データをグリッドビューにバインドする
    public function CreateDataSource($STARTDT)
    {
        $strSQL = "";
        $strSQL .= " SELECT ORDER_NO" . "\r\n";
        $strSQL .= " ,      HINMEI " . "\r\n";
        $strSQL .= " ,      TANKA " . "\r\n";
        $strSQL .= " ,      to_char(CREATE_DATE,'yyyy/mm/dd hh24:mi:ss') as CREATE_DATE " . "\r\n";
        $strSQL .= " FROM   HDTPRESENTBASE BASE " . "\r\n";
        $strSQL .= " WHERE  BASE.START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", $STARTDT, $strSQL);

        return parent::select($strSQL);
    }

    //成約プレゼント設定データを削除する
    public function DEL_SQL($STARTDT)
    {
        $strSQL = "";
        $strSQL .= " DELETE FROM HDTPRESENTBASE " . "\r\n";
        $strSQL .= " WHERE  START_DATE = '@STARTDT' " . "\r\n";

        $strSQL = str_replace("@STARTDT", $STARTDT, $strSQL);

        return parent::delete($strSQL);
    }

    //追加処理を行う
    public function INS_SQL($params)
    {
        $strSQL = "";
        $strSQL .= " INSERT INTO HDTPRESENTBASE " . "\r\n";
        $strSQL .= " (START_DATE,              " . "\r\n";
        $strSQL .= "  ORDER_NO,                " . "\r\n";
        $strSQL .= "  HINMEI,              " . "\r\n";
        $strSQL .= "  TANKA,                " . "\r\n";
        $strSQL .= "  UPD_DATE,                " . "\r\n";
        $strSQL .= "  CREATE_DATE,             " . "\r\n";
        $strSQL .= "  UPD_SYA_CD,              " . "\r\n";
        $strSQL .= "  UPD_PRG_ID,              " . "\r\n";
        $strSQL .= "  UPD_CLT_NM )             " . "\r\n";
        $strSQL .= "  VALUES(                  " . "\r\n";
        $strSQL .= "  '@START_DATE',           " . "\r\n";
        $strSQL .= "  '@ORDER_NO',             " . "\r\n";
        $strSQL .= "  '@HINMEI',           " . "\r\n";
        $strSQL .= "  '@TANKA',             " . "\r\n";
        $strSQL .= "  SYSDATE,             " . "\r\n";
        $strSQL .= "  @CREATE_DATE,          " . "\r\n";
        $strSQL .= "  '@UPD_SYA_CD',           " . "\r\n";
        $strSQL .= "  '@UPD_PRG_ID',           " . "\r\n";
        $strSQL .= "  '@UPD_CLT_NM' )          " . "\r\n";

        $strSQL = str_replace("@START_DATE", $params['lblExhibitTermStart'], $strSQL);
        $strSQL = str_replace("@ORDER_NO", $params['ORDER_NO'], $strSQL);
        $strSQL = str_replace("@HINMEI", $params['HINMEI'], $strSQL);
        $strSQL = str_replace("@TANKA", $params['TANKA'], $strSQL);

        if ($params['lblCREATE_DATE'] == "") {
            $strSQL = str_replace("@CREATE_DATE", "SYSDATE", $strSQL);
        } else {
            $strSQL = str_replace("@CREATE_DATE", "to_date('" . $params['lblCREATE_DATE'] . "','yyyy-mm-dd hh24:mi:ss')", $strSQL);
        }
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_PRG_ID", "PresentOrderBase", $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::insert($strSQL);
    }

}
