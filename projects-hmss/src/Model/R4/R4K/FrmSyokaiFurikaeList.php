<?php
// 共通クラスの読込み
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmSyokaiFurikaeList extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";

    function fncDeleteFurikaeSql($postData = NULL)
    {
        $strSQL = "";
        $strSQL .= "DELETE FROM HSTAFFCHUSYOKAI" . "\r\n";
        $strSQL .= "WHERE  KEIJO_DT = '@KEIJYO'" . "\r\n";
        $strSQL .= "AND    DENPY_NO = '@DENPYO'" . "\r\n";

        $strSQL = str_replace("@KEIJYO", str_replace("/", "", $postData['KEIJYO']), $strSQL);
        $strSQL = str_replace("@DENPYO", $postData['DENPYO'], $strSQL);
        return $strSQL;
    }

    function fncSearchFurikaeSql($postData = NULL)
    {

        $ym = $postData['KEIJYOBI'];
        $y = substr($ym, 0, 4);
        $m = substr($ym, 5, 2);
        // $m1 = (int) $m;
        // $d = cal_days_in_month(CAL_GREGORIAN, $m1, $y);
        $d = date("t", strtotime($y . '-' . $m));
        $ymd = $y . $m . $d;

        $strWHERE = " WHERE ";
        $strSQL = "";
        $strSQL .= "SELECT CHU.KEIJO_DT" . "\r\n";
        $strSQL .= ",      CHU.DENPY_NO" . "\r\n";
        $strSQL .= ",      HAI.BUSYO_CD" . "\r\n";
        $strSQL .= ",      CHU.MOT_SYAIN_NO" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= ",      SUM(CHU.KEIJO_GK) GOUKEI" . "\r\n";
        $strSQL .= "FROM   HSTAFFCHUSYOKAI CHU" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = CHU.MOT_SYAIN_NO" . "\r\n";
        $strSQL .= "LEFT JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON      HAI.SYAIN_NO = CHU.MOT_SYAIN_NO" . "\r\n";
        $strSQL .= "AND     HAI.START_DATE <= '" . $ymd . "'" . "\r\n";
        $strSQL .= "AND     NVL(HAI.END_DATE,'99999999') >= '" . $ymd . "'" . "\r\n";

        if (rtrim($postData['KEIJYOBI']) != '') {
            $strSQL .= $strWHERE;
            $strSQL .= "   CHU.KEIJO_DT = '@KEIJYOBI'";
            $strWHERE = "AND";
        }
        if (rtrim($postData['DENPYOF']) != '') {
            $strSQL .= $strWHERE;
            $strSQL .= "   CHU.DENPY_NO >= '@DENPYOF'";
            $strWHERE = "AND";
        }
        if (rtrim($postData['DENPYOT']) != '') {
            $strSQL .= $strWHERE;
            $strSQL .= "   CHU.DENPY_NO <= '@DENPYOT'";
            $strWHERE = "AND";
        }
        if (rtrim($postData['SYAINNO']) != '') {
            $strSQL .= $strWHERE;
            $strSQL .= "   CHU.MOT_SYAIN_NO = '@SYAINNO'";
        }
        $strSQL .= "GROUP BY CHU.KEIJO_DT" . "\r\n";
        $strSQL .= ",      CHU.DENPY_NO" . "\r\n";
        $strSQL .= ",      HAI.BUSYO_CD" . "\r\n";
        $strSQL .= ",      CHU.MOT_SYAIN_NO" . "\r\n";
        $strSQL .= ",      SYA.SYAIN_NM" . "\r\n";
        $strSQL .= "ORDER BY CHU.KEIJO_DT, CHU.DENPY_NO" . "\r\n";

        $strSQL = str_replace("@KEIJYOBI", str_replace("/", "", $postData['KEIJYOBI']), $strSQL);
        $strSQL = str_replace("@DENPYOF", $postData['DENPYOF'], $strSQL);
        $strSQL = str_replace("@DENPYOT", $postData['DENPYOT'], $strSQL);
        $strSQL = str_replace("@SYAINNO", $postData['SYAINNO'], $strSQL);
        return $strSQL;
    }

    function frmFurikae_LoadSql()
    {

        $strSQL = "SELECT ID" . "\r\n";

        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";

        $strSQL .= "   FROM  HKEIRICTL" . "\r\n";

        $strSQL .= "  WHERE  ID = '01'" . "\r\n";

        return $strSQL;
    }

    public function frmFurikae_Load()
    {
        $strSql = $this->frmFurikae_LoadSql();
        return parent::select($strSql);
    }

    public function fncSearchFurikae($postData = NULL)
    {
        $strSql = $this->fncSearchFurikaeSql($postData);
        return parent::select($strSql);
    }

    public function fncDeleteFurikae($postData = NULL)
    {
        $strSql = $this->fncDeleteFurikaeSql($postData);
        return parent::delete($strSql);
    }

}