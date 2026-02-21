<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmKaikei extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";

    function fncSelectSql($postData = NULL, $start = "", $limit = "")
    {

        // $this -> ClsComFnc = new ClsComFnc();
        $strWHERE = "WHERE";
        $strSQL = "";

        $strSQL .= "SELECT KEIJO_DT" . "\r\n";
        $strSQL .= ",      DENPY_NO" . "\r\n";
        $strSQL .= ",      SYOHY_NO" . "\r\n";
        $strSQL .= ",      L_BUSYO_CD" . "\r\n";
        $strSQL .= ",      L_KAMOK_CD" . "\r\n";
        $strSQL .= ",      L_KOMOK_CD" . "\r\n";
        $strSQL .= ",      L_HIMOK_CD" . "\r\n";
        $strSQL .= ",      R_BUSYO_CD" . "\r\n";
        $strSQL .= ",      R_KAMOK_CD" . "\r\n";
        $strSQL .= ",      R_KOMOK_CD" . "\r\n";
        $strSQL .= ",      R_HIMOK_CD" . "\r\n";
        $strSQL .= ",      KEIJO_GK" . "\r\n";
        $strSQL .= ",      TEKIYO1" . "\r\n";
        $strSQL .= ",      HASEI_MOTO_KB" . "\r\n";
        $strSQL .= ",      GYO_NO" . "\r\n";
        $strSQL .= ",      R_BK" . "\r\n";
        $strSQL .= ",      R_UC_NO" . "\r\n";
        $strSQL .= ",      INP_BUSYO" . "\r\n";
        $strSQL .= ",      L_BK" . "\r\n";
        $strSQL .= ",      L_UC_NO" . "\r\n";
        $strSQL .= "FROM   HKAIKEI" . "\r\n";

        if ($postData['KEIJYOBI'] != '') {
            $strSQL .= $strWHERE . " KEIJO_DT = '@KEIJYOBI'" . "\r\n";
            $strWHERE = "AND";
        }

        if ($postData['DENPYOF'] != '' && $postData['DENPYOT'] != '') {
            $strSQL .= $strWHERE . " DENPY_NO BETWEEN '@DENPYOF' AND '@DENPYOT'" . "\r\n";
        }

        $strSQL .= "ORDER BY KEIJO_DT" . "\r\n";

        $cell = "*";
        if (trim($start) != "") {
            $start = " WHERE RNM >" . $start;
        }
        if (trim($limit) != "") {
            $limit = " WHERE ROWNUM<=" . $limit;
        }
        $strSQL = "SELECT " . $cell . " FROM (SELECT TBL." . $cell . ",ROWNUM RNM FROM ( " . $strSQL . ") TBL " . $limit . ") " . $start;

        $strSQL = str_replace("@KEIJYOBI", str_replace("/", "", $postData['KEIJYOBI']), $strSQL);
        $strSQL = str_replace("@DENPYOF", $postData['DENPYOF'], $strSQL);
        $strSQL = str_replace("@DENPYOT", $postData['DENPYOT'], $strSQL);

        return $strSQL;
    }

    function fncSelectCountSql($postData = NULL)
    {

        // $this -> ClsComFnc = new ClsComFnc();
        $strWHERE = "WHERE";
        $strSQL = "";

        $strSQL .= "SELECT" . "\r\n";
        $strSQL .= "		count(*) as cnt   " . "\r\n";
        $strSQL .= "FROM   HKAIKEI" . "\r\n";

        if ($postData['KEIJYOBI'] != '') {
            $strSQL .= $strWHERE . " KEIJO_DT = '@KEIJYOBI'" . "\r\n";
            $strWHERE = "AND";
        }

        if ($postData['DENPYOF'] != '' && $postData['DENPYOT'] != '') {
            $strSQL .= $strWHERE . " DENPY_NO BETWEEN '@DENPYOF' AND '@DENPYOT'" . "\r\n";
        }

        $strSQL .= "ORDER BY KEIJO_DT" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", str_replace("/", "", $postData['KEIJYOBI']), $strSQL);
        $strSQL = str_replace("@DENPYOF", $postData['DENPYOF'], $strSQL);
        $strSQL = str_replace("@DENPYOT", $postData['DENPYOT'], $strSQL);

        return $strSQL;
    }

    public function fncDeleteFurikaeSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HKAIKEI" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJYO'" . "\r\n";
        $strSQL .= "AND    DENPY_NO = '@DENPYO'" . "\r\n";
        $strSQL .= "AND    GYO_NO = '@GYO_NO'" . "\r\n";

        $strSQL = str_replace("@KEIJYO", str_replace("/", "", $postData['KEIJYO']), $strSQL);
        $strSQL = str_replace("@DENPYO", $postData['DENPYO'], $strSQL);
        $strSQL = str_replace("@GYO_NO", $postData['GYO_NO'], $strSQL);

        return $strSQL;

    }

    public function ControlCheckSql()
    {
        $strSQL = "";

        $strSQL .= "SELECT ID FROM HKEIRICTL WHERE ID = '01'" . "\r\n";

        return $strSQL;

    }

    public function fncDeleteFurikae($postData = NULL)
    {
        $strSql = $this->fncDeleteFurikaeSql($postData);
        return parent::delete($strSql);
    }

    public function ControlCheck()
    {
        $strSql = $this->ControlCheckSql();
        return parent::select($strSql);
    }

    public function fncSelect($postData = NULL, $sortStr = "", $start = "", $limit = "")
    {
        if ($sortStr == "" && $start == "" && $limit == "") {
            $strSql = $this->fncSelectCountSql($postData);

        } else {
            $strSql = $this->fncSelectSql($postData, $start, $limit);

        }
        return parent::select($strSql);
    }

}