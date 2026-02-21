<?php

namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmHRAKUDataInsert extends ClsComDb
{
    public function fncInsRAKUDataSQL($postData, $filename, $systemTime)
    {
        $strSQL = "";

        $strSQL .= " INSERT INTO HRAKU_TBL_CONVERT(" . "\r\n";
        $strSQL .= "    SHIWAKE_NO " . "\r\n";
        $strSQL .= "	,	SHIWAKE_KBN	" . "\r\n";
        $strSQL .= "	,	SHIWAKE_CRE_DATE	" . "\r\n";
        $strSQL .= "	,	SHIWAKE_CRE_TIME	" . "\r\n";
        $strSQL .= "	,	BIKO1	" . "\r\n";
        $strSQL .= "	,	BUSI_REGIST_NUM	" . "\r\n";
        $strSQL .= "	,	SHIWAKE_DATE	" . "\r\n";
        $strSQL .= "	,	L_KANJYOU_CD	" . "\r\n";
        $strSQL .= "	,	L_KANJYOU_NM	" . "\r\n";
        $strSQL .= "	,	L_KANJYOU_KAIKEI	" . "\r\n";
        $strSQL .= "	,	L_HOJYO_CD	" . "\r\n";
        $strSQL .= "	,	L_HOJYO_NM	" . "\r\n";
        $strSQL .= "	,	L_HOJYO_KAIKEI	" . "\r\n";
        $strSQL .= "	,	L_HOJYO_KAIKEI_HOJYO1	" . "\r\n";
        $strSQL .= "	,	L_HOJYO_KAIKEI_HOJYO2	" . "\r\n";
        $strSQL .= "	,	L_HOJYO_KAIKEI_HOJYO3	" . "\r\n";
        $strSQL .= "	,	L_HOJYO_KAIKEI_HOJYO4	" . "\r\n";
        $strSQL .= "	,	L_FUTAN_BUMON_CD	" . "\r\n";
        $strSQL .= "	,	L_FUTAN_BUMON_NM	" . "\r\n";
        $strSQL .= "	,	L_FUTAN_BUMON_KAIKEI	" . "\r\n";
        $strSQL .= "	,	L_TAX_KBN_CD	" . "\r\n";
        $strSQL .= "	,	L_TAX_KBN_NM	" . "\r\n";
        $strSQL .= "	,	L_TAX_KBN_KAIKEI	" . "\r\n";
        $strSQL .= "	,	L_TAX_CALC_KBN	" . "\r\n";
        $strSQL .= "	,	L_TAX	" . "\r\n";
        $strSQL .= "	,	L_ODD	" . "\r\n";
        $strSQL .= "	,	L_PRO_CD	" . "\r\n";
        $strSQL .= "	,	L_PRO_NM	" . "\r\n";
        $strSQL .= "	,	L_PRO_KAIKEI	" . "\r\n";
        $strSQL .= "	,	L_AMOUNT	" . "\r\n";
        $strSQL .= "	,	L_TAX_AMOUNT	" . "\r\n";
        $strSQL .= "	,	L_NOTAX_AMOUNT	" . "\r\n";
        $strSQL .= "	,	R_KANJYOU_CD	" . "\r\n";
        $strSQL .= "	,	R_KANJYOU_NM	" . "\r\n";
        $strSQL .= "	,	R_KANJYOU_KAIKEI	" . "\r\n";
        $strSQL .= "	,	R_HOJYO_CD	" . "\r\n";
        $strSQL .= "	,	R_HOJYO_NM	" . "\r\n";
        $strSQL .= "	,	R_HOJYO_KAIKEI	" . "\r\n";
        $strSQL .= "	,	R_HOJYO_KAIKEI_HOJYO1	" . "\r\n";
        $strSQL .= "	,	R_HOJYO_KAIKEI_HOJYO2	" . "\r\n";
        $strSQL .= "	,	R_HOJYO_KAIKEI_HOJYO3	" . "\r\n";
        $strSQL .= "	,	R_HOJYO_KAIKEI_HOJYO4	" . "\r\n";
        $strSQL .= "	,	R_FUTAN_BUMON_CD	" . "\r\n";
        $strSQL .= "	,	R_FUTAN_BUMON_NM	" . "\r\n";
        $strSQL .= "	,	R_FUTAN_BUMON_KAIKEI	" . "\r\n";
        $strSQL .= "	,	R_TAX_KBN_CD	" . "\r\n";
        $strSQL .= "	,	R_TAX_KBN_NM	" . "\r\n";
        $strSQL .= "	,	R_TAX_KBN_KAIKEI	" . "\r\n";
        $strSQL .= "	,	R_TAX_CALC_KBN	" . "\r\n";
        $strSQL .= "	,	R_TAX	" . "\r\n";
        $strSQL .= "	,	R_ODD	" . "\r\n";
        $strSQL .= "	,	R_PRO_CD	" . "\r\n";
        $strSQL .= "	,	R_PRO_NM	" . "\r\n";
        $strSQL .= "	,	R_PRO_KAIKEI	" . "\r\n";
        $strSQL .= "	,	R_AMOUNT	" . "\r\n";
        $strSQL .= "	,	R_TAX_AMOUNT	" . "\r\n";
        $strSQL .= "	,	R_NOTAX_AMOUNT	" . "\r\n";
        $strSQL .= "	,	TEKYO	" . "\r\n";
        $strSQL .= "	,	FREE1	" . "\r\n";
        $strSQL .= "	,	FREE2	" . "\r\n";
        $strSQL .= "	,	FREE3	" . "\r\n";
        $strSQL .= "	,	FREE4	" . "\r\n";
        $strSQL .= "	,	FREE5	" . "\r\n";
        $strSQL .= "	,	FREE6	" . "\r\n";
        $strSQL .= "	,	FREE7	" . "\r\n";
        $strSQL .= "	,	FREE8	" . "\r\n";
        $strSQL .= "	,	DENPYOU_TYPE	" . "\r\n";
        $strSQL .= "	,	REQU_MENU_NM	" . "\r\n";
        $strSQL .= "	,	DENPYOU_NO	" . "\r\n";
        $strSQL .= "	,	DENPYOU_DETAIL_NO	" . "\r\n";
        $strSQL .= "	,	BUMON_CD	" . "\r\n";
        $strSQL .= "	,	BUMON_MN	" . "\r\n";
        $strSQL .= "	,	REQU_USER_CD	" . "\r\n";
        $strSQL .= "	,	REQU_USER_NM	" . "\r\n";
        $strSQL .= "	,	REQU_DATE	" . "\r\n";
        $strSQL .= "	,	TOTAL	" . "\r\n";
        $strSQL .= "	,	BIKO2	" . "\r\n";
        $strSQL .= "	,	FREE1_HEADER	" . "\r\n";
        $strSQL .= "	,	FREE2_HEADER	" . "\r\n";
        $strSQL .= "	,	FREE1_DETAIL	" . "\r\n";
        $strSQL .= "	,	FREE2_DETAIL	" . "\r\n";
        $strSQL .= "	,	CALC_MAE_AMOUNT	" . "\r\n";
        $strSQL .= "	,	UNIT	" . "\r\n";
        $strSQL .= "	,	RATE	" . "\r\n";
        $strSQL .= "	,	SHIHARA_HOUHOU	" . "\r\n";
        $strSQL .= "	,	SHIHARASAKI_CD	" . "\r\n";
        $strSQL .= "	,	SHIHARASAKI_NM	" . "\r\n";
        $strSQL .= "	,	SYUTTYO_AREA_HEADER	" . "\r\n";
        $strSQL .= "	,	SYUTTYO_KBN_HEADER	" . "\r\n";
        $strSQL .= "	,	UNPAID_EXPENSES_HEADER	" . "\r\n";
        $strSQL .= "	,	PAYER_HEADER	" . "\r\n";
        $strSQL .= "	,	AITE_KBN_HEADER	" . "\r\n";
        $strSQL .= "	,	KOSYA_DETAIL	" . "\r\n";
        $strSQL .= "	,	TYUMONSYO_NO_DETAIL	" . "\r\n";
        $strSQL .= "	,	SYAOKAISYA_NM_DETAIL	" . "\r\n";
        $strSQL .= "	,	KOUZA_HSSEI_NO_DETAIL	" . "\r\n";
        $strSQL .= "	,	KOUZA_NM_DETAIL	" . "\r\n";
        $strSQL .= "	,	CAR_TYPE_DETAIL	" . "\r\n";
        $strSQL .= "	,	CAR_NO_DETAIL	" . "\r\n";
        $strSQL .= "	,	TYUKOSYA_NO_DETAIL	" . "\r\n";
        $strSQL .= "	,	CUSTOMER_NO_DETAIL	" . "\r\n";
        $strSQL .= "	,	LOAN_CRE_CD_DETAIL	" . "\r\n";
        $strSQL .= "	,	ZONBO_DETAIL	" . "\r\n";
        $strSQL .= "	,	HOKENGAISYA_DETAIL	" . "\r\n";
        $strSQL .= "	,	TORIHIKI_KBN_DETAIL	" . "\r\n";
        $strSQL .= "	,	TOKUREI_KBN	" . "\r\n";
        $strSQL .= "	,	AITE_KBN	" . "\r\n";
        $strSQL .= "	,	TORIKOMU_DATE	" . "\r\n";
        $strSQL .= "	,	TORIKOMU_FILE_NM	" . "\r\n";

        $strSQL .= " )VALUES( " . "\r\n";

        $strSQL .= "  '" . $postData['SHIWAKE_NO'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['SHIWAKE_KBN'] . "' " . "\r\n";
        $strSQL .= " ,TO_DATE('" . $postData['SHIWAKE_CRE_DATE'] . "','YYYY/MM/DD') " . "\r\n";
        $strSQL .= " ,'" . $postData['SHIWAKE_CRE_TIME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['BIKO1'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['BUSI_REGIST_NUM'] . "' " . "\r\n";
        $strSQL .= " ,TO_DATE('" . $postData['SHIWAKE_DATE'] . "','YYYY/MM/DD') " . "\r\n";
        $strSQL .= " ,'" . $postData['L_KANJYOU_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_KANJYOU_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_KANJYOU_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_HOJYO_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_HOJYO_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_HOJYO_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_HOJYO_KAIKEI_HOJYO1'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_HOJYO_KAIKEI_HOJYO2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_HOJYO_KAIKEI_HOJYO3'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_HOJYO_KAIKEI_HOJYO4'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_FUTAN_BUMON_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_FUTAN_BUMON_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_FUTAN_BUMON_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_TAX_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_TAX_KBN_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_TAX_KBN_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_TAX_CALC_KBN'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_TAX'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_ODD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_PRO_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_PRO_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_PRO_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_AMOUNT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_TAX_AMOUNT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['L_NOTAX_AMOUNT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_KANJYOU_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_KANJYOU_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_KANJYOU_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_HOJYO_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_HOJYO_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_HOJYO_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_HOJYO_KAIKEI_HOJYO1'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_HOJYO_KAIKEI_HOJYO2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_HOJYO_KAIKEI_HOJYO3'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_HOJYO_KAIKEI_HOJYO4'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_FUTAN_BUMON_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_FUTAN_BUMON_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_FUTAN_BUMON_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_TAX_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_TAX_KBN_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_TAX_KBN_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_TAX_CALC_KBN'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_TAX'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_ODD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_PRO_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_PRO_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_PRO_KAIKEI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_AMOUNT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_TAX_AMOUNT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['R_NOTAX_AMOUNT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['TEKYO'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE1'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE3'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE4'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE5'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE6'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE7'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE8'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['DENPYOU_TYPE'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['REQU_MENU_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['DENPYOU_NO'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['DENPYOU_DETAIL_NO'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['BUMON_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['BUMON_MN'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['REQU_USER_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['REQU_USER_NM'] . "' " . "\r\n";
        $strSQL .= " ,TO_DATE('" . $postData['REQU_DATE'] . "','YYYY/MM/DD') " . "\r\n";
        $strSQL .= " ,'" . $postData['TOTAL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['BIKO2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE1_HEADER'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE2_HEADER'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE1_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['FREE2_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['CALC_MAE_AMOUNT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['UNIT'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['RATE'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['SHIHARA_HOUHOU'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['SHIHARASAKI_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['SHIHARASAKI_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['SYUTTYO_AREA_HEADER'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['SYUTTYO_KBN_HEADER'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['UNPAID_EXPENSES_HEADER'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['PAYER_HEADER'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['AITE_KBN_HEADER'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['KOSYA_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['TYUMONSYO_NO_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['SYAOKAISYA_NM_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['KOUZA_HSSEI_NO_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['KOUZA_NM_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['CAR_TYPE_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['CAR_NO_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['TYUKOSYA_NO_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['CUSTOMER_NO_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['LOAN_CRE_CD_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['ZONBO_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['HOKENGAISYA_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['TORIHIKI_KBN_DETAIL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['TOKUREI_KBN'] . "' " . "\r\n";
        $strSQL .= " ,'" . $postData['AITE_KBN'] . "' " . "\r\n";
        $strSQL .= " ,TO_CHAR(TO_DATE('" . $systemTime . "','YYYY/MM/DD HH24:MI:SS'),'YYYY/MM/DD HH24:MI:SS') " . "\r\n";
        $strSQL .= " ,'" . $filename . "' " . "\r\n";

        $strSQL .= " ) " . "\r\n";

        return parent::insert($strSQL);
    }

    public function fncInsRAKUData($strSql)
    {
        return parent::insert($strSql);
    }
    //========== 設定関連 start ==========
    public function repeatCheck($groupName)
    {
        $strsql = $this->repeatCheckSql($groupName);
        return parent::select($strsql);
    }
    public function repeatCheckSql($groupName)
    {
        $strSQL = "";
        $strSQL .= "        SELECT * " . "\r\n";
        $strSQL .= "        FROM" . "\r\n";
        $strSQL .= "        HRAKU_TBL_GROUP" . "\r\n";
        $strSQL .= "      WHERE" . "\r\n";
        $strSQL .= "        GROUP_NM='@GROUP_NM'" . "\r\n";
        $strSQL = str_replace("@GROUP_NM", $groupName, $strSQL);
        return $strSQL;
    }

    public function getSyainMstAllDataSql()
    {
        $strSQL = "SELECT SYAIN_NM,";
        $strSQL .= " SYAIN_NO";
        $strSQL .= " FROM   HSYAINMST";

        return $strSQL;
    }

    public function fncDataSetSql()
    {

        $strSQL = "SELECT";

        $strSQL .= "    BUSYO_CD,";

        $strSQL .= "    BUSYO_NM,";

        $strSQL .= "    KKR_BUSYO_CD";

        $strSQL .= " FROM HBUSYO";

        $strSQL .= " WHERE ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')";

        $strSQL .= " ORDER BY BUSYO_CD";

        return $strSQL;
    }

    public function getSyainMstAllData()
    {
        $str_sql = $this->getSyainMstAllDataSql();
        return parent::select($str_sql);
    }

    public function fncDataSet()
    {
        $str_sql = $this->fncDataSetSql();
        return parent::select($str_sql);

    }
    //========== 設定関連 end ==========
}