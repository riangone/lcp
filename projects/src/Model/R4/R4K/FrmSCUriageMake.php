<?php
namespace App\Model\R4\R4K;

use App\Model\Component\ClsComDb;

class FrmSCUriageMake extends ClsComDb
{
    public function selectsql()
    {
        $strSQL = "";
        $strSQL .= "SELECT ID " . "\r\n";
        $strSQL .= ",      (SUBSTR(SYR_YMD,1,4) || '/' || SUBSTR(SYR_YMD,5,2) || '/01') TOUGETU" . "\r\n";
        $strSQL .= ",      KISYU_YMD KISYU" . "\r\n";
        $strSQL .= ",      KI" . "\r\n";
        $strSQL .= "FROM   HKEIRICTL" . "\r\n";
        $strSQL .= "WHERE  ID = '01'";
        return $strSQL;
    }

    //コントロールマスタ存在ﾁｪｯｸ
    public function fncSelect()
    {
        return parent::select($this->selectsql());
    }

    public function fncGetCTLInfoSQL()
    {
        $strSQL = "";
        $strSQL .= "SELECT * " . "\r\n";
        $strSQL .= "  FROM HKEIRICTL" . "\r\n";
        $strSQL .= " WHERE ID　= '01'";
        return $strSQL;
    }

    public function fncGetCTLInfo()
    {
        return parent::select($this->fncGetCTLInfoSQL());
    }

    public function fncUpdateCTLInfoSQL($post1, $post2, $post3, $post4)
    {
        $strSQL = "";
        $strSQL .= " UPDATE HKEIRICTL";
        $strSQL .= " SET    UP_SYR_YMD = '@SYRYM'";
        $strSQL .= " ,      UP_DT_FROM = '@FROMDT'";
        $strSQL .= " ,      UP_DT_TO = '@TODT'";
        $strSQL .= " ,      UP_KB = '@KB'";
        $strSQL .= " WHERE ID = '01'";

        $strSQL = str_replace("@SYRYM", str_replace("/", "", $post1), $strSQL);
        $strSQL = str_replace("@FROMDT", str_replace("/", "", $post2), $strSQL);
        $strSQL = str_replace("@TODT", str_replace("/", "", $post3), $strSQL);
        $strSQL = str_replace("@KB", $post4, $strSQL);
        return $strSQL;
    }

    public function fncUpdateCTLInfo($post1, $post2, $post3, $post4)
    {
        return parent::Do_Execute($this->fncUpdateCTLInfoSQL($post1, $post2, $post3, $post4));
    }

    public function subSCURIUpdateSQL()
    {
        $strSQL = "";
        $strSQL .= "UPDATE HSCURI" . "\r\n";
        $strSQL .= "SET (" . "\r\n";
        $strSQL .= "    HSCURI.CARNO" . "\r\n";
        $strSQL .= "   ,HSCURI.SYADAI" . "\r\n";
        $strSQL .= "   ,HSCURI.SITEI_NO" . "\r\n";
        $strSQL .= "   ,HSCURI.RUIBETU_NO" . "\r\n";
        $strSQL .= "    ) = (" . "\r\n";
        $strSQL .= "    SELECT" . "\r\n";
        $strSQL .= "        HJYOUHEN.CARNO" . "\r\n";
        $strSQL .= "       ,HJYOUHEN.SYADAI" . "\r\n";
        $strSQL .= "       ,HJYOUHEN.SITEI_NO" . "\r\n";
        $strSQL .= "       ,HJYOUHEN.RUIBETU_NO" . "\r\n";
        $strSQL .= "    FROM HJYOUHEN" . "\r\n";
        $strSQL .= "    WHERE  HJYOUHEN.JKN_HKO_RIRNO = (SELECT MAX(JKN_HKO_RIRNO) FROM HJYOUHEN WHERE HJYOUHEN.CMN_NO = HSCURI.CMN_NO)" . "\r\n";
        $strSQL .= "        AND    HJYOUHEN.CMN_NO = HSCURI.CMN_NO" . "\r\n";
        $strSQL .= "    )" . "\r\n";
        $strSQL .= "WHERE  HSCURI.CARNO IS NULL" . "\r\n";
        $strSQL .= "AND    EXISTS(" . "\r\n";
        $strSQL .= "    SELECT 1" . "\r\n";
        $strSQL .= "    FROM   HJYOUHEN" . "\r\n";
        $strSQL .= "    WHERE  HJYOUHEN.CMN_NO = HSCURI.CMN_NO" . "\r\n";
        $strSQL .= ")";
        return $strSQL;
    }

