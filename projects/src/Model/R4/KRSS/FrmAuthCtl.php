<?php
/**
 * 説明：
 *
 *
 * @author li
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
// 共通クラスの読込み
namespace App\Model\R4\KRSS;

use App\Model\Component\ClsComDb;
use App\Model\R4\Component\ClsComFncKRSS;

class FrmAuthCtl extends ClsComDb
{
    //部署情報を抽出する
    public function fncGetBusyosql()
    {
        $strSQL = "";
        $strSQL .= "SELECT BUSYO_CD ";
        $strSQL .= ", BUSYO_NM ";
        $strSQL .= "  FROM ";
        $strSQL .= "  HBUSYO ";
        $strSQL .= "  WHERE ";
        $strSQL .= "  SYUKEI_KB IS NULL OR  SYUKEI_KB <> '1' ";
        return $strSQL;
    }

    public function fncGetBusyo()
    {
        return parent::select($this->fncGetBusyosql());
    }

    //明細情報を抽出する
    public function fncListSelSQL($postData)
    {
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= " SELECT DISTINCT" . "\r\n";
        $strSQL .= "     SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "     ,SYA.SYAIN_NM " . "\r\n";
        $strSQL .= "     ,(CASE WHEN ACTL.SYAIN_NO IS NULL THEN " . "\r\n";
        $strSQL .= "         '未' " . "\r\n";
        $strSQL .= "       WHEN ACTL.SYAIN_NO IS NOT NULL THEN " . "\r\n";
        $strSQL .= "         '済' " . "\r\n";
        $strSQL .= "       END) STATE " . "\r\n";
        $strSQL .= "     ,HAIZOKU.START_DATE " . "\r\n";
        $strSQL .= "     ,HAIZOKU.END_DATE " . "\r\n";
        $strSQL .= "     ,HAIZOKU.BUSYO_CD " . "\r\n";
        $strSQL .= "     ,BU.BUSYO_NM " . "\r\n";
        $strSQL .= "     FROM HAUTHORITY_CTL ACTL " . "\r\n";
        $strSQL .= "     ,HSYAINMST      SYA " . "\r\n";
        $strSQL .= "     ,HHAIZOKU       HAIZOKU " . "\r\n";

        $strSQL .= "     ,HBUSYO         BU " . "\r\n";
        $strSQL .= " WHERE SYA.SYAIN_NO =ACTL.SYAIN_NO(+) " . "\r\n";
        $strSQL .= "     AND ACTL.SYS_KB(+) = '@SYS_KB'" . "\r\n";

        $strSQL .= "     AND HAIZOKU.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= "     AND NVL(HAIZOKU.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";

        $strSQL .= "     AND HAIZOKU.SYAIN_NO=SYA.SYAIN_NO " . "\r\n";
        $strSQL .= "     AND HAIZOKU.BUSYO_CD=BU.BUSYO_CD " . "\r\n";
        $strSQL .= "     AND SYA.TAISYOKU_DATE IS NULL " . "\r\n";

        //1:      .画面([明細情報].社員番号に値が入っている場合)
        if ($postData['txtSyainCDFrom'] != "") {
            $strSQL .= "     AND SYA.SYAIN_NO = '@SYAIN_NO'" . "\r\n";
        }
        //2:      .画面([明細情報].社員番号ｶﾅに値が入っている場合)
        if ($postData['txtSyainKana'] != "") {
            $strSQL .= "     AND SYA.SYAIN_KN LIKE '%@SYAIN_KN%'" . "\r\n";
        }

        //3:      .画面([明細情報].部署コードに値が入っている場合)
        if ($postData['txtBusyouCD'] != "") {
            $strSQL .= "     AND HAIZOKU.BUSYO_CD = '@BUSYO_CD'" . "\r\n";
        }

        $strSQL .= " ORDER BY STATE,SYA.SYAIN_NO " . "\r\n";

        $strSQL = str_replace("@SYAIN_NO", $postData['txtSyainCDFrom'], $strSQL);
        $strSQL = str_replace("@SYAIN_KN", $postData['txtSyainKana'], $strSQL);
        $strSQL = str_replace("@BUSYO_CD", $postData['txtBusyouCD'], $strSQL);
        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);

        return $strSQL;
    }

    public function fncListSel($postData)
    {
        return parent::select($this->fncListSelSQL($postData));
    }

    //明細情報を削除する
    public function fncDeletDataSQL($SYAINNO)
    {
        $ClsComFncKRSS = new ClsComFncKRSS();
        $strSQL = "";
        $strSQL .= " DELETE FROM HAUTHORITY_CTL " . "\r\n";
        $strSQL .= " WHERE SYAIN_NO = '" . $SYAINNO . "'" . "\r\n";
        $strSQL .= " AND   SYS_KB = '@SYS_KB'" . "\r\n";
        $strSQL = str_replace("@SYS_KB", $ClsComFncKRSS::GSYSTEM_KB, $strSQL);
        return $strSQL;
    }

    public function fncDeletData($SYAINNO)
    {
        $sqlstr = $this->fncDeletDataSQL($SYAINNO);
        return parent::delete($sqlstr);
    }

}
