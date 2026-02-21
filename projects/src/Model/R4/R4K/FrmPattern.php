<?php
namespace App\Model\R4\R4K;

/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151208           #2089                                                         li
 * --------------------------------------------------------------------------------------------
 */

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmPattern extends ClsComDb
{
    private $clsComFnc;
    private $ClsComFnc;
    function fncPatternSelectSQL($STYLE_ID)
    {
        $strSQL = '';
        $this->clsComFnc = new ClsComFnc();

        $strSQL = "	SELECT PATTERN_ID, PATTERN_NM, TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH:MI:SS') AS CREATE_DATE		";
        // $strSQL = "	SELECT PATTERN_NM, TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH:MI:SS') AS CREATE_DATE		";
        $strSQL .= '	FROM(HPATTERNMST)		';
        //画面: 選択されている所属(ﾌﾟﾛﾊﾟﾃｨに設定してある所属ID)"
        $strSQL .= "	WHERE STYLE_ID = '" . $STYLE_ID . "'";
        $strSQL .= " AND   SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";
        $strSQL .= '	ORDER BY PATTERN_ID   ';

        return $strSQL;
    }

    function fncPatternListSelectSQL($STYLE_ID)
    {
        $strSQL = '';
        $this->clsComFnc = new ClsComFnc();

        $strSQL = "	SELECT 'NO' ADD_FLAG,  PRO.PRO_NM	, TO_CHAR(KAI.PRO_NO) AS PRO_NO, ''   CREATE_DATE";
        $strSQL .= '	FROM   HMENUKAISOUMST KAI		';
        $strSQL .= '	INNER JOIN ';
        $strSQL .= '	 HPROGRAMMST PRO ';
        $strSQL .= '	ON KAI.PRO_NO = PRO.PRO_NO   ';
        $strSQL .= " AND PRO.SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";
        //画面: 選択されている所属(ﾌﾟﾛﾊﾟﾃｨに設定してある所属ID)"
        $strSQL .= " 	WHERE KAI.STYLE_ID = '" . $STYLE_ID . "'";
        $strSQL .= "AND KAI.SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";
        $strSQL .= '	ORDER BY TO_NUMBER(PRO_NO)	';

        return $strSQL;
    }

    function fncHMENUSTYLESelectSQL()
    {
        $strSQL = '';
        $this->clsComFnc = new ClsComFnc();

        $strSQL = "SELECT STYLE_ID,STYLE_NM FROM HMENUSTYLE WHERE SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";
        return $strSQL;
    }

    function fncPatternListSelSQL($STYLE_ID, $PATTERN_ID)
    {
        $strSQL = '';
        $this->clsComFnc = new ClsComFnc();

        $strSQL = "	SELECT TO_CHAR(PRO_NO) AS PRO_NO ,TO_CHAR(CREATE_DATE,'YYYY/MM/DD HH:MI:SS') AS CREATE_DATE ,'YES' AS ADD_FLAG";
        $strSQL .= '	   FROM   HMENUKANRIPATTERN	';
        $strSQL .= "    WHERE  STYLE_ID = '" . $STYLE_ID . "'";
        $strSQL .= "	  AND    PATTERN_ID = '" . $PATTERN_ID . "'";
        $strSQL .= "    AND SYS_KB  = '" . ClsComFnc::GSYSTEM_KB . "'";
        $strSQL .= '	  ORDER BY TO_NUMBER(PRO_NO) ';

        return $strSQL;
    }

    function fncDelTblSQL($strTableNM, $selectIndex)
    {
        $strSQL = '';
        $this->clsComFnc = new ClsComFnc();

        $strSQL = 'DELETE FROM @TABLENM';
        $strSQL .= "   WHERE   STYLE_ID = '" . $selectIndex . "'";
        $strSQL .= "   AND     SYS_KB = '" . ClsComFnc::GSYSTEM_KB . "'";
        $strSQL = str_replace('@TABLENM', $strTableNM, $strSQL);

        return $strSQL;
    }
    //--- 20151208 LI UPD S
    // function fncInsertPatternMstSQL($inputData, $selectIndex, $UPDCLTNM)
    function fncInsertPatternMstSQL($inputData, $selectIndex, $UPDCLTNM, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];

        $strSQL = '';

        $strSQL = 'INSERT INTO HPATTERNMST (';
        $strSQL .= '     SYS_KB';
        $strSQL .= '     ,STYLE_ID';
        $strSQL .= '     ,PATTERN_ID';
        $strSQL .= '     ,PATTERN_NM';
        $strSQL .= '     ,UPD_DATE';
        $strSQL .= '     ,CREATE_DATE';
        $strSQL .= '     ,UPD_SYA_CD';
        $strSQL .= '     ,UPD_PRG_ID';
        $strSQL .= '     ,UPD_CLT_NM)';
        $strSQL .= '     values (';
        $strSQL .= "     '@SYS_KB',";
        $strSQL .= '     @STYLE_ID,';
        $strSQL .= '     @PATTERN_ID,';
        $strSQL .= '     @PATTERN_NM,';
        $strSQL .= '     sysdate,';
        $strSQL .= '     @CREATE_DATE,';
        $strSQL .= "     '" . $UPDUSER . "',";
        $strSQL .= "     'frmPattern',";
        $strSQL .= "     '" . $UPDCLTNM . "')";
        //--- 20151208 LI UPD S
        // $this -> ClsComFnc = new ClsComFnc();
        $this->ClsComFnc = $ClsComFnc;
        //--- 20151208 LI UPD E

        $strSQL = str_replace('@SYS_KB', ClsComFnc::GSYSTEM_KB, $strSQL);
        $strSQL = str_replace('@STYLE_ID', $this->ClsComFnc->FncSqlNv($selectIndex), $strSQL);
        $strSQL = str_replace('@PATTERN_ID', $this->ClsComFnc->FncSqlNv($inputData['PATTERN_ID']), $strSQL);
        $strSQL = str_replace('@PATTERN_NM', $this->ClsComFnc->FncSqlNv($inputData['PATTERN_NM']), $strSQL);

        $replaceDate = $inputData['CREATE_DATE'] != '' ? "TO_DATE(" . $this->clsComFnc->FncSqlNv($inputData['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";
        $strSQL = str_replace('@CREATE_DATE', $replaceDate, $strSQL);

        return $strSQL;
    }

    //--- 20151208 LI UPD S
    // function fncInsPatternListSQL($intPtnRow, $inputData, $selectIndex, $UPDCLTNM)
    function fncInsPatternListSQL($intPtnRow, $inputData, $selectIndex, $UPDCLTNM, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
        $UPDUSER = $this->GS_LOGINUSER['strUserID'];

        $strSQL = '';

        $strSQL = 'INSERT INTO HMENUKANRIPATTERN (';
        $strSQL .= '     SYS_KB';
        $strSQL .= '     ,STYLE_ID';
        $strSQL .= '     ,PATTERN_ID';
        $strSQL .= '     ,PRO_NO';
        $strSQL .= '     ,UPD_DATE';
        $strSQL .= '     ,CREATE_DATE';
        $strSQL .= '     ,UPD_SYA_CD';
        $strSQL .= '     ,UPD_PRG_ID';
        $strSQL .= '     ,UPD_CLT_NM)';
        $strSQL .= '     values (';
        $strSQL .= "     '@SYS_KB',";
        $strSQL .= '     @STYLE_ID,';
        $strSQL .= '     @PATTERN_ID,';
        $strSQL .= '     @PRO_NO,';
        $strSQL .= '     sysdate,';
        $strSQL .= '     @CREATE_DATE,';
        $strSQL .= "     '" . $UPDUSER . "',";
        $strSQL .= "     'frmPattern',";
        $strSQL .= "     '" . $UPDCLTNM . "')";

        //--- 20151208 LI UPD S
        // $this -> ClsComFnc = new ClsComFnc();
        $this->ClsComFnc = $ClsComFnc;
        //--- 20151208 LI UPD E


        $strSQL = str_replace('@SYS_KB', ClsComFnc::GSYSTEM_KB, $strSQL);
        $strSQL = str_replace('@STYLE_ID', $this->ClsComFnc->FncSqlNv($selectIndex), $strSQL);
        $strSQL = str_replace('@PATTERN_ID', $this->ClsComFnc->FncSqlNv($intPtnRow), $strSQL);
        $strSQL = str_replace('@PRO_NO', $this->ClsComFnc->FncSqlNv($inputData['PRO_NO']), $strSQL);

        $replaceDate = $inputData['CREATE_DATE'] != '' ? "TO_DATE(" . $this->clsComFnc->FncSqlNv($inputData['CREATE_DATE']) . ",'YYYY/MM/DD HH24:MI:SS')" : "SYSDATE";
        $strSQL = str_replace('@CREATE_DATE', $replaceDate, $strSQL);

        return $strSQL;
    }

    public function fncPatternSelect($STYLE_ID)
    {
        $strsql = $this->fncPatternSelectSQL($STYLE_ID);
        return parent::select($strsql);
    }

    public function fncPatternListSelect($STYLE_ID)
    {
        $strsql = $this->fncPatternListSelectSQL($STYLE_ID);
        return parent::select($strsql);
    }

    public function fncHMENUSTYLESelect()
    {
        $strsql = $this->fncHMENUSTYLESelectSQL();
        return parent::select($strsql);
    }

    public function fncPatternListSel($STYLE_ID, $PATTERN_ID)
    {
        $strsql = $this->fncPatternListSelSQL($STYLE_ID, $PATTERN_ID);
        return parent::select($strsql);
    }

    public function fncDelTbl($strTableNM, $selectIndex)
    {
        $strSql = $this->fncDelTblSQL($strTableNM, $selectIndex);
        return parent::Do_Execute($strSql);
    }
    //--- 20151208 LI UPD S
    // public function fncInsertPatternMst($inputData, $selectIndex, $UPDCLTNM)
    public function fncInsertPatternMst($inputData, $selectIndex, $UPDCLTNM, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
        //--- 20151208 LI UPD S
        // $strsql = $this -> fncInsertPatternMstSQL($inputData, $selectIndex, $UPDCLTNM);
        $strsql = $this->fncInsertPatternMstSQL($inputData, $selectIndex, $UPDCLTNM, $ClsComFnc);
        //--- 20151208 LI UPD E
        return parent::Do_Execute($strsql);
    }
    //--- 20151208 LI UPD S
    // public function fncInsPatternList($intPtnRow, $inputData, $selectIndex, $UPDCLTNM)
    public function fncInsPatternList($intPtnRow, $inputData, $selectIndex, $UPDCLTNM, $ClsComFnc)
    //--- 20151208 LI UPD E
    {
        //--- 20151208 LI UPD S
        // $strsql = $this -> fncInsPatternListSQL($intPtnRow, $inputData, $selectIndex, $UPDCLTNM);
        $strsql = $this->fncInsPatternListSQL($intPtnRow, $inputData, $selectIndex, $UPDCLTNM, $ClsComFnc);
        //--- 20151208 LI UPD E
        return parent::Do_Execute($strsql);
    }

}