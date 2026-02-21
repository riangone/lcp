<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmBusyoMst extends ClsComDb
{
    //--execute sql--
    public function fncSearchBusyo($postData)
    {
        $strSql = $this->fncSearchBusyo_sql($postData);
        return parent::select($strSql);
    }

    public function fncDeleteBusyo($busyoCd)
    {
        $strSql = $this->fncDeleteBusyo_sql($busyoCd);
        return parent::delete($strSql);
    }

    //--sql--
    public function fncSearchBusyo_sql($postData)
    {
        $strWhere = " WHERE ";
        $sqlstr = "";
        $sqlstr .= "SELECT	BUSYO_CD";
        $sqlstr .= ",	    BUSYO_NM";
        $sqlstr .= ",	    BUSYO_KANANM";
        $sqlstr .= ",	    BUSYO_RYKNM";
        $sqlstr .= ",	    KKR_BUSYO_CD";
        $sqlstr .= ",	    CNV_BUSYO_CD";
        $sqlstr .= ",	    SYUKEI_KB";
        $sqlstr .= ",	    MANEGER_CD";
        $sqlstr .= ",	    START_DATE";
        $sqlstr .= ",	    END_DATE";
        $sqlstr .= ",	    DSP_SEQNO";
        $sqlstr .= ",	    PRN_KB1";
        $sqlstr .= ",	    PRN_KB2";
        $sqlstr .= ",	    PRN_KB3";
        $sqlstr .= ",	    PRN_KB4";
        $sqlstr .= ",	    PRN_KB5";
        $sqlstr .= ",	    PRN_KB6";
        $sqlstr .= ",        HKNSYT_DSP_KB";
        $sqlstr .= ",        TORIKOMI_BUSYO_KB ";
        $sqlstr .= "FROM    HBUSYO ";
        if ($postData['busyoCD'] != "") {
            $sqlstr .= $strWhere . " BUSYO_CD LIKE '" . $postData['busyoCD'] . "%' ";
            $strWhere = " AND ";
        }
        if ($postData['busyoKN'] != "") {
            $sqlstr .= $strWhere . " BUSYO_KANANM LIKE '" . $postData['busyoKN'] . "%' ";
        }
        $sqlstr .= " ORDER BY BUSYO_CD ";
        return $sqlstr;

    }

    public function fncDeleteBusyo_sql($busyoCd)
    {
        $sqlstr = "";
        $sqlstr .= "DELETE FROM HBUSYO ";
        $sqlstr .= "WHERE  BUSYO_CD = '" . $busyoCd . "'";
        return $sqlstr;
    }

}