<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE370MLOGINList extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    //検索SQL
    function searchSQL($SYSKB, $postData)
    {
        $strSql = "";
        $strSql = $strSql . " SELECT SYA.SYAIN_NO ";
        $strSql = $strSql . " ,      SYA.SYAIN_NM ";
        $strSql = $strSql . " ,      PTN.PATTERN_NM ";
        $strSql = $strSql . " ,      (CASE WHEN LOG.USER_ID IS NOT NULL THEN '済' ELSE '未' END) FLG ";
        $strSql = $strSql . " FROM   HSYAINMST SYA ";
        $strSql = $strSql . " LEFT JOIN M_LOGIN LOG ";
        $strSql = $strSql . " ON     LOG.USER_ID = SYA.SYAIN_NO ";
        $strSql = $strSql . " AND    LOG.SYS_KB = '$SYSKB' ";
        $strSql = $strSql . " LEFT JOIN HPATTERNMST PTN ";
        $strSql = $strSql . " ON     PTN.SYS_KB = '$SYSKB' ";
        $strSql = $strSql . " AND    PTN.PATTERN_ID = LOG.PATTERN_ID ";
        $strSql = $strSql . " AND    LOG.STYLE_ID = PTN.STYLE_ID ";
        $strSql = $strSql . " WHERE  NVL(SYA.TAISYOKU_DATE,'99999999') > TO_CHAR(SYSDATE,'YYYYMMDD') ";
        if (trim($postData['txtUserID']) != '') {
            $strSql = $strSql . " AND    SYA.SYAIN_NO = '@USERID' ";
        }
        if (trim($postData['txtSyaYin']) != '') {
            $strSql = $strSql . " AND    SYA.SYAIN_NM LIKE '@SYASIN%' ";
        }
        $strSql = $strSql . " ORDER BY SYA.SYAIN_NO ";

        $strSql = str_replace("@USERID", $postData['txtUserID'], $strSql);
        $strSql = str_replace("@SYASIN", $postData['txtSyaYin'], $strSql);

        return $strSql;
    }

    //データ削除SQL
    function deleteSql($SYSKB, $SYAINCD)
    {
        $strSql = "";
        $strSql = $strSql . " DELETE FROM M_LOGIN ";
        $strSql = $strSql . " WHERE  USER_ID = '@USERID' ";
        $strSql = $strSql . " AND    SYS_KB = '@SYSKB' ";

        $strSql = str_replace("@USERID", $SYAINCD, $strSql);
        $strSql = str_replace("@SYSKB", $SYSKB, $strSql);

        return $strSql;
    }

    //検索ボタンのイベント
    public function btnSearch_Click($SYSKB, $postData)
    {
        return parent::select($this->searchSQL($SYSKB, $postData));
    }

    //データ削除
    public function deleteDataByCD($SYSKB, $SYAINCD)
    {
        return parent::delete($this->deleteSql($SYSKB, $SYAINCD));
    }

}
