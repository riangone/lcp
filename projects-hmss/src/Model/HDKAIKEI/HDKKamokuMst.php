<?php
/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                             内容                               担当
 * YYYYMMDD           #ID                                     XXXXXX                            FCSDL
 * 20240228           20240213_機能改善要望対応 NO6    「科目マスタの使用フラグ、使用フラグ名は撤廃」        caina
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
class HDKKamokuMst extends ClsComDb
{
    public $SessionComponent;
    public $ClsComFncHDKAIKEI = null;
    function getRelationSql($postData)
    {
        $strSQL = " SELECT DISTINCT";
        $strSQL .= "        RELATION_CD";
        $strSQL .= ",       RELATION_NM";
        $strSQL .= ",       TO_CHAR(R.UPD_DATE,'YYYY/MM/DD HH24:MI:SS') AS UPD_DATE";
        $strSQL .= " FROM ";
        $strSQL .= "        KAMOKU_RELATION R ";
        $strSQL .= "   LEFT JOIN HDK_MST_KAMOKU K ON K.PARENT_ID= R.RELATION_CD" . "\r\n";
        $strSQL .= " WHERE  ";
        $strSQL .= "        DEL_FLG <> '1' ";

        if (trim($postData['txtRelationName']) != '') {
            $strSQL .= " AND  R.RELATION_NM      LIKE '%@RELATION_NM%' ";
            $strSQL = str_replace("@RELATION_NM", $postData['txtRelationName'], $strSQL);
        }

        if (trim($postData['txtKamokuCD']) != '') {
            $strSQL .= " AND  K.KAMOK_CD      = '@KAMOKUCODE'";

            $strSQL = str_replace("@KAMOKUCODE", $postData['txtKamokuCD'], $strSQL);
        }
        $strSQL .= " ORDER BY ";
        $strSQL .= "        RELATION_CD";

        return $strSQL;
    }

    //科目データの取得
    function fncGetKamokuListSql($postData)
    {
        $strSQL = " SELECT DISTINCT ";
        $strSQL .= "        KAMOK_CD";
        $strSQL .= ",       KAMOK_NAME";
        $strSQL .= ",       PARENT_ID";
        $strSQL .= ",       CASE PARENT_ID WHEN @PARENT_ID THEN 0 ELSE 1 END AS KAMOK_ORDER";
        $strSQL .= " FROM ";
        $strSQL .= "        HDK_MST_KAMOKU ";
        $strSQL .= " WHERE ";
        //20240228 caina upd s
        // $strSQL .= "    USE_FLG = '1'";
        $strSQL .= "    1 = 1";
        //20240228 caina upd e

        $strSQL .= " ORDER BY ";
        $strSQL .= "      KAMOK_ORDER,  KAMOK_CD";

        $strSQL = str_replace("@PARENT_ID", $postData['relationCD'], $strSQL);

        return $strSQL;
    }

    //関係名データを取得Sql
    public function getRelation($postData)
    {
        $strSql = $this->getRelationSql($postData);

        return parent::select($strSql);
    }

    //科目データの取得Sql
    public function fncGetKamokuList($postData)
    {
        $strSql = $this->fncGetKamokuListSql($postData);

        return parent::select($strSql);
    }

    function CheckKamokuData($postData)
    {
        $strSQL = " ";
        $strSQL .= " SELECT KAMOK_CD ";
        $strSQL .= " FROM HDK_MST_KAMOKU ";
        $strSQL .= " WHERE KAMOK_CD IN (@CHECKSTR) ";
        $strSQL .= " AND PARENT_ID <> @PARENT_ID ";

        $strSQL = str_replace("@CHECKSTR", $postData['checkStr'], $strSQL);
        $strSQL = str_replace("@PARENT_ID", $postData['relationCD'], $strSQL);

        return parent::select($strSQL);
    }

    function CheckRelationData($postData)
    {
        $strSQL = " SELECT ";
        $strSQL .= "        RELATION_CD";
        $strSQL .= ",       TO_CHAR(UPD_DATE,'YYYY/MM/DD HH24:MI:SS') AS UPD_DATE";
        $strSQL .= " FROM ";
        $strSQL .= "        KAMOKU_RELATION ";
        $strSQL .= " WHERE  ";
        $strSQL .= "        DEL_FLG <> '1' ";

        $strSQL .= " AND    RELATION_CD = '@RELATION_CD' ";

        $strSQL = str_replace("@RELATION_CD", $postData['relationCD'], $strSQL);

        return parent::select($strSQL);
    }
    function SelectMaxRelation()
    {
        $strSQL = " SELECT ";
        $strSQL .= "    DECODE(MAX(RELATION_CD),null,0,MAX(RELATION_CD)) + 1 AS MAX_CD ";
        $strSQL .= " FROM ";
        $strSQL .= " KAMOKU_RELATION ";

        return parent::select($strSQL);
    }

