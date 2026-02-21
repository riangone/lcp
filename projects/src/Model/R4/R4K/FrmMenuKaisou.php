<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmMenuKaisou extends ClsComDb
{
    private $clsComFnc;
    private $ClsComFnc;
    function fncHMENUSTYLESelectSQL($STYLE_ID)
    {
        $strSQL = '';

        $strSQL = '	SELECT  ';
        $strSQL .= ' KAISOU_ID1 ';
        $strSQL .= ',KAISOU_ID2';
        $strSQL .= ',KAISOU_ID3';
        $strSQL .= ',KAISOU_ID4';
        $strSQL .= ',KAISOU_ID5';
        $strSQL .= ',KAISOU_ID6';
        $strSQL .= ',KAISOU_ID7';
        $strSQL .= ',KAISOU_ID8';
        $strSQL .= ',KAISOU_ID9';
        $strSQL .= ',KAISOU_ID10';
        $strSQL .= ',KAISOU_NM';
        $strSQL .= ',PRO.PRO_NO';
        $strSQL .= ",'' as search";
        $strSQL .= ",PRO.PRO_NM,TO_CHAR(KAISO.CREATE_DATE,'YYYY/MM/DD HH:MI:SS') AS CREATE_DATE ";
        $strSQL .= ' FROM      HMENUKAISOUMST KAISO';
        $strSQL .= ' LEFT JOIN HPROGRAMMST PRO ';
        $strSQL .= ' ON        KAISO.PRO_NO=PRO.PRO_NO';
        $strSQL .= " AND       PRO.SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";
        $strSQL .= " WHERE     KAISO.SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";

        if ($STYLE_ID != '') {
            $strSQL .= ' AND KAISO.STYLE_ID=@STYLE_ID ';
        }

        $strSQL .= ' ORDER BY KAISOU_ID1 ';
        $strSQL .= ' ,KAISOU_ID2';
        $strSQL .= ' ,KAISOU_ID3';
        $strSQL .= ' ,KAISOU_ID4';
        $strSQL .= ' ,KAISOU_ID5';
        $strSQL .= ' ,KAISOU_ID6';
        $strSQL .= ' ,KAISOU_ID7';
        $strSQL .= ' ,KAISOU_ID8';
        $strSQL .= ' ,KAISOU_ID9';
        $strSQL .= ' ,KAISOU_ID10';

        $strSQL = str_replace('@STYLE_ID', $STYLE_ID, $strSQL);

        return $strSQL;
    }

    function fncGetSysNMSQL()
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = '';

        $strSQL = 'SELECT STYLE_ID,STYLE_NM FROM HMENUSTYLE';
        $strSQL .= "   WHERE SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";

        return $strSQL;
    }

    function fncGetProNMSQL($PRO_NO)
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = '';

        $strSQL = 'SELECT PRO_NM FROM HPROGRAMMST';
        $strSQL .= '     WHERE PRO_NO = ' . $this->clsComFnc->FncSqlNv($PRO_NO);
        $strSQL .= "     AND   SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";

        return $strSQL;
    }

    function fncDelKaisouMstSQL($STYLE_ID)
    {
        $this->clsComFnc = new ClsComFnc();
        $strSQL = '';

        $strSQL = 'delete from hmenukaisoumst where style_id=' . $this->clsComFnc->FncSqlNz($STYLE_ID);
        $strSQL .= "  AND SYS_KB = '@SYS_KB'";
        $strSQL = str_replace('@SYS_KB', ClsComFnc::GSYSTEM_KB, $strSQL);

        return $strSQL;
    }

    //--- 20151208 LI UPD S
    // function fncUpdKaisouMstSQL($STYLE_ID, $inputData)
    function fncUpdKaisouMstSQL($STYLE_ID, $inputData, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
        $strSQL = '';

        //--- 20151208 LI UPD S
        // $this->clsComFnc = new ClsComFnc();
        $this->ClsComFnc = $ClsComFnc;
        //--- 20151208 LI UPD E
        $UPD_PRG_ID = $this->clsComFnc->FncSqlNv('MenuKaisou');
        $UPDUSER = $this->clsComFnc->FncSqlNv($this->GS_LOGINUSER['strUserID']);
        $UPDCLTNM = $this->clsComFnc->FncSqlNv($this->GS_LOGINUSER['strClientNM']);

        $strSQL = 'insert into HMENUKAISOUMST (';
        $strSQL .= ' SYS_KB,';
        $strSQL .= 'STYLE_ID,';
        $strSQL .= 'KAISOU_ID1,';
        $strSQL .= 'KAISOU_ID2,';
        $strSQL .= ' KAISOU_ID3,';
        $strSQL .= ' KAISOU_ID4,';
        $strSQL .= ' KAISOU_ID5,';
        $strSQL .= ' KAISOU_ID6,';
        $strSQL .= ' KAISOU_ID7,';
        $strSQL .= ' KAISOU_ID8,';
        $strSQL .= ' KAISOU_ID9,';
        $strSQL .= ' KAISOU_ID10,';
        $strSQL .= ' KAISOU_NM,';
        $strSQL .= ' PRO_NO,';
        $strSQL .= ' UPD_DATE,';
        $strSQL .= ' CREATE_DATE,';
        $strSQL .= ' UPD_SYA_CD,';
        $strSQL .= ' UPD_PRG_ID,';
        $strSQL .= ' UPD_CLT_NM) values (';

        $strSQL .= ' @SYS_KB,';
        $strSQL .= ' @STYLE_ID,';
        $strSQL .= ' @KAISOU_ID_1 ,';
        $strSQL .= ' @KAISOU_ID2 ,';
        $strSQL .= ' @KAISOU_ID3 ,';
        $strSQL .= ' @KAISOU_ID4 ,';
        $strSQL .= ' @KAISOU_ID5 ,';
        $strSQL .= ' @KAISOU_ID6 ,';
        $strSQL .= ' @KAISOU_ID7 ,';
        $strSQL .= ' @KAISOU_ID8 ,';
        $strSQL .= ' @KAISOU_ID9 ,';
        $strSQL .= ' @KAISOU_ID10 ,';
        $strSQL .= ' @KAISOU_NM ,';
        $strSQL .= ' @PRO_NO ,';
        $strSQL .= ' @UPD_DATE ,';
        $strSQL .= ' @CREATE_DATE ,';
        $strSQL .= ' @UPD_SYA_CD ,';
        $strSQL .= ' @UPD_PRG_ID ,';
        $strSQL .= ' @UPD_CLT_NM)';

        $strSQL = str_replace('@SYS_KB', ClsComFnc::GSYSTEM_KB, $strSQL);
        $strSQL = str_replace('@STYLE_ID', $this->clsComFnc->FncSqlNv($STYLE_ID), $strSQL);
        $strSQL = str_replace('@KAISOU_ID_1', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID1']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID2', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID2']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID3', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID3']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID4', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID4']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID5', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID5']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID6', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID6']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID7', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID7']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID8', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID8']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID9', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID9']), $strSQL);
        $strSQL = str_replace('@KAISOU_ID10', $this->clsComFnc->FncSqlNz($inputData['KAISOU_ID10']), $strSQL);
        $strSQL = str_replace('@KAISOU_NM', $this->clsComFnc->FncSqlNv($inputData['KAISOU_NM']), $strSQL);
        $strSQL = str_replace('@PRO_NO', $this->clsComFnc->FncSqlNv($inputData['PRO_NO']), $strSQL);
        $strSQL = str_replace('@UPD_DATE', 'sysdate', $strSQL);

        $replaceDate = $inputData['CREATE_DATE'] != '' ? "TO_DATE(" . $this->clsComFnc->FncSqlNv($inputData['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";
        $strSQL = str_replace('@CREATE_DATE', $replaceDate, $strSQL);

        $strSQL = str_replace('@UPD_SYA_CD', $UPDUSER, $strSQL);
        $strSQL = str_replace('@UPD_PRG_ID', $UPD_PRG_ID, $strSQL);
        $strSQL = str_replace('@UPD_CLT_NM', $UPDCLTNM, $strSQL);

        return $strSQL;
    }

    public function fncHMENUSTYLESelect($STYLE_ID)
    {
        $strsql = $this->fncHMENUSTYLESelectSQL($STYLE_ID);
        return parent::select($strsql);
    }

    public function fncGetSysNM()
    {
        $strsql = $this->fncGetSysNMSQL();
        return parent::select($strsql);
    }

    public function fncGetProNM($PRO_NO)
    {
        $strsql = $this->fncGetProNMSQL($PRO_NO);
        return parent::select($strsql);
    }

    public function fncDelKaisouMst($STYLE_ID)
    {
        $strsql = $this->fncDelKaisouMstSQL($STYLE_ID);
        return parent::Do_Execute($strsql);
    }

    //--- 20151208 LI UPD S
    // public function fncUpdKaisouMst($STYLE_ID, $inputData)
    public function fncUpdKaisouMst($STYLE_ID, $inputData, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
        //--- 20151208 LI UPD S
        // $strsql = $this -> fncUpdKaisouMstSQL($STYLE_ID, $inputData);
        $strsql = $this->fncUpdKaisouMstSQL($STYLE_ID, $inputData, $ClsComFnc);
        //--- 20151208 LI UPD E
        return parent::Do_Execute($strsql);
    }

}