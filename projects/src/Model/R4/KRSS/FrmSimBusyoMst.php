<?php
/**
 * 説明：
 *
 *
 * @author li
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

class FrmSimBusyoMst extends ClsComDb
{
    public function fncSQL($iKBN, $data = null)
    {
        switch ($iKBN) {
            //基本情報を抽出する
            case 1:
                return $this->fncListSelect();
            //データを削除する
            case 4:
                return $this->frmDelete();
            //データを作成する
            case 5:
                return $this->frmInsert($data);
        }
    }

    public function fncListSelect()
    {
        $strsql = $this->fncListSelectSQL();
        return parent::select($strsql);
    }

    public function frmDelete()
    {
        $strsql = $this->frmDeleteSQL();
        return parent::delete($strsql);
    }

    public function frmInsert($data)
    {
        $strsql = $this->frmInsertSQL($data);
        return parent::Do_Execute($strsql);
    }

    // '**********************************************************************
    // '処 理 名：SpreadリストSQL
    // '関 数 名：fncListSelectSQL
    // '引    数：
    // '戻 り 値：ＳＱＬ文
    // '処理説明：基本情報を抽出する
    // '**********************************************************************
    function fncListSelectSQL()
    {
        $strSQL = "";

        $strSQL .= "SELECT BUS.BUSYO_CD" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      SIM.SALESNUMBER_KB" . "\r\n";
        $strSQL .= ",      SIM.SALES_KB" . "\r\n";
        $strSQL .= ",      SIM.PROFIT_KB" . "\r\n";
        $strSQL .= ",      SIM.CREATE_DATE" . "\r\n";
        $strSQL .= "FROM   HBUSYO BUS" . "\r\n";
        $strSQL .= "       LEFT JOIN HSIMBUSYOMST SIM " . "\r\n";
        $strSQL .= "ON     SIM.BUSYO_CD = BUS.BUSYO_CD " . "\r\n";
        $strSQL .= "ORDER BY BUS.BUSYO_CD";

        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：データを削除する
    // '関 数 名：frmDeleteSQL
    // '引    数：
    // '戻 り 値：ＳＱＬ文
    // '処理説明：データを削除する
    // '**********************************************************************
    function frmDeleteSQL()
    {
        $strSQL = "";

        $strSQL = " DELETE FROM HSIMBUSYOMST ";
        return $strSQL;
    }

    // '**********************************************************************
    // '処 理 名：データを作成する
    // '関 数 名：frmInsertSQL
    // '引    数：{arrayr} $data
    // '戻 り 値：ＳＱＬ文
    // '処理説明：データを作成する
    // '**********************************************************************
    function frmInsertSQL($data)
    {
        $strSQL = "";

        $strSQL .= "INSERT INTO HSIMBUSYOMST " . "\r\n";
        $strSQL .= "       ( " . "\r\n";
        $strSQL .= "       BUSYO_CD " . "\r\n";
        $strSQL .= ",      SALESNUMBER_KB " . "\r\n";
        $strSQL .= ",      SALES_KB " . "\r\n";
        $strSQL .= ",      PROFIT_KB " . "\r\n";
        $strSQL .= ",      UPD_DATE " . "\r\n";
        $strSQL .= ",      CREATE_DATE " . "\r\n";
        $strSQL .= ",      UPD_SYA_CD " . "\r\n";
        $strSQL .= ",      UPD_PRG_ID " . "\r\n";
        $strSQL .= ",      UPD_CLT_NM " . "\r\n";
        $strSQL .= "       ) VALUES ( " . "\r\n";
        $strSQL .= "  '" . $data['BUSYO_CD'] . "'\r\n";
        $strSQL .= ", '" . $data['SALESNUMBER_KB'] . "'\r\n";
        $strSQL .= ", '" . $data['SALES_KB'] . "'\r\n";
        $strSQL .= ", '" . $data['PROFIT_KB'] . "'\r\n";
        $strSQL .= ", SYSDATE " . "\r\n";
        $strSQL .= ", SYSDATE " . "\r\n";
        $strSQL .= ", '" . $data['UPDUSER'] . "'\r\n";
        $strSQL .= ", '" . $data['UPDAPP'] . "'\r\n";
        $strSQL .= ", '" . $data['UPDCLTNM'] . "'\r\n";
        $strSQL .= ") ";
        return $strSQL;
    }
}
