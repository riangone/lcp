<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：車種マスタメンテナンス
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE320HDTSYASYUList extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //検索SQL
    function searchSQL($postData)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT SYASYU_CD";
        $strSql = $strSql . ", SYASYU_NM";
        $strSql = $strSql . ", SYASYU_RYKNM";
        $strSql = $strSql . ", SYASYU_KB";
        $strSql = $strSql . " FROM   HDTSYASYU";

        if (trim($postData['txtNumber']) != '' && trim($postData['txtName']) != '') {
            $strSql = $strSql . " WHERE  SYASYU_CD LIKE  '@SYASYUCD%'";
            $strSql = $strSql . " AND SYASYU_NM LIKE '@SYASYUNM%'";
        }
        if (trim($postData['txtNumber']) == '' && trim($postData['txtName']) != '') {
            $strSql = $strSql . " WHERE SYASYU_NM LIKE '@SYASYUNM%'";
        }
        if (trim($postData['txtNumber']) != '' && trim($postData['txtName']) == '') {
            $strSql = $strSql . " WHERE  SYASYU_CD LIKE  '@SYASYUCD%'";
        }
        $strSql = $strSql . " ORDER BY 1 ";

        $strSql = str_replace("@SYASYUCD", $postData['txtNumber'], $strSql);
        $strSql = str_replace("@SYASYUNM", $postData['txtName'], $strSql);

        return $strSql;
    }

    //データ削除SQL
    function deleteSql($syasyuCD)
    {
        $strSql = "";
        $strSql = $strSql . " DELETE FROM HDTSYASYU ";
        $strSql = $strSql . " WHERE  SYASYU_CD = '@SYASYUCD%' ";

        $strSql = str_replace("@SYASYUCD%", $syasyuCD, $strSql);

        return $strSql;
    }

    //検索ボタンのイベント
    public function btnSearch_Click($postData)
    {
        return parent::select($this->searchSQL($postData));
    }

    //データ削除
    public function deleteDataByCD($syasyuCD)
    {
        return parent::delete($this->deleteSql($syasyuCD));
    }

}
