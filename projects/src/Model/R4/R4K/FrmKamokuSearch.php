<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmKamokuSearch extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $ClsComFnc;

    function fncDataSetSql($postData = NULL)
    {

        $this->ClsComFnc = new ClsComFnc();

        $strSQL = "";

        $strSQL .= "SELECT KAMOK_CD KAMOKUCD, KAMOK_NM KAMOKUNM" . "\r\n";
        $strSQL .= "FROM   M_KAMOKU A" . "\r\n";
        $strSQL .= "WHERE  NVL(KOMOK_CD,'00') = (SELECT MIN(NVL(KOMOK_CD,'00')) FROM M_KAMOKU B WHERE A.KAMOK_CD = B.KAMOK_CD GROUP BY B.KAMOK_CD)" . "\r\n";

        if (trim($postData['txtNM']) != '') {
            $strSQL .= "AND    KAMOK_NM LIKE '@KAMOK%'" . "\r\n";
        }

        if (trim($postData['txtCD']) != '') {
            $strSQL .= "AND    KAMOK_CD LIKE '@CD%'" . "\r\n";
        }

        $strSQL .= " ORDER BY KAMOKUCD";

        $strSQL = str_replace("@KAMOK", $this->ClsComFnc->FncNv($postData['txtNM']), $strSQL);
        $strSQL = str_replace("@CD", $this->ClsComFnc->FncNv($postData['txtCD']), $strSQL);

        return $strSQL;
    }

    public function fncDataSet($postData = NULL)
    {
        $strSql = $this->fncDataSetSql($postData);

        return parent::select($strSql);
    }

}