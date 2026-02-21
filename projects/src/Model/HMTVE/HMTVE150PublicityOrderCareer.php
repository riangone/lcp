<?php
// 共通クラスの読込み
namespace App\Model\HMTVE;

use App\Model\Component\ClsComDb;
use Cake\Routing\Router;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class HMTVE150PublicityOrderCareer extends ClsComDb
{
    public $SessionComponent;
    public function getYMSQL()
    {
        $strSQL = "";
        $strSQL .= " SELECT MIN(IVENT_YM) IVENTMIN" . "\r\n";
        $strSQL .= ",       MAX(IVENT_YM)  IVENTMAX  " . "\r\n";
        $strSQL .= ",       ADD_MONTHS(SYSDATE,1) TD " . "\r\n";
        $strSQL .= " FROM   HDTPUBLICITYIVENT " . "\r\n";

        return parent::select($strSQL);
    }

    public function getShopNMSQL()
    {
        $strSQL = "";
        $strSQL .= "  SELECT MST.BUSYO_CD                                                 " . "\r\n";
        $strSQL .= "  ,       MST.BUSYO_RYKNM                                             " . "\r\n";
        $strSQL .= "  FROM   HBUSYO MST                                                   " . "\r\n";
        $strSQL .= "  INNER JOIN                                                          " . "\r\n";
        $strSQL .= "        (SELECT BUSYO_CD                                              " . "\r\n";
        $strSQL .= "         ,      (CASE                                                 " . "\r\n";
        $strSQL .= "                 WHEN HDT_TENPO_CD IS NOT NULL      THEN HDT_TENPO_CD " . "\r\n";
        $strSQL .= "                 ELSE BUSYO_CD END)  V_TENPO                          " . "\r\n";
        $strSQL .= "         FROM  HBUSYO) BUS                                            " . "\r\n";
        $strSQL .= "  ON     MST.BUSYO_CD = BUS.V_TENPO                                   " . "\r\n";
        $strSQL .= "  WHERE  MST.HDT_TENPO_DISP_NO IS NOT NULL AND                        " . "\r\n";
        $strSQL .= "         BUS.BUSYO_CD = '@BUSYOCD'                                    " . "\r\n";
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        return parent::select($strSQL);
    }

    public function getgrdExViewSQL($ddlDataStart, $ddlDataEnd)
    {
        $strSQL = "";
        $strSQL .= " SELECT START_DATE, KBN, HIDUKE, IVENT_NM, HINMEI, TANKA, SURYO, KINGAKU          " . "\r\n";
        $strSQL .= " FROM ( SELECT IVDT.START_DATE                                                    " . "\r\n";
        $strSQL .= "        ,      1 KBN                                                              " . "\r\n";
        $strSQL .= "        ,      TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') || '～' ||  " . "\r\n";
        $strSQL .= "               TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') HIDUKE        " . "\r\n";
        $strSQL .= "        ,      IVDT.IVENT_NM                                                      " . "\r\n";
        $strSQL .= "        ,      GOODS.HINMEI1 HINMEI	                                             " . "\r\n";
        $strSQL .= "        ,      GOODS.TANKA1 TANKA                                                 " . "\r\n";
        $strSQL .= "        ,      NVL(DATA.ORDER_VAL1,0) SURYO                                       " . "\r\n";
        $strSQL .= "        ,      (NVL(DATA.ORDER_VAL1,0) * GOODS.TANKA1) KINGAKU                    " . "\r\n";
        $strSQL .= "        FROM   HDTIVENTDATA IVDT                                                  " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYIVENT PLIV                                  " . "\r\n";
        $strSQL .= "               ON         PLIV.START_DATE = IVDT.START_DATE                       " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYGOODS GOODS                                 " . "\r\n";
        $strSQL .= "               ON         GOODS.IVENT_YM = PLIV.IVENT_YM                          " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYDATA DATA	                                 " . "\r\n";
        $strSQL .= "               ON         DATA.START_DATE = IVDT.START_DATE                       " . "\r\n";
        $strSQL .= "               AND    DATA.BUSYO_CD = '@BUSYOCD'                                  " . "\r\n";
        $strSQL .= "        WHERE  PLIV.IVENT_YM >= '@STDT'                                           " . "\r\n";
        $strSQL .= "        AND    PLIV.IVENT_YM <= '@EDDT'                                           " . "\r\n";
        $strSQL .= "        UNION ALL                                                                 " . "\r\n";
        $strSQL .= "        SELECT IVDT.START_DATE                                                    " . "\r\n";
        $strSQL .= "        ,      2 KBN			                                                     " . "\r\n";
        $strSQL .= "        ,      TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') || '～' ||  " . "\r\n";
        $strSQL .= "               TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') HIDUKE        " . "\r\n";
        $strSQL .= "        ,      IVDT.IVENT_NM                                                      " . "\r\n";
        $strSQL .= "        ,      GOODS.HINMEI2                                                      " . "\r\n";
        $strSQL .= "        ,      GOODS.TANKA2                                                       " . "\r\n";
        $strSQL .= "        ,      NVL(DATA.ORDER_VAL2,0) SURYO                                       " . "\r\n";
        $strSQL .= "        ,      (NVL(DATA.ORDER_VAL2,0) * GOODS.TANKA2) KINGAKU                    " . "\r\n";
        $strSQL .= "        FROM   HDTIVENTDATA IVDT                                                  " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYIVENT PLIV                                  " . "\r\n";
        $strSQL .= "               ON         PLIV.START_DATE = IVDT.START_DATE                       " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYGOODS GOODS                                 " . "\r\n";
        $strSQL .= "               ON         GOODS.IVENT_YM = PLIV.IVENT_YM                          " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYDATA DATA                                   " . "\r\n";
        $strSQL .= "               ON         DATA.START_DATE = IVDT.START_DATE                       " . "\r\n";
        $strSQL .= "               AND        DATA.BUSYO_CD = '@BUSYOCD'                              " . "\r\n";
        $strSQL .= "        WHERE  PLIV.IVENT_YM >= '@STDT'                                           " . "\r\n";
        $strSQL .= "        AND    PLIV.IVENT_YM <= '@EDDT'                                           " . "\r\n";
        $strSQL .= "        UNION ALL                                                                 " . "\r\n";
        $strSQL .= "        Select IVDT.START_DATE                                                    " . "\r\n";
        $strSQL .= "        ,      3 KBN                                                              " . "\r\n";
        $strSQL .= "        ,      TO_CHAR(TO_DATE(IVDT.START_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') || '～' ||  " . "\r\n";
        $strSQL .= "               TO_CHAR(TO_DATE(IVDT.END_DATE,'YYYY/MM/DD'),'YYYY/MM/DD') HIDUKE        " . "\r\n";
        $strSQL .= "        ,      IVDT.IVENT_NM                                                      " . "\r\n";
        $strSQL .= "        ,      GOODS.HINMEI3                                                      " . "\r\n";
        $strSQL .= "        ,      GOODS.TANKA3                                                       " . "\r\n";
        $strSQL .= "        ,      NVL(DATA.ORDER_VAL3,0) SURYO                                       " . "\r\n";
        $strSQL .= "        ,      (NVL(DATA.ORDER_VAL3,0) * GOODS.TANKA3) KINGAKU                    " . "\r\n";
        $strSQL .= "        FROM   HDTIVENTDATA IVDT                                                  " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYIVENT PLIV                                  " . "\r\n";
        $strSQL .= "               ON         PLIV.START_DATE = IVDT.START_DATE                       " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYGOODS GOODS                                 " . "\r\n";
        $strSQL .= "               ON         GOODS.IVENT_YM = PLIV.IVENT_YM                          " . "\r\n";
        $strSQL .= "               INNER JOIN HDTPUBLICITYDATA DATA                                   " . "\r\n";
        $strSQL .= "               ON         DATA.START_DATE = IVDT.START_DATE                       " . "\r\n";
        $strSQL .= "               AND        DATA.BUSYO_CD = '@BUSYOCD'                              " . "\r\n";
        $strSQL .= "        WHERE  PLIV.IVENT_YM >= '@STDT'                                           " . "\r\n";
        $strSQL .= "        AND    PLIV.IVENT_YM <= '@EDDT' )                                         " . "\r\n";
        $strSQL .= " ORDER BY START_DATE                                                              " . "\r\n";
        $strSQL .= " ,        KBN                                                                     " . "\r\n";
        $this->SessionComponent = Router::getRequest()->getSession();
        $strSQL = str_replace("@BUSYOCD", $this->SessionComponent->read('BusyoCD'), $strSQL);
        $strSQL = str_replace("@STDT", $ddlDataStart, $strSQL);
        $strSQL = str_replace("@EDDT", $ddlDataEnd, $strSQL);
        return parent::select($strSQL);
    }

}

