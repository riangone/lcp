<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmHRAKUDataSet extends ClsComDb
{
    public function getData()
    {
        $strsql = $this->getDataSql();
        return parent::select($strsql);
    }
    public function setSelectedData($params)
    {
        $strsql = $this->setSelectedDataSql($params);
        return parent::update($strsql);
    }
    public function getMaxGroupNo()
    {
        $strsql = $this->getMaxGroupNoSql();
        return parent::select($strsql);
    }
    public function insGroupData($params)
    {
        $strsql = $this->insGroupDataSql($params);
        return parent::insert($strsql);
    }
    public function alreadyUpdated($ids)
    {
        $strsql = $this->alreadyUpdatedSql($ids);
        return parent::select($strsql);
    }
    public function getDataSql()
    {
        $strSQL = "";
        $strSQL .= "        SELECT" . "\r\n";
        $strSQL .= "        ID," . "\r\n";
        $strSQL .= "        SEL_FLG," . "\r\n";
        $strSQL .= "        SHIWAKE_NO," . "\r\n";
        $strSQL .= "        to_char(SHIWAKE_CRE_DATE,'YYYY/MM/DD') AS SHIWAKE_CRE_DATE," . "\r\n";
        $strSQL .= "        L_KANJYOU_NM," . "\r\n";
        $strSQL .= "        L_HOJYO_NM," . "\r\n";
        $strSQL .= "        L_FUTAN_BUMON_CD," . "\r\n";
        $strSQL .= "        L_AMOUNT," . "\r\n";
        $strSQL .= "        R_KANJYOU_NM," . "\r\n";
        $strSQL .= "        R_HOJYO_NM," . "\r\n";
        $strSQL .= "        R_FUTAN_BUMON_CD," . "\r\n";
        $strSQL .= "        FREE1_DETAIL" . "\r\n";
        $strSQL .= "      FROM" . "\r\n";
        $strSQL .= "        HRAKU_TBL_CONVERT" . "\r\n";
        $strSQL .= "      WHERE" . "\r\n";
        $strSQL .= "        SEL_FLG=0" . "\r\n";
        $strSQL .= "      ORDER BY ID" . "\r\n";
        return $strSQL;
    }
    public function setSelectedDataSql($params)
    {
        $strSQL = "";
        $strSQL .= "        UPDATE" . "\r\n";
        $strSQL .= "        HRAKU_TBL_CONVERT" . "\r\n";
        $strSQL .= "      SET" . "\r\n";
        $strSQL .= "        SEL_FLG         =1," . "\r\n";
        $strSQL .= "        GROUP_NO        ='@GROUP_NO'," . "\r\n";
        $strSQL .= "        GROUP_NM        ='@GROUP_NM'," . "\r\n";
        $strSQL .= "        KEIRISYORI_DATE = TO_DATE('@KEIRISYORI_DATE','YYYY/MM/DD')" . "\r\n";
        $strSQL .= "      WHERE" . "\r\n";
        $strSQL .= "        ID IN (@idStr)" . "\r\n";
        $strSQL = str_replace("@GROUP_NO", $params['no'], $strSQL);
        $strSQL = str_replace("@GROUP_NM", $params['grNm'], $strSQL);
        $strSQL = str_replace("@KEIRISYORI_DATE", $params['keiriDt'], $strSQL);
        $strSQL = str_replace("@idStr", $params['idStr'], $strSQL);
        return $strSQL;
    }
    public function getMaxGroupNoSql()
    {
        $strSQL = " SELECT " . "\r\n";
        $strSQL .= "    NVL(LPAD(MAX(TO_NUMBER(GROUP_NO))+1,5,'0'), '00001') AS MAX_CD " . "\r\n";
        $strSQL .= " FROM " . "\r\n";
        $strSQL .= " HRAKU_TBL_GROUP" . "\r\n";
        return $strSQL;
    }
    public function insGroupDataSql($params)
    {
        $strSQL = " INSERT INTO " . "\r\n";
        $strSQL .= "    HRAKU_TBL_GROUP(GROUP_NO,GROUP_NM,SEL_CNT,KEIRI_DATE) " . "\r\n";
        $strSQL .= " VALUES " . "\r\n";
        $strSQL .= "    ('@GROUP_NO','@GROUP_NM',@SEL_CNT,TO_DATE('@KEIRI_DATE','YYYY/MM/DD'))" . "\r\n";
        $strSQL = str_replace("@GROUP_NO", $params['no'], $strSQL);
        $strSQL = str_replace("@GROUP_NM", $params['grNm'], $strSQL);
        $strSQL = str_replace("@SEL_CNT", $params['count'], $strSQL);
        $strSQL = str_replace("@KEIRI_DATE", $params['keiriDt'], $strSQL);
        return $strSQL;
    }
    public function alreadyUpdatedSql($ids)
    {
        $strSQL = " SELECT ID FROM  HRAKU_TBL_CONVERT WHERE SEL_FLG<>0 AND ID IN(@idStr)" . "\r\n";
        $strSQL = str_replace("@idStr", $ids, $strSQL);
        return $strSQL;
    }
}