<?php
namespace App\Model\R4\R4K;

// 共通クラスの読込み
use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmWDTSImportFS extends ClsComDb
{
    public function fncGetTableID($strDBLink, $const_dl_kind)
    {
        $sqlstr = $this->fncGetTableID_sql($strDBLink, $const_dl_kind);
        return parent::select($sqlstr);
    }

    public function fncGetTableID_sql($strDBLink, $const_dl_kind)
    {
        $sqlstr = "";
        $sqlstr .= "SELECT DL_KIND\n";
        $sqlstr .= ",      GROUP_ID\n";
        $sqlstr .= ",      TABLE_ID\n";
        $sqlstr .= "FROM   RCT_DL_GROUP" . $strDBLink . "\n";

        $sqlstr .= "WHERE  DL_KIND = '@DL_KIND'\n";

        $sqlstr = str_replace("@DL_KIND", $const_dl_kind, $sqlstr);

        return $sqlstr;
    }

    //取込処理が終了しているかﾁｪｯｸする   Sql
    function selectSql($strDBLink)
    {
        $strSQL = "SELECT BEF_GET_DT     FROM   M_DATARECEP@DBLINK   WHERE  TABLE_ID = '5'";
        $strSQL = str_replace("@DBLINK", $strDBLink, $strSQL);

        return $strSQL;
    }

    //取込処理が終了しているかﾁｪｯｸする
    public function fncGetDate($strDBLink)
    {
        return parent::select($this->selectSql($strDBLink));
    }

    //コンピュータ名取得
    public function fncGetClient()
    {
        $UPD_CLT_NM = $this->GS_LOGINUSER['strClientNM'];
        return $UPD_CLT_NM;
    }

    //HFTS_TARNSFER_LISTにINSERTする
    public function fncInsHFTSTRANSFERLIST($fncInsHFTS_TRANSFER_LISTSQL)
    {
        return parent::Do_Execute($fncInsHFTS_TRANSFER_LISTSQL);
    }

}