<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                                    担当
 * YYYYMMDD           #ID                                     XXXXXX                                 FCSDL
 * 20240228           20240213_機能改善要望対応 NO6    OBC科目マスタのレイアウトが変更されたため、
 *                                                   エクスポート／インポートとも対応が必要                     caina
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
use Cake\Routing\Router;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKOBCDataExpImp extends ClsComDb
{
    public $SessionComponent;
    function GetalldataSql($tablename)
    {
        $strSql = "";
        $strSql .= "SELECT   *" . "\r\n";
        $strSql .= "FROM     @tablename" . "\r\n";
        $strSql .= " ORDER BY ";
        if ($tablename == 'HDK_MST_KAMOKU') {
            $strSql .= "        KAMOK_CD,SUB_KAMOK_CD";
        } elseif ($tablename == 'HDK_MST_SHZKBN') {
            $strSql .= "        TAX_KBN_CD";
        } elseif ($tablename == 'HDK_MST_TORIHIKISAKI') {
            $strSql .= "        TORIHIKISAKI_CD";
        } elseif ($tablename == 'HDK_MST_BUMON') {
            $strSql .= "        BUSYO_CD";
        } elseif ($tablename == 'HDK_MST_BANK') {
            $strSql .= "        BANK_CD,BRANCH_CD";
        }
        $strSql = str_replace("@tablename", $tablename, $strSql);
        return $strSql;
    }

    function Deldata($tablename)
    {
        $strSQL = "";
        $strSQL .= " DELETE  " . "\r\n";
        $strSQL .= "  @tablename " . "\r\n";

        $strSQL = str_replace("@tablename", $tablename, $strSQL);
        return parent::delete($strSQL);
    }

    function InsertKdata($tabledata)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL = "INSERT INTO HDK_MST_KAMOKU " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " KAMOK_CD " . "\r\n";
        $strSQL .= " ,KAMOK_NAME " . "\r\n";
        $strSQL .= " ,SUB_KAMOK_CD " . "\r\n";
        $strSQL .= " ,SUB_KAMOK_NAME " . "\r\n";
        $strSQL .= " ,KAMOK_INDEX " . "\r\n";
        $strSQL .= " ,TAX " . "\r\n";
        //20240228 caina del s
        // $strSQL .= " ,USE_FLG " . "\r\n";
        // $strSQL .= " ,USE_FLG_NM " . "\r\n";
        //20240228 caina del e
        $strSQL .= " ,KARI_TAX_KBN " . "\r\n";
        $strSQL .= " ,KARI_TAX_KBN_NM " . "\r\n";
        $strSQL .= " ,KASI_TAX_KBN " . "\r\n";
        $strSQL .= " ,KASI_TAX_KBN_NM " . "\r\n";
        $strSQL .= " ,TAX_SYUBETU_CD " . "\r\n";
        $strSQL .= " ,TAX_SYUBETU_NAME " . "\r\n";
        $strSQL .= " ,TAX_AUTOCALC_CD " . "\r\n";
        $strSQL .= " ,TAX_AUTOCALC_NAME " . "\r\n";
        $strSQL .= " ,TAX_HASUU_CD " . "\r\n";
        $strSQL .= " ,TAX_HASUU_NAME " . "\r\n";
        $strSQL .= " ,CORP_KBN_CD " . "\r\n";
        $strSQL .= " ,CORP_KBN_NAME " . "\r\n";
        $strSQL .= " ,SIKINGURI " . "\r\n";
        $strSQL .= " ,KARI_SIKINGURI_CD " . "\r\n";
        $strSQL .= " ,KARI_SIKINGURI_NAME " . "\r\n";
        $strSQL .= " ,KASI_SIKINGURI_CD " . "\r\n";
        $strSQL .= " ,KASI_SIKINGURI_NAME " . "\r\n";
        $strSQL .= " ,SONNEKIBUNKI " . "\r\n";
        $strSQL .= " ,HIYOU_KBN_CD " . "\r\n";
        $strSQL .= " ,HIYOU_KBN_NAME " . "\r\n";
        $strSQL .= " ,YOSAN_INPUT_KBN_CD " . "\r\n";
        $strSQL .= " ,YOSAN_INPUT_KBN_NAME " . "\r\n";
        $strSQL .= " ,CACHFLOW " . "\r\n";
        $strSQL .= " ,FURI_MOTO_KIN_CD " . "\r\n";
        $strSQL .= " ,FURI_MOTO_KIN " . "\r\n";
        $strSQL .= " ,FURI_SAKI_TYPE_CD1 " . "\r\n";
        $strSQL .= " ,FURI_SAKI_TYPE_NAME1 " . "\r\n";
        //20240228 caina ins s
        $strSQL .= " ,FURI_SAKI_CD1 " . "\r\n";
        $strSQL .= " ,FURI_SAKI_NAME1 " . "\r\n";
        //20240228 caina ins e
        $strSQL .= " ,FURI_SAKI_TYPE_CD2 " . "\r\n";
        $strSQL .= " ,FURI_SAKI_TYPE_NAME2 " . "\r\n";
        $strSQL .= " ,FURI_SAKI_CD2 " . "\r\n";
        $strSQL .= " ,FURI_SAKI_NAME2 " . "\r\n";
        $strSQL .= " ,CREATE_DATE" . "\r\n";
        $strSQL .= " ,CRE_BUSYO_CD" . "\r\n";
        $strSQL .= " ,CRE_SYA_CD" . "\r\n";
        $strSQL .= " ,CRE_PRG_ID" . "\r\n";
        $strSQL .= " ,CRE_CLT_NM" . "\r\n";
        $strSQL .= " ,UPD_DATE" . "\r\n";
        $strSQL .= " ,UPD_BUSYO_CD" . "\r\n";
        $strSQL .= " ,UPD_SYA_CD" . "\r\n";
        $strSQL .= " ,UPD_PRG_ID" . "\r\n";
        $strSQL .= " ,UPD_CLT_NM" . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  '" . $tabledata['KAMOK_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KAMOK_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['SUB_KAMOK_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['SUB_KAMOK_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KAMOK_INDEX'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX'] . "' " . "\r\n";
        //20240228 caina del s
        // $strSQL .= " ,'" . $tabledata['USE_FLG'] . "' " . "\r\n";
        // $strSQL .= " ,'" . $tabledata['USE_FLG_NM'] . "' " . "\r\n";
        //20240228 caina del e
        $strSQL .= " ,'@KARI_TAX_KBN' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KARI_TAX_KBN_NM'] . "' " . "\r\n";
        $strSQL .= " ,'@KASI_TAX_KBN' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KASI_TAX_KBN_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX_SYUBETU_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX_SYUBETU_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX_AUTOCALC_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX_AUTOCALC_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX_HASUU_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX_HASUU_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['CORP_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['CORP_KBN_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['SIKINGURI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KARI_SIKINGURI_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KARI_SIKINGURI_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KASI_SIKINGURI_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['KASI_SIKINGURI_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['SONNEKIBUNKI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['HIYOU_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['HIYOU_KBN_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['YOSAN_INPUT_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['YOSAN_INPUT_KBN_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['CACHFLOW'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_MOTO_KIN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_MOTO_KIN'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_TYPE_CD1'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_TYPE_NAME1'] . "' " . "\r\n";
        //20240228 caina ins s
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_CD1'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_NAME1'] . "' " . "\r\n";
        //20240228 caina ins e
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_TYPE_CD2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_TYPE_NAME2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_CD2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FURI_SAKI_NAME2'] . "' " . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@CRE_BUSYO_CD'" . "\r\n";
        $strSQL .= " ,'@CRE_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@CRE_CLT_NM'" . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= " ,'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ") ";

        if (isset($tabledata['KARI_TAX_KBN']) && strlen($tabledata['KARI_TAX_KBN']) < 4) {
            $KARI_TAX_KBN = str_pad($tabledata['KARI_TAX_KBN'], 4, "0", STR_PAD_LEFT);
        }
        $strSQL = str_replace("@KARI_TAX_KBN", $KARI_TAX_KBN, $strSQL);

        if (isset($tabledata['KASI_TAX_KBN']) && strlen($tabledata['KASI_TAX_KBN']) < 4) {
            $KASI_TAX_KBN = str_pad($tabledata['KASI_TAX_KBN'], 4, "0", STR_PAD_LEFT);
        }
        $strSQL = str_replace("@KASI_TAX_KBN", $KASI_TAX_KBN, $strSQL);
        $strSQL = str_replace("@CRE_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }
    function existKdata($tabledata)
    {
        $strSQL = "";
        $strSQL = "SELECT KAMOK_CD " . "\r\n";
        $strSQL .= " ,SUB_KAMOK_CD " . "\r\n";
        $strSQL .= " FROM ";
        $strSQL .= "        HDK_MST_KAMOKU";
        $strSQL .= " WHERE KAMOK_CD =  '" . $tabledata['KAMOK_CD'] . "'" . "\r\n";
        $strSQL .= "AND SUB_KAMOK_CD =  '" . $tabledata['SUB_KAMOK_CD'] . "'" . "\r\n";

        return parent::select($strSQL);
    }
    function UpdateKdata($tabledata)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= " UPDATE HDK_MST_KAMOKU " . "\r\n";
        $strSQL .= " SET " . "\r\n";
        $strSQL .= " KAMOK_NAME =  '" . $tabledata['KAMOK_NAME'] . "'" . "\r\n";
        $strSQL .= " ,SUB_KAMOK_NAME =  '" . $tabledata['SUB_KAMOK_NAME'] . "'" . "\r\n";
        $strSQL .= " ,KAMOK_INDEX =  '" . $tabledata['KAMOK_INDEX'] . "'" . "\r\n";
        $strSQL .= " ,TAX =  '" . $tabledata['TAX'] . "'" . "\r\n";
        //20240228 caina del s
        // $strSQL .= " ,USE_FLG =  '" . $tabledata['USE_FLG'] . "'" . "\r\n";
        // $strSQL .= " ,USE_FLG_NM =  '" . $tabledata['USE_FLG_NM'] . "'" . "\r\n";
        //20240228 caina del e
        $strSQL .= " ,KARI_TAX_KBN =  '@KARI_TAX_KBN'" . "\r\n";
        $strSQL .= " ,KARI_TAX_KBN_NM =  '" . $tabledata['KARI_TAX_KBN_NM'] . "'" . "\r\n";
        $strSQL .= " ,KASI_TAX_KBN =  '@KASI_TAX_KBN' " . "\r\n";
        $strSQL .= " ,KASI_TAX_KBN_NM =  '" . $tabledata['KASI_TAX_KBN_NM'] . "'" . "\r\n";
        $strSQL .= " ,TAX_SYUBETU_CD =  '" . $tabledata['TAX_SYUBETU_CD'] . "'" . "\r\n";
        $strSQL .= " ,TAX_SYUBETU_NAME =  '" . $tabledata['TAX_SYUBETU_NAME'] . "'" . "\r\n";
        $strSQL .= " ,TAX_AUTOCALC_CD =  '" . $tabledata['TAX_AUTOCALC_CD'] . "'" . "\r\n";
        $strSQL .= " ,TAX_AUTOCALC_NAME =  '" . $tabledata['TAX_AUTOCALC_NAME'] . "'" . "\r\n";
        $strSQL .= " ,TAX_HASUU_CD =  '" . $tabledata['TAX_HASUU_CD'] . "'" . "\r\n";
        $strSQL .= " ,TAX_HASUU_NAME =  '" . $tabledata['TAX_HASUU_NAME'] . "'" . "\r\n";
        $strSQL .= " ,CORP_KBN_CD =  '" . $tabledata['CORP_KBN_CD'] . "'" . "\r\n";
        $strSQL .= " ,CORP_KBN_NAME =  '" . $tabledata['CORP_KBN_NAME'] . "'" . "\r\n";
        $strSQL .= " ,SIKINGURI =  '" . $tabledata['SIKINGURI'] . "'" . "\r\n";
        $strSQL .= " ,KARI_SIKINGURI_CD =  '" . $tabledata['KARI_SIKINGURI_CD'] . "'" . "\r\n";
        $strSQL .= " ,KARI_SIKINGURI_NAME =  '" . $tabledata['KARI_SIKINGURI_NAME'] . "'" . "\r\n";
        $strSQL .= " ,KASI_SIKINGURI_CD =  '" . $tabledata['KASI_SIKINGURI_CD'] . "'" . "\r\n";
        $strSQL .= " ,KASI_SIKINGURI_NAME =  '" . $tabledata['KASI_SIKINGURI_NAME'] . "'" . "\r\n";
        $strSQL .= " ,SONNEKIBUNKI =  '" . $tabledata['SONNEKIBUNKI'] . "'" . "\r\n";
        $strSQL .= " ,HIYOU_KBN_CD =  '" . $tabledata['HIYOU_KBN_CD'] . "'" . "\r\n";
        $strSQL .= " ,HIYOU_KBN_NAME =  '" . $tabledata['HIYOU_KBN_NAME'] . "'" . "\r\n";
        $strSQL .= " ,YOSAN_INPUT_KBN_CD =  '" . $tabledata['YOSAN_INPUT_KBN_CD'] . "'" . "\r\n";
        $strSQL .= " ,YOSAN_INPUT_KBN_NAME =  '" . $tabledata['YOSAN_INPUT_KBN_NAME'] . "'" . "\r\n";
        $strSQL .= " ,CACHFLOW =  '" . $tabledata['CACHFLOW'] . "'" . "\r\n";
        $strSQL .= " ,FURI_MOTO_KIN_CD =  '" . $tabledata['FURI_MOTO_KIN_CD'] . "'" . "\r\n";
        $strSQL .= " ,FURI_MOTO_KIN =  '" . $tabledata['FURI_MOTO_KIN'] . "'" . "\r\n";
        $strSQL .= " ,FURI_SAKI_TYPE_CD1 =  '" . $tabledata['FURI_SAKI_TYPE_CD1'] . "'" . "\r\n";
        $strSQL .= " ,FURI_SAKI_TYPE_NAME1 =  '" . $tabledata['FURI_SAKI_TYPE_NAME1'] . "'" . "\r\n";
        //20240228 caina ins s
        $strSQL .= " ,FURI_SAKI_CD1 =  '" . $tabledata['FURI_SAKI_CD1'] . "'" . "\r\n";
        $strSQL .= " ,FURI_SAKI_NAME1 =  '" . $tabledata['FURI_SAKI_NAME1'] . "'" . "\r\n";
        //20240228 caina ins e
        $strSQL .= " ,FURI_SAKI_TYPE_CD2 =  '" . $tabledata['FURI_SAKI_TYPE_CD2'] . "'" . "\r\n";
        $strSQL .= " ,FURI_SAKI_TYPE_NAME2 =  '" . $tabledata['FURI_SAKI_TYPE_NAME2'] . "'" . "\r\n";
        $strSQL .= " ,FURI_SAKI_CD2 =  '" . $tabledata['FURI_SAKI_CD2'] . "'" . "\r\n";
        $strSQL .= " ,FURI_SAKI_NAME2 =  '" . $tabledata['FURI_SAKI_NAME2'] . "'" . "\r\n";
        $strSQL .= " ,UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= " ,UPD_BUSYO_CD = '@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= " ,UPD_SYA_CD = '@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " ,UPD_PRG_ID = 'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,UPD_CLT_NM = '@UPD_CLT_NM'" . "\r\n";
        $strSQL .= "WHERE KAMOK_CD =  '" . $tabledata['KAMOK_CD'] . "'" . "\r\n";
        $strSQL .= "AND SUB_KAMOK_CD =  '" . $tabledata['SUB_KAMOK_CD'] . "'" . "\r\n";
        $KARI_TAX_KBN = '';
        if (isset($tabledata['KASI_TAX_KBN']) && strlen($tabledata['KARI_TAX_KBN']) < 4) {
            $KARI_TAX_KBN = str_pad($tabledata['KARI_TAX_KBN'], 4, "0", STR_PAD_LEFT);
        }
        $strSQL = str_replace("@KARI_TAX_KBN", $KARI_TAX_KBN ? $KARI_TAX_KBN : '', $strSQL);
        $KASI_TAX_KBN = '';
        if (isset($tabledata['KASI_TAX_KBN']) && strlen($tabledata['KASI_TAX_KBN']) < 4) {
            $KASI_TAX_KBN = str_pad($tabledata['KASI_TAX_KBN'], 4, "0", STR_PAD_LEFT);
        }
        $strSQL = str_replace("@KASI_TAX_KBN", $KASI_TAX_KBN ? $KASI_TAX_KBN : '', $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);

        return parent::update($strSQL);
    }
    function InsertSdata($tabledata)
    {
        $strSQL = "";
        $strSQL = "INSERT INTO HDK_MST_SHZKBN " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " TAX_KBN_CD " . "\r\n";
        $strSQL .= " ,TAX_KBN_NAME " . "\r\n";
        $strSQL .= " ,DECLARATION_KBN_CD " . "\r\n";
        $strSQL .= " ,DECLARATION_KBN_NAME " . "\r\n";
        $strSQL .= " ,NICKNAME " . "\r\n";
        $strSQL .= " ,BACKCOLOR " . "\r\n";
        $strSQL .= " ,DISP_CD " . "\r\n";
        $strSQL .= " ,DISP_KBN " . "\r\n";
        $strSQL .= " ,CREATE_DATE" . "\r\n";
        $strSQL .= " ,CRE_SYA_CD" . "\r\n";
        $strSQL .= " ,CRE_PRG_ID" . "\r\n";
        $strSQL .= " ,CRE_CLT_NM" . "\r\n";
        $strSQL .= " ,UPD_DATE" . "\r\n";
        $strSQL .= " ,UPD_SYA_CD" . "\r\n";
        $strSQL .= " ,UPD_PRG_ID" . "\r\n";
        $strSQL .= " ,UPD_CLT_NM" . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  '" . $tabledata['TAX_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TAX_KBN_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['DECLARATION_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['DECLARATION_KBN_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['NICKNAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BACKCOLOR'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['DISP_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['DISP_KBN'] . "' " . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@CRE_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@CRE_CLT_NM'" . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ") ";

        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }

    function InsertTdata($tabledata)
    {
        $strSQL = "";
        $strSQL = "INSERT INTO HDK_MST_TORIHIKISAKI " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " TORIHIKISAKI_CD " . "\r\n";
        $strSQL .= " ,HOUJIN_NO " . "\r\n";
        $strSQL .= " ,TORIHIKISAKI_NAME " . "\r\n";
        $strSQL .= " ,JIGYOUSYO_NM " . "\r\n";
        $strSQL .= " ,TORIHIKISAKI_KANA " . "\r\n";
        $strSQL .= " ,JIGYOUSYO_KANA " . "\r\n";
        $strSQL .= " ,TORIHIKISAKI_INDEX " . "\r\n";
        $strSQL .= " ,START_DATE " . "\r\n";
        $strSQL .= " ,END_DATE " . "\r\n";
        $strSQL .= " ,INVOICE_TOUROKU_KBN_CD " . "\r\n";
        $strSQL .= " ,INVOICE_TOUROKU_KBN " . "\r\n";
        $strSQL .= " ,INVOICE_TOUROKU_NO " . "\r\n";
        $strSQL .= " ,POST_CODE " . "\r\n";
        $strSQL .= " ,TODOUFUKEN " . "\r\n";
        $strSQL .= " ,SIKUCYOUSON " . "\r\n";
        $strSQL .= " ,BANNTI " . "\r\n";
        $strSQL .= " ,BILL_NAME " . "\r\n";
        $strSQL .= " ,TEL " . "\r\n";
        $strSQL .= " ,FAX " . "\r\n";
        $strSQL .= " ,MEMO1 " . "\r\n";
        $strSQL .= " ,MEMO2 " . "\r\n";
        $strSQL .= " ,MEMO3 " . "\r\n";
        $strSQL .= " ,CREATE_DATE" . "\r\n";
        $strSQL .= " ,CRE_SYA_CD" . "\r\n";
        $strSQL .= " ,CRE_PRG_ID" . "\r\n";
        $strSQL .= " ,CRE_CLT_NM" . "\r\n";
        $strSQL .= " ,UPD_DATE" . "\r\n";
        $strSQL .= " ,UPD_SYA_CD" . "\r\n";
        $strSQL .= " ,UPD_PRG_ID" . "\r\n";
        $strSQL .= " ,UPD_CLT_NM" . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  '" . $tabledata['TORIHIKISAKI_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['HOUJIN_NO'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TORIHIKISAKI_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['JIGYOUSYO_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TORIHIKISAKI_KANA'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['JIGYOUSYO_KANA'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TORIHIKISAKI_INDEX'] . "' " . "\r\n";
        $strSQL .= " ,TO_DATE('@startDate','YYYY-MM-DD') " . "\r\n";
        $strSQL .= " ,TO_DATE('@endDate','YYYY-MM-DD') " . "\r\n";
        $strSQL .= " ,'" . $tabledata['INVOICE_TOUROKU_KBN_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['INVOICE_TOUROKU_KBN'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['INVOICE_TOUROKU_NO'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['POST_CODE'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TODOUFUKEN'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['SIKUCYOUSON'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BANNTI'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BILL_NAME'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['TEL'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['FAX'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['MEMO1'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['MEMO2'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['MEMO3'] . "' " . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@CRE_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@CRE_CLT_NM'" . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ") ";

        if ($tabledata['START_DATE'] != '' && $tabledata['START_DATE'] != null) {
            $startDate = date("Y-m-d", $tabledata['START_DATE']);
            $strSQL = str_replace("@startDate", $startDate, $strSQL);
        } else {
            $strSQL = str_replace("@startDate", '', $strSQL);
        }
        if ($tabledata['END_DATE'] != '' && $tabledata['END_DATE'] != null) {
            $endDate = date("Y-m-d", $tabledata['END_DATE']);
            $strSQL = str_replace("@endDate", $endDate, $strSQL);
        } else {
            $strSQL = str_replace("@endDate", '', $strSQL);
        }

        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }
    function InsertBdata($tabledata)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL = "INSERT INTO HDK_MST_BUMON " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " BUSYO_CD " . "\r\n";
        $strSQL .= " ,BUSYO_NM " . "\r\n";
        $strSQL .= " ,BUSYO_KANANM " . "\r\n";
        $strSQL .= " ,BUSYO_KB " . "\r\n";
        $strSQL .= " ,USE_FLG " . "\r\n";
        $strSQL .= " ,USE_FLG_NM " . "\r\n";
        $strSQL .= " ,CREATE_DATE" . "\r\n";
        $strSQL .= " ,CRE_BUSYO_CD" . "\r\n";
        $strSQL .= " ,CRE_SYA_CD" . "\r\n";
        $strSQL .= " ,CRE_PRG_ID" . "\r\n";
        $strSQL .= " ,CRE_CLT_NM" . "\r\n";
        $strSQL .= " ,UPD_DATE" . "\r\n";
        $strSQL .= " ,UPD_BUSYO_CD" . "\r\n";
        $strSQL .= " ,UPD_SYA_CD" . "\r\n";
        $strSQL .= " ,UPD_PRG_ID" . "\r\n";
        $strSQL .= " ,UPD_CLT_NM" . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  '" . $tabledata['BUSYO_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BUSYO_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BUSYO_KANANM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BUSYO_KB'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['USE_FLG'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['USE_FLG_NM'] . "' " . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@CRE_BUSYO_CD'" . "\r\n";
        $strSQL .= " ,'@CRE_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@CRE_CLT_NM'" . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= " ,'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ") ";

        $strSQL = str_replace("@CRE_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }
    function InsertBankdata($tabledata)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL = "INSERT INTO HDK_MST_BANK " . "\r\n";
        $strSQL .= "( " . "\r\n";
        $strSQL .= " BANK_CD " . "\r\n";
        $strSQL .= " ,BRANCH_CD " . "\r\n";
        $strSQL .= " ,BANK_NM " . "\r\n";
        $strSQL .= " ,BANK_KANA " . "\r\n";
        $strSQL .= " ,BRANCH_NM " . "\r\n";
        $strSQL .= " ,BRANCH_KANA " . "\r\n";
        $strSQL .= " ,CREATE_DATE" . "\r\n";
        $strSQL .= " ,CRE_BUSYO_CD" . "\r\n";
        $strSQL .= " ,CRE_SYA_CD" . "\r\n";
        $strSQL .= " ,CRE_PRG_ID" . "\r\n";
        $strSQL .= " ,CRE_CLT_NM" . "\r\n";
        $strSQL .= " ,UPD_DATE" . "\r\n";
        $strSQL .= " ,UPD_BUSYO_CD" . "\r\n";
        $strSQL .= " ,UPD_SYA_CD" . "\r\n";
        $strSQL .= " ,UPD_PRG_ID" . "\r\n";
        $strSQL .= " ,UPD_CLT_NM" . "\r\n";
        $strSQL .= ")  " . "\r\n";
        $strSQL .= "Values( " . "\r\n";
        $strSQL .= "  '" . $tabledata['BANK_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BRANCH_CD'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BANK_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BANK_KANA'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BRANCH_NM'] . "' " . "\r\n";
        $strSQL .= " ,'" . $tabledata['BRANCH_KANA'] . "' " . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@CRE_BUSYO_CD'" . "\r\n";
        $strSQL .= " ,'@CRE_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@CRE_CLT_NM'" . "\r\n";
        $strSQL .= " ,SYSDATE" . "\r\n";
        $strSQL .= " ,'@UPD_BUSYO_CD'" . "\r\n";
        $strSQL .= " ,'@UPD_SYA_CD'" . "\r\n";
        $strSQL .= " ,'OBCDataExpImp'" . "\r\n";
        $strSQL .= " ,'@UPD_CLT_NM'" . "\r\n";
        $strSQL .= ") ";

        $strSQL = str_replace("@CRE_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@CRE_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@CRE_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@UPD_BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@UPD_SYA_CD", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@UPD_CLT_NM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        return parent::insert($strSQL);
    }
    public function Getalldata($tablename)
    {
        $res = parent::select($this->GetalldataSql($tablename));
        return $res;
    }

}
