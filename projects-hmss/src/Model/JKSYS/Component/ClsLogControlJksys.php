<?php
// 共通クラスの読込み
namespace App\Model\JKSYS\Component;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsLogControl
// * 処理説明：共通関数
//*************************************

class ClsLogControlJksys extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    // protected $conn_orl = "";
    // protected $Sel_Array = "";
    // 20131004 kamei add end

    //*************************************
    // * SQL文
    //*************************************

    public function insertSqlEntrySql($tblArr)
    {
        $strsql = "INSERT INTO HSYSTEMLOGDATA ";
        $strsql .= "( ID ";
        $strsql .= ", SYS_KB ";
        $strsql .= ", OUT_USER_ID ";
        $strsql .= ", OUT_DATE ";
        $strsql .= ", CREATE_DATE ";

        $strsql .= ", OUT_CLT_NM ";
        $strsql .= ", OUT_PRG_ID ";
        $strsql .= ", REC_CNT ";
        $strsql .= ", STATE_FLG ";
        $strsql .= ", OUT_CNT ";
        $strsql .= ", ITEM1 ";
        $strsql .= ", ITEM2 ";
        $strsql .= ", ITEM3 ";
        $strsql .= ", ITEM4 ";
        $strsql .= ", ITEM5 ";
        $strsql .= ", ITEM6 ";
        $strsql .= ", ITEM7 ";
        $strsql .= ", ITEM8 ";
        $strsql .= ", ITEM9 ";
        $strsql .= ", ITEM10 ";

        $strsql .= ", ITEM11 ";
        $strsql .= ", ITEM12 ";
        $strsql .= ", ITEM13 ";
        $strsql .= ", ITEM14 ";
        $strsql .= ", ITEM15 ";
        $strsql .= ", ITEM16 ";
        $strsql .= ", ITEM17 ";
        $strsql .= ", ITEM18 ";
        $strsql .= ", ITEM19 ";
        $strsql .= ", ITEM20) ";

        $strsql .= " VALUES ";
        $strsql .= "( '" . $tblArr['ID'] . "'";
        $strsql .= ", '" . $tblArr['SYS_KB'] . "'";
        $strsql .= ", '" . $tblArr['OUT_USER_ID'] . "'";

        $strsql .= ", '" . $tblArr['OUT_DATE'] . "'";
        $strsql .= ", SYSDATE ";

        $strsql .= ", '" . $tblArr['OUT_CLT_NM'] . "'";
        $strsql .= ", '" . $tblArr['OUT_PRG_ID'] . "'";
        $strsql .= ", '" . $tblArr['REC_CNT'] . "'";
        $strsql .= ", '" . $tblArr['STATE_FLG'] . "'";
        $strsql .= ", '" . $tblArr['OUT_CNT'] . "'";

        $strsql .= ", '" . $tblArr['ITEM01'] . "'";
        $strsql .= ", '" . $tblArr['ITEM02'] . "'";
        $strsql .= ", '" . $tblArr['ITEM03'] . "'";
        $strsql .= ", '" . $tblArr['ITEM04'] . "'";
        $strsql .= ", '" . $tblArr['ITEM05'] . "'";
        $strsql .= ", '" . $tblArr['ITEM06'] . "'";
        $strsql .= ", '" . $tblArr['ITEM07'] . "'";
        $strsql .= ", '" . $tblArr['ITEM08'] . "'";
        $strsql .= ", '" . $tblArr['ITEM09'] . "'";
        $strsql .= ", '" . $tblArr['ITEM10'] . "'";

        $strsql .= ", '" . $tblArr['ITEM11'] . "'";
        $strsql .= ", '" . $tblArr['ITEM12'] . "'";
        $strsql .= ", '" . $tblArr['ITEM13'] . "'";
        $strsql .= ", '" . $tblArr['ITEM14'] . "'";
        $strsql .= ", '" . $tblArr['ITEM15'] . "'";
        $strsql .= ", '" . $tblArr['ITEM16'] . "'";
        $strsql .= ", '" . $tblArr['ITEM17'] . "'";
        $strsql .= ", '" . $tblArr['ITEM18'] . "'";
        $strsql .= ", '" . $tblArr['ITEM19'] . "'";
        $strsql .= ", '" . $tblArr['ITEM20'] . "') ";
        return $strsql;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function fncEntrySql($tblArr)
    {
        return parent::insert($this->insertSqlEntrySql($tblArr));
    }

    /*************************************
     * 処理名	：関数終了時処理
     * 関数名	：finally
     * 引数		：無し
     * 戻り値		：無し
     * 処理説明	：register_shutdown_functionで呼び出された関数の終了後に実行する処理
     *************************************/
    function finally()
    {
        if (isset($this->Sel_Array)) {
            if ($this->Sel_Array['Pra_sta'] != false) {
                oci_free_statement($this->Sel_Array['Pra_info']);
            }
        }

        if (isset($this->conn_orl)) {
            if ($this->conn_orl['conn_sta'] != false) {
                oci_close($this->conn_orl['conn_orl']);
            }

        }

        unset($this->Sel_Array);
        unset($this->conn_orl);
    }

    // 20131004 kamei add end
}