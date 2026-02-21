<?php
// 共通クラスの読込み
// App::uses('ClsComDb', 'Model/R4');
namespace App\Model\R4\Component;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：ClsComDLSql
// * 処理説明：共通関数
//*************************************

class ClsComDLSql extends ClsComDb
{
    // 20131004 kamei add Start
    // 解放が必要な変数をメンバーに設定
    protected $conn_orl = "";
    protected $Sel_Array = "";
    public $number_of_rows = "";
    // 20131004 kamei add end

    //**********************************************************************
    //処 理 名：引数で指定したﾃｰﾌﾞﾙの列名とﾀｲﾌﾟを取得するSQL
    //関 数 名：fncTableNameGetSQL
    //引    数：グループID
    //戻 り 値：SQL文
    //処理説明：引数で指定したグループIDのﾃｰﾌﾞﾙ名を取得
    //**********************************************************************
    public function fncTableNameGetSQL($strGroupID)
    {
        $strSQL = "";
        $strSQL .= " SELECT TABLE_NM, KEY, TABLE_ID" . "\r\n";
        $strSQL .= " FROM   DL_DATA" . "\r\n";
        $strSQL .= " WHERE  GROUP_ID = '@GROUPID'" . "\r\n";
        $strSQL = str_replace("@GROUPID", $strGroupID, $strSQL);
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：引数で指定したﾃｰﾌﾞﾙの列名とﾀｲﾌﾟを取得するSQL
    //関 数 名：fncColumnNameGet
    //引    数：ﾃｰﾌﾞﾙ名
    //戻 り 値：SQL文
    //処理説明：引数で指定したﾃｰﾌﾞﾙの列名とﾀｲﾌﾟを取得するSQL
    //**********************************************************************
    public function fncColumnNameGet($strTableNM)
    {
        $strSQLName = "";
        $strSQLName .= " SELECT DISTINCT COLUMN_NAME,DATA_TYPE" . "\r\n";
        $strSQLName .= " FROM ALL_TAB_COLUMNS" . "\r\n";
        $strSQLName .= " WHERE TABLE_NAME = '@TABLENAME'" . "\r\n";
        $strSQLName .= " ORDER BY COLUMN_NAME" . "\r\n";
        $strSQLName = str_replace("@TABLENAME", $strTableNM, $strSQLName);
        return $strSQLName;
    }

    //2009/11/19 INS Start
    //**********************************************************************
    //処 理 名：ダウンロード対象テーブルを取得する。親テーブルに当たるテーブルを先に処理できるように並び替える。
    //　　　　：※依存リレーションテーブルは１階層しか考えていない。親のテーブルが子テーブルになるようなことはないということで考えてある。
    //関 数 名：fncTableNameGet
    //引    数：グループID
    //戻 り 値：SQL文
    //処理説明：引数で指定したグループIDのﾃｰﾌﾞﾙ名を取得
    //**********************************************************************
    public function fncTableNameGetRel($strGroupID, $strDLKind)
    {
        $strSQL = "";

        $strSQL .= "SELECT KIND,TABLE_NM,KEY,TABLE_ID,PARENT_TABLE_NM, OYA_DL_TABLEID, OYA_KEY" . "\r\n";
        $strSQL .= "";
        $strSQL .= " FROM (" . "\r\n";
        //親テーブルに該当するテーブル
        $strSQL .= " SELECT 1 KIND, DL.TABLE_NM, DL.KEY, DG.TABLE_ID,NULL PARENT_TABLE_NM, 0 OYA_DL_TABLEID, NULL OYA_KEY" . "\r\n";
        $strSQL .= " FROM   DL_DATA DL" . "\r\n";
        $strSQL .= " INNER JOIN RCT_DL_GROUP DG" . "\r\n";
        $strSQL .= " ON     DG.GROUP_ID = DL.GROUP_ID" . "\r\n";
        $strSQL .= " AND    DG.DL_KIND = '@DL_KIND'" . "\r\n";
        $strSQL .= " INNER JOIN RCT_DEPEND_REL_TBL REL" . "\r\n";
        $strSQL .= " ON     DL.TABLE_ID = REL.PARENT_TABLE_NM" . "\r\n";
        $strSQL .= " WHERE  DL.GROUP_ID = '@GROUPID'" . "\r\n";
        $strSQL .= " UNION ALL" . "\r\n";
        //親でも子でもないテーブル
        $strSQL .= " SELECT 2 KIND, DL.TABLE_NM, DL.KEY, DG.TABLE_ID,NULL, 0, NULL" . "\r\n";
        $strSQL .= " FROM   DL_DATA DL" . "\r\n";
        $strSQL .= " INNER JOIN RCT_DL_GROUP DG" . "\r\n";
        $strSQL .= " ON     DG.GROUP_ID = DL.GROUP_ID" . "\r\n";
        $strSQL .= " AND    DG.DL_KIND = '@DL_KIND'" . "\r\n";
        $strSQL .= " LEFT JOIN RCT_DEPEND_REL_TBL REL" . "\r\n";
        $strSQL .= " ON     DL.TABLE_ID = REL.PARENT_TABLE_NM" . "\r\n";
        $strSQL .= " LEFT JOIN RCT_DEPEND_REL_TBL CHI" . "\r\n";
        $strSQL .= " ON     DL.TABLE_ID = CHI.CHILD_TABLE_NM" . "\r\n";
        $strSQL .= " WHERE  (REL.PARENT_TABLE_NM IS NULL" . "\r\n";
        $strSQL .= " AND    CHI.CHILD_TABLE_NM IS NULL)" . "\r\n";
        $strSQL .= " AND    DL.GROUP_ID = '@GROUPID'" . "\r\n";
        $strSQL .= " UNION ALL" . "\r\n";
        //子テーブルに該当するテーブル
        $strSQL .= " SELECT 3 KIND, DL.TABLE_NM, DL.KEY, DG.TABLE_ID,REL.PARENT_TABLE_NM,OYA_DL.TABLE_ID OYA_DL_TABLEID, OYA_DL.KEY OYA_KEY" . "\r\n";
        $strSQL .= " FROM   DL_DATA DL" . "\r\n";
        $strSQL .= " INNER JOIN RCT_DL_GROUP DG" . "\r\n";
        $strSQL .= " ON     DG.GROUP_ID = DL.GROUP_ID" . "\r\n";
        $strSQL .= " AND    DG.DL_KIND = '@DL_KIND'" . "\r\n";
        $strSQL .= " INNER JOIN RCT_DEPEND_REL_TBL REL" . "\r\n";
        $strSQL .= " ON     DL.TABLE_ID = REL.CHILD_TABLE_NM" . "\r\n";
        $strSQL .= " INNER JOIN DL_DATA OYA_DL" . "\r\n";
        $strSQL .= " ON     OYA_DL.TABLE_ID = REL.PARENT_TABLE_NM" . "\r\n";
        $strSQL .= " WHERE  DL.GROUP_ID = '@GROUPID'" . "\r\n";
        $strSQL .= " ) V" . "\r\n";
        $strSQL .= "ORDER BY KIND,TABLE_ID" . "\r\n";

        $strSQL = str_replace("@GROUPID", $strGroupID, $strSQL);
        $strSQL = str_replace("@DL_KIND", $strDLKind, $strSQL);

        return $strSQL;
    }

    //*************************************
    // * 公開メソッド
    //*************************************

}
?>