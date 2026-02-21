<?php

namespace App\Model\R4\R4K;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFnc;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************

class FrmBusyoSearch extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $ClsComFnc;

    function fncDataSetSql($postData = NULL)
    {

        $this->ClsComFnc = new ClsComFnc();

        $strSQL = "SELECT";

        $strSQL .= "     NVL(BUSYO_CD,'') AS BUSYOCD";

        $strSQL .= "    ,NVL(BUSYO_NM,'') AS BUSYONM";

        $strSQL .= "    ,NVL(KKR_BUSYO_CD,'') as KKRCD";

        $strSQL .= " FROM HBUSYO";

        $strSQL .= " WHERE ( SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1')";

        if (trim($postData['txtBusyoNM']) != '') {
            $strSQL .= "   AND BUSYO_NM LIKE '@BUSYO%'";
        }

        if (trim($postData['txtBusyoCD']) != '') {
            $strSQL .= "   AND BUSYO_CD LIKE '@CD%'";
        }

        $strSQL .= " ORDER BY BUSYO_CD";

        $strSQL = str_replace("@BUSYO", $this->ClsComFnc->FncNv($postData['txtBusyoNM']), $strSQL);
        $strSQL = str_replace("@CD", $this->ClsComFnc->FncNv($postData['txtBusyoCD']), $strSQL);

        return $strSQL;
    }

    public function fncDataSet($postData = NULL)
    {
        $strSql = $this->fncDataSetSql($postData);
        return parent::select($strSql);
    }

}