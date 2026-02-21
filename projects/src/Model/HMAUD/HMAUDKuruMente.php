<?php
// 共通クラスの読込み
namespace App\Model\HMAUD;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMAUDKuruMente extends ClsComDb
{
    public function getCours()
    {
        return parent::select($this->getCoursSQL());
    }

    public function courDel()
    {
        return parent::delete($this->courDelSQL());
    }

    public function insertData($param)
    {
        return parent::insert($this->insertDataSQL($param));
    }

    public function getCoursSQL()
    {
        $strSql = "";
        $strSql .= " SELECT COURA.COURS, " . "\r\n";
        $strSql .= "   TO_CHAR(COURA.START_DT, 'YYYY/MM/DD')    AS START_DT,    " . "\r\n";
        $strSql .= "   TO_CHAR(COURA.END_DT, 'YYYY/MM/DD')      AS END_DT    " . "\r\n";
        $strSql .= " FROM HMAUD_MST_COUR COURA " . "\r\n";
        $strSql .= " ORDER BY COURA.COURS " . "\r\n";

        return $strSql;
    }

    public function courDelSQL()
    {
        $strSql = "";
        $strSql .= " DELETE FROM HMAUD_MST_COUR " . "\r\n";

        return $strSql;
    }

    public function insertDataSQL($param)
    {
        $strSql = "";
        $strSql .= " INSERT " . "\r\n";
        $strSql .= " INTO HMAUD_MST_COUR VALUES " . "\r\n";
        $strSql .= "   ( " . "\r\n";
        $strSql .= "     " . $param['COURS'] . ", " . "\r\n";
        $strSql .= "     TO_DATE('" . $param['START_DT'] . "', 'YYYY/MM/DD'), " . "\r\n";
        $strSql .= "     TO_DATE('" . $param['END_DT'] . " 23:59:59', 'YYYY/MM/DD HH24:MI:SS'), " . "\r\n";
        $strSql .= "     SYSDATE, " . "\r\n";
        $strSql .= "     '" . $this->GS_LOGINUSER['strUserID'] . "', " . "\r\n";
        $strSql .= "     SYSDATE, " . "\r\n";
        $strSql .= "     '" . $this->GS_LOGINUSER['strUserID'] . "' " . "\r\n";
        $strSql .= "   ) " . "\r\n";

        return $strSql;
    }

}