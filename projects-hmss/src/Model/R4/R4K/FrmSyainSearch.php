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

class FrmSyainSearch extends ClsComDb
{
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $ClsComFnc;

    function fncDataSetSql($postData = NULL)
    {

        $this->ClsComFnc = new ClsComFnc();

        $strSQL = "SELECT";

        $strSQL .= "     NVL(BUS.BUSYO_CD,'') AS BUSYOCD";

        $strSQL .= "    ,NVL(BUS.BUSYO_NM,'') AS BUSYONM";

        $strSQL .= "    ,NVL(SYA.SYAIN_NO,'') AS SYAINNO";

        $strSQL .= "    ,NVL(SYA.SYAIN_NM,'') AS SYAINNM";

        $strSQL .= " FROM HSYAINMST SYA";

        $strSQL .= " INNER JOIN HHAIZOKU HAI";

        $strSQL .= " ON    HAI.SYAIN_NO = SYA.SYAIN_NO";

        $strSQL .= " INNER JOIN HBUSYO BUS";

        $strSQL .= " ON    BUS.BUSYO_CD = HAI.BUSYO_CD";

        $strSQL .= " WHERE ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')";

        $strSQL .= " AND   SYA.TAISYOKU_DATE IS NULL";

        if (trim($postData['txtBusyoCD']) != '') {
            $strSQL .= "   AND HAI.BUSYO_CD LIKE '@BUSYOCD%'";
        }

        if (trim($postData['txtSyainCD']) != '') {
            $strSQL .= "   AND SYA.SYAIN_NO LIKE '@SYAINNO%'";
        }

        if (trim($postData['txtSyainKN']) != '') {
            $strSQL .= "   AND SYA.SYAIN_KN LIKE '@SYAINKN%'";
        }

        $strSQL .= " ORDER BY HAI.BUSYO_CD";

        $strSQL = str_replace("@BUSYOCD", $this->ClsComFnc->FncNv($postData['txtBusyoCD']), $strSQL);
        $strSQL = str_replace("@SYAINNO", $this->ClsComFnc->FncNv($postData['txtSyainCD']), $strSQL);
        $strSQL = str_replace("@SYAINKN", $this->ClsComFnc->FncNv($postData['txtSyainKN']), $strSQL);

        return $strSQL;
    }

    public function fncDataSet($postData = NULL)
    {
        $strSql = $this->fncDataSetSql($postData);
        return parent::select($strSql);
    }

}