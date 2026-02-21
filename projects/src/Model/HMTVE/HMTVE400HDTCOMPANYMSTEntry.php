<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE400HDTCOMPANYMSTEntry extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //窓口会社データのＳＱＬ文を取得
    function dataGetSQL($COMPANY_CD, $COMPANY_NM)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT	COMPANY_CD,";
        $strSql = $strSql . " COMPANY_NM,";
        $strSql = $strSql . " CREATE_DATE";
        $strSql = $strSql . " FROM HDTCOMPANYMST";
        $strSql = $strSql . " WHERE 1=1";
        if ($COMPANY_CD != null && $COMPANY_CD != "") {
            $strSql = $strSql . " AND	COMPANY_CD LIKE '@COMPANY_CD%'";
        }
        if ($COMPANY_NM != null && $COMPANY_NM != "") {
            $strSql = $strSql . " AND	COMPANY_NM LIKE '@COMPANY_NM%'";
        }
        $strSql = $strSql . " ORDER BY COMPANY_CD";
        $strSql = str_replace("@COMPANY_CD", $COMPANY_CD, $strSql);
        $strSql = str_replace("@COMPANY_NM", $COMPANY_NM, $strSql);

        return $strSql;
    }

    //窓口会社データにデータが存在するかを取得する
    function checkSQL($COMPANY_CD)
    {
        $strSql = "";
        $strSql = $strSql . "SELECT COMPANY_CD" . "\r\n";
        $strSql = $strSql . "FROM   HDTCOMPANYMST" . "\r\n";
        $strSql = $strSql . "WHERE  COMPANY_CD = '@COMPANY_CD'" . "\r\n";
        $strSql = str_replace("@COMPANY_CD", $COMPANY_CD, $strSql);

        return $strSql;
    }

    //窓口会社データの更新ＳＱＬ文を取得する
    function insertSQL($COMPANY_CD, $COMPANY_NM)
    {
        $strSql = "";
        $strSql = $strSql . "INSERT INTO HDTCOMPANYMST(" . "\r\n";
        $strSql = $strSql . "COMPANY_CD," . "\r\n";
        $strSql = $strSql . "COMPANY_NM," . "\r\n";
        $strSql = $strSql . "UPD_DATE," . "\r\n";
        $strSql = $strSql . "CREATE_DATE," . "\r\n";
        $strSql = $strSql . "UPD_SYA_CD," . "\r\n";
        $strSql = $strSql . "UPD_PRG_ID," . "\r\n";
        $strSql = $strSql . "UPD_CLT_NM)" . "\r\n";
        $strSql = $strSql . "VALUES(" . "\r\n";
        $strSql = $strSql . "'@COMPANY_CD'," . "\r\n";
        $strSql = $strSql . "'@COMPANY_NM'," . "\r\n";
        $strSql = $strSql . "SYSDATE," . "\r\n";
        $strSql = $strSql . "SYSDATE," . "\r\n";
        $strSql = $strSql . "'@LoginID'," . "\r\n";
        $strSql = $strSql . "'HDTCOMPANYMstEntry'," . "\r\n";
        $strSql = $strSql . "'@MachineNM')" . "\r\n";
        $strSql = str_replace("@COMPANY_CD", $COMPANY_CD, $strSql);
        $strSql = str_replace("@COMPANY_NM", $COMPANY_NM, $strSql);
        $strSql = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSql);

        return $strSql;
    }

    //窓口会社データの更新ＳＱＬ文を取得する
    function upDateSQL($COMPANY_CD, $COMPANY_NM)
    {
        $strSql = "";
        $strSql = $strSql . "UPDATE HDTCOMPANYMST SET " . "\r\n";
        $strSql = $strSql . "COMPANY_NM = '@COMPANY_NM'," . "\r\n";
        $strSql = $strSql . "UPD_DATE = SYSDATE," . "\r\n";
        $strSql = $strSql . "UPD_SYA_CD = '@LoginID'," . "\r\n";
        $strSql = $strSql . "UPD_PRG_ID = 'HDTCOMPANYMstEntry'," . "\r\n";
        $strSql = $strSql . "UPD_CLT_NM = '@MachineNM'" . "\r\n";
        $strSql = $strSql . "WHERE COMPANY_CD = '@COMPANY_CD'" . "\r\n";

        $strSql = str_replace("@COMPANY_NM", $COMPANY_NM, $strSql);
        $strSql = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSql);
        $strSql = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSql);
        $strSql = str_replace("@COMPANY_CD", $COMPANY_CD, $strSql);

        return $strSql;
    }

    //窓口会社データの削除ＳＱＬ文を取得する
    function deleteSQL($COMPANY_CD)
    {
        $strSql = "";
        $strSql = $strSql . "DELETE FROM HDTCOMPANYMST" . "\r\n";
        $strSql = $strSql . "WHERE COMPANY_CD = '@COMPANY_CD'" . "\r\n";

        $strSql = str_replace("@COMPANY_CD", $COMPANY_CD, $strSql);

        return $strSql;
    }

    //窓口会社データ取得
    public function dataGet($COMPANY_CD, $COMPANY_NM)
    {
        return parent::select($this->dataGetSQL($COMPANY_CD, $COMPANY_NM));
    }

    //窓口会社データにデータが存在するかを取得する
    public function check($COMPANY_CD)
    {
        return parent::select($this->checkSQL($COMPANY_CD));
    }

    //窓口会社データの更新
    public function insert_data($COMPANY_CD, $COMPANY_NM)
    {
        return parent::insert($this->insertSQL($COMPANY_CD, $COMPANY_NM));
    }

    //窓口会社データの更新
    public function upDate_data($COMPANY_CD, $COMPANY_NM)
    {
        return parent::update($this->upDateSQL($COMPANY_CD, $COMPANY_NM));
    }

    //窓口会社データの削除
    public function delete($COMPANY_CD)
    {
        return parent::delete($this->deleteSQL($COMPANY_CD));
    }

}
