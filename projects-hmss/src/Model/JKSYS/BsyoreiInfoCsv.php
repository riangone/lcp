<?php
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：BsyoreiInfoCsv
// * 関数名	：BsyoreiInfoCsv
// * 処理説明	：共通クラスの読込み
//*************************************
class BsyoreiInfoCsv extends ClsComDb
{
    //業績奨励手当支給データ
    public function fncGetGYOSEKISYOUREI($strTaisyou_YM)
    {
        $strSQL = "";
        $strSQL .= " SELECT JKG.SYAIN_NO" . "\r\n";
        $strSQL .= ",JKG.SYAIN_NM" . "\r\n";
        $strSQL .= ",JKG.SYOREI_TEATE" . "\r\n";
        $strSQL .= " FROM JKGYOSEKISYOREI JKG" . "\r\n";
        $strSQL .= " WHERE JKG.SIKYU_YM = '@TAISYOU_YM'" . "\r\n";
        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $strTaisyou_YM, $strSQL);

        return parent::select($strSQL);
    }

    //店長奨励手当社員別支給データ
    public function fncGetTENCHOSYOUREI($strTaisyou_YM)
    {
        $strSQL = "";
        $strSQL .= " SELECT JKT.SYAIN_NO" . "\r\n";
        $strSQL .= ",JKT.SYAIN_NM" . "\r\n";
        $strSQL .= ",JKT.SYOREI_TEATE" . "\r\n";
        $strSQL .= " FROM JKTENCHOSYOREISYAIN JKT " . "\r\n";
        $strSQL .= " WHERE JKT.SIKYU_YM = '@TAISYOU_YM' " . "\r\n";
        //条件を設定
        $strSQL = str_replace("@TAISYOU_YM", $strTaisyou_YM, $strSQL);

        return parent::select($strSQL);
    }

}
