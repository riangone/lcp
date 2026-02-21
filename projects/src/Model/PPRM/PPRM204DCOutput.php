<?php

namespace App\Model\PPRM;

use App\Model\Component\ClsComDb;

class PPRM204DCOutput extends ClsComDb
{
    // '**********************************************************************
    // '処 理 名：店舗コード、日締日時取得
    // '関 数 名：getTenpoCD_HjmDT
    // '引    数：$postData
    // '戻 り 値：SQL
    // '処理説明：店舗コードと日締日時を取得する
    // '**********************************************************************
    public function getTenpoCDHjmDT($postData)
    {
        $sql = $this->getTenpoCDHjmDTSql($postData);
        return parent::select($sql);
    }

    public function getTenpoCDHjmDTSql($postData)
    {
        $sql = "";
        $sql .= "SELECT TO_CHAR(HJM.HJM_SYR_DTM,'YYYY/MM/DD') AS HJM_SYR_DTM" . " \r\n";
        $sql .= ", HJM.TENPO_CD" . " \r\n";
        $sql .= "FROM  M41F11 HJM" . " \r\n";
        $sql .= "WHERE 1=1" . " \r\n";
        $sql .= "AND HJM.TEN_HJM_NO = '@TEN_HJM_NO'" . " \r\n";
        $sql = str_replace("@TEN_HJM_NO", $postData['txtJHjmNO'], $sql);
        return $sql;
    }

}