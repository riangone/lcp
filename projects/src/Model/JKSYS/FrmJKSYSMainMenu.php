<?php
/**
 * 説明：
 *
 *
 * @author yuan
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
// App::uses('ClsComDb', 'Model/Component');
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：FrmJKSYSMainMenu
// * 関数名	：FrmJKSYSMainMenu
// * 処理説明	：共通クラスの読込み
//*************************************
class FrmJKSYSMainMenu extends ClsComDb
{
    //部署のチェック
    public function fncBusyoKB($strBusyoCD)
    {
        $strsql = $this->fncBusyoKBSql($strBusyoCD);
        return parent::select($strsql);
    }

    //部署チェックのＳＱＬ文
    function fncBusyoKBSql($strBusyoCD)
    {
        $strSQL = "";
        $strSQL .= " SELECT KYOTN_CD " . "\r\n";
        $strSQL .= ",       TENPO_CD " . "\r\n";
        $strSQL .= ",       SVKYOTN_CD " . "\r\n";
        $strSQL .= " FROM   M27M01 " . "\r\n";
        $strSQL .= " WHERE KYOTN_CD IN " . "\r\n";
        $strSQL .= " (SELECT KTN.TENPO_CD " . "\r\n";
        $strSQL .= "  FROM M27M01 KTN  " . "\r\n";
        $strSQL .= "  WHERE KTN.HANSH_CD = '3634'  " . "\r\n";
        $strSQL .= "  AND KTN.ES_KB = 'E'  " . "\r\n";
        $strSQL .= "  AND KTN.KYOTN_CD = '@BUSYO_CD' ) " . "\r\n";
        $strSQL .= " AND ES_KB = 'E' " . "\r\n";
        $strSQL .= " AND HANSH_CD = '3634' " . "\r\n";

        $strSQL = str_replace("@BUSYO_CD", $strBusyoCD, $strSQL);
        return $strSQL;
    }

    //---20170710 li INS S.
    //JKSYSのSession 設定のSQL
    function menulistSql($user_id)
    {
        $strSql = "SELECT STYLE_ID, PATTERN_ID FROM M_LOGIN WHERE SYS_KB = '6' AND USER_ID = '$user_id'";
        return $strSql;
    }

    //JKSYSのSession 設定
    public function getmenulist($user_id)
    {
        $res = parent::select($this->menulistSql($user_id));
        return $res;
    }

    //JKSYSのmenu 設定
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
        return $strSql;
    }

    public function select($sql)
    {
        if ($GLOBALS['connection'] instanceof \mysqli)
            $result = mysqli_query($GLOBALS['connection'], $sql);
        return $result;
    }

    //根据sys_key,查询sys_cd
    public function getSysKb()
    {
        return $this->select($this->getSysKbSql());
    }

    public function getSysKbSql()
    {
        $strSql = "SELECT sys_cd FROM system_m WHERE sys_key='JKSYS'";
        // echo $strSql;
        return $strSql;
    }

    //社員情報最終取込日時の取得
    public function subSelSyainInfo()
    {
        $res = parent::select($this->subSelSyainInfoSQL());
        return $res;
    }

    //社員情報最終取込日時の取得SQL
    public function subSelSyainInfoSQL()
    {
        $strSql = " SELECT TO_CHAR(MAX(CREATE_DATE),'YYYY/MM/DD HH24:MI:SS') NENGETU " . "\r\n";
        $strSql .= " FROM   JKSYAIN ";

        return $strSql;
    }

    //給与賞与情報最終取込日時の取得
    public function subSelKyuyoInfo()
    {
        $res = parent::select($this->subSelKyuyoInfoSQL());
        return $res;
    }

    //給与賞与情報最終取込日時の取得SQL
    public function subSelKyuyoInfoSQL()
    {
        $strSql = "  SELECT * " . "\r\n";
        $strSql .= " FROM  ( " . "\r\n";
        $strSql .= "          SELECT SUBSTR(TAISYOU_YM,1,4) || '年' || SUBSTR(TAISYOU_YM,5,2) || '月' AS NENGETU " . "\r\n";
        $strSql .= "          FROM  JKSHIKYU " . "\r\n";
        $strSql .= "          ORDER BY TAISYOU_YM DESC " . "\r\n";
        $strSql .= "       ) " . "\r\n";
        $strSql .= " WHERE rownum = 1 ";

        return $strSql;
    }

    //評価情報最終取込日時の取得
    public function subSelHyoukaInfo()
    {
        $res = parent::select($this->subSelHyoukaInfoSQL());
        return $res;
    }

    //評価情報最終取込日時の取得SQL
    public function subSelHyoukaInfoSQL()
    {
        $strSql = "  SELECT * " . "\r\n";
        $strSql .= " FROM  ( " . "\r\n";
        $strSql .= "          SELECT SUBSTR(JISSHI_YM,1,4) || '年' || SUBSTR(JISSHI_YM,5,2) || '月' AS NENGETU " . "\r\n";
        $strSql .= "          FROM  JKHYOUKARIREKI " . "\r\n";
        $strSql .= "          ORDER BY JISSHI_YM DESC " . "\r\n";
        $strSql .= "       ) " . "\r\n";
        $strSql .= " WHERE rownum = 1 ";

        return $strSql;
    }

    //エラー内容の取得
    public function subTrkErrDisp()
    {
        $res = parent::select($this->subTrkErrDispSQL());
        return $res;
    }

    //エラー内容の取得SQL
    public function subTrkErrDispSQL()
    {
        $strSql = "  SELECT * " . "\r\n";
        $strSql .= " FROM  ( " . "\r\n";
        $strSql .= "          SELECT TO_CHAR(TO_DATE(START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') START_DATE " . "\r\n";
        $strSql .= "          ,      TO_CHAR(TO_DATE(START_TIME,'HH24:MI:SS'),'HH24:MI:SS') START_TIME " . "\r\n";
        $strSql .= "          ,      MESSAGE " . "\r\n";
        $strSql .= "          ,      PARA1 " . "\r\n";
        $strSql .= "          ,      PARA2 " . "\r\n";
        $strSql .= "          ,      PARA3 " . "\r\n";
        $strSql .= "          ,      STATE " . "\r\n";
        $strSql .= "          FROM   HFTS_TRANSFER_LIST " . "\r\n";
        $strSql .= "          WHERE  STATE NOT IN ('0','1') " . "\r\n";
        $strSql .= "          AND    KAKUNIN = '0' " . "\r\n";
        $strSql .= "          ORDER BY STATE DESC, START_DATE DESC, START_TIME DESC ";
        $strSql .= "       ) " . "\r\n";
        $strSql .= " WHERE rownum = 1 ";

        return $strSql;
    }

}