<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE080ExhibitionSearch extends ClsComDb
{
    function FncGetSql_SYAYIN($postData)
    {
        $strSQL = "";
        $strSQL .= "SELECT IVENT.START_DATE " . "\r\n";
        $strSQL .= ", IVENT.END_DATE" . "\r\n";
        $strSQL .= ", TO_CHAR(TO_DATE(IVENT.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') || '～'" . "\r\n";
        $strSQL .= "|| TO_CHAR(TO_DATE(IVENT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') KIKAN" . "\r\n";
        $strSQL .= ",IVENT.IVENT_NM" . "\r\n";
        $strSQL .= "FROM HDTIVENTDATA IVENT" . "\r\n";

        if ($postData['ddlExhibitDay'] <> '') {
            $strSQL .= "WHERE  TO_CHAR(TO_DATE(IVENT.START_DATE,'YYYY/MM/DD'),'YYYY') = '@ddlExhibitDay'" . "\r\n";
        }

        if ($postData['mmExhibitDay'] <> '') {
            $strSQL .= " AND TO_CHAR(TO_DATE(IVENT.START_DATE,'YYYY/MM/DD'),'MM') = '@mmExhibitDay'" . "\r\n";
        }
        //仕様変更2008/01/12順位を並びますstrSQL.Append(" ORDER BY 1 ")
        $strSQL .= " ORDER BY 1 " . "\r\n";

        $strSQL = str_replace("@ddlExhibitDay", $postData['ddlExhibitDay'], $strSQL);
        $strSQL = str_replace("@mmExhibitDay", $postData['mmExhibitDay'], $strSQL);

        return $strSQL;
    }

    public function btnView_Click($postData)
    {
        $strSql = $this->FncGetSql_SYAYIN($postData);

        return parent::select($strSql);
    }

}
