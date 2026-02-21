<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmSCUriageList extends ClsComDb
{
    public function FncSelectHscUrisql($postData, $start, $limit)
    {
        $strWhere = " WHERE ";
        $strSQL = "";
        $strSQL .= "SELECT URI.CMN_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      BUS.BUSYO_NM" . "\r\n";
        $strSQL .= ",      (SYA.SYAIN_KNJ_SEI || ' ' || SYA.SYAIN_KNJ_MEI) SYAINMEI" . "\r\n";
        $strSQL .= ",      (URI.KYK_MEI_KNJ1 || ' ' || URI.KYK_MEI_KNJ2) KEIYAUMEI" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(URI.URG_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') URG_DATE" . "\r\n";
        $strSQL .= ",      TO_CHAR(TO_DATE(URI.JKN_HKD,'YYYY/MM/DD'),'YYYY/MM/DD') JKN_HKD" . "\r\n";
        $strSQL .= ",      URI.NAU_KB" . "\r\n";
        $strSQL .= ",      TO_DATE(URI.CEL_DATE,'YYYY/MM/DD') CEL_DATE" . "\r\n";
        $strSQL .= ",      DECODE(URI.NAU_KB,'1','新車','中古') NAU_KB_NM" . "\r\n";
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        BUS.BUSYO_CD = URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN M29MA4 SYA" . "\r\n";
        $strSQL .= "ON        SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";

        //** 注文書番号
        if ($postData['txtCMNNO'] != "") {
            $strSQL .= $strWhere . " URI.CMN_NO  LIKE '@CMNNO%'" . "\r\n";
            $strWhere = " AND ";
        }
        //** UCNO
        if ($postData['txtUCNO'] != "") {
            $strSQL .= $strWhere . " URI.UC_NO LIKE '@UCNO%'" . "\r\n";
            $strWhere = " AND ";
        }
        //** カナ
        if ($postData['txtKana'] != "") {
            $strSQL .= $strWhere . " URI.KYK_MEI_KN LIKE '@KANA%'" . "\r\n";
            $strWhere = " AND ";
        }
        //** 登録NO下4桁
        if ($postData['txtTourokuNO'] != "") {
            $strSQL .= $strWhere . " URI.TOURK_NO3 = '@TOURKNO'" . "\r\n";
            $strWhere = " AND ";
        }
        //** 部署コード
        if ($postData['txtBusyoCD'] != "") {
            $strSQL .= $strWhere . "URI.URI_BUSYO_CD = '@BUSYOCD'" . "\r\n";
            $strWhere = " AND ";
        }
        //** 社員番号
        if ($postData['txtEmpNO'] != "") {
            $strSQL .= $strWhere . "URI.URI_TANNO = '@SYAINNO'" . "\r\n";
            $strWhere = " AND ";
        }
        //CarNO
        if ($postData['txtCarNO'] != "") {
            $strSQL .= $strWhere . "URI.CARNO LIKE '%@CARNO%'" . "\r\n";
            $strWhere = " AND ";
        }
        $strSQL .= "ORDER BY URI.UC_NO" . "\r\n";
        $cell = "*";
        if (trim($start) != "") {
            $start = " WHERE RNM >" . $start;
        }
        if (trim($limit) != "") {
            $limit = " WHERE ROWNUM<=" . $limit;
        }
        $strSQL = "SELECT " . $cell . " FROM (SELECT TBL." . $cell . ",ROWNUM RNM FROM ( " . $strSQL . ") TBL " . $limit . ") " . $start;

        $strSQL = str_replace("@CMNNO", $postData['txtCMNNO'], $strSQL);
        $strSQL = str_replace("@UCNO", $postData['txtUCNO'], $strSQL);
        $strSQL = str_replace("@KANA", $postData['txtKana'], $strSQL);
        $strSQL = str_replace("@TOURKNO", $postData['txtTourokuNO'], $strSQL);
        $strSQL = str_replace("@BUSYOCD", $postData['txtBusyoCD'], $strSQL);
        $strSQL = str_replace("@SYAINNO", $postData['txtEmpNO'], $strSQL);
        $strSQL = str_replace("@CARNO", $postData['txtCarNO'], $strSQL);

        return $strSQL;
    }

    public function FncSelectHscUriCountsql($postData)
    {
        $strWhere = " WHERE ";
        $strSQL = "";
        $strSQL .= "SELECT " . "\r\n";
        $strSQL .= "count(*) as cnt" . "\r\n";
        ;
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON        BUS.BUSYO_CD = URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "LEFT JOIN M29MA4 SYA" . "\r\n";
        $strSQL .= "ON        SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";

        //** 注文書番号
        if ($postData['txtCMNNO'] != "") {
            $strSQL .= $strWhere . " URI.CMN_NO  LIKE '@CMNNO%'" . "\r\n";
            $strWhere = " AND ";
        }
        //** UCNO
        if ($postData['txtUCNO'] != "") {
            $strSQL .= $strWhere . " URI.UC_NO LIKE '@UCNO%'" . "\r\n";
            $strWhere = " AND ";
        }
        //** カナ
        if ($postData['txtKana'] != "") {
            $strSQL .= $strWhere . " URI.KYK_MEI_KN LIKE '@KANA%'" . "\r\n";
            $strWhere = " AND ";
        }
        //** 登録NO下4桁
        if ($postData['txtTourokuNO'] != "") {
            $strSQL .= $strWhere . " URI.TOURK_NO3 = '@TOURKNO'" . "\r\n";
            $strWhere = " AND ";
        }
        //** 部署コード
        if ($postData['txtBusyoCD'] != "") {
            $strSQL .= $strWhere . "URI.URI_BUSYO_CD = '@BUSYOCD'" . "\r\n";
            $strWhere = " AND ";
        }
        //** 社員番号
        if ($postData['txtEmpNO'] != "") {
            $strSQL .= $strWhere . "URI.URI_TANNO = '@SYAINNO'" . "\r\n";
            $strWhere = " AND ";
        }
        //CarNO
        if ($postData['txtCarNO'] != "") {
            $strSQL .= $strWhere . "URI.CARNO LIKE '%@CARNO%'" . "\r\n";
            $strWhere = " AND ";
        }
        $strSQL .= "ORDER BY URI.UC_NO" . "\r\n";

        $strSQL = str_replace("@CMNNO", $postData['txtCMNNO'], $strSQL);
        $strSQL = str_replace("@UCNO", $postData['txtUCNO'], $strSQL);
        $strSQL = str_replace("@KANA", $postData['txtKana'], $strSQL);
        $strSQL = str_replace("@TOURKNO", $postData['txtTourokuNO'], $strSQL);
        $strSQL = str_replace("@BUSYOCD", $postData['txtBusyoCD'], $strSQL);
        $strSQL = str_replace("@SYAINNO", $postData['txtEmpNO'], $strSQL);
        $strSQL = str_replace("@CARNO", $postData['txtCarNO'], $strSQL);
        return $strSQL;
    }

    public function FncSelectHscUri($postData, $start, $limit)
    {
        if ($start == "" && $limit == "") {

            return parent::select($this->FncSelectHscUriCountsql($postData));
        } else {
            return parent::select($this->FncSelectHscUrisql($postData, $start, $limit));
        }
    }

}