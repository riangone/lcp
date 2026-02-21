<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240227           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」        caina
 * 20240507				QA42																		LQS
 * -------------------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKKamokuSearch extends ClsComDb
{

    public $ClsComFncHDKAIKEI = null;
    function FncGetSql_KAMOKU($postData)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = " SELECT DISTINCT ";
        $strSQL .= "        KAMOK_CD";
        $strSQL .= ",       SUB_KAMOK_CD";
        $strSQL .= ",       KAMOK_NAME";
        $strSQL .= ",       SUB_KAMOK_NAME";
        $strSQL .= ",       PARENT_ID";
        $strSQL .= " FROM ";
        $strSQL .= "        HDK_MST_KAMOKU ";
        $strSQL .= " WHERE ";
        //20240227 caina UPD s
        // $strSQL .= "    USE_FLG = '1'";
        $strSQL .= "        1=1 ";
        //20240227 caina UPD e

        if (trim($postData['txtKamokuCode']) != '') {
            $strSQL .= " AND   KAMOK_CD      LIKE '@KAMOKUCODE%'";
            $strSQL = str_replace("@KAMOKUCODE", $this->ClsComFncHDKAIKEI->FncNv($postData['txtKamokuCode']), $strSQL);
        }

        if (trim($postData['txtSubkoumokuCode']) != '') {
            // 20240507 LQS UPD S
            // $strSQL .= " AND   SUB_KAMOK_CD      = '@SUBKOUMOKUCODE' ";
            $strSQL .= " AND   SUB_KAMOK_CD      LIKE '@SUBKOUMOKUCODE%' ";
            // 20240507 LQS UPD E
            $strSQL = str_replace("@SUBKOUMOKUCODE", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSubkoumokuCode']), $strSQL);
        }

        if (trim($postData['txtKamokuName']) != '') {
            $strSQL .= " AND   KAMOK_NAME    LIKE '%@KAMOKUNAME%'";
            $strSQL = str_replace("@KAMOKUNAME", $this->ClsComFncHDKAIKEI->FncNv($postData['txtKamokuName']), $strSQL);
        }
        if (trim($postData['txtSubkoumokuName']) != '') {
            $strSQL .= " AND   SUB_KAMOK_NAME      LIKE '%@SUBKOUMOKUNAME%' ";
            $strSQL = str_replace("@SUBKOUMOKUNAME", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSubkoumokuName']), $strSQL);
        }
        $strSQL .= " ORDER BY ";
        if ($postData['mode'] == 'normal') {
            $strSQL .= "        KAMOK_CD,SUB_KAMOK_CD";
        } else {
            $strSQL .= "        PARENT_ID ASC NULLS LAST,KAMOK_CD,SUB_KAMOK_CD";
        }

        return $strSQL;
    }

    function GetTreeParentSql($postData)
    {
        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = " SELECT DISTINCT";
        $strSQL .= "        RELATION_CD";
        $strSQL .= ",        COUNT(K.KAMOK_CD) AS COUNTSON";
        $strSQL .= ",       RELATION_NM AS KAMOK_CD";
        $strSQL .= " FROM ";
        $strSQL .= "        KAMOKU_RELATION R ";
        $strSQL .= "   LEFT JOIN HDK_MST_KAMOKU K ON K.PARENT_ID= R.RELATION_CD" . "\r\n";
        $strSQL .= " WHERE  ";
        //20240227 caina UPD s
        // $strSQL .= "    K.USE_FLG = '1'";
        // $strSQL .= " AND  R.DEL_FLG <> '1'";
        $strSQL .= "    R.DEL_FLG <> '1'";
        //20240227 caina UPD e

        if (trim($postData['txtKamokuCode']) != '') {
            $strSQL .= " AND  K.KAMOK_CD      LIKE '@KAMOKUCODE%'";

            $strSQL = str_replace("@KAMOKUCODE", $this->ClsComFncHDKAIKEI->FncNv($postData['txtKamokuCode']), $strSQL);
        }

        if (trim($postData['txtSubkoumokuCode']) != '') {
            // 20240507 LQS UPD S
            // $strSQL .= " AND  K.SUB_KAMOK_CD      = '@SUBKOUMOKUCODE' ";
            $strSQL .= " AND  K.SUB_KAMOK_CD      LIKE '@SUBKOUMOKUCODE%' ";
            // 20240507 LQS UPD E
            $strSQL = str_replace("@SUBKOUMOKUCODE", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSubkoumokuCode']), $strSQL);
        }

        if (trim($postData['txtKamokuName']) != '') {
            $strSQL .= " AND  K.KAMOK_NAME    LIKE '%@KAMOKUNAME%'";
            $strSQL = str_replace("@KAMOKUNAME", $this->ClsComFncHDKAIKEI->FncNv($postData['txtKamokuName']), $strSQL);
        }

        if (trim($postData['txtSubkoumokuName']) != '') {
            $strSQL .= " AND   K.SUB_KAMOK_NAME      LIKE '%@SUBKOUMOKUNAME%' ";
            $strSQL = str_replace("@SUBKOUMOKUNAME", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSubkoumokuName']), $strSQL);
        }
        $strSQL .= " GROUP BY ";
        $strSQL .= "        RELATION_CD,RELATION_NM ";
        $strSQL .= " ORDER BY ";
        $strSQL .= "        RELATION_CD";

        return $strSQL;
    }
    //データを取得
    public function btnHyouji_Click($postData)
    {
        $strSql = $this->FncGetSql_KAMOKU($postData);

        return parent::select($strSql);
    }
    public function GetTreeParent($postData1)
    {
        $strSql = $this->GetTreeParentSql($postData1);

        return parent::select($strSql);
    }

}

