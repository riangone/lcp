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
namespace App\Model\APPM;

// App::uses('ClsComDb', 'Model/Component');
use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：FrmAPPMMainMenu
// * 関数名	：FrmAPPMMainMenu
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmAPPMMainMenu extends ClsComDb
{
    // //部署のチェック
    // public function fncBusyoKB($strBusyoCD)
    // {
    // $strsql = $this -> fncBusyoKBSql($strBusyoCD);
    // return parent::select($strsql);
    // }
// 
    // //部署チェックのＳＱＬ文
    // function fncBusyoKBSql($strBusyoCD)
    // {
    // $strSQL = "";
    // $strSQL .= " SELECT KYOTN_CD " . "\r\n";
    // $strSQL .= ",       TENPO_CD " . "\r\n";
    // $strSQL .= ",       SVKYOTN_CD " . "\r\n";
    // $strSQL .= " FROM   M27M01 " . "\r\n";
    // $strSQL .= " WHERE KYOTN_CD IN " . "\r\n";
    // $strSQL .= " (SELECT KTN.TENPO_CD " . "\r\n";
    // $strSQL .= "  FROM M27M01 KTN  " . "\r\n";
    // $strSQL .= "  WHERE KTN.HANSH_CD = '3634'  " . "\r\n";
    // $strSQL .= "  AND KTN.ES_KB = 'E'  " . "\r\n";
    // $strSQL .= "  AND KTN.KYOTN_CD = '@BUSYO_CD' ) " . "\r\n";
    // $strSQL .= " AND ES_KB = 'E' " . "\r\n";
    // $strSQL .= " AND HANSH_CD = '3634' " . "\r\n";
// 
    // $strSQL = str_replace("@BUSYO_CD", $strBusyoCD, $strSQL);
    // echo "string= " . $strSQL;
    // return $strSQL;
    // }

    //---20170427 li INS S.
    //PPRのSession 設定のSQL
    function menulistSql9($user_id, $sys_kb)
    {
        $strSQL = "";
        $strSQL .= " SELECT LOG.USER_ID AS USER_ID " . "\r\n";
        $strSQL .= ",       LOG.PATTERN_ID AS PATTERN_ID " . "\r\n";
        $strSQL .= ",       SYA.SYAIN_NM AS SYAIN_NM " . "\r\n";
        $strSQL .= ",       HAI.BUSYO_CD AS BUSYO_CD " . "\r\n";
        $strSQL .= ",       LOG.STYLE_ID AS STYLE_ID " . "\r\n";
        $strSQL .= " FROM   M_LOGIN LOG " . "\r\n";
        $strSQL .= " LEFT JOIN HSYAINMST SYA " . "\r\n";
        $strSQL .= " ON     SYA.SYAIN_NO = LOG.USER_ID " . "\r\n";
        $strSQL .= " LEFT JOIN HHAIZOKU HAI " . "\r\n";
        $strSQL .= " ON     SYA.SYAIN_NO = HAI.SYAIN_NO " . "\r\n";
        $strSQL .= " AND    HAI.START_DATE <= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " AND    NVL(HAI.END_DATE,'99999999') >= TO_CHAR(SYSDATE,'YYYYMMDD') " . "\r\n";
        $strSQL .= " WHERE USER_ID = '@LoginID' " . "\r\n";
        $strSQL .= " AND SYS_KB = '@SYS_KB' " . "\r\n";

        $strSQL = str_replace("@LoginID", $user_id, $strSQL);
        $strSQL = str_replace("@SYS_KB", $sys_kb, $strSQL);
        return $strSQL;
    }

    //APPMANAGEのSession 設定
    public function getmenulist9($user_id, $sys_kb)
    {
        $res = parent::select($this->menulistSql9($user_id, $sys_kb));
        return $res;
    }

    public function menu($STYLE_ID, $PATTERN_ID, $SYS_KB)
    {
        return parent::select($this->menuSql($STYLE_ID, $PATTERN_ID, $SYS_KB));
    }

    function menuSql($STYLE_ID, $PATTERN_ID, $SYS_KB)
    {
        $strSql = "SELECT  VW.KAISOU_ID1";
        $strSql = $strSql . " , VW.KAISOU_ID2";
        $strSql = $strSql . " , VW.KAISOU_ID3";
        $strSql = $strSql . " , VW.KAISOU_ID4";
        $strSql = $strSql . " , VW.KAISOU_ID5";
        $strSql = $strSql . " , VW.KAISOU_ID6";
        $strSql = $strSql . " , VW.KAISOU_ID7";
        $strSql = $strSql . " , VW.KAISOU_ID8";
        $strSql = $strSql . " , VW.KAISOU_ID9";
        $strSql = $strSql . " , VW.KAISOU_ID10";
        $strSql = $strSql . " , VW.KAISOU_NM";
        $strSql = $strSql . " , VW.PRO_NO";
        $strSql = $strSql . " , PRO.PRO_NM";
        $strSql = $strSql . " , PRO.PRO_ID";

        $strSql = $strSql . " FROM ( ";
        $strSql = $strSql . " SELECT  MAX(KAI.PRO_NO) PRO_NO";
        $strSql = $strSql . " , MAX(KAI.KAISOU_NM) KAISOU_NM";
        $strSql = $strSql . " , KAI.KAISOU_ID1";
        $strSql = $strSql . " , KAI.KAISOU_ID2";
        $strSql = $strSql . " , KAI.KAISOU_ID3";
        $strSql = $strSql . " , KAI.KAISOU_ID4";
        $strSql = $strSql . " , KAI.KAISOU_ID5";
        $strSql = $strSql . " , KAI.KAISOU_ID6";
        $strSql = $strSql . " , KAI.KAISOU_ID7";
        $strSql = $strSql . " , KAI.KAISOU_ID8";
        $strSql = $strSql . " , KAI.KAISOU_ID9";
        $strSql = $strSql . " , KAI.KAISOU_ID10";

        $strSql = $strSql . "  FROM HMENUKAISOUMST KAI";
        $strSql = $strSql . ",(";
        $strSql = $strSql . " Select  KAISOU.STYLE_ID";
        $strSql = $strSql . " , KAISOU.PRO_NO";
        $strSql = $strSql . " , KAISOU.KAISOU_ID1";
        $strSql = $strSql . " , KAISOU.KAISOU_ID2";
        $strSql = $strSql . " , KAISOU.KAISOU_ID3";
        $strSql = $strSql . " , KAISOU.KAISOU_ID4";
        $strSql = $strSql . " , KAISOU.KAISOU_ID5";
        $strSql = $strSql . " , KAISOU.KAISOU_ID6";
        $strSql = $strSql . " , KAISOU.KAISOU_ID7";
        $strSql = $strSql . " , KAISOU.KAISOU_ID8";
        $strSql = $strSql . " , KAISOU.KAISOU_ID9";
        $strSql = $strSql . " , KAISOU.KAISOU_ID10";

        $strSql = $strSql . "  FROM HMENUKAISOUMST KAISOU";
        $strSql = $strSql . ", HMENUKANRIPATTERN PAT";
        $strSql = $strSql . "  WHERE(KAISOU.PRO_NO = PAT.PRO_NO)";
        $strSql = $strSql . "  AND KAISOU.STYLE_ID = PAT.STYLE_ID";

        $strSql = $strSql . "  AND PAT.STYLE_ID ='$STYLE_ID'";
        $strSql = $strSql . "  AND PAT.PATTERN_ID ='$PATTERN_ID'";
        $strSql = $strSql . "  AND KAISOU.SYS_KB ='$SYS_KB'";
        $strSql = $strSql . "  AND PAT.SYS_KB ='$SYS_KB'";

        $strSql = $strSql . ") V ";

        $strSql = $strSql . "  WHERE(KAI.KAISOU_ID1 = V.KAISOU_ID1)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID2 = V.KAISOU_ID2 OR KAI.KAISOU_ID2 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID3 = V.KAISOU_ID3 OR KAI.KAISOU_ID3 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID4 = V.KAISOU_ID4 OR KAI.KAISOU_ID4 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID5 = V.KAISOU_ID5 OR KAI.KAISOU_ID5 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID6 = V.KAISOU_ID6 OR KAI.KAISOU_ID6 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID7 = V.KAISOU_ID7 OR KAI.KAISOU_ID7 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID8 = V.KAISOU_ID8 OR KAI.KAISOU_ID8 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID9 = V.KAISOU_ID9 OR KAI.KAISOU_ID9 = 0)";
        $strSql = $strSql . "  AND (KAI.KAISOU_ID10 = V.KAISOU_ID10 OR KAI.KAISOU_ID10 = 0)";
        $strSql = $strSql . "  AND KAI.STYLE_ID = V.STYLE_ID ";
        $strSql = $strSql . "  AND KAI.SYS_KB = '$SYS_KB'";

        $strSql = $strSql . "  GROUP BY KAI.KAISOU_ID1 ";
        $strSql = $strSql . " , KAI.KAISOU_ID2";
        $strSql = $strSql . " , KAI.KAISOU_ID3";
        $strSql = $strSql . " , KAI.KAISOU_ID4";
        $strSql = $strSql . " , KAI.KAISOU_ID5";
        $strSql = $strSql . " , KAI.KAISOU_ID6";
        $strSql = $strSql . " , KAI.KAISOU_ID7";
        $strSql = $strSql . " , KAI.KAISOU_ID8";
        $strSql = $strSql . " , KAI.KAISOU_ID9";
        $strSql = $strSql . " , KAI.KAISOU_ID10";
        $strSql = $strSql . " ) VW ";
        $strSql = $strSql . " LEFT JOIN HPROGRAMMST PRO";
        $strSql = $strSql . " ON VW.PRO_NO = PRO.PRO_NO";
        $strSql = $strSql . " AND PRO.SYS_KB = '$SYS_KB'";

        $strSql = $strSql . " ORDER BY ";
        $strSql = $strSql . " VW.KAISOU_ID1";
        $strSql = $strSql . ",VW.KAISOU_ID2";
        $strSql = $strSql . ",VW.KAISOU_ID3";
        $strSql = $strSql . ",VW.KAISOU_ID4";
        $strSql = $strSql . ",VW.KAISOU_ID5";
        $strSql = $strSql . ",VW.KAISOU_ID6";
        $strSql = $strSql . ",VW.KAISOU_ID7";
        $strSql = $strSql . ",VW.KAISOU_ID8";
        $strSql = $strSql . ",VW.KAISOU_ID9";
        $strSql = $strSql . ",VW.KAISOU_ID10";
        //getFields();
        return $strSql;
    }

    //---20170427 li INS E.

}