    //**********************************************************************
    //処 理 名：車両情報補完更新
    //関 数 名：subSCURIUpdate
    //引    数：なし
    //戻 り 値：なし
    //処理説明：車両情報を補完更新する
    //**********************************************************************
    public function subSCURIUpdate()
    {
        return parent::Do_Execute($this->subSCURIUpdateSQL());
    }

    public function fncPrintSelectSQL($post1, $post2, $post3)
    {
        $strSQL = "";
        $strSQL .= "SELECT URI.CMN_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      URI.URI_BUSYO_CD ERR_MSG1" . "\r\n";
        $strSQL .= ",      NULL ERR_MSG2" . "\r\n";
        $strSQL .= ",      NULL ERR_MSG3" . "\r\n";
        $strSQL .= ",      '1' ERR_NO" . "\r\n";
        $strSQL .= ",      '@KIKAN_FROM' KIKANF" . "\r\n";
        $strSQL .= ",      '@KIKAN_TO' KIKANT" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = URI.URI_BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE  TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') >= '@KIKAN_FROM'" . "\r\n";
        $strSQL .= "AND    TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') <= '@KIKAN_JKNTO'" . "\r\n";
        if ($post3 == 1) {
            $strSQL .= "AND   URI.NAU_KB = '1'" . "\r\n";
        } else {
            if ($post3 == 2) {
                $strSQL .= "AND   URI.NAU_KB = '2'" . "\r\n";
            }
        }
        $strSQL .= "AND    BUS.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT URI.CMN_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      URI.URK_BUSYO_CD" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      '2' ERR_NO" . "\r\n";
        $strSQL .= ",      '@KIKAN_FROM' KIKANF" . "\r\n";
        $strSQL .= ",      '@KIKAN_TO' KIKANT" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "LEFT JOIN HBUSYO BUS" . "\r\n";
        $strSQL .= "ON     BUS.BUSYO_CD = URI.URK_BUSYO_CD" . "\r\n";
        $strSQL .= "WHERE  TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') >= '@KIKAN_FROM'" . "\r\n";
        $strSQL .= "AND    TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') <= '@KIKAN_JKNTO'" . "\r\n";
        if ($post3 == 1) {
            $strSQL .= "AND   URI.NAU_KB = '1'" . "\r\n";
        } else {
            if ($post3 == 2) {

                $strSQL .= "AND   URI.NAU_KB = '2'" . "\r\n";
            }
        }
        $strSQL .= "AND    BUS.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT URI.CMN_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      URI.URI_TANNO" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      '3' ERR_NO" . "\r\n";
        $strSQL .= ",      '@KIKAN_FROM' KIKANF" . "\r\n";
        $strSQL .= ",      '@KIKAN_TO' KIKANT" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "LEFT JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "WHERE  TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') >= '@KIKAN_FROM'" . "\r\n";
        $strSQL .= "AND    TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') <= '@KIKAN_JKNTO'" . "\r\n";
        if ($post3 == 1) {
            $strSQL .= "AND   URI.NAU_KB = '1'" . "\r\n";
        } else {
            if ($post3 == 2) {
                $strSQL .= "AND   URI.NAU_KB = '2'" . "\r\n";
            }
        }
        $strSQL .= "AND    SYA.SYAIN_NO IS NULL" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        //配属先ﾏｽﾀ(2007/04/03より)と今回作成分の売上データの部署が不一致の場合、エラー出力
        $strSQL .= "SELECT URI.CMN_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      URI.URI_TANNO" . "\r\n";
        $strSQL .= ",      URI.URK_BUSYO_CD" . "\r\n";
        $strSQL .= ",      HAI.BUSYO_CD" . "\r\n";
        $strSQL .= ",      '4' ERR_NO" . "\r\n";
        $strSQL .= ",      '@KIKAN_FROM' KIKANF" . "\r\n";
        $strSQL .= ",      '@KIKAN_TO' KIKANT" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "LEFT  JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= URI.KRI_DATE" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= URI.KRI_DATE" . "\r\n";
        $strSQL .= "WHERE  TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') >= '@KIKAN_FROM'" . "\r\n";
        $strSQL .= "AND    TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') <= '@KIKAN_JKNTO'" . "\r\n";
        if ($post3 == 1) {
            $strSQL .= "AND   URI.NAU_KB = '1'" . "\r\n";
        } else {
            if ($post3 == 2) {
                $strSQL .= "AND   URI.NAU_KB = '2'" . "\r\n";
            }
        }
        $strSQL .= "AND    (HAI.BUSYO_CD IS NULL" . "\r\n";
        $strSQL .= "        OR     URI.URK_BUSYO_CD <> HAI.BUSYO_CD" . "\r\n";
        $strSQL .= "        OR     URI.URK_BUSYO_CD <> HAI.SYUKEI_BUSYO_CD )" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT URI.CMN_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      HAI.SYOKUSYU_KB" . "\r\n";
        $strSQL .= ",      URI.NAU_KB" . "\r\n";
        $strSQL .= ",      NULL" . "\r\n";
        $strSQL .= ",      '5' ERR_NO" . "\r\n";
        $strSQL .= ",      '@KIKAN_FROM' KIKANF" . "\r\n";
        $strSQL .= ",      '@KIKAN_TO' KIKANT" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "INNER JOIN HSYAINMST SYA" . "\r\n";
        $strSQL .= "ON     SYA.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "INNER JOIN HHAIZOKU HAI" . "\r\n";
        $strSQL .= "ON     HAI.SYAIN_NO = URI.URI_TANNO" . "\r\n";
        $strSQL .= "AND    HAI.START_DATE <= URI.KRI_DATE" . "\r\n";
        $strSQL .= "AND    NVL(HAI.END_DATE,'99999999') >= URI.KRI_DATE" . "\r\n";
        $strSQL .= "WHERE  TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') >= '@KIKAN_FROM'" . "\r\n";
        $strSQL .= "AND    TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') <= '@KIKAN_JKNTO'" . "\r\n";
        if ($post3 == 1) {
            $strSQL .= "AND   URI.NAU_KB = '1'" . "\r\n";
        } else {
            if ($post3 == 2) {
                $strSQL .= "AND   URI.NAU_KB = '2'" . "\r\n";
            }
        }
        $strSQL .= "AND    HAI.SYOKUSYU_KB NOT IN ('1','2') " . "\r\n";
        $strSQL .= "AND    NVL(SYA.TAISYOKU_DATE,'99999999') >= URI.KRI_DATE" . "\r\n";
        $strSQL .= "UNION ALL" . "\r\n";
        $strSQL .= "SELECT URI.CMN_NO" . "\r\n";
        $strSQL .= ",      URI.UC_NO" . "\r\n";
        $strSQL .= ",      URI.URI_TANNO" . "\r\n";
        $strSQL .= ",      URI.URK_BUSYO_CD" . "\r\n";
        $strSQL .= ",      CMN.HNB_KTN_CD" . "\r\n";
        $strSQL .= ",      '6' ERR_NO" . "\r\n";
        $strSQL .= ",      '@KIKAN_FROM' KIKANF" . "\r\n";
        $strSQL .= ",      '@KIKAN_TO' KIKANT" . "\r\n";
        $strSQL .= ",      TO_CHAR(SYSDATE,'YYYY/MM/DD') TODAY" . "\r\n";
        $strSQL .= "FROM   HSCURI URI" . "\r\n";
        $strSQL .= "INNER JOIN HURIBUSYOCNV CNV" . "\r\n";
        $strSQL .= "ON     CNV.CMN_NO = URI.CMN_NO" . "\r\n";
        $strSQL .= "INNER JOIN M41E10 CMN" . "\r\n";
        $strSQL .= "ON     CMN.CMN_NO = URI.CMN_NO" . "\r\n";
        $strSQL .= "WHERE  TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') >= '@KIKAN_FROM'" . "\r\n";
        $strSQL .= "AND    TO_CHAR(URI.UPD_DATE,'YYYY/MM/DD') <= '@KIKAN_JKNTO'" . "\r\n";
        if ($post3 == 1) {
            $strSQL .= "AND   URI.NAU_KB = '1'" . "\r\n";
        } else {
            if ($post3 == 2) {
                $strSQL .= "AND   URI.NAU_KB = '2'" . "\r\n";
            }
        }
        $strSQL .= "ORDER BY UC_NO, ERR_NO" . "\r\n";
        $strSQL = str_replace("@KIKAN_FROM", $post1, $strSQL);
        $strSQL = str_replace("@KIKAN_TO", $post2, $strSQL);
        $strSQL = str_replace("@KIKAN_JKNTO", $post2, $strSQL);
        return $strSQL;
    }

    public function fncPrintSelect($post1, $post2, $post3)
    {
        return parent::select($this->fncPrintSelectSQL($post1, $post2, $post3));
    }

}