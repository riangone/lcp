<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmChumonCSV extends ClsComDb
{
    //コントロールマスタ存在ﾁｪｯｸ
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

    public function fncSelect()
    {
        return parent::select($this->selectsql());
    }

    //**********************************************************************
    //処 理 名：チェックリストに出力するためのSQL
    //関 数 名：fncUriageChkListSql
    //引    数：
    //戻 り 値：SQL文
    //処理説明：売上データ作成で対象外になる売上データをチェックリストに出力するためのSQL
    //**********************************************************************
    public function fncUriageChkListSql($cboUCNO, $cboDateFrom, $cboDateTo)
    {
        $strSQL = "";
        $strSQL .= "SELECT ROW_NUMBER() OVER(ORDER BY CMN.CMN_NO) SEQNO" . "\r\n";
        $strSQL .= ",      SUBSTR('@SYORINENGETU',1,4) || '年' || SUBSTR('@SYORINENGETU',5,2) || '月' TAISYO_NEN" . "\r\n";
        $strSQL .= ",      '(' || '@KOUSINFROM' || '～' || '@KOUSINTO' || ')' KOUSIN_HANI" . "\r\n";
        $strSQL .= ",      CMN.CMN_NO" . "\r\n";
        $strSQL .= ",      CMN.UC_NO" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(CMN.JKN_HKD,'YYYY/MM/DD'),'YYYY/MM/DD') JHN_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(CMN.CEL_DT,'YYYY/MM/DD'),'YYYY/MM/DD') CEL_DT" . "\r\n";
        $strSQL .= ",      TO_CHAR(REC_UPD_DT,'YYYY/MM/DD') UPD_DT" . "\r\n";
        $strSQL .= ",      REC_UPD_CLT_NM" . "\r\n";
        $strSQL .= ",      REC_CRE_SYA_CD" . "\r\n";
        $strSQL .= "FROM   M41E10 CMN" . "\r\n";
        $strSQL .= "INNER JOIN WK_CMNNO W_C" . "\r\n";
        $strSQL .= "ON     W_C.CMN_NO = CMN.CMN_NO" . "\r\n";
        $strSQL .= "WHERE  SUBSTR(CMN.UC_NO,1,6) < '@SYORINENGETU'" . "\r\n";
        $strSQL .= "AND    (NVL(SUBSTR(CMN.JKN_HKD,1,6),' ') = ' ' " . "\r\n";
        $strSQL .= "        OR SUBSTR(CMN.JKN_HKD,1,6) < '@SYORINENGETU')" . "\r\n";
        $strSQL .= "AND    (NVL(SUBSTR(CMN.CEL_DT,1,6),' ') = ' '" . "\r\n";
        $strSQL .= "        OR SUBSTR(CMN.CEL_DT,1,6) < '@SYORINENGETU')" . "\r\n";
        $strSQL .= "AND    (TO_CHAR(W_C.GET_DATE,'YYYYMMDD') IS NULL" . "\r\n";
        $strSQL .= "        OR SUBSTR(TO_CHAR(W_C.GET_DATE,'YYYYMMDD'),1,6) < '@SYORINENGETU')" . "\r\n";
        $strSQL .= "AND     REC_UPD_CLT_NM<>'MZS892'" . "\r\n";

        $strSQL = str_replace("@SYORINENGETU", $cboUCNO, $strSQL);
        $strSQL = str_replace("@KOUSINFROM", $cboDateFrom, $strSQL);
        $strSQL = str_replace("@KOUSINTO", $cboDateTo, $strSQL);
        return $strSQL;
    }

    public function fncUriageChkList($cboUCNO, $cboDateFrom, $cboDateTo): array
    {
        return parent::select($this->fncUriageChkListSql($cboUCNO, $cboDateFrom, $cboDateTo));
    }

}
