<?php
// 共通クラスの読込み
namespace App\Model\JKSYS;

use App\Model\Component\ClsComDb;
use App\Model\JKSYS\Component\ClsComFncJKSYS;
//*************************************
// * 処理名	：機能名Model相当の処理
// * 関数名	：機能名
// * 処理説明	：機能名のデータ処理クラス
//*************************************
class FrmJKSYSSyainSearch extends ClsComDb
{
    public $ClsComFncJKSYS;
    function fncDataSetSql($postData)
    {
        $this->ClsComFncJKSYS = new ClsComFncJKSYS();

        $strSQL = "SELECT";
        $strSQL .= "     NVL(IDO.BUSYO_CD,'') AS BUSYOCD";
        $strSQL .= "    ,NVL(BUS.BUSYO_NM,'') AS BUSYONM";
        $strSQL .= "    ,NVL(SYA.SYAIN_NO,'') AS SYAINNO";
        $strSQL .= "    ,NVL(SYA.SYAIN_NM,'') AS SYAINNM";
        $strSQL .= " FROM JKSYAIN SYA";
        $strSQL .= " INNER JOIN (SELECT   IDO2.SYAIN_NO";
        $strSQL .= "                           ,IDO2.ANNOUNCE_DT";
        $strSQL .= "                           ,IDO2.BUSYO_CD";
        $strSQL .= "                           ,IDO2.SYOKUSYU_CD";
        $strSQL .= "                   FROM    JKIDOURIREKI IDO2";
        $strSQL .= "                           INNER JOIN  (SELECT   IDO3.SYAIN_NO";
        $strSQL .= "                                                 ,MAX(IDO3.ANNOUNCE_DT) ANNOUNCE_DT";
        $strSQL .= "                                         FROM    JKIDOURIREKI IDO3";
        $strSQL .= "                                         WHERE   TO_CHAR(IDO3.ANNOUNCE_DT,'YYYYMMDD') <= '@ST_DT'";
        $strSQL .= "                                         GROUP BY IDO3.SYAIN_NO";
        $strSQL .= "                                        ) IDO4";
        $strSQL .= "                               ON    IDO2.SYAIN_NO = IDO4.SYAIN_NO";
        $strSQL .= "                               AND   IDO2.ANNOUNCE_DT = IDO4.ANNOUNCE_DT";
        $strSQL .= "                  ) IDO";
        $strSQL .= " ON    SYA.SYAIN_NO = IDO.SYAIN_NO";
        $strSQL .= " INNER JOIN JKBUMON BUS";
        $strSQL .= " ON    BUS.BUSYO_CD = IDO.BUSYO_CD";
        $strSQL .= " WHERE 1 = 1";
        $strSQL .= " AND   SYA.TAISYOKU_DT IS NULL" . "\r\n";

        if (trim($postData['txtBusyoCD']) != '') {
            $strSQL .= "   AND IDO.BUSYO_CD LIKE '@BUSYOCD%'";
        }

        if (trim($postData['txtSyainCD']) != '') {
            $strSQL .= "   AND SYA.SYAIN_NO LIKE '@SYAINNO%'";
        }

        if (trim($postData['txtSyainKN']) != '') {
            $strSQL .= "   AND SYA.SYAIN_KN LIKE '@SYAINKN%'";
        }

        $strSQL .= " ORDER BY IDO.BUSYO_CD";

        $strSQL = str_replace("@ST_DT", $postData['Kijyunbi'], $strSQL);
        $strSQL = str_replace("@ED_DT", $postData['Kijyunbi'], $strSQL);
        $strSQL = str_replace("@BUSYOCD", $this->ClsComFncJKSYS->FncNv($postData['txtBusyoCD']), $strSQL);
        $strSQL = str_replace("@SYAINNO", $this->ClsComFncJKSYS->FncNv($postData['txtSyainCD']), $strSQL);
        $strSQL = str_replace("@SYAINKN", $this->ClsComFncJKSYS->FncNv($postData['txtSyainKN']), $strSQL);

        return $strSQL;
    }

    public function fncDataSet($postData)
    {
        $strSql = $this->fncDataSetSql($postData);

        return parent::select($strSql);
    }

}