    function InsertRelation($postData)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = "";
        $strSQL .= "INSERT INTO KAMOKU_RELATION (" . "\r\n";
        $strSQL .= "RELATION_CD," . "\r\n";
        $strSQL .= "RELATION_NM," . "\r\n";
        $strSQL .= "CREATE_DATE," . "\r\n";
        $strSQL .= "CRE_BUSYO_CD," . "\r\n";
        $strSQL .= "CRE_SYA_CD," . "\r\n";
        $strSQL .= "CRE_PRG_ID," . "\r\n";
        $strSQL .= "CRE_CLT_NM," . "\r\n";
        $strSQL .= "UPD_DATE," . "\r\n";
        $strSQL .= "UPD_BUSYO_CD," . "\r\n";
        $strSQL .= "UPD_SYA_CD," . "\r\n";
        $strSQL .= "UPD_PRG_ID," . "\r\n";
        $strSQL .= "UPD_CLT_NM," . "\r\n";
        $strSQL .= "DEL_FLG" . "\r\n";
        $strSQL .= ") VALUES (" . "\r\n";
        $strSQL .= "@RELATION_CD," . "\r\n";
        $strSQL .= "'@RELATION_NM'," . "\r\n";
        $strSQL .= "SYSDATE," . "\r\n";
        $strSQL .= "'@BUSYO_CD'," . "\r\n";
        $strSQL .= "'@LoginID'," . "\r\n";
        $strSQL .= "'HDKKamokuMst'," . "\r\n";
        $strSQL .= "'@MachineNM'," . "\r\n";
        $strSQL .= "SYSDATE," . "\r\n";
        $strSQL .= "'@BUSYO_CD'," . "\r\n";
        $strSQL .= "'@LoginID'," . "\r\n";
        $strSQL .= "'HDKKamokuMst'," . "\r\n";
        $strSQL .= "'@MachineNM'," . "\r\n";
        $strSQL .= "'0'" . "\r\n";
        $strSQL .= ")" . "\r\n";

        $strSQL = str_replace("@BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@RELATION_CD", $postData['relationCD'], $strSQL);
        $strSQL = str_replace("@RELATION_NM", $postData['relationName'], $strSQL);

        return parent::insert($strSQL);
    }

    function UpdateRelation($postData, $DorU)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = " UPDATE KAMOKU_RELATION ";
        $strSQL .= " SET		RELATION_NM = '@RELATION_NM' ";
        $strSQL .= ",		UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",		UPD_BUSYO_CD = '@BUSYO_CD'" . "\r\n";
        $strSQL .= ",		UPD_SYA_CD = '@LoginID'" . "\r\n";
        $strSQL .= ",		UPD_PRG_ID = 'HDKKamokuMst'" . "\r\n";
        $strSQL .= ",		UPD_CLT_NM = '@MachineNM'" . "\r\n";
        if ($DorU == 'delete') {
            $strSQL .= ",		DEL_DATE = SYSDATE" . "\r\n";
            $strSQL .= ",		DEL_SYA_CD = '@LoginID'" . "\r\n";
            $strSQL .= ",		DEL_PRG_ID = 'HDKKamokuMst'" . "\r\n";
            $strSQL .= ",		DEL_CLT_NM = '@MachineNM'" . "\r\n";
            $strSQL .= ",		DEL_FLG = '1'" . "\r\n";
        }
        $strSQL .= " WHERE RELATION_CD = @RELATION_CD ";

        $strSQL = str_replace("@BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@RELATION_CD", $postData['relationCD'], $strSQL);
        $strSQL = str_replace("@RELATION_NM", $postData['relationName'], $strSQL);

        return parent::update($strSQL);
    }

    function UpdateKamoku($postData, $DorU)
    {
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = " UPDATE HDK_MST_KAMOKU ";
        if ($DorU == 'delete') {
            $strSQL .= " SET		PARENT_ID = NULL ";
        } else {
            $strSQL .= " SET		PARENT_ID = '@RELATION_CD' ";
        }
        $strSQL .= ",		UPD_DATE = SYSDATE" . "\r\n";
        $strSQL .= ",		UPD_BUSYO_CD = '@BUSYO_CD'" . "\r\n";
        $strSQL .= ",		UPD_SYA_CD = '@LoginID'" . "\r\n";
        $strSQL .= ",		UPD_PRG_ID = 'HDKKamokuMst'" . "\r\n";
        $strSQL .= ",		UPD_CLT_NM = '@MachineNM'" . "\r\n";
        if ($DorU == 'delete') {
            $strSQL .= " WHERE PARENT_ID = @RELATION_CD ";
        } else {
            $strSQL .= " WHERE KAMOK_CD IN (@CHECKSTR) ";
            $strSQL = str_replace("@CHECKSTR", $postData['checkStr'], $strSQL);
        }

        $strSQL = str_replace("@BUSYO_CD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@LoginID", $this->GS_LOGINUSER['strUserID'], $strSQL);
        $strSQL = str_replace("@MachineNM", $this->GS_LOGINUSER['strClientNM'], $strSQL);
        $strSQL = str_replace("@RELATION_CD", $postData['relationCD'], $strSQL);

        return parent::update($strSQL);
    }

}
