<?php
// 共通クラスの読込み
// App::uses('ClsComDb', 'Model/Component');
namespace App\Model\HMDPS;

use App\Model\Component\ClsComDb;

//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmHMDPSMainMenu extends ClsComDb
{
    //*************************************
    // * SQL文
    //*************************************

    function menuSql($STYLE_ID, $PATTERN_ID, $SYS_KB)
    {
        $strSql = "";
        $strSql = $strSql . "SELECT                                   ";
        $strSql = $strSql . "*                                        ";
        $strSql = $strSql . "FROM                                     ";
        $strSql = $strSql . "(SELECT                                  ";
        $strSql = $strSql . "        KAI.KAISOU_ID1  KAISOU_ID1       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID2  KAISOU_ID2       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID3  KAISOU_ID3       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID4  KAISOU_ID4       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID5  KAISOU_ID5       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID6  KAISOU_ID6       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID7  KAISOU_ID7       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID8  KAISOU_ID8       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID9  KAISOU_ID9       ";
        $strSql = $strSql . ",       KAI.KAISOU_ID10 KAISOU_ID10      ";
        $strSql = $strSql . ",       KAI.KAISOU_NM   KAISOU_NM        ";
        $strSql = $strSql . ",       KAI.PRO_NO      PRO_NO           ";
        $strSql = $strSql . ",       ''              PRO_ID	          ";
        $strSql = $strSql . ",       ''              PRO_NM           ";
        $strSql = $strSql . "        FROM                             ";
        $strSql = $strSql . "       HMENUKAISOUMST KAI                ";
        $strSql = $strSql . ",       (	                              ";
        $strSql = $strSql . "         SELECT                          ";
        $strSql = $strSql . "             A.SYS_KB                    ";
        $strSql = $strSql . "     ,       A.KAISOU_ID1	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID2	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID3	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID4	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID5	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID6	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID7	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID8	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID9	              ";
        $strSql = $strSql . "     ,       A.KAISOU_ID10               ";
        $strSql = $strSql . "     FROM                                ";
        $strSql = $strSql . "     HMENUKAISOUMST A                    ";
        $strSql = $strSql . "     INNER JOIN HMENUKANRIPATTERN B	  ";
        $strSql = $strSql . "     ON 	                              ";
        $strSql = $strSql . "      A.PRO_NO = B.PRO_NO                ";
        $strSql = $strSql . "     AND   A.SYS_KB     = B.SYS_KB	      ";
        $strSql = $strSql . "      WHERE                              ";
        $strSql = $strSql . "            B.PATTERN_ID = '$PATTERN_ID'  ";
        $strSql = $strSql . "      AND   B.STYLE_ID = '$STYLE_ID'        ";
        $strSql = $strSql . "      AND   A.SYS_KB     = '$SYS_KB'      ";
        $strSql = $strSql . "      ) V		                           ";
        $strSql = $strSql . "      WHERE                               ";
        $strSql = $strSql . "      KAI.KAISOU_ID1 = V.KAISOU_ID1       ";
        $strSql = $strSql . "AND     KAI.SYS_KB      = '$SYS_KB'       ";
        $strSql = $strSql . "AND     KAI.KAISOU_NM IS NOT NULL         ";
        $strSql = $strSql . "       GROUP BY                           ";
        $strSql = $strSql . "        KAI.KAISOU_ID1                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID2                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID3                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID4                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID5                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID6                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID7                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID8                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID9                   ";
        $strSql = $strSql . ",       KAI.KAISOU_ID10                  ";
        $strSql = $strSql . ",       KAI.KAISOU_NM                    ";
        $strSql = $strSql . ",       KAI.PRO_NO	                      ";
        $strSql = $strSql . "        UNION ALL                        ";
        $strSql = $strSql . "SELECT	                                  ";
        $strSql = $strSql . "        A.KAISOU_ID1  KAISOU_ID1         ";
        $strSql = $strSql . ",       A.KAISOU_ID2  KAISOU_ID2         ";
        $strSql = $strSql . ",       A.KAISOU_ID3  KAISOU_ID3         ";
        $strSql = $strSql . ",       A.KAISOU_ID4  KAISOU_ID4         ";
        $strSql = $strSql . ",       A.KAISOU_ID5  KAISOU_ID5         ";
        $strSql = $strSql . ",       A.KAISOU_ID6  KAISOU_ID6         ";
        $strSql = $strSql . ",       A.KAISOU_ID7  KAISOU_ID7         ";
        $strSql = $strSql . ",       A.KAISOU_ID8  KAISOU_ID8         ";
        $strSql = $strSql . ",       A.KAISOU_ID9  KAISOU_ID9         ";
        $strSql = $strSql . ",       A.KAISOU_ID10  KAISOU_ID10       ";
        $strSql = $strSql . ",       A.KAISOU_NM   KAISOU_NM	      ";
        $strSql = $strSql . ",       A.PRO_NO      PRO_NO	          ";
        $strSql = $strSql . ",       REPLACE(REPLACE(C.PRO_ID,'DPY/',''),'_','')     PRO_ID	          ";
        $strSql = $strSql . ",       C.PRO_NM      PRO_NM	          ";
        $strSql = $strSql . "  FROM                                   ";
        $strSql = $strSql . "    HMENUKAISOUMST A                     ";
        $strSql = $strSql . "INNER JOIN HMENUKANRIPATTERN B	          ";
        $strSql = $strSql . "ON 							          ";
        $strSql = $strSql . "       A.PRO_NO = B.PRO_NO               ";
        $strSql = $strSql . "AND   A.SYS_KB     = B.SYS_KB	          ";
        $strSql = $strSql . "AND   B.STYLE_ID = '$STYLE_ID'           ";
        $strSql = $strSql . "LEFT JOIN HPROGRAMMST C	              ";
        $strSql = $strSql . "ON			                              ";
        $strSql = $strSql . "       A.SYS_KB = C.SYS_KB               ";
        $strSql = $strSql . "AND   A.PRO_NO     = C.PRO_NO            ";
        $strSql = $strSql . "       WHERE                             ";
        $strSql = $strSql . "     B.PATTERN_ID = '$PATTERN_ID'        ";
        $strSql = $strSql . "AND   A.SYS_KB     = '$SYS_KB'	          ";
        $strSql = $strSql . ")		                                  ";
        $strSql = $strSql . "        ORDER BY                         ";
        $strSql = $strSql . "        KAISOU_ID1                       ";
        $strSql = $strSql . ",       KAISOU_ID2                       ";
        $strSql = $strSql . ",       KAISOU_ID3                       ";
        $strSql = $strSql . ",       KAISOU_ID4                       ";
        $strSql = $strSql . ",       KAISOU_ID5                       ";
        $strSql = $strSql . ",       KAISOU_ID6                       ";
        $strSql = $strSql . ",       KAISOU_ID7                       ";
        $strSql = $strSql . ",       KAISOU_ID8                       ";
        $strSql = $strSql . ",       KAISOU_ID9                       ";
        $strSql = $strSql . ",       KAISOU_ID10                      ";
        $strSql = $strSql . ",       PRO_NO	                          ";
        $strSql = $strSql . ",       KAISOU_NM                        ";

        return $strSql;
    }

    public function menu($STYLE_ID, $PATTERN_ID, $SYS_KB)
    {
        return parent::select($this->menuSql($STYLE_ID, $PATTERN_ID, $SYS_KB));
    }

    function menulistSql($user_id, $SYS_KB)
    {
        //20211109 LUJUNXIA UPD S
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
        $strSQL = str_replace("@SYS_KB", $SYS_KB, $strSQL);
        //$strSql = "SELECT STYLE_ID, PATTERN_ID FROM M_LOGIN WHERE SYS_KB = '$SYS_KB' AND USER_ID = '$user_id'";
        //return $strSql;
        return $strSQL;
        //20211109 LUJUNXIA UPD E
    }

    public function getmenulist($user_id, $SYS_KB)
    {

        $res = parent::select($this->menulistSql($user_id, $SYS_KB));
        return $res;
    }

    function chkUserAuthoritySql($user_id, $pro_id, $SYS_KB, $STYLE_ID)
    {
        $strSql = "";
        $strSql .= "SELECT   COUNT(*)" . "\r\n";
        $strSql .= "FROM     M_LOGIN LGI" . "\r\n";
        $strSql .= "         INNER JOIN HMENUKANRIPATTERN MKP" . "\r\n";
        $strSql .= "             ON LGI.SYS_KB = MKP.SYS_KB" . "\r\n";
        $strSql .= "            AND LGI.PATTERN_ID = MKP.PATTERN_ID" . "\r\n";
        $strSql .= "         INNER JOIN HPROGRAMMST PGM" . "\r\n";
        $strSql .= "             ON MKP.SYS_KB = PGM.SYS_KB" . "\r\n";
        $strSql .= "            AND MKP.PRO_NO = PGM.PRO_NO" . "\r\n";
        $strSql .= "WHERE    LGI.SYS_KB  = '$SYS_KB'" . "\r\n";
        $strSql .= "AND      LGI.STYLE_ID = '$STYLE_ID' " . "\r\n";
        $strSql .= "AND      LGI.USER_ID = '$user_id'" . "\r\n";
        $strSql .= "AND      PGM.PRO_ID  = '$pro_id'";
        return $strSql;
    }

    public function FncChkUserAuthority($user_id, $pro_id, $SYS_KB, $STYLE_ID)
    {

        $res = parent::select($this->chkUserAuthoritySql($user_id, $pro_id, $SYS_KB, $STYLE_ID));
        return $res;
    }

}