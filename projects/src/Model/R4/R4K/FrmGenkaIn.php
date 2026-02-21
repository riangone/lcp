<?php
/**
 * 説明：
 *
 *
 * @author zhenghuiyun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL 
 * 20150930           ---                       BUG#2112                       Yuanjh  
 * --------------------------------------------------------------------------------------------
 */
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

class FrmGenkaIn extends ClsComDb
{
    public $cstrTableName = "HGENKAMST";
    public $UPDAPP = "GenkaIn";
    public $ClsComFnc;
    public function fncTableDelete_sql()
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM @TABLE_NAME";
        $strSQL = str_replace("@TABLE_NAME", $this->cstrTableName, $strSQL);
        return $strSQL;
    }

    public function fncTableDelete()
    {
        return parent::Do_Execute($this->fncTableDelete_sql());
    }

    public function fncGetSqlDelete($post_TOA_NAME, $post_HTA_PRC)
    {
        $this->ClsComFnc = new ClsComFnc();
        $post_TOA_NAME = $this->ClsComFnc->FncNv($post_TOA_NAME);
        $post_HTA_PRC = $this->ClsComFnc->FncNv($post_HTA_PRC, "''");
        $strSQL = "";
        $strSQL .= "DELETE FROM @TABLE_NAME" . "\r\n";
        $strSQL .= " WHERE TOA_NAME = '@TOA_NAME'" . "\r\n";
        //---20150930  Yuanjh  UPD S.
        //$strSQL .= "   AND HTA_PRC = @HTA_PRC" . "\r\n";
        $strSQL .= "   AND HTA_PRC = '@HTA_PRC'" . "\r\n";
        //---20150930  Yuanjh  UPD E.
        //ﾃｰﾌﾞﾙ名を設定
        $strSQL = str_replace("@TABLE_NAME", $this->cstrTableName, $strSQL);
        $strSQL = str_replace("@TOA_NAME", $post_TOA_NAME, $strSQL);
        $strSQL = str_replace("@HTA_PRC", $post_HTA_PRC, $strSQL);
        return $strSQL;
    }

    public function fncDelete($post_TOA_NAME, $post_HTA_PRC)
    {
        return parent::Do_Execute($this->fncGetSqlDelete($post_TOA_NAME, $post_HTA_PRC));
    }

    public function fncGetSqlInsert($postArr)
    {
        $this->ClsComFnc = new ClsComFnc();

        $UPDUSER = $this->GS_LOGINUSER['strUserID'];
        $UPDCLTNM = $this->GS_LOGINUSER['strClientNM'];

        $strSQL = "";
        $strSQL .= "INSERT INTO @TABLE_NAME (";
        $strSQL .= "  ID";
        $strSQL .= ", TOA_NAME";
        $strSQL .= ", HTA_PRC";
        $strSQL .= ", TNP_PRC";
        $strSQL .= ", FZK_PRC";
        $strSQL .= ", SOU_HABA";
        $strSQL .= ", SYA_PCS";
        $strSQL .= ", SIK_PCS";
        $strSQL .= ", FZK_PCS";
        $strSQL .= ", FZK_RIE";
        $strSQL .= ", KTN_PCS";
        $strSQL .= ", KTN_HABA";
        $strSQL .= ", TYK_PCS";
        $strSQL .= ", TYK_HABA";
        $strSQL .= ", F_PCS";
        $strSQL .= ", F_HABA";
        $strSQL .= ", UPD_DATE";
        $strSQL .= ", CREATE_DATE";
        $strSQL .= ", UPD_SYA_CD" . "\r\n";
        $strSQL .= ", UPD_PRG_ID" . "\r\n";
        $strSQL .= ", UPD_CLT_NM" . "\r\n";
        $strSQL .= ") VALUES (";
        $strSQL .= "  @ID";
        $strSQL .= ", @TOA_NAME";
        $strSQL .= ", @HTA_PRC";
        $strSQL .= ", @TNP_PRC";
        $strSQL .= ", @FZK_PRC";
        $strSQL .= ", @SOU_HABA";
        $strSQL .= ", @SYA_PCS";
        $strSQL .= ", @SIK_PCS";
        $strSQL .= ", @FZK_PCS";
        $strSQL .= ", @FZK_RIE";
        $strSQL .= ", @KTN_PCS";
        $strSQL .= ", @KTN_HABA";
        $strSQL .= ", @TYK_PCS";
        $strSQL .= ", @TYK_HABA";
        $strSQL .= ", @F_PCS";
        $strSQL .= ", @F_HABA";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", SYSDATE";
        $strSQL .= ", '@UPDUSER'" . "\r\n";
        $strSQL .= ", '@UPDAPP'" . "\r\n";
        $strSQL .= ", '@UPDCLT'" . "\r\n";
        $strSQL .= ")";

        //ﾃｰﾌﾞﾙ名を設定
        $strSQL = str_replace("@TABLE_NAME", $this->cstrTableName, $strSQL);

        $strSQL = str_replace("@ID", $this->ClsComFnc->FncSqlNv($postArr['ID']), $strSQL);
        $strSQL = str_replace("@TOA_NAME", $this->ClsComFnc->FncSqlNv($postArr['TOA_NAME']), $strSQL);
        $strSQL = str_replace("@HTA_PRC", $this->ClsComFnc->FncSqlNv($postArr['HTA_PRC']), $strSQL);
        $strSQL = str_replace("@TNP_PRC", $this->ClsComFnc->FncSqlNv($postArr['TNP_PRC']), $strSQL);
        $strSQL = str_replace("@FZK_PRC", $this->ClsComFnc->FncSqlNv($postArr['FZK_PRC']), $strSQL);
        $strSQL = str_replace("@SOU_HABA", $this->ClsComFnc->FncSqlNv($postArr['SOU_HABA']), $strSQL);
        $strSQL = str_replace("@SYA_PCS", $this->ClsComFnc->FncSqlNv($postArr['SYA_PCS']), $strSQL);
        $strSQL = str_replace("@SIK_PCS", $this->ClsComFnc->FncSqlNv($postArr['SIK_PCS']), $strSQL);
        $strSQL = str_replace("@FZK_PCS", $this->ClsComFnc->FncSqlNv($postArr['FZK_PCS']), $strSQL);
        $strSQL = str_replace("@FZK_RIE", $this->ClsComFnc->FncSqlNv($postArr['FZK_RIE']), $strSQL);
        $strSQL = str_replace("@KTN_PCS", $this->ClsComFnc->FncSqlNv($postArr['KTN_PCS']), $strSQL);
        $strSQL = str_replace("@KTN_HABA", $this->ClsComFnc->FncSqlNv($postArr['KTN_HABA']), $strSQL);
        $strSQL = str_replace("@TYK_PCS", $this->ClsComFnc->FncSqlNv($postArr['TYK_PCS']), $strSQL);
        $strSQL = str_replace("@TYK_HABA", $this->ClsComFnc->FncSqlNv($postArr['TYK_HABA']), $strSQL);
        $strSQL = str_replace("@F_PCS", $this->ClsComFnc->FncSqlNv($postArr['F_PCS']), $strSQL);
        $strSQL = str_replace("@F_HABA", $this->ClsComFnc->FncSqlNv($postArr['F_HABA']), $strSQL);

        $strSQL = str_replace("@UPDUSER", $UPDUSER, $strSQL);
        $strSQL = str_replace("@UPDAPP", $this->UPDAPP, $strSQL);
        $strSQL = str_replace("@UPDCLT", $UPDCLTNM, $strSQL);

        //戻り値			
        return $strSQL;
    }

    public function fncInsert($postArr)
    {
        return parent::Do_Execute($this->fncGetSqlInsert($postArr));
    }

}