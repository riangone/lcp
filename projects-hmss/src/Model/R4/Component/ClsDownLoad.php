<?php
// 共通クラスの読込み

namespace App\Model\R4\Component;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsDownLoad
// * 処理説明：共通関数
//*************************************

class ClsDownLoad extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $number_of_rows = "";
    // 20131004 kamei add end

    public function fncGetBEFGETDTSQL($strTableId)
    {
        $strSQL = "";
        //strSQL.Append("SELECT TO_CHAR(BEF_GET_DT,'YYYY/MM/DD HH24,MI,SS')" & vbCrLf)
        //2007/08/07 UPD Start   午前9時の場合"9"となりREC_UPD_DTをTO_CHARしたときの文字変換と一致せず、抽出できてなかったため変更
        //strSQL.Append("SELECT BEF_GET_DT" & vbCrLf)
        $strSQL .= "SELECT TO_CHAR(BEF_GET_DT,'YYYY/MM/DD HH24:MI:SS') BEF_GET_DT" . "\r\n";
        //2007/08/07 UPD End
        $strSQL .= "  FROM M_DATARECEP" . "\r\n";
        $strSQL .= " WHERE TABLE_ID = '" . $strTableId . "'" . "\r\n";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：データ受信テーブルの更新
    //関 数 名：fncUpdateDataRecepSQL
    //引    数：strgetdate
    //戻 り 値：SQL文
    //理説明：データ受信テーブルの更新(ID='5'で更新)
    //**********************************************************************
    public function fncUpdateDataRecepSQL($strgetdate)
    {
        $strSQL = "";
        $strSQL .= " UPDATE M_DATARECEP";
        $strSQL .= " SET    BEF_GET_DT = TO_DATE('" . $strgetdate . "','YYYY-MM-DD HH24:MI:SS')";
        $strSQL .= " WHERE  TABLE_ID = '5'";
        return $strSQL;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

    public function update($strsql = NULL)
    {
        return parent::update($strsql);
    }

    public function fncGetBEFGETDT($strTableId)
    {
        return parent::select($this->fncGetBEFGETDTSQL($strTableId));
    }

    public function fncUpdateDataRecep($strgetdate)
    {
        return parent::update($this->fncUpdateDataRecepSQL($strgetdate));
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