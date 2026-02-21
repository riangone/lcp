<?php
// 共通クラスの読込み

namespace App\Model\R4\R4G;

use App\Model\Component\ClsComDb;


//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmTRKDownLoadFS extends ClsComDb
{
    //取込処理が終了しているかﾁｪｯｸする    sql
    function selectSql()
    {
        $strsql = "SELECT BEF_GET_DT FROM M_DATARECEP WHERE TABLE_ID = '5'";
        return $strsql;
    }

    //取込処理が終了しているかﾁｪｯｸする
    public function select_data()
    {
        return parent::select($this->selectSql());
    }

    //HFTS_TARNSFER_LISTにINSERTする
    public function fncInsHFTSTRANSFERLIST($postData)
    {
        return parent::Do_Execute($postData);
    }

    //ﾌｧｲﾙ名
    public function fncgetclient()
    {
        $UPD_CLT_NM = $this->GS_LOGINUSER['strClientNM'];
        return $UPD_CLT_NM;
    }

}
