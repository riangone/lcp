<?php
/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
class FrmKanrRank extends ClsComDb
{
    /**
     * 関 数 名：selectsql
     * @param none
     * @return string   SQL
     */
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    /**
     * 関 数 名：select
     * @param none
     * @return array
     */
    public function selectData()
    {
        return parent::select($this->selectsql());
    }

    /**
     * 関 数 名：fncRankingDataSelsql
     * @param string $cboYM 処理年月
     * @return string   SQL
     */
    public function fncRankingDataSelsql($cboYM)
    {
        $strSQL = "";
        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= " (SUBSTR(NENGETU,1,4) || '/' || SUBSTR(NENGETU,5,2)) NENGETU" . "\r\n";
        $strSQL .= ",ATHER_JININ" . "\r\n";
        $strSQL .= ",SINSYA_DAISU" . "\r\n";
        $strSQL .= ",CHUKO_DAISU" . "\r\n";
        $strSQL .= ",SEIBI_JININ" . "\r\n";
        $strSQL .= ",TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH24:MI:SS') CRE_DT" . "\r\n";
        $strSQL .= "FROM HRANKINGINPUTDATA" . "\r\n";
        $strSQL .= "WHERE NENGETU = '@NENGETU'" . "\r\n";
        $strSQL .= "ORDER BY NENGETU DESC" . "\r\n";

        $strSQL = str_replace("@NENGETU", $cboYM, $strSQL);

        return $strSQL;
    }

    /**
     * 関 数 名：fncRankingDataSel
     * @param string $cboYM 処理年月
     * @return array
     */
    public function fncRankingDataSel($cboYM)
    {
        return parent::select($this->fncRankingDataSelsql($cboYM));
    }

}
