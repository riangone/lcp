<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDKyotenMenteSetting extends ClsComDb
{
    function getdataSQL($postdata)
    {
        $strSql = "";
        $strSql .= " SELECT HMK.RESPONSIBLE_EIGYO ";
        $strSql .= " ,      HMK.RESPONSIBLE_TERRITORY ";
        $strSql .= " ,      HMK.KEY_PERSON ";
        $strSql .= " FROM   HMAUD_MST_KTN HMK ";
        $strSql .= " WHERE  HMK.KYOTEN_CD = '@KYOTEN_CD' ";
        $strSql .= " AND    HMK.TERRITORY = '@TERRITORY' ";
        $strSql = str_replace("@KYOTEN_CD", $postdata['kyoten_cd'], $strSql);
        $strSql = str_replace("@TERRITORY", $postdata['territory'], $strSql);

        return $strSql;
    }

    public function FncGetSyainMstValueSQL()
    {
        $strSql = "";
        $strSql .= "SELECT SYAIN_NM" . "\r\n";
        $strSql .= ",      SYAIN_NO" . "\r\n";
        $strSql .= "FROM   HSYAINMST" . "\r\n";

        return $strSql;
    }

    function MST_KTNUpdSQL($postdata)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HMAUD_MST_KTN " . "\r\n";
        $strSQL .= " SET RESPONSIBLE_EIGYO = '@RESPONSIBLE_EIGYO' " . "\r\n";
        $strSQL .= " , RESPONSIBLE_TERRITORY = '@RESPONSIBLE_TERRITORY'" . "\r\n";
        $strSQL .= " , KEY_PERSON = '@KEY_PERSON'" . "\r\n";
        $strSQL .= " , UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= " , UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " WHERE  KYOTEN_CD = '@KYOTEN_CD' ";
        $strSQL .= " AND    TERRITORY = '@TERRITORY' ";
        $strSQL = str_replace("@KYOTEN_CD", $postdata['kyoten_cd'], $strSQL);
        $strSQL = str_replace("@TERRITORY", $postdata['territory'], $strSQL);
        $strSQL = str_replace("@RESPONSIBLE_EIGYO", $postdata['kyoten_userid'], $strSQL);
        $strSQL = str_replace("@RESPONSIBLE_TERRITORY", $postdata['responsible_userid'], $strSQL);
        $strSQL = str_replace("@KEY_PERSON", $postdata['keyperson_userid'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        return $strSQL;
    }

    public function MST_KTNUpd($postdata)
    {
        return parent::update($this->MST_KTNUpdSQL($postdata));
    }

    public function getdata($postdata)
    {
        return parent::select($this->getdataSQL($postdata));
    }

    public function FncGetSyainMstValue()
    {
        return parent::select($this->FncGetSyainMstValueSQL());
    }

}
