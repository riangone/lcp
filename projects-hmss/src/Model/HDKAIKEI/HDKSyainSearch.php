<?php
// 共通クラスの読込み
namespace App\Model\HDKAIKEI;
// 共通クラスの読込み
use App\Model\Component\ClsComDb;
use App\Model\HDKAIKEI\Component\ClsComFncHDKAIKEI;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HDKSyainSearch extends ClsComDb
{
    public $ClsComFncHDKAIKEI = null;
    function FncGetSql_SYAYIN($postData)
    {

        $this->ClsComFncHDKAIKEI = new ClsComFncHDKAIKEI();

        $strSQL = "SELECT";
        $strSQL .= "     SYA.SYAIN_NO";
        $strSQL .= ",    SYA.SYAIN_NM";
        $strSQL .= " FROM";
        $strSQL .= "      HSYAINMST SYA";
        $strSQL .= " LEFT JOIN ";
        $strSQL .= "      HHAIZOKU HAI";
        $strSQL .= " ON ";
        $strSQL .= "      HAI.SYAIN_NO = SYA.SYAIN_NO";
        $strSQL .= " AND ";
        $strSQL .= "      HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD')";
        $strSQL .= " AND ";
        $strSQL .= "      NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD')";
        $strSQL .= " WHERE ";
        $strSQL .= "      SYA.TAISYOKU_DATE IS NULL";
        if ($postData['txtSyainNO'] != '') {
            $strSQL .= "   AND   SYA.SYAIN_NO      = '@SYAIN_NO'";
            $strSQL = str_replace("@SYAIN_NO", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSyainNO']), $strSQL);
        }
        if ($postData['txtSyainNM'] != '') {
            $strSQL .= "   AND   SYA.SYAIN_NM      LIKE '@SYAIN_NM%'";
            $strSQL = str_replace("@SYAIN_NM", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSyainNM']), $strSQL);
        }
        if ($postData['txtSyainKN'] != '') {
            $strSQL .= "   AND   SYA.SYAIN_KN      LIKE '@SYAIN_KN%'";
            $strSQL = str_replace("@SYAIN_KN", $this->ClsComFncHDKAIKEI->FncNv($postData['txtSyainKN']), $strSQL);
        }
        if ($postData['txtBusyoCD'] != '') {
            $strSQL .= "   AND   HAI.BUSYO_CD      = '@BUSYO_CD'";
            $strSQL = str_replace("@BUSYO_CD", $this->ClsComFncHDKAIKEI->FncNv($postData['txtBusyoCD']), $strSQL);
        }
        $strSQL .= "  ORDER BY ";
        $strSQL .= "       SYA.SYAIN_NO ";

        return $strSQL;
    }

    //社員データを取得
    public function btnHyouji_Click($postData)
    {
        $strSql = $this->FncGetSql_SYAYIN($postData);

        return parent::select($strSql);
    }

}